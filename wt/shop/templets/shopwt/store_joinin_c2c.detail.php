<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?w=store&t=store_joinin" title="返回<?php echo $lang['pending'];?>列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['wt_store_manage'];?> - 查看会员“<?php echo $output['joinin_detail']['member_name'];?>”的店铺注册信息</h3>
        <h5><?php echo $lang['wt_store_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">个人及联系人信息</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">个人姓名：</th>
        <td colspan="20"><?php echo $output['joinin_detail']['company_name'];?></td>
      </tr>
      <tr>
        <th>家庭所在地：</th>
        <td><?php echo $output['joinin_detail']['company_address'];?></td>
        <th>家庭详细地址：</th>
        <td colspan="20"><?php echo $output['joinin_detail']['company_address_detail'];?></td>
      </tr>
      <tr>
        <th>联系人姓名：</th>
        <td><?php echo $output['joinin_detail']['contacts_name'];?></td>
        <th>联系人电话：</th>
        <td><?php echo $output['joinin_detail']['contacts_phone'];?></td>
        <th>电子邮箱：</th>
        <td><?php echo $output['joinin_detail']['contacts_email'];?></td>
      </tr>
      <tr>
	  <th>身份证正面<br/>电子版：</th>
        <td colspan="20"><a wttype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['organization_code_electronic']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['organization_code_electronic']);?>" alt="" /> </a></td>
      </tr>
      <tr>
        <th>身份证反面<br/>电子版：</th>
        <td colspan="20"><a wttype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['general_taxpayer']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['general_taxpayer']);?>" alt="" /> </a></td>
      </tr>
       <tr>
        <th>手持身份证<br />五官照片：</th>
        <td colspan="20"><a wttype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['business_licence_number_elc']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['business_licence_number_elc']);?>" alt="" /> </a></td>
      </tr>
    </tbody>
  </table>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">结算账号信息：</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">支付宝认证姓名：</th>
        <td><?php echo $output['joinin_detail']['settlement_bank_account_name'];?></td>
      </tr>
      <tr>
        <th>支付宝账号：</th>
        <td><?php echo $output['joinin_detail']['settlement_bank_account_number'];?></td>
      </tr>
    </tbody>
    
  </table>
  <form id="form_store_verify" action="index.php?w=store&t=store_joinin_verify" method="post">
    <input id="verify_type" name="verify_type" type="hidden" />
    <input name="member_id" type="hidden" value="<?php echo $output['joinin_detail']['member_id'];?>" />
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
      <thead>
        <tr>
          <th colspan="20">店铺经营信息</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="w150">商家账号：</th>
          <td><?php echo $output['joinin_detail']['seller_name'];?></td>
        </tr>
        <tr>
          <th class="w150">店铺名称：</th>
          <td><?php echo $output['joinin_detail']['store_name'];?></td>
        </tr>
        <tr>
          <th>店铺等级：</th>
          <td><?php echo $output['joinin_detail']['sg_name'];?>（开店费用：<?php echo $output['joinin_detail']['sg_price'];?> 元/年）</td>
        </tr>
        <tr>
          <th class="w150">开店时长：</th>
          <td><?php echo $output['joinin_detail']['joinin_year'];?> 年</td>
        </tr>
        <tr>
          <th>店铺分类：</th>
          <td><?php echo $output['joinin_detail']['sc_name'];?>（开店保证金：<?php echo $output['joinin_detail']['sc_bail'];?> 元）</td>
        </tr>
        <tr>
          <th>应付总金额：</th>
          <td><?php if(intval($output['joinin_detail']['joinin_state']) === 10) {?>
            <input type="text" value="<?php echo $output['joinin_detail']['paying_amount'];?>" name="paying_amount" />
            元
            <?php } else { ?>
            <?php echo $output['joinin_detail']['paying_amount'];?> 元
            <?php } ?></td>
        </tr>
        <tr>
          <th>经营类目：</th>
          <td colspan="2"><table border="0" cellpadding="0" cellspacing="0" id="table_category" class="type">
              <thead>
                <tr>
                  <th>分类1</th>
                  <th>分类2</th>
                  <th>分类3</th>
                  <th>比例</th>
                </tr>
              </thead>
              <tbody>
                <?php $store_class_names = unserialize($output['joinin_detail']['store_class_names']);?>
                <?php if(!empty($store_class_names) && is_array($store_class_names)) {?>
                <?php $store_class_commis_rates = explode(',', $output['joinin_detail']['store_class_commis_rates']);?>
                <?php for($i=0, $length = count($store_class_names); $i < $length; $i++) {?>
                <?php list($class1, $class2, $class3) = explode(',', $store_class_names[$i]);?>
                <tr>
                  <td><?php echo $class1;?></td>
                  <td><?php echo $class2;?></td>
                  <td><?php echo $class3;?></td>
                  <td><?php if(intval($output['joinin_detail']['joinin_state']) === 10) {?>
                    <input type="text" wttype="commis_rate" value="<?php echo $store_class_commis_rates[$i];?>" name="commis_rate[]" class="w100" />
                    %
                    <?php } else { ?>
                    <?php echo $store_class_commis_rates[$i];?> %
                    <?php } ?></td>
                </tr>
                <?php } ?>
                <?php } ?>
              </tbody>
            </table></td>
        </tr>
        <?php if(in_array(intval($output['joinin_detail']['joinin_state']), array(STORE_JOIN_STATE_PAY, STORE_JOIN_STATE_FINAL))) {?>
        <tr>
          <th>付款凭证：</th>
          <td><a wttype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['paying_money_certificate']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['paying_money_certificate']);?>" alt="" /> </a></td>
        </tr>
        <tr>
          <th>付款凭证说明：</th>
          <td><?php echo $output['joinin_detail']['paying_money_certif_exp'];?></td>
        </tr>
        <?php } ?>
        <?php if(in_array(intval($output['joinin_detail']['joinin_state']), array(STORE_JOIN_STATE_NEW, STORE_JOIN_STATE_PAY))) { ?>
        <tr>
          <th>审核意见：</th>
          <td colspan="2"><textarea id="joinin_message" name="joinin_message"></textarea></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <?php if(in_array(intval($output['joinin_detail']['joinin_state']), array(STORE_JOIN_STATE_NEW, STORE_JOIN_STATE_PAY))) { ?>
    <div id="validation_message" style="color:red;display:none;"></div>
    <div class="bottom"><a id="btn_pass" class="wtap-btn-big wtap-btn-green mr10" href="JavaScript:void(0);">通过</a><a id="btn_fail" class="wtap-btn-big wtap-btn-red" href="JavaScript:void(0);">拒绝</a> </div>
    <?php } ?>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_STATIC_URL;?>/js/jquery.nyroModal.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('a[wttype="nyroModal"]').nyroModal();

        $('#btn_fail').on('click', function() {
            if($('#joinin_message').val() == '') {
                $('#validation_message').text('请输入审核意见');
                $('#validation_message').show();
                return false;
            } else {
                $('#validation_message').hide();
            }
            if(confirm('确认拒绝申请？')) {
                $('#verify_type').val('fail');
                $('#form_store_verify').submit();
            }
        });
        $('#btn_pass').on('click', function() {
            var valid = true;
            $('[wttype="commis_rate"]').each(function(commis_rate) {
                rate = $(this).val();
                if(rate == '') {
                    valid = false;
                    return false;
                }

                var rate = Number($(this).val());
                if(isNaN(rate) || rate < 0 || rate >= 100) {
                    valid = false;
                    return false;
                }
            });
            if(valid) {
                $('#validation_message').hide();
                if(confirm('确认通过申请？')) {
                    $('#verify_type').val('pass');
                    $('#form_store_verify').submit();
                }
            } else {
                $('#validation_message').text('请正确填写分佣比例');
                $('#validation_message').show();
            }
        });
    });
</script>
