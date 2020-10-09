<?php
/**
 * 图片空间操作
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class sns_bbsControl extends BaseSNSControl {
    public function __construct() {
        parent::__construct();
        /**
         * 读取语言包
         */
        Language::read('sns_bbs,member_sns,sns_home');
        Tpl::output('menu_sign', 'bbs');

        $this->get_visitor();   // 获取访客
        define('BBS_TEMPLATES_URL', BBS_SITE_URL.'/templets/'.TPL_NAME);

        $where = array();
        $where['name']  = !empty($this->master_info['member_truename'])?$this->master_info['member_truename']:$this->master_info['member_name'];
        Model('seo')->type('sns')->param($where)->show();

        $this->sns_messageboard();
    }
    /**
     * index 默认为话题
     */
    public function indexWt(){
        $this->themeWt();
    }
    /**
     * 话题
     */
    public function themeWt(){
        $model = Model();
        $theme_list = $model->table('bbs_theme')->where(array('member_id'=>$this->master_id))->page(10)->order('theme_id desc')->select();
        Tpl::output('showpage', $model->showpage('2'));
        Tpl::output('theme_list', $theme_list);
        if(!empty($theme_list)){
            $theme_list = array_under_reset($theme_list, 'theme_id');
            $themeid_array = array(); $bbsid_array = array();
            foreach ($theme_list as $val){
                $themeid_array[]    = $val['theme_id'];
                $bbsid_array[]   = $val['bbs_id'];
            }
            $themeid_array = array_unique($themeid_array);
            $bbsid_array = array_unique($bbsid_array);
            // 附件
            $affix_list = $model->table('bbs_affix')->where(array('affix_type'=>1, 'member_id'=>$this->master_id, 'theme_id'=>array('in', $themeid_array)))->select();
            $affix_list = array_under_reset($affix_list, 'theme_id', 2);
            Tpl::output('affix_list', $affix_list);
        }

        $this->profile_menu('theme');
        Tpl::showpage('sns_bbstheme');
    }
    /**
     * 社区
     */
    public function bbsWt(){
        $model = Model();
        $cm_list = $model->table('bbs_member')->where(array('member_id'=>$this->master_id))->order('cm_jointime desc')->select();
        if(!empty($cm_list)){
            $cm_list = array_under_reset($cm_list, 'bbs_id'); $bbsid_array = array_keys($cm_list);
            $bbs_list = $model->table('bbs')->where(array('bbs_id'=>array('in', $bbsid_array)))->select();
            Tpl::output('bbs_list', $bbs_list);
        }
        $this->profile_menu('bbs');
        Tpl::showpage('sns_bbs');
    }
    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_key=''){
        $menu_array = array();

        $theme_menuname = $this->relation==3?L('sns_my_theme'):L('sns_TA_theme');
        $bbs_menuname = $this->relation==3?L('sns_my_group'):L('sns_TA_group');
        $menu_array = array(
            1=>array('menu_key'=>'theme','menu_name'=>$theme_menuname,'menu_url'=>'index.php?w=sns_bbs&t=theme&mid='.$this->master_id),
            2=>array('menu_key'=>'bbs','menu_name'=>$bbs_menuname,'menu_url'=>'index.php?w=sns_bbs&t=bbs&mid='.$this->master_id),
        );

        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
