$(function (){
	var cart_count = 0;
	cart_count = getCookie('cart_count');
    if (getQueryString('key') != '') {
        var key = getQueryString('key');
        var username = getQueryString('username');
        addCookie('key', key);
        addCookie('username', username);
    } else {
        var key = getCookie('key');
    }
    var html = '<div class="wtm-footer-wrap posr">'
        +'<div class="nav-text">';
    if(key){
        html += '<a href="'+WapSiteUrl+'/html/member/member.html">我的商城</a>'
            + '<a id="logoutbtn" href="javascript:void(0);">注销</a>'
            + '<a href="'+WapSiteUrl+'/html/member/member_feedback.html">反馈</a>'
	    + '<a href="' + WapSiteUrl + '/html/article_list.html?ac_id=2">帮助</a>';
            
    } else {
        html += '<a href="'+WapSiteUrl+'/html/member/login.html">登录</a>'
            + '<a href="'+WapSiteUrl+'/html/member/register.html">注册</a>'
            + '<a href="'+WapSiteUrl+'/html/member/login.html">反馈</a>'
	    + '<a href="' + WapSiteUrl + '/html/article_list.html?ac_id=2">帮助</a>';
    }
        html += '<a href="' + WapSiteUrl + '/news/index.html" class="gotop">资讯</a>' + "</div>" + '<!--<div class="copyright">' + 'Copyright&nbsp;&copy;&nbsp;2008-2018 <a href="javascript:void(0);">龙霸软件 www.longbasz.com</a>版权所有' + "</div>--></div>";
    var key = getCookie('key');
	$('#logoutbtn').click(function(){
		var username = getCookie('username');
		var key = getCookie('key');
		var client = 'wap';
		$.ajax({
			type:'get',
			url:ApiUrl+'/index.php?w=logout',
			data:{username:username,key:key,client:client},
			success:function(result){
				if(result){
					addCookie('wxout', '1');
					delCookie('username');
					delCookie('key');
					delCookie('cart_count');
					location.href = WapSiteUrl+'/html/member/member.html';
				}
			}
		});
	});
	if(typeof(navigate_id) == 'undefined'){navigate_id="0";}
	//当前页面
	if(navigate_id == "1"){
		$(".footnav .home").parent().addClass("current");
		$(".footnav .home").attr('class','home2');
	}else if(navigate_id == "2"){
		$(".footnav .categroy").parent().addClass("current");
		$(".footnav .categroy").attr('class','categroy2');
	}else if(navigate_id == "3"){
		$(".footnav .search").parent().addClass("current");
		$(".footnav .search").attr('class','search2');
	}else if(navigate_id == "4"){
		$(".footnav .cart").parent().parent().addClass("current");
		$(".footnav .cart").attr('class','cart2');
	}else if(navigate_id == "5"){
		$(".footnav .member").parent().addClass("current");
		$(".footnav .member").attr('class','member2');
	}
});