<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

$max_messages = 15;

if ($mail->open($email_ip, $email_type,$email_port,$email_username,$email_password,$mailbox))
{
	$messages_display = '';

	//when this is post request delete selected messages
	if ($REQUEST_METHOD == "POST" && isset($messages))
	{
		if ($form_action == "delete")
		{
			$mail->delete($messages);
		}else
		{
			$mail->move($folder, $messages);
		}
	}

	//determine how to sort messages and sort them
	if (!isset($mail_reverse))
			$mail_reverse = 1;
	if (!isset($mail_sort))
			$mail_sort = SORTDATE;


	$mail->sort($mail_sort, $mail_reverse);

	if ($mail_reverse == 1)
	{
		$image_string = '<img src="'.$GO_THEME->image_url.'buttons/arrow_down.gif" border="0" />';
		$mail_reverse=0;
	}else
	{
		$image_string = '<img src="'.$GO_THEME->image_url.'buttons/arrow_up.gif" border="0" />';
		$mail_reverse=1;
	}

	//count total and new messages
	$mail->count();
	$msg_count = $mail->count;

	$show = "from";

	if ($mail->is_imap())
	{
		if ($email->get_sent_folder($email_id) == $mailbox)
				$show = "to";
	}

	//loop through messages and display them
	$unseen = $mail->unseen;
	$total_size = 0;
	if ($msg_count > 0)
	{
		$email_filter = array();
		$subject_filter = array();
		$filters = array();
		//if there are new messages get the filters
		if ($mail->unseen > 0)
		{
			$email->get_filters($email_id);
			while ($email->next_record())
			{
				$filter["field"] = $email->f("field");
				$filter["folder"] = $email->f("folder");
				$filter["keyword"] = $email->f("keyword");
				$filters[] = $filter;
			}
		}
		$list_count = 0;
		while($mail->next_message() && ($list_count < $max_messages))
		{
			$continue = false;
			//check if message is new and apply users filters to new messages only in the inbox folder.
			if ($mail->f('new') == 1)
			{
				if (strtolower($mailbox) == "inbox")
				{
					for ($i=0;$i<sizeof($filters);$i++)
					{
						if ($filters[$i]["folder"])
						{
							$field = $mail->f($filters[$i]["field"]);
							if (!is_array($field))
							{
								if (eregi($filters[$i]["keyword"], $field))
								{
									$messages[] = $mail->f("uid");
									if ($mail->move($filters[$i]["folder"], $messages))
									{
										$msg_count--;
										$unseen--;
										$continue = true;
										break;
									}
								}
							}else
							{
								for ($x=0;$x<sizeof($field);$x++)
								{
									if (eregi($filters[$i]["keyword"], $field[$x]))
									{
										$messages[] = $mail->f("uid");
										if ($mail->move($filters[$i]["folder"], $messages))
										{
											$msg_count--;
											$unseen--;
											$continue = true;
											break;
										}
									}

								}
							}
						}
					}
				}

				if ($continue)
				{
					continue;
				}

				$class = ' class="Table4"';
				$image = '<img src="'.$GO_THEME->image_url.'newmail.gif" border="0" width="16" height="16" />';
			}else
			{
				$class = ' class="Table1"';
				$image = '<img src="'.$GO_THEME->image_url.'mail.gif" border="0" width="16" height="16" />';
			}

			//check if message has attachments
			if ($mail->f("attachment"))
			{
					$attachment = '<img src="'.$GO_THEME->image_url.'attach.gif" border="0" />';
			}else
			{
					$attachment = '&nbsp;';
			}

			switch ($mail->f("priority"))
			{
				case "4":
					$priority = '<img src="'.$GO_THEME->image_url.'low_priority.gif" width="8" height="16" />';
				break;

				case "2":
						$priority = '<img src="'.$GO_THEME->image_url.'high_priority.gif" width="8" height="16" />';
				break;

				default:
						$priority = '';
				break;
			}

			//display message
			$subject = $mail->f('subject') ? $mail->f('subject') : $ml_no_subject;
			$short_subject = cut_string($subject,50);
			if ($show == "from")
			{
				$short_from = cut_string($mail->f('from'),40);
			}else
			{
				$to = '';
				$to_array = $mail->f("to");
				for ($i=0;$i<sizeof($to_array);$i++)
				{
					if ($i != 0)
					{
							$to .= ", ";
					}
					$to .= $to_array[$i];
				}
				if ($to == "")
				{
						$to = $ml_no_reciepent;
				}
				$short_to = cut_string($to,50);
			}

			$total_size += $mail->f('size');
			$messages_display .= '<tr'.$class.' id="'.$mail->f('uid').'">';
			$messages_display .= '<td nowrap><input type="checkbox" name="messages[]" value="'.$mail->f('uid').'" onclick="javascript:item_click(this);" /></td>';
			$messages_display .= '<td nowrap>'.$priority.'&nbsp;</td>';
			$messages_display .= '<td nowrap>'.$attachment.'&nbsp;</td>';
			$messages_display .= '<td nowrap width="20">'.$image.'</td>';
			if ($show=="from")
			{
				$messages_display .= "<td nowrap><a href=\"".$PHP_SELF."?task=message&uid=".$mail->f('uid')."&mailbox=".$mailbox."\" title=\"".$mail->f('from')."&nbsp;&lt;".$mail->f("sender")."&gt;\">".$short_from."&nbsp;</a></td>";
			}else
			{
				$messages_display .= "<td nowrap><a href=\"".$PHP_SELF."?task=message&uid=".$mail->f('uid')."&mailbox=".$mailbox."\" title=\"".$to."\">".$short_to."&nbsp;</a></td>";
			}

			$messages_display .= "<td nowrap><a href=\"".$PHP_SELF."?task=message&uid=".$mail->f('uid')."&mailbox=".$mailbox."\" title=\"".$mail->f('subject')."\">".$short_subject."&nbsp;</a></td>";
			$messages_display .= '<td nowrap>'.format_size($mail->f('size')).'&nbsp;</td>';
			$messages_display .= '<td nowrap>'.date($ses_date_format,$mail->f('udate')).'</td>';
			$messages_display .= "</tr>\n";
			$messages_display .= '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td>';
			$messages_display .= "</tr>\n";

		}
		$messages_display .= '<tr><td colspan="99" class="small" height="18">&nbsp;'.$msg_count.' '.$ml_messages.'&nbsp;&nbsp;&nbsp;';
		if ($mail->is_imap())
		{
			$messages_display .= '('.$unseen.' '.$ml_new.')';
		}

		$messages_display .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$fbFolderSize.': '.format_size($total_size).'</td></tr>';
		$messages_display .= '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td>';
		$messages_display .= "</tr>\n";

	}
	if ($msg_count == 0)
	{
		$messages_display = '<tr><td colspan="99" height="18">'.$ml_no_messages.'</td></tr>';
		$messages_display .= '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
	}

	?>
	<table border="0" cellpadding="5" cellspacing="0">
	<tr>
			<td dir=ltr valign="top"><?php require("treeview.inc"); ?></td>
			<td valign="top">
			<input type="hidden" name="id" value="<?php echo $id; ?>" />
			<input type="hidden" name="mailbox" value="<?php echo $mailbox; ?>" />
			<input type="hidden" name="form_action" value="delete" />
			<table border="0" cellspacing="0" cellpadding="0" class="Table1">
			<tr>
					<td colspan="6" nowrap>

							<?php
							if ($email_type == "imap" && $email->get_folders($email_id) > 0)
							{
								$dropbox = new dropbox();
								$dropbox->add_value('', $ml_move_mail);
									if (strtolower($mailbox) != "inbox") $dropbox->add_value('INBOX','Inbox');
									$dropbox->add_sql_data('email', 'name', 'name');
									$dropbox->print_dropbox('folder','','onchange="javascript:move_mail()"');

							}else
							{
									echo '&nbsp;';
							}
							?>

					</td>
					<td colspan="2" align="right" class="small" nowrap>
					<?php
					echo $msg_count." ".$ml_messages;
					if ($mail->is_imap())
					{
							echo "&nbsp;&nbsp;&nbsp;(".$unseen." ".$ml_new.")";
					}
					?>
					</td>
			</tr>

			<tr>
					<td class="TableHead2" width="16"><input type="checkbox" name="dummy" value="dummy" onclick="javascript:invert_selection()" /></td>
					<td class="TableHead2" width="8">&nbsp;</td>
					<td class="TableHead2" width="6">&nbsp;</td>
					<td class="TableHead2" width="16">&nbsp;</td>
					<?php
					if ($show == "from")
					{
						echo '<td class="TableHead2" nowrap><a class="TableHead2" href="'.$PHP_SELF.'?newsort='.SORTFROM.'&newreverse='.$mail_reverse.'&mailbox='.urlencode($mailbox).'">'.$ml_from;
						if ($mail_sort == SORTFROM)
						{
								echo '&nbsp;'.$image_string;
						}
						echo '</a></td>';
					}else
					{
						echo '<td class="TableHead2" nowrap><a class="TableHead2" href="'.$PHP_SELF.'?newsort='.SORTTO.'&newreverse='.$mail_reverse.'&mailbox='.urlencode($mailbox).'">'.$ml_to;
						if ($mail_sort == SORTTO)
						{
								echo '&nbsp;'.$image_string;
						}
						echo '</a></td>';
					}
					echo '<td class="TableHead2" nowrap><a class="TableHead2" href="'.$PHP_SELF.'?newsort='.SORTSUBJECT.'&newreverse='.$mail_reverse.'&mailbox='.urlencode($mailbox).'">'.$ml_subject;
					if ($mail_sort == SORTSUBJECT)
					{
						echo '&nbsp;'.$image_string;
					}
					echo '</a></td>';

					echo '<td class="TableHead2" nowrap><a class="TableHead2" href="'.$PHP_SELF.'?newsort='.SORTSIZE.'&newreverse='.$mail_reverse.'&mailbox='.urlencode($mailbox).'">'.$ml_size;
					if ($mail_sort == SORTSIZE)
					{
						echo '&nbsp;'.$image_string;
					}
					echo '</a></td>';

					echo '<td class="TableHead2" nowrap><a class="TableHead2" href="'.$PHP_SELF.'?newsort='.SORTDATE.'&newreverse='.$mail_reverse.'&mailbox='.urlencode($mailbox).'">'.$strDate;
					if ($mail_sort == SORTDATE)
					{
						echo '&nbsp;'.$image_string;
					}
					echo '</a></td></tr>';

			echo $messages_display;
			echo '</table>';
			?>
			</td>
	</tr>
	</table>
<?php
$mail->close();
}else
{
	echo '<br /><p class="Error">'.$ml_connect_failed.' \''.$email_host.'\' ('.$email_ip.') '.$ml_at_port.': '.$email_port.'</p>';
	echo '<p class="Error">'.imap_last_error().'</p><br />'.$ml_solve_error;
	session_unregister("email_id");
}
?>


