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
    <li class="current"><i class="step2"></i>
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
  <div class="wtc-main">
    <div class="wtc-title">
      <h3><?php echo $lang['pointcart_ensure_info'];?></h3>
      <h5>请仔细核对填写收货、发票等信息，以确保物流快递及时准确投递。</h5>
    </div>
    <form method="post" id="porder_form" name="porder_form" action="index.php?w=pointcart&t=step2">
      <div class="wtc-receipt-info">
        <div class="wtc-receipt-info-title">
          <h3><?php echo $lang['pointcart_step1_receiver_address'];?><a href="<?php echo urlMember('member_address', 'address');?>" target="_blank">[管理]</a></h3>
        </div>
        <!-- 已经存在的收获地址start -->
        <div class="wtc-candidate-items">
          <?php if (!empty($output['address_list'])){ ?>
          <?php foreach($output['address_list'] as $k=>$val){ ?>
          <ul class="receive_add address_item">
            <li style="margin-top:0px;">
              <label for="address_<?php echo $val['address_id']; ?>">
                <input id="address_<?php echo $val['address_id']; ?>" type="radio" name="address_options" value="<?php echo $val['address_id']; ?>" <?php if ($val['is_default'] == 1) echo 'checked'; ?>/>
                &nbsp;&nbsp;<?php echo $val['area_info']; ?>&nbsp;&nbsp;<?php echo $val['address']; ?>&nbsp;&nbsp; <?php echo $val['true_name']; ?><?php echo $lang['cart_step1_receiver_shou'];?>&nbsp;&nbsp;
                <?php if($val['mob_phone']) echo $val['mob_phone']; else echo $val['tel_phone']; ?>
              </label>
            </li>
          </ul>
          <?php } ?>
          <?php } else { ?>
          <div style="color:#E4393C; height:25px; padding-top:3px;">暂无收货人地址，请先点击上方 “ 【管理】 ” ，新增收货地址，再进行兑换吧！</div>
          <?php }?>
        </div>
        <!-- 已经存在的收获地址end --> 
      </div>
      
      <!-- 留言start -->
      <div class="wtc-receipt-info">
        <div class="wtc-receipt-info-title">
          <h3><?php echo $lang['pointcart_step1_chooseprod'];?></h3>
        </div>
        
        <!-- 已经选择礼品start -->
        
        <table class="wtc-table-style">
          <thead>
            <tr>
              <th class="w20"></th>
              <th class="tl" colspan="2"><?php echo $lang['pointcart_step1_goods_content'];?></th>
              <th class="w120"><?php echo $lang['pointcart_step1_goods_num'];?></th>
              <th class="w120"><?php echo $lang['pointcart_step1_goods_point'];?></th>
            </tr>
          </thead>
          <tbody>
            <?php
	  			if(is_array($output['pointprod_arr']['pointprod_list']) and count($output['pointprod_arr']['pointprod_list'])>0) {
				foreach($output['pointprod_arr']['pointprod_list'] as $val) {
			?>
            <tr class="shop-list ">
              <td></td>
              <td class="w100"><a href="<?php echo urlShop('pointprod', 'pinfo', array('id' => $val['pgoods_id']));?>" class="wtc-goods-thumb" target="_blank"><img src="<?php echo $val['pgoods_image_small']; ?>" alt="<?php echo $val['pgoods_name']; ?>"/></a></td>
              <td class="tl"><dl class="wtc-goods-content">
                  <dt><a target="_blank" href="<?php echo urlShop('pointprod', 'pinfo', array('id' => $val['pgoods_id']));?>"><?php echo $val['pgoods_name']; ?></a></dt>
                </dl></td>
              <td><?php echo $val['quantity']; ?></td>
              <td><em class="goods-subtotal"><?php echo $val['onepoints']; ?></em></td>
            </tr>
            <?php } }?>
            <tr>
              <td colspan="20" class="tl"><label><?php echo $lang['pointcart_step1_message'].$lang['wt_colon'];?>
                  <textarea class="wtc-msg-textarea" value="<?php echo $lang['pointcart_step1_message_showice'];?>" placeholder="选填：对本次交易的说明（建议填写已经和商家达成一致的说明）"onclick="pcart_messageclear(this);" title="选填：对本次交易的说明（建议填写已经和商家达成一致的说明）"  maxlength="150"></textarea>
                </label></td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="20"><a href="index.php?w=pointcart" class="wtc-prev-btn"><i class="icon-angle-left"></i><?php echo $lang['pointcart_step1_return_list'];?></a>
                <div class="wtc-all-account"><?php echo $lang['pointcart_cart_allpoints'];?><em><?php echo $output['pointprod_arr']['pgoods_pointall']; ?></em><?php echo $lang['points_unit']; ?></div>
                <a id="submitpointorder" href="javascript:void(0);" class="wtc-next-submit ok"><?php echo $lang['pointcart_step1_submit'];?></a></td>
            </tr>
          </tfoot>
        </table>
        <!-- 已经选择礼品end --> 
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
	function pcart_messageclear(tt){
		if (!tt.name)
		{
			tt.value = '';
			tt.name = 'pcart_message';
		}
	}

	$("#submitpointorder").click(function(){
		var chooseaddress = parseInt($("input[name='address_options']:checked").val());
		if(!chooseaddress || chooseaddress <= 0){
			showDialog('请选择收货人地址');
		} else {
			$('#porder_form').submit();
		}
	});
</script> 
