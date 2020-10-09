<?php
/**
 * 社区首页
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class manage_informControl extends BasebbsManageControl{
    public function __construct(){
        parent::__construct();
        Language::read('manage_inform');
        $this->bbsSEO();
    }
    /**
     * Inform
     */
    public function informWt(){
        // bbs information
        $this->bbsInfo();
        // Membership information
        $this->bbsMemberInfo();
        // Members to join the bbs list
        $this->memberJoinbbs();
        $model = Model();
        if (chksubmit()){
            if(empty($_POST['i_id']))
                showDialog(L('wrong_argument'));


            foreach ($_POST['i_id'] as $val){
                $i_rewards = intval($_POST['i_rewards'][$val]);
                $update = array();
                $update['inform_id']    = $val;
                $update['inform_state'] = 1;
                $update['inform_opid']  = $_SESSION['member_id'];
                $update['inform_opname']= $_SESSION['member_name'];
                $update['inform_opexp'] = $i_rewards;
                $update['inform_opresult']  = $_POST['i_result'][$val] == ''? L('wt_nothing') : $_POST['i_result'][$val];

                $rs = $model->table('bbs_inform')->where(array('inform_id'=>$val))->update($update);

                // Experience increase or decrease
                if($rs && $i_rewards != 0){
                    $inform_info = $model->table('bbs_inform')->field('bbs_id,member_id,member_name')->where(array('inform_id'=>$val))->find();
                    if(!empty($inform_info)){
                        $param = array();
                        $param['bbs_id']     = $inform_info['bbs_id'];
                        $param['member_id']     = $inform_info['member_id'];
                        $param['member_name']   = $inform_info['member_name'];
                        $param['type']          = 'master';
                        $param['exp']           = $i_rewards;
                        $param['desc']          = L('bbs_exp_inform');
                        $param['itemid']        = 0;
                        Model('bbs_exp')->saveExp($param);
                    }
                }
            }

            // Update the inform number
            $count = $model->table('bbs_inform')->where(array('bbs_id'=>$this->c_id, 'inform_state'=>0))->count();
            $model->table('bbs')->where(array('bbs_id'=>$this->c_id))->update(array('new_informcount'=>$count));

            showDialog(L('wt_common_op_succ'),'reload','succ');

        }

        $where = array();
        $where['bbs_id'] = $this->c_id;
        $where['inform_state'] = $_GET['type'] == 'treated' ? 1 : 0;

        $inform_list = $model->table('bbs_inform')->where($where)->page(10)->order('inform_id desc')->select();
        // tidy
        if(!empty($inform_list)){
            foreach ($inform_list as $key=>$val){
                $inform_list[$key]['url']   = spellInformUrl($val);
                $inform_list[$key]['title'] = L('bbs_theme,wt_quote1').$val['theme_name'].L('wt_quote2');
                if($val['reply_id'] != 0)
                    $inform_list[$key]['title'] .= L('bbs_inform_reply_title');
            }
        }
        Tpl::output('inform_list', $inform_list);
        Tpl::output('show_page', $model->showpage(2));

        $type = $_GET['type'] == 'treated' ? 'treated' : 'untreated';
        $this->sidebar_menu('inform', $type);
        $_GET['type'] == 'treated' ? Tpl::showpage('group_manage_inform.treated') : Tpl::showpage('group_manage_inform.untreated');
    }

    /**
     * Delete Inform
     */
    public function delinformWt(){
        // Authentication
        $rs = $this->checkIdentity('c');
        if(!empty($rs)){
            showDialog($rs);
        }
        $inform_id = explode(',', $_GET['i_id']);
        if(empty($inform_id)){
            echo 'false';exit;
        }
        $where = array();
        $where['bbs_id'] = $this->c_id;
        $where['inform_id'] = array('in', $inform_id);
        Model()->table('bbs_inform')->where($where)->delete();
        showDialog(L('wt_common_del_succ'), 'reload', 'succ');
    }
}
