<?php
/**
 * 社区公共方法
 *
 * 公共方法
 * *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');

/**
 * 返回模板文件所在完整目录
 *
 * @param string $tplpath 模板文件名（不含扩展名）
 * @return string
 */
function bbs_template($tplpath){
    return BASE_PATH.'/templets/'.TPL_NAME.'/'.$tplpath.'.php';
}

function getRefUrl() {
    return urlencode('http://'.$_SERVER['HTTP_HOST'].request_uri());
}

/**
 * 成员身份
 */
function memberIdentity($identity){
    switch (intval($identity)){
        case 1:
            return '<em class="c">'.L('bbs_manager').'</em>';
            break;
        case 2:
            return '<em class="a">'.L('bbs_administrate').'</em>';
            break;
        case 3:
        default:
            break;
    }
}

/**
 * 买家秀图像
 */
function showImgUrl($param){
    return UPLOAD_SITE_URL.'/'.ATTACH_MALBUM.'/'.$param['member_id'].'/'.str_ireplace('.', '_240.', $param['ap_cover']);
}
/**
 * 根据会员id生成部分附件路径
 */
function themePartPath($id){
    $a = $id%20;
    $b = $id%10;
    return $a.'/'.$b.'/'.$id;
}
/**
 * Inform Url link
 */
function spellInformUrl($param){
    if($param['reply_id'] == 0) return $url = 'index.php?w=theme&t=theme_detail&c_id='.$param['bbs_id'].'&t_id='.$param['theme_id'];

    $where = array();
    $where['bbs_id'] = $param['bbs_id'];
    $where['theme_id']  = $param['theme_id'];
    $where['reply_id']  = array('elt', $param['reply_id']);
    $count = Model()->table('bbs_threply')->where($where)->count();
    $page = ceil($count/15);
    return $url = 'index.php?w=theme&t=theme_detail&c_id='.$param['bbs_id'].'&t_id='.$param['theme_id'].'&curpage='.$page.'#f'.$param['reply_id'];
}
/**
 * Replace the UBB tag
 *
 * @param string $ubb
 * @param int $video_sign
 * @return string
 */
function replaceUBBTag($ubb, $video_sign = 1){
    if($video_sign){
        $flash_sign = preg_match("/\[FLASH\](.*)\[\/FLASH\]/iU", $ubb);
    }
    $ubb = str_replace(array(
            '[B]', '[/B]', '[I]', '[/I]', '[U]', '[/U]', '[/FONT]', '[/FONT-SIZE]', '[/FONT-COLOR]'
    ), array(
            '', '', '', '', '', '', '', '', ''
    ), preg_replace(array(
            "/\[URL=(.*)\](.*)\[\/URL\]/iU",
            "/\[FONT=([A-Za-z ]*)\]/iU",
            "/\[FONT-SIZE=([0-9]*)\]/iU",
            "/\[FONT-COLOR=([A-Za-z0-9]*)\]/iU",
            "/\[SMILIER=([A-Za-z_]*)\/\]/iU",
            "/\[IMG\](.*)\[\/IMG\]/iU",
            "/\[FLASH\](.*)\[\/FLASH\]/iU",
            "<img class='pi' src=\"$1\"/>",
    ), array(
            '['.L('wt_link').']',
            "",
            "",
            "",
            "",
            '['.L('wt_img').']',
            ($video_sign == 1?'':'['.L('wt_video').']'),
            ""
    ), $ubb));

    if($video_sign && !empty($flash_sign)){
        $ubb .= "<span wttype=\"theme_read\"><img src=\"".BBS_SITE_URL.'/templets/'.TPL_BBS_NAME."/images/default_play.gif\"></span>";
    }
    return $ubb;
}
/**
 * tidy theme goods information
 *
 * @param array $array
 * @param string $key
 * @param int $deep 1 one-dimensional array 2 two dimension array
 * @param string $type
 * @return array
 */
