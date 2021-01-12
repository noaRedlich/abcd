<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

require("../../Group-Office.php");
require($GO_CONFIG->class_path."imap.class.inc");
require($GO_CONFIG->class_path."email.class.inc");
require($GO_LANGUAGE->get_language_file('email'));
$mail = new imap;
$email = new email;

$id = isset($id) ? $id : 0;
$task = isset($task) ? $task : '';
$mailbox = isset($mailbox)?  $mailbox : "INBOX";

$GO_SECURITY->authenticate();
$GO_MODULES->authenticate('email');

//remember sorting passed by inbox in cookie
if (isset($newsort))
{
	SetCookie("mail_sort",$newsort,time()+3600*24*365,"/","",0);
	$mail_sort = $newsort;
}
if (isset($newreverse))
{
	SetCookie("mail_reverse",$newreverse,time()+3600*24*365,"/","",0);
	$mail_reverse = $newreverse;
}


if ($id > 0 || !session_is_registered("email_id"))
{
	if ($email->get_account($id))
	{
		if ($email->f("user_id") == $GO_SECURITY->user_id)
		{
			$email_id = $email->f("id");
			$email_ip = gethostbyname($email->f("host"));
			$email_host = $email->f('host');
			$email_port = $email->f("port");
			$email_mbroot = $email->f("mbroot");
			$email_type = $email->f("type");
			$email_username = $email->f("username");
			$email_password = $email->f("password");
			$email_address = $email->f("email");
			session_register("email_id", "email_host", "email_ip", "email_port", "email_mbroot", "email_type", "email_username", "email_password", "email_address");
		}else
		{
			require($GO_THEME->theme_path."simple_header.inc");
			require($GO_CONFIG->root_path."error_docs/403.inc");
			require($GO_THEME->theme_path."simple_footer.inc");
			exit();
		}
	}
}

if ($REQUEST_METHOD == 'POST')
{
	switch ($task)
	{
		case 'account':
			$task='accounts';
			if ($name == "" || $mail_address == "" || $port == "" || $user == "" || $pass == "" || $host == "")
			{
				$feedback = $error_missing_field;
			}elseif(!$mail->open($host, $type, $port, $user, $pass))
			{
				$feedback = '<p class="Error">'.$ml_connect_failed.' \''.$host.'\' ('.$email_ip.') '.$ml_at_port.': '.$port.'</p>';
				$feedback .= '<p class="Error">'.imap_last_error().'</p>';
			}else
			{
				if ($type == "pop3")
				{
					if (isset($account_id))
					{
						if($email->update_account($account_id, $type, $host, $port, $mbroot, $user, $pass, $name, $mail_address, $signature))
						{
							if ($account_id == $email_id)
							{
								session_unregister("email_id");
								session_unregister("email_host");
								session_unregister("email_port");
								session_unregister("email_mbroot");
								session_unregister("email_type");
								session_unregister("email_username");
								session_unregister("email_password");
								session_unregister("email_address");
							}
							$table_tabindex=0;
						}else
						{
							$feedback = $strSaveError;
						}
					}else
					{
						if($email->add_account($GO_SECURITY->user_id, $type, $host, $port, $mbroot, $user, $pass, $name, $mail_address, $signature))
						{

							$table_tabindex=0;
						}else
						{
							$feedback = $strSaveError;
						}
					}
				}else
				{
					if (isset($account_id))
					{
						if ($account_id == $email_id)
						{
							session_unregister("email_id");
							session_unregister("email_host");
							session_unregister("email_port");
							session_unregister("email_mbroot");
							session_unregister("email_type");
							session_unregister("email_username");
							session_unregister("email_password");
							session_unregister("email_address");
						}
						if(!$email->update_account($account_id, $type, $host, $port, $mbroot, $user, $pass, $name, $mail_address, $signature))
						{
							$feedback = $strSaveError;
						}else
						{
							$table_tabindex=0;
						}
					}else
					{
						$account_id = $email->add_account($GO_SECURITY->user_id, $type, $host, $port, $mbroot, $user, $pass, $name, $mail_address, $signature);
						if(!$account_id)
						{
							$feedback = $strSaveError;
						}else
						{
							$table_tabindex=0;
						}
					}

					if (!isset($feedback))
					{
						$mailboxes =  $mail->get_mailboxes();
						$subscribed =  $mail->get_subscribed();
						$email->synchronise($account_id, $subscribed);

						if (!in_array($mbroot.$ml_sent_items, $mailboxes))
						{
							$mail->create_folder($mbroot.$ml_sent_items);
						}else
						{
							if (!in_array($mbroot.$ml_sent_items, $subscribed))
							{
								$mail->subscribe($mbroot.$ml_sent_items);
							}
						}

						if (!$email->folder_exists($account_id, $mbroot.$ml_sent_items))
							$email->add_folder($account_id, $mbroot.$ml_sent_items);

						$email->set_as_sent_folder($account_id, $mbroot.$ml_sent_items);

						if (!in_array($mbroot.$ml_spam, $mailboxes))
						{
							$mail->create_folder($mbroot.$ml_spam);
						}else
						{
							if (!in_array($mbroot.$ml_spam, $subscribed))
							{
								$mail->subscribe($mbroot.$ml_spam);
							}
						}

						if (!$email->folder_exists($account_id, $mbroot.$ml_spam))
							$email->add_folder($account_id, $mbroot.$ml_spam);

						$email->set_as_spam_folder($account_id, $mbroot.$ml_spam);
					}
				}
				$mail->close();
			}
		break;

		case 'filters':
			if ($keyword != "" && $folder != "")
			{
				if ($email->add_filter($email_id, $field, $keyword, $folder))
				{
					$table_tabindex=0;
				}else
				{
					$feedback = '<p class="Error">'.$strSaveError.'</p>';
				}
			}else
			{
				$feedback = '<p class="Error">'.$error_missing_field.'</p>';
			}
		break;
	}
}


