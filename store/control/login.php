<?php
/**
 * 物流自提服务站首页
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class loginControl extends BaseAccountCenterControl{
    public function __construct(){
        parent::__construct();
    }
    /**
     * 登录
     */
    public function indexWt() {
        if ($_SESSION['chain_login'] == 1) {
            @header('location: index.php?w=index');die;
        }
        if (chksubmit(true,true)) {
            $where = array();
            $where['chain_user'] = $_POST['user'];
            $where['chain_pwd'] = md5($_POST['pwd']);
            $chain_info = Model('chain')->getChainInfo($where);
            if (!empty($chain_info)) {
                $_SESSION['chain_login']    = 1;
                $_SESSION['chain_id']       = $chain_info['chain_id'];
                $_SESSION['chain_store_id'] = $chain_info['store_id'];
                $_SESSION['chain_user']     = $chain_info['chain_user'];
                $_SESSION['chain_name']     = $chain_info['chain_name'];
                $_SESSION['chain_img']      = getChainImage($chain_info['chain_img'], $chain_info['store_id']);
                $_SESSION['chain_address']  = $chain_info['area_info'] . ' ' . $chain_info['chain_address'];
                $_SESSION['chain_phone']    = $chain_info['chain_phone'];
                showDialog('登录成功', 'index.php?w=index', 'succ');
            } else {
                showDialog('登录失败');
            }
        }
	//登录表单页面
            $_pic0 = @unserialize(C('seller_login_pic'));
			$_pic=array();
			if(!empty($_pic0['p1']['pic'])&&$_pic0['p1']['pic']!='')
			{
				$_pic[]=$_pic0['p1'];
			}
			if(!empty($_pic0['p2']['pic'])&&$_pic0['p2']['pic']!='')
			{
				$_pic[]=$_pic0['p2'];
			}
			if(!empty($_pic0['p3']['pic'])&&$_pic0['p3']['pic']!='')
			{
				$_pic[]=$_pic0['p3'];
			}
			if(!empty($_pic0['p4']['pic'])&&$_pic0['p4']['pic']!='')
			{
				$_pic[]=$_pic0['p4'];
			}
		
            if (count($_pic[0])>0){
				$picinfo=$_pic[array_rand($_pic)];
				$picinfo['pic']=UPLOAD_SITE_URL.'/'.ATTACH_SELLER.'/'.$picinfo['pic'];
                Tpl::output('lpic',$picinfo);
            }else{				
				$ppic=array();
				$ppic['pic']=UPLOAD_SITE_URL.'/'.ATTACH_SELLER.'/'.rand(1,4).'.jpg';
				$ppic['url']='#';
				$ppic['color']='#ffffff';
                Tpl::output('lpic',$ppic);
            }
        Tpl::showpage('login');
    }
    /**
     * 登出
     */
    public function logoutWt() {
        unset($_SESSION['chain_login']);
        unset($_SESSION['chain_id']);
        unset($_SESSION['chain_store_id']);
        unset($_SESSION['chain_user']);
        unset($_SESSION['chain_name']);
        unset($_SESSION['chain_img']);
        unset($_SESSION['chain_address']);
        unset($_SESSION['chain_phone']);
        showDialog('退出成功', 'reload', 'succ');
    }
}
