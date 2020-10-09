<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>股东分红</h3>
        <h5>股东分红的管理</h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="相关操作时应注意，操作会影响会员的返现">操作提示</h4>
      <span id="explanationZoom" title="收起提示"></span> </div>
      <ul>
          <li>股东分红如果出现发放错误，可以点击收回返现</li>
      </ul>
  </div> 
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
    $(function(){
        $("#flexigrid").flexigrid({
            url: 'index.php?w=shareholders&t=get_xml',
            colModel : [
            {display: '操作', name : 'operation', width : 20, sortable : false, align: 'center', className: 'handle'},
            {display: '开始时间', name : 'start_time', width : 120, sortable : true, align: 'center'},
            {display: '结束时间', name : 'end_time', width : 120, sortable : true, align: 'center'},
            {display: '分红基数计算方式', name : 'commission_type', width : 120, sortable : true, align: 'center'},
            {display: '总分红基数', name : 'total_shareholder_commission', width : 80, sortable : true, align: 'center'},
            {display: '总股东数', name : 'total_member', width : 50, sortable : true, align: 'center'},
            {display: '分红状态', name : 'status', width : 100, sortable : true, align: 'center'},
            {display: '创建时间', name : 'createtime', width : 120, sortable : true, align: 'center'},
            ],         
            sortname: "id",
            sortorder: "desc",
            title: '分红管理'
        });
    });
</script>