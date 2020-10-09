<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<script src="<?php echo STATIC_SITE_URL.'/js/search_goods.js';?>"></script>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/layout.css" rel="stylesheet" type="text/css">
 <div id="nav-search" class="wrapper wth-breadcrumb-box">
  <?php if(!empty($output['nav_link_list']) && is_array($output['nav_link_list'])){?>
      <?php foreach($output['nav_link_list'] as $nav_link){ if($nav_link['is_show']=='1'){continue;} ?>
          <?php if(!empty($nav_link['link'])){?>
		  <?php
					$model_goods_class = Model('goods_class');
					$nav_info = $model_goods_class->getGoodsClassList(array('gc_id'=>$nav_link['id']));
					$where = array();
					$where['gc_id'] = array('not in',$nav_link['id']);
					$where['gc_parent_id'] = $nav_info[0]['gc_parent_id'];
					$nav_list = $model_goods_class->getGoodsClassList($where);
		  ?>
    <div class="wth-breadcrumb">
     <span class="sort-box"> <a href="<?php echo $nav_link['link'];?>" class="current"><?php echo $nav_link['title'];?><i class="drop-arrow"></i></a>
     <?php if(!empty($nav_list)){?>
      <div class="sort-sub">
        <ul>
         <?php foreach($nav_list as $key => $val){ if($val['is_show']=='1'){continue;} ?>
         <li><a href="<?php echo urlShop('search', 'index', array('cate_id' => $val['gc_id'], 'keyword' => $_GET['keyword']));?>"><?php echo $val['gc_name']?></a></li>
         <?php }?> 
        </ul>
      </div>
      <?php }?>  
       </span><span class="arrow">&gt;</span>
       </div>
        <?php }?>
    <?php }?>
     <?php }?>
	 <?php if((isset($output['checked_brand']) && is_array($output['checked_brand'])) || (isset($output['checked_attr']) && is_array($output['checked_attr']))){?>
	 <div class="select-undo"><a href="<?php echo dropParam(array('a_id', 'b_id'));?>" class="delAll">全部撤销</a></div>
	 <div class="selected-box">
		<?php if(isset($output['checked_brand']) && is_array($output['checked_brand'])){?>
		<?php foreach ($output['checked_brand'] as $key=>$val){?>
		<a class="selected"  href="<?php echo removeParam(array('b_id' => $key));?>"><?php echo $lang['goods_class_index_brand'];?>: <em><?php echo $val['brand_name']?></em><i></i></a>
		<?php }?>
		<?php }?>

		 <?php if(isset($output['checked_attr']) && is_array($output['checked_attr'])){?>
		 <?php foreach ($output['checked_attr'] as $val){?>
		 <a class="selected"  href="<?php echo removeParam(array('a_id' => $val['attr_value_id']));?>"><?php echo $val['attr_name'].': <em>'.$val['attr_value_name'].'</em>'?><i></i></a>
		 <?php }?>
		 <?php }?>
		 </div>
	 <?php }?>	 
    <div class="clear"></div>
  </div>
<div class="wth-container wrapper">
 
 <!-- 分类下的推荐商品 -->
    <div id="gc_goods_recommend_div" class="wrapper"></div>
    <div class="wth-module">
      <div class="title">
        <h3>
          <?php if (!empty($output['show_keyword'])) {?>
          <em><?php echo $output['show_keyword'];?></em> -
          <?php }?>
          商品筛选<i>搜索到<?php echo $output['goods_num'];?>件相关商品</i></h3>
      </div>
      <div class="content">
        <div class="wth-module-filter">
