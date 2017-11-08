<?php

namespace Shop\Model;
use Think\Model;

class TypeModel extends Model{
    protected $tableName = 'shop_bulletin_type';

    // 自动验证
    protected $_validate = array(
        array('title', 'require', '请填写分类名称', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '', '分类名称已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
    );
}