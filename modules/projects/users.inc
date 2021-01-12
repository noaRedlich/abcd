<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

$show = isset($show) ? $show : 'all';
$pm_user_id = isset($pm_user_id) ? $pm_user_id : $GO_SECURITY->user_id;

if (isset($delete_hours))
{
	$projects->delete_hours($delete_hours);
}
switch ($show)
{
	case 'all':
		$projects->get_user_hours($pm_user_id);
		$all_check='checked';
		$period_check = '';
		$month_check = '';
	break;

	case 'period':
		$projects->get_user_hours($pm_user_id,mktime(0,0,0,$start_month, $start_day, $start_year), mktime(0,0,0,$end_month, $end_day, $end_year));
		$all_check='';
		$period_check = 'checked';
		$month_check = '';
	break;

	case 'month':
		$days_in_month = mktime (0,0,0,$month+1,0,$year);
		$days_in_month = (strftime ("%d",$days_in_month));

		$start_time = mktime(0,0,0,$month, 1, $year);
		$end_time = mktime(0,0,0,$month, $days_in_month, $year);

		$projects->get_user_hours($pm_user_id, $start_time, $end_time);
		$all_check='';
		$period_check = '';
		$month_check = 'checked';
	break;
}
?>
<form name="users" method="post" action="<?php echo $PHP_SELF; ?>">
<input type="hidden" name="post_action" value="users" />
<table border="0" cellspacing="0" cellpadding="4">
<?php
$pm_user_id = isset($pm_user_id) ? $pm_user_id : $GO_SECURITY->user_id;
if ($GO_MODULES->write_permissions)
{
	echo '<tr><td>'.$pm_employee.':</td><td>';
	require_once($GO_CONFIG->class_path.'users.class.inc');
	$users = new users;
	$users->get_users();

	$dropbox = new dropbox();
	while($users->next_record())
	{
		if ($GO_SECURITY->has_permission($GO_SECURITY->user_id, $users->f('acl_id')) || $users->f('id') == $GO_SECURITY->user_id)
		{
			$dropbox->add_value($users->f('id'),$users->f('name'));
		}
	}
	$dropbox->print_dropbox('pm_user_id',$pm_user_id);
	echo '</td></tr>';
}else
{
	echo '<input type="hidden" name="pm_user_id" value="'.$GO_SECURITY->user_id.'" />';
}
echo '<input type="hidden" name="delete_hours" />';
?>
<tr>
	<td height="25" colspan="2">
	<?php
	echo '<input type="radio" name="show" value="all" '.$all_check.' />'.$pm_show_all;
	echo '</td></tr><tr><td height="25">';

	echo '<input type="radio" name="show" value="period" '.$period_check.' />'.$pm_show_period.'</td><td>';

	$time = time();
	$today = date("j", $time);
	$this_year = date("Y", $time);
	$this_month = date("m", $time);

	$start_day = isset($start_day) ? $start_day : $today;
	$start_month = isset($start_month) ? $start_month : $this_month;
	$start_year = isset($start_year) ? $start_year : $this_year;

	$end_day = isset($end_day) ? $end_day : $today;
	$end_month = isset($end_month) ? $end_month : $this_month;
	$end_year = isset($end_year) ? $end_year : $this_year;

	for ($i=0;$i<10;$i++)
	{
		$num_years[] = $this_year - $i;
	}

	for ($i=1;$i<32;$i++)
	{
		$num_days[] = $i;
	}

	for ($i=1;$i<13;$i++)
	{
		$num_months[] = $i;
	}

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
	$dropbox = new dropbox();
	$dropbox->add_arrays($num_years, $num_years);
	$dropbox->print_dropbox("start_year", $start_year);

	echo '&nbsp;&nbsp;'.$pm_to.'&nbsp;&nbsp;';

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
	$dropbox = new dropbox();
	$dropbox->add_arrays($num_years, $num_years);
	$dropbox->print_dropbox("end_year", $end_year);
	?>
	</td>
