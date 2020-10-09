<?php
/**
 * mobile父类
 *


 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

/********************************** 前台control父类 **********************************************/

class mobileControl{

    //客户端类型
    protected $client_type_array = array('android', 'wap', 'wechat', 'ios', 'windows');
    //列表默认分页数
    protected $page = 5;


    public function __construct() {
        Language::read('mobile');

        //分页数处理
        $page = intval($_GET['page']);
        if($page > 0) {
            $this->page = $page;
        }
    }
}

class mobileHomeControl extends mobileControl{
    public function __construct() {
        parent::__construct();
    }

    protected function getMemberIdIfExists()
    {
        $key = $_POST['key'];
        if (empty($key)) {
            $key = $_GET['key'];
        }

        $model_mb_user_token = Model('mb_user_token');
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
        if (empty($mb_user_token_info)) {
            return 0;
        }

        return $mb_user_token_info['member_id'];
    }
}

class mobileMemberControl extends mobileControl{

    protected $member_info = array();

    public function __construct() {
        parent::__construct();
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if (strpos($agent, "MicroMessenger") && $_GET["w"]=='auto') {	
			$this->appId = C('app_weixin_appid');
			$this->appSecret = C('app_weixin_secret');;			
        }else{
			$model_mb_user_token = Model('mb_user_token');
			$key = $_POST['key'];
			if(empty($key)) {
				$key = $_GET['key'];
			}
			$mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
			if(empty($mb_user_token_info)) {
				output_error('请登录', array('login' => '0'));
			}

        $model_member = Model('member');
        $this->member_info = $model_member->getMemberInfoByID($mb_user_token_info['member_id']);

        if(empty($this->member_info)) {
            output_error('请登录', array('login' => '0'));
			} else {
				$this->member_info['client_type'] = $mb_user_token_info['client_type'];
				$this->member_info['openid'] = $mb_user_token_info['openid'];
				$this->member_info['token'] = $mb_user_token_info['token'];
				$level_name = $model_member->getOneMemberGrade($mb_user_token_info['member_id']);
				$this->member_info['level_name'] = $level_name['level_name'];
				//读取卖家信息
				$seller_info = Model('seller')->getSellerInfo(array('member_id'=>$this->member_info['member_id']));
				$this->member_info['store_id'] = $seller_info['store_id'];
			}
        }
    }

    public function getOpenId()
    {
        return $this->member_info['openid'];
    }

    public function setOpenId($openId)
    {
        $this->member_info['openid'] = $openId;
        Model('mb_user_token')->updateMemberOpenId($this->member_info['token'], $openId);
    }
}

class mobileSellerControl extends mobileControl{

    protected $seller_info = array();
    protected $seller_group_info = array();
    protected $member_info = array();
    protected $store_info = array();
    protected $store_grade = array();

    public function __construct() {
        parent::__construct();

        $model_mb_seller_token = Model('mb_seller_token');

        $key = $_POST['key']?$_POST['key']:$_GET['key'];
        if(empty($key)) {
            output_error('请登录', array('login' => '0'));
        }

        $mb_seller_token_info = $model_mb_seller_token->getSellerTokenInfoByToken($key);
        if(empty($mb_seller_token_info)) {
            output_error('请登录', array('login' => '0'));
        }

        $model_seller = Model('seller');
        $model_member = Model('member');
        $model_store = Model('store');
        $model_seller_group = Model('seller_group');

        $this->seller_info = $model_seller->getSellerInfo(array('seller_id' => $mb_seller_token_info['seller_id']));
        $this->member_info = $model_member->getMemberInfoByID($this->seller_info['member_id']);
        $this->store_info = $model_store->getStoreInfoByID($this->seller_info['store_id']);
        $this->seller_group_info = $model_seller_group->getSellerGroupInfo(array('group_id' => $this->seller_info['seller_group_id']));

        // 店铺等级
        if (intval($this->store_info['is_own_shop']) === 1) {
            $this->store_grade = array(
                'sg_id' => '0',
                'sg_name' => '自营店铺专属等级',
                'sg_goods_limit' => '0',
                'sg_album_limit' => '0',
                'sg_space_limit' => '999999999',
                'sg_template_number' => '6',
                'sg_price' => '0.00',
                'sg_description' => '',
                'sg_function' => 'editor_multimedia',
                'sg_sort' => '0',
            );
        } else {
            $store_grade = rkcache('store_grade', true);
            $this->store_grade = $store_grade[$this->store_info['grade_id']];
        }

        if(empty($this->member_info)) {
            output_error('请登录', array('login' => '0'));
        } else {
            $this->seller_info['client_type'] = $mb_seller_token_info['client_type'];
        }
    }
}

