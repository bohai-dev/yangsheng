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
class ColumnsAdmin extends AdminController
{
    /**
     * 默认方法
     */
    public function index()
    {
        //头部导航
        $group = I('group',1);
        $map['group'] = $group;
        $tab_list = array(
            '1' => array(
                'title' => '首页栏目',
                'href'   => U('index', array('group' => 1)),
            ),
            '2' => array(
                'title' => '积分栏目',
                'href'   => U('index', array('group' => 2)),
            ),
            '3' => array(
                'title' => '首页广告',
                'href'   => U('index', array('group' => 3)),
            ),
            '4' => array(
                'title' => '开店栏目',
                'href'   => U('index', array('group' => 4)),
            ),
            '5' => array(
                'title' => '美妆栏目',
                'href'   => U('index', array('group' => 5)),
            )
        );
        // 搜索
        $keyword         = I('keyword', '', 'string');
        $condition       = array('like', '%' . $keyword . '%');
        $map['id|title'] = array($condition, $condition, '_multi' => true);

        // 获取所有分类
        $p             = !empty($_GET["p"]) ? $_GET["p"] : 1;
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $model_object = M('shop_columns');
        $data_list     = $model_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('sort DESC,id DESC')
            ->select();
        $page = new Page(
            $model_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('栏目管理') // 设置页面标题
            ->setTabNav($tab_list, $group)
            // ->addTopButton('addnew',['href'=>U('add',['group'=>$group])]) // 添加新增按钮
            // ->setSearch('请输入ID/标题', U('index'))
            ->addTableColumn('id', 'ID');
        switch ($group) {
            case '1':
                $builder
                    ->addTableColumn('title', '标题')
                    ->addTableColumn('icon', '图标','picture')
                    ->addTableColumn('cover', '封面','picture')
                    ->addTableColumn('sort', '排序');
                break;
            case '2':
                $builder
                    ->addTableColumn('title', '标题')
                    ->addTableColumn('subtitle', '副标题')
                    ->addTableColumn('cover', '封面','picture');
                break;
            case '3':
                $builder
                    ->addTableColumn('position', '位置')
                    ->addTableColumn('url', '链接')
                    ->addTableColumn('cover', '封面','picture');
                break;
            case '4':
                $builder
                    ->addTableColumn('title', '标题')
                    ->addTableColumn('cover', '封面','picture')
                    ->addTableColumn('sort', '排序');
                break;
            case '5':
                $builder
                    ->addTableColumn('title', '标题')
                    ->addTableColumn('cover', '封面','picture')
                    ->addTableColumn('sort', '排序');
                break;
            default:
                # code...
                break;
        }

        $builder->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit') // 添加编辑按钮
            // ->addRightButton('delete') // 添加删除按钮
            // ->addRightButton('forbid',['model'=>'shop_coupon'])
            ->display();
    }

    /**
     * 新增 (暂时不需要)
     */
    public function add($group)
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
                    ->addFormItem('title', 'text', '标题', '请输入标题');
            if($group==1){
                $builder->addFormItem('icon', 'picture', '图标', '请上传图标')
                        ->addFormItem('cover', 'picture', '封面', '请上传封面')
                        ->addFormItem('sort', 'text', '排序', '请输入整数');
            }elseif($group==2){
                $builder ->addFormItem('subtitle', 'text', '副标题', '请输入副标题')
                         ->addFormItem('cover', 'picture', '封面', '请上传封面');
            }
            $builder->display();
        }
    }

    /**
     * 编辑
     */
    public function edit($id)
    {
        $model_object = D('Columns');
        if (IS_POST) {
            if (!$data = $model_object->create()) {
                $this->error($model_object->getError());
            }

            if ($model_object->save()) {
                $this->success('更新成功', U('index',['group'=>$data['group']]));
            } else {
                $this->error('更新失败');
            }
        } else {
            $info = $model_object->find($id);
            $group = $info['group'];
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('group', 'hidden', '', '');
            if($group==1){
                $builder
                    ->addFormItem('title', 'text', '标题', '请输入标题')
                    ->addFormItem('icon', 'picture', '图标', '推荐尺寸：28*28')
                    ->addFormItem('url', 'text', '链接', '请输入链接')
                    ->addFormItem('cover', 'picture', '封面', '推荐尺寸：640*320')
                    ->addFormItem('sort', 'text', '排序', '请输入整数');
            }elseif($group==2){
                $builder
                    ->addFormItem('title', 'text', '标题', '请输入标题')
                    ->addFormItem('subtitle', 'text', '副标题', '请输入副标题')
                    ->addFormItem('cover', 'picture', '封面', '推荐尺寸：640*320')
                    ->addFormItem('url', 'text', '链接', '请输入链接');
            }elseif($group==3){
                $builder
                    ->addFormItem('position', 'static', '位置', '')
                    ->addFormItem('url', 'text', '链接', '请输入链接');
                if($id==7){
                    $builder
                        ->addFormItem('cover', 'picture', '封面', '推荐尺寸：320*276')
                        ->addFormItem('pagepic', 'picture', '页中图片','推荐尺寸：640*320');
                }elseif($id==8){
                    $builder
                    ->addFormItem('cover', 'picture', '封面', '推荐尺寸：320*138')
                    ->addFormItem('pagepic', 'picture', '页中图片','推荐尺寸：640*320');
                }elseif($id==9){
                     $builder
                    ->addFormItem('cover', 'picture', '封面', '推荐尺寸：320*138');
                }

            }elseif($group==4){
                $builder
                    ->addFormItem('title', 'text', '标题', '请输入标题')
                    ->addFormItem('cover', 'picture', '封面', '推荐尺寸：640*320')
                    ->addFormItem('sort', 'text', '排序', '请输入整数');

            }elseif($group==5){
                $builder
                    ->addFormItem('title', 'text', '标题', '请输入标题')
                    ->addFormItem('cover', 'picture', '封面', '推荐尺寸：640*320')
                    ->addFormItem('sort', 'text', '排序', '请输入整数');
            }
            $builder->setFormData($info)
                    ->display();
        }
    }
}
