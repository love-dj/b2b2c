<?php
/**
 * 分享积分
 * 


 

 *
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class sharepointControl extends mobileHomeControl{

    public function __construct() {
        parent::__construct();
    } 

    
    public function indexWt(){
		$this->shareWt();
    }
	
	public function shareWt(){
		$member_id=$this->getMemberIdIfExists();
		if($member_id>0 && C('points_share_isuse')==1 && C('points_isuse')==1){
			$member_info = Model('member')->getMemberInfoByID($member_id);
			if(!empty($member_info) && isset($_GET['gid']) && intval($_GET['gid'])>0){
				$share_model = Model('share_pointslog');
				
				$start_time = strtotime(date("Y-m-d 00:00:00",time()));
				$where = array();
				$where['member_id'] = $member_info['member_id'];
				$where['addtime'] = array('egt',$start_time);
				$count_points = $share_model->getPoints($where);
				if($count_points<intval(C('points_share_daypoint'))){
					$where['goods_id'] = intval($_GET['gid']);
					$info = $share_model->getInfo($where);
					if(empty($info)){
						$insert = array();
						$insert['goods_id'] = intval($_GET['gid']);
						$insert['member_id'] = $member_info['member_id'];
						$insert['points'] = intval(C('points_share_onepoint'));
						$insert['share_type'] = 1;
						$insert['ip'] = getIp();
						$insert['addtime'] = time();
						$share_model->add($insert);
						Model('points')->savePointsLog('share',array('pl_memberid'=>$member_info['member_id'],'pl_membername'=>$member_info['member_name']));
					}
				}
				
			}
			
		}
		
	}


}
