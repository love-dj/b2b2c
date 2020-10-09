<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_bbs_setting'];?></h3>
        <h5><?php echo $lang['wt_bbs_setting_subhead'];?></h5>
      </div>
      <ul class="tab-base wt-row">
        <li><a href="index.php?w=bbs_setting"><?php echo $lang['wt_bbs_setting'];?></a></li>
        <li><a href="JavaScript:void(0);" class="current"><?php echo $lang['bbs_setting_seo'];?></a></li>
        <li><a href="index.php?w=bbs_setting&t=sec"><?php echo $lang['bbs_setting_sec'];?></a></li>
        <li><a href="index.php?w=bbs_setting&t=exp"><?php echo $lang['bbs_setting_exp'];?></a></li>
        <li><a href="index.php?w=bbs_setting&t=super_list">超级管理员</a></li>
      </ul>
    </div>
  </div>
  <form id="bbs_form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="old_c_logo" value="<?php echo $output['list_setting']['bbs_logo'];?>" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="c_seotitle">SEO title</label>
        </dt>
        <dd class="opt">
          <input type="text" name="c_seotitle" id="c_seotitle" class="input-txt" value="<?php echo $output['list_setting']['bbs_seotitle'];?>" />
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="c_seokeywords">SEO keywords</label>
        </dt>
        <dd class="opt">
          <textarea class="tarea" rows="6" name="c_seokeywords" id="c_seokeywords"><?php echo $output['list_setting']['bbs_seokeywords'];?></textarea>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="c_seodescription">SEO description</label>
        </dt>
        <dd class="opt">
          <textarea class="tarea" rows="6" name="c_seodescription" id="c_seodescription"><?php echo $output['list_setting']['bbs_seodescription'];?></textarea>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="submitBtn"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_STATIC_URL;?>/js/jquery.edit.js" charset="utf-8"></script> 
<script>
//按钮先执行验证再提交表单
$(function(){
	$("#submitBtn").click(function(){
		$("#bbs_form").submit();
	});
});
</script> 
