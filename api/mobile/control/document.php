<?php
/**
 * 前台品牌分类
 *
 *


 

 */


defined('ShopWT') or exit('Access Denied By ShopWT');
class documentControl extends mobileHomeControl {
    public function __construct() {
        parent::__construct();
    }

    public function agreementWt() {
        $doc = Model('document')->getOneByCode('agreement');
        output_data($doc);
    }
}
