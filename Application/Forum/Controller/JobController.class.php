<?php
/**
 * Function
 * Created by PhpStorm.
 * User: john
 * Date: 2017/2/6
 * Time: 15:45
 */
namespace Forum\Controller;

use Home\Controller\BaseController;
use Think\Controller;

class JobController extends BaseController{
    public function _initialize(){
        parent::_initialize();
        $this->assign('gl', 3);
        if (!in_array(strtolower(ACTION_NAME), [
            'index',
            'job_detail',
            'recruit_detail'
        ])) {
            $this->check_login();
        }
        if (!in_array(strtolower(ACTION_NAME), ['address_add', 'address_edit'])) {
            $this->assign('back_url', U('Home/Member/Index'));
        }
        $this->assign('userInfo', $this->userInfo);
    }
    // 招聘首页
    public  function  index(){
        if(IS_AJAX){
            $page = I('post.n') ? I('post.n') : 1;
            $limit = 10;
            $start = ($page - 1) * $limit;
            $type = I('post.type',1); //1：招聘   2：求职
            $keywords =trim(I('post.keywords',''));
            $map['status'] =1;
            if($type ==1){
                if(!empty($keywords)){
                    $map['_string'] ="(title like '%{$keywords}%') OR (position like '%{$keywords}%') OR (prov like '%{$keywords}%') OR (city like '%{$keywords}%') OR (country like '%{$keywords}%')";
                }
            }else{
                if(!empty($keywords)){
                    $map['_string'] ="(position like '%{$keywords}%')";
                }
            }
            if($type ==2){
                $record =M('forum_job_search')
                    ->where($map)
                    ->limit($start,$limit)
                    ->order('time desc,id desc')
                    ->select();
            }else{
                $record =M('forum_job')
                    ->where($map)
                    ->limit($start,$limit)
                    ->order('time desc,id desc')
                    ->select();
            }
            if(!empty($record)){
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
                $this->assign('list',$record);
                $this->assign('type',$type);
                $html =$this->fetch('ajax_job_list');
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
            // 获取search
            $this->assign('search',get_search_words(1));
            $keywords = I('keywords', '');
            $cookie_key =cookie('job_search');
            if($keywords){
                if(empty($cookie_key)){
                    $cookie_key =[];
                    array_unshift($cookie_key,$keywords);
                    cookie('job_search',$cookie_key);
                }else{
                    if(!in_array($keywords,$cookie_key)){
                        array_unshift($cookie_key,$keywords);
                        cookie('job_search',$cookie_key);
                    }
                }
            }
            $this->assign('cookie_key',$cookie_key);
            $this->assign('keywords',$keywords);
            // 获取BANNER
            $silder_model = new \Forum\Model\SliderModel();
            $banner =$silder_model->getList(10,1,'sort',['type'=>'job']);
            $this->assign('banner',$banner);
            $this->assign('meta_title','招聘求职');
            $this->display();
        }
    }

    // 招聘填写
    public  function  post(){
        if(IS_AJAX){
            $post =I('post.');
            $post['uid'] =$this->uid;
            $post['pics'] =implode(',',$post['pics']);
            $area =I('area', '', 'trim');
            list($post['prov'], $post['city'], $post['country']) = explode(' ', $area);
            $post['time'] = datetime();
            $add =M('forum_job')->add($post);
            if($add){
                $this->success('发布成功,请等待审核');
            }else{
                $this->error('发布失败');
            }
        }else{
            $default_info=M('forum_job')->where(['uid'=>$this->uid])->order('time desc')->find();
            $this->assign('default',$default_info);
            $jsapi = R('Home/Weixin/jsapi', [['chooseImage', 'uploadImage','onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone']]);
            $this->assign('jsapi', $jsapi);
            $this->assign('meta_title','招聘填写');
            $this->display();
        }
    }

    // 求职填写
    public  function  find(){
        if(IS_AJAX){
            $post =I('post.');
            if($post['pics']){
                $post['pics'] =implode(',',$post['pics']);
            }
            $post['area'] =preg_replace("/\s/","",$post['area']);
            $post['time'] = datetime();
            $post['uid'] = $this->uid;
            $add =M('forum_job_search')->add($post);
            if($add){
                $this->success('发布成功,请等待审核');
            }else{
                $this->error('发布失败');
            }
        }else{
            $jsapi = R('Home/Weixin/jsapi', [['chooseImage', 'uploadImage','onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone']]);
            $this->assign('jsapi', $jsapi);
            $this->assign('meta_title','求职填写');
            $this->display();
        }
    }

    // 招聘详情
    public  function  recruit_detail(){
        $id =I('get.id','');
        if(empty($id)){
            $this->error('参数错误');
        }else{
            $info =M('forum_job')->where(['id'=>$id])->find();
            if(empty($info)){
                $this->error('信息不存在');
            }else{
                // 增加浏览记录
                add_browsing_history('forum_job',['id'=>$id],'pageviews');
                if(!empty($info['pics'])){
                    $info['pics']= explode(',',$info['pics']);
                }
                switch($info['sex']){
                    case 1:
                        $info['sex'] ='男';
                        break;
                    case 2:
                        $info['sex'] ='女';
                        break;
                    default:
                        $info['sex'] ='不限';
                }
                $info['date_time'] =time_difference(strtotime($info['time']));
                $this->assign('info',$info);
                $jsapi = R('Home/Weixin/jsapi', [['previewImage']]);
                $this->assign('jsapi', $jsapi);
                $this->assign('meta_title','招聘信息详情');
                $this->display();
            }
        }

    }

    // 求职详情
    public  function  job_detail(){
        $id =I('get.id','');
        if(empty($id)){
            $this->error('参数错误');
        }else{
            $info =M('forum_job_search')->where(['id'=>$id])->find();
            if(empty($info)){
                $this->error('信息不存在');
            }else{
                // 增加浏览记录
                add_browsing_history('forum_job_search',['id'=>$id],'pageviews');
                if(!empty($info['pics'])){
                    $info['pics']= explode(',',$info['pics']);
                }
                switch($info['sex']){
                    case 1:
                        $info['sex'] ='男';
                        break;
                    case 2:
                        $info['sex'] ='女';
                        break;
                    default:
                        $info['sex'] ='男';
                }
                $info['date_time'] =time_difference(strtotime($info['time']));
                $this->assign('info',$info);
                $jsapi = R('Home/Weixin/jsapi', [['previewImage']]);
                $this->assign('jsapi', $jsapi);
                $this->assign('meta_title','求职信息详情');
                $this->display();
            }
        }

    }

    public  function  edit(){
        $model =D('forum_job');
        if(IS_AJAX){
            $post =I('post.');
            $post['pics'] =implode(',',$post['pics']);
            $area =I('area', '', 'trim');
            list($post['prov'], $post['city'], $post['country']) = explode(' ', $area);
            $add =M('forum_job')->where(['id'=>I('id')])->save($post);
            if($add!==false){
                $this->success('修改成功',U('Home/Member/job'));
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