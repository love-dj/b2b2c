<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div id="news_index_content" class="news-content">
<?php 
$indexl_file = file_get_contents(UPLOAD_SITE_URL.DS.ATTACH_NEWS.DS.'index_html'.DS.'index.html');
echo $indexl_file;
?>
</div>
