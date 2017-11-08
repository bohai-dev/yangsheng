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

/**
 *优惠券控制器
 */
class GoodsAdmin extends AdminController
{
    /**
     * 默认方法
     */
    public function index()
    {
        // 搜索
        $keyword         = I('keyword', '', 'string');
        $condition       = array('like', '%' . $keyword . '%');
        $map['id|title'] = array($condition, $condition, '_multi' => true);

        //头部导航
        $group = I('group',1);
        $map['group'] = $group;
        $tab_list = array(
            '1' => array(
                'title' => '普通商品',
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
                'title' => '积分商品',
                'href'   => U('index', array('group' => 4)),
            )
        );


        //评论 按钮
        $rbtn['name']  = 'view';
        $rbtn['title'] = '查看评论';
        $rbtn['class'] = 'label label-success-outline label-pill';
        $rbtn['href']  = U('Shop/Goods/review', array('id' => '__data_id__','group'=>$group));

        //数据
        $p             = I('p',1);
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $model_object = D('goods');
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
        $builder->setMetaTitle('首页轮播') // 设置页面标题
            ->addTopButton('addnew',['href'=>U('add',['group'=>$group])]) // 添加新增按钮
            ->setSearch('请输入ID/名称', U('index',['group'=>$group]))
            ->setTabNav($tab_list, $group)
            ->addTableColumn('id', 'ID')
            ->addTableColumn('type', '类别','callback',array($model_object,'getTypeName'))
            ->addTableColumn('title', '名称','callback',[$model_object,'cutTitle'])
            ->addTableColumn('cover', '封面','picture')
            ->addTableColumn('original_price', '原价')
            ->addTableColumn('sale_price', '销售价')
            ->addTableColumn('postage', '运费');
         if($group ==4){
            $builder->addTableColumn('sale_integral', '购买积分')
                    ->addTableColumn('integral_price', '购买价格');
        }
        $builder->addTableColumn('back_integral', '赠送积分')
            // ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('forbid')  // 添加启用/禁用按钮
            ->addRightButton('edit')    // 添加编辑按钮
            ->addRightButton('delete',['href'=>U('delete',['id'=>'__data_id__'])])  // 添加删除按钮
            ->addRightButton('self',$rbtn)    // 添加评论按钮
            ->display();
    }

