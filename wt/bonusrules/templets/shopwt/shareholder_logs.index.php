<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>股东分红日志</h3>
        <h5>股东分红日志的管理</h5>
      </div>
    </div>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
    $(function(){
        $("#flexigrid").flexigrid({
            url: 'index.php?w=shareholder_logs&t=get_xml',
            colModel : [
				{display: '操作', name : 'operation', width : 20, sortable : false, align: 'center', className: 'handle'},
				{display: '分红ID', name : 'shareholder_return_id', width : 150, sortable : true, align: 'center'},
				{display: '买家id', name : 'uid', width : 50, sortable : true, align: 'center'},
				{display: '分红金额', name : 'return_money', width : 100, sortable : true, align: 'center'},
				{display: '创建时间', name : 'createtime', width : 120, sortable : true, align: 'center'},
            ],         
            sortname: "id",
            sortorder: "desc",
            title: '返现队列管理'
        });
    });
</script>