class mobilefenxiaoControl extends mobileControl{

    protected $member_info = array();

    public function __construct() {
        parent::__construct();

        $model_mb_user_token = Model('mb_user_token');
        $key = $_POST['key'];
        if(empty($key)) {
            $key = $_GET['key'];
        }
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
        if(empty($mb_user_token_info)) {
            output_error('请登录', array('login' => '0'));
        }

        $model_member = Model('member');
        $this->member_info = $model_member->getMemberInfoByID($mb_user_token_info['member_id']);

        if(empty($this->member_info)) {
            output_error('请登录', array('login' => '0'));
        } else {
            if(!in_array($this->member_info['fx_state'],array(2,4,5))) {
                output_error('请先认证成为分销员', array('is_fxuser' => '0'));
            }
            $member_gradeinfo = $model_member->getOneMemberGrade(intval($this->member_info['member_exppoints']));
            $this->member_info['level'] = $member_gradeinfo['level'];
            $this->member_info['client_type'] = $mb_user_token_info['client_type'];
            $this->member_info['openid'] = $mb_user_token_info['openid'];
            $this->member_info['token'] = $mb_user_token_info['token'];

            //读取卖家信息
            $seller_info = Model('seller')->getSellerInfo(array('member_id'=>$this->member_info['member_id']));
            $this->member_info['store_id'] = $seller_info['store_id'];

            //可提现金额
            $available_trad = $this->member_info['trad_amount'];

            //冻结金额
            $freeze_trad = floatval($this->member_info['freeze_trad']);
            if($this->member_info['fx_state'] == 2){
                if($this->member_info['trad_amount'] >= C('fenxiao_bill_limit')){
                    $freeze_trad += C('fenxiao_bill_limit');
                    $available_trad -= C('fenxiao_bill_limit');
                }else{
                    $freeze_trad += $this->member_info['trad_amount'];
                    $available_trad = 0;
                }
            }
            $this->member_info['available_fx_trad'] = $available_trad;
            $this->member_info['freeze_fx_trad'] = $freeze_trad;
        }
    }

    public function getOpenId()
    {
        return $this->member_info['openid'];
    }

    public function setOpenId($openId)
    {
        $this->member_info['openid'] = $openId;
        Model('mb_user_token')->updateMemberOpenId($this->member_info['token'], $openId);
    }
}

class mobiledistributionControl extends mobileControl{

    protected $member_info = array();

    public function __construct() {
        parent::__construct();

        $model_mb_user_token = Model('mb_user_token');
        $key = $_POST['key'];
        if(empty($key)) {
            $key = $_GET['key'];
        }
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
        if(empty($mb_user_token_info)) {
            output_error('请登录', array('login' => '0'));
        }

        $model_member = Model('member');
        $this->member_info = $model_member->getMemberInfoByID($mb_user_token_info['member_id']);

        if(empty($this->member_info)) {
            output_error('请登录', array('login' => '0'));
        } else {
            $distribution_isuse = Model('setting')->getRowSetting('distribution_isuse');//看看是否开启分销
            if($distribution_isuse['value'] != 1){
                output_error('抱歉：非法操作');//三级分销没有启用，不允许进入模块
            }
            $member_gradeinfo = $model_member->getOneMemberGrade(intval($this->member_info['member_exppoints']));
            $this->member_info['level'] = $member_gradeinfo['level'];
            $this->member_info['client_type'] = $mb_user_token_info['client_type'];
            $this->member_info['openid'] = $mb_user_token_info['openid'];
            $this->member_info['token'] = $mb_user_token_info['token'];

            //读取卖家信息
            $seller_info = Model('seller')->getSellerInfo(array('member_id'=>$this->member_info['member_id']));
            $this->member_info['store_id'] = $seller_info['store_id'];

            //可提现金额
            $available_trad = $this->member_info['trad_amount'];

            //冻结金额
            $freeze_trad = floatval($this->member_info['freeze_trad']);
            if($this->member_info['fx_state'] == 2){
                if($this->member_info['trad_amount'] >= C('fenxiao_bill_limit')){
                    $freeze_trad += C('fenxiao_bill_limit');
                    $available_trad -= C('fenxiao_bill_limit');
                }else{
                    $freeze_trad += $this->member_info['trad_amount'];
                    $available_trad = 0;
                }
            }
            $this->member_info['available_fx_trad'] = $available_trad;
            $this->member_info['freeze_fx_trad'] = $freeze_trad;
        }
    }
}

