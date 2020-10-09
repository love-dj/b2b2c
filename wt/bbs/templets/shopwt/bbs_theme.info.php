<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?w=bbs_theme&t=theme_list" title="返回<?php echo $lang['bbs_theme_list'];?>"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['wt_bbs_thememanage'];?> - <?php echo $lang['bbs_theme_info'];?>“<?php echo $output['theme_info']['theme_name'];?>”</h3>
        <h5><?php echo $lang['wt_bbs_thememanage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="pointprod_form" method="post" enctype="multipart/form-data" >
    <input type="hidden" name="form_submit" value="ok" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit"><?php echo $lang['bbs_theme'];?></dt>
        <dd class="opt"><?php echo $output['theme_info']['theme_name'];?></dd>
      </dl>
      <?php if($output['theme_info']['theme_special'] == 1){?>
      <dl class="row">
        <dt class="tit"><?php echo $lang['bbs_poll_info'];?></dt>
        <dd class="opt">
          <ul>
            <li><?php echo $lang['bbs_poll_form'].$lang['wt_colon'];if($output['poll_info']['poll_multiple'] == 0){echo $lang['bbs_poll_radio'];}else{echo $lang['bbs_poll_checkbox'];}?></li>
            <li><?php echo $lang['bbs_poll_starttime'].$lang['wt_colon'].date('Y-m-d H:i:s', $output['poll_info']['poll_startime']);?></li>
            <li><?php echo $lang['bbs_poll_days'].$lang['wt_colon'];if($output['poll_info']['poll_days'] == 0){echo $lang['wt_nothing'];}else{ echo $output['poll_info']['poll_days'];}?></li>
            <li><?php echo $lang['bbs_poll_sum'].$lang['wt_colon'].$output['poll_info']['poll_voters'];?></li>
          </ul>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['bbs_poll_option'];?></dt>
        <dd class="opt">
          <?php if(!empty($output['option_list'])){?>
          <ul class="wtap-ajax-add">
            <?php foreach ($output['option_list'] as $val){?>
            <li>
              <label class="w300"><?php echo $lang['bbs_poll_option'];?>：<?php echo $val['pollop_option'];?></label>
              <label class="w150"><?php echo $lang['bbs_poll_option_count'];?>：<?php echo $val['pollop_votes'];?></label>
              <label class="w200"><?php echo $lang['bbs_poll_option_participant'];?>：<?php echo $val['pollop_votername'];?></label>
            </li>
            <?php }?>
          </ul>
          <?php }?>
        </dd>
      </dl>
      <?php }?>
      <dl class="row">
        <dt class="tit"><?php echo $lang['bbs_theme_content'];?></dt>
        <dd class="opt"><?php echo ubb($output['theme_info']['theme_content']);?></dd>
      </dl>
      <div class="bot"> <a href="index.php?w=bbs_theme&t=theme_reply&t_id=<?php echo $output['theme_info']['theme_id'];?>" class="wtap-btn-big wtap-btn-blue mr10"><?php echo $lang['bbs_theme_check_reply'];?></a> <a href="JavaScript:void(0);" onclick="if(confirm('<?php echo $lang['bbs_theme_del_confirm'];?>')){location.href='index.php?w=bbs_theme&t=theme_del&c_id=<?php echo $output['theme_info']['bbs_id'];?>&t_id=<?php echo $output['theme_info']['theme_id'];?>';}else{return false;}" class="wtap-btn-big wtap-btn-red" id="delTheme"><?php echo $lang['bbs_theme_del'];?></a></div>
    </div>
  </form>
</div>
