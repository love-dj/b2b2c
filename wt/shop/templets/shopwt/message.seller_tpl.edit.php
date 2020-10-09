<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?w=message&t=seller_tpl" title="返回商家消息模板列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['wt_message_set'];?> - <?php echo $lang['wt_edit'];?>商家消息模板“<?php echo $output['smtpl_info']['smt_name'];?>”</h3>
        <h5><?php echo $lang['wt_message_set_subhead'];?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li>平台可给商家提供站内信、短消息、邮件三种通知方式。平台可以选择开启一种或多种通知方式供商家选择。</li>
      <li>开启强制接收后，商家不能取消该方式通知的接收。</li>
      <li>短消息、邮件需要商家设置正确号码后才能正常接收。</li>
    </ul>
  </div>
  <div class="homepage-focus" wttype="sellerTplContent">
    <div class="title">
      <h3>消息模板切换选择</h3>
      <ul class="tab-base wt-row">
        <li><a href="javascript:void(0);" class="current">站内信模板</a></li>
        <li><a href="javascript:void(0);">手机短信模板</a></li>
        <li><a href="javascript:void(0);">邮件模板</a></li>
      </ul>
    </div>
    <!-- 站内信 S -->
    <form class="tab-content" method="post" name="message_form" >
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="code" value="<?php echo $output['smtpl_info']['smt_code'];?>" />
      <input type="hidden" name="type" value="message" />
      <div class="wtap-form-default">
        <dl class="row">
          <dt class="tit">
            <label>站内信开关</label>
          </dt>
          <dd class="opt">
            <div class="onoff">
              <label for="message_switch1" class="cb-enable <?php if($output['smtpl_info']['smt_message_switch'] == 1){?>selected<?php }?>"><?php echo $lang['open'];?></label>
              <label for="message_switch0" class="cb-disable <?php if($output['smtpl_info']['smt_message_switch'] == 0){?>selected<?php }?>"><?php echo $lang['close'];?></label>
              <input id="message_switch1" name="message_switch" <?php if($output['smtpl_info']['smt_message_switch'] == 1){?>checked="checked"<?php }?> value="1" type="radio">
              <input id="message_switch0" name="message_switch" <?php if($output['smtpl_info']['smt_message_switch'] == 0){?>checked="checked"<?php }?> value="0" type="radio">
            </div>
            <p class="notic"> </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label>强制接收</label>
          </dt>
          <dd class="opt">
            <div class="onoff">
              <label for="message_forced1" class="cb-enable <?php if($output['smtpl_info']['smt_message_forced'] == 1){?>selected<?php }?>"><?php echo $lang['wt_yes'];?></label>
              <label for="message_forced0" class="cb-disable <?php if($output['smtpl_info']['smt_message_forced'] == 0){?>selected<?php }?>"><?php echo $lang['wt_no'];?></label>
              <input id="message_forced1" name="message_forced" <?php if($output['smtpl_info']['smt_message_forced'] == 1){?>checked="checked"<?php }?> value="1" type="radio">
              <input id="message_forced0" name="message_forced" <?php if($output['smtpl_info']['smt_message_forced'] == 0){?>checked="checked"<?php }?> value="0" type="radio">
            </div>
            <p class="notic"> </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label>消息内容</label>
          </dt>
          <dd class="opt">
            <textarea name="message_content" rows="6" class="tarea"><?php echo $output['smtpl_info']['smt_message_content'];?></textarea>
            <span class="err"></span>
            <p class="notic"> </p>
          </dd>
        </dl>
        <div class="bot"> <a href="JavaScript:void(0);" onclick="document.message_form.submit();" class="wtap-btn-big wtap-btn-green"><?php echo $lang['wt_submit'];?></a></div>
      </div>
    </form>
    <!-- 站内信 E --> 
    <!-- 短消息 S -->
    <form class="tab-content" method="post" name="short_name" style="display:none;">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="code" value="<?php echo $output['smtpl_info']['smt_code'];?>" />
      <input type="hidden" name="type" value="short" />
      <div class="wtap-form-default">
        <dl class="row">
          <dt class="tit">
            <label>手机短信开关</label>
          </dt>
          <dd class="opt">
            <div class="onoff">
              <label for="short_switch1" class="cb-enable <?php if($output['smtpl_info']['smt_short_switch'] == 1){?>selected<?php }?>"><?php echo $lang['open'];?></label>
              <label for="short_switch0" class="cb-disable <?php if($output['smtpl_info']['smt_short_switch'] == 0){?>selected<?php }?>"><?php echo $lang['close'];?></label>
              <input id="short_switch1" name="short_switch" <?php if($output['smtpl_info']['smt_short_switch'] == 1){?>checked="checked"<?php }?> value="1" type="radio">
              <input id="short_switch0" name="short_switch" <?php if($output['smtpl_info']['smt_short_switch'] == 0){?>checked="checked"<?php }?> value="0" type="radio">
            </div>
            <p class="notic"> </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label>强制接收</label>
          </dt>
          <dd class="opt">
            <div class="onoff">
              <label for="short_forced1" class="cb-enable <?php if($output['smtpl_info']['smt_short_forced'] == 1){?>selected<?php }?>"><?php echo $lang['wt_yes'];?></label>
              <label for="short_forced0" class="cb-disable <?php if($output['smtpl_info']['smt_short_forced'] == 0){?>selected<?php }?>"><?php echo $lang['wt_no'];?></label>
              <input id="short_forced1" name="short_forced" <?php if($output['smtpl_info']['smt_short_forced'] == 1){?>checked="checked"<?php }?> value="1" type="radio">
              <input id="short_forced0" name="short_forced" <?php if($output['smtpl_info']['smt_short_forced'] == 0){?>checked="checked"<?php }?> value="0" type="radio">
            </div>
            <p class="notic"> </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label>消息内容</label>
          </dt>
          <dd class="opt">
            <textarea name="short_content" rows="6" class="tarea"><?php echo $output['smtpl_info']['smt_short_content'];?></textarea>
            <span class="err"></span>
            <p class="notic"> </p>
          </dd>
        </dl>
		  <dl class="row">
        <dt class="tit">
          <label>短信后台模版CODE(用于阿里短信)</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['smtpl_info']['apicodeid'];?>" name="apicodeid" class="input-txt">
        </dd>
      </dl>
        <div class="bot"> <a href="JavaScript:void(0);" onclick="document.short_name.submit();" class="wtap-btn-big wtap-btn-green"><?php echo $lang['wt_submit'];?></a></div>
      </div>
    </form>
    <!-- 短消息 E --> 
    <!-- 邮件 S -->
    <form class="tab-content" method="post" name="mail_form" style="display:none;">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="code" value="<?php echo $output['smtpl_info']['smt_code'];?>" />
      <input type="hidden" name="type" value="mail" />
      <div class="wtap-form-default">
        <dl class="row">
          <dt class="tit">
            <label>邮件开关</label>
          </dt>
          <dd class="opt">
            <div class="onoff">
              <label for="mail_switch1" class="cb-enable <?php if($output['smtpl_info']['smt_mail_switch'] == 1){?>selected<?php }?>"><?php echo $lang['open'];?></label>
              <label for="mail_switch0" class="cb-disable <?php if($output['smtpl_info']['smt_mail_switch'] == 0){?>selected<?php }?>"><?php echo $lang['close'];?></label>
              <input id="mail_switch1" name="mail_switch" <?php if($output['smtpl_info']['smt_mail_switch'] == 1){?>checked="checked"<?php }?> value="1" type="radio">
              <input id="mail_switch0" name="mail_switch" <?php if($output['smtpl_info']['smt_mail_switch'] == 0){?>checked="checked"<?php }?> value="0" type="radio">
            </div>
            <p class="notic"> </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label>强制接收</label>
          </dt>
          <dd class="opt">
            <div class="onoff">
              <label for="mail_forced1" class="cb-enable <?php if($output['smtpl_info']['smt_mail_forced'] == 1){?>selected<?php }?>"><?php echo $lang['wt_yes'];?></label>
              <label for="mail_forced0" class="cb-disable <?php if($output['smtpl_info']['smt_mail_forced'] == 0){?>selected<?php }?>"><?php echo $lang['wt_no'];?></label>
              <input id="mail_forced1" name="mail_forced" <?php if($output['smtpl_info']['smt_mail_forced'] == 1){?>checked="checked"<?php }?> value="1" type="radio">
              <input id="mail_forced0" name="mail_forced" <?php if($output['smtpl_info']['smt_mail_forced'] == 0){?>checked="checked"<?php }?> value="0" type="radio">
            </div>
            <p class="notic"> </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label>邮件标题</label>
          </dt>
          <dd class="opt">
            <textarea name="mail_subject" rows="6" class="tarea"><?php echo $output['smtpl_info']['smt_mail_subject'];?></textarea>
            <span class="err"></span>
            <p class="notic"> </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label>邮件内容</label>
          </dt>
          <dd class="opt">
            <?php showEditor('mail_content', $output['smtpl_info']['smt_mail_content']);?>
          </dd>
          </p>
          </dd>
        </dl>
        <div class="bot"> <a href="JavaScript:void(0);" onclick="document.mail_form.submit();" class="wtap-btn-big wtap-btn-green"><?php echo $lang['wt_submit'];?></a> </div>
      </div>
    </form>
    <!-- 邮件 E --> 
  </div>
</div>
<script>
$(function(){
    $('div[wttype="sellerTplContent"] > .title > ul').find('a').click(function(){
        $(this).addClass('current').parent().siblings().find('a').removeClass('current');
        var _index = $(this).parent().index();
        var _form = $('div[wttype="sellerTplContent"]').find('form');
        _form.hide();
        _form.eq(_index).show();
    });
});
</script>