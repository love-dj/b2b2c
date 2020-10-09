<?php
/**
 * 分销管理
 */


defined('ShopWT') or exit('Access Denied By ShopWT');

class member_fxControl extends mobilefenxiaoControl {
    function __construct()
    {
        parent::__construct();
    }
	/**
     * 我的分销
     */
    public function indexWt() {
        $member_info = array();
        $member_info['user_name'] = $this->member_info['member_name'];
        $member_info['avatar'] = getMemberAvatarForID($this->member_info['member_id']);

        $member_gradeinfo = Model('member')->getOneMemberGrade(intval($this->member_info['member_exppoints']));
        $member_info['level'] = $member_gradeinfo['level'];
        $member_info['level_name'] = $member_gradeinfo['level_name'];
        $member_info['available_fx_trad'] = wtPriceFormat($this->member_info['available_fx_trad']);
        $member_info['freeze_fx_trad'] = wtPriceFormat($this->member_info['freeze_fx_trad']);
       
        $member_info['is_fxuser'] = $this->member_info['fx_state'] == 2 ? 1 : 0;
        //$model_fx_goods = Model('fx_goods');
        //$fx_goods_count = $model_fx_goods->getFenxiaoGoodsCount(array('member_id' => $this->member_info['member_id']));//查看该会员分销商品数量

        output_data(array('member_info' => $member_info));
    }

    /**
     * 获取分销商品列表
     */
    public function fx_goodsWt(){
        $model_goods = Model('fx_goods');
        $condition = array('member_id'=>$this->member_info['member_id']);
        $condition['fx_goods.fx_goods_state'] = 1;
        if(trim($_POST['goods_name']) != ''){
            $condition['goods_common.goods_name|goods_common.goods_jingle'] = array('like', '%' . $_POST['goods_name'] . '%');
        }
        $goods_tmp_list = $model_goods->getFenxiaoGoodsCommonList($condition,'*',$this->page);
        $goods_list = array();
        foreach($goods_tmp_list as $k => $value){
            $tmp = array();
            $tmp['fx_id'] = $value['fx_id'];
            $tmp['goods_commonid'] = $value['goods_commonid'];
            $tmp['goods_name'] = $value['goods_name'];
            $tmp['goods_price'] = $value['goods_price'];
            $tmp['fx_time'] = date('Y年m月d日',$value['fx_time']);
            $tmp['store_name'] = $value['store_name'];
            $tmp['store_id'] = $value['store_id'];
            $tmp['fx_commis_rate'] = $value['fx_commis_rate'];
            $tmp['goods_image_url'] = cthumb($value['goods_image'], 60,$value['store_id']);

            $goods_list[$k] = $tmp;
        }
        $page_count = $model_goods->gettotalpage();
        output_data(array('goods_list' => $goods_list), mobile_page($page_count));
    }

    /**
     * 删除分销商品
     */
    public function drop_goodsWt(){
        $fx_id = intval($_POST['fx_id']);
        if($fx_id <= 0){
            output_error('参数错误');
        }
        $model_goods = Model('fx_goods');
        $condition = array('fx_id' => $fx_id);
        $condition['member_id'] = $this->member_info['member_id'];
        $stat = $model_goods->delFenxiaoGoods($condition);
        if($stat){
            output_data('1');
        }else{
            output_error('删除失败');
        }
    }

