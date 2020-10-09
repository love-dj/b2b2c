<?php
/**
 * 微信公众号
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class materialControl extends SystemControl{

    public function __construct(){
        parent::__construct();
        Language::read('wechat');
    }

    public function indexWt() {
		$this->material_manageWt();
	}
    /**
     * 素材管理
     **/
    public function material_manageWt() {
		$model_wechat = Model('wechat');
        $links = array(
			array('url'=>'w=material&t=material_manage','lang'=>'material_all'),
			array('url'=>'w=material&t=material_edit','lang'=>'material_add')
		);
		
		$condition = array();
        if (!empty($_GET['material_type'])) {
            $condition['material_type'] = intval($_GET['material_type']);
        }else{
			$_GET['material_type'] = '';
		}
        $result = $model_wechat->getInfoList('weixin_material',$condition,8,'material_addtime desc');
		$material_list = array();
		if(!empty($result)){
			foreach($result as $key=>$value){
				$value['material_content'] = unserialize($value['material_content']);
				$material_list[] = $value;
			}
		}
		
		Tpl::output('material_list',$material_list);
		Tpl::output('page',$model_wechat->showpage('2'));
		Tpl::output('top_link',$this->sublink($links,'material_manage'));
				
		Tpl::setDirquna('wechat');
		Tpl::showpage('wechat_material');
    }
	/**
     * 素材编辑
     **/
    public function material_editWt() {
		$lang   = Language::getLangContent();
		$model_wechat = Model('wechat');
		if(chksubmit()){
			if(empty($_POST['ImgPath'])){
				showMessage('参数错误！');
			}
			if(!empty($_POST['Url'][$key])){
					$_POST['Url'][$key] = (strpos($_POST['Url'][$key],'http://')>-1 || strpos($_POST['Url'][$key],'https://')>-1) ? trim($_POST['Url'][$key]) : 'http://'.trim($_POST['Url'][$key]);
			}
			$submit_content = array();
			foreach($_POST['ImgPath'] as $key=>$value){
				$_POST['TextContents'][$key] = str_replace('\"','',$_POST['TextContents'][$key]);
				$_POST['TextContents'][$key] = str_replace("\'","",$_POST['TextContents'][$key]);
				if(empty($value)){
					continue;
				}
				$submit_content[] = array(
					'ImgPath'=>str_replace(UPLOAD_SITE_URL,'',trim($value)),
					'Title'=>trim($_POST['Title'][$key]),
					'Url'=>trim($_POST['Url'][$key]),
					'TextContents'=>trim($_POST['TextContents'][$key])
				);
			}
			
			if(empty($submit_content)){
				showMessage("{$lang['material_not_null']}");
			}
			$update_array = array();
			$update_array['material_type'] = count($submit_content)==1 ? 1 : 2;
			$update_array['material_content'] = serialize($submit_content);
			
			if(!empty($_POST['mid'])){
				$condition = array('material_id'=>intval($_POST['mid']));
				$result = $model_wechat->editInfo('weixin_material',$update_array,$condition);
			}else{
				$update_array['material_addtime'] = time();
				$result = $model_wechat->addInfo('weixin_material',$update_array);
			}
			
			if ($result){
				showMessage('保存成功','index.php?w='.$_GET['w'].'&t=material_manage');
			}else {
				showMessage('保存失败','index.php?w='.$_GET['w'].'&t=material_manage');
			}
		}else{
			if(!empty($_GET['mid'])){
				$material_info = $model_wechat->getInfoOne('weixin_material',array('material_id'=>intval($_GET['mid'])));
				if (empty($material_info)){
					showMessage($lang['info_not_exist']);
				}
				
				$material_info['items'] = unserialize($material_info['material_content']);
			}else{
				$material_info = array();
				$material_info['material_addtime'] = time();
			}
			
			Tpl::output('material',$material_info);
			Tpl::setDirquna('wechat');
			Tpl::showpage('wechat_material_edit');
		}
    }
	
	public function material_delWt(){
		$lang   = Language::getLangContent();
		if(empty($_GET['mid'])){
			showMessage('参数错误！','index.php?w='.$_GET['w'].'&t=material_manage');
		}
		
		if (intval($_GET['mid']) > 0){
			$model_wechat = Model('wechat');
			$condition = array('material_id'=>intval($_GET['mid']));
			$material_info = $model_wechat->getInfoOne('weixin_material',$condition);
			if(empty($material_info)){
				showMessage($lang['info_not_exist'],'index.php?w='.$_GET['w'].'&t=material_manage');
			}
			
			$result = $model_wechat->delInfo('weixin_material',$condition);
			
			//delete images
			$material_info['items'] = unserialize($material_info['material_content']);
			foreach($material_info['items'] as $key=>$value){
				@unlink(BASE_UPLOAD_PATH.$value['ImgPath']);
			}	
			showMessage('删除成功','index.php?w='.$_GET['w'].'&t=material_manage');
		}else {
			showMessage('删除失败','index.php?w='.$_GET['w'].'&t=material_manage');
		}
	}
		
	/*
	弹框获取素材列表
	*/
	public function material_listWt() {
		$model_wechat = Model('wechat');
        
		$condition = array();
        if (!empty($_GET['type'])) {
            $condition['material_type'] = intval($_GET['type']);
        }else{
			$_GET['type'] = '';
		}
        $result = $model_wechat->getInfoList('weixin_material',$condition,8,'material_addtime desc');
		$material_list = array();
		if(!empty($result)){
			foreach($result as $key=>$value){
				$value['material_content'] = unserialize($value['material_content']);
				$material_list[] = $value;
			}
		}
		
		Tpl::output('material_list',$material_list);
		Tpl::output('show_page',$model_wechat->showpage('2'));
		Tpl::setDirquna('wechat');
		Tpl::showpage('wechat_material_dialog','null_layout');
    }
	
	
}
