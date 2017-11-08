<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <59821125@qq.com>
// +----------------------------------------------------------------------
namespace Common\Util;

class Member
{

    private $type;
    private $member_table        = 'admin_user'; //管理员表
    private $user_money_table    = 'user_money'; //佣金记录表
    private $user_withdraw_table = 'user_withdraw'; //佣金记录表
    private $user_coupons_table  = 'user_coupons'; //优惠券记录表
    private $user_coin_table     = 'user_coin'; //金币记录表
    private $weixin_table        = 'user'; //用户表
    private $card_charge         = 'cardcharge'; //会员卡购买记录
    private $card_logs           = 'card_logs'; //会员卡使用记录
    private $encryption          = 'user_md5';
    private $sms_inteval         = 15; // 短信有效期 单位 分
    private $login_expire        = 7; // 登录过期天数
    private $user_model          = null;

    public function __construct($type = 'weixin')
    {
        $this->type = $type;
    }

    //__set()方法用来设置私有属性
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    //__get()方法用来获取私有属性
    public function __get($name)
    {
        return $this->$name;
    }

    // 检测登录
    public function is_login($check_field = 'uid')
    {
        if ($cookie_value = cookie($check_field)) {
            return AuthCode($cookie_value, 'DECODE');
        } else {
            return 0;
        }
    }

    // 发短信验证码
    public function sendSms($mobile)
    {
        if ($code = cookie('sms_code')) {
            $code = $code;
        } else {
            $code = rand(1000, 9999);
        }
        // $ret = TODO 自己实现发短信逻辑
        $ret = true;
        ptrace($code);
        $cookie_expire = 60 * $this->sms_inteval;
        cookie('sms_code', $code, $cookie_expire);
        return $ret;
    }

    // 检测登录表单
    public function checkLogin($data, $type = 'username')
    {
        switch ($type) {
            case 'username':
            case 'mobile_username':
                if (!isset($data['username']) || empty($data['username'])) {
                    return ['status' => 0, 'info' => '用户名不能为空'];
                }
                break;
            case 'email':
                if (!isset($data['email']) || empty($data['email'])) {
                    return ['status' => 0, 'info' => '邮箱不能为空'];
                }
                if (false == filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    return ['status' => 0, 'info' => '邮箱格式不正确'];
                }
                break;
            case 'mobile':
                if (!isset($data['mobile']) || empty($data['mobile'])) {
                    return ['status' => 0, 'info' => '手机号不能为空'];
                }
                if (!isMobileFormat($data['mobile'])) {
                    return ['status' => 0, 'info' => '手机号格式不正确'];
                }
                break;
            default:
                pubu("未支持的登录方式{$type}");
                return ['status' => 0, 'info' => '未支持的登录方式'];
                break;
        }
        if (!isset($data['password']) || empty($data['password'])) {
            return ['status' => 0, 'info' => '密码不能为空'];
        }

        if (isset($data['sms_code']) && in_array($type, ['mobile', 'mobile_username'])) {
            $sms_code = cookie('sms_code');
            if ($sms_code != $data['sms_code']) {
                return ['status' => '0', 'info' => '短信验证码不正确'];
            }
        }

        return ['status' => '1', '登录数据没有错误'];
    }

    // 登录
    public function login($data, $type = 'username')
    {
        $checkRet = $this->checkLogin($data, $type);
        if ($checkRet['status']) {
            switch ($type) {
                case 'username':
                    $username = $data['username'];
                    $where    = "`username`='{$username}'";
                    break;
                case 'mobile':
                    $mobile = $data['mobile'];
                    $where  = "`mobile`='{$mobile}'";
                    break;
                case 'email':
                    $email = $data['email'];
                    $where = "`email`='{$email}'";
                    break;
                case 'mobile_username':
                    $username = $data['username'];
                    $mobile   = $data['username'];
                    $where    = "`mobile`='{$mobile}' OR `username`='{$username}'";
                    break;
                default:
                    pubu("未支持的登录方式{$type}");
                    return ['status' => 0, 'info' => '未支持的登录方式'];
                    break;
            }
            $exist = M($this->member_table)->where($where)->find();
            if ($exist) {
                if ($exist['password'] == call_user_func($this->encryption, $data['password'])) {
                    $this->after_login($exist, $type, $this->type);
                    return ['status' => 1, 'info' => '登录成功'];
                } else {
                    return ['status' => 0, 'info' => '密码不正确'];
                }
            } else {
                return ['status' => 0, 'info' => '用户不存在'];
            }
        } else {
            return $checkRet;
        }
    }

