<?php
/**
 * 任务计划 - 小时执行的任务
 *
 *

 
 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');

class hourControl extends BaseCronControl {
    /**
     * 执行频率常量 1小时
     * @var int
     */
    const EXE_TIMES = 3600;

    private $_doc;
    private $_xs;
    private $_index;
    private $_search;
    private $_contract_item;

    /**
     * 默认方法
     */
    public function indexWt() {

        //未付款订单超期自动关闭
        $this->_order_timeout_cancel();

        if (C('fenxiao_isuse')) {//分销商品统计
            $this->_fx_goods_sta_num_update();
            $this->_fx_goods_sta_pay_update();
            $this->_fx_goods_sta_order_update();
            $this->_fx_goods_pay();
        }

        //更新全文搜索内容
        $this->_xs_update();
    }

    /**
     * 未付款订单超期自动关闭
     */
    private function _order_timeout_cancel() {
    
        //实物订单超期未支付系统自动关闭
        $_break = false;
        $model_order = Model('order');
        $handle_order = Handle('order');
        $handle_order_book = Handle('order_book');
        $condition = array();
        $condition['order_state'] = ORDER_STATE_NEW;
        $condition['chain_code'] = 0;
        $condition['api_pay_time'] = 0;
        $condition['add_time'] = array('lt',TIMESTAMP - ORDER_AUTO_CANCEL_TIME * self::EXE_TIMES);
        //分批，每批处理100个订单，最多处理5W个订单
        for ($i = 0; $i < 500; $i++){
            if ($_break) {
                break;
            }
            $order_list = $model_order->getOrderList($condition, '', '*', '', 100);
            if (empty($order_list)) break;
            foreach ($order_list as $order_info) {
                if ($order_info['order_type'] != 2) {
                    $result = $handle_order->changeOrderStateCancel($order_info,'system','系统','超期未支付系统自动关闭订单',true,array('order_state'=>ORDER_STATE_NEW));
                } else {
                    //预定订单单独处理
                    $result = $handle_order_book->changeOrderStateCancel($order_info,'system','系统','超期未支付系统自动关闭订单');
                }

                if (!$result['state']) {
                    $this->log('实物订单超期未支付关闭失败SN:'.$order_info['order_sn']); $_break = true; break;
                }
            }
        }

        //虚拟订单超期未支付系统自动关闭
        $_break = false;
        $model_order_vr = Model('order_vr');
        $handle_order_vr = Handle('order_vr');
        $condition = array();
        $condition['order_state'] = ORDER_STATE_NEW;
        $condition['api_pay_time'] = 0;
        $condition['add_time'] = array('lt',TIMESTAMP - ORDER_AUTO_CANCEL_TIME * self::EXE_TIMES);
    
        //分批，每批处理100个订单，最多处理5W个订单
        for ($i = 0; $i < 500; $i++){
            if ($_break) {
                break;
            }
            $order_list = $model_order_vr->getOrderList($condition, '', '*', '',100);
            if (empty($order_list)) break;
            foreach ($order_list as $order_info) {
                $result = $handle_order_vr->changeOrderStateCancel($order_info,'system','超期未支付系统自动关闭订单',false);
            }
            if (!$result['state']) {
                $this->log('虚拟订单超期未支付关闭失败SN:'.$order_info['order_sn']); $_break = true; break;
            }
        }
    }

    /**
     * 分销商品统计(领取次数)
     */
    private function _fx_goods_sta_num_update() {
        $num_update_time = TIMESTAMP;
        $model_fx_goods = Model('fx_goods');
        $condition = array();
        $fx_sta = $model_fx_goods->getDisStaInfo($condition,'num_update_time asc');
        if (!empty($fx_sta) && is_array($fx_sta)) {
            $_time = $fx_sta['num_update_time'];
            $condition = array();
            $condition['fx_time'] = array('gt',$_time);
            $condition['fx_goods_state'] = 1;
            $fx_list = $model_fx_goods->table('fx_goods')->field('count(fx_id) as fx_num,goods_commonid')->group('goods_commonid')->where($condition)->limit(9999)->key('goods_commonid')->select();
            $condition = array();
            $condition['is_fx'] = 1;
            $condition['add_time'] = array('gt',$_time);
            $order_list = $model_fx_goods->table('order_goods')->field('count(distinct order_id) as order_num,sum(goods_pay_price) as order_goods_amount,goods_commonid')->group('goods_commonid')->where($condition)->limit(9999)->key('goods_commonid')->select();
            $result = array_merge(array_keys($fx_list), array_keys($order_list));
            $commonid_list = array_unique($result);
            foreach ($commonid_list as $id) {
                $condition = array();
                $condition['goods_commonid'] = $id;
                $data = array();
                $_fx_num = $fx_list[$id]['fx_num'];
                if ($_fx_num) $data['fx_num'] = array('exp','fx_num+'.$_fx_num);
                $_order_num = $order_list[$id]['order_num'];
                if ($_order_num) $data['order_num'] = array('exp','order_num+'.$_order_num);
                $_goods_amount = $order_list[$id]['order_goods_amount'];
                if ($_goods_amount) $data['order_goods_amount'] = array('exp','order_goods_amount+'.$_goods_amount);
                $model_fx_goods->editDisSta($condition, $data);
            }
            $data = array();
            $data['num_update_time'] = $num_update_time;
            $model_fx_goods->table('fx_goods_sta')->where(true)->update($data);
        }
    }

    /**
     * 分销商品统计(已支付金额)
     */
    private function _fx_goods_sta_pay_update() {
        $pay_update_time = TIMESTAMP;
        $model_fx_goods = Model('fx_goods');
        $condition = array();
        $fx_sta = $model_fx_goods->getDisStaInfo($condition,'pay_update_time asc');
        if (!empty($fx_sta) && is_array($fx_sta)) {
            $_time = $fx_sta['pay_update_time'];
            $condition = array();
            $condition['is_fx'] = 1;
            $condition['payment_time'] = array('gt',$_time);
            $order_id_list = $model_fx_goods->table('orders')->field('order_id')->where($condition)->limit(9999)->key('order_id')->select();
            $condition = array();
            $condition['is_fx'] = 1;
            $condition['order_id'] = array('in',array_keys($order_id_list));
            $order_list = $model_fx_goods->table('order_goods')->field('sum(goods_pay_price) as order_goods_amount,goods_commonid')->group('goods_commonid')->where($condition)->limit(9999)->select();
            foreach ($order_list as $order) {
                $condition = array();
                $condition['goods_commonid'] = $order['goods_commonid'];
                $data = array();
                $_goods_amount = $order['order_goods_amount'];
                $data['pay_goods_amount'] = array('exp','pay_goods_amount+'.$_goods_amount);
                $model_fx_goods->editDisSta($condition, $data);
            }
            $data = array();
            $data['pay_update_time'] = $pay_update_time;
            $model_fx_goods->table('fx_goods_sta')->where(true)->update($data);
        }
    }

    /**
     * 分销商品统计(未结分销佣金)
     */
    private function _fx_goods_sta_order_update() {
        $_update_time = TIMESTAMP;
        $model_fx_goods = Model('fx_goods');
        $model_fx_order = Model('fx_order');
        $condition = array();
        $fx_pay = $model_fx_order->getDisPayInfo($condition);
        $_time = intval($fx_pay['add_time']);
        $condition = array();
        $condition['is_fx'] = 1;
        $condition['finnshed_time'] = array('gt',$_time);
        $order_list = $model_fx_order->table('orders')->field('order_id,order_sn')->where($condition)->limit(9999)->key('order_id')->select();
        if (!empty($order_list) && is_array($order_list)) {
            $fx_pay_list = array();
            $fx_sta = array();
            $condition = array();
            $condition['is_fx'] = 1;
            $condition['order_id'] = array('in',array_keys($order_list));
            $goods_list = $model_fx_order->table('order_goods')->where($condition)->limit(9999)->select();
            foreach ($goods_list as $k => $v) {
                $order_id = $v['order_id'];
                $goods_commonid = $v['goods_commonid'];
                $_pay = array();
                $order_sn = $order_list[$order_id]['order_sn'];
                $_pay['order_goods_id'] = $v['rec_id'];
                $_pay['order_id'] = $order_id;
                $_pay['order_sn'] = $order_sn;
                $_pay['store_id'] = $v['store_id'];
                $_pay['fx_member_id'] = $v['fx_member_id'];
                $_pay['goods_id'] = $v['goods_id'];
                $_pay['goods_commonid'] = $goods_commonid;
                $_pay['goods_name'] = $v['goods_name'];
                $_pay['goods_image'] = $v['goods_image'];
                $_pay['add_time'] = $_update_time;
                $_pay['pay_goods_amount'] = $v['goods_pay_price'];
                $_pay['fx_commis_rate'] = $v['fx_commis_rate'];
                $_pay['fx_pay_amount'] = wtPriceFormat($v['goods_pay_price']*$v['fx_commis_rate']/100);
                $_pay['log_state'] = 0;
                $fx_pay_list[] = $_pay;
                $fx_sta[$goods_commonid] = wtPriceFormat($_pay['fx_pay_amount']+$fx_sta[$goods_commonid]);
            }
            if (!empty($fx_pay_list) && is_array($fx_pay_list)) {
                $model_fx_order->table('fx_pay')->insertAll($fx_pay_list);
                foreach ($fx_sta as $k => $v) {
                    $condition = array();
                    $condition['goods_commonid'] = $k;
                    $data = array();
                    $data['order_commis_amount'] = array('exp','order_commis_amount+'.$v);
                    $model_fx_goods->editDisSta($condition, $data);//分销商品统计
                }
            }
        }
        $condition = array();
        $condition['fx_pay_state'] = 0;
        $condition['is_fx'] = 1;
        $condition['seller_state'] = 2;//商家处理状态:1为待审核,2为同意,3为不同意
        $condition['refund_state'] = 3;//管理员处理状态:1为处理中,2为待处理,3为已完成
        $refund_list = $model_fx_order->table('refund_return')->where($condition)->limit(9999)->select();
        if (!empty($refund_list) && is_array($refund_list)) {
            foreach ($refund_list as $k => $v) {
                $goods_commonid = $v['goods_commonid'];
                $_amount = wtPriceFormat($v['refund_amount']*$v['fx_commis_rate']/100);
                if ($_amount > 0) {
                    $condition = array();
                    $condition['goods_commonid'] = $goods_commonid;
                    $data = array();
                    $data['order_commis_amount'] = array('exp','order_commis_amount-'.$_amount);
                    $model_fx_goods->editDisSta($condition, $data);//分销商品统计
                    $order_goods_id = $v['order_goods_id'];
                    $condition = array();
                    $condition['log_state'] = 0;//未结分销佣金
                    $condition['order_goods_id'] = $order_goods_id;
                    $data = array();
                    $data['refund_amount'] = $v['refund_amount'];
                    $data['fx_pay_amount'] = array('exp','fx_pay_amount-'.$_amount);
                    $model_fx_order->table('fx_pay')->where($condition)->update($data);
                    $model_fx_order->table('refund_return')->where(array('refund_id'=> $v['refund_id']))->update(array('fx_pay_state'=> 1));
                }
            }
        }
    }

    /**
     * 分销商品统计(已结分销佣金)
     */
    private function _fx_goods_pay() {
        $_update_time = TIMESTAMP;
        $model_fx_goods = Model('fx_goods');
        $model_fx_order = Model('fx_order');
        $model_refund = Model('refund_return');
        $model_trade = Model('trade');
        $condition = array();
        $condition['refund_state'] = array('lt',3);//处理状态:1为处理中,2为待管理员处理,3为已完成
        $refund_list = $model_refund->table('refund_return')->field('order_id')->where($condition)->limit(9999)->key('order_id')->select();
        $order_ids = array_keys($refund_list);//有没完成退款的订单不进行佣金结算
        $order_refund = $model_trade->getMaxDay('order_refund');//收货完成后可以申请退款退货天数
        $condition = array();
        $condition['log_state'] = 0;//未结分销佣金
        $condition['add_time'] = array('lt',$_update_time-60*60*24*$order_refund);
        $condition['order_id'] = array('not in',$order_ids);
        $fx_pay_list = $model_fx_order->table('fx_pay')->where($condition)->order('add_time asc')->limit(9999)->select();
        if (!empty($fx_pay_list) && is_array($fx_pay_list)) {
            $order_list = array();
            $member_list = array();
            $data_log_list = array();
            try {
                $model_fx_order->beginTransaction();
                foreach ($fx_pay_list as $k => $v) {
                    $order_id = $v['order_id'];
                    $fx_member_id = $v['fx_member_id'];
                    $order = $order_list[$order_id];
                    if (empty($order)) {
                        $order = $model_fx_order->table('orders')->where(array('order_id'=> $order_id))->find();
                        $order_list[$order_id] = $order;
                    }
                    $refund_state = $model_refund->getRefundState($order);//根据订单状态判断是否可以退款退货
                    if ($refund_state == 0) {
                        $data_trad = array();
                        $data_trad['trad_amount'] = array('exp','trad_amount+'.$v['fx_pay_amount']);
                        Model('member')->editMember(array('member_id'=> $fx_member_id),$data_trad);
                        $data = array();
                        $data['fx_commis_amount'] = array('exp','fx_commis_amount+'.$v['fx_pay_amount']);//已结金额
                        $data['order_commis_amount'] = array('exp','order_commis_amount-'.$v['fx_pay_amount']);//未结金额
                        $model_fx_goods->editDisSta(array('goods_commonid'=> $v['goods_commonid']), $data);//分销商品统计
                        $data = array();
                        $data['fx_pay_time'] = $_update_time;
                        $data['log_state'] = 1;//已结
                        $model_fx_order->table('fx_pay')->where(array('log_id'=> $v['log_id']))->update($data);
                        
                        $member = $member_list[$fx_member_id];
                        if (empty($member)) {
                            $member = $model_fx_order->table('member')->where(array('member_id'=> $fx_member_id))->find();
                            $member_list[$fx_member_id] = $member;
                        }
                        $data_log = array();
                        $data_log['lg_member_id'] = $fx_member_id;
                        $data_log['lg_member_name'] = $member['member_name'];
                        $data_log['lg_add_time'] = TIMESTAMP;
                        $data_log['lg_type'] = 'trad_bill';
                        $data_log['lg_av_amount'] = $v['fx_pay_amount'];
                        $data_log['lg_desc'] = '订单结算获得佣金，商品: '.$v['goods_name'];
                        $data_log_list[] = $data_log;
                    }
                }
                if (!empty($data_log_list)) $model_fx_order->table('fx_trad_log')->insertAll($data_log_list);
                $model_fx_order->commit();
            } catch (Exception $e) {
                $model_fx_order->rollback();
                return false;
            }
        }
    }

    /**
     * 初始化对象
     */
    private function _ini_xs(){
        require(BASE_DATA_PATH.'/api/xs/lib/XS.php');
        $this->_doc = new XSDocument();
        $this->_xs = new XS(C('fullindexer.appname'));
        $this->_index = $this->_xs->index;
        $this->_search = $this->_xs->search;
        $this->_search->setCharset(CHARSET);

        //查询消费者保障服务
        if (C('contract_allow') == 1) {
            $this->_contract_item = Model('contract')->getContractItemByCache();
        }
    }

    /**
     * 全量创建索引
     */
    public function xs_createWt() {
        if (!C('fullindexer.open')) return;
        $this->_ini_xs();
        $model = Model();
        try {
            //每次批量更新商品数
            $step_num = 100;
            $model_goods = Model('goods');

            if (C('dbdriver') == 'mysqli') {
                $_field = "CONCAT(goods_commonid,',',color_id)";
                $_distinct = 'wt_distinct';
            }
            $count = $model_goods->getGoodsOnlineCount(array(),"distinct ".$_field);
            echo 'Total:'.$count."\n";
            if ($count != 0) {
                for ($i = 0; $i <= $count; $i = $i + $step_num){
                    if (C('dbdriver') == 'mysqli') {
                        $goods_list = $model_goods->getGoodsOnlineList(array(), '*,'.$_field.' wt_distinct', 0, '', "{$i},{$step_num}", $_distinct);
                    }
                    $this->_build_goods($goods_list);
                    echo $i." ok\n";
                    flush();
                    ob_flush();
                }
            }

            if ($count > 0) {
                sleep(2);
                $this->_index->flushIndex();
                sleep(2);
                $this->_index->flushLogging();
            }
        } catch (XSException $e) {
            $this->log($e->getMessage());
        }
    }

    /**
     * 更新增量索引
     */
    public function _xs_update() {
        if (!C('fullindexer.open')) return;
        $this->_ini_xs();
        $model = Model();
        try {
            //更新多长时间内的新增(编辑)商品信息，该时间一般与定时任务触发间隔时间一致,单位是秒,默认3600
            $step_time = self::EXE_TIMES + 60;
            //每次批量更新商品数
            $step_num = 100;

            $model_goods = Model('goods');
            $condition = array();
            $condition['goods_edittime'] = array('egt',TIMESTAMP-$step_time);
            if (C('dbdriver') == 'mysqli') {
                $_field = "CONCAT(goods_commonid,',',color_id)";
                $_distinct = 'wt_distinct';
            }
            $count = $model_goods->getGoodsOnlineCount($condition,"distinct ".$_field);
            echo 'Total:'.$count."\n";
            for ($i = 0; $i <= $count; $i = $i + $step_num){
                if (C('dbdriver') == 'mysqli') {
                    $goods_list = $model_goods->getGoodsOnlineList($condition, '*,'.$_field.' wt_distinct', 0, '', "{$i},{$step_num}", $_distinct);
                }
                //通过commonid得到所有goods_id，然后删除全文索引中的goods_id内容
                $goods_commonid_array = array();
                foreach ($goods_list as $_v) {
                    $goods_commonid_array[] = $_v['goods_commonid'];
                }
                if ($goods_commonid_array) {
                    $condition1 = array('goods_commonid' => array('in',$goods_commonid_array));
                    $goods_list1 = $model_goods->getGoodsOnlineList($condition1, 'goods_id', 0, '', '', false);
                    if ($goods_list1) {
                        $goods_id_array = array();
                        foreach ($goods_list1 as $_v) {
                            $goods_id_array[] = $_v['goods_id'];
                        }
                        $this->_index->del($goods_id_array);
                    }
                }
                $this->_build_goods($goods_list);
                echo $i." ok\n";
                flush();
                ob_flush();
            }
            if ($count > 0) {
                sleep(2);
                $this->_index->flushIndex();
                sleep(2);
                $this->_index->flushLogging();
            }
        } catch (XSException $e) {
            $this->log($e->getMessage());
        }
    }

    /**
     * 索引商品数据
     * @param array $goods_list
     */
    private function _build_goods($goods_list = array()) {
        if (empty($goods_list) || !is_array($goods_list)) return;
        $goods_class = Model('goods_class')->getGoodsClassForCacheModel();
        $model_goods = Model('goods');
        $goods_commonid_array = array();
        $goods_id_array = array();
        $store_id_array = array();
        foreach ($goods_list as $k => $v) {
            $goods_commonid_array[] = $v['goods_commonid'];
            $goods_id_array[] = $v['goods_id'];
            $store_id_array[] = $v['store_id'];
        }

        //商品图
        $image_list = $model_goods->getGoodsImageList(array('goods_commonid' => array('in',$goods_commonid_array)), '*', 'is_default desc,goods_image_id asc');

        // 店铺
        $store_list = Model('store')->getStoreMemberIDList($store_id_array);

        $kill_common_ids = array();
        //首先进行一次循环，根据商品分类的show_type设置，确定哪些SKU显示，缓存哪些商品图
        foreach ($goods_list as $k => $goods_content) {
            if ($goods_class[$goods_content['gc_id']]['show_type'] == 1) {
                //原来的显示方式，显示多个SKU,每个SKU显示各自的图
                foreach ($image_list as $image_info) {
                    if ($goods_content['goods_commonid'] == $image_info['goods_commonid'] 
                    && $goods_content['store_id'] == $image_info['store_id'] 
                    && $goods_content['color_id'] == $image_info['color_id']) {
                        $goods_list[$k]['image'][] = $image_info['goods_image'];
                    }
                }
            } else {
                //一个commonid中只显示一个SKU，显示各个SKU的主图
                foreach ($image_list as $image_info) {
                    if ($goods_content['goods_commonid'] == $image_info['goods_commonid'] 
                    && $goods_content['store_id'] == $image_info['store_id'] 
                    && $image_info['is_default'] == 1) {
                        $goods_list[$k]['image'][] = $image_info['goods_image'];
                    }
                }
                if (in_array($goods_content['goods_commonid'],$kill_common_ids)) {
                    unset($goods_list[$k]);
                } else {
                    $kill_common_ids[] = $goods_content['goods_commonid'];
                }
            }
        }

        //取common表内容
        $condition_common = array();
        $condition_common['goods_commonid'] = array('in',$goods_commonid_array);
        $goods_common_list = $model_goods->getGoodsCommonOnlineList($condition_common,'*',0);
        $goods_common_new_list = array();
        foreach($goods_common_list as $k => $v) {
            $goods_common_new_list[$v['goods_commonid']] = $v;
        }

        //取属性表值
        $model_type = Model('type');
        $attr_list = $model_type->getGoodsAttrIndexList(array('goods_id'=>array('in',$goods_id_array)),0,'goods_id,attr_value_id');
        if (is_array($attr_list) && !empty($attr_list)) {
            $attr_value_list = array();
            foreach ($attr_list as $val) {
                $attr_value_list[$val['goods_id']][] = $val['attr_value_id'];
            }
        }

        //处理商品消费者保障服务信息
        $goods_list = $model_goods->getGoodsContract($goods_list, $this->_contract_item);

        //整理需要索引的数据
        foreach ($goods_list as $k => $v) {
			$cate_3 = $cate_2 = $cate_1 = null;
            $gc_id = $v['gc_id'];
            $depth = $goods_class[$gc_id]['depth'];
            if ($depth == 3) {
                $cate_3 = $gc_id; $gc_id = $goods_class[$gc_id]['gc_parent_id']; $depth--;
            }
            if ($depth == 2) {
                $cate_2 = $gc_id; $gc_id = $goods_class[$gc_id]['gc_parent_id']; $depth--;
            }
            if ($depth == 1) {
                $cate_1 = $gc_id; $gc_id = $goods_class[$gc_id]['gc_parent_id'];
            }
            $index_data = array();
            $index_data['pk'] = $v['goods_id'];
            $index_data['goods_id'] = $v['goods_id'];
            $index_data['goods_name'] = $v['goods_name'];
            $index_data['goods_jingle'] = $v['goods_jingle'];
            $index_data['brand_id'] = $v['brand_id'];
            $index_data['is_book'] = $v['is_book'];
            $index_data['is_virtual'] = $v['is_virtual'];
            $index_data['goods_sale_price'] = $v['goods_sale_price'];
            $index_data['goods_click'] = $v['goods_click'];
            $index_data['goods_salenum'] = $v['goods_salenum'];
            $index_data['goods_barcode'] = $v['goods_barcode'];
            // 判断店铺是否为自营店铺
            $index_data['store_id'] = $v['is_own_shop'];
            $index_data['area_id'] = $v['areaid_1'];
            $index_data['gc_id'] = $v['gc_id'];
            $index_data['gc_name'] = str_replace('&gt;','',$goods_common_new_list[$v['goods_commonid']]['gc_name']);
            $index_data['brand_name'] = $goods_common_new_list[$v['goods_commonid']]['brand_name'];
            $index_data['have_gift'] = $v['have_gift'] ? 1 : 0;
            if (!empty($attr_value_list[$v['goods_id']])) {
                $index_data['attr_id'] = implode('_',$attr_value_list[$v['goods_id']]);
            }
            if (!empty($cate_1)) {
                $index_data['cate_1'] = $cate_1;
            }else{
				$index_data['cate_1'] = 0;
			}
            if (!empty($cate_2)) {
                $index_data['cate_2'] = $cate_2;
            }else{
				$index_data['cate_2'] = 0;
			}
            if (!empty($cate_3)) {
                $index_data['cate_3'] = $cate_3;
            }else{
				$index_data['cate_3'] = 0;
			}
			for($i=1;$i<=10;$i++) {
			    $index_data['contract_'.$i] = $v['contract_'.$i] ? 1 : 0;
			}
			$index_data['robbuy'] = $v['goods_sale_type'] == 1 ? 1 : 0;
			$index_data['xianshi'] = $v['goods_sale_type'] == 2 ? 1 : 0;

			if (is_array($v['contractlist']) && !empty($v['contractlist'])) {
			    foreach ($v['contractlist'] as $xbk => $xbv) {
			        $v['contractlist'][$xbk] = array();
			        $v['contractlist'][$xbk]['cti_descurl'] = $xbv['cti_descurl'];
			        $v['contractlist'][$xbk]['cti_name'] = $xbv['cti_name'];
			        $v['contractlist'][$xbk]['cti_icon_url_60'] = $xbv['cti_icon_url_60'];
			    }
			}

            $index_data['main_body'] = serialize(array(
            	   'goods_sale_type' => $v['goods_sale_type'],
                   'goods_marketprice' => $v['goods_marketprice'],
                   'contractlist' => $v['contractlist'],
                   'evaluation_good_star' => $v['evaluation_good_star'],
                   'is_virtual' => $v['is_virtual'],
                   'is_fcode' => $v['is_fcode'],
                   'is_presell' => $v['is_presell'],
                   'evaluation_count' => $v['evaluation_count'],
                   'member_id' => $store_list[$v['store_id']]['member_id'],
                   'store_domain' => $store_list[$v['store_id']]['store_domain'],
                   'store_id' => $v['store_id'],
                   'goods_storage' => $v['goods_storage'],
                   'goods_image' => $v['goods_image'],
                   'store_name' => $v['store_name'],
                   'image' => $v['image']
            ));
            //添加到索引库
             $this->_doc->setFields($index_data);
             $this->_index->update($this->_doc);
        }
    }

    public function xs_clearWt(){
        if (!C('fullindexer.open')) return;
        $this->_ini_xs();

        try {
            $this->_index->clean();
        } catch (XSException $e) {
            $this->log($e->getMessage());
        }
    }

    public function xs_flushLoggingWt(){
        if (!C('fullindexer.open')) return;
        $this->_ini_xs();
        try {
            $this->_index->flushLogging();
        } catch (XSException $e) {
            $this->log($e->getMessage());
        }
    }

    public function xs_flushIndexWt(){
        if (!C('fullindexer.open')) return;
        $this->_ini_xs();

        try {
            $this->_index->flushIndex();
        } catch (XSException $e) {
            $this->log($e->getMessage());
        }
    }
}
