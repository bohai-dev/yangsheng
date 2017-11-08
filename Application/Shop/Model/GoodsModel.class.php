<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Shop\Model;
use Think\Model;
/**
 * 商品模型
 */
class GoodsModel extends Model {
    /**
     * 模块名称
     * @author jry <598821125@qq.com>
     */
    public $moduleName = 'Shop';

    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'shop_goods';


    /**
     * 自动验证规则
     */
    protected $_validate = array(
        array('type', 'require', '请选择商品类别', self::MUST_VALIDATE  , 'regex', self::MODEL_BOTH),
        array('title', 'require', '商品名称不能为空', self::MUST_VALIDATE  , 'regex', self::MODEL_BOTH),
        array('title', '1,80', '商品名称长度为1-80个字符', self::MUST_VALIDATE , 'length'),
        array('subtitle', 'require', '商品描述不能为空', self::MUST_VALIDATE  , 'regex', self::MODEL_BOTH),
        array('cover', 'require', '请上传商品封面', self::MUST_VALIDATE  , 'regex', self::MODEL_BOTH),
        array('images', 'require', '请上传商品多图', self::MUST_VALIDATE  , 'regex', self::MODEL_BOTH),
        array('original_price', 'require', '商品原价不能为空', self::MUST_VALIDATE  , 'regex', self::MODEL_BOTH),
        array('original_price', 'checkPrice', '商品原价格式不正确', self::MUST_VALIDATE  , 'function', self::MODEL_BOTH),
        array('original_price', 'getOriginal', '出现即报错', self::MUST_VALIDATE  , 'callback', self::MODEL_BOTH),
        array('sale_price', 'require', '销售价格不能为空', self::MUST_VALIDATE  , 'regex', self::MODEL_BOTH),
        array('sale_price', 'checkPrice', '销售价格格式不正确', self::MUST_VALIDATE  , 'function', self::MODEL_BOTH),
        array('sale_price', 'comparePrice', '销售价格需小于商品原价', self::MUST_VALIDATE  , 'callback', self::MODEL_BOTH),
        array('collect', 'is_numeric', '收藏量格式不正确', self::EXISTS_VALIDATE  , 'function', self::MODEL_BOTH),
        array('sales_volume', 'is_numeric', '销售量格式不正确', self::EXISTS_VALIDATE  , 'function', self::MODEL_BOTH),
        array('sale_integral', 'require', '购买积分不能为空', self::EXISTS_VALIDATE  , 'regex', self::MODEL_BOTH),
        array('sale_integral', 'is_numeric', '购买积分格式不正确', self::EXISTS_VALIDATE  , 'function', self::MODEL_BOTH),
        array('integral_price', 'require', '购买价格不能为空', self::EXISTS_VALIDATE  , 'regex', self::MODEL_BOTH),
        array('integral_price', 'checkPrice', '购买价格格式不正确', self::EXISTS_VALIDATE  , 'function', self::MODEL_BOTH),
        array('back_integral', 'require', '赠送积分不能为空', self::MUST_VALIDATE  , 'regex', self::MODEL_BOTH),
        array('back_integral', 'is_numeric', '赠送积分格式不正确', self::MUST_VALIDATE  , 'function', self::MODEL_BOTH),
        array('postage', 'require', '运费不能为空', self::EXISTS_VALIDATE  , 'regex', self::MODEL_BOTH),
        array('postage', 'checkPrice', '运费格式不正确', self::EXISTS_VALIDATE  , 'function', self::MODEL_BOTH),
        array('explain', 'require', '购买说明不能为空', self::EXISTS_VALIDATE  , 'regex', self::MODEL_BOTH),
        array('description', 'require', '商品介绍不能为空', self::MUST_VALIDATE  , 'regex', self::MODEL_BOTH),
        // array('spec', 'require', '规格参数不能为空', self::MUST_VALIDATE  , 'regex', self::MODEL_BOTH),
        array('aftersale', 'require', '售后保障不能为空', self::MUST_VALIDATE  , 'regex', self::MODEL_BOTH),
        array('ad_b', 'get_ad_b', '出现即报错', self::EXISTS_VALIDATE  , 'callback', self::MODEL_BOTH),
        array('ad_b_sort', 'check_ad_b_sort', '广告2排序不能为空', self::EXISTS_VALIDATE  , 'callback', self::MODEL_BOTH),
        array('ad_b_sort', 'check_ad_b_num', '广告2排序格式不对', self::EXISTS_VALIDATE  , 'callback', self::MODEL_BOTH),

    );

    /**
     * 自动完成规则
     */
    protected $_auto = array(
        array('property', 'arrTostr', self::MODEL_BOTH, 'callback'),
        array('ad_a', 'arrTostr', self::MODEL_BOTH, 'callback'),
        array('coupons', 'arrTostr', self::MODEL_BOTH, 'callback'),
        array('columns', 'arrTostr', self::MODEL_BOTH, 'callback'),
        array('create_time', 'datetime', self::MODEL_INSERT, 'function'),
        array('update_time', 'datetime', self::MODEL_BOTH, 'function'),
    );

    //广告1
    public $ad_a_list = [1=>'一栏',2=>'二栏',3=>'三栏',4=>'四栏'];
    /**
     * 查找后置操作
     */
    protected function _after_find(&$result, $options)
    {

    }

