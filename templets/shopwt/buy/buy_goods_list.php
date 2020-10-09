<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wtc-receipt-info">
  <div class="wtc-receipt-info-title">
    <h3>商品清单</h3>
  </div>
  <table class="wtc-table-style">
    <thead>
      <tr>
        <th class="w50"></th>
        <th></th>
        <th><?php echo $lang['cart_index_store_goods'];?></th>
        <th class="w150"><?php echo $lang['cart_index_price'].'('.$lang['currency_zh'].')';?></th>
        <th class="w100"><?php echo $lang['cart_index_amount'];?></th>
        <th class="w150"><?php echo $lang['cart_index_sum'].'('.$lang['currency_zh'].')';?></th>
      </tr>
    </thead>
    <tbody id="jjg-valid-skus-tpl" style="display:none;">
      <tr class="bundling-list">
        <td class="tree td-border-left"><input name="jjg[]" type="hidden" value="%jjgId%|%jjgLevel%|%id%" /></td>
        <td><a class="wtc-goods-thumb" href="%url%" target="_blank"> <img alt="%name%" data-src="%imgUrl%" /> </a></td>
        <td class="tl"><dl class="wtc-goods-content">
            <dt> <a href="%url%" target="_blank">%name%</a> </dt>
            <dd class="wtc-goods-gift"><span>已选换购</span></dd>
          </dl></td>
        <td><em class="goods-price">%jjgPrice%</em></td>
        <td>1</td>
        <td class="td-border-right"><em wt_type="eachGoodsTotal" class="goods-subtotal"> %jjgPrice% </em></td>
      </tr>
    </tbody>
    <?php foreach($output['store_cart_list'] as $store_id => $cart_list) {?>
    <tbody>
      <tr>
        <th colspan="20"> <!-- S 店铺名称 -->
          
          <div class="wtc-store-name">店铺：<a href="<?php echo urlShop('show_store','index',array('store_id'=>$store_id));?>"><?php echo $cart_list[0]['store_name']; ?></a> <span member_id="<?php echo $output['store_list'][$store_id]['member_id'];?>"></span></div>
          
          <!-- E 店铺名称 --> 
          <!-- S 店铺满即送 -->
          
          <?php if (!empty($output['store_mansong_rule_list'][$store_id])) {?>
          <div class="wtc-store-sale ms"> <span>满即送</span><?php echo $output['store_mansong_rule_list'][$store_id]['desc'];?> </div>
          <?php } ?>
          
          <!-- E 店铺满即送 --> 
          <!-- S 店铺满金额包邮 -->
          
          <?php if (!empty($output['cancel_calc_sid_list'][$store_id])) {?>
          <div class="wtc-store-sale"> <span>免运费</span><?php echo $output['cancel_calc_sid_list'][$store_id]['desc'];?></div>
          <?php } ?>
          
          <!-- S 店铺满金额包邮 --> </th>
      </tr>
      <?php foreach($cart_list as $cart_info) {?>
      <tr id="cart_item_<?php echo $cart_info['cart_id'];?>" class="shop-list <?php echo ($cart_info['state'] && $cart_info['storage_state']) ? '' : 'item_disabled';?>"
<?php if ($cart_info['jjgRank'] > 0) { ?>
        data-jjg="<?php echo $cart_info['jjgRank']; ?>"
<?php } ?>
>
        <td class="td-border-left 
		<?php if ($cart_info['bl_id'] != '0') {?>
        td-bl
		<?php }?>
		<?php if ($cart_info['jjgRank'] > 0) { ?>
        td-bl
		<?php }?>"><?php if ($cart_info['state'] && $cart_info['storage_state']) {?>
          <input type="hidden" value="<?php echo $cart_info['cart_id'].'|'.$cart_info['goods_num'];?>" store_id="<?php echo $store_id?>" name="cart_id[]">
          <input type="hidden" value="<?php echo $cart_info['goods_id'].'|'.$cart_info['goods_num'];?>" store_id="<?php echo $store_id?>" name="goods_id[]">
          <?php } ?></td>
        <?php if ($cart_info['bl_id'] == '0') {?>
        <td class="w100"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$cart_info['goods_id']));?>" target="_blank" class="wtc-goods-thumb"><img src="<?php echo thumb($cart_info);?>" alt="<?php echo $cart_info['goods_name']; ?>" /></a></td>
        <?php } ?>
        <td class="tl" <?php if ($cart_info['bl_id'] != '0') {?>colspan="2"<?php }?>><dl class="wtc-goods-content">
            <dt>
              <?php if ($cart_info['bl_id'] != '0'){?>
              【套装】
              <?php }?>
              <a href="<?php echo urlShop('goods','index',array('goods_id'=>$cart_info['goods_id']));?>" target="_blank"><?php echo $cart_info['goods_name']; ?></a></dt>
            <?php if (!$cart_info['bl_id']) { ?>
            <dd class="goods-spec"><?php echo $cart_info['goods_spec'];?></dd>
            <?php } ?>
            <?php if ($cart_info['bl_id'] != '0') {?>
            <dd> <span class="buldling">优惠套装，单套直降<em>￥<?php echo $cart_info['down_price']; ?></em></span></dd>
            <?php }?>
            
            <!-- S 消费者保障服务 -->
            <?php if($cart_info["contractlist"]){?>
            <dd class="goods-slogen">
              <?php foreach($cart_info["contractlist"] as $gcitem_k=>$gcitem_v){?>
              <span <?php if($gcitem_v['cti_descurl']){ ?>onclick="window.open('<?php echo $gcitem_v['cti_descurl'];?>');" style="cursor: pointer;"<?php }?> title="<?php echo $gcitem_v['cti_name']; ?>"> <img src="<?php echo $gcitem_v['cti_icon_url_60'];?>"/> </span>
              <?php }?>
            </dd>
            <?php }?>
            <!-- E 消费者保障服务 --> <!-- S 商品赠品列表 -->
            <?php if (!empty($cart_info['gift_list'])) { ?>
            <dd class="wtc-goods-gift"><span>赠品</span>
              <ul class="wtc-goods-gift-list">
                <?php foreach ($cart_info['gift_list'] as $goods_content) { ?>
                <li wt_group="<?php echo $cart_info['cart_id'];?>"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$goods_content['gift_goodsid']));?>" target="_blank" class="thumb" title="赠品：<?php echo $goods_content['gift_goodsname']; ?> * <?php echo $goods_content['gift_amount'] * $cart_info['goods_num']; ?>"><img src="<?php echo cthumb($goods_content['gift_goodsimage'],60,$store_id);?>" alt="<?php echo $goods_content['gift_goodsname']; ?>"/></a> </li>
                <?php } ?>
              </ul>
            </dd>
            <?php  } ?>
            <!-- E 商品赠品列表 -->
          </dl></td>
        <td><!-- S 商品单价 -->
          
          <?php if (!empty($cart_info['xianshi_info'])) {?>
          <em class="goods-old-price tip" title="商品原价格"><?php echo $cart_info['goods_yprice']; ?></em>
          <?php } ?>
          <em class="goods-price"><?php echo $cart_info['goods_price']; ?></em><!-- E 商品单价 --> 
          <!-- S 商品促销-限时折扣 -->
          
          <?php if (!empty($cart_info['xianshi_info'])) {?>
          <dl class="wtc-goods-sale">
            <dt>商家促销<i class="icon-angle-down"></i></dt>
            <dd>
              <p>活动名称：限时折扣</p>
              <p>满<strong><?php echo $cart_info['xianshi_info']['lower_limit'];?></strong>件，单价直降<em>￥<?php echo $cart_info['xianshi_info']['down_price']; ?></em></p>
            </dd>
          </dl>
          <?php }?>
          
          <!-- E 商品促销-限时折扣 --> 
          <!-- S 商品促销-抢购 -->
          
          <?php if ($cart_info['ifrobbuy']) {?>
          <dl class="wtc-goods-sale">
            <dt>商家促销<i class="icon-angle-down"></i></dt>
            <dd>
              <p>活动名称：抢购</p>
              <?php if ($cart_info['upper_limit']) {?>
              <p>最多限购：<strong><?php echo $cart_info['upper_limit']; ?></strong>件 </p>
              <?php } ?>
            </dd>
          </dl>
          <?php }?>
          
          <!-- E 商品促销-抢购 --> 
          <!-- S 促销活动-加价购 -->
          
          <?php if ($cart_info['jjgRank'] > 0) { ?>
          <dl class="wtc-goods-sale">
            <dt>商家促销<i class="icon-angle-down"></i></dt>
            <dd>
              <p>活动名称：加价购</p>
              <p>活动满金额，加价购买更多优惠商品。</p>
            </dd>
          </dl>
          <?php } ?>
          
          <!-- E 促销活动-抢购 --></td>
        <td><?php echo $cart_info['state'] ? $cart_info['goods_num'] : ''; ?></td>
        <td class="td-border-right"><?php if ($cart_info['state'] && $cart_info['storage_state']) {?>
          <em cart_id="<?php echo $cart_info['cart_id']; ?>" goods_id="<?php echo $cart_info['goods_id'];?>" wt_type="eachGoodsTotal<?php echo $store_id?>" tpl_id="<?php echo $cart_info['transport_id']?>" class="goods-subtotal"><?php echo $cart_info['goods_total']; ?></em> <span id="no_send_tpl_<?php echo $cart_info['transport_id']?>" style="color: #F00;display:none">无货</span>
          <?php } elseif (!$cart_info['storage_state']) {?>
          <span style="color: #F00;">库存不足</span>
          <?php } elseif (!$cart_info['state']) {?>
          <span style="color: #F00;">无效</span>
          <?php }?></td>
      </tr>
      
      <!-- S bundling goods list -->
      <?php if (is_array($cart_info['bl_goods_list'])) {?>
      <?php foreach ($cart_info['bl_goods_list'] as $goods_content) { ?>
      <tr class="shop-list <?php echo $cart_info['state'] && $cart_info['storage_state'] ? '' : 'item_disabled';?>  bundling-list">
        <td class="tree td-border-left"></td>
        <td><a href="<?php echo urlShop('goods','index',array('goods_id'=>$goods_content['goods_id']));?>" target="_blank" class="wtc-goods-thumb"><img src="<?php echo cthumb($goods_content['goods_image'],$store_id);?>" alt="<?php echo $goods_content['goods_name']; ?>" /></a></td>
        <td class="tl"><dl class="wtc-goods-content">
            <dt><a href="<?php echo urlShop('goods','index',array('goods_id'=>$goods_content['goods_id']));?>" target="_blank"><?php echo $goods_content['goods_name']; ?></a> </dt>
            <?php if ($goods_content['goods_spec']) { ?>
            <dd class="goods-spec"><?php echo $goods_content['goods_spec'];?></dd>
            <?php } ?>
            <!-- S 消费者保障服务 -->
            <?php if($goods_content["contractlist"]){?>
            <dd class="goods-slogen">
              <?php foreach($goods_content["contractlist"] as $gcitem_k=>$gcitem_v){?>
              <span <?php if($gcitem_v['cti_descurl']){ ?>onclick="window.open('<?php echo $gcitem_v['cti_descurl'];?>');" style="cursor: pointer;"<?php }?> title="<?php echo $gcitem_v['cti_name']; ?>"> <img src="<?php echo $gcitem_v['cti_icon_url_60'];?>"/> </span>
              <?php }?>
            </dd>
            <?php }?>
            <!-- E 消费者保障服务 -->
          </dl></td>
        <td><em class="goods-price"><?php echo $goods_content['bl_goods_price'];?></em></td>
        <td><?php echo $cart_info['goods_num'];?></td>
        <td class="td-border-right"><em goods_id="<?php echo $goods_content['goods_id'];?>" cart_id="<?php echo $cart_info['cart_id'];?>" wt_type="eachGoodsTotal<?php echo $store_id?>" class="goods-subtotal"><?php echo wtPriceFormat($goods_content['bl_goods_price']*$cart_info['goods_num']);?></em> <span style="color: #F00;display:none">无货</span></td>
      </tr>
      <?php } ?>
      <?php  } ?>
      <!-- E bundling goods list -->
      
      <?php } ?>
      <tr>
        <td colspan="20"><div class="wtc-msg">买家留言：
            <textarea  name="pay_message[<?php echo $store_id;?>]" class="wtc-msg-textarea" placeholder="选填：对本次交易的说明（建议填写已经和商家达成一致的说明）" title="选填：对本次交易的说明（建议填写已经和商家达成一致的说明）"  maxlength="150"></textarea>
          </div>
          <div class="wtc-store-account">
            <dl>
              <dt>商品金额：</dt>
              <dd class="rule"></dd>
              <dd class="sum"><em id="eachStoreGoodsTotal_<?php echo $store_id;?>"><?php echo $output['store_goods_total'][$store_id];?></em></dd>
            </dl>
            <?php if ($output['store_mansong_rule_list'][$store_id]['discount'] > 0) {?>
            <dl>
              <dt>店铺优惠：</dt>
              <dd class="rule"><?php echo $output['store_mansong_rule_list'][$store_id]['desc'];?></dd>
              <dd class="sum"><em id="eachStoreManSong_<?php echo $store_id;?>" class="subtract">-<?php echo $output['store_mansong_rule_list'][$store_id]['discount'];?></em></dd>
            </dl>
            <?php } ?>
            
            <!-- S voucher list -->
            
            <?php if (!empty($output['store_voucher_list'][$store_id]) && is_array($output['store_voucher_list'][$store_id])) {?>
            <dl>
              <dt>优惠卡券：</dt>
              <dd class="rule">
                <select wttype="voucher" name="voucher[<?php echo $store_id;?>]" class="select">
                  <option value="<?php echo $voucher['voucher_t_id'];?>|<?php echo $store_id;?>|0.00">-选择使用店铺代金券-</option>
                  <?php foreach ($output['store_voucher_list'][$store_id] as $voucher) {?>
                  <option value="<?php echo $voucher['voucher_t_id'];?>|<?php echo $store_id;?>|<?php echo $voucher['voucher_price'];?>"><?php echo $voucher['desc'];?></option>
                  <?php } ?>
                </select>
              </dd>
              <dd class="sum"><em id="eachStoreVoucher_<?php echo $store_id;?>" class="subtract">-0.00</em></dd>
            </dl>
            <!-- E voucher list -->
            <?php } ?>
            <dl>
              <dt>物流运费：</dt>
              <dd class="rule">
                <?php if (!empty($output['cancel_calc_sid_list'][$store_id])) {?>
                <?php echo $output['cancel_calc_sid_list'][$store_id]['desc'];?>
                <?php } ?>
              </dd>
              <dd class="sum"><em wt_type="eachStoreFreight" id="eachStoreFreight_<?php echo $store_id;?>">0.00</em></dd>
            </dl>
            <dl class="total">
              <dt>本店合计：</dt>
              <dd class="rule"></dd>
              <dd class="sum"><em store_id="<?php echo $store_id;?>" wt_type="eachStoreTotal"></em><?php echo $lang['currency_zh'];?></dd>
            </dl>
          </div></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <!-- S rpt list -->
      <tr id="rpt_panel" style="display: none">
        <td class="pd-account" colspan="20"><div class="wtc-store-account"><dl><dt>平台优惠券：</dt><dd class="rule">
            <select wttype="rpt" id="rpt" name="rpt" class="select">
            </select>
            <dd class="sum"><em id="orderRpt" class="subtract">-0.00</em></dd></dl></div></td>
      </tr>
      <!-- E rpt list -->
	<!-- S 积分抵用 -->
      <?php if (C('points_money_isuse')==1) { ?>
      <tr>
          <td colspan="20">
              <div class="buy-point-select">
                  <div class="mt5 mb5">
                  <label>
                      <input type="checkbox" id="isPoint">
                      账户共<em><?php echo $output['member_points'];?></em>积分，最多可抵扣<em id="ismaxallow" style="color:red;"></em>积分<br>
                  </label>
                  </div>
                  <div class="buy-point-input" style="display: none;">
                  请输入积分：<input type="text" id="J_PointInput" name="J_PointInput" autocomplete="off">
                      <span class="discharge">- <span class="tc-rmb">￥</span><strong id="J_Discharge">0.00</strong></span>
                  </div>
              </div>
          </td>
      </tr>
      <?php } ?>
      <!-- E 积分抵用 -->
      <tr>
        <td colspan="20"><?php if(!empty($output['ifcart'])){?>
          <a href="index.php?w=cart" class="wtc-prev-btn"><i class="icon-angle-left"></i><?php echo $lang['cart_step1_back_to_cart'];?></a>
          <?php }?>
          <div class="wtc-all-account">订单总金额：<em id="orderTotal">....</font></em><?php echo $lang['currency_zh'];?></div>
          <a href="javascript:void(0)" id='submitOrder' class="wtc-next-submit"><?php echo $lang['cart_index_submit_order'];?></a></td>
      </tr>
    </tfoot>
  </table>
