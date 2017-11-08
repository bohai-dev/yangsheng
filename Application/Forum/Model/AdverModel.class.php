<?php
/**
 * Function
 * Created by PhpStorm.
 * User: john
 * Date: 2017/2/14
 * Time: 14:27
 */
namespace Forum\Model;
use Think\Model;
class AdverModel extends  Model{
    public $moduleName = 'Forum';
    protected $tableName = 'forum_adver';

    /**
     * 查找后置操作
     * @author jry <598821125@qq.com>
     */
    protected function _after_find(&$result, $options) {
        if ($result['cover']) {
            $result['cover_url'] = get_cover($result['cover'], 'default');
        }
    }

    /**
     * 查找后置操作
     * @author jry <598821125@qq.com>
     */
    protected function _after_select(&$result, $options) {
        foreach($result as &$record){
            $this->_after_find($record, $options);
        }
    }

    public function getList($limit = 10, $page = 1, $order = null, $map = null) {
        $con["status"] = array("eq", '1');
        if ($map) {
            $map = array_merge($con, $map);
        }
        if (!$order) {
            $order = 'sort desc, id desc';
        }
        $slider_list = $this->page($page, $limit)
            ->order($order)
            ->where($map)
            ->select();
        return $slider_list;
    }
}