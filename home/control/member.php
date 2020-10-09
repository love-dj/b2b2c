<?php
/**
 * 会员中心——账户概览
 *
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class memberControl extends BaseMemberControl{

    /**
     * 我的商城
     */
    public function homeWt() {
        $model_consume = Model('consume');
        $consume_list = $model_consume->getConsumeList(array('member_id' => $_SESSION['member_id']), '*', 0, 10);
        Tpl::output('consume_list', $consume_list);
        Tpl::showpage('member_home');
    }
}
