<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php $block_content = empty($block_content) ? $output['block_content'] : $block_content; ?>
<?php echo html_entity_decode($block_content);?>
