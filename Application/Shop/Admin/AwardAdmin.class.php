<?php
/**
 * Created by PhpStorm.
 * User: 水目
 * Date: 2017/3/16 0016
 * Time: 15:20
 */
namespace Shop\Admin;

use Admin\Controller\AdminController;
use Common\Util\Think\Page;

/**
 *抽奖控制器 可删
 */
class AwardAdmin extends AdminController
{
    // 获奖记录
    public  function  index(){
        // 搜索
        $keyword = I('keyword', '', 'string');
        // 奖品搜索
        if ($type  = I('award_type', '')) {
            if($type==1){
                $type=1;
            }else{
                $type=2;
            }
            $award_id =M('shop_award')->where(['type'=>$type])->getField('id',true);
            $map['award_id'] =['in',$award_id];
        }
        $p             = !empty($_GET["p"]) ? $_GET["p"] : 1;
        $map['status'] = array('egt', '0');  // 禁用和正常状态
        $post_object = D('shop_award_record');
        $data_list = $post_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('id desc')
            ->select();
        $page = new Page(
            $post_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面。
        $builder =new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('获奖记录')  // 设置页面标题
        ->addSearchItem('award_type', 'select', '奖品分类', '', ['' => '全部'] + [1=>'虚拟奖品',2=>'实体奖品'])
            ->addTableColumn('id', 'ID')
            ->addTableColumn('uid', '用户名','callback','get_username')
            ->addTableColumn('award_id', '奖品名称','callback',array(D('Award'),'get_award_name'))
            ->addTableColumn('time', '中奖时间')
            ->setTableDataList($data_list)     // 数据列表
            ->setTableDataPage($page->show())  // 数据列表分页
            ->display();
    }

    //奖品设置
    public  function  award(){
        // 搜索
        $keyword = I('keyword', '', 'string');
        $condition = array('like','%'.$keyword.'%');
        $map['id|title'] = array($condition, $condition,'_multi'=>true);

        // 获取所有分类
        $p             = !empty($_GET["p"]) ? $_GET["p"] : 1;
        $map['status'] = array('egt', '0');  // 禁用和正常状态
        $post_object = D('Shop/Award');
        $data_list = $post_object
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('sort desc,id desc')
            ->select();
        $page = new Page(
            $post_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面。
        $builder =new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('奖品列表')  // 设置页面标题
        ->addTopButton('addnew',['href'=>U('Award/award_add')]) // 添加新增按钮
        ->setSearch('请输入ID/奖品名称', U('award'))
            ->addTableColumn('id', 'ID')
            ->addTableColumn('cover', '奖品图片', 'picture')
            ->addTableColumn('title', '奖品标题')
            ->addTableColumn('sort', '排序')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list)     // 数据列表
            ->setTableDataPage($page->show())  // 数据列表分页
            ->addRightButton('edit',['href'=>U('award_edit', ['id'=>'__data_id__'])])           // 添加编辑按钮
            ->addRightButton('forbid')  // 添加禁用/启用按钮
            ->addRightButton('goods_recycle')  // 添加删除按钮
            ->display();
    }

    public  function  award_add(){
        if (IS_POST) {
            $model_object = D('Shop/Award');
            $post =I('post.');
            if($post['type']==1){
                if(empty($post['score'])){
                    $this->error('请填写积分');
                }
            }else{
                unset($post['score']);
            }
            if ($model_object->create()) {
                if ($model_object->add()) {
                    $this->success('新增成功', U('award'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($model_object->getError());
            }
        } else {
            $js = <<<JS
<script>
$(function(){
    $('.item_score').hide();
    $('[name="type"]').on('change',function(){
       var _this =$(this);
       if(_this.val()==1){
        $('.item_score').show();
       }else{
        $('.item_score').hide();
       }
    })
})
</script>
JS;
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增') //设置页面标题
            ->setPostUrl(U('award_add'))    //设置表单提交地址
            ->addFormItem('type','select','分类','',[1=>'虚拟物品','2'=>'实体物品'])
            ->addFormItem('title', 'text', '奖品名称', '奖品名称')
                ->addFormItem('cover', 'picture', '奖品图片', '奖品图片','')
                ->addFormItem('score', 'num', '请填写积分', '纯数字')
                ->addFormItem('rate','num','中奖概率','0-100之间')
                ->addFormItem('sort','num','排序','排序')
                ->setExtraHtml($js)
                ->display();

            // ,['self'=>['limit'=>3]]
        }
    }

    public  function  award_edit($id){
        if (IS_POST) {
            $model_object = D('Shop/Award');
            $data =  $model_object ->create(format_data());
            $post =I('post.');
            if($post['type']==1){
                if(empty($post['score'])){
                    $this->error('请填写积分');
                }
            }else{
                unset($post['score']);
            }
            if ($data) {
                $id =  $model_object ->save();
                if ($id !== false) {
                    $this->success('更新成功', U('award'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($model_object ->getError());
            }
        } else {
            $js = <<<JS
<script>
$(function(){
    if($('[name="type"]').val()==1){
        $('.item_score').show();
    }else{
        $('.item_score').hide();
    }
    $('[name="type"]').on('change',function(){
       var _this =$(this);
       if(_this.val()==1){
        $('.item_score').show();
       }else{
        $('.item_score').hide();
       }
    })
})
</script>
JS;
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑')  // 设置页面标题
            ->setPostUrl(U('award_edit'))     // 设置表单提交地址
            ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->addFormItem('type','select','分类','',[1=>'虚拟物品','2'=>'实体物品'])
            ->addFormItem('title', 'text', '奖品名称', '奖品名称')
                ->addFormItem('cover', 'picture', '奖品图片', '奖品图片','')
                ->addFormItem('score', 'num', '请填写积分', '纯数字')
                ->addFormItem('rate','num','中奖概率','0-100之间')
                ->addFormItem('sort','num','排序','排序')
                ->setFormData(D('Shop/Award')->find($id))
                ->setExtraHtml($js)
                ->display();
        }
    }
}