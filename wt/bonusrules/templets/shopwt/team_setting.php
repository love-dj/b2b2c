<script type="text/javascript">
$(document).ready(function(){

    $("#submit").click(function(){
        $("#add_form").submit();
    });

});
</script>
<div class="page">
  <div class="fixed-bar">
      <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_team_setting'];?></h3>
        <h5>设置团队无限级参数</h5>
      </div>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="add_form" method="post"  enctype="multipart/form-data">
  <input type="hidden" name="form_submit" value="ok" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="isuse"><?php echo  $lang['team_isuse'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="isuse_1" class="cb-enable <?php if($output['setting']['team_isuse'] == '1'){ ?>selected<?php } ?>" title="开启"><span>开启</span></label>
            <label for="isuse_0" class="cb-disable <?php if($output['setting']['team_isuse'] == '0'){ ?>selected<?php } ?>" title="关闭"><span>关闭</span></label>
            <input type="radio" id="isuse_1" name="isuse" value="1" <?php echo $output['setting']['team_isuse']==1?'checked=checked':''; ?>>
            <input type="radio" id="isuse_0" name="isuse" value="0" <?php echo $output['setting']['team_isuse']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"><?php echo $lang['team_isuse_explain'];?></p>
        </dd>
      </dl>

      <dl class="row">
        <dt class="tit">
          <label for="same_isuse">平级奖励</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="same_isuse_1" class="cb-enable <?php if($output['setting']['team_same_isuse'] == '1'){ ?>selected<?php } ?>" title="开启"><span>开启 </span></label>
            <label for="same_isuse_0" class="cb-disable <?php if($output['setting']['team_same_isuse'] == '0'){ ?>selected<?php } ?>" title="关闭"><span>关闭</span></label>
            <input type="radio" id="same_isuse_1" name="same_isuse" value="1" <?php echo $output['setting']['team_same_isuse']==1?'checked=checked':''; ?>>
            <input type="radio" id="same_isuse_0" name="same_isuse" value="0" <?php echo $output['setting']['team_same_isuse']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"><?php echo $lang['team_same_isuse_explain'];?></p>
        </dd>
      </dl>
	  
      <dl class="row">
        <dt class="tit">
          <label for="same_calculation">平级奖计算方式</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="same_calculation_1" class="cb-enable <?php if($output['setting']['team_same_calculation'] == '1'){ ?>selected<?php } ?>" title="下级团队奖励"><span>下级团队奖励</span></label>
            <label for="same_calculation_0" class="cb-disable <?php if($output['setting']['team_same_calculation'] == '0'){ ?>selected<?php } ?>" title="独立计算"><span>独立计算</span></label>
            <input type="radio" id="same_calculation_1" name="same_calculation" value="1" <?php echo $output['setting']['team_same_calculation']==1?'checked=checked':''; ?>>
            <input type="radio" id="same_calculation_0" name="same_calculation" value="0" <?php echo $output['setting']['team_same_calculation']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic">下级团队奖励：按照下级获得的团队奖励金额来计算平级奖<br>独立计算：商品设置,基础设置等,单独计算平级奖,与下级无关</p>
          <p class="notic" style="margin-top:10px;">商品启用经销商独立提成：如果商品经销商独立提成设置的是比例，那么平级奖计算金额则按照商品实际支付金额计算<br>
如果商品经销商独立提成设置的是固定金额，那么平级奖计算金额则按照固定金额计算</p>
        </dd>
      </dl>

      <dl class="row">
        <dt class="tit">
          <label for="include_self">团队统计是否包括自己</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="include_self_1" class="cb-enable <?php if($output['setting']['team_include_self'] == '1'){ ?>selected<?php } ?>" title="包括"><span>包括</span></label>
            <label for="include_self_0" class="cb-disable <?php if($output['setting']['team_include_self'] == '0'){ ?>selected<?php } ?>" title="不包括"><span>不包括</span></label>
            <input type="radio" id="include_self_1" name="include_self" value="1" <?php echo $output['setting']['team_include_self']==1?'checked=checked':''; ?>>
            <input type="radio" id="include_self_0" name="include_self" value="0" <?php echo $output['setting']['team_include_self']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
	   
      <h5>结算设置</h5>  
	  
      <dl class="row">
        <dt class="tit">
          <label for="billing_option">佣金计算去向</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="billing_option_1" class="cb-enable <?php if($output['setting']['team_billing_option'] == '1'){ ?>selected<?php } ?>" title="允许提现"><span>允许提现</span></label>
            <label for="billing_option_0" class="cb-disable <?php if($output['setting']['team_billing_option'] == '0'){ ?>selected<?php } ?>" title="转入积分"><span>转入积分</span></label>
            <input type="radio" id="billing_option_1" name="billing_option" value="1" <?php echo $output['setting']['team_billing_option']==1?'checked=checked':''; ?>>
            <input type="radio" id="billing_option_0" name="billing_option" value="0" <?php echo $output['setting']['team_billing_option']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic">积分兑换金额比例在【商城】-【会员】-【积分管理】-【规则设置】里面设置</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="settlement_event">结算事件</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="settlement_event_1" class="cb-enable <?php if($output['setting']['team_settlement_event'] == '1'){ ?>selected<?php } ?>" title="订单完成后"><span>订单完成后</span></label>
            <label for="settlement_event_0" class="cb-disable <?php if($output['setting']['team_settlement_event'] == '0'){ ?>selected<?php } ?>" title="订单支付后"><span>订单支付后</span></label>
            <input type="radio" id="settlement_event_1" name="settlement_event" value="1" <?php echo $output['setting']['team_settlement_event']==1?'checked=checked':''; ?>>
            <input type="radio" id="settlement_event_0" name="settlement_event" value="0" <?php echo $output['setting']['team_settlement_event']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic">建议设置[订单完成后]分红订单进入结算计算(ps:计算结算期)</p>
        </dd>
      </dl>
	  
      <dl class="row">
        <dt class="tit">
          <label for="settlement_days">结算天数</label>
        </dt>
        <dd class="opt">
        <input id="settlement_days" name="settlement_days" value="<?php echo $output['setting']['team_settlement_days'];?>" class="s-input-txt" type="text"> 天
          <span class="err"></span>
          <p class="notic">当结算事件发生N天后，佣金才可以结算</p>
        </dd>
      </dl>

      <dl class="row">
        <dt class="tit">
          <label for="commission">选择佣金计算基数</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
             <label for="commission_1" class="cb-enable <?php if($output['setting']['team_commission'] == '1'){ ?>selected<?php } ?>" title="订单实际支付金额"><span>订单实际支付金额</span></label>
            <label for="commission_2" class="cb-midable <?php if($output['setting']['team_commission'] == '2'){ ?>selected<?php } ?>" title="商品现价"><span>商品现价</span></label>
            <label for="commission_3" class="cb-midable2 <?php if($output['setting']['team_commission'] == '3'){ ?>selected<?php } ?>" title="商品成本"><span>商品成本</span></label>
            <label for="commission_4" class="cb-disable <?php if($output['setting']['team_commission'] == '4'){ ?>selected<?php } ?>" title="订单利润"><span>订单利润</span></label>
            <input type="radio" id="commission_1" name="commission" value="1" <?php echo $output['setting']['team_commission']==1?'checked=checked':''; ?>>
            <input type="radio" id="commission_2" name="commission" value="2" <?php echo $output['setting']['team_commission']==2?'checked=checked':''; ?>>
            <input type="radio" id="commission_3" name="commission" value="3" <?php echo $output['setting']['team_commission']==3?'checked=checked':''; ?>>
            <input type="radio" id="commission_4" name="commission" value="4" <?php echo $output['setting']['team_commission']==4?'checked=checked':''; ?>>
          </div>
          <p class="notic">===佣金计算方式说明===<br>根据以上选择的项目为基数来计算分销佣金</p>   
        </dd>     
      </dl>

      <div class="bot"><a id="submit" href="javascript:void(0)" class="wtap-btn-big wtap-btn-green">保存</a></div>    
  </div>

  </form>
</div>
<script type="text/javascript">
$(document).ready(function(){
  $("#_pic").change(function(){
        $("#thumb").val($("#_pic").val());
    });
});
</script> 