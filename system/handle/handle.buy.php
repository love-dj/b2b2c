<?php
/**
 * 购买行为
 *
 *
 *
 *

 
 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');
class buyHandle {

    /**
     * 会员信息
     * @var array
     */
    private $_member_info = array();

    /**
     * 下单数据
     * @var array
     */
    private $_order_data = array();

    /**
     * 表单数据
     * @var array
     */
    private $_post_data = array();

    /**
     * buy_action.handle 对象
     * @var obj
     */
    private $_handle_buy_action;

    public function __construct() {
        $this->_handle_buy_action = Handle('buy_action');
    }

    /**
     * 购买第一步
     * @param unknown $cart_id
     * @param unknown $ifcart
     * @param unknown $member_id
     * @param unknown $store_id
     * @param array $jjg
     * @param int $orderdiscount
     * @param int $member_level
     * @param bool $ifchain
     * @return Ambigous <multitype:unknown, multitype:unknown >
     */
    public function buyStep1($cart_id, $ifcart, $member_id, $store_id, $jjg = null,$orderdiscount = 0,$member_level = 0,$ifchain = null) {

        //平台店会员折扣
        $orderdiscount = 0;
        if ($orderdiscount > 0) {
            $own_shop_ids = Model('store')->getOwnShopIds();
            $orderdiscounts = array();
            foreach ($own_shop_ids as $store_id) {
                $orderdiscounts[$store_id] = $orderdiscount;
            }
        }

        //得到购买商品信息
        if ($ifcart) {
            $result = $this->getCartList($cart_id, $member_id, $jjg,$orderdiscounts);
        } else {
            $result = $this->getGoodsList($cart_id, $member_id, $store_id,$orderdiscounts);
        }
        if(!$result['state']) {
            return $result;
        }

        //得到页面所需数据：收货地址、发票、代金券、预存款、商品列表等信息
        $result = $this->getBuyStep1Data($member_id,$result['data'],$orderdiscount);

        //直接购买的商品显示门店自提(如果商品支持)
        if (!$ifcart && count($result['data']['store_cart_list']) == 1) {
            $goods_content = current($result['data']['store_cart_list']);
            if ($goods_content[0]['is_chain']) {
                $ifchain = 1;
            }
        }
        //处理门店自提
        if ($ifchain && count($result['data']['store_cart_list']) == 1) {
            $result['data']['ifchain'] = true;
            $result['data']['ifshow_chainpay'] = true;
            $result['data']['chain_store_id'] = key($result['data']['store_cart_list']);
        }

        //返回平台折扣信息
        $result['data']['zk_list'] = array();
//         if ($orderdiscount > 0) {
//             foreach ($result['data']['store_cart_list'] as $store_id => $v) {
//                 if (in_array($store_id,$own_shop_ids)) {
//                     $result['data']['zk_list'][$store_id] = sprintf('V[%s]会员%s折',$member_level,$orderdiscount/10);
//                 }
//             }
//         }
        return $result;
    }

    /**
     * 第一步：处理购物车
     *
     * @param array $cart_id 购物车
     * @param int $member_id 会员编号
     */
    public function getCartList($cart_id, $member_id, $jjg = null,$orderdiscounts = array()) {
        $model_cart = Model('cart');

        //取得POST ID和购买数量
        $buy_items = $this->_parseItems($cart_id);
        if (empty($buy_items)) {
            return callback(false, '所购商品无效');
        }

        if (count($buy_items) > 50) {
            return callback(false, '一次最多只可购买50种商品');
        }

        //购物车列表
        $condition = array('cart_id'=>array('in',array_keys($buy_items)), 'buyer_id'=>$member_id);
        $cart_list  = $model_cart->listCart('db', $condition);

        // 加价购条件
        $jjgObj = null;
        if ($jjg && is_array($jjg)) {
            $jjgObj = new \StdClass();
            $jjgObj->jjgPostData = $this->jjgPostDataParser($jjg);
        }

        //购物车列表 [得到最新商品属性及促销信息]
        $cart_list = $this->_handle_buy_action->getGoodsCartList($cart_list, $jjgObj);

        // 计算加价购各个活动总金额
        $jjgCosts = array();
        $jjgStores = array();
        foreach ($cart_list as $cart) {
            $jjgId = (int) $cart['jjgRank'];
            if ($jjgId > 0 && isset($jjgObj->jjgPostData[$jjgId])) {
                $jjgItemCost = $cart['goods_price'] * $cart['goods_num'];
                if (isset($jjgCosts[$jjgId])) {
                    $jjgCosts[$jjgId] += $jjgItemCost;
                } else {
                    $jjgCosts[$jjgId] = $jjgItemCost;
                }
                $jjgStores[$jjgId] = $cart['store_id'];
            }
        }

        // 过滤合法加价购换购商品
        $jjgValidSkus = array();
        $jjgStoreCosts = array();
        foreach ((array) $jjgObj->jjgPostData as $jjgId => $v) {
            foreach ((array) $v as $levelId => $vv) {
                $itemCounter = 0;
                foreach ((array) $vv as $skuId => $vvv) {
                    if (isset($jjgObj->details['cou'][$jjgId]['levels'][$levelId])
                        && isset($jjgObj->details['cou'][$jjgId]['levelSkus'][$levelId][$skuId])
                    ) {
                        $mincost = $jjgObj->details['cou'][$jjgId]['levels'][$levelId]['mincost'];
                        $maxcou = $jjgObj->details['cou'][$jjgId]['levels'][$levelId]['maxcou'];
                        $itemPrice = $jjgObj->details['cou'][$jjgId]['levelSkus'][$levelId][$skuId]['price'];

                        if ($maxcou > 0 && $maxcou <= $itemCounter) {
                            break;
                        }

                        if ($mincost - $jjgCosts[$jjgId] < 0.01) {
                            $itemCounter++;

                            $g = $jjgObj->details['items'][$skuId];
                            $g['jjgId'] = $jjgId;
                            $g['jjgLevel'] = $levelId;
                            $g['jjgPrice'] = $itemPrice;
                            $jjgValidSkus[$jjgId][$skuId] = $g;

                            $jjgStoreCosts[$jjgStores[$jjgId]] += $itemPrice;
                        }
                    }
                }
            }
        }

        $this->_handle_buy_action->parseZhekou($cart_list,$orderdiscounts);

        //商品列表 [优惠套装子商品与普通商品同级罗列]
        $goods_list = $this->_getGoodsList($cart_list);

        //以店铺下标归类
        $store_cart_list = $this->_getStoreCartList($cart_list);
        if (empty($store_cart_list) || !is_array($store_cart_list)) {
            return callback(false, '提交数据错误');
        }

        return callback(true, '', array(
            'goods_list' => $goods_list,
            'store_cart_list' => $store_cart_list,
            'jjgValidSkus' => $jjgValidSkus,
            'jjgStoreCosts' => $jjgStoreCosts,
        ));
    }

    protected function jjgPostDataParser($jjg)
    {
        $r = array();

        foreach ((array) $jjg as $j) {
            if (preg_match('/^(\d+)\|(\d+)\|(\d+)$/', $j, $m)) {
                $r[$m[1]][$m[2]][$m[3]] = 1;
            }
        }

        return $r;
    }

    /**
     * 第一步：处理立即购买
     *
     * @param array $cart_id 购物车
     * @param int $member_id 会员编号
     * @param int $store_id 店铺编号
     */
    public function getGoodsList($cart_id, $member_id, $store_id,$orderdiscounts = array()) {

        //取得POST ID和购买数量
        $buy_items = $this->_parseItems($cart_id);
        if (empty($buy_items)) {
            return callback(false, '所购商品无效');
        }

        $goods_id = key($buy_items);
        $quantity = current($buy_items);
		//批发价v6.5
		if($this->checklowbuyWt($goods_id,$quantity)>0)
		{
			 return callback(false, '购买数量未达到起批数量');
		}

        //商品信息[得到最新商品属性及促销信息]
        $goods_content = $this->_handle_buy_action->getGoodsOnlineInfo($goods_id,intval($quantity));
	//批发价v6.5
		$goods_step_price = Model('goods')->getGoodStepPrice('common_id='.$goods_id.' and step_l_num<='.$quantity.' and step_h_num>'.$quantity);
		
		if($goods_step_price)
		{
			$goods_content['goods_price'] = $goods_step_price[0]['step_price'];
		}
	//end
        if(empty($goods_content)) {
            return callback(false, '商品已下架或不存在');
        }

        //不能购买自己店铺的商品
        if ($goods_content['store_id'] == $store_id) {
            return callback(false, '不能购买自己店铺的商品');
        }

        if (!$goods_content['is_book']) {
            //预定 商品不使用会员折扣
            $this->_handle_buy_action->parseZhekou($goods_content,$orderdiscounts);
        }
        if ($_POST['pingou'] == 1) {
            $model_pingou = Model('p_pingou');
            $condition = array();
            $condition['start_time'] = array('lt',TIMESTAMP);
            $condition['end_time'] = array('gt',TIMESTAMP);
            $condition['goods_id'] = $goods_id;
            $condition['state'] = 1;
            $pingou_info = $model_pingou->getGoodsInfo($condition);
            if (!empty($pingou_info) && is_array($pingou_info)) {
                $goods_content['pingou_sale'] = '1';
                $goods_content['goods_price'] = $pingou_info['pingou_price'];
				if($pingou_info['goods_maxnum'] !=0 )
				{
					if($pingou_info['goods_maxnum'] < $quantity)
					{
						return callback(false, '购买数量不能超过'.$pingou_info['goods_maxnum']);
					}
					$where = array();
					$where['goods_id'] = $goods_id;
					$where['pingou_id'] = $pingou_info['pingou_id'];
					$where['cancel_time'] = 0;
					$where['buyer_id'] = $member_id;
					$_n = Model()->table('order_pingou')->where($where)->sum('goods_num');
					if($pingou_info['goods_maxnum'] < $_n+$quantity)
					{
						return callback(false, '购买数量不能超过'.$pingou_info['goods_maxnum']);
					}
				}
            }
        }

        //进一步处理数组
        $store_cart_list = array();
        $goods_list = array();
        $goods_list[0] = $store_cart_list[$goods_content['store_id']][0] = $goods_content;

        return callback(true, '', array('goods_list' => $goods_list, 'store_cart_list' => $store_cart_list,'is_book'=>$goods_content['is_book']));
    }

	/**
	 * 取得商品最低起批量 v6.5
	 */
	public function checklowbuyWt($goods_id,$quantity){

		$goods_step_price = Model('goods')->getGoodStepPrice('common_id='.$goods_id.' and step_l_num<='.$quantity.' and step_h_num>'.$quantity);
		if($goods_step_price)
		{
			return 0;
		}
		else
		{
			$goods_l = Model('goods')->getGoodStepPrice('common_id='.$goods_id);
			return $goods_l[0]['step_l_num'];
		}
	}
    /**
     * 购买第一步：返回商品、促销、地址、发票等信息，然后交前台抛出
     * @param unknown $member_id
     * @param unknown $data 商品信息
     * @return
     */
    public function getBuyStep1Data($member_id, $data, $orderdiscount = 0) {

        $goods_list = $data['goods_list'];
        $store_cart_list = $data['store_cart_list'];

        //商品金额计算(分别对每个商品/优惠套装小计、每个店铺小计)
        list($store_cart_list,$store_goods_total) = $this->_handle_buy_action->calcCartList($store_cart_list);

        // 加价购
        $jjgValidSkus = $data['jjgValidSkus'];
        $jjgStoreCosts = $data['jjgStoreCosts'];

        // 加价购
        foreach ((array) $store_goods_total as $k => $v) {
            if (isset($jjgStoreCosts[$k])) {
                $v += $jjgStoreCosts[$k];
                $store_goods_total[$k] = wtPriceFormat($v);
            }
        }

        //定义返回数组
        $result = array();

        // 加价购
        $result['jjgValidSkus'] = $jjgValidSkus;
        $result['jjgStoreCosts'] = $jjgStoreCosts;

        $result['store_cart_list'] = $store_cart_list;
        $result['store_goods_total'] = $store_goods_total;

        //预定商品不使用任何优惠
        if (!$data['is_book']) {
            //取得店铺优惠 - 满即送(赠品列表，店铺满送规则列表)
            list($store_premiums_list,$store_mansong_rule_list) = $this->_handle_buy_action->getMansongRuleCartListByTotal($store_goods_total);

            $result['store_premiums_list'] = $store_premiums_list;
            $result['store_mansong_rule_list'] = $store_mansong_rule_list;
            
            //重新计算优惠后(满即送)的店铺实际商品总金额
            $store_goods_total = $this->_handle_buy_action->reCalcGoodsTotal($store_goods_total,$store_mansong_rule_list,'mansong');

            if (APP_ID == 'mobile') {
                $result['store_goods_total_1'] = $store_goods_total;
            }

            //返回店铺可用的代金券
            $result['store_voucher_list'] = $this->_handle_buy_action->getStoreAvailableVoucherList($store_goods_total, $member_id);
            
            //返回可用平台优惠券
            $result['rpt_list'] = $this->_handle_buy_action->getStoreAvailableRptList($member_id);
            $result['rpt_list'] = array_values($result['rpt_list']);
        } else {
            $result['store_premiums_list'] = $result['store_mansong_rule_list'] = $result['store_voucher_list'] = $result['rpt_list'] = array();
        }

        //输出符合满X元包邮条件的店铺ID及包邮设置信息
        $cancel_calc_sid_list = $this->_handle_buy_action->getStoreFreightDescList($store_goods_total);
        $result['cancel_calc_sid_list'] = $cancel_calc_sid_list;

        //将商品ID、运费模板、运费序列化，加密，输出到模板，选择地区AJAX计算运费时作为参数使用
        $freight_list = $this->_handle_buy_action->getStoreFreightList($goods_list,array_keys($cancel_calc_sid_list));
        $result['freight_list'] = $this->buyEncrypt($freight_list, $member_id);

        //输出用户默认收货地址
        $result['address_info'] = Model('address')->getDefaultAddressInfo(array('member_id'=>$member_id));

        //输出有货到付款时，在线支付和货到付款及每种支付下商品数量和详细列表
        $pay_goods_list = $this->_handle_buy_action->getOfflineGoodsPay($goods_list);
        if (!empty($pay_goods_list['offline'])) {
            $result['pay_goods_list'] = $pay_goods_list;
            $result['ifshow_offpay'] = true;
            if ($_POST['pingou'] == 1) $result['ifshow_offpay'] = false;
        } else {
            //如果所购商品只支持线上支付，支付方式不允许修改
            $result['deny_edit_payment'] = true;
        }

        //发票 :只有所有商品都支持增值税发票才提供增值税发票
        foreach ($goods_list as $goods) {
            if (!intval($goods['goods_vat'])) {
                $vat_deny = true;break;
            }
        }
        //不提供增值税发票时抛出true(模板使用)
        $result['vat_deny'] = $vat_deny;
        $result['vat_hash'] = $this->buyEncrypt($result['vat_deny'] ? 'deny_vat' : 'allow_vat', $member_id);

        //输出默认使用的发票信息
        $inv_info = Model('invoice')->getDefaultInvInfo(array('member_id'=>$member_id));
        if ($inv_info['inv_state'] == '2' && !$vat_deny) {
            $inv_info['content'] = '增值税发票 '.$inv_info['inv_company'].' '.$inv_info['inv_code'].' '.$inv_info['inv_reg_addr'];
        } elseif ($inv_info['inv_state'] == '2' && $vat_deny) {
            $inv_info = array();
            $inv_info['content'] = '不需要发票';
        } elseif (!empty($inv_info)) {
            $inv_info['content'] = '普通发票 '.$inv_info['inv_title'].' '.$inv_info['inv_content'];
        } else {
            $inv_info = array();
            $inv_info['content'] = '不需要发票';
        }
        $result['inv_info'] = $inv_info;

        if (APP_ID == 'mobile') {
            $buyer_info = Model('member')->getMemberInfoByID($member_id);
            if (floatval($buyer_info['available_predeposit']) > 0) {
                $result['available_predeposit'] = $buyer_info['available_predeposit'];
            }
            if (floatval($buyer_info['available_rc_balance']) > 0) {
                $result['available_rc_balance'] = $buyer_info['available_rc_balance'];
            }
            $result['member_paypwd'] = $buyer_info['member_paypwd'] ? true : false;
        }

        return callback(true,'',$result);
    }

    /**
     * 购买第二步
     * @param array $post
     * @param int $member_id
     * @param string $member_name
     * @param string $member_email
     * @param int $orderdiscount 会员折扣 0 ~ 100
     * @param int $member_level 会员等级
     * @return array
     */
    public function buyStep2($post, $member_id, $member_name, $member_email, $orderdiscount = 0, $member_level = 0) {

        $this->_member_info['member_id'] = $member_id;
        $this->_member_info['member_name'] = $member_name;
        $this->_member_info['member_email'] = $member_email;
        $this->_member_info['orderdiscount'] = $orderdiscount;
        $this->_member_info['member_level'] = $member_level = 0;
        $this->_post_data = $post;

        try {

            $model = Model('order');
            $model->beginTransaction();
            $order = $model->table('orders')->where(array('buyer_id'=> $member_id,'add_time'=> array('egt',TIMESTAMP-3)))->find();//防止误操作,单个会员3秒内只能提交一个订单
            if (is_array($order) && !empty($order)) {
                throw new Exception('请勿多次提交订单');
            }
            $_info = Model('member')->table('member')->where(array('member_id'=> $member_id))->master(true)->lock(true)->find();//锁定当前会员记录

            //第1步 表单验证
            $this->_createOrderStep1();

            //第2步 得到购买商品信息
            $this->_createOrderStep2();

            //第3步 得到购买相关金额计算等信息
            $this->_createOrderStep3();

            //第4步 生成订单
            $this->_createOrderStep4();

            //第5步 处理预存款
            $this->_createOrderStep5();

            //第6步 订单后续处理
            $this->_createOrderStep6();

            $model->commit();

            return callback(true,'',$this->_order_data);

        }catch (Exception $e){
            $model->rollback();
            return callback(false, $e->getMessage());
        }

    }

    /**
     * 删除购物车商品
     * @param unknown $ifcart
     * @param unknown $cart_ids
     */
    public function delCart($ifcart, $member_id, $cart_ids) {
        if (!$ifcart || !is_array($cart_ids)) return;
        $cart_id_str = implode(',',$cart_ids);
        if (preg_match('/^[\d,]+$/',$cart_id_str)) {
            Handle('queue')->delCart(array('buyer_id'=>$member_id,'cart_ids'=>$cart_ids));
        }
    }

    /**
     * 根据门店自提站ID计算商品库存，返回库存不足的商品ID
     * @param unknown $chain_id
     * @param unknown $product
     * @return NULL
     */
    public function changeChain($chain_id = 0, $product = '') {
        $chain_id = intval($chain_id);
        if ($chain_id <= 0) return null;
        if (strpos($product,'-') !== false) {
            $product = explode('-',$product);
        } else {
            $product = array($product);
        }
        if (empty($product) || !is_array($product)) return null;
        $product = $this->_parseItems($product);
        $condition = array();
        $condition['goods_id'] = array('in',array_keys($product));
        $condition['chain_id'] = $chain_id;
        $list = Model('chain_stock')->getChainStockList($condition);
        if ($list) {
            $_tmp = array();
            foreach ($list as $v) {
                $_tmp[$v['goods_id']] = $v['stock'];
            }
            foreach ($product as $goods_id => $num) {
                if ($_tmp[$goods_id] >= $num) {
                    unset($product[$goods_id]);
                }
            }
        }
        $data = array();
        $data['state'] = 'success';
        $data['product'] = array_keys($product);
        return $data;
    }

    /**
     * 选择不同地区时，异步处理并返回每个店铺总运费以及本地区是否能使用货到付款
     * 如果店铺统一设置了满免运费规则，则运费模板无效
     * 如果店铺未设置满免规则，且使用运费模板，按运费模板计算，如果其中有商品使用相同的运费模板,作为一种商品算运费
     * 如果未找到运费模板，按免运费处理
     * 如果没有使用运费模板，商品运费按快递价格计算，运费不随购买数量增加
     */
    public function changeAddr($freight_hash, $city_id, $area_id, $member_id) {
        //$city_id计算运费模板,$area_id计算货到付款
        $city_id = intval($city_id);
        $area_id = intval($area_id);
        if ($city_id <= 0 || $area_id <= 0) return null;

        //将hash解密，得到运费信息(店铺ID，运费,运费模板ID),hash内容有效期为1小时
        $freight_list = $this->buyDecrypt($freight_hash, $member_id);
        //算运费
        list($store_freight_list,$no_send_tpl_ids) = $this->_handle_buy_action->calcStoreFreight($freight_list, $city_id);
        $data = array();
        $data['state'] = empty($store_freight_list) && empty($no_send_tpl_ids) ? 'fail' : 'success';
        $data['content'] = $store_freight_list;
        $data['no_send_tpl_ids'] = $no_send_tpl_ids;

        $offline_store_id_array = Model('store')->getOwnShopIds();
		$offline_pay = Model('payment')->getPaymentOpenInfo(array('payment_code'=>'offline'));
		
        if ($offline_store_id_array && $offline_pay) {
            $info = Model('area')->getAreaInfo(array('area_id'=>$area_id));
            if ($info['area_deep'] == 4) $area_id = $info['area_parent_id'];
            $allow_offpay_batch = Model('offpay_area')->checkSupportOffpayBatch($area_id, array_values($offline_store_id_array));

            //JS验证使用
            $data['allow_offpay'] = array_filter($allow_offpay_batch) ? '1' : '0';
            $data['allow_offpay_batch'] = $allow_offpay_batch;
        } else {
            //JS验证使用
            $data['allow_offpay'] = '0';
            $data['allow_offpay_batch'] = array();
        }

        //PHP验证使用
        $data['offpay_hash'] = $this->buyEncrypt($data['allow_offpay'] ? 'allow_offpay' : 'deny_offpay', $member_id);
        $data['offpay_hash_batch'] = $this->buyEncrypt($data['allow_offpay_batch'], $member_id);
        return $data;
    }

    /**
     * 验证F码
     * @param int $goods_commonid
     * @param string $fcode
     * @return array
     */
    public function checkFcode($goods_id, $fcode) {
        $fcode_info = Model('goods_fcode')->getGoodsFCode(array('goods_id' => $goods_id,'fc_code' => $fcode,'fc_state' => 0));
        if ($fcode_info) {
            return callback(true,'',$fcode_info);
        } else {
            return callback(false,'F码错误');
        }
    }

    /**
     * 订单生成前的表单验证与处理
     *
     */
    private function _createOrderStep1() {
        $post = $this->_post_data;

        //取得商品ID和购买数量
        $input_buy_items = $this->_parseItems($post['cart_id']);
        if (empty($input_buy_items)) {
            throw new Exception('所购商品无效');
        }

        //验证收货地址
        if (!$post['chain']['id']) {
            $input_address_id = intval($post['address_id']);
            if ($input_address_id <= 0) {
                throw new Exception('请选择收货地址');
            } else {
                $input_address_info = Model('address')->getAddressInfo(array('address_id'=>$input_address_id));
                if ($input_address_info['member_id'] != $this->_member_info['member_id']) {
                    throw new Exception('请选择收货地址');
                }
                if ($input_address_info['dlyp_id']) {
                    $input_dlyp_id = $input_address_info['dlyp_id'];
                }
            }
            //收货地址城市编号
            $input_city_id = intval($input_address_info['city_id']);  
			$input_area_id = intval($input_address_info['area_id']);          
        } else {
            $chain_info = Model('chain')->getChainInfo(array('chain_id'=>intval($post['chain']['id'])));
            if ($chain_info) {
                $input_address_info = array();
                $input_address_info['city_id'] = $chain_info['area_id_2'];
                $input_address_info['area_id'] = $chain_info['area_id'];
                $input_address_info['area_info'] = $chain_info['area_info'];
                $input_address_info['address'] = $chain_info['chain_name'].'('.$chain_info['chain_address'].')';
                $input_address_info['tel_phone'] = $post['chain']['tel_phone'];
                $input_address_info['mob_phone'] = $post['chain']['mob_phone'];
                $input_address_info['true_name'] = $post['chain']['buyer_name'];
                $input_city_id = $chain_info['area_id_2']; 
				$input_area_id = intval($input_address_info['area_id']); 
                $input_chain_id = $chain_info['chain_id'];
            } else {
                throw new Exception('门店地址错误');
            }
        }

        //是否开增值税发票
        $input_if_vat = $this->buyDecrypt($post['vat_hash'], $this->_member_info['member_id']);
        if (!in_array($input_if_vat,array('allow_vat','deny_vat'))) {
            throw new Exception('订单保存出现异常[增值税发票出现错误]，请重试');
        }
        $input_if_vat = ($input_if_vat == 'allow_vat') ? true : false;

        if (!$post['chain']['id']) {
            //是否支持货到付款
            $input_if_offpay = $this->buyDecrypt($post['offpay_hash'], $this->_member_info['member_id']);
            if (!in_array($input_if_offpay,array('allow_offpay','deny_offpay'))) {
                throw new Exception('订单保存出现异常[货到付款验证错误]，请重试');
            }
            $input_if_offpay = ($input_if_offpay == 'allow_offpay') ? true : false;

            //是否支持货到付款 具体到各个店铺
            $input_if_offpay_batch = $this->buyDecrypt($post['offpay_hash_batch'], $this->_member_info['member_id']);
            if (!is_array($input_if_offpay_batch)) {
                throw new Exception('订单保存出现异常[部分店铺付款方式出现异常]，请重试');
            }
        } else {
            $input_if_offpay = false;
            $input_if_offpay_batch = array();
        }

        //付款方式:在线支付/货到付款(online/offline)
        if (!in_array($post['pay_name'],array('online','offline','chain'))) {
            throw new Exception('付款方式错误，请重新选择');
        }
        $input_pay_name = $post['pay_name'];

        //验证发票信息
        if (!empty($post['invoice_id'])) {
            $input_invoice_id = intval($post['invoice_id']);
            if ($input_invoice_id > 0) {
                $input_invoice_info = Model('invoice')->getinvInfo(array('inv_id'=>$input_invoice_id));
                if ($input_invoice_info['member_id'] != $this->_member_info['member_id']) {
                    throw new Exception('请正确填写发票信息');
                }
            }
        }

        //验证代金券
        $input_voucher_list = array();
        if (!empty($post['voucher']) && is_array($post['voucher'])) {
            foreach ($post['voucher'] as $store_id => $voucher) {
                if (preg_match_all('/^(\d+)\|(\d+)\|([\d.]+)$/',$voucher,$matchs)) {
                    if (floatval($matchs[3][0]) > 0) {
                        $input_voucher_list[$store_id]['voucher_t_id'] = $matchs[1][0];
                        $input_voucher_list[$store_id]['voucher_price'] = $matchs[3][0];
                    }
                }
            }
        }

        //验证优惠券
        $input_rpt_info = array();
        if ($post['rpt']) {
            if (preg_match_all('/^(\d+)\|([\d.]+)$/',$post['rpt'],$matchs)) {
                if (floatval($matchs[2][0]) > 0) {
                    $input_rpt_info['coupon_t_id'] = $matchs[1][0];
                    $input_rpt_info['coupon_price'] = $matchs[2][0];
                }
            }
        }

        //取得平台店会员折扣
        $orderdiscounts = array();
        if (!$post['is_book'] && $this->_member_info['orderdiscount'] > 0) {
            $own_shop_ids = Model('store')->getOwnShopIds();
            foreach ($own_shop_ids as $store_id) {
                    $orderdiscounts[$store_id] = $this->_member_info['orderdiscount'];
            }
        }

        //保存数据
        $this->_order_data['input_buy_items'] = $input_buy_items;
        $this->_order_data['input_city_id'] = $input_city_id;
        $this->_order_data['input_area_id'] = $input_area_id;
        $this->_order_data['input_pay_name'] = $input_pay_name;
        $this->_order_data['input_if_offpay'] = $input_if_offpay;
        $this->_order_data['input_if_offpay_batch'] = $input_if_offpay_batch;
        $this->_order_data['input_pay_message'] = $post['pay_message'];
        $this->_order_data['input_address_info'] = $input_address_info;
        $this->_order_data['input_dlyp_id'] = $input_dlyp_id;
        $this->_order_data['input_chain_id'] = $input_chain_id;
        $this->_order_data['input_invoice_info'] = $input_invoice_info;
        $this->_order_data['input_voucher_list'] = $input_voucher_list;
        $this->_order_data['input_rpt_info'] = $input_rpt_info;
        $this->_order_data['order_from'] = $post['order_from'] == 2 ? 2 : 1;
        $this->_order_data['orderdiscount'] = $orderdiscounts;
        $this->_order_data['input_is_book'] = $post['is_book'];

    }

    /**
     * 得到购买商品信息
     *
     */
    private function _createOrderStep2() {
        $post = $this->_post_data;
        $input_buy_items = $this->_order_data['input_buy_items'];
        $input_is_book = $this->_order_data['input_is_book'];

        if ($post['ifcart']) {
            //购物车列表
            $model_cart = Model('cart');
            $condition = array('cart_id'=>array('in',array_keys($input_buy_items)),'buyer_id'=>$this->_member_info['member_id']);
            $cart_list  = $model_cart->listCart('db',$condition);

            // 加价购条件
            $jjgObj = null;

            $jjgPostData = $this->jjgPostDataParser((array) $this->_post_data['jjg']);
            if ($jjgPostData) {
                $jjgObj = new \StdClass();
                $jjgObj->jjgPostData = $jjgPostData;
            }

            //购物车列表 [得到最新商品属性及促销信息]
            $cart_list = $this->_handle_buy_action->getGoodsCartList($cart_list, $jjgObj);

            // 计算加价购各个活动总金额
            $jjgCosts = array();
            $jjgStores = array();
            foreach ($cart_list as $cart) {
                $jjgId = (int) $cart['jjgRank'];
                if ($jjgId > 0 && isset($jjgObj->jjgPostData[$jjgId])) {
                    $jjgItemCost = $cart['goods_price'] * $cart['goods_num'];
                    if (isset($jjgCosts[$jjgId])) {
                        $jjgCosts[$jjgId] += $jjgItemCost;
                    } else {
                        $jjgCosts[$jjgId] = $jjgItemCost;
                    }
                    $jjgStores[$jjgId] = $cart['store_id'];
                }
            }

            // 过滤合法加价购换购商品
            $jjgValidSkus = array();
            $jjgStoreCosts = array();
            foreach ((array) $jjgObj->jjgPostData as $jjgId => $v) {
                foreach ((array) $v as $levelId => $vv) {
                    $itemCounter = 0;
                    foreach ((array) $vv as $skuId => $vvv) {
                        if (isset($jjgObj->details['cou'][$jjgId]['levels'][$levelId])
                            && isset($jjgObj->details['cou'][$jjgId]['levelSkus'][$levelId][$skuId])
                        ) {
                            $mincost = $jjgObj->details['cou'][$jjgId]['levels'][$levelId]['mincost'];
                            $maxcou = $jjgObj->details['cou'][$jjgId]['levels'][$levelId]['maxcou'];
                            $itemPrice = $jjgObj->details['cou'][$jjgId]['levelSkus'][$levelId][$skuId]['price'];

                            if ($maxcou > 0 && $maxcou <= $itemCounter) {
                                break;
                            }

                            if ($mincost - $jjgCosts[$jjgId] < 0.01) {
                                $itemCounter++;

                                $g = $jjgObj->details['items'][$skuId];
                                $g['jjgId'] = $jjgId;
                                $g['jjgLevel'] = $levelId;
                                $g['jjgPrice'] = $itemPrice;
                                $jjgValidSkus[$jjgId][$skuId] = $g;

                                $jjgStoreCosts[$jjgStores[$jjgId]] += $itemPrice;
                            }
                        }
                    }
                }
            }
//             $this->_handle_buy_action->parseZhekou($cart_list,$this->_order_data['orderdiscount']);

            //商品列表 [优惠套装子商品与普通商品同级罗列]
            $goods_list = $this->_getGoodsList($cart_list);

            //以店铺下标归类
            $store_cart_list = $this->_getStoreCartList($cart_list);
            $input_is_book = false;
        } else {

            //来源于直接购买
            $goods_id = key($input_buy_items);
            $quantity = current($input_buy_items);

            //商品信息[得到最新商品属性及促销信息]
            $goods_content = $this->_handle_buy_action->getGoodsOnlineInfo($goods_id,intval($quantity));
            if(empty($goods_content)) {
                throw new Exception('商品已下架或不存在');
            }

            //预定不享受任何优惠
            if ($input_is_book && $goods_content['is_book']) {
                $input_is_book = true;
            } else {
                $input_is_book = false;
                $this->_handle_buy_action->parseZhekou($goods_content,$this->_order_data['orderdiscount']);
            }
            $this->_order_data['input_is_book'] = $input_is_book;
            if ($_POST['pingou'] == 1) {
                $model_pingou = Model('p_pingou');
                $condition = array();
                $condition['start_time'] = array('lt',TIMESTAMP);
                $condition['end_time'] = array('gt',TIMESTAMP);
                $condition['goods_id'] = $goods_id;
                $condition['state'] = 1;
                $pingou_info = $model_pingou->getGoodsInfo($condition);
                if (!empty($pingou_info) && is_array($pingou_info)) {
                    $goods_content['pingou_sale'] = '1';
                    $goods_content['goods_price'] = $pingou_info['pingou_price'];
                    $goods_content['pingou_info'] = $pingou_info;
                }
            }

            //进一步处理数组
            $store_cart_list = array();
            $goods_list = array();
            $goods_list[0] = $store_cart_list[$goods_content['store_id']][0] = $goods_content;

        }

        //F码验证
        $fc_id = $this->_checkFcode($goods_list, $post['fcode']);
        if(!$fc_id) {
            throw new Exception('F码商品验证错误');
        }

        //保存数据
        $this->_order_data['goods_list'] = $goods_list;
        $this->_order_data['store_cart_list'] = $store_cart_list;
        if ($fc_id > 0) {
            $this->_order_data['fc_id'] = $fc_id;
        }

        // 保存加价购数据
        $this->_order_data['jjgValidSkus'] = $jjgValidSkus;
        $this->_order_data['jjgStoreCosts'] = $jjgStoreCosts;

        //验证门店自提
        if ($this->_order_data['input_chain_id']) {
            if (count($store_cart_list) > 1 || !$this->_checkChain(current($store_cart_list))) {
                $this->_order_data['input_chain_id'] = null;
            } else {
                //验证门店自提库存忽略
            }
        }
    }

    /**
     * 得到购买相关金额计算等信息
     *
     */
    private function _createOrderStep3() {
        $goods_list = $this->_order_data['goods_list'];
        $store_cart_list = $this->_order_data['store_cart_list'];
        $input_voucher_list = $this->_order_data['input_voucher_list'];
        $input_city_id = $this->_order_data['input_city_id'];
        $input_rpt_info = $this->_order_data['input_rpt_info'];
        $input_is_book = $this->_order_data['input_is_book'];

        //商品金额计算(分别对每个商品/优惠套装小计、每个店铺小计)
        list($store_cart_list,$store_goods_total) = $this->_handle_buy_action->calcCartList($store_cart_list);

        //加价购 增加订单总额
        foreach ((array) $store_goods_total as $k => $v) {
            if (isset($this->_order_data['jjgStoreCosts'][$k])) {
                $v += $this->_order_data['jjgStoreCosts'][$k];
                $store_goods_total[$k] = wtPriceFormat($v);
            }
        }

        //预定不享受任何优惠
        if (!$input_is_book) {
            //取得店铺优惠 - 满即送(赠品列表，店铺满送规则列表)
            list($store_premiums_list,$store_mansong_rule_list) = $this->_handle_buy_action->getMansongRuleCartListByTotal($store_goods_total);

            //重新计算店铺扣除满即送后商品实际支付金额
            $store_final_goods_total = $this->_handle_buy_action->reCalcGoodsTotal($store_goods_total,$store_mansong_rule_list,'mansong');

            //计算每个店铺运费
            if ($this->_order_data['input_chain_id']) {
                $store_freight_total[key($store_final_goods_total)] = 0;
            } else {
                //取得包邮的店铺ID信息
                $cancel_calc_sid_list = $this->_handle_buy_action->getStoreFreightDescList($store_final_goods_total);
                $freight_list = $this->_handle_buy_action->getStoreFreightList($goods_list,array_keys($cancel_calc_sid_list));
                list($store_freight_total,$no_send_tpl_ids) = $this->_handle_buy_action->calcStoreFreight($freight_list,$input_city_id);
            }

            //得到有效的代金券
            $input_voucher_list = $this->_handle_buy_action->reParseVoucherList($input_voucher_list,$store_final_goods_total,$this->_member_info['member_id']);
            //重新计算店铺扣除优惠券送商品实际支付金额
            $store_final_goods_total = $this->_handle_buy_action->reCalcGoodsTotal($store_final_goods_total,$input_voucher_list,'voucher');

            //计算店铺最终订单实际支付金额(加上运费)```
            $store_final_order_total = $this->_handle_buy_action->reCalcGoodsTotal($store_final_goods_total,$store_freight_total,'freight');

            //计算每个店铺(所有店铺级优惠活动，代金券，满减)总共优惠多少
            $store_sale_total = $this->_handle_buy_action->getStoreSaleTotal($store_goods_total,$store_freight_total, $store_final_order_total);

            //得到有效平台优惠券
            $input_rpt_info = $this->_handle_buy_action->reParseRptInfo($input_rpt_info,array_sum($store_final_order_total),$this->_member_info['member_id']);

            //计算每个订单应用了多少平台优惠券
            list($store_final_order_total,$store_rpt_total) = $this->_handle_buy_action->parseOrderRpt($store_final_order_total,$input_rpt_info['coupon_price']);
            //重新计算优惠金额,将店铺优惠券减去运费的余额追加到店铺总优惠里
            $store_sale_total = $this->_handle_buy_action->reCalcStoreSaleTotal($store_sale_total,$store_freight_total,$store_rpt_total);

            //将赠品追加到购买列表(如果库存0，则不送赠品)
            $append_premiums_to_cart_list = $this->_handle_buy_action->appendPremiumsToCartList($store_cart_list,$store_premiums_list,$store_mansong_rule_list,$this->_member_info['member_id']);
            if($append_premiums_to_cart_list === false) {
                throw new Exception('抱歉，您购买的商品库存不足，请重购买');
            } else {
                list($store_cart_list,$goods_buy_quantity,$store_mansong_rule_list,$goods_sale) = $append_premiums_to_cart_list;
            }
            
            // 加价购 增加商品销量
            foreach ((array) $this->_order_data['jjgValidSkus'] as $k => $v) {
                foreach ((array) $v as $kk => $vv) {
                    $goods_buy_quantity[$kk] += 1;
                }
            }
        } else {

            //预定订单只有运费
            $cancel_calc_sid_list = $this->_handle_buy_action->getStoreFreightDescList($store_goods_total);
            $freight_list = $this->_handle_buy_action->getStoreFreightList($goods_list,array_keys($cancel_calc_sid_list));
            list($store_freight_total,$no_send_tpl_ids) = $this->_handle_buy_action->calcStoreFreight($freight_list,$input_city_id);
            //计算店铺最终订单实际支付金额(加上运费)
            $store_final_order_total = $this->_handle_buy_action->reCalcGoodsTotal($store_goods_total,$store_freight_total,'freight');
            $store_sale_total = $store_mansong_rule_list = $input_voucher_list = $input_rpt_info = $store_rpt_total = array();
            $goods_buy_quantity = array($goods_list[0]['goods_id'] => $goods_list[0]['goods_num']);
            $goods_sale = array($goods_list[0]['goods_id'] => $goods_list[0]['goods_commonid']);

        }

        if (is_array($no_send_tpl_ids) && !empty($no_send_tpl_ids)) {
            throw new Exception('抱歉，您购买的部分商品无货，请重购买');
        }

        //保存数据
        $this->_order_data['store_goods_total'] = $store_goods_total;
        $this->_order_data['store_final_order_total'] = $store_final_order_total;
        $this->_order_data['store_freight_total'] = $store_freight_total;
        $this->_order_data['store_sale_total'] = $store_sale_total;
        $this->_order_data['store_mansong_rule_list'] = $store_mansong_rule_list;
        $this->_order_data['store_cart_list'] = $store_cart_list;
        $this->_order_data['goods_buy_quantity'] = $goods_buy_quantity;
        $this->_order_data['input_voucher_list'] = $input_voucher_list;
        $this->_order_data['input_rpt_info'] = $input_rpt_info;
        $this->_order_data['store_rpt_total'] = $store_rpt_total;
        $this->_order_data['goods_sale'] = $goods_sale;

    }

    /**
     * 生成订单
     * @param array $input
     * @throws Exception
     * @return array array(支付单sn,订单列表)
     */
    private function _createOrderStep4() {
		
        extract($this->_order_data);

        $member_id = $this->_member_info['member_id'];
        $member_name = $this->_member_info['member_name'];
        $member_email = $this->_member_info['member_email'];
        $member_level = $this->_member_info['member_level'];

        $model_order = Model('order');
        $model_goods = Model('goods');

        //存储生成的订单数据
        $order_list = array();
        //存储通知信息
        $notice_list = array();
        //支付方式
        if ($input_pay_name == 'chain' && $input_chain_id) {
        	$store_pay_type_list = array(key($store_cart_list)=>'chain');
        } else {
            //每个店铺订单是货到付款还是线上支付,店铺ID=>付款方式[在线支付/货到付款]
            $store_pay_type_list = $this->_handle_buy_action->getStorePayTypeList(array_keys($store_cart_list), $input_if_offpay, $input_pay_name);
            foreach ($store_pay_type_list as $k => $v) {
                if (empty($input_if_offpay_batch[$k]))
                    $store_pay_type_list[$k] = 'online';
            }            
        }

        $pay_sn = $this->_handle_buy_action->makePaySn($member_id);
        $order_pay = array();
        $order_pay['pay_sn'] = $pay_sn;
        $order_pay['buyer_id'] = $member_id;
        $order_pay_id = $model_order->addOrderPay($order_pay);
        if (!$order_pay_id) {
            throw new Exception('订单保存失败[未生成支付单]');
        }

        //收货人信息
        list($reciver_info,$reciver_name,$reciver_phone) = $this->_handle_buy_action->getReciverAddr($input_address_info);

        // 加价购换购商品 店铺分组
        $jjgValidStoreSkus = array();
        foreach ((array) $this->_order_data['jjgValidSkus'] as $v) {
            foreach ((array) $v as $vv) {
                $jjgValidStoreSkus[$vv['store_id']][] = $vv;
            }
        }
        $fx_cart_list = array();
        if (C('fenxiao_isuse') == 1) {//分销商品
            $model_fx_goods = Model('fx_goods');
            $fx_cart_list = $model_fx_goods->getDisCartList($member_id);
        }
		
		if(C('points_money_isuse')==1&&C('member_points_payrate')!=0) {
			$allmomey=0;
			foreach($store_final_order_total as $store_id => $money){
				$allmomey+=floatval($money);
			}
			//将积分兑换成现金
			$points_parity = C('points_money_parity');
			$points_money = $this->_post_data['J_PointInput']*$points_parity;
			$allmomey_allow = $allmomey*C('member_points_payrate')*0.01;
			if (floatval($allmomey_allow)<floatval($points_money)) {
				throw new Exception('订单保存失败[积分最多可抵用'.intval($allmomey_allow/$points_parity).'积分]');
			}
		}

        $setting_infos = Model('setting')->getListSetting(); 
        //查找当前购买会员的上级"们"uid       
        $up_uids = Model('member_chain')->getInfoOne('member_chain',array('member_id'=>$member_id),'relation_chain');
        $up_uids_str = $up_uids['relation_chain'];
        /************预加载区域代理配置信息************/
        $agent_isuse = $setting_infos['agent_isuse'];//是否开启区域分红
        $agent_level_difference = $setting_infos['agent_level_difference'];//是否开启极差分红
        $agent_province_rate = $setting_infos['agent_province_rate'];//省代理分红比例
        $agent_city_rate = $setting_infos['agent_city_rate'];//市代理分红比例
        $agent_area_rate = $setting_infos['agent_area_rate'];//区县代理分红比例
        $agent_average_commission = $setting_infos['agent_average_commission'];//是否开启平均分红
        $agent_commission = $setting_infos['agent_commission'];//佣金计算基数
        /************预加载区域代理配置信息************/

        /************预加载团队无限级配置信息************/
        //基础设置
        $team_isuse = $setting_infos['team_isuse'];//是否开启团队无限级
        $team_same_isuse = $setting_infos['team_same_isuse'];//开启平级超越奖
        $team_same_calculation = $setting_infos['team_same_calculation'];//平级奖计算方式
        $team_include_self = $setting_infos['team_include_self'];//团队统计是否包括自己
        //结算设置
        $team_billing_option = $setting_infos['team_billing_option'];//佣金计算去向
        $team_settlement_event = $setting_infos['team_settlement_event'];//结算事件
        $team_settlement_days = $setting_infos['team_settlement_days'];//结算天数
        $team_commission = $setting_infos['team_commission'];//佣金计算项
        $team_superior_list = array();
        if($team_isuse == 1){//开启团队无限级
            if($team_include_self == 1){//团队统计是否包括自己
                if(empty($up_uids_str)){
                    $up_uids_str = $member_id;
                }else{
                    $up_uids_str .= ',' . $member_id;
                }
            }
            if(!empty($up_uids_str)){//存在上级才计算佣金
                $up_arr = explode(',',$up_uids_str);//所有上级的集合
                $comm_up_arr = array_reverse($up_arr);//$comm_up_arr 取出来的顺序是倒序,这里反转一下顺序，
                foreach($comm_up_arr as $k=>$v){
                    $superior_info = Model('member')->getMemberChainByID($v);
                    $team_superior_list[$k]['member_id'] = $superior_info['member_id'];   
                    $team_superior_list[$k]['team_level'] = $superior_info['team_level'];   //用户团队无限级等级
                }   
            }
        }
        /************预加载团队无限级配置信息************/

        /************预加载三级分销配置信息************/
        $distribution_isuse = $setting_infos['distribution_isuse'];//是否开启分销
        $distribution_uselevel = $setting_infos['distribution_uselevel'];//分销层级
        $distribution_level_one = $setting_infos['distribution_level_one'];//默认一级分销佣金比例
        $distribution_level_two = $setting_infos['distribution_level_two'];//默认二级分销佣金比例
        $distribution_level_three = $setting_infos['distribution_level_three'];//默认三级分销佣金比例
        $distribution_self_buy = $setting_infos['distribution_self_buy'];//是否开启自购
        //结算设置
        $distribution_billing_option = $setting_infos['distribution_billing_option'];//佣金计算去向
        $distribution_settlement_event = $setting_infos['distribution_settlement_event'];//结算事件
        $distribution_settlement_days = $setting_infos['distribution_settlement_days'];//结算天数
        $distribution_commission = $setting_infos['distribution_commission'];//佣金计算基数 1:订单实际支付金额;2:商品现价;3:商品成本;

        //先拉取当前买家的所有上级
        $superior_list = array();
        if($distribution_isuse == 1){//开启分销功能才计算佣金
            if($distribution_self_buy == 1){//开启了自购，自己算第一级
                if(empty($up_uids_str)){
                    $up_uids_str = $member_id;
                }else{
                    $up_uids_str .= ',' . $member_id;
                }
            }
            if(!empty($up_uids_str)){//存在上级才计算佣金
                $up_arr = explode(',',$up_uids_str);//所有上级的集合
                $comm_up_arr = array_slice($up_arr, -$distribution_uselevel);//只发放后台设置的分销层级数 取离自己最近的上级，最多3三层，参照后台设置
                $comm_up_arr = array_reverse($comm_up_arr);//$comm_up_arr 取出来的顺序是倒序,这里反转一下顺序，反转后一次是：一级，二级，三级
                foreach($comm_up_arr as $k=>$v){
                    $superior_info = Model('member')->getMemberChainByID($v);
                    $superior_list[$k]['member_id'] = $superior_info['member_id'];   
                    $superior_list[$k]['distribution_level'] = $superior_info['distribution_level']; //用户分销等级
                }   
            }
        } 
        /************预加载三级分销配置信息************/
        
        foreach ($store_cart_list as $store_id => $goods_list) {
            //取得本店优惠额度(后面用来计算每件商品实际支付金额，结算需要)
            $sale_total = !empty($store_sale_total[$store_id]) ? $store_sale_total[$store_id] : 0;

            //本店总的优惠比例,保留3位小数
//             $should_goods_total = $store_final_order_total[$store_id]-$store_freight_total[$store_id]+$sale_total;
            $should_goods_total = $store_goods_total[$store_id];
            $sale_rate = abs(number_format($sale_total/$should_goods_total,5));
            if ($sale_rate <= 1) {
                $sale_rate = floatval(substr($sale_rate,0,5));
            } else {
                $sale_rate = 0;
            }
            //每种商品的优惠金额累加保存入 $sale_sum
            $sale_sum = 0;

            $order = array();
            $order_common = array();
            $order_goods = array();
            $addtime = TIMESTAMP;
            $order['order_sn'] = $this->_handle_buy_action->makeOrderSn($order_pay_id);
            $order['pay_sn'] = $pay_sn;
            $order['store_id'] = $store_id;
            $order['store_name'] = $goods_list[0]['store_name'];
            $order['buyer_id'] = $member_id;
            $order['buyer_name'] = $member_name;
            $order['buyer_email'] = $member_email;
            $order['buyer_phone'] = is_numeric($reciver_phone) ? $reciver_phone : 0;
            $order['add_time'] = $addtime;
            $order['payment_code'] = $store_pay_type_list[$store_id];
            $order['order_state'] = $store_pay_type_list[$store_id] == 'offline' ? ORDER_STATE_PAY : ORDER_STATE_NEW;
            $order['order_amount'] = $store_final_order_total[$store_id];
            $order['shipping_fee'] = $store_freight_total[$store_id];
            $order['goods_amount'] = $order['order_amount'] - $order['shipping_fee'] + $store_rpt_total[$store_id];
            $order['order_from'] = $order_from;
            $order['order_type'] = $input_chain_id ? 3 : ($goods_list[0]['is_book'] ? 2 : 1);
            if ($goods_list[0]['pingou_sale']) $order['order_type'] = 4;
            $order['chain_id'] = $input_chain_id ? $input_chain_id : 0;
            $order['rpt_amount'] = empty($store_rpt_total[$store_id]) ? 0 : $store_rpt_total[$store_id] ;

            $order_id = $model_order->addOrder($order);
            if (!$order_id) {
                throw new Exception('订单保存失败[未生成订单数据]');
            }
            $order['order_id'] = $order_id;
            $order_list[$order_id] = $order;

            $order_common['order_id'] = $order_id;
            $order_common['store_id'] = $store_id;
            $order_common['order_message'] = isset($input_pay_message[$store_id]) ? $input_pay_message[$store_id] : '';

            //代金券
            if (isset($input_voucher_list[$store_id])){
                $order_common['voucher_price'] = $input_voucher_list[$store_id]['voucher_price'];
                $order_common['voucher_code'] = $input_voucher_list[$store_id]['voucher_code'];
            }

            //订单总优惠金额（代金券，满减，平台优惠券）
            $order_common['sale_total'] = $sale_total;

            $order_common['reciver_info']= $reciver_info;
            $order_common['reciver_name'] = $reciver_name;
            $order_common['reciver_city_id'] = $input_city_id;
            $order_common['reciver_area_id'] = $input_area_id;

            //发票信息
            $order_common['invoice_info'] = $this->_handle_buy_action->createInvoiceData($input_invoice_info);

            //保存促销信息
            $order_common['sale_info'] = array();
            if(is_array($store_mansong_rule_list[$store_id])) {
                if (APP_ID != 'mobile') {
                    $order_common['sale_info'][] =  array('满即送',$store_mansong_rule_list[$store_id]['desc']);
                } else {
                    $order_common['sale_info'][] =  array('满即送',$store_mansong_rule_list[$store_id]['desc']['desc']);
                }
                
            }

            //平台优惠券值
            if ($store_rpt_total[$store_id]) {
                $order_common['sale_info'][] = array('平台优惠券',sprintf('使用%s元优惠券 编码：%s',$store_rpt_total[$store_id],$input_rpt_info['coupon_code']));
            }

            //折扣值
//             if ($orderdiscount[$store_id]) {
//                 $order_common['discount'] = $orderdiscount[$store_id];
//                 $order_common['sale_info'] .= '<dl class="wt-store-sales"><dt>会员等级折扣</dt><dd>'.addslashes(sprintf(' [V%s]级会员享受原价%s折',$member_level,$orderdiscount[$store_id]/10)).'</dd></dl>';
//             }

            //代金券
            if (isset($input_voucher_list[$store_id])){
                $order_common['sale_info'][] = array('店铺代金券',sprintf('使用%s元代金券 编码：%s',$input_voucher_list[$store_id]['voucher_price'],$input_voucher_list[$store_id]['voucher_code']));
            }
            $order_common['sale_info'] = $order_common['sale_info'] ? serialize($order_common['sale_info']) : '';

            $insert = $model_order->addOrderCommon($order_common);
            if (!$insert) {
                throw new Exception('订单保存失败[未生成订单扩展数据]');
            }
            //添加订单日志
            $log_data = array();
            $log_data['order_id'] = $order_id;
            $log_data['log_role'] = '买家';
            $log_data['log_msg'] = '生成订单';
            $log_data['log_user'] = $member_name;
            $log_data['log_orderstate'] = ORDER_STATE_NEW;
            $model_order->addOrderLog($log_data);

            //生成order_goods订单商品数据
            $i = 0;
            $order_is_fx = 0;//是否分销订单
            foreach ($goods_list as $goods_content) {
                if (!$goods_content['state'] || !$goods_content['storage_state']) {
                    throw new Exception('抱歉，部分商品存在下架、变更销售方式或库存不足的情况，请重新选择');
                }
				$goods_invit=Model('goods')->getGoodsInfo(array('goods_id'=>$goods_content['goods_id']));
                if (!intval($goods_content['bl_id'])) {
                    //如果不是优惠套装
                    $goods_commonid = $goods_content['goods_commonid'];
                    $order_goods[$i]['order_id'] = $order_id;
                    $order_goods[$i]['goods_id'] = $goods_content['goods_id'];
                    $order_goods[$i]['store_id'] = $store_id;
                    $order_goods[$i]['goods_name'] = $goods_content['goods_name'];
                    $order_goods[$i]['goods_price'] = $goods_content['goods_price'];
                    $order_goods[$i]['goods_num'] = $goods_content['goods_num'];
                    $order_goods[$i]['goods_image'] = $goods_content['goods_image'];
                    $order_goods[$i]['goods_spec'] = $goods_content['goods_spec'];
					$order_goods[$i]['goods_serial'] = $goods_invit['goods_serial'];//订单货号
					$order_goods[$i]['invite_rates'] = $goods_invit['invite_rate'];
                    $order_goods[$i]['buyer_id'] = $member_id;
                    $order_goods[$i]['goods_commonid'] = $goods_commonid;
                    $order_goods[$i]['add_time'] = TIMESTAMP;
                    if ($goods_content['ifrobbuy']) {
                        $ifrobbuy = true;
                        $order_goods[$i]['goods_type'] = 2;
                    }elseif ($goods_content['ifxianshi']) {
                        $order_goods[$i]['goods_type'] = 3;
                    }elseif ($goods_content['ifzengpin']) {
                        $order_goods[$i]['goods_type'] = 5;
                    } elseif ($goods_content['jjgRank'] > 0) {
                        // 加价购活动参与商品
                        $order_goods[$i]['goods_type'] = 8;
                    }else {
                        $order_goods[$i]['goods_type'] = 1;
                    }
                    if ($goods_content['pingou_sale']) $order_goods[$i]['goods_type'] = 10;
                    $order_goods[$i]['sales_id'] = $goods_content['sales_id'] ? $goods_content['sales_id'] : 0;
                    if ($goods_content['jjgRank'] > 0) {
                        // 加价购活动参与商品
                        $order_goods[$i]['sales_id'] = $goods_content['jjgRank'];
                    }

                    $order_goods[$i]['commis_rate'] = 200;
                    $order_goods[$i]['gc_id'] = $goods_content['gc_id'];

                    //记录消费者保障服务
                    $contract_itemid_arr = $goods_content['contractlist']?array_keys($goods_content['contractlist']):array();
                    $order_goods[$i]['goods_contractid'] = $contract_itemid_arr?implode(',',$contract_itemid_arr):'';

                    //计算商品金额(商品现价)
                    $goods_total = $goods_content['goods_price'] * $goods_content['goods_num'];
                    //计算商品成本金额
                    $goods_costprice = $goods_content['goods_costprice'] * $goods_content['goods_num'];

                    //计算本件商品优惠金额
                    $sale_value = floor($goods_total*($sale_rate)*10)/10;//优惠金额精确到1角
                    $order_goods[$i]['goods_pay_price'] = $goods_total - $sale_value < 0 ? 0 : $goods_total - $sale_value;
                    $sale_sum += $sale_value;
                    $order_goods[$i]['is_fx'] = 0;
                    $order_goods[$i]['fx_member_id'] = 0;
                    $order_goods[$i]['fx_commis_rate'] = 0;
                    $fx_member_id = cookie('fx_'.$goods_commonid) ? cookie('fx_'.$goods_commonid) : $fx_cart_list[$goods_commonid]['fx_member_id'];
                    if (C('fenxiao_isuse') == 1 && $goods_content['is_fx'] == 1 && $fx_member_id > 0 && $order_goods[$i]['goods_pay_price'] >= 1) {
                        //分销商品佣金比例
                        $order_is_fx = 1;
                        $order_goods[$i]['is_fx'] = 1;
                        $order_goods[$i]['fx_member_id'] = $fx_member_id;
                        $_info = $model_goods->getGoodsCommonInfo(array('goods_commonid'=> $goods_commonid));
                        $order_goods[$i]['fx_commis_rate'] = $_info['fx_commis_rate'];
                        setWtCookie('fx_' . $goods_commonid, 0, -1);
                        Model('fx_goods')->delDisCart($goods_commonid, $member_id);
                    }
                    $i++;

                    //存储库存报警数据
                    if ($goods_content['goods_storage_alarm'] >= ($goods_content['goods_storage'] - $goods_content['goods_num'])) {
                        $param = array();
                        $param['common_id'] = $goods_content['goods_commonid'];
                        $param['sku_id'] = $goods_content['goods_id'];
                        $notice_list['goods_storage_alarm'][$goods_content['store_id']] = $param;
                    }
                } elseif (!empty($goods_content['bl_goods_list']) && is_array($goods_content['bl_goods_list'])) {
                    //优惠套装
                    foreach ($goods_content['bl_goods_list'] as $bl_goods_content) {
			            $bl_goods_invit=Model('goods')->getGoodsInfo(array('goods_id'=>$bl_goods_content['goods_id']));
                        $goods_commonid = $bl_goods_content['goods_commonid'];
			            $order_goods[$i]['order_id'] = $order_id;
                        $order_goods[$i]['goods_id'] = $bl_goods_content['goods_id'];
                        $order_goods[$i]['store_id'] = $store_id;
                        $order_goods[$i]['goods_name'] = $bl_goods_content['goods_name'];
                        $order_goods[$i]['goods_price'] = $bl_goods_content['bl_goods_price'];
                        $order_goods[$i]['goods_num'] = $goods_content['goods_num'];
                        $order_goods[$i]['goods_image'] = $bl_goods_content['goods_image'];
                        $order_goods[$i]['goods_spec'] = $bl_goods_content['goods_spec'];
                        $order_goods[$i]['buyer_id'] = $member_id;
                        $order_goods[$i]['goods_type'] = 4;
                        $order_goods[$i]['sales_id'] = $bl_goods_content['bl_id'];
                        $order_goods[$i]['commis_rate'] = 200;
						$order_goods[$i]['goods_serial'] = $bl_goods_invit['goods_serial'];
                        $order_goods[$i]['invite_rates'] =$bl_goods_invit['invite_rate'];
                        $order_goods[$i]['gc_id'] = $bl_goods_content['gc_id'];
                        $order_goods[$i]['goods_commonid'] = $goods_commonid;
                        $order_goods[$i]['add_time'] = TIMESTAMP;

                        //记录消费者保障服务
                        $contract_itemid_arr = $bl_goods_content['contractlist']?array_keys($bl_goods_content['contractlist']):array();
                        $order_goods[$i]['goods_contractid'] = $contract_itemid_arr?implode(',',$contract_itemid_arr):'';

                        //计算商品实际支付金额(goods_price减去分摊优惠金额后的值)
                        $goods_total = $bl_goods_content['bl_goods_price'] * $goods_content['goods_num'];
                        //计算商品成本金额
                        $goods_costprice = $goods_content['goods_costprice'] * $goods_content['goods_num'];

                        //计算本件商品优惠金额
                        $sale_value = floor($goods_total*($sale_rate)*10)/10;
                        $order_goods[$i]['goods_pay_price'] = $goods_total - $sale_value < 0 ? 0 : $goods_total - $sale_value;
                        $sale_sum += $sale_value;
                        $order_goods[$i]['is_fx'] = 0;
                        $order_goods[$i]['fx_member_id'] = 0;
                        $order_goods[$i]['fx_commis_rate'] = 0;
                        $fx_member_id = cookie('fx_'.$goods_commonid) ? cookie('fx_'.$goods_commonid) : $fx_cart_list[$goods_commonid]['fx_member_id'];
                        if (C('fenxiao_isuse') == 1 && $bl_goods_content['is_fx'] == 1 && $fx_member_id > 0 && $order_goods[$i]['goods_pay_price'] >= 1) {//分销商品佣金比例
                            $order_is_fx = 1;
                            $order_goods[$i]['is_fx'] = 1;
                            $order_goods[$i]['fx_member_id'] = $fx_member_id;
                            $_info = $model_goods->getGoodsCommonInfo(array('goods_commonid'=> $goods_commonid));
                            $order_goods[$i]['fx_commis_rate'] = $_info['fx_commis_rate'];
                            setWtCookie('fx_' . $goods_commonid, 0, -1);
                            Model('fx_goods')->delDisCart($goods_commonid, $member_id);
                        }                        
                        $i++;

                        //存储库存报警数据
                        if ($bl_goods_content['goods_storage_alarm'] >= ($bl_goods_content['goods_storage'] - $goods_content['goods_num'])) {
                            $param = array();
                            $param['common_id'] = $bl_goods_content['goods_commonid'];
                            $param['sku_id'] = $bl_goods_content['goods_id'];
                            $notice_list['goods_storage_alarm'][$bl_goods_content['store_id']] = $param;
                        }
                    }
                }      

                /***********区域代理佣金计算基数***********/ 
                if($agent_isuse == 1){//开启区域分红才计算佣金 
                    $agent_commission_amount = 0;
                    if($agent_commission == 1){//订单实付金额-运费
                        $this_amount = $order['order_amount'] - $order['shipping_fee'];                        
                        if($this_amount > 0){
                            $agent_commission_amount = $this_amount;
                        }
                    }
                    if($agent_commission == 2){//商品现价基数
                        $agent_commission_amount += $goods_total;
                    }
                    if($agent_commission == 3){//商品成本基数
                        $agent_commission_amount += $goods_costprice;
                    }
                    if($agent_commission == 4){//订单利润
                        $this_amount = $goods_total - $goods_costprice;
                        if($this_amount > 0){
                            $agent_commission_amount += $this_amount;
                        }else{
                            $agent_commission_amount += 0;
                        }
                    }

                    if($agent_level_difference){
                        //开启了极差
                        $agent_province_rate = $agent_province_rate - $agent_city_rate;
                        $agent_city_rate = $agent_city_rate - $agent_area_rate;
                    }
                    //防止参数设置有误
                    if($agent_province_rate < 0){
                        $agent_province_rate = 0;
                    }
                    if($agent_city_rate < 0){
                        $agent_city_rate = 0;
                    }
                    if($agent_area_rate < 0){
                        $agent_area_rate = 0;
                    } 
                    //$input_area_id 收货地址区域ID        
                    $area_ids = Model('area')->getTopAreaID($input_area_id);//返回类似1,2,3字符串，1为省id,2为市id,3为区id(第一个字符是','要去除)
                    $area_f = substr($area_ids,0,1); 
                    if($area_f == ','){
                        $area_ids = substr($area_ids,1,(strlen($area_ids)-1));
                    }
                    $agent_ulist = Model("member_chain")->field("member_id,agent_area_id")->where("agent_area_id in ({$area_ids})")->select();//查询当前收货地址的所有区域代理

                    $area_id_arr = explode(',', $area_ids);
                    $area_commission_arr = array();//拼装佣金数组   等级代号=>区域、佣金、人数
                    foreach($area_id_arr as $k=>$v){
                        if($k == 0){
                            $this_rate = $agent_province_rate;
                        }
                        if($k == 1){
                            $this_rate = $agent_city_rate;
                        }
                        if($k == 2){
                            $this_rate = $agent_area_rate;
                        }
                        $agent_count = 0;//当前等级代理人数统计
                        foreach ($agent_ulist as $key => $value) {
                            if($value['agent_area_id'] == $v){
                                $agent_count++;
                            }
                        }                        
                        $area_commission_arr[$k]['area_id'] = $v;
                        $area_commission_arr[$k]['commission'] = $agent_commission_amount * $this_rate * 0.01;
                        $area_commission_arr[$k]['agent_count'] = $agent_count;
                    }
                    foreach ($area_commission_arr as $k => $v) {//调整无代理区域佣金
                        if($v['agent_count'] == 0){//没有代理，区域奖金要调整
                            $now_area_commission = $area_commission_arr[$k]['commission'];
                            $area_commission_arr[$k]['commission'] = 0;
                            if($k > 0){//0是省级，省级上面没有等级了
                                $area_commission_arr[$k-1]['commission'] += $now_area_commission;//佣金划分给上一级代理
                            }
                        }
                    }
                    foreach ($area_commission_arr as $key => $value) {
                        if($value['agent_count'] > 0){
                            if($agent_average_commission == 1){//开启平均分红
                                $t_commission = $value['commission'] / $value['agent_count'];
                                $ta_commission = sprintf("%.2f",substr(sprintf("%.3f", $t_commission), 0, -1));
                            }else{
                                $ta_commission = $value['commission'];//没开启平均分红，那么代理各自独立
                            }
                            $area_commission_arr[$key]['ta_commission'] = $ta_commission;
                        }
                    }
                    //拼装代理佣金数据  最终以agent_commission_list来发放奖金
                    $agent_commission_list = array();//奖金列表
                    $agent_commission_uids = '';
                    foreach($agent_ulist as $k=>$v){
                        $agent_commission_uids .= $v['member_id'].',';
                        $u_c_arr = array(
                            'area_id' => $v['agent_area_id'],
                            'commission' => 0,
                            'agent_count' => 0,
                            'ta_commission' => 0
                        );
                        foreach ($area_commission_arr as $key => $value) {
                            if($v['agent_area_id'] == $value['area_id']){
                                $u_c_arr['commission'] = $value['commission'];
                                $u_c_arr['agent_count'] = $value['agent_count'];
                                $u_c_arr['ta_commission'] = $value['ta_commission'];
                            }
                        }
                        $agent_commission_list[$v['member_id']] = $u_c_arr;
                    }
                    if(!empty($agent_commission_uids)){
                        $agent_commission_uids = rtrim($agent_commission_uids,',');
                    }
                }

                /***********区域代理佣金计算基数***********/ 

                /***********统计三级分销佣金计算基数***** 出现高并发，这部分数据写入缓存，待服务器空闲执行插入（使用定时任务）******/  
                if($distribution_isuse == 1){//开启分销功能才计算佣金   
                    $commission_amount = 0;                     
                    if($distribution_commission == 1){//订单实付金额-运费
                        $this_amount = $order['order_amount'] - $order['shipping_fee'];
                        if($this_amount > 0){
                            $commission_amount = $this_amount;
                        }
                    }
                    if($distribution_commission == 2){//商品现价基数
                        $commission_amount += $goods_total;
                    }
                    if($distribution_commission == 3){//商品成本基数
                        $commission_amount += $goods_costprice;
                    }
                    if($distribution_commission == 4){//订单利润
                        $this_amount = $goods_total - $goods_costprice;
                        if($this_amount > 0){
                            $commission_amount += $this_amount;
                        }else{
                            $commission_amount += 0;
                        }
                    }
                    $first_level_commission = 0;//订单一级分销佣金
                    $first_level_uid = 0;//订单一级分销会员
                    $second_level_commission = 0;//订单二级分销佣金
                    $second_level_uid = 0;//订单二级分销会员
                    $third_level_commission = 0;//订单三级分销佣金
                    $third_level_uid = 0;//订单三级分销会员
                    if($goods_content['is_independent_bonus'] == 1){ //商品具备独立奖金设置
                        $goods_commission = unserialize($goods_content['level_commission']);//取出商品的三级分销奖金设置                        
                        foreach($superior_list as $k=>$v){
                            if($v['distribution_level'] > 0){//上级具备分销资格才计算佣金
                                $this_commission = $goods_commission[$v['distribution_level']];
                                //因为之前已经做了倒序处理，所以k值代表层级0为一级，1为二级，2为三级
                                if($k == 0){//一级
                                    if($this_commission['first_level_rate'] > 0){
                                        $t_commission = $commission_amount * $this_commission['first_level_rate'] * 0.01;
                                        $first_level_commission += sprintf("%.2f",substr(sprintf("%.3f", $t_commission), 0, -1));//平台利润最大化，不四舍五入
                                    }else{
                                        $first_level_commission += sprintf("%.2f",substr(sprintf("%.3f", $this_commission['first_level_money']), 0, -1));
                                    }
                                    $first_level_uid = $v['member_id'];
                                }
                                if($k == 1){//二级
                                    if($this_commission['second_level_rate'] > 0){
                                        $t_commission = $commission_amount * $this_commission['second_level_rate'] * 0.01;
                                        $second_level_commission += sprintf("%.2f",substr(sprintf("%.3f", $t_commission), 0, -1));
                                    }else{
                                        $second_level_commission += sprintf("%.2f",substr(sprintf("%.3f", $this_commission['second_level_money']), 0, -1));
                                    }
                                    $second_level_uid = $v['member_id'];
                                }
                                if($k == 2){//三级
                                    if($this_commission['third_level_rate'] > 0){
                                        $t_commission = $commission_amount * $this_commission['third_level_rate'] * 0.01;
                                        $third_level_commission += sprintf("%.2f",substr(sprintf("%.3f", $t_commission), 0, -1));
                                    }else{
                                        $third_level_commission += sprintf("%.2f",substr(sprintf("%.3f", $this_commission['third_level_money']), 0, -1));
                                    }
                                    $third_level_uid = $v['member_id'];
                                }
                            }else{
                                if($k == 0){
                                    $first_level_uid = $v['member_id'];
                                }
                                if($k == 1){
                                    $second_level_uid = $v['member_id'];
                                }
                                if($k == 2){
                                    $third_level_uid = $v['member_id'];
                                }
                            }
                        }
                    }else{//商品不具备独立佣金，取分销等级表中的来计算                            
                        foreach($superior_list as $k=>$v){                            
                            if($v['distribution_level'] > 0){//上级具备分销资格才计算佣金
                                $condition = "id = ".$v['distribution_level'];
                                $level_array = Model('distribution_level')->field('layer_one,layer_two,layer_three')->where($condition)->find();
                                //因为之前已经做了倒序处理，所以k值代表层级0为一级，1为二级，2为三级
                                if($k == 0){//一级
                                    if($level_array['layer_one'] > 0){
                                        $t_commission = $commission_amount * $level_array['layer_one'] * 0.01;
                                        $first_level_commission += sprintf("%.2f",substr(sprintf("%.3f", $t_commission), 0, -1)); 
                                    }else{
                                        //如果等级没有设置佣金比例，取公共默认比例
                                        $t_commission = $commission_amount * $distribution_level_one * 0.01;
                                        $first_level_commission += sprintf("%.2f",substr(sprintf("%.3f", $t_commission), 0, -1));
                                    }
                                    $first_level_uid = $v['member_id'];
                                }
                                if($k == 1){//二级
                                    if($level_array['layer_two'] > 0){
                                        $t_commission = $commission_amount * $level_array['layer_two'] * 0.01;
                                        $second_level_commission += sprintf("%.2f",substr(sprintf("%.3f", $t_commission), 0, -1)); 
                                    }else{
                                        //如果等级没有设置佣金比例，取公共默认比例
                                        $t_commission = $commission_amount * $distribution_level_two * 0.01;
                                        $second_level_commission += sprintf("%.2f",substr(sprintf("%.3f", $t_commission), 0, -1));
                                    }
                                    $second_level_uid = $v['member_id'];
                                }
                                if($k == 2){//三级
                                    if($level_array['layer_three'] > 0){
                                        $t_commission = $commission_amount * $level_array['layer_three'] * 0.01;
                                        $third_level_commission += sprintf("%.2f",substr(sprintf("%.3f", $t_commission), 0, -1)); 
                                    }else{
                                        //如果等级没有设置佣金比例，取公共默认比例
                                        $t_commission = $commission_amount * $distribution_level_three * 0.01;
                                        $third_level_commission += sprintf("%.2f",substr(sprintf("%.3f", $t_commission), 0, -1));
                                    }
                                    $third_level_uid = $v['member_id'];
                                }
                            }else{
                                if($k == 0){
                                    $first_level_uid = $v['member_id'];
                                }
                                if($k == 1){
                                    $second_level_uid = $v['member_id'];
                                }
                                if($k == 2){
                                    $third_level_uid = $v['member_id'];
                                }
                            }
                        }
                    }
                } 
                /***********统计三级分销佣金计算基数***********/

                /************团队无限级佣金计算**************/
                if($team_isuse == 1){//开启分销功能才计算佣金
                    /*//基础设置
                    $team_isuse = $setting_infos['team_isuse'];//是否开启团队无限级
                    $team_same_isuse = $setting_infos['team_same_isuse'];//开启平级超越奖
                    $team_same_calculation = $setting_infos['team_same_calculation'];//平级奖计算方式
                    $team_include_self = $setting_infos['team_include_self'];//团队统计是否包括自己
                    //结算设置
                    $team_billing_option = $setting_infos['team_billing_option'];//佣金计算去向
                    $team_settlement_event = $setting_infos['team_settlement_event'];//结算事件
                    $team_settlement_days = $setting_infos['team_settlement_days'];//结算天数
                    $team_commission = $setting_infos['team_commission'];//佣金计算项*/
                    $team_commission_amount = 0;
                    if($team_commission == 1){//订单实付金额-运费
                        $this_amount = $order['order_amount'] - $order['shipping_fee'];
                        if($this_amount > 0){
                            $team_commission_amount = $this_amount;
                        }
                    }
                    if($team_commission == 2){//商品现价基数
                        $team_commission_amount += $goods_total;
                    }
                    if($team_commission == 3){//商品成本基数
                        $team_commission_amount += $goods_costprice;
                    }
                    if($team_commission == 4){//订单利润
                        $this_amount = $goods_total - $goods_costprice;
                        if($this_amount > 0){
                            $team_commission_amount += $this_amount;
                        }else{
                            $team_commission_amount += 0;
                        }
                    }
                    $commission_list = array();//奖金列表
                    $comm_layer_count = 1;//提成层数统计
                    $max_team_commission = 0;//团队最大佣金
                    $max_team_uid = 0;//团队最大佣金标记会员ID
                    $last_same_team_commission = 0;
                    //整理出具备团队身份的会员数据
                    foreach($team_superior_list as $k=>$v){                     
                        $comm_arr = array();    
                        $comm_arr['team_layer_count'] = $comm_layer_count;//提成层数
                        if($v['team_level'] > 0){//上级具备团队资格才计算佣金
                                $condition = "id = ".$v['team_level'];
                                $level_array = Model('team_level')->field('layer_rate,commission_layers,same_layers,same_layer_rate')->where($condition)->find();//这里要进行数据的读取，然后进行计算，明天这里开始
                                $this_team_commission = $team_commission_amount * $level_array['layer_rate'] * 0.01;
                                if($max_team_commission < $this_team_commission){
                                    $max_team_commission = $this_team_commission;//记录最大的佣金值
                                    $max_team_uid = $v['member_id'];//记录最大的佣金值对应的用户ID
                                }
                                $comm_arr['team_commission'] = sprintf("%.2f",substr(sprintf("%.3f", $this_team_commission), 0, -1));
                                $comm_arr['commission_layers'] = $level_array['commission_layers'];
                                if($team_same_isuse == 1){//开启了平级超越奖
                                    $comm_arr['same_team_commission'] = sprintf("%.2f",substr(sprintf("%.3f", $team_commission_amount * $level_array['same_layer_rate'] * 0.01), 0, -1));
                                    $comm_arr['same_layers'] = $level_array['same_layers'];
                                }else{
                                    $comm_arr['same_team_commission'] = 0;
                                    $comm_arr['same_layers'] = 0;
                                }

                        }else{
                            $comm_arr['team_commission'] = 0;//没身份奖金为0
                            $comm_arr['commission_layers'] = 0;
                            $comm_arr['same_team_commission'] = 0;
                            $comm_arr['same_layers'] = 0;
                        }
                        $comm_arr['team_level'] = $v['team_level'];
                        if($comm_arr['team_commission'] > 0){
                            if($team_same_calculation == 1){//如果开启以下级团队奖励为基数计算，那么这里要改写平级奖金额
                                if($last_same_team_commission > 0){//如果为0 那么说明是第一级的平级奖 不需要经过下面计算
                                    $comm_arr['same_team_commission'] = sprintf("%.2f",substr(sprintf("%.3f", $last_same_team_commission * $level_array['same_layer_rate'] * 0.01), 0, -1));
                                }
                            }
                            $commission_list[$v['member_id']] = $comm_arr;
                            $last_same_team_commission = $comm_arr['same_team_commission'];
                        }
                        $comm_layer_count++;
                    }
                    //计算极差，平级等奖励
                    $team_ylj_commission = 0;//已经累计的佣金                    
                    $same_layer_count = 1;//平级层数统计
                    $commission_uids = '';
                    foreach($commission_list as $k=>$v){
                        $commission_uids .= $k.',';
                        $this_commission = $v['team_commission'] - $team_ylj_commission;//计算当前应该领的佣金
                        if($v['team_commission'] == $max_commission){//如果当前佣金等于最大佣金，那么可能是标记会员
                            if($k == $max_uid){//如果是标记会员   那么平级奖不允许出现
                                $commission_list[$k]['team_commission'] = sprintf("%.2f",substr(sprintf("%.3f", $this_commission), 0, -1));
                                $commission_list[$k]['same_team_commission'] = 0;         
                            }else{//如果不是标记会员，那么只能领平级奖
                                $commission_list[$k]['team_commission'] = 0;  
                                $commission_list[$k]['same_layer_count'] = $same_layer_count;  //平级超越奖才有该字段
                                $same_layer_count++;
                            }
                        }else{
                            if($this_commission > 0){//如果当前可领佣金大于0 那么当前会员不允许领平级奖
                                $commission_list[$k]['team_commission'] = sprintf("%.2f",substr(sprintf("%.3f", $this_commission), 0, -1));
                                $commission_list[$k]['same_team_commission'] = 0;
                            }else{//如果当前可领佣金小于0，那么当前会员只能领平级奖
                                $commission_list[$k]['team_commission'] = 0; 
                                $commission_list[$k]['same_layer_count'] = $same_layer_count;  //平级超越奖才有该字段
                                $same_layer_count++;
                            }
                        }
                        if($this_commission > 0){//当前可领佣金大于0才累计
                            $team_ylj_commission += $this_commission;
                        }
                    }
                    if(!empty($commission_uids)){
                        $commission_uids = rtrim($commission_uids,',');
                    }
                }
                /************团队无限级佣金计算**************/
				
				/************单品消费返现**************/
				//预加载单品返现配置
				$buy_return_isuse = $setting_infos['buy_return_isuse'];//开关
				$buy_return_type = $setting_infos['buy_return_type'];//返现方式
				$buy_return_commission = $setting_infos['buy_return_commission'];//选择返现计算基数
				$buy_return_rate = $setting_infos['buy_return_rate'];//默认单品返现比例
				$buy_return_each_rate = $setting_infos['buy_return_each_rate'];//每期返现比例
				$buy_return_settlement_days = $setting_infos['buy_return_settlement_days'];//结算天数
				$buy_return_settlement_event = $setting_infos['buy_return_settlement_event'];//结算事件
				
				//如果开启了 开关
				if($buy_return_isuse == 1){
					if($buy_return_commission == 1){//订单实付金额-运费
						$this_amount = $order['order_amount'] - $order['shipping_fee'];
						$buy_commission_amount = 0;
						if($this_amount > 0){
							$buy_commission_amount = $this_amount;
						}
					}
					if($buy_return_commission == 2){//商品现价基数
						$buy_commission_amount += $goods_total;
					}
					if($buy_return_commission == 3){//商品成本基数
						$buy_commission_amount += $goods_costprice;
					}
					if($buy_return_commission == 4){//订单利润
						$this_amount = $goods_total - $goods_costprice;
						if($this_amount > 0){
							$buy_commission_amount += $this_amount;
						}else{
							$buy_commission_amount += 0;
						}
					}
					
					$buy_commission_amount = round(($buy_commission_amount * $buy_return_rate * 0.01),2);
					
					$buy_return_data = array(
						'store_id' => $store_id,
						'order_id' => $order_id,
						'ordersn' => $order['order_sn'],
						'uid' => $member_id,
						'order_money' => $order['order_amount'],
						'total_commission' => $buy_commission_amount,
						'each_return_rate' => $buy_return_each_rate,
						'commission_type' => $buy_return_commission,
						'return_type' => $buy_return_type,
						'pay_commission' => 0,
						'balance_commission' => $buy_commission_amount,
						'createtime' => strtotime(date('Y-m-d 08:00:00',time() + ($buy_return_settlement_days*86400))),
						'updatetime' => strtotime(date('Y-m-d 08:00:00',time() + ($buy_return_settlement_days*86400))),
						'status' => 1,
					);
					
					if($buy_return_settlement_event == 1){
						$buy_return_data['createtime'] = 0;
						$buy_return_data['updatetime'] = 0;
						$buy_return_data['status'] = 0;
					}
					
					$do_result = Model('buy_return')->insert($buy_return_data);
					
				}
				
				/************单品消费返现**************/
				
            }            

            /*************保存三级分销佣金***********/
            if($distribution_isuse == 1){//开启分销功能才计算佣金         
                $distribution_order['order_id'] = $order_id;
                $distribution_order['buyer_id'] = $member_id;
                $distribution_order['commission_amount'] = $commission_amount;
                $distribution_order['commission_type'] = $distribution_commission;
                $distribution_order['store_id'] = $store_id;
                $distribution_order['distribution_uselevel'] = $distribution_uselevel;
                $distribution_order['commission_one_level'] = $first_level_commission;
                $distribution_order['commission_one_uid'] = $first_level_uid;
                $distribution_order['commission_two_level'] = $second_level_commission;
                $distribution_order['commission_two_uid'] = $second_level_uid;
                $distribution_order['commission_three_level'] = $third_level_commission;
                $distribution_order['commission_three_uid'] = $third_level_uid;
                $distribution_order['status'] = 0;                  //佣金状态（0：未发放；1：已发放；2：已扣除）
                $distribution_order['order_time'] = $addtime;       //同步订单的创建时间
                $distribution_order['create_time'] = TIMESTAMP;

                $do_result = Model('distribution_order')->insert($distribution_order);//插入订单产生的佣金记录

            } 
            /*************保存三级分销佣金***********/

            /************保存团队无限级佣金**************/
            if($team_isuse == 1 && !empty($commission_list)){//团队无限级开启 并且这笔订单产生了团队佣金
                $team_order['order_id'] = $order_id;
                $team_order['buyer_id'] = $member_id;
                $team_order['commission_amount'] = $team_commission_amount;
                $team_order['commission_type'] = $team_commission;
                $team_order['calculation_type'] = $team_same_calculation;
                $team_order['commission_list'] = serialize($commission_list);//序列化存储，到时候发放的时候取出来反序列化循环发放即可
                $team_order['commission_uid'] = $commission_uids;//所有能对该笔订单抽佣的用户集合
                $team_order['store_id'] = $store_id;
                $team_order['status'] = 0;                  //佣金状态（0：未发放；1：已发放；2：已扣除）
                $team_order['order_time'] = $addtime;       //同步订单的创建时间
                $team_order['create_time'] = TIMESTAMP;

                $to_result = Model('team_order')->insert($team_order);//插入订单产生的佣金记录
            }
            /************保存团队无限级佣金**************/

            /************保存区域代理佣金**************/            
            if($agent_isuse == 1 && !empty($agent_commission_list)){//团队无限级开启 并且这笔订单产生了团队佣金
                $agent_order['order_id'] = $order_id;
                $agent_order['buyer_id'] = $member_id;
                $agent_order['commission_amount'] = $agent_commission_amount;
                $agent_order['commission_type'] = $agent_commission;
                $agent_order['is_difference'] = $agent_level_difference;
                $agent_order['is_average'] = $agent_average_commission;
                $agent_order['commission_list'] = serialize($agent_commission_list);//序列化存储，到时候发放的时候取出来反序列化循环发放即可
                $agent_order['commission_uid'] = $agent_commission_uids;//所有能对该笔订单抽佣的用户集合
                $agent_order['store_id'] = $store_id;
                $agent_order['status'] = 0;                  //佣金状态（0：未发放；1：已发放；2：已扣除）
                $agent_order['order_time'] = $addtime;       //同步订单的创建时间
                $agent_order['create_time'] = TIMESTAMP;

                $to_result = Model('agent_order')->insert($agent_order);//插入订单产生的佣金记录
            }
            /************保存区域代理佣金**************/

            // 加价购换购商品
            foreach ((array) $jjgValidStoreSkus[$store_id] as $goods_content) {
                if ($goods_content['storage'] < 1) {
                    throw new Exception('抱歉，部分商品存在下架或库存不足的情况，请重新选择');
                }
				$ml_goods_invit=Model('goods')->getGoodsInfo(array('goods_id'=>$goods_content['id']));

                $order_goods[$i]['order_id'] = $order_id;
                $order_goods[$i]['goods_id'] = $goods_content['id'];
                $order_goods[$i]['store_id'] = $store_id;
                $order_goods[$i]['goods_name'] = $goods_content['name'];
                $order_goods[$i]['goods_price'] = $goods_content['jjgPrice'];
                $order_goods[$i]['goods_num'] = 1;
                $order_goods[$i]['goods_image'] = $goods_content['goods_image'];
                $order_goods[$i]['goods_spec'] = $goods_content['goods_spec'];
                $order_goods[$i]['buyer_id'] = $member_id;
                // 加价购活动换购商品
                $order_goods[$i]['goods_type'] = 9;
                $order_goods[$i]['sales_id'] = $goods_content['jjgId'];

                $order_goods[$i]['commis_rate'] = 200;
				$order_goods[$i]['goods_serial'] = $ml_goods_invit['goods_serial'];
				$order_goods[$i]['invite_rates'] =$ml_goods_invit['invite_rate'];
                $order_goods[$i]['gc_id'] = $goods_content['gc_id'];

                //记录消费者保障服务
                $contract_itemid_arr = $goods_content['contractlist']?array_keys($goods_content['contractlist']):array();
                $order_goods[$i]['goods_contractid'] = $contract_itemid_arr?implode(',',$contract_itemid_arr):'';

                //计算商品金额
                $goods_total = $goods_content['jjgPrice'] * 1;
                //计算本件商品优惠金额
                $sale_value = floor($goods_total*($sale_rate)*10)/10;
                $order_goods[$i]['goods_pay_price'] = $goods_total - $sale_value < 0 ? 0 : $goods_total - $sale_value;
                $sale_sum += $sale_value;
                $order_goods[$i]['is_fx'] = 0;
                $order_goods[$i]['fx_member_id'] = 0;
                $order_goods[$i]['fx_commis_rate'] = 0;
                $i++;

                //存储库存报警数据
                if ($goods_content['goods_storage_alarm'] >= ($goods_content['goods_storage'] - 1)) {
                    $param = array();
                    $param['common_id'] = $goods_content['goods_commonid'];
                    $param['sku_id'] = $goods_content['id'];
                    $notice_list['goods_storage_alarm'][$goods_content['store_id']] = $param;
                }

            }

            //将因舍出小数部分出现的差值补到最后一个商品的实际成交价中(商品goods_price=0时不给补，可能是赠品)
            if ($sale_total > $sale_sum) {
                $i--;
                for($i;$i>=0;$i--) {
                    if (floatval($order_goods[$i]['goods_price']) > 0) {
                        $order_goods[$i]['goods_pay_price'] -= $sale_total - $sale_sum;
                        break;
                    }
                }
            }
            $insert = $model_order->addOrderGoods($order_goods);
            if (!$insert) {
                throw new Exception('订单保存失败[未生成商品数据]');
            }
            if ($order_is_fx) {
                $model_order->editOrder(array('is_fx'=> $order_is_fx),array('order_id'=> $order_id));
            }

            //存储商家发货提醒数据
            if ($store_pay_type_list[$store_id] == 'offline') {
                $notice_list['new_order'][$order['store_id']] = array('order_sn' => $order['order_sn']);
            }
            if ($order['order_type'] == 4) {//拼团订单
                $model_pingou = Model('p_pingou');
                $_array = array();
                $_array['order_id'] = $order_id;
                $_array['order_sn'] = $order['order_sn'];
                $_array['store_id'] = $order['store_id'];
                $_array['buyer_id'] = $order['buyer_id'];
                $_array['buyer_name'] = $order['buyer_name'];
                $_array['add_time'] = TIMESTAMP;
                $_array['buyer_type'] = 0;
                $_array['goods_id'] = $goods_list[0]['goods_id'];
                $_array['goods_commonid'] = $goods_list[0]['goods_commonid'];
                $_array['min_num'] = $goods_list[0]['pingou_info']['min_num'];
                $pingou_end_time = $goods_list[0]['pingou_info']['end_time'];
                $_end = TIMESTAMP+60*60*24*PINGOU_ORDER_SUCCESS_MAX_TIME;
                if ($_end > $pingou_end_time) $_end = $pingou_end_time;
                if ($_POST['log_id']) {
                    $condition = array();
                    $condition['log_id'] = intval($_POST['log_id']);
                    $condition['buyer_id'] = intval($_POST['buyer_id']);
                    $condition['lock_state'] = 1;//未成团
                    $condition['pay_time'] = array('gt',0);
                    $condition['end_time'] = array('gt',TIMESTAMP);//没结束的可以参团
                    $_info = $model_pingou->getOrderInfo($condition);
                    if (!empty($_info) && is_array($_info)) {
                        $_end = $_info['end_time'];
                        $_array['buyer_type'] = $_info['buyer_type'] ? $_info['buyer_type'] : $_info['log_id'];
                    }
                }
                $_array['end_time'] = $_end;
                
				$_array['goods_num'] = $goods_list[0]['goods_num'];
				$_array['pingou_id'] = $goods_list[0]['pingou_info']['pingou_id'];
				
                $model_pingou->addOrder($_array);
            }
        }

        //保存数据
        $this->_order_data['pay_sn'] = $pay_sn;
        $this->_order_data['order_list'] = $order_list;
        $this->_order_data['notice_list'] = $notice_list;
        $this->_order_data['ifrobbuy'] = $ifrobbuy;
        $this->_order_data['ifbook'] = $goods_list[0]['is_book'] == 1 ;
    }

    /**
     * 充值卡、预存款支付
     *
     */
    private function _createOrderStep5() {
		
		$order_list = $this->_handle_buy_action->pointsPlay($this->_order_data['order_list'], $this->_post_data['J_PointInput'], $this->_member_info['member_id']);
        if (empty($this->_post_data['password'])) return ;
        $buyer_info = Model('member')->getMemberInfoByID($this->_member_info['member_id']);
        if ($buyer_info['member_paypwd'] == '' || $buyer_info['member_paypwd'] != md5($this->_post_data['password'])) return ;

        //使用充值卡支付
        if (!empty($this->_post_data['rcb_pay'])) {
            $order_list = $this->_handle_buy_action->rcbPay($this->_order_data['order_list'], $this->_post_data, $buyer_info);
        }

        //使用预存款支付
        if (!empty($this->_post_data['pd_pay'])) {
            $this->_handle_buy_action->pdPay($order_list ? $order_list :$this->_order_data['order_list'], $this->_post_data, $buyer_info);
        }
    }

    /**
     * 订单后续其它处理
     *
     */
    private function _createOrderStep6() {
        $ifcart = $this->_post_data['ifcart'];
        $goods_buy_quantity = $this->_order_data['goods_buy_quantity'];
        $input_voucher_list = $this->_order_data['input_voucher_list'];
        $input_rpt_info = $this->_order_data['input_rpt_info'];

        $store_cart_list = $this->_order_data['store_cart_list'];
        $input_buy_items = $this->_order_data['input_buy_items'];
        $order_list = $this->_order_data['order_list'];
        $input_address_info = $this->_order_data['input_address_info'];
        $notice_list = $this->_order_data['notice_list'];
        $fc_id = $this->_order_data['fc_id'];
        $ifrobbuy = $this->_order_data['ifrobbuy'];
        $ifbook = $this->_order_data['ifbook'];
        $pay_sn = $this->_order_data['pay_sn'];
        $input_dlyp_id = $this->_order_data['input_dlyp_id'];
        $input_chain_id = $this->_order_data['input_chain_id'];

        $goods_sale = $this->_order_data['goods_sale'];

        //变更库存和销量
        $result = Handle('queue')->createOrderUpdateStorage($goods_buy_quantity, $goods_sale);
        if (!$result['state']) {
            throw new Exception('订单保存失败[变更库存销量失败]');
        }

        //门店自提订单减存
        if ($input_chain_id) {
            $result = Handle('queue')->createOrderUpdateChainStorage($goods_buy_quantity,$input_chain_id);
            if (!$result['state']) {
                throw new Exception('订单保存失败[变更自提门店库存销量失败]');
            }
        }

        //更新使用的代金券状态
        if (!empty($input_voucher_list) && is_array($input_voucher_list)) {
            $result = Handle('queue')->editVoucherState($input_voucher_list);
            if (!$result['state']) {
                throw new Exception('订单保存失败[代金券处理失败]');
            }
        }

        //更新使用的平台优惠券状态
        if (!empty($input_rpt_info) && is_array($input_rpt_info)) {
            $result = Handle('queue')->editRptState($input_rpt_info,$pay_sn);
            if (!$result['state']) {
                throw new Exception('订单保存失败[平台优惠券处理失败]');
            }
        }

        //更新F码使用状态
        if ($fc_id) {
            $result = Handle('queue')->updateGoodsFCode($fc_id);
            if (!$result['state']) {
                throw new Exception('订单保存失败[F码处理失败]');
            }
        }

        //更新抢购购买人数和数量
        if ($ifrobbuy) {
            foreach ($store_cart_list as $goods_list) {
                foreach ($goods_list as $goods_content) {
                    if ($goods_content['ifrobbuy'] && $goods_content['robbuy_id']) {
                        $robbuy_info = array();
                        $robbuy_info['robbuy_id'] = $goods_content['robbuy_id'];
                        $robbuy_info['quantity'] = $goods_content['goods_num'];
                        QueueClient::push('editRobbuySaleCount', $robbuy_info);
                    }
                }
            }
        }

        //增加预定时段记录
        if ($ifbook) {
            Handle('order_book')->buy($this->_order_data,$this->_post_data);
        }

        //删除购物车中的商品
        $this->delCart($ifcart,$this->_member_info['member_id'],array_keys($input_buy_items));
        @setWtCookie('cart_goods_num','',-3600);

        //保存订单自提点信息
        if ($input_dlyp_id) {
            $data = array();
            $data['mob_phone'] = $input_address_info['mob_phone'];
            $data['tel_phone'] = $input_address_info['tel_phone'];
            $data['reciver_name'] = $input_address_info['true_name'];
            $data['dlyp_id'] = $input_address_info['dlyp_id'];
            foreach ($order_list as $v) {
                $data['order_sn_list'][$v['order_id']]['order_sn'] = $v['order_sn'];
                $data['order_sn_list'][$v['order_id']]['add_time'] = $v['add_time'];
            }
            QueueClient::push('saveDeliveryOrder', $data);
        }

        //发送提醒类信息
        if (!empty($notice_list)) {
            foreach ($notice_list as $code => $value) {
                QueueClient::push('sendStoreMsg', array('code' => $code, 'store_id' => key($value), 'param' => current($value)));
            }
        }

        //门店自提发送提货码
        if ($input_chain_id) {
            $order_info = current($order_list);
            if ($order_info['payment_code'] == 'chain') {
                $_code = rand(100000,999999);
                $result = Model('order')->editOrder(array('chain_code'=>$_code),array('order_id'=>$order_info['order_id']));
                if (!$result) {
                    throw new Exception('门店自提订单更新提货码失败');
                }
                $param = array();
                $param['chain_code'] = $_code;
                $param['order_sn'] = $order_info['order_sn'];
                $param['buyer_phone'] = $order_info['buyer_phone'];
                QueueClient::push('sendChainCode', $param);                
            }
        }

        //生成交易快照
        $order_id_list = array();
        foreach ($order_list as $order_info) {
            $order_id_list[] = $order_info['order_id'];
        }
        QueueClient::push('createSphot', $order_id_list);

    }

    /**
     * 加密
     * @param array/string $string
     * @param int $member_id
     * @return mixed arrray/string
     */
    public function buyEncrypt($string, $member_id) {
        $buy_key = sha1(md5($member_id.'&'.MD5_KEY));
        if (is_array($string)) {
            $string = serialize($string);
        } else {
            $string = strval($string);
        }
        return encrypt(base64_encode($string), $buy_key);
    }

    /**
     * 解密
     * @param string $string
     * @param int $member_id
     * @param number $ttl
     */
    public function buyDecrypt($string, $member_id, $ttl = 0) {
        $buy_key = sha1(md5($member_id.'&'.MD5_KEY));
        if (empty($string)) return;
        $string = base64_decode(decrypt(strval($string), $buy_key, $ttl));
        return ($tmp = @unserialize($string)) !== false ? $tmp : $string;
    }

    /**
     * 得到所购买的id和数量
     *
     */
    private function _parseItems($cart_id) {
        //存放所购商品ID和数量组成的键值对
        $buy_items = array();
        if (is_array($cart_id)) {
            foreach ($cart_id as $value) {
                if (preg_match_all('/^(\d{1,10})\|(\d{1,6})$/', $value, $match)) {
                    if (intval($match[2][0]) > 0) {
                        $buy_items[$match[1][0]] = $match[2][0];
                    }
                }
            }
        }
        return $buy_items;
    }

    /**
     * 从购物车数组中得到商品列表
     * @param unknown $cart_list
     */
    private function _getGoodsList($cart_list) {
        if (empty($cart_list) || !is_array($cart_list)) return $cart_list;
        $goods_list = array();
        $i = 0;
        foreach ($cart_list as $key => $cart) {
            if (!$cart['state'] || !$cart['storage_state']) continue;
            //购买数量
            $quantity = $cart['goods_num'];
            if (!intval($cart['bl_id'])) {
                //如果是普通商品
                $goods_list[$i]['goods_num'] = $quantity;
                $goods_list[$i]['goods_id'] = $cart['goods_id'];
                $goods_list[$i]['store_id'] = $cart['store_id'];
                $goods_list[$i]['gc_id'] = $cart['gc_id'];
                $goods_list[$i]['goods_name'] = $cart['goods_name'];
                $goods_list[$i]['goods_price'] = $cart['goods_price'];
                $goods_list[$i]['store_name'] = $cart['store_name'];
                $goods_list[$i]['goods_image'] = $cart['goods_image'];
                $goods_list[$i]['transport_id'] = $cart['transport_id'];
                $goods_list[$i]['goods_freight'] = $cart['goods_freight'];
                $goods_list[$i]['goods_trans_v'] = $cart['goods_trans_v'];
                $goods_list[$i]['goods_vat'] = $cart['goods_vat'];
                $goods_list[$i]['is_fcode'] = $cart['is_fcode'];
                $goods_list[$i]['bl_id'] = 0;
                $i++;
            } else {
                //如果是优惠套装商品
                foreach ($cart['bl_goods_list'] as $bl_goods) {
                    $goods_list[$i]['goods_num'] = $quantity;
                    $goods_list[$i]['goods_id'] = $bl_goods['goods_id'];
                    $goods_list[$i]['store_id'] = $cart['store_id'];
                    $goods_list[$i]['gc_id'] = $bl_goods['gc_id'];
                    $goods_list[$i]['goods_name'] = $bl_goods['goods_name'];
                    $goods_list[$i]['goods_price'] = $bl_goods['goods_price'];
                    $goods_list[$i]['store_name'] = $bl_goods['store_name'];
                    $goods_list[$i]['goods_image'] = $bl_goods['goods_image'];
                    $goods_list[$i]['transport_id'] = $bl_goods['transport_id'];
                    $goods_list[$i]['goods_freight'] = $bl_goods['goods_freight'];
                    $goods_list[$i]['goods_vat'] = $bl_goods['goods_vat'];
                    $goods_list[$i]['bl_id'] = $cart['bl_id'];
                    $i++;
                }
            }
        }
        return $goods_list;
    }

    /**
     * 将下单商品列表转换为以店铺ID为下标的数组
     *
     * @param array $cart_list
     * @return array
     */
    private function _getStoreCartList($cart_list) {
        if (empty($cart_list) || !is_array($cart_list)) return $cart_list;
        $new_array = array();
        foreach ($cart_list as $cart) {
            $new_array[$cart['store_id']][] = $cart;
        }
        return $new_array;
    }

    /**
     * 本次下单是否需要码及F码合法性
     * 无需使用F码，返回 true
     * 需要使用F码，返回($fc_id/false)
     */
    private function _checkFcode($goods_list, $fcode) {
        foreach ($goods_list as $k => $v) {
            if ($v['is_fcode'] == 1) {
                $is_fcode = true; break;
            }
        }
        if (!$is_fcode) return true;
        if (empty($fcode) || count($goods_list) > 1) {
            return false;
        }
        $goods_content = $goods_list[0];
        $fcode_info = $this->checkFcode($goods_content['goods_id'],$fcode);
        if ($fcode_info['state']) {
            return intval($fcode_info['data']['fc_id']);
        } else {
            return false;
        }
    }

    /**
     * 验证商品是否支持自提
     * @param unknown $goods_list
     * @return boolean
     */
    private function _checkChain($goods_list) {
        if (empty($goods_list) || !is_array($goods_list)) return false;
        $_flag = true;
        foreach ($goods_list as $goods_content) {
            if (!$goods_content['is_chain']) {
                $_flag = false;
                break;
            }
        }
        return $_flag;
    }
}
