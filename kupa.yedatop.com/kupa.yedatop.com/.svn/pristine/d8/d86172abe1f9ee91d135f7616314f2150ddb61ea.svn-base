<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

require("../Group-Office.php");
$post_action = isset($post_action) ? $post_action : '';
$action = isset($action) ? $action : '';
$GO_SECURITY->authenticate();
require($GO_LANGUAGE->get_language_file('contacts'));
//remember sorting in cookie
if ($post_action == 'sort')
{
	if (isset($newsort))
	{
		SetCookie("contact_sort",$newsort,time()+3600*24*365,"/","",0);
		$contact_sort = $newsort;
	}
	if (isset($newdirection))
	{
		SetCookie("contact_direction",$newdirection,time()+3600*24*365,"/","",0);
		$contact_direction = $newdirection;
	}
}

//load contact management class
require($GO_CONFIG->class_path."contacts.class.inc");
$contacts = new contacts;


function check_columns($columns)
{
	$check = array();
	for ($i=0;$i<sizeof($columns);$i++)
	{
		if ($columns[$i] != "")
		{
			if (!in_array($columns[$i], $check))
			{
				$check[] = $columns[$i];
			}else
			{
				return false;
			}
		}
	}
	return true;
}

if ($REQUEST_METHOD == 'POST')
{
	switch($action)
   	{
		case "import_data":
			$contact_groups[''] = 0;
			$group_mode = isset($group_mode) ? $group_mode : 'group_name';
			if ($group_mode == 'file')
			{
				$contacts->get_groups($GO_SECURITY->user_id);
				while ($contacts->next_record())
				{
					$contact_groups[$contacts->f('name')] = $contacts->f('id');
				}
			}
			if (!isset($seperator))
			{
				$seperator = ";";
			}

			if (check_columns($columns))
			{
				$columns = array_flip($columns);
				$fp = fopen($csv_file, "r");
				if ($fp)
				{
					fgets($fp, 4096);
					if(isset($columns["name"]))
					{
						while (!feof($fp))
						{
							$line = str_replace('"', '',fgets($fp, 4096));

							$record = explode($seperator, $line);
							if(isset($record[$columns["name"]]) &&  $record[$columns["name"]] != "")
							{
								if ($group_mode == 'file')
								{
									$group_name = trim($record[$group_record]);
									if (isset($contact_groups[$group_name]))
									{
										$group_id = $contact_groups[$group_name];
									}else
									{
										$contacts2= new contacts();
										$group_id = $contacts2->add_group($GO_SECURITY->user_id, $group_name);
										$contact_groups[$group_name] = $group_id;
									}
								}

								$name = isset($columns["name"]) ? addslashes(trim($record[$columns["name"]])) : '';
								$email = isset($columns["email"]) ? addslashes(trim($record[$columns["email"]])) : '';
								$work_phone = isset($columns["work_phone"]) ? addslashes(trim($record[$columns["work_phone"]])) : '';
								$home_phone = isset($columns["home_phone"]) ? addslashes(trim($record[$columns["home_phone"]])) : '';
								$fax = isset($columns["fax"]) ? addslashes(trim($record[$columns["fax"]]," ")) : '';
								$cellular = isset($columns["cellular"]) ? addslashes(trim($record[$columns["cellular"]])) : '';
								$country = isset($columns["country"]) ? addslashes(trim($record[$columns["country"]])) : '';
								$state = isset($columns["state"]) ? addslashes(trim($record[$columns["state"]])) : '';
								$city = isset($columns["city"]) ? addslashes(trim($record[$columns["city"]])) : '';
								$zip = isset($columns["zip"]) ? addslashes(trim($record[$columns["zip"]])) : '';
								$address = isset($columns["address"]) ? addslashes(trim($record[$columns["address"]])) : '';
								$company = isset($columns["company"]) ? addslashes(trim($record[$columns["company"]])) : '';
								$work_country = isset($columns["work_country"]) ? addslashes(trim($record[$columns["work_country"]])) : '';
								$work_state = isset($columns["work_state"]) ? addslashes(trim($record[$columns["work_state"]])) : '';
								$work_city = isset($columns["work_city"]) ? addslashes(trim($record[$columns["work_city"]])) : '';
								$work_zip = isset($columns["work_zip"]) ? addslashes(trim($record[$columns["work_zip"]])) : '';
								$work_address = isset($columns["work_address"]) ? addslashes(trim($record[$columns["work_address"]])) : '';
								$work_fax = isset($columns["work_fax"]) ? addslashes(trim($record[$columns["work_fax"]])) : '';
								$homepage = isset($columns["homepage"]) ? addslashes(trim($record[$columns["homepage"]])) : '';
								$department = isset($columns["department"]) ? addslashes(trim($record[$columns["department"]])) : '';
								$function = isset($columns["function"]) ? addslashes(trim($record[$columns["function"]])) : '';

								$contacts->add_contact("", $GO_SECURITY->user_id, $name, $email, $work_phone, $home_phone, $fax, $cellular, $country, $state, $city, $zip, $address, $company, $work_country, $work_state, $work_city, $work_zip, $work_address, $work_fax, $homepage, $department, $function,'', $group_id);
							}
						}
						fclose($fp);
						unlink($csv_file);
						$feedback = "<p class=\"Success\">".$contacts_import_success."</p>";
						$action = '';
					}else
					{
						$feedback = "<p class=\"Error\">".$contacts_import_noname."</p>";
					}
				}else
				{
					$feedback = "<p class=\"Error\">".$strDataError."</p>";

				}
			}else
			{
				$feedback = '<p class="Error">'.$contacts_import_double.'</p>';
			}

     	break;

     	case 'export':

			$browser = detect_browser();

			header("Content-type: text/x-csv");
			header('Expires: '.gmdate('D, d M Y H:i:s') . ' GMT');
			if ($browser['name'] == 'MSIE')
			{
				header("Content-Disposition: inline; filename=".$contacts_title.".csv");
        		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        		header('Pragma: public');
			}else
			{
				header('Pragma: no-cache');
				header("Content-Disposition: attachment; filename=".$contacts_title.".csv");
			}

			$quote = smartstrip($quote);
			$crlf = smartstrip($crlf);
			$crlf = str_replace('\\r', "\015", $crlf);
			$crlf = str_replace('\\n', "\012", $crlf);
            $crlf = str_replace('\\t', "\011", $crlf);
			$separator = smartstrip($separator);

			echo $quote.$strName.$quote.$separator.$quote.$strEmail.$quote.$separator.$quote.$strCountry.$quote.$separator.$quote.$strState.$quote.$separator.$quote.$strCity.$quote.$separator.$quote.$strZip.$quote.$separator.$quote.$strAddress.$quote.$separator.$quote.$strPhone.$quote.$separator.$quote.$strWorkphone.$quote.$separator.$quote.$strFax.$quote.$separator.$quote.$strCellular.$quote.$separator.$quote.$strCompany.$quote.$separator.$quote.$strWorkCountry.$quote.$separator.$quote.$strWorkState.$quote.$separator.$quote.$strWorkCity.$quote.$separator.$quote.$strWorkZip.$quote.$separator.$quote.$strWorkAddress.$quote.$separator.$quote.$strWorkFax.$quote.$separator.$quote.$strHomepage.$quote.$separator.$quote.$strDepartment.$quote.$separator.$quote.$strFunction.$quote.$separator.$quote.$contacts_group.$quote;
			echo $crlf;

			$contacts->get_contacts_with_group($GO_SECURITY->user_id);
			while ($contacts->next_record())
			{
				echo trim($quote.$contacts->f("name").$quote.$separator.$quote.$contacts->f("email").$quote.$separator.$quote.$contacts->f("country").$quote.$separator.$quote.$contacts->f("state").$quote.$separator.$quote.$contacts->f("city").$quote.$separator.$quote.$contacts->f("zip").$quote.$separator.$quote.$contacts->f("address").$quote.$separator.$quote.$contacts->f("home_phone").$quote.$separator.$quote.$contacts->f("work_phone").$quote.$separator.$quote.$contacts->f("fax").$quote.$separator.$quote.$contacts->f("cellular").$quote.$separator.$quote.$contacts->f("company").$quote.$separator.$quote.$contacts->f("work_country").$quote.$separator.$quote.$contacts->f("work_state").$quote.$separator.$quote.$contacts->f("work_city").$quote.$separator.$quote.$contacts->f("work_zip").$quote.$separator.$quote.$contacts->f("work_address").$quote.$separator.$quote.$contacts->f("work_fax").$quote.$separator.$quote.$contacts->f("homepage").$quote.$separator.$quote.$contacts->f("department").$quote.$separator.$quote.$contacts->f("function").$quote.$separator.$quote.$contacts->f('group_name').$quote);
				echo $crlf;
			}
			exit;
		break;

     	case 'authorise':
			if (!$contacts->authorise($authcode,$email,$GO_SECURITY->user_id))
			{
				$feedback = "<p class=\"Error\">".$add_contacts_wrong."</p>";
			}else
			{
				$action = 'members';
			}
     	break;
	}
}

