<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
    <div class="login-main" style="background-color:<?php echo $output['lpic']['color'];?>; height: 476px;">
      <div class="w1200"><a href="<?php echo $output['lpic']['url'];?>" target="_blank"><img data-which="login" site='2' src="<?php echo $output['lpic']['pic'];?>" /></a>
		  <!--手机验证码登录-->
        <div class="login-wrap">
          <div class="login-hearder">
            <div class="header-btn on" style="margin-left: 75px;" > 登录 </div>
            <a id="statis-enroll" class='statis' site='3' href="<?php echo urlLogin('login', 'register', array('ref_url' => $_GET['ref_url']));?>"  >
            <div class="header-btn"> 注册 </div>
            </a></div>
          <div class="login-content">
            <div class="login-others">            
            <?php if (C('qq_isuse') == 1 || C('sina_isuse') == 1 || C('weixin_isuse') == 1){?>
            <?php if (C('qq_isuse') == 1){?>
            <a id="statis-qq" class='statis other-logo logo-qq' site='4'  href="<?php echo MEMBER_SITE_URL;?>/index.php?w=connect_qq" ><i></i>&nbsp; <span>QQ登录</span></a>
            <?php } ?>
             <?php if (C('weixin_isuse') == 1){?>
            <a id='statis-wx' site='5' class='statis other-logo logo-wechat' href="javascript:void(0);" onclick="ajax_form('weixin_form', '微信账号登录', '<?php echo urlLogin('connect_wx', 'index');?>', 360);"><i></i>&nbsp; <span>微信登录</span></a>			 <?php } ?>	
             <?php if (C('sina_isuse') == 1){?>
            <a id='statis-sina' site='6' class='statis other-logo logo-weibo'  href="<?php echo MEMBER_SITE_URL;?>/index.php?w=connect_sina" ><i></i>&nbsp; <span>微博登录</span></a>
             <?php } ?>
 			 <?php } ?>     
            </div>
            <div class="login-box">
              <p class="cutting-line"><span>或</span></p>
              
              <?php if (C('sms_login') == 1){?>
          <form id="post_form" method="post" class="wt-login-form" action="<?php echo urlLogin('connect_sms', 'login');?>">
            <?php Security::getToken();?>
            <input type="hidden" name="form_submit" value="ok" />
            <input name="wthash" type="hidden" value="<?php echo getWthash();?>" />
              <div class="phone-box">
                <div class="login-form">
                  <div class="input-inline">
                    <div class="input-icon i-phone"></div>
					 <input name="phone" type="text" class="text" id="phone" tipMsg="可填写已注册的手机号接收短信" autocomplete="off" value="" maxlength="11">
                  </div>
                  <div id='sendcode-captcha' class="captcha input-inline" style="display:block">
                    <input name="captcha" id="image_captcha" type="text" autocomplete="off" tipMsg="<?php echo $lang['login_register_input_code'];?>" maxlength="4" style="width: 188px;"  />
                    <img alt="加载中..."  name="codeimage" id="sms_codeimage" onclick="javascript:document.getElementById('sms_codeimage').src='index.php?w=vercode&type=40x100&c=' + Math.random();" src="index.php?w=vercode&type=40x100"  style="margin-left: 10px;vertical-align:middle; cursor:pointer;" /><a class="img-code-a" onclick="javascript:document.getElementById('sms_codeimage').src='index.php?w=vercode&type=40x100&c=' + Math.random();"></a>
                    <p class="warning-text"></p>
                  </div>
                  <div class="input-inline captcha">
					  <input type="text" name="sms_captcha" style="width:  188px;" autocomplete="off" class="text" tipMsg="输入6位短信验证码" id="sms_captcha" maxlength="6" />
                    <div class="btn-captcha qt-btn btn-green"><a class='statis' site='13' href="javascript:void(0);" id="sms_text" onclick="get_sms_captcha('2')">发送验证码</a></div>
                    <p class="warning-text"></p>
                  </div>
                  <div class="disabled" id="submit-phone" >
                   <input type="submit" id="submit" class="login-submit qt-btn btn-green-linear " value="<?php echo $lang['login_index_login'];?>">
                <input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">
                </div>
				  <div class="login-switch clearfix"><a href="#"  site='15' class="statis fl j-accountLogin">帐号密码登录</a><a href="<?php echo urlLogin('login', 'forget_password');?>"  site='22' class="statis fr">忘记密码？</a></div>
                </div>
              </div>
			   </form>
              <?php } ?>   
              
              
              <div class="account-box">
              
              <form id="login_form" class="wt-login-form" method="post" action="<?php echo urlLogin('login', 'login');?>">
				<?php Security::getToken();?>
				<input type="hidden" name="form_submit" value="ok" />
				<input name="wthash" type="hidden" value="<?php echo getWthash();?>" />
                <div class="login-form">
                  <div class="input-inline">
                    <div class="input-icon i-user"></div>
                    <input id="user_name" name="user_name" type="text" autocomplete="off" tipMsg="可使用已注册的用户名或手机号登录">
                    <p class="warning-text"></p>
                  </div>
                  <div class="input-inline">
                    <div class="input-icon i-password"></div>
                    <input id="password" name="password" type="password" autocomplete="off" tipMsg="<?php echo $lang['login_register_password_to_login'];?>">
                    <p class="warning-text"></p>
                  </div>
					
					<?php if(C('captcha_status_login') == '1') { ?>
                  <div class="input-inline captcha account-captcha" >
					<input type="text" name="captcha" autocomplete="off" class="text" style="width: 188px;" tipMsg="<?php echo $lang['login_register_input_code'];?>" id="getcaptcha" size="10" /> 
                    <img  alt="加载中..." id="codeimage" name="codeimage" src="index.php?w=vercode&type=40x100" onclick="javascript:document.getElementById('codeimage').src='index.php?w=vercode&type=40x100&c=' + Math.random();"  style="margin-left: 10px;vertical-align:middle; cursor:pointer;" /><a class="img-code-a" onclick="javascript:document.getElementById('codeimage').src='index.php?w=vercode&type=40x100&c=' + Math.random();"></a>
                    <p class="warning-text"></p>
                  </div>
					<?php } ?>
					
                  <div class="disabled" id="submit-passwd">
				<input type="submit" site='20' class="login-submit qt-btn btn-green-linear" value="<?php echo $lang['login_index_login'];?>">
                <input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">
                  </div>
                  <div class="login-switch clearfix"><a href="#"  site='21' class="statis fl j-phoneLogin">手机验证码登录</a><a href="<?php echo urlLogin('login', 'forget_password');?>"  site='22' class="statis fr">忘记密码？</a></div>
                </div>
                 </form>
              </div>
            </div>
            <div class="login-footer">
              <p class="login-type"><a  site='7' href="javascript:;" class="statis j-phoneLogin" rel="external nofollow">手机验证码登录</a><a  site='8' href="javascript:;" class="statis j-accountLogin" rel="external nofollow">帐号密码登录</a></p>
            </div>
            <div class="agreement-alert"><a href="javascript:;" class="agreement-handle"></a>勾选代表你同意<a href="<?php echo urlShop('document', 'index',array('code'=>'agreement'));?>" target="_blank" class="text" title="<?php echo $lang['login_register_agreed'];?>"><?php echo $lang['login_register_agreement'];?></a></div>
          </div>
        </div>
      </div>
    </div>
