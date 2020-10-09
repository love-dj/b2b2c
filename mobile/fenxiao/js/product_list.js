var page = pagesize;
var curpage = 1;
var hasmore = true;
var footer = false;
var keyword = decodeURIComponent(getQueryString('keyword'));
var gc_id = getQueryString('gc_id');
var b_id = getQueryString('b_id');
var sort = getQueryString('sort');
var order = getQueryString('order');
var area_id = getQueryString('area_id');
var price_from = getQueryString('price_from');
var price_to = getQueryString('price_to');
var own_shop = getQueryString('own_shop');
var gift = getQueryString('gift');
var robbuy = getQueryString('robbuy');
var xianshi = getQueryString('xianshi');
var virtual = getQueryString('virtual');
var ci = getQueryString('ci');
var myDate = new Date();
var searchTimes = myDate.getTime();

$(function(){
	var key = getCookie('key');
    if (!key) {
        window.location.href = WapSiteUrl+'/html/member/login.html';
        return;
    }
    $.animationLeft({
        valve : '#search_show',
        wrapper : '.wtm-full-mask',
        scroll : '#list-items-scroll'
    });
    $("#header").on('click', '.header-inp', function(){
        location.href = WapSiteUrl + '/html/search.html?keyword=' + keyword;
    });
    if (keyword != '') {
    	$('#keyword').html(keyword);
    }

    // 商品展示形式
    $('#show_style').click(function(){
        if ($('#product_list').hasClass('grid')) {
            $(this).find('span').removeClass('browse-grid').addClass('browse-list');
            $('#product_list').removeClass('grid').addClass('list');
        } else {
            $(this).find('span').addClass('browse-grid').removeClass('browse-list');
            $('#product_list').addClass('grid').removeClass('list');
        }
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
	var key = getCookie('key');
    if (!key) {
        window.location.href = WapSiteUrl+'/html/member/login.html';
        return;
    }
    $('.loading').remove();
    if (!hasmore) {
        return false;
    }
    hasmore = false;
    param = {};
    param.page = page;
    param.curpage = curpage;
    if (gc_id != '') {
        param.gc_id = gc_id;
    } else if (keyword != '') {
        param.keyword = keyword;
    } else if (b_id != '') {
        param.b_id = b_id;
    }
    if (key != '') {
        param.key = key;
    }
    if (sort != '') {
        param.sort = sort;
    }
    if (order != '') {
        param.order = order;
    }

    $.getJSON(ApiUrl + '/index.php?w=member_fx&t=goods_list' + window.location.search.replace('?','&'), param, function(result){
    	if(!result) {
    		result = [];
    		result.datas = [];
    		result.datas.goods_list = [];
    	}
		result.datas.apiurl = ApiUrl;
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
    	if (gc_id) {
    		$('#g_id').val(gc_id);
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
    		var queryString = '?keyword=' + keyword, ci = '';
    		queryString += '&gc_id=' + $('#gc_id').val();
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
    		window.location.href = WapSiteUrl + '/fenxiao/product_list.html' + queryString;
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
    		$('#gc_id').val('');
    	});
    });
}

function init_get_list(o, k) {
    order = o;
    sort = k;
    curpage = 1;
    hasmore = true;
    $("#product_list .goods-secrch-list").html('');
    $('#footer').removeClass('posa');
    get_list();
}

function addfxgoods(gid){
  var e = getCookie("key");
  
  if (isEmpty(e)) {
     $.sDialog({
        skin:"block",
        content:t.datas.error,
        okBtn:false,
        cancelBtn:false
   });
	window.location.href = WapSiteUrl+'/html/member/login.html';
    return;
  }else{
    $.ajax({
        url: ApiUrl + "/index.php?w=member_fx&t=fx_add",
        data: {
           key: e,
           id: gid
        },
        type: "post",
        success: function(e) {
            var t = $.parseJSON(e);
                if (!t.datas.error) {                   
                        $.sDialog({
                            skin:"block",
                            content:'添加成功',
                            okBtn:false,
                            cancelBtn:false
                        });
                } else {
                        $.sDialog({
                            skin:"block",
                            content:t.datas.error,
                            okBtn:false,
                            cancelBtn:false
                        });
                    
                }
        }
    })
  }
}

