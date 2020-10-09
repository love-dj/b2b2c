<?php
/**
 * 邀请返利页面 
 * by ShopWTV5 ww w.33 ha o.c om 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');
class inviteControl extends BaseHomeControl{
	public function indexWt(){
		$myurl=$this->maker_qrcodeWt($_SESSION['member_id']);
		Tpl::output('myurl', $myurl);
		
		$mydownurl=BASE_SITE_URL."/index.php?w=invite&t=downqrfile&id=".$_SESSION['member_id'];
		Tpl::output('mydownurl', $mydownurl);
		Tpl::showpage('invite');
	}
	
	public function maker_qrcodeWt($id)
	{
		$id=intval($id);
		if($id<=0)
		{
			$id = intval($_GET['id']);
			
		}
		if($id<=0)
		{
		   return UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.'default_qrcode.png';
		}
		
		$str_member="memberqr_".$id;
		$imgfile=BASE_UPLOAD_PATH.DS."shop".DS."member".DS.$str_member . '.png';
		if(!file_exists($imgfile)){	
			$member_id = base64_encode(intval($id)*1);
			$myurl=BASE_SITE_URL."/#WT".$member_id;
			require_once(BASE_STATIC_PATH.DS.'phpqrcode'.DS.'index.php');
			$PhpQRCode = new PhpQRCode();
			
			$PhpQRCode->set('pngTempDir',BASE_UPLOAD_PATH.DS."shop".DS."member".DS);
			$PhpQRCode->set('date',$myurl);
			$PhpQRCode->set('pngTempName', $str_member . '.png');
			$PhpQRCode->init();
		}
		return UPLOAD_SITE_URL.DS."shop".DS."member".DS.$str_member.'.png';
		
	}
	
	public function downqrfileWt()
    {
	   
		$id=$_GET['id'];
		if($id<=0)die('请先登录会员后，再来这里操作哦。');
		$str_member="mqr_".$id;
		$fileurl=BASE_UPLOAD_PATH.DS."shop".DS."member".DS.$str_member.".png";
		
		ob_start(); 
		$filename=$fileurl;
		$date=date("Ymd-H:i:m");
		header( "Content-type:  application/octet-stream "); 
		header( "Accept-Ranges:  bytes "); 
		header( "Content-Disposition:  attachment;  filename= {$str_member}.png"); 
		$size=readfile($filename); 
		header( "Accept-Length: " .$size);
    }
	public function downqrfilehbWt()
    {
	   
		$id=$_GET['id'];
		if($id<=0)die('请先登录会员后，再来这里操作哦。');
		$str_member="mqr_".$id.'hb';
		$fileurl=BASE_UPLOAD_PATH.DS."shop".DS."member".DS.'invitehb'.DS.$str_member.".png";
		
		ob_start(); 
		$filename=$fileurl;
		$date=date("Ymd-H:i:m");
		header( "Content-type:  application/octet-stream "); 
		header( "Accept-Ranges:  bytes "); 
		header( "Content-Disposition:  attachment;  filename= {$str_member}.png"); 
		$size=readfile($filename); 
		header( "Accept-Length: " .$size);
    }
	
}
