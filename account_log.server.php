<?php
/*******************************************************************************
* account_log.server.php

* 账户管理系统后台文件
* account background management script

* Function Desc
	provide account_log management script

* 功能描述
	提供帐户管理脚本

* Function Desc
		init				初始化页面元素
		showGrid			显示grid
		createGrid			生成grid的HTML代码
		add					显示添加account的表单
		save				保存account信息
		update				更新account信息
		edit				显示修改account的表单
		delete				删除account信息
		showDetail			显示account详细信息
							当前返回空值
		searchFormSubmit    根据提交的搜索信息重构显示页面


********************************************************************************/

require_once ("db_connect.php");
require_once ('account_log.grid.inc.php');
require_once ('include/xajaxGrid.inc.php');
require_once ('include/astercrm.class.php');
require_once ('include/common.class.php');
require_once ("account_log.common.php");

/**
*  initialize page elements
*
*/

function init(){
	global $locate;

	$objResponse = new xajaxResponse();
	$objResponse->addAssign("divNav","innerHTML",common::generateManageNav($skin));
	$objResponse->addAssign("divCopyright","innerHTML",common::generateCopyright($skin));
	$objResponse->addScript("xajax_showGrid(0,".ROWSXPAGE.",'','','')");

	return $objResponse;
}

/**
*  show grid HTML code
*  @param	start		int			record start
*  @param	limit		int			how many records need
*  @param	filter		string		the field need to search
*  @param	content		string		the contect want to match
*  @param	divName		string		which div grid want to be put
*  @param	order		string		data order
*  @return	objResponse	object		xajax response object
*/

function showGrid($start = 0, $limit = 1,$filter = null, $content = null, $order = null, $divName = "grid", $ordering = ""){
	$html .= createGrid($start, $limit,$filter, $content, $order, $divName, $ordering);
	$objResponse = new xajaxResponse();
	$objResponse->addClear("msgZone", "innerHTML");
	$objResponse->addAssign($divName, "innerHTML", $html);

	return $objResponse;
}


/**
*  generate grid HTML code
*  @param	start		int			record start
*  @param	limit		int			how many records need
*  @param	filter		string		the field need to search
*  @param	content		string		the contect want to match
*  @param	divName		string		which div grid want to be put
*  @param	order		string		data order
*  @return	html		string		grid HTML code
*/

