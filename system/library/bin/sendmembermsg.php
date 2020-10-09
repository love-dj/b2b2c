<?php
/**
 *
 *
 *

 
 
 */
class sendMemberMsg {
    private $code = '';
    private $member_id = 0;
    private $member_info = array();
    private $mobile = '';
    private $email = '';

    /**
     * 设置
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function set($key,$value){
        $this->$key = $value;
    }

    public function send($param = array()) {
        $msg_tpl = rkcache('member_msg_tpl', true);
        if (!isset($msg_tpl[$this->code]) || $this->member_id <= 0) {
            return false;
        }

        $tpl_info = $msg_tpl[$this->code];

        $setting_info = Model('member_msg_setting')->getMemberMsgSettingInfo(array('mmt_code' => $this->code, 'member_id' => $this->member_id), 'is_receive');
        if (empty($setting_info) || $setting_info['is_receive']) {
            // 发送站内信
            if ($tpl_info['mmt_message_switch']) {
                $message = wtReplaceText($tpl_info['mmt_message_content'],$param);
                $this->sendMessage($message);
            }
            // 发送短消息
            if ($tpl_info['mmt_short_switch']) {
                $this->getMemberInfo();
                if (!empty($this->mobile)) $this->member_info['member_mobile'] = $this->mobile;
                if ($this->member_info['member_mobile_bind'] && !empty($this->member_info['member_mobile'])) {
                    $param['site_name'] = C('site_name');
					$param2 = $param;
					$param2['apicodeid'] = $tpl_info['apicodeid'];
                    $message = wtReplaceText($tpl_info['mmt_short_content'],$param);
                    $this->sendShort($this->member_info['member_mobile'], $message,$param2);
                }
            }
            // 发送邮件
            if ($tpl_info['mmt_mail_switch']) {
                $this->getMemberInfo();
                if (!empty($this->email)) $this->member_info['member_email'] = $this->email;
                if ($this->member_info['member_email_bind'] && !empty($this->member_info['member_email'])) {
                    $param['site_name'] = C('site_name');
                    $param['mail_send_time'] = date('Y-m-d H:i:s');
                    $subject = wtReplaceText($tpl_info['mmt_mail_subject'],$param);
                    $message = wtReplaceText($tpl_info['mmt_mail_content'],$param);
                    $this->sendMail($this->member_info['member_email'], $subject, $message);
                }
            }
         
			//微信通知
			if ($tpl_info['mmt_wx_switch'] && !empty($tpl_info['mp_msg_id'])) { 
				$this->getMemberInfo();

				if (!empty($this->member_info['weixin_unionid'])) {
					$logic_wx_api = Handle('wx_api');		
					$access_token = $logic_wx_api->getAccessToken();  
					if (!empty($access_token)) {
						$wx_msg = array();
						$wx_msg['to_id'] = $this->member_info['weixin_unionid'];
						$wx_msg['subject'] = $tpl_info['mp_msg_id'];
						$wx_msg['log_msg'] = serialize($param['mp_array']);
						$result = $logic_wx_api->sendTemplate($access_token, $wx_msg);
					}
					
				}
			}
        }
    }
	

    /**
     * 会员详细信息
     */
    private function getMemberInfo() {
        if (empty($this->member_info)) {
            $this->member_info = Model('member')->getMemberInfoByID($this->member_id);
        }
    }

    /**
     * 发送站内信
     * @param unknown $message
     */
    private function sendMessage($message) {
        //添加短消息
        $model_message = Model('message');
        $insert_arr = array();
        $insert_arr['from_member_id'] = 0;
        $insert_arr['member_id'] = $this->member_id;
        $insert_arr['msg_content'] = $message;
        $insert_arr['message_type'] = 1;
        $model_message->saveMessage($insert_arr);
    }

    /**
     * 发送短消息
     * @param unknown $number
     * @param unknown $message
     */
    private function sendShort($number, $message,$data=array()) {
        $sms = new Sms();
        $sms->send($number, $message,$data);
    }

    /**
     * 发送邮件
     * @param unknown $number
     * @param unknown $subject
     * @param unknown $message
     */
    private function sendMail($number, $subject, $message) {
    	//即时发送邮箱
	$email = new Email();
        $email->send_sys_email($this->store_number['store_msg_mail'],$subject,$message);
	
	
        // 计划任务代码
        $insert = array();
        $insert['mail'] = $number;
        $insert['subject'] = $subject;
        $insert['contnet'] = $message;
        Model('mail_cron')->addMailCron($insert);
    }
}
