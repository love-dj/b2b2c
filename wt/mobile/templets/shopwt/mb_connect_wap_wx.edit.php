<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>第三方账号登录</h3>
        <h5>设置使用第三方账号在手机客户端中登录</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li>此功能在微信客户端浏览器访问WAP时可用，需在微信公众平台注册帐号，并获得相应的AppID和AppSecret。</li>
      <li>如果启用了PC端的微信登录，因接口返回数据不同会注册不同的会员账号，建议按微信文档操作，<a class="wtap-btn" target="_blank" href="http://mp.weixin.qq.com/wiki/4/9ac2e7b1f1d22e9e57260f6553822520.html">查看</a>。</li>
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
            <label for="wap_weixin_isuse_1" class="cb-enable <?php if($output['list_setting']['wap_weixin_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><span><?php echo $lang['open'];?></span></label>
            <label for="wap_weixin_isuse_0" class="cb-disable <?php if($output['list_setting']['wap_weixin_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><span><?php echo $lang['close'];?></span></label>
            <input type="radio" id="wap_weixin_isuse_1" name="wap_weixin_isuse" value="1" <?php echo $output['list_setting']['wap_weixin_isuse']==1?'checked=checked':''; ?>>
            <input type="radio" id="wap_weixin_isuse_0" name="wap_weixin_isuse" value="0" <?php echo $output['list_setting']['wap_weixin_isuse']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic">启用后支持使用微信帐号来登录</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="wap_weixin_appid">公众号AppID</label>
        </dt>
        <dd class="opt">
          <input id="wap_weixin_appid" name="wap_weixin_appid" value="<?php echo $output['list_setting']['wap_weixin_appid'];?>" class="input-txt" type="text">
          <p class="notic">公众号AppID 开户邮件中可查看</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="wap_weixin_appkey">公众号Appsecret</label>
        </dt>
        <dd class="opt">
          <input id="wap_weixin_appkey" name="wap_weixin_secret" value="<?php echo $output['list_setting']['wap_weixin_secret'];?>" class="input-txt" type="text">
          <p class="notic">公众号Appsecret 开户邮件中可查看。</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" onclick="document.settingForm.submit()"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
