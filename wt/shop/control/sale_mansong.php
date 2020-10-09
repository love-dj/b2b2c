<?php
/**
 * 满即送管理
 *
 *
 *
 *

 
  
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class sale_mansongControl extends SystemControl{

    public function __construct(){
        parent::__construct();

        //读取语言包
        Language::read('sale_mansong');

        //检查审核功能是否开启
        if (intval($_GET['sale_allow']) !== 1 && intval(C('sale_allow')) !== 1){
            $url = array(
                array(
                    'url'=>'index.php?w=sale_mansong&sale_allow=1',
                    'msg'=>Language::get('open'),
                ),
                array(
                    'url'=>'index.php?w=setting',
                    'msg'=>Language::get('close'),
                ),
            );
            showMessage(Language::get('sale_unavailable'),$url,'html','succ',1,6000);
        }
    }

    /**
     * 默认Op
     */
    public function indexWt() {

        //自动开启满就送
        if (intval($_GET['sale_allow']) === 1){
            $model_setting = Model('setting');
            $update_array = array();
            $update_array['sale_allow'] = 1;
            $model_setting->updateSetting($update_array);
        }

        $this->mansong_listWt();
    }

    /**
     * 活动列表
     */
    public function mansong_listWt()
    {
        $model_mansong = Model('p_mansong');
        $mansong_state_array = $model_mansong->getMansongStateArray();
        Tpl::output('mansong_state_array', $mansong_state_array);

        $this->show_menu('mansong_list');
		Tpl::setDirquna('shop');
        Tpl::showpage('sale_mansong.list');
    }

    /**
     * 活动列表XML
     */
    public function mansong_list_xmlWt()
    {
        $condition = array();

        if ($_REQUEST['showanced']) {
            if (strlen($q = trim((string) $_REQUEST['mansong_name']))) {
                $condition['mansong_name'] = array('like', '%' . $q . '%');
            }
            if (strlen($q = trim((string) $_REQUEST['store_name']))) {
                $condition['store_name'] = array('like', '%' . $q . '%');
            }
            if (($q = (int) $_REQUEST['state']) > 0) {
                $condition['state'] = $q;
            }

            $pdates = array();
            if (strlen($q = trim((string) $_REQUEST['pdate1'])) && ($q = strtotime($q . ' 00:00:00'))) {
                $pdates[] = "end_time >= {$q}";
            }
            if (strlen($q = trim((string) $_REQUEST['pdate2'])) && ($q = strtotime($q . ' 00:00:00'))) {
                $pdates[] = "start_time <= {$q}";
            }
            if ($pdates) {
                $condition['pdates'] = array(
                    'exp',
                    implode(' or ', $pdates),
                );
            }

        } else {
            if (strlen($q = trim($_REQUEST['query']))) {
                switch ($_REQUEST['qtype']) {
                    case 'mansong_name':
                        $condition['mansong_name'] = array('like', '%'.$q.'%');
                        break;
                    case 'store_name':
                        $condition['store_name'] = array('like', '%'.$q.'%');
                        break;
                }
            }
        }

        $model_mansong = Model('p_mansong');
        $list = (array) $model_mansong->getMansongList($condition, $_REQUEST['rp']);

        $mansongStates = $model_mansong->getMansongStateArray();
        $flippedOwnShopIds = array_flip(Model('store')->getOwnShopIds());

        $data = array();
        $data['now_page'] = $model_mansong->shownowpage();
        $data['total_num'] = $model_mansong->gettotalnum();

        foreach ($list as $val) {
            $u = urlAdminShop('sale_mansong', 'mansong_detail', array(
                'mansong_id' => $val['mansong_id'],
            ));

            $o = '<a class="btn red confirm-on-click" href="javascript:;" data-href="' . urlAdminShop('sale_mansong', 'mansong_del', array(
                    'mansong_id' => $val['mansong_id'],
            )) . '"><i class="fa fa-trash-o"></i>删除</a>';

            $o .= '<span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em><ul>';

            if ($val['editable']) {
                $o .= '<li><a class="confirm-on-click" href="javascript:;" data-href="' . urlAdminShop('sale_mansong', 'mansong_cancel', array(
                    'mansong_id' => $val['mansong_id'],
                )) . '">取消活动</a></li>';
            }

            $o .= <<<EOB
<li><a href="javascript:;" onclick="ajax_form('mansong_detail', '店铺满即送活动详情', '{$u}', 640)">活动详细</a></li>
EOB;

            $o .= '</ul></span>';

            $i = array();
            $i['operation'] = $o;
            $i['mansong_name'] = $val['mansong_name'];
            $i['store_name'] = '<a target="_blank" href="' . urlShop('show_store', 'index', array(
                'store_id' => $val['store_id'],
            )) . '">' . $val['store_name'] . '</a>';

            if (isset($flippedOwnShopIds[$val['store_id']])) {
                $i['store_name'] .= '<span class="ownshop">[自营]</span>';
            }

            $i['start_time_text'] = date('Y-m-d H:i', $val['start_time']);
            $i['end_time_text'] = date('Y-m-d H:i', $val['end_time']);

            $i['mansong_state_text'] = $val['mansong_state_text'];

            $data['list'][$val['mansong_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 活动详细信息
     * temp
     **/
    public function mansong_detailWt() {
        $mansong_id = intval($_GET['mansong_id']);

        $model_mansong = Model('p_mansong');
        $model_mansong_rule = Model('p_mansong_rule');

        $mansong_info = $model_mansong->getMansongInfoByID($mansong_id);
        if(empty($mansong_info)) {
            showMessage(L('param_error'));
        }
        Tpl::output('mansong_info', $mansong_info);

        $param = array();
        $param['mansong_id'] = $mansong_id;
        $rule_list = $model_mansong_rule->getMansongRuleListByID($mansong_id);
        Tpl::output('list',$rule_list);

        $this->show_menu('mansong_detail');
		Tpl::setDirquna('shop');
        Tpl::showpage('sale_mansong.detail', 'null_layout');
    }

    /**
     * 满即送活动取消
     **/
    public function mansong_cancelWt() {
        $mansong_id = intval($_REQUEST['mansong_id']);
        $model_mansong = Model('p_mansong');
        $result = $model_mansong->cancelMansong(array('mansong_id' => $mansong_id));
        if($result) {
            $this->log('取消满即送活动，活动编号'.$mansong_id);

            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }

    /**
     * 满即送活动删除
     **/
    public function mansong_delWt() {
        $mansong_id = intval($_REQUEST['mansong_id']);
        $model_mansong = Model('p_mansong');
        $result = $model_mansong->delMansong(array('mansong_id' => $mansong_id));
        if($result) {
            $this->log('删除满即送活动，活动编号'.$mansong_id);

            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }


    /**
     * 套餐管理
     */
    public function mansong_quotaWt()
    {
        $this->show_menu('mansong_quota');
		Tpl::setDirquna('shop');
        Tpl::showpage('sale_mansong_quota.list');
    }

    /**
     * 套餐管理XML
     */
    public function mansong_quota_xmlWt()
    {
        $condition = array();

        if (strlen($q = trim($_REQUEST['query']))) {
            switch ($_REQUEST['qtype']) {
                case 'store_name':
                    $condition['store_name'] = array('like', '%'.$q.'%');
                    break;
            }
        }

        $model_mansong_quota = Model('p_mansong_quota');
        $list = (array) $model_mansong_quota->getMansongQuotaList($condition, $_REQUEST['rp'], 'quota_id desc');

        $data = array();
        $data['now_page'] = $model_mansong_quota->shownowpage();
        $data['total_num'] = $model_mansong_quota->gettotalnum();

        foreach ($list as $val) {
            $i = array();
            $i['operation'] = '<span>--</span>';

            $i['store_name'] = '<a target="_blank" href="' . urlShop('show_store', 'index', array(
                'store_id' => $val['store_id'],
            )) . '">' . $val['store_name'] . '</a>';

            $i['start_time_text'] = date("Y-m-d", $val['start_time']);
            $i['end_time_text'] = date("Y-m-d", $val['end_time']);

            $data['list'][$val['quota_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 设置
     **/
    public function mansong_settingWt() {

        $model_setting = Model('setting');
        $setting = $model_setting->GetListSetting();
        Tpl::output('setting',$setting);

        $this->show_menu('mansong_setting');
		Tpl::setDirquna('shop');
        Tpl::showpage('sale_mansong.setting');
    }

    public function mansong_setting_saveWt() {

        $sale_mansong_price = intval($_POST['sale_mansong_price']);
        if($sale_mansong_price < 0) {
            $sale_mansong_price = 20;
        }

        $model_setting = Model('setting');
        $update_array = array();
        $update_array['sale_mansong_price'] = $sale_mansong_price;

        $result = $model_setting->updateSetting($update_array);
        if ($result === true){
            $this->log(L('wt_config,wt_sale_mansong,mansong_price'));
            showMessage(Language::get('setting_save_success'),'');
        }else {
            showMessage(Language::get('setting_save_fail'),'');
        }
    }

    /**
     * 页面内导航菜单
     *
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function show_menu($menu_key) {
        $menu_array = array(
            'mansong_list'=>array('menu_type'=>'link','menu_name'=>Language::get('mansong_list'),'menu_url'=>urlAdminShop('sale_mansong', 'mansong_list')),
            'mansong_quota'=>array('menu_type'=>'link','menu_name'=>Language::get('mansong_quota'),'menu_url'=>urlAdminShop('sale_mansong', 'mansong_quota')),
            'mansong_detail'=>array('menu_type'=>'link','menu_name'=>Language::get('mansong_detail'),'menu_url'=>urlAdminShop('sale_mansong', 'mansong_detail')),
            'mansong_setting'=>array('menu_type'=>'link','menu_name'=>Language::get('mansong_setting'),'menu_url'=>urlAdminShop('sale_mansong', 'mansong_setting')),
        );
        if($menu_key != 'mansong_detail') unset($menu_array['mansong_detail']);
        $menu_array[$menu_key]['menu_type'] = 'text';
        Tpl::output('menu',$menu_array);
    }

}
