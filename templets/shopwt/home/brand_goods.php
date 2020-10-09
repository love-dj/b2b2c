<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<script>
var PURL = [<?php echo $output['purl'];?>];
</script>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/layout.css" rel="stylesheet" type="text/css">
<script src="<?php echo STATIC_SITE_URL.'/js/search_goods.js';?>"></script>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/brand.css" rel="stylesheet" type="text/css">
<div class="brand-info">
<div class="bd">
        <div class="wrapper pos_r">
            <div class="infor">
            <div class="infor_bg"></div>
                <div class="in_div">
                    <div class="top"><img alt="" src="<?php echo brandImage($output['brand_int']['brand_pic']);?>"></div>
                    <p class="tit_p"><?php echo $output['brand_int']['brand_name'];?></p>
                    <div class="cen_d more"><?php echo $output['brand_int']['brand_introduction'];?></div>
                    <div class="tt x_jt"></div>
                    <div class="bott_d clearfix">
                        <div class="guojia">在售商品<span><?php if(!empty($output['goods_num'])){;?><?php echo $output['goods_num']?><?php }else{?>0<?php }?></span>个</div>
                        <div class="guanzhu">
                            <p class="top_p"><?php echo $output['brand_int']['brand_view'];?></p>
                            <p data-bid="<?php echo $output['brand_int']['brand_id'];?>" class="bot_p"><a href="javascript:;">关注该品牌</a></p>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        </div>
        <script>
            $(document).ready(function(){
                $(".tt").toggle(function(){
                        $(this).removeClass("x_jt");
                        $(this).addClass("s_jt");
                        $(".cen_d").removeClass("more")
                        $(".cen_d").addClass("h_124")
                    },
                    function(){
                        $(this).removeClass("s_jt");
                        $(this).addClass("x_jt");
                        $(".cen_d").removeClass("h_124")
                        $(".cen_d").addClass("more")


                    }
                );
            });
	//关注品牌
    $(document).ready(function(){
        $('.bot_p').click(function(){
            if ($(this).hasClass("favorate")) {
                $(this).removeClass("favorate");
                var a = $(this).parent().find('.top_p').text();
                $(this).parent().find('.top_p').text(parseInt(a)-1);

            } else {
                $(this).addClass("favorate");
                var a = $(this).parent().find('.top_p').text();
                $(this).parent().find('.top_p').text( parseInt(a)+1);

            }

        });
    });
        </script>
        <ul>
            <li class="cent_main_t"><img width="1920" height="100%" alt="" src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_BRAND.'/'.$output['brand_int']['brand_bgpic'];?>"></li>
        </ul>
    
</div>
</div>

