<?php
/**
 * 验证码
 *

 
 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');

class apivercodeModel extends Model {
    public function __construct() {
        parent::__construct('apivercode');
    }
    public function addApiVercode($key,$vercode) {
        /*if (C('cache_open')) {
            return $this->addApiVercodeToCache($key,$vercode);
        }else{
            return $this->addApiVercodeToData($key,$vercode);
        }*/
        return $this->addApiVercodeToData($key,$vercode);
    }
    /**
     * 添加验证码信息至数据库
     * @param $key 验证码标识
     * @param $vercode 验证码值
     * @return boolean
     */
    private function addApiVercodeToData($key,$vercode) {
        if(!($key && $vercode)){
            return false;
        }
        $insert_arr = array();
        $insert_arr['sec_key'] = $key;
        $insert_arr['sec_val'] = encrypt(strtoupper($vercode),MD5_KEY);
        $insert_arr['sec_addtime'] = time();
        return $this->table('apivercode')->insert($insert_arr);
    }
    /**
     * 添加验证码信息至缓存
     * @param $key 验证码标识
     * @param $vercode 验证码值
     * @return boolean
     */
    /*private function addApiVercodeToCache($key,$vercode) {
        if(!($key && $vercode)){
            return false;
        }
        wcache($key,array('sec_val'=>encrypt(strtoupper($vercode),MD5_KEY),'sec_addtime'=>time()),'apivercode');
        return true;
    }*/
    /**
     * 验证验证码
     *
     * @param string $key 验证码标识
     * @param string $value 待验证值
     * @param boolean $is_runout 是否无论成功与否都失效
     * @return boolean
     */
    public function checkApiVercode($key,$value,$is_runout=true){
        /*if (C('cache_open')) {
            return $this->checkApiVercodeByCache($key,$value,$is_runout);
        }else{
            return $this->checkApiVercodeByData($key,$value,$is_runout);
        }*/
        return $this->checkApiVercodeByData($key,$value,$is_runout);
    }
    /**
     * 验证验证码
     *
     * @param string $key 验证码标识
     * @param string $value 待验证值
     * @param boolean $is_runout 是否无论成功与否都失效
     * @return boolean
     */
    private function checkApiVercodeByData($key,$value,$is_runout=true){
        //删除过期验证码
        $this->dropByKey(array('sec_addtime'=>array('elt',time()-3600)));

        //查询验证码
        $info = $this->getInfoByKey($key);
        if (!$info) {
            return false;
        }
        //超时失效
        /*if (time() - $info['sec_addtime'] > 3600) {
            $this->dropByKey(array('sec_key'=>$key));
            return false;
        }*/
        //验证码是否正确
        $checkvalue = decrypt($info['sec_val'],MD5_KEY);
        $return = $checkvalue == strtoupper($value);
        if ($is_runout) {//无论成功与否都失效
            $this->dropByKey(array('sec_key'=>$key));
        }else{//当验证码验证失败失效
            if (!$return) $this->dropByKey(array('sec_key'=>$key));
        }
        return $return;
    }
    /**
     * 验证验证码
     *
     * @param string $key 验证码标识
     * @param string $value 待验证值
     * @param boolean $is_runout 是否无论成功与否都失效
     * @return boolean
     */
    /*private function checkApiVercodeByCache($key,$value,$is_runout=true){
        $info = rcache($key, 'apivercode');
        if (!$info) {
            return false;
        }
        //超时失效
        if (time() - $info['sec_addtime'] > 3600) {
            dcache($key, 'apivercode');
            return false;
        }
        //验证码是否正确
        $checkvalue = decrypt($info['sec_val'],MD5_KEY);
        $return = $checkvalue == strtoupper($value);
        if ($is_runout) {//无论成功与否都失效
            dcache($key, 'apivercode');
        }else{//当验证码验证失败失效
            if (!$return) dcache($key, 'apivercode');
        }
        return $return;
    }*/
    /**
     * 获得验证码详情
     * @param string $key 验证码标识
     * @return boolean
     */
    public function getInfoByKey($key){
        if (!$key) {
            return false;
        }
        return $this->table('apivercode')->where(array('sec_key'=>$key))->field('*')->find();
    }
    /**
     * 删除验证码
     * @param string $key 验证码标识
     * @return boolean
     */
    public function dropByKey($where){
        if (!$where) {
            return false;
        }
        return $this->table('apivercode')->where($where)->delete();
    }
}
