<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<link
	href="<?php echo STATIC_SITE_URL;?>/js/jcarousel/skins/tango/skin.css"
	rel="stylesheet" type="text/css">
<style type="text/css">
.jcarousel-skin-tango .jcarousel-clip-horizontal {
	width: 424px !important;
	height: 96px !important;
}

.jcarousel-skin-tango .jcarousel-item {
	width: 96px !important;
	height: 96px !important;
}

.jcarousel-skin-tango .jcarousel-container-horizontal {
	width: 424px !important;
}
</style>
<div class="choose-goods">
	<ul class="tabs-nav">
		<li class="tabs-selected"><a href="javascript:void(0);"><?php echo $lang['bbs_goods_link'];?></a></li>
		<li><a href="javascript:void(0)"><?php echo $lang['bbs_bought_goods'];?></a></li>
		<li><a href="javascript:void(0)"><?php echo $lang['bbs_favorite_goods'];?></a></li>
	</ul>
	<div class="goods-api tabs-panel">
		<label><i></i><?php echo $lang['bbs_goods_link_tips1'];if(C('taobao_api_isuse')){echo $lang['bbs_goods_link_tips2'];}echo $lang['bbs_goods_link_tips3'];?></label>
		<p>
			<input type="text" id="goods_link" class="text w400" /> <a
				href="javascript:void(0);" wttype="goodsLinkAdd"><?php echo $lang['wt_add'];?></a>
		</p>
	</div>
	<div class="tabs-panel tabs-hide">
		<!-- 商品图片start -->
    <?php if (!empty($output['order_goods'])){?>
    <ul id="mycarousel1" class="jcarousel-skin-tango">
      <?php foreach ($output['order_goods'] as $v) {?>
      <li><a href="javascript:void(0);"
				data-param="{'id':'<?php echo $v['goods_id'];?>','name':'<?php echo $v['goods_name'];?>','price':'<?php echo $v['goods_price'];?>','storeid':'<?php echo $v['store_id'];?>','img':'<?php echo thumb($v, 60);?>','image':'<?php echo $v['goods_image'];?>','uri':'<?php echo urlShop('goods', 'index', array('goods_id'=>$v['goods_id']));?>','type':0}">
					<img title="<?php echo $v['goods_name']?>"
					src="<?php echo thumb($v, 240);?>" />
					<p class="extra"><?php echo $lang['bbs_selected'];?></p>
			</a></li>
      <?php }?>
    </ul>
    <?php } else{?>
    <div class="norecord"><?php echo $lang['bbs_bought_goods_null'];?></div>
    <?php }?>
  </div>
	<div class="tabs-panel tabs-hide">
		<!-- 商品图片start -->
    <?php if (!empty($output['favorites_goods'])){?>
    <ul id="mycarousel2" class="snsgoodsimglist jcarousel-skin-tango">
      <?php foreach ($output['favorites_goods'] as $v) {?>
      <li><a href="javascript:void(0);"
				data-param="{'id':'<?php echo $v['goods_id'];?>','name':'<?php echo $v['goods_name'];?>','price':'<?php echo $v['goods_price'];?>','storeid':'<?php echo $v['store_id'];?>','img':'<?php echo thumb($v, 60);?>','image':'<?php echo $v['goods_image'];?>','uri':'<?php echo urlShop('goods', 'index', array('goods_id'=>$v['goods_id']));?>','type':0}"><img
					title="<?php echo $v['goods_name']?>"
					src="<?php echo thumb($v, 240);?>" />
					<p class="extra"><?php echo $lang['bbs_selected'];?></p> </a></li>
      <?php }?>
    </ul>
    <?php } else{?>
    <div class="norecord"><?php echo $lang['bbs_favorite_goods_null'];?></div>
    <?php }?>
  </div>
	<div class="" wttype="cgError"></div>
	<dl class="selected-goods">
		<dt><?php echo $lang['bbs_selected_goods'];?></dt>
	</dl>
	<div class="handle-bar">
		<a href="Javascript: void(0)" class="button" wttype="insertGoods"><?php echo $lang['bbs_insert_theme'];?></a>
	</div>
