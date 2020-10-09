<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<title><?php $output['list']['special_desc'];?></title>
<link rel="stylesheet" type="text/css" href="<?php echo STATIC_SITE_URL; ?>/wap/css/main.css">
<link rel="stylesheet" type="text/css" href="<?php echo STATIC_SITE_URL; ?>/wap/css/index.css">
</head>
<body>
<div class="main" id="main-container">
<?php
foreach ((array) $output['list']['list'] as $v) {
    foreach ($v as $kk => $vv) {
        require 'mb_special_item.module_' . $kk . '.php';
        break;
    }
}
?>
</div>

<script type="text/javascript" src="<?php echo MOBILE_STATIC_SITE_URL; ?>/js/zepto.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo MOBILE_STATIC_SITE_URL; ?>/js/swipe.js" charset="utf-8"></script>
<script type="text/javascript">
$(function() {
    $('.show_list').each(function() {
        if ($(this).find('.item').length < 2) {
            return;
        }

        Swipe(this, {
            startSlide: 2,
            speed: 400,
            auto: 3000,
            continuous: true,
            disableScroll: false,
            stopPropagation: false,
            callback: function(index, elem) {},
            transitionEnd: function(index, elem) {}
        });
    });

                $('[wttype="btn_item"]').on('click', function() {
                    var type = $(this).attr('data-type');
                    var data = $(this).attr('data-data');
                    if(typeof window.android != 'undefined') {
                        window.android.mb_special_item_click(type, data);
                    }
                    return false;
                });

});
</script>

</body>
</html>