function createGrid($start = 0, $limit = 1, $filter = null, $content = null, $order = null, $divName = "grid", $ordering = "",$stype=array()){
	global $locate,$config;
	$_SESSION['ordering'] = $ordering;
	
	if($filter == null or $content == null || (!is_array($content) && $content == 'Array') || (!is_array(filter) && $filter == 'Array')){
		$content = null;
		$filter = null;
		$numRows =& Customer::getNumRows();
		$arreglo =& Customer::getAllRecords($start,$limit,$order);
	}else{
		foreach($content as $value){
			if(trim($value) != ""){  //搜索内容有值
				$flag = "1";
				break;
			}
		}
		foreach($filter as $value){
			if(trim($value) != ""){  //搜索条件有值
				$flag2 = "1";
				break;
			}
		}
		foreach($stype as $value){
			if(trim($value) != ""){  //搜索方式有值
				$flag3 = "1";
				break;
			}
		}

		if($flag != "1" || $flag2 != "1"){  //无值
			$order = null;
			$numRows =& Customer::getNumRows();
			$arreglo =& Customer::getAllRecords($start,$limit,$order);
		}elseif($flag3 != 1 ){
			$order = "id";
			$numRows =& Customer::getNumRowsMore($filter, $content,"account");
			$arreglo =& Customer::getRecordsFilteredMore($start, $limit, $filter, $content, $order,"account");
		}else{
			$order = "id";
			$numRows =& Customer::getNumRowsMorewithstype($filter, $content,$stype,$table);
			$arreglo =& Customer::getRecordsFilteredMorewithstype($start, $limit, $filter, $content, $stype,$order,$table);
		}
	}
		
	// Select Box: type table.
	$typeFromSearch = array();
	$typeFromSearch[] = 'like';
	$typeFromSearch[] = 'equal';
	$typeFromSearch[] = 'more';
	$typeFromSearch[] = 'less';

	// Selecct Box: Labels showed on searchtype select box.
	$typeFromSearchShowAs = array();
	$typeFromSearchShowAs[] = $locate->Translate("like");
	$typeFromSearchShowAs[] = '=';
	$typeFromSearchShowAs[] = '>';
	$typeFromSearchShowAs[] = '<';

	// Editable zone

	// Databse Table: fields
	$fields = array();
	$fields[] = 'username';
	$fields[] = 'ip';
	$fields[] = 'usertype';
	$fields[] = 'action';
	$fields[] = 'status';
	$fields[] = 'failedcause';
	$fields[] = 'cretime';

	// HTML table: Headers showed
	$headers = array();
	$headers[] = $locate->Translate("Username");
	$headers[] = $locate->Translate("IP");
	$headers[] = $locate->Translate("Usertype");
	$headers[] = $locate->Translate("Action");
	$headers[] = $locate->Translate("Status");
	$headers[] = $locate->Translate("Failed cause");
	$headers[] = $locate->Translate("Cretime");
	$headers[] = $locate->Translate("Option");

	// HTML table: hearders attributes
	$attribsHeader = array();
	$attribsHeader[] = 'width=""';
	$attribsHeader[] = 'width=""';
	$attribsHeader[] = 'width=""';
	$attribsHeader[] = 'width=""';
	$attribsHeader[] = 'width=""';
	$attribsHeader[] = 'width=""';
	$attribsHeader[] = 'width=""';

	// HTML Table: columns attributes
	$attribsCols = array();
	$attribsCols[] = 'style="text-align: left"';
	$attribsCols[] = 'style="text-align: left"';
	$attribsCols[] = 'style="text-align: left"';
	$attribsCols[] = 'style="text-align: left"';
	$attribsCols[] = 'style="text-align: left"';
	$attribsCols[] = 'style="text-align: left"';
	$attribsCols[] = 'style="text-align: left"';

	// HTML Table: If you want ascendent and descendent ordering, set the Header Events.
	$eventHeader = array();
	$eventHeader[]= 'onClick=\'xajax_showGrid(0,'.$limit.',"'.$filter.'","'.$content.'","username","'.$divName.'","ORDERING");return false;\'';
	$eventHeader[]= 'onClick=\'xajax_showGrid(0,'.$limit.',"'.$filter.'","'.$content.'","ip","'.$divName.'","ORDERING");return false;\'';
	$eventHeader[]= 'onClick=\'xajax_showGrid(0,'.$limit.',"'.$filter.'","'.$content.'","usertype","'.$divName.'","ORDERING");return false;\'';
	$eventHeader[]= 'onClick=\'xajax_showGrid(0,'.$limit.',"'.$filter.'","'.$content.'","action","'.$divName.'","ORDERING");return false;\'';
	$eventHeader[]= 'onClick=\'xajax_showGrid(0,'.$limit.',"'.$filter.'","'.$content.'","status","'.$divName.'","ORDERING");return false;\'';
	$eventHeader[]= 'onClick=\'xajax_showGrid(0,'.$limit.',"'.$filter.'","'.$content.'","failedcause","'.$divName.'","ORDERING");return false;\'';
	$eventHeader[]= 'onClick=\'xajax_showGrid(0,'.$limit.',"'.$filter.'","'.$content.'","cretime","'.$divName.'","ORDERING");return false;\'';

	// Select Box: fields table.
	$fieldsFromSearch = array();
	$fieldsFromSearch[] = 'username';
	$fieldsFromSearch[] = 'ip';
	$fieldsFromSearch[] = 'usertype';
	$fieldsFromSearch[] = 'action';
	$fieldsFromSearch[] = 'status';
	$fieldsFromSearch[] = 'failedcause';
	$fieldsFromSearch[] = 'cretime';

	// Selecct Box: Labels showed on search select box.
	$fieldsFromSearchShowAs = array();
	$fieldsFromSearchShowAs[] = $locate->Translate("Username");
	$fieldsFromSearchShowAs[] = $locate->Translate("IP");
	$fieldsFromSearchShowAs[] = $locate->Translate("Usertype");
	$fieldsFromSearchShowAs[] = $locate->Translate("Action");
	$fieldsFromSearchShowAs[] = $locate->Translate("status");
	$fieldsFromSearchShowAs[] = $locate->Translate("Failed cause");
	$fieldsFromSearchShowAs[] = $locate->Translate("cretime");


	// Create object whit 5 cols and all data arrays set before.
	$table = new ScrollTable(7,$start,$limit,$filter,$numRows,$content,$order);
	$table->setHeader('title',$headers,$attribsHeader,$eventHeader,0,0,0);
	$table->setAttribsCols($attribsCols);
	$table->exportFlag = '1';//对导出标记进行赋值

	$table->addRowSearchMore("account_log",$fieldsFromSearch,$fieldsFromSearchShowAs,$filter,$content,$start,$limit,0,$typeFromSearch,$typeFromSearchShowAs,$stype);

	while ($arreglo->fetchInto($row)) {
	// Change here by the name of fields of its database table
		$rowc = array();
		$rowc[] = $row['id'];
		$rowc[] = $row['username'];
		$rowc[] = $row['ip'];
		$rowc[] = $row['usertype'];
		$rowc[] = $row['action'];
		$rowc[] = $row['status'];
		$rowc[] = $row['failedcause'];
		$rowc[] = $row['cretime'];
		
		if($row['failedtimes'] >= $config['system']['max_incorrect_login']  && $config['system']['max_incorrect_login'] > 0){
			$rowc[] = '<a onclick="xajax_unlock(\''.$row['ip'].'\',xajax.getFormValues(\'searchForm\'))">'.$locate->Translate("Unlock").'</a>';
			$trstyle = 'style="background:#d5c59f;"';
		}else{
			$trstyle = '';
		}
		$table->addRow("account_log",$rowc,0,0,0,$divName,$fields,$trstyle);
 	}
 	
 	// End Editable Zone
 	
 	$html = $table->render();
 	
 	return $html;
}