</div>
<script
	src="<?php echo STATIC_SITE_URL;?>/js/jcarousel/jquery.jcarousel.min.js"></script>
<script>
var insertAllowedCount = <?php echo intval($_GET['count']);?>;		// 允许插入商品数量
$(function(){
    
	//帖子列表隔行变色
	$(".group-theme-list li:odd").css("background-color","#F8F9FA");
	$(".group-theme-list li:even").css("background-color","#FCFCFC");

	//侧边栏tab切换
	$(".tabs-nav > li > a").click(function(e) {
		var tabs = $(this).parent().parent().children("li");
		var panels = $(this).parent().parent().parent().children(".tabs-panel");
		var index = $.inArray(this, $(this).parent().parent().find("a"));
		tabs.removeClass("tabs-selected")
			.eq(index).addClass("tabs-selected");
		panels.addClass("tabs-hide")
			.eq(index).removeClass("tabs-hide");
		if(index != 0){
			//图片轮换
			$('#mycarousel'+index).unbind().jcarousel({visible: 4,itemFallbackDimension: 300});
		}
	}); 

	// 选项商品事件触发
	$('.tabs-panel').find('li > a').click(function(){
		chooseGoods($(this));
	});


	// According to the product link to add goods
	$('a[wttype="goodsLinkAdd"]').click(function(){

		// Validation choose goods quantity
		var count = insertAllowedCount - $('.selected-goods > dd').length;
		if(count <= 0){
			// 这里怎么加提示？ 不能在继续选择商品
			$('div[wttype="cgError"]').html('<span><?php echo $lang['bbs_goods_error1'];?></span>');
    		window.setTimeout(hideError,5000);	// 5 seconds after the hidden message
			return false;
		}
		
		var link = encodeURIComponent($('#goods_link').val());
		var _uri = BBS_SITE_URL+'/index.php?w=theme&t=check_link&link='+link+'&c_id='+c_id;
		$.getJSON(_uri, function(data){
			if (data){
				$('<dd data-param="{\'id\':\''+data.id+'\',\'name\':\''+data.title+'\',\'price\':\''+data.price+'\',\'storeid\':\''+data.storeid+'\',\'image\':\''+data.img+'\',\'img\':\''+data.image+'\',\'uri\':\''+data.url+'\',\'type\':\''+data.type+'\'}"><em></em><p><img src="'+data.image+'" /></p></dd>')
					.appendTo('.selected-goods').click( function(){ $(this).remove(); });
				return;
            }else{
        		// 这里怎么加提示？ 不能在继续选择商品
        		$('div[wttype="cgError"]').html('<span><?php echo $lang['bbs_goods_error2'];?></span>');
        		window.setTimeout(hideError,5000);	// 5 seconds after the hidden message
        		return;
            }
		});
	});

});
// 选择商品js
function chooseGoods(o){
	// 验证选择商品数量
	var count = insertAllowedCount - $('.selected-goods > dd').length;
	if(count <= 0){
		// 这里怎么加提示？ 不能在继续选择商品
		$('div[wttype="cgError"]').html('<?php echo $lang['bbs_goods_error1'];?>');
		return false;
	}
	var str = o.attr('data-param'); var data_str; eval( "data_str = " + str);
	$('<dd data-param="'+str+'"><em></em><p><img src="'+data_str.img+'" /></p></dd>').appendTo('.selected-goods')
		.click(function(){
			$(this).remove();
			o.removeClass('selected').click(function(){
				chooseGoods($(this));
			});
		});
	o.addClass('selected').unbind();

}
// Hide the error message
function hideError(){
	$('div[wttype="cgError"]').hide("slow", function(){
		$(this).html('').show();
	});
}
</script>