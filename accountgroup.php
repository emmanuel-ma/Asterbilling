<?php
/*******************************************************************************
* accountgroup.php

* 账户管理界面文件
* accountgroup management interface

* Function Desc
	provide an accountgroup management interface

* 功能描述
	提供帐户管理界面

* Page elements

* div:							
				divNav				show management function list
				formDiv				show add/edit accountgroup form
				grid				show accout grid
				msgZone				show action result
				divCopyright		show copyright

* javascript function:		

				init				page onload function			 


* Revision 0.045  2007/10/18 11:44:00  last modified by solo
* Desc: page created

********************************************************************************/

require_once('accountgroup.common.php');

include 'WebClientPrint.php';
use Neodynamic\SDK\Web\WebClientPrint;
use Neodynamic\SDK\Web\Utils;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<?php $xajax->printJavascript('include/'); ?>
		<meta http-equiv="Content-Language" content="utf-8" />
		<SCRIPT LANGUAGE="JavaScript">
		<!--

			function init(){
				xajax_init();
				dragresize.apply(document);
			}

		function searchFormSubmit(numRows,limit,id,type){
			//alert(xajax.getFormValues("searchForm"));
			xajax_searchFormSubmit(xajax.getFormValues("searchForm"),numRows,limit,id,type);
			return false;
		}

		function showComment(obj){
			var tval = obj.value;
			if(tval == "add" || tval== "reduce"){
				xajax.$("creditmod").disabled = false;
				xajax.$("comment").disabled = false;
			}else{
				xajax.$("creditmod").disabled = true;
				xajax.$("comment").disabled = true;
			}
		}

                function printTestReceipt(formId) {
                    
                    xajax.$("printerCommands").value = '0x1B0x210x00' + xajax.$('grouptitle').value
                                    +'0x0A' + xajax.$('grouptagline').value
                                    +'0x0A0x0A<?php echo $locate->Translate("Operator");?>: '
                                    +'0x0A0x0A<?php echo $locate->Translate("Phone");?>      <?php echo $locate->Translate("Billsec");?>  <?php echo $locate->Translate("Price");?>'
                                    +'0x0A999999999999  00:00:00  0.00'
                                    +'0x0A0x0A<?php echo $locate->Translate("Discount");?>: $0.00'
                                    +'0x0A<?php echo $locate->Translate("Total");?>: $0.00'
                                    +'0x0A0x0A<?php echo date("Y-m-d H:i:s");?>0x0A0x1D0x560x420x00';
                    doClientPrint(formId);
                }
		//-->
		</SCRIPT>
		<script type="text/javascript" src="js/dragresize.js"></script>
		<script type="text/javascript" src="js/dragresizeInit.js"></script>
		<script type="text/javascript" src="js/astercrm.js"></script>
		<LINK href="skin/default/css/style.css" type=text/css rel=stylesheet>
		<LINK href="skin/default/css/dragresize.css" type=text/css rel=stylesheet>

	</head>
	<body onload="init();" id="accountgroup">
		<div id="divNav"></div><br>
		<table width="100%" border="0" style="background: #F9F9F9; padding: 0px;">
			<tr>
				<td style="padding: 0px;">
					<fieldset>
			<div id="formDiv"  class="formDiv drsElement" 
				style="left: 450px; top: 50px;width:500px;"></div>
			<div id="grid" name="grid" align="center"> </div>
			<div id="msgZone" name="msgZone" align="left"> </div>
					</fieldset>
				</td>
			</tr>
		</table>
		<iframe name="iframeForUpload" id="iframeForUpload" width="0" height="0" scrolling="no"></iframe>
		<form name="exportForm" id="exportForm" action="dataexport.php" >
			<input type="hidden" value="" id="hidSql" name="hidSql" />
		</form>
		<div id="divCopyright"></div>
                
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/PrintCommands.js"></script>
<script type="text/javascript">
                var wcppGetPrintersDelay_ms = 5000; //5 sec

                function wcpGetPrintersOnSuccess(){
                    if(arguments[0].length > 0){
                        var p=arguments[0].split("|");
                        var options = '';
                        for (var i = 0; i < p.length; i++) {
                            options += '<option>' + p[i] + '</option>';
                        }
                        $('#installedPrinterName').html(options);
                        $('#installedPrinterName').focus();
                        //$('#loadPrinters').hide();
                    }else{
                        alert("<?php echo $locate->Translate("No printers are installed in your system");?>");
                    }
                }

                function wcpGetPrintersOnFailure() {
                    alert("<?php echo $locate->Translate("No printers are installed in your system");?>");
                }
