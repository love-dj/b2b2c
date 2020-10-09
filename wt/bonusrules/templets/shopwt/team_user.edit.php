<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<style type="text/css">
h3.dialog_head {
	margin: 0 !important;
}
.dialog_content {
	width: 900px;
	padding-top:10px;
	padding: 10px 15px 15px 15px !important;
	overflow: hidden;
}
</style>
<div class="page">
	<div class="fixed-bar">
		<div class="item-title"><a class="back" href="index.php?w=team_user&t=index" title="返回团队列表"><i class="fa fa-arrow-circle-o-left"></i></a>
			<div class="subject">
				<h3>修改团队等级</h3>
				<h5>修改商城会员团队的等级</h5>
			</div>
		</div>
	</div>
	<form id="user_form" action="" name="form1" enctype="multipart/form-data" method="post">
		<input type="hidden" name="form_submit" value="ok" />
		<input type="hidden" name="member_id" value="<?php echo $output['chain_array']['member_id'];?>" />
		<div class="wtap-form-default">			
			<dl class="row">
				<dt class="tit">
					<label for="level_oid"><em>*</em>团队当前等级</label>
				</dt>
				<dd class="opt">
						<?php foreach($output['level_array'] as $k=>$v){ if($output['chain_array']['team_level'] == $v['id']){?>
						<span>等级名称：<?php echo $v['level_name'];?></span><br>
						<span>提成比例：<?php echo $v['layer_rate'];?> %</span><br>
						<span>提成层数：<?php echo $v['commission_layers'];?></span><br>
						<span>平级奖励比例：<?php echo $v['same_layer_rate'];?> %</span><br>
						<span>平级层数：<?php echo $v['same_layers'];?></span>
						<?php }} ?>
					<span class="err"></span>
					<p class="notic"></p>
				</dd>
			</dl>
			<hr>
			<dl class="row">
				<dt class="tit">
					<label for="level_id"><em>*</em>团队等级</label>
				</dt>
				<dd class="opt">
					<select name="level_id" class="valid">
						<option value="0">-请选择-</option>
						<?php foreach($output['level_array'] as $k=>$v){ ?>
						<option value="<?php echo $v['id'];?>"><?php echo $v['level_name'];?></option>
						<?php } ?>
                    </select>
					<span class="err"></span>
					<p class="notic"></p>
				</dd>
			</dl>

 			<div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="submitBtn">保存</a></div>
		</div>
	</form>
</div>
<script type="text/javascript">
	$(function(){
		$("#submitBtn").click(function(){
			if($("#user_form").valid()){
				$("#user_form").submit();
			}
		});
	});
	
	$(document).ready(function(){
		$('#user_form').validate({
			errorPlacement: function(error, element){
				var error_td = element.parent('dd').children('span.err');
				error_td.append(error);
			},
			rules : {				
				level_id : {
					required   : true
				},
			},
			messages : {
				level_id : {
					required : '<i class="fa fa-exclamation-circle"></i>请选择团队等级'
				},
				
			}
		});
	});
</script>