<script type="text/javascript">
<!--
function move_mail()
{
	document.forms[0].form_action.value = 'move';
	document.forms[0].submit();

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

function confirm_delete()
{
	var count = 0;

	for (var i=0;i<document.forms[0].elements.length;i++)
	{
		if(document.forms[0].elements[i].type == 'checkbox' && document.forms[0].elements[i].name != 'dummy')
		{
			if (document.forms[0].elements[i].checked == true)
			{
				count++;

			}
		}
	}
	switch (count)
	{
		case 0:
			alert("<?php echo $fbNoSelect; ?>");
		break;

		case 1:
			if (confirm("<?php echo $strDeletePrefix.$ml_message.$strDeleteSuffix; ?>"))
			{
				document.forms[0].submit();
			}
		break;

		default:
			if (confirm("<?php echo $strDeletePrefix.$strThis; ?> "+count+" <?php echo $ml_messages2.$strDeleteSuffix; ?>"))
			{
				document.forms[0].submit();
			}
		break;
	}
}

function item_click(check_box)
{
	var item = get_object(check_box.value);
	if (check_box.checked)
	{
		if (item.className == 'Table1')
		{
			item.className = 'Table2';
		}else
		{
			item.className = 'Table3';
		}
	}else
	{
		if (item.className == 'Table2')
		{
			item.className = 'Table1';
		}else
		{
			item.className = 'Table4';
		}
	}
}
//-->
</script>