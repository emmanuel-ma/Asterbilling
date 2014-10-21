<?php
/*******************************************************************************
* credithistory.common.php
* credithistory参数信息文件

* credithistory parameter file

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

registed function:
*	call these function by xajax_ + funcionname
*	such as xajax_init()

	showGrid
	add					show account add form
	init				init html page

* Revision 0.045  2007/10/17 15:25:00  modified by solo
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

require_once ('include/localization.class.php');
if ($_SESSION['curuser']['usertype'] != 'admin' && $_SESSION['curuser']['usertype'] != 'reseller' && $_SESSION['curuser']['usertype'] != 'groupadmin' && $_SESSION['curuser']['usertype'] != 'clid') {
	header("Location: systemstatus.php");
}


require_once ("include/xajax.inc.php");

$GLOBALS['locate']=new Localization($_SESSION['curuser']['country'],$_SESSION['curuser']['language'],'credithistory');

$xajax = new xajax("credithistory.server.php");

$xajax->registerFunction("showGrid");
$xajax->registerFunction("init");
$xajax->registerFunction("searchFormSubmit");
$xajax->registerFunction("showClidCredit");

define("ROWSXPAGE", 25); // Number of rows show it per page.
define("MAXROWSXPAGE", 50);  // Total number of rows show it when click on "Show All" button.

?>
