<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
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
<div class="page">
	<div class="fixed-bar">
		<div class="item-title"><a class="back" href="index.php?w=team_user&t=index" title="返回团队列表"><i class="fa fa-arrow-circle-o-left"></i></a>
			<div class="subject">
				<h3>新增团队</h3>
				<h5>新增商城会员团队</h5>
			</div>
		</div>
	</div>
	<form id="level_form" action="" name="form1" enctype="multipart/form-data" method="post">
		<input type="hidden" name="form_submit" value="ok" />
		<div class="wtap-form-default">	
		    <dl class="row" id="search_input">
		        <dt class="tit">
		          <label><em>*</em>搜索会员</label>
		        </dt>
		        <dd class="opt">
		          <input type="text" placeholder="搜索会员手机号/姓名/邮箱" value="" id="user_key" maxlength="20" class="input-txt">
		          <a id="user_search" href="JavaScript:void(0);" class="wtap-btn mr5"><?php echo $lang['wt_search'];?></a></dd>
		    </dl>
		    <dl class="row" id="selected_goods_list">
		        <dt class="tit">已选择会员</dt>
		        <dd class="opt">
		          <input type="hidden" name="valid_recommend" id="valid_recommend" value="">
		          <span class="err"></span>
		          <ul class="dialog-goodslist-s1 goods-list scrollbar-box">
		          </ul>
		        </dd>
		    </dl>
		    <dl class="row" id="search_goodslist">
		        <dt class="tit">请选择会员</dt>
		        <dd class="opt">
		          <div id="show_recommend_goods_list" class="show-recommend-goods-list scrollbar-box"></div>
		          <p class="notic">一次最多可选择5个会员</p>
		        </dd>
		    </dl>		
			<dl class="row">
				<dt class="tit">
					<label for="level_id"><em>*</em>团队等级</label>
				</dt>
				<dd class="opt">
					<select name="level_id" class="valid">
						<option value="0">-请选择-</option>
						<?php foreach($output['level_array'] as $k=>$v){ ?>
						<option value="<?php echo $v['id'];?>"><?php echo $v['level_name'];?></option>
						<?php } ?>
                    </select>
					<span class="err"></span>
					<p class="notic"></p>
				</dd>
			</dl>

 			<div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="submitBtn">保存</a></div>
		</div>
	</form>
</div>
<script type="text/javascript">
	$(function(){
		$("#submitBtn").click(function(){
			if($("#level_form").valid()){
				$("#level_form").submit();
			}
		});
	});
	
    $('#user_search').on('click',function(){
        $('#valid_recommend').rules('remove');
        var user_key = $('#user_key').val();
    	if(user_key.length > 0){
    	    $('#show_recommend_goods_list').load('index.php?w=team_user&t=get_userlist&user_key='+user_key);
    	}else{
    		alert('请输入搜索关键词');
    	}
    });
	function select_recommend_goods(member_id) {
		if (typeof member_id == 'object') {
			var member_name = member_id['member_name'];
			var member_pic = member_id['member_avatar'];
			var member_id = member_id['member_id'];
		} else {
	    	var goods = $("#show_recommend_goods_list img[member_id='"+member_id+"']");
	    	var member_pic = goods.attr("src");
	    	var member_name = goods.attr("member_name");
		}
		var obj = $("#selected_goods_list");
		if(obj.find("img[member_id='"+member_id+"']").size()>0) return;//避免重复
		if(obj.find("ul>li").size()>=10){
			alert('最多可推荐10个商品');
			return false;
		}
		var text_append = '';
		text_append += '<div onclick="del_recommend_goods(this,'+member_id+');" class="goods-pic">';
		text_append += '<span class="ac-ico"></span>';
		text_append += '<span class="thumb size-72x72">';
		text_append += '<i></i>';
	  	text_append += '<img width="72" member_id="'+member_id+'" title="'+member_name+'" member_name="'+member_name+'" src="'+member_pic+'" />';
		text_append += '</span></div>';
		text_append += '<div class="goods-name">';
		text_append += '<a href="#" target="_blank">';
	  	text_append += member_name+'</a>';
		text_append += '</div>';
		text_append += '<input name="member_id_list[]" value="'+member_id+'" type="hidden">';
		obj.find("ul").append('<li>'+text_append+'</li>');
		<?php if (!$_GET['rec_gc_id']) { ?>
		   $('#gc_id').val($('#gcategory').children('select').last().val());	
	    <?php } ?>
	}
	function del_recommend_goods(obj,member_id) {
		$(obj).parent().remove();
	}

	$(document).ready(function(){
		$('#level_form').validate({
			errorPlacement: function(error, element){
				var error_td = element.parent('dd').children('span.err');
				error_td.append(error);
			},
			rules : {				
				level_id : {
					required   : true
				},
				member_id_list : {
					required   : true
				},
			},
			messages : {
				level_id : {
					required : '<i class="fa fa-exclamation-circle"></i>请选择团队等级'
				},
				member_id_list : {
					required : '<i class="fa fa-exclamation-circle"></i>请选择会员'
				},
				
			}
		});
	});
</script>