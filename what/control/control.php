<?php
/**
 * 前台control父类,店铺control父类,会员control父类
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

/********************************** 前台control父类 **********************************************/

class MircroShopControl{

    const GOODS_FLAG = 1;
    const PERSONAL_FLAG = 2;
    const ALBUM_FLAG = 3;
    const STORE_FLAG = 4;

    /**
     * 构造函数
     */
    public function __construct(){
        /**
         * 读取通用、布局的语言包
         */
        Language::read('common');
        Language::read('what');
        /**
         * 判断买什么是否关闭
         */
        if (C('what_isuse') != '1'){
            header('location: '.BASE_SITE_URL);die;
        }
        /**
         * 设置布局文件内容
         */
        Tpl::setLayout('what_layout');
        /**
         * 转码
         */
        if ($_GET['column'] && strtoupper(CHARSET) == 'GBK'){
            $_GET = Language::getGBK($_GET);
        }
        /**
         * 获取导航
         */
        Tpl::output('nav_list', rkcache('nav',true));
        /**
         * 搜索类型列表
         */
        $search_type = array();
        $search_type['goods'] = Language::get('wt_what_goods');
        $search_type['personal'] = Language::get('wt_what_personal');
        Tpl::output('search_type',$search_type);

        /**
         * 系统状态检查
         */
        if(!C('site_status')) halt(C('closed_reason'));

        /**
         * seo
         */
        Tpl::output('html_title',Language::get('wt_what').'-'.C('site_name'));
        Tpl::output('seo_keywords',C('what_seo_keywords'));
        Tpl::output('seo_description',C('what_seo_description'));

    }

    protected function check_login() {
        if(!isset($_SESSION['is_login'])) {
            @header("location: " . urlLogin('login', 'index', array('ref_url' => getRefUrl())));die;
        }
    }

    protected function get_channel_type($channel_name) {
        $result = array();
        switch ($channel_name) {
            case 'goods':
                $result['type_id'] = self::GOODS_FLAG;
                $result['type_key'] = 'commend_id';
                break;
            case 'personal':
                $result['type_id'] = self::PERSONAL_FLAG;
                $result['type_key'] = 'personal_id';
                break;
            case 'store':
                $result['type_id'] = self::STORE_FLAG;
                $result['type_key'] = 'what_store_id';
                break;
            default:
                break;
        }
        return $result;
    }

    protected function get_personal_class_list() {
        $model_class = Model("what_personal_class");
        $list = $model_class->getList(TRUE);
        Tpl::output('personal_class_list',$list);
    }

    //获取商品列表
    protected function get_goods_list($condition,$order='commend_time desc') {
        $model_what_goods = Model('what_goods');
        $page_number = 35;
        $field = 'what_goods.*,member.member_name,member.member_avatar';
        $list = $model_what_goods->getListWithUserInfo($condition,$page_number,$order,$field);
        Tpl::output('show_page',$model_what_goods->showpage(2));
        Tpl::output('list',$list);
    }

    //获取买心得列表
    protected function get_personal_list($condition,$order='commend_time desc') {
        $model_personal = Model('what_personal');
        $page_number = 35;
        $field = 'what_personal.*,member.member_name,member.member_avatar';
        $list = $model_personal->getListWithUserInfo($condition,$page_number,$order,$field);
        Tpl::output('show_page',$model_personal->showpage(2));
        Tpl::output('list',$list);
    }

    //获得分享列表
    protected function get_share_app_list() {
        $app_array = array();
        if (C('share_isuse') == 1 && isset($_SESSION['member_id'])){
            //站外分享接口
            $model = Model('sns_binding');
            $app_array = $model->getUsableApp($_SESSION['member_id']);
        }
        Tpl::output('app_arr',$app_array);
    }

