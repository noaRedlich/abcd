<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

class scheduler extends db
{
	function scheduler()
	{
		$this->db();
	}

	function get_primary($user_id)
	{
		$sql = "SELECT * FROM scSchedulers LEFT JOIN scSubscribed ON (scSubscribed.scheduler_id=scSchedulers.id) WHERE scSubscribed.user_id='$user_id' AND scSchedulers.user_id='$user_id'";
		$this->query($sql);
		if ($this->next_record())
		{
			return $this->f("id");
		}else
		{
			return false;
		}
	}

	function add_participant($event_id, $name, $email, $user_id = 0)
	{
		$id = $this->nextid("scParticipants");

		if ($id > 0)
		{
			$sql = "INSERT INTO scParticipants (id, event_id, user_id, name, email) VALUES ('$id', '$event_id', '$user_id', '$name', '$email')";
			if ($this->query($sql))
			{
				return $id;
			}else
			{
				return -1;
			}
		}else
		{
			return -1;
		}
	}

	function remove_participants($event_id)
	{
		$sql = "DELETE FROM scParticipants WHERE event_id='$event_id'";
		return $this->query($sql);
	}

	function is_participant($event_id, $email)
	{
		$sql = "SELECT id FROM scParticipants WHERE event_id='$event_id' AND email='$email'";
		$this->query($sql);
		return $this->next_record();
	}

	function get_participants($event_id, $sort="name", $direction="ASC")
	{
		$sql = "SELECT * FROM scParticipants WHERE event_id='$event_id' ORDER BY ".$sort." ".$direction;
		$this->query($sql);
		return $this->num_rows();
	}

	function subscribe($user_id, $scheduler_id)
	{
		$sql = "INSERT INTO scSubscribed (user_id, scheduler_id) VALUES ('$user_id','$scheduler_id')";
		return $this->query($sql);
	}

	function unsubscribe_all($user_id)
	{
		$sql = "DELETE FROM scSubscribed WHERE user_id='$user_id'";
		return $this->query($sql);
	}

	function is_subscribed($user_id, $scheduler_id)
	{
		$sql = "SELECT * FROM scSubscribed WHERE user_id='$user_id' AND scheduler_id='$scheduler_id'";
		$this->query($sql);
		return $this->next_record();
	}

	function add_scheduler($user_id, $name)
	{
		if ($this->get_primary($user_id))
		{
			$standard='0';
		}else
		{
			$standard='1';
		}
		global $GO_SECURITY;
		$acl_read = $GO_SECURITY->get_new_acl('Scheduler read: '.$name);
		$acl_write = $GO_SECURITY->get_new_acl('Scheduler write: '.$name);
		if ($acl_read > 0 && $acl_write > 0)
		{
			$scheduler_id = $this->nextid("scSchedulers");
			if ($scheduler_id > 0)
			{
				$sql  = "INSERT INTO scSchedulers (id, user_id, name, acl_read, acl_write, standard) ";
				$sql .= "VALUES ('$scheduler_id', '$user_id', '$name', '$acl_read', '$acl_write', '$standard')";
				if ($this->query($sql))
				{
					return $scheduler_id;
				}
			}else
			{
				$GO_SECURITY->delete_acl($acl_read);
				$GO_SECURITY->delete_acl($acl_write);
			}

		}
		return false;

	}


	function delete_scheduler($scheduler_id)
	{
		global $GO_SECURITY;
		$delete = new scheduler;

		$sql = "SELECT * FROM scEventsSchedulers WHERE scheduler_id='$scheduler_id'";
		$this->query($sql);

		while ($this->next_record())
		{
			$sql = "SELECT * FROM scEventsSchedulers WHERE event_id='".$this->f("event_id")." '";
			$delete->query($sql);
			if ($delete->num_rows() < 2)
			{
				$event = $delete->get_event($this->f("event_id"));
				$GO_SECURITY->delete_acl($event["acl_read"]);
				$GO_SECURITY->delete_acl($event["acl_write"]);
				$sql = "DELETE FROM scEvents WHERE id='".$this->f("event_id")."'";
				$delete->query($sql);
				$sql = "DELETE FROM scParticipants WHERE event_id='".$this->f("event_id")."'";
				$delete->query($sql);
			}
		}
		$sql = "DELETE FROM scEventsSchedulers WHERE scheduler_id='$scheduler_id'";
		$this->query($sql);
		$scheduler_prop = $this->get_scheduler($scheduler_id);

		$GO_SECURITY->delete_acl($scheduler_prop["acl_read"]);
		$GO_SECURITY->delete_acl($scheduler_prop["acl_write"]);
		$sql= "DELETE FROM scSchedulers WHERE id='$scheduler_id'";
		$this->query($sql);
		$sql = "DELETE FROM scSubscribed WHERE scheduler_id='$scheduler_id'";
		$this->query($sql);
	}

