<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['schema_level_manage'];?></h3>
    </div>
</div>
</div>
<div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
      <ul>
          <li><?php echo $lang['express_index_help1'];?></li>
      </ul>
  </div> 
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
    $(function(){
        $("#flexigrid").flexigrid({
            url: 'index.php?act=schema_level&op=get_xml',
            colModel : [
            {display: '操作', name : 'operation', width : 200, sortable : false, align: 'center', className: 'handle'},
            {display: '等级权重', name : 'level_weight', width : 100, sortable : true, align: 'center'},
            {display: '等级名称', name : 'level_name', width : 100, sortable : true, align: 'center'},
            {display: '一级比例', name : 'layer_one', width : 100, sortable : true, align: 'center'},
            {display: '二级比例', name : 'layer_two', width : 100, sortable : true, align: 'center'},
            {display: '三级比例', name : 'layer_three', width : 100, sortable : true, align: 'center'},
            {display: '等级人数', name : 'level_people', width : 100, sortable : true, align: 'center'},
            // {display: '升级条件', name : 'level_condition', width : 400, sortable : true, align: 'center'},
            ],
            buttons : [
            {display: '<i class="fa fa-plus"></i>添加新等级', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operation },
            {display: '<i class="fa fa-trash"></i>批量删除', name : 'del', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operation }
            ],        
            sortname: "id",
            sortorder: "asc",
            title: '分销商等级列表'
        });
    });

    function fg_operation(name, bDiv) {
        if (name == 'add') {
            window.location.href = 'index.php?act=schema_level&op=add_level';
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
        location.href = 'index.php?act=schema_level&op=del_level&del_id='+id;
    }
</script>