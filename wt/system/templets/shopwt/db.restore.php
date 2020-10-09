<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
			<div class="subject">
				<h3>
					<?php echo $lang['db_index_db'];?>
				</h3>
				<h5>数据库恢复与备份</h5>
			</div>
		</div>
	</div>
	<!-- 操作说明 -->
	<div class="explanation" id="explanation">
		<div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
			<h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
			<span id="explanationZoom" title="收起提示"></span> </div>
		<ul>
			<li><?php echo $lang['db_import_help1'];?></li>
		</ul>
	</div>
<div class="flexigrid">
	<div class="mDiv">
		<div class="ftitle">
			<h3>备份数据列表</h3>
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
							<div style="text-align: left; width: 150px;"><?php echo $lang['db_index_name'];?></div>
						</th>
						<th axis="col4" class="" align="left">
							<div style="text-align: center; width: 150px;" class=""><?php echo $lang['db_restore_backup_time'];?></div>
						</th>
						<th axis="col5" class="" align="center">
							<div style="text-align: center; width: 150px;" class=""><?php echo $lang['db_restore_backup_size'];?></div>
						</th>
						<th axis="col5" class="" align="center">
							<div style="text-align: center; width: 150px;" class=""><?php echo $lang['db_restore_volumn'];?></div>
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
				<div class="add" title="备份数据"><span><i class="fa fa-plus"></i><a href="index.php?w=db&t=db">备份数据</a></span>
				</div>
			</div>
		</div>
		<div style="clear:both"></div>
	</div>
	<div class="bDiv" style="height: auto;">
		<div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
			<table>
				<tbody>
					
					 <?php if(!empty($output['dir_list']) && is_array($output['dir_list'])){ ?>
        			<?php foreach($output['dir_list'] as $k => $v){ ?>
					
					<tr>
						<td class="sign">
							<div style="width: 24px;"><i class="ico-check"></i>
							</div>
						</td>
						<td class="handle" align="center">
							<div style="text-align: center; width: 60px;">
								<span class="btn"><em><i class="fa fa-cog"></i>操作 <i class="arrow"></i></em><ul>
									<li><a href="javascript:if(confirm('<?php echo $lang['db_index_backup_tip'];?>?')){location.href='index.php?w=db&t=db_import&dir_name=<?php echo $v['name'];?>&step=1'};"><?php echo $lang['db_restore_import'];?></a></li>
									<li><a href="javascript:if(confirm('<?php echo $lang['wt_ensure_del'];?>')){location.href='index.php?w=db&t=db_del&dir_name=<?php echo $v['name'];?>'};"><?php echo $lang['wt_del'];?></a></li></ul></span>
							</div>
						</td>
						<td>
							<div style="text-align: left; width: 150px;"><?php echo $v['name'];?></div>
						</td>
						<td><div style="text-align: center; width: 150px;">
						<?php echo $v['make_time'];?>
							
							</div></td>
						<td >
							<div style="text-align: center; width: 150px;"><?php echo $v['size'];?></div>
						</td>
						
						<td >
							<div style="text-align: center; width: 150px;"><?php echo $v['file_num'];?></div>
						</td>
						<td class="" style="width: 100%;" align="">
							<div>&nbsp;</div>
						</td>
					</tr>
					<?php } ?>
						<?php }else { ?>
						<tr>
						  <td colspan="6" class="no-data"><i class="fa fa-exclamation-bbs"></i><?php echo $lang['wt_no_record'];?></td>
						</tr>
						<?php } ?>
					
				</tbody>
			</table>
		</div>
	</div>
	<!--<div class="pDiv">
			<div class="pagination">
				<?php echo $output['show_page'];?>
			</div>
		<div style="clear:both"></div>
	</div>-->
</div>
</div>