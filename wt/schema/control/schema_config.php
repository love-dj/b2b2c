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
class schema_configControl extends SystemControl{
	public function __construct(){   
         
		parent::__construct();
		Language::read('schema');
	}

	public function indexWt() {
		$this->schema_configWt();
	}

    /**
     * 奖金制度设置
     */
    public function schema_configWt() {
    	$schema_config = Model('setting');
        $setting_list = $schema_config->getListSetting();
    	Tpl::output('setting',$setting_list);
    	Tpl::setDirquna('schema');
    	Tpl::showpage('schema_config');
    }

    /**
     * 奖金制度设置保存
     */
    public function schema_config_saveWt() {
     	$schema_config = Model('setting');        
        $update_array = array();
        $update_array['schema_isuse'] = intval($_POST['schema_isuse']);
        $update_array['schema_condition'] = intval($_POST['schema_condition']);
        $update_array['schema_layer'] = intval($_POST['schema_layer']);
        $update_array['schema_layer_one_ratio'] = intval($_POST['schema_layer_one_ratio']);
        $update_array['schema_layer_two_ratio'] = intval($_POST['schema_layer_two_ratio']);
        $update_array['schema_layer_three_ratio'] = intval($_POST['schema_layer_three_ratio']);
        $update_array['schema_inner'] = $_POST['schema_inner'];
    	$result = $schema_config->updateSetting($update_array);
    	if ($result === true){
    		$this->log('分销设置保存', 0);
    		showMessage(Language::get('nc_common_save_succ'));
    	}else {
    		$this->log('分销设置保存', 0);
    		showMessage(Language::get('nc_common_save_fail'));
    	}
    }

    /*public function settlementWt() {
        $schema_config = Model('setting');
        $setting_list = $schema_config->getListSetting();
        Tpl::output('setting',$setting_list);
        Tpl::setDirquna('schema');
        Tpl::showpage('schema_config.settlement');
    }

    public function active() {
        $schema_config = Model('setting');
        $setting_list = $schema_config->getListSetting();
        Tpl::output('setting',$setting_list);
        Tpl::setDirquna('schema');
        Tpl::showpage('schema_config.active');
    }

    public function center() {
        $schema_config = Model('setting');
        $setting_list = $schema_config->getListSetting();
        Tpl::output('setting',$setting_list);
        Tpl::setDirquna('schema');
        Tpl::showpage('schema_config.center');
    }*/


}
