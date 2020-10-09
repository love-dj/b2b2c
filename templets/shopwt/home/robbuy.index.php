<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/group.css" rel="stylesheet" type="text/css">
<style type="text/css">
.wth-breadcrumb-box {display: none; }
</style>
<div class="wtg-banner mb10">
    <?php if (!empty($output['picArr'])) { ?>
    <div class="wtg-slides-banner">
      <ul id="fullScreenSlides" class="full-screen-slides">
        <?php foreach($output['picArr'] as $p) { ?>
        <li style="background: url(<?php echo UPLOAD_SITE_URL.'/'.ATTACH_LIVE.'/'.$p[0];?>) 50% 0% no-repeat <?php echo $p[1];?>;"><a href="<?php echo $p[2];?>" target="_blank"></a></li>
        <?php } ?>
      </ul>
    </div>
    <?php } ?>
</div>

<div class="wtg-container">
    <div class="wtg-category" id="wtgCategory">
    <h3><a href="<?php echo urlShop('robbuy', 'robbuy_list'); ?>" title="在线抢购">在线抢购</a></h3>
    <ul>
<?php $i = 0; $names = $output['robbuy_classes']['name']; foreach ((array) $output['robbuy_classes']['children'][0] as $v) { if (++$i > 6) break; ?>
      <li><a href="<?php echo urlShop('robbuy', 'robbuy_list', array('class' => $v)); ?>"><?php echo $names[$v]; ?></a></li>
<?php } ?>
    </ul>
    <h3><a href="<?php echo urlShop('robbuy', 'vr_robbuy_list'); ?>" title="虚拟抢购">虚拟抢购</a></h3>
    <ul>
<?php $i = 0; $names = $output['robbuy_vr_classes']['name']; foreach ((array) $output['robbuy_vr_classes']['children'][0] as $v) { if (++$i > 6) break; ?>
      <li><a href="<?php echo urlShop('robbuy', 'vr_robbuy_list', array('vr_class' => $v)); ?>"><?php echo $names[$v]; ?></a></li>
<?php } ?>
    </ul>
  </div>

  <div class="wtg-content">

    <div class="group-list">
      <div class="wtg-recommend-title">
        <h3>在线抢购</h3>
        <a href="<?php echo urlShop('robbuy', 'robbuy_list'); ?>" class="more">更多</a></div>
      <?php if (!empty($output['robbuy'])) { ?>
      <ul>
        <?php foreach ($output['robbuy'] as $robbuy) { ?>
        <li class="<?php echo $output['current'];?>">
          <div class="wtg-list-content"> <a title="<?php echo $robbuy['robbuy_name'];?>" href="<?php echo $robbuy['robbuy_url'];?>" class="pic-thumb" target="_blank"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" rel="lazy" data-url="<?php echo gthumb($robbuy['robbuy_image'],'mid');?>" alt=""></a>
            <h3 class="title"><a title="<?php echo $robbuy['robbuy_name'];?>" href="<?php echo $robbuy['robbuy_url'];?>" target="_blank"><?php echo $robbuy['robbuy_name'];?></a></h3>
            <?php list($integer_part, $decimal_part) = explode('.', wtPriceFormat($robbuy['robbuy_price']));?>
            <div class="item-prices"> <span class="price"><i><?php echo $lang['currency'];?></i><?php echo $integer_part;?><em>.<?php echo $decimal_part;?></em></span>
              <div class="dock"><span class="limit-num"><?php echo $robbuy['robbuy_rebate'];?>&nbsp;<?php echo $lang['text_zhe'];?></span> <del class="orig-price"><?php echo $lang['currency'].wtPriceFormat($robbuy['goods_price']);?></del></div>
              <span class="sold-num"><em><?php echo $robbuy['buy_quantity']+$robbuy['virtual_quantity'];?></em><?php echo $lang['text_piece'];?><?php echo $lang['text_buy'];?></span><a href="<?php echo $robbuy['robbuy_url'];?>" target="_blank" class="buy-button">去看看</a></div>
          </div>
        </li>
        <?php } ?>
      </ul>
      <?php } else { ?>
      <div class="norecommend">暂无线上抢购推荐</div>
      <?php } ?>
    </div>
    <div class="group-list mt30">
      <div class="wtg-recommend-title">
        <h3>虚拟抢购</h3>
        <a href="<?php echo urlShop('robbuy', 'vr_robbuy_list'); ?>" class="more">更多</a></div>
      <?php if (!empty($output['vr_robbuy'])) { ?>
      <ul>
        <?php foreach($output['vr_robbuy'] as $robbuy) { ?>
        <li class="<?php echo $output['current'];?>">
          <div class="wtg-list-content"> <a title="<?php echo $robbuy['robbuy_name'];?>" href="<?php echo $robbuy['robbuy_url'];?>" class="pic-thumb" target="_blank"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" rel="lazy" data-url="<?php echo gthumb($robbuy['robbuy_image'],'mid');?>" alt=""></a>
            <h3 class="title"><a title="<?php echo $robbuy['robbuy_name'];?>" href="<?php echo $robbuy['robbuy_url'];?>" target="_blank"><?php echo $robbuy['robbuy_name'];?></a></h3>
            <?php list($integer_part, $decimal_part) = explode('.', wtPriceFormat($robbuy['robbuy_price']));?>
            <div class="item-prices"> <span class="price"><i><?php echo $lang['currency'];?></i><?php echo $integer_part;?><em>.<?php echo $decimal_part;?></em></span>
              <div class="dock"><span class="limit-num"><?php echo $robbuy['robbuy_rebate'];?>&nbsp;<?php echo $lang['text_zhe'];?></span> <del class="orig-price"><?php echo $lang['currency'].wtPriceFormat($robbuy['goods_price']);?></del></div>
              <span class="sold-num"><em><?php echo $robbuy['buy_quantity']+$robbuy['virtual_quantity'];?></em><?php echo $lang['text_piece'];?><?php echo $lang['text_buy'];?></span><a href="<?php echo $robbuy['robbuy_url'];?>" target="_blank" class="buy-button">去看看</a></div>
          </div>
        </li>
        <?php } ?>
      </ul>
      <?php } else{ ?>
      <div class="norecommend">暂无虚拟抢购推荐</div>
      <?php } ?>
    </div>
  </div>
</div>
