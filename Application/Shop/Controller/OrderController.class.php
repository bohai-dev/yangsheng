<?php
namespace Shop\Controller;

use Home\Controller\BaseController;

/**
 * 订单控制器
 */
class OrderController extends BaseController
{
  public function _initialize()
  {
    parent::_initialize();
    $this->check_login();
  }

  //生成订单
  public function addOrder()
  {
    if(IS_AJAX){
      $nowTime = datetime();
      $post = I('post.');

      $uid = $this->uid;

//=========================地址信息=====================================//
      $address_info = M('shop_address')
                ->field('prov,city,country,detail,realname,phone')
                ->where(['id'=>$post['address']])
                ->find();
      $data['address_detail']= $address_info['prov'].$address_info['city'].$address_info['country'].$address_info['detail'];
      $data['address_phone'] = $address_info['phone'];
      $data['address_realname'] = $address_info['realname'];
//=========================地址信息END=====================================//

      $set_info = M('shop_set')->field('integral_rate,postage_total,postage_free')->find();
      if(empty($address_info)|| empty($set_info)){
        $this->error('服务器异常,请稍后再试');
      }

//=============================积分抵扣===========================//
      $post_integral = empty($post['integral'])?0:$post['integral'];//用户输入的抵扣积分
      //判断 是否超过该用户持有的积分
      if(!empty($post_integral)){
        $total_integral = M('admin_user')->where(['id'=>$uid])->getfield('score');
        $post_integral = $post_integral>$total_integral?$total_integral:$post_integral;
      }

      $integral = 0; //抵扣积分换算成RMB
      if(!empty($post_integral)){
        $integral_rate = $set_info['integral_rate'];
        $integral_rate = 1/$integral_rate;
        $integral = $integral_rate * $post_integral;
      }

      $data['integral'] =$post_integral; //本单抵扣积分
//=============================积分抵扣END==========================//


//==============================优惠券===============================//
      $coupon_id = empty($post['true_pay_coupon'])?0:$post['true_pay_coupon'];
      $privilege = 0;
      if($coupon_id){
        $privilege = M('shop_coupon')->where(['id'=>$coupon_id])->getfield('price');
      }
      $data['privilege'] = $privilege; //优惠价格
      $data['coupon'] = $coupon_id;//优惠券id

//==============================优惠券END===============================//


      $data['type'] = 'normal';      //默认订单类型
      //支付类型  1微信支付 2支付宝支付 4货到付款
      $data['pay_type'] = empty($post['true_pay_type'])?1:$post['true_pay_type'];
      $integral_pay = empty($post['integral_pay'])?0:$post['integral_pay'];//积分+RMB购


//==========================购物车购买==============================================//
      if($post['action']=='fromcart'){
        $cartids     = cookie('fromcartids');
        if(empty($cartids)){
          $this->error('购物车已空');
        }
        $cart_ids    = explode(',', $cartids);
        //购物车过来 多商品
        $goods_info = M('shop_cart')
            ->join('oc_shop_goods ON oc_shop_goods.id = oc_shop_cart.gid','LEFT')
            ->field('oc_shop_cart.id,
                     oc_shop_goods.title,
                     oc_shop_goods.sale_price,
                     oc_shop_goods.back_integral,
                     oc_shop_cart.key,
                     oc_shop_goods.postage,
                     oc_shop_goods.group,
                     oc_shop_cart.gid,
                     oc_shop_cart.goodsnum
                  ')
            ->where(['oc_shop_cart.id'=>['IN',$cart_ids],'uid'=>$uid])
            ->select();
        $gids =array_column($goods_info,'gid');
        foreach($goods_info as $m => $t) {
          if($t['key']){
            $goods_info[$m]['sale_price'] = M('spec_goods_price')->where(['goods_id' => $t['gid'], 'key' => $t['key']])->getField('shop_price');
          }
        }
        $new_goods_info = [];
        foreach ($goods_info as $key => $value) {
          $value['price'] = $value['sale_price'];
          $new_goods_info[$value['gid']] = $value;
        }

      }else{
//===========================购物车购买END===========================================//

//===========================立即购买========================================//
        $gids =$post['gid'];
        $num = $post['buynum'];
        //商品详情页 立即购买
        $goods_info = M('shop_goods')
                      ->field('id,group,title,sale_price,
                               postage,integral_price,
                               sale_integral,back_integral
                              ')
                      ->where(['id'=>$gids])
                      ->find();
        $goods_info['gid']      = $gids;
        $goods_info['goodsnum'] = $num;
        //规格
        $spec_key =I('goodskey','');
        if($spec_key){
          $spec_price =M('spec_goods_price')->where(['goods_id'=>$gids,'key'=>$spec_key])->getField('shop_price');

          $goods_info['sale_price'] = $spec_price?:$goods_info['sale_price'];
          $goods_info['key'] =$spec_key;
        }

        $goods_info['price'] = $goods_info['sale_price'];

        //积分商品 购买
        if($goods_info['group']==4 && $integral_pay==1){
          $check_order_info = '';
          // $check_order_info = M('shop_order')
          //   ->join('oc_shop_order_item ON oc_shop_order_item.order_id = oc_shop_order.id','LEFT')
          //   ->where(['oc_shop_order_item.goods_id'=>$gids,'oc_shop_order.uid'=>$uid,'oc_shop_order.type'=>'coin'])
          //   ->field('oc_shop_order.id')
          //   ->find();
          // 判断 用户是否积分购买过该商品
          if(empty($check_order_info)){
            $data['type'] = 'coin';  //订单类型 为积分购买
            $total_integral = M('admin_user')->where(['id'=>$uid])->getfield('score');
            $sale_integral  = $goods_info['sale_integral']*$num;    //积分购买的积分价格
            $integral_price = $goods_info['integral_price']*$num;  //积分购买的

            if($total_integral<$sale_integral){
              //积分不足 RMB补差
              $makeup_integral = $sale_integral - $total_integral;
              $integral_rate = 1/$set_info['integral_rate'];

              $goods_info['price'] = $integral_price+$makeup_integral*$integral_rate;
              $goods_info['price'] = round($goods_info['price'],2);
              $data['integral']   =   $total_integral; //本单所用积分
              $data['makeup']     =   $goods_info['price']; //本单 补差价
            }else{
              $goods_info['price'] = $integral_price;
              $data['integral']   = $sale_integral;//本单所用积分
            }

            //无需付RMB  支付方式为积分支付
            // if($goods_info['price']==0){
            //   $data['pay_type']   =   3;
            // }
          }
        }
        $new_goods_info[$gids] = $goods_info;
      }
//===================================立即购买END=============================//




//=====================================秒杀信息================================//
      //获取现在存在的秒杀商品信息
      $seckill_info = M('shop_seckill')
                      ->join('oc_shop_seckill_goods ON oc_shop_seckill_goods.seckill_id = oc_shop_seckill.id','LEFT')
                      ->field('oc_shop_seckill_goods.goods_id,
                               oc_shop_seckill_goods.seckill_price,
                               oc_shop_seckill_goods.stock,
                               oc_shop_seckill_goods.seckill_sales,
                               oc_shop_seckill_goods.id
                               ')
                      ->where(['oc_shop_seckill_goods.goods_id'=>['IN',$gids],
                                'oc_shop_seckill.start_time'=>['lt',$nowTime],
                                'oc_shop_seckill.end_time'=>['gt',$nowTime]
                              ])
                      ->select();

      //判断是否有秒杀
      if(!empty($seckill_info)){
        foreach ($seckill_info as $key => $value) {
          //购买的商品里时候有秒杀商品并且有库存
          if(!empty($new_goods_info[$value['goods_id']]) && $value['stock']>$value['seckill_sales']){
            //改变商品单价
            $new_goods_info[$value['goods_id']]['price'] = $value['seckill_price'];
            $new_goods_info[$value['goods_id']]['seckill_check'] =$value['id'];
          }
        }
      }
//=====================================秒杀信息END=============================//

//====================================总价&支付价&运费================================//
      $total_price = 0; //总价
      $payment = 0;     //支付价
      $check_group =0;  //是否是积分商品
      foreach ($new_goods_info as $key => $value) {
        if($goods_info['group']==4 && $integral_pay==1){
          $total_price += $value['price']; //数量*单价
        }else{
          $total_price += $value['price']*$value['goodsnum']; //数量*单价
        }
        if($value['group']==4){
          $check_group = 1;
        }
      }

      if($total_price>$set_info['postage_free']){
        $ship_fee = 0;
      }else if($post['action']=='buynow'){
        $ship_fee = $new_goods_info[$gids]['postage'];
      }else{
         $ship_fee = $set_info['postage_total'];
      }

      if(!empty($check_group==1)){
        //积分商品 不用运费
        $payment = $total_price - $privilege;
      }else{
        //实际支付 = 应支付-优惠券抵扣-积分抵扣+运费
        $payment = $total_price - $privilege - $integral;
      }

      //正常购买
      if($payment<=0  &&  $data['type'] == 'normal'){
        $payment ='0.01';
      }
      //积分购买 支付价格可以为0
      if($payment<=0  &&  $data['type'] == 'coin'){
        $payment = 0;
      }

      $payment +=$ship_fee;   //加上运费 优惠券/积分抵扣 不能抵运费

      if($payment ==0 &&  $data['type'] == 'coin'){
        $data['pay_type']   =   3;
      }
      $data['ship_fee'] = $ship_fee;
      $data['total'] = $total_price;
      $data['payment'] = $payment;
//====================================总价&支付价&运费END=============================//




//==============================订单基本信息======================================//
      $data['ordernum']       = getOrderNo();                        //订单号
      $data['uid']            = $uid;                               //用户id
      $data['checkinfo']      = 1;                                  //订单状态(未支付)
      $data['remark']         = !empty($post['remark'])?$post['remark']:''; //备注
      $data['receipt']        = !empty($post['receipt_title'])?1:0; //发票...
      $data['receipt_tit']    = !empty($post['receipt_tit'])?$post['receipt_tit']:0;
      $data['receipt_type']   = !empty($post['receipt_type'])?$post['receipt_type']:0;
      $data['receipt_title']  = !empty($post['receipt_title'])?$post['receipt_title']:0;
      $data['create_time']    = $nowTime;                           //创建时间

//==============================订单基本信息END===================================//

//==============================插入订单&事务开始================================//
      $order_object = M('shop_order');
      $order_object->startTrans();
      if(!$order_object->create($data)){
        ptrace($order_object->getError());
        $this->error('创建订单失败,请稍后再试！');
      }
      $order_id = $order_object->add();
      if(empty($order_id))
        goto no;
//==============================插入订单&事务开始END================================//


//==============================插入订单详情=======================================//
      $item_data= [];
      $i = 0;
      foreach ($new_goods_info as $key => $value) {
        $item_data[$i]['goods_id'] =$value['gid'];
        $item_data[$i]['key']  =!empty($value['key'])?$value['key']:'';
        $item_data[$i]['order_id'] =$order_id;
        $item_data[$i]['buy_num'] =$value['goodsnum'];
        $item_data[$i]['integral_back'] =$value['back_integral'];
        $item_data[$i]['price'] =$value['price'];
        $item_data[$i]['sale_price'] =$value['sale_price'];
        $item_data[$i]['seckill_check'] = empty($value['seckill_check'])?0:$value['seckill_check'];
        $i++;
      }
      //插入订单详情
      $item_res = M('shop_order_item')->addAll($item_data);
      if(empty($item_res)){
        ptrace('插入订单详情失败');
        goto no;
      }
//==============================插入订单详情END=======================================//

//==============================删除购物车=======================================//
      if($post['action']=='fromcart'){
        $cart_res = M('shop_cart')->where(['id'=>['IN',$cart_ids],'uid'=>$uid])->delete();
        if(empty($cart_res)){
          ptrace('删除购物车失败');
          goto no;
        }
      }
//==============================删除购物车END=======================================//

//==============================扣除积分&增加积分使用记录===========================//
      if(!empty($data['integral'])){
        $score_data['uid']=$uid;
        $score_data['title']='购买商品扣除'.$data['integral'].'积分';
        $score_data['score']=$data['integral'];
        $score_data['create_time']=$nowTime;
        $score_data['type']=2;
        $score_data['group']=3;
        $score_data['nickname']=$this->userInfo['nickname'];
        $score_res = M('user_score')->add($score_data);
        if(empty($score_res)){
          ptrace('增加积分使用记录失败');
          goto no;
        }

        $admin_score = M('admin_user')->where(['id'=>$uid])
                        ->setDec('score',$data['integral']);
        if(empty($admin_score)){
          ptrace('扣除积分失败');
          goto no;
        }
      }
//==============================扣除积分&增加积分使用记录END==========================//

//==============================更新优惠券状态=======================================//
      if(!empty($coupon_id)){
        $coupon_map['cid'] = $coupon_id;
        $coupon_map['uid'] = $uid;
        $coupon_data['use_time'] = $nowTime;
        $coupon_data['orderid'] = $order_id;
        $coupon_data['status'] = 2;
        $coupon_res = M('user_coupons')->where($coupon_map)->save($coupon_data);
        if(empty($coupon_res)){
          ptrace('改变优惠券状态失败');
          goto no;
        }
      }
//===============================更新优惠券状态END====================================//

//===============================支付=================================================//
      $success_url = '';
      switch($data['pay_type']){
        // 微信支付:
        case 1:
            if($data['payment']>0){
                $orderinfo =array(
                    'money'=>$data['payment'],
                    'ordernum'=>$data['ordernum'],
                    'body'     => C('WEB_SITE_TITLE') . '微信支付',
                    'detail'   => C('WEB_SITE_TITLE') . '微信支付',
                    'openid'   => $this->openid,
                );
                $ret = R('Home/Weixin/union_order', [$orderinfo]);

                if(empty($ret)){
                  ptrace('微信支付失败');
                  goto no;
                }

                $order_save_res = M('shop_order')
                                 ->where(['id' =>$order_id])
                                 ->save(['wxconfig' => $ret]);
                if($order_save_res===false){
                  ptrace('保存微信支付配置失败');
                  goto no;
                }
                $success_info = '创建微信订单成功';
                $success_data = ['id' =>$order_id, 'wxconfig' => $ret,'paytype' => 1];

            }
            break;
        // 支付宝支付:
        case 2:
            $orderinfo =array(
                'money'=>$data['payment'],
                'ordernum'=>$data['ordernum'],
                'body'     => C('WEB_SITE_TITLE') . '支付宝支付',
                'detail'   => C('WEB_SITE_TITLE') . '支付宝支付',
                'openid'   => $this->openid,
            );

            $ret = R('Home/AliPay/doalipay', [$orderinfo]);
            if(empty($ret)){
              ptrace('创建支付宝支付失败');
              goto no;
            }
            $order_save_res = M('shop_order')
              ->where(['id' =>$order_id])
              ->save(['alipayurl' => $ret]);
            if($order_save_res===false){
              ptrace('保存支付宝支付配置失败');
              goto no;
             }
            $success_info = '创建支付宝订单成功';
            $success_data = ['alipayurl' => $ret ,'paytype' => 2];
             // $this->success('创建支付宝订单成功', '', ['alipayurl' => $ret ,'paytype' => 2]);
            break;
        //积分支付 不涉及到付款
        case 3:
            $order_save_res = M('shop_order')
                                ->where(['id' =>$order_id])
                                ->save(['checkinfo' => 2]);
            if($order_save_res===false){
              ptrace('更新积分支付失败');
              goto no;
            }
            $success_info = '支付成功';
            $success_data = ['paytype' => 3];
            $success_url = U('Shop/Order/index',['checkinfo'=>2]);
            break;
        //货到付款
        case 4:
            $order_save_res = M('shop_order')
                                ->where(['id' =>$order_id])
                                ->save(['checkinfo' => 2]);
            if($order_save_res===false){
              ptrace('更新货到付款失败');
              goto no;
            }
            $success_info = '下单成功';
            $success_data = ['paytype' => 4];
            $success_url = U('Shop/Order/index',['checkinfo'=>2]);
      }
//===============================支付END===============================================//

      //清除cookie
      cookie('fromcartids',null);
      cookie('default_coupons',null);
      cookie('default_pay_type',null);
      cookie('default_default_receipt_type',null);
      cookie('default_default_receipt_tit',null);
      cookie('default_default_receipt_name',null);


      $order_object->commit();
      $this->success($success_info,$success_url,$success_data);

      no:
      $order_object->rollback();
      $this->error('服务器异常，请稍后再试！');
    }
  }

  /**
   * 订单列表
   */
  public function index($checkinfo = 1)
  {
    if(IS_AJAX){
      $page = I('post.n') ? I('post.n') : 1;
      $limit = 10;
      $start = ($page - 1) * $limit;
      $checkinfo = I('post.checkinfo');
      $map = [
          // 'type'=>'normal',
          'uid'       => $this->uid,
          'checkinfo' => $checkinfo,
          'status'    => 1,
      ];
      if($checkinfo ==5){
        $map['checkinfo'] = ['egt', 5];
      }
      $list = D('Goodsorder')->where($map)->order('id DESC')->limit($start,$limit)->select();

      if($list){
        $this->assign('checkinfo', $checkinfo);
        $this->assign('order_list',$list);
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
      if (!in_array($checkinfo, [1, 2, 3, 4, 5])) {
        $checkinfo =1;
        // $this->error('错误的订单状态');
      }
      switch($checkinfo){
        case 1:
          $title ='待付款';
          break;
        case 2 :
          $title ='待发货';
          break;
        case 3:
          $title ='已发货';
          break;
        case 4:
          $title ='已完成';
          break;
        case 5:
          $title ='退款';
          break;
      }
      $this->assign('checkinfo', $checkinfo);
      $this->assign('back_url', U('Home/Member/index'));

      // 将该状态下unread设为0
      $this->readAll($this->uid, $checkinfo);
      $this->assign('meta_title', $title);
      $this->display();
    }
  }

  /**
   * 订单详情
   */
  public function detail()
  {
    $ordernum               = I('get.ordernum');
    $uid                    = $this->uid;
    $model                  = D('goodsorder');
    $order                  = $model->where(['ordernum' => $ordernum])->find();
    $order['pay_type_text'] = $model->pay_types[$order['pay_type']];
    $order['coupon_price'] = sprintf("%1\$.2f",$order['coupon']);
    if (!empty($order)) {
      $order['totalnum'] = 0;
      if ($uid == $order['uid']) {
        if ($order['checkinfo'] == 1) {
          $jsapi = R('Home/Weixin/jsapi', [['chooseWXPay']]);
          $this->assign('jsapi', $jsapi);
        }
        $shoppingcart = M('shop_order_item')->where(['order_id' => $order['id']])->select();
        // dump($order);die;

        if (!empty($shoppingcart)) {
          foreach ($shoppingcart as $value) {
            $goodsdetail = D('goods')->find($value['goods_id']);
            $goodsdetail['buynum']       = $value['buy_num'];
            $goodsdetail['price']        = $value['price'];
            $order['detail'][]           = $goodsdetail;
            $order['totalnum'] += $value['buy_num'];
          }
        } else {
          pubu("订单号{$ordernum}的订单商品详情缺失");
          $this->error('订单商品详情缺失');
        }
        $order['checkname'] = $model->checkinfos[$order['checkinfo']];

        $this->assign('detail', $order);
        $this->assign('meta_title', "订单详情");
        $this->display();
      } else {
        $this->error('不能看别人的订单');
      }
    } else {
      $this->error('订单记录查找不到');
    }
  }

  // 获取订单未阅读数
  public function getUnreadNum($uid='')
  {
    if(IS_AJAX){
      $unreads = M('shop_order')->where(['uid' => $uid, 'unread' => 1])->field('id,checkinfo')->select();
      $ret     = [];
      foreach (range(1, 3) as $key => $checkinfo) {
        $ret[$checkinfo] = count(list_search($unreads, ['checkinfo' => $checkinfo]));
      }
      $this->success('', '', ['data' => $ret]);
    }else{
      $url =U('Home/member/index');
      redirect($url);
    }
  }
  // 获取订单未读数目
  public function getOrderNum($uid='')
  {
    if(IS_AJAX){
      $order= M('shop_order')->where(['uid' => $uid,'type'=>'normal'])->field('id,checkinfo')->select();
      $ret     = [];
      foreach (range(1, 3) as $key => $checkinfo) {
        $ret[$checkinfo] = count(list_search($order, ['checkinfo' => $checkinfo]));
      }
      $this->success('', '', ['data' => $ret]);
    }else{
      $url =U('Home/member/index');
      redirect($url);
    }
  }

  // 将某类型订单标记为已读
  public function readAll($uid, $checkinfo)
  {
    return M('shop_order')->where(['uid' => $uid, 'unread' => 1, 'checkinfo' => $checkinfo])->save(['unread' => 0]);
  }


  // 去支付订单
  public function topay($ordernum)
  {
    $order = M('shop_order')->where(['ordernum' => $ordernum])->find();
    $order_id = $order['id'];

    $order_item_info = M('shop_order_item')
                        ->join('oc_shop_goods ON oc_shop_goods.id = oc_shop_order_item.goods_id','LEFT')
                        ->field('oc_shop_goods.status')
                        ->where(['oc_shop_order_item.order_id'=>$order_id])
                        ->select();

    foreach ($order_item_info as $key => $value) {
      if($value['status'] != 1){
        $this->error('该订单中某些商品已下架，请取消订单后重新下单！');
      }
    }

    if (!$order) {
      $this->error("订单记录查找不到");
    }
    if ($order['checkinfo'] > 1) {
      $this->error('订单已支付过');
    }
    switch($order['pay_type']){
      // 微信支付
      case 1:
        $ordernum = getOrderNo();
        $result = M('shop_order')->where(['id'=>$order['id']])->save(['ordernum' => $ordernum]);
        if(!$result){
          $this->error('修改订单状态失败');
        }
        $orderinfo =array(
            'money'=>$order['payment'],
            'ordernum'=>$ordernum,
            'body'     => C('WEB_SITE_TITLE') . '微信支付',
            'detail'   => C('WEB_SITE_TITLE') . '微信支付',
            'openid'   => $this->openid,
        );
        $ret = R('Home/Weixin/union_order', [$orderinfo]);

        if(empty($ret)){
          $this->error('生成微信支付失败');
        }
        $order_save_res = M('shop_order')
            ->where(['id' =>$order['id']])
            ->save(['wxconfig' => $ret]);
        if($order_save_res===false){
          $this->error('生成微信支付配置失败');
        }
        $this->success('可以支付', U('index', ['checkinfo' => 2]), ['id' => $order['id'], 'wxconfig' => $ret]);
        break;
      //支付宝支付
      case 2:
        $this->success('可以支付', U('index', ['checkinfo' => 2]), ['id' => $order['id'],'alipayurl'=>$order['alipayurl']]);
        break;
      default:
            $this->error('支付方式不存在');
    }

  }


  //修改订单状态
  public function update_order()
  {
    $uid      = $this->uid;
    $ordernum = I('request.ordernum');
    $action   = I('request.action');
    if (empty($ordernum)) {
      $this->error('订单号为空');
    } else {
      $order = M('shop_order')->where(["ordernum" => $ordernum])->find();
      if ($uid == $order['uid']) {
        $orderObj = new \Common\Util\Order();
        $result   = $orderObj->ChangeOrderStatus($order['id'], $action);
      } else {
        $this->error('只能操作自己的订单');
      }
      if (false !== $result) {
        $url = empty($result['url'])?'':$result['url'];
        $this->success('订单状态修改成功', $url);
      } else {
        $this->error('订单状态修改失败');
      }
    }
  }

  /**
   * 删除订单
   */
  public function delete_order()
  {
    if(IS_AJAX){
      $id = I('get.id','');
      $id?:exit;
      $order_object = M('shop_order');
      $order_uid = $order_object ->where(['id'=>$id])->getfield('uid');

      if(empty($order_uid)){
        $this->error('查无此单！');
      }

      if($order_uid !=$this->uid){
        $this->error('不能操作别人的订单！');
      }

      $order_res = $order_object->where(['id' => $id])->save(['status'=>0]);
      if($order_res){
        $this->success('删除成功');
      }else{
        $this->error('数据异常,请稍后再试！');
      }
    }
  }


  /**
   * 完成订单后的 商品 评价
   */
  public function review()
  {
    if(IS_AJAX){
      $msg = I('post.msg');
      $order_id = I('post.order_id');
      $uid = $this->uid;
      foreach ($msg as $key => $value) {
        $value['uid'] = $uid;
        $value['create_time'] = datetime();
        $value['update_time'] =$value['create_time'];
        $dataList[] = $value;
      }
      $order_object = M('shop_order');
      $order_object->startTrans();
      $review_res = M('shop_goods_review')->addAll($dataList);

      $order_res = $order_object->where(['id'=>$order_id])->save(['review'=>1]);

      if($review_res && $order_res){
        $order_object->commit();
        $this->success('评价成功',U('Shop/Order/index',['checkinfo'=>4]));
      }else{
        $order_object->rollback();
        $this->error('数据异常，请稍后再试！');
      }

    }else{
      $order_id = I('id','');
      $order_id?:exit;
      $order_uid = M('shop_order')->where(['id'=>$order_id])->getField('uid');
      if(empty($order_uid)){
        $this->error('查无此单！');
      }
      if($order_uid != $this->uid){
        $this->error('不能操作别人的订单！');
      }
      //判断 是否完成订单 是否 是自己的订单
      $goods_object = M('shop_goods');
      $goods_info = $goods_object
                      ->join('oc_shop_order_item ON oc_shop_order_item.goods_id = oc_shop_goods.id','LEFT')
                      ->field('oc_shop_goods.id,
                               oc_shop_goods.cover,
                               oc_shop_goods.title
                              ')
                      ->where(['oc_shop_order_item.order_id'=>$order_id])
                      ->select();
      $this->assign('order_id',$order_id);
      $this->assign('goods_info',$goods_info);
      $this->display();
    }
  }

  /**
   * 检查商品状态
   */
  public function checkGoodsStatus()
  {
    if(IS_AJAX){
      $gid = I('gid','');
      if(empty($gid)){
        $this->error('数据异常，请稍后再试！');
      }
      $res = M('shop_goods')->where(['id'=>$gid])->getField('status');
      if(empty($res) || $res == '-1'){
        $this->error('该商品已下架！');
      }else{
        $this->success('',U('Shop/Goods/detail',['id'=>$gid]));
      }

    }
  }


  // 优惠券
  public  function  coupons(){
    //1可用 2不可用
    $type =I('get.type',1);

    $gid = I('gid','')?:exit('数据异常，请稍后再试！');
    $uid =$this->uid;
    $coupons_model =new \Shop\Model\CouponModel();
    $coupons_info = $coupons_model ->getCoupons($uid,$gid);
    $can_coupons = $coupons_info['can'];
    $used_coupons = $coupons_info['not'];

    $from =str_replace('.html','/from/coupons',cookie('from'));

    $this->assign('type',$type);
    $this->assign('gid',$gid);
    $this->assign('coupons',$can_coupons);
    $this->assign('used_coupons',$used_coupons);
    $this->assign('from',$from); //跳转页面
    $this->display();

  }

  //支付方式
  public  function  pay_type(){
    $this->assign('from',cookie('from')); //跳转页面
    $this->meta_title ='支付方式';
    $this->display();
  }

  // 发票
  public  function  receipt(){
    $this->assign('from',cookie('from')); //跳转页面
    $this->meta_title ='发票填写';
    $this->display();
  }
}