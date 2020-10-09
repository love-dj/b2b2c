$(function() {
    var e = getCookie("seller_key");
    if (!e) {
        location.href = "login.html"
    }
    function s() {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?w=seller_express&t=get_list",
            data: {
                key: e
            },
            dataType: "json",
            success: function(e) {
                //checkLogin(e.login);  
                var t = template.render("saddress_list", e);
                $("#address_list").empty();
                $("#address_list").append(t);
				
				$("#header-nav").click(function() {
					$(".btn-l").click()
				});
                $(".btn-l").click(function() {
                    $.sDialog({
                        skin: "block",
                        content: "确定保存？",
                        okBtn: true,
                        cancelBtn: true,
                        okFn: function() {
							 
                            saveexpress();
                        }
                    })
                })
            } 
        })
    }
    s();
    function saveexpress() {
		  var expresslists = ""; 
		   $("input[name='defaultexpress']:checked").each(function(){ 
                if($(this).attr("prop")){
                    expresslists += $(this).val()+","
                }
            })
          expresslists=expresslists.substring(0,expresslists.length-1);
		  if(expresslists==""){
							$.sDialog({
								skin: "block",
								content: "请至少选择一个物流公司!",
								okBtn: true,
								cancelBtn: false
								 
							})
		  
		  }else{
						  $.ajax({
							type: "post",
							url: ApiUrl + "/index.php?w=seller_express&t=savedefault",
							data: {
								expresslists:expresslists,
								key: e
							},
							dataType: "json",
							success: function(e) {
								//checkLogin(e.login);
								if (e) {
 
									$.sDialog({
										skin: "block",
										content: "物流保存成功!",
										okBtn: true,
										cancelBtn: true,
										okFn: function() {
											s()
										}
									})
  
								}else{
								
									
									$.sDialog({
										skin: "block",
										content: "物流保存失败!",
										okBtn: true,
										cancelBtn: true,
										okFn: function() {
											s()
										}
									})
								
								}
							}
						  }) 
		  }
    }
});