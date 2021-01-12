<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

//load user management class
require($GO_CONFIG->class_path."users.class.inc");
$users = new users;

if ($REQUEST_METHOD == "POST")
{
	if (isset($user))
	{
		for ($i=0;$i<sizeof($user);$i++)
		{
			if (!$contacts->user_is_contact($GO_SECURITY->user_id, $user[$i]))
			{
				$profile = $users->get_user($user[$i]);
				$name = addslashes($profile["name"]);
				$email = addslashes($profile["email"]);
				$work_phone = addslashes($profile["work_phone"]);
				$home_phone = addslashes($profile["home_phone"]);
				$fax = addslashes($profile["fax"]);
				$cellular = addslashes($profile["cellular"]);
				$country = addslashes($profile["country"]);
				$state = addslashes($profile["state"]);
				$city = addslashes($profile["city"]);
				$zip = addslashes($profile["zip"]);
				$address = addslashes($profile["address"]);
				$company = addslashes($profile["company"]);
				$department = addslashes($profile["department"]);
				$function = addslashes($profile["function"]);
				$work_fax = addslashes($profile["work_fax"]);
				$work_country = addslashes($profile["work_country"]);
				$work_state = addslashes($profile["work_state"]);
				$work_city = addslashes($profile["work_city"]);
				$work_zip = addslashes($profile["work_zip"]);
				$work_address = addslashes($profile["work_address"]);
				$homepage = addslashes($profile["homepage"]);

				$contacts->add_contact($user[$i], $GO_SECURITY->user_id, $name, $email, $work_phone, $home_phone, $fax, $cellular, $country, $state, $city, $zip, $address, $company, $work_country, $work_state, $work_city, $work_zip, $work_address, $work_fax, $homepage, $department, $function,'',$group);
			}
		}
	}
}


if (!isset($contact_sort) || $contact_sort == "source_id")
        $contact_sort = "name";

if (!isset($contact_direction))
        $contact_direction = "ASC";

if ($contact_direction == "DESC")
{
        $image_string = '&nbsp;<img src="'.$GO_THEME->image_url.'buttons/arrow_down.gif" border="0" />';
        $newdirection = "ASC";
}else
{
        $image_string = '&nbsp;<img src="'.$GO_THEME->image_url.'buttons/arrow_up.gif" border="0" />';
        $newdirection = "DESC";
}



echo '<input type="hidden" name="action" value="'.$action.'" />';

if ($contacts->get_groups($GO_SECURITY->user_id) > 0)
{

	$dropbox = new dropbox();
	$dropbox->add_value('',$contacts_add_to_group);
	$dropbox->add_value('0',$contacts_other);
	$dropbox->add_sql_data('contacts','id','name');
	$dropbox->print_dropbox('group','','onchange="javascript:document.forms[0].submit()"');
}else
{
        echo '<a href="javascript:document.forms[0].submit()" class="normal">'.$contacts_add_to.'</a><br /><br />';
        echo '<input type="hidden" name="group" value="0" />';
}
$count = $users->get_users($GO_SECURITY->user_id, false, $contact_sort, $contact_direction);
echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
echo "<tr>";
echo '<td class="TableHead2" width="16"><input type="checkbox" value="dummy" name="dummy" onclick="javascript:invert_selection()" /></td>';
echo "<td class=\"TableHead2\"><a class=\"TableHead2\" href=\"".$PHP_SELF."?action=members&newsort=name&newdirection=".$newdirection."\">".$strName;
if ($contact_sort == "name")
        echo $image_string;
echo "</a></td>\n";
echo "<td class=\"TableHead2\"><a class=\"TableHead2\" href=\"".$PHP_SELF."?action=members&newsort=email&newdirection=".$newdirection."\">".$strEmail;
if ($contact_sort == "email")
        echo $image_string;
echo "</a></td>\n";
echo "<td class=\"TableHead2\"><a class=\"TableHead2\" href=\"".$PHP_SELF."?action=members&newsort=company&newdirection=".$newdirection."\">".$strCompany;
if ($contact_sort == "company")
        echo $image_string;
echo "</a></td>\n";
echo "<td class=\"TableHead2\">&nbsp;</td>\n";
echo "</tr>\n";


while ($users->next_record())
{
	if(!$GO_SECURITY->has_permission($GO_SECURITY->user_id, $users->f('acl_id')) || $GO_SECURITY->user_id == $users->f("id"))
	{
		$count--;
	}else
	{
		echo "<tr id=\"".$users->f("id")."\" height=\"18\" class=\"Table1\">\n";
		echo "<td width=\"16\"><input type=\"checkbox\" name=\"user[]\" value=\"".$users->f("id")."\" onclick=\"item_click(this)\" /></td>";
		echo "<td>".show_profile($users->f("id"),$users->f("name"))."&nbsp;</a></td>\n";
		echo "<td>".mail_to(empty_to_stripe($users->f("email")))."&nbsp;</td>\n";
		echo "<td>".empty_to_stripe($users->f("company"))."&nbsp;</td>\n";
		echo "<td><a href=\"javascript:popup('add_contact.php?id=".$users->f("id")."','750','500')\" title=\"".$contacts_add_member."\"><img src=\"".$GO_THEME->image_url."buttons/save.gif\" border=\"0\" />";
		echo "</tr>\n";
		echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
	}
}


if($count > 0)
{
	echo '<tr><td colspan="99" height="18">'.$count.' gebruiker(s)</td></tr>';
	echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
}else
{
    echo '<tr><td colspan="99" height="18">'.$contacts_no_contacts.'</td></tr>';
	echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
}
echo "</table>";
?>
<script language="javascript" type="text/javascript">
function item_click(check_box)
{
	var item = get_object(check_box.value);
	if (check_box.checked)
	{
		item.className = 'Table2';
	}else
	{
		item.className = 'Table1';
	}
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

</script>