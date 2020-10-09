<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php include template('layout/common_layout');?>
<?php include template('layout/cur_local');?>
<link href="<?php echo MEMBER_TEMPLATES_URL;?>/css/member.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/member.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/ToolTip.js"></script>
<style>.wth-breadcrumb-box { display: block !important;}</style>
<div class="wtm-container">
    <?php require_once($tpl_file);?>
  <div class="clear"></div>
</div>
<?php require_once template('layout/footer');?>
</body></html>