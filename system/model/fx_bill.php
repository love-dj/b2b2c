<?php
/**
 * 分销结算
 *
 *
 *
 * *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');

class fx_billModel extends Model{

    public function __construct() {
        parent::__construct();
    }

    /**
     * 获取结算列表
     */
    public function getFenxiaoBillList($condition = array(), $field = '*', $page = 0,$order = 'log_id desc', $limit = 0, $group = ''){
        return $this->table('fx_pay')->field($field)->where($condition)->group($group)->order($order)->limit($limit)->page($page)->select();
    }



    /**
     * 获取结算单数量
     */
    public function getFenxiaoBillCount($condition){
        return $this->table('fx_pay')->where($condition)->count();
    }

    /**
     * 获取结算单详情
     */
    public function getFenxiaoBillInfo($condition, $field = '*', $order = 'log_id desc'){
        return $this->table('fx_pay')->field($field)->where($condition)->find();
    }
}