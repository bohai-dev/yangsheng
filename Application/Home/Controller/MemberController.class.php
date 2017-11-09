<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Home\Controller;

use Think\Controller;

// use Addons\Alidayu\Controller;//阿里大鱼短信

/**
 * 前台默认控制器
 */
class MemberController extends BaseController
{
    public function _initialize()
    {
        parent::_initialize();
        $this->assign('gl', 5);
        if (!in_array(strtolower(ACTION_NAME), [
            'index_test',
            'customer',
            'help',
            'send',
            'miao_send',
            'login',
            'login_shoper',
            'register',
            'register_note',
            'register_send',
            'register_step2',
            'register_step3',
            'login_send',
            'forget_pwd',
            'forget_pwd_step2',
            'forgetpwd_send',
            'verify',
            'check_verify',
            'checkmobileexist',
        ])) {
            $this->check_login();
        }
        if (!in_array(strtolower(ACTION_NAME), ['address_add','address_edit'])) {
            $this->assign('back_url', U('Home/Member/Index'));
        }
        $this->assign('userInfo', $this->userInfo);
    }

    // 我的
    public function index()
    {
        // 获取粉丝数
        $ForumModel = new \Forum\Model\ForumModel();
        $fans =$ForumModel->get_fans($this->uid);
        $this->assign('fans',$fans);
        $this->assign('user',$this->userInfo);
        $this->assign('meta_title','个人中心');
        $this->display();
    }
    //  我的积分
    public  function  score(){
        if(IS_AJAX) {
            $page = I('post.n') ? I('post.n') : 1;
            $limit = 10;
            $start = ($page - 1) * $limit;
            $map['type'] =I('type',1);
            $map['uid'] =$this->uid;
            $record =ajax_data('user_score',$start,$limit,$map,'create_time desc');
            if($record){
                $this->assign('score_list',$record);
                $html =$this->fetch('ajax_score_list');
                $result =array('status'=>1,'msg'=>$html);
            }else{
                if($page ==1){
                    $result = array('status' => 0, 'msg' => '');
                }else {
                    $result = array('status' => 1, 'msg' => '');
                }
            }
            $this->ajaxReturn($result);
        }else{
            $user = $this->userInfo;
            $rate =M('shop_set')->getField('integral_rate');
            $moeny =floor($user['score']/$rate);
            $this->assign('money',$moeny);
            $this->assign('user',$this->userInfo);
            $this->assign('meta_title','我的积分');
            $this->display();
        }
    }

    // 曾经购买
    public  function  history_buy(){
        if(IS_AJAX){
            $page = I('post.n') ? I('post.n') : 1;
            $limit = 10;
            $start = ($page - 1) * $limit;
            $map = [
                'o.type'=>'normal',
                'o.uid'       => $this->uid,
                'o.status'    => 1,
            ];
            $map['o.checkinfo']=array('in',[2,3,4]);
            $record =M('shop_order_item as i')
                    ->field('g.*')
                    ->join('oc_shop_order as o on i.order_id=o.id')
                    ->join('oc_shop_goods as g on g.id =i.goods_id')
                    ->where($map)
                    ->order('i.id DESC')
                    ->limit($start,$limit)
                    ->select();
            if(is_array($record)){
                $record =assoc_unique($record,'id');
            }
            if($record){
                $this->assign('record',$record);
                $html =$this->fetch('ajax_history_buy_list');
                $result =array('status'=>1,'msg'=>$html);
            }else{
                if($page ==1){
                    $result = array('status' => 0, 'msg' => '');
                }else {
                    $result = array('status' => 1, 'msg' => '');
                }
            }
            $this->ajaxReturn($result);
        }else{
            $this->assign('meta_title','曾经购买');
            $this->display();
        }
    }
    // 我的优惠券
    public  function  coupons(){
        if(IS_AJAX){
            $page = I('post.n') ? I('post.n') : 1;
            $limit = 10;
            $start = ($page - 1) * $limit;
            $map['uid'] =$this->uid;
            $record =M('shop_coupon as c')
                    ->field('u.create_time,u.status,c.title,c.price,c.start_time,c.end_time')
                    ->join('left join oc_user_coupons as u  on u.cid =c.id')
                    ->where(['u.uid'=>$this->uid,'c.status'=>1])
                    ->limit($start,$limit)
                    ->order('u.create_time desc')
                    ->select();
            if($record){
                $this->assign('record',$record);
                $html =$this->fetch('ajax_coupons_list');
                $result =array('status'=>1,'msg'=>$html);
            }else{
                if($page ==1){
                    $result = array('status' => 0, 'msg' => '');
                }else {
                    $result = array('status' => 1, 'msg' => '');
                }
            }
            $this->ajaxReturn($result);
        }else{
            $can_count = M('shop_coupon as c')
                ->join('left join oc_user_coupons as u  on u.cid =c.id')
                ->where(['u.uid'=>$this->uid,'c.status'=>1,'u.status'=>1])
                ->count();
            $total = M('shop_coupon as c')
                ->join('left join oc_user_coupons as u  on u.cid =c.id')
                ->where(['u.uid'=>$this->uid,'c.status'=>1])
                ->count();
            $this->assign('can_count',$can_count);
            $this->assign('total',$total);
            $this->assign('meta_title','我的优惠券');
            $this->display();
        }
    }

