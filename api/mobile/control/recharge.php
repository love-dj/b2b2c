<?php
/**

 *
 *
 *
 *
 * 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class rechargeControl extends mobileMemberControl {

	public function __construct(){
		parent::__construct();
	}

	/**
	 * 写充值信息
	 */
	public function indexWt(){
        $pdr_amount = abs(floatval($_POST['pdr_amount']));
		if ($pdr_amount <= 0) {		    
			output_error('充值金额不正确!');
		}
		else{
			$model_pdr = Model('predeposit');
			$data = array();
			$data['pdr_sn'] = $pay_sn = $model_pdr->makeSn();
			$data['pdr_member_id'] = $this->member_info['member_id'];
			$data['pdr_member_name'] = $this->member_info['member_name'];
			$data['pdr_amount'] = $pdr_amount;
			$data['pdr_add_time'] = TIMESTAMP;
			$insert = $model_pdr->addPdRecharge($data);
			if ($insert) {
				output_data(array('pay_sn' => $pay_sn));
				
			}
			else{
				output_error('提交失败!');
			}
		}
		
    }
	/**
	 * 申请提现
	 */
	public function pd_cash_addWt(){
	
		$obj_validate = new Validate();
		$pdc_amount = abs(floatval($_POST['pdc_amount']));
		$validate_arr[] = array("input"=>$pdc_amount, "require"=>"true",'validator'=>'Compare','operator'=>'>=',"to"=>'0.01',"message"=>'提现金额不正确');
		$validate_arr[] = array("input"=>$_POST["pdc_bank_name"], "require"=>"true","message"=>'请输入收款银行');
		$validate_arr[] = array("input"=>$_POST["pdc_bank_no"], "require"=>"true","message"=>'请输入收款账号');
		$validate_arr[] = array("input"=>$_POST["pdc_bank_user"], "require"=>"true","message"=>'请输入开户人姓名');
		$validate_arr[] = array("input"=>$_POST["password"], "require"=>"true","message"=>'请输入支付密码');
		$validate_arr[] = array("input"=>$_POST["mobilenum"], "require"=>"true","message"=>'请输入手机号码');
		$obj_validate -> validateparam = $validate_arr;
		$error = $obj_validate->validate();
		if ($error != ''){
			output_error($error);
		}

		$model_pd = Model('predeposit');
		$model_member = Model('member');
		$memberinfo = $model_member->getMemberInfoByID($this->member_info['member_id']);
		//验证支付密码
		if (md5($_POST['password']) != $memberinfo['member_paypwd']) {
			output_error('支付密码错误');
		}
		//验证金额是否足够
		if (floatval($memberinfo['available_predeposit']) < $pdc_amount){
			output_error('金额不足本次提现');
		}
		try {
			$model_pd->beginTransaction();
			$pdc_sn = $model_pd->makeSn();
			$data = array();
			$data['pdc_sn'] = $pdc_sn;
			$data['pdc_member_id'] = $memberinfo['member_id'];
			$data['pdc_member_name'] = $memberinfo['member_name'];
			$data['pdc_amount'] = $pdc_amount;
			$data['pdc_bank_name'] = $_POST['pdc_bank_name'];
			$data['pdc_bank_no'] = $_POST['pdc_bank_no'];
			$data['pdc_bank_user'] = $_POST['pdc_bank_user'];
			$data['pdc_add_time'] = TIMESTAMP;
			$data['pdc_payment_state'] = 0;
			$data['mobilenum'] = $_POST['mobilenum'];
			$insert = $model_pd->addPdCash($data);
			if (!$insert) {
				output_error('提交失败！');
			}
			//冻结可用预存款
			$data = array();
			$data['member_id'] = $memberinfo['member_id'];
			$data['member_name'] = $memberinfo['member_name'];
			$data['amount'] = $pdc_amount;
			$data['order_sn'] = $pdc_sn;
			$model_pd->changePd('cash_apply',$data);
			$model_pd->commit();
			output_data(array('status'=>'ok'));
			
		} catch (Exception $e) {
			$model_pd->rollback();
			output_error('系统繁忙，提交失败');
		}
		
	}
	public function recharge_orderWt()
	{
		$pay_sn	= $_POST['pay_sn'];
        if (!preg_match('/^\d{18}$/',$pay_sn)){
           output_error('订单号错误!');
		   exit();
        }

        //查询支付单信息
        $model_order= Model('predeposit');
        $pd_info = $model_order->getPdRechargeInfo(array('pdr_sn'=>$pay_sn,'pdr_member_id'=>$this->member_info['member_id']));
        if(empty($pd_info)){
            output_error('订单不存在!');
		   exit();
        }
        if (intval($pd_info['pdr_payment_state'])) {
			output_error('您的订单已经支付，请勿重复支付!');
		   exit();
        }
       	
		
		$model_mb_payment = Model('mb_payment');

		$payment_list = $model_mb_payment->getMbPaymentOpenList();
		$payment_array = array();
		if(!empty($payment_list)) {
			foreach ($payment_list as $value) {
				$payment_array[] = array('payment_code' => $value['payment_code'],'payment_name' =>$value['payment_name']);
			}
			
		}
		else{
			output_error('暂未找到合适的支付方式!');
			exit();
		}
		unset($pd_info['pdr_payment_code']);
		unset($pd_info['pdr_payment_name']);
		unset($pd_info['pdr_trade_sn']);
		unset($pd_info['pdr_payment_state']);
		unset($pd_info['pdr_payment_time']);
		unset($pd_info['pdr_admin']);
		output_data(array('payment_list'=>$payment_array,'pdinfo'=>$pd_info));
	}
	
	public function member_vWt() {

        $member_info = array();


        $member_info['user_name'] = $this->member_info['member_name'];

        $member_info['avator'] = getMemberAvatarForID($this->member_info['member_id']);

		$member_info['point'] = $this->member_info['member_points'];

        $member_gradeinfo = Model('member')->getOneMemberGrade(intval($this->member_info['member_exppoints']));

        $member_info['level_name'] = $member_gradeinfo['level_name'];

	 

        $member_info['favorites_store'] = Model('favorites')->getStoreFavoritesCountByMemberId($this->member_info['member_id']);

       	$member_info['favorites_goods'] = Model('favorites')->getGoodsFavoritesCountByMemberId($this->member_info['member_id']);



		$member_info['member_id']            = $this->member_info['member_id'];//

		$member_info['member_id_64']         = base64_encode(intval($this->member_info['member_id'])*1);//
		$model_setting = Model('setting');
		$list_setting = $model_setting->getListSetting();
		$member_info['vip_1fee']            = $list_setting['vip_1fee'];
		$member_info['vip_2fee']            = $list_setting['vip_2fee'];
		output_data(array('member_info' => $member_info));

    }
	
	/**

	 * 在线升级到会员级别

	 */

	public function recharge_vip1Wt(){
		$pdr_amount = abs(floatval($_POST['pdr_amount']));
		$model_setting = Model('setting');
		$list_setting = $model_setting->getListSetting();
		if ($pdr_amount <= 0||$pdr_amount != abs(floatval($list_setting['vip_1fee']))) {

		    output_error('金额参数错误!');
			exit();

		}

        $model_pdr = Model('predeposit');

        $data = array();

        $data['pdr_sn'] = $pay_sn = $model_pdr->makeSn();

        $data['pdr_member_id'] = $this->member_info['member_id'];

        $data['pdr_member_name'] = $this->member_info['member_name'];

        $data['pdr_amount'] = $pdr_amount;

        $data['pdr_add_time'] = TIMESTAMP;

		$data['pdr_vipid'] = '1';

        $insert = $model_pdr->addVipRecharge($data);

        if ($insert) {
            output_data(array('pay_sn' => $pay_sn));
        }
		else{
			
		    output_error('参数错误!');
		}

	}
	
	
	public function recharge_vip2Wt(){
		$pdr_amount = abs(floatval($_POST['pdr_amount']));
		$model_setting = Model('setting');
		$list_setting = $model_setting->getListSetting();
		if ($pdr_amount <= 0||$pdr_amount != abs(floatval($list_setting['vip_2fee']))) {

		    output_error('金额参数错误!');
			exit();

		}

        $model_pdr = Model('predeposit');

        $data = array();

        $data['pdr_sn'] = $pay_sn = $model_pdr->makeSn();

        $data['pdr_member_id'] = $this->member_info['member_id'];

        $data['pdr_member_name'] = $this->member_info['member_name'];

        $data['pdr_amount'] = $pdr_amount;

        $data['pdr_add_time'] = TIMESTAMP;

		$data['pdr_vipid'] = '2';

        $insert = $model_pdr->addVipRecharge($data);

        if ($insert) {
            output_data(array('pay_sn' => $pay_sn));
        }
		else{
			
		    output_error('参数错误!');
		}

	}
	
	
	public function viprecharge_orderWt()
	{
		$pay_sn	= $_POST['pay_sn'];
        if (!preg_match('/^\d{18}$/',$pay_sn)){
           output_error('订单号错误!');
		   exit();
        }

        //查询支付单信息
        $model_order= Model('predeposit');
        $pd_info = $model_order->getVipRechargeInfo(array('pdr_sn'=>$pay_sn,'pdr_member_id'=>$this->member_info['member_id']));
        if(empty($pd_info)){
            output_error('订单不存在!');
		   exit();
        }
        if (intval($pd_info['pdr_payment_state'])) {
			output_error('您的订单已经支付，请勿重复支付!');
		   exit();
        }
       	
		
		$model_mb_payment = Model('mb_payment');

		$payment_list = $model_mb_payment->getMbPaymentOpenList();
		$payment_array = array();
		if(!empty($payment_list)) {
			foreach ($payment_list as $value) {
				$payment_array[] = array('payment_code' => $value['payment_code'],'payment_name' =>$value['payment_name']);
			}
			
		}
		else{
			output_error('暂未找到合适的支付方式!');
			exit();
		}
		unset($pd_info['pdr_payment_code']);
		unset($pd_info['pdr_payment_name']);
		unset($pd_info['pdr_trade_sn']);
		unset($pd_info['pdr_payment_state']);
		unset($pd_info['pdr_payment_time']);
		unset($pd_info['pdr_admin']);
		output_data(array('payment_list'=>$payment_array,'pdinfo'=>$pd_info));
	}
    
}
