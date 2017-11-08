<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace shop\Model;
use Think\Model;
/**
 * 今日头条模型
 */
class BulletinModel extends Model {
    /**
     * 数据库表名
     */
    protected $tableName = 'shop_bulletin';

    /**
     * 自动验证规则
     */
    protected $_validate = array(
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('pictures', 'require', '请上传封面图片', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('description', 'require', '描述不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     */
    protected $_auto = array(
        array('create_time', 'datetime', self::MODEL_INSERT, 'function'),
        array('update_time', 'datetime', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
    );



    //头像
    public function getImg($avatar)
    {
        $src = '';
        if ($avatar) {
            $src = "<img src='$avatar' width='40'/>";
        }
        return $src;
    }
}
