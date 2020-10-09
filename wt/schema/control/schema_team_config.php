<?php
/**
 * schema管理
 * 2018/10/22
 * auth feng
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @license    http://www.weisbao.com
 * @link       联系方式：13632978801
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class schema_team_configControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('schema');
	}


    /**
     * 团队奖金设置
     */
    public function indexWt() {
        $schema_config = Model('setting');
        $setting_list = $schema_config->getListSetting();
        Tpl::output('setting',$setting_list);
        Tpl::setDirquna('schema');
        Tpl::showpage('schema_team_config');
    }

    /**
     * 奖金保存
     */
    public function schema_team_config_saveWt() {
     	$schema_config = Model('setting');
        $update_array = array();

        $update_array['schema_team_open'] = intval($_POST['schema_team_open']);             //团队奖开关
        $update_array['schema_team_condition'] = intval($_POST['schema_team_condition']);   //升级条件（满足其一或者全部满足）
//        $update_array['schema_team_layer_ratio'] = $_POST['schema_team_layer_ratio'];

    	$result = $schema_config->updateSetting($update_array);
    	if ($result === true){
    		$this->log('设置保存', 0);
    		showMessage(Language::get('nc_common_save_succ'));
    	}else {
    		$this->log('设置保存', 0);
    		showMessage(Language::get('nc_common_save_fail'));
    	}
    }
}
