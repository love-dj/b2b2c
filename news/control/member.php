<?php
/**
 * APP会员
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class memberControl{

    public function __construct(){
        require_once(BASE_PATH.'/library/function/client.php');
    }

    public function infoWt(){
        if (!empty($_GET['uid'])){
            $member_info = wt_member_info($_GET['uid'],'uid');
        }elseif(!empty($_GET['user_name'])){
            $member_info = wt_member_info($_GET['user_name'],'user_name');
        }
        return $member_info;
    }
}
