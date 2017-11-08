<?php
namespace Shop\Controller;

use Home\Controller\BaseController;

/**
 * 今日头条控制器
 */
class BulletinController extends BaseController
{
  public function _initialize()
  {
    parent::_initialize();
  }

  /**
   * 今日头条 列表页
   */
  public function index()
  {
    if(IS_AJAX){
      $page = I('post.n') ? I('post.n') : 1;
      $limit = 10;
      $start = ($page - 1) * $limit;
      $map['status'] =1;
      $type=I('post.type','');
      if($type){
        if($type!='all'){
            $map['type']=$type;
        }
      }else{
        $keywords =I('keywords','');
        if($keywords){
          $map['_string'] ="(title like '%{$keywords}%')";
        }
      }
      $info = M('shop_bulletin')->field('id,title,pictures,create_time,type,read_num')->where($map)->limit($start,$limit)->order('id DESC')->select();
      foreach ($info as $key => $value) {
        $info[$key]['posttime'] = time_difference(strtotime($value['create_time']));
        $info[$key]['type_name'] =M('shop_bulletin_type')->where(['id'=>$value['type']])->getField('title');
        $info[$key]['check'] = 0;
        $covers =  array_slice(explode(',', $value['pictures']),0, 3);
        $info[$key]['cover'] =$covers;
        if(count($covers)>1){
          $info[$key]['check']=1;
        }
      }
      if($info){
        $this->assign('info',$info);
        $html =$this->fetch('ajax_index_list');
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
      $keywords =I('post.keywords','');
      $cookie_key =cookie('news_search');
      if($keywords){
        if(empty($cookie_key)){
          $cookie_key =[];
          array_unshift($cookie_key,$keywords);
          cookie('news_search',$cookie_key);
        }else{
          if(!in_array($keywords,$cookie_key)){
            array_unshift($cookie_key,$keywords);
            cookie('news_search',$cookie_key);
          }
        }
      }
      $this->assign('keywords',$keywords);
      // BANNER
      $banner =D('Slider')->getList(10,1,'sort asc,id desc',['type'=>4]);
      $this->assign('banner',$banner);
      //搜索
      $hot_serach =M('shop_set')->where(['id'=>1])->getField('new_search');
      if($hot_serach){
        $hot_serach =explode(',',$hot_serach);
      }
      $this->assign('search',$hot_serach);
      // 用户搜索
      $cookie_key =cookie('news_search');
      $this->assign('cookie_key',$cookie_key);
      $type =D('type')->where(['status'=>1])->select();
      $this->assign('type',$type);
      $this->display();
    }
  }

  /**
   * 今日头条 详情
   */
  public function detail()
  {
    $id = I('id');
    if(IS_AJAX){
      $page = I('post.n',1);
      $limit = 10;
      $start = ($page - 1) * $limit?:2; //从第二个开始
      $map['bid']=$id;
      $map['status'] =1;

      $info =M('shop_bulletin_review')
                  ->order('id desc')
                  ->where($map)
                  ->limit($start,$limit)
                  ->select();

      $like =M('shop_bulletin_like')->where(['uid'=>$this->uid,'type'=>2])->getField('bid',true);
      foreach($info as $key =>$value){
        if(empty($like)){
          $info[$key]['like'] =0;
        }else{
          if(in_array($value['id'],$like)){
            $info[$key]['like'] =1;
          }else{
            $info[$key]['like'] =0;
          }
        }
      }

      if($info){
        $this->assign('info',$info);
        $html =$this->fetch('ajax_detail_list');

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
      cookie('before_reg', $this->current_url, $this->cookie_expire);
      $uid = $this->uid;
      empty($id)?exit:'';


      $info = M('shop_bulletin')->where(['id'=>$id])->find();
      M('shop_bulletin')->where(['id'=>$id])->setInc('read_num',1);
      if(empty($info) || $info['status'] != 1){
        $this->error('头条已被删除');
      }
      $collect_on = '';
      if(!empty($uid)){
        $collect_res = M('shop_bulletin_collect')->where(['bid'=>$id,'uid'=>$uid])->find();
        if(!empty($collect_res)){
          $collect_on = 'on';
        }
      }
      $this->assign('collect_on',$collect_on);



      $priase = M('shop_bulletin_like')->where(['uid'=>$this->uid,'bid'=>$info['id'],'type'=>1])->find();
      if($priase){
        $info['praise']=1;
      }else{
        $info['praise']=0;
      }
      $priase_num = M('shop_bulletin_like')->where(['bid'=>$info['id'],'type'=>1])->count();
      $info['priase_num'] =$priase_num;
      $this->assign('info',$info);

      //推荐阅读
      $recommend_list =M('shop_bulletin')
                        ->field('title,pictures,id,create_time')
                        ->where(['status'=>1,'id'=>['neq',$id]])->
                        order('id desc')->limit(3)->select();
      $this->assign('recommend_list',$recommend_list);
      // 评论
      $comment_list =M('shop_bulletin_review')->where(['bid'=>$id])->order('id desc')->limit(2)->select();

      $like =M('shop_bulletin_like')->where(['uid'=>$this->uid,'type'=>2])->getField('bid',true);
      foreach($comment_list as $key =>$value){
        if(empty($like)){
          $comment_list[$key]['like'] =0;
        }else{
          if(in_array($value['id'],$like)){
            $comment_list[$key]['like'] =1;
          }else{
            $comment_list[$key]['like'] =0;
          }
        }
      }
      $this->assign('comment_list',$comment_list);


      $comment_total =M('shop_bulletin_review')->where(['bid'=>$id])->count();
      $this->assign('comment_total',$comment_total);

      $wechatShare["title"] = $info['title'];
      $wechatShare["desc"] = $info['title'];
      $wechatShare["link"] =$this->current_url;
      $wechatShare["imgUrl"] = XILUDomain().getpics($info['pictures'],'cover');
      $wechatShare["type"] = 'link';
      $jsapi = R('Home/Weixin/jsapi', [['onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone']]);
      $this->assign('jsapi', $jsapi);
      $this->assign('wechatShare',$wechatShare);
      $this->display();
    }

  }


  // 点赞
  public  function  praise(){
    if(IS_AJAX){
      $id= I('id','');
      $type =I('type',1);
      $model =I('model','');
      switch($model){
        case 'bulletin':
              $bulletin_type =1;
              $model_database ='shop_bulletin';
              break;
        case 'bulletin_review':
          $bulletin_type =2;
          $model_database ='shop_bulletin_review';
          break;
      }
      if($type==1){
        // 点赞
        D()->startTrans();
        $add_data =M('shop_bulletin_like')->add(['uid'=>$this->uid,'bid'=>$id,'type'=>$bulletin_type,'time'=>datetime()]);
        if(!$add_data){
          goto finished;
        }
        $update_data =M($model_database)->where(['id'=>$id])->setInc('like_num',1);
        if(!$update_data){
          goto  finished;
        }
        finished:
        if($add_data && $update_data){
          D()->commit();
          $this->success('成功');
        }else{
          D()->rollback();
          $this->error('失败');
        }
      }else{
        // 取消点赞
        D()->startTrans();
        $add_data =M('shop_bulletin_like')->where(['uid'=>$this->uid,'type'=>$bulletin_type,'bid'=>$id])->delete();
        if(!$add_data){
          goto finish;
        }
        $update_data =M($model_database)->where(['id'=>$id])->setDec('like_num',1);
        if(!$update_data){
          goto  finish;
        }
        finish:
        if($add_data && $update_data){
          D()->commit();
          $this->success('成功');
        }else{
          D()->rollback();
          $this->error('失败');
        }
      }
    }
  }
  /**
   * 今日头条 收藏
   */
  public function collect()
  {
    if(IS_AJAX){

      $check  = I('check',''); // 1取消 0关注
      $gid = I('gid','');
      $uid = $this->uid;

      if(empty($uid)){
        cookie('before_reg', U('Shop/Bulletin/detail',['id'=>$gid]), $this->cookie_expire);
        $this->error('立即登录',U('Home/Member/login'));
      }
      $nowTime = datetime();
      $collect_obejct = M('shop_bulletin_collect');
      if($check){
        $collect_res = $collect_obejct->where(['uid'=>$uid,'bid'=>$gid])->delete();
        $success_info = '取消成功';
      }else{
        $collect_data['uid'] = $uid;
        $collect_data['bid'] = $gid;
        $collect_data['create_time'] = $nowTime;
        $collect_data['update_time'] = $nowTime;
        $collect_data['status'] = 1;
        $collect_res  = $collect_obejct->add($collect_data);
        $success_info = '收藏成功';
      }
      if($collect_res ){
        $this->success($success_info);
      }else{
        $this->error('系统繁忙请稍后再试！');
      }
    }
  }

  /**
   * 今日头条 写评论
   */
  public function review()
  {
    if(IS_AJAX){
      $uid = $this->uid;
      $bid  = I('bid','');

      if(empty($uid)){
        cookie('before_reg', U('Shop/Bulletin/detail',['id'=>$bid]), $this->cookie_expire);
        $this->error('立即登录',U('Home/Member/login'));
      }
      $content = I('content','');
      if(empty($content) || empty($bid)){
        $this->error('数据异常，请稍后再试！');
      }

      $review_object = M('shop_bulletin_review');
      $nowTime  = datetime();
      $data['uid'] =$uid;
      $data['bid'] = $bid;
      $data['content'] = $content;
      $data['create_time'] = $nowTime;
      $data['update_time'] = $nowTime;

      if(!$review_object->create($data)){
        ptrace('头条评论'.$review_object->getError());
        $this->error('数据异常，请稍后再试！');
      }
      $res = $review_object->add();

      if($res){
        $this->success('评论成功');
      }else{
        $this->error('服务器异常,请稍后再试！');
      }

    }
  }


  /**
   * 头条评论列表
   */
  public function review_list()
  {
    if(IS_AJAX){
      $page = I('post.n') ? I('post.n') : 1;
      $limit = 10;
      $start = ($page - 1) * $limit;
      $id = I('id');
      $map['oc_shop_bulletin_review.bid']=$id;
      $map['oc_shop_bulletin_review.status'] =1;

      $info = M('shop_bulletin_review')
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

      if($info){
        $this->assign('info',$info);
        $html =$this->fetch('ajax_review_list');
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
      cookie('before_reg', $this->current_url, $this->cookie_expire);
      $id = I('id','');
      $id?:exit;
      $this->assign('id',$id);
      $this->display();
    }
  }
  /**
   * 头条详情 ajax 检查登录
   */
  public function ajaxCheckLogin()
  {
    if(IS_AJAX){
      $uid = $this->uid;
      if(empty($uid)){
        $this->error('立即登录',U('Home/Member/login'));
      }else{
        $this->success();
      }
    }
  }

  public  function  adver_detail(){
    $id =I('id','');
    $info =M('cms_slider')->where(['id'=>$id])->find();
    if(empty($info)){
      $this->error('信息不存在');
    }else{
      $this->meta_title ='文章详情';
      $this->assign('info',$info);
      $this->display();
    }
  }
  /**
   * 详情页评论 ajax 加载
   */
  public function detailAjax()
  {

  }

}