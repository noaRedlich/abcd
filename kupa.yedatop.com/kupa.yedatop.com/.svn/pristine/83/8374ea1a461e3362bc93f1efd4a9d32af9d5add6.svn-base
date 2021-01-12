<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

//////////////////////////////////////////////////////////////////////////////////
// Put this anywhere inside a form and set $acl_control_acl_id.    				//
// The acl will automatically be updated after submission.	 					//
//////////////////////////////////////////////////////////////////////////////////

$acl_control_columns = isset($acl_control_columns) ? $acl_control_columns :3;
$acl_control_acl_id = isset($acl_control_acl_id) ? $acl_control_acl_id : 0;
$acl_control_set_acl = isset($acl_control_set_acl) ? $acl_control_set_acl : false;
$selected_groups_name = "selected_groups_".$acl_control_acl_id;
$selected_users_name = "selected_users_".$acl_control_acl_id;

${$selected_groups_name} = isset(${$selected_groups_name}) ? ${$selected_groups_name} : array();
${$selected_users_name} = isset(${$selected_users_name}) ? ${$selected_users_name} : array();

if ($acl_control_set_acl == 'true')
{
	$GO_SECURITY->clear_acl($acl_control_acl_id);
	for ($i=0;$i<count(${$selected_groups_name});$i++)
	{
		$GO_SECURITY->add_group_to_acl(${$selected_groups_name}[$i],$acl_control_acl_id);
	}

	for ($i=0;$i<count(${$selected_users_name});$i++)
	{
		$GO_SECURITY->add_user_to_acl(${$selected_users_name}[$i],$acl_control_acl_id);
	}
}

echo '<input type="hidden" name="acl_control_set_acl" value="true" />';
?>

<table border="0" cellpadding="0" cellspacing="0">
<tr>
        <td colspan="3"><b><?php echo $acl_control_auth_groups; ?><b></td>
</tr>

<?php
if (isset($acl_control_read_only) && $acl_control_read_only)
{
	$disabled = "disabled";
}else
{
	$disabled = "";
}

require_once($GO_CONFIG->class_path."groups.class.inc");
$groups = new groups;
$groups->get_authorised_groups($GO_SECURITY->user_id);
$i=0;
echo "<tr>\n";
while ($groups->next_record())
{
	if ($i==$acl_control_columns)
	{
			echo "</tr>\n<tr>";
			$i=0;
	}

	if ((!$acl_control_set_acl && in_array($groups->f("id"), ${$selected_groups_name})) || ($acl_control_acl_id > 0 && $GO_SECURITY->group_in_acl($groups->f("id"),$acl_control_acl_id)))
	{
			echo "<td><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td><input type=\"checkbox\" name=\"".$selected_groups_name."[]\" value=\"".$groups->f("id")."\" checked ".$disabled." /></td>";
	}else
	{
			echo "<td><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td><input type=\"checkbox\" name=\"".$selected_groups_name."[]\" value=\"".$groups->f("id")."\" ".$disabled." /></td>";
	}
	echo "<td><a class=\"normal\" href=\"javascript:popup('".$GO_CONFIG->host."groups/show_group.php?group_id=".$groups->f("id")."&name=".urlencode($groups->f("name"))."','350','350')\" title=\"".$strShowGroup."\">".$groups->f("name")."</a></td></tr></table></td>\n";
	$i++;
}
echo "</tr>\n";

echo "<tr><td colspan=\"3\"><br /><b>".$acl_control_auth_users."</b></td></tr>";
echo "<tr>\n";
if (!isset($acl_control_hide_self) || !$acl_control_hide_self)
{
	if (isset($acl_control_select_self) && $acl_control_select_self)
	{
		echo "<input type=\"hidden\" name=\"".$selected_users_name."[]\" value=\"".$GO_SECURITY->user_id."\" />";
		echo "<td><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td><input type=\"checkbox\" name=\"dummy\" value=\"".$GO_SECURITY->user_id."\" checked disabled /></td>";
		echo "<td>".show_profile($GO_SECURITY->user_id,$ses_name)."</td></tr></table></td>\n";
	}else
	{
		if ((!$acl_control_set_acl && in_array($GO_SECURITY->user_id, ${$selected_users_name})) || ($acl_control_acl_id > 0 && $GO_SECURITY->user_in_acl($GO_SECURITY->user_id,$acl_control_acl_id)))
		{
			echo "<td><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td><input type=\"checkbox\" name=\"".$selected_users_name."[]\" value=\"".$GO_SECURITY->user_id."\" checked ".$disabled." /></td>";
			echo "<td>".show_profile($GO_SECURITY->user_id,$ses_name)."</td></tr></table></td>\n";
		}else
		{
			echo "<td><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td><input type=\"checkbox\" name=\"".$selected_users_name."[]\" value=\"".$GO_SECURITY->user_id."\" ".$disabled." /></td>";
			echo "<td>".show_profile($GO_SECURITY->user_id,$ses_name)."</td></tr></table></td>\n";

		}
	}
        $i=1;
}else
{
        $i=0;
}
require_once($GO_CONFIG->class_path."users.class.inc");
$users = new users;
$count = $users->get_users();

while ($users->next_record())
{
	if ($i==$acl_control_columns)
	{
			echo "</tr><tr>\n";
			$i=0;
	}

	if (!$GO_SECURITY->has_permission($GO_SECURITY->user_id, $users->f('acl_id')) || $users->f("id") == $GO_SECURITY->user_id)
	{
		$count--;
	}else
	{
		if ((!$acl_control_set_acl && in_array($users->f("id"), ${$selected_users_name})) || ($acl_control_acl_id > 0 && $GO_SECURITY->user_in_acl($users->f("id"),$acl_control_acl_id)))
		{
			echo "<td><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td><input type=\"checkbox\" name=\"".$selected_users_name."[]\" value=\"".$users->f("id")."\" checked ".$disabled." /></td>";
			echo "<td>".show_profile($users->f("id"),$users->f("username"))."</td></tr></table></td>\n";
		}else
		{
			echo "<td><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td><input type=\"checkbox\" name=\"".$selected_users_name."[]\" value=\"".$users->f("id")."\" ".$disabled." /></td>";
			echo "<td>".show_profile($users->f("id"),$users->f("username"))."</td></tr></table></td>\n";
		}
		$i++;
	}
}

if ($count == 0 && isset($acl_control_hide_self))
        echo "<td colspan=\"3\"><br /><small>".$acl_control_no_contacts."</small></td>";

echo "</tr></table>";
?>
