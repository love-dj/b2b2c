<?php
/**
 * 分销认证
 *
 *
 * *

 

 */


defined('ShopWT') or exit('Access Denied By ShopWT');

class fx_joinControl extends BasefenxiaoControl{

    protected $member;
    function __construct(){
        parent::__construct();
        $this->checkLogin();
        Tpl::setLayout('fx_joinin_layout');
        $model_member = Model('member');
        $member_info = $model_member->getMemberInfoByID($_SESSION['member_id']);
        Tpl::output('member_info',$member_info);
        if(!empty($member_info) && $member_info['fx_state'] == 2) {
            @header('location: '.urlFenxiao('fx_center','home'));
        }
        if(!empty($member_info) && in_array($member_info['fx_state'],array('1','3')) && $_GET['t'] != 'step2') {
            if(!isset($_REQUEST['reup']) || $_REQUEST['reup'] != 'ok'){
                @header('location: '.urlFenxiao('fx_join','step2'));
            }
        }
        $this->member = $member_info;
        $phone_array = explode(',',C('site_phone'));
        Tpl::output('phone_array',$phone_array);
    }

    public function indexWt(){
        $this->step0Wt();
    }

    public function step0Wt(){
        $model_document = Model('document');
        $document_info = $model_document->getOneByCode('fenxiao_auth');
        Tpl::output('agreement', $document_info['doc_content']);
        Tpl::output('step', '0');
        Tpl::output('sub_step', 'step0');
        Tpl::showpage('fx_join_apply');
    }

    public function step1Wt(){
        Tpl::output('step', '1');
        Tpl::output('sub_step', 'step1');
        Tpl::showpage('fx_join_apply');
    }

    public function step2Wt(){
        if(!empty($_POST)){
            $member_id = intval($_SESSION['member_id']);

            $param = array();
            $param['bill_user_name'] = trim($_POST['bill_user_name']);
            $param['bill_type_number'] = trim($_POST['bill_type_number']);
            $param['bill_type_code'] = trim($_POST['bill_type_code']);
            $param['fx_state'] = 2;
            $param['fx_time'] = time();
            $param['fx_apply_times'] = array('exp','fx_apply_times+1');
            $param['fx_show'] = 1;
            if(C('fenxiao_check')){
                $param['fx_state'] = 1;
                $param['fx_show'] = 0;
            }
            if($param['bill_type_code'] == 'bank'){
                $param['bill_bank_name'] = trim($_POST['bill_bank_name']);
            }else{
                $param['bill_bank_name'] = str_replace(array('alipay'),array('支付宝'),trim($_POST['bill_type_code']));
            }

            $this->step2_save_valid($param);

            $model_member = Model('member');
            $joinin_info = $model_member->editMember(array('member_id' => $member_id),$param);
            if(!$joinin_info){
                showMessage('提交失败','index.php?w=fx_join&t=step1');
            }
            @header('location: '.urlFenxiao('fx_join','step2'));
        }
        $msg = "申请已提交，请耐心等待平台审核";
        if($this->member['fx_state'] == 3){
            $msg = "您的申请未通过审核，请<a href=\"".urlFenxiao('fx_join','index',array('reup'=>'ok'))."\">重新申请</a>";
        }
        Tpl::output('msg',$msg);
        Tpl::output('step', '2');
        Tpl::output('sub_step', 'step2');
        Tpl::showpage('fx_join_apply');
    }
    private function step2_save_valid($param) {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$param['bill_user_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"收款人姓名不能为空且必须小于50个字"),
            array("input"=>$param['bill_type_number'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"收款账号不能为空且必须小于20个字"),
        );
        if($param['bill_type_code'] == 'bank'){
            $obj_validate->validateparam[] = array("input"=>$param['bill_bank_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"开户银行名称不能为空且必须小于50个字");
        }
        $error = $obj_validate->validate();
        if ($error != ''){
            showMessage($error);
        }
    }
}