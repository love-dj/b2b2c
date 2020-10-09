<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <table class="wtm-default-table order">
    <thead>
      <tr>
        <th class="w10"></th>
        <th colspan="2"><?php echo $lang['member_pointorder_info_prodinfo'];?></th>
        <th class="w100"><?php echo $lang['member_pointorder_exchangepoints'];?></th>
        <th class="w40"><?php echo $lang['member_pointorder_exnum'];?></th>
        <th class="w150"><?php echo $lang['member_pointorder_exchangepoints_shippingfee'];?></th>
        <th class="w100">交易状态</th>
        <th class="w100"><?php echo $lang['member_pointorder_orderstate_handle'];?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(count($output['order_list'])>0){ ?>
      <?php foreach ($output['order_list'] as $val) { ?>
      <tr>
        <td colspan="19" class="sep-row"></td>
      </tr>
      <tr>
        <th colspan="20"><span class="ml10"><?php echo $lang['member_pointorder_ordersn'].$lang['wt_colon'];?><?php echo $val['point_ordersn']; ?></span><span><?php echo $lang['member_pointorder_addtime'].$lang['wt_colon'];?><?php echo @date("Y-m-d H:i:s",$val['point_addtime']); ?></span></th>
      </tr>
      <?php foreach((array)$val['prodlist'] as $k=>$v) {?>
      <tr>
        <td class="bdl"></td>
        <td class="w50"><div class="pic-thumb"><a href="<?php echo urlShop('pointprod', 'pinfo', array('id' => $v['point_goodsid']));?>" target="_blank"><img src="<?php echo $v['point_goodsimage_small']; ?>" onMouseOver="toolTip('<img src=<?php echo $v['point_goodsimage']; ?>>')" onMouseOut="toolTip()" /></a></div></td>
        <td class="tl"><dl class="goods-name">
            <dt><a href="<?php echo urlShop('pointprod', 'pinfo', array('id' => $v['point_goodsid']));?>" target="_blank"><?php echo $v['point_goodsname']; ?></a></dt>
          </dl></td>
        <td><?php echo $v['point_goodspoints']; ?></td>
        <td><?php echo $v['point_goodsnum']; ?></td>
        <?php if ((count($val['prodlist']) > 1 && $k ==0) || (count($val['prodlist']) == 1)){?>
        <td class="bdl" rowspan="<?php echo count($val['prodlist']);?>"><p class="price"><strong><?php echo $val['point_allpoint']; ?></strong></p></td>
        <td rowspan="<?php echo count($val['prodlist']);?>" class="bdl"><p><?php echo $val['point_orderstatetext']; ?></p>
          <p><a href="index.php?w=member_pointorder&t=order_info&order_id=<?php echo $val['point_orderid']; ?>" target="_blank"><?php echo $lang['member_pointorder_viewinfo'];?></a></p></td>
        <td class="bdl bdr w100" rowspan="<?php echo count($val['prodlist']);?>"><p></p>
          <?php if ($val['point_orderallowreceiving']) { ?>
          <p><a href="javascript:void(0)" class="wtbtn" onclick="ajax_confirm('<?php echo $lang['member_pointorder_confirmreceivingtip']; ?>','<?php echo MEMBER_SITE_URL;?>/index.php?w=member_pointorder&t=receiving_order&order_id=<?php echo $val['point_orderid']; ?>');"><?php echo $lang['member_pointorder_confirmreceiving']; ?></a></p>
          <?php } ?>
          <?php if ($val['point_orderallowcancel']) { ?>
          <p><a href="javascript:void(0)" class="wtbtn wtbtn-bittersweet" onclick="ajax_confirm('<?php echo $lang['member_pointorder_cancel_confirmtip']; ?>','<?php echo MEMBER_SITE_URL;?>/index.php?w=member_pointorder&t=cancel_order&order_id=<?php echo $val['point_orderid']; ?>');"><?php echo $lang['member_pointorder_cancel_title']; ?></a></p>
          <?php } ?></td>
        <?php } ?>
      </tr>
      <?php } ?>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php if(count($output['order_list'])>0){ ?>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['page']; ?></div></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
