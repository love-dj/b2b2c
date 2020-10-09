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
        <h3><?php echo $lang['wt_distribution_setting'];?></h3>
        <h5>设置三级分销奖金</h5>
      </div>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="add_form" method="post"  enctype="multipart/form-data">
  <input type="hidden" name="form_submit" value="ok" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="isuse"><?php echo  $lang['distribution_isuse'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="isuse_1" class="cb-enable <?php if($output['setting']['distribution_isuse'] == '1'){ ?>selected<?php } ?>" title="开启"><span>开启</span></label>
            <label for="isuse_0" class="cb-disable <?php if($output['setting']['distribution_isuse'] == '0'){ ?>selected<?php } ?>" title="关闭"><span>关闭</span></label>
            <input type="radio" id="isuse_1" name="isuse" value="1" <?php echo $output['setting']['distribution_isuse']==1?'checked=checked':''; ?>>
            <input type="radio" id="isuse_0" name="isuse" value="0" <?php echo $output['setting']['distribution_isuse']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"><?php echo $lang['distribution_isuse_explain'];?></p>
        </dd>
      </dl>

      <dl class="row">
        <dt class="tit">
          <label for="identity">注册送分销资格</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="identity_1" class="cb-enable <?php if($output['setting']['distribution_identity'] == '1'){ ?>selected<?php } ?>" title="开启"><span>开启 </span></label>
            <label for="identity_0" class="cb-disable <?php if($output['setting']['distribution_identity'] == '0'){ ?>selected<?php } ?>" title="关闭"><span>关闭</span></label>
            <input type="radio" id="identity_1" name="identity" value="1" <?php echo $output['setting']['distribution_identity']==1?'checked=checked':''; ?>>
            <input type="radio" id="identity_0" name="identity" value="0" <?php echo $output['setting']['distribution_identity']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic">开启：用户注册即可获得第一个分销等级身份，后面等级需升级<br>关闭：用户注册不具备分销资格，需要满足条件自动升级</p>
        </dd>
      </dl>

      <dl class="row">
        <dt class="tit">
          <label for="uselevel"> 分销层级</label>
        </dt>
        <dd class="opt">
          <select id="uselevel" name="uselevel" value="8">
            <option value="1" <?php echo $output['setting']['distribution_uselevel']==1?'selected':''; ?>>一级分销</option>
            <option value="2" <?php echo $output['setting']['distribution_uselevel']==2?'selected':''; ?>>二级分销</option>
            <option value="3" <?php echo $output['setting']['distribution_uselevel']==3?'selected':''; ?>>三级分销</option>
          </select>
          <p class="notic">选择N级，奖金就发放N级</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="level_one">一级分销比例</label>
        </dt>
        <dd class="opt">
        <input id="level_one" name="level_one" value="<?php echo $output['setting']['distribution_level_one'];?>" class="s-input-txt" type="text"> %
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="level_two">二级分销比例</label>
        </dt>
        <dd class="opt">
        <input id="level_two" name="level_two" value="<?php echo $output['setting']['distribution_level_two'];?>" class="s-input-txt" type="text"> %
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="level_three">三级分销比例</label>
        </dt>
        <dd class="opt">
        <input id="level_three" name="level_three" value="<?php echo $output['setting']['distribution_level_three'];?>" class="s-input-txt" type="text"> %
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="self_buy">分销自购</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="self_buy_1" class="cb-enable <?php if($output['setting']['distribution_self_buy'] == '1'){ ?>selected<?php } ?>" title="开启"><span>开启</span></label>
            <label for="self_buy_0" class="cb-disable <?php if($output['setting']['distribution_self_buy'] == '0'){ ?>selected<?php } ?>" title="关闭"><span>关闭</span></label>
            <input type="radio" id="self_buy_1" name="self_buy" value="1" <?php echo $output['setting']['distribution_self_buy']==1?'checked=checked':''; ?>>
            <input type="radio" id="self_buy_0" name="self_buy" value="0" <?php echo $output['setting']['distribution_self_buy']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic">开启分销自购，分销商自己购买商品，享受一级佣金，上级享受二级佣金，上上级享受三级佣金</p>
        </dd>
      </dl>

      <h5>结算设置</h5>   
      <dl class="row">
        <dt class="tit">
          <label for="billing_option">佣金计算去向</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="billing_option_1" class="cb-enable <?php if($output['setting']['distribution_billing_option'] == '1'){ ?>selected<?php } ?>" title="允许提现"><span>允许提现</span></label>
            <label for="billing_option_0" class="cb-disable <?php if($output['setting']['distribution_billing_option'] == '0'){ ?>selected<?php } ?>" title="转入积分"><span>转入积分</span></label>
            <input type="radio" id="billing_option_1" name="billing_option" value="1" <?php echo $output['setting']['distribution_billing_option']==1?'checked=checked':''; ?>>
            <input type="radio" id="billing_option_0" name="billing_option" value="0" <?php echo $output['setting']['distribution_billing_option']==0?'checked=checked':''; ?>>
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
            <label for="settlement_event_1" class="cb-enable <?php if($output['setting']['distribution_settlement_event'] == '1'){ ?>selected<?php } ?>" title="订单完成后"><span>订单完成后</span></label>
            <label for="settlement_event_0" class="cb-disable <?php if($output['setting']['distribution_settlement_event'] == '0'){ ?>selected<?php } ?>" title="订单支付后"><span>订单支付后</span></label>
            <input type="radio" id="settlement_event_1" name="settlement_event" value="1" <?php echo $output['setting']['distribution_settlement_event']==1?'checked=checked':''; ?>>
            <input type="radio" id="settlement_event_0" name="settlement_event" value="0" <?php echo $output['setting']['distribution_settlement_event']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic">默认[订单完成后]分销订单进入结算计算(ps:计算结算期)</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="settlement_days">结算天数</label>
        </dt>
        <dd class="opt">
        <input id="settlement_days" name="settlement_days" value="<?php echo $output['setting']['distribution_settlement_days'];?>" class="s-input-txt" type="text"> 天
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
            <label for="commission_1" class="cb-enable <?php if($output['setting']['distribution_commission'] == '1'){ ?>selected<?php } ?>" title="订单实际支付金额"><span>订单实际支付金额</span></label>
            <label for="commission_2" class="cb-midable <?php if($output['setting']['distribution_commission'] == '2'){ ?>selected<?php } ?>" title="商品现价"><span>商品现价</span></label>
            <label for="commission_3" class="cb-midable2 <?php if($output['setting']['distribution_commission'] == '3'){ ?>selected<?php } ?>" title="商品成本"><span>商品成本</span></label>
            <label for="commission_4" class="cb-disable <?php if($output['setting']['distribution_commission'] == '4'){ ?>selected<?php } ?>" title="订单利润"><span>订单利润</span></label>
            <input type="radio" id="commission_1" name="commission" value="1" <?php echo $output['setting']['distribution_commission']==1?'checked=checked':''; ?>>
            <input type="radio" id="commission_2" name="commission" value="2" <?php echo $output['setting']['distribution_commission']==2?'checked=checked':''; ?>>
            <input type="radio" id="commission_3" name="commission" value="3" <?php echo $output['setting']['distribution_commission']==3?'checked=checked':''; ?>>
            <input type="radio" id="commission_4" name="commission" value="4" <?php echo $output['setting']['distribution_commission']==4?'checked=checked':''; ?>>
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