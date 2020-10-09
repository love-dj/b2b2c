<?php
/**
 * 买家 预约/到货通知
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class member_appointControl extends BaseMemberControl {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 买家我的订单，以总订单pay_sn来分组显示
     *
     */
    public function indexWt() {
        $model_arrtivalnotice = Model('arrival_notice');
        $appoint_list = $model_arrtivalnotice->getArrivalNoticeList(array('member_id' => $_SESSION['member_id']), '*', '', '15');
        Tpl::output('appoint_list', $appoint_list);
        Tpl::output('show_page', $model_arrtivalnotice->showpage());
        self::profile_menu('member_appoint');
        Tpl::showpage('member_appoint.index');
    }
    
    /**
     * 删除
     */
    public function del_appointWt() {
        $id = intval($_GET['id']);
        $model_arrtivalnotice = Model('arrival_notice');
        $model_arrtivalnotice->delArrivalNotice(array('member_id' => $_SESSION['member_id'], 'an_id' => $id));
        showDialog('操作成功', 'reload', 'succ');
    }
    
    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_key='') {
        $menu_array = array(
            array('menu_key'=>'member_appoint','menu_name'=>'预约/到货通知', 'menu_url'=>'index.php?w=member_appoint')
        );
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
