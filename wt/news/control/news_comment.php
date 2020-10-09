<?php
/**
 * news评论
 *
 *
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class news_commentControl extends SystemControl{


    public function __construct(){
        parent::__construct();
        Language::read('news');
    }

    public function indexWt() {
        $this->comment_manageWt();
    }


    /**
     * 评论管理
     */
    public function comment_manageWt()
    {
        $this->get_type_array();
        Tpl::setDirquna('news');
Tpl::showpage('news_comment.manage');
    }

    /**
     * 评论管理
     */
    public function comment_manage_xmlWt()
    {
        $condition = array();

        if ($_REQUEST['showanced']) {
            if (strlen($q = trim((string) $_REQUEST['comment_id']))) {
                $condition['comment_id'] = (int) $q;
            }
            if (strlen($q = trim((string) $_REQUEST['member_name']))) {
                $condition['member_name'] = $q;
            }
            if (strlen($q = trim((string) $_REQUEST['comment_type']))) {
                $condition['comment_type'] = (int) $q;
            }
            if (strlen($q = trim((string) $_REQUEST['comment_object_id']))) {
                $condition['comment_object_id'] = (int) $q;
            }
            if (strlen($q = trim((string) $_REQUEST['comment_message']))) {
                $condition['comment_message'] = array('like', '%' . $q . '%');
            }
        } else {
            if (strlen($q = trim($_REQUEST['query'])) > 0) {
                switch ($_REQUEST['qtype']) {
                    case 'comment_id':
                        $condition['comment_id'] = (int) $q;
                        break;
                    case 'member_name':
                        $condition['member_name'] = array('like', '%' . $q . '%');
                        break;
                    case 'comment_object_id':
                        $condition['comment_object_id'] = (int) $q;
                        break;
                    case 'comment_message':
                        $condition['comment_message'] = array('like', '%' . $q . '%');
                        break;
                }
            }
        }

        $model_comment = Model("news_comment");
        $list = (array) $model_comment->getListWithUserInfo($condition, $_REQUEST['rp'], 'comment_time desc');

        $data = array();
        $data['now_page'] = $model_comment->shownowpage();
        $data['total_num'] = $model_comment->gettotalnum();

        $channel_array = $this->get_type_array();

        foreach ($list as $val) {
            $channel = $channel_array[$val['comment_type']];
            $o = '<a class="btn red confirm-del-on-click" href="index.php?w=news_comment&t=comment_drop&comment_id=' .
                $val['comment_id'] .
                '"><i class="fa fa-trash"></i>删除</a>';
            $o .= '<a class="btn green" target="_blank" href="' .
                NEWS_SITE_URL.DS.'index.php?w=' .
                $channel['key'] .
                '&t=' .
                $channel['key'] .
                '_detail&' .
                $channel['key'] .
                '_id=' .
                $val['comment_object_id'] .
                '"><i class="fa fa-list-alt"></i>查看</a>';


            $i = array();
            $i['operation'] = $o;
            $i['comment_id'] = $val['comment_id'];

            $i['member_name'] = $val['member_name'];

            $i['comment_type'] = $channel['name'];
            $i['comment_object_id'] = $val['comment_object_id'];
            $i['comment_message'] = parsesmiles($val['comment_message']);

            $data['list'][$val['comment_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 获取类型数组
     */
    private function get_type_array() {
        $type_array = array();
        $type_array[1] = array('name'=>Language::get('news_text_artcile'),'key'=>'article');
        $type_array[2] = array('name'=>Language::get('news_text_picture'),'key'=>'picture');
        Tpl::output('type_array', $type_array);

        return $type_array;
    }


    /**
     * 评论删除
     */
    public function comment_dropWt() {
        $model = Model('news_comment');
        $condition = array();
        $condition['comment_id'] = array('in',trim($_REQUEST['comment_id']));
        $result = $model->drop($condition);
        if($result) {
            $this->log(Language::get('news_log_comment_drop').$_REQUEST['comment_id'], 1);
            showMessage(Language::get('wt_common_del_succ'),'');
        } else {
            $this->log(Language::get('news_log_comment_drop').$_REQUEST['comment_id'], 0);
            showMessage(Language::get('wt_common_del_fail'),'','','error');
        }
    }

}
