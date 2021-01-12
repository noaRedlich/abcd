<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

$count = $email->get_accounts($GO_SECURITY->user_id);
if ($count > 0)
{
	echo '<table border="0" cellpadding="0" cellspacing="0">';
	echo '<tr><td nowrap><img src="'.$GO_THEME->image_url.'newmail.gif" border="0" height="16" width="16" /></td>';
	echo '</table>';

    $i=0;
	while($email->next_record())
	{
		$i++;

		if ($email->f("id") == $email_id)
		{
			if ($i == $count)
			{
					$node = $GO_THEME->image_url."treeview/mlastnode.gif";
					$blank = $GO_THEME->image_url."treeview/blank.gif";
			}else
			{
					$node = $GO_THEME->image_url."treeview/mnode.gif";
					$blank = $GO_THEME->image_url."treeview/vertline.gif";
			}
			echo '<table border="0" cellpadding="0" cellspacing="0">';
			echo '<tr><td nowrap><a href="'.$PHP_SELF.'?id='.$email->f("id").'&mailbox=INBOX"><img src="'.$node.'" border="0" height="22" width="16" /></a>';
			echo '<img src="'.$GO_THEME->image_url.'buttons/mail_account.gif" border="0" height="16" width="16" /></td>';
			echo '<td nowrap>&nbsp;<a href="'.$PHP_SELF.'?id='.$email->f("id").'&mailbox=INBOX" title="'.$email->f("email").'">'.cut_string($email->f("email"),30).'</a></td></tr>';
			echo '</table>';

			$image = $GO_THEME->image_url."buttons/inbox.gif";

			if ($mail->is_imap())
			{
				$status = $mail->status("INBOX");
				if ($status->unseen > 0)
					$status = '('.$status->unseen.')';
				else
					$status = '';
				echo '<table border="0" cellpadding="0" cellspacing="0">';
				echo '<tr><td nowrap><img src="'.$blank.'" border="0" height="22" width="16" /><img src="'.$GO_THEME->image_url.'treeview/emptynode.gif" border="0" height="22" width="16" />';
				echo '<img src="'.$GO_THEME->image_url.'buttons/inbox.gif" border="0" /></td>';
				echo '<td nowrap class="count">&nbsp;<a class="Table1" href="'.$PHP_SELF.'?id='.$email->f("id").'&mailbox=INBOX">Inbox</a> '.$status.'</td></tr>';
				echo '</table>';

				$email2 = new email;
				$count_folders = $email2->get_folders($email->f("id"));
				if ($count_folders > 0)
				{
					$x=0;
					while ($email2->next_record())
					{
						if ($email2->f("type") == "sent")
						{
							$status = '';
						}else
						{
							$status = $mail->status($email2->f("name"));
							if ($status->unseen > 0)
								$status = '('.$status->unseen.')';
							else
								$status = '';
						}

						$x++;

						if ($x == $count_folders)
								$node = $GO_THEME->image_url."treeview/emptylastnode.gif";
						else
								$node = $GO_THEME->image_url."treeview/emptynode.gif";

						$folder_name = $email2->f("name");

						switch ($email2->f("type"))
						{
							case "sent":
									$image = $GO_THEME->image_url."buttons/sent.gif";
							break;

							case "spam":
									$image = $GO_THEME->image_url."buttons/spam.gif";
							break;

							default:
									$image = $GO_THEME->image_url."buttons/inbox.gif";
							break;

						}

						$subfolder = !strrchr($folder_name,".") ? $folder_name : substr(strrchr($folder_name, "."), 1);

						echo '<table border="0" cellpadding="0" cellspacing="0">';
						echo '<tr><td nowrap><img src="'.$blank.'" border="0" height="22" width="16" /><img src="'.$node.'" border="0" height="22" width="16" />';
						echo '<img src="'.$image.'" border="0" /></td>';
						echo '<td nowrap class="count">&nbsp;<a class="Table1" href="'.$PHP_SELF.'?id='.$email->f("id").'&mailbox='.urlencode($folder_name).'">'.$subfolder.'</a> '.$status.'</td></tr>';
						echo '</table>';
					}
				}
			}else
			{
				echo '<table border="0" cellpadding="0" cellspacing="0">';
				echo '<tr><td nowrap><img src="'.$blank.'" border="0" height="22" width="16" /><img src="'.$GO_THEME->image_url.'treeview/emptylastnode.gif" border="0" height="22" width="16" />';
				echo '<img src="'.$GO_THEME->image_url.'buttons/inbox.gif" border="0" /></td>';
				echo '<td nowrap class="count">&nbsp;<a class="Table1" href="'.$PHP_SELF.'?id='.$email->f("id").'&mailbox=INBOX">Inbox</a></td></tr>';
				echo '</table>';
			}
		}else
		{
			if ($i == $count)
			{
				$node = $GO_THEME->image_url."treeview/plastnode.gif";

			}else
			{
				$node = $GO_THEME->image_url."treeview/pnode.gif";

			}
			echo '<table border="0" cellpadding="0" cellspacing="0">';
			echo '<tr><td nowrap><a href="'.$PHP_SELF.'?id='.$email->f("id").'&mailbox=INBOX"><img src="'.$node.'" border="0" height="22" width="16" /></a>';
			echo '<img src="'.$GO_THEME->image_url.'buttons/mail_account.gif" border="0" height="16" width="16" /></td>';
			echo '<td nowrap>&nbsp;<a href="'.$PHP_SELF.'?id='.$email->f("id").'&mailbox=INBOX" title="'.$email->f("email").'">'.cut_string($email->f("email"),30).'</a></td></tr>';
			echo '</table>';
		}
	}

}
?>