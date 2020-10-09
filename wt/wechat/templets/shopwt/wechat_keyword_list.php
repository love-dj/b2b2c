<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
			<div class="subject">
				<h3>
					<?php echo $lang['wt_wechat_keywords'];?>
				</h3>
				<h5>设置关键词</h5>
			</div>
		</div>
	</div>
	<!-- 操作说明 -->
	<div class="explanation" id="explanation">
		<div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
			<h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
			<span id="explanationZoom" title="收起提示"></span> </div>
		<ul>
			<li>设置关键字主要对用户在回复内容里自动匹配</li>
			<li>精确匹配：用户输入的文字和此关键词一样才会触发,一般用于一个关键词</li>
			<li>模糊匹配：只要用户输入的文字包含此关键词就触</li>
		</ul>
	</div>
<div class="flexigrid">
	<div class="mDiv">
		<div class="ftitle">
			<h3>关键词列表</h3>
		</div>
		<div class="sDiv">
			<div class="sDiv2">
				<form method="get" name="formSearch">
				<input type="hidden" name="w" value="keyword">
    			<input type="hidden" name="t" value="keyword_manage">
					<select name="fields" class="select">
					  <option value="0"><?php echo $lang['wechat_select_all']?></option>
						<?php foreach($lang['reply_type_name'] as $k=>$v){ if($k>=2){continue;}?>
						  <option value="<?php echo $k+1;?>" <?php echo !empty($_GET['type']) && $_GET['type']==($k+1) ? ' selected' : '';?>><?php echo $v;?></option>
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
							<div style="text-align: left; width: 200px;"><?php echo $lang['wechat_keywords'];?></div>
						</th>
						<th axis="col4" class="" align="left">
							<div style="text-align: left; width: 80px;" class=""><?php echo $lang['reply_type'];?></div>
						</th>
						<th axis="col5" class="" align="left">
							<div style="text-align: left; width: 150px;" class=""><?php echo $lang['reply_content'];?></div>
						</th>
						<th axis="col5" class="" align="left">
							<div style="text-align: left; width: 80px;" class=""><?php echo $lang['reply_pattern_type'];?></div>
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
				<div class="add" title="添加一条新数据到列表"><span><i class="fa fa-plus"></i><a href="index.php?w=keyword&t=keyword_add">新增数据</a></span>
				</div>
			</div>
		</div>
		<div style="clear:both"></div>
	</div>
	<div class="bDiv" style="height: auto;">
		<div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
			<table>
				<tbody>
					
					<?php if(!empty($output['reply_list']) && is_array($output['reply_list'])){ ?>
        			<?php foreach($output['reply_list'] as $key => $value){ ?>
					
					<tr>
						<td class="sign">
							<div style="width: 24px;"><i class="ico-check"></i>
							</div>
						</td>
						<td class="handle" align="center">
							<div style="text-align: center; width: 150px;"><a class="btn blue" href="index.php?w=keyword&t=keyword_edit&rid=<?php echo $value['reply_id'];?>"><i class="fa fa-pencil-square-o"></i>编辑</a><a class="btn blue" href="javascript:void(0)" onclick="if(confirm('确认删除？')){location.href='index.php?w=keyword&t=keyword_del&rid=<?php echo $value['reply_id'];?>';}"><i class="fa fa-pencil-square-o"></i>删除</a>
							</div>
						</td>
						<td class="" align="left">
							<div style="text-align: left; width: 200px;"><?php echo trim($value['reply_keywords'],'|');?></div>
						</td>
						<td class="" align="left">
							<div style="text-align: left; width: 80px;"><?php echo $lang['reply_type_name'][$value['reply_msgtype']];?></div>
						</td>
						<td class="" align="left">
							<div style="text-align: left; width:150px;"><?php echo $value['reply_msgtype']==1 ? $lang['reply_material'] : $value['reply_textcontents'];?></div>
						</td>
						<td class="" align="left">
							<div style="text-align: left; width:150px;"><?php echo $lang['reply_pattern_type_name'][$value['reply_patternmethod']];?></div>
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
