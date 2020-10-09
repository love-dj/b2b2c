<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<table class="wtsc-default-table">
  <thead>
    <tr>
        <th class="w10"></th>
        <th class="w150">实体店铺名称</th>
      <th class="w250">电话</th>
      <th>详细地址/公交</th>
      <th class="w100"><?php echo $lang['wt_handle'];?></th>
    </tr>
  </thead>
  <tbody>
  <?php if (is_array($output['map_list']) && !empty($output['map_list'])) { ?>
    <?php foreach ($output['map_list'] as $key => $val) { ?>
    <tr class="bd-line" >
        <td></td>
        <td><?php echo $val['name_info']; ?></td>
      <td><?php echo $val['phone_info']; ?></td>
      <td class="tl"><dl class="map-address"><dt><?php echo $val['address_info']; ?></dt>
        <dd>公交<?php echo $lang['wt_colon'];?><?php echo $val['bus_info']; ?></dd></dl></td>
      <td class="nscs-table-handle">
        <span><a href="javascript:void(0)" class="btn-mint" wt_type="dialog" dialog_title="<?php echo $lang['wt_edit'];?>" dialog_id="map_edit" dialog_width="480" uri="index.php?w=store_map&t=edit_map&map_id=<?php echo $val['map_id']; ?>"><i class="icon-edit"></i><p><?php echo $lang['wt_edit'];?></p></a></span>
        <span><a href="javascript:void(0)" onclick="ajax_get_confirm('<?php echo $lang['wt_ensure_del'];?>', 'index.php?w=store_map&t=del_map&map_id=<?php echo $val['map_id']; ?>');" class="btn-grapefruit"><i class="icon-trash"></i><p><?php echo $lang['wt_del'];?></p></a></span>
        </td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign">&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php if (is_array($output['map_list']) && !empty($output['map_list'])) { ?>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>