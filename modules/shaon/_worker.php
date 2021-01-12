<?
	session_start();
	
	$simple=1;
	$page_subtitle = "דו\"ח שעות עבודה";
	$xlsfilename="ShaotNochechut_".$num;
	
	include("include/common.php");
	if (!loginCheck('User'))exit;
	global $action, $id, $cur_page, $lang, $conn, $config;
	
	require("include/functions.php");
    require("../stock/include/functions.php"); 
	require("$config[template_path]/admin_top.html");

	if ($_GET["sDate"] && $saction != "sendreport"){
		$saction="go";
	}
	
 
	if (!$sDate)
    {
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
	
	$sql="select WorkingHoursPerDay,WorkingHoursFriday from $TABLE_USERDATA where username='$username'";
	$userdata = $conn->Execute($sql);
	if ($userdata === false){log_error($sql);}
	$WorkingHoursPerDay=$userdata->fields["WorkingHoursPerDay"];
	$WorkingHoursFriday=$userdata->fields["WorkingHoursFriday"];
		
	if ($stock){
		$sql="select * from $TABLE_LISTINGSSTOCKS where id=$stock";
		$stock = $conn->Execute($sql);
		if ($stock === false){log_error($sql);}
		$stockname=$stock->fields["StockName"];
	}
	else{
		$stockname="";
	}
	DBQuery("SET NAMES 'utf8'");
	$stocks = DBQuery("select * from $TABLE_LISTINGSSTOCKS where Status=1 and user_id = $userID order by binary StockName");
		
	$sql="select * from workers where user_ID = $userID and workernum=$num";
	$worker = DBQuery($sql);
	$name=$worker->fields["WorkerName"]." ".$worker->fields["WorkerSurName"];
	$workerid=$worker->fields["ID"];
	
	
	if ($newDate)
    {
		$DateSQL=DateToSQL($newDate);
        
		$sql="INSERT INTO attendance_original (manual,terminal_id, worker_id, worker_num, day, entertime, exittime, recordclosednormally, istwodates, entermanual, enterclosedabnormally, exitmanual, exitclosedabnormally, user_id,timestamp,valid)
		VALUES (1,$sstock, '$workerid', $num, '$DateSQL', '$newEnterTime', '$newExitTime', 1, 0, 1, 0, 1, 0, $userID,unix_timestamp(),1)";
		$new = DBQuery($sql);
		$newid = $conn->Insert_ID();	

		$sql="INSERT INTO attendance (hour_salary,manual,id,terminal_id, worker_id, worker_num, day, entertime, exittime, recordclosednormally, istwodates, entermanual, enterclosedabnormally, exitmanual, exitclosedabnormally, user_id,timestamp,valid)
		VALUES ('$hSalary',1,$newid ,$sstock, '$workerid', $num, '$DateSQL', '$newEnterTime', '$newExitTime', 1, 0, 1, 0, 1, 0, $userID,unix_timestamp(),1)";
		$new = DBQuery($sql);

		$sql="delete from attendance_original where id=$newid";
		$new = DBQuery($sql);

		calcSalary($num,$sDate,$eDate,0,"");
		echo"<script>window.opener.location=window.opener.location.href</script>";
		echo "<script>window.location=window.location.href;</script>";

	}
    if ($deleterow){
			$sql="delete from attendance where id=".$deleterow. " and user_id = $userID";
			$upd = $conn->Execute($sql);
			if ($upd === false){log_error($sql);}
            echo"<script>window.opener.location=window.opener.location.href</script>";
    }

	if($_POST["UPDATE"]){
		$i=0;
		foreach($_POST["entertime"] as $time){
			
			$atime=explode("#",$time);
			
			$sql="update attendance set entertime=(case when '".$atime[0]."' = '' then null else '".$atime[0]."' end) ,entertime_updated=now() where (entertime is null or entertime<>'".$atime[0]."') and id=".$atime[1];
			//echo $sql;
			$upd = $conn->Execute($sql);
			if ($upd === false){log_error($sql);}
	
			$sql="update attendance set valid=(case when entertime is not null and exittime is not null and entertime<exittime then 1 else 0 end) where id=".$atime[1];
			$upd = $conn->Execute($sql);
			if ($upd === false){log_error($sql);}

			$i++;
		}
		
		$i=0;
		foreach($_POST["exittime"] as $time){
			
			$atime=explode("#",$time);
			
			$sql="update attendance set exittime='".$atime[0]."',exittime_updated=now() where (exittime is null or exittime<>'".$atime[0]."') and id=".$atime[1];
			$upd = $conn->Execute($sql);
			if ($upd === false){log_error($sql);}
			
			$sql="update attendance set valid=(case when entertime is not null and exittime is not null and entertime<exittime then 1 else 0 end) where id=".$atime[1];
			$upd = $conn->Execute($sql);
			if ($upd === false){log_error($sql);}

			$i++;
		}
		
		calcSalary($num,$sDate,$eDate,"");
		echo"<script>window.opener.location=window.opener.location.href</script>";		
	} 
	
	
	?>
	<style>
	.z {color:gray}
	.b {font-weight:bold}
	th {background-color:silver}
	</style>
	<script>
	function edit(id,time,mode){
		el = window.event.srcElement;
		//newInput = document.createElement("<input name=entertime[] value='"+time+"'>");
		el.style.padding=0;
		el.innerHTML = "<input timeid="+id+" id='I_"+mode+"_"+id+"' onclick='window.event.cancelBubble=true' dir=ltr style='text-align:right;border:inset 1;background-color:lightyellow;height:19;width:100%' name="+mode+"[] value='"+time+"'>";
		document.getElementById("I_"+mode+"_"+id).focus();
		document.getElementById("UPDATE").style.display="";
		document.getElementById("INFO").style.display="none";
	}
	
	function validate(){	
		for (i=0;i<document.F.elements.length;i++)
		{
			if (document.F.elements[i].timeid+""!="undefined")
			{
				if (document.F.elements[i].value!="" && !isTime(document.F.elements[i].value))
				{
					alert("ערך שהוזן אינו תקין");
					document.F.elements[i].focus();
					return false;
				}
			}
		}
		return true;
	}
	
	function updateids(){	
		if (!validate()){
			return false;
		}
                debugger;
		for (i=0;i<document.F.elements.length;i++)
		{
			if (document.F.elements[i].getAttribute("timeid")!=null)
			{
				document.F.elements[i].style.display="none";
				document.F.elements[i].parentNode.style.padding="2px";
				document.F.elements[i].parentNode.innerHTML=document.F.elements[i].parentNode.innerHTML+" "+document.F.elements[i].value;
				document.F.elements[i].value+="#"+document.F.elements[i].getAttribute("timeid");
			}
		}
		return true;
	}
	
	function isTime(b) 
	{
		var t_array = b.split(":");
		
		var hour = t_array[0]*1;
		var mins = t_array[1]*1;
		
		return(t_array.length==2 && hour >= 0 && hour <= 23 && mins >= 0 && mins <= 59);
	}

	
	function PrintReport(){
		var s = document.getElementById("INFO").style.display;
		var s1 = document.getElementById("UPDATE").style.display;
		var s2 = document.getElementById("ADDROW").style.display;
		document.getElementById("Query").style.display = "none";
		document.getElementById("INFO").style.display = "none";
		document.getElementById("UPDATE").style.display = "none";
		document.getElementById("ADDROW").style.display = "none";
		document.getElementById("IDKUN").style.display = "none";
		window.print();
		document.getElementById("IDKUN").style.display = "";
		document.getElementById("Query").style.display = "";
		document.getElementById("INFO").style.display = s;
		document.getElementById("UPDATE").style.display = s1;
		document.getElementById("ADDROW").style.display = s2;
	}
	
	function addRow(){
		if(isNaN(Date.parse(document.F.newDate.value))){
			alert("יש להזין תאריך");
			document.F.newDate.focus();
			return;
		}
		if(document.F.newEnterTime.value==""){
			alert("יש להזין זמן כניסה");
			document.F.newEnterTime.focus();
			return;
		}
		if(document.F.newExitTime.value==""){
			alert("יש להזין זמן יציאה");
			document.F.newExitTime.focus();
			return;
		}
		if(!isTime(document.F.newEnterTime.value)){
			alert("ערך שהוזן אינו תקין");
			document.F.newEnterTime.focus();
			return;
		}
		if(!isTime(document.F.newExitTime.value)){
			alert("ערך שהוזן אינו תקין");
			document.F.newExitTime.focus();
			return;
		}
		document.F.submit();
	}
	
	function cancelRow(){
		document.F.newExitTime.value="";
		document.F.newEnterTime.value="";
		document.F.newDate.value="";
		document.getElementById("ADD").style.display="none";
	}
	
	</script>
	<body>
	<form name=F method=post>
	<div id=ADD style='display:none;position:absolute;top:50;left:300;width:200;height:100;background-color:lightyellow;border:outset 2'>
		<table width=100%>
		<tr>
			<td>
			נקודה
			</td>
			<td>
				<select name=sstock>
					<?while(!$stocks->EOF){?>
						<option value="<?=$stocks->fields["ID"]?>" <?=((($sstock)?$sstock:$stock)==$stocks->fields["ID"])?"selected":""?>><?=$stocks->fields["StockName"]?>
						<?$stocks->MoveNext();
					}?>
					</select>
				</td>
		</tr>
		<tr>
			<td>תאריך</td>
			<td width=1% nowrap>
				<input size=7 name=newDate id=newDate value="" class="tcal">
				<!-- img align=absmiddle style="cursor:hand" src='<?=$imgPath?>calendar.gif' onclick='ShowCalendar1("F.newDate")' -->
			</td>
		</tr>
		<tr>
			<td>זמן כניסה</td>
			<td>
				<input size=6 name=newEnterTime id=newEnterTime value="">
			</td>
		</tr>
		<tr>
			<td>זמן יציאה</td>
			<td>
				<input size=6 name=newExitTime id=newExitTime value="">
			</td>
		</tr>
		<tr>
		<tr>
			<td>שכר שעה</td>
			<td>
				<input size=6 name=hSalary id=hSalary value="<?=($hSalary)?$hSalary:$worker->fields["HourSalary"]?>">
			</td>
		</tr>
		<tr>	
		<td colspan=2 align=center>
		<hr noshade size=1>
			<input type=button value='אישור' onclick=addRow()>
			<input type=button value='ביטול' onclick='cancelRow()'>
		</td>
		</tr>
		</table>
	</div>
	
	<table cellpadding=5 border=0 width=100%>
	<tr>

	
	<input type=hidden name=usr value=<?=$usr?>>
	<input type=hidden name=rmodule value=<?=$rmodule?>>
	<td nowrap>
	
	
	<?
	$reporttitle = " דו\"ח שעות עבודה " ."<br>".$name."<br>".$stockname;
	$mailreporttitle = " דו\"ח שעות עבודה " .$name." ".$stockname;
	?>
	<?if ($usr){$reporttitle.="<br>".$username;}?>
	<strong style='font-size:12pt'><?=$reporttitle?></strong>
	<?	$page_subtitle = strip_tags($reporttitle); ?>
	<a id=IDKUN onclick='event.cancelBubble=true;' href="javascript:wopen('cp/main.php?service=workers&bd_event=edit_record&record_id=<?=$workerid?></a>','edit')">עדכון פרטי עובד</a>
	</td><td width=99% style='border:inset 1'>
			<table id=INFO <?=($UPDATE)?"style=display:none":""?> align=left><tr><td bgcolor=lightyellow align=center style='padding:0 5;border:solid 1 black;color:blue'><strong>לחץ\י על משבצת 
			<br>
			לעדכון זמן</strong></td></tr></table>

	<?=$lang["from"]?> <input size=7 name=sDate id=sDate value="<?=$sDate?>" class="tcal">
<!-- img align=absmiddle style="cursor:hand" src='<?=$imgPath?>calendar.gif' onclick='ShowCalendar("F.sDate")
'-->
 <?=$lang["to"]?> <input size=7 name=eDate id=eDate value="<?=$eDate?>" class="tcal">
<!--img align=absmiddle style="cursor:hand" src='<?=$imgPath?>calendar.gif' onclick='ShowCalendar("F.eDate")
'-->
 
	

		
	<input type=submit value=" <?=$lang["show"]?> " style=";cursor:hand;padding:0 0 0 10;background-image:url(<?=$imgPath?>refresh.gif);background-repeat:no-repeat;background-position:left top" >
	<input type=button onclick='<?="s=window.open(\"recalc.php?num=$num&sDate=$sDate&eDate=$eDate\",\"recalc\",\"top=100,left=100,width=300,height=200\");s.focus();"?>' value=" חשב שכר " style=";cursor:hand;padding:0 0 0 10;background-image:url(<?=$imgPath?>refresh.gif);background-repeat:no-repeat;background-position:left top" >
	
	<input type=button id=ADDROW onclick='document.getElementById("ADD").style.display=""' value='הוסף שורה' style=';width:100;PADDING-RIGHT: 0px; PADDING-LEFT: 10px;background-image: url(<?=$imgPath?>plus.gif) no-repeat left top;PADDING-BOTTOM: 0px; CURSOR: hand; PADDING-TOP: 0px'>
	<input type=submit id=UPDATE name=UPDATE onclick='return updateids()' value='עדכן' style='display:none;width:100;PADDING-RIGHT: 0px; PADDING-LEFT: 10px;background-image: url(<?=$imgPath?>refresh.gif) no-repeat left top; BACKGROUND-color:lightgreen;PADDING-BOTTOM: 0px; CURSOR: hand; PADDING-TOP: 0px'>
	<?if ($saction=="go" || $saction=="sendreport"){
		require("sendreport.php");
	}?>
	<input type=hidden name=saction value=go>
	<input type=hidden name=reportbody value="">
	<input type=hidden name=sendmode value="">

	</td>

	</tr>
	</table>
	<?	
	if ($saction=="sendreport"){
		$rbody = strip_tags(stripslashes($reportbody),"<table><tr><td><th><b>");
		sendReport($mailreporttitle,$rbody,$sendmode);
		echo "<center><strong style='color:green'>".$lang["report_sent"]."</strong></center>";
		echo stripslashes($reportbody);
	}
	elseif ($saction=="go"){
	
	$sDateSQL=substr($sDate,6,4)."-".substr($sDate,3,2)."-".substr($sDate,0,2);
	$eDateSQL=substr($eDate,6,4)."-".substr($eDate,3,2)."-".substr($eDate,0,2);
	
	if (!$sort){	
	
			$q="";
			if ($_GET["stock"]){
				$q=" and terminal_id =  ".$_GET["stock"];
			}
	
			$sql = "
			SELECT 
			a.id,
			a.day,
            a.manual,
			sabbat,
			valid,
			h.name as holiday_name,
			h1.name as erev_hag,
			entertime_updated,
			exittime_updated,
			DAYOFWEEK(a.day) as weekday,
				(case when 
					exittime is null or entertime is null then
						null
					when exittime<entertime then
						0
					else
						TIME_TO_SEC(exittime)-TIME_TO_SEC(entertime)
					end)
				 as total,
			exittime,
			entertime,
			(case when 
					exittime is null or entertime is null then
						null
					when exittime<entertime then
						0
					when	
					(TIME_TO_SEC(exittime)-TIME_TO_SEC(entertime))>((case when dayofweek(a.day)=6 or h1.name is not null then $WorkingHoursFriday else $WorkingHoursPerDay end)*3600) then
						TIME_TO_SEC(exittime)-TIME_TO_SEC(entertime)-((case when dayofweek(a.day)=6 or h1.name is not null then $WorkingHoursFriday else $WorkingHoursPerDay end)*3600)
					else
						0
					end
			) as nosafot,
				total_salary as salary,
				base_salary,
				nosafot_salary,
				hour_salary
			from attendance a
			left outer join workers w on worker_num = w.workernum and w.user_id = $userID
			left outer join holidays h on h.day = a.day and h.user_id = $userID
			left outer join holidays h1 on h1.day = DATE_ADD(a.day,INTERVAL 1 DAY) and h1.user_id = $userID
			where a.day between '$sDateSQL' and '$eDateSQL'
			and worker_num = $num
			and a.user_id = $userID
			$q
			order by a.day,entertime
			";
		//echo $sql;
		$days = $conn->Execute($sql);
		if ($days === false){log_error($sql);}

		$report = array();
		
		while (!$days->EOF){
			
				$report[] = array(
					"id" => $days->fields["id"],
					"day" => $days->fields["day"],
					"weekday" => $days->fields["weekday"],
					"entertime" => $days->fields["entertime"],
					"exittime" => $days->fields["exittime"],
					"total" => $days->fields["total"],
					"nosafot" => $days->fields["nosafot"],
					"entertime_updated"=>$days->fields["entertime_updated"],
					"exittime_updated"=>$days->fields["exittime_updated"],
					"valid"=>$days->fields["valid"],
					"salary"=>$days->fields["salary"],
					"base_salary"=>$days->fields["base_salary"],
					"nosafot_salary"=>$days->fields["nosafot_salary"],
					"hour_salary"=>$days->fields["hour_salary"],
					"holiday_name"=>$days->fields["holiday_name"],
					"erev_hag"=>$days->fields["erev_hag"],
					"manual"=>$days->fields["manual"],
				);

			$days->MoveNext();
		}
		
		$_SESSION["REPORT"] = $report;
	
	}
	else{ 

		//Sort Mode
	
		$report = $_SESSION["REPORT"];
		$report = aSortBySecondIndex($report,$sort,$desc);
	}
	//Output
	
	$totalhours = 0;
	$totalnosafot = 0;
	$totalsalary = 0;
	$totalbasesalary = 0;
	$totalnosafotsalary = 0;

	$url = $_SELF."?saction=go&usr=$usr&rmodule=$rmodule&num=$num&desc=".((!$desc)?1:0);
	
	$worksheet->set_column('A:I', 12);
	$headings = array('תאריך','יום','כניסה','יציאה','שעות רגילות','שעות נוספות','סה"כ שעות','שכר שעות רגילות','שכר שעות נוספות','שכר סה"כ');
	$worksheet->write_row('A'.($rrow++), $headings, $heading);
	;

	echo "<div >
	<table id=REPORTTABLE dir=$dir  border=1 bgcolor=white width=100% bordercolor=black style='border-collapse:collapse' cellpadding=2>";
	echo "
	<tr><td colspan=11 bgcolor=lightyellow>
	מספר עובד: <b>".$worker->fields["WorkerNum"]."</b>
&nbsp;&nbsp;	ת.ז.: <b>".$worker->fields["TeudatZehut"]."</b>";
if ($worker->fields["Birthday"]&&$worker->fields["Birthday"]!="0000-00-00"){
    echo "&nbsp;&nbsp;	ת.ל.: <b>".dateFromSQL($worker->fields["Birthday"])."</b>";
}
if ($worker->fields["HomePhone"]){
    echo "&nbsp;&nbsp;	טלפון בבית: <b>".$worker->fields["HomePhone"]."</b>";
}
if ($worker->fields["CellPhone"]){
    echo "&nbsp;&nbsp;	טלפון נייד: <b>".$worker->fields["CellPhone"]."</b>";
}
if ($worker->fields["Address"]){
    echo "<br>כתובת: <b>".$worker->fields["Address"]."</b>";
}
if (trim($worker->fields["StartDate"])&&$worker->fields["StartDate"]!="0000-00-00"){
    echo "&nbsp;&nbsp;	ת.תחילת עבודה: <b>".dateFromSQL($worker->fields["StartDate"])."</b>";
}
if (trim($worker->fields["EndDate"])&&$worker->fields["EndDate"]!="0000-00-00"){
    echo "&nbsp;&nbsp;	ת.סיום עבודה: <b>".dateFromSQL($worker->fields["EndDate"])."</b>";
}
echo "</td></tr>
	<tr valign=top>
	<th><a href='$url&sort=day'>תאריך</a></th>
	<th><a href='$url&sort=weekday'>יום</a></th>
	<th><a href='$url&sort=entertime'>כניסה</a></th>
	<th><a href='$url&sort=exittime'>יציאה</a></th>
	<th><a href='$url&sort=ragilot'>שעות רגילות</a></th>
	<th><a href='$url&sort=nosafot'>שעות נוספות</a></th>
	<th><a href='$url&sort=total'>סה\"כ שעות</a></th>
	<th><a href='$url&sort=hour_salary'>שכר שעה</a></th>
	<th><a href='$url&sort=base_salary'>שכר שעות רגילות</a></th>
	<th><a href='$url&sort=nosafot_salary'>שכר שעות נוספות</a></th>
	<th><a href='$url&sort=salary'>שכר סה\"כ</a></th>
	<th>&nbsp;</th>
	</tr>
	";
	$days =array(
	1=>"א",
	2=>"ב",
	3=>"ג",
	4=>"ד",
	5=>"ה",
	6=>"ו",
	7=>"<font color=red>ש</font>",
	);
	
	foreach ($report as $row){
	
		$salary = ($row["salary"]);
		$basesalary = ($row["base_salary"]);
		$nosafotsalary = ($row["nosafot_salary"]);
		$hour_salary = $row["hour_salary"];
		
		$totalhours += $row["total"];
		$totalnosafot += $row["nosafot"];
		$totalsalary += $salary;
		$totalbasesalary += $basesalary;
		$totalnosafotsalary += $nosafotsalary;
	
		$total = $row["total"];
		if ($total!=""){
			$totalhrs = str_pad(floor($total / 3600),2,"0",STR_PAD_LEFT);
			$totalmin = str_pad(($total % 3600 ) /60,2,"0",STR_PAD_LEFT);
			$total = $totalhrs.":".$totalmin;
		}
		
		$nosafot = $row["nosafot"];
		if ($nosafot!=""){
			$nosafothrs = str_pad(floor($nosafot / 3600),2,"0",STR_PAD_LEFT);
			$nosafotmin = str_pad(($nosafot % 3600 ) /60,2,"0",STR_PAD_LEFT);
			$nosafot = $nosafothrs.":".$nosafotmin;
		}

                $ragilot = "";
                if ($total!="" && $nosafot!=""){
                    $ragilot = $row["total"]-$row["nosafot"];
                    $ragilothrs = str_pad(floor($ragilot / 3600),2,"0",STR_PAD_LEFT);
                    $ragilotmin = str_pad(($ragilot % 3600 ) /60,2,"0",STR_PAD_LEFT);
                    $ragilot = $ragilothrs.":".$ragilotmin;
                }
		
		$entertime=substr($row["entertime"],0,strlen($row["entertime"])-3);
		$exittime=substr($row["exittime"],0,strlen($row["exittime"])-3);
		
		$day=substr($row["day"],8,2)."/".substr($row["day"],5,2)."/".substr($row["day"],0,4);
	
		echo "
		<tr valign=top title=\"".($row["holiday_name"])."\" ".(($row["manual"])?"style='background-color:#efefef'":"").">
		<td height=22 ".((!$row["valid"])?"style=color:red":"")." ><span dir=ltr>".($day)."</td>
		<td align=center><span dir=ltr>".($days[$row["weekday"]]);
		if($row["holiday_name"]){
			echo "<strong style='color:red' title=\"".($row["holiday_name"])."\">*</strong>";
			$holiday=true;
		}
		if($row["erev_hag"]){
			echo "<strong style='color:green' title=\"".($row["erev_hag"])."\">*</strong>";
			$holiday=true;
		}
		echo "</td>
		<td ".(($row["entertime_updated"])?"style=color:blue":"")." onclick='edit(".$row["id"].",\"$entertime\",\"entertime\")'>".($entertime)."</td>
		<td ".(($row["exittime_updated"])?"style=color:blue":"")." onclick='edit(".$row["id"].",\"$exittime\",\"exittime\")'>".($exittime)."</td>
		<td><span dir=ltr>".($ragilot)."</td>
		<td><span dir=ltr>".($nosafot)."</td>
		<td><span dir=ltr>".($total)."</td>
		<td><span dir=ltr>".number_format($hour_salary,2)."</td>
		<td><span dir=ltr>".number_format($basesalary,2)."</td>
		<td><span dir=ltr>".number_format($nosafotsalary,2)."</td>
		<td><span dir=ltr>".number_format($salary,2)."</td>
        <td valign=middle>&nbsp;";
	    echo" <img src=".$imgPath."delete.png  align=absmiddle onclick='if (confirm(\"למחוק שורה?\")){document.F.deleterow.value=".$row["id"].";document.F.submit();}' style='cursor:hand' alt='מחק שורה'>";
		echo "</td></tr>";
		
		$b=$body;
		$f=$numformat;
		$worksheet->write("A".$rrow,strip_tags($row["day"]),$b);
		$worksheet->write("B".$rrow,strip_tags($days[$row["weekday"]]),$b);
		$worksheet->write("C".$rrow,$entertime,$b);
		$worksheet->write("D".$rrow,$exittime,$b);
		$worksheet->write("E".$rrow,$ragilot,$b);
		$worksheet->write("F".$rrow,$nosafot,$b);
		$worksheet->write("G".$rrow,$total,$b);
		$worksheet->write("H".$rrow,$basesalary,$f);
		$worksheet->write("I".$rrow,$nosafotsalary,$f);
		$worksheet->write("J".$rrow,$salary,$f);
		$rrow++;
		
		 
 	}
	
        $totalragilot =$totalhours - $totalnosafot;
	$ragilothrs = str_pad(floor($totalragilot / 3600),2,"0",STR_PAD_LEFT);
	$ragilotmin = str_pad(($totalragilot % 3600 ) /60,2,"0",STR_PAD_LEFT);
	$totalragilot = $ragilothrs.":".$ragilotmin;
	
	$totalhrs = str_pad(floor($totalhours / 3600),2,"0",STR_PAD_LEFT);
	$totalmin = str_pad(($totalhours % 3600 ) /60,2,"0",STR_PAD_LEFT);
	$totalhours = $totalhrs.":".$totalmin;
		
	$nosafothrs = str_pad(floor($totalnosafot / 3600),2,"0",STR_PAD_LEFT);
	$nosafotmin = str_pad(($totalnosafot % 3600 ) /60,2,"0",STR_PAD_LEFT);
	$totalnosafot = $nosafothrs.":".$nosafotmin;
	
	echo "<tr>
	<td><b>סה\"כ</b></td>
	<td></td>
	<td></td>
	<td></td>
	<td><strong>$totalragilot</strong></td>
	<td><strong>$totalnosafot</strong></td>
	<td><strong>$totalhours</strong></td>
	<td></td>
	<td><strong>".number_format($totalbasesalary,2)."</strong></td>
	<td><strong>".number_format($totalnosafotsalary,2)."</strong></td>
	<td><strong>".number_format($totalsalary,2)."</strong></td>
	</tr>";
	
	echo "</table></div>";
	
	if ($holiday){
		echo "<br><hr><strong style='color:red'>*</strong> חג <strong style='color:green'>*</strong> ערב חג";
	}
	
	}
	
	$worksheet->write("D".($rrow),"סה\"כ",$numformatb);
	$worksheet->write("E".($rrow),$totalragilot,$b);
	$worksheet->write("F".($rrow),$totalnosafot,$b);
	$worksheet->write("G".($rrow),$totalhours,$b);
	$worksheet->write("H".($rrow),$totalbasesalary,$numformatb);
	$worksheet->write("I".($rrow),$totalnosafotsalary,$numformatb);
	$worksheet->write("J".($rrow),$totalsalary,$numformatb);
	
	$workbook->close();
	copy($fname,"../../tmp/".$xlsfilename."_".$userID.".xls");
    unlink($fname);
	
	echo "<input type=hidden name=deleterow></FORM>";
	include("$config[template_path]/admin_bottom.html");
	$conn->Close(); // close the db connection
	
	
	function aSortBySecondIndex($multiArray, $secondIndex,$rew = false) {
		while (list($firstIndex, ) = each($multiArray))
		$indexMap[$firstIndex] = $multiArray[$firstIndex][$secondIndex];
		if (!$rew)
			asort($indexMap);
		else 	
			arsort($indexMap);
		while (list($firstIndex, ) = each($indexMap))
		if (is_numeric($firstIndex))
		$sortedArray[] = $multiArray[$firstIndex];
		else $sortedArray[$firstIndex] = $multiArray[$firstIndex];
	return $sortedArray;


}

?>