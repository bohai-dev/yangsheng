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
 * 论坛模型
 * @author jry <598821125@qq.com>
 */
class ForumModel extends Model {
    protected  $tableName ='forum_posts';
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

    // 获取分类
    public  function  get_forum_type($format='normal'){
        $type =M('forum_type')->where(['status'=>1])->order('sort asc')->select();
        switch($format){
            case 'normal':
                return $type;
                break;
            case 'json':

                break;
        }
    }

    // 获取关注数
    public  function  get_fans($uid){
        $num=[];
        $model =M('forum_attention');
        $attention=$model->where(['uid'=>$uid,'status'=>1])->count();
        $num['attention'] =$attention;
        $fans =$model->where(['attention_userid'=>$uid,'status'=>1])->count();
        $num['fans'] =$fans;
        return $num;
    }
}
?>