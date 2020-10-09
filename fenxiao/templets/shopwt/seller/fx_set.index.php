<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="wtsc-form-default">
  <form method="post" id="post_form" action="<?php echo FENXIAO_SITE_URL;?>/index.php?w=store_fx_set&t=index">
    <input type="hidden" name="form_submit" value="ok" />
    <dl>
      <dt>默认佣金比例<?php echo $lang['wt_colon'];?></dt>
      <dd>
        <input class="text w30" name="fx_commis_rate" maxlength="2" type="text"  id="fx_commis_rate" value="<?php echo $output['fx_commis_rate'];?>" />
        %
        <label for="fx_commis_rate" class="error"></label>
        <p class="hint">最小为1，最大为30，只能为整数。</p>
      </dd>
    </dl>
    <div class="bottom">
        <label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['wt_common_button_save'];?>" /></label>
      </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
	$('#post_form').validate({
    	submitHandler:function(form){
    		ajaxpost('post_form', '', '', 'onerror')
    	},
		rules : {
			fx_commis_rate: {
                required    : true,
                number      : true,
                min         : 1,
                max         : 30,
			}
        },
        messages : {
        	fx_commis_rate: {
                required    : '请填写正确的佣金比例',
                number      : '请填写正确的佣金比例',
                min         : '佣金比例最小为1',
                max         : '佣金比例最大为30',
			}
        }
    });    
    
});
</script> 
