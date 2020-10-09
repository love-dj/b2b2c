<?php
/**
 * 购物车操作
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class cartControl extends BaseBuyControl {

    public function __construct() {
        parent::__construct();
        Language::read('home_cart_index');

        $t = isset($_GET['t']) ? $_GET['t'] : $_POST['t'];

        //允许不登录就可以访问的op
        $op_arr = array('ajax_load','add','del');
        if (!in_array($t,$op_arr) && !$_SESSION['member_id'] ){
            redirect(urlLogin('login', 'index', array('ref_url' => request_uri())));
        }
        Tpl::output('hidden_rtoolbar_cart', 1);
    }

    /**
     * 购物车首页
     */
    public function indexWt() {
        $model_cart = Model('cart');
        $handle_buy_action = handle('buy_action');

        //购物车列表
        $cart_list  = $model_cart->listCart('db',array('buyer_id'=>$_SESSION['member_id']));

        // 加价购
        $jjgObj = new \StdClass();

        // 购物车列表 [得到最新商品属性及促销信息]
        $cart_list = $handle_buy_action->getGoodsCartList($cart_list, $jjgObj);
        // 加价购活动
        Tpl::output('jjgDetails', $jjgObj->details);

        //购物车商品以店铺ID分组显示,并计算商品小计,店铺小计与总价由JS计算得出
        $store_cart_list = array();
        foreach ($cart_list as $cart) {
            $cart['goods_total'] = wtPriceFormat($cart['goods_price'] * $cart['goods_num']);
            $store_cart_list[$cart['store_id']][] = $cart;
        }
        Tpl::output('store_cart_list',$store_cart_list);

        //店铺信息
        $store_list = Model('store')->getStoreMemberIDList(array_keys($store_cart_list));
        Tpl::output('store_list',$store_list);

        // 店铺优惠券
        $condition = array();
        $condition['voucher_t_gettype'] = 3;
        $condition['voucher_t_state'] = 1;
        $condition['voucher_t_end_date'] = array('gt', time());
        $condition['voucher_t_mgradelimit'] = array('elt', $this->member_info['level']);
        $condition['voucher_t_store_id'] = array('in', array_keys($store_cart_list));
        $voucher_template = Model('voucher')->getVoucherTemplateList($condition);
        $voucher_template = array_under_reset($voucher_template, 'voucher_t_store_id', 2);
        Tpl::output('voucher_template', $voucher_template);

        //取得店铺级活动 - 可用的满即送活动
        $mansong_rule_list = $handle_buy_action->getMansongRuleList(array_keys($store_cart_list));
        Tpl::output('mansong_rule_list',$mansong_rule_list);

        //取得哪些店铺有满免运费活动
        $free_freight_list = $handle_buy_action->getFreeFreightActiveList(array_keys($store_cart_list));
        Tpl::output('free_freight_list',$free_freight_list);
        //标识 购买流程执行第几步
        Tpl::output('buy_step','step1');
        Tpl::showpage(empty($cart_list) ? 'cart_empty' : 'cart');
    }

    /**
     * 异步查询购物车
     */
    public function ajax_loadWt() {
        $model_cart = Model('cart');
        if ($_SESSION['member_id']){
            //登录后
            $cart_list  = $model_cart->listCart('db',array('buyer_id'=>$_SESSION['member_id']));
            $cart_array = array();
            if(!empty($cart_list)){
                foreach ($cart_list as $k => $cart){
                    $cart_array['list'][$k]['cart_id'] = $cart['cart_id'];
                    $cart_array['list'][$k]['goods_id'] = $cart['goods_id'];
                    $cart_array['list'][$k]['goods_name'] = $cart['goods_name'];
                    $cart_array['list'][$k]['goods_price']  = $cart['goods_price'];
                    $cart_array['list'][$k]['goods_image']  = thumb($cart,60);
                    $cart_array['list'][$k]['goods_num'] = $cart['goods_num'];
                    $cart_array['list'][$k]['goods_url'] = urlShop('goods', 'index', array('goods_id' => $cart['goods_id']));
                }
            }
        } else {
            //登录前
            $cart_list = $model_cart->listCart('cookie');
            foreach ($cart_list as $key => $cart){
                $value = array();
                $value['cart_id'] = $cart['goods_id'];
                $value['goods_id'] = $cart['goods_id'];
                $value['goods_name'] = $cart['goods_name'];
                $value['goods_price'] = $cart['goods_price'];
                $value['goods_num'] = $cart['goods_num'];
                $value['goods_image'] = thumb($cart,60);
                $value['goods_url'] = urlShop('goods', 'index', array('goods_id' => $cart['goods_id']));
                $cart_array['list'][] = $value;
            }
        }
        setWtCookie('cart_goods_num',$model_cart->cart_goods_num,2*3600);
        $cart_array['cart_all_price'] = wtPriceFormat($model_cart->cart_all_price);
        $cart_array['cart_goods_num'] = $model_cart->cart_goods_num;
        if ($_GET['type'] == 'html') {
            Tpl::output('cart_list',$cart_array);
            Tpl::showpage('cart_mini','null_layout');
        } else {
            $cart_array = strtoupper(CHARSET) == 'GBK' ? Language::getUTF8($cart_array) : $cart_array;
            $json_data = json_encode($cart_array);
            if (isset($_GET['callback'])) {
                $json_data = $_GET['callback']=='?' ? '('.$json_data.')' : $_GET['callback']."($json_data);";
            }
            exit($json_data);
        }

    }

    /**
     * 加入购物车，登录后存入购物车表
     * 存入COOKIE，由于COOKIE长度限制，最多保存5个商品
     * 未登录不能将优惠套装商品加入购物车，登录前保存的信息以goods_id为下标
     *
     */
    public function addWt() {
        $model_goods = Model('goods');
        $handle_buy_action = Handle('buy_action');
        if (is_numeric($_GET['goods_id'])) {

            //商品加入购物车(默认)
            $goods_id = intval($_GET['goods_id']);
            $quantity = intval($_GET['quantity']);
            if ($goods_id <= 0) return ;
            $goods_content = $model_goods->getGoodsOnlineInfoAndSaleById($goods_id);

            //抢购
            $handle_buy_action->getRobbuyInfo($goods_content);

            //限时折扣
            $handle_buy_action->getXianshiInfo($goods_content,$quantity);

            $this->_check_goods($goods_content,$_GET['quantity']);

        } elseif (is_numeric($_GET['bl_id'])) {

            //优惠套装加入购物车(单套)
            if (!$_SESSION['member_id']) {
                exit(json_encode(array('msg'=>'请先登录','UTF-8')));
            }
            $bl_id = intval($_GET['bl_id']);
            if ($bl_id <= 0) return ;
            $model_bl = Model('p_bundling');
            $bl_info = $model_bl->getBundlingInfo(array('bl_id'=>$bl_id));
            if (empty($bl_info) || $bl_info['bl_state'] == '0') {
                exit(json_encode(array('msg'=>'该优惠套装已不存在，建议您单独购买','UTF-8')));
            }

            //检查每个商品是否符合条件,并重新计算套装总价
            $bl_goods_list = $model_bl->getBundlingGoodsList(array('bl_id'=>$bl_id));
            $goods_id_array = array();
            $bl_amount = 0;
            foreach ($bl_goods_list as $goods) {
                $goods_id_array[] = $goods['goods_id'];
                $bl_amount += $goods['bl_goods_price'];
            }
            $model_goods = Model('goods');
            $goods_list = $model_goods->getGoodsOnlineListAndSaleByIdArray($goods_id_array);
            foreach ($goods_list as $goods) {
                $this->_check_goods($goods,1);
            }

            //优惠套装作为一条记录插入购物车，图片取套装内的第一个商品图
            $goods_content    = array();
            $goods_content['store_id'] = $bl_info['store_id'];
            $goods_content['goods_id'] = $goods_list[0]['goods_id'];
            $goods_content['goods_name'] = $bl_info['bl_name'];
            $goods_content['goods_price'] = $bl_amount;
            $goods_content['goods_num']   = 1;
            $goods_content['goods_image'] = $goods_list[0]['goods_image'];
            $goods_content['store_name'] = $bl_info['store_name'];
            $goods_content['bl_id'] = $bl_id;
            $quantity = 1;
        }

        //已登录状态，存入数据库,未登录时，存入COOKIE
        if($_SESSION['member_id']) {
            $save_type = 'db';
            $goods_content['buyer_id'] = $_SESSION['member_id'];
        } else {
            $save_type = 'cookie';
        }
        $model_cart = Model('cart');
	//提取阶梯价格 v6.5
		$goods_step_price = Model('goods')->getGoodStepPrice('common_id='.$goods_id.' and step_l_num<='.$quantity.' and step_h_num>'.$quantity);
		
		if($goods_step_price)
		{
			$goods_content['goods_price'] = $goods_step_price[0]['step_price'];
		}
		
       //检查起批数量 v6.5

		if($this->checklowbuyWt($goods_id,$quantity)==0)
		{		
        $insert = $model_cart->addCart($goods_content,$save_type,$quantity);
        if ($insert) {
            //购物车商品种数记入cookie
            setWtCookie('cart_goods_num',$model_cart->cart_goods_num,2*3600);
            $data = array('state'=>'true', 'num' => $model_cart->cart_goods_num, 'amount' => wtPriceFormat($model_cart->cart_all_price));
        } else {
            $data = array('state'=>'false');
        }
        exit(json_encode($data));
		} else{
			exit(json_encode(array('msg'=>'购买数量未达到起批数量','UTF-8')));
		}
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

    /**
     * 推荐组合加入购物车
     */
    public function add_combWt() {
        if (!preg_match('/^[\d|]+$/', $_GET['goods_ids'])) {
            exit(json_encode(array('state'=>'false')));
        }

        $model_goods = Model('goods');
        $handle_buy_action = Handle('buy_action');

        if (!$_SESSION['member_id']) {
            exit(json_encode(array('msg'=>'请先登录','UTF-8')));
        }

        $goods_id_array = explode('|', $_GET['goods_ids']);

        $model_goods = Model('goods');
        $goods_list = $model_goods->getGoodsOnlineListAndSaleByIdArray($goods_id_array);

        foreach ($goods_list as $goods) {
            $this->_check_goods($goods,1);
        }

        //抢购
        $handle_buy_action->getRobbuyCartList($goods_list);

        //限时折扣
        $handle_buy_action->getXianshiCartList($goods_list);

        $model_cart = Model('cart');
        foreach ($goods_list as $goods_content) {
            $cart_info = array();
            $cart_info['store_id']  = $goods_content['store_id'];
            $cart_info['goods_id']  = $goods_content['goods_id'];
            $cart_info['goods_name'] = $goods_content['goods_name'];
            $cart_info['goods_price'] = $goods_content['goods_price'];
            $cart_info['goods_num']   = 1;
            $cart_info['goods_image'] = $goods_content['goods_image'];
            $cart_info['store_name'] = $goods_content['store_name'];
            $quantity = 1;
            //已登录状态，存入数据库,未登录时，存入COOKIE
            if($_SESSION['member_id']) {
                $save_type = 'db';
                $cart_info['buyer_id'] = $_SESSION['member_id'];
            } else {
                $save_type = 'cookie';
            }
            $insert = $model_cart->addCart($cart_info,$save_type,$quantity);
            if ($insert) {
                //购物车商品种数记入cookie
                setWtCookie('cart_goods_num',$model_cart->cart_goods_num,2*3600);
                $data = array('state'=>'true', 'num' => $model_cart->cart_goods_num, 'amount' => wtPriceFormat($model_cart->cart_all_price));
            } else {
                $data = array('state'=>'false');
                exit(json_encode($data));
            }
        }
        exit(json_encode($data));
    }

    /**
     * 检查商品是否符合加入购物车条件
     * @param unknown $goods
     * @param number $quantity
     */
    private function _check_goods($goods_content, $quantity) {
        if(empty($quantity)) {
            exit(json_encode(array('msg'=>Language::get('wrong_argument','UTF-8'))));
        }
        if(empty($goods_content)) {
            exit(json_encode(array('msg'=>Language::get('cart_add_goods_not_exists','UTF-8'))));
        }
        if ($goods_content['store_id'] == $_SESSION['store_id']) {
            exit(json_encode(array('msg'=>Language::get('cart_add_cannot_buy','UTF-8'))));
        }
        if(intval($goods_content['goods_storage']) < 1) {
            exit(json_encode(array('msg'=>Language::get('cart_add_stock_shortage','UTF-8'))));
        }
        if(intval($goods_content['goods_storage']) < $quantity) {
            exit(json_encode(array('msg'=>Language::get('cart_add_too_much','UTF-8'))));
        }
        if ($goods_content['is_virtual'] || $goods_content['is_fcode'] || $goods_content['is_book']) {
            exit(json_encode(array('msg'=>'该商品不允许加入购物车，请直接购买','UTF-8')));
        }
    }

    /**
     * 购物车更新商品数量
     */
    public function updateWt() {
        $cart_id    = intval(abs($_GET['cart_id']));
        $quantity   = intval(abs($_GET['quantity']));

        if(empty($cart_id) || empty($quantity)) {
            exit(json_encode(array('msg'=>Language::get('cart_update_buy_fail','UTF-8'))));
        }

        $model_cart = Model('cart');
        $model_goods= Model('goods');
        $handle_buy_action = handle('buy_action');

        //存放返回信息
        $return = array();

        $cart_info = $model_cart->getCartInfo(array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
        if ($cart_info['bl_id'] == '0') {

            //普通商品
            $goods_id = intval($cart_info['goods_id']);
            $goods_content = $handle_buy_action->getGoodsOnlineInfo($goods_id,$quantity);
            if(empty($goods_content)) {
                $return['state'] = 'invalid';
                $return['msg'] = '商品已被下架';
                $return['subtotal'] = 0;
                QueueClient::push('delCart', array('buyer_id'=>$_SESSION['member_id'],'cart_ids'=>array($cart_id)));
                exit(json_encode($return));
            }

            //抢购
            $handle_buy_action->getRobbuyInfo($goods_content);

            //限时折扣
            $handle_buy_action->getXianshiInfo($goods_content,$quantity);

            $quantity = $goods_content['goods_num'];

            if(intval($goods_content['goods_storage']) < $quantity) {
                $return['state'] = 'shortage';
                $return['msg'] = '库存不足';
                $return['goods_num'] = $goods_content['goods_storage'];
                $return['goods_price'] = $goods_content['goods_price'];
                $return['subtotal'] = $goods_content['goods_price'] * intval($goods_content['goods_storage']);
                $model_cart->editCart(array('goods_num'=>$goods_content['goods_storage']),array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
                exit(json_encode($return));
            }
        } else {

            //优惠套装商品
            $model_bl = Model('p_bundling');
            $bl_goods_list = $model_bl->getBundlingGoodsList(array('bl_id'=>$cart_info['bl_id']));
            $goods_id_array = array();
            foreach ($bl_goods_list as $goods) {
                $goods_id_array[] = $goods['goods_id'];
            }
            $goods_list = $model_goods->getGoodsOnlineListAndSaleByIdArray($goods_id_array);

            //如果其中有商品下架，删除
            if (count($goods_list) != count($goods_id_array)) {
                $return['state'] = 'invalid';
                $return['msg'] = '该优惠套装已经无效，建议您购买单个商品';
                $return['subtotal'] = 0;
                QueueClient::push('delCart', array('buyer_id'=>$_SESSION['member_id'],'cart_ids'=>array($cart_id)));
                exit(json_encode($return));
            }

            //如果有商品库存不足，更新购买数量到目前最大库存
            foreach ($goods_list as $goods_content) {
                if ($quantity > $goods_content['goods_storage']) {
                    $return['state'] = 'shortage';
                    $return['msg'] = '该优惠套装部分商品库存不足，建议您降低购买数量或购买库存足够的单个商品';
                    $return['goods_num'] = $goods_content['goods_storage'];
                    $return['goods_price'] = $cart_info['goods_price'];
                    $return['subtotal'] = $cart_info['goods_price'] * $quantity;
                    $model_cart->editCart(array('goods_num'=>$goods_content['goods_storage']),array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
                    exit(json_encode($return));
                    break;
                }
            }
            $goods_content['goods_price'] = $cart_info['goods_price'];
        }

        $data = array();
        $data['goods_num'] = $quantity;
        $data['goods_price'] = $goods_content['goods_price'];
        $update = $model_cart->editCart($data,array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
        if ($update) {
            $return = array();
            $return['state'] = 'true';
            $return['subtotal'] = $goods_content['goods_price'] * $quantity;
            $return['goods_price'] = $goods_content['goods_price'];
            $return['goods_num'] = $quantity;
        } else {
            $return = array('msg'=>Language::get('cart_update_buy_fail','UTF-8'));
        }
        exit(json_encode($return));
    }

    /**
     * 购物车删除单个商品，未登录前使用cart_id即为goods_id
     */
    public function delWt() {
        $cart_id = intval($_GET['cart_id']);
        if($cart_id < 0) return ;
        $model_cart = Model('cart');
        $data = array();
        if ($_SESSION['member_id']) {
            //登录状态下删除数据库内容
            $delete = $model_cart->delCart('db',array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
            if($delete) {
                $data['state'] = 'true';
                $data['quantity'] = $model_cart->cart_goods_num;
                $data['amount'] = $model_cart->cart_all_price;
            } else {
                $data['msg'] = Language::get('cart_drop_del_fail','UTF-8');
            }
        } else {
            //未登录时删除cookie的购物车信息
            $delete = $model_cart->delCart('cookie',array('goods_id'=>$cart_id));
            if($delete) {
                $data['state'] = 'true';
                $data['quantity'] = $model_cart->cart_goods_num;
                $data['amount'] = $model_cart->cart_all_price;
            }
        }
        setWtCookie('cart_goods_num',$model_cart->cart_goods_num,2*3600);
        $json_data = json_encode($data);
        if (isset($_GET['callback'])) {
            $json_data = $_GET['callback']=='?' ? '('.$json_data.')' : $_GET['callback']."($json_data);";
        }
        exit($json_data);
    }
}
