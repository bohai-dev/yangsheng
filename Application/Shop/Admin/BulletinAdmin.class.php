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
use Common\Builder\ListBuilder;
use Common\Builder\FormBuilder;
/**
 * 今日头条控制器
 */
class BulletinAdmin extends AdminController {

    public function index()
    {
        // 搜索
        $keyword                                  = I('keyword', '', 'string');
        $condition                                = array('like', '%' . $keyword . '%');
        $map['id|title'] = array(
            $condition,
            $condition,
            '_multi' => true,
        );
        $type_select =D('type')->where(['status'=>1])->getField('id,title');
        if ($type = I('type', '')) {
            $map['_string'] ='FIND_IN_SET('.$type.',type)';
        }
        //评论 按钮
        $rbtn['name']  = 'view';
        $rbtn['title'] = '查看评论';
        $rbtn['class'] = 'label label-success-outline label-pill';
        $rbtn['href']  = U('Shop/Bulletin/review', array('id' => '__data_id__'));

        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $user_object   = M('shop_bulletin');
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
        $builder->setMetaTitle('今日头条') // 设置页面标题
            ->addTopButton('addnew') // 添加新增按钮
            ->addSearchItem('type', 'select', '', '', ['' => '分类'] + $type_select)
            ->addSearchItem('keyword', 'text', '', '请输入ID/标题')
            ->addTableColumn('id', 'ID')
            ->addTableColumn('title', '标题')
            ->addTableColumn('title', '分类')
            // ->addTableColumn('status', '状态','status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('delete',['model'=>'shop_bulletin']) // 添加删除按钮
            ->addRightButton('self',$rbtn) // 评论按钮
            ->display();
    }

    /**
     * 新增商品类型
     */
    public function add() {
        if (IS_POST) {
            $model_object = D('bulletin');
            if ($model_object->create()) {
                if ($model_object->add()) {
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($model_object->getError());
            }
        } else {
            $type =D('type')->where(['status'=>1])->getField('id,title');
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增商品类型') //设置页面标题
                    ->setPostUrl(U('add'))    //设置表单提交地址
                    ->addFormItem('title', 'text', '标题', '今日头条标题')
                    ->addFormItem('type', 'radio', '分类', '',$type)
                    ->addFormItem('pictures', 'pictures', '封面图片', '只显示前3张','')
                    ->addFormItem('description', 'kindeditor', '详情描述', '','3')
                    ->addFormItem('status', 'radio', '状态', '',[0=>'禁用',1=>'启用'])
                    ->display();

                    // ,['self'=>['limit'=>3]]
        }
    }

    /**
     * 编辑商品类型
     */
    public function edit($id) {
        $model_object = D('bulletin');
        if (IS_POST) {
            if ($model_object->create()) {
                if ($model_object->save()) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败', $model_object->getError());
                }
            } else {
                $this->error($model_object->getError());
            }
        } else {
            // 获取用户类型信息
            $info = $model_object->find($id);
            // $pics_arr = explode(',',$info['pictures']);
            // $pic_num =3 -  count($pics_arr);
            // var_dump( $pic_num);die;
            // 使用FormBuilder快速建立表单页面。
            $type =D('type')->where(['status'=>1])->getField('id,title');
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑')  // 设置页面标题
                    ->setPostUrl(U('edit'))    // 设置表单提交地址
                    ->addFormItem('id', 'hidden', '', '')
                    ->addFormItem('title', 'text', '标题', '今日头条标题')
                    ->addFormItem('type', 'radio', '分类', '',$type)
                    ->addFormItem('pictures', 'pictures', '封面图片', '只显示前3张','')
                    ->addFormItem('description', 'kindeditor', '详情描述', '','3')
                    ->addFormItem('status', 'radio', '状态', '',[0=>'禁用',1=>'启用'])
                    ->setFormData($info)
                    ->display();
        }
    }

