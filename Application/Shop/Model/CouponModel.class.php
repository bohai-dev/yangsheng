<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Shop\Model;
use Think\Model;
/**
 * 优惠券模型
 */
class CouponModel extends Model {
    /**
     * 模块名称
     */
    public $moduleName = 'Shop';

    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     */
    protected $tableName = 'shop_coupon';

    /**
     * 自动验证规则
     */
    protected $_validate = array(
        array('title', 'require', '名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('price', 'require', '优惠金额不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('price', 'checkPrice', '优惠金额格式不正确', self::EXISTS_VALIDATE, 'callback',self::MODEL_BOTH),
        array('start_time', 'getStart', '', self::EXISTS_VALIDATE, 'callback',self::MODEL_BOTH),
        array('end_time', 'checkTime', '结束时间需大于开始时间', self::EXISTS_VALIDATE, 'callback',self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     */
    protected $_auto = array(
        array('create_time', 'datetime', self::MODEL_INSERT, 'function'),
        array('update_time', 'datetime', self::MODEL_BOTH, 'function'),
    );

    /**
     * 验证价格
     */
    public function checkPrice($price)
    {
        if(preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $price))
            return true;
        return false;
    }

    /*
     *验证时间
     */
    public function checkTime($end_time)
    {
        if(strtotime($end_time)>$this->start_time)
            return true;
        return false;
    }
    /*
     *获取起始时间
     */
    public function getStart($start_time)
    {
        $this->start_time = strtotime($start_time);
        return true;
    }

    // 获取可用优惠券
    public  function  get_can_coupons($uid,$field='*',$map=null){
        $con =[
                'u.status'=>1,
                'u.uid'=>$uid,
                'c.status'=>1,
                'c.start_time'=>['elt',datetime()],
                'c.end_time'=>['egt',datetime()]
                ];
        if(empty($map)){
            $map =$con;
        }else{
            $map = array_merge($map,$con);
        }
        return  M('user_coupons as u')
                 ->field($field)
                 ->join('left join oc_shop_coupon as c  on u.cid =c.id')
                 ->where($map)
                 ->select();
    }

    // 获取不可用优惠券
    public  function  get_used_coupons($uid,$field='*')
    {
        return  M('user_coupons as u')
            ->field($field)
            ->join('left join oc_shop_coupon as c  on u.cid =c.id')
            ->where(['u.status'=>1,'u.uid'=>$uid,'c.status'=>1,'c.start_time'=>['gt',datetime()],'c.end_time'=>['lt',datetime()]])
            ->select();
    }


    /**
     * [getCoupons description]
     * @Author   yi
     * @DateTime 2017-03-21T10:08:42+0800
     * @return   [可使用优惠券,不可使用优惠券]                   [description]
     */
    public function getCoupons($uid,$gid)
    {
        $nowTime = datetime();
        $can_coupons_id_arr='';
        $not_coupons_id_arr='';

        //用户所有可用的优惠券
        $user_coupons = $this->get_can_coupons($uid,'u.cid');
        //商品 允许使用的优惠券
        $goods_coupons_info = M('shop_goods')->field('coupons')->where(['id'=>['IN',$gid]])->select();


        foreach ($goods_coupons_info as $key => $value) {
            if($key==0){
                $goods_coupons_id_str = $value['coupons'];
            }else{
                $goods_coupons_id_str .= ','.$value['coupons'];
            }
        }

        //商品可以使用的优惠券   切成数值并去除重复值
        $goods_coupons_id_arr = array_unique(explode(',', $goods_coupons_id_str));
        //判断 用户的优惠券 是否能使用
        $user_coupons_id_arr = array_column($user_coupons,'cid');
        //数组集合
        $can_coupons_id_arr = array_intersect($goods_coupons_id_arr,$user_coupons_id_arr);
        //数组差集
        $not_coupons_id_arr =array_diff($user_coupons_id_arr,$can_coupons_id_arr);
        //获取优惠券详细信息
        $can_coupons_info  = $this->getCouponsInfo($uid,$can_coupons_id_arr);
        $not_coupons_info  = $this->getCouponsInfo($uid,$not_coupons_id_arr);
        return ['can'=>$can_coupons_info,'not'=>$not_coupons_info];
    }


    /**
     * [getcouponsInfo 获取优惠券详细信息]
     * @Author   yi
     * @DateTime 2017-03-21T11:03:19+0800
     * @param    [type]                   $id [description]
     * @return   [type]                       [description]
     */
    public function getCouponsInfo($uid,$id)
    {
        $result = '';
        if(!empty($id)){
            $result = M('shop_coupon')
                        ->field('id,title,start_time,end_time,price')
                        ->where(['id'=>['IN',$id]])->select();
        }
        return $result;
    }
}
