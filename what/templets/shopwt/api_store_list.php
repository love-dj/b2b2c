<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<!--店铺街推荐排行-->
<?php $store_list_count = count($output['store_list']);?>

<div class="title-bar">
  <h3><?php echo $lang['wt_what_store'];?></h3>
  <a href="<?php echo WHAT_SITE_URL.DS;?>index.php?w=store" class="more" target="_blank"><?php echo $lang['wt_more'];?></a> </div>
<div class="contnet-box">
  <ol wt_type="index_store" class="what-store-list">
      <?php $i = 1;?>
    <?php if(!empty($output['store_list']) && is_array($output['store_list'])) {?>
    <?php foreach($output['store_list'] as $key=>$value) {?>
    <li class="overall" style="display:none;"><i><?php echo $i;?></i>
      <dl class="store-intro">
        <dt><?php echo $value['store_name'];?></dt>
        <dd><?php echo $lang['what_text_goods'];?><?php echo $lang['wt_colon'];?><em><?php echo $value['goods_count'];?></em><?php echo $lang['piece'];?></dd>
        <dd><a href="<?php echo WHAT_SITE_URL.DS;?>index.php?w=store&t=detail&store_id=<?php echo $value['what_store_id'];?>" target="_blank"><?php echo $lang['what_api_store_info'];?></a></dd>
      </dl>
    </li>
    <li class="simple"><i><?php echo $i++;?></i><a href=""><?php echo $value['store_name'];?></a></li>
    <?php } ?>
    <?php } ?>
  </ol>
</div>
