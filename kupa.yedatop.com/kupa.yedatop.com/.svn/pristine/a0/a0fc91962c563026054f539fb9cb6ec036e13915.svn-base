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
if (isset($SET_HANDLER))
{
	$GO_HANDLER = $SET_HANDLER;
}

if (!$returnfield){
$returnfield = "email";
}

if(isset($SET_FIELD))
{
	$GO_FIELD = $SET_FIELD;
}
session_register("GO_HANDLER", "GO_FIELD");

//remember sorting in cookie
if (isset($newsort))
{
	SetCookie("contact_sort",$newsort,time()+3600*24*365,"/",$GO_CONFIG->host,0);
	$contact_sort = $newsort;
}
if (isset($newdirection))
{
	SetCookie("contact_direction",$newdirection,time()+3600*24*365,"/",$GO_CONFIG->host,0);
	$contact_direction = $newdirection;
}

if (!isset($contact_sort) || ($contact_sort != "name" && $contact_sort != "email"))
{
        $contact_sort = "name";
}
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

$page_title = $contacts_select;

require($GO_THEME->theme_path."simple_header.inc");
require($GO_CONFIG->class_path."contacts.class.inc");
$contacts1 = new contacts;

if (!isset($contacts_expanded))
	$contacts_expanded[]=-1;

if (isset($expand_id) && $post_action=='expand')
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

?>
<script type="text/javascript">
function sort(column)
{
	document.select.action = "<?php echo $PHP_SELF; ?>";
	document.select.newsort.value = column;
	document.select.submit();
}

function expand_group(group_id)
{
	document.select.post_action.value='expand';
	document.select.newdirection.value = '';
	document.select.expand_id.value = group_id;
	document.select.action = "<?php echo $PHP_SELF; ?>";
	document.select.submit();
}
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

</script>
<form method="post" name="select" action="<?php echo $GO_HANDLER; ?>">
<input type="hidden" value="<?php echo $newdirection; ?>" name="newdirection" />
<input type="hidden" name="newsort" />
<input type="hidden" name="expand_id" />
<input type="hidden" name=returnfield value="<?=$returnfield?>" />
<input type="hidden" name="post_action" />
<input type="hidden" name="GO_FIELD" value="<?php echo $GO_FIELD; ?>" />


<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tr>
	<td align="right"><a class="normal" href="javascript:popup('<?php echo $GO_CONFIG->host; ?>contacts/add_contact.php','750','500')"><?php echo $contacts_add; ?></a></td>
	</td>
