<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<style type="text/css">
.color {
	position: relative!important;
	z-index: 1!important;
	padding: 0!important;
}
.evo-colorind-ie {
	position: relative;
*top:0/*IE6,7*/ !important;
}
.setcolor {
	display:inline-block;
	margin-left: 10px;
}
.input-link-show {padding-top:10px;}
.input-link-show .input-txt { width:458px;}
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['web_set'];?></h3>
        <h5><?php echo $lang['web_set_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li>网站登录页面左侧图片及背景色，每次刷新可随机显示，最多可设置上传4张。</li>
      <li>选择上传文件并提交表单生效，图片请依据输入框下提示文字内容选择。</li>
      <li>为确保显示效果美观，可设置登录页背景填充色用于弥补图片在不同分辨率下显示区域超出图片时的问题，可根据您焦点图片的基础底色作为参照进行颜色设置。</li>
    </ul>
  </div>
  <form method="post" enctype="multipart/form-data" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="old_login_pic1" value="<?php echo $output['list']['p1']['pic'];?>" />
    <input type="hidden" name="old_login_pic2" value="<?php echo $output['list']['p2']['pic'];?>" />
    <input type="hidden" name="old_login_pic3" value="<?php echo $output['list']['p3']['pic'];?>" />
    <input type="hidden" name="old_login_pic4" value="<?php echo $output['list']['p4']['pic'];?>" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="login_pic1">主题轮换图片1</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_PATH.'/login/'.$output['list']['p1']['pic']);?>"><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_PATH.'/login/'.$output['list']['p1']['pic']);?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input name="login_pic1" type="file" class="type-file-file" id="login_pic1" size="30" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效"/>
            </span></div>
            <div title="请选择背景填充色" class="opt setcolor">
            <input wttype="login_color" placeholder="请选择背景填充色" name="login_color1" value="<?php echo $output['list']['p1']['color'];?>" class="" type="text">
            <span class="err"></span>
          </div>
			<div title="输入要跳转的链接地址" class="input-link-show">
                <input value="<?php echo $output['list']['p1']['url'];?>" name="url1" placeholder="输入要跳转的链接地址" class="input-txt" type="text"> <i class="fa fa-link"></i>
              </div>
          <p class="notic">请使用990*320像素jpg/gif/png格式的图片。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="login_pic2">主题轮换图片2</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_PATH.'/login/'.$output['list']['p2']['pic']);?>"><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_PATH.'/login/'.$output['list']['p2']['pic']);?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input name="login_pic2" type="file" class="type-file-file" id="login_pic2" size="30" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效"/>
            </span></div>
            <div title="请选择背景填充色" class="opt setcolor">
            <input wttype="login_color" placeholder="请选择背景填充色" name="login_color2" value="<?php echo $output['list']['p2']['color'];?>" class="" type="text">
            <span class="err"></span>
          </div>
			<div title="输入要跳转的链接地址" class="input-link-show">
                <input value="<?php echo $output['list']['p2']['url'];?>" name="url2" placeholder="输入要跳转的链接地址" class="input-txt" type="text"> <i class="fa fa-link"></i>
              </div>
          <p class="notic">请使用990*320像素jpg/gif/png格式的图片。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="login_pic3">主题轮换图片3</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_PATH.'/login/'.$output['list']['p3']['pic']);?>"><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_PATH.'/login/'.$output['list']['p3']['pic']);?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input name="login_pic3" type="file" class="type-file-file" id="login_pic3" size="30" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效"/>
            </span></div>
            <div title="请选择背景填充色" class="opt setcolor">
            <input wttype="login_color" placeholder="请选择背景填充色" name="login_color3" value="<?php echo $output['list']['p3']['color'];?>" class="" type="text"> 
            <span class="err"></span>
          </div>
			<div title="输入要跳转的链接地址" class="input-link-show">
                <input value="<?php echo $output['list']['p3']['url'];?>" name="url3" placeholder="输入要跳转的链接地址" class="input-txt" type="text"> <i class="fa fa-link"></i>
              </div>
          <p class="notic">请使用990*320像素jpg/gif/png格式的图片。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="login_pic4">主题轮换图片4</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_PATH.'/login/'.$output['list']['p4']['pic']);?>"><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_PATH.'/login/'.$output['list']['p4']['pic']);?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input name="login_pic4" type="file" class="type-file-file" id="login_pic4" size="30" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效"/>
            </span></div>
            <div title="请选择背景填充色" class="opt setcolor">
            <input wttype="login_color" placeholder="请选择背景填充色" name="login_color4" value="<?php echo $output['list']['p4']['color'];?>" class="" type="text">
            <span class="err"></span>
          </div>
			<div title="输入要跳转的链接地址" class="input-link-show">
                <input value="<?php echo $output['list']['p4']['url'];?>" name="url4" placeholder="输入要跳转的链接地址" class="input-txt" type="text"> <i class="fa fa-link"></i>
              </div>
          <p class="notic">请使用990*320像素jpg/gif/png格式的图片。</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" onclick="document.form1.submit()"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_STATIC_URL;?>/js/jquery.nyroModal.js"></script>
<link href="<?php echo STATIC_SITE_URL;?>/js/colorpicker/evol.colorpicker.css" rel="stylesheet" type="text/css">
<script src="<?php echo STATIC_SITE_URL;?>/js/colorpicker/evol.colorpicker.min.js"></script>
<script type="text/javascript">
// 模拟网站LOGO上传input type='file'样式
$(function(){
	$('[wttype="login_color"]').colorpicker({showOn:'both'});//初始化切换大图背景颜色控件
    $('[wttype="login_color"]').parent().css("width",'');
    $('[wttype="login_color"]').parent().addClass("color");
	
    var textButton1="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />"
    var textButton2="<input type='text' name='textfield' id='textfield2' class='type-file-text' /><input type='button' name='button' id='button2' value='选择上传...' class='type-file-button' />"
    var textButton3="<input type='text' name='textfield' id='textfield3' class='type-file-text' /><input type='button' name='button' id='button3' value='选择上传...' class='type-file-button' />"
    var textButton4="<input type='text' name='textfield' id='textfield4' class='type-file-text' /><input type='button' name='button' id='button4' value='选择上传...' class='type-file-button' />"
	$(textButton1).insertBefore("#login_pic1");
	$(textButton2).insertBefore("#login_pic2");
	$(textButton3).insertBefore("#login_pic3");
	$(textButton4).insertBefore("#login_pic4");
	$("#login_pic1").change(function(){$("#textfield1").val($("#login_pic1").val());});
	$("#login_pic2").change(function(){$("#textfield2").val($("#login_pic2").val());});
	$("#login_pic3").change(function(){$("#textfield3").val($("#login_pic3").val());});
	$("#login_pic4").change(function(){$("#textfield4").val($("#login_pic4").val());});
// 上传图片类型
$('input[class="type-file-file"]').change(function(){
	var filepath=$(this).val();
	var extStart=filepath.lastIndexOf(".");
	var ext=filepath.substring(extStart,filepath.length).toUpperCase();
		if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
			alert("<?php echo $lang['default_img_wrong'];?>");
				$(this).attr('value','');
			return false;
		}
	});

$('#time_zone').attr('value','<?php echo $output['list_setting']['time_zone'];?>');
$('.nyroModal').nyroModal();
});
</script> 
