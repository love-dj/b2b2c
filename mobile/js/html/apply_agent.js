$(function(){
    var key = getCookie('key');
	$.sValid.init({
		rules:{
			agent_level:"required",
			apply_remark:"required",
			mob_phone:"required",
			area_info:"required"
		},
		messages:{
			agent_level:"等级必填！",
			apply_remark:"说明必填！",
			mob_phone:"手机号必填！",
			area_info:"地区必填！"
		},
		callback:function (eId,eMsg,eRules){
			if(eId.length >0){
				var errorHtml = "";
				$.map(eMsg,function (idx,item){
					errorHtml += "<p>"+idx+"</p>";
				});
				errorTipsShow(errorHtml);
			}else{
				errorTipsHide();
			}
		}  
	});
	$('#header-nav').click(function(){
		$('.btn').click();
	});
	$('.btn').click(function(){
		if($.sValid()){
			
			var sel=document.getElementById("agent_level");//select元素的id 
			var index=sel.selectedIndex;//获取被选中的option的索引 
			var agent_level= sel.options[index].value;//获取相应的option的value值
			
			var apply_remark = $('#apply_remark').val();
			var mob_phone = $('#mob_phone').val();
			
			if(agent_level == 3){
				var area_info = $('#area_info').data('areaid');
			}else{
				var area_info = $('#area_info').data('areaid2');
			}
			
			$.ajax({
				type:'post',
				url:ApiUrl+"/index.php?w=member_index&t=apply_agent_add",	
				data:{
				    key:key,
					agent_level:agent_level,
				    mob_phone:mob_phone,
				    apply_remark:apply_remark,
				    area_info:area_info
				},
				dataType:'json',
				success:function(result){
					if(result){
						location.href = WapSiteUrl+'/html/member/member.html';
					}else{
						location.href = WapSiteUrl+'/html/member/apply_agent.html';
					}
				}
			});
		}
	});

    // 选择地区
    $('#area_info').on('click', function(){
        $.areaSelected({
            success : function(data){
                $('#area_info').val(data.area_info).attr({'data-areaid':data.area_id, 'data-areaid2':(data.area_id_2 == 0 ? data.area_id_1 : data.area_id_2)});
            }
        });
    });
});