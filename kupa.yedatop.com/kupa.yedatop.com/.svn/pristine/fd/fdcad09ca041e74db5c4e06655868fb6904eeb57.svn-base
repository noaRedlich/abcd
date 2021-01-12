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
//load contact management class
require($GO_CONFIG->class_path."contacts.class.inc");
$contacts = new contacts;

require($GO_CONFIG->class_path."/validate.class.inc");
$val = new validate;

$action = isset($action) ? $action : '';

if ($REQUEST_METHOD == "POST" && $action =="add")
{
	$val->error_required = $error_required;
	$val->error_min_length = $error_min_length;
	$val->error_max_length = $error_max_length;
	$val->error_expression = $error_email;
	$val->error_match = $error_match_auth;


	$val->name="name";
	$val->input=$name;
	$val->max_length=50;
	$val->required=true;
	$val->validate_input();


	if ($val->validated == true)
	{
		if ($contacts->update_contact($id, $name, $email, $work_phone, $home_phone, $fax, $cellular, $country, $state, $city, $zip, $address, $company, $work_country, $work_state, $work_city, $work_zip, $work_address, $work_fax, $homepage, $department, $function, $comments, $group_id))
		{
			echo "<script type=\"text/javascript\">\nopener.location=opener.location;window.close();\n</script>\n";
		}else
		{
			$feedback = "<p class=\"Error\">".$strSaveError."</p>";
		}
	}else
	{
		$feedback ="<p class='Error'>".$errors_in_form."</p>";
	}

}
$contact = $contacts->get_contact($id);

//don't allow users that try to see other persons contacts to pass through
if ($contact["user_id"] != $GO_SECURITY->user_id)
{
	Header("Location: ".$GO_CONFIG->host."access_denied.php");
	exit;
}

if ($action == "update")
{
	$comments = $contact['comments'];
	$source_id = $contacts->f("source_id");
	require($GO_CONFIG->class_path."users.class.inc");
	$users = new users;
	$contact = $users->get_user($contacts->f("source_id"));
	$contact["source_id"] = $source_id;
	$contact['comments'] = $comments;
}

if (!isset($contact))
{
	$feedback = "<p class=\"Error\">".$strDataError."</p>";
}


