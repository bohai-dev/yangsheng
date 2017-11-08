<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Home\Controller;
use Common\Util\Think\Page;
/**
 * 前台默认控制器
 * @author jry <598821125@qq.com>
 */
class IndexController extends BaseController {
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index() {
        redirect(U('Shop/Index/index'));
        $this->display();
    }

    public  function  info(){
        $id =I('id','');
        if(!$id){
            $this->error('参数错误');
        }
        $info =M('admin_post')->where(['id'=>$id])->find();
        $this->assign('info',$info);
        $this->meta_title=$info['title'];
        $this->display();
    }
}
