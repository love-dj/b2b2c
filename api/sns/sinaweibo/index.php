<?php
include_once(BASE_API_PATH.DS.'sns'.DS.'sinaweibo'.DS.'config.php' );
include_once(BASE_API_PATH.DS.'sns'.DS.'sinaweibo'.DS.'saetv2.ex.class.php' );
$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
$code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );
@header("location:$code_url");
exit;
?>
