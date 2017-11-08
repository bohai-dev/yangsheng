<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Cms\Admin;
use Admin\Controller\AdminController;
use Common\Util\Think\Page;
/**
 * 幻灯片控制器
 * @author jry <598821125@qq.com>
 */
class AppAdmin extends AdminController {
	/**
	 * 默认方法
	 * @author jry <598821125@qq.com>
	 */
	public function index() {
		list($data_list, $page, $model_object) = $this->lists('app', '1', 'id DESC');
		  // $attr['name']  = 'top';
		  // $attr['title'] = '置顶';
		  // $attr['class'] = 'label label-primary';
		  // $attr['href']  = U('edit', array('id' => '__data_id__'));

		$builder = new \Common\Builder\ListBuilder();
		 $builder->setMetaTitle('预约列表')  // 设置页面标题
				 ->addTopButton('add')
				 ->addTableColumn('id', 'id')
				 // ->addTableColumn('logo', 'logo')
				 // ->addTableColumn('pic', '图片')
				 ->addTableColumn('name', '姓名')
				 ->addTableColumn('tel', '电话')
				 ->addTableColumn('email', 'email')
				 ->addTableColumn('address', '地址')
				 ->addTableColumn('company', '公司')
				 ->addTableColumn('job', '职位')
				 ->addTableColumn('apkname', '应用名')
				 ->addTableColumn('creat_time', '创建日期')
				 ->addTableColumn('right_button', '操作', 'btn')
				  // ->addRightButton('self', $attr)
				  ->addRightButton('edit')          // 添加编辑按钮
				  // ->addRightButton('forbid')  // 禁用按钮/启用按钮（根据status自动判断）


				  ->setTableDataList($data_list) // 数据列表
				  ->setTableDataPage($page->show()) // 数据列表分页


				  ->display();	
				}
	/**
	 * 编辑文章
	 * @author jry <598821125@qq.com>
	 */
	public function edit($id) {
		if (IS_POST) {
			$model_object = D('app');
			$orignal = $model_object->find($id);

			$data = $model_object->create();
			if ($data) {
				if($orignal['status'] !== $data['status']){
					$data['check_time'] = datetime();
				}
				$id = $model_object->save($data);
				if ($id !== false) {
					$this->success('更新成功', U('index'));

				} else {
					$this->error('更新失败');
				}
			} else {
				$this->error($model_object->getError());
			}	
		} else {	

			 // 使用FormBuilder快速建立表单页面。
			$builder = new \Common\Builder\FormBuilder();
			$builder->setMetaTitle('编辑预约')  // 设置页面标题
					->setPostUrl(U('edit'))     // 设置表单提交地址
					->addFormItem('id', 'hidden', 'ID', 'ID')
					->addFormItem('name','text', '姓名' )
					->addFormItem('tel','text', '电话' )
					->addFormItem('email', 'text', 'email', '')
					->addFormItem('address', 'text', '地址', '')
					->addFormItem('company', 'text', '公司', '')
					->addFormItem('job','text', '职位','' )
					->addFormItem('apkname','text', '应用名','' )
					->addFormItem('logo', 'picture', 'Logo', '')
					->addFormItem('pic', 'pictures', '图片', '')
					->addFormItem('description', 'textarea', '描述', '')
					// ->addFormItem('creat_time', 'datetime', '创建日期', '')
					->addFormItem('is_top', 'radio', '是否置顶','', array('0' => '否','1' => '是'))
					->addFormItem('status', 'radio', '审核状态','', array('-1' => '未审核','0' => '未通过','1' => '已审核'))
					->addFormItem('online', 'radio', '是否下线','', array('0' => '下线','1' => '上线'))

					// ->addFormItem('url', 'text', '链接', '点击跳转链接')
					// ->addFormItem('type', 'radio', '类型', '链接类型', array('1' => '友情链接', '2' => '合作伙伴'))
					// ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
					->setFormData(D('app')->find($id))
					->display();

				}
			}

		}
