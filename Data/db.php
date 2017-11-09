<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------

/**
 * CoreThink数据库连接配置文件
 */

if (!function_exists('slog')) {
    require './slog.php';
    $slog_config = array(
        'enable'              => true, //是否打印日志的开关
        'host'                => 'slog.thinkphp.cn', //websocket服务器地址，默认localhost
        'optimize'            => true, //是否显示利于优化的参数，如果运行时间，消耗内存等，默认为false
        'show_included_files' => true, //是否显示本次程序运行加载了哪些文件，默认为false
        'error_handler'       => true, //是否接管程序错误，将程序错误显示在console中，默认为false
        'force_client_id'     => '', //日志强制记录到配置的client_id,默认为空
        'allow_client_ids'    => array('slog_7e39ba'), ////限制允许读取日志的client_id，默认为空,表示所有人都可以获得日志。
    );
    if (MODULE_MARK == 'Admin') {
        // $slog_config['enable'] = false;
    }
    if (isset($_GET['slog_force_client_id'])) {
        $slog_config['force_client_id'] = $_GET['slog_force_client_id'];
    } else {
        if (is_weixin()) {
            // $slog_config['force_client_id'] = 'slog_7e39ba';
        }
    }
    slog($slog_config, 'config');
}

require_once './vendor/autoload.php';

// 开启开发部署模式
if (isset($_SERVER[ENV_PRE.'DEV_MODE']) && $_SERVER[ENV_PRE.'DEV_MODE'] === 'true') {
    // 数据库配置
    return array(
        'DB_TYPE'   => $_SERVER[ENV_PRE.'DB_TYPE'] ? : 'mysql',       // 数据库类型
        'DB_HOST'   => $_SERVER[ENV_PRE.'DB_HOST'] ? : '127.0.0.1',       // 服务器地址
        'DB_NAME'   => $_SERVER[ENV_PRE.'DB_NAME'] ? : 'lyadmin',       // 数据库名
        'DB_USER'   => $_SERVER[ENV_PRE.'DB_USER'] ? : 'root',       // 用户名
        'DB_PWD'    => $_SERVER[ENV_PRE.'DB_PWD']  ? : '',        // 密码
        'DB_PORT'   => $_SERVER[ENV_PRE.'DB_PORT'] ? : '3306',            // 端口
        'DB_PREFIX' => $_SERVER[ENV_PRE.'DB_PREFIX'] ? : 'mt_',   // 数据库表前缀
    );
} else {
    // 数据库配置
    return array(
        'DB_TYPE'   => 'mysql',       // 数据库类型
        'DB_PORT'   => '3306',            // 端口
        'DB_PREFIX' => 'oc_',     // 数据库表前缀
        'DB_CHARSET'=> 'utf8mb4',

       /* 'DB_HOST'   => '106.14.215.221', // 服务器地址
        'DB_NAME'   => 'yangsheng', // 数据库名
        'DB_USER'   => 'root', // 用户名
        'DB_PWD'    => '123456', // 密码*/

        'DB_HOST'   => '192.168.1.106', // 服务器地址
        'DB_NAME'   => 'yangsheng', // 数据库名
        'DB_USER'   => 'yangsheng', // 用户名
        'DB_PWD'    => 'yangsheng', // 密码

        // 'DB_HOST'   => '127.0.0.1', // 服务器地址
        // 'DB_NAME'   => 'dzqh', // 数据库名
        // 'DB_USER'   => 'root', // 用户名
        // 'DB_PWD'    => 'root', // 密码
    );
}
