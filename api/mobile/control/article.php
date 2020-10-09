<?php
/**
 * 文章 

 


 * 
 **/

defined('ShopWT') or exit('Access Denied By ShopWT');
class articleControl extends mobileHomeControl{

	public function __construct() {
        parent::__construct();
    }

    /**
     * 文章列表
     */
    public function article_listWt() {
        if(!empty($_GET['ac_id']) && intval($_GET['ac_id']) > 0) {
			$article_class_model	= Model('article_class');
			$article_model	= Model('article');
			$condition	= array();
			if(!empty($_GET['keyword'])){
				$condition['like_title']	=  trim($_GET['keyword']);
			}
			$child_class_list = $article_class_model->getChildClass(intval($_GET['ac_id']));
			$ac_ids	= array();
			if(!empty($child_class_list) && is_array($child_class_list)){
				foreach ($child_class_list as $v){
					$ac_ids[]	= $v['ac_id'];
				}
			}
			$ac_ids	= implode(',',$ac_ids);
			$condition['ac_ids']	= $ac_ids;
			$condition['article_show']	= '1';
			$article_list = $article_model->getArticleList($condition);	
			if(empty($article_list)){
				$article_list= array();
			}			
			$article_type_name = $this->article_type_name($ac_ids);
			output_data(array('article_list' => $article_list, 'article_type_name'=> $article_type_name));		
		}else if(!empty($_GET['keyword'])){
			$article_model	= Model('article');
			$condition	= array();
			$condition['like_title']	=  trim($_GET['keyword']);
			$condition['article_show']	= '1';
			$article_list = $article_model->getArticleList($condition);	
			if(empty($article_list)){
				$article_list= array();
			}			
			output_data(array('article_list' => $article_list, 'article_type_name'=> $article_type_name));	
		}
		else {
			output_error('缺少参数:文章类别编号');
		}    	
    }

    /**
     * 根据类别编号获取文章类别信息
     */
    private function article_type_name() {
    	if(!empty($_GET['ac_id']) && intval($_GET['ac_id']) > 0) {
			$article_class_model = Model('article_class');
			$article_class = $article_class_model->getOneClass(intval($_GET['ac_id']));
			return ($article_class['ac_name']);
		}
		else {
			return ('缺少参数:文章类别编号');			
		}    	
    }
    
    /**
     * 单篇文章显示
     */
    public function article_showWt() {
		$article_model	= Model('article');

        if(!empty($_GET['article_id']) && intval($_GET['article_id']) > 0) {
			$article	= $article_model->getOneArticle(intval($_GET['article_id']));

			if (empty($article)) {
				output_error('文章不存在');
			}
			else {
				$class=Model('article_class')->getOneClass($article['ac_id']);
				$article['ac_name']=$class['ac_name'];
				output_data($article);
			}
        } 
        else {
			output_error('缺少参数:文章编号');
        }
    }
}
