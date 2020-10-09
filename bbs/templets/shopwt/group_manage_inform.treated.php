<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="base-box">
  <div class="mainbox">
    <form method="post" action="<?php echo BBS_SITE_URL;?>/index.php?w=manage&t=inform&c_id=<?php echo $output['c_id'];?>" id="inform_form" onsubmit="ajaxpost('inform_form', '<?php echo BBS_SITE_URL;?>/index.php?w=manage&t=inform&c_id=<?php echo $output['c_id'];?>', '', 'onerror');">
      <input type="hidden" name="form_submit" value="ok" />
      <?php include bbs_template('group_manage_top');?>
      <table class="base-table-style">
        <thead>
          <tr>
            <th class="w30"></th>
            <th class="tl"><?php echo $lang['bbs_inform_info'];?></th>
            <th class="w120"><?php echo $lang['bbs_informer'];?></th>
            <th class="w120"><?php echo $lang['bbs_handle_result'];?></th>
            <th class="w90"><?php echo $lang['wt_handle'];?></th>
          </tr>
          <tr>
            <td class="tc"><input id="all" class="checkall" type="checkbox" /></td>
            <td colspan="20"><label for="all" class="handle-btn"><i class="ac0"></i><?php echo $lang['wt_check_all'];?></label>
              <a href="javascript:void(0);" class="handle-btn" wttype="batchbutton" uri="<?php echo BBS_SITE_URL;?>/index.php?w=manage&t=delinform&c_id=<?php echo $output['c_id'];?>" name="i_id" confirm="<?php echo $lang['wt_ensure_del'];?>" title="<?php echo $lang['wt_delete'];?>"><i class="ac5"></i><?php echo $lang['wt_delete'];?></a></td>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($output['inform_list'])){?>
          <?php foreach ($output['inform_list'] as $val){?>
          <tr>
            <td><input type="checkbox" class="checkitem" name="i_id[]" value="<?php echo $val['inform_id'];?>" /></td>
            <td class="tl"><dl class="inform">
                <dt><strong><?php echo $lang['bbs_inform_url'].$lang['wt_colon'];?></strong><a href="<?php echo $val['url'];?>" target="_blank"><?php echo $val['title'];?></a></dt>
                <dd><strong><?php echo $lang['bbs_inform_content'].$lang['wt_colon'];?></strong><span class="tip" title="<?php echo $val['inform_content'];?>"><?php echo $val['inform_content'];?></span></dd>
              </dl></td>
            <td><?php echo $val['member_name'];?></td>
            <td class="inform-hanele"><p><strong><?php echo $lang['bbs_handler'].$lang['wt_colon'];?></strong><span><?php echo $val['inform_opname'];?></span></p>
              <p>
                <strong><?php echo $lang['bbs_handle_rewards'].$lang['wt_colon'];?></strong><span><?php $lang['bbs_rewards'].$lang['wt_colon']; if($val['inform_opexp'] == '0'){echo $lang['bbs_not_rewards'];}else{ echo $val['inform_opexp'].$lang['bbs_inform_exp'];}?></span>
              </p></td>
            <td class="handle"><a href="javascript:void(0);" onclick="delinform(<?php echo $val['inform_id'];?>);"><?php echo $lang['wt_delete'];?></a></td>
          </tr>
          <tr>
            <td colspan="5" class="inform-note"><?php echo $lang['bbs_message'].$lang['wt_colon'].$val['inform_opresult'];?></td>
          </tr>
          <?php }?>
          <?php }else{?>
          <tr>
            <td colspan="20" class="noborder"><p class="no-record"><?php echo $lang['no_record'];?></p></td>
          </tr>
          <?php }?>
        </tbody>
        <?php if(!empty($output['inform_list'])){?>
        <tfoot>
          <tr>
            <th class="tc"><input id="all" class="checkall" type="checkbox" /></th>
            <th colspan="20"><label for="all" class="handle-btn"><i class="ac0"></i><?php echo $lang['wt_check_all'];?></label>
              <a href="javascript:void(0);" class="handle-btn" wttype="batchbutton" uri="<?php echo BBS_SITE_URL;?>/index.php?w=manage&t=delinform&c_id=<?php echo $output['c_id'];?>" name="i_id" confirm="<?php echo $lang['wt_ensure_del'];?>" title="<?php echo $lang['wt_delete'];?>"><i class="ac5"></i><?php echo $lang['wt_delete'];?></a> </th>
          </tr>
          <tr>
            <td colspan="20"><div class="pagination"><?php echo $output['show_page'];?></div></td>
          </tr>
        </tfoot>
        <?php }?>
      </table>
    </form>
  </div>
  <div class="sidebar">
    <?php include bbs_template('group_manage_sidebar');?>
  </div>
</div>
<script src="<?php echo BBS_STATIC_SITE_URL;?>/js/bbs_manage.js"></script> 
<script>
var c_id = <?php echo $output['c_id'];?>;
function delinform(i_id){
	showDialog('<?php echo $lang['wt_ensure_del'];?>', 'confirm', '', function(){
		_uri = BBS_SITE_URL+'/index.php?w=manage&t=delinform&c_id='+c_id+'&i_id='+i_id;
		ajaxget(_uri);
	});
}
//Ajax提示
$(function(){
	$('.tip').poshytip({
		className: 'tip-yellowsimple',
		showTimeout: 1,
		alignTo: 'target',
		alignX: 'center',
		alignY: 'bottom',
		offsetY: 5,
		allowTipHover: false
	});
});
</script>