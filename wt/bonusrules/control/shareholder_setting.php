<?php
/**
 * 股东分红管理
 * 2019/06/10
 * auth hyz
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @link    http://www.weisbao.com
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class shareholder_settingControl extends SystemControl{
	
    public function __construct(){
        parent::__construct();
        Language::read('bonusrules');
    }
    
	public function indexWt() {
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            //基础设置
            $update_array['shareholder_isuse'] = intval($_POST['isuse']);//是否开启股东分红
            
            //结算设置
            $update_array['shareholder_commission'] = intval($_POST['commission']);//分红计算项
            $update_array['shareholder_rate'] = intval($_POST['rate']);//分红比例
            $update_array['shareholder_limit'] = intval($_POST['limit']);//分红股东数量
            $update_array['shareholder_level'] = intval($_POST['level']);//分红最低股东等级权重
            $update_array['shareholder_times'] = intval($_POST['times']);//分红时间段
            $update_array['shareholder_settlement_event'] = intval($_POST['settlement_event']);//结算事件

            $result = $model_setting->updateSetting($update_array);            
            if ($result === true){
                showMessage('提交成功！');
            }else {
                showMessage('提交失败！');
            }
        }else{
            $setting_list = $model_setting->getListSetting();
			$level_list = Model('distribution_level')->select();

            Tpl::output('setting',$setting_list);
			
            Tpl::output('level_list',$level_list);
			
            Tpl::setDirquna('bonusrules');
            Tpl::showpage('shareholder_setting');
        }
    }

}
