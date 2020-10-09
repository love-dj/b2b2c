var page = pagesize;
var curpage = 1;
var hasmore = true;
var gc_id = getQueryString('gc_id');
var b_id = getQueryString('b_id');
var key = getQueryString('key');
var order = getQueryString('order');
var area_id = getQueryString('area_id');
var price_from = getQueryString('price_from');
var price_to = getQueryString('price_to');
$(function(){
	//大类分类列表
	$.getJSON(ApiUrl+"/index.php?w=goods_class&t=get_child_all_list", function(result){
		var data = result.datas;
		data.WapSiteUrl = WapSiteUrl;
		var html = template.render('nav-class-list', data);
        
		$("#nav-show-class").html(html);
		var ele = $("#nav-show-class");
		ele.width((ele.find("li").length + 1) * (ele.find("li").width()+20));
		myScroll = new IScroll('.nav-content', { mouseWheel: true, scrollX:true, click: true });
	});
	//点击分类
	$('#nav-show-class').on('click','.gc_active', function(){
		gc_id = $(this).attr('date-id');
		$("#product_list .goods-secrch-list").html('');
		hasmore = true;
     	curpage=1;
	    get_list();
		$(this).parent().addClass('active').siblings().removeClass("active");
		myScroll.scrollToElement(document.querySelector('.active'), 1000, true, true);
	});

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

    get_list();
    $(window).scroll(function(){
        if(($(window).scrollTop() + $(window).height() > $(document).height()-1)){
            get_list();
        }
    });
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
    if (gc_id != '' && gc_id>0) {
        param.gc_id = gc_id;
    } else if (b_id != '') {
        param.b_id = b_id;
    }
    if (key != '') {
        param.key = key;
    }
    if (order != '') {
        param.order = order;
    }

    $.getJSON(ApiUrl + '/index.php?w=pingou&t=pingou_list' + window.location.search.replace('?','&'), param, function(result){
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