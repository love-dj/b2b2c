<?php
/**
 * 文章分类
 *
 *
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class article_classControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('article_class');
    }

    public function indexWt() {
        $this->article_classWt();
    }

    /**
     * 文章管理
     */
    public function article_classWt(){
        $model_class = Model('article_class');
        /**
         * 列表
         */
        $tmp_list = $model_class->getTreeClassList(2);
        //dumps($tmp_list);
        if (is_array($tmp_list)){
            foreach ($tmp_list as $k => $v){
                if ($v['ac_id'] == 0 || $v['ac_parent_id'] == 1) {
                    continue;
                }
                /**
                 * 判断是否有子类
                 */
                if ($tmp_list[$k+1]['deep'] > $v['deep']){
                    $v['have_child'] = 1;
                }
                $class_list[] = $v;
            }
        }
        //dumps($class_list);die;
        Tpl::output('class_list',$class_list);
		Tpl::setDirquna('system');
        Tpl::showpage('article_class.index');
    }

    /**
     * 文章分类 新增
     */
    public function article_class_addWt(){
        $lang   = Language::getLangContent();
        $model_class = Model('article_class');
        if (chksubmit()){
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["ac_name"], "require"=>"true", "message"=>$lang['article_class_add_name_null']),
                array("input"=>$_POST["ac_sort"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['article_class_add_sort_int']),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {

                $insert_array = array();
                $insert_array['ac_name'] = trim($_POST['ac_name']);
                $insert_array['ac_parent_id'] = intval($_POST['ac_parent_id']);
                $insert_array['ac_sort'] = trim($_POST['ac_sort']);

                $result = $model_class->add($insert_array);
                if ($result){
                    $url = array(
                        array(
                            'url'=>'index.php?w=article_class&t=article_class_add&ac_parent_id='.intval($_POST['ac_parent_id']),
                            'msg'=>$lang['article_class_add_class'],
                        ),
                        array(
                            'url'=>'index.php?w=article_class&t=article_class',
                            'msg'=>$lang['article_class_add_back_to_list'],
                        )
                    );
                    $this->log(l('wt_add,article_class_index_class').'['.$_POST['ac_name'].']',1);
                    showMessage($lang['article_class_add_succ'],$url);
                }else {
                    showMessage($lang['article_class_add_fail']);
                }
            }
        }

        $parent_list = $model_class->getTreeClassList(1);
        if (is_array($parent_list)){
            foreach ($parent_list as $k => $v){
                if ($v['ac_id'] == 1) {
                    unset($parent_list[$k]);
                    continue;
                }
                $parent_list[$k]['ac_name'] = str_repeat("&nbsp;",$v['deep']*2).$v['ac_name'];
            }
        }

        Tpl::output('ac_parent_id',intval($_GET['ac_parent_id']));
        Tpl::output('parent_list',$parent_list);
		Tpl::setDirquna('system');
        Tpl::showpage('article_class.add');
    }

    /**
     * 文章分类编辑
     */
    public function article_class_editWt(){
        $lang   = Language::getLangContent();
        $model_class = Model('article_class');

        if (chksubmit()){
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["ac_name"], "require"=>"true", "message"=>$lang['article_class_add_name_null']),
                array("input"=>$_POST["ac_sort"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['article_class_add_sort_int']),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {

                $update_array = array();
                $update_array['ac_id'] = intval($_POST['ac_id']);
                $update_array['ac_name'] = trim($_POST['ac_name']);
//              $update_array['ac_parent_id'] = intval($_POST['ac_parent_id']);
                $update_array['ac_sort'] =trim($_POST['ac_sort']);

                $result = $model_class->updates($update_array);
                if ($result){
                    $url = array(
                        array(
                            'url'=>'index.php?w=article_class&t=article_class',
                            'msg'=>$lang['article_class_add_back_to_list'],
                        ),
                        array(
                            'url'=>'index.php?w=article_class&t=article_class_edit&ac_id='.intval($_POST['ac_id']),
                            'msg'=>$lang['article_class_edit_again'],
                        ),
                    );
                    $this->log(l('wt_edit,article_class_index_class').'['.$_POST['ac_name'].']',1);
                    showMessage($lang['article_class_edit_succ'],'index.php?w=article_class&t=article_class');
                }else {
                    showMessage($lang['article_class_edit_fail']);
                }
            }
        }

        $class_array = $model_class->getOneClass(intval($_GET['ac_id']));
        if (empty($class_array)){
            showMessage($lang['param_error']);
        }

        Tpl::output('class_array',$class_array);
		Tpl::setDirquna('system');
        Tpl::showpage('article_class.edit');
    }

    /**
     * 删除分类
     */
    public function article_class_delWt(){
        $lang   = Language::getLangContent();
        $model_class = Model('article_class');
        if (intval($_GET['ac_id']) > 0){
            $array = array(intval($_GET['ac_id']));

            $del_array = $model_class->getChildClass($array);
            if (is_array($del_array)){
                foreach ($del_array as $k => $v){
                    $model_class->del($v['ac_id']);
                }
            }
            $this->log(l('wt_add,article_class_index_class').'[ID:'.intval($_GET['ac_id']).']',1);
            showMessage($lang['article_class_index_del_succ'],'index.php?w=article_class&t=article_class');
        }else {
            showMessage($lang['article_class_index_choose'],'index.php?w=article_class&t=article_class');
        }
    }
    /**
     * ajax操作
     */
    public function ajaxWt(){
        switch ($_GET['branch']){
            /**
             * 分类：验证是否有重复的名称
             */
            case 'article_class_name':
                $model_class = Model('article_class');
                $class_array = $model_class->getOneClass(intval($_GET['id']));

                $condition['ac_name'] = trim($_GET['value']);
                $condition['ac_parent_id'] = $class_array['ac_parent_id'];
                $condition['no_ac_id'] = intval($_GET['id']);
                $class_list = $model_class->getClassList($condition);
                if (empty($class_list)){
                    $update_array = array();
                    $update_array['ac_id'] = intval($_GET['id']);
                    $update_array['ac_name'] = trim($_GET['value']);
                    $model_class->updates($update_array);
                    echo 'true';exit;
                }else {
                    echo 'false';exit;
                }
                break;
            /**
             * 分类： 排序 显示 设置
             */
            case 'article_class_sort':
                $model_class = Model('article_class');
                $update_array = array();
                $update_array['ac_id'] = intval($_GET['id']);
                $update_array[$_GET['column']] = trim($_GET['value']);
                $result = $model_class->updates($update_array);
                echo 'true';exit;
                break;
            /**
             * 分类：添加、修改操作中 检测类别名称是否有重复
             */
            case 'check_class_name':
                $model_class = Model('article_class');
                $condition['ac_name'] = trim($_GET['ac_name']);
                $condition['ac_parent_id'] = intval($_GET['ac_parent_id']);
                $condition['no_ac_id'] = intval($_GET['ac_id']);
                $class_list = $model_class->getClassList($condition);
                if (empty($class_list)){
                    echo 'true';exit;
                }else {
                    echo 'false';exit;
                }
                break;
        }
    }
}
