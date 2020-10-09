<?php
/**
 * 我的余额
 *
 *


 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');


class member_inviteControl extends mobileMemberControl {

	public function __construct() {
		parent::__construct();
	}

    /**
     * 添加返利
     */
    public function indexWt() {
		$member_id=$this->member_info['member_id'];
		$encode_member_id = base64_encode(intval($member_id)*1);
	    //$myurl=BASE_SITE_URL."/#WT".$encode_member_id;
		$myurl = WAP_SITE_URL.'/html/invite.html?smid='.$member_id;
		
		$str_member="mqr_".$member_id;
		$myurl_src=UPLOAD_SITE_URL.DS."shop".DS."member".DS.$str_member.'.png';
		$imgfile=BASE_UPLOAD_PATH.DS."shop".DS."member".DS.$str_member . '.png';
		if(!file_exists($imgfile)){			
			require_once(BASE_STATIC_PATH.DS.'phpqrcode'.DS.'index.php');
			$PhpQRCode = new PhpQRCode();
			
			$PhpQRCode->set('pngTempDir',BASE_UPLOAD_PATH.DS."shop".DS."member".DS);
			$PhpQRCode->set('date',$myurl);
			$PhpQRCode->set('pngTempName', $str_member . '.png');
			$PhpQRCode->init();
		}
		$member_info = array();
        $member_info['user_name'] = $this->member_info['member_name'];
		$member_info['user_id'] = $this->member_info['member_id'];
        $member_info['avator'] = getMemberAvatarForID($this->member_info['member_id']);
        $member_info['point'] = $this->member_info['member_points'];
        $member_info['predepoit'] = $this->member_info['available_predeposit'];
		$member_info['available_rc_balance'] = $this->member_info['available_rc_balance'];
        $member_info['myurl']=$myurl;
		$member_info['myurl_src']=$myurl_src;
		//下载连接
		$mydownurl=BASE_SITE_URL."/index.php?w=invite&t=downqrfile&id=".$member_id;
		$member_info['mydownurl']= $mydownurl;
		$seo = Model('seo')->type('index')->showwap();
		$member_info['html_title'] = $seo['html_title'];
		$member_info['seo_description'] = $seo['seo_description'];
        output_data(array('member_info' => $member_info));

       
    }
	/**

     * 获取一级会员佣金列表

     */

    public function inviteoneWt() {

		 //查询佣金日志列表

		$member_model = Model('member');

		$page = new Page();

		$memberid = $this->member_info['member_id'];

		$condition = array();

		$condition['invite_one'] = $memberid ;

        $list = $member_model->getMembersList($condition,$page);     

		

		if($list){



		//计算用户的累计返利金额

		foreach($list as $key => $val)

		{

			//获取佣金订单数量

			$invite_num = Model('points')->getOrderInviteCount($memberid,$val['member_id']);

			if($invite_num>0){

				$list[$key]['invite_num']=$invite_num;

			}else{

				$list[$key]['invite_num']=0;

					}

			//获取佣金总积分

		    $invite_amount = Model('points')->getOrderInviteamount($memberid,$val['member_id']);

			if($invite_amount>0){

				$list[$key]['invite_amount']=$invite_amount;

			}else{

				$list[$key]['invite_amount']=0;

					}

		}}

		

		$page_count = $member_model->gettotalpage();

        output_data(array('list' => $list),mobile_page($page_count));

    }

   /**

     * 获取二级会员佣金列表

     */

    public function invitetwoWt() {

		 //查询佣金日志列表

		$member_model = Model('member');

		$page = new Page();

		$memberid = $this->member_info['member_id'];

		$condition = array();

		$condition['invite_two'] = $memberid ;

        $list = $member_model->getMembersList($condition,$page);

		if($list){



		//计算用户的累计返利金额

		foreach($list as $key => $val)

		{

			//获取佣金订单数量

			$invite_num = Model('points')->getOrderInviteCount($memberid,$val['member_id']);

			if($invite_num>0){

				$list[$key]['invite_num']=$invite_num;

			}else{

				$list[$key]['invite_num']=0;

					}

			//获取佣金总金额

		    $invite_amount = Model('points')->getOrderInviteamount($memberid,$val['member_id']);

			if($invite_amount>0){

				$list[$key]['invite_amount']=$invite_amount;

			}else{

				$list[$key]['invite_amount']=0;

					}

		}}

		

		$page_count = $member_model->gettotalpage();

        output_data(array('list' => $list),mobile_page($page_count));

    }

	

  /**

     * 获取三级会员佣金列表

     */

    public function invitethirWt() {

		 //查询佣金日志列表

		$member_model = Model('member');

		$page = new Page();

		$memberid = $this->member_info['member_id'];

		$condition = array();

		$condition['invite_three'] = $memberid ;

        $list = $member_model->getMembersList($condition,$page);

		if($list){



		//计算用户的累计返利金额

		foreach($list as $key => $val)

		{

			//获取佣金订单数量

			$invite_num = Model('points')->getOrderInviteCount($memberid,$val['member_id']);

			if($invite_num>0){

				$list[$key]['invite_num']=$invite_num;

			}else{

				$list[$key]['invite_num']=0;

					}

			//获取佣金总金额

		    $invite_amount = Model('points')->getOrderInviteamount($memberid,$val['member_id']);

			if($invite_amount>0){

				$list[$key]['invite_amount']=$invite_amount;

			}else{

				$list[$key]['invite_amount']=0;

					}

		}}

		

		$page_count = $member_model->gettotalpage();

        output_data(array('list' => $list),mobile_page($page_count));

    }
}
