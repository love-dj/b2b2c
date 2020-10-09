<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['account_syn'];?></h3>
        <h5><?php echo $lang['account_syn_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li>启用前需在微信开放平台注册开发者帐号，创建网站应用并获得相应的AppID和AppSecret。</li>
      <li>网站应用的微信登录，通过微信扫描二维码来实现。</li>
    </ul>
  </div>
  <form method="post" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label>是否启用微信登录功能</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="weixin_isuse_1" class="cb-enable <?php if($output['list_setting']['weixin_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><span><?php echo $lang['open'];?></span></label>
            <label for="weixin_isuse_0" class="cb-disable <?php if($output['list_setting']['weixin_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><span><?php echo $lang['close'];?></span></label>
            <input type="radio" id="weixin_isuse_1" name="weixin_isuse" value="1" <?php echo $output['list_setting']['weixin_isuse']==1?'checked=checked':''; ?>>
            <input type="radio" id="weixin_isuse_0" name="weixin_isuse" value="0" <?php echo $output['list_setting']['weixin_isuse']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic">启用后支持使用微信帐号来登录</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="weixin_appid">应用标识AppID</label>
        </dt>
        <dd class="opt">
          <input id="weixin_appid" name="weixin_appid" value="<?php echo $output['list_setting']['weixin_appid'];?>" class="input-txt" type="text">
          <p class="notic">应用标识AppID，在 微信开放平台-网站应用 里得到</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="weixin_appkey">应用密钥AppSecret</label>
        </dt>
        <dd class="opt">
          <input id="weixin_appkey" name="weixin_secret" value="<?php echo $output['list_setting']['weixin_secret'];?>" class="input-txt" type="text">
          <p class="notic">应用标识AppSecret，在 微信开放平台-网站应用 里得到</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" onclick="document.settingForm.submit()"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
