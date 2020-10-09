<?php
/**
 * 买什么
 *
 *
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class goodsControl extends SystemControl{

    const what_CLASS_LIST = 'index.php?w=goods_class&t=goodsclass_list';
    const GOODS_FLAG = 1;
    const PERSONAL_FLAG = 2;
    const ALBUM_FLAG = 3;
    const STORE_FLAG = 4;

    public function __construct(){
        parent::__construct();
        Language::read('store');
        Language::read('what');
    }

    public function indexWt() {
       $this->goods_manageWt();
    }

    /**
     * 说说看管理
     */
    public function goods_manageWt()
    {
        Tpl::setDirquna('what');
Tpl::showpage('what_goods.manage');
    }

    /**
     * 说说看管理XML
     */
    public function goods_manage_xmlWt()
    {
        $condition = array();

        if ($_REQUEST['showanced']) {
            if (strlen($q = trim((string) $_REQUEST['commend_id']))) {
                $condition['commend_id'] = (int) $q;
            }
            if (strlen($q = trim((string) $_REQUEST['member_name']))) {
                $condition['member_name'] = $q;
            }
            if (strlen($q = trim((string) $_REQUEST['commend_goods_name']))) {
                $condition['commend_goods_name'] = array('like', '%' . $q . '%');
            }
            if (strlen($q = trim((string) $_REQUEST['what_commend']))) {
                $condition['what_commend'] = (int) $q;
            }

            $sdate = (int) strtotime($_GET['sdate']);
            $edate = (int) strtotime($_GET['edate']);
            if ($sdate > 0 || $edate > 0) {
                $condition['commend_time'] = array(
                    'time',
                    array($sdate, $edate, ),
                );
            }

        } else {
            if (strlen($q = trim($_REQUEST['query'])) > 0) {
                switch ($_REQUEST['qtype']) {
                    case 'commend_id':
                        $condition['commend_id'] = (int) $q;
                        break;
                    case 'member_name':
                        $condition['member_name'] = $q;
                        break;
                    case 'commend_goods_name':
                        $condition['commend_goods_name'] = array('like', '%' . $q . '%');
                        break;
                }
            }
        }

        $model_what_goods = Model('what_goods');
        $field = 'what_goods.*,member.member_name,member.member_avatar';
        $list = (array) $model_what_goods->getListWithUserInfo($condition, $_REQUEST['rp'],
            'commend_time desc', $field);

        $data = array();
        $data['now_page'] = $model_what_goods->shownowpage();
        $data['total_num'] = $model_what_goods->gettotalnum();

        foreach ($list as $val) {
            $o = '<a class="btn red confirm-del-on-click" href="index.php?w=goods&t=goods_drop&commend_id=' .
                $val['commend_id'] .
                '"><i class="fa fa-trash-o"></i>删除</a>';

            $o .= '<span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em><ul>';

            if ($val['what_commend'] == '1') {
                $o .= '<li><a href="javascript:;" data-ie-column="what_commend" data-ie-value="0">取消推荐</a></li>';
            } else {
                $o .= '<li><a href="javascript:;" data-ie-column="what_commend" data-ie-value="1">推荐商品</a></li>';
            }

            $o .= '<li><a target="_blank" href="' .
                    WHAT_SITE_URL.DS.'index.php?w=goods&t=detail&goods_id=' .
                    $val['commend_id'] .
                    '">查看商品</a></li>';

            $o .= '</ul></span>';

            $i = array();
            $i['operation'] = $o;
            $i['commend_id'] = $val['commend_id'];

            $i['what_sort'] = '<span class="editable" title="可编辑" style="width:50px;" data-live-inline-edit="what_sort">' .
                $val['what_sort'] . '</span>';

            $i['commend_state'] = $val['what_commend'] == 1
                ? '<span class="yes"><i class="fa fa-check-bbs"></i>是</span>'
                : '<span class="no"><i class="fa fa-ban"></i>否</span>';

            $i['member_name'] = '<a target="_blank" href="' .
                WHAT_SITE_URL.DS.'index.php?w=home&member_id='.$val['commend_member_id'] .
                '">' .
                $val['member_name'] .
                '</a>';

            $img = cthumb($val['commend_goods_image'], 240, $val['commend_goods_store_id']);
            $i['commend_goods_image'] = <<<EOB
<a href="javascript:;" class="pic-thumb-tip" onMouseOut="toolTip()" onMouseOver="toolTip('<img src=\'{$img}\'>')">
<i class='fa fa-picture-o'></i></a>
EOB;

            $i['commend_goods_name'] = $val['commend_goods_name'];

            $i['commend_message'] = $val['commend_message'];
            $i['commend_time_text'] = date('Y-m-d', $val['commend_time']);

            $data['list'][$val['commend_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 说说看删除
     */
    public function goods_dropWt()
    {
        $model = Model('what_goods');
        $condition = array();
        $condition['commend_id'] = array('in',trim($_REQUEST['commend_id']));

        //删除说说看图片
        $list = $model->getList($condition);
        if(!empty($list)) {
            foreach ($list as $info) {
                //计数
                $model_what_member_info = Model('what_member_info');
                $model_what_member_info->updateMemberGoodsCount($info['commend_member_id'],'-');
            }
        }
        $result = $model->drop($condition);
        if($result) {
            showMessage(Language::get('wt_common_del_succ'),'');
        } else {
            showMessage(Language::get('wt_common_del_fail'),'','','error');
        }
    }

    /**
     * 更新买什么说说看排序
     */
    public function goods_sort_updateWt() {
        if(intval($_GET['id']) <= 0) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        }
        $new_sort = intval($_GET['value']);
        if ($new_sort > 255){
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('what_sort_error')));
            die;
        } else {
            $model_class = Model('what_goods');
            $result = $model_class->modify(array('what_sort'=>$new_sort),array('commend_id'=>$_GET['id']));
            if($result) {
                echo json_encode(array('result'=>TRUE,'message'=>'wt_common_op_succ'));
                die;
            } else {
                echo json_encode(array('result'=>FALSE,'message'=>Language::get('wt_common_op_fail')));
                die;
            }
        }
    }
    /**
     * ajax操作
     */
    public function ajaxWt(){
        //说说看推荐
        if($_GET['branch'] == 'goods_commend') {
            if(intval($_GET['id']) > 0) {
                $model= Model('what_goods');
                $condition['commend_id'] = intval($_GET['id']);
                $update[$_GET['column']] = trim($_GET['value']);
                $model->modify($update,$condition);
                echo 'true';die;
            } else {
                echo 'false';die;
            }
        }
    }
}
