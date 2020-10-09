<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="<?php echo urlAdminMobile('mb_payment', 'payment_list');?>" title="返回手机支付方式列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3>手机支付方式 - <?php echo $lang['wt_set'];?>“<?php echo $output['payment']['payment_name'];?>”</h3>
        <h5>手机客户端可使用支付方式/接口设置</h5>
      </div>
    </div>
  </div>
  <form id="post_form" method="post" name="form1" action="<?php echo urlAdminMobile('mb_payment', 'payment_save');?>">
    <input type="hidden" name="payment_id" value="<?php echo $output['payment']['payment_id'];?>" />
    <input type="hidden" name="payment_code" value="<?php echo $output['payment']['payment_code'];?>" />
    <div class="wtap-form-default">
      <?php if ($output['payment']['payment_code'] == 'alipay') { ?>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>支付宝账号</label>
        </dt>
        <dd class="opt">
          <input name="alipay_account" id="alipay_account" value="<?php echo $output['payment']['payment_config']['alipay_account'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>合作伙伴身份（PID）</label>
        </dt>
        <dd class="opt">
          <input name="alipay_partner" id="alipay_partner" value="<?php echo $output['payment']['payment_config']['alipay_partner'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>安全校验码（key）</label>
        </dt>
        <dd class="opt">
          <input name="alipay_key" id="alipay_key" value="<?php echo $output['payment']['payment_config']['alipay_key'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php } ?>
      <?php if ($output['payment']['payment_code'] == 'alipay_native') { ?>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>支付宝账号</label>
        </dt>
        <dd class="opt">
          <input name="alipay_account" id="alipay_account" value="<?php echo $output['payment']['payment_config']['alipay_account'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>交易安全校验码(key)</label>
        </dt>
        <dd class="opt">
          <input name="alipay_key" id="alipay_key" value="<?php echo $output['payment']['payment_config']['alipay_key'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>合作者身份(partner ID)</label>
        </dt>
        <dd class="opt">
          <input name="alipay_partner" id="alipay_partner" value="<?php echo $output['payment']['payment_config']['alipay_partner'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php } ?>
      <?php if ($output['payment']['payment_code'] == 'wxpay') { ?>
      <div class="row">
        <dd class="opt">如果启用微信在线退款功能需要在服务器设置“证书”，证书文件不能放在web服务器虚拟目录，应放在有访问权限控制的目录中，防止被他人下载。</dd>
        <dd class="opt">证书路径在“admin\api\refund\wxpay\WxPayApp.Config.php”中。退款有一定延时，用零钱支付的20分钟内到账，银行卡支付的至少3个工作日。</dd>
      </div>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>APP唯一凭证(appid)</label>
        </dt>
        <dd class="opt">
          <input name="wxpay_appid" id="wxpay_appid" value="<?php echo $output['payment']['payment_config']['wxpay_appid'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">APP唯一凭证，需要到微信开放平台进行申请</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>商户号(Mchid/partnerid)</label>
        </dt>
        <dd class="opt">
          <input name="wxpay_partnerid" id="wxpay_partnerid" value="<?php echo $output['payment']['payment_config']['wxpay_partnerid'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">微信支付对应的商户号，开户邮件中可查看</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>API密钥</label>
        </dt>
        <dd class="opt">
          <input name="wxpay_partnerkey" id="wxpay_partnerkey" value="<?php echo $output['payment']['payment_config']['wxpay_partnerkey'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">到微信商户平台(账户设置-安全设置-API安全)进行设置得到</p>
        </dd>
      </dl>
      <?php } ?>
      <?php if ($output['payment']['payment_code'] == 'wxpay_jsapi' || $output['payment']['payment_code'] == 'wxpay_h5') { ?>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>公众号AppID</label>
        </dt>
        <dd class="opt">
          <input name="appId" id="appId" value="<?php echo $output['payment']['payment_config']['appId'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">公众号AppID 开户邮件中可查看</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>公众号Appsecret</label>
        </dt>
        <dd class="opt">
          <input name="appSecret" id="appSecret" value="<?php echo $output['payment']['payment_config']['appSecret'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">公众号Appsecret 开户邮件中可查看。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>商户号</label>
        </dt>
        <dd class="opt">
          <input name="partnerId" id="partnerId" value="<?php echo $output['payment']['payment_config']['partnerId'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">微信支付对应的商户号，开户邮件中可查看</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>API密钥</label>
        </dt>
        <dd class="opt">
          <input name="apiKey" id="apiKey" value="<?php echo $output['payment']['payment_config']['apiKey'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">到微信商户平台(账户设置-安全设置-API安全)进行设置得到</p>
        </dd>
      </dl>
      <?php } ?>
	<?php if ($output['payment']['payment_code'] == 'mini_wxpay') { ?>
	      <dl class="row">
	        <dt class="tit">
	          <label><em>*</em>小程序AppID</label>
	        </dt>
	        <dd class="opt">
	          <input name="appId" id="appId" value="<?php echo $output['payment']['payment_config']['appId'];?>" class="input-txt" type="text">
	          <span class="err"></span>
	          <p class="notic">小程序AppID在登录微信小程序后(设置-开发设置)里看到</p>
	        </dd>
	      </dl>
	      <dl class="row">
	        <dt class="tit">
	          <label><em>*</em>小程序Appsecret</label>
	        </dt>
	        <dd class="opt">
	          <input name="appSecret" id="appSecret" value="<?php echo $output['payment']['payment_config']['appSecret'];?>" class="input-txt" type="text">
	          <span class="err"></span>
	          <p class="notic">小程序Appsecret在登录微信小程序后(设置-开发设置)里看到</p>
	        </dd>
	      </dl>
	      <dl class="row">
	        <dt class="tit">
	          <label><em>*</em>商户号</label>
	        </dt>
	        <dd class="opt">
	          <input name="partnerId" id="partnerId" value="<?php echo $output['payment']['payment_config']['partnerId'];?>" class="input-txt" type="text">
	          <span class="err"></span>
	          <p class="notic">微信支付对应的商户号，开户邮件中可查看</p>
	        </dd>
	      </dl>
	      <dl class="row">
	        <dt class="tit">
	          <label><em>*</em>API密钥</label>
	        </dt>
	        <dd class="opt">
	          <input name="apiKey" id="apiKey" value="<?php echo $output['payment']['payment_config']['apiKey'];?>" class="input-txt" type="text">
	          <span class="err"></span>
	          <p class="notic">到微信商户平台(账户设置-安全设置-API安全)进行设置得到</p>
	        </dd>
	      </dl>
	      <?php } ?>

       <?php if ($output['payment']['payment_code'] == 'paypal') { ?>
		<dl class="row">
        <dt class="tit">商户账户: </dt>
        <dd class="opt">
	    <input name="paypal_account" id="paypal_account" value="<?php echo $output['payment']['payment_config']['paypal_account'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
		
		<dl class="row">
        <dt class="tit">支付货币: </dt>
        <dd class="opt">
                <select name="paypal_currency">
                    <option value="CNY" <?php if($output['payment']['payment_config']['paypal_currency']=='CNY'){ echo 'selected';}?>>人民币</option>
                    <option value="AUD" <?php if($output['payment']['payment_config']['paypal_currency']=='AUD'){ echo 'selected';}?>>澳元</option>
                    <option value="CAD" <?php if($output['payment']['payment_config']['paypal_currency']=='CAD'){ echo 'selected';}?>>加元</option>
                    <option value="EUR" <?php if($output['payment']['payment_config']['paypal_currency']=='EUR'){ echo 'selected';}?>>欧元</option>
                    <option value="GBP" <?php if($output['payment']['payment_config']['paypal_currency']=='GBP'){ echo 'selected';}?>>英镑</option>
                    <option value="JPY" <?php if($output['payment']['payment_config']['paypal_currency']=='JPY'){ echo 'selected';}?>>日元</option>
                    <option value="USD" <?php if($output['payment']['payment_config']['paypal_currency']=='USD'){ echo 'selected';}?>>美元</option>
                    <option value="HKD" <?php if($output['payment']['payment_config']['paypal_currency']=='HKD'){ echo 'selected';}?>>港元</option>
                    <option value="TWD" <?php if($output['payment']['payment_config']['paypal_currency']=='TWD'){ echo 'selected';}?>>新台币</option>
                    <option value="SGD" <?php if($output['payment']['payment_config']['paypal_currency']=='SGD'){ echo 'selected';}?>>新加坡元</option>
                    <option value="NZD" <?php if($output['payment']['payment_config']['paypal_currency']=='NZD'){ echo 'selected';}?>>新西兰元</option>
                </select>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
		
		<dl class="row">
        <dt class="tit">环境: </dt>
        <dd class="opt">
                <select name="sandbox">
                    <option value="1" <?php if($output['payment']['payment_config']['sandbox']=='1'){ echo 'selected';}?>>正式环境</option>
                </select>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
        <?php } ?>
      <dl class="row">
        <dt class="tit">启用</dt>
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
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="btn_submit" ><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
$(document).ready(function(){
	$('#post_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
		<?php if ($output['payment']['payment_code'] == 'alipay') { ?>
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
                required : '<i class="fa fa-exclamation-bbs"></i>不能为空'
            },
            alipay_partner  : {
                required : '<i class="fa fa-exclamation-bbs"></i>不能为空'
            },
            alipay_key  : {
                required : '<i class="fa fa-exclamation-bbs"></i>不能为空'
            },
        }
		<?php } ?>
		<?php if ($output['payment']['payment_code'] == 'alipay_native') { ?>
        rules : {
            alipay_account : {
                required   : true
            },
            alipay_key : {
                required   : true
            },
            alipay_partner : {
                required   : true
            }
        },
        messages : {
            alipay_account  : {
                required : '<i class="fa fa-exclamation-bbs"></i>不能为空'
            },
            alipay_key  : {
                required : '<i class="fa fa-exclamation-bbs"></i>不能为空'
            },
            alipay_partner  : {
                required : '<i class="fa fa-exclamation-bbs"></i>不能为空'
            }
        }
		<?php } ?>
		<?php if ($output['payment']['payment_code'] == 'wxpay') { ?>
        rules : {
            wxpay_appid : {
                required   : true
            },
            wxpay_partnerid : {
                required   : true
            },
            wxpay_partnerkey : {
                required   : true
            }
        },
        messages : {
            wxpay_appid  : {
                required : '<i class="fa fa-exclamation-bbs"></i>不能为空'
            },
            wxpay_partnerid  : {
                required : '<i class="fa fa-exclamation-bbs"></i>不能为空'
            },	 
            wxpay_partnerkey  : {
                required : '<i class="fa fa-exclamation-bbs"></i>不能为空'
            }
        }
		<?php } ?>
		<?php if ($output['payment']['payment_code'] == 'wxpay_jsapi') { ?>
        rules : {
            appId : {
                required   : true
            },
            appSecret : {
                required   : true
            },
            partnerId : {
                required   : true
            },
            apiKey : {
                required   : true
            }
        },
        messages : {
            appId  : {
                required : '<i class="fa fa-exclamation-bbs"></i>不能为空'
            },
            appSecret  : {
                required : '<i class="fa fa-exclamation-bbs"></i>不能为空'
            },
            partnerId  : {
                required : '<i class="fa fa-exclamation-bbs"></i>不能为空'
            },
            partnerId  : {
                apiKey : '<i class="fa fa-exclamation-bbs"></i>不能为空'
            }
        }
		<?php } ?>
    });

    $('#btn_submit').on('click', function() {
        $('#post_form').submit();
    });
});
</script>
