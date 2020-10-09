<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="login-main" style="background-color:<?php echo $output['lpic']['color'];?>; height: 476px;">
      <div class="w1200" ><img class='statis publicPicClick' data-which="register" site='2' src="<?php echo $output['lpic']['pic'];?>"  >
		  <!--手机验证码登录-->
        <div class="login-wrap">
          <div class="login-hearder">
			<ul class="tabs-nav">
        <li class="j-accountLogin on"><a href="javascript:;">邮箱找回密码<i></i></a></li>
        <?php if (C('sms_password') == 1){?>
        <li class="j-phoneLogin"><a href="javascript:;" title="手机找回密码" class="sms_find">手机找回密码<i></i></a></li>
        <?php } ?>
      </ul>   
			</div>
          <div class="login-content">
			  
			                
              <div class="account-box" style="display: block">
               <form class="wt-login-form" action="<?php echo urlLogin('login', 'find_password');?>" method="POST" id="find_password_form">
				<?php Security::getToken();?>
				<input type="hidden" name="form_submit" value="ok" />
				<input name="wthash" type="hidden" value="<?php echo getWthash();?>" />
                <div class="login-form">
                  <div class="input-inline">
                    <div class="input-icon i-user"></div>
					  <input name="username" id="username" type="text" autocomplete="off" tipMsg="输入您已注册的用户名">
                  </div>
                  <div class="input-inline">
                    <div class="input-icon i-password"></div>
                    <input type="text" class="text" name="email" tipMsg="输入您已注册的邮箱" />
                  </div>
					
					<?php if(C('captcha_status_login') == '1') { ?>
                  <div class="input-inline captcha account-captcha" >
					<input type="text" name="captcha" autocomplete="off" class="text" style="width: 188px;" tipMsg="<?php echo $lang['login_register_input_code'];?>" id="captcha" size="10" /> 
                    <img  alt="加载中..." id="codeimage" name="codeimage" src="index.php?w=vercode&type=40x100" onclick="javascript:document.getElementById('codeimage').src='index.php?w=vercode&type=40x100&c=' + Math.random();"  style="margin-left: 10px;vertical-align:middle; cursor:pointer;" /><a class="img-code-a" onclick="javascript:document.getElementById('codeimage').src='index.php?w=vercode&type=40x100&c=' + Math.random();"></a>
                    <p class="warning-text"></p>
                  </div>
					<?php } ?>
					
                  <div class="disabled" id="submit-passwd">
				<input type="submit" site='20' class="login-submit qt-btn btn-green-linear" value="重置密码">
                <input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">
                  </div>
                </div>
                 </form>
              </div>
			  
              <?php if (C('sms_login') == 1){?>
			  <div class="phone-box">
          <form id="post_form" method="post" class="wt-login-form" action="<?php echo urlLogin('connect_sms', 'find_password');?>">
            <?php Security::getToken();?>
            <input type="hidden" name="form_submit" value="ok" />
            <input name="wthash" type="hidden" value="<?php echo getWthash();?>" />
              
                <div class="login-form">
                  <div class="input-inline">
                    <div class="input-icon i-phone"></div>
					  <input type="text" class="text" name="phone" id="phone" maxlength="11" autocomplete="off" tipMsg="输入已注册的手机号码" />
                  </div>
                  <div id='sendcode-captcha' class="captcha input-inline" style="display:block">
					<input type="text" name="captcha" class="text" id="image_captcha" tipMsg="<?php echo $lang['login_register_input_code'];?>" style="width: 188px;" />
                    <img alt="加载中..."  name="codeimage" id="sms_codeimage" onclick="javascript:document.getElementById('sms_codeimage').src='index.php?w=vercode&type=40x100&c=' + Math.random();" src="index.php?w=vercode&type=40x100"  style="margin-left: 10px;vertical-align:middle; cursor:pointer;" /><a class="img-code-a" onclick="javascript:document.getElementById('sms_codeimage').src='index.php?w=vercode&type=40x100&c=' + Math.random();"></a>
                    <p class="warning-text"></p>
                  </div>
                  <div class="input-inline captcha">
					  <input type="text" name="sms_captcha" autocomplete="off" class="text" id="sms_captcha" tipMsg="短信验证码" style="width: 188px;" />
                    <div class="btn-captcha qt-btn btn-green"><a class='statis' site='13' href="javascript:void(0);" id="sms_text" onclick="get_sms_captcha('3')">发送验证码</a></div>
                    <p class="warning-text"></p>
                  </div>
				<div class="input-inline">
                    <div class="input-icon i-phone"></div>
					<input type="text" name="password" id="password" class="text" tipMsg="输入新密码" />
                  </div>
					
                  <div class="disabled" id="submit-phone" >
                   <input type="submit" id="submit" class="login-submit qt-btn btn-green-linear " value="确认重置">
                </div>
                </div>
			   </form>
			</div>
              <?php } ?>
			  <div class="login-footer">
              <p class="login-type"><a  site='7' href="<?php echo urlLogin('login', 'index');?>" class="statis" rel="external nofollow">登录</a><a  site='8' href="<?php echo urlLogin('login', 'register');?>" class="statis" rel="external nofollow">注册</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
