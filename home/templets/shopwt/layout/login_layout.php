<?php defined('ShopWT') or exit('Access Denied By ShopWT');
$wapurl = WAP_SITE_URL;
	$agent = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($agent,"comFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS")){
		global $config;
        if(!empty($config['wap_site_url'])){
            $url = $config['wap_site_url'];
            switch ($_GET['w']){
			case 'goods':
			  $url .= '/html/product_detail.html?goods_id=' . $_GET['goods_id'];
			  break;
			case 'store':
			  $url .= '/shop.html';
			  break;
			case 'show_store':
			  $url .= '/html/store.html?store_id=' . $_GET['store_id'];
			  break;
			}
        } else {
            header('Location:'.$wapurl.$_SERVER['QUERY_STRING']);
        }
        header('Location:' . $url);
        exit();	
	}
?>
<!doctype html>
<html lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
<meta name="description" content="<?php echo $output['seo_description']; ?>" />
<meta name="author" content="ShopWT">
<meta name="copyright" content="ShopWT Inc. All Rights Reserved">
<?php echo html_entity_decode($output['setting_config']['qq_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['sina_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['share_qqzone_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['share_sinaweibo_appcode'],ENT_QUOTES); ?>
<link href="<?php echo LOGIN_TEMPLATES_URL;?>/css/common.css" rel="stylesheet" type="text/css">
<link href="<?php echo LOGIN_TEMPLATES_URL;?>/css/header.css" rel="stylesheet" type="text/css">
<link href="<?php echo LOGIN_TEMPLATES_URL;?>/css/login.css" rel="stylesheet" type="text/css">
<link href="<?php echo LOGIN_STATIC_SITE_URL;?>/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!--[if lt IE 9]>
      <script src="<?php echo STATIC_SITE_URL_HTTPS;?>/js/html5shiv.js"></script>
      <script src="<?php echo STATIC_SITE_URL_HTTPS;?>/js/respond.min.js"></script>
<![endif]-->
<script>
var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var SITEURL = '<?php echo BASE_SITE_URL;?>';var BASE_SITE_URL = '<?php echo BASE_SITE_URL;?>';var STATIC_SITE_URL = '<?php echo STATIC_SITE_URL;?>';var STATIC_SITE_URL = '<?php echo STATIC_SITE_URL;?>';var SHOP_TEMPLATES_URL = '<?php echo SHOP_TEMPLATES_URL;?>';
</script>
<script src="<?php echo STATIC_SITE_URL_HTTPS;?>/js/jquery.min.js"></script>
<script src="<?php echo STATIC_SITE_URL_HTTPS;?>/js/jquery-ui/jquery.ui.js"></script>
<script src="<?php echo STATIC_SITE_URL_HTTPS;?>/js/common.js"></script>
<script src="<?php echo STATIC_SITE_URL_HTTPS;?>/js/jquery.validation.min.js"></script>
<script src="<?php echo STATIC_SITE_URL_HTTPS;?>/js/dialog/dialog.js" id="wt_dialog"></script>
<script src="<?php echo LOGIN_STATIC_SITE_URL?>/js/taglibs.js"></script>
<script src="<?php echo LOGIN_STATIC_SITE_URL?>/js/tabulous.js"></script>
</head>
<body>
<div class="qt-header-other">
      <div class="w1200">
        <div class="qt-logo"><a class='statis' site='1' href="<?php echo BASE_SITE_URL;?>" title="<?php echo $output['setting_config']['site_name']; ?>"><img src="<?php echo UPLOAD_SITE_URL_HTTPS.DS.ATTACH_COMMON.DS.$output['setting_config']['site_logo']; ?>"></a></div>
        <div class="header-title">
         <?php if ($output['hidden_login'] != '1') {?>
    <?php if ($_GET['t'] == 'index') {?>
    登录
    <?php } else {?>
    注册
    <?php }?>
    <?php }?>
        </div>
      </div>
    </div>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<?php require_once($tpl_file);?>
<?php require_once template('layout/footer_https');?>
</body>
</html>
