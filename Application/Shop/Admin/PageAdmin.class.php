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
 *轮播图控制器
 */
class PageAdmin extends AdminController
{
    /**
     * 默认方法
     */
    public function index()
    {

        $type = I('type',1);
        $tab_list = array(
            '1' => array(
                'title' => '开店必备',
                'href'   => U('index', array('type' => 1)),
            ),
            '2' => array(
                'title' => '个人美妆',
                'href'   => U('index', array('type' => 2)),
            )
        );

        $map['type'] = $type;
        // 获取所有分类
        $p             = !empty($_GET["p"]) ? $_GET["p"] : 1;
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $model_object = M('shop_page');
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
        $builder->setMetaTitle('文章单页') // 设置页面标题
            // ->addTopButton('addnew',['href'=>U('add',['type'=>$type])]) // 添加新增按钮
            // ->setSearch('请输入ID/标题', U('index',['type'=>$type]))
            ->setTabNav($tab_list, $type)
            ->addTableColumn('id', 'ID')
            ->addTableColumn('cover', '封面', 'picture')
            ->addTableColumn('title', '标题')
            // ->addTableColumn('create_time', '创建时间', 'time')
            // ->addTableColumn('sort', '排序')
            // ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit') // 添加编辑按钮
            // ->addRightButton('delete') // 添加删除按钮
            ->display();
    }


    /**
     * 编辑文章
     */
    public function edit($id)
    {
        $model_object = M('shop_page');
        if (IS_POST) {
            $post = I('post.');

            if(empty($post['title'])){
                $this->error('标题不能为空');
            }

            if(empty($post['cover'])){
                $this->error('请上传封面');
            }
            if(empty($post['description']) && empty($post['url'])){
                $this->error('请填写URL或页面详情');
            }
            $post['update_time']= datetime();
            $data          = $model_object->create($post);
            if ($data) {
                $id = $model_object->save();
                if ($id !== false) {
                    $this->success('更新成功', U('index',['type'=>$data['type']]));
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
                ->addFormItem('type', 'hidden', '', '')
                ->addFormItem('title', 'text', '标题', '标题')
                ->addFormItem('cover', 'picture', '封面', '推荐尺寸：640*320')
                ->addFormItem('url', 'text', '跳转', '跳转链接')
                ->addFormItem('description','kindeditor','页面详情','页面详情')
                // ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData($model_object->find($id))
                ->display();
        }
    }
}
