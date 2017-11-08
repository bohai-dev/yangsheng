<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Shop\Admin;
use Admin\Controller\AdminController;
use Common\Util\Think\Page;
/**
 * 商品类别控制器
 */
class GoodstypeAdmin extends AdminController {

    public function index()
    {
        //顶部导航
        $group = I('group',1);
        $tab_list = array(
            '1' => array(
                'title' => '普通分类',
                'href'   => U('index', array('group' => 1)),
            ),
            '2' => array(
                'title' => '个护美妆',
                'href'   => U('index', array('group' => 2)),
            ),
            '3' => array(
                'title' => '开店必备',
                'href'   => U('index', array('group' => 3)),
            ),
            '4' => array(
                'title' => '积分商城',
                'href'   => U('index', array('group' => 4)),
            )
        );
        // $map['pid'] = ['neq',0];
        $map['group'] =$group;
        $map['check'] =0;
        $case = 0;
        //如果是美妆 必备 则页面样式不一样
        if($group ==1){
            $map['pid'] = 0;
            $case=1;
        }elseif($group==2|| $group==3){
            $case=2;
        }elseif($group==4){
            $case=3;
        }
        // 搜索
        $keyword         = I('keyword', '', 'string');
        $condition       = array('like', '%' . $keyword . '%');
        $map['id|title'] = array($condition, $condition, '_multi' => true);
        //数据 分页
        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $user_object   = M('shop_goodstype');
        $data_list     = $user_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('id DESC')
            ->select();
        $page = new Page(
            $user_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );
        //自定义按钮
        $rbtn['name']  = 'view';
        $rbtn['title'] = '查看子级';
        $rbtn['class'] = 'label label-success-outline label-pill';
        $rbtn['href']  = U('Shop/Goodstype/type', array('pid' => '__data_id__'));

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('商品分类'); // 设置页面标题
        switch ($case) {
            case '1': //普通商品
                $builder->addTopButton('addnew',['href'=>U('add',['group'=>$group])])
                    ->setSearch('请输入ID/名称', U('index',['group'=>$group]))
                    ->setTabNav($tab_list, $group)
                    ->addTableColumn('id', 'ID')
                    ->addTableColumn('title', '名称')
                    ->addTableColumn('right_button', '操作', 'btn')
                    ->setTableDataList($data_list) // 数据列表
                    ->setTableDataPage($page->show()) // 数据列表分页
                    ->addRightButton('edit')
                    ->addRightButton('delete',['model'=>'shop_goodstype'])
                    ->addRightButton('self',$rbtn);
                break;
            case '2': //个护美妆 开店必备
                $builder->setTabNav($tab_list, $group)
                        ->addTableColumn('id', 'ID')
                        ->addTableColumn('title', '名称')
                        ->addTableColumn('icon', '图标','picture')
                        ->addTableColumn('right_button', '操作', 'btn')
                        ->setTableDataList($data_list)
                        ->setTableDataPage($page->show())
                        ->addRightButton('edit')
                        ->addRightButton('forbid',['model'=>'shop_goodstype']);
                break;
            case '3'://积分商城
            // ->addTopButton('addnew',['href'=>U('add',['group'=>$group])])
            // ->setSearch('请输入ID/名称', U('index',['group'=>$group]))

               $builder ->setTabNav($tab_list, $group)
                        ->addTableColumn('id', 'ID')
                        ->addTableColumn('title', '名称')
                        // ->addTableColumn('icon', '图标','picture')
                        ->addTableColumn('right_button', '操作', 'btn')
                        ->setTableDataList($data_list)
                        ->setTableDataPage($page->show())
                        ->addRightButton('edit');
                        // ->addRightButton('delete',['model'=>'shop_goodstype']);
                break;
            default:
                break;
        }
        $builder->display();
    }


