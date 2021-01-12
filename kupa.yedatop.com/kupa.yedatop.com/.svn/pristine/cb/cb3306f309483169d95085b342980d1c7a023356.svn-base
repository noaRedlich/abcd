<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

require($GO_CONFIG->class_path."filetypes.class.inc");
require($GO_CONFIG->class_path."users.class.inc");
require($GO_CONFIG->class_path."contacts.class.inc");
$users = new users;
$contacts = new contacts;
$filetypes = new filetypes;

$to = '';
$texts = '';
$images = '';
?>

<script type="text/javascript">
<!--

function confirm_delete()
{
	if (confirm("<?php echo $ml_delete_message; ?>"))
	{
		document.location = "<?php echo $PHP_SELF."?task=message&mailbox=".urlencode($mailbox)."&delete_message=".$uid; ?>";
	}
}

//-->
</script>
<table border="0" width="600">
<tr>
	<td>
	<table border="0" cellpadding="1" cellspacing="0" class="TableBorder" width="100%">
	<tr>
		<td>
		<table border="0" cellpadding="1" cellspacing="0" class="TableInside" width="100%">
		<?php
		switch ($content["priority"])
		{
			case "4":
				echo '<tr><td class="Table2"><table border="0" cellpadding="1" cellspacing="1"><tr><td><img src="'.$GO_THEME->image_url.'info.gif" border="0" width="16" height="16" /></td><td class="Success">'.$ml_low_priority.'</td></tr></table></td></tr>';
			break;

			case "2":
				echo '<tr><td class="Table2"><table border="0" cellpadding="1" cellspacing="1"><tr><td><img src="'.$GO_THEME->image_url.'info.gif" border="0" width="16" height="16" /></td><td class="Error">'.$ml_high_priority.'</td></tr></table></td></tr>';
			break;
		}
		?>
		<tr>
			<td>
			<table border="0" cellpadding="1" cellspacing="0">


			<tr>
				<td><b><?php echo $ml_subject; ?>:&nbsp;</b></td>
				<td><?php echo $subject; ?></td>
			</tr>

			<tr>
				<td><b><?php echo $ml_from; ?>:&nbsp;</b></td>
				<td>
				<?php
				if ($contact_id = $contacts->get_contact_id_by_email($content["sender"], $GO_SECURITY->user_id))
				{
					echo "<a href='javascript:popup(\"".$GO_CONFIG->host."contacts/show_profile.php?contact_id=".$contact_id."\",\"750\",\"500\")' class=\"normal\" title=\"".$strShowProfile."\">";
					echo $content["from"]."</a>\n";
				}else
				{
					$users_id = $users->get_user_id_by_email($content["sender"]);
					if ($users_id && $GO_SECURITY->user_is_visible($users_id))
					{
						echo "<a href='javascript:popup(\"".$GO_CONFIG->host."contacts/show_profile.php?users_id=".$users_id."\",\"750\",\"500\")' class=\"normal\" title=\"".$strShowProfile."\">";
						echo $content["from"]."</a>\n";
					}else
					{
						echo "<a href=\"javascript:popup('".$GO_CONFIG->host."contacts/add_contact.php?email=".$content["sender"]."&name=".urlencode($content["from"])."','750','500')\" class=\"normal\" title=\"".$ml_add_contact."\">".$content["from"]."</a>";
					}
				}
				?>
				</td>
			</tr>
			<tr>
				<td valign="top"><b><?php echo $ml_to; ?>:&nbsp;</b></td>
				<td>
				<?php
				$to == "";
				if (isset($content["to"]))
				{
					for ($i=0;$i<sizeof($content["to"]);$i++)
					{
						if ($i != 0)
						{
							$to .=", ";
						}
						$to .= $content["to"][$i];
					}
				}
				if ($to == "")
				{
					$to = $ml_no_reciepent;
				}
				echo $to;
				?>
				</td>
			</tr>
			<?php
			if (isset($content["cc"]))
			{
				$cc = '';
				for ($i=0;$i<sizeof($content["cc"]);$i++)
				{
					if ($i != 0)
					{
						$cc .=", ";
					}
					$cc .= $content["cc"][$i];
				}
				if ($cc != '')
				{
					echo '<tr><td valign="top"><b>Cc:</b>&nbsp;</td><td>';
					echo $cc;
					echo '</td></tr>';
				}
			}
			if (isset($content["bcc"]))
			{
				$bcc = '';
				for ($i=0;$i<sizeof($content["bcc"]);$i++)
				{
					if ($i != 0)
					{
						$bcc .=", ";
					}
					$bcc .= $content["bcc"][$i];
				}
				if ($bcc != '')
				{
					echo '<tr><td valign="top"><b>Bcc:</b>&nbsp;</td><td>';
					echo $bcc;
					echo '</td></tr>';
				}
			}
			?>
			<tr>
				<td><b><?php echo $strDate; ?>:&nbsp;</b></td>
				<td><?php echo date($ses_date_format,$content['udate']); ?></td>
			</tr>
			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td>
	<?php
	$count = 0;
	$splitter = 0;
	$parts = array_reverse($mail->f("parts"));

	$attachments = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr>";

	for ($i=0;$i<count($parts);$i++)
	{
		if (((eregi("ATTACHMENT", $parts[$i]["disposition"]) || eregi("INLINE", $parts[$i]["disposition"])) && $parts[$i]["name"] != '') || eregi("message/rfc822", $parts[$i]["mime"]))
		{

			if ($parts[$i]["name"] == "")
			{
				$parts[$i]["name"] = $parts[$i]["mime"];
				$pos = strrpos($parts[$i]["name"] ,'/');
				if ($pos)
				{
					$parts[$i]["name"] = substr($parts[$i]["name"],$pos+1,strlen($parts[$i]["name"]));
				}

				if ($extension = $filetypes->get_mime_extension($parts[$i]["mime"]))
				{
					$parts[$i]["name"] .='.'.$extension;
				}
			}

			$target = '_blank';
			$link = "attachment.php?mailbox=".urlencode($mailbox)."&uid=".$uid."&part=".$parts[$i]["number"]."&transfer=".$parts[$i]["transfer"]."&mime=".$parts[$i]["mime"]."&filename=".urlencode($parts[$i]["name"]);

			$splitter++;
			$count++;

			$attachments .= '<td><img border="0" width="16" height="16" src="'.$GO_CONFIG->host.'filetypes/icon.php?extension='.get_file_extension($parts[$i]["name"]).'&mime='.urlencode($parts[$i]["mime"]).'" /></td>';
			$attachments .= '<td valign="center" nowrap>&nbsp;<a href="'.$link.'" target="'.$target.'" title="'.$parts[$i]["name"].'">'.cut_string($parts[$i]["name"],50).'</a> ('.format_size($parts[$i]["size"]).')</td>';
			$attachments .= "<td>&nbsp;<a title=\"".$ml_save_attachment."\" href=\"javascript:popup('save_attachment.php?mailbox=".urlencode($mailbox)."&uid=".$uid."&part=".$parts[$i]["number"]."&transfer=".$parts[$i]["transfer"]."&mime=".$parts[$i]["mime"]."&filename=".urlencode(addslashes($parts[$i]["name"]))."','600','400')\"><img src=\"".$GO_THEME->image_url."buttons/save.gif\" border=\"0\" /></a>;&nbsp;</td>\n";
			if ($splitter == 3)
			{
				$splitter = 0;
				$attachments .= "</tr><tr>";
			}
		}
	}

	$attachments .= "</tr></table>";

	if ($count>0)
	{
		echo '<br /><table border="0" cellpadding="1" cellspacing="0" class="TableBorder" width="100%"><tr><td><table border="0" cellpadding="1" cellspacing="0" class="TableInside" width="100%"><tr><td valign="top">';
		echo '<table border="0" cellpadding="0" cellspacing="0"><tr><td valign="top"><b>'.$ml_attachments.':</b>&nbsp;&nbsp;</td><td>'.$attachments.'</td></tr></table>';
		echo '</td></tr></table></td></tr></table>';
	}
	?>
	</td>
