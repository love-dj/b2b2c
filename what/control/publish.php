<?php
/**
 * 买什么发布
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class publishControl extends MircroShopControl{

    public function __construct() {
        parent::__construct();
        self::check_login();
        Tpl::output('index_sign','');
    }

    public function indexWt() {
        $this->goods_buyWt();
    }

    public function goods_buyWt() {
        $model_order = Model('order');

        $condition = array();
        $condition['buyer_id'] = $_SESSION['member_id'];
        $goods_list = $model_order->getOrderGoodsList($condition, '*', null, 20);
        Tpl::output('list',$goods_list);
        Tpl::output('goods_type','buy');
        Tpl::output('show_page',$model_order->showpage());
        $this->get_commend_goods_list();
        //获得分享app列表
        self::get_share_app_list();
        Tpl::showpage('publish_goods');
    }

    public function goods_favoritesWt() {
        $model_favorites = Model('favorites');
        $condition = array();
        $condition['member_id'] = $_SESSION['member_id'];
        $favorites_list = $model_favorites->getGoodsFavoritesList($condition, '*', 20);
        $goods_list = array();
        if (!empty($favorites_list) && is_array($favorites_list)){
            $goods_id_string = '';
            foreach ($favorites_list as $key=>$value){
                $goods_id_string .= $value['fav_id'].',';
            }
            $goods_id_string = rtrim($goods_id_string,',');
            $model_goods = Model('goods');
            $goods_list = $model_goods->getGoodsList(array('goods_id'=>array('in', $goods_id_string)));
        }
        Tpl::output('list',$goods_list);
        Tpl::output('goods_type','favorites');
        Tpl::output('show_page',$model_favorites->showpage());
        $this->get_commend_goods_list();

        //获得分享app列表
        self::get_share_app_list();
        Tpl::showpage('publish_goods');
    }

    //获取已经推荐的列表
    private function get_commend_goods_list() {
        $model_what_goods = Model('what_goods');
        $commend_goods_list = $model_what_goods->getList(array('commend_member_id'=>$_SESSION['member_id']));
        $commend_goods_array = array();
        if(!empty($commend_goods_list)) {
            foreach ($commend_goods_list as $value) {
                $commend_goods_array[] = $value['commend_goods_id'];
            }
        }
        Tpl::output('commend_goods_array',$commend_goods_array);
    }

    public function goods_saveWt() {
        $model_goods = Model('goods');
        $model_what_goods = Model('what_goods');
        $goods_id = intval($_POST['commend_goods_id']);

        if(empty($goods_id)) {
            showDialog(Language::get('wrong_argument'),'','error','');
        }
        $goods_content = $model_goods->getGoodsInfoByID($goods_id);
        if (empty($goods_content)) {
            showDialog('商品不存在或已删除','reload','error','');
        }

        $model_goods_relation = Model('what_goods_relation');
        $goods_relation = $model_goods_relation->getOne(array('shop_class_id'=>$goods_content['gc_id']));

        $commend_goods_content = array();
        $commend_goods_content['commend_member_id'] = $_SESSION['member_id'];
        $commend_goods_content['commend_goods_id'] = $goods_content['goods_id'];
        $commend_goods_content['commend_goods_commonid'] = $goods_content['goods_commonid'];
        $commend_goods_content['commend_goods_store_id'] = $goods_content['store_id'];
        $commend_goods_content['commend_goods_name'] = $goods_content['goods_name'];
        $commend_goods_content['commend_goods_price'] = $goods_content['goods_price'];
        $commend_goods_content['commend_goods_image'] = $goods_content['goods_image'];
        if(empty($_POST['commend_message'])) {
            $commend_goods_content['commend_message'] = Language::get('what_goods_default_commend_message');
        } else {
            $commend_goods_content['commend_message'] = trim($_POST['commend_message']);
        }
        $commend_goods_content['commend_time'] = time();
        $commend_goods_content['what_commend'] = 0;
        $commend_goods_content['what_sort'] = 255;
        //没有建立分类绑定关系的，使用默认分类，没有设定默认分类的默认到第一个二级分类下
        if(empty($goods_relation)) {
            $model_goods_class = Model('what_goods_class');
            $default_class = $model_goods_class->getOne(array('class_default'=>1));
            if(!empty($default_class)) {
                //默认分类
                $commend_goods_content['class_id'] = $default_class['class_id'] ;
            } else {
                $condition = array();
                $condition['class_parent_id'] = array('gt',0);
                $goods_class = $model_goods_class->getOne($condition,'class_id asc');
                if(empty($goods_class)) {
                    showDialog(Language::get('what_goods_class_none'),'reload','error','');
                } else {
                    $commend_goods_content['class_id'] = $goods_class['class_id'] ;
                }
            }
        } else {
            $commend_goods_content['class_id'] = $goods_relation['class_id'];
        }
        $result = $model_what_goods->save($commend_goods_content);
        $message = Language::get('wt_common_save_fail');
            //分享内容
            if($result) {
                $message = Language::get('wt_common_save_succ');
                //计数
                $model_what_member_info = Model('what_member_info');
                $model_what_member_info->updateMemberGoodsCount($_SESSION['member_id'],'+');

                if(isset($_POST['share_app_items'])) {
                    $commend_goods_content['type'] = 'goods';
                    $commend_goods_content['url'] = WHAT_SITE_URL.DS."index.php?w=goods&t=detail&goods_id=".$result;
                    self::share_app_publish('publish',$commend_goods_content);
                }
            }
        showDialog($message,'reload',$result? 'succ' : 'error','');
    }

    /**
     * 买心得图片上传
     **/
    public function personal_image_uploadWt() {
        $data = array();
        $data['status'] = 'success';
        if(isset($_SESSION['member_id'])) {
            if(!empty($_FILES['personal_image_ajax']['name'])) {
                $upload = new UploadFile();
                $upload->set('default_dir',ATTACH_WHAT.DS.$_SESSION['member_id']);
                $upload->set('thumb_width','60,240');
                $upload->set('thumb_height', '5000,50000');
                $upload->set('thumb_ext',   '_tiny,_list');

                $result = $upload->upfile('personal_image_ajax');
                if(!$result) {
                    $data['status'] = 'fail';
                    $data['error'] = $upload->error;
                }
                $data['file'] = $upload->file_name;
            }
        } else {
            $data['status'] = 'fail';
            $data['error'] = Language::get('no_login');
        }
        self::echo_json($data);
    }

    /**
     * 买心得图片删除
     **/
    public function personal_image_deleteWt() {
        $data = array();
        $data['status'] = 'success';
        self::drop_personal_image($_GET['image_name']);
        self::echo_json($data);
    }

    /**
     * 买心得数量限制检查
     **/
    public function personal_limitWt() {
        $result = $this->check_personal_limit();
        if($result) {
            self::return_json('','true');
        } else {
            self::return_json(Language::get('what_personal_limit_error'),'false');
        }
    }

    //检查买心得数量限制
    private function check_personal_limit() {
        $personal_limit = C('what_personal_limit');
        if(empty($personal_limit)) {
            return TRUE;
        }
        $model = Model('what_member_info');
        $what_member_info = $model->getOneById($_SESSION['member_id']);
        if(empty($what_member_info)) {
            return TRUE;
        }
        if($what_member_info['personal_count'] < $personal_limit) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 买心得购买链接添加
     */
    public function personal_link_addWt() {
        $link = urldecode($_GET['link']);
        if(empty($link)) {
            self::return_json(Language::get('wrong_argument'),'false');
        }
        $model_goods_content = Model('goods_content_by_url');
        $result = $model_goods_content->get_goods_content_by_url($link);
        if($result) {
            self::echo_json($result);
        } else {
            self::return_json(Language::get('what_wrong_url'),'false');
        }
    }

    /**
     * 买心得保存
     **/
    public function personal_saveWt() {
        $personal_limit = $this->check_personal_limit();
        if(!$personal_limit) {
            self::drop_personal_image($_POST['personal_image']);
            showDialog(Language::get('what_personal_limit_error'),'','error','');
        }
        if(empty($_POST['personal_image'])) {
            showDialog(Language::get('wrong_argument'),'','error','');
        }
        $personal_info = array();
        $personal_info['class_id'] = intval($_POST['class_id']);
        if(empty($_POST['commend_message'])) {
            $personal_info['commend_message'] = Language::get('what_personal_default_commend_message');
        } else {
            $personal_info['commend_message'] = trim($_POST['commend_message']);
        }
        $personal_info['commend_member_id'] =  $_SESSION['member_id'];
        $personal_info['commend_image'] = trim($_POST['personal_image']);
        $personal_info['commend_time'] = time();
        $personal_info['class_id'] =  intval($_POST['personal_class']);
        $personal_link_array = array();
        if(!empty($_POST['personal_buy_link'])) {
            $model_goods_content = Model('goods_content_by_url');
            for ($i = 0,$count = count($_POST['personal_buy_link']); $i < $count; $i++) {
                $check_link = $model_goods_content->check_personal_buy_link($_POST['personal_buy_link'][$i]);
                if($check_link) {
                    $personal_link_array[$i]['link'] = $_POST['personal_buy_link'][$i];
                    $personal_link_array[$i]['image'] = $_POST['personal_buy_image'][$i];
                    $personal_link_array[$i]['price'] = $_POST['personal_buy_price'][$i];
                    $personal_link_array[$i]['title'] = $_POST['personal_buy_title'][$i];
                }
            }
        }
        $personal_info['commend_buy'] = serialize($personal_link_array);
        $personal_info['what_commend'] = 0;
        $personal_info['what_sort'] = 255;

        $model_personal = Model('what_personal');
        $result = $model_personal->save($personal_info);
        $message = Language::get('wt_common_save_fail');
        //分享内容
        if($result) {
            $message = Language::get('wt_common_save_succ');
            //计数
            $model_what_member_info = Model('what_member_info');
            $model_what_member_info->updateMemberPersonalCount($_SESSION['member_id'],'+');
            if(isset($_POST['share_app_items'])) {
                $personal_info['type'] = 'personal';
                $personal_info['url'] = WHAT_SITE_URL.DS."index.php?w=personal&t=detail&personal_id=".$result;
                self::share_app_publish('publish',$personal_info);
            }
        }
        showDialog($message,WHAT_SITE_URL.DS.'index.php?w=home&t=personal',$result? 'succ' : 'error','');
    }



    public function albumWt() {
        Tpl::showpage('publish_album');
    }
}
