<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="wtsc-form-default">
  <form id="add_form" action="index.php?w=store_sale_pingou&t=quota_add_save" method="post">
    <dl>
      <dt><i class="required">*</i>套餐购买数量<?php echo $lang['wt_colon'];?></dt>
      <dd>
          <input id="pingou_quantity" name="pingou_quantity" type="text" class="text w30" /><em class="add-on">月</em><span></span>
        <p class="hint">购买单位为月(30天)，您需要支付<?php echo $output['setting_config']['sale_pingou_price'].$lang['wt_yuan'];?> ; </p>
        <p class="hint"><strong style="color: red">相关费用会在店铺的账期结算中扣除</strong></p>
      </dd>
    </dl>
    <div class="bottom">
      <label class="submit-border"><input id="submit_button" type="submit" class="submit" value="<?php echo $lang['wt_submit'];?>"></label>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
$(document).ready(function(){
    //页面输入内容验证
    $("#add_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span');
            error_td.append(error);
        },
    	submitHandler:function(form){
            var unit_price = <?php echo $output['setting_config']['sale_pingou_price'];?>;
            var quantity = $("#pingou_quantity").val();
            var price = unit_price * quantity;
            showDialog('确认购买?您总共需要支付'+price+'<?php echo $lang['wt_yuan'];?>', 'confirm', '', function(){
            	ajaxpost('add_form', '', '', 'onerror');
            	});
    	},
            rules : {
                pingou_quantity : {
                    required : true,
                    digits : true,
                    min : 1,
                    max : 12
                }
            },
                messages : {
                    pingou_quantity : {
                        required : "<i class='icon-exclamation-sign'></i>数量不能为空，且必须为1-12之间的整数",
                        digits : "<i class='icon-exclamation-sign'></i>数量不能为空，且必须为1-12之间的整数",
                        min : "<i class='icon-exclamation-sign'></i>数量不能为空，且必须为1-12之间的整数",
                        max : "<i class='icon-exclamation-sign'></i>数量不能为空，且必须为1-12之间的整数"
                    }
                }
    });
});
</script>
