<?php
/**
 * 前台control父类
 *

 
 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');

/********************************** 前台control父类 **********************************************/

class BaseControl {
	public function __construct(){
		Language::read('common');
	}
}
