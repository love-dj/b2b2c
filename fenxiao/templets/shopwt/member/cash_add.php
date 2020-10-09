<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <div class="alert">
    <span class="mr30">
      <?php echo '可提现佣金金额'.$lang['wt_colon']; ?>
      <strong class="mr5 red" style="font-size: 18px;">
        <?php 
          echo wtPriceFormatForList($output['member_info']['available_fx_trad']);
        ?>
      </strong><?php echo $lang['currency_zh'];?>
    </span>
  </div>
  <div class="wtm-default-form">
    <?php if($output['member_info']['available_fx_trad'] > 0){?>
    <form method="post" id="recharge_form" action="index.php">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="w" value="cash" />
      <input type="hidden" name="t" value="apply_cash" />
      <dl>
        <dt><?php echo '收款人'.$lang['wt_colon']; ?></dt>
        <dd>
          <?php echo $output['member_info']['bill_user_name'];?>
        </dd>
      </dl>
      <dl>
        <dt><?php echo '收款银行'.$lang['wt_colon']; ?></dt>
        <dd>
          <?php echo $output['member_info']['bill_bank_name'];?>
        </dd>
      </dl>
      <dl>
        <dt><?php echo '收款账号'.$lang['wt_colon']; ?></dt>
        <dd>
          <?php echo $output['member_info']['bill_type_number'];?>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i><?php echo '提现金额'.$lang['wt_colon']; ?></dt>
        <dd>
          <input name="cash_amount" type="text" class="text w100" id="cash_amount" maxlength="8" max="<?php echo $output['member_info']['available_fx_trad'];?>" />
          <em class="add-on">
            <i class="icon-renminbi"></i>
          </em><span class="error"></span>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i><?php echo '支付密码'.$lang['wt_colon']; ?></dt>
        <dd>
          <input name="pay_pwd" type="password" class="text w200" id="pay_pwd" maxlength="35" autocomplete="off" />
          <span class="error"></span>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i><?php echo '验证码'.$lang['wt_colon']; ?></dt>
        <dd>
          <input  type="text" id="captcha" name="captcha" class="text w80" size="10"  />
          <span onclick="javascript:document.getElementById('codeimage').src='index.php?w=vercode&t=makecode&type=30x92&wthash=<?php echo getwthash();?>&c=' + Math.random();">
            <img src="index.php?w=vercode&t=makecode&type=30x92&wthash=<?php echo getwthash();?>" name="codeimage" id="codeimage"/> <a class="makecode" href="javascript:void(0)">更换验证码</a>
          </span>
          <span class="error"></span></dd>
      </dl>
      <dl class="bottom">
        <dt>&nbsp; </dt>
        <dd>
          <label class="submit-border"><a href="javascript:void(0)" class="submit"><?php echo $lang['wt_submit']; ?></a></label>
        </dd>
      </dl>
    </form>
    <?php } else {?>
       &nbsp;&nbsp;&nbsp;&nbsp;您现在无可提现佣金
    <?php }?>
  </div>
</div>
<script type="text/javascript">
$(function(){
	$('#recharge_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('.error');
            error_td.append(error);
        },
        onkeyup: false,
        rules : {
        	cash_amount: {
	          required  : true,
	          number    : true,
            max       : <?php echo $output['member_info']['available_fx_trad'];?>,
	          min       : 0.01
          },
          pay_pwd: {
            required  : true,
            remote   : {
              url : 'index.php?w=cash&t=check_pwd',
              type: 'get',
              data:{
                pay_pwd : function(){
                  return $('#pay_pwd').val();
                }
              }
            }
          },
          captcha: {
            required  : true,
            remote   : {
              url : 'index.php?w=vercode&t=check&wthash=<?php echo getwthash();?>',
              type: 'get',
              data:{
                captcha : function(){
                  return $('#captcha').val();
                }
              },
              complete: function(data) {
                if(data.responseText == 'false') {
                  document.getElementById('codeimage').src='index.php?w=vercode&t=makecode&type=30x92&wthash=<?php echo getwthash();?>&c=' + Math.random();
                }
              }
            }
          }
        },
        messages : {
        	cash_amount		: {
          	required  :'<i class="icon-exclamation-sign"></i>请正确填写提现金额',
           	number    :'<i class="icon-exclamation-sign"></i>请正确填写提现金额',
            max       :'<i class="icon-exclamation-sign"></i>请正确填写提现金额',
            min    	  :'<i class="icon-exclamation-sign"></i>请正确填写提现金额'
          },
          pay_pwd: {
            required  : '<i class="icon-exclamation-sign"></i>请正确填写支付密码',
            remote   : '<i class="icon-exclamation-sign"></i>请正确填写支付密码'
          },
          captcha: {
            required  : '<i class="icon-exclamation-sign"></i>请正确填写验证码',
            remote   : '<i class="icon-exclamation-sign"></i>请正确填写验证码',
          }
        }
    });
  $('.submit').click(function(){
    if($('#recharge_form').validate()){
      if(confirm('请确保提现信息正确无误')){
        $('#recharge_form').submit();
      }
    }
  });
});
</script>