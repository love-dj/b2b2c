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
        <h3><?php echo $lang['wt_shareholder_setting'];?></h3>
        <h5>设置股东分红奖金</h5>
      </div>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="add_form" method="post"  enctype="multipart/form-data">
  <input type="hidden" name="form_submit" value="ok" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="isuse"><?php echo  $lang['shareholder_isuse'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="isuse_1" class="cb-enable <?php if($output['setting']['shareholder_isuse'] == '1'){ ?>selected<?php } ?>" title="开启"><span>开启</span></label>
            <label for="isuse_0" class="cb-disable <?php if($output['setting']['shareholder_isuse'] == '0'){ ?>selected<?php } ?>" title="关闭"><span>关闭</span></label>
            <input type="radio" id="isuse_1" name="isuse" value="1" <?php echo $output['setting']['shareholder_isuse']==1?'checked=checked':''; ?>>
            <input type="radio" id="isuse_0" name="isuse" value="0" <?php echo $output['setting']['shareholder_isuse']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"><?php echo $lang['shareholder_isuse_explain'];?></p>
        </dd>
      </dl>

      <h5>结算设置</h5>

      <dl class="row">
        <dt class="tit">
          <label for="commission">选择返现计算基数</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="commission_1" class="cb-enable <?php if($output['setting']['shareholder_commission'] == '1'){ ?>selected<?php } ?>" title="订单实际支付金额"><span>订单实际支付金额</span></label>
            <label for="commission_2" class="cb-midable <?php if($output['setting']['shareholder_commission'] == '2'){ ?>selected<?php } ?>" title="商品现价"><span>商品现价</span></label>
            <label for="commission_3" class="cb-midable2 <?php if($output['setting']['shareholder_commission'] == '3'){ ?>selected<?php } ?>" title="商品成本"><span>商品成本</span></label>
            <label for="commission_4" class="cb-disable <?php if($output['setting']['shareholder_commission'] == '4'){ ?>selected<?php } ?>" title="订单利润"><span>订单利润</span></label>
            <input type="radio" id="commission_1" name="commission" value="1" <?php echo $output['setting']['shareholder_commission']==1?'checked=checked':''; ?>>
            <input type="radio" id="commission_2" name="commission" value="2" <?php echo $output['setting']['shareholder_commission']==2?'checked=checked':''; ?>>
            <input type="radio" id="commission_3" name="commission" value="3" <?php echo $output['setting']['shareholder_commission']==3?'checked=checked':''; ?>>
            <input type="radio" id="commission_4" name="commission" value="4" <?php echo $output['setting']['shareholder_commission']==4?'checked=checked':''; ?>>
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
            <label for="settlement_event_1" class="cb-enable <?php if($output['setting']['shareholder_settlement_event'] == '1'){ ?>selected<?php } ?>" title="订单完成后"><span>订单完成后</span></label>
            <label for="settlement_event_0" class="cb-disable <?php if($output['setting']['shareholder_settlement_event'] == '0'){ ?>selected<?php } ?>" title="订单支付后"><span>订单支付后</span></label>
            <input type="radio" id="settlement_event_1" name="settlement_event" value="1" <?php echo $output['setting']['shareholder_settlement_event']==1?'checked=checked':''; ?>>
            <input type="radio" id="settlement_event_0" name="settlement_event" value="0" <?php echo $output['setting']['shareholder_settlement_event']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic">默认[订单完成后]分销订单进入结算计算(ps:计算结算期)</p>
        </dd>
      </dl>
	  
      <dl class="row">
        <dt class="tit">
          <label for="rate">股东分红比例</label>
        </dt>
        <dd class="opt">
        <input id="rate" name="rate" value="<?php echo $output['setting']['shareholder_rate'];?>" class="s-input-txt" type="text"> %
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
	  
      <dl class="row">
        <dt class="tit">
          <label for="limit">分红股东人数限制</label>
        </dt>
        <dd class="opt">
        <input id="limit" name="limit" value="<?php echo $output['setting']['shareholder_limit'];?>" class="s-input-txt" type="text"> 个
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
	  
      <dl class="row">
        <dt class="tit">
          <label for="level">分红股东等级限制</label>
        </dt>
        <dd class="opt">
			<select id="level" name="level">
				<?php foreach($output['level_list'] as $level){?>
					<option value="<?=$level['level_weight']?>" <?php if($output['setting']['shareholder_level'] == $level['level_weight']){ ?>selected<?php } ?>><?=$level['level_name']?></option>
				<?php }?>
			</select>
			起
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
	  
      <dl class="row">
        <dt class="tit">
          <label for="times">分红周期</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="return_times_1" class="cb-enable <?php if($output['setting']['shareholder_times'] == '1'){ ?>selected<?php } ?>" title="每天"><span>每天</span></label>
			<label for="return_times_2" class="cb-midable <?php if($output['setting']['shareholder_times'] == '2'){ ?>selected<?php } ?>" title="每周"><span>每周</span></label>
            <label for="return_times_0" class="cb-disable <?php if($output['setting']['shareholder_times'] == '0'){ ?>selected<?php } ?>" title="每月"><span>每月</span></label>
			<input type="radio" id="return_times_2" name="times" value="2" <?php echo $output['setting']['shareholder_times']==2?'checked=checked':''; ?>>
            <input type="radio" id="return_times_1" name="times" value="1" <?php echo $output['setting']['shareholder_times']==1?'checked=checked':''; ?>>
            <input type="radio" id="return_times_0" name="times" value="0" <?php echo $output['setting']['shareholder_times']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
	  

      <div class="bot"><a id="submit" href="javascript:void(0)" class="wtap-btn-big wtap-btn-green">保存</a></div>    
  </div>

  </form>
</div>