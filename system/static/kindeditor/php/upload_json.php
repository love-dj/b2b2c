<?php
/**
 * KindEditor PHP 
 *
 */

//引用全局文件
if (!@include('../../../../shopwt.php')) exit('shopwt.php isn\'t exists!');
if (!@include(BASE_DATA_PATH.'/config/config.php')) exit('config.php isn\'t exists!');
if (file_exists(BASE_PATH.'/config/config.php')){
	include(BASE_PATH.'/config/config.php');
}
if (file_exists(BASE_PATH.'/library/function/core.php')){
	include(BASE_PATH.'/library/function/core.php');
}

global $config;
        if ($config['oss']['open'] != 'true') {
			//文件保存目录路径
			$save_path = '../../../upfiles/editor/';
			//文件保存目录URL
			$save_url = UPLOAD_SITE_URL.'/editor/';
			$save_url = str_replace('static/kindeditor/php/data/','',$save_url);
        }


//定义允许上传的文件扩展名
$ext_arr = array(
	'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
	'flash' => array('swf', 'flv'),
	'media' => array('flv', 'mp3', 'avi', 'mp4'),
	'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'pdf'),
);
//最大文件大小
$max_size = 1000000*1024;

//有上传文件时
if (empty($_FILES) === false) {
	//原文件名
	$file_name = $_FILES['imgFile']['name'];
	//服务器上临时文件名
	$tmp_name = $_FILES['imgFile']['tmp_name'];
	//文件大小
	$file_size = $_FILES['imgFile']['size'];
	//检查文件名
	if (!$file_name) {
		alert("请选择文件。");
	}
	//检查文件大小
	if ($file_size > $max_size) {
		alert("上传文件大小超过限制。");
	}
	//检查目录名
	$dir_name = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
	if (empty($ext_arr[$dir_name])) {
		alert("目录名不正确。");
	}
	//获得文件扩展名
	$temp_arr = explode(".", $file_name);
	$file_ext = array_pop($temp_arr);
	$file_ext = trim($file_ext);
	$file_ext = strtolower($file_ext);
	//检查扩展名
	if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
		alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。");
	}
	
	if ($config['oss']['open'] == 'true') {
		$GLOBALS['setting_config']['oss']['img_url'] = $config['oss']['img_url'];
		$GLOBALS['setting_config']['oss']['api_url'] = $config['oss']['api_url'];
		$GLOBALS['setting_config']['oss']['bucket'] = $config['oss']['bucket'];
		$GLOBALS['setting_config']['oss']['access_id'] = $config['oss']['access_id'];
		$GLOBALS['setting_config']['oss']['access_key'] = $config['oss']['access_key'];
		if (file_exists(BASE_ROOT_PATH.'/apid/oss/sdk.class.php')){
				include(BASE_ROOT_PATH.'/apid/oss/sdk.class.php');
		}
		$dir_name = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
		$dir_ = $dir_name.'/'.date("Ymd",time());
		//文件保存目录路径
		$save_path =  'editor/'.$dir_ .'/';
		//文件保存目录URL
		$save_url = $config['oss']['img_url'] . '/editor/'.$dir_ .'/';
		$save_url = str_replace('static/kindeditor/php/data/','',$save_url);

		$ext = strtolower(pathinfo($_FILES['imgFile']['name'], PATHINFO_EXTENSION));
		$fname=date("YmdHis",time()) ;
		$new_file_name = $fname. '_' . rand(10000, 99999) . '.' . $ext;

		$result = oss::upload($_FILES['imgFile']['tmp_name'],$save_path.$new_file_name);
		if ($result == false) {
			alert("上传失败！！。");
		}else{
			$file_url = $save_url . $new_file_name;

			header('Content-type: text/html; charset=UTF-8');
			echo json_encode(array('error' => 0, 'url' => $file_url));
			exit;
		}

		exit;
	}
	
	
	//检查目录
	if (@is_dir($save_path) === false) {
		alert("上传目录不存在。");
	}
	//检查目录写权限
	if (@is_writable($save_path) === false) {
		alert("上传目录没有写权限。");
	}
	//检查是否已上传
	if (@is_uploaded_file($tmp_name) === false) {
		alert("临时文件可能不是上传文件。");
	}
	
	//创建文件夹
	if ($dir_name !== '') {
		$save_path .= $dir_name . "/";
		$save_url .= $dir_name . "/";
		if (!file_exists($save_path)) {
			mkdir($save_path);
		}
	}
	$ymd = date("Ymd");
	$save_path .= $ymd . "/";
	$save_url .= $ymd . "/";
	if (!file_exists($save_path)) {
		mkdir($save_path);
	}
	//新文件名
	$new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
	//移动文件
	$file_path = $save_path . $new_file_name;
	if (move_uploaded_file($tmp_name, $file_path) === false) {
		alert("上传文件失败。");
	}
	@chmod($file_path, 0644);
	$file_url = $save_url . $new_file_name;

	header('Content-type: text/html; charset=UTF-8');
	echo json_encode(array('error' => 0, 'url' => $file_url));
	exit;
}

function alert($msg) {
	header('Content-type: text/html; charset=UTF-8');
	echo json_encode(array('error' => 1, 'message' => $msg));
	exit;
}
?>