</div>
<script>
function submitNext(){
	if (!SUBMIT_FORM) return;

	if ($('input[name="cart_id[]"]').size() == 0) {
		showDialog('所购商品无效', 'error','','','','','','','','',2);
		return;
	}
  if ($('#address_id').val() == ''){
		showDialog('<?php echo $lang['cart_step1_please_set_address'];?>', 'error','','','','','','','','',2);
		return;
	}
	if ($('#buy_city_id').val() == '') {
		showDialog('正在计算运费,请稍后！', 'error','','','','','','','','',2);
		return;
	}
	if ($('input[name="fcode"]').size() == 1 && $('#fcode_callback').val() != '1') {
		showDialog('请输入并使用F码！', 'error','','','','','','','','',2);
		return;
	}
	if (no_send_tpl_ids.length > 0 || no_chain_goods_ids.length > 0) {
		showDialog('有部分商品配送范围无法覆盖您选择的地址，请更换其它商品！', 'error','','','','','','','','',4);
		return;
	}
	SUBMIT_FORM = false;
 	$('#order_form').submit();
}

//计算总运费和每个店铺小计
function calcOrder() {
    allTotal = 0;
    $('em[wt_type="eachStoreTotal"]').each(function(){
        store_id = $(this).attr('store_id');
        var eachTotal = 0;
        $('em[wt_type="eachGoodsTotal'+store_id+'"]').each(function(){
        	if (no_send_tpl_ids[$(this).attr('tpl_id')]) {
     		    $(this).next().show();
     		    $('#cart_item_'+$(this).attr('cart_id')).addClass('item_disabled');
     		} else {
         		if (no_chain_goods_ids[$(this).attr('goods_id')]){
         		    $(this).next().show();
         		    $('#cart_item_'+$(this).attr('cart_id')).addClass('item_disabled');
             	} else {
         		    $(this).next().hide();
           		    $('#cart_item_'+$(this).attr('cart_id')).removeClass('item_disabled');
                }
     		}
        });
        if ($('#eachStoreGoodsTotal_'+store_id).length > 0) {
        	eachTotal += parseFloat($('#eachStoreGoodsTotal_'+store_id).html());
	    }
        if ($('#eachStoreManSong_'+store_id).length > 0) {
        	eachTotal += parseFloat($('#eachStoreManSong_'+store_id).html());
	    }
        if ($('#eachStoreVoucher_'+store_id).length > 0) {
        	eachTotal += parseFloat($('#eachStoreVoucher_'+store_id).html());
        }
        if ($('#eachStoreFreight_'+store_id).length > 0) {
        	eachTotal += parseFloat($('#eachStoreFreight_'+store_id).html());
	    }
        allTotal += eachTotal;
        $(this).html(eachTotal.toFixed(2));
    });
	

	var pm_parity = <?php echo C('points_money_parity');?>;
	var pm_rate = <?php echo C('member_points_payrate');?>;
	
	var pm_allow= parseInt((pm_rate*allTotal*0.01)/pm_parity);
	var m_points = <?php echo $output['member_points'] ;?>;
	if(pm_allow>m_points){
		pm_allow = m_points;
	}
	$('#ismaxallow').html(pm_allow);
	
    if ($('#J_Discharge').length > 0) {
        if(parseInt($('#J_PointInput').val())>pm_allow){
			showDialog('超过当前最多可抵用积分！', 'error','','','','','','','','',4);
			$('#J_PointInput').val(pm_allow);
			var pnumber = pm_allow*pm_parity;
			$('#J_Discharge').text(pnumber.toFixed(2));
		}
		allTotal -= parseFloat($('#J_Discharge').text());
    }
    if ($('#orderRpt').length > 0) {
    	iniRpt(allTotal.toFixed(2));
    	$('#orderRpt').html('-0.00');
    }
    $('#orderTotal').html(allTotal.toFixed(2));
    $('#submitOrder').on('click',function(){submitNext()}).addClass('ok');
}
$(function() {
    var tpl = $('#jjg-valid-skus-tpl').html();
    var jjgValidSkus = <?php echo json_encode($output['jjgValidSkus']); ?>;

    $footers = {};
    $('[data-jjg]').each(function() {
        var id = $(this).attr('data-jjg');
        if (!$footers[id]) {
            var $footer = $('<tr><td colspan="20"></td></tr>');
            $footers[id] = $footer;
            $("tr[data-jjg='"+id+"']:last").after($footer);
        }
    });

    $.each(jjgValidSkus || {}, function(k, v) {
        $.each(v || {}, function(kk, vv) {
            var s = tpl.replace(/%(\w+)%/g, function($m, $1) {
                return vv[$1];
            });
            var $s = $(s);
            $s.find('img[data-src]').each(function() {
                this.src = $(this).attr('data-src');
            });
            $footers[k].before($s);
        });
    });
});

</script> 
