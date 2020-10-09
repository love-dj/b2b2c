<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php if(!empty($output['option_list'])){?>

<div class="theme-detail-poll-content">
  <form method="post" id="poll_form" <?php if(!$output['vote_end'] && !$output['partake']){?>action="<?php echo BBS_SITE_URL;?>/index.php?w=theme&t=save_votepoll&c_id=<?php echo $output['c_id'];?>&t_id=<?php echo $output['t_id'];?>"<?php }?>>
    <input type="hidden" name="form_submit" value="ok" />
    <div class="poll-option-info">
      <h4>
        <?php if($output['poll_info']['poll_multiple'] == 1){echo $lang['bbs_checkbox_poll'];}else{echo $lang['bbs_radio_poll'];}?>
      </h4>
      <?php echo $lang['bbs_owned_by_all'];?><em><?php echo $output['poll_info']['poll_voters'];?></em><?php echo $lang['bbs_participate_in_the_vote'];?>
      <h5>
        <?php if($output['vote_end']){echo $lang['bbs_poll_ends'];}else if($output['partake']){echo $lang['bbs_have_to_vote'];}?>
      </h5>
    </div>
    <table width="100%" border=0 cellpadding="0" cellspacing="0">
      <?php $i = 0;foreach ($output['option_list'] as $val){ $i++;?>
      <tr>
        <td class="w20"><?php if($output['poll_info']['poll_multiple'] == 1){?>
          <input type="checkbox" name="pollopid[]" value="<?php echo $val['pollop_id'];?>" <?php if($output['vote_end'] || $output['partake'] || !in_array($output['identity'], array(1,2,3))){?>disabled="disabled"<?php }?> />
          <?php }else{?>
          <input type="radio" name="pollopid[]" value="<?php echo $val['pollop_id'];?>" <?php if($output['vote_end'] || $output['partake'] || !in_array($output['identity'], array(1,2,3))){?>disabled="disabled"<?php }?> />
          <?php }?></td>
        <td class="w150"><?php echo $val['pollop_option'];?></td>
        <td class="w230"><div class="poll-column">
            <p class="c<?php echo $i;?>" style="width: <?php echo intval($output['poll_info']['poll_voters']) != 0?sprintf('%.2f%%', intval($val['pollop_votes'])/intval($output['poll_info']['poll_voters'])*100):0;?>"> </p>
          <i> <?php echo intval($output['poll_info']['poll_voters']) != 0?sprintf('%.2f%%', intval($val['pollop_votes'])/intval($output['poll_info']['poll_voters'])*100):'0.00%';?></i></div></td>
        <td><?php if($val['pollop_votername'] != ''){?>
          <span><?php echo recentlyTwoVoters($val['pollop_votername']);?>&nbsp;<?php echo $lang['wt_etc'];?></span>
          <?php }?>
          <em><?php echo $val['pollop_votes'];?></em><?php echo $lang['bbs_have_poll'];?></td>
      </tr>
      <?php }?>
    </table>
    <?php if(!$output['vote_end'] && !$output['partake'] && in_array($output['identity'], array(1,2,3))){?>
    <div class="bottom"><a href="javascript:void(0);" wttype="poll_submit" class="btn" ><?php echo $lang['wt_submit'];?></a><span wttype="poll-warning" class="warning"></span> </div>
    <?php }?>
  </form>
</div>
<script>
var c_id = <?php echo $output['c_id'];?>;
var t_id = <?php echo $output['t_id'];?>;
$(function(){
	<?php if(!$output['vote_end'] && !$output['partake']){?>
	$('a[wttype="poll_submit"]').click(function(){
		if(_ISLOGIN){
			if($('input[name="pollopid[]"]:checked').length == 0){
				$('span[wttype="poll-warning"]').html('<?php echo $lang['bbs_vote_option_not_null'];?>').show();
				window.setTimeout(pollWarningHide,5000);	// 5 seconds after the hidden message
				return false;
			}
			ajaxpost('poll_form', BBS_SITE_URL+'/index.php?w=theme&t=save_votepoll&c_id='+c_id+'&t_id='+t_id, '', 'onerror');
		}else{
			login_dialog();		
		}
	});
	<?php }?>
});
function pollWarningHide(){
	$('span[wttype="poll-warning"]').hide('slow').html('');
}
</script>
<?php }?>
