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
 *秒杀控制器
 */
class SetAdmin extends AdminController
{
  /**
   * 默认方法
   */
  public function index()
  {
    $model_object = D('set');
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
        ->setPostUrl(U('')) // 设置表单提交地址
        ->addFormItem('id', 'hidden', 'ID', 'ID')
        ->addFormItem('integral_rate', 'text', '积分比例', '一元等于多少积分')
        ->addFormItem('integral_get', 'text', '签到积分', '签到一次所获积分')
        ->addFormItem('register_gift', 'text', '注册积分', '用户注册赠送积分')
        ->addFormItem('integral_min', 'text', '积分限制', '购买积分商品限制')
        ->addFormItem('shop_mobile', 'text', '首页客服', '首页客服电话')
        ->addFormItem('postage_total', 'text', '总运费', '大于一件商品,并且未达到包邮条件时的运费')
        ->addFormItem('postage_free', 'text', '包邮条件', '消费满足该金额,包邮')
        ->addFormItem('new_search', 'tags', '养生头条热搜词', '养生头条热搜词')
        ->setFormData($model_object->find())
        ->display();
    }
  }
}
