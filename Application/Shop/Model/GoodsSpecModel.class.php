<?php

namespace Shop\Model;
use Think\Model;

class GoodsSpecModel extends Model{
	// 数据库表名
    protected $tableName = 'spec';

	// 自动验证规则
    protected $_validate = array(
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
            
            $type_names = [];
            if($type_ids = array_column($result, 'type_id')){
                $type_names = M('shop_goodstype')->where(['id'=>['in', $type_ids]])->getField('id,title');
            }
            foreach ($result as $key => &$row) {
                if(isset($row['type_id'])){
                    $row['type_name'] = isset($type_names[$row['type_id']]) ? $type_names[$row['type_id']] : '';
                }
            }
        }
    }

    public function getSpec($map)
    {
        $spec = $this->where($map)->field('id,name')->select();
        $model = D('SpecItem');
        foreach ($spec as $key => &$row) {
            $row['items'] = $model->where(['spec_id'=>$row['id']])->field('id,item')->select();
        }
        return $spec;
    }
}