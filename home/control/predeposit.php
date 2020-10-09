<?php
/**
 * 预存款管理
 *

 

 */


defined('ShopWT') or exit('Access Denied By ShopWT');

class predepositControl extends BaseMemberControl {
    public function __construct(){
        parent::__construct();
        Language::read('member_predeposit');
    }

    /**
     * 充值添加
     */
    public function recharge_addWt(){
        if (!chksubmit()){
            //信息输出
            self::profile_menu('recharge_add','recharge_add');
            Tpl::showpage('predeposit.pd_add');
            exit();
        }
        $pdr_amount = abs(floatval($_POST['pdr_amount']));
        if ($pdr_amount <= 0) {
            showMessage(Language::get('predeposit_recharge_add_pricemin_error'),'','html','error');
        }
        $model_pdr = Model('predeposit');
        $data = array();
        $data['pdr_sn'] = $pay_sn = $model_pdr->makeSn();
        $data['pdr_member_id'] = $_SESSION['member_id'];
        $data['pdr_member_name'] = $_SESSION['member_name'];
        $data['pdr_amount'] = $pdr_amount;
        $data['pdr_add_time'] = TIMESTAMP;
        $insert = $model_pdr->addPdRecharge($data);
        if ($insert) {
            //转向到商城支付页面
            redirect(BASE_SITE_URL . '/index.php?w=buy&t=pd_pay&pay_sn='.$pay_sn);
        }
    }

    /**
     * 平台充值卡
     */
    public function rechargecard_addWt()
    {
        if (!chksubmit()) {
            self::profile_menu('rechargecard_add','rechargecard_add');
            Tpl::showpage('predeposit.rechargecard_add');
            return;
        }

        $sn = (string) $_POST['rc_sn'];
        if (!$sn || strlen($sn) > 50) {
            showMessage('平台充值卡卡号不能为空且长度不能大于50', '', 'html', 'error');
            exit;
        }

        try {
            model('predeposit')->addRechargeCard($sn, $_SESSION);
            showMessage('平台充值卡使用成功', urlMember('predeposit', 'rcb_log_list'));
        } catch (Exception $e) {
            showMessage($e->getMessage(), '', 'html', 'error');
            exit;
        }
    }

    /**
     * 充值列表
     */
    public function indexWt(){
        $condition = array();
        $condition['pdr_member_id'] = $_SESSION['member_id'];
        if (!empty($_GET['pdr_sn'])) {
            $condition['pdr_sn'] = $_GET['pdr_sn'];
        }

        $model_pd = Model('predeposit');
        $list = $model_pd->getPdRechargeList($condition,20,'*','pdr_id desc');

        self::profile_menu('log','recharge_list');
        Tpl::output('list',$list);
        Tpl::output('show_page',$model_pd->showpage());

        Tpl::showpage('predeposit.pd_list');
    }

    /**
     * 查看充值详细
     *
     */
    public function recharge_showWt(){
        $pdr_id = intval($_GET["id"]);
        if ($pdr_id <= 0){
            showDialog(Language::get('predeposit_parameter_error'),'','error');
        }

        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdr_member_id'] = $_SESSION['member_id'];
        $condition['pdr_id'] = $pdr_id;
        $condition['pdr_payment_state'] = 1;
        $info = $model_pd->getPdRechargeInfo($condition);
        if (!$info){
            showDialog(Language::get('predeposit_record_error'),'','error');
        }
        Tpl::output('info',$info);
        self::profile_menu('rechargeinfo','rechargeinfo');
        Tpl::showpage('predeposit.pd_info');
    }

    /**
     * 删除充值记录
     *
     */
    public function recharge_delWt(){
        $pdr_id = intval($_GET["id"]);
        if ($pdr_id <= 0){
            showDialog(Language::get('predeposit_parameter_error'),'','error');
        }

        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdr_member_id'] = $_SESSION['member_id'];
        $condition['pdr_id'] = $pdr_id;
        $condition['pdr_payment_state'] = 0;
        $result = $model_pd->delPdRecharge($condition);
        if ($result){
            showDialog(Language::get('wt_common_del_succ'),'reload','succ','CUR_DIALOG.close()');
        }else {
            showDialog(Language::get('wt_common_del_fail'),'','error');
        }
    }

