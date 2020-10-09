<?php
/**
 * Personal Center
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class p_centerControl extends BasebbsPersonalControl{
    public function __construct(){
        parent::__construct();
        Language::read('p_center');
    }

    /**
     * Personal Center theme list
     */
    public function indexWt(){
        $model = Model();
        $theme_list = $model->table('bbs_theme')->where(array('member_id'=>$this->m_id))->page(10)->order('theme_id desc')->select();
        if(!empty($theme_list)){
            $theme_list = array_under_reset($theme_list, 'theme_id');
            $themeid_array = array(); $bbsid_array = array();
            foreach ($theme_list as $val){
                $themeid_array[]    = $val['theme_id'];
                $bbsid_array[]   = $val['bbs_id'];
            }
            $themeid_array = array_unique($themeid_array);
            $bbsid_array = array_unique($bbsid_array);

            // affix
            $affix_list = $model->table('bbs_affix')->where(array('affix_type'=>1, 'member_id'=>$this->m_id, 'theme_id'=>array('in', $themeid_array)))->select();
            $affix_list = array_under_reset($affix_list, 'theme_id', 2);

            // like
            $like_list = $model->table('bbs_like')->where(array('theme_id'=>array('in', $themeid_array)))->select();
            $like_list = array_under_reset($like_list, 'theme_id');
            if(!empty($like_list)){
                $lt_id = array_keys($like_list);
                Tpl::output('lt_id', $lt_id);
            }
        }

        Tpl::output('show_page', $model->showpage('2'));
        Tpl::output('theme_list', $theme_list);
        Tpl::output('affix_list', $affix_list);

        $this->profile_menu('theme', 'theme');
        Tpl::showpage('p_center.theme');
    }

    /**
     * Personal Center likeing theme list
     */
    public function likeingWt(){
        $model = Model();
        $like_array = $model->table('bbs_like')->field('bbs_id,theme_id')->where(array('member_id'=>$this->m_id))->order('theme_id desc')->page(10)->select();
        if(!empty($like_array)){
            $theme_list = array_under_reset($like_array, 'theme_id');
            $themeid_array = array(); $bbsid_array = array();
            foreach ($theme_list as $val){
                $themeid_array[]    = $val['theme_id'];
                $bbsid_array[]   = $val['bbs_id'];
            }
            $themeid_array = array_unique($themeid_array);
            $bbsid_array = array_unique($bbsid_array);
            // theme
            $theme_list = $model->table('bbs_theme')->where(array('theme_id'=>array('in', $themeid_array)))->select();
            // affix
            $affix_list = $model->table('bbs_affix')->where(array('affix_type'=>1, 'theme_id'=>array('in', $themeid_array)))->select();
            $affix_list = array_under_reset($affix_list, 'theme_id', 2);

            Tpl::output('theme_list', $theme_list);
            Tpl::output('affix_list', $affix_list);
        }

        $this->profile_menu('theme', 'likeing');
        Tpl::showpage('p_center.likeing');
    }

    /**
     * Personal Center my bbs group
     */
    public function my_groupWt(){
        $model = Model();
        $bbsmember_array = $model->table('bbs_member')->where(array('member_id'=>$this->m_id))->select();
        if(!empty($bbsmember_array)){
            $bbsmember_array = array_under_reset($bbsmember_array, 'bbs_id');
            Tpl::output('cm_array', $bbsmember_array);
            $bbsid_array = array_keys($bbsmember_array);
            $bbs_list = $model->table('bbs')->where(array('bbs_id'=>array('in', $bbsid_array)))->select();
            Tpl::output('bbs_list', $bbs_list);
        }
        $this->profile_menu('group', 'group');
        Tpl::showpage('p_center.group');
    }

    /**
     * Personal Center my inform
     */
    public function my_informWt(){
        // language
        Language::read('manage_inform');
        $model = Model();
        $where = array();
        $where['member_id'] = $_SESSION['member_id'];
        $inform_list = $model->table('bbs_inform')->where($where)->page(10)->order('inform_id desc')->select();  // tidy
        if(!empty($inform_list)){
            foreach ($inform_list as $key=>$val){
                $inform_list[$key]['url']   = spellInformUrl($val);
                $inform_list[$key]['title'] = L('bbs_theme,wt_quote1').$val['theme_name'].L('wt_quote2');
                $inform_list[$key]['state'] = $this->informStatr(intval($val['inform_state']));
                if($val['reply_id'] != 0)
                    $inform_list[$key]['title'] .= L('bbs_inform_reply_title');
            }
        }
        Tpl::output('inform_list', $inform_list);
        Tpl::output('show_page', $model->showpage(2));

        $this->profile_menu('inform', 'inform');
        Tpl::showpage('p_center.inform');
    }

    /**
     * Inform state
     */
    private function informStatr($state){
        switch ($state){
            case 0:
                return L('bbs_inform_untreated');
                break;
            case 1:
                return L('bbs_inform_treated');
                break;
        }
    }

    /**
     * Delete inform
     */
    public function delinformWt(){
        $inform_id = explode(',', $_GET['i_id']);
        if(empty($inform_id)){
            echo 'false';exit;
        }
        $where = array();
        $where['member_id'] = $_SESSION['member_id'];
        $where['inform_id'] = array('in', $inform_id);
        Model()->table('bbs_inform')->where($where)->delete();
        showDialog(L('wt_common_del_succ'), 'reload', 'succ');
    }

    /**
     * Personal Center my recycled
     */
    public function my_recycledWt(){
        $model = Model();
        $recycle_list = $model->table('bbs_recycle')->where(array('member_id'=>$_SESSION['member_id']))->order('recycle_id desc')->page(10)->select();
        Tpl::output('recycle_list', $recycle_list);
        Tpl::output('show_page', $model->showpage(2));
        $this->profile_menu('recycled', 'recycled');
        Tpl::showpage('p_center.recycled');
    }

    /**
     * Empty the recycle bin
     */
    public function clr_recycledWt(){
        Model()->table('bbs_recycle')->where(array('member_id'=>$_SESSION['member_id']))->delete();
        showDialog(L('wt_common_op_succ'),'reload','succ');
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  types of navigation
     * @param string    $menu_key   key of navigation
     * @return
     */
    private function profile_menu($menu_type, $menu_key){
        $menu_array = array();
        switch ($menu_type){
            case 'theme':
                $menu_array = array(
                    1=>array('menu_key'=>'theme','menu_name'=>L('p_center_published_theme'),'menu_url'=>'index.php?w=p_center'),
                    2=>array('menu_key'=>'likeing','menu_name'=>L('p_center_liked_theme'),'menu_url'=>'index.php?w=p_center&t=likeing'),
                );
                break;
            case 'group':
                $menu_array = array(
                    1=>array('menu_key'=>'group','menu_name'=>L('p_center_my_bbs'),'menu_url'=>'index.php?w=p_center&t=my_group'),
                );
                break;
            case 'inform':
                $menu_array = array(
                    1=>array('menu_key'=>'inform','menu_name'=>L('p_center_my_inform'),'menu_url'=>'index.php?w=p_center&t=my_inform'),
                );
                break;
            case 'recycled':
                $menu_array = array(
                    1=>array('menu_key'=>'recycled','menu_name'=>L('p_center_my_recycled'),'menu_url'=>'index.php?w=p_center&t=my_recycled'),
                );
                break;
        }
        Tpl::output('menu_type', $menu_type);
        Tpl::output('member_menu', $menu_array);
        Tpl::output('menu_key', $menu_key);
    }
}
