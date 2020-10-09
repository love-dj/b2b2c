<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="login-main" style="background-color:#ffffff; height: 476px;">
      <div class="w1200" ><img class='statis publicPicClick' data-which="register" site='2' src="<?php echo MEMBER_TEMPLATES_URL;?>/images/login_openid.jpg"  >
		  <!--sina-->
        <div class="login-wrap">
          <div class="login-hearder">
			<ul class="tabs-nav">
        <li class="j-accountLogin on"><a href="javascript:;">微博登录</a></li>
        <li><a href="index.php" title="跳过该步骤" class="sms_find">跳过该步骤<i></i></a></li>
      </ul>   
			</div>
          <div class="login-content">           
              <div class="account-box" style="display: block">
               <form name="register_form" id="register_form" class="wt-login-form" method="post" action="index.php?w=connect_sina&t=register">
            <input type="hidden" value="ok" name="form_submit">
                <div class="login-form">
                  <div class="input-inline">
                    <div class="input-icon i-user"></div>
					  <input type="text" value="<?php echo $_SESSION['member_name'];?>" id="user" name="user" class="text" readOnly/>
                  </div>
                  <div class="input-inline">
                    <div class="input-icon i-password"></div>
					 <input type="text" value="<?php echo $output['user_passwd'];?>" id="password" name="password" class="text" tipMsg="<?php echo $lang['login_register_password_to_login'];?>"/>
                  </div>
					
					<div class="input-inline">
                    <div class="input-icon i-password"></div>
					 <input type="text" id="email" name="email" class="text" tipMsg="<?php echo $lang['login_register_input_valid_email'];?>"/>
                  </div>
                  <div class="disabled" id="submit-passwd">
				<input type="submit" name="submit" value="<?php echo $lang['login_register_enter_now'];?>" class="login-submit qt-btn btn-green-linear"/>
                  </div>
                </div>
                 </form>
              </div>
			  <div class="login-footer">
              <p class="login-type"><a  site='7' href="<?php echo urlLogin('login', 'index');?>" class="statis" rel="external nofollow">网站首页</a><a  site='8' href="<?php echo urlLogin('login', 'register');?>" class="statis" rel="external nofollow">会员中心</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
<script>
$(function(){
	//初始化Input的灰色提示信息  
	$('input[tipMsg]').inputTipText({pwd:'password'});
    //注册表单验证
        $('#register_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('.input-inline');
            error_td.append(error);
            element.addClass('error');
        },
        success: function(label) {
            label.removeClass('error').find('label').remove();
        },
            rules: {
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                },
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: 'index.php?w=login&t=check_email',
                        type: 'get',
                        data: {
                            email: function() {
                                return $('#email').val();
                            }
                        }
                    }
                }
        },
        messages : {
            password  : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_input_password'];?>',
                minlength: '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_password_range'];?>',
				maxlength: '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_password_range'];?>'
            },
            email : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_input_email'];?>',
                email    : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_invalid_email'];?>',
				remote	 : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_email_exists'];?>'
            }
        }
    });
});
</script>