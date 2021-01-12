<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

//$week_number = intval(strftime("%V",mktime (0,0,0,$month,$day,$year)));
$week_number = date("W", mktime(0,0,0,$month,$day,$year));
$next_week = $day+7;
$previous_week = $day-7;
$week_day = date("w", mktime(0,0,0,$month,$day,$year));
if($week_day == 0)
	$week_day = 7;
echo '<table cellpadding="0" cellspacing="1" class="TableBorder" width="100%">';
echo '<tr><td class="TableInside2" colspan="8" height="20" align="center"><a href="javascript:post_week('.$previous_week.')">&lt&lt</a>&nbsp;Week '.$week_number.'&nbsp;<a href="javascript:post_week('.$next_week.')">&gt&gt</a></td>';
echo '<tr><td class="TableInside2" width="40">&nbsp;</td>';
$day_number = $daystart;
$day_count = $daystart;
$date_format = str_replace(" H:i", "",$ses_date_format);

for ($n=0;$n<7;$n++)
{
	$old_day = $day-$week_day+$day_count;
	$old_month = $month;
	$old_year = $year;

	$display_day = $day-$week_day+$day_count;
	$new_year = date("Y", mktime(0,0,0,$month,$display_day,$year));
	$new_month= date("m", mktime(0,0,0,$month,$display_day,$year));
	$display_day = date("d", mktime(0,0,0,$month,$display_day,$year));

	$date = date($date_format, mktime(0,0,0,$new_month,$display_day,$new_year));


	if ($day_number == 7) $day_number = 0;

	$notime_events[$day_number] = '';

	if ($display_day == $current_day && $new_month==$current_month && $new_year==$current_year)
		$class="HoverLinkSelected";
	else
		$class="HoverLink";

	echo '<td align="center"><a href="javascript:post_day('.$display_day.')" class="'.$class.'">'.$full_days[$day_number].'<br />'.$date.'</a></td>';


	$scheduler->get_events($scheduler_id, $display_day, $new_month, $new_year, $day_number);

	while ($scheduler->next_record())
	{
		if ($scheduler->f("user_id") == $GO_SECURITY->user_id)
		{
			$owner = true;
		}else
		{
			$owner = false;
		}
		$write_perm = $GO_SECURITY->has_permission($GO_SECURITY->user_id, $scheduler->f("acl_write"));
		$read_perm = $GO_SECURITY->has_permission($GO_SECURITY->user_id, $scheduler->f("acl_read"));
		if ($read_perm || $write_perm)
		{
			if ($scheduler->f('notime') == 1)
			{
				if ($write_perm)
				{
					$notime_events[$day_number] .= '<b>'.$scheduler->f('title').'</b>&nbsp;<a href="javascript:load_event('.$scheduler->f('id').')"><img src="'.$GO_THEME->image_url.'buttons/edit.gif" border="0" /></a>&nbsp;';

					if ($scheduler_write)
					{
						$notime_events[$day_number] .= "<a href='javascript:popup(\"delete_event.php?scheduler_id=".$scheduler_id."&event_id=".$scheduler->f("id")."\",\"500\",\"150\")' title=\"".$strDeleteItem." '".$scheduler->f("title")."'\"><img src=\"".$GO_THEME->image_url."buttons/delete.gif\" border=\"0\"></a>";
					}else
					{
						$notime_events[$day_number] .= "<a href='javascript:delete_event(\"".$scheduler->f("id")."\",\"".rawurlencode($strDeletePrefix."'".$scheduler->f("title")."'".$strDeleteSuffix)."\")' title=\"".$strDeleteItem." '".$scheduler->f("title")."'\"><img src=\"".$GO_THEME->image_url."buttons/delete.gif\" border=\"0\"></a>";
					}
					$notime_events[$day_number] .= "<br /><a href=\"javascript:popup('participants.php?event_id=".$scheduler->f('id')."','500','300')\" class=\"small_underline\">".$sc_participants."</a>";

					if ($scheduler->f('location_text') != '')
					{
						$notime_events[$day_number] .= '<br />'.$sc_location.':&nbsp;'.$scheduler->f('location_text');
					}
					$notime_events[$day_number] .= '<br /><br /><a href="javascript:post_day('.$display_day.')">'.nl2br(cut_string($scheduler->f('description'),50)).'</a><br /><br />';
				}else
				{
					$notime_events[$day_number] .= '<b>'.$scheduler->f('title').'</b>';
					if ($scheduler_write)
					{
						$notime_events[$day_number] .= '<a href=\'javascript:unsubscribe_event('.$scheduler->f('id').',"'.rawurlencode($strDeletePrefix."'".$scheduler->f("title")."'".$strDeleteSuffix).'")\' title="'.$strDeleteItem.' \''.$scheduler->f("title").'\'"><img src="'.$GO_THEME->image_url.'buttons/delete.gif" border="0"></a>';
					}
					$notime_events[$day_number] .= "<br /><a href=\"javascript:popup('participants.php?event_id=".$scheduler->f('id')."','500','300')\" class=\"small_underline\">".$sc_participants."</a>";

					if ($scheduler->f('location_text') != '')
					{
						$notime_events[$day_number] .= '<br />'.$sc_location.':&nbsp;'.$scheduler->f('location_text');
					}
					$notime_events[$day_number] .= '<br /><br /><a href="javascript:post_day('.$display_day.')">'.nl2br(cut_string($scheduler->f('description'),50)).'</a><br /><br />';
				}

			}else
			{
				if (strlen($new_month) == 1) $new_month = '0'.$new_month;
				if (strlen($display_day) == 1) $display_day = '0'.$display_day;

				switch ($scheduler->f('type'))
				{
					case 'once':
						$calc_date = $new_year.'-'.$new_month.'-'.$display_day;
					break;

					case 'month_date':
						$calc_date = '0000-00-'.$display_day;

					break;

					case 'yearly':
						$calc_date = '0000-'.$new_month.'-'.$display_day;
					break;

					default:
						$calc_date = '0000-00-00';
					break;

				}

				if ($scheduler->f('start_date') < $calc_date)
				{
					$start_hour = 0;
					$start_min = '00';
				}else
				{
					$start_hour = $scheduler->f('start_hour');
					$start_min = $scheduler->f('start_min');
					if (strlen($start_min) == 1) $start_min = '0'.$start_min;
				}

				if ($scheduler->f('end_date') > $calc_date)
				{
					$end_hour = 23;
					$end_min = '00';
				}else
				{
					$end_hour = $scheduler->f('end_hour');
					if ($scheduler->f('end_min')==0)
						$end_hour--;

					$end_min = $scheduler->f('end_min');
					if (strlen($end_min) == 1) $end_min = '0'.$end_min;
				}

				if ($write_perm)
				{
					$innerHTML = '<b>'.$scheduler->f('title').'</b>&nbsp;<a href="javascript:load_event('.$scheduler->f('id').')"><img src="'.$GO_THEME->image_url.'buttons/edit.gif" border="0" /></a>&nbsp;';

					if ($scheduler_write)
					{
						$innerHTML .= "<a href='javascript:popup(\"delete_event.php?scheduler_id=".$scheduler_id."&event_id=".$scheduler->f("id")."\",\"500\",\"150\")' title=\"".$strDeleteItem." '".$scheduler->f("title")."'\"><img src=\"".$GO_THEME->image_url."buttons/delete.gif\" border=\"0\"></a>";
					}else
					{
						$innerHTML .= "<a href='javascript:delete_event(\"".$scheduler->f("id")."\",\"".rawurlencode($strDeletePrefix."'".$scheduler->f("title")."'".$strDeleteSuffix)."\")' title=\"".$strDeleteItem." '".$scheduler->f("title")."'\"><img src=\"".$GO_THEME->image_url."buttons/delete.gif\" border=\"0\"></a>";
					}
					$innerHTML .= '<br />'.$scheduler->f('start_hour').':'.$start_min.' - '.$scheduler->f('end_hour').':'.$end_min;
					$innerHTML .= "<br /><a href=\"javascript:popup('participants.php?event_id=".$scheduler->f('id')."','500','300')\" class=\"small_underline\">".$sc_participants."</a>";

					if ($scheduler->f('location_text') != '')
					{
						$innerHTML .= '<br />'.$sc_location.':&nbsp;'.$scheduler->f('location_text');
					}
					$innerHTML .= '<br /><br /><a href="javascript:post_day('.$display_day.')">'.nl2br(cut_string($scheduler->f('description'),50)).'</a><br /><br />';
				}else
				{
					$innerHTML  = '<b>'.$scheduler->f('title').'</b>';
					if ($scheduler_write)
					{
						$innerHTML .= '<a href=\'javascript:unsubscribe_event('.$scheduler->f('id').',"'.rawurlencode($strDeletePrefix."'".$scheduler->f("title")."'".$strDeleteSuffix).'")\' title="'.$strDeleteItem.' \''.$scheduler->f("title").'\'"><img src="'.$GO_THEME->image_url.'buttons/delete.gif" border="0"></a>';
					}
					$innerHTML .= '<br />'.$scheduler->f('start_hour').':'.$start_min.' - '.$scheduler->f('end_hour').':'.$end_min;
					$innerHTML .= "<br /><a href=\"javascript:popup('participants.php?event_id=".$scheduler->f('id')."','500','300')\" class=\"small_underline\">".$sc_participants."</a>";

					if ($scheduler->f('location_text') != '')
					{
						$innerHTML .= '<br />'.$sc_location.':&nbsp;'.$scheduler->f('location_text');
					}
					$innerHTML .= '<br /><br /><a href="javascript:post_day('.$display_day.')">'.nl2br(cut_string($scheduler->f('description'),50)).'</a><br /><br />';
				}

				if (isset($events[$day_number][$start_hour]))
				{
					if (isset($events[$day_number][$start_hour]['reference']))
						$event_hour = $events[$day_number][$start_hour]['reference'];
					else
						$event_hour = $start_hour;

					$events[$day_number][$event_hour]['innerHTML'] .= $innerHTML;
					if (($end_hour - $event_hour + 1) > $events[$day_number][$event_hour]['rowspan'])
						$events[$day_number][$event_hour]['rowspan'] = $end_hour - $event_hour + 1;

					for ($i=$event_hour+1;$i<=$end_hour;$i++)
					{
						$events[$day_number][$i]['reference'] = $start_hour;
					}

				}else
				{
					$rowspan = $end_hour - $start_hour + 1;
					$events[$day_number][$start_hour]['innerHTML'] = $innerHTML;
					$events[$day_number][$start_hour]['rowspan'] = $rowspan;
					for ($i=$start_hour+1;$i<=$end_hour;$i++)
					{
						$events[$day_number][$i]['reference'] = $start_hour;
					}
				}
			}
		}
	}

	$day_number++;
	$day_count++;
}
echo '</tr>';