    // 注册
    public function register($data, $type = 'username')
    {
        $checkRet = $this->checkLogin($data, $type);
        if ($checkRet['status']) {
            $data['password'] = call_user_func($this->encryption, $data['password']);
            switch ($type) {
                case 'username':
                    $username = $data['username'];
                    $where    = "`username`='{$username}'";
                    break;
                case 'mobile':
                    $mobile              = $data['mobile'];
                    $data['mobile_bind'] = 1;
                    $where               = "`mobile`='{$mobile}'";
                    break;
                case 'mobile_username':
                    $username = $data['username'];
                    $mobile   = $data['mobile'];
                    $where    = "`mobile` in ('{$mobile}', '{$username}') OR `username` in ('{$mobile}', '{$username}')";
                    break;
                default:
                    pubu("未支持的注册方式{$type}");
                    return ['status' => 0, 'info' => '未支持的注册方式'];
                    break;
            }
            $exist = M($this->member_table)->where($where)->find();
            if ($exist) {
                return ['status' => 0, 'info' => '用户已经存在了'];
            }

            $data['regip']       = get_client_ip();
            $data['create_time'] = datetime();
            $data['status']      = 1;
            try {
                $id = M($this->member_table)->add($data);
                $this->after_register($data, $id);
                return ['status' => 1, 'info' => '注册成功'];
            } catch (\Exception $e) {
                return ['status' => 0, 'info' => $e->getMessage()];
            }
        } else {
            return $checkRet;
        }
    }

    // 登录之后
    public function after_login($member, $login_type)
    {
        $expire      = 3600 * 24 * $this->login_expire;
        $model       = M($this->member_table);
        $weixin_user = M($this->weixin_table);
        if ($this->type == 'weixin') {
            if ($openid = cookie('openid')) {
                $weixin_record = $weixin_user->where("`openid`='{$openid}'")->find();
                if ($weixin_record) {
                    $weixin_user->where(['id' => $weixin_record['id']])->save(['admin_uid' => $member['id']]);
                    $weixin_user->where(['id' => ['neq', $weixin_record['id']], 'admin_uid' => $member['id']])->save(['admin_uid' => 0]);
                }
                $member['openid'] = $openid;
            }

            // cookie('openid', $openid, $expire);
        }

        if ('mobile' == $login_type) {
            cookie('mobile', $member['mobile'], $expire);
        }
        cookie('uid', $member['id'], $expire);
        //D('Admin/user')->auto_login($member);
    }

    // 注册之后
    public function after_register($data, $newid)
    {
        $expire = 3600 * 24 * $this->login_expire;
        if ($this->type == 'weixin') {
            $openid       = cookie('openid');
            $weixin_table = M($this->weixin_table);
            // 将要更新的其他绑定解除
            $weixin_table->where(['admin_uid' => $newid])->save(['admin_uid' => 0]);
            $weixin_headimgurl = $weixin_table->where(['openid' => $openid])->getField('headimgurl');
            $ret = false !== $weixin_table->where("openid='{$openid}'")->save(['admin_uid' => $newid]);
            $weixin_user =$weixin_table->where(['admin_uid' => $newid])->find();
            //同步微信表头像
            if ($weixin_headimgurl) {;
                $avatar_arr = downloadImageFromWeiXin($weixin_user['headimgurl']);
                $admin_user_data['avatar']= empty($avatar_arr['id'])?'':$avatar_arr['id'];
            }
            $admin_user_data['nickname'] = $weixin_user['nickname'];

                        //赠送积分
            $score_gift = M('shop_set')->getfield('register_gift');
            if(!empty($score_gift)){
                $admin_user_data['score'] = $score_gift;
            }
            // 绑定关系
            $admin_user_data['pfirstid'] =$weixin_user['pfirstid'];
            $admin_user_data['psecondid'] =$weixin_user['psecondid'];
            $admin_user_data['pthirdid'] =$weixin_user['pthirdid'];
            $admin_user_res = M('admin_user')->where(['id'=>$newid])->save($admin_user_data);
            //增加积分记录
            if($admin_user_res!==false && !empty($score_gift)){
                $score_data['title'] = "注册增加$score_gift";
                $score_data['uid'] = $newid;
                $score_data['score'] = $score_gift;
                $score_data['create_time'] = datetime();
                $score_data['type'] = 1;
                $score_data['group'] = 4;
                M('user_score')->add($score_data);
            }

        }
        $data['id'] = $newid;
       // D('Admin/user')->auto_login($data);
        cookie('uid', $newid, $expire);
    }

