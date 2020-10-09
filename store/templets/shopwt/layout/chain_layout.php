<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $output['html_title'];?></title>
<link href="<?php echo CHAIN_TEMPLATES_URL?>/css/chain.css" rel="stylesheet" type="text/css">
<link href="<?php echo CHAIN_STATIC_SITE_URL;?>/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<script>
var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var SITEURL = '<?php echo BASE_SITE_URL;?>';var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>';var STATIC_SITE_URL = '<?php echo STATIC_SITE_URL;?>';var CHAIN_STATIC_SITE_URL = '<?php echo CHAIN_STATIC_SITE_URL;?>';var CHAIN_TEMPLATES_URL = '<?php echo CHAIN_TEMPLATES_URL;?>';</script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/waypoints.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/common.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/member.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/dialog/dialog.js" id="wt_dialog" charset="utf-8"></script>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo STATIC_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo STATIC_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
</head>

<body>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/ToolTip.js"></script>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<header class="wtsc-head-box w">
  <div class="wrapper">
    <div class="wtsc-admin">
    
    <dl><dt><?php echo $_SESSION['chain_name'];?></dt>
    <dd><?php echo $_SESSION['chain_address'];?></dd>
    <dd><?php echo $_SESSION['chain_phone']?></dd></dl><div class="pic"><img src="<?php echo $_SESSION['chain_img'];?>"></div>
     
     
    </div>
    <div class="center-logo"> <a href="<?php echo BASE_SITE_URL;?>" target="_blank"><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('seller_center_logo');?>" class="pngFix" alt=""/></a>
      <h1>门店管理</h1>
    </div>
    <ul class="wtsc-nav">
      <li> <a href="index.php?w=goods" class="<?php echo $_GET['w'] == 'goods'?'current':'';?>">门店商品库存</a> </li>
      <li> <a href="index.php?w=order" class="<?php echo $_GET['w'] == 'order'?'current':'';?>">门店取货订单</a> </li>
      <li><a href="javascript:;" onclick="ajaxget('<?php echo urlChain('login', 'logout');?>')" title="安全退出">安全退出</a></li>
    </ul>
  </div>
</header>
<div class="wtsc-box wrapper">
  <div class="main-content" id="mainContent">
    <?php require_once($tpl_file); ?>
  </div>
  <div id="layoutRight" class="wtsc-box-right"> </div>
</div>
<?php require_once template('layout/footer');?>
</body>
</html>
