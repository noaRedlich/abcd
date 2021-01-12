
function sort(column)
{
	document.forms[0].newsort.value = column;
	document.forms[0].submit();
}
function select_all()
{
	for (var i = 0; i < document.forms[0].elements.length; i++)
	{
		if(document.forms[0].elements[i].name.substr(0, 7) == 'contact')
		{
			document.forms[0].elements[i].checked = !(document.forms[0].elements[i].checked);
		}
	}
}

function select_group(group_id)
{
	var add = false;

	for (var i = 0; i < document.forms[0].elements.length; i++)
	{
		if (document.forms[0].elements[i].name == 'group_start_'+group_id)
		{
			add = true;
		}

		if (document.forms[0].elements[i].name == 'group_end_'+group_id)
		{
			add = false;
		}

		if(document.forms[0].elements[i].name.substr(0, 7) == 'contact' && add==true)
		{
			document.forms[0].elements[i].checked = !(document.forms[0].elements[i].checked);
		}
	}
}

function toggle_ending_date()
{
	if (document.forms[0].ending_day.disabled==true)
	{
  		document.forms[0].ending_day.disabled=false;
		document.forms[0].ending_month.disabled=false;
		document.forms[0].ending_year.disabled=false;
	}else
	{
 		document.forms[0].ending_day.disabled=true;
		document.forms[0].ending_month.disabled=true;
		document.forms[0].ending_year.disabled=true;
	}
}

function disable_time()
{
	if (document.forms[0].start_hour.disabled==false)
	{
		document.forms[0].start_hour.disabled=true;
		document.forms[0].start_min.disabled=true;
		document.forms[0].end_hour.disabled=true;
		document.forms[0].end_min.disabled=true;
	}else
	{
		document.forms[0].start_hour.disabled=false;
		document.forms[0].start_min.disabled=false;
		document.forms[0].end_hour.disabled=false;
		document.forms[0].end_min.disabled=false;
	}
}

function toggle_recur(recur)
{
	document.forms[0].recur_type.value = recur;
	switch(recur)
	{
		case 'once':
			disable_days(true);
			disable_date(false);
			document.forms[0].month_time.disabled = true;
			disable_ending_date(true);
			disable_starting_date(true);
		break;

		case 'daily':
			disable_days(true);
			disable_date(true);
			document.forms[0].month_time.disabled = true;
			disable_ending_date(false);
			disable_starting_date(false);
		break;

		case 'weekly':
			disable_days(false);
			disable_date(true);
			document.forms[0].month_time.disabled = true;
			disable_ending_date(false);
			disable_starting_date(false);
		break;

		case 'month_date':
			disable_days(true);
			disable_date(false);
			document.forms[0].start_month.disabled=true;
			document.forms[0].end_month.disabled=true;

			document.forms[0].month_time.disabled = true;
			disable_ending_date(false);
			disable_starting_date(false);
		break;

		case 'month_day':
			disable_days(false);
			disable_date(true);
			document.forms[0].month_time.disabled = false;
			disable_ending_date(false);
			disable_starting_date(false);
		break;

		case 'yearly':
			disable_days(true);
			disable_date(false);
			document.forms[0].month_time.disabled = true;
			disable_ending_date(false);
			disable_starting_date(false);
		break;
	}

}

function disable_days(disable)
{
	document.forms[0].recur_days_0.disabled=disable;
	document.forms[0].recur_days_1.disabled=disable;
	document.forms[0].recur_days_2.disabled=disable;
	document.forms[0].recur_days_3.disabled=disable;
	document.forms[0].recur_days_4.disabled=disable;
	document.forms[0].recur_days_5.disabled=disable;
	document.forms[0].recur_days_6.disabled=disable;

}

function disable_date(disable)
{
	document.forms[0].start_day.disabled=disable;
	document.forms[0].start_month.disabled=disable;
	document.forms[0].end_day.disabled=disable;
	document.forms[0].end_month.disabled=disable;

}

function disable_starting_date(disable)
{
	document.forms[0].starting_day.disabled=disable;
	document.forms[0].starting_month.disabled=disable;
	document.forms[0].starting_year.disabled=disable;
}

function disable_ending_date(disable)
{
	document.forms[0].noend.disabled=disable;
	if (disable == true || (disable==false && document.forms[0].noend.checked == false))
	{
		document.forms[0].ending_day.disabled=disable;
		document.forms[0].ending_month.disabled=disable;
		document.forms[0].ending_year.disabled=disable;
	}
}

function load_event(event_id)
{
	document.forms[0].event_id.value = event_id;
	document.forms[0].post_action.value = 'load_event';
	document.forms[0].submit();
}
function new_event(hour)
{
	document.forms[0].event_id.value = 0;
	document.forms[0].hour.value = hour;
	document.forms[0].post_action.value='new_event';
	document.forms[0].submit();
}
function delete_event(event_id, text)
{
	if (confirm(unescape(text)))
	{
		document.forms[0].event_id.value = event_id;
		document.forms[0].post_action.value = 'delete_event';
		document.forms[0].submit();
	}
}
function unsubscribe_event(event_id, text)
{
	if (confirm(unescape(text)))
	{
		document.forms[0].event_id.value = event_id;
		document.forms[0].post_action.value = 'unsubscribe_event';
		document.forms[0].submit();
	}
}
function post_week(weekday)
{
	document.forms[0].post_action.value = 'week';
	document.forms[0].day.value = weekday;
	document.forms[0].submit();
}

function post_day(day)
{
	document.forms[0].day.value = day;
	document.forms[0].post_action.value='day';
	document.forms[0].submit();
}

function post_date(day, month, year)
{
	document.forms[0].post_action.value='day';
	document.forms[0].day.value = day;
	document.forms[0].month.value = month;
	document.forms[0].year.value = year;
	document.forms[0].submit();
}

function add_scheduler()
{
	document.forms[0].post_action.value='add_scheduler';
	document.forms[0].submit();
}

function load_scheduler(scheduler_id)
{
	document.forms[0].scheduler_id.value = scheduler_id;
	document.forms[0].post_action.value='load_scheduler';
	document.forms[0].submit();
}
function load_schedulers()
{
	document.forms[0].post_action.value='schedulers';
	document.forms[0].submit();
}

function delete_scheduler(scheduler_id,message)
{
	if (confirm(unescape(message)))
	{
		document.forms[0].scheduler_id.value = scheduler_id;
		document.forms[0].post_action.value='delete_scheduler';
		document.forms[0].submit();
	}
}

function subscribe()
{
	document.forms[0].post_action.value = "subscribe";
	document.forms[0].submit();
}


function set_primary()
{
	document.forms[0].post_action.value = "set_primary";
	document.forms[0].submit();
}

function change_scheduler(scheduler_id)
{
	document.forms[0].scheduler_id.value = scheduler_id;
	document.forms[0].post_action.value = "week";
	document.forms[0].submit();
}

function change_location()
{
	if (document.forms[0].location_id.value == "0")
	{
		document.forms[0].location_text.disabled=false;
	}else
	{
		document.forms[0].location_text.disabled=true;
	}
}

function show_events()
{
	document.forms[0].post_action.value = "show_events";
	document.forms[0].submit();	
}
