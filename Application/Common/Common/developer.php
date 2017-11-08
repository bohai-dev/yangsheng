<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------

//开发者二次开发公共函数统一写入此文件，不要修改function.php以便于系统升级。


define('CURRENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']);

define('DOMAIN', strstr(CURRENT_URL, _PHP_FILE_, true));

function is_admin()
{
    return session('user_auth.uid') == 1;
}

/**
 * 保存推荐用户id
 * @param int $recUid
 */
function saveRecUser($recUid)
{
    $historyRecUid = cookie('recUid') ?: '';

    $userExist = M('admin_user')->where(['id' => $recUid])->field('id')->find();

    if (!$historyRecUid) {
        if ($userExist) {
            cookie('recUid', $recUid, time() + 86400 * 365);
        }
    } else {
        if ($recUid != $historyRecUid) {
            $historyUserExist = M('admin_user')->where(['id' => $historyRecUid])->field('id')->find();
            if (!$historyUserExist && $userExist) {
                cookie('recUid', $recUid, time() + 86400 * 365);
            }
        } else {
            cookie('recUid', $recUid, time() + 86400 * 365);
        }
    }
}

// 保存推广链接urlUid
function saveURLUser($urlUid, $dbname='admin_user')
{
    $userExist = M($dbname)->where(['id' => $urlUid])->field('id')->find();
    if ($userExist) {
        cookie('urlUid', $urlUid, time() + 86400 * 365);
    } else {
        // 无效推广链接人，清空旧cookie
        if (cookie('urlUid') == $urlUid) {
            cookie('urlUid', null);
        }
    }
}

//获取当前月到当天的所有日期
function getMonthDays($diffDays = 30, $start = NOW_TIME)
{
    if (!$diffDays) {
        $dateClass             = new \Common\Util\Think\Date();
        $lastDayOfMonth        = $dateClass->lastDayOfMonth();
        $lastDayOfMonth_string = $lastDayOfMonth->format();
        $dateClass2            = new \Common\Util\Think\Date();
        $diffDays              = ceil($dateClass2->dateDiff($lastDayOfMonth_string));
    }
    $start = date('Y-m-d', $start);
    $end   = date('Y-m-d', strtotime("+{$diffDays} day"));

    // 获取start 和end 之间的日期数组
    $xAxis    = [];
    $start    = new DateTime($start);
    $interval = new DateInterval('P1D');
    $end      = new DateTime($end);
    $period   = new DatePeriod($start, $interval, $end->modify('+1 day'));
    foreach ($period as $date) {
        $xAxis[] = [
            $date->format('Y-m-d'),
            $date->format('w'),
            $date->format('d'),
        ];
    }
    return $xAxis;
}

// 将mysql里 time 类型的字符串格式化为 24小时 分钟格式
function getTimeByTime($time)
{
    return date('H:i', strtotime($time));
}

// 获取星期几 根据周几代号
function getChineseWeekDay($weekday)
{
    static $weekdays = [
        '日',
        '一',
        '二',
        '三',
        '四',
        '五',
        '六',
    ];
    return $weekdays[$weekday];
}

function buildDate($str)
{
    return $str . ' 00:00:00';
}

/**
 * 日期格式化
 * @param  string $format 格式
 * @param  string $date   日期 可不填
 * @return string         格式化后的日期
 */
function datetime_formart($format, $date = '')
{
    if ($date) {
        return date($format, strtotime($date));
    } else {
        return date($format);
    }
}

/**
 * 是否微信访问
 * @return bool
 * @author jry <598821125@qq.com>
 */
