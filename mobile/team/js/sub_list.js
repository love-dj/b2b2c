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
		
		$.ajax({
			type:'post',
			url:ApiUrl+"/index.php?w=member_team&t=subuser_list",
			data:{key:key},
			dataType:'json',
			success:function(result){
				checkLogin(result.login);//检测是否登录了
				curpage++;
           
				var data = result;
				data.WapSiteUrl = WapSiteUrl;//页面地址
				data.ApiUrl = ApiUrl;
				data.key = getCookie('key');
				
				$(".wtm-order-list").html(result.datas.dom);
			}
		});
	}       

    //初始化页面
    initPage();
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