<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>行业分析</h3>
        <h5>平台根据商品分类对行业进行各项分析</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['stat_validorder_explain'];?></li>
      <li>点击“设置价格区间”进入设置价格区间页面，下方统计图将根据您设置的价格区间进行统计</li>
      <li>统计图展示符合搜索条件的有效订单中商品的单价所在价格区间中的总销售额和总下单商品数量走势</li>
    </ul>
  </div>
  <div class="wtap-stat-chart">
    <div class="title">
      <h3>行业价格分布图</h3>
      <h5><a href="index.php?w=stat_general&t=setting" class="wtap-btn">设置价格区间</a></h5>
    </div>
    <?php if ($output['goodsnum_stat_json'] || $output['orderamount_stat_json']){ ?>
    <div id="container_orderamount" style="height:400px"></div>
    <div id="container_goodsnum" style="height:400px"></div>
    <?php } else {?>
    <div class="no-date"><i class="fa fa-exclamation-triangle"></i>查看行业价格分布情况前，请先设置价格区间。<a href="index.php?w=stat_general&t=setting" class="wtap-btn wtap-btn-orange">马上设置</a></div>
    <?php } ?>
  </div>
  <div class="wtap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="wtap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" action="index.php" name="formSearch" id="formSearch">
      <input type="hidden" name="w" value="stat_industry" />
      <input type="hidden" name="t" value="price" />
      <input type="hidden" id="choose_gcid" name="choose_gcid" value="0"/>
      <div id="searchCon" class="content">
        <div class="layout-box">
          <dl>
            <dt>按商品分类筛选</dt>
            <dd id="searchgc_td"> </dd>
          </dl>
          <dl>
            <dt>按时间周期筛选</dt>
            <dd>
              <label>
                <select name="search_type" id="search_type" class="s-select">
                  <option value="day" <?php echo $output['search_arr']['search_type']=='day'?'selected':''; ?>>按照天统计</option>
                  <option value="week" <?php echo $output['search_arr']['search_type']=='week'?'selected':''; ?>>按照周统计</option>
                  <option value="month" <?php echo $output['search_arr']['search_type']=='month'?'selected':''; ?>>按照月统计</option>
                </select>
              </label>
            </dd>
            <dd id="searchtype_day" style="display:none;">
              <label>
                <input class="s-input-txt" type="text" value="<?php echo @date('Y-m-d',$output['search_arr']['day']['search_time']);?>" id="search_time" name="search_time">
              </label>
            </dd>
            <dd id="searchtype_week" style="display:none;">
              <label>
                <select name="searchweek_year" class="s-select">
                  <?php foreach ($output['year_arr'] as $k => $v){?>
                  <option value="<?php echo $k;?>" <?php echo $output['search_arr']['week']['current_year'] == $k?'selected':'';?>><?php echo $v; ?>年</option>
                  <?php } ?>
                </select>
              </label>
              <label>
                <select name="searchweek_month" class="s-select">
                  <?php foreach ($output['month_arr'] as $k => $v){?>
                  <option value="<?php echo $k;?>" <?php echo $output['search_arr']['week']['current_month'] == $k?'selected':'';?>><?php echo $v; ?>月</option>
                  <?php } ?>
                </select>
              </label>
              <label>
                <select name="searchweek_week" class="s-select">
                  <?php foreach ($output['week_arr'] as $k => $v){?>
                  <option value="<?php echo $v['key'];?>" <?php echo $output['search_arr']['week']['current_week'] == $v['key']?'selected':'';?>><?php echo $v['val']; ?></option>
                  <?php } ?>
                </select>
              </label>
            </dd>
            <dd id="searchtype_month" style="display:none;">
              <label>
                <select name="searchmonth_year" class="s-select">
                  <?php foreach ($output['year_arr'] as $k => $v){?>
                  <option value="<?php echo $k;?>" <?php echo $output['search_arr']['month']['current_year'] == $k?'selected':'';?>><?php echo $v; ?>年</option>
                  <?php } ?>
                </select>
              </label>
              <label>
                <select name="searchmonth_month" class="s-select">
                  <?php foreach ($output['month_arr'] as $k => $v){?>
                  <option value="<?php echo $k;?>" <?php echo $output['search_arr']['month']['current_month'] == $k?'selected':'';?>><?php echo $v; ?>月</option>
                  <?php } ?>
                </select>
              </label>
            </dd>
          </dl>
        </div>
      </div>
      <div class="bottom"> <a href="javascript:void(0);" id="wtsubmit" class="wtap-btn wtap-btn-green">提交查询</a> </div>
    </form>
  </div>
</div>
<script type="text/javascript" src="<?php echo ADMIN_STATIC_URL?>/js/highcharts.js"></script> 
<script type="text/javascript" src="<?php echo ADMIN_STATIC_URL?>/js/statistics.js"></script> 
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/common_select.js"></script>
</div>
<script>
//展示搜索时间框
function show_searchtime(){
	s_type = $("#search_type").val();
	$("[id^='searchtype_']").hide();
	$("#searchtype_"+s_type).show();
}

$(function () {
	//统计数据类型
	var s_type = $("#search_type").val();
	$('#search_time').datepicker({dateFormat: 'yy-mm-dd'});

	$('#searchBarOpen').click();

	show_searchtime();
	$("#search_type").change(function(){
		show_searchtime();
	});

	//更新周数组
	$("[name='searchweek_month']").change(function(){
		var year = $("[name='searchweek_year']").val();
		var month = $("[name='searchweek_month']").val();
		$("[name='searchweek_week']").html('');
		$.getJSON('<?php echo ADMIN_SITE_URL?>/index.php?w=common&t=getweekofmonth',{y:year,m:month},function(data){
	        if(data != null){
	        	for(var i = 0; i < data.length; i++) {
	        		$("[name='searchweek_week']").append('<option value="'+data[i].key+'">'+data[i].val+'</option>');
			    }
	        }
	    });
	});
	//商品分类
	init_gcselect(<?php echo $output['gc_choose_json'];?>,<?php echo $output['gc_json']?>);

	$('#container_orderamount').highcharts(<?php echo $output['orderamount_stat_json'];?>);
	$('#container_goodsnum').highcharts(<?php echo $output['goodsnum_stat_json'];?>);

	$('#wtsubmit').click(function(){
    	$('#formSearch').submit();
    });

});
</script>