function is_weixin()
{
    if (cookie('openid')) {
        return true;
    }
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * 是否手机访问
 * @return bool
 * @author jry <598821125@qq.com>
 */
function is_wap()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset($_SERVER['HTTP_VIA'])) {
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile',
        );
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    // 协议法，因为有可能不准确，放到最后判断
    if (isset($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}

/**
 * 获取订单号
 *
 * @return string 19 位时间戳组成的唯一码
 */
function getOrderNo()
{
    return date('YmdHis', NOW_TIME) . mt_rand(10000, 99999);
}
/**
 * 获取网站域名
 */
function XILUDomain()
{
    $server = $_SERVER['HTTP_HOST'];
    $http   = is_ssl() ? 'https://' : 'http://';
    return $http . $server;
}

// 获得随机字符串
function get_rand_str($length = 6)
{
    $chars     = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $chars_len = strlen($chars);
    $randstr   = '';
    for ($i = 0; $i < $length; $i++) {
        $randstr .= substr($chars, mt_rand(0, $chars_len - 1), 1);
    }
    return $randstr;
}

/**
 * 用于生成唯一密码（加密方式md5）
 *
 * @param
 *            len int 密码长度 默认8
 * @param
 *            pattern string 密码字符的范围 第一个是阿拉伯数字 开关 第二个是小写字母 第三个大写字母 第四个是特殊字符 如果 都用到就是1111 至少要有一个1
 */
function randstr($len = 8, $pattern = '1000')
{
    static $seed = array(
        '0123456789',
        'abcdefghijklmnopqrstuvwxyz',
        'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        '+=-@#~,.[]()!%^*$',
    );
    if (!is_string($pattern) || strpos('1', $pattern) == -1 || strlen($pattern) > count($seed)) {
        send_error('', '生成随机字符传模式格式有误');
    }

    $pattern = str_split($pattern);
    $randStr = '';
    foreach ($pattern as $key => $value) {
        if ($value == '1') {
            $randStr .= $seed[$key];
        }
    }
    // echo $randStr;
    $temppass = array_fill(0, $len, '');
    if (!function_exists('getChar')) {
        function getChar(&$value, $key, $seed)
        {
            $value = $seed[mt_rand(0, strlen($seed) - 1)];
        }
    }
    array_walk($temppass, 'getChar', $randStr);
    $temppass = implode('', $temppass);
    return $temppass;
}

// 生成在数据库中不重复的唯一键
function create_unique_db_code($table, $key, $prefix = '', $total_length = 6)
{
    $prefix_len = strlen($prefix);
    if ($prefix_len >= $total_length - 4) {
        // warn('随机token，前缀长度超过 总长度-4，前缀长度：' . $prefix_len . '|总长度：' . $total_length);
        return false;
    }
    for ($i = 0; $i < 10; $i++) {
        $token = get_rand_str($total_length - $prefix_len);
        if (!$repeat = db_find($table, '*', "`{$key}`='{$prefix}{$token}'")) {
            $flag = $prefix . $token;
            break;
        }
    }
    return $flag;
}

/**
 * 在数据列表中搜索
 *
 * @access public
 * @param array $list
 *            数据列表
 * @param mixed $condition
 *            查询条件
 *            支持 array('name'=>$value) 或者 name=$value
 * @return array
 */
function list_search($list, $condition)
{
    if (is_string($condition)) {
        parse_str($condition, $condition);
    }

    // 返回的结果集合
    $resultSet = array();
    foreach ($list as $key => $data) {
        $find = false;
        foreach ($condition as $field => $value) {
            if (isset($data[$field])) {
                if (0 === strpos($value, '/')) {
                    $find = preg_match($value, $data[$field]);
                } elseif ($data[$field] == $value) {
                    $find = true;
                }
            }
        }
        if ($find) {
            $resultSet[] = &$list[$key];
        }
    }
    return $resultSet;
}

/**
 * 对查询结果集进行排序
 *
 * @access public
 * @param array $list
 *            查询结果
 * @param string $field
 *            排序的字段名
 * @param array $sortby
 *            排序类型
 *            asc正向排序 desc逆向排序 nat自然排序
 * @return array
 */
function list_sort_by($list, $field, $sortby = 'asc')
{
    if (is_array($list)) {
        $refer = $resultSet = array();
        foreach ($list as $i => $data) {
            $refer[$i] = &$data[$field];
        }

        switch ($sortby) {
            case 'asc': // 正向排序
                asort($refer);
                break;
            case 'desc': // 逆向排序
                arsort($refer);
                break;
            case 'nat': // 自然排序
                natcasesort($refer);
                break;
        }
        foreach ($refer as $key => $val) {
            $resultSet[] = &$list[$key];
        }

        return $resultSet;
    }
    return false;
}

function http($url, $param, $data = '', $method = 'GET')
{
    $opts = array(
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    );

    /* 根据请求类型设置特定参数 */
    $opts[CURLOPT_URL] = $url . '?' . http_build_query($param);

    if (strtoupper($method) == 'POST') {
        $opts[CURLOPT_POST]       = 1;
        $opts[CURLOPT_POSTFIELDS] = $data;

        if (is_string($data)) {
            // 发送JSON数据
            $opts[CURLOPT_HTTPHEADER] = array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($data),
            );
        }
    }

    /* 初始化并执行curl请求 */
    $ch = curl_init();
    curl_setopt_array($ch, $opts);
    $data  = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    // 发生错误，抛出异常
    if ($error) {
        ptrace($data);
        throw new \Exception('请求发生错误：' . $error);
    }

    return $data;
}

/*
 * 获取图片
 * $pics图片ID
 * $type ：cover获取第一张，all获取所有
 */
function getpics($pics, $type = 'cover')
{
    $arrpic = explode(',', $pics);
    if ($type == 'cover') {
        return get_cover($arrpic[0], 'default');
    } else {
        $get_cover = function ($id) {
            return get_cover($id, 'default');
        };
        $l = array_map($get_cover, $arrpic);
        return $l;
    }
}

// 生成或者获取二维码
function getWxQrcode($type, $type_id)
{
    $expire_seconds = 0;
    $model_object   = M('wxqrcode');
    $exist          = $model_object->where([
        'type'      => $type,
        'relate_id' => $type_id,
    ])->find();
    // 存在返回 已有二维码地址
    if ($exist) {
        return [
            'url'         => $exist['url'],
            'expire_time' => $exist['expire_time'],
            'status'      => $exist['status'],
        ];
    }
    switch ($type) {
        case 'admin_user':
            $user = M('admin_user')->find($type_id);
            if (!$user) {
                return [
                    'status' => 0,
                    'info'   => "用户表数据不存在",
                ];
            }
            break;
        default:
            pubu("错误的二维码获取类型 type:{$type}");
            return [
                'status' => 0,
                'info'   => "错误的二维码获取类型 type:{$type}",
            ];
            break;
    }
    // ptrace($type);
    $code = $model_object->max('id') + 1;
    $res  = front_r('Home/Weixin/qrcode', [
        $code,
        $expire_seconds,
    ]);
    // ptrace($res);
    if ($res['status']) {
        $insertData = [
            'id'          => $code,
            'type'        => $type,
            'relate_id'   => $type_id,
            'status'      => 0,
            'url'         => $res['data']['url'],
            'create_time' => datetime(),
            'expire_time' => $expire_seconds == 0 ? null : time_format(time() + $expire_seconds),
        ];
        $model_object->add($insertData);
        $res = [
            'status'      => 1,
            'url'         => $res['data']['url'],
            'expire_time' => $insertData['expire_time'],
        ];
    } else {
        ptrace($res);
    }
    return $res;
}

/**
 * 调试到瀑布使用
 */
function ptrace($msg)
{
    return pubu(is_string($msg) ? $msg : '`' . var_export($msg, true) . '`', 'debug');
}