$day_number = $daystart;

echo '<tr><td class="TableInside2">&nbsp;</td>';
for ($i=0;$i<7;$i++)
{
	if ($day_number == 7) $day_number = 0;
	if (isset($notime_events[$day_number]))
	{
		echo '<td class="TableInside4" valign="top">'.$notime_events[$day_number].'</td>';
	}else
	{
		echo '<td class="TableInside2">&nbsp;</td>';
	}
	$day_number++;
}

for ($i=0;$i<24;$i++)
{
	echo '<tr><td class="TableInside2" align="right" valign="top">'.$i.':00</td>';

	$day_number = $daystart;

	for ($n=0;$n<7;$n++)
	{
		if ($day_number == 7) $day_number = 0;

		if (isset($events[$day_number][$i]))
		{
			if (!isset($events[$day_number][$i]['reference']))
			{
				echo '<td valign="top" class="TableInside4" rowspan="'.$events[$day_number][$i]['rowspan'].'">';
				echo $events[$day_number][$i]['innerHTML'].'</td>';
			}
		}else
		{
			if (($i>7) && ($i<18) && $day_number != 6 && $day_number != 5)
				$class="TableInside3";
			else
				$class="TableInside1";

			echo '<td class="'.$class.'">&nbsp;</td>';
		}

		$day_number++;
	}
	echo "</tr>\n";
}
?>
</table>

