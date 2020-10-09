<?php
/**
 * 地区
 *
 *


 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class areaControl extends mobileHomeControl{

    public function __construct() {
        parent::__construct();
    }

    public function indexWt() {
        $this->area_listWt();
    }

    /**
     * 地区列表
     */
    public function area_listWt() {
        $area_id = intval($_GET['area_id']);

        $model_area = Model('area');

        $condition = array();
        if($area_id > 0) {
            $condition['area_parent_id'] = $area_id;
        } else {
            $condition['area_deep'] = 1;
        }
        $area_list = $model_area->getAreaList($condition, 'area_id,area_name');
        output_data(array('area_list' => $area_list));
    }

}
