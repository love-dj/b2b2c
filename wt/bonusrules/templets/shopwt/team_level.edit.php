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
		<div class="item-title"><a class="back" href="index.php?w=team_level&t=index" title="返回团队等级列表"><i class="fa fa-arrow-circle-o-left"></i></a>
			<div class="subject">
				<h3>编辑团队等级</h3>
				<h5>编辑商城会员团队等级</h5>
			</div>
		</div>
	</div>
	<form id="level_form" action="" name="form1" enctype="multipart/form-data" method="post">
		<input type="hidden" name="form_submit" value="ok" />
		<input type="hidden" name="id" value="<?php echo $output['level_array']['id'];?>" />
		<div class="wtap-form-default">
			<dl class="row">
				<dt class="tit">
					<label for="level_weight"><em>*</em>等级权重</label>
				</dt>
				<dd class="opt">
					<input type="text" value="<?php echo $output['level_array']['level_weight'];?>" name="level_weight" id="level_weight" maxlength="20" class="s-input-txt">
					<span class="err"></span>
					<p class="notic"></p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label for="level_name"><em>*</em>等级名称</label>
				</dt>
				<dd class="opt">
					<input type="text" value="<?php echo $output['level_array']['level_name'];?>" name="level_name" id="level_name" class="input-txt">
					<span class="err"></span>
					<p class="notic"></p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label for="commission_layers"><em>*</em>提成层数</label>
				</dt>
				<dd class="opt">
					<input type="text" value="<?php echo $output['level_array']['commission_layers'];?>" name="commission_layers" id="commission_layers" class="s-input-txt"> 层
					<span class="err"></span>
					<p class="notic"></p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label for="layer_rate"><em>*</em>层级奖励比例</label>
				</dt>
				<dd class="opt">
					<input type="text" value="<?php echo $output['level_array']['layer_rate'];?>" name="layer_rate" id="layer_rate" class="s-input-txt"> %
					<span class="err"></span>
					<p class="notic"></p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label for="same_layers"><em>*</em>平级层级</label>
				</dt>
				<dd class="opt">
					<input type="text" value="<?php echo $output['level_array']['same_layers'];?>" name="same_layers" id="same_layers" class="s-input-txt"> 层
					<span class="err"></span>
					<p class="notic"></p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label for="same_layer_rate"><em>*</em>平级奖励比例</label>
				</dt>
				<dd class="opt">
					<input type="text" value="<?php echo $output['level_array']['same_layer_rate'];?>" name="same_layer_rate" id="same_layer_rate" class="s-input-txt"> %
					<span class="err"></span>
					<p class="notic"></p>
				</dd>
			</dl>

		      <dl class="row">
		        <dt class="tit">
		          <label for="up_condition">升级条件关系</label>
		        </dt>
		        <dd class="opt">
		          <div class="onoff">
		            <label for="up_condition_1" class="cb-enable <?php if($output['upgrade_value_array']['up_condition'] == '1'){ ?>selected<?php } ?>" title="与"><span>与</span></label>
		            <label for="up_condition_0" class="cb-disable <?php if($output['upgrade_value_array']['up_condition'] == '0'){ ?>selected<?php } ?>" title="或"><span>或</span></label>
		            <input type="radio" id="up_condition_1" name="upgrade_value[up_condition]" value="1" <?php echo $output['upgrade_value_array']['up_condition']==1?'checked=checked':''; ?>>
		            <input type="radio" id="up_condition_0" name="upgrade_value[up_condition]" value="0" <?php echo $output['upgrade_value_array']['up_condition']==0?'checked=checked':''; ?>>
		          </div>
		          <p class="notic">[或]满足任意条件都可以升级<br>[与]满足所有条件才可以升级(ps:其中下级粉丝人数和下级分销商人数是上级分销商的升级依据,并隐藏[购买指定商品][购买指定商品之一]升级方式)</p>
		        </dd>
		      </dl>
			<dl class="row">
				<dt class="tit">
					<label for="dividend_ratio">升级条件</label>
				</dt>
				<dd class="opt">
					<div class="col-xs-12 col-sm-9 col-md-10">
						<div class="input-group row" style="margin-bottom:5px;">
							<label class="radio-inline col-xs-12 col-sm-6">
								<input type="checkbox" name="upgrade_type[]" value="first_cost_count" <?php if(in_array('first_cost_count', $output['upgrade_type_array'])){echo "checked=checked";}?>>
								一级会员消费满&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="text" name="upgrade_value[first_cost_count]" style="width: 50px;" value="<?php echo $output['upgrade_value_array']['first_cost_count'];?>" >
								<span>&nbsp;元&nbsp;</span>
								&nbsp;订单数达到&nbsp;
								<input type="text" name="upgrade_value[first_cost_num]" style="width: 50px;" value="<?php echo $output['upgrade_value_array']['first_cost_num'];?>" >
								<span>&nbsp;个&nbsp;</span>
							</label>
							
							<label class="radio-inline  col-xs-12 col-sm-6" style="margin-left:20px;">
								<input type="checkbox" name="upgrade_type[]" value="team_first_member_count" <?php if(in_array('team_first_member_count', $output['upgrade_type_array'])){echo "checked=checked";}?>>
								&nbsp;一级会员数量满&nbsp;&nbsp;&nbsp;
								<input type="text" name="upgrade_value[team_first_member_count]" value="<?php echo $output['upgrade_value_array']['team_first_member_count'];?>">
								<span>个</span>
							</label>
						</div>
						<div class="input-group row" style="margin-bottom:5px;">
							<label class="radio-inline col-xs-12 col-sm-6">
								<input type="checkbox" name="upgrade_type[]" value="second_cost_count" <?php if(in_array('second_cost_count', $output['upgrade_type_array'])){echo "checked=checked";}?>>
								二级会员消费满&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="text" name="upgrade_value[second_cost_count]" style="width: 50px;" value="<?php echo $output['upgrade_value_array']['second_cost_count'];?>" >
								<span>&nbsp;元&nbsp;</span>
								&nbsp;订单数数达到&nbsp;
								<input type="text" name="upgrade_value[second_cost_num]" style="width: 50px;" value="<?php echo $output['upgrade_value_array']['second_cost_num'];?>" >
								<span>&nbsp;个&nbsp;</span>
							</label>
							
							<label class="radio-inline  col-xs-12 col-sm-6" style="margin-left:20px;">
								<input type="checkbox" name="upgrade_type[]" value="team_second_member_count" <?php if(in_array('team_second_member_count', $output['upgrade_type_array'])){echo "checked=checked";}?>>
								&nbsp;二级会员数量满&nbsp;&nbsp;&nbsp;
								<input type="text" name="upgrade_value[team_second_member_count]" value="<?php echo $output['upgrade_value_array']['team_second_member_count'];?>">
								<span>个</span>
							</label>
						</div>

						<div class="input-group row" style="margin-bottom:5px;">
							<label class="radio-inline col-xs-12 col-sm-6">
								<input type="checkbox" name="upgrade_type[]" value="team_order_money" <?php if(in_array('team_order_money', $output['upgrade_type_array'])){echo "checked=checked";}?>>
								团队业绩金额满&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="text" name="upgrade_value[team_order_money]" value="<?php echo $output['upgrade_value_array']['team_order_money'];?>">
								<span>&nbsp;元&nbsp;</span>
							</label>

							<label class="radio-inline  col-xs-12 col-sm-6" style="margin-left:20px;">
								<input type="checkbox" name="upgrade_type[]" value="team_member_count" <?php if(in_array('team_member_count', $output['upgrade_type_array'])){echo "checked=checked";}?>>
								&nbsp;团队会员数量满&nbsp;&nbsp;&nbsp;
								<input type="text" name="upgrade_value[team_member_count]" value="<?php echo $output['upgrade_value_array']['team_member_count'];?>">
								<span>个</span>
							</label>
						</div>
						

						<div class="input-group row" style="margin-bottom:5px;">
							<label class="radio-inline col-xs-12 col-sm-6">
								<input type="checkbox" name="upgrade_type[]" value="self_buy_money" <?php if(in_array("self_buy_money", $output['upgrade_type_array'])){echo "checked=checked";}?>>
								自购订单金额满
								<input type="text" name="upgrade_value[self_buy_money]" value="<?php echo $output['upgrade_value_array']['self_buy_money'];?>">
								<span>元</span>
							</label>

							<label class="radio-inline col-xs-12 col-sm-6" style="margin-left:50px;">
								<input type="checkbox" name="upgrade_type[]" value="self_buy_count" <?php if(in_array("self_buy_count", $output['upgrade_type_array'])){echo "checked=checked";}?>>
								自购单数量满
								<input type="text" name="upgrade_value[self_buy_count]" value="<?php echo $output['upgrade_value_array']['self_buy_count'];?>">
								<span>个</span>
							</label>
						</div>
																	
						<div class="input-group row">
							<label class="radio-inline col-xs-12 col-sm-6">
								<input type="checkbox" name="upgrade_type[]" value="settle_money" <?php if(in_array("settle_money", $output['upgrade_type_array'])){echo "checked=checked";}?>>
								结算分红金额满&nbsp;&nbsp;&nbsp;
								<input type="text" name="upgrade_value[settle_money]" value="<?php echo $output['upgrade_value_array']['settle_money'];?>">
								<span>元</span>
							</label>
							
							<label class="radio-inline  col-xs-12 col-sm-6" style="margin-left:20px;">
								<input type="checkbox" name="upgrade_type[]" value="team_identity_member_count" <?php if(in_array("team_identity_member_count", $output['upgrade_type_array'])){echo "checked=checked";}?>>
								&nbsp;团队具备身份的会员数量满&nbsp;&nbsp;&nbsp;
								<input type="text" name="upgrade_value[team_identity_member_count]" value="<?php echo $output['upgrade_value_array']['team_identity_member_count'];?>">
								<span>个</span>
							</label>							
						</div>	

						<div class="input-group row" style="margin-bottom:5px;">
							<label class="radio-inline col-xs-12 col-sm-6">
								<input type="checkbox" name="upgrade_type[]" id="b_o_goods" value="buy_one_goods" <?php if(in_array("buy_one_goods", $output['upgrade_type_array'])){echo "checked=checked";}?>>
								购买指定商品之一&nbsp;&nbsp;&nbsp;
							</label>							
						</div>
					</div>
					<p class="notic"></p>
				</dd>
			</dl>

			<dl class="row" id="search_input" style="display: <?php if(in_array("buy_one_goods", $output['upgrade_type_array'])){echo "block";}else{echo "none";}?>;">
		        <dt class="tit">
		          <label><em>*</em>搜索商品</label>
		        </dt>
		        <dd class="opt">
		          <input type="text" placeholder="搜索商品名称" value="" id="goods_name" maxlength="20" class="input-txt">
		          <a id="goods_search" href="JavaScript:void(0);" class="wtap-btn mr5"><?php echo $lang['wt_search'];?></a></dd>
		      </dl>
		      <dl class="row" id="selected_goods_list" style="display: <?php if(in_array("buy_one_goods", $output['upgrade_type_array'])){echo "block";}else{echo "none";}?>;">
		        <dt class="tit">已选择商品</dt>
		        <dd class="opt">
		          <input type="hidden" name="valid_recommend" id="valid_recommend" value="">
		          <span class="err"></span>
		          <ul class="dialog-goodslist-s1 goods-list scrollbar-box">
		          	<?php foreach($output['goods_array'] as $value){ ?>
		          	<li>
		          		<div onclick="del_recommend_goods(this,<?php echo $value['goods_id'];?>);" class="goods-pic">
		          			<span class="ac-ico"></span>
		          			<span class="thumb size-72x72">
		          				<i></i>
		          				<img width="72" goods_id="55" title="<?php echo $value['goods_name'];?>" goods_name="<?php echo $value['goods_name'];?>" src="<?php echo $value['goods_image'];?>">
		          			</span>
		          		</div>
		          		<div class="goods-name">
		          			<a href="index.php?w=goods&goods_id=<?php echo $value['goods_id'];?>" target="_blank"><?php echo $value['goods_name'];?></a>
		          		</div>
		          		<input name="upgrade_value[goods_id_list][]" value="<?php echo $value['goods_id'];?>" type="hidden">
		          	</li>
		          <?php } ?>
		          </ul>
		        </dd>
		      </dl>
		      <dl class="row" id="upgrade_event_change" style="display: <?php if(in_array("buy_one_goods", $output['upgrade_type_array'])){echo "block";}else{echo "none";}?>;">
		        <dt class="tit">
		          <label for="up_condition">升级事件</label>
		        </dt>
		        <dd class="opt">
		          <div class="onoff">
		            <label for="upgrade_event_1" class="cb-enable <?php if($output['upgrade_value_array']['upgrade_event'] == '1'){ ?>selected<?php } ?>" title="订单完成后"><span>订单完成后</span></label>
		            <label for="upgrade_event_0" class="cb-disable <?php if($output['upgrade_value_array']['upgrade_event'] == '0'){ ?>selected<?php } ?>" title="订单支付后"><span>订单支付后</span></label>
		            <input type="radio" id="upgrade_event_1" name="upgrade_value[upgrade_event]" value="1" <?php echo $output['upgrade_value_array']['upgrade_event']==1?'checked=checked':''; ?>>
		            <input type="radio" id="upgrade_event_0" name="upgrade_value[upgrade_event]" value="0" <?php echo $output['upgrade_value_array']['upgrade_event']==0?'checked=checked':''; ?>>
		          </div>
		          <p class="notic"></p>
		        </dd>
		      </dl>
		      <dl class="row" id="search_goodslist" style="display: <?php if(in_array("buy_one_goods", $output['upgrade_type_array'])){echo "block";}else{echo "none";}?>;">
		        <dt class="tit">选择商品</dt>
		        <dd class="opt">
		          <div id="show_recommend_goods_list" class="show-recommend-goods-list scrollbar-box"></div>
		          <p class="notic">最多可选择10个商品</p>
		        </dd>
		      </dl>
 			<div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="submitBtn">保存</a></div>
		</div>
	</form>
