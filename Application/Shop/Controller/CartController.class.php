<?php
namespace Shop\Controller;

use Home\Controller\BaseController;

/**
 * 购物车控制器
 */
class CartController extends BaseController
{
  public function _initialize()
  {
    parent::_initialize();
    $this->assign('gl',4);
    $this->check_login();

  }

  /**
   * 购物车页
   */
  public function index()
  {
    $cart_object = M('shop_cart');
    $uid = $this->uid;
    if(IS_AJAX){
      $page = I('post.n') ? I('post.n') : 1;
      $limit = 10;
      $start = ($page - 1) * $limit;
      //购物车基础信息
      $cart_map['oc_shop_cart.uid']=$uid;
      $cart_map['oc_shop_cart.status']=1;
      $cart_map['oc_shop_goods.status']=1;
      $where['uid']=$uid;
      $where['status']=1;
      $cart_list =$cart_object->where($where)->limit($start,$limit)->select();

      if($cart_list){
        foreach($cart_list as $m=>$t) {
          $goods = M('shop_goods')->field('title,cover,sale_price')->where(['id' => $t['gid'], 'status' => 1])->find();
          if ($goods) {
            if ($t['key']) {
              $cart_list[$m]['sale_price'] = M('spec_goods_price')->where(['goods_id' => $t['gid'], 'key' => $t['key']])->getField('shop_price');
              if(!$cart_list[$m]['sale_price']){
                  unset($cart_list[$m]);
              }
            } else {
              $price = $goods['sale_price'];
              $cart_list[$m]['sale_price'] = $price;
            }
            $cart_list[$m]['title'] = $goods['title'];
            $cart_list[$m]['cover'] = $goods['cover'];
          }else{
            unset($cart_list[$m]);
          }
        }
      }
      $info_other  =$cart_list;
      $info = [];
      foreach ($info_other as $key => $value) {
        $value['seckill_price'] ='';
        $value['stock'] ='0';
        $value['seckill_sales'] ='0';
        $info[$value['id']] = $value;
      }

      $nowTime = datetime();
      //是否有打折商品
      $info_seckill = $cart_object
                ->join('oc_shop_goods ON oc_shop_goods.id = oc_shop_cart.gid','LEFT')
                ->join('oc_shop_seckill_goods ON oc_shop_cart.gid = oc_shop_seckill_goods.goods_id','LEFT')
                ->join('oc_shop_seckill ON oc_shop_seckill.id = oc_shop_seckill_goods.seckill_id','LEFT')
                ->field('oc_shop_goods.title,
                         oc_shop_goods.cover,
                         oc_shop_goods.sale_price,
                         oc_shop_cart.id,
                         oc_shop_cart.gid,
                         oc_shop_cart.goodsnum,
                         oc_shop_seckill_goods.seckill_price,
                         oc_shop_seckill_goods.id AS  sgid,
                         oc_shop_seckill_goods.stock,
                         oc_shop_seckill_goods.seckill_sales
                  ')
                ->where(['oc_shop_cart.uid'=>$uid,
                         'oc_shop_cart.status'=>1,
                         'oc_shop_goods.status'=>1,
                         'oc_shop_seckill_goods.status'=>1,
                         'oc_shop_seckill.status'=>1,
                         'oc_shop_seckill.start_time'=>['lt',$nowTime],
                         'oc_shop_seckill.end_time'=>['gt',$nowTime]
                      ])
                ->limit($start,$limit)
                ->select();

      foreach ($info_seckill as $key => $value) {

        //秒杀 数量控制
        if($value['stock'] > $value['seckill_sales']){

          $value['sale_price'] = $value['seckill_price'];
          $stock_plus = $value['stock'] - $value['seckill_sales'];

          if($stock_plus < $value['goodsnum']){

            $value['goodsnum'] = $stock_plus;
            if(!$cart_object->save(['id'=>$value['id'],'goodsnum'=>$stock_plus])){
              $this->error('数据异常，请稍后再试！');
            }
          }
        }
        $info[$value['id']] = $value;
      }

      if($info){
        $this->assign('info',$info);
        $html =$this->fetch('ajax_index_list');
        $result =array('status'=>1,'msg'=>$html);
      }else{
        if($page ==1){
          $result = array('status' => 0, 'msg' => '');
        }else {
          $result = array('status' => 1, 'msg' => '');
        }
      }
      $this->ajaxReturn($result);
    }else{
      //为您推荐 浏览过的商品按销量排序 6件; 去除 已经在购物车里的
      $cart_info = $cart_object->where(['uid'=>$uid,'status'=>1])->field('gid')->select();
      if(!empty($cart_info)){
        foreach ($cart_info as $key => $value) {
          if($key==0){
            $gids_str = $value['gid'];
          }else{
            $gids_str = $gids_str.','.$value['gid'];
          }
        }
      }
      if(!empty($gids_str)){
        $map['id'] = ['NOT IN',$gids_str];
      }
      $map['status'] =1;
      $map['group'] = 1;
      $rec_list = M('shop_goods')
                  ->field('id,title,original_price,sale_price,cover')
                  ->where($map)
                  ->order('sales_volume DESC')
                  ->limit(6)
                  ->select();
      if(!empty($rec_list)){
        $check_ids='';
        foreach ($rec_list as $key => $value) {
          $rec_list[$key]['seckill_price'] = '';
          if($key==0){
            $check_ids  = $value['id'];
          }else{
            $check_ids .=','.$value['id'];
          }
        }
        $nowTime = datetime();
        $seckill_info = M('shop_seckill_goods')
                        ->join('oc_shop_seckill ON oc_shop_seckill.id = oc_shop_seckill_goods.seckill_id','LEFT')
                        ->field('oc_shop_seckill_goods.goods_id,
                                 oc_shop_seckill_goods.stock,
                                 oc_shop_seckill_goods.seckill_sales,
                                 oc_shop_seckill_goods.seckill_price
                                ')
                        ->where(['oc_shop_seckill_goods.goods_id'=>['IN',$check_ids],
                                 'oc_shop_seckill.start_time'=>['lt',$nowTime],
                                 'oc_shop_seckill.end_time'=>['gt',$nowTime],
                                 'oc_shop_seckill.status'=>1,
                                 'oc_shop_seckill_goods.status'=>1
                                ])
                        ->select();
        if(!empty($seckill_info)){
          foreach ($rec_list as $key => $value) {
            foreach ($seckill_info as $k => $v) {
              if($value['id']==$v['goods_id'] && $v['stock']>$v['seckill_sales']){
               $rec_list[$key]['seckill_price'] = $v['seckill_price'];
              }
            }
          }
        }


        $cart_map['oc_shop_cart.uid']=$uid;
        $cart_map['oc_shop_cart.status']=1;
        $cart_map['oc_shop_goods.status']=1;
        $cart_num = $cart_object
                      ->join('oc_shop_goods ON oc_shop_goods.id = oc_shop_cart.gid','LEFT')
                      ->where($cart_map)
                      ->count();

        $this->assign('cart_num',$cart_num);
        $this->assign('rec_list',$rec_list);
        $this->display();
      }
    }
  }

  /**
   * 删除购物车商品
   */
  public function delete()
  {
    if(IS_AJAX){
      $ids = I('ids');
      $ids_str = implode(',', $ids);
      $map['id'] = ['IN',$ids_str];
      $res  = M('shop_cart')->where($map)->delete();
      if($res){
        $this->success();
      }else{
        $this->error('系统繁忙请稍后再试！');
      }
    }
  }

  /*
   * 改变单个商品数量
   */
  public function changeGoodsNum()
  {
    if(IS_AJAX){
      $cid = I('cid');
      $num = I('num');

      $res = M('shop_cart')->where(['id'=>$cid])->save(['goodsnum'=>$num]);

      if($res!==false){
        $this->success();
      }else{
        $this->error();
      }

    }
  }

  /**
   * 购物车里商品数量
   */
  public function cart_num()
  {
    $uid    = $this->userInfo['id'];
    $num    = M('shop_cart')
                ->join('oc_shop_goods ON oc_shop_goods.id = oc_shop_cart.gid','LEFT')
                ->field('oc_shop_cart.id')
                ->where(['oc_shop_cart.uid' => $uid,
                         'oc_shop_cart.status'=>1,
                         'oc_shop_goods.status'=>1])
                ->sum('oc_shop_cart.goodsnum');
    $result = array('num' => $num);
    $this->ajaxReturn($result);
  }


  /**
   * 结算页
   */
  public function confirm_pay()
  {
    $uid = $this->uid;
    $num = I('goodsnum','')?:I('buynums',1);
    $action =I('action','');
    $nowTime = datetime();
    $ids= I('ids','');
    $cartids  = I('checkbox/a', []);
    if (empty($cartids) && session('cartids')) {
      $cartids = session('cartids');
    }
    session('cartids', $cartids);

    //避免冲突 重新定义商品id
    if(!empty($cartids)){
      $cart_gid = M('shop_cart')->field('gid')->where(['id'=>['IN',$cartids]])->select();
      $gids = implode(',',array_column($cart_gid,'gid'));
    }else{
      $gids = $ids;
    }

    if(empty($gids)){
      ptrace('普通商品结算页,商品ID为空');
      $this->redirect('Shop/index/index');
    }


    $spec_key =I('key','');
    $from_url= U('confirm_pay', ['action' => $action, 'ids' => $ids,'goodsnum' =>$num, 'key' => $spec_key]);
    cookie('from',$from_url,36000);


//========================地址====================================//
    $address_id = cookie('default_address_id');
    if(empty($address_id)){
      $addr_map['uid']    = $uid;
      $addr_map['status'] = 1;
      $addr_map['default'] = 1;
    }else{
      $addr_map['id'] = $address_id;
    }
    $address_info = M('shop_address')
                  ->where($addr_map)
                  ->find();
    $this->assign('address_info',$address_info);
//========================地址END====================================//

//========================支付方式========================//
    if(cookie('default_pay_type')){
      $pay_type =cookie('default_pay_type');
    }else{
      $pay_type =1;
    }
    $orderModel = new \Shop\Model\GoodsorderModel();
    $pay_name =$orderModel->pay_types[$pay_type];
    $this->assign('pay_type',$pay_type);
    $this->assign('pay_name',$pay_name);
//========================支付方式END========================//

//=========================发票=============================//
    if(cookie('default_receipt_type')){
      // $receipt_tit_de = [1=>'个人',2=>'公司'];
      $receipt_title =cookie('default_receipt_title');
      $receipt_type =cookie('default_receipt_type')?:1;
      $receipt_tit =cookie('default_receipt_tit')?:1;
      // $receipt_tit_en = $receipt_tit_de[$receipt_tit];
    }else{
      $receipt_title ='';
      $receipt_type ='';
      $receipt_tit ='';
      $receipt_tit_en = '';

    }

    // $this->assign('receipt_tit_en',$receipt_tit_en);
    $this->assign('receipt_title',$receipt_title);
    $this->assign('receipt_type',$receipt_type);
    $this->assign('receipt_tit',$receipt_tit);
//=========================发票END=============================//

// ========================优惠券========================//
    //检查是否有优惠券
    $coupons_model =new \Shop\Model\CouponModel();
    $coupons_info = $coupons_model ->getCoupons($uid,$gids);

    $check_coupons = 0; //是否可用优惠券
    $coupon_info = '';  //优惠券信息
    $coupon_price = 0;  //优惠价
    if(!empty($coupons_info['can'])){
      $check_coupons = 1;
      //是否选择优惠券
      if(I('from','')=='coupons' && !empty(cookie('default_coupons'))){
        $coupon_id =  cookie('default_coupons');
        foreach ($coupons_info['can'] as $key => $value) {
          if($value['id']==$coupon_id){ //可用优惠券中 有 cookie中的 id 更改变量
            $coupon_info = $value;
            $coupon_price = $value['price'];

          }
        }
      }
    }

    $this->assign('coupon_gid',$gids);
    $this->assign('check_coupons',$check_coupons);
    $this->assign('coupons_info',$coupon_info);
//=========================优惠券END=====================//

    if($action=='fromcart'){
//=========================购物车购买=====================//
      $cart_ids          = $cartids;
      $map['oc_shop_cart.id'] = ['IN', $cart_ids];
      $map['oc_shop_goods.status'] = 1;
      $map['oc_shop_cart.uid'] = $uid;
      $info = [];
      $back_integral=0; //总返回积分
      $total_price=0; //总价
      $cart_object = M('shop_cart');
      //1.正常信息
      $cart_info = $cart_object
              ->join('oc_shop_goods ON oc_shop_goods.id = oc_shop_cart.gid')
              ->field('  oc_shop_cart.id,
                       oc_shop_goods.title,
                       oc_shop_cart.key,
                       oc_shop_goods.cover,
                       oc_shop_goods.original_price,
                       oc_shop_goods.back_integral,
                       oc_shop_goods.sale_price,
                       oc_shop_goods.postage,
                       oc_shop_cart.gid,
                       oc_shop_cart.goodsnum
                      ')
              ->where($map)
              ->select();

      foreach($cart_info as $m => $t) {
        if($t['key']){
          $cart_info[$m]['sale_price'] = M('spec_goods_price')->where(['goods_id' => $t['gid'], 'key' => $t['key']])->getField('shop_price');
        }
      }

      foreach ($cart_info as $key => $value) {
        $value['seckill_price'] = '';
        $info[$value['gid']] = $value;
        $back_integral += $value['back_integral']*$value['goodsnum'];//返回积分
      }

      //2.秒杀 信息 替换正常信息
      $info_seckill = M('shop_seckill_goods')
                      ->join('oc_shop_seckill ON oc_shop_seckill.id = oc_shop_seckill_goods.seckill_id','LEFT')
                      ->field('oc_shop_seckill_goods.goods_id,
                               oc_shop_seckill_goods.stock,
                               oc_shop_seckill_goods.seckill_sales,
                               oc_shop_seckill_goods.seckill_price
                              ')
                      ->where(['oc_shop_seckill_goods.goods_id'=>['IN',$gids],
                               'oc_shop_seckill_goods.status'=>1,
                               'oc_shop_seckill.status'=>1,
                               'oc_shop_seckill.start_time'=>['lt',$nowTime],
                               'oc_shop_seckill.end_time'=>['gt',$nowTime]
                              ])
                      ->select();

      if(!empty($info_seckill)){
        foreach ($info_seckill as $key => $value) {
          if($value['stock']>$value['seckill_sales']){ //有库存
            //秒杀价
            $info[$value['goods_id']]['seckill_price'] = $value['seckill_price'];
            $stock_plus = $value['stock'] - $value['seckill_sales']; //剩余数量
            //购买数量大于库存
            if($info[$value['goods_id']]['goodsnum'] > $stock_plus){
              $info[$value['goods_id']]['goodsnum'] = $stock_plus;
              M('shop_cart')->where(['gid'=>$value['goods_id'],'uid'=>$uid])->save(['goodsnum'=>$stock_plus]);
            }
          }
        }
      }
      foreach ($info as $key => $value) {
        $price = $value['seckill_price']?$value['seckill_price']:$value['sale_price'];
        $total_price += $price * $value['goodsnum'];
      }


      cookie('fromcartids', implode(',', $cartids));
//=========================购物车购买END=====================//

    }else{
//=========================立即支付================================//
      //商品信息
      $goods_info = M('shop_goods')
                    ->field('id,title,cover,original_price,sale_price,postage,back_integral')
                    ->where(['id'=>$ids])->find();
      $goods_info['goodsnum']=$num;
      $goods_info['spec_key'] =$spec_key;
      $goods_info['seckill_price']='';
      $back_integral = $goods_info['back_integral'];//返回积分
      //当前秒杀信息
      $info_seckill = M('shop_seckill_goods')
                      ->join('oc_shop_seckill ON
                              oc_shop_seckill_goods.seckill_id = oc_shop_seckill.id','LEFT')
                      ->field('oc_shop_seckill_goods.seckill_price,
                               oc_shop_seckill_goods.stock,
                               oc_shop_seckill_goods.seckill_sales
                              ')
                      ->where(['oc_shop_seckill_goods.goods_id'=>$ids,
                               'oc_shop_seckill.start_time'=>['lt',$nowTime],
                               'oc_shop_seckill.end_time'=>['gt',$nowTime],
                               'oc_shop_seckill.status'=>1,
                               'oc_shop_seckill_goods.status'=>1
                              ])
                      ->find();

      if(!empty($spec_key)){
        $goods_info['sale_price'] =M('spec_goods_price')->where(['goods_id'=>$ids,'key'=>$spec_key])->getField('shop_price')?:$goods_info['sale_price'];
      }
      if(!empty($info_seckill) && $info_seckill['stock']>$info_seckill['seckill_sales']){
        $goods_info['seckill_price'] = $info_seckill['seckill_price'];
      }

      $price = $goods_info['seckill_price']?$goods_info['seckill_price']:$goods_info['sale_price'];
      $total_price = $price * $goods_info['goodsnum'];
      $info[$goods_info['id']] = $goods_info;
//==================================立即支付================================//
    }


//===================================运费====================================//
    $postage_info = M('shop_set')->field('postage_free,postage_total,integral_rate')->find();
    $integral_rate = 1/$postage_info['integral_rate'];

    if($total_price>$postage_info['postage_free']){
      $postage = 0;
    }else if($action=='buynow'){
      $postage = $info[$ids]['postage'];
    }else{
       $postage = $postage_info['postage_total'];
    }
    $this->assign('total_price',$total_price);      //单价*数量
    $this->assign('postage',$postage);

//===================================运费END====================================//


//=================================用户积分===================================//
    $user_score = M('admin_user')->where(['id'=>$uid])->getField('score');
    $this->assign('user_score',$user_score);
//=================================用户积分END=================================//


    $this->assign('action',$action);
    $this->assign('spec_key',$spec_key);
    $this->assign('num',$num);
    $this->assign('ids',$ids);
    $this->assign('integral_rate',$integral_rate);
    $this->assign('back_integral',$back_integral);  //返还总积分
    $this->assign('info',$info);
    $this->display();
  }



}