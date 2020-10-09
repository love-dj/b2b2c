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
        <h3><?php echo $lang['wt_agent_setting'];?></h3>
        <h5>设置区域分红参数</h5>
      </div>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="add_form" method="post"  enctype="multipart/form-data">
  <input type="hidden" name="form_submit" value="ok" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="isuse"><?php echo  $lang['agent_isuse'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="isuse_1" class="cb-enable <?php if($output['setting']['agent_isuse'] == '1'){ ?>selected<?php } ?>" title="开启"><span>开启</span></label>
            <label for="isuse_0" class="cb-disable <?php if($output['setting']['agent_isuse'] == '0'){ ?>selected<?php } ?>" title="关闭"><span>关闭</span></label>
            <input type="radio" id="isuse_1" name="isuse" value="1" <?php echo $output['setting']['agent_isuse']==1?'checked=checked':''; ?>>
            <input type="radio" id="isuse_0" name="isuse" value="0" <?php echo $output['setting']['agent_isuse']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"><?php echo $lang['agent_isuse_explain'];?></p>
        </dd>
      </dl>

      <dl class="row">
        <dt class="tit">
          <label for="again">申请驳回后可再次申请</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="again_1" class="cb-enable <?php if($output['setting']['agent_again'] == '1'){ ?>selected<?php } ?>" title="是"><span>是</span></label>
            <label for="again_0" class="cb-disable <?php if($output['setting']['agent_again'] == '0'){ ?>selected<?php } ?>" title="否"><span>否</span></label>
            <input type="radio" id="again_1" name="again" value="1" <?php echo $output['setting']['agent_again']==1?'checked=checked':''; ?>>
            <input type="radio" id="again_0" name="again" value="0" <?php echo $output['setting']['agent_again']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic">是：用户代理身份被取消后，可以重新申请；否：则不可以。</p>
        </dd>
      </dl>
	  
      <dl class="row">
        <dt class="tit">
          <label for="level_difference">是否开启极差分红</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="level_difference_1" class="cb-enable <?php if($output['setting']['agent_level_difference'] == '1'){ ?>selected<?php } ?>" title="是"><span>是</span></label>
            <label for="level_difference_0" class="cb-disable <?php if($output['setting']['agent_level_difference'] == '0'){ ?>selected<?php } ?>" title="否"><span>否</span></label>
            <input type="radio" id="level_difference_1" name="level_difference" value="1" <?php echo $output['setting']['agent_level_difference']==1?'checked=checked':''; ?>>
            <input type="radio" id="level_difference_0" name="level_difference" value="0" <?php echo $output['setting']['agent_level_difference']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic">例如：省6%、市5%、区3%；如选择是：那么省只能拿到（6-5）%，市只能拿到（5-3）%，区（3）%；如选择否：那么省6%，市5%，区3%。</p>
        </dd>
      </dl>
	   	  
      <dl class="row">
        <dt class="tit">
          <label for="province_rate">省代理分红比例</label>
        </dt>
        <dd class="opt">
        <input id="province_rate" name="province_rate" value="<?php echo $output['setting']['agent_province_rate'];?>" class="s-input-txt" type="text"> %
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="city_rate">市代理分红比例</label>
        </dt>
        <dd class="opt">
        <input id="city_rate" name="city_rate" value="<?php echo $output['setting']['agent_city_rate'];?>" class="s-input-txt" type="text"> %
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="area_rate">区县代理分红比例</label>
        </dt>
        <dd class="opt">
        <input id="area_rate" name="area_rate" value="<?php echo $output['setting']['agent_area_rate'];?>" class="s-input-txt" type="text"> %
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
	  
      <dl class="row">
        <dt class="tit">
          <label for="settlement_event">结算事件</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="settlement_event_1" class="cb-enable <?php if($output['setting']['agent_settlement_event'] == '1'){ ?>selected<?php } ?>" title="订单完成后"><span>订单完成后</span></label>
            <label for="settlement_event_0" class="cb-disable <?php if($output['setting']['agent_settlement_event'] == '0'){ ?>selected<?php } ?>" title="订单支付后"><span>订单支付后</span></label>
            <input type="radio" id="settlement_event_1" name="settlement_event" value="1" <?php echo $output['setting']['agent_settlement_event']==1?'checked=checked':''; ?>>
            <input type="radio" id="settlement_event_0" name="settlement_event" value="0" <?php echo $output['setting']['agent_settlement_event']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic">默认[订单完成后]分销订单进入结算计算(ps:计算结算期)</p>
        </dd>
      </dl>
	  
      <dl class="row">
        <dt class="tit">
          <label for="average_commission">是否开启平均分红</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="average_commission_1" class="cb-enable <?php if($output['setting']['agent_average_commission'] == '1'){ ?>selected<?php } ?>" title="是"><span>是</span></label>
            <label for="average_commission_0" class="cb-disable <?php if($output['setting']['agent_average_commission'] == '0'){ ?>selected<?php } ?>" title="否"><span>否</span></label>
            <input type="radio" id="average_commission_1" name="average_commission" value="1" <?php echo $output['setting']['agent_average_commission']==1?'checked=checked':''; ?>>
            <input type="radio" id="average_commission_0" name="average_commission" value="0" <?php echo $output['setting']['agent_average_commission']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic">是：则多个区域代理均分利润；否：则单独获取利润</p>
        </dd>
      </dl>
	  
      <dl class="row">
        <dt class="tit">
          <label for="settlement_days">结算天数</label>
        </dt>
        <dd class="opt">
        <input id="settlement_days" name="settlement_days" value="<?php echo $output['setting']['agent_settlement_days'];?>" class="s-input-txt" type="text"> 天
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
             <label for="commission_1" class="cb-enable <?php if($output['setting']['agent_commission'] == '1'){ ?>selected<?php } ?>" title="订单实际支付金额"><span>订单实际支付金额</span></label>
            <label for="commission_2" class="cb-midable <?php if($output['setting']['agent_commission'] == '2'){ ?>selected<?php } ?>" title="商品现价"><span>商品现价</span></label>
            <label for="commission_3" class="cb-midable2 <?php if($output['setting']['agent_commission'] == '3'){ ?>selected<?php } ?>" title="商品成本"><span>商品成本</span></label>
            <label for="commission_4" class="cb-disable <?php if($output['setting']['agent_commission'] == '4'){ ?>selected<?php } ?>" title="订单利润"><span>订单利润</span></label>
            <input type="radio" id="commission_1" name="commission" value="1" <?php echo $output['setting']['agent_commission']==1?'checked=checked':''; ?>>
            <input type="radio" id="commission_2" name="commission" value="2" <?php echo $output['setting']['agent_commission']==2?'checked=checked':''; ?>>
            <input type="radio" id="commission_3" name="commission" value="3" <?php echo $output['setting']['agent_commission']==3?'checked=checked':''; ?>>
            <input type="radio" id="commission_4" name="commission" value="4" <?php echo $output['setting']['agent_commission']==4?'checked=checked':''; ?>>
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