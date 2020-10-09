<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/index.css" rel="stylesheet" type="text/css">
<script src="<?php echo STATIC_SITE_URL;?>/js/waypoints.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/home_index.js" charset="utf-8"></script>
<style type="text/css">
.category { display:block !important; }
</style>
<!--[if IE 6]>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/ie6.js" charset="utf-8"></script>
<![endif]-->
<div class="clear"></div>
<div class="i-slides-con"> <?php echo $output['web_html']['index_pic'];?>
  <div class="right-floor">
    <div class="right-bannder-content">
    <div class="title">
      <h3>商城快报</h3>
    </div>
     <div class="news">
     <ul>
      <?php if(!empty($output['show_article']['notice']['list']) && is_array($output['show_article']['notice']['list'])) { ?>
          <?php foreach($output['show_article']['notice']['list'] as $val) { ?>
          <li><a target="_blank" href="<?php echo empty($val['article_url']) ? urlShop('article', 'show',array('article_id'=> $val['article_id'])):$val['article_url'] ;?>" title="<?php echo $val['article_title']; ?>"><?php echo str_cut($val['article_title'],38);?> </a>
          </li>
          <?php } ?>
          <?php } ?>
    </ul>
      </div>
      <div class="ntrance">
      <ul>
        <li><a rel="nofollow" href="<?php echo urlShop('invite', 'index');?>" target="_self"><i class="i_ico01"></i>推广返利</a></li>
		<li><a rel="nofollow" href="<?php echo BASE_SITE_URL;?>/other/service/index.html" target="_blank"><i class="i_ico02"></i>7大服务</a></li>
		<li><a rel="nofollow" href="<?php echo BASE_SITE_URL;?>/other/guide/index.html" target="_blank"><i class="i_ico03"></i>导购流程</a></li>
		<li><a rel="nofollow" href="<?php echo DELIVERY_SITE_URL;?>" target="_self"><i class="i_ico04"></i>物流自提</a></li>
       <li><a rel="nofollow" href="<?php echo urlShop('show_joinin', 'index');?>" target="_self"><i class="i_ico05"></i>招商入驻</a></li>
       <li><a rel="nofollow" href="<?php echo urlShop('seller_login','show_login');?>" target="_self"><i class="i_ico06"></i>商家管理</a></li>
       <li><a rel="nofollow" href="<?php echo urlShop('pointprod', 'plist');?>" target="_self"><i class="i_ico07"></i>兑换礼品</a></li>
       <li><a rel="nofollow" href="<?php echo urlShop('pointvoucher', 'index');?>" target="_self"><i class="i_ico08"></i>代金券</a></li>
       <li><a rel="nofollow" href="<?php echo urlShop('pointcoupon', 'index');?>" target="_self"><i class="i_ico09"></i>优惠券</a></li>
      </ul>
    </div>
		
		</div>
  </div>
</div>

<!--首页打折-->
<div class="wti-sk wrapper">
  <div class="sk_inner">
    <div class="sk_hd"><a class="sk_hd_lk" href="javascript:;">
      <div class="sk_tit">打折秒杀</div>
      <div class="sk_subtit">FLASH DEALS</div>
      <i class="sk_ico"></i>
      <div class="sk_desc">本场距离结束还剩</div>
      <div class="sk_cd">
        <div count_down="222091421" class="cd time-remain">
          <div class="cd_item cd_day"><span class="cd_item_txt" time_id="d">00</span></div>
          <div class="cd_item cd_hour"><span class="cd_item_txt" time_id="h">00</span></div>
          <div class="cd_item cd_minute"><span class="cd_item_txt" time_id="m">00</span></div>
          <div class="cd_item cd_second"><span class="cd_item_txt" time_id="s">00</span></div>
        </div>
      </div>
      </a></div>
		<div class="sk_bd">
		  <div class="sk_list slider_list">
			<div class="sk_list_inner">
				<div class="goods_wrapper">
					<?php if(!empty($output['xianshi_item']) && is_array($output['xianshi_item'])) { ?>
					<?php foreach($output['xianshi_item'] as $val) { ?>
				  <div class="sk_item"><a class="sk_item_lk" href="<?php echo urlShop('goods','index',array('goods_id'=> $val['goods_id']));?>" target="_blank" title="<?php echo $val['goods_name']; ?>">
					<div class="goods-thumb"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo thumb($val, 240);?>" rel="lazy"></div>
					<p class="sk_item_name"><?php echo $val['goods_name']; ?></p>
					<div class="sk_item_price"><span class="sk_item_price_new"><span><?php echo wtPriceFormatForList($val['xianshi_price']); ?></span></span><span class="sk_item_price_origin"><span><?php echo wtPriceFormatForList($val['goods_price']);?></span></span></div>
					</a></div>
					<?php } ?>
					<?php } ?>
				</div>
					<div class="arrow pre"></div>
					<div class="arrow next"></div>
			</div>
		  </div>
		  <div class="sk_chn">
			  <div class="sk_chn_inner">
					<?php echo rec(1);?>
			  </div> 
		  </div>
		</div>
	  </div>
	</div>
