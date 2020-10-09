<?php defined('ShopWT') or exit('Access Denied By ShopWT');
if (!empty($output['goods_content']['mobile_body'])) {
    echo $output['goods_content']['mobile_body'];
} else {
    echo $output['goods_content']['goods_body'];
}?>