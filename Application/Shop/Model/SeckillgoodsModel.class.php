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
 * 秒杀商品模型
 */
class SeckillgoodsModel extends Model {
    /**
     * 模块名称
     */
    public $moduleName = 'Shop';

    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     */
    protected $tableName = 'shop_seckill_goods';

    public $start_time;

    /**
     * 自动验证规则
     */
    protected $_validate = array(
            array('goods_id', 'require', '请选择商品', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
            array('seckill_price', 'require', '秒杀价不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
            array('seckill_price', 'checkPrice', '秒杀价格式不正确', self::MUST_VALIDATE, 'function',self::MODEL_BOTH),
            array('stock', 'require', '商品库存不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     */
    protected $_auto = array(
        array('create_time', 'datetime', self::MODEL_INSERT, 'function'),
        array('update_time', 'datetime', self::MODEL_BOTH, 'function'),
    );

    /*
     * select 商品类别下拉 数据 不包括积分 美妆 开店必备商品
     */
    public function getType()
    {
        // $map['pid'] = ['neq',1];
        // $map['id'] = ['neq',1];
        $map['group'] = 1;
        $list = M('shop_goodstype')->where($map)->select();
        return list_as_tree($list,null,'id','title',true);
    }

}
