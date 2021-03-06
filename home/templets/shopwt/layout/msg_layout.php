<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<meta name="keywords" content="<?php echo $output['setting_config']['site_keywords']; ?>" />
<meta name="description" content="<?php echo $output['setting_config']['site_description']; ?>" />
<meta name="author" content="ShopWT">
<meta name="copyright" content="ShopWT Inc. All Rights Reserved">
<meta name="renderer" content="webkit">
<meta name="renderer" content="ie-stand">
<link href="<?php echo MEMBER_TEMPLATES_URL;?>/css/common.css" rel="stylesheet" type="text/css">
<link href="<?php echo MEMBER_TEMPLATES_URL;?>/css/header.css" rel="stylesheet" type="text/css">
<link href="<?php echo MEMBER_STATIC_SITE_URL;?>/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!--[if lt IE 9]>
      <script src="<?php echo STATIC_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo STATIC_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<style type="text/css">
body, .header-box { background-color: #FAFAFA;}
.wrapper { width: 1200px;}
#faq { display: none;}
.msg { font: 100 36px/48px arial,"microsoft yahei"; color: #555; background-color: #FFF; text-align: center; width: 100%; border: solid 1px #E6E6E6; margin-bottom: 10px; padding: 120px 0;}
.msg i { font-size: 48px; vertical-align: middle; margin-right: 10px;}
</style>
<script>var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var SITEURL = '<?php echo MEMBER_SITE_URL;?>';var MEMBER_STATIC_SITE_URL = '<?php echo MEMBER_STATIC_SITE_URL;?>';var STATIC_SITE_URL = '<?php echo STATIC_SITE_URL;?>';var MEMBER_TEMPLATES_URL = '<?php echo MEMBER_TEMPLATES_URL;?>';</script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/common.js"></script>
<script type="text/javascript">
$(function(){
	$("#details").children('ul').children('li').click(function(){
		$(this).parent().children('li').removeClass("current");
		$(this).addClass("current");
		$('#search_wt').attr("value",$(this).attr("w"));
	});
	var search_wt = $("#details").find("li[class='current']").attr("w");
	$('#search_wt').attr("value",search_wt);
	$("#keyword").blur();
});
</script>
</head>
<body>
<?php require_once template('layout/layout_top');?>
<div class="header-box"><header class="wt-head wrapper">
  <h1 class="site-logo"><a href="<?php echo MEMBER_SITE_URL;?>"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$output['setting_config']['site_logo']; ?>" class="pngFix"></a></h1></header></div>
<div class="msg">
      <?php if($output['msg_type'] == 'error'){ ?>
      <i class="icon-info-sign" style="color: #39C;"></i>
        <?php }else { ?>
      <i class="icon-ok-sign" style=" color: #099;"></i>
        <?php } ?>
        <?php require_once($tpl_file);?>
</div>
<script type="text/javascript">
<?php if (!empty($output['url'])){
?>
	window.setTimeout("javascript:location.href='<?php echo $output['url'];?>'", <?php echo $time;?>);
<?php
}else{
?>
	window.setTimeout("javascript:history.back()", <?php echo $time;?>);
<?php
}?>
</script>
<?php
require_once template('layout/footer');
?>
</body>
</html>
