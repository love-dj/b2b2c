<?php
/**
 * ShopWT控件管理
 *  

 
 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');
class wtControl extends SystemControl{
	 private $links = array(
	    array('url'=>'w=setting&t=lc','lang'=>'wt_set'),
		//array('url'=>'w=wt&t=sms','lang'=>'sms_set'),
		//临时注释
		//array('url'=>'w=wt&t=rc','lang'=>'rc_set'),
		//array('url'=>'w=wt&t=webchat','lang'=>'webchat_set'),
        
    );
	public function __construct(){
		parent::__construct();
		Language::read('wt,setting');
	}
	    public function indexWt() {
        $this->rc_addWt();
    }
		 /**
     * 基本信息
     */
    public function baseWt(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $list_setting = $model_setting->getListSetting();
/*            $update_array = array();
            $update_array['wt_mail'] = $_POST['wt_mail'];
            $update_array['wt_phone'] = $_POST['wt_phone'];
			$update_array['wt_qq'] = $_POST['wt_qq'];
            $update_array['wt_time'] = $_POST['wt_time'];
			$update_array['points_invite'] = intval($_POST['points_invite'])?$_POST['points_invite']:0;
			$update_array['points_rebate'] = intval($_POST['points_rebate'])?$_POST['points_rebate']:0;*/
            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                $this->log(L('wt_edit,wt_set'),1);
                showMessage(L('wt_common_save_succ'));
            }else {
                $this->log(L('wt_edit,wt_set'),0);
                showMessage(L('wt_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();

        Tpl::output('list_setting',$list_setting);

        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'base'));
		
		Tpl::setDirquna('system');
        Tpl::showpage('wt.base');
    }

	
		 /**
     * 首页热门关键词链接
     */
    public function rcWt() {
        $model_setting = Model('setting');
        $rc_info = $model_setting->getRowSetting('wt_rc');
        if ($rc_info !== false) {
            $rc_list = @unserialize($rc_info['value']);
        }
        if (!$rc_list && !is_array($rc_list)) {
            $rc_list = array();
        }
        Tpl::output('rc_list',$rc_list);
        Tpl::output('top_link',$this->sublink($this->links,'rc'));
		Tpl::setDirquna('system');
        Tpl::showpage('wt.rc');
    }

    /**
     * 楼层快速直达添加
     */
    public function rc_addWt() {
        $model_setting = Model('setting');
        $rc_info = $model_setting->getRowSetting('wt_rc');
        if ($rc_info !== false) {
            $rc_list = @unserialize($rc_info['value']);
        }
        if (!$rc_list && !is_array($rc_list)) {
            $rc_list = array();
        }
        if (chksubmit()) {
            if (count($rc_list) >= 8) {
                showMessage('最多可设置8个楼层','index.php?w=wt&t=rc');
            }
            if ($_POST['rc_name'] != '' && $_POST['rc_value'] != '' && $_POST['rc_blod'] != '') {
                $data = array('name'=>stripslashes($_POST['rc_name']),'value'=>stripslashes($_POST['rc_value']),'is_blod'=>stripslashes($_POST['rc_blod']));
                array_unshift($rc_list, $data);
            }
            $result = $model_setting->updateSetting(array('wt_rc'=>serialize($rc_list)));
            if ($result){
                showMessage('保存成功','index.php?w=wt&t=rc');
            }else {
                showMessage('保存失败');
            }
        }
		Tpl::setDirquna('system');

        Tpl::showpage('wt.rc_add');
    }

    /**
     * 删除
     */
    public function rc_delWt() {
        $model_setting = Model('setting');
        $rc_info = $model_setting->getRowSetting('wt_rc');
        if ($rc_info !== false) {
            $rc_list = @unserialize($rc_info['value']);
        }
        if (!empty($rc_list) && is_array($rc_list) && intval($_GET['id']) >= 0) {
            unset($rc_list[intval($_GET['id'])]);
        }
        if (!is_array($rc_list)) {
            $rc_list = array();
        }
        $result = $model_setting->updateSetting(array('wt_rc'=>serialize(array_values($rc_list))));
        if ($result){
            showMessage('删除成功');
        }
        showMessage('删除失败');
    }

    /**
     * 编辑
     */
    public function rc_editWt() {
        $model_setting = Model('setting');
        $rc_info = $model_setting->getRowSetting('wt_rc');
        if ($rc_info !== false) {
            $rc_list = @unserialize($rc_info['value']);
        }
        if (!is_array($rc_list)) {
            $rc_list = array();
        }
        if (!chksubmit()) {
            if (!empty($rc_list) && is_array($rc_list) && intval($_GET['id']) >= 0) {
                $current_info = $rc_list[intval($_GET['id'])];
            }
            Tpl::output('current_info',is_array($current_info) ? $current_info : array());
			Tpl::setDirquna('system');
            Tpl::showpage('wt.rc_add');
        } else {
            if ($_POST['rc_name'] != '' && $_POST['rc_value'] != '' && $_POST['rc_blod'] != '' && $_POST['id'] != '' && intval($_POST['id']) >= 0) {
                $rc_list[intval($_POST['id'])] = array('name'=>stripslashes($_POST['rc_name']),'value'=>stripslashes($_POST['rc_value']),'is_blod'=>stripslashes($_POST['rc_blod']));
            }
            $result = $model_setting->updateSetting(array('wt_rc'=>serialize($rc_list)));
            if ($result){
                showMessage('编辑成功','index.php?w=wt&t=rc');
            }
            showMessage('编辑失败');
        }


    }
}