<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?w=web_channel&t=floor_list" title="返回模块列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3>频道管理 - 新增模块</a></h3>
        <h5>商城的频道及模块内容管理</h5>
      </div>
    </div>
  </div>
  <form id="web_form" method="post" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>模块名称</label>
        </dt>
        <dd class="opt">
          <input id="web_name" name="web_name" value="" class="input-txt" type="text" maxlength="20">
          <span class="err"></span>
          <p class="notic">只在后台模块列表中作为模块标识出现，在频道页不显示，最多20个字。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="web_page"><em>*</em>模块类型</label>
        </dt>
        <dd class="opt">
          <select name="web_page" id="web_page">
            <option value=""><?php echo $lang['wt_please_choose'];?></option>
            <?php if(!empty($output['style_array']) && is_array($output['style_array'])){ ?>
            <?php foreach($output['style_array'] as $key => $val){ ?>
            <option value="<?php echo $key;?>"><?php echo $val;?></option>
            <?php } ?>
            <?php } ?>
          </select>
          <span class="err"></span>
          <p class="notic">由于不同模块调用的初始化数据不同，选择类型保存后不可修改。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">启用状态</dt>
        <dd class="opt">
          <div class="onoff">
            <label for="show1" class="cb-enable selected" title="<?php echo $lang['wt_yes'];?>"><?php echo $lang['wt_yes'];?></label>
            <label for="show0" class="cb-disable" title="<?php echo $lang['wt_no'];?>"><?php echo $lang['wt_no'];?></label>
            <input id="show1" name="web_show" checked="checked" value="1" type="radio">
            <input id="show0" name="web_show" value="0" type="radio">
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
            },
            web_page : {
                required : true
            }
        },
        messages : {
            web_name : {
                required : "<i class='fa fa-exclamation-bbs'></i>模块名称不能为空"
            },
            web_page : {
                required : "<i class='fa fa-exclamation-bbs'></i>请选择模块类型"
            }
        }
	});
});

</script>
