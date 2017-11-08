<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author:
// +----------------------------------------------------------------------
namespace Shop\Admin;

use Admin\Controller\AdminController;
use Common\Util\Think\Page;
use Common\Builder\ListBuilder;
use Common\Builder\FormBuilder;
/**
 *秒杀控制器
 */
class UserAdmin extends AdminController
{
  /**
   * 默认方法
   */
  public function index()
  {
    // 搜索
    $keyword         = I('keyword', '', 'string');
    $condition       = array('like', '%' . $keyword . '%');
    $map['id|nickname|mobile'] = array($condition, $condition,$condition, '_multi' => true);

    // 获取所有分类
    $p             = !empty($_GET["p"]) ? $_GET["p"] : 1;
    $map['status'] = array('egt', '0'); // 禁用和正常状态
    $map['user_type'] = 1;
    $map['reg_type'] = 'front';
    $model_object = M('admin_user');
    $data_list     = $model_object
        ->page($p, C('ADMIN_PAGE_ROWS'))
        ->where($map)
        ->order('id DESC')
        ->select();
    $page = new Page(
        $model_object->where($map)->count(),
        C('ADMIN_PAGE_ROWS')
    );

    // 使用Builder快速建立列表页面。
    $builder = new \Common\Builder\ListBuilder();
    $builder->setMetaTitle('用户列表') // 设置页面标题
        // ->addTopButton('addnew') // 添加新增按钮
        ->setSearch('请输入ID/昵称/手机号', U('index'))
        ->addTableColumn('id', 'ID')
        ->addTableColumn('nickname', '昵称')
        ->addTableColumn('avatar', '头像','picture')
        ->addTableColumn('mobile', '手机号')
        ->addTableColumn('score', '积分  ')
        ->addTableColumn('money', '金额  ')
        ->addTableColumn('create_time', '注册时间  ')
        ->addTableColumn('right_button', '操作', 'btn')
        ->setTableDataList($data_list) // 数据列表
        ->setTableDataPage($page->show()) // 数据列表分页
        ->addRightButton('edit') // 添加编辑按钮
        ->addRightButton("self",array('title'=>'查看积分记录','class'=>'label label-primary-outline label-pill','href'=>U('score_record',['id' => '__data_id__'])))
        ->addRightButton("self",array('title'=>'查看佣金记录','class'=>'label label-primary-outline label-pill','href'=>U('commission_record',['id' => '__data_id__'])))
        //->addRightButton('delete') // 添加删除按钮
        ->display();
  }

    public  function  edit($id){
        if(IS_POST){
            $model =M('admin_user');
            $post = I('post.');
            $data = $model->create();
            if($data){
                $user =M('admin_user')->where(['id'=>$id])->find();
                //$result = $model->where(['id'=>$id])->save($data);
                if($user['score']!= $data['score']){
                    if($user['score']<$data['score']){
                        $score =abs($data['score']-$user['score']);
                        $result = set_user_score(1,$score,$id,'后台管理员增加'.$score.'积分');
                    }else{
                        $score =abs($user['score']-$data['score']);
                        $result =set_user_score(2,$score,$id,'后台管理员减少'.$score.'积分');
                    }
                    if($result!==false){

                        $this->success('修改成功', U('index'));
                    }else{
                        $this->error('修改失败');
                    }
                }else{
                    $this->success('修改成功', U('index'));
                }

            }else{
                $this->error($model->getError());
            }
        }else{
            $info  =M('admin_user')->where(['id'=>$id])->find();
            $builder = new  FormBuilder();
            $builder->setMetaTitle('编辑');
            $builder->addFormItem('score', 'num', '用户积分','');
            $builder->setFormData($info);
            $builder->display();
        }
    }

    public  function score_record($id){
        $map = [];
        $map['uid']=$id;
        list($data_list, $page, $model) = $this->lists('user_score', $map);
        $builder = new ListBuilder();
        $builder->setMetaTitle('积分记录');
        $builder->addTableColumn('title', '标题');
        $builder->addTableColumn('type','类型','callback','get_score_type');
        $builder->addTableColumn('score','积分');
        $builder->addTableColumn('create_time','时间');
        $builder->setTableDataList($data_list);
        $builder->setTableDataPage($page->show());
        $builder->display();
    }

    public  function  commission_record($id){
        $map = [];
        $map['uid']=$id;
        list($data_list, $page, $model) = $this->lists('user_money', $map);
        $builder = new ListBuilder();
        $builder->setMetaTitle('佣金记录');
        $builder->addTableColumn('title', '标题');
        $builder->addTableColumn('money','金额');
        $builder->addTableColumn('create_time','时间');
        $builder->setTableDataList($data_list);
        $builder->setTableDataPage($page->show());
        $builder->display();
    }

    public  function  user_complain(){
        $map = [];
        $map['status'] =1;
        list($data_list, $page, $model) = $this->lists('user_complain', $map);
        $builder = new ListBuilder();
        $builder->setMetaTitle('用户意见列表');
        $builder->addTableColumn('content', '内容');
        $builder->addTableColumn('pics','图片','pictures');
        $builder->addTableColumn('contacts','联系方式');
        $builder->addTableColumn('time','时间');
        $builder->addTableColumn('right_button', '操作管理', 'btn');
        $builder->setTableDataList($data_list);
        $builder->setTableDataPage($page->show());
        $builder->addRightButton('delete',['model'=>'user_complain']); // 添加编辑按钮
        $builder->display();
    }
}


