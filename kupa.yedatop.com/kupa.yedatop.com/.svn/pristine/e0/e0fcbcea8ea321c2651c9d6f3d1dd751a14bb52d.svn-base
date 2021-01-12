<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

require("../Group-Office.php");

$GO_SECURITY->authenticate();
require($GO_LANGUAGE->get_language_file('contacts'));

if (isset($users_id))
{
	$page_title=$user_profile;
	require($GO_CONFIG->class_path."users.class.inc");
	$users = new users; 
	$profile = $users->get_user($users_id);

	$subusers = $users->get_subusers($users_id);
	if ($GO_SECURITY->user_id != $users_id)
	{
		if (!$GO_SECURITY->has_permission($GO_SECURITY->user_id,$profile["acl_id"]))
		{
			Header("Location: ".$GO_CONFIG->host."error_docs/403.php");
			exit;
		}
	}

}else
{
	$page_title=$contact_profile;
	require($GO_CONFIG->class_path."contacts.class.inc");
	$contacts = new contacts;
	$profile = $contacts->get_contact($contact_id);

	//don't allow users that try to see other persons contacts to pass through

	if ($profile["user_id"] != $GO_SECURITY->user_id || $GO_MODULES->has_read_permission($GO_SECURITY->user_id, "stock_admin"))
	{
		Header("Location: ".$GO_CONFIG->host."error_docs/403.php");
		exit;
	}
}


