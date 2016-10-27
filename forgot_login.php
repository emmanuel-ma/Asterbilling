<?php
/*******************************************************************************
********************************************************************************/

require_once('forgot_login.common.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-Language" content="utf-8" />
		<?php $xajax->printJavascript('include/'); ?>
		<script type="text/javascript">
		/**
		*  login function, launched when user click login button
		*
		*  	@param null
		*	@return false
		*/
		function loginSignup()
		{
			xajax.$('loginButton').disabled=true;
			xajax.$('loginButton').value=xajax.$('onclickMsg').value;
			xajax_processForm(xajax.getFormValues("loginForm"));
			return false;
		}

		/**
		*  init function, launched after page load
		*
		*  	@param null
		*	@return false
		*/
		function init(){
			xajax_init(xajax.getFormValues("loginForm"));
			return false;
		}
		</script>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<LINK href="skin/default/css/style.css" type=text/css rel=stylesheet>

</head>
	<body onload="init();" style="margin-top: 80px;">
	 <div align="center">
	 		<div id="formDiv">
			<form id="loginForm" action="javascript:void(null);" onsubmit="loginSignup();">
                            <div class="login_in">
				<div id="titleDiv"></div>
				<div class="left">
					<table width="410" height="143" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<th width="70" height="58" scope="col">&nbsp;</th>
							<th width="" valign="bottom" scope="col">
								<div name="emailDiv" id="emailDiv" align="right"></div>
							</th>
							<th width="" valign="bottom" scope="col">
								<div align="left">
                                                                    <input name="email" type="email" id="email" style="width:150px;height:14px" />
								</div>
							</th>
						</tr>
						<tr>
							<td height="36">&nbsp;</td>
							<th><div name="validcodeDiv" id="validcodeDiv" align="right"></div></th>
							<td>
								<div align="left">
									<input type="text" name="code" id="code" style="width:50px;height:14px" />
								</div>
							</td>
						</tr>
						<tr>
							<td height="">&nbsp;</td>
							<th></th>
							<td><div align="left"><img id="imgCode" name="imgCode" src=""></div></td>
						</tr>
                                                <tr>
                                                    <td height="36">&nbsp;</td>
							<th></th>
							<td>
								<div align="left">
                                                                    <input id="loginButton" name="loginButton" type="submit" value=""/>
                                                                    <input id="onclickMsg" name="onclickMsg" type="hidden" value=""/>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="right">&nbsp;</div>
                                <div name="divCopyright" id="divCopyright" class="right"></div>
		  </div></form></div>
	    </div>
	</body>
</html>
