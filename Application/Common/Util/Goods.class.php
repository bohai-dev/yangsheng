<?php
// +----------------------------------------------------------------------
// | 商品类
// +----------------------------------------------------------------------
// | 创建人：张亚伟  创建日期：2016-09-08   QQ:1743325520
// +----------------------------------------------------------------------
// |所有修改请在这里记录
// 修改人：        修改日期：             QQ:
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
namespace Common\Util;

class Goods
{

    private $type;
    private $goods_table          = 'shop_goods'; // 商品表
    private $comment_table        = 'Comment'; //评论表
    private $user_table           = 'admin_user'; //用户信息表
    private $stock_table          = 'ShoppingmallGoodsstock'; // 经销商库存表
    private $collect_table        = 'ShoppingmallGoodsCollect'; //收藏表
    private $history_table        = 'ShoppingmallGoodsHistory'; //记录表
    private $specgoodsprice_table = 'SpecGoodsPrice'; //商品规格表
    private $history_expire_time  = '+1 month';
    private $goodsorder_table     = 'shoppingmall_goodsorder';
    private $goodsorderitem_table = 'shoppingmall_goodsorderitem';

    public function __construct($type = 'weixin')
    {
        $this->type        = $type;
        $this->goods_model = M($this->goods_table);
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
     * 获取商品信息
     *
     * @param   int  $goodsid  商品ID
     * @param   string|int  $goodskey  商品规格ID
     * @param   string $field 查询字段
     * @param   boolean $comment  是否获取评论
     * @param   int $comment_num  评论数量
     * @return  array 商品信息
     */
    public function GetGoodsInfo($goodsid, $goodskey = '', $field = '*', $comment = false, $comment_num = 0)
    {
        $goodsInfo = $this->goods_model->field($field)->where(['id' => $goodsid])->find();
        if (!$goodsInfo) {
            return [];
        }
        if (!empty($goodskey)) {
            $sepc_item = false;
            if ($sepc_item) {
                $sepc_item['privilege_price'] = $sepc_item['price'];
                unset($sepc_item['price']);
                $goodsInfo = $sepc_item + $goodsInfo;
            } else {
                $goodsInfo = array();
            }
        }
        if ($comment) {
            // 获取相关评论
            $goodsInfo['comment'] = $this->GetComment($goodsid, '', 1, $comment_num);
        }
        //TODO 处理今日特惠的价格
        return $goodsInfo;
    }

    /**
     * 获取库存
     * @param   int         $goodsid   商品ID
     * @param   string|int  $goodskey  商品规格ID
     * @return  int         返回库存
     */
    public function GetHousenum($goodsid, $goodskey = '')
    {
        if (empty($goodskey)) {
            $housenum = M($this->goods_table)->where(['id' => $goodsid])->getField('store_count');
        } else {
            $housenum = M($this->specgoodsprice_table)->where(['id' => $goodsid, 'key' => $goodskey])->getField('store_count');
        }
        return $housenum ?: 0;
    }

    /**
     * 获取销量
     * @param   int         $goodsid   商品ID
     * @param   string|int  $goodskey  商品规格ID
     * @return  int         返回销量
     */
    public function GetSalenum($goodsid, $goodskey = '')
    {
        $salenum = M($this->goods_table)->where(['id' => $goodsid])->getField('salenum');
        return $salenum ?: 0;
    }

    /**
     * 修改库存
     * @param      int         $goodsid   商品ID
     * @param      int         $num       修改数量
     * @param      string|int  $goodskey  商品规格ID
     * @param      string      $type      修改类型 add：加、其它：减
     * @return     int         1：成功、0：失败
     */
    public function SetHousenum($goodsid, $num, $goodskey = '', $recUid = 0)
    {
        if ($num == 0) {
            $res = true;
        } else if ($num > 0) {
            if (empty($goodskey)) {
                //无规格
                if ($recUid) {
                    $stockModel = M($this->stock_table);
                    $has_stock  = $stockModel->where(['uid' => $recUid, 'goods_id' => $goodsid])->find();
                    if ($has_stock) {
                        $res = $stockModel->where(['id' => $has_stock['id']])->save(['store' => ['exp', '`store`+' . $num]]);
                    } else {
                        $res = $stockModel->add(['store' => $num, 'salenum' => 0, 'uid' => $recUid, 'goods_id' => $goodsid]);
                    }
                } else {
                    $res = M($this->goods_table)->where(['id' => $goodsid])->setInc('store_count', $num);
                }
            } else {
                //有规格
                $res = M($this->specgoodsprice_table)->where(['id' => $goodsid, 'key' => $goodskey])->setInc('store_count', $num);
            }
        } else {
            $num = abs($num);
            if (empty($goodskey)) {
                //无规格
                $stockModel = M($this->stock_table);
                if ($recUid) {
                    $has_stock = $stockModel->where(['uid' => $recUid, 'goods_id' => $goodsid])->find();
                    if ($has_stock) {
                        $res = $stockModel->where(['id' => $has_stock['id']])->save(['store' => ['exp', '`store`-' . $num, 'salenum' => ['exp', '`salenum`+' . $num]]]);
                    } else {
                        $res = $stockModel->add(['store' => 0 - $num, 'salenum' => $num, 'uid' => $recUid, 'goods_id' => $goodsid]);
                    }
                } else {
                    $res = M($this->goods_table)->where(['id' => $goodsid])->setDec('store_count', $num);
                }
            } else {
                //有规格
                $res = M($this->specgoodsprice_table)->where(['id' => $goodsid, 'key' => $goodskey])->setDec('store_count', $num);
            }
        }
        return $res !== false ? 1 : 0;
    }

    /**
     * 修改销量
     * @param      int         $goodsid   商品ID
     * @param      int         $num       修改数量
     * @param      string|int  $goodskey  商品规格ID
     * @return     int         1：成功、0：失败
     */
    public function SetSalenum($goodsid, $num, $goodskey)
    {
        if ($num >= 0) {
            $res = M($this->goods_table)->where(['id' => $goodsid])->setInc('salenum', $num);
        } else {
            $res = M($this->goods_table)->where(['id' => $goodsid])->setDec('salenum', abs($num));
        }
        return $res !== false ? 1 : 0;
    }

    /**
     * 添加评论
     * @param      int          $goodsid  商品ID
     * @param      int          $uid      用户id
     * @param      array        $data     The data
     * @return     int|boolean  返回评论id，如果返回false则表示写入出错
     */
    public function AddComment($goodsid, $uid, $data = [])
    {
        $insertData = ['goodsid' => $goodsid, 'uid' => $uid];
        if ($data) {
            $insertData = array_merge($insertData, $data);
        }
        if (isset($data['id'])) {
            unset($data['id']);
        }
        $model = D('Shoppingmall/Comment');
        $id    = M($this->comment_table)->add($data);
        $grade = $model->get_next_grade($data['score'], 'goods', $goodsid);
        M($this->goods_table)->where(['id' => $goodsid])->save(['grade' => $grade['avg']]);
        return $id;
    }

    /**
     * 获取评论
     * @param      int          $goodsid    商品ID
     * @param      string|int   $goodskey   商品规格ID
     * @return     array        返回商品评论
     */
    public function GetComment($goodsid, $goodskey = '', $page = 1, $num = 0)
    {
        if ($num) {
            $list = M($this->comment_table)->where(['relate_id' => $goodsid, 'type' => 'goods'])->page($page, $num)->order('id DESC')->select();
        } else {
            $list = M($this->comment_table)->where(['relate_id' => $goodsid, 'type' => 'goods'])->order('id DESC')->select();
        }
        if ($list) {
            $uids  = array_column($list, 'uid');
            $users = M($this->user_table)->where(['id' => ['in', $uids]])->getField('id,nickname,avatar,headimgurl');
            foreach ($list as $key => &$comment) {
                if (isset($users[$comment['uid']])) {
                    $comment['username'] = $users[$comment['uid']]['nickname'];
                    $comment['avatar']   = get_user_avatar($users[$comment['uid']]);
                } else {
                    $comment['username'] = '无名';
                    $comment['avatar']   = get_user_avatar([]);
                }
            }
        }
        return $list;
    }

    /**
     * 喜欢、收藏编辑
     * @param      int          $goodsid  商品ID
     * @param      int          $uid      用户ID
     * @return     int|boolean  添加收藏：返回收藏id，如果返回false则表示写入出错、删除收藏：返回值是删除的记录数，如果返回值是false则表示SQL出错，返回值如果为0表示没有删除任何数据
     */
    public function CollectEdit($goodsid, $uid)
    {
        $result = false;
        //判断是否收藏
        if ($this->CheckCollect($goodsid, $uid)) {
            $result = $this->AddCollect($goodsid, $uid);
        } else {
            $result = $this->DelCollect($goodsid, $uid);
        }
        return $result;
    }

    /**
     * 添加收藏
     * @param      int             $goodsid  商品ID
     * @param      int             $uid      用户ID
     * @return     int|boolean     返回收藏id，如果返回false则表示写入出错
     */
    public function AddCollect($goodsid, $uid)
    {
        return M($this->collect_table)->add(['uid' => $uid, 'goods_id' => $goodsid]);
    }

    /**
     * 删除收藏
     * @param      int          $goodsid  商品ID
     * @param      int          $uid      用户ID
     * @return     int|boolean  返回值是删除的记录数，如果返回值是false则表示SQL出错，返回值如果为0表示没有删除任何数据
     */
    public function DelCollect($goodsid, $uid)
    {
        return M($this->collect_table)->where(['id' => $uid, 'goods_id' => $goodsid])->delete();
    }

    /**
     * 删除多个收藏
     * @param      array        $goodsids  商品ID数组
     * @param      int          $uid       用户ID
     * @return     int|boolean  返回值是删除的记录数，如果返回值是false则表示SQL出错，返回值如果为0表示没有删除任何数据
     */
    public function DelCollectMulti($goodsids, $uid)
    {
        $where = ['goods_id' => ['in', $goodsids], 'uid' => $uid];
        return M($this->collect_table)->where($where)->delete();
    }

    /**
     * 判断是否收藏
     * @param      int      $goodsid   商品ID
     * @param      int      $uid       用户ID
     * @return     boolean  true：已收藏、false：未收藏
     */
    public function CheckCollect($goodsid, $uid)
    {
        $result = false;
        if (!empty(M($this->collect_table)->where(['id' => $uid, 'goods_id' => $goodsid])->find())) {
            $result = true;
        }
        return $result;
    }

    /**
     * 浏览记录
     * @param      int    $goodsid   商品ID
     * @param      int    $uid       用户ID
     */
    public function History($goodsid, $uid)
    {
        $model = M($this->history_table);
        $exist = $model->where(['goods_id' => $goodsid, 'uid' => $uid])->find();
        if ($exist) {
            $model->where(['id' => $exist['id']])->save(['after_time' => datetime($this->history_expire_time)]);
        } else {
            $model->add(['goods_id' => $goodsid, 'uid' => $uid, 'create_time' => datetime(), 'after_time' => datetime($this->history_expire_time)]);
        }
    }

    /**
     * 获取浏览记录商品
     * @param      int      $uid    用户ID
     * @return     array    返回浏览记录
     */
    public function HistoryAll($uid)
    {
        $model     = M($this->history_table);
        $goods_ids = $model->where(['uid' => $uid])->order('after_time DESC')->getField('goods_id');
        if ($goods_ids) {
            return M($this->goods_table)->where(['id' => ['in', $goods_ids]])->select();
        } else {
            return [];
        }
    }

    /**
     * 删除过期的记录
     * @param      int          $uid    用户ID
     * @return     int|boolean  返回值是删除的记录数，如果返回值是false则表示SQL出错，返回值如果为0表示没有删除任何数据
     */
    public function HistoryDeleteExpire($uid)
    {
        return M($this->history_table)->where(['after_time' => ['lt', datetime()]])->delete();
    }

    /**
     * 获取商品规格
     */
    public function GetSpec($goods_id)
    {
        //商品规格 价钱 库存表 找出 所有 规格项id
        $sepcPrice   = M($this->specgoodsprice_table)->where("goods_id = {$goods_id}")->order('key ASC')->getField("key , price, store_count");
        $filter_spec = [];
        if ($sepcPrice) {
            $keys         = array_column($sepcPrice, 'key');
            $keys_str     = implode(',', array_keys($sepcPrice));
            $sql          = "SELECT a.name,a.order,b.* FROM __PREFIX__spec AS a INNER JOIN __PREFIX__spec_item AS b ON a.id = b.spec_id WHERE b.id IN($keys_str) ORDER BY b.id ASC, a.order ASC";
            $filter_spec2 = M()->query($sql);
            foreach ($filter_spec2 as $key => $val) {
                $filter_spec[$val['name']][] = array(
                    'item_id' => $val['id'],
                    'item'    => $val['item'],
                    'price'   => $sepcPrice[$val['id']]['price'],
                );
            }
        }
        return $filter_spec;
    }
    /**
     * 获取商品剩余购买次数
     * @param      int      $goodsid   商品id
     * @param      array    $userInfo  用户信息
     * @return     integer  -1：不限次数
     */
    public function GetLimitNum($goodsid, $uid = 0)
    {
        if ($uid == 0) {
            return -1;
        }

        $limit_num = 0;
        $info      = $this->GetGoodsInfo($goodsid, '', 'status,is_bargain,limit_num');
        if ($info && $info['status'] == 1) {
            if ($info['is_bargain'] == 0 || $info['limit_num'] == 0) {
                $limit_num = -1;
            } else {
                $db_prefix = C('DB_PREFIX');
                $buy_num   = M($this->goodsorderitem_table . ' as goi')
                    ->join('left join ' . $db_prefix . $this->goodsorder_table . ' go on goi.goodsorder_id=go.id')
                    ->where(['go.uid' => $uid, 'go.checkinfo' => ['gt', 0], 'goi.goods_id' => $goodsid])
                    ->sum('goi.buy_num');
                $buy_num   = intval($buy_num);
                $limit_num = $info['limit_num'] - $buy_num;
                if ($limit_num < 0) {
                    $limit_num = 0;
                }
            }
        }
        return $limit_num;
    }
}
