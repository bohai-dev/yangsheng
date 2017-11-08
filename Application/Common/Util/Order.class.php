<?php
// +----------------------------------------------------------------------
// | 订单类
// CheckGoodsInfo: 检查商品是否下架、库存等符合购买要求。
// CreateGoodsOrder: 创建订单
// ChangeOrderStatus: 修改订单状态
// GetOrderInfo: 获取订单信息
// GetOrderItem: 获取订单商品信息
// UpdateOrder: 修改订单信息
// GetOrdernum: 返回一个随机订单号
// GetError: 返回错误信息
// +----------------------------------------------------------------------
// | 创建人：张亚伟  创建日期：2016-09-08   QQ:1743325520
// +----------------------------------------------------------------------
// |所有修改请在这里记录
//   修改人：        修改日期：             QQ:
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
namespace Common\Util;

use Common\Util\Cart;
use Common\Util\Goods;
use Common\Util\Member;

class Order
{

    private $order_table        = 'shop_order'; //订单表
    private $item_table         = 'shop_order_item'; //订单明细表
    private $goods_table        = 'shop_goods'; //商品表
    private $coupons_table      = 'coupons'; //优惠券表
    private $user_table         = 'admin_user'; // 用户表
    private $admin_user_table   = 'admin_user'; //商家表
    private $user_coupons_table = 'user_coupons'; //优惠券记录表
    private $cardcharge_table   = 'cardcharge'; //会员卡购买记录
    private $order_status_arr   = [
        1 => '未支付',
        2 => '已支付',
        3 => '已发货',
        4 => '已收货',
        5 => '申请退货',
        6 => '同意申请退货',
        7 => '退货成功',
        8 => '拒绝退货',
    ];

