<?php
/**
 * 新增分销商等级
 * @license      http://www.weisbao.com
 * @link       联系方式：13632978801
 * @since      微商宝提供技术支持 授权请联系微商宝授权
 */
defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
	<div class="fixed-bar">
		<div class="item-title"><a class="back" href="index.php?act=schema_level&op=index" title="返回分销商等级列表"><i class="fa fa-arrow-circle-o-left"></i></a>
			<div class="subject">
				<h3> - <?php echo $lang['nc_new'];?></h3>
				<h5>新增分销商等级</h5>
			</div>
		</div>
	</div>
	<form id="schema_form" action="" name="form1" enctype="multipart/form-data" method="post">
		<input type="hidden" name="form_submit" value="ok" />
		<div class="ncap-form-default">
			<dl class="row">
				<dt class="tit">
					<label for="schema_weight"><em>*</em>等级权重</label>
				</dt>
				<dd class="opt">
					<input type="text" value="" name="level_weight" id="schema_weight" maxlength="20" class="input-txt">
					<span class="err"></span>
					<p class="notic"></p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label for="schema_level"><em>*</em>等级名称</label>
				</dt>
				<dd class="opt">
					<input type="text" value="" name="level_name" id="schema_level" class="input-txt">
					<span class="err"></span>
					<p class="notic"></p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label for="layer_one"><em></em>一级分销比例</label>
				</dt>
				<dd class="opt">
					<input type="text" value="" name="layer_one" id="layer_one" class="input-txt">
					<span class="err"></span>
					<p class="notic"></p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label for="layer_two"><em></em>二级分销比例</label>
				</dt>
				<dd class="opt">
					<input type="text" value="" name="layer_two" id="layer_two" class="input-txt">
					<span class="err"></span>
					<p class="notic"></p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label for="layer_three"><em></em>三级分销比例</label>
				</dt>
				<dd class="opt">
					<input type="text" value="" name="layer_three" id="layer_three" class="input-txt">
					<span class="err"></span>
					<p class="notic"></p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label for="level_people"><em></em>等级人数</label>
				</dt>
				<dd class="opt">
					<input type="text" value="" name="level_people" id="level_people" class="input-txt">
					<span class="err"></span>
					<p class="notic"></p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label for="dividend_ratio">升级条件</label>
				</dt>				
				<dd class="opt">
					<div class="col-xs-12 col-sm-9 col-md-10">
						<div class="input-group row" style="padding-buttom:50px;">
							<label class="radio-inline col-xs-12 col-sm-6">
								<input type="checkbox" name="upgrade_type[first_cost_count]" value="1"><?php if (!empty($condition)) ?> <check="checkbox">
								下一级用户消费满&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="text" name="upgrade_value[first_cost_count]" style="width: 50px;" value=""><?php if (!empty($condition)) ?> <check="checkbox">
								<span>&nbsp;元&nbsp;</span>
								&nbsp;人数达到&nbsp;
								<input type="text" name="upgrade_value[first_cost_num]" style="width: 50px;" value=""><?php if (!empty($condition)) ?> <check="checkbox">
								<span>&nbsp;个&nbsp;</span>
							</label>
						</div>
						<?php echo "<br>"; ?>
						<div class="input-group row">
							<label class="radio-inline col-xs-12 col-sm-6">
								<input type="checkbox" name="upgrade_type[order_money]" value="1">
								分销订单金额满&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="text" name="upgrade_value[order_money]" value="">
								<span>&nbsp;元&nbsp;</span>
							</label>

							<label class="radio-inline  col-xs-12 col-sm-6" style="margin-left:50px;">
								<input type="checkbox" name="upgrade_type[order_count]" value="1">
								&nbsp;分销订单数量满&nbsp;&nbsp;&nbsp;
								<input type="text" name="upgrade_value[order_count]" value="">
								<span>个</span>
							</label>
						</div>
						<?php echo "<br>"; ?>
						<div class="input-group row">
							<label class="radio-inline col-xs-12 col-sm-6">
								<input type="checkbox" name="upgrade_type[first_order_money]" value="1">
								下一级分销订单金额满&nbsp;&nbsp;&nbsp;
								<input type="text" name="upgrade_value[first_order_money]" value="">
								<span>元</span>
							</label>
							<label class="radio-inline col-xs-12 col-sm-6" style="margin-left:50px;">
								<input type="checkbox" name="upgrade_type[first_order_count]" value="1">
								下一级分销订单数量满&nbsp;&nbsp;&nbsp;
								<input type="text" name="upgrade_value[first_order_count]" value="">
								<span>个</span>
							</label>
						</div>
						<?php echo "<br>"; ?>
						<div class="input-group row">
							<label class="radio-inline col-xs-12 col-sm-6">
								<input type="checkbox" name="upgrade_type[self_buy_money]" value="1">
								自购订单金额满&nbsp;&nbsp;&nbsp;
								<input type="text" name="upgrade_value[self_buy_money]" value="">
								<span>元</span>
							</label>

							<label class="radio-inline col-xs-12 col-sm-6" style="margin-left:50px;">
								<input type="checkbox" name="upgrade_type[self_buy_count]" value="1">
								自购单数量满&nbsp;&nbsp;&nbsp;
								<input type="text" name="upgrade_value[self_buy_count]" value="">
								<span>个</span>
							</label>
						</div>
						
						<?php echo "<br>"; ?>						
						<div class="input-group row">
							<label class="radio-inline col-xs-12 col-sm-6">
								<input type="checkbox" name="upgrade_type[settle_money]" value="1">
								结算佣金总额满&nbsp;&nbsp;&nbsp;
								<input type="text" name="upgrade_value[settle_money]" value="">
								<span>元</span>
							</label>							
						</div>
						<?php echo "<br>"; ?>
						<div class="input-group row">
							<!-- <div class="input-group">
								<label class="radio-inline">
									<input type="checkbox" name="upgrade_type[goods]" value="1">
									购买指定商品 &nbsp;&nbsp;&nbsp;

									<input type="hidden" id="goodsid" name="upgrade_value[goods]" value="">
									<div class="input-group" style="display:inline;">
										<input type="text" name="goods" maxlength="30" value="" id="goods" class="form-control" readonly="">
										<div class="input-group-btn" style="display:inline;">
											<button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-menus-goods').modal();">
												选择商品
											</button>
											<button class="btn btn-danger" type="button" onclick="$('#goods_id').val('');$('#goods').val('');">
												清除选择
											</button>
										</div>
									</div>
								</label>
								<?php echo "<br>"; ?>
								<span id="goodsthumb" class="help-block" style="display:none"><img style="width:100px;height:100px;border:1px solid #ccc;padding:1px" src=""></span>
							</div> -->

							<!-- <div class="input-group">
								<label class="radio-inline"><input type="radio" name="upgrade_type[become]" value="0" checked="" style="margin: 4px 0 0; position:inherit"> 付款后</label>
								<label class="radio-inline"  style="margin-left:50px;"><input type="radio" name="upgrade_type[become]" value="1" style="margin: 4px 0 0; position:inherit"> 完成后</label>
							</div> -->
							<span class="help-block">获取推广下线权利的会员达到条件，自动升级为对应等级的代理商；指定某团队等级人数升级需添加完成后编辑</span>
						</div>
					</div>
					<p class="notic"></p>
				</dd>
			</dl>

			<div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.form1.submit()" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
		</div>
	</form>
</div>
<script type="text/javascript">
	$(function(){
		$("#submitBtn").click(function(){
			if($("#express_form").valid()){
				$("#express_form").submit();
			}
		});
	});
	$(document).ready(function(){
		$('#express_form').validate({
			errorPlacement: function(error, element){
				var error_td = element.parent('dd').children('span.err');
				error_td.append(error);
			},
			rules : {				
				level_weight : {
					required   : true
				}level_name : {
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
				
			}
		});
	});
</script>