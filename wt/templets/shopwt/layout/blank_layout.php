<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<!doctype html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.min.js"></script>
</head>
<body>
<?php 
	require_once($tpl_file);
?>
</body>
</html>