<?php
/**
 * 三级分销管理
 * 2019/05/20
 * auth sam
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @link    http://www.weisbao.com
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class buy_return_settingControl extends SystemControl{
	
    public function __construct(){
        parent::__construct();
        Language::read('bonusrules');
    }
    
	public function indexWt() {
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            //基础设置
            $update_array['buy_return_isuse'] = intval($_POST['isuse']);//是否开启单品消费返现
            
            //结算设置
            //结算设置
            $update_array['buy_return_type'] = intval($_POST['type']);//返现方式
            $update_array['buy_return_commission'] = intval($_POST['commission']);//返现计算项
            $update_array['buy_return_rate'] = intval($_POST['rate']);//返现比例
            $update_array['buy_return_each_rate'] = intval($_POST['each_rate']);//每期返现比例
            $update_array['buy_return_times'] = intval($_POST['times']);//返现时间
            $update_array['buy_return_settlement_event'] = intval($_POST['settlement_event']);//结算天数
            $update_array['buy_return_settlement_days'] = intval($_POST['settlement_days']);//结算天数

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
            Tpl::showpage('buy_return_setting');
        }
    }

}
