<?php
/**
 * 核心文件
 *
 * 核心初始化类，不允许继承
 * 


 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');
final class Base{

	const CPURL = '';

	/**
	 * init
	 */
	public static function init() {
	    // config info
	    global $setting_config;
	    self::parse_conf($setting_config);
	    define('MD5_KEY',md5($setting_config['md5_key']));
         
	    if(function_exists('date_default_timezone_set')){
	        if (is_numeric($setting_config['time_zone'])){
	            @date_default_timezone_set('Asia/Shanghai');
	        }else{
	            @date_default_timezone_set($setting_config['time_zone']);
	        }
	    }

	    //session start
	    self::start_session();
	    
	    //output to the template
	    Tpl::output('setting_config',$setting_config);
	  
	    //read language
	    Language::read('core_lang_index');
	}

	/**
	 * run
	 */
	public static function run(){
	    self::cp();
	    self::init();
		self::control();
	}
	public static function runadmin($admin){
		defined('IS_ADMIN') or define('IS_ADMIN',true);
	    self::cp();
	    self::init();
		self::controladmin($admin);
	}
	/**
	 * get setting
	 */
	private static function parse_conf(&$setting_config){
		$wt_config = $GLOBALS['config'];
		if(is_array($wt_config['db']['slave']) && !empty($wt_config['db']['slave'])){
			$dbslave = $wt_config['db']['slave'];
			$sid     = array_rand($dbslave);
			$wt_config['db']['slave'] = $dbslave[$sid];
		}else{
			$wt_config['db']['slave'] = $wt_config['db'][1];
		}
		$wt_config['db']['master'] = $wt_config['db'][1];
		$setting_config = $wt_config;
		$setting = ($setting = rkcache('setting')) ? $setting : rkcache('setting',true);
		$setting['shopwt_version'] = '<span class="vol"><font class="b">Shop</font><font class="o">WT</font></span>';
		$setting_config = array_merge_recursive($setting,$wt_config);
	}

	/**
	 * 控制器调度
	 *
	 */
	private static function control(){
			
		//二级域名
		if ($GLOBALS['setting_config']['enabled_subdomain'] == '1' && $_GET['w'] == 'index' && $_GET['t'] == 'index'){
			$store_id = subdomain();
			if ($store_id > 0) $_GET['w'] = 'show_store';
		}
		$act_file = realpath(BASE_PATH.'/control/'.$_GET['w'].'.php');
		$class_name = $_GET['w'].'Control';
		if (!@include($act_file)){
		    if (C('debug')) {
		        throw_exception("Base Error: access file isn't exists!");
		    } else {
		        showMessage('抱歉！您访问的页面不存在','','html','error');
		    }
		}
		if (class_exists($class_name)){
			$main = new $class_name();
			$function = $_GET['t'].'Wt';
			if (method_exists($main,$function)){
				$main->$function();
			}elseif (method_exists($main,'indexWt')){
				$main->indexWt();
			}else {
				$error = "Base Error: function $function not in $class_name!";
				throw_exception($error);
			}
		}else {
			$error = "Base Error: class $class_name isn't exists!";
			throw_exception($error);
		}
	}
private static function controladmin($admin){
		
		//二级域名
		if ($GLOBALS['setting_config']['enabled_subdomain'] == '1' && $_GET['w'] == 'index' && $_GET['t'] == 'index'){
			$store_id = subdomain();
			if ($store_id > 0) $_GET['w'] = 'show_store';
		}
		$act_file = realpath(BASE_PATH.'/'.$admin.'/control/'.$_GET['w'].'.php');
		
		$class_name = $_GET['w'].'Control';
		if (!@include($act_file)){
		    if (C('debug')) {
		        throw_exception("Base Error: access file isn't exists!");
		    } else {
		        showMessage('抱歉！您访问的页面不存在','','html','error');
		    }
		}
		if (class_exists($class_name)){
			$main = new $class_name();
			$function = $_GET['t'].'Wt';
			if (method_exists($main,$function)){
				$main->$function();
			}elseif (method_exists($main,'indexWt')){
				$main->indexWt();
			}else {
				$error = "Base Error: function $function not in $class_name!";
				throw_exception($error);
			}
		}else {
			$error = "Base Error: class $class_name isn't exists!";
			throw_exception($error);
		}
	}

	/**
	 * 开启session
	 *
	 */
	private static function start_session(){
		if (SUBDOMAIN_SUFFIX){
			$subdomain_suffix = SUBDOMAIN_SUFFIX;
		}else{
			if (preg_match("/^[0-9.]+$/",$_SERVER['HTTP_HOST'])){
				$subdomain_suffix = $_SERVER['HTTP_HOST'];
			}else{
				$split_url = explode('.',$_SERVER['HTTP_HOST']);
				if($split_url[2] != '') unset($split_url[0]);
				$subdomain_suffix = implode('.',$split_url);
			}
		}
		//session.name强制定制成PHPSESSID,不请允许更改
		@ini_set('session.name','PHPSESSID');
		$subdomain_suffix = str_replace('http://','',$subdomain_suffix);
		if ($subdomain_suffix !== 'localhost') {
		    @ini_set('session.cookie_domain', $subdomain_suffix);
		}

		//开启以下配置支持session信息存信memcache
		//@ini_set("session.save_handler", "memcache");
		//@ini_set("session.save_path", C('memcache.1.host').':'.C('memcache.1.port'));

		//默认以文件形式存储session信息
		session_save_path(BASE_DATA_PATH.'/session');
		session_start();
	}

	public static function autoload($class){
		$class = strtolower($class);
		if (ucwords(substr($class,-5)) == 'Class' ){
			if (!@include_once(BASE_PATH.'/library/bin/'.substr($class,0,-5).'.class.php')){
				exit("Class Error: {$class}.isn't exists!");
			}
		}elseif (ucwords(substr($class,0,5)) == 'Cache' && $class != 'cache'){
			if (!@include_once(BASE_DATA_PATH.'/library/cache/'.substr($class,0,5).'.'.substr($class,5).'.php')){
				exit("Class Error: {$class}.isn't exists!");
			}
		}elseif ($class == 'db'){
			if (!@include_once(BASE_DATA_PATH.'/library/bin/drive/'.strtolower(DBDRIVER).'.php')){
				exit("Class Error: {$class}.isn't exists!");
			}
		}else{
			if (!@include_once(BASE_DATA_PATH.'/library/bin/'.$class.'.php')){
				exit("Class Error: {$class}.isn't exists!");
			}
		}
	}

	/**
	 * 合法性验证
	 *
	 */
	private static function cp(){
		if (self::CPURL == '') return;
		if ($_SERVER['HTTP_HOST'] == 'localhost') return;
		if ($_SERVER['HTTP_HOST'] == '127.0.0.1') return;
		if (strpos(self::CPURL,'||') !== false){
			$a = explode('||',self::CPURL);
			foreach ($a as $v) {
				$d = strtolower(stristr($_SERVER['HTTP_HOST'],$v));
				if ($d == strtolower($v)){
					return;
				}else{
					continue;
				}
			}
			header('location: http://www.shopwt.com');exit();
		}else{
			$d = strtolower(stristr($_SERVER['HTTP_HOST'],self::CPURL));
			if ($d != strtolower(self::CPURL)){
				header('location: http://www.shopwt.com');exit();
			}
		}
	}
}