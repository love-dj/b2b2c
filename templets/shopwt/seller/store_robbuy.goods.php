<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){?>
<ul class="goods-list" style="width:760px;">
  <?php foreach($output['goods_list'] as $key=>$val){?>
  <li>
    <div class="goods-thumb"><img src="<?php echo thumb($val, 240);?>"/></div>
    <dl class="goods-content">
      <dt><?php echo $val['goods_name'];?></dt>
      <dd>销售价：<?php echo $lang['currency'].wtPriceFormat($val['goods_price']);?>
    </dl>
    <a wttype="btn_add_robbuy_goods" data-goods-commonid="<?php echo $val['goods_commonid'];?>" href="javascript:void(0);" class="wtbtn-mini wtbtn-mint"><i class="icon-ok-bbs "></i>选择为抢购商品</a> </li>
  <?php } ?>
</ul>
<div class="pagination"><?php echo $output['show_page']; ?></div>
<?php } else { ?>
<div><?php echo $lang['no_record'];?></div>
<?php } ?>
