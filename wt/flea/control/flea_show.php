<?php
/**
 * 闲置主页seo设置
 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');
class flea_showcontrol extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('setting,flea_index_setting');
		if($GLOBALS['setting_config']['flea_isuse']!='1'){
			showMessage(Language::get('flea_isuse_off_tips'),'index.php?w=dashboard&t=welcome');
		}
	}
	
	public function indexWt() {
        $this->show_manageWt();
    }
	
	/**
	 * 闲置首页广告
	 */
	public function show_manageWt(){
		$model_setting = Model('setting');
		if (chksubmit()){
			$input = array();
			//上传图片
			$upload = new UploadFile();
			$upload->set('default_dir',ATTACH_PATH.DS.'shopwt');
			$upload->set('thumb_ext',	'');
			$upload->set('file_name','flea_1.jpg');
			$upload->set('ifremove',false);
			if (!empty($_FILES['show_pic1']['name'])){
				$result = $upload->upfile('show_pic1');
				if (!$result){
					showMessage($upload->error,'','','error');
				}else{
					$input[1]['pic'] = $upload->file_name;
					$input[1]['url'] = $_POST['show_url1'];
				}
			}elseif ($_POST['old_show_pic1'] != ''){
				$input[1]['pic'] = $_POST['old_show_pic1'];
				$input[1]['url'] = $_POST['show_url1'];
			}

			$upload->set('default_dir',ATTACH_PATH.DS.'shopwt');
			$upload->set('thumb_ext',	'');
			$upload->set('file_name','flea_2.jpg');
			$upload->set('ifremove',false);
			if (!empty($_FILES['show_pic2']['name'])){
				$result = $upload->upfile('show_pic2');
				if (!$result){
					showMessage($upload->error,'','','error');
				}else{
					$input[2]['pic'] = $upload->file_name;
					$input[2]['url'] = $_POST['show_url2'];
				}
			}elseif ($_POST['old_show_pic2'] != ''){
				$input[2]['pic'] = $_POST['old_show_pic2'];
				$input[2]['url'] = $_POST['show_url2'];
			}

			$upload->set('default_dir',ATTACH_PATH.DS.'shopwt');
			$upload->set('thumb_ext', '');
			$upload->set('file_name', 'flea_3.jpg');
			$upload->set('ifremove', false);
			if (!empty($_FILES['show_pic3']['name'])){
				$result = $upload->upfile('show_pic3');
				if (!$result){
					showMessage($upload->error,'','','error');
				}else{
					$input[3]['pic'] = $upload->file_name;
					$input[3]['url'] = $_POST['show_url3'];
				}
			}elseif ($_POST['old_show_pic3'] != ''){
				$input[3]['pic'] = $_POST['old_show_pic3'];
				$input[3]['url'] = $_POST['show_url3'];
			}

			$upload->set('default_dir',ATTACH_PATH.DS.'shopwt');
			$upload->set('thumb_ext',	'');
			$upload->set('file_name','flea_4.jpg');
			$upload->set('ifremove',false);
			if (!empty($_FILES['show_pic4']['name'])){
				$result = $upload->upfile('show_pic4');
				if (!$result){
					showMessage($upload->error,'','','error');
				}else{
					$input[4]['pic'] = $upload->file_name;
					$input[4]['url'] = $_POST['show_url4'];
				}
			}elseif ($_POST['old_show_pic4'] != ''){
				$input[4]['pic'] = $_POST['old_show_pic4'];
				$input[4]['url'] = $_POST['show_url4'];
			}
			
			$upload->set('default_dir',ATTACH_PATH.DS.'shopwt');
			$upload->set('thumb_ext',	'');
			$upload->set('file_name','flea_5.jpg');
			$upload->set('ifremove',false);
			if (!empty($_FILES['show_pic5']['name'])){
				$result = $upload->upfile('show_pic5');
				if (!$result){
					showMessage($upload->error,'','','error');
				}else{
					$input[5]['pic'] = $upload->file_name;
					$input[5]['url'] = $_POST['show_url5'];
				}
			}elseif ($_POST['old_show_pic4'] != ''){
				$input[5]['pic'] = $_POST['old_show_pic5'];
				$input[5]['url'] = $_POST['show_url5'];
			}

			$update_array = array();
			if (count($input) > 0){
				$update_array['flea_loginpic'] = serialize($input);
			}

			$result = $model_setting->updateSetting($update_array);
			if ($result === true){
				$this->log(L('wt_edit,loginSettings'),1);
				showMessage(L('wt_common_save_succ'));
			}else {
				$this->log(L('wt_edit,loginSettings'),0);
				showMessage(L('wt_common_save_fail'));
			}
		}
		$list_setting = $model_setting->getListSetting();
		if ($list_setting['flea_loginpic'] != ''){
			$list = unserialize($list_setting['flea_loginpic']);
		}
		Tpl::output('list', $list);
		Tpl::setDirquna('flea');
		Tpl::showpage('flea_setting.show');
	}

	
}