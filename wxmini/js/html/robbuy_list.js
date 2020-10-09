var page = pagesize;
var curpage = 1;
var hasmore = true;
var footer = false;
var key = getQueryString('key');
var order = getQueryString('order');
var area_id = getQueryString('area_id');
var price_from = getQueryString('price_from');
var price_to = getQueryString('price_to');
var own_shop = getQueryString('own_shop');
var ci = getQueryString('ci');
var myDate = new Date();
var searchTimes = myDate.getTime();

$(function(){
    $.animationLeft({
        valve : '#search_show',
        wrapper : '.wtm-full-mask',
        scroll : '#list-items-scroll'
    });
 

    // 排序显示隐藏
    $('#sort_default').click(function(){
        if ($('#sort_inner').hasClass('hide')) {
            $('#sort_inner').removeClass('hide');
        } else {
            $('#sort_inner').addClass('hide');
        }
    });
    $('#nav_ul').find('a').click(function(){
        $(this).addClass('current').parent().siblings().find('a').removeClass('current');
        if (!$('#sort_inner').hasClass('hide') && $(this).parent().index() > 0) {
            $('#sort_inner').addClass('hide');
        }
    });
    $('#sort_inner').find('a').click(function(){
        $('#sort_inner').addClass('hide').find('a').removeClass('cur');
        var text = $(this).addClass('cur').text();
        $('#sort_default').html(text + '<i></i>');
    });

    get_list();
    $(window).scroll(function(){
        if(($(window).scrollTop() + $(window).height() > $(document).height()-1)){
            get_list();
        }
    });
    search_show();
});

function get_list() {
    $('.loading').remove();
    if (!hasmore) {
        return false;
    }
    hasmore = false;
    param = {};
    param.page = page;
    param.curpage = curpage;
   
    if (key != '') {
        param.key = key;
    }
    if (order != '') {
        param.order = order;
    }

    $.getJSON(ApiUrl + '/index.php?w=goods&t=goods_gblist' + window.location.search.replace('?','&'), param, function(result){
    	if(!result) {
    		result = [];
    		result.datas = [];
    		result.datas.goods_list = [];
    	}
        $('.loading').remove();
        curpage++;
        var html = template.render('home_body', result);
        $("#product_list .goods-secrch-list").append(html);
        hasmore = result.hasmore;
    });
}

function search_show() {
    $.getJSON(ApiUrl + '/index.php?w=index&t=search_show', function(result) {
    	var data = result.datas;
    	$('#list-items-scroll').html(template.render('search_items',data));
    	if (area_id) {
    		$('#area_id').val(area_id);
    	}
    	if (price_from) {
    		$('#price_from').val(price_from);
    	}
    	if (price_to) {
    		$('#price_to').val(price_to);
    	}
    	if (own_shop) {
    		$('#own_shop').addClass('current');
    	}
    	
    	if (ci) {
    		var ci_arr = ci.split('_');
    		for(var i in ci_arr) {
    			$('a[name="ci"]').each(function(){
    				if ($(this).attr('value') == ci_arr[i]) {
    					$(this).addClass('current');
    				}
    			});
    		}
    	}
    	$('#search_submit').click(function(){
    		var ci = '';
    		var queryString = '?area_id=' + $('#area_id').val();
    		if ($('#price_from').val() != '') {
    			queryString += '&price_from=' + $('#price_from').val();
    		}
    		if ($('#price_to').val() != '') {
    			queryString += '&price_to=' + $('#price_to').val();
    		}
    		if ($('#own_shop')[0].className == 'current') {
    			queryString += '&own_shop=1';
    		}
			
    		$('a[name="ci"]').each(function(){
    			if ($(this)[0].className == 'current') {
    				ci += $(this).attr('value') + '_';
    			}
    		});
    		if (ci != '') {
    			queryString += '&ci=' + ci;
    		}
    		window.location.href = WapSiteUrl + '/html/product_robbuy.html' + queryString;
    	});
    	$('a[wttype="items"]').click(function(){
    		var myDate = new Date();
    		if(myDate.getTime() - searchTimes > 300) {
    			$(this).toggleClass('current');
    			searchTimes = myDate.getTime();
    		}
    	});
    	$('input[wttype="price"]').on('blur',function(){
    		if ($(this).val() != '' && ! /^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test($(this).val())) {
    			$(this).val('');
    		}
    	});
    	$('#reset').click(function(){
    		$('a[wttype="items"]').removeClass('current');
    		$('input[wttype="price"]').val('');
    		$('#area_id').val('');
    	});
    });
}

function init_get_list(o, k) {
    order = o;
    key = k;
    curpage = 1;
    hasmore = true;
    $("#product_list .goods-secrch-list").html('');
    $('#footer').removeClass('posa');
    get_list();
}

//跳转详情
function go_product_detail(id){
    location.href='product_detail.html?goods_id='+id;
}
function takeCount() {
	setTimeout("takeCount()", 1000);
	$(".time-remain").each(function(){
		var obj = $(this);
		var tms = obj.attr("count_down");
		if (tms>0) {
			tms = parseInt(tms)-1;
			var days = Math.floor(tms / (1 * 60 * 60 * 24));
			var hours = Math.floor(tms / (1 * 60 * 60)) % 24;
			var minutes = Math.floor(tms / (1 * 60)) % 60;
			var seconds = Math.floor(tms / 1) % 60;

			if (days < 0) days = 0;
			if (hours < 0) hours = 0;
			if (minutes < 0) minutes = 0;
			if (seconds < 0) seconds = 0;
			 obj.find("[time_id='d']").html(days);
			obj.find("[time_id='h']").html(hours);
			obj.find("[time_id='m']").html(minutes);
			obj.find("[time_id='s']").html(seconds);
			obj.attr("count_down",tms);
		}
	});
}
$(function(){
		setTimeout("takeCount()", 1000);
});