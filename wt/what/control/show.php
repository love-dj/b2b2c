<?php
/**
 * 买什么
 *
 *
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class showControl extends SystemControl{

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
       $this->show_manageWt();
    }

    /**
     * 广告管理
     */
    public function show_manageWt() {
        $model_personal = Model('what_show');
        $condition = array();
        if(!empty($_GET['show_type'])) {
            $condition['show_type'] = array('like','%'.trim($_GET['show_type']).'%');
        }
        if(!empty($_GET['show_name'])) {
            $condition['show_name'] = array('like','%'.trim($_GET['show_name']).'%');
        }
        $list = $model_personal->getList($condition,10,'','*');
        Tpl::output('show_page',$model_personal->showpage(2));
        Tpl::output('list',$list);
        $this->get_show_type_list();
        $this->show_menu_show('show_manage');
        Tpl::setDirquna('what');
Tpl::showpage('what_show.manage');
    }

    /**
     * 买什么广告添加
     **/
    public function show_addWt() {
        $this->get_show_type_list();
        $this->show_menu_show('show_add');
        Tpl::setDirquna('what');
Tpl::showpage('what_show.add');
    }

    public function show_editWt() {
        $show_id = intval($_GET['show_id']);
        if(empty($show_id)) {
            showMessage(Language::get('param_error'),'','','error');
        }
        $model_show = Model("what_show");
        $condition = array();
        $condition['show_id'] = $show_id;
        $show_info = $model_show->getOne($condition);
        Tpl::output('show_info',$show_info);

        $this->get_show_type_list();
        $this->show_menu_show('show_add');
        Tpl::setDirquna('what');
Tpl::showpage("what_show.add");
    }

    public function show_saveWt() {
        $obj_validate = new Validate();
        $validate_array = array(
            array('input'=>$_POST['show_sort'],'require'=>'true','validator'=>'Range','min'=>0,'max'=>255,'message'=>Language::get('class_sort_error')),
        );
        $obj_validate->validateparam = $validate_array;
        $error = $obj_validate->validate();
        if ($error != ''){
            showMessage(Language::get('error').$error,'','','error');
        }

        $param = array();
        $param['show_type'] = trim($_POST['show_type']);
        $param['show_name'] = trim($_POST['show_name']);
        $param['show_url'] = trim($_POST['show_url']);
        $param['show_sort'] = intval($_POST['show_sort']);
        if(!empty($_FILES['show_image']['name'])) {
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_WHAT.DS.'shopwt');
            $result = $upload->upfile('show_image');
            if(!$result) {
                showMessage($upload->error);
            }
            $param['show_image'] = $upload->file_name;
            //删除老图片
            if(!empty($_POST['old_show_image'])) {
                $old_image = BASE_UPLOAD_PATH.DS.ATTACH_WHAT.DS.'shopwt'.DS.$_POST['old_show_image'];
                if(is_file($old_image)) {
                    unlink($old_image);
                }
            }
        } else {
            if(empty($_POST['show_id'])) {
                showMessage(Language::get('what_show_image_error'),'','','error');
            }
        }

        $model_show = Model("what_show");
        if(isset($_POST['show_id']) && intval($_POST['show_id']) > 0) {
            $result = $model_show->modify($param,array('show_id'=>$_POST['show_id']));
        } else {
            $result = $model_show->save($param);
        }
        if($result) {
            showMessage(Language::get('wt_common_save_succ'),"index.php?w=show&t=show_manage");
        } else {
            showMessage(Language::get('wt_common_save_fail'),"index.php?w=show&t=show_manage",'','error');
        }
    }

    /**
     * 广告删除
     */
    public function show_dropWt() {
        $model = Model('what_show');
        $condition = array();
        $condition['show_id'] = array('in',trim($_REQUEST['show_id']));

        //删除图片
        $list = $model->getList($condition);
        if(!empty($list)) {
            foreach ($list as $show_info) {
                //删除原始图片
                $image_name = BASE_UPLOAD_PATH.DS.ATTACH_WHAT.DS.'show'.DS.$show_info['show_image'];
                if(is_file($image_name)) {
                    unlink($image_name);
                }
            }
        }

        $result = $model->drop($condition);
        if($result) {
            showMessage(Language::get('wt_common_del_succ'),'');
        } else {
            showMessage(Language::get('wt_common_del_fail'),'','','error');
        }
    }

    /**
     * 广告排序
     */
    public function show_sort_updateWt() {
        if(intval($_GET['id']) <= 0) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        }
        $new_sort = intval($_GET['value']);
        if ($new_sort > 255){
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('class_sort_error')));
            die;
        } else {
            $model_class = Model("what_show");
            $result = $model_class->modify(array('show_sort'=>$new_sort),array('show_id'=>$_GET['id']));
            if($result) {
                echo json_encode(array('result'=>TRUE,'message'=>''));
                die;
            } else {
                echo json_encode(array('result'=>FALSE,'message'=>''));
                die;
            }
        }
    }


    //买什么广告类型列表
    private function get_show_type_list() {
        $show_type_list = array();
        $show_type_list['index'] = Language::get('what_show_type_index');
        $show_type_list['store_list'] = Language::get('what_show_type_store_list');
        Tpl::output('show_type_list',$show_type_list);
    }

    private function show_menu_show($menu_key) {
        $menu_array = array(
            'show_manage'=>array('menu_type'=>'link','menu_name'=>Language::get('wt_manage'),'menu_url'=>'index.php?w=show&t=show_manage'),
            'show_add'=>array('menu_type'=>'link','menu_name'=>Language::get('wt_new'),'menu_url'=>'index.php?w=show&t=show_add'),
        );

        if($menu_key == 'show_edit') {
            $menu_array['show_edit'] = array('menu_type'=>'link','menu_name'=>Language::get('wt_edit'),'menu_url'=>'###');
            unset($menu_array['show_add']);
        }
        $menu_array[$menu_key]['menu_type'] = 'text';
        Tpl::output('menu',$menu_array);
    }
}
