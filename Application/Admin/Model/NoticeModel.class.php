<?php
/**
 * Created by PhpStorm.
 * User: 水目
 * Date: 2017/3/14 0014
 * Time: 15:11
 */
namespace Admin\Model;
use Common\Model\ModelModel;
class NoticeModel extends ModelModel {
    protected $tableName = 'shop_notice';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        // array('url', 'require', '链接不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('abstract', 'require', '概述不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('cover', 'require', '请上传图片', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    public  function  get_list($map=null,$order=null){
        $con["status"] = array("eq", '1');
        if ($map) {
            $map = array_merge($con, $map);
        }else{
            $map['status'] =1;
        }
        if (!$order) {
            $order = 'id desc';
        }
        $slider_list = $this->order($order)->where($map)->select();
        return $slider_list;
    }
}