<script type="text/javascript">
$(document).ready(function(){

    $("#submit").click(function(){
        $("#add_form").submit();
    });

});
</script>
<div class="page">
  <div class="fixed-bar">
      <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_wechat_setting'];?></h3>
        <h5>设置微信公众号基本信息</h5>
      </div>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="add_form" method="post"  enctype="multipart/form-data">
  <input type="hidden" name="form_submit" value="ok" />
  <input type="hidden" name="wid" value="<?php echo $output['api_account']['wechat_id'];?>" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="wechat_isuse"><?php echo  $lang['wechat_isuse'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
           	<label for="isuse_1" class="cb-enable <?php if($output['setting']['wechat_isuse'] == '1'){ ?>selected<?php } ?>" title="开启"><span>开启</span></label>
            <label for="isuse_0" class="cb-disable <?php if($output['setting']['wechat_isuse'] == '0'){ ?>selected<?php } ?>" title="关闭"><span>关闭</span></label>
            <input type="radio" id="isuse_1" name="isuse" value="1" <?php echo $output['setting']['wechat_isuse']==1?'checked=checked':''; ?>>
            <input type="radio" id="isuse_0" name="isuse" value="0" <?php echo $output['setting']['wechat_isuse']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"><?php echo $lang['wechat_isuse_explain'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="sharetitle">自定义分享标题</label>
        </dt>
        <dd class="opt">
		  	<input id="sharetitle" name="sharetitle" value="<?php echo $output['api_account']['wechat_share_title'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="_pic">自定义分享图标</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show">
            <?php if(empty($output['api_account']['wechat_share_logo'])) { ?>
            <a class="nyroModal" rel="gal" href="<?php echo ADMIN_TEMPLATES_URL.'/images/preview.png';?>"> <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo ADMIN_TEMPLATES_URL.'/images/preview.png';?>>')" onMouseOut="toolTip()"></i></a>
            <?php } else { ?>
            <a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.DS.'wxshare'.DS.$output['api_account']['wechat_share_logo'];?>"> <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.DS.'wxshare'.DS.$output['api_account']['wechat_share_logo'];?>>')" onMouseOut="toolTip()"></i> </a>
            <?php } ?>
            </span> <span class="type-file-box">
           <input type='text' name='thumb' id='thumb' value="" class='type-file-text' />
			<input type='button' name='button' id='button1' value='请选择图片' class='type-file-button' />
			<input name="_pic" type="file" class="type-file-file" id="_pic" size="30" hidefocus="true">
            </span></div>
          <span class="err"></span>
          <p class="notic">最佳显示尺寸为100*100像素</p>
        </dd>
      </dl>
	   <dl class="row">
        <dt class="tit">
          <label for="what_style">自定义分享简介</label>
        </dt>
        <dd class="opt">
        	<textarea name="sharedesc" rows="6" class="tarea" id="sharedesc" ><?php echo $output['api_account']['wechat_share_desc'];?></textarea>
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
	$("#_pic").change(function(){
        $("#thumb").val($("#_pic").val());
    });
});
</script> 