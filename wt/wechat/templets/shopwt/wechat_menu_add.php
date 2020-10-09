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
<link type="text/css" href="<?php echo ADMIN_TEMPLATES_URL?>/css/weixin/menu.css" rel="stylesheet" />
<div class="page">
 <div class="fixed-bar">
      <div class="item-title"><a class="back" href="index.php?w=menu" title="返回列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['wechat_menu_manage'];?> -  新增</h3>
        <h5><?php echo $lang['wechat_edit_menu'];?></h5>
      </div>
	</div>
  </div>
	<!-- 操作说明 -->
	<div class="explanation" id="explanation">
		<div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
			<h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
			<span id="explanationZoom" title="收起提示"></span> </div>
		<ul>
			<li>当保存提示失败时，请严格按要求填写，带有*写的为必填项。</li>
		</ul>
	</div>

  <div class="fixed-empty"></div>
  <div id="diy_menu">
      <form id="add_form" method="post">
      <input type="hidden" name="form_submit" value="ok" />
      
      <input type="hidden" id="firstmenu" value="1" />
      <input type="hidden" id="childmenu" value="0" />
      
        <div class="diy_menu_designer">
        	<div class="diy_menu_designer_top"></div>
            <div class="diy_menu_designer_content">
            	<div class="diy_menu_designer_content_top"></div>
                <ul id="menu_items">
                	<li class="w162 current" id="first_1">
                    	<input type="hidden" name="Title[1][0]" value="<?php echo $lang['wechat_menu_name'];?>1" />
                        <input type="hidden" name="MsgType[1][0]" value="0" />
                        <textarea style="display:none" name="TextContents[1][0]"></textarea>
                        <input type="hidden" name="MaterialID[1][0]" />
                        <input type="hidden" name="Url[1][0]" />
                    	<a href="Javascript:editfirstmenu(1);"><?php echo $lang['wechat_menu_name'];?>1</a>
                        <p>
                        	<span class="child_add_btn" onclick="addchildmenu(1);"><font style="font-size:18px; font-weight:bold">＋</font></span>
                        </p>
                        <em></em>
                    </li>
                    <li class="w162 btn">
                    	<a href="Javascript:addfirstmenu();"><font style="font-size:14px; font-weight:bold">＋</font>添加菜单</a>
                    </li>
                    <div class="clear"></div>
                </ul>
            </div>
            <div class="diy_menu_designer_footer"></div>
        </div>
        <div class="diy_menu_right">
        	<em></em>
            <div class="diy_menu_form">
            	<div class="diy_table m15">
                	<h2>标题</h2>
                    <div class="input_rows p20">
                    	<label><i>*</i>标题</label>
                        <span><input type="text" name="MenuTitle" value="" id="MenuTitle" /></span>
                        <div class="clear"></div>
                    </div>
                </div>
                
                <div class="diy_table for_form m15">
                	<h2><s onclick="deletemenu();"></s>菜单设置</h2>
                    <div class="input_rows pt20">
                    	<label><i>*</i>菜单名称</label>
                        <span><input type="text" name="inputtitle" value="" id="inputtitle" /></span>
                        <div class="clear"></div>
                    </div>
                    <div class="input_rows other_detail pt20">
                    	<label><i>*</i>菜单动作</label>
                        <span><input type="radio" name="inputtype" value="0" id="inputtype_0" checked />&nbsp;回复内容&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="inputtype" value="1" id="inputtype_1" />&nbsp;回复图文&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="inputtype" value="2" id="inputtype_2" />&nbsp;链接网址&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <div class="clear"></div>
                    </div>
                    <div class="input_rows other_detail pt20" id="detail_msgtype_0">
                    	<label><i>*</i>回复内容</label>
                        <span>
                        	<textarea name="inputtextcontents"></textarea>
                        </span>
						<label></label><span>回复内容不能为空</span>
                        <div class="clear"></div>
                    </div>
                    <div class="input_rows other_detail pt20" id="detail_msgtype_1" style="display:none">
                    	<label><i>*</i>回复素材</label>
                        <span>
                        	<a href="JavaScript:show_dialog('material_list');">[选择素材]</a>
                            <div id="material_confirm" class="material_dialog" style="display:none">
              					<div class="list">
            						<div class="item"></div>
              					</div>
            				</div>
                        </span>
						<label></label><span>回复内容不能留空</span>
                        <div class="clear"></div>
                    </div>
                    <div class="input_rows other_detail pt20" id="detail_msgtype_2" style="display:none">
                    	<label><i>*</i>链接网址</label>
                        <span>
                        	<input type="text" name="inputlink" value="" id="inputlink" />
                        </span>
						<label></label><span>链接网址要完整填写，必填上http://或https://</span>
                        <div class="clear"></div>
                    </div>
                    <div class="height20"></div>
                </div>
            </div>
        </div>
        <div class="clear"></div>
      </form>
  </div>
  <div class="height80"></div>
