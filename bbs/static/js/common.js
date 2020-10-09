$(function(){
	$('.my-group').mouseover(function(){
		var $this = $(this);
		if(!$this.hasClass('t')){
			$this.addClass('t');
			if(_ISLOGIN){
				$.getJSON(BBS_SITE_URL+'/index.php?w=index&t=myjoinedbbs', function(data){
					if(data){
						$.each(data, function(e,d){
							$i = '';
							if(d.is_identity == 1){$i = "<span class=\"c\" title=\"圈主\"></span>";}else if(d.is_identity == 2){$i = "<span class=\"a\" title=\"管理员\"></span>";}
							$('<a href="'+BBS_SITE_URL+'/index.php?w=group&c_id='+d.bbs_id+'">'+d.bbs_name+$i+'</a>').appendTo('span[wttype="span-mygroup"]');
						});
					}else{
						$('<a href="javascript:void(0);">暂未加入过</a>').appendTo('span[wttype="span-mygroup"]');
					}
				});
			}
		}
	});
	$('a[wttype="login"]').click(function(){
		login_dialog();
	});
	$('#topNav').find('li[class="cart"]').mouseover(function(){
		// 运行加载购物车
		load_cart_information();
		$(this).unbind();
	});
	
	// 创建社区
	$('a[wttype="create_bbs"]').click(function(){
		if(_ISLOGIN == 0){
			login_dialog();
		}else{
			window.location.href=BBS_SITE_URL+"/index.php?w=index&t=add_group";
		}
	});
	
	// 返回顶部
    backTop=function (btnId){
        var btn=document.getElementById(btnId);
        var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        window.onscroll=set;
        btn.onclick=function (){
            btn.style.display="none";
            window.onscroll=null;
            this.timer=setInterval(function(){
                scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
                scrollTop-=Math.ceil(scrollTop*0.1);
                if(scrollTop==0) clearInterval(btn.timer,window.onscroll=set);
                if (document.documentElement.scrollTop > 0) document.documentElement.scrollTop=scrollTop;
                if (document.body.scrollTop > 0) document.body.scrollTop=scrollTop;
            },10);
        };
        function set(){
            scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
            btn.style.display=scrollTop?'block':"none";
        }
        };
        backTop('gotop');
	
	$.fn.quick_reply = function(options){
		var defaults = {	
				reply		: '',
				reply_box	: '',
				id			: '',
				c_id		: '',
				identity	: 3
			}; 
		var options = $.extend(defaults, options);
		this.each(function(){
			$(this).click(function(){
				if(_ISLOGIN){
					if(options.identity  == 1 || options.identity == 2 || options.identity == 3){ 	// 成员点击展开回复
						if(options.reply_box.css('display') == 'none'){
							if(!options.reply.hasClass('t')){
								options.reply_box.show();
								// 快速回复
								$.getJSON(BBS_SITE_URL+'/index.php?w=theme&t=ajax_quickreply&c_id='+options.c_id+'&t_id='+options.id, function(data){
									
									// 头像  快速回复栏
									if(data.c_istalk){
										$('<div class="member-avatar-m"><img src="'+data.member_avatar+'" /></div>').appendTo(options.reply);
										var form = $('<form method="post" id="reply_form'+options.id+'" action="'+data.form_action+'"></form>');
										$('<input type="hidden" value="ok" name="form_submit" />').appendTo(form);
										$('<div class="content"><textarea name="replycontent" id="textarea'+options.id+'" ></textarea></div>').appendTo(form);
										$('<span class="count" id="charcount'+options.id+'"></span>').appendTo(form);										
										$('<div class="bottom"><a class="submit-btn" href="javascript:void(0);" wttype="reply_submit">发表回复</a><div wttype="warning" id="warning"></div></div>').appendTo(form);						
										
										
										form.find('a[wttype="reply_submit"]').click(function(){
										    form.submit();
									    }).end().appendTo(options.reply);
										
										$('#textarea'+options.id).charCount({
											allowed: 140,
											warning: 10,
											counterContainerID:'charcount'+options.id,
											firstCounterText:'还可以输入',
											endCounterText:'字',
											errorCounterText:'已经超出'
										});
										
										form.validate({
									        errorLabelContainer: form.find('div[wttype="warning"]'),
									    	submitHandler:function(form){
									    		ajaxpost('reply_form'+options.id, data.form_action, '', 'onerror');
									    	},
									        rules : {
									        	replycontent : {
									                required : true,
									                minlength: data.c_contentleast,
									                maxlength : 140
									            }
									        },
									        messages : {
									        	replycontent  : {
									                required : '请填写内容',
									                minlength: data.c_contentmsg,
									                maxlength : '不能超过140个字符'
									            }
									        }
									    });
									}else{
										//  Reply function does close,put Reply's div hidden.
										options.reply.hide();
									}
									
									// 回复内容部分
									if(data.reply_list){
										$.each(data.reply_list, function(e, d){
											var reply_list = $('<div class="quick-reply-list-2"></div>');
											$('<div class="member-avatar-s"><img src="'+d.member_avatar+'" /></div>').appendTo(reply_list);
											d.reply_id = parseInt(d.reply_id);d.reply_id = ((d.reply_id > 9)?'9+':d.reply_id+'F');
											$('<div class="floor">'+d.reply_id+'</div><div class="line">&nbsp;</div>').appendTo(reply_list);
											var reply_dl = $('<dl></dl>');
											$('<dt class="member-name">'+d.member_name+'<span class="reply-date">'+d.reply_addtime+'</span></dt>').appendTo(reply_dl);
											$('<dd>'+d.reply_content+'</dd>').appendTo(reply_dl);
											reply_dl.appendTo(reply_list);
											reply_list.appendTo(options.reply_box);
										});
									}
									
									options.reply.addClass('t');
								});
							}else{
								options.reply_box.show();
							}
						}else{
							options.reply_box.hide();
						}
					}else{
						// 点击展开申请
						CUR_DIALOG = ajax_form('apply_join','申请加入','index.php?w=group&t=apply&c_id='+options.c_id,520,1);
					}
				}else{
					login_dialog();		
				}
			});
		});	
	}
	// Membership card
	$('[wttype="mcard"]').membershipCard({type:'bbs'});
});
//弹出框登录
function login_dialog(){
		$.show_wt_login({
			wthash:WT_HASH,
			formhash:WT_TOKEN,
			anchor:'bbs_comment_flag'
		});	
}

