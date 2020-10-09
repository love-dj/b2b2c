var wx_share = 0;
var goods_id = getQueryString('gid');
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
			url:ApiUrl+"/index.php?w=member_fx&t=fx_add",
			data:{key:key,id:goods_id},
			dataType:'json',
			//jsonp:'callback',
			success:function(result){
				checkLogin(result.login);
				var data = result.datas.data;
				var logo_wx = data.goods_image;
				var to_url = data.fx_url;
				
				$('.goods_name').html(result.datas.data.goods_name);
				$('.goods_pic_url').html('<img src="'+data.goods_image+'" style="width:100%; height:100%; max-width:360px;max-height:360px;" />');
				$('.goods_price').html('<b style="display: inline-block; font-size: 0.85rem; color: #DB4453; line-height: 1rem;">¥'+result.datas.data.goods_price+'</b>');
				$('#myurl').val(to_url);
				$('.get_url').attr("href",to_url);
				
				//二维码
				$('#qrcodeTable').html('');
				
				jQuery('#qrcodeTable').qrcode({render: "canvas",text:to_url,width:"240",height:"240"});
				//event.preventDefault();

				var share_title = encodeURIComponent(data.goods_name);
				var weibo_url =	'http://service.weibo.com/share/share.php?url='+to_url+'&title='+share_title+'&pic='+logo_wx+'&appkey=';
				var qq_url = 'http://connect.qq.com/widget/shareqq/index.html?url='+to_url+'&title='+share_title+'&source='+share_title+'&desc=&pics='+logo_wx;
				var qzone_url = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+encodeURIComponent(to_url)+'&title='+share_title+'&desc=&summary=&site='+share_title+'&pics='+logo_wx;
				var douban_url = 'http://shuo.douban.com/!service/share?href='+to_url+'&name='+share_title+'&text=&image='+logo_wx+'&starid=0&aid=0&style=11';
				 
			 	$('#weibo_url').attr('href',weibo_url);
				$('#qq_url').attr('href',qq_url);
				$('#qzone_url').attr('href',qzone_url);
				$('#douban_url').attr('href',douban_url);
				//$('#wx_qrcode').attr("src",data.myurl_src); 
				
			   if (wx_share) {
					var url_wx = location.href.split('#')[0];
				  	var _str = url_wx+'@@@'+data.goods_name+'@@@'+logo_wx+'@@@'+data.goods_name+'@@@'+to_url;
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