    // 登出
    public function logout($check_field = 'admin_uid')
    {
        cookie($check_field, null);
        return !cookie($check_field);
    }

    /**
     * 更新分销关系
     * @param int $uid
     * @param array $data ['salesman'=>'1', 'store'=>'0', 'uplevel', 'from'=>['store|link']]
     */
    public function updateDistributionRelation($uid, $data)
    {
        return M('admin_user')->where(['id' => $uid])->save($data) !== false;
    }

    /**
     * 获取用户信息
     * @param  int      $uid   用户id
     * @param  string   $field
     * @return array    用户信息
     */
    public function GetUserInfo($uid, $field = '*')
    {
        return M($this->member_table)->where(['id' => $uid])->field($field)->find();
    }

    // /**
    //  * 修改用户积分
    //  * @param      int  $uid    The uid
    //  * @param      int  $num    The number
    //  * @param      string  $type   The type
    //  * @return     <type>  ( description_of_the_return_value )
    //  */
    // public function SetScore($uid, $num, $type = 'add')
    // {
    //     if ($type == 'add') {
    //         $res = M($this->weixin_table)->where(['id' => $uid])->setInc('score', $num);
    //     } else {
    //         $res = M($this->weixin_table)->where(['id' => $uid])->setDec('score', $num);
    //     }
    //     return $res;
    // }

    /**
     * 积分记录函数
     * @param  array    $user     用户信息
     * @param  string   $title
     * @param  int      $score    修改数量 大于0：加、小于0：减
     * @param  integer  $order_id 订单id
     * @return boolean
     */
    public function SetScore($user, $title, $score, $order_id = 0)
    {
        if ($score == 0) {
            return true;
        }
        $title = score_title($title);
        // ptrace($nickname);
        $add = M('user_score')->add([
            'title'       => $title,
            'score'       => $score,
            'uid'         => $user['id'],
            'order_id'    => $order_id,
            'create_time' => datetime(),
        ]);
        if ($score > 0) {
            $update = M($this->member_table)->where(['id' => $user['id']])->setInc('score', $score);
        } else {
            $update = M($this->member_table)->where(['id' => $user['id']])->setDec('score', abs($score));
        }
        return $add && (false !== $update);
    }
    /**
     * 修改用户会员卡
     * @param  int          $user     用户信息
     * @param  int          $card_id  会员卡id
     * @param  string       $title
     * @param  number       $money    修改金额 大于0：加、小于0：减
     * @param  integer      $order_id 订单id
     * @return boolean      true：成功、false：失败
     */
    public function SetCardMoney($user, $card_id, $title, $money, $order_id = 0)
    {
        if ($money == 0) {
            return true;
        }
        $add = M($this->card_logs)->add([
            'uid'         => $user['id'],
            'order_id'    => $order_id,
            'card_id'     => $card_id,
            'title'       => $title,
            'money'       => $money,
            'create_time' => datetime(),
        ]);
        if ($money > 0) {
            $update = M($this->card_charge)->where(['uid' => $user['id'], 'card_id' => $card_id])->setInc('money', $money);
        } else {
            $update = M($this->card_charge)->where(['uid' => $user['id'], 'card_id' => $card_id])->setDec('money', abs($money));
        }
        return $add && (false !== $update);
    }

    /**
     * 修改用户金币、金币记录
     * @param      array  $userInfo    用户
     * @param      int  $num    修改数量
     * @param      string  $type  类型 add：加、其他：减
     * @return     boolean true：成功、false：失败
     */
    public function SetCoin($userInfo, $num, $type = 'add', $orderid, $title)
    {
        if ($num == 0) {
            return true;
        }
        if ($type == 'add') {
            $res = M($this->member_table)->where(['id' => $userInfo['id']])->setInc('coin', $num);
        } else {
            $res = M($this->member_table)->where(['id' => $userInfo['id']])->setDec('coin', $num);
        }
        $coin_data = array(
            'uid'         => $userInfo['id'],
            'title'       => $title,
            'coin'        => $num,
            'order_id'    => $orderid,
            'create_time' => datetime(),
        );
        $add = M($this->user_coin_table)->add($coin_data);
        return $res && (false !== $add);
    }

