<?php
/*******************************************************************************
* manager_profile.php

* 
* manager profile management interface

* Function Desc
	provide an manager profile management interface


* Page elements

* div:							
				divNav				show management function list
				divCopyright		show copyright

* javascript function:		
				init				page onload function			 

* Revision 0.001  2014/11/19  last modified by ema
* Desc: page created

********************************************************************************/

require_once('manager_profile.common.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<?php $xajax->printJavascript('include/');?>		
		<meta http-equiv="Content-Language" content="utf-8" />
		<SCRIPT LANGUAGE="JavaScript">
		<!--

			function init(){
				xajax_init();
				dragresize.apply(document);
			}			

			function profileAction(type){
                            var formValues = null;
                            
                            if ( type == 'newpassword') formValues = xajax.getFormValues("newpasswordForm");
                            
                            if ( formValues != null )
                                xajax_profileAction(formValues, type);
                        
                            return false;
			}

		//-->
		</SCRIPT>
		<script type="text/javascript" src="js/dragresize.js"></script>
		<script type="text/javascript" src="js/dragresizeInit.js"></script>
		<script type="text/javascript" src="js/astercrm.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<LINK href="skin/default/css/style.css" type=text/css rel=stylesheet>
		<LINK href="skin/default/css/dragresize.css" type=text/css rel=stylesheet>

	</head>
	<body onload="init();" id="system">
		<div id="divNav"></div><br>
<center>
    <div id="info"></div>
    <form id="newpasswordForm" action="javascript:void(null);" onsubmit="profileAction('newpassword');">
	<table border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#F0F0F0" width="650">
	  <tr>
		<td width="25%" height="39" class="td font" align="left">
			<?php echo $locate->Translate('Change Password');?>
		</td>
		<td width="75%" class="td font" align="center"><div id="divmsg"></div></td>
	  </tr>
	</table>
	<table border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#F0F0F0" id="menu" width="650"> 
            <tr bgcolor="#F7F7F7">
		<td width="25%" align="center" valign="center" height="30"><?php echo $locate->Translate('Current Password');?></td>
                <td  align="left" valign="center" height="30"><input type="password" name="password" id="password" style="width:150px;height:14px" maxlength="30" /></td>
            </tr>
            <tr bgcolor="#F7F7F7">
                <td width="25%" align="center" valign="center" height="30"><?php echo $locate->Translate('New Password');?></td>
                <td  align="left" valign="center" height="30"><input type="password" name="newpassword" id="newpassword" style="width:150px;height:14px" maxlength="30" /></td>
            </tr>
            <tr bgcolor="#F7F7F7">
		<td  align="center" valign="center" height="30"><?php echo $locate->Translate('Confirm New Password');?></td>
                <td align="left" valign="center" height="30" ><input type="password" name="confirmpassword" id="confirmpassword" style="width:150px;height:14px" maxlength="30" /></td>
            </tr>

            <tr bgcolor="#F7F7F7">
		<td  align="center" valign="center" height="30"></td>
		<td align="left" valign="center" height="30" ><input id="changeButton" name="changeButton" type="submit" value="<?php echo $locate->Translate('Change');?>"/></td>
            </tr>
	</table>
    </form>
</center>
		<div id="divCopyright"></div>
</body>
</html>