<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>团队管理</h3>
        <h5>商城会员无限级团队的管理</h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
      <span id="explanationZoom" title="收起提示"></span> </div>
    <ul>
      <li>撤掉会员团队身份后，以前已经发放的奖金不会受影响。</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
  $(function(){
      $("#flexigrid").flexigrid({
          url: 'index.php?w=team_user&t=get_xml',
          colModel : [
          {display: '操作', name : 'operation', width : 200, sortable : false, align: 'center', className: 'handle'},
          {display: '会员ID', name : 'member_id', width : 80, sortable : true, align: 'center'},
          {display: '会员姓名', name : 'member_truename', width : 100, sortable : true, align: 'center'},
          {display: '联系方式', name : 'member_mobile', width : 100, sortable : true, align: 'center'},
          {display: '团队开启时间', name : 'become_team_time', width : 120, sortable : true, align: 'center'},
          {display: '团队等级', name : 'level_name', width : 100, sortable : true, align: 'center'},
          {display: '提成比例', name : 'layer_rate', width : 100, sortable : true, align: 'center'},
          {display: '未结算提成', name : 'team_remain_commission', width : 100, sortable : true, align: 'center'},
          {display: '累计提成', name : 'team_commission', width : 100, sortable : true, align: 'center'},
          {display: '团队业绩', name : 'team_cost_money', width : 100, sortable : true, align: 'center'},
          ],
          buttons : [
          {display: '<i class="fa fa-plus"></i>添加新团队', name : 'add', bclass : 'add', title : '新增团队', onpress : fg_operation },
          {display: '<i class="fa fa-trash"></i>批量撤销团队身份', name : 'del', bclass : 'del', title : '将选定行数据批量操作', onpress : fg_operation }
          ],        
          sortname: "id",
          sortorder: "asc",
          title: '团队列表'
      });
  });

  function fg_operation(name, bDiv) {
      if (name == 'add') {
          window.location.href = 'index.php?w=team_user&t=add_user';
      } else if (name == 'del') {
          if ($('.trSelected', bDiv).length == 0) {
              showError('请选择要操作的数据项！');
          }
          var itemids = new Array();
          $('.trSelected', bDiv).each(function(i){
              itemids[i] = $(this).attr('data-id');
          });
          fg_delete(itemids);
      }
  }

  function fg_delete(id) {
      if (typeof id == 'number') {
          var id = new Array(id.toString());
      };
      if(confirm('撤销后将不能恢复，确认撤销这 ' + id.length + ' 项吗？')){
          id = id.join(',');
      } else {
          return false;
      }
      location.href = 'index.php?w=team_user&t=remove_user&del_id='+id;
  }
</script> 
