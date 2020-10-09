<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/cart.css" rel="stylesheet" type="text/css">
<style type="text/css">
.wt-head-search,
.head-my-menu,
.wt-nav,
.head-app { display: none !important; }
</style>

<div class="wrapper pr">
  <ul class="wtc-flow wtc-point-flow">
    <li class=""><i class="step1"></i>
      <p><?php echo $lang['pointcart_ensure_order'];?></p>
      <sub></sub>
      <div class="hr"></div>
    </li>
    <li class=""><i class="step2"></i>
      <p><?php echo $lang['pointcart_ensure_info'];?></p>
      <sub></sub>
      <div class="hr"></div>
    </li>
    <li class="current"><i class="step4"></i>
      <p><?php echo $lang['pointcart_exchange_finish'];?></p>
      <sub></sub>
      <div class="hr"></div>
    </li>
  </ul>
  <div class="wtc-main">
    <div class="wtc-title">
      <h3><?php echo $lang['pointcart_exchange_finish'];?></h3>
      <h5>兑换订单已提交完成，祝您购物愉快</h5>
    </div>
    <div class="wtc-receipt-info mb30">
      <div class="wtc-finish-a"><i></i><?php echo $lang['pointcart_step2_order_created'];?> <span class="all-points"><?php echo $lang['pointcart_step2_order_allpoints'].$lang['wt_colon'];?><em><?php echo $output['order_info']['point_allpoint']; ?></em></span> </div>
      <div class="wtc-finish-b">可通过用户中心<a href="<?php echo MEMBER_SITE_URL;?>/index.php?w=member_pointorder&t=orderlist">积分兑换记录</a>查看兑换单状态。 </div>
      <div class="wtc-finish-c mb30"> <a class="wtbtn-mini wtbtn-mint mr15" href="<?php echo BASE_SITE_URL?>"><i class="icon-shopping-cart"></i>继续购物</a> <a class="wtbtn-mini wtbtn-aqua" href="<?php echo MEMBER_SITE_URL;?>/index.php?w=member_pointorder&t=order_info&order_id=<?php echo $output['order_info']['point_orderid'];?>"><i class="icon-file-text-alt"></i><?php echo $lang['pointcart_step2_view_order'];?></a></div>
    </div>
  </div>
</div>
