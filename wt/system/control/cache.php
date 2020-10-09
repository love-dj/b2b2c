<?php
/**
 * 更新缓存
 *

 
 
 */


defined('ShopWT') or exit('Access Denied By ShopWT');

class cacheControl extends SystemControl
{
    protected $cacheItems = array(
        'setting',          // 基本缓存
        'seo',              // SEO缓存
        'robbuy_price',   // 抢购价格区间
        'nav',              // 底部导航缓存
        'express',          // 快递公司
        'store_class',      // 店铺分类
        'store_grade',      // 店铺等级
        'store_msg_tpl',    // 店铺消息
        'member_msg_tpl',   // 用户消息
        'consult_type',     // 咨询类型
        'bbs_level',     // 社区成员等级
        'admin_menu',       // 后台菜单
        'area',              // 地区
        'contractitem'      //消费者保障服务
    );

    public function __construct() {
        parent::__construct();
        Language::read('cache');
    }

    public function indexWt() {
        $this->clearWt();
    }

    /**
     * 更新缓存
     */
    public function clearWt() {
        if (!chksubmit()) {
			Tpl::setDirquna('system');
            Tpl::showpage('cache.clear');
            return;
        }

        $lang = Language::getLangContent();

        // 清理所有缓存
        if ($_POST['cls_full'] == 1) {
            foreach ($this->cacheItems as $i) {
                dkcache($i);
            }

            // 商品分类
            dkcache('gc_class');
            dkcache('all_categories');
            dkcache('goods_class_seo');
            dkcache('class_tag');
            dkcache('get_child_all_list');
            // 广告
            Model('show')->makeApAllCache();

            // 首页及频道
            Model('page_config')->updateWeb(array('web_show'=>1),array('web_html'=>''));
            delCacheFile('index');
            dkcache('channel');

            if (C('cache_open')) {
                dkcache('index/article');
            }
            // 分销首页管理
            Model('fx_page_config')->updateWeb(array('web_show'=>1),array('web_html'=>''));
            delCacheFile('index');

        } else {
            $todo = (array) $_POST['cache'];

            foreach ($this->cacheItems as $i) {
                if (in_array($i, $todo)) {
                    dkcache($i);
                }
            }

            // 商品分类
            if (in_array('goodsclass', $todo)) {
                dkcache('gc_class');
                dkcache('all_categories');
                dkcache('goods_class_seo');
                dkcache('class_tag');
				dkcache('get_child_all_list');
            }

            // 广告
            if (in_array('show', $todo)) {
                Model('show')->makeApAllCache();
            }

            // 首页及频道
            if (in_array('index', $todo)) {
                Model('page_config')->updateWeb(array('web_show'=>1),array('web_html'=>''));
                delCacheFile('index');
                dkcache('channel');

                if (C('cache_open')) {
                    dkcache('index/article');
                }
            }
            // 分销首页管理
            if(in_array('fx_index' , $todo)){
                Model('fx_page_config')->updateWeb(array('web_show'=>1),array('web_html'=>''));
                delCacheFile('index');
            }
        }

        $this->log(L('cache_cls_operate'));
        showMessage($lang['cache_cls_ok']);
    }
}
