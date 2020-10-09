<?php
/**
 * 此为PHP-SDK 2.0
 */
require_once(BASE_API_PATH.DS.'sns'.DS.'qqweibo'.DS.'tencent.php' );
require_once(BASE_API_PATH.DS.'sns'.DS.'qqweibo'.DS.'config.php' );

OAuth::init($client_id, $client_secret);
Tencent::$debug = $debug;

$url = OAuth::getAuthorizeURL($callback);
header('Location: ' . $url);
