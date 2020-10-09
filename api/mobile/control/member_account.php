<?php
/**
 * 我的钱
 *


 

 */


defined('ShopWT') or exit('Access Denied By ShopWT');

class member_accountControl extends mobileMemberControl {
    public function __construct(){
        parent::__construct();
    }
	
	public function indexWt(){
		$data = array();
        $data['m_state'] = $this->member_info['member_mobile_bind'] ? true : false;
        $data['mobile'] = $data['state'] ? encryptShow($this->member_info['member_mobile'],4,4) : $this->member_info['member_mobile'];
		$data['p_state'] = $this->member_info['member_paypwd'] ? true : false;
        $data['avatar'] = getMemberAvatarForID($this->member_info['member_id']);
        output_data($data);
	}

    /*
    *我的头像次修改操作
    */
    public function ajax_update_avatarWt(){
		$member_id = $this->member_info['member_id'];
		$img = $_POST['img'];
		$file['list']=BASE_UPLOAD_PATH.DS.ATTACH_AVATAR;
		$member_info = Model('member')->getMemberInfoByID($member_id,'member_avatar');
		if ($member_info['member_avatar']) {
			$src = BASE_UPLOAD_PATH.DS.ATTACH_AVATAR.DS.$member_info['member_avatar'];
			$clear = Model('member')->editMember(array('member_id'=>$member_id),array('member_avatar'=>''));
			@unlink($src);
		}
		if (!file_exists($file['list'])) {
			mkdir($file['list'],0777,true);
		}

		if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img, $result)){
		  $type = $result[2];
		  $filename = $file['list']."/avatar_{$member_id}.jpg";
		  file_put_contents($filename, base64_decode(str_replace($result[1], '', $img)));
		}

		$info = explode('/', $filename);
		$member_avatar = $info[count($info)-1];
		$up = Model('member')->editMember(array('member_id'=>$member_id),array('member_avatar'=>$member_avatar));

		if($up){

			echo json_encode(array('status'=>1,'info'=>'头像上传成功!','img'=>$member_avatar));

		}else{

			echo json_encode(array('status'=>0,'info'=>'头像上传失败!'));

		}
    }


	

    /**
     * 初次绑定手机第一步
     */
    public function bind_mobile_step1Wt() {
        if (!$_POST['mobile'] || !preg_match('/^\d{11}$/',$_POST['mobile'])) {
            output_error('请正确输入手机号');
        }
        /* 
		if (!preg_match('/^\w{4}$/', $_POST['captcha']) || !Model('apivercode')->checkApiVercode($_POST["codekey"],$_POST['captcha'])) {
            output_error('验证码错误');
        }*/

        $model_member = Model('member');
		
		//验证手机号码绑定
		$check_mobile = $model_member->getMemberInfo(array('member_mobile'=>trim($_POST['mobile']), 'member_mobile_bind'=>1));
	    if(is_array($check_mobile) and count($check_mobile) > 0) {
			output_error('手机号码已经被绑定过');
		}
        //发送频率验证
        $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$this->member_info['member_id']));
        if (!empty($member_common_info['send_acode_time'])) {
            if (date('Ymd',$member_common_info['send_acode_time']) != date('Ymd',TIMESTAMP)) {
                $data = array();
                $data['send_acode_times'] = 0;
                $update = $model_member->editMemberCommon($data,array('member_id'=>$this->member_info['member_id']));
            } else {
                if (TIMESTAMP - $member_common_info['send_acode_time'] < DEFAULT_CONNECT_SMS_TIME) {
                    output_error('请60秒以后再发');
                } else {
                    if ($member_common_info['send_acode_times'] >= 15) {
                        output_error('今天短信已超15条，无法发送');
                    }
                }
            }
        }

        try {
            $verify_code = rand(100,999).rand(100,999);
            $tpl_info = Model('mail_templates')->getTplInfo(array('code'=>'authenticate'));
            $param = array();
            $param['send_time'] = date('H:i',TIMESTAMP);
            $param['verify_code'] = $verify_code;
            $param['site_name'] = C('site_name');
            $message = wtReplaceText($tpl_info['content'],$param);
			$param['apicodeid'] = $tpl_info['apicodeid'];
            $sms = new Sms();
            $result = $sms->send($_POST['mobile'],$message,$param);
            if ($result) {
                $data = array();
                $update_data['auth_code'] = $verify_code;
                $update_data['send_acode_time'] = TIMESTAMP;
                $update_data['send_acode_times'] = array('exp','send_acode_times+1');
                $update = $model_member->editMemberCommon($update_data,array('member_id'=>$this->member_info['member_id']));
                if (!$update) {
                    output_error('系统发生错误');
                }
                $data = array();
                $data['member_mobile'] = $_POST['mobile'];
                $update = $model_member->editMember(array('member_id'=>$this->member_info['member_id']),$data);
                if (!$update) {
                    output_error('系统发生错误');
                }
                output_data(array('sms_time'=>DEFAULT_CONNECT_SMS_TIME));
            } else {
                output_error('验证码发送失败');
            }
        } catch (Exception $e) {
            output_error($e->getMessage());
        }
    }

    /**
     * 初次绑定手机第二步 - 验证短信码
     */
    public function bind_mobile_step2Wt() {
        if (!$_POST['auth_code'] || !preg_match('/^\d{6}$/',$_POST['auth_code'])) {
            output_error('请正确输入短信验证码');
        }
        $model_member = Model('member');
        $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$this->member_info['member_id']));
        if (empty($member_common_info) || !is_array($member_common_info)) {
            output_error('验证失败');
        }
        if (TIMESTAMP - $member_common_info['send_acode_time'] > 1800) {
            output_error('验证码已失效，请重新获取');
        }
        if ($member_common_info['auth_code_check_times'] > 6) {
            output_error('输入错误次数过多，请重新获取');
        }
        if ($member_common_info['auth_code'] != $_POST['auth_code']) {
            $data = array();
            $update_data['auth_code_check_times'] = array('exp','auth_code_check_times+1');
            $update = $model_member->editMemberCommon($update_data,array('member_id'=>$this->member_info['member_id']));
            if (!$update) {
                output_error('系统发生错误');
            }
            output_error('验证失败');
        }

        $data = array();
        $data['auth_code'] = '';
        $data['send_acode_time'] = 0;
        $data['auth_code_check_times'] = 0;
        $update = $model_member->editMemberCommon($data,array('member_id'=>$this->member_info['member_id']));
        if (!$update) {
            output_error('系统发生错误');
        }
        $update = $model_member->editMember(array('member_id'=>$this->member_info['member_id']),array('member_mobile_bind'=>1));
        if (!$update) {
            output_error('系统发生错误');
        }
        output_data('1');
    }

    /**
     * 检测会员手机是否绑定
     * 更改绑定手机 第一步 - 得到已经绑定的手机号
     * 修改密码 第一步 - 得到已经绑定的手机号
     * 修改支付密码 第一步 - 得到已经绑定的手机号
     */
    public function get_mobile_infoWt() {
        $data = array();
        $data['state'] = $this->member_info['member_mobile_bind'] ? true : false;
        $data['mobile'] = $data['state'] ? encryptShow($this->member_info['member_mobile'],4,4) : $this->member_info['member_mobile'];
        output_data($data);
    }

    /**
     * 检测是否设置了支付密码
     */
    public function get_paypwd_infoWt() {
        $data = array();
        $data['state'] = $this->member_info['member_paypwd'] ? true : false;
        output_data($data);
    }

    /**
     * 更改绑定手机 第二步 - 向已经绑定的手机发送验证码
     */
    public function modify_mobile_step2Wt() {
        $this->_send_bind_mobile_msg();
    }

    /**
     * 更改密码 第二步 - 向已经绑定的手机发送验证码
     */
    public function modify_password_step2Wt() {
        $this->_send_bind_mobile_msg();
    }

    /**
     * 更改支付密码第二步 - 向已经绑定的手机发送验证码
     */
    public function modify_paypwd_step2Wt() {
        $this->_send_bind_mobile_msg();
    }

    private function _send_bind_mobile_msg() {
		/* 
        if (!preg_match('/^\w{4}$/', $_POST['captcha']) || !Model('apivercode')->checkApiVercode($_POST["codekey"],$_POST['captcha'])) {
            output_error('验证码错误');
        }
		*/

        if (!$this->member_info['member_mobile_bind'] || !$this->member_info['member_mobile']) {
            output_error('您还未绑定手机号码');
        }

        $model_member = Model('member');
        //发送频率验证
        $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$this->member_info['member_id']));
        if (!empty($member_common_info['send_acode_time'])) {
            if (date('Ymd',$member_common_info['send_acode_time']) != date('Ymd',TIMESTAMP)) {
                $data = array();
                $data['send_acode_times'] = 0;
                $update = $model_member->editMemberCommon($data,array('member_id'=>$this->member_info['member_id']));
                if (!$update) {
                    output_error('系统发生错误');
                }
            } else {
                if (TIMESTAMP - $member_common_info['send_acode_time'] < DEFAULT_CONNECT_SMS_TIME) {
                    output_error('请60秒以后再发');
                } else {
                    if ($member_common_info['send_acode_times'] >= 15) {
                        output_error('今天短信已超15条，无法发送');
                    }
                }
            }
        }

        try {
            $verify_code = rand(100,999).rand(100,999);
            $tpl_info = Model('mail_templates')->getTplInfo(array('code'=>'authenticate'));
            $param = array();
            $param['send_time'] = date('H:i',TIMESTAMP);
            $param['verify_code'] = $verify_code;
            $param['site_name'] = C('site_name');
            $message = wtReplaceText($tpl_info['content'],$param);
			$param['apicodeid'] = $tpl_info['apicodeid'];
            $sms = new Sms();
            $result = $sms->send($this->member_info['member_mobile'],$message,$param);
            if ($result) {
                $data = array();
                $update_data['auth_code'] = $verify_code;
                $update_data['send_acode_time'] = TIMESTAMP;
                $update_data['send_acode_times'] = array('exp','send_acode_times+1');
                $update = $model_member->editMemberCommon($update_data,array('member_id'=>$this->member_info['member_id']));
                if (!$update) {
                    output_error('系统发生错误');
                }
                output_data(array('sms_time'=>DEFAULT_CONNECT_SMS_TIME));
            } else {
                output_error('验证码发送失败');
            }
        } catch (Exception $e) {
            output_error($e->getMessage());
        }
    }

    /**
     * 更改绑定手机 第三步 - 验证短信码
     */
    public function modify_mobile_step3Wt() {
        if (!$_POST['auth_code'] || !preg_match('/^\d{6}$/',$_POST['auth_code'])) {
            output_error('请正确输入短信验证码');
        }
        $model_member = Model('member');
        $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$this->member_info['member_id']));
        if (empty($member_common_info) || !is_array($member_common_info)) {
            output_error('验证失败');
        }
        if (TIMESTAMP - $member_common_info['send_acode_time'] > 1800) {
            output_error('验证码已失效，请重新获取');
        }
        if ($member_common_info['auth_code_check_times'] > 6) {
            output_error('输入错误次数过多，请重新获取');
        }
        if ($member_common_info['auth_code'] != $_POST['auth_code']) {
            $data = array();
            $update_data['auth_code_check_times'] = array('exp','auth_code_check_times+1');
            $update = $model_member->editMemberCommon($update_data,array('member_id'=>$this->member_info['member_id']));
            if (!$update) {
                output_error('系统发生错误');
            }
            output_error('验证失败');
        }

        $data = array();
        $data['auth_code'] = '';
        $data['send_acode_time'] = 0;
        $data['auth_code_check_times'] = 0;
        $update = $model_member->editMemberCommon($data,array('member_id'=>$this->member_info['member_id']));
        if (!$update) {
            output_error('系统发生错误');
        }
        $data = array();
        $data['member_mobile'] = '';
        $data['member_mobile_bind'] = 0;
        $update = $model_member->editMember(array('member_id'=>$this->member_info['member_id']),$data);
        if (!$update) {
            output_error('系统发生错误');
        }
        output_data('1');
    }

    /**
     * 更改密码 第三步 - 验证短信码
     */
    public function modify_password_step3Wt() {
        $this->_modify_pwd_check_vcode();
    }

    /**
     * 更改支付密码 第三步 - 验证短信码
     */
    public function modify_paypwd_step3Wt() {
        $this->_modify_pwd_check_vcode();
    }

    public function _modify_pwd_check_vcode() {
        if (!$_POST['auth_code'] || !preg_match('/^\d{6}$/',$_POST['auth_code'])) {
            output_error('请正确输入短信验证码');
        }
        $model_member = Model('member');
        $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$this->member_info['member_id']));
        if (empty($member_common_info) || !is_array($member_common_info)) {
            output_error('验证失败');
        }
        if (TIMESTAMP - $member_common_info['send_acode_time'] > 1800) {
            output_error('验证码已失效，请重新获取');
        }
        if ($member_common_info['auth_code_check_times'] > 3) {
            output_error('输入错误次数过多，请重新获取');
        }
        if ($member_common_info['auth_code'] != $_POST['auth_code']) {
            $data = array();
            $update_data['auth_code_check_times'] = array('exp','auth_code_check_times+1');
            $update = $model_member->editMemberCommon($update_data,array('member_id'=>$this->member_info['member_id']));
            if (!$update) {
                output_error('系统发生错误');
            }
            output_error('验证失败');
        }

        $data = array();
        $data['auth_code'] = '';
        $data['send_acode_time'] = 0;
        $data['auth_code_check_times'] = 0;
        $update = $model_member->editMemberCommon($data,array('member_id'=>$this->member_info['member_id']));
        if (!$update) {
            output_error('系统发生错误');
        }

        //更改密码授权
        $update = $model_member->editMemberCommon(array('auth_modify_pwd_time'=>TIMESTAMP),array('member_id'=>$this->member_info['member_id']));
        if (!$update) {
            output_error('系统发生错误');
        }

        output_data('1');
    }

    /**
     * 更改密码 第四步 - 检查是否有权修改密码
     */
    public function modify_password_step4Wt() {
        $this->_modify_pwd_limit_check();
        output_data('1');
    }

    /**
     * 更改支付密码 第四步 - 检查是否有权修改密码
     */
    public function modify_paypwd_step4Wt() {
        $this->_modify_pwd_limit_check();
        output_data('1');
    }

    private function _modify_pwd_limit_check() {
        //身份验证后，需要在30分钟内完成修改密码操作
        $model_member = Model('member');
        $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$this->member_info['member_id']));
        if (empty($member_common_info) || !is_array($member_common_info)) {
            output_error('验证失败');
        }
        if ($member_common_info['auth_modify_pwd_time'] && TIMESTAMP - $member_common_info['auth_modify_pwd_time'] > 1800) {
            output_error('操作超时，请重新获取短信验证码');
        }
    }

    /**
     * 更改密码 第五步 - 保存新密码到数据库
     */
    public function modify_password_step5Wt() {

        if (!$_POST['password'] || !$_POST['password1'] || $_POST['password'] != $_POST['password1']) {
            output_error('提交数据错误');
        }

        //身份验证后，需要在30分钟内完成修改密码操作
        $this->_modify_pwd_limit_check();

        $model_member = Model('member');

        $update = $model_member->editMember(array('member_id'=>$this->member_info['member_id']),array('member_passwd'=>md5($_POST['password'])));
        if (!$update) {
            output_error('密码修改失败');
        }

        $update = $model_member->editMemberCommon(array('auth_modify_pwd_time'=>'0'),array('member_id'=>$this->member_info['member_id']));
        if (!$update) {
            output_error('系统发生错误');
        }
        output_data('1');
    }

    /**
     * 更改支付密码 第五步 - 保存新密码到数据库
     */
    public function modify_paypwd_step5Wt() {

        if (!$_POST['password'] || !$_POST['password1'] || $_POST['password'] != $_POST['password1']) {
            output_error('提交数据错误');
        }

        //身份验证后，需要在30分钟内完成修改密码操作
        $this->_modify_pwd_limit_check();

        $model_member = Model('member');
        $update = $model_member->editMember(array('member_id'=>$this->member_info['member_id']),array('member_paypwd'=>md5($_POST['password'])));
        if (!$update) {
            output_error('密码修改失败');
        }

        $update = $model_member->editMemberCommon(array('auth_modify_pwd_time'=>'0'),array('member_id'=>$this->member_info['member_id']));
        if (!$update) {
            output_error('系统发生错误');
        }
        output_data('1');
    }

    /**
     * 验证输入支付密码是否正确
     */
    public function check_paypwdWt() {
        if (!$_POST['password']) {
            output_error('未输入支付密码');
        }
		/*
        if (!preg_match('/^\w{4}$/', $_POST['captcha']) || !Model('apivercode')->checkApiVercode($_POST["codekey"],$_POST['captcha'])) {
            output_error('验证码错误');
        }
		*/ 
        if (md5($_POST['password']) != $this->member_info['member_passwd']) {
            output_error('支付密码输入不正确');
        }

        $model_member = Model('member');
        $data = array();
        $data['member_mobile'] = '';
        $data['member_mobile_bind'] = 0;
        $update = $model_member->editMember(array('member_id'=>$this->member_info['member_id']),$data);
        if (!$update) {
            output_error('系统发生错误');
        }
        //授权绑定新手机
        $update = $model_member->editMemberCommon(array('auth_modify_pwd_time'=>TIMESTAMP),array('member_id'=>$this->member_info['member_id']));
        if (!$update) {
            output_error('系统发生错误');
        }
        output_data('1');
    }
}