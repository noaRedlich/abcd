<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

if ($REQUEST_METHOD == "POST")
{
	$name = trim($name);

        if ($name != "")
        
        {
		if (validate_input($name))
		{
			if (!$groups->group_exists($name))
			{
				$new_group_id = $groups->add_group($GO_SECURITY->user_id, $name);
				if ($new_group_id != -1)
				{
					//add the owner to the group
					if (is_array($selected_users))
					{
						for ($i=0;$i<sizeof($selected_users);$i++)
						{
							$groups->add_user_to_group($selected_users[$i],$new_group_id);
						}
					}
					$feedback = "<p class=\"Success\">".$add_group_success."</p>";
				}else
				{
					$feedback = "<p class=\"Error\">".$add_group_fail."</p>";
				}
			}else
			{
				$feedback = "<p class=\"Error\">".$add_group_exists."</p>";
			}
		}else
		{
			$feedback = "<p class=\"Error\">".$invalid_chars.": \\ / ? & \"</p>";
		}
        }else
        {
                $feedback = "<p class=\"Error\">".$add_group_no_name."</p>";
        }
}
?>
<form name="add" method="post" action="<?php echo $PHP_SELF; ?>?table_tabindex=<?php echo $table_tabindex; ?>">
<table border="0" cellpadding="10" cellspacing="0">
<tr>
        <td colspan="2">
        <?php
        if (isset($feedback)) echo $feedback;
        echo $add_group_text; ?>
        </td>
</tr>
<tr>
        <td>
        <table border="0">
        <tr>
        	<td>
			<?php echo $strName; ?>:
			</td>
			<td>
			<input type="text" class="textbox" maxlength="50" name="name" value="<?php if (isset($name)) echo $name; ?>" size="30">
			</td>
		</tr>
		</table>
		</td>
</tr>
<tr>
	<td colspan="2">
	<?php
	echo '<table border="0" cellpadding="0" cellspacing="3">';
	echo "<tr><td colspan=\"3\">".$groups_users_in_select."</td></tr>";

	$users->get_users();

	echo "<tr>";
	if (isset($selected_users))
	{
		if (in_array($GO_SECURITY->user_id, $selected_users))
		{
			echo "<td><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"normal\"><tr><td><input type=\"checkbox\" name=\"selected_users[]\" value=\"".$GO_SECURITY->user_id."\" checked /></td>";
			echo "<td valign=\"center\">".show_profile($GO_SECURITY->user_id, $ses_name)."</td></table></td>";
		}else
		{
			echo "<td><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"normal\"><tr><td><input type=\"checkbox\" name=\"selected_users[]\" value=\"".$GO_SECURITY->user_id."\" /></td>";
			echo "<td valign=\"center\">".show_profile($GO_SECURITY->user_id, $ses_name)."</td></table></td>";
		}
	}else
	{
		echo "<td><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"normal\"><tr><td><input type=\"checkbox\" name=\"selected_users[]\" value=\"".$GO_SECURITY->user_id."\" checked /></td>";
		echo "<td valign=\"center\">".show_profile($GO_SECURITY->user_id, $ses_name)."</td></table></td>";
	}

	$i=1;
	while ($users->next_record())
	{
		if ($GO_SECURITY->has_permission($GO_SECURITY->user_id, $users->f('acl_id')))
		{
			if ($users->f("id") != $GO_SECURITY->user_id)
			{
				if ($i==3)
				{
					echo "</tr><tr>";
					$i=0;
				}
				if (isset($selected_users))
				{
					if (in_array($users->f("id"), $selected_users))
					{
						echo "<td><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"normal\"><tr><td><input type=\"checkbox\" name=\"selected_users[]\" value=\"".$users->f("id")."\" checked /></td>";
						echo "<td valign=\"center\">".show_profile($users->f("id"), $users->f("name"))."</td></table></td>";
					}else
					{
						echo "<td><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"normal\"><tr><td><input type=\"checkbox\" name=\"selected_users[]\" value=\"".$users->f("id")."\" /></td>";
						echo "<td valign=\"center\">".show_profile($users->f("id"), $users->f("name"))."</td></table></td>";
					}
				}else
				{
					echo "<td><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"normal\"><tr><td><input type=\"checkbox\" name=\"selected_users[]\" value=\"".$users->f("id")."\" /></td>";
					echo "<td valign=\"center\">".show_profile($users->f("id"), $users->f("name"))."</td></table></td>";
				}
				$i++;
			}
		}
	}
	echo "</tr>";
	echo "</table>";
	?>
	</td>
</tr>

<tr>
    <td colspan="2">
    <?php $button = new button($cmdSave, 'javascript:document.forms[0].submit()'); ?>
	</td>
</tr>
</table>
</form>
<script type="text/javascript">
document.add.name.focus();
</script>