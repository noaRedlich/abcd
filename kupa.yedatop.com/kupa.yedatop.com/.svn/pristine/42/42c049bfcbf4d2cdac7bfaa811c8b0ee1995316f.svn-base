
<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

if (!isset($contacts_expanded))
	$contacts_expanded[]=-1;

if (!isset($contact_sort))
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


if ($REQUEST_METHOD == "POST")
{
	switch($post_action)
	{
		case "delete":
			for ($i=0;$i<sizeof($contact);$i++)
			{
					$contacts->delete_contact($contact[$i]);
			}
			unset($contact);
		break;


		case "move":
			if (!isset($group_id))
			{
				$group_id="0";
			}

			if (isset($contact))
			{
				for ($i=0;$i<sizeof($contact);$i++)
				{
					$contacts->move_contact_to_group($contact[$i], $group_id);
				}
			}

		break;

		case "expand":
			if (isset($expand_id))
			{
				$key = array_search($expand_id, $contacts_expanded);
				if (!$key)
				{
					$contacts_expanded[]=$expand_id;
				}else
				{
					unset($contacts_expanded[$key]);
				}
				session_register("contacts_expanded");
			}
		break;
	}
}

echo '<input type="hidden" name="post_action" value="delete" />';
echo '<input type="hidden" value="'.$newdirection.'" name="newdirection" />';
echo '<input type="hidden" name="newsort" />';
echo '<input type="hidden" name="expand_id" />';

if (isset($feedback)) echo $feedback;

$group_count = $contacts->get_groups($GO_SECURITY->user_id);
if ($group_count > 0)
{
	$groups = array();

	$dropbox = new dropbox();
	$dropbox->add_value('',$contacts_move_to_group);
	$dropbox->add_value('0',$contacts_other);

	while ($contacts->next_record())
	{
		$dropbox->add_value($contacts->f('id'), $contacts->f('name'));
		$group['name'] = $contacts->f('name');
		$group['id'] = $contacts->f('id');
		$groups[] = $group;
	}
	$dropbox->print_dropbox('group_id','','onchange="javascript:move_to_group()"');
}


echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
echo "<tr>";
echo '<td class="TableHead2" width="16">&nbsp;</td>';
echo '<td class="TableHead2" width="16"><input type="checkbox" name="dummy" value="dummy" onclick="javascript:invert_selection()" /></td>';
echo "<td class=\"TableHead2\"><a class=\"TableHead2\" href=\"javascript:sort('name')\">".$strName;
if ($contact_sort == "name")
        echo $image_string;
echo "</a></td>\n";
echo "<td class=\"TableHead2\"><a class=\"TableHead2\" href=\"javascript:sort('email')\">".$strEmail;
if ($contact_sort == "email")
        echo $image_string;
echo "</a></td>\n";
echo "<td class=\"TableHead2\"><a class=\"TableHead2\" href=\"javascript:sort('company')\">".$strCompany;
if ($contact_sort == "company")
        echo $image_string;
echo "</a></td>\n";
echo "<td class=\"TableHead2\"><a class=\"TableHead2\" href=\"javascript:sort('source_id')\">".$strMember;
if ($contact_sort == "source_id")
        echo $image_string;
echo "</a></td>\n";
echo "</tr>\n";