if ($task == 'message')
{
	$part = isset($part) ? $part : '';
	if ($mail->open($email_host, $email_type,$email_port,$email_username,$email_password,$mailbox))
	{
		//block email to spam folder
		if (isset($spam_uid))
		{
			$spam_folder = $email->get_spam_folder($email_id);
			if ($spam_address != '' && $spam_folder != '')
			{
				$email->add_filter($email_id, "sender", $spam_address, $spam_folder);
				$messages[] = $spam_uid;
				$mail->move($spam_folder, $messages);
				$mail->close();
				$task = '';
			}else
			{
				$feedback = '<p class="Error">Spam folder is not set. Go to the folders and set the spam folder.</p>';
			}

		}

		if (isset($delete_message))
		{
			$messages[] = $delete_message;
			$mail->delete($messages);
			$mail->close();
			$task = '';
		}
		if ($task == 'message')
		{
			$mail_sort = isset($mail_sort) ? $mail_sort : '';
			$mail_reverse = isset($mail_reverse) ? $mail_reverse : '';
			$content = $mail->get_message($uid, $mail_sort, $mail_reverse, 'html', $part);
			$subject = isset($content["subject"]) ? $content["subject"] : $ml_no_subject;
		}
	}else
	{
		require($GO_CONFIG->theme_path.'header.inc');
		echo '<table border="0" cellpadding="10" width="100%"><tr><td>';
		echo '<p class="Error">'.$ml_connect_failed.'</p>';
		echo '<p class="Error">'.imap_last_error().'</p>';
		require($GO_CONFIG->theme_path.'footer.inc');
		exit();
	}
}


$page_title = "E-mail";
$email_type = isset($email_type) ? $email_type : '';

require($GO_THEME->theme_path."header.inc");


echo '<table border="0" cellspacing="5" cellpadding="10" width="100%"><tr><td>';
?>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="5"></td>
	<td><h1>
	<?php
	if (isset($email_address))
	{
		if (!isset($mailbox))
				$mailbox = "INBOX";

		echo $mailbox." - ".$email_address;
	}else
	{
		echo $ml_welcome;
	}
	?>
	</h1>
	</td>
