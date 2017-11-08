<?php
/**
 * Function
 * Created by PhpStorm.
 * User: john
 * Date: 2017/2/5
 * Time: 13:35
 */
namespace Forum\Admin;

use Admin\Controller\AdminController;
use Common\Util\Think\Page;
use Common\Builder\FormBuilder;
use Common\Builder\ListBuilder;
/**
 * 论坛后台控制器
 * @author jry <598821125@qq.com>
 */
class ForumAdmin extends AdminController{
    public  function  index(){
        $map = [];
        $type =M('forum_type')->where(['status'=>1])->getField('id,title');
        if ($type_id = I('type_id', '')) {
            $map['_string'] ='FIND_IN_SET('.$type_id.',type)';
        }
        list($data_list, $page, $model_object) = $this->lists('Forum', $map, 'top desc,status asc,id ASC');
        $arrb = array(
            'title' =>'评论管理',
            'class'=>'label label-success-outline label-pill',
            'href'=>U('user_comment_list',['id' => '__data_id__'])
            );
        $builder = new ListBuilder();
        $builder->setMetaTitle('帖子列表') // 设置页面标题
        ->addSearchItem('type_id', 'select', '', '', ['' => '分类'] + $type)
        ->addTableColumn('id', 'id')
            ->addTableColumn('username','用户名')
            ->addTableColumn('title', '标题')
            ->addTableColumn('type_name','分类')
            ->addTableColumn('like_num','点赞数')
            ->addTableColumn('reward_num','打赏数')
            ->addTableColumn('status','状态','callback','callback_status')
            ->addTableColumn('right_button', '操作管理', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit') // 添加编辑按钮
            // ->addRightButton("check") // 添加禁用/启用按钮
            ->addRightButton("delete",['href'=>U('del_forum',['id' => '__data_id__'])]) // 添加禁用/启用按钮
            ->addRightButton('top') // 置顶
            ->addRightButton('classic') // 精华
            ->addRightButton("self",array('title'=>'查看打赏记录','class'=>'label label-primary-outline label-pill','href'=>U('reward_record',['id' => '__data_id__']))) // 添加禁用/启用按钮
            ->addRightButton("self", $arrb)
            ->display();
    }

    public  function  edit($id){
            if (IS_POST) {
                $slider_object = D('Forum');
                $data          = $slider_object->create();
                if ($data) {
                    $id = $slider_object->save();
                    if ($id !== false) {
                        checked_sendcustom($data['id'],$data['status'],'forum_posts');//发送客服消息。
                        $this->success('更新成功',U('index'));
                    } else {
                        $this->error('更新失败');
                    }
                } else {
                    $this->error($slider_object->getError());
                }
            } else {
                // 使用FormBuilder快速建立表单页面。
                $info = D('Forum')->find($id);
                $type =M('forum_type')->where(['status'=>1])->getField('id,title');
                $builder = new \Common\Builder\FormBuilder();
                $builder->setMetaTitle('编辑') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                    ->addFormItem('id', 'hidden', 'ID', 'ID')
                    ->addFormItem('username','static','用户名','')
                    ->addFormItem('type', 'checkbox', '类型', '',$type)
                    ->addFormItem('title', 'text', '标题', '标题')
                    ->addFormItem('content', 'textarea', '内容', '内容')
                    ->addFormItem('pics', 'pictures', '图片', '图片')
                    ->addFormItem('status','radio','审核状态','',[-1=>'已拒绝',0=>'未审核',1=>'审核通过'])
                    ->setFormData($info)
                    ->display();
            }
    }

    public  function  reward_record($id){
        $map['posts_id'] =$id;
        list($data_list, $page, $model_object) = $this->lists('forum_record',$map, 'id ASC');
        $builder = new ListBuilder();
        $builder->setMetaTitle('打赏记录') // 设置页面标题
            ->addTableColumn('id', 'id')
            ->addTableColumn('uid','用户名','callback','get_username')
            ->addTableColumn('score', '打赏积分')
            ->addTableColumn('time','打赏时间')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->display();
    }
    
    public function del_forum($id =0){
        M()->startTrans();
        $tran = true;
        $return = M('forum_posts')->delete($id);
        $tran = ($tran===false) ||($return === false) ?false:true;
        $return = M('user_comment')->where(['order_id'=>$id])->delete();
        $tran = ($tran===false) ||($return === false) ?false:true;
        if($tran){
             M()->commit();
             $this->success('删除帖子成功！');
        }else{
             M()->rollback();
            $this->error('删除失败！');
        }

    }
//评论列表
    public function user_comment_list($id = 0){
        $map = array(
            'order_id' => $id
            );
        list($data_list, $page, $model_object) = $this->lists('Usercomment',$map, 'id DESC');
        // dump($data_list);
        // die;
        $builder = new ListBuilder();
        $builder->setMetaTitle('评论列表')
            ->addTableColumn('id','id')
            ->addTableColumn('username','评论人')
            ->addTableColumn('comment_time','日期')
            ->addTableColumn('comment','内容')
            ->addTableColumn('right_button', '操作管理', 'btn')
            ->addRightButton("self",array('title'=>'查看评论','class'=>'label label-primary-outline label-pill','href'=>U('comment_list',['id' => '__data_id__']))) // 添加禁用/启用按钮
            ->addRightButton("delete",['href'=>U('del_com',['id' => '__data_id__'])]) // 添加禁用/启用按钮
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->display();
       
    }
    public  function comment_list($id){
        $map = array(
            'pid' => $id
        );
        list($data_list, $page, $model_object) = $this->lists('Usercomment',$map, 'id DESC');
        // dump($data_list);
        // die;
        $builder = new ListBuilder();
        $builder->setMetaTitle('评论列表')
            ->addTableColumn('id','id')
            ->addTableColumn('username','评论人')
            ->addTableColumn('comment_time','日期')
            ->addTableColumn('comment','内容')
            ->addTableColumn('right_button', '操作管理', 'btn')
            ->addRightButton("delete",['href'=>U('del_com',['id' => '__data_id__'])]) // 添加禁用/启用按钮
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->display();
    }
    public function del_com($id =0){
         $return = M('user_comment')->delete($id);
          if($return){
             $this->success('删除评论成功！');
        }else{
            $this->error('删除失败！');
        }
    }
}
?>