function tidyThemeGoods($array, $key, $deep=1, $type= 60){
    if (is_array($array)){
        $tmp = array();
        foreach ($array as  $v) {
            if($v['thg_type'] == 0){
                $v['image']     = thumb($v, $type);
                $v['thg_url']   = urlShop('goods', 'index', array('goods_id'=>$v['goods_id']));
            }else{
                $v['image'] = $v['goods_image'];
            }
            if ($deep === 1){
                $tmp[$v[$key]] = $v;
            }elseif($deep === 2){
                $tmp[$v[$key]][] = $v;
            }
        }
        return $tmp;
    }else{
        return $array;
    }
}
/**
 * The editor
 *
 * @param string $cname     The content of the editor 'id' and the 'name' of the name
 * @param string $content   The editor content
 * @param string $type      The toolbar type
 * @param array  $affix     The affix content
 * @param string $gname     The name of the goods content
 * @param array  $goods     The goods content
 * @param array  $readperm  Optional permissions array
 * @param int    $rpvalue   Has chosen the permissions
 */
function showMiniEditor($cname, $content = '', $type = 'all', $affix = array(), $gname = '', $goods = array(), $readperm = array(), $rpvalue = 0){
    switch ($type){
        case 'manage':
            $items = array('font', 'size', 'line', 'bold', 'italic', 'underline', 'color', 'line', 'url', 'flash', 'image', 'line', 'smilier');
            break;
        case 'quickReply':
            $items = array('font', 'size', 'line', 'bold', 'italic', 'underline', 'color', 'line', 'url', 'flash', 'line', 'smilier');
            break;
        case 'hQuickReply':
            $items = array('font', 'size', 'line', 'bold', 'italic', 'underline', 'color', 'line', 'url', 'flash', 'line', 'smilier', 'highReply');
            break;
        default:
            $items = array('font', 'size', 'line', 'bold', 'italic', 'underline', 'color', 'line', 'affix', 'line', 'url', 'flash', 'image', 'goods', 'line', 'smilier');
            break;
    }

    // toolbar items
    $_line  = "<span class=\"line\"></span>";
    $_font  = "<a href=\"javascript:void(0);\" wttype=\"font-family\" class=\"font-family\">".L('wt_font')."
                    <div class=\"ubb-layer font-family-layer\">
                        <div class=\"arrow\"></div>
                        <span class=\"ff01\" data-param=\"Microsoft YaHei\">".L('wt_Microsoft_YaHei')."</span><span class=\"ff02\" data-param=\"simsun\">".L('wt_simsun')."</span><span class=\"ff03\" data-param=\"simhei\">".L('wt_simhei')."</span><span class=\"ff04\" data-param=\"Arial\">Arial</span><span class=\"ff05\" data-param=\"Verdana\">Verdana</span><span class=\"ff06\" data-param=\"Helvetica\">Helvetica</span><span class=\"ff07\" data-param=\"Tahoma\">Tahoma</span>
                    </div>
                </a>";
    $_size  = "<a href=\"javascript:void(0);\" wttype=\"font-size\" class=\"font-size\">".L('wt_font_size')."
                    <div class=\"ubb-layer font-size-layer\">
                        <div class=\"arrow\"></div>
                        <span class=\"s12\">12px</span><span class=\"s14\">14px</span><span class=\"s16\">16px</span><span class=\"s18\">18px</span><span class=\"s20\">20px</span><span class=\"s22\">22px</span><span class=\"s24\">24px</span>
                    </div>
                </a>";
    $_bold  = "<a href=\"javascript:void(0);\" wttype=\"b\" title=\"".L('wt_font_bold')."\"><i class=\"font-b\"></i></a>";
    $_italic= "<a href=\"javascript:void(0);\" wttype=\"i\" title=\"".L('wt_font_italic')."\"><i class=\"font-i\"></i></a>";
    $_underline = "<a href=\"javascript:void(0);\" wttype=\"u\" title=\"".L('wt_font_underline')."\"><i class=\"font-u\"></i></a>";
    $_color = "<a href=\"javascript:void(0);\" wttype=\"color\" title=\"".L('wt_font_color')."\" class=\"font-color-handle\"><i class=\"font-color\"></i>
                    <div class=\"ubb-layer font-color-layer\">
                        <div class=\"arrow\"></div>
                        <span class=\"c-000000\"></span><span class=\"c-A0522D\"></span><span class=\"c-556B2F\"></span><span class=\"c-006400\"></span><span class=\"c-483D8B\"></span><span class=\"c-000080\"></span><span class=\"c-4B0082\"></span><span class=\"c-2F4F4F\"></span> <span class=\"c-8B0000\"></span><span class=\"c-FF8C00\"></span><span class=\"c-808000\"></span><span class=\"c-008000\"></span><span class=\"c-008080\"></span><span class=\"c-0000FF\"></span><span class=\"c-708090\"></span><span class=\"c-696969\"></span><span class=\"c-FF0000\"></span><span class=\"c-F4A460\"></span><span class=\"c-9ACD32\"></span><span class=\"c-2E8B57\"></span><span class=\"c-48D1CC\"></span><span class=\"c-4169E1\"></span><span class=\"c-800080\"></span><span class=\"c-808080\"></span><span class=\"c-FF00FF\"></span><span class=\"c-FFA500\"></span><span class=\"c-FFFF00\"></span><span class=\"c-00FF00\"></span><span class=\"c-00FFFF\"></span><span class=\"c-00BFFF\"></span><span class=\"c-9932CC\"></span><span class=\"c-C0C0C0\"></span><span class=\"c-FFC0CB\"></span><span class=\"c-F5DEB3\"></span><span class=\"c-FFFACD\"></span><span class=\"c-98FB98\"></span><span class=\"c-AFEEEE\"></span><span class=\"c-ADD8E6\"></span><span class=\"c-DDA0DD\"></span>
                    </div>
                </a>";
    $_affix = "<div class=\"upload-btn\" title=\"".L('wt_upload_image_affix')."\">
                    <span><i class=\"upload-img\"></i>
                        <div class=\"upload-button\">".L('wt_upload_affix')."</div>
                    </span>
                    <input type=\"file\" name=\"test_file\" id=\"test_file\" multiple=\"multiple\"  file_id=\"0\" class=\"upload-file\" size=\"1\" hidefocus=\"true\" style=\"cursor: pointer;\" />
                    <input id=\"submit_button\" style=\"display:none\" type=\"button\" value=\"&nbsp;\" onClick=\"submit_form($(this))\" />
                </div>";
    $_url   = "<a href=\"javascript:void(0);\" wttype=\"url\" title=\"".L('wt_insert_link_address')."\" class=\"mr5 url-handle\"><i class=\"url\"></i>".L('wt_line')."
                    <div class=\"ubb-layer url-layer\" style=\"display: none;\">
                        <div class=\"arrow\"></div>
                        <label>".L('wt_link_content')."</label>
                        <input name=\"content\" type=\"text\" class=\"text w180\" />
                        <label>".L('wt_link_address')."</label>
                        <input name=\"url\" type=\"text\" class=\"text w180\" placeholder=\"http://\" />
                        <input name=\"".L('wt_submit')."\" type=\"submit\" class=\"button\" value=\"".L('wt_submit')."\"/>
                    </div>
                </a>";
    $_flash = "<a href=\"javascript:void(0);\" wttype=\"flase\" title=\"".L('wt_video_address')."\" class=\"mr5 flash-handle\"><i class=\"flash\"></i>".L('wt_video')."
                    <div class=\"ubb-layer flash-layer\" style=\"display: none;\">
                        <div class=\"arrow\"></div>
                        <label>".L('wt_video_address')."</label>
                        <input name=\"flash\" type=\"text\" class=\"text w180\" placeholder=\"http://\" />
                        <input name=\"".L('wt_submit')."\" type=\"submit\" class=\"button\" value=\"".L('wt_submit')."\"/>
                    </div>
                </a>";
    $_image = "<a href=\"javascript:void(0);\" wttype=\"uploadImage\" title=\"".L('wt_insert_network_image')."\" class=\"mr5\"><i class=\"url-img\"></i>".L('wt_image')."</a>";
    $_goods = "<a href=\"javascript:void(0);\" wttype=\"chooseGoods\" title=\"".L('wt_insert_relevance_goods')."\"><i class=\"url-goods\"></i>".L('wt_goods')."</a>";
    $_smilier   = "<a href=\"javascript:void(0);\" wttype=\"smilier\" title=\"".L('wt_insert_smilier')."\" class=\"smilier-handle\"><i class=\"smilier\"></i>".L('wt_smilier')."
                        <div class=\"ubb-layer smilier-layer\">
                            <div class=\"arrow\"></div>
                            <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/adore.png\" data-param=\"adore\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/after_boom.png\" data-param=\"after_boom\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/ah.png\" data-param=\"ah\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/amazing.png\" data-param=\"amazing\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/anger.png\" data-param=\"anger\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/angry.png\" data-param=\"angry\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/baffle.png\" data-param=\"baffle\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/batman.png\" data-param=\"batman\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/beat_brick.png\" data-param=\"beat_brick\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/bigsmile.png\" data-param=\"bigsmile\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/bye_bye.png\" data-param=\"bye_bye\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/confuse.png\" data-param=\"confuse\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/cool.png\" data-param=\"cool\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/crazy.png\" data-param=\"crazy\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/crazy_rabbit.png\" data-param=\"crazy_rabbit\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/cry.png\" data-param=\"cry\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/dead.png\" data-param=\"dead\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/devil.png\" data-param=\"devil\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/diver.png\" data-param=\"diver\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/doubt.png\" data-param=\"doubt\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/evilgrin.png\" data-param=\"evilgrin\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/exciting.png\" data-param=\"exciting\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/flower_dead.png\" data-param=\"flower_dead\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/go.png\" data-param=\"go\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/greedy.png\" data-param=\"greedy\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/haha.png\" data-param=\"haha\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/hand_flower.png\" data-param=\"hand_flower\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/happy.png\" data-param=\"happy\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/horror.png\" data-param=\"horror\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/hypnotized.png\" data-param=\"hypnotized\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/kiss.png\" data-param=\"kiss\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/love.png\" data-param=\"love\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/mad.png\" data-param=\"mad\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/matrix.png\" data-param=\"matrix\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/misdoubt.png\" data-param=\"misdoubt\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/money.png\" data-param=\"money\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/nerd.png\" data-param=\"nerd\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/ninja.png\" data-param=\"ninja\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/nosebleed.png\" data-param=\"nosebleed\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/pirate.png\" data-param=\"pirate\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/question.png\" data-param=\"question\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/sad.png\" data-param=\"sad\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/shame.png\" data-param=\"shame\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/shocked.png\" data-param=\"shame\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/silent.png\" data-param=\"silent\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/sleep.png\" data-param=\"sleep\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/sweat.png\" data-param=\"sweat\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/star.png\" data-param=\"star\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/whist.png\" data-param=\"whist\"></span> <span><img src=\"".BBS_TEMPLATES_URL."/images/smilier/surrender.png\" data-param=\"surrender\"></span>
                        </div>
                    </a>";
    $_highReply= "<a href=\"javascript:void(0);\" wttype=\"highReply\" class=\"high-reply\"><i class=\"high\"></i>".L('wt_showanced_reply')."</a>";

    // Spell the editor contents
    $__content = '';
    $__content .= "<div class=\"content\">
            <div class=\"ubb-bar\">";
    foreach ($items as $val){
        $val = '_'.$val;
        $__content .= $$val;
    }
    $__content .= "</div>
            <div class=\"textarea\">
                <textarea id=\"".$cname."\" name=\"".$cname."\">".$content."</textarea>
            </div>
            <div class=\"smilier\"></div>
        </div>";


    // The attachment part
    $__affix = '';
    $__affix .= "<div class=\"affix\">
              <h3><i></i>".L('wt_relevance_adjunct')."</h3>
              <div class=\"help\" wttype=\"affix\" ".(empty($affix)?"":"style=\"display: none;\"").">
                <p>".L('wt_relevance_adjunct_help_one')."</p>
                <p>".L('wt_relevance_adjunct_help_two')."</p>
              </div>
              <div id=\"scrollbar\">
              <ul>";
    if(!empty($affix)){
        foreach($affix as $val){
            $__affix .=  "<li>
                  <p><img src=\"".themeImageUrl($val['affix_filethumb'])."\"> </p>
                  <div class=\"handle\"> <a data-param=\"".themeImageUrl($val['affix_filename'])."\" wttype=\"affix_insert\" href=\"javascript:void(0);\"><i class=\"c\"></i>".L('wt_insert')."</a> <a data-param=\"".$val['affix_id']."\" wttype=\"affix_delete\" href=\"javascript:void(0);\"><i class=\"d\"></i>".L('wt_delete')."</a> </div>
                </li>";
        }
    }
    $__affix .= "</ul>
              </div>
            </div>";

    $__maffix = str_replace("wttype=\"affix_delete\"", "wttype=\"maffix_delete\"", $__affix);

    // After insert part of goods
    $__goods = '';
    $__goods .= "<div class=\"insert-goods\" ".(empty($goods)?"style=\"display:none;\"":"").">
              <h3><i></i>".L('wt_select_insert_goods,wt_colon')."</h3>";
    if(!empty($goods)){
        foreach($goods as $val){
            $__goods .= "<dl>
                <dt class=\"goods-name\">".$val['goods_name']."</dt>
                <dd class=\"goods-pic\"><a href=\"javascript:void(0);\"><img src=\"".$val['image']."\"></a></dd>
                <dd class=\"goods-price\"><em>".$val['goods_price']."</em></dd>
                <dd class=\"goods-del\">".L('wt_delete')."</dd>
                <input type=\"hidden\" value=\"".$val['goods_id']."\" name=\"".$gname."[".$val['themegoods_id']."][id]\">
                <input type=\"hidden\" value=\"".$val['goods_name']."\" name=\"".$gname."[".$val['themegoods_id']."][name]\">
                <input type=\"hidden\" value=\"".$val['goods_price']."\" name=\"".$gname."[".$val['themegoods_id']."][price]\">
                <input type=\"hidden\" value=\"".$val['goods_image']."\" name=\"".$gname."[".$val['themegoods_id']."][image]\">
                <input type=\"hidden\" value=\"".$val['store_id']."\" name=\"".$gname."[".$val['themegoods_id']."][storeid]\">
                <input type=\"hidden\" value=\"".$val['thg_type']."\" name=\"".$gname."[".$val['themegoods_id']."][type]\">
                <input type=\"hidden\" value=\"".$val['thg_url']."\" name=\"".$gname."[".$val['themegoods_id']."][uri]\">
              </dl>";
        }
    }
    $__goods .= "</div>";

    // Part read permissions
    $__readperm = '';
    if(!empty($readperm)){
        $__readperm .= "<div class=\"readperm\"><span>".L('wt_read_perm,wt_colon')."</span><span><select name=\"readperm\">";
        foreach($readperm as $key=>$val){
            $__readperm .= "<option value=\"".$key."\" ".(($rpvalue == $key)?"selected=\"selected\"":"").">".$val."&nbsp;lv".$key."</option>";
        }
        $__readperm .= "</select></span></div>";
    }

    switch ($type){
        case 'manage':
            $return = $__content.$__maffix.$__goods.$__readperm;
            break;
        case 'quickReply':
            $return = $__content;
            break;
        case 'hQuickReply':
            $return = $__content;
            break;
        default:
            $return = $__content.$__affix.$__goods.$__readperm;
            break;
    }
    return $return;
}


