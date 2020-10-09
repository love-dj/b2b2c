<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="base-tab-menu">
  <dl class="my-manage">
    <dt class="group-name"><strong><a href="<?php echo BBS_SITE_URL;?>/index.php?w=group&c_id=<?php echo $output['c_id'];?>"><?php echo $output['bbs_info']['bbs_name'];?></a></strong>[<?php echo $lang['bbs_management_center'];?>]</dt>
    <dd class="group-pic"><img src="<?php echo bbsLogo($output['bbs_info']['bbs_id']);?>" /></dd>
    <dd class="group-ID"> <?php echo memberIdentity($output['identity']);?> </dd>
  </dl>
  <ul class="base-tab-nav" id="jsddm">
    <?php if(!empty($output['sidebar_menu'])){?>
    <?php foreach($output['sidebar_menu'] as $key=>$val){?>
    <li <?php if($output['sidebar_sign'] == $key){ echo 'class="selected"';}?>>
      <?php if($key == 'applying' && $output['bbs_info']['new_verifycount'] != 0){?>
      <sup><?php echo $output['bbs_info']['new_verifycount'];?></sup>
      <?php }else if($key == 'inform' && $output['bbs_info']['new_informcount'] != 0){?>
      <sup><?php echo $output['bbs_info']['new_informcount'];?></sup>
      <?php }else if($key == 'managerapply' && $output['bbs_info']['new_mapplycount'] != 0){?>
      <sup><?php echo $output['bbs_info']['new_mapplycount'];?></sup>
      <?php }?>
      <a href="<?php echo $val['menu_url'];?>"><?php echo $val['menu_name'];?></a>
      <?php if(!empty($val['menu_child'])){?>
      <div class="tabs-child-menu">
        <?php foreach ($val['menu_child'] as $k=>$v){?>
        <a href="<?php echo $v['url'];?>" <?php if($output['sidebar_child_sign'] == $k){echo 'class="selected"';}?>><i></i><?php echo $v['name'];?></a>
        <?php }?>
      </div>
      <?php }?>
    </li>
    <?php }?>
    <?php }?>
  </ul>
</div>
