<?php
/**
 * 分销订单
 *
 *
 * *

 

 */


defined('ShopWT') or exit('Access Denied By ShopWT');

class fx_orderControl extends MemberfenxiaoControl{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 分销员分销订单管理
     */
    public function indexWt(){
        $this->order_listWt();
    }

    public function order_listWt(){
        $model_order = Model('fx_order');
        $condition = array('fx_member_id' => $_SESSION['member_id']);
        if(trim($_GET['goods_name'])){
            $condition['order_goods.goods_name'] = array('like', '%' . $_GET['goods_name'] . '%');
        }
        switch(intval($_GET['order_state'])){
            case 0:
                if(isset($_GET['order_state'])){
                    $condition['orders.order_state'] = 0;
                }
                break;
            case 10:
                $condition['orders.order_state'] = 10;
                $condition['orders.chain_code'] = 0;
                break;
            case 11:
                $condition['orders.order_state'] = 10;
                $condition['orders.chain_code'] = array('neq',0);
                break;
            case 20:
                $condition['orders.order_state'] = 20;
                $condition['orders.chain_code'] = 0;
                break;
            case 21:
                $condition['orders.order_state'] = 20;
                $condition['orders.chain_code'] = array('neq',0);
                break;
            case 30:
                $condition['orders.order_state'] = 30;break;
            case 40:
                $condition['orders.order_state'] = 40;break;
        }
        $condition['order_goods.is_fx'] = 1;
        $fields = '*';
        $list = $model_order->getMeberFenxiaoOrderList($condition, $fields, 8);

        Tpl::output('order_list',$list);
        Tpl::output('show_page',$model_order->showpage(2));
        Tpl::showpage('fx_order.list');
    }

}