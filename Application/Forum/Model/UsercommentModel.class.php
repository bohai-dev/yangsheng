<?php
/**
 * Function
 * Created by PhpStorm.
 * User: john
 * Date: 2017/2/5
 * Time: 13:38
 */
namespace Forum\Model;
use Think\Model;
/**
 * 评论模型
 * @author jry <598821125@qq.com>
 */
class UsercommentModel extends Model {
    protected  $tableName ='user_comment';
    protected function _after_select(&$result, $options)
    {
        foreach ($result as &$record) {
            $this->_after_find($record, $options);
        }
    }

    protected function _after_find(&$result, $options)
    {
        $result['username'] =get_user_info($result['uid'],'admin_user','nickname');
        $result['type_name'] =get_type_name($result['type']);

    }

   
}
?>