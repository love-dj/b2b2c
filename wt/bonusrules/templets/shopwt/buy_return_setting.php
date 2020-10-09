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
        <h3><?php echo $lang['wt_buy_return_setting'];?></h3>
        <h5>设置单品消费返现奖金</h5>
      </div>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="add_form" method="post"  enctype="multipart/form-data">
  <input type="hidden" name="form_submit" value="ok" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="isuse"><?php echo  $lang['buy_return_isuse'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="isuse_1" class="cb-enable <?php if($output['setting']['buy_return_isuse'] == '1'){ ?>selected<?php } ?>" title="开启"><span>开启</span></label>
            <label for="isuse_0" class="cb-disable <?php if($output['setting']['buy_return_isuse'] == '0'){ ?>selected<?php } ?>" title="关闭"><span>关闭</span></label>
            <input type="radio" id="isuse_1" name="isuse" value="1" <?php echo $output['setting']['buy_return_isuse']==1?'checked=checked':''; ?>>
            <input type="radio" id="isuse_0" name="isuse" value="0" <?php echo $output['setting']['buy_return_isuse']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"><?php echo $lang['buy_return_isuse_explain'];?></p>
        </dd>
      </dl>

      <h5>结算设置</h5>  

      <dl class="row">
        <dt class="tit">
          <label for="type">返现方式</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="return_type_1" class="cb-enable <?php if($output['setting']['buy_return_type'] == '1'){ ?>selected<?php } ?>" title="昨天"><span>等额返现</span></label>
            <label for="return_type_0" class="cb-disable <?php if($output['setting']['buy_return_type'] == '0'){ ?>selected<?php } ?>" title="上个月"><span>递减返现</span></label>
            <input type="radio" id="return_type_1" name="type" value="1" <?php echo $output['setting']['buy_return_type']==1?'checked=checked':''; ?>>
            <input type="radio" id="return_type_0" name="type" value="0" <?php echo $output['setting']['buy_return_type']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
	  
      <dl class="row">
        <dt class="tit">
          <label for="commission">选择返现计算基数</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="commission_1" class="cb-enable <?php if($output['setting']['buy_return_commission'] == '1'){ ?>selected<?php } ?>" title="订单实际支付金额"><span>订单实际支付金额</span></label>
            <label for="commission_2" class="cb-midable <?php if($output['setting']['buy_return_commission'] == '2'){ ?>selected<?php } ?>" title="商品现价"><span>商品现价</span></label>
            <label for="commission_3" class="cb-midable2 <?php if($output['setting']['buy_return_commission'] == '3'){ ?>selected<?php } ?>" title="商品成本"><span>商品成本</span></label>
            <label for="commission_4" class="cb-disable <?php if($output['setting']['buy_return_commission'] == '4'){ ?>selected<?php } ?>" title="订单利润"><span>订单利润</span></label>
            <input type="radio" id="commission_1" name="commission" value="1" <?php echo $output['setting']['buy_return_commission']==1?'checked=checked':''; ?>>
            <input type="radio" id="commission_2" name="commission" value="2" <?php echo $output['setting']['buy_return_commission']==2?'checked=checked':''; ?>>
            <input type="radio" id="commission_3" name="commission" value="3" <?php echo $output['setting']['buy_return_commission']==3?'checked=checked':''; ?>>
            <input type="radio" id="commission_4" name="commission" value="4" <?php echo $output['setting']['buy_return_commission']==4?'checked=checked':''; ?>>
          </div>
          <p class="notic">===返现计算方式说明===<br>根据以上选择的项目为基数来计算单品消费返现</p>   
        </dd>     
      </dl>	

      <dl class="row">
        <dt class="tit">
          <label for="settlement_event">结算事件</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="settlement_event_1" class="cb-enable <?php if($output['setting']['buy_return_settlement_event'] == '1'){ ?>selected<?php } ?>" title="订单完成后"><span>订单完成后</span></label>
            <label for="settlement_event_0" class="cb-disable <?php if($output['setting']['buy_return_settlement_event'] == '0'){ ?>selected<?php } ?>" title="订单支付后"><span>订单支付后</span></label>
            <input type="radio" id="settlement_event_1" name="settlement_event" value="1" <?php echo $output['setting']['buy_return_settlement_event']==1?'checked=checked':''; ?>>
            <input type="radio" id="settlement_event_0" name="settlement_event" value="0" <?php echo $output['setting']['buy_return_settlement_event']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic">默认[订单完成后]分销订单进入结算计算(ps:计算结算期)</p>
        </dd>
      </dl>
	  
      <dl class="row">
        <dt class="tit">
          <label for="rate">默认单品返现比例</label>
        </dt>
        <dd class="opt">
        <input id="rate" name="rate" value="<?php echo $output['setting']['buy_return_rate'];?>" class="s-input-txt" type="text"> %
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
	  
      <dl class="row">
        <dt class="tit">
          <label for="each_rate">每期返现比例</label>
        </dt>
        <dd class="opt">
        <input id="each_rate" name="each_rate" value="<?php echo $output['setting']['buy_return_each_rate'];?>" class="s-input-txt" type="text"> %
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
	  
      <dl class="row">
        <dt class="tit">
          <label for="times">返现时间</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="return_times_1" class="cb-enable <?php if($output['setting']['buy_return_times'] == '1'){ ?>selected<?php } ?>" title="每天"><span>每天</span></label>
			<label for="return_times_2" class="cb-midable <?php if($output['setting']['buy_return_times'] == '2'){ ?>selected<?php } ?>" title="每周"><span>每周</span></label>
            <label for="return_times_0" class="cb-disable <?php if($output['setting']['buy_return_times'] == '0'){ ?>selected<?php } ?>" title="每月"><span>每月</span></label>
			<input type="radio" id="return_times_2" name="times" value="2" <?php echo $output['setting']['buy_return_times']==2?'checked=checked':''; ?>>
            <input type="radio" id="return_times_1" name="times" value="1" <?php echo $output['setting']['buy_return_times']==1?'checked=checked':''; ?>>
            <input type="radio" id="return_times_0" name="times" value="0" <?php echo $output['setting']['buy_return_times']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
	  
      <dl class="row">
        <dt class="tit">
          <label for="settlement_days">结算天数</label>
        </dt>
        <dd class="opt">
        <input id="settlement_days" name="settlement_days" value="<?php echo $output['setting']['buy_return_settlement_days'];?>" class="s-input-txt" type="text"> 天
          <span class="err"></span>
          <p class="notic">当结算事件发生N天后，佣金才可以结算</p>
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