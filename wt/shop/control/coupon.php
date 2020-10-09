<?php
/**
 * 平台优惠券管理

 
 
 */


defined('ShopWT') or exit('Access Denied By ShopWT');
class couponControl extends SystemControl{
    //每次导出订单数量
    const EXPORT_SIZE = 1000;
    private $gettype_arr;
    private $templatestate_arr;
    private $coupon_state_arr;
    private $member_grade_arr;
    
    public function __construct(){
        parent::__construct();
        if (C('coupon_allow') != 1){
            showDialog('需开启“平台优惠券”功能','index.php?w=operation','succ');
        }
        $model_coupon = Model('coupon');
        $this->gettype_arr = $model_coupon->getGettypeArr();
        $this->templatestate_arr = $model_coupon->getTemplateState();
        $this->coupon_state_arr = $model_coupon->getCouponState();
        $this->member_grade_arr = Model('member')->getMemberGradeArr();
    }

    /*
     * 默认操作列出优惠券
     */
    public function indexWt(){
        $this->rptlistWt();
    }
    /**
     * 新增优惠券
     */
    public function rptaddWt(){
        if (chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                    array("input"=>$_POST['rpt_title'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>'模版名称不能为空且小于50个字符'),
                    array("input"=>$_POST['rpt_gettype'], "require"=>"true","message"=>'请选择领取方式'),
                    array("input"=>$_POST['rpt_sdate'], "require"=>"true","message"=>'请选择有效期开始时间'),
                    array("input"=>$_POST['rpt_edate'], "require"=>"true","message"=>'请选择有效期结束时间'),
                    array("input"=>$_POST['rpt_price'], "require"=>"true","validator"=>"Number","min"=>"1","message"=>'面额不能为空且为大于1的整数'),
                    array("input"=>$_POST['rpt_total'], "require"=>"true","validator"=>"Number","min"=>"1","message"=>'可发放数量不能为空且为大于1的整数'),
                    array("input"=>$_POST['rpt_orderlimit'], "require"=>"true","validator"=>"Double","min"=>"0","message"=>'模版使用消费限额不能为空且必须是数字'),
                    array("input"=>$_POST['rpt_desc'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"200","message"=>'模版描述不能为空且小于200个字符')
            );
            $error = $obj_validate->validate();
            //开始时间不能大于结束时间
            $stime = strtotime($_POST['rpt_sdate']);
            $etime = strtotime($_POST['rpt_edate']);
            if ($stime > $etime){
                $error.= '开始时间不能大于结束时间';
            }
            //验证优惠券面额不能大于订单限额
            $price = floatval($_POST['rpt_price'])>0?floatval($_POST['rpt_price']):0;
            $limit = floatval($_POST['rpt_orderlimit'])>0?floatval($_POST['rpt_orderlimit']):0;
            if($limit>0 && $price>=$limit) $error.= '面额不能大于消费限额';
            //验证卡密优惠券发放数量
            $gettype = trim($_POST['rpt_gettype']);
            if($gettype == 'pwd'){
                if (intval($_POST['rpt_total']) > 10000){
                    $error.= '领取方式为卡密兑换的优惠券，发放总数不能超过10000张';
                }
            }
            //验证积分
            $points = intval($_POST['rpt_points']);
            if($gettype == 'points' && $points < 1){
                $error.= '兑换所需积分不能为空且为大于1的整数';
            }
            if ($error){
                showDialog($error, '', 'error');
            }else {
                $model_coupon = Model('coupon');
                $insert_arr = array();
                $insert_arr['coupon_t_title'] = trim($_POST['rpt_title']);
                $insert_arr['coupon_t_desc'] = trim($_POST['rpt_desc']);
                $insert_arr['coupon_t_start_date'] = $stime;
                $insert_arr['coupon_t_end_date'] = $etime;
                $insert_arr['coupon_t_price'] = $price;
                $insert_arr['coupon_t_limit'] = $limit;
                $insert_arr['coupon_t_adminid'] = $this->admin_info['id'];
                $insert_arr['coupon_t_state'] = $this->templatestate_arr['usable']['sign'];
                $insert_arr['coupon_t_total'] = intval($_POST['rpt_total']);
                $insert_arr['coupon_t_giveout'] = 0;
                $insert_arr['coupon_t_used'] = 0;
                $insert_arr['coupon_t_updatetime'] = time();
                $insert_arr['coupon_t_points'] = $points;
                $insert_arr['coupon_t_eachlimit'] = ($t = intval($_POST['rpt_eachlimit']))>0?$t:0;
                $insert_arr['coupon_t_recommend'] = 0;
                $insert_arr['coupon_t_gettype'] = in_array($gettype,array_keys($this->gettype_arr))?$this->gettype_arr[$gettype]['sign']:$this->gettype_arr[$model_coupon::GETTYPE_DEFAULT]['sign'];
                $insert_arr['coupon_t_isbuild'] = 0;
                $mgrade_limit = intval($_POST['rpt_mgradelimit']);
                $insert_arr['coupon_t_mgradelimit'] = in_array($mgrade_limit,array_keys($this->member_grade_arr))?$mgrade_limit:$this->member_grade_arr[0]['level'];
                //自定义图片
                if (!empty($_FILES['rpt_img']['name'])){
                    $upload = new UploadFile();
                    $upload->set('default_dir', ATTACH_REDPACKET);
                    $upload->set('thumb_width','160');
                    $upload->set('thumb_height','160');
                    $upload->set('thumb_ext','_small');
                    $result = $upload->upfile('rpt_img');
                    if ($result){
                        $insert_arr['coupon_t_customimg'] =  $upload->file_name;
                    }
                }
                $rs = $model_coupon->addRptTemplate($insert_arr);
                if($rs){
                    //生成卡密优惠券
                    if($gettype == 'pwd'){
                        QueueClient::push('buildPwdCoupon', $rs);
                    }
                    $this->log("新增优惠券模板[ID：{$rs}]成功");
                    showDialog(L('wt_common_save_succ'),'index.php?w=coupon&t=rptlist','succ');
                }else{
                    showDialog(L('wt_common_save_fail'),'','error');
                }
            }
        }else {
            TPL::output('gettype_arr',$this->gettype_arr);
            TPL::output('member_grade',$this->member_grade_arr);
			Tpl::setDirquna('shop');
            Tpl::showpage('coupon.templateadd');
        }
    }
    /**
     * 优惠券列表
     */
    public function rptlistWt()
    {
        TPL::output('gettype_arr',$this->gettype_arr);
        TPL::output('templateState',$this->templatestate_arr);
		Tpl::setDirquna('shop');
        Tpl::showpage('coupon.templatelist');
    }

    /**
     * 优惠券模板列表XML
     */
    public function rptlist_xmlWt()
    {
        $where = array();
        if ($_REQUEST['showanced']) {
            if (strlen($q = trim($_REQUEST['rpt_title']))) {
                $where['coupon_t_title'] = array('like', '%' . $q . '%');
            }
            if (($q = (int) $_REQUEST['rpt_gettype']) > 0) {
                $where['coupon_t_gettype'] = $q;
            }
            if (($q = (int) $_REQUEST['rpt_state']) > 0) {
                $where['coupon_t_state'] = $q;
            }
            if (strlen($q = trim($_REQUEST['rpt_recommend']))) {
                $where['coupon_t_recommend'] = (int) $q;
            }

            if (trim($_GET['sdate']) && trim($_GET['edate'])) {
                $sdate = strtotime($_GET['sdate']);
                $edate = strtotime($_GET['edate']);
                $where['coupon_t_updatetime'] = array('between', "$sdate,$edate");
            } elseif (trim($_GET['sdate'])) {
                $sdate = strtotime($_GET['sdate']);
                $where['coupon_t_updatetime'] = array('egt', $sdate);
            } elseif (trim($_GET['edate'])) {
                $edate = strtotime($_GET['edate']);
                $where['coupon_t_updatetime'] = array('elt', $edate);
            }

            $pdates = array();
            if (strlen($q = trim((string) $_REQUEST['pdate1'])) && ($q = strtotime($q . ' 00:00:00'))) {
                $pdates[] = "coupon_t_end_date >= {$q}";
            }
            if (strlen($q = trim((string) $_REQUEST['pdate2'])) && ($q = strtotime($q . ' 00:00:00'))) {
                $pdates[] = "coupon_t_start_date <= {$q}";
            }
            if ($pdates) {
                $where['pdates'] = array('exp',implode(' and ', $pdates));
            }
        } else {
            if (strlen($q = trim($_REQUEST['query']))) {
                switch ($_REQUEST['qtype']) {
                    case 'rpt_title':
                        $where['coupon_t_title'] = array('like', "%$q%");
                        break;
                }
            }
        }

        switch ($_REQUEST['sortname']) {
            case 'coupon_t_price':
            case 'coupon_t_limit':
                $sort = $_REQUEST['sortname'];
                break;
            case 'coupon_t_mgradelimittext':
                $sort = 'coupon_t_mgradelimit';
                break;
            case 'coupon_t_updatetimetext':
                $sort = 'coupon_t_updatetime';
                break;
            case 'coupon_t_start_datetext':
                $sort = 'coupon_t_start_date';
                break;
            case 'coupon_t_end_datetext':
                $sort = 'coupon_t_end_date';
                break;
            case 'coupon_t_statetext':
                $sort = 'coupon_t_state';
                break;
            case 'coupon_t_recommend':
                $sort = 'coupon_t_recommend';
                break;
            default:
                $sort = 'coupon_t_id';
                break;
        }
        if ($_REQUEST['sortorder'] != 'asc') {
            $sort .= ' desc';
        }

        $model_coupon = Model('coupon');
        $list = $model_coupon->getRptTemplateList($where, '*', 0, $_REQUEST['rp'], $sort);
        
        $data = array();
        $data['now_page'] = $model_coupon->shownowpage();
        $data['total_num'] = $model_coupon->gettotalnum();
        foreach ($list as $val) {
            $o = '';
            if($val['coupon_t_giveout']<=0 && $val['coupon_t_isbuild'] == 0){
                $o .= '<a class="btn red" href="javascript:void(0);" onclick="fg_del('.$val['coupon_t_id'].')"><i class="fa fa-trash-o"></i>删除</a>';
            }            
            $o .= "<span class='btn'><em><i class='fa fa-cog'></i>设置 <i class='arrow'></i></em><ul>";
            $o .= "<li><a href='" . urlAdminShop('coupon', 'rptedit', array('tid' => $val['coupon_t_id'])) . "'>编辑信息</a></li>";
            $o .= "<li><a href='" . urlAdminShop('coupon', 'rptinfo', array('tid' => $val['coupon_t_id'])) . "'>查看详细</a></li>";
            $o .= "</ul>";
            
            $i = array();
            $i['operation'] = $o;
            $i['coupon_t_title'] = $val['coupon_t_title'];
            $i['coupon_t_price'] = $val['coupon_t_price'];
            $i['coupon_t_limit'] = $val['coupon_t_limit'];
            $i['coupon_t_mgradelimittext'] = $val['coupon_t_mgradelimittext'];
            $i['coupon_t_updatetimetext'] = date('Y-m-d H:i', $val['coupon_t_updatetime']);
            $i['coupon_t_start_datetext'] = date('Y-m-d H:i', $val['coupon_t_start_date']);
            $i['coupon_t_end_datetext'] = date('Y-m-d H:i', $val['coupon_t_end_date']);
            $i['coupon_t_gettype_text'] = $val['coupon_t_gettype_text'];
            $i['coupon_t_statetext'] = $val['coupon_t_state_text'];
            $i['coupon_t_recommendtext'] = $val['coupon_t_recommend'] == '1'
                ? '<span class="yes"><i class="fa fa-check-bbs"></i>是</span>'
                : '<span class="no"><i class="fa fa-ban"></i>否</span>';

            $data['list'][$val['coupon_t_id']] = $i;
        }
        echo Tpl::flexigridXML($data);
        exit;
    }

    /*
     * 优惠券模版编辑
     */
    public function rpteditWt(){
        $t_id = intval($_GET['tid']);
        if ($t_id <= 0){
            $t_id = intval($_POST['tid']);
        }
        if ($t_id <= 0){
            showDialog(L('param_error'),'index.php?w=coupon&t=rptlist');
        }
        $model_coupon = Model('coupon');
        //查询模板信息
        $where = array();
        $where['coupon_t_id'] = $t_id;
        $t_info = $model_coupon->getRptTemplateInfo($where);
        if (!$t_info){
            showDialog(L('param_error'),'index.php?w=coupon&t=rptlist');
        }
        //判断模板详情是否能编辑
        if($t_info['coupon_t_giveout'] > 0 || $t_info['coupon_t_isbuild'] == 1){
            $t_info['ableedit'] = false;
        } else {
            $t_info['ableedit'] = true;
        } 
        if(chksubmit()){            
            if ($t_info['ableedit'] == true){
                $obj_validate = new Validate();
                $obj_validate->validateparam = array(
                        array("input"=>$_POST['rpt_title'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>'模版名称不能为空且小于50个字符'),
                        array("input"=>$_POST['rpt_gettype'], "require"=>"true","message"=>'请选择领取方式'),
                        array("input"=>$_POST['rpt_sdate'], "require"=>"true","message"=>'请选择有效期开始时间'),
                        array("input"=>$_POST['rpt_edate'], "require"=>"true","message"=>'请选择有效期结束时间'),
                        array("input"=>$_POST['rpt_price'], "require"=>"true","validator"=>"Number","min"=>"1","message"=>'面额不能为空且为大于1的整数'),
                        array("input"=>$_POST['rpt_total'], "require"=>"true","validator"=>"Number","min"=>"1","message"=>'可发放数量不能为空且为大于1的整数'),
                        array("input"=>$_POST['rpt_orderlimit'], "require"=>"true","validator"=>"Double","min"=>"0","message"=>'模版使用消费限额不能为空且必须是数字'),
                        array("input"=>$_POST['rpt_desc'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"200","message"=>'模版描述不能为空且小于200个字符')
                );
                $error = $obj_validate->validate();
                //开始时间不能大于结束时间
                $stime = strtotime($_POST['rpt_sdate']);
                $etime = strtotime($_POST['rpt_edate']);
                if ($stime > $etime){
                    $error.= '开始时间不能大于结束时间';
                }
                //验证优惠券面额不能大于订单限额
                $price = floatval($_POST['rpt_price'])>0?floatval($_POST['rpt_price']):0;
                $limit = floatval($_POST['rpt_orderlimit'])>0?floatval($_POST['rpt_orderlimit']):0;
                if($limit>0 && $price>=$limit) $error.= '面额不能大于消费限额';
                //验证卡密优惠券发放数量
                $gettype = trim($_POST['rpt_gettype']);
                if($gettype == 'pwd'){
                    if (intval($_POST['rpt_total']) > 10000){
                        $error.= '领取方式为卡密兑换的优惠券，发放总数不能超过10000张';
                    }
                }
                //验证积分
                $points = intval($_POST['rpt_points']);
                if($gettype == 'points' && $points < 1){
                    $error.= '兑换所需积分不能为空且为大于1的整数';
                }
                if($gettype <> 'points') {
                    $points = 0;
                }
                if ($error){
                    showDialog($error, '', 'error');
                }
                $update_arr = array();
                $update_arr['coupon_t_title'] = trim($_POST['rpt_title']);
                $update_arr['coupon_t_desc'] = trim($_POST['rpt_desc']);
                $update_arr['coupon_t_start_date'] = $stime;
                $update_arr['coupon_t_end_date'] = $etime;
                $update_arr['coupon_t_price'] = $price;
                $update_arr['coupon_t_limit'] = $limit;
                $update_arr['coupon_t_adminid'] = $this->admin_info['id'];
                $update_arr['coupon_t_total'] = intval($_POST['rpt_total']);
                $update_arr['coupon_t_giveout'] = 0;
                $update_arr['coupon_t_used'] = 0;
                $update_arr['coupon_t_updatetime'] = time();
                $update_arr['coupon_t_points'] = $points;
                $update_arr['coupon_t_eachlimit'] = ($t = intval($_POST['rpt_eachlimit']))>0?$t:0;
                $update_arr['coupon_t_gettype'] = $this->gettype_arr[$gettype]['sign'];
                $update_arr['coupon_t_isbuild'] = 0;
                $mgrade_limit = intval($_POST['rpt_mgradelimit']);
                $update_arr['coupon_t_mgradelimit'] = in_array($mgrade_limit,array_keys($this->member_grade_arr))?$mgrade_limit:$this->member_grade_arr[0]['level'];
                //自定义图片
                if (!empty($_FILES['rpt_img']['name'])){
                    $upload = new UploadFile();
                    $upload->set('default_dir', ATTACH_REDPACKET);
                    $upload->set('thumb_width','160');
                    $upload->set('thumb_height','160');
                    $upload->set('thumb_ext','_small');
                    $result = $upload->upfile('rpt_img');
                    if ($result){
                        $update_arr['coupon_t_customimg'] =  $upload->file_name;
                        //删除旧图片
                        if ($t_info['coupon_t_customimg'] && is_file(BASE_UPLOAD_PATH . '/' . ATTACH_REDPACKET . '/' . $t_info['coupon_t_customimg'])) {
                            @unlink(BASE_UPLOAD_PATH . '/' . ATTACH_REDPACKET . '/' . $t_info['coupon_t_customimg']);
                            @unlink(BASE_UPLOAD_PATH . '/' . ATTACH_REDPACKET . '/' . str_ireplace('.', '_small.', $t_info['coupon_t_customimg']));
                        }
                    }
                }
            }
            $update_arr['coupon_t_state'] = ($t=intval($_POST['rpt_state']))==1?1:2;
            $update_arr['coupon_t_recommend'] = ($t=intval($_POST['recommend']))==1?1:0;
            $rs = Model('coupon')->editRptTemplate(array('coupon_t_id'=>$t_id),$update_arr);
            if($rs){
                $this->log("编辑优惠券模板[ID：{$t_id}]成功");
                showDialog(L('wt_common_save_succ'),'index.php?w=coupon&t=rptlist','succ');
            } else {
                showDialog(L('wt_common_save_fail'),'','error');
            }
        }else{
            //查询最近修改的管理员
            $creator_info = Model('admin')->getOneAdmin($t_info['coupon_t_adminid']);
            $t_info['coupon_t_creator_name'] = $creator_info['admin_name'];
            $t_info['coupon_t_price'] = intval($t_info['coupon_t_price']);
            TPL::output('gettype_arr',$this->gettype_arr);
            TPL::output('member_grade',$this->member_grade_arr);
            TPL::output('templatestate_arr',$this->templatestate_arr);
            TPL::output('t_info',$t_info);
			Tpl::setDirquna('shop');
            Tpl::showpage('coupon.templateedit');
        }
    }

    /**
     * 删除优惠券模板 
     */
    public function rptdelWt() {
        $t_id = intval($_GET['tid']);
        if ($t_id <= 0){
            showDialog(L('param_error'));
        }
        $model_coupon = Model('coupon');
        //查询模板信息
        $where = array();
        $where['coupon_t_id'] = $t_id;
        $where['coupon_t_giveout'] = array('elt',0);
        $where['coupon_t_isbuild'] = 0;
        $result = $model_coupon->dropRptTemplate($where);
        if ($result){
            $this->log("删除优惠券模板[ID：{$t_id}]成功");
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }
    
    /*
     * 优惠券模版编辑
    */
    public function rptinfoWt(){
        $t_id = intval($_GET['tid']);
        if ($t_id <= 0){
            $t_id = intval($_POST['tid']);
        }
        if ($t_id <= 0){
            showDialog(L('param_error'),'index.php?w=coupon&t=rptlist');
        }
        $model_coupon = Model('coupon');
        //查询模板信息
        $where = array();
        $where['coupon_t_id'] = $t_id;
        $t_info = $model_coupon->getRptTemplateInfo($where);
        if (!$t_info){
            showDialog(L('param_error'),'index.php?w=coupon&t=rptlist');
        }
        //查询最近修改的管理员
        $creator_info = Model('admin')->getOneAdmin($t_info['coupon_t_adminid']);
        $t_info['coupon_t_creator_name'] = $creator_info['admin_name'];
        $t_info['coupon_t_price'] = intval($t_info['coupon_t_price']);
        TPL::output('t_info',$t_info);
		Tpl::setDirquna('shop');
        Tpl::showpage('coupon.templateinfo');
    }
    /**
     * 优惠券列表XML
     */
    public function rplist_xmlWt()
    {
        $t_id = intval($_GET['tid']);
        if ($t_id <= 0){
            echo Tpl::flexigridXML(array());
            exit;
        }
        $model_coupon = Model('coupon');
        $list = $model_coupon->getCouponList(array('coupon_t_id'=>$t_id), '*', 0, $_REQUEST['rp'], 'coupon_id desc');
        $data = array();
        $data['now_page'] = $model_coupon->shownowpage();
        $data['total_num'] = $model_coupon->gettotalnum();
        foreach ($list as $val) {
            $i = array();
            $i['coupon_code'] = $val['coupon_code'];
            if($_GET['gtype'] == 'pwd'){
                $i['coupon_pwd'] = $model_coupon->get_rpt_pwd($val['coupon_pwd2']);
            }
            foreach($this->coupon_state_arr as $rpstate_k=>$rpstate_v){
                if($val['coupon_state'] == $rpstate_v['sign']){
                    $i['coupon_statetext'] = $rpstate_v['name'];
                }
            }
            $i['coupon_owner_name'] = $val['coupon_owner_name']?$val['coupon_owner_name']:'未领取';
            $i['coupon_active_datetext'] = $val['coupon_owner_id']>0?date('Y-m-d H:i', $val['coupon_active_date']):'';
            $data['list'][$val['coupon_id']] = $i;
        }
        echo Tpl::flexigridXML($data);
        exit;
    }
    /**
     * 生成优惠券卡密 
     */
    public function rpbulidpwdWt(){
        $t_id = intval($_GET['tid']);
        if ($t_id <= 0){
            showDialog('优惠券生成失败','','error');
        }
        //生成卡密优惠券队列
        QueueClient::push('buildPwdCoupon', $t_id);
        showDialog('生成优惠券卡密任务已建立，稍后将生成','reload','succ');
    }
    
    /**
     * 导出
     */
    public function export_step1Wt(){
        $model_coupon = Model('coupon');
        $t_id = intval($_GET['tid']);
        //查询优惠券模板
        $rpt_info = $model_coupon->getRptTemplateInfo(array('coupon_t_id'=>$t_id));
        if (!$rpt_info){
            showDialog(L('param_error'),'index.php?w=coupon&t=rptlist');
        }
        $where  = array();
        $where['coupon_t_id'] = intval($_GET['tid']);
        if (preg_match('/^[\d,]+$/', $_GET['rid'])) {
            $_GET['rid'] = explode(',',trim($_GET['rid'],','));
            $where['coupon_id'] = array('in',$_GET['rid']);
        }
        $order = 'coupon_id desc';
        
        if (!is_numeric($_GET['curpage'])){
            $count = $model_coupon->getCouponCount($where);
            $array = array();
            if ($count > self::EXPORT_SIZE ){//显示下载链接
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?w=coupon&t=rptinfo&tid='.$t_id);
				Tpl::setDirquna('shop');
                Tpl::showpage('export.excel');
            }else{//如果数量小，直接下载
                $data = $model_coupon->getCouponList($where,'*',self::EXPORT_SIZE,0,$order);
                $this->createExcel($data,$rpt_info);
            }
        }else{//下载
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $data = $model_coupon->getCouponList($where,'*',"{$limit1},{$limit2}",0,$order);
            $this->createExcel($data,$rpt_info);
        }
    }
    
    /**
     * 生成excel
     *
     * @param array $data
     */
    private function createExcel($data = array(),$rpt_info){
        Language::read('export');
        import('bin.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'优惠券编码');
        if ($rpt_info['coupon_t_gettype_key'] == 'pwd'){
            $excel_data[0][] = array('styleid'=>'s_title','data'=>'卡密');
        }
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'领取方式');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'有效期');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'面额');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'每人限领');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'消费限额');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'会员级别');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'状态');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'使用状态');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'所属会员');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'领取时间');
        //data
        $model_coupon = Model('coupon');
        foreach ((array)$data as $k=>$v){
            $list = array();
            $list['coupon_code'] = $v['coupon_code'];
            if ($rpt_info['coupon_t_gettype_key'] == 'pwd'){
                $list['coupon_pwd'] = $model_coupon->get_rpt_pwd($v['coupon_pwd2']);
            }
            $list['coupon_t_gettype_text'] = $rpt_info['coupon_t_gettype_text'];
            $list['coupon_expiry_date'] = @date('Y-m-d',$v['coupon_start_date']).'~'.@date('Y-m-d',$v['coupon_end_date']);
            $list['coupon_price'] = $v['coupon_price'];
            $list['coupon_t_eachlimit'] = $rpt_info['coupon_t_eachlimit']>0? $rpt_info['coupon_t_eachlimit'] : '不限';
            $list['coupon_limit'] = $v['coupon_limit'];
            $list['coupon_t_mgradelimittext'] = $rpt_info['coupon_t_mgradelimittext'];
            $list['coupon_t_state_text'] = $rpt_info['coupon_t_state_text'];
            $list['coupon_state_text'] = $v['coupon_state_text'];
            $list['coupon_owner_name'] = $v['coupon_owner_name']?$v['coupon_owner_name']:'未领取';
            $list['coupon_active_date'] = $v['coupon_owner_name']?@date('Y-m-d H:i:s',$v['coupon_active_date']):0;
            $tmp = array();
            $tmp[] = array('data'=>$list['coupon_code']);
            if ($rpt_info['coupon_t_gettype_key'] == 'pwd'){
                $tmp[] = array('data'=>$list['coupon_pwd']);
            }
            $tmp[] = array('data'=>$list['coupon_t_gettype_text']);
            $tmp[] = array('data'=>$list['coupon_expiry_date']);
            $tmp[] = array('data'=>$list['coupon_price']);
            $tmp[] = array('data'=>$list['coupon_t_eachlimit']);
            $tmp[] = array('data'=>$list['coupon_limit']);
            $tmp[] = array('data'=>$list['coupon_t_mgradelimittext']);
            $tmp[] = array('data'=>$list['coupon_t_state_text']);
            $tmp[] = array('data'=>$list['coupon_state_text']);
            $tmp[] = array('data'=>$list['coupon_owner_name']);
            $tmp[] = array('data'=>$list['coupon_active_date']);
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset('优惠券',CHARSET));
        $excel_obj->generateXML($rpt_info['coupon_t_title'].$_GET['curpage'].'-'.date('Y-m-d-H',time()));
    }
}
