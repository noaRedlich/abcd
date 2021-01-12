<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

$to = isset($to) ? $to : '';
$title = isset($title) ? $title : '';
$description = isset($description) ? $description : '';

$hours = array("00","01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23");
$mins = array("00","05","10","15","20","25","30","35","34","45","50","55");
for ($i=1;$i<32;$i++)
{
	$num_days[] = $i;
}

for ($i=1;$i<13;$i++)
{
	$num_months[] = $i;
}


echo '<table border="0" cellpadding="2" cellspacing="0">';
if (isset($feedback))
	echo '<tr><td colspan="2" class="Error">'.$feedback.'</td></tr>';
echo '<tr><td>';
echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td><a class=\"normal\" href=\"javascript:open_addressbook('to',document.forms[0].to.value)\"><img src=\"".$GO_THEME->image_url."buttons/addressbook.gif\" width=\"16\" height=\"16\" border=\"0\" /></a>&nbsp;</td><td><a class=\"normal\" href=\"javascript:open_addressbook('to',document.forms[0].to.value)\">$sc_participants</a>:&nbsp;</td></tr></table></td>";
echo '<td><input type="text" class="textbox" name="to" value="'.$to.'" size="70" /></td></tr>';
echo '<tr><td>'.$sc_title.':&nbsp;</td><td><input type="text" class="textbox" maxlength="50" name="title" size="70" value="'.$title.'" /></td></tr>';
echo '<tr><td valign="top">'.$sc_description.':&nbsp;</td><td><textarea class="textbox" name="description" cols="60" rows="12">'.$description.'</textarea></td></tr>';
echo '<tr><td colspan="2" height="30">';

if (!isset($recur))
{
	 $recur = 'once';
	 $once = 'checked';
	 $weekly = '';
	 $daily = '';
	 $month_date = '';
	 $month_day = '';
	 $yearly = '';
}else
{
	switch($recur)
	{
		case 'once':
			$once = 'checked';
			$weekly = '';
		 	$daily = '';
		 	$month_date = '';
		 	$month_day = '';
	 		$yearly = '';
		break;

		case 'weekly':
			$weekly='checked';
			$once = '';
			$daily = '';
			$month_date = '';
			$month_day = '';
			$yearly = '';

		break;

		case 'daily':
			$daily = 'checked';
			$weekly = '';
		 	$once = '';
		 	$month_date = '';
		 	$month_day = '';
	 		$yearly = '';

		break;

		case 'month_date':
			$month_date = 'checked';
			$weekly = '';
		 	$daily = '';
		 	$once = '';
		 	$month_day = '';
	 		$yearly = '';

		break;

		case 'month_day':
			$month_day = 'checked';
			$weekly = '';
		 	$daily = '';
		 	$month_date = '';
		 	$once = '';
	 		$yearly = '';

		break;

		case 'yearly';
			$yearly = 'checked';
			$weekly = '';
		 	$daily = '';
		 	$month_date = '';
		 	$month_day = '';
	 		$once = '';
		break;
	}
}

echo '<table border="0" cellpadding="0" cellspacing="0"><tr>';
echo '<td><input type="radio" name="recur" value="once" onclick="javascript:toggle_recur(\'once\')" '.$once.' /></td><td>'.$sc_types['once'].'</td>';
echo '<td><input type="radio" name="recur" value="daily" onclick="javascript:toggle_recur(\'daily\')" '.$daily.' /></td><td>'.$sc_types['daily'].'</td>';
echo '<td><input type="radio" name="recur" value="weekly" onclick="javascript:toggle_recur(\'weekly\')" '.$weekly.' /></td><td>'.$sc_types['weekly'].'</td>';
echo '<td><input type="radio" name="recur" value="month_date" onclick="javascript:toggle_recur(\'month_date\')" '.$month_date.' /></td><td>'.$sc_types['month_date'].'</td>';
echo '<td><input type="radio" name="recur" value="month_day" onclick="javascript:toggle_recur(\'month_day\')" '.$month_day.' /></td><td>'.$sc_types['month_day'].'</td>';
echo '<td><input type="radio" name="recur" value="yearly" onclick="javascript:toggle_recur(\'yearly\')" '.$yearly.' /></td><td>'.$sc_types['yearly'].'</td>';
echo '</tr></table></td></tr>';
echo '<tr><td>'.$sc_start_at.':&nbsp;</td><td>';

