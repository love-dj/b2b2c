<?php
/**
 * 默认展示页面
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class indexControl extends BaseLoginControl{
    public function __construct() {
        @header("location: " . urlMember('member_information'));
    }
}
