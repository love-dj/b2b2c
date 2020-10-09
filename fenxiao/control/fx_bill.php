<?php
/**
 * 分销会员结算管理
 *
 *
 * *

 

 */


defined('ShopWT') or exit('Access Denied By ShopWT');

class fx_billControl extends MemberfenxiaoControl{
    function __construct()
    {
        parent::__construct();
    }
    /**
     * 分销员分销订单管理
     */
    public function indexWt(){
        $this->bill_listWt();
    }

    public function bill_listWt(){
        $model_bill = Model('fx_bill');
        $condition = array('fx_member_id' => $_SESSION['member_id']);
        if(trim($_GET['goods_name'])){
            $condition['goods_name'] = array('like', '%' . $_GET['goods_name'] . '%');
        }
        if(is_numeric($_GET['bill_state']) && intval($_GET['bill_state']) >= 0){
            $condition['log_state'] = intval($_GET['bill_state']);
        }
        $fields = '*';
        $list = $model_bill->getFenxiaoBillList($condition, $fields, 15);

        Tpl::output('bill_list',$list);
        Tpl::output('show_page',$model_bill->showpage(2));
        Tpl::showpage('fx_bill.list');
    }

}