$page_title = $menu_contacts;
require($GO_THEME->theme_path."header.inc");


?>
<table border="0" cellspacing="0" cellpadding="10">
<tr>
	<td>
        <table border="0" cellspacing="5" cellpadding="0">
        <tr>
                <td align="center" width="60" nowrap>
                <a class="small" href="<?php echo $PHP_SELF; ?>"><img src="<?php echo $GO_THEME->image_url; ?>buttons/addressbook_big.gif" border="0" height="32" width="32" /><br /><?php echo $contacts_contacts; ?></a></td>
                </td>
                <td align="center" width="60" nowrap>
                <a class="small" href="javascript:popup('<?php echo $GO_CONFIG->host; ?>contacts/add_contact.php','750','500')"><img src="<?php echo $GO_THEME->image_url; ?>buttons/add_contact.gif" border="0" height="32" width="32" /><br /><?php echo $cmdAdd; ?></a></td>
                </td>
                <td align="center" width="60" nowrap>
                <a class="small" href="<?php echo $PHP_SELF; ?>?action=groups"><img src="<?php echo $GO_THEME->image_url; ?>buttons/groups.gif" border="0" height="32" width="32" /><br /><?php echo $contacts_groups; ?></a></td>
                </td>
                <td align="center" width="60" nowrap>
                <a class="small" href="<?php echo $PHP_SELF; ?>?action=members"><img src="<?php echo $GO_THEME->image_url; ?>buttons/users.gif" border="0" height="32" width="32" /><br /><?php echo $contacts_members; ?></a></td>
                </td>
                <td align="center" width="60" nowrap>
                <a class="small" href="<?php echo $PHP_SELF; ?>?action=authorise"><img src="<?php echo $GO_THEME->image_url; ?>buttons/authorise.gif" border="0" height="32" width="32" /><br /><?php echo $contacts_authorise; ?></a></td>
                </td>
                <td align="center">
                <a class="small" href="<?php echo $PHP_SELF; ?>?action=import"><img src="<?php echo $GO_THEME->image_url; ?>buttons/import.gif" border="0" height="32" width="32" /><br /><?php echo $contacts_import; ?></a></td>
                </td>
                <td align="center" width="60" nowrap>
                <a class="small" href="<?php echo $PHP_SELF; ?>?action=export"><img src="<?php echo $GO_THEME->image_url; ?>buttons/export.gif" border="0" height="32" width="32" /><br /><?php echo $contacts_export; ?></a></td>
                </td>
                <?php
                if ($action == '' || $action == 'groups')
                {
					echo '<td align="center" width="60" nowrap>';
					echo '<a class="small" href="javascript:confirm_delete()"><img src="'.$GO_THEME->image_url.'buttons/delete_big.gif" border="0" height="32" width="32" /><br />'.$contacts_delete.'</a></td>';
                }
                ?>
        </tr>
        </table>
        <form name="contacts" method="post" action="<?php echo $PHP_SELF; ?>" enctype="multipart/form-data">
        <?php
        switch($action)
        {
                case 'members':
                        require('members.inc');
                break;

                case 'export':
                	require('export.inc');
                break;

				case 'import_data':
					require('import.inc');
				break;

                case 'import':

                	require('import.inc');
                break;

                case 'groups':
					if (isset($task) && $task == 'add_group')
					{
						$name = trim($name);
						if ($name != '')
						{
							if (validate_input($name))
							{
								if($contacts->add_group($GO_SECURITY->user_id,$name))
								{
										$table_tabindex=0;
								}else
								{
										$feedback = "<p class=\"Error\">".$strSaveError."</p>";
								}

							}else
							{
								$feedback = "<p class=\"Error\">".$invalid_input.": \\ / & ? </p>";
							}
						}else
						{
							$feedback = "<p class=\"Error\">".$error_missing_field."</p>";
						}
					}

					//Add the tab names with thier associated documents
					$table_docs[] = "groups.inc";
					$table_tabs[] = $contacts_groups;
					$table_docs[] = "add_group.inc";
					$table_tabs[] = $cmdAdd;

					$table_title = $contacts_groups;
					$table_width = "600";
					$table_height = "300";
					$table_arguments = '&action=groups';
					echo '<input type="hidden" name="action" value="groups" />';
					require($GO_CONFIG->control_path."html_table.inc");

                break;

                case 'authorise':
                	require('authorise.inc');
                break;

                default:
                        require("contacts.inc");
                break;
        }
        ?>
	</td>
</tr>
</table>
</form>
<?php require($GO_THEME->theme_path."footer.inc"); ?>
