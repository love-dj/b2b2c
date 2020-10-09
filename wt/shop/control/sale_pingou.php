<?php
/**
 * 拼团管理 v6.4
 *
 *
 *
 * *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class sale_pingouControl extends SystemControl{

    public function __construct(){
        parent::__construct();
        $model_pingou = Model('p_pingou');
        $model_pingou->getStateArray();
    }

    /**
     * 默认
     */
    public function indexWt() {
        $this->pingou_listWt();

    }

    /**
     * 活动列表
     */
    public function pingou_listWt()
    {
        $this->show_menu('pingou_list');
		Tpl::setDirquna('shop');
        Tpl::showpage('sale_pingou.list');
    }

    /**
     * 活动列表
     */
    public function pingou_list_xmlWt()
    {
        $condition = array();
        if ($_REQUEST['showanced']) {
            if (strlen($q = trim((string) $_REQUEST['pingou_name']))) {
                $condition['pingou_name'] = array('like', '%' . $q . '%');
            }
            if (strlen($q = trim((string) $_REQUEST['store_name']))) {
                $condition['store_name'] = array('like', '%' . $q . '%');
            }
            if ($_REQUEST['state'] != '') {
                $condition['state'] = intval($_REQUEST['state']);
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
                    case 'pingou_name':
                        $condition['pingou_name'] = array('like', '%'.$q.'%');
                        break;
                    case 'store_name':
                        $condition['store_name'] = array('like', '%'.$q.'%');
                        break;
                }
            }
        }

        $model_pingou = Model('p_pingou');
        $pingou_list = (array) $model_pingou->getList($condition, $_REQUEST['rp']);
        $state_array = $model_pingou->getStateArray();

        $flippedOwnShopIds = array_flip(Model('store')->getOwnShopIds());

        $data = array();
        $data['now_page'] = $model_pingou->shownowpage();
        $data['total_num'] = $model_pingou->gettotalnum();

        foreach ($pingou_list as $val) {
            $o  = '<a class="btn red confirm-on-click" href="javascript:;" data-href="' . urlAdminShop('sale_pingou', 'pingou_del', array(
                'pingou_id' => $val['pingou_id'],
            )) . '"><i class="fa fa-trash-o"></i>删除</a>';

            $o .= '<span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em><ul>';

            if ($val['state']) {
                $o .= '<li><a class="confirm-on-click" href="javascript:;" data-href="' . urlAdminShop('sale_pingou', 'pingou_cancel', array(
                    'pingou_id' => $val['pingou_id'],
                )) . '">取消活动</a></li>';
            }

            $o .= '<li><a class="confirm-on-click" href="' . urlAdminShop('sale_pingou', 'pingou_detail', array(
                'pingou_id' => $val['pingou_id'],
            )) . '">活动详细</a></li>';

            $o .= '</ul></span>';

            $i = array();
            $i['operation'] = $o;
            $i['pingou_name'] = $val['pingou_name'];
            $i['store_name'] = '<a target="_blank" href="' . urlShop('show_store', 'index', array(
                'store_id'=>$val['store_id'],
            )) . '">' . $val['store_name'] . '</a>';

            if (isset($flippedOwnShopIds[$val['store_id']])) {
                $i['store_name'] .= '<span class="ownshop">[自营]</span>';
            }

            $i['start_time_text'] = date('Y-m-d H:i', $val['start_time']);
            $i['end_time_text'] = date('Y-m-d H:i', $val['end_time']);

            $i['min_num'] = $val['min_num'];
            $i['state_text'] = $val['end_time'] > TIMESTAMP ? $state_array[$val['state']]:'已结束';

            $data['list'][$val['pingou_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 活动取消
     **/
    public function pingou_cancelWt() {
        $pingou_id = intval($_REQUEST['pingou_id']);
        $model_pingou = Model('p_pingou');
        $result = $model_pingou->cancel(array('pingou_id' => $pingou_id));
        if($result) {
            $this->log('取消拼团活动，活动编号'.$pingou_id);
            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }

    /**
     * 活动删除
     **/
    public function pingou_delWt() {
        $pingou_id = intval($_REQUEST['pingou_id']);
        $model_pingou = Model('p_pingou');
        $result = $model_pingou->del(array('pingou_id' => $pingou_id));
        if($result) {
            $this->log('删除拼团活动，活动编号'.$pingou_id);

            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }

    /**
     * 活动详细信息
     **/
    public function pingou_detailWt() {
        $pingou_id = intval($_GET['pingou_id']);

        $model_pingou = Model('p_pingou');
        $condition = array();
        $condition['pingou_id'] = $pingou_id;
        $pingou_info = $model_pingou->getInfo($condition);
        Tpl::output('pingou_info',$pingou_info);

        //获取商品列表
        $pingou_goods_list = $model_pingou->getGoodsList($condition);
        Tpl::output('list',$pingou_goods_list);
		Tpl::setDirquna('shop');
        Tpl::showpage('sale_pingou.detail');
    }

    /**
     * 套餐管理
     */
    public function pingou_quotaWt()
    {
        $this->show_menu('pingou_quota');
		Tpl::setDirquna('shop');
        Tpl::showpage('sale_pingou_quota.list');
    }

    /**
     * 套餐管理XML
     */
    public function pingou_quota_xmlWt()
    {
        $condition = array();

        if (strlen($q = trim($_REQUEST['query']))) {
            switch ($_REQUEST['qtype']) {
                case 'store_name':
                    $condition['store_name'] = array('like', '%'.$q.'%');
                    break;
            }
        }

        $model_pingou = Model('p_pingou');
        $list = (array) $model_pingou->getQuotaList($condition, $_REQUEST['rp']);

        $data = array();
        $data['now_page'] = $model_pingou->shownowpage();
        $data['total_num'] = $model_pingou->gettotalnum();

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
    public function pingou_settingWt() {
        $model_setting = Model('setting');
        $setting = $model_setting->GetListSetting();
		Tpl::setDirquna('shop');
        Tpl::output('setting',$setting);

        $this->show_menu('pingou_setting');
        Tpl::showpage('sale_pingou.setting');
    }

    public function pingou_setting_saveWt() {
        $sale_pingou_price = intval($_POST['sale_pingou_price']);
        if($sale_pingou_price < 0) {
            $sale_pingou_price = 20;
        }
        $model_setting = Model('setting');
        $update_array = array();
        $update_array['sale_pingou_price'] = $sale_pingou_price;
        $result = $model_setting->updateSetting($update_array);
        if ($result){
            $this->log('修改拼团价格为'.$sale_pingou_price.'元');
            showMessage(Language::get('wt_common_save_succ'),'');
        }else {
            showMessage(Language::get('wt_common_save_fail'),'');
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
            'pingou_list'=>array('menu_type'=>'link','menu_name'=>'活动列表','menu_url'=>'index.php?w=sale_pingou&t=pingou_list'),
            'pingou_quota'=>array('menu_type'=>'link','menu_name'=>'套餐管理','menu_url'=>'index.php?w=sale_pingou&t=pingou_quota'),
            'pingou_setting'=>array('menu_type'=>'link','menu_name'=>'设置','menu_url'=>'index.php?w=sale_pingou&t=pingou_setting'),
        );
        $menu_array[$menu_key]['menu_type'] = 'text';
        Tpl::output('menu',$menu_array);
    }
}
