<?php
/**
 * 微信管理
 *
 *
 *
 *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');
class keywordModel extends Model{
    public function __construct() {
        parent::__construct('keyword');
    }

    /**
     * 取得列表
     * @param unknown $condition
     * @param string $pagesize
     * @param string $order
     */
    public function getStoreFenxiaobutionList($condition = array(), $pagesize = '', $order = 'fx_id desc') {
        return $this->where($condition)->order($order)->page($pagesize)->select();
    }

    /**
     * 增加新记录
     * @param unknown $data
     * @return
     */
    public function addStoreFenxiaobution($data) {
        return $this->insert($data);
    }

    /**
     * 取单条信息
     * @param unknown $condition
     */
    public function getStoreFenxiaobutionInfo($condition) {
        return $this->where($condition)->find();
    }

    /**
     * 更新记录
     * @param unknown $condition
     * @param unknown $data
     */
    public function editStoreFenxiaobution($data,$condition) {
        return $this->where($condition)->update($data);
    }

    /**
     * 取得数量
     * @param unknown $condition
     */
    public function getStoreFenxiaobutionCount($condition) {
        return $this->where($condition)->count();
    }

    /**
     * 删除记录
     * @param unknown $condition
     */
    public function delStoreFenxiaobution($condition) {
        return $this->where($condition)->delete();
    }
}