</tr>
<tr>
	<td height="25">
	<input type="radio" name="show" value="month" <?php echo $month_check; ?> /><?php echo $pm_show_month; ?>
	</td>
	<td>
	<?php
	$month = isset($month) ? $month : $this_month;
	$dropbox = new dropbox();
	$dropbox->add_arrays($num_months, $months);
	$dropbox->print_dropbox("month", $month);

	$year = isset($year) ? $year : $this_year;
	$dropbox = new dropbox();
	$dropbox->add_arrays($num_years, $num_years);
	$dropbox->print_dropbox("year", $year);
	?>
	</td>
</tr>
<tr>
	<td colspan="2">
	<?php
	$button = new button($cmdOk, "javascript:document.forms[0].submit()");
	?>
	</td>
</tr>
</table>
</form>
<?php
if ($REQUEST_METHOD == 'POST')
{
	$date_format = $ses_date_format;
	$date_format = str_replace(" H:i", "",$ses_date_format);

	echo '<table border="0" cellpadding="4" cellspacing="0">';
	echo '<tr><td><h3>'.$strDate.'</h3></td>';
	echo '<td><h3>'.$pm_project.'</h3></td>';
	echo '<td align="right"><h3>'.$pm_hours.'</h3></td>';
	echo '<td align="right"><h3>'.$pm_starttime.'</h3></td>';
	echo '<td align="right"><h3>'.$pm_endtime.'</h3></td>';
	echo '<td align="right"><h3>'.$pm_breaktime.'</h3></td>';
	echo '<td><h3>'.$strComments.'</h3></td><td></td></tr>';


	$last_date = '';
	$total_days = 0;
	$total_hours = 0;
	$class = 'TableInside1';
	while($projects->next_record())
	{
		$break_time = date("H:i", $projects->f('break_time')+mktime(0,0,0,1,1,1970));
		$start_time = date("H:i", $projects->f('start_time'));
		$end_time = date("H:i", $projects->f('end_time'));
		$hours = ($projects->f('end_time') - $projects->f('start_time') - $projects->f('break_time'))/3600;
		$date = date($date_format, $projects->f('start_time'));

		if ($date != $last_date) $total_days++;
		$last_date = $date;


		$total_hours += $hours;
		echo '<tr class="'.$class.'"><td valign="top" nowrap>'.$date.'</td>';
		echo '<td valign="top" nowrap><a href="'.$PHP_SELF.'?post_action=project&project_id='.$projects->f('project_id').'" class="normal">'.$projects->f('project_name').'</a></td>';
		echo '<td align="right" valign="top">'.$hours.'</td>';
		echo '<td align="right" valign="top">'.$start_time.'</td>';
		echo '<td align="right" valign="top">'.$end_time.'</td>';
		echo '<td align="right" valign="top">'.$break_time.'</td>';
		echo '<td valign="top">'.text_to_html($projects->f('comments')).'</td>';
		if ($GO_MODULES->write_permissions)
		{
			echo "<td valign=\"top\"><a href='javascript:confirm_action(\"javascript:delete_hours(".$projects->f("id").")\",\"".rawurlencode($strDeletePrefix.' deze invoer '.$strDeleteSuffix)."\")' title=\"".$strDeleteItem." invoer\"><img src=\"".$GO_THEME->image_url."buttons/delete.gif\" border=\"0\"></a></td></tr>\n";
		}else
		{
			echo '<td></td></tr>';
		}

		if ($class != 'TableInside2') $class='TableInside2'; else $class='TableInside1';
	}
	echo '<tr><td colspan="5"><i>'.$pm_total_hours_days.': '.$total_hours.' / '.$total_days.'</i></td></tr>';
	echo '</table>';
}
?>
<script type="text/javascript" langauge="javascript">
function delete_hours(id)
{
	document.forms[0].delete_hours.value=id;
	document.forms[0].submit();
}
</script>
