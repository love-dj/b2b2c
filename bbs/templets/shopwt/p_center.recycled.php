<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="base-box">
  <div class="mainbox">
    <div class="base-tab-menu">
      <ul class="base-tab-nav">
        <?php if(!empty($output['member_menu'])){?>
        <?php foreach ($output['member_menu'] as $val){?>
        <li <?php if($val['menu_key'] == $output['menu_key']){?>class="selected"<?php }?>><a href="<?php echo $val['menu_url'];?>"><?php echo $val['menu_name'];?></a></li>
        <?php }?>
        <?php }?>
      </ul>
    </div>
    <div class="my-theme-list">
      <?php if(!empty($output['recycle_list'])){?>
      <ul class="group-theme-list">
        <?php foreach($output['recycle_list'] as $val){?>
        <li>
          <dl class="theme-info">
            <?php if($val['recycle_type'] == 1){?>
            <dt><span class="theme-title"><?php echo $lang['p_center_you_are_in'];?> <a href="<?php echo BBS_SITE_URL;?>/index.php?w=group&c_id=<?php echo $val['bbs_id'];?>"><?php echo $val['bbs_name'];?></a> <?php echo $lang['p_center_published_theme'].$lang['wt_colon'];?><a href="javascript:void(0);"><?php echo $lang['wt_quote1'].$val['theme_name'].$lang['wt_quote2'];?></a></span></dt>
            <dd class="theme-content">
              <div class="theme-summary"><?php echo replaceUBBTag($val['recycle_content'], 0);?></div>
              <div class="theme-info">
                <time><?php echo date('Y-m-d H:i:s', $val['recycle_time']);?></time>
                &nbsp;<span class="group-name"><?php echo $lang['p_center_be'];?>&nbsp;<a href="javascript:void(0)"><?php echo $val['recycle_opname'];?></a>&nbsp;<?php echo $lang['wt_delete'];?></span></div>
            </dd>
            <?php }elseif ($val['recycle_type'] == 2){?>
            <dt><span class="theme-title"><?php echo $lang['p_center_you_are_in'];?> <a href="<?php echo BBS_SITE_URL;?>/index.php?w=group&c_id=<?php echo $val['bbs_id'];?>"><?php echo $val['bbs_name'];?></a> <?php echo $lang['p_center_published_reply'].$lang['wt_colon'];?><a href="javascript:void(0);"><?php echo $lang['wt_quote1'].replaceUBBTag($val['recycle_content'], 0).$lang['wt_quote2'];?></a></span></dt>
            <dd class="theme-content">
              <div class="theme-summary"><?php echo replaceUBBTag($val['recycle_content'], 0);?></div>
              <div class="theme-info">
                <time><?php echo date('Y-m-d H:i:s', $val['recycle_time']);?></time>
                &nbsp;<span class="group-name"><?php echo $lang['p_center_be'];?>&nbsp;<a href="javascript:void(0)"><?php echo $val['recycle_opname'];?></a>&nbsp;<?php echo $lang['wt_delete'];?></span></div>
            </dd>
            <?php }?>
          </dl>
        </li>
        <?php }?>
      </ul>
      <div class="pagination bottom"><a href="javascript:void(0);" class="submit-btn" style=" float: right;" onclick="if(confirm('<?php echo $lang['wt_ensure_del'];?>')){ajaxget('<?php echo BBS_SITE_URL?>/index.php?w=p_center&t=clr_recycled')}"><?php echo $lang['p_center_clear_recycle'];?></a><?php echo $output['show_page'];?></div>
      <?php }else{?>
      <div class="no-theme"><span><i></i><?php echo $lang['p_center_recycled_null'];?></span></div>
      <?php }?>
    </div>
  </div>
  <?php include bbs_template('p_center.sidebar');?>
  <div class="clear"></div>
</div>