// pubu日志
function pubu($text, $channel = 'normal', $name = '', $avatarUrl = '', $attachments = array())
{
    $urls = array(
        'normal' => 'https://hooks.pubu.im/services/14k9bqwe8bbksgm',
        'debug'  => 'https://hooks.pubu.im/services/14k9bqwe8bbksgm',
        'all'    => 'https://hooks.pubu.im/services/14k9bqwe8bbksgm',
    );
    if (!isset($urls[$channel])) {
        return false;
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_URL, $urls[$channel]);
    // if (is_devmode()) {
    // $text = '[' . $channel . ']' . $text;
    // $channel = 'devlog';
    // }
    $post_data = array(
        'text'        => C('WEB_SITE_TITLE') . PHP_EOL . date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']) . ' ' . $_SERVER['SERVER_PROTOCOL'] . ' ' . $_SERVER['REQUEST_METHOD'] . ' : ' . __SELF__ . PHP_EOL . $text,
        'displayUser' => array(
            'name'      => $name,
            'avatarUrl' => $avatarUrl,
        ),
    );
    // if (!empty($attachments)) {
    //     foreach ($attachments as $key => $attachment) {
    //         $post_data['file'][] = "@{$attachment['url']}";
    //     }
    // } else {
    //     // $post_data = @http_build_query($post_data);
    // }
    @curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $result = curl_exec($ch);
    // var_dump($result);
    if ($error = curl_error($ch)) {
        // var_dump($error);
        trace($error);
        return false;
    }
    return true;
}

//获取头像
function get_front_avatar($user)
{
    $return = $user['avatar'] ? get_cover($user['avatar'], 'avatar') : $user['headimgurl'];
    return $return ?: get_cover(0, 'avatar');
}

// 获取昵称
function get_front_name($user)
{
    return isset($user['cnname']) && !empty($user['cnname']) ? $user['cnname'] : $user['nickname'];
}

// 根据admin_uid 获取昵称
function get_username($uid)
{
    return M('admin_user')->where(['id' => $uid])->getField('nickname') ?: '未查到';
}

// 获取logo
function get_logo()
{
    return get_cover(C('Shoppingmall_config.logo'), 'default');
}

//datetime转date显示
function dttodate($date)
{
    $return = substr($date, 0, 10);
    return $return;
}

// datetime 转时间显示
function dttotime($date)
{
    $return = substr($date, 11);
    return $return;
}



/**
 * select返回的数组进行整数映射转换
 *
 * @param array $map  映射关系二维数组  array(
 *                                          '字段名1'=>array(映射关系数组),
 *                                          '字段名2'=>array(映射关系数组),
 *                                           ......
 *                                       )
 * @author 朱亚杰 <zhuyajie@topthink.net>
 * @return array
 *
 *  array(
 *      array('id'=>1,'title'=>'标题','status'=>'1','status_text'=>'正常')
 *      ....
 *  )
 *
 */
function int_to_string(&$data, $map = array('status' => array(1 => '正常', -1 => '删除', 0 => '禁用', 2 => '未审核', 3 => '草稿')))
{
    if ($data === false || $data === null) {
        return $data;
    }
    $data = (array) $data;
    foreach ($data as $key => $row) {
        foreach ($map as $col => $pair) {
            if (isset($row[$col]) && isset($pair[$row[$col]])) {
                $data[$key][$col . '_text'] = $pair[$row[$col]];
            }
        }
    }
    return $data;
}

/**
 * 地域上级导航
 */
function get_area_nav()
{
    $area_nav = D('Area')->where(['parentid' => '0'])->field('id,name,name as title, parentid,child,arrchildid,keyid,sort')->select();
    slog($area_nav);
    if (!empty($area_nav)) {
        foreach ($area_nav as $key => $val) {
            $area_nav[$val['id']] = $val['name'];
        }
        unset($area_nav[0]);
    }

    return $area_nav;
}

/**
 * 多个数组的笛卡尔积
 *
 * @param unknown_type $data
 */
function combineDika()
{
    $data   = func_get_args();
    $data   = current($data);
    $cnt    = count($data);
    $result = array();
    $arr1   = array_shift($data);
    foreach ($arr1 as $key => $item) {
        $result[] = array($item);
    }

    foreach ($data as $key => $item) {
        $result = combineArray($result, $item);
    }
    return $result;
}

/**
 * 两个数组的笛卡尔积
 * @param unknown_type $arr1
 * @param unknown_type $arr2
 */
function combineArray($arr1, $arr2)
{
    $result = array();
    foreach ($arr1 as $item1) {
        foreach ($arr2 as $item2) {
            $temp     = $item1;
            $temp[]   = $item2;
            $result[] = $temp;
        }
    }
    return $result;
}

/**
 * 刷新商品库存, 如果商品有设置规格库存, 则商品总库存 等于 所有规格库存相加
 * @param type $goods_id  商品id
 */
function refresh_stock($goods_id)
{
    $count = M("SpecGoodsPrice")->where("goods_id = $goods_id")->count();
    if ($count == 0) {
        return false;
    }
    // 没有使用规格方式 没必要更改总库存

    $store_count = M("SpecGoodsPrice")->where("goods_id = $goods_id")->sum('store_count');
    // M("ShoppingmallGoods")->where("id = $goods_id")->save(array('store_count' => $store_count)); // 更新商品的总库存
}

/* 参数解释
$string： 明文 或 密文
$operation：DECODE表示解密,其它表示加密
$key： 密匙
$expiry：密文有效期
 */
