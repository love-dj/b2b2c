<?php
/**
 * schema管理
 * 2018/10/22
 * auth feng
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @license    http://www.weisbao.com
 * @link       联系方式：13632978801
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class shareholders_base_configControl extends SystemControl{
    /*private $links = array(
        array('url'=>'act=schema_config&op=settlement', 'lang'=>'settlement'),
        array('url'=>'act=schema_config&op=active', 'lang'=>'active'),
        array('url'=>'act=schema_config&op=center', 'lang'=>'center'),
    );*/

	public function __construct(){
		parent::__construct();
		Language::read('schema');
	}

	public function indexWt() {
		$this->shareholders_base_configWt();

	}

    /**
     * 股东分红设置
     */
    public function shareholders_base_configWt() {


        $schema_config = Model('setting');

        $setting_list = $schema_config->getListSetting();
        Tpl::output('setting',$setting_list);
        Tpl::setDirquna('schema');
        Tpl::showpage('shareholders_base_config');
    }


    /*
        * 股东分红--------设置
        * */
    public function base_configWt(){

        if($_POST){
            $arr = array(

                array('name'=>'shareholder_is_dividend','value'=>$_POST['shareholder_is_dividend']), //是否开启股东分红 0否  1是
                array('name'=>'shareholder_lever_0','value'=>intval($_POST['shareholder_lever_0'])),         //分红比例  普通股东 比例
                array('name'=>'shareholder_lever_1','value'=>intval($_POST['shareholder_lever_1'])),         //分红比例  银牌股东 比例
                array('name'=>'shareholder_lever_2','value'=>intval($_POST['shareholder_lever_2'])),         //分红比例  金牌股东 比例
                array('name'=>'shareholder_lever_3','value'=>intval($_POST['shareholder_lever_3'])),         //分红比例  砖石股东 比例
                array('name'=>'shareholder_sett_method','value'=>intval($_POST['shareholder_sett_method'])),  //股东分红结算方式 0营业额 1利润
                array('name'=>'shareholder_sett_cycle','value'=>$_POST['shareholder_sett_cycle'])             //股东分红结算周期  1天 2周 3月
            );

            foreach($arr as $k=>$v){
                $res = Model('setting')->update($v);
                if(!$res){
                    showMessage(Language::get('nc_common_save_fail'));
                }
            }
            showMessage(Language::get('nc_common_save_succ'));
        }
    }

    /*
        * 股东分红--------消息通知
        * */
    public function settlementWt(){
        if($_POST){
            $arr = array(
                array('name'=>'culate_method','value'=>$_POST['culate_method']),   //结算方式 0： 订单价格:(不包括运费及抵扣金额)  1： 利润:(订单最终价格-成本，负数取0)
                array('name'=>'is_average','value'=>$_POST['is_average']),         //是否开启平均分红  0否 1是
                array('name'=>'is_distinction','value'=>$_POST['is_distinction']), //是否开启极差分红  0否 1是
                array('name'=>'province_rate','value'=>intval($_POST['province_rate'])),              //默认分红比例 省
                array('name'=>'city_rate','value'=>intval($_POST['city_rate'])),                     //默认分红比例 市
                array('name'=>'area_rate','value'=>intval($_POST['area_rate'])),                     //默认分红比例 区
//                array('name'=>'settlement_model','value'=>$_POST['settlement_model']),    //结算类型 0自动结算  1手动结算
                array('name'=>'region_settle_days','value'=>intval($_POST['region_settle_days'])) //结算期

            );
            foreach($arr as $k=>$v){
                $res = Model('setting')->update($v);
                if(!$res){
                    $this->log('区域分红设置保存', 0);
                    showMessage(Language::get('nc_common_save_fail'));
                }
            }
            $this->log('区域分红设置保存', 0);
            showMessage(Language::get('nc_common_save_succ'));
        }
    }










    
}
