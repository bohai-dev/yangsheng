<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Addons\ModelConfigEditor\Model;

use Think\Model;

/**
 * 分类模型
 */
class ModuleModel extends Model
{
    protected $autoCheckFields = false;

    protected $tableName = 'admin_module';

    protected $_validate = array(
        array('name', 'require', '模块名称必须！'), //默认情况下用正则进行验证
        array('icon', 'require', '模块图标必须！'),
        array('name', '', '模块名称已存在！', 0, 'unique', 1, ['type' => 'name']),
        array('title', 'require', '模块标题必须！'), //默认情况下用正则进行验证
        array('title', '', '模块标题已存在！', 0, 'unique', 1, ['type' => 'title']),
    );

    public function build($param)
    {
        if (!is_writeable(APP_PATH)) {
            $this->error = '应用目录【' . APP_PATH . '】不可写，请手动设置权限';
            return false;
        }

        $file        = $this->file();
        $module_name = $param['name'];
        // 创建应用目录
        $module_dir = $file->mk_dir(APP_PATH . '/' . $module_name);
        if ($module_dir) {
            // 创建行为
            $step1        = $this->create_file($module_name, 'behavior', []);
            $php_errormsg = $this->getError();
            if (!$step1) {
                $this->error = '创建行为失败：' . $php_errormsg;
                return false;
            }
            // 创建函数库
            $step2 = $this->create_file($module_name, 'func', []);
            if (!$step2) {
                $this->error = '创建函数库失败：' . $php_errormsg;
                return false;
            }
            // 创建配置
            $step3 = $this->create_file($module_name, 'config', []);
            if (!$step3) {
                $this->error = '创建配置失败：' . $php_errormsg;
                return false;
            }
            // 创建控制器
            $step4 = $this->create_file($module_name, 'controller_front', []);
            if (!$step4) {
                $this->error = '创建前台控制器失败：' . $php_errormsg;
                return false;
            }
            $step5 = $this->create_file($module_name, 'controller_admin', []);
            if (!$step5) {
                $this->error = '创建后台台控制器失败：' . $php_errormsg;
                return false;
            }
            // 创建模型
            $step6 = $this->create_file($module_name, 'model', []);
            if (!$step6) {
                $this->error = '创建模型失败：' . $php_errormsg;
                return false;
            }
            // 创建Sql
            $step7 = $this->create_file($module_name, 'sql', []);
            if (!$step7) {
                $this->error = '创建Sql失败：' . $php_errormsg;
                return false;
            }

            // 创建Sql
            $step7 = $this->create_file($module_name, 'unsql', []);
            if (!$step7) {
                $this->error = '创建Sql失败：' . $php_errormsg;
                return false;
            }

            // 创建opencmf
            $step8 = $this->create_file($module_name, 'opencmf', $param);
            if (!$step8) {
                $this->error = '创建opencmf失败：' . $php_errormsg;
                return false;
            }
            return true;
        } else {
            $this->error = '模块目录无法创建' . $php_errormsg;
            return false;
        }
    }

