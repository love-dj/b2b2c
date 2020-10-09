<?php
/**
 * QQ互联登录
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class connect_qqControl extends BaseLoginControl{
    public function __construct(){
        parent::__construct();
        Language::read("home_login_register,home_login_index,home_qqconnect");
        /**
         * 判断qq互联功能是否开启
         */
        if (C('qq_isuse') != 1){
            showMessage(Language::get('home_qqconnect_unavailable'),'index.php','html','error');//'系统未开启QQ互联功能'
        }
        if (!empty($_GET['code'])){
            $handle_connect_api = Handle('connect_api');
            $user_info = $handle_connect_api->getQqUserInfo($_GET['code']);//获取用户信息
            if (!empty($user_info['openid'])){
                $_SESSION['qquser_info'] = $user_info;
            } else {
                showMessage('QQ互联账号信息错误',urlLogin('login', 'index'),'html','error');
            }
        }
        if (empty($_SESSION['qquser_info'])){
            $handle_connect_api = Handle('connect_api');
            $qq_url = $handle_connect_api->getQqOAuth2Url();
            @header("location: ".$qq_url);exit;
        }
        Tpl::output('hidden_login', 1);
    }
    /**
     * 首页
     */
    public function indexWt(){
        /**
         * 检查登录状态
         */
        if($_SESSION['is_login'] == '1') {
            //qq绑定
            $this->bindqqWt();
        }else {
            $this->autologin();
			
            $this->registerWt();
        }
    }
    /**
     * qq绑定新用户
     */
    public function registerWt(){
        //实例化模型
        $model_member   = Model('member');
        if (chksubmit()){
            $update_info    = array();
            $update_info['member_passwd']= md5(trim($_POST["password"]));
            if(!empty($_POST["email"])) {
                $update_info['member_email']= $_POST["email"];
                $_SESSION['member_email']= $_POST["email"];
            }
            $model_member->editMember(array('member_id'=>$_SESSION['member_id']),$update_info);
			//返回上一页
			$reload = urldecode($_COOKIE['redirect_uri']);
			 if(empty($reload)) {
                    $reload = urlMember('member', 'home');
             }
			showMessage(Language::get('wt_common_save_succ'),$reload);
        }else {
            //检查登录状态
            $model_member->checkloginMember();
            $qquser_info = $_SESSION['qquser_info'];
            Tpl::output('qquser_info',$qquser_info);
            $handle_connect_api = Handle('connect_api');
            $member_info = $handle_connect_api->qqRegister($qquser_info, 'www');
            if($member_info['member_id']) {
                $model_member->createSession($member_info,true);
                Tpl::output('user_passwd',$member_info['password']);
                Tpl::showpage('connect_qq');
            } else {
                showMessage(Language::get('login_usersave_regist_fail'),urlLogin('login', 'register'),'html','error');//"会员注册失败"
            }
        }
    }
    /**
     * 已有用户绑定QQ
     */
    public function bindqqWt(){
        $model_member   = Model('member');
        $qquser_info = $_SESSION['qquser_info'];
        //验证QQ账号用户是否已经存在
        $array  = array();
        $array['member_qqopenid']   = $qquser_info['openid'];
        $member_info = $model_member->getMemberInfo($array);
        if (!empty($member_info)){
            showMessage(Language::get('home_qqconnect_binding_exist'),urlMember('member_bind', 'qqbind'),'html','error');//'该QQ账号已经绑定其他商城账号,请使用其他QQ账号与本账号绑定'
        }
        $edit_state     = $model_member->editMember(array('member_id'=>$_SESSION['member_id']), array('member_qqopenid'=>$qquser_info['openid'], 'member_qqinfo'=>serialize($qquser_info)));
        if ($edit_state){
            showMessage(Language::get('home_qqconnect_binding_success'),urlMember('member_bind', 'qqbind'));
        }else {
            showMessage(Language::get('home_qqconnect_binding_fail'),urlMember('member_bind', 'qqbind'),'html','error');//'绑定QQ失败'
        }
    }
    /**
     * 绑定qq后自动登录
     */
    public function autologin(){
        //查询是否已经绑定该qq,已经绑定则直接跳转
        $model_member   = Model('member');
        $qquser_info = $_SESSION['qquser_info'];
        $array  = array();
        $array['member_qqopenid']   = $qquser_info['openid'];
        $member_info = $model_member->getMemberInfo($array);
        if (!empty($member_info)){
            if(!$member_info['member_state']){//1为启用 0 为禁用
                showMessage(Language::get('wt_notallowed_login'),'','html','error');
            }
            $model_member->createSession($member_info);
            $success_message = Language::get('login_index_login_success');
            //sho p wt .com  返回上一页
			$reload = urldecode($_COOKIE['redirect_uri']);
			 if(empty($reload)) {
                    $reload = urlMember('member', 'home');
             }
			showMessage($success_message,$reload);
        }
    }
}
