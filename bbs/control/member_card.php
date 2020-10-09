<?php
/**
 * The AJAX call member information
 *
 *
 *
 *

 

 */

class member_cardControl extends BasebbsControl{
    public function mcard_infoWt(){
        $uid    = intval($_GET['uid']);
        $member_list = Model()->table('bbs_member')->field('member_id,bbs_id,bbs_name,cm_level,cm_exp')->where(array('member_id'=>$uid,'cm_state'=>1))->select();
        if(empty($member_list)){
            echo 'false';exit;
        }
        echo json_encode($member_list);exit;
    }
}