if (!function_exists('AuthCode')) {
    function AuthCode($string, $operation = 'DECODE', $key = '', $expiry = 0)
    {
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
        // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
        // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
        // 当此值为 0 时，则不产生随机密钥
        $ckey_length = 4;
        // 密匙
        $key = md5($key ? $key : C('AUTH_KEY'));
        // 密匙a会参与加解密
        $keya = md5(substr($key, 0, 16));
        // 密匙b会用来做数据完整性验证
        $keyb = md5(substr($key, 16, 16));
        // 密匙c用于变化生成的密文
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
        // 参与运算的密匙
        $cryptkey   = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);
        // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
        // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
        $string        = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);
        $result        = '';
        $box           = range(0, 255);
        $rndkey        = array();

        // 产生密匙簿
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上并不会增加密文的强度
        for ($j = $i = 0; $i < 256; $i++) {
            //$j是三个数相加与256取余
            $j       = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp     = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        // 核心加解密部分
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            //在上面基础上再加1 然后和256取余
            $a       = ($a + 1) % 256;
            $j       = ($j + $box[$a]) % 256; //$j加$box[$a]的值 再和256取余
            $tmp     = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符，加密和解决时($box[($box[$a] + $box[$j]) % 256])的值是不变的。
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if ($operation == 'DECODE') {
            // substr($result, 0, 10) == 0 验证数据有效性
            // substr($result, 0, 10) - time() > 0 验证数据有效性
            // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
            // 验证数据有效性，请看未加密明文的格式
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
            // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
            return $keyc . str_replace('=', '', base64_encode($result));
        }
    }
}

/**
 * 验证手机格式
 * @param string $string
 * @return boolean
 */
function isMobileFormat($string)
{
    $pattern = '/^(0|86|17951)?(13[0-9]|15[012356789]|1[78][0-9]|14[57])[0-9]{8}$/';
    return preg_match($pattern, $string);
}

// 通过area表拼接city-picker
function getAreaList($parentid = 0)
{
    $area        = [];
    $model       = M('area');
    $first_level = $model->where(['child' => 1, 'parentid' => $parentid, 'status' => 1])->order('sort asc,id asc')->select();
    foreach ($first_level as $key => $first) {
        $item = [
            'name' => $first['name'],
            'code' => $first['id'],
        ];
        $sub = getSubArea($first['id']) ?: [];
        if ($sub) {
            $item['sub'] = $sub;
        }
        $area[] = $item;

    }

    return $area;
}

function getSubArea($parentid = 0)
{
    $ret         = [];
    $first_level = M('area')->where(['parentid' => $parentid, 'status' => 1])->order('sort asc,id asc')->select();
    foreach ($first_level as $key => $first) {
        $item = [
            'name' => $first['name'],
            'code' => $first['id'],
        ];
        $sub = getSubArea($first['id']) ?: [];
        if ($sub) {
            $item['sub'] = $sub;
        }
        $ret[] = $item;
    }
    return $ret;
}


//发送短信 容联云
function sendTemplateSMS($to, $datas, $tempId)
{
    $data = R('Addons://YuntxSms/YuntxSms/sendSms', [$to, $datas, $tempId]);
    return $data;
}

function sendTemplateMiaoSMS($body){

    $data = R('Addons://MiaoDi/Miao/sendSms', [$body]);
    return $data;
}

// 获取订单的平台
function get_platform()
{
    if (is_weixin()) {
        return 'weixin';
    } else if (is_wap()) {
        return 'app';
    } else {
        return 'pc';
    }
}

// 获取本周的 周几， 传周几的英文简写 如周一 为 mon 或1
function get_current_weekday($weekday = 'mon', $format = 'Y-m-d')
{
    $week          = date('w');
    $short_weekday = ['', 'mon', 'tue', 'web', 'thu', 'fri', 'sat', 'sun'];
    if (is_numeric($weekday)) {
        $weekday = $short_weekday[$weekday];
    }
    $weeks['mon'] = date($format, strtotime('+' . 1 - $week . ' days'));
    $weeks['tue'] = date($format, strtotime('+' . 2 - $week . ' days'));
    $weeks['wed'] = date($format, strtotime('+' . 3 - $week . ' days'));
    $weeks['thu'] = date($format, strtotime('+' . 4 - $week . ' days'));
    $weeks['fri'] = date($format, strtotime('+' . 5 - $week . ' days'));
    $weeks['sat'] = date($format, strtotime('+' . 6 - $week . ' days'));
    $weeks['sun'] = date($format, strtotime('+' . 7 - $week . ' days'));
    return $weeks[$weekday];
}

/**
 * 获取当前页面完整URL地址
 */
function get_url()
{
    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    $php_self     = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    $path_info    = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    $relate_url   = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
    return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url;
}

function listarea($level = 0, $dataval = '', $debug = 0)
{
    $areas              = array();
    $v                  = isset($dataval) ? $dataval : '0';
    $where['datagroup'] = 'area';
    if ($level >= 0) {
        if ($v == 0) {
            $where['level'] = $level;
            //$sql .= " AND level='{$level}' ";
        } else if ($v % 500 == 0 && $v == 5000) {
            $where['level']     = $level;
            $where['datavalue'] = array('between', array($v, ($v + 499)));
        } else {
            $where['level']     = $level;
            $where['datavalue'] = array('like', $v . '%%%');
        }
    }
    $result = M('area')->where($where)->order('orderid ASC,datavalue ASC')->select();

    foreach ($result as $k => $v) {
        $areas[$v['datavalue']] = $v['dataname'];
    }
    return $areas;
}

//根据地址datavalue 查询  dataname
function listareabykey($dataval = '', $level = 0)
{
    $areas             = array();
    $dataval           = isset($dataval) ? $dataval : '0';
    $areas['dataname'] = '';
    $res               = M('area')->field('dataname')->where(array('datagroup' => 'area', 'datavalue' => $dataval))->select();
    foreach ($res as $k => $v) {
        $areas['dataname'] = $v['dataname'];
    }
    return $areas['dataname'];
}

/**
 * 腾讯地图坐标转百度地图坐标
 * @param [String] $lat 腾讯地图坐标的纬度
 * @param [String] $lng 腾讯地图坐标的经度
 * @return [Array] 返回记录纬度经度的数组
 */
