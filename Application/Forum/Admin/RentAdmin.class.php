<?php
namespace Forum\Admin;

use Admin\Controller\AdminController;
use Common\Util\Think\Page;
use Common\Builder\FormBuilder;
use Common\Builder\ListBuilder;

/**
 * 默认控制器
 */
class RentAdmin extends AdminController{
    public  function  index(){
        list($data_list, $page, $model_object) = $this->lists('rent', '', 'status asc,id DESC');
        $builder = new ListBuilder();
        $builder->setMetaTitle('出租列表') // 设置页面标题
        ->addTableColumn('id', 'id')
            ->addTableColumn('type','类型','callback',array(D('Rent'), 'get_type'))
            ->addTableColumn('title', '标题')
            ->addTableColumn('address','地址')
            ->addTableColumn('price','价格')
            ->addTableColumn('contacts','联系人')
            ->addTableColumn('telephone','联系方式')
            ->addTableColumn('right_button', '操作管理', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton("check") // 添加禁用/启用按钮
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('delete') // 添加编辑按钮
            ->display();
    }

    public  function  edit($id){
        if (IS_POST) {
            $slider_object = D('Rent');
            $data          = $slider_object->create();
            if ($data) {
                $id = $slider_object->save();
                if ($id !== false) {
                    $this->success('更新成功',U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($slider_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $info = D('Rent')->find($id);
            $info['type'] =D('Rent')->get_type($info['type']);
            $info['area'] =$info['area'].$info['address'];
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑') // 设置页面标题
            ->setPostUrl(U('edit')) // 设置表单提交地址
            ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('title','static','标题','')
                ->addFormItem('area','static','地址','')
                ->addFormItem('type','static','类型','')
                ->addFormItem('price','static','价格','')
                ->addFormItem('acreage','static','面积','')
                ->addFormItem('description','static','描述','')
                ->addFormItem('telephone','static','联系方式','')
                ->addFormItem('pics','pictures','图片','')
                ->addFormItem('status','radio','审核状态','',[0=>'未审核',1=>'审核通过'])
                ->setFormData($info)
                ->display();
        }
    }
}