    /*
     *只为 普通类别 服务
     */
    public function type($pid)
    {
        //搜索
        $keyword                                  = I('keyword', '', 'string');
        $condition                                = array('like', '%' . $keyword . '%');
        $map['id|title'] = array(
            $condition,
            $condition,
            '_multi' => true,
        );
        //自定义按钮
        $tbtn['name']  = 'back';
        $tbtn['title'] = '返回';
        $tbtn['class'] = 'btn btn-default-outline btn-pill';
        $tbtn['href']  = U('Shop/Goodstype/index');
        //数据 分页
        $map['pid'] = $pid;
        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $user_object   = M('shop_goodstype');
        $data_list     = $user_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('id DESC')
            ->select();
        $page = new Page(
            $user_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('商品类别')// 设置页面标题
            ->setSearch('请输入ID/名称', U('type',['pid'=>$pid]))
            ->addTopButton('addnew',['href'=>U('add',['group'=>1,'pid'=>$pid])])
            ->addTopButton('self',$tbtn)// 添加返回按钮
            ->addTableColumn('id', 'ID')
            ->addTableColumn('title', '名称')
            // ->addTableColumn('icon', '图标','picture')
            ->addTableColumn('cover', '封面','picture')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit')
            ->addRightButton('delete',['model'=>'shop_goodstype'])
            ->display();
    }

    /**
     * 新增商品类别
     */
    public function add($group,$pid='')
    {
        $type_object = D('goodstype');
        if (IS_POST) {
            if (!$type_object->create()) {
                $this->error($type_object->getError());
            }
            if ($type_object->add()) {
                if(!empty($pid)){
                    $this->success('新增成功', U('type',['pid'=>$pid]));
                }elseif($pid==0){
                    $this->success('新增成功', U('index'));
                }
            } else {
                $this->error('新增失败');
            }
        } else {
            $group_arr = [1=>'普通商城',4=>'积分商城'];
            $info['group'] = $group;
            $group == 1 && empty($pid) ?$pid=0:'';
            $info['pid'] = $pid;
            if(!empty($pid))
                $info['pid_title'] = $type_object->where("id=$pid")->getfield('title');
            else
                $info['pid_title'] = $group_arr[$group];

            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增商品类别') //设置页面标题
                    ->setPostUrl(U('add'))    //设置表单提交地址
                    ->addFormItem('group', 'hidden', '', '')
                    ->addFormItem('pid', 'hidden', '', '')
                    ->addFormItem('pid_title', 'static', '上级分类', '')
                    ->addFormItem('title', 'text', '名称', '分类名称');
            if($group ==4){
                // $builder->addFormItem('icon', 'picture', '图标', '分类图标');
            }elseif(!empty($pid)){
                $builder->addFormItem('cover', 'picture', '封面', '推荐尺寸:310*310');
            }
            $builder->setFormData($info)
                    ->display();
        }
    }



    /**
     * 编辑类别 $group 哪种分类
     */
    public function edit($id)
    {
        $type_object = D('goodstype');
        if (IS_POST) {
            $pid = I('post.pid');
            $group = I('post.group');
          if (!$type_object->create()) {
              $this->error($type_object->getError());
          }
          if ($type_object->save()) {
              if($pid){
                $this->success('更新成功', U('type',['pid'=>$pid,'group'=>$group]));
              }else if($pid==0){
                $this->success('更新成功', U('index',['group'=>$group]));
              }
          } else {
              $this->error('更新失败', $type_object->getError());
          }
        } else {

            // 获取用户类别信息
            $info = $type_object->find($id);
            $pid= $info['pid'];
            $group= $info['group'];

            // $info['group'] = $group;
            // $group == 1 && empty($pid) ?$pid=0:'';
            // $info['pid'] = $pid;


            // if(!empty($pid))
            //     $info['pid_title'] = $type_object->where("id=$pid")->getfield('title');
            // else
            //     $info['pid_title'] = $group_arr[$group];



            if($pid==0){
                $group_arr = [1=>'普通商城',2=>'个护美妆',3=>'开店必备',4=>'积分商城'];
                $pid_title = $group_arr[$group];
            }else{
                $pid_title = $type_object->where(['id'=>$info['pid']])->getfield('title');
            }
            $info['pid_title'] = $pid_title;
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑')  // 设置页面标题
                    ->setPostUrl(U('edit'))    // 设置表单提交地址
                    ->addFormItem('id', 'hidden', 'ID', 'ID')
                    ->addFormItem('pid', 'hidden', '', '')
                    ->addFormItem('group', 'hidden', '', '')
                    ->addFormItem('pid_title', 'static', '上级分类', '上级分类')
                    ->addFormItem('title', 'text', '名称', '商品类别名称');
            if($group ==2 || $group ==3){
                $builder->addFormItem('icon', 'picture', '图标', '推荐尺寸:102*102');
            }
            if($group==1 && !empty($pid)){
                $builder->addFormItem('cover', 'picture', '封面', '推荐尺寸:310*310');
            }
            $builder->setFormData($info)
                    ->display();
        }
    }
}