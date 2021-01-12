<?
function calcSalary($num,$userID,$username,$DateSQL,$hSalary,$newExitTime)
{
    $TABLE_USERDATA = 'vcx_weberp.userdata';

    $sql="select * from $TABLE_USERDATA where username='$username'"; // select * from vcx_weberp.userdata where username='meytal
    $result = mysql_query($sql);
    $userdata = mysql_fetch_array($result);
    //print_r($userdata);

    if ($userdata === false){log_error($sql);}

    $WorkingHoursPerDay=$userdata["WorkingHoursPerDay"];
    $NumAddHours=$userdata["NumAddHours"];
    $AddHoursPercentage1=$userdata["AddHoursPercentage1"];
    $AddHoursPercentage2=$userdata["AddHoursPercentage2"];
    $HolidayPercentage=$userdata["HolidayPercentage"];
    $HolidayEndTime=$userdata["HolidayEndTime"];
    $HolidayEndPercentage=$userdata["HolidayEndPercentage"];
    $WorkingHoursFriday=$userdata["WorkingHoursFriday"];
    if ($HolidayEndTime=="") $HolidayEndTime="23:59";
    $harr = explode(":",$HolidayEndTime);
    $HolidayEndHour = ($harr[0])?intVal($harr[0]):0;
    $HolidayEndMinute = ($harr[1])?intval($harr[1]):0;

	$sql="update attendance set exittime='$newExitTime',exittime_updated=now() where (exittime is null or exittime<>'$newExitTime') and day = '$DateSQL' and worker_num = '$num'";
	mysql_query($sql);
	
    $query = " worker_num = $num	and a.user_id = $userID and a.day = '$DateSQL' ";
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

    $result = mysql_query($sql);
    $RS = mysql_fetch_array($result);
	
    $hoursalary = ($hSalary) ? $hSalary : $RS["hour_salary"];
    $baseSalary = 0;
    $nosafotSalary = 0;
    $aidd = $RS["id"];
    $total = $RS["total"];
    $afterholiday = $RS["afterholiday"];
    $nosafot = $RS["nosafot"];
    $weekday = $RS["weekday"];
    $day = $RS["day"];

    if ($afterholiday<0)$afterholiday=0;

    //check if is motsash i.e. day before holiday
    $isHoliday = $RS["holiday_name"];
    if ($total && $hoursalary)
	{
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
        $totalSalary = 0;
    }

    $hoursalary = number_format($hoursalary,"2",".",""); // 23.00
    $baseSalary = number_format($baseSalary,"2",".",""); // 157.17
    $nosafotSalary = number_format($nosafotSalary,"2",".","");	// 0
    $totalSalary = number_format($totalSalary,"2",".",""); // 157.17
	
	$sql="update attendance set exittime='$newExitTime',exittime_updated=now(), salary_calculated=unix_timestamp(),hour_salary='$hoursalary', base_salary='$baseSalary', nosafot_salary='$nosafotSalary', total_salary='$totalSalary' where id=$aidd";
	$result = mysql_query($sql);
	return $result;
}