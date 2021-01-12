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

$page_title = $contacts_add;
require($GO_THEME->theme_path."simple_header.inc");

require($GO_CONFIG->class_path."/validate.class.inc");
$val = new validate;

if ($REQUEST_METHOD == "POST")
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
			if ($contacts->add_contact($id, $GO_SECURITY->user_id, $name, $email, $work_phone, $home_phone, $fax, $cellular, $country, $state, $city, $zip, $address, $company, $work_country, $work_state, $work_city, $work_zip, $work_address, $work_fax, $homepage, $department, $function, $comments, $group))
			{
					?>
					<script type="text/javascript">
						opener.location=opener.location;
							window.close();
					</script>
					<?php
					$feedback = "<p class=\"Success\">".$strSaveSuccess."</b></p>";
			}else
			{
					$feedback = "<p class=\"Error\">".$strSaveError."</p>";
			}
	}else
	{
			$feedback ="<p class='Error'>".$errors_in_form."</p>";
	}
}else
{
	if (isset($id))
	{
		//load user management class
		require($GO_CONFIG->class_path."users.class.inc");
		$users = new users;

		$profile = $users->get_user($id);
		extract($profile,EXTR_OVERWRITE);

		if ($contacts->user_is_contact($GO_SECURITY->user_id, $id))
		{
				$feedback = "<p class='Error'>".$contact_exist_warning."</p>";
				$id = "";
		}
	}
}
?>
<table border="0" cellpadding="10" cellspacing="0">
<tr>
	<td>
        <form name="add" method="post" action="<?php echo $PHP_SELF; ?>?table_tabindex=<?php echo $table_tabindex; ?>">
        <input type="hidden" value="<?php echo $id; ?>" name="id" />
        <table border=0 cellpadding="2" cellspacing="0">
        <tr>
			<td colspan="2"><h1><?php echo $contacts_add; ?></h1>
	        <?php if(isset($feedback)) echo $feedback;
	        echo $add_personal_text; ?>
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
			<td align="right" nowrap>
			<?php echo $strName; ?>*:&nbsp;
			</td>
			<td>
			<input type="text" class="textbox"  name="name" size="30" value="<?php if(isset($name)) echo $name; ?>" maxlength="50">
			</td>
		</tr>
		<tr>
			<td align="right" nowrap>
			<?php echo $strAddress; ?>:&nbsp;
			</td>
			<td>
			<input type="text" class="textbox"  name="address" size="30" value="<?php if(isset($address)) echo $address; ?>" maxlength="100">
			</td>
		</tr>

		<tr>
			<td align="right" nowrap>
			<?php echo $strZip; ?>:&nbsp;
			</td>
			<td>
			<input type="text" class="textbox"  name="zip" size="30" value="<?php if(isset($zip)) echo $zip; ?>" maxlength="20">
			</td>
		</tr>
		<tr>
			<td align="right" nowrap>
			<?php echo $strCity; ?>:&nbsp;
			</td>
			<td>
			<input type="text" class="textbox"  name="city" size="30" value="<?php if(isset($city)) echo $city; ?>" maxlength="50">
			</td>
		</tr>


		<tr>
			<td align="right" nowrap>
			<?php echo $strState; ?>:&nbsp;
			</td>
			<td>
			<input type="text" class="textbox"  name="state" size="30" value="<?php if(isset($state)) echo $state; ?>" maxlength="50">
			</td>
		</tr>

		<tr>
			<td align="right" nowrap>
			<?php echo $strCountry; ?>:&nbsp;
			</td>
			<td>
			<input type="text" class="textbox"  name="country" size="30" value="<?php if(isset($country)) echo $country; ?>" maxlength="50">
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
			<input type="text" class="textbox"  name="home_phone" size="30" value="<?php if(isset($home_phone)) echo $home_phone; ?>" maxlength="20">
			</td>
		</tr>



		<tr>
			<td align="right" nowrap>
			<?php echo $strFax; ?>:&nbsp;
			</td>
			<td>
			<input type="text" class="textbox"  name="fax" size="30" value="<?php if(isset($fax)) echo $fax; ?>" maxlength="20">
			</td>
		</tr>

		<tr>
			<td align="right" nowrap>
			<?php echo $strCellular; ?>:&nbsp;
			</td>
			<td>
			<input type="text" class="textbox"  name="cellular" size="30" value="<?php if(isset($cellular)) echo $cellular; ?>" maxlength="20">
			</td>
		</tr>
		<?php
		if (isset($val->error["email"]))
		{
		?>
		<tr>
			<td colspan="2" class="Error">
				<?php echo $val->error["email"]; ?>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td align="right" nowrap>
			<?php echo $strEmail; ?>*:&nbsp;
			</td>
			<td class="small">
			<input type="text" class="textbox"  name="email" size="30" value="<?php if(isset($email)) echo $email; ?>" maxlength="75">
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td valign="top"><?php echo $strComments; ?>:</td>
			<td><textarea class="textbox" cols="30" rows="2" name="comments"><?php if(isset($comments)) echo $comments; ?></textarea></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<?php
		if ($contacts->get_groups($GO_SECURITY->user_id) > 0)
		{
			echo '<tr><td align="right" nowrap>'.$contacts_group.':&nbsp;</td><td>';

			$group = isset($group) ? $group : 0;
			$dropbox = new dropbox();
			$dropbox->add_value('0',$contacts_other);
			$dropbox->add_sql_data('contacts','id','name');
			$dropbox->print_dropbox('group',$group);
			echo '</td></tr>';
		}
		?>


		</table>
		</td>
		<td valign="top">
			<table border="0" cellpadding="0" cellspacing="3">
			<tr>
				<td align="right" nowrap>
				<?php echo $strCompany; ?>:&nbsp;
				</td>
				<td>
				<input type="text" class="textbox"  name="company" size="30" value="<?php if(isset($company)) echo $company; ?>" maxlength="50">
				</td>
			</tr>
			<tr>
				<td align="right" nowrap>
				<?php echo $strAddress; ?>:&nbsp;
				</td>
				<td>
				<input type="text" class="textbox"  name="work_address" size="30" value="<?php if(isset($work_address)) echo $work_address; ?>" maxlength="100">
				</td>
			</tr>

			<tr>
				<td align="right" nowrap>
				<?php echo $strZip; ?>:&nbsp;
				</td>
				<td>
				<input type="text" class="textbox"  name="work_zip" size="30" value="<?php if(isset($work_zip)) echo $work_zip; ?>" maxlength="20">
				</td>
			</tr>
			<tr>
				<td align="right" nowrap>
				<?php echo $strCity; ?>:&nbsp;
				</td>
				<td>
				<input type="text" class="textbox"  name="work_city" size="30" value="<?php if(isset($work_city)) echo $work_city; ?>" maxlength="50">
				</td>
			</tr>


			<tr>
				<td align="right" nowrap>
				<?php echo $strState; ?>:&nbsp;
				</td>
				<td>
				<input type="text" class="textbox"  name="work_state" size="30" value="<?php if(isset($work_state)) echo $work_state; ?>" maxlength="50">
				</td>
			</tr>

			<tr>
				<td align="right" nowrap>
				<?php echo $strCountry; ?>:&nbsp;
				</td>
				<td>
				<input type="text" class="textbox"  name="work_country" size="30" value="<?php if(isset($work_country)) echo $work_country; ?>" maxlength="50">
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
				<input type="text" class="textbox"  name="work_phone" size="30" value="<?php if(isset($work_phone)) echo $work_phone; ?>" maxlength="20">
				</td>
			</tr>
			<tr>
				<td align="right" nowrap>
				<?php echo $strFax; ?>:&nbsp;
				</td>
				<td>
				<input type="text" class="textbox"  name="work_fax" size="30" value="<?php if(isset($work_fax)) echo $work_fax; ?>" maxlength="20">
				</td>
			</tr>
			<tr>
				<td align="right" nowrap>
				<?php echo $strHomepage; ?>:&nbsp;
				</td>
				<td>
				<input type="text" class="textbox"  name="homepage" size="30" value="<?php if(isset($homepage)) echo $homepage ?>" maxlength="100">
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td align="right" nowrap>
				<?php echo $strDepartment; ?>:&nbsp;
				</td>
				<td>
				<input type="text" class="textbox"  name="department" size="30" value="<?php if(isset($department)) echo $department; ?>" maxlength="50">
				</td>
			</tr>

			<tr>
				<td align="right" nowrap>
				<?php echo $strFunction; ?>:&nbsp;
				</td>
				<td>
				<input type="text" class="textbox"  name="function" size="30" value="<?php if(isset($function)) echo $function; ?>" maxlength="50">
				</td>
			</tr>
			</table>
		</td>
		</tr>
		<tr heigth="25">
			<td>
			<br />
			<?php
			$button = new button($cmdOk, 'javascript:document.forms[0].submit()');
			echo '&nbsp;&nbsp;';
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
