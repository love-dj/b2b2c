<?php
/**
 * 我的推广
 *
 *


 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class member_inviteControl extends BaseMemberControl {
    public function __construct() {
        parent::__construct();
    }

    public function indexWt() {
        Language::read("home_login_index,member_invite");
        $lang   = Language::getLangContent();
        $this->firstWt();
        exit;
    }

    //一级推广
    public function firstWt() {
		 Language::read("member_invite");
        $lang   = Language::getLangContent();
        $model_invite = Model('member');

        $condition = array();
        $condition['invite_one'] = $_SESSION['member_id'];

        $invite_list = $model_invite->getMemberList($condition, '*');

        if (is_array($invite_list) && !empty($invite_list)) {
            //计算用户的累计返利金额
            foreach ($invite_list as $key => $value) {
				$invite_num = Model('points')->getOrderInviteCount($_SESSION['member_id'],$value['member_id']);
				if($invite_num>0){
					$invite_list[$key]['buy_count']=$invite_num;
				}else{
					$invite_list[$key]['buy_count']=0;
				}
				
				//获取佣金总金额
				$invite_amount = Model('points')->getOrderInviteamount($_SESSION['member_id'],$value['member_id']);
				if($invite_amount>0){
					$invite_list[$key]['refund_amount']=$invite_amount;
				}else{
					$invite_list[$key]['refund_amount']=0;
				}
            }
        }

        //信息输出
        self::profile_menu('first');
        Tpl::output('show_page', $model_invite->showpage());
        Tpl::output('invite_list', $invite_list);
        Tpl::showpage('member_invite.list');
    }

    //二级推广
    public function secondWt() {

         Language::read("member_invite");
        $lang   = Language::getLangContent();
        $model_invite = Model('member');

        $condition = array();
        $condition['invite_two'] = $_SESSION['member_id'];

        $invite_list = $model_invite->getMemberList($condition, '*');

        if (is_array($invite_list) && !empty($invite_list)) {
            //计算用户的累计返利金额
            foreach ($invite_list as $key => $value) {
				$invite_num = Model('points')->getOrderInviteCount($_SESSION['member_id'],$value['member_id']);
				if($invite_num>0){
					$invite_list[$key]['buy_count']=$invite_num;
				}else{
					$invite_list[$key]['buy_count']=0;
				}
				
				//获取佣金总金额
				$invite_amount = Model('points')->getOrderInviteamount($_SESSION['member_id'],$value['member_id']);
				if($invite_amount>0){
					$invite_list[$key]['refund_amount']=$invite_amount;
				}else{
					$invite_list[$key]['refund_amount']=0;
				}
            }
        }

        //信息输出
        self::profile_menu('second');
        Tpl::output('show_page', $model_invite->showpage());
        Tpl::output('invite_list', $invite_list);
        Tpl::showpage('member_invite.list');
    }

    //三级推广
    public function thirdWt() {

         Language::read("member_invite");
        $lang   = Language::getLangContent();
        $model_invite = Model('member');

        $condition = array();
        $condition['invite_three'] = $_SESSION['member_id'];

        $invite_list = $model_invite->getMemberList($condition, '*');

        if (is_array($invite_list) && !empty($invite_list)) {
            //计算用户的累计返利金额
            foreach ($invite_list as $key => $value) {
				$invite_num = Model('points')->getOrderInviteCount($_SESSION['member_id'],$value['member_id']);
				if($invite_num>0){
					$invite_list[$key]['buy_count']=$invite_num;
				}else{
					$invite_list[$key]['buy_count']=0;
				}
				
				//获取佣金总金额
				$invite_amount = Model('points')->getOrderInviteamount($_SESSION['member_id'],$value['member_id']);
				if($invite_amount>0){
					$invite_list[$key]['refund_amount']=$invite_amount;
				}else{
					$invite_list[$key]['refund_amount']=0;
				}
            }
        }
        //信息输出
        self::profile_menu('third');
        Tpl::output('show_page', $model_invite->showpage());        
        Tpl::output('invite_list', $invite_list);
        Tpl::showpage('member_invite.list');
    }
    //三级推广
    public function viewWt() {
		$member_id=$_SESSION['member_id'];
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
        $member_info['myurl']=BASE_SITE_URL."/#WT".base64_encode(intval($member_id)*1);
		$member_info['myurl_src']=$myurl_src;
		//下载连接
		$mydownurl=BASE_SITE_URL."/index.php?w=invite&t=downqrfile&id=".$member_id;
		$member_info['mydownurl']= $mydownurl;
		
		//生成海报图片
		$imgfile_hb=BASE_UPLOAD_PATH.DS."shop".DS."member".DS.'invitehb'.DS.$str_member . 'hb.png';
		if(!file_exists($imgfile_hb)){	
			$config = array(
			  'image'=>array(
				array(
				  'url'=>$imgfile,     //二维码资源
				  'stream'=>0,
				  'left'=>223,
				  'top'=>842,
				  'right'=>0,
				  'bottom'=>0,
				  'width'=>300,
				  'height'=>300,
				  'opacity'=>100
				)
			  ),
			  'background'=>BASE_UPLOAD_PATH.DS."shop".DS."member".DS.'invitehb'.DS.'invite_hb.jpg'          //背景图
			);
			
			$this->createPoster($config,$imgfile_hb);
		}
		$member_info['myurlhb_src']= UPLOAD_SITE_URL.DS."shop".DS."member".DS.'invitehb'.DS.$str_member.'hb.png';
		$member_info['mydownurl_hb']= BASE_SITE_URL."/index.php?w=invite&t=downqrfilehb&id=".$member_id;
		
		
		Tpl::output('member_info', $member_info);
        //信息输出
        self::profile_menu('view');
        Tpl::showpage('member_invite.view');
    }
	/**
	 * 生成宣传海报
	 * @param array  参数,包括图片和文字
	 * @param string  $filename 生成海报文件名,不传此参数则不生成文件,直接输出图片
	 * @return [type] [description]
	 */
	private function createPoster($config=array(),$filename=""){
		  //如果要看报什么错，可以先注释调这个header
		  if(empty($filename)) header("content-type: image/png");
		  $imageDefault = array(
			'left'=>0,
			'top'=>0,
			'right'=>0,
			'bottom'=>0,
			'width'=>100,
			'height'=>100,
			'opacity'=>100
		  );
		  $textDefault = array(
			'text'=>'',
			'left'=>0,
			'top'=>0,
			'fontSize'=>32,       //字号
			'fontColor'=>'255,255,255', //字体颜色
			'angle'=>0,
		  );
		  $background = $config['background'];//海报最底层得背景
		  //背景方法
		  $backgroundInfo = getimagesize($background);
		  $backgroundFun = 'imagecreatefrom'.image_type_to_extension($backgroundInfo[2], false);
		  $background = $backgroundFun($background);
		  $backgroundWidth = imagesx($background);  //背景宽度
		  $backgroundHeight = imagesy($background);  //背景高度
		  $imageRes = imageCreatetruecolor($backgroundWidth,$backgroundHeight);
		  $color = imagecolorallocate($imageRes, 0, 0, 0);
		  imagefill($imageRes, 0, 0, $color);
		  // imageColorTransparent($imageRes, $color);  //颜色透明
		  imagecopyresampled($imageRes,$background,0,0,0,0,imagesx($background),imagesy($background),imagesx($background),imagesy($background));
		  //处理了图片
		  if(!empty($config['image'])){
			foreach ($config['image'] as $key => $val) {
			  $val = array_merge($imageDefault,$val);
			  $info = getimagesize($val['url']);
			  $function = 'imagecreatefrom'.image_type_to_extension($info[2], false);
			  if($val['stream']){   //如果传的是字符串图像流
				$info = getimagesizefromstring($val['url']);
				$function = 'imagecreatefromstring';
			  }
			  $res = $function($val['url']);
			  $resWidth = $info[0];
			  $resHeight = $info[1];
			  //建立画板 ，缩放图片至指定尺寸
			  $canvas=imagecreatetruecolor($val['width'], $val['height']);
			  imagefill($canvas, 0, 0, $color);
			  //关键函数，参数（目标资源，源，目标资源的开始坐标x,y, 源资源的开始坐标x,y,目标资源的宽高w,h,源资源的宽高w,h）
			  imagecopyresampled($canvas, $res, 0, 0, 0, 0, $val['width'], $val['height'],$resWidth,$resHeight);
			  $val['left'] = $val['left']<0?$backgroundWidth- abs($val['left']) - $val['width']:$val['left'];
			  $val['top'] = $val['top']<0?$backgroundHeight- abs($val['top']) - $val['height']:$val['top'];
			  //放置图像
			  imagecopymerge($imageRes,$canvas, $val['left'],$val['top'],$val['right'],$val['bottom'],$val['width'],$val['height'],$val['opacity']);//左，上，右，下，宽度，高度，透明度
			}
		  }
		  //处理文字
		  if(!empty($config['text'])){
			foreach ($config['text'] as $key => $val) {
			  $val = array_merge($textDefault,$val);
			  list($R,$G,$B) = explode(',', $val['fontColor']);
			  $fontColor = imagecolorallocate($imageRes, $R, $G, $B);
			  $val['left'] = $val['left']<0?$backgroundWidth- abs($val['left']):$val['left'];
			  $val['top'] = $val['top']<0?$backgroundHeight- abs($val['top']):$val['top'];
			  imagettftext($imageRes,$val['fontSize'],$val['angle'],$val['left'],$val['top'],$fontColor,$val['fontPath'],$val['text']);
			}
		  }
		  //生成图片
		  if(!empty($filename)){
			$res = imagejpeg ($imageRes,$filename,90); //保存到本地
			imagedestroy($imageRes);
			if(!$res) return '';
			return $filename;
		  }else{
			//imagejpeg ($imageRes);     //在浏览器上显示
			//imagedestroy($imageRes);
		  }
	}
    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_key = ''){
        $menu_array = array(
            array('menu_key'=>'first', 'menu_name'=>'一级推广', 'menu_url'=>'index.php?w=member_invite&t=first'),
            array('menu_key'=>'second', 'menu_name'=>'二级推广', 'menu_url'=>'index.php?w=member_invite&t=second'),
            array('menu_key'=>'third', 'menu_name'=>'三级推广', 'menu_url'=>'index.php?w=member_invite&t=third')
        );

        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }


}