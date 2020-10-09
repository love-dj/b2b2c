<?php
/**
 * 验证码
 *


 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');


class vercodeControl{
    /**
     * 生成验证码标识
     */
    public function makecodekeyWt(){
        $key = $this->makeApiVercodeKey();
        output_data(array('codekey' => $key));
    }

	public function indexWt(){
		$this->verifycodeWt();
	}

    /**
     * 产生验证码
     *
     */
    public function verifycodeWt(){
        $param = $_GET;
        $key = $param['k']?trim($param['k']):'';
        if (!$key) {
            die(false);
        }
        $vcode = buildVercode(false);
        $result = Model('apivercode')->addApiVercode($key,$vcode);
        if (!$result) {
            die(false);
        }
        $width = 120;
        $height = 50;
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
     * 产生验证码名称标识
     *
     * @param string $wthash 哈希数
     * @return string
     */
    private function makeApiVercodeKey(){
        return md5(uniqid(mt_rand(), true));
    }

}
