<?php
/**
 * 分销提现管理
 *
 *
 * *

 

 */


defined('ShopWT') or exit('Access Denied By ShopWT');

class cashControl extends MemberfenxiaoControl{

    /**
     * 提现记录
     */
    public function indexWt(){
        $this->cash_listWt();
    }

    /**
     * 提现记录列表
     */
    public function cash_listWt(){
        $condition = array();
        if (preg_match('/^\d+$/',$_GET['sn_search'])) {
            $condition['tradc_sn'] = $_GET['sn_search'];
        }
        if (isset($_GET['paystate_search'])){
            $condition['tradc_payment_state'] = intval($_GET['paystate_search']);
        }
        $condition['tradc_member_id'] = $_SESSION['member_id'];
        $model_tard = Model('fx_trad');
        $list = $model_tard->getFenxiaoTradCashList($condition, '*' , 20);

        //信息输出
        self::profile_menu('log','cash_list');
        Tpl::output('show_page',$model_tard->showpage(2));
        Tpl::output('list',$list);
        Tpl::showpage('cash_list');
    }

    /**
     * 提现记录详情
     */
    public function cash_infoWt(){
        $tradc_id = intval($_GET["id"]);
        if ($tradc_id <= 0){
            showMessage('参数错误','index.php?w=cash&t=cash_list','html','error');
        }
        $model_tard = Model('fx_trad');
        $condition = array();
        $condition['tradc_member_id'] = $_SESSION['member_id'];
        $condition['tradc_id'] = $tradc_id;
        $info = $model_tard->getFenxiaoTradCashInfo($condition);
        if (empty($info)){
            showMessage('记录不存在或已删除','index.php?w=cash&t=cash_list','html','error');
        }

        self::profile_menu('cashinfo','cashinfo');
        Tpl::output('info',$info);
        Tpl::showpage('cash_info');
    }

    /**
     * 申请提现
     */
    public function apply_cashWt(){
        if(chksubmit()){
            $obj_validate = new Validate();
            $tradc_amount = abs(floatval($_POST['cash_amount']));
            $validate_arr[] = array("input"=>$tradc_amount, "require"=>"true",'validator'=>'Compare','operator'=>'>=',"to"=>'0.01',"message"=>'请输入正确的提现金额');
            $validate_arr[] = array("input"=>$_POST["pay_pwd"], "require"=>"true","message"=>'请输入支付密码');
            $obj_validate -> validateparam = $validate_arr;
            $error = $obj_validate->validate();
            if ($error != ''){
                showDialog($error,'','error');
            }

            $model_tard = Model('fx_trad');
            $model_member = Model('member');
            $member_info = $model_member->getMemberInfoByID($_SESSION['member_id']);
            //验证支付密码
            if (md5($_POST['pay_pwd']) != $member_info['member_paypwd']) {
                showDialog('支付密码错误','','error');
            }
            //验证金额是否足够
            $available_trad = $member_info['trad_amount'];

            if($this->member_info['fx_state'] == 2){
                if($this->member_info['trad_amount'] >= C('fenxiao_bill_limit')){
                    $available_trad -= C('fenxiao_bill_limit');
                }else{
                    $available_trad = 0;
                }
            }

            if (floatval($available_trad) < $tradc_amount){
                showDialog('请输入正确的提现金额','index.php?w=cash&t=cash_list','error');
            }
            try {
                $model_tard->beginTransaction();
                $tradc_sn = $model_tard->makeSn();
                $data = array();
                $data['tradc_sn'] = $tradc_sn;
                $data['tradc_member_id'] = $_SESSION['member_id'];
                $data['tradc_member_name'] = $_SESSION['member_name'];
                $data['tradc_amount'] = $tradc_amount;
                $data['tradc_bank_name'] = $member_info['bill_bank_name'];
                $data['tradc_bank_no'] = $member_info['bill_type_number'];
                $data['tradc_bank_user'] = $member_info['bill_user_name'];
                $data['tradc_add_time'] = TIMESTAMP;
                $data['tradc_payment_state'] = 0;
                $insert = $model_tard->addFenxiaoTradCash($data);
                if (!$insert) {
                    throw new Exception('提现申请失败');
                }
                //增加冻结分销佣金
                $data = array();
                $data['member_id'] = $member_info['member_id'];
                $data['member_name'] = $member_info['member_name'];
                $data['amount'] = $tradc_amount;
                $data['order_sn'] = $tradc_sn;
                $model_tard->changeDirtriTrad('cash_apply',$data);
                $model_tard->commit();
                showDialog('提现申请成功','index.php?w=cash&t=cash_list','succ');
            } catch (Exception $e) {
                $model_tard->rollback();
                showDialog($e->getMessage(),'index.php?w=cash&t=cash_list','error');
            }
        }
        self::profile_menu('cashadd','cashadd');
        Tpl::showpage('cash_add');
    }

    /**
     * 验证用户支付密码
     */
    public function check_pwdWt(){
        if (empty($_GET['pay_pwd'])) exit('0');
        $buyer_info = Model('member')->getMemberInfoByID($_SESSION['member_id'],'member_paypwd');
        echo ($buyer_info['member_paypwd'] != '' && $buyer_info['member_paypwd'] === md5($_GET['pay_pwd'])) ? 'true' : 'false';
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
            array('menu_key'=>'cash_list',        'menu_name'=>'提现记录',    'menu_url'=>'index.php?w=cash&t=cash_list')
        );
        switch ($menu_type) {
            case 'cashinfo':
                $menu_array[] = array('menu_key'=>'cashinfo','menu_name'=>'提现详细',  'menu_url'=>'');
                break;
            case 'cashadd':
                $menu_array[] = array('menu_key'=>'cashadd','menu_name'=>'提现申请',    'menu_url'=>'index.php?w=cash&t=apply_cash');
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}