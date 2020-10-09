<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  
  <div class="wtm-default-form">
    <form method="post" id="password_form" name="password_form" action="<?php echo MEMBER_SITE_URL;?>/index.php?w=member_security&t=modify_pwd">
      <input type="hidden" name="form_submit" value="ok"  />
      <dl>
        <dt><i class="required">*</i>设置新密码：</dt>
        <dd>
          <input type="password"  maxlength="40" class="password" name="password" id="password"/>
          <label for="password" generated="true" class="error"></label>
          <p class="hint">6-20位字符，可由英文、数字及标点符号组成。</p></dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>确认新密码：</dt>
        <dd>
          <input type="password" maxlength="40" class="password" name="confirm_password" id="confirm_password" />
          <label for="confirm_password" generated="true" class="error"></label>
        </dd>
      </dl>
      <dl class="bottom">
        <dt>&nbsp;</dt>
        <dd><label class="submit-border">
          <input type="submit" class="submit" value="确认提交" /></label>
        </dd>
      </dl>
    </form>
  </div>
</div>
<script type="text/javascript">
$(function(){
    $('#password_form').validate({
         submitHandler:function(form){
            ajaxpost('password_form', '', '', 'onerror') 
        },
        rules : {
            password : {
                required   : true,
                minlength  : 6,
                maxlength  : 20
            },
            confirm_password : {
                required   : true,
                equalTo    : '#password'
            }
        },
        messages : {
            password  : {
                required  : '<i class="icon-exclamation-sign"></i>请正确输入密码',
                minlength : '<i class="icon-exclamation-sign"></i>请正确输入密码',
                maxlength : '<i class="icon-exclamation-sign"></i>请正确输入密码'
            },
            confirm_password : {
                required   : '<i class="icon-exclamation-sign"></i>请正确输入密码',
                equalTo    : '<i class="icon-exclamation-sign"></i>两次密码输入不一致'
            }
        }
    });
});
</script> 