<script type="text/javascript" src="<?php echo LOGIN_STATIC_SITE_URL;?>/js/login.js"></script>
<script>
$(document).ready(function() {
	//初始化Input的灰色提示信息  
	$('input[tipMsg]').inputTipText({pwd:'password'});
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
            oLoginWrap.find('.account-box').show()
        }
        if (!oLoginWrap.find('.login-footer').is(':hidden')) {
            oLoginWrap.find('.login-footer').hide();
            oLoginWrap.find('.login-others').stop().animate({
                marginTop: '-100px'
            }, 200, 'linear', function() {
                $(this).attr('class', 'login-others-cards').removeAttr('style');
                oLoginWrap.find('.login-box').fadeIn(300)
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
                'left: 50%;"><img src="<?php echo LOGIN_TEMPLATES_URL;?>/images/login/warning-icon.png" style="display: inline-block;vertical-align: middle;margin-right: 15px">登录必须同意该协议</div>');
            $('.agreement-handle-message').fadeIn();
            setTimeout(function () {
                $('.agreement-handle-message').fadeOut();
                $('.agreement-handle-message').remove()
            }, 3000)
        })


    });
</script>

<script>
$(function(){
	$("#login_form").validate({
         errorPlacement: function(error, element){
            var error_td = element.parent('.input-inline');
            error_td.append(error);
            element.addClass('error');
        },
        success: function(label) {
            label.removeClass('error').find('label').remove();
        },
    	submitHandler:function(form){
    	    ajaxpost('login_form', '', '', 'onerror');
    	},
        onkeyup: false,
		rules: {
			<?php if(C('captcha_status_login') == '1') { ?>
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
            }
			<?php } ?>
		},
		messages: {
			<?php if(C('captcha_status_login') == '1') { ?>
            captcha : {
                required : '<i class="icon-remove-bbs" title="<?php echo $lang['login_index_input_checkcode'];?>"></i>',
				remote	 : '<i class="icon-remove-bbs" title="<?php echo $lang['login_index_input_checkcode'];?>"></i>'
            }
			<?php } ?>
		}
	});

    $('input[name="auto_login"]').click(function(){
        if ($(this).attr('checked')){
            $(this).attr('checked', true).next().show();
        } else {
            $(this).attr('checked', false).next().hide();
        }
    });
});
</script>

<?php if (C('sms_login') == 1){?>
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
                    return $("#image_captcha").val().length == 4;
                },
                minlength: 6,
				maxlength: 6
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
                required : '<i class="icon-remove-bbs" title="请输入6位短信验证码"></i>',
				minlength: '<i class="icon-remove-bbs" title="请输入6位短信验证码"></i>',
                maxlength: '<i class="icon-remove-bbs" title="请输入6位短信验证码"></i>'
            }
		}
	});
});
</script>
<?php } ?>