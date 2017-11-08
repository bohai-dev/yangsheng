<?php
namespace Forum\Behavior;
use Think\Behavior;
use Think\Hook;
defined('THINK_PATH') or exit();
/**
 * 行为扩展
 */
class ForumBehavior extends Behavior
{
    /**
     * 行为扩展的执行入口必须是run
     */
    public function run(&$content)
    {
        // 行为扩展逻辑
    }
}