    /**
     * 查找后置操作
     */
    protected function _after_select(&$result, $options)
    {

    }
    /*
     * 获取原价
     */
    protected function getOriginal($original_price)
    {
        $this->original_price = $original_price;
        return true;
    }

    /*
     * 比较 原价 和销售价 大小
     */
    protected function comparePrice($sale_price)
    {
        if($sale_price> $this->original_price)
            return false;
        return true;
    }

    /*
     *获取广告2 的值
     */
    protected function get_ad_b($ad_b)
    {
        $this->ad_b = $ad_b;
        return true;
    }

    /*
     * 判断 时候填写广告2排序
     */
    protected function check_ad_b_sort($ad_b_sort)
    {
        if(!empty($this->ad_b) && empty($ad_b_sort)){
            return false;
        }
        return true;
    }
    /*
     *
     */
    protected function check_ad_b_num($ad_b_sort)
    {
        if(!empty($this->ad_b))
            return is_numeric($ad_b_sort);
        return true;
    }
    /*
     * select 商品类别下拉 数据
     */
    public function getType($group)
    {
        $map['group'] = $group;
        $map['check'] = 0;
        $disabled = false;
        if($group==1){
            $disabled = true;
        }
        $list = M('shop_goodstype')->where($map)->select();
        return list_as_tree($list,null,'id','title',$disabled);
    }
    /*
     * 获取 类别名称
     */
    public function getTypeName($id)
    {
        $list = M('shop_goodstype')->field('id,title')->select();
        foreach ($list as $key => $value) {
            if($value['id'] == $id){
                $result = $value['title'];
                return $result;
            }
        }
    }

    /*
     *栏目数据
     */
    public function getColumns()
    {
        $info = M('shop_columns')->where(['group'=>1])->field('id,title')->select();
        $result=[];
        foreach ($info as $key => $value) {
            $result[$value['id']]=$value['title'];
        }
        return $result;
    }


    /**
     * 优惠券
     */
    public  function getCoupons()
    {
        $nowTime = datetime();
        $info = M('shop_coupon')
                    ->where(['start_time'=>['lt',$nowTime],'end_time'=>['gt',$nowTime],'status'=>1])
                    ->field('id,title,price')
                    ->select();
        $result=[];
        foreach ($info as $key => $value) {
            $result[$value['id']]=$value['title'].'('.$value['price'].'元)';
        }
        return $result;
    }


    public function arrTostr($arr='')
    {
        $result = '';
        if(!empty($arr)){
            $result = implode(',',$arr);
        }
        return $result;
    }

    public function cutTitle($title)
    {
        return '<div title="'.$title.'" style="cursor:pointer;width:200px;white-space:nowrap;overflow:hidden; text-overflow:ellipsis;">'.$title.'</div>';
    }

    /**
     * 设置规格
     * @param [type] $goods_id 商品id
     * @param [type] $post     [description]
     */
    public function setSpec($goods_id, $post)
    {
        if (isset($post['item'])) {
            // 实例化 商品规格 价格对象
            $specGoodsPrice = M("SpecGoodsPrice");
            // 删除原有的价格规格对象
            $specGoodsPrice->where(['goods_id'=>$goods_id])->delete();
            foreach ($post['item'] as $k => $v) {
                $k =explode('_',$k);
                sort($k);
                $k =implode('_',$k);
                // 批量添加数据
                $dataList[] = [
                    'goods_id'      => $goods_id,
                    'key'           => $k,
                    'key_name'      => $v['key_name'],
                    'shop_price'    => $v['shop_price'],
                  /*  'original_price'=> $v['original_price'],
                    'store_count'   => $v['store_count']*/
                ];
            }
            $specGoodsPrice->addAll($dataList);
        }
    }

    /**
     * 获取商品规格
     */
    public function get_spec($goods_id)
    {
        //商品规格 价钱 库存表 找出 所有 规格项id
        $keys = M('SpecGoodsPrice')->where(['goods_id'=>$goods_id])->getField("GROUP_CONCAT(`key` SEPARATOR '_') ");
        $filter_spec = array();
        if ($keys) {
            $specImage = M('SpecImage')->where("goods_id = $goods_id and src != '' ")->getField("spec_image_id,src");// 规格对应的 图片表， 例如颜色
            $keys = str_replace('_', ',', $keys);
            $sql = "SELECT a.name,a.order,b.* FROM __PREFIX__spec AS a INNER JOIN __PREFIX__spec_item AS b ON a.id = b.spec_id WHERE b.id IN($keys) ORDER BY b.id";
            $filter_spec2 = M()->query($sql);
            foreach ($filter_spec2 as $key => $val) {
                $item = array(
                    'item_id' => $val['id'],
                    'item' => $val['item'],
                    'src' => $specImage[$val['id']],
                );
                if(isset($filter_spec[$val['spec_id']])){
                    $filter_spec[$val['spec_id']]['items'][] = $item;
                }else{
                    $filter_spec[$val['spec_id']] = ['id'=>$val['spec_id'], 'name'=>$val['name'], 'items'=>[$item]];
                }
            }
        }
        return $filter_spec;
    }
}
