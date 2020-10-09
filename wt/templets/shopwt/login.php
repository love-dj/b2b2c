<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="login-box">
  <div class="top">
    <h2><?php echo $lang['login_index_title_02'];?></h2>
    <h6><?php echo $lang['login_index_title_01'];?></h6>
  </div>
  <form method="post" id="form_login">
  <?php Security::getToken();?>
  <input type="hidden" name="form_submit" value="ok" />
  <input type="hidden" name="SiteUrl" id="SiteUrl" value="<?php echo BASE_SITE_URL;?>" />
	  <div class="con">
		<div class="hand">
			<div class="tou"></div>
			<div id="left_hand" class="initial_left_hand"></div>
			<div id="right_hand" class="initial_right_hand"></div>
		</div>
		<div class="user-input">
		<p class="username">
			<span class="ico"><i class="fa fa-user"></i></span>
			<input class="ipt" type="text" placeholder="请输入登录帐号" id="user_name" name="user_name"  autocomplete="off"  required>
		</p>
		<p class="password">
			<span class="ico"><i class="fa fa-key"></i></span>
			<input id="password" class="ipt" type="password"  placeholder="请输入登录密码" name="password" autocomplete="off" type="password" required pattern="[\S]{6}[\S]*" title="<?php echo $lang['login_index_password_pattern'];?>"> 
		</p>
		 </div>
		<div class="submit">
			   <span class="user-code">
				<div class="code" style="width:140px;height:40px;bottom:-45px;">
				  <div class="arrow"></div>
				  <div class="code-img"><img src="index.php?w=vercode" name="codeimage" id="codeimage" border="0" style="width:115px;height:35px;"/></div>
				  <a href="JavaScript:void(0);" id="hide" class="close" title="<?php echo $lang['login_index_close_checkcode'];?>"><i></i></a><a href="JavaScript:void(0);" onclick="javascript:document.getElementById('codeimage').src='index.php?w=vercode&type=30x92&c=' + Math.random();" class="change" title="<?php echo $lang['login_index_change_checkcode'];?>"><i></i></a> </div>
					<input name="captcha" type="text" required class="input-code ipt" id="captcha" placeholder="<?php echo $lang['login_index_checkcode'];?>" pattern="[A-z0-9]{4}" title="<?php echo $lang['login_index_checkcode_pattern'];?>" autocomplete="off" value="" style="width:80px;" >
					<input name="wthash" type="hidden" value="<?php echo getWthash();?>" />
				   <span class="ico"><i class="fa fa-qrcode"></i></span>
				</span>
			   <span class="right">
				   <input name="" class="input-button btn-submit" type="button" value="<?php echo $lang['login_index_button_login'];?>">
			   </span>
		</div>
		<div class="submit2"></div>
	</div>
 </form>
</div>