    /**
     * 新增
     */
    public function add($group=1)
    {
        $model_object = D('goods');
        $post = I('post.');
        if (IS_POST) {
            if (!$data = $model_object->create()) {
                $this->error($model_object->getError());
            }
            $id =$model_object->add();
            if ($id) {
                $model_object->setSpec($id, $post);
                $this->success('新增成功', U('index',['group'=>$group]));
            } else {
                $this->error('新增失败');
            }
        } else {
    $js = <<< JS
<script type="text/javascript" src="./Application/Common/Builder/goods.js"></script>
<script>
    $(function(){
        $(".item_ad_b_sort").hide();
        $("input[name='ad_b']").click(function(){
            var status = $(this).val();
            if(status==1){
                $(".item_ad_b_sort").show();
            }else if(status==0){
                $(".item_ad_b_sort").hide();
            }
        })
    })
</script>
JS;
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增') // 设置页面标题
                ->setPostUrl(U('add')) // 设置表单提交地址
                ->addFormItem('group', 'hidden', '', '')
                ->addFormItem('type', 'select', '类别', '',$model_object->getType($group))
                ->addFormItem('spec', 'spec', '规格','')
                ->addFormItem('title', 'text', '名称', '请输入商品名称')
                ->addFormItem('subtitle', 'text', '描述', '请输入商品描述')
                ->addFormItem('cover', 'picture', '封面', '推荐尺寸：640*427')
                ->addFormItem('images', 'pictures', '商品多图', '请上传商品多图','')
                ->addFormItem('original_price', 'text', '商品原价', '请输入商品原价')
                ->addFormItem('sale_price', 'text', '销售价格', '请输入销售价格')
                ->addFormItem('collect', 'text', '收藏量', '请输入收藏量')
                ->addFormItem('sales_volume', 'text', '销售量', '请输入销售量');
            switch ($group) {
                case '1':
                    $builder
                        ->addFormItem('postage', 'text', '运费', '请输入运费金额')
                        ->addFormItem('explain', 'textarea', '购买说明', '请输入购买说明')
                        ->addFormItem('back_integral', 'text', '增送积分', '请输入增送积分')
                        ->addFormItem('description', 'kindeditor', '商品介绍', '')
                        ->addFormItem('spec', 'kindeditor', '规格参数', '')
                        ->addFormItem('aftersale', 'kindeditor', '售后保障', '')
                        ->addFormItem('property','checkbox','栏目(可不选)','',$model_object->getColumns())
                        ->addFormItem('ad_a','checkbox','广告1(可不选)','',[1=>'一栏',2=>'二栏',3=>'三栏',4=>'四栏'])
                        ->addFormItem('ad_b','radio','广告2','',[0=>'否',1=>'是'])
                        ->addFormItem('ad_b_sort','text','广告2排序','请输入整数广告2排序',[0=>'是',1=>'否']);
                    break;
                case '2':
                    $builder
                        ->addFormItem('postage', 'text', '运费', '请输入运费金额')
                        ->addFormItem('explain', 'textarea', '购买说明', '请输入购买说明')
                        ->addFormItem('back_integral', 'text', '增送积分', '请输入增送积分')
                        ->addFormItem('description', 'kindeditor', '商品介绍', '')
                        ->addFormItem('spec', 'kindeditor', '规格参数', '')
                        ->addFormItem('aftersale', 'kindeditor', '售后保障', '')
                        ->addFormItem('columns','checkbox','推荐(可不选)','',[1=>'一栏',5=>'二栏1(页内)',4=>'二栏2(页内)',3=>'三栏']);

                    break;
                case '3':
                    $builder
                        ->addFormItem('postage', 'text', '运费', '请输入运费金额')
                        ->addFormItem('explain', 'textarea', '购买说明', '请输入购买说明')
                        ->addFormItem('back_integral', 'text', '增送积分', '请输入增送积分')
                        ->addFormItem('description', 'kindeditor', '商品介绍', '')
                        ->addFormItem('spec', 'kindeditor', '规格参数', '')
                        ->addFormItem('aftersale', 'kindeditor', '售后保障', '')
                        ->addFormItem('columns','checkbox','推荐(可不选)','',[1=>'一栏',2=>'二栏',3=>'三栏']);

                    break;
                //积分商品
                case '4':
                    $builder
                    ->addFormItem('sale_integral', 'text', '购买积分', '请输入购买积分')
                    ->addFormItem('integral_price', 'text', '购买价格','请输入购买价格')
                    ->addFormItem('back_integral', 'text', '增送积分', '请输入增送积分')
                    ->addFormItem('description', 'kindeditor', '商品介绍', '')
                    ->addFormItem('spec', 'kindeditor', '规格参数', '')
                    ->addFormItem('aftersale', 'kindeditor', '售后保障', '')
                    ->addFormItem('columns','checkbox','推荐(可不选)','',[1=>'一栏',3=>'三栏',4=>'首页']);
                    break;
                default:
                    # code...
                    break;
            }
            $builder->addFormItem('status','radio','状态','',[0=>'禁用',1=>'启用'])
                ->setFormData(['group'=>$group])
                ->setExtraHtml($js)
                ->display();
        }
    }

