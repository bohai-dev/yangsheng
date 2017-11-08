<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Shop\Admin;
use Admin\Controller\AdminController;
use Common\Util\Think\Page;
/**
 * 默认控制器
 * @author jry <598821125@qq.com>
 */
class IndexAdmin extends AdminController {

    public function index()
    {
          // 搜索
        $keyword                                  = I('keyword', '', 'string');
        $condition                                = array('like', '%' . $keyword . '%');
        $map['oc_admin_user.id|oc_admin_user.nickname|oc_admin_user.mobile'] = array(
            $condition,
            $condition,
            $condition,
            '_multi' => true,
        );

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




}