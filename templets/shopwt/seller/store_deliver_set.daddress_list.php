<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <a href="javascript:void(0)" class="wtbtn wtbtn-mint" wt_type="dialog" dialog_title="<?php echo $lang['store_daddress_new_address'];?>" dialog_id="my_address_add"  uri="index.php?w=store_deliver_set&t=daddress_add" dialog_width="550" title="<?php echo $lang['store_daddress_new_address'];?>"><?php echo $lang['store_daddress_new_address'];?></a></div>
<div></div>
<table class="wtsc-default-table" >
  <thead>
    <tr>
      <th class="w70">是否默认</th>
      <th class="w90"><?php echo $lang['store_daddress_receiver_name'];?></th>
      <th class="tl"><?php echo $lang['store_daddress_deliver_address'];?></th>
      <th class="w150"><?php echo $lang['store_daddress_phone'];?></th>
      <th class="w110"><?php echo $lang['wt_handle'];?></th>
    </tr>
  </thead>
  <tbody>
    <?php if(!empty($output['address_list']) && is_array($output['address_list'])){?>
    <?php foreach($output['address_list'] as $key=>$address){?>
    <tr class="bd-line">
      <td>
        <label for="is_default_<?php echo $address['address_id'];?>"><input type="radio" id="is_default_<?php echo $address['address_id'];?>" name="is_default" <?php if ($address['is_default'] == 1) echo 'checked';?> value="<?php echo $address['address_id'];?>">
        <?php echo $lang['store_daddress_default'];?></label>
      </td>
      <td><?php echo $address['seller_name'];?></td>
      <td class="tl"><?php echo $address['area_info'];?>&nbsp;<?php echo $address['address'];?></td>
      <td><span class="tel"><?php echo $address['telphone'];?></span> <br/>
      <td class="nscs-table-handle"><span><a href="javascript:void(0);" dialog_id="my_address_edit" dialog_width="640" dialog_title="<?php echo $lang['store_daddress_edit_address'];?>" wt_type="dialog" uri="index.php?w=store_deliver_set&t=daddress_add&address_id=<?php echo $address['address_id'];?>" class="btn-bluejeans"><i class="icon-edit"></i>
        <p><?php echo $lang['wt_edit'];?></p>
        </a></span><span> <a href="javascript:void(0)" onclick="ajax_get_confirm('<?php echo $lang['wt_ensure_del'];?>', 'index.php?w=store_deliver_set&t=daddress_del&address_id=<?php echo $address['address_id'];?>');" class="btn-grapefruit"><i class="icon-trash"></i>
        <p><?php echo $lang['wt_del'];?></p>
        </a></span></td>
    </tr>
    <?php }?>
    <?php }else{?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php }?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="20">&nbsp;</td>
    </tr>
  </tfoot>
</table>
<script>
$(function (){
	$('input[name="is_default"]').on('click',function(){
		$.get('index.php?w=store_deliver_set&t=daddress_default_set&address_id='+$(this).val(),function(result){})
	});
});
</script> 
