<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

//delete accounts if requested
if(isset($delete_account))
{
	if (!$email->delete_account($GO_SECURITY->user_id, $delete_account))
	{
		echo $strDeleteError;
	}else
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
}

if ($REQUEST_METHOD == "POST" && isset($account))
{
	$email->set_as_default($account, $GO_SECURITY->user_id);
}
?>
<input type="hidden" name="task" value="accounts" />
<table border="0" cellpadding="10" cellspacing="0">
<tr>
	<td>
	<table border="0" cellpadding="4" cellspacing="0" width="100%">
	<?php
	$count = $email->get_accounts($GO_SECURITY->user_id);

	if ($count > 0)
	{
		echo '<tr><td align="right"><h3>'.$strDefault.'</h3></td>';
		echo '<td><h3>'.$strHost.'</h3></td>';
		echo '<td><h3>'.$strEmail.'</h3></td>';
		echo '<td>&nbsp;</td></tr>';

		while ($email->next_record())
		{
			if ($email->f("standard") == "1")
			{
				$checked = "checked";
			}else
			{
				$checked = "";
			}

			echo '<tr>';
			echo '<td align="right"><input type="radio" onclick="javascript:document.forms[0].submit()" name="account" value="'.$email->f("id").'" '.$checked.' /></td>';
			echo '<td>'.$email->f("host").'</a></td>';
			echo '<td>'.$email->f('email').'</td>';
			echo '<td><a href="'.$PHP_SELF.'?task=accounts&table_tabindex=2&account_id='.$email->f('id').'" title="'.$strEdit.' '.$email->f('host').'"><img src="'.$GO_THEME->image_url.'buttons/edit.gif" border="0" /></a></td>';
			echo "<td><a href='javascript:confirm_action(\"".$PHP_SELF."?task=accounts&delete_account=".$email->f("id")."\",\"".rawurlencode($strDeletePrefix."'".$email->f("host")."'".$strDeleteSuffix)."\")' title=\"".$strDeleteItem." '".$email->f("host")."'\"><img src=\"".$GO_THEME->image_url."buttons/delete.gif\" border=\"0\"></a></td>\n";
			echo '</tr>';
		}
	}else
	{
		echo "<tr><td>".$ml_no_accounts."</td></tr>";
	}
	?>
	</table>
	</td>
</tr>
</table>