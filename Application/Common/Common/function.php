<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------

require_once APP_PATH . 'Common/Common/developer.php'; //加载开发者二次开发公共函数库

/**
 * 根据配置类型解析配置
 * @param $value
 * @param null $type
 * @return array
 */
function parse_attr($value, $type = null)
{
    switch ($type) {
        default: //解析"1:1\r\n2:3"格式字符串为数组
            $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
            if (strpos($value, ':')) {
                $value = array();
                foreach ($array as $val) {
                    list($k, $v) = explode(':', $val);
                    $value[$k]   = $v;
                }
            } else {
                $value = $array;
            }
            break;
    }
    return $value;
}

/**
 * 处理插件钩子
 * @param string $hook   钩子名称
 * @param mixed $params 传入参数
 * @return void
 * @author jry <598821125@qq.com>
 */
function hook($hook, $params = array())
{
    $result = \Think\Hook::listen($hook, $params);
}

/**
 * 获取插件类的类名
 * @param string $name 插件名
 * @return string
 * @author jry <598821125@qq.com>
 */
function get_addon_class($name)
{
    $class = "Addons\\{$name}\\{$name}Addon";
    return $class;
}

/**
 * 插件显示内容里生成访问插件的url
 * @param string $url url
 * @param array $param 参数
 * @author jry <598821125@qq.com>
 */
function addons_url($url, $param = array())
{
    return D('Admin/Addon')->getAddonUrl($url, $param);
}

/**
 * 兼容Nginx
 * @return array
 * @author jry <598821125@qq.com>
 */
if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

/**
 * POST数据提前处理
 * @return array
 * @author jry <598821125@qq.com>
 */
function format_data($data = null)
{
    //解析数据类似复选框类型的数组型值
    if (!$data) {
        $data = $_POST;
    }
    $data_object = new \Common\Util\Think\Date;
    foreach ($data as $key => $val) {
        if (!is_array($val)) {
            $val = trim($val);
            if ($data_object->checkDatetime($val)) {
                $data[$key] = strtotime($val);
            } else if ($data_object->checkDatetime($val, 'Y-m-d H:i')) {
                $data[$key] = strtotime($val);
            } else if ($data_object->checkDatetime($val, 'Y-m-d')) {
                $data[$key] = strtotime($val);
            } else {
                $data[$key] = $val;
            }
        } else {
            $data[$key] = implode(',', $val);
        }
    }
    return $data;
}

/**
 * 获取所有数据并转换成一维数组
 * @author jry <598821125@qq.com>
 */
function select_list_as_tree($model, $map = null, $extra = null, $key = 'id')
{
    //获取列表
    $con['status'] = array('eq', 1);
    if ($map) {
        $con = array_merge($con, $map);
    }
    $model_object = D($model);
    if (in_array('sort', $model_object->getDbFields())) {
        $list = $model_object->where($con)->order('sort asc, id asc')->select();
    } else {
        $list = $model_object->where($con)->order('id asc')->select();
    }
    //转换成树状列表(非严格模式)
    $tree = new \Common\Util\Tree();
    $list = $tree->toFormatTree($list, 'title', 'id', 'pid', 0, false);

    if ($extra) {
        $result[0] = $extra;
    }

    //转换成一维数组
    foreach ($list as $val) {
        $result[$val[$key]] = $val['title_show'];
    }
    return $result;
}

/**
 * 解析文档内容
 * @param string $str 待解析内容
 * @return string
 * @author jry <598821125@qq.com>
 */
function parse_content($str)
{
    // 将img标签的src改为lazy-src用户前台图片lazyload加载
    if (C('STATIC_DOMAIN')) {
        $tmp = preg_replace('/<img.*?src="(.*?Uploads.*?)"(.*?)>/i', "<img class='lazy lazy-fadein img-responsive' style='display:inline-block;' data-src='" . C('STATIC_DOMAIN') . "$1'>", $str);
        $tmp = preg_replace('/<img.*?src="(\/.*?)"(.*?)>/i', "<img class='img-responsive' style='display:inline-block;' src='" . C('STATIC_DOMAIN') . "$1'>", $tmp);
    } else {
        $domain = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
        $tmp    = preg_replace('/<img.*?src="(.*?Uploads.*?)"(.*?)>/i', "<img class='lazy lazy-fadein img-responsive' style='display:inline-block;' data-src='" . $domain . "$1'>", $str);
        $tmp    = preg_replace('/<img.*?src="(\/.*?)"(.*?)>/i', "<img class='img-responsive' style='display:inline-block;' src='" . $domain . "$1'>", $tmp);
    }
    return $tmp;
}

