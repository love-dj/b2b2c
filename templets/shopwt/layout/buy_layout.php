<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
<meta name="description" content="<?php echo $output['seo_description']; ?>" />
<meta name="author" content="ShopWT">
<meta name="copyright" content="ShopWT Inc. All Rights Reserved">
<style type="text/css">
body {.wt-appbar-tabs a.compare { display: none !important;}
</style>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/common.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/cart.css" rel="stylesheet" type="text/css">
<link href="<?php echo STATIC_SITE_URL;?>/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<script>
var PRICE_FORMAT = '<?php echo $lang['currency'];?>%s';var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var SITEURL = '<?php echo BASE_SITE_URL;?>';var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>';var STATIC_SITE_URL = '<?php echo STATIC_SITE_URL;?>';var STATIC_SITE_URL = '<?php echo STATIC_SITE_URL;?>';var SHOP_TEMPLATES_URL = '<?php echo SHOP_TEMPLATES_URL;?>';
Number.prototype.toFixed = function(d)
{
    var s=this+"";if(!d)d=0;
    if(s.indexOf(".")==-1)s+=".";s+=new Array(d+1).join("0");
    if (new RegExp("^(-|\\+)?(\\d+(\\.\\d{0,"+ (d+1) +"})?)\\d*$").test(s))
    {
        var s="0"+ RegExp.$2, pm=RegExp.$1, a=RegExp.$3.length, b=true;
        if (a==d+2){a=s.match(/\d/g); if (parseInt(a[a.length-1])>4)
        {
            for(var i=a.length-2; i>=0; i--) {a[i] = parseInt(a[i])+1;
            if(a[i]==10){a[i]=0; b=i!=1;} else break;}
        }
        s=a.join("").replace(new RegExp("(\\d+)(\\d{"+d+"})\\d$"),"$1.$2");
    }if(b)s=s.substr(1);return (pm+s).replace(/\.$/, "");} return this+"";
};
</script>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.min.js"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/common.js"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.poshytip.min.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/dialog/dialog.js" id="wt_dialog" charset="utf-8"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.scrollLoading-min.js" charset="utf-8"></script>
<?php if ($_GET['w'] != 'buy_vr') {?>
<script src="<?php echo STATIC_SITE_URL;?>/js/goods_cart.js"></script>
<?php } else { ?>
<script src="<?php echo STATIC_SITE_URL;?>/js/buy_vr.js"></script>
<?php } ?>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo STATIC_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo STATIC_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
$(function(){
	$("img[rel='lazy']").scrollLoading();
});
</script>
</head>
<body>
<?php require_once template('layout/layout_top');?>  <header class="wtc-head-box">
    <div class="site-logo"><a href="<?php echo BASE_SITE_URL;?>"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$output['setting_config']['site_logo']; ?>" class="pngFix"></a></div>
    <?php if ($_GET['t'] != 'pd_pay' && $_POST['payment_code'] != 'wxpay') { ?>
    <ul class="wtc-flow">
      <li class="<?php echo $output['buy_step'] == 'step1' ? 'current' : '';?>"><i class="step1"></i>
        <p><?php echo $lang['cart_index_ensure_order'];?></p>
        <sub></sub>
        <div class="hr"></div>
      </li>
      <li class="<?php echo $output['buy_step'] == 'step2' ? 'current' : '';?>"><i class="step2"></i>
        <p><?php echo $lang['cart_index_ensure_info'];?></p>
        <sub></sub>
        <div class="hr"></div>
      </li>
      <li class="<?php echo $output['buy_step'] == 'step3' ? 'current' : '';?>"><i class="step3"></i>
        <p><?php echo $lang['cart_index_payment'];?></p>
        <sub></sub>
        <div class="hr"></div>
      </li>
      <li class="<?php echo $output['buy_step'] == 'step4' ? 'current' : '';?>"><i class="step4"></i>
        <p><?php echo $lang['cart_index_buy_finish'];?></p>
        <sub></sub>
        <div class="hr"></div>
      </li>
    </ul>
    <?php } ?>
  </header>
<div class="wtc-wrapper">

  <?php require_once($tpl_file);?>

</div><?php require_once template('footer');?>
<script>
//提示信息
$('.tip').poshytip({
	className: 'tip-yellowsimple',
	showOn: 'hover',
	alignTo: 'target',
	alignX: 'center',
	alignY: 'top',
	offsetX: 0,
	offsetY: 5,
	allowTipHover: false
});
</script>
</body>
</html>
