<?php
/**
 * 分享积分
 *
 *
 *
 *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');
class share_pointslogModel extends Model {

    public function __construct() {
        parent::__construct('share_pointslog');
    }

    /**
     * 获取列表
     *
     * @return mixed
     */
    public function getList($condition = array(), $fields = '*', $group = '', $page = null) {
        return $this->where($condition)->field($fields)->page($page)->limit(false)->group($group)->select();
    }

	/**
	 * 通过id
	 *
	 * @param int $id 
	 */
	public function geInfoById($id) {
		 return $this->where(array('id'=>$id))->find();
	}
	
	/**
     * 获取详情
     *
     * @return mixed
     */
    public function getInfo($condition = array(), $fileds = '*') {
        return $this->where($condition)->field($fileds)->find();
    }
	/**
     * 获取积分总数
     *
     * @return mixed
     */
    public function getPoints($condition = array()) {
        return $this->where($condition)->sum('points');
    }

    public function add($data = array()) {
        return $this->insert($data);
    }

    public function edit($data = array(), $condition = array()) {
        return $this->where($condition)->update($data);
    }

    public function del($condition = array()) {
        return $this->where($condition)->delete();
    }
	
}
