<?php
/**
 * 我的购物车
 *
 *

 * @license    http://www.s h opwt.c om


 */


//require_once BASE_VENDOR_PATH.'/php-sdk/autoload.php'; //引入加载文件

defined('ShopWT') or exit('Access Denied By ShopWT');

class upload_videoControl extends mobileHomeControl {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 购物车列表
     */
    public function upWt() {
        $accessKey = C('qiniuyun_app_key');  //秘钥
        $secretKey = C('qiniuyun_secret_key');  //秘钥
        $bucket=C('bucket_name');//存储空间
        //require_once(BASE_VENDOR_PATH.'/php-sdk/autoload.php');
        //require_once(BASE_VENDOR_PATH.'/php-sdk/src/Qiniu/Auth.php');
        //require_once(BASE_VENDOR_PATH.'/php-sdk/src/Qiniu/Storage/UploadManager.php');
        dumps($_POST['form']);die;
        $qiniu  = sevenCattleCloud($accessKey,$secretKey,$bucket);


    }

}
