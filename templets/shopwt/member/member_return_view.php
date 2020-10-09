<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wtm-flow-box">
  <div class="wtm-flow-container">
    <div class="title">
      <h3>退货退款服务</h3>
    </div>
    <div class="alert">
      <h4>提示：</h4>
      <ul>
        <li>1. 若提出申请后，商家拒绝退款或退货，可再次提交申请或选择<em>“商品投诉”</em>，请求商城客服人员介入。</li>
        <li>2. 成功完成退款/退货；经过商城审核后，会将退款金额以<em>“预存款”</em>的形式返还到您的余额账户中（充值卡部分只能退回到充值卡余额）。</li>
      </ul>
    </div>
    <div id="saleRefundReturn" show_id="1">
      <div class="wtm-flow-step">
        <dl class="step-first current">
          <dt>买家申请退货</dt>
          <dd class="bg"></dd>
        </dl>
        <dl class="<?php echo $output['return']['seller_time'] > 0 ? 'current':'';?>">
          <dt>商家处理退货申请</dt>
          <dd class="bg"> </dd>
        </dl>
        <dl class="<?php echo ($output['return']['ship_time'] > 0 || $output['return']['return_type']==1) ? 'current':'';?>">
          <dt>买家退货给商家</dt>
          <dd class="bg"> </dd>
        </dl>
        <dl class="<?php echo $output['return']['admin_time'] > 0 ? 'current':'';?>">
          <dt>确认收货，平台审核</dt>
          <dd class="bg"> </dd>
        </dl>
      </div>
      <div class="wtm-default-form">
        <h3>我的退货退款申请</h3>
        <dl>
          <dt>退货退款编号：</dt>
          <dd><?php echo $output['return']['refund_sn']; ?> </dd>
        </dl>
        <dl>
          <dt>退货退款原因：</dt>
          <dd><?php echo $output['return']['reason_info']; ?> </dd>
        </dl>
        <dl>
          <dt>退款金额：</dt>
          <dd><?php echo $lang['currency'];?><?php echo $output['return']['refund_amount']; ?> </dd>
        </dl>
        <dl>
          <dt><?php echo $lang['return_order_return'].$lang['wt_colon'];?></dt>
          <dd><?php echo $output['return']['return_type']==2 ? $output['return']['goods_num']:'无'; ?></dd>
        </dl>
        <dl>
          <dt>退货退款说明：</dt>
          <dd><?php echo $output['return']['buyer_message']; ?> </dd>
        </dl>
        <dl>
          <dt>凭证上传：</dt>
          <dd>
            <?php if (is_array($output['pic_list']) && !empty($output['pic_list'])) { ?>
            <ul class="wtm-evidence-pic">
              <?php foreach ($output['pic_list'] as $key => $val) { ?>
              <?php if(!empty($val)){ ?>
              <li><a href="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/refund/'.$val;?>"  wttype="nyroModal"> <img class="show_image" src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/refund/'.$val;?>"></a></li>
              <?php } ?>
              <?php } ?>
            </ul>
            <?php } ?>
          </dd>
        </dl>
        <h3>商家退货退款处理</h3>
        <dl>
          <dt><?php echo $lang['refund_state'].$lang['wt_colon'];?></dt>
          <dd><?php echo $output['state_array'][$output['return']['seller_state']]; ?>
            <?php if ($output['return']['seller_state'] == 2 && $output['return']['return_type'] == 1) { ?>
            （商家弃货，即不用将商品退回直接退款。）
            <?php } ?>
            </dd>
        </dl>
        <?php if ($output['return']['seller_time'] > 0) { ?>
        <dl>
          <dt><?php echo $lang['refund_seller_message'].$lang['wt_colon'];?></dt>
          <dd><?php echo $output['return']['seller_message']; ?> </dd>
        </dl>
        <?php } ?>
        <?php if($output['return']['seller_state'] == 2 && $output['return']['return_type'] == 2 && $output['return']['goods_state'] == 1 && $output['ship'] == 1) { ?>
        <?php require template('member/member_return_ship');?>
        <?php } else { ?>
        <?php if ($output['return']['express_id'] > 0 && !empty($output['return']['invoice_no'])) { ?>
        <h3>我的退货发货信息</h3>
        <dl>
          <dt><?php echo '物流信息'.$lang['wt_colon'];?></dt>
          <dd> <?php echo $output['return_e_name'].' , '.$output['return']['invoice_no']; ?> </dd>
        </dl>
        <?php } ?>
        <?php if ($output['return']['seller_state'] == 2 && $output['return']['refund_state'] >= 2) { ?>
        <h3>商城退款审核</h3>
        <dl>
          <dt><?php echo '平台确认'.$lang['wt_colon'];?></dt>
          <dd><?php echo $output['admin_array'][$output['return']['refund_state']]; ?> </dd>
        </dl>
        <?php } ?>
        <?php if ($output['return']['admin_time'] > 0) { ?>
        <dl>
          <dt><?php echo '平台备注'.$lang['wt_colon'];?></dt>
          <dd><?php echo $output['return']['admin_message']; ?> </dd>
        </dl>
        <?php if ($output['detail_array']['refund_state'] == 2) { ?>
        <h3>退款详细</h3>
        <dl>
          <dt>支付方式：</dt>
          <dd><?php echo orderPaymentName($output['detail_array']['refund_code']);?></dd>
        </dl>
        <dl>
          <dt>在线退款金额：</dt>
          <dd><?php echo wtPriceFormat($output['detail_array']['pay_amount']); ?> </dd>
        </dl>
        <dl>
          <dt>预存款金额：</dt>
          <dd><?php echo wtPriceFormat($output['detail_array']['pd_amount']); ?> </dd>
        </dl>
        <dl>
          <dt>充值卡金额：</dt>
          <dd><?php echo wtPriceFormat($output['detail_array']['rcb_amount']); ?> </dd>
        </dl>
        <?php } ?>
        <?php } ?>
        <div class="bottom"><a href="javascript:history.go(-1);" class="wtbtn"><i class="icon-reply"></i>返回列表</a></div>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php require template('member/member_refund_right');?>
</div>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" ></script>
<link href="<?php echo STATIC_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script>
$(document).ready(function(){
   $('a[wttype="nyroModal"]').nyroModal();
});
</script>