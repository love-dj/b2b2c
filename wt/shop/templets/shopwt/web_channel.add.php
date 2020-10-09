<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?w=web_channel" title="返回频道列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3>频道管理 - 新增频道</a></h3>
        <h5>商城的频道及模块内容管理</h5>
      </div>
    </div>
  </div>
  <form id="web_form" method="post" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="channel_name"><em>*</em>频道名称</label>
        </dt>
        <dd class="opt">
          <input id="channel_name" name="channel_name" value="" class="input-txt" type="text" maxlength="20">
          <span class="err"></span>
          <p class="notic">所填文字会在浏览器标题栏显示，在页面中程序会自动加上网站名称，最多20个字。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>颜色风格</label>
        </dt>
        <dd class="opt">
          <input type="hidden" value="default" name="channel_style" id="channel_style">
          <ul class="home-templates-board-style">
            <li class="red"><em></em><i class="fa fa-check-bbs"></i><?php echo $lang['page_config_style_red'];?></li>
            <li class="pink"><em></em><i class="fa fa-check-bbs"></i><?php echo $lang['page_config_style_pink'];?></li>
            <li class="orange"><em></em><i class="fa fa-check-bbs"></i><?php echo $lang['page_config_style_orange'];?></li>
            <li class="green"><em></em><i class="fa fa-check-bbs"></i><?php echo $lang['page_config_style_green'];?></li>
            <li class="blue"><em></em><i class="fa fa-check-bbs"></i><?php echo $lang['page_config_style_blue'];?></li>
            <li class="purple"><em></em><i class="fa fa-check-bbs"></i><?php echo $lang['page_config_style_purple'];?></li>
            <li class="brown"><em></em><i class="fa fa-check-bbs"></i><?php echo $lang['page_config_style_brown'];?></li>
            <li class="default selected"><em></em><i class="fa fa-check-bbs"></i><?php echo $lang['page_config_style_default'];?></li>
          </ul>
          <span class="err"></span>
          <p class="notic">选择该频道的整体风格，与页面中的CSS配合使用。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="keywords">SEO关键字</label>
        </dt>
        <dd class="opt">
          <input id="keywords" name="keywords" value="" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="description">SEO描述</label>
        </dt>
        <dd class="opt">
          <textarea name="description" rows="6" class="tarea" id="description" ></textarea>
          <span class="err"></span>
          <p class="notic"><span class="vatop rowform"></span></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">启用状态</dt>
        <dd class="opt">
          <div class="onoff">
            <label for="show1" class="cb-enable selected" title="<?php echo $lang['wt_yes'];?>"><?php echo $lang['wt_yes'];?></label>
            <label for="show0" class="cb-disable" title="<?php echo $lang['wt_no'];?>"><?php echo $lang['wt_no'];?></label>
            <input id="show1" name="channel_show" checked="checked" value="1" type="radio">
            <input id="show0" name="channel_show" value="0" type="radio">
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
	$(".home-templates-board-style li").click(function(){
        $(".home-templates-board-style li").removeClass("selected");
        $("#channel_style").val($(this).attr("class"));
        $(this).addClass("selected");
	});
	$("#web_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            channel_name : {
                required : true
            }
        },
        messages : {
            channel_name : {
                required : "<i class='fa fa-exclamation-bbs'></i>频道名称不能为空"
            }
        }
	});
});

</script>