	function update_scheduler($scheduler_id, $name)
	{
		$sql = "UPDATE scSchedulers SET name='$name' WHERE id='$scheduler_id'";
		return $this->query($sql);
	}


	function get_scheduler($scheduler_id)
	{
		$sql = "SELECT * FROM scSchedulers WHERE id='$scheduler_id'";
		$this->query($sql);
		if ($this->next_record())
		{
			return $this->Record;
		}else
		{
			return false;
		}
	}

	function get_subscribed($user_id)
	{
		$sql = "SELECT scSchedulers.* FROM scSchedulers LEFT JOIN scSubscribed ON (scSubscribed.scheduler_id=scSchedulers.id) WHERE scSubscribed.user_id='$user_id' ORDER BY name ASC";
		$this->query($sql);
		return $this->num_rows();
	}

	function get_schedulers()
	{
		$sql = "SELECT * FROM scSchedulers ORDER BY name ASC";
		$this->query($sql);
		return $this->num_rows();
	}

	function set_event_status($event_id, $status, $email)
	{
		$sql = "UPDATE scParticipants SET status='$status' WHERE email='$email' AND event_id='$event_id'";
		return $this->query($sql);
	}

	function get_event_status($event_id, $email)
	{
		$sql = "SELECT status FROM scParticipants WHERE email='$email' AND event_id='$event_id'";
		$this->query($sql);
		$this->next_record();
		return $this->f('status');
	}

	function save_event($user_id, $title, $description='', $start_day='', $start_month='', $year='', $start_hour='', $start_min='', $end_day='', $end_month='', $end_hour='', $end_min='', $monday='0', $tuesday='0', $wednesday='0', $thursday='0', $friday='0', $saturday='0', $sunday='0', $month_time='0', $delete='0', $notime='0',$noend='0', $type='daily',$starting_date='0', $ending_date='0', $location_text='0')
	{
		global $GO_SECURITY;
		$acl_read = $GO_SECURITY->get_new_acl('Event read: '.$title);
		$acl_write = $GO_SECURITY->get_new_acl('Event write: '.$title);
		if ($acl_read > 0 && $acl_write > 0)
		{
			$start_date = $year.'-'.$start_month.'-'.$start_day;
			$end_date = $year.'-'.$end_month.'-'.$end_day;

			$event_id = $this->nextid("scEvents");
			if ($event_id > 0)
			{
				$sql = "INSERT INTO scEvents (id, user_id, title, description, start_date, start_hour, start_min, end_date, end_hour, end_min, monday, tuesday, wednesday, thursday, friday, saturday, sunday, month_time, delete_record, notime, noend, type, starting_date, ending_date, acl_read, acl_write, location_text) ";
				$sql .= "VALUES ('$event_id', '$user_id', '$title', '$description', '$start_date', '$start_hour', '$start_min', '$end_date', '$end_hour', '$end_min', '$monday', '$tuesday', '$wednesday', '$thursday', '$friday', '$saturday', '$sunday', '$month_time', '$delete', '$notime', '$noend', '$type','$starting_date', '$ending_date', '$acl_read', '$acl_write', '$location_text')";
				if ($this->query($sql))
				{
					return $event_id;
				}
			}else
			{
				$GO_SECURITY->delete_acl($acl_read);
				$GO_SECURITY->delete_acl($acl_write);
			}
		}
		return false;
	}

	function subscribe_event($event_id, $scheduler_id)
	{
		$sql = "INSERT INTO scEventsSchedulers (scheduler_id, event_id) VALUES ('$scheduler_id','$event_id')";
		return $this->query($sql);
	}