</script>
<script type="text/javascript">
    var globalDialog;
  $(function() {
    var dialog, form,
 
      installedPrinterName = $( "#installedPrinterName" ),
      allFields = $( [] ).add( installedPrinterName ),
      tips = $( ".validateTips" );
 
    function updateTips( t ) {
      tips
        .text( t )
        .addClass( "ui-state-highlight" );
      setTimeout(function() {
        tips.removeClass( "ui-state-highlight", 1500 );
      }, 500 );
    }
 
    function checkLength( o, n, min, max ) {
      if ( o.val() == null || (o.val().length > max || o.val().length < min) ) {
        o.addClass( "ui-state-error" );
        updateTips( "<?php echo $locate->Translate("Length of");?> " + n + " <?php echo $locate->Translate("must be between");?> " +
          min + " <?php echo $locate->Translate("and");?> " + max + " <?php echo $locate->Translate("characters");?>." );
        return false;
      } else {
        return true;
      }
    }
  
    function selectPrinter() {
      var valid = true;
      allFields.removeClass( "ui-state-error" );
 
      valid = valid && checkLength( installedPrinterName, "<?php echo $locate->Translate("Printer Name");?>", 1, 50 );
 
      if ( valid ) {
        $( "#receipt_printer" ).val( installedPrinterName.val() );
        dialog.dialog( "close" );
      }
      return valid;
    }
 
    dialog = $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 250,
      width: 350,
      modal: true,
      buttons: {
        "<?php echo $locate->Translate("Select");?>": selectPrinter,
        <?php echo $locate->Translate("Cancel");?>: function() {
          dialog.dialog( "close" );
        }
      },
      close: function() {
        form[ 0 ].reset();
        allFields.removeClass( "ui-state-error" );
      }
    });
 
    form = dialog.find( "form" ).on( "submit", function( event ) {
      event.preventDefault();
      selectPrinter();
    });
 
    /*$( "#select-printer" ).button().on( "click", function() {
      alert('OK');
      dialog.dialog( "open" );
      jsWebClientPrint.getPrinters();
    });*/
    
    globalDialog = dialog;
  });
  </script>

﻿<div id="dialog-form" title="<?php echo $locate->Translate("Select a printer");?>">
  <p class="validateTips"><?php echo $locate->Translate("Must select a printer");?></p>
 
  <form>
    <fieldset>
      <label for="installedPrinterName">Name</label>
      <select type="text" name="installedPrinterName" id="installedPrinterName" class="text ui-widget-content ui-corner-all"></select>
 
      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
    </fieldset>
  </form>
</div>

<?php
    $currentFileName = basename($_SERVER['PHP_SELF']);
    // REQUEST_URI tambien contempla los argumentos que se le hayan pasado al archivo php
    //$currentFolder = substr($_SERVER['REQUEST_URI'], 0, strlen($_SERVER['REQUEST_URI']) - strlen($currentFileName));
    $currentFolder = substr($_SERVER['SCRIPT_NAME'], 0, strlen($_SERVER['SCRIPT_NAME']) - strlen($currentFileName));
    //Specify the ABSOLUTE URL to the php file that will create the ClientPrintJob object
    echo WebClientPrint::createScript(Utils::getRoot().$currentFolder.'PrintCommandsProcess.php');
?>
	</body>
</html>
