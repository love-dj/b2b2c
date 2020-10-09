<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
    <div class="login-main" style="background-color:<?php echo $output['lpic']['color'];?>; height: 476px;">
      <div class="w1200" ><a href="<?php echo $output['lpic']['url'];?>" target="_blank"><img data-which="register" site='2' src="<?php echo $output['lpic']['pic'];?>" /></a>
		  <!--注册-->
        <div class="login-wrap">
          <div class="login-hearder"><a  class='statis site-change' site='3' href="<?php echo urlLogin('login', 'index', array('ref_url' => $_GET['ref_url']));?>" >
            <div class="header-btn"> 登录 </div>
            </a>
            <div class="header-btn on"> 注册 </div>
          </div>
          <div class="login-content">
            <div class="login-others">
            <?php if (C('qq_isuse') == 1 || C('sina_isuse') == 1 || C('weixin_isuse') == 1){?>
            <?php if (C('qq_isuse') == 1){?>
            <a id="statis-qq" class='statis other-logo logo-qq' site='4'  href="<?php echo MEMBER_SITE_URL;?>/index.php?w=connect_qq" ><i></i>&nbsp; <span>QQ注册</span></a>
            <?php } ?>
             <?php if (C('weixin_isuse') == 1){?>
            <a id='statis-wx' site='5' class='statis other-logo logo-wechat' href="javascript:void(0);" onclick="ajax_form('weixin_form', '微信账号注册', '<?php echo urlLogin('connect_wx', 'index');?>', 360);"><i></i>&nbsp; <span>微信注册</span></a>			 <?php } ?>	
             <?php if (C('sina_isuse') == 1){?>
            <a id='statis-sina' site='6' class='statis other-logo logo-weibo'  href="<?php echo MEMBER_SITE_URL;?>/index.php?w=connect_sina" ><i></i>&nbsp; <span>微博注册</span></a>
             <?php } ?>
 			 <?php } ?> 
            </div>
            <div class="login-box">
              <p class="cutting-line"><span>或</span></p>
			<?php if (C('sms_register') == 1){?>	
			<form id="post_form" method="post" class="wt-login-form">
                <?php Security::getToken();?>
                <input type="hidden" name="form_submit" value="ok" />
                <input name="wthash" type="hidden" value="<?php echo getWthash();?>" />
				<div class="phone-box">
              <div class="login-form">
                <div class="input-inline">
                  <div class="input-icon i-phone"></div>
                  <input name="phone" id="phone" type="text" maxlength="11" autocomplete="off" tipMsg="请输入手机号码">
                </div>
                <div class="input-inline captcha">
                  <input name="captcha" id="image_captcha" type="text" autocomplete="off" tipMsg="<?php echo $lang['login_register_input_code'];?>" value="" maxlength="6" style="width: 188px;"  />
                  <img alt="加载中..." name="codeimage" id="sms_codeimage" onclick="javascript:document.getElementById('sms_codeimage').src='index.php?w=vercode&type=40x100&c=' + Math.random();" src="index.php?w=vercode&type=40x100"  style="margin-left: 10px;vertical-align: middle; cursor:pointer;" /><a class="img-code-a" href="javascript:void(0);" onclick="javascript:document.getElementById('sms_codeimage').src='index.php?w=vercode&type=40x100&c=' + Math.random();"></a>
                  <p class="warning-text"></p>
                </div>
                <div class="input-inline captcha">
                  <input id="sms_captcha" name="sms_captcha" type="text" style="width:  188px;" maxlength="6" autocomplete="off"  tipMsg="输入6位短信验证码">
                  <div class="btn-captcha qt-btn btn-green"><a id="sms_text" class='statis' site='12' href="javascript:void(0);" onclick="get_sms_captcha('1')">发送验证码</a></div>
                  <p class="warning-text"></p>
                </div>
                <div id="submit-phone">
				  <input type="button" id="submitBtn" site='13' class="login-submit qt-btn btn-green-linear" value="下一步">
				  </div>
				  <div class="login-switch clearfix"><a href="#"  site='15' class="statis fl j-accountLogin">使用邮箱注册</a><a href="<?php echo urlLogin('login', 'forget_password');?>"  site='22' class="statis fr">忘记密码？</a></div>
              </div>
				</div>
				</form>
				<form style="display: none;" id="register_sms_form" class="wt-login-form" method="post" action="<?php echo urlLogin('connect_sms', 'register');?>">
                <input type="hidden" name="form_submit" value="ok" />
                <input type="hidden" name="register_captcha" id="register_sms_captcha" value="" />
                <input type="hidden" name="register_phone" id="register_phone" value="" />
				<div class="login-form">
				<div class="input-inline">
                  <div class="input-icon i-user"></div>
                  <input name="member_name" id="member_name" type="text" autocomplete="off" tipMsg="系统生成随机用户名,可另行修改一次">
                  <p class="warning-text"></p>
                </div>
				<div class="input-inline">
                    <div class="input-icon i-password"></div>
                    <input id="sms_password" name="password" type="password" autocomplete="off" tipMsg="系统生成随机密码，请牢记或修改为自设密码">
                    <p class="warning-text"></p>
                  </div>
					
				<div class="input-inline">
                    <div class="input-icon i-password"></div>
                    <input id="sms_email" name="email" type="text" autocomplete="off" tipMsg="<?php echo $lang['login_register_input_valid_email'];?>">
                    <p class="warning-text"></p>
                  </div>
				<div id="submit-passwd" class="disabled">
				<input type="submit" value="提交注册" class="login-submit qt-btn btn-green-linear" site="20">
                  </div>
				</div>
              </form>
			 <?php } ?>	
				
			<div class="account-box">
              <form id="register_form" method="post" class="wt-login-form" action="<?php echo urlLogin('login', 'usersave');?>">
                <?php Security::getToken();?>
                <div class="login-form">
                  <div class="input-inline">
                    <div class="input-icon i-user"></div>
                    <input id="user_name" name="user_name" type="text" autocomplete="off" tipMsg="<?php echo $lang['login_register_username_to_login'];?>">
                    
                  </div>
                  <div class="input-inline">
                    <div class="input-icon i-password"></div>
                    <input id="password" name="password" type="password" autocomplete="off" tipMsg="请输入登录密码">
                    
                  </div>
					
					<div class="input-inline">
                    <div class="input-icon i-password"></div>
                    <input type="password"  id="password_confirm" name="password_confirm" autocomplete="off" tipMsg="<?php echo $lang['login_register_input_password_again'];?>">
                    
                  </div>
					<div class="input-inline">
					 <div class="input-icon i-user"></div>
                    <input id="email" name="email" type="text" autocomplete="off" tipMsg="<?php echo $lang['login_register_input_valid_email'];?>">
                    
                  </div>
					
					<?php if(C('captcha_status_login') == '1') { ?>
                  <div class="input-inline captcha account-captcha" >
					<input type="text" name="captcha" autocomplete="off" class="text" style="width: 188px;" tipMsg="<?php echo $lang['login_register_input_code'];?>" id="getcaptcha" size="10" /> 
                    <img  alt="加载中..." id="codeimage" name="codeimage" src="index.php?w=vercode&type=40x100" onclick="javascript:document.getElementById('codeimage').src='index.php?w=vercode&type=40x100&c=' + Math.random();"  style="margin-left: 10px;vertical-align:middle; cursor:pointer;" /><a class="img-code-a" onclick="javascript:document.getElementById('codeimage').src='index.php?w=vercode&type=40x100&c=' + Math.random();"></a>
                    
                  </div>
					<?php } ?>
					
                  <div class="disabled" id="submit-passwd">
				<input type="submit" site='20' class="login-submit qt-btn btn-green-linear" value="<?php echo $lang['login_register_regist_now'];?>">
                <input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">
                <input name="wthash" type="hidden" value="<?php echo getWthash();?>" />
                <input type="hidden" name="form_submit" value="ok" />
                  </div>
                  <div class="login-switch clearfix"><a href="#"  site='21' class="statis fl j-phoneLogin">手机验证码登录</a><a href="<?php echo urlLogin('login', 'forget_password');?>"  site='22' class="statis fr">忘记密码？</a></div>
                </div>
                 </form>
              </div>
				
				
            </div>
			<div class="login-footer">
              <p class="login-type"><a  site='7' href="javascript:;" class="statis j-phoneLogin" rel="external nofollow">使用手机注册</a><a  site='8' href="javascript:;" class="statis j-accountLogin" rel="external nofollow">使用邮箱注册</a></p>
            </div>  
			  
			  
            <div class="agreement-alert"><a href="javascript:;" class="agreement-handle"></a>勾选代表你同意<a href="<?php echo urlShop('document', 'index',array('code'=>'agreement'));?>" target="_blank" class="text" title="<?php echo $lang['login_register_agreed'];?>"><?php echo $lang['login_register_agreement'];?></a></div>
          </div>
        </div>
      </div>
    </div>
