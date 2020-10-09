<?php
/**
 * 虚拟商品购买行为
 *

 
 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');
class buy_vrHandle {

    /**
     * 虚拟商品购买第一步，得到购买数据(商品、店铺、会员)
     * @param int $goods_id 商品ID
     * @param int $quantity 购买数量
     * @param int $member_id 会员ID
     * @return array
     */
    public function getBuyStep1Data($goods_id, $quantity, $member_id) {
        return $this->getBuyStepData($goods_id, $quantity, $member_id);
    }

    /**
     * 虚拟商品购买第二步，得到购买数据(商品、店铺、会员)
     * @param int $goods_id 商品ID
     * @param int $quantity 购买数量
     * @param int $member_id 会员ID
     * @return array
     */
    public function getBuyStep2Data($goods_id, $quantity, $member_id) {
        return $this->getBuyStepData($goods_id, $quantity, $member_id);
    }

    /**
     * 得到虚拟商品购买数据(商品、店铺、会员)
     * @param int $goods_id 商品ID
     * @param int $quantity 购买数量
     * @param int $member_id 会员ID
     * @return array
     */
    public function getBuyStepData($goods_id, $quantity, $member_id) {
        $model_goods = Model('goods');
        $goods_id = intval($goods_id);
        $goods_content = $model_goods->getVirtualGoodsOnlineInfoByID($goods_id);
        if (empty($goods_content)) {
            return callback(false, '该商品不符合购买条件，可能的原因有：下架、不存在、过期等');
        }
        if ($goods_content['store_id'] == $_SESSION['store_id']) {
            return callback(false,'不允许自己店铺的商品');
        }

        if ($goods_content['virtual_limit'] > $goods_content['goods_storage']) {
            $goods_content['virtual_limit'] = $goods_content['goods_storage'];
        }
        //处理商品消费者保障服务信息
        $goods_list = $model_goods->getGoodsContract(array(0=>$goods_content));
        $goods_content = $goods_list[0];

        //取得抢购信息
        $goods_content = $this->_getRobbuyInfo($goods_content);

        $quantity = abs(intval($quantity));
        $quantity = $quantity == 0 ? 1 : $quantity;
        $quantity = $quantity > $goods_content['virtual_limit'] ? $goods_content['virtual_limit'] : $quantity;
        if ($quantity > $goods_content['goods_storage']) {
            return callback(false, '该商品库存不足');
        }
        $goods_content['quantity'] = $quantity;
        $goods_content['goods_total'] = wtPriceFormat($goods_content['goods_price'] * $goods_content['quantity']);
        $goods_content['goods_image_url'] = cthumb($goods_content['goods_image'], 240, $goods_content['store_id']);

        //规格
        $_tmp_name = unserialize($goods_content['spec_name']);
        $_tmp_value = unserialize($goods_content['goods_spec']);
        if (is_array($_tmp_name) && is_array($_tmp_value)) {
            $_tmp_name = array_values($_tmp_name);$_tmp_value = array_values($_tmp_value);
            foreach ($_tmp_name as $sk => $sv) {
                $new_array['goods_spec'] .= $sv.'：'.$_tmp_value[$sk].'，';
            }
            $goods_content['goods_spec'] = rtrim($new_array['goods_spec'],'，');
        } else {
            $goods_content['goods_spec'] = null;
        }

        $return = array();
        $return['goods_content'] = $goods_content;
        $return['store_info'] = Model('store')->getStoreOnlineInfoByID($goods_content['store_id'],'store_name,store_id,member_id');
        $return['member_info'] = Model('member')->getMemberInfoByID($member_id);

        return callback(true,'',$return);
    }

    /**
     * 虚拟商品购买第三步
     * @param array $post 接收POST数据，必须传入goods_id:商品ID，quantity:购买数量,buyer_phone:接收手机,buyer_msg:买家留言
     * @param int $member_id
     * @return array
     */
    public function buyStep3($post, $member_id) {

        $result = $this->getBuyStepData($post['goods_id'], $post['quantity'], $member_id);
        if (!$result['state']) return $result;
        
        $goods_content = $result['data']['goods_content'];
        $member_info = $result['data']['member_info'];

        //应付总金额计算
        $pay_total = $goods_content['goods_price'] * $goods_content['quantity'];
        $store_id = $goods_content['store_id'];
        $store_goods_total_list = array($store_id => $pay_total);
        $pay_total = $store_goods_total_list[$store_id];

        //整理数据
        $input = array();
        $input['quantity'] = $goods_content['quantity'];
        $input['buyer_phone'] = $post['buyer_phone'];
        $input['buyer_msg'] = $post['buyer_msg'];
        $input['pay_total'] = $pay_total;
        $input['order_from'] = $post['order_from'];
        try {

            $model_goods = Model('goods');
            //开始事务
            $model_goods->beginTransaction();

            //生成订单
            $order_info = $this->_createOrder($input,$goods_content,$member_info);

            if (!empty($post['password'])) {
                if ($member_info['member_paypwd'] != '' && $member_info['member_paypwd'] == md5($post['password'])) {
                    //充值卡支付
                    if (!empty($post['rcb_pay'])) {
                        $order_info = $this->rcbPay($order_info, $post, $member_info);
                    }
                    //预存款支付
                    if (!empty($post['pd_pay'])) {
                        $this->pdPay($order_info, $post, $member_info);
                    }
                }
            }

            //提交事务
            $model_goods->commit();

        }catch (Exception $e){

            //回滚事务
            $model_goods->rollback();
            return callback(false, $e->getMessage());
        }

        //变更库存和销量
        QueueClient::push('createOrderUpdateStorage', array($goods_content['goods_id'] => $goods_content['quantity']));

        //更新抢购信息
        $this->_updaterobbuy($goods_content);

        //发送兑换码到手机
        $param = array('order_id'=>$order_info['order_id'],'buyer_id'=>$member_id,'buyer_phone'=>$order_info['buyer_phone'],'goods_name'=>$order_info['goods_name']);
        QueueClient::push('sendVrCode', $param);

        //生成交易快照
        QueueClient::push('createVrSphot', array('order_id'=>$order_info['order_id'],'goods_id'=>$goods_content['goods_id']));

        return callback(true,'',array('order_id' => $order_info['order_id'],'order_sn' => $order_info['order_sn']));
    }

    /**
     * 生成订单
     * @param array $input 表单数据
     * @param unknown $goods_content 商品数据
     * @param unknown $member_info 会员数据
     * @throws Exception
     * @return array
     */
    private function _createOrder($input, $goods_content, $member_info) {
        extract($input);
        $model_order_vr = Model('order_vr');

        //存储生成的订单,函数会返回该数组
        $order_list = array();

        $order = array();
        $order_code = array();

        $order['order_sn'] = $this->_makeOrderSn($member_info['member_id']);
        $order['store_id'] = $goods_content['store_id'];
        $order['store_name'] = $goods_content['store_name'];
        $order['buyer_id'] = $member_info['member_id'];
        $order['buyer_name'] = $member_info['member_name'];
        $order['buyer_phone'] = $input['buyer_phone'];
        $order['buyer_msg'] = $input['buyer_msg'];
        $order['add_time'] = TIMESTAMP;
        $order['order_state'] = ORDER_STATE_NEW;
        $order['order_amount'] = $pay_total;
        $order['goods_id'] = $goods_content['goods_id'];
        $order['goods_name'] = $goods_content['goods_name'];
        $order['goods_price'] = $goods_content['goods_price'];
        $order['goods_num'] = $input['quantity'];
        $order['goods_image'] = $goods_content['goods_image'];
        $order['commis_rate'] = 200;
        $order['gc_id'] = $goods_content['gc_id'];
        $order['vr_indate'] = $goods_content['virtual_indate'];
        $order['vr_invalid_refund'] = $goods_content['virtual_invalid_refund'];
        $order['order_from'] = $input['order_from'];
        if ($goods_content['ifrobbuy'] == 1) {
            $order['order_sale_type'] = 1;
            $order['sales_id'] = $goods_content['robbuy_id'];
        }
        //记录消费者保障服务
        $contract_itemid_arr = $goods_content['contractlist']?array_keys($goods_content['contractlist']):array();
        $order['goods_contractid'] = $contract_itemid_arr?implode(',',$contract_itemid_arr):'';

        $order['goods_spec'] = $goods_content['goods_spec'];

        $order_id = $model_order_vr->addOrder($order);

        if (!$order_id) {
            throw new Exception('订单保存失败');
        }
        $order['order_id'] = $order_id;

        // 提醒[库存报警]
        if ($goods_content['goods_storage_alarm'] >= ($goods_content['goods_storage'] - $input['quantity'])) {
            $param = array();
            $param['common_id'] = $goods_content['goods_commonid'];
            $param['sku_id'] = $goods_content['goods_id'];
            QueueClient::push('sendStoreMsg', array('code' => 'goods_storage_alarm', 'store_id' => $goods_content['store_id'], 'param' => $param));
        }

        return $order;
    }

    /**
     * 生成支付单编号(两位随机 + 从2000-01-01 00:00:00 到现在的秒数+微秒+会员ID%1000)，该值会传给第三方支付接口
     * 长度 =2位 + 10位 + 3位 + 3位  = 18位
     * 1000个会员同一微秒提订单，重复机率为1/100
     * @return string
     */
    private function _makeOrderSn($member_id) {
        return mt_rand(10,99)
        . sprintf('%010d',time() - 946656000)
        . sprintf('%03d', (float) microtime() * 1000)
        . sprintf('%03d', (int) $member_id % 1000);
    }

    /**
     * 更新抢购购买人数和数量
     */
    private function _updaterobbuy($goods_content) {
        if ($goods_content['ifrobbuy'] && $goods_content['robbuy_id']) {
            $robbuy_info = array();
            $robbuy_info['robbuy_id'] = $goods_content['robbuy_id'];
            $robbuy_info['quantity'] = $goods_content['quantity'];
            QueueClient::push('editRobbuySaleCount', $robbuy_info);
        }
    }

    /**
     * 充值卡支付
     * 如果充值卡足够就单独支付了该订单，如果不足就暂时冻结，等API支付成功了再彻底扣除
     */
    public function rcbPay($order_info, $input, $buyer_info) {
        if ($order_info['order_state'] == ORDER_STATE_PAY) return $order_info;
        $available_rcb_amount = floatval($buyer_info['available_rc_balance']);

        if ($available_rcb_amount <= 0) return $order_info;
        $model_order_vr = Model('order_vr');
        $model_pd = Model('predeposit');

        $order_amount = floatval($order_info['order_amount']);
        $data_pd = array();
        $data_pd['member_id'] = $buyer_info['member_id'];
        $data_pd['member_name'] = $buyer_info['member_name'];
        $data_pd['amount'] = $order_amount;
        $data_pd['order_sn'] = $order_info['order_sn'];

        if ($available_rcb_amount >= $order_amount) {
    
            // 预存款立即支付，订单支付完成
            $model_pd->changeRcb('order_pay',$data_pd);
            $available_rcb_amount -= $order_amount;
    
            // 订单状态 置为已支付
            $data_order = array();
            $order_info['order_state'] = $data_order['order_state'] = ORDER_STATE_PAY;
            $order_info['payment_time'] = $data_order['payment_time'] = TIMESTAMP;
            $order_info['payment_code'] = $data_order['payment_code'] = 'predeposit';
            $order_info['rcb_amount'] = $data_order['rcb_amount'] = $order_amount;
            $result = $model_order_vr->editOrder($data_order,array('order_id'=>$order_info['order_id']));
            if (!$result) {
                throw new Exception('订单更新失败');
            }

            //发放兑换码
            $insert = $model_order_vr->addOrderCode($order_info);
            if (!$insert) {
                throw new Exception('兑换码发送失败');
            }

        } else {

            //暂冻结预存款,后面还需要 API彻底完成支付
            $data_pd['amount'] = $available_rcb_amount;
            $model_pd->changeRcb('order_freeze',$data_pd);
            //预存款支付金额保存到订单
            $data_order = array();
            $order_info['rcb_amount'] = $data_order['rcb_amount'] = $available_rcb_amount;
            $result = $model_order_vr->editOrder($data_order,array('order_id'=>$order_info['order_id']));
            if (!$result) {
                throw new Exception('订单更新失败');
            }
        }
        return $order_info;
    }

    /**
     * 预存款支付
     * 如果预存款足够就单独支付了该订单，如果不足就暂时冻结，等API支付成功了再彻底扣除
     */
    public function pdPay($order_info, $input, $buyer_info) {
        if ($order_info['order_state'] == ORDER_STATE_PAY) return $order_info;

        $available_pd_amount = floatval($buyer_info['available_predeposit']);
        if ($available_pd_amount <= 0) return $order_info;

        $model_order_vr = Model('order_vr');
        $model_pd = Model('predeposit');

        $order_amount = floatval($order_info['order_amount'])-floatval($order_info['rcb_amount']);
        $data_pd = array();
        $data_pd['member_id'] = $buyer_info['member_id'];
        $data_pd['member_name'] = $buyer_info['member_name'];
        $data_pd['amount'] = $order_amount;
        $data_pd['order_sn'] = $order_info['order_sn'];

        if ($available_pd_amount >= $order_amount) {

            //预存款立即支付，订单支付完成
            $model_pd->changePd('order_pay',$data_pd);
            $available_pd_amount -= $order_amount;

            //下单，支付被冻结的充值卡
            $pd_amount = floatval($order_info['rcb_amount']);
            if ($pd_amount > 0) {
                $data_pd = array();
                $data_pd['member_id'] = $buyer_info['member_id'];
                $data_pd['member_name'] = $buyer_info['member_name'];
                $data_pd['amount'] = $pd_amount;
                $data_pd['order_sn'] = $order_info['order_sn'];
                $model_pd->changeRcb('order_comb_pay',$data_pd);
            }

            // 订单状态 置为已支付
            $data_order = array();
            $order_info['order_state'] = $data_order['order_state'] = ORDER_STATE_PAY;
            $order_info['payment_time'] = $data_order['payment_time'] = TIMESTAMP;
            $order_info['payment_code'] = $data_order['payment_code'] = 'predeposit';
            $order_info['pd_amount'] = $data_order['pd_amount'] = $order_amount;
            $result = $model_order_vr->editOrder($data_order,array('order_id'=>$order_info['order_id']));
            if (!$result) {
                throw new Exception('订单更新失败');
            }

            //发放兑换码
            $model_order_vr->addOrderCode($order_info);       
       //发送兑换码到手机
            $param = array('order_id'=>$order_info['order_id'],'buyer_id'=>$order_info['buyer_id'],'buyer_phone'=>$order_info['buyer_phone']);
            QueueClient::push('sendVrCode', $param);
                // 支付成功发送店铺消息
                $param = array();
                $param['code'] = 'new_order';
                $param['store_id'] = $order_info['store_id'];
                $param['param'] = array(
                        'order_sn' => $order_info['order_sn']
                );
                QueueClient::push('sendStoreMsg', $param);

        } else {

            //暂冻结预存款,后面还需要 API彻底完成支付
            $data_pd['amount'] = $available_pd_amount;
            $model_pd->changePd('order_freeze',$data_pd);
            //预存款支付金额保存到订单
            $data_order = array();
            $order_info['pd_amount'] = $data_order['pd_amount'] = $available_pd_amount;
            $result = $model_order_vr->editOrder($data_order,array('order_id'=>$order_info['order_id']));
            if (!$result) {
                throw new Exception('订单更新失败');
            }
        }
        return $order_info;
    }

    /**
     * 取得抢购信息
     * @param array $goods_content
     * @return array
     */
    private function _getRobbuyInfo($goods_content = array()) {
        if (!C('robbuy_allow') || empty($goods_content) || !is_array($goods_content)) return $goods_content;
    
        $robbuy_info = Model('robbuy')->getRobbuyInfoByGoodsCommonID($goods_content['goods_commonid']);
        if (empty($robbuy_info)) return $goods_content;
        // 虚拟抢购数量限制
        if ($robbuy_info['upper_limit'] > 0 && $robbuy_info['upper_limit'] < $goods_content['virtual_limit']) {
            $goods_content['virtual_limit'] = $robbuy_info['upper_limit'];
        }
        $goods_content['goods_price'] = $robbuy_info['robbuy_price'];
        $goods_content['robbuy_id'] = $robbuy_info['robbuy_id'];
        $goods_content['ifrobbuy'] = true;
    
        return $goods_content;
    }
}