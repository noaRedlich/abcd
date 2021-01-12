<?php
$max_rows = 10;

$events = array();
$keys = array();
$key=0;
$event_count = $scheduler->get_all_events($scheduler_id);
while ($scheduler->next_record())
{
	$starting_date_array = explode('-',$scheduler->f('starting_date'));
	$ending_date_array = explode('-',$scheduler->f('ending_date'));

	$starting_time = mktime($scheduler->f('start_hour'),$scheduler->f('start_min'),0,$starting_date_array[1],$starting_date_array[2],$starting_date_array[0]);
	$ending_time = mktime($scheduler->f('end_hour'),$scheduler->f('end_min'),0,$ending_date_array[1],$ending_date_array[2],$ending_date_array[0]);

	$event['title'] = $scheduler->f('title');
	$event['type'] = $scheduler->f('type');
	$event['id'] = $scheduler->f('id');

	switch($scheduler->f('type'))
	{
		case 'once':
			$date = explode('-',$scheduler->f('start_date'));
			$event['time'] = mktime($scheduler->f('start_hour'),$scheduler->f('start_min'),0,$date[1],$date[2],$date[0]);
			$event_time = $event['time'];
			while (isset($events[$event_time]))
			{
				$event_time++;
			}
			$events[$event_time] = $event;
			$keys[$key] = $event_time;
			$events[$event_time] = $event;
			$keys[$key] = $event_time;
			$range = abs($time-$event_time);
			if (!isset($smallest_range))
			{
				$smallest_range = $range;
				$nearest_event_time= $event_time;
			}else
			{
				if ($range < $smallest_range)
				{
					$smallest_range = $range;
					$nearest_event_time= $event_time;
				}
			}
			$key++;
		break;

		case 'weekly':
			$fields[0] = 'sunday';
			$fields[1] = 'monday';
			$fields[2] = 'tuesday';
			$fields[3] = 'wednesday';
			$fields[4] = 'thursday';
			$fields[5] = 'friday';
			$fields[6] = 'saturday';


			if (($starting_time < $time) && (($time < $ending_time) || ($scheduler->f('noend') == 1)))
			{
				$weekday = date("w", mktime(0,0,0,$current_month,$current_day,$current_year));

				for ($i=0;$i<7;$i++)
				{
					$_weekday = $weekday+$i;
					if ($_weekday > 6)
					{
						$_weekday = $_weekday-7;
					}
					if ($scheduler->f($fields[$_weekday]) == 1)
					{
						$event['time'] = mktime($scheduler->f('start_hour'),$scheduler->f('start_min'),0,$current_month,$current_day+$i,$current_year);
						$event_time = $event['time'];
						while (isset($events[$event_time]))
						{
							$event_time++;
						}
						$events[$event_time] = $event;
						$keys[$key] = $event_time;
						$events[$event_time] = $event;
						$keys[$key] = $event_time;
						$range = abs($time-$event_time);
						if (!isset($smallest_range))
						{
							$smallest_range = $range;
							$nearest_event_time= $event_time;
						}else
						{
							if ($range < $smallest_range)
							{
								$smallest_range = $range;
								$nearest_event_time= $event_time;
							}
						}
						$key++;
					}
				}
			}else
			{
				$weekday = date("w", $ending_time);

				for ($i=6;$i>-1;$i--)
				{
					$_weekday = $weekday-$i;
					if ($_weekday < 0)
					{
						$_weekday = $_weekday+7;
					}
					if ($scheduler->f($fields[$_weekday]) == 1)
					{
						$event['time'] = mktime($scheduler->f('start_hour'),$scheduler->f('start_min'),0,$ending_date_array[1],$ending_date_array[2]-$i,$ending_date_array[0]);
						$event_time = $event['time'];
						while (isset($events[$event_time]))
						{
							$event_time++;
						}
						$events[$event_time] = $event;
						$keys[$key] = $event_time;
						$events[$event_time] = $event;
						$keys[$key] = $event_time;
						$range = abs($time-$event_time);
						if (!isset($smallest_range))
						{
							$smallest_range = $range;
							$nearest_event_time= $event_time;
						}else
						{
							if ($range < $smallest_range)
							{
								$smallest_range = $range;
								$nearest_event_time= $event_time;
							}
						}
						$key++;
					}
				}
			}
		break;

		case 'daily':
			if (($starting_time < $time) && (($time < $ending_time) || ($scheduler->f('noend') == 1)))
			{
				$event['time'] = mktime($scheduler->f('start_hour'),$scheduler->f('start_min'),0,$current_month,$current_day,$current_year);
			}else
			{
				$event['time'] = mktime($scheduler->f('start_hour'),$scheduler->f('start_min'),0,$ending_date_array[1],$ending_date_array[2],$ending_date_array[0]);
			}
			$event_time = $event['time'];
			while (isset($events[$event_time]))
			{
				$event_time++;
			}
			$events[$event_time] = $event;
			$keys[$key] = $event_time;
			$events[$event_time] = $event;
			$keys[$key] = $event_time;
			$range = abs($time-$event_time);
			if (!isset($smallest_range))
			{
				$smallest_range = $range;
				$nearest_event_key = key;
			}else
			{
				if ($range < $smallest_range)
				{
					$smallest_range = $range;
					$nearest_event_time= $event_time;
				}
			}

			$key++;
		break;

		case 'month_date':
			$date_array = explode('-', $scheduler->f('start_date'));
			if (($starting_time < $time) && (($time < $ending_time) || ($scheduler->f('noend') == 1)))
			{
				$event['time'] = mktime($scheduler->f('start_hour'),$scheduler->f('start_min'),0,$current_month,$date_array[2],$current_year);
			}else
			{
				$event['time'] = mktime($scheduler->f('start_hour'),$scheduler->f('start_min'),0,$ending_date_array[1],$date_array[2],$ending_date_array[0]);
			}
			$event_time = $event['time'];
			while (isset($events[$event_time]))
			{
				$event_time++;
			}
			$events[$event_time] = $event;
			$keys[$key] = $event_time;
			$events[$event_time] = $event;
			$keys[$key] = $event_time;
			$range = abs($time-$event_time);
			if (!isset($smallest_range))
			{
				$smallest_range = $range;
				$nearest_event_time= $event_time;
			}else
			{
				if ($range < $smallest_range)
				{
					$smallest_range = $range;
					$nearest_event_time= $event_time;
				}
			}
			$key++;
		break;

		case 'month_day':
			$fields[0] = 'sunday';
			$fields[1] = 'monday';
			$fields[2] = 'tuesday';
			$fields[3] = 'wednesday';
			$fields[4] = 'thursday';
			$fields[5] = 'friday';
			$fields[6] = 'saturday';

			if (($starting_time < $time) && (($time < $ending_time) || ($scheduler->f('noend') == 1)))
			{
				$current_month_time = ceil($current_day/7);
				$mk_month = $current_month;
				if ($scheduler->f('month_time') <= $current_month_time)
				{
					$mk_month++;
				}
				$weekday = date("w", $ending_time);

				$add_days = ($scheduler->f('month_time')-1)*7;
				$first_weekday_of_month = date("w", mktime(0,0,0,$mk_month, 1, $current_year));
				$last_day_of_month = date('j', mktime(0,0,0,$mk_month+1, -1, $current_year));
				for ($i=0;$i<7;$i++)
				{
					$_weekday = $first_weekday_of_month+$i;
					if ($_weekday > 6)
					{
						$_weekday = $_weekday-7;
					}
					if ($scheduler->f($fields[$_weekday]) == 1)
					{
						$month_day = $i+$add_days+1;
						if ($month_day < $last_day_of_month)
						{
							$event['time'] = mktime($scheduler->f('start_hour'),$scheduler->f('start_min'),0,$mk_month,$month_day,$current_year);
							$event_time = $event['time'];
							while (isset($events[$event_time]))
							{
								$event_time++;
							}
							$events[$event_time] = $event;
							$keys[$key] = $event_time;
							$range = abs($time-$event_time);
							if (!isset($smallest_range))
							{
								$smallest_range = $range;
								$nearest_event_time= $event_time;
							}else
							{
								if ($range < $smallest_range)
								{
									$smallest_range = $range;
									$nearest_event_time= $event_time;
								}
							}
							$key++;
						}
					}
				}
			}else
			{
				$current_month_time = ceil($ending_date_array[2]/7);
				$mk_month = $ending_date_array[1];
				$weekday = date("w", $ending_time);

				$add_days = ($scheduler->f('month_time')-1)*7;
				$first_weekday_of_month = date("w", mktime(0,0,0,$mk_month, 1, $ending_date_array[0]));
				$last_day_of_month = date('j', mktime(0,0,0,$mk_month+1, -1, $ending_date_array[0]));
				for ($i=0;$i<7;$i++)
				{
					$_weekday = $first_weekday_of_month+$i;
					if ($_weekday > 6)
					{
						$_weekday = $_weekday-7;
					}
					if ($scheduler->f($fields[$_weekday]) == 1)
					{
						$month_day = $i+$add_days+1;
						if ($month_day < $last_day_of_month)
						{
							$event['time'] = mktime($scheduler->f('start_hour'),$scheduler->f('start_min'),0,$mk_month,$month_day,$ending_date_array[0]);
							$event_time = $event['time'];
							while (isset($events[$event_time]))
							{
								$event_time++;
							}
							$events[$event_time] = $event;
							$keys[$key] = $event_time;
							$range = abs($time-$event_time);
							if (!isset($smallest_range))
							{
								$smallest_range = $range;
								$nearest_event_time= $event_time;
							}else
							{
								if ($range < $smallest_range)
								{
									$smallest_range = $range;
									$nearest_event_time= $event_time;
								}
							}
							$key++;
						}
					}
				}
			}
		break;

		case 'yearly';
			$date_array = explode('-', $scheduler->f('start_date'));
			if (($starting_time < $time) && (($time < $ending_time) || ($scheduler->f('noend') == 1)))
			{
				$event['time'] = mktime($scheduler->f('start_hour'),$scheduler->f('start_min'),0,$date_array[1],$date_array[2],$current_year);
			}else
			{
				$event['time'] = mktime($scheduler->f('start_hour'),$scheduler->f('start_min'),0,$date_array[1],$date_array[2],$ending_date_array[0]);
			}
			$event_time = $event['time'];
			while (isset($events[$event_time]))
			{
				$event_time++;
			}

			$events[$event_time] = $event;
			$keys[$key] = $event_time;
			$range = abs($time-$event_time);
			if (!isset($smallest_range))
			{
				$smallest_range = $range;
				$nearest_event_time= $event_time;
			}else
			{
				if ($range < $smallest_range)
				{
					$smallest_range = $range;
					$nearest_event_time= $event_time;
				}
			}
			$key++;
		break;
	}


}
$event_count = count($keys);
sort($keys);
$nearest_event_key = array_search($nearest_event_time, $keys);
$default_key = floor($nearest_event_key/$max_rows)*$max_rows;
$first = isset($first) ? $first : $default_key;

