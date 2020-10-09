<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){?>

<ul class="goods-list">
  <?php foreach($output['goods_list'] as $key=>$val){?>
  <li>
    <div class="goods-thumb"><img src="<?php echo thumb($val, 240);?>"/></div>
    <dl class="goods-content">
      <dt><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['goods_id']));?>" target="_blank"><?php echo $val['goods_name'];?></a> </dt>
      <dd>销售价格：<?php echo $lang['currency'].wtPriceFormat($val['goods_price']);?>
    </dl>
    <a  href="<?php echo urlShop('store_sale_combo', 'choosed_goods', array('gid' => $val['goods_id']));?>" class="wtbtn-mini">选择商品</a> 
    </li>
  <?php } ?>
</ul>
<div class="pagination"><?php echo $output['show_page'];?></div>
<?php } else { ?>
<div><?php echo $lang['no_record'];?></div>
<?php } ?>