<?php if($output['area_id']>0){ ?>
<dl>
   <dt><span>商品所在地<?php echo $lang['wt_colon'] ;?></span> </dt>
   	<dd class="list">
		 <ul>
			 <li><a class="allarea" href="<?php echo replaceParam(array('area_id' => 0));?>">不限></a> </li>
			<?php if($output['areadeep'] == 1) { ?>
			  <li><a href="<?php echo replaceParam(array('area_id' => $output['nowurl']));?>"><?php echo $output['areaname'];?>></a></li>
			<?php } elseif($output['areadeep'] == 2){?>
			 <li><a href="<?php echo replaceParam(array('area_id' => $output['parurl']));?>"><?php echo $output['parname'];?>></a></li>
			<li><a href="<?php echo replaceParam(array('area_id' => $output['nowurl']));?>"><?php echo $output['areaname'];?>></a></li>
			<?php } elseif($output['areadeep'] == 3){?>
			<li><a href="<?php echo replaceParam(array('area_id' => $output['ppurl']));?>"><?php echo $output['ppname'];?>></a></li>
			<li><a href="<?php echo replaceParam(array('area_id' => $output['parurl']));?>"><?php echo $output['parname'];?>></a></li>
			<li><a href="<?php echo replaceParam(array('area_id' => $output['nowurl']));?>"><?php echo $output['areaname'];?></a></li>
			<?php }?>
			<?php if (!empty($output['arealist'])) {?>
				<?php foreach ($output['arealist'] as $value) {?>
				  <li><a href="<?php echo replaceParam(array('area_id' => $value['area_id']));?>"><?php echo $value['area_name']?></a></li>
				 <?php }?>
			<?php }?>
		</ul>
     </dd>  
</dl>
     <?php }?>
<?php $dl=1;  //dl标记?>
     <?php if(!empty($output['goods_class_array']) and isset($output['goods_class_array'])):?>
          <dl>
            <dt><span>包含分类<?php echo $lang['wt_colon'] ;?></span> </dt>
            <dd class="list">
              <ul>
                <?php foreach ($output['goods_class_array'] as $key => $value) { if($value['is_show']=='1'){continue;} ?>
                  <li><a href="<?php echo replaceParam(array('cate_id' => $value['gc_id']));?>"><?php echo $value['gc_name']  ;?></a></li>
                <?php }?>
              </ul>
            </dd>  
          </dl>
          <?php endif;?>  
    <?php $dl=1; $num_dl = 1;?>
    <?php if(1){?>
          <?php if (!isset($output['checked_brand']) || empty($output['checked_brand'])){?>
          <?php if(!empty($output['brand_array']) && is_array($output['brand_array'])){?>
          <dl>
            <dt><?php echo $lang['goods_class_index_brand'].$lang['wt_colon'];?></dt>
            <dd class="list">
              <ul class="wth-brand-tab" wttype="ul_initial" style="display:none;">
                <li data-initial="all"><a href="javascript:void(0);">所有品牌<i class="arrow"></i></a></li>
                <?php if (!empty($output['initial_array'])) {?>
                <?php foreach ($output['initial_array'] as $val) {?>
                <li data-initial="<?php echo $val;?>"><a href="javascript:void(0);"><?php echo $val;?><i class="arrow"></i></a></li>
                <?php }?>
                <?php }?>
              </ul>
              <div id="wtBrandlist">
                <ul class="wth-brand-con" wttype="ul_brand">
                  <?php $i = 0;foreach ($output['brand_array'] as $k=>$v){$i++;?>
                  <li data-initial="<?php echo $v['brand_initial']?>" <?php if ($i > 14) {?>style="display:none;"<?php }?>><a href="<?php $b_id = (($_GET['b_id'] != '' && intval($_GET['b_id']) != 0)?$_GET['b_id'].'_'.$k:$k); echo replaceParam(array('b_id' => $b_id));?>">
                    <?php if ($v['show_type'] == 0) {?>
                    <img src="<?php echo brandImage($v['brand_pic']);?>" alt="<?php echo $v['brand_name'];?>" /> <span>
                    <?php  echo $v['brand_name'];?>
                    </span>
                    <?php } else { echo $v['brand_name'];?>
                    <?php }?>
                    </a></li>
                  <?php }?>
                </ul>
              </div>
            </dd>
            <?php if (count($output['brand_array']) > 16){?>
            <dd class="all"><span wttype="brand_show"><i class="icon-angle-down"></i><?php echo $lang['goods_class_index_more'];?></span></dd>
            <?php }?>
          </dl>
          <?php $dl++;$num_dl++;}?>
          <?php }?>
          <?php if(!empty($output['attr_array']) && is_array($output['attr_array'])){?>
          <?php $j = 0;foreach ($output['attr_array'] as $key=>$val){$j++;?>
          <?php if(!isset($output['checked_attr'][$key]) && !empty($val['value']) && is_array($val['value'])){?>          
          <dl <?php if($num_dl > 3){?>class="hide_dl" style="display: none;"<?php }?>>
            <dt><?php echo $val['name'].$lang['wt_colon'];?></dt>
            <dd class="list">
              <ul>
                <?php $i = 0;foreach ($val['value'] as $k=>$v){$i++;?>
                <li <?php if ($i>10){?>style="display:none" wt_type="none"<?php }?>><a href="<?php $a_id = (($_GET['a_id'] != '' && $_GET['a_id'] != 0)?$_GET['a_id'].'_'.$k:$k); echo replaceParam(array('a_id' => $a_id));?>"><?php echo $v['attr_value_name'];?></a></li>
                <?php }?>
              </ul>
            </dd>
            <?php if (count($val['value']) > 10){?>
            <dd class="all"><span wt_type="show"><i class="icon-angle-down"></i><?php echo $lang['goods_class_index_more'];?></span></dd>
            <?php }?>
          </dl>
          <?php $num_dl++;}?>
          <?php $dl++;} ?>
          <?php }?>
	  <?php }?>
        </div>
      </div>
 </div>
 <?php if($num_dl > 4){?>
	<div id="more_select_nav"><a href="javascript:void(0);" class="down"><span>更多选项&nbsp;</span><i class="icon-angle-down"></i></i></a></div>
 <?php }?>
 <div class="shop_con_list" id="main-list-bar">
 <nav class="sort-bar" id="main-nav">
        <div class="pagination"><?php echo $output['show_page1']; ?> </div>
        <div class="wth-category-nav">
          <div class="category-nav">
            <?php require template('layout/home_goods_class');?>
          </div>
        </div>
        <div class="wth-sortbar-array">
          <ul class="screen">
            <li <?php if(!$_GET['key']){?>class="selected"<?php }?>><a href="<?php echo dropParam(array('order', 'key'));?>"  title="<?php echo $lang['goods_class_index_default_sort'];?>"><?php echo $lang['goods_class_index_default_sort'];?></a></li>
            <li <?php if($_GET['key'] == '1'){?>class="selected"<?php }?>><a href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '1') ? replaceParam(array('key' => '1', 'order' => '1')):replaceParam(array('key' => '1', 'order' => '2')); ?>" <?php if($_GET['key'] == '1'){?>class="<?php echo $_GET['order'] == 1 ? 'asc' : 'desc';?>"<?php }?> title="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '1')?$lang['goods_class_index_sold_asc']:$lang['goods_class_index_sold_desc']; ?>"><?php echo $lang['goods_class_index_sold'];?><i></i></a></li>
            <li <?php if($_GET['key'] == '2'){?>class="selected"<?php }?>><a href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '2') ? replaceParam(array('key' => '2', 'order' => '1')):replaceParam(array('key' => '2', 'order' => '2')); ?>" <?php if($_GET['key'] == '2'){?>class="<?php echo $_GET['order'] == 1 ? 'asc' : 'desc';?>"<?php }?> title="<?php  echo ($_GET['order'] == '2' && $_GET['key'] == '2')?$lang['goods_class_index_click_asc']:$lang['goods_class_index_click_desc']; ?>"><?php echo $lang['goods_class_index_click']?><i></i></a></li>
            <li <?php if($_GET['key'] == '3'){?>class="selected"<?php }?>><a href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '3') ? replaceParam(array('key' => '3', 'order' => '1')):replaceParam(array('key' => '3', 'order' => '2')); ?>" <?php if($_GET['key'] == '3'){?>class="<?php echo $_GET['order'] == 1 ? 'asc' : 'desc';?>"<?php }?> title="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '3')?$lang['goods_class_index_price_asc']:$lang['goods_class_index_price_desc']; ?>"><?php echo $lang['goods_class_index_price'];?><i></i></a></li>
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
      </nav>