    /**
     * 预存款变更日志
     */
    public function pd_log_listWt(){
        $model_pd = Model('predeposit');
        $condition = array();
        $condition['lg_member_id'] = $_SESSION['member_id'];
        $list = $model_pd->getPdLogList($condition,20,'*','lg_id desc');
        //信息输出
        self::profile_menu('log','loglist');
        Tpl::output('show_page',$model_pd->showpage());
        Tpl::output('list',$list);
        Tpl::showpage('predeposit.pd_log_list');
    }

    /**
     * 分销佣金
     */
    public function pd_commission_listWt(){        
        $model_pd = Model('predeposit');
        $condition = array();
        $condition['member_id'] = $_SESSION['member_id'];        
        /****************三级分销奖金触发发放*******************/
        $distribution_settlement_event = Model('setting')->getRowSetting('distribution_settlement_event');//结算事件 1:订单完成；0：订单付款
        $distribution_settlement_days = Model('setting')->getRowSetting('distribution_settlement_days');//结算天数  上面的事件触发后的天数后佣金到账
        //结算未结算的佣金
        $no_list = Model()->table('distribution_order,orders')->field('distribution_order.*,orders.order_sn,orders.order_amount,orders.order_state,orders.refund_state')->join('left')->on('distribution_order.order_id=orders.order_id')->where("order_state > 10 and status = 0 and (commission_one_uid = {$condition['member_id']} or commission_two_uid = {$condition['member_id']} or commission_three_uid = {$condition['member_id']})")->select();
        $no_pay_commission = 0;//待结算佣金
        foreach($no_list as $nl){
            if($refund_state == 0){ 
                $do_pay = false;   
                $now_time = TIMESTAMP;//获取当前时间
                $o_time = $now_time - $nl['order_time'];//订单时间跟当前时间的时间差
                $pay_times = $distribution_settlement_days['value'] * 86400;//系统要求的发放时间
                if($nl['order_state'] == 40 && $o_time >= $pay_times){//已收货，订单完成，不管怎样，佣金都该发放
                    $do_pay = true;
                }
                if($nl['order_state'] == 20 || $nl['order_state'] == 30){                   
                    //后台设置如果是付款后结算的话，那么必须还要满足一个条件，就是佣金到账时间
                    if($distribution_settlement_event['value'] == 0 && $o_time >= $pay_times){
                        $do_pay = true;
                    }
                }
                if($do_pay){//开始结算佣金                      
                    try {              
                        //开始发放订单佣金，三级都发
                        $model_pd->beginTransaction();
                        $distribution_order['status'] = 1;//0：未发放；1：已发放；2：已扣除
                        $distribution_order['settle_time'] = $nl['order_time'] + $distribution_settlement_days['value'] * 86400; //用户自己触发，没做自动发放，所以这里伪装一下时间
                        $d_condition['id'] = $nl['id'];
                        $o_result = $model_pd->editDistributionOrder($distribution_order,$d_condition);//更新订单信息

                        //一级
                        $data = array();
                        $data['member_id'] = $nl['commission_one_uid'];
                        $data['member_name'] = '会员编号：'.$nl['buyer_id'];
                        $data['amount'] = $nl['commission_one_level'];
                        $data['order_sn'] = $nl['order_sn'];
                        $model_pd->changePd('order_distribution',$data);
                        //二级
                        $data['member_id'] = $nl['commission_two_uid'];
                        $data['amount'] = $nl['commission_two_level'];
                        $model_pd->changePd('order_distribution',$data);
                        //三级
                        $data['member_id'] = $nl['commission_three_uid'];
                        $data['amount'] = $nl['commission_three_level'];
                        $model_pd->changePd('order_distribution',$data);
                        $model_pd->commit();
                    } catch (Exception $e) {
                        $model_pd->rollback();
                    }
                }else{
                    //累计待结算佣金
                    if($nl['commission_one_uid'] == $_SESSION['member_id']){
                        $no_pay_commission += $nl['commission_one_level'];
                    }else if($nl['commission_two_uid'] == $_SESSION['member_id']){
                        $no_pay_commission += $nl['commission_two_level'];
                    }else if($nl['commission_three_uid'] == $_SESSION['member_id']){
                        $no_pay_commission += $nl['commission_three_level'];
                    }else{
                        $no_pay_commission += 0;
                    }
                }
            }
        }
        /****************三级分销奖金触发发放*******************/
        $list = $model_pd->getCommissionList($condition, $this->page,'order_time desc');
        foreach($list as $k=>$v){
            if($v['commission_one_uid'] == $_SESSION['member_id']){

                $list[$k]['user_commission'] = $v['commission_one_level'];

            }else if($v['commission_two_uid'] == $_SESSION['member_id']){

                $list[$k]['user_commission'] = $v['commission_two_level'];

            }else if($v['commission_three_uid'] == $_SESSION['member_id']){

                $list[$k]['user_commission'] = $v['commission_three_level'];

            }else{
                $list[$k]['user_commission'] = 0;
            }
        }

        $distribution_billing_option = Model('setting')->getRowSetting('distribution_billing_option');//佣金计算去向 提现 or 积分
        //信息输出
        self::profile_menu('log','commissionlist');
        Tpl::output('show_page',$model_pd->showpage());
        Tpl::output('list',$list);
        Tpl::output('billing_option',$distribution_billing_option['value']);
        Tpl::output('no_pay_commission',$no_pay_commission);
        Tpl::showpage('predeposit.pd_commission_list');
    }

