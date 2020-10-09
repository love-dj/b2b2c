
var wx_share = 0;
var ua = navigator.userAgent.toLowerCase();
if (ua.indexOf('micromessenger') > -1) {
    wx_share = 1;
    loadJs("https://res.wx.qq.com/open/js/jweixin-1.2.0.js");
}
$(function(){
		var key = getCookie('key');
		if(key==''){
			location.href = 'login.html';
		}


		$.ajax({
			type:'post',
			url:ApiUrl+"/index.php?w=member_invite",
			data:{key:key},
			dataType:'json',
			//jsonp:'callback',
			success:function(result){
				checkLogin(result.login);
				var data = result.datas.member_info;
				var logo_wx = WapSiteUrl+'/images/logo_mb.png';
				var to_url = WapSiteUrl+'/html/invite.html?smid='+data.user_id;
				
				$('#username').html(data.user_name);
				$('#myurl').val(data.myurl);
				$('#myurl_src').attr("src",data.myurl_src);
				$('#download_url').attr("href",data.mydownurl);
				$('.get_url').attr("href",data.myurl);

				var share_title = encodeURIComponent(data.user_name+'邀请您领积分~新人专享~积分兑换礼品~'+data.html_title);
				var weibo_url =	'http://service.weibo.com/share/share.php?url='+to_url+'&title='+share_title+'&pic='+logo_wx+'&appkey=';
				var qq_url = 'http://connect.qq.com/widget/shareqq/index.html?url='+to_url+'&title='+share_title+'&source='+share_title+'&desc=&pics='+logo_wx;
				var qzone_url = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+encodeURIComponent(to_url)+'&title='+share_title+'&desc=&summary=&site='+share_title+'&pics='+logo_wx;
				var douban_url = 'http://shuo.douban.com/!service/share?href='+to_url+'&name='+share_title+'&text=&image='+logo_wx+'&starid=0&aid=0&style=11';
				
				$('#weibo_url').attr('href',weibo_url);
				$('#qq_url').attr('href',qq_url);
				$('#qzone_url').attr('href',qzone_url);
				$('#douban_url').attr('href',douban_url);
				$('#wx_qrcode').attr("src",data.myurl_src);
				
			  if (wx_share) {
					var url_wx = location.href.split('#')[0];
				  	var _str = url_wx+'@@@'+data.user_name+'邀请您领积分~'+data.html_title+'@@@'+logo_wx+'@@@'+'新人专享，积分兑换礼品~'+data.seo_description+'@@@'+to_url;
					$.ajax({
						url: ApiUrl + "/index.php?w=wx_share&str="+encodeURIComponent(_str),
						data:{key:key},
						dataType: 'script',
						success: function (result) {
						}
					});
				}
				return false;
			}
		});
		if (ua.indexOf('micromessenger') > -1) {
			$('#wx-sha').show();
			$('#wx-qrc').hide();
			$('#wx-sha').click(function(){
				$('#mcover').show();
			});
		}else{
			$('#wx-sha').hide();
			$('#wx-qrc').show();
		}
	$('.abtn').click(function(){
		$('#invite-more').show();
		

	});
	
	
});