<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<style>
.bill-alert-block {
    padding-bottom: 14px;
    padding-top: 14px;
}
.bill_alert {
    background-color: #F9FAFC;
    border: 1px solid #F1F1F1;
    margin-bottom: 20px;
    padding: 8px 35px 8px 14px;
    text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
	line-height:30px;
}
</style>
  <div class="bill_alert bill-alert-block mt10">
    <div style="width:800px"><h3 style="float:left">本期结算</h3><div style="float:right;">
    <?php if ($output['bill_info']['ob_state'] == BILL_STATE_CREATE){?>
    <a class="wtbtn wtbtn-grapefruit mt5" onclick="ajax_get_confirm('一旦确认将无法恢复，系统将自动进入结算环节,<BR/>确认系统出账单计算无误吗?', 'index.php?w=store_bill&t=confirm_bill&ob_id=<?php echo $_GET['ob_id'];?>');" href="javascript:void(0)">本期结算无误，我要确认</a>
    <?php } elseif ($output['bill_info']['ob_state'] == BILL_STATE_SUCCESS) {?>
    <a class="wtbtn mt5" target="_blank" href="index.php?w=store_bill&t=bill_print&ob_id=<?php echo $_GET['ob_id'];?>">打印结算单</a>
    <?php } ?>
    </div>
    <div style="clear:both"></div>
    </div>
    <ul>
      <li>结算单号：<?php echo $output['bill_info']['ob_id'];?>&emsp;
      <?php echo date('Y-m-d',$output['bill_info']['ob_start_date']);?> &nbsp;至&nbsp; <?php echo date('Y-m-d',$output['bill_info']['ob_end_date']);?></li>
      <li>出账时间：<?php echo date('Y-m-d',$output['bill_info']['ob_create_date']);?></li>
      <li>本期应收：<?php echo wtPriceFormat($output['bill_info']['ob_result_totals']);?> = <?php echo wtPriceFormat($output['bill_info']['ob_order_totals']);?> (订单金额) - <?php echo wtPriceFormat($output['bill_info']['ob_commis_totals']);?> (佣金金额) - <?php echo wtPriceFormat($output['bill_info']['ob_order_return_totals']);?> (退单金额) + <?php echo wtPriceFormat($output['bill_info']['ob_commis_return_totals']);?> (退还佣金) - <?php echo wtPriceFormat($output['bill_info']['ob_store_cost_totals']);?> (促销费用)
      <?php if (floatval($output['bill_info']['ob_order_book_totals']) > 0) { ?>
      + <?php echo wtPriceFormat($output['bill_info']['ob_order_book_totals']);?> (未退定金金额)
      <?php } ?>
      <?php if (floatval($output['bill_info']['ob_rpt_amount']) > 0) { ?>
      + <?php echo wtPriceFormat($output['bill_info']['ob_rpt_amount']);?> (平台优惠券)
      <?php } ?>
      <?php if (floatval($output['bill_info']['ob_rf_rpt_amount']) > 0) { ?>
      - <?php echo wtPriceFormat($output['bill_info']['ob_rf_rpt_amount']);?> (全部退款时应扣除的平台优惠券)
      <?php } ?>
      <?php if (floatval($output['bill_info']['ob_fx_pay_amount']) > 0) { ?>
      - <?php echo wtPriceFormat($output['bill_info']['ob_fx_pay_amount']);?> (分销佣金)
      <?php } ?>
      </li>
      <li>结算状态：<?php echo billState($output['bill_info']['ob_state']);?>
      <?php if ($output['bill_info']['ob_state'] == BILL_STATE_SUCCESS){?>
      	，结算日期：<?php echo date('Y-m-d',$output['bill_info']['ob_pay_date']);?>
      <?php }?>
      </li>
    </ul>
  </div>
  <div class="tabmenu">
  	<?php include template('layout/submenu');?>
  </div>
<?php include template('seller/'.$output['sub_tpl_name']);?>
<link type="text/css" rel="stylesheet" href="<?php echo STATIC_SITE_URL."/js/jquery-ui/themes/ui-lightness/jquery.ui.css";?>"/>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8" ></script> 
<script type="text/javascript">
$(document).ready(function(){
	$('#query_start_date').datepicker();
	$('#query_end_date').datepicker();
});
</script>