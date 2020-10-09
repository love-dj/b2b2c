var page = pagesize;
var curpage = 1;
var hasMore = true;
var footer = false;
var reset = true;
var orderKey = '';
	
$(function(){
	var key = getCookie('key');
	if(!key){
		window.location.href = WapSiteUrl+'/html/member/login.html';
	}

    $('#search_btn').click(function(){
        reset = true;
    	initPage();
    });


	function initPage(){
		var goods_name = $('#goods_key').val();
	    if (reset) {
	        curpage = 1;
	        hasMore = true;
	    }
        $('.loading').remove();
        if (!hasMore) {
            return false;
        }
        hasMore = false;
	   
		$.ajax({
			type:'post',
			url:ApiUrl+"/index.php?w=member_fx&t=fx_goods&page="+page+"&curpage="+curpage,
			data:{key:key,goods_name:goods_name},
			dataType:'json',
			success:function(result){
				checkLogin(result.login);//检测是否登录了
				curpage++;
                hasMore = result.hasmore;
                if (!hasMore) {
                    get_footer();
                }
            
				var data = result;
				data.WapSiteUrl = WapSiteUrl;//页面地址
				data.ApiUrl = ApiUrl;
				data.key = getCookie('key');
				
              
				var html = template.render('order-list-tmpl', data);
				if (reset) {
				    reset = false;
				    $("#order-list").html(html);
				} else {
                    $("#order-list").append(html);
                }
			}
		});

	}
	

    
    // 删除分销商品
    $('#order-list').on('click','.delete-fxgoods',deleteFxgoods);
   
   //删除分销商品
    function deleteFxgoods(){
        var fx_id = $(this).attr("fx_id");

        $.sDialog({
            content: '是否移除分销商品？',
            okFn: function() { deleteFxId(fx_id); }
        });
    }

    function deleteFxId(fx_id) {
        $.ajax({
            type:"post",
            url:ApiUrl+"/index.php?w=member_fx&t=drop_goods",
            data:{fx_id:fx_id,key:key},
            dataType:"json",
            success:function(result){
                if(result.datas && result.datas == 1){
                    reset = true;
                    initPage();
                } else {
                    $.sDialog({
                        skin:"red",
                        content:result.datas.error,
                        okBtn:false,
                        cancelBtn:false
                    });
                }
            }
        });
    }


/*     $('#filtrate_ul').find('a').click(function(){
        $('#filtrate_ul').find('li').removeClass('selected');
        $(this).parent().addClass('selected').siblings().removeClass("selected");
        reset = true;
        window.scrollTo(0,0);
        initPage();
    }); */

    //初始化页面
    initPage();
    $(window).scroll(function(){
        if(($(window).scrollTop() + $(window).height() > $(document).height()-1)){
            initPage();
        }
    });
});
function get_footer() {
    if (!footer) {
        footer = true;
        $.ajax({
            url: WapSiteUrl+'/fenxiao/js/fx_footer.js',
            dataType: "script"
          });
    }
}