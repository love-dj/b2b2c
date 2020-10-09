<?php
/**
 * 社区分类管理
 *
 *
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class bbs_classControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('bbs');
    }

    public function indexWt() {
        $this->class_listWt();
    }
    /**
     * 社区分类列表
     */
    public function class_listWt(){
        $model = Model();
        $where = array();
        if(trim($_GET['searchname']) != ''){
            $where['class_name']        = array('like', '%'.trim($_GET['searchname']).'%');
        }
        if(trim($_GET['searchstatus']) != ''){
            $where['class_status']      = intval($_GET['searchstatus']);
        }
        $class_list = $model->table('bbs_class')->where($where)->order('class_sort asc')->select();
        Tpl::output('class_list', $class_list);
         
Tpl::setDirquna('bbs');
Tpl::showpage('bbs_class.list');
    }
    /**
     * 社区分类添加
     */
    public function class_addWt(){
        $model = Model();
        if(chksubmit()){
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                    array("input"=>$_POST["class_name"], "require"=>"true", "message"=>L('bbs_class_name_not_null')),
                    array("input"=>$_POST["class_sort"], "require"=>"true", 'validator'=>'Number', "message"=>L('bbs_class_sort_is_number')),
            );
            $error = $obj_validate->validate();
            if($error != ''){
                showMessage($error);
            }else{
                $insert = array();
                $insert['class_name']       = trim($_POST['class_name']);
                $insert['class_sort']       = intval($_POST['class_sort']);
                $insert['class_status']     = intval($_POST['status']);
                $insert['is_recommend']     = intval($_POST['recommend']);
                $insert['class_addtime']    = time();
                $result = $model->table('bbs_class')->insert($insert);
                if($result){
                    $url = array(
                        array(
                            'url'=>'index.php?w=bbs_class&t=class_add',
                            'msg'=>L('bbs_continue_add'),
                        ),
                        array(
                            'url'=>'index.php?w=bbs_class&t=class_list',
                            'msg'=>L('bbs_return_list'),
                        )
                    );
                    showMessage(L('wt_common_op_succ'),$url);
                }else{
                    showMessage(L('wt_common_op_fail'));
                }
            }
        }
        // 商品分类
        $gc_list = Model('goods_class')->getGoodsClassListByParentId(0);
        Tpl::output('gc_list', $gc_list);

         
Tpl::setDirquna('bbs');
Tpl::showpage('bbs_class.add');
    }
    /**
     * 社区分类编辑
     */
    public function class_editWt(){
        $model = Model();
        if(chksubmit()){
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                    array("input"=>$_POST["class_name"], "require"=>"true", "message"=>L('bbs_class_name_not_null')),
                    array("input"=>$_POST["class_sort"], "require"=>"true", 'validator'=>'Number', "message"=>L(’bbs_class_sort_is_number)),
            );
            $error = $obj_validate->validate();
            if($error != ''){
                showMessage($error);
            }else{
                $update = array();
                $update['class_name']   = trim($_POST['class_name']);
                $update['class_sort']   = intval($_POST['class_sort']);
                $update['class_status'] = intval($_POST['status']);
                $update['is_recommend'] = intval($_POST['recommend']);
                $result = $model->table('bbs_class')->where(array('class_id'=>intval($_POST['class_id'])))->update($update);
                if($result){
                    showMessage(L('wt_common_op_succ'),'index.php?w=bbs_class&t=class_list');
                }else{
                    showMessage(L('wt_common_op_fail'));
                }
            }
        }
        $id = intval($_GET['classid']);
        if($id <= 0){
            showMessage(L('param_error'));
        }
        $class_info = $model->table('bbs_class')->where(array('class_id'=>$id))->find();

        Tpl::output('class_info', $class_info);
        // 商品分类
        $gc_list = Model('goods_class')->getGoodsClassListByParentId(0);
        Tpl::output('gc_list', $gc_list);

         
Tpl::setDirquna('bbs');
Tpl::showpage('bbs_class.edit');
    }
    /**
     * 删除分类
     */
    public function class_delWt(){
        $ids = explode(',', $_GET['id']);
        if (count($ids) == 0){
            exit(json_encode(array('state'=>false,'msg'=>L('wrong_argument'))));
        }
        Model()->table('bbs_class')->where(array('class_id'=>array('in', $ids)))->delete();
        exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
    }
    /**
     * ajax操作
     */
    public function ajaxWt(){
        switch ($_GET['branch']){
            case 'recommend':
            case 'status':
            case 'sort':
            case 'name':
                $update = array(
                    $_GET['column']=>$_GET['value']
                );
                Model()->table('bbs_class')->where(array('class_id'=>intval($_GET['id'])))->update($update);
                echo 'true';
                break;
        }
    }
}
