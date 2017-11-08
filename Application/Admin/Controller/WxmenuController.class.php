<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;

use Common\Util\Think\Page;
use EasyWeChat\Foundation\Application;

/**
 * 默认控制器
 * @author jry <598821125@qq.com>
 */
class WxmenuController extends AdminController
{
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index()
    {
        // 获取列表
        $map          = '1';
        $p            = I('p', 1);
        $model_object = D("wxmenu");
        $data_list    = $model_object
            ->page($p, C("ADMIN_PAGE_ROWS"))
            ->where($map)
            ->order("sort ASC,id ASC")
            ->select();
        $page = new Page(
            $model_object->where($map)->count(),
            C("ADMIN_PAGE_ROWS")
        );

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle("列表") // 设置页面标题
            ->addTopButton("addnew") // 添加新增按钮
            ->addTopButton("self", ['title' => '生成菜单', 'href' => U('build'), 'class' => 'btn btn-primary']) // 添加新增按钮
            ->addTableColumn("sort", "排序")
            ->addTableColumn("id", "编号")
            ->addTableColumn("name", "菜单名称")
            ->addTableColumn("url", "内容")
            ->addTableColumn("right_button", "操作管理", "btn")
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton("self", [
                'title' => '添加子菜单',
                'href'  => U('add', ['fid' => '__data_id__']),
            ]) // 添加禁用/启用按钮
            ->addRightButton("edit") // 添加编辑按钮
            ->addRightButton("delete") // 添加删除按钮
            ->display();
    }

    // 添加微信菜单
    public function add($fid = 0)
    {
        if (IS_POST) {
            if (D('wxmenu')->add(I('post.'))) {
                $this->success('添加成功',U('index'));
            } else {
                trace(D('wxmenu')->getError());
                $this->error('添加失败');
            }
        } else {
            $topMenu = D('wxmenu')->order('sort ASC,id ASC')->select();
            if (!$fid) {
                $topMenu = [];
            } else {
                $topMenu = list_sort_by($topMenu, 'id', $sortby = 'asc');
            }
            $topMenu = array_column($topMenu, 'name', 'id');
            // 使用FormBuilder快速建立表单页面
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增') // 设置页面标题
                ->setPostUrl(U('add')) // 设置表单提交地址
                ->addFormItem('fid', 'select', '顶级菜单', '标题', $topMenu)
                ->addFormItem('name', 'text', '输入菜单名称', '')
                ->addFormItem('type', 'select', '类别', '', ['0' => '链接', '1' => '内容'])
                ->addFormItem('url', 'text', '内容', '')
                ->addFormItem('sort', 'num', '排序', '')
                ->setFormData([
                    'sort' => D('wxmenu')->MAX('sort') + 1,
                    'fid'  => $fid,
                ])
                ->setExtraHtml('<script>$("[name=fid] option:contains(\'请选择\')").val(0);</script>')
                ->display();
        }
    }

    // 编辑微信菜单
    public function edit($id)
    {
        if (IS_POST) {
            if (false !== D('wxmenu')->where(['id' => $id])->save(I('post.'))) {
                $this->success('更新成功',U('index'));
            } else {
                $this->error('更新失败');
            }
        } else {
            $row     = D('wxmenu')->find($id);
            $topMenu = D('wxmenu')->order('sort ASC,id ASC')->select();
            if (!$row['fid']) {
                $topMenu = [];
            } else {
                $topMenu = list_sort_by($topMenu, 'id', $sortby = 'asc');
            }
            $topMenu = array_column($topMenu, 'name', 'id');
            // 使用FormBuilder快速建立表单页面
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('fid', 'select', '顶级菜单', '标题', $topMenu)
                ->addFormItem('name', 'text', '输入菜单名称', '')
                ->addFormItem('type', 'select', '类别', '', ['0' => '链接', '1' => '内容'])
                ->addFormItem('url', 'text', '内容', '')
                ->addFormItem('sort', 'num', '排序', '')
                ->addFormItem('id', 'hidden', 'id', '')
                ->setFormData($row)
                ->setExtraHtml('<script>$("[name=fid] option:contains(\'请选择\')").val(0);</script>')
                ->display();
        }
    }

    // 生成微信菜单
    public function build()
    {
        $wx       = [];
        $menus    = D('wxmenu')->order('sort ASC, id ASC')->select();
        $topMenus = list_search($menus, 'fid=0');
        if (!$topMenus) {
            $this->error('没有一级菜单');
        } else {
            foreach ($topMenus as $key => $topMenu) {
                $sub_button  = [];
                $secondMenus = list_search($menus, "fid={$topMenu['id']}");
                if ($secondMenus) {
                    foreach ($secondMenus as $key => $secondMenu) {
                        $sub_button[] = $this->getWxmenuByRow($secondMenu);
                    }
                }
                $wx[] = $this->getWxmenuByRow($topMenu, $sub_button);
            }
        }
        $options = [
            'debug'  => APP_DEBUG,
            'app_id' => C('wechat_appid'),
            'secret' => C('wechat_appsecret'),
            'token'  => C('wechat_apptoken'),
            'log'    => [
                'level' => 'debug',
                'file'  => LOG_PATH . 'easywechat.log', // XXX: 绝对路径！！！！
            ],
        ];

        $app  = new Application($options);
        $menu = $app->menu;
        try {
            $menu->destroy(); // 全部
            try {
                $menu->add($wx);
                $this->success('生成成功');
            } catch (\Exception $e) {
                $this->error('生成失败：' . $e->getMessage());
            }
        } catch (\Exception $e) {
            $this->error('删除失败:' . $e->getMessage());
        }
    }

    //根据微信菜单记录返回正确格式数组
    private function getWxmenuByRow($row, $sub_button = [])
    {
        $field = $row['type'] ? 'key' : 'url';
        return [
            "type"       => $row['type'] ? 'click' : 'view',
            "name"       => $row["name"],
            "sub_button" => $sub_button,
            $field       => $row['url'],
        ];
    }
}
