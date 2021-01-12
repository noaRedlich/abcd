<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

require("../../Group-Office.php");

$GO_SECURITY->authenticate();
$GO_MODULES->authenticate('scheduler');
require($GO_LANGUAGE->get_language_file('scheduler'));

require($GO_CONFIG->class_path."scheduler.class.inc");
$scheduler = new scheduler();
$post_action = isset($post_action) ? $post_action : '';

switch($post_action)
{
	case 'load_scheduler':
		$load_scheduler_id = $scheduler_id;
		$post_action = 'schedulers';
		$table_tabindex = 2;
	break;

	case 'save_scheduler':
		$post_action = 'schedulers';
		if ($name != "")
		{
			if (validate_input($name))
			{
				if (!isset($load_scheduler_id))
				{
					$scheduler_id = $scheduler->add_scheduler($GO_SECURITY->user_id, $name);
					$scheduler->subscribe($GO_SECURITY->user_id, $scheduler_id);
					$table_tabindex=1;
				}else
				{
					$scheduler_id = $load_scheduler_id;
					$scheduler->update_scheduler($scheduler_id, $name);
					$table_tabindex=2;
				}
				if ($scheduler_id > 0)
				{
					$scheduler_prop = $scheduler->get_scheduler($scheduler_id);
					$GO_SECURITY->add_user_to_acl($GO_SECURITY->user_id,$scheduler_prop["acl_write"]);
					$table_tabindex=0;

				}else
				{
					$feedback = "<p class=\"Error\">".$strSaveError.": \\ / & ? </p>";
				}
			}else
			{
				$feedback = "<p class=\"Error\">".$invalid_input.": \\ / & ? </p>";
			}
		}else
		{
			$feedback = "<p class=\"Error\">".$error_missing_field."</p>";
		}
	break;

	case 'subscribe':

		$scheduler->unsubscribe_all($GO_SECURITY->user_id);
		for ($i=0;$i<sizeof($subscribed);$i++)
		{
			$scheduler->subscribe($GO_SECURITY->user_id, $subscribed[$i]);
		}
		echo '<input type="hidden" name="scheduler_id" value="'.$scheduler_id.'" />';
		echo '<tr><td valign="top">';
		$post_action = 'schedulers';
	break;

	case 'set_primary':

		echo '<input type="hidden" name="scheduler_id" value="'.$scheduler_id.'" />';
		echo '<tr><td valign="top">';
		require("schedulers.inc");
	break;

	case 'delete_scheduler':
		$post_action = 'schedulers';

		$scheduler_prop = $scheduler->get_scheduler($scheduler_id);
		if ($scheduler_prop["user_id"] == $GO_SECURITY->user_id)
		{
			$scheduler->delete_scheduler($scheduler_id);
			$scheduler_id = $scheduler->get_primary($GO_SECURITY->user_id);
		}else
		{
			$feedback = '<p class="Error">'.$strAccessDenied.'</p>';
		}
		echo '<input type="hidden" name="scheduler_id" value="'.$scheduler_id.'" />';
		echo '<tr><td valign="top">';
		if ($scheduler_id > 0)
		{
			$table_tabindex=0;
		}else
		{
			$feedback = '<p class="Error">'.$sc_no_scheduler.'</p>';
			$table_tabindex=1;
		}
	break;
}

if (!isset($scheduler_id))
{
	$scheduler_id = $scheduler->get_primary($GO_SECURITY->user_id);
	if (!$scheduler_id)
	{
		require_once($GO_CONFIG->class_path.'users.class.inc');
		$users = new users;
		$user = $users->get_user($GO_SECURITY->user_id);
		$scheduler_id = $scheduler->add_scheduler($GO_SECURITY->user_id, $user["name"], 'p');
		if ($scheduler_id)
		{
			$scheduler->subscribe($GO_SECURITY->user_id, $scheduler_id);
			$scheduler_prop = $scheduler->get_scheduler($scheduler_id);
			$GO_SECURITY->add_user_to_acl($GO_SECURITY->user_id,$scheduler_prop["acl_write"]);
		}else
		{
			$feedback = '<p class="Error">'.$strSaveError.'</p>';
		}
	}
}

