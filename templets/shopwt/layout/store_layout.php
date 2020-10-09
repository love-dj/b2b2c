<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php include template('layout/goods_layout');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/shop.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_TEMPLATES_URL?>/css/store_decoration.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_TEMPLATES_URL;?>/store/style/<?php echo $output['store_theme'];?>/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/member.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/shop.js" charset="utf-8"></script>
<div id="store_decoration_content" class="background" style="<?php echo $output['decoration_background_style'];?>">
<?php if(!empty($output['decoration_nav'])) {?>
  <style>
<?php echo $output['decoration_nav']['style'];?>
</style>
  <?php } ?>
  <?php require_once($tpl_file); ?>
  <div class="clear">&nbsp;</div>
</div>
<?php include template('footer');?>
<script type="text/javascript">
$(function(){
	var storeTrends	= true;
	$('.favorites').mouseover(function(){
		var $this = $(this);
		if(storeTrends){
			$.getJSON('index.php?w=show_store&t=ajax_store_trend_count&store_id=<?php echo $output['store_info']['store_id'];?>', function(data){
				$this.find('li:eq(2)').find('a').html(data.count);
				storeTrends = false;
			});
		}
	});

	$('a[wttype="share_store"]').click(function(){
		<?php if ($_SESSION['is_login'] !== '1'){?>
		login_dialog();
		<?php } else {?>
		ajax_form('sharestore', '分享店铺', 'index.php?w=member_snsindex&t=sharestore_one&inajax=1&sid=<?php echo $output['store_info']['store_id'];?>');
		<?php }?>
	});
});

</script>
</body></html>