</tr>
<tr>
        <td><h1>
        <?php
        echo $contacts_select_title;

        if (!isset($contacts))
			$contacts[] = '';

        if (isset($address_string))
        {
                $addresses = cut_address($address_string,$charset);
        }

		if (isset($addresses))
		{
			for ($i=0;$i<sizeof($addresses);$i++)
			{
				$contacts[] = $addresses[$i];
			}
		}

        ?>
        </h1>
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
                <td colspan="99"><?php echo $contacts_select; ?><br /><br /></td>
        </tr>
        <tr>
		<td class="TableHead2" width="16">&nbsp;</td>
		<td class="TableHead2" width="16"><input type="checkbox" name="dummy" value="dummy" onclick="javascript:invert_selection()" /></td>
		<?php
		echo "<td class=\"TableHead2\"><a class=\"TableHead2\" href=\"javascript:sort('name')\">".$strName;
		if ($contact_sort == "name")
				echo $image_string;
		echo "</a></td>\n";
		echo "<td class=\"TableHead2\"><a class=\"TableHead2\" href=\"javascript:sort('email')\">".$strEmail;
		if ($contact_sort == "email")
				echo $image_string;
		echo "</a></td>\n";
		?>
        </tr>
        <?php
        $group_count = $contacts1->get_groups($GO_SECURITY->user_id);
        while ($contacts1->next_record())
        {
			if (in_array($contacts1->f("id"), $contacts_expanded))
			{
				echo "<tr class=\"Table4\"><td width=\"16\"><a href=\"javascript:expand_group(".$contacts1->f("id").")\"><img src=\"".$GO_THEME->image_url."treeview/min_node.gif\" border=\"0\" /></a></td><td width=\"16\"><input type=\"checkbox\" value=\"dummy\" onclick=\"javascript:select_group('".$contacts1->f("id")."')\" /></td><td colspan=\"2\">".$contacts1->f("name")."</td></tr>";
				echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';

				$contacts2 = new contacts;

				if ($contacts2->get_contacts_group($GO_SECURITY->user_id, $contacts1->f("id"), $contact_sort, $contact_direction) > 0)
				{
					echo '<input type="hidden" name="group_start_'.$contacts1->f("id").'" />';
					while ($contacts2->next_record())
					{
						if ($contacts2->f($returnfield) != "")
						{
							$class="Table1";
							$check = "";
							if (isset($contacts))
							{
								$key = array_search($contacts2->f($returnfield), $contacts);
								if (is_int($key))
								{
									unset($contacts[$key]);
									$check = "checked";
									$class = "Table2";
								}
							}

							echo "<tr id=\"".$contacts2->f($returnfield)."\" class=\"".$class."\"><td>&nbsp;</td>\n";
							echo "<td><input type=\"checkbox\" onclick=\"javascript:item_click(this);\" name=\"contacts[]\" value=\"".$contacts2->f($returnfield)."\" ".$check." /></td><td><a class=\"normal\" href=\"javascript:popup('show_profile.php?contact_id=".$contacts2->f("id")."','750','500')\" title=\"".$strShowProfile."\">".empty_to_stripe($contacts2->f("name"))."</a>&nbsp;</td>\n";
							echo '<td><a href="'.$GO_HANDLER.'?email='.$contacts2->f($returnfield).'&GO_FIELD='.$GO_FIELD.'" class="normal">'.$contacts2->f($returnfield).'</a>&nbsp;</td>';
							echo "</tr>\n";
							echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
						}
					}
					echo '<input type="hidden" name="group_end_'.$contacts1->f("id").'" />';
				}else
				{
					echo "<tr><td colspan=\"99\" height=\"18\">".$contacts_no_contacts."</td></tr>";
					echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
				}
			}else
			{
				echo "<tr class=\"Table4\"><td width=\"16\"><a href=\"javascript:expand_group(".$contacts1->f("id").")\"><img src=\"".$GO_THEME->image_url."treeview/plus_node.gif\" border=\"0\" /></a></td><td width=\"16\">&nbsp;</td><td colspan=\"2\" width=\"100%\">".$contacts1->f("name")."</td></tr>";
				echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
			}
        }
        if ($group_count > 0)
        {
			if (in_array('0', $contacts_expanded))
			{
				echo "<tr class=\"Table4\"><td><a href=\"javascript:expand_group('0')\"><img src=\"".$GO_THEME->image_url."treeview/min_node.gif\" border=\"0\" /></a></td><td><input type=\"checkbox\" value=\"dummy\" onclick=\"javascript:select_group('0')\" /></td><td colspan=\"2\">".$contacts_other."</td></tr>";
				echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';

				$contacts1->get_contacts_group($GO_SECURITY->user_id, 0, $contact_sort, $contact_direction);
				if ($contacts1->num_rows() > 0)
				{
					echo '<input type="hidden" name="group_start_0" />';
					while ($contacts1->next_record())
					{
						if ($contacts1->f($returnfield) != "")
						{
							$class="Table1";
							$check = "";
							if (isset($contacts))
							{
								$key = array_search($contacts1->f($returnfield), $contacts);
								if (is_int($key))
								{
									unset($contacts[$key]);
									$check = "checked";
									$class="Table2";
								}
							}

							echo "<tr id=\"".$contacts1->f($returnfield)."\" class=\"".$class."\"><td>&nbsp;</td>\n";
							echo "<td><input onclick=\"javascript:item_click(this);\" type=\"checkbox\" name=\"contacts[]\" value=\"".$contacts1->f($returnfield)."\" ".$check." /></td><td><a class=\"normal\" href=\"javascript:popup('show_profile.php?contact_id=".$contacts1->f("id")."','750','500')\" title=\"".$strShowProfile."\">".empty_to_stripe($contacts1->f("name"))."</a>&nbsp;</td>\n";
							echo '<td><a href="'.$GO_HANDLER.'?email='.$contacts1->f($returnfield).'&GO_FIELD='.$GO_FIELD.'" class="normal">'.$contacts1->f($returnfield).'</a>&nbsp;</td>';
							echo "</tr>\n";
							echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
						}
					}
					echo '<input type="hidden" name="group_end_0" />';
				}else
				{
					echo "<tr><td colspan=\"99\" height=\"18\">".$contacts_no_contacts."</td></tr>";
					echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
				}
			}else
			{
				echo "<tr class=\"Table4\"><td width=\"16\"><a href=\"javascript:expand_group('0')\"><img src=\"".$GO_THEME->image_url."treeview/plus_node.gif\" border=\"0\" /></a></td><td width=\"16\">&nbsp</td><td colspan=\"2\" width=\"100%\">".$contacts_other."</td></tr>";
				echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
			}

        }else
		{
			$contacts1->get_contacts_group($GO_SECURITY->user_id, 0, $contact_sort, $contact_direction);
			if ($contacts1->num_rows() > 0)
			{
				echo '<input type="hidden" name="group_start_0" />';
				while ($contacts1->next_record())
				{
					if ($contacts1->f($returnfield) != "")
					{
						$class = "Table1";
						$check = "";
						if (isset($contacts))
						{
							$key = array_search($contacts1->f($returnfield), $contacts);
							if (is_int($key))
							{
								unset($contacts[$key]);
								$check = "checked";
								$class = "Table2";
							}
						}

						echo "<tr id=\"".$contacts1->f($returnfield)."\" class=\"".$class."\"><td>&nbsp;</td>\n";
						echo "<td><input onclick=\"javascript:item_click(this);\" type=\"checkbox\" name=\"contacts[]\" value=\"".$contacts1->f($returnfield)."\" ".$check." /></td><td><a class=\"normal\" href=\"javascript:popup('show_profile.php?contact_id=".$contacts1->f("id")."','750','500')\" title=\"".$strShowProfile."\">".empty_to_stripe($contacts1->f("name"))."</a>&nbsp;</td>\n";
						echo '<td><a href="'.$GO_HANDLER.'?email='.$contacts1->f($returnfield).'&GO_FIELD='.$GO_FIELD.'" class="normal">'.$contacts1->f($returnfield).'</a>&nbsp;</td>';
						echo "</tr>\n";
						echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
					}
				}
				echo '<input type="hidden" name="group_end_0" />';
			}else
			{
				echo "<tr><td colspan=\"99\" height=\"18\">".$contacts_no_contacts."</td></tr>";
				echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
			}
		}

        echo "</table>";
		if (is_array($contacts))
		{
			while($adress = array_pop($contacts))
			{
				echo '<input type="hidden" name="addresses[]" value="'.$adress.'" />';
			}
		}
        ?>
        </td>
</tr>
<?php
echo '<tr><td align="center"><br />';
$button = new button($cmdOk,'javascript:document.forms[0].submit()');
echo '&nbsp;&nbsp;';
$button = new button($cmdCancel,'javascript:window.close()');
echo '</td></tr>';
?>
</table>
</form>
<?php
require($GO_THEME->theme_path."simple_footer.inc");
?>
