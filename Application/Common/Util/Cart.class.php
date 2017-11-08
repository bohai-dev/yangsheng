<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <59821125@qq.com>
// +----------------------------------------------------------------------
namespace Common\Util;

use Common\Util\Goods;

class Cart
{

    private $type;
    private $cart_model;
    private $cart_table  = 'ShoppingmallCart';
    private $goods_table = 'ShoppingmallGoods';

    public function __construct($type = 'weixin')
    {
        $this->type       = $type;
        $this->cart_model = M($this->cart_table);
    }

    //__set()方法用来设置私有属性
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    //__get()方法用来获取私有属性
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * 修改购物车
     * @param      int   $uid       用户ID
     * @param      int   $goodsid   商品ID
     * @param      integer  $num       数量
     * @param      string   $goodskey  商品规格ID
     * @return     array    [status：是否成功（boolean）、code：错误码（int）、msg：错误信息（string）]
     */
    public function cart_edit($uid, $goodsid, $num, $goodstype, $goodskey = '')
    {
        $result     = array('status' => false, 'code' => 0, 'msg' => '');
        $errCode    = 0;
        $goodsClass = new \Goods;
        $num        = intval($num);
        if ($num > 0) {
            //检查商品是否存在
            $goodsInfo = $goodsClass->GetGoodsInfo($goodsid, $goodskey);
            if ($goodsInfo) {
                // 库存
                $housenum = $goodsInfo['housenum']; // -1 表示不限制库存
                if (($housenum < $num && $housenum != -1) || $housenum == 0) {
                    $errCode = 1105;
                } else {
                    //检查购物车是否存在
                    $cart_id = $this->CartIsExist($uid, $goodsid, $goodskey);
                    if ($cart_id) {
                        $res = $this->CartUpdate($cart_id, $num);
                        if ($res === false) {
                            $errCode = 1104;
                        }
                    } else {
                        $res = $this->CartAdd($uid, $goodsid, $num, $goodstype, $goodskey);
                        if (!$res) {
                            $errCode = 1103;
                        }
                    }
                }
            } else {
                $errCode = 1102;
            }
        } else {
            $res = $this->DelGoodsCart($uid, $goodsid, $goodskey);
            if ($res === false) {
                $errCode = 1106;
            }
        }
        $result['code'] = $errCode;
        if (!$errCode) {
            $result['status'] = true;
        }
        $result['msg'] = $this->GetError($errCode);
        return $result;
    }
    public function GetCartInfo($uid, $goodsid, $goodskey)
    {
        return $this->cart_model->where(['uid' => $uid, 'goodsid' => $goodsid, 'goodskey' => $goodskey])->find();
    }

    /**
     * 加
     * @param      int      $uid       用户ID
     * @param      int      $goodsid   商品ID
     * @param      int      $num       加上数量
     * @param      string   $goodstype     商品类型
     * @param      string|int   $goodskey  商品规格ID
     * @return     array    [status：是否成功（boolean）、code：错误码（int）、msg：错误信息（string）]
     */
    public function cart_plus($uid, $goodsid, $goodstype, $num = 1, $goodskey = '')
    {
        $result  = array('status' => true, 'code' => 0, 'msg' => '');
        $errCode = 0;
        //检查是否存在
        $cart_info = $this->GetCartInfo($uid, $goodsid, $goodskey);
        $goodsObj  = new \Common\Util\Goods();
        $limit_num = $goodsObj->GetLimitNum($goodsid, $uid);
        $housenum = $goodsObj->GetHousenum($goodsid, $goodskey);
        if ($cart_info) {
            if($cart_info['goodstype']=='normal'){
                if (($cart_info['goodsnum'] + $num) > $housenum) {
                    $errCode = 1105;
                    goto done;
                }
                //检测剩余购买次数
                if ($limit_num != -1 && $limit_num < ($cart_info['goodsnum'] + $num)) {
                    $errCode = 1107;
                    goto done;
                }
            }
            $res = $this->cart_model->where(['id' => $cart_info['id']])->setInc('goodsnum', $num);
            if ($res === false) {
                $errCode = 1104;
                goto done;
            }
        } else {
            if($goodstype=='normal'){
                if ($num > $housenum) {
                    $errCode = 1105;
                    goto done;
                }
                //检测剩余购买次数
                if ($limit_num != -1 && $limit_num < $num) {
                    $errCode = 1107;
                    goto done;
                }
            }
            //加入购物车
            $res = $this->CartAdd($uid, $goodsid, $num, $goodstype, $goodskey);
            if ($res === false) {
                $errCode = 1104;
                goto done;
            }
        }
        done:
        $result['code'] = $errCode;
        $result['msg']  = $this->GetError($errCode);
        if ($errCode !== 0) {
            $result['status'] = false;
        }
        return $result;
    }
    /**
     * 减
     * @param      int      $uid       用户ID
     * @param      int      $goodsid   商品ID
     * @param      int      $num       减去数量
     * @param      string|int   $goodskey  商品规格ID
     * @return     array    [status：是否成功（boolean）、code：错误码（int）、msg：错误信息（string）]
     */
    public function cart_minus($uid, $goodsid, $num = 1, $goodskey = '')
    {
        $result   = array('status' => true, 'code' => 0, 'msg' => '');
        $errCode  = 0;
        $cartInfo = $this->cart_model->where(['uid' => $uid, 'goodsid' => $goodsid, 'goodskey' => $goodskey])->find();
        //购物车存在
        if (!empty($cartInfo)) {
            $cart_id  = $cartInfo['id'];
            $cart_num = $cartInfo['goodsnum'];
            if ($cart_num < 2) {
                //删除
                $res = $this->DelCart($cart_id);
                if ($res === false) {
                    $errCode = 1104;
                }
            } else {
                $res = $this->cart_model->where(['id' => $cart_id])->setDec('goodsnum', $num);
                if ($res === false) {
                    $errCode = 1104;
                }
            }
        }
        $result['code'] = $errCode;
        $result['msg']  = $this->GetError($errCode);
        if ($errCode !== 0) {
            $result['status'] = false;
        }
        return $result;
    }

