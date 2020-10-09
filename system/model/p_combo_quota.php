<?php
/**
 * 推荐组合管理
 *
 *
 *
 *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');

class p_combo_quotaModel extends Model {
    public function __construct() {
        parent::__construct('p_combo_quota');
    }

    /**
     * 预售套餐列表
     *
     * @param array $condition
     * @param string $field
     * @param int $page
     * @param string $order
     * @return array
     */
    public function getComboQuotaList($condition, $field = '*', $page = null, $order = 'cq_id desc') {
        return $this->field($field)->where($condition)->order($order)->page($page)->select();
    }

    /**
     * 预售套餐详细信息
     *
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getComboQuotaInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 保存预售套餐
     *
     * @param array $insert
     * @param boolean $replace
     * @return boolean
     */
    public function addComboQuota($insert, $replace = false) {
        return $this->insert($insert, $replace);
    }

    /**
     * 编辑预售套餐
     * @param array $update
     * @param array $condition
     * @return array
     */
    public function editComboQuota($update, $condition) {
        return $this->where($condition)->update($update);
    }
}
