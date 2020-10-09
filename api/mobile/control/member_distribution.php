<?php
/**
 * 分销管理
 */


defined('ShopWT') or exit('Access Denied By ShopWT');

class member_distributionControl extends mobiledistributionControl {
    function __construct()
    {
        parent::__construct();
    }
	/**
     * 我的分销
     */
    public function indexWt() {
        //$member_info['user_name'] = $this->member_info['member_id'];
        //$member_info['avatar'] = getMemberAvatarForID($this->member_info['member_id']);

        /****************三级分销奖金触发发放*******************/          
        $model_pd = Model('predeposit');
        $distribution_settlement_event = Model('setting')->getRowSetting('distribution_settlement_event');//结算事件 1:订单完成；0：订单付款
        $distribution_settlement_days = Model('setting')->getRowSetting('distribution_settlement_days');//结算天数  上面的事件触发后的天数后佣金到账
        //结算未结算的佣金
        $no_list = Model()->table('distribution_order,orders')->field('distribution_order.*,orders.order_sn,orders.order_amount,orders.order_state,orders.refund_state')->join('left')->on('distribution_order.order_id=orders.order_id')->where("order_state > 10 and status = 0 and (commission_one_uid = {$this->member_info['member_id']} or commission_two_uid = {$this->member_info['member_id']} or commission_three_uid = {$this->member_info['member_id']})")->select();
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
                    if($nl['commission_one_uid'] == $this->member_info['member_id']){
                        $no_pay_commission += $nl['commission_one_level'];
                    }else if($nl['commission_two_uid'] == $this->member_info['member_id']){
                        $no_pay_commission += $nl['commission_two_level'];
                    }else if($nl['commission_three_uid'] == $this->member_info['member_id']){
                        $no_pay_commission += $nl['commission_three_level'];
                    }else{
                        $no_pay_commission += 0;
                    }
                }
            }
        }
        /****************三级分销奖金触发发放*******************/

        $member_info = Model('member')->getMemberInfoByID($this->member_info['member_id'], 'member_id,trad_amount,user_name');
        $member_info['avatar'] = getMemberAvatarForID($this->member_info['member_id']);
        $member_info['available_fx_trad'] = wtPriceFormat($member_info['trad_amount']);
        $member_info['freeze_fx_trad'] = $no_pay_commission;
        $md_info = Model('member')->getMemberDistributionInfo(array('member_id'=>$this->member_info['member_id']));
        $member_info['level_name'] = $md_info['level_name'];
        //$model_fx_goods = Model('fx_goods');
        //$fx_goods_count = $model_fx_goods->getFenxiaoGoodsCount(array('member_id' => $this->member_info['member_id']));//查看该会员分销商品数量

        output_data(array('member_info' => $member_info));
    }


    /**
     * 获取分销订单列表
     */
    public function distribution_orderWt(){      
        $model_pd = Model('predeposit');
        $condition = array('member_id' => $this->member_info['member_id']);//$this->page
        $order_list = $model_pd->getCommissionList($condition, $this->page,'order_time desc');
        foreach($order_list as $k=>$v){
            if($v['commission_one_uid'] == $this->member_info['member_id']){

                $order_list[$k]['user_commission'] = $v['commission_one_level'];

            }else if($v['commission_two_uid'] == $this->member_info['member_id']){

                $order_list[$k]['user_commission'] = $v['commission_two_level'];

            }else if($v['commission_three_uid'] == $this->member_info['member_id']){

                $order_list[$k]['user_commission'] = $v['commission_three_level'];

            }else{
                $order_list[$k]['user_commission'] = 0;
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
        $order_count = $model_pd->getCommissionCount($condition);
        $page_count = ceil($order_count/$this->page);
        output_data(array('order_list' => $order_list), mobile_page($page_count));
    }

    /**
     * 获取三级分销下级用户
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
        //getMemberList($condition = array(), $field = '*', $page = null, $order = 'member_id desc', $limit = '') 
        $invite_list = $model_invite->getMemberList($condition, '*', $this->page);

        $invite_count = $model_invite->getMemberCount($condition);
        $page_count = ceil($invite_count/$this->page);
        output_data(array('invite_list' => $invite_list,'asdf'=>$this->member_info['member_id']), mobile_page($page_count));
    }

    /**
     * 我的佣金
     */
    public function my_assetWt() {
        $member_info = array();
        if($this->member_info['member_id'] > 0){
            $member_info = Model('member')->getMemberInfoByID($this->member_info['member_id'], 'member_id,trad_amount,user_name');
        }
        output_data($member_info);
    }

    /**
     * 提现记录
     */
    public function pdcashlistWt(){
        $where = array();
        $where['pdc_member_id'] =  $this->member_info['member_id'];
        $where['pdc_type'] =  1;//提现类型（0：余额；1：三级分销佣金；2：团队无限级佣金；3：区域分红佣金）
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
        $memberinfo = $model_member->getMemberInfoByID($this->member_info['member_id'], 'member_id,member_paypwd,trad_amount,member_name');
        //验证支付密码
        if (md5($_POST['password']) != $memberinfo['member_paypwd']) {
            output_error('支付密码错误');
        }
        //验证金额是否足够
        if (floatval($memberinfo['trad_amount']) < $pdc_amount){
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
            $data['pdc_type'] = 1;//提现类型（0：余额；1：三级分销佣金；2：团队无限级佣金；3：区域分红佣金）
            $insert = $model_pd->addPdCash($data);
            if (!$insert) {
                output_error('提交失败！');
            }
            //冻结可用预存款
            $data = array();
            $data_m['trad_amount'] = array('exp','trad_amount-'.$pdc_amount);//扣除提现金额
            $model_member->editMember(array('member_id'=>$this->member_info['member_id']),$data_m);            
            $model_pd->commit();
            output_data(array('status'=>'ok'));
            
        } catch (Exception $e) {
            $model_pd->rollback();
            output_error('系统繁忙，提交失败');
        }
        
    }

}