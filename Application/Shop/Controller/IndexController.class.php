<?php
namespace Shop\Controller;

use Home\Controller\BaseController;

/**
 * 默认控制器
 */
class IndexController extends BaseController
{
  public function _initialize()
  {
    parent::_initialize();
    $this->assign('gl',1);
  }

  /**
   * 首页
   */
  public function index()
  {

    //频道
    $type = M('shop_goodstype')->where(['group'=>1,'pid'=>[0]])->field('id,title')->select();
    $this->assign('type',$type);

    //搜索
    $user_search = cookie('user_search');
    $search = M('shop_search')->where(['status'=>1])->field('id,word')->select();
    $this->assign('user_search_info',$user_search);
    $this->assign('shop_search_info',$search);

    //登录
      cookie('before_reg', $this->current_url, $this->cookie_expire);
    //轮播
    $banner = M('cms_slider')->where(['type'=>1])->field('url,cover')->select();
    $this->assign('banner',$banner);

    //链接

    //头条
    $bulletin = M('shop_bulletin')->where(['status'=>1])->field('id,title')->select();

    $this->assign('bulletin',$bulletin);

    //秒杀
    $now_time = datetime();
      // 1.正在进行的秒杀场次  必须有商品
    $seckill = M('shop_seckill')
              ->where(['start_time'=>['lt',$now_time],
                       'goods_num'=>['gt',0],
                       'status'=>1,
                      'end_time'=>['gt',$now_time]])
              ->field('id,start_time,end_time,title')
              ->order('start_time ASC')
              ->find();

    // 2.如果没有正在进行的秒杀场次 取有商品的下一场
    if(empty($seckill['id'])){
      $check_seckill = false;
      $seckill = M('shop_seckill')
              ->where(['status'=>1,'start_time'=>['gt',$now_time],'goods_num'=>['gt',0]])
              ->field('id,start_time,end_time','title')
              ->order('start_time ASC')
              ->find();

      $seckill['remark'] = '距离开始';
      $seckill['count_time'] = empty($seckill['start_time'])?'':strtotime($seckill['start_time']);
    }else{
      $check_seckill = true;
      $seckill['remark'] = '距离结束';
      $seckill['count_time'] = strtotime($seckill['end_time']);
    }


    $seckill_goods  = '';
    if(!empty($seckill['id'])){
      $seckill_goods = M('shop_seckill_goods')
                      ->join('oc_shop_goods ON oc_shop_seckill_goods.goods_id =oc_shop_goods.id','LEFT')
                      ->field('oc_shop_goods.id,
                              oc_shop_goods.title,
                              oc_shop_goods.cover,
                              oc_shop_seckill_goods.seckill_price,
                              oc_shop_seckill_goods.stock,
                              oc_shop_seckill_goods.seckill_sales
                          ')
                      ->where(['oc_shop_seckill_goods.seckill_id'=>$seckill['id'],'oc_shop_goods.status'=>1])
                      ->order('oc_shop_seckill_goods.id DESC')
                      ->select();
        // //限购数量有没有达到
        // foreach ($seckill_goods as $key => $value) {
        //   if($value['stock']<=$value['seckill_sales']){
        //     unset($seckill_goods[$key]);
        //   }
        // }
    }

    // 3.页面判断 秒杀场次为空 不显示秒杀栏目
    $this->assign('seckill',$seckill);
    // 4.页面判断 秒杀商品为空 不显示秒杀栏目
    $this->assign('seckill_goods',$seckill_goods);

    //广告
    $ad = M('shop_columns')->where(['group'=>3])->field('id,url,cover')->order('id ASC')->select();
    $this->assign('ad',$ad);

    //栏目 oc_shop_columns oc_shop_goods
    $check_goods_ids = [];
    $columnsInfo =  M('shop_columns')->where(['group'=>1])
                ->field('id,title,url,icon,cover')
                ->order('sort DESC')->select();
    foreach ($columnsInfo as $key => $value) {
      // $key == 2?$limit=2:$limit=4;
      $map[$key]['oc_shop_goods.status'] =1;
      $map[$key][] ='FIND_IN_SET('.$value['id'].',oc_shop_goods.property)';
      $value['goods'] = M('shop_goods')
                                    ->field('oc_shop_goods.id,
                                             oc_shop_goods.cover,
                                             oc_shop_goods.title,
                                             oc_shop_goods.original_price,
                                             oc_shop_goods.sale_price
                                            ')
                                    ->where($map[$key])
                                    ->order('oc_shop_goods.id DESC')
                                    // ->limit($limit)
                                    ->select();

        foreach ($value['goods'] as $k2 => $v2) {
          $v2['seckill_price'] = '';
          if(!empty($seckill_goods) && $check_seckill ){
            foreach ($seckill_goods as $k => $v) {
              if($v2['id'] ==$v['id']){
                $v2['seckill_price'] = $v['seckill_price'];
              }
            }
          }
           $value['goods'][$k2] = $v2;
        }
      $columnsInfo[$key] =  $value;

    }

    $this->assign('columnsInfo',$columnsInfo);

    //客服
    $shop_mobile = M('shop_set')->getfield('shop_mobile');
    $this->assign('shop_mobile',$shop_mobile);
    // 首页图标
    $column =M('shop_column')->where(['status'=>1])->order('sort asc,id desc')->limit(10)->select();
    $this->assign('column',$column);
    $this->display();
  }

}