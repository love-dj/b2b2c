$(function() {
    var pingou_id = getQueryString("pingou_id");
    var buyer_id = getQueryString("buyer_id");
    var key = getCookie('key');
    $.ajax({
        url: ApiUrl + "/index.php?w=pingou&t=info",
        data: {key: key, pingou_id: pingou_id,buyer_id: buyer_id},
        type: "get",
        dataType: "json",
        success: function(result) {
            var html = template.render('product_detail', result.datas.pingou_info);
            $("#product_detail_html").html(html);
            html = template.render('pingou-btn', result.datas.pingou_info);
            $("#btn_html").html(html);
            var _info = result.datas.pingou_info;
            document.title=_info.goods_name;
            takeCount();
        }
    });
});
	function takeCount() {
	    setTimeout("takeCount()", 1000);
	    $(".pingou-Countdown").each(function(){
	        var obj = $(this);
	        var tms = obj.attr("count_down");
	        if (tms>0) {
	            tms = parseInt(tms)-1;
                var hours = Math.floor(tms / (1 * 60 * 60)) % 24;
                var minutes = Math.floor(tms / (1 * 60)) % 60;
                var seconds = Math.floor(tms / 1) % 60;

                if (hours < 0) hours = 0;
                if (minutes < 0) minutes = 0;
                if (seconds < 0) seconds = 0;
                obj.find("[time_id='h']").html(hours);
                obj.find("[time_id='m']").html(minutes);
                obj.find("[time_id='s']").html(seconds);
                obj.attr("count_down",tms);
	        }
	    });
	}