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
class CouponAdmin extends AdminController
{
    /**
     * 默认方法
     */
    public function index()
    {
        // 搜索
        $keyword         = I('keyword', '', 'string');
        $condition       = array('like', '%' . $keyword . '%');
        $map['id|price'] = array($condition, $condition, '_multi' => true);

        // 获取所有分类
        $p             = !empty($_GET["p"]) ? $_GET["p"] : 1;
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $model_object = M('shop_coupon');
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
        $builder->setMetaTitle('首页轮播') // 设置页面标题
            ->addTopButton('addnew') // 添加新增按钮
            ->setSearch('请输入ID/优惠金额', U('index'))
            ->addTableColumn('id', 'ID')
            ->addTableColumn('title', '名称')
            ->addTableColumn('price', '优惠金额')
            ->addTableColumn('start_time', '开始时间')
            ->addTableColumn('end_time', '结束时间')
            // ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('forbid',['model'=>'shop_coupon'])
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('delete',['href'=>U('delete',['id'=>'__data_id__'])])
            // 添加删除按钮
            ->display();
    }

    /**
     * 新增
     */
    public function add()
    {
        if (IS_POST) {
            $model_object = D('coupon');
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
                ->addFormItem('title', 'text', '名称', '名称')
                ->addFormItem('price', 'text', '优惠金额', '请输入优惠金额')
                ->addFormItem('start_time','date','开始时间','开始时间')
                ->addFormItem('end_time','date','结束时间','结束时间')
                ->addFormItem('status','radio','状态','结束时间',[0=>'禁用',1=>'启用'])
                ->display();
        }

    }

    /**
     * 编辑
     */
    public function edit($id)
    {
        $model_object = D('coupon');
        if (IS_POST) {
            if (!$model_object->create()) {
                $this->error($model_object->getError());
            }

            if ($model_object->save()) {
                $this->success('更新成功', U('index'));
            } else {
                $this->error('更新失败');
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('title', 'text', '名称', '名称')
                ->addFormItem('price', 'text', '优惠金额', '请输入优惠金额')
                ->addFormItem('start_time','date','开始时间','开始时间')
                ->addFormItem('end_time','date','结束时间','结束时间')
                ->addFormItem('status','radio','状态','结束时间',[0=>'禁用',1=>'启用'])
                ->setFormData($model_object->find($id))
                ->display();
        }
    }

    /**
     * 删除
     */
    public function delete($id)
    {
      $result = M('shop_coupon')->where(['id'=>$id])->save(['status'=>-1]);
      if ($result) {
          $this->success('删除成功，不可恢复！');
      } else {
          $this->error('删除失败');
      }
    }
}
