<?
session_start();
include_once($DOCUMENT_ROOT . "/Group-Office.php");
$simple=1;
$page_subtitle = $lang['salary_calculation'];

include("include/common.php");
	
if (!loginCheck('User'))exit;
global $action, $id, $cur_page, $lang, $conn, $config;

require("include/functions.php");
include("$config[template_path]/admin_top.html");


if (!$sDate){
	$firstday = mktime(0,0,0,date("m"),1,date("Y"));
	$sDate = date("d/m/Y",strtotime("+0 day",$firstday));
	$eDate = date("d/m/Y",strtotime("-1 day",strtotime("+ 1 month",$firstday)));
}

$asDate = explode("/",$sDate);
$aeDate = explode("/",$eDate);

$startdate = mktime(0,0,0,$asDate[1],$asDate[0],$asDate[2]);
$enddate = mktime(23,59,59,$aeDate[1],$aeDate[0],$aeDate[2]);
$startDay = date("Y/m/d",$startdate);
$endDay = date("Y/m/d",$enddate);

$conn->Execute('SET NAMES "utf8"');
$sql="select * from workers where user_ID = $userID and workernum='$num'";
$worker = $conn->Execute($sql);
if ($worker === false){log_error($sql);}
if ($worker->EOF){
	$name=$lang['num_worked']." ".$num;
}
else{
	$name=$worker->fields["WorkerName"]." ".$worker->fields["WorkerSurName"];
}

	
if ($saction=="go")
{
	calcSalary($num,$sDate,$eDate,0,$hSalary);
	echo"<script>window.opener.location=window.opener.location.href</script>";
	
}	
	
	?>

	<body>
	<script>
	function validate(){
	
		if (document.getElementById("MSG")!=null){
				document.getElementById("MSG").style.display="none";
		}
		if(document.F.sDate.value==""/*||isNaN(Date.parse(document.F.sDate.value))*/){
			alert("<?=$lang['invalid_value']?>"+Date.parse(document.F.sDate.value));
			document.F.sDate.focus();
			return false;
		}
		if(document.F.eDate.value==""/*||isNaN(Date.parse(document.F.eDate.value))*/){
			alert("<?=$lang['invalid_value']?>"+Date.parse(document.F.eDate.value));
			document.F.eDate.focus();
			return false;
		}
		if(document.F.hSalary.value==""||isNaN(document.F.hSalary.value)){
			alert("<?=$lang['invalid_value']?>"+document.F.hSalary.value);
			document.F.hSalary.focus();
			return false;
		}
		if(confirm("<?=$lang['do_you_want_to_re-calculate_salary']?>")){
			return true;
		}
		return false;
	}
	</script>
	<form name=F method=post onsubmit='return validate()'>
		
	<table cellpadding=5 border=0 width=100%>
	<tr>
	<td align=center nowrap>
	
	
	<?$reporttitle = $lang['salary_calculation'] ." ".$name;?>
	<?if ($usr){$reporttitle.="<br>".$username;}?>
	<strong style='font-size:10pt'><?=$reporttitle?></strong>
	</td></tr><tr></tr><td width=100% align=center>

<table>
	<tr>
		<td Align=left><?=$lang['from_date']?>:</td>
		<td>&nbsp;<input size=7 name=sDate id=sDate value="<?=$sDate?>" class="tcal">
		<!-- img align=absmiddle style="cursor:hand" src='<?=$imgPath?>calendar.gif' onclick='ShowCalendar1("F.sDate")' --></td>
	</tr>
	<tr>
	<td Align=left><?=$lang['date_till']?>:</td>
	<td>&nbsp;<input size=7 name=eDate id=eDate value="<?=$eDate?>" class="tcal">
	<!-- img align=absmiddle style="cursor:hand" src='<?=$imgPath?>calendar.gif' onclick='ShowCalendar1("F.eDate")' --></td>
	</tr>
	<tr>
		 <td Align=left><?=$lang['according_to_hourly_wages']?>:</td>
		 <td>&nbsp;<Input name=hSalary size=2 value="<?=($hSalary)?$hSalary:$worker->fields["HourSalary"]?>"><?=$lang['nis']?></td>
	</tr>
</table>

	<hr>
	<input type=submit value="<?=$lang['submit']?>" style="cursor:hand;padding:0 0 0 10;background-image:url(<?=$imgPath?>refresh.gif);background-repeat:no-repeat;background-position:left top" >
	<input type=button onclick='window.close()' value=' <?=$lang['close_button']?> ' style="cursor:hand;padding:0 0 0 0;background-image:url(<?=$imgPath?>refresh1.gif);background-repeat:no-repeat;background-position:left top">
	<input type=hidden name=saction value=go>
	<?if ($saction=="go"){?>
	<br><b ID=MSG style=color:green><?=$lang['calculation_was_successful']?></b>
	<?}?>
	</td>

	</tr>
	</table>
	</form>
<?
	include("$config[template_path]/admin_bottom.html");
	$conn->Close(); // close the db connection
	
?>