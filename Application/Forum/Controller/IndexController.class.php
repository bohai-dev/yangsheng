<?php
namespace Forum\Controller;
use Think\Controller;
use Home\Controller\BaseController;
/**
 * 论坛控制器
 */
class IndexController extends BaseController
{
    public function _initialize(){
        parent::_initialize();
        $this->assign('gl', 3);
        if (!in_array(strtolower(ACTION_NAME), [
            'index',
            'detail',
        ])) {
            $this->check_login();
        }
        $this->assign('userInfo', $this->userInfo);
    }
    /**
     * 论坛方法
     */
    public function index()
    {
        if(IS_AJAX){
            $type =I('type','all');
            $map['status'] =1;
            if($type!='all'){
                $map['_string'] ='FIND_IN_SET('.$type.',type)';
            }
            $page = I('post.n') ? I('post.n') : 1;
            $limit = 10;
            $start = ($page - 1) * $limit;
            $attention =M('forum_attention')->where(['uid'=>$this->uid,'status'=>1])->getField('attention_userid',true);
            if(I('attention','')=='attention'){
                if(empty($attention)){
                    $record='';
                }else{
                    $map['uid'] =array('in',$attention);
                    $record =D('Forum')->where($map)
                        ->limit($start,$limit)
                        ->order('top desc,time desc,id desc')
                        ->select();
                }
            }else{
                if($uid =I('uid',0)){
                    $map['uid'] =$uid;
                }
                $record =D('Forum')->where($map)
                    ->limit($start,$limit)
                    ->order('top desc,time desc,id desc')
                    ->select();
            }
            $like =M('forum_like')->where(['uid'=>$this->uid])->getField('forum_id',true);
            if(!empty($record)){
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
                    $record[$key]['type_title'] =$v['type_name'];
                    $record[$key]['username'] =$user['nickname'];
                    $record[$key]['avatar'] =getpics($user['avatar']);
                    $record[$key]['date_time']  =time_difference(strtotime($v['time']));
                    $record[$key]['comment'] =get_total_comment($v['id'],3);
                    if($v['pics']){
                        $record[$key]['pics'] =explode(',',($v['pics']));
                    }
                }
                $this->assign('lists',$record);
                $html =$this->fetch('ajax_list');
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
            // 微信分享
            $jsapi = R('Home/Weixin/jsapi', [['onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone']]);
            $this->assign('jsapi', $jsapi);
            $wechatShare = XILUWechatShare($this->uid);
            $this->assign('wechatShare', $wechatShare);
            // 获取分类
            $ForumModel = new \Forum\Model\ForumModel();
            $type =$ForumModel->get_forum_type();
            $this->assign('type',$type);
            // 获取积分
            $score =explode(',',C('Forum_config.score'));
            if(is_array($score)){
                $this->assign('score',$score);
                $html =$this->fetch('ajax_score');
                $this->assign('score_html',json_encode($html));
            }
            // 获取广告
            $adver_list =D('Adver')->getList(20,0,'sort desc',['group'=>1]);
            $this->assign('adver',$adver_list);
            // 获取中间广告
            $middle_adver_list =D('Adver')->getList(20,0,'sort desc',['group'=>2]);
            $this->assign('middle_adver',$middle_adver_list);
            //   获取banner
            $silder_model = new \Forum\Model\SliderModel();
            $banner =$silder_model->getList(10,1,'sort',['type'=>'index']);
            $this->assign('user',$this->userInfo);
            $this->assign('banner',$banner);
            $this->assign('meta_title','论坛');
            $this->display();
        }

    }
    // 关注
    public  function   attention(){
        if(IS_AJAX){
            $type = I('type','');
            $attention_userid = I('attend_uid');
            if(empty($type)){
                $this->error('服务器繁忙，请稍后重试');
            }else{
                if($this->uid ==$attention_userid){
                    $this->error('自己不能关注自己哦');
                }
                $map['uid']=$this->uid;
                $map['attention_userid']=$attention_userid;
                $attention_model =M('forum_attention');
                $data =$attention_model->where($map)->find();
                if($type ==1){
                    // 关注
                    if($data){
                        $update_data =$attention_model->where($map)->save(['status'=>1,'time'=>datetime()]);
                        if($update_data!==false){
                            $this->success('关注成功');
                        }else{
                            $this->error('关注失败');
                        }
                    }else{
                        $add_data =$attention_model->add(['uid'=>$this->uid,'attention_userid'=>$attention_userid,'time'=>datetime()]);
                        if($add_data){
                            $this->success('关注成功');
                        }else{
                            $this->error('关注失败');
                        }
                    }
                }else{
                    // 取消关注
                    $update_data =$attention_model->where($map)->save(['status'=>2,'time'=>datetime()]);
                    if($update_data!==false){
                        $this->success('取消关注成功');
                    }else{
                        $this->error('取消关注失败');
                    }
                }
            }
        }else{
            // 获取积分
            $score =explode(',',C('Forum_config.score'));
            if(is_array($score)){
                $this->assign('score',$score);
                $html =$this->fetch('ajax_score');
                $this->assign('score_html',json_encode($html));
            }
            // 微信分享
            $jsapi = R('Home/Weixin/jsapi', [['onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone']]);
            $this->assign('jsapi', $jsapi);
            $wechatShare = XILUWechatShare($this->uid);
            $this->assign('wechatShare', $wechatShare);
            $this->assign('meta_title','关注');
            $this->assign('user',$this->userInfo);
            $this->display();
        }
    }
    public  function  like(){
        // 点赞
        if(IS_AJAX){
            $type =I('type');
            $forum_id =I('id');
            if($type==1){
                // 点赞
                D()->startTrans();
                $add_data =M('forum_like')->add(['uid'=>$this->uid,'forum_id'=>$forum_id,'time'=>datetime()]);
                if(!$add_data){
                    goto finished;
                }
                $update_data =M('forum_posts')->where(['id'=>$forum_id])->setInc('like_num',1);
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
                $add_data =M('forum_like')->where(['uid'=>$this->uid,'forum_id'=>$forum_id])->delete();
                if(!$add_data){
                    goto finish;
                }
                $update_data =M('forum_posts')->where(['id'=>$forum_id])->setDec('like_num',1);
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
    // 打赏
    public  function  reward(){
        if(IS_AJAX){
            $post_id =I('posts_id');
            $value =I('value');
            $uid =$this->uid;
            $user =$this->userInfo;
            if($user['score']<$value){
                $this->error('您的积分不足');
            }
            $post_info =M('forum_posts')->where(['id'=>$post_id])->find();
            if($post_info['uid']==$uid){
                $this->error('不能为自己打赏哦');
            }
            $old_record =M('forum_record')->where(['posts_id'=>$post_id,'uid'=>$this->uid])->find();
            if($old_record){
                $this->error('您已经为该帖打赏过了');
            }
            //事务开始
            D()->startTrans();
            // 增加帖子金额
            $update_reward= M('forum_posts')->where(['id'=>$post_id])->setInc('reward_num',$value);
            if(!$update_reward){
                goto finished;
            }
            // 增加帖子用户积分
            $add_post_user =M('admin_user')->where(['id'=>$post_info['uid']])->setInc('score',$value);
            if(!$add_post_user){
                goto finished;
            }
            // 增加帖子积分记录
            $add_post_record =M('user_score')->add([
                'uid'=>$post_info['uid'],
                'nickname'=>get_user_info($post_info['uid'],'admin_user','nickname'),
                'title'=>'论坛打赏增加'.$value.'积分',
                'score'=>$value,
                'create_time'=>datetime(),
                'type'=>1
            ]);
            if(!$add_post_record){
                goto finished;
            }
            // 减少积分
            $reduce_score =M('admin_user')->where(['id'=>$uid])->setDec('score',$value);
            if(!$reduce_score){
                goto finished;
            }
            // 增加记录
            $add_record =M('forum_record')->add(['posts_id'=>$post_id,'uid'=>$uid,'score'=>$value,'time'=>datetime()]);
            if(!$add_record){
                goto finished;
            }
            // 增加积分消费记录
            $add_score_record =M('user_score')->add([
               'uid'=>$uid,
                'nickname'=>$this->userInfo['nickname'],
                'title'=>'论坛打赏扣除'.$value.'积分',
                'score'=>$value,
                'create_time'=>datetime(),
                'type'=>2
            ]);
            if(!$add_score_record){
                goto finished;
            }
            //
            finished:
            if($update_reward  && $reduce_score && $add_record && $add_score_record && $add_post_user && $add_post_record){
                D()->commit();
                $this->success('打赏成功');
            }else{
                D()->rollback();
                $this->error('打赏失败');
            }
        }
    }
    // 评论
    public  function  comment(){
        if(IS_AJAX){
            $uid =$this->uid;
            $comment =empty(I('comment',''))?I('content',''):I('comment','');
            $posts_id =I('posts_id');

            $scroll_top = I('scroll_top',0);  //滚动条高度

            $post_info =M('forum_posts')->where(['id'=>$posts_id])->find();
            if($post_info['uid']==$uid){
                $this->error('不能为自己评论哦');
            }
            $comment_exists =M('user_comment')->where(['uid'=>$uid,'type'=>3,'order_id'=>$posts_id])->find();
            if($comment_exists){
                $this->error('您已经评论过了');
            }else{
                $add  =M('user_comment')->add([
                    'uid'=>$uid,
                    'type'=>3,
                    'order_id'=>$posts_id,
                    'comment'=>$comment,
                    'comment_time'=>datetime(),
                    'status'=>1,
                ]);
                if($add){
                    $this->success('评论成功',U('Forum/Index/detail',array('scroll_top'=>$scroll_top,'id'=>$posts_id)),['num'=>get_total_comment($posts_id,3)]);
                }else{
                    $this->error('评论失败');
                }
            }
        }
    }
    // 论坛发布
    public  function  post(){
        if(IS_AJAX){
            $post =I('post.');
            $post['uid']=$this->uid;
            if($post['pics']){
                $post['pics'] =implode(',',$post['pics']);
            }
            $post['time'] = datetime();
            $post['type'] =$post['true_type'];
            $add =M('forum_posts')->add($post);
            if($add){
                $this->success('发布成功,请等待审核');
            }else{
                $this->error('发布失败');
            }
        }else{
            //获取分类
            $ForumModel = new \Forum\Model\ForumModel();
            $type =$ForumModel->get_forum_type();
            foreach($type as $key=>$v){
                $type[$key]['value'] =$v['id'];
                unset($type[$key]['status']);
                unset($type[$key]['sort']);
            }
            $type =json_encode($type);
            $this->assign('type',$type);
            $jsapi = R('Home/Weixin/jsapi', [['chooseImage', 'uploadImage','onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone']]);
            $this->assign('jsapi', $jsapi);
            $this->assign('meta_title','发布帖子');
            $this->display();
        }
    }
    public  function  comment_like(){
        if(IS_AJAX){
            $type =I('type');
            $forum_id =I('id');
            if($type==1){
                // 点赞
                D()->startTrans();
                $add_data =M('comment_like')->add(['uid'=>$this->uid,'comment_id'=>$forum_id,'time'=>datetime()]);
                if(!$add_data){
                    goto finished;
                }
                $update_data =M('user_comment')->where(['id'=>$forum_id])->setInc('like_num',1);
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
                $add_data =M('comment_like')->where(['uid'=>$this->uid,'comment_id'=>$forum_id])->delete();
                if(!$add_data){
                    goto finish;
                }
                $update_data =M('user_comment')->where(['id'=>$forum_id])->setDec('like_num',1);
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
    // 编辑
    public  function  edit(){
        if(IS_AJAX){
            $post =I('post.');
            $post['pics'] =implode(',',$post['pics']);
            $post['type'] =$post['true_type'];
            $add =M('forum_posts')->where(['id'=>$post['id']])->save($post);
            if($add!==false){
                $this->success('修改成功',U('Home/Member/posts'));
            }else{
                $this->error('修改失败');
            }
        }else{
            $id =I('id');
            $info =M('forum_posts')->where(['id'=>$id])->find();
            $ForumModel = new \Forum\Model\ForumModel();
            $type =$ForumModel->get_forum_type();
            foreach($type as $key=>$v){
                $type[$key]['value'] =$v['id'];
                unset($type[$key]['status']);
                unset($type[$key]['sort']);
            }
            $type_exit = M('forum_type')->getField('id,title',true);
            $arr =[];
            foreach(explode(',',$info['type']) as $v){
                $arr[]=$type_exit[$v];
            }
            $info['type_name'] =implode(',',$arr);
            if(!empty($info['pics'])){
                $info['pics'] =explode(',',$info['pics']);
            }
            $jsapi = R('Home/Weixin/jsapi', [['chooseImage', 'uploadImage','onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone']]);
            $this->assign('jsapi', $jsapi);
            $type =json_encode($type);
            $this->assign('type',$type);
            $this->assign('info',$info);
            $this->meta_title ='编辑';
            $this->display();
        }
    }

    // 评论列表
    public  function  comment_list(){
        $id =I('id');
        $uid =$this->uid;
        $action = I('get.action');
        $post_uid =M('forum_posts')->where(['id'=>$id])->getField('uid');

        if(empty($post_uid)){
            $this->error('该帖子不存在');
        }
        $replay_user =get_user_info($post_uid,'admin_user','nickname');

        $type= I('type','list');
        $comment_exists =M('user_comment')->where(['uid'=>$uid,'type'=>3,'order_id'=>$id])->find();
        if($comment_exists){
            $type='list';
        }
        $comment_list =M('user_comment')->where(['order_id'=>$id])->order('id desc')->select();

        $this->assign('replay_user',$replay_user);
        $this->assign('scroll_top',I('scroll_top',0));
        $this->assign('type',$type);
        $this->assign('uid',$uid);
        $this->assign('post_id',$id);
        $this->assign('action',$action);
        $this->assign('post_uid',$post_uid);
        $this->assign('list',$comment_list);
        $this->meta_title ='评论列表';
        $this->display();
    }
    public  function  comment_reply(){
        if(IS_AJAX){
            $post =I('post.');
            $model =M('user_comment');
            $exist =$model->where(['pid'=>$post['comment_id'],'uid'=>$this->uid])->find();
            if($exist){
                $this->error('您已经评论过了');
            }
            $data['pid']=$post['comment_id'];
            $data['uid'] =$this->uid;
            $data['type'] =3;
            $data['comment_time'] =datetime();
            $data['comment']= $post['content'];
            $add =$model ->add($data);
            if($add){
                $this->success('评论成功');
            }else{
                $this->error('评论失败');
            }
        }
    }
    // 帖子详情
    public  function  detail(){
        $id = I('get.id','');
        if(empty($id)){
            $this->error('参数错误');
        }
        $detail =D('Forum')->where(['id'=>$id])->find();
        // $detail =D('Forum')->where(['id'=>$id,'status'=>1])->find(); //cy 2017年05月24日 查看帖子，去掉状态控制。
        D('Forum')->where(['id'=>$id,'status'=>1])->setInc('read_num',1);
        $detail['nickname'] =get_user_info($detail['uid'],'admin_user','nickname');
        $detail['avatar'] =getpics(get_user_info($detail['uid'],'admin_user','avatar'));
        $detail['content'] =str_replace("\n","<br>",$detail['content']);
       // $detail['time'] =time_difference(strtotime($detail['time']));
        $detail['comment'] =get_total_comment($detail['id'],3);
        if(isset($detail['pics']) && !empty($detail['pics'])){
            $detail['pics'] =explode(',',$detail['pics']);
        }
        $attention =M('forum_attention')->where(['uid'=>$this->uid,'status'=>1])->getField('attention_userid',true);
        if(empty($attention)){
            $detail['attention'] =0;
        }else{
            if(in_array($detail['uid'],$attention)){
                $detail['attention'] =1;
            }else{
                $detail['attention'] =0;
            }
        }
        $like =M('forum_like')->where(['uid'=>$this->uid])->getField('forum_id',true);
        if(empty($like)){
            $detail['like'] =0;
        }else{
            if(in_array($detail['id'],$like)){
                $detail['like'] =1;
            }else{
                $detail['like'] =0;
            }
        }
        // 获取积分
        $score =explode(',',C('Forum_config.score'));
        if(is_array($score)){
            $this->assign('score',$score);
            $html =$this->fetch('ajax_score');
            $this->assign('score_html',json_encode($html));
        }
        // 微信分享
        $jsapi = R('Home/Weixin/jsapi', [['onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone']]);
        $this->assign('jsapi', $jsapi);
        $wechatShare = XILUWechatShare($this->uid);
        $this->assign('wechatShare', $wechatShare);
        // 评论
        $comment =M('user_comment')->order('id desc')->where(['order_id'=>$detail['id'],'type'=>3,'status'=>1])->select();

        if(is_array($comment)){
            foreach($comment as $ff=>$tt){
                $comment[$ff]['num'] =M('user_comment')->where(['pid'=>$tt['id']])->count();
                $comment[$ff]['nickname'] =get_user_info($tt['uid'],'admin_user','nickname');
            }
        }
        $comment_like =M('comment_like')->where(['uid'=>$this->uid])->getField('comment_id',true);
        foreach($comment as $key=>$v){
            if(empty($comment_like)){
                $comment[$key]['like'] =0;
            }else{
                if(in_array($v['id'],$comment_like)){
                    $comment[$key]['like'] =1;
                }else{
                    $comment[$key]['like'] =0;
                }
            }
        }
        $forum_like =M('forum_like')->where(['forum_id'=>$detail['id'],'status'=>1])->select();


        $this->assign('scroll_top',I('scroll_top',0));
        $this->assign('forum_like',$forum_like);
        $this->assign('comment',$comment);
        $this->assign('detail',$detail);
        $this->assign('meta_title',$detail['title']);
        $this->display();
    }

    public  function  personal_list(){
        $uid =I('get.id',0);
        $user =M('admin_user')->where(['id'=>$uid])->find();
        // 获取粉丝数
        $ForumModel = new \Forum\Model\ForumModel();
        $fans =$ForumModel->get_fans($uid);
        $map =[
            'status'=>1,
            'uid'=>$uid
        ];
        $forum_list =D('Forum')->where($map)
            ->order('time desc,id desc')
            ->select();
        $this->assign('forum_list',$forum_list);
        // 关注
        $attention =M('forum_attention')->where(['uid'=>$this->uid,'status'=>1])->getField('attention_userid',true);
        if(empty($attention)){
            $user['attention'] =0;
        }else{
            if(in_array($uid,$attention)){
                $user['attention'] =1;
            }else{
                $user['attention'] =0;
            }
        }
        $this->assign('fans',$fans);
        $this->assign('user',$user);
        $this->assign('uid',$uid);
        $this->meta_title ='论坛';
        $this->display();
    }

    public  function  forum_comment_list(){
        $id =I('get.id',0);
        $model =M('user_comment');
        $comment_like =M('comment_like')->where(['uid'=>$this->uid])->getField('comment_id',true);
        $comment =$model->where(['id'=>$id,'status'=>1])->find();
        if($comment_like){
            if(in_array($comment['id'],$comment_like)){
                $comment['like'] =1;
            }else{
                $comment['like'] =0;
            }
        }else{
            $comment['like'] =0;

        }
        $this->assign('comment',$comment);
        $child_comment =$model->where(['pid'=>$id,'type'=>3,'status'=>1])->select();
        foreach($child_comment as $key=>$v){
            if(empty($comment_like)){
                $child_comment[$key]['like'] =0;
            }else{
                if(in_array($v['id'],$comment_like)){
                    $child_comment[$key]['like'] =1;
                }else{
                    $child_comment[$key]['like'] =0;
                }
            }
        }
        $this->assign('child_comment',$child_comment);
        $this->meta_title='回复';
        $this->display();

    }

    public  function  del_comment(){
        if(IS_AJAX){
            $res =M('user_comment')->where(['id'=>I('id')])->delete();
            if($res){
                $this->success('删除成功');
            }else{
                $this->error('删除失败');
            }
        }
    }
    public  function  award_list(){
        $id =I('get.id',0);
        $award_list =M('forum_record')->where(['posts_id'=>$id])->select();
        $this->assign('list',$award_list);
        $this->meta_title='打赏记录';
        $this->display();
    }
    public  function  adver_detail(){
        $id =I('id','');
        $info =M('forum_adver')->where(['id'=>$id])->find();
        if(empty($info)){
            $this->error('信息不存在');
        }else{
            $this->meta_title ='文章详情';
            $this->assign('info',$info);
            $this->display();
        }
    }
}