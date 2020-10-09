<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/common.css" rel="stylesheet" type="text/css">
<table style="width:800px;font-size:15px;line-height:40px;margin-top:50px;" align="center">
<tr><td colspan="2"><img height="32" src="<?php echo C('member_logo') == ''?UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('site_logo'):UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('member_logo'); ?>"></td></tr>
<tr><td colspan="4" style="border-bottom:1px solid #ccc"><h3 style="font-size:20px;line-height:60px"><?php echo C('site_name');?> - 实物订单商家结算单</h3></td></tr>
<tr>
<td width="80px">商家</td><td colspan="3"><?php echo $output['bill_info']['ob_store_name'];?></td>
</tr>
<tr><td>结算单号</td><td width="130px"><?php echo $output['bill_info']['ob_id'];?></td><td width="100px">结算范围</td><td><?php echo date('Y-m-d',$output['bill_info']['ob_start_date']);?> &nbsp;至&nbsp; <?php echo date('Y-m-d',$output['bill_info']['ob_end_date']);?></td></tr>
<tr><td>出账时间</td><td><?php echo date('Y-m-d',$output['bill_info']['ob_create_date']);?></td>
<td>结算状态</td><td><?php echo billState($output['bill_info']['ob_state']);?></td></tr>
<?php if ($output['bill_info']['ob_state'] == BILL_STATE_SUCCESS){?>
<tr>
<td>
结算日期</td><td><?php echo date('Y-m-d',$output['bill_info']['ob_pay_date']);?>
</td>
</tr>
<?php }?>
<tr><td>商家应收</td><td colspan="3"><?php echo wtPriceFormat($output['bill_info']['ob_result_totals']);?></td></tr>
<tr><td>结算明细</td><td colspan="3"></td></tr>
<tr><td colspan="4"><?php echo wtPriceFormat($output['bill_info']['ob_order_totals']);?> (订单金额) - <?php echo wtPriceFormat($output['bill_info']['ob_commis_totals']);?> (佣金) - <?php echo wtPriceFormat($output['bill_info']['ob_order_return_totals']);?> (退单金额) + <?php echo wtPriceFormat($output['bill_info']['ob_commis_return_totals']);?> (退还佣金) - <?php echo wtPriceFormat($output['bill_info']['ob_store_cost_totals']);?> (店铺促销费用)
<?php if (floatval($output['bill_info']['ob_order_book_totals']) > 0) { ?>
+ <?php echo wtPriceFormat($output['bill_info']['ob_order_book_totals']);?> (未退定金)
<?php } ?>
<?php if (floatval($output['bill_info']['ob_rpt_amount']) > 0) { ?>
+ <?php echo wtPriceFormat($output['bill_info']['ob_rpt_amount']);?> (平台优惠券)
<?php } ?>
<?php if (floatval($output['bill_info']['ob_rf_rpt_amount']) > 0) { ?>
- <?php echo wtPriceFormat($output['bill_info']['ob_rf_rpt_amount']);?> (全部退款时应扣除的平台优惠券)
<?php } ?>
</td></tr>
</table>