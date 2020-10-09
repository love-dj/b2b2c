<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="warp-all">
  <div class="mainbox">
    <div class="sitenav-bar">
        <div class="sitenav"><?php echo $lang['current_location'];?><?php echo $lang['wt_colon'];?> <?php echo $lang['news_site_name'];?> > <?php echo $lang['news_special'];?></div>
    </div>
    <?php if(!empty($output['special_list']) && is_array($output['special_list'])) {?>
    <ul class="special-list">
      <?php foreach($output['special_list'] as $value) {?>
      <li>
        <h3 class="special-title"> <a href="<?php echo getNEWSSpecialUrl($value['special_id']);?>" target="_blank"> <?php echo $value['special_title'];?> </a> </h3>
        <div class="special-cover">
            <a href="<?php echo getNEWSSpecialUrl($value['special_id']);?>" target="_blank"> <img src="<?php echo getNEWSSpecialImageUrl($value['special_image']);?>" alt="" /></a>
        </div>
      </li>
      <?php } ?>
    </ul>
    <div class="pagination"> <?php echo $output['show_page'];?> </div>
    <?php } else { ?>
    <div class="no-content-b"><i class="special"></i><?php echo $lang['no_record'];?></div>
    <?php } ?>
  </div>
</div>
