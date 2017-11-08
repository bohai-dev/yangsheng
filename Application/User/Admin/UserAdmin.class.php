<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace User\Admin;

use Admin\Controller\AdminController;
use Common\Util\Think\Page;

/**
 * 用户控制器
 * @author jry <598821125@qq.com>
 */
class UserAdmin extends AdminController
{
    /**
     * 用户列表
     * @author jry <598821125@qq.com>
     */


    public function index(){
        // 搜索
        $keyword                                  = I('keyword', '', 'string');
        $condition                                = array('like', '%' . $keyword . '%');
        $map['oc_admin_user.id|oc_admin_user.nickname|oc_admin_user.mobile'] = array(
            $condition,
            $condition,
            $condition,
            '_multi' => true,
        );

        // 获取所有用户
        $map['oc_admin_user.user_status'] = 1; // 区分前后台
        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $user_object   = M('admin_user');
        $data_list     = $user_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('id desc')
            ->select();
        $page = new Page(
            $user_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // $shopModel = D('Shop/shop');

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('用户列表') // 设置页面标题
            ->addTopButton('addnew') // 添加新增按钮
            // ->addTopButton('resume', array('model' => 'user')) // 添加启用按钮
        // ->setSearch('请输入ID/用户名', U('index'))
            ->addSearchItem('keyword', 'text', '', '请输入ID/姓名/手机')
            // ->addSearchItem('email_bind', 'select', '', '', ['' => '是否验证邮箱', '0' => '未验证', '1' => '已验证'])
            ->addTableColumn('id', 'ID')
            ->addTableColumn('nickname', '姓名')
            ->addTableColumn('mobile', '手机号')
            ->addTableColumn('headimgurl', '头像', 'callback',[$shopModel,'getImg'])
            ->addTableColumn('wxname', '微信昵称')
            // ->addTableColumn('create_time', '创建时间')
            // ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('delete') // 添加删除按钮
            // ->addRightButton('forbid') // 添加禁用/启用按钮
            ->display();
    }


    /**
     * 新增用户
     * @author jry <598821125@qq.com>
     */
    public function add()
    {
        if (IS_POST) {
            $user_object = D('User/User');
            $post = I('post.');
            $post['username'] = $post['mobile'];
            if (!$data = $user_object->create($post)) {
                $this->error($user_object->getError());
            }
            if ($user_object->add()) {
                $this->success('新增成功', U('index'));
            } else {
                $this->error('新增失败');
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增用户') //设置页面标题
                ->setPostUrl(U('add')) //设置表单提交地址
                ->addFormItem('reg_type', 'hidden', '注册方式', '注册方式')
                ->addFormItem('user_type', 'select', '用户等级', '用户等级', select_list_as_tree('user_typeuser'))
                ->addFormItem('nickname', 'text', '姓名', '姓名')
                ->addFormItem('mobile', 'text', '手机号', '手机号')
                // ->addFormItem('gender', 'radio', '性别', '性别')
                ->setFormData(array('reg_type' => 'admin'))
                ->display();
        }
    }

    /**
     * 编辑用户
     * @author jry <598821125@qq.com>
     */
    public function edit($id)
    {
        $user_object = D('User/User');

        if (IS_POST) {
            if (!$data= $user_object->create()) {
                $this->error($user_object->getError());
            }
;           $result = $user_object
                        ->field('id,nickname,mobile,gender,avatar,update_time')
                        ->save($data);
            if ($result) {
                $this->success('更新成功', U('index'));
            } else {
                $this->error('更新失败', $user_object->getError());
            }

        } else {
            // 获取账号信息
            $info = $user_object->field('id,user_type,nickname,mobile')->find($id);
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑用户') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('user_type', 'select', '用户等级', '用户等级', select_list_as_tree('user_typeuser'))
                ->addFormItem('nickname', 'text', '姓名', '姓名')
                ->addFormItem('mobile', 'text', '手机号', '手机号')
                ->setFormData($info)
                ->display();
        }
    }

    /**
     * 设置一条或者多条数据的状态
     * @author jry <598821125@qq.com>
     */
    public function setStatus($model = CONTROLLER_NAME, $script = false)
    {
        $ids = I('request.ids');
        if (is_array($ids)) {
            if (in_array('1', $ids)) {
                $this->error('超级管理员不允许操作');
            }
        } else {
            if ($ids === '1') {
                $this->error('超级管理员不允许操作');
            }
        }
        parent::setStatus($model);
    }
}