if (!isset($scheduler_prop)) $scheduler_prop = $scheduler->get_scheduler($scheduler_id);

$scheduler_read = $GO_SECURITY->has_permission($GO_SECURITY->user_id, $scheduler_prop['acl_read']);
$scheduler_write = $GO_SECURITY->has_permission($GO_SECURITY->user_id, $scheduler_prop['acl_write']);

$page_title = $menu_scheduler;
require($GO_THEME->theme_path."header.inc");


echo '<script language="javascript" type="text/javascript" src="'.$GO_CONFIG->host.$GO_MODULES->path.'scheduler.js"></script>';

if (!isset($post_action)) $post_action = 'week';

if ($post_action == 'week' || $post_action == 'day')
	$last_view = $post_action;

if (!isset($last_view)) $last_view = 'week';

$time = time();
$current_year = date("Y", $time);
$current_month = date("m", $time);
$current_day = date("j", $time);

//by default display current day
if(!isset($year)) $year = $current_year;
if(!isset($month)) $month = $current_month;
if(!isset($day)) $day = $current_day;

$passed_year = $year;
$passed_month = $month;
$passed_day = $day;
$year = date("Y", mktime(0,0,0,$passed_month,$passed_day,$passed_year));
$month= date("m", mktime(0,0,0,$passed_month,$passed_day,$passed_year));
$day = date("d", mktime(0,0,0,$passed_month,$passed_day,$passed_year));
$event_id = isset($event_id) ? $event_id : 0;
$hour = isset($hour) ? $hour : date("H", $time);

$delete = isset($delete) ? $delete : '';

$ending_year = isset($ending_year) ? $ending_year : $year;
$ending_month = isset($ending_month) ? $ending_month : $month;
$ending_day = isset($ending_day) ? $ending_day : $day;
$starting_year = isset($starting_year) ? $starting_year : $year;
$starting_month = isset($starting_month) ? $starting_month : $month;
$starting_day = isset($starting_day) ? $starting_day : $day;
$start_hour = isset($start_hour) ? $start_hour : 0;
$start_min = isset($start_min) ? $start_min : 0;
$start_year = isset($start_year) ? $start_year : $year;


$end_hour = isset($end_hour) ? $end_hour : 0;
$end_min = isset($end_min) ? $end_min : 0;
$end_year = isset($end_year) ? $end_year : $year;
$month_time = isset($month_time) ? $month_time : '';
$notime = isset($notime) ? $notime : '0';
$noend = isset($noend) ? $noend : '0';
$recur_days_0 = isset($recur_days_0) ? $recur_days_0 : '';
$recur_days_1 = isset($recur_days_1) ? $recur_days_1 : '';
$recur_days_2 = isset($recur_days_2) ? $recur_days_2 : '';
$recur_days_3 = isset($recur_days_3) ? $recur_days_3 : '';
$recur_days_4 = isset($recur_days_4) ? $recur_days_4 : '';
$recur_days_5 = isset($recur_days_5) ? $recur_days_5 : '';
$recur_days_6 = isset($recur_days_6) ? $recur_days_6 : '';
?>

