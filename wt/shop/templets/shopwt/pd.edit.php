<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?w=predeposit&t=predeposit" title="返回充值管理列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['wt_member_predepositmanage'];?> - 处理预存款充值</h3>
        <h5><?php echo $lang['wt_member_predepositmanage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form method="post" name="form1" id="form1" action="index.php?w=predeposit&t=recharge_edit&id=<?php echo intval($_GET['id']);?>">
    <input type="hidden" name="form_submit" value="ok"/>
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_predeposit_sn']; ?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="input-txt" value="<?php echo $output['info']['pdr_sn']; ?>" readonly>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_predeposit_recharge_price'];?>(<?php echo $lang['currency_zh']; ?>)</label>
        </dt>
        <dd class="opt">
          <input type="text" class="input-txt" value="<?php echo $output['info']['pdr_amount']; ?>" readonly>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_predeposit_membername']; ?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="input-txt" value="<?php echo $output['info']['pdr_member_name']; ?>" readonly>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="site_name"> <?php echo $lang['admin_predeposit_paytime'];?></label>
        </dt>
        <dd class="opt">
          <input id="payment_time" class="input-txt" name="payment_time" value="" type="text" />
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="site_name"> <?php echo $lang['admin_predeposit_payment'];?></label>
        </dt>
        <dd class="opt">
          <select name="payment_code" class="s-select">
            <option value=""><?php echo $lang['wt_please_choose'];?></option>
            <?php foreach($output['payment_list'] as $val) { ?>
            <option value="<?php echo $val['payment_code']; ?>"><?php echo $val['payment_name']; ?></option>
            <?php } ?>
          </select>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="closed_reason"> 第三方支付平台交易号</label>
        </dt>
        <dd class="opt">
          <input type="text" class="input-txt" name="trade_no" id="trade_no" maxlength="40">
          <span class="err"></span>
          <p class="notic">支付宝等第三方支付平台交易号</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" id="wtsubmit" class="wtap-btn-big wtap-btn-green"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('#payment_time').datepicker({dateFormat: 'yy-mm-dd',maxDate: '<?php echo date('Y-m-d',TIMESTAMP);?>'});
    $('#wtsubmit').click(function(){
    	if($("#form1").valid()){
        	if (confirm("操作提醒：\n该操作不可撤销\n提交前请务必确认是否已收到付款\n继续操作吗?")){
        	}else{
        		return false;
        	}
        	$('#form1').submit();
    	}
    });
	$("#form1").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
        	payment_time : {
                required : true
            },
            payment_code : {
                required : true
            },
            trade_no    :{
                required : true
            }       
        },
        messages : {
        	payment_time : {
                required : '<i class="fa fa-exclamation-bbs"></i>请填写付款时间'
            },
            payment_code : {
                required : '<i class="fa fa-exclamation-bbs"></i>请选择付款方式'
            },
            trade_no : {
                required : '<i class="fa fa-exclamation-bbs"></i>请填写第三方支付平台交易号'
            }
        }
	});
});
</script>