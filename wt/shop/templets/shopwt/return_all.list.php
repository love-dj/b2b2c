<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['return_manage'];?></h3>
        <h5><?php echo $lang['return_manage_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div id="flexigrid"></div>
  <div class="wtap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="wtap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" name="formSearch" id="formSearch">
      <div id="searchCon" class="content">
        <div class="layout-box">
          <dl>
            <dt>关键字搜索</dt>
            <dd>
              <label>
                <select class="s-select" name="keyword_type">
                  <option selected="selected" value="">-请选择-</option>
                  <option value="refund_sn">退单编号</option>
                  <option value="goods_name">商品名称</option>
                  <option value="buyer_name">买家账号</option>
                  <option value="store_name">店铺名称</option>
                  <option value="order_sn">订单编号</option>
                </select>
              </label>
              <label>
                <input type="text" value="" placeholder="请输入关键字" name="keyword" class="s-input-txt">
              </label>
              <label>
                <input type="checkbox" value="1" name="jq_query">精确
              </label>
            </dd>
          </dl>
          <dl>
            <dt>日期筛选</dt>
            <dd>
              <label>
                <select class="s-select" name="qtype_time">
                  <option selected="selected" value="">-请选择-</option>
                  <option value="add_time">买家申请时间</option>
                  <option value="seller_time">商家处理时间</option>
                  <option value="admin_time">平台处理时间 </option>
                </select>
              </label>
              <label>
                <input readonly id="query_start_date" placeholder="请选择起始时间" name=query_start_date value="" type="text" class="s-input-txt" />
              </label>
              <label>
                <input readonly id="query_end_date" placeholder="请选择结束时间" name="query_end_date" value="" type="text" class="s-input-txt" />
              </label>
            </dd>
          </dl>
          <dl>
            <dt>退款金额</dt>
            <dd>
              <label>
                <input placeholder="请输入起始金额" name=query_start_amount value="" type="text" class="s-input-txt" />
              </label>
              <label>
                <input placeholder="请输入结束金额" name="query_end_amount" value="" type="text" class="s-input-txt" />
              </label>
            </dd>
          </dl>
        </div>
      </div>
      <div class="bottom"> <a href="javascript:void(0);" id="wtsubmit" class="wtap-btn wtap-btn-green mr5">提交查询</a><a href="javascript:void(0);" id="wtreset" class="wtap-btn wtap-btn-orange" title="撤销查询结果，还原列表项所有内容"><i class="fa fa-retweet"></i><?php echo $lang['wt_cancel_search'];?></a></div>
    </form>
  </div>
</div>
<script type="text/javascript">
$(function(){
	$('#query_start_date').datepicker();
    $('#query_end_date').datepicker();
    // 高级搜索提交
    $('#wtsubmit').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?w=return&t=get_all_xml&'+$("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });
    // 高级搜索重置
    $('#wtreset').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?w=return&t=get_all_xml'}).flexReload();
        $("#formSearch")[0].reset();
    });
    $("#flexigrid").flexigrid({
        url: 'index.php?w=return&t=get_all_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '退单编号', name : 'refund_sn', width : 130, sortable : false, align: 'center'},
            {display: '退款金额', name : 'refund_amount', width : 70, sortable : true, align: 'left'},
            {display: '申请图片', name : 'pic_info', width : 60, sortable : false, align : 'left'},
            {display: '申请原因', name : 'buyer_message', width : 120, sortable : false, align: 'left'},
            {display: '申请时间', name : 'refund_id', width: 120, sortable : true, align : 'center'},
            {display: '涉及商品', name : 'goods_name', width : 120, sortable : false, align: 'left'},
            {display: '退货数量', name : 'goods_num', width : 70, sortable : true, align: 'center'},
            {display: '商家处理', name : 'seller_state', width : 80, sortable : true, align: 'center'},
            {display: '平台处理', name : 'refund_state', width : 80, sortable : false, align: 'center'},
            {display: '商家处理备注', name : 'seller_message', width : 120, sortable : false, align: 'left'},
            {display: '平台处理备注', name : 'admin_message', width : 120, sortable : false, align: 'left'},
            {display: '商家申核时间', name : 'seller_time', width: 120, sortable : true, align : 'center'},
            {display: '商品图', name : 'goods_image', width : 40, sortable : true, align: 'center'},
            {display: '商品ID', name : 'goods_id', width : 80, sortable : true, align: 'center'},
            {display: '订单编号', name : 'order_sn', width : 120, sortable : false, align: 'center'},
            {display: '买家', name : 'buyer_name', width : 60, sortable : true, align: 'left'},
            {display: '买家ID', name : 'buyer_id', width : 40, sortable : true, align: 'center'},
            {display: '商家名称', name : 'store_name', width : 100, sortable : true, align: 'left'},
            {display: '商家ID', name : 'store_id', width : 40, sortable : true, align: 'center'}
            ],
        searchitems : [
            {display: '退单编号', name : 'refund_sn', isdefault: true},
            {display: '商品名称', name : 'goods_name'},
            {display: '买家账号', name : 'buyer_name'},
            {display: '店铺名称', name : 'store_name'},
            {display: '订单编号', name : 'order_sn'}
            ],
        buttons : [
           {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出csv文件,如果不选中行，将导出列表所有数据', onpress : fg_operate }
           ],
        sortname: "refund_id",
        sortorder: "desc",
        title: '线上实物交易订单退货列表'
    });
});
function fg_operate(name, grid) {
    if (name == 'csv') {
    	var itemlist = new Array();
        if($('.trSelected',grid).length>0){
            $('.trSelected',grid).each(function(){
            	itemlist.push($(this).attr('data-id'));
            });
        }
        fg_csv(itemlist);
    }
}
function fg_csv(ids) {
    id = ids.join(',');
    window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&t=export_step1&refund_id=' + id;
}
</script> 
