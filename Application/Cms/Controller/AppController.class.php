<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Cms\Controller;
use Home\Controller\HomeController;
use Common\Util\Think\Page;
/**
 * 幻灯片控制器
 * @author jry <598821125@qq.com>
 */
class AppController extends HomeController {
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index() {
    	 $builder = new \Common\Builder\ListBuilder();
    	  $builder->setMetaTitle('模型列表')  // 设置页面标题
    			  // ->addTableColumn('icon', '图标', 'icon')

 				->display();	
     	// $this->assign('cc','123');
       // $this->display();
    }
}
