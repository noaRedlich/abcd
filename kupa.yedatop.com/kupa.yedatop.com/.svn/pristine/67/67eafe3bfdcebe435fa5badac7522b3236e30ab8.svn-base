<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

//perform on delete request
if (isset($delete_group))
{
        //only owners can delete groups
        if ($groups->user_owns_group($GO_SECURITY->user_id, $delete_group) && $delete_group != $groups->group_everyone && $delete_group != $groups->group_root)
        {
                $groups->delete_group($delete_group);
        }else
        {
                $feedback = "<P class=\"Error\">".$strAccessDenied."</p>";
        }
}

//performed when save button is pressed
if ($REQUEST_METHOD == "POST")
{
        if ($groups->user_owns_group($GO_SECURITY->user_id, $group_id))
        {
                $groups->clear_group($group_id);
                for ($i=0;$i<count($selected_users);$i++)
                {
                        $groups->add_user_to_group($selected_users[$i], $group_id);
                }
                $feedback = "<P class=\"Success\">".$groups_saved."</p>";
        }else
        {
                $feedback = "<P class=\"Error\">".$strAccessDenied."</p>";
        }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">

<html>
<head>
  <meta name="generator" content=
  "HTML Tidy for Windows (vers 1st November 2003), see www.w3.org">

  <title></title>
</head>

<body>
  <table border="0" cellpadding="10" cellspacing="0" class=
  "normal">
    <tr>
      <td colspan="2"><?php if(isset($feedback)) echo $feedback;
        echo $groups_text; ?></td>
    </tr>

    <tr>
      <td valign="top">
        <table cellpadding="4" cellspacing="0" border="0">
          <tr>
            <td>
              <h3><?php echo $strName; ?></h3>
            </td>

            <td>
              <h3><?php echo $strOwner; ?></h3>
            </td>

            <td>&nbsp;</td>
          </tr><?php
                //show the groups the user is in and owns.
                $groups->get_authorised_groups($GO_SECURITY->user_id);
                $class='Table1';
                while ($groups->next_record())
                {
                        echo '<tr class="'.$class.'">';
                        echo "<td><a class=\"normal\" href=\"".$PHP_SELF."?group_id=".$groups->f("id")."&group_name=".$groups->f("name")."\">".$groups->f("name")."</a></td>\n";
                        echo "<td>".show_profile($groups->f("user_id"), $groups->f("username"))."</td>\n";
                        echo "<td><a href='javascript:confirm_action(\"".$PHP_SELF."?delete_group=".$groups->f("id")."\",\"".rawurlencode($strDeletePrefix."'".$groups->f("name")."'".$strDeleteSuffix)."\")' title=\"".$strDeleteItem." '".$groups->f("name")."'\"><img src=\"".$GO_THEME->image_url."buttons/delete.gif\" border=\"0\"></a></td>\n";
                        echo "</tr>";
                        #if ($class != 'Table2') $class='Table2'; else $class='Table1';
                }
                ?>

        </table>
      </td>

      <td valign="top"><?php
                if (isset($group_id))
                {
                        echo "<form name=\"save\" method=\"post\" action=\"".$PHP_SELF."\">";
                        echo "<input type=\"hidden\" name=\"group_name\" value=\"".$group_name."\">";
                        echo "<input type=\"hidden\" name=\"group_id\" value=\"".$group_id."\">";
                        echo '<table border="0" cellpadding="0" cellspacing="3">';
                        echo "<tr><td colspan=\"2\"><h3>".$groups_users_in.$group_name."</h3></td></tr>";
			if ($group_id != $groups->group_everyone)
			{
				$users->get_users();

				echo "<tr>";
				if ($groups->is_in_group($GO_SECURITY->user_id,$group_id))
				{
					echo "<td><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"normal\"><tr><td><input type=\"checkbox\" name=\"selected_users[]\" value=\"".$GO_SECURITY->user_id."\" checked /></td>";
					echo "<td valign=\"center\">".show_profile($GO_SECURITY->user_id, $ses_name)."</td></table></td>";
				}else
				{
					echo "<td><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"normal\"><tr><td><input type=\"checkbox\" name=\"selected_users[]\" value=\"".$GO_SECURITY->user_id."\" /></td>";
					echo "<td valign=\"center\">".show_profile($GO_SECURITY->user_id, $ses_name)."</td></table></td>";
				}
				$i=1;
				while ($users->next_record())
				{
					if ($GO_SECURITY->has_permission($GO_SECURITY->user_id, $users->f('acl_id')))
					{
						if ($users->f("id") != $GO_SECURITY->user_id)
						{
							if ($i==2)
							{
								echo "</tr><tr>";
								$i=0;
							}

							if ($groups->is_in_group($users->f("id"),$group_id))
							{
								echo "<td><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"normal\"><tr><td><input type=\"checkbox\" name=\"selected_users[]\" value=\"".$users->f("id")."\" checked /></td>";
								echo "<td valign=\"center\">".show_profile($users->f("id"), $users->f("name"))."</td></table></td>";
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
				echo '<tr><td colspan="2"><br />';
				$button = new button($cmdSave, 'javascript:document.forms[0].submit()');
				echo '</td></tr>';
			}else
			{
				echo "<tr><td colspan=\"2\">".$groups_everyone."</td></tr>";
			}

                        echo "</table>";
                        echo "</form>";
                }
                ?>
</td>
    </tr>
  </table>
</body>
</html>