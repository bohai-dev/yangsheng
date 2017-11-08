<?php
/**
 * Created by PhpStorm.
 * User: 水目
 * Date: 2017/3/16 0016
 * Time: 15:38
 */
namespace Shop\Model;
use Think\Model;
/**
 * 管理员与用户组对应关系模型
 * @author jry <598821125@qq.com>
 */
class AwardModel extends Model {
    protected $tableName = 'shop_award';
    /**
     * 自动验证规则
     */
    protected $_validate = array(
        array('type', 'require', '请选择奖品类型', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('type', 'require', '请填写奖品名称', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('rate', 'require', '请填写奖品概率', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );
    //  获取奖品
    public  function  get_award($limit = 10, $page = 1,$field='*',$order = null, $map = null){
        $con["status"] = array("eq", '1');
        if ($map) {
            $map = array_merge($con, $map);
        }else{
            $map=$con;
        }
        if (!$order) {
            $order = 'sort desc, id desc';
        }
        $slider_list = $this->page($page,$limit)
            ->field($field)
            ->order($order)
            ->where($map)
            ->select();
        return $slider_list;
    }
    // 中奖概率算法
    public  function get_rand($proArr) {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        return $result;
    }

    public  function  get_award_name($id){
        return M('shop_award')->where(['id'=>$id])->getField('title')?:'未查到';
    }
}