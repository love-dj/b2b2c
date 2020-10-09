<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<style type="text/css">
.flexigrid .bDiv tr:nth-last-child(2) span.btn ul { bottom: 0; top: auto}
</style>


<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['goods_class_index_class'];?></h3>
        <h5><?php echo $lang['goods_class_index_class_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span>
    </div>
    <ul>
      <li><?php echo $lang['goods_class_index_help1'];?></li>
      <li><?php echo $lang['goods_class_index_help3'];?></li>
      <li>“商品展示”为在商品列表页的展示方式。<br>“颜色”：每个SPU只展示不同颜色的SKU，同一颜色多个SKU只展示一个SKU。<br>“SPU”：每个SPU只展示一个SKU。</li>
      <li>“编辑分类导航”功能可以设置前台左上侧商品分类导航的相关信息，可以设置分类前图标、分类别名、推荐分类、推荐品牌以及两张广告图片。</li>
      <li>分类导航信息设置完成后，需要更新“首页及频道”缓存。</li>
    </ul>
  </div>
  <form method='post'>
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="submit_type" id="submit_type" value="" />
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" class="handle" align="center"><?php echo $lang['wt_handle'];?></th>
          <th width="60" align="center"><?php echo $lang['wt_sort'];?></th>
          <th width="300" align="left"><?php echo $lang['goods_class_index_name'];?></th>
          <th width="80" align="center"><?php echo $lang['goods_class_add_type'];?></th>
          <th width="80" align="center"><?php echo $lang['goods_class_add_commis_rate'];?></th>
          <th width="80" align="center">虚拟</th>
          <th width="120" align="center">商品展示</th>
          <th width="80" align="center">是否显示</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['class_list']) && is_array($output['class_list'])){ ?>
        <?php foreach($output['class_list'] as $k => $v){ ?>
        <tr data-id="<?php echo $v['gc_id'];?>">
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle">
            <a class="btn red" href="javascript:void(0);" onclick="fg_del(<?php echo $v['gc_id'];?>);"><i class="fa fa-trash-o"></i><?php echo $lang['wt_del'];?></a>
            <span class="btn"><em><i class="fa fa-cog"></i><?php echo $lang['wt_set'];?><i class="arrow"></i></em>
            <ul>
              <li><a href="index.php?w=goods_class&t=goods_class_edit&gc_id=<?php echo $v['gc_id'];?>">编辑分类信息</a></li>
              <li><a href="index.php?w=goods_class&t=goods_class_add&gc_parent_id=<?php echo $v['gc_id'];?>">新增下级分类</a></li>
              <?php if ($v['have_child'] == 1) {?>
              <li><a href="index.php?w=goods_class&t=goods_class&gc_id=<?php echo $v['gc_id'];?>">查看下级分类</a></li>
              <?php }?>
              <li><a href="index.php?w=goods_class&t=nav_edit&gc_id=<?php echo $v['gc_id'];?>">编辑分类导航</a></li>
            </ul>
            </span></td>
          <td class="sort"><span title="<?php echo $lang['wt_editable'];?>" column_id="<?php echo $v['gc_id'];?>" fieldname="gc_sort" wt_type="inline_edit" class="editable "><?php echo $v['gc_sort'];?></span></td>
          <td class="name"><span title="<?php echo $lang['wt_editable'];?>"  column_id="<?php echo $v['gc_id'];?>" fieldname="gc_name" wt_type="inline_edit" class="editable "><?php echo $v['gc_name'];?></span></td>
          <td><?php echo $v['type_name'];?></td>
          <td><?php echo $v['commis_rate'];?> %</td>
          <td><?php if ($v['gc_virtual'] == 1) {?><span class="yes"><i class="fa fa-check-bbs"></i>允许</span><?php } else {?><span class="no"><i class="fa fa-ban"></i>禁止</span><?php }?></td>
          <td><?php echo $output['show_type'][$v['show_type']]?></td>
		  
          <td><?php if ($v['is_show'] == 0) {?><span class="yes"><i class="fa fa-check-bbs"></i>显示</span><?php } else {?><span class="no"><i class="fa fa-ban"></i>隐藏</span><?php }?></td>
          <td></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr>
          <td class="no-data" colspan="100"><i class="fa fa-exclamation-bbs"></i><?php echo $lang['wt_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_STATIC_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript">
$(function(){
    $('.flex-table').flexigrid({
        height:'auto',// 高度自动
        usepager: false,// 不翻页
        striped:false,// 不使用斑马线
        resizable: false,// 不调节大小
        title: '商品分类列表(一级)',// 表格标题
        reload: false,// 不使用刷新
        columnControl: false,// 不使用列控制
        buttons : [
                   {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', onpress : fg_operation },
                   {display: '<i class="fa fa-trash"></i>批量删除', name : 'del', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operation },
                   {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '导出全部分类数据', onpress : fg_operation }	
               ]
    });

    $('span[wt_type="inline_edit"]').inline_edit({w: 'goods_class',t: 'ajax'});
});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?w=goods_class&t=goods_class_add';
    } else if (name == 'del') {
        if ($('.trSelected', bDiv).length == 0) {
            showError('请选择要操作的数据项！');
        }
        var itemids = new Array();
        $('.trSelected', bDiv).each(function(i){
            itemids[i] = $(this).attr('data-id');
        });
        fg_del(itemids);
    } else if (name = 'csv') {
        window.location.href = 'index.php?w=goods_class&t=goods_class_export';
    }
}
function fg_del(ids) {
    if (typeof ids == 'number') {
        var ids = new Array(ids.toString());
    };
    id = ids.join(',');
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?w=goods_class&t=goods_class_del', {id:id}, function(data){
            if (data.state) {
                location.reload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script>