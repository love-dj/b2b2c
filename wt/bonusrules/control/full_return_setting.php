<?php
/**
 * 满额返现管理
 * 2019/06/04
 * auth hyz
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @link    http://www.weisbao.com
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class full_return_settingControl extends SystemControl{
	
    public function __construct(){
        parent::__construct();
        Language::read('bonusrules');
    }
    
	public function indexWt() {
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            //基础设置
            $update_array['full_return_isuse'] = intval($_POST['isuse']);//是否开启满额返现

            //结算设置
            $update_array['full_return_commission'] = intval($_POST['commission']);//佣金计算项
            $update_array['full_return_turnover_mode'] = intval($_POST['turnover_mode']);//返现时间段
            $update_array['full_return_base_ratio'] = intval($_POST['base_ratio']);//返现基数
            $update_array['full_return_limit_money'] = intval($_POST['limit_money']);//1个权益等于(默认1元)
            $update_array['full_return_limit'] = intval($_POST['limit']);//权益总数限制
            $update_array['full_return_give_ratio'] = intval($_POST['give_ratio']);//返现比例
            $update_array['full_return_return_times'] = intval($_POST['return_times']);//返现时间
            $update_array['full_return_settlement_event'] = intval($_POST['settlement_event']);//计算事件

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
            Tpl::showpage('full_return_setting');
        }
    }

}
