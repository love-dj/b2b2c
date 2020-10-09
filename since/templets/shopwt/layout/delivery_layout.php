<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<!doctype html>
<html>
<head>
<!--<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE10" />-->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
<meta name="description" content="<?php echo $output['seo_description']; ?>" />
<link href="<?php echo DELIVERY_TEMPLATES_URL;?>/css/layout.css" rel="stylesheet" type="text/css">
<script>
var SITEURL = '<?php echo BASE_SITE_URL;?>';var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>';
</script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo DELIVERY_STATIC_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery-ui/jquery.ui.js" charset="utf-8"></script>
<script type="text/javascript" id="wt_dialog" src="<?php echo STATIC_SITE_URL;?>/js/dialog/dialog.js" charset="utf-8"></script>
</head>
<body style="background-color: #F0F0E1;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<header class="wtd-header">
  <div class="wrapper">
    <h1 class="wtd-logo"><i></i>物流自提自提点</h1>
    <form method="get" action="index.php">
    <input type="hidden" name="w" value="d_center">
    <?php if ($output['hidden_success'] == 1) {?>
    <input type="hidden" name="hidden_success" value="1">
    <?php }?>
    <div class="wtd-search" id="wtdSearch">
      <div class="wtd-search-box">
      <label>输入订单号/运单号/手机号进行查询</label>
      <input type="text" class="wtd-search-text" name="search_name" value="<?php echo $output['search_name'];?>">
      </div>
      <input type="submit" class="wtd-search-submit" value="搜索">
    </div>
    </form>
    <div class="wtd-user">
      <h3>Hi！<strong><?php echo $_SESSION['dlyp_name'];?></strong>，欢迎回到管理中心</h3>
      <ul>
        <li><a href="javascript:void(0);" onclick="javascript:ajax_form('information', '详细资料', 'index.php?w=d_center&t=information');">详细资料</a></li>
        <li><a href="javascript:void(0);" onclick="javascript:ajax_form('change_password', '修改密码', 'index.php?w=d_center&t=change_password')">修改密码</a></li>
        <li><a href="javascript:void(0);" onclick="javascript:ajaxget('<?php echo DELIVERY_SITE_URL;?>/index.php?w=login&t=logout');">安全退出</a></li>
      </ul>
    </div>
  </div>
</header>
<?php require_once($tpl_file);?>
<?php require_once(BASE_DELIVERY_TEMPLATES_URL.'/layout/footer.php');?>
</body>
<script>
//input焦点时隐藏/显示填写内容提示信息
$("#wtdSearch .wtd-search-text").each(function() {
	var thisVal = $(this).val();
	if (thisVal != "") {
		$(this).siblings("label").hide();
	} else {
		$(this).siblings("label").show();
	}
	$(this).keyup(function() {
		var val = $(this).val();
		$(this).siblings("label").hide();
	}).blur(function() {
		var val = $(this).val();
		if (val != "") {
			$(this).siblings("label").hide();
		} else {
			$(this).siblings("label").show();
		}
	});
});
</script>
</html>
