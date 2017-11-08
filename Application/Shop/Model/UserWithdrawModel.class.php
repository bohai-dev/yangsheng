<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
/**
 * 默认模型
 * @author jry <598821125@qq.com>
 */
namespace Shop\Model;

use Think\Model;

class UserWithdrawModel extends Model
{
    protected $tableName = 'user_withdraw';
    protected $_validate = array(
        array('status', 'require', '请选择审核状态！'), //默认情况下用正则进行验证
    );

    public $status_texts = [1 => '未处理', 2=> '同意', 3 => '拒绝'];
    // 获取状态
    public function getStatusText($status)
    {
        return $this->status_texts[$status];
    }

    protected function _after_select(&$result, $options)
    {
        $user_ids = array_column($result, 'uid');
        if ($user_ids) {
            $user_ids = array_unique($user_ids);
        }
        $username  = M('admin_user')->where(['id' => ['in', $user_ids]])->getField('id,nickname');
        foreach ($result as &$record) {
                $record['nickname']=$username[$record['uid']];
        }
    }
    protected function _after_find(&$result, $options)
    {
        $result['username'] = M('admin_user')->getFieldById($result['uid'], 'nickname');
    }
}
