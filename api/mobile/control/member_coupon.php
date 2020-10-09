<?php
/**
 * 我的优惠券
 *


 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class member_couponControl extends mobileMemberControl {
    private $coupon_state_arr;

    public function __construct() {
        parent::__construct();
        //判断系统是否开启优惠券功能
        if (C('coupon_allow') != 1){
            output_error('系统未开启优惠券功能');
        }
        $model_coupon = Model('coupon');
        $this->coupon_state_arr = $model_coupon->getCouponState();
    }
    /*
     * 我的优惠券列表
     */
    public function coupon_listWt(){
        $param = $_POST;
        $model_coupon = Model('coupon');
        //更新优惠券过期状态
        $model_coupon->updateCouponExpire($this->member_info['member_id']);
        //查询优惠券
        $where = array();
        $where['coupon_owner_id'] = $this->member_info['member_id'];
        $rp_state_select = trim($param['rp_state']);
        if ($rp_state_select){
            $where['coupon_state'] = $this->coupon_state_arr[$rp_state_select]['sign'];
        }
        $coupon_list = $model_coupon->getCouponList($where, '*', 0, $this->page, 'coupon_state asc,coupon_id desc');
        $page_count = $model_coupon->gettotalpage();
        output_data(array('coupon_list' => $coupon_list), mobile_page($page_count));
    }
    /**
     * 卡密领取优惠券
     */
    public function rp_pwexWt(){
        $param = $_POST;
        $pwd_code = trim($param["pwd_code"]);
        if (!$pwd_code) {
            output_error('请输入优惠券卡密');
        }
        if (!Model('apivercode')->checkApiVercode($param["codekey"],$param['captcha'])) {
            output_error('验证码错误');
        }
        //查询优惠券
        $model_coupon = Model('coupon');
        $coupon_info = $model_coupon->getCouponInfo(array('coupon_pwd'=>md5($pwd_code)));
        if(!$coupon_info){
            output_error('优惠券卡密错误');
        }
        if($coupon_info['coupon_owner_id'] > 0){
            output_error('该优惠券卡密已被使用');
        }
        $where = array();
        $where['coupon_id'] = $coupon_info['coupon_id'];
        $update_arr = array();
        $update_arr['coupon_owner_id'] = $this->member_info['member_id'];
        $update_arr['coupon_owner_name'] = $this->member_info['member_name'];
        $update_arr['coupon_active_date'] = time();
        $result = $model_coupon->editCoupon($where, $update_arr, $this->member_info['member_id']);
        if($result){
            //更新优惠券模板
            $update_arr = array();
            $update_arr['coupon_t_giveout'] = array('exp','coupon_t_giveout+1');
            $model_coupon->editRptTemplate(array('coupon_t_id'=>$coupon_info['coupon_t_id']),$update_arr);
            output_data('1');
        } else {
            output_error('优惠券领取失败');
        }
    }
	/**
     * 兑换优惠保存信息
     */
    public function getcouponWt(){
        
        $tid = intval($_REQUEST['tid']);
        if ($tid <= 0){
            output_error('优惠信息错误');
        }
        $model_coupon = Model('coupon');
        //验证是否可以兑换优惠
        $data = $model_coupon->getCanChangeTemplateInfo($tid,$this->member_info['member_id']);
        if ($data['state'] == false){
			 output_error($data['msg']);
        }
        //添加优惠信息
        $data = $model_coupon->exchangeCoupon($data['info'],$this->member_info['member_id'],$this->member_info['member_name']);
        if ($data['state'] == true){
            output_data('1');
        } else {
            throw new Exception($data['msg']);
        }
    }
}
