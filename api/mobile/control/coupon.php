<?php
/**
 * 优惠券列表
 *


 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class couponControl extends mobileHomeControl{
    public function __construct()
    {
        parent::__construct();
    }

	    /**
     * 列表
     */
    public function coupon_listWt() {
		$isLogin = 1;
		if($this->getMemberIdIfExists()>0)
		{
			$isLogin = 2;
		}
		
        $model_coupon = Model('coupon');
        //模板状态
        $templatestate_arr = $model_coupon->getTemplateState();
        //领取方式
        $gettype_arr = $model_coupon->getGettypeArr();
        
        $model_member = Model('member');
        //查询会员信息
        //$member_info = $model_member->getMemberInfoByID($_SESSION['member_id']);
                
        //查询优惠券列表
        $where = array();
        $where['coupon_t_gettype']     = $gettype_arr['free']['sign'];
        $where['coupon_t_state']       = $templatestate_arr['usable']['sign'];
        //$where['coupon_t_start_date']  = array('elt',time());
        $where['coupon_t_end_date']    = array('egt',time());
        
        //排序
        $orderby .= 'coupon_t_id desc';
        $rptlist = $model_coupon->getRptTemplateList($where, '*', 0, 18, $orderby);
        $page_count = $model_coupon->gettotalpage();
	if ($rptlist) {
		foreach($rptlist as $k=>$v){
			$v['end_date']  = '有效期至'.date('Y-m-d',$v['coupon_t_end_date']);
			$rptlist[$k] = $v;
		}
	}
      
        
	   output_data(array('coupon_list' => $rptlist,'isLogin'=>$isLogin), mobile_page($page_count));
    }
	
	public function coupon_infoWt(){
		$tid=intval($_GET['tid']);
		if($tid<1)
		{
			output_error('参数错误');
		}
		$isLogin = 1;
		if($this->getMemberIdIfExists()>0)
		{
			$isLogin = 2;
		}
		
        $model_coupon = Model('coupon');
        //模板状态
        $templatestate_arr = $model_coupon->getTemplateState();
        //领取方式
        $gettype_arr = $model_coupon->getGettypeArr();
        
        $model_member = Model('member');
        //查询会员信息
        //$member_info = $model_member->getMemberInfoByID($_SESSION['member_id']);
                
        //查询优惠券列表
        $where = array();
		$where['coupon_t_id']     = $tid;
        $where['coupon_t_gettype']     = $gettype_arr['free']['sign'];
        $where['coupon_t_state']       = $templatestate_arr['usable']['sign'];
        ////$where['coupon_t_start_date']  = array('elt',time());
        $where['coupon_t_end_date']    = array('egt',time());

        
        //排序
        $orderby .= 'coupon_t_id desc';
        $rptlist = $model_coupon->getRptTemplateList($where, '*', 0, 1, $orderby);
        if(empty($rptlist) ||!is_array($rptlist))
		{
			output_error('优惠券已经过期！');
		}else{
			foreach($rptlist as $k=>$v){
				$v['end_date']  = '有效期至'.date('Y-m-d',$v['coupon_t_end_date']);
				$rptlist[$k] = $v;
			}
		}
        
	   output_data(array('coupon_list' => $rptlist,'isLogin'=>$isLogin));
	}

}