    // 我的团队
    public  function  team(){
        $team= get_team_num($this->uid);
        $team['total'] =$team['pfirstid']+$team['psecondid'] +$team['pthirdid'];
        $this->assign('team',$team);
            $this->assign('meta_title','我的团队');
            $this->display();
    }
    // 团队详情
    public  function  team_detail(){
        $level =I('level','');
        switch($level){
            case 1:
                $field ='pfirstid';
                break;
            case 2:
                $field ='psecondid';
                break;
            default:
                $field ='pthirdid';

        }
        $team_list =M('admin_user')->where([$field=>$this->uid])->select();
        $this->assign('team',$team_list);
        $this->assign('meta_title','艾玛莎基');
        $this->display();
    }
    // 我的二维码
    public  function  qrcode(){
        $user =$this->userInfo;
        if(empty($user['qrcode'])){
            // 生成二维码
            $qrcode =get_qrcode($user['id']);
            M('admin_user')->where(['id'=>$user['id']])->save(['qrcode'=>$qrcode['id']]);
            $user =M('admin_user')->where(['id'=>$user['id']])->find();
        }
        $this->assign('user',$user);
        $this->assign('meta_title','我的二维码');
        $this->display();
    }

    // 我的帖子
    public  function posts(){
        if(IS_AJAX){
            $page = I('post.n') ? I('post.n') : 1;
            $limit = 10;
            $start = ($page - 1) * $limit;
            $map['uid'] =$this->uid;
            // $map['status'] =1;
            $record =ajax_data('forum_posts',$start,$limit,$map,'time desc');
            $attention =M('forum_attention')->where(['uid'=>$this->uid,'status'=>1])->getField('attention_userid',true);
            $like =M('forum_like')->where(['uid'=>$this->uid])->getField('forum_id',true);
            foreach($record as $key=>$v){
                if(empty($attention)){
                    $record[$key]['attention'] =0;
                }else{
                    if(in_array($v['uid'],$attention)){
                        $record[$key]['attention'] =1;
                    }else{
                        $record[$key]['attention'] =0;
                    }
                }
                if(empty($like)){
                    $record[$key]['like'] =0;
                }else{
                    if(in_array($v['id'],$like)){
                        $record[$key]['like'] =1;
                    }else{
                        $record[$key]['like'] =0;
                    }
                }
                $user =get_user_info($v['uid'],'admin_user');
                $record[$key]['username'] =$user['nickname'];
                $record[$key]['avatar'] =getpics($user['avatar']);
                $record[$key]['date_time']  =time_difference(strtotime($v['time']));
                $record[$key]['comment'] =get_total_comment($v['id'],3);
                if(!empty($v['pics'])){
                    $record[$key]['pics'] =explode(',',$v['pics']);
                }
            }
            if($record){
                $this->assign('record',$record);
                $html =$this->fetch('ajax_posts_list');
                $result =array('status'=>1,'msg'=>$html);
            }else{
                if($page ==1){
                    $result = array('status' => 0, 'msg' => '');
                }else {
                    $result = array('status' => 1, 'msg' => '');
                }
            }
            $this->ajaxReturn($result);
        }else{
            $this->assign('user',$this->userInfo);
            $this->assign('meta_title','我的帖子');
            $this->display();
        }
    }


    public  function  posts_delete(){
        if(IS_AJAX){
            $id =I('post.id');
            $delete =M('forum_posts')->where(['id'=>$id])->delete();
            if($delete){
                $this->success('删除成功');
            }else{
                $this->success('删除失败');
            }
        }
    }

    // 我的出租
    public  function  rent(){
        if(IS_AJAX){
            $page = I('post.n') ? I('post.n') : 1;
            $limit = 10;
            $start = ($page - 1) * $limit;
            // $map['status'] =1;
            $map['uid'] =$this->uid;
            $record =ajax_data('forum_rent',$start,$limit,$map,'time desc');
            if($record){
                $this->assign('member',1);
                $this->assign('record',$record);
                $html =$this->fetch(T('Home@Member/ajax_rent_list'));
                $result =array('status'=>1,'msg'=>$html);
            }else{
                if($page ==1){
                    $result = array('status' => 0, 'msg' => '');
                }else {
                    $result = array('status' => 1, 'msg' => '');
                }
            }
            $this->ajaxReturn($result);
        }else{
            $this->assign('user',$this->userInfo);
            $this->assign('meta_title','我的出租');
            $this->display();
        }
    }
    public  function  rent_delete(){
        if(IS_AJAX){
            $id = I('id');
            $delete =M('forum_rent')->where(['id'=>$id])->delete();
            if($delete){
                $this->success('删除成功');
            }else{
                $this->success('删除失败');
            }
        }
    }

    public  function  job(){
        if(IS_AJAX) {
            $page = I('post.n') ? I('post.n') : 1;
            $limit = 10;
            $start = ($page - 1) * $limit;
            // $map['status'] =1;
            $map['uid'] =$this->uid;
            $record =ajax_data('forum_job',$start,$limit,$map,'time desc');
            foreach($record as $key=>$v){
                switch($v['sex']){
                    case 1:
                        $record[$key]['sex'] ='男';
                        break;
                    case 2:
                        $record[$key]['sex'] ='女';
                        break;
                    default:
                        $record[$key]['sex'] ='男女不限';
                }
            }
            if($record){
                $this->assign('member',1);
                $this->assign('record',$record);
                $html =$this->fetch(T('Home@Member/ajax_job_list'));
                $result =array('status'=>1,'msg'=>$html);
            }else{
                if($page ==1){
                    $result = array('status' => 0, 'msg' => '');
                }else {
                    $result = array('status' => 1, 'msg' => '');
                }
            }
            $this->ajaxReturn($result);
        }else{
            $this->assign('meta_title','我的招聘');
            $this->display();
        }
    }

