<?php

namespace Addons\YuntxSms;

use Common\Controller\Addon;

/**
 * 云通讯插件插件
 * @author yangweijie
 */

class YuntxSmsAddon extends Addon
{
    public $hooks = [];
    public $info  = array(
        'name'        => 'YuntxSms',
        'title'       => '云通讯插件',
        'description' => '用于配置并发送云通讯短信',
        'status'      => 1,
        'author'      => 'yangweijie',
        'version'     => '0.1',
    );

    public function install()
    {
        return true;
    }

    public function uninstall()
    {
        return true;
    }

}