    /**
     * 修改用户总佣金
     * @param      int  $uid    用户ID
     * @param      int  $num    修改数量
     * @param      string  $type  类型 add：加、其他：减
     * @return     <type>  ( description_of_the_return_value )
     */
    public function SetMoney($uid, $num, $type = 'add')
    {
        if ($type == 'add') {
            $res = M($this->member_table)->where(['id' => $uid])->setInc('money', $num);
        } else {
            $res = M($this->member_table)->where(['id' => $uid])->setDec('money', $num);
        }
        return $res;
    }

    /**
     * 添加用户佣金记录
     * @param      array  $data   The data
     * @return     <type>  ( description_of_the_return_value )
     */
    public function AddUserMoney($data)
    {
        return M($this->user_money_table)->add($data);
    }

    public function SendCommission($uid, $orderInfo)
    {
        $result   = true;
        $userInfo = $this->GetUserInfo($uid);
        if ($userInfo) {
            $ratio        = $this->GetRebateRatio($uid);
            $rebate_money = $orderInfo['payment'] * $ratio;
            if ($rebate_money > 0) {
                $res = $this->WithdrawLog($userInfo['id'], $rebate_money, 1, $orderInfo['id']);
                if ($res['status'] !== 0) {
                    $result = false;
                }
            }
        }
        return $result;
    }

    /**
     * 佣金记录
     * @param      int   $uid      用户id
     * @param      int   $money    金额
     * @param      integer  $type     类型 1: 返利、2：提现、3：拒绝提现
     * @param      integer  $orderid  订单id
     * @param      string   $reason   退款失败原因
     * @param      integer  $admin_id     操作员id
     * @param      integer  $withdraw_id  佣金记录id 退款时用
     * @param      datatime  $check_time  审核时间
     *
     * @return     array    ( description_of_the_return_value )
     */
    public function WithdrawLog($uid, $money, $type, $orderid = 0, $reason = '', $admin_id = 0, $withdraw_id = 0, $check_time = '')
    {
        $result        = ['status' => 0, 'msg' => ''];
        $withdraw_data = array(
            'uid'         => $uid,
            'money'       => $money,
            'orderid'     => $orderid,
            'create_time' => datetime(),
            'type'        => $type,
            'reason'      => $reason,
            'admin_id'    => $admin_id,
            'withdraw_id' => $withdraw_id,
        );
        if (!empty($check_time)) {
            $withdraw_data['check_time'] = $check_time;
        }
        switch ($type) {
            case '1': //返利
                $res = $this->SetCanGetMoney($uid, $money);
                break;
            case '2': //提现
                $withdraw_data['status'] = 1;
                $res                     = $this->SetCanGetMoney($uid, $money, 'd');
                break;
            case '3': //拒绝提现
                $res = M($this->user_withdraw_table)->where(['id' => $withdraw_id])->save(['status' => '3', 'check_time' => datetime(), 'admin_id' => $admin_id, 'reason' => $reason]);
                if ($res === false) {
                    $result['msg'] = '状态修改失败';
                    goto done;
                }
                $res = $this->SetCanGetMoney($uid, $money);
                break;
            default:
                $result['msg'] = '状态不存在';
                goto done;
                break;
        }
        if ($res === false) {
            $result['msg'] = '修改佣金失败';
            goto done;
        }
        $res = M($this->user_withdraw_table)->add($withdraw_data);
        if ($res === false) {
            $result['msg'] = '佣金记录创建失败';
            goto done;
        }
        done:
        if (!empty($result['msg'])) {
            $result['status'] = 1;
        } else {
            $result['status'] = 0;
        }
        return $result;
    }

    public function SetCanGetMoney($uid, $money, $type = 'add')
    {
        if ($type == 'add') {
            $res = $this->set_money($uid, $money, $type);
            if ($res === false) {
                return $res;
            }
            $res = M($this->member_table)->where(['id' => $uid])->setInc('can_get_money', $money);
        } else {
            $res = M($this->member_table)->where(['id' => $uid])->setDec('can_get_money', $money);
        }
        return $res;
    }

    /**
     * 修改用户优惠券冻结状态
     * @param      array  $coupons  优惠券记录id
     * @param      int  $status   1：未使用、2:已使用
     * @return     <type>  ( description_of_the_return_value )
     */
    public function SetUserCouponsStatus($coupons, $status)
    {
        return M($this->user_coupons_table)->where(['id' => ['in', $coupons]])->save(['status' => $status]);
    }

