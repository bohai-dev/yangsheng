<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Forum\Admin;

use Admin\Controller\AdminController;
use Common\Util\Think\Page;

/**
 * 幻灯片控制器
 * @author jry <598821125@qq.com>
 */
class SliderAdmin extends AdminController
{
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        // 搜索
        $keyword         = I('keyword', '', 'string');
        $condition       = array('like', '%' . $keyword . '%');
        $map['id|title'] = array($condition, $condition, '_multi' => true);

        $type        = I('type', 'index');
        switch($type){
            case 'index':
                $group =0;
                break;
            case 'job':
                $group =1;
                break;
            case 'rent':
                $group =2;
                break;
        }
        $map['type'] = $type;
        $tab_list    = [
            ['title' => '论坛BANNER', 'href' => U('index', ['type' => 'index'])],
            ['title' => '招聘BANNER', 'href' => U('index', ['type' => 'job'])],
            ['title' => '出租BANNER', 'href' => U('index', ['type' => 'rent'])]
        ];
        // 获取所有分类
        $p             = !empty($_GET["p"]) ? $_GET["p"] : 1;
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $slider_object = D('Slider');
        $data_list     = $slider_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('sort desc,id desc')
            ->select();
        $page = new Page(
            $slider_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('幻灯列表') // 设置页面标题
        ->setTabNav($tab_list,$group)
            ->addTopButton('addnew', ['href' => U('add', ['type' =>$type])]) // 添加新增按钮
            ->addTopButton('resume') // 添加启用按钮
            ->addTopButton('forbid') // 添加禁用按钮
            //   ->setSearch('请输入ID/模型标题', U('index'))
            ->addTableColumn('id', 'ID')
            ->addTableColumn('cover', '图片', 'picture','图片大小1920*660')
            ->addTableColumn('title', '标题')
            ->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn('sort', '排序')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();
    }

    /**
     * 新增文档
     * @author jry <598821125@qq.com>
     */
    public function add()
    {
        $slider_object = D('Slider');
        if (IS_POST) {
            $data = $slider_object->create();
            if ($data) {
                $id = $slider_object->add();
                if ($id) {
                    $this->success('新增成功', U('index',['type'=>I('type','index')]));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($slider_object->getError());
            }
        } else {
            $type =I('type','index');
            $default_data = [
                'type'        => $type,
            ];
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增幻灯') // 设置页面标题
            ->setPostUrl(U('add')) // 设置表单提交地址
            ->addFormItem('title', 'text', '标题', '标题')
                ->addFormItem('cover', 'picture', '图片','图片大小1920*660')
                ->addFormItem('type', 'hidden', '','')
                ->addFormItem('url', 'text', '链接', '点击跳转链接')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData($default_data)
                ->display();
        }

    }

    /**
     * 编辑文章
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        if (IS_POST) {
            $slider_object = D('Slider');
            $data          = $slider_object->create();
            if ($data) {
                $id = $slider_object->save();
                if ($id !== false) {
                    $this->success('更新成功',U('index',['type'=>I('type','index')]));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($slider_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑幻灯') // 设置页面标题
            ->setPostUrl(U('edit')) // 设置表单提交地址
            ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->addFormItem('type', 'hidden', 'ID', 'ID')
                ->addFormItem('title', 'text', '标题', '标题')
                ->addFormItem('cover', 'picture', '图片', '切换图片')
                ->addFormItem('url', 'text', '链接', '点击跳转链接')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData(D('Slider')->find($id))
                ->display();
        }
    }
}
