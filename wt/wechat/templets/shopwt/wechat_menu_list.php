<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
			<div class="subject">
				<h3>
					<?php echo $lang['wechat_menu_manage'];?>
				</h3>
				<h5>设置微信菜单</h5>
			</div>
		</div>
	</div>
	<!-- 操作说明 -->
	<div class="explanation" id="explanation">
		<div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
			<h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
			<span id="explanationZoom" title="收起提示"></span> </div>
		<ul>
			<li>只能生效一个默认菜单</li>
			<li>如果同步失败，在添加菜单时，如果提示保存失败，请检查填写的内容或链接不能为空或有非法字符</li>
			<li>未认证的订阅号只能使用编辑模式下的自定义菜单功能，认证成功后才能使用自定义菜单的相关接口功能，也就是未认证的不能直接跳转外部链接，如果想让用户跳转到外部链接，只能在微信内部的素材库上传相关的二维码</li>
		</ul>
	</div>
<div class="flexigrid">
	<div class="mDiv">
		<div class="ftitle">
			<h3>自定义菜单列表</h3>
		</div>
	</div>
	<div class="hDiv">
		<div class="hDivBox">
			<table cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<th class="sign" axis="col0">
							<div style="width: 24px;"><i class="ico-check"></i>
							</div>
						</th>
						<th axis="col1" class="handle" align="center">
							<div style="text-align: center; width: 60px;">操作</div>
						</th>
						<th axis="col3" class="" align="left">
							<div style="text-align: left; width: 150px;"><?php echo $lang['wechat_menu_title'];?></div>
						</th>
						<th axis="col4" class="" align="left">
							<div style="text-align: center; width: 150px;" class=""><?php echo $lang['wechat_is_useful'];?></div>
						</th>
						<th axis="col5" class="" align="center">
							<div style="text-align: center; width: 150px;" class=""><?php echo $lang['wechat_updatetime'];?></div>
						</th>
						<th style="width:100%" axis="col8">
							<div></div>
						</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
	<div class="tDiv">
		<div class="tDiv2">
			<div class="fbutton">
				<div class="add" title="添加一条新数据到列表"><span><i class="fa fa-plus"></i><a href="index.php?w=menu&t=menu_add">新增数据</a></span>
				</div>
			</div>
		</div>
		<div style="clear:both"></div>
	</div>
	<div class="bDiv" style="height: auto;">
		<div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
			<table>
				<tbody>
					
					<?php if(!empty($output['menu_list']) && is_array($output['menu_list'])){ ?>
					<?php foreach($output['menu_list'] as $key => $value){ ?>
					
					<tr>
						<td class="sign">
							<div style="width: 24px;"><i class="ico-check"></i>
							</div>
						</td>
						<td class="handle" align="center">
							<div style="text-align: center; width: 60px;">
								<span class="btn"><em><i class="fa fa-cog"></i>操作 <i class="arrow"></i></em><ul>
									<?php if($value['menu_status']==0){?>
									<li><a href="index.php?w=menu&t=menu_publish&mid=<?php echo $value['menu_id'];?>">同步</a></li>
									<?php }?><li><a href="index.php?w=menu&t=menu_edit&mid=<?php echo $value['menu_id'];?>">编辑</a></li><li><a href="javascript:void(0);" onclick="if(confirm('您确定要删除吗？')){location.href='index.php?w=menu&t=menu_del&mid=<?php echo $value['menu_id'];?>';}">删除</a></li></ul></span>
							</div>
						</td>
						<td>
							<div style="text-align: left; width: 150px;"><?php echo trim($value['menu_name']);?></div>
						</td>
						<td><div style="text-align: center; width: 150px;">
						<?php echo $value['menu_status']==0 ? '<span class="no"><i class="fa fa-ban"></i>微信未生效</span>' : '<span class="yes"><i class="fa fa-check-bbs"></i>微信已同步</span>';?></div></td>
						<td >
							<div style="text-align: center; width: 150px;"><?php echo date('Y-m-d H:i:s',$value['menu_addtime']);?></div>
						</td>
						<td class="" style="width: 100%;" align="">
							<div>&nbsp;</div>
						</td>
					</tr>
					<?php } ?>
						<?php }else { ?>
						<tr>
						  <td colspan="6" class="no-data"><i class="fa fa-exclamation-bbs"></i>没有符合条件的记录</td>
						</tr>
						<?php } ?>
					
				</tbody>
			</table>
		</div>
	</div>
	<div class="pDiv">
			<div class="pagination">
				<?php echo $output['show_page'];?>
			</div>
		<div style="clear:both"></div>
	</div>
</div>
</div>
