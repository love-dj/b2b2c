<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<!doctype html>
<html>
<head>
<title><?php echo $output['html_title'];?></title>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
<meta name="description" content="<?php echo $output['seo_description']; ?>" />
<meta name="author" content="ShopWT">
<meta name="copyright" content="ShopWT Inc. All Rights Reserved">
<link href="<?php echo FENXIAO_TEMPLATES_URL;?>/css/common.css" rel="stylesheet" type="text/css">
<link href="<?php echo FENXIAO_TEMPLATES_URL;?>/css/fx_joinin.css" rel="stylesheet" type="text/css">
<link href="<?php echo FENXIAO_STATIC_SITE_URL;?>/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="<?php echo FENXIAO_STATIC_SITE_URL;?>/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<style type="text/css">
body { _behavior: url(<?php echo FENXIAO_TEMPLATES_URL;?>/css/csshover.htc);}</style>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo STATIC_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo STATIC_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<script>
var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var SITEURL = '<?php echo BASE_SITE_URL;?>';var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>';var BASE_SITE_URL = '<?php echo BASE_SITE_URL;?>';var STATIC_SITE_URL = '<?php echo STATIC_SITE_URL;?>';var SHOP_TEMPLATES_URL = '<?php echo SHOP_TEMPLATES_URL;?>';
</script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/dialog/dialog.js" id="wt_dialog" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.scrollLoading-min.js" charset="utf-8"></script>
<script type="text/javascript">
$(function(){
	$("img[rel='lazy']").scrollLoading();
});
</script>
</head>
<body>
<div class="header">
  <h2 class="header_logo"><a href="<?php echo BASE_SITE_URL;?>"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$output['setting_config']['site_logo']; ?>" class="pngFix"></a></h2>
</div>
<div class="header_line"></div>
<script type="text/javascript">
    function show_list(t_id){
        var obj = $(".sidebar dl[show_id='"+t_id+"']");
    	var show_class=obj.find("dt i").attr('class');
    	if(show_class=='hide') {
    		obj.find("dd").show();
    		obj.find("dt i").attr('class','show');
    	}else{
    		obj.find("dd").hide();
    		obj.find("dt i").attr('class','hide');
    	}
    }
</script>
<?php require_once($tpl_file);?>
<?php require_once template('footer');?>
</body>
</html>
