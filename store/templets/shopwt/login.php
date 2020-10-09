<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<script language="JavaScript" type="text/javascript">
		<!--
		window.onerror=function(){return true;}
		// -->
$(document).ready(function() {
    //更换验证码
    function change_vercode() {
        $('#codeimage').attr('src', 'index.php?w=vercode&type=30x92&c=' + Math.random());
        $('#captcha').select();
    }

    $('[wttype="btn_change_vercode"]').on('click', function() {
        change_vercode();
    });

    //登陆表单验证
    $("#chain_login").validate({
        errorPlacement:function(error, element) {
            element.prev(".repuired").append(error);
        },
        submitHandler:function(form){
            ajaxpost('chain_login', '', '', 'onerror');
        },
        onkeyup: false,
        rules:{
            user:{
                required:true
            },
            pwd:{
                required:true
            },
            captcha:{
                required:true,
                remote:{
                    url:"index.php?w=vercode&t=check",
                    type:"get",
                    data:{
                        captcha:function() {
                            return $("#captcha").val();
                        }
                    },
                    complete: function(data) {
                        if(data.responseText == 'false') {
                            change_vercode();
                        }
                    }
                }
            }
        },
        messages:{
            user:{
                required:"<i class='icon-exclamation-sign'></i>用户名不能为空"
            },
            pwd:{
                required:"<i class='icon-exclamation-sign'></i>密码不能为空"
            },
            captcha:{
                required:"<i class='icon-exclamation-sign'></i>验证码不能为空",
                remote:"<i class='icon-frown'></i>验证码错误"
            }
        }
    });
	//Hide Show verification code
    $("#hide").click(function(){
        $(".code").fadeOut("slow");
    });
    $("#captcha").focus(function(){
        $(".code").fadeIn("fast");
    });

});
</script>
<style type="text/css">
body {background:#fff;}
#header{height:78px;margin:0 auto;text-align:center position: relative;width:1100px;float:center}
.logo{width:1100px;margin:0 auto;overflow:hidden}
.logo h1{display:block;float:left;zoom:1;width:240px;height:50px; padding-top: 10px; vertical-align:middle;}
h1{font-size:18px}
.logo .link{float:right;line-height:44px;color:#666;font-size:12px;text-decoration:none; margin-top: 22px;}
.logo .link i{display:inline-block;vertical-align:middle;background:url(<?php echo SHOP_TEMPLATES_URL;?>/images/seller/login/dlgj.png) no-repeat;width:28px;height:26px}
#content{margin:0 auto;padding:80px 0;width:100%;border-top: 1px solid #e5e5e5;}
.wtsc-login-layout{margin:0 auto;position:relative;width:1100px;height:320px;z-index:1}
.iconfont{font-family:iconfont!important;font-size:16px;font-style:normal;-webkit-font-smoothing:antialiased;-webkit-text-stroke-width:.2px;-moz-osx-font-smoothing:grayscale}
.left-pic,.left-pic a{float:left;height:320px;position:relative;width:720px;z-index:1;text-indent:-9999px}
.right-login{float:right;position:relative;z-index:1}
#footer {background:#fff;border-top: 1px solid #e5e5e5; text-align: center}
#footer,#footer p{color:#666;}
#footer a{color:#666}
</style>
	<div id="header">
		<div class="logo">
			<h1><a href="<?php echo BASE_SITE_URL;?>"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$output['setting_config']['site_logo']; ?>" class="pngFix"></a>
        </h1>
			<a href="<?php echo BASE_SITE_URL;?>/index.php?w=show_joinin&t=index" class="link">
            <i></i>商家入驻申请
        </a>
		</div>
	</div>
	<div id="content" style="background: <?php echo $output['lpic']['color'];?>">
		<div class="wtsc-login-layout" style="background: <?php echo $output['lpic']['color'];?> url(<?php echo $output['lpic']['pic'];?>) no-repeat scroll 0px center;">
			<div class="left-pic">
				<a href="<?php echo $output['lpic']['url'];?>"></a>
			</div>
			<div class="right-login">
<div class="wtsc-login-container">
  <div class="wtsc-login-title">
    <h2>门店管理</h2>
    <h4><a href="<?php echo urlShop('seller_login', 'show_login');?>">商家管理</a></h4>
  </div>
  <form id="chain_login" method="post" action="<?php echo CHAIN_SITE_URL;?>/index.php?w=login">
    <?php Security::getToken();?>
    <input type="hidden" name="form_submit" value="ok" />
    <div class="input">
      <label>&nbsp;</label>
      <span class="repuired"></span>
      <input class="text" type="text" name="user" id="user"  placeholder="请输入门店用户名" autofocus autocomplete="off"  />
      <span class="ico"><i class="icon-user"></i></span> </div>
    <div class="input">
      <label>&nbsp;</label>
      <span class="repuired"></span>
      <input class="text" type="password" placeholder="请输入登录密码" name="pwd" id="pwd" />
      <span class="ico"><i class="icon-key"></i></span> </div>
    <div class="input">
    <label>&nbsp;</label>
    <span class="repuired"></span>
    <input type="text"  placeholder="验证码" name="captcha" id="captcha" autocomplete="off" class="text" style="width: 80px;" maxlength="4" size="10" />
    <div class="code">
      <div class="arrow"></div>
      <div class="code-img"><a href="javascript:void(0)" wttype="btn_change_vercode"><img src="index.php?w=vercode" name="codeimage" border="0" id="codeimage"></a></div>
      <a href="JavaScript:void(0);" id="hide" class="close" title="<?php echo $lang['login_index_close_checkcode'];?>"><i></i></a> <a href="JavaScript:void(0);" class="change" wttype="btn_change_vercode" title="<?php echo $lang['login_index_change_checkcode'];?>"><i></i></a> </div>
    <span class="ico"><i class="icon-qrcode"></i></span>
    <input type="submit" class="login-submit" value="确认登录">
  </form>
</div>
			</div>
		</div>
	</div>
</div>
<?php require_once template('layout/footer');?>
</body>
</html>