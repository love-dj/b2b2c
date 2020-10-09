<?php
/**
 * 缓存操作
 *

 
 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');

class Cache {

	protected $params;
	protected $enable;
	protected $handler;

	/**
	 * 实例化缓存驱动
	 *
	 * @param unknown_type $type
	 * @param unknown_type $args
	 * @return unknown
	 */
	public function connect($type,$args = array()){
		if (empty($type)) $type = C('cache_open') ? 'redis' : 'file';
      	$type = strtolower($type);
      	if($type !='cacheredis'){
        	$type = 'Cache'.strtolower($type);
        }
		$class = ucwords($type);
		if (!class_exists($class)){
			import('cache.cache#'.$type);
		}
		return new $class($args);
	}

	/**
	 * 取得实例
	 *
	 * @return object
	 */
	public static function getInstance(){
		$args = func_get_args();
		return get_obj_instance(__CLASS__,'connect',$args);
	}
}
