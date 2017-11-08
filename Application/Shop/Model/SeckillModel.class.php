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
 * 优惠券模型
 */
class SeckillModel extends Model {
    /**
     * 模块名称
     */
    public $moduleName = 'Shop';

    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     */
    protected $tableName = 'shop_seckill';

    public $UpdateID = '';
    /**
     * 自动验证规则
     */
    protected $_validate = array(
            array('id', 'getUpdateID', '', self::EXISTS_VALIDATE, 'callback',self::MODEL_UPDATE),
            array('title', 'require', '活动标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
            array('start_time', 'getStart', '', self::EXISTS_VALIDATE, 'callback',self::MODEL_BOTH),
            array('end_time', 'checkTime', '结束时间需大于开始时间', self::EXISTS_VALIDATE, 'callback',self::MODEL_BOTH),
            array('end_time', 'checkRound', '同时间段只能存在一场秒杀', self::EXISTS_VALIDATE, 'callback',self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     */
    protected $_auto = array(
        array('create_time', 'datetime', self::MODEL_INSERT, 'function'),
        array('update_time', 'datetime', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
    );

    /*
     *验证时间
     */
    public function checkTime($end_time)
    {
        $this->end_time = $end_time;
        if(strtotime($end_time)>strtotime($this->start_time))
            return true;
        return false;
    }


    /**
     * 验证秒杀场次是否重合
     *
     * 确保同一时间只有一场
     */
    public function checkRound()
    {
        $map['_string'] = "(start_time <= '{$this->start_time}' AND
                          '{$this->start_time}' <=  end_time)    OR
                           (start_time <= '{$this->end_time}'    AND
                           '{$this->end_time}'  <=  end_time)";
        $res = M('shop_seckill')->where($map)->field('id')->find();

        if(empty($res) || $res['id'] == $this->UpdateID)
            return true;
        return false;
    }

    /*
     *获取起始时间
     */
    public function getStart($start_time)
    {
        $this->start_time = $start_time;
        return true;
    }

    /**
     * 获取修改ID
     */
    public function getUpdateID($id)
    {
        $this->UpdateID = $id;
    }



}
