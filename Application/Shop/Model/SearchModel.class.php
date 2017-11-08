<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace shop\Model;
use Think\Model;
/**
 * 今日头条模型
 */
class SearchModel extends Model {
    /**
     * 数据库表名
     */
    protected $tableName = 'shop_search';

    /**
     * 自动验证规则
     */
    protected $_validate = array(
        array('word', 'require', '关键字不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     */
    protected $_auto = array(
        array('create_time', 'datetime', self::MODEL_INSERT, 'function'),
        array('update_time', 'datetime', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
    );
}
