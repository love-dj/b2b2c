<?php
/**
 * 预存款管理
 *

 

 */


defined('ShopWT') or exit('Access Denied By ShopWT');

class seller_predepositControl extends BaseSellerControl {
    public function __construct(){
        parent::__construct();
        Language::read('member_predeposit');
		Language::read('common,member_layout');
		Tpl::output('menu_apply','1');
    }



    /**
     * 预存款变更日志
     */
    public function indexWt(){
        $model_pd = Model('predeposit');
	
		$model_member = Model('member');
		$member_info = $model_member->getMemberInfoByID($_SESSION['member_id']);
		Tpl::output('member_info',$member_info);
        $condition = array();
        $condition['lg_member_id'] = $_SESSION['member_id'];
        $list = $model_pd->getPdLogList($condition,20,'*','lg_id desc');

        //信息输出
        self::profile_menu('log','loglist');
        Tpl::output('show_page',$model_pd->showpage());
        Tpl::output('list',$list);
        Tpl::showpage('predeposit.index');
    }


    /**
     * 申请提现
     */
    public function pd_cash_addWt(){
        if (chksubmit()){
            $obj_validate = new Validate();
            $pdc_amount = abs(floatval($_POST['pdc_amount']));
            $validate_arr[] = array("input"=>$pdc_amount, "require"=>"true",'validator'=>'Compare','operator'=>'>=',"to"=>'0.01',"message"=>Language::get('predeposit_cash_add_pricemin_error'));
            $validate_arr[] = array("input"=>$_POST["pdc_bank_name"], "require"=>"true","message"=>Language::get('predeposit_cash_add_shoukuanbanknull_error'));
            $validate_arr[] = array("input"=>$_POST["pdc_bank_no"], "require"=>"true","message"=>Language::get('predeposit_cash_add_shoukuanaccountnull_error'));
            $validate_arr[] = array("input"=>$_POST["pdc_bank_user"], "require"=>"true","message"=>Language::get('predeposit_cash_add_shoukuannamenull_error'));
            $validate_arr[] = array("input"=>$_POST["password"], "require"=>"true","message"=>'请输入支付密码');
            $obj_validate -> validateparam = $validate_arr;
            $error = $obj_validate->validate();
            if ($error != ''){
                showDialog($error,'','error');
            }

            $model_pd = Model('predeposit');
            $model_member = Model('member');
            $member_info = $model_member->table('member')->where(array('member_id'=> $_SESSION['member_id']))->master(true)->lock(true)->find();
            //验证支付密码
            if (md5($_POST['password']) != $member_info['member_paypwd']) {
                showDialog('支付密码错误','','error');
            }
            //验证金额是否足够
            if (floatval($member_info['available_predeposit']) < $pdc_amount){
                showDialog(Language::get('predeposit_cash_shortprice_error'),'index.php?w=seller_predeposit&t=pd_cash_list','error');
            }
            try {
                $model_pd->beginTransaction();
                $pdc_sn = $model_pd->makeSn();
                $data = array();
                $data['pdc_sn'] = $pdc_sn;
                $data['pdc_member_id'] = $_SESSION['member_id'];
                $data['pdc_member_name'] = $_SESSION['member_name'];
                $data['pdc_amount'] = $pdc_amount;
                $data['pdc_bank_name'] = $_POST['pdc_bank_name'];
                $data['pdc_bank_no'] = $_POST['pdc_bank_no'];
                $data['pdc_bank_user'] = $_POST['pdc_bank_user'];
                $data['pdc_add_time'] = TIMESTAMP;
                $data['pdc_payment_state'] = 0;
                $insert = $model_pd->addPdCash($data);
                if (!$insert) {
                    throw new Exception(Language::get('predeposit_cash_add_fail'));
                }
                //冻结可用预存款
                $data = array();
                $data['member_id'] = $member_info['member_id'];
                $data['member_name'] = $member_info['member_name'];
                $data['amount'] = $pdc_amount;
                $data['order_sn'] = $pdc_sn;
                $model_pd->changePd('cash_apply',$data);
                $model_pd->commit();
                showDialog(Language::get('predeposit_cash_add_success'),'index.php?w=seller_predeposit&t=pd_cash_list','succ');
            } catch (Exception $e) {
                $model_pd->rollback();
                showDialog($e->getMessage(),'index.php?w=seller_predeposit&t=pd_cash_list','error');
            }
        }
    }

    /**
     * 提现列表
     */
    public function pd_cash_listWt(){
        $condition = array();
        $condition['pdc_member_id'] =  $_SESSION['member_id'];
        if (preg_match('/^\d+$/',$_GET['sn_search'])) {
            $condition['pdc_sn'] = $_GET['sn_search'];
        }
        if (isset($_GET['paystate_search'])){
            $condition['pdc_payment_state'] = intval($_GET['paystate_search']);
        }
        $model_pd = Model('predeposit');
        $cash_list = $model_pd->getPdCashList($condition,30,'*','pdc_id desc');

        self::profile_menu('log','cashlist');
        Tpl::output('list',$cash_list);
        Tpl::output('show_page',$model_pd->showpage());
        Tpl::showpage('predeposit.pd_cash_list');
    }

    /**
     * 提现记录详细
     */
    public function pd_cash_infoWt(){
        $pdc_id = intval($_GET["id"]);
        if ($pdc_id <= 0){
            showMessage(Language::get('predeposit_parameter_error'),'index.php?w=seller_predeposit&t=pd_cash_list','html','error');
        }
        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdc_member_id'] = $_SESSION['member_id'];
        $condition['pdc_id'] = $pdc_id;
        $info = $model_pd->getPdCashInfo($condition);
        if (empty($info)){
            showMessage(Language::get('predeposit_record_error'),'index.php?w=seller_predeposit&t=pd_cash_list','html','error');
        }

        self::profile_menu('cashinfo','cashinfo');
        Tpl::output('info',$info);
        Tpl::showpage('predeposit.pd_cash_info');
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key=''){
        $menu_array = array(
            array('menu_key'=>'loglist',        'menu_name'=>'账户余额',    'menu_url'=>'index.php?w=seller_predeposit&t=pd_log_list'),
        );
        switch ($menu_type) {
            case 'rechargeinfo':
                $menu_array[] = array('menu_key'=>'rechargeinfo','menu_name'=>'充值详细',  'menu_url'=>'');
                break;
            case 'cashadd':
                $menu_array[] = array('menu_key'=>'cashadd','menu_name'=>'提现申请',    'menu_url'=>'index.php?w=seller_&t=pd_cash_add');
                break;
            case 'cashinfo':
                $menu_array[] = array('menu_key'=>'cashinfo','menu_name'=>'提现详细',  'menu_url'=>'');
                break;
            case 'log':
            default:
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
