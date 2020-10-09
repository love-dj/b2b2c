<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>店铺统计</h3>
        <h5>平台针对店铺的各项数据统计</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span>
    </div>
    <ul>
      <li><?php echo $lang['stat_validorder_explain'];?></li>
            <li>“店铺热卖TOP榜”展示了时间段内店铺有效订单的订单数量和订单总金额高的前15名店铺</li>
            <li>“店铺热卖飙升榜”展示了时间段内店铺有效订单的订单数量和订单总金额增长率高的前15名店铺</li>
    </ul>
  </div>
  <div id="stat_tabs" class="  ui-tabs" style="min-height:500px">

      <ul class="tab-base wt-row">
        <li><a href="#orderamount_div" wt_type="showdata" data-param='{"type":"orderamount"}'>下单金额</a></li>
        <li><a href="#ordernum_div" wt_type="showdata" data-param='{"type":"ordernum"}'>下单量</a></li>
      </ul>

    <!-- 下单金额 -->
    <div id="orderamount_div" style="text-align:center;"></div>
    <!-- 下单量 -->
    <div id="ordernum_div" style="text-align:center;"></div>
  </div>
  <div class="wtap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="wtap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="w" value="stat_store" />
    <input type="hidden" name="t" value="hotrank" />
      <div id="searchCon" class="content">
        <div class="layout-box">
          <dl>
            <dt>按店铺分类筛选</dt>
            <dd>
              <label>
                <select name="search_sclass" id="search_sclass" class="s-select">
                <option value="">-请选择-</option>
                <?php foreach ($output['store_class'] as $k => $v){ ?>
                <option value="<?php echo $v['sc_id'];?>" <?php echo $_REQUEST['search_sclass'] == $v['sc_id']?'selected':''; ?>><?php echo $v['sc_name'];?></option>
                <?php } ?>
              </select>
              </label>
            </dd>
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
              </label></dd>
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
              </label></dd>
            <dd id="searchtype_month" style="display:none;">
              <label><select name="searchmonth_year" class="s-select">
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
              </label></dd>
          </dl>
        </div>
      </div>
      <div class="bottom">
        <a href="javascript:void(0);" id="wtsubmit" class="wtap-btn wtap-btn-green">提交查询</a>
      </div>
    </form>
  </div>
  <script type="text/javascript" src="<?php echo ADMIN_STATIC_URL?>/js/statistics.js"></script>
</div>
<script>
//展示搜索时间框
function show_searchtime(){
	s_type = $("#search_type").val();
	$("[id^='searchtype_']").hide();
	$("#searchtype_"+s_type).show();
}
$(function () {
	//切换登录卡
	$('#stat_tabs').tabs();

	//统计数据类型
	var s_type = $("#search_type").val();
	$('#search_time').datepicker({dateFormat: 'yy-mm-dd'});

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

	$('#searchBarOpen').click();

	$('#wtsubmit').click(function(){
    	$('#formSearch').submit();
    });

    //加载统计数据
    getStatdata('orderamount');
    $("[wt_type='showdata']").click(function(){
    	var data_str = $(this).attr('data-param');
		eval('data_str = '+data_str);
		getStatdata(data_str.type);
    });
});
//加载统计地图
function getStatdata(type){
	//店铺分类
	var search_sclass = $("#search_sclass").val();
	$('#'+type+'_div').load('index.php?w=stat_store&t=hotrank_list&type='+type+'&search_sclass='+search_sclass+'&c=<?php echo $output['searchtime'];?>');
}
</script>