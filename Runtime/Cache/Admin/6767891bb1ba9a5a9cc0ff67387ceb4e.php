<?php if (!defined('THINK_PATH')) exit();?><div class="col-xs-12 col-sm-6 col-lg-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<i class="fa fa-cog"></i> 模块列表
			<a href="<?php echo addons_url('ModelConfigEditor://ModelConfigEditor/addModule');?>" class="btn btn-link">新增模块</a>
		</div>
		<div class="panel-body">
			<div class="col-xs-12">
				<div class="builder-table">
					<div class="panel panel-default table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th>名称</th>
									<th>标题</th>
									<th>描述</th>
									<th>版本</th>
									<th>状态</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
								<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
									<td><?php echo ($vo["name"]); ?></td>
									<td><?php echo ($vo["title"]); ?></td>
									<td><?php echo ($vo["description"]); ?></td>
									<td><?php echo ($vo["version"]); ?></td>
									<td>
										<i class="fa <?php if(($vo["status"]) == "1"): ?>fa-check text-success<?php else: ?>fa-ban text-danger<?php endif; ?>"></i>
									</td>
									<td>
										<?php if(isset($vo['id'])): ?><a class="label label-info" href="<?php echo addons_url('ModelConfigEditor://ModelConfigEditor/menus', ['id'=>$addon_id, 'module_id'=>$vo['id']]);?>">编辑菜单</a>
											<?php else: ?>
											<a class="label label-info" href="<?php echo addons_url('ModelConfigEditor://ModelConfigEditor/menus', ['id'=>$addon_id]);?>">编辑菜单</a><?php endif; ?>

									</td>
								</tr><?php endforeach; endif; else: echo "" ;endif; ?>
								</tbody>
							</table>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>