    /**
     * 编辑
     */
    public function edit($id)
    {
        $model_object = D('goods');
        if (IS_POST) {
            $post =I('post.');
            if (!$data = $model_object->create()) {
                $this->error($model_object->getError());
            }
            if ($model_object->save()!==false) {
                $model_object->setSpec($id, $post);
                $this->success('更新成功', U('index',['group'=>$data['group']]));
            } else {
                $this->error('更新失败');
            }
        } else {
    $js = <<< JS
<script type="text/javascript" src="./Application/Common/Builder/goods.js"></script>
<script>
    $(function(){
        var check = $("input[name='ad_b']:checked").val();
        if(check ==0){
            $(".item_ad_b_sort").hide();
        }
        $("input[name='ad_b']").click(function(){
            var status = $(this).val();
            if(status==1){
                $(".item_ad_b_sort").show();
            }else if(status==0){
                $(".item_ad_b_sort").hide();
            }
        })
    })
</script>
JS;
            $info = $model_object->find($id);
            $group = $info['group'];
            // 分类规格
            $spec_list = D('GoodsSpec')->getSpec(['type_id'=>$info['type']]);
            $this->assign('spec_list', $spec_list);
            // 商品规格值
            $goods_spec  = M('SpecGoodsPrice')->where(['goods_id'=>$info['id']])->select();
            $this->assign('goods_spec', $goods_spec);
            // 商品规格
            $item_ids_str = implode('_', array_column($goods_spec, 'key'));
            $item_ids = $item_ids_str ? array_unique(explode('_', $item_ids_str)) : [];
            $this->assign('item_ids', $item_ids);
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('group', 'hidden', '', '')
                ->addFormItem('type', 'select', '类别', '请选择商品类别',$model_object->getType($group))
                ->addFormItem('spec', 'spec', '规格','')
                ->addFormItem('title', 'text', '名称', '请输入商品名称')
                ->addFormItem('subtitle', 'text', '描述', '请输入商品描述')
                ->addFormItem('cover', 'picture', '封面', '请上传商品封面')
                ->addFormItem('images', 'pictures', '商品多图', '请上传商品多图')
                ->addFormItem('original_price', 'text', '商品原价', '请输入商品原价')
                ->addFormItem('sale_price', 'text', '销售价格', '请输入销售价格')
                ->addFormItem('collect', 'text', '收藏量', '请输入收藏量')
                ->addFormItem('sales_volume', 'text', '销售量', '请输入销售量');
            switch ($group) {
                case '1':
                    $builder
                        ->addFormItem('postage', 'text', '运费', '请输入运费金额')
                        ->addFormItem('explain', 'textarea', '购买说明', '请输入购买说明')
                        ->addFormItem('back_integral', 'text', '增送积分', '请输入增送积分')
                        ->addFormItem('description', 'kindeditor', '商品介绍', '')
                        ->addFormItem('spec', 'kindeditor', '规格参数', '')
                        ->addFormItem('aftersale', 'kindeditor', '售后保障', '')
                        ->addFormItem('property','checkbox','栏目(可不选)','',$model_object->getColumns())
                        ->addFormItem('ad_a','checkbox','广告1(可不选)','',[1=>'一栏',2=>'二栏',3=>'三栏',4=>'四栏'])
                        ->addFormItem('ad_b','radio','广告2','',[0=>'否',1=>'是'])
                        ->addFormItem('ad_b_sort','text','广告2排序','请输入整数广告2排序',[0=>'是',1=>'否']);
                    break;
                case '2':
                    $builder
                        ->addFormItem('postage', 'text', '运费', '请输入运费金额')
                        ->addFormItem('explain', 'textarea', '购买说明', '请输入购买说明')
                        ->addFormItem('back_integral', 'text', '增送积分', '请输入增送积分')
                        ->addFormItem('description', 'kindeditor', '商品介绍', '')
                        ->addFormItem('spec', 'kindeditor', '规格参数', '')
                        ->addFormItem('aftersale', 'kindeditor', '售后保障', '')
                        ->addFormItem('columns','checkbox','推荐(可不选)','',[1=>'一栏',5=>'二栏1(页内)',4=>'二栏2(页内)',3=>'三栏']);

                    break;
                case '3':
                    $builder
                        ->addFormItem('postage', 'text', '运费', '请输入运费金额')
                        ->addFormItem('explain', 'textarea', '购买说明', '请输入购买说明')
                        ->addFormItem('back_integral', 'text', '增送积分', '请输入增送积分')
                        ->addFormItem('description', 'kindeditor', '商品介绍', '')
                        ->addFormItem('spec', 'kindeditor', '规格参数', '')
                        ->addFormItem('aftersale', 'kindeditor', '售后保障', '')
                        ->addFormItem('columns','checkbox','推荐(可不选)','',[1=>'一栏',2=>'二栏',3=>'三栏']);

                    break;
                //积分商品
                case '4':
                    $builder
                    ->addFormItem('postage', 'text', '运费', '请输入运费金额')
                    ->addFormItem('sale_integral', 'text', '购买积分', '请输入购买积分')
                    ->addFormItem('integral_price', 'text', '购买价格','请输入购买价格')
                    ->addFormItem('back_integral', 'text', '增送积分', '请输入增送积分')
                    ->addFormItem('description', 'kindeditor', '商品介绍', '')
                    ->addFormItem('spec', 'kindeditor', '规格参数', '')
                    ->addFormItem('aftersale', 'kindeditor', '售后保障', '')
                    ->addFormItem('columns','checkbox','推荐(可不选)','',[1=>'一栏',3=>'三栏',4=>'首页']);
                    break;
                default:
                    # code...
                    break;
            }

            $builder->addFormItem('coupons','checkbox','优惠券(可不选)','',$model_object->getCoupons())
                ->addFormItem('status','radio','状态','',[0=>'禁用',1=>'启用'])
                ->setFormData($info)
                ->setExtraHtml($js)
                ->display();
        }
    }