</div>
<!--打折end-->
<!--商品模块-->
<div class="i-sale-con wrapper">
  <?php echo $output['web_html']['index_sale'];?>
</div>
<!--商品模块end-->
<div class="wrapper mt30">
	<?php echo loadshow(11);?>
</div>
<?php echo $output['web_html']['index'];?> 
</div>
<!--品牌推荐-->
<div class="wrapper mt30 index-brand">
<h2 class="text-title"><span class="text-ctn">品牌推荐</span></h2>
  <ul class="logo-list">
    <?php if(!empty($output['brand_r'])){?>
    <?php foreach($output['brand_r'] as $key=>$brand_r){?>
    <li> <a target="_blank" href="<?php echo urlShop('brand', 'list',array('brand'=>$brand_r['brand_id']));?>" alt="<?php echo $brand_r['brand_name'];?>" title="<?php echo $brand_r['brand_name'];?>"><img width="120" height="40" src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo brandImage($brand_r['brand_pic']);?>" rel="lazy" /><span><?php echo $brand_r['brand_name'];?></span></a></li>
    <?php } }?>
  </ul>
</div>
<!--品牌推荐 end-->
<!--专题推荐-->
<?php if(!empty($output['special_list'])&&count($output['special_list'])>2){?>
<div class="com-floor mt30">
<h2 class="text-title"><span class="text-ctn">专题推荐</span></h2>
		<div class="slider_wrapper">
		<?php foreach($output['special_list'] as $special){?>
		<div class="box slider_item">
			<div class="box_hd">
				<a class="box_hd_lk" href="<?php echo urlShop('special','special_detail', array('special_id'=>$special['special_id']));?>" target="_blank"><i></i><h3 class="box_tit" title="<?php echo $special['special_title'];?>"><?php echo $special['special_title'];?></h3><span class="box_subtit" title="<?php echo $special['special_stitle'];?>"><?php echo $special['special_stitle'];?></span></a>
			</div>
			<div class="box_bd">
				<a class="special_lk" title="<?php echo $special['special_stitle'];?>" href="<?php echo urlShop('special','special_detail', array('special_id'=>$special['special_id']));?>" target="_blank"><div class="special_img"><img src="<?php echo getNEWSSpecialImageUrl($special['special_image_min']);?>"></div></a>
			</div>
		</div>
		<?php }?>
		</div>
</div>
<?php }?>
<!--专题推荐 end-->
<div class="wrapper">
  <div class="mt30"><?php echo loadshow(9,'html');?></div>
</div>
<!--猜你喜欢-->
<div class="wrapper mt30">
<div class="wti-floor">
	<h2 class="text-title"><span class="text-ctn">猜你喜欢</span></h2>
	<ul class="wti-love" id="guestulike">
			 <?php require(BASE_TPL_PATH.'/home/index_guestulike.item.php');?>
			</ul>
		<div class="tc mt30 mb20">
			<div class="pagination" id="page-nav"></div>
		</div>
		
	</div>
	<div id="page-more"><a href="index.php?w=index&t=index_guestulike&curpage=2"></a></div>
</div>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.masonry.js" type="text/javascript"></script> 
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.infinitescroll.js" type="text/javascript"></script>
<script>
var $container = $('#guestulike');
$container.masonry({
    columnWidth: 242,
    itemSelector: '#guest_item'
});
$(function(){
$(".sk_list").slide({ effect: "leftLoop", mainCell: ".goods_wrapper", prevCell: ".pre", nextCell: ".next", vis: 4,interTime:4000 });
	$container.infinitescroll({  
        navSelector : '#page-more',
        nextSelector : '#page-more a',
        itemSelector : '#guest_item',
        loading: {
        	selector:'#page-nav',
            img: '<?php echo SHOP_TEMPLATES_URL;?>/images/loading.gif',
            msgText:'努力加载中...',
            maxPage :<?php echo $output['guestulike_totalpage'];?>,
            finishedMsg : '我是有底线哦~',
            finished : function() {
            	
            }
        }
    },function(newElements){
		var $newElems = $(newElements);
		$container.masonry('appended', $newElems, true);
	});
});
</script>
<!--猜你喜欢 end-->
<div id="nav_box">
  <ul>
<?php if (is_array($output['lc_list']) && !empty($output['lc_list'])) {$i=0 ?>
<?php foreach($output['lc_list'] as $v) { $i++?>
<li class="nav_h_<?php echo $i;?> <?php if($i==1) echo 'hover'?>"><a href="javascript:;" class="num"><?php echo $v['value']?></a> <a href="javascript:;" class="word"><?php echo $v['name']?></a></li>
<?php }} ?>
  </ul>
</div>

