<?php
/**
 * 我的代金券
 *
 *


 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class member_pointsControl extends mobileMemberControl {

    public function __construct() {
        parent::__construct();
    }
    /**
     * 积分日志列表
     */
    public function pointslogWt(){
        $where = array();
        $where['pl_memberid'] = $this->member_info['member_id'];
        //查询积分日志列表
        $points_model = Model('points');
        $log_list = $points_model->getPointsLogList($where, '*', 0, $this->page);
        $page_count = $points_model->gettotalpage();
        output_data(array('log_list' => $log_list), mobile_page($page_count));
    }
}