	function unsubscribe_event($event_id, $scheduler_id)
	{
		$sql = "DELETE FROM scEventsSchedulers WHERE scheduler_id='$scheduler_id' AND event_id='$event_id'";
		return $this->query($sql);
	}

	function event_is_subscribed($event_id, $scheduler_id)
	{
		$sql = "SELECT * FROM scEventsSchedulers WHERE event_id='$event_id' AND scheduler_id='$scheduler_id'";
		$this->query($sql);
		return $this->next_record();
	}

	function get_event_subscribtions($event_id)
	{
		$sql = "SELECT * FROM scEventsSchedulers WHERE event_id='$event_id'";
		$this->query($sql);
		return $this->num_rows();
	}

	function update_event($event_id, $title, $description='', $start_day='', $start_month='', $year='', $start_hour='', $start_min='', $end_day='', $end_month='', $end_hour='', $end_min='', $monday='0', $tuesday='0', $wednesday='0', $thursday='0', $friday='0', $saturday='0', $sunday='0', $month_time='0', $delete='0', $notime='0',$noend='0', $type='daily',$starting_date='0', $ending_date='0', $location_text='0')
	{
		$start_date = $year.'-'.$start_month.'-'.$start_day;
		$end_date = $year.'-'.$end_month.'-'.$end_day;

		$sql = "UPDATE scEvents SET title='$title', description='$description', start_date='$start_date', start_hour='$start_hour', start_min='$start_min', end_date='$end_date', end_hour='$end_hour', end_min='$end_min', monday='$monday', tuesday='$tuesday', wednesday='$wednesday', thursday='$thursday', friday='$friday', saturday='$saturday', sunday='$sunday', month_time='$month_time', delete_record='$delete', notime='$notime', noend='$noend', type='$type', starting_date='$starting_date', ending_date='$ending_date', location_text='$location_text' ";
		$sql .= "WHERE id='$event_id'";
		return $this->query($sql);
	}


	function get_events($scheduler_id, $day, $month, $year, $weekday)
	{
		$month_time = ceil($day/7);
		if (strlen($month) == 1) $month='0'.$month;
		if (strlen($day) == 1) $day='0'.$day;

		$fetch_date = $year.'-'.$month.'-'.$day;

		switch ($weekday)
		{
			case 0:
				$weekday = 'sunday';
			break;

			case 1:
				$weekday = 'monday';
			break;

			case 2:
				$weekday = 'tuesday';
			break;

			case 3:
				$weekday = 'wednesday';
			break;

			case 4:
				$weekday = 'thursday';
			break;

			case 5:
				$weekday = 'friday';
			break;

			case 6:
				$weekday = 'saturday';
			break;
		}
		$sql  = "SELECT scEvents.* FROM scEvents LEFT JOIN scEventsSchedulers ON (scEvents.id=scEventsSchedulers.event_id) WHERE ";
		$sql .= "((scEvents.start_date<='$fetch_date' AND scEvents.end_date>='$fetch_date' AND scEvents.type='once') OR";
		$sql .= "(scEvents.start_date<='0000-00-$day' AND scEvents.end_date>='0000-00-$day' AND scEvents.type='month_date') OR ";
		$sql .= "(scEvents.type='daily') OR ";
		$sql .= "($weekday='1' AND scEvents.type='weekly') OR ";
		$sql .= "($weekday='1' AND scEvents.type='month_day' AND scEvents.month_time='$month_time') OR ";
		$sql .= "(scEvents.start_date<='0000-$month-$day' AND scEvents.end_date>='0000-$month-$day' AND scEvents.type='yearly')) ";
		$sql .= "AND (scEvents.ending_date='0000-00-00' OR noend='1' OR scEvents.ending_date >= '$fetch_date') AND (scEvents.starting_date='0000-00-00' OR scEvents.starting_date <= '$fetch_date') AND scEventsSchedulers.scheduler_id='$scheduler_id' ORDER BY scEvents.start_hour ASC";
		$this->query($sql);
		return $this->num_rows();
	}

	function get_all_events($scheduler_id)
	{
		$sql = "SELECT scEvents.* FROM scEvents LEFT JOIN scEventsSchedulers ON (scEvents.id=scEventsSchedulers.event_id) WHERE scEventsSchedulers.scheduler_id='$scheduler_id'";
		$this->query($sql);
		return $this->num_rows();
	}

