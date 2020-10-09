<?php
/**
 * 社区父类
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

/********************************** 前台control父类 **********************************************/

class BasebbsControl{
    protected $identity = 0;    // 身份 0游客 1圈主 2管理 3成员 4申请中 5申请失败 6禁言
    protected $c_id = 0;        // 社区id
    protected $cm_info = array();   // Members of the information
    protected $m_readperm = 0;  // Members read permissions
    protected $super = 0;
    /**
     * 构造函数
     */
    public function __construct(){
		$model = Model();
        /**
         * 验证社区是否开启
         */
        if (C('bbs_isuse') != '1'){
            @header('location: '.BASE_SITE_URL);die;
        }
        /**
         * 读取通用、布局的语言包
         */
        Language::read('common');
        /**
         * 设置布局文件内容
         */
        Tpl::setLayout('bbs_layout');
        /**
         * 查询是否是超管
         */
        $this->checkSuper();
        /**
         * 获取导航
         */
        Tpl::output('nav_list', rkcache('nav',true));
		
		// 社区分类
        $class_list = $model->table('bbs_class')->where(array('class_status'=>1))->order('class_sort asc')->limit('9')->select();
        Tpl::output('class_list', $class_list);
		
    }
    private function checkSuper() {
        if($_SESSION['is_login']){
            $super = Model('bbs_member')->getSuperInfo(array('member_id' => $_SESSION['member_id']));
            $this->super = empty($super) ? 0 : 1;
        }
        Tpl::output('super', $this->super);
    }
    /**
     * 社区信息
     */
    protected function bbsInfo(){
        $this->bbs_info = Model()->table('bbs')->where(array('bbs_id'=>$this->c_id))->find();

        if(empty($this->bbs_info)){
            showMessage(L('bbs_group_not_exists'), '', '', 'error');
        }
        Tpl::output('bbs_info', $this->bbs_info);
    }
    /**
     * 圈主和管理信息
     */
    protected function manageList(){
        $prefix = 'bbs_managelist';
        $info = rcache($this->c_id, $prefix);
        if (empty($info)) {
            $manager_list = Model()->table('bbs_member')->where(array('bbs_id'=>$this->c_id, 'is_identity'=>array('in', array(1,2))))->select();
            $manager_list = array_under_reset($manager_list, 'is_identity', 2);
            $manager_list[2] = array_under_reset($manager_list[2], 'member_id', 1);
            $info['info'] = serialize($manager_list);
            wcache($this->c_id, $info, $prefix, 60);
        }
        $manager_list = unserialize($info['info']);
        Tpl::output('creator', $manager_list[1][0]);
        Tpl::output('manager_list', $manager_list[2]);
    }
    /**
     * 会员信息
     */
    protected function memberInfo(){
        if($_SESSION['is_login']){
            $this->cm_info = Model()->table('bbs_member')->where(array('bbs_id'=>$this->c_id, 'member_id'=>$_SESSION['member_id']))->find();
            if(!empty($this->cm_info)){
                switch (intval($this->cm_info['cm_state'])){
                    case 1:
                        $this->identity = intval($this->cm_info['is_identity']);
                        break;
                    case 0:
                        $this->identity = 4;
                        break;
                    case 2:
                        $this->identity = 5;
                        break;
                }
                // 禁言
                if($this->cm_info['is_allowspeak'] == 0){
                    $this->identity = 6;
                }
            }
            Tpl::output('cm_info', $this->cm_info);
        }
        Tpl::output('identity', $this->identity);
    }
    /**
     * sidebar相关信息
     */
    protected function sidebar(){
        $prefix = 'bbs_sidebar';
        $data = rcache($this->c_id, $prefix);
        if (empty($data)) {
            // 社区所属分类
            $data['class_info'] = Model()->table('bbs_class')->where(array('class_id'=>$this->bbs_info['class_id']))->find();

            // 明星圈友
            $data['star_member'] = Model()->table('bbs_member')->where(array('cm_state'=>1, 'bbs_id'=>$this->c_id, 'is_star'=>1))->order('rand()')->limit(5)->select();

            // 最新加入
            $data['newest_member'] = Model()->table('bbs_member')->where(array('cm_state'=>1, 'bbs_id'=>$this->c_id))->order('cm_jointime desc')->limit(5)->select();

            // 友情社区
            $data['friendship_list'] = Model()->table('bbs_fs')->where(array('bbs_id'=>$this->c_id, 'friendship_status'=>1))->order('friendship_sort asc')->select();
        }
        Tpl::output('class_info', $data['class_info']);
        Tpl::output('star_member', $data['star_member']);
        Tpl::output('newest_member', $data['newest_member']);
        Tpl::output('friendship_list', $data['friendship_list']);
    }
    /**
     * 最新话题/热门话题/人气回复
     */
    protected function themeTop(){
        $prefix = 'bbs_themetop';
        $info = rcache('bbs', $prefix);
        if (empty($info)) {
            $model = Model();
            // 最新话题
            $data['new_themelist'] = $model->table('bbs_theme')->where(array('is_closed'=>0))->order('theme_id desc')->limit(10)->select();
            // 热门话题
            $data['hot_themelist'] = $model->table('bbs_theme')->where(array('is_closed'=>0))->order('theme_browsecount desc')->limit(10)->select();
            // 人气回复
            $data['reply_themelist'] = $model->table('bbs_theme')->where(array('is_closed'=>0))->order('theme_commentcount desc')->limit(10)->select();
            $info['info'] = serialize($data);
            wcache('bbs', $info, $prefix, 60);
        }
        $data = unserialize($info['info']);
        Tpl::output('new_themelist', $data['new_themelist']);
        Tpl::output('hot_themelist', $data['hot_themelist']);
        Tpl::output('reply_themelist', $data['reply_themelist']);
    }
    /**
     * SEO
     */
    protected function bbsSEO($title= '') {
        Tpl::output('html_title',$title.' '.C('bbs_seotitle'));
        Tpl::output('seo_keywords',C('bbs_seokeywords'));
        Tpl::output('seo_description',C('bbs_seodescription'));
    }

