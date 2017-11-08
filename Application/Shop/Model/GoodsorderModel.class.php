<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
/**
 * 商品订单模型
 * @author jry <598821125@qq.com>
 */
namespace Shop\Model;

use Think\Model;

class GoodsorderModel extends Model
{
    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'shop_order';
    public $checkinfos   = [
        '-1' => '已取消',
        '1'  => '未支付',
        '2'  => '待发货',
        '3'  => '待收货',
        '4'  => '已完成',
        '5' =>  '退款审核中',
        '6'  => '已退货',
        '7'=>'拒绝退款'
    ];

    public $checkinfos_conference = [
        '-1' => '已取消',
        '1'  => '未支付',
        '2'  => '待发货',
        '3'  => '待收货',
        '4'  => '已完成',
        '5' =>  '退款审核中',
        '6'  => '已退款',
        '7'=>'拒绝退款'
    ];
    public  $color_status =[
        '-1' => 'black',
        '1'  => 'black',
        '2'  => 'red',
        '3'  => 'black',
        '4'  => 'green',
        '5' =>  'black',
        '6'  => 'black',
        '7'=>'black'
    ];
    public $pay_types = [
        '4'=>  '货到付款',
        '2' => '支付宝支付',
        '1' => '微信支付',
    ];

    protected function _after_select(&$result, $options)
    {
        $user_ids = array_column($result, 'uid');
        if ($user_ids) {
            $user_ids = array_unique($user_ids);
        }
        $username  = M('admin_user')->where(['id' => ['in', $user_ids]])->getField('id,nickname');
        $user_map  = ['uid' => ['in', $user_ids]];
        foreach ($result as &$record) {

            $record['pay_type_name'] = !empty($record['pay_type']) && isset($this->pay_types[$record['pay_type']]) ? $this->pay_types[$record['pay_type']] : '';
            $record['price_text']    = "总价：{$record['total']}元/支付价：{$record['payment']}元";
            if ($record['privilege'] > 0) {
                $record['price_text'] .= "，优惠：{$record['privilege']} 元";
            }
            $goods = M('shop_order_item')->field('content', true)->where(['order_id' => $record['id']])->select();
            if(!empty($goods)){
                $goodlist         = M('shop_goods')->where(['id' => ['in', array_column($goods, 'goods_id')]])->select();
                $record['buynum'] = 0;
                foreach ($goods as $good_item) {
                    foreach ($goodlist as $key => $value) {
                        if ($good_item['goods_id'] == $value['id']) {
                            $record['buynum'] += $good_item['buy_num'];
                            $good_item['title'] = $value['title'];
                            $good_item['cover']   = $value['cover'];
                            $good_item['original_price'] =$value['original_price'];
                            $good_item['sale_price'] =$value['sale_price'];
                            $record['goods'][]  = $good_item;
                        }
                    }
                }
                $record['nickname']=$username[$record['uid']];
                $record['checkname'] = $this->checkinfos[$record['checkinfo']] ?: $record['checkinfo'];
            }
        }
    }
    public  function  get_color($checkinfo){
        $str ='<span style="color:'.$this->color_status[$checkinfo].'">'.$this->checkinfos[$checkinfo].'<span>';
        return $str;
    }
    protected function _after_find(&$result, $options)
    {
        $result['pay_type_name'] = !empty($result['pay_type']) && isset($this->pay_types[$result['pay_type']]) ? $this->pay_types[$result['pay_type']] : '';
        $result['price_text']    = "总价：{$result['total']}元/支付价：{$result['payment']}元";
        if ($result['privilege'] > 0) {
            $result['price_text'] .= "，优惠：{$result['privilege']} 元";
        }
    }
}
