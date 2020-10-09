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

	if (getQueryString('data-state') != '') {
	    $('#filtrate_ul').find('li').has('a[data-state="' + getQueryString('data-state')  + '"]').addClass('selected').siblings().removeClass("selected");
	}

    $('#search_btn').click(function(){
        reset = true;
    	initPage();
    });


	function initPage(){
	    if (reset) {
	        curpage = 1;
	        hasMore = true;
	    }
        $('.loading').remove();
        if (!hasMore) {
            return false;
        }
        hasMore = false;
	    var state_type = $('#filtrate_ul').find('.selected').find('a').attr('data-state');
	    var orderKey = $('#order_key').val();
		$.ajax({
			type:'post',
			url:ApiUrl+"/index.php?w=member_fx&t=fx_order&page="+page+"&curpage="+curpage,
			data:{key:key, state_type:state_type, order_key : orderKey},
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
	

   
    
    $('#filtrate_ul').find('a').click(function(){
        $('#filtrate_ul').find('li').removeClass('selected');
        $(this).parent().addClass('selected').siblings().removeClass("selected");
        reset = true;
        window.scrollTo(0,0);
        initPage();
    });

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