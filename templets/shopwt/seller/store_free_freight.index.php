<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="wtsc-form-default">
  <form method="post"  action="index.php?w=store_deliver_set&t=free_freight" id="my_store_form">
    <input type="hidden" name="form_submit" value="ok" />
    <dl>
      <dt>免运费额度<?php echo $lang['wt_colon'];?></dt>
      <dd>
        <input class="text w60" name="store_free_price" maxlength="10" type="text"  id="store_free_price" value="<?php echo $output['store_free_price'];?>" /><em class="add-on">
<i class="icon-renminbi"></i>
</em>
        <p class="hint">默认为 0，表示不设置免运费额度，大于0表示购买金额超出该值后将免运费</p>
      </dd>
    </dl>
    <div class="bottom">
        <label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['wt_common_button_save'];?>" /></label>
      </div>
  </form>
</div>
<script type="text/javascript">
var SITEURL = "<?php echo BASE_SITE_URL; ?>";
$(function(){
	$('#my_store_form').validate({
    	submitHandler:function(form){
    		ajaxpost('my_store_form', '', '', 'onerror')
    	},
		rules : {
			store_free_price: {
			required : true,
			number : true
			}
        },
        messages : {
        	store_free_price: {
				required : '请填写金额',
				number : '请正确填写'
			}
        }
    });    
    
});
</script> 
