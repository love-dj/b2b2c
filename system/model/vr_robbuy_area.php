<?php
/**
 * 虚拟抢购区域管理
 *
 *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');

class vr_robbuy_areaModel extends Model
{
    public function __construct()
    {
        parent::__construct('vr_robbuy_area');
    }

    /**
     * 线下抢购信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getVrRobbuyAreaInfo($condition, $field = '*')
    {
        return $this->table('vr_robbuy_area')->field($field)->where($condition)->find();
    }

    /**
     * 线下抢购列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     * @param string $limit
     */
    public function getVrRobbuyAreaList($condition = array(), $field = '*', $page='15', $order = 'hot_city desc, area_id')
    {
       return $this->table('vr_robbuy_area')->where($condition)->page($page)->order($order)->select();
    }

    /**
     * 添加线下抢购
     * @param array $data
     */
    public function addVrRobbuyArea($data)
    {
        return $this->table('vr_robbuy_area')->insert($data);
    }

    /**
     * 编辑线下抢购
     * @param array $condition
     * @param array $data
     */
    public function editVrRobbuyArea($condition, $data)
    {
        return $this->table('vr_robbuy_area')->where($condition)->update($data);
    }

    /**
     * 删除线下分类
     * @param array $condition
     */
    public function delVrRobbuyArea($condition)
    {
        return $this->table('vr_robbuy_area')->where($condition)->delete();
    }
}