if (!isset($start_month))
{
	$start_month = $month;
}
if (!isset($start_day))
{
	$start_day = $day;
}

echo '<table border="0" cellpadding="0" cellspacing="0"><tr><td>';
if ($ses_date_format == "m-d-Y H:i")
{
	$dropbox = new dropbox();
	$dropbox->add_arrays($num_months, $months);
	$dropbox->print_dropbox("start_month", $start_month);

	$dropbox = new dropbox();
	$dropbox->add_arrays($num_days, $num_days);
	$dropbox->print_dropbox("start_day", $start_day);
}else
{
	$dropbox = new dropbox();
	$dropbox->add_arrays($num_days, $num_days);
	$dropbox->print_dropbox("start_day", $start_day);

	$dropbox = new dropbox();
	$dropbox->add_arrays($num_months, $months);
	$dropbox->print_dropbox("start_month", $start_month);
}
echo '</td><td>&nbsp;&nbsp;';

if ($start_hour == 0) $start_hour = $hour;
$dropbox = new dropbox();
$dropbox->add_arrays($hours, $hours);
$dropbox->print_dropbox("start_hour", $start_hour);
echo '&nbsp;:&nbsp;';
$dropbox = new dropbox();
$dropbox->add_arrays($mins, $mins);
$dropbox->print_dropbox("start_min", $start_min);

echo '</td></tr></table>';
echo '</td></tr>';

echo '<tr><td>'.$sc_end_at.':&nbsp;</td><td>';
if (!isset($end_month)) $end_month = $month;
if (!isset($end_day)) $end_day = $day;

echo '<table border="0" cellpadding="0" cellspacing="0"><tr><td>';
if ($ses_date_format == "m-d-Y H:i")
{
	$dropbox = new dropbox();
	$dropbox->add_arrays($num_months, $months);
	$dropbox->print_dropbox("end_month", $end_month);

	$dropbox = new dropbox();
	$dropbox->add_arrays($num_days, $num_days);
	$dropbox->print_dropbox("end_day", $end_day);
}else
{
	$dropbox = new dropbox();
	$dropbox->add_arrays($num_days, $num_days);
	$dropbox->print_dropbox("end_day", $end_day);

	$dropbox = new dropbox();
	$dropbox->add_arrays($num_months, $months);
	$dropbox->print_dropbox("end_month", $end_month);
}
echo '</td><td>&nbsp;&nbsp;';

if($end_hour==0) $end_hour = $hour;
$dropbox = new dropbox();
$dropbox->add_arrays($hours, $hours);
$dropbox->print_dropbox("end_hour", $end_hour);
echo '&nbsp;:&nbsp;';
$end_min = isset($end_min) ? $end_min : 0;
$dropbox = new dropbox();
$dropbox->add_arrays($mins, $mins);
$dropbox->print_dropbox("end_min", $end_min);
echo '</td></tr></table>';
echo '</td></tr>';

$notime_check = ($notime == '1') ? 'checked' : '';

echo '<tr><td>&nbsp;</td><td><table border="0" cellpadding="0" cellspacing="0"><tr><td><input type="checkbox" name="notime" value="1" onclick="javascript:disable_time()" '.$notime_check.' /></td><td>'.$sc_notime.'</td></tr></table></td></tr>';
echo '<tr><td>'.$sc_cycle_start.':&nbsp;</td><td>';


