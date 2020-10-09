<?php
/**
 * 微信公众号
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class menuControl extends SystemControl{

    public function __construct(){
        parent::__construct();
        Language::read('wechat');
    }

    public function indexWt() {
		$this->menu_manageWt();
	}

		/*自定义菜单列表*/
	public function menu_manageWt(){
		$model_wechat = Model('wechat');
		$condition = array();
        $menu_list = $model_wechat->getInfoList('weixin_menu',$condition,10,'menu_addtime desc');
		Tpl::output('menu_list',$menu_list);
		Tpl::output('show_page',$model_wechat->showpage('2'));
		
		Tpl::setDirquna('wechat');
		Tpl::showpage('wechat_menu_list');
	}
	
	/**
     * 自定义菜单添加
     **/
    public function menu_addWt() {
        $model_wechat = Model('wechat');
		if (chksubmit()){
			if(empty($_POST['MenuTitle']))
			{
				showMessage('请输入菜单标题');
			}
			if(empty($_POST['Title'])){
				showMessage('请输入标题');
			}
			$flag = true;
			$model_wechat->beginTransaction();
			$menu = array(
				'menu_name'=>trim($_POST['MenuTitle']),
				'menu_addtime'=>time()
			);
			$menuid = $model_wechat->addInfo('weixin_menu',$menu);
			$flag = $flag && $menuid;
			$i = 0;
			foreach($_POST['Title'] as $key=>$value){
				if(empty($_POST['Title'][$key][0])){
					continue;
				}
				$i++;
				if(!empty($_POST['Url'][$key][0])){
					$_POST['Url'][$key][0] = (strpos($_POST['Url'][$key][0],'http://')>-1 || strpos($_POST['Url'][$key][0],'https://')>-1) ? trim($_POST['Url'][$key][0]) : 'http://'.trim($_POST['Url'][$key][0]);
				}
				$first = array(
					'detail_name'=>$_POST['Title'][$key][0],
					'menu_id'=>$menuid,
					'detail_msgtype'=>$_POST['MsgType'][$key][0],
					'detail_textcontents'=>$_POST['TextContents'][$key][0],
					'detail_materialid'=>$_POST['MaterialID'][$key][0],
					'detail_url'=>$_POST['Url'][$key][0],
					'detail_sort'=>$i
				);
				$parentid = $model_wechat->addInfo('weixin_menu_detail',$first);
				$flag = $flag && $parentid;
				$j=0;
				$detail = array();
				ksort($value);
				foreach($value as $k=>$v){
					if(empty($_POST['Title'][$key][$k]) || $k==0){
						continue;
					}
					$j++;
					if(!empty($_POST['Url'][$key][$k])){
						$_POST['Url'][$key][$k] = (strpos($_POST['Url'][$key][$k],'http://')>-1 || strpos($_POST['Url'][$key][$k],'https://')>-1)? trim($_POST['Url'][$key][$k]) : 'http://'.trim($_POST['Url'][$key][$k]);
					}
					$detail[] = array(
						'detail_name'=>$_POST['Title'][$key][$k],
						'menu_id'=>$menuid,
						'detail_msgtype'=>$_POST['MsgType'][$key][$k],
						'detail_textcontents'=>$_POST['TextContents'][$key][$k],
						'detail_materialid'=>$_POST['MaterialID'][$key][$k],
						'detail_url'=>$_POST['Url'][$key][$k],
						'detail_sort'=>$j,
						'parent_id'=>$parentid
					);
				}
				if(!empty($detail)){
					$child = $model_wechat->addAll('weixin_menu_detail',$detail);
					$flag = $flag && $child;
				}
				
			}
			
			if($flag){
				$model_wechat->commit();
				showMessage('保存成功！','index.php?w='.$_GET['w'].'&t=menu_manage');
			}else{
				$model_wechat->rollback();
				showMessage('保存失败！');
			}
			
		}else{
			Tpl::setDirquna('wechat');
			Tpl::showpage('wechat_menu_add');
		}
    }
	
	/**
     * 自定义菜单删除
     **/
    public function menu_delWt() {
        $model_wechat = Model('wechat');
		$result = false;

		if(empty($_GET['mid']) && empty($_POST['mid'])){
			showMessage($lang['not_info_id'],'index.php?w='.$_GET['w'].'&t=menu_manage');
		}
		
		$mid = array();
		if(!empty($_GET['mid'])){
			$mid[] = $_GET['mid'];
		}
		
		if(!empty($_POST['mid'])){
			$mid = $_POST['mid'];
		}
		
		$where['menu_id'] = array('in', $mid);
		$where['menu_status'] = 1;
		$menu_info = $model_wechat->getInfoOne('weixin_menu',$where);
		
		$condition['menu_id'] = array('in', $mid);
		$result = $model_wechat->delInfo('weixin_menu_detail',$condition);
		$result = $model_wechat->delInfo('weixin_menu',$condition);
		if(!empty($menu_info)){
			$response = $this->deletemenu();
			if($response['status']==0){
				showMessage($lang[$response['msg']],'index.php?w='.$_GET['w'].'&t=menu_manage');
			}else{
				showMessage($lang[$response['msg']],'index.php?w='.$_GET['w'].'&t=menu_manage');
			}
		}else{
			if($result){
				showMessage('删除成功','index.php?w='.$_GET['w'].'&t=menu_manage');
			}else{
				showMessage('删除失败！','index.php?w='.$_GET['w'].'&t=menu_manage');
			}
		}
    }
	
	/**
     * 自定义菜单添加
     **/
    public function menu_editWt() {
        $model_wechat = Model('wechat');
		if (chksubmit()){
			if(empty($_POST['MenuTitle'])){
				showMessage('请输入菜单标题');
			}
			
			if(empty($_POST['Title'])){
				showMessage('请输入标题');
			}
			
			if(empty($_POST['mid'])){
				showMessage($lang['not_info_id'],'index.php?w='.$_GET['w'].'&t=menu_manage');
			}
			
			$menu_info = $model_wechat->getInfoOne('weixin_menu',array('menu_id'=>intval($_POST['mid'])),'menu_status');
			if(empty($menu_info)){
				showMessage('菜单信息不存在！','index.php?w='.$_GET['w'].'&t=menu_manage');
			}
			if($_POST['inputtype'] == 0 && empty($_POST['inputtextcontents'])){
				showMessage('菜单动作回复内容不能为空！');
				exit;
			}
			if($_POST['inputtype'] == 2 && empty($_POST['inputlink'])){
				showMessage('菜单动作url不能为空！');
				exit;
			}
			
			$flag = true;
			$model_wechat->beginTransaction();
			
			$result = $model_wechat->delInfo('weixin_menu_detail',array('menu_id'=>intval($_POST['mid'])));
			$flag = $flag && $result;
			
			$menu = array(
				'menu_name'=>trim($_POST['MenuTitle'])
			);
			
			$result = $model_wechat->editInfo('weixin_menu',$menu,array('menu_id'=>intval($_POST['mid'])));
			$flag = $flag && $result;
			
			$i = 0;
			foreach($_POST['Title'] as $key=>$value){
				if(empty($_POST['Title'][$key][0])){
					continue;
				}
				$i++;
				if(!empty($_POST['Url'][$key][0])){
					$_POST['Url'][$key][0] = (strpos($_POST['Url'][$key][0],'http://')>-1 || strpos($_POST['Url'][$key][0],'https://')>-1)? trim($_POST['Url'][$key][0]) : 'http://'.trim($_POST['Url'][$key][0]);
				}
				$first = array(
					'detail_name'=>$_POST['Title'][$key][0],
					'menu_id'=>$_POST['mid'],
					'detail_msgtype'=>$_POST['MsgType'][$key][0],
					'detail_textcontents'=>$_POST['TextContents'][$key][0],
					'detail_materialid'=>$_POST['MaterialID'][$key][0],
					'detail_url'=>$_POST['Url'][$key][0],
					'detail_sort'=>$i
				);
				
				$parentid = $model_wechat->addInfo('weixin_menu_detail',$first);
				$flag = $flag && $parentid;
				$j=0;
				$detail = array();
				ksort($value);
				foreach($value as $k=>$v){
					if(empty($_POST['Title'][$key][$k]) || $k==0){
						continue;
					}
					$j++;
					if(!empty($_POST['Url'][$key][$k])){
						$_POST['Url'][$key][$k] = (strpos($_POST['Url'][$key][$k],'http://')>-1 || strpos($_POST['Url'][$key][$k],'https://')>-1)? trim($_POST['Url'][$key][$k]) : 'http://'.trim($_POST['Url'][$key][$k]);
					}
					$detail[] = array(
						'detail_name'=>$_POST['Title'][$key][$k],
						'menu_id'=>$_POST['mid'],
						'detail_msgtype'=>$_POST['MsgType'][$key][$k],
						'detail_textcontents'=>$_POST['TextContents'][$key][$k],
						'detail_materialid'=>$_POST['MaterialID'][$key][$k],
						'detail_url'=>$_POST['Url'][$key][$k],
						'detail_sort'=>$j,
						'parent_id'=>$parentid
					);
				}
				if(!empty($detail)){
					$child = $model_wechat->addAll('weixin_menu_detail',$detail);
					$flag = $flag && $child;
				}
				
			}
			
			if($flag){
				$model_wechat->commit();
				if($menu_info['menu_status']==1){
					$response = $this->publish($_POST['mid']);
					if($response['status']==1){
						showMessage('同步中，请到微信公众号查看','index.php?w='.$_GET['w'].'&t=menu_manage');
					}else{
						showMessage('有个别菜单动作为空，请检查！','index.php?w='.$_GET['w'].'&t=menu_manage');
					}
				}else{
					showMessage('保存成功！','index.php?w='.$_GET['w'].'&t=menu_manage');
				}				
			}else{
				$model_wechat->rollback();
				showMessage('保存失败！');
			}
			
		}else{
			$menu_info = array();
			if(empty($_GET['mid'])){
				showMessage($lang['not_info_id'],'index.php?w='.$_GET['w'].'&t=menu_manage');
			}
			$menu_info = $model_wechat->getInfoOne('weixin_menu',array('menu_id'=>intval($_GET['mid'])));
			if(empty($menu_info)){
				showMessage($lang['info_not_exist'],'index.php?w='.$_GET['w'].'&t=menu_manage');
			}
			
			$first_info = $second_info = array();
			$result = $model_wechat->getInfoList('weixin_menu_detail',array('menu_id'=>intval($_GET['mid'])),'','parent_id asc, detail_sort asc');
			$i = $j = 0;
			$child_info = array();
			if(!empty($result)){
				foreach($result as $key=>$value){
					if($value['parent_id']==0){
						$i++;
						$child_info[$value['detail_id']] = 0;
						$first_info[$i] = $value;
					}else{
						$j++;
						$child_info[$value['parent_id']] = $child_info[$value['parent_id']] + 1;
						$second_info[$value['parent_id']]['child'][$j] = $value;
					}
				}
			}
			
			Tpl::output('firstnum',count($first_info));
			Tpl::output('menu_info',$menu_info);
			Tpl::output('child_info',$child_info);
			Tpl::output('first_info',$first_info);
			Tpl::output('j',$j);
			Tpl::output('second_info',$second_info);
			Tpl::setDirquna('wechat');
			Tpl::showpage('wechat_menu_edit');
		}
    }
	
	public function menu_publishWt(){
        $response = $this->publish($_GET['mid']);
		if($response['status']==0){
			showMessage('同步数据失败！','index.php?w='.$_GET['w'].'&t=menu_manage');
		}else{
			showMessage('同步数据成功！','index.php?w='.$_GET['w'].'&t=menu_manage');
		}
	}
	
	
	private function publish($menuid){
		$model_wechat = Model('wechat');
		
		if(empty($menuid)){
			return array('status'=>0,'msg'=>'not_info_id');
		}
		
		$api_account = $model_wechat->getInfoOne('weixin_wechat','');
	
		if(empty($api_account["wechat_appid"]) || empty($api_account["wechat_appsecret"])){
			return array('status'=>0,'msg'=>'not_appid');
		}
		
		$result = $model_wechat->getInfoList('weixin_menu_detail',array('menu_id'=>intval($menuid)),'','parent_id asc, detail_sort asc');
		if(empty($result)){
			return array('status'=>0,'msg'=>'not_menu_data');
		}
		
		$ACCESS_TOKEN = Handle('weixin_token')->get_access_token();
		if(!$ACCESS_TOKEN){
			return array('status'=>0,'msg'=>'get_access_token_fail');
		}
		
		$first_menu = $child_menu = array();
		foreach($result as $key=>$value){
			if($value['parent_id']==0){
				$first_menu[] = $value;
			}else{
				$child_menu[$value['parent_id']][] = $value;
			}
		}
		
		$Menu=array();
		foreach($first_menu as $key=>$value){
			if(!empty($child_menu[$value['detail_id']])){
				$Data=array(
					"name"=>$value["detail_name"],
					"sub_button"=>array()
				);
				$sub_button = array_reverse($child_menu[$value['detail_id']]);
				foreach($sub_button as $k=>$v){
					if($v["detail_msgtype"]==0){
						$Data["sub_button"][]=array(
							"type"=>"click",
							"name"=>$v["detail_name"],
							"key"=>strlen($v["detail_textcontents"])>=120 ? "changwenben_".$v["detail_id"] : $v["detail_textcontents"]
						);
					}elseif($v["detail_msgtype"]==1){
						$Data["sub_button"][]=array(
							"type"=>"click",
							"name"=>$v["detail_name"],
							"key"=>"MaterialID_".$v["detail_materialid"]
						);
					}elseif($v["detail_msgtype"]==2){
						$Data["sub_button"][]=array(
							"type"=>"view",
							"name"=>$v["detail_name"],
							"url"=>$v["detail_url"]
						);
					}
				}
			}else{
				if($value["detail_msgtype"]==0){
					$Data=array(
						"type"=>"click",
						"name"=>$value["detail_name"],
						"key"=>strlen($value["detail_textcontents"])>=120 ? "changwenben_".$value["detail_id"] : $value["detail_textcontents"]
					);
				}elseif($value["detail_msgtype"]==1){
					$Data=array(
						"type"=>"click",
						"name"=>$value["detail_name"],
						"key"=>"MaterialID_".$value["detail_materialid"]
					);
				}elseif($value["detail_msgtype"]==2){
					$Data=array(
						"type"=>"view",
						"name"=>$value["detail_name"],
						"url"=>$value["detail_url"]
					);
				}
			}
			$Menu["button"][]=$Data;
		}
		$response = Handle('weixin_token')->curl_post('https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$ACCESS_TOKEN,$Menu);
		
		if(empty($response['errcode'])){
			$model_wechat->editInfo('weixin_menu',array('menu_status'=>0),'');
			$model_wechat->editInfo('weixin_menu',array('menu_status'=>1),array('menu_id'=>$menuid));
			return array('status'=>1,'msg'=>'menu_publish_success');
			
		}else{
			return array('status'=>0,'msg'=>'menu_publish_fail'.json_encode($response).'-------'.json_encode($Menu));
		}
	}
	
	private function deletemenu(){
		$model_wechat = Model('wechat');
		
		$api_account = $model_wechat->getInfoOne('weixin_wechat','');
	
		if(empty($api_account["wechat_appid"]) || empty($api_account["wechat_appsecret"])){
			return array('status'=>0,'msg'=>'not_appid');
		}
		
		$ACCESS_TOKEN = Handle('weixin_token')->get_access_token();
		if(!$ACCESS_TOKEN){
			return array('status'=>0,'msg'=>'get_access_token_fail');
		}
		
		$response = Handle('weixin_token')->curl_get('https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='.$ACCESS_TOKEN);
		
		if(empty($response['errcode'])){
			return array('status'=>1,'msg'=>'menu_delete_success');			
		}else{
			return array('status'=>0,'msg'=>'menu_delete_fail');
		}
	}
	/**
	 * ajax操作
	 */
	public function ajaxWt(){
		$model_wechat = Model('wechat');
		switch ($_GET['branch']){
			case 'check_keywords':
				$keywords = trim($_GET['keywords'],'|');
				$rid = empty($_GET['rid']) ? 0 : intval($_GET['rid']);
				$array = explode('|',$keywords);
				foreach($array as $a){
					if(trim($a)=='') continue;
					
					$condition['reply_keywords'] = array('like', '%|' . trim($a) . '|%');
					if($rid>0){
						$condition['reply_id'] = array('neq',$rid);
					}
					
					$reply_info = $model_wechat->getInfoOne('weixin_reply',$condition);
					if(!empty($reply_info)){
						echo 'false';
						exit;
					}
				}
				
				echo 'true';
				exit;
			break;
			case 'get_material':
				if(empty($_GET['mid'])){
					$data['msg'] = '<div class="item"></div>';
					echo json_encode($data);
					exit;
				}
				$material_info = $model_wechat->getInfoOne('weixin_material',array('material_id'=>intval($_GET['mid'])));
				if (empty($material_info)){
					$data['msg'] = '<div class="item"></div>';
					echo json_encode($data);
					exit;
				}
				
				$items = unserialize($material_info['material_content']);
				if(!is_array($items)){
					$data['msg'] = '<div class="item"></div>';
					echo json_encode($data);
					exit;
				}
				
				$html = '';
				if($material_info['material_type'] == 1){
					$html .= '<div class="item one">';
					foreach($items as $k=>$v){
                  		$html .= '<div class="title">'.$v['Title'].'</div><div>'.date("Y-m-d",$material_info['material_addtime']).'</div><div class="img"><img src="'.UPLOAD_SITE_URL.$v['ImgPath'].'" /></div><div class="txt">'.str_replace(array("\r\n", "\r", "\n"), "<br />",$v['TextContents']).'</div>';
                 	}
					$html .= '</div>';
				}else{
					$html .= '<div class="item multi">';
					$html .= '<div class="time">'.date("Y-m-d",$material_info['material_addtime']).'</div>';
                  	foreach($items as $k=>$v){
                  		$html .= '<div class="'.($k>0 ? "list" : "first").'"><div class="info"><div class="img"><img src="'.UPLOAD_SITE_URL.$v['ImgPath'].'" /></div><div class="title">'.$v['Title'].'</div></div></div>';
                  	}
					$html .= '</div>';
				}
				$data['msg'] = $html;
				echo json_encode($data);
				exit;
			break;
		}
	}
	
	
}