function unlock($ip,$s){
	global $db;
	//print_r($s);exit;
	$objResponse = new xajaxResponse();
	$sql = "UPDATE account_log SET failedtimes='0' WHERE ip='".$ip."'";
	astercrm::events($sql);
	$res =& $db->query($sql);

	$searchField = array();
	$searchContent = array();
	$searchContent = $s['searchContent'];  //搜索内容 数组
	$searchField = $s['searchField'];      //搜索条件 数组
	$searchType =  $s['searchType'];			//搜索方式 数组

	$html = createGrid(0, 25,$searchField, $searchContent, $searchField, 'grid', "",$searchType);
	$objResponse->addClear("msgZone", "innerHTML");
	$objResponse->addAssign('grid', "innerHTML", $html);
	return $objResponse;
}

/**
*  generate account add form HTML code
*  @return	html		string		account add HTML code
*/

function add(){
   // Edit zone
	global $locate;
	$objResponse = new xajaxResponse();
	$html = Table::Top($locate->Translate("adding_account"),"formDiv");  // <-- Set the title for your form.
	$html .= Customer::formAdd();  // <-- Change by your method
	// End edit zone
	$html .= Table::Footer();
	$objResponse->addAssign("formDiv", "style.visibility", "visible");
	$objResponse->addAssign("formDiv", "innerHTML", $html);
	
	return $objResponse->getXML();
}

/**
*  save account record
*  @param	f			array		account record
*  @return	objResponse	object		xajax response object
*/

function save($f){
	global $locate,$db,$config;
	$objResponse = new xajaxResponse();

	if($config['synchronize']['id_autocrement_byset']){
		$local_lastid = astercrm::getLocalLastId('account');
		$f['id'] = intval($local_lastid+1);
	}
	
	$f['username'] = trim($f['username']);
	$f['password'] = trim($f['password']);
	if ($f['username'] == '' || $f['password'] == ''){
		$objResponse->addAlert($locate->Translate("Please enter the username and password"));
		return $objResponse->getXML();
	}

	if ($f['usertype'] == ''){
		$objResponse->addAlert($locate->Translate("Please select usertype"));
		return $objResponse->getXML();
	}

	if ( $f['resellerid'] == 0 && $f['usertype'] == 'reseller' ){
		$objResponse->addAlert($locate->Translate("Please choose a reseller"));
		return $objResponse->getXML();
	}

	if ( ($f['groupid'] == 0 || $f['resellerid'] == 0) && ($f['usertype'] == 'groupadmin' || $f['usertype'] == 'operator') ){
		$objResponse->addAlert($locate->Translate("Please choose reseller and group"));
		return $objResponse->getXML();
	}

	$id = astercrm::checkValues("account","username",$f['username']);

	if($id != ''){
		$objResponse->addAlert($locate->Translate("Username Duplicate"));
		return $objResponse->getXML();
	}
	
	$respOk = Customer::insertNewAccount($f); // add a new account
	if ($respOk){
		$html = createGrid(0,ROWSXPAGE);
		$objResponse->addAssign("grid", "innerHTML", $html);
		$objResponse->addAssign("msgZone", "innerHTML", $locate->Translate("add_account"));
		$objResponse->addAssign("formDiv", "style.visibility", "hidden");
		$objResponse->addClear("formDiv", "innerHTML");
	}else{
		$objResponse->addAssign("msgZone", "innerHTML", $locate->Translate("rec_cannot_insert"));
	}
	return $objResponse->getXML();
	
}

/**
*  update account record
*  @param	f			array		account record
*  @return	objResponse	object		xajax response object
*/