<div class="container wrapper">
    <div class="shop_con_list" id="main-list-bar">
      <nav class="sort-bar" id="main-nav">
        <div class="wth-sortbar-array">
          <ul class="screen">
            <li <?php if(!$_GET['key']){?>class="selected"<?php }?>><a href="<?php echo dropParam(array('order', 'key'));?>" class="nobg" title="<?php echo $lang['brand_index_default_sort'];?>"><?php echo $lang['brand_index_default'];?></a></li>
            <li <?php if($_GET['key'] == '1'){?>class="selected"<?php }?>><a href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '1') ? replaceParam(array('key' => '1', 'order' => '1')):replaceParam(array('key' => '1', 'order' => '2')); ?>" <?php if($_GET['key'] == '1'){?>class="<?php echo $_GET['order'] == 1 ? 'asc' : 'desc';?>"<?php }?> title="<?php echo ($_GET['order'] == 'desc' && $_GET['key'] == '1')?$lang['brand_index_sold_asc']:$lang['brand_index_sold_desc']; ?>"><?php echo $lang['brand_index_sold'];?><i></i></a></li>
            <li <?php if($_GET['key'] == '2'){?>class="selected"<?php }?>><a href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '2') ? replaceParam(array('key' => '2', 'order' => '1')):replaceParam(array('key' => '2', 'order' => '2')); ?>" <?php if($_GET['key'] == '2'){?>class="<?php echo $_GET['order'] == 1 ? 'asc' : 'desc';?>"<?php }?> title="<?php  echo ($_GET['order'] == 'desc' && $_GET['key'] == '2')?$lang['brand_index_click_asc']:$lang['brand_index_click_desc']; ?>"><?php echo $lang['brand_index_click']?><i></i></a></li>
            <li <?php if($_GET['key'] == '3'){?>class="selected"<?php }?>><a href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '3') ? replaceParam(array('key' => '3', 'order' => '1')):replaceParam(array('key' => '3', 'order' => '2')); ?>" <?php if($_GET['key'] == '3'){?>class="<?php echo $_GET['order'] == 1 ? 'asc' : 'desc';?>"<?php }?> title="<?php echo ($_GET['order'] == 'desc' && $_GET['key'] == '3')?$lang['brand_index_price_asc']:$lang['brand_index_price_desc']; ?>"><?php echo $lang['brand_index_price'];?><i></i></a></li>
          </ul>
        </div>
        <div class="wth-bar-filter" wt_type="more-filter">
        <span class="arrow"></span>
          <ul>
            <li><a href="<?php if ($_GET['type'] == 1) { echo dropParam(array('type'));} else { echo replaceParam(array('type' => '1'));}?>" <?php if ($_GET['type'] == 1) {?>class="selected"<?php }?>><i></i>平台自营</a></li>
            <li><a href="<?php if ($_GET['gift'] == 1) { echo dropParam(array('gift'));} else { echo replaceParam(array('gift' => '1'));}?>" <?php if ($_GET['gift'] == 1) {?>class="selected"<?php }?>><i></i>赠品</a></li>
            <!-- 消费者保障服务 -->
            <?php if($output['contract_item']){?>
            <?php foreach($output['contract_item'] as $citem_k=>$citem_v){ ?>
            <li><a href="<?php if (in_array($citem_k,$output['search_ci_arr'])){ echo removeParam(array('ci' => $citem_k));} else { echo replaceParam(array("ci" => $output['search_ci_str'].$citem_k));}?>" <?php if (in_array($citem_k,$output['search_ci_arr'])) {?>class="selected"<?php }?>><i></i><?php echo $citem_v['cti_name']; ?></a></li>
            <?php }?>
            <?php }?>
          </ul>
        </div>
   <!-- 所在地 -->
        <div class="wth-sortbar-location">
        <div class="wts-logistics">
        <div id="wts-freight" class="fl">所在地：</div>
            <div id="wts-freight-selector" class="wts-freight-select">
              <div class="text">
                <div class="region"><?php echo $output['deliver_region'] ? str_replace(' ','',$output['deliver_region']) : '请选择地区'?></div>
                <b>∨</b> </div>
              <div class="content">
                <div id="wts-stock" class="wts-stock" data-widget="tabs">
                  <div class="mt">
                    <ul class="tab">
                      <li data-index="0" data-widget="tab-item" class="curr"><a href="#none" class="hover"><em>请选择</em><i> ∨</i></a></li>
                    </ul>
                  </div>
                  <div id="stock_province_item" data-widget="tab-content" data-area="0">
                    <ul class="area-list">
                    </ul>
                  </div>
                  <div id="stock_city_item" data-widget="tab-content" data-area="1" style="display: none;">
                    <ul class="area-list">
                    </ul>
                  </div>
                  <div id="stock_area_item" data-widget="tab-content" data-area="2" style="display: none;">
                    <ul class="area-list">
                    </ul>
                  </div>
                </div>
                <a class="set allarea" href="<?php echo replaceParam(array('area_id' => 0,'areaid_1' => 0));?>">不限</a>
              </div>
              <a href="javascript:;" class="close" onclick="$('#wts-freight-selector').removeClass('hover')">关闭</a> </div>
              </div>
         </div>
        <!-- 所在地  -->
        <div class="pagination"> <?php echo $output['show_page1']; ?> </div>
      </nav>

      <!-- 商品列表循环  -->
      <?php require_once (BASE_TPL_PATH.'/home/goods.squares.php');?>
      <div class="tc mt20 mb20">
        <div class="pagination"> <?php echo $output['show_page']; ?> </div>
      </div>
   

    <!-- 猜你喜欢 -->
    <div id="guesslike_div" style="width:1200px;"></div>
  </div>
