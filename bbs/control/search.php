<?php
/**
 * 社区首页
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class searchControl extends BasebbsControl{
    public function __construct(){
        parent::__construct();
        Language::read('bbs');
        $this->themeTop();
    }
    /**
     * 话题搜索
     */
    public function themeWt(){
        $model = Model();
        $where = array();
        if($_GET['keyword'] != ''){
            $where['theme_name'] = array('like', '%'.$_GET['keyword'].'%');
        }
        $count = $model->table('bbs_theme')->where($where)->count();
        $theme_list = $model->table('bbs_theme')->where($where)->page(10,$count)->order('theme_addtime desc')->select();
        Tpl::output('count', $count);
        Tpl::output('show_page', $model->showpage('2'));
        Tpl::output('theme_list', $theme_list);
        Tpl::output('search_sign', 'theme');

        $this->bbsSEO(L('search_theme'));
        Tpl::showpage('search.theme');
    }
    /**
     * 社区搜索
     */
    public function groupWt(){
        $model = Model();
        $where = array();
        $where['bbs_status'] = 1;
        if($_GET['keyword'] != ''){
            $where['bbs_name|bbs_tag'] = array('like', '%'.$_GET['keyword'].'%');
        }
        if(intval($_GET['class_id']) > 0){
            $where['class_id'] = intval($_GET['class_id']);
        }
        $count = $model->table('bbs')->where($where)->count();
        $bbs_list = $model->table('bbs')->where($where)->page(10,$count)->select();
        Tpl::output('count', $count);
        Tpl::output('bbs_list', $bbs_list);
        Tpl::output('show_page', $model->showpage('2'));
        Tpl::output('search_sign', 'group');

        $this->bbsSEO(L('search_bbs'));
        Tpl::showpage('search.group');
    }
}
