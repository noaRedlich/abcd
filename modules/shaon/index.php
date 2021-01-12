<html dir="rtl"></html><!--lc 18/05/2016 turning the display of the page from right to left -->
<?php  
 session_start();
?>
<script>
	function openReport(url){
		s = window.open(url+'&simple=1','','top=50,left=100,height=500,width=800,resizable=yes,scrollbars=yes');
		s.focus();
	}
</script>

<?php

$firstday = mktime(0,0,0,date("m"),1,date("Y"));
if (!$sDate){
	$sDate = date("d/m/Y",strtotime("+0 day",$firstday));
}
if (!$eDate){
	$eDate = date("d/m/Y",strtotime("-1 day",strtotime("+ 1 month",$firstday)));
}
	
	include("include/common.php");
	
	$xlsfilename="ShaotNochechut";

	if (!loginCheck('User'))exit;
	global $action, $id, $cur_page, $lang, $conn, $config;
	$conn->Execute('SET NAMES "utf8"');
	/*sk 20/01/2016 print nochechut report for all the worker*/
	//$sql="select WorkingHoursPerDay,WorkingHoursFriday from $TABLE_USERDATA where username='$username'";
	$sql="select * from $TABLE_USERDATA where username='$username'";
	$userdata = $conn->Execute($sql);	
	if ($userdata === false){log_error($sql);}
	if($userdata->EOF){
		die("User record is not initialized, please contact administrator.");
	}
	$WorkingHoursPerDay=$userdata->fields["WorkingHoursPerDay"];
	$WorkingHoursFriday=$userdata->fields["WorkingHoursFriday"];
	/*sk 28/01/16 nochechut report sequence*/
	$show_days_report=$userdata->fields["ShowDaysReport"];
	
	/*sk 20/01/2016 get fields from DB */
	?> 
	<input type="hidden" value="<?=$userdata->fields["printWorkerName"]?>" id="printWorkerName"/>
	<input type="hidden" value="<?=$userdata->fields["printWorkerNum"]?>" id="printWorkerNum"/>
    <input type="hidden" value="<?=$userdata->fields["printPayForHours"]?>" id="printPayForHours"/>
	<input type="hidden" value="<?=$userdata->fields["printTotalDays"]?>" id="printTotalDays"/>
    <input type="hidden" id="printRegularHours" name="printRegularHours" value="<?=$userdata->fields["printRegularHours"]?>"/>
    <input type="hidden" id="printOvertime" name="printOvertime" value="<?=$userdata->fields["printOvertime"]?>"/>
    <input type="hidden" id="printTotalHours" name="printTotalHours" value="<?=$userdata->fields["printTotalHours"]?>"/>
     <?
	$sql = "select * from $TABLE_LISTINGSSTOCKS where Status=1 and user_id = $userID order by binary StockName";
	$stocks = $conn->Execute($sql);
	if ($stocks === false){log_error($sql);}

	include("$config[template_path]/admin_top.html");
	

		echo "<table width=100% height=375 border=0><tr style='height:1%'>";
		echo "<td colspan=2></td>";
		// total report



			$sDateSQL=substr($sDate,6,4)."-".substr($sDate,3,2)."-".substr($sDate,0,2);
			$eDateSQL=substr($eDate,6,4)."-".substr($eDate,3,2)."-".substr($eDate,0,2);
			$q="";
			if ($stock){
				$q=" and terminal_id = $stock ";
			}
			$conn->Execute('SET NAMES "utf8"');
			
			$sql = "
			SELECT w.id as worker_id, workernum as worker_num, workername, workersurname,
			sum(TIME_TO_SEC(concat(exit_day,' ' ,exittime ))-TIME_TO_SEC(concat(day,' ',entertime))) as total,
			round(sum(
				(case when 
					TIME_TO_SEC(concat(exit_day,' ' ,exittime ))-TIME_TO_SEC(concat(day,' ',entertime))>((case when dayofweek(day)=6 then $WorkingHoursFriday else $WorkingHoursPerDay end)*3600) then
						TIME_TO_SEC(concat(exit_day,' ' ,exittime ))-TIME_TO_SEC(concat(day,' ',entertime))-((case when dayofweek(day)=6 then $WorkingHoursFriday else $WorkingHoursPerDay end)*3600)
					else
						0
					end 
				)
			),0) as nosafot,
                        count(distinct day) as numworkingdays,
			sum(total_Salary) as salary
			from workers w
			left outer join attendance a on worker_num = w.workerNum and a.user_id = $userID
			and a.day between '$sDateSQL' and '$eDateSQL'
			where w.user_id = $userID
			and w.Status = 1
			
 
			$q
			GROUP BY w.workernum 
			order by w.workernum
			";
		  
		echo "<!--SQL: $sql-->";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$recordSet = $conn->Execute($sql);
		
		if ($recordSet === false) log_error($sql);
		$num_rows = $recordSet->RecordCount();

		// build the string to select a certain number of listings per page
		$config[listings_per_page] = 1000;
		$limit_str = $cur_page * $config[listings_per_page];
		

		$recordSet = $conn->SelectLimit($sql, $config[listings_per_page], $limit_str );
		if ($recordSet === false) 
		{
			log_error($sql);
		}
		$count = 0;
		
			"<td><h3>$s</h3></td>";
		echo "</tr>";
		/*sk 20/01/16 print nochechut report for all the worker*/
		echo "<tr class='topHeader' style='height:1%;'><td colspan=2>";
		
		echo "<a style='color:black;font-size:8pt' href=# onclick=parent.wopen('cp/main.php?service=workers&bd_event=create_record','tools')><img src=".$imgPath."businessman_add.png width=16 height=16 align=absmiddle hspace=3 border=0>".$lang['new_isWorker']."</a>";

		echo "</td></tr>";
		echo "<tr>";
		echo "<h2 id='titlenochechut' style='display:none; text-align:center'>דוח נוכחות</h2>";
		echo "<td valign=top width=100% align=center>

		<div style='border:solid 1 gray;overflow-Y:auto;overflow-X:auto;width:100%;'>";

		$gs="stock=$stock&catid=$catid&deleted=$deleted&title=".urlencode($title)."&BarCode=$BarCode&ProductGroup=$ProductGroup&MisparZar=$MisparZar&MisparSiduri=$MisparSiduri&MisparChalifi=$MisparChalifi&search=$search";

		$fname = tempnam("../../tmp",$xlsfilename.".xls");
		$workbook =new writeexcel_workbook($fname);
		$workbook->set_tempdir('../../tmp');
		$worksheet = $workbook->addworksheet($xlsfilename);
		$header  = $workbook->addformat(array(
	                                        bold    => 1,
	                                        color   => 'blue',
											font 	=> "Arial Hebrew",
											size	=> 18,
											align  => 'left',
	                                        ));
		$heading  = $workbook->addformat(array(
	                                        bold    => 1,
	                                        color   => 'blue',
											font 	=> "Arial Hebrew",
											valign  => 'top',
											align	=> 'center',
											border	=> 1
	                                        ));
		$body  = $workbook->addformat(array(
	                                        bold    => 0,
											font 	=> "Arial Hebrew",
											valign  => 'top',
											border	=> 1,
											num_format  =>  '#######################0',
	                                        ));
		$bodyred  = $workbook->addformat(array(
	                                        bold    => 0,
											font 	=> "Arial Hebrew",
											valign  => 'top',
											color	=> "red",
											border	=> 1,
											num_format  =>  '#######################0',
	                                        ));
		$numformat  = $workbook->addformat(array(
											num_format  =>  '0.00',
											bold    => 0,
											font 	=> "Arial Hebrew",
											valign  => 'top',
											border	=> 1
	                                        ));
		$numformatb  = $workbook->addformat(array(
											num_format  =>  '0.00',
											bold    => 1,
											font 	=> "Arial Hebrew",
											valign  => 'top',
											border	=> 1
	                                        ));
		$numformatred  = $workbook->addformat(array(
											num_format  =>  '############0.00',
											bold    => 0,
											color	=> "red",
											font 	=> "Arial Hebrew",
											valign  => 'top',
											border	=> 1
	                                        ));
		$heading->set_text_wrap();
		$body->set_text_wrap();
		$worksheet->hide_gridlines(2);
		
		$page_subtitle = $lang['presence_hours'];
		$worksheet->write("A1",$page_subtitle,$header);
		$rrow=3;

		$worksheet->set_column('A:F', 12);
		$worksheet->set_column('B:B', 20);
		$headings = array($lang['worker_num'],$lang['worker_name'],$lang['normal_hours'],$lang['overtime_hours'],$lang['total_hours'],$lang['number_of_days'],$lang['hourly_salary']);
		$worksheet->write_row('A'.($rrow++), $headings, $heading);
		
		?>
		
		<table border="0" cellspacing="0" cellpadding="<?php echo $style['admin_listing_cellpadding'] ?>" width="100%" class="form_main">
		<tr class=tableHead2 align=center>
		<td class="tableHead2 td_worker_num" width=1% nowrap><?=$lang['worker_num']?></td>
		<td class="tableHead2 td_worker_name"  nowrap><?=$lang['worker_name']?></td>
		<td class="tableHead2 td_normal_hours" nowrap><?=$lang['normal_hours']?></td>
		<td class="tableHead2 td_overtime_hours" nowrap><?=$lang['overtime_hours']?></td>
		<td class="tableHead2 td_total_hours" nowrap><?=$lang['total_hours']?></td>
		<td class="tableHead2 td_total_days" nowrap><?=$lang['number_of_days']?></td>
		<td class="tableHead2 td_pay_for_hour" nowrap><?=$lang['hourly_salary']?></td> 
		<td class="tableHead2 topHeader" width=1%  nowrap><?=$lang['operations']?></td>
		</tr>
		<?
		
		$TOTAL=0;$NOSAFOT=0;$SALARY=0;$DAYS=0;
		while (!$recordSet->EOF)
		{
					
			$id =$recordSet->fields["worker_id"];
			$ID =$recordSet->fields["worker_num"];
			/*********************************************************************************************/    
		/*sk 28/01/16 nochechut report sequence*/
			if($show_days_report==0){
				 $sql="select day,DAYOFWEEK(day) as weekday,DAYOFWEEK(exit_day) as exit_weekday,
				DATEDIFF(exit_day,day) AS date_diff,
				(case when
					concat(exit_day,' ' ,exittime ) is null or concat(day,' ',entertime) is null then
						null
					when concat(exit_day,' ' ,exittime ) < concat(day,' ',entertime) then
						0
					else
						TIME_TO_SEC(concat(exit_day,' ' ,exittime ))-TIME_TO_SEC(concat(day,' ',entertime))
					end)
				 as total,entertime,exittime,exit_day from attendance  where worker_num='$ID' and day between '$sDateSQL' and '$eDateSQL'";
			echo '<!-- klor lor'.$sql.'-->';
			
				$calc_hour = $conn->Execute($sql);
				
				$report=array();
				
		while(!$calc_hour->EOF){
					$report[]=array(
					"day"               => $calc_hour->fields["day"],
               		"weekday"           => $calc_hour->fields["weekday"],
            		"entertime"         => $calc_hour->fields["entertime"],
                	"exittime"          => $calc_hour->fields["exittime"],
                	"total"             => $calc_hour->fields["total"],
                	"HourSalary"		=> $calc_hour->fields["HourSalary"],
                	"exit_day"          => $calc_hour->fields["exit_day"],
                	"exit_day_weekday"  => $calc_hour->fields["exit_weekday"],
                	"date_diff"  		=> $calc_hour->fields["date_diff"],
					);
					$calc_hour->MoveNext();				
				}
				$report_l=count($report);
			}
			
$final_total=0;
$salary_calc=0;
$salary_per_second=0;
				
$xx=0;
if($show_days_report==0){
	for($j=0;$j<$report_l;$j++,$xx++){
            $date_diff=$report[$j]["date_diff"];
		    if($date_diff>0){
		    	
	    	$total=($date_diff-1)*24*60;
			
			$num_hours=$report[$j]["entertime"];
			
			$hhhh= substr($num_hours,0,2);//get the hours
			
			$sss= substr($num_hours,3,2);//get the seconds
			
			$tttt=(24-$hhhh)*60;
			
			$tttt-=$sss;
			
			$total=$total+$tttt;
			
			$num_hours=$report[$j]["exittime"];
				$hhhh= substr($num_hours,0,2);
				  
				$sss= substr($num_hours,3,2);
				$hhhh=$hhhh*60;
				$hhhh+=$sss;
				$total+=$hhhh;
				$total=$total*60;
				
				
				$final_total+=$total;
		}	
		else {
			if($report[$j]['total']!=null){
				$final_total+=$report[$j]['total'];
				
			}
		}
	}			
}

			
			foreach($recordSet->fields as $name => $value){	 // in case when worker has not clicked on stopping work button
				if(intval($value) < 0){
					$recordSet->fields[$name] = null;
				}
			}

		/*********************************************************************************************/		

			// echo 'final_total: ' . $final_total . '<br>';
			// echo 'recordSet->fields["total"]: ' . $recordSet->fields["total"] . '<br>';
			
			//$cala_total= $final_total ?$final_total: $recordSet->fields["total"];
			$cala_total = null;
			
			if($final_total){
				$cala_total = $final_total;
			} else {
				if($recordSet->fields["total"] != null){ // in case when worker has not clicked on stopping work button
					$cala_total = $recordSet->fields["total"]; 
				}
			}
		
			$salary =$final_total ? $final_total*$salary_per_second : ($recordSet->fields["salary"]);
			$numworkingdays = $recordSet->fields["numworkingdays"];
			$worker_num = 	$recordSet->fields["worker_num"];
			$name = $recordSet->fields["workersurname"]." ".$recordSet->fields["workername"];
			$total = $cala_total;			
			$totalhrs = str_pad(floor($total / 3600),2,"0",STR_PAD_LEFT);
			$totalmin = str_pad(($total % 3600 ) /60,2,"0",STR_PAD_LEFT);
			$total = $totalhrs.":".$totalmin;
			
			$nosafot = $recordSet->fields["nosafot"];
			$nosafothrs = str_pad(floor($nosafot / 3600),2,"0",STR_PAD_LEFT);
			$nosafotmin = str_pad(($nosafot % 3600 ) /60,2,"0",STR_PAD_LEFT);
			$nosafot = $nosafothrs.":".$nosafotmin;
			
			//echo 'worker_id: ' . $recordSet->fields['worker_id'] . '<br>';
			//echo 'cala_total: ' . $cala_total . '<br>';
			//echo 'nosafot: ' . $recordSet->fields["nosafot"] . '<br>';
			$ragilot = $cala_total - $recordSet->fields["nosafot"];
			//echo 'worker_num: ' . $worker_num . '<br>';
			//echo 'ragilot: ' . $ragilot . '<br>';
			$ragilothrs = str_pad(floor($ragilot / 3600),2,"0",STR_PAD_LEFT);
			//echo 'ragilothrs: ' . $ragilothrs . '<br>';
			$ragilotmin = str_pad(($ragilot % 3600 ) /60,2,"0",STR_PAD_LEFT);
			//echo 'ragilotmin: ' . $ragilotmin . '<br>';
			$ragilot = $ragilothrs.":".$ragilotmin;
			//echo 'ragilot: ' . $ragilot . '<br>';
			//echo '<hr>';


			$TOTAL += $cala_total;
			$NOSAFOT += $recordSet->fields["nosafot"];
			$SALARY += $salary;
			$DAYS += $numworkingdays;
			
			// alternate the colors
			if ($count == 0)
			{
				$count = $count +1;
			}
			else
			{
				$count = 0;
			}
 
 $nan = 'NaN';
			 if($cala_total == null){ // if worker has not clicked stopping work button - following parameters will be NaN
				 $totalForEcho = $nan;
				 $ragilotForEcho = $nan;
				 $nosafotForEcho = $nan;
				 $numworkingdaysForEcho = $nan;
				 $salaryForEcho = $nan;
			 } else {
				 $totalForEcho = $total;
				 $ragilotForEcho = $ragilot;
				 $nosafotForEcho = $nosafot;
				 $numworkingdaysForEcho = $numworkingdays;
				 $salaryForEcho = $salary;
			 }
 
			echo "<tr id=tr$ID style='cursor:hand'>";
			
			echo "<td nowrap class='td_worker_num' onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" class=\"row3_$count\">$worker_num</td>";
			echo "<td class='td_worker_name' onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" class=\"row3_$count\"><a onclick='event.cancelBubble=true;' href=\"javascript:wopen('cp/main.php?service=workers&bd_event=edit_record&record_id=$id','edit')\">$name</a>&nbsp;</td>";
			echo "<td class='td_normal_hours' onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" nowrap class=\"row3_$count\" align=right><span dir=ltr>&nbsp;".$ragilotForEcho."&nbsp;</td>";
			echo "<td class='td_overtime_hours' onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" nowrap class=\"row3_$count\" align=right><span dir=ltr>&nbsp;".$nosafotForEcho."&nbsp;</td>";
			echo "<td  class='td_total_hours' onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" nowrap class=\"row3_$count\" align=right>&nbsp;<a onclick='event.cancelBubble=true;' href='javascript:openReport(\"worker.php?num=$worker_num&sDate=$sDate&eDate=$eDate\")'>".$totalForEcho."</a>&nbsp;</td>";
			echo "<td class='td_total_days' onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" nowrap class=\"row3_$count\" align=right><span dir=ltr>&nbsp;".$numworkingdaysForEcho."&nbsp;</td>";
			echo "<td class='td_pay_for_hour' onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" nowrap class=\"row3_$count\" align=right><span dir=ltr>&nbsp;".$salaryForEcho != $nun ? number_format($salaryForEcho,2) : $salaryForEcho."&nbsp;</td>";
			echo "<td class='topHeader' onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" nowrap class=\"row3_$count\" align=center>";
			echo"<a onclick='s=window.open(\"recalc.php?num=$worker_num&sDate=$sDate&eDate=$eDate\",\"recalc\",\"top=100,left=100,width=300,height=200\");s.focus();event.cancelBubble=true;' ><img  style='cursor:hand' src='".$imgPath."calc.gif' width=15 height=16 alt='{$lang['salary_calculation']}' border=0></a>";
			echo "&nbsp;</td>";
	
			$b=$body;
			$f=$numformat;
			$worksheet->write("A".$rrow,$worker_num,$b);
			$worksheet->write("B".$rrow,$name,$b);
			$worksheet->write("C".$rrow,$ragilot,$f);
			$worksheet->write("D".$rrow,$nosafot,$f);
			$worksheet->write("E".$rrow,$total,$f);
			$worksheet->write("F".$rrow,$numworkingdays,$f);
			$worksheet->write("G".$rrow,$salary,$f);
			$rrow++;
					
			echo "</tr>\r\n\r\n";
			$recordSet->MoveNext();
		} // end while
		
                $RAGILOT = $TOTAL - $NOSAFOT;

                $totalhrs = str_pad(floor($TOTAL / 3600),2,"0",STR_PAD_LEFT);
		$totalmin = str_pad(($TOTAL % 3600 ) /60,2,"0",STR_PAD_LEFT);
		$TOTAL = $totalhrs.":".$totalmin;

                $nosafothrs = str_pad(floor($NOSAFOT / 3600),2,"0",STR_PAD_LEFT);
		$nosafotmin = str_pad(($NOSAFOT % 3600 ) /60,2,"0",STR_PAD_LEFT);
		$NOSAFOT = $nosafothrs.":".$nosafotmin;

                $ragilothrs = str_pad(floor($RAGILOT / 3600),2,"0",STR_PAD_LEFT);
		$ragilotmin = str_pad(($RAGILOT % 3600 ) /60,2,"0",STR_PAD_LEFT);
		$RAGILOT = $ragilothrs.":".$ragilotmin;


		$worksheet->write("B".($rrow),"סה\"כ",$numformatb);
		$worksheet->write("C".($rrow),$RAGILOT,$numformatb);
		$worksheet->write("D".($rrow),$NOSAFOT,$numformatb);
		$worksheet->write("E".($rrow),$TOTAL,$numformatb);
		$worksheet->write("F".($rrow),$DAYS,$numformatb);
		$worksheet->write("G".($rrow),$SALARY,$numformatb);
		
		$workbook->close();
		copy($fname,"../../tmp/".$xlsfilename."_".$userID.".xls");
        unlink($fname);
		?>
		
		<tr style="border-top: 2px solid #00A7EB;">
		<td class="row3_1 td_worker_num"><strong><?=$lang['total']?></strong></td>
		<td class="row3_1 td_worker_name" >&nbsp;</td>
		<td class="row3_1 td_normal_hours" >&nbsp;<b><?=$RAGILOT?></b></td>
		<td class="row3_1 td_overtime_hours" >&nbsp;<b><?=$NOSAFOT?></b></td>
		<td class="row3_1 td_total_hours" >&nbsp;<b><?=$TOTAL?></b></td>
		<td class="row3_1 td_total_days" >&nbsp;<b><?=$DAYS?></b></td>
		<td class="row3_1 td_pay_for_hour" >&nbsp;<b><?=number_format($SALARY,2)?></b></td>
		<td class="row3_1" >&nbsp;</td>
		</tr>
		</table>
		</div>
		
		</td></tr></table>

	<P>
	</P>
<style>
	#divHeader{
		display: none!important;
	}
</style>
<?php
	include("$config[template_path]/admin_bottom.html");
	$conn->Close(); // close the db connection
?>