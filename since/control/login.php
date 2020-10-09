<?php
/**
 * 物流自提服务站首页
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class loginControl extends BaseAccountCenterControl{
    public function __construct(){
        parent::__construct();
    }
    /**
     * 登录
     */
    public function indexWt() {
        if ($_SESSION['delivery_login'] == 1) {
            @header('location: index.php?w=d_center');die;
        }
        if (chksubmit()) {
            $where = array();
            $where['dlyp_name'] = $_POST['dname'];
            $where['dlyp_passwd'] = md5($_POST['dpasswd']);
            $dp_info = Model('delivery_point')->getDeliveryPointInfo($where);
            if (!empty($dp_info)) {
                $_SESSION['delivery_login'] = 1;
                $_SESSION['dlyp_id'] = $dp_info['dlyp_id'];
                $_SESSION['dlyp_name'] = $dp_info['dlyp_name'];
                showDialog('登录成功', 'index.php?w=d_center', 'succ');
            } else {
                showDialog('登录失败');
            }
        }
        Tpl::showpage('login');
    }
    /**
     * 登出
     */
    public function logoutWt() {
        unset($_SESSION['delivery_login']);
        unset($_SESSION['dlyp_id']);
        unset($_SESSION['dlyp_name']);
        showDialog('退出成功', 'reload', 'succ');
    }
}
