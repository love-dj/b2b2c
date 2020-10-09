<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
		<div class="subject">
		<h3>微信公众号消息通知</h3>
		<h5>公众号向会员发送重要的服务通知</h5>
		</div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
		<ul>
		<li>此功能使用微信公众号接口，需在注册并认证“服务号”，并获得模板消息的使用权限。</li>
		<li>在公众平台模板库中按照“IT科技 互联网|电子商务”的行业来选择模板，<a class="wtap-btn" target="_blank" href="https://mp.weixin.qq.com/wiki">微信公众平台文档</a>。</li>
		<li>因微信对通知内容要求较严格，不支持营销、到期提醒类消息推送，详细规则<a class="wtap-btn" target="_blank" href="https://mp.weixin.qq.com/wiki/2/def71e3ecb5706c132229ae505815966.html">查看</a>。</li>
		</ul>
  </div>
  <form method="post" name="settingForm">
<input type="hidden" name="form_submit" value="ok" />
<div class="wtap-form-default">
<dl class="row">
<dt class="tit">
<label>是否启用微信通知功能</label>
</dt>
<dd class="opt">
          <div class="onoff">
            <label for="weixin_mp_isuse_1" class="cb-enable <?php if($output['list_setting']['weixin_mp_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><?php echo $lang['open'];?></label>
            <label for="weixin_mp_isuse_0" class="cb-disable <?php if($output['list_setting']['weixin_mp_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><?php echo $lang['close'];?></label>
            <input id="weixin_mp_isuse_1" name="weixin_mp_isuse" <?php if($output['list_setting']['weixin_mp_isuse'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="weixin_mp_isuse_0" name="weixin_mp_isuse" <?php if($output['list_setting']['weixin_mp_isuse'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
<p class="notic">使用微信关注公众号，与商城会员帐号绑定后就可以在微信客户端收到相关通知。</p>
</dd>
</dl>
<dl class="row">
<dt class="tit">
<label for="weixin_mp_appid">公众号AppID</label>
</dt>
<dd class="opt">
<input id="weixin_mp_appid" readonly="readonly" name="weixin_mp_appid" value="<?php echo $output['list_setting']['weixin_mp_appid'];?>" class="input-txt" type="text">
<p class="notic">公众号AppID 开户邮件中可查看或到微信公众号里查看。</p>
</dd>
</dl>
<dl class="row">
<dt class="tit">
<label for="weixin_mp_appsecret">公众号Appsecret</label>
</dt>
<dd class="opt">
<input id="weixin_mp_appsecret" readonly="readonly" name="weixin_mp_appsecret" value="<?php echo $output['list_setting']['weixin_mp_appsecret'];?>" class="input-txt" type="text">
<p class="notic">公众号Appsecret 开户邮件中可查看或到微信公众号里查看。</p>
</dd>
</dl>
<!--
<dl class="row">
<dt class="tit">
<label>服务器地址</label>
</dt>
<dd class="opt"><?php echo MOBILE_SITE_URL . '/index.php?w=weixin';?> <p class="notic">微信公众平台-开发-基本设置-服务器配置-服务器地址(URL)填写。</p>
</dd>
</dl>
-->
<dl class="row">
<dt class="tit">
<label for="weixin_mp_token">Token</label>
</dt>
<dd class="opt">
<input id="weixin_mp_token" name="weixin_mp_token" readonly="readonly" value="<?php echo $output['list_setting']['weixin_mp_token'];?>" class="input-txt" type="text">
<p class="notic">令牌(Token)，与微信公众号平台验证要一致，路经：微信公众平台-开发-基本设置-服务器配置-令牌(Token)填写</p>
</dd>
</dl>
<div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" onclick="document.settingForm.submit()">确认提交</a></div>
</div>
</form>
</div>
