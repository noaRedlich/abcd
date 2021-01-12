<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/


$num_attach = isset($num_attach) ? $num_attach : 0;
$mail_from = isset($mail_from) ? $mail_from : '';
$mail_body = isset($mail_body) ? $mail_body : '';

//reset attachments array in case user aborted a message or changed format
if (($REQUEST_METHOD != "POST" && !isset($email_file)) || $sendaction == 'change_format')
{
	session_unregister("attach_array");
	session_unregister("num_attach");
	unset($attach_array);
	unset($num_attach);
	$add_recievers = 'true';
}

//get users email accounts to determine from addresses
$count = $email->get_accounts($GO_SECURITY->user_id);
while ($email->next_record())
{
	$addresses[] = $email->f("email");
	$names[] = $email->f("email")." (".$email->f("name").")";
	$ids[] = $email->f("id");
}

//if a uid is given then the user is replying or forwarding
if (isset($uid) && $uid > 0)
{
	//get the original message
	require($GO_CONFIG->class_path."imap.class.inc");
	$mail = new imap;

	if ($mail->open($email_host, $email_type, $email_port, $email_username, $email_password, $mailbox))
	{
		$content = $mail->get_message($uid,"","","html");
		$parts = array_reverse($mail->f("parts"));
	}

	//fill in the header fields
	$subject = isset($content['subject']) ? $content['subject'] : $ml_no_subject;
	switch($action)
	{
		case "reply":
			$mail_to = $content["sender"];
			$mail_subject = "Re: ".$subject;
		break;

		case "reply_all":
			$mail_to = $content["sender"];
			$mail_subject = "Re: ".$subject;

			//add all recievers from this email
			for ($i=0;$i<sizeof($content["to"]);$i++)
			{
				$lt = strpos($content["to"][$i], "&lt") + 4;
				$gt = strpos($content["to"][$i], "&gt");

				$length = $gt-$lt;

				$address = trim(substr($content["to"][$i],$lt,$length));

				if ($address != "" && !in_array($address,$addresses))
				{
						$mail_to .= ";".$address;
				}
			}

		break;

		case "forward":
			//reattach attachments
			$mail_subject = "Fwd: ".$subject;

			for ($i=0;$i<count($parts);$i++)
			{
				if (eregi("attachment", $parts[$i]["disposition"]) && !eregi("message/RFC822", $parts[$i]["mime"]))
				{
					$file = $mail->view_part($uid, $parts[$i]["number"], $parts[$i]["transfer"], $parts[$i]["mime"]);

					$tmp_file = $GO_CONFIG->tmpdir.basename($parts[$i]["name"].'.att');
					$fp = fopen($tmp_file,"wb");
					fwrite ($fp,$file);
					fclose($fp);
					$email->register_attachment($tmp_file, $parts[$i]["name"], $parts[$i]["size"], $parts[$i]["mime"],$parts[$i]["id"]);

				}
			}

		break;
	}

	//reatach inline attachements
	for ($i=0;$i<count($parts);$i++)
	{
		if ($parts[$i]["id"] != '')
		{
			$file = $mail->view_part($uid, $parts[$i]["number"], $parts[$i]["transfer"], $parts[$i]["mime"]);

			$tmp_file = $GO_CONFIG->tmpdir.basename($parts[$i]["name"].'.att');
			$fp = fopen($tmp_file,"wb");
			fwrite ($fp,$file);
			fclose($fp);

			$email->register_attachment($tmp_file, $parts[$i]["name"], $parts[$i]["size"], $parts[$i]["mime"],$parts[$i]["id"]);

			//Content-ID's that need to be replaced with urls when message is send
			$replace_url[] = $GO_CONFIG->host."email/open_attachment.php?mailbox=".$mailbox."&amp;uid=".$uid."&amp;part=".$parts[$i]["number"]."&amp;transfer=".$parts[$i]["transfer"]."&amp;mime=".$parts[$i]["mime"]."&amp;filename=".urlencode($parts[$i]["name"]);
			$replace_id[] = '<'.$parts[$i]["id"].'>';


		}
	}

	session_unregister('attach_array');
	session_register('num_attach', 'attach_array');

	$html_message_count = 0;
	for ($i=0;$i<count($parts);$i++)
	{
		if($content_type=='text/HTML')
		{
			if ((strtolower($parts[$i]["mime"]) == "text/plain" || strtolower($parts[$i]["mime"]) == "text/html") && !eregi("ATTACHMENT", $parts[$i]["disposition"]))
			{
				if (strtolower($parts[$i]["mime"]) == "text/plain")
				{
					$html_part = text_to_html($mail->view_part($uid, $parts[$i]["number"], $parts[$i]["transfer"]));
				}else
				{
					$html_part = $mail->view_part($uid, $parts[$i]["number"], $parts[$i]["transfer"]);
				}
				$mail_body .= addslashes($html_part);
			}
		}else
		{
			if (strtolower($parts[$i]["mime"]) == "text/plain" && !eregi("ATTACHMENT", $parts[$i]["disposition"]))
			{
				$text_part = $mail->view_part($uid, $parts[$i]["number"], $parts[$i]["transfer"]);
				if ($action == 'forward')
					$mail_body .= $text_part;
				else
					$mail_body .= quote($text_part);
			}

			//add html messages as an attachment since we don't have an html editor to display it coreect yet
			if (strtolower($parts[$i]["mime"]) == "text/html" && !eregi("ATTACHMENT", $parts[$i]["disposition"]))
			{
				if ($parts[$i]["name"] == '' && $parts[$i]["mime"] == "text/HTML")
				{
					if ($html_message_count == 0)
						$parts[$i]["name"] = $content["sender"].".html";
					else
						$parts[$i]["name"] = $content["sender"]."(".$html_message_count.").html";

					$html_message_count++;
				}

				$file = $mail->view_part($uid, $parts[$i]["number"], $parts[$i]["transfer"], $parts[$i]["mime"]);

				$tmp_file = $GO_CONFIG->tmpdir.$parts[$i]["name"].'.att';
				$fp = fopen($tmp_file,"w");
				fwrite ($fp,$file);
				fclose($fp);

				$email->register_attachment($tmp_file, $parts[$i]["name"], $parts[$i]["size"], $parts[$i]["mime"]);
			}
		}
	}

	if ($content_type=='text/HTML')
	{
		if ($mail_body != '')
		{
			if (isset($replace_url) && isset($replace_id))
			{
				for ($i=0;$i<count($replace_url);$i++)
				{
					$id = "cid:".substr($replace_id[$i], 1,strlen($replace_id[$i])-2);
					$mail_body=str_replace($id, $replace_url[$i], $mail_body);
				}
			}
		}
		$header_om  = '<font face="verdana" size="2">'.$ml_original_follows."<br />";
		$om_to = '';
		if (isset($content))
		{
			$header_om .= "<b>".$ml_subject.":&nbsp;</b>".addslashes($subject)."<br />";
			$header_om .= "<b>".$ml_from.":&nbsp;</b>".$content["sender"]."<br />";
			for ($i=0;$i<sizeof($content["to"]);$i++)
			{
				$lt = strpos($content["to"][$i], "&lt") + 4;
				$gt = strpos($content["to"][$i], "&gt");

				$length = $gt-$lt;

				$address = trim(substr($content["to"][$i],$lt,$length));
				if ($i!=0)	$om_to .= ';';
				$om_to .= $address;

			}
			$header_om .= "<b>".$ml_to.":&nbsp;</b>".addslashes($om_to)."<br />";
			$header_om .= "<b>".$strDate.":&nbsp;</b>".date($ses_date_format,$content["udate"])."<br />";
		}
		$header_om .= "</font><br /><br />";

		$mail_body = '<br /><blockquote>'.$header_om.$mail_body.'</blockquote>';
		$mail_body = '<html><head><meta http-equiv=Content-Type content="text/html; charset='.$charset.'"><meta content="'.$GO_CONFIG->title.' '.$GO_CONFIG->version.'" name="GENERATOR"><style>P {margin: 0px;}</style></head><body>'.$mail_body.'</body></html>';
	}else
	{
		$header_om  = $ml_original_follows."\r\n";
		if (isset($content))
		{
			$header_om .= $ml_subject.": ".$subject."\r\n";
			$header_om .= $ml_from.": ".$content["sender"]."\r\n";
			$om_to = '';
			for ($i=0;$i<sizeof($content["to"]);$i++)
			{
				$lt = strpos($content["to"][$i], "&lt") + 4;
				$gt = strpos($content["to"][$i], "&gt");

				$length = $gt-$lt;

				$address = trim(substr($content["to"][$i],$lt,$length));
				if ($i!=0)	$om_to .= ';';
				$om_to .= $address;

			}
			$header_om .= $ml_to.": ".$om_to."\r\n";
			$header_om .= $strDate.": ".date($ses_date_format,$content["udate"])."\r\n\r\n\r\n";
		}

		if ($html_message_count > 0)
		{
			$mail_body = $ml_html_message_attached."\r\n\r\n".$header_om.$mail_body;
		}else
		{
			$mail_body = $header_om.$mail_body;
		}
	}
    $mail->close();
}

