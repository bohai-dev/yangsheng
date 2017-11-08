<?php

namespace Shop\Model;
use Think\Model;

class SpecItemModel extends Model{
	// 数据库表名
    protected $tableName = 'spec_item';

	// 自动验证规则
    protected $_validate = array(
        array('item', 'require', '规格值不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH)
    );

    // 自动完成规则
    protected $_auto = array(
    );

    // 查找后置操作
    protected function _after_find(&$result, $options)
    {
    }

     // 查找后置操作
    protected function _after_select(&$result, $options)
    {
        if($result){
            
        }
    }
}