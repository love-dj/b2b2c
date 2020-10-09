<?php
/**
 * 商家订单
 *


 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class seller_orderControl extends mobileSellerControl {

    public function __construct(){
        parent::__construct();
    }

    public function order_listWt() {
        $model_order = Model('order');

        $order_list = $model_order->getStoreOrderList(
            $this->store_info['store_id'],
            $_POST['order_sn'],
            $_POST['buyer_name'],
            $_POST['state_type'],
            $_POST['query_start_date'],
            $_POST['query_end_date'],
            $_POST['skip_off'],
            '*',
            array('order_goods')
        );

        sort($order_list);

        $page_count = $model_order->gettotalpage();

        output_data(array('order_list' => $order_list), mobile_page($page_count));
    }

    public function order_vr_listWt() {
        $ownShopIds = Model('store')->getOwnShopIds();

        $model_order_vr = Model('order_vr');

        $condition = array();
        $condition['store_id'] = $this->store_info['store_id'];
        if (preg_match('/^\d{10,20}$/',$_POST['order_key'])) {
            $condition['order_sn'] = $_POST['order_key'];
        } elseif ($_POST['order_key'] != '') {
            $condition['goods_name'] = array('like','%'.$_POST['order_key'].'%');
        }
        if ($_POST['state_type'] != '') {
            $condition['order_state'] = str_replace(
                    $allow_state_array = array('state_new','state_pay','state_success','state_cancel'),
                    array(ORDER_STATE_NEW,ORDER_STATE_PAY,ORDER_STATE_SUCCESS,ORDER_STATE_CANCEL), $_POST['state_type']);
        }
		
		$if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_POST['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_POST['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_POST['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_POST['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }

        if ($_POST['skip_off'] == 1) {
            $condition['order_state'] = array('neq',ORDER_STATE_CANCEL);
        }
		

        $order_list = $model_order_vr->getOrderList($condition, $this->page, '*', 'order_id desc');

        foreach ($order_list as $key => $order) {
            //显示取消订单
            $order_list[$key]['if_cancel'] = $model_order_vr->getOrderOperateState('buyer_cancel',$order);

            //显示支付
            $order_list[$key]['if_pay'] = $model_order_vr->getOrderOperateState('payment',$order);

            //显示评价
            $order_list[$key]['if_evaluation'] = $model_order_vr->getOrderOperateState('evaluation',$order);

            $order_list[$key]['goods_image_url'] = cthumb($order['goods_image'], 240, $order['store_id']);

            $order_list[$key]['ownshop'] = in_array($order['store_id'], $ownShopIds);
        }

        $page_count = $model_order_vr->gettotalpage();

        output_data(array('order_list' => $order_list), mobile_page($page_count));
    }

    public function order_infoWt()
    {
        $order_id = intval($_POST['order_id']);
        if (!$order_id) {
            output_error('订单编号有误');
        }
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $condition;
        $condition['store_id'] = $this->store_info['store_id'];

        $order_info = $model_order->getOrderInfo($condition, array('order_common','order_goods','member'));
        if (empty($order_info)) {
            output_error('订单信息不存在');
        }

        //取得订单其它扩展信息
        $model_order->getOrderExtendInfo($order_info);

        $model_refund_return = Model('refund_return');
        $order_list = array();
        $order_list[$order_id] = $order_info;
        //订单商品的退款退货显示
        $order_list = $model_refund_return->getGoodsRefundList($order_list, 1);
        $order_info = $order_list[$order_id];
        $refund_all = $order_info['refund_list'][0];

        //订单全部退款商家审核状态:1为待审核,2为同意,3为不同意
        if (!empty($refund_all) && $refund_all['seller_state'] < 3) {
            $order_info['extend_refund']['refund_all'] = $refund_all;
        }

        //显示锁定中
        $order_info['if_lock'] = $model_order->getOrderOperateState('lock',$order_info);

        //显示调整费用
        $order_info['if_modify_price'] = $model_order->getOrderOperateState('modify_price',$order_info);

        //显示取消订单
        $order_info['if_store_cancel'] = $model_order->getOrderOperateState('store_cancel',$order_info);

        //显示发货
        $order_info['if_store_send'] = $model_order->getOrderOperateState('store_send',$order_info);

        //显示物流跟踪
        $order_info['if_deliver'] = $model_order->getOrderOperateState('deliver',$order_info);

        //显示系统自动取消订单日期
        if ($order_info['order_state'] == ORDER_STATE_NEW) {
            $order_info['order_cancel_day'] = $order_info['add_time'] + ORDER_AUTO_CANCEL_TIME * 3600;
        }

        //显示快递信息
        if ($order_info['shipping_code'] != '') {
            $express = rkcache('express',true);
            $order_info['express_info']['e_code'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_code'];
            $order_info['express_info']['e_name'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_name'];
            $order_info['express_info']['e_url'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_url'];
        }

        //显示系统自动收获时间
        if ($order_info['order_state'] == ORDER_STATE_SEND) {
            $order_info['order_confirm_day'] = $order_info['delay_time'] + ORDER_AUTO_RECEIVE_DAY * 24 * 3600;
        }

        //取得订单操作日志
        $order_log_list = $model_order->getOrderLogList(array('order_id'=>$order_info['order_id']),'log_id asc');
        $order_info['extend_log']['order_log_list'] = $order_log_list;

        //如果订单已取消，取得取消原因、时间，操作人
        if ($order_info['order_state'] == ORDER_STATE_CANCEL) {
            $last_log = end($order_log_list);
            if ($last_log['log_orderstate'] == ORDER_STATE_CANCEL) {
                $order_info['close_info'] = $last_log;
            }
        }
        //查询消费者保障服务
        if (C('contract_allow') == 1) {
            $contract_item = Model('contract')->getContractItemByCache();
        }
        foreach ($order_info['extend_order_goods'] as $value) {
            $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
            $value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
            $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
            $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
            //处理消费者保障服务
            if (trim($value['goods_contractid']) && $contract_item) {
                $goods_contractid_arr = explode(',',$value['goods_contractid']);
                foreach ((array)$goods_contractid_arr as $gcti_v) {
                    $value['contractlist'][] = $contract_item[$gcti_v];
                }
            }
            $order_info['zengpin_list'] = array();
            if ($value['goods_type'] == 5) {
                $order_info['zengpin_list'][] = $value;
            } else {
                $order_info['goods_list'][] = $value;
            }
        }

        if (empty($order_info['zengpin_list'])) {
            $order_info['goods_count'] = count($order_info['goods_list']);
        } else {
            $order_info['goods_count'] = count($order_info['goods_list']) + 1;
        }

        //分销返佣
        foreach ($order_info['goods_list'] as $key => $value) {
            $model_mingxi = Model('mingxi');
            $order_info['goods_list'][$key]['firstCommision'] = $model_mingxi->getCommisionInfo($order_info['order_sn'], 1, $value['goods_id']);
            $order_info['goods_list'][$key]['secondCommision'] = $model_mingxi->getCommisionInfo($order_info['order_sn'], 2, $value['goods_id']);
            $order_info['goods_list'][$key]['thirdCommision'] = $model_mingxi->getCommisionInfo($order_info['order_sn'], 3, $value['goods_id']);
        }

        //优惠信息
        $order_info['sale'] = array();

        //发货信息
        if (!empty($order_info['extend_order_common']['daddress_id'])) {
            $daddress_info = Model('daddress')->getAddressInfo(array('address_id'=>$order_info['extend_order_common']['daddress_id']));
            $order_info['daddress_info'] = $daddress_info;
        }

        output_data(array('order_info' => $order_info));
    }

    /**
     * 取消订单
     */
    public function order_cancelWt() {
        $order_id = intval($_POST['order_id']);
        $reason = $_POST['reason'];
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $this->store_info['store_id'];
        $order_info = $model_order->getOrderInfo($condition);

        $if_allow = $model_order->getOrderOperateState('store_cancel',$order_info);
        if (!$if_allow) {
            output_error('无权操作');
        }

        if (TIMESTAMP - 86400 < $order_info['api_pay_time']) {
            $_hour = ceil(($order_info['api_pay_time']+86400-TIMESTAMP)/3600);
            output_error('该订单曾尝试使用第三方支付平台支付，须在'.$_hour.'小时以后才可取消');
        }

        if ($order_info['order_type'] == 2) {
            //预定订单
            $result = Handle('order_book')->changeOrderStateCancel($order_info,'seller',$this->seller_info['seller_name'], $reason);
        } else {
            $cancel_condition = array();
            if ($order_info['payment_code'] != 'offline') {
                $cancel_condition['order_state'] = ORDER_STATE_NEW;
            }
            $result = Handle('order')->changeOrderStateCancel($order_info,'seller',$this->seller_info['seller_name'], $reason, true, $cancel_condition);
        }

        if (!$result['state']) {
            output_error($result['msg']);
        }
        output_data('1');
    }
	
	/**
     * 虚拟 取消订单
     */
	public function order_vr_cancelWt() {
			$order_id = intval($_POST['order_id']);
			$reason = $_POST['reason'];
			$model_order_vr = Model('order_vr');
            $handle_order_vr = Handle('order_vr');
		    $condition = array();
			$condition['order_id'] = $order_id;
			$condition['store_id'] = $this->store_info['store_id'];
			$order_info = $model_order_vr->getOrderInfo($condition);
		
            $if_allow = $model_order_vr->getOrderOperateState('store_cancel',$order_info);
            if (!$if_allow) {
                return callback(false,'无权操作');
            }
            if (TIMESTAMP - 86400 < $order_info['api_pay_time']) {
                $_hour = ceil(($order_info['api_pay_time']+86400-TIMESTAMP)/3600);
                return callback(false,'该订单曾尝试使用第三方支付平台支付，须在'.$_hour.'小时以后才可取消');
            }
			$result = Handle('order_vr')->changeOrderStateCancel($order_info,'seller',$this->seller_info['seller_name'], $reason, true, $cancel_condition);
			if (!$result['state']) {
				output_error($result['msg']);
			}
			output_data('1');
    }
    
    /**
     * 修改运费
     */
    public function order_ship_priceWt() {
        $order_id = intval($_POST['order_id']);
        $shipping_fee = wtPriceFormat($_POST['shipping_fee']);
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $this->store_info['store_id'];
        $order_info = $model_order->getOrderInfo($condition);

        $if_allow = $model_order->getOrderOperateState('modify_price',$order_info);
        if (!$if_allow) {
            output_error('无权操作');
        }
        $result = Handle('order')->changeOrderShipPrice($order_info, 'seller', $this->seller_info['seller_name'], $shipping_fee);

        if (!$result['state']) {
            output_error($result['msg']);
        }
        output_data('1');
    }
	
	  /**
	   * 修改商品价格
	   * */
    public function order_spay_priceWt(){
        $model_order = Model('order');
        $handle_order = Handle('order');
        $order_id= intval($_POST['order_id']);
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $this->store_info['store_id'];
        $goods_amount = $_POST['order_fee'];
        $order_info = $model_order->getOrderInfo($condition);
        $if_allow = $model_order->getOrderOperateState('spay_price',$order_info);
        if (!$if_allow) {
            output_error('无权操作');
        }

        $info = $handle_order->changeOrderSpayPrice($order_info,'seller',$this->seller_info['seller_name'],$goods_amount);
        if($info['state'] == 1){
            output_data('修改成功!');

        }else{
            output_error('操作失败!');

        }
    }
	
	
    
    /**
     * 发货
     */
    public function order_deliver_sendWt() {
        $order_id = intval($_POST['order_id']);
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $this->store_info['store_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
        $if_allow_send = intval($order_info['lock_state']) || !in_array($order_info['order_state'],array(ORDER_STATE_PAY,ORDER_STATE_SEND));
        if ($if_allow_send) {
            output_error('无权操作');
        }

        $result = Handle('order')->changeOrderSend($order_info, 'seller', $this->seller_info['seller_name'], $_POST);
        if (!$result['state']) {
            output_error($result['msg']);
        }
        output_data('1');
    }
}
