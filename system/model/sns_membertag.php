<?php
/**
 * 会员标签
 *

 
 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');
class sns_membertagModel extends Model {

    public function __construct(){
        parent::__construct('sns_membertag');
    }
    
    /**
     * 标签详细信息
     * @param unknown $condition
     */
    public function getSnsMemberTagInfo($condition) {
        return $this->where($condition)->find();
    }
    
    /**
     * 根据id查询标签详细信息
     * @param unknown $id
     */
    public function getSnsMemberTagInfoById($id) {
        $condition = array('mtag_id' => $id);
        return $this->getSnsMemberTagInfo($condition);
    }

    /**
     * 标签列表
     * @param array $condition
     * @param int $page
     * @param string $order
     */
    public function getSnsMemberTagList($condition, $page, $order) {
        return $this->where($condition)->order($order)->page($page)->select();
    }
    
    /**
     * 编辑标签
     * @param unknown $where
     * @param unknown $update
     */
    public function editSnsMemberTag($where, $update) {
        return $this->where($where)->update($update);
    }
}
