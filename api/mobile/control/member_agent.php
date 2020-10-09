<?php
/**
 * 区域代理
 */


defined('ShopWT') or exit('Access Denied By ShopWT');

class member_agentControl extends mobileagentControl {
    function __construct()
    {
        parent::__construct();
    }
	/**
     * 我的代理区域
     */
    public function indexWt() {
        /****************区域分红奖金触发发放*******************/          
        $model_pd = Model('predeposit');
        $agent_settlement_event = Model('setting')->getRowSetting('agent_settlement_event');//结算事件 1:订单完成；0：订单付款
        $agent_settlement_days = Model('setting')->getRowSetting('agent_settlement_days');//结算天数  上面的事件触发后的天数后佣金到账
        //结算未结算的佣金
        $no_list = Model()->table('agent_order,orders')->field('agent_order.*,orders.order_sn,orders.order_amount,orders.order_state,orders.refund_state')->join('left')->on('agent_order.order_id=orders.order_id')->where("order_state > 10 and status = 0 and find_in_set({$this->member_info['member_id']},commission_uid)")->select();
        $no_pay_commission = 0;//待结算佣金
        foreach($no_list as $nl){
            if($refund_state == 0){ 
                $do_pay = false;   
                $now_time = TIMESTAMP;//获取当前时间
                $o_time = $now_time - $nl['order_time'];//订单时间跟当前时间的时间差
                $pay_times = $agent_settlement_days['value'] * 86400;//系统要求的发放时间
                if($nl['order_state'] == 40 && $o_time >= $pay_times){//已收货，订单完成，不管怎样，佣金都该发放
                    $do_pay = true;
                }
                if($nl['order_state'] == 20 || $nl['order_state'] == 30){                   
                    //后台设置如果是付款后结算的话，那么必须还要满足一个条件，就是佣金到账时间
                    if($agent_settlement_event['value'] == 0 && $o_time >= $pay_times){
                        $do_pay = true;
                    }
                }
                $commission_list = unserialize($nl['commission_list']);
                
                if($do_pay){//开始结算佣金                      
                    try {              
                        //开始发放订单佣金，三级都发
                        $model_pd->beginTransaction();
                        $agent_order['status'] = 1;//0：未发放；1：已发放；2：已扣除
                        $agent_order['settle_time'] = $nl['order_time'] + $agent_settlement_days['value'] * 86400; //用户自己触发，没做自动发放，所以这里伪装一下时间
                        $d_condition['id'] = $nl['id'];
                        $o_result = $model_pd->editAgentOrder($agent_order,$d_condition);//更新订单信息
                        /*({s:7:"area_id";s:1:"2";s:10:"commission";d:1.2;s:11:"agent_count";i:1;s:13:"ta_commission";s:4:"1.20";}}*/
                        foreach($commission_list as $k1=>$v1){
                            if($v1['ta_commission'] > 0){//如果有提成，并且没有超过等级相应提成层级的
                                $data = array();
                                $data['member_id'] = $k1;
                                $data['member_name'] = '会员编号：'.$nl['buyer_id'];
                                $data['amount'] = $v1['ta_commission'];
                                $data['order_amount'] = $n1['order_amount'];
                                $data['order_sn'] = $nl['order_sn'];
                                $model_pd->changePd('order_agent',$data);
                            }
                        }                        
                        $model_pd->commit();
                    } catch (Exception $e) {
                        $model_pd->rollback();
                    }
                }else{
                    //累计待结算佣金                    
                    foreach($commission_list as $k1=>$v1){
                        if($k1 == $this->member_info['member_id']){
                            if($v1['ta_commission'] > 0){//如果有提成，并且没有超过等级相应提成层级的
                                $no_pay_commission += $v1['ta_commission'];
                            }else{                        
                                $no_pay_commission += 0;//否则没佣金
                            }
                        }
                    }
                }
            }
        }
        /****************区域分红奖金触发发放*******************/

        $member_info = Model('member')->getMemberInfoByID($this->member_info['member_id'], 'member_id,trad_amount,user_name');
        $member_info['avatar'] = getMemberAvatarForID($this->member_info['member_id']);
        $member_chain_info = Model('member')->getMemberChainInfo(array('member_id'=>$this->member_info['member_id']),'agent_area_id,agent_check_time,area_cost_money,area_commission');
		
        $member_info['available_fx_trad'] = wtPriceFormat($member_chain_info['area_commission']);
        $member_info['freeze_fx_trad'] = $no_pay_commission;
        $area_name = Model('area')->getTopAreaName($member_chain_info['agent_area_id']);   
        $member_info['area_name'] = $area_name;
		$tx_cash = Model('pd_cash')->field('sum(pdc_amount) as tx_money')->where(array('pdc_member_id'=>$this->member_info['member_id'],'pdc_payment_state'=>1,'pdc_type'=>3))->find();
        $member_info['tx_cash'] = $tx_cash['tx_money']>0?$tx_cash['tx_money']:0;

        output_data(array('member_info' => $member_info));
    }


