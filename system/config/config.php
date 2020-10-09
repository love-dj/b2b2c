<?php
// ShopWT
//$baseUrl = "http://b2b2c.longbasz.cn";
$baseUrl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'];
$data = '';
$node_chat = false;
$cache_file_path = BASE_DATA_PATH . '/config/site_imchat.php';
if (file_exists($cache_file_path)) {
    include_once $cache_file_path;
    $result['site_imchat'] = $data;
    if($result['site_imchat'] == 1){
        $node_chat = true;
    }
}
$config = array(
		'base_site_url' 	   => $baseUrl,
		'fenxiao_site_url'     => $baseUrl.'/fenxiao',
		'news_site_url'        => $baseUrl.'/news',
		'what_site_url'        => $baseUrl.'/what',
		'bbs_site_url'         => $baseUrl.'/bbs',
		'admin_site_url'       => $baseUrl.'/wt',
		'wap_site_url'         => $baseUrl.'/mobile',
		'mobile_site_url'      => $baseUrl.'/api/mobile',
		'wxmini_site_url'      => $baseUrl.'/wxmini',
		'chat_site_url'        => $baseUrl.'/api/im',
		'node_site_url' 	   => $baseUrl.':33', //如果要启用IM，把127.0.0.1修改为：您的服务器 IP 
		'delivery_site_url'    => $baseUrl.'/since',
		'chain_site_url'       => $baseUrl.'/store',
		'member_site_url'      => $baseUrl.'/home',
		'upload_site_url'      => $baseUrl.'/system/upfiles',
		'static_site_url'      => $baseUrl.'/system/static',
		'version'              => '20190313001',
		'setup_date'           => '2019-05-09 16:17:27',
		'gip'                  => 0,
		'dbdriver'             => 'mysqli',
		'tablepre'             => 'lbsz_',
		'db'				   => array(
									'1' => array(
										'dbhost'       => 'localhost',
										'dbport'       => '3306',
										'dbuser'       => 'longbasz',
										'dbpwd'        => 'longbajituan',
										'dbname'       => 'b2b2c',
										'dbcharset'    => 'UTF-8'
									),
									'slave'            => ''
									),

		'session_expire'       => 3600,
		'lang_type'            => 'zh_cn',
		'cookie_pre'           => 'D03A_',
		'cache_open'           => false,
		'debug'                => false,
		'url_model'            => false, //如果要启用伪静态，把false修改为true
		'subdomain_suffix'     => '',//如果要启用店铺二级域名，请填写不带www的域名，比如longbasz.com
		'node_chat'            => $node_chat,//如果要启用IM，把false修改为true
		'flowstat_tablenum'    => 3,//流量统计表，默认3，不要随意修改，会造成统计数据错误
		'queue'       		   => array(
									'open'      => false,
									'host'      => '127.0.0.1',
									'port'      => 6379
								),
		'https' 			   => false,
		'sys_log'       	   => true
);
return $config;