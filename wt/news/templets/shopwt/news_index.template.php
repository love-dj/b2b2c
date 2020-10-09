<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php if($_GET['t'] === 'news_index_preview') { ?>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo NEWS_SITE_URL;?>/static/js/common.js" charset="utf-8"></script>
<link href="<?php echo NEWS_SITE_URL.DS;?>templets/shopwt/css/common.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.VMiddleImg.js"></script>
<?php } ?>
<link href="<?php echo NEWS_SITE_URL.DS;?>templets/news_special.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jcarousel/jquery.jcarousel.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.slides.min.js" charset="utf-8"></script>
<link href="<?php echo STATIC_SITE_URL;?>/js/jcarousel/skins/personal/skin.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
$(document).ready(function() {
    if($('#index1_1_1_content').children().length > 0) {
        $('#index1_1_1_content').slidesjs({
            play: {
                active: true,
                    interval: 5000,
                    auto: true,
                    pauseOnHover: false,
                    restartDelay: 2500
            },
            callback: {
                complete: function(number) {
                    var $item = $(".slidesjs-pagination-item");
                    $item.removeClass("current");
                    $item.eq(number - 1).addClass("current");
                }
            },
                width: 380,
                height: 260
        });
        $(".slidesjs-pagination-item").eq(0).addClass("current");
    }

    //图片延迟加载
    $(".lazyload_container").wt_lazyload_init();
    $("img").wt_lazyload();

    //计算自定义块高度
    var frames = $('.news-module-frame');
    $.each(frames, function(index, frame) {
        var boxs = $(frame).find('[wttype="news_module_content"]');
        var height = 0;
        $.each(boxs, function(index2, box) {
            var box_height = $(box).height();
            if(box_height > height) {
                height = box_height;
            }
        });
        boxs.height(height);
    });
});
</script>
<?php if($_GET['t'] === 'news_index_preview') { ?>
<div style="width:1000px;margin:0 auto;">
<?php } ?>
<?php if(!empty($output['module_list']) && is_array($output['module_list'])) {?>
<?php foreach($output['module_list'] as $key=>$value) {?>
<?php $module_content = unserialize(base64_decode($value['module_content']));?>
<?php if($value['module_type'] != 'index' && $value['module_type'] != 'what') { ?>
<textarea class="lazyload_container" rows="10" cols="30" style="display:none;">
<?php } ?>
<?php require($value['module_template']);?>
<?php if($value['module_type'] != 'index' && $value['module_type'] != 'what') { ?>
</textarea>
<?php } ?>
<?php } ?>
<?php } ?>
<?php if($_GET['t'] === 'news_index_preview') { ?>
</div>
<?php } ?>