    public function create_file($module_name, $filename, $variants)
    {
        $module_path = APP_PATH . "{$module_name}";
        $file        = $this->file();
        switch ($filename) {
            case 'behavior':
                $need_dir = "{$module_path}/Behavior";
                if (!is_dir($need_dir)) {
                    $create_dir = $file->mk_dir($need_dir);
                    if (!$create_dir) {
                        $this->error = "创建{$need_dir}失败";
                        return false;
                    }
                }
                $path    = "{$module_path}/Behavior/{$module_name}Behavior.class.php";
                $content = $this->$filename($module_name);
                break;
            case 'func':
                $need_dir = "{$module_path}/Common";
                if (!is_dir($need_dir)) {
                    $create_dir = $file->mk_dir($need_dir);
                    if (!$create_dir) {
                        $this->error = "创建{$need_dir}失败";
                        return false;
                    }
                }
                $path    = "{$module_path}/Common/function.php";
                $content = $this->$filename($module_name);
                break;
            case 'config':
                $need_dir = "{$module_path}/Conf";
                if (!is_dir($need_dir)) {
                    $create_dir = $file->mk_dir($need_dir);
                    if (!$create_dir) {
                        $this->error = "创建{$need_dir}失败";
                        return false;
                    }
                }
                $path    = "{$module_path}/Conf/config.php";
                $content = $this->$filename($module_name);
                break;
            case 'controller_front':
                $need_dir = "{$module_path}/Controller";
                if (!is_dir($need_dir)) {
                    $create_dir = $file->mk_dir($need_dir);
                    if (!$create_dir) {
                        $this->error = "创建{$need_dir}失败";
                        return false;
                    }
                }
                $path    = "{$module_path}/Controller/IndexController.class.php";
                $content = $this->$filename($module_name);
                break;
            case 'controller_admin':
                $need_dir = "{$module_path}/Admin";
                if (!is_dir($need_dir)) {
                    $create_dir = $file->mk_dir($need_dir);
                    if (!$create_dir) {
                        $this->error = "创建{$need_dir}失败";
                        return false;
                    }
                }
                $path    = "{$module_path}/Admin/IndexAdmin.class.php";
                $content = $this->$filename($module_name);
                break;
            case 'model':
                $need_dir = "{$module_path}/Model";
                if (!is_dir($need_dir)) {
                    $create_dir = $file->mk_dir($need_dir);
                    if (!$create_dir) {
                        $this->error = "创建{$need_dir}失败";
                        return false;
                    }
                }
                $path    = "{$module_path}/Model/DefaultModel.class.php";
                $content = $this->$filename($module_name);
                break;
            case 'sql':
                $need_dir = "{$module_path}/Sql";
                if (!is_dir($need_dir)) {
                    $create_dir = $file->mk_dir($need_dir);
                    if (!$create_dir) {
                        $this->error = "创建{$need_dir}失败";
                        return false;
                    }
                }
                $path    = "{$module_path}/Sql/install.sql";
                $content = $this->$filename($module_name);
                break;
            case 'unsql':
                $need_dir = "{$module_path}/Sql";
                if (!is_dir($need_dir)) {
                    $create_dir = $file->mk_dir($need_dir);
                    if (!$create_dir) {
                        $this->error = "创建{$need_dir}失败";
                        return false;
                    }
                }
                $path    = "{$module_path}/Sql/uninstall.sql";
                $content = $this->$filename($module_name);
                break;
            case 'opencmf':
                $path    = "{$module_path}/opencmf.php";
                $content = $this->$filename($module_name, $variants['title'], $variants['icon']);
                break;
            default:
                $this->error = "未支持自动生成的文件名:{$name}";
                return false;
                break;
        }
        if (false === file_put_contents($path, $content)) {
            $this->error = "写入{$path}文件 失败";
            return false;
        } else {
            return true;
        }
    }

    public function behavior($module_name)
    {
        return <<<PHP
<?php
namespace {$module_name}\Behavior;
use Think\Behavior;
use Think\Hook;
defined('THINK_PATH') or exit();
/**
 * 行为扩展
 */
class {$module_name}Behavior extends Behavior
{
    /**
     * 行为扩展的执行入口必须是run
     */
    public function run(&\$content)
    {
        // 行为扩展逻辑
    }
}
PHP;
    }

    public function func()
    {
        return <<<PHP
<?php

PHP;
    }

    public function config()
    {
        return <<<PHP
<?php

return array(

);

PHP;
    }

    public function controller_front($module_name)
    {
        return <<<PHP
<?php
namespace {$module_name}\Controller;

use Think\Controller;

/**
 * 默认控制器
 */
class IndexController extends Controller
{
    /**
     * 默认方法
     */
    public function index()
    {
        $this->show('请自己实现');
    }
}
PHP;
    }

