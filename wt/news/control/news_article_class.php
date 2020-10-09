<?php
/**
 * 内容管理系统 文章分类
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class news_article_classControl extends SystemControl{

    public function __construct(){
        parent::__construct();
        Language::read('news');
    }

    public function indexWt() {
        $this->news_article_class_listWt();
    }

    /**
     * news文章分类列表
     **/
    public function news_article_class_listWt() {
        $model = Model('news_article_class');
        $list = $model->getList(TRUE);
        $this->show_menu('list');
        Tpl::output('list',$list);
        Tpl::setDirquna('news');
Tpl::showpage("news_article_class.list");
    }

    /**
     * news文章分类添加
     **/
    public function news_article_class_addWt() {
        $this->show_menu('add');
        Tpl::setDirquna('news');
Tpl::showpage('news_article_class.add');
    }

    /**
     * news文章分类保存
     **/
    public function news_article_class_saveWt() {
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
        $model_class = Model('news_article_class');
        $result = $model_class->save($param);
        if($result) {
            $this->log(Language::get('news_log_article_class_save').$result, 1);
            showMessage(Language::get('class_add_success'),'index.php?w=news_article_class&t=news_article_class_list');
        } else {
            $this->log(Language::get('news_log_article_class_save').$result, 0);
            showMessage(Language::get('class_add_fail'),'index.php?w=news_article_class&t=news_article_class_list','','error');
        }


    }

    /**
     * news文章分类排序修改
     */
    public function update_class_sortWt() {
        if(intval($_GET['id']) <= 0) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        }
        $new_sort = intval($_GET['value']);
        if ($new_sort > 255){
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('class_sort_error')));
            die;
        } else {
            $model_class = Model("news_article_class");
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

    public function update_class_nameWt() {
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
            $model_class = Model("news_article_class");
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
     * news文章分类删除
     **/
     public function news_article_class_dropWt() {
        $class_id = trim($_REQUEST['class_id']);
        $model_class = Model('news_article_class');
        $condition = array();
        $condition['class_id'] = array('in',$class_id);
        $result = $model_class->drop($condition);
        if($result) {
            $this->log(Language::get('news_log_article_class_drop').$_REQUEST['class_id'], 1);
            showMessage(Language::get('class_drop_success'),'');
        } else {
            $this->log(Language::get('news_log_article_class_drop').$_REQUEST['class_id'], 0);
            showMessage(Language::get('class_drop_fail'),'','','error');
        }

     }

    private function show_menu($menu_key) {
        $menu_array = array(
            'list'=>array('menu_type'=>'link','menu_name'=>Language::get('wt_list'),'menu_url'=>'index.php?w=news_article_class&t=news_article_class_list'),
            'add'=>array('menu_type'=>'link','menu_name'=>Language::get('wt_new'),'menu_url'=>'index.php?w=news_article_class&t=news_article_class_add'),
        );
        $menu_array[$menu_key]['menu_type'] = 'text';
        Tpl::output('menu',$menu_array);
    }


}