<div class="bot" id="page_btn_submit"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green">确认提交</a></div>
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
          <a href="JavaScript:void(0);" onclick="get_material_list();" class="btn-search " title="<?php echo $lang['wt_query'];?>"></a></td>
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

var first_id = 2;
var child_id = 1;

$(function(){
	$('input[name=inputtype]').click(function(){
		select_msgtype($(this).val());
	});
	 
	$("#page_btn_submit a").click(function(){
		if($('#MenuTitle').val()==''){
			alert('<?php echo $lang['wechat_not_title'];?>');
			$('#MenuTitle').focus();
			return false;
		}
		if($('#inputtitle').val()==''){
			alert('<?php echo $lang['wechat_not_menu'];?>');
			$('#inputtitle').focus();
			return false;
		}
		$("#add_form").submit();
    });
	
	$('.for_form input').filter('[name=inputtitle]').on('keyup paste blur', function(){
		setmenuvalue('input','Title',$(this).val());
	})
	
	$('.for_form textarea').filter('[name=inputtextcontents]').on('keyup paste blur', function(){
		setmenuvalue('textarea','TextContents',$(this).val());
	})
		
	$('.for_form input').filter('[name=inputlink]').on('keyup paste blur', function(){
		setmenuvalue('input','Url',$(this).val());
	})
	
	setformvalue();
});

//set value to menu
function setmenuvalue(input_type,input_name,input_value){
	var fid=$('#firstmenu').attr('value');
	var cid=$('#childmenu').attr('value');
	if(input_type=='textarea'){
		$(input_type+'[name='+input_name+'\\['+fid+'\\]\\['+cid+'\\]]').html(input_value);
	}else{
		$(input_type+'[name='+input_name+'\\['+fid+'\\]\\['+cid+'\\]]').val(input_value);
	}
	
	if(input_name=='Title'){
		if(cid==0){
			$('#first_'+fid+' a').html(input_value);
		}else{
			$('#first_'+fid+' #child_'+cid+' i').html(input_value);
		}
	}
}

//set form value
function setformvalue(){
	var fid=$('#firstmenu').attr('value');
	var cid=$('#childmenu').attr('value');
	$('input[name=inputtitle]').val($('input[name=Title\\['+fid+'\\]\\['+cid+'\\]]').val());
	$('textarea[name=inputtextcontents]').val($('textarea[name=TextContents\\['+fid+'\\]\\['+cid+'\\]]').val());
	$('input[name=inputlink]').val($('input[name=Url\\['+fid+'\\]\\['+cid+'\\]]').val());
	var mtype = $('input[name=MsgType\\['+fid+'\\]\\['+cid+'\\]]').val();
	$("input[name=inputtype]:eq("+mtype+")").attr("checked",true);
	select_msgtype(mtype);
	
	if(mtype==1){//display material
	    var material_id = $('input[name=MaterialID\\['+fid+'\\]\\['+cid+'\\]]').val();
		if(material_id==0){
			$('#material_confirm .list').html('<div class="item"></div>');
		}else{
			$.getJSON('index.php?w=menu&t=ajax&branch=get_material&mid='+material_id, '', function(data){
				$('#material_confirm .list').html(data.msg);
			});
		}
	}
}

