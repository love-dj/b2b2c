<?php
/**
 * 文章 

 


 * 
 **/

defined('ShopWT') or exit('Access Denied By ShopWT');
class article_classControl extends mobileHomeControl{

	public function __construct() {
        parent::__construct();
    }
    
    public function indexWt() {
			$article_class_model	= Model('article_class');
			$article_model	= Model('article');
			$condition	= array();
			
			$article_class = $article_class_model->getClassList($condition);
			output_data(array('article_class' => $article_class));		
    }
}
