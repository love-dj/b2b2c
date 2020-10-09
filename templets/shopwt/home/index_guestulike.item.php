<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php if(!empty($output['guestulike_goodslist'])&& is_array($output['guestulike_goodslist'])) { ?>
<?php foreach($output['guestulike_goodslist'] as $goods) { ?>
<li id="guest_item"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$goods['goods_id']));?>" title="<?php echo $goods['goods_name'];?>" target="_blank">
			<span class="goods-thumb">
			<img src="<?php echo cthumb($goods['goods_image'], 360,$goods['store_id']);?>" />
			</span>
			<span class="item-info">
				<span class="item-desc"><?php echo $goods['goods_name'];?></span>
				<span class="item-detail"><span class="item-price"><?php echo wtPriceFormatForList($goods['goods_price']);?></span></span>
			</span>
			</a></li>
<?php } ?>
<?php } ?>

