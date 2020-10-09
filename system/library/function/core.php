<?php
/**
 * 公共方法
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
require_once(BASE_VENDOR_PATH.'/php-sdk/autoload.php');
use Qiniu\Auth;  //使用auth类
use Qiniu\Storage\UploadManager;
if(!function_exists('dumps')){
    /**
     * 打印函数
     */
    function dumps()
    {
        $args = func_get_args();
        foreach ($args as $val) {
            echo '<pre>';
            var_dump($val);
            echo '</pre>';
        }
    }
}



/**
 * 产生验证码
 *
 * @param bool $buildcookie 是否存储cookie
 * @return string
 */
function buildVercode($buildcookie = true ){
    $vcode = random(6, 1);
    $character = 'abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY2345678';
    if($character) {
        $vcode = '';
        for($i = 0; $i < 4; $i++) {
			$vcode .= $character[mt_rand(0, strlen($character)-1)];
        }
    }
	if($buildcookie){
		setWtCookie('verifycode', encrypt(strtoupper($vcode)."\t".time(),MD5_KEY),3600);
	}
    return $vcode;
}

/**
 * 验证验证码
 *
 * @param string $wthash 哈希数
 * @param string $value 待验证值
 * @return boolean
 */
function checkVercode($wthash='',$value){
    list($checkvalue, $checktime) = explode("\t", decrypt(cookie('verifycode'),MD5_KEY));
    $return = $checkvalue == strtoupper($value);
    if (!$return) setWtCookie('verifycode','',-3600);
    return $return;
}

/**
 * 设置cookie
 *
 * @param string $name cookie 的名称
 * @param string $value cookie 的值
 * @param int $expire cookie 有效周期
 * @param string $path cookie 的服务器路径 默认为 /
 * @param string $domain cookie 的域名
 * @param string $secure 是否通过安全的 HTTPS 连接来传输 cookie,默认为false
 */
function setWtCookie($name, $value, $expire='3600', $path='', $domain='', $secure=false){
	if (empty($path)) $path = '/';
	if (empty($domain)) $domain = SUBDOMAIN_SUFFIX ? SUBDOMAIN_SUFFIX : '';
	$name = defined('COOKIE_PRE') ? COOKIE_PRE.$name : strtoupper(substr(md5(MD5_KEY),0,4)).'_'.$name;
	$expire = intval($expire)?intval($expire):(intval(SESSION_EXPIRE)?intval(SESSION_EXPIRE):3600);
	$result = setcookie($name, $value, time()+$expire, $path, $domain, $secure);
	$_COOKIE[$name] = $value;
}

/**
 * 取得COOKIE的值
 *
 * @param string $name
 * @return unknown
 */
function cookie($name= ''){
	$name = defined('COOKIE_PRE') ? COOKIE_PRE.$name : strtoupper(substr(md5(MD5_KEY),0,4)).'_'.$name;
	return $_COOKIE[$name];
}

/**
 * 当访问的act或op不存在时调用此函数并退出脚本
 *
 * @param string $w
 * @param string $t
 * @return void
 */
function requestNotFound($w = null, $t = null) {
    showMessage('您访问的页面不存在！', BASE_SITE_URL, 'exception', 'error', 1, 3000);
    exit;
}

/**
 * 输出信息
 *
 * @param string $msg 输出信息
 * @param string/array $url 跳转地址 当$url为数组时，结构为 array('msg'=>'跳转连接文字','url'=>'跳转连接');
 * @param string $show_type 输出格式 默认为html
 * @param string $msg_type 信息类型 succ 为成功，error为失败/错误
 * @param string $is_show  是否显示跳转链接，默认是为1，显示
 * @param int $time 跳转时间，默认为2秒
 * @return string 字符串类型的返回结果
 */
function showMessage($msg,$url='',$show_type='html',$msg_type='succ',$is_show=1,$time=2000){
	if (!class_exists('Language')) import('bin.language');
	Language::read('core_lang_index');
	$lang	= Language::getLangContent();
	/**
	 * 如果默认为空，则跳转至上一步链接
	 */
	$url = ($url!='' ? $url : getReferer());

    $msg_type = in_array($msg_type,array('succ','error')) ? $msg_type : 'error';

    /**
     * 输出类型
     */
    switch ($show_type){
        case 'json':
            $return = '{';
            $return .= '"msg":"'.$msg.'",';
            $return .= '"url":"'.$url.'"';
            $return .= '}';
            echo $return;
            break;
        case 'exception':
            echo '<!DOCTYPE html>';
            echo '<html>';
            echo '<head>';
            echo '<meta http-equiv="Content-Type" content="text/html; charset='.CHARSET.'" />';
            echo '<title></title>';
            echo '<style type="text/css">';
            echo 'body { font-family: "Verdana";padding: 0;margin: 0;background: #eaeaea;font: 12px/20px "Hiragino Sans GB", "Microsoft Yahei", arial, 宋体, "Helvetica Neue", Helvetica, STHeiTi, sans-serif;}';
			echo 'div { background-color: #FFF; border-radius: 5px; width: 480px; height: auto; margin: 0 0 0 -240px; padding: 20px; position: absolute;  z-index: 1;  top: 20%; left: 50%; box-shadow: 0 0 5px rgba(0,0,0,0.1);}';
            echo 'h2 { margin: 0 auto;font-size: 72px;color: #333;line-height: 30px;padding-bottom: 40px;margin: 20px 0 0 10px;}';
            echo 'dl { float: left;display: inline;clear: both;padding: 0;margin: 10px 20px 5px 10px;}';
            echo 'dt { font-size: 12px;line-height: 40px;color: #666;padding: 0;margin: 0;border-width: 0px;}';
            echo 'dd { font-size: 18px;font-weight: bold;line-height: 40px;color: #333;padding: 0px;margin: 0;}';
            echo '</style>';
            echo '</head>';
            echo '<body>';
			echo '<div>';
            echo '<h2>:-(</h2>';
            echo '<dl>';
            echo '<dd>'.$lang['error_notice_operate'].'</dd>';
            echo '<dt>'.$lang['error_info'].'：'.$msg.'</dt>';
            echo '</dl>';
			echo '</div>';
            echo '</body>';
            echo '</html>';
            exit;
            break;
        case 'javascript':
            echo "<script>";
            echo "alert('". $msg ."');";
            echo "location.href='". $url ."'";
            echo "</script>";
            exit;
            break;
        case 'tenpay':
            echo "<html><head>";
            echo "<meta name=\"TENCENT_ONLINE_PAYMENT\" content=\"China TENCENT\">";
            echo "<script language=\"javascript\">";
            echo "window.location.href='" . $url . "';";
            echo "</script>";
            echo "</head><body></body></html>";
            exit;
            break;
        default:
            /**
             * 不显示右侧工具条
             */
            Tpl::output('hide_rightbar', 1);
            if (is_array($url)){
                foreach ($url as $k => $v){
                    $url[$k]['url'] = $v['url']?$v['url']:getReferer();
                }
            }
            /**
             * 读取信息布局的语言包
             */
            Language::read("msg");
            /**
             * html输出形式
             * 指定为指定项目目录下的error模板文件
             */
            Tpl::setDir('');
			Tpl::output('html_title',Language::get('wt_html_title'));
			Tpl::output('msg',$msg);
			Tpl::output('url',$url);
			Tpl::output('msg_type',$msg_type);
			Tpl::output('is_show',$is_show);
			Tpl::showpage('msg','msg_layout',$time);
	}
	exit;
}

/**
 * 消息提示，主要适用于普通页面AJAX提交的情况
 *
 * @param string $message 消息内容
 * @param string $url 提示完后的URL去向
 * @param stting $alert_type 提示类型 error/succ/notice 分别为错误/成功/警示
 * @param string $extrajs 扩展JS
 * @param int $time 停留时间
 */
function showDialog($message = '', $url = '', $alert_type = 'error', $extrajs = '', $time = 2){
	if (empty($_GET['inajax'])){
		if ($url == 'reload') $url = '';
		showMessage($message.$extrajs,$url,'html',$alert_type,1,$time*1000);
	}
	$message = str_replace("'", "\\'", strip_tags($message));

	$paramjs = null;
	if ($url == 'reload'){
		$paramjs = 'window.location.reload()';
	}elseif ($url != ''){
		$paramjs = 'window.location.href =\''.$url.'\'';
	}
	if ($paramjs){
		$paramjs = 'function (){'.$paramjs.'}';
	}else{
		$paramjs = 'null';
	}
	$modes = array('error' => 'alert', 'succ' => 'succ', 'notice' => 'notice','js'=>'js');
	$cover = $alert_type == 'error' ? 1 : 0;
	$extra .= 'showDialog(\''.$message.'\', \''.$modes[$alert_type].'\', null, '.($paramjs ? $paramjs : 'null').', '.$cover.', null, null, null, null, '.(is_numeric($time) ? $time : 'null').', null);';
	$extra = $extra ? '<script type="text/javascript" reload="1">'.$extra.'</script>' : '';
	if ($extrajs != '' && substr(trim($extrajs),0,7) != '<script'){
		$extrajs = '<script type="text/javascript" reload="1">'.$extrajs.'</script>';
	}
	$extra .= $extrajs;
	ob_end_clean();
	@header("Expires: -1");
	@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
	@header("Pragma: no-cache");
	@header("Content-type: text/xml; charset=".CHARSET);

	$string =  '<?xml version="1.0" encoding="'.CHARSET.'"?>'."\r\n";
	$string .= '<root><![CDATA['.$message.$extra.']]></root>';
	echo $string;exit;
}


/**
 * 不显示信息直接跳转
 *
 * @param string $url
 */
function redirect($url = ''){
	if (empty($url)){
		if(!empty($_REQUEST['ref_url'])){
			$url = $_REQUEST['ref_url'];
		}else{
			$url = getReferer();
		}
	}
	header('Location: '.$url);exit();
}

/**
 * 取上一步来源地址
 *
 * @param
 * @return string 字符串类型的返回结果
 */
function getReferer(){
    return str_replace(array('\'','"', '<', '>'), '', $_SERVER['HTTP_REFERER']);
}

/**
 * 取验证码hash值
 *
 * @param
 * @return string 字符串类型的返回结果
 */
function getWthash($w = '', $t = ''){
    $w = $w ? $w : $_GET['w'];
    $t = $t ? $t : $_GET['t'];
    if (C('captcha_status_login')){
        return substr(md5(BASE_SITE_URL.$w.$t),0,8);
    } else {
        return '';
    }
}

/**
 * 加密函数
 *
 * @param string $txt 需要加密的字符串
 * @param string $key 密钥
 * @return string 返回加密结果
 */
