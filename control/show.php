<?php
/**
 * 广告展示
 *
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class showControl {
    /**
     *
     * 广告展示
     */
    public function showWt(){
        import('function.show');
        $ap_id = intval($_GET['ap_id']);
        echo show($ap_id,'js');
    }
    /**
     * 异步调用广告
     *
     */
    public function get_show_listWt(){
        $ap_ids = $_GET['ap_ids'];
        $list = array();
        if (!empty($ap_ids) && is_array($ap_ids)) {
            import('function.show');
            foreach ($ap_ids as $key => $value) {
                $ap_id = intval($value);//广告位编号
                $show_info = show($ap_id,'array');
                if (!empty($show_info) && is_array($show_info)) {
                    $show_info['show_url'] = htmlspecialchars_decode($show_info['show_url']);
                    $list[$ap_id] = $show_info;
                }
            }
        }
        echo $_GET['callback'].'('.json_encode($list).')';
        exit;
    }
}
