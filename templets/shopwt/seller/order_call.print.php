<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/seller.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
body { background: #FFF none;
}
</style>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.printarea.js" charset="utf-8"></script>
<title><?php echo $lang['member_printorder_print'];?>--<?php echo $output['store_info']['store_name'];?><?php echo $lang['member_printorder_title'];?></title>
</head>

<body>
<?php if (!empty($output['order_list'])){?>
<div class="print-box">
  <div class="print-btn" id="printbtn" title="<?php echo $lang['member_printorder_print_tip'];?>"><i></i><a href="javascript:void(0);"><?php echo $lang['member_printorder_print'];?></a></div>
  <div class="a5-size"></div>
  <dl class="a5-tip">
    <dt>
      <h1>A5</h1>
      <em>Size: 210mm x 148mm</em></dt>
    <dd><?php echo $lang['member_printorder_print_tip_A5'];?></dd>
  </dl>
  <div class="a4-size"></div>
  <dl class="a4-tip">
    <dt>
      <h1>A4</h1>
      <em>Size: 210mm x 297mm</em></dt>
    <dd><?php echo $lang['member_printorder_print_tip_A4'];?></dd>
  </dl>
 
  <div class="print-page">
    <div id="printarea">
	
      <?php foreach ($output['order_list'] as $item_k =>$item_v){?>
      <div class="orderprint">
	  
        <div class="top">
		  <div class="full-title"><?php echo $item_v['store_name'];?></div>
        </div>
		
        <table class="buyer-info">
          <tr>
            <td class="w200">收货人：<?php echo $item_v['extend_order_common']['reciver_name'];?></td>
            <td><?php echo '电话'.$lang['wt_colon']; ?><?php echo @$item_v['extend_order_common']['reciver_info']['phone'];?></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="3">地址<?php echo $lang['wt_colon']; ?><?php echo @$item_v['extend_order_common']['reciver_info']['address'];?></td>
          </tr>
          <tr>
            <td>订单号<?php echo $lang['wt_colon'];?><?php echo $item_v['order_sn'];?></td>
            <td>下单时间<?php echo $lang['wt_colon'];?><?php echo @date('Y-m-d',$item_v['add_time']);?></td>
			
            <td><?php if ($item_v['shippin_code']){?>
              <span><?php echo $lang['member_printorder_shippingcode'].$lang['wt_colon']; ?><?php echo $item_v['shipping_code'];?></span>
              <?php }?></td>
          </tr>
        </table>
		
        <table class="order-info">
          <thead>
            <tr>
              <th class="w40"><?php echo $lang['member_printorder_serialnumber'];?></th>
              <th class="tl"><?php echo $lang['member_printorder_goodsname'];?></th>
              <th class="w70 tl"><?php echo $lang['member_printorder_goodsprice'];?>(<?php echo $lang['currency_zh'];?>)</th>
              <th class="w50"><?php echo $lang['member_printorder_goodsnum'];?></th>
              <th class="w70 tl"><?php echo $lang['member_printorder_subtotal'];?>(<?php echo $lang['currency_zh'];?>)</th>
            </tr>
          </thead>
		  
          <tbody>
            <?php foreach ($item_v['goods_list'] as $k=>$v){?>
            <tr>
              <td><?php echo $k;?></td>
              <td class="tl"><?php echo $v['goods_name'];?></td>
              <td class="tl"><?php echo $lang['currency'].$v['goods_price'];?></td>
              <td><?php echo $v['goods_num'];?></td>
              <td class="tl"><?php echo $lang['currency'].$v['goods_all_price'];?></td>
            </tr>
            <?php }?>
            <tr>
              <th></th>
              <th colspan="2" class="tl"><?php echo $lang['member_printorder_amountto'];?></th>
              <th><?php echo $item_v['goods_all_num'];?></th>
              <th class="tl"><?php echo $lang['currency'].$item_v['goods_total_price'];?></th>
            </tr>
          </tbody>
		  
          <tfoot>
            <tr>
              <th colspan="10"><span><?php echo $lang['member_printorder_totle'].$lang['wt_colon'];?><?php echo $lang['currency'].$item_v['goods_total_price'];?></span><span><?php echo $lang['member_printorder_freight'].$lang['wt_colon'];?><?php echo $lang['currency'].$item_v['shipping_fee'];?></span><span><?php echo $lang['member_printorder_privilege'].$lang['wt_colon'];?><?php echo $lang['currency'].$item_v['sale_amount'];?></span><span><?php echo $lang['member_printorder_orderamount'].$lang['wt_colon'];?><?php echo $lang['currency'].$item_v['order_amount'];?></span><span><?php echo $lang['member_printorder_shop'].$lang['wt_colon'];?><?php echo $item_v['store_info']['store_name'];?></span>
                <?php if (!empty($item_v['store_info']['store_qq'])){?>
                <span>QQ：<?php echo $item_v['store_info']['store_qq'];?></span>
                <?php }elseif (!empty($item_v['store_info']['store_ww'])){?>
                <span><?php echo $lang['member_printorder_shopww'].$lang['wt_colon'];?><?php echo $item_v['store_info']['store_ww'];?></span>
                <?php }?></th>
            </tr>
          </tfoot>
        </table>
        <?php if (empty($item_v['store_info']['store_stamp'])){?>
        <div class="explain">
        	<?php echo $item_v['store_info']['store_printdesc'];?>
        </div>
        <?php }else {?>
        <div class="explain">
        	<?php echo $item_v['store_info']['store_printdesc'];?>
        </div>
        <div class="seal"><img src="<?php echo $item_v['store_info']['store_stamp'];?>" onload="javascript:DrawImage(this,120,120);"/></div>
        <?php }?>
		
        <!--<div class="tc page"><?php echo $lang['member_printorder_pagetext_1']; ?><?php echo $item_k;?><?php echo $lang['member_printorder_pagetext_2']; ?>/<?php echo $lang['member_printorder_pagetext_3']; ?><?php echo count($item_v['goods_list']);?><?php echo $lang['member_printorder_pagetext_2']; ?></div>-->
      </div>
      <?php }?>
	  
    </div>
    <?php }?>
  </div>
  
</div>
</body>
<script>
$(function(){
	$("#printarea").printArea();
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