<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php if($output['bbs_info']['bbs_status'] == 1){?>
<link href="<?php echo BBS_TEMPLATES_URL;?>/css/ubb.css" rel="stylesheet" type="text/css">
<div class="group warp-all">
  <?php require_once bbs_template('group.top');?>
  <div class="group-level-intro">
    <h3><?php echo $lang['level_h3_1'];?></h3>
    <table class="base-table-style">
      <thead>
        <tr>
          <th><?php echo $lang['level'];?></th>
          <th><?php echo $lang['level_rank'];?></th>
          <th><?php echo $lang['level_need_exp'];?></th>
          <th><?php echo $lang['level'];?></th>
          <th><?php echo $lang['level_rank'];?></th>
          <th><?php echo $lang['level_need_exp'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php for($i=1;$i<=8;$i++){?>
        <tr>
          <td height="40"><?php echo $i;?></td>
          <td><?php echo memberLevelHtml(array('cm_level'=>$i, 'cm_levelname'=>$output['ml_info']['ml_'.$i], 'bbs_id'=>$output['c_id']));?></td>
          <td style="border-right: solid 1px #E5ECEE"><?php echo $output['mld_array'][$i]['mld_exp'];?></td>
          <td><?php echo $i+8;?></td>
          <td><?php echo memberLevelHtml(array('cm_level'=>$i+8, 'cm_levelname'=>$output['ml_info']['ml_'.($i+8)], 'bbs_id'=>$output['c_id']));?></td>
          <td><?php echo $output['mld_array'][($i+8)]['mld_exp'];?></td>
        </tr>
        <?php }?>
      </tbody>
    </table>
    <h3><?php echo $lang['level_h3_2'];?></h3>
    <table class="base-table-style">
      <thead>
        <tr>
          <th><?php echo $lang['level_user_action'];?></th>
          <th><?php echo $Lang['level_exp_in_table'];?></th>
          <th><?php echo $lang['level_daily_exp_ceiling']?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td height="40"><?php echo $lang['level_release_theme'];?></td>
          <td style="border-right: solid 1px #E5ECEE"><?php echo C('bbs_exprelease');?></td>
          <td rowspan="2"><?php echo C('bbs_expreleasemax');?></td>
        </tr>
        <tr>
          <td height="40"><?php echo $lang['level_reply_theme'];?></td>
          <td style="border-right: solid 1px #E5ECEE"><?php echo C('bbs_expreply');?></td>
        </tr>
        <tr>
          <td height="40"><?php echo $lang['level_replied_theme'];?></td>
          <td style="border-right: solid 1px #E5ECEE"><?php echo C('bbs_expreplied');?></td>
          <td><?php echo C('bbs_exprepliedmax');?></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="20"><p><?php echo $lang['level_needing_attention'];?></p></th>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
<link href="<?php echo STATIC_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.nyroModal/custom.min.js"></script> 
<?php }else if($output['bbs_info']['bbs_status'] == 2){?>
<div class="warp-all">
  <div class="bbs-status"><i class="icon02"></i>
    <h3><?php echo $lang['bbs_is_under_approval'];?></h3>
  </div>
</div>
<?php }else if($output['bbs_info']['bbs_status'] == 3){?>
<div class="warp-all">
  <div class="bbs-status"><i class="icon03"></i>
    <h3><?php echo $lang['bbs_approval_fail'];?></h3>
    <?php if($output['bbs_info']['bbs_statusinfo'] != ''){echo '<h5>'.$lang['bbs_reason'].$lang['wt_colon'].$output['bbs_info']['bbs_statusinfo'].'</h5>'; }?>
  </div>
</div>
<?php }else{?>
<div class="warp-all">
  <div class="bbs-status"><i class="icon01"></i>
    <h3><?php echo $lang['bbs_is_closed'];?></h3>
    <?php if($output['bbs_info']['bbs_statusinfo'] != ''){echo '<h5>'.$lang['bbs_reason'].$lang['wt_colon'].$output['bbs_info']['bbs_statusinfo'].'</h5>'; }?>
  </div>
</div>
<?php }?>