?>
<table border="0" cellpadding="10" cellspacing="0" width="100%" height="100%">
<tr>
	<td width="100%" height="100%" valign="top">
		<form name="sendform" enctype="multipart/form-data" method="post" action="<?php echo $PHP_SELF; ?>">
		<input type="hidden" name="sendaction" value="" />
		<?php
		if(isset($uid) && $uid > 0)
		{
			echo '<input type="hidden" name="uid" value="" />';
			echo '<input type="hidden" name="mailbox" value="'.$mailbox.'" />';
			echo '<input type="hidden" name="action" value="'.$action.'" />';
		}
		?>
		<input type="hidden" name="num_attach" value="<?php if (isset($num_attach)) echo $num_attach; ?>" />
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $GO_CONFIG->max_attachment_size; ?>" />

		<table  cellspacing="2" cellpadding="1" border="0" width="100%" height="100%">
			<?php
			if ($count > 0)
			{
				echo '<tr><td>'.$ml_from.':</td><td colspan="2">';
				$dropbox = new dropbox();
				$dropbox->add_arrays($ids,$names);
				$dropbox->print_dropbox('mail_from',$mail_from);
				echo '</td></tr>';
			}
			?>
			<tr>
				<td>
					<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td valign="center">
						<a class="normal" href="javascript:open_addressbook('mail_to',document.sendform.mail_to.value)">
						<img src="<?php echo $GO_THEME->image_url; ?>buttons/addressbook.gif" width="16" height="16" border="0" /></a>&nbsp;
						</td>
						<td valign="center">
						<a class="normal" href="javascript:open_addressbook('mail_to',document.sendform.mail_to.value)"><?php echo $ml_to ?></a> :
						</td>
					</tr>
					</table>
				</td>
				<td colspan="2"><input class="textbox" type="text" name="mail_to" size="95" value="<?php echo (isset($mail_to) ? htmlspecialchars($mail_to) : ''); ?>" /></td>
			</tr>
			<tr>
				<td>
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td valign="center">
						<a class="normal" href="javascript:open_addressbook('mail_cc',document.sendform.mail_cc.value)">
						<img src="<?php echo $GO_THEME->image_url; ?>buttons/addressbook.gif" width="16" height="16" border="0" /></a>&nbsp;
						</td>
						<td valign="center">
						<a class="normal" href="javascript:open_addressbook('mail_cc',document.sendform.mail_cc.value)">CC</a> :
						</td>
					</tr>
					</table>
				</td>
				<td colspan="2"><input class="textbox" type="text" name="mail_cc" size="95" value="<?php echo (isset($mail_cc) ? htmlspecialchars($mail_cc) : '') ?>" /></td>
			</tr>
			<tr>
				<td>
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td valign="center">
						<a class="normal" href="javascript:open_addressbook('mail_bcc',document.sendform.mail_bcc.value)"><img src="<?php echo $GO_THEME->image_url; ?>buttons/addressbook.gif" width="16" height="16" border="0" /></a>&nbsp;
						</td>
						<td valign="center">
						<a class="normal" href="javascript:open_addressbook('mail_bcc',document.sendform.mail_bcc.value)">BCC</a> :
						</td>
					</tr>
					</table>
				</td>
				<td colspan="2"><input class="textbox" type="text" name="mail_bcc" size="95" value="<?php echo (isset($mail_bcc) ? htmlspecialchars($mail_bcc) : '') ?>" /></td>
			</tr>
			<tr>
				<td ><?php echo $ml_subject ?> : </td>
				<td colspan="2"><input class="textbox" type="text" name="mail_subject" size="95" value="<?php echo (isset($mail_subject) ? htmlspecialchars($mail_subject) : '') ?>" /></td>
			</tr>
			<tr>
				<td ><?php echo $ml_attachment ?> : </td>
				<td colspan="2" nowrap>
					<input class="textbox" type="file" name="mail_att" maxlength="200" size="30" value="" />
					<?php
					$button = new button($ml_attach, 'javascript:attach()');
					echo '&nbsp;&nbsp;';
					if ($module = $GO_MODULES->get_module('filesystem'))
					{
						if ($GO_SECURITY->has_permission($GO_SECURITY->user_id, $module['acl_read']) || $GO_SECURITY->has_permission($GO_SECURITY->user_id, $module['acl_write']))
						{
							$button = new button($ml_online_files, "javascript:popup('select_file.php','600','400')");
						}
					}
					?>
				</td>
			</tr>
			<tr>
				<td valign="top"><?php echo $ml_priority ?> : </td>
				<td valign="top">
				<?php
				if(!isset($priority)) $priority = '3 (Normal)';

				$dropbox = new dropbox();
				$dropbox->add_value('2 (High)',$ml_high);
				$dropbox->add_value('3 (Normal)',$ml_normal);
				$dropbox->add_value('4 (Low)',$ml_low);
				$dropbox->print_dropbox('priority',$priority);
				$notification_check = isset($notification) ? 'checked' : '';
				$add_recievers_check = isset($add_recievers) ? 'checked' : '';
				?>
				&nbsp;&nbsp;
				</td>
				<td <?php if ($wysiwyg) echo 'rowspan="2"'; ?>>
					<input type="checkbox" name="notification" value="true" <?php echo $notification_check; ?> /><?php echo $ml_notification; ?><br />
					<input type="checkbox" name="add_recievers" value="true" <?php echo $add_recievers_check; ?> /><?php echo $ml_add_recievers; ?>
				</td>
			</tr>
			<?php
			if ($wysiwyg)
			{
				echo '<tr><td>'.$ml_format.':</td><td>';
				$dropbox = new dropbox();
				$dropbox->add_value('text/PLAIN', 'text');
				$dropbox->add_value('text/HTML', 'HTML');
				$dropbox->print_dropbox('content_type',$content_type, 'onchange="javascript:change_format()"');
				echo '</td><td></td></tr>';
			}else
			{
				echo '<input type="hidden" value="text/PLAIN" name="content_type" />';
			}

			if (isset($num_attach) && ($num_attach > 0))
			{
				echo '<tr><td colspan="3">';
				echo '<table border="0" cellspacing="2"><tr>';
				$totalsize = 0;
				$splitter=0;
				for ($i=1;$i<=$num_attach;$i++)
				{
					$splitter++;
					$totalsize += $attach_array[$i]->file_size;
					$att_name = imap_mime_header_decode($attach_array[$i]->file_name);
					echo '<td><table border="0" cellpadding="0" cellspacing="0"><tr><td><input type="checkbox" name="file'.$i.'" /></td><td><img border="0" width="16" height="16" src="'.$GO_CONFIG->host.'/filetypes/icon.php?extension='.get_file_extension($attach_array[$i]->file_name).'&mime='.urlencode($attach_array[$i]->file_mime).'" /></td><td nowrap>&nbsp;'.htmlentities($att_name[0]->text).' ('.format_size($attach_array[$i]->file_size).'); </td></tr></table></td>';
					if ($splitter=="2")
					{
						echo "</tr><tr>\n";
						$splitter=0;
					}

				}
				echo '<tr><td>';
				$button = new button($cmdDelete, 'javascript:delete_attach()');
				echo '</td><td>'.$ml_total_size.' : '.format_size($totalsize).'</td></tr></table>';
			}
			echo '</td></tr>';
			?>
			<tr>
				<td colspan="3" width="100%" height="100%"><?php
				if ($content_type=='text/HTML')
				{
					$editor_width='100%';
					$editor_height='100%';
					$editor_html=$mail_body;

					require($GO_CONFIG->control_path.'wysiwyg.inc');
					echo '<input type="hidden" name="mail_body" />';
				}else
				{
					echo '<textarea class="textbox" name="mail_body" cols="110" rows="12">'.htmlspecialchars($mail_body).'</textarea>';
				}
				?>
				</td>
			</tr>
			<tr>
				<td align="center" colspan="3">
					<?php
					$button = new button($ml_send,"javascript:send()");
					echo '&nbsp;&nbsp;';
					$button = new button($cmdCancel,"javascript:window.close()");
					?>
				</td>
			</tr>
		</table>
		</form>
	</td>
