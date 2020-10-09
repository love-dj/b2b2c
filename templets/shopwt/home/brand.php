<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/layout.css" rel="stylesheet" type="text/css">
<style type="text/css">body {background: #F5F5F5;}</style>
<div class="wth-container wrapper">
  <div class="wth-brand-class">
    <div class="wth-brand-class-tab">
      <?php if(!empty($output['brand_class'])){?>
      <ul class="tabs-nav">
		<li class="tabs-selected"><a href="javascript:void(0);">推荐品牌 </a></li>
        <?php $i = 0;foreach($output['brand_class'] as $key=>$brand){$i++;?>
        <li class="<?php if ($i == 0) { echo 'tabs-selected';} ?>"><a href="javascript:void(0);"><?php echo $brand['brand_class'];?></a></li>
        <?php }?>
      </ul>
      <?php }?>
    </div>
	<?php if(!empty($output['brand_r'])){?>
	<div class="wth-barnd-list tabs-panel">
      <ul>
		<?php foreach($output['brand_r'] as $key=>$brand_r){?>
				<li>
				  <dl>
					<dt><a href="<?php echo urlShop('brand', 'list', array('brand'=>$brand_r['brand_id']));?>"><img src="<?php echo brandImage($brand_r['brand_pic']);?>" alt="<?php echo $brand_r['brand_name'];?>" /></a></dt>
					<dd><a href="<?php echo urlShop('brand', 'list', array('brand'=>$brand_r['brand_id']));?>"><?php echo $brand_r['brand_name'];?></a></dd>
				  </dl>
				</li>
		<?php }?>
	</ul>
	</div>
	<?php }?>
    <?php if(!empty($output['brand_c'])) {?>
    <?php $i = 0; foreach($output['brand_c'] as $key=>$brand_c){$i++; ?>
    <div class="wth-barnd-list tabs-panel <?php if ($i != 0) { echo 'tabs-hide';} ?>">
      <ul>
        <?php if ($brand_c['image']){?>
        <?php foreach($brand_c['image'] as $key=>$brand){?>
        <li>
          <dl>
            <dt><a href="<?php echo urlShop('brand', 'list', array('brand'=>$brand['brand_id']));?>"><img src="<?php echo brandImage($brand['brand_pic']);?>" alt="<?php echo $brand['brand_name'];?>"/></a></dt>
            <dd><a href="<?php echo urlShop('brand', 'list', array('brand'=>$brand['brand_id']));?>"><?php echo $brand['brand_name'];?></a></dd>
          </dl>
        </li>
        <?php }?>
        <?php }?>
      </ul>
      <?php if ($brand_c['text']){?>
      <div class="wth-barnd-list-text"><strong>更多品牌：</strong>
        <?php foreach($brand_c['text'] as $key=>$brand){ ?>
        <a href="<?php echo urlShop('brand', 'list', array('brand'=>$brand['brand_id']));?>"><?php echo $brand['brand_name'];?></a>
        <?php } ?>
      </div>
      <?php } ?>
    </div>
    <?php }?>
    <?php }?>
  </div>
</div>
<script>
//首页Tab标签卡滑门切换
$(".tabs-nav > li > a").live('mousedown', (function(e) {
	if (e.target == this) {
		var tabs = $(this).parents('ul:first').children("li");
		var panels = $(this).parents('.wth-brand-class:first').children(".tabs-panel");
		var index = $.inArray(this, $(this).parents('ul:first').find("a"));
		if (panels.eq(index)[0]) {
			tabs.removeClass("tabs-selected").eq(index).addClass("tabs-selected");
			panels.addClass("tabs-hide").eq(index).removeClass("tabs-hide");
		}
	}
}));
</script>