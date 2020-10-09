<?php
/**
 * 社区首页
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class manage_mapplyControl extends BasebbsManageControl{
    public function __construct(){
        parent::__construct();
        Language::read('bbs');
        $this->bbsSEO();
    }
    /**
     * Apply to be a management
     */
    public function indexWt(){
        // bbs information
        $this->bbsInfo();
        // Membership information
        $this->bbsMemberInfo();
        // Members to join the bbs list
        $this->memberJoinbbs();

        $model = Model();
        $mapply_list = $model->table('bbs_mapply')->where(array('bbs_id'=>$this->c_id))->page(10)->order('mapply_id desc')->select();
        if(!empty($mapply_list)){
            $memberid_array = array();
            $mapply_array   = array();
            foreach ($mapply_list as $val){
                $memberid_array[]   = $val['member_id'];
                $mapply_array[$val['member_id']]    = $val;
            }
            $member_list = $model->table('bbs_member')->field('cm_level,cm_levelname,member_id,member_name')->where(array('bbs_id'=>$this->c_id, 'member_id'=>array('in', $memberid_array)))->select();
            $mapply_list = array();
            if (!empty($member_list)){
                foreach ($member_list as $val){
                    $mapply_list[$val['member_id']] = array_merge($val, $mapply_array[$val['member_id']]);
                }
            }
            Tpl::output('mapply_list', $mapply_list);
            Tpl::output('show_page', $model->showpage(2));
        }

        $this->sidebar_menu('managerapply');
        Tpl::showpage('group_manage_mapply');
    }
    /**
     * Management application approved
     */
    public function mapply_passWt(){
        // Verify the identity
        $rs = $this->checkIdentity('c');
        if(!empty($rs)){
            showDialog($rs);
        }

        $cmid_array = explode(',', $_GET['cm_id']);
        foreach ($cmid_array as $key=>$val){
            if(!is_numeric($val)) unset($cmid_array[$key]);
        }
        if(empty($cmid_array)){
            showDialog(L('wrong_argument'));
        }
        $model = Model();
        // Calculate number allows you to add administrator
        $manage_count = $model->table('bbs_member')->where(array('bbs_id'=>$this->c_id, 'is_identity'=>2))->count();
        $i = intval(C('bbs_managesum')) - intval($manage_count);
        $cmid_array = array_slice($cmid_array, 0, $i);

        // conditions
        $where = array();
        $where['member_id'] = array('in', $cmid_array);
        $where['bbs_id'] = $this->c_id;

        // Update the data
        $update = array();
        $update['is_identity'] = 2;
        $model->table('bbs_member')->where($where)->update($update);

        // Delete already through application information
        $model->table('bbs_mapply')->where($where)->delete();

        // Update the application for membership
        $count = $model->table('bbs_mapply')->where(array('bbs_id'=>$this->c_id))->count();
        $model->table('bbs')->where(array('bbs_id'=>$this->c_id))->update(array('new_mapplycount'=>$count));

        showDialog(L('wt_common_op_succ'), 'reload', 'succ');

    }
    /**
     * Management application to delete
     */
    public function delWt(){
        // Verify the identity
        $rs = $this->checkIdentity('c');
        if(!empty($rs)){
            showDialog($rs);
        }

        $cmid_array = explode(',', $_GET['cm_id']);
        foreach ($cmid_array as $key=>$val){
            if(!is_numeric($val)) unset($cmid_array[$key]);
        }
        if(empty($cmid_array)){
            showDialog(L('wrong_argument'));
        }

        $model = Model();
        // conditions
        $where = array();
        $where['bbs_id'] = $this->c_id;
        $where['member_id'] = array('in', $cmid_array);

        // Delete the information
        $model->table('bbs_mapply')->where($where)->delete();

        // Update the application for membership
        $count = $model->table('bbs_mapply')->where(array('bbs_id'=>$this->c_id))->count();
        $model->table('bbs')->where(array('bbs_id'=>$this->c_id))->update(array('new_mapplycount'=>$count));

        showDialog(L('wt_common_op_succ'), 'reload', 'succ');
    }
}