$links = '';
$last = 0;
if ($event_count > $max_rows)
{
	$next_start = $first+$max_rows;
	$previous_start = $first-$max_rows;
	if ($first != 0)
	{
		$links .= '<a href="'.$PHP_SELF.'?post_action=show_events&scheduler_id='.$scheduler_id.'&first='.$previous_start.'">&lt&lt '.$cmdPrevious.'</a>&nbsp;';
	}else
	{
		$links .= '<font color="#cccccc">&lt&lt '.$cmdPrevious.'</font>&nbsp;';
	}
	for ($i=0;$i<$event_count;$i+=$max_rows)
	{
		$page = ($i/$max_rows)+1;
		if ($i==$first)
		{
			$links .= '<b><i>'.$page.'</i></b>&nbsp;';
		}else
		{
			$links .= '<a href="'.$PHP_SELF.'?post_action=show_events&scheduler_id='.$scheduler_id.'&first='.$i.'">'.$page.'</a>&nbsp;';
		}
	}


	if ($event_count > $next_start)
	{
		$links .= '<a href="'.$PHP_SELF.'?post_action=show_events&scheduler_id='.$scheduler_id.'&first='.$next_start.'">'.$cmdNext.' &gt&gt</a>';
	}else
	{
		$links .= '<font color="#cccccc">'.$cmdNext.' &gt&gt</font>';
	}
	$last = $first+$max_rows;
	if ($last > $event_count)
	{
		$last = $event_count;
	}
}else
{
	$last = $event_count;
}