function Convert_GCJ02_To_BD09($lat, $lng)
{
    $x_pi  = 3.14159265358979324 * 3000.0 / 180.0;
    $x     = $lng;
    $y     = $lat;
    $z     = sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $x_pi);
    $theta = atan2($y, $x) + 0.000003 * cos($x * $x_pi);
    $lng   = $z * cos($theta) + 0.0065;
    $lat   = $z * sin($theta) + 0.006;
    return array('lng' => $lng, 'lat' => $lat);
}

//百度地图坐标计算
function rad($d)
{
    return $d * 3.1415926535898 / 180.0;
}

/**
 * 计算腾讯坐标间距离
 * @param [String] $lat1 A点的纬度
 * @param [String] $lng1 A点的经度
 * @param [String] $lat2 B点的纬度
 * @param [String] $lng2 B点的经度
 * @return [String] 两点坐标间的距离，输出单位为米
 */
function getDistanceWithTencent($lat1, $lng1, $lat2, $lng2)
{
    $location1 = Convert_GCJ02_To_BD09($lat1, $lng1);
    $location2 = Convert_GCJ02_To_BD09($lat2, $lng2);
    return getDistance($location1['lat'], $location1['lng'], $location2['lat'], $location2['lng']);
}

/**
 * 腾讯地图坐标转百度地图坐标后计算距离
 * @param [String] $lat1 A点的纬度
 * @param [String] $lng1 A点的经度
 * @param [String] $lat2 B点的纬度
 * @param [String] $lng2 B点的经度
 * @return [String] 两点坐标间的距离，输出单位为米
 */
