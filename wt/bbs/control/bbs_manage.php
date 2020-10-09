<?php
/**
 * 社区管理
 *
 *
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class bbs_manageControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('bbs');
    }

    public function indexWt() {
        $this->bbs_listWt();
    }
    /**
     * 社区列表
     */
    public function bbs_listWt(){
        Tpl::setDirquna('bbs');

Tpl::showpage('bbs.list');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlWt() {
        $model = Model();
        $condition = array();
        if ($_GET['type'] == 'verify') {
            $condition['bbs_status'] = 2;
        }
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('bbs_id', 'bbs_name', 'bbs_img', 'bbs_masterid', 'bbs_mastername', 'bbs_status', 'bbs_addtime'
                , 'is_recommend', 'is_hot', 'bbs_mcount', 'bbs_thcount'
        );
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $cirlce_list = $model->table('bbs')->where($condition)->order($order)->page($page)->select();

        // 圈主状态
        $status_array = $this->getbbsStatus();

        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
        foreach ($cirlce_list as $value) {
            $param = array();
            $operation = "<a class='btn red' href=\"javascript:void(0);\" onclick=\"fg_del('".$value['bbs_id']."')\"><i class='fa fa-trash-o'></i>删除</a>";
            if ($value['bbs_status'] == '2') {
                $operation .= "<a class='btn orange' href='index.php?w=bbs_manage&t=bbs_edit&c_id=".$value['bbs_id']."' class='url'><i class='fa fa-pencil-square-o'></i>审核</a>";
            } else {
                $operation .= "<a class='btn blue' href='index.php?w=bbs_manage&t=bbs_edit&c_id=".$value['bbs_id']."' class='url'><i class='fa fa-pencil-square-o'></i>编辑</a>";
            }
            $param['operation'] = $operation;
            $param['bbs_id'] = $value['bbs_id'];
            $param['bbs_name'] = $value['bbs_name'];
            $param['bbs_img'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".bbsLogo($value['bbs_id']).">\")'><i class='fa fa-picture-o'></i></a>";
            $param['bbs_masterid'] = $value['bbs_masterid'];
            $param['bbs_mastername'] = $value['bbs_mastername'];
            $param['bbs_status'] = $status_array[$value['bbs_status']];
            $param['bbs_addtime'] = date('Y-m-d H:i:s', $value['bbs_addtime']);
            $param['is_recommend'] = $value['is_recommend'] == '1' ? '是' : '否';
            $param['is_hot'] = $value['is_hot'] == '1' ? '是' : '否';
            $param['bbs_mcount'] = $value['bbs_mcount'];
            $param['bbs_thcount'] = $value['bbs_thcount'];
            $data['list'][$value['bbs_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 社区状态
     * @return multitype:string
     */
    private function getbbsStatus() {
        return array(
                '0' => '关闭',
                '1' => '开启',
                '2' => '审核中',
                '3' => '审核失败'
        );
    }

    /**
     * 社区待审核列表
     */
    public function bbs_verifyWt(){
        Tpl::setDirquna('bbs');

Tpl::showpage('bbs.verify');
    }
    /**
     * 社区新增
     */
    public function bbs_addWt(){
        $model = Model();
        // 保存
        if(chksubmit()){
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                    array("input"=>$_POST["c_name"], "require"=>"true", "message"=>L('bbs_name_not_null')),
            );
            $error = $obj_validate->validate();
            if($error != ''){
                showMessage($error);
            }else{
                $insert = array();
                $insert['bbs_name']      = trim($_POST['c_name']);
                $insert['bbs_masterid']  = intval($_POST['masterid']);
                $insert['bbs_mastername']= trim($_POST['mastername']);
                $insert['bbs_desc']      = $_POST['c_desc'];
                $insert['bbs_tag']       = $_POST['c_tag'];
                $insert['bbs_notice']    = $_POST['c_notice'];
                $insert['bbs_status']    = intval($_POST['c_status']);
                $insert['is_recommend']     = intval($_POST['c_recommend']);
                $insert['class_id']         = intval($_POST['classid']);
                $insert['bbs_joinaudit'] = 0;
                $insert['bbs_addtime']   = time();
                $insert['new_verifycount']  = 0;
                $insert['new_informcount']  = 0;
                $insert['mapply_open']      = 0;
                $insert['mapply_ml']        = 0;
                $insert['new_mapplycount']  = 0;
                $bbsid = $model->table('bbs')->insert($insert);
                if($bbsid){
                    // 把圈主信息加入社区会员表
                    $insert = array();
                    $insert['member_id']    = intval($_POST['masterid']);
                    $insert['bbs_id']    = $bbsid;
                    $insert['bbs_name']  = $_POST['c_name'];
                    $insert['member_name']  = $_POST['mastername'];
                    $insert['cm_applytime'] = $insert['cm_jointime'] = time();
                    $insert['cm_state']     = 1;
                    $insert['is_identity']  = 1;
                    $insert['cm_lastspeaktime'] = '';
                    $rs = $model->table('bbs_member')->insert($insert);
                    if($rs){
                        $update['bbs_mcount']    = 1;
                    }
                    if (!empty($_POST['c_img'])){
                        $update['bbs_img']   = $bbsid.'.jpg';
                        rename(BASE_UPLOAD_PATH.'/'.ATTACH_BBS.'/group/'.$_POST['c_img'],BASE_UPLOAD_PATH.'/'.ATTACH_BBS.'/group/'.$bbsid.'.jpg');
                    }
                    $model->table('bbs')->where(array('bbs_id'=>$bbsid))->update($update);
                    $this->log(L('wt_add_bbs').'['.$bbsid.']');
                    showMessage(L('wt_common_op_succ'));
                }else{
                    showMessage(L('wt_common_op_fail'));
                }
            }
        }
        // 社区分类
        $class_list = $model->table('bbs_class')->where(array('class_status'=>1))->order('class_sort asc')->select();
        Tpl::output('class_list', $class_list);

        Tpl::setDirquna('bbs');

Tpl::showpage('bbs.add');
    }
    /**
     * 社区编辑
     */
    public function bbs_editWt(){
        $model = Model();
        if(chksubmit()){
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                    array("input"=>$_POST["c_name"], "require"=>"true", "message"=>L('bbs_name_not_null')),
            );
            $error = $obj_validate->validate();
            if($error != ''){
                showMessage($error);
            }else{
                $update = array();
                $update['bbs_name']      = trim($_POST['c_name']);
                $update['bbs_masterid']  = intval($_POST['masterid']);
                $update['bbs_mastername']= trim($_POST['mastername']);
                $update['bbs_desc']      = $_POST['c_desc'];
                $insert['bbs_tag']       = $_POST['c_tag'];
                $update['bbs_notice']    = $_POST['c_notice'];
                $update['bbs_status']    = intval($_POST['c_status']);
                $update['bbs_statusinfo']= $_POST['c_statusinfo'];
                $update['is_recommend']     = intval($_POST['c_recommend']);
                $update['is_hot']           = intval($_POST['is_hot']);
                $update['class_id']         = intval($_POST['classid']);
                $insert['bbs_img']       = $_POST['c_img'];
                $rs = $model->table('bbs')->where(array('bbs_id'=>intval($_POST['c_id'])))->update($update);
                if($rs){
                    // 更新社区会员表 圈主信息。
                    $cminfo = $model->table('bbs_member')->where(array('member_id'=>intval($_POST['masterid']), 'bbs_id'=>intval($_POST['c_id'])))->find();
                    if(empty($cminfo)){
                        // 取消员圈主圈主身份
                        $model->table('bbs_member')->where(array('bbs_id'=>intval($_POST['c_id']), 'is_identity'=>1))->update(array('is_identity'=>3));
                        $model->table('bbs_theme')->where(array('bbs_id'=>intval($_POST['c_id']), 'is_identity'=>1))->update(array('is_identity'=>3));
                        // 把圈主信息加入社区会员表
                        $insert = array();
                        $insert['member_id']    = intval($_POST['masterid']);
                        $insert['bbs_id']    = intval($_POST['c_id']);
                        $insert['bbs_name']  = $_POST['c_name'];
                        $insert['member_name']  = $_POST['mastername'];
                        $insert['cm_applytime'] = $insert['cm_jointime'] = time();
                        $insert['cm_state']     = 1;
                        $insert['is_identity']  = 1;
                        $insert['cm_lastspeaktime'] = '';
                        $model->table('bbs_member')->insert($insert);
                    }else{
                        if($cminfo['is_identity'] != 1){
                            // 取消员圈主圈主身份
                            $model->table('bbs_member')->where(array('bbs_id'=>intval($_POST['c_id']), 'is_identity'=>1))->update(array('is_identity'=>3));
                            $model->table('bbs_theme')->where(array('bbs_id'=>intval($_POST['c_id']), 'is_identity'=>1))->update(array('is_identity'=>3));
                            // 任命新圈主
                            $model->table('bbs_member')->where(array('member_id'=>intval($_POST['masterid']), 'bbs_id'=>intval($_POST['c_id'])))->update(array('is_identity'=>1));
                            $model->table('bbs_theme')->where(array('member_id'=>intval($_POST['masterid']), 'bbs_id'=>intval($_POST['c_id'])))->update(array('is_identity'=>1));
                        }
                    }
                    // 更新社区成员信息
                    $count = $model->table('bbs_member')->where(array('bbs_id'=>intval($_POST['c_id'])))->count();
                    $model->table('bbs')->where(array('bbs_id'=>intval($_POST['c_id'])))->update(array('bbs_mcount'=>$count));

                    // 更新主题社区名称字段
                    $model->table('bbs_theme')->where(array('bbs_id'=>intval($_POST['c_id'])))->update(array('bbs_name'=>$_POST['c_name']));
                    $model->table('bbs_member')->where(array('bbs_id'=>intval($_POST['c_id'])))->update(array('bbs_name'=>$_POST['c_name']));

                    $this->log(L('wt_edit_bbs').'['.intval($_POST['c_id']).']');
                    showMessage(L('wt_common_op_succ'), 'index.php?w=bbs_manage&t=bbs_list');
                }else{
                    showMessage(L('wt_common_op_fail'));
                }
            }
        }
        $id = intval($_GET['c_id']);
        if($id <= 0){
            showMessage(L('param_error'));
        }
        // 社区详细
        $bbs_info = $model->table('bbs')->where(array('bbs_id'=>$id))->find();
        Tpl::output('bbs_info', $bbs_info);

        // 社区分类
        $class_list = $model->table('bbs_class')->where(array('class_status'=>1))->order('class_sort asc')->select();
        Tpl::output('class_list', $class_list);

        Tpl::setDirquna('bbs');

Tpl::showpage('bbs.edit');
    }
    /**
     * 选择圈主
     */
    public function choose_masterWt(){
        Tpl::output('search_url', (intval($_GET['c_id']) > 0) ? urlAdminbbs('bbs_manage', 'search_member', array('c_id' => intval($_GET['c_id']))) : urlAdminbbs('bbs_manage', 'search_member'));
        Tpl::setDirquna('bbs');
Tpl::showpage('bbs.choose_master', 'null_layout');
    }
    /**
     * 搜索会员
     */
    public function search_memberWt(){
        $model = Model();
        $where = array();
        if($_GET['name'] != ''){
            $where['member_name'] = array('like', '%'.trim($_GET['name']).'%');
        }
        $member_list = $model->table('member')->field('member_id,member_name')->where($where)->select();
        $member_list = array_under_reset($member_list, 'member_id', 1);

        // 允许创建社区验证
        $where = array();
        if(intval($_GET['c_id']) > 0){
            $where = array('bbs_id'=>array('neq',intval($_GET['c_id'])));
        }
        $count_array = $model->table('bbs')->field('bbs_masterid,count(*) as count')->where($where)->group('bbs_masterid')->select();
        if (!empty($count_array)){
            foreach ($count_array as $val){
                if(intval($val['count']) >= C('bbs_createsum')) unset($member_list[$val['bbs_masterid']]);
            }
        }

        // 允许加入社区验证
        $where = array();
        if(intval($_GET['c_id']) > 0){
            $where = array('bbs_id');
        }
        $count_array = $model->table('bbs_member')->field('member_id,count(*) as count')->where($where)->group('member_id')->select();
        if(!empty($count_array)){
            foreach ($count_array as $val){
                if(intval($val['count']) >= C('bbs_joinsum')) unset($member_list[$val['member_id']]);
            }
        }

        $member_list = array_values($member_list);
        // 加入社区数量验证
        if(strtoupper(CHARSET) == 'GBK'){
            $member_list = Language::getUTF8($member_list);
        }
        echo json_encode($member_list);die;
    }
    /**
     * 删除社区
     */
    public function bbs_delWt(){
        $id = intval($_GET['id']);
        if($id <= 0){
            exit(json_encode(array('state'=>false,'msg'=>L('param_error'))));
        }
        $model = Model();
        $bbs_info = $model->table('bbs')->where(array('bbs_id'=>$id))->find();
        if(!empty($bbs_info)) @unlink(BASE_UPLOAD_PATH.DS.ATTACH_BBS.'/group/'.$bbs_info['bbs_id'].'.jpg');

        // 删除附件
        $affix_list = $model->table('bbs_affix')->where(array('bbs_id'=>$id))->select();
        if(!empty($affix_list)){
            foreach ($affix_list as $val){
                @unlink(themeImagePath($val['affix_filename']));
                @unlink(themeImagePath($val['affix_filethumb']));
            }
            $model->table('bbs_affix')->where(array('bbs_id'=>$id))->delete();
        }

        // 删除商品
        $model->table('bbs_thg')->where(array('bbs_id'=>$id))->delete();

        // 删除赞表相关
        $model->table('bbs_like')->where(array('bbs_id'=>$id))->delete();

        // 删除回复
        $model->table('bbs_threply')->where(array('bbs_id'=>$id))->delete();

        // 删除话题
        $model->table('bbs_theme')->where(array('bbs_id'=>$id))->delete();

        // 删除成员
        $model->table('bbs_member')->where(array('bbs_id'=>$id))->delete();

        // 删除社区
        $model->table('bbs')->where(array('bbs_id'=>$id))->delete();

        $this->log(L('wt_del_bbs').'['.$id.']');
        exit(json_encode(array('state'=>true,'msg'=>L('wt_common_op_succ'))));
    }
    /**
     * 会员名称检测
     */
    public function check_memberWt() {
        $model = Model();
        $member_info = Model('member')->table('member')->where(array('member_name'=>trim($_GET['name']), 'member_id'=>intval($_GET['id'])))->select();
        if(empty($member_info)){
            echo 'false';exit;
        }else{
            // 允许创建数量验证
            $where = array();
            $where['bbs_masterid']   = intval($_GET['id']);
            if(intval($_GET['c_id']) > 0){
                $where['bbs_id']     = array('neq', intval($_GET['c_id']));
            }
            $count = $model->table('bbs')->where($where)->count();
            if(intval($count) >= intval(C('bbs_createsum'))){
                echo 'false';exit;
            }

            // 允许加入社区验证
            $where = array();
            $where['member_id'] = intval($_GET['id']);
            if(intval($_GET['c_id']) > 0){
                $where['bbs_id'] = array('neq', intval($_GET['c_id']));
            }
            $count = $model->table('bbs_member')->where($where)->count();
            if(intval($count) >= intval(C('bbs_joinsum'))){
                echo 'false';exit;
            }

            echo 'true';exit;
        }
    }
    /**
     * ajax操作
     */
    public function ajaxWt(){
        switch ($_GET['branch']){
            case 'status':
                $this->log(L('wt_bbs_pass_cerify').'['.intval($_GET['id']).']');
            case 'recommend':
                $update = array(
                    $_GET['column']=>$_GET['value']
                );
                Model()->table('bbs')->where(array('bbs_id'=>intval($_GET['id'])))->update($update);
                echo 'true';
                break;
            case 'name':
                $where  = array(
                    'bbs_id'=>intval($_GET['id'])
                );
                $update = array(
                    $_GET['column']=>$_GET['value']
                );
                Model()->table('bbs')->where($where)->update($update);
                Model()->table('bbs_theme')->where($where)->update($update);
                echo 'true';
                break;

        }
    }
    /**
     * ajax验证社区名称
     */
    public function check_bbs_nameWt(){
        $name = $_GET['name'];
        if (strtoupper(CHARSET) == 'GBK'){
            $name = Language::getGBK($name);
        }
        $where = array();
        $where['bbs_name']   = $name;
        if(intval($_GET['id']) > 0){
            $where['bbs_id'] = array('neq', intval($_GET['id']));
        }
        $rs = Model()->table('bbs')->where($where)->find();
        if (!empty($rs)){
            echo 'false';
        }else{
            echo 'true';
        }
    }
}
