<?php
/**
 * 分销订单 V6.4
 *
 *
 *
 * *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');

class fx_orderModel extends Model{

    public function __construct() {
        parent::__construct();
    }

    /**
     * 查询分销订单
     *
     * @param
     * @return array
     */
    public function getDisOrderList($condition = array(), $page = '', $limit = '', $order = 'order_id desc', $extend = array('order_common')) {
        $condition['is_fx'] = 1;
        $result = $this->table('orders')->where($condition)->page($page)->limit($limit)->order($order)->select();
        $order_list = array();
        if (!empty($result) && is_array($result)) {
            $order_ids = array();//订单编号数组
            $fx_member_ids = array();//分销会员编号数组
            foreach ($result as $order){
                $order_id = $order['order_id'];
                $order_ids[] = $order_id;
                $order['state_desc'] = orderState($order);
                $order['payment_name'] = orderPaymentName($order['payment_code']);
                $order['add_time_text'] = date('Y-m-d H:i:s',$order['add_time']);
                $order['goods_count'] = 0;
                $order['fx_order_amount'] = 0;//分销商品金额
                $order['fx_commis_amount'] = 0;//分销商品佣金
                $order_list[$order_id] = $order;
            }
            $order_goods_list = $this->table('order_goods')->where(array('is_fx'=>1,'order_id'=> array('in',$order_ids)))->select();
            foreach ($order_goods_list as $value) {
                $order_id = $value['order_id'];
                $fx_member_ids[] = $value['fx_member_id'];
                $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
                $value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
                $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
                $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
                $value['fx_commis_amount'] = wtPriceFormat($value['goods_pay_price']*$value['fx_commis_rate']/100);
                $order_list[$order_id]['extend_order_goods'][] = $value;
                $order_list[$order_id]['goods_count'] += 1;
                $fx_order_amount = $order_list[$order_id]['fx_order_amount']+$value['goods_pay_price'];
                $order_list[$order_id]['fx_order_amount'] = wtPriceFormat($fx_order_amount);
                $fx_commis_amount = $order_list[$order_id]['fx_commis_amount']+$value['fx_commis_amount'];
                $order_list[$order_id]['fx_commis_amount'] = wtPriceFormat($fx_commis_amount);
            }
            $member_list = $this->table('member')->where(array('member_id'=> array('in', $fx_member_ids)))->field('member_name,member_id')->key('member_id')->select();
            $fx_pay_list = $this->table('fx_pay')->field('order_id,max(fx_pay_time) as fx_pay_time')->group('order_id')->where(array('log_state'=>1,'order_id'=> array('in', $order_ids)))->key('order_id')->select();
            foreach ($order_list as $k => $v) {
                foreach ($v['extend_order_goods'] as $k_goods => $v_goods) {
                    $member_id = $v_goods['fx_member_id'];
                    $order_list[$k]['extend_order_goods'][$k_goods]['fx_member_name'] = $member_list[$member_id]['member_name'];
                }
                $order_id = $v['order_id'];
                $order_list[$order_id]['fx_pay_state'] = 0;
                $order_list[$order_id]['fx_pay_state_text'] = '未结算';
                $order_list[$order_id]['fx_pay_time'] = '';
                if (!empty($fx_pay_list[$order_id])) {
                    $order_list[$order_id]['fx_pay_state'] = 1;
                    $order_list[$order_id]['fx_pay_state_text'] = '已结算';
                    $order_list[$order_id]['fx_pay_time'] = date('Y-m-d H:i:s',$fx_pay_list[$order_id]['fx_pay_time']);
                }
            }
            if (in_array('order_common',$extend)) {
                $order_common_list = $this->table('order_common')->where(array('order_id'=> array('in',$order_ids)))->select();
                foreach ($order_common_list as $order_common) {
                    $order_id = $order_common['order_id'];
                    $order_list[$order_id]['extend_order_common'] = $order_common;
                    $order_list[$order_id]['extend_order_common']['reciver_info'] = @unserialize($order_common['reciver_info']);
                    $order_list[$order_id]['extend_order_common']['invoice_info'] = @unserialize($order_common['invoice_info']);
                }
            }
        }
        return $order_list;
    }

    /**
     * 分销员订单列表
     * @return array
     */
    public function getMeberFenxiaoOrderList($condition = array(), $field = '*', $page = 0, $order = 'order_goods.rec_id desc', $limit = 0){
        return $this->table('order_goods,orders')->join('Left')->on('order_goods.order_id = orders.order_id')->field($field)->where($condition)->order($order)->limit($limit)->page($page)->select();
    }

    /**
     * 分销员订单列表+结算
     * @return array
     */
    public function getMeberFenxiaoOrderWithPayList($condition = array(), $field = '*', $page = 0, $order = 'order_goods.rec_id desc', $limit = 0,$group = ''){
        return $this->table('order_goods,orders,fx_pay')->join('Left')->on('order_goods.order_id = orders.order_id,order_goods.rec_id = fx_pay.order_goods_id')->field($field)->where($condition)->group($group)->order($order)->limit($limit)->page($page)->select();
    }

    /**
     * 分销佣金结算单条记录
     *
     * @param array 
     * @return array
     */
    public function getDisPayInfo($condition = array(), $order = 'log_id desc') {
        return $this->table('fx_pay')->where($condition)->order($order)->find();
    }

    /**
     * 分销佣金结算记录
     *
     * @param
     * @return array
     */
    public function getDisPayList($condition = array(), $page = '', $fields = '*', $limit = '', $order = 'log_id desc') {
        $result = $this->table('fx_pay')->field($fields)->where($condition)->page($page)->limit($limit)->order($order)->select();
        return $result;
    }
}
