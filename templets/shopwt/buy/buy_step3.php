<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wtc-main">
  <div class="wtc-title">
    <h3><?php echo $lang['cart_index_buy_finish'];?></h3>
    <h5>订单已支付完成，祝您购物愉快。</h5>
  </div>
  <div class="wtc-receipt-info mb30">
  <div class="wtc-finish-a"><i></i>订单支付成功！您已成功支付订单金额<em>￥<?php echo $_GET['pay_amount'];?></em>。</div>
  <div class="wtc-finish-b">可通过用户中心<a href="<?php echo BASE_SITE_URL?>/index.php?w=member_order">已买到的商品</a>查看订单状态。</div>
  <div class="wtc-finish-c mb30"><a href="<?php echo BASE_SITE_URL?>" class="wtbtn-mini wtbtn-mint mr15"><i class="icon-shopping-cart"></i>继续购物</a><a href="<?php echo BASE_SITE_URL?>/index.php?w=member_order" class="wtbtn-mini wtbtn-aqua"><i class="icon-file-text-alt"></i>查看订单</a></div>
  </div>
</div>