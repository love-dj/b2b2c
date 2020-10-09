<?php
/**
 * 店铺地址地图
 *
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class show_mapControl extends BaseHomeControl {
    public function __construct() {
        parent::__construct();
    }
    /**
     * 店铺地址地图
     *
     */
    public function indexWt() {
        if (empty($_GET['width'])) {
            $_GET['width'] =500;
        }
        if (empty($_GET['height'])) {
            $_GET['height'] =500;
        }

        $model_store_map = Model('store_map');
        $store_id = intval($_GET['store_id']);
        if ($store_id > 0) {
            $condition = array();
            $condition['store_id'] = $store_id;
            $map_list = $model_store_map->getStoreMapList($condition, '', '', 'map_id asc');
            Tpl::output('map_list',$map_list);
            Tpl::showpage('show_map','null_layout');
        }
    }
}
