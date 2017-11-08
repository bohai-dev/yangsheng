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
 *秒杀控制器
 */
class SeckillAdmin extends AdminController
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

		$p             = !empty($_GET["p"]) ? $_GET["p"] : 1;
		$map['status'] = array('egt', '0'); // 禁用和正常状态
		$model_object = D('seckill');
		$data_list     = $model_object
			->page($p, C('ADMIN_PAGE_ROWS'))
			->where($map)
			->order('end_time DESC')
			->select();
		$page = new Page(
			$model_object->where($map)->count(),
			C('ADMIN_PAGE_ROWS')
		);

		//自定义按钮
		$rbtn['name']  = 'view';
		$rbtn['title'] = '查看商品';
		$rbtn['class'] = 'label label-success-outline label-pill';
		$rbtn['href']  = U('Shop/Seckill/seckill_index', array('seckill_id' => '__data_id__'));

		// 使用Builder快速建立列表页面。
		$builder = new \Common\Builder\ListBuilder();
		$builder->setMetaTitle('秒杀管理')  // 设置页面标题
			->addTopButton('addnew')        // 添加新增按钮
			->setSearch('请输入ID/标题', U('index'))
			->addTableColumn('id', 'ID')
			->addTableColumn('title', '标题')
			->addTableColumn('start_time', '开始时间')
      ->addTableColumn('end_time', '结束时间')
			->addTableColumn('goods_num', '商品数量')
			// ->addTableColumn('status', '状态', 'status')
			->addTableColumn('right_button', '操作', 'btn')
			->setTableDataList($data_list)  // 数据列表
			->setTableDataPage($page->show()) // 数据列表分页
      ->addRightButton('edit')        // 添加编辑按钮
      // ->addRightButton('forbid')
      ->addRightButton('delete',['href'=>U('delete',['id'=>'__data_id__'])])      // 添加删除按钮
			->addRightButton('self',$rbtn)     // 添加编辑按钮
			->display();
	}

  /**
   * 编辑
   */
  public function add()
  {
    if (IS_POST) {
      $model_object = D('seckill');
      if (!$model_object->create()) {
        $this->error($model_object->getError());
      }

      if ($model_object->add()) {
        $this->success('新增成功', U('index'));
      } else {
        $this->error('新增失败');
      }
    } else {
      // 使用FormBuilder快速建立表单页面。
      $builder = new \Common\Builder\FormBuilder();
      $builder->setMetaTitle('新增') // 设置页面标题
        ->setPostUrl(U('add')) // 设置表单提交地址
        ->addFormItem('id', 'hidden', 'ID', 'ID')
        ->addFormItem('title', 'text', '标题', '请输入活动标题')
        ->addFormItem('start_time','datetime','开始时间','开始时间')
        ->addFormItem('end_time','datetime','结束时间','结束时间')
        ->setFormData(['start_time'=>' ','end_time'=>' '])
        ->display();
    }
  }

	/**
	 * 编辑
	 */
	public function edit($id)
	{
		$model_object = D('seckill');
		if (IS_POST) {
			if (!$model_object->create()) {
				$this->error($model_object->getError());
			}

			if ($model_object->save()) {
				$this->success('更新成功', U('index'));
			} else {
				$this->error('更新失败');
			}
		} else {
			// 使用FormBuilder快速建立表单页面。
			$builder = new \Common\Builder\FormBuilder();
			$builder->setMetaTitle('编辑') // 设置页面标题
				->setPostUrl(U('edit')) // 设置表单提交地址
				->addFormItem('id', 'hidden', 'ID', 'ID')
				->addFormItem('title', 'text', '标题', '请输入活动标题')
				->addFormItem('start_time','datetime','开始时间','开始时间')
				->addFormItem('end_time','datetime','结束时间','结束时间')
				->setFormData($model_object->find($id))
				->display();
		}
	}

  public function delete($id)
  {
      $result = D('seckill')->where(['id'=>$id])->save(['status'=>-1]);
      if ($result) {
          $this->success('删除成功，不可恢复！');
      } else {
          $this->error('删除失败');
      }
  }

	/*
	 *秒杀商品列表
	 */
	public function seckill_index($seckill_id)
	{
		//自定义返回按钮
		$tbtn['name']  = 'back';
    $tbtn['title'] = '返回';
    $tbtn['class'] = 'btn btn-default-outline btn-pill';
    $tbtn['href']  = U('Shop/Seckill/index');

		//搜索
		$keyword         = I('keyword', '', 'string');
		$condition       = array('like', '%' . $keyword . '%');
		$map['oc_shop_seckill_goods.id|oc_shop_goods.title'] = array($condition,$condition, '_multi' => true);


		$map['seckill_id'] = $seckill_id;
		$p             = !empty($_GET["p"]) ? $_GET["p"] : 1;
		$map['oc_shop_seckill_goods.status'] = array('egt', '0'); // 禁用和正常状态
		$model_object = M('shop_seckill_goods');
		$data_list     = $model_object
			->join('oc_shop_goods ON oc_shop_goods.id = oc_shop_seckill_goods.goods_id','LEFT')
			->field('oc_shop_goods.title AS goods_title,
					oc_shop_goods.cover AS goods_cover,
					oc_shop_goods.sale_price AS goods_sale_price,
					oc_shop_seckill_goods.seckill_price,
					oc_shop_seckill_goods.stock,
					oc_shop_seckill_goods.id
					')
			->where($map)
			->page($p, C('ADMIN_PAGE_ROWS'))
			->order('oc_shop_seckill_goods.id DESC')
			->select();
		$page = new Page(
			$model_object->where($map)->join('oc_shop_goods ON oc_shop_goods.id = oc_shop_seckill_goods.goods_id','LEFT')->count(),
			C('ADMIN_PAGE_ROWS')
		);



		// 使用Builder快速建立列表页面。
		$builder = new \Common\Builder\ListBuilder();
		$builder->setMetaTitle('秒杀管理')  // 设置页面标题
			->addTopButton('addnew',['href'=>U('seckill_add',['seckill_id'=>$seckill_id])])
			->addTopButton('self',$tbtn)
			// 添加新增按钮
			->setSearch('请输入ID/商品名称', U('seckill_index',['seckill_id'=>$seckill_id]))
			->addTableColumn('id', 'ID')
			->addTableColumn('goods_title', '商品名称')
			->addTableColumn('goods_cover', '商品封面','picture')
			->addTableColumn('goods_sale_price', '销售价')
			->addTableColumn('seckill_price', '秒杀价')
      ->addTableColumn('stock', '限购数量')
			->addTableColumn('right_button', '操作', 'btn')
			->setTableDataList($data_list)  // 数据列表
			->setTableDataPage($page->show()) // 数据列表分页
			->addRightButton('edit',['href'=>U('seckill_edit',['id'=>'__data_id__'])])        // 添加编辑按钮
			->addRightButton('delete',['href'=>U('seckill_delete',['id'=>'__data_id__','seckill_id'=>$seckill_id])])      // 添加删除按钮
			->display();
	}

	/*
	 *新增秒杀商品
	 */
	public function seckill_add($seckill_id)
	{
		$model_object = D('seckillgoods');
        if (IS_POST) {
        	$post = I('post.');
        	unset($post['type']);
        	$post['seckill_id'] = $seckill_id;
            if (!$model_object->create($post)) {
                $this->error($model_object->getError());
            }
            $model_object->startTrans();
            $result = $model_object->add();
            $res = M('shop_seckill')->execute("UPDATE oc_shop_seckill SET goods_num = goods_num+1 WHERE id=$seckill_id");
            if ($result && $res) {
              $model_object->commit();
              $this->success('新增成功', U('seckill_index',['seckill_id'=>$seckill_id]));
            } else {
              $model_object->rollback();
              $this->error('新增失败');
            }
        } else {
    $list_url = U('goods_list');
    $js = <<< JS
<script>
    $(function(){
        $("select[name='type']").change(function(){
			var type = $(this).val();
			var seckill_id = $seckill_id;
			$.ajax({
				type:'POST',
				url:"$list_url",
				data:{type:type,seckill_id:seckill_id},
				dataType:'json',
				success:function(data){
					if(data.status){
						$(".remove").remove();
						$("select[name='goods_id']").append(data.result);
					}
				}
			})
        });
    })
</script>
JS;
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增') // 设置页面标题
                ->setPostUrl(U('seckill_add')) // 设置表单提交地址
                ->addFormItem('seckill_id', 'hidden', '', '')
                ->addFormItem('type', 'select', '商品类别', '请选择商品类别',$model_object->getType())
                ->addFormItem('goods_id', 'select', '商品', '请选择商品','')
                ->addFormItem('seckill_price', 'text', '秒杀价', '请输入秒杀价')
                ->addFormItem('stock', 'text', '限购数量', '请输入限购数量')
                ->setFormData(['seckill_id'=>$seckill_id])
                ->setExtraHtml($js)
                ->display();
        }
	}

	/*
	 *修改秒杀商品
	 */
	public function seckill_edit($id)
	{
		$model_object = D('seckillgoods');
        if (IS_POST) {
            if (!$data = $model_object->create()) {
                $this->error($model_object->getError());
            }
            if ($model_object->save()) {
                $this->success('更新成功', U('seckill_index',['seckill_id'=>$data['seckill_id']]));
            } else {
                $this->error('更新失败');
            }
        } else {

        	$info = $model_object->find($id);
        	$goods_title = M('shop_goods')->where(['id'=>$info['goods_id']])->getfield('title');
        	$info['goods_title'] = $goods_title;
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑') // 设置页面标题
                ->setPostUrl(U('seckill_edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('seckill_id', 'hidden', '', '')
                ->addFormItem('goods_title', 'static', '商品名称', '')
                 ->addFormItem('seckill_price', 'text', '秒杀价', '请输入秒杀价')
                ->addFormItem('stock', 'text', '限购数量', '请输入限购数量')
                ->setFormData($info)
                ->display();
        }
	}

  /*
   *删除秒杀商品
   */
  public function seckill_delete($id,$seckill_id)
  {
    $model_object = D('seckillgoods');
    $model_object->startTrans();
    $result = $model_object->where(['id'=>$id])->delete();
    $res = M('shop_seckill')->execute("UPDATE oc_shop_seckill SET goods_num = goods_num-1 WHERE id = $seckill_id");
    if ($result&&$res) {
      $model_object->commit();
      $this->success('删除成功，不可恢复！');
    } else {
      $model_object->rollback();
      $this->error('删除失败');
    }
  }

	/*
	 *获取商品列表
	 */
	public function goods_list($type,$seckill_id)
	{
		//排除已经添加的商品
		$info = M('shop_seckill_goods')->where(['seckill_id'=>$seckill_id])->field('goods_id')->select();
		foreach ($info as $key => $value) {
			if($key==0){
				$ids = $value['goods_id'];
			}else{
				$ids .= ','.$value['goods_id'];
			}
		}
    if(!empty($ids)){
  		$map['id'] = ['not in',$ids];
    }
    $map['type'] = $type;
    $map['status']=['gt',0];
		$list = M('shop_goods')->where($map)->field('id,title')->select();

    $result = '';
		foreach ($list as $key => $value) {
			if($key==0){
				$result= "<option class='remove' value='{$value['id']}'>{$value['title']}</option>";
			}else{
				$result .= "<option class='remove' value='{$value['id']}'>{$value['title']}</option>";
			}
		}
		$data['status'] =1;
		$data['result'] =$result;
		$this->ajaxReturn($data);
	}


}
