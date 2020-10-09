<?php
/**
 * 社区首页
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class indexControl extends BasebbsControl{
    public function __construct(){
        Language::read('bbs');
        parent::__construct();
    }
    /**
     * 首页
     */
    public function indexWt(){
        $model = Model();

        // 热门社区      **显示3个社区，按推荐随机排列，推荐不够按成员数主题数降序排列**
        $bbs_list = $model->table('bbs')->field('*, is_hot*rand() as rand')->where(array('bbs_status'=>1, 'is_hot'=>1))->order('rand desc')->limit(3)->select();
        if(!empty($bbs_list)){
            $bbs_list = array_under_reset($bbs_list, 'bbs_id');$bbsid_array = array_keys($bbs_list);
            // 查询社区最新主题
            foreach($bbs_list as $key=>$val){
                // 最新的两条数据
                $theme_list = $model->table('bbs_theme')->where(array('bbs_id'=>$val['bbs_id'], 'is_closed'=>0))->order('theme_id desc')->limit(2)->select();
                $bbs_list[$key]['theme_list'] = $theme_list;
            }
            Tpl::output('bbs_list', $bbs_list);

            $now = strtotime(date('Y-m-d',time()));
            // 今天发表的主题
            $nowthemecount_array = $model->table('bbs_theme')->field('count(bbs_id) as count,bbs_id')->group('bbs_id')->where(array('theme_addtime'=>array('gt', $now), 'bbs_id'=>array('in', $bbsid_array), 'is_closed'=>0))->select();
            if(!empty($nowthemecount_array)){
                $nowthemecount_array = array_under_reset($nowthemecount_array, 'bbs_id');
                Tpl::output('nowthemecount_array', $nowthemecount_array);
            }

            // 今天新加入的成员
            $nowjoincount_array = $model->table('bbs_member')->field('count(bbs_id) as count,bbs_id')->group('bbs_id')->where(array('cm_jointime'=>array('gt', $now), 'bbs_id'=>array('in', $bbsid_array)))->select();
            if(!empty($nowjoincount_array)){
                $nowjoincount_array = array_under_reset($nowjoincount_array, 'bbs_id');
                Tpl::output('nowjoincount_array', $nowjoincount_array);
            }
        }

        // 社区分类
        //$class_list = $model->table('bbs_class')->where(array('class_status'=>1, 'is_recommend'=>1))->order('class_sort asc')->select();
        //Tpl::output('class_list', $class_list);

        // 推荐社区
        $rbbs_list = $model->table('bbs')->field('*, is_recommend*rand() as rand')->where(array('bbs_status'=>1, 'is_recommend'=>1))->order('rand desc')->limit('20')->select();
        Tpl::output('rbbs_list', $rbbs_list);

        // 推荐话题
        $theme_list = $model->table('bbs_theme')->field('*, is_recommend*rand() as rand')->where(array('has_affix'=>1, 'is_closed'=>0, 'is_recommend'=>1))->order('rand desc')->limit(10)->select();
        if(!empty($theme_list)){
            $theme_list = array_under_reset($theme_list, 'theme_id'); $themeid_array = array_keys($theme_list);

            // 附件
            $affix_list = $model->table('bbs_affix')->field('theme_id, min(affix_filethumb) affix_filethumb')->where(array('theme_id'=>array('in', $themeid_array), 'affix_type'=>1))->group('theme_id')->select();
            if(!empty($affix_list)) $affix_list = array_under_reset($affix_list, 'theme_id');


            foreach ($theme_list as $key=>$val){
                if(isset($affix_list[$val['theme_id']])) $theme_list[$key]['affix'] = themeImageUrl($affix_list[$val['theme_id']]['affix_filethumb']);
            }

            Tpl::output('theme_list', $theme_list);
        }

        // 商品话题
        $gtheme_list = $model->table('bbs_theme')->where(array('has_goods'=>1, 'is_closed'=>0))->order('theme_id desc')->limit(6)->select();
        if(!empty($gtheme_list)){
            $gtheme_list = array_under_reset($gtheme_list, 'theme_id'); $themeid_array = array_keys($gtheme_list);

            // 社区商品
            $thg_list = $model->table('bbs_thg')->where(array('theme_id'=>array('in', $themeid_array), 'reply_id'=>0))->select();
            $thg_list = tidyThemeGoods($thg_list, 'theme_id', 2);
            Tpl::output('thg_list', $thg_list);

            Tpl::output('gtheme_list', $gtheme_list);
        }

        // 优秀成员
        $member_list = $model->table('bbs_member')->field('*, is_recommend*rand() as rand')->where(array('is_recommend'=>1))->order('rand desc')->limit(5)->select();

        if(!empty($member_list)){
            $member_list = array_reverse($member_list);
            $one_member  = array_pop($member_list);
            $where = array();
            $where['member_id'] = $one_member['member_id'];
            $where['bbs_id'] = $one_member['bbs_id'];
            $one_membertheme = $model->table('bbs_theme')->where($where)->order('theme_id desc')->limit(4)->select();
            Tpl::output('one_member', $one_member);
            Tpl::output('one_membertheme', $one_membertheme);

            if(!empty($member_list)){
                $where = '';
                foreach ($member_list as $val){
                    $where .= '( bbs_member.member_id = '.$val['member_id'].' and bbs_member.bbs_id = '.$val['bbs_id'].') or ';
                }
                $where = rtrim($where, 'or ');
                $more_membertheme = $model->field('min(bbs_member.member_id) member_id,min(bbs_member.member_name) member_name,min(bbs_theme.bbs_id) bbs_id,min(bbs_theme.theme_id) theme_id,min(bbs_theme.theme_name) theme_name')->table('bbs_member,bbs_theme')->join('inner')->on('bbs_member.member_id = bbs_theme.member_id and bbs_member.bbs_id = bbs_theme.bbs_id')
                        ->where($where)->group('bbs_member.member_id,bbs_member.bbs_id')->select();
                Tpl::output('more_membertheme', $more_membertheme);
            }
        }

        // 最新话题/热门话题/人气回复
        $this->themeTop();

        // 首页幻灯
        $loginpic = unserialize(C('bbs_loginpic'));
        Tpl::output('loginpic', $loginpic);

        $this->bbsSEO();
        Tpl::showpage('index');
    }
    /**
     * 创建社区
     */
    public function add_groupWt(){
        if($_SESSION['is_login'] != 1){
            @header("location: " . urlLogin('login', 'index', array('ref_url' => getRefUrl())));
        }
        if(!intval(C('bbs_iscreate'))){
            showMessage(L('bbs_grooup_not_create'), '', '', 'error');
        }
        $model = Model();
        // 在验证
        // 允许创建社区验证
        $where = array();
        $where['bbs_masterid'] = $_SESSION['member_id'];
        $create_count = $model->table('bbs')->where($where)->count();
        if(intval($create_count) >= C('bbs_createsum')) showDialog(L('bbs_create_max_error'));

        // 允许加入社区验证
        $where = array();
        $where['member_id'] = $_SESSION['member_id'];
        $join_count = $model->table('bbs_member')->where($where)->count();
        if(intval($join_count) >= C('bbs_joinsum')) showDialog(L('bbs_join_max_error'));

        if(chksubmit()){
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                    array("input"=>$_POST["c_name"], "require"=>"true", "message"=>L('bbs_name_not_null'))
            );
            $error = $obj_validate->validate();
            if($error != ''){
                showDialog($error);
            }else{
                $insert = array();
                $insert['bbs_name']          = $_POST['c_name'];
                $insert['bbs_masterid']      = $_SESSION['member_id'];
                $insert['bbs_mastername']    = $_SESSION['member_name'];
                $insert['bbs_desc']          = $_POST['c_desc'];
                $insert['bbs_tag']           = $_POST['c_tag'];
                $insert['bbs_pursuereason']  = $_POST['c_pursuereason'];
                $insert['bbs_status']        = 2;
                $insert['is_recommend']         = 0;
                $insert['class_id']             = intval($_POST['class_id']);
                $insert['bbs_joinaudit']     = 0;
                $insert['bbs_addtime']       = time();
                $insert['bbs_mcount']        = 1;
                $result = $model->table('bbs')->insert($insert);
                if($result){
                    // Membership level information
                    $data = rkcache('bbs_level', true);

                    // 把圈主信息加入社区会员表
                    $insert = array();
                    $insert['member_id']    = $_SESSION['member_id'];
                    $insert['bbs_id']    = $result;
                    $insert['bbs_name']  = $_POST['c_name'];
                    $insert['member_name']  = $_SESSION['member_name'];
                    $insert['cm_applytime'] = $insert['cm_jointime'] = time();
                    $insert['cm_state']     = 1;
                    $insert['cm_level']     = $data[1]['mld_id'];
                    $insert['cm_levelname'] = $data[1]['mld_name'];
                    $insert['cm_exp']       = 1;
                    $insert['cm_nextexp']   = $data[2]['mld_exp'];
                    $insert['is_identity']  = 1;
                    $insert['cm_lastspeaktime'] = '';
                    $model->table('bbs_member')->insert($insert);

                    showDialog(L('wt_common_op_succ'),'index.php?w=group&c_id='.$result, 'succ');
                }else{
                    showDialog(L('wt_common_op_fail'));
                }
            }
        }
        Tpl::output('create_count', $create_count);
        Tpl::output('join_count', $join_count);

        $this->bbsSEO(L('bbs_create'));
        Tpl::showpage('group_add');
    }
    /**
     * 我加入的社区
     */
    public function myjoinedbbsWt(){
        $model = Model('bbs_member');

        $cm_list = $model->getbbsMemberList(array('member_id'=>$_SESSION['member_id'], 'bbs_id' => array('neq', 0)),'bbs_id,bbs_name,is_identity', 0, 'is_identity asc');
        if (empty($cm_list)) {
            echo false;die;
        }
        if (strtoupper(CHARSET) == 'GBK'){
            $cm_list = Language::getUTF8($cm_list);
        }
        echo json_encode($cm_list);
    }
    /**
     * 社区名称验证
     */
    public function check_bbs_nameWt(){
        $name = $_GET['name'];
        if (strtoupper(CHARSET) == 'GBK'){
            $name = Language::getGBK($name);
        }
        $rs = Model()->table('bbs')->where(array('bbs_name'=>$name))->find();
        if (!empty($rs)){
            echo 'false';
        }else{
            echo 'true';
        }
    }
}
