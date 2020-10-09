<?php
/**
 * 团队无限级管理（级差）
 * 2019/05/20
 * auth sam
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @link    http://www.weisbao.com
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class team_settingControl extends SystemControl{
	
    public function __construct(){
        parent::__construct();
        Language::read('bonusrules');
    }
    
	public function indexWt() {
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            //基础设置
            $update_array['team_isuse'] = intval($_POST['isuse']);//是否开启团队无限级
            $update_array['team_same_isuse'] = intval($_POST['same_isuse']);//开启平级超越奖
            $update_array['team_same_calculation'] = intval($_POST['same_calculation']);//平级奖计算方式
            $update_array['team_include_self'] = intval($_POST['include_self']);//团队统计是否包括自己
            //结算设置
            $update_array['team_billing_option'] = intval($_POST['billing_option']);//佣金计算去向
            $update_array['team_settlement_event'] = intval($_POST['settlement_event']);//结算事件
            $update_array['team_settlement_days'] = intval($_POST['settlement_days']);//结算天数
            $update_array['team_commission'] = intval($_POST['commission']);//佣金计算项

            $result = $model_setting->updateSetting($update_array);            
            if ($result === true){
                showMessage('提交成功！');
            }else {
                showMessage('提交失败！');
            }
        }else{
            $setting_list = $model_setting->getListSetting();

            Tpl::output('setting',$setting_list);
            Tpl::setDirquna('bonusrules');
            Tpl::showpage('team_setting');
        }
    }

}