$page_title = $contact_profile;
require($GO_THEME->theme_path."simple_header.inc");
?>
<script type="text/javascript">
function update()
{
        document.add.action.value = "update";
        document.add.submit();
}
</script>
<form name="add" method="post" action="<?php echo $PHP_SELF; ?>?table_tabindex=<?php echo $table_tabindex; ?>">
<input type="hidden" value="<?php echo $id; ?>" name="id" />
<input type="hidden" value="<?php echo $contact["source_id"]; ?>" name="source_id" />
<input type="hidden" name="action" value="add" />
<table border="0" cellpadding="10" cellspacing="0">
<tr>
	<td>
	<table border="0" cellpadding="0" cellspacing="3">
	<tr>
		<td colspan="2"><h1><?php echo $contact_profile; ?></h1>
		<?php if(isset($feedback)) echo $feedback;
		echo $add_personal_text; ?><br /><br />
		</td>
	</tr>
	<tr>
	<td valign="top">
	<table border="0" cellpadding="0" cellspacing="3">
	<?php
	if (isset($val->error["name"]))
	{
	?>
	<tr>
		<td class="Error" colspan="2">
			<?php echo $val->error["name"]; ?>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td align="right" nowrap><?php echo $strName; ?>*:&nbsp;</td>
		<td width="100%"><input type="text" class="textbox"  name="name" size="30" maxlength="50" value="<?php echo $contact["name"]; ?>"></td>
	</tr>

	<tr>
		<td align="right" nowrap><?php echo $strAddress; ?>:&nbsp;</td>
		<td width="100%"><input type="text" class="textbox"  name="address" size="30" maxlength="50" value="<?php echo $contact["address"]; ?>"></td>
	</tr>

	<tr>
		<td align="right" nowrap><?php echo $strZip; ?>:&nbsp;</td>
		<td width="100%">
			<input type="text" class="textbox"  name="zip" size="30" maxlength="20" value="<?php echo $contact["zip"]; ?>">
		</td>
	</tr>
	<tr>
		<td align="right" nowrap><?php echo $strCity; ?>:&nbsp;</td>
		<td width="100%">
			<input type="text" class="textbox"  name="city" size="30" maxlength="50" value="<?php echo $contact["city"]; ?>">
		</td>
	</tr>
	<tr>
		<td align="right" nowrap><?php echo $strState; ?>:&nbsp;</td>
		<td width="100%"><input type="text" class="textbox"  name="state" size="30" maxlength="30" value="<?php echo $contact["state"]; ?>"></td>
	</tr>

	<tr>
		<td align="right" nowrap><?php echo $strCountry; ?>:&nbsp;</td>
		<td width="100%"><input type="text" class="textbox"  name="country" size="30" maxlength="30" value="<?php echo $contact["country"]; ?>"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>

	<?php
	if (isset($val->error["email"]))
	{
	?>
	<tr>
		<td class="Error" colspan="2">
			<?php echo $val->error["email"]; ?>
		</td>
	</tr>
	<?php } ?>

	<tr>
		<td align="right" nowrap><?php echo $strEmail; ?>*:&nbsp;</td>
		<td><input type="text" class="textbox"  name="email" size="30" value="<?php echo $contact["email"]; ?>" maxlength="50"></td>
	</tr>

	<tr>
		<td align="right" nowrap><?php echo $strPhone; ?>:&nbsp;</td>
		<td><input type="text" class="textbox"  name="home_phone" size="30" value="<?php echo $contact["home_phone"]; ?>" maxlength="20"></td>
	</tr>

	<tr>
		<td align="right" nowrap><?php echo $strFax; ?>:&nbsp;</td>
		<td><input type="text" class="textbox"  name="fax" size="30" value="<?php echo $contact["fax"]; ?>" maxlength="20"></td>
	</tr>

	<tr>
		<td align="right" nowrap><?php echo $strCellular; ?>:&nbsp;</td>
		<td><input type="text" class="textbox"  name="cellular" size="30" value="<?php echo $contact["cellular"]; ?>" maxlength="20"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td valign="top"><?php echo $strComments; ?>:</td>
		<td><textarea class="textbox" cols="30" rows="2" name="comments"><?php if(isset($contact["comments"])) echo $contact["comments"]; ?></textarea></td>
	</tr>
	<?php
	if ($contacts->get_groups($GO_SECURITY->user_id) > 0)
	{
		echo '<tr><td align="right" nowrap>'.$contacts_group.':&nbsp;</td><td>';

		$dropbox = new dropbox();
		$dropbox->add_value('0',$contacts_other);
		$dropbox->add_sql_data('contacts','id','name');
		$dropbox->print_dropbox('group_id',$contact["group_id"]);
		echo '</td></tr>';
	}
	?>
	</table>
	</td>
	<td valign="top">
	<table border="0" cellpadding="0" cellspacing="3">

	<tr>
		<td align="right" nowrap><?php echo $strCompany; ?>:&nbsp;</td>
		<td><input type="text" class="textbox"  name="company" size="30" value="<?php echo $contact["company"]; ?>" maxlength="50"></td>
	</tr>
	<tr>
		<td align="right" nowrap>
		<?php echo $strAddress; ?>:&nbsp;
		</td>
		<td>
		<input type="text" class="textbox"  name="work_address" size="30" value="<?php echo $contact["work_address"]; ?>" maxlength="100">
		</td>
	</tr>

	<tr>
		<td align="right" nowrap>
		<?php echo $strZip; ?>:&nbsp;
		</td>
		<td>
		<input type="text" class="textbox"  name="work_zip" size="30" value="<?php echo $contact["work_zip"]; ?>" maxlength="20">
		</td>
	</tr>
	<tr>
		<td align="right" nowrap>
		<?php echo $strCity; ?>:&nbsp;
		</td>
		<td>
		<input type="text" class="textbox"  name="work_city" size="30" value="<?php echo $contact["work_city"]; ?>" maxlength="50">
		</td>
	</tr>

	<tr>
		<td align="right" nowrap>
		<?php echo $strState; ?>:&nbsp;
		</td>
		<td>
		<input type="text" class="textbox"  name="work_state" size="30" value="<?php echo $contact["work_state"]; ?>" maxlength="50">
		</td>
	</tr>

	<tr>
		<td align="right" nowrap>
		<?php echo $strCountry; ?>:&nbsp;
		</td>
		<td>
		<input type="text" class="textbox"  name="work_country" size="30" value="<?php echo $contact["work_country"]; ?>" maxlength="50">
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>

	<tr>
		<td align="right" nowrap>
		<?php echo $strPhone; ?>:&nbsp;
		</td>
		<td>
		<input type="text" class="textbox"  name="work_phone" size="30" value="<?php echo $contact["work_phone"]; ?>" maxlength="20">
		</td>
	</tr>
	<tr>
		<td align="right" nowrap>
		<?php echo $strFax; ?>:&nbsp;
		</td>
		<td>
		<input type="text" class="textbox"  name="work_fax" size="30" value="<?php echo $contact["work_fax"]; ?>" maxlength="20">
		</td>
	</tr>
	<tr>
		<td align="right" nowrap>
		<?php echo $strHomepage; ?>:&nbsp;
		</td>
		<td>
		<input type="text" class="textbox"  name="homepage" size="30" value="<?php echo $contact["homepage"] ?>" maxlength="100">
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>

	<tr>
		<td align="right" nowrap><?php echo $strDepartment; ?>:&nbsp;</td>
		<td><input type="text" class="textbox"  name="department" size="30" value="<?php echo $contact["department"]; ?>" maxlength="50"></td>
	</tr>

	<tr>
		<td align="right" nowrap><?php echo $strFunction; ?>:&nbsp;</td>
		<td><input type="text" class="textbox" name="function" size="30" value="<?php echo $contact["function"]; ?>" maxlength="50"></td>
	</tr>
	</table>

	</td>
</tr>
	<tr>
		<td colspan="2">
			<br />
			<?php
			$button = new button($cmdOk, 'javascript:document.forms[0].submit()');
			echo '&nbsp;&nbsp;';
			if ($contact["source_id"] > 0)
			{
				$button = new button($cmdUpdate, 'javascript:update()');
				echo '&nbsp;&nbsp;';
			}
			$button = new button($cmdClose, 'javascript:window.close();');
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