    /**
     * 今日头条 评论
     */
    public function review()
    {
        $id = I('id','');
        $id?:exit;

        // 搜索
        $keyword                                  = I('keyword', '', 'string');
        $condition                                = array('like', '%' . $keyword . '%');
        $map['oc_shop_bulletin_review.id|oc_user.nickname'] = array(
            $condition,
            $condition,
            '_multi' => true,
        );


        //回复评论 按钮
        $rbtn['name']  = 'view';
        $rbtn['title'] = '回复';
        $rbtn['class'] = 'label label-primary-outline label-pill';
        $rbtn['href']  = U('Shop/Bulletin/reply', array('id' => '__data_id__'));

        //头部返回按钮
        $tbtn['name']  = 'go-back';
        $tbtn['title'] = '返回';
        $tbtn['class'] = 'btn btn-default-outline btn-pill';
        $tbtn['href']  = U('Shop/Bulletin/index');

        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $map['oc_shop_bulletin_review.bid'] = $id;
        $review_obejct   = M('shop_bulletin_review');
        $data_list     = $review_obejct
            ->join('oc_user ON oc_user.admin_uid = oc_shop_bulletin_review.uid','LEFT')
            ->field('oc_shop_bulletin_review.id,
                     oc_shop_bulletin_review.content,
                     oc_shop_bulletin_review.reply,
                     oc_shop_bulletin_review.create_time,
                     oc_shop_bulletin_review.status,
                     oc_user.nickname,
                     oc_user.headimgurl
                     ')
            ->where($map)
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->order('id DESC')
            ->select();
        $page = new Page(
            $review_obejct
            ->join('oc_user ON oc_user.admin_uid = oc_shop_bulletin_review.uid','LEFT')
            ->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('评论列表') // 设置页面标题
            ->setSearch('ID/评论人姓名',U('',['id'=>$id]))
            ->addTopButton('self',$tbtn)
            ->addTableColumn('id', 'ID')
            ->addTableColumn('nickname', '评论人')
            ->addTableColumn('headimgurl', '头像','callback',[D('Shop/Bulletin'),'getImg'])
            ->addTableColumn('content', '内容')
            ->addTableColumn('reply', '回复')
            ->addTableColumn('create_time', '评论时间')
            // ->addTableColumn('status', '状态','status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('forbid',['model'=>'shop_bulletin_review']) // 添加禁用/启用按钮
            ->addRightButton('self',$rbtn) // 添加禁用/启用按钮
            ->addRightButton('delete',['model'=>'shop_bulletin_review']) // 添加删除按钮
            ->display();
    }

    public function  reply($id)
    {
        $model_object = M('shop_bulletin_review');
        if (IS_POST) {
            if ($data = $model_object->create()) {
                if ($model_object->save() !==false) {
                    //发送模板消息
                    $weixin_class = new \Home\Controller\WeixinController();
                    $openid = M('user')->where(['admin_uid'=>$data['uid']])->getfield('openid');
                    $title = M('shop_bulletin')->where(['id'=>$data['bid']])->getfield('title');
                    $message = '您评论的今日头条《'.$title.'》得到了回复';
                    $weixin_class->send_custom($openid,$message);
                    $this->success('回复成功', U('review',['id'=>$data['bid']]));
                } else {
                    $this->error('回复失败', $model_object->getError());
                }
            } else {
                $this->error($model_object->getError());
            }
        } else {
            // 获取用户类型信息
            $info = $model_object->find($id);

            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('回复')  // 设置页面标题
                    ->setPostUrl(U(''))    // 设置表单提交地址
                    ->addFormItem('id', 'hidden', '', '')
                    ->addFormItem('bid', 'hidden', '', '')
                    ->addFormItem('uid', 'hidden', '', '')
                    ->addFormItem('content', 'static', '评论内容', '')
                    ->addFormItem('reply', 'textarea', '回复', '')
                    ->setFormData($info)
                    ->display();
        }
    }

    public function type_index()
    {
        $map=[];
        $keyword              = I('keyword', '', 'string');
        $condition            = array('like', '%' . $keyword . '%');
        $map['id|title'] = array($condition, $condition, $condition, '_multi' => true);
        list($data_list, $page, $model_object) = $this->lists('Type',$map, 'id ASC');
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle("列表") // 设置页面标题
        ->addTopButton("addnew",['href'=>U('type_add')]) // 添加新增按钮
        ->setSearch("请输入ID/标题", U("index"))
            ->addTableColumn("id", "ID")
            ->addTableColumn("title", "标题")
            ->addTableColumn("right_button", "操作", "btn")
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton("edit",['href'=>U('type_edit',['id'=>'__data_id__'])]) // 添加编辑按钮
            ->addRightButton("forbid",['model' => 'Type']) // 添加禁用/启用按钮
            ->addRightButton("delete",['model' => 'Type']) // 添加删除按钮
            ->display();
    }

    public  function  type_add(){
        $model_object =D('Type');
        if(IS_POST){
            $post = I('post.');
            if (!$data = $model_object->create($post)) {
                $this->error($model_object->getError());
            } else {
                if ($model_object->add($data)) {
                    $this->success('添加成功', U('type_index'));
                } else {
                    trace($model_object->getError());
                    $this->error('添加失败');
                }
            }
        }else{
            $builder = new FormBuilder();
            $builder->setMetaTitle('新增') // 设置页面标题
            ->setPostUrl(U()) // 设置表单提交地址
            ->addFormItem('title','text','分类名称','请输入分类名称')
                ->addFormItem('sort', 'num', '排序', '请输入排序')
                ->display();
        }
    }
    public  function  type_edit($id){
        $model_object =D('Type');
        if(IS_POST){
            $post                 = I('post.');
            if (!$data = $model_object->create($post)) {
                $this->error($model_object->getError());
            } else {
                if (false !== $model_object->where(['id' => $id])->save($data)) {
                    $this->success('更新成功', U('type_index'));
                } else {
                    trace($model_object->getError());
                    $this->error('更新失败');
                }
            }
        }else{
            $builder = new FormBuilder();
            $builder->setMetaTitle('编辑') // 设置页面标题
            ->setPostUrl(U('',['id' => $id])) // 设置表单提交地址
            ->addFormItem('id','hidden','','')
                ->addFormItem('title','text','分类名称','请输入分类名称')
                ->addFormItem('sort', 'num', '排序', '请输入排序')
                ->setFormData($model_object->find($id))
                ->display();
        }
    }
}