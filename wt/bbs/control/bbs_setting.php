<?php
/**
 * 社区分类管理
 *
 *
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class bbs_settingControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('bbs');
    }
    /**
     * 社区设置
     */
    public function indexWt(){
        $model_setting = Model('setting');
        if(chksubmit()){
            $update = array();
            $update['bbs_isuse']     = intval($_POST['c_isuse']);
            $update['bbs_name']      = $_POST['c_name'];
            $update['bbs_createsum'] = intval($_POST['c_createsum']);
            $update['bbs_joinsum']   = intval($_POST['c_joinsum']);
            $update['bbs_managesum'] = intval($_POST['c_managesum']);
            $update['bbs_iscreate']  = intval($_POST['c_iscreate']);
            $update['bbs_istalk']    = intval($_POST['c_istalk']);
            $update['bbs_wordfilter']    = $_POST['c_wordfilter'];
            if (!empty($_FILES['c_logo']['name'])){
                $upload = new UploadFile();
                $upload->set('default_dir',ATTACH_BBS);
                $result = $upload->upfile('c_logo');
                if ($result){
                    $update['bbs_logo'] = $upload->file_name;
                }else {
                    showMessage($upload->error,'','','error');
                }
            }
            $list_setting = $model_setting->getListSetting();
            $result = $model_setting->updateSetting($update);
            if($result){
                if($list_setting['bbs_logo'] != '' && isset($update['bbs_logo'])){
                    @unlink(BASE_UPLOAD_PATH.DS.ATTACH_BBS.DS.$list_setting['bbs_logo']);
                }
                if(intval($_POST['c_isuse']) == 1){
                    $this->log(L('wt_bbs_open'));
                }else{
                    $this->log(L('wt_bbs_close'));
                }
                showMessage(L('wt_common_op_succ'));
            }else{
                showMessage(L('wt_common_op_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
        Tpl::setDirquna('bbs');
Tpl::showpage('bbs_setting.index');
    }
    /**
     * SEO 设置
     */
    public function seoWt(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $update = array();
            $update['bbs_seotitle']  = $_POST['c_seotitle'];
            $update['bbs_seokeywords']   = $_POST['c_seokeywords'];
            $update['bbs_seodescription']= $_POST['c_seodescription'];
            $result = $model_setting->updateSetting($update);
            if($result){
                showMessage(L('wt_common_op_succ'));
            }else{
                showMessage(L('wt_common_op_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
        Tpl::setDirquna('bbs');
Tpl::showpage('bbs_setting.seo');
    }
    /**
     * SEC 设置
     */
    public function secWt(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $update = array();
            $update['bbs_intervaltime']      = intval($_POST['c_intervaltime']);
            $update['bbs_contentleast']      = intval($_POST['c_contentleast']);
            $result = $model_setting->updateSetting($update);
            if($result){
                showMessage(L('wt_common_op_succ'));
            }else{
                showMessage(L('wt_common_op_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
        Tpl::setDirquna('bbs');
Tpl::showpage('bbs_setting.sec');
    }
    /**
     * 成员等级
     */
    public function expWt(){
        $model_setting = Model('setting');
        if(chksubmit()){
            $update = array();
            $update['bbs_exprelease']    = intval($_POST['c_exprelease']);
            $update['bbs_expreply']      = intval($_POST['c_expreply']);
            $update['bbs_expreleasemax'] = intval($_POST['c_expreleasemax']);
            $update['bbs_expreplied']    = intval($_POST['c_expreplied']);
            $update['bbs_exprepliedmax'] = intval($_POST['c_exprepliedmax']);
            $result = $model_setting->updateSetting($update);
            if ($result){
                showMessage(L('wt_common_op_succ'));
            }else {
                showMessage(L('wt_common_op_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
        Tpl::setDirquna('bbs');
Tpl::showpage('bbs_setting.exp');
    }
    /**
     * 社区首页广告
     */
    public function show_manageWt(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $input = array();
            //上传图片
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_BBS);
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','1.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['show_pic1']['name'])){
                $result = $upload->upfile('show_pic1');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input[1]['pic'] = $upload->file_name;
                    $input[1]['url'] = $_POST['show_url1'];
                }
            }elseif ($_POST['old_show_pic1'] != ''){
                $input[1]['pic'] = $_POST['old_show_pic1'];
                $input[1]['url'] = $_POST['show_url1'];
            }

            $upload->set('default_dir',ATTACH_BBS);
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','2.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['show_pic2']['name'])){
                $result = $upload->upfile('show_pic2');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input[2]['pic'] = $upload->file_name;
                    $input[2]['url'] = $_POST['show_url2'];
                }
            }elseif ($_POST['old_show_pic2'] != ''){
                $input[2]['pic'] = $_POST['old_show_pic2'];
                $input[2]['url'] = $_POST['show_url2'];
            }

            $upload->set('default_dir',ATTACH_BBS);
            $upload->set('thumb_ext', '');
            $upload->set('file_name', '3.jpg');
            $upload->set('ifremove', false);
            if (!empty($_FILES['show_pic3']['name'])){
                $result = $upload->upfile('show_pic3');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input[3]['pic'] = $upload->file_name;
                    $input[3]['url'] = $_POST['show_url3'];
                }
            }elseif ($_POST['old_show_pic3'] != ''){
                $input[3]['pic'] = $_POST['old_show_pic3'];
                $input[3]['url'] = $_POST['show_url3'];
            }

            $upload->set('default_dir',ATTACH_BBS);
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','4.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['show_pic4']['name'])){
                $result = $upload->upfile('show_pic4');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input[4]['pic'] = $upload->file_name;
                    $input[4]['url'] = $_POST['show_url4'];
                }
            }elseif ($_POST['old_show_pic4'] != ''){
                $input[4]['pic'] = $_POST['old_show_pic4'];
                $input[4]['url'] = $_POST['show_url4'];
            }

            $update_array = array();
            if (count($input) > 0){
                $update_array['bbs_loginpic'] = serialize($input);
            }

            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                $this->log(L('wt_edit,loginSettings'),1);
                showMessage(L('wt_common_save_succ'));
            }else {
                $this->log(L('wt_edit,loginSettings'),0);
                showMessage(L('wt_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        if ($list_setting['bbs_loginpic'] != ''){
            $list = unserialize($list_setting['bbs_loginpic']);
        }
        Tpl::output('list', $list);
        Tpl::setDirquna('bbs');
Tpl::showpage('bbs_setting.show');
    }

    /**
     * 添加超级管理
     */
    public function superaddWt() {
        if (chksubmit()) {
            if (intval($_POST['member_id']) <= 0) {
                showMessage(L('wt_common_op_fail'));
            }
            $insert = array();
            $insert['member_id'] = intval($_POST['member_id']);
            $insert['member_name'] = $_POST['member_name'];
            $result = Model('bbs_member')->addSuper($insert);
            if ($result) {
                showMessage(L('wt_common_op_succ'), urlAdminbbs('bbs_setting', 'super_list'));
            } else {
                showMessage(L('wt_common_op_fail'));
            }
        }
        Tpl::setDirquna('bbs');
Tpl::showpage('bbs_setting.super_add');
    }

    /**
     * 超级管理列表
     */
    public function super_listWt() {
        $model_bbsmember = Model('bbs_member');
        if (chksubmit()) {
            $id_array = $_POST['del_id'];
            if (empty($id_array)) {
                showMessage(L('miss_argument'));
            }
            foreach ($id_array as $val) {
                if (!is_numeric($val)) {
                    showMessage(L('param_error'));
                }
            }
            $result = $model_bbsmember->delSuper(array('member_id' => array('in', $id_array)));
            if ($result) {
                showMessage(L('wt_common_del_succ'));
            } else {
                showMessage(L('wt_common_del_fail'));
            }
        }
        $cm_list = $model_bbsmember->getSuperList(array());
        Tpl::output('cm_list', $cm_list);
        Tpl::setDirquna('bbs');
Tpl::showpage('bbs_setting.super_list');
    }

    /**
     * 选择超管
     */
    public function choose_superWt(){
        Tpl::output('search_url', urlAdminbbs('bbs_setting', 'member_search'));
        Tpl::setDirquna('bbs');
Tpl::showpage('bbs.choose_master', 'null_layout');
    }

    /**
     * 删除超级管理员
     */
    public function del_superWt() {
        $member_id = intval($_GET['member_id']);
        if ($member_id < 0) {
            showMessage(L('param_error'));
        }

        $result = Model('bbs_member')->delSuper(array('member_id' => $member_id));
        if ($result) {
            showMessage(L('wt_common_del_succ'));
        } else {
            showMessage(L('wt_common_del_fail'));
        }
    }

    /**
     * 搜索会员
     */
    public function member_searchWt() {
        $cm_list = Model('bbs_member')->getSuperList(array(), 'member_id');

        $where = array();
        if (!empty($_GET['name'])) {
            $where['member_name'] = array('like', '%' . trim($_GET['name']) . '%');
        }
        if (!empty($cm_list)) {
            $cm_list = array_under_reset($cm_list, member_id);
            $memberid_array = array_keys($cm_list);
            $where['member_id'] = array('not in', $memberid_array);
        }
        $member_list = Model('member')->getMemberList($where, 'member_id,member_name');
        echo json_encode($member_list);die;
    }
}
