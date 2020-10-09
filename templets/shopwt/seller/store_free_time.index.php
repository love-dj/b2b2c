<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="wtsc-form-default">
  <form method="post"  action="index.php?w=store_deliver_set&t=free_time" id="my_store_form">
    <input type="hidden" name="form_submit" value="ok" />
    <dl>
      <dt>预计送达时间<?php echo $lang['wt_colon'];?></dt>
      <dd>
        <input class="text w60" name="store_free_time" maxlength="10" type="text"  id="store_free_time" value="<?php echo $output['store_free_time'];?>" /><em class="add-on">
<i>天</i>
</em>
        <p class="hint">默认为0不显示，以天为单位计算。</p>
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
			store_free_time: {
			required : true,
			digits : true
			},
			
        },
        messages : {
			store_free_time: {
				required : '请填写天数',
				digits : '请正确填写，只能整数'
			}
        }
    });    
    
});
</script> 