<table border="0" cellpadding="10" cellspacing="0" width="100%"><tr><td>
<table border="0" cellpadding="5" cellspacing="0">
<tr>
	<td align="center"><a href="javascript:new_event(<?php echo $hour; ?>)" class="small"><img src="<?php echo $GO_THEME->image_url; ?>buttons/compose.gif" border="0" width="32" height="32" /><br /><?php echo $sc_new_app; ?></a></td>
	<td align="center"><a href="javascript:post_day(<?php echo $day; ?>)" class="small"><img src="<?php echo $GO_THEME->image_url; ?>buttons/day.gif" border="0" width="32" height="32" /><br /><?php echo $sc_day_view; ?></a></td>
	<td align="center"><a href="javascript:post_week(<?php echo $day; ?>)" class="small"><img src="<?php echo $GO_THEME->image_url; ?>buttons/week.gif" border="0" width="32" height="32" /><br /><?php echo $sc_week_view; ?></a></td>
	<td align="center"><a href="javascript:show_events()" class="small"><img src="<?php echo $GO_THEME->image_url; ?>buttons/listview.gif" border="0" width="32" height="32" /><br /><?php echo $sc_list_view; ?></a></td>
	<td align="center"><a href="javascript:load_schedulers()" class="small"><img src="<?php echo $GO_THEME->image_url; ?>buttons/scheduler.gif" border="0" width="32" height="32" /><br /><?php echo $sc_schedulers; ?></a></td>