//赞
function likeYes(o,options){
	$.getJSON(BBS_SITE_URL+'/index.php?w=theme&t=ajax_likeyes&c_id='+options.c_id+'&t_id='+options.t_id, function(data){
		if(data){
			var likeCount = parseInt(o.find('em[wttype="like"]').html())+1;
			o.html('取消赞(<em wttype="like">'+likeCount+'</em>)');
			o.unbind().click(function(){
				likeNo(o,options);
			});
		}
	});
}
//取消赞
function likeNo(o,options){
	$.getJSON(BBS_SITE_URL+'/index.php?w=theme&t=ajax_likeno&c_id='+options.c_id+'&t_id='+options.t_id, function(data){
		if(data){
			var likeCount = parseInt(o.find('em[wttype="like"]').html())-1;
			o.html('赞(<em wttype="like">'+likeCount+'</em>)');
			o.unbind().click(function(){
				likeYes(o,options);
			});
		}
	});
}


$(document).ready(function(){
  $('input[type="radio"][name!="levelset"]').on('ifChecked', function(event){
	if(this.id == 'radio-0'){
			$('.select-module').show();
		}else{
			$('.select-module').hide();
		}
  }).iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass: 'iradio_flat-green'
  });
  $('input[type="checkbox"][class!="checkall"][class!="checkitem"]').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass: 'iradio_flat-green'
  });

});