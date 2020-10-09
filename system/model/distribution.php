<?php
/**
 * 微信管理
 *
 *
 *
 *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');
class DistributionModel extends Model
{

    public function __construct() {
        parent::__construct('distribution');
    }
	
    /**
     * 获取某条信息
	 * @param string $table 表名
     * @param array $condition
     * @return array
     */
    public function getInfoOne($table,$condition, $field = '*') {
        return $this->table($table)->field($field)->where($condition)->find();
    }
	
	/**
	 * 查询信息列表
     *
	 * @param string $table 表名
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $order 排序
	 * @param string $field 字段
	 * @param string $limit 取多少条
     * @return array
	 */
	
	public function getInfoList($table,$condition, $page = null, $order = '', $field = '*', $limit = '') {
        $result = $this->table($table)->field($field)->where($condition)->order($order)->limit($limit)->page($page)->select();
        return $result;
    }

    /**
     * 更新某条信息
	 * @param string $table 表名
     * @param array $condition
     * @return int
     */
    public function editInfo($table,$update, $condition=array()){
        return $this->table($table)->where($condition)->update($update);
    }
	
	/*
	 * 添加某条信息
     * @param string $table 表名
	 * @param array $param 数据信息
	 * @return bool
	 */	
	public function addInfo($table,$param){
        return $this->table($table)->insert($param);
    }
	
	/*
	 * 删除某条信息
     * @param string $table 表名
	 * @param array $param 数据信息
	 * @return bool
	 */	
	public function delInfo($table,$condition) {
        return $this->table($table)->where($condition)->delete();
    }
	
	/*
	 * 批量添加新数据
     * @param string $table 表名
	 * @param array $param 数据信息
	 * @return bool
	 */	
	public function addAll($table,$param){
        return $this->table($table)->insertAll($param);
    }

}
