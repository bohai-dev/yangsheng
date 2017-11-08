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
class SliderAdmin extends AdminController
{
    /**
     * 默认方法
     */
    public function index()
    {
        // 搜索
        $keyword         = I('keyword', '', 'string');
        $condition       = array('like', '%' . $keyword . '%');
        $map['id|title'] = array($condition, $condition, '_multi' => true);

        // $type        = I('type', 'weixin');

        $type = I('type',1);
        $tab_list = array(
            '1' => array(
                'title' => '首页轮播',
                'href'   => U('index', array('type' => 1)),
            ),
            '2' => array(
                'title' => '开店轮播',
                'href'   => U('index', array('type' => 2)),
            ),
            '3' => array(
                'title' => '美妆轮播',
                'href'   => U('index', array('type' => 3)),

            ),
            '4' => array(
                'title' => '头条轮播',
                'href'   => U('index', array('type' => 4)),

            )

        );

        $map['type'] = $type;
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
        $builder->setMetaTitle('首页轮播') // 设置页面标题
            ->addTopButton('addnew',['href'=>U('add',['type'=>$type])]) // 添加新增按钮
            ->setSearch('请输入ID/标题', U('index',['type'=>$type]))
            ->setTabNav($tab_list, $type)
            ->addTableColumn('id', 'ID')
            ->addTableColumn('cover', '图片', 'picture')
            ->addTableColumn('title', '标题')
            ->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn('sort', '排序')
            // ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();
    }

    /**
     * 新增文档
     */
    public function add($type)
    {
        $slider_object = D('Slider');
        if (IS_POST) {
            $data = $slider_object->create();
            if ($data) {
                $id = $slider_object->add();
                if ($id) {
                    $this->success('新增成功', U('index',['type'=>$type]));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($slider_object->getError());
            }
        } else {
            $js = <<< JS
<script>
function show_list(is_url){
    if(is_url==0){
         $('.item_url').show();
         $('.item_content').hide();
    }else{
         $('.item_url').hide();
         $('.item_content').show();
    }
}
$(function(){
    var is_url =$('[name="is_url"]:checked').val();
    if(group==4){
    var group =$('[name="type"]').val();
    show_list(is_url);
     $('[name="is_url"]').on('change',function(){
        var value =$(this).val();
        show_list(value);
     });
    }

});
</script>
JS;
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增') // 设置页面标题
            ->setPostUrl(U('add')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('type', 'hidden', '', '')
                ->addFormItem('title', 'text', '标题', '标题')
                ->addFormItem('description','text','描述','描述')
                ->addFormItem('cover', 'picture', '图片', '推荐尺寸：640*320');
            if($type==4){
                $builder->addFormItem('is_url','radio','请选择显示方式','',[0=>'链接',1=>'单页']);
                $builder->addFormItem('url', 'text', '链接', '点击跳转链接');
                $builder->addFormItem('content','kindeditor','图文详情','图文详情');
            }else{
                $builder->addFormItem('url', 'text', '链接', '点击跳转链接');
            }
                $builder->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData(['type'=>$type])
                    ->setExtraHtml($js)
                ->display();
        }

    }

    /**
     * 编辑文章
     */
    public function edit($id)
    {
        if (IS_POST) {
            $slider_object = D('Slider');
            $data          = $slider_object->create();
            if ($data) {
                $id = $slider_object->save();
                if ($id !== false) {
                    $this->success('更新成功', U('index',['type'=>$data['type']]));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($slider_object->getError());
            }
        } else {
            $info =D('Slider')->find($id);
            $js = <<< JS
<script>
function show_list(is_url){
    if(is_url==0){
         $('.item_url').show();
         $('.item_content').hide();
    }else{
         $('.item_url').hide();
         $('.item_content').show();
    }
}
$(function(){
    var group =$('[name="type"]').val();
    if(group==4){
        var is_url =$('[name="is_url"]:checked').val();
        show_list(is_url);
         $('[name="is_url"]').on('change',function(){
            var value =$(this).val();
            show_list(value);
         });
    }
});
</script>
JS;
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('type', 'hidden', '', '')
                ->addFormItem('title', 'text', '标题', '标题')
                ->addFormItem('description','text','描述','描述')
                ->addFormItem('cover', 'picture', '图片', '推荐尺寸：640*320');
                 //   ->addFormItem('is_show','radio','标题描述是否显示','',[1=>'显示',0=>'不显示'])
            //    ->addFormItem('is_bg','radio','透明度','',[1=>'有',0=>'无'])
            if($info['type']==4){
                $builder->addFormItem('is_url','radio','请选择显示方式','',[0=>'链接',1=>'单页']);
                $builder->addFormItem('url', 'text', '链接', '点击跳转链接');
                $builder->addFormItem('content','kindeditor','图文详情','图文详情');
            }else{
                $builder->addFormItem('url', 'text', '链接', '点击跳转链接');
            }
                $builder->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData($info)
                ->setExtraHtml($js)
                ->display();
        }
    }
}
