<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Home\Controller;

use Common\Controller\ControllerController;
use Common\Util\Think\Page;
use Think\Verify;

/**
 * 微信前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用模块名
 * @author jry <598821125@qq.com>
 */
class BaseController extends ControllerController
{
    public $member;
    public $memberInfo;
    public $openid;
    public $uid;

    /**
     * 初始化方法
     * @author jry <598821125@qq.com>
     */
    protected function _initialize()
    {
        if (!APP_DEBUG) {
            C('SHOW_PAGE_TRACE', false);
        }

        C('SHOW_PAGE_TRACE', false);
        $this->cookie_expire = 86400 * 7;

        // debug
//        cookie('openid', 'oVyxKuCAEJPSMypiwwgYdrHIUDSY', $this->cookie_expire); //本地调试cookie
//        cookie('openid', 'oVyxKuCAEJPSMypiwwgYdrHIUDSY', $this->cookie_expire); //本地调试cookie
//        cookie('uid', 1, $this->cookie_expire);
//        cookie('lng', '119.4574950000', $this->cookie_expire);
//        cookie('lat', '32.1350290000', $this->cookie_expire);
//        cookie('uid',3);

         // 系统开关
        $this->assign('is_weixin', is_weixin() ? '1' : '0');
        if (!C('TOGGLE_WEB_SITE')) {
            $this->error('站点已经关闭，请稍后访问~');
        }

        $this->current_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        //后退
        $this->assign('back_url', 'javascript:history.back(-1);');
        if (isset($_SERVER['HTTP_REFERER'])) {
            Cookie('__forward__', $_SERVER['HTTP_REFERER']);
        }
        $openid        = cookie('openid');
        $this->openid = $openid;
        $uid           = cookie('uid');
        $uid           = empty($uid) ? 0 : (int) $uid;
        $this->uid    = $uid;
        $userInfo      = D('Admin/user')->find($uid);

        //为空时给默认值
        if (empty($userInfo)) {
            $this->uid = 0;
            $userInfo  = [
                'id'         => 0,
                'admin_uid'  => 0,
                'headimgurl' => '',
                'avatar'     => 0,
                'mobile'     => '',
                'recUid'     => 0,
            ];
        }else{
            $this->userInfo = $userInfo;
        }

        //判断是否需要微信授权
        if (!in_array(strtolower(ACTION_NAME), ['auth_back',])) {
            $this->is_auth(); // 微信授权登录
        }

       /* //商家登录后的关联id
        $admin_uid        = cookie('admin_uid');
        $admin_uid        = empty($admin_uid) ? 0 : (int) $admin_uid;
        $this->admin_uid = $admin_uid;
        if ($admin_uid) {
            //商家信息
            $admin_info = M('shop')->find($admin_uid);
            if (!$admin_info) {
                $msg = "os_user表{$uid} 对应 os_shop 商家表id为{$admin_uid}的记录查找不到";
                pubu($msg);
                $this->admin_uid = 0;
                // E("oc_user表{$uid} 对应 admin_user 用户表id为{$userInfo['admin_uid']}的记录查找不到");
            } else {
                $this->admin_info = $admin_info;
            }
        }*/

        //分销关系
        if ($recUid = I('recUid')) {
            // 上级经销商 admin_user表的uid
            saveRecUser($recUid);
        }
        // 登录后如果有推荐人，更新推荐人
        if ($recUid = cookie('recUid')) {
            if ($this->admin_uid != $recUid) {
                if ($userInfo['id'] && $userInfo['recUid'] == 0) {
                    M('admin_user')->where(['id' => $this->admin_uid])->save(['recUid' => $recUid]);
                }
            }
        }

        // 获取所有模块配置的用户导航
        $mod_con['status'] = 1;
        $TMPL_PARSE_STRING = C('TMPL_PARSE_STRING');
        $TMPL_PARSE_STRING += [
            '__HTML__'      => __ROOT__ . '/html',
            '__HTML_CSS__'  => __ROOT__ . '/html/css',
            '__HTML_JS__'   => __ROOT__ . '/html/js',
            '__HTML_IMG__'  => __ROOT__ . '/html/images',
            '__HTML_WEUI__' => __ROOT__ . '/html/weui',
        ];
        C('TMPL_PARSE_STRING', $TMPL_PARSE_STRING);

        // 监听行为扩展
        \Think\Hook::listen('corethink_behavior');

        $url = $this->current_url;
        //检测分享
        /*$shareparentid = 0;
        if(strpos($url,'shareparentid')!=false && $_GET['shareparentid']){
            M('user')->where(['id'=>$uid])->save(['pid'=>$_GET['shareparentid']]);
            $shareparentid = $_GET['shareparentid'];
        }
        $this->shareparentid = $shareparentid;
        //分享配置
        $share_desc = C('share_desc');
        $share_desc = str_replace("【分享人】", $this->nickname, $share_desc);
        if(strpos($url,'?')==false){
            $url .= '?shareparentid='. $uid;
        }else{
            $url .= '&shareparentid='. $uid;
        }
        $share_link = C('share_link');
        $share_link = empty($share_link) ? $url : $share_link;
        $share = array(
            'title' => C('share_title'),
            'desc' => $share_desc,
            'link' => $share_link,
            'imgUrl' => 'http://' . $_SERVER['HTTP_HOST'].get_cover(C('share_imgUrl'),'default')
        );
        $this->share = $share;*/

        $this->assign('uid', $this->uid);
        $this->assign('userInfo', $this->userInfo);
        $this->assign('admin_uid', $this->admin_uid);
        $this->assign('gl', 0);//底部菜单高亮判断标志
        $this->assign('meta_title', C('WEB_SITE_TITLE'));
        $this->assign('meta_keywords', C('WEB_SITE_KEYWORD'));
        $this->assign('meta_description', C('WEB_SITE_DESCRIPTION'));
        $this->assign('meta_wrap', '');

        //获取用户经纬度
        $this->lng = isset($lng) ? $lng : cookie('lng');//经度
        $this->lat = isset($lat) ? $lat : cookie('lat');//纬度
        $this->assign('lng', $this->lng);
        $this->assign('lat', $this->lat);

        $jsapi = R('Home/Weixin/jsapi', [['getLocation','scanQRCode','chooseWXPay']]);
        $this->assign('jsapi', $jsapi);

        //非支付页面设置默认分享，上传图片页面也不默认指定分享
        /*if (!in_array(strtolower(ACTION_NAME), ['confirm', 'order', 'setting'])) {
            $jsapi = R('Home/Weixin/jsapi', [['onMenuShareAppMessage', 'onMenuShareTimeline']]);
            $this->assign('jsapi', $jsapi);
        } else {
            if (in_array(strtolower(ACTION_NAME), ['confirm', 'order'])) {
                $can_share = 0;
            }
        }*/
    }

