<?php
namespace Shop\Controller;

use Home\Controller\BaseController;

/**
 * 积分商城控制器
 */
class IntegralController extends BaseController
{
  public function _initialize()
  {
    parent::_initialize();
    // $this->assign('gl',1);
  }

  /**
   * 积分商城首页
   */
  public function index()
  {
    if(IS_AJAX){
      $goods_object = M('shop_goods');
      $page = I('post.n') ? I('post.n') : 1;
      $limit = 10;
      $start = ($page - 1) * $limit;
      // $type = I('type','');
      // if(!empty($type)){
      //   $map['type'] = $type;
      // }
      $map['status'] =1;
      $map['group']  =4;
      $map[] = "FIND_IN_SET(4,columns)";

      $info = $goods_object
                ->where($map)
                ->field('id,cover,title,integral_price,sale_integral,sale_price,original_price,sales_volume')
                ->order('id DESC')
                ->limit($start,$limit)
                ->select();

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
      $uid = $this->uid;

      //签到 登录
      cookie('before_reg', $this->current_url, $this->cookie_expire);
      $user_integral = 0;
      if(!empty($uid)){
        $user_integral = M('admin_user')->where(['id'=>$uid])->getField('score');
      }

      //积分分类
      $type_info    = M('shop_goodstype')
                        ->field('id,title')
                        ->where(['group'=>4,'status'=>1,'check'=>0])
                        ->limit(4)
                        ->order('id DESC')
                        ->select();
      //栏目分类
      $columns_info = M('shop_columns')
                        ->field('id,title,subtitle,cover,url')
                        ->where(['group'=>2,'status'=>1])
                        ->order('id ASC')
                        ->select();


      $this->assign('user_integral',$user_integral);
      $this->assign('columns_info',$columns_info);
      $this->assign('type_info',$type_info);
      $this->display();
    }
  }

  /**
   * 签到
   */
  public function check_in()
  {
    if(IS_AJAX){
      $uid = $this->uid;
      if(empty($uid)){
        $this->error('',U('Home/Member/login'));
      }

      $score_object = M('user_score');
      //最近的签到数据
      $nowTime = datetime();
      $check_info = $score_object
                      ->where(['uid'=>$uid,'group'=>1])
                      ->order('create_time DESC')
                      ->find();
      if(!empty($check_info)){
        $check_time = $check_info['create_time'];
        $today = strtotime(date('Y-m-d',time()+ 86400));
        $last_time = strtotime($check_time);
        $result_time = $today - $last_time;
        if ($result_time <= 86400) {
            $this->error('今天已签到');
        }
      }

      $integral_get = M('shop_set')->where(['id'=>1])->getField('integral_get');
      if(empty($integral_get)){
        $this->error('数据异常，请稍后再试！');
      }
      $data['uid'] = $uid;
      $data['nickname'] = $this->userInfo['nickname'];
      $data['title'] = '签到获得'.$integral_get.'积分';
      $data['score'] = $integral_get;
      $data['create_time'] = $nowTime;
      $data['type'] = 1;
      $data['group'] = 1;

      $score_object->startTrans();
      $score_res = $score_object->data($data)->add();

      $user_res = M('admin_user')->where(['id'=>$uid])->setInc('score',$integral_get);

      if($score_res&&$user_res){
        $score_object->commit();
        $result['status'] =1;
        $result['info'] ='签到成功';
        $result['integral_get'] =$integral_get;
        $this->ajaxReturn($result);
      }
      $score_object->rollback();
      $this->error('服务器异常,请稍后再试！');
    }
  }