if(isset($groups))
{
	for($i=0;$i<sizeof($groups);$i++)
	{
		if (in_array($groups[$i]['id'], $contacts_expanded))
		{
			echo "<tr class=\"Table4\"><td><a href=\"javascript:expand_group(".$groups[$i]['id'].")\"><img src=\"".$GO_THEME->image_url."treeview/min_node.gif\" border=\"0\" /></a></td><td><input type=\"checkbox\" name=\"dummy\" value=\"dummy\" onclick=\"javascript:select_group('".$groups[$i]['id']."')\" /></td><td colspan=\"4\">".$groups[$i]['name']."</td></tr>";
			echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';

			if ($contacts->get_contacts_group($GO_SECURITY->user_id, $groups[$i]['id'], $contact_sort, $contact_direction) > 0)
			{
				echo '<input type="hidden" name="group_start_'.$groups[$i]['id'].'" />';
				while ($contacts->next_record())
				{
					$checked = "";
					$class = 'Table1';
					if (isset($contact))
					{
						$key = array_search($contacts->f("id"), $contact);
						if (is_int($key))
						{
							unset($contact[$key]);
							$checked = "checked";
							$class = 'Table2';
						}
					}

					echo "<tr id=\"".$contacts->f("id")."\" class=\"".$class."\"><td>&nbsp;</td>\n";
					echo "<td><input type=\"checkbox\" onclick=\"javacript:item_click(this)\" name=\"contact[]\" value=\"".$contacts->f("id")."\" ".$checked." /></td><td><a class=\"normal\" href=\"javascript:popup('show_profile.php?contact_id=".$contacts->f("id")."','750','500')\" title=\"".$strShowProfile."\">".empty_to_stripe($contacts->f("name"))."</a>&nbsp;</td>\n";
					echo "<td>".mail_to(empty_to_stripe($contacts->f("email")))."&nbsp;</td>\n";
					echo "<td>".empty_to_stripe($contacts->f("company"))."&nbsp;</td>\n";
					echo "<td>";
					if ($contacts->f("source_id") > 0)
						echo $cmdYes;
					else
						echo $cmdNo;

					echo "</td>\n";
					echo "</tr>\n";
					echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
				}
				echo '<input type="hidden" name="group_end_'.$groups[$i]['id'].'" />';
			}else
			{
				echo "<tr><td colspan=\"99\" height=\"18\">".$contacts_empty_group."</td></tr>";
				echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
			}
		}else
		{
			echo "<tr class=\"Table4\"><td><a href=\"javascript:expand_group(".$groups[$i]['id'].")\"><img src=\"".$GO_THEME->image_url."treeview/plus_node.gif\" border=\"0\" /></a></td><td>&nbsp;</td><td colspan=\"4\" width=\"100%\">".$groups[$i]['name']."</td></tr>";
			echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
		}
	}
}

if ($group_count > 0)
{
	if (in_array(0, $contacts_expanded))
	{
		echo "<tr class=\"Table4\"><td><a href=\"javascript:expand_group(0)\"><img src=\"".$GO_THEME->image_url."treeview/min_node.gif\" border=\"0\" /></a><td><input type=\"checkbox\" name=\"dummy\" value=\"dummy\" onclick=\"javascript:select_group('0')\" /></td><td colspan=\"4\">".$contacts_other."</td></tr>";
		echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';

		$contacts->get_contacts_group($GO_SECURITY->user_id, 0, $contact_sort, $contact_direction);
		if ($contacts->num_rows() > 0)
		{
			echo '<input type="hidden" name="group_start_0" />';
			while ($contacts->next_record())
			{
				$checked = "";
				$class = 'Table1';
				if (isset($contact))
				{
					$key = array_search($contacts->f("id"), $contact);
					if (is_int($key))
					{
						unset($contact[$key]);
						$checked = "checked";
						$class = 'Table2';
					}
				}

				echo "<tr id=\"".$contacts->f("id")."\" class=\"".$class."\"><td>&nbsp;</td>\n";
				echo "<td><input onclick=\"javacript:item_click(this)\" type=\"checkbox\" name=\"contact[]\" value=\"".$contacts->f("id")."\" ".$checked." /></td><td><a class=\"normal\" href=\"javascript:popup('show_profile.php?contact_id=".$contacts->f("id")."','750','500')\" title=\"".$strShowProfile."\">".empty_to_stripe($contacts->f("name"))."</a>&nbsp;</td>\n";
				echo "<td>".mail_to(empty_to_stripe($contacts->f("email")))."&nbsp;</td>\n";
				echo "<td>".empty_to_stripe($contacts->f("company"))."&nbsp;</td>\n";
				echo "<td>";
				if ($contacts->f("source_id") > 0)
					echo $cmdYes;
				else
					echo $cmdNo;

				echo "</td>\n";
				echo "</tr>\n";
				echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
			}
			echo '<input type="hidden" name="group_end_0" />';
		}else
		{
			if ($group_count > 0)
				$text = $contacts_empty_group;
			else
				$text = $contacts_no_contacts;

			echo "<tr><td colspan=\"99\" height=\"18\" class=\"normal\">".$text."</td></tr>";
			echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
		}
	}else
	{
		echo "<tr class=\"Table4\"><td><a href=\"javascript:expand_group(0)\"><img src=\"".$GO_THEME->image_url."treeview/plus_node.gif\" border=\"0\" /></a><td>&nbsp;</td><td colspan=\"4\" width=\"100%\">".$contacts_other."</td></tr>";
		echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
	}
}else
{
	$contacts->get_contacts_group($GO_SECURITY->user_id, 0, $contact_sort, $contact_direction);
	if ($contacts->num_rows() > 0)
	{
		echo '<input type="hidden" name="group_start_0" />';
		while ($contacts->next_record())
		{
			$class = 'Table1';
			$checked = "";
			if (isset($contact))
			{
				$key = array_search($contacts->f("id"), $contact);
				if (is_int($key))
				{
					unset($contact[$key]);
					$checked = "checked";
					$class = 'Table2';
				}
			}

			echo "<tr id=\"".$contacts->f("id")."\" class=\"".$class."\"><td width=\"16\">&nbsp;</td>\n";
			echo "<td width=\"16\"><input onclick=\"javacript:item_click(this)\"  type=\"checkbox\" name=\"contact[]\" value=\"".$contacts->f("id")."\" ".$checked." /></td><td><a class=\"normal\" href=\"javascript:popup('show_profile.php?contact_id=".$contacts->f("id")."','750','500')\" title=\"".$strShowProfile."\">".empty_to_stripe($contacts->f("name"))."</a>&nbsp;</td>\n";
			echo "<td>".mail_to(empty_to_stripe($contacts->f("email")))."&nbsp;</td>\n";
			echo "<td>".empty_to_stripe($contacts->f("company"))."&nbsp;</td>\n";
			echo "<td>";
			if ($contacts->f("source_id") > 0)
				echo $cmdYes;
			else
				echo $cmdNo;

			echo "</td>\n";
			echo "</tr>\n";
			echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
		}
		echo '<input type="hidden" name="group_end_0" />';
	}else
	{
		if ($group_count > 0)
			$text = $contacts_empty_group;
		else
			$text = $contacts_no_contacts;

		echo "<tr><td colspan=\"99\" height=\"18\" class=\"normal\" width=\"100%\">".$text."</td></tr>";
		echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
	}

}

