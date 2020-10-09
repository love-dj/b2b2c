<style type="text/css">
h3.dialog_head {
	margin: 0 !important;
}
.dialog_content {
	width: 900px;
	padding-top:10px;
	padding: 10px 15px 15px 15px !important;
	overflow: hidden;
}
</style>
<link type="text/css" href="<?php echo ADMIN_TEMPLATES_URL?>/css/weixin/material.css" rel="stylesheet" />
<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?w=keyword" title="返回列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['wt_wechat_keywords'];?> - 添加</h3>
        <h5>设置关键词</h5>
      </div>
    </div>
  </div>
	<!-- 操作说明 -->
<!--	<div class="explanation" id="explanation">
		<div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
			<h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
			<span id="explanationZoom" title="收起提示"></span> </div>
		<ul>
			<li>设置关键词</li>
		</ul>
	</div>-->
  <form id="add_form" method="post">
  <input type="hidden" name="form_submit" value="ok" />
  <input type="hidden" name="materialid" id="materialid" value="0" />
	<div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="keywords"><?php echo $lang['wechat_keywords']; ?></label>
        </dt>
        <dd class="opt">
         <input class="input-txt" name="keywords" id="keywords" value="" type="text">
          <p class="notic"><?php echo $lang['wechat_keywords_notice']?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="msgtype"><?php echo $lang['reply_type']; ?></label>
        </dt>
        <dd class="opt">
          <?php foreach($lang['reply_type_name'] as $key=>$value){?>
           <?php if($key<2){?>
           	<input type="radio" name="msgtype" value="<?php echo $key;?>" id="msgtype_<?php echo $key;?>"<?php echo $key==0 ? ' checked' : '';?> /><label for="msgtype_<?php echo $key;?>"><?php echo $value;?></label>&nbsp;&nbsp;
           <?php }?>
           <?php }?>
          <p class="notic"></p>
        </dd>
      </dl>
		
		
      <dl class="row msgtype_0">
        <dt class="tit">
          <label for="textcontents"><?php echo $lang['reply_content']; ?></label>
        </dt>
        <dd class="opt">
         <textarea name="textcontents" id="textcontents" class="tarea"></textarea>
          <p class="notic"></p>
        </dd>
      </dl>
		
		
	<dl class="row msgtype_1" style="display: none">
        <dt class="tit">
          <label for="textcontents"><?php echo $lang['reply_material']; ?></label>
        </dt>
        <dd class="opt">
         [<a href="JavaScript:show_dialog('material_list');" style="color:#0099D8"><?php echo $lang['material_select_btn'];?></a>]
            <div id="material_confirm" class="material_dialog" style="display:none">
              <div class="list">
            	<div class="item"></div>
              </div>
            </div>
          <p class="notic"></p>
        </dd>
      </dl>
		
      <dl class="row">
        <dt class="tit">
          <label for="patternmethod"><?php echo $lang['reply_pattern_type']; ?></label>
        </dt>
        <dd class="opt">
         <?php foreach($lang['reply_pattern_type_name'] as $kk=>$vv){?>
           	<input type="radio" name="patternmethod" value="<?php echo $kk;?>" id="patternmethod_<?php echo $kk;?>"<?php echo $kk==0 ? ' checked' : '';?> /><label for="patternmethod_<?php echo $kk;?>"><?php echo $vv;?></label>&nbsp;&nbsp;<span style="color:#999"><?php echo $lang['wechat_patternmethod_notice'][$kk];?></span><br />
          <?php }?>
          <p class="notic"></p>
        </dd>
      </dl>
	 <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="submitBtn"><?php echo $lang['wt_submit'];?></a></div> 
  </form>
</div>

<div id="material_list_dialog" style="display:none;">
  <div class="dialog-show-box">
    <table class="tb-type1 noborder search" style="margin-top:8px;">
      <tbody>
        <tr>
          <td>
          	<select name="material_type" id="material_type">
          	  <option value="0">全部</option>
              <?php foreach($lang['material_type'] as $tid=>$tname){?>
              <option value="<?php echo $tid;?>" ><?php echo $tname;?></option>
              <?php }?>
            </select>
          </td>
          <td>
          <a href="JavaScript:void(0);" onclick="get_material_list();" class="btn-search " title="查询">查询</a></td>
        </tr>
      </tbody>
    </table>
    <div id="show_material_list"></div>
    <div class="clear"></div>
  </div>
  <div class="clear"></div>
</div>

<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.ajaxContent.pack.js"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/common_select.js"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/waypoints.js"></script>

<script type="text/javascript">
$(function(){
	$('input[name=msgtype]').click(function(){
		$('.msgtype_0').hide();
		$('.msgtype_1').hide();
		$('.msgtype_2').hide();
		$('.msgtype_'+$(this).val()).show();
	});
	 
	$("#submitBtn").click(function(){
		if($("#add_form").valid()){
			$("#add_form").submit();
		}
    });
	
	$('#add_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            keywords : {
                required : true,
                remote   : {                
                url :'index.php?w=menu&t=ajax&branch=check_keywords',
                type:'get',
                data:{
                    keywords : function(){
                        return $('#keywords').val();
                    }
                  }
                }
            }
        },
        messages : {
            keywords : {
                required : '<?php echo $lang['not_info_keywords'];?>',
                remote   : '<?php echo $lang['info_keywords_exits'];?>'
            }
        }
    });
});

DialogManager.close = function(id) {
	__DIALOG_WRAPPER__[id].hide();
	ScreenLocker.unlock();
}

DialogManager.show = function(id) {
	if (__DIALOG_WRAPPER__[id]) {
		__DIALOG_WRAPPER__[id].show();
		ScreenLocker.lock();
		return true;
	}
	return false;
}

var titles = new Array();
titles["material_list"] = '素材列表';

function show_dialog(id) {//弹出框
	if(DialogManager.show(id)) return;
	var d = DialogManager.create(id);//不存在时初始化(执行一次)
	var dialog_html = $("#"+id+"_dialog").html();
	$("#"+id+"_dialog").remove();
	d.setTitle(titles[id]);
	d.setContents('<div id="'+id+'_dialog" class="'+id+'_dialog">'+dialog_html+'</div>');
	d.setWidth(930);
	d.show('center',1);
	get_material_list();
}
function replace_url(url) {//去当前网址
	return url.replace(UPLOAD_SITE_URL+"/", '');
}

function get_material_list(){//查询商品
	var material_type;
	material_type = $('#material_type').val();
	$("#show_material_list").load('index.php?w=material&t=material_list&'+$.param({'type':material_type}));
}

function select_material(id,type){//商品选择
	if(type==2){
		$('#material_confirm .list .item').removeClass('one');
		$('#material_confirm .list .item').addClass('multi');
	}else{
		$('#material_confirm .list .item').removeClass('multi');
		$('#material_confirm .list .item').addClass('one');
	}
	$('#material_confirm .list .item').html($('#select_'+id).html());
	$('#material_confirm .list .item .mod_del').hide();
	$('#material_confirm').show();
	$('#materialid').val(id);
	DialogManager.close("material_list");
}
</script>