</tr>
</table>
<table border="0" cellspacing="10" cellpadding="0">
<tr>
	<td align="center" nowrap>
	<a class="small" href="javascript:popup('send.php','650','500')"><img src="<?php echo $GO_THEME->image_url; ?>buttons/compose.gif" border="0" height="32" width="32" /><br /><?php echo $ml_compose; ?></a></td>
	<?php
	echo '<td align="center" width="60" nowrap><a class="small" href="'.$PHP_SELF.'?mailbox='.$mailbox.'"><img src="'.$GO_THEME->image_url.'buttons/email.gif" border="0" height="32" width="32" /><br />'.$ml_inbox.'</a></td>';
	if ($task != 'message')
	{
		echo '<td align="center" nowrap>';
		echo '<a class="small" href="'.$PHP_SELF.'?task=accounts"><img src="'.$GO_THEME->image_url.'buttons/accounts.gif" border="0" height="32" width="32" /><br />'.$ml_accounts.'</a></td>';

		if ($email_type == "imap" && session_is_registered("email_id"))
		{
			echo '<td align="center" nowrap>';
			echo "<a class=\"small\" href=\"".$PHP_SELF."?task=folders\"><img src=\"".$GO_THEME->image_url."buttons/folders.gif\" border=\"0\" height=\"32\" width=\"32\" /><br />".$ml_folders."</a></td>";
			echo '<td align="center" nowrap>';
			echo "<a class=\"small\" href=\"".$PHP_SELF."?task=filters\"><img src=\"".$GO_THEME->image_url."buttons/filters.gif\" border=\"0\" height=\"32\" width=\"32\" /><br />".$ml_filters."</a></td>";
		}
		if ($task == '' && session_is_registered("email_id"))
		{
			echo '<td align="center" nowrap><a class="small" href="'.$PHP_SELF.'?id='.$email_id.'mailbox=INBOX"><img src="'.$GO_THEME->image_url.'buttons/refresh.gif" border="0" height="32" width="32" /><br />'.$ml_refresh.'</a></td>';
			echo '<td align="center" nowrap><a class="small" href="javascript:confirm_delete()"><img src="'.$GO_THEME->image_url.'buttons/delete_big.gif" border="0" height="32" width="32" /><br />'.$ml_delete.'</a></td>';
		}
	}else
	{
		echo '<td align="center">';
		echo "<a class=\"small\" href=\"javascript:popup('send.php?uid=".$uid."&mailbox=".urlencode($mailbox)."&action=reply','640','500')\"><img src=\"".$GO_THEME->image_url."buttons/reply.gif\" border=\"0\" height=\"32\" width=\"32\" /><br />".$ml_reply."</a></td>\n";
		echo '<td align="center" nowrap>';
		echo "<a class=\"small\" href=\"javascript:popup('send.php?uid=".$uid."&mailbox=".urlencode($mailbox)."&action=reply_all','640','500')\"><img src=\"".$GO_THEME->image_url."buttons/reply_all.gif\" border=\"0\" height=\"32\" width=\"32\" /><br />".$ml_reply_all."</a></td>\n";
		echo '<td align="center" nowrap>';
		echo "<a class=\"small\" href=\"javascript:popup('send.php?uid=".$uid."&mailbox=".urlencode($mailbox)."&action=forward','640','500')\"><img src=\"".$GO_THEME->image_url."buttons/forward.gif\" border=\"0\" height=\"32\" width=\"32\" /><br />".$ml_forward."</a></td>\n";
		echo '<td align="center" nowrap>';
		echo "<a class=\"small\" href=\"javascript:popup('properties.php?uid=".$uid."&mailbox=".urlencode($mailbox)."','450','500')\"><img src=\"".$GO_THEME->image_url."buttons/properties.gif\" border=\"0\" height=\"32\" width=\"32\" /><br />".$fbProperties."</a></td>\n";

		echo '<td align="center" nowrap>';
		echo '<a class="small" href="javascript:confirm_delete()"><img src="'.$GO_THEME->image_url.'buttons/delete_big.gif" border="0" height="32" width="32" /><br />'.$ml_delete.'</a></td>';
		echo '<td align="center"  nowrap>';
		echo '<a class="small" href="javascript:popup(\'print_message.php?uid='.$uid.'&mailbox='.urlencode($mailbox).'\',\'\',\'\')"><img src="'.$GO_THEME->image_url.'buttons/print.gif" border="0" height="32" width="32" /><br />'.$ml_print.'</a></td>';
		if ($mail->is_imap())
		{
			echo '<td align="center" nowrap>';
			echo '<a class="small" href="'.$PHP_SELF.'?task=message&spam_uid='.$uid.'&spam_address='.$content["sender"].'&mailbox='.urlencode($mailbox).'"><img src="'.$GO_THEME->image_url.'buttons/block.gif" border="0" height="32" width="32" /><br />'.$ml_block.'</a></td>';
		}

		if ($content["previous"] != 0)
		{
			echo '<td align="center" nowrap>';
			echo '<a class="small" href="'.$PHP_SELF.'?task=message&uid='.$content["previous"].'&mailbox='.urlencode($mailbox).'"><img src="'.$GO_THEME->image_url.'buttons/previous.gif" border="0" height="32" width="32" /><br />'.$cmdPrevious.'</a></td>';
		}

		if ($content["next"] != 0)
		{
			echo '<td align="center" nowrap>';
			echo '<a class="small" href="'.$PHP_SELF.'?task=message&uid='.$content["next"].'&mailbox='.urlencode($mailbox).'"><img src="'.$GO_THEME->image_url.'buttons/next.gif" border="0" height="32" width="32" /><br />'.$cmdNext.'</a></td>';
        }
	}
	?>
