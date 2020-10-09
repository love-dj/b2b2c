<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>商家中心</title>
<link href="<?php echo FENXIAO_TEMPLATES_URL?>/css/common.css" rel="stylesheet" type="text/css">
<link href="<?php echo FENXIAO_TEMPLATES_URL?>/css/seller.css" rel="stylesheet" type="text/css">
<link href="<?php echo FENXIAO_TEMPLATES_URL;?>/css/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<script>
var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var SITEURL = '<?php echo BASE_SITE_URL;?>';var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>';var STATIC_SITE_URL = '<?php echo STATIC_SITE_URL;?>';var SHOP_STATIC_SITE_URL = '<?php echo SHOP_STATIC_SITE_URL;?>';var SHOP_TEMPLATES_URL = '<?php echo SHOP_TEMPLATES_URL;?>';</script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo FENXIAO_STATIC_SITE_URL;?>/js/seller.js"></script>
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
<?php if (!empty($output['store_closed'])) { ?>
<div class="store-closed"><i class="icon-warning-sign"></i>
  <dl>
    <dt>您的店铺已被平台关闭</dt>
    <dd>关闭原因：<?php echo $output['store_close_info'];?></dd>
    <dd>在此期间，您的店铺以及商品将无法访问；如果您有异议或申诉请及时联系平台管理。</dd>
  </dl>
</div>
<?php } ?>
<header class="wtsc-head-box w">
  <div class="wrapper">
    <div class="wtsc-admin">
      <dl class="wtsc-admin-info">
        <dt class="admin-avatar"><img src="<?php echo getMemberAvatarForID($_SESSION['member_id']);?>" width="32" class="pngFix" alt=""/></dt>
        <dd class="admin-name"><?php echo $_SESSION['seller_name'];?></dd>
        <dd class="admin-permission">微笑每一天</dd>
      </dl>
      <div class="wtsc-admin-function"><a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$_SESSION['store_id']), $output['store_info']['store_domain']);?>" title="前往店铺" target="_blank"><i class="icon-home"></i></a><a href="<?php echo urlMember('member_security', 'auth',array('type'=>'modify_pwd'));?>" title="修改密码" target="_blank"><i class="icon-wrench"></i></a><a href="<?php echo urlShop('seller_logout', 'logout');;?>" title="安全退出"><i class="icon-signout"></i></a></div>
    </div>
    <div class="center-logo"> <a href="<?php echo BASE_SITE_URL;?>" target="_blank"><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('seller_center_logo');?>" class="pngFix" alt=""/></a>
      <h1>商家中心</h1>
    </div>
    <nav class="wtsc-nav">
      <dl class="<?php echo $output['current_menu']['model'] == 'index'?'current':'';?>">
        <dt><a href="<?php echo BASE_SITE_URL;?>/index.php?w=seller_center&t=index">首页</a></dt>
        <dd class="arrow"></dd>
      </dl>
      <?php if(!empty($output['menu']) && is_array($output['menu'])) {?>
      <?php foreach($output['menu'] as $key => $menu_value) {?>
      <dl class="<?php echo $output['current_menu']['model'] == $key?'current':'';?>">
        <dt><a href="<?php echo $key != 'fenxiao' ? BASE_SITE_URL.'/':'';?>index.php?w=<?php echo $menu_value['child'][key($menu_value['child'])]['w'];?>&t=<?php echo $menu_value['child'][key($menu_value['child'])]['t'];?>"><?php echo $menu_value['name'];?></a></dt>
        <dd>
          <ul>
            <?php if(!empty($menu_value['child']) && is_array($menu_value['child'])) {?>
            <?php foreach($menu_value['child'] as $submenu_value) {?>
            <li> <a href="<?php echo $key != 'fenxiao' ? BASE_SITE_URL.'/':'';?>index.php?w=<?php echo $submenu_value['w'];?>&t=<?php echo $submenu_value['t'];?>"> <?php echo $submenu_value['name'];?> </a> </li>
            <?php } ?>
            <?php } ?>
          </ul>
        </dd>
        <dd class="arrow"></dd>
      </dl>
      <?php } ?>
      <?php } ?>
    </nav>
        <div class="index-search-container">
      <div class="index-sitemap"><a href="javascript:void(0);">导航管理 <i class="icon-angle-down"></i></a>
        <div class="sitemap-menu-arrow"></div>
        <div class="sitemap-menu">
          <div class="title-bar">
            <h2> <i class="icon-sitemap"></i>管理导航<em>小提示：添加您经常使用的功能到首页侧边栏，方便操作。</em> </h2>
            <span id="closeSitemap" class="close">X</span></div>
          <div id="quicklink_list" class="content">
            <?php if(!empty($output['menu']) && is_array($output['menu'])) {?>
            <?php foreach($output['menu'] as $key => $menu_value) {?>
            <dl <?php echo ($key == 'sale' ? 'class="double"' : '');?>>
              <dt><?php echo $menu_value['name'];?></dt>
              <?php if(!empty($menu_value['child']) && is_array($menu_value['child'])) {?>
              <?php foreach($menu_value['child'] as $submenu_value) {?>
              <dd <?php if(!empty($output['seller_quicklink'])) {echo in_array($submenu_value['w'], $output['seller_quicklink'])?'class="selected"':'';}?>><i wttype="btn_add_quicklink" data-quicklink-w="<?php echo $submenu_value['w'];?>" class="icon-check" title="添加为常用功能菜单"></i><a href="index.php?w=<?php echo $submenu_value['w'];?>&t=<?php echo $submenu_value['t'];?>"> <?php echo $submenu_value['name'];?> </a></dd>
              <?php } ?>
              <?php } ?>
            </dl>
            <?php } ?>
            <?php } ?>
          </div>
        </div>
      </div>
			<!--
      <div class="search-bar">
        <form method="get" target="_blank">
          <input type="hidden" name="w" value="search">
          <input type="text" wttype="search_text" name="keyword" placeholder="商城商品搜索" class="search-input-text">
          <input type="submit" wttype="search_submit" class="search-input-btn pngFix" value="">
        </form>
      </div>
