<?php
/**
 * 广告管理
 *
 *
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class showControl extends SystemControl{
	private $links = array(
        array('url'=>'w=show&t=ap_manage','lang'=>'show_index_manage'),
		array('url'=>'w=show&t=rec_list','lang'=>'rec_position'),
        array('url'=>'w=show&t=banner','lang'=>'top_set'),
    );
	
    public function __construct(){
        parent::__construct();
        Language::read('show');
    }

    public function indexWt() {
        $this->ap_manageWt();
    }

    /**
     *
     * 管理员添加广告
     */
    public function show_addWt(){
        if(!chksubmit()){
            $show  = Model('show');
            /**
             * 取广告位信息
             */
            $ap_list = $show->getApList();
            Tpl::output('ap_list',$ap_list);
			Tpl::setDirquna('system');
            Tpl::showpage('show.add');
        }else{
            $lang = Language::getLangContent();
            $show  = Model('show');
            $upload     = new UploadFile();
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $validate_arr = array();
            $validate_arr[] = array("input"=>$_POST["show_name"], "require"=>"true", "message"=>$lang['show_can_not_null']);
            $validate_arr[] = array("input"=>$_POST["aptype_hidden"], "require"=>"true", "message"=>$lang['must_select_ap']);
            $validate_arr[] = array("input"=>$_POST["ap_id"], "require"=>"true", "message"=>$lang['must_select_ap']);
            $validate_arr[] = array("input"=>$_POST["show_start_time"], "require"=>"true", "message"=>$lang['must_select_start_time']);
            $validate_arr[] = array("input"=>$_POST["show_end_time"], "require"=>"true", "message"=>$lang['must_select_end_time']);
            if ($_POST["aptype_hidden"] == '1'){
                //文字广告
                $validate_arr[] = array("input"=>$_POST["show_word"], "require"=>"true", "message"=>$lang['textshow_null_error']);
            }elseif ($_POST["aptype_hidden"] == '3'){
                //flash广告
                $validate_arr[] = array("input"=>$_FILES['flash_swf']['name'], "require"=>"true", "message"=>$lang['flashshow_null_error']);
            }else {
                //图片广告
                $validate_arr[] = array("input"=>$_FILES['show_pic']['name'], "require"=>"true", "message"=>$lang['picshow_null_error']);
            }
            $obj_validate->validateparam = $validate_arr;
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $insert_array['show_title']       = trim($_POST['show_name']);
                $insert_array['ap_id']           = intval($_POST['ap_id']);
                $insert_array['show_start_date']  = $this->getunixtime($_POST['show_start_time']);
                $insert_array['show_end_date']    = $this->getunixtime($_POST['show_end_time']);
                $insert_array['is_allow']        = '1';
                /**
                 * 建立文字广告信息的入库数组
                 */
                //判断页面编码确定汉字所占字节数
                switch (CHARSET){
                   case 'UTF-8':
                      $charrate = 3;
                      break;
                   case 'GBK':
                      $charrate = 2;
                      break;
                }
                //图片广告
                if($_POST['aptype_hidden'] == '0'){
                    $upload->set('default_dir',ATTACH_SHOW);
                    $result = $upload->upfile('show_pic');
                    if (!$result){
                        showMessage($upload->error,'','','error');
                    }
                    $ac = array(
                    'show_pic'     =>$upload->file_name,
                    'show_pic_url' =>trim($_POST['show_pic_url'])
                    );
                    $ac = serialize($ac);
                    $insert_array['show_content'] = $ac;
                }
                //文字广告
                if($_POST['aptype_hidden'] == '1'){
                    if(strlen($_POST['show_word'])>($_POST['show_word_len']*$charrate)){
                        $error = $lang['wordshow_toolong'];
                        showMessage($error);die;
                    }
                    $ac = array(
                    'show_word'    =>trim($_POST['show_word']),
                    'show_word_url'=>trim($_POST['show_word_url'])
                    );
                    $ac = serialize($ac);
                    $insert_array['show_content'] = $ac;
                }
                //建立Flash广告信息的入库数组
                if($_POST['aptype_hidden'] == '3'){
                    $upload->set('default_dir',ATTACH_SHOW);
                    $upload->upfile('flash_swf');
                    $ac = array(
                    'flash_swf'  =>$upload->file_name,
                    'flash_url'  =>trim($_POST['flash_url'])
                    );
                    $ac = serialize($ac);
                    $insert_array['show_content'] = $ac;
                }
                //广告信息入库
                $result = $show->show_add($insert_array);
                //更新相应广告位所拥有的广告数量
                $condition['ap_id'] = intval($_POST['ap_id']);
                $ap_list = $show->getApList($condition);
                $ap_list = $ap_list['0'];
                $show_num = $ap_list['show_num'];
                $param['ap_id']   = intval($_POST['ap_id']);
                $param['show_num'] = $show_num+1;
                $result2 = $show->ap_update($param);

                if ($result&&$result2){
                    $this->log(L('show_add_succ').'['.$_POST["show_name"].']',null);
                    showMessage($lang['show_add_succ'],'index.php?w=show&t=show&ap_id='.$_POST['ap_id']);
                }else {
                    showMessage($lang['show_add_fail'],'index.php?w=show&t=show&ap_id='.$_POST['ap_id']);
                }
        }
      }
    }

    /**
     *
     * 管理广告位
     */
    public function ap_manageWt(){
		 //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'ap_manage'));
		Tpl::setDirquna('system');
        Tpl::showpage('ap_manage');
    }

    public function get_ap_xmlWt(){
        $lang = Language::getLangContent();
        $show  = Model('show');
        $condition  = array();
        if ($_POST['query'] != '' && in_array($_POST['qtype'],array('ap_name'))) {
            $condition[$_POST['qtype']] = $_POST['query'];
        }
        $sort_fields = array('ap_class','ap_display','is_use');
        if ($_POST['sortorder'] != '' && in_array($_POST['sortname'],$sort_fields)) {
            $order = $_POST['sortname'].' '.$_POST['sortorder'];
        }
        $page = new Page();
        $page->setEachNum($_POST['rp']);
        $page->setStyle('admin');
        $ap_list  = $show->getApList($condition,$page,$order);
        $show_list = $show->getList();
        $data = array();
        $data['now_page'] = $page->get('now_page');
        $data['total_num'] = $page->get('total_num');
        foreach ((array)$ap_list as $k => $ap_info) {
            $list = array();$operation_detail = '';
            $list['operation'] = "<a class='btn red' onclick=\"fg_delete({$ap_info['ap_id']})\"><i class='fa fa-trash-o'></i>删除</a>";
            $operation_detail = "<li><a href=\"index.php?w=show&t=show&ap_id={$ap_info['ap_id']}\"></i>管理广告</a></li>";
            $operation_detail .= "<li><a href='index.php?w=show&t=ap_edit&ap_id={$ap_info['ap_id']}'>编辑属性</a></li>";
            $operation_detail .= "<li><a onclick=\"copyToClipBoard('{$ap_info['ap_id']}');\" href=\"javascript:void(0)\">代码调用</a></li>";
            if ($operation_detail) {
                $list['operation'] .= "<span class='btn'><em><i class='fa fa-cog'></i>设置 <i class='arrow'></i></em><ul>{$operation_detail}</ul>";
            }
            $list['ap_name'] = $ap_info['ap_name'];
            $list['ap_class'] = str_replace(array(0,1,3), array('图片','文字','Flash'),$ap_info['ap_class']);
            $list['ap_display'] = str_replace(array(0,1,2), array($lang['ap_slide_show'],$lang['ap_mul_show'],$lang['ap_one_show']),$ap_info['ap_display']);
            $list['ap_width'] = $ap_info['ap_width'];
            $list['ap_height'] = $ap_info['ap_class'] == '1' ? '' : $ap_info['ap_height'];

            $ap_now_count = 0;$ap_count = 0;
            $time = time();
            if(!empty($show_list)){
                foreach ($show_list as $show_k => $show_v){
                    if($show_v['ap_id'] == $ap_info['ap_id'] && $show_v['show_end_date'] > $time && $show_v['show_start_date'] < $time && $show_v['is_allow'] == '1'){
                        $ap_now_count++;
                    }
                    if($show_v['ap_id'] == $ap_info['ap_id']){
                        $ap_count++;
                    }
                }}
            $list['ap_count'] = $ap_count;
            $list['ap_now_count'] = $ap_now_count;
            $list['is_use'] = $ap_info['is_use'] ? '<span class="yes"><i class="fa fa-check-bbs"></i>是</span>' : '<span class="no"><i class="fa fa-check-bbs"></i>否</span>';
            $data['list'][$ap_info['ap_id']] = $list;
        }
        exit(Tpl::flexigridXML($data));
    }

    /**
     * js代码调用
     */
    public function ap_copyWt(){
		Tpl::setDirquna('system');
        Tpl::showpage('ap_copy', 'null_layout');
    }

    public function deleteWt() {
         if (preg_match('/^[\d,]+$/', $_GET['del_id'])) {
            $show = Model('show');
            $where  = "where show_id in (".$_GET['del_id'].")";
            Model('show')-> ap_del("show",$where);
            foreach (explode(',',$_GET['del_id']) as $v) {
                if (!empty($v)) {
                    $result  = $show->ap_del(intval($v));
                }
            }
            $this->log('删除广告位成功'.'[ID:'.$_GET['del_id'].']',null);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    public function show_deleteWt() {
        if (preg_match('/^[\d,]+$/', $_GET['del_id'])) {
            $show = Model('show');
            $where  = "where show_id in (".$_GET['del_id'].")";
            Model('show')-> show_del("show",$where);
			
            foreach (explode(',',$_GET['del_id']) as $v) {
                if (!empty($v)) {
                    $result  = $show->show_del(intval($v));
                }
            }
            $this->log('删除广告成功'.'[ID:'.$_GET['del_id'].']',null);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     *
     * 修改广告位
     */
    public function ap_editWt(){
        if (empty($_GET['ap_id'])) {
            showMessage('参数错误');
        }
        if(!chksubmit()){
             $show  = Model('show');
             $condition['ap_id'] = intval($_GET['ap_id']);
             $ap_list = $show->getApList($condition);
             Tpl::output('ref_url',getReferer());
             Tpl::output('ap_list',$ap_list);
			 Tpl::setDirquna('system');
             Tpl::showpage('ap_edit');
        }else{
            $lang = Language::getLangContent();
            $show  = Model('show');
            $upload     = new UploadFile();

            $obj_validate = new Validate();
            if($_POST['ap_class'] == '1'){
                $obj_validate->validateparam = array(
                array("input"=>$_POST["ap_name"], "require"=>"true", "message"=>$lang['ap_can_not_null']),
                array("input"=>$_POST["ap_width"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['ap_width_must_num']),
                );
            }else{
                $obj_validate->validateparam = array(
                array("input"=>$_POST["ap_name"], "require"=>"true", "message"=>$lang['ap_can_not_null']),
                array("input"=>$_POST["ap_width"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['ap_width_must_num']),
                array("input"=>$_POST["ap_height"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['ap_height_must_num']),
                );
            }

            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $param['ap_id']      = intval($_GET['ap_id']);
                $param['ap_name']    = trim($_POST["ap_name"]);
                $param['ap_width']   = intval(trim($_POST["ap_width"]));
                $param['ap_height']  = intval(trim($_POST["ap_height"]));
                if($_POST["ap_display"] != ''){
                    $param['ap_display'] = intval($_POST["ap_display"]);
                }
                if($_POST["is_use"] != ''){
                    $param['is_use']     = intval($_POST["is_use"]);
                }
                if($_FILES['default_pic']['name'] != ''){
                    $upload->set('default_dir',ATTACH_SHOW);
                    $result = $upload->upfile('default_pic');
                    if (!$result){
                        showMessage($upload->error,'','','error');
                    }
                    $param['default_content'] = $upload->file_name;
                }
                if($_POST['default_word'] != ''){
                    $param['default_content'] = trim($_POST['default_word']);
                }
                $result = $show->ap_update($param);

                if ($result){
                    $this->log(L('ap_change_succ').'['.$_POST["ap_name"].']',null);
                    showMessage($lang['ap_change_succ'],$_POST['ref_url']);
                }else {
                    showMessage($lang['ap_change_fail'] ,$url);
                }
            }
        }
    }

    /**
     *
     * 新增广告位
     */
    public function ap_addWt(){
        if($_POST['form_submit'] != 'ok'){
			Tpl::setDirquna('system');
            Tpl::showpage('ap_add');
        }else{
            $lang = Language::getLangContent();
            $show  = Model('show');
            $upload     = new UploadFile();

            $obj_validate = new Validate();
            if($_POST['ap_class'] == '1'){
                $obj_validate->validateparam = array(
                array("input"=>$_POST["ap_name"], "require"=>"true", "message"=>$lang['ap_can_not_null']),
                array("input"=>$_POST["ap_width_word"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['ap_wordwidth_must_num']),
                array("input"=>$_POST["default_word"], "require"=>"true", "message"=>$lang['default_word_can_not_null']),
            );
            }else{
                $obj_validate->validateparam = array(
                array("input"=>$_POST["ap_name"], "require"=>"true", "message"=>$lang['ap_can_not_null']),
                array("input"=>$_POST["ap_width_media"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['ap_width_must_num']),
                array("input"=>$_POST["ap_height"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['ap_height_must_num']),
                array("input"=>$_FILES["default_pic"], "require"=>"true", "message"=>$lang['default_pic_can_not_null']),
            );
            }
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $insert_array['ap_name']    = trim($_POST['ap_name']);
                $insert_array['ap_class']   = intval($_POST['ap_class']);
                $insert_array['ap_display'] = intval($_POST['ap_display']);
                $insert_array['is_use']     = intval($_POST['is_use']);
                if($_POST['ap_width_media'] != ''){
                    $insert_array['ap_width']  = intval(trim($_POST['ap_width_media']));
                }
                if($_POST['ap_width_word'] != ''){
                    $insert_array['ap_width']  = intval(trim($_POST['ap_width_word']));
                }
                if($_POST['default_word'] != ''){
                    $insert_array['default_content'] = trim($_POST['default_word']);
                }
                if($_FILES['default_pic']['name'] != ''){
                    $upload->set('default_dir',ATTACH_SHOW);
                    $result = $upload->upfile('default_pic');
                    if (!$result){
                        showMessage($upload->error,'','','error');
                    }
                    $insert_array['default_content'] = $upload->file_name;
                }
                $insert_array['ap_height'] = intval(trim($_POST['ap_height']));
                $result  = $show->ap_add($insert_array);

                if ($result){
                    $this->log(L('ap_add_succ').'['.$_POST["ap_name"].']',null);
                    showMessage($lang['ap_add_succ'],'index.php?w=show&t=ap_manage','html','succ',1,4000);
                }else {
                    showMessage($lang['ap_add_fail']);
                }
            }
        }
    }

    /**
     *
     * 广告管理
     */
    public function showWt(){
        Tpl::output('ap_name',Model()->table('show_position')->getfby_ap_id(intval($_GET['ap_id']),'ap_name'));
		 //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'base'));
		Tpl::setDirquna('system');
        Tpl::showpage('show.index');
    }

    public function get_show_xmlWt(){
        $lang = Language::getLangContent();
        $show  = Model('show');
        $condition  = array();
        $condition['ap_id'] = intval($_GET['ap_id']);
        if ($_POST['query'] != '' && in_array($_POST['qtype'],array('show_title'))) {
            $condition[$_POST['qtype']] = $_POST['query'];
        }
        $sort_fields = array('ap_id','show_start_date','show_end_date');
        if ($_POST['sortorder'] != '' && in_array($_POST['sortname'],$sort_fields)) {
            $order = $_POST['sortname'].' '.$_POST['sortorder'];
        }
        $condition['is_allow'] = '1';
        $page = new Page();
        $page->setEachNum($_POST['rp']);
        $page->setStyle('admin');
        $show_list  = $show->getList($condition,$page,'',$order);
        $ap_list = $show->getApList();
        $data = array();
        $data['now_page'] = $page->get('now_page');
        $data['total_num'] = $page->get('total_num');
        foreach ((array)$show_list as $k => $show_info) {
            $list = array();$operation_detail = '';
            $list['operation'] = "<a class='btn red' onclick=\"fg_delete({$show_info['show_id']})\"><i class='fa fa-trash-o'></i>删除</a><a class='btn blue' href='index.php?w=show&t=show_edit&show_id={$show_info['show_id']}'><i class='fa fa-pencil-square-o'></i>编辑</a>";
            $list['show_title'] = $show_info['show_title'];
            $list['ap_id'] = $list['ap_class'];
            $list['ap_class'] = '';
            foreach ($ap_list as $ap_k => $ap_v){
                if($show_info['ap_id'] == $ap_v['ap_id']){
                    $list['ap_id'] = $ap_v['ap_name'];
                    $list['ap_class'] = str_replace(array(0,1,3), array('图片','文字','Flash'),$ap_v['ap_class']);
                    break;
                }
            }
            $list['show_start_date'] = date('Y-m-d',$show_info['show_start_date']);
            $list['show_end_date'] = date('Y-m-d',$show_info['show_end_date']);
            $data['list'][$show_info['show_id']] = $list;
        }
        exit(Tpl::flexigridXML($data));
    }

    /**
     *
     * 修改广告
     */
    public function show_editWt(){
        if (empty($_GET['show_id'])) {
            showMessage('参数错误');
        }
        if($_POST['form_submit'] != 'ok'){
             $show  = Model('show');
             $condition['show_id'] = intval($_GET['show_id']);
             $show_list = $show->getList($condition);
             $ap_info  = $show->getApList();
             Tpl::output('ref_url',getReferer());
             Tpl::output('show_list',$show_list);
             Tpl::output('ap_info',$ap_info);
			 Tpl::setDirquna('system');
             Tpl::showpage('show.edit');
        }else{
            $lang = Language::getLangContent();
            $show  = Model('show');
            $upload     = new UploadFile();
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["show_name"], "require"=>"true", "message"=>$lang['ap_can_not_null']),
                array("input"=>$_POST["show_start_date"], "require"=>"true","message"=>$lang['must_select_start_time']),
                array("input"=>$_POST["show_end_date"], "require"=>"true", "message"=>$lang['must_select_end_time'])
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $param['show_id']         = intval($_GET['show_id']);
                $param['show_title']      = trim($_POST['show_name']);
                $param['show_start_date'] = $this->getunixtime(trim($_POST['show_start_date']));
                $param['show_end_date']   = $this->getunixtime(trim($_POST['show_end_date']));
                /**
                  * 建立图片广告信息的入库数组
                  */
                if($_POST['mark'] == '0'){
                if($_FILES['show_pic']['name'] != ''){
                    $upload->set('default_dir',ATTACH_SHOW);
                    $result = $upload->upfile('show_pic');
                    if (!$result){
                        showMessage($upload->error,'','','error');
                    }
                    $ac = array(
                    'show_pic'     =>$upload->file_name,
                    'show_pic_url' =>trim($_POST['show_pic_url'])
                    );
                    $ac = serialize($ac);
                    $param['show_content'] = $ac;
                }else{
                    $ac = array(
                    'show_pic'     =>trim($_POST['pic_ori']),
                    'show_pic_url' =>trim($_POST['show_pic_url'])
                    );
                    $ac = serialize($ac);
                    $param['show_content'] = $ac;
                }
                }
               /**
                 * 建立文字广告信息的入库数组
                 */
                if($_POST['mark'] == '1'){
                //判断页面编码确定汉字所占字节数
                switch (CHARSET){
                   case 'UTF-8':
                        $charrate = 3;
                        break;
                   case 'GBK':
                        $charrate = 2;
                        break;
                }
                if(strlen($_POST['show_word'])>($_POST['show_word_len']*$charrate)){
                        $error = $lang['wordshow_toolong'];
                        showMessage($error);die;
                }
                    $ac = array(
                    'show_word'    =>trim($_POST['show_word']),
                    'show_word_url'=>trim($_POST['show_word_url'])
                    );
                    $ac = serialize($ac);
                    $param['show_content'] = $ac;
                }
                /**
                 * 建立Flash广告信息的入库数组
                 */
                if($_POST['mark'] == '3'){
                if($_FILES['flash_swf']['name'] != ''){
                    $upload->set('default_dir',ATTACH_SHOW);
                    $result = $upload->upfile('flash_swf');
                    $ac = array(
                    'flash_swf'  =>$upload->file_name,
                    'flash_url'  =>trim($_POST['flash_url'])
                    );
                    $ac = serialize($ac);
                    $param['show_content'] = $ac;
                 }else{
                    $ac = array(
                    'flash_swf'  =>trim($_POST['flash_ori']),
                    'flash_url'  =>trim($_POST['flash_url'])
                    );
                    $ac = serialize($ac);
                    $param['show_content'] = $ac;
                 }
                }
                $result = $show->updates($param);

                if ($result){
                    $url = array(
                        array(
                            'url'=>trim($_POST['ref_url']),
                            'msg'=>$lang['goback_ap_manage'],
                        )
                    );
                    $this->log(L('show_change_succ').'['.$_POST["show_name"].']',null);
                    showMessage($lang['show_change_succ'],$url);
                }else {
                    showMessage($lang['show_change_fail'],$url);
                }
            }
        }
    }

    /**
     *
     * 获取UNIX时间戳
     */
    public function getunixtime($time){
        $array     = explode("-", $time);
        $unix_time = mktime(0,0,0,$array[1],$array[2],$array[0]);
        return $unix_time;
    }

    /**
     *
     * ajaxOp
     */
    public function ajaxWt(){
        switch ($_GET['branch']){
            case 'is_use':
            $show=Model('show');
            $param[trim($_GET['column'])]=intval($_GET['value']);
            $param['ap_id']=intval($_GET['id']);
            $show->ap_update($param);

            echo 'true';exit;
            break;
        }
    }
	
	/**
     * 推广展位列表
     *
     */
    public function rec_listWt(){
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'rec_list'));
		Tpl::setDirquna('system');
        Tpl::showpage('rec_position.index');
    }

    public function get_xmlWt(){
        $lang = Language::getLangContent();
        $model_rec = model('rec_position');
        $condition  = array();
        if ($_POST['query'] != '' && in_array($_POST['qtype'],array('ap_name'))) {
            $condition[$_POST['qtype']] = $_POST['query'];
        }
        if ($_POST['qtype'] != '') {
            $condition['pic_type'] = intval($_POST['qtype']);
        }
        if (!empty($_POST['query'])) {
            $condition['title'] = array('like','%'.$_POST['query'].'%');
        }
        if (in_array($_POST['sortname'],array('rec_id')) && in_array($_POST['sortorder'],array('asc','desc'))) {
            $order = $_POST['sortname'].' '.$_POST['sortorder'];
        }
        $list = $model_rec->where($condition)->order($order)->page($_POST['rp'])->select();
        $data = array();
        $data['now_page'] = $model_rec->shownowpage();
        $data['total_num'] = $model_rec->gettotalnum();
        foreach ($list as $k => $info) {
            $list = array();$operation_detail = '';
            $info['content'] = unserialize($info['content']);
            $list['operation'] = "<a class='btn red' onclick=\"fg_delete({$info['rec_id']})\"><i class='fa fa-trash-o'></i>删除</a>";
            $operation_detail = "<li><a href='index.php?w=show&t=rec_edit&rec_id={$info['rec_id']}'>编辑内容</a></li>";
            $operation_detail .= "<li><a href=\"javascript:void(0);\" rec_id=\"{$info['rec_id']}\" wttype=\"jscode\"></i>调用代码</a></li>";
            $operation_detail .= "<li><a href=\"index.php?w=show&t=rec_view&rec_id={$info['rec_id']}\" target=\"_blank\">预览效果</a></li>";
            if ($operation_detail) {
                $list['operation'] .= "<span class='btn'><em><i class='fa fa-cog'></i>设置 <i class='arrow'></i></em><ul>{$operation_detail}</ul>";
            }
            $list['title'] = $info['title'];
            $list['pic_type'] = str_replace(array(0,1,2),array($lang['rec_ps_txt'],$lang['rec_ps_picb'],$lang['rec_ps_picy']),$info['pic_type']);
            $list['pic_type'] .= $info['pic_type'] != 0 ? (count($info['content']['body']) == 1 ? $lang['rec_ps_picdan'] : $lang['rec_ps_picduo']) : null;
            if($info['pic_type'] == 0){
                $list['content'] = $info['content']['body'][0]['title'];
            } else {
                $list['content'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".UPLOAD_SITE_URL.'/'.$info['content']['body'][0]['title'].">\")'><i class='fa fa-picture-o'></i></a>";
            }
            $list['url'] = $info['content']['body'][0]['url'];
            $list['target'] = $info['content']['target'] == 1 ? '<span class="no"><i class="fa fa-ban"></i>否</span>' : '<span class="yes"><i class="fa fa-check-bbs"></i>是</span>';
            $data['list'][$info['rec_id']] = $list;
        }
        exit(Tpl::flexigridXML($data));
    }

    /**
     * 新增推广展位
     *
     */
    public function rec_addWt(){
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'rec_list'));
		Tpl::setDirquna('system');
        Tpl::showpage('rec_position.add');
    }

    /**
     * 编辑推广展位
     *
     */
    public function rec_editWt(){
        $model = Model('rec_position');
        $info = $model->where(array('rec_id'=>intval($_GET['rec_id'])))->find();
        if (!$info) showMessage(Language::get('no_record'));
        $info['content'] = unserialize($info['content']);
        foreach((array)$info['content']['body'] as $k=>$v){
            if ($info['pic_type'] == 1){
                $info['content']['body'][$k]['title'] = UPLOAD_SITE_URL.'/'.$v['title'];
            }
        }
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'rec_list'));
        Tpl::output('info',$info);
		Tpl::setDirquna('system');
        Tpl::showpage('rec_position.edit');
    }

    /**
     * 删除
     *
     */
    public function rec_delWt(){
        $model = Model('rec_position');
        if (preg_match('/^[\d,]+$/', $_GET['rec_id'])) {
            $_GET['rec_id'] = explode(',',trim($_GET['rec_id'],','));
            if (is_array($_GET['rec_id'])) {
                foreach($_GET['rec_id'] as $rec_id) {
                    $info = $model->where(array('rec_id'=>$rec_id))->find();
                    if (!$info) {
                        exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
                    }
                    $info['content'] = unserialize($info['content']);
                    $result = $model->where(array('rec_id'=>$rec_id))->delete();
                    if ($result){
                        if ($info['pic_type'] == 1 && is_array($info['content']['body'])){
                            foreach ($info['content']['body'] as $v){
                                @unlink(BASE_UPLOAD_PATH.'/'.$v['title']);
                            }
                        }
                        dkcache("rec_position/{$info['rec_id']}");
                        $this->log(L('wt_del,rec_position').'[ID:'.$rec_id.']',1);
                    }else{
                        exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
                    }
                }
                exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
            }
        }
        exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
    }

    /**
     * 添加保存推广展位
     *
     */
    public function rec_saveWt(){
        $array = array();
        $data = array();
        $pattern = "/^http:\/\/[A-Za-z0-9]+[A-Za-z0-9.]+\.[A-Za-z0-9]+/i";
        //文字类型
        if ($_POST['rec_type'] == 1){
            if (is_array($_POST['txt']) && is_array($_POST['urltxt'])){
                foreach ($_POST['txt'] as $k=>$v){
                    if (trim($v) == '') continue;
                    $c = count($array['body']);
                    $array['body'][$c]['title'] = $v;
                    $array['body'][$c]['url'] = preg_match($pattern,$_POST['urltxt'][$k]) ? $_POST['urltxt'][$k] : '';
                    $data['pic_type'] = 0;
                }
            }else{
                showMessage(Language::get('param_error'));
            }
        }elseif ($_POST['rec_type'] == 2 && $_POST['pic_type'] == 1){
            //本地图片上传
            if (is_array($_FILES['pic']['tmp_name'])){
                foreach($_FILES['pic']['tmp_name'] as $k=>$v){
                    if (empty($v)) continue;
                    $ext = strtolower(pathinfo($_FILES['pic']['name'][$k], PATHINFO_EXTENSION));
                    if (in_array($ext,array('jpg','jpeg','gif','png'))){
                        $filename = substr(md5(microtime(true)),0,16).rand(100,999).$k.'.'.$ext;
                        if ($_FILES['pic']['size'][$k]<1024*1024){
                            move_uploaded_file($v,BASE_UPLOAD_PATH.'/'.ATTACH_REC_POSITION.'/'.$filename);
                        }
                        if ($_FILES['pic']['error'][$k] != 0) showMessage(Language::get('wt_common_op_fail'));
                        $c = count($array['body']);
                        $array['body'][$c]['title'] = ATTACH_REC_POSITION.'/'.$filename;
                        $array['body'][$c]['url']   = preg_match($pattern,$_POST['urlup'][$k]) ? $_POST['urlup'][$k] : '';
                        $array['width']             = is_numeric($_POST['rwidth']) ? $_POST['rwidth'] : '';
                        $array['height']            = is_numeric($_POST['rheight']) ? $_POST['rheight'] : '';
                        $data['pic_type']           = 1;
                    }
                    if (empty($array)) showMessage(Language::get('param_error'));
                }
            }
        }elseif ($_POST['rec_type'] == 2 && $_POST['pic_type'] == 2){

            //远程图片
            if (is_array($_POST['pic'])){
                foreach ($_POST['pic'] as $k=>$v){
                    if (!preg_match("/^(http\:\/\/)/i",$v)) continue;
                    $ext = strtolower(pathinfo($v, PATHINFO_EXTENSION));
                    if (in_array($ext,array('jpg','jpeg','gif','png','bmp'))){
                        $c = count($array['body']);
                        $array['body'][$c]['title'] = $v;
                        $array['body'][$c]['url']   = preg_match($pattern,$_POST['urlremote'][$k]) ? $_POST['urlremote'][$k] : '';
                        $array['width']             = is_numeric($_POST['rwidth']) ? $_POST['rwidth'] : '';
                        $array['height']            = is_numeric($_POST['rheight']) ? $_POST['rheight'] : '';
                        $data['pic_type']           = 2;
                    }
                    if (empty($array)) showMessage(Language::get('param_error'));
                }
            }
        }else{
            showMessage(Language::get('param_error'));
        }
        $array['target']    = intval($_POST['rtarget']);
        $data['title']      = $_POST['rtitle'];
        $data['content']    = serialize($array);
        $model = Model('rec_position');
        $model->insert($data);
        $this->log(L('wt_add,rec_position').'['.$_POST['rtitle'].']',1);
        showMessage(Language::get('wt_common_save_succ'),'index.php?w=show&t=rec_list');
    }

    /**
     * 编辑保存推广展位
     *
     */
    public function rec_edit_saveWt(){
        if (!is_numeric($_POST['rec_id'])) showMessage(Language::get('param_error'));
        $array = array();
        $data = array();
        $pattern = "/^http:\/\/[A-Za-z0-9]+[A-Za-z0-9.]+\.[A-Za-z0-9]+/i";
        //文字类型
        if ($_POST['rec_type'] == 1){
            if (is_array($_POST['txt']) && is_array($_POST['urltxt'])){
                foreach ($_POST['txt'] as $k=>$v){
                    if (trim($v) == '') continue;
                    $c = count($array['body']);
                    $array['body'][$c]['title'] = $v;
                    $array['body'][$c]['url'] = preg_match($pattern,$_POST['urltxt'][$k]) ? $_POST['urltxt'][$k] : '';
                    $data['pic_type'] = 0;
                }
            }else{
                showMessage(Language::get('param_error'));
            }
        }elseif ($_POST['rec_type'] == 2 && $_POST['pic_type'] == 1){
            //本地图片上传
            if (is_array($_FILES['pic']['tmp_name'])){
                foreach($_FILES['pic']['tmp_name'] as $k=>$v){
                    //未上传新图的，还用老图
                    if (empty($v) && !empty($_POST['opic'][$k])){
                        $array['body'][$k]['title'] = str_ireplace(UPLOAD_SITE_URL.'/','',$_POST['opic'][$k]);
                        $array['body'][$k]['url']   = preg_match($pattern,$_POST['urlup'][$k]) ? $_POST['urlup'][$k] : '';
                    }
                    $ext = strtolower(pathinfo($_FILES['pic']['name'][$k], PATHINFO_EXTENSION));
                    if (in_array($ext,array('jpg','jpeg','gif','png','bmp'))){
                        $filename = substr(md5(microtime(true)),0,16).rand(100,999).$k.'.'.$ext;
                        if ($_FILES['pic']['size'][$k]<1024*1024){
                            move_uploaded_file($v,BASE_UPLOAD_PATH.'/'.ATTACH_REC_POSITION.'/'.$filename);
                        }
                        if ($_FILES['pic']['error'][$k] != 0) showMessage(Language::get('wt_common_save_fail'));

                        //删除老图
                        $old_file = str_ireplace(array(UPLOAD_SITE_URL,'..'),array(BASE_UPLOAD_PATH,''),$_POST['opic'][$k]);
                        if (is_file($old_file)) @unlink($old_file);

                        $array['body'][$k]['title'] = ATTACH_REC_POSITION.'/'.$filename;
                        $array['body'][$k]['url']   = preg_match($pattern,$_POST['urlup'][$k]) ? $_POST['urlup'][$k] : '';
                        $data['pic_type']           = 1;
                    }
                }

                //最后删除数据库里有但没有POST过来的图片
                $model = Model('rec_position');
                $oinfo = $model->where(array('rec_id'=>$_POST['rec_id']))->find();
                $oinfo = unserialize($oinfo['content']);
                foreach ($oinfo['body'] as $k=>$v) {
                    if (!in_array(UPLOAD_SITE_URL.'/'.$v['title'],(array)$_POST['opic'])){
                        if (is_file(BASE_UPLOAD_PATH.'/'.$v['title'])){
                            @unlink(BASE_UPLOAD_PATH.'/'.$v['title']);
                        }
                    }
                }
                unset($oinfo);
            }
            //如果是上传图片，则取原图片地址
            if (empty($array)){
                if (is_array($_POST['opic'])){
                    foreach ($_POST['opic'] as $k=>$v){
                        $array['body'][$k]['title'] = $v;
                        $array['body'][$k]['url']   = preg_match($pattern,$_POST['urlup'][$k]) ? $_POST['urlup'][$k] : '';
                    }
                }
            }
        }elseif ($_POST['rec_type'] == 2 && $_POST['pic_type'] == 2){

            //远程图片
            if (is_array($_POST['pic'])){
                foreach ($_POST['pic'] as $k=>$v){
                    if (!preg_match("/^(http\:\/\/)/i",$v)) continue;
                    $ext = strtolower(pathinfo($v, PATHINFO_EXTENSION));
                    if (in_array($ext,array('jpg','jpeg','gif','png','bmp'))){
                        $c = count($array['body']);
                        $array['body'][$c]['title'] = $v;
                        $array['body'][$c]['url']   = preg_match($pattern,$_POST['urlremote'][$k]) ? $_POST['urlremote'][$k] : '';
                        $data['pic_type']           = 2;
                    }
                }
            }
        }else{
            showMessage(Language::get('param_error'));
        }

        if ($_POST['rec_type'] != 1){
            $array['width']             = is_numeric($_POST['rwidth']) ? $_POST['rwidth'] : '';
            $array['height']            = is_numeric($_POST['rheight']) ? $_POST['rheight'] : '';
        }

        $array['target']    = intval($_POST['rtarget']);
        $data['title']      = $_POST['rtitle'];
        $data['content']    = serialize($array);
        $model = Model('rec_position');

        //如果是把本地上传类型改为文字或远程，则先取出原来上传的图片路径，待update成功后，再删除这些图片
        if ($_POST['opic_type'] == 1 && ($_POST['pic_type'] == 2 || $_POST['rec_type'] == 1)){
            $oinfo = $model->where(array('rec_id'=>$_POST['rec_id']))->find();
            $oinfo = unserialize($oinfo['content']);
        }
        $result = $model->where(array('rec_id'=>$_POST['rec_id']))->update($data);
        if ($result){
            if ($oinfo){
                foreach ($oinfo['body'] as $v){
                    if (is_file(BASE_UPLOAD_PATH.'/'.$v['title'])){
                        @unlink(BASE_UPLOAD_PATH.'/'.$v['title']);
                    }
                }
            }

            dkcache("rec_position/{$_POST['rec_id']}");
            showMessage(Language::get('wt_common_save_succ'),'index.php?w=show&t=rec_list');
        }else{
            showMessage(Language::get('wt_common_save_fail'),'index.php?w=show&t=rec_list');
        }
    }

    public function rec_codeWt(){
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'rec_list'));
		Tpl::setDirquna('system');
        Tpl::showpage('rec_position.code','null_layout');
    }

    public function rec_viewWt(){
        @header("Content-type: text/html; charset=".CHARSET);
        echo rec(intval($_GET['rec_id']));
    }
	
	 /**
     * 顶部广告信息
     */
    public function bannerWt(){
        $model_setting = Model('setting');
        if (chksubmit()){
			 if (!empty($_FILES['wt_top_banner_pic']['name'])){
                $upload = new UploadFile();
                $upload->set('default_dir',ATTACH_COMMON);
                $result = $upload->upfile('wt_top_banner_pic');
                if ($result){
                    $_POST['wt_top_banner_pic'] = $upload->file_name;
                }else {
                    showMessage($upload->error,'','','error');
                }
            }
            $list_setting = $model_setting->getListSetting();
            $update_array = array();
            $update_array['wt_top_banner_name'] = $_POST['top_banner_name'];
            $update_array['wt_top_banner_url'] = $_POST['top_banner_url'];
            $update_array['wt_top_banner_color'] = $_POST['top_banner_color'];
            $update_array['wt_top_banner_status'] = $_POST['top_banner_status'];
			if (!empty($_POST['wt_top_banner_pic'])){
                $update_array['wt_top_banner_pic'] = $_POST['wt_top_banner_pic'];
            }
            $result = $model_setting->updateSetting($update_array);
			if ($result === true){
                //判断有没有之前的图片，如果有则删除
                if (!empty($list_setting['wt_top_banner_pic']) && !empty($_POST['wt_top_banner_pic'])){
                    @unlink(BASE_UPLOAD_PATH.DS.ATTACH_COMMON.DS.$list_setting['wt_top_banner_pic']);
                }
                $this->log(L('wt_edit,top_set'),1);
                showMessage(L('wt_common_save_succ'));
            }else {
                $this->log(L('wt_edit,top_set'),0);
                showMessage(L('wt_common_save_fail'));
            }
        }
         
        $list_setting = $model_setting->getListSetting();

        Tpl::output('list_setting',$list_setting);

        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'banner'));
		
		Tpl::setDirquna('system');
        Tpl::showpage('wt.banner');
    }
}
