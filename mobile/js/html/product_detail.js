var goods_id = getQueryString("goods_id");
var map_list = [];
var map_index_id = '';
var store_id;
var wx_share = 0;
var ua = navigator.userAgent.toLowerCase();
if (ua.indexOf('micromessenger') > -1) {
    wx_share = 1;
    loadJs("https://res.wx.qq.com/open/js/jweixin-1.2.0.js");
}
$(function (){
    var key = getCookie('key');
	addCookie('redirect_uri','/html/product_detail.html?goods_id='+goods_id);
    var unixTimeToDateString = function(ts, ex) {
        ts = parseFloat(ts) || 0;
        if (ts < 1) {
            return '';
        }
        var d = new Date();
        d.setTime(ts * 1e3);
        var s = '' + d.getFullYear() + '-' + (1 + d.getMonth()) + '-' + d.getDate();
        if (ex) {
            s += ' ' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
        }
        return s;
    };

    var buyLimitation = function(a, b) {
        a = parseInt(a) || 0;
        b = parseInt(b) || 0;
        var r = 0;
        if (a > 0) {
            r = a;
        }
        if (b > 0 && r > 0 && b < r) {
            r = b;
        }
        return r;
    };

    template.helper('isEmpty', function(o) {
        for (var i in o) {
            return false;
        }
        return true;
    });

     // 图片轮播
    function picSwipe(){
      var elem = $("#mySwipe")[0];
      window.mySwipe = Swipe(elem, {
        continuous: false,
        // disableScroll: true,
        stopPropagation: true,
        callback: function(index, element) {
          $('.goods-detail-turn').find('li').eq(index).addClass('cur').siblings().removeClass('cur');
        }
      });
    }
    get_detail(goods_id);
  //点击商品规格，获取新的商品
  function arrowClick(self,myData){
    $(self).addClass("current").siblings().removeClass("current");
    //拼接属性
    var curEle = $(".spec").find("a.current");
    var curSpec = [];
    $.each(curEle,function (i,v){
        // convert to int type then sort
        curSpec.push(parseInt($(v).attr("specs_value_id")) || 0);
    });
    var spec_string = curSpec.sort(function(a, b) { return a - b; }).join("|");
    //获取商品ID
    goods_id = myData.spec_list[spec_string];
    get_detail(goods_id);
  }

  function contains(arr, str) {//检测goods_id是否存入
	    var i = arr.length;
	    while (i--) {
           if (arr[i] === str) {
	           return true;
           }
	    }
	    return false;
	}
  $.sValid.init({
        rules:{
            buynum:"digits"
        },
        messages:{
            buynum:"请输入正确的数字"
        },
        callback:function (eId,eMsg,eRules){
            if(eId.length >0){
                var errorHtml = "";
                $.map(eMsg,function (idx,item){
                    errorHtml += "<p>"+idx+"</p>";
                });
                $.sDialog({
                    skin:"red",
                    content:errorHtml,
                    okBtn:false,
                    cancelBtn:false
                });
            }
        }
    });
  //检测商品数目是否为正整数
  function buyNumer(){
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

              if (data.goods_content.is_virtual == '0') {
            	  $('.goods-detail-o2o').remove();
              }
    
              //渲染模板
              var html = template.render('product_detail_sepc', data);
              $("#product_detail_spec_html").html(html);
                    if (data.goods_content.pingou_sale == '1') {
                        var log_id = getQueryString("log_id");
                        if (log_id) $(".cart_pingou_sale").html('参团');
                    }

              //渲染模板
              var html = template.render('voucher_script', data);
              $("#voucher_html").html(html);
	      var html = template.render('product_title', data);
	      $("head").append(html);
              if (data.goods_content.is_virtual == '1') {
            	  store_id = data.store_info.store_id;
            	  virtual();
              }
                    if (data.goods_content.pingou_sale == '1') {
                        takeCount();
              }
  
              // 购物车中商品数量
              if (getCookie('cart_count')) {
                  if (getCookie('cart_count') > 0) {
                      $('#cart_count,#cart_count1').html('<sup>'+getCookie('cart_count')+'</sup>');
                  }
              }

              //图片轮播
              picSwipe();
              //商品描述
              $(".pddcp-arrow").click(function (){
                $(this).parents(".pddcp-one-wp").toggleClass("current");
              });
              //规格属性
              var myData = {};
              myData["spec_list"] = data.spec_list;
              $(".spec a").click(function (){
                var self = this;
                arrowClick(self,myData);
              });
              //购买数量，减
              $(".minus").click(function (){
                 var buynum = $(".buy-num").val();
                 if(buynum >1){
                    $(".buy-num").val(parseInt(buynum-1));
                 }
              });
              //购买数量加
              $(".add").click(function (){
                 var buynum = parseInt($(".buy-num").val());
                 if(buynum < data.goods_content.goods_storage){
                    $(".buy-num").val(parseInt(buynum+1));
                 }
              });
              // 一个F码限制只能购买一件商品 所以限制数量为1
              if (data.goods_content.is_fcode == '1') {
                  $('.minus').hide();
                  $('.add').hide();
                  $(".buy-num").attr('readOnly', true);
              }
              //收藏
              $(".pd-collect").click(function (){
                  if ($(this).hasClass('favorate')) {
                      if (dropFavoriteGoods(goods_id)) $(this).removeClass('favorate');
                  } else {
                      if (favoriteGoods(goods_id)) $(this).addClass('favorate');
                  }
              });
			   //分享
			   var to_url = encodeURIComponent(location.href.split('#')[0]);
			   var logo_wx = data.goods_image[0];
			   var share_title = encodeURIComponent(data.goods_content.goods_name);
				var weibo_url =	'http://service.weibo.com/share/share.php?url='+to_url+'&title='+share_title+'&pic='+logo_wx+'&appkey=';
				var qq_url = 'http://connect.qq.com/widget/shareqq/index.html?url='+to_url+'&title='+share_title+'&source='+share_title+'&desc=&pics='+logo_wx;
				var qzone_url = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+to_url+'&title='+share_title+'&desc=&summary=&site='+share_title+'&pics='+logo_wx;
				var douban_url = 'http://shuo.douban.com/!service/share?href='+to_url+'&name='+share_title+'&text=&image='+logo_wx+'&starid=0&aid=0&style=11';
              $(".pd-share").click(function (){
				     var share_html =' <div class="invite-txt">登录后分享得积分哦</div>'
						+'<div class="invite-cetner">'
						+'<div class="social-share">'
						+'	<a id="wx-sha" href="javascript:;" class="social-share-icon icon-weixin"></a>'
						+'	<a id="qq_url" class="social-share-icon icon-qq" href="javascript:;" ></a>'
						+'	<a id="qzone_url" class="social-share-icon icon-qzone" href="javascript:;" ></a>'
						//+'	<a id="wx-qrc" class="social-share-icon icon-wechat" href="javascript:;" tabindex="-1"><div class="wechat-qrcode"><h4>微信扫一扫：分享</h4><div class="qrcode"><img id="wx_qrcode" src=""></div><div class="help"><p>微信里点“发现”，扫一下</p><p>二维码便可将本文分享至朋友圈。</p></div></div></a>'
						+'	<a id="weibo_url" class="social-share-icon icon-weibo" href="javascript:;" ></a>'
						+'	<a id="douban_url" class="social-share-icon icon-douban" href="javascript:;" ></a>'
						+'</div>'
						+'</div>';
				  $.sDialog({
						skin:"red",
						content: share_html,
						"cancelBtn":false,
						"lock":true
					});
				  return;
              });
            $("#wx-sha").live("click", function(){
				$('#mcover').html("<img src= '../images/tishi.png'>");
				$('#mcover').show();
			});
			  $("#qq_url").live("click", function(){
				  $.ajax({
					url: ApiUrl + "/index.php?w=sharepoint&gid="+data.goods_content.goods_id,
					data:{key:key},
					dataType: 'json',
					success: function (result) {
						location.href=qq_url;}
					});
				});
			  $("#qzone_url").live("click", function(){
				  $.ajax({
					url: ApiUrl + "/index.php?w=sharepoint&gid="+data.goods_content.goods_id,
					data:{key:key},
					dataType: 'json',
					success: function (result) {
						location.href=qzone_url;}
					});});
			  $("#weibo_url").live("click", function(){
				  $.ajax({
					url: ApiUrl + "/index.php?w=sharepoint&gid="+data.goods_content.goods_id,
					data:{key:key},
					dataType: 'json',
					success: function (result) {
						location.href=weibo_url;}
					});});
			  $("#douban_url").live("click", function(){
				  $.ajax({
					url: ApiUrl + "/index.php?w=sharepoint&gid="+data.goods_content.goods_id,
					data:{key:key},
					dataType: 'json',
					success: function (result) {
						location.href=douban_url;}
					});});
				
			$("#rule_link").click(function(){
            var con = $("#rule_info").html();
				$.sDialog({
					content: con,
					"width": 100,
					"height": 100,
					"cancelBtn": false,
					"lock": true
				});
        	});
			  
			  
              //加入购物车
              $("#add-cart").click(function (){
                var key = getCookie('key');//登录标记
                var quantity = parseInt($(".buy-num").val());
						var lowest = parseInt($(".lowestnum").val());
							if(quantity<lowest)
							{
                                $.sDialog({
                                    skin: "red",
                                    content: "购买数量低于起批数量",
                                    okBtn: false,
                                    cancelBtn: false
                                });	
								return false;
							}
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
                        var buy_pingou = 0;
                        var log_id = getQueryString("log_id");
                        var buyer_id = getQueryString("buyer_id");
                        function cart_buy() {
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
						 // 拼团商品限购数量
                      if (buy_pingou == 1 &&data.goods_content.goods_maxnum > 0 && buynum > data.goods_content.goods_maxnum) {
                            $.sDialog({
                                skin:"red",
                                content:'购买数量不能超过'+data.goods_content.goods_maxnum,
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
                                            var u = WapSiteUrl + '/html/order/buy_step1.html?goods_id=' + goods_id + '&buynum=' + buynum;
                                            if (buy_pingou) u += '&pingou=1&log_id=' + log_id + '&buyer_id=' + buyer_id;
                                            location.href = u;
                                        }
                                    }
                                });
                            }
                        }
                        $("#buy-now").click(function () {cart_buy();});
                        if (data.goods_content.pingou_sale == '1') {
                            $(".pingou_sale .invite-btn").click(function () {cart_buy();});
                            $(".pingou_sale .order-btn").click(function () {
                                buy_pingou = 1;
                                cart_buy();
                            });
                        }
              }
			  if (wx_share) {
					var url_wx = location.href.split('#')[0];
					var _str = url_wx+'@@@'+data.goods_content.goods_name+'@@@'+data.goods_image[0]+'@@@'+data.goods_content.goods_name;
					$.ajax({
						url: ApiUrl + "/index.php?w=wx_share&str="+encodeURIComponent(_str)+"&goods_id="+data.goods_content.goods_id,
						data:{key:key},
						dataType: 'script',
						success: function (result) {
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
  
  $.scrollTransparent();
  $('#product_detail_html').on('click', '#get_area_selected', function(){
      $.areaSelected({
          success : function(data){
              $('#get_area_selected_name').html(data.area_info);
              var area_id = data.area_id_2 == 0 ? data.area_id_1:data.area_id_2;
              $.getJSON(ApiUrl + '/index.php?w=goods&t=calc', {goods_id:goods_id,area_id:area_id},function(result){
                  $('#get_area_selected_whether').html(result.datas.if_store_cn);
                  $('#get_area_selected_content').html(result.datas.content);
                  if (!result.datas.if_store) {
                      $('.buy-handle').addClass('no-buy');
                  } else {
                      $('.buy-handle').removeClass('no-buy');
                  }
              });
          }
      });
  });
  
  $('body').on('click', '#goodsBody,#goodsBody1', function(){
      window.location.href = WapSiteUrl+'/html/product_info.html?goods_id=' + goods_id;
  });
  $('body').on('click', '#goodsEvaluation,#goodsEvaluation1', function(){
      window.location.href = WapSiteUrl+'/html/product_eval_list.html?goods_id=' + goods_id;
  });

  $('#list-address-scroll').on('click','dl > a',map);
  $('#map_all').on('click',map);
});


function show_tip() {
    var flyer = $('.goods-pic > img').clone().css({'z-index':'999','height':'3rem','width':'3rem'});
    flyer.fly({
        start: {
            left: $('.goods-pic > img').offset().left,
            top: $('.goods-pic > img').offset().top-$(window).scrollTop()
        },
        end: {
            left: $("#cart_count1").offset().left+40,
            top: $("#cart_count1").offset().top-$(window).scrollTop(),
            width: 0,
            height: 0
        },
        onEnd: function(){
            flyer.remove();
        }
    });
}

function virtual() {
	$('#get_area_selected').parents('.goods-detail-item').remove();
    $.getJSON(ApiUrl + '/index.php?w=goods&t=store_o2o_addr', {store_id:store_id},function(result){
    	if (!result.datas.error) {
    		if (result.datas.addr_list.length > 0) {
    	    	$('#list-address-ul').html(template.render('list-address-script',result.datas));
    	    	map_list = result.datas.addr_list;
    	    	var _html = '';
    	    	_html += '<dl index_id="0">';
    	    	_html += '<dt>'+ map_list[0].name_info +'</dt>';
    	    	_html += '<dd>'+ map_list[0].address_info +'</dd>';
    	    	_html += '</dl>';
    	    	_html += '<p><a href="tel:'+ map_list[0].phone_info +'"></a></p>';
    	    	$('#goods-detail-o2o').html(_html);

    	    	$('#goods-detail-o2o').on('click','dl',map);

    	    	if (map_list.length > 1) {
    	    		$('#store_addr_list').html('查看全部'+map_list.length+'家分店地址');
    	    	} else {
    	    		$('#store_addr_list').html('查看商家地址');
    	    	}
    	    	$('#map_all > em').html(map_list.length);    			
    		} else {
    			$('.goods-detail-o2o').hide();
    		}
    	}
    });
    $.animationLeft({
        valve : '#store_addr_list',
        wrapper : '#list-address-wrapper',
        scroll : '#list-address-scroll'
    });
}

function map() {
	  $('#map-wrappers').removeClass('hide').removeClass('right').addClass('left');
	  $('#map-wrappers').on('click', '.header-l > a', function(){
		  $('#map-wrappers').addClass('right').removeClass('left');
	  });
	  $('#baidu_map').css('width', document.body.clientWidth);
	  $('#baidu_map').css('height', document.body.clientHeight);
	  map_index_id = $(this).attr('index_id');
	  if (typeof map_index_id != 'string'){
		  map_index_id = '';
	  }
	  if (typeof(map_js_flag) == 'undefined') {
	      $.ajax({
	          url: WapSiteUrl+'/js/map.js',
	          dataType: "script",
	          async: false
	      });
	  }
	if (typeof BMap == 'object') {
	    baidu_init();
	} else {
	    load_script();
	}
}
function talert(){
  var key2 = getCookie('key');
  $.ajax({
    url: ApiUrl + "/index.php?w=sharepoint&gid="+goods_id,
    type: 'get',
    data:{key:key2},
    dataType: 'json',
    success: function(result) {
     }
  });
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
			if(days>9){
			  obj.find("[time_id='d']").html('9+');
			}else{
			 obj.find("[time_id='d']").html(days);
			}
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