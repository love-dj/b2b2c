<?php
/**
 * 微信公众号
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class subcribeControl extends SystemControl{

    public function __construct(){
        parent::__construct();
        Language::read('wechat');
    }

    public function indexWt() {
		$this->subcribe_manageWt();
		
	}
/**
     * 首次关注设置
     **/
    public function subcribe_manageWt() {
        $model_wechat = Model('wechat');
		if (chksubmit()){
			if(empty($_POST['rid'])){
				showMessage('参数错误！');
			}
			$rid = intval($_POST['rid']);
			$update_array = array(
				'reply_msgtype'=>intval($_POST['msgtype']),
				'reply_textcontents'=>trim($_POST['textcontents']),
				'reply_materialid'=>empty($_POST['materialid']) ? 0 : intval($_POST['materialid']),
				'reply_subscribe'=>empty($_POST['subscribe']) ? 0 : intval($_POST['subscribe']),
				'reply_membernotice'=>empty($_POST['membernotice']) ? 0 : intval($_POST['membernotice'])
			);
			
			$condition = array('reply_id'=>$rid);
			
			$result = $model_wechat->editInfo('weixin_attention',$update_array,$condition);
			if ($result === true){
				showMessage('保存成功！');
			}else {
				showMessage('保存失败！');
			}
		}else{
			$attention_account = $model_wechat->getInfoOne('weixin_attention','');
			if(empty($attention_account)){
				$attention_account = array(
					'admin_id'=>'',
					'reply_msgtype'=>0,
					'reply_textcontents'=>'很高兴认识你，新朋友！',
					'reply_materialid'=>0,
					'reply_subscribe'=>1,
					'reply_membernotice'=>1	
				);
				$reply_id = $model_wechat->addInfo('weixin_attention',$attention_account);
				$attention_account['reply_id'] = $reply_id;
			}
			
			$material_info = array();
			if(!empty($attention_account['reply_materialid'])){
				$material_info = $model_wechat->getInfoOne('weixin_material',array('material_id'=>intval($attention_account['reply_materialid'])));
				if (!empty($material_info)){
					$material_info['items'] = unserialize($material_info['material_content']);
				}
			}
			Tpl::output('material_info',$material_info);
			Tpl::output('attention_account',$attention_account);
			Tpl::setDirquna('wechat');
			Tpl::showpage('wechat_attention');
		}
    }
	
}
