<?php
/**
 * 微信公众号
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class urlControl extends SystemControl{

    public function __construct(){
        parent::__construct();
        Language::read('wechat');
    }

    public function indexWt() {
		$this->url_manageWt();
	}
	/*URL列表*/
	public function url_manageWt(){
		$model_wechat = Model('wechat');
		$condition = array();
        if(!empty($_GET['classid'])) {
            $condition['url_classid'] = intval($_GET['classid']);
        }else{
			$_GET['classid'] = '';
		}
		
		if (!empty($_GET['keywords'])) {
            $condition['url_'.$_GET['fields']] = array('like', '%' . trim($_GET['keywords']) . '%');
        }else{
			$_GET['keywords'] = '';
		}
		
        $url_list = $model_wechat->getInfoList('weixin_url',$condition,10,'url_addtime desc');
		$url_class = $model_wechat->getInfoList('weixin_url_class',array());
		Tpl::output('url_class',$url_class);
		Tpl::output('url_list',$url_list);
		Tpl::output('show_page',$model_wechat->showpage('2'));
		Tpl::setDirquna('wechat');
		Tpl::showpage('wechat_url_list');
	}
	
	/**
     * URL添加
     **/
    public function url_addWt() {
        $model_wechat = Model('wechat');
		if (chksubmit()){
			if(empty($_POST['name'])){
				showMessage($lang['not_info_url_name']);
			}
			if(empty($_POST['urllink'])){
				showMessage($lang['not_info_url_link']);
			}
			$_POST['urllink'] = (strpos($_POST['urllink'],'http://')>0 || strpos($_POST['urllink'],'https://')>0)? trim($_POST['urllink']) : 'http://'.trim($_POST['urllink']);
			$update_array = array(
				'url_name'=>trim($_POST['name']),
				'admin_id'=>0,
				'url_link'=>$_POST['urllink'],
				'url_classid'=>1,
				'url_addtime'=>time()
			);
			
			$result = $model_wechat->addInfo('weixin_url',$update_array);
			if ($result){
				showMessage('操作成功！','index.php?w='.$_GET['w'].'&t=url_manage');
			}else {
				showMessage('操作失败！');
			}
		}else{
			Tpl::setDirquna('wechat');
			Tpl::showpage('wechat_url_add');
		}
    }
	
	/**
     * URL修改
     **/
    public function url_editWt() {
        $model_wechat = Model('wechat');
		if (chksubmit()){
			if(empty($_POST['urlid'])){
				showMessage('参数错误！','index.php?w='.$_GET['w'].'&tp=url_manage');
			}
			
			if(empty($_POST['name'])){
				showMessage($lang['not_info_url_name']);
			}
			
			if(empty($_POST['urllink'])){
				showMessage($lang['not_info_url_link']);
			}
			$_POST['urllink'] = (strpos($_POST['urllink'],'http://')>-1 || strpos($_POST['urllink'],'https://')>-1)? trim($_POST['urllink']) : 'http://'.trim($_POST['urllink']);
			$update_array = array(
				'url_name'=>trim($_POST['name']),
				'url_link'=>$_POST['urllink']
			);
			
			$condition = array('url_id'=>intval($_POST['urlid']));
			
			$result = $model_wechat->editInfo('weixin_url',$update_array,$condition);
			if ($result === true){
				showMessage('操作成功！','index.php?w='.$_GET['w'].'&t=url_manage');
			}else {
				showMessage('操作失败！','index.php?w='.$_GET['w'].'&t=url_manage');
			}
		}else{
			$url_info = array();
			if(empty($_GET['urlid'])){
				showMessage('参数错误！','index.php?w='.$_GET['w'].'&t=url_manage');
			}
			$url_info = $model_wechat->getInfoOne('weixin_url',array('url_id'=>intval($_GET['urlid'])));
			if(empty($url_info)){
				showMessage('记录不存在','index.php?w='.$_GET['w'].'&t=url_manage');
			}
			
			Tpl::output('url_info',$url_info);
			Tpl::setDirquna('wechat');
			Tpl::showpage('wechat_url_edit');
		}
    }
	
	/**
     * URL删除
     **/
    public function url_delWt() {
        $model_wechat = Model('wechat');
		
		if(empty($_GET['urlid']) && empty($_POST['urlid'])){
			showMessage('参数错误！','index.php?w='.$_GET['w'].'&t=url_manage');
		}
		
		$result = false;
		if(!empty($_GET['urlid'])){
			$result = $model_wechat->delInfo('weixin_url',array('url_id'=>intval($_GET['urlid'])));
		}
		
		if(!empty($_POST['urlid'])){
			$where['url_id'] = array('in', $_POST['urlid']);
			$result = $model_wechat->delInfo('weixin_url',$where);
		}
		
		if($result){
			showMessage('删除成功！','index.php?w='.$_GET['w'].'&t=url_manage');
		}else{
			showMessage('删除失败！','index.php?w='.$_GET['w'].'&t=url_manage');
		}
    }
	

}
