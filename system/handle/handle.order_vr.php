<?php
/**
 * 虚拟订单行为
 *

 
 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');
class order_vrHandle {

    /**
     * 取消订单
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $msg 操作备注
     * @param boolean $if_queue 是否使用队列
     * @return array
     */
    public function changeOrderStateCancel($order_info, $role, $msg, $if_queue = true) {

        try {

            $model_order_vr = Model('order_vr');
            $model_order_vr->beginTransaction();
            $_info = $model_order_vr->table('order_vr')->where(array('order_id'=> $order_info['order_id']))->master(true)->lock(true)->find();
            if ($_info['order_state'] == ORDER_STATE_CANCEL) {
                throw new Exception('参数错误');
            }

            //库存、销量变更
            //为保证数据准确，不使用队列
            $result = Handle('queue')->cancelOrderUpdateStorage(array($order_info['goods_id'] => $order_info['goods_num']));
            if (!$result['state']) {
                throw new Exception('还原库存失败');
            }

            $model_pd = Model('predeposit');

            //解冻充值卡
            $pd_amount = floatval($order_info['rcb_amount']);
            if ($pd_amount > 0) {
                $data_pd = array();
                $data_pd['member_id'] = $order_info['buyer_id'];
                $data_pd['member_name'] = $order_info['buyer_name'];
                $data_pd['amount'] = $pd_amount;
                $data_pd['order_sn'] = $order_info['order_sn'];
                $model_pd->changeRcb('order_cancel',$data_pd);
            }

            //解冻预存款
            $pd_amount = floatval($order_info['pd_amount']);
            if ($pd_amount > 0) {
                $data_pd = array();
                $data_pd['member_id'] = $order_info['buyer_id'];
                $data_pd['member_name'] = $order_info['buyer_name'];
                $data_pd['amount'] = $pd_amount;
                $data_pd['order_sn'] = $order_info['order_sn'];
                $model_pd->changePd('order_cancel',$data_pd);
            }

            //更新订单信息
            $update_order = array(
                    'order_state' => ORDER_STATE_CANCEL,
                    'pd_amount' => 0,
                    'close_time' => TIMESTAMP,
                    'close_reason' => $msg
            );
            $update = $model_order_vr->editOrder($update_order,array('order_id'=>$order_info['order_id']));
            if (!$update) {
                throw new Exception('保存失败');
            }

            $model_order_vr->commit();
            return callback(true,'更新成功');

        } catch (Exception $e) {
            $model_order_vr->rollback();
            return callback(false,$e->getMessage());
        }
    }

    /**
     * 支付订单
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $post
     * @return array
     */
    public function changeOrderStatePay($order_info, $role, $post) {
        try {

            $model_order_vr = Model('order_vr');
            $model_order_vr->beginTransaction();
            $_info = $model_order_vr->getOrderInfo(array('order_id'=>$order_info['order_id']), 'order_state', true,true);
            if ($_info['order_state'] == ORDER_STATE_PAY) {
                return callback(true,'更新成功');
            }
            $model_pd = Model('predeposit');
            //下单，支付被冻结的充值卡
            $rcb_amount = floatval($order_info['rcb_amount']);
            if ($rcb_amount > 0) {
                $data_pd = array();
                $data_pd['member_id'] = $order_info['buyer_id'];
                $data_pd['member_name'] = $order_info['buyer_name'];
                $data_pd['amount'] = $rcb_amount;
                $data_pd['order_sn'] = $order_info['order_sn'];
                $model_pd->changeRcb('order_comb_pay',$data_pd);
            }

            //下单，支付被冻结的预存款
            $pd_amount = floatval($order_info['pd_amount']);
            if ($pd_amount > 0) {
                $data_pd = array();
                $data_pd['member_id'] = $order_info['buyer_id'];
                $data_pd['member_name'] = $order_info['buyer_name'];
                $data_pd['amount'] = $pd_amount;
                $data_pd['order_sn'] = $order_info['order_sn'];
                $model_pd->changePd('order_comb_pay',$data_pd);
            }

            //更新订单状态
            $update_order = array();
            $update_order['order_state'] = ORDER_STATE_PAY;
            $update_order['payment_time'] = $post['payment_time'] ? strtotime($post['payment_time']) : TIMESTAMP;
            $update_order['payment_code'] = $post['payment_code'];
            $update_order['trade_no'] = $post['trade_no'];
            $update = $model_order_vr->editOrder($update_order,array('order_id'=>$order_info['order_id'],'order_state'=>ORDER_STATE_NEW));
            if (!$update) {
                throw new Exception(L('wt_common_save_fail'));
            }
            //发放兑换码
            $insert = $model_order_vr->addOrderCode($order_info);
            if (!$insert) {
                throw new Exception('兑换码生成失败');
            }

            // 支付成功发送买家消息
            $param = array();
            $param['code'] = 'order_payment_success';
            $param['member_id'] = $order_info['buyer_id'];
            $param['param'] = array(
                    'order_sn' => $order_info['order_sn'],
                    'order_url' => urlShop('member_order_vr', 'show_order', array('order_id' => $order_info['order_id']))
            );
			$param['param']['mp_array'] = Handle('wx_api')->getTemplateData($param['code'], $order_info);
            QueueClient::push('sendMemberMsg', $param);

            // 支付成功发送店铺消息
            $param = array();
            $param['code'] = 'new_order';
            $param['store_id'] = $order_info['store_id'];
            $param['param'] = array(
                    'order_sn' => $order_info['order_sn']
            );
            QueueClient::push('sendStoreMsg', $param);

            //发送兑换码到手机
            $param = array('order_id'=>$order_info['order_id'],'buyer_id'=>$order_info['buyer_id'],'buyer_phone'=>$order_info['buyer_phone'],'goods_name'=>$order_info['goods_name']);
            QueueClient::push('sendVrCode', $param);

            $model_order_vr->commit();
            return callback(true,'更新成功');

        } catch (Exception $e) {
            $model_order_vr->rollback();
            return callback(false,$e->getMessage());
        }
    }

    /**
     * 完成订单(如果全部兑换完成)
     * @param int $order_id
     * @return array
     */
    public function changeOrderStateSuccess($order_id) {
        $model_order_vr = Model('order_vr');
        $condition = array();
        $condition['vr_state'] = 0;
        $condition['refund_lock'] = array('in',array(0,1));
        $condition['order_id'] = $order_id;
        $condition['vr_indate'] = array('gt',TIMESTAMP);
        $order_code_info = $model_order_vr->getOrderCodeInfo($condition,'*',true);
        if (empty($order_code_info)) {
            $update = $model_order_vr->editOrder(array('order_state' => ORDER_STATE_SUCCESS,'finnshed_time' => TIMESTAMP), array('order_id' => $order_id));
            if (!$update) {
                callback(false,'更新失败');
            }
            $order_info = $model_order_vr->getOrderInfo(array('order_id'=>$order_id));
            //添加会员积分
            if (C('points_isuse') == 1){
                Model('points')->savePointsLog('order',array('pl_memberid'=>$order_info['buyer_id'],'pl_membername'=>$order_info['buyer_name'],'orderprice'=>$order_info['order_amount'],'order_sn'=>$order_info['order_sn'],'order_id'=>$order_info['order_id']),true);
            }

            //添加会员经验值
            Model('exppoints')->saveExppointsLog('order',array('exp_memberid'=>$order_info['buyer_id'],'exp_membername'=>$order_info['buyer_name'],'orderprice'=>$order_info['order_amount'],'order_sn'=>$order_info['order_sn'],'order_id'=>$order_info['order_id']),true);
        }

        return callback(true,'更新成功');
    }
}
