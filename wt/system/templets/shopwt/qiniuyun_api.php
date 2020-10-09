<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
            <h3>七牛云接口</h3>
            <h5>七牛云开放平台账号设置</h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li>七牛云</li>
	  <li>七牛云</li>
	  <li>七牛云</li>
    </ul>
  </div>
  <form id="add_form" method="post" action="index.php?w=qiniuyun_api&t=qiniuyun_api_save">
    <div class="wtap-form-default">
      <!-- 七牛云接口开关 -->
      <dl class="row">
        <dt class="tit">
          <label>七牛云接口开关</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="qiniuyun_isuse_1" class="cb-enable <?php if($output['setting']['qiniuyun_api_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['wt_open'];?>"><?php echo $lang['wt_open'];?></label>
            <label for="qiniuyun_isuse_0" class="cb-disable <?php if($output['setting']['qiniuyun_api_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['wt_close'];?>"><?php echo $lang['wt_close'];?></label>
            <input type="radio" id="qiniuyun_isuse_1" name="qiniuyun_api_isuse" value="1" <?php echo $output['setting']['qiniuyun_api_isuse']==1?'checked=checked':''; ?>>
            <input type="radio" id="qiniuyun_isuse_0" name="qiniuyun_api_isuse" value="0" <?php echo $output['setting']['qiniuyun_api_isuse']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="qiniuyun_app_key">七牛云应用标识(AK)</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['setting']['qiniuyun_app_key'];?>" name="qiniuyun_app_key" class="input-txt">
          <span class="err"></span>
          <p class="notic"><a class="wtap-btn" target="_blank" href="https://developer.qiniu.com">立即在线申请</a></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="qiniuyun_secret_key">七牛云应用密钥(SK)</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['setting']['qiniuyun_secret_key'];?>" name="qiniuyun_secret_key" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
        <dl class="row">
            <dt class="tit">
                <label for="bucket_name">七牛云应用存储空间(BN)</label>
            </dt>
            <dd class="opt">
                <input type="text" value="<?php echo $output['setting']['bucket_name'];?>" name="bucket_name" class="input-txt">
                <span class="err"></span>
                <p class="notic"></p>
            </dd>
        </dl>
      <div class="bot"><a id="submit" href="javascript:void(0)" class="wtap-btn-big wtap-btn-green"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $("#submit").click(function(){
        $("#add_form").submit();
    });
});
</script> 
