<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php 
if(empty($output['goods_list'])) {
    $block_content = empty($block_content) ? $output['block_content'] : $block_content; 
    $goods_list = unserialize($block_content);
} else {
    $goods_list = $output['goods_list'];
}
?>
<?php if(!empty($goods_list) && is_array($goods_list)){?>

<ul class="goods-list">
  <?php foreach($goods_list as $key=>$val){?>
  <li wttype="goods_item" data-goods-id="<?php echo $val['goods_id'];?>" data-goods-name="<?php echo $val['goods_name'];?>" data-goods-price="<?php echo wtPriceFormat($val['goods_price']);?>"  data-goods-image="<?php echo $val['goods_image'];?>">
    <?php $goods_url = urlShop('goods', 'index', array('goods_id' => $val['goods_id']));?>
    <div class="goods-thumb"> <a href="<?php echo $goods_url;?>" target="_blank" title="<?php echo $val['goods_name'];?>"> <img src="<?php echo cthumb($val['goods_image'], 240);?>"/ alt="<?php echo $val['goods_name'];?>"> </a> </div>
    <dl class="goods-content">
      <dt><a href="<?php echo $goods_url;?>" target="_blank" title="<?php echo $val['goods_name'];?>"><?php echo $val['goods_name'];?></a></dt>
      <dd><?php echo $lang['currency'].wtPriceFormat($val['goods_price']);?></dd>
    </dl>
    <?php if(!empty($output['goods_list'])) { ?>
    <a wttype="btn_module_goods_operate" class="wtbtn-mini" href="javascript:;"><i class="icon-plus"></i>选择添加</a>
    <?php } ?>
  </li>
  <?php } ?>
</ul>
<?php if(!empty($output['goods_list'])) { ?>
<div class="pagination"><?php echo $output['show_page']; ?></div>
<?php } ?>
<?php } else { ?>
<?php if(!empty($output['goods_list'])) { ?>
<div><?php echo $lang['no_record'];?></div>
<?php } ?>
<?php } ?>
