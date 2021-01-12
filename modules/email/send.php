<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

require("../../Group-Office.php");
$GO_SECURITY->authenticate();
$GO_MODULES->authenticate('email');
require($GO_LANGUAGE->get_language_file('email'));

require($GO_CONFIG->class_path."/sendmail.class.inc");
require($GO_CONFIG->class_path."./smtp.class.inc");
require($GO_CONFIG->class_path."email.class.inc");
$email = new email;

$browser = detect_browser();
if ($browser['name'] == 'MSIE' && $browser['version'] > 5.0)
{

	$wysiwyg = true;
	$GO_MAIL_FORMAT = isset($GO_MAIL_FORMAT) ? $GO_MAIL_FORMAT : 'text/PLAIN';
	$content_type = isset($content_type) ? $content_type : $GO_MAIL_FORMAT;
}else
{
	$content_type = 'text/PLAIN';
	$wysiwyg = false;
}

if ($content_type == 'text/HTML')
{
	$header_body_args = 'onLoad="Init()"';
}

$page_title = $ml_compose;
$sendaction = isset($sendaction) ? $sendaction : '';
$attachments_size = 0;
switch ($sendaction)
{
	case 'add':
                // Adding the new file to the array
		if (is_uploaded_file($mail_att))
		{
			// Counting the attachments number in the array
			if (isset($attach_array))
			{
				for($i=1;$i<=count($attach_array);$i++)
				{
					$attachments_size += $attach_array[$i]->file_size;
				}
			}
			$attachments_size += $mail_att_size;
			if ($attachments_size < $GO_CONFIG->max_attachment_size)
			{
				$tmp_file = $GO_CONFIG->tmpdir.basename($mail_att_name).'att';
				copy($mail_att, $tmp_file);
				$email->register_attachment($tmp_file, basename($mail_att_name), $mail_att_size, $mail_att_type);

				require($GO_THEME->theme_path."simple_header.inc");
				require ("compose.inc");
			}else
			{
				require($GO_THEME->theme_path."simple_header.inc");
				require ("compose.inc");
				?>
				<script type="text/javascript">
					alert("<?php echo $ml_file_too_big.format_size($GO_CONFIG->max_attachment_size)." (".number_format($GO_CONFIG->max_attachment_size, 0, $ses_decimal_seperator, $ses_thousands_seperator)." bytes)."; ?>");
				</script>
				<?php
			}
			}else
			{
					require($GO_THEME->theme_path."simple_header.inc");
					require ("compose.inc");
					?>
					<script type="text/javascript">
							alert("<?php echo $ml_file_too_big.format_size($GO_CONFIG->max_attachment_size)." (".number_format($GO_CONFIG->max_attachment_size, 0, $ses_decimal_seperator, $ses_thousands_seperator)." bytes)."; ?>");
					</script>
					<?php

			}

		break;
	case 'send':
		if (!isset($mail_from))
		{
			require($GO_CONFIG->class_path."users.class.inc");
			$users = new users;
			$profile = $users->get_user($GO_SECURITY->user_id);
		}else
		{
			$profile = $email->get_account($mail_from);
		}

		$ip = (getenv('HTTP_X_FORWARDED_FOR') ? getenv('HTTP_X_FORWARDED_FOR') : getenv('REMOTE_ADDR'));
		$mail = new mime_mail();
		$mail->body_ctype = $content_type;
		$mail->crlf = get_crlf($GO_CONFIG->smtp_server);
		$mail->smtp_server = $GO_CONFIG->smtp_server;
		$mail->smtp_port = $GO_CONFIG->smtp_port;
		$mail->charset = $charset;
		$mail->from = $profile["email"];
		$mail->sender_name = $profile["name"];
		$mail->priority = $priority;
		$mail->headers = 'X-Originating-Ip: [' . $ip . ']' . $mail->crlf . 'X-Mailer: Group-Office '.$GO_CONFIG->version;
		if (isset($notification))
			$mail->headers .= $mail->crlf.'Disposition-Notification-To: "'.$profile["name"].'" <'.$profile["email"].'>';

		$mail_to_array = cut_address(trim($mail_to), $charset);
		$mail_cc_array = cut_address(trim($mail_cc), $charset);
		$mail_bcc_array = cut_address(trim($mail_bcc), $charset);;
		$mail->to = $mail_to_array;
		$mail->cc = $mail_cc_array;
		$mail->bcc = $mail_bcc_array;

		if ($mail_subject != '')
			$mail->subject = smartstrip(trim($mail_subject));

		if (isset($replace_url) && isset($replace_id))
		{
			for ($i=0;$i<count($replace_url);$i++)
			{
				$mail_body=str_replace($replace_url[$i], "cid:".substr($replace_id[$i], 1,strlen($replace_id[$i])-2), $mail_body);
			}
			session_unregister('replace_url');
			session_unregister('replace_id');
		}
		if ($profile["signature"] != '')
		{
			if ($content_type == 'text/HTML')
			{
				$signature=text_to_html($mail->crlf.$mail->crlf.$profile["signature"]);
			}else
			{
				$signature=$mail->crlf.$mail->crlf.$profile["signature"];
			}
			$mail->body = smartstrip($mail_body).$signature;
		}else
		{
			$mail->body = smartstrip($mail_body);
		}

		// Getting the attachments
		if (isset($attach_array))
		{
			for ($i = 1; $i <= $num_attach; $i++)
			{
				// If the temporary file exists, attach it
				if (file_exists($attach_array[$i]->tmp_file))
				{
					$fp = fopen($attach_array[$i]->tmp_file, "rb");
					$file = fread($fp, $attach_array[$i]->file_size);
					fclose($fp);
					// add it to the message, by default it is encoded in base64
					$mail->add_attachment($file, imap_qprint($attach_array[$i]->file_name), $attach_array[$i]->file_mime, 'base64', '', $attach_array[$i]->content_id);
				}
			}
		}
		// We need to unregister the attachments array and num_attach
		session_unregister('num_attach');
		session_unregister('attach_array');

		if ($mail->send())
		{
			if ($add_recievers == 'true')
			{
				require($GO_CONFIG->class_path.'contacts.class.inc');
				$contacts = new contacts;

				for ($i=0;$i<count($mail_to_array);$i++)
				{
					if (!$contacts->get_contact_id_by_email($mail_to_array[$i], $GO_SECURITY->user_id))
					{
						$contacts->add_contact('', $GO_SECURITY->user_id, $mail_to_array[$i], $mail_to_array[$i]);
					}
				}
				for ($i=0;$i<count($mail_cc_array);$i++)
				{
					if (!$contacts->get_contact_id_by_email($mail_cc_array[$i], $GO_SECURITY->user_id))
					{
						$contacts->add_contact('', $GO_SECURITY->user_id, $mail_cc_array[$i], $mail_cc_array[$i]);
					}
				}
				for ($i=0;$i<count($mail_bcc_array);$i++)
				{
					if (!$contacts->get_contact_id_by_email($mail_bcc_array[$i], $GO_SECURITY->user_id))
					{
						$contacts->add_contact('', $GO_SECURITY->user_id, $mail_bcc_array[$i], $mail_bcc_array[$i]);
					}
				}

			}
			if ($profile["type"] == "imap")
			{
				$sent_folder = $email->get_sent_folder($mail_from);
				if ($sent_folder != '')
				{
						require($GO_CONFIG->class_path."imap.class.inc");
						$mailbox = new imap;
						if ($mailbox->open($profile["host"], "imap", $profile["port"], $profile["username"], $profile["password"], $sent_folder))
						{
								if ($mailbox->append_message($sent_folder, $mail->mime,"\\Seen"))
								{
										$mailbox->close();
										echo "<script type=\"text/javascript\">\r\nwindow.close();\r\n</script>\r\n";
										exit;
								}
						}
				}
				require($GO_THEME->theme_path."simple_header.inc");
				echo "<script type=\"text/javascript\">\r\nalert('".$ml_sent_items_fail."');\r\nwindow.close();\r\n</script>\r\n";
				exit;
			}else
			{
				echo "<script type=\"text/javascript\">\r\nwindow.close();\r\n</script>\r\n";
				exit;
			}
		}else
		{
			require($GO_THEME->theme_path."simple_header.inc");
			echo '<p class="Error">'.$ml_send_error.'</p>';
			require("compose.inc");
		}

		break;
	case 'delete':
		// Rebuilding the attachments array with only the files the user wants to keep
		$tmp_array = array();
		for ($i = $j = 1; $i <= $num_attach; $i++)
		{
			$thefile = 'file'.$i;
			if (empty($$thefile))
			{
				$tmp_array[$j]->file_name = $attach_array[$i]->file_name;
				$tmp_array[$j]->tmp_file = $attach_array[$i]->tmp_file;
				$tmp_array[$j]->file_size = $attach_array[$i]->file_size;
				$tmp_array[$j]->file_mime = $attach_array[$i]->file_mime;
				$j++;
			}
			else
				@unlink($GO_CONFIG->tmpdir.$attach_array[$i]->tmp_file);
		}
		$num_attach = ($j > 1 ? $j - 1 : 0);
		// Removing the attachments array from the current session
		session_unregister('num_attach');
		session_unregister('attach_array');
		$attach_array = $tmp_array;
		// Registering the attachments array into the session
		session_register('num_attach', 'attach_array');
		// Displaying the sending form with the new attachment array
		header("Content-type: text/html; Charset=$charset");

		require($GO_THEME->theme_path."simple_header.inc");
		require("compose.inc");
		break;

	case 'change_format':
		SetCookie("GO_MAIL_FORMAT",$content_type,time()+3600*24*30,"/",'',0);
		require($GO_THEME->theme_path."simple_header.inc");
		require("compose.inc");
	break;

	default:
		require($GO_THEME->theme_path."simple_header.inc");
		require("compose.inc");
		break;
}
require($GO_THEME->theme_path."simple_footer.inc");
?>
