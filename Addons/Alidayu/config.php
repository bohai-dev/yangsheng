<?php
// +----------------------------------------------------------------------
// | 河源市智辰科技有限公司 [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.hychichen.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: huangda <huang-da@qq.com>
// +----------------------------------------------------------------------
return array(
    'status' => array(
        'title'   => '是否开启登录、注册短信:',
        'type'    => 'radio',
        'options' => array(
            '1' => '开启',
            '0' => '关闭',
        ),
        'value'   => '1',
    ),
    'appkey' => array(
        'title' => 'APPKEY：',
        'type'  => 'text',
        'value' => '',
        'tip'   => '请通过www.alidayu.com申请',
    ),
    'secret' => array(
        'title' => 'SECRET：',
        'type'  => 'text',
        'value' => '',
        'tip'   => '请通过www.alidayu.com申请',
    ),
);
