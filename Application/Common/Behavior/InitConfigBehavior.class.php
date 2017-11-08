<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Common\Behavior;
use Think\Behavior;
defined('THINK_PATH') or exit();
/**
 * 根据不同情况读取数据库的配置信息并与本地配置合并
 * 本行为扩展很重要会影响核心系统前后台、模块功能及模版主题使用
 * 如非必要或者并不是十分了解系统架构不推荐更改
 * @author jry <598821125@qq.com>
 */
class InitConfigBehavior extends Behavior {
    /**
     * 行为扩展的执行入口必须是run
     * @author jry <598821125@qq.com>
     */
    public function run(&$content) {
        // 安装模式下直接返回
        if(defined('BIND_MODULE') && BIND_MODULE === 'Install') return;

        ////////////////////
        //添加配置 start
        ////////////////////
        // 如果是后台并且不是Admin模块则设置默认控制器层为Admin
        if (MODULE_MARK === 'Admin' && MODULE_NAME !== 'Admin') {
            $oc_config['DEFAULT_C_LAYER'] = 'Admin';
        }

        // 当前模块模版参数配置
        $oc_config['TMPL_PARSE_STRING']             = C('TMPL_PARSE_STRING'); // 先取出配置文件中定义的否则会被覆盖
        $oc_config['TMPL_PARSE_STRING']['__IMG__']  = __ROOT__ . '/' . APP_PATH . MODULE_NAME . '/View/Public/img';
        $oc_config['TMPL_PARSE_STRING']['__CSS__']  = __ROOT__ . '/' . APP_PATH . MODULE_NAME . '/View/Public/css';
        $oc_config['TMPL_PARSE_STRING']['__JS__']   = __ROOT__ . '/' . APP_PATH . MODULE_NAME . '/View/Public/js';
        $oc_config['TMPL_PARSE_STRING']['__LIBS__'] = __ROOT__ . '/' . APP_PATH . MODULE_NAME . '/View/Public/libs';

        // 获取当前主题的名称
        $current_theme = D('Admin/Theme')->where(array('current' => 1))->order('id asc')->getField('name');
        if ($current_theme) {
            // 前台Home模块静态资源路径及模板继承基本模板
            $home_public_path = './Theme/' . $current_theme . '/Home/Public';
            if (is_dir($home_public_path)) {
                $oc_config['HOME_PUBLIC_LAYOUT']                 = $home_public_path . '/layout.html';
                $oc_config['TMPL_PARSE_STRING']['__HOME_IMG__']  = __ROOT__ . '/' . $home_public_path . '/img';
                $oc_config['TMPL_PARSE_STRING']['__HOME_CSS__']  = __ROOT__ . '/' . $home_public_path . '/css';
                $oc_config['TMPL_PARSE_STRING']['__HOME_JS__']   = __ROOT__ . '/' . $home_public_path . '/js';
                $oc_config['TMPL_PARSE_STRING']['__HOME_LIBS__'] = __ROOT__ . '/' . $home_public_path . '/libs';
            }

            // 如果当前主题存在User模板则改变相关配置
            if (is_dir('./Theme/' . $current_theme . '/User')) {
                $oc_config['USER_CENTER_SIDE'] = './Theme/' . $current_theme . '/User/Index/side.html';
                $oc_config['USER_CENTER_FORM'] = './Theme/' . $current_theme . '/User/Builder/form.html';
                $oc_config['USER_CENTER_LIST'] = './Theme/' . $current_theme . '/User/Builder/list.html';
                $oc_config['USER_LOGIN_MODAL'] = './Theme/' . $current_theme . '/User/User/login_modal.html';
            }

            // 当前主题
            $current_theme_path = './Theme/' . $current_theme . '/' . MODULE_NAME . '/'; //当前主题文件夹路径
            if (is_dir($current_theme_path)) {
                if (MODULE_MARK === 'Home') {
                    $oc_config['VIEW_PATH'] = $current_theme_path;
                }
                $oc_config['CURRENT_THEME'] = $current_theme; //默认主题设为当前主题

                // 各模块自带静态资源路径
                $module_public_path = './Theme/' . $current_theme . '/' . MODULE_NAME . '/Public';
                if (is_dir($module_public_path)) {
                    $oc_config['TMPL_PARSE_STRING']['__IMG__']  = __ROOT__ . '/' . $module_public_path . '/img';
                    $oc_config['TMPL_PARSE_STRING']['__CSS__']  = __ROOT__ . '/' . $module_public_path . '/css';
                    $oc_config['TMPL_PARSE_STRING']['__JS__']   = __ROOT__ . '/' . $module_public_path . '/js';
                    $oc_config['TMPL_PARSE_STRING']['__LIBS__'] = __ROOT__ . '/' . $module_public_path . '/libs';
                }
            }
        }
        C($oc_config); // 添加配置
        ////////////////////
        //添加配置 end
        ////////////////////

        // 读取数据库中的配置
        $system_config = S('DB_CONFIG_DATA');
        if (!$system_config || APP_DEBUG === true) {
            // 获取所有系统配置
            $system_config = D('Admin/Config')->lists();

            // SESSION与COOKIE与前缀设置避免冲突
            $system_config['SESSION_PREFIX'] = strtolower(ENV_PRE.MODULE_MARK.'_');  // Session前缀
            $system_config['COOKIE_PREFIX']  = strtolower(ENV_PRE.MODULE_MARK.'_');  // Cookie前缀

            // Session数据表
            $system_config['SESSION_TABLE'] = C('DB_PREFIX').'admin_session';

            // 获取所有安装的模块配置
            $module_list = D('Admin/Module')->where(array('status' => '1'))->select();
            foreach ($module_list as $val) {
                $module_config[strtolower($val['name'].'_config')] = json_decode($val['config'], true);
                $module_config[strtolower($val['name'].'_config')]['module_name'] = $val['name'];
            }
            if ($module_config) {
                // 合并模块配置
                $system_config = array_merge($system_config, $module_config);

                // 加载模块标签库及行为扩展
                $system_config['TAGLIB_PRE_LOAD'] = explode(',', C('TAGLIB_PRE_LOAD'));  // 先取出配置文件中定义的否则会被覆盖
                foreach ($module_config as $key => $val) {
                    // 加载模块标签库
                    if (isset($val['taglib'])) {
                        foreach ($val['taglib'] as $tag) {
                            $tag_path = APP_PATH.$val['module_name'].'/'.'TagLib'.'/'.$tag.'.class.php';
                            if (is_file($tag_path)) {
                                $system_config['TAGLIB_PRE_LOAD'][] = $val['module_name'].'\\TagLib\\'.$tag;
                            }
                        }
                    }

                    // 加载模块行为扩展
                    if (isset($val['behavior'])) {
                        foreach ($val['behavior'] as $bhv) {
                            $bhv_path = APP_PATH.$val['module_name'].'/'.'Behavior'.'/'.$bhv.'Behavior.class.php';
                            if (is_file($bhv_path)) {
                                \Think\Hook::add('corethink_behavior', $val['module_name'].'\\Behavior\\'.$bhv.'Behavior');
                            }
                        }
                    }
                }
                $system_config['TAGLIB_PRE_LOAD'] = implode(',', $system_config['TAGLIB_PRE_LOAD']);
            }

            S('DB_CONFIG_DATA', $system_config, 3600);  // 缓存配置
        }

        // 系统主页地址配置
        $system_config['TOP_HOME_DOMAIN'] = (is_ssl()?'https://':'http://') . $_SERVER['HTTP_HOST'];
        $system_config['HOME_DOMAIN']   = (is_ssl()?'https://':'http://') . $_SERVER['HTTP_HOST'];
        $system_config['HOME_PAGE']     = $system_config['HOME_DOMAIN'] . __ROOT__;
        $system_config['TOP_HOME_PAGE'] = $system_config['TOP_HOME_DOMAIN'] . __ROOT__;

        // 如果是后台并且不是Admin模块则设置默认控制器层为Admin
        if (MODULE_MARK === 'Admin' && MODULE_NAME !== 'Admin') {
            $system_config['DEFAULT_C_LAYER'] = 'Admin';
            $system_config['VIEW_PATH'] = APP_PATH.MODULE_NAME.'/View/Admin/';
        }

        // 静态资源域名
        $current_domain = $system_config['TOP_HOME_PAGE'];
        $system_config['CURRENT_DOMAIN'] = $current_domain;

        // 模版参数配置
        $system_config['TMPL_PARSE_STRING'] = C('TMPL_PARSE_STRING');  // 先取出配置文件中定义的否则会被覆盖
        $system_config['TMPL_PARSE_STRING']['__IMG__']    = $current_domain.'/'.APP_PATH.MODULE_NAME.'/View/Public/img';
        $system_config['TMPL_PARSE_STRING']['__CSS__']    = $current_domain.'/'.APP_PATH.MODULE_NAME.'/View/Public/css';
        $system_config['TMPL_PARSE_STRING']['__JS__']     = $current_domain.'/'.APP_PATH.MODULE_NAME.'/View/Public/js';
        $system_config['TMPL_PARSE_STRING']['__LIBS__']   = $current_domain.'/'.APP_PATH.MODULE_NAME.'/View/Public/libs';

        C($system_config);  // 添加配置
    }
}
