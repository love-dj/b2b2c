<?php
/**
 * 验证码
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class vercodeControl{
    public function __construct(){
    }
    
	public function indexWt(){
		$this->verifycodeWt();
	}

    /**
     * 产生验证码
     *
     */
    public function verifycodeWt(){
        $refererhost = parse_url($_SERVER['HTTP_REFERER']);
        $refererhost['host'] .= !empty($refererhost['port']) ? (':'.$refererhost['port']) : '';
        $vcode = buildVercode();
		
		$width = 90;
        $height = 26;
        if ($_GET['type']) {
            $param = explode('x', $_GET['type']);
            $width = intval($param[1]);
            $height = intval($param[0]);
        }
		
        $verify =     new verifycode();
        $verify->imageH=$height;
        $verify->imageW=$width;
        $verify->vcode=$vcode;
        $verify->entry();
    }

    /**
     * AJAX验证
     *
     */
    public function checkWt(){
        if (checkVercode($_GET['wthash'],$_GET['captcha'])){
            exit('true');
        }else{
            exit('false');
        }
    }
}
