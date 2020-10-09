<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/layout.css" rel="stylesheet" type="text/css">
<div class="wth-article-con" style="margin:0 auto 20px; width:1200px">
  <h1><?php echo $output['doc']['doc_title'];?></h1>
  <h2><?php echo date('Y-m-d H:i',$output['doc']['doc_time']);?></h2>
  <div class="default">
    <p><?php echo nl2br($output['doc']['doc_content']);?></p>
  </div>
</div>
