<?php
/**
 * 分销余额管理
 *
 *
 * *

 

 */


defined('ShopWT') or exit('Access Denied By ShopWT');

class commissionControl extends MemberfenxiaoControl{

    public function indexWt(){
        $this->commission_infoWt();
    }

    /**
     * 账户余额
     */
    public function commission_infoWt(){
        $model_tard = Model('fx_trad');
        $condition = array();
        $condition['lg_member_id'] = $_SESSION['member_id'];
        $list = $model_tard->getFenxiaoTradList($condition, '*' , 20);

        //信息输出
        self::profile_menu('log','commission_info');
        Tpl::output('show_page',$model_tard->showpage(2));
        Tpl::output('list',$list);
        Tpl::showpage('commission_info');
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key=''){
        $menu_array = array(
            array('menu_key'=>'commission_info',        'menu_name'=>'账户余额',    'menu_url'=>'index.php?w=commission&t=commission_info')
        );
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}