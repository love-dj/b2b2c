<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<script src="<?php echo ADMIN_STATIC_URL?>/js/admin.js" type="text/javascript"></script>
<form method="post" name="form1" id="form1" class="wtap-form-dialog" action="<?php echo urlAdminShop('goods', 'goods_verify');?>">
  <input type="hidden" name="form_submit" value="ok" />
  <input type="hidden" value="<?php echo $output['common_info']['goods_commonid'];?>" name="commonid">
  <div class="wtap-form-default">
    <dl class="row">
      <dt class="tit">审核商品货号</dt><dd class="opt"><?php echo $output['common_info']['goods_commonid'];?></dd></dl>
      <dl class="row">
      <dt class="tit">审核商品名称</dt><dd class="opt"><?php echo $output['common_info']['goods_name'];?></dd></dl>
    <dl class="row">
      <dt class="tit">
        <label>审核通过</label>
      </dt>
      <dd class="opt">
        <div class="onoff">
          <label for="rewrite_enabled"  class="cb-enable selected" title="<?php echo $lang['wt_yes'];?>"><?php echo $lang['wt_yes'];?></label>
          <label for="rewrite_disabled" class="cb-disable" title="<?php echo $lang['wt_no'];?>"><?php echo $lang['wt_no'];?></label>
          <input id="rewrite_enabled" name="verify_state" checked="checked" value="1" type="radio">
          <input id="rewrite_disabled" name="verify_state" value="0" type="radio">
        </div>
        <p class="notic"><?php echo $lang['open_rewrite_tips'];?></p>
      </dd>
    </dl>
    <dl class="row" wttype="reason" style="display: none">
      <dt class="tit">
        <label for="verify_reason">未通过理由</label>
      </dt>
      <dd class="opt">
        <textarea rows="6" class="tarea" cols="60" name="verify_reason" id="verify_reason"></textarea>
      </dd>
    </dl>
    <div class="bot"><a href="javascript:void(0);" class="wtap-btn-big wtap-btn-green" wttype="btn_submit"><?php echo $lang['wt_submit'];?></a></div>
  </div>
</form>
<script>
$(function(){
    $('a[wttype="btn_submit"]').click(function(){
        ajaxpost('form1', '', '', 'onerror');
    });
    $('input[name="verify_state"]').click(function(){
        if ($(this).val() == 1) {
            $('dl[wttype="reason"]').hide();
        } else {
            $('dl[wttype="reason"]').show();
        }
    });
});
</script>