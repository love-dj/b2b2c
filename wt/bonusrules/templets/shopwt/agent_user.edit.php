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
		<div class="item-title"><a class="back" href="index.php?w=team_user&t=index" title="返回代理列表"><i class="fa fa-arrow-circle-o-left"></i></a>
			<div class="subject">
				<h3>修改代理区域</h3>
				<h5>修改商城会员代理的区域</h5>
			</div>
		</div>
	</div>
	<form id="user_form" action="" name="form1" enctype="multipart/form-data" method="post">
		<input type="hidden" name="form_submit" value="ok" />
		<input type="hidden" name="member_id" value="<?php echo $output['chain_array']['member_id'];?>" />
		<div class="wtap-form-default">			
			<dl class="row">
				<dt class="tit">
					<label for="level_oid"><em>*</em>会员当前代理区域</label>
				</dt>
				<dd class="opt">
						<span>区域：<?php echo $output['chain_array']['area_name'];?> </span>
					<span class="err"></span>
					<p class="notic"></p>
				</dd>
			</dl>
			<hr>		
			<dl class="row">
				<dt class="tit">
					<label for="level_id"><em>*</em>选择区域</label>
				</dt>
				<dd class="opt">
					<div class="area-region-select">
					<input id="region" name="region" type="hidden" value="" >
					<input id="area_id" name="area_id" type="hidden" value="" >
					<span class="err"></span>
					</div>
					<p class="notic"></p>
				</dd>
			</dl>

 			<div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="submitBtn">保存</a></div>
		</div>
	</form>
</div>
<script type="text/javascript">
	$(function(){
		$("#region").wt_region({src:'db',show_deep:3});
		$("#submitBtn").click(function(){
			if($("#user_form").valid()){
				$("#user_form").submit();
			}
		});
		
		$(".area-region-select select").live("change",function(){
			$("#area_id").val($(this).val());
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