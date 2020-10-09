<?php
/**
 * 网站设置
 *
 *
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class seoControl extends SystemControl{

    public function __construct(){
        parent::__construct();
        Language::read('setting');
    }

    public function indexWt() {
        $this->seoWt();
    }

    /**
     * SEO与rewrite设置
     */
    public function seoWt(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            $update_array['rewrite_enabled'] = $_POST['rewrite_enabled'];
            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                $this->log(L('wt_edit,wt_seo_set'),1);
                showMessage(L('wt_common_save_succ'));
            }else {
                $this->log(L('wt_edit,wt_seo_set'),0);
                showMessage(L('wt_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();

        //读取SEO信息
        $list = Model('seo')->select();
        $seo = array();
        foreach ((array)$list as $value) {
            $seo[$value['type']] = $value;
        }

        Tpl::output('list_setting',$list_setting);
        Tpl::output('seo',$seo);

        $category = Model('goods_class')->getGoodsClassForCacheModel();
        Tpl::output('category',$category);
		Tpl::setDirquna('shop');

        Tpl::showpage('seo.setting');
    }

    public function ajax_categoryWt(){
        $model = Model('goods_class');
        $list = $model->field('gc_title,gc_keywords,gc_description')->where(array('gc_id' => intval($_GET['id'])))->find();
        //转码
        if (strtoupper(CHARSET) == 'GBK'){
            $list = Language::getUTF8($list);//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
        }
        echo json_encode($list);exit();
    }

    /**
     * SEO设置保存
     */
    public function seo_updateWt(){
        $model_seo = Model('seo');
        if (chksubmit()){
            $update = array();
            if (is_array($_POST['SEO'][0])){
                $seo = $_POST['SEO'][0];
            }else{
                $seo = $_POST['SEO'];
            }
            foreach ((array)$seo as $key=>$value) {
                $model_seo->where(array('type'=>$key))->update($value);
            }
            dkcache('seo');
            showMessage(L('wt_common_save_succ'));
        }else{
            showMessage(L('wt_common_save_fail'));
        }
    }

    /**
     * 分类SEO保存
     *
     */
    public function seo_categoryWt(){
        if (chksubmit()){
            $where = array('gc_id' => intval($_POST['category']));
            $input = array();
            $input['gc_title'] = $_POST['cate_title'];
            $input['gc_keywords'] = $_POST['cate_keywords'];
            $input['gc_description'] = $_POST['cate_description'];
            if (Model('goods_class')->editGoodsClass($input, $where)){
                dkcache('goods_class_seo');
                showMessage(L('wt_common_save_succ'));
            }
        }
        showMessage(L('wt_common_save_fail'));
    }
}
