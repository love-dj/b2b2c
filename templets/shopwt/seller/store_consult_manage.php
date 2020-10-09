<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<form method="get" action="index.php">
  <input type="hidden" name="w" value="store_consult" />
  <input type="hidden" name="t" value="consult_list" />
  <?php if (in_array($_GET['type'], array('to_reply', 'replied'))) {?>
  <input type="hidden" name="type" value="<?php echo $_GET['type'];?>" />
  <?php }?>
  <table class="search-form">
    <tr>
      <td>&nbsp;</td>
      <th>咨询类型</th>
      <td class="w160"><select name="ctid" class="w150">
          <option value="0">全部</option>
          <?php if (!empty($output['consult_type'])) {?>
          <?php foreach ($output['consult_type'] as $val) {?>
          <option <?php if (intval($_GET['ctid']) == $val['ct_id']) {?>selected="selected"<?php }?> value="<?php echo $val['ct_id']?>"><?php echo $val['ct_name'];?></option>
          <?php }?>
          <?php }?>
        </select></td>
      <td class="tc w70"><label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['wt_search'];?>" /></label></td>
    </tr>
  </table>
</form>
<table class="wtsc-default-table">
  <thead>
    <tr>
      <th class="w30"></th>
      <th><?php echo $lang['store_consult_reply'];?></th>
      <th class="w30"></th>
      <th class="w110"><?php echo $lang['wt_handle'];?></th>
    </tr>
    <?php if (count($output['list_consult'])>0) { ?>
    <tr>
      <td class="tc"><input id="all" type="checkbox" class="checkall" /></td>
      <td colspan="20"><label for="all"><?php echo $lang['wt_select_all'];?></label>
        <a href="javascript:void(0);" class="wtbtn-mini" wt_type="batchbutton" uri="index.php?w=store_consult&t=drop_consult" name="id" confirm="<?php echo $lang['wt_ensure_del'];?>"><i class="icon-trash"></i><?php echo $lang['wt_del'];?></a></td>
    </tr>
    <?php }?>
  </thead>
  <tbody>
    <?php if (count($output['list_consult'])>0) { ?>
    <?php foreach($output['list_consult'] as $consult){?>
    <tr>
      <th colspan="20" class="tl"><input type="checkbox"  value="<?php echo $consult['consult_id'];?>" class="checkitem ml10 mr10" />
        <span><a href="index.php?w=goods&goods_id=<?php echo $consult['goods_id'];?>" target="_blank"><?php echo $consult['goods_name'];?></a></span><span class="ml20"><?php echo $lang['store_consult_list_consult_member'].$lang['wt_colon'];?></span>
        <?php if($consult['member_id'] == "0"){ echo $lang['wt_guest']; } else { echo $consult['isanonymous'] == 1?str_cut($consult['member_name'],2).'***':$consult['member_name']; }?>
        <?php if ($consult['member_id']>0 && $consult['isanonymous'] == 0) { ?>
        <span member_id="<?php echo $consult['member_id'];?>"></span>
        <?php }?>
        <span class="ml20"><?php echo $lang['store_consult_list_consult_time'].$lang['wt_colon'];?><em class="goods-time"><?php echo date("Y-m-d H:i:s",$consult['consult_addtime']);?></em></span></th>
    </tr>
    <tr>
      <td rowspan="2"></td>
      <td class="tl"><strong><?php echo $lang['store_consult_list_consult_content'].$lang['wt_colon'];?></strong><span class="gray"><?php echo nl2br($consult['consult_content']);?></span></td>
      <td rowspan="2"></td>
      <td rowspan="2" class="nscs-table-handle vt"><?php if($consult['consult_reply'] == ''){?>
        <span><a href="javascript:void(0);" class="btn-aqua" wt_type="dialog" dialog_id="my_qa_reply" dialog_title="<?php echo $lang['store_consult_list_reply_consult'];?>" dialog_width="460" uri="index.php?w=store_consult&t=reply_consult&id=<?php echo $consult['consult_id'];?>"><i class="icon-comments-alt "></i><p><?php echo $lang['store_consult_list_reply'];?></p></a></span>
        <?php }else{?>
        <span><a href="javascript:void(0);" wt_type="dialog" dialog_id="my_qa_edit_reply" dialog_title="<?php echo $lang['store_consult_list_reply_consult'];?>" dialog_width="480" uri="index.php?w=store_consult&t=reply_consult&id=<?php echo $consult['consult_id'];?>" class="btn-bluejeans"><i class="icon-edit"></i><p><?php echo $lang['wt_edit'];?></p></a></span>
        <?php }?>
        <span><a href="javascript:void(0)" onclick="ajax_get_confirm('<?php echo $lang['wt_ensure_del'];?>', 'index.php?w=store_consult&t=drop_consult&id=<?php echo $consult['consult_id'];?>');" class="btn-grapefruit"><i class="icon-trash"></i><p><?php echo $lang['wt_del'];?></p></a> </span></td>
    </tr>

    <tr><?php if($consult['consult_reply']!=""){?>
      <td class="tl"><strong><?php echo $lang['store_consult_list_my_reply'].$lang['wt_colon'];?></strong><span class="gray"><?php echo nl2br($consult['consult_reply']);?></span><span class="ml10 goods-time">(<?php echo date("Y-m-d H:i:s",$consult['consult_reply_time']);?>)</span></td>
    <?php }?></tr>

    <?php }?>
    <?php }else{?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php }?>
  </tbody>
  <tfoot>
    <?php if (count($output['list_consult'])>0) { ?>
    <tr>
      <th class="tc"><input id="all" type="checkbox" class="checkall" /></th>
      <th colspan="20"><label for="all2"><?php echo $lang['wt_select_all'];?></label>
        <a href="javascript:void(0);" class="wtbtn-mini" wt_type="batchbutton" uri="index.php?w=store_consult&t=drop_consult" name="id" confirm="<?php echo $lang['wt_ensure_del'];?>"><i class="icon-trash"></i><?php echo $lang['wt_del'];?></a></th>
    </tr>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php }?>
  </tfoot>
</table>