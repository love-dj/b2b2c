<?php
/**
 * 佣金设置
 *
 *
 *
 * *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class store_fx_setControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
    }
    /**
     * 佣金设置
     *
     */
    public function indexWt() {
        $model_store_ext = Model('store_extend');
        $store_ext = $model_store_ext->getStoreExtendInfo(array('store_id'=> $_SESSION['store_id']));
        Tpl::output('fx_commis_rate', $store_ext['fx_commis_rate']);
        if (chksubmit()) {
            $fx_commis_rate = intval($_POST['fx_commis_rate']);
            if ($fx_commis_rate && $fx_commis_rate <= 30) {
                $model_store_ext->editStoreExtend(array('fx_commis_rate'=> $fx_commis_rate), array('store_id'=> $_SESSION['store_id']));
            }
            showDialog(L('wt_common_op_succ'), 'reload', 'succ');
        }
        self::profile_menu('fx_set','index');
        Tpl::showpage('fx_set.index');
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
            case 'fx_set':
                $menu_array = array(
                    array('menu_key'=>'index','menu_name'=>'默认佣金设置',  'menu_url'=>'index.php?w=store_fx_set&t=index')
                );
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