</div>
<script src="<?php echo STATIC_SITE_URL;?>/js/waypoints.js"></script> 
<script src="<?php echo STATIC_SITE_URL;?>/js/search_category_menu.js"></script> 
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/fly/jquery.fly.min.js" charset="utf-8"></script> 
<!--[if lt IE 10]>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/fly/requestAnimationFrame.js" charset="utf-8"></script>
<![endif]-->
<script src="<?php echo STATIC_SITE_URL;?>/js/waypoints.js"></script>
<script>
$(function(){
 //浮动导航  waypoints.js
    $('#main-list-bar').waypoint(function(event, direction) {
        $(this).parent().toggleClass('sticky', direction === "down");
        event.stopPropagation();
    });
    //浏览历史处滚条
	$('#wthSidebarViewed').perfectScrollbar({suppressScrollX:true});
  	//猜你喜欢
	$('#guesslike_div').load('<?php echo urlShop('search', 'get_guesslike', array()); ?>', function(){
        $(this).show();
    });

    //复选框筛选
    $("[wt_type='more-filter']").mouseover(function(){
        $("[wt_type='more-filter']").addClass('box-hover');
    });
    $("[wt_type='more-filter']").mouseout(function(){
        $("[wt_type='more-filter']").removeClass('box-hover');
    });
	
	
//地区显示
var $cur_area_list,$cur_tab,next_tab_id = 0,cur_select_area = [],calc_area_id = '',calced_area = [],cur_select_area_ids = [];
$("#wts-freight-selector").hover(function() {
		if (typeof wt_a === "undefined") {
	 		$.getJSON(SITEURL + "/index.php?w=index&t=search_json_area&callback=?", function(data) {
	 			wt_a = data;
	 			$cur_tab = $('#wts-stock').find('li[data-index="0"]');
	 			_loadArea(0,data,0);
	 		});
		}
		$(this).addClass("hover");
		$(this).on('mouseleave',function(){
			$(this).removeClass("hover");
		});
	});
	
	$('#stock_province_item ul[class="area-list"]').on('click','a',function(){
            var $this = $(this),
            areaId = $this.data("value"),
            areaDeep = "1";
            $.cookie("wt" + (areaDeep - 1), areaId + "," + areaDeep + "," + $this.html(), {
            	expires: 30,
                path: '/'
           });
		
		});
	
$('ul[class="area-list"]').on('click','a',function(){
		$('#wts-freight-selector').unbind('mouseleave');
		var tab_id = parseInt($(this).parents('div[data-widget="tab-content"]:first').attr('data-area'));
		if (tab_id == 0) {cur_select_area = [];cur_select_area_ids = []};
		if (tab_id == 1 && cur_select_area.length > 1) {
			cur_select_area.pop();
			cur_select_area_ids.pop();
			if (cur_select_area.length > 1) {
				cur_select_area.pop();
				cur_select_area_ids.pop();
			}
		}
		next_tab_id = tab_id + 1;
		var area_id = $(this).attr('data-value');
		$cur_tab = $('#wts-stock').find('li[data-index="'+tab_id+'"]');
		$cur_tab.find('em').html($(this).html());
		$cur_tab.find('i').html(' ∨');
		if (tab_id < 2) {
			calc_area_id = area_id;
			cur_select_area.push($(this).html());
			cur_select_area_ids.push(area_id);
			$cur_tab.find('a').removeClass('hover');
			$cur_tab.nextAll().remove();
			{
				var qstr='<?php echo getParamStr();?>';
    	 		$.getJSON(SITEURL + '/index.php?w=index&t=search_json_area&area_id='+calc_area_id+'&qstr='+qstr+'&callback=?', function(data) {
    	 			wt_a = data;
    	 			_loadArea(area_id,data,tab_id);
    	 		});
			}
		} else {
			//点击第三级，不需要显示子分类
			if (cur_select_area.length == 3) {
				cur_select_area.pop();
				cur_select_area_ids.pop();
			}
			cur_select_area.push($(this).html());
			cur_select_area_ids.push(area_id);
			$('#wts-freight-selector > div[class="text"] > div').html(cur_select_area.join(''));
			$('#wts-freight-selector').removeClass("hover");
			$.cookie("wt1", "1");
			_calc();
			
		}
		$('#wts-stock').find('li[data-widget="tab-item"]').on('click','a',function(){
			var tab_id = parseInt($(this).parent().attr('data-index'));
			if (tab_id < 2) {
				$(this).parent().nextAll().remove();
				$(this).addClass('hover');
				$('#wts-stock').find('div[data-widget="tab-content"]').each(function(){
					if ($(this).attr("data-area") == tab_id) {
						$(this).show();
					} else {
						$(this).hide();
					}
				});
			}
		});
	});
	function _loadArea(area_id,data,tab_id){
		if (data && data.length > 0) {
			$('#wts-stock').find('div[data-widget="tab-content"]').each(function(){
				if ($(this).attr("data-area") == next_tab_id) {
					$(this).show();
					$cur_area_list = $(this).find('ul');
					$cur_area_list.html('');
				} else {
					$(this).hide();
				}
			});
			var areas = [];
			areas = data;
			for (i = 0; i < areas.length; i++) {
				var url='#none';
				if(tab_id==1){  url=areas[i][2]; }
				if (areas[i][1].length > 8) {
					$cur_area_list.append("<li class='longer-area'><a data-value='" + areas[i][0] + "' href='"+url+"'>" + areas[i][1] + "</a></li>");
				} else {
				    $cur_area_list.append("<li><a data-value='" + areas[i][0] + "' href='"+url+"'>" + areas[i][1] + "</a></li>");
				}
			}
			if (area_id > 0){
				$cur_tab.after('<li data-index="' + (next_tab_id) + '" data-widget="tab-item"><a class="hover" href="#none" ><em>请选择</em><i> ∨</i></a></li>');
			}
		} else {
			//点击第一二级时，已经到了最后一级
			$cur_tab.find('a').addClass('hover');
			$('#wts-freight-selector > div[class="text"] > div').html(cur_select_area);
			$('#wts-freight-selector').removeClass("hover");
			_calc();
		}
	}
	//添加所在的记录
	function _calc() {
		$.cookie('dregion', cur_select_area_ids.join(' ')+'|'+cur_select_area.join(' '), { expires: 30 });
	}
	//清除地区记录
	$('.allarea').click(function(){
		$('.region').html("请选择地区");
		$.cookie("wt0", "0,1,全国", {
						expires: 30,
						path: '/'
					});
		$.cookie('dregion', '', { expires: -1 });
	});
	$('#area-all li').click(function(){
		$('.region').html("请选择地区");
		$.cookie('dregion', '', { expires: -1 });
	});
	
});
</script> 