function update($f){
	global $locate,$db;
	$objResponse = new xajaxResponse();

	$f['username'] = trim($f['username']);
	$f['password'] = trim($f['password']);
	if ($f['username'] == '' || $f['password'] == ''){
		$objResponse->addAlert($locate->Translate("Please enter the username and password"));
		return $objResponse->getXML();
	}

	if ($f['usertype'] == ''){
		$objResponse->addAlert($locate->Translate("Please select usertype"));
		return $objResponse->getXML();
	}

	if ( $f['resellerid'] == 0 && $f['usertype'] == 'reseller' ){
		$objResponse->addAlert($locate->Translate("Please choose a reseller"));
		return $objResponse->getXML();
	}


	if ( ($f['groupid'] == 0 || $f['resellerid'] == 0) && ($f['usertype'] == 'groupadmin' || $f['usertype'] == 'operator') ){
		$objResponse->addAlert($locate->Translate("Please choose reseller and group"));
		return $objResponse->getXML();
	}

	$id = astercrm::checkValuesNon($f['id'],"account","username",$f['username']);

	if($id != ''){
		$objResponse->addAlert($locate->Translate("Username Duplicate"));
		return $objResponse->getXML();
	}

	$respOk = Customer::updateAccountRecord($f);

	if($respOk){
		$html = createGrid(0,ROWSXPAGE);
		$objResponse->addAssign("grid", "innerHTML", $html);
		$objResponse->addAssign("msgZone", "innerHTML", $locate->Translate("update_rec"));
		$objResponse->addAssign("formDiv", "style.visibility", "hidden");
	}else{
		$objResponse->addAssign("msgZone", "innerHTML", $locate->Translate("rec_cannot_update"));
	}
	
	return $objResponse->getXML();
}

/**
*  show account edit form
*  @param	id			int			account id
*  @return	objResponse	object		xajax response object
*/

function edit($id){
	global $locate;
	$html = Table::Top( $locate->Translate("edit_account"),"formDiv"); 
	$html .= Customer::formEdit($id);
	$html .= Table::Footer();
	// End edit zone

	$objResponse = new xajaxResponse();
	$objResponse->addAssign("formDiv", "style.visibility", "visible");
	$objResponse->addAssign("formDiv", "innerHTML", $html);
	return $objResponse->getXML();
}

/**
*  show account record detail
*  @param	accountid	int			account id
*  @return	objResponse	object		xajax response object
*/

function showDetail($accountid){
	$objResponse = new xajaxResponse();
	global $locate;
	$html = Table::Top( $locate->Translate("account_detail"),"formDiv"); 
	$html .= Customer::showAccountDetail($accountid);
	$html .= Table::Footer();

	$objResponse->addAssign("formDiv", "style.visibility", "visible");
	$objResponse->addAssign("formDiv", "innerHTML", $html);
	return $objResponse;
}

function setGroup($resellerid){
	global $locate;
	$objResponse = new xajaxResponse();
	$res = astercrm::getAll("accountgroup",'resellerid',$resellerid);
	$objResponse->addScript("addOption('groupid','0','');");
	//添加option
	while ($res->fetchInto($row)) {
		$objResponse->addScript("addOption('groupid','".$row['id']."','".$row['groupname']."');");
	}
	return $objResponse;
}


function searchFormSubmit($searchFormValue,$numRows,$limit,$id,$type){
	global $locate,$db;
	$objResponse = new xajaxResponse();
	$searchField = array();
	$searchContent = array();
	$optionFlag = $searchFormValue['optionFlag'];
	$searchContent = $searchFormValue['searchContent'];  //搜索内容 数组
	$searchField = $searchFormValue['searchField'];      //搜索条件 数组
	$searchType =  $searchFormValue['searchType'];			//搜索方式 数组
	$divName = "grid";
	if($optionFlag == "export"){
		$sql = astercrm::getSql($searchContent,$searchField,$searchType,'account'); //得到要导出的sql语句
		$_SESSION['export_sql'] = $sql;
		$objResponse->addAssign("hidSql", "value", $sql); //赋值隐含域
		$objResponse->addScript("document.getElementById('exportForm').submit();");
	}elseif($type == "delete"){
		$res = Customer::deleteRecord($id,'account');
		if ($res){
			$html = createGrid($searchFormValue['numRows'], $searchFormValue['limit'],$searchField, $searchContent, $searchField, $divName, "",$searchType);
			$objResponse = new xajaxResponse();
			$objResponse->addAssign("msgZone", "innerHTML", $locate->Translate("delete_rec")); 
			$objResponse->addClear("msgZone", "innerHTML");
			$objResponse->addAssign($divName, "innerHTML", $html);
		}else{
			$objResponse->addAssign("msgZone", "innerHTML", $locate->Translate("rec_cannot_delete")); 
		}
	}else{
		$html = createGrid($numRows, $limit,$searchField, $searchContent, $searchField, $divName, "",$searchType);
		$objResponse->addClear("msgZone", "innerHTML");
		$objResponse->addAssign($divName, "innerHTML", $html);
	}
	
	return $objResponse->getXML();
}

$xajax->processRequests();
?>
