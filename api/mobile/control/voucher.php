<?php
/**
 * 店铺
 *


 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class voucherControl extends mobileHomeControl{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 代金券列表
     */
    public function voucher_tpl_listWt(){
        $param = $_REQUEST;

        $model_voucher = Model('voucher');
        $templatestate_arr = $model_voucher->getTemplateState();
        $voucher_gettype_array = $model_voucher->getVoucherGettypeArray();

        $where = array();
        $where['voucher_t_state'] = $templatestate_arr['usable'][0];
        $store_id = intval($param['store_id']);
        if ($store_id > 0){
            $where['voucher_t_store_id'] = $store_id;
        }
        $where['voucher_t_gettype'] = array('in',array($voucher_gettype_array['points']['sign'],$voucher_gettype_array['free']['sign']));
        if ($param['gettype'] && in_array($param['gettype'], array('points','free'))) {
            $where['voucher_t_gettype'] = $voucher_gettype_array[$param['gettype']]['sign'];
        }
        $order = 'voucher_t_id asc';
        $voucher_list = $model_voucher->getVoucherTemplateList($where, '*', 20, 0, $order);
        if ($voucher_list) {
            foreach($voucher_list as $k=>$v){
                $v['voucher_t_end_date_text'] = $v['voucher_t_end_date']?@date('Y年m月d日',$v['voucher_t_end_date']):'';
                $voucher_list[$k] = $v;
            }
        }
        output_data(array('voucher_list' => $voucher_list));
    }

	    /**
     * 列表
     */
    public function voucher_listWt() {
        $model_voucher = Model('voucher');
		$isLogin = 1;
		if($this->getMemberIdIfExists()>0)
		{
			$isLogin = 2;
		}
        //代金券模板状态
        $templatestate_arr = $model_voucher->getTemplateState();

        $model_member = Model('member');
        //查询会员信息
        //$member_info = $model_member->getMemberInfoByID($_SESSION['member_id']);
                
        //查询代金券列表
        $where = array();
        $gettype_arr = $model_voucher->getVoucherGettypeArray();
        $where['voucher_t_gettype'] = $gettype_arr['free']['sign'];
        $where['voucher_t_state'] = $templatestate_arr['usable'][0];
        $where['voucher_t_end_date'] = array('gt',time());
        if (intval($_GET['sc_id']) > 0){
            $where['voucher_t_sc_id'] = intval($_GET['sc_id']);
        }

        $store_id = intval($_GET['store_id']);
        if ($store_id > 0) {
            $where['voucher_t_store_id'] = $store_id;
        }

        
        //排序
        $orderby = 'voucher_t_points desc,';
        
        $orderby .= 'voucher_t_id desc';
        $voucherlist = $model_voucher->getVoucherTemplateList($where, '*', 0, 18, $orderby);
        $page_count = $model_voucher->gettotalpage();
	if ($voucherlist) {
		foreach($voucherlist as $k=>$v){
			$v['end_date']  = '有效期至'.date('Y-m-d',$v['voucher_t_end_date']);
			$voucherlist[$k] = $v;
		}
         }
        if ($store_id <= 0) {
        
            //查询店铺分类
            $store_class = rkcache('store_class', true);
            Tpl::output('store_class', $store_class);
        }
        
	   output_data(array('voucher_list' => $voucherlist,'isLogin'=>$isLogin), mobile_page($page_count));
    }
	
	public function voucher_infoWt() {
		$tid=intval($_GET['tid']);
		if($tid<1)
		{
			output_error('参数错误');
		}
        $model_voucher = Model('voucher');
		$isLogin = 1;
		if($this->getMemberIdIfExists()>0)
		{
			$isLogin = 2;
		}
        //代金券模板状态
        $templatestate_arr = $model_voucher->getTemplateState();

        $model_member = Model('member');
              
        //查询代金券列表
        $where = array();
        $gettype_arr = $model_voucher->getVoucherGettypeArray();
        $where['voucher_t_gettype'] = $gettype_arr['free']['sign'];
        $where['voucher_t_state'] = $templatestate_arr['usable'][0];
        $where['voucher_t_end_date'] = array('gt',time());
        $where['voucher_t_id'] = intval($_GET['tid']);
        
        //排序
       
        $orderby = 'voucher_t_id desc';
        $voucherlist = $model_voucher->getVoucherTemplateList($where, '*', 0, 1, $orderby);
       if(empty($voucherlist) ||!is_array($voucherlist))
		{
			output_error('代金券已经过期！');
		}else{
			foreach($voucherlist as $k=>$v){
				$v['end_date']  = '有效期至'.date('Y-m-d',$v['voucher_t_end_date']);
				$voucherlist[$k] = $v;
			}
		}
       
	   output_data(array('voucher_list' => $voucherlist,'isLogin'=>$isLogin));
    }
}
