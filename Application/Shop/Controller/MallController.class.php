<?php
namespace Shop\Controller;

use Home\Controller\BaseController;

/**
 * 开店必备 \ 个护美妆控制器
 */
class MallController extends BaseController
{
  public function _initialize()
  {
    parent::_initialize();
    $this->assign('gl',1);
  }

  /**
   * 开店必备
   */
  public function index()
  {
    //轮播
    $banner    = M('cms_slider')
                  ->where(['type'=>2])
                  ->field('url,cover')
                  ->order('sort DESC')
                  ->select();

    //分类
    $type_info = M('shop_goodstype')
                  ->field('id,title,icon')
                  ->where(['group'=>3,'status'=>1,'check'=>0])
                  ->order('id DESC')
                  ->limit(7)
                  ->select();
    foreach ($type_info as $key => $value) {
      if($key<4){
        $type_fir_info[] = $value; //第一栏分类
      }else{
        $type_sec_info[] = $value; //第二栏分类
      }
    }
    $this->assign('type_fir_info',$type_fir_info);
    $this->assign('type_sec_info',$type_sec_info);

    //栏目图片
    $columns_info = M('shop_columns')->field('cover')->where(['group'=>5])->limit(3)->order('sort DESC')->select();
    $this->assign('columns_info',$columns_info);

    //推荐栏目商品
    $columns_goods_info = M('shop_goods')
                          ->field('id,title,cover,original_price,sale_price,columns')
                          ->where(['status'=>1,'group'=>3,'columns'=>['exp','is not null']])
                          ->order('id DESC')

                          ->select();
    $columns_goods_fir_info ='';
    $columns_goods_sec_info ='';
    $columns_goods_thi_info = '';
    foreach ($columns_goods_info as $key => $value) {
      if(strstr($value['columns'],'1')){
        $columns_goods_fir_info[] = $value;
      }
      if(strstr($value['columns'],'2')){
        $columns_goods_sec_info[] = $value;
      }
      if(strstr($value['columns'],'3')){
        $columns_goods_thi_info[] = $value;
      }
    }


    $this->assign('columns_goods_fir_info',$columns_goods_fir_info);
    $this->assign('columns_goods_sec_info',$columns_goods_sec_info);
    $this->assign('columns_goods_thi_info',$columns_goods_thi_info);


    //文章单页
    $page_info  = M('shop_page')
                  ->where(['type'=>1])
                  ->field('id,type,title,url,cover')->select();

    //商品信息
    // $goods_info = M('shop_goods')
    //                ->where(['status'=>1,'group'=>3])
    //                ->field('id,title,cover,original_price,sale_price')
    //                ->order('sales_volume DESC')
    //                ->limit(4)
    //                ->select();

    // $this->assign('goods_info',$goods_info);
    $this->assign('page_info',$page_info);
    $this->assign('banner',$banner);
    $this->display();
  }

  /**
   * 个护美妆
   */
  public function  mall()
  {
    //轮播
    $banner = M('cms_slider')
              ->where(['type'=>3])
              ->field('url,cover')
              ->order('sort DESC')
              ->select();
    $this->assign('banner',$banner);

    //栏目图片
    $columns_info = M('shop_columns')->field('cover')->where(['group'=>4])->limit(3)->order('sort DESC')->select();
    $this->assign('columns_info',$columns_info);

    //栏目推荐商品
    $columns_goods_info = M('shop_goods')
                          ->field('id,title,cover,original_price,sale_price,columns')
                          ->where(['status'=>1,'group'=>2,'columns'=>['exp','is not null']])
                          ->order('id DESC')
                          ->select();
    $columns_goods_fir_info ='';
    $columns_goods_thi_info ='';
    foreach ($columns_goods_info as $key => $value) {
      if(strstr($value['columns'],'1')){
        $columns_goods_fir_info[] = $value;
      }

      if(strstr($value['columns'],'3')){
        $columns_goods_thi_info[] = $value;
      }
    }

    $this->assign('columns_goods_fir_info',$columns_goods_fir_info);
    $this->assign('columns_goods_thi_info',$columns_goods_thi_info);

    //分类
    $type_info = M('shop_goodstype')
                  ->field('id,title,icon')
                  ->where(['group'=>2,'status'=>1,'check'=>0])->select();
    $this->assign('type_info',$type_info);


    //文章单页
    $page_info  = M('shop_page')
                  ->where(['type'=>2])
                  ->field('id,type,title,url,cover')
                  ->order('id DESC')
                  ->select();
    $this->assign('page_info',$page_info);


    $this->display();
  }

  /**
   * 美妆详情
   */
  public function mallDetail()
  {
    $id = I('id','')?:exit;
    $page_info = M('shop_page')->where(['id'=>$id])->find();
    $this->assign('page_info',$page_info);


    $columns_goods_info = M('shop_goods')
                          ->field('id,title,cover,original_price,sale_price,columns')
                          ->where(['status'=>1,'group'=>2,'_string'=>'FIND_IN_SET('.$id.',columns)'])
                          ->order('id DESC')

                          ->select();
    $this->assign('columns_goods_info',$columns_goods_info);

    $this->display();
  }

  public function page_detail()
  {
    $id  = I('id','');
    if(empty($id))
      exit;
    $page_info = M('shop_page')->where(['id'=>$id])->find();
    $this->assign('page_info',$page_info);
    $this->display();
  }
}