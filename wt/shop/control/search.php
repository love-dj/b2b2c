<?php
/**
 * 搜索设置
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class searchControl extends SystemControl{

    private $_links = array(
        array('url'=>'w=search&t=index','text'=>'默认搜索'),
        array('url'=>'w=search&t=hot','text'=>'热门搜索')
    );

    public function __construct(){
        parent::__construct();
    }

    /**
     * 默认搜索
     */
    public function indexWt() {
        if (chksubmit()){
            $model_setting = Model('setting');
            $comma = '，';
            if (strtoupper(CHARSET) == 'GBK'){
                $comma = Language::getGBK($comma);
            }
            $result = $model_setting->updateSetting(array(
                    'hot_search'=>str_replace($comma,',',$_POST['hot_search'])));
            if ($result){
                showMessage('保存成功');
            }else {
                showMessage('保存失败');
            }
        }
        $model_setting = Model('setting');
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);

        Tpl::output('top_link',$this->sublink($this->_links,'index'));
		Tpl::setDirquna('shop');

        Tpl::showpage('search.index');
    }

    /**
     * 热门搜索词列表
     */
    public function hotWt() {
        $model_setting = Model('setting');
        $search_info = $model_setting->getRowSetting('rec_search');
        if ($search_info !== false) {
            $search_list = @unserialize($search_info['value']);
        }
        if (!$search_list && !is_array($search_list)) {
            $search_list = array();
        }
        Tpl::output('search_list',$search_list);
        Tpl::output('top_link',$this->sublink($this->_links,'hot'));
		Tpl::setDirquna('shop');
        Tpl::showpage('search.hot');
    }

    /**
     * 热搜词添加
     */
    public function hot_addWt() {
        $model_setting = Model('setting');
        $search_info = $model_setting->getRowSetting('rec_search');
        if ($search_info !== false) {
            $search_list = @unserialize($search_info['value']);
        }
        if (!$search_list && !is_array($search_list)) {
            $search_list = array();
        }
        if (chksubmit()) {
            if (count($search_list) >= 10) {
                showMessage('最多可设置10个热搜词','index.php?w=search&t=hot');
            }
            if ($_POST['s_name'] != '' && $_POST['s_value'] != '') {
                $data = array('name'=>stripslashes($_POST['s_name']),'value'=>stripslashes($_POST['s_value']));
                array_unshift($search_list, $data);
            }
            $result = $model_setting->updateSetting(array('rec_search'=>serialize($search_list)));
            if ($result){
                showMessage('保存成功','index.php?w=search&t=hot');
            }else {
                showMessage('保存失败');
            }
        }
		Tpl::setDirquna('shop');

        Tpl::showpage('search.hot_add');
    }

    /**
     * 删除
     */
    public function hot_delWt() {
        $model_setting = Model('setting');
        $search_info = $model_setting->getRowSetting('rec_search');
        if ($search_info !== false) {
            $search_list = @unserialize($search_info['value']);
        }
        if (!empty($search_list) && is_array($search_list) && intval($_GET['id']) >= 0) {
            unset($search_list[intval($_GET['id'])]);
        }
        if (!is_array($search_list)) {
            $search_list = array();
        }
        $result = $model_setting->updateSetting(array('rec_search'=>serialize(array_values($search_list))));
        if ($result){
            showMessage('删除成功');
        }
        showMessage('删除失败');
    }

    /**
     * 编辑
     */
    public function hot_editWt() {
        $model_setting = Model('setting');
        $search_info = $model_setting->getRowSetting('rec_search');
        if ($search_info !== false) {
            $search_list = @unserialize($search_info['value']);
        }
        if (!is_array($search_list)) {
            $search_list = array();
        }
        if (!chksubmit()) {
            if (!empty($search_list) && is_array($search_list) && intval($_GET['id']) >= 0) {
                $current_info = $search_list[intval($_GET['id'])];
            }
            Tpl::output('current_info',is_array($current_info) ? $current_info : array());
			Tpl::setDirquna('shop');
            Tpl::showpage('search.hot_add');
        } else {
            if ($_POST['s_name'] != '' && $_POST['s_value'] != '' && $_POST['id'] != '' && intval($_POST['id']) >= 0) {
                $search_list[intval($_POST['id'])] = array('name'=>stripslashes($_POST['s_name']),'value'=>stripslashes($_POST['s_value']));
            }
            $result = $model_setting->updateSetting(array('rec_search'=>serialize($search_list)));
            if ($result){
                showMessage('编辑成功','index.php?w=search&t=hot');
            }
            showMessage('编辑失败');
        }


    }
}