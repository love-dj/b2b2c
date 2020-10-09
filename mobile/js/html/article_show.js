//v5. 2 33h ao
$(function(){
	var article_id = getQueryString('article_id')
	
	if (article_id=='') {
    	window.location.href = WapSiteUrl + '/index.html';
    	return;
	}
	else {
		$.ajax({
			url:ApiUrl+"/index.php?w=article&t=article_show",
			type:'get',
			data:{article_id:article_id},
			jsonp:'callback',
			dataType:'jsonp',
			success:function(result){
				var data = result.datas;
				var html = template.render('article', data);
				$("#art_name ,#art_title").html(data.ac_name);
				$("#article-content").html(html);
				$(".article-content").html(data.article_content);
			}
		});
	}	
	$('#serach_article').click(function(){

		var keyword = encodeURIComponent($('#artkeyword').val());

        location.href = WapSiteUrl+'/html/article_list.html?keyword='+keyword;

    });
});