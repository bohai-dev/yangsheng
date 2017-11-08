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
 *优惠券控制器
 */
class SearchAdmin extends AdminController
{
    /**
     * 默认方法
     */
    public function index()
    {
        // 搜索
        $keyword         = I('keyword', '', 'string');
        $condition       = array('like', '%' . $keyword . '%');
        $map['id|word'] = array($condition, $condition, '_multi' => true);

        // 获取所有分类
        $p             = !empty($_GET["p"]) ? $_GET["p"] : 1;
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $model_object = M('shop_search');
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
        $builder->setMetaTitle('栏目管理') // 设置页面标题
            ->addTopButton('addnew') // 添加新增按钮
            ->setSearch('请输入ID/关键字', U('index'))
            ->addTableColumn('id', 'ID')
            ->addTableColumn('word', '关键字')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();
    }

    /**
     * 新增
     */
    public function add()
    {
        if (IS_POST) {
            $model_object = D('search');
            if (!$model_object->create()) {
                $this->error($model_object->getError());
            }
            if ($model_object->add()) {
                $this->success('新增成功', U('index'));
            } else {
                $this->error('新增失败');
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增') // 设置页面标题
                ->setPostUrl(U('add')) // 设置表单提交地址
                ->addFormItem('word', 'text', '关键字', '请输入热搜关键字')
                ->display();
        }
    }

    /**
     * 编辑
     */
    public function edit($id)
    {
        $model_object = D('search');
        if (IS_POST) {
            if (!$data = $model_object->create()) {
                $this->error($model_object->getError());
            }
            if ($model_object->save()) {
                $this->success('更新成功', U('index'));
            } else {
                $this->error('更新失败');
            }
        } else {
            $info = $model_object->find($id);
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('word','text','关键字','请输入热搜关键字')
                ->setFormData($info)
                ->display();
        }
    }
}
