<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?w=help_store&t=help_store" title="返回列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3>店铺帮助 - <?php echo $lang['wt_edit'];?>”<?php echo $output['help']['help_title']?>“</h3>
        <h5>商品店铺帮助类型与文章管理</h5>
      </div>
    </div>
  </div>
  <form id="post_form" method="post" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="help_title"><em>*</em>帮助标题</label>
        </dt>
        <dd class="opt">
          <input id="help_title" name="help_title" value="<?php echo $output['help']['help_title']?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="type_id"><em>*</em>帮助类型</label>
        </dt>
        <dd class="opt">
          <select name="type_id" id="type_id">
            <option value=""><?php echo $lang['wt_please_choose'];?></option>
            <?php if(!empty($output['type_list']) && is_array($output['type_list'])){ ?>
            <?php foreach($output['type_list'] as $key => $val){ ?>
            <option <?php if($val['type_id'] == $output['help']['type_id']){?>selected<?php }?> value="<?php echo $val['type_id'];?>"><?php echo $val['type_name'];?></option>
            <?php } ?>
            <?php } ?>
          </select>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="help_sort"><em>*</em><?php echo $lang['wt_sort'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['help']['help_sort']?>" name="help_sort" id="help_sort" class="input-txt">
          <span class="err"></span>
          <p class="notic">数字范围为0~255，数字越小越靠前</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="help_url"> 链接地址</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['help']['help_url']?>" name="help_url" id="help_url" class="input-txt">
          <span class="err"></span>
          <p class="notic">当填写"链接"后点击标题将直接跳转至链接地址，不显示内容。链接格式请以http://开头</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>帮助内容</label>
        </dt>
        <dd class="opt">
          <?php showEditor('content',$output['help']['help_info']);?>
          </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">图片上传</dt>
        <dd class="opt">
          <div class="input-file-show" id="divComUploadContainer"><span class="type-file-box">
            <input class="type-file-file" id="fileupload" name="fileupload" type="file" size="30" multiple hidefocus="true" title="点击按钮选择文件上传">
            <input type="text" name="text" id="text" class="type-file-text" />
            <input type="button" name="button" id="button" value="选择上传..." class="type-file-button" />
            </span></div>
          <div id="thumbnails" class="wtap-thumb-list">
            <h5><i class="fa fa-exclamation-bbs"></i>上传后的图片可以插入到富文本编辑器中使用，无用附件请手动删除，如不处理系统会始终保存该附件图片。</h5>
            <ul>
              <?php if(!empty($output['pic_list']) && is_array($output['pic_list'])){?>
              <?php foreach($output['pic_list'] as $key => $val){ ?>
              <li id="pic_<?php echo $val['upload_id'];?>">
                <input type="hidden" name="file_id[]" value="<?php echo $v['upload_id'];?>" />
                <div class="thumb-list-pics"><a href="javascript:void(0);"><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_ARTICLE.'/'.$val['file_name'];?>" alt="<?php echo $v['file_name'];?>"/></a></div>
                <a href="javascript:del_file_upload('<?php echo $val['upload_id'];?>');" class="del" title="<?php echo $lang['wt_del'];?>">X</a><a href="javascript:insert_editor('<?php echo $val['file_name'];?>');" class="inset"><i class="fa fa-clipboard"></i>插入图片</a> </li>
              <?php } ?>
              <?php } ?>
            </ul>
          </div>
        </dd>
      </dl>
      <div class="bot"> <a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="submitBtn"><?php echo $lang['wt_submit'];?></a> </div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script> 
<script>
var UPLOAD_ARTICLE_URL = "<?php echo UPLOAD_SITE_URL.'/'.ATTACH_ARTICLE.'/'; ?>";
//按钮先执行验证再提交表单
$(function(){
	$("#submitBtn").click(function(){
        if($("#post_form").valid()){
            $("#post_form").submit();
    	}
	});
	$("#post_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            help_title : {
                required : true
            },
            type_id : {
                required : true
            },
            help_sort : {
                required : true,
                digits   : true
            },
			help_url : {
				url : true
            },
			content : {
                required   : true
            }
        },
        messages : {
            help_title : {
                required : "<i class='fa fa-exclamation-bbs'></i>类型名称不能为空"
            },
            type_id : {
                required : "<i class='fa fa-exclamation-bbs'></i>请选择帮助类型"
            },
            help_sort  : {
                required : "<i class='fa fa-exclamation-bbs'></i>排序仅可以为数字",
                digits   : "<i class='fa fa-exclamation-bbs'></i>排序仅可以为数字"
            },
            help_url : {
                url : "<i class='fa fa-exclamation-bbs'></i>链接格式不正确"
            },
            content : {
                required : "<i class='fa fa-exclamation-bbs'></i>帮助内容不能为空"
            }
        }
	});
    // 图片上传
    $('#fileupload').each(function(){
        $(this).fileupload({
            dataType: 'json',
            url: 'index.php?w=help_store&t=upload_pic&item_id=<?php echo $output['help']['help_id']?>',
            done: function (e,data) {
                if(data != 'error'){
                	add_uploadedfile(data.result);
                }
            }
        });
    });
});

function add_uploadedfile(file){
    var newImg = '<li id="pic_' + file.file_id + '"><input type="hidden" name="file_id[]" value="' + file.file_id + '" /><div class="thumb-list-pics"><a href="javascript:void(0);"><img src="'+UPLOAD_ARTICLE_URL + file.file_name + '" /></a></div><a href="javascript:del_file_upload(' + file.file_id + ');" class="del" title="<?php echo $lang['wt_del'];?>">X</a><a href="javascript:insert_editor(\'' + file.file_name + '\');" class="inset"><i class="fa fa-clipboard"></i>插入图片</a></li>';
    $('#thumbnails > ul').prepend(newImg);
}
function insert_editor(file_name){
	KE.appendHtml('content', '<img src="'+UPLOAD_ARTICLE_URL+ file_name + '">');
}
function del_file_upload(file_id){
    if(!window.confirm('<?php echo $lang['wt_ensure_del'];?>')){
        return;
    }
    $.getJSON('index.php?w=help_store&t=del_pic&file_id=' + file_id, function(result){
        if(result){
            $('#pic_' + file_id).remove();
        }
    });
}
</script> 