<script type="text/javascript" src="<?php echo LOGIN_STATIC_SITE_URL;?>/js/register.js"></script>
<script>
$(document).ready(function() {
	//初始化Input的灰色提示信息  
	$('input[tipMsg]').inputTipText({pwd:'password,password_confirm'});
    $('.j-phoneLogin,.j-accountLogin').on('click', function() {
        var oLoginWrap = $(this).closest('.login-wrap');
        $(".geetest_logo").attr('href', 'javascript:void(0);');
        oLoginWrap.find('.phone-box,.account-box').hide();
        if ($(this).hasClass('j-phoneLogin')) {
            oLoginWrap.find('.phone-box').show();
            $("#statis-enroll").attr('site', '9');
            $("#statis-qq").attr('site', '10');
            $("#statis-wx").attr('site', '11');
            $("#statis-sina").attr('site', '12')
        } else {
            $("#statis-enroll").attr('site', '16');
            $("#statis-qq").attr('site', '17');
            $("#statis-wx").attr('site', '18');
            $("#statis-sina").attr('site', '19');
			oLoginWrap.find('.login-others-cards').hide();
            oLoginWrap.find('.account-box').show();
        }
        if (!oLoginWrap.find('.login-footer').is(':hidden')) {
            oLoginWrap.find('.login-footer').hide();
            oLoginWrap.find('.login-others').stop().animate({
                marginTop: '-100px'
            }, 200, 'linear', function() {
				$(this).attr('class', 'login-others-cards').hide();
				
				 //$(this).attr('class', 'login-others-cards').removeAttr('style');
                oLoginWrap.find('.login-box').fadeIn(300);
				 oLoginWrap.find('.cutting-line').hide();
				oLoginWrap.find('.wt-login-form').css("margin-top","20px");
				
				
            })
        }
    });
	
        $('.agreement-handle').click(function () {
            $('body').append('<div class="agreement-handle-message" style=" font-size: 14px;\n' +
                'display: none;' +
                'line-height: 1.5;\n' +
                'padding: 20px 30px;' +
                'border-radius: 4px;' +
                '-webkit-box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);' +
                'box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);' +
                'background: #fff;' +
                'color: rgba(0, 0, 0, 0.65);\n' +
                'list-style: none;\n' +
                'position: fixed;\n' +
                'z-index: 1010;\n' +
                'margin-left: -138px;' +
                'top: 16px;\n' +
                'left: 50%;"><img src="<?php echo LOGIN_TEMPLATES_URL;?>/images/login/warning-icon.png" style="display: inline-block;vertical-align: middle;margin-right: 15px">注册必须同意该协议</div>');
            $('.agreement-handle-message').fadeIn();
            setTimeout(function () {
                $('.agreement-handle-message').fadeOut();
                $('.agreement-handle-message').remove()
            }, 3000)
        });
	
	
		var _register_member = 0;
//注册表单验证
    $("#register_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('.input-inline');
            error_td.append(error);
            element.addClass('error');
        },
        success: function(label) {
            label.removeClass('error').find('label').remove();
        },
    	submitHandler:function(form){
    	    if (_register_member) return false;
    	    _register_member = 1;
    	    ajaxpost('register_form', '', '', 'onerror');
    	},
        onkeyup: false,
        rules : {
            user_name : {
                required : true,
                rangelength : [6,20],
                letters_name : true,
                remote   : {
                    url :'index.php?w=login&t=check_member&column=ok',
                    type:'get',
                    data:{
                        user_name : function(){
                            return $('#user_name').val();
                        }
                    }
                }
            },
            password : {
                required : true,
                minlength: 6,
				maxlength: 20
            },
            password_confirm : {
                required : true,
                equalTo  : '#password'
            },
            email : {
                required : true,
                email    : true,
                remote   : {
                    url : 'index.php?w=login&t=check_email',
                    type: 'get',
                    data:{
                        email : function(){
                            return $('#email').val();
                        }
                    }
                }
            },
			<?php if(C('captcha_status_register') == '1') { ?>
            captcha : {
                required : true,
                remote   : {
                    url : 'index.php?w=vercode&t=check',
                    type: 'get',
                    data:{
                        captcha : function(){
                            return $('#getcaptcha').val();
                        }
                    },
                    complete: function(data) {
                        if(data.responseText == 'false') {
                        	document.getElementById('codeimage').src='index.php?w=vercode&type=40x100&c=' + Math.random();
                        }
                    }
                }
            },
			<?php } ?>
            agree : {
                required : true
            }
        },
        messages : {
            user_name : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_input_username'];?>',
                rangelength : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_username_range'];?>',
				letters_name: '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_username_lettersonly'];?>',
				remote	 : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_username_exists'];?>'
            },
            password  : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_input_password'];?>',
                minlength: '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_password_range'];?>',
				maxlength: '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_password_range'];?>'
            },
            password_confirm : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_input_password_again'];?>',
                equalTo  : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_password_not_same'];?>'
            },
            email : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_input_email'];?>',
                email    : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_invalid_email'];?>',
				remote	 : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_email_exists'];?>'
            },
			<?php if(C('captcha_status_register') == '1') { ?>
            captcha : {
                required : '<i class="icon-remove-bbs" title="<?php echo $lang['login_register_input_text_in_image'];?>"></i>',
				remote	 : '<i class="icon-remove-bbs" title="<?php echo $lang['login_register_code_wrong'];?>"></i>'
            },
			<?php } ?>
            agree : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_must_agree'];?>'
            }
        }
    });
	
	
    });