</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
	<?php

	echo '<form method="post" action="'.$PHP_SELF.'" name="calendar">';
	echo '<input type="hidden" name="post_action" value="'.$post_action.'" />';
	echo '<input type="hidden" name="last_view" value="'.$last_view.'" />';
	echo '<input type="hidden" name="event_id" value="'.$event_id.'" />';
	echo '<input type="hidden" name="day" value="'.$day.'" / >';
	echo '<input type="hidden" name="hour" value="'.$hour.'" / >';
	echo '<input type="hidden" name="recur_type" / >';

	switch ($post_action)
	{
		case 'show_events':
			$scheduler_count = $scheduler->get_subscribed($GO_SECURITY->user_id);
			$dropbox = new dropbox();
			if($scheduler_count > 1)
			{
				$dropbox->add_sql_data("scheduler","id","name");
				echo '<tr><td colspan="2">';
				$dropbox->print_dropbox("scheduler_id", $scheduler_id, 'onchange="javascript:change_scheduler(this.value)"');
				echo '</td></tr>';
			}else
			{
				echo '<input type="hidden" name="scheduler_id" value="'.$scheduler_id.'" />';
			}
			echo '<tr><td valign="top">';
			require("calendar.inc");
			echo '</td><td valign="top" width="100%">';
			require("events.inc");
		break;
		case 'schedulers':
			echo '<input type="hidden" name="scheduler_id" value="'.$scheduler_id.'" />';
			echo '<tr><td valign="top">';
			$table_title = $sc_schedulers;
			$table_width = "600";  
			$table_height = "300";
			$table_docs[] = "schedulers.inc";
			$table_tabs[] = $sc_schedulers;
			$table_docs[] = "scheduler.inc";
			$table_tabs[] = $cmdAdd;
			if (isset($load_scheduler_id))
			{
				$table_docs[] = 'scheduler.inc';
				$table_tabs[] = $strProperties;
			}
			$table_arguments = '&post_action=schedulers';
			require($GO_CONFIG->control_path."html_table.inc");
		break;



		case 'day':
			$scheduler_count = $scheduler->get_subscribed($GO_SECURITY->user_id);
			$dropbox = new dropbox();
			if($scheduler_count > 1)
			{
				$dropbox->add_sql_data("scheduler","id","name");
				echo '<tr><td colspan="2">';
				$dropbox->print_dropbox("scheduler_id", $scheduler_id, 'onchange="javascript:change_scheduler(this.value)"');
				echo '</td></tr>';
			}else
			{
				echo '<input type="hidden" name="scheduler_id" value="'.$scheduler_id.'" />';
			}
			echo '<tr><td valign="top">';
			require("calendar.inc");
			echo '</td><td valign="top" width="100%">';
			require("day.inc");
		break;

		case 'new_event':
			$scheduler_count = $scheduler->get_subscribed($GO_SECURITY->user_id);
			$dropbox = new dropbox();
			if($scheduler_count > 1)
			{
				$dropbox->add_sql_data("scheduler","id","name");
				echo '<tr><td colspan="2">';
				$dropbox->print_dropbox("scheduler_id", $scheduler_id, 'onchange="javascript:change_scheduler(this.value)"');
				echo '</td></tr>';
			}else
			{
				echo '<input type="hidden" name="scheduler_id" value="'.$scheduler_id.'" />';
			}
			echo '<tr><td valign="top">';
			require("calendar.inc");
			echo '</td><td valign="top" width="100%">';
			if ($scheduler_write)
			{
		 		require("event.inc");
			}else
			{
				require($GO_CONFIG->root_path."error_docs/403.inc");
			}
		break;

		case 'unsubscribe_event':
			if ($scheduler_write)
			{
				if ($scheduler->get_event_subscribtions($event_id) < 2)
				{
					$scheduler->delete_event($event_id);
				}
				$scheduler->unsubscribe_event($event_id, $scheduler_id);
			}

			$scheduler_count = $scheduler->get_subscribed($GO_SECURITY->user_id);
			$dropbox = new dropbox();
			if($scheduler_count > 1)
			{
				$dropbox->add_sql_data("scheduler","id","name");
				echo '<tr><td colspan="2">';
				$dropbox->print_dropbox("scheduler_id", $scheduler_id, 'onchange="javascript:change_scheduler(this.value)"');
				echo '</td></tr>';
			}else
			{
				echo '<input type="hidden" name="scheduler_id" value="'.$scheduler_id.'" />';
			}
			echo '<tr><td valign="top">';
			require("calendar.inc");
			echo '</td><td valign="top" width="100%">';
			require($last_view.'.inc');
		break;

		case 'delete_event':

			$event = $scheduler->get_event($event_id);
			if ($GO_SECURITY->has_permission($GO_SECURITY->user_id, $event['acl_write']))
			{
				$scheduler->delete_event($event_id);
			}

			$scheduler_count = $scheduler->get_subscribed($GO_SECURITY->user_id);
			$dropbox = new dropbox();
			if($scheduler_count > 1)
			{
				$dropbox->add_sql_data("scheduler","id","name");
				echo '<tr><td colspan="2">';
				$dropbox->print_dropbox("scheduler_id", $scheduler_id, 'onchange="javascript:change_scheduler(this.value)"');
				echo '</td></tr>';
			}else
			{
				echo '<input type="hidden" name="scheduler_id" value="'.$scheduler_id.'" />';
			}
			echo '<tr><td valign="top">';
			require("calendar.inc");
			echo '</td><td valign="top" width="100%">';
			require($last_view.'.inc');
		break;

		case 'load_event':
			if ($event = $scheduler->get_event($event_id))
			{
				extract($event,EXTR_OVERWRITE);
				$user_id = $GO_SECURITY->user_id;

				$recur = $type;
				$starting_date = explode('-',$starting_date);
				if ($starting_date[0] != '0000')
					$starting_year = $starting_date[0];
				if ($starting_date[1] != '00')
					$starting_month = $starting_date[1];
				if ($starting_date[2] != '00')
					$starting_day = $starting_date[2];

				$ending_date = explode('-',$ending_date);
				if ($ending_date[0] != '0000')
					$ending_year = $ending_date[0];
				if ($ending_date[1] != '00')
					$ending_month = $ending_date[1];
				if ($ending_date[2] != '00')
					$ending_day = $ending_date[2];

				$start_date = explode('-',$start_date);
				if ($start_date[0] != '0000')
					$start_year = $start_date[0];
				if ($start_date[1] != '00')
					$start_month = $start_date[1];
				if ($start_date[2] != '00')
					$start_day = $start_date[2];

				$end_date = explode('-',$end_date);
				if ($end_date[0] != '0000')
					$ending_year = $end_date[0];
				if ($end_date[1] != '00')
					$end_month = $end_date[1];
				if ($end_date[2] != '00')
					$end_day = $end_date[2];

				$recur_days_0 = $sunday;
				$recur_days_1 = $monday;
				$recur_days_2 = $tuesday;
				$recur_days_3 = $wednesday;
				$recur_days_4 = $thursday;
				$recur_days_5 = $friday;
				$recur_days_6 = $saturday;

				$scheduler->get_participants($event_id);
				while ($scheduler->next_record())
				{
					if (isset($to))
					{
						$to .= ', '.$scheduler->f("email");
					}else
					{
						$to = $scheduler->f("email");
					}
				}

				$scheduler->get_event_subscribtions($event_id);
				while($scheduler->next_record())
				{
					$schedulers[] = $scheduler->f('scheduler_id');
				}

			}else
			{
				$feedback = '<p class="Error">'.$strDataError.'</p>';
			}
			$scheduler_count = $scheduler->get_subscribed($GO_SECURITY->user_id);
			$dropbox = new dropbox();
			if($scheduler_count > 1)
			{
				$dropbox->add_sql_data("scheduler","id","name");
				echo '<tr><td colspan="2">';
				$dropbox->print_dropbox("scheduler_id", $scheduler_id, 'onchange="javascript:change_scheduler(this.value)"');
				echo '</td></tr>';
			}else
			{
				echo '<input type="hidden" name="scheduler_id" value="'.$scheduler_id.'" />';
			}
			echo '<tr><td valign="top">';
			require("calendar.inc");
			echo '</td><td valign="top" width="100%">';
			require("event.inc");
		break;

		case 'save_event':
			if ($title == '')
			{
				$feedback = $error_missing_field;
			}elseif(!isset($schedulers) || count($schedulers) == 0)
			{
				$feedback = $sc_select_scheduler_please;
			}else
			{
				$noend = isset($noend) ? $noend : '';
				$notime = isset($notime) ? $notime : '';
				$ending_date = $ending_year.'-'.$ending_month.'-'.$ending_day;
				$starting_date = $starting_year.'-'.$starting_month.'-'.$starting_day;
				switch($recur)
				{
					case 'once':
						$type=$recur;
						$starting_date = '0000-00-00';
						$ending_date = '0000-00-00';
						$save_start_day=$start_day;
						$save_start_month=$start_month;
						$save_end_day=$end_day;
						$save_end_month=$end_month;
						$save_year=$year;

					break;

					case 'weekly':
						$type=$recur;
						$save_start_day='00';
						$save_start_month='00';
						$save_end_day='00';
						$save_end_month='00';
						$save_year='0000';
					break;

					case 'daily':
						$type=$recur;
						$save_start_day='00';
						$save_start_month='00';
						$save_end_day='00';
						$save_end_month='00';
						$save_year='0000';
					break;

					case 'month_date':
						$type = 'month_date';
						$save_start_day=$start_day;
						$save_start_month='00';
						$save_end_day=$end_day;
						$save_end_month='00';
						$save_year='0000';
					break;

					case 'month_day':
						$type = 'month_day';
						$save_start_day='00';
						$save_start_month='00';
						$save_end_day='00';
						$save_end_month='00';
						$save_year='0000';
					break;

					case 'yearly';
						$type=$recur;
						$save_start_day=$start_day;
						$save_start_month=$start_month;
						$save_end_day=$end_day;
						$save_end_month=$end_month;
						$save_year='0000';
					break;
				}

				if ($event_id > 0)
				{
					if (!$scheduler->update_event($event_id, $title, $description, $save_start_day, $save_start_month, $save_year, $start_hour, $start_min, $save_end_day, $save_end_month, $end_hour, $end_min, $recur_days_1, $recur_days_2, $recur_days_3, $recur_days_4, $recur_days_5, $recur_days_6, $recur_days_0, $month_time, $delete, $notime, $noend, $type, $starting_date, $ending_date, $location_text))
					{
						$feedback = $strSaveError;
					}else
					{
						$scheduler->remove_participants($event_id);
						$event_prop = $scheduler->get_event($event_id);
					}

				}else
				{
					$event_id = $scheduler->save_event($GO_SECURITY->user_id, $title, $description, $save_start_day, $save_start_month, $save_year, $start_hour, $start_min, $save_end_day, $save_end_month, $end_hour, $end_min, $recur_days_1, $recur_days_2, $recur_days_3, $recur_days_4, $recur_days_5, $recur_days_6, $recur_days_0, $month_time, $delete, $notime, $noend, $type, $starting_date, $ending_date, $location_text);
					$event_prop = $scheduler->get_event($event_id);
					if (!$event_id)
					{
						$feedback = $strSaveError;
					}else
					{
						if ($inherit_from > 0)
						{
							$scheduler_prop = $scheduler->get_scheduler($scheduler_id);


							$GO_SECURITY->copy_acl($scheduler_prop['acl_read'], $event_prop['acl_read']);
							$GO_SECURITY->copy_acl($scheduler_prop['acl_write'], $event_prop['acl_write']);
						}
					}
				}
				if (!isset($feedback))
				{
					if (!$GO_SECURITY->has_permission($GO_SECURITY->user_id, $event_prop['acl_write']))
					{
						$GO_SECURITY->add_user_to_acl($GO_SECURITY->user_id, $event_prop['acl_write']);
					}

					$scheduler2 = new scheduler();
					$scheduler2->get_subscribed($GO_SECURITY->user_id);
					while ($scheduler2->next_record())
					{
						if ($GO_SECURITY->has_permission($GO_SECURITY->user_id, $scheduler2->f('acl_write')))
						{
							if (in_array($scheduler2->f('id'), $schedulers))
							{
								if (!$scheduler->event_is_subscribed($event_id, $scheduler2->f('id')))
								{
									$scheduler->subscribe_event($event_id, $scheduler2->f('id'));
								}
							}else
							{
								if ($scheduler->event_is_subscribed($event_id, $scheduler2->f('id')))
								{
									$scheduler->unsubscribe_event($event_id, $scheduler2->f('id'));
								}
							}
						}
					}

					$participants = cut_address($to, $charset);

					$mail_body  = '<html><body>'.$sc_invited.'<br /><br />';

					if (strlen($start_min) == 1) $start_min = '0'.$start_min;
					if (strlen($end_min) == 1) $end_min = '0'.$end_min;
					switch ($recur)
					{
						case 'once':
							$mail_body .= $sc_types['once'].'<br />';
							$mail_body .= $sc_start_at.': '.date($ses_date_format, mktime($start_hour,$start_min,0,$start_month,$start_day,$save_year)).'<br />';
							$mail_body .= $sc_end_at.': '.date($ses_date_format, mktime($end_hour,$end_min,0,$end_month,$end_day,$save_year)).'<br />';
							$mail_body .= $sc_location.': '.$location_text.'<br />';
							$mail_body .= $sc_description.':<br />';
							$mail_body .= text_to_html($description);

						break;

						case 'weekly':
							$mail_body .= $sc_types['weekly'].'<br />';
							$mail_body .= $sc_at_days.': ';
							if ($recur_days_1 == 1)
							{
								$mail_body .= $full_days[1].'&nbsp;';
							}

							if ($recur_days_2 == 1)
							{
								$mail_body .= $full_days[2].'&nbsp;';
							}

							if ($recur_days_3 == 1)
							{
								$mail_body .= $full_days[3].'&nbsp;';
							}

							if ($recur_days_4 == 1)
							{
								$mail_body .= $full_days[4].'&nbsp;';
							}

							if ($recur_days_5 == 1)
							{
								$mail_body .= $full_days[5].'&nbsp;';
							}

							if ($recur_days_6 == 1)
							{
								$mail_body .= $full_days[6].'&nbsp;';
							}

							if ($recur_days_0 == 1)
							{
								$mail_body .= $full_days[0].'&nbsp;';
							}

							$mail_body .= $sc_start_time.': '.$start_hour.':'.$start_min.'<br />';
							$mail_body .= $sc_end_time.': '.$end_hour.':'.$end_min.'<br />';

							$mail_body .= $sc_cycle_start.': '.$starting_date.'<br />';
							$mail_body .= $sc_cycle_end.': '.$ending_date.'<br />';
							$mail_body .= $sc_location.': '.$location_text.'<br />';
							$mail_body .= $sc_description.':<br />';
							$mail_body .= text_to_html($description);
						break;

						case 'daily':
							$mail_body .= $sc_types['daily'].'<br />';
							$mail_body .= $sc_start_time.': '.$start_hour.':'.$start_min.'<br />';
							$mail_body .= $sc_end_time.': '.$end_hour.':'.$end_min.'<br />';
							$mail_body .= $sc_cycle_start.': '.$starting_date.'<br />';
							$mail_body .= $sc_cycle_end.': '.$ending_date.'<br />';
							$mail_body .= $sc_location.': '.$location_text.'<br />';
							$mail_body .= $sc_description.':<br />';
							$mail_body .= text_to_html($description);
						break;

						case 'month_date':

							$mail_body .= $sc_types['month_date'].'<br />';
							$mail_body .= $sc_start_at.': '.date($ses_date_format, mktime($start_hour,$start_min,0,$start_month,$start_day,$start_year)).'<br />';
							$mail_body .= $sc_end_at.': '.date($ses_date_format, mktime($end_hour,$end_min,0,$end_month,$end_day,$end_year)).'<br />';

							$mail_body .= $sc_cycle_start.': '.$starting_date.'<br />';
							$mail_body .= $sc_cycle_end.': '.$ending_date.'<br />';
							$mail_body .= $sc_location.': '.$location_text.'<br />';
							$mail_body .= $sc_description.':<br />';
							$mail_body .= text_to_html($description);
						break;

						case 'month_day':
							$mail_body .= $sc_types['month_day'].'<br />';
							$mail_body .= $month_times[0]." ";
							if ($recur_days_1 == 1)
							{
								$mail_body .= $full_days[1].'&nbsp;';
							}

							if ($recur_days_2 == 1)
							{
								$mail_body .= $full_days[2].'&nbsp;';
							}

							if ($recur_days_3 == 1)
							{
								$mail_body .= $full_days[3].'&nbsp;';
							}

							if ($recur_days_4 == 1)
							{
								$mail_body .= $full_days[4].'&nbsp;';
							}

							if ($recur_days_5 == 1)
							{
								$mail_body .= $full_days[5].'&nbsp;';
							}

							if ($recur_days_6 == 1)
							{
								$mail_body .= $full_days[6].'&nbsp;';
							}

							if ($recur_days_0 == 1)
							{
								$mail_body .= $full_days[0].'&nbsp;';
							}
							$mail_body .= $sc_of_month.'<br />';
							$mail_body .= $sc_cycle_start.': '.$starting_date.'<br />';
							$mail_body .= $sc_cycle_end.': '.$ending_date.'<br />';
							$mail_body .= $sc_location.': '.$location_text.'<br />';
							$mail_body .= $sc_description.':<br />';
							$mail_body .= text_to_html($description);
						break;

						case 'yearly';
							$mail_body .= $sc_types['yearly'].'<br />';
							$mail_body .= $sc_start_at.': '.date($ses_date_format, mktime($start_hour,$start_min,0,$start_month,$start_day,$start_year)).'<br />';
							$mail_body .= $sc_end_at.': '.date($ses_date_format, mktime($end_hour,$end_min,0,$end_month,$end_day,$end_year)).'<br />';

							$mail_body .= $sc_cycle_start.': '.$starting_date.'<br />';
							$mail_body .= $sc_cycle_end.': '.$ending_date.'<br />';
							$mail_body .= $sc_location.': '.$location_text.'<br />';
							$mail_body .= $sc_description.':<br />';
							$mail_body .= text_to_html($description);
						break;
					}

					$mail_body .= '<br />'.$sc_accept_question.'<br /><br />';

					require_once($GO_CONFIG->class_path."users.class.inc");
					$users = new users;
					require_once($GO_CONFIG->class_path."contacts.class.inc");
					$contacts = new contacts;

					$profile = $users->get_user($GO_SECURITY->user_id);
					for ($i=0;$i<sizeof($participants);$i++)
					{
						$user_profile = $contacts->get_contact_profile_by_email($participants[$i], $user_id);
						$id = $user_profile["source_id"];
						if (!$user_profile)
						{
							$user_profile = $users->get_profile_by_email($participants[$i]);
							$id = $user_profile["id"];
						}

						if (!$user_profile)
						{
							$nouser_link = '<p><a href="'.$GO_CONFIG->host.$GO_MODULES->path.'accept.php?event_id='.$event_id.'&member=false&email='.$participants[$i].'" class="blue">'.$sc_accept.'</a>&nbsp|&nbsp;<a href="'.$GO_CONFIG->host.$GO_MODULES->path.'decline.php?event_id='.$event_id.'&member=false&email='.$participants[$i].'" class="blue">'.$sc_decline.'</a></p>';
							if (sendmail($participants[$i], $profile["email"], $profile["name"], $title, $mail_body.$nouser_link,'3 (Normal)', 'text/html'))
							{
								$scheduler->add_participant($event_id, $participants[$i], $participants[$i]);
							}
						}else
						{
							$user_link = '<p class="cmd"><a href="'.$GO_CONFIG->host.$GO_MODULES->path.'accept.php?event_id='.$event_id.'&member=true&email='.$participants[$i].'" class="blue">'.$sc_accept.'</a>&nbsp|&nbsp;<a href="'.$GO_CONFIG->host.$GO_MODULES->path.'decline.php?event_id='.$event_id.'&member=true&email='.$participants[$i].'" class="blue">'.$sc_decline.'</a></p>';
							if ($GO_SECURITY->user_id != $id)
							{
								if (sendmail($participants[$i], $profile["email"], $profile["name"], $title, $mail_body.$user_link,'3 (Normal)', 'text/html'))
								{
									$scheduler->add_participant($event_id, $user_profile["name"], $user_profile["email"], $id);
								}
							}else
							{
								$scheduler->add_participant($event_id, $user_profile["name"], $user_profile["email"], $id);
								$scheduler->set_event_status($event_id, '1', $user_profile["email"]);
							}
						}
					}
				}

			}
			$scheduler_count = $scheduler->get_subscribed($GO_SECURITY->user_id);
			$dropbox = new dropbox();
			if($scheduler_count > 1)
			{
				$dropbox->add_sql_data("scheduler","id","name");
				echo '<tr><td colspan="2">';
				$dropbox->print_dropbox("scheduler_id", $scheduler_id, 'onchange="javascript:change_scheduler(this.value)"');
				echo '</td></tr>';
			}else
			{
				echo '<input type="hidden" name="scheduler_id" value="'.$scheduler_id.'" />';
			}
			echo '<tr><td valign="top">';
			require("calendar.inc");
			echo '</td><td valign="top" width="100%">';
			if (isset($feedback))
			{
				require('event.inc');
			}else
			{

				require($last_view.'.inc');
			}

		break;

		default:
			$scheduler_count = $scheduler->get_subscribed($GO_SECURITY->user_id);
			$dropbox = new dropbox();
			if($scheduler_count > 1)
			{
				$dropbox->add_sql_data("scheduler","id","name");
				echo '<tr><td colspan="2">';
				$dropbox->print_dropbox("scheduler_id", $scheduler_id, 'onchange="javascript:change_scheduler(this.value)"');
				echo '</td></tr>';
			}else
			{
				echo '<input type="hidden" name="scheduler_id" value="'.$scheduler_id.'" />';
			}
			echo '<tr><td valign="top">';
			require("calendar.inc");
			echo '</td><td valign="top" width="100%">';
			require("week.inc");
		break;
	}
	echo '</form>';
	?>
	</td>

</tr>
</table>
</td></tr></table>
<?php

require($GO_THEME->theme_path."footer.inc");
?>
