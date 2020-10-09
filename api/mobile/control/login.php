<?php
/**
 * 前台登录 退出操作
 *
 *


 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class loginControl extends mobileHomeControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 登录
     */
    public function indexWt(){
        if(empty($_POST['username']) || empty($_POST['password']) || !in_array($_POST['client'], $this->client_type_array)) {
            output_error('登录失败');
        }

        $model_member = Model('member');

        $login_info = array();
        $login_info['user_name'] = $_POST['username'];
        $login_info['password'] = $_POST['password'];
        $member_info = $model_member->login($login_info);
        if(isset($member_info['error'])) {
            output_error($member_info['error']);
        } else {
			
     			//登录生成token V6.2 同步登录
			$model_seller = Model('seller');
			$seller_info = $model_seller->getSellerInfo(array('member_id' => $member_info['member_id']));
			$sellerinfo=array();
			if($seller_info) {
				 //读取店铺信息
				$model_store = Model('store');
				$store_info = $model_store->getStoreInfoByID($seller_info['store_id']);
				 //更新卖家登陆时间
				$model_seller->editSeller(array('last_login_time' => TIMESTAMP), array('seller_id' => $seller_info['seller_id']));

				//生成登录令牌
				$token = $this->_get_seller_token($seller_info['seller_id'], $seller_info['seller_name'], 'wap');
				$sellerinfo=array('seller_name' => $seller_info['seller_name'],'store_name' => $store_info['store_name'],'key' => $token);
			}
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token) {
                output_data(array('username' => $member_info['member_name'], 'userid' => $member_info['member_id'], 'key' => $token,'sell'=>$sellerinfo));
            } else {
                output_error('登录失败');
            }
        }
    }

    /**
     * 登录生成token
     */
    private function _get_token($member_id, $member_name, $client) {
        $model_mb_user_token = Model('mb_user_token');

        //重新登录后以前的令牌失效
        //暂时停用
        //$condition = array();
        //$condition['member_id'] = $member_id;
        //$condition['client_type'] = $client;
        //$model_mb_user_token->delMbUserToken($condition);

        //生成新的token
        $mb_user_token_info = array();
        $token = md5($member_name . strval(TIMESTAMP) . strval(rand(0,999999)));
        $mb_user_token_info['member_id'] = $member_id;
        $mb_user_token_info['member_name'] = $member_name;
        $mb_user_token_info['token'] = $token;
        $mb_user_token_info['login_time'] = TIMESTAMP;
        $mb_user_token_info['client_type'] = $client;

        $result = $model_mb_user_token->addMbUserToken($mb_user_token_info);

        if($result) {
            return $token;
        } else {
            return null;
        }

    }
	/**
     * 登录生成token V6.2 同步登录
     */
    private function _get_seller_token($seller_id, $seller_name, $client) {
        $model_mb_seller_token = Model('mb_seller_token');

        //重新登录后以前的令牌失效
        $condition = array();
        $condition['seller_id'] = $seller_id;
        $model_mb_seller_token->delSellerToken($condition);

        //生成新的token
        $mb_seller_token_info = array();
        $token = md5($seller_name. strval(TIMESTAMP) . strval(rand(0,999999)));
        $mb_seller_token_info['seller_id'] = $seller_id;
        $mb_seller_token_info['seller_name'] = $seller_name;
        $mb_seller_token_info['token'] = $token;
        $mb_seller_token_info['login_time'] = TIMESTAMP;
        $mb_seller_token_info['client_type'] = $client;

        $result = $model_mb_seller_token->addSellerToken($mb_seller_token_info);

        if($result) {
            return $token;
        } else {
            return null;
        }
    }
    /**
     * 注册
     */
    public function registerWt(){
		if (process::islock('reg')){
			output_error('您的操作过于频繁，请稍后再试');
		} 
        $model_member   = Model('member');
		// 会员邀请
		$invite_id = intval(base64_decode($_COOKIE['uid']))/1;
		if(!empty($invite_id)) {
		    $member=$model_member->getMemberInfo(array('member_id'=>$invite_id));
			$invite_one = $invite_id;
			$invite_two = $member['invite_one'];
			$invite_three = $member['invite_two'];
		}else{
		    $invite_one = 0;
			$invite_two = 0;
			$invite_three = 0;
		}
        $register_info = array();
        $register_info['username'] = $_POST['username'];
        $register_info['password'] = $_POST['password'];
        $register_info['password_confirm'] = $_POST['password_confirm'];
        $register_info['email'] = $_POST['email'];
		//添加奖励积分
		$register_info['inviter_id'] = intval(base64_decode($_COOKIE['uid']))/1;
		//
		$register_info['invite_one'] = $invite_one;
		$register_info['invite_two'] = $invite_two;
		$register_info['invite_three'] = $invite_three;
        $member_info = $model_member->register($register_info);
        if(!isset($member_info['error'])) {
	        process::addprocess('reg');
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token) {
                output_data(array('username' => $member_info['member_name'], 'userid' => $member_info['member_id'], 'key' => $token));
            } else {
                output_error('注册失败');
            }
        } else {
            output_error($member_info['error']);
        }

    }
}
