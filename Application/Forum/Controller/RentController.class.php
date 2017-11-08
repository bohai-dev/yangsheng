<?php
/**
 * Function
 * Created by PhpStorm.
 * User: john
 * Date: 2017/2/8
 * Time: 15:04
 */
namespace Forum\Controller;

use Home\Controller\BaseController;
use Think\Controller;
class RentController extends BaseController{
    public function _initialize(){
        parent::_initialize();
        $this->assign('gl', 3);
        if (!in_array(strtolower(ACTION_NAME), [
           'index',
            'rent_detail'
        ])) {
            $this->check_login();
        }
        if (!in_array(strtolower(ACTION_NAME), ['address_add', 'address_edit'])) {
            $this->assign('back_url', U('Home/Member/Index'));
        }
        $this->assign('userInfo', $this->userInfo);
    }
    // 出租
    public  function  index(){
        if(IS_AJAX){
            $page = I('post.n') ? I('post.n') : 1;
            $limit = 10;
            $start = ($page - 1) * $limit;
            $type = I('post.type','all'); //1：转让  2：出租 3：求租
            $keywords =I('post.keywords','');
            $map['status'] =1;
            if(!empty($keywords)){
                $map['_string'] ="(title like '%{$keywords}%') OR (area like '%{$keywords}%')";
            }
            if($type!='all'){
                $map['type']=$type;
            }
            $record =M('forum_rent')->where($map)
                    ->limit($start,$limit)
                    ->order('time desc,id desc')
                    ->select();
            if(!empty($record)){
                $this->assign('list',$record);
                $html =$this->fetch('ajax_rent_list');
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
            $this->assign('search',get_search_words(2));
            $keywords = I('keywords', '');
            $cookie_key =cookie('rent_search');
            if($keywords){
                if(empty($cookie_key)){
                    $cookie_key =[];
                    array_unshift($cookie_key,$keywords);
                    cookie('rent_search',$cookie_key);
                }else{
                    if(!in_array($keywords,$cookie_key)){
                        array_unshift($cookie_key,$keywords);
                        cookie('rent_search',$cookie_key);
                    }
                }
            }
            $this->assign('cookie_key',$cookie_key);
            $this->assign('keywords',$keywords);
            //   获取banner
            $silder_model = new \Forum\Model\SliderModel();
            $banner =$silder_model->getList(10,1,'sort',['type'=>'rent']);
            $this->assign('banner',$banner);
            $this->assign('meta_title','转让出租求租');
            $this->display();
        }
    }

    //  出租填写
    public  function  post_rent(){
        if(IS_AJAX){
            $post =I('post.');
            $post['uid'] =$this->uid;
            $post['pics'] =implode(',',$post['pics']);
           // $area =I('area', '', 'trim');
           /* list($post['prov'], $post['city'], $post['country']) = explode(' ', $area);*/
            $post['area'] =$post['area'];
            $post['time'] = datetime();
            $add =M('forum_rent')->add($post);
            if($add){
                $this->success('发布成功,请等待审核');
            }else{
                $this->error('发布失败');
            }
        }else{
            $jsapi = R('Home/Weixin/jsapi', [['chooseImage', 'uploadImage','onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone']]);
            $this->assign('jsapi', $jsapi);
            $this->assign('meta_title','信息填写');
            $this->display();
        }
    }

    // 出租详情
    public function rent_detail(){
        $id =I('get.id','');
        if(empty($id)){
            $this->error('参数错误');
        }else{
            $info =M('forum_rent')->where(['id'=>$id])->find();
            if(empty($info)){
                $this->error('信息不存在');
            }else{
                // 增加浏览记录
                add_browsing_history('forum_rent',['id'=>$id],'pageviews');
                if(!empty($info['pics'])){
                    $info['pics']= explode(',',$info['pics']);
                }
                $info['date_time'] =time_difference(strtotime($info['time']));
                $this->assign('info',$info);
                $jsapi = R('Home/Weixin/jsapi', [['previewImage']]);
                $this->assign('jsapi', $jsapi);
                $this->assign('meta_title','信息详情');
                $this->display();
            }
        }

    }
    public  function  edit(){
        $model =D('Rent');
        if(IS_AJAX){
            $post =I('post.');
            $post['pics'] =implode(',',$post['pics']);
            $post['area'] =$post['area'];
            $add =M('forum_rent')->where(['id'=>$post['id']])->save($post);
            if($add!==false){
                $this->success('修改成功',U('Home/Member/rent'));
            }else{
                $this->error('修改失败');
            }
        }else{
            $info =$model->where(['id'=>I('id')])->find();
            if(!empty($info['pics'])){
                $info['pics'] =explode(',',$info['pics']);
            }
            $jsapi = R('Home/Weixin/jsapi', [['chooseImage', 'uploadImage','onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone']]);
            $this->assign('jsapi', $jsapi);
            $this->assign('info',$info);
            $this->meta_title ='编辑';
            $this->display();
        }
    }
}