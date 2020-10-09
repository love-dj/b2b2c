<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<!doctype html>
<html>
<head>
<!--<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE10" />-->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
<meta name="description" content="<?php echo $output['seo_description']; ?>" />
<link href="<?php echo BBS_TEMPLATES_URL;?>/css/layout.css" rel="stylesheet" type="text/css">
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo STATIC_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo STATIC_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<script>
var COOKIE_PRE = '<?php echo COOKIE_PRE;?>'; var _CHARSET = '<?php echo strtolower(CHARSET);?>'; var SITEURL = '<?php echo BASE_SITE_URL;?>';
var BASE_SITE_URL = '<?php echo BASE_SITE_URL;?>';
var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>';
var BBS_SITE_URL = '<?php echo BBS_SITE_URL;?>'; var _ISLOGIN = <?php echo intval($_SESSION['is_login']);?>;
var LOGIN_SITE_URL = '<?php echo LOGIN_SITE_URL;?>';var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>'; var STATIC_SITE_URL = '<?php echo STATIC_SITE_URL;?>';
var WT_HASH = '<?php echo getWthash();?>'; var WT_TOKEN = '<?php echo Security::getTokenValue();?>';
</script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery-ui/jquery.ui.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.icheck.min.js"></script>
<script type="text/javascript" id="wt_dialog" src="<?php echo STATIC_SITE_URL;?>/js/dialog/dialog.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/qtip/jquery.qtip.min.js"></script>
<link href="<?php echo STATIC_SITE_URL;?>/js/qtip/jquery.qtip.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo BBS_STATIC_SITE_URL;?>/js/common.js" charset="utf-8"></script> 
<link href="<?php echo STATIC_SITE_URL;?>/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/perfect-scrollbar.min.js"></script>
</head>
<body>
<?php require_once(bbs_template('layout/top'));?>
<?php require_once($tpl_file);?>
<?php require_once(bbs_template('layout/footer'));?>
</body>
</html>