    /**
     * 获取分销订单列表
     */
    public function fx_orderWt(){
        $model_order = Model('fx_order');
        $condition = array('fx_member_id' => $this->member_info['member_id']);
        if(trim($_POST['order_key'])){
            $condition['order_goods.goods_name|order_sn'] = array('like', '%' . $_POST['order_key'] . '%');
        }

        if ($_POST['state_type'] != '') {
            $condition['order_state'] = str_replace(
                array('state_new','state_send','state_noeval'),
                array(ORDER_STATE_NEW,ORDER_STATE_SEND,ORDER_STATE_SUCCESS), $_POST['state_type']);
        }
        if ($_POST['state_type'] == 'state_new') {
            $condition['chain_code'] = 0;
        }
        if ($_POST['state_type'] == 'state_noeval') {
           // $condition['evaluation_state'] = 0;
            $condition['order_state'] = ORDER_STATE_SUCCESS;
        }
        if ($_POST['state_type'] == 'state_notakes') {
            $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
            $condition['chain_code'] = array('gt',0);
        }

        $condition['order_goods.is_fx'] = 1;
        $fields = '*';
        $list = $model_order->getMeberFenxiaoOrderList($condition, $fields, $this->page);
        $order_list = array();

        foreach($list as $k => $value){
            $tmp = array();
            $tmp['goods_id'] = $value['goods_id'];
            $tmp['goods_name'] = $value['goods_name'];
            $tmp['goods_price'] = $value['goods_price'];
            $tmp['goods_num'] = $value['goods_num'];
            $tmp['order_sn'] = $value['order_sn'];
            $tmp['goods_pay_price'] = $value['goods_pay_price'];
            $tmp['fx_commis_rate'] = $value['fx_commis_rate'];
            $tmp['add_time'] = $value['add_time'];
            $tmp['store_id'] = $value['store_id'];
            $tmp['store_name'] = $value['store_name'];
            $tmp['goods_image_url'] = cthumb($value['goods_image'], 60,$value['store_id']);
            $tmp['fx_commis_amount'] = $value['goods_pay_price']*$value['fx_commis_rate']*0.01;
            $tmp['order_state'] = $value['order_state'];
            $tmp['order_state_txt'] = orderState($value);
            $tmp['add_time'] = date('Y年m月d日',$value['add_time']);

            $order_list[$k] = $tmp;
        }

        $page_count = $model_order->gettotalpage();
        output_data(array('order_list' => $order_list), mobile_page($page_count));
    }

    /**
     * 获取分销结算列表
     */
    public function fx_billWt(){
        $model_bill = Model('fx_bill');
        $condition = array('fx_member_id' => $this->member_info['member_id']);
        if(trim($_POST['goods_name'])){
            $condition['goods_name|order_sn'] = array('like', '%' . $_POST['goods_name'] . '%');
        }
        if(is_numeric($_POST['bill_state']) && intval($_POST['bill_state']) >= 0){
            $condition['log_state'] = intval($_POST['bill_state']);
        }
        $fields = '*';
        $list = $model_bill->getFenxiaoBillList($condition, $fields, $this->page);

        $bill_list = array();
        foreach($list as $k => $value){
            $tmp = array();
            $tmp['order_sn'] = $value['order_sn'];
            $tmp['goods_id'] = $value['goods_id'];
            $tmp['goods_name'] = $value['goods_name'];
            $tmp['pay_goods_amount'] = $value['pay_goods_amount'];
            $tmp['refund_amount'] = $value['refund_amount'];
            $tmp['fx_commis_rate'] = $value['fx_commis_rate'];
            $tmp['goods_image_url'] = cthumb($value['goods_image'], 60,$value['store_id']);
            $tmp['fx_pay_amount'] = $value['fx_pay_amount'];
            $tmp['fx_pay_time'] = $value['fx_pay_time'];
            $tmp['bill_state'] = $value['log_state'];
            $tmp['bill_state_txt'] = str_replace(array(0,1), array('未结算','已结算'), $value['log_state']);
			if($value['log_state'] == 1){
				$tmp['fx_pay_time_txt'] = date('Y年m月d日',$value['fx_pay_time']);
			}else{
				$tmp['fx_pay_time_txt'] = '---';
			}

            $bill_list[$k] = $tmp;
        }

        $page_count = $model_bill->gettotalpage();
        output_data(array('bill_list' => $bill_list), mobile_page($page_count));
    }

