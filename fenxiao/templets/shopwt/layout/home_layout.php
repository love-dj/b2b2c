<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php include template('layout/common_layout');?>

<?php if(!$output['is_search']){
    include template('layout/cur_local');
}
?>
<?php require_once($tpl_file);?>
<?php require_once template('footer');?>
</body>
</html>
