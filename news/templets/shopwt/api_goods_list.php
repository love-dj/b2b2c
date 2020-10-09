<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){ ?>

<div class="goods-select-box">
  <div class="arrow"></div>
  <ul id="goods_search_list" class="goods-search-list">
    <?php foreach($output['goods_list'] as $value){ ?>
    <?php $goods_content = array();?>
    <?php $goods_content['url'] = getGoodsUrl($value['goods_id']);?>
    <?php $goods_content['title'] = $value['goods_name'];?>
    <?php $goods_content['image'] = thumb($value, 240);?>
    <?php $goods_content['price'] = $value['goods_price'];?>
    <?php $goods_content['type'] = 'store';?>
    <li wttype="btn_goods_select" goods_url="<?php echo $goods_content['url'];?>" goods_title="<?php echo $goods_content['title'];?>" goods_image="<?php echo $goods_content['image'];?>" goods_price="<?php echo $goods_content['price'];?>" goods_type="<?php echo $goods_content['type'];?>">
      <dl>
        <dt class="name"><a href="<?php echo $goods_content['url'];?>" target="_blank"> <?php echo $goods_content['title'];?> </a></dt>
        <dd class="image"><img title="<?php echo $goods_content['title'];?>" src="<?php echo $goods_content['image'];?>" /></dd>
        <dd class="price"><?php echo $lang['wt_common_price'];?><?php echo $lang['wt_colon'];?><em><?php echo $goods_content['price'];?></em></dd>
      </dl>
      <i><?php echo $lang['api_goods_add'];?></i></li>
    <?php } ?>
  </ul>
  <div class="pagination"><?php echo $output['show_page'];?></div>
</div>
<?php }else { ?>
<div class="no-record"><?php echo $lang['no_record'];?></div>
<?php } ?>