    /**
     * 用户登录检测
     * @author jry <598821125@qq.com>
     */
    protected function is_auth()
    {
        if (empty($this->openid)) {
            cookie('openid', null);
            cookie('uid', null);
            $this->wechat();
            exit;
        } else {
            return $this->uid;
        }
    }

    public function check_login()
    {
        $uid = cookie('uid');
        if (!$uid || !$this->uid) {
            if (IS_GET) {
                cookie('before_reg', $this->current_url, $this->cookie_expire);
            }
            $this->redirect('Home/Member/login');
        }else{
            return $this->uid;
        }
    }

    public function wechat()
    {
        $cookie_time = $this->cookie_expire;
        cookie('target_url', $this->current_url, $cookie_time);
        $back_url = U('Home/Weixin/auth_back', null, false, true);
        R('Home/Weixin/auth', [$back_url, 'snsapi_userinfo']);
    }

    //获取当前高亮索引
    public function getCurrenNav()
    {
        $match_url = CONTROLLER_NAME . '/' . ACTION_NAME;
        $match_url = strtolower($match_url);
        switch ($match_url) {
            case 'booking/recommend':
                return 0;
                break;
            case 'booking/index':
                return 1;
                break;
            case 'index/index':
                return 2;
                break;
            default:
                return 3;
                break;
        }
    }

    /**
     * 设置一条或者多条数据的状态
     * @param $script 严格模式要求处理的纪录的uid等于当前登陆用户UID
     * @author jry <598821125@qq.com>
     */
    public function setStatus($model = CONTROLLER_NAME, $script = true)
    {
        $ids    = I('request.ids');
        $status = I('request.status');
        if (empty($ids)) {
            $this->error('请选择要操作的数据');
        }
        $model_primary_key       = D($model)->getPk();
        $map[$model_primary_key] = array('in', $ids);
        if ($script) {
            $map['uid'] = array('eq', is_login());
        }
        switch ($status) {
            case 'forbid': // 禁用条目
                $data = array('status' => 0);
                $this->editRow(
                    $model,
                    $data,
                    $map,
                    array('success' => '禁用成功', 'error' => '禁用失败')
                );
                break;
            case 'resume': // 启用条目
                $data = array('status' => 1);
                $map  = array_merge(array('status' => 0), $map);
                $this->editRow(
                    $model,
                    $data,
                    $map,
                    array('success' => '启用成功', 'error' => '启用失败')
                );
                break;
            case 'hide': // 隐藏条目
                $data = array('status' => 2);
                $map  = array_merge(array('status' => 1), $map);
                $this->editRow(
                    $model,
                    $data,
                    $map,
                    array('success' => '隐藏成功', 'error' => '隐藏失败')
                );
                break;
            case 'show': // 显示条目
                $data = array('status' => 1);
                $map  = array_merge(array('status' => 2), $map);
                $this->editRow(
                    $model,
                    $data,
                    $map,
                    array('success' => '显示成功', 'error' => '显示失败')
                );
                break;
            case 'recycle': // 移动至回收站
                $data['status'] = -1;
                $this->editRow(
                    $model,
                    $data,
                    $map,
                    array('success' => '成功移至回收站', 'error' => '删除失败')
                );
                break;
            case 'restore': // 从回收站还原
                $data = array('status' => 1);
                $map  = array_merge(array('status' => -1), $map);
                $this->editRow(
                    $model,
                    $data,
                    $map,
                    array('success' => '恢复成功', 'error' => '恢复失败')
                );
                break;
            case 'delete': // 删除条目
                $result = D($model)->where($map)->delete();
                if ($result) {
                    $this->success('删除成功，不可恢复！');
                } else {
                    $this->error('删除失败');
                }
                break;
            default:
                $this->error('参数错误');
                break;
        }
    }

