<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/seller.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.printarea.js" charset="utf-8"></script>
<title><?php echo $lang['member_printorder_print'];?>--<?php echo $output['store_info']['store_name'];?><?php echo $lang['member_printorder_title'];?></title>
<style>
.print-box .mini-size {
	width: 58mm;
	position: absolute;
	top: 5mm;
	left: 5mm;
	padding: 1px;
	height: auto;
    z-index: 2;
}
</style>
</head>
<body>
<?php if (!empty($output['order_info'])){?>
<div class="print-box">
  <div class="print-btn" id="printbtn" title="<?php echo $lang['member_printorder_print_tip'];?>"><i></i><a href="javascript:void(0);"><?php echo $lang['member_printorder_print'];?></a></div>
<div class="mini-size"></div>
<dl class="a5-tip">
    <dt>
		<h3>小票</h3>
      <em>宽度58mm</em></dt>
    <dd>合适于58mm热敏小票纸</dd>
  </dl>
  <div>
<div id="printarea" style="width:58mm;">
 <style media="print">
    @page {
      size: auto;
      margin: 0mm;
    }
</style>
	<?php foreach ($output['goods_list'] as $item_k =>$item_v){?>
	  <div id="print_container" style="width:58mm;">
		<table border=0 style="width:58mm;font-size:12px; color: #000; background: #Fff;border: dashed 1px #ccc;">
		<tr>
		<td><strong><?php echo $output['store_info']['store_name'];?> 订单</strong><td>
		<tr>
		<td>订单号：<?php echo $output['order_info']['order_sn'];?></td>
		</tr>
		<tr>
		<td>支付状态：<?php echo $output['order_info']['state_desc'];?></td>
		</tr>
		<tr>
		<td>下单时间：<?php echo @date('Y-m-d H:i:s',$output['order_info']['add_time']); ?></td>
		</tr>
		<tr>
		<td>收货人：<?php echo $output['order_info']['extend_order_common']['reciver_name']; ?> </td>
		</tr>

		<tr>
		<td>手机：<?php echo $output['order_info']['extend_order_common']['reciver_info']['phone'];?></td>
		</tr>

		<tr>
		<td>地址：<?php echo $output['order_info']['extend_order_common']['reciver_info']['address'];?></td>
		</tr>
		<!--<tr>
		<td>配送方式：<?php echo $output['order_info']['delivery_name'];?></td>
		</tr>-->
		<tr>
		<td>收款方式：<?php echo $output['order_info']['payment_name'];?></td>
		</tr>

		<tr>
		<td>订单备注：<?php echo $output['store_info']['extend_order_common']['order_message'];?></td>
		</tr>
		<tr><td>
		<table border='0' style='width:100%;font-size:12px;text-align:left;'>
		<tr>
		<td style="width:8mm;text-align:center">NO.</td>
		<td style="width:30mm;">名称</td>
		<td style="width:10mm; text-align:center">数量</td>
		<td style="width:15mm;">单价</td>
		</tr>
		<?php foreach ($item_v as $k=>$v){?>
		<tr>
		<td style="width:8mm;text-align:center"><?php echo $k;?></td>
		<td style="width:35mm;"><?php echo $v['goods_name'];?></td>
		<td style="width:10mm; text-align:center"><?php echo $v['goods_num'];?></td>
		<td style="width:15mm;"><?php echo $v['goods_all_price'];?></td>
		</tr>
		<?php } ?>
		</table>
		</td>
		</tr>
		<tr style="text-align:right">
		<td><strong>总计：<?php echo $output['goods_total_price'];?>元</strong></td>
		</tr>
		<?php } ?>
		</table>
		</div>
    </div>
    <?php }?>
  </div>
  
</div>
</body>
<script>

$(function(){
	//$("#printarea").printArea();
	//window.print();
	$("#printbtn").click(function(){
	   $("#printarea").printArea();
	});
	
});

//打印提示
$('#printbtn').poshytip({
	className: 'tip-yellowsimple',
	showTimeout: 1,
	alignTo: 'target',
	alignX: 'center',
	alignY: 'bottom',
	offsetY: 5,
	allowTipHover: false
});
</script>
</html>