<?php
/**
 * 获取内容
 *
 */
defined('ShopWT') or exit('Access Denied By ShopWT');
class gain_contentControl extends mobileHomeControl
{
    public function __construct(){
        parent::__construct();
    }

    /**
     * 获取配置内容
     */
    public function configureWt(){
        //dumps($_POST['name']);die;
        if(empty($_POST['name'])) {
            output_error('参数错误');
        }
        $key = $_POST['name'];
        $data['code'] = 1;
        $data['key'] = C($key);
        header("Access-Control-Allow-Origin:*");
        exit(json_encode($data));

    }

}