<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="base-box">
  <div class="mainbox">
    <?php include bbs_template('group_manage_top');?>
    <table class="base-table-style">
      <thead>
        <tr>
          <th class="w30"></th>
          <th><?php echo $lang['bbs_member'];?></th>
          <th class="w100"><?php echo $lang['bbs_apply_time'];?></th>
          <th class="w180"><?php echo $lang['bbs_apply_reason'];?></th>
          <th class="w180"><?php echo $lang['bbs_self_introduction'];?></th>
          <th class="w90"><?php echo $lang['wt_handle'];?></th>
        </tr>
        <?php if(!empty($output['cm_list'])){?>
        <tr>
          <td class="tc"><input id="all" class="checkall" type="checkbox" /></td>
          <td colspan="20"><label for="all" class="handle-btn"><i class="ac0"></i><?php echo $lang['wt_check_all'];?></label>
            <a href="javascript:void(0);" class="handle-btn" wttype="batchbutton" uri="<?php echo BBS_SITE_URL;?>/index.php?w=manage&t=applying_manage&type=yes&c_id=<?php echo $output['c_id'];?>" name="cm_id"><i class="ac6"></i><?php echo $lang['bbs_pass'];?></a>
            <a href="javascript:void(0);" class="handle-btn" wttype="batchbutton" uri="<?php echo BBS_SITE_URL;?>/index.php?w=manage&t=applying_manage&type=no&c_id=<?php echo $output['c_id'];?>" name="cm_id" confirm="<?php echo $lang['bbs_refuse_confirm'];?>"><i class="ac7"></i><?php echo $lang['bbs_refuse'];?></a></td>
        </tr>
        <?php }?>
      </thead>
      <tbody>
        <?php if(!empty($output['cm_list'])){?>
        <?php foreach ($output['cm_list'] as $val){?>
        <tr>
          <td><input class="checkitem" type="checkbox" value="<?php echo $val['member_id'];?>"></td>
          <td><dl class="member-base"><dt class="name"><a href="<?php echo BASE_SITE_URL;?>/index.php?w=sns_bbs&mid=<?php echo $val['member_id'];?>" target="_blank"><?php echo $val['member_name'];?></a></dt><dd class="member-avatar-s"><img src="<?php echo getMemberAvatarForID($val['member_id']);?>" /></dd>
            </dl></td>
          <td><?php echo date('Y-m-d', $val['cm_applytime']);?></td>
          <td><div class="long-text tip" title="<?php echo $val['cm_applycontent'];?>" style="color:#399EC8;"><?php echo $val['cm_applycontent'];?></div></td>
          <td><div class="long-text tip" title="<?php echo $val['cm_intro'];?>" style="color:#F66"><?php echo $val['cm_intro'];?></div></td>
          <td class="handle"><a href="javascript:void(0);" onclick="applyingManage('yes', <?php echo $val['member_id'];?>);"><i class="ac6"></i><?php echo $lang['bbs_pass'];?></a>&nbsp;|&nbsp;<a href="javascript:void(0);" onclick="applyingManage('no', <?php echo $val['member_id'];?>);"><i class="ac7"></i><?php echo $lang['bbs_refuse'];?></a></td>
        </tr>
        <?php }?>
        <?php }else{?>
        <tr>
          <td colspan="20" class="noborder"><p class="no-record"><?php echo $lang['no_record'];?></p></td>
        </tr>
        <?php }?>
      </tbody>
      <?php if(!empty($output['cm_list'])){?>
      <tfoot>
        <tr>
          <th class="tc"><input id="all" class="checkall" type="checkbox" /></th>
          <th colspan="20"><label for="all" class="handle-btn"><i class="ac0"></i><?php echo $lang['wt_check_all'];?></label>
            <a href="javascript:void(0);" class="handle-btn" wttype="batchbutton" uri="<?php echo BBS_SITE_URL;?>/index.php?w=manage&t=applying_manage&type=yes&c_id=<?php echo $output['c_id'];?>" name="cm_id"><i class="ac6"></i><?php echo $lang['bbs_pass'];?></a>
            <a href="javascript:void(0);" class="handle-btn" wttype="batchbutton" uri="<?php echo BBS_SITE_URL;?>/index.php?w=manage&t=applying_manage&type=no&c_id=<?php echo $output['c_id'];?>" name="cm_id" confirm="<?php echo $lang['bbs_refuse_confirm'];?>"><i class="ac7"></i><?php echo $lang['bbs_refuse'];?></a></th>
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
<script>
function applyingManage(type, id){
	_uri = "<?php echo BBS_SITE_URL;?>/index.php?w=manage&t=applying_manage&type="+type+"&c_id=<?php echo $output['c_id'];?>&cm_id="+id;
	if(type == 'yes'){
		ajaxget(_uri);
	}else if(type == 'no'){
		showDialog('<?php echo $lang['bbs_refuse_confirm'];?>', 'confirm', '', function(){
			ajaxget(_uri);
		});
	}
}
//Ajax提示
$(function(){
	$('.tip').poshytip({
		className: 'tip-yellowsimple',
		showTimeout: 1,
		alignTo: 'target',
		alignX: 'center',
		alignY: 'top',
		offsetY: 5,
		allowTipHover: false
	});
});
</script>
