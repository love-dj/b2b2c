<?php
/**
 * 微信管理
 *
 *
 *
 *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');
class wxconfigModel extends Model{
    public function __construct() {
        parent::__construct('wechat_config');
    }
}