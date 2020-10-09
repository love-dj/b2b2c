<?php
/**
 * 等级
 *
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @license    http://www.weisbao.com
 * @link       联系方式：13632978801
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class schema_levelControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('schema');
	}

	public function indexWt(){
		$this->schemaWt();
	}
	/**
	 * 等级列表
	 * @return schema_list 
	 */ 
	public function schemaWt() {
		$model_schema = Model('schema_level');
		$schema_list = $model_schema->select();
		Tpl::output('schema_list', $schema_list);
		Tpl::setDirquna('schema');
		Tpl::showpage('schema_level');
	}
    /**
     * 输出XML数据
     */
    public function get_xmlWt() {
    	$model = Model('schema_level');
    	$condition = array();
    	if ($_POST['query'] != '') {
    		$condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
    	}
    	$order = '';
    	$param = array('id', 'level_weight', 'level_name', 'layer_one', 'layer_two', 'layer_three', 'level_people');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $list = $model->where($condition)->page($page)->order($order)->select();

        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();

        foreach ($list as $value) {            
        	$param = array();

        	$operation = "<a class='btn red' href='javascript:void(0);' onclick='fg_delete(".$value['id'].");'><i class='fa fa-trash-o'></i>删除</a>";
        	$operation .= "<span class='btn'><em><i class='fa fa-cog'></i>" . L('nc_set') . " <i class='arrow'></i></em><ul>";
        	$operation .= "<li><a href='index.php?act=schema_level&op=edit_level&id=".$value['id']."'>编辑</a></li>";            
        	$operation .= "</ul></span>";
        	$param['operation']     = $operation;
        	$param['level_weight']  = $value['level_weight'];
        	$param['level_name']    = $value['level_name'];
        	$param['layer_one']     = $value['layer_one'];
        	$param['layer_two']     = $value['layer_two'];
            $param['layer_three']   = $value['layer_three'];
            $where['level_id']      = $value['id'];
            $user_list              = Model('schema_manage')->where($where)->select();
            $param['people_number'] = count($user_list);
            // $param['level_condition']   = $value['level_condition'];
            
        	$data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);
    }    

    /**
     * 新增分销等级
     */
    public function add_levelWt(){
    	if(chksubmit()){
    		$obj_validate = new Validate();
    		$obj_validate->validateparam = array(
    			array("input"=>$_POST["level_weight"], "require"=>"true", "message"=>"等级权重不能为空"),
    			array("input"=>$_POST["level_name"], "require"=>"true", "message"=>"等级名称不能为空"),
    			array("input"=>$_POST["layer_one"], "require"=>"true"),
    			array("input"=>$_POST["layer_two"], "require"=>"true"),
                array("input"=>$_POST["layer_three"], "require"=>"true"),
    			array("input"=>$_POST["level_people"], "require"=>"true"),
                array("input"=>$_POST["level_condition"], "require"=>"true"),
    		);
    		$error = $obj_validate->validate();
    		if ($error != ''){
    			showMessage($error);
    		} else {
    			$param  = array();                
                $param['level_weight']    = trim($_POST['level_weight']);
                $param['level_name']      = trim($_POST['level_name']);
                $param['layer_one']       = trim($_POST['layer_one']);
                $param['layer_two']       = trim($_POST['layer_two']);
                $param['layer_three']     = trim($_POST['layer_three']);
                $param['level_people']    = trim($_POST['level_people']);
                // $param['level_condition'] = serialize($_POST['upgrade_value']);                
                $schema_level_model       = Model('schema_level');
    			$result = $schema_level_model->insert($param);
    			if ($result){
    				$url = array(
    					array(
    						'url'=>'index.php?act=schema_level&op=index',
    						'msg'=>'返回',
    					),
    					array(
    						'url'=>'index.php?act=schema_level&op=add_level',
    						'msg'=>'继续新增分销等级',
    					),
    				);
    				dkcache('schema_level');
    				$this->log('新增分销等级'.'['.$_POST['level_name'].']',null);
    				showMessage("新增分销等级成功",$url);
    			}else{
    				showMessage("新增分销等级失败");
    			}
    		}
    	}
    	$schema_config = Model('setting');
        $setting_list = $schema_config->getListSetting();
        Tpl::output('setting',$setting_list);
        Tpl::setDirquna('schema');
        Tpl::showpage('schema_level_add');
    }

    /**
     * 编辑等级
     */
    public function edit_levelWt(){
    	if(chksubmit()){

    		$obj_validate = new Validate();
    		$obj_validate->validateparam = array(
    			array("input"=>$_POST["level_weight"], "require"=>"true", "message"=>"等级权重不能为空"),
    			array("input"=>$_POST["level_name"], "require"=>"true", "message"=>"等级名称不能为空"),
    			array("input"=>$_POST["layer_one"], "require"=>"true"),
    			array("input"=>$_POST["layer_two"], "require"=>"true"),
                array("input"=>$_POST["layer_three"], "require"=>"true"),
    			array("input"=>$_POST["level_people"], "require"=>"true"),
                array("input"=>$_POST["level_condition"], "require"=>"true"),
    		);

    		$error = $obj_validate->validate();
    		if ($error != ''){
    			showMessage($error);
    		} else {
    			$param  = array();
                $con = array();
                $param['level_weight']    = trim($_POST['level_weight']);
                $param['level_name']      = trim($_POST['level_name']);
                $param['layer_one']       = trim($_POST['layer_one']);
                $param['layer_two']       = trim($_POST['layer_two']);
                $param['layer_three']     = trim($_POST['layer_three']);
                $param['level_people']    = trim($_POST['level_people']);                
                $con['upgrade_type']      = $_POST['upgrade_type'];
                $con['upgrade_value']     = $_POST['upgrade_value']; 
                $param['level_condition'] = serialize($con);                         
    			$schema_model             = Model('schema_level');
    			$result = $schema_model->where(array('id'=>$_POST['id']))->update($param);
    			if ($result){
    				$url = array(
    					array(
    						'url'=>'index.php?act=schema_level&op=index',
    						'msg'=>'返回等级列表',
    					),
    					array(
    						'url'=>'index.php?act=schema_level&op=edit_level&id='.intval($_POST['id']),
    						'msg'=>'重新编辑该等级',
    					),
    				);
    				dkcache('schema_level');
    				$this->log('编辑等级'.'['.$_POST['level_name'].']',null);
    				showMessage("编辑等级成功",$url);
    			}else{
    				showMessage("编辑等级失败");
    			}
    		}
    	}  
        $id = trim($_REQUEST['id']);
        $level_model = Model('schema_level');
        $condition = "id = ".$id ;
        $level_array = $level_model->where($condition)->find();
        // var_dump($level_array);exit;
    	if (empty($level_array)){
    		showMessage('参数错误');
    	}
    	Tpl::output('level_array',$level_array);
    	Tpl::setDirquna('schema');
    	Tpl::showpage('schema_level_edit');
    }

    /**
     * 删除等级
     */
    public function del_levelWt(){
    	$id = trim($_REQUEST['del_id']);
        $level_model = Model('schema_level');
        $condition['id'] = array('in',$id);
    	$result = $level_model->where($condition)->delete();
        if($result) {
    		dkcache('schema_level');
    		$this->log('删除等级['.$id.']', 1);
    		showMessage('删除等级成功','');
    	} else {
    		showMessage('删除等级失败','','','error');
    	}

    }

}