function encrypt($txt, $key = ''){
	if (empty($txt)) return $txt;
	if (empty($key)) $key = md5(MD5_KEY);
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
	$ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
	$nh1 = rand(0,64);
	$nh2 = rand(0,64);
	$nh3 = rand(0,64);
	$ch1 = $chars{$nh1};
	$ch2 = $chars{$nh2};
	$ch3 = $chars{$nh3};
	$nhnum = $nh1 + $nh2 + $nh3;
	$knum = 0;$i = 0;
	while(isset($key{$i})) $knum +=ord($key{$i++});
	$mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum%8,$knum%8 + 16);
	$txt = base64_encode(time().'_'.$txt);
	$txt = str_replace(array('+','/','='),array('-','_','.'),$txt);
	$tmp = '';
	$j=0;$k = 0;
	$tlen = strlen($txt);
	$klen = strlen($mdKey);
	for ($i=0; $i<$tlen; $i++) {
		$k = $k == $klen ? 0 : $k;
		$j = ($nhnum+strpos($chars,$txt{$i})+ord($mdKey{$k++}))%64;
		$tmp .= $chars{$j};
	}
	$tmplen = strlen($tmp);
	$tmp = substr_replace($tmp,$ch3,$nh2 % ++$tmplen,0);
	$tmp = substr_replace($tmp,$ch2,$nh1 % ++$tmplen,0);
	$tmp = substr_replace($tmp,$ch1,$knum % ++$tmplen,0);
	return $tmp;
}

/**
 * 解密函数
 *
 * @param string $txt 需要解密的字符串
 * @param string $key 密匙
 * @return string 字符串类型的返回结果
 */
function decrypt($txt, $key = '', $ttl = 0){
	if (empty($txt)) return $txt;
	if (empty($key)) $key = md5(MD5_KEY);

	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
	$ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
	$knum = 0;$i = 0;
	$tlen = @strlen($txt);
	while(isset($key{$i})) $knum +=ord($key{$i++});
	$ch1 = @$txt{$knum % $tlen};
	$nh1 = strpos($chars,$ch1);
	$txt = @substr_replace($txt,'',$knum % $tlen--,1);
	$ch2 = @$txt{$nh1 % $tlen};
	$nh2 = @strpos($chars,$ch2);
	$txt = @substr_replace($txt,'',$nh1 % $tlen--,1);
	$ch3 = @$txt{$nh2 % $tlen};
	$nh3 = @strpos($chars,$ch3);
	$txt = @substr_replace($txt,'',$nh2 % $tlen--,1);
	$nhnum = $nh1 + $nh2 + $nh3;
	$mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum % 8,$knum % 8 + 16);
	$tmp = '';
	$j=0; $k = 0;
	$tlen = @strlen($txt);
	$klen = @strlen($mdKey);
	for ($i=0; $i<$tlen; $i++) {
		$k = $k == $klen ? 0 : $k;
		$j = strpos($chars,$txt{$i})-$nhnum - ord($mdKey{$k++});
		while ($j<0) $j+=64;
		$tmp .= $chars{$j};
	}
	$tmp = str_replace(array('-','_','.'),array('+','/','='),$tmp);
	$tmp = trim(base64_decode($tmp));

	if (preg_match("/\d{10}_/s",substr($tmp,0,11))){
		if ($ttl > 0 && (time() - substr($tmp,0,11) > $ttl)){
			$tmp = null;
		}else{
			$tmp = substr($tmp,11);
		}
	}
	return $tmp;
}

/**
 * 取得IP
 *
 *
 * @return string 字符串类型的返回结果
 */
function getIp(){
	if (@$_SERVER['HTTP_CLIENT_IP'] && $_SERVER['HTTP_CLIENT_IP']!='unknown') {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (@$_SERVER['HTTP_X_FORWARDED_FOR'] && $_SERVER['HTTP_X_FORWARDED_FOR']!='unknown') {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return preg_match('/^\d[\d.]+\d$/', $ip) ? $ip : '';
}

/**
 * 数据库模型实例化入口
 *
 * @param string $model 模型名称
 * @return obj 对象形式的返回结果
 */
function Model($model = null){
    static $_cache = array();
    if (!is_null($model) && isset($_cache[$model])) return $_cache[$model];
    $file_name = BASE_DATA_PATH.'/model/'.$model.'.php';
    $class_name = $model.'Model';
    if (!file_exists($file_name)){
        return $_cache[$model] =  new Model($model);
    }else{
        require_once($file_name);
        if (!class_exists($class_name)){
            $error = 'Model Error:  Class '.$class_name.' is not exists!';
            throw_exception($error);
        }else{
            return $_cache[$model] = new $class_name();
        }
    }
}

/**
 * 行为模型实例
 *
 * @param string $model 模型名称
 * @return obj 对象形式的返回结果
 */
function Handle($model = null, $base_path = null){
    static $_cache = array();
    $cache_key = $model.'.'.$base_path;
    if (!is_null($model) && isset($_cache[$cache_key])) return $_cache[$cache_key];
    $base_path = $base_path == null ? BASE_DATA_PATH : $base_path;
    $file_name = $base_path.'/handle/handle.'.$model.'.php';
    $class_name = $model.'Handle';
    if (!file_exists($file_name)){
        return $_cache[$cache_key] =  new Model($model);
    }else{
        require_once($file_name);
        if (!class_exists($class_name)){
            $error = 'Handle Error:  Class '.$class_name.' is not exists!';
            throw_exception($error);
        }else{
            return $_cache[$cache_key] = new $class_name();
        }
    }
}

/**
 * 读取目录列表
 * 不包括 . .. 文件 三部分
 *
 * @param string $path 路径
 * @return array 数组格式的返回结果
 */
function readDirList($path){
	if (is_dir($path)) {
		$handle = @opendir($path);
		$dir_list = array();
		if ($handle){
			while (false !== ($dir = readdir($handle))){
				if ($dir != '.' && $dir != '..' && is_dir($path.DS.$dir)){
					$dir_list[] = $dir;
				}
			}
			return $dir_list;
		}else {
			return false;
		}
	}else {
		return false;
	}
}

/**
 * 转换特殊字符
 *
 * @param string $string 要转换的字符串
 * @return string 字符串类型的返回结果
 */
function replaceSpecialChar($string){
	$str = str_replace("\r\n", "", $string);
	$str = str_replace("\t", "    ", $string);
	$str = str_replace("\n", "", $string);
	return $string;
}

/**
 * 编辑器内容
 *
 * @param int $id 编辑器id名称，与name同名
 * @param string $value 编辑器内容
 * @param string $width 宽 带px
 * @param string $height 高 带px
 * @param string $style 样式内容
 * @param string $upload_state 上传状态，默认是开启
 */
function showEditor($id, $value='', $width='700px', $height='300px', $style='visibility:hidden;',$upload_state="true", $media_open=false, $type='all'){
	//是否开启多媒体
	$media = '';
	if ($media_open){
		$media = ", 'flash', 'media'";
	}
    switch($type) {
    case 'basic':
        $items = "['source', '|', 'fullscreen', 'undo', 'redo', 'cut', 'copy', 'paste', '|', 'about']";
        break;
    case 'simple':
        $items = "['source', '|', 'fullscreen', 'undo', 'redo', 'cut', 'copy', 'paste', '|',
            'fontname', 'fontsize', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
            'removeformat', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
            'insertunorderedlist', '|', 'emoticons', 'image', 'link', '|', 'about']";
        break;
    default:
        $items = "['source', '|', 'fullscreen', 'undo', 'redo', 'print', 'cut', 'copy', 'paste',
            'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
            'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
            'superscript', '|', 'selectall', 'clearhtml','quickformat','|',
            'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
            'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image'".$media.", 'table', 'hr', 'emoticons', 'link', 'unlink', '|', 'about']";
        break;
    }
	//图片、Flash、视频、文件的本地上传都可开启。默认只有图片，要启用其它的需要修改static\kindeditor\php下的upload_json.php的相关参数
	echo '<textarea id="'. $id .'" name="'. $id .'" style="width:'. $width .';height:'. $height .';'. $style .'">'.$value.'</textarea>';
	echo '
<script src="'. STATIC_SITE_URL .'/kindeditor/kindeditor.js" charset="utf-8"></script>
<script src="'. STATIC_SITE_URL .'/kindeditor/lang/zh_CN.js" charset="utf-8"></script>
<script>
	var KE;
  KindEditor.ready(function(K) {
        KE = K.create("textarea[name=\''.$id.'\']", {
						items : '.$items.',
						cssPath : "' . STATIC_SITE_URL . '/kindeditor/themes/default/default.css",
						allowImageUpload : '.$upload_state.',
						allowFlashUpload : '.$upload_state.',
						allowMediaUpload : '.$upload_state.',
						allowFileManager : false,
						syncType:"form",
						afterCreate : function() {
							var self = this;
							self.sync();
						},
						afterChange : function() {
							var self = this;
							self.sync();
						},
						afterBlur : function() {
							var self = this;
							self.sync();
						}
        });
			KE.appendHtml = function(id,val) {
				this.html(this.html() + val);
				if (this.isCreated) {
					var cmd = this.cmd;
					cmd.range.selectNodeContents(cmd.doc.body).collapse(false);
					cmd.select();
				}
				return this;
			}
	});
</script>
	';
	return true;
}

/**
 * 获取目录大小
 *
 * @param string $path 目录
 * @param int $size 目录大小
 * @return int 整型类型的返回结果
 */
function getDirSize($path, $size=0){
	$dir = @dir($path);
	if (!empty($dir->path) && !empty($dir->handle)){
		while($filename = $dir->read()){
			if($filename != '.' && $filename != '..'){
				if (is_dir($path.DS.$filename)){
					$size += getDirSize($path.DS.$filename);
				}else {
					$size += filesize($path.DS.$filename);
				}
			}
		}
	}
	return $size ? $size : 0 ;
}

/**
 * 删除缓存目录下的文件或子目录文件
 *
 * @param string $dir 目录名或文件名
 * @return boolean
 */
function delCacheFile($dir){
	//防止删除cache以外的文件
	if (strpos($dir,'..') !== false) return false;
	$path = BASE_DATA_PATH.DS.'cache'.DS.$dir;
	if (is_dir($path)){
		$file_list = array();
		readFileList($path,$file_list);
		if (!empty($file_list)){
			foreach ($file_list as $v){
				if (basename($v) != 'index.html')@unlink($v);
			}
		}
	}else{
		if (basename($path) != 'index.html') @unlink($path);
	}
	return true;
}

/**
 * 获取文件列表(所有子目录文件)
 *
 * @param string $path 目录
 * @param array $file_list 存放所有子文件的数组
 * @param array $ignore_dir 需要忽略的目录或文件
 * @return array 数据格式的返回结果
 */