    protected function share_app_publish($type,$publish_info=array()) {
        $param = array();
        switch ($type) {
        case 'comment':
            $param['comment'] = "'".$_SESSION['member_name']."'".Language::get('what_text_zai').Language::get('wt_what').Language::get('what_text_comment').Language::get('what_text_le').Language::get("wt_what_{$publish_info['type']}_content");
            $param['title'] = "'".$_SESSION['member_name']."'".Language::get('what_text_zai').Language::get('wt_what').Language::get('what_text_comment').Language::get('what_text_le').Language::get("wt_what_{$publish_info['type']}_content");
            break;
        case 'publish':
            $param['comment'] = "'".$_SESSION['member_name']."'".Language::get('what_text_zai').Language::get('wt_what').Language::get('what_text_commend').Language::get('what_text_le').Language::get("wt_what_{$publish_info['type']}_content");
            $param['title'] = "'".$_SESSION['member_name']."'".Language::get('what_text_zai').Language::get('wt_what').Language::get('what_text_commend').Language::get('what_text_le').Language::get("wt_what_{$publish_info['type']}_content");
            break;
        case 'share':
            $param['comment'] = "'".$_SESSION['member_name']."'".Language::get('what_text_zai').Language::get('wt_what').Language::get('what_text_share').Language::get('what_text_le').Language::get("wt_what_{$publish_info['type']}_content");
            $param['title'] = "'".$_SESSION['member_name']."'".Language::get('what_text_zai').Language::get('wt_what').Language::get('what_text_share').Language::get('what_text_le').Language::get("wt_what_{$publish_info['type']}_content");
            break;
        }
        $param['url'] = $publish_info['url'];
        $function_name = "get_share_app_{$publish_info['type']}_content";
        $param['content'] = self::$function_name($publish_info,$param);
        $param['images'] = '';

        //分享应用
        $app_items = array();
        foreach ($_POST['share_app_items'] as $val) {
            if($val != '') {
                $app_items[$val] = TRUE;
            }
        }

        if (C('share_isuse') == 1 && !empty($app_items)){
            $model = Model('sns_binding');
            //查询该用户的绑定信息
            $bind_list = $model->getUsableApp($_SESSION['member_id']);
            //商城
            if (isset($app_items['shop'])){

                $model_member = Model('member');
                $member_info = $model_member->getMemberInfoByID($_SESSION['member_id']);

                $tracelog_model = Model('sns_tracelog');
                $insert_arr = array();
                $insert_arr['trace_originalid'] = '0';
                $insert_arr['trace_originalmemberid'] = '0';
                $insert_arr['trace_memberid'] = $_SESSION['member_id'];
                $insert_arr['trace_membername'] = $_SESSION['member_name'];
                $insert_arr['trace_memberavatar'] = $member_info['member_avatar'];
                $insert_arr['trace_title'] = $publish_info['commend_message'];
                $insert_arr['trace_content'] = $param['content'];
                $insert_arr['trace_addtime'] = time();
                $insert_arr['trace_state'] = '0';
                $insert_arr['trace_privacy'] = 0;
                $insert_arr['trace_commentcount'] = 0;
                $insert_arr['trace_copycount'] = 0;
                $insert_arr['trace_from'] = '3';
                $result = $tracelog_model->tracelogAdd($insert_arr);

            }
            //腾讯微博
            if (isset($app_items['qqweibo']) && $bind_list['qqweibo']['isbind'] == true){
                $model->addQQWeiboPic($bind_list['qqweibo'],$param);
            }
            //新浪微博
            if (isset($app_items['sinaweibo']) && $bind_list['sinaweibo']['isbind'] == true){
                $model->addSinaWeiboUpload($bind_list['sinaweibo'],$param);
            }
        }
    }

    //商品sns内容结构
    protected function get_share_app_goods_content($goods_content,$param) {
        $content_str = "
            <div class='fd-media'>
            <div class='goodsimg'><a target=\"_blank\" href=\"{$param['url']}\"><img src=\"".cthumb($goods_content['commend_goods_image'], 240, $goods_content['commend_goods_store_id'])."\" onload=\"javascript:DrawImage(this,120,120);\" title=\"{$goods_content['commend_goods_name']}\" alt=\"{$goods_content['commend_goods_name']}\"></a></div>
            <div class='goodsinfo'>
            <dl>
            <dt><a target=\"_blank\" href=\"{$param['url']}\">{$goods_content['commend_goods_name']}</a></dt>
            <dd>".Language::get('wt_common_price').Language::get('wt_colon').Language::get('currency').$goods_content['commend_goods_price']."</dd>
            <dd>{$param['comment']}<a target=\"_blank\" href=\"{$param['url']}\">".Language::get('wt_common_goto')."</a></dd>
            </dl>
            </div>
            </div>
            ";
        return $content_str;
    }

    //买心得sns内容结构
    protected function get_share_app_personal_content($personal_info,$param) {
        $personal_image_array = getwhatPersonalImageUrl($personal_info,'list');
        $personal_image_array_tiny = getwhatPersonalImageUrl($personal_info,'tiny');
        $content_str = "
            <div class='fd-media'>
            <div class='goodsimg'><a target=\"_blank\" href=\"{$param['url']}\"><img src=\"".$personal_image_array[0]."\" onload=\"javascript:DrawImage(this,120,120);\"></a></div>
            <div class='goodsinfo'>
            <ul>
            ";
        if(!empty($personal_image_array_tiny[1])) {
            $content_str .= "<li><a target=\"_blank\" href=\"{$param['url']}\"><img src=\"".$personal_image_array_tiny[1]."\" onload=\"javascript:DrawImage(this,60,60);\"></a></li>";
        }
        if(!empty($personal_image_array_tiny[2])) {
            $content_str .= "<li><a target=\"_blank\" href=\"{$param['url']}\"><img src=\"".$personal_image_array_tiny[2]."\" onload=\"javascript:DrawImage(this,60,60);\"></a></li>";
        }
        $content_str .= "</ul><p>{$param['comment']}<a target=\"_blank\" href=\"{$param['url']}\">".Language::get('wt_common_goto')."</a></p>
            </div>
            </div>
            ";
        return $content_str;
    }