    public function __construct($type = 'weixin', $uid = 0)
    {
        $this->type = $type;
        $this->uid  = $uid;
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
     * 检查商品是否下架、库存等符合购买要求
     * @param      array  $buys   购买商品信息
     * @param      array  $post   去结算时提交的信息，如选择的地址、优惠券、是否使用积分等信息。
     * @param      string    $type  订单类型  normal：普通商品、score: 积分商品 purchase 进货
     * @param      boolean  $is_housenum   是否库存管理
     * @return     array   ( description_of_the_return_value )
     */
    public function CheckGoodsInfo($buys, $post = [], $type = 'normal', $is_housenum = false, $is_status = true)
    {
        $result     = ['status' => true, 'msg' => ''];
        $amount     = 0;
        $total      = 0;
        $msg        = '';
        $orderData  = [];
        $itemData   = [];
        $goodsClass = new Goods();
        foreach ($buys as $key => $goods) {
            // ptrace('goods:');
            // ptrace($goods);
            //商品id、规格、购买数量
            $goodsid  = $goods['goodsid'];
            $goodskey = isset($goods['goodskey']) ? $goods['goodskey'] : '';
            $buynum   = $goods['buynum'];

            switch ($type) {
                case 'score':
                    $fields = 'id,id as goodsid, pub_type, price, status, title, pic, description, score as privilege_price, score,is_bargain';
                    break;
                case 'beauty_service':
                    // if ($goods['payed']) {
                    //     $fields = 'id,id as goodsid, pub_type, privilege_price as price, status, title, pic, description,privilege_price, score,is_bargain';
                    // } else {
                    //     $fields = 'id,id as goodsid, pub_type, price as privilege_price, status, title, pic, description,privilege_price as price, score,is_bargain';
                    // }
                    $fields = 'id,id as goodsid, pub_type, price, status, title, pic, description,privilege_price, score, present_money,is_bargain';
                    break;
                default:
                    $fields = 'id,id as goodsid, pub_type, price, status, title, pic, description,privilege_price, score, present_money,is_bargain';
                    break;
            }
            //商品信息
            $goodsInfo = $goodsClass->GetGoodsInfo($goodsid, $goodskey, $fields);
            if (!empty($goodsInfo)) {
                //判断是否下架
                if ($goodsInfo['status'] != 1 && $is_status) {
                    $msg = '未找到对应的商品数据';
                    goto done;
                }
                if ($is_housenum) {
                    //判断库存
                    $housenum = $goodsClass->GetHousenum($goodsid, $goodskey);
                    if ($housenum == 0 || ($housenum < $buynum && $housenum != -1)) {
                        $msg = $goodsInfo['title'] . ' 库存不足';
                        goto done;
                    }
                }
                //检测剩余购买次数
                $limit_num = $goodsClass->GetLimitNum($goodsid, $this->uid);
                if ($limit_num != -1 && $limit_num < $buynum) {
                    $msg = $goodsInfo['title'] . '是特价商品，剩余购买数量不足';
                    goto done;
                }

                $goodsInfo['goodskey'] = $goodskey;
                $goodsInfo['buynum']   = $buynum;
                //总额=优惠价*数量
                $total += $goodsInfo['privilege_price'] * $buynum;
                // if ($type == 'beauty_service') {
                //     $total += $goodsInfo['price'] * $buynum;
                // } else {
                if ($type == 'beauty_service') {
                    if ($goods['payed']) {
                        $amount += $goodsInfo['privilege_price'] * $buynum;
                    }
                } else {
                    $amount += $goodsInfo['privilege_price'] * $buynum;
                }
                // }
                $itemData[$key] = $goodsInfo;
            } else {
                if (count($buys) == 1) {
                    $msg = '商品已下架';
                } else {
                    $msg = '存在商品已下架';
                }
                goto done;
            }
        }
        if (empty($itemData)) {
            $msg = '商品为空';
        }
        done:
        if ($type == 'beauty_service') {
            $type = 'service';
        }
        if ($msg === '') {
            //金币
            $data['coin'] = 0;
            if ($post) {
                //优惠券
                $orderData['coupon'] = I('benefit', '');
                // 上级
                // $orderData['recUid'] = $this->getRecUid($post, $type);
            }
            //订单产生的平台
            $orderData['platform'] = $this->type;
            //订单号
            $orderData['ordernum'] = $this->GetOrdernum();
            //状态
            $orderData['checkinfo'] = 1; //未支付
            //类型
            $orderData['type'] = $type;
            //总价
            $orderData['total'] = $total;
            // 积分
            $orderData['score']   = $type == 'score' ? $amount : 0;
            $orderData['payment'] = $type == 'score' ? 0 : $amount;
            //订单数据
            $result['orderData'] = $orderData;
            $result['payment']   = $amount;
            //orderitem数据
            $result['itemData'] = $itemData;
        } else {
            $result = ['status' => false, 'msg' => $msg];
        }
        return $result;
    }

    // 获取上级订单来源uid
    public function getRecUid($post, $type)
    {
        if ($type == 'normal') {
            if (isset($post['recUid']) && $post['recUid']) {
                return $post['recUid'];
            } else {
                // 上海的为默认代理商
                if (false !== stripos($post['address_detail'], '上海')) {
                    return C('shoppingmall_config.default_recuid');
                } else {
                }
            }
        } else {
            return 0;
        }
    }

    /**
     * 计算邮费
     */
    public function GetShipFee($default_fee = 0, $total)
    {
        return $default_fee;
    }

    /**
     * 创建订单
     * @param array $data
     * @param string $create_type 购物车下单和立即购买
     * @return mixed array num string
     */
    public function CreateGoodsOrder($data, $create_type = '')
    {
        $orderdata   = $data['orderData'];
        $orderitem   = $data['itemData'];
        $result      = array('status' => true, 'code' => 0, 'msg' => '');
        $goodsClass  = new Goods();
        $cartClass   = new Cart();
        $memberClass = new Member();
        $errCode     = 0;
        $orderModel  = M($this->order_table);
        $orderModel->startTrans();
        $orderid  = 0;
        $userInfo = $memberClass->GetUserInfo($orderdata['uid']);
        if ($userInfo) {
            // 返利人信息
            $orderdata['recUid']        = $userInfo['recUid'];
            $orderdata['recUplevelUid'] = getUplevelUid($userInfo['recUid']);
            $orderdata['urlUid']        = $userInfo['urlUid'];
            $orderdata['urlUplevelUid'] = getUplevelUid($userInfo['urlUid']);
        } else {
            $errCode = 20;
            goto done;
        }
        //判断用户积分是否足够
        if ($orderdata['type'] == 'score' && isset($orderdata['score']) && $orderdata['score'] > 0) {
            $user_score = $userInfo['score'];
            if ($user_score < $orderdata['score']) {
                $errCode = 13;
                goto done;
            }
        }
        $orderdata['create_time'] = datetime();
        $orderid                  = $orderModel->add($orderdata);
        if (!$orderid) {
            $errCode = 1;
            goto done;
        } else {
            //积分使用记录
            if ($orderdata['type'] == 'score' && isset($orderdata['score']) && $orderdata['score'] > 0) {
                $res = score($userInfo, '兑换商品消耗积分', 0 - $orderdata['score'], $orderid);
                if ($res === false) {
                    $errCode = 11;
                    goto done;
                }
            }
            switch ($orderdata['type']) {
                case 'normal':
                case 'service':
                    //修改会员卡余额
                    $pay_type_arr = [
                        4 => ['title' => '购买服务项目消费', 'card_type' => 'service_card'],
                        5 => ['title' => '购买商品消费', 'card_type' => 'card'],
                    ];
                    if (in_array($orderdata['pay_type'], array_keys($pay_type_arr))) {
                        if ($orderdata['pay_type'] == 4) {
                            if ($userInfo['service_money'] < $orderdata['payment']) {
                                $errCode = 19;
                                goto done;
                            }
                        } else {
                            if ($userInfo['card_money'] < $orderdata['payment']) {
                                $errCode = 19;
                                goto done;
                            }
                        }
                        $res = $memberClass->card_money($orderdata['uid'], $pay_type_arr[$orderdata['pay_type']]['title'], 0, $orderid, $pay_type_arr[$orderdata['pay_type']]['card_type'], (0 - $orderdata['payment']));
                        if (!$res) {
                            $errCode = 18;
                            goto done;
                        }
                    }
                    break;
            }
            $toAddAll = [];
            $i        = 0;
            foreach ($orderitem as $key => $item) {
                $key = $i;
                //商品id
                $toAddAll[$key]['goods_id'] = $item['goodsid'];
                $toAddAll[$key]['buy_num']  = $item['buynum'];
                if ($orderdata['type'] == 'score' && isset($orderdata['score']) && $orderdata['score'] > 0) {
                    // 增加积分商品销量
                    $res_add = $goodsClass->SetSalenum($item['goodsid'], 1, '');
                    if (!$res_add) {
                        $errCode = 16;
                        goto done;
                    }
                }
                if (isset($item['goodskey']) && $item['goodskey']) {
                    $toAddAll[$key]['key']      = $item['goodskey'];
                    $toAddAll[$key]['key_name'] = $item['key_name'];
                }
                $toAddAll[$key]['goodsorder_id'] = $orderid;
                $toAddAll[$key]['price']         = $item['privilege_price'];
                $toAddAll[$key]['origin_price']  = $item['price'];
                $toAddAll[$key]['score']         = $item['score'];
                $toAddAll[$key]['is_bargain']    = $item['is_bargain'];
                //会员卡赠送金额
                $toAddAll[$key]['present_money'] = isset($item['present_money']) ? $item['present_money'] : 0;
                $i++;
            }
            if (empty($toAddAll)) {
                $errCode = 2;
                goto done;
            } else {
                $res = M($this->item_table)->addAll($toAddAll);
                if ($res == false) {
                    $errCode = 3;
                    goto done;
                }
            }
        }
        done:
        $result['code']    = $errCode;
        $result['msg']     = $this->GetError($errCode);
        $result['orderid'] = $orderid;
        if (!$errCode) {
            $result['status'] = true;
            $orderModel->commit();
            switch ($create_type) {
                case 'fromcart': //购物车
                    //删除购物车
                    $cartClass->DelSelescCart($orderdata['uid'], array_keys($orderitem));
                    break;
                default:
                    break;
            }
        } else {
            $result['status'] = false;
            //报错
            // if (!in_array($errCode, [2,4])) {
            ptrace('创建订单失败：' . json_encode($result));
            // }
            $orderModel->rollback();
        }
        return $result;
    }

    /**
     * 修改订单状态
     * @param int $orderid 订单ID
     * @param string $status 状态 cancel payed apply_refund agree_refund refund_ok confirm
     * @param array 额外订单参数
     * @return array
     */
    public function ChangeOrderStatus($orderid, $status, $extra = [])
    {
        $result      = array('status' => true, 'code' => 0, 'msg' => '');
        $checkinfos  = ['cancel' => -1, 'payed' => 2, 'finish' => 4, 'apply_return' => 5];
        $orderData   = [];
        $errCode     = 0;
        $goodsClass  = new Goods();
        $memberClass = new Member();
        $orderModel  = M($this->order_table);
        $orderInfo   = $this->GetOrderInfo($orderid);
       /* $orderitem   = $this->GetOrderItem($orderid);*/
        if (!array_key_exists($status, $checkinfos)) {
            $errCode = 17;
            goto commit;
        }
        if ($checkinfos[$status] == $orderInfo['checkinfo']) {
            $errCode = 0;
            goto commit;
        }
        $orderData['checkinfo'] = $checkinfos[$status];
        $orderModel->startTrans();
        switch ($status) {
            case 'cancel': //未支付订单取消
                //增加积分
                $user =$memberClass->GetUserInfo($orderInfo['uid']);
                if(!empty($orderInfo['integral'])){
                    $nowTime = datetime();
                    $score_data['uid']=$orderInfo['uid'];
                    $score_data['title']='取消订单返还'.$orderInfo['integral'].'积分';
                    $score_data['score']=$orderInfo['integral'];
                    $score_data['create_time']=$nowTime;
                    $score_data['type']=1;
                    $score_data['group']=3;
                    $score_data['nickname']=$user['nickname'];
                    $score_res = M('user_score')->add($score_data);
                    if(empty($score_res)){
                      ptrace('增加积分使用记录失败');
                      ptrace($score_data);
                      $errCode = 9;
                      goto commit;
                    }

                    $admin_score = M('admin_user')->where(['id'=>$orderInfo['uid']])
                                    ->setInc('score',$orderInfo['integral']);
                    if(empty($admin_score)){
                      ptrace('返回积分失败');
                      $errCode = 9;
                      goto commit;
                    }
                }

                //返回优惠券
                if(!empty($orderInfo['coupon'])){
                    $coupon_map['cid'] = $orderInfo['coupon'];
                    $coupon_map['uid'] = $orderInfo['uid'];
                    $coupon_data['use_time'] = '';
                    $coupon_data['orderid'] = '';
                    $coupon_data['status'] = 1;
                    $coupon_res = M('user_coupons')->where($coupon_map)->save($coupon_data);
                    if(empty($coupon_res)){
                      ptrace('改变优惠券状态失败');
                      ptrace($coupon_data);
                      ptrace($coupon_map);
                      $errCode = 9;
                      goto commit;
                    }
                }
                $result['url']       = U('Shop/Order/index', ['checkinfo' => 1]);
                break;
            case 'payed': //支付后
                $order_item_object = M('shop_order_item');
                $goods_object = M('shop_goods');
                $seckill_goods_object = M('shop_seckill_goods');
                //订单详情
                $order_item_info = $order_item_object
                                   ->where(['order_id'=>$orderid])
                                   ->select();

                //更新商品信息 商品销量 秒杀销量
                foreach ($order_item_info as $key => $value) {
                    $goods_object->where(['id'=>$value['goods_id']])->setInc('sales_volume',$value['buy_num']);
                    if(!empty($value['seckill_check'])){
                        $seckill_goods_object->where(['id'=>$value['seckill_check']])->setInc('seckill_sales',$value['buy_num']);
                    }
                }
                $result['url']        = U('Shop/Order/index',['checkinfo'=>2]);
                $result['status']        =true;
                break;
            case 'finish': //确认收货
                // 发送佣金，增加佣金记录
                $user =$memberClass->GetUserInfo($orderInfo['uid']);
                $user_money_model =M('user_money');
                $member_model =M('admin_user');
                $add_user_data =[
                    'title'=>'会员购买商品返佣',
                    'create_time'=>datetime(),
                    'order_id'=>$orderid
                ];
                // 一级会员发放佣金
                if($user['pfirstid']){
                    $money =$orderInfo['payment']*C('first_rate');
                    if($money>0){
                        $add_user_data['money'] =$money;
                        $add_user_data['uid']=$user['pfirstid'];
                        $res = $user_money_model->add($add_user_data);
                        if(!$res){
                            pubu("订单id{$orderid}完成时1级用户上级返利失败");
                            $errCode = 9;
                            goto commit;
                        }
                        $update =$member_model->where(['id'=>$user['pfirstid']])->setInc('money',$money);
                        if(!$update){
                            pubu("订单id{$orderid}完成时1级用户上级返利失败");
                            $errCode = 9;
                            goto commit;
                        }
                    }
                }
                //二级会员发放佣金
                if($user['psecondid']){
                    $money =$orderInfo['payment']*C('second_rate');
                    if($money>0){
                        $add_user_data['money'] =$money;
                        $add_user_data['uid']=$user['psecondid'];
                        $res =$user_money_model->add($add_user_data);
                        if(!$res){
                            pubu("订单id{$orderid}完成时2级用户上级返利失败");
                            $errCode = 9;
                            goto commit;
                        }
                        $update = $member_model->where(['id'=>$user['psecondid']])->setInc('money',$money);
                        if(!$update){
                            pubu("订单id{$orderid}完成时2级用户上级返利失败");
                            $errCode = 9;
                            goto commit;
                        }
                    }
                }
                //三级会员发放佣金
                if($user['pthirdid']){
                    $money =$orderInfo['payment']*C('third_rate');
                    if($money>0){
                        $add_user_data['money'] =$money;
                        $add_user_data['uid']=$user['pthirdid'];
                        $res =  $user_money_model->add($add_user_data);
                        if(!$res){
                            pubu("订单id{$orderid}完成时3级用户上级返利失败");
                            $errCode = 9;
                            goto commit;
                        }
                        $update = $member_model->where(['id'=>$user['pthirdid']])->setInc('money',$money);
                        if(!$update){
                            pubu("订单id{$orderid}完成时3级用户上级返利失败");
                            $errCode = 9;
                            goto commit;
                        }
                    }
                }
                // 赠送积分
                $score =0;
                $order_item =M('shop_order_item')->where(['order_id'=>$orderid])->select();
                foreach($order_item as $key=>$v){
                    $score +=$v['integral_back'];
                }
                if($score>0){
                    $score_data['nickname']=get_user_info($orderInfo['uid'],'admin_user','nickname');
                    $score_data['title'] ='购买商品赠送'.$score.'积分';
                    $score_data['uid'] =$orderInfo['uid'];
                    $score_data['score'] =$score;
                    $score_data['create_time'] =datetime();
                    $score_data['type']=1;
                    $res =M('user_score')->add($score_data);
                    if(!$res){
                        pubu("订单id{$orderid}赠送积分失败");
                        $errCode = 9;
                        goto commit;
                    }
                    $update =M('admin_user')->where(['id'=>$orderInfo['uid']])->setInc('score',$score);
                    if(!$update){
                        pubu("订单id{$orderid}赠送积分失败");
                        $errCode = 9;
                        goto commit;
                    }
                }
                $result['url']       = U('Shop/order/index', ['checkinfo' => 4]);
                break;
            case 'apply_return': // 退款
                $result['url']       = U('Shop/Order/index', ['checkinfo' => 5]);
                break;
        }

        if (is_array($extra) && !empty($extra)) {
            $orderData = array_merge($orderData, $extra);
        }

        $res = $orderModel->where(['id' => $orderid])->save($orderData);
        if ($res === false) {
            $errCode = 5;
        }
        commit:
        $result['code'] = $errCode;
        $result['msg']  = $this->GetError($errCode);
        if (!$errCode) {
            $result['status'] = true;
            $orderModel->commit();
        } else {
            $result['status'] = false;
            $orderModel->rollback();
        }
        return $result;
    }

    /**
     * 获取订单信息
     * @param      int  $orderid  The orderid
     * @return     array  The order.
     */
    public function GetOrderInfo($orderid)
    {
        if (strlen($orderid) > 11) {
            $map = ['ordernum' => $orderid];
        } else {
            $map = ['id' => $orderid];
        }
        $orderInfo = M($this->order_table)->where($map)->find();
        return $orderInfo;
    }

    /**
     * 获取订单商品信息
     * @param      int  $orderid  The orderid
     * @return     array   The order item.
     */
    public function GetOrderItem($orderid)
    {
        $record    = ['buynum' => 0, 'goods' => []];
        $orderInfo = $this->GetOrderInfo($orderid);
        $orderItem = M($this->item_table)->where(['goods_id' => $orderInfo['id']])->select();
        if ($orderItem) {
            $goodslist = M($this->goods_table)->where(['id' => ['in', array_column($orderItem, 'goods_id')]])->select();
            foreach ($orderItem as $good_item) {
                foreach ($goodslist as $key => $value) {
                    if ($good_item['goods_id'] == $value['id']) {
                        //未支付状态
                        // if($orderInfo['checkinfo']==1){
                        //     $good_item['price'] = $value['privilege_price'];
                        // }
                        $good_item['title']     = $value['title'];
                        $good_item['cover']       = $value['cover'];
                        $good_item['images']      = $value['images'];
                        $good_item['score']     = $value['score'];
                        $record['buynum'] += $good_item['buy_num'];
                        $record['goods'][] = $good_item;
                    }
                }
            }
        }
        $record['info'] = $orderInfo;
        return $record;
    }

    /**
     * 修改订单
     * @param array $data
     * @param int $orderid
     */
    public function UpdateOrder($data, $orderid)
    {
        $res = M($this->order_table)->where(['id' => $orderid])->save($data);
        if ($res === false) {
            pubu("修改订单id为{$orderid}的订单失败，修改数据" . var_export($data, true) . '失败原因：' . M($this->order_table)->getError());
        }
        return $res !== false;
    }

    public function DelectOrder($orderid)
    {
        M()->startTrans();
        $num = M($this->order_table)->where(['id' => $orderid])->delete();
        if ($num !== false) {
            if (M($this->item_table)->where(['goodsorder_id' => $orderid])->delete() !== false) {
                M()->commit();
                return 1;
            }
        }
        M()->rollback();
        return 0;

    }

    /**
     * 生成一个订单号
     **/
    public function GetOrdernum()
    {
        return getOrderNo();
    }

    /**
     * 回滚某订单
     * @param  string $id 订单id
     */
    public function rollbackOrder($id)
    {
        M($this->order_table)->startTrans();
        $delete_order = M($this->order_table)->delete($id);
        $delete_item  = M($this->item_table)->where(['goodsorder_id' => $id])->delete();
        if ($delete_order === false || $delete_item === false) {
            M($this->order_table)->rollback();
            return false;
        } else {
            M($this->order_table)->commit();
            return true;
        }
    }

    /**
     * 返回错误信息
     * @param      int  $code   The code
     * @return     <type>  The error.
     */
    public function GetError($code)
    {
        $errCode = array(
            '0'  => '成功',
            '1'  => '创建订单失败',
            '2'  => '购买商品为空',
            '3'  => '创建订单明细失败',
            '4'  => '库存不足',
            '5'  => '状态修改失败',
            '6'  => '赠送券失败',
            '7'  => '优惠券使用失败',
            '8'  => '优惠券返还失败',
            '9'  => '返利失败',
            '10' => '积分使用失败',
            '11' => '创建积分使用记录失败',
            '12' => '退货所需返回系统的积分不足',
            '13' => '积分不足',
            '14' => '更新库存失败',
            '15' => '更新上级销量失败',
            '16' => '更新商品销量失败',
            '17' => '状态不存在',
            '18' => '会员卡修改失败',
            '19' => '余额不足',
            '20' => '订单所属用户缺失',
            '21' => '更新预约状态失败',
            '22' => '支付订单关联的预约表记录缺失',
        );
        return !empty($code) && isset($errCode[$code]) ? $errCode[$code] : '';
    }

}
