<?php
/**
 * 我的购物车
 *
 *

 * @license    http://www.s h opwt.c om
 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class member_cartControl extends mobileMemberControl {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 购物车列表
     */
    public function cart_listWt() {
        $model_cart = Model('cart');

        $condition = array('buyer_id' => $this->member_info['member_id']);
        $cart_list  = $model_cart->listCart('db', $condition);

        // 购物车列表 [得到最新商品属性及促销信息]
        $handle_buy_action = handle('buy_action');
        $cart_list = $handle_buy_action->getGoodsCartList($cart_list);

        //购物车商品以店铺ID分组显示,并计算商品小计,店铺小计与总价由JS计算得出
        $store_cart_list = array();
        $sum = 0;
        foreach ($cart_list as $cart) {
            if (!empty($cart['gift_list'])) {
                foreach ($cart['gift_list'] as $key => $val) {
                    $cart['gift_list'][$key]['goods_image_url'] = cthumb($val['gift_goodsimage'], $cart['store_id']);
                }
                $cart['gift_list'] = array_values($cart['gift_list']);
            }
            $store_cart_list[$cart['store_id']]['store_id'] = $cart['store_id'];
            $store_cart_list[$cart['store_id']]['store_name'] = $cart['store_name'];
            $cart['goods_image_url'] = cthumb($cart['goods_image'], $cart['store_id']);
            $cart['goods_total'] = wtPriceFormat($cart['goods_price'] * $cart['goods_num']);
            $cart['xianshi_info'] = $cart['xianshi_info'] ? $cart['xianshi_info'] : array();
            $cart['robbuy_info'] = $cart['robbuy_info'] ? $cart['robbuy_info'] : array();
            $store_cart_list[$cart['store_id']]['goods'][] = $cart;
            $sum += $cart['goods_total'];
        }
        
        // 店铺优惠券
        $condition = array();
        $condition['voucher_t_gettype'] = 3;
        $condition['voucher_t_state'] = 1;
        $condition['voucher_t_end_date'] = array('gt', time());
        $condition['voucher_t_mgradelimit'] = array('elt', $this->member_info['level']);
        $condition['voucher_t_store_id'] = array('in', array_keys($store_cart_list));
        $voucher_template = Model('voucher')->getVoucherTemplateList($condition);
        if (!empty($voucher_template)) {
            foreach ($voucher_template as $val) {
                $param = array();
                $param['voucher_t_id'] = $val['voucher_t_id'];
                $param['voucher_t_price'] = $val['voucher_t_price'];
                $param['voucher_t_limit'] = $val['voucher_t_limit'];
                $param['voucher_t_end_date'] = date('Y年m月d日', $val['voucher_t_end_date']);
                $store_cart_list[$val['voucher_t_store_id']]['voucher'][] = $param;
            }
        }
        
        //取得店铺级活动 - 可用的满即送活动
        $mansong_rule_list = $handle_buy_action->getMansongRuleList(array_keys($store_cart_list));
        if (!empty($mansong_rule_list)) {
            foreach ($mansong_rule_list as $key => $val) {
                $store_cart_list[$key]['mansong'] = $val;
            }
        }
        
        //取得哪些店铺有满免运费活动
        $free_freight_list = $handle_buy_action->getFreeFreightActiveList(array_keys($store_cart_list));
        if (!empty($free_freight_list)) {
            foreach ($free_freight_list as $key => $val) {
                $store_cart_list[$key]['free_freight'] = $val;
            }
        }

        output_data(array('cart_list' => array_values($store_cart_list), 'sum' => wtPriceFormat($sum), 'cart_count' => count($cart_list)));
    }

    /**
     * 购物车列表
     */
    public function cart_list_oldWt() {
        $model_cart = Model('cart');
    
        $condition = array('buyer_id' => $this->member_info['member_id']);
        $cart_list  = $model_cart->listCart('db', $condition);
    
        // 购物车列表 [得到最新商品属性及促销信息]
        $cart_list = handle('buy_action')->getGoodsCartList($cart_list, $jjgObj);
        $sum = 0;
        foreach ($cart_list as $key => $value) {
            $cart_list[$key]['goods_image_url'] = cthumb($value['goods_image'], $value['store_id']);
            $cart_list[$key]['goods_sum'] = wtPriceFormat($value['goods_price'] * $value['goods_num']);
            $sum += $cart_list[$key]['goods_sum'];
        }
    
        output_data(array('cart_list' => $cart_list, 'sum' => wtPriceFormat($sum)));
    }

    /**
     * 购物车添加
     */
    public function cart_addWt() {
        if(!$this->member_info['is_buy']) output_error('您没有商品购买的权限,如有疑问请联系客服人员');
        $goods_id = intval($_POST['goods_id']);
        $quantity = intval($_POST['quantity']);
        if($goods_id <= 0 || $quantity <= 0) {
            output_error('参数错误');
        }

        $model_goods = Model('goods');
        $model_cart = Model('cart');
        $handle_buy_action = Handle('buy_action');

        $goods_content = $model_goods->getGoodsOnlineInfoAndSaleById($goods_id);

        //验证是否可以购买
        if(empty($goods_content)) {
            output_error('商品已下架或不存在');
        }

        //抢购
        $handle_buy_action->getRobbuyInfo($goods_content);
        if ($goods_content['ifrobbuy']) {
            if ($goods_content['upper_limit'] && $quantity > $goods_content['upper_limit']) {
                output_error('抢购商品购买超限，最多可购买'.$goods_content['upper_limit']."个");
            }
        }

        //限时折扣
        $handle_buy_action->getXianshiInfo($goods_content,$quantity);

        if ($goods_content['store_id'] == $this->member_info['store_id']) {
            output_error('不能购买自己发布的商品');
        }
        if(intval($goods_content['goods_storage']) < 1 || intval($goods_content['goods_storage']) < $quantity) {
            output_error('库存不足');
        }

        if ($goods_content['is_virtual'] || $goods_content['is_fcode'] || $goods_content['is_book']) {
            output_error('该商品不允许加入购物车，请直接购买');
        }

        $param = array();
        $param['buyer_id']  = $this->member_info['member_id'];
        $param['store_id']  = $goods_content['store_id'];
        $param['goods_id']  = $goods_content['goods_id'];
        $param['goods_name'] = $goods_content['goods_name'];
        $param['goods_price'] = $goods_content['goods_price'];
        $param['goods_image'] = $goods_content['goods_image'];
        $param['store_name'] = $goods_content['store_name'];

        $result = $model_cart->addCart($param, 'db', $quantity);
        if($result) {
            $fx_id = intval($_POST['fx_id']);
            if($fx_id && $goods_content['is_fx']) {//分销
                $model_fx_goods = Model('fx_goods');
                $condition = array();
                $condition['fx_id'] = $fx_id;
                $fx_goods = $model_fx_goods->getFenxiaoGoodsInfo($condition);
                if ($fx_goods['fx_goods_state'] == 1) {
                    $model_fx_goods->addDisCart($fx_goods['goods_commonid'],$fx_goods['member_id'],$this->member_info['member_id']);
                }
            }
            output_data('1');
        } else {
            output_error('加入购物车失败');
        }
    }

    /**
     * 购物车删除
     */
    public function cart_delWt() {
        $cart_id = intval($_POST['cart_id']);

        $model_cart = Model('cart');

        if($cart_id > 0) {
            $condition = array();
            $condition['buyer_id'] = $this->member_info['member_id'];
            $condition['cart_id'] = $cart_id;

            $model_cart->delCart('db', $condition);
        }

        output_data('1');
    }

    /**
     * 更新购物车购买数量
     */
    public function cart_edit_quantityWt() {
        $cart_id = intval(abs($_POST['cart_id']));
		$goods_id = intval($_POST['goods_id']);
        $quantity = intval(abs($_POST['quantity']));
        if(empty($cart_id) || empty($quantity)) {
            output_error('参数错误');
        }

        $model_cart = Model('cart');

        $cart_info = $model_cart->getCartInfo(array('cart_id'=>$cart_id, 'buyer_id' => $this->member_info['member_id']));
		
		//提取阶梯价格 v6.5
		$goods_step_price = Model('goods')->getGoodStepPrice('common_id='.$goods_id.' and step_l_num<='.$quantity.' and step_h_num>'.$quantity);
		
		if(!$this->checklowbuyWt($goods_id,$quantity)==0){
			output_error('购买数量未达到起批数量');
		}
		

        //检查是否为本人购物车
        if($cart_info['buyer_id'] != $this->member_info['member_id']) {
            output_error('参数错误');
        }

        //检查库存是否充足
        if(!$this->_check_goods_storage($cart_info, $quantity, $this->member_info['member_id'])) {
            output_error('超出限购数或库存不足');
        }

        $data = array();
        $data['goods_num'] = $quantity;
		
		
        $update = $model_cart->editCart($data, array('cart_id'=>$cart_id));
        if ($update) {
			$return = array();
            $return['quantity'] = $quantity;
			if($goods_step_price){
			$return['goods_price']  = wtPriceFormat($goods_step_price[0]['step_price']);
			}else {
			$return['goods_price'] = wtPriceFormat($cart_info['goods_price']);
			}
            $return['total_price'] = wtPriceFormat($cart_info['goods_price'] * $quantity);		
            output_data($return);
        } else {
            output_error('修改失败');
		}
    }

    /**
     * 检查库存是否充足
     */
    private function _check_goods_storage(& $cart_info, $quantity, $member_id) {
        $model_goods= Model('goods');
        $model_bl = Model('p_bundling');
        $handle_buy_action = Handle('buy_action');

        if ($cart_info['bl_id'] == '0') {
            //普通商品
            $goods_content = $model_goods->getGoodsOnlineInfoAndSaleById($cart_info['goods_id']);

            //手机专享
            $handle_buy_action->getMbSoleInfo($goods_content);
            
            //抢购
            $handle_buy_action->getRobbuyInfo($goods_content);
            if ($goods_content['ifrobbuy']) {
                if ($goods_content['upper_limit'] && $quantity > $goods_content['upper_limit']) {
                    return false;
                }
            }

            //限时折扣
            $handle_buy_action->getXianshiInfo($goods_content,$quantity);

            if(intval($goods_content['goods_storage']) < $quantity) {
                return false;
            }
            $goods_content['cart_id'] = $cart_info['cart_id'];
            $cart_info = $goods_content;
        } else {
            //优惠套装商品
            $bl_goods_list = $model_bl->getBundlingGoodsList(array('bl_id' => $cart_info['bl_id']));
            $goods_id_array = array();
            foreach ($bl_goods_list as $goods) {
                $goods_id_array[] = $goods['goods_id'];
            }
            $bl_goods_list = $model_goods->getGoodsOnlineListAndSaleByIdArray($goods_id_array);

            //如果有商品库存不足，更新购买数量到目前最大库存
            foreach ($bl_goods_list as $goods_content) {
                if (intval($goods_content['goods_storage']) < $quantity) {
                    return false;
                }
            }
        }
        return true;
    }
    
    /**
     * 查询购物车商品数量
     */
    function cart_countWt() {
        $param['cart_count'] = Model('cart')->countCartByMemberId($this->member_info['member_id']);
        output_data($param);
    }

    /**
     * 批量添加购物车
     * cartlist 格式为goods_id1,num1|goods_id2,num2
     */
    public function cart_batchaddWt(){
        $param = $_POST;
        $cartlist_str = trim($param['cartlist']);
        $cartlist_arr = $cartlist_str?explode('|',$cartlist_str):array();
        if(!$cartlist_arr) {
            output_error('参数错误');
        }

        $cartlist_new =  array();
        foreach($cartlist_arr as $k=>$v){
            $tmp = $v?explode(',',$v):array();
            if (!$tmp) {
                continue;
            }
            $cartlist_new[$tmp[0]]['goods_num'] = $tmp[1];
        }
        Model('cart')->batchAddCart($cartlist_new, $this->member_info['member_id'], $this->member_info['store_id']);
        output_data('1');
    }
	/**
	 * 取得商品最低起批量 v6.5
	 */
	public function checklowbuyWt($goods_id,$quantity){
            //商品
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
}
