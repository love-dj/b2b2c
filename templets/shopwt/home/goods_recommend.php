<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wth-goods-recommend">
  <div class="title">热销推荐</div>
  <div class="content">
    <?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){ ?>
    <ul class="wth-module-recommend">
      <?php foreach($output['goods_list'] as $k=>$v){?>
      <li>
        <div class="goods-pic"> <a href="<?php echo urlShop('goods','index',array('goods_id'=>$v['goods_id']));?>" target="_blank"><img alt="" src="<?php echo cthumb($v['goods_image'], 240);?>"></a> </div>
        <dl class="goods-content">
          <dt><a href="<?php echo urlShop('goods','index',array('goods_id'=>$v['goods_id']));?>" title="<?php echo $v['goods_name'];?>" target="_blank">
            <?php if ($v['goods_sale_type'] == 1){ ?>
            <span>抢购</span>
            <?php } elseif ($v['goods_sale_type'] == 2) { ?>
            <span>折扣</span>
            <?php } ?>
            <?php echo $v['goods_name'];?></a></dt>
          <dd class="goods-price"><em><?php echo wtPriceFormatForList($v['goods_sale_price']);?></em></dd>
          <dd class="buy-btn"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$v['goods_id']));?>" target="_blank">立即抢购</a></dd>
        </dl>
      </li>
      <?php } ?>
    </ul>
    <?php } else { ?>
    <div class="noguess">暂无商品向您推荐</div>
    <?php }?>
  </div>
</div>