    /**
     * Read permissions
     */
    protected function readPermissions($cm_info){
        $data = rkcache('bbs_level', true);
        $rs = array();
        $rs[0] = 0;
        $rs[0] = L('bbs_no_limit');
        foreach ($data as $v){
            $rs[$v['mld_id']]   = $v['mld_name'];
        }
        switch ($cm_info['is_identity']){
            case 1:
            case 2:
                $rs['255'] = L('bbs_administrator');
                $this->m_readperm = 255;
                return $rs;
                break;
            case 3:
                $rs = array_slice($rs, 0, intval($cm_info['cm_level'])+1, true);
                $this->m_readperm = $cm_info['cm_level'];
                return $rs;
                break;
        }
    }
    /**
     * breadcrumb navigation
     */
    protected function breadcrumd($param = ''){
        $crumd = array(
            0=>array(
                'link'=>BBS_SITE_URL,
                'title'=>L('wt_index')
            ),
            1=>array(
                'link'=>BBS_SITE_URL.'/index.php?w=group&c_id='.$this->c_id,
                'title'=>$this->bbs_info['bbs_name']
            ),
        );
        if(!empty($this->theme_info)){
            $crumd[2] = array(
                'link'=>BBS_SITE_URL.'/index.php?w=theme&t=theme_detail&c_id='.$this->c_id.'&t_id='.$this->t_id,
                'title'=>$this->theme_info['theme_name']
            );
        }
        if(empty($param)){
            unset($crumd[(count($crumd)-1)]['link']);
        }else{
            $crumd[]['title'] = $param;
        }
        Tpl::output('breadcrumd', $crumd);
    }
}
class BasebbsThemeControl extends BasebbsControl{
    protected $bbs_info = array();   // 社区详细信息
    protected $t_id = 0;        // 话题id
    protected $theme_info = array();    // 话题详细信息
    protected $r_id = 0;        // 回复id
    protected $reply_info = array();    // reply info
    protected $cm_info = array();       // Members of the information
    public function __construct(){
        parent::__construct();
        Language::read('bbs');

        $this->c_id = intval($_GET['c_id']);
        if($this->c_id <= 0){
            @header("location: ".BBS_SITE_URL);
        }
        Tpl::output('c_id', $this->c_id);
    }
    /**
     * 话题信息
     */
    protected function themeInfo(){
        $this->t_id = intval($_GET['t_id']);
        if($this->t_id <= 0){
            @header("location: ".BBS_SITE_URL);
        }
        Tpl::output('t_id', $this->t_id);

        $this->theme_info = Model()->table('bbs_theme')->where(array('bbs_id'=>$this->c_id, 'theme_id'=>$this->t_id))->find();
        if(empty($this->theme_info)){
            showMessage(L('bbs_theme_not_exists'), '', '', 'error');
        }
        Tpl::output('theme_info', $this->theme_info);
    }
    /**
     * 验证回复
     */
    protected function checkReplySelf(){
        $this->t_id = intval($_GET['t_id']);
        if($this->t_id <= 0){
            showDialog(L('wrong_argument'));
        }
        Tpl::output('t_id', $this->t_id);

        $this->r_id = intval($_GET['r_id']);
        if($this->r_id <= 0){
            showDialog(L('wrong_argument'));
        }
        Tpl::output('r_id', $this->r_id);

        $this->reply_info = Model()->table('bbs_threply')->where(array('theme_id'=>$this->t_id, 'reply_id'=>$this->r_id, 'member_id'=>$_SESSION['member_id']))->find();
        if(empty($this->reply_info)){
            showDialog(L('wrong_argument'));
        }
        Tpl::output('reply_info', $this->reply_info);
    }
    /**
     * 验证话题
     */
    protected function checkThemeSelf(){
        $this->t_id = intval($_GET['t_id']);
        if($this->t_id <= 0){
            showDialog(L('wrong_argument'));
        }
        Tpl::output('t_id', $this->t_id);

        $this->theme_info = Model()->table('bbs_theme')->where(array('theme_id'=>$this->t_id, 'member_id'=>$_SESSION['member_id']))->find();
        if(empty($this->theme_info)){
            showDialog(L('wrong_argument'));
        }
        Tpl::output('theme_info', $this->theme_info);
    }
}
class BasebbsManageControl extends BasebbsControl{
    protected $bbs_info = array();   // 社区详细信息
    protected $t_id = 0;        // 话题id
    protected $theme_info = array();    // 话题详细信息
    protected $identity = 0;    // 身份 0游客 1圈主 2管理 3成员
    protected $cm_info = array();   // 会员信息
    public function __construct(){
        parent::__construct();
        $this->c_id = intval($_GET['c_id']);
        if($this->c_id <= 0){
            @header("location: ".BBS_SITE_URL);
        }
        Tpl::output('c_id', $this->c_id);
    }
    /**
     * 社区信息
     */
    protected function bbsInfo(){
        // 社区信息
        $this->bbs_info = Model()->table('bbs')->where(array('bbs_id'=>$this->c_id))->find();
        if(empty($this->bbs_info)) @header("location: ".BBS_SITE_URL);
        Tpl::output('bbs_info', $this->bbs_info);
    }
    /**
     * 会员信息
     */
    protected function bbsMemberInfo(){
        // 会员信息
        $this->cm_info = Model()->table('bbs_member')->where(array('bbs_id'=>$this->c_id, 'member_id'=>$_SESSION['member_id']))->find();
        if(!empty($this->cm_info)){
            $this->identity = $this->cm_info['is_identity'];
            Tpl::output('cm_info', $this->cm_info);
        }
        if(in_array($this->identity, array(0,3))){
            @header("location: ".BBS_SITE_URL);
        }
        Tpl::output('identity', $this->identity);
    }
    /**
     * 去除圈主
     */
    protected function removeCreator($array){
        return array_diff($array, array($this->cm_info['member_id']));
    }
    /**
     * 去除圈主和管理
     */
    protected function removeManager($array){
        $where = array();
        $where['is_identity']   = array('in', array(1,2));
        $where['bbs_id']     = $this->c_id;
        $cm_info = Model()->table('bbs_member')->where($where)->select();
        if(empty($cm_info)){
            return $array;
        }
        foreach ($cm_info as $val){
            $array = array_diff($array, array($val['member_id']));
        }
        return $array;
    }
    /**
     * 身份验证
     */
    protected function checkIdentity($type){        // c圈主 m管理 cm圈主和管理
        $this->cm_info = Model()->table('bbs_member')->where(array('bbs_id'=>$this->c_id, 'member_id'=>$_SESSION['member_id']))->find();
        $identity = intval($this->cm_info['is_identity']); $sign = false;
        switch ($type){
            case 'c':
                if($identity != 1) $sign = true;
                break;
            case 'm':
                if($identity != 2) $sign = true;
                break;
            case 'cm':
                if($identity != 1 && $identity != 2) $sign = true;
                break;
            default:
                $sign = true;
                break;
        }
        if ($this->super) {
            $sign = false;
        }
        if($sign){
            return L('bbs_permission_denied');
        }
    }
    /**
     * 会员加入的社区
     */
    protected function memberJoinbbs(){
        // 所属社区信息
        $bbs_array = Model()->table('bbs,bbs_member')->field('bbs.*,bbs_member.is_identity')
                        ->join('inner')->on('bbs.bbs_id=bbs_member.bbs_id')
                        ->where(array('bbs_member.member_id'=>$_SESSION['member_id']))->select();
        Tpl::output('bbs_array', $bbs_array);
    }
    /**
     * Top Navigation
     */
    protected  function sidebar_menu($sign, $child_sign=''){
        $menu = array(
                    'index'=>array('menu_name'=>L('bbs_basic_setting'), 'menu_url'=>'index.php?w=manage&c_id='.$this->c_id),
                    'member'=>array('menu_name'=>L('bbs_member_manage'), 'menu_url'=>'index.php?w=manage&t=member_manage&c_id='.$this->c_id),
                    'applying'=>array('menu_name'=>L('bbs_wait_apply'), 'menu_url'=>'index.php?w=manage&t=applying&c_id='.$this->c_id),
                    'level'=>array('menu_name'=>L('bbs_member_level'), 'menu_url'=>'index.php?w=manage_level&t=level&c_id='.$this->c_id),
                    'class'=>array('menu_name'=>L('bbs_tclass'), 'menu_url'=>'index.php?w=manage&t=class&c_id='.$this->c_id),
                    'inform'=>array(
                                'menu_name'=>L('bbs_inform'),
                                'menu_url'=>'index.php?w=manage_inform&t=inform&c_id='.$this->c_id,
                                'menu_child'=>array(
                                            'untreated'=>array('name'=>L('bbs_inform_untreated'), 'url'=>'index.php?w=manage_inform&t=inform&c_id='.$this->c_id),
                                            'treated'=>array('name'=>L('bbs_inform_treated'), 'url'=>'index.php?w=manage_inform&t=inform&type=treated&c_id='.$this->c_id)
                                        ),
                            ),
                    'managerapply'=>array('menu_name'=>L('bbs_mapply'), 'menu_url'=>'index.php?w=manage_mapply&c_id='.$this->c_id),
                    'friendship'=>array('menu_name'=>L('fbbs'), 'menu_url'=>'index.php?w=manage&t=friendship&c_id='.$this->c_id)
                );
        if($this->identity == 2){
            unset($menu['index']);unset($menu['member']);unset($menu['level']);unset($menu['class']);unset($menu['friendship']);
            unset($menu['inform']['menu_child']['untreated']);unset($menu['managerapply']);
        }
        Tpl::output('sidebar_menu', $menu);
        Tpl::output('sidebar_sign', $sign);
        Tpl::output('sidebar_child_sign', $child_sign);
    }
}
class BasebbsPersonalControl extends BasebbsControl{
    protected  $m_id = 0;   // memeber ID
    public function __construct(){
        parent::__construct();
        if(!$_SESSION['is_login']){
            @header("location: ".BBS_SITE_URL);
        }
        $this->m_id = $_SESSION['member_id'];

        // member information
        $this->bbsMemberInfo();
    }
    /**
     * member information
     */
    protected function bbsMemberInfo(){
        // member information list
        $bbsmember_list = Model()->table('bbs_member')->where(array('member_id'=>$this->m_id))->select();

        $data = array();
        $data['cm_thcount']     = 0;
        $data['cm_comcount']    = 0;
        $data['member_id']      = $_SESSION['member_id'];
        $data['member_name']    = $_SESSION['member_name'];
        if(!empty($bbsmember_list)){
            foreach ($bbsmember_list as $val){
                $data['cm_thcount']     += $val['cm_thcount'];
                $data['cm_comcount']    += $val['cm_comcount'];
            }
        }
        Tpl::output('cm_info', $data);
    }

}
