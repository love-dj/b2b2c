<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <a href="javascript:void(0)" class="wtbtn wtbtn-mint" wt_type="dialog" dialog_title="<?php echo $lang['store_goods_class_new_class'];?>" dialog_id="my_category_add" dialog_width="480" uri="<?php echo urlShop('store_goods_class', 'index', array('type' => 'ok'));?>" title="<?php echo $lang['store_goods_class_new_class'];?>"><?php echo $lang['store_goods_class_new_class'];?></a></div>
<table class="wtsc-default-table" id="my_category" server="index.php?w=store_goods_class&t=goods_class_ajax" >
  <thead>
    <tr wt_type="table_header">
      <th class="w30"></th>
      <th coltype="editable" column="stc_name" checker="check_required" inputwidth="50%"><?php echo $lang['store_goods_class_name'];?></th>
      <th class="w60" coltype="editable" column="stc_sort" checker="check_max" inputwidth="30px"><?php echo $lang['store_goods_class_sort'];?></th>
      <th class="w120" coltype="switchable" column="stc_state" checker="" onclass="showclass-ico-btn" offclass="noshowclass-ico-btn"><?php echo $lang['wt_display'];?></th>
      <th class="w100"><?php echo $lang['wt_handle'];?></th>
    </tr>
    <?php if (!empty($output['goods_class'])) { ?>
    <tr>
      <td class="tc"><input id="all" type="checkbox" class="checkall" /></td>
      <td colspan="20"><label for="all"><?php echo $lang['wt_select_all'];?></label>
        <a href="javascript:void(0)" class="wtbtn-mini" wt_type="batchbutton" uri="index.php?w=store_goods_class&t=drop_goods_class" name="class_id" confirm="<?php echo $lang['store_goods_class_ensure_del'];?>?"><i class="icon-trash"></i><?php echo $lang['wt_del'];?></a></td>
    </tr>
    <?php } ?>
  </thead>
  <tbody id="treet1">
    <?php if (!empty($output['goods_class'])) { ?>
    <?php foreach ($output['goods_class'] as $key => $val) { ?>
    <tr class="bd-line" wt_type="table_item" idvalue="<?php echo $val['stc_id']; ?>" >
      <td class="tc"><input type="checkbox" class="checkitem" value="<?php echo $val['stc_id']; ?>" /></td>
      <td class="tl"><span class="ml5" wt_type="editobj" ><?php echo $val['stc_name']; ?></span>
        <?php if ($val['stc_parent_id'] == 0) { ?>
        <span class="add_ico_a"> <a href="javascript:void(0)" class="wtbtn" wt_type="dialog" dialog_width="480" dialog_title="<?php echo $lang['store_goods_class_add_sub'];?>" dialog_id="my_category_add" uri="index.php?w=store_goods_class&top_class_id=<?php echo $val['stc_id']; ?>&type=ok" ><?php echo $lang['store_goods_class_add_sub'];?></a></span>
        <?php } ?></td>
      <td><span wt_type="editobj"><?php echo $val['stc_sort']; ?></span></td>
      <td><?php if ($val['stc_state'] == 1) { ?>
        <?php echo $lang['store_create_yes'];?>
        <?php } else { ?>
        <?php echo $lang['store_create_no'];?>
        <?php } ?></td>
      <td class="nscs-table-handle"><span><a href="javascript:void(0)" wt_type="dialog" dialog_width="480" dialog_title="<?php echo $lang['store_goods_class_edit_class'];?>" dialog_id="my_category_edit" uri="index.php?w=store_goods_class&class_id=<?php echo $val['stc_id']; ?>&type=ok" class="btn-bluejeans"><i class="icon-edit"></i>
        <p><?php echo $lang['wt_edit'];?></p>
        </a></span> <span><a href="javascript:void(0)" onclick="ajax_get_confirm('<?php echo $lang['store_goods_class_ensure_del'];?>?', 'index.php?w=store_goods_class&t=drop_goods_class&class_id=<?php echo $val['stc_id']; ?>');" class="btn-grapefruit"><i class="icon-trash"></i>
        <p><?php echo $lang['wt_del'];?></p>
        </a></span></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span> </div></td>
    </tr>
    <?php } ?>
  </tbody>
  <?php if (!empty($output['goods_class'])) { ?>
  <tfoot>
    <tr>
      <th class="tc"><input id="all2" type="checkbox" class="checkall" /></th>
      <th colspan="15"><label for="all2"><?php echo $lang['wt_select_all'];?></label>
        <a href="javascript:void(0)" class="wtbtn-mini" wt_type="batchbutton" uri="index.php?w=store_goods_class&t=drop_goods_class" name="class_id" confirm="<?php echo $lang['store_goods_class_ensure_del'];?>?"><i class="icon-trash"></i><?php echo $lang['wt_del'];?></a></th>
    </tr>
  </tfoot>
  <?php } ?>
</table>
<style>
<!--
.collapsed{display:none;}
-->
</style>
<script src="<?php echo STATIC_SITE_URL;?>/js/jqtreetable.js"></script>
<script>
$(function()
{
    var map = [<?php echo $output['map']; ?>];
    var path = "<?php echo SHOP_TEMPLATES_URL;?>/images/";
    if (map.length > 0)
    {
        var option = {
		openImg: path + "treetable/tv-collapsable.gif",
		shutImg: path + "treetable/tv-expandable.gif",
		leafImg: path + "treetable/tv-item.gif",
		lastOpenImg: path + "treetable/tv-collapsable-last.gif",
		lastShutImg: path + "treetable/tv-expandable-last.gif",
		lastLeafImg: path + "treetable/tv-item-last.gif",
		vertLineImg: path + "treetable/vertline.gif",
		blankImg: path + "treetable/blank.gif",
		collapse: false,
		column: 1,
		striped: false,
		highlight: false,
		state:false};
        $("#treet1").jqTreeTable(map, option);
    }
});
</script>