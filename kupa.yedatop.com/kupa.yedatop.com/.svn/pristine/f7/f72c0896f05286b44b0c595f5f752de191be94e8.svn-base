<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0  Release date: 14 March 2003									//
// Version: 1.02 Release date: 24 April 2003									//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

require("../../Group-Office.php");

require($GO_CONFIG->class_path."scheduler.class.inc");
$scheduler = new scheduler();
require($GO_LANGUAGE->get_language_file('scheduler'));

if ($REQUEST_METHOD == "POST")
{
	if (isset($schedulers))
	{
		$scheduler->set_event_status($event_id, '1', $email);
		while($scheduler_id = array_shift($schedulers))
		{
			if (!$scheduler->event_is_subscribed($event_id, $scheduler_id))
			{
				if ($scheduler->subscribe_event($event_id, $scheduler_id))
				{
					if (!$scheduler->set_event_status($event_id, '1', $email))
					{
						$error = true;
					}
				}else
				{
					$error = true;
				}
			}
		}
		require($GO_THEME->theme_path.'header.inc');
		echo '<table border="0" cellpadding="10" cellspacing="0"><tr><td><h1>'.$sc_accept_title.'</h1>';
		echo $sc_accept_confirm;

		if (isset($error))
			echo '<p class="Error">'.$strSaveError.'</p>';

		echo '</td></tr></table>';
		require($GO_THEME->theme_path.'footer.inc');
		exit();
	}else
	{
		$feedback = $sc_select_scheduler_please;
	}
}


$event = $scheduler->get_event($event_id);
if ($event && $email != '')
{
	if ($member == "true")
	{
		$GO_SECURITY->authenticate();
		$module = $GO_MODULES->get_module('scheduler');
		if ($GO_SECURITY->has_permission($GO_SECURITY->user_id, $module['acl_read']) || $GO_SECURITY->has_permission($GO_SECURITY->user_id, $module['acl_write']))
		{
			require($GO_THEME->theme_path.'header.inc');

			echo '<table border="0" cellpadding="10" cellspacing="0"><tr><td><h1>'.$sc_accept_title.'</h1></td></tr><tr><td>';

			if (isset($feedback))
			{
				echo '<p class="Error">'.$feedback.'</p>';
			}
			if (!$GO_SECURITY->has_permission($GO_SECURITY->user_id, $event["acl_read"]) && !$GO_SECURITY->has_permission($GO_SECURITY->user_id, $event["acl_write"]))
			{
				$GO_SECURITY->add_user_to_acl($GO_SECURITY->user_id, $event["acl_read"]);
			}


			$scheduler_count = $scheduler->get_subscribed($GO_SECURITY->user_id);
			$scheduler_id = isset($scheduler_id) ? $scheduler_id : 0;

			if($scheduler_count > 1)
			{
				echo $sc_select_scheduler.': ';
				echo '<form name="accept" method="post" action="'.$PHP_SELF.'">';
				echo '<input type="hidden" name="email" value="'.$email.'" />';
				echo '<input type="hidden" name="event_id" value="'.$event_id.'" />';
				echo '<input type="hidden" name="member" value="'.$member.'" />';
				echo '<table border="0"><tr><td valign="top"><?php echo $sc_put_in; ?>:</td>';
				echo '<td><table border="0">';
				while ($scheduler->next_record())
				{
					if ($GO_SECURITY->has_permission($GO_SECURITY->user_id, $scheduler->f('acl_write')))
					{
						$schedulers_check = (isset($schedulers) && in_array($scheduler->f('id'), $schedulers)) ? 'checked' : '';
						echo '<tr><td><input type="checkbox" name="schedulers[]" value="'.$scheduler->f('id').'" '.$schedulers_check.'  /></td><td>'.$scheduler->f('name').'</td></tr>';
					}
				}
				echo '</table></td></tr></table>';
				$button = new button($cmdOk, "javascript:document.forms[0].submit();");
				echo '</form>';
			}else
			{
				if ($scheduler_count == 1)
				{
					$scheduler->next_record();
					$scheduler_id = $scheduler->f('id');
					if ($scheduler_id > 0)
					{
						if (!$scheduler->event_is_subscribed($event_id, $scheduler_id))
						{
							if ($scheduler->subscribe_event($event_id, $scheduler_id))
							{
								if ($scheduler->set_event_status($event_id, '1', $email))
								{
									echo $sc_accept_confirm;
								}else
								{
									echo $strSaveError;
								}
							}else
							{
								echo $strSaveError;
							}
						}else
						{
							if ($scheduler->set_event_status($event_id, '1', $email))
							{
								echo $sc_accept_confirm;
							}else
							{
								echo $strSaveError;
							}
						}
					}
				}else
				{
					echo $sc_no_schedulers;
				}
			}
		}else
		{
			require($GO_THEME->theme_path.'header.inc');
			echo '<table border="0" cellpadding="10" cellspacing="0"><tr><td><h1>'.$sc_accept_title.'</h1>';
			if ($scheduler->set_event_status($event_id,'1', $email))
			{
				echo $sc_accept_confirm;
			}
		}
	}else
	{
		require($GO_THEME->theme_path.'header.inc');
		echo '<table border="0" cellpadding="10" cellspacing="0"><tr><td><h1>'.$sc_accept_title.'</h1>';
		if ($scheduler->set_event_status($event_id,'1', $email))
		{
			echo $sc_accept_confirm;
		}
	}
}else
{
	require($GO_THEME->theme_path.'header.inc');

	echo '<table border="0" cellpadding="10" cellspacing="0"><tr><td class="Error"><h1>'.$sc_accept_title.'</h1>';

	echo $sc_bad_event;
}

echo '</td></tr></table>';
require($GO_THEME->theme_path.'footer.inc');
?>
