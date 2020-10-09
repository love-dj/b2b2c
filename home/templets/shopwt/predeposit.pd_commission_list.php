<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu'); ?>    
    <?php  if ($output['billing_option'] > 0) { ?>
      <a class="wtbtn wtbtn-mint" href="index.php?w=member_security&t=auth&type=pd_cash" style="right: 107px;"><i class="icon-money"></i>申请提现</a> 
    <?php  }else{ ?>
      <a class="wtbtn wtbtn-mint" href="#" style="right: 107px;"><i class="icon-money"></i>转入积分</a> 
    <?php } ?>
  </div>
  <div class="alert">
    <span class="mr30">
      佣金:
      <strong class="mr5 red" style="font-size: 18px;">
        <?php echo $output['member_info']['trad_amount']; ?>
      </strong>
      <?php echo $lang['currency_zh'];?>
    </span>
    <span>
      待结算佣金：
      <strong class="mr5 blue" style="font-size: 18px;">
        <?php echo $output['no_pay_commission']; ?>
      </strong>
      <?php echo $lang['currency_zh'];?>
    </span>

  </div>
  <table class="wtm-default-table">
    <thead>
      <tr>
        <th class="w10"></th>
        <th class="w150 tl">订单标号</th>
        <th class="w150 tl">佣金(<?php echo $lang['currency_zh'];?>)</th>
        <th class="w150 tl">订单时间</th>
        <th class="w150 tl">结算时间</th>
      </tr>
    </thead>
    <tbody>
      <?php  if (count($output['list'])>0) { ?>
      <?php foreach($output['list'] as $v) { ?>
      <tr class="bd-line">
        <td></td>
        <td class="tl"><?php echo $v['order_sn'];?></td>
        <td class="tl"><?php echo $v['user_commission'];?></td>
        <td class="goods-time tl"><?php echo @date('Y-m-d H:i:s',$v['order_time']);?></td>
        <td class="goods-time tl"><?php if($v['status'] == 1){echo @date('Y-m-d H:i:s',$v['settle_time']);}else{ echo '待结算';}?></td>
      </tr>
      <?php } ?>
      <?php } else {?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php  if (count($output['list'])>0) { ?>
      <tr>
        <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?></div></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
