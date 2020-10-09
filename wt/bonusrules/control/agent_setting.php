<?php
/**
 * 区域分红管理
 * 2019/05/20
 * auth sam
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @link    http://www.weisbao.com
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class agent_settingControl extends SystemControl{
	
    public function __construct(){
        parent::__construct();
        Language::read('bonusrules');
    }
    
	public function indexWt() {
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            $update_array['agent_isuse'] = intval($_POST['isuse']);//是否开启区域分红
            $update_array['agent_again'] = intval($_POST['again']);//申请驳回后可再次申请
            $update_array['agent_level_difference'] = intval($_POST['level_difference']);//是否开启极差分红
            $update_array['agent_province_rate'] = intval($_POST['province_rate']);//省代理分红比例
            $update_array['agent_city_rate'] = intval($_POST['city_rate']);//市代理分红比例
            $update_array['agent_area_rate'] = intval($_POST['area_rate']);//区县代理分红比例
            $update_array['agent_average_commission'] = intval($_POST['average_commission']);//是否开启平均分红
            $update_array['agent_settlement_event'] = intval($_POST['settlement_event']);//结算事件
            $update_array['agent_settlement_days'] = intval($_POST['settlement_days']);//结算天数
            $update_array['agent_commission'] = intval($_POST['commission']);//佣金计算基数

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
            Tpl::showpage('agent_setting');
        }
    }

}