    /**
     * 获取分销提现列表
     */
    public function fx_cashWt(){
        $condition = array();
        if (preg_match('/^\d+$/',$_POST['sn_search'])) {
            $condition['tradc_sn'] = $_POST['sn_search'];
        }
        if (isset($_POST['paystate_search']) && is_numeric($_POST['paystate_search'])){
            $condition['tradc_payment_state'] = intval($_POST['paystate_search']);
        }
        $condition['tradc_member_id'] = $this->member_info['member_id'];
        $model_tard = Model('fx_trad');
        $list = $model_tard->getFenxiaoTradCashList($condition, '*' , $this->page);

        $cash_list = array();
        foreach($list as $k => $value){
            $tmp = array();
            $tmp['tradc_sn'] = $value['tradc_sn'];
            $tmp['tradc_add_time'] = date('Y-m-d H:i:s',$value['tradc_add_time']);
            $tmp['tradc_amount'] = $value['tradc_amount'];
            $tmp['tradc_payment_state'] = $value['tradc_payment_state'];
            $tmp['tradc_payment_state_txt'] = str_replace(array('0','1'),array('未支付','已支付'),$value['tradc_payment_state']);
            $tmp['tradc_id'] = $value['tradc_id'];

            $cash_list[$k] = $tmp;
        }

        $page_count = $model_tard->gettotalpage();
        output_data(array('cash_list' => $cash_list,'available_trad'=>$this->member_info['available_fx_trad'],'freeze_trad'=>$this->member_info['freeze_fx_trad']), mobile_page($page_count));
    }

    /**
     * 提现记录详情
     */
    public function cash_infoWt(){
        $tradc_id = intval($_GET['tradc_id']);
        if ($tradc_id <= 0){
            output_error('参数错误');
        }
        $model_tard = Model('fx_trad');
        $condition = array();
        $condition['tradc_member_id'] = $this->member_info['member_id'];
        $condition['tradc_id'] = $tradc_id;
        $info = $model_tard->getFenxiaoTradCashInfo($condition);
        if (empty($info)){
            output_error('记录不存在或已删除');
        }
		$info['tradc_add_time_text'] = date('Y-m-d H:i:s',$info['tradc_add_time']);
        $info['tradc_payment_state_txt'] = str_replace(array('0','1'),array('未支付','已支付'),$info['tradc_payment_state']);
		$info['tradc_payment_time_text'] = date('Y-m-d H:i:s',$info['tradc_add_time']);
		
        output_data($info);
    }

    /**
     * 佣金提现申请
     */
    public function cash_applyWt(){
        $obj_validate = new Validate();
        $tradc_amount = abs(floatval($_POST['tradc_amount']));
        $validate_arr[] = array("input"=>$tradc_amount, "require"=>"true",'validator'=>'Compare','operator'=>'>=',"to"=>'0.01',"message"=>'请输入正确的提现金额');
        $validate_arr[] = array("input"=>$_POST["pay_pwd"], "require"=>"true","message"=>'请输入支付密码');
        $obj_validate -> validateparam = $validate_arr;
        $error = $obj_validate->validate();
        if ($error != ''){
            output_error($error);
        }

        $model_tard = Model('fx_trad');

        //验证支付密码
        if (md5($_POST['pay_pwd']) != $this->member_info['member_paypwd']) {
            output_error('支付密码错误');
        }
        //验证金额是否足够
        $available_trad = $this->member_info['available_fx_trad'];

        if (floatval($available_trad) < $tradc_amount){
            output_error('请输入正确的提现金额!');
        }
        try {
            $model_tard->beginTransaction();
            $tradc_sn = $model_tard->makeSn();
            $data = array();
            $data['tradc_sn'] = $tradc_sn;
            $data['tradc_member_id'] = $this->member_info['member_id'];
            $data['tradc_member_name'] = $this->member_info['member_name'];
            $data['tradc_amount'] = $tradc_amount;
            $data['tradc_bank_name'] = $this->member_info['bill_bank_name'];
            $data['tradc_bank_no'] = $this->member_info['bill_type_number'];
            $data['tradc_bank_user'] = $this->member_info['bill_user_name'];
            $data['tradc_add_time'] = TIMESTAMP;
            $data['tradc_payment_state'] = 0;
            $insert = $model_tard->addFenxiaoTradCash($data);
            if (!$insert) {
                throw new Exception('提现申请失败');
            }
            //增加冻结分销佣金
            $data = array();
            $data['member_id'] = $this->member_info['member_id'];
            $data['member_name'] = $this->member_info['member_name'];
            $data['amount'] = $tradc_amount;
            $data['order_sn'] = $tradc_sn;
            $model_tard->changeDirtriTrad('cash_apply',$data);
            $model_tard->commit();
            output_data('1');
        } catch (Exception $e) {
            $model_tard->rollback();
            output_error($e->getMessage());
        }
    }
	
