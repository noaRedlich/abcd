<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

if ($mail->open($email_ip, $email_type,$email_port,$email_username,$email_password,$mailbox))
{
	if ($task == "synchronise")
	{
		$subscribed = $mail->get_subscribed();
		$email->synchronise($email_id, $subscribed);
	}

	$edit_name = isset($edit_name) ? $edit_name : '';

	if (isset($delete_folder))
	{
		if ($mail->delete_folder($delete_folder))
		{
			$email->delete_folder($email_id, $delete_folder);
		}
	}

	if ($REQUEST_METHOD == "POST")
	{
		$subscribed = $mail->get_subscribed();
		if (isset($use))
		{
			for ($i=0;$i<sizeof($subscribed);$i++)
			{
				if (!in_array($subscribed[$i], $use))
				{
					if($mail->unsubscribe($subscribed[$i]))
					{
						$email->delete_folder($email_id, $subscribed[$i]);
					}
				}
			}

			for ($i=0;$i<sizeof($use);$i++)
			{
				if (!in_array($use[$i], $subscribed))
				{
					if($mail->subscribe($use[$i]))
					{
						$email->add_folder($email_id, $use[$i]);
					}
				}
			}
		}else
		{
			for ($i=0;$i<sizeof($subscribed);$i++)
			{
				if($mail->unsubscribe($subscribed[$i]))
				{
					$email->delete_folder($email_id, $subscribed[$i]);
				}
			}
		}

		$email->set_as_sent_folder($email_id, $sent);
		$email->set_as_spam_folder($email_id, $spam);

		if (isset($new_name))
		{
			if ($mail->rename_folder($old_name, $email_mbroot.$new_name))
			{
				$email->rename_folder($email_id, $old_name, $email_mbroot.$new_name);
			}
		}

		if ($create_folder == 'true' && $name != '')
		{
			if ($mail->create_folder($email_mbroot.$name))
			{
				$email->add_folder($email_id, $email_mbroot.$name);
			}
		}
	}

	//get all the folders and the subscribed folders as an array
	//and add all missing subscribed folders to Group-Office
	$mailboxes = array();
	$mailboxes = $mail->get_mailboxes();

	//get all the Group-Office folders as an array
	$email->get_folders($email_id);
	$GO_mailboxes = array();
	$sent_folder = '';
	$spam_folder = '';
	while ($email->next_record())
	{
			$GO_mailbox_id[] = $email->f("id");
			$GO_mailboxes[] = $email->f("name");
			if ($email->f("type") == "sent")
			{
					$sent_folder = $email->f("name");
			}
			if ($email->f("type") == "spam")
			{
					$spam_folder = $email->f("name");
			}
	}
	?>
	<input type="hidden" name="task" value="folders" />
	<input type="hidden" name="create_folder" value="false" />
	<table border="0" cellpadding="0" cellspacing="8" class="normal">
	<tr>
			<td>
			<table border="0" cellpadding="2" cellspacing="0">
			<tr>
				<td>
				<?php echo $em_new_folder; ?>:
				</td>
				<td>
				<input type="text" class="textbox" name="name" value="<?php echo $em_new_folder; ?>" maxlength="100" size="30" />
				</td>
				<td>
				<?php
				$button = new button($cmdOk, 'javascript:new_folder()');
				?>
				</td>
			</tr>
			</table>
			<br />
			<table border="0" cellpadding="2" cellspacing="0">
			<tr>
					<td><h3><?php echo $strName; ?></h3></td>
					<td><h3><?php echo $ml_use; ?></h3></td>
					<td><h3><?php echo $ml_sent_items; ?></h3></td>
					<td><h3><?php echo $ml_spam; ?></h3></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
			</tr>
			<?php
			echo '<input type="hidden" name="use[]" value="INBOX" />';
			$mcount = sizeof($mailboxes);
			for ($a=0;$a<$mcount;$a++)
			{
					if ($mailboxes[$a] != "INBOX" && substr($mailboxes[$a],0,1) != '.')
					{
							$image = $GO_THEME->image_url."treeview/folder.gif";
							$old_mailboxname = !strrchr($mailboxes[$a],".") ? $mailboxes[$a] : substr(strrchr($mailboxes[$a], "."), 1);

							echo '<tr><td><table border="0" cellpadding="0" cellspacing="0">';
							echo '<tr><td><img src="'.$image.'" border="0" /></td>';
							if ($edit_name == $mailboxes[$a])
									echo '<td>&nbsp;<input class="textbox" type="text" name="new_name" value="'.$old_mailboxname.'" /><input type="hidden" name="old_name" value="'.$mailboxes[$a].'" /></td></tr>';
							else
									echo "<td>&nbsp;".$mailboxes[$a]."</td></tr>";

							echo '</table></td>';
							if (in_array($mailboxes[$a], $GO_mailboxes))
									$checked = 'checked';
							else
									$checked = '';

							echo '<td align="center"><input type="checkbox" name="use[]" value="'.$mailboxes[$a].'" '.$checked.' /></td>';
							if ($mailboxes[$a] == $sent_folder)
									$checked = 'checked';
							else
									$checked = '';

							echo '<td align="center"><input type="radio" name="sent" value="'.$mailboxes[$a].'" '.$checked.' /></td>';
							if ($mailboxes[$a] == $spam_folder)
									$checked = 'checked';
							else
									$checked = '';

							echo '<td align="center"><input type="radio" name="spam" value="'.$mailboxes[$a].'" '.$checked.' /></td>';
							echo "<td><a href='javascript:confirm_action(\"".$PHP_SELF."?task=folders&delete_folder=".urlencode($mailboxes[$a])."\",\"".rawurlencode($strDeletePrefix."'".$mailboxes[$a]."'".$strDeleteSuffix)."\")' title=\"".$strDeleteItem." '".$mailboxes[$a]."'\"><img src=\"".$GO_THEME->image_url."buttons/delete.gif\" border=\"0\"></a></td>";
							echo '<td>&nbsp;<a href="'.$PHP_SELF.'?task=folders&edit_name='.urlencode($mailboxes[$a]).'" title="'.$strEdit.' '.$mailboxes[$a].'"><img src="'.$GO_THEME->image_url.'buttons/edit.gif" border="0" /></a></td>';
							echo '</tr>';
					}
			}
			echo '<tr><td colspan="6"><br />';
			$button = new button($cmdSave, 'javascript:document.forms[0].submit()');
			echo '&nbsp;&nbsp;';
			$button = new button($ml_synchronise,'javascript:document.location=\''.$PHP_SELF.'?task=synchronise\'');
			echo '</td></tr>';
			echo "</td></tr></table>";
			echo "</td></tr></table>";
			$mail->close();
}else
{
		echo '<br /><p class="Error">'.$ml_connect_failed.' \''.$email_host.'\' ('.$email_ip.') '.$ml_at_port.': '.$email_port.'</p>';
		echo '<p class="Error">'.imap_last_error().'</p><br />'.$ml_solve_error;
		session_unregister("email_id");
}
?>
<script type="text/javascript" language="javascript">
function new_folder()
{
	document.forms[0].create_folder.value = 'true';
	document.forms[0].submit();
}
</script>
