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
class GoodstypeModel extends Model {
    /**
     * 模块名称
     */
    public $moduleName = 'Shop';

    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     */
    protected $tableName = 'shop_goodstype';

    public $start_time;

    /**
     * 自动验证规则
     */
    protected $_validate = array(
        array('title', 'require', '分类名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('icon', 'require', '请上传类别图标', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('cover', 'require', '请上传类别封面', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),

    );

    /**
     * 自动完成规则
     */
    protected $_auto = array(
        array('create_time', 'datetime', self::MODEL_INSERT, 'function'),
        array('update_time', 'datetime', self::MODEL_BOTH, 'function'),
    );

}