</div>
 <div class="left"> 
    <div class="shop_con_list" id="main-list-bar">
      <!-- 商品列表循环  -->
      <div>
        <?php require_once (BASE_TPL_PATH.'/home/goods.squares.php');?>
      </div>
      <div class="tc mt20 mb20">
        <div class="pagination"> <?php echo $output['show_page']; ?> </div>
      </div>
    </div>
  </div>
<?php if($output['is_show_right'] == '1'){?>
  <div class="right">
    <!-- 推荐展位 -->
    <div wttype="booth_goods" class="wth-booth" > 
	   <?php require_once (BASE_TPL_PATH.'/home/goods.booth.php');?>
	  </div>
  </div>
<?php } ?>
  <div class="clear"></div>
	<div class="wrapper">
    <!-- 猜你喜欢 -->
    <div id="guesslike_div"></div>
	   <?php if(!empty($output['viewed_goods'])){?>
		<!-- 最近浏览 -->
		<div class="wth-module wth-module-style03">
		  <div class="title">
			<h3><?php echo $lang['goods_class_viewed_goods']; ?><span><a href="<?php echo BASE_SITE_URL;?>/index.php?w=member_goodsbrowse&t=list">全部浏览历史</a></span></h3>
		  </div>
		  <div class="content">
			<div class="wth-sidebar-viewed">
			  <ul>
				<?php if(!empty($output['viewed_goods']) && is_array($output['viewed_goods'])){?>
				<?php foreach ($output['viewed_goods'] as $k=>$v){?>
				<li class="wth-sidebar-bowers">
				  <div class="goods-pic"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$v['goods_id'])); ?>" target="_blank"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" rel="lazy" data-url="<?php echo thumb($v, 60); ?>" title="<?php echo $v['goods_name']; ?>" alt="<?php echo $v['goods_name']; ?>" ></a></div>
				  <dl>
					<dd><?php echo $lang['currency'];?><?php echo wtPriceFormat($v['goods_sale_price']); ?></dd>
				  </dl>
				</li>
				<?php } ?>
				<?php } ?>
			  </ul>
			</div>
		</div>
		<?php } ?>
	</div>
	<!-- 广告位 -->
	<div class="wth-module"><?php echo loadshow(37,'html');?></div>
	</div>
