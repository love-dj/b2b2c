<?php
/**
 * 优惠券
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class member_couponControl extends BaseMemberControl{
    private $coupon_state_arr;
    
    public function __construct() {
        parent::__construct();
        Language::read('member_layout');
        //判断系统是否开启优惠券功能
        if (C('coupon_allow') != 1){
            showDialog('系统未开启优惠券功能',urlShop('member', 'home'),'error');
        }
        $model_coupon = Model('coupon');
        $this->coupon_state_arr = $model_coupon->getCouponState();
    }
    /*
     * 默认显示优惠券模版列表
     */
    public function indexWt() {
        $this->rp_listWt() ;
    }

    /*
     * 获取优惠券模版详细信息
     */
    public function rp_listWt(){
        $model_coupon = Model('coupon');
        //更新优惠券过期状态
        $model_coupon->updateCouponExpire($_SESSION['member_id']);
        //查询优惠券
        $where = array();
        $where['coupon_owner_id'] = $_SESSION['member_id'];
        $rp_state_select = trim($_GET['rp_state_select']);
        if ($rp_state_select){
            $where['coupon_state'] = $this->coupon_state_arr[$rp_state_select]['sign'];
        }
        $list = $model_coupon->getCouponList($where, '*', 0, 10, 'coupon_active_date desc');
        Tpl::output('list', $list);
        Tpl::output('couponstate_arr', $model_coupon->getCouponState());
        Tpl::output('show_page',$model_coupon->showpage(2)) ;
        $this->profile_menu('rp_list');
        Tpl::showpage('member_coupon.list');
    }

    /**
     * 通过卡密绑定优惠券
     */
    public function rp_bindingWt(){
        if(chksubmit(false,true)){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $_POST["pwd_code"],"require" => "true","message" => '请输入优惠券卡密'),
            );
            $error = $obj_validate->validate();
            if ($error != '')
            {
                showDialog($error,'','error','submiting = false');
            }
            //查询优惠券
            $model_coupon = Model('coupon');
            $where = array();
            $where['coupon_pwd'] = md5($_POST["pwd_code"]);
            $coupon_info = $model_coupon->getCouponInfo($where);
            if(!$coupon_info){
                showDialog('优惠券卡密错误','','error','submiting = false');
            }
            if($coupon_info['coupon_owner_id'] > 0){
                showDialog('该优惠券卡密已被使用，不可重复领取','','error','submiting = false');
            }
            $where = array();
            $where['coupon_id'] = $coupon_info['coupon_id'];
            $update_arr = array();
            $update_arr['coupon_owner_id'] = $_SESSION['member_id'];
            $update_arr['coupon_owner_name'] = $_SESSION['member_name'];
            $update_arr['coupon_active_date'] = time();
            $result = $model_coupon->editCoupon($where, $update_arr, $_SESSION['member_id']);
            if($result){
                //更新优惠券模板
                $update_arr = array();
                $update_arr['coupon_t_giveout'] = array('exp','coupon_t_giveout+1');
                $model_coupon->editRptTemplate(array('coupon_t_id'=>$coupon_info['coupon_t_id']),$update_arr);
                showDialog('优惠券领取成功', 'index.php?w=member_coupon&t=rp_list','succ');
            } else {
                showDialog('优惠券领取失败','','error','submiting = false');
            }
        }
        $this->profile_menu('rp_binding');
        Tpl::showpage('member_coupon.binding');
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function profile_menu($menu_key='') {
        $menu_array = array(
            1=>array('menu_key'=>'rp_list','menu_name'=>'我的优惠券','menu_url'=>'index.php?w=member_coupon&t=rp_list'),
            2=>array('menu_key'=>'rp_binding','menu_name'=>'领取优惠券','menu_url'=>'index.php?w=member_coupon&t=rp_binding'),
        );
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}