    //买心得sns内容结构
    protected function get_share_app_store_content($store_info,$param) {
        $content_str = "
            <div class='fd-media'>
            <div class='goodsimg'><a target=\"_blank\" href=\"{$param['url']}\"><img src=\"".getStoreLogo($store_info['store_avatar'])."\" onload=\"javascript:DrawImage(this,120,120);\"></a></div>
            <div class='goodsinfo'>
            <dl>
            <dt><a target=\"_blank\" href=\"{$param['url']}\">{$store_info['store_name']}</a></dt>
            <dd>{$param['comment']}<a target=\"_blank\" href=\"{$param['url']}\">".Language::get('wt_common_goto')."</a></dd>
            </dl>
            </div>
            </div>
            ";
        return $content_str;
    }

    /**
     * 买什么详细页侧栏
     */
    protected function get_sidebar_list($member_id) {
        //说说看
        $model_what_goods = Model('what_goods');
        $sidebar_goods_list = $model_what_goods->getList(array('commend_member_id'=>$member_id),null,'commend_time desc','*',9);
        Tpl::output('sidebar_goods_list',$sidebar_goods_list);
        //买心得
        $model_what_personal = Model('what_personal');
        $sidebar_personal_list = $model_what_personal->getList(array('commend_member_id'=>$member_id),null,'commend_time desc','*',9);
        Tpl::output('sidebar_personal_list',$sidebar_personal_list);
    }

    /**
     * 用户详细信息
     */
    protected function get_member_detail_info($member_info) {
        $model = Model();
        //生成缓存的键值
        $member_id  = $member_info['member_id'];
        //粉丝数
        $fan_count = $model->table('sns_friend')->where(array('friend_tomid'=>$member_id))->count();
        $member_info['fan_count'] = $fan_count;
        //关注数
        $attention_count = $model->table('sns_friend')->where(array('friend_frommid'=>$member_id))->count();
        $member_info['attention_count'] = $attention_count;
        //兴趣标签
        $mtag_list = $model->table('sns_membertag,sns_mtagmember')->field('mtag_name')->on('sns_membertag.mtag_id = sns_mtagmember.mtag_id')->join('inner')->where(array('sns_mtagmember.member_id'=>$member_id))->select();
        $tagname_array = array();
        if(!empty($mtag_list)){
            foreach ($mtag_list as $val){
                $tagname_array[] = $val['mtag_name'];
            }
        }
        $member_info['tagname'] = $tagname_array;
        return $member_info;
    }

    /**
     * 删除买心得图片
     */
    protected function drop_personal_image($commend_image) {
        $image_array = explode(',',$commend_image);
        foreach ($image_array as $image_name) {
            list($name, $ext) = explode(".", $image_name);
            $name = str_replace('/', '', $name);
            $name = str_replace('.', '', $name);
            $image = array();
            $image['src'] = $name.'.'.$ext;
            $image['list'] = $image['src'].'_list.'.$ext;
            $image['tiny'] = $image['src'].'_tiny.'.$ext;

            foreach ($image as $value) {
                $image_name = BasePath.DS.ATTACH_WHAT.DS.$_SESSION['member_id'].DS.$value;
                if(is_file($image_name)) {
                    unlink($image_name);
                }
            }
        }
    }

    /**
     * 返回json状态
     */
    protected function return_json($message,$result='true') {
        $data = array();
        $data['result'] = $result;
        $data['message'] = $message;
        self::echo_json($data);
    }

    protected function echo_json($data) {
        if (strtoupper(CHARSET) == 'GBK'){
            $data = Language::getUTF8($data);//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
        }
        echo json_encode($data);
    }

    /**
     * 买什么广告
     */
    protected function get_what_show($type='index') {
        $model = Model('what_show');
        $show_list = $model->getList(array('show_type'=>$type),null,'show_sort asc');
        Tpl::output($type.'_show_list',$show_list);
    }

    /**
     * 获取主域名
     */
    protected function get_url_domain($url) {
        $url_parse_array = parse_url($url);
        $host = $url_parse_array['host'];
        $host_names = explode(".", $host);
        $bottom_host_name = $host_names[count($host_names)-2] . "." . $host_names[count($host_names)-1];
        return $bottom_host_name;
    }
}
