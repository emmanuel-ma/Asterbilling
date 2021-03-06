<?php
/*******************************************************************************
* checkout.php
* 结帐

* Function Desc

* javascript function:		

* Revision asterCC 0.01  2007/11/21 17:55:00  last modified by solo
* Desc: page created
********************************************************************************/

require_once('checkout.common.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<?php $xajax->printJavascript('include/'); ?>
		<meta http-equiv="Content-Language" content="utf-8" />
		<SCRIPT LANGUAGE="JavaScript">
		<!--
			
	function init(){
		curpeer = document.getElementById("hidCurpeer").value;
		xajax_init(curpeer);
		if (curpeer != ''){
			document.getElementById('listType').value = "listdetail";
		}
		listCDR();
	}

	function  addOption(objId,optionVal,optionText,optionSelected)  {
		objSelect = document.getElementById(objId);
		var _o = document.createElement("OPTION");
		_o.text = optionText;
		_o.value = optionVal;
		_o.selected = optionSelected;
	//	alert(objSelect.length);
		objSelect.options.add(_o);
	} 

	function listCDR(){
		document.getElementById("divMsg").style.visibility="visible";
		xajax_listCDR(xajax.getFormValues("frmFilter"));
	}

	function ckbOnClick(objCkb){
		var trId = "tr-" + objCkb.value;

		var oTotal = document.getElementById('spanTotal');
		var ofreeTotal = document.getElementById('spanrealTotal');
		var oCallshopCost = document.getElementById('spanCallshopCost');
		var oResellerCost = document.getElementById('spanResellerCost');

		var oPrice = document.getElementById("price-" + objCkb.value) ;
		var oCallshop = document.getElementById("callshop-" + objCkb.value) ;
		var oReseller = document.getElementById("reseller-" + objCkb.value) ;
		var ofree = document.getElementById("free-" + objCkb.value) ;

		var total = Float02(oTotal.innerHTML);
		var totalreal = Float02(ofreeTotal.innerHTML);
		var callshopcost = Float02(oCallshopCost.innerHTML);
		var resellercost = Float02(oResellerCost.innerHTML);

		var price  = Float02(oPrice.value);
		var callshop = Float02(oCallshop.value);
		var reseller = Float02(oReseller.value);

		if (objCkb.checked){
			document.getElementById(trId).style.backgroundColor="#eeeeee";
			total = total + price ;
			if(ofree.value == 'yes'){
               totalreal = totalreal + 0.00 ;
			}else{
               totalreal = totalreal + price ;
			}
			callshopcost = callshopcost + callshop;
			resellercost = resellercost + reseller;
		}else{
			total = total - price ;
			if(ofree.value == 'yes'){
               totalreal = totalreal - 0.00 ;
			   document.getElementById(trId).style.backgroundColor="#d5c59f";
			}else{
               totalreal = totalreal - price ;
			   document.getElementById(trId).style.backgroundColor="#ffffff";
			}
			callshopcost = callshopcost - callshop;
			resellercost = resellercost - reseller;
		}
		oTotal.innerHTML = Float02(total);
		ofreeTotal.innerHTML = Float02(totalreal);
		oCallshopCost.innerHTML = Float02(callshopcost);
		oResellerCost.innerHTML = Float02(resellercost);

		var currency;
		var currencyreal;

		currency = setCurrency(String(Float02(total)));
		currencyreal = setCurrency(String(Float02(totalreal)));
		document.getElementById('spanCurrencyTotal').innerHTML = '<?php echo $locate->Translate("should")?>:'+currency+'  <?php echo $locate->Translate("real")?>:'+currencyreal;

		currency = setCurrency(String(Float02(callshopcost)));
		document.getElementById('spanCurrencyCallshopCost').innerHTML = currency;

		currency = setCurrency(String(Float02(resellercost)));
		document.getElementById('spanCurrencyResellerCost').innerHTML = currency;
}

	function Float02(val)
	{
			return parseInt(val * 100 + 0.1)/100;
	}

	function ckbAllOnClick(objCkb){
		var ockb = document.getElementsByName('ckb[]');
		for(i=0;i<ockb.length;i++) {
			if (ockb[i].checked != objCkb.checked){
				ockb[i].checked = objCkb.checked;
				ckbOnClick(ockb[i]);
			}
		}

		var ockb = document.getElementsByName('ckbAll[]');
		for(i=0;i<ockb.length;i++) {
			ockb[i].checked = objCkb.checked;
		}
	}

	function setGroup(){
		var resellerid = xajax.$('resellerid').value;
		if (resellerid == '')
			return;
		//清空 groupid
		document.getElementById("groupid").options.length = 0;
		document.getElementById("sltBooth").options.length = 0;
                //document.getElementById("sltAccount").options.length = 0;

		if (resellerid != 0)
			xajax_setGroup(resellerid);
	}

	function setClid(){
		var groupid = xajax.$('groupid').value;
		if (groupid == '')
			return;
		//清空 clid
		document.getElementById("sltBooth").options.length = 0;
                document.getElementById("sltAccount").options.length = 0;
		if (groupid != 0)
			xajax_setClid(groupid);
	}

	//-->
		</SCRIPT>

		<script language="JavaScript" src="js/astercrm.js"></script>
		<script language="JavaScript" src="js/dhtmlgoodies_calendar.js"></script>
        <script type="text/javascript" src="openflash/js/swfobject.js"></script>
        <script type="text/javascript">


function actionFlash(resellerid,groupid,sltBooth,sltAccount,sdate,edate,listType,hidCurpeer){
	
//数量 Llamadas
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "num_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
    {"data-file":"checkout.server.flash.php?action=numV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );

// 计费时长 Tiempo Facturado
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "time_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
  {"data-file":"checkout.server.flash.php?action=timeV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );
  
//   合计 Cantidad
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "total_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
  {"data-file":"checkout.server.flash.php?action=totalV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );

//   分组 Caseta
<?php if ($_SESSION['curuser']['usertype'] == 'admin' || $_SESSION['curuser']['usertype'] == 'reseller' || $_SESSION['curuser']['usertype'] == 'groupadmin'){?>
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "group_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
  {"data-file":"checkout.server.flash.php?action=groupV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );
<?php }?>

//   代理商成本 Costo Distribuidor
<?php if ($_SESSION['curuser']['usertype'] == 'admin' || $_SESSION['curuser']['usertype'] == 'reseller'){?>
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "cost_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
  {"data-file":"checkout.server.flash.php?action=costV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );
<?php }?>
            
 //  利润 Beneficio
<?php if ($_SESSION['curuser']['usertype'] == 'admin' || $_SESSION['curuser']['usertype'] == 'reseller' || $_SESSION['curuser']['usertype'] == 'groupadmin'){?>
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "gain_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
  {"data-file":"checkout.server.flash.php?action=gainV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );
<?php }?>
}

function actionPie1(resellerid,groupid,sltBooth,sltAccount,sdate,edate,listType,hidCurpeer){
//数量 Llamadas
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "num_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
    {"data-file":"checkout.server.pie.php?action=recordNumV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );

// 计费时长 Tiempo facturado
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "time_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
  {"data-file":"checkout.server.pie.php?action=secondsV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );
  
//   合计 Cantidad
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "total_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
  {"data-file":"checkout.server.pie.php?action=creditV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );

//   分组 Caseta
<?php if ($_SESSION['curuser']['usertype'] == 'admin' || $_SESSION['curuser']['usertype'] == 'reseller' || $_SESSION['curuser']['usertype'] == 'groupadmin'){?>
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "group_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
  {"data-file":"checkout.server.pie.php?action=callshopcreditV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );
<?php }?>

//   代理商成本 Costo Distribuidor
<?php if ($_SESSION['curuser']['usertype'] == 'admin' || $_SESSION['curuser']['usertype'] == 'reseller'){?>
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "cost_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
  {"data-file":"checkout.server.pie.php?action=resellercreditV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );
<?php }?>

 //  利润 Beneficio
<?php if ($_SESSION['curuser']['usertype'] == 'admin' || $_SESSION['curuser']['usertype'] == 'reseller' || $_SESSION['curuser']['usertype'] == 'groupadmin'){?>
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "gain_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
  {"data-file":"checkout.server.pie.php?action=markupV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );
<?php }?>
}

function actionPie2(resellerid,groupid,sltBooth,sltAccount,sdate,edate,listType,hidCurpeer){
//数量 Llamadas
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "num_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
    {"data-file":"checkout.server.pie.php?action=recordNumV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );

// 计费时长 Tiempo Facturado
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "time_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
  {"data-file":"checkout.server.pie.php?action=secondsV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );
  
//   合计 Cantidad
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "total_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
  {"data-file":"checkout.server.pie.php?action=creditV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );

//   分组 Caseta
<?php if ($_SESSION['curuser']['usertype'] == 'admin' || $_SESSION['curuser']['usertype'] == 'reseller' || $_SESSION['curuser']['usertype'] == 'groupadmin'){?>
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "group_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
  {"data-file":"checkout.server.pie.php?action=callshopcreditV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );
<?php }?>

// Beneficio
<?php if ($_SESSION['curuser']['usertype'] == 'admin' || $_SESSION['curuser']['usertype'] == 'reseller' || $_SESSION['curuser']['usertype'] == 'groupadmin'){?>
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "gain_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
  {"data-file":"checkout.server.pie.php?action=callshopcreditV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );
<?php }?>
}

function actionPie3(resellerid,groupid,sltBooth,sltAccount,sdate,edate,listType,hidCurpeer){
//数量 Llamadas
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "num_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
    {"data-file":"checkout.server.pie.php?action=recordNumV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );

// 计费时长 Tiempo Facturado
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "time_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
  {"data-file":"checkout.server.pie.php?action=secondsV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );
  
//   合计 Cantidad
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "total_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
  {"data-file":"checkout.server.pie.php?action=creditV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );
}

function actionPieGroup(resellerid,groupid,sltBooth,sltAccount,sdate,edate,listType,hidCurpeer){
//数量 Llamadas
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "num_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
    {"data-file":"checkout.server.piegroup.php?action=recordNumV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );

// 计费时长 Tiempo Facturado
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "time_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
  {"data-file":"checkout.server.piegroup.php?action=secondsV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );
 
//   合计 Cantidad
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "total_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
  {"data-file":"checkout.server.piegroup.php?action=creditV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );

//   分组 Caseta
<?php if ($_SESSION['curuser']['usertype'] == 'admin' || $_SESSION['curuser']['usertype'] == 'reseller' || $_SESSION['curuser']['usertype'] == 'groupadmin'){?>
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "group_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
  {"data-file":"checkout.server.piegroup.php?action=callshopcreditV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );
<?php }?>

//   代理商成本 Costo Distribuidor
<?php if ($_SESSION['curuser']['usertype'] == 'admin' || $_SESSION['curuser']['usertype'] == 'reseller'){?>
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "cost_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
  {"data-file":"checkout.server.piegroup.php?action=resellercreditV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );
<?php }?>

 //  利润 Beneficio
<?php if ($_SESSION['curuser']['usertype'] == 'admin' || $_SESSION['curuser']['usertype'] == 'reseller' || $_SESSION['curuser']['usertype'] == 'groupadmin'){?>
swfobject.embedSWF(
  "openflash/open-flash-chart.swf", "gain_chart",
  "400", "300", "9.0.0", "expressInstall.swf",
  {"data-file":"checkout.server.piegroup.php?action=markupV"+resellerid+"V"+groupid+"V"+sltBooth+"V"+sltAccount+"V"+sdate+"V"+edate+"V"+listType+"V"+hidCurpeer} );
<?php }?>
}

function checkoutAll()
{
	if (!confirm("<?php echo $locate->Translate("Are you sure to clear all booth");?>"+"?'"))
	{
		return false;
	}
	var r = document.getElementById("resellerid").value;
	var g = document.getElementById("groupid").value;
	var c = document.getElementById("sltBooth").value;
	xajax_checkoutAll(r,g,c);
}

function listTypeChange() {
    var reportType = document.getElementById("listType").value;
    
    if ( reportType == 'listdetail' )
        document.getElementById("divCheckout").style.visibility = 'visible';
    else
        document.getElementById("divCheckout").style.visibility = 'hidden';
}
</script>

       
		<LINK href="skin/default/css/style.css" type=text/css rel=stylesheet>
		<LINK href="js/dhtmlgoodies_calendar.css" type=text/css rel=stylesheet>

	</head>

	<body onload="init();" id="report">
		<div id="divNav"></div>
		<br>
		<div id="divLimitStatus" name="divLimitStatus">
		</div>

		<form name="frmFilter" id="frmFilter" method="post">
		<div>
			<?php echo $locate->Translate("Reseller")?>: <SELECT id="resellerid" name="resellerid" onchange="setGroup();">
			</SELECT>

			<?php echo $locate->Translate("Callshop")?>: <SELECT id="groupid" name="groupid" onchange="setClid();">
			</SELECT>

			<?php echo $locate->Translate("Clid")?>: <select id="sltBooth" name="sltBooth" onchange="listCDR();">
			</select>
                        
                        <?php echo $locate->Translate("Account")?>: <select id="sltAccount" name="sltAccount" onchange="listCDR();">
			</select>
			<?php if($_SESSION['curuser']['usertype'] == 'admin'){
				echo '&nbsp;&nbsp;<a href="###" onclick="checkoutAll();">'.$locate->Translate("CheckOutAll").'</a>&nbsp';
			}?>
						<br>
                        <a href="###" onclick="xajax_speedDate('tye')"><?php echo $locate->Translate("Yesterday")?></a>&nbsp;|
			<a href="###" onclick="xajax_speedDate('td')"><?php echo $locate->Translate("Today")?></a>&nbsp;|
			<a href="###" onclick="xajax_speedDate('tw')"><?php echo $locate->Translate("This week")?></a>&nbsp;|
			<a href="###" onclick="xajax_speedDate('tm')"><?php echo $locate->Translate("This month")?></a>&nbsp;|
			<a href="###" onclick="xajax_speedDate('l3m')"><?php echo $locate->Translate("Last 3 months")?></a>&nbsp;|
			<a href="###" onclick="xajax_speedDate('ty')"><?php echo $locate->Translate("This year")?></a>&nbsp;|
			<a href="###" onclick="xajax_speedDate('ly')"><?php echo $locate->Translate("Last year")?></a>
			<br />
			<?php echo $locate->Translate("From")?>: <input type="text" name="sdate" id="sdate" size="20" value="<?php echo date("Y-m-d H:i",time()-86400);?>" >
			<INPUT onclick="displayCalendar(document.forms[0].sdate,'yyyy-mm-dd hh:ii',this,true)" type="button" value="<?php echo $locate->Translate("Cal")?>">
			<?php echo $locate->Translate("To")?>:<input type="text" name="edate" id="edate" size="20" value="<?php echo date("Y-m-d H:i",time());?>" >
			<INPUT onclick="displayCalendar(document.forms[0].edate,'yyyy-mm-dd hh:ii',this,true)" type="button" value="<?php echo $locate->Translate("Cal")?>">
			<?php echo $locate->Translate("Report")?>: <SELECT id="listType" name="listType" onchange="listTypeChange(); return false;">
                        <?php if ($_SESSION['curuser']['usertype'] == 'supervisor') { ?>
				<OPTION value="none"><?php echo $locate->Translate("None")?></OPTION>
				<OPTION value="listdetail"><?php echo $locate->Translate("List Detail")?></OPTION>
				<OPTION value="summonth"><?php echo $locate->Translate("Sum By Month")?></OPTION>
				<OPTION value="sumday"><?php echo $locate->Translate("Sum By Day")?></OPTION>
				<OPTION value="sumhour"><?php echo $locate->Translate("Sum By Hour")?></OPTION>
				<OPTION value="sumdest"><?php echo $locate->Translate("Sum By Destination")?></OPTION>
				<OPTION value="sumgroup"><?php echo $locate->Translate("Sum By Group")?></OPTION>
                                <OPTION value="sumaccount"><?php echo $locate->Translate("Sum By Account")?></OPTION>
                        <?php }elseif ($_SESSION['curuser']['usertype'] != 'hrsupervisor') { ?>
				<OPTION value="none"><?php echo $locate->Translate("None")?></OPTION>
				<OPTION value="listdetail"><?php echo $locate->Translate("List Detail")?></OPTION>
				<OPTION value="sumyear"><?php echo $locate->Translate("Sum By Year")?></OPTION>
				<OPTION value="summonth"><?php echo $locate->Translate("Sum By Month")?></OPTION>
				<OPTION value="sumday"><?php echo $locate->Translate("Sum By Day")?></OPTION>
				<OPTION value="sumhour"><?php echo $locate->Translate("Sum By Hour")?></OPTION>
				<OPTION value="sumdest"><?php echo $locate->Translate("Sum By Destination")?></OPTION>
				<OPTION value="sumgroup"><?php echo $locate->Translate("Sum By Group")?></OPTION>
                                <OPTION value="sumaccount"><?php echo $locate->Translate("Sum By Account")?></OPTION>
                        <?php } ?>
                                <OPTION value="accountlog-account"><?php echo $locate->Translate("AccountLog Account")?></OPTION>
                                <OPTION value="accountlog-group"><?php echo $locate->Translate("AccountLog Group")?></OPTION>
			</SELECT>
			<br>
			<?php echo $locate->Translate("Report Type")?>: <input type="radio" value="text" name="reporttype" checked><?php echo $locate->Translate("Text")?>
			<input type="radio" value="flash" name="reporttype"><?php echo $locate->Translate("Flash")?>
                        <br/>
			<input type="button" onclick="listCDR();return false;" value="<?php echo $locate->Translate("List")?>">
			<input type="hidden" id="hidCurpeer" name="hidCurpeer" value="<?php echo $_REQUEST['peer']?>">
		</div>
		</form>
		<div id="divSum"></div>
		<div id="divUnbilledList" name="divUnbilledList">
		</div>
		
		<center>
                <div id='divCheckout'>
			<div style="overflow:hidden; zoom:1; margin:auto; width:830px;">
				<div class="jin-fl"><div id='num_chart'></div></div>
				<div class="jin-fl"><div id='time_chart'></div></div>
				<div class="jin-fl"><div id='total_chart'></div></div>
				<div class="jin-fl"><div id='group_chart'></div></div>
				<div class="jin-fl"><div id='cost_chart'></div></div>
				<div class="jin-fl"><div id='gain_chart'></div></div>
			</div>
			<div style="display:none;">
				<?php echo $locate->Translate("Amount")?>: <span id="spanTotal" name="spanTotal">0</span> <span id="spanrealTotal" name="spanrealTotal">0</span>
				<?php echo $locate->Translate("Callshop Cost")?>: <span id="spanCallshopCost" name="spanCallshopCost">0</span>
				<?php echo $locate->Translate("Reseller Cost")?>: <span id="spanResellerCost" name="spanResellerCost">0</span>
			</div>
			<?php echo $locate->Translate("Amount")?>: <span id="spanCurrencyTotal" name="spanCurrencyTotal">0</span><br />
			<?php echo $locate->Translate("Callshop Cost")?>: <span id="spanCurrencyCallshopCost" name="spanCurrencyCallshopCost">0</span><br />
			<?php echo $locate->Translate("Reseller Cost")?>: <span id="spanCurrencyResellerCost" name="spanCurrencyResellerCost">0</span><br />
			<input type="button" value="<?php echo $locate->Translate("Check Out")?>" name="btnCheckOut" id="btnCheckOut" onclick="xajax_checkOut(xajax.getFormValues('f'));">
		</div>
		</center>
	<div id="divMsg">
		<?php echo $locate->Translate("Processing please wait")?> ...
	</div>
	<div id="divCopyright"></div>
	</body>
</html>
