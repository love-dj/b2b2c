<?php
/**
 * 物流工具
 *
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class store_transportControl extends BaseSellerControl {

    public function __construct(){
        parent::__construct();
        if ($_GET['type'] != '' && $_GET['type'] != 'select') $_GET['type'] = 'select';
        if ($_POST['type'] != '' && $_POST['type'] != 'select') $_POST['type'] = 'select';
        $goods_fee_array = array(
            '1' => '买家',
            '2' => '商家'
            );//运费承担:1买家承担2是商家承担
        Tpl::output('goods_fee_array', $goods_fee_array);
        
        $goods_trans_array = array(
            '1' => '按件数',
            '2' => '按重量',
            '3' => '按体积'
            );//计费规则:1是按件数2是按重量3是按体积
        Tpl::output('goods_trans_array', $goods_trans_array);
    }

    public function indexWt(){
        $this->listWt();
    }

    /**
     * 运费模板列表
     *
     */
    public function listWt(){

        //读取语言包
        Language::read('transport');

        $model_transport    = Model('transport');
        $list = $model_transport->getTransportList(array('store_id'=>$_SESSION['store_id']),4);
        if (!empty($list) && is_array($list)){
            $transport = array();
            foreach ($list as $v) {
                if (!array_key_exists($v['id'],$transport)){
                    $transport[$v['id']] = $v['title'];
                }
            }
            $extend = $model_transport->getExtendList(array('transport_id'=>array('in',array_keys($transport))));
            // 整理
            if (!empty($extend)) {
                $tmp_extend = array();
                foreach ($extend as $val) {
                    $tmp_extend[$val['transport_id']]['data'][] = $val;
                }
                $extend = $tmp_extend;
            }
        }

        /**
         * 页面输出
         */
        Tpl::output('list',$list);
        Tpl::output('extend',$extend);
        Tpl::output('show_page',$model_transport->showpage());
        self::profile_menu('transport','transport');
        Tpl::showpage('store_transport.list');
    }

    /**
     * 新增运费模板
     *
     */
    public function addWt(){

        //读取语言包
        Language::read('transport');

        $areas = Model('area')->getAreas();
        Tpl::output('areas', $areas);

        self::profile_menu('transport','transport');
        Tpl::showpage('store_transport.add');
    }

    public function editWt(){

        //读取语言包
        Language::read('transport');

        $id = intval($_GET['id']);
        $model_transport = Model('transport');
        $transport = $model_transport->getTransportInfo(array('id'=>$id));
        $extend = $model_transport->getExtendInfo(array('transport_id'=>$id));
        Tpl::output('transport',$transport);
        Tpl::output('extend',$extend);

        $areas = Model('area')->getAreas();
        Tpl::output('areas', $areas);

        self::profile_menu('transport','transport');
        Tpl::showpage('store_transport.edit');
    }

    public function deleteWt(){
        //读取语言包
        Language::read('transport');
        $id = intval($_GET['id']);
        $model_transport = Model('transport');
        $transport = $model_transport->getTransportInfo(array('id'=>$id));
        if ($transport['store_id'] != $_SESSION['store_id']){
            showMessage(Language::get('transport_op_fail'),getReferer(),'html','error');
        }
        //查看是否正在被使用
        if ($model_transport->isUsing($id)){
            showMessage(Language::get('transport_op_using'),getReferer(),'html','error');
        }
        if($model_transport->delTansport(array('id'=>$id))){
            header('location: '.getReferer());exit;
        }else{
            showMessage(Language::get('transport_op_fail'),getReferer(),'html','error');
        }
    }

    public function cloneWt(){

        //读取语言包
        Language::read('transport');

        $id = intval($_GET['id']);
        $model_transport = Model('transport');
        $transport = $model_transport->getTransportInfo(array('id'=>$id));
        unset($transport['id']);
        $transport['title'] .= Language::get('transport_clone_name');
        $transport['update_time'] = time();

        try {
            $model_transport->beginTransaction();
            $insert = $model_transport->addTransport($transport);
            if ($insert) {
                $extend = $model_transport->getExtendList(array('transport_id'=>$id));
                foreach ($extend as $k=>$v) {
                    foreach ($v as $key=>$value) {
                        $extend[$k]['transport_id'] = $insert;
                    }
                    unset($extend[$k]['id']);
                }
                $insert = $model_transport->addExtend($extend);
            }
            if (!$insert) throw new Exception(Language::get('transport_op_fail'));
            $model_transport->commit();
            header('location: '.getReferer());exit;
        }catch (Exception $e){
            $model_transport->rollback();
            showMessage($e->getMessage(),getReferer(),'html','error');
        }
    }

    /**
     * 保存运费模板
     *
     */
    public function saveWt(){
        if (!chksubmit()) return false;
        //读取语言包
        Language::read('transport');

        $trans_info = array();
        $trans_info['title']        = $_POST['title'];
        $trans_info['send_tpl_id']  = 1;
        $trans_info['store_id']     = $_SESSION['store_id'];
        $trans_info['update_time']  = TIMESTAMP;
        $trans_info['goods_fee_type']        = intval($_POST['goods_fee_type']);//1买家承担2是商家承担

        $model_transport = Model('transport');

        if (is_numeric($_POST['transport_id'])){
            //编辑时，删除所有附加表信息
            $transport_id = intval($_POST['transport_id']);
            $model_transport->transUpdate($trans_info,array('id'=>intval($_POST['transport_id'])));
            $model_transport->delExtend($transport_id);
        }else{
            if (empty($_POST['special'])) showMessage(Language::get('transport_op_fail'),getReferer(),'html','error');
            
            $trans_info['goods_trans_type']        = intval($_POST['goods_trans_type']);//1是按件数2是按重量3是按体积
            //新增
            $transport_id = $model_transport->addTransport($trans_info);
        }

        $trans_list = array();
        $areas = $_POST['areas']['kd'];
        $special = $_POST['special']['kd'];

        if (is_array($special)){
            foreach ($special as $key=>$value) {
                $tmp = array();
                if (empty($areas[$key])) continue;
                $areas[$key] = explode('|||',$areas[$key]);
                $tmp['area_id'] = ','.$areas[$key][0].',';
                $tmp['area_name'] = $areas[$key][1];
                $tmp['snum'] = $value['start'];
                $tmp['sprice'] = $value['postage'];
                $tmp['xnum'] = $value['plus'];
                $tmp['xprice'] = $value['postageplus'];
                $tmp['transport_id'] = $transport_id;
                $tmp['transport_title'] = $_POST['title'];
                //计算省份ID
                $province = array();
                $tmp1 = explode(',',$areas[$key][0]);
                if (!empty($tmp1) && is_array($tmp1)){
                    $city = Model('area')->getCityProvince();
                    foreach ($tmp1 as $t) {
                        $pid = $city[$t];
                        if (!in_array($pid,$province) && !empty($pid))$province[] = $pid;
                    }
                }
                if (count($province)>0){
                    $tmp['top_area_id'] = ','.implode(',',$province).',';
                }else{
                    $tmp['top_area_id'] = '';
                }
                $trans_list[] = $tmp;
            }
        }

        $result = $model_transport->addExtend($trans_list);
        if ($result){
            header('location: index.php?w=store_transport&type='.$_POST['type']);exit;
        }else{
            showMessage(Language::get('transport_op_fail'),getReferer(),'html','error');
        }
    }

    /**
     * 货到付款地区设置
     *
     */
    public function offpay_areaWt()
    {
        if (!checkPlatformStore()) {
            showMessage('本功能仅限平台自营店使用', '', 'html', 'error');
        }

        $model_parea = Model('offpay_area');

        $store_id = $_SESSION['store_id'];

        if (chksubmit()) {
            if (!preg_match('/^[\d,]+$/',$_POST['county'])) {
                $_POST['county'] = '';
            }
            //内置自营店ID
            $area_info = $model_parea->getAreaInfo(array('store_id'=>$store_id));
            $data = array();
            $county = trim($_POST['county'],',');
            $data['area_id'] = serialize(explode(',',$county));
            if (!$area_info) {
                $data['store_id'] = $store_id;
                $result = $model_parea->addArea($data);
            } else {
                $result = $model_parea->updateArea(array('store_id'=>$store_id),$data);
            }
            if ($result) {
                showMessage('保存成功');
            } else {
                showMessage('保存失败','','html','error');
            }
        }

        //取出支持货到付款的县ID及上级市ID
        $parea_info = $model_parea->getAreaInfo(array('store_id'=>$store_id));
        if (!empty($parea_info['area_id'])) {
            $parea_ids = @unserialize($parea_info['area_id']);
        }
        if (empty($parea_ids)) {
            $parea_ids = array();
        }

        Tpl::output('areaIds', $parea_ids);

        $model_area = Model('area');
        $areas = $model_area->getAreas();
        Tpl::output('areas', $areas);

        //取出支持货到付款县ID的上级市ID
        $city_checked_child_array = array();

        foreach ($parea_ids as $i)
            if (isset($areas['parent'][$i]))
                $city_checked_child_array[$areas['parent'][$i]][] = $i;

        Tpl::output('city_checked_child_array', $city_checked_child_array);

        //市级下面的县是不是全部支持货到付款，如果全部支持，默认选中
        //如果其中部分县支持货到付款，默认不选中但显示一个支付到付县的数量

        //格式 city_id => 下面支持到付的县ID数量
        $city_count_array = array();

        //格式 city_id => 是否选中true/false
        $city_checked_array = array();

        foreach ($city_checked_child_array as $city_id => $c) {
            $city_count_array[$city_id] = count($areas['children'][$city_id]);

            $c = count($c);
            if ($c > 0 && $c == $city_count_array[$city_id]) {
                $city_checked_array[$city_id] = true;
            }
        }

        Tpl::output('city_count_array', $city_count_array);
        Tpl::output('city_checked_array', $city_checked_array);

        //计算哪些省需要默认选中(即该省下面的所有县都支持到付，即所有市都是选中状态)
        $province_checked_array = array();
        foreach ($areas['children'][0] as $province_id) {
            $b = true;
            if (is_array($areas['children'][$province_id])) {
                foreach ($areas['children'][$province_id] as $city_id) {
                    if (empty($city_checked_array[$city_id])) {
                        $b = false;
                        break;
                    }
                }                
            }
            if ($b)
                $province_checked_array[$province_id] = true;
        }
        Tpl::output('province_checked_array', $province_checked_array);

        $area_array_json = json_encode($model_area->getAreaArrayForJson());
        Tpl::output('area_array_json', $area_array_json);

        Language::read('transport');
        self::profile_menu('transport', 'offpay_area');

        Tpl::showpage('store_transport.offpay_area');
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key='') {
        Language::read('member_layout');
        $menu_array     = array();
        switch ($menu_type) {
            case 'transport':
            case 'offpay_area':
                $menu_array = array(
                1=>array('menu_key'=>'transport',   'menu_name'=>Language::get('wt_member_path_postage'),       'menu_url'=>'index.php?w=store_transport'),
                );
                if (checkPlatformStore()) {
                    $menu_array[] = array(
                        'menu_key' => 'offpay_area',
                        'menu_name' => '货到付款地区',
                        'menu_url' => 'index.php?w=store_transport&t=offpay_area'
                    );
                }
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