    /**
     * 充值卡余额变更日志
     */
    public function rcb_log_listWt()
    {
        $model = Model();
        $list = $model->table('rcb_log')->where(array(
            'member_id' => $_SESSION['member_id'],
        ))->page(20)->order('id desc')->select();

        //信息输出
        self::profile_menu('log', 'rcb_log_list');
        Tpl::output('show_page', $model->showpage());
        Tpl::output('list', $list);
        Tpl::showpage('predeposit.rcb_log_list');
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
                showDialog(Language::get('predeposit_cash_shortprice_error'),'index.php?w=predeposit&t=pd_cash_list','error');
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
                showDialog(Language::get('predeposit_cash_add_success'),'index.php?w=predeposit&t=pd_cash_list','succ');
            } catch (Exception $e) {
                $model_pd->rollback();
                showDialog($e->getMessage(),'index.php?w=predeposit&t=pd_cash_list','error');
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
            showMessage(Language::get('predeposit_parameter_error'),'index.php?w=predeposit&t=pd_cash_list','html','error');
        }
        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdc_member_id'] = $_SESSION['member_id'];
        $condition['pdc_id'] = $pdc_id;
        $info = $model_pd->getPdCashInfo($condition);
        if (empty($info)){
            showMessage(Language::get('predeposit_record_error'),'index.php?w=predeposit&t=pd_cash_list','html','error');
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
            array('menu_key'=>'loglist',        'menu_name'=>'账户余额',    'menu_url'=>'index.php?w=predeposit&t=pd_log_list'),
            array('menu_key'=>'recharge_list',  'menu_name'=>'充值明细',    'menu_url'=>'index.php?w=predeposit&t=index'),
            array('menu_key'=>'cashlist',       'menu_name'=>'余额提现',    'menu_url'=>'index.php?w=predeposit&t=pd_cash_list'),
            array('menu_key'=>'rcb_log_list',   'menu_name'=>'充值卡余额',   'menu_url'=>'index.php?w=predeposit&t=rcb_log_list'),
        );
        $distribution_isuse = Model('setting')->getRowSetting('distribution_isuse');//看看是否开启分销
        if($distribution_isuse['value'] == 1){
            $menu_array[] = array('menu_key'=>'commissionlist', 'menu_name'=>'分销佣金',    'menu_url'=>'index.php?w=predeposit&t=pd_commission_list');
        }
        switch ($menu_type) {
            case 'rechargeinfo':
                $menu_array[] = array('menu_key'=>'rechargeinfo','menu_name'=>'充值详细',  'menu_url'=>'');
                break;
            case 'recharge_add':
                $menu_array[] = array('menu_key'=>'recharge_add','menu_name'=>'在线充值',   'menu_url'=>'');
                break;
            case 'rechargecard_add':
                $menu_array[] = array('menu_key'=>'rechargecard_add','menu_name'=>'充值卡充值','menu_url'=>'javascript:;');
                break;
            case 'cashadd':
                $menu_array[] = array('menu_key'=>'cashadd','menu_name'=>'提现申请',    'menu_url'=>'index.php?w=predeposit&t=pd_cash_add');
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