/**
 * Recently two voters
 */
function recentlyTwoVoters($str){
    $str = explode(' ', $str, 3);
    $rs = '';
    if(isset($str[0]) && !empty($str[0])) $rs .= $str[0];
    if(isset($str[1]) && !empty($str[1])) $rs .= ', '.$str[1];
    return $rs;
}

/**
 * member rank html
 */
function memberLevelHtml($param){
    if ($param['is_identity'] == 1) {
        return "<div class=\"member-rank member-rank-m\" title=\"圈主\">圈主<i></i></div>";
    } else if ($param['is_identity'] == 2) {
        return "<div class=\"member-rank member-rank-a\" title=\"管理员\">管理员<i></i></div>";
    } else {
        return "<div class=\"member-rank member-rank-".$param['cm_level']."\" title=\"".L('bbs_level_introduction_page')."\"><a href=\"".BBS_SITE_URL."/index.php?w=group&t=level_intr&c_id=".$param['bbs_id']."\" target=\"_blank\">".($param['cm_levelname'] == ''?L('bbs_violation'):$param['cm_levelname'])."</a><i></i><em>".$param['cm_level']."</em></div>";
    }
}
/**
 * 文本过滤
 * @param $param string $subject
 * @return string
 */
function bbsCenterCensor($subject){
    $replacement = '***';
    if(C('bbs_wordfilter') == '') return $subject;
    $find = explode(',', C('bbs_wordfilter'));
    foreach ($find as $val){
        if(preg_match('/^\/(.+?)\/$/', $val, $a)){
            $subject = preg_replace($val, $replacement, $subject);
        }else{
            $val = preg_replace("/\\\{(\d+)\\\}/", ".{0,\\1}", preg_quote($val, '/'));
            $subject = preg_replace("/".$val."/", $replacement, $subject);
        }
    }
    return $subject;
}
