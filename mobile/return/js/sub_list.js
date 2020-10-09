var page = pagesize;
var curpage = 1;
var hasMore = true;
var footer = false;
var reset = true;
	
$(function(){
	var key = getCookie('key');
	if(!key){
		window.location.href = WapSiteUrl+'/html/member/login.html';
	}

	function initPage(tj){
	    if (reset) {
	        curpage = 1;
	        hasMore = true;
	    }
		var tj_type = parseInt(tj);
		if(tj_type > 3 || tj_type < 0){
			tj_type = 1;
		}
        $('.loading').remove();
        if (!hasMore) {
            return false;
        }
        hasMore = false;		
		
		$.ajax({
			type:'post',
			url:ApiUrl+"/index.php?w=member_distribution&t=subuser_list&page="+page+"&curpage="+curpage,
			data:{key:key, tj_type:tj_type},
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
		$("#tj").val($(this).data('state'));
        $('#filtrate_ul').find('li').removeClass('selected');
        $(this).parent().addClass('selected').siblings().removeClass("selected");
        reset = true;
        window.scrollTo(0,0);
        initPage($(this).data('state'));
    });

    //初始化页面
    initPage($("#tj").val());//默认第一级推广
    $(window).scroll(function(){
        if(($(window).scrollTop() + $(window).height() > $(document).height()-1)){
			var tj = $("#tj").val();
            initPage(tj);
        }
    });
});
function get_footer() {
    if (!footer) {
        footer = true;
        $.ajax({
            url: WapSiteUrl+'/distribution./js/d_footer.js',
            dataType: "script"
          });
    }
}