-->
    </div>
  </div>
</header>
<?php if(!$output['seller_layout_no_menu']) { ?>
<div class="wtsc-box wrapper">
  <div id="layoutLeft" class="wtsc-box-left">
    <div id="sidebar" class="sidebar">
      <div class="column-title" id="main-nav"><span class="ico-<?php echo $output['current_menu']['model'];?>"></span>
        <h2><?php echo $output['current_menu']['model_name'];?></h2>
      </div>
      <div class="column-menu">
        <ul id="seller_center_left_menu">
          <?php if(!empty($output['left_menu']) && is_array($output['left_menu'])) {?>
          <?php foreach($output['left_menu'] as $submenu_value) {?>
          <li <?php echo $_GET['w'] == 'seller_center'?"id='quicklink_".$submenu_value['w']."'":'';?>class="<?php echo $submenu_value['w'] == $_GET['w']?'current':'';?>"> <a href="index.php?w=<?php echo $submenu_value['w'];?>&t=<?php echo $submenu_value['t'];?>"> <?php echo $submenu_value['name'];?> </a> </li>
          <?php } ?>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
  <div id="layoutRight" class="wtsc-box-right">
    <div class="wtsc-path"><i class="icon-desktop"></i>商家管理中心<i class="icon-angle-right"></i><?php echo $output['current_menu']['model_name'];?><i class="icon-angle-right"></i><?php echo $output['current_menu']['name'];?></div>
    <div class="main-content" id="mainContent">
      <?php require_once($tpl_file); ?>
    </div>
  </div>
</div>
<?php } else { ?>
<div class="wrapper">
  <?php require_once($tpl_file); ?>
</div>
<?php } ?>
<script type="text/javascript">
</script>
<script type="text/javascript">
$(document).ready(function(){
    //浮动导航  waypoints.js
    $("#sidebar,#mainContent").waypoint(function(event, direction) {
        $(this).parent().toggleClass('sticky', direction === "down");
        event.stopPropagation();
        });
    });
    // 搜索商品不能为空
    $('input[wttype="search_submit"]').click(function(){
        if ($('input[wttype="search_text"]').val() == '') {
            return false;
        }
    });
</script>
<?php require_once template('footer');?>
</body>
</html>
