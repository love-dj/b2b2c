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
class region_configControl extends SystemControl{
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
		$this->region_fenhong_configWt();

	}

    /**
     * 区域代理分红设置
     */
    public function region_fenhong_configWt() {


        $schema_config = Model('setting');
        $setting_list = $schema_config->getListSetting();
        Tpl::output('setting',$setting_list);
        Tpl::setDirquna('schema');
        Tpl::showpage('region_fenhong_config');
    }


    /*
        * 区域分红设置
        * */
    public function region_dividendWt(){

        if($_POST){
            $arr = array(

                array('name'=>'is_area_dividend','value'=>$_POST['is_area_dividend']), //是否开启区域分红 0否  1是
                array('name'=>'is_only_agent','value'=>$_POST['is_only_agent']),       //是否是否独家代理 0否  1是
                array('name'=>'become_agent','value'=>$_POST['become_agent']),         //成为区域代理 0无条件  1需要申请
                array('name'=>'become_check','value'=>$_POST['become_check']),         //是否需要审核 0否  1需要
                array('name'=>'apply_again','value'=>$_POST['apply_again']),           //申请驳回后可再次申请 0否  1是

            );
//            var_dump($arr);die;
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

    /*
     * 结算设置
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