/**
 * 字符串截取(中文按2个字符数计算)，支持中文和其他编码
 * @static
 * @access public
 * @param str $str 需要转换的字符串
 * @param str $start 开始位置
 * @param str $length 截取长度
 * @param str $charset 编码格式
 * @param str $suffix 截断显示字符
 * @return str
 */
function cut_str($str, $start, $length, $charset = 'utf-8', $suffix = true)
{
    return \Common\Util\Think\Str::cutStr(
        $str, $start, $length, $charset, $suffix
    );
}

/**
 * 过滤标签，输出纯文本
 * @param string $str 文本内容
 * @return string 处理后内容
 * @author jry <598821125@qq.com>
 */
function html2text($str)
{
    return \Common\Util\Think\Str::html2text($str);
}

/**
 * 友好的时间显示
 * @param int    $sTime 待显示的时间
 * @param string $type  类型. normal | mohu | full | ymd | other
 * @param string $alt   已失效
 * @return string
 * @author jry <598821125@qq.com>
 */
function friendly_date($sTime, $type = 'mohu', $alt = 'false')
{
    $date = new \Common\Util\Think\Date((int) $sTime);
    return $date->friendlyDate($type, $alt);
}

/**
 * 用于生成插入datetime类型字段用的字符串
 * @param string $str 支持偏移字符串
 */
function datetime($str = 'now', $format="Y-m-d H:i:s")
{
    return @date($format, strtotime($str));
}

/**
 * 时间戳格式化
 * @param int $time
 * @return string 完整的时间显示
 * @author jry <598821125@qq.com>
 */
function time_format($time = null, $format = 'Y-m-d H:i')
{
    $time = $time === null ? time() : intval($time);
    return date($format, $time);
}

/**
 * 系统非常规MD5加密方法
 * @param  string $str 要加密的字符串
 * @return string
 * @author jry <598821125@qq.com>
 */
function user_md5($str, $auth_key = '')
{
    if (!$auth_key) {
        $auth_key = C('AUTH_KEY') ?: 'OpenCMF';
    }
    return '' === $str ? '' : md5(sha1($str) . $auth_key);
}

/**
 * 检测用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 * @author jry <598821125@qq.com>
 */
function is_login()
{
    return D('Admin/User')->is_login();
}

/**
 * 检测用户是否VIP
 * @return integer VIP等级
 * @author jry <598821125@qq.com>
 */
function is_vip($uid)
{
    if (D('Admin/Module')->where('name="Vip" and status="1"')->count()) {
        $uid = $uid ? $uid : is_login();
        return D('Vip/Index')->isVip($uid);
    }
    return false;
}

/**
 * 获取上传文件路径
 * @param  int $id 文件ID
 * @return string
 * @author jry <598821125@qq.com>
 */
function get_cover($id = null, $type = null)
{
    return D('Admin/Upload')->getCover($id, $type);
}

/**
 * 自动生成URL，支持在后台生成前台链接
 * @param string $url URL表达式，格式：'[模块/控制器/操作#锚点@域名]?参数1=值1&参数2=值2...'
 * @param string|array $vars 传入的参数，支持数组和字符串
 * @param string|boolean $suffix 伪静态后缀，默认为true表示获取配置值
 * @param boolean $domain 是否显示域名
 * @return string
 * @author jry <598821125@qq.com>
 */
function oc_url($url = '', $vars = '', $suffix = true, $domain = true)
{
    $url = U($url, $vars, $suffix, $domain);
    if (MODULE_MARK === 'Admin') {
        $url_model = D('Admin/Config')->where(array('name' => 'URL_MODEL'))->getField('value');
        switch ($url_model) {
            case '1':
                $result = strtr($url, array('admin.php?s=' => 'index.php'));
                break;
            case '2':
                $result = strtr($url, array('admin.php?s=/' => ''));
                break;
            case '3':
                $result = strtr($url, array('admin.php' => 'index.php'));
                break;
            default:
                $result = strtr($url, array('admin.php' => 'index.php'));
                break;
        }
        return $result;
    } else {
        return $url;
    }
}


