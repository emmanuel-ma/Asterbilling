<?php
/*******************************************************************************
* manager_profile.server.php

* 配置管理系统后台文件
* manager profile background management script

* Function Desc
	provide manager profile management script


* Function Desc
		init				

* Revision 0.001  2014/11/19  last modified by ema
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

        $objResponse->addAssign("password","value","");
        $objResponse->addAssign("newpassword","value","");
        $objResponse->addAssign("confirmpassword","value","");
        $objResponse->addScript("xajax.$('password').focus();");
        
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
