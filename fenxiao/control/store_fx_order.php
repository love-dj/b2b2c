<?php
/**
 * 分销订单
 *
 *
 *
 * *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class store_fx_orderControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
    }
    /**
     * 分销订单列表
     *
     */
    public function indexWt() {
        $model_fx_order = Model('fx_order');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $order_list = $model_fx_order->getDisOrderList($condition, 10);

        Tpl::output('order_list', $order_list);
        Tpl::output('show_page', $model_fx_order->showpage());
        self::profile_menu('fx_order','index');
        Tpl::showpage('fx_order.index');
    }
    /**
     * 小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key='') {
        $menu_array = array();
        switch ($menu_type) {
            case 'fx_order':
                $menu_array = array(
                    array('menu_key'=>'index','menu_name'=>'分销订单 ',  'menu_url'=>'index.php?w=store_fx_order&t=index')
                );
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }

}