    public function getspec()
    {
        if($type_id = I('type_id')){
            $spec = D('GoodsSpec')->getSpec(['type_id'=>$type_id]);
            $this->ajaxReturn(['status'=>1, 'spec'=>$spec]);
        }
        $this->ajaxReturn(['status'=>0]);
    }
    /*
     * 假删除
     */
    public function delete($id)
    {
        $goods_object = D('goods');
        $goods_object->startTrans();
        $result = $goods_object->where(['id'=>$id])->save(['status'=>-1]);


        //删除秒杀商品
        $sec_goods_info = M('shop_seckill_goods')
                            ->field('id,seckill_id')
                            ->where(['goods_id'=>$id])
                            ->select();
        if(empty($sec_goods_info)){
            $goods_object ->commit();
            $this->success('删除成功，不可恢复！');
        }else{
            $sec_goods_res = M('shop_seckill_goods')->where(['goods_id'=>$id])->save(['status'=>'-1']);
            foreach ($sec_goods_info as $key => $value) {
                $sec_ids[]=$value['seckill_id'];
            }

            $sec_res = M('shop_seckill')->where(['id'=>['IN',$sec_ids]])->setDec('goods_num',1);
        }


        if ($result && $sec_res && $sec_goods_res) {
            $goods_object ->commit();
            $this->success('删除成功，不可恢复！');
        } else {
            $goods_object ->rollback();
            $this->error('删除失败');
        }
    }


    public function  review($id)
    {
        $id = I('id','');
        $id?:exit;
        $group = I('group','1');
        // 搜索
        $keyword                                  = I('keyword', '', 'string');
        $condition                                = array('like', '%' . $keyword . '%');
        $map['oc_shop_goods_review.id|oc_user.nickname'] = array(
            $condition,
            $condition,
            '_multi' => true,
        );

        //回复评论 按钮
        $rbtn['name']  = 'view';
        $rbtn['title'] = '回复';
        $rbtn['class'] = 'label label-primary-outline label-pill';
        $rbtn['href']  = U('Shop/Goods/reply', array('id' => '__data_id__'));

        //头部返回按钮
        $tbtn['name']  = 'go-back';
        $tbtn['title'] = '返回';
        $tbtn['class'] = 'btn btn-default-outline btn-pill';
        $tbtn['href']  = U('Shop/Goods/index',['group',$group]);

        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $map['oc_shop_goods_review.gid'] = $id;
        $review_obejct   = M('shop_goods_review');
        $data_list     = $review_obejct
            ->join('oc_user ON oc_user.admin_uid = oc_shop_goods_review.uid','LEFT')
            ->field('oc_shop_goods_review.id,
                     oc_shop_goods_review.content,
                     oc_shop_goods_review.rating,
                     oc_shop_goods_review.reply,
                     oc_shop_goods_review.create_time,
                     oc_shop_goods_review.status,
                     oc_user.nickname,
                     oc_user.headimgurl
                     ')
            ->where($map)
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->order('id DESC')
            ->select();
        $page = new Page(
            $review_obejct
            ->join('oc_user ON oc_user.admin_uid = oc_shop_goods_review.uid','LEFT')
            ->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );
       $bulletin_model =  D('Shop/Bulletin');
        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('评论列表') // 设置页面标题
            ->setSearch('ID/评论人姓名',U('',['id'=>$id]))
            ->addTopButton('self',$tbtn)
            ->addTableColumn('id', 'ID')
            ->addTableColumn('nickname', '评论人')
            ->addTableColumn('headimgurl', '头像','callback',[$bulletin_model,'getImg'])
            ->addTableColumn('content', '内容')
            ->addTableColumn('rating', '评分')
            ->addTableColumn('reply', '回复')
            ->addTableColumn('create_time', '评论时间')
            // ->addTableColumn('status', '状态','status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('forbid',['model'=>'shop_goods_review']) // 添加禁用/启用按钮
            ->addRightButton('self',$rbtn) // 添加禁用/启用按钮
            ->addRightButton('delete',['model'=>'shop_goods_review']) // 添加删除按钮
            ->display();
    }


    public function  reply($id)
    {
        $model_object = M('shop_goods_review');
        if (IS_POST) {
            if ($data = $model_object->create()) {
                if ($model_object->save()) {
                    $this->success('更新成功', U('review',['id'=>$data['gid']]));
                } else {
                    $this->error('更新失败', $model_object->getError());
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
                    ->addFormItem('gid', 'hidden', '', '')
                    ->addFormItem('rating', 'static', '评分', '')
                    ->addFormItem('content', 'static', '内容', '')
                    ->addFormItem('reply', 'textarea', '回复', '')
                    ->setFormData($info)
                    ->display();
        }
    }
}
