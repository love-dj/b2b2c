<?php
/**
 * 返利推广 V6.5
 *
 *


 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');


class inviteControl extends mobileHomeControl {

	public function __construct() {
		parent::__construct();
	}

    /**
     * 添加返利
     */
    public function indexWt() {
		$member_id=$_POST['smid'];
		$m_info = Model('member')->getMemberInfoByID($member_id);
		$encode_member_id = base64_encode(intval($member_id)*1);
		$member_info = array();
		$member_info['member_name'] = $m_info['member_name'];
		$member_info['member_id'] = $m_info['member_id'];
		$member_info['member_qrc'] = $encode_member_id;
		$member_info['points_invite'] = intval(C('points_invite'));
		$seo = Model('seo')->type('index')->showwap();
		$member_info['html_title'] = $seo['html_title'];
		$member_info['seo_description'] = $seo['seo_description'];
        output_data(array('member_info' => $member_info));

       
    }
}
