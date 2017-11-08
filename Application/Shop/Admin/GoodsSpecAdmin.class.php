<?php
namespace Shop\Admin;

use Admin\Controller\AdminController;
use Common\Builder\ListBuilder;
use Common\Builder\FormBuilder;

/**
*
*/
class GoodsSpecAdmin extends AdminController
{

	public function index()
	{
		$map = [];

		list($data_list, $page, $model) = $this->lists('GoodsSpec', $map,'type_id DESC ,id DESC');
		// var_dump($data_list);die;
		$builder = new ListBuilder();
		$builder->setMetaTitle('规格列表')
				->addTopButton('addnew')
            	->addTableColumn('id', 'ID')
            	->addTableColumn('type_name', '分类')
            	->addTableColumn('name', '规格')
            	->addTableColumn('right_button', '操作', 'btn')
				->setTableDataList($data_list)
				->setTableDataPage($page->show())
				->addRightButton('edit')
            	->addRightButton('self', [
            								'title' => '管理规格项',
            								'class' => 'label label-primary-outline label-pill',
            								'href'  => U('items', ['spec_id'=>'__data_id__']),
            							 ]
            					)
            	->addRightButton('delete')
				->display();

	}

	public function add()
	{
		if(IS_POST){
			$model = D('GoodsSpec');
			$data = $model->create();
			if($data){
				$id = $model->add($data);
				if($id!==false){
					 $this->success('新增成功', U('index'));
				}else{
					$this->error('新增失败');
				}
			}else{
				$this->error($model->getError());
			}
		}else{
			$goods_types = M('shop_goodstype')->where(['status'=>1,'check'=>0])->getField('id,title');

			$builder = new  FormBuilder();
			$builder->setMetaTitle('新增')
					->addFormItem('type_id', 'select', '所属分类', '', $goods_types)
					->addFormItem('name', 'text', '规格','')
					->display();
		}
	}

	public function edit($id)
	{
		$model = D('GoodsSpec');
		if(IS_POST){
			$model = D('GoodsSpec');
			$data = $model->create();
			if($data){
				$result = $model->where(['id'=>$id])->save($data);
				if($result!==false){
					 $this->success('修改成功', U('index'));
				}else{
					$this->error('修改失败');
				}
			}else{
				$this->error($model->getError());
			}
		}else{
			$info = $model->find($id);
			$goods_types = M('shop_goodstype')->where(['status'=>1,'check'=>0])->getField('id,title');
			$builder = new  FormBuilder();
			$builder->setMetaTitle('編輯')
					->addFormItem('type_id', 'select', '所属分类', '', $goods_types)
					->addFormItem('name', 'text', '规格','')
					->setFormData($info)
					->display();
		}
	}

	public function items($spec_id)
	{

		$map = ['spec_id'=>$spec_id];

		list($data_list, $page, $model) = $this->lists('SpecItem', $map);

		$builder = new ListBuilder();
		$builder->setMetaTitle('规格项列表')
				->addTopButton('addnew', ['href'=>U('items_add', ['spec_id'=>$spec_id])])
            	->addTableColumn('id', 'ID')
            	->addTableColumn('item', '规格值')
            	->addTableColumn('right_button', '操作', 'btn')
				->setTableDataList($data_list)
				->setTableDataPage($page->show())
				->addRightButton('edit', ['href'=>U('items_edit', ['id'=>'__data_id__'])])
				->addRightButton('delete', ['model' => 'spec_item'])
				->display();

	}

	public function items_add($spec_id)
	{
		if(IS_POST){
			$model = D('SpecItem');
			$data = $model->create();
			if($data){
				$id = $model->add($data);
				if($id!==false){
					 $this->success('新增成功', U('items', ['spec_id'=>$data['spec_id']]));
				}else{
					$this->error('新增失败');
				}
			}else{
				$this->error($model->getError());
			}
		}else{
			$builder = new  FormBuilder();
			$builder->setMetaTitle('新增')
                	->addFormItem('item', 'text', '规格值', '')
                	->addFormItem('spec_id', 'hidden', '', '')
                	->setFormData(['spec_id' => $spec_id])
					->display();
		}
	}

	public function items_edit($id)
	{
		$model = D('SpecItem');
		if(IS_POST){
			$data = $model->create();
			if($data){
				$id = $model->where(['id'=>$id])->save($data);
				if($id!==false){
					 $this->success('修改成功', U('items', ['spec_id'=>$data['spec_id']]));
				}else{
					$this->error('修改失败');
				}
			}else{
				$this->error($model->getError());
			}
		}else{
			$info = $model->find($id);
			$builder = new  FormBuilder();
			$builder->setMetaTitle('编辑')
                	->addFormItem('id', 'hidden', 'ID', '')
                	->addFormItem('item', 'text', '规格值', '')
                	->addFormItem('spec_id', 'hidden', '', '')
                	->setFormData($info)
					->display();
		}
	}
}
