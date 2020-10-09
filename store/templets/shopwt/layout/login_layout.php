<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<!doctype html>
<html>
<head>
<!--<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE10" />-->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
<meta name="description" content="<?php echo $output['seo_description']; ?>" />
<link href="<?php echo CHAIN_TEMPLATES_URL;?>/css/chain.css" rel="stylesheet" type="text/css">
<link href="<?php echo CHAIN_STATIC_SITE_URL;?>/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<script>
var SITEURL = '<?php echo BASE_SITE_URL;?>';
</script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery-ui/jquery.ui.js" charset="utf-8"></script>
<script type="text/javascript" id="wt_dialog" src="<?php echo STATIC_SITE_URL;?>/js/dialog/dialog.js" charset="utf-8"></script>
</head>
<body>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<?php require_once($tpl_file);?>
</body>
</html>
