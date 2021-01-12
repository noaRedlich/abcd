<?php
$time = time();
$year = date("Y", $time);
$month = date("m", $time);
$day = date("j", $time);
$hour = date("H", $time);
$minute = date("i", $time);

$keyword = isset($keyword) ? $keyword : '';

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
$search_on_modified_earlier_check = isset($search_on_modified_earlier) ? 'checked' : '';
$search_on_modified_later_check = isset($search_on_modified_later) ? 'checked' : '';
?>
<input type="hidden" name="task" value="search" />
<h1><img src="<?php echo $GO_THEME->image_url; ?>buttons/3.gif" border="0" height="32" width="32" />&nbsp;<?php echo $search_title; ?></h1>
<table border="0">
<tr>
	<td dir=ltr valign="top">
	<?php require('treeview.inc'); ?>
	</td>
	<td valign="top">
	<table border="0">
	<tr>
		<td></td>
		<td><?php echo $fbPath; ?>:</td>
		<td>
		<?php
		echo $page_title;
		?>
		</td>
	</tr>
	<tr>
		<td></td>
		<td><?php echo $fs_keyword; ?>:</td>
		<td>
		<input type="text" name="keyword" size="31" maxlength="255" class="textbox" value="<?php echo $keyword; ?>">
		</td>
	</tr>
	<tr>
		<td><input onclick="update_form();" type="checkbox" name="search_on_modified_later" value="true" <?php echo $search_on_modified_later_check; ?> /></td>
		<td><?php echo $fs_modified_after; ?>:</td>
		<td>
		<?php
		if (!isset($modified_later_then_month)) $modified_later_then_month = $month;
		if (!isset($modified_later_then_day)) $modified_later_then_day = $day;
		if ($ses_date_format == "m-d-Y H:i")
		{
			$dropbox = new dropbox();
			$dropbox->add_arrays($num_months, $months);
			$dropbox->print_dropbox("modified_later_then_month", $modified_later_then_month);

			$dropbox = new dropbox();
			$dropbox->add_arrays($num_days, $num_days);
			$dropbox->print_dropbox("modified_later_then_day", $modified_later_then_day);
		}else
		{
			$dropbox = new dropbox();
			$dropbox->add_arrays($num_days, $num_days);
			$dropbox->print_dropbox("modified_later_then_day", $modified_later_then_day);

			$dropbox = new dropbox();
			$dropbox->add_arrays($num_months, $months);
			$dropbox->print_dropbox("modified_later_then_month", $modified_later_then_month);
		}
		$years[4] = $year;
		$years[3] = $year-1;
		$years[2] = $year-2;
		$years[1] = $year-3;
		$years[0] = $year-4;

		$modified_later_then_year = isset($modified_later_then_year) ? $modified_later_then_year : $year;
		$dropbox = new dropbox();
		$dropbox->add_arrays($years, $years);
		$dropbox->print_dropbox("modified_later_then_year", $modified_later_then_year);
		echo '&nbsp;&nbsp;';
		if (!isset($modified_later_then_hour)) $modified_later_then_hour = $hour;
		$dropbox = new dropbox();
		$dropbox->add_arrays($hours, $hours);
		$dropbox->print_dropbox("modified_later_then_hour", $modified_later_then_hour);

		echo '&nbsp;:&nbsp;';
		$modified_later_then_min = isset($modified_later_then_min) ? $modified_later_then_min : $minute;
		$dropbox = new dropbox();
		$dropbox->add_arrays($mins, $mins);
		$dropbox->print_dropbox("modified_later_then_min", $modified_later_then_min);
		?>
		</td>
	</tr>
	<tr>
		<td><input onclick="update_form();" type="checkbox" name="search_on_modified_earlier" value="true" <?php echo $search_on_modified_earlier_check; ?> /></td>
		<td><?php echo $fs_modified_before; ?>:</td>
		<td>
		<?php
		if (!isset($modified_earlier_then_month)) $modified_earlier_then_month = $month;
		if (!isset($modified_earlier_then_day)) $modified_earlier_then_day = $day;
		if ($ses_date_format == "m-d-Y H:i")
		{
			$dropbox = new dropbox();
			$dropbox->add_arrays($num_months, $months);
			$dropbox->print_dropbox("modified_earlier_then_month", $modified_earlier_then_month);

			$dropbox = new dropbox();
			$dropbox->add_arrays($num_days, $num_days);
			$dropbox->print_dropbox("modified_earlier_then_day", $modified_earlier_then_day);
		}else
		{
			$dropbox = new dropbox();
			$dropbox->add_arrays($num_days, $num_days);
			$dropbox->print_dropbox("modified_earlier_then_day", $modified_earlier_then_day);

			$dropbox = new dropbox();
			$dropbox->add_arrays($num_months, $months);
			$dropbox->print_dropbox("modified_earlier_then_month", $modified_earlier_then_month);
		}


		$modified_earlier_then_year = isset($modified_earlier_then_year) ? $modified_earlier_then_year : $year;
		$dropbox = new dropbox();
		$dropbox->add_arrays($years, $years);
		$dropbox->print_dropbox("modified_earlier_then_year", $modified_earlier_then_year);
		echo '&nbsp;&nbsp;';
		if (!isset($modified_earlier_then_hour)) $modified_earlier_then_hour = $hour;
		$dropbox = new dropbox();
		$dropbox->add_arrays($hours, $hours);
		$dropbox->print_dropbox("modified_earlier_then_hour", $modified_earlier_then_hour);

		echo '&nbsp;:&nbsp;';
		$modified_earlier_then_min = isset($modified_earlier_then_min) ? $modified_earlier_then_min : $minute;
		$dropbox = new dropbox();
		$dropbox->add_arrays($mins, $mins);
		$dropbox->print_dropbox("modified_earlier_then_min", $modified_earlier_then_min);
		?>
		</td>
	</tr>
	<tr>
		<td colspan="3">
		<?php
		$button = new button($fs_search,'javascript:document.forms[0].submit();');
		echo '&nbsp;&nbsp;';
		$button = new button($cmdBack,"javascript:document.location='".$PHP_SELF."';");
		?>
		</td>
	</tr>
	<tr>
		<td colspan="3">
		<br />
		<?php
		if ($REQUEST_METHOD == 'POST')
		{
			if ($keyword != '')
			{
				if (isset($search_on_modified_earlier))
				{
					$modified_earlier_then = mktime($modified_earlier_then_hour, $modified_earlier_then_min,0, $modified_earlier_then_month, $modified_earlier_then_day, $modified_earlier_then_year);
				}else
				{
					$modified_earlier_then = 0;
				}

				if (isset($search_on_modified_later))
				{
					$modified_later_then = mktime($modified_later_then_hour, $modified_later_then_min,0, $modified_later_then_month, $modified_later_then_day, $modified_later_then_year);
				}else
				{
					$modified_later_then = 0;
				}
				$results = $fs->search($path, $keyword,$modified_later_then,$modified_earlier_then);
				$count = count($results);

				echo '<table border="0" cellspacing="0" cellpadding="0" width="500"><tr>';
				echo '<td colspan="2" class="TableHead2" width="16">'.$strName.'</td>';
				echo '<td colspan="2" class="TableHead2" width="16">Lokatie</td></tr>';

				while($result = array_shift($results))
				{
					if (!is_dir($result['path']))
					{
						$extension = get_file_extension($result['name']);
						echo '<tr height="18"><td width="16"><img width="16" height="16" border="0" src="'.$GO_CONFIG->host.'filetypes/icon.php?extension='.$extension.'" /></td>';
						echo '<td nowrap>&nbsp;<a target="_blank" href="download.php?path='.urlencode($result['path']).'">'.$result['name'].'</a></td>';
					}else
					{
						echo '<tr height="18"><td width="16"><img width="16" height="16" border="0" src="'.$GO_THEME->image_url.'folder.gif" /></td>';
						echo '<td nowrap>&nbsp;<a target="_self" href="'.$PHP_SELF.'?path='.urlencode($result['path']).'">'.$result['name'].'</a></td>';
					}
					$location = dirname($result['path']);

					echo '<td nowrap>&nbsp;<a href="'.$PHP_SELF.'?path='.urlencode($location).'">'.str_replace($GO_CONFIG->file_storage_path,$GO_CONFIG->slash,$location).'</a></td></tr>';
					echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
				}
				echo '<tr><td colspan="99" class="small" height="18">&nbsp;'.$count.' '.$fbItems.'</td></tr>';
				echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
				echo '</table>';
			}else
			{
				echo '<p class="Error">'.$fs_enter_keyword.'</p>';
			}
		}
		?>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>

<script type="text/javascript" language="javascript">
function update_form()
{
	document.forms[0].modified_earlier_then_min.disabled = !document.forms[0].search_on_modified_earlier.checked;
	document.forms[0].modified_earlier_then_hour.disabled = !document.forms[0].search_on_modified_earlier.checked;
	document.forms[0].modified_earlier_then_month.disabled = !document.forms[0].search_on_modified_earlier.checked;
	document.forms[0].modified_earlier_then_day.disabled = !document.forms[0].search_on_modified_earlier.checked;
	document.forms[0].modified_earlier_then_year.disabled = !document.forms[0].search_on_modified_earlier.checked;

	document.forms[0].modified_later_then_min.disabled = !document.forms[0].search_on_modified_later.checked;
	document.forms[0].modified_later_then_hour.disabled = !document.forms[0].search_on_modified_later.checked;
	document.forms[0].modified_later_then_month.disabled = !document.forms[0].search_on_modified_later.checked;
	document.forms[0].modified_later_then_day.disabled = !document.forms[0].search_on_modified_later.checked;
	document.forms[0].modified_later_then_year.disabled = !document.forms[0].search_on_modified_later.checked;
}
update_form();
</script>