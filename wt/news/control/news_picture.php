<?php
/**
 * news文章分类
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class news_pictureControl extends SystemControl{
    //文章状态草稿箱
    const ARTICLE_STATE_DRAFT = 1;
    //文章状态待审核
    const ARTICLE_STATE_VERIFY = 2;
    //文章状态已发布
    const ARTICLE_STATE_PUBLISHED = 3;
    //文章状态回收站
    const ARTICLE_STATE_RECYCLE = 4;

    public function __construct(){
        parent::__construct();
        Language::read('news');
    }

    public function indexWt() {
        $this->news_picture_listWt();
    }

    /**
     * news文章列表
     **/
    public function news_picture_listWt()
    {
        $this->doAction(0, 'list');
    }

    /**
     * 待审核文章列表
     */
    public function news_picture_list_verifyWt()
    {
        $this->doAction(self::ARTICLE_STATE_VERIFY, 'list_verify');
    }

    /**
     * 已发布文章列表
     */
    public function news_picture_list_publishedWt()
    {
        $this->doAction(self::ARTICLE_STATE_PUBLISHED, 'list_published');
    }

    protected function doAction($state, $menuKey)
    {
        $this->show_menu($menuKey);
        Tpl::output('currentState', $state);

        $states = $this->get_picture_state_list();
        Tpl::output('states', $states);

        Tpl::setDirquna('news');
Tpl::showpage("news_picture.list");
    }

    public function news_picture_list_xmlWt()
    {
        $condition = array();

        if ($_REQUEST['showanced']) {
            if (strlen($q = trim((string) $_REQUEST['picture_title']))) {
                $condition['picture_title'] = array('like', '%' . $q . '%');
            }
            if (strlen($q = trim((string) $_REQUEST['picture_publisher_name']))) {
                $condition['picture_publisher_name'] = $q;
            }

            if (strlen($q = trim((string) $_REQUEST['picture_commend_flag']))) {
                $condition['picture_commend_flag'] = (int) $q;
            }
            if (strlen($q = trim((string) $_REQUEST['picture_comment_flag']))) {
                $condition['picture_comment_flag'] = (int) $q;
            }
            if (strlen($q = trim((string) $_REQUEST['picture_state']))) {
                $condition['picture_state'] = (int) $q;
            }
        } else {
            if (strlen($q = trim($_REQUEST['query'])) > 0) {
                switch ($_REQUEST['qtype']) {
                    case 'picture_title':
                        $condition['picture_title'] = array('like', '%' . $q . '%');
                        break;
                    case 'picture_publisher_name':
                        $condition['picture_publisher_name'] = $q;
                        break;
                }
            }
        }

        if ($_GET['picture_state']) {
            $condition['picture_state'] = (int) $_GET['picture_state'];
        }

        $model_picture = Model('news_picture');
        $list = (array) $model_picture->getList($condition, $_REQUEST['rp'], 'picture_id desc');

        $data = array();
        $data['now_page'] = $model_picture->shownowpage();
        $data['total_num'] = $model_picture->gettotalnum();

        $states = $this->get_picture_state_list();

        foreach ($list as $val) {
            $o = '<a class="btn red" href="javascript:;" data-j="drop"><i class="fa fa-trash-o"></i>删除</a>';

            $o .= '<span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em><ul>';

            if ($val['picture_state'] == self::ARTICLE_STATE_VERIFY) {
                $o .= '<li><a href="javascript:;" data-j="audit">审核图刊</a></li>';
            }
            if ($val['picture_state'] == self::ARTICLE_STATE_PUBLISHED) {
                $o .= '<li><a href="javascript:;" data-j="callback">收回图刊</a></li>';
            }

            $o .= '<li><a target="_blank" href="' .
                    NEWS_SITE_URL .
                    '/index.php?w=picture&t=picture_detail&picture_id=' .
                    $val['picture_id'] .
                    '">查看图刊</a></li>';

            if ($val['picture_commend_flag'] == 1) {
                $o .= '<li><a href="javascript:;" data-j="picture_commend_flag" data-val="0">取消推荐</a></li>';
            } else {
                $o .= '<li><a href="javascript:;" data-j="picture_commend_flag" data-val="1">推荐图刊</a></li>';
            }
            if ($val['picture_comment_flag'] == 1) {
                $o .= '<li><a href="javascript:;" data-j="picture_comment_flag" data-val="0">关闭评论</a></li>';
            } else {
                $o .= '<li><a href="javascript:;" data-j="picture_comment_flag" data-val="1">开启评论</a></li>';
            }

            $o .= '</ul></span>';

            $i = array();
            $i['operation'] = $o;

            $i['picture_sort'] = '<span class="editable" title="可编辑" style="width:50px;" data-live-inline-edit="picture_sort">' .
                $val['picture_sort'] . '</span>';

            $i['picture_title'] = $val['picture_title'];

            $img = getNEWSArticleImageUrl($val['picture_attachment_path'], $val['picture_image']);
            $i['img'] = <<<EOB
<a href="javascript:;" class="pic-thumb-tip" onMouseOut="toolTip()" onMouseOver="toolTip('<img src=\'{$img}\'>')">
<i class='fa fa-picture-o'></i></a>
EOB;

            $i['picture_publisher_name'] = $val['picture_publisher_name'];

            $i['picture_click'] = '<span class="editable" title="可编辑" style="width:50px;" data-live-inline-edit="picture_click">' .
                $val['picture_click'] . '</span>';

            $i['picture_commend_flag'] = $val['picture_commend_flag'] == 1
                ? '<span class="yes"><i class="fa fa-check-bbs"></i>是</span>'
                : '<span class="no"><i class="fa fa-ban"></i>否</span>';

            $i['picture_comment_flag'] = $val['picture_comment_flag'] == 1
                ? '<span class="yes"><i class="fa fa-check-bbs"></i>开启</span>'
                : '<span class="no"><i class="fa fa-ban"></i>关闭</span>';

            $i['picture_state'] = $states[$val['picture_state']]['text'];

            $data['list'][$val['picture_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * news文章审核
     */
    public function news_picture_verifyWt() {
        if(intval($_REQUEST['verify_state']) === 1) {
            $this->news_picture_state_modify(self::ARTICLE_STATE_PUBLISHED);
        } else {
            $this->news_picture_state_modify(self::ARTICLE_STATE_DRAFT, array('picture_verify_reason' => $_POST['verify_reason']));
        }
    }

    /**
     * news文章回收
     */
    public function news_picture_callbackWt() {
        $this->news_picture_state_modify(self::ARTICLE_STATE_DRAFT);
    }

    /**
     * 修改文章状态
     */
    private function news_picture_state_modify($new_state, $param = array()) {
        $picture_id = $_REQUEST['picture_id'];
        $model_picture = Model('news_picture');
        $param['picture_state'] = $new_state;
        $model_picture->modify($param,array('picture_id'=>array('in',$picture_id)));
        showMessage(Language::get('wt_common_op_succ'),'');
    }

    /**
     * news文章分类排序修改
     */
    public function update_picture_sortWt() {
        if(intval($_GET['id']) <= 0) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        }
        $new_sort = intval($_GET['value']);
        if ($new_sort > 255){
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('picture_sort_error')));
            die;
        } else {
            $model_class = Model("news_picture");
            $result = $model_class->modify(array('picture_sort'=>$new_sort),array('picture_id'=>$_GET['id']));
            if($result) {
                echo json_encode(array('result'=>TRUE,'message'=>'class_add_success'));
                die;
            } else {
                echo json_encode(array('result'=>FALSE,'message'=>Language::get('class_add_fail')));
                die;
            }
        }
    }

    /**
     * news文章分类排序修改
     */
    public function update_picture_clickWt() {
        if(intval($_GET['id']) <= 0 || intval($_GET['value']) < 0) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));die;
        }
        $model_class = Model("news_picture");
        $result = $model_class->modify(array('picture_click'=>$_GET['value']),array('picture_id'=>$_GET['id']));
        if($result) {
            echo json_encode(array('result'=>TRUE,'message'=>''));die;
        } else {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));die;
        }
    }


    /**
     * news文章删除
     **/
     public function news_picture_dropWt() {
        $picture_id = trim($_REQUEST['picture_id']);
        $model_picture = Model('news_picture');
        $condition = array();
        $condition['picture_id'] = array('in',$picture_id);
        $result = $model_picture->drop($condition);
        if($result) {
            $this->log(Language::get('news_log_picture_drop').$picture_id, 1);
            showMessage(Language::get('wt_common_del_succ'),'');
        } else {
            $this->log(Language::get('news_log_picture_drop').$picture_id, 0);
            showMessage(Language::get('wt_common_del_fail'),'','','error');
        }
     }

    /**
     * ajax操作
     */
    public function ajaxWt()
    {
        if (intval($_GET['id']) < 1) {
            exit('false');
        }

        switch ($_GET['column']) {
            case 'picture_commend_flag':
            case 'picture_comment_flag':
                break;
            default:
                exit('false');
        }

        $model= Model('news_picture');
        $update[$_GET['column']] = trim($_GET['value']);
        $condition['picture_id'] = intval($_GET['id']);
        $model->modify($update,$condition);
        echo 'true';
        die;
    }


    /**
     * 获取文章状态列表
     */
    private function get_picture_state_list() {
        $array = array();
        $array[self::ARTICLE_STATE_DRAFT] = array('text'=>Language::get('news_text_draft'));
        $array[self::ARTICLE_STATE_VERIFY] = array('text'=>Language::get('news_text_verify'));
        $array[self::ARTICLE_STATE_PUBLISHED] = array('text'=>Language::get('news_text_published'));
        $array[self::ARTICLE_STATE_RECYCLE] = array('text'=>Language::get('news_text_recycle'));
        return $array;
    }

    private function show_menu($menu_key) {
        $menu_array = array(
            'list'=>array('menu_type'=>'link','menu_name'=>Language::get('wt_list'),'menu_url'=>'index.php?w=news_picture&t=news_picture_list'),
            'list_verify'=>array('menu_type'=>'link','menu_name'=>Language::get('news_article_list_verify'),'menu_url'=>'index.php?w=news_picture&t=news_picture_list_verify'),
            'list_published'=>array('menu_type'=>'link','menu_name'=>Language::get('news_article_list_published'),'menu_url'=>'index.php?w=news_picture&t=news_picture_list_published'),
        );
        $menu_array[$menu_key]['menu_type'] = 'text';
        Tpl::output('menu',$menu_array);
    }

}
