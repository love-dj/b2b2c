<?php
/**
 * 文章 news


 

 */


defined('ShopWT') or exit('Access Denied By ShopWT');
class news_articleControl extends mobileHomeControl{

	public function __construct() {
        parent::__construct();
    }
	
	public function indexWt() {
        $this->article_listWt();
    }

    /**
     * news文章列表
     */
    public function article_listWt() {	
		 $condition = array();
		 $condition['article_state']=3;
        if(!empty($_GET['class_id'])) {
            $condition['article_class_id'] = intval($_GET['class_id']);
        }
		if(!empty($_GET['keyword'])){
			$condition['article_title'] =  array('like', '%' . $_GET['keyword'] . '%');
		}
        $model_article = Model('news_article');
        $article_list = $model_article->getListWithClassName($condition, 10, 'article_sort asc, article_id desc');
		$page_count = $model_article->gettotalpage();
        
		
	 	if(!empty($article_list) && is_array($article_list))
		{
			foreach($article_list as $k=>$value)
			{
				$value['img']='';
				if(!empty($value['article_image'])){
					$value['img']= getNEWSArticleImageUrl($value['article_attachment_path'], $value['article_image'], 'list');
				}
				$value['time'] = date('Y-m-d',$value['article_publish_time']);
				$article_list[$k]=$value;
			}
		} 
       
		output_data(array('article_list' => $article_list), mobile_page($page_count));
    }
    /**
     * news类型
     */
	public function class_listWt(){
		$nowid=0;
		if(isset($_GET['class_id']))$nowid=intval($_GET['class_id']);
		$news_seo = array();
		$news_seo['news_seo_title'] = C('news_seo_title');
        $news_seo['news_seo_keywords'] = C('news_seo_keywords');
        $news_seo['news_seo_description'] = C('news_seo_description');
		 $art_class=Model('news_article_class')->getList(array());       
if($nowid>0){
			$art_info=Model('news_article_class')->getOne(array('class_id'=>$nowid));
			if(!empty($art_info)) $news_seo['news_seo_title'] = $art_info['class_name'].'-'.C('news_seo_title');
		}
		output_data(array('article_class_list'=>$art_class,'nowid'=>$nowid,'news_seo' => $news_seo));
	}
	 /**
     * news文章详情
     */
	public function news_showWt(){
$news_seo = array();
		$news_seo['news_seo_title'] = C('news_seo_title');
        $news_seo['news_seo_keywords'] = C('news_seo_keywords');
        $news_seo['news_seo_description'] = C('news_seo_description');
		$article_id = intval($_GET['article_id']);
        if($article_id <= 0) {
            output_error('文章不存在');
        }
		$model_article = Model('news_article');
		$article_detail = $model_article->getOne(array('article_id'=>$article_id));
		$article_detail['article_time'] = date('Y-m-d',$article_detail['article_publish_time']);
		if(empty($article_detail)) {
            output_error('文章不存在');
        }
		//计数加1
        $model_article->modify(array('article_click'=>array('exp','article_click+1')),array('article_id'=>$article_id));
		$article_detail['news_seo'] = $news_seo;
		output_data($article_detail);
	}
	/**
     * 评论列表
     **/
    public function comment_listWt() {
        $page_count = 10;
        $order = 'comment_id desc';
        $comment_object_id = intval($_GET['comment_object_id']);
        $comment_type = 0;
        switch ($_GET['type']) {
        case 'article':
            $comment_type = 1;
            break;
        case 'picture':
            $comment_type = 2;
            break;
        }

        if($comment_object_id > 0 && $comment_type > 0) {
            $condition = array();
            $condition["comment_object_id"] = $comment_object_id;
            $condition["comment_type"] = $comment_type;
            $model_news_comment = Model('news_comment');
            $comment_list = $model_news_comment->getListWithUserInfo($condition, $page_count, $order);
			$all_count = $model_news_comment->gettotalpage();
			if(!empty($comment_list) && is_array($comment_list))
			{
				foreach($comment_list as $k=>$val)
				{
					$val['member_avatar'] = getMemberAvatar($val['member_avatar']);
					$comment_list[$k] = $val;
				}
			}
			output_data(array('comment_list'=>$comment_list), mobile_page($all_count));
        }
    }
	