    /**
     * 添加商品
     * @param      int  $uid       用户ID
     * @param      int  $goodsid   商品ID
     * @param      int  $num       数量
     * @param      string|int $goodskey  商品规格ID
     * @return     boolean|int 返回购物车id，如果返回false则表示写入出错
     */
    public function CartAdd($uid, $goodsid, $num, $goodstype, $goodskey = '')
    {
        $data = ['uid' => $uid, 'goodsid' => $goodsid, 'goodsnum' => $num, 'goodstype' => $goodstype, 'goodskey' => $goodskey];
        return $this->cart_model->add($data);
    }

    /**
     * 更新购物车
     * @param      int  $cart_id   购物车ID
     * @param      int  $num       数量
     * @return     boolean|int     返回值是影响的记录数，如果返回false则表示更新出错
     */
    public function CartUpdate($cart_id, $num)
    {
        $data = ['goodsnum' => $num];
        return $this->cart_model->where(['id' => $cart_id])->save($data);
    }

    /**
     * 用户商品删除
     * @param      int  $uid       用户id
     * @param      int  $goodsid   商品id
     * @param      string|int  $goodskey  规格id
     * @return     boolean|int 返回值是删除的记录数，如果返回值是false则表示SQL出错，返回值如果为0表示没有删除任何数据
     */
    public function DelGoodsCart($uid, $goodsid, $goodskey = '')
    {
        $result = $this->cart_model->where(['uid' => $uid, 'goodsid' => $goodsid, 'goodskey' => $goodskey])->delete();
        return $result;
    }

    /**
     * 删除单个购物车商品
     * @param  int $cartid 购物车ID
     * @return boolean|int 返回值是删除的记录数，如果返回值是false则表示SQL出错，返回值如果为0表示没有删除任何数据
     */
    public function DelCart($cartid)
    {
        $result = $this->cart_model->where(['id' => $cartid])->delete();
        if ($result === false) {
            return false;
        }
        return $result;
    }

    /**
     * 删除多个购物车商品
     * @param  array $cartids 购物车ID数组
     * @return boolean|int    返回值是删除的记录数，如果返回值是false则表示SQL出错，返回值如果为0表示没有删除任何数据
     */
    public function DelSelescCart($uid, $cartids)
    {
        $result = $this->cart_model->where(['id' => ['in', $cartids], ['uid' => $uid]])->delete();
        return $result === false ? false : $result;
    }

