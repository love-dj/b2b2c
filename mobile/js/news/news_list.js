var page = pagesize;
var curpage = 1;
var hasmore = true;
var class_id = getQueryString("class_id");
var keyword = decodeURIComponent(getQueryString("keyword"));

$(function() {

    if (keyword != "") {
        $("#news-keyword").val(keyword);
    }
	get_list();
	$(window).scroll(function() {
		if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {
			get_list()
		}
	});
	class_list()
	
	$('#search-news').click(function(){

		var keyword = encodeURIComponent($('#news-keyword').val());

        location.href = WapSiteUrl+'/news/index.html?keyword='+keyword;

    });
	
});

function get_list() {
	$(".loading").remove();
	if (!hasmore) {
		return false
	}
	hasmore = false;
	param = {};
	param.page = page;
	param.curpage = curpage;
	if (class_id != "") {
		param.class_id = class_id
	}
	if (keyword != "") {
        param.keyword = keyword;
    }

	$.getJSON(ApiUrl + "/index.php?w=news_article&t=article_list" + window.location.search.replace("?", "&"), param, function(e) {
		if (!e) {
			e = [];
			e.datas = [];
			e.datas.article_list = []
		}
		$(".loading").remove();
		curpage++;
		var r = template.render("home_body", e);
		$("#cart-list-wp .news-list").append(r);
		hasmore = e.hasmore
	})
}
function class_list() {
	param = {};
	if (class_id != "") {
		param.class_id = class_id
	}
	$.getJSON(ApiUrl + "/index.php?w=news_article&t=class_list", param, function(e) {
		var r = e.datas;
		$("#loadClass").html(template.render("class_list", r));
		$('title').html(e.datas.news_seo.news_seo_title);
		$("meta[name='keywords']").attr('content',e.datas.news_seo.news_seo_keywords);
		$("meta[name='description']").attr('content',e.datas.news_seo.news_seo_description);
	})
}
function init_get_list(e, r) {
	order = e;
	key = r;
	curpage = 1;
	hasmore = true;
	$("#cart-list-wp .news-list").html("");
	get_list()
}