<?php
namespace Forum\Admin;

use Admin\Controller\AdminController;
use Common\Util\Think\Page;
use Common\Builder\FormBuilder;
use Common\Builder\ListBuilder;

/**
 * 默认控制器
 */
class JobAdmin extends AdminController{
    public  function  index(){
        list($data_list, $page, $model_object) = $this->lists('forum_job', '', 'status asc,  id DESC');
        $builder = new ListBuilder();
        $builder->setMetaTitle('职位列表') // 设置页面标题
        //前提数据库中必须有一个status的字段
        /*  ->addTopButton('addnew')*/
        ->addTableColumn('id', 'id')
            ->addTableColumn('company', '公司名称')
            ->addTableColumn('position','职位')
            ->addTableColumn('telephone','联系方式')
            ->addTableColumn('contacts','联系人')
            ->addTableColumn('status','状态','callback','callback_status')
            // ->addTableColumn('search_index', '是否搜索')
            ->addTableColumn('right_button', '操作管理', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            // ->addRightButton("check",['model'=>'forum_job']) // 添加禁用/启用按钮
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton("delete",['model'=>'forum_job']) // 添加禁用/启用按钮
            ->display();
    }
    public  function  edit($id){
        if (IS_POST) {
            $slider_object = D('forum_job');
            $data          = $slider_object->create();
            if ($data) {
                $id = $slider_object->save();
                if ($id !== false) {
                    checked_sendcustom($data['id'],$data['status'],'forum_job');//发送客服消息。
                    $this->success('更新成功',U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($slider_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $info = D('forum_job')->find($id);
            $info['area'] =$info['prov'].$info['city'].$info['country'].$info['address'];
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑') // 设置页面标题
            ->setPostUrl(U('edit')) // 设置表单提交地址
            ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('title','text','标题','')
                ->addFormItem('position','text','职位','')
                ->addFormItem('position','text','职位','')
                ->addFormItem('num','text','招聘人数','')
                ->addFormItem('exprience','text','要求','')
                ->addFormItem('salary','text','待遇','')
                ->addFormItem('area','static','地区','')
                ->addFormItem('description','textarea','描述','')
                ->addFormItem('company','text','公司名称','')
                ->addFormItem('company_desc','textarea','公司描述','')
                ->addFormItem('contacts','text','联系人','')
                ->addFormItem('telephone','text','联系方式','')
                ->addFormItem('pics','pictures','图片','')
                ->addFormItem('status','radio','审核状态','',[-1=>'已拒绝',0=>'未审核',1=>'审核通过'])
                ->setFormData($info)
                ->display();
        }
    }
    public  function  job_search(){
        list($data_list, $page, $model_object) = $this->lists('forum_job_search', '', 'status asc,id DESC');
        $builder = new ListBuilder();
        $builder->setMetaTitle('求职列表') // 设置页面标题
        //前提数据库中必须有一个status的字段
        /*  ->addTopButton('addnew')*/
        ->addTableColumn('id', 'id')
            ->addTableColumn('position','求职职位')
            ->addTableColumn('area','求职区域')
            ->addTableColumn('salary','期望薪资')
            ->addTableColumn('telephone','联系方式')
            ->addTableColumn('name','联系人')
            ->addTableColumn('status','状态','callback','callback_status')
            // ->addTableColumn('search_index', '是否搜索')
            ->addTableColumn('right_button', '操作管理', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            // ->addRightButton("check",['model'=>'forum_job_search']) // 添加禁用/启用按钮
            // ->addRightButton("refuse",['model'=>'forum_job_search']) // 添加禁用/启用按钮
            // ->addRightButton("self",['title'=>'拒绝','class'=>'label label-danger-outline label-pill ajax-get confirm','href'=>U("status_refuse",["ids"=>"__data_id__","model"=>"forum_job_search"])]) // 添加拒绝
            ->addRightButton('edit',['href'=>U('job_edit',array('id'=>'__data_id__'))]) // 添加编辑按钮
            ->addRightButton("delete",['model'=>'forum_job_search']) // 添加禁用/启用按钮
            ->display();
    }

    public  function job_edit($id){
        if (IS_POST) {
            $slider_object = D('forum_job_search');
            $data          = $slider_object->create();
            if ($data) {
                $id = $slider_object->save();
                if ($id !== false) {
                    checked_sendcustom($data['id'],$data['status'],'forum_job_search');//发送客服消息。
                    $this->success('更新成功',U('job_search'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($slider_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $info = D('forum_job_search')->find($id);
            switch($info['sex']){
                case 1:
                    $info['sex'] ='男';
                    break;
                case 2 :
                    $info['sex']='女';
                    break;
            }
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑') // 设置页面标题
            ->setPostUrl(U('job_edit')) // 设置表单提交地址
            ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('name','static','姓名','')
                ->addFormItem('sex','static','性别','')
                ->addFormItem('education','static','学历','')
                ->addFormItem('exprience','static','工作经验','')
                ->addFormItem('area','static','期望工作地址','')
                ->addFormItem('position','static','职位','')
                ->addFormItem('salary','static','待遇','')
                ->addFormItem('description','static','描述','')
                ->addFormItem('telephone','static','联系方式','')
                ->addFormItem('pics','pictures','图片','')
                ->addFormItem('status','radio','审核状态','',[-1=>'已拒绝',0=>'未审核',1=>'审核通过'])
                ->setFormData($info)
                ->display();
        }
    }
}