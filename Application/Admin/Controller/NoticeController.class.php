<?php
/**
 * Created by PhpStorm.
 * User: 水目
 * Date: 2017/3/14 0014
 * Time: 14:41
 */
namespace Admin\Controller;

use Common\Util\Think\Page;
class NoticeController extends AdminController{
    public function index(){
        // 搜索
        $keyword         = I('keyword', '', 'string');
        $condition       = array('like', '%' . $keyword . '%');
        $map['id|title'] = array($condition, $condition, '_multi' => true);

        // 获取所有分类
        $p             = !empty($_GET["p"]) ? $_GET["p"] : 1;
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $model_object = D('shop_notice');
        $data_list     = $model_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('id DESC')
            ->select();
        $page = new Page(
            $model_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('系统通知列表') // 设置页面标题
        ->addTopButton('addnew') // 添加新增按钮
        ->setSearch('请输入ID/标题', U('index'))
            ->addTableColumn('id', 'ID')
            ->addTableColumn('title', '标题','callback','shorten_cloumn')
            ->addTableColumn('abstract', '概述','callback','shorten_cloumn')
            ->addTableColumn('cover', '封面','picture')
            // ->addTableColumn('url', '链接')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();

    }
    public  function  add(){
        $model_object = D('Notice');
        if(IS_POST){
            $post = I('post.');
            $post['time'] =datetime();
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
            $builder =new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增') // 设置页面标题
            ->setPostUrl(U()) // 设置表单提交地址
            ->addFormItem('title', 'text', '标题', '')
                ->addFormItem('abstract', 'text', '概述', '')
                ->addFormItem('cover','picture','图片','')
                // ->addFormItem('url','text','链接','链接')
                ->addFormItem('content','kindeditor','通知内容','通知内容')
                ->display();
        }
    }
    public  function  edit($id){
        $model_object = D('Notice');
        if(IS_POST){
            $post = I('post.');
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
            $builder =new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑') // 设置页面标题
            ->setPostUrl(U('', ['id' => $id])) // 设置表单提交地址
            ->addFormItem('title', 'text', '标题', '')
                ->addFormItem('abstract', 'text', '概述', '')
                ->addFormItem('cover','picture','图片','')
                // ->addFormItem('url','text','链接','链接')
                ->addFormItem('content','kindeditor','通知内容','通知内容')
                ->setFormData($model_object->find($id))
                ->display();
        }
    }
}