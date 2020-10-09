<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="search-page">
  <div class="layout-l">
    <div class="search-title">
      <h3><?php echo $lang['bbs_to_search'];?>"<em><?php echo $output['count'];?></em>"<?php echo $lang['bbs_item'];?><?php if($_GET['keyword'] != ''){?><?php echo $lang['bbs_yu'];?>"<em><?php echo $_GET['keyword'];?></em>"<?php echo $lang['bbs_relevant'];?><?php }elseif($_GET['class_name'] != ''){?><?php echo $lang['bbs_yu'];?>"<em><?php echo $_GET['class_name'];?></em>"<?php echo $lang['bbs_class_relavant']; }?><?php echo $lang['bbs_result'];?></h3>
    </div>
  <div class="search-group-list">
  <?php if(!empty($output['bbs_list'])){?>
    <ul>
    <?php foreach($output['bbs_list'] as $val){?>
      <li>
        <dl class="group-info">
          <dt class="group-name"><a href="<?php echo BBS_SITE_URL;?>/index.php?w=group&c_id=<?php echo $val['bbs_id'];?>"><?php echo $val['bbs_name']?></a></dt>
          <dd class="group-pic"><a href="<?php echo BBS_SITE_URL;?>/index.php?w=group&c_id=<?php echo $val['bbs_id'];?>"><img src="<?php echo bbsLogo($val['bbs_id']);?>" /></a></dd>
          <dd class="group-time"><?php echo $lang['bbs_created_at'];?><em><?php echo @date('Y-m-d', $val['bbs_addtime'])?></em></dd>
          <dd class="group-total"><em><?php echo $val['bbs_mcount'];?></em><?php echo $lang['bbs_members'];?></dd>
          <dd class="group-intro"><?php echo $val['bbs_desc'];?></dd>
        </dl>
      </li>
      <?php }?>
    </ul>
    <div class="pagination"><?php echo $output['show_page'];?></div>
    <?php }else{?>
    <div class="no-theme">
      <i></i>
      <span><?php echo $lang['bbs_result_null'].L('wt_comma,bbs_go');?><a href="<?php echo BBS_SITE_URL;?>"><?php echo L('bbs_home_page_around');?></a></span>
      <br>
      <span class="theme-but"><?php echo $lang['bbs_search_null_msg'];?><a href="<?php echo BBS_SITE_URL;?>/index.php?w=index&t=add_group&kw=<?php echo $_GET['keyword'];?>"><?php echo $lang['bbs_instantly_create'];?></a></span>
    </div>
    <div></div>
    <?php }?>
  </div></div>
  <div class="layout-r">
    <?php require_once bbs_template('index.themetop');?>
  </div>
</div>