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
 *运费管理控制器
 */
class PostageAdmin extends AdminController
{
    /**
     * 编辑
     */
    public function index()
    {
        $model_object = M('shop_postage');
        if (IS_POST) {
            $post = I('post.');

            if(empty($post['price']) && $post['price']==0){
                $this->error('总运费不能为空');
            }
            if(!is_numeric($post['postage'])){
                $this->error('总运费格式不正确');
            }
            if(empty($post['price'])){
                $this->error('消费金额不能为空');
            }
            if(!checkPrice($post['price'])){
                $this->error('消费金额格式不正确');
            }

            if(empty($post['weight'])){
                $this->error('包邮重量不能为空');
            }

            if(!is_numeric($post['weight'])){
                $this->error('包邮重量格式不正确');
            }
            $post['update_time'] = datetime();
            if (!$model_object->create($post)) {
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
            $builder->setMetaTitle('运费管理') // 设置页面标题
                ->setPostUrl(U('index')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('postage', 'text', '总运费(元)', '请输入总运费')
                ->addFormItem('price', 'text', '消费金额(元)', '达到消费金额包邮')
                ->addFormItem('weight', 'text', '包邮重量(kg)', '请输入包邮重量')
                ->addFormItem('description','kindeditor','运费说明','')
                ->setFormData($model_object->find(1))
                ->display();
        }
    }
}
