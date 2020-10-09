<?php
/**
 * 手机端下载地址
 *
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class mb_appControl extends BaseHomeControl {
    public function __construct() {
        parent::__construct();
    }
    /**
     * 下载地址
     *
     */
    public function indexWt() {
        $mobilebrowser_list ='iphone|ipad';
        if(preg_match("/$mobilebrowser_list/i", $_SERVER['HTTP_USER_AGENT'])) {
            @header('Location: '.C('mobile_ios'));exit;
        } else {
            @header('Location: '.C('mobile_apk'));exit;
        }
    }
}
