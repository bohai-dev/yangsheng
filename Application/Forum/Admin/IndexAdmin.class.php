<?php
namespace Forum\Admin;

use Admin\Controller\AdminController;
use Common\Util\Think\Page;
use Common\Builder\FormBuilder;
use Common\Builder\ListBuilder;

/**
 * 默认控制器
 */
class IndexAdmin extends AdminController
{
    /**
     * 默认方法
     */
    public function index()
    {
        $map=[];
        $keyword              = I('keyword', '', 'string');
        $condition            = array('like', '%' . $keyword . '%');
        $map['id|title'] = array($condition, $condition, $condition, '_multi' => true);
        list($data_list, $page, $model_object) = $this->lists('Type',$map, 'id ASC');
		$builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle("列表") // 设置页面标题
            ->addTopButton("addnew") // 添加新增按钮
            ->addTopButton("resume",['model' => 'Type']) // 添加启用按钮
            ->addTopButton("forbid",['model' => 'Type']) // 添加禁用按钮
            ->setSearch("请输入ID/标题", U("index"))
            ->addTableColumn("id", "ID")
            ->addTableColumn("title", "标题")
            ->addTableColumn("cover", "图标",'picture')
            ->addTableColumn("right_button", "操作", "btn")
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton("edit") // 添加编辑按钮
            ->addRightButton("forbid",['model' => 'Type']) // 添加禁用/启用按钮
            ->addRightButton("delete",['model' => 'Type']) // 添加删除按钮
            ->display();
    }

    public  function  add(){
        $model_object =D('Type');
        if(IS_POST){
            $post = I('post.');
            if (!$data = $model_object->create($post)) {
                $this->error($model_object->getError());
            } else {
                if ($model_object->add($data)) {
                    $this->success('添加成功', U('index'));
                } else {
                    trace($model_object->getError());
                    $this->error('添加失败');
                }
            }
        }else{
            $builder = new FormBuilder();
            $builder->setMetaTitle('新增') // 设置页面标题
            ->setPostUrl(U()) // 设置表单提交地址
            ->addFormItem('title','text','分类名称','请输入分类名称')
            ->addFormItem('cover','picture','分类图标','请上传图标'.'图片大小20*20px')
            ->addFormItem('cover_status','picture','状态图标','请上传图标'.'图片大小20*20px')
                ->addFormItem('sort', 'num', '排序', '请输入排序')
                ->display();
        }
    }
    public  function  edit($id){
        $model_object =D('Type');
        if(IS_POST){
            $post                 = I('post.');
            if (!$data = $model_object->create($post)) {
                $this->error($model_object->getError());
            } else {
                if (false !== $model_object->where(['id' => $id])->save($data)) {
                    $this->success('更新成功', U('index'));
                } else {
                    trace($model_object->getError());
                    $this->error('更新失败');
                }
            }
        }else{
            $builder = new FormBuilder();
            $builder->setMetaTitle('编辑') // 设置页面标题
            ->setPostUrl(U('',['id' => $id])) // 设置表单提交地址
                ->addFormItem('id','hidden','','')
                ->addFormItem('title','text','分类名称','请输入分类名称')
                ->addFormItem('cover','picture','分类图标','请上传图标'.'图片大小20*20px')
                ->addFormItem('cover_status','picture','状态图标','请上传图标'.'图片大小20*20px')
                ->addFormItem('sort', 'num', '排序', '请输入排序')
                ->setFormData($model_object->find($id))
                ->display();
        }
    }

}