<?php
namespace Shop\Controller;

use Home\Controller\BaseController;

/**
 * 商品控制器
 */
class GoodsController extends BaseController
{
  public function _initialize()
  {
    parent::_initialize();

    $this->assign('gl',2);
  }
  /**
   * 商品分类
   */
  public function index()
  {
    $type_model = M('shop_goodstype');
    if(IS_AJAX){
      $page = I('post.n') ? I('post.n') : 1;
      $limit = 10;
      $start = ($page - 1) * $limit;
      $check = I('check','');
      $map['group'] = 1;
      $map['status'] =1;
      $map['pid'] = ['neq',0];
      if(!empty($check)){
        $map['pid'] = $check;
      }else{
        $type = $type_model
          ->where(['group'=>1,'pid'=>0])
          ->field('id,title')
          ->select();
        $pid_array = array_column($type, 'id');
        $map['pid'] = ['IN',$pid_array];
      }
      $info = $type_model->where($map)->limit($start,$limit)->select();
      // dump($info);die;
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
      $pid = I('pid','');
      //普通商品一级分类
      $type = $type_model
              ->where(['group'=>1,'pid'=>0])
              ->field('id,title')
              ->select();
      $this->assign('pid',$pid);
      $this->assign('type',$type);

      //搜索
      $search = M('shop_search')->where(['status'=>1])->field('id,word')->select();
      $user_kw_info = cookie('user_search');
      $this->assign('user_kw_info',$user_kw_info);
      $this->assign('shop_kw_info',$search);
      $this->display();
    }
  }

