<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="quick-login">
  <form id="login_form" action="<?php echo urlLogin('login');?>" method="post" class="bg" >
    <?php Security::getToken();?>
    <input type="hidden" name="form_submit" value="ok" />
    <input name="wthash" type="hidden" value="<?php echo getWthash();?>" />
    <dl>
      <dt>账&nbsp;&nbsp;&nbsp;号</dt>
      <dd>
        <input type="text" class="text" autocomplete="off"  name="user_name" id="user_name" autofocus >
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['login_index_password'];?></dt>
      <dd>
        <input type="password" class="text" name="password" autocomplete="off"  id="password">
      </dd>
    </dl>
    <?php if(C('captcha_status_login') == '1') { ?>
    <dl>
      <dt><?php echo $lang['login_index_checkcode'];?></dt>
      <dd>
        <input type="text" name="captcha" class="text fl w60" id="captcha" maxlength="4" size="10" />
        <img class="fl ml10" src="index.php?w=vercode" title="<?php echo $lang['login_index_change_checkcode'];?>" name="codeimage" border="0" id="codeimage" onclick="this.src='index.php?w=vercode&type=30x92&c=' + Math.random()"><span></span></dd>
    </dl>
    <?php } ?>
    <ul>
      <li><?php echo $lang['quick_login_please_regist1']?><a href="<?php echo urlLogin('login', 'register');?>" class="register"><?php echo $lang['quick_login_please_regist2']?></a><?php echo $lang['quick_login_please_regist3']?></li>
      <li><?php echo $lang['quick_login_please_forget1']?><a href="<?php echo urlLogin('login', 'forget_password');?>" class="forget"><?php echo $lang['quick_login_please_forget2']?></a><?php echo $lang['quick_login_please_forget3']?></li>
    </ul>
    <div class="enter">
      <input type="submit" class="submit" value="登&#12288;录" name="Submit">
      <?php if (C('qq_isuse') == 1 || C('sina_isuse') == 1 || C('weixin_isuse') == 1){?>
      <span class="other">
      <?php if (C('qq_isuse') == 1){?>
      <a href="<?php echo MEMBER_SITE_URL;?>/index.php?w=connect_qq" title="QQ账号登录" class="qq"><i></i></a>
      <?php } ?>
      <?php if (C('sina_isuse') == 1){?>
      <a href="<?php echo MEMBER_SITE_URL;?>/index.php?w=connect_sina" title="新浪微博账号登录" class="sina"><i></i></a>
      <?php } ?>
      <?php if (C('weixin_isuse') == 1){?>
      <a href="javascript:void(0);" onclick="ajax_form('weixin_form', '微信账号登录', '<?php echo urlLogin('connect_wx', 'index');?>', 360);" title="微信账号登录" class="wx"><i></i></a>
      <?php } ?>
      </span>
      <?php } ?>
    </div>
    <input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">
  </form>
</div>
<script>
$(document).ready(function(){
	$("#login_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.find('label').hide();
            error_td.append(error);
        },
        onkeyup: false,
    	submitHandler:function(form){
    		ajaxpost('login_form', '', '', 'error');
    	},
		rules: {
			user_name: "required",
			password: "required"
			<?php if(C('captcha_status_login') == '1') { ?>
            ,captcha : {
                required : true,
                remote   : {
                    url : 'index.php?w=vercode&t=check',
                    type: 'get',
                    data:{
                        captcha : function(){
                            return $('#captcha').val();
                        }
                    },
                    complete: function(data) {
                        if(data.responseText == 'false') {
                        	document.getElementById('codeimage').src='index.php?w=vercode&type=30x92&c=' + Math.random();
                        }
                    }
                }
            }
			<?php } ?>
		},
		messages: {
			user_name: "",
			password: ""
			<?php if(C('captcha_status_login') == '1') { ?>
            ,captcha : {
                required : '',
				remote	 : '验证码错误'
            }
			<?php } ?>
		}
	});
});
</script> 
