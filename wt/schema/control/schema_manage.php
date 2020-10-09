<?php
/**
 * 分销商管理
 *
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @license    http://www.weisbao.com
 * @link       联系方式：13632978801
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class schema_manageControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('schema');
    }

    public function indexWt(){
        $this->schema_manageWt();
    }
   
    public function schema_manageWt() {
        $model_manage = Model('schema_manage');
        $manage_list = $model_manage->select();
        Tpl::output('manage_list', $manage_list);
        Tpl::setDirquna('schema');
        Tpl::showpage('schema_manage');
    }

    public function level_nameWt() {

        $level_name = Model('schema_level')->select();
        Tpl::output('level_name', $level_name); 
        Tpl::output('id', $_GET['id']);
        Tpl::setDirquna('schema');
        Tpl::showpage('schema_manage_edit');
    }

    public function level_name_aWt() {
        $level_name = Model('schema_level')->where(array('id'=>$_POST['level_id']))->find();
    
        $sql = "update lbsz_schema_manage set level_id = '{$_POST['level_id']}',level_name='{$level_name['level_name']}' where id={$_POST['manage_id']}";       
        $result = Db::query($sql);
        if($result){
            Tpl::setDirquna('schema');
            Tpl::showpage('schema_manage');
        }
    }
    /**
     * 输出XML数据
     */
    public function get_xmlWt() {
        $model = Model('schema_manage');
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('id', 'nickname', 'true_name', 'parent_name', 'level_name', 'distribute_total');
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
            $operation .= "<li><a href='index.php?act=schema_manage&op=level_name&id=".$value['id']."'>编辑</a></li>";            
            $operation .= "<li><a href='index.php?act=schema_manage&op=level_name&id=".$value['id']."'>编辑</a></li>";            
            $operation .= "</ul></span>";
            $param['operation'] = $operation;
            $param['nickname'] = $value['nickname'];
            $param['true_name'] = $value['true_name'];
            $parent = Model('member')->where(array('member_id'=>$value['parent_id']))->find();
            $param['parent_name'] = $parent['member_name'];
            $param['level_name'] = $value['level_name'];
            $param['distribute_total'] = $value['distribute_total'];

            $data['list'][$value['id']] = $param;
        }
//         var_dump($data);die;
        echo Tpl::flexigridXML($data);
    }
    
    /**
     * 删除等级
     */
    public function del_manageWt(){
        $id = trim($_REQUEST['del_id']);
        $manage_model = Model('schema_manage');
        $condition['id'] = array('in',$id);
        $result = $manage_model->where($condition)->delete();
        if($result) {
            dkcache('schema_manage');
            $this->log('删除等级['.$id.']', 1);
            showMessage('删除等级成功','');
        } else {
            showMessage('删除等级失败','','','error');
        }

    }

}
