<?php
/*******************************************************************************
********************************************************************************/
require_once ("forgot_login.common.php");
require_once ("db_connect.php");
require_once ('include/asterisk.class.php');
require_once ('include/astercrm.class.php');
require_once ('include/common.class.php');

/**
*  function to process form data
*	
*  	@param $aFormValues	(array)			login form data
															$aFormValues['username']
*	@return $objResponse
*/

function processForm($aFormValues)
{
	global $locate, $config;	

	$objResponse = new xajaxResponse();

	if ($config['system']['validcode'] == 'yes'){
		if (trim($aFormValues['code']) != $_SESSION["Checknum"]){
			$objResponse->addAlert('Invalid code');
			$objResponse->addScript('init();');
			return $objResponse;
		}
	}

	if (array_key_exists("email",$aFormValues))
	{
		if (filter_var($aFormValues['email'], FILTER_VALIDATE_EMAIL))
		{
		  // passed
			return processEmail($aFormValues['email']);
		}else{
		  // error
			$objResponse->addAlert($locate->Translate("invalid_email"));
			$objResponse->addScript('init();');
			return $objResponse;
		}

	} else{
		//$objResponse = new xajaxResponse();
		return $objResponse;
	}
}

/**
*  function to init forgot password page
*	
*  	@param $aFormValues	(array)			login form data
															$aFormValues['email']
*	@return $objResponse
*  @session

*  @global
															$locate
*/

function init($aFormValue){

	$objResponse = new xajaxResponse();
	
	global $locate,$config;

        if (isset($_COOKIE["language"])) {
		$language = $_COOKIE["language"];	
	}else{
		$language = "es_MX";
	}

        list($_SESSION['curuser']['language'],$_SESSION['curuser']['country']) = preg_split ("/_/", $language);	//get locate parameter

	$locate=new Localization($_SESSION['curuser']['language'],$_SESSION['curuser']['country'],'login');			//init localization class
	$objResponse->addAssign("titleDiv","innerHTML",$locate->Translate("forgot_login_title"));
	$objResponse->addAssign("emailDiv","innerHTML",$locate->Translate("email")."&nbsp;&nbsp;&nbsp;");
	$objResponse->addAssign("validcodeDiv","innerHTML",$locate->Translate("Valid Code")."&nbsp;&nbsp;&nbsp;");
	$objResponse->addAssign("loginButton","value",$locate->Translate("submit"));
	$objResponse->addAssign("loginButton","disabled",false);
	$objResponse->addAssign("onclickMsg","value",$locate->Translate("send_please_waiting"));	
	$objResponse->addScript("xajax.$('email').focus();");
	$objResponse->addScript("imgCode = new Image;imgCode.src = 'showimage.php';document.getElementById('imgCode').src = imgCode.src;");

	$objResponse->addAssign("divCopyright","innerHTML",Common::generateCopyright($skin));
        unset($_SESSION['curuser']);
        
	return $objResponse;
}


/**
*  function to verify and send email to user
*	
*  	@param $email	(string)			email

*	@return $objResponse
*  @session
*/
function processEmail($email)
{
	global $db,$config,$locate;
	
	$objResponse = new xajaxResponse();
	
	/* check whether the pear had been installed */
	$pear_exists_result = class_exists('PEAR');
	if(empty($pear_exists_result)) {
		$objResponse->addAlert($locate->Translate("Please install php pear"));
		$objResponse->addAssign("loginButton","value",$locate->Translate("submit"));
		$objResponse->addAssign("loginButton","disabled",false);
		return $objResponse;
	}

	$query = "SELECT * FROM account WHERE email='".$email."' LIMIT 1";
	$res = $db->query($query);
	if($res->fetchInto($f)){
		$to = $f['email'];
                $subject = $locate->Translate("Your forgot Username and Password").'...';
                $message = '
                    '.$locate->Translate("Hello").' '.$f['username'].',
                        
                    '.$locate->Translate("username").': '.$f['username'].'
                    '.$locate->Translate("password").': '.$f['password'].'
                            
                    '.$locate->Translate("Now you can login with this username and password").'.'.'
                        
                    '.$locate->Translate("SuMaTeL Team");
                
                if (!mail($to,$subject,$message)) {
                    $html = $locate->Translate("not sent email");
                    return $html;
                }
                
                $objResponse->addAlert($locate->Translate("login_sent_successfully"));
                $objResponse->addAssign("loginButton","value",$locate->Translate("submit"));
                $objResponse->addAssign("loginButton","disabled",false);
                $objResponse->addScript('window.location.href="index.php";');
	} else {
                $objResponse->addAlert($locate->Translate("your email is not registered for login"));
		$objResponse->addAssign("loginButton","value",$locate->Translate("submit"));
		$objResponse->addAssign("loginButton","disabled",false);
        }
        
        return $objResponse;
}

$xajax->processRequests();
?>