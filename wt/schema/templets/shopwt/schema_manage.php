<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['schema_manage'];?></h3>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
      <ul>
        <li></li>
      </ul>
    </div> 
    <div id="flexigrid"></div>
  </div>
  <script type="text/javascript">
    $(function(){
      var rowdbclick = function(rowData) { 
        alert($(rowData).data("ID").toString());
      };
      $("#flexigrid").flexigrid({
        url: 'index.php?act=schema_manage&op=get_xml',
        colModel : [
        {display: '操作', name : 'operation', width : 100, sortable : false, align: 'center', className: 'handle'},
        {display: '昵称', name : 'nickname', width : 100, sortable : true, align: 'center'},
        {display: '真实姓名', name : 'truename', width : 100, sortable : true, align: 'center'},
        {display: '邀请人姓名', name : 'parent_name', width : 100, sortable : true, align: 'center'},
        {display: '分销商等级', name : 'level_name', width : 100, sortable : true, align: 'center'},
        {display: '累计佣金', name : 'distribute_total', width : 100, sortable : true, align: 'center' },
        ],
        buttons : [        
        {display: '<i class="fa fa-trash"></i>批量删除', name : 'del', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operation }
        ],    
        sortname: "id",
        sortorder: "asc",
        title: '分销商管理'
      });
    });

    function fg_operation(name, bDiv) {
        if (name == 'edit') {
          
            window.location.href = 'index.php?act=schema_manage&op=level_name';
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
        if(confirm('删除后将不能恢复，确认删除这 ' + id.length + ' 项吗？')){
            id = id.join(',');
        } else {
            return false;
        }
        location.href = 'index.php?act=schema_manage&op=del_manage&del_id='+id;
    }
  </script>