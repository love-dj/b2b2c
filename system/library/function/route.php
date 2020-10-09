<?php
/**
 * 路由
 *
 */
defined('ShopWT') or exit('Access Denied By ShopWT');

class Route {

    /**
     * PATH_INFO 分隔符
     *
     * @var string
     */
    private $_pathinfo_split = '-';

    /**
     * 系统配置信息
     *
     * @var array
     */

    private $_config = array();

    /**
     * PATH_INFO内容分隔正则
     *
     * @var string
     */
    private $_pathinfo_pattern = '';

    /**
     * 伪静态文件扩展名
     *
     * @var string
     */
    private $_rewrite_extname = '.html';

    /**
     * 构造方法
     *
     */
    public function __construct($config = array()) {
        $this->_config = $config;
        $this->_pathinfo_pattern = "/{$this->_pathinfo_split}/";
        $this->parseRule();
    }

    /**
     * 路由解析
     *
     */
    public function parseRule() {
        if ($this->_config['url_model']) {
            $this->_parseRuleRewrite();
        } else {
            $this->_parseRuleNormal();
        }
    }

    /**
     * 默认URL模式
     *
     */
    private function _parseRuleNormal() {
        //不进行任何处理
    }

    /**
     * 伪静态模式
     *
     */
    private function _parseRuleRewrite() {
        $path_info = $_SERVER['REQUEST_URI'];
        $path_info = substr($path_info,strrpos($path_info,'/')+1);
        if(strpos($path_info, '?')) {
            $path_info = substr($path_info, 0, (int) strpos($path_info, '?'));
        }
        if (!empty($path_info) && $path_info != 'index.php' && strpos($path_info, $this->_rewrite_extname)){
            //去掉伪静态扩展名
            $path_info = substr($path_info,0,-strlen($this->_rewrite_extname));

            //根据不同APP匹配url规则
            $path_info_function = '_' . APP_ID . 'PathInfo';
            if (!method_exists($this,$path_info_function)) {
                return ;
            }
            $path_info = $this->$path_info_function($path_info);

            $split_array = preg_split($this->_pathinfo_pattern,$path_info);
            //w,op强制赋值
            $_GET['w'] = isset($split_array[0]) ? $split_array[0] : 'index';
            $_GET['t'] = isset($split_array[1]) ? $split_array[1] : 'index';
            unset($split_array[0]);
            unset($split_array[1]);

            //其它参数也放入$_GET
            while (current($split_array) !== false) {
                $name = current($split_array);
                $value = next($split_array);
                $_GET[$name] = $value;
                if (next($split_array) === false){
                    break;
                }
            }
        } else {
            $_GET['w'] = $_GET['t'] = 'index';
        }
    }

