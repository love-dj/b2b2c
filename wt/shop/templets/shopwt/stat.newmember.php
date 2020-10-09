<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>会员统计</h3>
        <h5>平台针对会员的各项数据统计</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li>统计图展示了时间段内新增会员数的走势和与前一时间段的对比</li>
      <li>统计表展示了时间段内新增会员数值和与前一时间段的同比数值，点击每条记录后的“查看”，了解新增会员的详细信息</li>
      <li>点击列表上方的“导出数据”，将列表数据导出为Excel文件</li>
    </ul>
  </div>
  <div id="container" style="height:400px"></div>
  <table class="flex-table">
    <input type="hidden" id="export_type" name="export_type" data-param='{"url":"<?php echo $output['actionurl'];?>&exporttype=excel"}' value="excel"/>
    <thead>
      <tr>
        <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
        <th width="60" align="center" class="handle-s">操作</th>
        <?php foreach ($output['statlist']['headertitle'] as $v){?>
        <th width="150" align="center"><?php echo $v; ?></th>
        <?php }?>
        <th></th>
      </tr>
    </thead>
    <tbody id="datatable">
      <?php if(!empty($output['statlist']['data'])){ ?>
      <?php foreach ($output['statlist']['data'] as $k => $v){?>
      <tr>
        <td class="sign"><i class="ico-check"></i></td>
        <td class="handle-s"><a href="index.php?w=stat_member&t=showmember&type=newbyday&c=<?php echo $v['seartime'];?>" class="btn green"><i class="fa fa-list-alt"></i>查看</a></td>
        <td><?php echo $v['timetext'];?></td>
        <td><?php echo $v['updata'];?></td>
        <td><?php echo $v['currentdata'];?></td>
        <td><?php echo $v['tbrate'];?></td>
        <td></td>
      </tr>
      <?php } ?>
      <tr>
        <td class="sign"><i class="ico-check"></i></td>
        <td class="handle-s"><a href="index.php?w=stat_member&t=showmember&type=newbyday&c=<?php echo $output['count_arr']['seartime'];?>" class="btn green"><i class="fa fa-list-alt"></i>查看</a></td>
        <td><b>总计</b></td>
        <td><?php echo $output['count_arr']['up'];?></td>
        <td><?php echo $output['count_arr']['curr'];?></td>
        <td><?php echo $output['count_arr']['tbrate'];?></td>
        <td></td>
      </tr>
      <?php } else { ?>
      <tr>
        <td class="no-data" colspan="100"><i class="fa fa-exclamation-triangle"></i><?php echo $lang['wt_no_record'];?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <div class="wtap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="wtap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="w" value="stat_member" />
    <input type="hidden" name="t" value="newmember" />
      <div id="searchCon" class="content">
        <div class="layout-box">
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
  <script type="text/javascript" src="<?php echo ADMIN_STATIC_URL?>/js/highcharts.js"></script>
  <script type="text/javascript" src="<?php echo ADMIN_STATIC_URL?>/js/statistics.js"></script>
</div>
<script>

$(function () {
	//同步加载flexigrid表格
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
		buttons : [
                   {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '导出数据', onpress : fg_operation }
               ]
		});
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

	$('#container').highcharts(<?php echo $output['stat_json'];?>);

	$('#wtsubmit').click(function(){
    	$('#formSearch').submit();
    });

});
//展示搜索时间框
function show_searchtime(){
	s_type = $("#search_type").val();
	$("[id^='searchtype_']").hide();
	$("#searchtype_"+s_type).show();
}
//flexigrid表格导出图表
function fg_operation(name, bDiv) {
    if (name == 'csv') {
        var item = $("#export_type");
        var type = $(item).val();
        if(type == 'excel'){
        	download_excel(item);
        }
    }
}
</script>