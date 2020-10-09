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
  <div class="layout-l">
  <div class="search-group-list">
  <?php if(!empty($output['bbs_list'])){?>
    <ul>
    <?php foreach($output['bbs_list'] as $val){?>
      <li>
        <dl class="group-info">
          <dt class="group-name">
            <a href="<?php echo BBS_SITE_URL;?>/index.php?w=group&c_id=<?php echo $val['bbs_id'];?>"><?php echo $val['bbs_name']?></a>
            <?php echo memberLevelHtml($output['cm_array'][$val['bbs_id']]);?>
          </dt>
          <dd class="group-pic"><a href="<?php echo BBS_SITE_URL;?>/index.php?w=group&c_id=<?php echo $val['bbs_id'];?>"><img src="<?php echo bbsLogo($val['bbs_id']);?>" /></a></dd>
          <dd class="group-time"><?php echo $lang['bbs_created_at'];?><em><?php echo @date('Y-m-d', $val['bbs_addtime'])?></em></dd>
          <dd class="group-total"><em><?php echo $val['bbs_mcount'];?></em><?php echo $lang['bbs_members'];?></dd>
          <dd class="group-intro"><?php echo $val['bbs_desc'];?></dd>
        </dl>
      </li>
      <?php }?>
    </ul>
    <?php }else{?>
    <div class="no-theme"><span> <i></i><?php echo $lang['p_center_not_jion_bbs'];?></span></div>
    <?php }?>
  </div></div>
</div>
  <?php include bbs_template('p_center.sidebar');?>
</div>