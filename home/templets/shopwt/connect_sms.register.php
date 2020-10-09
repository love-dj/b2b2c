<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
    $("#member_name").val('<?php echo $output['member_name']; ?>');
    $("#sms_password").val('<?php echo $output['password']; ?>');
    $("#register_phone").val($("#phone").val());
    $("#register_sms_captcha").val($("#sms_captcha").val());