//mstype click
function select_msgtype(msgtype){
	$('#detail_msgtype_0').hide();
	$('#detail_msgtype_1').hide();
	$('#detail_msgtype_2').hide();
	if(msgtype<3){
		$('#detail_msgtype_'+msgtype).show();
	}
		
	var fid=$('#firstmenu').attr('value');
	var cid=$('#childmenu').attr('value');
	$('input[name=MsgType\\['+fid+'\\]\\['+cid+'\\]]').val(msgtype);
	if(msgtype==1){//display material
	    var material_id = $('input[name=MaterialID\\['+fid+'\\]\\['+cid+'\\]]').val();
		if(material_id==0){
			$('#material_confirm').hide();
			$('#material_confirm .list').html('<div class="item"></div>');
		}else{
			$.getJSON('index.php?w=menu&t=ajax&branch=get_material&mid='+material_id, '', function(data){
				$('#material_confirm').show();
				$('#material_confirm .list').html(data.msg);
			});
		}
	}
}

//add first menu
function addfirstmenu(){
	var li_count = $("#menu_items li").length;
	$("#menu_items span").removeClass('curr');
	if(li_count>=3){
		$("#menu_items li").removeClass('current');
		$("#menu_items li.btn").html('<input type="hidden" value="<?php echo $lang['wechat_menu_name'];?>'+first_id+'" name="Title['+first_id+'][0]" /><input type="hidden" name="MsgType['+first_id+'][0]" value="0" /><textarea style="display:none" name="TextContents['+first_id+'][0]"></textarea><input type="hidden" name="MaterialID['+first_id+'][0]" /><input type="hidden" name="Url['+first_id+'][0]" /><a href="Javascript:editfirstmenu('+first_id+');"><?php echo $lang['wechat_menu_name'];?>'+first_id+'</a><p><span class="child_add_btn" onclick="addchildmenu('+first_id+');"><font style="font-size:18px; font-weight:bold">＋</font></span></p><em></em>');
		$("#menu_items li.btn").attr('id','first_'+first_id);
		$("#menu_items li.btn").removeClass('btn').addClass('current');
	}else{
		$("#menu_items li").removeClass('w162').addClass('w108');
		$("#menu_items li").removeClass('current');
		$("#menu_items li").eq(0).after('<li class="w108 current" id="first_'+first_id+'"><input type="hidden" value="<?php echo $lang['wechat_menu_name'];?>'+first_id+'" name="Title['+first_id+'][0]" /><input type="hidden" name="MsgType['+first_id+'][0]" value="0" /><textarea style="display:none" name="TextContents['+first_id+'][0]"></textarea><input type="hidden" name="MaterialID['+first_id+'][0]" /><input type="hidden" name="Url['+first_id+'][0]" /><a href="Javascript:editfirstmenu('+first_id+');"><?php echo $lang['wechat_menu_name'];?>'+first_id+'</a><p><span class="child_add_btn" onclick="addchildmenu('+first_id+');"><font style="font-size:18px; font-weight:bold">＋</font></span></p><em></em></li>');
	}
	
	$('#firstmenu').attr('value',first_id);
	$('#childmenu').attr('value','0');
	setformvalue();
	first_id++;
}

//edit child menu
function editfirstmenu(id){
	$("#menu_items li").removeClass('current');
	$("#menu_items li").eq(id-1).addClass('current');
	$("#menu_items span").removeClass('curr');
	$('#firstmenu').attr('value',id);
	$('#childmenu').attr('value','0');
	var span_count = $('#first_'+id+' p span').length;
	setformvalue();
	if(span_count>1){
		$('.for_form .other_detail').hide();
	}
}