<script type="text/javascript" src="<?php echo LOGIN_STATIC_SITE_URL;?>/js/login.js"></script>
<script type="text/javascript">
$(function(){
	var cotrs = $(".tabs-nav li");
	cotrs.click(function(){
	 $(this).addClass("on").siblings().removeClass("on");
	});
	//初始化Input的灰色提示信息  
	$('input[tipMsg]').inputTipText({pwd:'password'});
    $('.j-phoneLogin,.j-accountLogin').on('click', function() {
        var oLoginWrap = $(this).closest('.login-wrap');
        oLoginWrap.find('.phone-box,.account-box').hide();
        if ($(this).hasClass('j-phoneLogin')) {
            oLoginWrap.find('.phone-box').show();
        } else {
            oLoginWrap.find('.account-box').show()
        }
    });
	
    $('#Submit').click(function(){
        if($("#find_password_form").valid()){
        	ajaxpost('find_password_form', '', '', 'onerror');
        } else{
        	document.getElementById('codeimage').src='index.php?w=vercode&type=40x100&c=' + Math.random();
        }
    });
    $('#find_password_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('.input-inline');
            error_td.append(error);
            element.addClass('error');
        },
        success: function(label) {
            label.removeClass('error').find('label').remove();
        },
        rules : {
            username : {
                required : true,
				username:true,
            },
            email : {
                required : true,
                email : true
            },
            captcha : {
                required : true,
                minlength: 4,
                remote   : {
                    url : 'index.php?w=vercode&t=check',
                    type: 'get',
                    data:{
                        captcha : function(){
                            return $('#captcha').val();
                        }
                    }
                }
            } 
        },
        messages : {
            username : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_usersave_login_usersave_username_isnull'];?>',
				username : '<i class="icon-exclamation-sign"></i>用户名必须在4-20个字符之间'
            },
            email  : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_password_input_email'];?>',
                email : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_password_wrong_email'];?>'
            },
            captcha : {
                required : '<i class="icon-remove-bbs" title="<?php echo $lang['login_usersave_code_isnull'];?>"></i>',
                minlength : '<i class="icon-remove-bbs" title="<?php echo $lang['login_usersave_wrong_code'];?>"></i>',
                remote   : '<i class="icon-remove-bbs" title="<?php echo $lang['login_usersave_wrong_code'];?>"></i>'
            }
        }
    });
});
</script>
<?php if (C('sms_password') == 1){?>
<script type="text/javascript" src="<?php echo LOGIN_STATIC_SITE_URL;?>/js/connect_sms.js" charset="utf-8"></script> 
<script>
$(function(){
	$("#post_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('.input-inline');
            error_td.append(error);
            element.addClass('error');
        },
        success: function(label) {
            label.removeClass('error').find('label').remove();
        },
    	submitHandler:function(form){
    	    ajaxpost('post_form', '', '', 'onerror');
    	},
        onkeyup: false,
		rules: {
			phone: {
                required : true,
                mobile : true
            },
			captcha : {
                required : true,
                minlength: 4,
                remote   : {
                    url : 'index.php?w=vercode&t=check',
                    type: 'get',
                    data:{
                        captcha : function(){
                            return $('#image_captcha').val();
                        }
                    },
                    complete: function(data) {
                        if(data.responseText == 'false') {
                        	document.getElementById('sms_codeimage').src='index.php?w=vercode&type=40x100&c=' + Math.random();
                        }
                    }
                }
            },
			sms_captcha: {
                required : function(element) {
                    return $("#captcha").val().length == 4;
                },
                minlength: 6
            },
            password : {
				password : true,
                required : function(element) {
                    return $("#sms_captcha").val().length == 6;
                },
                minlength: 6,
				maxlength: 20,
            }
		},
		messages: {
			phone: {
                required : '<i class="icon-exclamation-sign"></i>请输入正确的手机号',
                mobile : '<i class="icon-exclamation-sign"></i>请输入正确的手机号'
            },
			captcha : {
                required : '<i class="icon-remove-bbs" title="请输入正确的验证码"></i>',
                minlength: '<i class="icon-remove-bbs" title="请输入正确的验证码"></i>',
				remote	 : '<i class="icon-remove-bbs" title="请输入正确的验证码"></i>'
            },
			sms_captcha: {
 				required : '<i class="icon-remove-bbs" title="请输入六位短信验证码"></i>',
                minlength: '<i class="icon-remove-bbs" title="请输入六位短信验证码"></i>'
            },
            password  : {
				password: '<i class="icon-exclamation-sign"></i>6-20位数字,字母,下划线',
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_input_password'];?>',
                minlength: '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_password_range'];?>',
				maxlength: '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_password_range'];?>'
            }
		}
	});
});
</script>
<?php } ?>
