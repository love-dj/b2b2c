<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>满额返现日志</h3>
        <h5>满额返现日志的管理</h5>
      </div>
    </div>
  </div>
  <!--<div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="相关操作时应注意，操作会影响会员的返现">操作提示</h4>
      <span id="explanationZoom" title="收起提示"></span> </div>
      <ul>
          <li>订单返现如果出现发放错误，可以点击收回返现</li>
      </ul>
  </div> -->
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
    $(function(){
        $("#flexigrid").flexigrid({
            url: 'index.php?w=full_return_logs&t=get_xml',
            colModel : [
				{display: '操作', name : 'operation', width : 20, sortable : false, align: 'center', className: 'handle'},
				{display: '队列ID', name : 'buy_return_id', width : 150, sortable : true, align: 'center'},
				{display: '买家id', name : 'uid', width : 50, sortable : true, align: 'center'},
				{display: '总返现金额', name : 'return_money', width : 100, sortable : true, align: 'center'},
				{display: '返现比例', name : 'return_base_ratio', width : 50, sortable : true, align: 'center'},
				{display: '本期返现金额', name : 'return_commission', width : 80, sortable : true, align: 'center'},
				{display: '备注', name : 'remark', width : 100, sortable : true, align: 'center'},
				{display: '返现状态', name : 'status', width : 100, sortable : true, align: 'center'},
				{display: '创建时间', name : 'createtime', width : 120, sortable : true, align: 'center'},
            ],         
            sortname: "id",
            sortorder: "desc",
            title: '返现队列管理'
        });
    });
</script>