  /*
   * 商品列表页
   */
  public function goods_list()
  {
    if(IS_AJAX){

      $goods_object = M('shop_goods');

      $page = I('post.n') ? I('post.n') : 1;
      $limit = 10;
      $start = ($page - 1) * $limit;
      $type = I('type','');

      if(!empty($type)){
        $map['type'] = $type;
      }

      if($group = I('group','')){
        $map['group'] = $group;
      }

      if($columns = I('columns','')){
        $map[] = "FIND_IN_SET($columns,columns)";
      }

      $map['status'] = 1;

      $search_kw = I('search_kw','');
      if(!empty($search_kw)){
        $map['title'] = ['like','%'.$search_kw.'%'];
      }

      //排序
      $order_status = I('order','1');
      switch ($order_status) {
        case '1':
          $order = "sales_volume DESC";
          break;
        case '2':
          $order = "sales_volume ASC";
          break;
        case '3':
          $order = "sale_price DESC";
          break;
        case '4':
          $order = "sale_price ASC";
          break;
        default:
          $order = "sales_volume DESC";
          break;
      }

      $info = $goods_object->where($map)->field('id,group,cover,title,integral_price,sale_integral,sale_price,original_price,sales_volume')->order($order)->limit($start,$limit)->select();

      //判断秒杀价
      $check_ids= '';
      foreach ($info as $key => $value) {
        $info[$key]['seckill_price'] = '';
        if($key==0){
          $check_ids = $value['id'];
        }else{
          $check_ids .=",".$value['id'];
        }
      }
      if(!empty($info)){
        $nowTime = datetime();
        $seckill_info = M('shop_seckill_goods')
                        ->join('oc_shop_seckill ON oc_shop_seckill.id = oc_shop_seckill_goods.seckill_id','LEFT')
                        ->field('oc_shop_seckill_goods.goods_id,
                                 oc_shop_seckill_goods.stock,
                                 oc_shop_seckill_goods.seckill_sales,
                                 oc_shop_seckill_goods.seckill_price
                                ')
                        ->where(['oc_shop_seckill_goods.goods_id'=>['IN',$check_ids],
                                 'oc_shop_seckill.start_time'=>['lt',$nowTime],
                                 'oc_shop_seckill.end_time'=>['gt',$nowTime],
                                 'oc_shop_seckill.status'=>1,
                                 'oc_shop_seckill_goods.status'=>1
                                ])
                        ->select();
        if(!empty($seckill_info)){
          foreach ($info as $key => $value) {
            foreach ($seckill_info as $k => $v) {
              if($value['id']==$v['goods_id'] && $v['stock']>$v['seckill_sales']){
               $info[$key]['seckill_price'] = $v['seckill_price'];
              }
            }
          }
        }
      }

      if($info){
        $this->assign('info',$info);
        $html =$this->fetch('ajax_goods_list');
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
      $type_id = I('type_id','');
      $group = I('group','');
      $columns = I('columns','');

      $search_kw = I('search_kw','');
      $user_kw_info = cookie('user_search')?cookie('user_search'):[];
      if(!empty($search_kw)){
        //最近搜索
        foreach ($user_kw_info as $key => $value) {
          if($value == $search_kw){
            $unset_key = $key;
            unset($user_kw_info[$key]);
          }
        }
        if(empty($user_kw_info)){
          $user_kw_info[] = $search_kw;
        }else{
          $user_kw_info = array_slice($user_kw_info,0,9);
          array_unshift($user_kw_info,$search_kw); //数组开头插入
        }
        cookie('user_search',$user_kw_info,86400 * 7);
      }
      $shop_kw_info = M('shop_search')->field('word')->where(['status'=>1])->order('id DESC')->select();

      $this->assign('user_kw_info',$user_kw_info);
      $this->assign('shop_kw_info',$shop_kw_info);
      $this->assign('columns',$columns);
      $this->assign('search_kw',$search_kw);
      $this->assign('type',$type_id);
      $this->assign('group',$group);
      $this->display();
    }
  }


  /*
   *商品秒杀列表
   */
  public function seckill_list()
  {
    if(IS_AJAX){
      $page = I('post.n') ? I('post.n') : 1;
      $limit = 10;
      $start = ($page - 1) * $limit;
      $change = I('change');
      $map['oc_shop_seckill_goods.status'] =1;
      $map['oc_shop_goods.status'] =1;
      $map['oc_shop_seckill_goods.seckill_id'] = $change;

      $info = M('shop_seckill_goods')
              ->join('oc_shop_goods ON oc_shop_seckill_goods.goods_id = oc_shop_goods.id','LEFT')
              ->field('oc_shop_goods.cover,
                       oc_shop_goods.title,
                       oc_shop_goods.id,
                       oc_shop_goods.original_price,
                       oc_shop_seckill_goods.seckill_price,
                       oc_shop_seckill_goods.stock,
                       oc_shop_seckill_goods.seckill_sales
                       ')
              ->where($map)
              ->limit($start,$limit)
              ->select();
      if($info){
        foreach ($info as $key => $value) {
          if($value['seckill_sales']>0){
            $info[$key]['sales_point'] = floor($value['seckill_sales']/$value['stock']*100);
          }else{
            $info[$key]['sales_point'] = 0;
          }
        }
        $this->assign('info',$info);
        $html =$this->fetch('ajax_seckill_list');
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
      $id = I('id','');
      $nowTime = datetime();
      $seckill = M('shop_seckill')
                ->where(['goods_num'=>['gt',0],'status'=>1,'end_time'=>['gt',$nowTime]])
                ->field('id,title,end_time,start_time')
                ->order('start_time ASC')
                ->limit('0,3')
                ->select();
      foreach ($seckill as $key => $value) {
        $start_time = strtotime($value['start_time']);
        if(strtotime($nowTime) >$start_time){
          $seckill[$key]['title_info'] = '抢购中';
          $seckill[$key]['count_time'] = strtotime($value['end_time']);
          $distance[$value['id']] = '距结束';
          $subtitle[$value['id']] = '抢购中 先下单先得哦';

        }else{
          $seckill[$key]['title_info'] = '即将开始';
          $seckill[$key]['count_time'] = strtotime($value['start_time']);
          $distance[$value['id']] = '距开始';
          $subtitle[$value['id']] = '即将开始 先下单先得哦';

        }
        if($value['id']==$id){
          $seckill[$key]['active'] = $id;
        }
      }
      $distance = json_encode($distance);
      $subtitle = json_encode($subtitle);

      $this->assign('distance',$distance);
      $this->assign('subtitle',$subtitle);
      $this->assign('seckill',$seckill);
      $this->display();
    }
  }

  /*
   * 商品详情页
   */
  public function detail()
  {

    $id = I('id','');
    cookie('before_reg', $this->current_url, $this->cookie_expire);
    if(empty('id'))
      exit();
    $nowTime = datetime(); // 现在时间 create_time update_time

    $openid = $this->openid;
    $user_id= M('user')->where(['openid'=>$openid])->getfield('id');
    //记录用户浏览 user_id
    if(!empty($user_id)){
      $browse_object = M('shop_goods_browse');
      $browse_data['uid'] = $user_id;
      $browse_data['gid'] = $id;
      $browse_info = $browse_object->where([$browse_data])->find();
      if(empty($browse_info)){
        $browse_data['create_time'] = $nowTime;
        $browse_data['update_time'] = $nowTime;
        $browse_date['state'] =1;
        M('shop_goods_browse')->add($browse_data);
      }else{
        $browse_save['num'] = $browse_info['num']+1;
        $browse_save['update_time'] = $nowTime;
        $browse_object
          ->where(['id'=>$browse_info['id']])
          ->save($browse_save);
      }
    }

    //是否收藏
    $uid = $this->uid;
    $check_on = '';
    if(!empty($uid)){
      $check_collect = M('shop_goods_collect')->where(['uid'=>$uid,'gid'=>$id])->find();
      if($check_collect){
        $check_on = 'on';
      }
    }

    //商品基本信息
    $goods_object = M('shop_goods');
    $goods_list = $goods_object->where(['id'=>$id,'status'=>1])->find();

    if(empty($goods_list)){
      $this->error('该商品已下架！');
    }

    //优惠券信息
    $coupons_str_info = '';
    if($goods_list['coupons']){
      $coupons_info = M('shop_coupon')
                      ->where(['id'=>['IN',$goods_list['coupons']]])
                      ->select();
      foreach ($coupons_info as $key => $value) {
        if($key==0){
          $coupons_str_info = $value['title'].'(￥'.$value['price'].')';
        }else{
          $coupons_str_info .= '　'.$value['title'].'(￥'.$value['price'].')';
        }
      }
    }
    $this->assign('coupons_str_info',$coupons_str_info);


    // 规格 对应 价格 库存表
    $spec_items = D('goods')->get_spec($id);
    $this->assign('spec_items', $spec_items);
    $spec_goods_price  = M('spec_goods_price')->where(['goods_id'=>$id])->getField("key,shop_price,store_count");
    $this->assign('spec_goods_price', $spec_goods_price);//规格参数

    $goods_object->save(['id'=>$id,'browse'=>$goods_list['browse']+1]);//商品浏览量 +1

    //商品详情 轮播
    $banner = explode(',',$goods_list['images']);

    //======判断是否是秒杀商品=====
    //1.该商品 是否有未结束的秒杀
    $seckill =  M('shop_seckill_goods')
                ->join('oc_shop_seckill ON
                        oc_shop_seckill.id = oc_shop_seckill_goods.seckill_id',
                        'LEFT')
                ->field('oc_shop_seckill.id,
                         oc_shop_seckill.start_time,
                         oc_shop_seckill.end_time,
                         oc_shop_seckill.title,
                         oc_shop_seckill_goods.seckill_price,
                         oc_shop_seckill_goods.stock,
                         oc_shop_seckill_goods.seckill_sales
                         ')
                ->where(['oc_shop_seckill_goods.goods_id'=>$id,
                         'oc_shop_seckill.status'=>1,
                         'oc_shop_seckill.end_time'=>['gt',$nowTime]])
                ->order('oc_shop_seckill.start_time DESC')
                ->select();
    $maxnum = 0;
    $assign_seckill['count_time'] = ''; //防止html notice
    $assign_seckill['seckill_price'] = '';//防止html notice
    $assign_seckill['subtitle'] = '';//防止html notice
    $assign_seckill['seckill_sales'] = 0;//防止html notice
    $assign_seckill['stock'] = 0;//防止html notice
    if(!empty($seckill)){
      //2.判断秒杀状态
      $positive_array = [];
      $negative_array = [];
      foreach ($seckill as $key => $value) {
        $maxnum = $value['stock'];
        if($value['stock']>$value['seckill_sales']){
          $gap=strtotime($value['start_time'])-time();
          if($gap<=0){ //正在进行的
            $negative_array[$key] = $gap;
          }else{ //将要开始的
            $positive_array[$key] = $gap;
          }
        }
      }

      if(!empty($negative_array)){
        $k=array_search(max($negative_array),$negative_array);
        $seckill[$k]['count_time'] = strtotime($seckill[$k]['end_time']);
        $seckill[$k]['subtitle'] = '距结束';
        $assign_seckill = $seckill[$k];

      }elseif(!empty($positive_array)){
        $k=array_search(min($positive_array),$positive_array);
        $seckill[$k]['count_time'] = strtotime($seckill[$k]['start_time']);
        $seckill[$k]['subtitle'] = '距开始';
        $assign_seckill = $seckill[$k];
      }
    }

    //运费
    $postage = M('shop_set')->field('postage_free')->find();


    //猜你喜欢的  同类别下的两件商品 按销量排序
    $youlike = M('shop_goods')
              ->where(['status'=>1,'group'=>$goods_list['group'],'type'=>$goods_list['type'],'id'=>['NEQ',$id]])
              ->order('sales_volume DESC')
              ->field('id,cover,title,original_price,sale_price')
              ->limit('2')
              ->select();

    //商品评论
    $review_info = M('shop_goods_review')
                    ->join('oc_user ON oc_user.admin_uid = oc_shop_goods_review.uid','LEFT')
                    ->field('oc_user.nickname,
                             oc_user.headimgurl,
                             oc_shop_goods_review.rating,
                             oc_shop_goods_review.content,
                             oc_shop_goods_review.reply,
                             oc_shop_goods_review.create_time
                            ')
                    ->where(['oc_shop_goods_review.gid'=>$id,'oc_shop_goods_review.status'=>1])
                    ->order('oc_shop_goods_review.id DESC')
                    ->select();

    $review_num = count($review_info);
    $review_info = array_slice($review_info, 0, 2);

    //分享
    $jsapi = R('Home/Weixin/jsapi', [['onMenuShareTimeline',
                                      'onMenuShareAppMessage',
                                      'onMenuShareQQ',
                                      'onMenuShareWeibo',
                                      'onMenuShareQZone']]);
    $wechatShare["title"] = $goods_list['title'];
    $wechatShare["desc"] = $goods_list['subtitle'];
    $wechatShare["link"] =$this->current_url;
    $wechatShare["imgUrl"] = XILUDomain().getpics($goods_list['cover'],'cover');
    $wechatShare["type"] = 'link';


    $this->assign('maxnum',$maxnum);   //库存
    $this->assign('jsapi', $jsapi);
    $this->assign('wechatShare',$wechatShare);
    $this->assign('review_num',$review_num);
    $this->assign('review_info',$review_info); //评论信息
    $this->assign('check_on',$check_on);//是否收藏
    $this->assign('goods_list',$goods_list);
    $this->assign('banner',$banner);
    $this->assign('seckill',$assign_seckill);
    $this->assign('postage',$postage);
    $this->assign('youlike',$youlike);
    $this->display();
  }

  /*
   * 商品收藏 同时增加商品收藏量
   */
  public function collect()
  {
    if(IS_AJAX){
      $check  = I('check',''); // 1取消 0关注
      $gid = I('gid','');
      $nowTime = datetime();
      $uid = $this->uid;
      //验证登录
      if(empty($uid)){
        $this->error('立即登录',U('Home/Member/login'));
      }

      $collect_obejct = M('shop_goods_collect');
      $collect_obejct ->startTrans();
      if($check){
        $collect_res = $collect_obejct->where(['uid'=>$uid,'gid'=>$gid])->delete();
        $goods_res = M('shop_goods')
                    ->execute("UPDATE oc_shop_goods SET collect = collect-1 WHERE id = $gid");
        $success_info = '取消成功';
      }else{
        $collect_data['uid'] = $uid;
        $collect_data['gid'] = $gid;
        $collect_data['create_time'] = $nowTime;
        $collect_data['update_time'] = $nowTime;
        $collect_data['status'] = 1;

        $collect_res  = $collect_obejct->add($collect_data);
        $goods_res = M('shop_goods')
                    ->execute("UPDATE oc_shop_goods SET collect = collect+1 WHERE id = $gid");
        $success_info = '收藏成功';

      }

      if($collect_res && $goods_res){
        $collect_obejct->commit();
        $this->success($success_info);
      }else{
        $collect_obejct->rollback();
        $this->error('系统繁忙请稍后再试！');
      }
    }
  }

  /*
   *加入购物车 操作
   */
  public function addToCart()
  {
    if(IS_AJAX){
      $uid = $this->uid;
      if(empty($uid)){
        $this->error('立即登录',U('Home/Member/login'));
      }

      $cart_object  = M('shop_cart');

      $post = I('post.','');
      $gid = $post['gid'];
      $stcok = $post['stock'];
      $key =I('post.key','');
      $cart_info = $cart_object
                    ->field('id,goodsnum')
                    ->where(['gid'=>$gid,'uid'=>$uid,'key'=>$key])
                    ->find();

      if(!empty($cart_info)){
        $cart_info['goodsnum'] +=$post['goodsnum'];
        if(!empty($stcok) && $cart_info['goodsnum'] > $stcok){
          $this->success('库存不足');
        }
        $cart_res = $cart_object->save($cart_info);

      }else{
        $post['uid'] = $uid;
        $post['key'] =$key;
        $post['create_time'] = datetime();
        $post['update_time'] = datetime();
        if(!$data = $cart_object->create($post)){
          ptrace($cart_object->getError());
          $this->error('系统繁忙请稍后再试！');
        }
        $cart_res = $cart_object->add();

      }

      if($cart_res){
          $this->success('加入成功',U('Shop/Cart/index'));
        } else{
          $this->error('系统繁忙请稍后再试！');
      }
    }
  }

  /**
   * 商品评论列表
   */
  public function review_list()
  {
    if(IS_AJAX){
      $page = I('post.n') ? I('post.n') : 1;
      $limit = 10;
      $start = ($page - 1) * $limit;
      $gid = I('gid','');
      $map['oc_shop_goods_review.gid']=$gid;
      $map['oc_shop_goods_review.status'] =1;

      $info = M('shop_goods_review')
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

      $gid = I('gid','');
      $this->assign('gid',$gid);
      $this->display();
    }

  }


  /**
   * 商品列表页 添加购物车 的html 数据
   */
  public function item_detail()
  {
    if(IS_AJAX){
      $id = I('id','');
      //商品基本信息
      $goods_object = M('shop_goods');
      $goods_list = $goods_object->where(['id'=>$id,'status'=>1])->find();
      if(empty($goods_list)){
        $result['status'] = 0;
        goto ok;
      }
      $nowTime = datetime(); // 现在时间 create_time update_time

      // 规格 对应 价格 库存表
      $spec_items = D('goods')->get_spec($id);
      $spec_goods_price  = M('spec_goods_price')->where(['goods_id'=>$id])->getField("key,shop_price,store_count");
      $seckill =  M('shop_seckill_goods')
              ->join('oc_shop_seckill ON
                      oc_shop_seckill.id = oc_shop_seckill_goods.seckill_id',
                      'LEFT')
              ->field('oc_shop_seckill.id,
                       oc_shop_seckill.start_time,
                       oc_shop_seckill.end_time,
                       oc_shop_seckill.title,
                       oc_shop_seckill_goods.seckill_price,
                       oc_shop_seckill_goods.stock,
                       oc_shop_seckill_goods.seckill_sales
                       ')
              ->where(['oc_shop_seckill_goods.goods_id'=>$id,
                       'oc_shop_seckill.status'=>1
                       ])
              ->find();

      $maxnum = 0;

      if(!empty($seckill)){
        //2.判断秒杀状态
        if($seckill['stock']>$seckill['seckill_sales']){
          $maxnum = $seckill['stock'] - $seckill['seckill_sales'];
        }
      }

      $this->assign('spec_goods_price', $spec_goods_price);//规格参数
      $this->assign('goods_list', $goods_list);
      $this->assign('spec_items', $spec_items);
      $this->assign('maxnum', $maxnum);
      $this->assign('seckill', $seckill);

      $result['html'] = $this->fetch('ajax_goodsitem_detail');
      $result['status'] = 1;

      ok:
      $this->ajaxReturn($result);

    }
  }

}