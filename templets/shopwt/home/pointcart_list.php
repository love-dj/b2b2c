<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/cart.css" rel="stylesheet" type="text/css">
<style type="text/css">
.wt-head-search,
.head-my-menu,
.wt-nav,
.head-app { display: none !important; }
</style>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/pgoods_cart.js" charset="utf-8"></script>

<div class="wrapper pr">
  <ul class="wtc-flow wtc-point-flow">
    <li class="current"><i class="step1"></i>
      <p><?php echo $lang['pointcart_ensure_order'];?></p>
      <sub></sub>
      <div class="hr"></div>
    </li>
    <li class=""><i class="step2"></i>
      <p><?php echo $lang['pointcart_ensure_info'];?></p>
      <sub></sub>
      <div class="hr"></div>
    </li>
    <li class=""><i class="step4"></i>
      <p><?php echo $lang['pointcart_exchange_finish'];?></p>
      <sub></sub>
      <div class="hr"></div>
    </li>
  </ul>
  <?php if (is_array($output['cart_array']) && count($output['cart_array'])>0){ ?>
  <div class="wtc-main">
    <div class="wtc-title">
      <h3><?php echo $lang['pointcart_cart_chooseprod_title']; ?></h3>
      <h5>查看兑换清单，增加减少礼品数量，进入下一步操作。</h5>
    </div>
    <table class="wtc-table-style">
      <thead>
        <tr>
          <th class="w20"></th>
          <th colspan="2"><?php echo $lang['pointcart_goodsname']; ?> </th>
          <th class="w120"><?php echo $lang['pointcart_exchangepoint']; ?> </th>
          <th class="w120"><?php echo $lang['pointcart_exchangenum']; ?></th>
          <th class="w120"><?php echo $lang['pointcart_pointoneall']; ?></th>
          <th class="w80"><?php echo $lang['pointcart_cart_handle'] ?></th>
        </tr>
      </thead>
      <?php foreach ($output['cart_array'] as $k=>$v){ ?>
      <tr id="pcart_item_<?php echo $v['pcart_id']; ?>">
        <td></td>
        <td class="w100"><a href="<?php echo urlShop('pointprod', 'pinfo', array('id' => $v['pgoods_id']));?>" target="_blank" class="wtc-goods-thumb"><img src="<?php echo $v['pgoods_image_small']; ?>" alt="<?php echo $v['pgoods_name']; ?>"/></a></td>
        <td class="tl"><dl class="wtc-goods-content">
            <dt><a href="<?php echo urlShop('pointprod', 'pinfo', array('id' => $v['pgoods_id']));?>" target="_blank"><?php echo $v['pgoods_name']; ?></a></dt>
          </dl></td>
        <td><em class="goods-price"><?php echo $v['pgoods_points']; ?></em></td>
        <td><a href="JavaScript:void(0);" onclick="pcart_decrease_quantity(<?php echo $v['pcart_id']; ?>);" title="<?php echo $lang['pointcart_cart_reduse'];?>" class="add-substract-key tip">-</a><input id="input_item_<?php echo $v['pcart_id']; ?>" value="<?php echo $v['pgoods_choosenum']; ?>" changed="<?php echo $v['pgoods_choosenum']; ?>" onkeyup="pcart_change_quantity('<?php echo $v['pcart_id']; ?>', this);" class="text w30" type="text" style="text-align:center;"/><a  href="JavaScript:void(0);" onclick="pcart_add_quantity(<?php echo $v['pcart_id']; ?>);" title="<?php echo $lang['pointcart_cart_increase'];?>" class="add-substract-key tip">+</a></td>
        <td><em id="item_<?php echo $v['pcart_id']; ?>_subtotal" class="goods-subtotal"><?php echo $v['pgoods_pointone']; ?></em></td>
        <td><a class="del" href="javascript:void(0)" onclick="drop_pcart_item(<?php echo $v['pcart_id']; ?>);"><?php echo $lang['pointcart_cart_del']; ?></a></td>
      </tr>
      <?php } ?>
      <tfoot>
        <tr>
          <td colspan="20"><div class="wtc-all-account"><?php echo $lang['pointcart_cart_allpoints']; ?><em id="pcart_amount"><?php echo $output['pgoods_pointall']; ?></em><?php echo $lang['points_unit']; ?></div><a href="index.php?w=pointcart&t=step1" class="wtc-next-submit ok"><?php echo $lang['pointcart_cart_submit']; ?></a></td>
        </tr>
      </tfoot>
    </table>
  </div>
  <?php } else { ?>
  <div class="wtc-null-shopping"><i class="ico-gift"></i>
    <h4><?php echo $lang['pointcart_cart_null'];?></h4>
    <p><a href="index.php?w=pointprod" class="wtbtn-mini mr10"><i class="icon-reply-all"></i><?php echo $lang['pointcart_cart_exchangenow'];?></a> <a href="<?php echo MEMBER_SITE_URL;?>/index.php?w=member_pointorder" class="wtbtn-mini"><i class="icon-file-text"></i><?php echo $lang['pointcart_cart_exchanged_list'];?></a></p>
  </div>
  <?php } ?>
</div>