class mobileteamControl extends mobileControl{

    protected $member_info = array();

    public function __construct() {
        parent::__construct();

        $model_mb_user_token = Model('mb_user_token');
        $key = $_POST['key'];
        if(empty($key)) {
            $key = $_GET['key'];
        }
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
        if(empty($mb_user_token_info)) {
            output_error('请登录', array('login' => '0'));
        }

        $model_member = Model('member');
        $this->member_info = $model_member->getMemberInfoByID($mb_user_token_info['member_id']);

        if(empty($this->member_info)) {
            output_error('请登录', array('login' => '0'));
        } else {
            $team_isuse = Model('setting')->getRowSetting('team_isuse');//看看是否开启团队无限级
            if($team_isuse['value'] != 1){
                output_error('抱歉：非法操作');//团队无限级没有启用，不允许进入模块
            }
            $member_chain_info = Model('member')->getMemberChainInfo(array('member_id'=>$this->member_info['member_id']),'team_level');
            if($member_chain_info['team_level'] < 1){
                output_error('抱歉：您还不具备团队身份');//没有团队无限级身份，不允许进入模块
            }
            $member_gradeinfo = $model_member->getOneMemberGrade(intval($this->member_info['member_exppoints']));
            $this->member_info['level'] = $member_gradeinfo['level'];
            $this->member_info['client_type'] = $mb_user_token_info['client_type'];
            $this->member_info['openid'] = $mb_user_token_info['openid'];
            $this->member_info['token'] = $mb_user_token_info['token'];

            //读取卖家信息
            $seller_info = Model('seller')->getSellerInfo(array('member_id'=>$this->member_info['member_id']));
            $this->member_info['store_id'] = $seller_info['store_id'];

            //可提现金额
            $available_trad = $this->member_info['trad_amount'];

            //冻结金额
            $freeze_trad = floatval($this->member_info['freeze_trad']);
            
            $this->member_info['available_fx_trad'] = $available_trad;
            $this->member_info['freeze_fx_trad'] = $freeze_trad;
        }
    }
}

class mobileagentControl extends mobileControl{

    protected $member_info = array();

    public function __construct() {
        parent::__construct();

        $model_mb_user_token = Model('mb_user_token');
        $key = $_POST['key'];
        if(empty($key)) {
            $key = $_GET['key'];
        }
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
        if(empty($mb_user_token_info)) {
            output_error('请登录', array('login' => '0'));
        }

        $model_member = Model('member');
        $this->member_info = $model_member->getMemberInfoByID($mb_user_token_info['member_id']);

        if(empty($this->member_info)) {
            output_error('请登录', array('login' => '0'));
        } else {
            $agent_isuse = Model('setting')->getRowSetting('agent_isuse');//看看是否开启区域代理
            if($agent_isuse['value'] != 1){
                output_error('抱歉：非法操作');//区域代理没有启用，不允许进入模块
            }
			
			$is_apply = Model('agent_apply_log')->where('status = 0 AND member_id = ' . $this->member_info['member_id'])->find();
			if($is_apply){
				output_error('您的申请正在审核，请耐心等候审核结果！');
			}
			
            $member_chain_info = Model('member')->getMemberChainInfo(array('member_id'=>$this->member_info['member_id']),'agent_area_id');
            if($member_chain_info['agent_area_id'] < 1){
                output_error('抱歉：您还不是区域代理', array('is_agent' => '0'));//没有区域代理无限级身份，不允许进入模块
            }
            $member_gradeinfo = $model_member->getOneMemberGrade(intval($this->member_info['member_exppoints']));
            $this->member_info['level'] = $member_gradeinfo['level'];
            $this->member_info['client_type'] = $mb_user_token_info['client_type'];
            $this->member_info['openid'] = $mb_user_token_info['openid'];
            $this->member_info['token'] = $mb_user_token_info['token'];

            //读取卖家信息
            $seller_info = Model('seller')->getSellerInfo(array('member_id'=>$this->member_info['member_id']));
            $this->member_info['store_id'] = $seller_info['store_id'];

            //可提现金额
            $available_trad = $this->member_info['trad_amount'];

            //冻结金额
            $freeze_trad = floatval($this->member_info['freeze_trad']);
            
            $this->member_info['available_fx_trad'] = $available_trad;
            $this->member_info['freeze_fx_trad'] = $freeze_trad;
        }
    }
}

