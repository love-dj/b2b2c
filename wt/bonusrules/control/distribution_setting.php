<?php
/**
 * 三级分销管理
 * 2019/05/20
 * auth sam
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @link    http://www.weisbao.com
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class distribution_settingControl extends SystemControl{
	
    public function __construct(){
        parent::__construct();
        Language::read('bonusrules');
    }
    
	public function indexWt() {
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            //基础设置
            $update_array['distribution_isuse'] = intval($_POST['isuse']);//是否开启分销
            $uselevel = intval($_POST['uselevel']);
            if($uselevel > 3 || $uselevel < 1){
                $uselevel = 3;
            }
            $update_array['distribution_uselevel'] = $uselevel;//分销层级
            $update_array['distribution_level_one'] = $_POST['level_one'];//默认一级分销佣金比例
            $update_array['distribution_level_two'] = $_POST['level_two'];//默认二级分销佣金比例
            $update_array['distribution_level_three'] = $_POST['level_three'];//默认三级分销佣金比例
            $update_array['distribution_self_buy'] = intval($_POST['self_buy']);//是否开启自购
            //结算设置
            $update_array['distribution_identity'] = intval($_POST['identity']);//获取身份资格
            $update_array['distribution_billing_option'] = intval($_POST['billing_option']);//结算选项
            $update_array['distribution_settlement_event'] = intval($_POST['settlement_event']);//结算事件
            $update_array['distribution_settlement_days'] = intval($_POST['settlement_days']);//结算天数
            $update_array['distribution_commission'] = intval($_POST['commission']);//佣金计算项

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
            Tpl::showpage('distribution_setting');
        }
    }

}
