<?php
/*******************************************************************************
* system_config.common.php
* system config

* system config parameter file

* 功能描述
	检查用户权限
	初始化语言变量
	初始化xajax类
	预定义xajaxGrid中需要使用的一些参数

* Function Desc
	authority
	initialize localization class
	initialize xajax class
	define xajaxGrid parameters

* Revision 0.0057  2014/12/01 10:19:00  last modified by ema
* Desc: page created

********************************************************************************/

header('Content-Type: text/html; charset=utf-8');
header('Expires: Sat, 01 Jan 2000 00:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: post-check=0, pre-check=0',false);
header('Pragma: no-cache');
session_cache_limiter('public, no-store');

session_set_cookie_params(0);
if (!session_id()) session_start();
setcookie('PHPSESSID', session_id());


if ($_SESSION['curuser']['usertype'] != 'admin') {
    header("Location: checkout.php");
}


require_once ("include/xajax.inc.php");
require_once ('include/localization.class.php');

$GLOBALS['locate']=new Localization($_SESSION['curuser']['country'],$_SESSION['curuser']['language'],'systemconfig');

$xajax = new xajax("system_config.server.php");

$xajax->registerFunction("init");
$xajax->registerFunction("profileAction");
?>