<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <a href="javascript:void(0)" class="wtbtn wtbtn-mint" onclick="go('index.php?w=store_account_group&t=group_add');" title="添加账号"><i class="icon-group"></i>添加组</a> </div>
<table class="wtsc-default-table">
  <thead>
    <tr>
      <th class="w30"></th>
      <th class="tl">组名</th>
      <th class="w100"><?php echo $lang['wt_handle'];?></th>
    </tr>
  </thead>
  <tbody>
    <?php if(!empty($output['seller_group_list']) && is_array($output['seller_group_list'])){?>
    <?php foreach($output['seller_group_list'] as $key => $value){?>
    <tr class="bd-line">
      <td></td>
      <td class="tl"><?php echo $value['group_name'];?></td>
      <td class="nscs-table-handle"><span><a href="<?php echo urlShop('store_account_group', 'group_edit', array('group_id' => $value['group_id']));?>" class="btn-bluejeans"><i class="icon-edit"></i>
        <p><?php echo $lang['wt_edit'];?></p>
        </a></span><span><a wttype="btn_del_group" data-group-id="<?php echo $value['group_id'];?>" href="javascript:;" class="btn-grapefruit"><i class="icon-trash"></i>
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
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
  </tfoot>
</table>
<form id="del_form" method="post" action="<?php echo urlShop('store_account_group', 'group_del');?>">
  <input id="del_group_id" name="group_id" type="hidden" />
</form>
<script type="text/javascript">
    $(document).ready(function(){
        $('[wttype="btn_del_group"]').on('click', function() {
            var group_id = $(this).attr('data-group-id');
            if(confirm('确认删除？')) {
                $('#del_group_id').val(group_id);
                ajaxpost('del_form', '', '', 'onerror');
            }
        });
    });
</script> 
