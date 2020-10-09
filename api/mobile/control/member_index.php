<?php
/**
 * 我的商城
 *


 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class member_indexControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 我的商城
     */
    public function indexWt() {
        $member_info = array();
        $member_info['user_name'] = $this->member_info['member_name'];
        $member_info['avatar'] = getMemberAvatarForID($this->member_info['member_id']);

        $member_gradeinfo = Model('member')->getOneMemberGrade(intval($this->member_info['member_exppoints']));
        $member_info['level'] = $member_gradeinfo['level'];
        $member_info['level_name'] = $member_gradeinfo['level_name'];
        $member_info['favorites_store'] = Model('favorites')->getStoreFavoritesCountByMemberId($this->member_info['member_id']);
        $member_info['favorites_goods'] = Model('favorites')->getGoodsFavoritesCountByMemberId($this->member_info['member_id']);
        // 交易提醒
        $model_order = Model('order');
        $member_info['order_nopay_count'] = $model_order->getOrderCountByID('buyer', $this->member_info['member_id'], 'NewCount');
        $member_info['order_noreceipt_count'] = $model_order->getOrderCountByID('buyer', $this->member_info['member_id'], 'SendCount');
        $member_info['order_notakes_count'] = $model_order->getOrderCountByID('buyer', $this->member_info['member_id'], 'TakesCount');
        $member_info['order_noeval_count'] = $model_order->getOrderCountByID('buyer', $this->member_info['member_id'], 'EvalCount');
        
        // 售前退款
        $condition = array();
        $condition['buyer_id'] = $this->member_info['member_id'];
        $condition['refund_state'] = array('lt', 3);
        $member_info['return'] = Model('refund_return')->getRefundReturnCount($condition);
        $member_info['is_fxuser'] = $this->member_info['fx_state'] == 2 ? 1 : 0;
        $model_fx_goods = Model('fx_goods');
        $fx_goods_count = $model_fx_goods->getFenxiaoGoodsCount(array('member_id' => $this->member_info['member_id']));//查看该会员分销商品数量

        $distribution_isuse = Model('setting')->getRowSetting('distribution_isuse');//是否开启三级分销

        $team_isuse = Model('setting')->getRowSetting('team_isuse');//是否开启团队无限级
		
		$buy_return_isuse = Model('setting')->getRowSetting('buy_return_isuse');//看看是否开启单品消费返利
		$full_return_isuse = Model('setting')->getRowSetting('full_return_isuse');//看看是否开启满额消费返利

        $agent_isuse = Model('setting')->getRowSetting('agent_isuse');//是否开启区域代理
		
		
        $shareholder_isuse = Model('setting')->getRowSetting('shareholder_isuse');//是否开启区域代理

        output_data(array('member_info' => $member_info,'distribution_isuse' => $distribution_isuse['value'],'team_isuse' => $team_isuse['value'],'agent_isuse' => $agent_isuse['value'],'buy_return_isuse' => $buy_return_isuse['value'],'full_return_isuse' => $full_return_isuse['value'],'shareholder_isuse' => $shareholder_isuse['value']));
    }
    
    /**
     * 我的资产
     */
    public function my_assetWt() {
        $param = $_GET;
        $fields_arr = array('point','predepoit','available_rc_balance','coupon','voucher');
        $fields_str = trim($param['fields']);
        if ($fields_str) {
            $fields_arr = explode(',',$fields_str);
        }
        $member_info = array();
        if (in_array('point',$fields_arr)) {
            $member_info['point'] = $this->member_info['member_points'];
        }
        if (in_array('predepoit',$fields_arr)) {
            $member_info['predepoit'] = $this->member_info['available_predeposit'];
        }
        if (in_array('available_rc_balance',$fields_arr)) {
            $member_info['available_rc_balance'] = $this->member_info['available_rc_balance'];
        }
        if (in_array('coupon',$fields_arr)) {
            $member_info['coupon'] = Model('coupon')->getCurrentAvailableCouponCount($this->member_info['member_id']);
        }
        if (in_array('voucher',$fields_arr)) {
            $member_info['voucher'] = Model('voucher')->getCurrentAvailableVoucherCount($this->member_info['member_id']);
        }
        output_data($member_info);
    }
    
    /**
     * 我的会员等级
     */
    public function member_gradeWt() {
        $gradeinfo = Model('member')->getOneMemberGrade(intval($this->member_info['member_exppoints']));
        $_info = array();
        $_info['level'] = $gradeinfo['level'];
        $_info['level_name'] = $gradeinfo['level_name'];
        output_data($_info);
    }
	
	public function apply_agent_addWt()
	{
		$param = $_POST;
		if(!$param['agent_level'] || !$param['mob_phone'] || !$param['apply_remark'] || !$param['area_info']){
			output_error('参数错误！');
		}
		
		$member = Model('member_chain')->where('member_id = ' . $this->member_info['member_id'])->find();
		if($member['agent_area_id'] > 0 && $member['agent_check'] == 1){
			output_error('您已经是代理商了，请勿重新提交！');
		}
		
		$is_apply = Model('agent_apply_log')->where('status = 0 AND member_id = ' . $this->member_info['member_id'])->find();
		if($is_apply){
			output_error('您的申请正在审核请勿重新提交！');
		}
		
		
		
		if($param['agent_level'] == 1){
			$area = Model('area')->field('area_parent_id')->where('area_id = ' . $param['area_info'])->find();
			$param['area_info'] = $area['area_parent_id'];
		}
		
		$apply_log_data = array(
			'member_id' => $this->member_info['member_id'],
			'agent_level' => $param['agent_level'],
			'area_info' => $param['area_info'],
			'member_mobile' => $param['mob_phone'],
			'remark' => $param['apply_remark'],
			'createtime' => time(),
			'updatetime' => 0,
			'status' => 0,
		);
		
		Model('agent_apply_log')->insert($apply_log_data);
		
		$member_chain_data = array(
			'agent_area_id' => $param['area_info'],
			'agent_check' => 0,
			'agent_check_time' => 0,
			'agent_apply_time' => time(),
		);
		Model('member_chain')->where('member_id = ' . $this->member_info['member_id'])->update($member_chain_data);
		output_data(array('status'=>'ok'));
	}
}
