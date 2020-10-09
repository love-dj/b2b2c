<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wtc-main">
  <div class="wtc-title">
    <h3>订单完成</h3>
    <h5>订单已支付完成，祝您购物愉快。</h5>
  </div>
  <div class="wtc-receipt-info mb30">
  <div class="wtc-finish-a"><i></i>订单支付成功！您已成功支付订单金额<em>￥<?php echo $_GET['order_amount'];?></em>，订单编号：<?php echo $_GET['order_sn'];?>。</div>
  <div class="wtc-finish-b"><a href="<?php echo BASE_SITE_URL?>/index.php?w=member_order_vr&t=show_order&order_id=<?php echo $_GET['order_id'];?>">查看订单详情</a></div>
  <div class="wtc-finish-c mb30"><a href="<?php echo BASE_SITE_URL?>" class="wtbtn-mini wtbtn-mint mr15"><i class="icon-shopping-cart"></i>继续购物</a><a href="<?php echo BASE_SITE_URL?>/index.php?w=member_order_vr" class="wtbtn-mini wtbtn-aqua"><i class="icon-file-text-alt"></i>查看我的订单</a></div>
  </div>
</div>