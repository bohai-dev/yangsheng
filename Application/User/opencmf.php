<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
// 模块信息配置
return array (
  'info' => 
  array (
    'name' => 'User',
    'title' => '用户',
    'icon' => 'fa fa-users',
    'icon_color' => '#F9B440',
    'description' => '用户中心模块',
    'developer' => '西陆科技',
    'website' => 'http://www.opencmf.cn',
    'version' => '1.0.0',
    'dependences' => 
    array (
      'Admin' => '1.0.0',
    ),
  ),
  'user_nav' => 
  array (
    'title' => 
    array (
      'center' => '个人信息',
    ),
    'center' => 
    array (
      0 => 
      array (
        'title' => '修改信息',
        'icon' => 'fa fa-edit',
        'url' => 'User/Center/profile',
      ),
      1 => 
      array (
        'title' => '修改密码',
        'icon' => 'fa fa-lock',
        'url' => 'User/Center/password',
      ),
      2 => 
      array (
        'title' => '消息中心',
        'icon' => 'fa fa-envelope-o',
        'url' => 'User/Message/index',
        'badge' => 
        array (
          0 => 'User/Message',
          1 => 'newMessageCount',
        ),
        'badge_class' => 'badge-danger',
      ),
    ),
    'main' => 
    array (
      0 => 
      array (
        'title' => '个人中心',
        'icon' => 'fa fa-tachometer',
        'url' => 'User/Center/index',
      ),
    ),
  ),
  'config' => 
  array (
    'status' => 
    array (
      'title' => '是否开启',
      'type' => 'radio',
      'options' => 
      array (
        1 => '开启',
        0 => '关闭',
      ),
      'value' => '1',
    ),
    'reg_toggle' => 
    array (
      'title' => '注册开关',
      'type' => 'radio',
      'options' => 
      array (
        1 => '开启',
        0 => '关闭',
      ),
      'value' => '1',
    ),
    'allow_reg_type' => 
    array (
      'title' => '允许注册类型',
      'type' => 'checkbox',
      'options' => 
      array (
        'username' => '用户名注册',
        'email' => '邮箱注册',
        'mobile' => '手机注册',
      ),
      'value' => 
      array (
        0 => 'username',
      ),
    ),
    'deny_username' => 
    array (
      'title' => '禁止注册的用户名',
      'type' => 'textarea',
      'value' => '',
    ),
    'user_protocol' => 
    array (
      'title' => '用户协议',
      'type' => 'kindeditor',
      'value' => '',
    ),
    'behavior' => 
    array (
      'title' => '行为扩展',
      'type' => 'checkbox',
      'options' => 
      array (
        'User' => 'User',
      ),
      'value' => 
      array (
        0 => 'User',
      ),
    ),
    'default_avatar' => 
    array (
      'title' => '默认头像',
      'type' => 'picture_crop',
      'options' => 
      array (
        'aspectRatio' => 1,
        'targetWidth' => '100',
        'targetHeight' => '100',
      ),
      'value' => 0,
    ),
  ),
  'admin_menu' => 
  array (
    1 => 
    array (
      'pid' => '0',
      'title' => '用户',
      'icon' => 'fa fa-user',
      'id' => 1,
    ),
    2 => 
    array (
      'pid' => '1',
      'title' => '用户管理',
      'icon' => 'fa fa-folder-open-o',
      'id' => 2,
    ),
    4 => 
    array (
      'pid' => '2',
      'title' => '用户统计',
      'icon' => 'fa fa-area-chart',
      'url' => 'User/Index/index',
      'id' => 4,
    ),
    5 => 
    array (
      'pid' => '2',
      'title' => '用户列表',
      'icon' => 'fa fa-list',
      'url' => 'User/User/index',
      'id' => 5,
    ),
    6 => 
    array (
      'pid' => '5',
      'title' => '新增',
      'url' => 'User/User/add',
      'id' => 6,
    ),
    7 => 
    array (
      'pid' => '5',
      'title' => '编辑',
      'url' => 'User/User/edit',
      'id' => 7,
    ),
    8 => 
    array (
      'pid' => '5',
      'title' => '设置状态',
      'url' => 'User/User/setStatus',
      'id' => 8,
    ),
    10 => 
    array (
      'pid' => '9',
      'title' => '新增',
      'url' => 'User/Type/add',
      'id' => 10,
    ),
    11 => 
    array (
      'pid' => '9',
      'title' => '编辑',
      'url' => 'User/Type/edit',
      'id' => 11,
    ),
    12 => 
    array (
      'pid' => '9',
      'title' => '设置状态',
      'url' => 'User/Type/setStatus',
      'id' => 12,
    ),
    13 => 
    array (
      'pid' => '9',
      'title' => '字段管理',
      'icon' => 'fa fa-users',
      'url' => 'User/Attribute/index',
      'id' => 13,
    ),
    14 => 
    array (
      'pid' => '13',
      'title' => '新增',
      'url' => 'User/Attribute/add',
      'id' => 14,
    ),
    15 => 
    array (
      'pid' => '13',
      'title' => '编辑',
      'url' => 'User/Attribute/edit',
      'id' => 15,
    ),
    16 => 
    array (
      'pid' => '13',
      'title' => '设置状态',
      'url' => 'User/Attribute/setStatus',
      'id' => 16,
    ),
    20 => 
    array (
      'id' => 20,
      'pid' => '2',
      'title' => '业务联系人',
      'url' => 'User/Contacts/Index',
      'icon' => 'fa fa-user',
      'sort' => '',
    ),
    21 => 
    array (
      'id' => 21,
      'pid' => '2',
      'title' => '活动列表',
      'url' => 'User/Meeting/index',
      'icon' => 'fa fa-glass',
      'sort' => '',
    ),
    22 => 
    array (
      'id' => '22',
      'pid' => '2',
      'title' => '用户等级',
      'url' => 'User/Typeuser/index',
      'icon' => 'fa fa-th-large',
      'sort' => '',
    ),
  ),
)
;