</div>
<script type="text/javascript">
	$(function(){
		$("#submitBtn").click(function(){
			if($("#level_form").valid()){
				$("#level_form").submit();
			}
		});
	});
	$("#b_o_goods").change(function() { 
		var now_status = $(this).attr("checked");
		if(now_status == 'checked'){
			$("#search_input").css('display','block');
			$("#upgrade_event_change").css('display','block');
			$("#selected_goods_list").css('display','block');
			$("#search_goodslist").css('display','block');
		}else{
			$("#search_input").css('display','none');
			$("#upgrade_event_change").css('display','none');
			$("#selected_goods_list").css('display','none');
			$("#search_goodslist").css('display','none');
		}
	});

    $('#goods_search').on('click',function(){
        $('#valid_recommend').rules('remove');
        var goods_name = $('#goods_name').val();
    	if(goods_name.length > 0){
    	    $('#show_recommend_goods_list').load('index.php?w=team_level&t=get_goodslist&goods_name='+goods_name);
    	}else{
    		alert('请输入商品关键词');
    	}
    });
	function select_recommend_goods(goods_id) {
		if (typeof goods_id == 'object') {
			var goods_name = goods_id['goods_name'];
			var goods_pic = goods_id['goods_image'];
			var goods_id = goods_id['goods_id'];
		} else {
	    	var goods = $("#show_recommend_goods_list img[goods_id='"+goods_id+"']");
	    	var goods_pic = goods.attr("src");
	    	var goods_name = goods.attr("goods_name");
		}
		var obj = $("#selected_goods_list");
		if(obj.find("img[goods_id='"+goods_id+"']").size()>0) return;//避免重复
		if(obj.find("ul>li").size()>=10){
			alert('最多可推荐10个商品');
			return false;
		}
		var text_append = '';
		text_append += '<div onclick="del_recommend_goods(this,'+goods_id+');" class="goods-pic">';
		text_append += '<span class="ac-ico"></span>';
		text_append += '<span class="thumb size-72x72">';
		text_append += '<i></i>';
	  	text_append += '<img width="72" goods_id="'+goods_id+'" title="'+goods_name+'" goods_name="'+goods_name+'" src="'+goods_pic+'" />';
		text_append += '</span></div>';
		text_append += '<div class="goods-name">';
		text_append += '<a href="<?php echo BASE_SITE_URL?>/index.php?w=goods&goods_id='+goods_id+'" target="_blank">';
	  	text_append += goods_name+'</a>';
		text_append += '</div>';
		text_append += '<input name="upgrade_value[goods_id_list][]" value="'+goods_id+'" type="hidden">';
		obj.find("ul").append('<li>'+text_append+'</li>');
		<?php if (!$_GET['rec_gc_id']) { ?>
		   $('#gc_id').val($('#gcategory').children('select').last().val());	
	    <?php } ?>
	}
	function del_recommend_goods(obj,goods_id) {
		$(obj).parent().remove();
	}
	$(document).ready(function(){
		$('#level_form').validate({
			errorPlacement: function(error, element){
				var error_td = element.parent('dd').children('span.err');
				error_td.append(error);
			},
			rules : {				
				level_weight : {
					required   : true
				},
				level_name : {
					required   : true
				},
				layer_rate : {
					required   : true
				},
				commission_layers : {
					required   : true
				},
				same_layers : {
					required   : true
				},
				same_layer_rate : {
					required   : true
				},
			},
			messages : {
				level_weight : {
					required : '<i class="fa fa-exclamation-circle"></i>等级权重不能为空'
				},
				level_name : {
					required : '<i class="fa fa-exclamation-circle"></i>等级名称不能为空'
				},
				layer_rate : {
					required : '<i class="fa fa-exclamation-circle"></i>层级奖励比例不能为空'
				},
				commission_layers : {
					required : '<i class="fa fa-exclamation-circle"></i>提成层数不能为空'
				},
				same_layers : {
					required : '<i class="fa fa-exclamation-circle"></i>平级层级不能为空'
				},
				same_layer_rate : {
					required : '<i class="fa fa-exclamation-circle"></i>平级奖励比例不能为空'
				},
				
			}
		});
	});
</script>