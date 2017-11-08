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
    'name' => 'Forum',
    'title' => '论坛',
    'icon' => 'fa fa-volume-up',
    'icon_color' => '#F9B440',
    'description' => '论坛模块',
    'developer' => '西陆科技',
    'website' => 'http://lingyun.net',
    'version' => '1.0.0',
    'dependences' => 
    array (
      'Admin' => '1.0.0',
    ),
  ),
  'user_nav' => 
  array (
  ),
  'config' => 
  array (
    'score' => 
    array (
      'title' => '积分打赏设置',
      'type' => 'textarea',
      'tip' => '多个积分以逗号隔开',
      'value' => '',
    ),
  ),
  'admin_menu' => 
  array (
    1 => 
    array (
      'pid' => '0',
      'title' => '论坛',
      'icon' => 'fa fa-volume-up',
      'id' => 1,
    ),
    2 => 
    array (
      'id' => 2,
      'pid' => '1',
      'title' => '论坛管理',
      'url' => '',
      'icon' => 'fa fa-folder-open-o',
      'sort' => '',
    ),
    3 => 
    array (
      'id' => 3,
      'pid' => '2',
      'title' => '论坛分类',
      'url' => 'Forum/Index/index',
      'icon' => 'fa fa-volume-up',
      'sort' => '',
    ),
    4 => 
    array (
      'id' => 4,
      'pid' => '2',
      'title' => '幻灯片管理',
      'url' => 'Forum/Slider/index',
      'icon' => 'fa fa-picture-o',
      'sort' => '2',
    ),
    5 => 
    array (
      'pid' => '1',
      'title' => '求职招聘管理',
      'url' => '',
      'icon' => 'fa fa-user',
      'sort' => '',
      'id' => 5,
    ),
    6 => 
    array (
      'pid' => '1',
      'title' => '转让出租管理',
      'url' => '',
      'icon' => 'fa fa-user',
      'sort' => '',
      'id' => 6,
    ),
    7 => 
    array (
      'id' => 7,
      'pid' => '5',
      'title' => '职位列表',
      'url' => 'Forum/Job/index',
      'icon' => 'fa fa-pencil-square',
      'sort' => '',
    ),
    8 => 
    array (
      'id' => 8,
      'pid' => '5',
      'title' => '求职列表',
      'url' => 'Forum/Job/job_search',
      'icon' => 'fa fa-user',
      'sort' => '',
    ),
    9 => 
    array (
      'id' => 9,
      'pid' => '6',
      'title' => '出租列表',
      'url' => 'Forum/Rent/index',
      'icon' => 'fa fa-table',
      'sort' => '',
    ),
    10 => 
    array (
      'id' => 10,
      'pid' => '2',
      'title' => '帖子管理',
      'url' => 'Forum/Forum/index',
      'icon' => 'fa fa-file-o',
      'sort' => '',
    ),
    11 => 
    array (
      'id' => 11,
      'pid' => '1',
      'title' => '广告管理',
      'url' => 'Forum/Adver/Index',
      'icon' => 'fa fa-folder-open-o',
      'sort' => '1',
    ),
    12 => 
    array (
      'id' => 12,
      'pid' => '11',
      'title' => '广告设置',
      'url' => 'Forum/Adver/index',
      'icon' => 'fa fa-adn',
      'sort' => '1',
    ),
    13 => 
    array (
      'pid' => '11',
      'title' => '搜索管理',
      'url' => 'Forum/Search/index',
      'icon' => 'fa fa-search',
      'sort' => '2',
      'id' => 13,
    ),
    14 => 
    array (
      'pid' => '3',
      'title' => '新增',
      'url' => 'Forum/Index/add',
      'icon' => 'fa ',
      'sort' => '1',
      'id' => 14,
    ),
    15 => 
    array (
      'pid' => '3',
      'title' => '编辑',
      'url' => 'Forum/Index/edit',
      'icon' => 'fa ',
      'sort' => '2',
      'id' => 15,
    ),
    16 => 
    array (
      'pid' => '3',
      'title' => '删除',
      'url' => 'Forum/Index/setStatus/status/delete',
      'icon' => 'fa ',
      'sort' => '3',
      'id' => 16,
    ),
    17 => 
    array (
      'pid' => '3',
      'title' => '启用',
      'url' => 'Forum/Index/setStatus/status/resume',
      'icon' => 'fa ',
      'sort' => '4',
      'id' => 17,
    ),
    18 => 
    array (
      'pid' => '3',
      'title' => '禁用',
      'url' => 'Forum/Index/setStatus/status/forbid',
      'icon' => 'fa ',
      'sort' => '5',
      'id' => 18,
    ),
    19 => 
    array (
      'pid' => '4',
      'title' => '新增',
      'url' => 'Forum/Slider/add',
      'icon' => 'fa ',
      'sort' => '1',
      'id' => 19,
    ),
    20 => 
    array (
      'pid' => '4',
      'title' => '编辑',
      'url' => 'Forum/Slider/edit',
      'icon' => 'fa ',
      'sort' => '2',
      'id' => 20,
    ),
    21 => 
    array (
      'pid' => '4',
      'title' => '删除',
      'url' => 'Forum/Slider/setStatus/status/delete',
      'icon' => 'fa ',
      'sort' => '3',
      'id' => 21,
    ),
    22 => 
    array (
      'pid' => '4',
      'title' => '启用',
      'url' => 'Forum/Slider/setStatus/status/resume',
      'icon' => 'fa ',
      'sort' => '4',
      'id' => 22,
    ),
    23 => 
    array (
      'pid' => '4',
      'title' => '禁用',
      'url' => 'Forum/Slider/setStatus/status/forbid',
      'icon' => 'fa ',
      'sort' => '5',
      'id' => 23,
    ),
    24 => 
    array (
      'pid' => '10',
      'title' => '编辑',
      'url' => 'Forum/Forum/edit',
      'icon' => 'fa ',
      'sort' => '1',
      'id' => 24,
    ),
    25 => 
    array (
      'id' => 25,
      'pid' => '10',
      'title' => '禁用',
      'url' => 'Forum/Forum/setStatus/status/unchecked',
      'icon' => 'fa ',
      'sort' => '2',
    ),
    26 => 
    array (
      'id' => 26,
      'pid' => '10',
      'title' => '启用',
      'url' => 'Forum/Forum/setStatus/status/checked',
      'icon' => 'fa ',
      'sort' => '3',
    ),
    27 => 
    array (
      'pid' => '10',
      'title' => '查看打赏记录',
      'url' => 'Forum/Forum/reward_record',
      'icon' => 'fa ',
      'sort' => '4',
      'id' => 27,
    ),
    28 => 
    array (
      'id' => 28,
      'pid' => '7',
      'title' => '禁用',
      'url' => 'Forum/Job/setStatus/status/unchecked',
      'icon' => 'fa ',
      'sort' => '1',
    ),
    29 => 
    array (
      'id' => 29,
      'pid' => '7',
      'title' => '启用',
      'url' => 'Forum/Job/setStatus/status/checked',
      'icon' => 'fa ',
      'sort' => '2',
    ),
    30 => 
    array (
      'pid' => '7',
      'title' => '编辑',
      'url' => 'Forum/Job/edit',
      'icon' => 'fa ',
      'sort' => '3',
      'id' => 30,
    ),
    31 => 
    array (
      'pid' => '8',
      'title' => '编辑',
      'url' => 'Forum/Job/job_edit',
      'icon' => 'fa ',
      'sort' => '1',
      'id' => 31,
    ),
    32 => 
    array (
      'id' => 32,
      'pid' => '8',
      'title' => '禁用',
      'url' => 'Forum/Job/setStatus/status/unchecked',
      'icon' => 'fa ',
      'sort' => '2',
    ),
    33 => 
    array (
      'id' => 33,
      'pid' => '8',
      'title' => '启用',
      'url' => 'Forum/Job/setStatus/status/checked',
      'icon' => 'fa ',
      'sort' => '3',
    ),
    34 => 
    array (
      'id' => 34,
      'pid' => '9',
      'title' => '禁用',
      'url' => 'Forum/Rent/setStatus/status/unchecked',
      'icon' => 'fa ',
      'sort' => '1',
    ),
    35 => 
    array (
      'id' => 35,
      'pid' => '9',
      'title' => '启用',
      'url' => 'Forum/Rent/setStatus/status/checked',
      'icon' => 'fa ',
      'sort' => '2',
    ),
    36 => 
    array (
      'pid' => '9',
      'title' => '编辑',
      'url' => 'Forum/Rent/edit',
      'icon' => 'fa ',
      'sort' => '3',
      'id' => 36,
    ),
    37 => 
    array (
      'pid' => '12',
      'title' => '编辑',
      'url' => 'Forum/Adver/edit',
      'icon' => 'fa ',
      'sort' => '1',
      'id' => 37,
    ),
    38 => 
    array (
      'pid' => '12',
      'title' => '新增',
      'url' => 'Forum/Adver/add',
      'icon' => 'fa ',
      'sort' => '2',
      'id' => 38,
    ),
    39 => 
    array (
      'pid' => '12',
      'title' => '删除',
      'url' => 'Forum/Adver/setStatus/status/delete',
      'icon' => 'fa ',
      'sort' => '3',
      'id' => 39,
    ),
    40 => 
    array (
      'pid' => '12',
      'title' => '禁用',
      'url' => 'Forum/Adver/setStatus/status/forbid',
      'icon' => 'fa ',
      'sort' => '4',
      'id' => 40,
    ),
    41 => 
    array (
      'pid' => '12',
      'title' => '启用',
      'url' => 'Forum/Adver/setStatus/status/resume',
      'icon' => 'fa ',
      'sort' => '5',
      'id' => 41,
    ),
    42 => 
    array (
      'pid' => '13',
      'title' => '新增',
      'url' => 'Forum/Search/add',
      'icon' => 'fa ',
      'sort' => '1',
      'id' => 42,
    ),
    43 => 
    array (
      'pid' => '13',
      'title' => '编辑',
      'url' => 'Forum/Search/edit',
      'icon' => 'fa ',
      'sort' => '2',
      'id' => 43,
    ),
    44 => 
    array (
      'pid' => '13',
      'title' => '删除',
      'url' => 'Forum/Search/setStatus/status/delete',
      'icon' => 'fa ',
      'sort' => '3',
      'id' => 44,
    ),
    45 => 
    array (
      'pid' => '13',
      'title' => '禁用',
      'url' => 'Forum/Search/setStatus/status/forbid',
      'icon' => 'fa ',
      'sort' => '4',
      'id' => 45,
    ),
    46 => 
    array (
      'pid' => '13',
      'title' => '启用',
      'url' => 'Forum/Search/setStatus/status/resume',
      'icon' => 'fa ',
      'sort' => '5',
      'id' => 46,
    ),
    47 => 
    array (
      'id' => 47,
      'pid' => '10',
      'title' => '删除',
      'url' => 'Forum/Forum/del_forum',
      'icon' => 'fa ',
      'sort' => '6',
    ),
    48 => 
    array (
      'pid' => '7',
      'title' => '删除',
      'url' => 'Forum/Job/setStatus/status/delete',
      'icon' => 'fa ',
      'sort' => '6',
      'id' => 48,
    ),
    49 => 
    array (
      'pid' => '8',
      'title' => '删除',
      'url' => 'Forum/Job/setStatus/status/delete',
      'icon' => 'fa ',
      'sort' => '6',
      'id' => 49,
    ),
    50 => 
    array (
      'pid' => '9',
      'title' => '删除',
      'url' => 'Forum/Rent/setStatus/status/delete',
      'icon' => 'fa ',
      'sort' => '6',
      'id' => 50,
    ),
    51 => 
    array (
      'pid' => '10',
      'title' => '置顶',
      'url' => 'Forum/Forum/setStatus/status/top',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 51,
    ),
    52 => 
    array (
      'id' => 52,
      'pid' => '10',
      'title' => '取消置顶',
      'url' => 'Forum/Forum/setStatus/status/untop',
      'icon' => 'fa ',
      'sort' => '',
    ),
    53 => 
    array (
      'pid' => '10',
      'title' => '精华',
      'url' => 'Forum/Forum/setStatus/status/classic',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 53,
    ),
    54 => 
    array (
      'pid' => '10',
      'title' => '取消精华',
      'url' => 'Forum/Forum/setStatus/status/unclassic',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 54,
    ),
    55 => 
    array (
      'pid' => '10',
      'title' => '评论管理',
      'url' => 'Forum/Forum/user_comment_list',
      'icon' => 'fa fa-th-list',
      'sort' => '',
      'id' => 55,
    ),
    56 => 
    array (
      'pid' => '55',
      'title' => '删除',
      'url' => 'Forum/Forum/del_com',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 56,
    ),
    57 => 
    array (
      'pid' => '55',
      'title' => '查看评论',
      'url' => 'Forum/Forum/comment_list',
      'icon' => 'fa ',
      'sort' => '1',
    ),
  ),
)
;