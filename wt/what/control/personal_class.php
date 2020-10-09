<?php
/**
 * 买什么
 *
 *
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class personal_classControl extends SystemControl{

    const what_CLASS_LIST = 'index.php?w=goods_class&t=goodsclass_list';
    const GOODS_FLAG = 1;
    const PERSONAL_FLAG = 2;
    const ALBUM_FLAG = 3;
    const STORE_FLAG = 4;

    public function __construct(){
        parent::__construct();
        Language::read('store');
        Language::read('what');
    }

    public function indexWt() {
       $this->personalclass_listWt();
    }

    /**
     * 买什么商品(说说看)分类管理
     **/
    public function personalclass_listWt() {
        $model_class = Model("what_personal_class");
        $list = $model_class->getList(TRUE);
        Tpl::output('list',$list);
        $this->show_menu_personal_class("personal_class_list");
        Tpl::setDirquna('what');
Tpl::showpage("what_personal_class.list");
    }

    /**
     * 买什么买心得分类添加
     **/
    public function personalclass_addWt() {
        $this->show_menu_personal_class('personal_class_add');
        Tpl::setDirquna('what');
Tpl::showpage('what_personal_class.add');
    }

    /**
     * 买什么商品(说说看)分类编辑
     **/
    public function personalclass_editWt() {
        $class_id = intval($_GET['class_id']);
        if(empty($class_id)) {
            showMessage(Language::get('param_error'),'','','error');
        }
        $model_class = Model("what_personal_class");
        $condition = array();
        $condition['class_id'] = $class_id;
        $class_info = $model_class->getOne($condition);
        Tpl::output('class_info',$class_info);

        $this->show_menu_personal_class("personal_class_edit");
        Tpl::setDirquna('what');
Tpl::showpage("what_personal_class.add");
    }

    /**
     * 买什么买心得分类保存
     **/
    public function personalclass_saveWt() {
        $obj_validate = new Validate();
        $validate_array = array(
            array('input'=>$_POST['class_name'],'require'=>'true',"validator"=>"Length","min"=>"1","max"=>"10",'message'=>Language::get('class_name_error')),
            array('input'=>$_POST['class_sort'],'require'=>'true','validator'=>'Range','min'=>0,'max'=>255,'message'=>Language::get('class_sort_error')),
        );
        $obj_validate->validateparam = $validate_array;
        $error = $obj_validate->validate();
        if ($error != ''){
            showMessage(Language::get('error').$error,'','','error');
        }

        $param = array();
        $param['class_name'] = trim($_POST['class_name']);
        $param['class_sort'] = intval($_POST['class_sort']);
        if(!empty($_FILES['class_image']['name'])) {
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_WHAT);
            $result = $upload->upfile('class_image');
            if(!$result) {
                showMessage($upload->error);
            }
            $param['class_image'] = $upload->file_name;
            //删除老图片
            if(!empty($_POST['old_class_image'])) {
                $old_image = BASE_UPLOAD_PATH.DS.ATTACH_WHAT.DS.$_POST['old_class_image'];
                if(is_file($old_image)) {
                    unlink($old_image);
                }
            }
        }

        $model_class = Model("what_personal_class");
        if(isset($_POST['class_id']) && intval($_POST['class_id']) > 0) {
            $result = $model_class->modify($param,array('class_id'=>$_POST['class_id']));
        } else {
            $result = $model_class->save($param);
        }
        if($result) {
            showMessage(Language::get('class_add_success'),"index.php?w=personal_class&t=personalclass_list");
        } else {
            showMessage(Language::get('class_add_fail'),"index.php?w=personal_class&t=personalclass_list",'','error');
        }

    }

    /*
     * ajax修改分类排序
     */
    public function personalclass_sort_updateWt() {
        if(intval($_GET['id']) <= 0) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        }
        $new_sort = intval($_GET['value']);
        if ($new_sort > 255){
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('class_sort_error')));
            die;
        } else {
            $model_class = Model("what_personal_class");
            $result = $model_class->modify(array('class_sort'=>$new_sort),array('class_id'=>$_GET['id']));
            if($result) {
                echo json_encode(array('result'=>TRUE,'message'=>'class_add_success'));
                die;
            } else {
                echo json_encode(array('result'=>FALSE,'message'=>Language::get('class_add_fail')));
                die;
            }
        }
    }

    /*
     * ajax修改分类名称
     */
    public function personalclass_name_updateWt() {
        $class_id = intval($_GET['id']);
        if($class_id <= 0) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        }

        $new_name = trim($_GET['value']);
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array('input'=>$new_name,'require'=>'true',"validator"=>"Length","min"=>"1","max"=>"10",'message'=>Language::get('class_name_error')),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('class_name_error')));
            die;
        } else {
            $model_class = Model("what_personal_class");
            $result = $model_class->modify(array('class_name'=>$new_name),array('class_id'=>$class_id));
            if($result) {
                echo json_encode(array('result'=>TRUE,'message'=>'class_add_success'));
                die;
            } else {
                echo json_encode(array('result'=>FALSE,'message'=>Language::get('class_add_fail')));
                die;
            }
        }
    }

    /**
     * 买心得分类删除
     **/
     public function personalclass_dropWt() {

        $class_id = trim($_REQUEST['class_id']);
        $model_class = Model('what_personal_class');
        $condition = array();
        $condition['class_id'] = array('in',$class_id);
        //删除分类图片
        $list = $model_class->getList($condition);
        if(!empty($list)) {
            foreach ($list as $value) {
                //删除老图片
                if(!empty($value['class_image'])) {
                    $old_image = BASE_UPLOAD_PATH.DS.ATTACH_WHAT.DS.$value['class_image'];
                    if(is_file($old_image)) {
                        unlink($old_image);
                    }
                }
            }
        }

        $result = $model_class->drop($condition);
        if($result) {
            showMessage(Language::get('class_drop_success'),'');
        } else {
            showMessage(Language::get('class_drop_fail'),'','','error');
        }

     }

     private function show_menu_personal_class($menu_key) {
         $menu_array = array(
                 'personal_class_list'=>array('menu_type'=>'link','menu_name'=>Language::get('wt_manage'),'menu_url'=>'index.php?w=personal_class&t=personalclass_list'),
                 'personal_class_add'=>array('menu_type'=>'link','menu_name'=>Language::get('wt_new'),'menu_url'=>'index.php?w=personal_class&t=personalclass_add'),
         );
         if($menu_key == 'personal_class_edit') {
             $menu_array['personal_class_edit'] = array('menu_type'=>'link','menu_name'=>Language::get('wt_edit'),'menu_url'=>'###');
         }
         $menu_array[$menu_key]['menu_type'] = 'text';
         Tpl::output('menu',$menu_array);
     }

}
