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
    'name' => 'Shop',
    'title' => '商家',
    'icon' => 'fa fa-shopping-cart',
    'icon_color' => '#F9B440',
    'description' => '商家模块',
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
    'company_url' => 
    array (
      'title' => '公司官网地址',
      'type' => 'text',
    ),
    'share_title' => 
    array (
      'title' => '微信分享标题',
      'type' => 'text',
    ),
    'share_desc' => 
    array (
      'title' => '微信分享描述',
      'type' => 'text',
    ),
    'share_img' => 
    array (
      'title' => '微信分享图片',
      'type' => 'picture',
    ),
  ),
  'admin_menu' => 
  array (
    1 => 
    array (
      'id' => 1,
      'pid' => '0',
      'title' => '商城',
      'url' => '',
      'icon' => 'fa fa-shopping-cart',
      'sort' => '',
    ),
    40 => 
    array (
      'id' => 40,
      'pid' => '43',
      'title' => '商品分类',
      'url' => 'Shop/Goodstype/index',
      'icon' => 'fa fa-cog',
      'sort' => '',
    ),
    41 => 
    array (
      'id' => 41,
      'pid' => '1',
      'title' => '商城管理',
      'url' => '',
      'icon' => 'fa fa-folder-open-o',
      'sort' => '7',
    ),
    42 => 
    array (
      'id' => 42,
      'pid' => '43',
      'title' => '商品列表',
      'url' => 'Shop/Goods/index',
      'icon' => 'fa fa-gift',
      'sort' => '',
    ),
    43 => 
    array (
      'id' => 43,
      'pid' => '1',
      'title' => '商品管理',
      'url' => '',
      'icon' => 'fa fa-folder-open-o',
      'sort' => '11',
    ),
    44 => 
    array (
      'id' => 44,
      'pid' => '1',
      'title' => '订单管理',
      'url' => '',
      'icon' => 'fa fa-folder-open-o',
      'sort' => '',
    ),
    45 => 
    array (
      'id' => 45,
      'pid' => '53',
      'title' => '轮播管理',
      'url' => 'Shop/Slider/index',
      'icon' => 'fa fa-picture-o',
      'sort' => '',
    ),
    48 => 
    array (
      'id' => 48,
      'pid' => '44',
      'title' => '订单列表',
      'url' => 'Shop/Order/index',
      'icon' => 'fa fa-file-o',
      'sort' => '',
    ),
    49 => 
    array (
      'id' => 49,
      'pid' => '53',
      'title' => '文章单页',
      'url' => 'Shop/Page/index',
      'icon' => 'fa fa-file-o',
      'sort' => '',
    ),
    50 => 
    array (
      'id' => 50,
      'pid' => '41',
      'title' => '今日头条',
      'url' => 'Shop/Bulletin/index',
      'icon' => 'fa fa-volume-down',
      'sort' => '',
    ),
    51 => 
    array (
      'id' => 51,
      'pid' => '52',
      'title' => '优惠管理',
      'url' => 'Shop/Coupon/index',
      'icon' => 'fa fa-usd',
      'sort' => '',
    ),
    52 => 
    array (
      'id' => 52,
      'pid' => '1',
      'title' => '优惠活动',
      'url' => '',
      'icon' => 'fa fa-folder-open-o',
      'sort' => '',
    ),
    53 => 
    array (
      'id' => 53,
      'pid' => '1',
      'title' => '广告管理',
      'url' => '',
      'icon' => 'fa fa-folder-open-o',
      'sort' => '',
    ),
    56 => 
    array (
      'id' => 56,
      'pid' => '52',
      'title' => '秒杀管理',
      'url' => 'Shop/Seckill/index',
      'icon' => 'fa fa-bolt',
      'sort' => '',
    ),
    57 => 
    array (
      'id' => 57,
      'pid' => '53',
      'title' => '栏目广告',
      'url' => 'Shop/Columns/index',
      'icon' => 'fa fa-desktop',
      'sort' => '',
    ),
    58 => 
    array (
      'pid' => '41',
      'title' => '热搜管理',
      'url' => 'Shop/Search/index',
      'icon' => 'fa fa-search',
      'sort' => '',
      'id' => 58,
    ),
    59 => 
    array (
      'id' => 59,
      'pid' => '41',
      'title' => '商城设置',
      'url' => 'Shop/Set/index',
      'icon' => 'fa fa-cogs',
      'sort' => '',
    ),
    60 => 
    array (
      'pid' => '44',
      'title' => '退款管理',
      'url' => 'Shop/Order/refund_order',
      'icon' => 'fa fa-trello',
      'sort' => '',
      'id' => 60,
    ),
    61 => 
    array (
      'pid' => '50',
      'title' => '新增',
      'url' => 'Shop/Bulletin/add',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 61,
    ),
    62 => 
    array (
      'pid' => '50',
      'title' => '编辑',
      'url' => 'Shop/Bulletin/edit',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 62,
    ),
    63 => 
    array (
      'id' => 63,
      'pid' => '50',
      'title' => '启用',
      'url' => 'Shop/Bulletin/setStatus/status/resume',
      'icon' => 'fa ',
      'sort' => '',
    ),
    64 => 
    array (
      'id' => 64,
      'pid' => '50',
      'title' => '禁用',
      'url' => 'Shop/Bulletin/setStatus/status/forbid',
      'icon' => 'fa ',
      'sort' => '',
    ),
    65 => 
    array (
      'pid' => '50',
      'title' => '删除',
      'url' => 'Shop/Bulletin/setStatus/status/delete',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 65,
    ),
    66 => 
    array (
      'pid' => '50',
      'title' => '查看评论',
      'url' => 'Shop/Bulletin/review',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 66,
    ),
    67 => 
    array (
      'pid' => '50',
      'title' => '回复',
      'url' => 'Shop/Bulletin/reply',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 67,
    ),
    68 => 
    array (
      'pid' => '58',
      'title' => '新增',
      'url' => 'Shop/Search/add',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 68,
    ),
    69 => 
    array (
      'pid' => '58',
      'title' => '编辑',
      'url' => 'Shop/Search/edit',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 69,
    ),
    70 => 
    array (
      'id' => 70,
      'pid' => '58',
      'title' => '删除',
      'url' => 'Shop/Search/setStatus/status/delete',
      'icon' => 'fa ',
      'sort' => '',
    ),
    71 => 
    array (
      'pid' => '40',
      'title' => '新增',
      'url' => 'Shop/Goodstype/add',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 71,
    ),
    72 => 
    array (
      'pid' => '40',
      'title' => '编辑',
      'url' => 'Shop/Goodstype/edit',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 72,
    ),
    73 => 
    array (
      'pid' => '40',
      'title' => '删除',
      'url' => 'Shop/Goodstype/setStatus/status/delete',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 73,
    ),
    74 => 
    array (
      'pid' => '40',
      'title' => '查看子级',
      'url' => 'Shop/Goodstype/type',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 74,
    ),
    75 => 
    array (
      'pid' => '42',
      'title' => '新增',
      'url' => 'Shop/Goods/add',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 75,
    ),
    76 => 
    array (
      'pid' => '42',
      'title' => '编辑',
      'url' => 'Shop/Goods/edit',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 76,
    ),
    77 => 
    array (
      'id' => 77,
      'pid' => '42',
      'title' => '删除',
      'url' => 'Shop/Goods/delete',
      'icon' => 'fa ',
      'sort' => '',
    ),
    78 => 
    array (
      'id' => 78,
      'pid' => '42',
      'title' => '禁用',
      'url' => 'Shop/Goods/setStatus/status/forbid',
      'icon' => 'fa ',
      'sort' => '',
    ),
    79 => 
    array (
      'pid' => '42',
      'title' => '启用',
      'url' => 'Shop/Goods/setStatus/status/resume',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 79,
    ),
    80 => 
    array (
      'pid' => '42',
      'title' => '查看评论',
      'url' => 'Shop/Goods/review',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 80,
    ),
    81 => 
    array (
      'pid' => '42',
      'title' => '回复',
      'url' => 'Shop/Goods/reply',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 81,
    ),
    82 => 
    array (
      'pid' => '48',
      'title' => '导出订单',
      'url' => 'Shop/Order/export',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 82,
    ),
    83 => 
    array (
      'pid' => '48',
      'title' => '编辑',
      'url' => 'Shop/Order/edit',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 83,
    ),
    84 => 
    array (
      'pid' => '60',
      'title' => '审核',
      'url' => 'Shop/Order/refund_order_edit',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 84,
    ),
    85 => 
    array (
      'pid' => '51',
      'title' => '新增',
      'url' => 'Shop/Coupon/add',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 85,
    ),
    86 => 
    array (
      'pid' => '51',
      'title' => '编辑',
      'url' => 'Shop/Coupon/edit',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 86,
    ),
    87 => 
    array (
      'id' => 87,
      'pid' => '51',
      'title' => '删除',
      'url' => 'Shop/Coupon/delete',
      'icon' => 'fa ',
      'sort' => '',
    ),
    88 => 
    array (
      'pid' => '51',
      'title' => '禁用',
      'url' => 'Shop/Coupon/setStatus/status/forbid',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 88,
    ),
    89 => 
    array (
      'pid' => '51',
      'title' => '启用',
      'url' => 'Shop/Coupon/setStatus/status/resume',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 89,
    ),
    90 => 
    array (
      'pid' => '56',
      'title' => '新增',
      'url' => 'Shop/Seckill/add',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 90,
    ),
    91 => 
    array (
      'pid' => '56',
      'title' => '编辑',
      'url' => 'Shop/Seckill/edit',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 91,
    ),
    92 => 
    array (
      'pid' => '56',
      'title' => '删除',
      'url' => 'Shop/Seckill/delete',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 92,
    ),
    93 => 
    array (
      'pid' => '56',
      'title' => '查看商品',
      'url' => 'Shop/Seckill/seckill_index',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 93,
    ),
    94 => 
    array (
      'id' => 94,
      'pid' => '56',
      'title' => '新增商品',
      'url' => 'Shop/Seckill/seckill_add',
      'icon' => 'fa ',
      'sort' => '',
    ),
    95 => 
    array (
      'id' => 95,
      'pid' => '56',
      'title' => '编辑商品',
      'url' => 'Shop/Seckill/seckill_edit',
      'icon' => 'fa ',
      'sort' => '',
    ),
    96 => 
    array (
      'pid' => '56',
      'title' => '删除商品',
      'url' => 'Shop/Seckill/seckill_delete',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 96,
    ),
    97 => 
    array (
      'pid' => '56',
      'title' => '获取商品',
      'url' => 'Shop/Seckill/goods_list',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 97,
    ),
    98 => 
    array (
      'pid' => '45',
      'title' => '新增',
      'url' => 'Shop/Slider/add',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 98,
    ),
    99 => 
    array (
      'pid' => '45',
      'title' => '编辑',
      'url' => 'Shop/Slider/edit',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 99,
    ),
    100 => 
    array (
      'pid' => '45',
      'title' => '删除',
      'url' => 'Shop/Slider/setStatus/status/delete',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 100,
    ),
    101 => 
    array (
      'pid' => '49',
      'title' => '编辑',
      'url' => 'Shop/Page/edit',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 101,
    ),
    102 => 
    array (
      'pid' => '57',
      'title' => '编辑',
      'url' => 'Shop/Columns/edit',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 102,
    ),
    103 => 
    array (
      'pid' => '40',
      'title' => '查看同级',
      'url' => 'Shop/Goodstype/index/group',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 103,
    ),
    104 => 
    array (
      'pid' => '44',
      'title' => '提现管理',
      'url' => 'Shop/Order/withdraw',
      'icon' => 'fa fa-money',
      'sort' => '4',
      'id' => 104,
    ),
    105 => 
    array (
      'pid' => '104',
      'title' => '审核',
      'url' => 'Shop/Order/withdraw_check',
      'icon' => 'fa ',
      'sort' => '5',
      'id' => 105,
    ),
    107 => 
    array (
      'id' => 107,
      'pid' => '41',
      'title' => '用户列表',
      'url' => 'Shop/User/index',
      'icon' => 'fa fa-user',
      'sort' => '',
    ),
    108 => 
    array (
      'pid' => '41',
      'title' => '用户投诉',
      'url' => 'Shop/User/user_complain',
      'icon' => 'fa fa-comment',
      'sort' => '5',
      'id' => 108,
    ),
    109 => 
    array (
      'pid' => '107',
      'title' => '编辑',
      'url' => 'Shop/User/edit',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 109,
    ),
    110 => 
    array (
      'pid' => '107',
      'title' => '查看积分',
      'url' => 'Shop/User/score_record',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 110,
    ),
    111 => 
    array (
      'pid' => '107',
      'title' => '查看佣金',
      'url' => 'Shop/User/commission_record',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 111,
    ),
    112 => 
    array (
      'pid' => '108',
      'title' => '删除',
      'url' => 'Shop/User/setStatus/status/delete',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 112,
    ),
    113 => 
    array (
      'id' => 113,
      'pid' => '43',
      'title' => '商品规格',
      'url' => 'Shop/GoodsSpec/index',
      'icon' => 'fa fa-suitcase',
      'sort' => '',
    ),
    114 => 
    array (
      'pid' => '113',
      'title' => '新增',
      'url' => 'Shop/GoodsSpec/add',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 114,
    ),
    115 => 
    array (
      'pid' => '113',
      'title' => '编辑',
      'url' => 'Shop/GoodsSpec/edit',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 115,
    ),
    116 => 
    array (
      'pid' => '113',
      'title' => '管理规格项',
      'url' => 'Shop/GoodsSpec/items',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 116,
    ),
    117 => 
    array (
      'pid' => '113',
      'title' => '删除',
      'url' => 'Shop/GoodsSpec/setStatus/status/delete',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 117,
    ),
    118 => 
    array (
      'pid' => '116',
      'title' => '新增',
      'url' => 'Shop/GoodsSpec/items_add',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 118,
    ),
    119 => 
    array (
      'id' => 119,
      'pid' => '116',
      'title' => '编辑',
      'url' => 'Shop/GoodsSpec/items_edit',
      'icon' => 'fa ',
      'sort' => '',
    ),
    120 => 
    array (
      'pid' => '116',
      'title' => '删除',
      'url' => 'Shop/GoodsSpec/setStatus/status/delete',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 120,
    ),
    121 => 
    array (
      'pid' => '42',
      'title' => '获取规格',
      'url' => 'Shop/Goods/getspec',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 121,
    ),
    122 => 
    array (
      'id' => 122,
      'pid' => '1',
      'title' => '抽奖管理',
      'url' => 'Shop/Award/award',
      'icon' => 'fa fa-folder-open-o',
      'sort' => '',
    ),
    123 => 
    array (
      'pid' => '122',
      'title' => '奖品列表',
      'url' => 'Shop/Award/award',
      'icon' => 'fa fa-apple',
      'sort' => '',
      'id' => 123,
    ),
    124 => 
    array (
      'id' => 124,
      'pid' => '123',
      'title' => '新增',
      'url' => 'Shop/Award/award_add',
      'icon' => 'fa ',
      'sort' => '',
    ),
    125 => 
    array (
      'pid' => '123',
      'title' => '编辑',
      'url' => 'Shop/Award/award_edit',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 125,
    ),
    126 => 
    array (
      'pid' => '123',
      'title' => '删除',
      'url' => 'Shop/Award/setStatus/status/recycle',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 126,
    ),
    127 => 
    array (
      'id' => 127,
      'pid' => '122',
      'title' => '中奖记录',
      'url' => 'Shop/Award/index',
      'icon' => 'fa fa-th-large',
      'sort' => '',
    ),
    128 => 
    array (
      'id' => 128,
      'pid' => '41',
      'title' => '头条分类管理',
      'url' => 'Shop/Bulletin/type_index',
      'icon' => 'fa fa-th-large',
      'sort' => '',
    ),
    129 => 
    array (
      'pid' => '128',
      'title' => '新增',
      'url' => 'Shop/Bulletin/type_add',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 129,
    ),
    130 => 
    array (
      'pid' => '128',
      'title' => '编辑',
      'url' => 'Shop/Bulletin/type_edit',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 130,
    ),
    131 => 
    array (
      'pid' => '128',
      'title' => '删除',
      'url' => 'Shop/Bulletin/setStatus/status/delete',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 131,
    ),
    132 => 
    array (
      'pid' => '128',
      'title' => '启用',
      'url' => 'Shop/Bulletin/setStatus/status/resume',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 132,
    ),
    133 => 
    array (
      'pid' => '128',
      'title' => '禁用',
      'url' => 'Shop/Bulletin/setStatus/status/forbid',
      'icon' => 'fa ',
      'sort' => '',
      'id' => 133,
    ),
    134 => 
    array (
      'pid' => '41',
      'title' => '首页栏目管理',
      'url' => 'Shop/Column/index',
      'icon' => 'fa fa-th-large',
      'sort' => '',
      'id' => 134,
    ),
    135 => 
    array (
      'pid' => '134',
      'title' => '编辑',
      'url' => 'Shop/Column/edit',
      'icon' => 'fa fa-th-large',
      'sort' => '',
    ),
  ),
)
;