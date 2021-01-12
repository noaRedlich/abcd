<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

$time = time();
$today = date("j", $time);
$this_year = date("Y", $time);
$this_month = date("m", $time);

$day = isset($day) ? $day : $today;
$month = isset($month) ? $month : $this_month;
$year = isset($year) ? $year : $this_year;

$date_format = $ses_date_format;
$date_format = str_replace(" H:i", "",$ses_date_format);
$date = date($date_format, mktime(0,0,0,$month,$day,$year));

$project_id = isset($project_id) ? $project_id : 0;
$projects_dropbox = new dropbox();
$count = $projects->get_projects();

if ($GO_MODULES->write_permissions || (($time - $new_time) < 8*24*60*60))
{
	$enabled = true;
}else
{
	$enabled = false;
}

if ($count > 0)
{
	while ($projects->next_record())
	{
		if ($GO_SECURITY->has_permission($GO_SECURITY->user_id, $projects->f('acl_read')) || $GO_SECURITY->has_permission($GO_SECURITY->user_id, $projects->f('acl_write')))
		{
			$projects_dropbox->add_value($projects->f('id'),$projects->f('name'));
		}else
		{
			$count--;
		}
	}
}
if (!$enabled)
{
	echo '<p class="Error">'.$pm_too_old.'</p>';
}
?>
<form name="book" action="<?php echo $PHP_SELF; ?>" method="post">
<input type="hidden" name="post_action" />
<?php
if ($count > 0)
{
?>
	<table border="0" cellpadding="0" cellspacing="0" height="300" width="100%">
	<tr>
		<td valign="top">
		<?php
			require('calendar.inc');
		?>
		</td>
		<td class="TableInside" valign="top">

		<table border="0" cellpadding="0" cellspacing="5">

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
			$dropbox->print_dropbox('pm_user_id',$pm_user_id, 'onchange="document.forms[0].submit()"');
			echo '</td></tr>';
		}else
		{
			echo '<input type="hidden" name="pm_user_id" value="'.$GO_SECURITY->user_id.'" />';
		}
		?>
		<tr>
			<td>
			<?php echo $pm_project; ?>:
			</td>
			<td>
			<?php
			$projects_dropbox->print_dropbox('project_id', $project_id);
			?>
			</td>
		</tr>


		<tr>
			<td><?php echo $pm_starttime; ?>:</td>
			<td>
			<?php
			$hours = array("00","01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23");
			$mins = array("00","15","30","45");
			$start_hour = isset($start_hour) ? $start_hour : 8;
			$dropbox = new dropbox();
			$dropbox->add_arrays($hours, $hours);
			$dropbox->print_dropbox("start_hour", $start_hour);

			echo '&nbsp;:&nbsp;';
			$start_min = isset($start_min) ? $start_min : 0;
			$dropbox = new dropbox();
			$dropbox->add_arrays($mins, $mins);
			$dropbox->print_dropbox("start_min", $start_min);
			?>
			</td>
		</tr>

		<tr>
			<td><?php echo $pm_endtime; ?>:</td>
			<td>
			<?php
			$end_hour = isset($end_hour) ? $end_hour : 17;
			$dropbox = new dropbox();
			$dropbox->add_arrays($hours, $hours);
			$dropbox->print_dropbox("end_hour", $end_hour);

			echo '&nbsp;:&nbsp;';
			$end_min = isset($end_min) ? $end_min : 0;
			$dropbox = new dropbox();
			$dropbox->add_arrays($mins, $mins);
			$dropbox->print_dropbox("end_min", $end_min);
			?>
			</td>
		</tr>
		<tr>
			<td><?php echo $pm_breaktime; ?>:</td>
			<td>
			<?php
			$break_hours = isset($break_hours) ? $break_hours : 0;
			$dropbox = new dropbox();
			$dropbox->add_arrays($hours, $hours);
			$dropbox->print_dropbox("break_hours", $break_hours);

			echo '&nbsp;:&nbsp;';
			$break_mins = isset($break_mins) ? $break_mins : 0;
			$dropbox = new dropbox();
			$dropbox->add_arrays($mins, $mins);
			$dropbox->print_dropbox("break_mins", $break_mins);
			?>
			</td>
		</tr>

		<tr>
			<td valign="top"><?php echo $strComments; ?>:</td>
			<td>
			<textarea class="textbox" name="comments" cols="40" rows="4"><?php if (isset($comments)) echo $comments; ?></textarea>
			</td>
		</tr>
		<?php
		if ($enabled)
		{
			echo '<tr><td colspan="2">';
			$button = new button($cmdSave,"javascript:save_hours()");
			echo '</td></tr>';
		}
		?>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<?php echo $pm_this_week; ?>:<br /><br />
		<?php
		echo '<table border="0" cellpadding="4" cellspacing="0">';
		echo '<tr><td><h3>'.$strDate.'</h3></td>';
		echo '<td><h3>'.$pm_project.'</h3></td>';
		echo '<td align="right"><h3>'.$pm_hours.'</h3></td>';
		echo '<td align="right"><h3>'.$pm_starttime.'</h3></td>';
		echo '<td align="right"><h3>'.$pm_endtime.'</h3></td>';
		echo '<td align="right"><h3>'.$pm_breaktime.'</h3></td>';
		echo '<td><h3>'.$strComments.'</h3></td><td></td></tr>';

		$weekday = date("w", $time);

		$total_hours = 0;
		$class = 'TableInside1';


		for ($i=7;$i>=0;$i--)
		{
			$day_of_month = $today - $i;

			$day_unix_start = mktime(0,0,0,$this_month, $day_of_month, $this_year);
			$day_unix_end = mktime(0,0,0,$this_month, $day_of_month+1, $this_year);

			$weekday = date("w", $day_unix_start);

			$projects->get_user_hours($pm_user_id, $day_unix_start, $day_unix_end);
			while($projects->next_record())
			{
				$break_time = date("H:i", $projects->f('break_time')+mktime(0,0,0,1,1,1970));
				$start_time = date("H:i", $projects->f('start_time'));
				$end_time = date("H:i", $projects->f('end_time'));
				$hours = ($projects->f('end_time') - $projects->f('start_time') - $projects->f('break_time'))/3600;
				$date = date($date_format, $projects->f('start_time'));

				$total_hours += $hours;
				echo '<tr class="'.$class.'"><td valign="top" nowrap>'.$full_days[$weekday].' '.$date.'</td>';
				echo '<td valign="top" nowrap><a href="'.$PHP_SELF.'?post_action=project&project_id='.$projects->f('project_id').'" class="normal">'.$projects->f('project_name').'</a></td>';
				echo '<td align="right" valign="top">'.$hours.'</td>';
				echo '<td align="right" valign="top">'.$start_time.'</td>';
				echo '<td align="right" valign="top">'.$end_time.'</td>';
				echo '<td align="right" valign="top">'.$break_time.'</td>';
				echo '<td valign="top">'.text_to_html($projects->f('comments')).'</td>';
				echo "<td valign=\"top\"><a href='javascript:confirm_action(\"".$PHP_SELF."?post_action=book&delete_hours=".$projects->f("id")."\",\"".rawurlencode($strDeletePrefix.' deze invoer '.$strDeleteSuffix)."\")' title=\"".$strDeleteItem." invoer\"><img src=\"".$GO_THEME->image_url."buttons/delete.gif\" border=\"0\"></a></td></tr>\n";

				if ($class != 'TableInside2') $class='TableInside2'; else $class='TableInside1';
			}
		}

		echo '<tr><td colspan="5"><i>'.$pm_total_hours.': '.$total_hours.'</i></td></tr>';
		echo '</table>';

		echo '</td></tr></table>';
	}else
	{
		echo $pm_no_projects;
	}
	?>


</form>
<script type="text/javascript" language="javascript">
function save_hours()
{
	document.forms[0].post_action.value = 'save_hours';
	document.forms[0].submit();
}
</script>