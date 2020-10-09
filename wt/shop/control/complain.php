<?php
/**
 * 投诉管理
 *
 *
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class complainControl extends SystemControl {
    //定义投诉状态常量
    const STATE_NEW = 10;
    const STATE_APPEAL = 20;
    const STATE_TALK = 30;
    const STATE_HANDLE = 40;
    const STATE_FINISH = 99;
    const STATE_ACTIVE = 2;
    const STATE_UNACTIVE = 1;

    private $links = array(
            array('url'=>'w=complain&t=complain_new_list','text'=>'新投诉'),
            array('url'=>'w=complain&t=complain_appeal_list','text'=>'待申诉'),
            array('url'=>'w=complain&t=complain_talk_list','text'=>'对话中'),
            array('url'=>'w=complain&t=complain_handle_list','text'=>'待仲裁'),
            array('url'=>'w=complain&t=complain_finish_list','text'=>'已关闭'),
            array('url'=>'w=complain&t=complain_subject_list','text'=>'主题设置'),
            array('url'=>'w=complain&t=complain_setting','text'=>'时效设置')
    );
    public function __construct() {
        parent::__construct();
        Language::read('complain');
        if ($_GET['t'] == 'index') $_GET['t'] = 'complain_new_list';
        Tpl::output('top_link',$this->sublink($this->links,$_GET['t']));
    }

    /*
     * 默认操作列出新投诉
     */
    public function indexWt() {
        $this->complain_new_listWt();
    }

    /*
     * 未处理的投诉列表
     */
    public function complain_new_listWt() {
        $this->get_complain_list(self::STATE_NEW,'complain_new_list');
    }

    /*
     * 待申诉的投诉列表
     */
    public function complain_appeal_listWt() {
        $this->get_complain_list(self::STATE_APPEAL,'complain_appeal_list');
    }

    /*
     * 对话的投诉列表
     */
    public function complain_talk_listWt() {
        $this->get_complain_list(self::STATE_TALK,'complain_talk_list');
    }

    /*
     * 待仲裁的投诉列表
     */
    public function complain_handle_listWt() {
        $this->get_complain_list(self::STATE_HANDLE,'complain_handle_list');
    }

    /*
     * 已关闭的投诉列表
     */
    public function complain_finish_listWt() {
        $this->get_complain_list(self::STATE_FINISH,'complain_finish_list');
    }

    /*
     * 获取投诉列表
     */
    private function get_complain_list($complain_state,$t) {
        $_GET['state'] = $complain_state;
						
		Tpl::setDirquna('shop');
        Tpl::showpage('complain.list') ;
    }

    /*
     * 获取投诉列表
    */
    public function get_new_xmlWt() {
        $page = new Page();
        $page->setEachNum(intval($_POST['rp']));
        $page->setStyle('admin') ;
        $model_complain = Model('complain');
        $condition = array();
        $condition['complain_state'] = intval($_GET['state']);
        if ($_POST['query'] != '' && in_array($_POST['qtype'],array('complain_accuser','complain_accused'))) {
            $condition[$_POST['qtype']] = $_POST['query'];
        }
        $sort_fields = array('accuser_name','complain_id','accused_name','accuser_id','accused_id');
        if (in_array($_POST['sortorder'],array('asc','desc')) && in_array($_POST['sortname'],$sort_fields)) {
            $condition['order'] = $_POST['sortname'].' '.$_POST['sortorder'];
        }
        $complain_list = $model_complain->getComplain($condition,$page);
        if (empty($complain_list)) $complain_list = array();
        $data = array();
        $data['now_page'] = $page->get('now_page');
        $data['total_num'] = $page->get('total_num');
        $pic_base_url = UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/complain/';
        foreach ($complain_list as $k => $complain_info) {
            $list = array();
            if ($_GET['state'] == 99) {
                $list['operation'] = "<a class=\"btn orange\" href=\"index.php?w=complain&t=complain_progress&complain_id={$complain_info['complain_id']}\"><i class=\"fa fa-list-alt\"></i>查看</a>";
            } else {
                $list['operation'] = "<a class=\"btn orange\" href=\"index.php?w=complain&t=complain_progress&complain_id={$complain_info['complain_id']}\"><i class=\"fa fa-gavel\"></i>处理</a>";
            }
            $list['accuser_name'] = $complain_info['accuser_name'];
            $list['complain_content'] = "<span title='{$complain_info['complain_content']}'>{$complain_info['complain_content']}</span>";
            if(!empty($complain_info['complain_pic1'])) {
                $list['complain_pic'] .= "<a href='".$pic_base_url.$complain_info['complain_pic1']."' target='_blank' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".$pic_base_url.$complain_info['complain_pic1'].">\")'><i class='fa fa-picture-o'></i></a> ";
            }
            if(!empty($complain_info['complain_pic2'])) {
                $list['complain_pic'] .= "<a href='".$pic_base_url.$complain_info['complain_pic2']."' target='_blank' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".$pic_base_url.$complain_info['complain_pic2'].">\")'><i class='fa fa-picture-o'></i></a> ";
            }
            if(!empty($complain_info['complain_pic3'])) {
                $list['complain_pic'] .= "<a href='".$pic_base_url.$complain_info['complain_pic3']."' target='_blank' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".$pic_base_url.$complain_info['complain_pic3'].">\")'><i class='fa fa-picture-o'></i></a> ";
            }
            if (empty($list['complain_pic'])) $list['complain_pic'] = '';
            $list['complain_id'] = date('Y-m-d H:i:s',$complain_info['complain_datetime']);
            $list['complain_subject_content'] = "<span title='{$complain_info['complain_subject_content']}'>{$complain_info['complain_subject_content']}</span>";

            if (in_array($_GET['state'],array(self::STATE_TALK,self::STATE_HANDLE))) {
                $list['appeal_message'] = "<span title='{$complain_info['appeal_message']}'>{$complain_info['appeal_message']}</span>";
                if(!empty($complain_info['appeal_pic1'])) {
                    $list['appeal_pic'] .= "<a href='".$pic_base_url.$complain_info['appeal_pic1']."' target='_blank' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".$pic_base_url.$complain_info['appeal_pic1'].">\")'><i class='fa fa-picture-o'></i></a> ";
                }
                if(!empty($complain_info['appeal_pic2'])) {
                    $list['appeal_pic'] .= "<a href='".$pic_base_url.$complain_info['appeal_pic2']."' target='_blank' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".$pic_base_url.$complain_info['appeal_pic2'].">\")'><i class='fa fa-picture-o'></i></a> ";
                }
                if(!empty($complain_info['appeal_pic3'])) {
                    $list['appeal_pic'] .= "<a href='".$pic_base_url.$complain_info['appeal_pic3']."' target='_blank' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".$pic_base_url.$complain_info['appeal_pic3'].">\")'><i class='fa fa-picture-o'></i></a> ";
                }
                if (empty($list['appeal_pic'])) $list['appeal_pic'] = '';
                $list['appeal_datetime'] = date('Y-m-d H:i:s',$complain_info['appeal_datetime']);
            }
            if (in_array($_GET['state'],array(self::STATE_FINISH))) {
                $list['final_handle_message'] = "<span title='{$complain_info['final_handle_message']}'>{$complain_info['final_handle_message']}</span>";
                $list['final_handle_datetime'] = date('Y-m-d H:i:s',$complain_info['final_handle_datetime']);
            }

            $list['accused_name'] = "<a class='open' title='{$complain_info['accused_name']}' href='". urlShop('show_store','index', array('store_id'=>$complain_info['accused_id'])) ."' target='blank'>{$complain_info['accused_name']}</a>";
            $list['accuser_id'] = $complain_info['accuser_id'];
            $list['accused_id'] = $complain_info['accused_id'];
            $data['list'][$complain_info['complain_id']] = $list;
        }
        exit(Tpl::flexigridXML($data));

    }
    /*
     * 进行中的投诉
     */
    public function complain_progressWt() {
        $complain_id = intval($_GET['complain_id']);
        //获取投诉详细信息
        $complain_info = $this->get_complain_info($complain_id);
        //获取订单详细信息
        $order_info = $this->get_order_info($complain_info['order_id']);
        //获取投诉的商品列表
        $complain_goods_list = $this->get_complain_goods_list($complain_info['order_goods_id']);
        Tpl::output('complain_goods_list',$complain_goods_list);
        if(intval($complain_info['complain_subject_id']) == 1) {//退款信息
            $model_refund = Model('refund_return');
            $model_refund->getRefundStateArray();//向模板页面输出退款退货状态
            $list = $model_refund->getComplainRefundList($order_info, $complain_info['order_goods_id']);
            Tpl::output('refund_list',$list['refund']);//已退或处理中商品
            Tpl::output('refund_goods',$list['goods']);//可退商品
        }
        $this->show_menu('complain_progress');
        Tpl::output('order_info',$order_info);
        Tpl::output('complain_info',$complain_info);
						
		Tpl::setDirquna('shop');
        Tpl::showpage('complain.info');
    }

    /*
     * 审核提交的投诉
     */
    public function complain_verifyWt() {
        $complain_id = intval($_POST['complain_id']);
        $complain_info = $this->get_complain_info($complain_id);
        if(intval($complain_info['complain_state']) === self::STATE_NEW) {
            $model_complain = Model('complain');
            $update_array = array();
            $update_array['complain_state'] = self::STATE_APPEAL;
            $update_array['complain_handle_datetime'] = time();
            $update_array['complain_handle_member_id'] = $this->get_admin_id();
            $update_array['complain_active'] = self::STATE_ACTIVE;
            $where_array = array();
            $where_array['complain_id'] = $complain_id;
            if($model_complain->updateComplain($update_array,$where_array)) {
                $this->log(L('complain_verify_success').'['.$complain_id.']',1);

                // 发送商家消息
                $param = array();
                $param['code'] = 'complain';
                $param['store_id'] = $complain_info['accused_id'];
                $param['param'] = array(
                    'complain_id' => $complain_id
                );
                QueueClient::push('sendStoreMsg', $param);

                showMessage(Language::get('complain_verify_success'),'index.php?w=complain&t=complain_new_list');
            } else {
                showMessage(Language::get('complain_verify_fail'),'index.php?w=complain&t=complain_new_list');
            }
        } else {
            showMessage(Language::get('param_error'),'');
        }
    }

    /*
     * 关闭投诉
     */
    public function complain_closeWt() {
        //获取输入的数据
        $complain_id = intval($_POST['complain_id']);
        $final_handle_message = trim($_POST['final_handle_message']);
        //验证输入的数据
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$final_handle_message, "require"=>"true","validator"=>"Length","min"=>"1","max"=>"255","message"=>Language::get('final_handle_message_error')),
        );
        $error = $obj_validate->validate();
        if ($error != '') {
            showMessage($error);
        }
        $complain_info = $this->get_complain_info($complain_id);
        $current_state = intval($complain_info['complain_state']);
        if($current_state !== self::STATE_FINISH) {
            $model_complain = Model('complain');
            $update_array = array();
            $update_array['complain_state'] = self::STATE_FINISH;
            $update_array['final_handle_message'] = $final_handle_message;
            $update_array['final_handle_datetime'] = time();
            $update_array['final_handle_member_id'] = $this->get_admin_id();
            $where_array = array();
            $where_array['complain_id'] = $complain_id;
            if($model_complain->updateComplain($update_array,$where_array)) {
                if(intval($complain_info['complain_subject_id']) == 1) {//退款信息
                    $order = $this->get_order_info($complain_info['order_id']);
                    $model_refund = Model('refund_return');
                    $list = $model_refund->getComplainRefundList($order, $complain_info['order_goods_id']);
                    $refund_goods = $list['goods'];//可退商品
                    if (!empty($refund_goods) && is_array($refund_goods)) {
                        $checked_goods = $_POST['checked_goods'];
                        foreach ($refund_goods as $key => $value) {
                            $goods_id = $value['rec_id'];//订单商品表编号
                            if (!empty($checked_goods) && array_key_exists($goods_id,$checked_goods)) {//验证提交的商品属于订单
                                $refund_array = array();
                                $refund_array['refund_type'] = '1';//类型:1为退款,2为退货
                                $refund_array['seller_state'] = '2';//商家处理状态:1为待审核,2为同意,3为不同意
                                $refund_array['refund_state'] = '2';//状态:1为处理中,2为待管理员处理,3为已完成
                                $refund_array['order_lock'] = '1';//锁定类型:1为不用锁定,2为需要锁定
                                $refund_array['refund_amount'] = wtPriceFormat($value['goods_refund']);
                                $refund_array['reason_id'] = '0';
                                $refund_array['reason_info'] = '投诉成功';
                                $refund_array['buyer_message'] = '投诉成功,待管理员确认退款';
                                $refund_array['seller_message'] = '投诉成功,待管理员确认退款';
                                $refund_array['add_time'] = time();
                                $refund_array['seller_time'] = time();
                                $model_refund->addRefundReturn($refund_array,$order,$value);
                            }
                        }
                    }
                }
                $this->log(L('complain_close_success').'['.$complain_id.']',1);
                showMessage(Language::get('complain_close_success'),$this->get_complain_state_link($current_state));
            } else {
                showMessage(Language::get('complain_close_fail'),$this->get_complain_state_link($current_state));
            }
        } else {
            showMessage(Language::get('param_error'),'');
        }
    }

    /*
     * 投诉主题列表
     */
    public function complain_subject_listWt() {
        /*
         * 实例化分页
         */
        $page = new Page();
        $page->setEachNum(10) ;
        $page->setStyle('admin') ;
        /*
         * 获得举报主题列表
         */
        $model_complain_subject = Model('complain_subject') ;
        //搜索条件
        $condition = array();
        $condition['order'] = 'complain_subject_id asc';
        $condition['complain_subject_state'] = 1;
        $complain_subject_list = $model_complain_subject->getComplainSubject($condition,$page) ;
        $this->show_menu('complain_subject_list');
        Tpl::output('list', $complain_subject_list) ;
        Tpl::output('show_page',$page->show()) ;
						
		Tpl::setDirquna('shop');
        Tpl::showpage('complain_subject.list') ;
    }

    /*
     * 添加投诉主题页面
     */
    public function complain_subject_addWt() {
        $this->show_menu('complain_subject_add');
						
		Tpl::setDirquna('shop');
        Tpl::showpage('complain_subject.add') ;
    }

    /*
     * 保存添加的投诉主题
     */
    public function complain_subject_saveWt() {
        //获取提交的内容
        $input['complain_subject_content'] = trim($_POST['complain_subject_content']);
        $input['complain_subject_desc'] = trim($_POST['complain_subject_desc']);
        //验证提交的内容
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$input['complain_subject_content'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>Language::get('complain_subject_content_error')),
            array("input"=>$input['complain_subject_desc'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"100","message"=>Language::get('complain_subject_desc_error')),
        );
        $error = $obj_validate->validate();
        if ($error != '') {
            showMessage($error);
        } else {
            //验证成功保存
            $input['complain_subject_state'] = 1;
            $model_complain_subject = Model('complain_subject');
            $model_complain_subject->saveComplainSubject($input);
            $this->log(L('complain_subject_add_success').'['.$_POST['complain_subject_content'].']',1);
            showMessage(Language::get('complain_subject_add_success'),'index.php?w=complain&t=complain_subject_list');
        }
    }

    /*
     * 删除投诉主题,伪删除只是修改标记
     */
    public function complain_subject_dropWt() {
        $complain_subject_id = trim($_GET['complain_subject_id']);
        if(empty($complain_subject_id)) {
            showMessage(Language::get('param_error'),'index.php?w=complain');
        }
        $model_complain_subject= Model('complain_subject');
        $update_array = array();
        $update_array['complain_subject_state'] = 2;
        $where_array = array();
        $where_array['in_complain_subject_id'] = "'".implode("','", explode(',', $complain_subject_id))."'";
        if($model_complain_subject->updateComplainSubject($update_array,$where_array)) {
            $this->log(L('complain_subject_delete_success').'[ID:'.$_GET['complain_subject_id'].']',1);
            showMessage(Language::get('complain_subject_delete_success'),'index.php?w=complain&t=complain_subject_list');
        } else {
            showMessage(Language::get('complain_subject_delete_fail'),'index.php?w=complain&t=complain_subject_list');
        }
    }

    /*
     * 根据投诉id获取投诉对话列表
     */
    public function get_complain_talkWt() {
        $complain_id = intval($_POST['complain_id']);
        $complain_info = $this->get_complain_info($complain_id);
        $complain_talk_list = $this->get_talk_list($complain_id);
        $talk_list = array();
        $i=0;
        foreach($complain_talk_list as $talk) {
            $talk_list[$i]['css'] = $talk['talk_member_type'];
            $talk_list[$i]['talk'] = date("Y-m-d",$talk['talk_datetime']);
            switch($talk['talk_member_type']) {
                case 'accuser':
                    $talk_list[$i]['talk'] .= Language::get('complain_accuser');
                    break;
                case 'accused':
                    $talk_list[$i]['talk'] .= Language::get('complain_accused');
                    break;
                case 'admin':
                    $talk_list[$i]['talk'] .= Language::get('complain_admin');
                    break;
                default:
                    $talk_list[$i]['talk'] .= Language::get('complain_unknow');
            }
            if(intval($talk['talk_state']) === 2) {
                $talk['talk_content'] = Language::get('talk_forbit_message');
                $forbit_link = '';
            } else {
                $forbit_link = "&nbsp;&nbsp;<a href='#' onclick=forbit_talk(".$talk['talk_id'].")>".Language::get('complain_text_forbit')."</a>";
            }
            $talk_list[$i]['talk'].= '('.$talk['talk_member_name'].')'.Language::get('complain_text_say').':'.$talk['talk_content'].$forbit_link;
            $i++;
        }
        if (strtoupper(CHARSET) == 'GBK') {
            $talk_list = Language::getUTF8($talk_list);
        }
        echo json_encode($talk_list);
    }

    /*
     * 发布投诉对话
     */
    public function publish_complain_talkWt() {
        $complain_id = intval($_POST['complain_id']);
        $complain_talk = trim($_POST['complain_talk']);
        $talk_len = strlen($complain_talk);
        if($talk_len > 0 && $talk_len < 255) {
            $complain_info = $this->get_complain_info($complain_id);
            $model_complain_talk = Model('complain_talk');
            $param = array();
            $param['complain_id'] = $complain_id;
            $param['talk_member_id'] = $this->get_admin_id();
            $param['talk_member_name'] = $this->get_admin_name();
            $param['talk_member_type'] = 'admin';
            if (strtoupper(CHARSET) == 'GBK') {
                $complain_talk = Language::getGBK($complain_talk);
            }
            $param['talk_content'] = $complain_talk;
            $param['talk_state'] =1;
            $param['talk_admin'] = 0;
            $param['talk_datetime'] = time();
            if($model_complain_talk->saveComplainTalk($param)) {
                echo json_encode('success');
            } else {
                echo json_encode('error2');
            }
        } else {
            echo json_encode('error1');
        }
    }

    /*
     * 屏蔽对话
     */
    public function forbit_talkWt() {
        $talk_id = intval($_POST['talk_id']);
        if(!empty($talk_id)&&is_integer($talk_id)) {
            $model_complain_talk = Model('complain_talk');
            $update_array = array();
            $update_array['talk_state'] = 2;
            $update_array['talk_admin'] = $this->get_admin_id();
            $where_array = array();
            $where_array['talk_id'] = $talk_id;
            if($model_complain_talk->updateComplainTalk($update_array,$where_array)) {
                echo json_encode('success');
            } else {
                echo json_encode('error2');
            }
        } else {
            echo json_encode('error1');
        }
    }

    /**
     * 投诉设置
     **/
    public function complain_settingWt() {
        //读取设置内容 $list_setting
        $model_setting = Model('setting');
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
        $this->show_menu('complain_setting');
						
		Tpl::setDirquna('shop');
        Tpl::showpage('complain.setting') ;
    }

    /**
     * 投诉设置保存
     **/
    public function complain_setting_saveWt() {
        $model_setting = Model('setting');
        $complain_time_limit = intval($_POST['complain_time_limit']);
        if(empty($complain_time_limit)) {
            //如果输入不合法默认30天
            $complain_time_limit = 2592000;
        } else {
            $complain_time_limit = $complain_time_limit*86400;
        }
        $update_array['complain_time_limit'] = $complain_time_limit;
        if($model_setting->updateSetting($update_array)) {
            $this->log(L('complain_setting_save_success'),1);
            showMessage(Language::get('complain_setting_save_success'),'');
        } else {
            showMessage(Language::get('complain_setting_save_fail'),'');
        }
    }

    /*
     * 获取订单信息
     */
    private function get_order_info($order_id) {
        $model_order = Model('order');
        $order_info = $model_order->getOrderInfo(array('order_id' => $order_id),array('order_goods'));
        if(empty($order_info)) {
            showMessage(Language::get('param_error'));
        }
        $order_info['order_state_text'] = orderState($order_info);
        return $order_info;
    }

    /*
     * 获取投诉信息
     */
    private function get_complain_info($complain_id) {
        $model_complain = Model('complain');
        $complain_info = $model_complain->getoneComplain($complain_id);
        if(empty($complain_info)) {
            showMessage(Language::get('param_error'));
        }
        $complain_info['complain_state_text'] = $this->get_complain_state_text($complain_info['complain_state']);
        return $complain_info;
    }

    /*
     * 获取投诉商品列表
     */
    private function get_complain_goods_list($order_goods_id) {
        $model_order = Model('order');
        $param = array();
        $param['rec_id'] = $order_goods_id;
        $complain_goods_list = $model_order->getOrderGoodsList($param);
        return $complain_goods_list;
    }

    /*
     * 获取对话列表
     */
    private function get_talk_list($complain_id) {
        $model_complain_talk = Model('complain_talk');
        $param = array();
        $param['complain_id'] = $complain_id;
        $talk_list = $model_complain_talk->getComplainTalk($param);
        return $talk_list;
    }

    /*
     * 获得投诉状态文本
     */
    private function get_complain_state_text($complain_state) {
        switch(intval($complain_state)) {
            case self::STATE_NEW:
                return Language::get('complain_state_new');
                break;
            case self::STATE_APPEAL:
                return Language::get('complain_state_appeal');
                break;
            case self::STATE_TALK:
                return Language::get('complain_state_talk');
                break;
            case self::STATE_HANDLE:
                return Language::get('complain_state_handle');
                break;
            case self::STATE_FINISH:
                return Language::get('complain_state_finish');
                break;
            default:
                showMessage(Language::get('param_error'),'');
        }
    }

    /*
     * 获得投诉状态文本
     */
    private function get_complain_state_link($complain_state) {
        switch(intval($complain_state)) {
            case self::STATE_NEW:
                return 'index.php?w=complain&t=complain_new_list';
                break;
            case self::STATE_APPEAL:
                return 'index.php?w=complain&t=complain_appeal_list';
                break;
            case self::STATE_TALK:
                return 'index.php?w=complain&t=complain_talk_list';
                break;
            case self::STATE_HANDLE:
                return 'index.php?w=complain&t=complain_handle_list';
                break;
            case self::STATE_FINISH:
                return 'index.php?w=complain&t=complain_finish_list';
                break;
            default:
                showMessage(Language::get('param_error'));
        }
    }

    /*
     * 获得管理员id
     */
    private function get_admin_id() {
        $admin_info = $this->getAdminInfo();
        return $admin_info['id'];
    }

    /*
     * 获得管理员name
     */
    private function get_admin_name() {
        $admin_info = $this->getAdminInfo();
        return $admin_info['name'];
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function show_menu($menu_key) {
        $menu_array = array(
            'complain_new_list'=>array('menu_type'=>'link','menu_name'=>Language::get('complain_new_list'),'menu_url'=>'index.php?w=complain&t=complain_new_list'),
            'complain_appeal_list'=>array('menu_type'=>'link','menu_name'=>Language::get('complain_appeal_list'),'menu_url'=>'index.php?w=complain&t=complain_appeal_list'),
            'complain_talk_list'=>array('menu_type'=>'link','menu_name'=>Language::get('complain_talk_list'),'menu_url'=>'index.php?w=complain&t=complain_talk_list'),
            'complain_handle_list'=>array('menu_type'=>'link','menu_name'=>Language::get('complain_handle_list'),'menu_url'=>'index.php?w=complain&t=complain_handle_list'),
            'complain_finish_list'=>array('menu_type'=>'link','menu_name'=>Language::get('complain_finish_list'),'menu_url'=>'index.php?w=complain&t=complain_finish_list'),
            'complain_subject_list'=>array('menu_type'=>'link','menu_name'=>Language::get('complain_subject_list'),'menu_url'=>'index.php?w=complain&t=complain_subject_list'),
            'complain_subject_add'=>array('menu_type'=>'link','menu_name'=>Language::get('complain_subject_add'),'menu_url'=>'index.php?w=complain&t=complain_subject_add'),
            'complain_setting'=>array('menu_type'=>'link','menu_name'=>Language::get('complain_setting'),'menu_url'=>'index.php?w=complain&t=complain_setting'),
            'complain_progress'=>array('menu_type'=>'link','menu_name'=>Language::get('complain_progress'),'menu_url'=>'###'),
        );
        if($menu_key !== 'complain_progress') {
            unset($menu_array['complain_progress']);
        }
        $menu_array[$menu_key]['menu_type'] = 'text';
        Tpl::output('menu',$menu_array);
    }
}