//add child menu
function addchildmenu(id){
	var span_count = $('#first_'+id+' p span').length;
	if(span_count>=5){
		$('#first_'+id+' p span.child_add_btn').remove();
		//$('#first_'+id+' p span').eq(3).after('<span onclick="editchildmenu('+id+','+child_id+')" id="child_'+child_id+'"><input type="hidden" name="Title['+id+']['+child_id+']" value="子菜单'+child_id+'" /><input type="hidden" name="MsgType['+id+']['+child_id+']" value="0" /><textarea style="display:none" name="TextContents['+id+']['+child_id+']"></textarea><input type="hidden" name="MaterialID['+id+']['+child_id+']" /><input type="hidden" name="Url['+id+']['+child_id+']" /><i>子菜单'+child_id+'</i></span>');
	}else{
		var p_height = 45 * (span_count+1);
		$('#first_'+id+' p').css('height',p_height);
	}
	$('#first_'+id+' p').prepend('<span onclick="editchildmenu('+id+','+child_id+')" id="child_'+child_id+'"><input type="hidden" name="Title['+id+']['+child_id+']" value="<?php echo $lang['wechat_child_name'];?>'+child_id+'" /><input type="hidden" name="MsgType['+id+']['+child_id+']" value="0" /><textarea style="display:none" name="TextContents['+id+']['+child_id+']"></textarea><input type="hidden" name="MaterialID['+id+']['+child_id+']" /><input type="hidden" name="Url['+id+']['+child_id+']" /><i><?php echo $lang['wechat_child_name'];?>'+child_id+'</i></span>');
	$('#firstmenu').attr('value',id);
	$('#childmenu').attr('value',child_id);
	$("#menu_items li").removeClass('current');
	$("#menu_items span").removeClass('curr');
	$("#first_"+id+" #child_"+child_id).addClass('curr');
	
	setformvalue();
	child_id++;
}

//edit child menu
function editchildmenu(ffid,ccid){
	$("#menu_items li").removeClass('current');
	$('#firstmenu').attr('value',ffid);
	$('#childmenu').attr('value',ccid);
	$("#menu_items span").removeClass('curr');
	$("#first_"+ffid+" #child_"+ccid).addClass('curr');
	setformvalue();
}

function deletemenu(){
	var fid=$('#firstmenu').attr('value');
	var cid=$('#childmenu').attr('value');
	if(cid>0){
		var span_count = $('#first_'+fid+' p span').length;
		$('#first_'+fid+' p span').removeClass('curr');
		if(span_count==2){
			$('#child_'+cid).remove();
			$('#childmenu').attr('value','0');
			var p_height = 45 * (span_count-1);
			$('#first_'+fid).addClass('current');
		}else if(span_count==5 && $('#first_'+fid+' p span.child_add_btn').length==0){
			$('#child_'+cid).remove();
			$('#childmenu').attr('value',$('#first_'+fid+' p span').eq(0).attr('id').replace("child_", ""));
			$('#first_'+fid+' p span').eq(0).addClass('curr');
			var p_height = 45 * span_count;
			$('#first_'+fid+' p').eq(0).append('<span class="child_add_btn" onclick="addchildmenu('+fid+');"><font style="font-size:18px; font-weight:bold">＋</font>');
		}else{
			$('#child_'+cid).remove();
			$('#childmenu').attr('value',$('#first_'+fid+' p span').eq(0).attr('id').replace("child_", ""));
			$('#first_'+fid+' p span').eq(0).addClass('curr');
			var p_height = 45 * (span_count-1);
		}
		$('#first_'+fid+' p').css('height',p_height);
		
	}else{
		if(confirm('是否要删除该菜单及其下子菜单？')){
			var li_count = $("#menu_items li").length;
			if(li_count==2){
				alert('至少要设置一个菜单');
			}else{
				$('#first_'+fid).remove();
				if($("#menu_items li.btn").length>0){
					$("#menu_items li").removeClass('w108').addClass('w162');
					$("#menu_items li").removeClass('current');
					$("#menu_items li").eq(0).addClass('current');
					$('#firstmenu').attr('value',$("#menu_items li").eq(0).attr('id').replace("first_", ""));
					$('#childmenu').attr('value','0');
				}else{
					$("#menu_items li").removeClass('current');
					$("#menu_items li").eq(0).addClass('current');
					$('#firstmenu').attr('value',$("#menu_items li").eq(0).attr('id').replace("first_", ""));
					$('#childmenu').attr('value','0');
					$("#menu_items li").eq(1).after('<li class="w108 btn"><a href="Javascript:addfirstmenu();"><font style="font-size:14px; font-weight:bold">＋</font>添加菜单</a></li>');
				}
			}
		}
	}
	setformvalue();
}

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
	
	var fid=$('#firstmenu').attr('value');
	var cid=$('#childmenu').attr('value');
	$('input[name=MaterialID\\['+fid+'\\]\\['+cid+'\\]]').val(id);
	
	DialogManager.close("material_list");
}
</script>