<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>单品消费返现队列</h3>
        <h5>单品消费返现队列的管理</h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="相关操作时应注意，操作会影响会员的返现">操作提示</h4>
      <span id="explanationZoom" title="收起提示"></span> </div>
      <ul>
          <li>订单返现如果出现发放错误，可以点击收回返现</li>
          <li>临时队列表示该队列暂未开启返现，订单完成后开始返现</li>
      </ul>
  </div> 
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
    $(function(){
        $("#flexigrid").flexigrid({
            url: 'index.php?w=buy_return_queue&t=get_xml',
            colModel : [
            {display: '操作', name : 'operation', width : 20, sortable : false, align: 'center', className: 'handle'},
            {display: '订单编号', name : 'ordersn', width : 150, sortable : true, align: 'center'},
            {display: '买家id', name : 'uid', width : 50, sortable : true, align: 'center'},
            {display: '订单价格', name : 'order_money', width : 80, sortable : true, align: 'center'},
            {display: '返现来源', name : 'commission_type', width : 100, sortable : true, align: 'center'},
            {display: '返现总额', name : 'total_commission', width : 80, sortable : true, align: 'center'},
            {display: '返现方式', name : 'return_type', width : 50, sortable : true, align: 'center'},
            {display: '每期返现比例', name : 'each_return_rate', width : 80, sortable : true, align: 'center'},
            {display: '已返现金额', name : 'pay_commission', width : 80, sortable : true, align: 'center'},
            {display: '剩余返现金额', name : 'balance_commission', width : 80, sortable : true, align: 'center'},
            {display: '订单状态', name : 'order_state', width : 100, sortable : true, align: 'center'},
            {display: '退款状态', name : 'refund_status', width : 100, sortable : true, align: 'center'},
            {display: '返现状态', name : 'status', width : 100, sortable : true, align: 'center'},
            {display: '创建时间', name : 'createtime', width : 120, sortable : true, align: 'center'},
            {display: '最近一次返现时间', name : 'updatetime', width : 120, sortable : true, align: 'center'},
            ],         
            sortname: "id",
            sortorder: "desc",
            title: '返现队列管理'
        });
    });
</script>