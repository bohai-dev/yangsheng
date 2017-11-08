<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author:
// +----------------------------------------------------------------------
namespace Shop\Admin;

use Admin\Controller\AdminController;
use Common\Util\Think\Page;

/**
 *广告控制器 可删
 */
class AdvertisingAdmin extends AdminController
{
    /**
     * 默认方法
     */
    public function index()
    {
        // $type = I('type',1);
        // $map['type'] = $type;
        // $tab_list = array(
        //     '1' => array(
        //         'title' => '顶部广告',
        //         'href'   => U('index', array('type' => 1)),
        //     ),
        //     '2' => array(
        //         'title' => '页中广告',
        //         'href'   => U('index', array('type' => 2)),
        //     )
        // );
        $p             = !empty($_GET["p"]) ? $_GET["p"] : 1;
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $model_object = D('Advertising');
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
        $builder->setMetaTitle('广告管理') // 设置页面标题
            // ->setTabNav($tab_list, $type)
            ->addTableColumn('id', 'ID')
            ->addTableColumn('position', '位置')
            ->addTableColumn('cover', '封面', 'picture');
            if($type==1){
                $builder->addTableColumn('title', '标题')
                    ->addTableColumn('subtitle', '副标题');
            }
            $builder->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()); // 数据列表分页
            if($type==1){
                $builder->addRightButton('edit');
            }else if($type=2){
                $builder->addRightButton('edit',['href'=>U('update',['id'=>'__data_id__','type'=>$type])]);
            }
            // 添加编辑按钮
            // ->addRightButton('delete') // 添加删除按钮
            $builder->display();
    }



   /**
     * 编辑顶部广告
     */
    public function edit($id)
    {
        $model_object = D('Advertising');
        if (IS_POST) {
            $data          = $model_object->create();
            if ($data) {
                $id = $model_object->save();
                if ($id !== false) {
                    $this->success('更新成功', U('index',['type'=>1]));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($model_object->getError());
            }
        } else {

        // 使用FormBuilder快速建立表单页面。
        $builder = new \Common\Builder\FormBuilder();
        $builder->setMetaTitle('编辑') // 设置页面标题
            ->setPostUrl(U('edit')) // 设置表单提交地址
            ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->addFormItem('position', 'static', '位置', '')
            ->addFormItem('title', 'text', '标题', '标题')
            ->addFormItem('subtitle', 'text', '副标题', '副标题')
            ->addFormItem('cover', 'picture', '封面', '');
            if($id!=3){
                $builder->addFormItem('headpic', 'picture', '图片', '广告页顶部图片');
            }
            $builder->addFormItem('url', 'text', '链接', '点击跳转链接')
            ->setFormData($model_object->find($id))
            ->display();
        }
    }

    /**
     * 编辑页中广告
     * cover 可以多图
     */
    public function update($id)
    {
        $model_object = D('Advertising');
        if (IS_POST) {
            $data          = $model_object->create();
            if ($data) {
                $id = $model_object->save();
                if ($id !== false) {
                    $this->success('更新成功', U('index',['type'=>2]));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($model_object->getError());
            }
        } else {
        // 使用FormBuilder快速建立表单页面。
        $builder = new \Common\Builder\FormBuilder();
        $builder->setMetaTitle('编辑') // 设置页面标题
            ->setPostUrl(U('update')) // 设置表单提交地址
            ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->addFormItem('position', 'static', '位置', '')
            ->addFormItem('cover', 'pictures', '封面', '')
            ->addFormItem('url', 'text', '链接', '点击跳转链接')
            ->setFormData($model_object->find($id))
            ->display();
        }
    }

}
