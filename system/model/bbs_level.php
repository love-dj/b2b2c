<?php
/**
 * bbs Level
 *
 *
 *
 *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');

class bbs_levelModel extends Model {
    public function __construct(){
        parent::__construct();
    }
    /**
     * insert
     * @param array $insert
     * @param bool $replace
     */
    public function levelInsert($insert, $replace){
        $this->table('bbs_ml')->insert($insert, $replace);
        return $this->updateLevelName($insert);
    }

    /**
     * update level name
     * @param array $insert
     */
    private function updateLevelName($insert){
        $str = '( case cm_level ';
        for ($i=1; $i<=16; $i++){
            $str .= ' when '.$i.' then \''.$insert['ml_'.$i].'\'';
        }
        $str .= ' else cm_levelname end)';

        $update = array();
        $update['cm_levelname'] = array('exp',$str);

        $where = array();
        $where['bbs_id'] = $insert['bbs_id'];
        return $this->table('bbs_member')->where($where)->update($update);
    }
}
