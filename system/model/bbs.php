<?php
/**
 * 社区模型
 *
 *
 *
 *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');

class bbsModel extends Model {
    public function __construct(){
        parent::__construct('bbs');
    }

    /**
     * 获取社区数量
     * @param array $condition
     * @return int
     */
    public function getbbsCount($condition) {
        return $this->where($condition)->count();
    }

    /**
     * 未审核的社区数量
     * @param array $condition
     * @return int
     */
    public function getbbsUnverifiedCount($condition = array()) {
        $condition['bbs_status'] = 2;
        return $this->getbbsCount($condition);
    }
}
