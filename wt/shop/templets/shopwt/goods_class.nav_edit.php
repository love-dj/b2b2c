<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?w=goods_class" title="返回商品分类列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['goods_class_index_class'];?> - <?php echo $lang['wt_edit'];?>“<?php echo $output['class_info']['gc_name'];?>”的分类导航</h3>
        <h5><?php echo $lang['goods_class_index_class_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li>设置前台左上侧商品分类导航的相关信息，可以设置分类前图标、分类别名、推荐分类、推荐品牌以及两张广告图片。</li>
      <li>分类导航信息设置完成后，需进入“平台->设置->更新缓存->首页及频道”操作后生效。</li>
    </ul>
  </div>
  <form id="goods_class_form" name="goodsClassForm" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="gc_id" value="<?php echo $output['class_info']['gc_id'];?>" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label class="cn_name" for="cn_name">分类别名</label>
        </dt>
        <dd class="opt">
          <input type="text" maxlength="20" value="<?php echo $output['nav_info']['cn_alias'];?>" name="cn_alias" id="cn_alias" class="input-txt">
          <span class="err"></span>
          <p class="notic">可选项。设置别名后，别名将会替代原分类名称展示在分类导航菜单列表中。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="pic"><?php echo '分类图片';?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo $output['nav_info']['cn_pic'];?>"> <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo $output['nav_info']['cn_pic'];?>>')" onMouseOut="toolTip()"></i> </a></span><span class="type-file-box">
            <input class="type-file-file" id="pic" name="pic" type="file" size="30" wt_type="change_pic" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            <input type="text" name="textfield" id="textfield1" class="type-file-text" />
            <input type="button" name="button" id="button1" value="选择上传..." class="type-file-button" />
            </span></div>
          <p class="notic">建议使用16*16像素PNG透明背景图片</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>推荐分类</label>
        </dt>
        <dd class="opt">
          <div> 分类下的三级分类 <a class="wtap-btn" wttype="class_hide" href="javascript:void(0);">隐藏未选项</a></div>
          <div id="class_div" class="scrollbar-box">
            <div class="wtap-type-spec-list">
              <?php if (!empty($output['third_class'])) {?>
              <?php foreach ($output['third_class'] as $key => $val) {?>
              <dl>
                <dt id="class_dt_<?php echo $key;?>"><?php echo $val['name'];?></dt>
                <?php if (!empty($val['class'])) {?>
                <dd>
                  <?php foreach ($val['class'] as $k => $v) {?>
                  <label for="class_<?php echo $k;?>">
                    <input type="checkbox" name="class_id[]" value="<?php echo $k;?>" <?php if(in_array($k, $output['nav_info']['cn_classids'])){ echo 'checked="checked"';}?>>
                    <?php echo $v;?> </label>
                  <?php }?>
                </dd>
                <?php }?>
              </dl>
              <?php }?>
              <?php }?>
            </div>
          </div>
          <p class="notic">推荐分类将在展开后的二、三级导航列表上方突出显示，建议根据分类名称长度控制选择数量不超过8个以确保展示效果。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>推荐品牌</label>
        </dt>
        <dd class="opt">
          <div id="brandcategory">快捷定位
            <select class="class-select">
              <option value="0"><?php echo $lang['wt_please_choose'];?></option>
              <?php if(!empty($output['gc_list'])){ ?>
              <?php foreach($output['gc_list'] as $k => $v){ ?>
              <?php if ($v['gc_parent_id'] == 0) {?>
              <option value="<?php echo $v['gc_id'];?>"><?php echo $v['gc_name'];?></option>
              <?php } ?>
              <?php } ?>
              <?php } ?>
            </select>
            分类下对应的品牌 <a class="wtap-btn" wttype="brand_hide" href="javascript:void(0);">隐藏未选项</a></div>
          <div id="brand_div" class="scrollbar-box">
            <div class="wtap-type-spec-list">
              <?php if(is_array($output['brand_list']) && !empty($output['brand_list'])) {?>
              <?php foreach ($output['brand_list'] as $k=>$val){?>
              <dl>
                <dt id="brand_dt_<?php echo $k;?>"><?php echo $val['name'];?></dt>
                <?php if($val['brand']) {?>
                <dd>
                  <?php foreach ($val['brand'] as $bval){?>
                  <label for="brand_<?php echo $bval['brand_id'];?>">
                    <input type="checkbox" name="brand_id[]" value="<?php echo $bval['brand_id']?>" <?php if(in_array($bval['brand_id'], $output['nav_info']['cn_brandids'])){ echo 'checked="checked"';}?> id="brand_<?php echo $bval['brand_id'];?>" />
                    <?php echo $bval['brand_name']?></label>
                  <?php }?>
                </dd>
                <?php }?>
              </dl>
              <?php }?>
              <?php }else{?>
              <div><?php echo $lang['type_add_brand_null_one'];?><a href="JavaScript:void(0);" onclick="window.parent.openItem('shop|brand')"><?php echo $lang['wt_brand_manage'];?></a><?php echo $lang['type_add_brand_null_two']?></div>
              <?php }?>
            </div>
          </div>
          <p class="notic">推荐品牌将在展开后的二、三级导航列表右侧突出显示，建议选择数量为8个具有图片的品牌，超过将被隐藏。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="pic">广告1图</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo $output['nav_info']['cn_show1'];?>"> <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo $output['nav_info']['cn_show1'];?>>')" onMouseOut="toolTip()"></i> </a></span><span class="type-file-box">
            <input class="type-file-file" id="show1" name="show1" type="file" size="30" wt_type="change_pic" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            <input type="text" name="textfield" id="textfield2" class="type-file-text" />
            <input type="button" name="button" id="button1" value="选择上传..." class="type-file-button" />
            </span></div>
          <label title="分类导航广告图1-跳转链接" for="cn_show1_link" class="ml5"><i class="fa fa-link"></i>
            <input type="text" value="<?php echo $output['nav_info']['cn_show1_link'];?>" name="cn_show1_link" id="cn_show1_link" class="input-txt ml5">
          </label>
          <p class="notic">广告图片将展示在推荐品牌下方，请使用宽度190像素，高度150像素的jpg/gif/png格式图片作为分类导航广告上传，<br/>
            如需跳转请在后方添加以http://开头的链接地址。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="pic">广告2图</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo $output['nav_info']['cn_show2'];?>"> <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo $output['nav_info']['cn_show2'];?>>')" onMouseOut="toolTip()"></i> </a></span><span class="type-file-box">
            <input class="type-file-file" id="show2" name="show2" type="file" size="30" wt_type="change_pic" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            <input type="text" name="textfield" id="textfield3" class="type-file-text" />
            <input type="button" name="button" id="button1" value="选择上传..." class="type-file-button" />
            </span></div>
          <label for="cn_show2_link" title="分类导航广告图2-跳转链接" class="ml5"><i class="fa fa-link"></i>
            <input type="text" value="<?php echo $output['nav_info']['cn_show2_link'];?>" name="cn_show2_link" id="cn_show2_link" class="input-txt ml5">
          </label>
          <p class="notic">广告图片将展示在推荐品牌下方，请使用宽度190像素，高度150像素的jpg/gif/png格式图片作为分类导航广告上传，<br/>
            如需跳转请在后方添加以http://开头的链接地址。</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="submitBtn"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/perfect-scrollbar.min.js"></script> 
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.mousewheel.js"></script> 
<script type="text/javascript" src="<?php echo ADMIN_STATIC_URL;?>/js/jquery.nyroModal.js"></script> 
<script>
$(function(){
//自动加载滚动条
    $('#class_div').perfectScrollbar();
    $('#brand_div').perfectScrollbar();

// 点击查看图片
	$('.nyroModal').nyroModal();
	//按钮先执行验证再提交表单
	$("#submitBtn").click(function(){
	    if($("#goods_class_form").valid()){
	     $("#goods_class_form").submit();
		}
	});

	$("#pic").change(function(){
		$("#textfield1").val($(this).val());
	});
	$("#show1").change(function(){
		$("#textfield2").val($(this).val());
	});
	$("#show2").change(function(){
		$("#textfield3").val($(this).val());
	});

    // 类型搜索
    $("#brandcategory > select").live('change',function(){
        brand_scroll($(this));
    });

    // 品牌隐藏未选项
    $('a[wttype="brand_hide"]').live('click',function(){
        checked_hide('brand');
    });
    // 品牌全部显示
    $('a[wttype="brand_show"]').live('click',function(){
        checked_show('brand');
    });
    // 子级分类隐藏未选项
    $('a[wttype="class_hide"]').live('click',function(){
        checked_hide('class');
    });
    // 子级分类全部显示
    $('a[wttype="class_show"]').live('click',function(){
        checked_show('class');
    });
});
var brandScroll = 0;
function brand_scroll(o){
    var id = o.val();
    if(!$('#brand_dt_'+id).is('dt')){
        return false;
    }
    $('#brand_div').scrollTop(-brandScroll);
    var sp_top = $('#brand_dt_'+id).offset().top;
    var div_top = $('#brand_div').offset().top;
    $('#brand_div').scrollTop(sp_top-div_top);
    brandScroll = sp_top-div_top;
}


//隐藏未选项
function checked_show(str) {
$('#'+str+'_div').find('dt').show().end().find('label').show();
$('#'+str+'_div').find('dl').show();
$('a[wttype="'+str+'_show"]').attr('wttype',str+'_hide').html('隐藏未选项');
$('#'+str+'_div').perfectScrollbar('destroy').perfectScrollbar();
}

//显示全部选项
function checked_hide(str) {
$('#'+str+'_div').find('dt').hide();
$('#'+str+'_div').find('input[type="checkbox"]').parents('label').hide();
$('#'+str+'_div').find('input[type="checkbox"]:checked').parents('label').show();
$('#'+str+'_div').find('dl').each(function(){
    if ($(this).find('input[type="checkbox"]:checked').length == 0 ) $(this).hide();
});
$('a[wttype="'+str+'_hide"]').attr('wttype',str+'_show').html('显示未选项');
$('#'+str+'_div').perfectScrollbar('destroy').perfectScrollbar();
}
gcategoryInit('brandcategory');
</script> 
