<?php
/*******************************************************************************
* system_config.server.php

* 配置管理系统后台文件
* system config background management script

* Function Desc
	system config management script


* Function Desc
		init				

* Revision 0.001  2014/12/01  last modified by ema
* Desc: page created
********************************************************************************/
require_once ("db_connect.php");
require_once ("manager_profile.common.php");
require_once ('include/astercrm.class.php');

/**
*  initialize page elements
*
*/

function init(){
	global $config,$locate;
	$objResponse = new xajaxResponse();
	
	$objResponse->addAssign("divNav","innerHTML",common::generateManageNav($skin,$_SESSION['curuser']['country'],$_SESSION['curuser']['language']));

        $objResponse->addAssign("expirydurationcertificates","value","");
        $objResponse->addAssign("newloginemailsubject","value","");
        $objResponse->addAssign("newloginemailmsg","value","");
        $objResponse->addAssign("forgotloginemailsubject", "value", "");
        $objResponse->addAssign("forgotloginemailmsg", "value", "");
        
        $objResponse->addScript("xajax.$('expirydurationcertificates').focus();");
        
	$objResponse->addAssign("divCopyright","innerHTML",common::generateCopyright($skin));

	return $objResponse;
}

function profileAction($aFormValues, $type){
	global $locate;
	$objResponse = new xajaxResponse();
        
	if($_SESSION['curuser']['usertype'] != 'operator' && $_SESSION['curuser']['usertype'] != 'supervisor' && $_SESSION['curuser']['usertype'] != 'hrsupervisor')
            return $objResponse;

	if($type == 'newpassword') {
            $f = astercrm::getRecordByField('id',$_SESSION['curuser']['userid'],'account');
            if ($aFormValues['password'] == "" || $aFormValues['password'] != $f['password']) {
                $objResponse->addAlert($locate->Translate("current_password_not_valid"));
                return $objResponse;
            }
            
            if ($aFormValues['newpassword'] == "" || $aFormValues['newpassword'] != $aFormValues['confirmpassword']) {
                $objResponse->addAlert($locate->Translate("new_password_confirmation_not_valid"));
                return $objResponse;
            }
            
            astercrm::updateField("account","password",$aFormValues['newpassword'],$_SESSION['curuser']['userid']);
            $objResponse->addAlert($locate->Translate("new_password_saved"));
            
            $objResponse->addScript('init();');
	}
        
	
	return $objResponse;
}

$xajax->processRequests();
?>