    /**
     * 获取用户返利比例
     * @param      int   $uid    用户id
     * @return     integer  The rebate ratio.
     */
    public function GetRebateRatio($uid)
    {
        $userInfo  = $this->GetUserInfo($uid, 'shop_type');
        $ratio     = 0;
        $ratio_arr = [
            'salesman' => floatval(C('Shoppingmall_config.salesman_distribute_rate')),
            'entity'   => floatval(C('Shoppingmall_config.entity_distribute_rate')),
            'single'   => floatval(C('Shoppingmall_config.single_distribute_rate')),
        ];
        $ratio = !empty($userInfo) && isset($ratio_arr[$userInfo['shop_type']]) ? $ratio_arr[$userInfo['shop_type']] : 0;
        return $ratio;
    }
    /**
     * Sets the user service item.
     * @param      int  $uid    The uid
     * @param      int  $gid    The gid
     * @param      int  $num    The number
     * @return
     */
    public function SetUserServiceItem($uid, $gid, $num)
    {
        $model = M('user_service_item');
        $info  = $model->where(['uid' => $uid, 'goods_id' => $gid])->find();
        if ($info) {
            $res = $model->where(['uid' => $uid, 'goods_id' => $gid])->setInc('all_times', $num);
        } else {
            $res = $model->add(['goods_id' => $gid, 'uid' => $uid, 'all_times' => $num]);
        }
        return $res!==false ? 1 : 0;
    }

    private function GetUserModel()
    {
        if (!$this->user_model) {
            $this->user_model = M($this->member_table);
        }
        return $this->user_model;
    }
    /**
     * 修改用户购物卡余额
     * @param      int              $uid    用户id
     * @param      integer          $money  变动金额
     * @return     int 1:成功、0:失败
     */
    public function SetUserCardMoney($uid, $money)
    {
        if ($money == 0) {
            return 1;
        } else if ($money > 0) {
            $res = $this->GetUserModel()->where(['id' => $uid])->setInc('card_money', $money);
        } else {
            $res = $this->GetUserModel()->where(['id' => $uid])->setDec('card_money', abs($money));
        }
        return $res !== false ? 1 : 0;
    }

    /**
     * 修改用户服务卡余额
     * @param      int              $uid    用户id
     * @param      integer          $money  变动金额
     * @return     int 1:成功、0:失败
     */
    public function SetUserServiceMoney($uid, $money)
    {
        if ($money == 0) {
            return 1;
        } else if ($money > 0) {
            $res = $this->GetUserModel()->where(['id' => $uid])->setInc('service_money', $money);
        } else {
            $res = $this->GetUserModel()->where(['id' => $uid])->setDec('service_money', abs($money));
        }
        return $res !== false ? 1 : 0;
    }
    /**
     * 修改用户购物卡或服务卡
     * @param      int      $uid        用户id
     * @param      string   $title      修改内容描述
     * @param      int      $goods_id   商品id
     * @param      int      $order_id   订单id
     * @param      string   $card_type  卡类型
     * @param      int      $money      变动金额
     * @return     integer  0：失败、1：成功
     */
    public function card_money($uid, $title, $goods_id, $order_id, $card_type, $money)
    {
        if ($money == 0) {
            return 1;
        }
        $data = array(
            'uid'         => $uid,
            'type'        => $card_type,
            'order_id'    => $order_id,
            'title'       => $title,
            'money'       => $money,
            'create_time' => datetime(),
        );
        $res = M('card_logs')->add($data);
        if ($res === false) {
            return 0;
        }
        if ($money > 0) {
            //会员卡购买记录
            $data = array(
                'uid'         => $uid,
                'goods_id'    => $goods_id,
                'order_id'    => $order_id,
                'card_type'   => $card_type,
                'money'       => $money,
                'create_time' => datetime(),
            );
            $res = M('cardcharge')->add($data);
            if ($res === false) {
                return 0;
            }
        }
        switch ($card_type) {
            case 'card': //修改购物卡余额
                $res = $this->SetUserCardMoney($uid, $money);
                if ($res === false) {
                    return 0;
                }
                break;
            case 'service_card': //修改服务卡余额
                $res = $this->SetUserServiceMoney($uid, $money);
                if ($res === false) {
                    return 0;
                }
                break;
        }
        return 1;
    }
}
