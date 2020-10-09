<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="content" wt_type="current_display_mode">
  <?php if(!empty($output['b_goods_list']) && is_array($output['b_goods_list'])){?>
  <ul class="wth-booth-list">
    <?php foreach($output['b_goods_list'] as $value){?>
    <li wttype_goods="<?php echo $value['goods_id'];?>" wttype_store="<?php echo $value['store_id'];?>">
        <div class="goods-pic"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$value['goods_id']));?>" target="_blank" title="<?php echo $value['goods_name'];?>"><img src="<?php echo thumb($value, 240);?>" title="<?php echo $value['goods_name'];?>" alt="<?php echo $value['goods_name'];?>" /></a> </div>
        <?php if (C('robbuy_allow') && $value['goods_sale_type'] == 1) {?>
        <div class="goods-sale"><span>抢购商品</span></div>
        <?php } elseif (C('sale_allow') && $value['goods_sale_type'] == 2)  {?>
        <div class="goods-sale"><span>限时折扣</span></div>
        <?php }?>
          <div class="goods-name"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$value['goods_id']));?>" target="_blank" title="<?php echo $value['goods_jingle'];?>"><?php echo $value['goods_name'];?></a></div>
          <div class="goods-price" title="商品价格<?php echo $lang['wt_colon'].$lang['currency'].wtPriceFormat($value['goods_sale_price']);?>"><?php echo $lang['currency'];?><?php echo wtPriceFormat($value['goods_sale_price']);?><span>推广</span></div>
    </li>
    <?php }?>
  </ul>
  <?php }?>
</div>
