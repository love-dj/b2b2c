<?php
/**
 * 领取免费优惠券
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class couponControl extends BaseHomeControl{
    public function __construct() {
        parent::__construct();
        //判断系统是否开启优惠券功能
        if (C('coupon_allow') != 1){
            showDialog('系统未开启优惠券功能','index.php','error');
        }
        parent::checkLogin();
    }
    /**
     * 免费优惠券页面
     */
    public function getcouponWt() {
        $t_id = intval($_GET['tid']);
        $error_url = getReferer();
        if (!$error_url){
            $error_url = 'index.php';
        }
        if($t_id <= 0){
            showDialog('优惠券信息错误',$error_url,'error');
        }
        $model_coupon = Model('coupon');
        //获取领取方式
        $gettype_array = $model_coupon->getGettypeArr();
        //获取优惠券状态
        $templatestate_arr = $model_coupon->getTemplateState();
        //查询优惠券模板详情
        $where = array();
        $where['coupon_t_id'] = $t_id;
        $where['coupon_t_gettype'] = $gettype_array['free']['sign'];
        $where['coupon_t_state'] = $templatestate_arr['usable']['sign'];
        //$where['coupon_t_start_date'] = array('elt',time());
        $where['coupon_t_end_date'] = array('egt',time());
        $template_info = $model_coupon->getRptTemplateInfo($where);
        if (empty($template_info)){
            showDialog('优惠券信息错误',$error_url,'error');
        }
        if ($template_info['coupon_t_total']<=$template_info['coupon_t_giveout']){//优惠券不存在或者已兑换完
            showDialog('优惠券已兑换完',$error_url,'error');
        }
        TPL::output('template_info',$template_info);
        Tpl::showpage('coupon.getcoupon');
    }
    /**
     * 领取免费优惠券
     */
    public function getcouponsaveWt() {
        $t_id = intval($_GET['tid']);
        if($t_id <= 0){
            showDialog('优惠券信息错误','','error');
        }
        $model_coupon = Model('coupon');
        //验证是否可领取优惠券
        $data = $model_coupon->getCanChangeTemplateInfo($t_id, intval($_SESSION['member_id']));
        if ($data['state'] == false){
            showDialog($data['msg'], '', 'error');
        }
        try {
            $model_coupon->beginTransaction();
            //添加优惠券信息
            $data = $model_coupon->exchangeCoupon($data['info'], $_SESSION['member_id'], $_SESSION['member_name']);
            if ($data['state'] == false) {
                throw new Exception($data['msg']);
            }
            $model_coupon->commit();
            showDialog('优惠券领取成功', MEMBER_SITE_URL.'/index.php?w=member_coupon&t=index', 'succ');
        } catch (Exception $e) {
            $model_coupon->rollback();
            showDialog($e->getMessage(), '', 'error');
        }
        
    }
}
