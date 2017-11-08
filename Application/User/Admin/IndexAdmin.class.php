<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace User\Admin;
use Admin\Controller\AdminController;
use Common\Util\Think\Page;
/**
 * 默认控制器
 * @author jry <598821125@qq.com>
 */
class IndexAdmin extends AdminController {
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index() {
        //计算统计图日期
        $type = I('get.type','');
        $user_object = M('User');
        if ($type == 'month') {
            //按月统计 
            $start_date = I('get.start_date') ? substr(I('get.start_date'),0,7) : date('Y-m',strtotime('-12 month'));
            //过去12个月
            $end_date = I('get.end_date') ? substr(I('get.end_date'),0,7) : date('Y-m',strtotime('+0 month'));
            $i = 0;
            $month = '';
            while($month != $end_date ){
                $month = date('Y-m',strtotime('+'.$i.' month '.$start_date));
                $next_month = date('Y-m',strtotime('+'.($i+1).' month'.$start_date));
                $map['create_time'] = [
                    ['egt',$month.'-00'],
                    ['lt',$next_month.'-00'],
                ];
                $user_reg_date[] = date('y年m月', strtotime(($month)));
                $user_reg_count[] = (int)$user_object->where($map)->count();                
                $i++;
            }
            $count_day = $i;
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date.' +1 month')-1);
        }else if($type == 'year'){
            //按年统计
            $start_date = I('get.start_date') ? I('get.start_date') : date('Y-m-d',strtotime('-5 year'));
            //过去5年
            $end_date = I('get.end_date') ? I('get.end_date') : date('Y-m-d',strtotime('+0 year'));
            $i = 0;
            $end_date = substr($end_date,0,4);
            $year = '';
            while($year != $end_date ){
                $year = date('Y',strtotime('+'.$i.' year '.$start_date));
                $next_year = date('Y',strtotime('+'.($i+1).' year'.$start_date));
                $map['create_time'] = [
                    ['egt',$year.'-00-00'],
                    ['lt',$next_year.'-00-00'],
                ];
                $user_reg_date[] = date('Y年', strtotime(($year.'-01-01')));
                $user_reg_count[] = (int)$user_object->where($map)->count();                
                $i++;
            }
            $count_day = $i;
            $start_date = date('Y', strtotime($start_date)).'-01-01';
            $end_date = date('Y', strtotime($end_date.' +1 year')).'-01-01';           
        }else{
            //按日统计 2周
            $today = strtotime(date('Y-m-d', time())); //今天
            $start_date = I('get.start_date') ? strtotime(I('get.start_date')) : $today-14*86400;
            $end_date   = I('get.end_date') ? (strtotime(I('get.end_date'))+1) : $today+86400;
            $count_day  = ($end_date-$start_date)/86400; //查询最近n天
            for($i = 0; $i < $count_day; $i++){
                $day_stamp = $start_date + $i*86400; //第n天日期
                $day_after_stamp = $start_date + ($i+1)*86400; //第n+1天日期
                
                $day = date('Y-m-d H:i:s',$day_stamp);
                $day_after = date('Y-m-d H:i:s',$day_after_stamp);
                $map['create_time'] = array(
                    array('egt', $day),
                    array('lt', $day_after)
                );
                $user_reg_date[] = date('m月d日', $day_stamp);
                $user_reg_count[] = (int)$user_object->where($map)->count();
            }
            $start_date = date('Y-m-d', $start_date);
            $end_date = date('Y-m-d', $end_date-1);            
        }

        $this->assign('type', $type);
        $this->assign('start_date', $start_date);
        $this->assign('end_date', $end_date);
        $this->assign('count_day', $count_day);
        $this->assign('user_reg_date', json_encode($user_reg_date));
        $this->assign('user_reg_count', json_encode($user_reg_count));
        $this->assign('meta_title', "用户");
        $this->display();
    }


    public function coupon_receive(){
        //计算统计图日期
        $type = I('get.type','');
        $user_object = M('User');
        $coupon_object = M('coupon_user');
        $user_count = $user_object->count();
        if ($type == 'month') {
            //按月统计 
            $start_date = I('get.start_date') ? substr(I('get.start_date'),0,7) : date('Y-m',strtotime('-12 month'));
            //过去12个月
            $end_date = I('get.end_date') ? substr(I('get.end_date'),0,7) : date('Y-m',strtotime('+0 month'));
            $i = 0;
            $month = '';
            while($month != $end_date ){
                $month = date('Y-m',strtotime('+'.$i.' month '.$start_date));
                $next_month = date('Y-m',strtotime('+'.($i+1).' month'.$start_date));
                $map['createtime'] = [
                    ['egt',$month.'-00'],
                    ['lt',$next_month.'-00'],
                ];
                $x_date[] = date('y年m月', strtotime(($month)));
                $y_coupon_use[] = (float)$coupon_object->where($map)->count()/$user_count;                
                $i++;
            }
            $count_day = $i;
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date.' +1 month')-1);
        }else if($type == 'year'){
            //按年统计
            $start_date = I('get.start_date') ? I('get.start_date') : date('Y-m-d',strtotime('-5 year'));
            //过去5年
            $end_date = I('get.end_date') ? I('get.end_date') : date('Y-m-d',strtotime('+0 year'));
            $i = 0;
            $end_date = substr($end_date,0,4);
            $year = '';
            while($year != $end_date ){
                $year = date('Y',strtotime('+'.$i.' year '.$start_date));
                $next_year = date('Y',strtotime('+'.($i+1).' year'.$start_date));
                $map['createtime'] = [
                    ['egt',$year.'-00-00'],
                    ['lt',$next_year.'-00-00'],
                ];
                $x_date[] = date('Y年', strtotime(($year.'-01-01')));
                $y_coupon_use[] = (float)$coupon_object->where($map)->count()/$user_count;                
                $i++;
            }
            $count_day = $i;
            $start_date = date('Y', strtotime($start_date)).'-01-01';
            $end_date = date('Y', strtotime($end_date.' +1 year')).'-01-01';           
        }else{
            //按日统计 2周
            $today = strtotime(date('Y-m-d', time())); //今天
            $start_date = I('get.start_date') ? strtotime(I('get.start_date')) : $today-14*86400;
            $end_date   = I('get.end_date') ? (strtotime(I('get.end_date'))+1) : $today+86400;
            $count_day  = ($end_date-$start_date)/86400; //查询最近n天
            $map['status'] = 1;
            for($i = 0; $i < $count_day; $i++){
                $day_stamp = $start_date + $i*86400; //第n天日期
                $day_after_stamp = $start_date + ($i+1)*86400; //第n+1天日期
                $day = date('Y-m-d H:i:s',$day_stamp);
                $day_after = date('Y-m-d H:i:s',$day_after_stamp);
                $map['createtime'] = array(
                    array('egt', $day),
                    array('lt', $day_after)
                );
                $x_date[] = date('m月d日', $day_stamp);
                $y_coupon_use[] = (float)$coupon_object->where($map)->count()/$user_count;
            }
            $start_date = date('Y-m-d', $start_date);
            $end_date = date('Y-m-d', $end_date-1);            
        }

        $this->assign('type', $type);
        $this->assign('start_date', $start_date);
        $this->assign('end_date', $end_date);
        $this->assign('count_day', $count_day);
        $this->assign('x_date', json_encode($x_date));
        $this->assign('y_coupon_use', json_encode($y_coupon_use));
        $this->assign('meta_title', "优惠券领取");
        $this->display();        
    }


    public function coupon_use(){
        //计算统计图日期
        $type = I('get.type','');
        $user_object = M('User');
        $coupon_object = M('coupon_user');
        $category = '';
        $user_count = $user_object->count();
        if ($sid = I('shop_type','')) {
            $map['sid'] = $sid;
            $category = M('type')->where(['id'=>$sid])->getField('name');
        }
        $map['status'] = 1;
        $map['used'] = 1;
        if ($type == 'month') {
            //按月统计 
            $start_date = I('get.start_date') ? substr(I('get.start_date'),0,7) : date('Y-m',strtotime('-12 month'));
            //过去12个月
            $end_date = I('get.end_date') ? substr(I('get.end_date'),0,7) : date('Y-m',strtotime('+0 month'));
            $i = 0;
            $month = '';
            while($month != $end_date ){
                $month = date('Y-m',strtotime('+'.$i.' month '.$start_date));
                $next_month = date('Y-m',strtotime('+'.($i+1).' month'.$start_date));
                $map['usetime'] = [
                    ['egt',$month.'-00'],
                    ['lt',$next_month.'-00'],
                ];
                $x_date[] = date('y年m月', strtotime(($month)));
                $y_coupon_use[] = (float)$coupon_object->where($map)->count()/$user_count;                
                $i++;
            }
            $count_day = $i;
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date.' +1 month')-1);
        }else if($type == 'year'){
            //按年统计
            $start_date = I('get.start_date') ? I('get.start_date') : date('Y-m-d',strtotime('-5 year'));
            //过去5年
            $end_date = I('get.end_date') ? I('get.end_date') : date('Y-m-d',strtotime('+0 year'));
            $i = 0;
            $end_date = substr($end_date,0,4);
            $year = '';
            while($year != $end_date ){
                $year = date('Y',strtotime('+'.$i.' year '.$start_date));
                $next_year = date('Y',strtotime('+'.($i+1).' year'.$start_date));
                $map['usetime'] = [
                    ['egt',$year.'-00-00'],
                    ['lt',$next_year.'-00-00'],
                ];
                $x_date[] = date('Y年', strtotime(($year.'-01-01')));
                $y_coupon_use[] = (float)$coupon_object->where($map)->count()/$user_count;                
                $i++;
            }
            $count_day = $i;
            $start_date = date('Y', strtotime($start_date)).'-01-01';
            $end_date = date('Y', strtotime($end_date.' +1 year')).'-01-01';           
        }else{
            //按日统计 2周
            $today = strtotime(date('Y-m-d', time())); //今天
            $start_date = I('get.start_date') ? strtotime(I('get.start_date')) : $today-14*86400;
            $end_date   = I('get.end_date') ? (strtotime(I('get.end_date'))+1) : $today+86400;
            $count_day  = ($end_date-$start_date)/86400; //查询最近n天
            for($i = 0; $i < $count_day; $i++){
                $day_stamp = $start_date + $i*86400; //第n天日期
                $day_after_stamp = $start_date + ($i+1)*86400; //第n+1天日期
                $day = date('Y-m-d H:i:s',$day_stamp);
                $day_after = date('Y-m-d H:i:s',$day_after_stamp);
                $map['usetime'] = array(
                    array('egt', $day),
                    array('lt', $day_after)
                );
                $x_date[] = date('m月d日', $day_stamp);
                $y_coupon_use[] = (float)$coupon_object->where($map)->count()/$user_count;
            }
            $start_date = date('Y-m-d', $start_date);
            $end_date = date('Y-m-d', $end_date-1);            
        }

        $types = M('type')->where(['pid'=>1])->getField('id,name');
        $this->assign('category', $category);
        $this->assign('types', $types);
        $this->assign('type', $type);
        $this->assign('start_date', $start_date);
        $this->assign('end_date', $end_date);
        $this->assign('count_day', $count_day);
        $this->assign('x_date', json_encode($x_date));
        $this->assign('y_coupon_use', json_encode($y_coupon_use));
        $this->assign('meta_title', "优惠券使用");
        $this->display();       
    }


    public function index_pv(){
        //计算统计图日期
        $type = I('get.type','');
        $visit_object = M('pv_count');
        $map['status'] = 1;
        if ($type == 'month') {
            //按月统计 
            $start_date = I('get.start_date') ? substr(I('get.start_date'),0,7) : date('Y-m',strtotime('-12 month'));
            //过去12个月
            $end_date = I('get.end_date') ? substr(I('get.end_date'),0,7) : date('Y-m',strtotime('+0 month'));
            $i = 0;
            $month = '';
            while($month != $end_date ){
                $month = date('Y-m',strtotime('+'.$i.' month '.$start_date));
                $next_month = date('Y-m',strtotime('+'.($i+1).' month'.$start_date));
                $map['visit_time'] = [
                    ['egt',$month.'-00'],
                    ['lt',$next_month.'-00'],
                ];
                $x_date[] = date('y年m月', strtotime(($month)));
                $y_visit_times[] = (int)$visit_object->where($map)->count();                
                $i++;
            }
            $count_day = $i;
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date.' +1 month')-1);
        }else if($type == 'year'){
            //按年统计
            $start_date = I('get.start_date') ? I('get.start_date') : date('Y-m-d',strtotime('-5 year'));
            //过去5年
            $end_date = I('get.end_date') ? I('get.end_date') : date('Y-m-d',strtotime('+0 year'));
            $i = 0;
            $end_date = substr($end_date,0,4);
            $year = '';
            while($year != $end_date ){
                $year = date('Y',strtotime('+'.$i.' year '.$start_date));
                $next_year = date('Y',strtotime('+'.($i+1).' year'.$start_date));
                $map['visit_time'] = [
                    ['egt',$year.'-00-00'],
                    ['lt',$next_year.'-00-00'],
                ];
                $x_date[] = date('Y年', strtotime(($year.'-01-01')));
                $y_visit_times[] = (int)$visit_object->where($map)->count();                
                $i++;
            }
            $count_day = $i;
            $start_date = date('Y', strtotime($start_date)).'-01-01';
            $end_date = date('Y', strtotime($end_date.' +1 year')).'-01-01';           
        }else{
            $today = strtotime(date('Y-m-d', time())); //今天
            $start_date = I('get.start_date') ? strtotime(I('get.start_date')) : $today-14*86400;
            $end_date   = I('get.end_date') ? (strtotime(I('get.end_date'))+1) : $today+86400;
            $count_day  = ($end_date-$start_date)/86400; //查询最近n天            
            for($i = 0; $i < $count_day; $i++){
                $day_stamp = $start_date + $i*86400; //第n天日期
                $day_after_stamp = $start_date + ($i+1)*86400; //第n+1天日期
                $day = date('Y-m-d H:i:s',$day_stamp);
                $day_after = date('Y-m-d H:i:s',$day_after_stamp);
                $map['visit_time'] = array(
                    array('egt', $day),
                    array('lt', $day_after)
                );
                
                $x_date[] = date('m月d日', $day_stamp);
                $y_visit_times[] = (int)$visit_object->where($map)->count();

            }
            $start_date = date('Y-m-d', $start_date);
            $end_date = date('Y-m-d', $end_date-1);            
        }

        $this->assign('type', $type);
        $this->assign('start_date', $start_date);
        $this->assign('end_date', $end_date);
        $this->assign('count_day', $count_day);
        $this->assign('x_date', json_encode($x_date));
        $this->assign('y_visit_times', json_encode($y_visit_times));
        $this->assign('meta_title', "首页访问量");
        $this->display(); 
    }


    public function ajax_type(){
        $types = [];
        if (IS_AJAX) {
            $types = M('type')->where(['pid'=>I('id')])->getField('id,name');
        }
        $this->ajaxReturn($types);
    }










}