if (isset($contact))
{
	while($adress = array_pop($contact))
	{
		echo '<input type="hidden" name="contact[]" value="'.$adress.'" />';
	}
}
echo '</table>';
?>
<script type="text/javascript" language="javascript">
<!--
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

function select_group(group_id)
{
	var add = false;

	for (var i = 0; i < document.forms[0].elements.length; i++)
	{
		if (document.forms[0].elements[i].name == 'group_start_'+group_id)
		{
			add = true;
		}

		if (document.forms[0].elements[i].name == 'group_end_'+group_id)
		{
			add = false;
		}

		if(document.forms[0].elements[i].type == 'checkbox' && document.forms[0].elements[i].name != 'dummy' && add==true)
		{
			document.forms[0].elements[i].checked = !(document.forms[0].elements[i].checked);
			item_click(document.forms[0].elements[i]);
		}
	}
}

function confirm_delete()
{
	var count = 0;

	for (var i=0;i<document.forms[0].elements.length;i++)
	{
		if(document.forms[0].elements[i].type == 'checkbox' && document.forms[0].elements[i].name != 'dummy')
		{
			if (document.forms[0].elements[i].checked == true)
			{
				count++;

			}
		}
	}
	switch (count)
	{
		case 0:
			alert("<?php echo $contacts_no_select; ?>");
		break;

		case 1:
			if (confirm("<?php echo $strDeletePrefix.$contacts_this_contact.$strDeleteSuffix; ?>"))
			{
				document.forms[0].post_action.value="delete";
				document.forms[0].submit();
			}
		break;

		default:
			if (confirm("<?php echo $strDeletePrefix.$strThis; ?> "+count+" <?php echo $contacts_contacts.$strDeleteSuffix; ?>"))
			{
				document.forms[0].post_action.value="delete";
				document.forms[0].submit();
			}
		break;
	}
}

function move_to_group()
{
	document.forms[0].post_action.value="move";
	document.forms[0].submit();
}
function sort(column)
{
	document.forms[0].post_action.value = 'sort';
	document.forms[0].newsort.value = column;
	document.forms[0].submit();
}

function expand_group(group_id)
{
	document.forms[0].newdirection.value = '';
	document.forms[0].expand_id.value = group_id;
	document.forms[0].post_action.value = "expand";
	document.forms[0].submit();
}

//-->
</script>