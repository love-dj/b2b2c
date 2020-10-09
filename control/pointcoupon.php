<?php
/**
 * 优惠券
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class pointcouponControl extends BasePointShopControl {
    public function __construct() {
        parent::__construct();
        //判断系统是否开启优惠券功能
        if (C('coupon_allow') != 1){
            showDialog('系统未开启优惠券功能','index.php','error');
        }
    }
    public function indexWt(){
        $this->pointcouponWt();
    }
    /**
     * 优惠券列表
     */
    public function pointcouponWt(){
        //查询会员及其附属信息
        parent::pointsMInfo();
        $model_coupon = Model('coupon');
        //模板状态
        $templatestate_arr = $model_coupon->getTemplateState();
        //领取方式
        $gettype_arr = $model_coupon->getGettypeArr();
        
        $model_member = Model('member');
        //查询会员信息
        $member_info = $model_member->getMemberInfoByID($_SESSION['member_id']);
                
        //查询优惠券列表
        $where = array();
        $where['coupon_t_gettype']     = $gettype_arr['points']['sign'];
        $where['coupon_t_state']       = $templatestate_arr['usable']['sign'];
        //$where['coupon_t_start_date']  = array('elt',time());
        $where['coupon_t_end_date']    = array('egt',time());
        if (intval($_GET['price']) > 0){
            $where['voucher_t_price'] = intval($_GET['price']);
        }
        //查询仅我能兑换和所需积分
        $points_filter = array();
        if (intval($_GET['isable']) == 1){
            $points_filter['isable'] = $member_info['member_points'];
        }
        if (intval($_GET['points_min']) > 0){
            $points_filter['min'] = intval($_GET['points_min']);
        }
        if (intval($_GET['points_max']) > 0){
            $points_filter['max'] = intval($_GET['points_max']);
        }
                
        if (count($points_filter) > 0){
            asort($points_filter);
            if (count($points_filter) > 1){
                $points_filter = array_values($points_filter);
                $where['coupon_t_points'] = array('between',array($points_filter[0],$points_filter[1]));
            } else {
                if ($points_filter['min']){
                    $where['coupon_t_points'] = array('egt',$points_filter['min']);
                } elseif ($points_filter['max']) {
                    $where['coupon_t_points'] = array('elt',$points_filter['max']);
                } elseif (isset($points_filter['isable'])) {
                    $where['coupon_t_points'] = array('elt',$points_filter['isable']);
                }
            }
        }
        //仅我能兑换的会员级别
        if (intval($_GET['isable']) == 1){
            $member_currgrade = $model_member->getOneMemberGrade($member_info['member_exppoints']);
            $member_info['member_grade_level'] = $member_currgrade?$member_currgrade['level']:0;
            $where['coupon_t_mgradelimit'] = array('elt',$member_info['member_grade_level']);
        }
        
        //排序
        switch ($_GET['orderby']){
            case 'exchangenumdesc':
                $orderby = 'coupon_t_giveout desc,';
                break;
            case 'exchangenumasc':
                $orderby = 'coupon_t_giveout asc,';
                break;
            case 'pointsdesc':
                $orderby = 'coupon_t_points desc,';
                break;
            case 'pointsasc':
                $orderby = 'coupon_t_points asc,';
                break;
        }
        $orderby .= 'coupon_t_id desc';
        $rptlist = $model_coupon->getRptTemplateList($where, '*', 0, 18, $orderby);
        Tpl::output('rptlist',$rptlist);
        Tpl::output('show_page', $model_coupon->showpage(2));
        //分类导航
        $nav_link = array(
                0=>array('title'=>L('homepage'),'link'=>BASE_SITE_URL),
                1=>array('title'=>'积分中心','link'=>urlShop('points','index')),
                2=>array('title'=>'优惠券列表')
        );
        Tpl::output('nav_link_list', $nav_link);
        Tpl::showpage('pointcoupon');
    }
    
    /**
     * 兑换优惠券
     */
    public function rptexchangeWt(){
        $tid = intval($_GET['tid']);
        if($tid <= 0){
            $tid = intval($_POST['tid']);
        }
        if($_SESSION['is_login'] != '1'){
            $js = "login_dialog();";
            showDialog('','','js',$js);
        }elseif ($_GET['dialog']){
            $js = "CUR_DIALOG = ajax_form('rptexchange', '您要兑换的优惠券', 'index.php?w=pointcoupon&t=rptexchange&tid={$tid}', 550);";
            showDialog('','','js',$js);
            die;
        }
        $result = true;
        $message = "";
        if ($tid <= 0){
            $result = false;
            L('wrong_argument');
        }
        if ($result){
            //查询可兑换优惠券模板信息
            $template_info = Model('coupon')->getCanChangeTemplateInfo($tid,intval($_SESSION['member_id']));
            if ($template_info['state'] == false){
                $result = false;
                $message = $template_info['msg'];
            }else {
                //查询会员信息
                $member_info = Model('member')->getMemberInfoByID($_SESSION['member_id'],'member_points');
                Tpl::output('member_info',$member_info);
                Tpl::output('template_info',$template_info['info']);
            }
        }
        Tpl::output('message',$message);
        Tpl::output('result',$result);
        Tpl::showpage('pointcoupon.exchange','null_layout');
    }
    /**
     * 兑换优惠券保存信息
     */
    public function rptexchange_saveWt(){
        if($_SESSION['is_login'] != '1'){
            $js = "login_dialog();";
            showDialog('','','js',$js);
        }
        $tid = intval($_POST['tid']);
        $js = "DialogManager.close('rptexchange');";
        if ($tid <= 0){
            showDialog(L('wrong_argument'),'','error',$js);
        }
        $model_coupon = Model('coupon');
        //验证是否可以兑换优惠券
        $data = $model_coupon->getCanChangeTemplateInfo($tid,intval($_SESSION['member_id']));
        if ($data['state'] == false){
            showDialog($data['msg'],'','error',$js);
        }
        //添加优惠券信息
        $data = $model_coupon->exchangeCoupon($data['info'],$_SESSION['member_id'],$_SESSION['member_name']);
        if ($data['state'] == true){
            showDialog($data['msg'],'','succ',$js);
        } else {
            showDialog($data['msg'],'','error',$js);
        }
    }
}