if ($ses_date_format == "m-d-Y H:i")
{
	$dropbox = new dropbox();
	$dropbox->add_arrays($num_months, $months);
	$dropbox->print_dropbox("starting_month", $starting_month);

	$dropbox = new dropbox();
	$dropbox->add_arrays($num_days, $num_days);
	$dropbox->print_dropbox("starting_day", $starting_day);
}else
{
	$dropbox = new dropbox();
	$dropbox->add_arrays($num_days, $num_days);
	$dropbox->print_dropbox("starting_day", $starting_day);

	$dropbox = new dropbox();
	$dropbox->add_arrays($num_months, $months);
	$dropbox->print_dropbox("starting_month", $starting_month);
}

$years[0] = $year;
$years[1] = $year+1;
$years[2] = $year+2;
$years[3] = $year+3;
$years[4] = $year+4;

$ending_year = isset($ending_year) ? $ending_year : 0;
$dropbox = new dropbox();
$dropbox->add_arrays($years, $years);
$dropbox->print_dropbox("starting_year", $starting_year);
echo '</td></tr>';

echo '<tr><td>'.$sc_cycle_end.':&nbsp;</td><td>';

if ($ses_date_format == "m-d-Y H:i")
{
	$dropbox = new dropbox();
	$dropbox->add_arrays($num_months, $months);
	$dropbox->print_dropbox("ending_month", $ending_month);

	$dropbox = new dropbox();
	$dropbox->add_arrays($num_days, $num_days);
	$dropbox->print_dropbox("ending_day", $ending_day);
}else
{
	$dropbox = new dropbox();
	$dropbox->add_arrays($num_days, $num_days);
	$dropbox->print_dropbox("ending_day", $ending_day);

	$dropbox = new dropbox();
	$dropbox->add_arrays($num_months, $months);
	$dropbox->print_dropbox("ending_month", $ending_month);
}

$years[0] = $year;
$years[1] = $year+1;
$years[2] = $year+2;
$years[3] = $year+3;
$years[4] = $year+4;

$dropbox = new dropbox();
$dropbox->add_arrays($years, $years);
$dropbox->print_dropbox("ending_year", $ending_year);

$noend_check = ($noend == '1') ? 'checked' : '';

echo '<input type="checkbox" name="noend" value="1" onclick="javascript:toggle_ending_date()" '.$noend_check.' />'.$sc_noend;

echo '</td></tr>';
echo '<tr><td colspan="2">';
echo '<table border="0" cellpadding="0" cellspacing="0"><tr>';
echo '<td>';
$month_time = isset($month_time) ? $month_time : 0;
$dropbox = new dropbox();
$dropbox->add_arrays(array(1,2,3,4), $month_times);
$dropbox->print_dropbox("month_time", $month_time);
echo '</td>';

$day_number = $daystart;
for ($i=0;$i<7;$i++)
{
	if ($day_number == 7) $day_number = 0;
	echo '<td><input type="checkbox" name="recur_days_'.$day_number.'" value="1" ';
	$var = 'recur_days_'.$day_number;
	if (isset($$var) && $$var != '') echo 'checked ';
	echo '/></td><td>'.$days[$day_number].'</td>';
	$day_number++;
}
echo '</tr></table></td></tr>';
$location_text = isset($location_text) ? $location_text : '';
echo '<tr height="40"><td>'.$sc_location.':</td><td><input type="text" class="textbox" size="40" name="location_text" value="'.$location_text.'" /></td></tr>';
if ($post_action == 'load_event')
{
	echo "<tr><td colspan=\"2\"><a href=\"javascript:popup('event_permissions.php?event_id=".$event_id."','450','380')\" class=\"normal\">".$strPermissions."</a><br /><br /></td></tr>";
}

if (!$event_id)
{
	$scheduler_count = $scheduler->get_subscribed($GO_SECURITY->user_id);

	$inherit_from = isset($inherit_from) ? $inherit_from : $scheduler_id;
	echo '<tr><td>'.$sc_inherit_permissions.':</td><td>';
	$dropbox = new dropbox();
	$dropbox->add_value('0', $sc_no_inherit);
	while ($scheduler->next_record())
	{
		if ($scheduler->f('user_id') == $GO_SECURITY->user_id)
		{
			$dropbox->add_value($scheduler->f('id'), $scheduler->f('name'));
		}
	}
	$dropbox->print_dropbox('inherit_from', $inherit_from);
	echo '</td></tr>';

}