  /**
   *积分产品详情
   */
  public function detail()
  {
    $id = I('id','');
    cookie('before_reg', $this->current_url, $this->cookie_expire);

    $uid = $this->uid;
    $goods_object = M('shop_goods');
    $goods_info = $goods_object->where(['id'=>$id,'status'=>1])->find();
    if(empty($goods_info)){
      $this->error('商品已下架！');
    }

    //优惠券信息
    $coupons_str_info = '';
    if($goods_info['coupons']){
      $coupons_info = M('shop_coupon')
                      ->where(['id'=>['IN',$goods_info['coupons']]])
                      ->select();
      foreach ($coupons_info as $key => $value) {
        if($key==0){
          $coupons_str_info = $value['title'].'(￥'.$value['price'].')';
        }else{
          $coupons_str_info .= '　'.$value['title'].'(￥'.$value['price'].')';
        }
      }
    }
    $this->assign('coupons_str_info',$coupons_str_info);


    $spec_items = D('goods')->get_spec($id);
    $this->assign('spec_items', $spec_items);
    // 规格 对应 价格 库存表
    $spec_goods_price  = M('spec_goods_price')->where(['goods_id'=>$id])->getField("key,shop_price,store_count");
    $this->assign('spec_goods_price', $spec_goods_price);//规格参数


    //浏览
    $nowTime = datetime(); // 现在时间 create_time update_time
    $openid =$this->openid;
    $user_id= M('user')->where(['openid'=>$openid])->getfield('id');
    //记录用户浏览 user_id
    if(!empty($user_id)){
      $browse_object = M('shop_goods_browse');
      $browse_data['uid'] = $user_id;
      $browse_data['gid'] = $id;
      $browse_info = $browse_object->where([$browse_data])->find();
      if(empty($browse_info)){
        $browse_data['create_time'] = $nowTime;
        $browse_data['update_time'] = $nowTime;
        $browse_date['state'] =1;
        M('shop_goods_browse')->add($browse_data);
      }else{
        $browse_save['num'] = $browse_info['num']+1;
        $browse_save['update_time'] = $nowTime;
        $browse_object
          ->where(['id'=>$browse_info['id']])
          ->save($browse_save);
      }
    }


    //轮播
    $banner = explode(',', $goods_info['images']);

    //推荐商品信息 同分类下 除了本页面商品 销售最高的两件商品
    $recommend_info = $goods_object
                        ->where(['status'=>1,'type'=>$goods_info['type'],'id'=>['NEQ',$id]])
                        ->order('sales_volume DESC')
                        ->limit(2)
                        ->select();
    //收藏
     $on_info = '';
    if(!empty($uid)){
      $collect_info = M('shop_goods_collect')->where(['uid'=>$uid,'gid'=>$id,'status'=>1])->find();
      if(!empty($collect_info)){
        $on_info = 1;
      }
    }


    //商品评论
    $review_info = M('shop_goods_review')
                    ->join('oc_user ON oc_user.admin_uid = oc_shop_goods_review.uid','LEFT')
                    ->field('oc_user.nickname,
                             oc_user.headimgurl,
                             oc_shop_goods_review.rating,
                             oc_shop_goods_review.content,
                             oc_shop_goods_review.create_time
                            ')
                    ->where(['oc_shop_goods_review.gid'=>$id,'oc_shop_goods_review.status'=>1])
                    ->order('oc_shop_goods_review.id DESC')
                    ->select();

    $review_num = count($review_info);
    $review_info = array_slice($review_info, 0, 2);

    //分享
    $jsapi = R('Home/Weixin/jsapi', [['onMenuShareTimeline',
                                      'onMenuShareAppMessage',
                                      'onMenuShareQQ',
                                      'onMenuShareWeibo',
                                      'onMenuShareQZone']]);
    $wechatShare["title"] = $goods_info['title'];
    $wechatShare["desc"] = $goods_info['subtitle'];
    $wechatShare["link"] =$this->current_url;
    $wechatShare["imgUrl"] = XILUDomain().getpics($goods_info['cover'],'cover');
    $wechatShare["type"] = 'link';


    $this->assign('review_num',$review_num);
    $this->assign('review_info',$review_info); //评论信息
    $this->assign('jsapi', $jsapi);
    $this->assign('wechatShare',$wechatShare);
    $this->assign('on_info',$on_info);
    $this->assign('recommend_info',$recommend_info);
    $this->assign('banner',$banner);
    $this->assign('goods_info',$goods_info);
    $this->display();
  }

  /**
   * 积分结算
   */
  public function confirm_pay()
  {
    $uid =  $this->check_login();
    $ids = I('ids','')?:exit('数据异常，请稍后再试！'); //商品ID
    $num = I('goodsnum');                            //积分商品数量唯一
    // $num = 1;                                           //积分商品数量唯一
    $action ='buynow';                                  //积分商品立即购买
    $spec_key =I('key','');                             //商品规格
    //表单提交 需要重新拼URL
    cookie('from',U('confirm_pay', ['action' => $action, 'ids' => $ids,'goodsnum' =>$num, 'key' => $spec_key]),36000);


//=======================支付方式============================//
    $pay_type =1;//默认微信支付
    if(cookie('default_pay_type')){
      $pay_type =cookie('default_pay_type');
    }
    $orderModel = new \Shop\Model\GoodsorderModel();
    $pay_name =$orderModel->pay_types[$pay_type];
    $this->assign('pay_type',$pay_type);
    $this->assign('pay_name',$pay_name);
//=======================支付方式END=========================//

// ========================优惠券========================//
    //检查是否有优惠券
    $coupons_model =new \Shop\Model\CouponModel();
    $coupons_info = $coupons_model ->getCoupons($uid,$ids);

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
    // dump($coupons_info);die;
    $this->assign('check_coupons',$check_coupons);
    $this->assign('coupons_info',$coupon_info);

//=========================优惠券END=====================//



//=======================商品信息============================//
    $goods_info = M('shop_goods')
                  ->field('id,title,cover,
                          original_price,sale_price,
                          integral_price,sale_integral,postage,
                          back_integral')
                  ->where(['id'=>$ids])->find();
    $goods_info['goodsnum']=$num;


    //检查积分商品的购买价格(sale_price 是否有规格价)
    $spec_str = '';
    if(!empty($spec_key)){
      $spec_price =M('spec_goods_price')
                      ->where(['goods_id'=>$ids,'key'=>$spec_key])
                      ->getField('shop_price');
      $goods_info['sale_price'] = $spec_price?:$goods_info['sale_price'];
      $item_ids = explode('_',$spec_key);
      $spec_info = M('spec_item')
                    ->join('oc_spec ON oc_spec_item.spec_id = oc_spec.id','LEFT')
                    ->field('oc_spec.name,oc_spec_item.item')
                    ->where(['oc_spec_item.id'=>['in',$item_ids]])
                    ->select();

      foreach ($spec_info as $key => $value) {
        $spec_str .= $value['name'].'：'.$value['item'].'　';
      }
    }
    //商品价格 减去 优惠价
    $goods_info['pay_money'] = $goods_info['sale_price']*$num - $coupon_price;

    if($goods_info['pay_money']<=0){
       $goods_info['pay_money']=0.01;
    }
    $goods_info['pay_money'] += $goods_info['postage'];  //加上运费


    $this->assign('spec_str',$spec_str);//规格信息
    $this->assign('spec_key',$spec_key);//规格key值15_16
    $this->assign('goods_info',$goods_info);//商品信息
//=======================商品信息END===================//



//========================地址=========================//
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

//========================地址END======================//




//=========================积分购========================//
    //判断用户是否积分买过该商品  限购
    // $order_info = M('shop_order')
    //               ->join('oc_shop_order_item
    //                       ON oc_shop_order_item.order_id = oc_shop_order.id','LEFT')
    //               ->where([
    //                         'oc_shop_order_item.goods_id'=>$ids,
    //                         'oc_shop_order.uid'=>$uid,
    //                         'oc_shop_order.type'=>'coin'
    //                       ])
    //               ->field('oc_shop_order.id')
    //               ->find();
    $check_buy = '';
    // if(!empty($order_info)){
    //   $check_buy = 1;
    // }
    $this->assign('check_buy',$check_buy);


    $set_info = M('shop_set')->field('integral_rate,integral_min')->find();

    //积分比例
    $integral_rate = $set_info['integral_rate'];
    $integral_rate = 1/$integral_rate;

    //积分限制
    $integral_min = $set_info['integral_min'];

    //积分购买时 用户积分小于购买积分则需RMB补足
    $user_score = M('admin_user')
                    ->where(['id'=>$uid])
                    ->getField('score');     //用户拥有的积分
    $make_up = 0;
    $use_integral   = $goods_info['sale_integral']*$num;
    if($user_score < $use_integral){
      $use_integral  = $user_score;
      $make_up = ($goods_info['sale_integral']*$num - $user_score)*$integral_rate;
      $make_up = round($make_up,2);
    }

    //积分购买需付款
    $integral_pay_price = $goods_info['integral_price']*$num + $make_up - $coupon_price;

    if($integral_pay_price<0){
      $integral_pay_price =0;
    }
    $integral_pay_price += $goods_info['postage'];   //加上运费

    $this->assign('integral_min',$integral_min);
    $this->assign('user_score',$user_score); //用户积分
    $this->assign('use_integral',$use_integral);  //使用积分
    $this->assign('make_up',$make_up); //RMB 补差
    $this->assign('integral_pay_price',$integral_pay_price); //积分购买 实付款

//====================积分购END======================//


    $this->display();
  }

  public function  pay_type()
  {
    $this->assign('from',cookie('from')); //跳转页面
    $this->meta_title ='支付方式';
    $this->display();
  }
}