    /**
     * ajax分页
     * @param  mixed $result       数组或文本 数据数组或者sql
     * @param  integer $listRows     每页显示多少条
     * @param  string $listvar      遍历时的变量
     * @param  array  $parameter    数组
     * @param  string $target       目标替换容器选择器
     * @param  string $pageSelector 分页选择器
     * @param  string $template     ajax加载时的模板
     * @return mixed               数组或字符串
     */
    public function ajaxPage($result, $listRows, $listvar, $parameter = [], $target = '', $pageSelector = '', $template = '')
    {
        //总记录数
        $is_sql  = is_string($result);
        $listvar = $listvar ?: 'list';
        if ($is_sql) {
            $totalRows = M()->table($result . ' a')->count();
        } else {
            $totalRows = ($result) ? count($result) : 1;
        }
        //创建分页对象
        if ($target && $pageSelector) {
            $p = new Page($totalRows, $listRows, $parameter);
        } else {
            $p = new Page($totalRows, $listRows, $parameter);
        }

        //抽取数据
        if ($is_sql) {
            $result .= " LIMIT {$p->firstRow},{$p->listRows}";
            $voList = M()->query($result);
        } else {
            $voList = array_slice($result, $p->firstRow, $p->listRows);
        }
        //分页显示
        $page = $p->show();

        if ($target && $pageSelector && !IS_AJAX && $page) {
            $page .= <<<JS
<script>
     jQuery(function($) {
        $('{$pageSelector} a').click(function(){
            $.ajax({
                url: $(this).attr('href'),
                dataType: "html",
                type: "GET",
                cache: false,
                async:true,
                success: function(html){
                    $("{$target}").html(html);
                    return false;
                }
            });
            return false;
        });
     });
 </script>
JS;
        }
        //模板赋值
        $this->assign($listvar, $voList);
        $this->assign('pageSelector', $pageSelector);
        $this->assign("table_data_page", $page);
        //判断ajax请求
        if (IS_AJAX) {
            layout(false);
            $template = $template ?: 'ajaxlist';
            exit($this->fetch($template));
        }
        return $voList;
    }
    //前端异常
    public function front_log()
    {
        $text = I('text');
        $res  = pubu($text, 'all');
        if ($res) {
            $this->success();
        } else {
            $this->error('发送瀑布消息失败');
        }
    }

    /**
     * 检测验证码
     * @param  integer $id 验证码ID
     * @return boolean 检测结果
     */
    public function check_verify($code, $vid = 1)
    {
        $verify = new Verify();
        return $verify->check($code, $vid);
    }
    public  function  miao_send(){
        if (IS_AJAX) {
            $mobile = I('mobile', '');
            $rand   = rand(1000, 9999);
            $code   = I('post.code', '');
            if ($code && !$this->check_verify($code, 1)) {
                $this->error('图形验证码不正确');
            }
        } else {
            $this->error();
        }
        $cookie_time = time() + 60 * 5;
        $time =5;
        cookie($mobile . '_codenum', $rand, $cookie_time);
        cookie('mobile', $mobile, $cookie_time);
        ptrace('短信' . $rand);
        $message_model =new \Common\Util\Message();
        $funAndOperate = "industrySMS/sendSMS";
        $body = $message_model->createBasicAuthData();
        $body['smsContent'] = "【艾玛莎基网】"."您的验证码为{$rand}，请于{$time}分钟内正确输入，如非本人操作，请忽略此短信。";
        $body['to'] = $mobile;
        $result = $message_model->post($funAndOperate, $body);
        $result =json_decode($result,true);
        ptrace($result);
        if($result['respCode']=='0000'){
            $this->success('发送成功');
        }else{
            $this->error('发送失败');
        }
        exit;
    }
    //发送短信验证码
    public function send()
    {
        if (IS_AJAX) {
            $mobile = I('mobile', '');
            $rand   = rand(1000, 9999);
            $code   = I('post.code', '');
            if ($code && !$this->check_verify($code, 1)) {
                $this->error('图形验证码不正确');
            }
        } else {
            $this->error();
        }
        $cookie_time = time() + 60 * 5;
        cookie($mobile . '_codenum', $rand, $cookie_time);
        cookie('mobile', $mobile, $cookie_time);
        ptrace('短信' . $rand);
        // ptrace($rand);
        $data = sendTemplateSMS($mobile, [$rand, '5'], '105645');
        $this->ajaxReturn($data);
        exit;
    }

    /**
     * 图片验证码生成，用于登录和注册
     * @author jry <598821125@qq.com>
     */
    public function verify($vid = 1)
    {
        $verify = new Verify([
            'useCurve' => false,
            'useNoise' => false,
            'fontSize' => 36,
        ]);
        $verify->length = 4;
        $verify->entry($vid);
    }

}
