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
class SetModel extends Model {
    /**
     * 模块名称
     */
    public $moduleName = 'Shop';

    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     */
    protected $tableName = 'shop_set';


    /**
     * 自动验证规则
     */
    protected $_validate = array(
            array('integral_rate', 'require', '积分比例不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
            array('integral_rate', 'is_numeric', '积分比例格式不正确', self::MUST_VALIDATE, 'function', self::MODEL_BOTH),
            array('integral_get', 'require', '签到积分不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
            array('integral_get', 'is_numeric', '签到积分格式不正确', self::MUST_VALIDATE, 'function', self::MODEL_BOTH),
            array('register_gift', 'is_numeric', '注册积分格式不正确', self::MUST_VALIDATE, 'function', self::MODEL_BOTH),
            array('integral_min', 'is_numeric', '积分限制格式不正确', self::MUST_VALIDATE, 'function', self::MODEL_BOTH),
            array('shop_mobile', 'require', '首页客服不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
            array('postage_total', 'require', '总运费不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
            array('postage_total', 'checkPrice', '总运费格式不正确', self::MUST_VALIDATE, 'function',self::MODEL_BOTH),
            array('postage_free', 'require', '包邮条件不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
            array('postage_free', 'checkPrice', '包邮条件格式不正确', self::MUST_VALIDATE, 'function',self::MODEL_BOTH),
            // array('postage_description', 'require', '运费说明不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH)
    );

    /**
     * 自动完成规则
     */
    protected $_auto = array(
        array('update_time', 'datetime', self::MODEL_BOTH, 'function'),
    );
}
