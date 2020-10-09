<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php if(!empty($output['brand_list']) && is_array($output['brand_list'])){ ?>

<div class="brand-select-box">
  <div class="arrow"></div>
  <ul id="brand_search_list" class="brand-search-list">
    <?php foreach($output['brand_list'] as $value){ ?>
    <li>
      <div class="news-thumb"><a wttype="brand_item" href="<?php echo BASE_SITE_URL;?>/index.php?w=brand&t=list&brand=<?php echo $value['brand_id'];?>" data-brand-id="<?php echo $value['brand_id'];?>" title="<?php echo $value['brand_name'];?>" target="_blank"><img style="width:78px;" src="<?php echo brandImage($value['brand_pic']);?>" alt="<?php echo $value['brand_name'];?>" /></a></div>
      <div class="brand-name"><a href="<?php echo BASE_SITE_URL;?>/index.php?w=brand&t=list&brand=<?php echo $value['brand_id'];?>" title="<?php echo $value['brand_name'];?>" target="_blank"><?php echo $value['brand_name'];?></a></div>
      <div wttype="btn_brand_select" class="add-brand"><i></i></div>
    </li>
    <?php } ?>
    <div class="pagination"><?php echo $output['show_page'];?></div>
  </ul>
</div>
<?php }else { ?>
<div class="no-record"><?php echo $lang['no_record'];?></div>
<?php } ?>
