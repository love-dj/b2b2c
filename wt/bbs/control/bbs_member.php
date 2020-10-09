<?php
/**
 * 社区话题管理
 *
 *
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class bbs_memberControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('bbs');
    }

    public function indexWt() {
        $this->member_listWt();
    }
    /**
     * 成员列表
     */
    public function member_listWt(){
        $model = Model();
        if(chksubmit()){
            if (!empty($_POST['check_param']) && is_array($_POST['check_param'])){
                foreach ($_POST['check_param'] as $val){
                    $param = explode('|', $val);
                    list($member_id, $bbs_id) = $param;
                    $where['member_id'] = $member_id;
                    $where['bbs_id'] = $bbs_id;
                    Model()->table('bbs_member')->where($where)->delete();
                }
            }
            showMessage(L('wt_common_op_succ'));
        }
        $where = array();
        if($_GET['searchname'] != ''){
            $where['member_name'] = array('like', '%'.$_GET['searchname'].'%');
        }
        if($_GET['bbsname'] != ''){
            $where['bbs_name'] = array('like', '%'.$_GET['bbsname'].'%');
        }
        if($_GET['searchrecommend'] != '' && in_array(intval($_GET['searchrecommend']), array(0,1))){
            $where['is_recommend'] = intval($_GET['searchrecommend']);
        }
        if ($_GET['searchidentity'] != '' && in_array(intval($_GET['searchidentity']), array(1,2,3))) {
            $where['is_identity'] = intval($_GET['searchidentity']);
        }

        $order = array();
        if(intval($_GET['searchsort']) > 0){
            switch (intval($_GET['searchsort'])){
                case 1:
                    $order = 'cm_thcount desc';
                    break;
                case 2:
                    $order = 'cm_comcount desc';
                    break;
                default:
                    $order = 'cm_jointime desc';
                    break;
            }
        }
        $member_list = $model->table('bbs_member')->where($where)->page(10)->order($order)->select();
        Tpl::output('show_page', $model->showpage('2'));
        Tpl::output('member_list', $member_list);
        Tpl::setDirquna('bbs');
Tpl::showpage('bbs_member.list');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlWt() {
        $model = Model();
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('member_id', 'member_name', 'bbs_id', 'bbs_name', 'is_recommend', 'cm_jointime', 'is_identity', 'is_star', 'cm_thcount'
                , 'cm_comcount', 'cm_lastspeaktime'
        );
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $member_list = $model->table('bbs_member')->where($condition)->page($page)->order($order)->select();

        // 成员身份
        $identity_array = $this->getMemberIdentity();

        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
        foreach ($member_list as $value) {
            $param = array();
            $operation = "<a class='btn red' href='javascript:void(0);' onclick=\"fg_del('".$value['member_id']."|".$value['bbs_id']."')\"><i class='fa fa-list-alt'></i>删除</a>";
            $operation .= "<span class='btn'><em><i class='fa fa-cog'></i>" . L('wt_set') . " <i class='arrow'></i></em><ul>";
            $operation .= "<li><a href='".BASE_SITE_URL."/index.php?w=sns_bbs&mid=".$value['member_id']."' target=\"_blank\">查看成员信息</a></li>";
            if ($value['is_recommend'] == 1) {
                $operation .= "<li><a href='javascript:void(0);' onclick=\"fg_recommend('".$value['member_id']."|".$value['bbs_id']."', 0)\">取消成员推荐</a></li>";
            } else {
                $operation .= "<li><a href='javascript:void(0);' onclick=\"fg_recommend('".$value['member_id']."|".$value['bbs_id']."', 1)\">推荐优秀成员</a></li>";
            }
            $operation .= "</ul></span>";
            $param['operation'] = $operation;
            $param['member_id'] = $value['member_id'];
            $param['member_name'] = $value['member_name'];
            $param['bbs_id'] = $value['bbs_id'];
            $param['bbs_name'] = $value['bbs_name'];
            $param['is_recommend'] = $value['is_recommend'] == '1' ? '是' : '否';
            $param['cm_jointime'] = date('Y-m-d H:i:s', $value['cm_jointime']);
            $param['is_identity'] = $identity_array[$value['is_identity']];
            $param['is_star'] = $value['is_star'] == '1' ? '是' : '否';
            $param['cm_thcount'] = $value['cm_thcount'];
            $param['cm_comcount'] = $value['cm_comcount'];
            $param['cm_lastspeaktime'] = $value['cm_lastspeaktime'] != '' ? date('Y-m-d H:i:s', $value['cm_lastspeaktime']) : '--';
            $param['is_allowspeak'] = $value['is_allowspeak'] == '1' ? '允许' : '禁止';
            $data['list'][$value['member_id']."|".$value['bbs_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 成员身份
     * @return multitype:string
     */
    private function getMemberIdentity() {
        return array(
                '1' => '圈主',
                '2' => '管理',
                '3' => '成员'
        );
    }

    /**
     * 删除会员
     */
    public function member_delWt(){
        $param = explode(',', $_GET['id']);
        foreach ($param as $value) {
            $tpl_param = explode('|', $value);
            list($member_id, $bbs_id) = $tpl_param;
            $where['member_id'] = $member_id;
            $where['bbs_id'] = $bbs_id;
            Model()->table('bbs_member')->where($where)->delete();

            if ($_POST['all']) {
                Model()->table('bbs_theme')->where($where)->delete();
                Model()->table('bbs_threply')->where($where)->delete();
            }
        }
        exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
    }

    /**
     * 推荐/取消会员
     */
    public function member_recommendWt(){
        $array = explode('|', $_GET['id']);
        list($member_id, $bbs_id) = $array;
        $where = array();
        $where['member_id'] = $member_id;
        $where['bbs_id'] = $bbs_id;
        $update = array('is_recommend'=>($_GET['value'] == 1 ? 1 : 0));
        Model()->table('bbs_member')->where($where)->update($update);
        exit(json_encode(array('state'=>true,'msg'=>'操作成功')));
    }
}
