<?php
/**
 * 店铺分类管理
 *
 *
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class store_classControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('store_class');
    }

    public function indexWt() {
        $this->store_classWt();
    }

    /**
     * 店铺分类
     */
    public function store_classWt(){
        $lang   = Language::getLangContent();
        $model_class = Model('store_class');

        //删除
        if (chksubmit()){
            if (!empty($_POST['check_sc_id']) && is_array($_POST['check_sc_id']) ){
                $result = $model_class->delStoreClass(array('sc_id'=>array('in',$_POST['check_sc_id'])));
                if ($result) {
                    $this->log(L('wt_del,store_class').'[ID:'.implode(',',$_POST['check_sc_id']).']',1);
                    showMessage($lang['wt_common_del_succ']);
                }
            }
            showMessage($lang['wt_common_del_fail']);
        }

        $store_class_list = $model_class->getStoreClassList(array());
        Tpl::output('class_list',$store_class_list);
		Tpl::setDirquna('shop');
        Tpl::showpage('store_class.index');
    }

    /**
     * 商品分类添加
     */
    public function store_class_addWt(){
        $lang   = Language::getLangContent();
        $model_class = Model('store_class');
        if (chksubmit()){
            //验证
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
            array("input"=>$_POST["sc_name"], "require"=>"true", "message"=>$lang['store_class_name_no_null']),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $insert_array = array();
                $insert_array['sc_name'] = $_POST['sc_name'];
                $insert_array['sc_bail'] = intval($_POST['sc_bail']);
                $insert_array['sc_sort'] = intval($_POST['sc_sort']);
                $result = $model_class->addStoreClass($insert_array);
                if ($result){
                    $url = array(
                    array(
                    'url'=>'index.php?w=store_class&t=store_class_add',
                    'msg'=>$lang['continue_add_store_class'],
                    ),
                    array(
                    'url'=>'index.php?w=store_class&t=store_class',
                    'msg'=>$lang['back_store_class_list'],
                    )
                    );
                    $this->log(L('wt_add,store_class').'['.$_POST['sc_name'].']',1);
                    showMessage($lang['wt_common_save_succ'],$url,'html','succ',1,5000);
                }else {
                    showMessage($lang['wt_common_save_fail']);
                }
            }
        }
		Tpl::setDirquna('shop');
        Tpl::showpage('store_class.add');
    }

    /**
     * 编辑
     */
    public function store_class_editWt(){
        $lang   = Language::getLangContent();

        $model_class = Model('store_class');

        if (chksubmit()){
            //验证
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
            array("input"=>$_POST["sc_name"], "require"=>"true", "message"=>$lang['store_class_name_no_null']),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $update_array = array();
                $update_array['sc_name'] = $_POST['sc_name'];
                $update_array['sc_bail'] = intval($_POST['sc_bail']);
                $update_array['sc_sort'] = intval($_POST['sc_sort']);
                $result = $model_class->editStoreClass($update_array,array('sc_id'=>intval($_POST['sc_id'])));
                if ($result){
                    $this->log(L('wt_edit,store_class').'['.$_POST['sc_name'].']',1);
                    showMessage($lang['wt_common_save_succ'],'index.php?w=store_class&t=store_class');
                }else {
                    showMessage($lang['wt_common_save_fail']);
                }
            }
        }

        $class_array = $model_class->getStoreClassInfo(array('sc_id'=>intval($_GET['sc_id'])));
        if (empty($class_array)){
            showMessage($lang['illegal_parameter']);
        }

        Tpl::output('class_array',$class_array);
		Tpl::setDirquna('shop');
        Tpl::showpage('store_class.edit');
    }

    /**
     * 删除分类
     */
    public function store_class_delWt(){
        $lang   = Language::getLangContent();
        $model_class = Model('store_class');
        if (intval($_GET['sc_id']) > 0){
            $array = array(intval($_GET['sc_id']));
            $result = $model_class->delStoreClass(array('sc_id'=>intval($_GET['sc_id'])));
            if ($result) {
                 $this->log(L('wt_del,store_class').'[ID:'.$_GET['sc_id'].']',1);
                 showMessage($lang['wt_common_del_succ'],getReferer());
            }
        }
        showMessage($lang['wt_common_del_fail'],'index.php?w=store_class&t=store_class');
    }

    /**
     * ajax操作
     */
    public function ajaxWt(){
        $model_class = Model('store_class');
        $update_array = array();
        switch ($_GET['branch']){
            //分类：添加、修改操作中 检测类别名称是否有重复
            case 'sc_name':
                $condition = array();
                $condition['sc_name'] = $_GET['value'];
                $condition['sc_id'] = array('neq',intval($_GET['sc_id']));
                $class_list = $model_class->getStoreClassList($condition);
                if (empty($class_list)){
                    $update_array['sc_name'] = $_GET['value'];
                    $update = $model_class->editStoreClass($update_array,array('sc_id'=>intval($_GET['id'])));
                    $return = $update ? true : false;
                } else {
                    $return = false;
                }
                break;
            //分类： 排序 显示 设置
            case 'sc_sort':
                $model_class = Model('store_class');
                $update_array['sc_sort'] = intval($_GET['value']);
                $result = $model_class->editStoreClass($update_array,array('sc_id'=>intval($_GET['id'])));
                $return = $result ? true : false;
                break;
        }
        exit(json_encode(array('result'=>$return)));
    }
    
    /**
     * 验证分类名称
     */
    public function ajax_check_nameWt(){
        $model_class = Model('store_class');
        $condition['sc_name'] = $_GET['sc_name'];
        $condition['sc_id'] = array('neq',intval($_GET['sc_id']));
        $class_list = $model_class->getStoreClassList($condition);
        $return = empty($class_list) ? 'true' : 'false';
        echo $return;
    }
}
