<?
function calcSalary($num,$sDate,$eDate,$attendance_id,$hSalary,$transusername)
{
	global $conn,$userID,$username,$TABLE_USERDATA;

    $USER = ($transusername)?$transusername:$username;
	$sql="select * from $TABLE_USERDATA where username='$USER'";
	$userdata = $conn->Execute($sql);
	if ($userdata === false){log_error($sql);}
	if($userdata->EOF){
		die("No user record registered, please contact administrator.");
	}
	$WorkingHoursPerDay=$userdata->fields["WorkingHoursPerDay"];
	$NumAddHours=$userdata->fields["NumAddHours"];
	$AddHoursPercentage1=$userdata->fields["AddHoursPercentage1"];
	$AddHoursPercentage2=$userdata->fields["AddHoursPercentage2"];
	$HolidayPercentage=$userdata->fields["HolidayPercentage"];
	$HolidayEndTime=$userdata->fields["HolidayEndTime"];
	$HolidayEndPercentage=$userdata->fields["HolidayEndPercentage"];
	$WorkingHoursFriday=$userdata->fields["WorkingHoursFriday"];
    if ($HolidayEndTime=="")$HolidayEndTime="23:59";
    $harr = explode(":",$HolidayEndTime);
    $HolidayEndHour = ($harr[0])?intVal($harr[0]):0;
    $HolidayEndMinute = ($harr[1])?intval($harr[1]):0;


	if ($attendance_id > 0){
		$query=" a.id = $attendance_id ";
	}
	else{
		$sDateSQL=substr($sDate,6,4)."-".substr($sDate,3,2)."-".substr($sDate,0,2);
		$eDateSQL=substr($eDate,6,4)."-".substr($eDate,3,2)."-".substr($eDate,0,2);
		$query=" a.day between '$sDateSQL' and '$eDateSQL'
				and worker_num = $num
				and a.user_id = $userID ";
	}
	$sql = "
			SELECT 
			a.id,
			a.day,
			h.name as holiday_name,
			sabbat,
			valid,
			entertime_updated,
			exittime_updated,
			hour_salary,
			DAYOFWEEK(a.day) as weekday,
				(case when
					exittime is null or entertime is null then
						0
					when exittime<entertime then
						0
					else
						TIME_TO_SEC(exittime)-TIME_TO_SEC(entertime)
					end)
				 as total,
				(case when
					exittime is null or entertime is null then
						0
					when exittime<entertime then
						0
                    when TIME_TO_SEC(entertime) >= TIME_TO_SEC(MAKETIME($HolidayEndHour,$HolidayEndMinute,0)) then
                        TIME_TO_SEC(exittime)-TIME_TO_SEC(entertime)
					else
						TIME_TO_SEC(exittime)-TIME_TO_SEC(MAKETIME($HolidayEndHour,$HolidayEndMinute,0))
					end)
				 as afterholiday,
            exittime,
			entertime,
			(case when
					exittime is null or entertime is null then
						0
					when exittime<entertime then
						0
					when	
					(TIME_TO_SEC(exittime)-TIME_TO_SEC(entertime))>((case when dayofweek(a.day)=6 or h1.name is not null then $WorkingHoursFriday else $WorkingHoursPerDay end)*3600) then
						TIME_TO_SEC(exittime)-TIME_TO_SEC(entertime)-((case when dayofweek(a.day)=6 or h1.name is not null then $WorkingHoursFriday else $WorkingHoursPerDay end)*3600)
					else
						0
					end
			) as nosafot
			from attendance a
			left outer join workers w on worker_num = w.workernum and w.user_id = $userID
			left outer join holidays h on a.day = h.day and h.user_id = $userID
			left outer join holidays h1 on h1.day = DATE_ADD(a.day,INTERVAL 1 DAY) and h1.user_id = $userID
			where $query
			order by a.day,entertime";
			//echo "<!--SQL: $sql -->";
	$RS = $conn->Execute($sql);
	if ($RS === false){log_error($sql);}
	while (!$RS->EOF){
		$hoursalary = ($hSalary)?$hSalary:$RS->fields["hour_salary"];
		$baseSalary = 0;
		$nosafotSalary = 0;
		$aidd = $RS->fields["id"];
		$total = $RS->fields["total"];
		$afterholiday = $RS->fields["afterholiday"];
		$nosafot = $RS->fields["nosafot"];
		$weekday = $RS->fields["weekday"];
		$day = $RS->fields["day"];

        //echo "enter=".$RS->fields["entertime"]." holidayend=".$RS->fields["holidayendtime"]."<br>";

        if ($afterholiday<0)$afterholiday=0;

        //check if is motsash i.e. day before holiday


		$isHoliday = $RS->fields["holiday_name"];
		if ($total && $hoursalary){
			
			if ($weekday == 7 || $isHoliday){
				//holiday
				$baseSalaryHoliday = $hoursalary * ($total-$afterholiday)/3600;
				$baseSalaryAfterHoliday = $hoursalary * ($afterholiday)/3600;
                $baseSalary = $baseSalaryHoliday + $baseSalaryAfterHoliday;
                $nosafotSalaryHoliday = $baseSalaryHoliday * $HolidayPercentage/100 - $baseSalaryHoliday;
                $nosafotSalaryAfterHoliday = $baseSalaryAfterHoliday * $HolidayEndPercentage/100 - $baseSalaryAfterHoliday;
                $nosafotSalary  = $nosafotSalaryHoliday + $nosafotSalaryAfterHoliday;
				//echo $HolidayPercentage."% ".$day." afterholiday=$afterholiday base=". $baseSalaryAfterHoliday ."    nosafot=".$nosafotSalary."<br>";
			}
			else{
				//regular day
				$baseSalary = $hoursalary * ($total-$nosafot)/3600;
				echo "<!--base salary:$baseSalary-->";
				if ($nosafot/3600<=$NumAddHours){
					$nosafotSalary = ($hoursalary * $AddHoursPercentage1/100) * $nosafot/3600;
				}
				else{
					$nosafotSalary1 = ($hoursalary * $AddHoursPercentage1/100) * $NumAddHours;
					$nosafotSalary2 = (($hoursalary * $AddHoursPercentage2/100) * ($nosafot-$NumAddHours*3600)/3600);
					$nosafotSalary = $nosafotSalary1+$nosafotSalary2;
					//echo $AddHoursPercentage1."% ".$day." base=". $baseSalary ."    nosafot1=".$nosafotSalary1." nosafot2=".$nosafotSalary2."<br>";

				}
			}
			$totalSalary = $baseSalary+$nosafotSalary;
		}
		else{
			$totalSalary=0;
		}
		
		$hoursalary = number_format($hoursalary,"2",".","");
		$baseSalary = number_format($baseSalary,"2",".","");
		$nosafotSalary = number_format($nosafotSalary,"2",".","");
		$totalSalary = number_format($totalSalary,"2",".","");
		
		$sql="update attendance set salary_calculated=unix_timestamp(),hour_salary='$hoursalary', base_salary=$baseSalary, nosafot_salary=$nosafotSalary, total_salary=$totalSalary where id=$aidd";
		$SAL = $conn->Execute($sql); 
		if ($SAL === false){log_error($sql);}
	
		$RS->MoveNext();
		
	}
	
}
?>