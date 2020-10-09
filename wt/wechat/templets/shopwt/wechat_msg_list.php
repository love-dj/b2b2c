<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_message_set'];?></h3>
        <h5><?php echo $lang['wt_message_set_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
		<ul>
		<li>在公众平台设置所属行业“<span class="red">IT科技 互联网|电子商务</span>”，用下面的“添加”按钮会自动添加。</li>
		<li>也可以在公众平台模板库中按照“微信模板标题”查找，添加编号为“微信模板ID”到模板列表。</li>
		<li class="red">添加成功后，点击<a href="javascript:window.location.reload();" class="btn blue">刷新页面</a>重新加载数据。</li>
		</ul>
  </div>
  <form name='form1' method='post'>
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="submit_type" id="submit_type" value="" />

	
    <table class="flex-table">
		<thead>
		<tr>
		<th width="24" align="center" class="sign"><i class="ico-check"></i></th>
		<th width="60" align="center" class="handle-s">操作</th>
		<th width="200" align="left">商城模板名称</th>
		<th width="150" align="left">微信模板ID</th>
		<th width="150" align="left">微信模板标题</th>
		<th width="100" align="center">绑定状态</th>
		<th></th>
		</tr>
		</thead>
      <tbody>
        <?php if(!empty($output['list'])){?>
        <?php foreach($output['list'] as $val){?>
		<tr>
		<td class="sign"><i class="ico-check"></i></td>
		<td class="handle-s" id="msg_<?php echo $val['msg_code'];?>">
		<?php if (empty($output['template_list'][$val['wx_title']])) {?>
			<a class="btn blue" href="javascript:wx_tpl('<?php echo $val['wx_tpl_id'];?>','<?php echo $val['msg_code'];?>',1);"><i class="fa fa-cloud-upload"></i>添加</a>
		<?php }else if ($val['mp_msg_id'] != $output['template_list'][$val['wx_title']]) {?>
			<a class="btn blue" href="javascript:wx_tpl('<?php echo $output['template_list'][$val['wx_title']];?>','<?php echo $val['msg_code'];?>',2);">
			<i class="fa fa-chain"></i>绑定</a>
		<?php }else{?>
		已添加
		<?php }?>
		
		</td>
		<td><?php echo $val['msg_name'];?></td>
		<td><?php echo $val['wx_tpl_id'];?></td>
		<td><?php echo $val['wx_title'];?></td>
		<td id="<?php $val['msg_code'];?>">
          
          <?php if($val['mp_msg_id']){ ?>
          <a class="btn blue" href="javascript:wx_tpl('<?php echo $output['template_list'][$val['wx_title']];?>','<?php echo $val['msg_code'];?>',2);">
			<i class="fa fa-chain"></i>重新绑定</a>
          <?php }else{?>
          未绑定
          <?php } ?>
          
          
          </td>
		<td></td>
		</tr>
        <?php } ?>
        <?php } ?>
      </tbody>
    </table>
	
	
	
	
	
  </form>
</div>

<script>
function wx_tpl(_id,_code,_t){
<?php if (empty($output['access_token'])) { ?>
	showError('获取access_token失败，请检查公众号设置是否正确！');
<?php }?>

<?php if ($output['wx_industry']['primary_industry']['second_class'] == '互联网|电子商务' || $output['wx_industry']['secondary_industry']['second_class'] == '互联网|电子商务') {?>
	var _url = 'index.php?w=wechat_msg&t=wx_tpl&id='+_id+'&code='+_code+'&to='+_t;
	$.getJSON(_url, function(data){
		if (data.state) {
			$("#msg_"+_code).html('<div style="text-align: center;"> -- </div>');
			$("#"+_code).html('<div style="text-align: center;">已绑定</div>');
			showSucc(data.msg);
			} else {
				showError(data.msg);
			}
	});
<?php }else {?>
	showError('获取所属行业失败，请检查行业设置是否有“互联网|电子商务”！');
<?php } ?>

}
$(function(){
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped: true,// 使用斑马线
		resizable: false,// 不调节大小
		title: '商家消息模板列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false// 不使用列控制      
		});
});
</script> 