<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_bbs_setting'];?></h3>
        <h5><?php echo $lang['wt_bbs_setting_subhead'];?></h5>
      </div>
      <ul class="tab-base wt-row">
        <li><a href="JavaScript:void(0);" class="current"><?php echo $lang['wt_bbs_setting'];?></a></li>
        <li><a href="index.php?w=bbs_setting&t=seo"><?php echo $lang['bbs_setting_seo'];?></a></li>
        <li><a href="index.php?w=bbs_setting&t=sec"><?php echo $lang['bbs_setting_sec'];?></a></li>
        <li><a href="index.php?w=bbs_setting&t=exp"><?php echo $lang['bbs_setting_exp'];?></a></li>
        <li><a href="index.php?w=bbs_setting&t=super_list">超级管理员</span></a></li>
      </ul>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['bbs_setting_prompts_one'];?></li>
      <li><?php echo $lang['bbs_setting_prompts_two'];?></li>
      <li><?php echo $lang['bbs_setting_prompts_three'];?></li>
    </ul>
  </div>
  <form id="bbs_form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="old_c_logo" value="<?php echo $output['list_setting']['bbs_logo'];?>" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="c_isuse"> <?php echo $lang['bbs_setting_isuse'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="c_isuse1" class="cb-enable <?php if($output['list_setting']['bbs_isuse'] == 1) echo 'selected';?>" ><?php echo $lang['open'];?></label>
            <label for="c_isuse0" class="cb-disable <?php if($output['list_setting']['bbs_isuse'] == 0) echo 'selected';?>" ><?php echo $lang['close'];?></label>
            <input id="c_isuse1" name="c_isuse" <?php if($output['list_setting']['bbs_isuse'] == 1) echo 'checked="checked"';?> value="1" type="radio">
            <input id="c_isuse0" name="c_isuse" <?php if($output['list_setting']['bbs_isuse'] == 0) echo 'checked="checked"';?> value="0" type="radio">
          </div>
          <p class="notic"><?php echo $lang['bbs_setting_isuse_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="c_name"> <?php echo $lang['bbs_setting_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="c_name" id="c_name" class="input-txt" value="<?php echo $output['list_setting']['bbs_name'];?>">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['bbs_setting_name_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label> <?php echo $lang['bbs_setting_logo'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_BBS.DS.$output['list_setting']['bbs_logo'];?>"/><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.ATTACH_BBS.DS.$output['list_setting']['bbs_logo'];?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input type='text' name='textfield' id='textfield' class='type-file-text' />
            <input type='button' name='button' id='button' value='选择上传...' class='type-file-button' />
            <input name="c_logo" type="file" class="type-file-file" id="c_logo" size="30" hidefocus="true"  title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效"/>
            </span></div>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['bbs_setting_logo_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="c_iscreate"> <?php echo $lang['bbs_setting_iscreate'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="c_iscreate1" class="cb-enable <?php if($output['list_setting']['bbs_iscreate'] == 1) echo 'selected';?>"><?php echo $lang['open'];?></label>
            <label for="c_iscreate0" class="cb-disable <?php if($output['list_setting']['bbs_iscreate'] == 0) echo 'selected';?>"><?php echo $lang['close'];?></label>
            <input id="c_iscreate1" name="c_iscreate" <?php if($output['list_setting']['bbs_iscreate'] == 1) echo 'checked="checked"';?> value="1" type="radio" />
            <input id="c_iscreate0" name="c_iscreate" <?php if($output['list_setting']['bbs_iscreate'] == 0) echo 'checked="checked"';?> value="0" type="radio" />
          </div>
          <p class="notic"><?php echo $lang['bbs_setting_iscreate_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="c_istalk"> <?php echo $lang['bbs_setting_istalk'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="c_istalk1" class="cb-enable <?php if($output['list_setting']['bbs_istalk'] == 1) echo 'selected';?>"><?php echo $lang['open'];?></label>
            <label for="c_istalk0" class="cb-disable <?php if($output['list_setting']['bbs_istalk'] == 0) echo 'selected';?>"><?php echo $lang['close'];?></label>
            <input id="c_istalk1" name="c_istalk" <?php if($output['list_setting']['bbs_istalk'] == 1) echo 'checked="checked"';?> value="1" type="radio" />
            <input id="c_istalk0" name="c_istalk" <?php if($output['list_setting']['bbs_istalk'] == 0) echo 'checked="checked"';?> value="0" type="radio" />
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="c_createsum"> <?php echo $lang['bbs_setting_create_sum'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="c_createsum" id="c_createsum" class="input-txt" value="<?php echo $output['list_setting']['bbs_createsum'];?>">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['bbs_setting_create_sum_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="c_joinsum"> <?php echo $lang['bbs_setting_join_sum'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="c_joinsum" id="c_joinsum" class="input-txt" value="<?php echo $output['list_setting']['bbs_joinsum'];?>">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['bbs_setting_join_sum_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label> <?php echo $lang['bbs_setting_manage_sum'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="c_managesum" id="c_managesum" class="input-txt" value="<?php echo $output['list_setting']['bbs_managesum'];?>" />
          <span class="err"></span>
          <p class="notic"><?php echo $lang['bbs_setting_manage_sum_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="c_wordfilter"> <?php echo $lang['bbs_setting_wordfilter'];?></label>
        </dt>
        <dd class="opt">
          <textarea class="tarea" rows="6" name="c_wordfilter" id="c_wordfilter"><?php echo $output['list_setting']['bbs_wordfilter'];?></textarea>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['bbs_setting_wordfilter_tips'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="submitBtn"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_STATIC_URL;?>/js/jquery.edit.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo ADMIN_STATIC_URL;?>/js/jquery.nyroModal.js"></script>

<script>
//按钮先执行验证再提交表单
$(function(){
	// 图片js
	$("#c_logo").change(function(){$("#textfield").val($("#c_logo").val());});
	
	$('.nyroModal').nyroModal();// 点击查看图片

	$("#submitBtn").click(function(){
		$("#bbs_form").submit();
	});
	
});
</script> 
