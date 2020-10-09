<?php
/**
 * Theme Share
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class theme_shareControl extends BasebbsControl{
    protected $c_id = 0;        // 社区id
    protected $identity = 0;    // 身份 0游客 1圈主 2管理 3成员 4申请中 5申请失败
    protected $bbs_info = array();
    public function __construct(){
        parent::__construct();
        $this->c_id = intval($_GET['c_id']);
        if($this->c_id <= 0){
            echo '<script>CUR_DIALOG.close();</script>';
        }
        Tpl::output('c_id', $this->c_id);
        Language::read('theme_share');
    }
    /**
     * Share the binding
     */
    public function indexWt(){
        $t_id = $_GET['t_id'];
        if($t_id <= 0){
            echo '<script>CUR_DIALOG.close();</script>';
        }
        Tpl::output('t_id', $t_id);

        $affix_list = array();
        $theme_info = Model()->table('bbs_theme')->where(array('theme_id'=>$t_id))->find();
        if(!empty($theme_info)){
            $affix_list = Model()->table('bbs_affix')->where(array('affix_type'=>1, 'bbs_id'=>$this->c_id, 'theme_id'=>$t_id))->limit(1)->select();
            Tpl::output('theme_info', $theme_info);
            Tpl::output('affix_list', $affix_list);
        }else{
            echo '<script>CUR_DIALOG.close();</script>';
        }

        if(chksubmit()){
            $obj_validate = new Validate();
            $validate_arr[] = array("input"=>$_POST["content"], "validator"=>'Length',"min"=>0,"max"=>140,"message"=>Language::get('sharebind_content_not_null'));
            $obj_validate -> validateparam = $validate_arr;
            $error = $obj_validate->validate();
            if ($error != ''){
                showDialog($error,'','error');
            }
            $insert_arr = array();
            $insert_arr['trace_originalid'] = '0';
            $insert_arr['trace_originalmemberid'] = '0';
            $insert_arr['trace_memberid'] = $_SESSION['member_id'];
            $insert_arr['trace_membername'] = $_SESSION['member_name'];
            $insert_arr['trace_memberavatar'] = 'avatar_'.$_SESSION['member_id'].'.jpg';
            $insert_arr['trace_title'] = $_POST['content'];
            $insert_arr['trace_content'] = $this->traceContent($theme_info, $affix_list);
            $insert_arr['trace_addtime'] = time();
            $insert_arr['trace_from'] = 5;
            $result = Model('sns_tracelog')->tracelogAdd($insert_arr);
            if ($result){
                // Off-site sharing
                if (C('share_isuse') == 1){
                    $model = Model('sns_binding');
                    // binding information
                    $bind_list = $model->getUsableApp($_SESSION['member_id']);
                    // Content Sharing
                    $params = array();
                    $params['title'] = L('sharebind_share_theme');
                    $params['url'] = BBS_SITE_URL."/index.php?w=theme&t=theme_detail&c_id=".$this->c_id."&t_id=".$theme_info['theme_id'];
                    $params['comment'] = $theme_info['theme_name'].$_POST['comment'];
                    if(!empty($affix_list[0])){
                        $params['images'] = themeImageUrl($affix_list[0]['affix_filethumb']);
                    }
                    // Share Tencent Weibo
                    if (isset($_POST['checkapp_qqweibo']) && !empty($_POST['checkapp_qqweibo']) && $bind_list['qqweibo']['isbind'] == true){
                        $model->addQQWeiboPic($bind_list['qqweibo'],$params);
                    }
                    // Share Sina Weibo
                    if (isset($_POST['checkapp_sinaweibo']) && !empty($_POST['checkapp_sinaweibo']) && $bind_list['sinaweibo']['isbind'] == true){
                        $model->addSinaWeiboUpload($bind_list['sinaweibo'],$params);
                    }
                }
                Model()->table('bbs_theme')->where(array('theme_id'=>$t_id))->update(array('theme_sharecount'=>array('exp', 'theme_sharecount+1')));
                showDialog(Language::get('sharebind_share_succ'),'','succ','DialogManager.close("share");var count = $(\'em[wttype="share"]\').html(); $(\'em[wttype="share"]\').html(parseInt(count)+1);');
            }else {
                showDialog(Language::get('sharebind_share_fail'),'','error','DialogManager.close("share");');
            }
        }

        if (C('share_isuse') == 1){
            // Other web sites share interface
            $model = Model('sns_binding');
            $app_arr = $model->getUsableApp($_SESSION['member_id']);
            Tpl::output('app_arr',$app_arr);
        }

        Tpl::showpage('theme.share','null_layout');
    }
    /**
     * trace content
     */
    private function traceContent($theme_info, $affix_list){
        $content = "<div class='fd-media'>";
        $url = BBS_SITE_URL."/index.php?w=theme&t=theme_detail&c_id=".$this->c_id."&t_id=".$theme_info['theme_id'];
        if(!empty($affix_list[0])){
            $content .= "<div class='goodsimg'><a target='_blank' href='".$url."'><img src='".themeImageUrl($affix_list[0]['affix_filethumb'])."' onload='javascript:DrawImage(this,120,120);'></a></div>";
        }
        $content .= "<div class=\"goodsinfo\"><p>".$_SESSION['member_name'].L('bbs_at,wt_quote1').$theme_info['bbs_name'].L('wt_quote2').L('bbs_share,sharebind_theme').L('wt_colon').'</p><p>'.L('wt_quote1').$theme_info['theme_name'].L('wt_quote2')."&nbsp;&nbsp;<a href='".$url."'>".L('sharebind_go_and_see')."</a></p></div></div>";
        return $content;
    }
}