require($GO_THEME->theme_path."simple_header.inc");
if (!$profile)
{
        echo "<p class=\"Error\">".$strDataError."</p>";
	exit;
}
?>
<table border="0" cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td>
	<table border="0" cellpadding="" cellspacing="3" width="100%">
	<tr>
		<td height="40" colspan="2"><h1><?php echo $page_title; ?></h1></td>
	</tr>
	<tr>
		<td valign="top" width="50%">
		<table border="0" class="normal" cellpadding="0" cellspacing="3" width="100%">

		<tr>
			<td align="right" nowrap><i><?php echo $strName; ?>:</i>&nbsp;</td>
			<td width="100%"><?php echo empty_to_stripe($profile["name"]); ?></td>
		</tr>

        <tr>
			<td align="right" nowrap><i><?php echo $strBusinessNum; ?>:</i>&nbsp;</td>
			<td width="100%"><?php echo empty_to_stripe($profile["businessnum"]); ?></td>
		</tr> 

        <tr>
			<td align="right" nowrap><i><?php echo $strZehut; ?>:</i>&nbsp;</td>
			<td width="100%"><?php echo empty_to_stripe($profile["zehut"]); ?></td>
		</tr> 

		<tr>
			<td align="right" nowrap><i><?php echo $strZip; ?>:</i>&nbsp;</td>
			<td width="100%"><?php echo empty_to_stripe($profile["zip"]); ?></td>
		</tr>

		<tr>
			<td align="right" nowrap><i><?php echo $strCity; ?>:</i>&nbsp;</td>
			<td width="100%"><?php echo empty_to_stripe($profile["city"]); ?></td>
		</tr>

		<tr>
			<td align="right" nowrap><i><?php echo $strState; ?>:</i>&nbsp;</td>
			<td width="100%"><?php echo empty_to_stripe($profile["state"]); ?></td>
		</tr>

		<tr>
			<td align="right" nowrap><i><?php echo $strCountry; ?>:</i>&nbsp;</td>
			<td width="100%"><?php echo empty_to_stripe($profile["country"]); ?></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>

		<tr>
			<td align="right" nowrap><i><?php echo $strEmail; ?>:</i>&nbsp;</td>
			<td><?php echo mail_to(empty_to_stripe($profile["email"])); ?></td>
		</tr>

		<tr>
			<td align="right" nowrap><i><?php echo $strPhone; ?>:</i>&nbsp;</td>
			<td><?php echo empty_to_stripe($profile["home_phone"]); ?></td>
		</tr>

		<tr>
			<td align="right" nowrap><i><?php echo $strFax; ?>:</i>&nbsp;</td>
			<td><?php echo empty_to_stripe($profile["fax"]); ?></td>
		</tr>


		<tr>
			<td align="right" nowrap><i><?php echo $strCellular; ?>:</i>&nbsp;</td>
			<td><?php echo empty_to_stripe($profile["cellular"]); ?></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<?php
		if (isset($contact_id))
		{
			echo '<tr><td valign="top"><i>'.$strComments.':</i></td><td>'.text_to_html($profile["comments"]).'</td></tr>';
		}
		?>
		</table>
		</td>
		<td valign="top" width="50%">
		<table border="0" class="normal" cellpadding="0" cellspacing="3" width="100%">
		<tr>
			<td align="right" nowrap><i><?php echo $strCompany; ?>:</i>&nbsp;</td>
			<td><?php echo empty_to_stripe($profile["company"]); ?></td>
		</tr>
		<tr>
			<td align="right" nowrap><i><?php echo $strAddress; ?>:</i>&nbsp;</td>
			<td width="100%"><?php echo empty_to_stripe($profile["work_address"]); ?></td>
		</tr>

		<tr>
			<td align="right" nowrap><i><?php echo $strZip; ?>:</i>&nbsp;</td>
			<td width="100%"><?php echo empty_to_stripe($profile["work_zip"]); ?></td>
		</tr>

		<tr>
			<td align="right" nowrap><i><?php echo $strCity; ?>:</i>&nbsp;</td>
			<td width="100%"><?php echo empty_to_stripe($profile["work_city"]); ?></td>
		</tr>

		<tr>
			<td align="right" nowrap><i><?php echo $strState; ?>:</i>&nbsp;</td>
			<td width="100%"><?php echo empty_to_stripe($profile["work_state"]); ?></td>
		</tr>

		<tr>
			<td align="right" nowrap><i><?php echo $strCountry; ?>:</i>&nbsp;</td>
			<td width="100%"><?php echo empty_to_stripe($profile["work_country"]); ?></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td align="right" nowrap><i><?php echo $strPhone; ?>:</i>&nbsp;</td>
			<td><?php echo empty_to_stripe($profile["work_phone"]); ?></td>
		</tr>
		<tr>
			<td align="right" nowrap><i><?php echo $strFax; ?>:</i>&nbsp;</td>
			<td><?php echo empty_to_stripe($profile["work_fax"]); ?></td>
		</tr>
		<tr>
			<td align="right" nowrap><i><?php echo $strHomepage; ?>:</i>&nbsp;</td>
			<td><?php echo empty_to_stripe(text_to_html($profile["homepage"])); ?></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td align="right" nowrap><i><?php echo $strDepartment; ?>:</i>&nbsp;</td>
			<td><?php echo empty_to_stripe($profile["department"]); ?></td>
		</tr>

		<tr>
			<td align="right" nowrap><i><?php echo $strFunction; ?>:</i>&nbsp;</td>
			<td><?php echo empty_to_stripe($profile["function"]); ?></td>
		</tr>
		</table>
		</td>
	</tr>
	<?if (count($subusers)){?>
	<tr>
		<td colspan=2>
		<br>

		<b>Sub-Users</b>
		<table width=80% border=1>
		<?foreach($subusers as $subuser){?>
		<tr>
			<td>
				<a target="_blank" href="/users/edit_user.php?id=<?=$subuser["id"]?>"><u><?=$subuser["username"]?></u></a>
			</TD>
		</TR>
		<?}?>
		</table>
		</td>
	</tr>
	<?}?>
	<tr>
		<td colspan="2">
		<br />
		<?php
		if (!$nobuttons){
			if (isset($users_id))
			{
				$button = new button($contacts_add_user, "document.location='".$GO_CONFIG->host."contacts/add_contact.php?id=".$users_id."';");
				echo '&nbsp;&nbsp;';
			}else
			{
				$button = new button($contacts_edit, "document.location='".$GO_CONFIG->host."contacts/edit_contact.php?id=".$contact_id."';");
				echo '&nbsp;&nbsp;';
			}
			$button = new button($cmdClose, 'javascript:window.close();');
		}
		?>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
<?php
require($GO_THEME->theme_path."simple_footer.inc");
?>
