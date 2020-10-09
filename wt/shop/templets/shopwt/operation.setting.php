<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_operation_set']?></h3>
        <h5><?php echo $lang['wt_operation_set_subhead']?></h5>
      </div>
    </div>
  </div>
  <form method="post" name="settingForm" id="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit"><?php echo $lang['robbuy_allow'];?></dt>
        <dd class="opt">
          <div class="onoff">
            <label for="robbuy_allow_1" class="cb-enable <?php if($output['list_setting']['robbuy_allow'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><?php echo $lang['open'];?></label>
            <label for="robbuy_allow_0" class="cb-disable <?php if($output['list_setting']['robbuy_allow'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><?php echo $lang['close'];?></label>
            <input id="robbuy_allow_1" name="robbuy_allow" <?php if($output['list_setting']['robbuy_allow'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="robbuy_allow_0" name="robbuy_allow" <?php if($output['list_setting']['robbuy_allow'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic"><?php echo $lang['robbuy_isuse_notice'];?></p>
        </dd>
      </dl>
      <!-- 促销开启 -->
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['sale_allow'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="sale_allow_1" class="cb-enable <?php if($output['list_setting']['sale_allow'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><?php echo $lang['open'];?></label>
            <label for="sale_allow_0" class="cb-disable <?php if($output['list_setting']['sale_allow'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><?php echo $lang['close'];?></label>
            <input type="radio" id="sale_allow_1" name="sale_allow" value="1" <?php echo $output['list_setting']['sale_allow'] ==1?'checked=checked':''; ?>>
            <input type="radio" id="sale_allow_0" name="sale_allow" value="0" <?php echo $output['list_setting']['sale_allow'] ==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"><?php echo $lang['sale_notice'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['open_points_isuse'];?></dt>
        <dd class="opt">
          <div class="onoff">
            <label for="points_isuse_1" class="cb-enable <?php if($output['list_setting']['pointscenter_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['wt_open'];?>"><span><?php echo $lang['wt_open'];?></span></label>
            <label for="points_isuse_0" class="cb-disable <?php if($output['list_setting']['pointscenter_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['wt_close'];?>"><span><?php echo $lang['wt_close'];?></span></label>
            <input id="points_isuse_1" name="pointscenter_isuse" <?php if($output['list_setting']['pointscenter_isuse'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="points_isuse_0" name="pointscenter_isuse" <?php if($output['list_setting']['pointscenter_isuse'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic"><?php echo sprintf($lang['open_points_isuse_notice'],"index.php?w=setting&t=points_setting");?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['open_pointprod_isuse'];?></dt>
        <dd class="opt">
          <div class="onoff">
            <label for="pointprod_isuse_1" class="cb-enable <?php if($output['list_setting']['pointprod_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><?php echo $lang['open'];?></label>
            <label for="pointprod_isuse_0" class="cb-disable <?php if($output['list_setting']['pointprod_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><?php echo $lang['close'];?></label>
            <input id="pointprod_isuse_1" name="pointprod_isuse" <?php if($output['list_setting']['pointprod_isuse'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="pointprod_isuse_0" name="pointprod_isuse" <?php if($output['list_setting']['pointprod_isuse'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic"><?php echo $lang['open_pointprod_isuse_notice'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['voucher_allow'];?></dt>
        <dd class="opt">
          <div class="onoff">
            <label for="voucher_allow_1" class="cb-enable <?php if($output['list_setting']['voucher_allow'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><?php echo $lang['open'];?></label>
            <label for="voucher_allow_0" class="cb-disable <?php if($output['list_setting']['voucher_allow'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><?php echo $lang['close'];?></label>
            <input id="voucher_allow_1" name="voucher_allow" <?php if($output['list_setting']['voucher_allow'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="voucher_allow_0" name="voucher_allow" <?php if($output['list_setting']['voucher_allow'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic"><?php echo $lang['voucher_allow_notice'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">平台优惠券</dt>
        <dd class="opt">
          <div class="onoff">
            <label for="coupon_allow_1" class="cb-enable <?php if($output['list_setting']['coupon_allow'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><?php echo $lang['open'];?></label>
            <label for="coupon_allow_0" class="cb-disable <?php if($output['list_setting']['coupon_allow'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><?php echo $lang['close'];?></label>
            <input id="coupon_allow_1" name="coupon_allow" <?php if($output['list_setting']['coupon_allow'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="coupon_allow_0" name="coupon_allow" <?php if($output['list_setting']['coupon_allow'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic">平台优惠券开启后，可以在后台“平台优惠券”功能中发布优惠券，会员通过相应的方式领取优惠券，在下单时选择使用拥有的优惠券，从而得到优惠</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="submitBtn"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
$(function(){$("#submitBtn").click(function(){
    if($("#settingForm").valid()){
     $("#settingForm").submit();
	}
	});
});
</script>
