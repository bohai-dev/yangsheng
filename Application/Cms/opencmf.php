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
    'name' => 'Cms',
    'title' => 'CMS',
    'icon' => 'fa fa-newspaper-o',
    'icon_color' => '#9933FF',
    'description' => 'CMS门户模块',
    'developer' => '西陆科技',
    'website'     => 'http://lingyun.net',
    'version' => '1.0.0',
    'dependences' => 
    array (
      'Admin' => '1.0.0',
    ),
  ),
  'user_nav' => 
  array (
    'center' => 
    array (
      0 => 
      array (
        'title' => '我的文档',
        'icon' => 'fa fa-list',
        'url' => 'Cms/Index/my',
      ),
    ),
  ),
  'config' => 
  array (
    'need_check' => 
    array (
      'title' => '前台发布审核',
      'type' => 'radio',
      'options' => 
      array (
        1 => '需要',
        0 => '不需要',
      ),
      'value' => '0',
    ),
    'toggle_comment' => 
    array (
      'title' => '是否允许评论文档',
      'type' => 'radio',
      'options' => 
      array (
        1 => '允许',
        0 => '不允许',
      ),
      'value' => '1',
    ),
    'group_list' => 
    array (
      'title' => '栏目分组',
      'type' => 'array',
      'value' => '1:默认',
    ),
    'cate' => 
    array (
      'title' => '首页栏目自定义',
      'type' => 'array',
      'value' => 'a:1',
    ),
    'taglib' => 
    array (
      'title' => '加载标签库',
      'type' => 'checkbox',
      'options' => 
      array (
        'Cms' => 'Cms',
      ),
      'value' => 
      array (
        0 => 'Cms',
      ),
    ),
  ),
  'admin_menu' => 
  array (
    1 => 
    array (
      'id' => '1',
      'pid' => '0',
      'title' => 'CMS',
      'url' => '',
      'icon' => 'fa fa-newspaper-o',
    ),
    2 => 
    array (
      'pid' => '1',
      'title' => '内容管理',
      'icon' => 'fa fa-folder-open-o',
      'id' => 2,
    ),
    3 => 
    array (
      'pid' => '2',
      'title' => '文章配置',
      'icon' => 'fa fa-wrench',
      'url' => 'Cms/Index/module_config',
      'id' => 3,
    ),
    4 => 
    array (
      'pid' => '2',
      'title' => '文档模型',
      'icon' => 'fa fa-th-large',
      'url' => 'Cms/Type/index',
      'id' => 4,
    ),
    5 => 
    array (
      'pid' => '4',
      'title' => '新增',
      'url' => 'Cms/Type/add',
      'id' => 5,
    ),
    6 => 
    array (
      'pid' => '4',
      'title' => '编辑',
      'url' => 'Cms/Type/edit',
      'id' => 6,
    ),
    7 => 
    array (
      'pid' => '4',
      'title' => '设置状态',
      'url' => 'Cms/Type/setStatus',
      'id' => 7,
    ),
    8 => 
    array (
      'pid' => '4',
      'title' => '字段管理',
      'icon' => 'fa fa-database',
      'url' => 'Cms/Attribute/index',
      'id' => 8,
    ),
    9 => 
    array (
      'pid' => '8',
      'title' => '新增',
      'url' => 'Cms/Attribute/add',
      'id' => 9,
    ),
    10 => 
    array (
      'pid' => '8',
      'title' => '编辑',
      'url' => 'Cms/Attribute/edit',
      'id' => 10,
    ),
    11 => 
    array (
      'pid' => '8',
      'title' => '设置状态',
      'url' => 'Cms/Attribute/setStatus',
      'id' => 11,
    ),
    12 => 
    array (
      'pid' => '2',
      'title' => '栏目分类',
      'icon' => 'fa fa-navicon',
      'url' => 'Cms/Category/index',
      'id' => 12,
    ),
    13 => 
    array (
      'pid' => '12',
      'title' => '新增',
      'url' => 'Cms/Category/add',
      'id' => 13,
    ),
    14 => 
    array (
      'pid' => '12',
      'title' => '编辑',
      'url' => 'Cms/Category/edit',
      'id' => 14,
    ),
    15 => 
    array (
      'pid' => '12',
      'title' => '设置状态',
      'url' => 'Cms/Category/setStatus',
      'id' => 15,
    ),
    16 => 
    array (
      'pid' => '2',
      'title' => '文章管理',
      'icon' => 'fa fa-edit',
      'url' => 'Cms/Index/index',
      'id' => 16,
    ),
    17 => 
    array (
      'pid' => '16',
      'title' => '新增',
      'url' => 'Cms/Index/add',
      'id' => 17,
    ),
    18 => 
    array (
      'pid' => '16',
      'title' => '编辑',
      'url' => 'Cms/Index/edit',
      'id' => 18,
    ),
    19 => 
    array (
      'pid' => '16',
      'title' => '回收文章',
      'url' => 'Cms/Index/setStatus/status/recycle',
      'id' => 19,
    ),
    20 => 
    array (
      'pid' => '2',
      'title' => '幻灯切换',
      'icon' => 'fa fa-image',
      'url' => 'Cms/Slider/index',
      'id' => 20,
    ),
    21 => 
    array (
      'pid' => '20',
      'title' => '新增',
      'url' => 'Cms/Slider/add',
      'id' => 21,
    ),
    22 => 
    array (
      'pid' => '20',
      'title' => '编辑',
      'url' => 'Cms/Slider/edit',
      'id' => 22,
    ),
    23 => 
    array (
      'pid' => '20',
      'title' => '设置状态',
      'url' => 'Cms/Slider/setStatus',
      'id' => 23,
    ),
    24 => 
    array (
      'pid' => '2',
      'title' => '通知公告',
      'icon' => 'fa fa-bullhorn',
      'url' => 'Cms/Notice/index',
      'id' => 24,
    ),
    25 => 
    array (
      'pid' => '24',
      'title' => '新增',
      'url' => 'Cms/Notice/add',
      'id' => 25,
    ),
    26 => 
    array (
      'pid' => '24',
      'title' => '编辑',
      'url' => 'Cms/Notice/edit',
      'id' => 26,
    ),
    27 => 
    array (
      'id' => 27,
      'pid' => '24',
      'title' => '设置状态',
      'url' => 'Cms/Notice/setStatus',
      'icon' => '',
    ),
    28 => 
    array (
      'pid' => '2',
      'title' => '底部导航',
      'icon' => 'fa fa-map-signs',
      'url' => 'Cms/Footnav/index',
      'id' => 28,
    ),
    29 => 
    array (
      'pid' => '28',
      'title' => '新增',
      'url' => 'Cms/Footnav/add',
      'id' => 29,
    ),
    30 => 
    array (
      'pid' => '28',
      'title' => '编辑',
      'url' => 'Cms/Footnav/edit',
      'id' => 30,
    ),
    31 => 
    array (
      'pid' => '28',
      'title' => '设置状态',
      'url' => 'Cms/Footnav/setStatus',
      'id' => 31,
    ),
    32 => 
    array (
      'pid' => '2',
      'title' => '友情链接',
      'icon' => 'fa fa-link',
      'url' => 'Cms/FriendlyLink/index',
      'id' => 32,
    ),
    33 => 
    array (
      'pid' => '32',
      'title' => '新增',
      'url' => 'Cms/FriendlyLink/add',
      'id' => 33,
    ),
    34 => 
    array (
      'pid' => '32',
      'title' => '编辑',
      'url' => 'Cms/FriendlyLink/edit',
      'id' => 34,
    ),
    35 => 
    array (
      'pid' => '32',
      'title' => '设置状态',
      'url' => 'Cms/FriendlyLink/setStatus',
      'id' => 35,
    ),
    36 => 
    array (
      'pid' => '2',
      'title' => '回收站',
      'icon' => 'fa fa-recycle',
      'url' => 'Cms/Index/recycle',
      'id' => 36,
    ),
    37 => 
    array (
      'pid' => '36',
      'title' => '取消回收',
      'url' => 'Cms/Index/setStatus/status/restore',
      'id' => 37,
    ),
    38 => 
    array (
      'pid' => '36',
      'title' => '彻底删除',
      'url' => 'Cms/Index/setStatus/status/delete',
      'id' => 38,
    ),
  ),
)
;