</tr>
<tr>
	<td>
	<br />
	<?php
	//get all text and html content
	for ($i=0;$i<sizeof($parts);$i++)
	{
		$mime = strtolower($parts[$i]["mime"]);

		if (($mime == "text/html") || ($mime == "text/plain"))
		{
			$part = $mail->view_part($uid, $parts[$i]["number"], $parts[$i]["transfer"]);

			switch($mime)
			{
				case 'text/plain':
					$part = text_to_html($part);
				break;

				case 'text/html':
					$part = convert_html($part);
				break;
			}

			if ($parts[$i]["name"] != '')
			{
				$texts .= "<p class=\"normal\" align=\"center\">--- ".$parts[$i]["name"]." ---</p>";
			}else
			{
				$texts .= '<br /><br /><br />';
			}

			$texts .= $part;
		}
	}

	//Content-ID's that need to be replaced with urls when message needs to be reproduced
	$replace_url = array();
	$replace_id = array();
	//preview all images

	for ($i=0;$i<sizeof($parts);$i++)
	{
		if (eregi("image",$parts[$i]["mime"]))
		{
			//when an image has an id it belongs somewhere in the text we gathered above so replace the
			//source id with the correct link to display the image.
			if ($parts[$i]["id"] != '')
			{
				$id = "cid:".substr($parts[$i]["id"], 1,strlen($parts[$i]["id"])-2);
				$url = "open_attachment.php?mailbox=".$mailbox."&amp;uid=".$uid."&amp;part=".$parts[$i]["number"]."&amp;transfer=".$parts[$i]["transfer"]."&amp;mime=".$parts[$i]["mime"]."&amp;filename=".urlencode($parts[$i]["name"]);
				$texts = str_replace($id, $url, $texts);
				$replace_url[] = $url;
				$replace_id[] = $parts[$i]["id"];
			}else
			{
				$images .= "<br /><p class=\"normal\" align=\"center\">--- ".$parts[$i]["name"]." ---</p><div align=\"center\"><img src=\"open_attachment.php?mailbox=".$mailbox."&uid=".$uid."&part=".$parts[$i]["number"]."&transfer=".$parts[$i]["transfer"]."&mime=".$parts[$i]["mime"]."&filename=".urlencode($parts[$i]["name"])."\" border=\"0\" /></div>";
			}
		}
	}
	session_register("replace_url", "replace_id");

	echo $texts.$images;
	?>
	</td>
</tr>
</table>

<?php
if ($content["notification"] != '' && $content["new"] == 1)
{
	echo "<script type=\"text/javascript\">\npopup('"."notification.php?notification=".urlencode($content["notification"])."&date=".urlencode(date($ses_date_format,$content['udate']))."&subject=".urlencode($subject)."&to=".urlencode($to)."','500','150');\n</script>\n";
}
?>
