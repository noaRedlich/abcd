<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

$daystart = 0;

$years[0] = $year - 2;
$years[1] = $year - 1;
$years[2] = $year;
$years[3] = $year + 1;
$years[4] = $year + 2;

// NUMBER OF DAYS IN MONTH
$days_in_month = mktime (0,0,0,$month+1,0,$year);
$days_in_month = (strftime ("%d",$days_in_month));

if ($day > $days_in_month)
{
	$day = $day - $days_in_month;
	if ($month < 12)
	{
		$month++;
	}else
	{
		$month=1;
		$year++;
	}

	$days_in_month = mktime (0,0,0,$month+1,0,$year);
	$days_in_month = (strftime ("%d",$days_in_month));
}

$first_day_of_month = date("w", mktime(0,0,0,$month,1,$year));

$month_numbers[0] = '1';
$month_numbers[1] = '2';
$month_numbers[2] = '3';
$month_numbers[3] = '4';
$month_numbers[4] = '5';
$month_numbers[5] = '6';
$month_numbers[6] = '7';
$month_numbers[7] = '8';
$month_numbers[8] = '9';
$month_numbers[9] = '10';
$month_numbers[10] = '11';
$month_numbers[11] = '12';
$dropbox = new dropbox();
$dropbox->add_arrays($month_numbers, $months);
$dropbox->print_dropbox('month',$month);

$dropbox = new dropbox();
$dropbox->add_arrays($years, $years);
$dropbox->print_dropbox('year',$year);


echo '<table border="0" cellpadding="0" cellspacing="3">';
echo '<tr><td>&nbsp;</td>';

$day_number = $daystart;
for ($i=0;$i<7;$i++)
{
	if ($day_number == 7) $day_number = 0;
	echo '<td align="center" class="small">'.$days[$day_number].'</td>';
	$day_number++;
}
echo '</tr>';

$cellcount=$first_day_of_month-$daystart;
if ($cellcount < 0) $cellcount = 7 + $cellcount;

echo '<tr>';
//$week_number = intval(strftime("%V",mktime(0,0,0,$month,1,$year)));
$week_number = date("W", mktime(0,0,0,$month,1,$year));

echo '<td><i><a class="small" href="javascript:post_week(1)">'.$week_number.'</a></i></td>';
for ($i=1;$i<=$cellcount;$i++)
{
	echo '<td>&nbsp;</td>';
}

for ($i=1;$i<=$days_in_month;$i++)
{
	if ($cellcount == 7)
	{
		echo "</tr><tr>\n";
		$week_number++;
		echo '<td><i><a class="small" href="javascript:post_week('.$i.')">'.$week_number.'</a></i></td>';
		$cellcount=0;
	}
	$cellcount++;
	if (($i == $current_day) && ($year == $current_year) && ($month==$current_month))
		$class="HoverLinkSelected";
	else
		$class="HoverLink";

	echo '<td align="center"><a class="'.$class.'" href="javascript:post_day('.$i.')">'.$i.'</a></td>';

}
$remaining_cells = 7-$cellcount;

for ($i=0;$i<$remaining_cells;$i++)
{
	echo '<td>&nbsp;</td>';
}

echo '</tr>';
echo '</table>';

?>





