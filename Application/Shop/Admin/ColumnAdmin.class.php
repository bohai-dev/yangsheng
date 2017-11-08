<?php
/**
 * Created by PhpStorm.
 * User: 水目
 * Date: 2017/10/20 0020
 * Time: 17:23
 */
namespace Shop\Admin;

use Admin\Controller\AdminController;
use Common\Util\Think\Page;
use Common\Builder\ListBuilder;
use Common\Builder\FormBuilder;
class ColumnAdmin extends AdminController
{
    public  function  index(){
        $map = [];
        list($data_list, $page, $model_object) = $this->lists('shop_column', $map, 'sort asc,id ASC');
        // 使用Builder快速建立列表页面。
        $builder = new ListBuilder();
        $builder->setMetaTitle('首页图标列表') // 设置页面标题
            ->addTableColumn('id', 'id')
            ->addTableColumn('title','标题')
            ->addTableColumn('cover','图标','picture')
            ->addTableColumn('url','链接')
            ->addTableColumn('sort', '排序')
            ->addTableColumn('right_button', '操作管理', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit') // 添加编辑按钮
            ->display();
    }

    public  function  edit($id){
        $model_object = M('shop_column');
        if (IS_POST) {
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
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new FormBuilder();
            $builder->setMetaTitle('编辑') // 设置页面标题
            ->setPostUrl(U('', ['id' => $id])) // 设置表单提交地址
            ->addFormItem('title', 'text', '标题', '')
                ->addFormItem('cover','picture','图标','图标')
                ->addFormItem('url','text','链接','链接')
                ->addFormItem('sort', 'num', '排序', '')
                ->setFormData($model_object->find($id))
                ->display();
        }
    }
}