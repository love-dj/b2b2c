<?php
/**
 * 预定订单时段模板
 *
 *
 *
 *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');
class order_bookModel extends Model{

    public function __construct(){
        parent::__construct('order_book');
    }

    /**
     * 读取列表
     * @param array $condition
     *
     */
    public function getOrderBookList($condition = array(), $page = '', $order = 'book_id asc', $field = '*', $limit = '') {
        return $this->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }

    /**
     * 读取单条记录
     * @param array $condition
     *
     */
    public function getOrderBookInfo($condition,$fields = '*') {
        return $this->where($condition)->field($fields)->find();
    }

    /*
     * 增加
     * @param array $data
     * @return bool
     */
    public function addOrderBook($data){
        return $this->insert($data);
    }

    /**
     * 编辑
     * @param unknown $data
     * @param unknown $condition
     */
    public function editOrderBook($data,$condition) {
        return $this->where($condition)->update($data);
    }

    public function getOrderBookCount($condition) {
        return $this->where($condition)->count();
    }

}
