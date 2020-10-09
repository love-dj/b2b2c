<?php
/**
 * news用户中心图刊
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class member_pictureControl extends NEWSMemberControl{

    public function __construct() {
        parent::__construct();
    }

    public function indexWt() {
        $this->picture_listWt();
    }

    /**
     * 图刊列表
     */
    public function picture_listWt() {
        $condition = array();
        if(!empty($_GET['picture_state'])) {
            $condition['picture_state'] = intval($_GET['picture_state']);
        } else {
            $condition['picture_state'] = array('in',array(self::ARTICLE_STATE_PUBLISHED, self::ARTICLE_STATE_VERIFY)) ;
        }
        $this->get_picture_list($condition);
    }

    /**
     * 草稿列表
     */
    public function draft_listWt() {
        $condition = array();
        $condition['picture_state'] = self::ARTICLE_STATE_DRAFT;
        $this->get_picture_list($condition);
    }

    /**
     * 草稿列表
     */
    public function recycle_listWt() {
        $condition = array();
        $condition['picture_state'] = self::ARTICLE_STATE_RECYCLE;
        $this->get_picture_list($condition);
    }

    /**
     * 获得图刊列表
     */
    private function get_picture_list($condition = array()) {
        if(!empty($_GET['keyword'])) {
            $condition['picture_title'] = array('like', '%'.$_GET['keyword'].'%');
        }
        $condition['picture_type'] = $this->publisher_type;
        $condition['picture_publisher_id'] = $this->publisher_id;
        $model_picture = Model('news_picture');
        $picture_list = $model_picture->getList($condition, 20, 'picture_id desc');
        Tpl::output('show_page',$model_picture->showpage(2));
        Tpl::output('picture_list', $picture_list);

        //获取图刊图片
        $picture_ids = '';
        if(!empty($picture_list)) {
            foreach ($picture_list as $value) {
                $picture_ids .= $value['picture_id'].',';
            }
            $picture_ids = rtrim($picture_ids, ',');
        }
        $model_picture_image = Model('news_picture_image');
        $picture_image_array = $model_picture_image->getList(array('image_picture_id'=>array('in', $picture_ids)));
        $picture_image_list = array();
        if(!empty($picture_image_array)) {
            foreach($picture_image_array as $value) {
                $image = array('name'=>$value['image_name'], 'path'=>$value['image_path']);
                $picture_image_list[$value['image_picture_id']][] = serialize($image);
            }
        }
        Tpl::output('picture_image_list', $picture_image_list);

        Tpl::output('picture_state_list', $this->get_article_state_list());
        Tpl::output('index_sign', 'picture');
        Tpl::showpage('member_picture_list', 'news_member_layout');
    }

    /**
     * 图刊编辑
     */
    public function picture_editWt() {
        $picture_id = intval($_GET['picture_id']);
        $picture_detail = $this->check_picture_auth($picture_id);
        if($picture_detail) {
            $model_picture_class = Model('news_picture_class');
            $picture_class_list = $model_picture_class->getList(TRUE, NULL, 'class_sort asc');
            Tpl::output('picture_class_list', $picture_class_list);

            $model_tag = Model('news_tag');
            $tag_list = $model_tag->getList(TRUE, NULL, 'tag_sort asc');
            Tpl::output('tag_list', $tag_list);

            $model_picture_image = Model('news_picture_image');
            $picture_image_list = $model_picture_image->getList(array('image_picture_id'=>$picture_id), NULL);
            Tpl::output('picture_image_list', $picture_image_list);

            Tpl::output('picture_detail', $picture_detail);

            Tpl::showpage('publish_picture','news_member_layout');
        } else {
            showMessage(Language::get('wrong_argument'),'','','error');
        }
    }

    /**
     * 发布
     */
    public function picture_publishWt() {
        $this->picture_state_change($this->publish_state);
    }

    /**
     * 移到回收站
     */
    public function picture_recycleWt() {
        $this->picture_state_change(self::ARTICLE_STATE_RECYCLE);
    }

    /**
     * 移到草稿箱
     */
    public function picture_draftWt() {
        $this->picture_state_change(self::ARTICLE_STATE_DRAFT);
    }

    /**
     * 删除
     */
    public function picture_dropWt() {
        $picture_id = intval($_GET['picture_id']);
        $picture_auth = $this->check_picture_auth($picture_id);
        if($picture_auth) {
            $model_picture = Model('news_picture');
            $result = $model_picture->drop(array('picture_id'=>$picture_id));
            if($result) {
                showMessage(Language::get('wt_common_del_succ'),'');
            } else {
                showMessage(Language::get('wt_common_del_fail'),'','','error');
            }
        } else {
            showMessage(Language::get('wrong_argument'),'','','error');
        }
    }

    /**
     * 改变图刊状态
     */
    private function picture_state_change($picture_state_new) {
        $picture_id = intval($_GET['picture_id']);
    $picture_auth = $this->check_picture_auth($picture_id);
    if($picture_auth) {
        $model_picture = Model('news_picture');
        $result = $model_picture->modify(array('picture_state'=>$picture_state_new),array('picture_id'=>$picture_id));
        showMessage(Language::get('wt_common_op_succ'),'');
    } else {
        showMessage(Language::get('wt_common_op_fail'),'','','error');
    }
}
}
