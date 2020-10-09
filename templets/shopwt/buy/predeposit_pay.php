<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wtc-main">
  <div class="wtc-title">
    <h3><?php echo $lang['cart_index_payment'];?></h3>
    <h5>查看充值记录可以通过<a href="<?php echo urlMember('predeposit');?>" target="_blank">我的充值列表 </a>进行查看。</h5>
  </div>
  <form action="index.php?w=payment&t=pd_order" method="POST" id="buy_form">
    <input type="hidden" name="pdr_sn" value="<?php echo $output['pdr_info']['pdr_sn'];?>">
    <input type="hidden" id="payment_code" name="payment_code" value="">
    <div class="wtc-receipt-info">
    <div>充值单号 : <?php echo $output['pdr_info']['pdr_sn'];?></div>
      <div class="wtc-receipt-info-title">
        <h3>您已申请账户余额充值，请立即在线支付！
          充值金额：<strong>￥<?php echo $output['pdr_info']['pdr_amount'];?></strong> </h3>
      </div>
    </div>
    <div class="wtc-receipt-info">
      <?php if (!isset($output['payment_list'])) {?>
      <?php }else if (!empty($output['payment_list'])){ ?>
      <div class="wtc-receipt-info-title">
        <h3>支付选择</h3>
      </div>
      <ul class="wtc-payment-list">
        <?php foreach($output['payment_list'] as $val) { ?>
        <li payment_code="<?php echo $val['payment_code']; ?>">
          <label for="pay_<?php echo $val['payment_code']; ?>">
          <i></i>
          <div class="logo" for="pay_<?php echo $val['payment_id']; ?>"> <img src="<?php echo SHOP_TEMPLATES_URL?>/images/payment/<?php echo $val['payment_code']; ?>_logo.gif" /> </div>
          <div class="predeposit" wt_type="predeposit" style="display:none">
            <?php if ($val['payment_code'] == 'predeposit') {?>
                <?php if ($output['available_predeposit']) {?>
                <p>当前预存款余额<br/>￥<?php echo $output['available_predeposit'];?><br/>不足以支付该订单<br/><a href="<?php echo MEMBER_SITE_URL.'/index.php?w=predeposit';?>" class="predeposit">马上充值</a></p>
                <?php } else {?>
                <input type="password" class="text w120" name="password" maxlength="40" id="password" value="">
                <p>使用站内预存款进行支付时，需输入您的登录密码进行安全验证。</p>
                <?php } ?>
            <?php } ?>
          </div>
          </label>
        </li>
        <?php } ?>
      </ul>
      <?php } ?>
    </div>
    
    <div class="wtc-bottom"><a href="javascript:void(0);" id="next_button" class="pay-btn"><i class="icon-shield"></i>确认支付</a></div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('.wtc-payment-list > li').on('click',function(){
    	$('.wtc-payment-list > li').removeClass('using');
        $(this).addClass('using');
        $('#payment_code').val($(this).attr('payment_code'));
    });
    $('#next_button').on('click',function(){
        if ($('#payment_code').val() != '') {
            $('#buy_form').submit();
        }
    });
});
</script>