$(function() {
    var goods_id = getQueryString("goods_id");
    var key = getCookie('key');

    get_detail(goods_id);
    $.ajax({
        url: ApiUrl + "/index.php?w=goods&t=goods_body",
        data: {goods_id: goods_id},
        type: "get",
        success: function(result) {
            $(".fixed-tab-pannel").html(result);
        }
    });

    $('#goodsDetail').click(function(){
        window.location.href = WapSiteUrl+'/html/product_detail.html?goods_id=' + goods_id;
    });
    $('#goodsBody').click(function(){
        window.location.href = WapSiteUrl+'/html/product_info.html?goods_id=' + goods_id;
    });
    $('#goodsEvaluation').click(function(){
        window.location.href = WapSiteUrl+'/html/product_eval_list.html?goods_id=' + goods_id;
    });
function show_tip() {
	var flyer = $('.goods-pic > img').clone().css({
		'z-index': '999',
		'height': '3rem',
		'width': '3rem'
	});
	flyer.fly({
		start: {
			left: $('.goods-pic > img').offset().left,
			top: $('.goods-pic > img').offset().top - $(window).scrollTop()
		},
		end: {
			left: $("#cart_count1").offset().left + 40,
			top: $("#cart_count1").offset().top - $(window).scrollTop(),
			width: 0,
			height: 0
		},
		onEnd: function() {
			flyer.remove();
		}
	});
}    
	function buyNumer() {
		$.sValid();
	}    
	function get_detail(goods_id) {
		//渲染页面
		$.ajax({
         url:ApiUrl+"/index.php?w=goods&t=goods_detail",
         type:"get",
         data:{goods_id:goods_id,key:key},
         dataType:"json",
         success:function(result){
            var data = result.datas;
            if(!data.error){
              //商品图片格式化数据
              if(data.goods_image){
                var goods_image = data.goods_image.split(",");
                data.goods_image = goods_image;
              }else{
                 data.goods_image = [];
              }
              //商品规格格式化数据
              if(data.goods_content.spec_name){
                var goods_map_spec = $.map(data.goods_content.spec_name,function (v,i){
                  var goods_specs = {};
                  goods_specs["goods_spec_id"] = i;
                  goods_specs['goods_spec_name']=v;
                  if(data.goods_content.spec_value){
                      $.map(data.goods_content.spec_value,function(vv,vi){
                          if(i == vi){
                            goods_specs['goods_spec_value'] = $.map(vv,function (vvv,vvi){
                              var specs_value = {};
                              specs_value["specs_value_id"] = vvi;
                              specs_value["specs_value_name"] = vvv;
                              return specs_value;
                            });
                          }
                        });
                        return goods_specs;
                  }else{
                      data.goods_content.spec_value = [];
                  }
                });
                data.goods_map_spec = goods_map_spec;
              }else {
                data.goods_map_spec = [];
              }

              // 虚拟商品限购时间和数量
              if (data.goods_content.is_virtual == '1') {
                  data.goods_content.virtual_indate_str = unixTimeToDateString(data.goods_content.virtual_indate, true);
                  data.goods_content.buyLimitation = buyLimitation(data.goods_content.virtual_limit, data.goods_content.upper_limit);
              }

              // 预售发货时间
              if (data.goods_content.is_presell == '1') {
                  data.goods_content.presell_deliverdate_str = unixTimeToDateString(data.goods_content.presell_deliverdate);
              }

              //渲染模板
              var html = template.render('product_detail', data);
              $("#product_detail_html").html(html);


					//渲染模板
					var html = template.render('product_detail_sepc', data);
					$("#product_detail_spec_html").html(html);

					//渲染模板
//					var html = template.render('voucher_script', data);
//					$("#voucher_html").html(html);

					if (data.goods_content.is_virtual == '1') {
						store_id = data.store_info.store_id;
						virtual();
					}

					// 购物车中商品数量
					if (getCookie('cart_count')) {
						if (getCookie('cart_count') > 0) {
							$('#cart_count,#cart_count1').html('<sup>' + getCookie('cart_count') + '</sup>');
						}
					}

	
					//商品描述
					$(".pddcp-arrow").click(function() {
						$(this).parents(".pddcp-one-wp").toggleClass("current");
					});
					//规格属性
					var myData = {};
					myData["spec_list"] = data.spec_list;
					$(".spec a").click(function() {
						var self = this;
						arrowClick(self, myData);
					});

					//购买数量，减
					$(".minus").click(function() {
						var buynum = $(".buy-num").val();
						if (buynum > 1) {
							$(".buy-num").val(parseInt(buynum - 1));
						}
					});
					//购买数量加
					$(".add").click(function() {
						var buynum = parseInt($(".buy-num").val());
						if (buynum < data.goods_content.goods_storage) {
							$(".buy-num").val(parseInt(buynum + 1));
						}
					});
					// 一个F码限制只能购买一件商品 所以限制数量为1
					if (data.goods_content.is_fcode == '1') {
						$('.minus').hide();
						$('.add').hide();
						$(".buy-num").attr('readOnly', true);
					}
					//收藏
					$(".pd-collect").click(function() {
						if ($(this).hasClass('favorate')) {
							if (dropFavoriteGoods(goods_id)) $(this).removeClass('favorate');
						} else {
							if (favoriteGoods(goods_id)) $(this).addClass('favorate');
						}
					});
					//加入购物车
              $("#add-cart").click(function (){
                var key = getCookie('key');//登录标记
                var quantity = parseInt($(".buy-num").val());
                 if(!key){
                     var goods_content = decodeURIComponent(getCookie('goods_cart'));
                     if (goods_content == null) {
                         goods_content = '';
                     }
                     if(goods_id<1){
                         show_tip();
                         return false;
                     }
                     var cart_count = 0;
                     if(!goods_content){
                         goods_content = goods_id+','+quantity;
                         cart_count = 1;
                     }else{
                         var goodsarr = goods_content.split('|');
                         for (var i=0; i<goodsarr.length; i++) {
                             var arr = goodsarr[i].split(',');
                             if(contains(arr,goods_id)){
                                 show_tip();
                                 return false;
                             }
                         }
                         goods_content+='|'+goods_id+','+quantity;
                         cart_count = goodsarr.length;
                     }
                     // 加入cookie
                     addCookie('goods_cart',goods_content);
                     // 更新cookie中商品数量
                     addCookie('cart_count',cart_count);
                     show_tip();
                     getCartCount();
                     $('#cart_count,#cart_count1').html('<sup>'+cart_count+'</sup>');
                     return false;
                 }else{
                    $.ajax({
                       url:ApiUrl+"/index.php?w=member_cart&t=cart_add",
                       data:{key:key,goods_id:goods_id,quantity:quantity},
                       type:"post",
                       success:function (result){
                          var rData = $.parseJSON(result);
                          if(checkLogin(rData.login)){
                            if(!rData.datas.error){
                                show_tip();
                                // 更新购物车中商品数量
                                delCookie('cart_count');
                                getCartCount();
                                $('#cart_count,#cart_count1').html('<sup>'+getCookie('cart_count')+'</sup>');
                            }else{
                              $.sDialog({
                                skin:"red",
                                content:rData.datas.error,
                                okBtn:false,
                                cancelBtn:false
                              });
                            }
                          }
                       }
                    })
                 }
              });

              //立即购买
              if (data.goods_content.is_virtual == '1') {
                  $("#buy-now").click(function() {
                      var key = getCookie('key');//登录标记
                      if (!key) {
						//v5.2 添加登录后，返回商品页
						addCookie('redirect_uri','/html/product_detail.html?goods_id='+goods_id);
                        window.location.href = WapSiteUrl+'/html/member/login.html';
                        return false;
                      }

                      var buynum = parseInt($('.buy-num').val()) || 0;

                      if (buynum < 1) {
                            $.sDialog({
                                skin:"red",
                                content:'参数错误！',
                                okBtn:false,
                                cancelBtn:false
                            });
                          return;
                      }
                      if (buynum > data.goods_content.goods_storage) {
                            $.sDialog({
                                skin:"red",
                                content:'库存不足！',
                                okBtn:false,
                                cancelBtn:false
                            });
                          return;
                      }

                      // 虚拟商品限购数量
                      if (data.goods_content.buyLimitation > 0 && buynum > data.goods_content.buyLimitation) {
                            $.sDialog({
                                skin:"red",
                                content:'超过限购数量！',
                                okBtn:false,
                                cancelBtn:false
                            });
                          return;
                      }

                      var json = {};
                      json.key = key;
                      json.cart_id = goods_id;
                      json.quantity = buynum;
                      $.ajax({
                          type:'post',
                          url:ApiUrl+'/index.php?w=member_vr_buy&t=buy_step1',
                          data:json,
                          dataType:'json',
                          success:function(result){
                              if (result.datas.error) {
                                  $.sDialog({
                                      skin:"red",
                                      content:result.datas.error,
                                      okBtn:false,
                                      cancelBtn:false
                                  });
                              } else {
                                  location.href = WapSiteUrl+'/html/order/vr_buy_step1.html?goods_id='+goods_id+'&quantity='+buynum;
                              }
                          }
                      });
                  });
              } else {
                  $("#buy-now").click(function (){
                     var key = getCookie('key');//登录标记
                     if(!key){
						//v5.2 添加登录后，返回商品页
						addCookie('redirect_uri','/html/product_detail.html?goods_id='+goods_id);
                        window.location.href = WapSiteUrl+'/html/member/login.html';
                     }else{
                         var buynum = parseInt($('.buy-num').val()) || 0;

                      if (buynum < 1) {
                            $.sDialog({
                                skin:"red",
                                content:'参数错误！',
                                okBtn:false,
                                cancelBtn:false
                            });
                          return;
                      }
                      if (buynum > data.goods_content.goods_storage) {
                            $.sDialog({
                                skin:"red",
                                content:'库存不足！',
                                okBtn:false,
                                cancelBtn:false
                            });
                          return;
                      }

                        var json = {};
                        json.key = key;
                        json.cart_id = goods_id+'|'+buynum;
                        $.ajax({
                            type:'post',
                            url:ApiUrl+'/index.php?w=member_buy&t=buy_step1',
                            data:json,
                            dataType:'json',
                            success:function(result){
                                if (result.datas.error) {
                                    $.sDialog({
                                        skin:"red",
                                        content:result.datas.error,
                                        okBtn:false,
                                        cancelBtn:false
                                    });
                                }else{
                                    location.href = WapSiteUrl+'/html/order/buy_step1.html?goods_id='+goods_id+'&buynum='+buynum;
                                }
                            }
                        });
                     }
                  });

              }

            }else {

              $.sDialog({
                  content: data.error + '！<br>请返回上一页继续操作…',
                  okBtn:false,
                  cancelBtnText:'返回',
                  cancelFn: function() { history.back(); }
              });
            }

            //验证购买数量是不是数字
            $("#buynum").blur(buyNumer);
            

            // 从下到上动态显示隐藏内容
            $.animationUp({
                valve : '.animation-up,#goods_spec_selected',          // 动作触发
                wrapper : '#product_detail_spec_html',    // 动作块
                scroll : '#product_roll',     // 滚动块，为空不触发滚动
                start : function(){       // 开始动作触发事件
                    $('.goods-detail-foot').addClass('hide').removeClass('block');
                },
                close : function(){        // 关闭动作触发事件
                    $('.goods-detail-foot').removeClass('hide').addClass('block');
                }
            });
            
            $.animationUp({
                valve : '#getVoucher',          // 动作触发
                wrapper : '#voucher_html',    // 动作块
                scroll : '#voucher_roll',     // 滚动块，为空不触发滚动
            });

            $('#voucher_html').on('click', '.btn', function(){
                getFreeVoucher($(this).attr('data-tid'));
            });
            
            // 联系客服
            $('.kefu').click(function(){
				if (data.store_info.node_chat) {
					 window.location.href = WapSiteUrl+'/html/member/chat_info.html?goods_id=' + goods_id + '&t_id=' + result.datas.store_info.member_id;
				}else{
                	window.location.href = "http://wpa.qq.com/msgrd?v=3&uin=" + result.datas.store_info.store_qq + "&site=qq&menu=yes";
            	}	 
				
				
                
				
            })
			}
		});
	}
});