?>
<form method="post" action="<?php echo $PHP_SELF; ?>">
<table border="0" cellspacing="0" cellpadding="0" width="600">
<tr>
	<td colspan="4" align="right" class="small"><?php echo $event_count.' '.$sc_events; ?></td>
</tr>
<tr height="20">
	<td class="TableHead2">
		<?php echo $strDate; ?>
	</td>
	<td class="TableHead2">
	<?php echo $sc_title; ?>
	</td>
	<td class="TableHead2">
	<?php echo $sc_type; ?>
	</td>
</tr>

<?php
for ($i=$first;$i<$last;$i++)
{
	$event_day = date('j',$events[$keys[$i]]['time']);
	$event_month = date('m',$events[$keys[$i]]['time']);
	$event_year = date('Y',$events[$keys[$i]]['time']);

	echo '<tr height="20" class="Table1">';
	echo '<td><a class="normal" href="'.$PHP_SELF.'?post_action=day&scheduler_id='.$scheduler_id.'&day='.$event_day.'&month='.$event_month.'&year='.$event_year.'">'.date($ses_date_format,$events[$keys[$i]]['time']).'</a></td>';
	echo '<td>'.$events[$keys[$i]]['title'].'</td><td>'.$sc_types[$events[$keys[$i]]['type']].'</td></tr>';
	echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
}
if ($links != '')
{
	echo '<tr><td colspan="99"align="center">'.$links.'</td></tr>';
	echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';

}
echo '</table></form>';
?>

<script type="text/javascript" language="javascript">
<!--

function item_click(check_box)
{
	var item = get_object(check_box.value);
	if (check_box.checked)
	{
		item.className = 'Table2';
	}else
	{
		item.className = 'Table1';
	}
}

function invert_selection()
{
	for (var i=0;i<document.forms[0].elements.length;i++)
	{
		if(document.forms[0].elements[i].type == 'checkbox' && document.forms[0].elements[i].name != 'dummy')
		{
			document.forms[0].elements[i].checked = !(document.forms[0].elements[i].checked);
			item_click(document.forms[0].elements[i]);
		}
	}
}

//-->
</script>