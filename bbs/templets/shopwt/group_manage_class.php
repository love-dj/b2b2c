<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="base-box">
  <div class="mainbox">
    <?php include bbs_template('group_manage_top');?>
    <table class="base-table-style">
      <thead>
        <tr>
          <th class="w30"></th>
          <th class="tl"><?php echo $lang['wt_name'];?></th>
          <th class="w120"><?php echo $lang['wt_sort'];?></th>
          <th class="w120"><?php echo $lang['wt_status'];?></th>
          <th class="w120"><?php echo $lang['wt_handle'];?></th>
        </tr>
        <tr>
          <?php if(!empty($output['thclass_list'])){?>
          <td class="tc"><input id="all" class="checkall" type="checkbox" /></td>
          <td colspan="3"><label for="all" class="handle-btn"><i class="ac0"></i><?php echo $lang['wt_check_all'];?></label>
            <a href="javascript:void(0);" class="handle-btn" wttype="batchbutton" uri="<?php echo BBS_SITE_URL;?>/index.php?w=manage&t=class_del&c_id=<?php echo $output['c_id'];?>" name="thc_id" confirm="<?php echo $lang['wt_ensure_del'];?>"><i class="ac5"></i><?php echo $lang['wt_delete'];?></a></td>
          <?php }?>
          <td colspan="20" class="tr"><?php if(count($output['thclass_list']) <10){?><a href="javascript:void(0);" wttype="add_thclass" class="add-group-class-btn mr10"><?php echo $lang['bbs_tclass_add'];?></a><?php }else{echo $lang['bbs_tclass_add_max_10'];}?></td>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['thclass_list'])){?>
        <?php foreach ($output['thclass_list'] as $val){?>
        <tr>
          <td><input type="checkbox" class="checkitem" value="<?php echo $val['thclass_id'];?>" /></td>
          <td class="tl"><?php echo $val['thclass_name'];?></td>
          <td><?php echo $val['thclass_sort'];?></td>
          <td><?php if($val['thclass_status'] == 1){echo $lang['wt_open'];}else{echo $lang['wt_close'];}?></td>
          <td class="handle"><a href="javascript:void(0);" onclick="edit_thclass(<?php echo $val['thclass_id'];?>)"><?php echo $lang['wt_edit'];?></a>&nbsp;|&nbsp;<a href="javascript:void(0);" onclick="del_thclass(<?php echo $val['thclass_id'];?>)"><?php echo $lang['wt_delete'];?></a></td>
        </tr>
        <?php }?>
        <?php }else{?>
        <tr>
          <td colspan="20" class="noborder"><p class="no-record"><?php echo $lang['no_record'];?></p></td>
        </tr>
        <?php }?>
      </tbody>
      <?php if(!empty($output['thclass_list'])){?>
      <tfoot>
        <tr>
          <th class="tc"><input id="all" class="checkall" type="checkbox" /></th>
          <th colspan="20"><label for="all" class="handle-btn"><i class="ac0"></i><?php echo $lang['wt_check_all'];?></label>
            <a href="javascript:void(0);" class="handle-btn" wttype="batchbutton" uri="<?php echo BBS_SITE_URL;?>/index.php?w=manage&t=class_del&c_id=<?php echo $output['c_id'];?>" name="thc_id" confirm="<?php echo $lang['wt_ensure_del'];?>"><i class="ac5"></i><?php echo $lang['wt_delete'];?></a> </th>
        </tr>
      </tfoot>
      <?php }?>
    </table>
  </div>
  <div class="sidebar">
    <?php include bbs_template('group_manage_sidebar');?>
  </div>
</div>
<script src="<?php echo BBS_STATIC_SITE_URL;?>/js/bbs_manage.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.validation.min.js"></script> 
<script>
$(function(){
    // 添加分类
    $('a[wttype="add_thclass"]').click(function(){
    	_uri = "<?php echo BBS_SITE_URL;?>/index.php?w=manage&t=class_add&inajax=1&c_id=<?php echo $output['c_id'];?>";
    	CUR_DIALOG = ajax_form('add_thclass', '<?php echo $lang['bbs_tclass_add'];?>', _uri, 520);
	});
});
function edit_thclass(id){
	_uri = "<?php echo BBS_SITE_URL;?>/index.php?w=manage&t=class_edit&inajax=1&c_id=<?php echo $output['c_id'];?>&thc_id="+id;
	CUR_DIALOG = ajax_form('edit_thclass', '<?php echo $lang['bbs_tclass_edit'];?>', _uri, 520);
}
function del_thclass(id){
	showDialog('<?php echo $lang['wt_ensure_del'];?>', 'confirm', '', function(){
		_uri = "<?php echo BBS_SITE_URL;?>/index.php?w=manage&t=class_del&inajax=1&c_id=<?php echo $output['c_id'];?>&thc_id="+id;
		ajaxget(_uri);
	});
}
</script>