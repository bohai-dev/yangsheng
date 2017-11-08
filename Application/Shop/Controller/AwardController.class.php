<?php
/**
 * Created by PhpStorm.
 * User: 水目
 * Date: 2017/3/16 0016
 * Time: 17:12
 */

namespace Shop\Controller;

use Home\Controller\BaseController;

/**
 * 首页广告控制器
 */
class AwardController extends BaseController
{
    public function _initialize(){
        parent::_initialize();
        if (!in_array(strtolower(ACTION_NAME), [
            'index_test',
        ])) {
            $this->check_login();
        }
        if (!in_array(strtolower(ACTION_NAME), ['address_add', 'address_edit'])) {
            $this->assign('back_url', U('Home/Member/Index'));
        }
        $this->assign('userInfo', $this->userInfo);
    }
    public  function  index(){

        // 中奖记录
        $award_record =M('shop_award_record as r')
                        ->field('r.uid,r.time,a.title')
                        ->join('left join oc_shop_award as a on r.award_id =a.id')
                        ->order('r.id desc')
                        ->select();
        $this->assign('award_record',$award_record);
        // 获取奖品
        $this->assign('user',$this->userInfo);
        $awardModel =D('Award');
        $award =$awardModel->get_award(10,1,'*','sort desc,id desc',['status'=>1]);
        $this->assign('award',$award);
        $this->assign('meta_title','抽奖');
        $this->display();
    }
    // 抽奖
    public  function lottery(){
        if(IS_AJAX){
            if(empty($this->uid)){
                $this->error('请先登录');
            }
            $user =$this->userInfo;
            $score =C('award_score');
            if($user['score']<$score){
                $this->error('您的积分不足');
            }

            $awardModel =D('Award');
            $award =$awardModel->get_award(10,1,'id,title,rate,type,score','sort desc,id desc',['status'=>1]);
            foreach($award as $key =>$val){
                $arr[$val['id']] = $val['rate'];
            }
            $rid = $awardModel->get_rand($arr);
            $award_title_arr =array_column($award,'title');
            // 奖品具体信息
            $price_arr = $awardModel->where(['id'=>$rid])->find();
            $aid =array_search($price_arr['title'],$award_title_arr);
            $flag =1;
            switch($price_arr['type']){
                // 虚拟物品 积分
                case 1:
                    D()->startTrans();
                    $add_record =M('shop_award_record')->add(['uid'=>$this->uid,'award_id'=>$rid,'time'=>datetime()]);
                    if(!$add_record){
                        $flag =0;
                        goto commit;
                    }
                    $title ="积分抽奖扣除".$score."积分";
                    $status =set_user_score(2,$score,$this->uid,$title);
                    if(!$status){
                        $flag =0;
                        goto commit;
                    }
                    $add_title ="积分抽奖中了".$price_arr['title']."增加".$price_arr['score']."积分";
                    $add_status =set_user_score(1,$price_arr['score'],$this->uid,$add_title);
                    if(!$add_status){
                        $flag =0;
                        goto commit;
                    }
                    goto commit;
                    break;
                // 实物物品
                case 2:
                    D()->startTrans();
                    $add_record =M('shop_award_record')->add(['uid'=>$this->uid,'award_id'=>$rid,'time'=>datetime()]);
                    if(!$add_record){
                        $flag =0;
                        goto commit;
                    }
                    $title ="积分抽奖扣除".$score."积分";
                    $status =set_user_score(2,$score,$this->uid,$title);
                    if(!$status){
                        $flag =0;
                        goto commit;
                    }
                    goto commit;
                    break;
            }
            commit:
            if(!$flag){
                D()->rollback();
                $this->error('系统繁忙,请稍后重试');
            }else{
                D()->commit();
                $this->success('成功','',['prize_name'=>$price_arr['title'],'rid'=>$rid,'site'=>$aid]);
            }

        }
    }

    // 我的领取记录
    public function  my_award(){
        $default_address_id =cookie('default_address_id');
        if(empty($default_address_id)){
            $default_address='';
        }else{
            $default_address =M('shop_address')->where(['id'=>$default_address_id])->find();
        }
        $this->assign('address',$default_address);
        // 中奖记录
        $award_record =M('shop_award_record as r')
            ->field('r.uid,r.time,a.title')
            ->where(['r.uid'=>$this->uid])
            ->join('left join oc_shop_award as a on r.award_id =a.id')
            ->order('r.id desc')
            ->select();
        $this->assign('award_record',$award_record);
        $this->assign('user',$this->userInfo);
        $this->meta_title ='领取记录';
        $this->display();
    }

    // 抽奖规则
    public  function  award_rule(){
        $info =M('admin_post')->where(['id'=>3])->find();
        $this->assign('info',$info);
        $this->assign('user',$this->userInfo);
        $this->meta_title ='活动规则';
        $this->display();
    }
}