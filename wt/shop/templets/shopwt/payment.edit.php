<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?w=payment" title="返回支付方式列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['wt_pay_method'];?> - <?php echo $lang['wt_set'];?>“<?php echo $output['payment']['payment_name'];?>”</h3>
        <h5><?php echo $lang['wt_pay_method_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="post_form" method="post" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="payment_id" value="<?php echo $output['payment']['payment_id'];?>" />
    <div class="wtap-form-default">
      <?php if ($output['payment']['payment_code'] == 'chinabank') { ?>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_chinabank_account'];?></dt>
        <dd class="opt">
          <input type="hidden" name="config_name" value="chinabank_account,chinabank_key" />
          <input name="chinabank_account" id="chinabank_account" value="<?php echo $output['config_array']['chinabank_account'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_chinabank_key'];?></dt>
        <dd class="opt">
          <input name="chinabank_key" id="chinabank_key" value="<?php echo $output['config_array']['chinabank_key'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php } elseif ($output['payment']['payment_code'] == 'tenpay') { ?>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_tenpay_account'];?></dt>
        <dd class="opt">
          <input type="hidden" name="config_name" value="tenpay_account,tenpay_key" />
          <input name="tenpay_account" id="tenpay_account" value="<?php echo $output['config_array']['tenpay_account'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_tenpay_key'];?></dt>
        <dd class="opt">
          <input name="tenpay_key" id="tenpay_key" value="<?php echo $output['config_array']['tenpay_key'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php } elseif ($output['payment']['payment_code'] == 'alipay') { ?>
      <div class="row">
        <dd class="opt">支付宝在线退款功能要在支付宝网站输入该账号的“支付密码”，管理员进行确认后才能完成退款操作。</dd>
      </div>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_alipay_account'];?></dt>
        <dd class="opt">
          <input type="hidden" name="config_name" value="alipay_service,alipay_account,alipay_key,alipay_partner" />
          <input type="hidden" name="alipay_service" value="create_direct_pay_by_user" />
          <input name="alipay_account" id="alipay_account" value="<?php echo $output['config_array']['alipay_account'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_alipay_partner'];?></dt>
        <dd class="opt">
          <input name="alipay_partner" id="alipay_partner" value="<?php echo $output['config_array']['alipay_partner'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_alipay_key'];?></dt>
        <dd class="opt">
          <input name="alipay_key" id="alipay_key" value="<?php echo $output['config_array']['alipay_key'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
 <?php } elseif ($output['payment']['payment_code'] == 'paypal') { ?>
		
		<dl class="row">
        <dt class="tit">商户账户: </dt>
        <dd class="opt">
		<input type="hidden" name="config_name" value="paypal_account,paypal_currency,sandbox" />
	    <input name="paypal_account" id="paypal_account" value="<?php echo $output['config_array']['paypal_account'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
		
		<dl class="row">
        <dt class="tit">支付货币: </dt>
        <dd class="opt">
		          <select name="paypal_currency">
                    <option value="CNY" <?php if($output['config_array']['paypal_currency']=='CNY'){ echo 'selected';}?>>人民币</option>
                        <option value="CAD" <?php if($output['config_array']['paypal_currency']=='CAD'){ echo 'selected';}?>>加元</option>
                        <option value="EUR" <?php if($output['config_array']['paypal_currency']=='EUR'){ echo 'selected';}?>>欧元</option>
                        <option value="GBP" <?php if($output['config_array']['paypal_currency']=='GBP'){ echo 'selected';}?>>英镑</option>
                        <option value="JPY" <?php if($output['config_array']['paypal_currency']=='JPY'){ echo 'selected';}?>>日元</option>
                        <option value="USD" <?php if($output['config_array']['paypal_currency']=='USD'){ echo 'selected';}?>>美元</option>
                        <option value="HKD" <?php if($output['config_array']['paypal_currency']=='HKD'){ echo 'selected';}?>>港元</option>
                        <option value="TWD" <?php if($output['config_array']['paypal_currency']=='TWD'){ echo 'selected';}?>>新台币</option>
                        <option value="SGD" <?php if($output['config_array']['paypal_currency']=='SGD'){ echo 'selected';}?>>新加坡元</option>
                        <option value="NZD" <?php if($output['config_array']['paypal_currency']=='NZD'){ echo 'selected';}?>>新西兰元</option>
                    </select>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
		
		<dl class="row">
        <dt class="tit">环境: </dt>
        <dd class="opt">
               <select name="sandbox">
                    <option value="1" <?php if($output['config_array']['sandbox']=='1'){ echo 'selected';}?>>正式环境</option>
               </select>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php } elseif ($output['payment']['payment_code'] == 'wxpay') { ?>
      <div class="row">
        <dd class="opt">如果启用微信在线退款功能需要在服务器设置“证书”，请联系技术人员配置证书。</dd>
        <dd class="opt">退款有一定延时，用零钱支付的20分钟内到账，银行卡支付的至少3个工作日。</dd>
      </div>
      <dl class="row">
        <dt class="tit">公众号AppID</dt>
        <dd class="opt">
          <input type="hidden" name="config_name" value="appid,mchid,key" />
          <input name="appid" id="appid" value="<?php echo $output['config_array']['appid'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">公众号AppID 开户邮件中可查看</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">商户号</dt>
        <dd class="opt">
          <input name="mchid" id="mchid" value="<?php echo $output['config_array']['mchid'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">微信支付对应的商户号，开户邮件中可查看</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">API密钥</dt>
        <dd class="opt">
          <input name="key" id="key" value="<?php echo $output['config_array']['key'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">到微信商户平台(账户设置-安全设置-API安全)进行设置得到</p>
        </dd>
      </dl>
      <?php } ?>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_index_enable'];?></dt>
        <dd class="opt">
          <div class="onoff">
            <label for="payment_state1" class="cb-enable <?php if($output['payment']['payment_state'] == '1'){ ?>selected<?php } ?>" ><?php echo $lang['wt_yes'];?></label>
            <label for="payment_state2" class="cb-disable <?php if($output['payment']['payment_state'] == '0'){ ?>selected<?php } ?>" ><?php echo $lang['wt_no'];?></label>
            <input type="radio" <?php if($output['payment']['payment_state'] == '1'){ ?>checked="checked"<?php }?> value="1" name="payment_state" id="payment_state1">
            <input type="radio" <?php if($output['payment']['payment_state'] == '0'){ ?>checked="checked"<?php }?> value="0" name="payment_state" id="payment_state2">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="submitBtn" onclick="document.form1.submit()"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
$(document).ready(function(){
	$('#post_form').validate({
		<?php if($output['payment']['payment_code'] == 'chinabank') { ?>
        rules : {
            chinabank_account : {
                required   : true
            },
            chinabank_key : {
                required   : true
            }
        },
        messages : {
            chinabank_account  : {
                required : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['payment_chinabank_account'];?><?php echo $lang['payment_edit_not_null']; ?>'
            },
            chinabank_key  : {
                required : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['payment_chinabank_key'];?><?php echo $lang['payment_edit_not_null']; ?>'
            }
        }
		<?php } elseif ($output['payment']['payment_code'] == 'tenpay') { ?>
        rules : {
            tenpay_account : {
                required   : true
            },
            tenpay_key : {
                required   : true
            }
        },
        messages : {
            tenpay_account  : {
                required : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['payment_tenpay_account'];?><?php echo $lang['payment_edit_not_null']; ?>'
            },
            tenpay_key  : {
                required : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['payment_tenpay_key'];?><?php echo $lang['payment_edit_not_null']; ?>'
            }
        }
			
		<?php } elseif ($output['payment']['payment_code'] == 'alipay') { ?>
        rules : {
            alipay_account : {
                required   : true
            },
            alipay_partner : {
                required   : true
            },
            alipay_key : {
                required   : true
            }			 
        },
        messages : {
            alipay_account  : {
                required : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['payment_alipay_account'];?><?php echo $lang['payment_edit_not_null']; ?>'
            },
            alipay_partner  : {
                required : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['payment_alipay_partner'];?><?php echo $lang['payment_edit_not_null']; ?>'
            },
            alipay_key  : {
                required : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['payment_alipay_key'];?><?php echo $lang['payment_edit_not_null']; ?>'
            }
        }
		<?php } ?>
    });
});
</script>