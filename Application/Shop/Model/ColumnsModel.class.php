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
class ColumnsModel extends Model {
    /**
     * 模块名称
     */
    public $moduleName = 'Shop';

    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     */
    protected $tableName = 'shop_columns';

    /**
     * 自动验证规则
     */
    protected $_validate = array(
        array('title', 'require', '标题不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('subtitle', 'require', '副标题不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('url', 'require', '链接不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('icon', 'require', '请上传图标', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('cover', 'require', '请上传封面', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('sort', 'require', '排序不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('sort', 'is_numeric', '排序格式不正确', self::EXISTS_VALIDATE, 'function', self::MODEL_BOTH),
        array('pagepic', 'require', '请上传页中图片', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),

    );

    /**
     * 自动完成规则
     */
    protected $_auto = array(
        array('create_time', 'datetime', self::MODEL_INSERT, 'function'),
        array('update_time', 'datetime', self::MODEL_BOTH, 'function'),
    );


}
