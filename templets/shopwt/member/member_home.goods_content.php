<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div id="favoritesGoods" class="double">
  <div class="outline">
    <div class="title">
      <h3>商品收藏</h3>
    </div>
    <?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){ ?>
    <div class="wtm-favorites-goods">
      <ul id="favoritesGoodsList" class="jcarousel-skin-tango">
        <?php foreach($output['favorites_list'] as $key=>$favorites){?>
        <li>
          <div class="wtm-goods-thumb-120"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$favorites['goods_id']));?>" target="_blank"><img alt="" src="<?php echo thumb($favorites, 240);?>"></a>
            <div class="wtm-goods-price"><em>￥<?php echo wtPriceFormat($favorites['goods_sale_price']);?></em></div>
          </div>
          <div class="wtm-goods-name"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$favorites['goods_id']));?>" title="<?php echo $favorites['goods_name'];?>" target="_blank"><?php echo $favorites['goods_name'];?></a></div>
        </li>
        <?php } ?>
      </ul>
      <div class="more"><a target="_blank" href="index.php?w=member_favorite_goods&t=fglist">查看收藏的所有商品</a></div>
    </div>
    <?php } else { ?>
    <dl class="null-tip">
      <dt></dt>
      <dd>
        <h4>您还没有收藏商品</h4>
        <h5>收藏的商品将显示最新的促销活动和降价情况</h5>
      </dd>
    </dl>
    <?php } ?>
  </div>
</div>
<div id="favoritesStore" class="normal">
  <div class="outline">
    <div class="title">
      <h3>店铺收藏</h3>
    </div>
    <?php if(!empty($output['favorites_store_list']) && is_array($output['favorites_store_list'])){?>
    <div class="wtm-favorites-store">
      <ul id="favoritesStoreList" class="jcarousel-skin-tango">
        <?php foreach($output['favorites_store_list'] as $key=>$favorites){?>
        <li>
          <div class="wtm-store-pic"><a href="<?php echo urlShop('show_store', 'index', array('store_id' => $favorites['store_id']), $favorites['store_domain']);?>" ><img alt="" src="<?php echo getStoreLogo($favorites['store_avatar']);?>"></a></div>
          <dl>
            <dt class="wtm-goods-name"><a href="<?php echo urlShop('show_store', 'index', array('store_id' => $favorites['store_id']), $favorites['store_domain']);?>" title="<?php echo $favorites['store_name'];?>" ><?php echo $favorites['store_name'];?></a></dt>
            <dd>新品<?php echo $output['goods_count'][$favorites['store_id']];?>件</dd>
          </dl>
        </li>
        <?php } ?>
      </ul>
      <div class="more"><a target="_blank" href="index.php?w=member_favorite_store&t=fslist">查看收藏的所有店铺</a></div>
    </div>
    <?php } else { ?>
    <dl class="null-tip">
      <dt></dt>
      <dd>
        <h4>您还没有收藏店铺</h4>
        <h5>收藏店铺可获知店铺最新商品和促销活动</h5>
      </dd>
    </dl>
    <?php } ?>
  </div>
</div>