	/**
     * 文章点赞顶
     **/
    public function article_upWt() {

        $data = array();
        $data['result'] = 'true';

        $article_id = intval($_POST['article_id']);
		
        if($article_id > 0) {
			$memberId=$this->getMemberIdIfExists();
			$model_attitude = Model('news_article_attitude');
            $param = array();
            $param['attitude_article_id'] = $article_id;
            $param['attitude_member_id'] = $memberId;
            $exist = $model_attitude->isExist($param);
            if(!$exist) {
                $param['attitude_time'] = time();
                $result = $model_attitude->save($param);
                if($result) {

                    //点赞计数加1
                   $model_comment = Model('news_article');
					$model_comment-> modify(array('article_attitude_5'=>array('exp', 'article_attitude_5+1')), array('article_id'=>$article_id));
					$data['status'] = '1';
					$data['message'] = '谢谢您点赞~';                   

                } else {
                    $data['result'] = 'false';
                    $data['message'] = '点赞失败';
                }
            } else {
                $data['result'] = 'false';
                $data['message'] = '您已经点赞过了哦~';
            }
           
        } else {
            $data['result'] = 'false';
            $data['message'] = '点赞失败';
        }
		output_data($data);
    }
	
	/**
     * 评论顶
     **/
    public function comment_upWt() {

        $data = array();
        $data['result'] = 'true';

        $comment_id = intval($_POST['comment_id']);
        if($comment_id > 0) {
			$memberId=$this->getMemberIdIfExists();
            $model_comment_up = Model('news_comment_up');
            $param = array();
            $param['comment_id'] = $comment_id;
            $param['up_member_id'] = $memberId;
            $is_exist = $model_comment_up->isExist($param);
            if(!$is_exist) {
                $param['up_time'] = time();
                $model_comment_up->save($param);

                $model_comment = Model('news_comment');
                $model_comment->modify(array('comment_up'=>array('exp', 'comment_up+1')), array('comment_id'=>$comment_id));
				$data['status'] = '1';
                $data['message'] = '谢谢您！';
            } else {
                $data['result'] = 'false';
                $data['message'] = '顶过了';
            }
        } else {
            $data['result'] = 'false';
            $data['message'] = Language::get('wrong_argument');
        }
		output_data($data);
    }

    /**
     * 评论保存
     **/
    public function comment_saveWt() {

        $data = array();
        $data['result'] = 'true';
        $comment_object_id = intval($_POST['comment_object_id']);
        $comment_type = $_POST['comment_type'];
        $model_name = '';
        $count_field = '';
        switch ($comment_type) {
        case 'article':
            $comment_type = 1;
            $model_name = 'news_article';
            $count_field = 'article_comment_count';
            $comment_object_key = 'article_id';
            break;
        case 'picture':
            $comment_type = 2;
            $model_name = 'news_picture';
            $count_field = 'picture_comment_count';
            $comment_object_key = 'picture_id';
            break;
        default:
            $comment_type = 0;
            break;
        }
		 

        if($comment_object_id <= 0 || empty($comment_type) || empty($_POST['comment_message'])) {
            $data['result'] = 'false';
            $data['message'] = Language::get('wrong_argument');
			output_data($data);
        }
		
		$memberId=$this->getMemberIdIfExists();

        if($memberId>0) {

            $param = array();
            $param['comment_type'] = $comment_type;
            $param["comment_object_id"] = $comment_object_id;
            if (strtoupper(CHARSET) == 'GBK'){
                $param['comment_message'] = Language::getGBK(trim($_POST['comment_message']));
            } else {
                $param['comment_message'] = trim($_POST['comment_message']);
            }
            $param['comment_member_id'] = $memberId;
            $param['comment_time'] = time();

            $model_comment = Model('news_comment');

            if(!empty($_POST['comment_id'])) {
                $comment_detail = $model_comment->getOne(array('comment_id'=>$_POST['comment_id']));
                if(empty($comment_detail['comment_quote'])) {
                    $param['comment_quote'] = $_POST['comment_id'];
                } else {
                    $param['comment_quote'] = $comment_detail['comment_quote'].','.$_POST['comment_id'];
                }
            } else {
                $param['comment_quote'] = '';
            }

            $result = $model_comment->save($param);
            if($result) {

                //评论计数加1
                $model = Model($model_name);
                $update = array();
                $update[$count_field] = array('exp',$count_field.'+1');
                $condition = array();
                $condition[$comment_object_key] = $comment_object_id;
                $model->modify($update, $condition);
				$data['status'] = '1';
				$data['message'] = '发表成功！';

            } else {
                $data['result'] = 'false';
                $data['message'] = '发表失败！';
            }
        } else {
            $data['result'] = 'false';
            $data['message'] = '请登录！';
        }
		output_data($data);
    }
	

  
}