    /**
     * 商城短网址还原成长网址
     * @param unknown $path_info
     * @return mixed
     */
    private function _shopPathInfo($path_info) {
        $reg_match_from = array(

		    '/^special$/',
			'/^special-(\d+)$/',
            '/^category$/',
            '/^channel-(\d+)$/',
            '/^goods-(\d+)$/',
            '/^shop-(\d+)$/',
            '/^shop-view-(\d+)-(\d+)-([0-5])-([0-2])-(\d+)$/',
            '/^help-(\d+)$/',
            '/^help-cate-(\d+)$/',
            '/^document-([a-z_]+)$/',
            '/^list-(\d+)-([0-9_]+)-([0-9_]+)-([0-9_]+)-([0-3])-([0-2])-([0-1])-([0-1])-(\d+)-(\d+)$/',
            '/^brand-(\d+)-([0-9_]+)-([0-3])-([0-2])-([0-1])-([0-1])-(\d+)-(\d+)$/',
            '/^brand$/',
            '/^sale$/',
            '/^sale-(\d+)$/',
            '/^robbuy$/',
            '/^robbuy-view-(\d+)$/',

            '/^robbuy-list-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)$/',
            '/^robbuy-ready-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)$/',
            '/^robbuy-past-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)$/',

            '/^robbuy-vr-list-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)$/',
            '/^robbuy-vr-ready-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)$/',
            '/^robbuy-vr-past-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)$/',

            '/^points/',
            '/^integral_item-(\d+)$/',
            '/^voucher$/',
            '/^grade$/',
            '/^explog-(\d+)$/',
            '/^comments-(\d+)-([0-3])-(\d+)$/'
        );
        $reg_match_to = array(

		    'special-special_list',
			'special-special_detail-special_id-\\1',
            'category-index',
            'channel-index-id-\\1',
            'goods-index-goods_id-\\1',
            'show_store-index-store_id-\\1',
            'show_store-goods_all-store_id-\\1-stc_id-\\2-key-\\3-order-\\4-curpage-\\5',
            'article-show-article_id-\\1',
            'article-article-ac_id-\\1',
            'document-index-code-\\1',
            'search-index-cate_id-\\1-b_id-\\2-a_id-\\3-ci-\\4-key-\\5-order-\\6-type-\\7-gift-\\8-area_id-\\9-curpage-\\10',
            'brand-list-brand-\\1-ci-\\2-key-\\3-order-\\4-type-\\5-gift-\\6-area_id-\\7-curpage-\\8',
            'brand-index',
            'sale-index',
            'sale-index-gc_id-\\1',
            'robbuy-index',
            'robbuy-robbuy_detail-group_id-\\1',

            'robbuy-robbuy_list-class-\\1-s_class-\\2-robbuy_price-\\3-robbuy_order_key-\\4-robbuy_order-\\5-curpage-\\6',
            'robbuy-robbuy_soon-class-\\1-s_class-\\2-robbuy_price-\\3-robbuy_order_key-\\4-robbuy_order-\\5-curpage-\\6',
            'robbuy-robbuy_history-class-\\1-s_class-\\2-robbuy_price-\\3-robbuy_order_key-\\4-robbuy_order-\\5-curpage-\\6',

            'robbuy-vr_robbuy_list-vr_class-\\1-vr_s_class-\\2-vr_area-\\3-vr_mall-\\4-robbuy_price-\\5-robbuy_order_key-\\6-robbuy_order-\\7-curpage-\\8',
            'robbuy-vr_robbuy_soon-vr_class-\\1-vr_s_class-\\2-vr_area-\\3-vr_mall-\\4-robbuy_price-\\5-robbuy_order_key-\\6-robbuy_order-\\7-curpage-\\8',
            'robbuy-vr_robbuy_history-vr_class-\\1-vr_s_class-\\2-vr_area-\\3-vr_mall-\\4-robbuy_price-\\5-robbuy_order_key-\\6-robbuy_order-\\7-curpage-\\8',

            'points-index',
            'pointprod-pinfo-id-\\1',
            'pointvoucher-index',
            'pointgrade-index',
            'pointgrade-exppointlog-curpage-\\1',
            'goods-comments_list-goods_id-\\1-type-\\2-curpage-\\3'
        );
        return preg_replace($reg_match_from,$reg_match_to,$path_info);
    }

    /**
     * 商城短网址还原成长网址
     * @param unknown $path_info
     * @return mixed
     */
    private function _memberPathInfo($path_info) {
        $reg_match_from = array(
            '/^help-(\d+)$/',
            '/^help-cate-(\d+)$/'
        );
        $reg_match_to = array(
            'article-show-article_id-\\1',
            'article-article-ac_id-\\1'
        );
        return preg_replace($reg_match_from,$reg_match_to,$path_info);
    }

    /**
     * NEWS短网址还原成长网址
     * @param unknown $path_info
     * @return mixed
     */
    private function _newsPathInfo($path_info) {
        $reg_match_from = array(
            '/^article-(\d+)$/',
            '/^picture-(\d+)$/'
        );
        $reg_match_to = array(
            'article-article_detail-article_id-\\1',
            'picture-picture_detail-picture_id-\\1'
        );
        return preg_replace($reg_match_from,$reg_match_to,$path_info);
    }

}
