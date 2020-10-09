<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<style type="text/css">
body {background: #F5F5F5;}
#box { background: #FFF; width: 238px; height: 410px; margin: -390px 0 0 0; display: block; border: solid 4px #E4393C; position: absolute; z-index: 999; opacity: .5 }
#infscr-loading { display: none; }
</style>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/layout.css" rel="stylesheet" type="text/css">

<div class="wth-container wrapper">
  <div class="wtp-category">
    <ul>
      <input type="hidden" id="sc_id" value="<?php echo intval($_GET['sc_id'])>0?intval($_GET['sc_id']):'';?>"/>
      <li><a class="<?php echo intval($_GET['gc_id']) <= 0?'selected':'';?>" href="<?php echo urlShop('sale','index');?>">所有分类</a></li>
      <?php foreach ($output['goods_class'] as $k=>$v){?>
      <li><a class="<?php echo intval($_GET['gc_id']) == $v['gc_id']?'selected':'';?>" href="<?php echo urlShop('sale','index',array('gc_id'=>$v['gc_id']));?>"}'><?php echo $v['gc_name'];?></a></li>
      <?php } ?>
    </ul>
  </div>
  <div id="saleGoods">
    <?php require(BASE_TPL_PATH.'/home/sale.item.php');?>
  </div>
  <div class="tc mt20 mb20">
    <div class="pagination" id="page-nav"></div>
  </div>
</div>
<div id="page-more"><a href="index.php?w=sale&gc_id=<?php echo $_GET['gc_id'];?>&curpage=2"></a></div>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.raty/jquery.raty.min.js"></script> 
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.masonry.js" type="text/javascript"></script> 
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.infinitescroll.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/fly/jquery.fly.min.js" charset="utf-8"></script> 
<!--[if lt IE 10]>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/fly/requestAnimationFrame.js" charset="utf-8"></script>
<![endif]-->
<script>
var $container = $('#saleGoods');
$container.masonry({
    columnWidth: 305,
    itemSelector: '.item'
});
$(function(){
	$container.infinitescroll({  
        navSelector : '#page-more',
        nextSelector : '#page-more a',
        itemSelector : '.item',
        loading: {
        	selector:'#page-nav',
            img: '<?php echo SHOP_TEMPLATES_URL;?>/images/loading.gif',
            msgText:'努力加载中...',
            maxPage : <?php echo $output['total_page'];?>,
            finishedMsg : '没有记录了',
            finished : function() {
            	$('.raty').raty({
                    path: "<?php echo STATIC_SITE_URL;?>/js/jquery.raty/img",
                    readOnly: true,
                    width: 100,
                    score: function() {
                      return $(this).attr('data-score');
                    }
                });
            }
        }
    },function(newElements){
		var $newElems = $(newElements);
		$container.masonry('appended', $newElems, true);
	});

	$('.raty').raty({
        path: "<?php echo STATIC_SITE_URL;?>/js/jquery.raty/img",
        readOnly: true,
        width: 100,
        score: function() {
          return $(this).attr('data-score');
        }
    });
    // 加入购物车
    $(window).resize(function() {
        $('#saleGoods').on('click', 'a[wttype="add_cart"]', function() {
            flyToCart($(this));
        });
    });
    $('#saleGoods').on('click', 'a[wttype="add_cart"]', function() {
        flyToCart($(this));
    });
     function flyToCart($this) {
        var rtoolbar_offset_left = $("#rtoolbar_cart").offset().left;
        var rtoolbar_offset_top = $("#rtoolbar_cart").offset().top-$(document).scrollTop();
        var img = $this.parents('.scope:first').find('img:first').attr('src');
        var data_gid = $this.attr('data-gid');
        var flyer = $('<img class="u-flyer" src="'+img+'" style="z-index:999">');
        flyer.fly({
            start: {
                left: $this.offset().left,
                top: $this.offset().top-$(document).scrollTop()-450
            },
            end: {
                left: rtoolbar_offset_left,
                top: rtoolbar_offset_top,
                width: 0,
                height: 0
            },
            onEnd: function(){
                addcart(data_gid,1,'');
                flyer.remove();
            }
        });
     }
});
</script> 