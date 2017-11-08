<?php
/**
 * Function
 * Created by PhpStorm.
 * User: john
 * Date: 2017/2/21
 * Time: 15:56
 */
namespace  Forum\Admin;


use Admin\Controller\AdminController;
use Common\Util\Think\Page;
use Common\Builder\FormBuilder;
use Common\Builder\ListBuilder;

class SearchAdmin extends  AdminController{
    public  function  index(){
        $group = I('group',1);
        $map['group'] =$group;
        $tab_list    = [
            '1'=>['title' => '招聘搜索热词', 'href' => U('index', ['group' => '1'])],
            '2'=>['title' => '出租搜索热词', 'href' => U('index', ['group' => '2'])],
        ];
        // 获取所有分类
        $p             = !empty($_GET["p"]) ? $_GET["p"] : 1;
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $slider_object = D('forum_search');
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
        $builder->setMetaTitle('搜索热词列表') // 设置页面标题
        ->setTabNav($tab_list,$group);
        $builder->addTopButton('addnew', ['href' => U('add', ['group' =>$group])]) // 添加新增按钮
        ->addTopButton('resume') // 添加启用按钮
        ->addTopButton('forbid'); // 添加禁用按钮
        //   ->setSearch('请输入ID/模型标题', U('index'))
        $builder->addTableColumn('id', 'ID')
            ->addTableColumn('title', '热词')
            ->addTableColumn('sort', '排序')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('forbid',['model'=>'forum_search']) // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();
    }
    public  function  add(){
        $slider_object =D('forum_search');
        if (IS_POST) {
            $data = $slider_object->create();
            if ($data) {
                $id = $slider_object->add();
                if ($id) {
                    $this->success('新增成功', U('index',['group'=>I('group',$data['group'])]));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($slider_object->getError());
            }
        } else {
            $group =I('group',1);
            $default_data = [
                'group'        => $group,
            ];
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增广告') // 设置页面标题
            ->setPostUrl(U('add')) // 设置表单提交地址
            ->addFormItem('title', 'text', '热词', '热词')
                ->addFormItem('group', 'hidden', '','')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData($default_data)
                ->display();
        }

    }
    public  function  edit($id){
        if (IS_POST) {
            $slider_object = D('forum_search');
            $data          = $slider_object->create();
            if ($data) {
                $id = $slider_object->save();
                if ($id !== false) {
                    $this->success('更新成功',U('index',['group'=>I('group','1')]));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($slider_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑热词') // 设置页面标题
            ->setPostUrl(U('edit')) // 设置表单提交地址
            ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('group', 'hidden', 'ID', 'ID')
                ->addFormItem('title', 'text', '热词', '热词')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData(D('forum_search')->find($id))
                ->display();
        }
    }
}