<script src="<?php echo STATIC_SITE_URL;?>/js/waypoints.js"></script> 
<script src="<?php echo STATIC_SITE_URL;?>/js/search_category_menu.js"></script> 
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/fly/jquery.fly.min.js" charset="utf-8"></script> 
<!--[if lt IE 10]>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/fly/requestAnimationFrame.js" charset="utf-8"></script>
<![endif]-->
<script type="text/javascript">
var defaultSmallGoodsImage = '<?php echo defaultGoodsImage(240);?>';
var defaultTinyGoodsImage = '<?php echo defaultGoodsImage(60);?>';

$(function(){
    $('#files').tree({
        expanded: 'li:lt(2)'
    });
	//品牌索引过长滚条
	$('#wtBrandlist').perfectScrollbar({suppressScrollX:true});
    //浮动导航  waypoints.js
    $('#main-list-bar').waypoint(function(event, direction) {
        $(this).parent().toggleClass('sticky', direction === "down");
        event.stopPropagation();
    });
	// 单行显示更多
	$('span[wt_type="show"]').click(function(){
		s = $(this).parents('dd').prev().find('li[wt_type="none"]');
		if(s.css('display') == 'none'){
			s.show();
			$(this).html('<i class="icon-angle-up"></i><?php echo $lang['goods_class_index_retract'];?>');
		}else{
			s.hide();
			$(this).html('<i class="icon-angle-down"></i><?php echo $lang['goods_class_index_more'];?>');
		}
	});

	<?php if(isset($_GET['area_id']) && intval($_GET['area_id']) > 0){?>
  // 选择地区后的地区显示
  $('[wt_type="area_name"]').html('<?php echo $output['province_array'][intval($_GET['area_id'])]; ?>');
	<?php }?>

	<?php if($output['is_show_right'] == '0'){ ?>
		$('.wth-container .right').hide();
		$('.wth-container .left').css({"width":"1200px"});
		$('.squares .list_pic').css({"width":"1250px","overflow":"hidden"});
		$('.squares .list_pic .item').css({"width":"231px"});
	<?php }?>
	//猜你喜欢
	$('#guesslike_div').load('<?php echo urlShop('search', 'get_guesslike', array()); ?>', function(){
        $(this).show();
    });

	//商品分类推荐
	$('#gc_goods_recommend_div').load('<?php echo urlShop('search', 'get_gc_goods_recommend', array('cate_id'=>$output['default_classid'])); ?>');

  //获取更多
  $('#more_select_nav a').click(function(){
    var attr = $(this).attr('class');
    if(attr == 'down'){
      $(this).attr('class','up');
      $(this).find('i').removeClass('icon-angle-down').addClass('icon-angle-up');
      $(this).find('span').html('收起选项&nbsp;');
      $('.wth-module-filter .hide_dl').show();
    }else{
      $(this).attr('class','down');
      $(this).find('i').removeClass('icon-angle-up').addClass('icon-angle-down');
      $(this).find('span').html('更多选项&nbsp;');
      $('.wth-module-filter .hide_dl').hide();
    }
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
