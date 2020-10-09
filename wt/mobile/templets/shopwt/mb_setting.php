<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>手机端设置</h3>
        <h5>手机端的相关设置</h5>
      </div>
    </div>
  </div>
  <form method="post" name="settingForm" id="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">微信自动登录</dt>
        <dd class="opt">
          <div class="onoff">
            <label for="wap_weixin_auto_1" class="cb-enable <?php if($output['list_setting']['wap_weixin_auto'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><?php echo $lang['open'];?></label>
            <label for="wap_weixin_auto_0" class="cb-disable <?php if($output['list_setting']['wap_weixin_auto'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><?php echo $lang['close'];?></label>
            <input id="wap_weixin_auto_1" name="wap_weixin_auto" <?php if($output['list_setting']['wap_weixin_auto'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="wap_weixin_auto_0" name="wap_weixin_auto" <?php if($output['list_setting']['wap_weixin_auto'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic">WAP手机版，是否开启自动登录功能</p>
        </dd>
      </dl>
		
      <dl class="row">
        <dt class="tit">会员签到</dt>
        <dd class="opt">
          <div class="onoff">
            <label for="signin_isuse_1" class="cb-enable <?php if($output['list_setting']['signin_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><?php echo $lang['open'];?></label>
            <label for="signin_isuse_0" class="cb-disable <?php if($output['list_setting']['signin_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><?php echo $lang['close'];?></label>
            <input id="signin_isuse_1" name="signin_isuse" <?php if($output['list_setting']['signin_isuse'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="signin_isuse_0" name="signin_isuse" <?php if($output['list_setting']['signin_isuse'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic">签到启用后，会员将可以通过移动端商城签到获取积分</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">签到送积分</dt>
        <dd class="opt">
          <input id="points_signin" name="points_signin" value="<?php echo $output['list_setting']['points_signin'];?>" class="input-txt" type="text">
          <p class="notic">例:设置为5，表明签到一次赠送5积分</p>
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
