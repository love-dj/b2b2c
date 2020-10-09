<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <a href="javascript:void(0)" class="wtbtn wtbtn-mint" wt_type="dialog" dialog_title="<?php echo $lang['store_goods_brand_apply'];?>" dialog_id="my_goods_brand_apply" dialog_width="480" uri="index.php?w=store_brand&t=brand_add"><?php echo $lang['store_goods_brand_apply'];?></a></div>
<table class="search-form">
  <form method="get">
    <input type="hidden" name="w" value="store_brand">
    <input type="hidden" name="t" value="brand_list">
    <tr>
      <td>&nbsp;</td>
      <th><?php echo $lang['store_goods_brand_name'];?></th>
      <td class="w160"><input type="text" class="text" name="brand_name" value="<?php echo $_GET['brand_name']; ?>"/></td>
      <td class="w70 tc"><label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['wt_search'];?>" /></label></td>
    </tr>
  </form>
</table>
<table class="wtsc-default-table">
  <thead>
    <tr>
      <th class="w150"><?php echo $lang['store_goods_brand_icon'];?></th>
      <th><?php echo $lang['store_goods_brand_name'];?></th>
      <th><?php echo $lang['store_goods_brand_belong_class'];?></th>
      <th class="w100"><?php echo $lang['wt_handle'];?></th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['brand_list'])) { ?>
    <?php foreach($output['brand_list'] as $val) { ?>
    <tr class="bd-line">
      <td><img src="<?php echo brandImage($val['brand_pic']);?>" onload="javascript:DrawImage(this,88,44);" /></td>
      <td><?php echo $val['brand_name']; ?></td>
      <td><?php echo $val['brand_class']; ?></td>
      <td class="nscs-table-handle"><?php if ($val['brand_apply'] == 0) { ?>
     <span><a href="javascript:void(0)" class="btn-bluejeans" wt_type="dialog" dialog_title="<?php echo $lang['store_goods_brand_edit'];?>" dialog_id="my_goods_brand_edit" dialog_width="480" uri="index.php?w=store_brand&t=brand_add&brand_id=<?php echo $val['brand_id']; ?>"><i class="icon-edit"></i><p><?php echo $lang['wt_edit'];?></p></a></span>
        <span><a href="javascript:void(0)" class="btn-grapefruit" onclick="ajax_get_confirm('<?php echo $lang['wt_ensure_del'];?>', 'index.php?w=store_brand&t=drop_brand&brand_id=<?php echo $val['brand_id']; ?>');"><i class="icon-trash"></i><p><?php echo $lang['wt_del'];?></p></a></span><?php } ?></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php if (!empty($output['brand_list'])) { ?>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