    public function controller_admin($module_name)
    {
        return <<<PHP
<?php
namespace {$module_name}\Admin;

use Admin\Controller\AdminController;
use Common\Util\Think\Page;

/**
 * 默认控制器
 */
class IndexAdmin extends AdminController
{
    /**
     * 默认方法
     */
    public function index()
    {
    	\$data_list = [
    		['id'=>1, 'title'=>'标题1', 'status'=>1],
    		['id'=>2, 'title'=>'标题2', 'status'=>1],
    	];
		\$builder = new \Common\Builder\ListBuilder();
        \$builder->setMetaTitle("列表") // 设置页面标题
            ->addTopButton("addnew") // 添加新增按钮
            ->addTopButton("resume") // 添加启用按钮
            ->addTopButton("forbid") // 添加禁用按钮
            ->setSearch("请输入ID/标题", U("index"))
            ->addTableColumn("id", "ID")
            ->addTableColumn("title", "标题")
            ->addTableColumn("right_button", "操作", "btn")
            ->setTableDataList(\$data_list) // 数据列表
            ->addRightButton("edit") // 添加编辑按钮
            ->addRightButton("forbid") // 添加禁用/启用按钮
            ->addRightButton("delete") // 添加删除按钮
            ->display();
    }
}
PHP;
    }

    public function model($module_name)
    {
        return <<<PHP
<?php

namespace {$module_name}\Model;
use Think\Model;

class DefaultModel extends Model{

}
PHP;
    }

    public function sql($module_name)
    {
        return <<<PHP
# {$module_name}的安装sql
select * from `__ADMIN_USER__`;
PHP;
    }

    public function unsql($module_name)
    {
        return <<<PHP
# {$module_name}的安装sql
select * from `__ADMIN_USER__`;
PHP;
    }

    public function opencmf($module_name, $module_title, $module_icon)
    {
        return <<<PHP
<?php
return array(
    'info'       => array(
        'name'        => '{$module_name}',
        'title'       => '{$module_title}',
        'icon'        => 'fa {$module_icon}',
        'icon_color'  => '#F9B440',
        'description' => '{$module_title}模块',
        'developer'   => '西陆科技',
        'website'     => 'http://lingyun.net',
        'version'     => '1.0.0',
        'dependences' => array(
            'Admin' => '1.0.0',
        ),
    ),
    'user_nav'   => array(
    ),
    'config'     => array(
        'test'               => array(
            'title' => '可添加轮播数',
            'type'  => 'text',
            'value' => '',
        ),
    ),
    'admin_menu' => array(
        1 =>
        array (
          'pid' => '0',
          'title' => '{$module_title}',
          'icon' => 'fa {$module_icon}',
          'id' => 1,
        ),
        2 =>
        array (
          'pid' => '1',
          'title' => '列表',
          'icon' => 'fa fa-folder-open-o',
          'id' => 2,
        ),
        3 =>
        array (
          'id' => '3',
          'pid' => '2',
          'title' => '{$module_title}',
          'url' => '{$module_name}/Index/index',
          'icon' => 'fa {$module_icon}',
        ),
    ),
)
;

PHP;
    }

    public function file()
    {
        if (!$this->file) {
            $file       = new \Common\Util\File();
            $this->file = $file;
            return $file;
        } else {
            return $this->file;
        }
    }

    public function build_fail($module_name)
    {
        $temp_module       = APP_PATH . '/' . $module_name;
        $installed_modules = $this->getField('name', true);
        if (in_array($module_name, $installed_modules)) {
            $this->error = '无法删除已安装的模块';
            return false;
        }
        $file   = $this->file();
        $result = $file->del_dir($temp_module);
        if ($result) {
            return true;
        } else {
            // $this->error = '删除目录失败:' . $php_errormsg;
            return false;
        }
    }

}
