<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?w=web_channel&t=floor_list" title="返回模块列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3>频道管理 - 设置“<?php echo $output['web']['web_name'];?>”模块</h3>
        <h5>商城的频道及模块内容管理</h5>
      </div>
    </div>
  </div>
  <form id="web_form" method="post" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="channel_id" value="<?php echo $output['web']['web_id'];?>" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>模块名称</label>
        </dt>
        <dd class="opt">
          <input id="web_name" name="web_name" value="<?php echo $output['web']['web_name'];?>" class="input-txt" type="text" maxlength="20">
          <span class="err"></span>
          <p class="notic">只在后台模块列表中作为模块标识出现，在频道页不显示，最多20个字。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>模块类型</label>
        </dt>
        <dd class="opt"><?php echo $output['style_array'][$output['web']['web_page']];?><span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">显示状态</dt>
        <dd class="opt">
          <div class="onoff">
            <label for="show1" class="cb-enable <?php if($output['web']['web_show'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['wt_yes'];?>"><?php echo $lang['wt_yes'];?></label>
            <label for="show0" class="cb-disable <?php if($output['web']['web_show'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['wt_no'];?>"><?php echo $lang['wt_no'];?></label>
            <input id="show1" name="web_show" <?php if($output['web']['web_show'] == '1'){ ?>checked="checked"<?php } ?>  value="1" type="radio">
            <input id="show0" name="web_show" <?php if($output['web']['web_show'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="submitBtn"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){
	$("#submitBtn").click(function(){
        if($("#web_form").valid()){
            $("#web_form").submit();
		}
	});
	$("#web_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            web_name : {
                required : true
            }
        },
        messages : {
            web_name : {
                required : "<i class='fa fa-exclamation-bbs'></i>模块名称不能为空"
            }
        }
	});
});

</script>