</tr>
</table>

<script type="text/javascript">
<!--

function open_addressbook(field, address_string)
{
        popup('<?php echo $GO_CONFIG->host."contacts/select.php?SET_HANDLER=".$GO_CONFIG->host.$GO_MODULES->path."add_contacts.php&SET_FIELD="; ?>'+field+'&address_string='+escape(address_string),'550','400');
}

function validate()
{
	if (window.RegExp)
	{
		var reg = new RegExp("[0-9A-Za-z]+","g");
		if (!reg.test(document.sendform.mail_to.value) && !reg.test(document.sendform.mail_cc.value) && !reg.test(document.sendform.mail_bcc.value))
		{
			alert("<?php echo $ml_to_empty ?>");
			document.sendform.mail_to.focus();
                        return (false);
		}
	}
	if (document.sendform.mail_att.value != "")
	{
		alert("<?php echo $ml_attach_forget ?>")
                return (false);
	}
        return (true);
}

function send()
{
        if (validate())
        {
        	<?php if($content_type=='text/HTML') echo "document.forms[0].mail_body.value = iView.document.getElementsByTagName('html')[0].outerHTML;\n"; ?>
                document.sendform.sendaction.value = "send";
	        document.sendform.submit();
        }
}

function attach()
{
	if (document.sendform.mail_att.value != "")
	{
		<?php if($content_type=='text/HTML') echo "document.forms[0].mail_body.value = iView.document.getElementsByTagName('html')[0].outerHTML;\n"; ?>
		document.sendform.sendaction.value = "add";
		document.sendform.submit();
	}
}

function change_format()
{
	if (confirm('<?php echo $ml_loose_changes; ?>'))
	{
		<?php
		if (isset($uid) && $uid > 0)
		{
			echo 'document.forms[0].uid.value="'.$uid.'";';
		}
		?>
		document.forms[0].mail_body.value='';
		document.forms[0].sendaction.value = 'change_format';
		document.forms[0].submit();
	}
}

function delete_attach()
{
	<?php if($content_type=='text/HTML') echo "document.forms[0].mail_body.value = iView.document.getElementsByTagName('html')[0].outerHTML;\n"; ?>
	document.sendform.sendaction.value = "delete";
	document.sendform.submit();
}
//-->
</script>