$scheduler_count = $scheduler->get_subscribed($GO_SECURITY->user_id);
if($scheduler_count > 1)
{
	echo '<tr><td valign="top">'.$sc_put_in.':</td>';
	echo '<td><table border="0">';
	while ($scheduler->next_record())
	{
		if ($GO_SECURITY->has_permission($GO_SECURITY->user_id, $scheduler->f('acl_write')))
		{
			$schedulers_check = (isset($schedulers) && in_array($scheduler->f('id'), $schedulers)) ? 'checked' : '';
			$schedulers_check = ($scheduler_id == $scheduler->f('id')) ? 'checked' : $schedulers_check;
			echo '<tr><td><input type="checkbox" name="schedulers[]" value="'.$scheduler->f('id').'" '.$schedulers_check.'  /></td><td>'.$scheduler->f('name').'</td></tr>';
		}
	}
	echo '</table></td></tr>';
}else
{
	echo '<input type="hidden" name="schedulers[]" value="'.$scheduler_id.'" />';
}

echo '<tr><td colspan="2">';
$button = new button($cmdOk, 'javascript:save_event();');
echo '&nbsp;&nbsp;';
$button = new button($cmdCancel, 'javascript:post_'.$last_view.'('.$day.')');
echo '</td></tr>';
echo '</table>';
?>
<script type="text/javascript" language="javascript">
toggle_recur('<?php echo $recur; ?>');
<?php

if ($notime == '1')
{
	echo 'disable_time();';
}

if ($noend == '1')
{
	echo 'toggle_ending_date();';
}
?>
function open_addressbook(field, address_string)
{
	popup('<?php echo $GO_CONFIG->host."contacts/select.php?SET_HANDLER=".$GO_CONFIG->host.$GO_MODULES->path."add_contacts.php&SET_FIELD="; ?>'+field+'&address_string='+escape(address_string),'550','400');
}

function save_event()
{
	start_date = new Date(document.forms[0].year.value+'/'+document.forms[0].start_month.value+'/'+document.forms[0].start_day.value+' '+document.forms[0].start_hour.value+':'+document.forms[0].start_min.value+':00');
	end_date = new Date(document.forms[0].year.value+'/'+document.forms[0].end_month.value+'/'+document.forms[0].end_day.value+' '+document.forms[0].end_hour.value+':'+document.forms[0].end_min.value+':00');
	starting_date = new Date(document.forms[0].starting_year.value+'/'+document.forms[0].starting_month.value+'/'+document.forms[0].starting_day.value);
	ending_date = new Date(document.forms[0].ending_year.value+'/'+document.forms[0].ending_month.value+'/'+document.forms[0].ending_day.value);

	if (start_date > end_date)
	{
		alert("<?php echo $sc_start_later; ?>");
		return;
	}

	if ((starting_date > ending_date) && document.forms[0].noend.checked == false)
	{
		alert("<?php echo $sc_cycle_start_later; ?>");
		return;
	}

	if (document.forms[0].recur_type.value == 'weekly' || document.forms[0].recur_type.value == 'month_day')
	{
		if (document.forms[0].recur_days_0.checked == false && document.forms[0].recur_days_1.checked == false && document.forms[0].recur_days_2.checked == false && document.forms[0].recur_days_3.checked == false && document.forms[0].recur_days_4.checked == false && document.forms[0].recur_days_5.checked == false && document.forms[0].recur_days_6.checked == false)
		{
			alert("<?php echo $sc_never_happens; ?>");
			return;
		}
	}
	document.forms[0].post_action.value = 'save_event';
	document.forms[0].submit();

}
</script>
