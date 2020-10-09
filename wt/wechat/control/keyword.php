<?php
/**
 * 微信公众号
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class keywordControl extends SystemControl{

    public function __construct(){
        parent::__construct();
        Language::read('wechat');
    }

    public function indexWt() {
		$this->keyword_manageWt();
	}
	
	/*关键词列表*/
	public function keyword_manageWt(){
		$model_wechat = Model('wechat');
        
		$condition = array();
        if (!empty($_GET['type'])) {
            $condition['reply_msgtype'] = intval($_GET['type'])-1;
        }else{
			$_GET['type'] = 0;
		}
		if (!empty($_GET['keywords'])) {
            $condition['reply_keywords'] = array('like', '%' . trim($_GET['keywords']) . '%');
        }else{
			$_GET['keywords'] = '';
		}
        $reply_list = $model_wechat->getInfoList('weixin_reply',$condition,10,'reply_addtime desc');
		
		Tpl::output('reply_list',$reply_list);
		Tpl::output('show_page',$model_wechat->showpage('2'));
		Tpl::setDirquna('wechat');
		Tpl::showpage('wechat_keyword_list');
	}
	
	/**
     * 关键词添加
     **/
    public function keyword_addWt() {
        $model_wechat = Model('wechat');
		if (chksubmit()){
			
			if(!empty($_POST['keywords'])){
				$_POST['keywords'] = trim($_POST['keywords'],'|');
				$_POST['keywords'] = str_replace('||','|',$_POST['keywords']);
			}
			
			if(empty($_POST['keywords'])){
				showMessage('关键词不能为空');
			}
			
			$array = explode('|',$_POST['keywords']);
			foreach($array as $a){
				if(trim($a)=='') continue;
				$condition['reply_keywords'] = array('like', '%|' . trim($a) . '|%');
				$reply_info = $model_wechat->getInfoOne('weixin_reply',$condition);
				if(!empty($reply_info)){
					showMessage('关键词不存在：'.$a);
				}
			}
			
			$update_array = array(
				'reply_keywords'=>intval($_POST['patternmethod']) == 0 ? trim($_POST['keywords']) : '|'.trim($_POST['keywords'],'|').'|',
				'admin_id'=>0,
				'reply_patternmethod'=>intval($_POST['patternmethod']),
				'reply_msgtype'=>intval($_POST['msgtype']),
				'reply_textcontents'=>trim($_POST['textcontents']),
				'reply_materialid'=>intval($_POST['materialid']),
				'reply_addtime'=>time()
			);
			
			$result = $model_wechat->addInfo('weixin_reply',$update_array);
			if ($result){
				showMessage('保存成功！','index.php?w='.$_GET['w'].'&t=keyword_manage');
			}else {
				showMessage('保存失败！');
			}
		}else{
			
			Tpl::setDirquna('wechat');
			Tpl::showpage('wechat_keyword_add');
		}
    }
	
	/**
     * 关键词修改
     **/
    public function keyword_editWt() {
        $model_wechat = Model('wechat');
		if (chksubmit()){
			if(empty($_POST['rid'])){
				showMessage('参数错误！','index.php?w='.$_GET['w'].'&t=keyword_manage');
			}
			
			if(!empty($_POST['keywords'])){
				$_POST['keywords'] = trim($_POST['keywords'],'|');
				$_POST['keywords'] = str_replace('||','|',$_POST['keywords']);
			}
			
			if(empty($_POST['keywords'])){
				showMessage('关键词不能为空！');
			}
			
			$array = explode('|',$_POST['keywords']);
			foreach($array as $a){
				if(trim($a)=='') continue;
				$condition['reply_keywords'] = array('like', '%|' . trim($a) . '|%');
				$condition['reply_id'] = array('neq',$_POST['rid']);
				
				$reply_info = $model_wechat->getInfoOne('weixin_reply',$condition);
				if(!empty($reply_info)){
					showMessage('关键词不存在：'.$a);
				}
			}
			
			$update_array = array(
				'reply_keywords'=>intval($_POST['patternmethod']) == 0 ? trim($_POST['keywords']) : '|'.trim($_POST['keywords'],'|').'|',
				'reply_patternmethod'=>intval($_POST['patternmethod']),
				'reply_msgtype'=>intval($_POST['msgtype']),
				'reply_textcontents'=>trim($_POST['textcontents']),
				'reply_materialid'=>intval($_POST['materialid'])
			);
			
			$condition = array('reply_id'=>intval($_POST['rid']));
			
			$result = $model_wechat->editInfo('weixin_reply',$update_array,$condition);
			if ($result === true){
				showMessage('保存成功！','index.php?w='.$_GET['w'].'&t=keyword_manage');
			}else {
				showMessage('保存失败！','index.php?w='.$_GET['w'].'&t=keyword_manage');
			}
		}else{
			$reply_info = $material_info = array();
			if(empty($_GET['rid'])){
				showMessage('参数错误！','index.php?w='.$_GET['w'].'&t=keyword_manage');
			}
			$reply_info = $model_wechat->getInfoOne('weixin_reply',array('reply_id'=>intval($_GET['rid'])));
			if(empty($reply_info)){
				showMessage('记录不存在','index.php?w='.$_GET['w'].'&t=keyword_manage');
			}
			
			if(!empty($reply_info['reply_materialid'])){
				$material_info = $model_wechat->getInfoOne('weixin_material',array('material_id'=>intval($reply_info['reply_materialid'])));
				if (!empty($material_info)){
					$material_info['items'] = unserialize($material_info['material_content']);
				}
			}
			Tpl::output('reply_info',$reply_info);
			Tpl::output('material_info',$material_info);			
			Tpl::setDirquna('wechat');
			Tpl::showpage('wechat_keyword_edit');
		}
    }
	
	/**
     * 关键词删除
     **/
    public function keyword_delWt() {
        $model_wechat = Model('wechat');
		
		if(empty($_GET['rid']) && empty($_POST['rid'])){
			showMessage('参数错误！','index.php?w='.$_GET['w'].'&t=keyword_manage');
		}
		
		$result = false;
		if(!empty($_GET['rid'])){
			$result = $model_wechat->delInfo('weixin_reply',array('reply_id'=>intval($_GET['rid'])));
		}
		
		if(!empty($_POST['rid'])){
			$where['reply_id'] = array('in', $_POST['rid']);
			$result = $model_wechat->delInfo('weixin_reply',$where);
		}
		
		if($result){
			showMessage('删除成功！','index.php?w='.$_GET['w'].'&t=keyword_manage');
		}else{
			showMessage('删除失败！','index.php?w='.$_GET['w'].'&t=keyword_manage');
		}
    }
	

}