function readFileList($path,&$file_list,$ignore_dir=array()){
	$path = rtrim($path,'/');
	if (is_dir($path)) {
		$handle = @opendir($path);
		if ($handle){
			while (false !== ($dir = readdir($handle))){
				if ($dir != '.' && $dir != '..'){
					if (!in_array($dir,$ignore_dir)){
						if (is_file($path.DS.$dir)){
							$file_list[] = $path.DS.$dir;
						}elseif(is_dir($path.DS.$dir)){
							readFileList($path.DS.$dir,$file_list,$ignore_dir);
						}
					}
				}
			}
			@closedir($handle);
//			return $file_list;
		}else {
			return false;
		}
	}else {
		return false;
	}
}

/**
* 价格格式化
*
* @param int	$price
* @return string	$price_format
*/
function wtPriceFormat($price) {
    return number_format($price,2,'.','');
}

/**
* 价格格式化
*
* @param int	$price
* @return string	$price_format
*/
function wtPriceFormatForList($price) {
    if ($price >= 10000) {
       return number_format(floor($price/100)/100,2,'.','').'万';
    } else {
     return '&yen;'.wtPriceFormat($price);
    }
}

/**
 * 二级域名解析
 * @return int 店铺id
 */
function subdomain(){
	$store_id = 0;
	/**
	 * 获得系统配置,二级域名功能是否开启
	 */
	if (C('enabled_subdomain')=='1'){//开启了二级域名
		$line = @explode(SUBDOMAIN_SUFFIX,$_SERVER['HTTP_HOST']);
		$line = trim($line[0],'.');
		if(empty($line) || strtolower($line) == 'www') return 0;

		$model_store = Model('store');
		$store_info = $model_store->getStoreInfo(array('store_domain'=>$line));
		//二级域名存在
		if ($store_info['store_id'] > 0){
			$store_id = $store_info['store_id'];
			$_GET['store_id'] = $store_info['store_id'];
		}
	}
	return $store_id;
}

/**
 * 通知邮件/通知消息 内容转换函数
 *
 * @param string $message 内容模板
 * @param array $param 内容参数数组
 * @return string 通知内容
 */
function wtReplaceText($message,$param){
	if(!is_array($param))return false;
	foreach ($param as $k=>$v){
		$message	= str_replace('{$'.$k.'}',$v,$message);
	}
	return $message;
}

/**
 * 字符串切割函数，一个字母算一个位置,一个字算2个位置
 *
 * @param string $string 待切割的字符串
 * @param int $length 切割长度
 * @param string $dot 尾缀
 */