</tr>
</table>
<form method="POST" action="<?php echo $PHP_SELF; ?>">
<?php
switch ($task)
{
	case 'message':
		require('message.inc');
		$mail->close();
	break;

	case 'accounts':
		//Add the tab names with thier associated documents
		$table_docs[] = "accounts.inc";
		$table_tabs[] = $ml_accounts;
		$table_docs[] = "account.inc";
		$table_tabs[] = $cmdAdd;
		if (isset($account_id))
		{
			$table_docs[] = 'account.inc';
			$table_tabs[] = $ml_properties;
		}
		$table_title = $ml_your_accounts;
		$table_width = "600";
		$table_height = "300";
		$table_arguments = '&task=accounts';
		echo '<input type="hidden" name="task" value="accounts" />';
		require($GO_CONFIG->control_path."html_table.inc");
	break;

	case 'folders':
		$table_title = $ml_folders;
		$table_width = "600";
		$table_height = "300";
		$table_docs[] = "folders.inc";
		echo '<input type="hidden" name="task" value="folders" />';
		require($GO_CONFIG->control_path."html_table.inc");
	break;

	case "synchronise":
		$table_title = $ml_folders;
		$table_width = "600";
		$table_height = "300";
		$table_docs[] = "folders.inc";
		echo '<input type="hidden" name="task" value="folders" />';
		require($GO_CONFIG->control_path."html_table.inc");
	break;

	case 'filters':
		$table_title = $ml_filters;
		$table_width = "600";
		$table_height = "300";
		$table_docs[] = "filters.inc";
		$table_tabs[] = $ml_filters;
		$table_docs[] = "filter.inc";
		$table_tabs[] = $cmdAdd;
		$table_arguments = '&task=filters';
		echo '<input type="hidden" name="task" value="filters" />';
		require($GO_CONFIG->control_path."html_table.inc");
	break;

	default:
		if (session_is_registered("email_id"))
		{
		  	require("navigation.inc");
		}else
		{
        	echo '<br /><p class="normal"><b>'.$ml_no_accounts.'</b></p><br /><p class="normal">'.$ml_text.'</p>';
		}

    break;
}

echo '</form></td></tr></table>';
require($GO_THEME->theme_path."footer.inc");
?>
