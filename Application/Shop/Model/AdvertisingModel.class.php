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
 * 广告模型 可删
 * @author jry <598821125@qq.com>
 */
class AdvertisingModel extends Model {
    /**
     * 模块名称
     * @author jry <598821125@qq.com>
     */
    public $moduleName = 'Shop';

    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'shop_advertising';

    /**
     * 自动验证规则
     */
    protected $_validate = array(
        array('title', 'require', '标题不能为空', self::EXISTS_VALIDATE , 'regex', self::MODEL_BOTH),
        array('title', '1,80', '标题长度为1-80个字符', self::EXISTS_VALIDATE, 'length'),
        array('subtitle', 'require', '副标题不能为空', self::EXISTS_VALIDATE , 'regex', self::MODEL_BOTH),
        array('subtitle', '1,80', '副标题长度为1-80个字符', self::EXISTS_VALIDATE, 'length'),
        array('cover', 'require', '请上传封面', self::EXISTS_VALIDATE , 'regex', self::MODEL_BOTH),
        // array('url', 'require', '链接不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     */
    protected $_auto = array(
        array('create_time', 'datetime', self::MODEL_INSERT, 'function'),
        array('update_time', 'datetime', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 查找后置操作
     */
    protected function _after_find(&$result, $options)
    {

    }

    /**
     * 查找后置操作
     */
    protected function _after_select(&$result, $options)
    {

    }


}
