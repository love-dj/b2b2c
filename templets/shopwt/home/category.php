<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/layout.css" rel="stylesheet" type="text/css">
<style type="text/css">
.wt-appbar-tabs a.compare { display: none !important;}
</style>
<div class="wth-container wrapper">
  <div class="wth-category-select">
  <ul>
   <?php foreach ($output['show_goods_class'] as $k=>$v){
	   if($v['is_show']=='1'){continue;}
	   ?>
      <li><a href="#<?php echo $v['gc_id'];?>"><?php echo $v['gc_name'];?></a></li>
      <?php } ?>
   </ul>
  </div>
  <div class="wth-category-all">
    <?php if(!empty($output['show_goods_class']) && is_array($output['show_goods_class'])){?>
    <ul class="wth-category-container" id="categoryList">
      <?php foreach($output['show_goods_class'] as $key=>$gc_list){if($gc_list['is_show']=='1'){continue;}?>
      <li class="classes">
        <div class="title"><i></i>
        <a name="<?php echo $gc_list['gc_id'];?>" href="<?php echo urlShop('search', 'index', array('cate_id' => $gc_list['gc_id']));?>"><?php echo $gc_list['gc_name'];?></a>
        </div>
        <?php if (!empty($gc_list['class2'])) {?>
        <?php foreach ($gc_list['class2'] as $gc_list2) { if($gc_list2['is_show']=='1'){continue;} ?>
	<?php if($gc_list2['is_show']=='1'){ continue;}?>
        <dl>
          <dt><a href="<?php echo urlShop('search', 'index', array('cate_id' => $gc_list2['gc_id']));?>"><?php echo $gc_list2['gc_name'];?></a></dt>
          <dd>
            <?php if(!empty($gc_list2['class3'])){?>
            <?php foreach($gc_list2['class3'] as $key=>$gc_list3){  if($gc_list3['is_show']=='1'){continue;} ?>
	    <?php if($gc_list3['is_show']=='1'){ continue;}?>
            <a href="<?php echo urlShop('search', 'index', array('cate_id' => $gc_list3['gc_id']));?>"><?php echo $gc_list3['gc_name'];?></a>
            <?php }?>
            <?php }?>
          </dd>
        </dl>
        <?php }?>
        <?php }?>
      </li>
      <?php }?>
    </ul>
    <?php }?>
  </div>
</div>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.masonry.js"></script>
<script>
$(function(){
	$("#categoryList").masonry({
		itemSelector : '.classes'
	});
});
</script> 