	//never tested or used
	function is_available($scheduler_id, $day, $month, $year, $weekday, $start_hour, $start_min, $end_hour, $end_min)
	{
		if (strlen($start_min) == 1) $start_min='0'.$start_min;
		if (strlen($end_min) == 1) $end_min='0'.$end_min;
		$start_time = $start_hour.$start_min;
		$end_time = $end_hour.$end_min;

		$month_time = ceil($day/7);
		if (strlen($month) == 1) $month='0'.$month;
		if (strlen($day) == 1) $day='0'.$day;

		$fetch_date = $year.'-'.$month.'-'.$day;

		switch ($weekday)
		{
			case 0:
				$weekday = 'sunday';
			break;

			case 1:
				$weekday = 'monday';
			break;

			case 2:
				$weekday = 'tuesday';
			break;

			case 3:
				$weekday = 'wednesday';
			break;

			case 4:
				$weekday = 'thursday';
			break;

			case 5:
				$weekday = 'friday';
			break;

			case 6:
				$weekday = 'saturday';
			break;
		}
		$sql  = "SELECT scEvents.* FROM scEvents LEFT JOIN scEventsSchedulers ON (scEvents.id=scEventsSchedulers.event_id) WHERE ";
		$sql .= "((scEvents.start_date<='$fetch_date' AND scEvents.end_date>='$fetch_date' AND scEvents.type='once') OR";
		$sql .= "(scEvents.start_date<='0000-00-$day' AND scEvents.end_date>='0000-00-$day' AND scEvents.type='month_date') OR ";
		$sql .= "(scEvents.type='daily') OR ";
		$sql .= "($weekday='1' AND scEvents.type='weekly') OR ";
		$sql .= "($weekday='1' AND scEvents.type='month_day' AND scEvents.month_time='$month_time') OR ";
		$sql .= "(scEvents.start_date<='0000-$month-$day' AND scEvents.end_date>='0000-$month-$day' AND scEvents.type='yearly')) ";
		$sql .= "AND (scEvents.ending_date='0000-00-00' OR scEvents.ending_date >= '$fetch_date') AND (scEvents.starting_date='0000-00-00' OR scEvents.starting_date <= '$fetch_date') AND scEventsSchedulers.scheduler_id='$scheduler_id' ORDER BY scEvents.start_hour ASC";
		$this->query($sql);
		while ($this->next_record())
		{
			if ($this->f('notime') == '0')
			{
				$event_start_min = $this->f('start_min');
				if (strlen($event_start_min) == 1) $event_start_min='0'.$event_start_min;
				$event_end_min = $this->f('end_min');
				if (strlen($event_end_min) == 1) $event_end_min='0'.$event_end_min;

				$event_start_time = $this->f('start_hour').$event_start_min;
				$event_end_time = $this->f('end_hour').$event_end_min;

				if ($event_end_time > $start_time && $event_start_time < $end_time)
				{
					return false;
				}

			}
		}
		return true;
	}

	function get_event($event_id)
	{
		$sql = "SELECT * FROM scEvents WHERE id='$event_id'";
		$this->query($sql);
		if($this->next_record())
		{
			return $this->Record;
		}else
		{
			return false;
		}
	}

	function delete_event($event_id)
	{
		$event = $this->get_event($event_id);
		global $GO_SECURITY;
		$GO_SECURITY->delete_acl($event["acl_read"]);
		$GO_SECURITY->delete_acl($event["acl_write"]);

		$sql = "DELETE FROM scEvents WHERE id='$event_id'";
		$this->query($sql);
		$sql = "DELETE FROM scEventsSchedulers WHERE event_id='$event_id'";
		$this->query($sql);
		$sql = "DELETE FROM scParticipants WHERE event_id='$event_id'";
		return $this->query($sql);
	}

	function delete_user($user_id)
	{
		$this->unsubscribe_all($user_id);

		$delete = new scheduler;
		$sql = "SELECT * FROM scSchedulers WHERE user_id='$user_id'";
		$this->query($sql);
		while($this->next_record())
		{
			$delete->delete_scheduler($this->f('id'));
		}

		$sql = "SELECT * FROM scEvents WHERE user_id='$user_id'";
		$this->query($sql);

		while($this->next_record())
		{
			$delete->delete_event($this->f('id'));
		}

	}
}
?>