	/**
     * 佣金提现 账户信息
     */
    public function my_assetWt(){
        $model_member = Model('member');
        $member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
		
		//可提现金额
        $available_trad = $member_info['trad_amount'];

        //冻结金额
        $freeze_trad = floatval($member_info['freeze_trad']);
        if($member_info['fx_state'] == 2){
            if($member_info['trad_amount'] >= C('fenxiao_bill_limit')){
                $freeze_trad += C('fenxiao_bill_limit');
                $available_trad -= C('fenxiao_bill_limit');
            }else{
                $freeze_trad += $member_info['trad_amount'];
                $available_trad = 0;
            }
        }
		
		
        $member['member_name'] = $member_info['member_name'];
        $member['member_email'] = $member_info['member_email'];
        $member['bill_user_name'] = $member_info['bill_user_name'];
        $member['bill_type_code'] = $member_info['bill_type_code'];
        $member['bill_type_number'] = $member_info['bill_type_number'];
        $member['bill_bank_name'] = $member_info['bill_bank_name'];
        //$member['bill_user_name'] = $member_info['bill_user_name'];
        //$member['bill_user_name'] = $member_info['bill_user_name'];
		
		
        $member['available_fx_trad'] = $available_trad;
        $member['freeze_fx_trad'] = $freeze_trad;
		
        output_data($member);
    }

	
    /**
     * 添加分销商品列表
     */
    public function goods_listWt() {
        $model_goods = Model('goods');
        $model_search = Model('search');
        //$_GET['is_book'] = 0;

        //查询条件
        $condition = array();
        // ==== 暂时不显示定金预售商品，手机端未做。  ====
        //$condition['is_book'] = 0;
		$condition['is_fx'] = 1;
        if(!empty($_GET['gc_id']) && intval($_GET['gc_id']) > 0) {
            $condition['gc_id'] = $_GET['gc_id'];
        } elseif (!empty($_GET['keyword'])) {
			//空格搜
			$keys = explode(' ',$_GET['keyword']);
			$datakey = array();
			foreach($keys as $key=>$val){
				$datakey[] = array('like','%' . $val . '%');
			}
			$datakey[] = 'and';
			$condition['goods_name|goods_jingle'] = $datakey;
			
			
            if ($_COOKIE['hisSearch'] == '') {
                $his_sh_list = array();
            } else {
                $his_sh_list = explode('~', $_COOKIE['hisSearch']);
            }
            if (strlen($_GET['keyword']) <= 20 && !in_array($_GET['keyword'],$his_sh_list)) {
                if (array_unshift($his_sh_list, $_GET['keyword']) > 8) {
                    array_pop($his_sh_list);
                }
            }
            setcookie('hisSearch', implode('~', $his_sh_list), time()+2592000, '/', SUBDOMAIN_SUFFIX ? SUBDOMAIN_SUFFIX : '', false);

        } elseif (!empty($_GET['barcode'])) {
            $condition['goods_barcode'] = $_GET['barcode'];
        } elseif (!empty($_GET['b_id']) && intval($_GET['b_id'] > 0)) {
            $condition['brand_id'] = intval($_GET['b_id']);
        }
        $price_from = preg_match('/^[\d.]{1,20}$/',$_GET['price_from']) ? $_GET['price_from'] : null;
        $price_to = preg_match('/^[\d.]{1,20}$/',$_GET['price_to']) ? $_GET['price_to'] : null;

        //所需字段
        //$fieldstr = "goods_commonid,store_id,goods_name,goods_jingle,goods_price,goods_marketprice,goods_image,goods_salenum,evaluation_good_star,evaluation_count";

        //$fieldstr .= ',fx_commis_rate,goods_jingle,store_id,store_name,is_own_shop';
		$fieldstr =  "goods_commonid,goods_name,goods_jingle,gc_id,store_id,store_name,goods_price,goods_image,sale_count,click_count,gc_id_3,gc_id_1,gc_id_2,goods_verify,goods_state,is_own_shop,areaid_1,fx_commis_rate";

        //排序方式
        $order = $this->_goods_list_order($_GET['sort'], $_GET['order']);

	
		//查询消费者保障服务
		$contract_item = array();
		if (C('contract_allow') == 1) {
			$contract_item = Model('contract')->getContractItemByCache();
		}
		//消费者保障服务
		if ($contract_item && $search_ci_arr) {
			foreach ($search_ci_arr as $ci_val) {
				$condition["contract_{$ci_val}"] = 1;
			}
		}

		if ($price_from && $price_from) {
			$condition['goods_sale_price'] = array('between',"{$price_from},{$price_to}");
		} elseif ($price_from) {
			$condition['goods_sale_price'] = array('egt',$price_from);
		} elseif ($price_to) {
			$condition['goods_sale_price'] = array('elt',$price_to);
		}
		if ($_GET['gift'] == 1) {
			$condition['have_gift'] = 1;
		}
		if ($_GET['own_shop'] == 1) {
			$condition['store_id'] = 1;
		}
		if (intval($_GET['area_id']) > 0) {
			$condition['areaid_1'] = intval($_GET['area_id']);
		}

		//抢购和限时折扣搜索
	/* 	$_tmp = array();
		if ($_GET['robbuy'] == 1) {
			$_tmp[] = 1;
		}
		if ($_GET['xianshi'] == 1) {
			$_tmp[] = 2;
		}
		if ($_tmp) {
			$condition['goods_sale_type'] = array('in',$_tmp);
		}
		unset($_tmp); */

		//虚拟商品
		if ($_GET['virtual'] == 1) {
			$condition['is_virtual'] = 1;
		}

	   // $goods_list = $model_goods->getGoodsListByColorDistinct($condition, $fieldstr, $order, $this->page);
		$goods_list = $model_goods->getGoodsCommonOnlineList($condition, $fieldstr, $this->page, $order);
	
        $page_count = $model_goods->gettotalpage();
        //处理商品列表(抢购、限时折扣、商品图片)
        $goods_list = $this->_goods_list_extend($goods_list);
        output_data(array('goods_list' => $goods_list), mobile_page($page_count));
    }
	    /**
     * 商品列表排序方式
     */
    private function _goods_list_order($key, $order) {
        $result = 'is_own_shop desc,goods_commonid desc';
        if (!empty($key)) {

            $sequence = 'desc';
            if($order == 1) {
                $sequence = 'asc';
            }

            switch ($key) {
                //销量
               /*  case '1' :
                    $result = 'goods_salenum' . ' ' . $sequence;
                    break; */
                //浏览量
                case '2' :
                    $result = 'click_count' . ' ' . $sequence;
                    break;
                //价格
                case '3' :
                    $result = 'goods_price' . ' ' . $sequence;
                    break;
                //佣金
                case '4' :
                    $result = 'goods_price*fx_commis_rate' . ' ' . $sequence;
                    break;
            }
        }
        return $result;
    }
    /**
     * 处理商品列表(抢购、限时折扣、商品图片)
     */
    private function _goods_list_extend($goods_list) {

        foreach ($goods_list as $key => $value) {
            $goods_list[$key]['sole_flag']      = false;
            $goods_list[$key]['group_flag']     = false;
            $goods_list[$key]['xianshi_flag']   = false;
            

            //商品图片url
            $goods_list[$key]['goods_image_url'] = cthumb($value['goods_image'], 360, $value['store_id']);
			$goods_list[$key]['fx_commis_m'] = wtPriceFormat($value['goods_price']*$value['fx_commis_rate']*0.01);

            unset($goods_list[$key]['goods_sale_type']);
            unset($goods_list[$key]['goods_sale_price']);
           // unset($goods_list[$key]['goods_commonid']);
            unset($goods_list[$key]['wt_distinct']);
        }

        return $goods_list;
    }