    /**
     * 获取购物车
     * @param      int  $uid    用户id
     * @return     array  [list：商品、total：合计]
     */
    public function GetAll($uid, $goodstypes = ['normal'])
    {
        $selected = $this->cart_model->where(['uid' => $uid, ['goodstype' => ['in', $goodstypes]]])->select();
        if ($selected) {
            $goods = M($this->goods_table)->where(['id' => ['in', array_column($selected, 'goodsid'), 'status' => 1]])->field('id,title,price,privilege_price,pic,pics,is_bargain')->select();
            if (!$goods) {
                return ['list' => [], 'total' => 0];
            } else {
                $selected_goods = [];
                foreach ($goods as $key => $good) {
                    $selected_goods[$good['id']] = $good;
                }
                $cartListInfo['total'] = 0;
                foreach ($selected as $key => $cart) {
                    if (isset($selected_goods[$cart['goodsid']])) {
                        $cartListInfo['list'][] = array_merge($selected_goods[$cart['goodsid']], ['goodsnum' => $cart['goodsnum'], 'cartid' => $cart['id']]);
                        $cartListInfo['total'] += $cart['goodsnum'] * $selected_goods[$cart['goodsid']]['price'];
                    }
                }
                return $cartListInfo;
            }
        } else {
            return ['list' => [], 'total' => 0];
        }
    }

    /**
     * 获取购物车中选中商品信息
     * @param int $uid     用户id
     * @param array $cartids 购物车id数组
     * @return array 购物车信息 [list：商品（array）、salesprice_total：优惠价总和、marketprice_total：原价总和]
     */
    public function GetSelectedAll($uid, $cartids)
    {
        $map = [
            'uid' => $uid,
            'id'  => ['in', $cartids],
        ];
        $selected  = $this->cart_model->where($map)->select();
        $empty_ret = ['list' => [], 'salesprice_total' => 0, 'marketprice_total' => 0, 'status' => 1, 'msg' => '', 'is_virtual_paying' => 1];
        if ($selected) {
            $goodsObj = new Goods();
            $goods    = $goodsObj->goods_model->where(['id' => ['in', array_column($selected, 'goodsid'), 'status' => 1]])->field('id,title,price,privilege_price,pic,pics,score,is_bargain,is_virtual_paying')->select();
            if (!$goods) {
                return $empty_ret;
            } else {
                $selected_goods    = [];
                $is_virtual_paying = 1;
                foreach ($goods as $key => $good) {
                    $selected_goods[$good['id']] = $good;
                    if ($good['is_bargain'] && !$good['is_virtual_paying']) {
                        $is_virtual_paying = 0;
                    }
                    //检测剩余购买次数
                    $limit_num = $goodsObj->GetLimitNum($good['id'], $uid);
                    if ($limit_num != -1 && $limit_num < 1) {
                        $empty_ret['status'] = 0;
                        $empty_ret['msg']    = '存在商品是特价商品，剩余购买数量不足';
                        return $empty_ret;
                    }
                }
                $cartListInfo['status']            = 1;
                $cartListInfo['salesprice_total']  = 0;
                $cartListInfo['marketprice_total'] = 0;
                $cartListInfo['is_virtual_paying'] = $is_virtual_paying;
                foreach ($selected as $key => $cart) {
                    if (isset($selected_goods[$cart['goodsid']])) {
                        $cartListInfo['list'][] = array_merge($selected_goods[$cart['goodsid']], ['goodsnum' => $cart['goodsnum'], 'cartid' => $cart['id']]);
                        $cartListInfo['marketprice_total'] += $cart['goodsnum'] * $selected_goods[$cart['goodsid']]['price'];
                        $cartListInfo['salesprice_total'] += $cart['goodsnum'] * $selected_goods[$cart['goodsid']]['privilege_price'];
                    }
                }
                return $cartListInfo;
            }
        } else {
            return $empty_ret;
        }
    }

    /**
     * 获取购物车数量
     * @param      int  $uid    用户id
     * @return     int  购物车数量
     */
    public function GetNum($uid)
    {
        return $this->cart_model->where(['uid' => $uid])->count('id');
    }

    /**
     * 检测是否存在, 返回购物车ID
     * @param      int  $uid       用户ID
     * @param      int  $goodsid   商品ID
     * @param      string|int  $goodskey  商品规格ID
     * @return     int  购物车ID
     */
    public function CartIsExist($uid, $goodsid, $goodskey = '')
    {
        $data = $this->cart_model->where(['uid' => $uid, 'goodsid' => $goodsid, 'goodskey' => $goodskey])->find();
        return empty($data) ? 0 : $data['id'];
    }

    public function startTrans()
    {
        $this->cart_model->startTrans();
    }
    public function rollback()
    {
        $this->cart_model->rollback();
    }
    public function commit()
    {
        $this->cart_model->commit();
    }

    public function GetError($code)
    {
        $errArr = array(
            1101 => '数量必须大于0',
            1102 => '商品不存在或已下架',
            1103 => '添加失败',
            1104 => '修改失败',
            1105 => '库存不足',
            1106 => '失败',
            1107 => '该商品是特价商品，剩余购买数量不足',
        );
        return isset($errArr[$code]) ? $errArr[$code] : '';
    }
}