function getDistance($lat1, $lng1, $lat2, $lng2)
{
    $EARTH_RADIUS = 6378.137; //地球的半径
    $radLat1      = rad($lat1);
    $radLat2      = rad($lat2);
    $a            = $radLat1 - $radLat2;
    $b            = rad($lng1) - rad($lng2);
    $s            = 2 * asin(sqrt(pow(sin($a / 2), 2) +
        cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
    $s = $s * $EARTH_RADIUS;
    $s = round($s * 10000) / 10000;
    $s = $s * 1000;
    return ceil($s);
}

/**
 * 标记大概的距离，做出友好的距离提示
 * @param [$number] 距离数量
 * @return[String] 距离提示
 */
function mToKm($number)
{
    if (!is_numeric($number)) {
        return ' ';
    }

    switch ($number) {
        case $number > 1800 && $number <= 2000:
            $v = '2';
            break;
        case $number > 1500 && $number <= 1800:
            $v = '1.8';
            break;
        case $number > 1200 && $number <= 1500:
            $v = '1.5';
            break;
        case $number > 1000 && $number <= 1200:
            $v = '1.2';
            break;
        case $number > 900 && $number <= 1000:
            $v = '1';
            break;
        default:
            $v = ceil($number / 100) * 100;
            break;
    }

    if ($v < 100) {
        $v = '距离我【<font color="#FF4C06"><b>' . $v . '</b></font>】千米内。';} else {
        $v = '距离我【<font color="#FF4C06"><b>' . $v . '</b></font>】米内。';
    }
    return $v;

}

/**
 * 前台调用R方法 不影响后台中调用
 */
function front_r($url, $vars = array(), $layer = '')
{
    $default_layer = C('DEFAULT_C_LAYER');
    C('DEFAULT_C_LAYER', 'Controller');
    $res = R($url, $vars, $layer);
    C('DEFAULT_C_LAYER', $default_layer);
    return $res;
}

/**
 * 缩短显示后台列表字段
 */
function shorten_cloumn($text, $maxlen = 10)
{
    $show = cut_str($text, 0, $maxlen);
    return <<<HTML
<span title="{$text}">{$show}</span>
HTML;
}

/**
 * 根据地址获取经纬度
 */
function getGeo($address)
{
    // ptrace($address);
    //"http://apis.map.qq.com/ws/location/v1/ip?ip=$ip&output=json&key=O5BBZ-IUX65-YRBIB-QZRXG-LBFBT-A7FUR"
    $return = http('http://apis.map.qq.com/ws/geocoder/v1?address=' . $address, ['output' => 'json', 'key' => '5QABZ-ZXYKF-4B7JA-J2IDS-PZUDK-FJBAT']);
    $lng    = $lat    = 0;
    $return = json_decode($return);

    if (!$return->status) {
        $lng = $return->result->location->lng;
        $lat = $return->result->location->lat;
        return ['lng' => $lng, 'lat' => $lat, 'status' => 1];
    } else {
        return ['status' => 0];
    }
}

/*
 * 根据经纬度返回地址信息
 */
function getAddr($lat,$lng){
    $data = [
        'location'      => "$lat,$lng",
        'key'            =>'5QABZ-ZXYKF-4B7JA-J2IDS-PZUDK-FJBAT'
    ];
    $return = http("http://apis.map.qq.com/ws/geocoder/v1/",$data);
    $return = json_decode($return);
    if(!$return->status){
        $address = $return->result->address_component->city;
    }else{
        $address = '';
    }
    return $address;
}

/**
 * 可变化数量字段显示
 */
function op_field($val = 0, $min = 1, $url = '')
{
    $td = <<<TD
<a class="icon" title="minus" data-icon="fa-minus" data-filter="minus" data-url="{$url}" onclick="changeVal(this, 'minus', __data_id__)" href="javascript:;">
	<i class="fa fa-fw fa-minus fa-sm"></i>
</a>
<input type="num" data-min="{$min}" class="w30 text-center num" value="{$val}">
<a class="icon" title="plus" data-icon="fa-plus" data-filter="plus" data-url="{$url}" onclick="changeVal(this, 'add', __data_id__)" href="javascript:;">
	<i class="fa fa-fw fa-plus fa-sm"></i>
</a>
TD;
    return $td;
}

// 发送预约完成确认提示模板
function send_complete($appointment, $openid)
{
    $template_id = 'JE1GZc1BT09j3qwaDvzH6MCNonYxurkXTuXPoaqYCW4';
    $title       = $appointment['ordernum'] ? "您的美容预约已经服务完成,订单号#{$appointment['ordernum']}" : '您的美容预约已经服务完成';
    $data        = array(
        "first"    => $title,
        "keyword1" => $appointment['beauty_address'], // 订单号
        "keyword2" => datetime(), // 支付时间
        "remark"   => '点击详情去预约列表确认结束预约', //支付金额
    );

    $link = U('Shoppingmall/Appointment/index', ['from' => $appointment['from'], 'type' => $appointment['type']], true, true);
    return R('Home/Weixin/send_template', [$openid, $template_id, $data, $link]);
}

// 过滤Emoji表情
function filterEmoji($str)
{
    $str = preg_replace_callback(
        '/./u',
        function (array $match) {
            return strlen($match[0]) >= 4 ? '' : $match[0];
        },
        $str);
    return $str;
}

//二维数组关键字去重
function second_array_unique_bykey($arr, $key){
    $tmp_arr = array();
    foreach($arr as $k => $v)
    {
        if(in_array($v[$key], $tmp_arr))   //搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
        {
            unset($arr[$k]); //销毁一个变量  如果$tmp_arr中已存在相同的值就删除该值
        }
        else {
            $tmp_arr[$k] = $v[$key];  //将不同的值放在该数组中保存
        }
    }
    //ksort($arr); //ksort函数对数组进行排序(保留原键值key)  sort为不保留key值
    return $arr;
}

//记录访问次数
function pv_count($uid){
    $data = [
        'uid'=>$uid,
        'visit_time'=>datetime(),
    ];
    return M('pv_count')->add($data);
}

//微信分享
function XILUWechatShare($fromUid,$title = '', $desc = '', $imgUrl = '', $url = '') {
    //$referrerId = cookie('referrerId');
    if ($url) {
        $link = $url;
    } else {
        $link = XILUDomain() . __SELF__;
        /*if(strpos($url,"referrerId")){
            $link .= '&referrerId=' . $referrerId;
        }else{
            $link .= '?referrerId=' . $referrerId;
        }*/
        if (strstr($link, '?')) {
            $link .= '&fromuid=' . $fromUid;
        } else {
            $link .= '?fromuid=' . $fromUid;
        }
    }
    $wechatShare = array(
        'title' => C('wx_title'),
        'desc' => C('wx_desc'),
        'link' => $link,
        'imgUrl' => $imgUrl ? $imgUrl : XILUDomain() . '/' . getpics(C('wx_cover'),'cover'),
        'type' => 'link'
        /*'title' => $title ? $title : $GLOBALS['CONFIG']['webTitle'],
        'desc' => $desc ? $desc : $GLOBALS['CONFIG']['webDesc'],
        'link' => $link,
        'imgUrl' => $imgUrl ? $imgUrl : XILUDomain() . '/' . $GLOBALS['CONFIG']['webLogo'],
        'type' => 'link'*/
    );
    return $wechatShare;
}

function get_wx_nickname($uid){
    $user =M('user')->where(['admin_uid'=>$uid])->find();
    return $user['nickname'];
}


// 将树形列表转换为树 disabled pid=0 时 不可选
function list_as_tree($list, $extra = null, $key = 'id', $title_field = 'title',$disabled=false)
{
    //转换成树状列表(非严格模式)
    $tree = new \Common\Util\Tree();
    $list = $tree->toFormatTree($list, $title_field, 'id', 'pid', 0, false);

    if ($extra) {
        $result[0] = $extra;
    }

    //看情况转换成一维数组
    foreach ($list as $val) {
        if($disabled && $val['pid']==0){
            $result[$val[$key]] = ['title'=>$val['title_show'],'disabled'=>'disabled'];
        }else{
            $result[$val[$key]] = $val['title_show'];
        }
    }

    return $result;
}

// 获取论坛分类的名称
function get_type_name($id){
    $type_name =[];
    $id =explode(',',$id);
    foreach($id as $v){
        $name =M('forum_type')->where(['id'=>$v])->getField('title');
        if(!empty($name)){
            $type_name[]=$name ;
        }
    }
   /* $type_name = M('forum_type')->where(['id'=>$id])->getField('title');*/
    return $type_name? $type_name:'分类已删';
}
/**
 *  获取用户微信信息
 * @param $uid
 * @param $field
 */
function get_user_info($uid,$type,$field=''){
    if($type =='wx'){
        $table='user';
    }else{
        $table ='admin_user';
    }
    if(empty($field)){
        return M($table)->where(['id'=>$uid])->find();
    }else{
        return M($table)->where(['id'=>$uid])->getField($field);
    }
}

//返回格林威治标准时间
if (!function_exists('MyDate')) {

    function MyDate($format = 'Y-m-d H:i:s', $timest = 0) {
        global $cfg_timezone;

        $addtime = $cfg_timezone * 3600;
        if (empty($format))
            $format = 'Y-m-d H:i:s';

        return gmdate($format, $timest + $addtime);
    }

}
/**
 * 返回时差
 * @param $posttime 发布或评论时间
 * @return string   时差
 */
function time_difference($posttime)
{
    $nowtime = time();
    $posttime = empty($posttime) ? $nowtime : $posttime;
    //时差
    $time_difference = $nowtime - $posttime;
//    return $time_difference;
    switch($time_difference){
        case $time_difference > 3600*24*365 :
            $time = (date('Y',$time_difference)-1970).'年';
            break;

        case $time_difference > 3600*24*30 :
            $time = (date('n',$time_difference)-1);
            $time = ($time < 0 ? $time+12 : $time).'月';
            break;

        case $time_difference > 3600*24 :
            $time = (date('j',$time_difference)-1);
            $time = ($time < 0 ? $time+30 : $time).'天';
            break;

        case $time_difference > 3600 :
            $time = (date('G',$time_difference)-8);
            $time = ($time < 0 ? $time+24 : $time).'小时';
            break;

        case $time_difference > 60 :
            $time =date('i',$time_difference);
            if($time[0]==0){
                $time =str_replace(0,'',$time);
            }
            $time = $time.'分钟';
            break;

        default :
            $time = date('s',$time_difference).'秒';
    }
    return $time;
}

/**
 * 增加浏览记录
 * @param $table_name   表名
 * @param $map   条件
 * @param int $num  数量
 */
function add_browsing_history($table_name,$map,$field,$num=1){
    M($table_name)->where($map)->setInc($field,$num);
}
/**
 * [checkPrice 判断价格格式]
 * @Author   yi
 * @DateTime 2017-02-06T14:14:56+0800
 * @return   [type]                   [description]
 */
function checkPrice($price)
{
    if(preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $price))
        return true;
    return false;
}

function ajax_data($table,$start=0,$limit = 10,$map = null,$order = null){
    if (!$order) {
        $order = 'id desc';
    }
    $data_list = M($table)
        ->limit($start,$limit)
        ->order($order)
        ->where($map)
        ->select();
    return $data_list;
}


// 获取团队人数
function get_team_num($uid){
    $Model = new \Think\Model();
    $data =$Model->query("select count(case when  psecondid=$uid then psecondid end) as `psecondid`,count(case when  pfirstid=$uid then pfirstid end) as `pfirstid`,count(case when  pthirdid=$uid then pthirdid end) as `pthirdid` from oc_admin_user");
    return $data[0];
}

function downloadImageFromWeiXin($url)
{
    $client  =new \GuzzleHttp\Client();
    $res =$client->request('GET',$url);
    $ext       = 'jpg';
    $tmp_name = tempnam(sys_get_temp_dir(), 'weixin_avatar') . '.' . $ext;
    file_put_contents($tmp_name, $res->getBody());
    $url = U('Home/Upload/upload', ['dir' => 'image'], true, true);
    $url = str_replace("admin.php","index.php",$url);
    $uploadModel =new \Admin\Model\UploadModel();
    $result =$uploadModel->curlUploadFile($url, $tmp_name, 'image');
    if ($result === false) {
        $ret = ['status' => 0, 'info' => '上传失败'];
    } else {
        $res = json_decode($result, true);
        if ($res['status']) {
            $ret = ['status' => 1, 'info' => '上传成功', 'id' => $res['id'], 'src' => $res['url']];
        } else {
            $ret = ['status' => 0, 'info' => '上传失败，返回格式不对'];
        }
    }
    return $ret;
}

function get_qrcode($mobile,$exprie=0){
    $wechat = new \Home\Controller\WeixinController();
    $qrcode =$wechat->qrcode($mobile,$exprie);
    if($qrcode['status'] ==0){
        $ret =$qrcode;
    }else{
        // 保存图片;
        $url = $qrcode['data']['url'];
        $ret = downloadImageFromWeiXin($url);
    }
    return $ret;
}

 function get_total_comment($order_id,$type=1){
     $map['status']=1;
     $map['type']=3;
     $map['order_id'] =$order_id;
    return M('user_comment')->where($map)->count('id');
 }

 function get_search_words($group){
    return M('forum_search')->where(['status'=>1,'group'=>$group])->order('sort desc')->select();
 }
// 去除二维数组重复值
function assoc_unique(&$arr, $key)
{
    $rAr=array();
    for($i=0;$i<count($arr);$i++)
    {
        if(!isset($rAr[$arr[$i][$key]]))
        {
            $rAr[$arr[$i][$key]]=$arr[$i];
        }
    }
    $arr=array_values($rAr);
    return $arr;
}

function getparentids($userid){
    $row =M('admin_user')->where(['id'=>$userid])->find();
    if($row){
        $parentids = array(
            'pfirstid'  => $userid,
            'psecondid' => $row['pfirstid'],
            'pthirdid'  => $row['psecondid'],
            'pfouthid'  => $row['pthirdid'],
        );
    }else{
        $parentids = array(
            'pfirstid'  => $userid,
            'psecondid' => 0,
            'pthirdid'  => 0,
            'pfouthid'  => 0,
        );
    }
    return $parentids;
}

function get_goods_info($order_id,$check='')
{
    $info = M('shop_order_item')->where(['order_id' => $order_id])->select();
    $str  = '';
    foreach ($info as $k => $v) {
        $goods_detail = M('shop_goods')->where(['id' => $v['goods_id']])->getField('title');

        if(empty($check)){
            if($k==0){
                $str = '商品：' .$goods_detail . '数量:' . $v['buy_num'] . '　价格：' . $v['price'];
            }else{
                $str .= '<hr>'.'商品：' .$goods_detail . '数量:' . $v['buy_num'] . '　价格：' . $v['price'];
            }
        }else{
            if($k==0){
                $str = cutTitle('商品：' .$goods_detail)  . '数量:' . $v['buy_num'] . '　价格：' . $v['price'];
            }else{
                $str .= '<hr style="border-color:#2699ed;">'.cutTitle('商品：' .$goods_detail)  . '数量:' . $v['buy_num'] . '　价格：' . $v['price'];
            }
        }

    }

    return $str;
}

//   获取openid
function get_wx_userinfo($uid,$type=''){
    $user =M('user')->where(['admin_uid'=>$uid])->find();
    if(empty($type)){
        return $user;
    }else{
        return $user[$type];
    }
}

// 获取积分类型
function get_score_type($id){
    switch($id){
        case 1:
            $name= '增加';
            break;
        case 2:
            $name='扣除';
            break;
        default:
            $name ='增加';
    }
    return $name;
}
/**
 * [cutTitle 防止标题过长]
 * @Author   yi
 * @DateTime 2017-03-01T13:08:07+0800
 * @param    [type]                   $title [description]
 * @return   [type]                          [description]
 */
function cutTitle($title)
{
    return '<div title="'.$title.'" style="cursor:pointer;width:200px;white-space:nowrap;overflow:hidden; text-overflow:ellipsis;">'.$title.'</div>';
}

/**
 * @param $type   类型
 * @param $score  积分
 * @param $uid    用户ID
 * @param $title  标题
 * @return int
 */
function set_user_score($type,$score,$uid,$title){
    $flag =1;
    $add_user_data=[];
    $add_user_data['uid'] =$uid;
    $add_user_data['score'] =$score;
    $add_user_data['title'] =$title;
    $add_user_data['nickname']=get_username($uid);
    $add_user_data['create_time'] =datetime();
    $add_user_data['type']=$type;
    $add_record =M('user_score')->add($add_user_data);
    if(!$add_record){
        $flag =0;
        return $flag;
    }
    switch($type){
        // 增加
        case 1:
            $reduce_score =M('admin_user')->where(['id'=>$uid])->setInc('score',$score);
            break;
        // 减少
        case 2:
            $reduce_score =M('admin_user')->where(['id'=>$uid])->setDec('score',$score);
            break;
    }
    if(!$reduce_score){
        $flag =0;
        return $flag;
    }
    return $flag;
}
/**
 * [callback_status 帖子状态]
 * @param  [type] $status [description]
 * @return [type]         [description]
 */
function callback_status($status){
    switch ($status) {
        case -1:
            $return = '已拒绝';
            break;
        case 0:
            $return = '<span style="color:red">未审核</span>';
            break;
        case 1:
            $return = '已通过';
            break;
    }
    return $return;
}
/**
 * [checked_sendcustom 后台审核发送客服消息]
 * @param  [type] $ids    [ID]
 * @param  [type] $status [description]
 * @param  [type] $model  [表名]
 * @return [type]         [description]
 */
function checked_sendcustom($ids,$status,$model){
    $model_info = M($model);
    $user_info = $model_info
            ->alias('a')
            ->join('oc_admin_user as b on b.id = a.uid')
            ->join('oc_user as c on b.id = c.admin_uid')
            ->field('b.nickname,c.openid')
            ->where(['a.id'=>$ids])
            ->find();
    if($status == 1){
        $statustxt = '通过审核';
    }else{
        $statustxt = '被拒绝发布';
    }
    if($user_info){
        switch ($model) {
        case 'forum_job_search'://求职
            $msg = $user_info['nickname'].'，您发表的求职信息已经'.$statustxt;
            break;
        case 'forum_job'://招聘
            $msg = $user_info['nickname'].'，您发表的招聘信息已经'.$statustxt;
            break;
        case 'forum_posts'://帖子
            $msg = $user_info['nickname'].'，您发表的帖子已经'.$statustxt;
            break;
        }
        $weixin_class = new \Home\Controller\WeixinController();
        $weixin_class->send_custom($user_info['openid'],$msg);
    }
 
}
if (!function_exists('ReStrLen')) {

    function ReStrLen($str, $len = 10, $etc = '...') {
        $restr = '';
        $i = 0;
        $n = 0.0;

        //字符串的字节数
        $strlen = strlen($str);
        while (($n < $len) and ($i < $strlen)) {
            $temp_str = substr($str, $i, 1);

            //得到字符串中第$i位字符的ASCII码
            $ascnum = ord($temp_str);

            //如果ASCII位高与252
            if ($ascnum >= 252) {
                //根据UTF-8编码规范，将6个连续的字符计为单个字符
                $restr = $restr . substr($str, $i, 6);
                //实际Byte计为6
                $i = $i + 6;
                //字串长度计1
                $n++;
            } else if ($ascnum >= 248) {
                $restr = $restr . substr($str, $i, 5);
                $i = $i + 5;
                $n++;
            } else if ($ascnum >= 240) {
                $restr = $restr . substr($str, $i, 4);
                $i = $i + 4;
                $n++;
            } else if ($ascnum >= 224) {
                $restr = $restr . substr($str, $i, 3);
                $i = $i + 3;
                $n++;
            } else if ($ascnum >= 192) {
                $restr = $restr . substr($str, $i, 2);
                $i = $i + 2;
                $n++;
            }

            //如果是大写字母 I除外
            else if ($ascnum >= 65 and $ascnum <= 90 and $ascnum != 73) {
                $restr = $restr . substr($str, $i, 1);
                //实际的Byte数仍计1个
                $i = $i + 1;
                //但考虑整体美观，大写字母计成一个高位字符
                $n++;
            }

            //%,&,@,m,w 字符按1个字符宽
            else if (!(array_search($ascnum, array(37, 38, 64, 109, 119)) === FALSE)) {
                $restr = $restr . substr($str, $i, 1);
                //实际的Byte数仍计1个
                $i = $i + 1;
                //但考虑整体美观，这些字条计成一个高位字符
                $n++;
            }

            //其他情况下，包括小写字母和半角标点符号
            else {
                $restr = $restr . substr($str, $i, 1);
                //实际的Byte数计1个
                $i = $i + 1;
                //其余的小写字母和半角标点等与半个高位字符宽
                $n = $n + 0.5;
            }
        }

        //超过长度时在尾处加上省略号
        if ($i < $strlen) {
            $restr = $restr . $etc;
        }

        return $restr;
    }

}
?>