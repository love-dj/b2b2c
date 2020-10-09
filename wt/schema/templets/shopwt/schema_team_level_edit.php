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
		<div class="item-title"><a class="back" href="index.php?act=level&op=index" title="返回团队等级列表"><i class="fa fa-arrow-circle-o-left"></i></a>
			<div class="subject">
				<h3>团队等级 - <?php echo $lang['nc_edit'];?></h3>
				<h5>编辑团队等级</h5>
			</div>
		</div>
	</div>
	<form id="schema_form_edit" action="" name="form1" enctype="multipart/form-data" method="post">
		<input type="hidden" name="form_submit" value="ok" />
		<input type="hidden" name="id" value="<?php echo $output['level_array']['id'];?>" />
		<div class="ncap-form-default">
			<dl class="row">
				<dt class="tit">
					<label for="level_weight"><em>*</em>等级权重</label>
				</dt>
				<dd class="opt">
					<input type="text" value="<?php echo $output['level_array']['level_weight'];?>" name="level_weight" id="schema_weight" maxlength="20" class="input-txt">
					<span class="err"></span>
					<p class="notic"></p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label for="schema_level"><em>*</em>等级名称</label>
				</dt>
				<dd class="opt">
					<input type="text" value="<?php echo $output['level_array']['level_name'];?>" name="level_name" id="schema_level" class="input-txt">
					<span class="err"></span>
					<p class="notic"></p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label for="layer_one"><em></em>奖金比例</label>
				</dt>
				<dd class="opt">
					<input type="text" value="<?php echo $output['level_array']['layer_one'];?>" name="layer_one" id="layer_one" class="input-txt">
					<span class="err"></span>
					<p class="notic"></p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label for="level_people"><em></em>等级人数</label>
				</dt>
				<dd class="opt">
					<input type="text" value="<?php echo $output['level_array']['level_people'];?>" name="level_people" id="level_people" class="input-txt">
					<span class="err"></span>
					<p class="notic"></p>
				</dd>
			</dl>	
			<dl class="row">
				<dt class="tit">
					<label for="dividend_ratio">升级条件</label>
				</dt>
                <?php $condition = unserialize($output['level_array']['level_condition']) ?>
				<dd class="opt">
					<div class="col-xs-12 col-sm-9 col-md-10">
						<div class="input-group row" style="padding-buttom:50px;">
							<label class="radio-inline col-xs-12 col-sm-6">
								<input type="checkbox" name="upgrade_type[first_cost_count]" value="" <?php if(!empty($condition['first_cost_count']) && !empty($condition['first_cost_num'])){ ?> checked <?php } ?>>
								一级客户消费满
								<input type="text" name="upgrade_value[first_cost_count]" style="width: 50px;" value="<?php echo $condition['first_cost_count'];?>">
								<span>元</span>
								人数达到
								<input type="text" name="upgrade_value[first_cost_num]" style="width: 50px;" value="<?php echo $condition['first_cost_num'];?>">
								<span>个</span>
							</label>
						</div>

						<div class="input-group row">
							<label class="radio-inline col-xs-12 col-sm-6">
								<input type="checkbox" name="upgrade_type[order_money]" value="1" <?php if(!empty($condition['order_money'])){ ?> checked <?php } ?>>
								分销订单金额满
								<input type="text" name="upgrade_value[order_money]" value="<?php echo $condition['order_money'];?>">
								<span>元</span>
							</label>

							<label class="radio-inline  col-xs-12 col-sm-6" style="margin-left:50px;">
								<input type="checkbox" name="upgrade_type[order_count]" value="1" <?php if(!empty($condition['order_count'])){ ?> checked <?php } ?>>
								分销订单数量满
								<input type="text" name="upgrade_value[order_count]" value="<?php echo $condition['order_count'];?>">
								<span>个</span>
							</label>

						</div>
						<div class="input-group row">
							<label class="radio-inline col-xs-12 col-sm-6">
								<input type="checkbox" name="upgrade_type[first_order_money]" value="1" <?php if(!empty($condition['first_order_money'])){ ?> checked <?php } ?>>
								一级分销订单金额满
								<input type="text" name="upgrade_value[first_order_money]" value="<?php echo $condition['first_order_money'];?>">
								<span>元</span>
							</label>
							<label class="radio-inline col-xs-12 col-sm-6" style="margin-left:50px;">
								<input type="checkbox" name="upgrade_type[first_order_count]" value="1" <?php if(!empty($condition['first_order_count'])){ ?> checked <?php } ?>>
								一级分销订单数量满
								<input type="text" name="upgrade_value[first_order_count]" value="<?php echo $condition['first_order_count'];?>">
								<span>个</span>
							</label>
						</div>

						<div class="input-group row">
							<label class="radio-inline col-xs-12 col-sm-6">
								<input type="checkbox" name="upgrade_type[self_buy_money]" value="1" <?php if(!empty($condition['self_buy_money'])){ ?> checked <?php } ?>>
								自购订单金额满
								<input type="text" name="upgrade_value[self_buy_money]" value="<?php echo $condition['self_buy_money'];?>">
								<span>元</span>
							</label>

							<label class="radio-inline col-xs-12 col-sm-6" style="margin-left:50px;">
								<input type="checkbox" name="upgrade_type[self_buy_count]" value="1" <?php if(!empty($condition['self_buy_count'])){ ?> checked <?php } ?>>
								自购单数量满
								<input type="text" name="upgrade_value[self_buy_count]" value="<?php echo $condition['self_buy_count'];?>">
								<span>个</span>
							</label>
						</div>

						<div class="input-group row">
							<label class="radio-inline col-xs-12 col-sm-6">
								<input type="checkbox" name="upgrade_type[fans_count]" value="1" <?php if(!empty($condition['fans_count'])){ ?> checked <?php } ?>>
								粉丝人数满
								<input type="text" name="upgrade_value[fans_count]" value="<?php echo $condition['fans_count'];?>">
								<span>人</span>
							</label>
							<label class="radio-inline col-xs-12 col-sm-6" style="margin-left:50px;">
								<input type="checkbox" name="upgrade_type[first_fans_count]" value="1" <?php if(!empty($condition['first_fans_count'])){ ?> checked <?php } ?>>
								一级粉丝人数满
								<input type="text" name="upgrade_value[first_fans_count]" value="<?php echo $condition['first_fans_count'];?>">
								<span>人</span>
							</label>
						</div>

						<div class="input-group row">
							<label class="radio-inline col-xs-12 col-sm-6">
								<input type="checkbox" name="upgrade_type[fans_order_count]" value="1" <?php if(!empty($condition['fans_order_count'])){ ?> checked <?php } ?>>
								粉丝分销商人数满
								<input type="text" name="upgrade_value[fans_order_count]" value="<?php echo $condition['fans_order_count'];?>">
								<span>人</span>
							</label>
							<label class="radio-inline col-xs-12 col-sm-6" style="margin-left:50px;">
								<input type="checkbox" name="upgrade_type[first_fans_order_count]" value="1" <?php if(!empty($condition['first_fans_order_count'])){ ?> checked <?php } ?>>
								一级粉丝分销商人数满
								<input type="text" name="upgrade_value[first_fans_order_count]" value="<?php echo $condition['first_fans_order_count'];?>">
								<span>人</span>
							</label>
						</div>
						
						<div class="input-group row">
							<label class="radio-inline col-xs-12 col-sm-6">
								<input type="checkbox" name="upgrade_type[settle_money]" value="1" <?php if(!empty($condition['settle_money'])){ ?> checked <?php } ?>>
								结算佣金总额满
								<input type="text" name="upgrade_value[settle_money]" value="<?php echo $condition['settle_money'];?>">
								<span>元</span>
							</label>
							
						</div>

						<div class="input-group row">
							<!--<div class="input-group">
								<label class="radio-inline">
									<input type="checkbox" name="upgrade_type[goods]" value="1">
									购买指定商品

									<input type="hidden" id="goodsid" name="upgrade_value[goods]" value="">
									<div class="input-group" style="display:inline;">
										<input type="text" name="goods" maxlength="30" value="" id="goods" class="form-control" readonly="">
										<div class="input-group-btn" style="display:inline;">
											<button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-menus-goods').modal();">
												选择商品
											</button>
											<button class="btn btn-danger" type="button" onclick="$('#goodsid').val('');$('#goods').val('');">
												清除选择
											</button>
										</div>
									</div>
								</label>

								<span id="goodsthumb" class="help-block" style="display:none"><img style="width:100px;height:100px;border:1px solid #ccc;padding:1px" src=""></span>
							</div>-->
							<div class="input-group">
								<label class="radio-inline"><input type="radio" name="upgrade_type[become]" value="0" checked="" style="margin: 4px 0 0; position:inherit"> 付款后</label>
								<label class="radio-inline"  style="margin-left:50px;"><input type="radio" name="upgrade_type[become]" value="1" style="margin: 4px 0 0; position:inherit"> 完成后</label>
							</div>
							<span class="help-block">不需要勾选条件，如需设置条件，直接填写对应条件的值即可！如需删除该条件，删除值即可</span>
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
				}
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