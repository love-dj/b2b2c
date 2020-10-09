<?php
/**
 * The AJAX call member information
 *
 *
 *
 *

 

 */

class member_cardControl extends MircroShopControl{
    public function mcard_infoWt(){
        $uid    = intval($_GET['uid']);
        if($uid <= 0) {
            echo 'false';exit;
        }
        $model_what_member_info = Model('what_member_info');
        $what_member_info = $model_what_member_info->getOneById($uid);
        if(empty($what_member_info)){
            echo 'false';exit;
        }
        echo json_encode($what_member_info);exit;
    }
}
