<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu'); ?>
    <a class="wtbtn wtbtn-mint" href="index.php?w=member_security&t=auth&type=pd_cash"><i class="icon-money"></i>申请提现</a> 
	</div>
  <div class="alert"><span class="mr30">可用金额<?php echo $lang['wt_colon']; ?><strong class="mr5 red" style="font-size: 18px;"><?php echo $output['member_info']['available_predeposit']; ?></strong><?php echo $lang['currency_zh'];?></span><span>冻结金额<?php echo $lang['wt_colon']; ?><strong class="mr5 blue" style="font-size: 18px;"><?php echo $output['member_info']['freeze_predeposit']; ?></strong><?php echo $lang['currency_zh'];?></span></div>
  <table class="wtm-default-table">
    <thead>
      <tr>
        <th class="w10"></th>
        <th class="w150 tl"><?php echo $lang['predeposit_addtime']; ?></th>
        <th class="w150 tl">收入(<?php echo $lang['currency_zh'];?>)</th>
        <th class="w150 tl">支出(<?php echo $lang['currency_zh'];?>)</th>
        <th class="w150 tl">冻结(<?php echo $lang['currency_zh'];?>)</th>
        <th class="tl"><?php echo $lang['predeposit_log_desc'];?></th>
      </tr>
    </thead>
    <tbody>
      <?php  if (count($output['list'])>0) { ?>
      <?php foreach($output['list'] as $v) { ?>
      <tr class="bd-line">
        <td></td>
        <td class="goods-time tl"><?php echo @date('Y-m-d H:i:s',$v['lg_add_time']);?></td>
<?php $availableFloat = (float) $v['lg_av_amount']; if ($availableFloat > 0) { ?>
        <td class="tl red">+<?php echo $v['lg_av_amount']; ?></td>
        <td class="tl green"></td>
<?php } elseif ($availableFloat < 0) { ?>
        <td class="tl red"></td>
        <td class="tl green"><?php echo $v['lg_av_amount']; ?></td>
<?php } else { ?>
        <td class="tl red"></td>
        <td class="tl green"></td>
<?php } ?>
        <td class="tl blue"><?php echo floatval($v['lg_freeze_amount']) ? (floatval($v['lg_freeze_amount']) > 0 ? '+' : null ).$v['lg_freeze_amount'] : null;?></td>
        <td class="tl"><?php echo $v['lg_desc'];?></td>
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
