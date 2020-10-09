<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
			<div class="subject">
				<h3>
					<?php echo $lang['wechat_url_manage'];?>
				</h3>
				<h5>设置子定义url</h5>
			</div>
		</div>
	</div>
	<!-- 操作说明 -->
	<div class="explanation" id="explanation">
		<div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
			<h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
			<span id="explanationZoom" title="收起提示"></span> </div>
		<ul>
			<li>URL管理主要方便你快递的添加</li>
		</ul>
	</div>
<div class="flexigrid">
	<div class="mDiv">
		<div class="ftitle">
			<h3>URL列表</h3>
		</div>
		<div class="sDiv">
			<div class="sDiv2">
				<form method="get" name="formSearch">
					<input type="hidden" name="w" value="url">
					<input type="hidden" name="t" value="url_manage">
					<select name="fields" class="select">
					  <?php foreach($lang['wechat_url_select_type'] as $f_k=>$f){?>
					  <option value="<?php echo $f_k;?>"<?php echo !empty($_GET['fields']) && $_GET['fields']==$f_k ? ' selected' : '';?>><?php echo $f;?></option>
					  <?php }?>
					<input size="30" name="keywords" id="keywords" class="qsbox"  value="<?php echo empty($_GET['keywords']) ? '' : $_GET['keywords'];?>" placeholder="搜索相关数据..." type="text"> <input class="btn" id="btn" onClick="document.formSearch.submit();" value="搜索" type="button">
					 </form>
			</div>
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
							<div style="text-align: center; width: 150px;">操作</div>
						</th>
						<th axis="col3" class="" align="left">
							<div style="text-align: left; width: 150px;">URL名称</div>
						</th>
						<th abbr="member_id" axis="col4" class="" align="left">
							<div style="text-align: left; width: 120px;" class="">URL地址</div>
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
				<div class="add" title="添加一条新数据到列表"><span><i class="fa fa-plus"></i><a href="index.php?w=url&t=url_add">新增数据</a></span>
				</div>
			</div>
		</div>
		<div style="clear:both"></div>
	</div>
	<div class="bDiv" style="height: auto;">
		<div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
			<table>
				<tbody>
					
					<?php if(!empty($output['url_list']) && is_array($output['url_list'])){ ?>
        			<?php foreach($output['url_list'] as $key => $value){ ?>
					
					<tr>
						<td class="sign">
							<div style="width: 24px;"><i class="ico-check"></i>
							</div>
						</td>
						<td class="handle" align="center">
							<div style="text-align: center; width: 150px;"><a class="btn blue" href="index.php?w=url&t=url_edit&urlid=<?php echo $value['url_id'];?>"><i class="fa fa-pencil-square-o"></i>编辑</a><a class="btn blue" href="javascript:void(0)" onclick="if(confirm('确认删除吗？')){location.href='index.php?w=url&t=url_del&urlid=<?php echo $value['url_id'];?>';}"><i class="fa fa-pencil-square-o"></i>删除</a>
							</div>
						</td>
						<td class="" align="left">
							<div style="text-align: left; width: 150px;"><?php echo $lang['wechat_url_name'];?></div>
						</td>
						<td class="" align="left">
							<div style="text-align: left; width: 120px;"><?php echo $lang['wechat_url_link'];?></div>
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
