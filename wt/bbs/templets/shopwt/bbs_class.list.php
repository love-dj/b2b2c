<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_bbs_classmanage'];?></h3>
        <h5><?php echo $lang['wt_bbs_classmanage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['bbs_class_prompts_one'];?></li>
      <li><?php echo $lang['bbs_class_prompts_two'];?></li>
    </ul>
  </div>
  <div class="flex-table-search">
    <form method="get" name="formSearch">
      <input type="hidden" name="w" value="bbs_class">
      <input type="hidden" name="t" value="class_list">
      <div class="sDiv">
        <select name="searchstatus" class="select">
          <option value=""><?php echo $lang['bbs_class_status'];?></option>
          <option value="1" <?php if ($_GET['searchstatus'] == '1'){echo 'selected=selected';}?>><?php echo $lang['wt_open'];?></option>
          <option value="0" <?php if ($_GET['searchstatus'] == '0'){echo 'selected=selected';}?>><?php echo $lang['wt_close'];?></option>
        </select>
        <input type="text" name="searchname" id="searchname" value='<?php echo $_GET['searchname'];?>' class="qsbox" placeholder="输入社区分类名称...">
        <a href="javascript:document.formSearch.submit();" class="btn"><?php echo $lang['wt_search'];?></a></div>
    </form>
  </div>
  <form method='post' name="class_form" id="class_form">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="submit_type" id="submit_type" value="" />
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" align="center" class="handle"><?php echo $lang['wt_handle'];?></th>
          <th width="60" align="center"><?php echo $lang['wt_sort'];?></th>
          <th width="300" align="left"><?php echo $lang['bbs_class_name'];?></th>
          <th width="150" align="center"><?php echo $lang['wt_add_time'];?></th>
          <th width="100" align="center"><?php echo $lang['wt_recommend'];?></th>
          <th width="100" align="center"><?php echo $lang['wt_status'];?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['class_list']) && is_array($output['class_list'])){ ?>
        <?php foreach($output['class_list'] as $k => $v){ ?>
        <tr class="edit"  data-id="<?php echo $v['class_id'];?>">
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle"><a href="javascript:void(0);" onclick="fg_del(<?php echo $v['class_id'];?>)" class="btn red"><i class="fa fa-trash-o"></i><?php echo $lang['wt_del'];?></a><a href="index.php?w=bbs_class&t=class_edit&classid=<?php echo $v['class_id'];?>" class="btn blue"><i class="fa fa-pencil-square-o"></i><?php echo $lang['wt_edit'];?></a></td>
          <td class="sort"><span title="<?php echo $lang['wt_editable'];?>" fieldid="<?php echo $v['class_id'];?>" ajax_branch="sort" datatype="number" fieldname="class_sort" wt_type="inline_edit" class="editable "><?php echo $v['class_sort'];?></span></td>
          <td class="name"><span title="<?php echo $lang['wt_editable'];?>" required="1" fieldid="<?php echo $v['class_id'];?>" ajax_branch="name" fieldname="class_name" wt_type="inline_edit" maxlength="6" class="editable"><?php echo $v['class_name'];?></span></td>
          <td><?php echo date('Y-m-d',$v['class_addtime']);?></td>
          <td class="yes-onoff"><?php if($v['is_recommend'] == 0){ ?>
            <a href="JavaScript:void(0);" class=" disabled" fieldvalue="0" fieldid="<?php echo $v['class_id'];?>" ajax_branch="recommend" fieldname="is_recommend" wt_type="inline_edit" title="<?php echo $lang['wt_editable'];?>"><img src="<?php echo ADMIN_TEMPLATES_URL;?>/images/transparent.gif"></a>
            <?php }else{ ?>
            <a href="JavaScript:void(0);" class=" enabled" fieldvalue="1" fieldid="<?php echo $v['class_id'];?>" ajax_branch="recommend" fieldname="is_recommend" wt_type="inline_edit" title="<?php echo $lang['wt_editable'];?>"><img src="<?php echo ADMIN_TEMPLATES_URL;?>/images/transparent.gif"></a>
            <?php } ?></td>
          <td class="power-onoff"><?php if($v['class_status'] == 0){ ?>
            <a href="JavaScript:void(0);" class=" disabled" fieldvalue="0" fieldid="<?php echo $v['class_id'];?>" ajax_branch="status" fieldname="class_status" wt_type="inline_edit" title="<?php echo $lang['wt_editable'];?>"><img src="<?php echo ADMIN_TEMPLATES_URL;?>/images/transparent.gif"></a>
            <?php }else{ ?>
            <a href="JavaScript:void(0);" class=" enabled" fieldvalue="1" fieldid="<?php echo $v['class_id'];?>" ajax_branch="status" fieldname="class_status" wt_type="inline_edit" title="<?php echo $lang['wt_editable'];?>"><img src="<?php echo ADMIN_TEMPLATES_URL;?>/images/transparent.gif"></a>
            <?php } ?></td>
          <td></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr>
          <td class="no-data" colspan="100"><i class="fa fa-exclamation-triangle"></i><?php echo $lang['wt_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript">
$(function(){
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		title: '社区分类列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
        buttons : [
                   {display: '<i class="fa fa-plus"></i>新增分类', name : 'add', bclass : 'add', title : '新增分类', onpress : fg_operation },
				   {display: '<i class="fa fa-trash"></i>批量删除', name : 'del', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operation }
               ]
		});

});
function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?w=bbs_class&t=class_add';
    } else if (name == 'del') {
        if ($('.trSelected', bDiv).length == 0) {
            showError('请选择要操作的数据项！');
        }
        var itemids = new Array();
        $('.trSelected', bDiv).each(function(i){
            itemids[i] = $(this).attr('data-id');
        });
        fg_del(itemids);
    }
}
function fg_del(ids) {
    if (typeof ids == 'number') {
        var ids = new Array(ids.toString());
    };
    id = ids.join(',');
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?w=bbs_class&t=class_del', {id:id}, function(data){
            if (data.state) {
                location.reload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script> 
<script type="text/javascript" src="<?php echo ADMIN_STATIC_URL;?>/js/jquery.edit.js" charset="utf-8"></script> 