</script>

<?php if (C('sms_register') == 1){?>
<script type="text/javascript" src="<?php echo LOGIN_STATIC_SITE_URL;?>/js/connect_sms.js" charset="utf-8"></script> 
<script>
$(function(){
    var _sms_register_member = 0;
	$("#submitBtn").click(function(){
        if($("#post_form").valid()){
            check_captcha();
    	}
	});
	$("#post_form").validate({
         errorPlacement: function(error, element){
            var error_td = element.parent('.input-inline');
            error_td.append(error);
            element.addClass('error');
        },
        success: function(label) {
            label.removeClass('error').find('label').remove();
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
                    return $("#image_captcha").val().length == 4;
                },
				minlength: 6,
                maxlength: 6
            }
		},
		messages: {
			phone: {
                required : '<i class="icon-exclamation-sign"></i>输入正确的手机号',
                mobile : '<i class="icon-exclamation-sign"></i>输入正确的手机号'
            },
			captcha : {
                required : '<i class="icon-remove-bbs" title="<?php echo $lang['login_register_input_text_in_image'];?>"></i>',
                minlength: '<i class="icon-remove-bbs" title="<?php echo $lang['login_register_input_text_in_image'];?>"></i>',
				remote	 : '<i class="icon-remove-bbs" title="<?php echo $lang['login_register_code_wrong'];?>"></i>'
            },
			sms_captcha: {
                required : '<i class="icon-remove-bbs" title="请输入6位短信验证码"></i>',
				minlength: '<i class="icon-remove-bbs" title="请输入6位短信验证码"></i>',
                maxlength: '<i class="icon-remove-bbs" title="请输入6位短信验证码"></i>'
            }
		}
	});
    $('#register_sms_form').validate({
         errorPlacement: function(error, element){
            var error_td = element.parent('.input-inline');
            error_td.append(error);
            element.addClass('error');
        },
        success: function(label) {
            label.removeClass('error').find('label').remove();
        },
    	submitHandler:function(form){
    	    if (_sms_register_member) return false;
    	    _sms_register_member = 1;
    	    ajaxpost('register_sms_form', '', '', 'onerror');
    	},
        rules : {
            member_name : {
                required : true,
                rangelength : [6,20],
                letters_name : true,
                remote   : {
                    url :'index.php?w=login&t=check_member&column=ok',
                    type:'get',
                    data:{
                        user_name : function(){
                            return $('#member_name').val();
                        }
                    }
                }
            },
            password : {
                required   : true,
                minlength: 6,
				maxlength: 20
            },
            email : {
                email    : true,
                remote   : {
                    url : 'index.php?w=login&t=check_email',
                    type: 'get',
                    data:{
                        email : function(){
                            return $('#sms_email').val();
                        }
                    }
                }
            },
            agree : {
                required : true
            }
        },
        messages : {
            member_name : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_input_username'];?>',
                rangelength : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_username_range'];?>',
				letters_name: '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_username_lettersonly'];?>',
				remote	 : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_username_exists'];?>'
            },
            password  : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_input_password'];?>',
                minlength: '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_password_range'];?>',
				maxlength: '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_password_range'];?>'
            },
            email : {
                email    : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_invalid_email'];?>',
				remote	 : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_email_exists'];?>'
            },
            agree : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['login_register_must_agree'];?>'
            }
        }
    });
});
</script>
<?php } ?>