    /**
     * 立即推广 & 获取二维码/添加分销商品
     */
    public function fx_addWt()
    {
        
		$member_info = Model('member')->getMemberInfoByID($this->member_info['member_id'], 'fx_state');
		if ($member_info['fx_state'] == 2) {
			$mode = Model('goods');
			$goods_commonid = intval($_POST['id']);
			$goods_common = $mode->getGoodsCommonInfoByID($goods_commonid);
			if (intval($goods_commonid) > 0 && !empty($goods_common)) {
				$stat = false;
				$model_dis = Model('fx_goods');
				$fx_goods = $model_dis->getFenxiaoGoodsInfo(array('member_id' => $member_info['member_id'], 'goods_commonid' => $goods_commonid,'fx_goods_state' => 1));

				$param = array();
				//$fx_id = 0;
				if (empty($fx_goods)) {
					$param['goods_commonid'] = $goods_common['goods_commonid'];
					$param['goods_name'] = $goods_common['goods_name'];
					$param['goods_image'] = cthumb($goods_common['goods_image'], 360, $goods_common['store_id']);
					$param['member_id'] = $member_info['member_id'];
					$param['member_name'] = $member_info['member_name'];
					$param['fx_time'] = time();
					$param['store_id'] = $goods_common['store_id'];
					$param['store_name'] = $goods_common['store_name'];
					$param['fx_goods_state'] = 1;
					$stat = $model_dis->addFenxiaoGoods($param);
					$fx_id = $stat;
				} else {
					$param['goods_name'] = $goods_common['goods_name'];
					$param['goods_image'] = cthumb($goods_common['goods_image'], 360, $goods_common['store_id']);
					$stat = $model_dis->updateFenxiaoGoods(array('fx_id' => $fx_goods['fx_id']), $param);
					$fx_id = $fx_goods['fx_id'];
				}
				$param['goods_price'] = $goods_common['goods_price'];
				$param['fx_id'] = $fx_id;
				$param['fx_url'] = urlShop('fx_goods','index',array('goods_id'=>$fx_id));
				if ($stat) {
					$data['stat'] = 'succ';
					$data['data'] = $param;
					output_data($data);
				} else {
					output_error('所选商品无效', array('goods_state' => '0'));
				}
			} else {
				output_error('所选商品无效!', array('goods_state' => '0'));
			}
		} else {
                output_error('请先认证成为分销员', array('is_fxuser' => '0'));
		}
        
        
    }

