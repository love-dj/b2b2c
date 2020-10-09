<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<!doctype html>
<html lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
<meta name="description" content="<?php echo $output['seo_description']; ?>" />
<meta name="author" content="ShopWT">
<meta name="copyright" content="ShopWT Inc. All Rights Reserved">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<meta name="renderer" content="webkit">
<meta name="renderer" content="ie-stand">
<?php echo html_entity_decode($output['setting_config']['qq_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['sina_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['share_qqzone_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['share_sinaweibo_appcode'],ENT_QUOTES); ?>
<link rel="shortcut icon" href="<?php echo BASE_SITE_URL;?>/favicon.ico" />
<link href="<?php echo FENXIAO_TEMPLATES_URL;?>/css/common.css" rel="stylesheet" type="text/css">
<link href="<?php echo FENXIAO_TEMPLATES_URL;?>/css/header.css" rel="stylesheet" type="text/css">
<link href="<?php echo FENXIAO_TEMPLATES_URL;?>/css/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!--[if lt IE 9]>
      <script src="<?php echo STATIC_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo STATIC_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<script>
var PRICE_FORMAT = '<?php echo $lang['currency'];?>%s';var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var SITEURL = '<?php echo BASE_SITE_URL;?>';var BASE_SITE_URL = '<?php echo BASE_SITE_URL;?>';var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>';var STATIC_SITE_URL = '<?php echo STATIC_SITE_URL;?>';var SHOP_TEMPLATES_URL = '<?php echo SHOP_TEMPLATES_URL;?>';var FENXIAO_SITE_URL = '<?php echo FENXIAO_SITE_URL;?>';var FENXIAO_TEMPLATES_URL = '<?php echo FENXIAO_TEMPLATES_URL;?>';
</script>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.min.js"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/dialog/dialog.js" id="wt_dialog" charset="utf-8"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.scrollLoading-min.js" charset="utf-8"></script>
<script type="text/javascript">
$(function(){
$("img[rel='lazy']").scrollLoading();
	$(".category ul.menu").find("li").each(
		function() {
			$(this).hover(
				function() {
				    var cat_id = $(this).attr("cat_id");
					var menu = $(this).find("div[cat_menu_id='"+cat_id+"']");
					menu.show();
					$(this).addClass("hover");					
					var menu_height = menu.height();
					if (menu_height < 60) menu.height(80);
					menu_height = menu.height();
					var li_top = $(this).position().top;
					$(menu).css("top",-li_top + 37);
				},
				function() {
					$(this).removeClass("hover");
				    var cat_id = $(this).attr("cat_id");
					$(this).find("div[cat_menu_id='"+cat_id+"']").hide();
				}
			);
		}
	);
	$(".head-my-menu dl").hover(function() {
		$(this).addClass("hover");
	},
	function() {
		$(this).removeClass("hover");
	});
	$('.head-my-menu .my-cart').mouseover(function(){
		load_cart_information();
		$(this).unbind('mouseover');
	});
    <?php if (C('fullindexer.open')) { ?>
	// input ajax tips
	$('#keyword').focus(function(){
		if ($(this).val() == $(this).attr('title')) {
			$(this).val('').removeClass('tips');
		}
	}).blur(function(){
		if ($(this).val() == '' || $(this).val() == $(this).attr('title')) {
			$(this).addClass('tips').val($(this).attr('title'));
		}
	}).blur().autocomplete({
        source: function (request, response) {
            $.getJSON('<?php echo BASE_SITE_URL;?>/index.php?w=search&t=auto_complete', request, function (data, status, xhr) {
                $('#top_search_box > ul').unwrap();
                response(data);
                if (status == 'success') {
                 $('body > ul:last').wrap("<div id='top_search_box'></div>").css({'zIndex':'1000','width':'362px'});
                }
            });
       },
		select: function(ev,ui) {
			$('#keyword').val(ui.item.label);
			$('#head_search_form').submit();
		}
	});
	<?php } ?>

	$('#button').click(function(){
	    if ($('#keyword').val() == '') {
		    if ($('#keyword').attr('data-value') == '') {
			    return false
			} else {
				window.location.href="<?php echo FENXIAO_SITE_URL?>/index.php?w=search&t=index&keyword="+$('#keyword').attr('data-value');
			    return false;
			}
	    }
	});
	$(".search-m").hover(null,
	function() {
		$('#search-tips').hide();
	});
	$('#keyword').focus(function(){
		if($('#search_wt').val()=='search') {
			$('#search-tips').show();
		} else {
			$('#search-tips').hide();
		}
		}).autocomplete({
        source: function (request, response) {
            $.getJSON('<?php echo BASE_SITE_URL;?>/index.php?w=search&t=auto_complete', request, function (data, status, xhr) {
                $('#top_search_box > ul').unwrap();
                response(data);
                if (status == 'success') {
                    $('#search-tips').hide();
                    $(".search-m").unbind('mouseover');
                    $('body > ul:last').wrap("<div id='top_search_box'></div>").css({'zIndex':'1000','width':'362px'});
                }
            });
       },
		select: function(ev,ui) {
			$('#keyword').val(ui.item.label);
			$('#head_search_form').submit();
		}
	});
	$('#search-his-del').on('click',function(){$.cookie('<?php echo C('cookie_pre')?>his_sh',null,{path:'/'});$('#search-history-list').empty();});
});
</script>	
</head>
<body>
<?php require_once template('layout/layout_top');?>
<div class="header-box">
  <header class="wt-head wrapper">
    <h1 class="site-logo"><a title="返回分销首页" href="<?php echo FENXIAO_SITE_URL;?>">
		<?php if(C('fenxiao_logo')){?>
        <img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_FENXIAO.DS.$output['setting_config']['fenxiao_logo']; ?>" class="pngFix">
        <?php }else{?>
        <img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_FENXIAO.DS.'logo.png';?>" class="pngFix">
        <?php }?>  
		</a></h1>
    <div class="logo-banner"><?php echo loadshow(1048);?></div>
    <div class="wt-head-search">
      <div class="search-m" id="search-m">
     <div id="search">
          <ul class="tab">
            <li w="search" class="current"><span>商品</span><i class="arrow"></i></li>
            <li w="store"><span>店铺</span></li>
          </ul>
        </div>

        <form action="<?php echo FENXIAO_SITE_URL;?>" method="get" class="search-form" id="head_search_form">
          <input name="w" id="search_wt" value="search" type="hidden">
          <?php
			if ($_GET['keyword']) {
				$keyword = stripslashes($_GET['keyword']);
			} elseif ($output['rec_search_list']) {
                $_stmp = $output['rec_search_list'][array_rand($output['rec_search_list'])];
				$keyword_name = $_stmp['name'];
				$keyword_value = $_stmp['value'];
			} else {
                $keyword = '';
            }
		?>
          <input name="keyword" id="keyword" type="text" class="input-text" value="<?php echo $keyword;?>" maxlength="60" x-webkit-speech lang="zh-CN" onwebkitspeechchange="foo()" placeholder="<?php echo $keyword_name ? $keyword_name : '请输入您要搜索的分销商品关键字';?>" data-value="<?php echo rawurlencode($keyword_value);?>" x-webkit-grammar="builtin:search" autocomplete="off" />
          <input type="submit" id="button" value="搜分销" class="search-submit">
        </form>
        <div class="search-tips" id="search-tips">
          <div class="search-history">
            <div class="title">历史纪录<a href="javascript:void(0);" id="search-his-del">清除</a></div>
            <ul id="search-history-list">
              <?php if (is_array($output['his_search_list']) && !empty($output['his_search_list'])) { ?>
              <?php foreach($output['his_search_list'] as $v) { ?>
              <li><a href="<?php echo urlFenxiao('search', 'index', array('keyword' => $v));?>"><?php echo $v ?></a></li>
              <?php } ?>
              <?php } ?>
            </ul>
          </div>
          <div class="search-hot">
            <div class="title">热门搜索...</div>
            <ul>
              <?php if (is_array($output['rec_search_list']) && !empty($output['rec_search_list'])) { ?>
              <?php foreach($output['rec_search_list'] as $v) { ?>
              <li><a href="<?php echo urlFenxiao('search', 'index', array('keyword' => $v['value']));?>"><?php echo $v['value']?></a></li>
              <?php } ?>
              <?php } ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="keyword">
        <ul>
          <?php if(is_array($output['hot_search']) && !empty($output['hot_search'])) { foreach($output['hot_search'] as $val) { ?>
          <li><a href="<?php echo urlFenxiao('search', 'index', array('keyword' => $val));?>"><?php echo $val; ?></a></li>
          <?php } }?>
        </ul>
      </div>
    </div>
    <div class="head-my-menu">
      <dl class="my-cart">
        <div class="addcart-goods-num"><?php echo $output['cart_goods_num'];?></div>
        <dt><span class="ico"></span><?php echo $lang['wt_ensure_order'];?><i class="arrow"></i></dt>
        <dd>
          <div class="sub-title">
            <h4><?php echo $lang['wt_new_goods'];?></h4>
          </div>
          <div class="incart-goods-box">
            <div class="incart-goods"> <img class="loading" src="<?php echo SHOP_TEMPLATES_URL;?>/images/loading.gif" /> </div>
          </div>
          <div class="checkout"> <span class="total-price">共<i><?php echo $output['cart_goods_num'];?></i><?php echo $lang['wt_kindof_goods'];?></span><a href="<?php echo BASE_SITE_URL;?>/index.php?w=cart" class="btn-cart"><?php echo $lang['wt_bill_goods'];?></a> </div>
        </dd>
      </dl>
    </div>
  </header>
</div>
<nav class="wt-nav <?php if($output['channel']) {echo 'channel-'.$output['channel']['channel_style'].' channel-'.$output['channel']['channel_id'];} ?>">
  <div class="wrapper">
    <div class="category-nav">
      <?php require template('layout/home_goods_class');?>
    </div>
    <ul class="nav">
      <li class="ml10"><a href="<?php echo BASE_SITE_URL;?>" <?php if($output['index_sign'] == 'index' && $output['index_sign'] != '0') {echo 'class="current"';} ?>><span><?php echo $lang['wt_index'];?></span></a></li>
 <?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>
      <?php foreach($output['nav_list'] as $nav){?>
      <?php if($nav['nav_location'] == '1'){?>
      <li><a
        <?php
        if($nav['nav_new_open']) {
            echo ' target="_blank"';
        }
        switch($nav['nav_type']) {
            case '0':
                echo ' href="' . $nav['nav_url'] . '"';
                break;
            case '1':
                echo ' href="' . urlShop('search', 'index',array('cate_id'=>$nav['item_id'])) . '"';
                if (isset($_GET['cate_id']) && $_GET['cate_id'] == $nav['item_id']) {
                    echo ' class="current"';
                }
                break;
            case '2':
                echo ' href="' . urlShop('article', 'article',array('ac_id'=>$nav['item_id'])) . '"';
                if (isset($_GET['ac_id']) && $_GET['ac_id'] == $nav['item_id']) {
                    echo ' class="current"';
                }
                break;
            case '3':
                echo ' href="' . urlShop('activity', 'index', array('activity_id'=>$nav['item_id'])) . '"';
                if (isset($_GET['activity_id']) && $_GET['activity_id'] == $nav['item_id']) {
                    echo ' class="current"';
                }
                break;
        }
        ?>><?php echo $nav['nav_title'];?></a></li>
      <?php }?>
      <?php }?>
      <?php }?>
    </ul>
  </div>
</nav>
