<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<dl>
  <dt> <i title="<?php echo $lang['news_del'];?>"></i> <a href="<?php echo BASE_SITE_URL.DS;?>index.php?w=search&cate_id=<?php echo $output['gc_parent']['gc_id'];?>" target="_blank"><?php echo $output['gc_parent']['gc_name'];?></a> </dt>
  <div class="clear"></div>
  <?php if(!empty($output['goods_class']) && is_array($output['goods_class'])){ ?>
  <?php foreach($output['goods_class'] as $k => $v){ ?>
  <dd> <i title="<?php echo $lang['news_del'];?>"></i> <a href="<?php echo BASE_SITE_URL.DS;?>index.php?w=search&cate_id=<?php echo $v['gc_id'];?>" target="_blank"><?php echo $v['gc_name'];?></a> </dd>
  <?php } ?>
  <?php } ?>
</dl>
