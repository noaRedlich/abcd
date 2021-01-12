<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

echo '<table cellpadding="0" cellspacing="1" class="TableBorder" width="100%"><tr>';

$day_count = $daystart;
$date_format = $ses_date_format;
$date_format = str_replace(" H:i", "",$ses_date_format);

$date = date($date_format, mktime(0,0,0,$month,$day,$year));
$day_number = date("w", mktime(0,0,0,$month,$day,$year));

$nextday = $day+1;
$previousday = $day-1;
echo '<td class="TableInside2" width="40">&nbsp;</td><td align="center" height="20" class="TableInside2"><a href="javascript:post_day('.$previousday.')">&lt&lt</a>&nbsp;'.$full_days[$day_number].' '.$date.'&nbsp;<a href="javascript:post_day('.$nextday.')">&gt&gt</a></td>';
echo '</tr>';

$scheduler->get_events($scheduler_id, $day, $month, $year, $day_number);
$notime_events = '';
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
				$notime_events .= '<b>'.$scheduler->f('title').'</b>&nbsp;<a href="javascript:load_event('.$scheduler->f('id').')"><img src="'.$GO_THEME->image_url.'buttons/edit.gif" border="0" /></a>&nbsp;';
				if ($scheduler_write)
				{
					$notime_events .= "<a href='javascript:popup(\"delete_event.php?scheduler_id=".$scheduler_id."&event_id=".$scheduler->f("id")."\",\"500\",\"150\")' title=\"".$strDeleteItem." '".$scheduler->f("title")."'\"><img src=\"".$GO_THEME->image_url."buttons/delete.gif\" border=\"0\"></a>";
				}else
				{
					$notime_events .= "<a href='javascript:delete_event(\"".$scheduler->f("id")."\",\"".rawurlencode($strDeletePrefix."'".$scheduler->f("title")."'".$strDeleteSuffix)."\")' title=\"".$strDeleteItem." '".$scheduler->f("title")."'\"><img src=\"".$GO_THEME->image_url."buttons/delete.gif\" border=\"0\"></a>";
				}
				$notime_events .= "<br /><a href=\"javascript:popup('participants.php?event_id=".$scheduler->f('id')."','500','300')\" class=\"small_underline\">".$sc_participants."</a>";

				if ($scheduler->f('location_text') != '')
				{
					$notime_events .= '<br />'.$sc_location.':&nbsp;'.$scheduler->f('location_text');
				}
				$notime_events .= '<br /><br />'.text_to_html($scheduler->f('description')).'<br /><br />';
			}else
			{
				$notime_events .= '<b>'.$scheduler->f('title').'</b>';
				$notime_events .= "<br /><a href=\"javascript:popup('participants.php?event_id=".$scheduler->f('id')."','500','300')\" class=\"small_underline\">".$sc_participants."</a>";
				if ($scheduler_write)
				{
					$notime_events .= '<a href=\'javascript:unsubscribe_event('.$scheduler->f('id').',"'.rawurlencode($strDeletePrefix."'".$scheduler->f("title")."'".$strDeleteSuffix).'")\' title="'.$strDeleteItem.' \''.$scheduler->f("title").'\'"><img src="'.$GO_THEME->image_url.'buttons/delete.gif" border="0"></a>';
				}
				if ($scheduler->f('location_text') != '')
				{
					$notime_events .= '<br />'.$sc_location.':&nbsp;'.$scheduler->f('location_text');
				}
				$notime_events .= '<br /><br />'.text_to_html($scheduler->f('description')).'<br /><br />';
			}
		}else
		{
			if (strlen($month) == 1) $month = '0'.$month;
			if (strlen($day) == 1) $day = '0'.$day;

			switch ($scheduler->f('type'))
			{
				case 'once':
					$calc_date = $year.'-'.$month.'-'.$day;
				break;

				case 'month_date':
					$calc_date = '0000-00-'.$day;

				break;

				case 'yearly':
					$calc_date = '0000-'.$month.'-'.$day;
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
				$innerHTML .= '<br /><br />'.text_to_html($scheduler->f('description')).'<br /><br />';
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
				$innerHTML .= '<br /><br />'.text_to_html($scheduler->f('description')).'<br /><br />';
			}

			if (isset($events[$start_hour]))
			{
				if (isset($events[$start_hour]['reference']))
					$event_hour = $events[$start_hour]['reference'];
				else
					$event_hour = $start_hour;

				$events[$event_hour]['innerHTML'] .= $innerHTML;

				if (($end_hour - $event_hour + 1) > $events[$event_hour]['rowspan'])
					$events[$event_hour]['rowspan'] = $end_hour - $event_hour + 1;

				for ($i=$event_hour+1;$i<=$end_hour;$i++)
				{
					$events[$i]['reference'] = $start_hour;
				}

			}else
			{
				$rowspan = $end_hour - $start_hour + 1;
				$events[$start_hour]['innerHTML'] = $innerHTML;
				$events[$start_hour]['rowspan'] = $rowspan;

				for ($i=$start_hour+1;$i<=$end_hour;$i++)
				{
					$events[$i]['reference'] = $start_hour;
				}
			}
		}
	}
}

if (isset($notime_events))
{
	echo '<tr><td colspan="2" class="TableInside4">'.$notime_events.'</td>';
}

for ($i=0;$i<24;$i++)
{
	echo '<tr><td width="40" align="right" class="TableInside2" valign="top"><a href="javascript:new_event('.$i.')" class="HoverLink">'.$i.':00</a></td>';

	if (isset($events[$i]))
	{
		if (!isset($events[$i]['reference']))
		{
			echo '<td valign="top" width="100%" class="TableInside4" rowspan="'.$events[$i]['rowspan'].'">';
			echo $events[$i]['innerHTML'].'</td>';
		}
		echo '</tr>';
	}else
	{
		if (($i>7) && ($i<18) && $day_number != 6 && $day_number != 0)
				$class="TableInside3";
			else
				$class="TableInside1";

		echo '<td class="'.$class.'">&nbsp;</td></tr>';
	}
	echo "\n";
}

?>
</table>

