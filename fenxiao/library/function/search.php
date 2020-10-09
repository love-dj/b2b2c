<?php
defined('ShopWT') or exit('Access Denied By ShopWT');

/**
 * 删除地址参数(把参数的值赋值为0)
 *
 * @param array $param
 */
function dropParam($param) {
    $purl = getParam();
    if (!empty($param)) {
        foreach ($param as $val) {
            $purl['param'][$val]= 0;
        }
    }
    return urlFenxiao($purl['w'], $purl['t'], $purl['param']);
}

/**
 * 替换地址参数(替换参数值)
 *
 * @param array $param
 */
function replaceParam($param) {
    $purl = getParam();
    if (!empty($param)) {
        foreach ($param as $key => $val) {
            $purl['param'][$key] = $val;
        }
    }

    return urlFenxiao($purl['w'], $purl['t'], $purl['param']);
}

/**
 * 替换并删除地址参数
 *
 * @param array $param
 */
function replaceAndDropParam($paramToReplace, $paramToDrop) {
    $purl = getParam();
    if (!empty($paramToReplace)) {
        foreach ($paramToReplace as $key => $val) {
            $purl['param'][$key] = $val;
        }
    }
    if (!empty($paramToDrop)) {
        foreach ($paramToDrop as $val) {
            $purl['param'][$val]= 0;
        }
    }

    return urlFenxiao($purl['w'], $purl['t'], $purl['param']);
}

/**
 * 删除部分地址参数（适用于商品搜索品牌、属性部分）
 * 例：参数w=1_2_3_4
 *    如需要删除w中的3可使用该函数 removeRaram(array('w' => 3))
 *    该函数只能删除一个参数
 *
 * @param array $param
 */
function removeParam($param) {
    $purl = getParam();
    if (!empty($param)) {
        foreach ($param as $key => $val) {
            if (!isset($purl['param'][$key])) {
                continue;
            }
            $tpl_params = explode('_', $purl['param'][$key]);
            foreach ($tpl_params as $k=>$v) {
                if ($val == $v) {
                    unset($tpl_params[$k]);
                }
            }
            if (empty($tpl_params)) {
                $purl['param'][$key] = 0;
            } else {
                $purl['param'][$key] = implode('_', $tpl_params);
            }
        }
    }
    return urlFenxiao($purl['w'], $purl['t'], $purl['param']);
}

function getParam() {
    $param = $_GET;
    $purl = array();
    $purl['w'] = $param['w'];
    unset($param['w']);
    $purl['t'] = $param['t'];
    unset($param['t']); unset($param['curpage']);
    $purl['param'] = $param;
    return $purl;
}