function str_cut($string, $length, $dot = '')
{
	$string = str_replace(array('&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array(' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
	$strlen = strlen($string);
	if($strlen <= $length) return $string;
	$maxi = $length - strlen($dot);
	$strcut = '';
	if(strtolower(CHARSET) == 'utf-8')
	{
		$n = $tn = $noc = 0;
		while($n < $strlen)
		{
			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t < 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}
			if($noc >= $maxi) break;
		}
		if($noc > $maxi) $n -= $tn;
		$strcut = substr($string, 0, $n);
	}
	else
	{
		$dotlen = strlen($dot);
		$maxi = $length - $dotlen;
		for($i = 0; $i < $maxi; $i++)
		{
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}
	$strcut = str_replace(array('&', '"', "'", '<', '>'), array('&amp;', '&quot;', '&#039;', '&lt;', '&gt;'), $strcut);
	return $strcut.$dot;
}

/**
 * unicode转为utf8
 * @param string $str 待转的字符串
 * @return string
 */
function unicodeToUtf8($str, $order = "little")
{
	$utf8string ="";
	$n=strlen($str);
	for ($i=0;$i<$n ;$i++ )
	{
		if ($order=="little")
		{
			$val = str_pad(dechex(ord($str[$i+1])), 2, 0, STR_PAD_LEFT) .
			str_pad(dechex(ord($str[$i])),      2, 0, STR_PAD_LEFT);
		}
		else
		{
			$val = str_pad(dechex(ord($str[$i])),      2, 0, STR_PAD_LEFT) .
			str_pad(dechex(ord($str[$i+1])), 2, 0, STR_PAD_LEFT);
		}
		$val = intval($val,16); // 由于上次的.连接，导致$val变为字符串，这里得转回来。
		$i++; // 两个字节表示一个unicode字符。
		$c = "";
		if($val < 0x7F)
		{ // 0000-007F
			$c .= chr($val);
		}
		elseif($val < 0x800)
		{ // 0080-07F0
			$c .= chr(0xC0 | ($val / 64));
			$c .= chr(0x80 | ($val % 64));
		}
		else
		{ // 0800-FFFF
			$c .= chr(0xE0 | (($val / 64) / 64));
			$c .= chr(0x80 | (($val / 64) % 64));
			$c .= chr(0x80 | ($val % 64));
		}
		$utf8string .= $c;
	}
	/* 去除bom标记 才能使内置的iconv函数正确转换 */
	if (ord(substr($utf8string,0,1)) == 0xEF && ord(substr($utf8string,1,2)) == 0xBB && ord(substr($utf8string,2,1)) == 0xBF)
	{
		$utf8string = substr($utf8string,3);
	}
	return $utf8string;
}

/*
 * 重写$_SERVER['REQUREST_URI']
 */
function request_uri()
{
    if (isset($_SERVER['REQUEST_URI']))
    {
        $uri = $_SERVER['REQUEST_URI'];
    }
    else
    {
        if (isset($_SERVER['argv']))
        {
            $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
        }
        else
        {
            $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
        }
    }
    $uri = explode('/', $uri);
    $uri = end($uri);
    return APP_SITE_URL .'/'. $uri;
}

/*
 * 自定义memory_get_usage()
 *
 * @return 内存使用额度，如果该方法无效，返回0
 */
if(!function_exists('memory_get_usage')){
	function memory_get_usage(){//目前程序不兼容5以下的版本
		return 0;
	}
}

// 记录和统计时间（微秒）
function addUpTime($start,$end='',$dec=3) {
    static $_info = array();
    if(!empty($end)) { // 统计时间
        if(!isset($_info[$end])) {
            $_info[$end]   =  microtime(TRUE);
        }
        return number_format(($_info[$end]-$_info[$start]),$dec);
    }else{ // 记录时间
        $_info[$start]  =  microtime(TRUE);
    }
}

/**
 * 取得系统配置信息
 *
 * @param string $key 取得下标值
 * @return mixed
 */
function C($key){
        if (strpos($key,'.')){
                $key = explode('.',$key);
                if (isset($key[2])){
                        return $GLOBALS['setting_config'][$key[0]][$key[1]][$key[2]];
                }else{
                        return $GLOBALS['setting_config'][$key[0]][$key[1]];
                }
        }else{
                return $GLOBALS['setting_config'][$key];
        }
}

/**
 * 取得商品默认大小图片
 *
 * @param string $key	图片大小 small tiny
 * @return string
 */
function defaultGoodsImage($key){
    $file = str_ireplace('.', '_' . $key . '.', C('default_goods_image'));
	return ATTACH_COMMON.DS.$file;
}

/**
 * 取得用户头像图片
 *
 * @param string $member_avatar
 * @return string
 */
function getMemberAvatar($member_avatar){
    if (empty($member_avatar)) {
        return UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('default_user_portrait');
    } else {
       if (file_exists(BASE_UPLOAD_PATH.DS.ATTACH_AVATAR.DS.$member_avatar)){
            return UPLOAD_SITE_URL.DS.ATTACH_AVATAR.DS.$member_avatar;
       } else {
           return UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('default_user_portrait');
       }

    }
}
/**
 * 取得用户头像图片
 *
 * @param string $member_avatar
 * @return string
 */
function getMemberAvatarHttps($member_avatar){
    if (empty($member_avatar)) {
        return UPLOAD_SITE_URL_HTTPS.DS.ATTACH_COMMON.DS.C('default_user_portrait');
    } else {
       if (file_exists(BASE_UPLOAD_PATH.DS.ATTACH_AVATAR.DS.$member_avatar)){
            return UPLOAD_SITE_URL_HTTPS.DS.ATTACH_AVATAR.DS.$member_avatar;
       } else {
           return UPLOAD_SITE_URL_HTTPS.DS.ATTACH_COMMON.DS.C('default_user_portrait');
       }

    }
}
/**
 * 成员头像
  * @param string $member_id
 * @return string
 */
function getMemberAvatarForID($id){
	if(file_exists(BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR.'/avatar_'.$id.'.jpg')){
		return UPLOAD_SITE_URL.'/'.ATTACH_AVATAR.'/avatar_'.$id.'.jpg';
	}else{
		return UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_user_portrait');
	}
}
/**
 * 取得店铺标志
 *
 * @param string $img 图片名
 * @param string $type 查询类型 store_logo/store_avatar
 * @return string
 */
function getStoreLogo($img, $type = 'store_avatar'){
    if ($type == 'store_avatar') {
        if (empty($img)) {
            return UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('default_store_avatar');
        } else {
            return UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.$img;
        }
    }elseif ($type == 'store_logo') {
        if (empty($img)) {
            return UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('default_store_logo');
        } else {
            return UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.$img;
        }
    }
}

/**
 * 获取文章URL
 */
function getNEWSArticleUrl($article_id) {
    if(URL_MODEL) {
        // 开启伪静态
        return NEWS_SITE_URL.DS.'article-'.$article_id.'.html';
    } else {
        return NEWS_SITE_URL.DS.'index.php?w=article&t=article_detail&article_id='.$article_id;
    }
}

/**
 * 获取图刊URL
 */
function getNEWSPictureUrl($picture_id) {
    if(URL_MODEL) {
        // 开启伪静态
        return NEWS_SITE_URL.DS.'picture-'.$picture_id.'.html';
    } else {
        return NEWS_SITE_URL.DS.'index.php?w=picture&t=picture_detail&picture_id='.$picture_id;
    }
}

/**
 * 获取文章图片URL
 */
function getNEWSArticleImageUrl($image_path, $image_name, $type='list') {
    if(empty($image_name)) {
        return UPLOAD_SITE_URL.DS.ATTACH_NEWS.DS.'no_cover.png';
    } else {
        $image_array = unserialize($image_name);
        if(!empty($image_array['name'])) {
            $image_name = $image_array['name'];
        }
        if(!empty($image_array['path'])) {
            $image_path = $image_array['path'];
        }
        $ext_array = array('list','max');
        $file_path = ATTACH_NEWS.DS.'article'.DS.$image_path.DS.str_ireplace('.', '_'.$type.'.', $image_name);
        if(file_exists(BASE_UPLOAD_PATH.DS.$file_path)) {
            $image_name = UPLOAD_SITE_URL.DS.$file_path;
        } else {
            $image_name = UPLOAD_SITE_URL.DS.ATTACH_NEWS.DS.'no_cover.png';
        }
        return $image_name;
    }
}

/**
 * 获取文章图片URL
 */
function getNEWSImageName($image_name_string) {
    $image_array = unserialize($image_name_string);
    if(!empty($image_array['name'])) {
        $image_name = $image_array['name'];
    } else {
        $image_name = $image_name_string;
    }
    return $image_name;
}

/**
 * 获取NEWS专题图片
 */
function getNEWSSpecialImageUrl($image_name='') {
    return UPLOAD_SITE_URL.DS.ATTACH_NEWS.DS.'special'.DS.$image_name;
}

/**
 * 获取NEWS专题路径
 */
function getNEWSSpecialImagePath($image_name='') {
    return BASE_UPLOAD_PATH.DS.ATTACH_NEWS.DS.'special'.DS.$image_name;
}

/**
 * 获取NEWS首页图片
 */
function getNEWSIndexImageUrl($image_name='') {
    return UPLOAD_SITE_URL.DS.ATTACH_NEWS.DS.'index'.DS.$image_name;
}

/**
 * 获取NEWS首页图片路径
 */
function getNEWSIndexImagePath($image_name='') {
    return BASE_UPLOAD_PATH.DS.ATTACH_NEWS.DS.'index'.DS.$image_name;
}

/**
 * 获取NEWS专题Url
 */
function getNEWSSpecialUrl($special_id) {
    return NEWS_SITE_URL.DS.'index.php?w=special&t=special_detail&special_id='.$special_id;
}

/**
 * 获取商城专题Url
 */
function getShopSpecialUrl($special_id) {
    return BASE_SITE_URL.DS.'index.php?w=special&t=special_detail&special_id='.$special_id;
}


/**
 * 获取NEWS专题静态文件
 */
function getNEWSSpecialHtml($special_id) {
    $special_file = file_get_contents(UPLOAD_SITE_URL.DS.ATTACH_NEWS.DS.'special_html'.DS.md5('special'.intval($special_id)).'.html');
    return $special_file;
}

/**
 * 获取买什么买心得图片地址
 */
function getwhatPersonalImageUrl($personal_info,$type=''){
    $ext_array = array('list','tiny');
    $personal_image_array = array();
    $personal_image_list = explode(',',$personal_info['commend_image']);
    if(!empty($personal_image_list)){
        foreach ($personal_image_list as $value) {
            if(!empty($type) && in_array($type,$ext_array)) {
                $file_name = str_replace('.', '_'.$type.'.', $value);
            } else {
                $file_name = $value;
            }
            $file_path = $personal_info['commend_member_id'].DS.$file_name;
            if(is_file(BASE_UPLOAD_PATH.DS.ATTACH_WHAT.DS.$file_path)) {
                $personal_image_array[] = UPLOAD_SITE_URL.DS.ATTACH_WHAT.DS.$file_path;
            } else {
                $personal_image_array[] = getwhatDefaultImage();
            }
        }
    } else {
        $personal_image_array[] = getwhatDefaultImage();
    }
    return $personal_image_array;

}

function getwhatDefaultImage() {
    return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
}

/**
 * 获取开店申请图片
 */
function getStoreJoininImageUrl($image_name='') {
    return UPLOAD_SITE_URL.DS.ATTACH_STORE_JOININ.DS.$image_name;
}

/**
 * 获取开店装修图片地址
 */
function getStoreDecorationImageUrl($image_name = '', $store_id = null) {
    if(empty($store_id)) {
        $image_name_array = explode('_', $image_name);
        $store_id = $image_name_array[0];
    }

    $image_path = DS . ATTACH_STORE_DECORATION . DS . $store_id . DS . $image_name;
    if(is_file(BASE_UPLOAD_PATH . $image_path)) {
        return UPLOAD_SITE_URL . $image_path;
    } else {
        return '';
    }
}

/**
 * 获取运单图片地址
 */
function getWaybillImageUrl($image_name = '') {
    $image_path = DS . ATTACH_WAYBILL . DS . $image_name;
    if(is_file(BASE_UPLOAD_PATH . $image_path)) {
        return UPLOAD_SITE_URL . $image_path;
    } else {
        return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
    }
}

/**
 * 获取运单图片地址
 */
function getMbSpecialImageUrl($image_name = '') {
    $name_array = explode('_', $image_name);
    if(count($name_array) == 2) {
        $image_path = DS . ATTACH_MOBILE . DS . 'special' . DS . $name_array[0] . DS . $image_name;
    } else {
        $image_path = DS . ATTACH_MOBILE . DS . 'special' . DS . $image_name;
    }
    if(is_file(BASE_UPLOAD_PATH . $image_path)) {
        return UPLOAD_SITE_URL . $image_path;
    } else {
        return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
    }
}

/**
 * 加载文件
 *
 * 使用require_once函数，只适用于加载框架内类库文件
 * 如果文件名中包含"_"使用"#"代替
 *
 * @example import('cache'); //require_once(BASE_PATH.'/library/bin/cache.php');
 * @example import('bin.cache');	//require_once(BASE_PATH.'/library/bin/cache.php');
 * @example import('function.core');	//require_once(BASE_PATH.'/library/function/core.php');
 * @example import('.control.show')	//require_once(BASE_PATH.'/control/shopwt.php');
 *
 * @param 要加载的文件 $libname
 * @param 文件扩展名 $file_ext
 */
function import($libname,$file_ext='.php'){
	//替换为目录符号/
	if (strstr($libname,'.')){
		$path = str_replace('.','/',$libname);
	}else{
		$path = 'bin/'.$libname;
	}
	// 基准目录，如果是顶级目录
	if(substr($libname,0,1) == '.'){
		$base_dir = BASE_DATA_PATH.'/';
		$path = ltrim(str_replace('bin/','',$path),'/');
	}else{
		$base_dir = BASE_DATA_PATH.'/library/';
	}
	//如果文件名中含有.使用#代替
	if (strstr($path,'#')){
		$path = str_replace('#','.',$path);
	}
	//返回安全路径
	if(preg_match('/^[\w\d\/_.]+$/i', $path)){
		$file = realpath($base_dir.$path.$file_ext);
	}else{
		$file = false;
	}
	if (!$file){
		exit($path.$file_ext.' isn\'t exists!');
	}else{
		require_once($file);
	}

}

/**
 * 取得随机数
 *
 * @param int $length 生成随机数的长度
 * @param int $numeric 是否只产生数字随机数 1是0否
 * @return string
 */
function random($length, $numeric = 0) {
	$seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
	$hash = '';
	$max = strlen($seed) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $seed{mt_rand(0, $max)};
	}
	return $hash;
}

/**
 * 返回模板文件所在完整目录
 *
 * @param str $tplpath
 * @return string
 */
function template($tplpath){
    if (strpos($tplpath,':') !== false){
        $tpltmp = explode(':',$tplpath);
        return BASE_DATA_PATH.'/'.$tpltmp[0].'/tpl/'.$tpltmp[1].'.php';
    }else{
        if (defined('MODULE_NAME')) {
            return MODULES_BASE_PATH.'/templets/'.TPL_NAME.'/'.$tplpath.'.php';
        } else {
            return BASE_PATH.'/templets/'.TPL_NAME.'/'.$tplpath.'.php';
        }
    }
}

/**
 * 检测FORM是否提交
 * @param  $check_token 是否验证token
 * @param  $check_captcha 是否验证验证码
 * @param  $return_type 'alert','num'
 * @return boolean
 */
function chksubmit($check_token = false, $check_captcha = false, $return_type = 'alert'){
	$submit = isset($_POST['form_submit']) ? $_POST['form_submit'] : $_GET['form_submit'];
	if ($submit != 'ok') return false;
	if ($check_token && !Security::checkToken()){
		if ($return_type == 'alert'){
			showDialog('Token error!');
		}else{
			return -11;
		}
	}
	if ($check_captcha){
		if (!checkVercode($_POST['wthash'],$_POST['captcha'])){
		    setWtCookie('verifycode','',-3600);
			if ($return_type == 'alert'){
				showDialog('验证码错误!');
			}else{
				return -12;
			}
		}
		setWtCookie('verifycode','',-3600);
	}
	return true;
}

/**
 * sns表情标示符替换为html
 */
function parsesmiles($message) {
	$smilescache_file = BASE_DATA_PATH.DS.'smilies'.DS.'smilies.php';
	if (file_exists($smilescache_file)){
		include $smilescache_file;
		if (strtoupper(CHARSET) == 'GBK') {
			$smilies_array = Language::getGBK($smilies_array);
		}
		if(!empty($smilies_array) && is_array($smilies_array)) {
			$imagesurl = STATIC_SITE_URL.DS.'js'.DS.'smilies'.DS.'images'.DS;
			$replace_arr = array();
			foreach($smilies_array['replacearray'] AS $key => $smiley) {
				$replace_arr[$key] = '<img src="'.$imagesurl.$smiley['imagename'].'" title="'.$smiley['desc'].'" border="0" alt="'.$imagesurl.$smiley['desc'].'" />';
			}

			$message = preg_replace($smilies_array['searcharray'], $replace_arr, $message);
		}
	}
	return $message;
}

/**
 * 输出validate的验证信息
 *
 * @param array/string $error
 */
function showValidateError($error){
	if (!empty($_GET['inajax'])){
		foreach (explode('<br/>',$error) as $v) {
			if (trim($v != '')){
				showDialog($v,'','error','',3);
			}
		}
	}else{
		showDialog($error,'','error','',3);
	}
}

/**
 * 延时加载分页功能，判断是否有更多连接和limitstart值和经过验证修改的$delay_eachnum值
 * @param int $delay_eachnum 延时分页每页显示的条数
 * @param int $delay_page 延时分页当前页数
 * @param int $count 总记录数
 * @param bool $ispage 是否在分页模式中实现延时分页(前台显示的两种不同效果)
 * @param int $page_nowpage 分页当前页数
 * @param int $page_eachnum 分页每页显示条数
 * @param int $page_limitstart 分页初始limit值
 * @return array array('hasmore'=>'是否显示更多连接','limitstart'=>'加载的limit开始值','delay_eachnum'=>'经过验证修改的$delay_eachnum值');
 */
function lazypage($delay_eachnum,$delay_page,$count,$ispage=false,$page_nowpage=1,$page_eachnum=1,$page_limitstart=1){
	//是否有多余
	$hasmore = true;
	$limitstart = 0;
	if ($ispage == true){
		if ($delay_eachnum < $page_eachnum){//当延时加载每页条数小于分页的每页条数时候实现延时加载，否则按照普通分页程序流程处理
			$page_totlepage = ceil($count/$page_eachnum);
			//计算limit的开始值
			$limitstart = $page_limitstart + ($delay_page-1)*$delay_eachnum;
			if ($page_totlepage > $page_nowpage){//当前不为最后一页
				if ($delay_page >= $page_eachnum/$delay_eachnum){
					$hasmore = false;
				}
				//判断如果分页的每页条数与延时加载每页的条数不能整除的处理
				if ($hasmore == false && $page_eachnum%$delay_eachnum >0){
					$delay_eachnum = $page_eachnum%$delay_eachnum;
				}
			}else {//当前最后一页
				$showcount = ($page_totlepage-1)*$page_eachnum+$delay_eachnum*$delay_page;//已经显示的记录总数
				if ($count <= $showcount){
					$hasmore = false;
				}
			}
		}else {
			$hasmore = false;
		}
	}else {
		if ($count <= $delay_page*$delay_eachnum){
			$hasmore = false;
		}
		//计算limit的开始值
		$limitstart = ($delay_page-1)*$delay_eachnum;
	}

	return array('hasmore'=>$hasmore,'limitstart'=>$limitstart,'delay_eachnum'=>$delay_eachnum);
}

/**
 * 文件数据读取和保存 字符串、数组
 *
 * @param string $name 文件名称（不含扩展名）
 * @param mixed $value 待写入文件的内容
 * @param string $path 写入cache的目录
 * @param string $ext 文件扩展名
 * @return mixed
 */
function F($name, $value = null, $path = 'cache', $ext = '.php') {
	if (strtolower(substr($path,0,5)) == 'cache'){
		$path  = 'system/'.$path;
	}
	static $_cache = array();
	if (isset($_cache[$name.$path])) return $_cache[$name.$path];
    $filename = BASE_ROOT_PATH.'/'.$path.'/'.$name.$ext;
    if (!is_null($value)) {
        $dir = dirname($filename);
        if (!is_dir($dir)) mkdir($dir);
        return write_file($filename,$value);
    }

    if (is_file($filename)) {
        $_cache[$name.$path] = $value = include $filename;
    } else {
        $value = false;
    }
    return $value;
}

/**
 * 内容写入文件
 *
 * @param string $filepath 待写入内容的文件路径
 * @param string/array $data 待写入的内容
 * @param  string $mode 写入模式，如果是追加，可传入“append”
 * @return bool
 */
function write_file($filepath, $data, $mode = null)
{
    if (!is_array($data) && !is_scalar($data)) {
        return false;
    }

    $data = var_export($data, true);

    $data = "<?php defined('ShopWT') or exit('Access Denied By ShopWT'); return ".$data.";";
    $mode = $mode == 'append' ? FILE_APPEND : null;
    if (false === file_put_contents($filepath,($data),$mode)){
        return false;
    }else{
        return true;
    }
}

/**
 * 循环创建目录
 *
 * @param string $dir 待创建的目录
 * @param  $mode 权限
 * @return boolean
 */
function mk_dir($dir, $mode = '0777') {
    if (is_dir($dir) || @mkdir($dir, $mode))
        return true;
    if (!mk_dir(dirname($dir), $mode))
        return false;
    return @mkdir($dir, $mode);
}

/**
 * 封装分页操作到函数，方便调用
 *
 * @param string $cmd 命令类型
 * @param mixed $arg 参数
 * @return mixed
 */
function pagecmd($cmd ='', $arg = ''){
	if (!class_exists('page'))	import('page');
	static $page;
	if ($page == null){
		$page = new Page();
	}

    switch (strtolower($cmd)) {
        case 'seteachnum':      $page->setEachNum($arg);break;
        case 'settotalnum':     $page->setTotalNum($arg);break;
        case 'setstyle':        $page->setStyle($arg);break;
        case 'show':            return $page->show($arg);break;
        case 'obj':             return $page;break;
        case 'gettotalnum':     return $page->getTotalNum();break;
        case 'gettotalpage':    return $page->getTotalPage();break;
        case 'getnowpage':      return $page->getNowPage();break;
        case 'settotalpagebynum': return $page->setTotalPageByNum($arg);break;
        default:                break;
    }
}

/**
 * 抛出异常
 *
 * @param string $error 异常信息
 */
function throw_exception($error){
	if (!defined('IGNORE_EXCEPTION')){
		showMessage($error, '', 'exception');
	}else{
		exit();
	}
}

/**
 * 输出错误信息
 *
 * @param string $error 错误信息
 */
function halt($error){
	showMessage($error,'','exception');
}

/**
 * 去除代码中的空白和注释
 *
 * @param string $content 待压缩的内容
 * @return string
 */
	function compress_code($content) {
    $stripStr = '';
    //分析php源码
    $tokens = token_get_all($content);
    $last_space = false;
    for ($i = 0, $j = count($tokens); $i < $j; $i++) {
        if (is_string($tokens[$i])) {
            $last_space = false;
            $stripStr .= $tokens[$i];
        } else {
            switch ($tokens[$i][0]) {
                case T_COMMENT:	//过滤各种PHP注释
                case T_DOC_COMMENT:
                    break;
                case T_WHITESPACE:	//过滤空格
                    if (!$last_space) {
                        $stripStr .= ' ';
                        $last_space = true;
                    }
                    break;
                default:
                    $last_space = false;
                    $stripStr .= $tokens[$i][1];
            }
        }
    }
    return $stripStr;
}

/**
 * 取得对象实例
 *
 * @param object $class
 * @param string $method
 * @param array $args
 * @return object
 */
function get_obj_instance($class, $method='', $args = array()){
	static $_cache = array();
	$key = $class.$method.(empty($args) ? null : md5(serialize($args)));
	if (isset($_cache[$key])){
		return $_cache[$key];
	}else{
		if (class_exists($class)){
			$obj = new $class;
			if (method_exists($obj,$method)){
				if (empty($args)){
					$_cache[$key] = $obj->$method();
				}else{
					$_cache[$key] = call_user_func_array(array(&$obj, $method), $args);
				}
			}else{
				$_cache[$key] = $obj;
			}
			return $_cache[$key];
		}else{
			throw_exception('Class '.$class.' isn\'t exists!');
		}
	}
}

/**
 * 返回以原数组某个值为下标的新数据
 *
 * @param array $array
 * @param string $key
 * @param int $type 1一维数组2二维数组
 * @return array
 */
function array_under_reset($array, $key, $type=1){
	if (is_array($array)){
		$tmp = array();
		foreach ($array as $v) {
			if ($type === 1){
				$tmp[$v[$key]] = $v;
			}elseif($type === 2){
				$tmp[$v[$key]][] = $v;
			}
		}
		return $tmp;
	}else{
		return $array;
	}
}

/**
 * KV缓存 读
 *
 * @param string $key 缓存名称
 * @param boolean $callback 缓存读取失败时是否使用回调 true代表使用cache.model中预定义的缓存项 默认不使用回调
 * @param callable $callback 传递非boolean值时 通过is_callable进行判断 失败抛出异常 成功则将$key作为参数进行回调
 * @return mixed
 */
function rkcache($key, $callback = false)
{
    if (C('cache_open')) {
        $cacher = Cache::getInstance('cacheredis');
    } else {
        $cacher = Cache::getInstance('file', null);
    }
    if (!$cacher) {
        throw new Exception('Cannot fetch cache object!');
    }

    $value = $cacher->get($key);

    if ($value === false && $callback !== false) {
        if ($callback === true) {
            $callback = array(Model('cache'), 'call');
        }

        if (!is_callable($callback)) {
            throw new Exception('Invalid rkcache callback!');
        }

        $value = call_user_func($callback, $key);
        wkcache($key, $value);
    }

    return $value;
}

/**
 * KV缓存 写
 *
 * @param string $key 缓存名称
 * @param mixed $value 缓存数据 若设为否 则下次读取该缓存时会触发回调（如果有）
 * @param int $expire 缓存时间 单位秒 null代表不过期
 * @return boolean
 */
function wkcache($key, $value, $expire = null)
{
    if (C('cache_open')) {
        $cacher = Cache::getInstance('cacheredis');
    } else {
        $cacher = Cache::getInstance('file', null);
    }
    if (!$cacher) {
        throw new Exception('Cannot fetch cache object!');
    }

    return $cacher->set($key, $value, null, $expire);
}
/**
 * KV缓存 删
 *
 * @param string $key 缓存名称
 * @return boolean
 */
function dkcache($key)
{
    if (C('cache_open')) {
        $cacher = Cache::getInstance('cacheredis');
    } else {
        $cacher = Cache::getInstance('file', null);
    }
    if (!$cacher) {
        throw new Exception('Cannot fetch cache object!');
    }

    return $cacher->rm($key);
}


/**
 * 读取缓存信息
 *
 * @param string $key 要取得缓存键
 * @param string $prefix 键值前缀
 * @param string $fields 所需要的字段
 * @return array/bool
 */
function rcache($key = null, $prefix = '', $fields = '*'){
    if ($key===null || !C('cache_open')) return array();
    $ins = Cache::getInstance('cacheredis');
    $cache_info = $ins->hget($key,$prefix,$fields);
    if ($cache_info === false) {
        //取单个字段且未被缓存
        $data  = array();
    } elseif (is_array($cache_info)) {
        //如果有一个键值为false(即未缓存)，则整个函数返回空，让系统重新生成全部缓存
        $data = $cache_info;
        foreach ($cache_info as $k => $v) {
            if ($v === false) {
                $data = array();break;
            }
        }
    } else {
        //string 取单个字段且被缓存
        $data = array($fields => $cache_info);
    }
    // 验证缓存是否过期
    if (isset($data['cache_expiration_time']) && $data['cache_expiration_time'] < TIMESTAMP) {
        $data = array();
    }
    return $data;
}

/**
 * 写入缓存
 *
 * @param string $key 缓存键值
 * @param array $data 缓存数据
 * @param string $prefix 键值前缀
 * @param int $period 缓存周期  单位分，0为永久缓存
 * @return bool 返回值
 */
function wcache($key = null, $data = array(), $prefix, $period = 0){
    if ($key===null || !C('cache_open') || !is_array($data)) return;
    if (!in_array($prefix,array('show','all_categories','area','channel','class_tag','contractitem','gc_class','goods_class_seo','index/article','nav','own_shop_ids','seo','setting','setting_updates','member','member_chain'))) {
        return true;
    }
    $period = intval($period);
    if ($period != 0) {
        $data['cache_expiration_time'] = TIMESTAMP + $period * 60;
    }
    $ins = Cache::getInstance('cacheredis');
    $ins->hset($key, $prefix, $data);
    $cache_info = $ins->hget($key,$prefix);
    return true;
}

/**
 * 删除缓存
 * @param string $key 缓存键值
 * @param string $prefix 键值前缀
 * @return boolean
 */
function dcache($key = null, $prefix = ''){
    if ($key===null || !C('cache_open')) return true;
    if (!in_array($prefix,array('show','all_categories','area','channel','class_tag','contractitem','gc_class','goods_class_seo','index/article','nav','own_shop_ids','seo','setting','setting_updates','member','member_chain'))) {
        return true;
    }
    $ins = Cache::getInstance('cacheredis');
    return $ins->hdel($key, $prefix);
}

/**
 * 调用推荐位
 *
 * @param int $rec_id 推荐位ID
 * @return string 推荐位内容
 */
function rec($rec_id = null){
	import('function.rec_position');
	return rec_position($rec_id);
}

/**
 * 快速调用语言包
 *
 * @param string $key
 * @return string
 */
function L($key = ''){
	if (class_exists('Language')){
		if (strpos($key,',') !== false){
			$tmp = explode(',',$key);
			$str = Language::get($tmp[0]).Language::get($tmp[1]);
			return isset($tmp[2])? $str.Language::get($tmp[2]) : $str;
		}else{
			return Language::get($key);
		}
	}else{
		return null;
	}
}

/**
 * 加载完成业务方法的文件
 *
 * @param string $filename
 * @param string $file_ext
 */
function loadfunc($filename, $file_ext = '.php'){
	if(preg_match('/^[\w\d\/_.]+$/i', $filename.$file_ext)){
		$file = realpath(BASE_PATH.'/library/function/'.$filename.$file_ext);
	}else{
		$file = false;
	}
	if (!$file){
		exit($filename.$file_ext.' isn\'t exists!');
	}else{
		require_once($file);
	}
}

/**
 * 商城加载完成业务方法的文件
 *
 * @param string $filename
 * @param string $file_ext
 */
function shopload($filename, $file_ext = '.php'){
	if(preg_match('/^[\w\d\/_.]+$/i', $filename.$file_ext)){
		$file = realpath(BASE_PATH.'/system/library/function/'.$filename.$file_ext);
	}else{
		$file = false;
	}
	if (!$file){
		exit($filename.$file_ext.' isn\'t exists!');
	}else{
		require_once($file);
	}
}

/**
 * 实例化类
 *
 * @param string $model_name 模型名称
 * @return obj 对象形式的返回结果
 */
function wt_class($classname = null){
	static $_cache = array();
	if (!is_null($classname) && isset($_cache[$classname])) return $_cache[$classname];
	$file_name = BASE_PATH.'/library/bin/'.$classname.'.class.php';
	$newname = $classname.'Class';
	if (file_exists($file_name)){
		require_once($file_name);
		if (class_exists($newname)){
			return $_cache[$classname] = new $newname();
		}
	}
	throw_exception('Class Error:  Class '.$classname.' is not exists!');
}

/**
 * 加载广告
 *
 * @param  $ap_id 广告位ID
 * @param $type 广告返回类型 html,js
 */
function loadshow($ap_id = null, $type = 'html'){
	if (!is_numeric($ap_id)) return false;
	if (!function_exists('show')) import('function.show');
	return show($ap_id,$type);
}

/**
 * 格式化ubb标签
 *
 * @param string $theme_content/$reply_content 话题内容/回复内容
 * @return string
 */
function ubb($ubb){
	$ubb = str_replace(array(
			'[B]', '[/B]', '[I]', '[/I]', '[U]', '[/U]', '[IMG]', '[/IMG]', '[/FONT]', '[/FONT-SIZE]', '[/FONT-COLOR]'
	), array(
			'<b>', '</b>', '<i>', '</i>', '<u>', '</u>', '<img class="pic" src="', '"/>', '</span>', '</span>', '</span>'
	), preg_replace(array(
			"/\[URL=(.*)\](.*)\[\/URL\]/iU",
			"/\[FONT=([A-Za-z ]*)\]/iU",
			"/\[FONT-SIZE=([0-9]*)\]/iU",
			"/\[FONT-COLOR=([A-Za-z0-9]*)\]/iU",
			"/\[SMILIER=([A-Za-z_]*)\/\]/iU",
			"/\[FLASH\](.*)\[\/FLASH\]/iU",
			"/\\n/i"
	), array(
			"<a href=\"$1\" target=\"_blank\">$2</a>",
			"<span style=\"font-family:$1\">",
			"<span style=\"font-size:$1px\">",
			"<span style=\"color:#$1\">",
			"<img src=\"".BBS_SITE_URL.'/templets/'.TPL_BBS_NAME."/images/smilier/$1.png\">",
			"<embed src=\"$1\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" wmode=\"opaque\" width=\"480\" height=\"400\"></embed>",
			"<br />"
	), $ubb));
	return $ubb;
}
/**
 * 去掉ubb标签
 *
 * @param string $theme_content/$reply_content 话题内容/回复内容
 * @return string
 */
function removeUBBTag($ubb){
	$ubb = str_replace(array(
			'[B]', '[/B]', '[I]', '[/I]', '[U]', '[/U]', '[/FONT]', '[/FONT-SIZE]', '[/FONT-COLOR]'
	), array(
			'', '', '', '', '', '', '', '', ''
	), preg_replace(array(
			"/\[URL=(.*)\](.*)\[\/URL\]/iU",
			"/\[FONT=([A-Za-z ]*)\]/iU",
			"/\[FONT-SIZE=([0-9]*)\]/iU",
			"/\[FONT-COLOR=([A-Za-z0-9]*)\]/iU",
			"/\[SMILIER=([A-Za-z_]*)\/\]/iU",
			"/\[IMG\](.*)\[\/IMG\]/iU",
			"/\[FLASH\](.*)\[\/FLASH\]/iU",
			"<img class='pi' src=\"$1\"/>",
	), array(
			"$2",
			"",
			"",
			"",
			"",
			"",
			"",
			""
	), $ubb));
	return $ubb;
}

/**
 * 话题图片绝对路径
 *
 * @param $param string 文件名称
 * @return string
 */
function themeImagePath($param){
	return BASE_UPLOAD_PATH.'/'.ATTACH_BBS.'/theme/'.$param;
}
/**
 * 话题图片url
 *
 * @param $param string
 * @return string
 */
function themeImageUrl($param){
	return UPLOAD_SITE_URL.'/'.ATTACH_BBS.'/theme/'.$param;
}
/**
 * 社区logo
 *
 * @param $param string 社区id
 * @return string
 */
function bbsLogo($id){
	if(file_exists(BASE_UPLOAD_PATH.'/'.ATTACH_BBS.'/group/'.$id.'.jpg')){
		return UPLOAD_SITE_URL.'/'.ATTACH_BBS.'/group/'.$id.'.jpg';
	}else{
		return UPLOAD_SITE_URL.'/'.ATTACH_BBS.'/shopwt_group_logo.png';
	}
}
/**
 * sns 来自
 * @param $param string $trace_from
 * @return string
 */
function snsShareFrom($sign) {
    switch ($sign) {
        case '1' :
        case '2' :
            return L('sns_from') . '<a target="_black" href="' . BASE_SITE_URL . '">' . L('sns_shop') . '</a>';
            break;
        case '3' :
            return L('sns_from') . '<a target="_black" href="' . WHAT_SITE_URL . '">' . L('wt_modules_what') . '</a>';
            break;
        case '4' :
            return L('sns_from') . '<a target="_black" href="' . NEWS_SITE_URL . '">NEWS</a>';
            break;
        case '5' :
            return L('sns_from') . '<a target="_black" href="' . BBS_SITE_URL . '">' . L('wt_bbs') . '</a>';
            break;
    }
}

/**
 * 输出聊天信息
 *
 * @return string
 */
function getChat($layout){
	if (!C('node_chat') || !file_exists(BASE_DATA_PATH.'/library/bin/chat.php')) return '';
	if (!class_exists('Chat')) import('bin.chat');
	return Chat::getChatHtml($layout);
}

/**
 * 拼接动态URL，参数需要小写
 *
 * 调用示例
 *
 * 若指向网站首页，可以传空:
 * url() => 表示act和op均为index，返回当前站点网址
 *
 * url('search,'index','array('cate_id'=>2)); 实际指向 index.php?w=search&t=index&cate_id=2
 * 传递数组参数时，若act（或op）值为index,则可以省略
 * 上面示例等同于
 * url('search','',array('w'=>'search','cate_id'=>2));
 *
 * @param string $w control文件名
 * @param string $t op方法名
 * @param array $args URL其它参数
 * @param boolean $model 默认取当前系统配置
 * @param string $site_url 生成链接的网址，默认取当前网址
 * @return string
 */
function url($w = '', $t = '', $args = array(), $model = false, $site_url = ''){
    //伪静态文件扩展名
    $ext = '.html';
    //入口文件名
    $file = 'index.php';
//    $site_url = empty($site_url) ? BASE_SITE_URL : $site_url;
    $w = trim($w);
    $t = trim($t);
    $args = !is_array($args) ? array() : $args;
    //定义变量存放返回url
    $url_string = '';
    if (empty($w) && empty($t) && empty($args)) {
        return $site_url;
    }
    $w = !empty($w) ? $w : 'index';
    $t = !empty($t) ? $t : 'index';

    $model = $model ? URL_MODEL : $model;

    if ($model) {
        //伪静态模式
        $url_perfix = "{$w}-{$t}";
        if (!empty($args)){
            $url_perfix .= '-';
        }
        $url_string = $url_perfix.http_build_query($args,'','-').$ext;
        $url_string = str_replace('=','-',$url_string);
    }else {
        //默认路由模式
        $url_perfix = "w={$w}&t={$t}";
        if (!empty($args)){
            $url_perfix .= '&';
        }
        $url_string = $file.'?'.$url_perfix.http_build_query($args);
    }
    //将商品、店铺、分类、品牌、文章自动生成的伪静态URL使用短URL代替
    $reg_match_from = array(
	    '/^special-special_list\.html$/',
		'/^special-special_detail-special_id-(\d+)\.html$/',
        '/^category-index\.html$/',
        '/^channel-index-id-(\d+)\.html$/',
        '/^goods-index-goods_id-(\d+)\.html$/',
        '/^show_store-index-store_id-(\d+)\.html$/',
        '/^show_store-goods_all-store_id-(\d+)-stc_id-(\d+)-key-([0-5])-order-([0-2])-curpage-(\d+)\.html$/',
        '/^article-show-article_id-(\d+)\.html$/',
        '/^article-article_list-class_id-(\d+)\.html$/',
        '/^article-article-ac_id-(\d+)\.html$/',
        '/^document-index-code-([a-z_]+)\.html$/',
        '/^search-index-cate_id-(\d+)-b_id-([0-9_]+)-a_id-([0-9_]+)-ci-([0-9_]+)-key-([0-3])-order-([0-2])-type-([0-1])-gift-([0-1])-area_id-(\d+)-curpage-(\d+)\.html$/',
        '/^brand-list-brand-(\d+)-ci-([0-9_]+)-key-([0-3])-order-([0-2])-type-([0-1])-gift-([0-1])-area_id-(\d+)-curpage-(\d+)\.html$/',
        '/^brand-index\.html$/',
        '/^sale-index\.html$/',
        '/^sale-index-gc_id-(\d+)\.html$/',

        '/^robbuy-index\.html$/',
        '/^robbuy-robbuy_detail-group_id-(\d+)\.html$/',

        '/^robbuy-robbuy_list-class-(\d+)-s_class-(\d+)-robbuy_price-(\d+)-robbuy_order_key-(\d+)-robbuy_order-(\d+)-curpage-(\d+)\.html$/',
        '/^robbuy-robbuy_soon-class-(\d+)-s_class-(\d+)-robbuy_price-(\d+)-robbuy_order_key-(\d+)-robbuy_order-(\d+)-curpage-(\d+)\.html$/',
        '/^robbuy-robbuy_history-class-(\d+)-s_class-(\d+)-robbuy_price-(\d+)-robbuy_order_key-(\d+)-robbuy_order-(\d+)-curpage-(\d+)\.html$/',

        '/^robbuy-vr_robbuy_list-vr_class-(\d+)-vr_s_class-(\d+)-vr_area-(\d+)-vr_mall-(\d+)-robbuy_price-(\d+)-robbuy_order_key-(\d+)-robbuy_order-(\d+)-curpage-(\d+)\.html$/',
        '/^robbuy-vr_robbuy_soon-vr_class-(\d+)-vr_s_class-(\d+)-vr_area-(\d+)-vr_mall-(\d+)-robbuy_price-(\d+)-robbuy_order_key-(\d+)-robbuy_order-(\d+)-curpage-(\d+)\.html$/',
        '/^robbuy-vr_robbuy_history-vr_class-(\d+)-vr_s_class-(\d+)-vr_area-(\d+)-vr_mall-(\d+)-robbuy_price-(\d+)-robbuy_order_key-(\d+)-robbuy_order-(\d+)-curpage-(\d+)\.html$/',

        '/^points-index.html$/',
        '/^pointprod-pinfo-id-(\d+).html$/',
        '/^pointvoucher-index.html$/',
        '/^pointgrade-index.html$/',
        '/^pointgrade-exppointlog-curpage-(\d+).html$/',
        '/^goods-comments_list-goods_id-(\d+)-type-([0-4])-curpage-(\d+).html$/'
        );
    $reg_match_to = array(
	    'special.html',
		'special-\\1.html',
        'category.html',
        'channel-\\1.html',
        'goods-\\1.html',
        'shop-\\1.html',
        'shop-view-\\1-\\2-\\3-\\4-\\5.html',
		'help-\\1.html',
        'help-list-\\1.html',
        'help-cate-\\1.html',
        'document-\\1.html',
        'list-\\1-\\2-\\3-\\4-\\5-\\6-\\7-\\8-\\9-\\10.html',
        'brand-\\1-\\2-\\3-\\4-\\5-\\6-\\7-\\8.html',
        'brand.html',
        'sale.html',
        'sale-\\1.html',

        'robbuy.html',
        'robbuy-view-\\1.html',

        'robbuy-list-\\1-\\2-\\3-\\4-\\5-\\6.html',
        'robbuy-ready-\\1-\\2-\\3-\\4-\\5-\\6.html',
        'robbuy-past-\\1-\\2-\\3-\\4-\\5-\\6.html',

        'robbuy-vr-list-\\1-\\2-\\3-\\4-\\5-\\6-\\7-\\8.html',
        'robbuy-vr-ready-\\1-\\2-\\3-\\4-\\5-\\6-\\7-\\8.html',
        'robbuy-vr-past-\\1-\\2-\\3-\\4-\\5-\\6-\\7-\\8.html',

        'points.html',
        'integral_item-\\1.html',
        'voucher.html',
        'grade.html',
        'explog-\\1.html',
        'comments-\\1-\\2-\\3.html'
    );
    $url_string = preg_replace($reg_match_from,$reg_match_to,$url_string);
    return rtrim($site_url,'/').'/'.$url_string;
}

/**
 * 商城会员中心使用的URL链接函数，强制使用动态传参数模式
 *
 * @param string $w control文件名
 * @param string $t t方法名
 * @param array $args URL其它参数
 * @param string $store_domian 店铺二级域名
 * @return string
 */
function urlShop($w = '', $t = '', $args = array(), $store_domain = ''){

    // 如果是自营店则返回javascript:;
    /*
    if ($w == 'show_store' && $t != 'goods_all') {
        static $ownShopIds = null;
        if ($ownShopIds === null) {
            $ownShopIds = Model('store')->getOwnShopIds();
        }
        if (isset($args['store_id']) && in_array($args['store_id'], $ownShopIds)) {
            return 'javascript:;';
        }
    }
    */

    // 开启店铺二级域名
    if (intval(C('enabled_subdomain')) == 1 && !empty($store_domain)){
        if(C('https')){
			return 'https://'.$store_domain.'.'.SUBDOMAIN_SUFFIX.'/';
		}else{
			return 'http://'.$store_domain.'.'.SUBDOMAIN_SUFFIX.'/';
		}
    }
    if ($w == 'search' && $t == 'index' && $args['cate_id'] > 0 && empty($args['keyword'])) {//商品搜索列表页只有商品分类参数
        $id = intval($args['cate_id']);
        $channel_list  = rkcache('channel',true);
        if ($channel_list[$id] > 0) {//商品分类与频道的绑定
            $w = 'channel';
            $args = array();
            $args['id'] = $channel_list[$id];
        }
    }

    // 默认标志为不开启伪静态
    $rewrite_flag = false;

    // 如果平台开启伪静态开关，并且为伪静态模块，修改标志为开启伪静态
    $rewrite_item = array(

    	'store:index',
	    'special:special_list',
		'special:special_detail',
        'category:index',
        'channel:index',
        'goods:index',
        'goods:comments_list',
        'search:index',
        'show_store:index',
        'show_store:goods_all',
        'article:show',
        'article:article',
        'document:index',
        'brand:list',
        'brand:index',
        'sale:index',
		'sale:list',
        'robbuy:index',
        'robbuy:robbuy_detail',
        'robbuy:robbuy_list',
        'robbuy:robbuy_soon',
        'robbuy:robbuy_history',
        'robbuy:vr_robbuy_list',
        'robbuy:vr_robbuy_soon',
        'robbuy:vr_robbuy_history',
        'points:index',
        'pointvoucher:index',
        'pointprod:pinfo',
        'pointprod:plist',
        'pointgrade:index',
        'pointgrade:exppointlog',
        'store_snshome:index',
    );
    if(URL_MODEL && in_array($w.':'.$t, $rewrite_item)) {
        $rewrite_flag = true;
        $tpl_args = array();        // url参数临时数组
        switch ($w.':'.$t) {
            case 'search:index':
                if (!empty($args['keyword'])) {
                    $rewrite_flag = false;
                    break;
                }
                $tpl_args['cate_id'] = empty($args['cate_id']) ? 0 : $args['cate_id'];
                $tpl_args['b_id'] = empty($args['b_id']) || intval($args['b_id']) == 0 ? 0 : $args['b_id'];
                $tpl_args['a_id'] = empty($args['a_id']) || intval($args['a_id']) == 0 ? 0 : $args['a_id'];
                $tpl_args['ci'] = empty($args['ci']) || intval($args['ci']) == 0 ? 0 : $args['ci'];
                $tpl_args['key'] = empty($args['key']) ? 0 : $args['key'];
                $tpl_args['order'] = empty($args['order']) ? 0 : $args['order'];
                $tpl_args['type'] = empty($args['type']) ? 0 : $args['type'];
                $tpl_args['gift'] = empty($args['gift']) ? 0 : $args['gift'];
                $tpl_args['area_id'] = empty($args['area_id']) ? 0 : $args['area_id'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;
            case 'show_store:goods_all':
                if (isset($args['inkeyword'])) {
                    $rewrite_flag = false;
                    break;
                }
                $tpl_args['store_id'] = empty($args['store_id']) ? 0 : $args['store_id'];
                $tpl_args['stc_id'] = empty($args['stc_id']) ? 0 : $args['stc_id'];
                $tpl_args['key'] = empty($args['key']) ? 0 : $args['key'];
                $tpl_args['order'] = empty($args['order']) ? 0 : $args['order'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;
            case 'brand:list':
                $tpl_args['brand'] = empty($args['brand']) ? 0 : $args['brand'];
                $tpl_args['ci'] = empty($args['ci']) || intval($args['ci']) == 0 ? 0 : $args['ci'];
                $tpl_args['key'] = empty($args['key']) ? 0 : $args['key'];
                $tpl_args['order'] = empty($args['order']) ? 0 : $args['order'];
                $tpl_args['type'] = empty($args['type']) ? 0 : $args['type'];
                $tpl_args['gift'] = empty($args['gift']) ? 0 : $args['gift'];
                $tpl_args['area_id'] = empty($args['area_id']) ? 0 : $args['area_id'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;

            case 'robbuy:index':
            case 'robbuy:robbuy_detail':
                break;

            case 'robbuy:robbuy_list':
            case 'robbuy:robbuy_soon':
            case 'robbuy:robbuy_history':
                $tpl_args['class'] = empty($args['class']) ? 0 : $args['class'];
                $tpl_args['s_class'] = empty($args['s_class']) ? 0 : $args['s_class'];
                $tpl_args['robbuy_price'] = empty($args['robbuy_price']) ? 0 : $args['robbuy_price'];
                $tpl_args['robbuy_order_key'] = empty($args['robbuy_order_key']) ? 0 : $args['robbuy_order_key'];
                $tpl_args['robbuy_order'] = empty($args['robbuy_order']) ? 0 : $args['robbuy_order'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;

            case 'robbuy:vr_robbuy_list':
            case 'robbuy:vr_robbuy_soon':
            case 'robbuy:vr_robbuy_history':
                $tpl_args['vr_class'] = empty($args['vr_class']) ? 0 : $args['vr_class'];
                $tpl_args['vr_s_class'] = empty($args['vr_s_class']) ? 0 : $args['vr_s_class'];
                $tpl_args['vr_area'] = empty($args['vr_area']) ? 0 : $args['vr_area'];
                $tpl_args['vr_mall'] = empty($args['vr_mall']) ? 0 : $args['vr_mall'];
                $tpl_args['robbuy_price'] = empty($args['robbuy_price']) ? 0 : $args['robbuy_price'];
                $tpl_args['robbuy_order_key'] = empty($args['robbuy_order_key']) ? 0 : $args['robbuy_order_key'];
                $tpl_args['robbuy_order'] = empty($args['robbuy_order']) ? 0 : $args['robbuy_order'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;

            case 'goods:comments_list':
                $tpl_args['goods_id'] = empty($args['goods_id']) ? 0 : $args['goods_id'];
                $tpl_args['type'] = empty($args['type']) ? 0 : $args['type'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;

            case 'pointgrade:exppointlog':
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;
            case 'sale:index':
                $args = empty($args['gc_id']) ? null : $args;
                break;
            default:
                break;
        }
    }

    return url($w, $t, $args, $rewrite_flag, BASE_SITE_URL);
}

/**
 * 商城后台使用的URL链接函数，强制使用动态传参数模式
 *
 * @param string $w control文件名
 * @param string $t op方法名
 * @param array $args URL其它参数
 * @return string
 */
function urlAdmin($w = '', $t = '', $args = array()){
    return url($w, $t, $args, false, ADMIN_SITE_URL);
}
function urlAdminShop($w = '', $t = '', $args = array()){
    return url($w, $t, $args, false, ADMIN_SITE_URL.DS.ADMIN_MODULES_SHOP);
}
function urlAdminCms($w = '', $t = '', $args = array()){
    return url($w, $t, $args, false, ADMIN_SITE_URL.DS.ADMIN_MODULES_NEWS);
}
function urlAdminFlea($w = '', $t = '', $args = array()){
    return url($w, $t, $args, false, ADMIN_SITE_URL.DS.ADMIN_MODULES_FLEA);
}
function urlAdminMobile($w = '', $t = '', $args = array()){
    return url($w, $t, $args, false, ADMIN_SITE_URL.DS.ADMIN_MODULES_MOBILE);
}
function urlAdminbbs($w = '', $t = '', $args = array()){
    return url($w, $t, $args, false, ADMIN_SITE_URL.DS.ADMIN_MODULES_BBS);
}
function urlAdminwhat($w = '', $t = '', $args = array()){
    return url($w, $t, $args, false, ADMIN_SITE_URL.DS.ADMIN_MODULES_WHAT);
}
function urlAdminfenxiao($w = '', $t = '', $args = array()){
    return url($w, $t, $args, false, ADMIN_SITE_URL.DS.ADMIN_MODULES_FENXIAO);
}
/**
 * NEWS使用的URL链接函数，强制使用动态传参数模式
 *
 * @param string $w control文件名
 * @param string $t 方法名
 * @param array $args URL其它参数
 * @return string
 */
function urlnews($w = '', $t = '', $args = array()){
    return url($w, $t, $args, false, NEWS_SITE_URL);
}
/**
 * 社区使用的URL链接函数，强制使用动态传参数模式
 *
 * @param string $w control文件名
 * @param string $t 方法名
 * @param array $args URL其它参数
 * @return string
 */
function urlbbs($w = '', $t = '', $args = array()){
    return url($w, $t, $args, false, BBS_SITE_URL);
}
/**
 * 买什么使用的URL链接函数，强制使用动态传参数模式
 *
 * @param string $w control文件名
 * @param string $t op方法名
 * @param array $args URL其它参数
 * @return string
 */
function urlwhat($w = '', $t = '', $args = array()){
    return url($w, $t, $args, false, WHAT_SITE_URL);
}
/**
 * 会员中心使用的URL链接函数，强制使用动态传参数模式
 * 
 * @param string $w control文件名
 * @param string $t op方法名
 * @param unknown $args URL其它参数
 * @return string
 */
function urlMember($w = '', $t = '', $args = array()) {
    // 默认标志为不开启伪静态
    $rewrite_flag = false;
    
    // 如果平台开启伪静态开关，并且为伪静态模块，修改标志为开启伪静态
    $rewrite_item = array(
            'article:show',
            'article:article'
    );
    if(URL_MODEL && in_array($w.':'.$t, $rewrite_item)) {
        $rewrite_flag = true;
    }
    return url($w, $t, $args, $rewrite_flag, MEMBER_SITE_URL);
}
/**
 * 会员登录使用的URL链接函数，强制使用动态传参数模式
 * @param string $w control文件名
 * @param string $t op方法名
 * @param unknown $args URL其它参数
 * @return string
 */
function urlLogin($w = '', $t = '', $args = array()) {
    return url($w, $t, $args, false, LOGIN_SITE_URL);
}
/**
 * 门店使用的URL链接函数，强制使用动态传参数模式
 * @param string $w control文件名
 * @param string $t op方法名
 * @param unknown $args URL其它参数
 * @return string
 */
function urlChain($w = '', $t = '', $args = array()){
    return url($w, $t, $args, false, CHAIN_SITE_URL);
}
/**
 * 分销使用的URL链接函数，强制使用动态传参数模式
 * @param string $w control文件名
 * @param string $t t方法名
 * @param unknown $args URL其它参数
 * @return string
 */
function urlFenxiao($w = '', $t = '', $args = array()){
    return url($w, $t, $args, false, FENXIAO_SITE_URL);
}
/**
 * 商家手机版url链接函数，强制使用动态传参数模式
 * @param string $w control文件名
 * @param string $t t方法名
 * @param unknown $args URL其它参数
 * @return string
 */
function urlMobile($w = '', $t = '', $args = array()){
    return url($w, $t, $args, false, MSELLER_SITE_URL);
}
/**
 * 验证是否为平台店铺
 *
 * @return boolean
 */
function checkPlatformStore($store_id = 0){
    if (isset($_SESSION['is_own_shop'])) {
        return $_SESSION['is_own_shop'];
    } else {
        $own_shop_ids = Model('store')->getOwnShopIds(true);
        return in_array($store_id, $own_shop_ids);
    }
}

/**
 * 验证是否为平台店铺 并且绑定了全部商品类目
 *
 * @return boolean
 */
function checkPlatformStoreBindingAllGoodsClass($store_id = 0, $bind_all_gc = 0){
    if (isset($_SESSION['is_own_shop'])) {
        return checkPlatformStore() && $_SESSION['bind_all_gc'];
    } else {
        return $store_id && $bind_all_gc;
    }
}

/**
 * 将字符部分加密并输出
 * @param unknown $str
 * @param unknown $start 从第几个位置开始加密(从1开始)
 * @param unknown $length 连续加密多少位
 * @return string
 */
function encryptShow($str,$start,$length) {
    $end = $start - 1 + $length;
    $array = str_split($str);
    foreach ($array as $k => $v) {
    	if ($k >= $start-1 && $k < $end) {
    	    $array[$k] = '*';
    	}
    }
    return implode('',$array);
}

/**
 * 规范数据返回函数
 * @param unknown $state
 * @param unknown $msg
 * @param unknown $data
 * @return multitype:unknown
 */
function callback($state = true, $msg = '', $data = array()) {
    return array('state' => $state, 'msg' => $msg, 'data' => $data);
}

/**
 * flexigrid.js返回的数组
 * @param array $in_array 需要进行赋值的数据（提供给页面中JS使用）
 * @param array $fields_array 赋值下标的数组
 * @param array $data 从数据库读出的未处理数据
 * @param array $format_array 格式化价格下标的数组
 * @return array 处理后的数据
 */
function getFlexigridArray($in_array,$fields_array,$data,$format_array = array()) {
    $out_array = $in_array;
    if (empty($out_array['operation'])) {
        $out_array['operation'] = '--';
    }
    if (!empty($fields_array) && is_array($fields_array)) {
        foreach ($fields_array as $key => $value) {
            $k = '';
            if (is_int($key)) {
                $k = $value;
            } else {
                $k = $key;
            }
            if (is_array($data) && array_key_exists($k, $data)) {
                $out_array[$k] = $data[$k];
                if (!empty($format_array) && in_array($k,$format_array)) {
                    $out_array[$k] = wtPriceFormat($data[$k]);
                }
            } else {
                $out_array[$k] = '--';
            }
        }
    }
    return $out_array;
}

/**
 * flexigrid.js返回的数组列表
 * @param array $list 从数据库读出的未处理列表
 * @param array $fields_array 赋值下标的数组
 * @param array $format_array 格式化价格下标的数组
 * @return array 处理后的数据
 */
function getFlexigridList($list,$fields_array,$format_array = array()) {
    $out_list = array();
    if (!empty($list) && is_array($list)) {
        foreach ($list as $key => $value) {
            $out_list[] = getFlexigridArray(array(),$fields_array,$value,$format_array);
        }
    }
    return $out_list;
}

/**
 * 会员标签图片
 * @param unknown $img
 * @return string
 */
function getMemberTagimage($img) {
    return UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/membertag/'.($img != ''?$img:'default_tag.gif');
}

/**
 * 门店图片
 * @param string $image
 * @param int $store_id
 * @return string
 */
function getChainImage($image, $store_id) {
    return UPLOAD_SITE_URL.DS.ATTACH_CHAIN.DS.$store_id.DS.$image;
}

/**
 * 分销提现方式
 * @param string $payment_code
 * @return string
 */
function getFenxiaoBillName($payment_code) {
    return str_replace(
        array('bank','alipay'),
        array('银行账号','支付宝'),
        $payment_code);
}

/**
 * 获取唯一码
 * @param $key
 * @return string
 */
function getUniqueCode($key){
    static $guid = '';
    $uid = uniqid("", true);
    $data = $key;
    $data .= $_SERVER['REQUEST_TIME'];
    $data .= $_SERVER['HTTP_USER_AGENT'];
    $data .= $_SERVER['LOCAL_ADDR'];
    $data .= $_SERVER['LOCAL_PORT'];
    $data .= $_SERVER['REMOTE_ADDR'];
    $data .= $_SERVER['REMOTE_PORT'];
    $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
    $guid = substr($hash,  0,  8) .
        '-' .
        substr($hash,  8,  4) .
        '-' .
        substr($hash, 12,  4) .
        '-' .
        substr($hash, 16,  4) .
        '-' .
        substr($hash, 20, 12);
    return $guid;
}

/**
 * @param $Key  七牛云应用标识(AK)
 * @param $secretKey 七牛云应用密钥(SK)
 * @param $bucket 七牛云应用存储空间(BN)
 * @param $files 上传文件内容
 * @return string
 * @throws Exception
 */
function sevenCattleCloud($Key,$secretKey,$bucket,$files){
    $auth = new Auth($Key, $secretKey);  //实例化
    $token = $auth->uploadToken($bucket);
    $uploadMgr = new UploadManager();

    $filePath = $files['file']['tmp_name'];//'./php-logo.png';  //接收图片信息
    if($files['file']['type']=='video/mp4'){
        $key = 'video'.time().'.mp4';
    }elseif($files['file']['type']=='audio/mp3'){
        $key = 'audio'.time().'.mp3';
    }else{
        $key = 'png'.time().'.png';
    }
    list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
    if ($err !== null) {
       return  ['status'=>-1,"msg"=>$err,"data"=>""];
    } else {
        return ['status'=>1,'msg'=>"上传成功",'data'=>$ret];
    }
}
