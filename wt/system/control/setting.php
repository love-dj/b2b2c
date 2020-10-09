<?php
/**
 * 网站设置
 *
 *
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class settingControl extends SystemControl{
    private $links = array(
        array('url'=>'w=setting&t=base','lang'=>'web_set'),
        array('url'=>'w=setting&t=dump','lang'=>'fx_dump'),
        array('url'=>'w=setting&t=lc','lang'=>'lc_set'),
        array('url'=>'w=setting&t=login','lang'=>'loginSettings'),
	    array('url'=>'w=setting&t=register','lang'=>'registerSettings'),
		array('url'=>'w=setting&t=seller_login','lang'=>'seller_loginSettings'),
    );
    public function __construct(){
        parent::__construct();
        Language::read('setting');
    }

    public function indexWt() {
        $this->baseWt();
    }

    /**
     * 基本信息
     */
    public function baseWt(){
        $model_setting = Model('setting');
        //$this->write_static_cache('site_imchat',1111);
        if (chksubmit()){
            $list_setting = $model_setting->getListSetting();
            $update_array = array();
            $update_array['wt_mail'] = $_POST['wt_mail'];
            $update_array['wt_phone'] = $_POST['wt_phone'];
			$update_array['wt_qq'] = $_POST['wt_qq'];
            $update_array['wt_time'] = $_POST['wt_time'];
            $update_array['time_zone'] = $this->setTimeZone($_POST['time_zone']);
            $update_array['site_name'] = $_POST['site_name'];
            $update_array['statistics_code'] = $_POST['statistics_code'];
            $update_array['icp_number'] = $_POST['icp_number'];
			$update_array['site_area'] = $_POST['site_area'];
			$update_array['site_imchat'] = $_POST['site_imchat'];
			$update_array['short_video'] = $_POST['short_video'];
			$this->write_static_cache('site_imchat',$_POST['site_imchat']);
            $update_array['site_status'] = $_POST['site_status'];
            $update_array['closed_reason'] = $_POST['closed_reason'];
            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                $this->log(L('wt_edit,web_set'),1);
                showMessage(L('wt_common_save_succ'));
            }else {
                $this->log(L('wt_edit,web_set'),0);
                showMessage(L('wt_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        if ($list_setting['site_imchat'] == ''){
            $list_setting['site_imchat'] = 0;
            $this->write_static_cache('site_imchat',$list_setting['site_imchat']);
        }
        if ($list_setting['short_video']==''){
            $list_setting['short_video']=0;
        }
        foreach ($this->getTimeZone() as $k=>$v) {
            if ($v == $list_setting['time_zone']){
                $list_setting['time_zone'] = $k;break;
            }
        }
        Tpl::output('list_setting',$list_setting);

        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'base'));
		
		Tpl::setDirquna('system');
        Tpl::showpage('setting.base');
    }

    public function write_static_cache($cache_name, $caches, $cache_file_path = '', $type = 0, $url_data = array())
    {
        /*echo ROOT_PATH;
        die;*/
        if (!empty($cache_file_path)) {
            $cache_file_path = ROOT_PATH . $cache_file_path . $cache_name . '.php';
        }
        else {
            $cache_file_path = BASE_DATA_PATH . '/config/' . $cache_name . '.php';
        }

        $content = "<?php\r\n";

        if ($type == 1) {
            $content .= '$url_data = ' . var_export($url_data, true) . ";\r\n";
            $content .= $caches . "\r\n";
        }
        else {
            $content .= '$data = ' . var_export($caches, true) . ";\r\n";
        }

        $content .= '?>';
        $result = file_put_contents($cache_file_path, $content, LOCK_EX);
        return $result;
    }
	
	 /**
     * 楼层快速直达列表
     */
    public function lcWt() {
        $model_setting = Model('setting');
        $lc_info = $model_setting->getRowSetting('wt_lc');
        if ($lc_info !== false) {
            $lc_list = @unserialize($lc_info['value']);
        }
        if (!$lc_list && !is_array($lc_list)) {
            $lc_list = array();
        }
        Tpl::output('lc_list',$lc_list);
        Tpl::output('top_link',$this->sublink($this->links,'lc'));
		Tpl::setDirquna('system');
        Tpl::showpage('wt.lc');
    }

    /**
     * 楼层快速直达添加
     */
    public function lc_addWt() {
        $model_setting = Model('setting');
        $lc_info = $model_setting->getRowSetting('wt_lc');
        if ($lc_info !== false) {
            $lc_list = @unserialize($lc_info['value']);
        }
        if (!$lc_list && !is_array($lc_list)) {
            $lc_list = array();
        }
        if (chksubmit()) {
            if (count($lc_list) >= 8) {
                showMessage('最多可设置8个楼层','index.php?w=wt&t=lc');
            }
            if ($_POST['lc_name'] != '' && $_POST['lc_value'] != '') {
                $data = array('name'=>stripslashes($_POST['lc_name']),'value'=>stripslashes($_POST['lc_value']));
                array_unshift($lc_list, $data);
            }
            $result = $model_setting->updateSetting(array('wt_lc'=>serialize($lc_list)));
            if ($result){
                showMessage('保存成功','index.php?w=wt&t=lc');
            }else {
                showMessage('保存失败');
            }
        }
		Tpl::setDirquna('system');

        Tpl::showpage('wt.lc_add');
    }

    /**
     * 楼层快速直达删除
     */
    public function lc_delWt() {
        $model_setting = Model('setting');
        $lc_info = $model_setting->getRowSetting('wt_lc');
        if ($lc_info !== false) {
            $lc_list = @unserialize($lc_info['value']);
        }
        if (!empty($lc_list) && is_array($lc_list) && intval($_GET['id']) >= 0) {
            unset($lc_list[intval($_GET['id'])]);
        }
        if (!is_array($lc_list)) {
            $lc_list = array();
        }
        $result = $model_setting->updateSetting(array('wt_lc'=>serialize(array_values($lc_list))));
        if ($result){
            showMessage('删除成功');
        }
        showMessage('删除失败');
    }

    /**
     * 楼层快速直达编辑
     */
    public function lc_editWt() {
        $model_setting = Model('setting');
        $lc_info = $model_setting->getRowSetting('wt_lc');
        if ($lc_info !== false) {
            $lc_list = @unserialize($lc_info['value']);
        }
        if (!is_array($lc_list)) {
            $lc_list = array();
        }
        if (!chksubmit()) {
            if (!empty($lc_list) && is_array($lc_list) && intval($_GET['id']) >= 0) {
                $current_info = $lc_list[intval($_GET['id'])];
            }
            Tpl::output('current_info',is_array($current_info) ? $current_info : array());
			Tpl::setDirquna('system');
            Tpl::showpage('wt.lc_add');
        } else {
            if ($_POST['lc_name'] != '' && $_POST['lc_value'] != '' && $_POST['id'] != '' && intval($_POST['id']) >= 0) {
                $lc_list[intval($_POST['id'])] = array('name'=>stripslashes($_POST['lc_name']),'value'=>stripslashes($_POST['lc_value']));
            }
            $result = $model_setting->updateSetting(array('wt_lc'=>serialize($lc_list)));
            if ($result){
                showMessage('编辑成功','index.php?w=wt&t=lc');
            }
            showMessage('编辑失败');
        }


    }

    /**
     * 防灌水设置
     */
    public function dumpWt(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            $update_array['captcha_status_login'] = $_POST['captcha_status_login'];
            $update_array['captcha_status_register'] = $_POST['captcha_status_register'];
            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                $this->log(L('wt_edit,fx_dump'),1);
                showMessage(L('wt_common_save_succ'));
            }else {
                $this->log(L('wt_edit,fx_dump'),0);
                showMessage(L('wt_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
        Tpl::output('top_link',$this->sublink($this->links,'dump'));
		Tpl::setDirquna('system');
        Tpl::showpage('setting.dump');
    }

    /**
     * 设置时区
     *
     * @param int $time_zone 时区键值
     */
    private function setTimeZone($time_zone){
        $zonelist = $this->getTimeZone();
        return empty($zonelist[$time_zone]) ? 'Asia/Shanghai' : $zonelist[$time_zone];
    }

    private function getTimeZone(){
        return array(
        '-12' => 'Pacific/Kwajalein',
        '-11' => 'Pacific/Samoa',
        '-10' => 'US/Hawaii',
        '-9' => 'US/Alaska',
        '-8' => 'America/Tijuana',
        '-7' => 'US/Arizona',
        '-6' => 'America/Mexico_City',
        '-5' => 'America/Bogota',
        '-4' => 'America/Caracas',
        '-3.5' => 'Canada/Newfoundland',
        '-3' => 'America/Buenos_Aires',
        '-2' => 'Atlantic/St_Helena',
        '-1' => 'Atlantic/Azores',
        '0' => 'Europe/Dublin',
        '1' => 'Europe/Amsterdam',
        '2' => 'Africa/Cairo',
        '3' => 'Asia/Baghdad',
        '3.5' => 'Asia/Tehran',
        '4' => 'Asia/Baku',
        '4.5' => 'Asia/Kabul',
        '5' => 'Asia/Karachi',
        '5.5' => 'Asia/Calcutta',
        '5.75' => 'Asia/Katmandu',
        '6' => 'Asia/Almaty',
        '6.5' => 'Asia/Rangoon',
        '7' => 'Asia/Bangkok',
        '8' => 'Asia/Shanghai',
        '9' => 'Asia/Tokyo',
        '9.5' => 'Australia/Adelaide',
        '10' => 'Australia/Canberra',
        '11' => 'Asia/Magadan',
        '12' => 'Pacific/Auckland'
        );
    }

    /**
     * 登录主题图片
     */
    public function loginWt(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $input = array();
            //上传图片
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_PATH.'/login');
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','1.jpg');
            $upload->set('ifremove',false);
			if (!empty($_FILES['login_pic1']['name'])){
                $result = $upload->upfile('login_pic1');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input['p1']['pic'] = $upload->file_name;
					$input['p1']['color'] =$_POST['login_color1'];
					$input['p1']['url'] = $_POST['url1'];
                }
            }elseif ($_POST['old_login_pic1'] != ''){
                $input['p1']['pic'] = '1.jpg';
				$input['p1']['color'] = $_POST['login_color1'];
				$input['p1']['url'] = $_POST['url1'];
            }

            $upload->set('default_dir',ATTACH_PATH.'/login');
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','2.jpg');
            $upload->set('ifremove',false);
			if (!empty($_FILES['login_pic2']['name'])){
                $result = $upload->upfile('login_pic2');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{                    
                    $input['p2']['pic'] = $upload->file_name;
					$input['p2']['color'] = $_POST['login_color2'];
					$input['p2']['url'] = $_POST['url2'];
                }
            }elseif ($_POST['old_login_pic2'] != ''){
                $input['p2']['pic'] = '2.jpg';
				$input['p2']['color'] = $_POST['login_color2'];
				$input['p2']['url'] = $_POST['url2'];
            }

            $upload->set('default_dir',ATTACH_PATH.'/login');
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','3.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['login_pic3']['name'])){
                $result = $upload->upfile('login_pic3');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{                    
                    $input['p3']['pic'] = $upload->file_name;
					$input['p3']['color'] = $_POST['login_color3'];
					$input['p3']['url'] = $_POST['url3'];
                }
            }elseif ($_POST['old_login_pic3'] != ''){
                $input['p3']['pic'] = '3.jpg';
				$input['p3']['color'] = $_POST['login_color3'];
				$input['p3']['url'] = $_POST['url3'];
            }

            $upload->set('default_dir',ATTACH_PATH.'/login');
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','4.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['login_pic4']['name'])){
                $result = $upload->upfile('login_pic4');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input['p4']['pic'] = $upload->file_name;
					$input['p4']['color'] = $_POST['login_color4'];
					$input['p4']['url'] = $_POST['url4'];
                }
            }elseif ($_POST['old_login_pic4'] != ''){
                $input['p4']['pic'] = '4.jpg';
				$input['p4']['color'] =$_POST['login_color4'];
				$input['p4']['url'] = $_POST['url4'];
            }

            $update_array = array();
            if (count($input) > 0){
                $update_array['login_pic'] = serialize($input);
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
        if ($list_setting['login_pic'] != ''){
            $list = unserialize($list_setting['login_pic']);
        }
        Tpl::output('list',$list);
        Tpl::output('top_link',$this->sublink($this->links,'login'));
        Tpl::setDirquna('system');
        Tpl::showpage('setting.login');
    }
    
    
	/**
     * 注册主题图片
     */
    public function registerWt(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $input = array();
            //上传图片
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_PATH.'/register');
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','1.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['register_pic1']['name'])){
                $result = $upload->upfile('register_pic1');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input['p1']['pic'] = $upload->file_name;
					$input['p1']['color'] =$_POST['register_color1'];
					$input['p1']['url'] =$_POST['url1'];
                }
            }elseif ($_POST['old_register_pic1'] != ''){
                $input['p1']['pic'] = '1.jpg';
				$input['p1']['color'] =$_POST['register_color1'];
				$input['p1']['url'] =$_POST['url1'];
            }

            $upload->set('default_dir',ATTACH_PATH.'/register');
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','2.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['register_pic2']['name'])){
                $result = $upload->upfile('register_pic2');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{                    
                    $input['p2']['pic'] = $upload->file_name;
					$input['p2']['color'] =$_POST['register_color2'];
					$input['p2']['url'] =$_POST['url2'];
                }
            }elseif ($_POST['old_register_pic2'] != ''){
                $input['p2']['pic'] = '2.jpg';
				$input['p2']['color'] =$_POST['register_color2'];
				$input['p2']['url'] =$_POST['url2'];
            }

            $upload->set('default_dir',ATTACH_PATH.'/register');
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','3.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['register_pic3']['name'])){
                $result = $upload->upfile('register_pic3');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{                    
                    $input['p3']['pic'] = $upload->file_name;
					$input['p3']['color'] =$_POST['register_color3'];
					$input['p3']['url'] =$_POST['url3'];
                }
            }elseif ($_POST['old_register_pic3'] != ''){
                $input['p3']['pic'] = '3.jpg';
				$input['p3']['color'] =$_POST['register_color3'];
				$input['p3']['url'] =$_POST['url3'];
            }

            $upload->set('default_dir',ATTACH_PATH.'/register');
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','4.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['register_pic4']['name'])){
                $result = $upload->upfile('register_pic4');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input['p4']['pic'] = $upload->file_name;
					$input['p4']['color'] =$_POST['register_color4'];
					$input['p4']['url'] =$_POST['url4'];
                }
            }elseif ($_POST['old_register_pic4'] != ''){
                $input['p4']['pic'] = '4.jpg';
				$input['p4']['color'] =$_POST['register_color4'];
				$input['p4']['url'] =$_POST['url4'];
            }

            $update_array = array();
            if (count($input) > 0){
                $update_array['register_pic'] = serialize($input);
            }

            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                $this->log(L('wt_edit,registerSettings'),1);
                showMessage(L('wt_common_save_succ'));
            }else {
                $this->log(L('wt_edit,registerSettings'),0);
                showMessage(L('wt_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        if ($list_setting['register_pic'] != ''){
            $list = unserialize($list_setting['register_pic']);
        }
        Tpl::output('list',$list);
        Tpl::output('top_link',$this->sublink($this->links,'register'));
	Tpl::setDirquna('system');
        Tpl::showpage('setting.register');
    }

	
/**
     * 商家登录主题图片
     */
    public function seller_loginWt(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $input = array();
            //上传图片
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_PATH.'/seller');
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','1.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['seller_login_pic1']['name'])){
                $result = $upload->upfile('seller_login_pic1');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input['p1']['pic'] = $upload->file_name;
					$input['p1']['color'] =$_POST['seller_login_color1'];
					$input['p1']['url'] =$_POST['url1'];
                }
            }elseif ($_POST['old_seller_login_pic1'] != ''){
                $input['p1']['pic'] = '1.jpg';
				$input['p1']['color'] =$_POST['seller_login_color1'];
				$input['p1']['url'] =$_POST['url1'];
            }

            $upload->set('default_dir',ATTACH_PATH.'/seller');
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','2.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['seller_login_pic2']['name'])){
                $result = $upload->upfile('seller_login_pic2');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{                    
                    $input['p2']['pic'] = $upload->file_name;
					$input['p2']['color'] =$_POST['seller_login_color2'];
					$input['p2']['url'] =$_POST['url2'];
                }
            }elseif ($_POST['old_seller_login_pic2'] != ''){
                $input['p2']['pic'] = '2.jpg';
				$input['p2']['color'] =$_POST['seller_login_color2'];
				$input['p2']['url'] =$_POST['url2'];
            }

            $upload->set('default_dir',ATTACH_PATH.'/seller');
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','3.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['seller_login_pic3']['name'])){
                $result = $upload->upfile('seller_login_pic3');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{                    
                    $input['p3']['pic'] = $upload->file_name;
					$input['p3']['color'] =$_POST['seller_login_color3'];
					$input['p3']['url'] =$_POST['url3'];
                }
            }elseif ($_POST['old_seller_login_pic3'] != ''){
                $input['p3']['pic'] = '3.jpg';
				$input['p3']['color'] =$_POST['seller_login_color3'];
				$input['p3']['url'] =$_POST['url3'];
            }

            $upload->set('default_dir',ATTACH_PATH.'/seller');
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','4.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['seller_login_pic4']['name'])){
                $result = $upload->upfile('seller_login_pic4');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input['p4']['pic'] = $upload->file_name;
					$input['p4']['color'] =$_POST['seller_login_color4'];
					$input['p4']['url'] =$_POST['url4'];
                }
            }elseif ($_POST['old_seller_login_pic4'] != ''){
                $input['p4']['pic'] = '4.jpg';
				$input['p4']['color'] =$_POST['seller_login_color4'];
				$input['p4']['url'] =$_POST['url4'];
            }

            $update_array = array();
            if (count($input) > 0){
                $update_array['seller_login_pic'] = serialize($input);
            }

            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                $this->log(L('wt_edit,seller_loginSettings'),1);
                showMessage(L('wt_common_save_succ'));
            }else {
                $this->log(L('wt_edit,seller_loginSettings'),0);
                showMessage(L('wt_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        if ($list_setting['seller_login_pic'] != ''){
            $list = unserialize($list_setting['seller_login_pic']);
        }
        Tpl::output('list',$list);
        Tpl::output('top_link',$this->sublink($this->links,'seller_login'));
		Tpl::setDirquna('system');
        Tpl::showpage('setting.seller_login');
    }

    //执行计划任务
    public function exetargetWt()
    {
       
        header("content-type:text/html; charset=utf-8"); 
        $page=BASE_SITE_URL.'/system/task/cj_index.php?w=minutes';
        $html = file_get_contents($page,'r');
        $page=BASE_SITE_URL.'/system/task/cj_index.php?w=hour';
        $html = file_get_contents($page,'r');
        $page=BASE_SITE_URL.'/system/task/cj_index.php?w=date';
        $html = file_get_contents($page,'r');

	showMessage(计划任务执行成功,'index.php?w=setting&t=base');
    }

    
}
