<?php
/**
 * 签到
 *


 

 */

defined('ShopWT') or exit('Access Denied By ShopWT');

class member_signinControl extends mobileMemberControl {
    public function __construct(){
        parent::__construct();
        if (!C('signin_isuse')) {
            output_error('签到失败',array('state'=>'isclose'));
        }
    }
    /**
     * 签到
     */
    public function signin_addWt(){
        //查询今天是否已签到
        $model_signin = Model('signin');
        $result = $model_signin->isAbleSignin($this->member_info['member_id']);
        if (!$result['done']) {
            output_error($result['msg']);
        }
        try {
            $points = C('points_signin');
            //增加签到记录
            $result = Model('signin')->addSignin(array('points'=>$points,'member_id'=>$this->member_info['member_id'],'member_name'=>$this->member_info['member_name']));
            if (!$result) {
                throw Exception('签到失败');
            }
            //增加积分
            $result = Model('points')->savePointsLog('signin',array('pl_memberid'=>$this->member_info['member_id'],'pl_membername'=>$this->member_info['member_name'],'pl_points'=>$points));
            if (!$result) {
                throw Exception('签到失败');
            }
            output_data(array('point'=>$this->member_info['member_points']+$points));
        } catch (Exception $e) {
            output_error($e->getMessage());
        }
    }
    /**
     * 获取是否能签到
     */
    public function checksigninWt(){
        $result = Model('signin')->isAbleSignin($this->member_info['member_id']);
        if (!$result['done']) {
            output_error($result['msg']);
        }
        output_data(array('points_signin'=>C('points_signin')));
    }
    /**
     * 获得签到日志
     */
    public function signin_listWt(){
        $model_signin = Model('signin');
        $where = array();
        $where['sl_memberid'] = $this->member_info['member_id'];
        $signin_list = $model_signin->getSigninList($where, '*', 0, $this->page, 'sl_id desc');
        $page_count = $model_signin->gettotalpage();
        if ($signin_list) {
            foreach ($signin_list as $k=>$v) {
                $v['sl_addtime_text'] = @date('Y-m-d H:i:s', $v['sl_addtime']);
                $signin_list[$k] = $v;
            }
        }
        output_data(array('signin_list' => $signin_list), mobile_page($page_count));
    }
}