    /**
     * 获取区域订单列表
     */
    public function agent_orderWt(){      
        $model_pd = Model('predeposit');
        $condition = array('member_id' => $this->member_info['member_id']);//$this->page
        $order_list = $model_pd->getAgentCommissionList($condition, $this->page,'order_time desc');
        foreach($order_list as $k=>$v){
            $commission_list = unserialize($v['commission_list']);
            foreach($commission_list as $k1=>$v1){
                if($k1 == $this->member_info['member_id']){
                    if($v1['ta_commission'] > 0){//如果有提成，并且没有超过等级相应提成层级的
                        $order_list[$k]['user_commission'] = $v1['ta_commission'];
                    }else{                        
                        $order_list[$k]['user_commission'] = 0;//否则没佣金
                    }
                }
            }
            $order_list[$k]['order_time'] = date('Y-m-d H:i:s',$v['order_time']);
            if($v['status'] == 1){
                $order_list[$k]['status'] = '已结算';
                $order_list[$k]['settle_time'] = date("Y-m-d H:i:s",$v['settle_time']);
            }else{
                $order_list[$k]['status'] = '待结算';
                $order_list[$k]['settle_time'] = '等待结算';
            }
        }
        $order_count = $model_pd->getAgentCommissionCount($condition);
        $page_count = ceil($order_count/$this->page);
        output_data(array('order_list' => $order_list), mobile_page($page_count));
    }

    /**
     * 获取区域树
     */
    public function subuser_listWt(){
        $model_invite = Model('member');
        $tj_level = intval($_POST['tj_type']);
        if($tj_level == 3){            
            $condition['invite_three'] = $this->member_info['member_id'];
        }else if($tj_level == 2){
            $condition['invite_two'] = $this->member_info['member_id'];
        }else{
            $condition['invite_one'] = $this->member_info['member_id'];
        }
        $invite_list = $model_invite->getMemberList($condition, '*', $this->page);
        $invite_count = $model_invite->getMemberCount($condition);
        $page_count = ceil($invite_count/$this->page);
        output_data(array('invite_list' => $invite_list), mobile_page($page_count));
    }	
	

    /**
     * 我的佣金
     */
    public function my_assetWt() {
        $member_info = array();
        if($this->member_info['member_id'] > 0){
            //$member_info = Model('member')->getMemberInfoByID($this->member_info['member_id'], 'member_id,trad_amount,user_name');
			$member_info = Model('member')->getMemberChainInfo(array('member_id'=>$this->member_info['member_id']),'agent_area_id,member_id,area_commission');
        }
        output_data($member_info);
    }

    /**
     * 提现记录
     */
    public function pdcashlistWt(){
        $where = array();
        $where['pdc_member_id'] =  $this->member_info['member_id'];
        $where['pdc_type'] =  3;//提现类型（0：余额；1：三级分销佣金；2：团队无限级佣金；3：区域分红佣金）
        $model_pd = Model('predeposit');
        $list = $model_pd->getPdCashList($where, $this->page, '*', 'pdc_id desc');
        $page_count = $model_pd->gettotalpage();
        if ($list) {
            foreach($list as $k=>$v){
                $v['pdc_add_time_text'] = @date('Y-m-d H:i:s',$v['pdc_add_time']);
                $v['pdc_payment_time_text'] = @date('Y-m-d H:i:s',$v['pdc_payment_time']);
                $v['pdc_payment_state_text'] = $v['pdc_payment_state']==1?'已支付':'未支付';
                $list[$k] = $v;
            }
        }
        output_data(array('list' => $list), mobile_page($page_count));
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
        $memberinfo = $model_member->getMemberInfoByID($this->member_info['member_id'], 'member_id,member_paypwd,member_name');
        $memberchaininfo = $model_member->getMemberChainByID($this->member_info['member_id'], 'agent_area_id,area_commission');
        //验证支付密码
        if (md5($_POST['password']) != $memberinfo['member_paypwd']) {
            output_error('支付密码错误');
        }
        //验证金额是否足够
        if (floatval($memberchaininfo['area_commission']) < $pdc_amount){
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
            $data['pdc_type'] = 3;//提现类型（0：余额；1：三级分销佣金；2：团队无限级佣金；3：区域分红佣金）
            $insert = $model_pd->addPdCash($data);
            if (!$insert) {
                output_error('提交失败！');
            }
            //冻结可用预存款
            $data = array();
            $data_m['area_commission'] = array('exp','area_commission-'.$pdc_amount);//扣除提现金额
            $model_member->editMemberChain(array('member_id'=>$this->member_info['member_id']),$data_m);            
            $model_pd->commit();
            output_data(array('status'=>'ok'));
            
        } catch (Exception $e) {
            $model_pd->rollback();
            output_error('系统繁忙，提交失败');
        }
        
    }
}