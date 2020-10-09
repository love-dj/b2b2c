<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wtsc-flow-box">
  <div class="wtsc-flow-container">
    <div class="title">
      <h3>物流详情</h3>
    </div>
    <div class="alert alert-block alert-info">
      <ul>
        <li><strong><?php echo $lang['member_show_receive_info'].$lang['wt_colon'];?></strong><?php echo $output['order_info']['extend_order_common']['reciver_name']?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $output['order_info']['extend_order_common']['reciver_info']['phone'];?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $output['order_info']['extend_order_common']['reciver_info']['address'];?></li>
        <li><strong><?php echo $lang['store_show_order_buyer_message'].$lang['wt_colon'];?></strong><?php echo $output['order_info']['extend_order_common']['order_message']; ?></li>
        <li><strong><?php echo $lang['member_show_deliver_info'].$lang['wt_colon'];?></strong><?php echo $output['daddress_info']['seller_name']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $output['daddress_info']['telphone'];?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $output['daddress_info']['area_info'];?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $output['daddress_info']['address'];?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $output['daddress_info']['company'];?></li>
      </ul>
    </div>
    <div class="tabmenu">
      <ul class="tab">
        <li class="active"><a href=""><?php echo $lang['member_show_express_ship_dstatus'];?></a></li>
      </ul>
    </div>
    <ul class="express-log" id="express_list">
      <?php if(!empty($output['order_info']['extend_order_common']['shipping_time'])) { ?>
      <li class="loading"><?php echo $lang['wt_common_loading'];?></li>
      <?php } ?>
    </ul>
  </div>
  <div class="wtsc-flow-item">
    <div class="title"><?php echo $lang['member_show_order_info'];?></div>
    <div class="item-goods">
      <?php if(is_array($output['order_info']['extend_order_goods']) && !empty($output['order_info']['extend_order_goods'])) { foreach($output['order_info']['extend_order_goods'] as $goods) { ?>
      <dl>
        <dt>
          <div class="wtsc-goods-thumb-mini"><a target="_blank" href="<?php echo urlShop('goods','index',array('goods_id'=>$goods['goods_id']));?>"><img src="<?php echo thumb($goods, 60); ?>" onMouseOver="toolTip('<img src=<?php echo thumb($goods,'240'); ?>>')" onMouseOut="toolTip()"/></a></div>
        </dt>
        <dd><a target="_blank" href="<?php echo urlShop('goods','index',array('goods_id'=>$goods['goods_id']));?>"><?php echo $goods['goods_name']; ?></a><span class="rmb-price"><i class="mr5"><?php echo wtPriceFormat($goods['goods_price']); ?></i>&nbsp;*&nbsp;<?php echo $goods['goods_num']; ?></span></dd>
        </dt>
      </dl>
      <?php } } ?>
    </div>
    <div class="item-order">
      <dl>
        <dt>运费：</dt>
        <dd><?php echo $output['order_info']['shipping_fee'];?></dd>
      </dl>
      <dl>
        <dt>订单总额：</dt>
        <dd><strong><?php echo $lang['currency'].$output['order_info']['order_amount'];?></strong></dd>
      </dl>
      <dl>
        <dt><?php echo $lang['member_change_order_no'].$lang['wt_colon'];?></dt>
        <dd><a href="javascript:void(0);"><?php echo $output['order_info']['order_sn']; ?></a><a href="javascript:void(0);" class="a">更多<i class="icon-angle-down"></i>
          <div class="more"> <span class="arrow"></span>
            <ul>
              <li>支付方式：<span><?php echo $output['order_info']['payment_name']; ?>
                <?php if($output['order_info']['payment_code'] != 'offline' && !in_array($output['order_info']['order_state'],array(ORDER_STATE_CANCEL,ORDER_STATE_NEW))) { ?>
                (<?php echo '付款单号'.$lang['wt_colon'];?><?php echo $output['order_info']['pay_sn']; ?>)
                <?php } ?>
                </span> </li>
              <li><?php echo $lang['member_order_time'].$lang['wt_colon'];?><span><?php echo date('Y-m-d H:i:s',$output['order_info']['add_time']);?></span></li>
              <?php if(intval($output['order_info']['payment_time'])) { ?>
              <li><?php echo $lang['member_show_order_pay_time'].$lang['wt_colon'];?><span><?php echo date("Y-m-d H:i:s",$output['order_info']['payment_time']); ?></span></li>
              <?php } ?>
              <?php if($output['order_info']['extend_order_common']['shipping_time']) { ?>
              <li><?php echo $lang['member_show_order_send_time'].$lang['wt_colon'];?><span><?php echo date("Y-m-d H:i:s",$output['order_info']['extend_order_common']['shipping_time']); ?></span></li>
              <?php } ?>
              <?php if(intval($output['order_info']['finnshed_time'])) { ?>
              <li><?php echo $lang['member_show_order_finish_time'].$lang['wt_colon'];?><span><?php echo date("Y-m-d H:i:s",$output['order_info']['finnshed_time']); ?></span></li>
              <?php } ?>
            </ul>
          </div>
          </a></dd>
      </dl>
      <dl>
        <dt><?php echo $lang['member_show_express_ship_code'].$lang['wt_colon'];?></dt>
        <dd><?php echo $output['order_info']['shipping_code']; ?><a href="<?php echo $output['e_url'];?>" class="a" target="_blank"><?php echo $output['e_name'];?></a></dd>
      </dl>
    </div>
  </div>
</div>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.poshytip.min.js"></script> 
<script>
$(function(){
	//Ajax提示
	$('.tip').poshytip({
		className: 'tip-yellowsimple',
		showTimeout: 1,
		alignTo: 'target',
		alignX: 'center',
		alignY: 'bottom',
		offsetX: 5,
		offsetY: 0,
		allowTipHover: false
	});
      var_send = '<li><?php echo date("Y-m-d H:i:s",$output['order_info']['extend_order_common']['shipping_time']); ?>&nbsp;&nbsp;<?php echo $lang['member_show_seller_has_send'];?></li>';
	$.getJSON('index.php?w=seller_center&t=get_express&e_code=<?php echo $output['e_code']?>&shipping_code=<?php echo $output['order_info']['shipping_code']?>&c=<?php echo random(7);?>',function(data){
		if(data){
			data = var_send+data;
			$('#express_list').html(data).next().css('display','');
		}else{
			$('#express_list').html(var_send);
		}
	});
});
</script>