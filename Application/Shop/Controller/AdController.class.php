<?php
namespace Shop\Controller;

use Home\Controller\BaseController;

/**
 * 首页广告控制器
 */
class AdController extends BaseController
{
  public function _initialize()
  {
    parent::_initialize();
  }

  /**
   * 广告页 NO.1
   */
  public function index()
  {

    $goods_object = M('shop_goods');
    $goods_info = $goods_object

                    ->field('oc_shop_goods.id,
                             oc_shop_goods.cover,
                             oc_shop_goods.sale_price,
                             oc_shop_goods.original_price,
                             oc_shop_goods.title,
                             oc_shop_goods.ad_a
                            ')
                    ->where(['oc_shop_goods.status'=>1,
                             'oc_shop_goods.ad_a'=>['exp','is not null']
                            ])
                    ->select();

    $gids = array_column($goods_info, 'id');
    $nowTime = datetime();
    $seckill_info = M('shop_seckill_goods')
                    ->join('oc_shop_seckill ON oc_shop_seckill.id = oc_shop_seckill_goods.seckill_id','LEFT')
                    ->field('oc_shop_seckill_goods.seckill_price,
                             oc_shop_seckill_goods.stock,
                             oc_shop_seckill_goods.goods_id,
                             oc_shop_seckill_goods.seckill_sales
                            ')
                    ->where(['oc_shop_seckill.status'=>1,
                             'oc_shop_seckill_goods.status'=>1,
                             'oc_shop_seckill_goods.goods_id'=>['IN',$gids],
                             'oc_shop_seckill.start_time'=>['lt',$nowTime],
                             'oc_shop_seckill.end_time'=>['gt',$nowTime]

                            ])
                    ->select();
   $new_seckill_goods  = [];
    foreach ($seckill_info as $k => $v) {
      if( $v['stock'] > $v['seckill_sales']){
       $new_seckill_goods[$v['goods_id']] = $v['seckill_price'];
      }
    }

    $goods_info_a=[];
    $goods_info_b=[];
    $goods_info_c=[];
    $goods_info_d=[];
    foreach ($goods_info as $key => $value) {
      if(!empty($new_seckill_goods[$value['id']])){
        $value['sale_price'] = $new_seckill_goods[$value['id']];
      }
      if(strpos($value['ad_a'],'1')!==false){
        $goods_info_a[] =$value;
      }
      if(strpos($value['ad_a'],'2')!==false){
        $goods_info_b[] =$value;
      }
      if(strpos($value['ad_a'],'3')!==false){
        $goods_info_c[] =$value;
      }
      if(strpos($value['ad_a'],'4')!==false){
        $goods_info_d[] =$value;
      }
    }




    $pagepic = M('shop_columns')->where(['id'=>7])->getfield('pagepic');


    $this->assign('pagepic',$pagepic);
    $this->assign('goods_info_a',$goods_info_a);
    $this->assign('goods_info_b',$goods_info_b);
    $this->assign('goods_info_c',$goods_info_c);
    $this->assign('goods_info_d',$goods_info_d);
    $this->display();
  }

  /**
   *广告页 NO.2
   */
  public function  ad()
  {

    $goods_info = M('shop_goods')
                  ->where(['ad_b'=>1,'status'=>1])
                  ->field('id,title,cover,sale_price,original_price')
                  ->order(['ad_b_sort DESC'])
                  ->select();

    $gids = array_column($goods_info, 'id');
    $nowTime = datetime();
    $seckill_info = M('shop_seckill_goods')
                    ->join('oc_shop_seckill ON oc_shop_seckill.id = oc_shop_seckill_goods.seckill_id','LEFT')
                    ->field('oc_shop_seckill_goods.seckill_price,
                             oc_shop_seckill_goods.stock,
                             oc_shop_seckill_goods.goods_id,
                             oc_shop_seckill_goods.seckill_sales
                            ')
                    ->where(['oc_shop_seckill.status'=>1,
                             'oc_shop_seckill_goods.status'=>1,
                             'oc_shop_seckill_goods.goods_id'=>['IN',$gids],
                             'oc_shop_seckill.start_time'=>['lt',$nowTime],
                             'oc_shop_seckill.end_time'=>['gt',$nowTime]

                            ])
                    ->select();

    $new_seckill_goods  = [];
    foreach ($seckill_info as $k => $v) {
      if( $v['stock'] > $v['seckill_sales']){
       $new_seckill_goods[$v['goods_id']] = $v['seckill_price'];
      }
    }

    foreach ($goods_info as $key => $value) {
      if(!empty($new_seckill_goods[$value['id']])){
        $value['sale_price'] = $new_seckill_goods[$value['id']];
      }
      $goods_info[$key] = $value;
    }

    $pagepic = M('shop_columns')->where(['id'=>8])->getfield('pagepic');


    $this->assign('pagepic',$pagepic);

    $this->assign('goods_info',$goods_info);
    $this->display();
  }

}