    public  function  job_delete(){
        if(IS_AJAX){
            $id = I('id');
            $delete =M('forum_job')->where(['id'=>$id])->delete();
            if($delete){
                $this->success('删除成功');
            }else{
                $this->success('删除失败');
            }
        }
    }

    // 我的评论
    public  function  comment(){
        if(IS_AJAX){
            $page = I('post.n') ? I('post.n') : 1;
            $limit = 10;
            $start = ($page - 1) * $limit;
            $checkinfo  = I('checkinfo',1);
            $uid = $this->uid;
            switch ($checkinfo) {
                case '1':
                    $map['status'] =1;
                    $map['uid'] =$this->uid;
                    $record =ajax_data('user_comment',$start,$limit,$map,'comment_time desc');
                    # code...
                    break;
                case '2':
                    $map['oc_shop_goods_review.uid']=$uid;
                    $map['oc_shop_goods_review.status'] =1;
                    $record = M('shop_goods_review')
                            ->join('oc_user ON oc_shop_goods_review.uid = oc_user.admin_uid','LEFT')
                            ->field('oc_shop_goods_review.content,
                                     oc_shop_goods_review.reply,
                                     oc_shop_goods_review.rating,
                                     oc_shop_goods_review.create_time,
                                     oc_user.nickname,
                                     oc_user.headimgurl')
                            ->where($map)
                            ->limit($start,$limit)
                            ->order('oc_shop_goods_review.id DESC')
                            ->select();
                    break;
                case '3':
                    $map['oc_shop_bulletin_review.uid']=$uid;
                    $map['oc_shop_bulletin_review.status'] =1;
                    $record = M('shop_bulletin_review')
                                ->join('oc_user ON oc_shop_bulletin_review.uid = oc_user.admin_uid','LEFT')
                                ->field('oc_shop_bulletin_review.content,
                                       oc_shop_bulletin_review.reply,
                                       oc_shop_bulletin_review.create_time,
                                       oc_user.nickname,
                                       oc_user.headimgurl')
                                ->where($map)
                                ->limit($start,$limit)
                                ->order('oc_shop_bulletin_review.id DESC')
                                ->select();
                    break;
                default:
                    # code...
                    break;
            }

            if($record){
                $this->assign('checkinfo',$checkinfo);
                $this->assign('record',$record);
                $html =$this->fetch(T('Home@Member/ajax_comment_list'));
                $result =array('status'=>1,'msg'=>$html);
            }else{
                if($page ==1){
                    $result = array('status' => 0, 'msg' => '');
                }else {
                    $result = array('status' => 1, 'msg' => '');
                }
            }
            $this->ajaxReturn($result);
        }else{
            $this->meta_title ='我的评论';
            $this->assign('checkinfo',1);
            $this->display();
        }
    }

    // 我的收藏
    public  function  collect(){
        if(IS_AJAX){
            $page = I('post.n') ? I('post.n') : 1;
            $limit = 10;
            $start = ($page - 1) * $limit;
            $uid = $this->uid;
            $checkinfo = I('post.checkinfo',1); //1 商品 2 头条
            if($checkinfo ==2){
                $map['oc_shop_bulletin_collect.uid'] = $uid;
                $map['oc_shop_bulletin.status'] = 1;
                $info = M('shop_bulletin_collect')
                            ->join('oc_shop_bulletin ON oc_shop_bulletin.id =
                                    oc_shop_bulletin_collect.bid','LEFT')
                            ->field('oc_shop_bulletin.title,
                                     oc_shop_bulletin.pictures,
                                     oc_shop_bulletin.create_time,
                                     oc_shop_bulletin.id
                                     ')
                            ->where($map)
                            ->limit($start,$limit)
                            ->order('oc_shop_bulletin_collect.create_time desc')
                            ->select();
                foreach ($info as $key => $value) {
                    $info[$key]['posttime'] = time_difference(strtotime($value['create_time']));
                    $info[$key]['check'] = 0;
                    $covers =  array_slice(explode(',', $value['pictures']),0, 3);
                    $info[$key]['cover'] =$covers;
                    if(count($covers)>1){
                      $info[$key]['check']=1;
                    }
                }

            }else{
                $map['c.uid']=$uid;
                $map['g.status']=1;
                $info =M('shop_goods_collect as c')
                    ->field('g.cover,c.gid,c.id,g.title,g.group,g.original_price,sale_price')
                    ->join('left join oc_shop_goods as g on c.gid = g.id')
                    ->where($map)
                    ->limit($start,$limit)
                    ->order('c.create_time desc')
                    ->select();
            }

            if($info){
                $this->assign('checkinfo',$checkinfo);
                $this->assign('info',$info);
                $html =$this->fetch(T('Home@Member/ajax_collect_list'));
                $result =array('status'=>1,'msg'=>$html);
            }else{
                if($page ==1){
                    $result = array('status' => 0, 'msg' => '');
                }else {
                    $result = array('status' => 1, 'msg' => '');
                }
            }
            $this->ajaxReturn($result);
        }else{
            $checkinfo = I('checkinfo',1);
            $this->assign('checkinfo',$checkinfo);
            $this->meta_title ='我的收藏';
            $this->display();
        }
    }

    // 浏览足记
    public  function  browse(){
        if(IS_AJAX){
            $page = I('post.n') ? I('post.n') : 1;
            $user =M('user')->where(['admin_uid'=>$this->uid])->find();
            $limit = 10;
            $start = ($page - 1) * $limit;
            $map['c.uid']=$user['id'];
            $map['g.status']=1;
            $info =M('shop_goods_browse as c')
                ->field('g.cover,c.gid,c.id,g.title,g.group,g.original_price,sale_price')
                ->join('left join oc_shop_goods as g on c.gid = g.id')
                ->where($map)
                ->limit($start,$limit)
                ->order('c.update_time desc')
                ->select();


            if($info){
                $this->assign('info',$info);
                $html =$this->fetch(T('Home@Member/ajax_browse_list'));
                $result =array('status'=>1,'msg'=>$html);
            }else{
                if($page ==1){
                    $result = array('status' => 0, 'msg' => '');
                }else {
                    $result = array('status' => 1, 'msg' => '');
                }
            }
            $this->ajaxReturn($result);
        }else{
            $this->meta_title ='浏览足迹';
            $this->display();
        }
    }

    // 我的佣金
    public  function  commission(){
        $this->assign('user',$this->userInfo);
        $money_list =M('user_money')->where(['uid'=>$this->uid])->order('create_time desc')->select();
        $money_total =M('user_money')->where(['uid'=>$this->uid])->sum('money');
        $this->assign('total_money',$money_total?: 0);
        $this->assign('list',$money_list);
        $withdraw_list =M('user_withdraw')->where(['uid'=>$this->uid])->order('create_time desc')->select();
        $withdraw_total =M('user_withdraw')->where(['uid'=>$this->uid])->sum('money');
        $this->assign('withdraw_list',$withdraw_list);
        $this->assign('withdraw_total',$withdraw_total?: 0);
        $this->meta_title ='我的佣金';
        $this->display();
    }

    // 申请提现
    public  function  apply(){
        if(IS_AJAX){
            $money =floatval(I('money',0));
            if($money<C('least_money')){
                $this->error('提现金额必须大于'.C('least_money').'元');
            }
            $user =$this->userInfo;
            if($money>$user['money']){
                $this->error('您最多可提现'.$user['money'].'元');
            }
            D()->startTrans();
            $add_data =[
                'uid'=>$this->uid,
                'money'=>$money,
                'status'=>1,
                'create_time'=>datetime(),
            ];
            $add =M('user_withdraw')->add($add_data);
            if(!$add){
                goto commit;
            }
            $update_money =M('admin_user')->where(['id'=>$user['id']])->setDec('money',$money);
            if(!$update_money){
                goto commit;
            }
            commit:
            if($add_data && $update_money){
                D()->commit();
                $this->success('提现成功，请等待审核');
            }else{
                D()->rollback();
                $this->success('提现失败，请稍候重试');
            }
        }else{
            $this->assign('user',$this->userInfo);
            $this->meta_title ='申请提现';
            $this->display();
        }
    }
    // 我的关注
    public  function  attention(){
        $uid =I('get.id',$this->uid);
        $model =M('forum_attention');
        $my_attention =$model->where(['uid'=>$uid,'status'=>1])->select();
        $my_fans =$model->where(['attention_userid'=>$uid,'status'=>1])->select();
        $attention =M('forum_attention')->where(['uid'=>$uid,'status'=>1])->getField('attention_userid',true);
        foreach($my_fans as $key=>$v){
            if(empty($attention)){
                $my_fans[$key]['attention'] =0;
            }else{
                if(in_array($v['uid'],$attention)){
                    $my_fans[$key]['attention'] =1;
                }else{
                    $my_fans[$key]['attention'] =0;
                }
            }
        }
        if($uid==$this->uid){
            $type =0;
        }else{
            $type =1;
        }
        $this->assign('type',$type);
        $this->assign('attention',$my_attention);
        $this->assign('fans',$my_fans);
        if($type==0){
            $this->meta_title ='我的关注';
        }else{
            $this->meta_title ='TA的关注';
        }
        $this->display();
    }
    // 我的
    public function index_test()
    {
        $this->meta_title = L('lg_user_center');
        session('from', null);
        $template = $this->userInfo['allow_front'] == 0 ? 'index_test' : 'index_shoper';
        $this->display($template);
    }
    // 投诉意见
    public  function complain(){
        if(IS_AJAX){
            $post =I('post.');
            $post['uid'] =$this->uid;
            if(isset($post['pics']) && !empty($post['pics'])){
                $post['pics'] =implode(',',$post['pics']);
            }
            $post['time'] = datetime();
            $add =M('user_complain')->add($post);
            if($add){
                $this->success('您的宝贵意见发送成功');
            }else{
                $this->error('您的宝贵意见发送失败');
            }
        }else{
            $tel =M('shop_set')->where(['id'=>1])->getField('shop_mobile');
            $this->assign('tel',$tel);
            $jsapi = R('Home/Weixin/jsapi', [['chooseImage', 'uploadImage','onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone']]);
            $this->assign('jsapi', $jsapi);
            $this->meta_title ='投诉意见';
            $this->display();
        }
    }
    // 系统通知
    public  function  notice(){
        $model =new \Admin\Model\NoticeModel();
        $notice =$model->get_list();
        $this->assign('notice',$notice);
        $this->meta_title ='系统通知';
        $this->display();
    }

    // 系统通知
    public  function  notice_brow($nid){
        $data_info = M('shop_notice')->find($nid);
        $this->assign('data_info',$data_info);
        $this->meta_title ='系统通知';
        $this->display();
    }
    // 信息管理
    public function info()
    {
        $this->meta_title = L('lg_info_management');
        $this->display();
    }

    // 登录
    public function login()
    {

        if (IS_POST) {
            vendor('Common.Util.Member');
            $post   = I('post.');
            $member = new \Common\Util\Member();
            $res    = $member->login($post, 'mobile');
            if ($res['status']) {
                if ($before_reg = cookie('before_reg')) {
                    cookie('before_reg', null);
                    $this->success($res['info'], $before_reg);
                } else {
                    $this->success($res['info'], U('Home/Member/index'));
                }
            } else {
                $this->error($res['info']);
            }
        } else {
            // 检测是否登录过
            $this->has_login();
            if ($uid = cookie('uid') && $this->uid) {
                $this->redirect('Home/Member/index');
                die;
            }
            $this->meta_title = '登陆';
            $this->display();
        }
    }
    // 已经登录后的跳转
    public function has_login()
    {
        $uid = cookie('uid');
        if ($uid && $this->uid) {
            $this->redirect('Home/Member/index');
            return true;
        } else {
            return false;
        }
    }
    // 注册须知
    public function register_note()
    {
        $this->meta_title = L('lg_title_register_notice');
        $this->display();
    }

    // 注册
    public function register()
    {
        $this->has_login();
        if (IS_POST) {
            $post        = I('post.');
            $mobile      = $post['mobile']      = trim($post['mobile']);
            $verify_code = cookie($post['mobile'] . '_codenum');
            if ($exist = M('admin_user')->where(['mobile' => $post['mobile']])->getField('id')) {
                $this->error('该手机号已被使用');
            }
            if (!$verify_code) {
                $this->error('验证码失效，请重新发送');
            }
            if ($verify_code !== $post['verify_code']) {
                $this->error('验证码错误');
            }
            $member           = new \Common\Util\Member();
            $post['reg_type'] = 'front';
            if ($recUid = cookie('recUid')) {
                $post['recUid'] = $recUid;
            }
            $res = $member->register($post, 'mobile');
            if ($res['status']) {
                $this->success($res['info'], U('index'));
            } else {
                $this->error($res['info']);
            }
        } else {
            $this->meta_title ='注册';
            $this->display();
        }

    }
    // 检测手机号是否存在
    public function checkMobileExist($type = 'front')
    {
        $mobile = I('mobile');
        $map    = ['mobile' => $mobile];
        if ($type != 'front') {
            $map['allow_front'] = 1;
        }
        $exist = M('admin_user')->where($map)->find();
        if ($exist) {
            $this->ajaxReturn(['status' => 1, 'exist' => 1]);
        } else {
            $this->ajaxReturn(['status' => 1, 'exist' => 0]);
        }
        exit;
    }

    // 登出
    public function logout()
    {
        session('user_auth', null);
        session('user_auth_sign', null);
        cookie('uid', null);
        cookie('mobile', null);
        cookie('before_reg', null);
        cookie('before_reg_admin', null);
        $this->success('退出成功', U('Home/Member/login', [], false, true));
    }

    // 忘记密码
    public function forget_pwd()
    {
        if (IS_POST) {
            $mobile      = I('mobile');
            $verify_code = cookie($mobile . '_codenum');
            $password    = I('password');
            if (!$mobile) {
                $this->error('请填写手机号');
            }
            if (!$verify_code) {
                $this->error('验证码失效，请重新发送');
            }
            if ($verify_code !== I('verify_code')) {
                $this->error('验证码错误');
            } else {
                $model  = D('Admin/User');
                $member = $model->where(['mobile' => $mobile])->find();
                if (!$member) {
                    $this->error('没有该手机号对应的用户');
                }
                $ret = $model->where(['mobile' => $mobile])->save(['password' => user_md5(I('password'))]);
                if (false !== $ret) {
                    cookie('before_reg', null);
                    $this->success('修改成功');
                } else {
                    $this->error($model->getError());
                }
            }
        } else {
            $this->assign('_home_public_layout', 'Base/layout_login');
            $this->meta_title = '手机验证';
            $this->display();
        }
    }
    // 注册时短信
    public function register_send()
    {
        $mobile = I('mobile', '');
        if ($exist = M('admin_user')->where(['mobile' => $mobile])->getField('id')) {
            $this->error('该手机号已被使用');
        }
        parent::send();
    }

    // 忘记密码时短信
    public function forgetpwd_send()
    {
        $mobile = I('mobile', '');
        if (!$exist = M('admin_user')->where(['mobile' => $mobile])->getField('id')) {
            $this->error('该手机号不存在');
        }
        parent::send();
    }

    // 注册时短信
    public function login_send()
    {
        $mobile = I('mobile', '');
        if (!$exist = M('admin_user')->where(['mobile' => $mobile])->getField('id')) {
            $this->error('该手机号不存在');
        }
        parent::send();
    }

    // 设置
    public function setting()
    {
        if (IS_POST) {
            $data = I('post.');
            if(empty($data['password'])){
                unset($data['password']);
            }else{
                $data['password'] =user_md5($data['password']);
            }
            $uid =$this->uid;
            $userModel =M('admin_user');
            // 检测手机重复
             $existMobile = $userModel->where(['mobile' => $data['mobile'], 'id' => ['neq', $uid]])->getField('id');
             if ($existMobile) {
                 $this->error('手机号已存在');
             }
            $ret = $userModel->where(['id' => $uid])->field('avatar,mobile,nickname,password')->save($data);
            if (false !== $ret) {
                $this->success('修改成功');
            } else {
                $this->error('修改失败');
            }
        } else {
            cookie('from',null);
            $jsapi = R('Home/Weixin/jsapi', [['chooseImage', 'uploadImage','onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone']]);
            $this->assign('jsapi', $jsapi);
            $this->assign('user',$this->userInfo);
            $this->meta_title = '个人信息';
            $this->display();
        }
    }

    //地址文本获取地理坐标
    public function getGeo($address)
    {
        return getGeo($address);
    }

    // 修改登录密码
    public function change_login_pass()
    {
        if (IS_POST) {
            $data        = I('post.');
            $uid         = $this->uid;
            $userModel   = M('admin_user');
            $mobile      = I('mobile');
            $verify_code = cookie($mobile . '_codenum');
            $password    = I('password');
            if (!$mobile) {
                $this->error('请填写手机号');
            }
            if (!$password) {
                $this->error('请填写新密码');
            }
            if (!$verify_code) {
                $this->error('验证码失效，请重新发送');
            }
            if ($verify_code !== I('verify_code')) {
                $this->error('验证码错误');
            } else {
                $ret = $userModel->where(['id' => $uid])->field('password')->save(['password' => user_md5($data['password'])]);
                if (false !== $ret) {
                    $this->success('修改成功', U('setting'));
                } else {
                    pubu("更新admin_user表{$this->openid}|{$this->uid}的用户更新资料失败：" . $userModel->getError());
                    $this->error('修改失败');
                }
            }
        } else {
            $this->assign('meta_title', '修改登录密码');
            $this->display();
        }
    }


    //我的预订
    public function booking_order()
    {
        C('HOME_PUBLIC_LAYOUT', 'Base/layout');
        $this->assign('body_class', 'bg_1');
        $list = D('Appointment')->where(['uid' => $this->uid, 'lang' => $this->lang])->order('start_time ASC')->select();
        $this->assign('list', $list);
        $this->meta_title = '我的预订';
        $this->display();
    }

    //我的账单
    public function money_log()
    {
        $list = M('user_money_log')->where(['uid' => $this->uid])->order('create_time DESC')->select();
        $time = array();
        foreach ($list as $key => &$value) {
            if ($value['create_time']) {
                $time[]        = $value['order_time']        = date('Y年m月', strtotime($value['create_time']));
                $value['date'] = date('m/j', strtotime($value['create_time']));
                $value['time'] = date('H:i', strtotime($value['create_time']));
                $value['type'] = ($value['relate_type'] == 'order') ? 1 : 2;
            }
        }
        $time  = array_unique($time);
        $list2 = [];
        foreach ($time as $val) {
            $list2[$val] = list_search($list, ['order_time' => $val]);
        }
        C('HOME_PUBLIC_LAYOUT', 'Base/layout');
        $this->assign('body_class', 'bg_1');
        $this->assign('list', $list2);
        $this->meta_title = '我的账单';
        $this->display();
    }

    // 创建订单 商家
    public function create_pay_order()
    {
        if (IS_POST) {
            $total         = I('total');
            $ordernum      = getOrderNo();
            $restaurant_id = $this->userInfo['restaurant_id'];
            $new_order     = M('order')->add([
                'type'        => 'card_pay',
                'uid'         => 0,
                'lang'        => $this->lang,
                'ordernum'    => $ordernum,
                'openid'      => '',
                'pay_type'    => 3,
                'payment'     => 0,
                'total'       => $total,
                'gift_money'  => 0,
                'status'      => 1,
                'create_time' => datetime(),
                'pay_time'    => null,
            ]);
            if ($new_order) {
                $id = card_pay(0, '礼卡支付扣除', 0, 0, 'goods_user', $total, null, $ordernum, $restaurant_id, $this->uid);
                $this->success('', '', ['id' => $id]);
            } else {
                $this->error('创建卡支付订单失败');
            }
        } else {
            C('HOME_PUBLIC_LAYOUT', 'Base/layout');
            if ($this->platform == 'weixin') {
                $jsapi = front_r('Home/Weixin/jsapi', [['scanQRCode']]);
                // ptrace($jsapi);
                $this->assign('jsapi', $jsapi);
            }
            $this->meta_title = '扫一扫';
            $this->display();
        }
    }

    /**
     * 扫码之后  商家
     * @param  string $pay_code 支付码
     * @param  int $id   支付订单 user_money_log 表记录id
     */
    public function after_scan($pay_code, $id)
    {
        $goods_user_model = M('goods_user');
        $card             = $goods_user_model->where(['pay_code' => $pay_code])->find();
        if (!$card) {
            $this->error('支付码已过期');
        }
        if ($card['status'] == 0 || $card['left_money'] == 0) {
            $this->error('该卡已作废');
        }
        $money_log = M('user_money_log')->find($id);
        if (!$money_log) {
            $this->error('支付订单无效，请重新下单');
        }
        $total = $money_log['total'];
        if ($total == 0) {
            $this->error('订单金额不能为0，无效订单，请重新下单');
        }
        if ($card['left_money'] < $total) {
            $ret['cost_money'] = $card['left_money'];
            $ret['need_money'] = $total - $card['left_money'];
        } else {
            $ret['cost_money'] = $total;
            $ret['need_money'] = 0;
        }
        $goods_user_model->startTrans();
        $update_data = [
            'left_money' => ['exp', "left_money-{$ret['cost_money']}"],
        ];
        if ($card['goods_type'] == 'gift') {
            $update_data['status'] = 0;
        } else {
            if ($card['left_money'] <= $total) {
                $update_data['status'] = 0;
            }
        }
        $update_goods_user = $goods_user_model->where(['id' => $card['id']])->save($update_data);
        $update_money_log  = M('user_money_log')->where(['id' => $id])->save(
            [
                'uid'        => $card['uid'],
                'money'      => $ret['cost_money'],
                'relate_id'  => $card['id'],
                'pay_time'   => datetime(),
                'left_money' => $goods_user_model->where(['id' => $card['id']])->getField('left_money'),
            ]
        );
        $update_order = M('order')->where(['ordernum' => $money_log['ordernum']])->save(
            [
                'pay_time' => datetime(),
                'status'   => 2,
                'payment'  => $ret['cost_money'],
            ]
        );
        if ($update_goods_user !== false && $update_money_log !== false) {
            $card_user       = M('admin_user')->find($card['uid']);
            $ret['realname'] = $card_user['firstname'] . $card_user['lastname'];
            $goods_user_model->commit();
            $this->success('', '', ['data' => $ret]);
        } else {
            $goods_user_model->rollback();
            $this->error('支付失败，请重新下单尝试');
        }
    }

    //取消预订
    public function cancel_order()
    {
        if (IS_AJAX) {
            $id  = I('post.id');
            $res = M('appointment')->where(['id' => $id])->save(['status' => -1, 'cancel_time' => datetime()]);
            if ($res) {
                $this->ajaxReturn(['status' => 1]);
            } else {
                $this->ajaxReturn(['status' => 0]);
            }
        }
    }

    // 商家登录
    public function login_shoper()
    {
        $this->has_login();
        if (IS_POST) {
            $post = I('post.');
            if (!$exist = M('admin_user')->where(['phone' => $post['mobile']])->getField('id')) {
                $this->error('该手机号不存在');
            }
            $code = I('post.code', '');
            if ($code && !$this->check_verify($code, 2)) {
                $this->error('图形验证码不正确');
            }
            $member = new \Common\Util\User($this->platform);
            $res    = $member->login($post, 'shop_mobile');
            if ($res['status']) {
                $intial_pass = C('INITiAL_PASSWORD');
                if ($post['password'] == $intial_pass) {
                    $this->redirect('forget_pass_shoper', [], 3, '初次登录需重设密码');
                }
                $this->success($res['info'], U('Home/Member/index'));
            } else {
                $this->error($res['info']);
            }
        } else {
            $this->meta_title = '登录';
            $this->display();
        }
    }

    // 验证码
    public function verify($vid = 2)
    {
        parent::verify($vid);
    }

    // 餐厅预订-商家
    public function appointment($type = 1)
    {
        C('HOME_PUBLIC_LAYOUT', 'Base/layout');
        $this->assign('bg_class', 'bg_1');
        $this->meta_title = '预订信息';
        switch ($type) {
            //未接受
            case 1:
                $status = 1;
                break;
            // 全部
            case 2:
                $status = ['gt', -2];
                break;
            // 已接受
            case 3:
                $status = ['gt', 1];
                break;
        }
        $this->assign('type', $type);
        $list = D('Appointment')->where([
            'status'        => $status,
            'restaurant_id' => $this->userInfo['restaurant_id'],
        ])->order('id DESC')->select(['viewer' => 'shoper']);
        $this->ajaxPage($list, 5, 'list', ['type' => $type], 'body', '.page', 'appointment_list');
        if (!IS_AJAX) {
            $this->display('');
        }
    }

    // 修改状态
    public function appointment_change_status($id, $status)
    {
        $model  = M('appointment');
        $record = $model->find($id);
        if (!$record) {
            $this->error('记录不存在');
        }
        if ($record['restaurant_id'] != $this->userInfo['restaurant_id']) {
            $this->error('只能修改自己餐厅的预订');
        }
        switch ($status) {
            case 'confirm_cancel':
                if (!$record['handle_time']) {
                    $model->where(['id' => $id])->save(['handle_time' => datetime(), 'cancel_time' => datetime()]);
                }
                $this->success('确认取消成功');
                break;
            case 'confirm_accept':
                $ret = $model->where(['id' => $id])->save(['handle_time' => datetime(), 'status' => 2]);
                if (false !== $ret) {
                    $this->success('确定接受预订成功');
                } else {
                    $this->error('操作失败');
                }
                break;
            case 'confirm_refuse':
                $ret = $model->where(['id' => $id])->save(['handle_time' => datetime(), 'status' => 0]);
                if (false !== $ret) {
                    $this->success('确定拒绝预订成功');
                } else {
                    $this->error('操作失败');
                }
                break;
            case 'confirm_arrive':
                $ret = $model->where(['id' => $id])->save(['handle_time' => datetime(), 'status' => 4, 'finish_time' => datetime()]);
                if (false !== $ret) {
                    $this->success('确定到店');
                } else {
                    $this->error('操作失败');
                }
                break;
            case 'confirm_no_arrive':
                $ret = $model->where(['id' => $id])->save(['handle_time' => datetime(), 'status' => 3, 'finish_time' => datetime()]);
                if (false !== $ret) {
                    $this->success('确定接受预订成功');
                } else {
                    $this->error('操作失败');
                }
                break;
            default:
                $this->error('未知的状态修改');
                break;
        }
    }

    // 消费记录
    public function money_flow()
    {
        $model = D('UserMoneyLog');
        C('HOME_PUBLIC_LAYOUT', 'Base/layout');
        $map = [
            'relate_type'   => 'goods_user',
            'total'         => ['gt', 0],
            'pay_time'      => ['exp', 'IS NOT NULL'],
            'restaurant_id' => $this->userInfo['restaurant_id'],
        ];
        $list             = $model->where($map)->order('pay_time DESC')->select();
        $total            = $model->where($map)->sum('total');
        $this->meta_title = '消费记录';
        $this->assign('total', $total);
        $this->assign('list', $list);
        $this->display();
    }

       /*
     * 地址列表
     */
    public function address()
    {
        $uid       = cookie('uid');
        //所有地址
        $addresses = M('shop_address')
                    ->where(['uid' => $uid])
                    ->order('update_time DESC,id DESC')->select();
        $de_address='';
        foreach ($addresses as $key => $value) {
           if($value['default']==1){
            $de_address = $value;
            unset($addresses[$key]);
           }
        }
        $from =I('get.from','');
        if(empty($from)){
            $from  =cookie('from');
        }else{
            $from =U('Shop/Award/my_award');
        }
        $this->assign('de_address',$de_address);
        $this->assign('from', $from); //跳转页面
        $this->assign('addresses', $addresses);
        $this->display();
    }

    /*
     * 添加地址
     */
    public function address_add()
    {
        $model = M('shop_address');
        $uid   = $this->uid;
        if (IS_POST) {
            $data = $model->create();
            if ($data) {
                $area        = I('area', '', 'trim');
                $data['uid'] = $uid;
                list($data['prov'], $data['city'], $data['country']) = explode(' ', $area);
                //如果之前没地址，自动设为默认
                $old_address = $model->where(['uid' => $uid, 'default' => 1])->count('id');
                if (!$old_address) {
                    $data['default'] = 1;
                    cookie('default_address_id', null);
                }
                $id = $model->add($data);
                if ($id) {
                    if (!empty($data['default']) && $data['default'] == 1) {
                        $res1 = $model->where(['uid' => $uid, ['id' => ['neq', $id]]])->save(['default' => 0]);
                    }
                    cookie('default_address_id', $id, 20);
                    $from = cookie('from');
                    $jump = $from ?: U('address');
                    $this->success('新增成功', $jump);
                } else {
                    $this->error('新增失败：' . $model->getError());
                }
            } else {
                $this->error('新增失败:' . $model->getError());
            }
        } else {

            $this->display();
        }
    }

    // 编辑地址
    public function address_edit($id)
    {
        $model = M('shop_address');
        $uid = cookie('uid');
        if (IS_POST) {

            $data = $model->create();
            if ($data) {
                $area      = I('area', '', 'trim');
                $data['uid']        = $uid;
                list($data['prov'], $data['city'], $data['country']) = explode(' ', $area);

                if(!empty($data['default'])){
                    $de_res = $model->where(['uid'=>$uid])->save(['default'=>0]);
                }
                $res = $model->where(['id' => $id, 'uid' => $uid])->save($data);
                if (false !== $res) {
                    cookie('default_address_id', $id, 20);//结算页 选择地址
                    $from = cookie('address_jump_url');
                    $jump = $from ?: U('address');
                    $this->success('更新成功', $jump);
                } else {
                    $this->error('更新失败：' . $model->getError());
                }
            } else {
                $this->error('更新失败:' . $model->getError());
            }
        } else {

            $this->assign('address', $model->find($id));
            $this->display();
        }
    }

    // 删除地址
    public function address_del($id)
    {
        $model = M('shop_address');
        $uid = cookie('uid');
        $model->startTrans();
        if ($model->delete($id)) {
            //判断是否还有默认地址
            if (!$default = $model->where(['uid' => $uid, 'default' => 1])->count('id')) {
                //随便挑一个出来 设为默认
                if ($exist = $model->where(['uid' => $uid])->getField('id')) {
                    if (false !== $model->where(['id' => $exist])->save(['default' => 1, 'update_time' => datetime()])) {
                        goto ok;
                    } else {
                        goto fail;
                    }
                } else {
                    goto ok;
                }
            } else {
                goto ok;
            }
        } else {
            goto fail;
        }
        ok:
        $model->commit();
        $this->success('删除成功');
        exit;
        fail:
        $model->rollback();
        $this->error('删除失败');
    }

    // 设为默认地址
    public function address_set_default()
    {
        $id     = I('id');
        $uid    = cookie('uid');
        $model  = M('shop_address');
        $record = $model->find($id);
        if (!$record) {
            $this->error('地址不存在，无法编辑');
        } else {
            if ($record['uid'] != $uid) {
                $this->error('非本人的地址无法编辑');
            } else {
                $model->startTrans();
                $res1 = $model->where(['uid' => $uid, ['id' => ['neq', $id]]])->save(['default' => 0]);
                $res2 = $model->where(['id' => $id])->save(['default' => 1]);
                if (false !== $res1 && false !== $res2) {
                    $model->commit();
                    //$from = session('from');
                    //$jump = $from ?: '';
                    $jump = ''; //不跳转
                    //session('from',null);
                    $this->success('设置成功', $jump);
                } else {
                    pubu("将id : {$id}的地址设为默认失败：" . $model->getError());
                    $model->rollback();
                    $this->error('设置失败');
                }
            }
        }
    }
}
