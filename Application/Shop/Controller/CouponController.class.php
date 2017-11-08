<?php
namespace Shop\Controller;

use Home\Controller\BaseController;

/**
 * 优惠券控制器
 */
class CouponController extends BaseController
{
  public function _initialize()
  {
    parent::_initialize();
    $this->check_login();
  }

  /**
   * 领券中心
   */
  public function index()
  {
    if(IS_AJAX){
      $coupon_object =  M('user_coupons');
      $cid = I('cid');
      $uid = cookie('uid');
      $check = $coupon_object->where(['uid'=>$uid,'cid'=>$cid])->find();
      if(!empty($check)){
        $this->error('您已经领取过！');
      }

      $data['cid']  = $cid;
      $data['uid'] = $uid;
      $data['create_time'] = datetime();
      $res =$coupon_object->data($data)->add();
      if($res){
        $this->success('领取成功');
      }else{
        $this->error('服务器异常，请稍后再试！');
      }
    }else{

      $coupon_object = M('shop_coupon');
      $nowTime = datetime();
      $coupon_info = $coupon_object->where(['status'=>1,'end_time'=>['gt',$nowTime],'start_time'=>['lt',$nowTime]])->select();
      $this->assign('coupon_info',$coupon_info);
      $this->display();
    }
  }

}