    /**
     * 账户余额
     */
    public function commission_infoWt(){
        $model_tard = Model('fx_trad');
        $condition = array();
        $condition['lg_member_id'] = $this->member_info['member_id'];
        $list = $model_tard->getFenxiaoTradList($condition, '*' , 20);
		$page_count = $model_tard->gettotalpage();
        output_data(array('list' => $list), mobile_page($page_count));
        
    }
	
	/**
     * 保存体现账户修改信息
     */
    public function save_memberWt(){
		
		$param = array();
		$param['bill_user_name'] = trim($_POST['bill_user_name']);
		$param['bill_type_number'] = trim($_POST['bill_type_number']);
		$param['bill_type_code'] = trim($_POST['bill_type_code']);
		if($param['bill_type_code'] == 'bank'){
			$param['bill_bank_name'] = trim($_POST['bill_bank_name']);
		}
		
		$obj_validate = new Validate();
		$obj_validate->validateparam = array(
			array("input"=>$param['bill_user_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"收款人姓名不能为空且必须小于50个字"),
			array("input"=>$param['bill_type_number'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"收款账号不能为空且必须小于50个字"),
		);
		if($param['bill_type_code'] == 'bank'){
			$obj_validate->validateparam[] = array("input"=>$param['bill_bank_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"开户银行名称不能为空且必须小于50个字");
		}
		$error = $obj_validate->validate();
		if ($error != ''){
			output_error($error);
		}

		$model_member = Model('member');
		$member_info = $model_member->editMember(array('member_id' => $this->member_info['member_id']),$param);
		if(!$member_info){
			output_error('账户信息更新失败');
		}else{
			output_data('1');
		}
        
    }
	

}