class mobiledisreturnControl extends mobileControl{

    protected $member_info = array();

    public function __construct() {
        parent::__construct();

        $model_mb_user_token = Model('mb_user_token');
        $key = $_POST['key'];
        if(empty($key)) {
            $key = $_GET['key'];
        }
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
        if(empty($mb_user_token_info)) {
            output_error('请登录', array('login' => '0'));
        }

        $model_member = Model('member');
        $this->member_info = $model_member->getMemberInfoByID($mb_user_token_info['member_id']);

        if(empty($this->member_info)) {
            output_error('请登录', array('login' => '0'));
        } else {
            $buy_return_isuse = Model('setting')->getRowSetting('buy_return_isuse');//看看是否开启单品消费返利
            $full_return_isuse = Model('setting')->getRowSetting('full_return_isuse');//看看是否开启满额消费返利
            if($buy_return_isuse['value'] != 1 && $full_return_isuse['value'] != 1){
                output_error('抱歉：非法操作');//两个模块都没有启用，不允许进入
            }
            $member_gradeinfo = $model_member->getOneMemberGrade(intval($this->member_info['member_exppoints']));
            $this->member_info['level'] = $member_gradeinfo['level'];
            $this->member_info['client_type'] = $mb_user_token_info['client_type'];
            $this->member_info['openid'] = $mb_user_token_info['openid'];
            $this->member_info['token'] = $mb_user_token_info['token'];

            //读取卖家信息
            $seller_info = Model('seller')->getSellerInfo(array('member_id'=>$this->member_info['member_id']));
            $this->member_info['store_id'] = $seller_info['store_id'];

            //可提现金额
            $available_trad = $this->member_info['trad_amount'];

            //冻结金额
            $freeze_trad = floatval($this->member_info['freeze_trad']);
            
            $this->member_info['available_fx_trad'] = $available_trad;
            $this->member_info['freeze_fx_trad'] = $freeze_trad;
        }
    }
}


class mobileshareholderControl extends mobileControl{

    protected $member_info = array();

    public function __construct() {
        parent::__construct();

        $model_mb_user_token = Model('mb_user_token');
        $key = $_POST['key'];
        if(empty($key)) {
            $key = $_GET['key'];
        }
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
        if(empty($mb_user_token_info)) {
            output_error('请登录', array('login' => '0'));
        }

        $model_member = Model('member');
        $this->member_info = $model_member->getMemberInfoByID($mb_user_token_info['member_id']);

        if(empty($this->member_info)) {
            output_error('请登录', array('login' => '0'));
        } else {
            $shareholder_isuse = Model('setting')->getRowSetting('shareholder_isuse');//看看是否开启股东分红
            if($shareholder_isuse['value'] != 1){
                output_error('抱歉：非法操作');//模块没有启用，不允许进入
            }
            $member_gradeinfo = $model_member->getOneMemberGrade(intval($this->member_info['member_exppoints']));
            $this->member_info['level'] = $member_gradeinfo['level'];
            $this->member_info['client_type'] = $mb_user_token_info['client_type'];
            $this->member_info['openid'] = $mb_user_token_info['openid'];
            $this->member_info['token'] = $mb_user_token_info['token'];
			
			//获取用户分销商等级和权重
			$member_level = Model()->field('distribution_level.level_weight,member_chain.distribution_level')->table('member_chain,distribution_level')->join('left')->on('distribution_level.id = member_chain.distribution_level')->where('member_chain.member_id = ' . $this->member_info['member_id'])->find();
			//获取股东分红最低等级权限
			$shareholder_level = Model('setting')->getRowSetting('shareholder_level');
			if($member_level['level_weight'] < $shareholder_level['value']){
				output_error('抱歉：您的等级不享受股东分红');//用户分销等级不够，提醒用户
			}
			$this->member_info['agent_level'] = $member_level['distribution_level'];
			$this->member_info['agent_level_weigth'] = $member_level['level_weight'];

            //读取卖家信息
            $seller_info = Model('seller')->getSellerInfo(array('member_id'=>$this->member_info['member_id']));
            $this->member_info['store_id'] = $seller_info['store_id'];

            //可提现金额
            $available_trad = $this->member_info['trad_amount'];

            //冻结金额
            $freeze_trad = floatval($this->member_info['freeze_trad']);
            
            $this->member_info['available_fx_trad'] = $available_trad;
            $this->member_info['freeze_fx_trad'] = $freeze_trad;
        }
    }
}
