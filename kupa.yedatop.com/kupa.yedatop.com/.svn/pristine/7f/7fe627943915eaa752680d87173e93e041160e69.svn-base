<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

require("../../Group-Office.php");


$GO_SECURITY->authenticate();
$GO_MODULES->authenticate('projects');
require($GO_LANGUAGE->get_language_file('projects'));

$page_title=$menu_projects;
require($GO_CONFIG->class_path."projects.class.inc");
$projects = new projects;

$post_action = isset($post_action) ? $post_action : '';

require($GO_THEME->theme_path."header.inc");


switch($post_action)
{
	case 'add_project':
		$projects_tab = true;
		if ($GO_MODULES->write_permissions)
		{
			if ($REQUEST_METHOD != 'POST')
			{
				$require = 'add_project.inc';
			}elseif ($name == '')
			{
				$feedback = '<p class="Error">'.$error_missing_field.'</p>';
				$require = 'add_project.inc';
			}else
			{
				$acl_read = $GO_SECURITY->get_new_acl('Project read: '.$name);
				$acl_write = $GO_SECURITY->get_new_acl('Project write: '.$name);
				if ($acl_read > 0 && $acl_write > 0)
				{
					if ($GO_SECURITY->add_user_to_acl($GO_SECURITY->user_id, $acl_write))
					{
						if ($projects->add_project($GO_SECURITY->user_id, $name, $comments, $acl_read, $acl_write))
						{
							$require = 'projects.inc';
						}else
						{
							$GO_SECURITY->delete_acl($acl_read);
							$GO_SECURITY->delete_acl($acl_write);
							$feedback = '<p class="Error">'.$strSaveError.'</p>';
							$require = 'add_project.inc';
						}
					}else
					{
						$GO_SECURITY->delete_acl($acl_read);
						$GO_SECURITY->delete_acl($acl_write);
						$feedback = '<p class="Error">'.$strSaveError.'</p>';
						$require = 'add_project.inc';
					}
				}else
				{
					$feedback ='<p class="Error">'.$strAclError.'</p>';
					$require = 'add_project.inc';
				}
			}
			$title = $pm_new_project;
		}else
		{
			$title = $strAccessDenied;
			$require = $GO_CONFIG->root_path.'error_docs/401.inc';
		}
	break;

	case 'save_hours':
		$book_tab = true;

		$start_time = mktime($start_hour, $start_min, 0, $month, $day, $year);
		$end_time = mktime($end_hour, $end_min, 0, $month, $day, $year);

		if ($end_time < $start_time)
		{
			$feedback = '<p class="Error">'.$pm_invalid_period.'</p>';

		}elseif(!$projects->check_hours($pm_user_id, $start_time, $end_time))
		{
			$feedback = '<p class="Error">'.$pm_already_booked.'</p>';
		}else
		{
			$break_time = ($break_hours*3600)+($break_mins*60);
			if (!$projects->add_hours($project_id, $pm_user_id, $start_time, $end_time, $break_time, $comments))
			{
				$feedback = '<p class="Error">'.$strSaveError.'</p>';
			}else
			{
				$feedback = '<p class="Success">'.$pm_add_hours_success.'</p>';
			}
		}
		$require = 'book.inc';


		$time = time();
		$today = date("j", $time);
		$this_year = date("Y", $time);
		$this_month = date("m", $time);

		$day = isset($day) ? $day : $today;
		$month = isset($month) ? $month : $this_month;
		$year = isset($year) ? $year : $this_year;

		$date_format = $ses_date_format;
		$date_format = str_replace(" H:i", "",$ses_date_format);
		$date = date($date_format, mktime(0,0,0,$month,$day,$year));
		$title = $pm_new_entry.' '.$full_days[date("w", $time)].' '.$date;
	break;

	case 'projects':
		if (isset($delete_project))
		{
			$project = $projects->get_project($delete_project);
			if ($project['user_id'] == $GO_SECURITY->user_id)
			{
				$projects->delete_project($delete_project);
			}else
			{
				$feedback = '<p class="Error">'.$strAccessDenied.'</p>';
			}

		}
		$projects_tab = true;
		$require = 'projects.inc';
		$title = $lang_modules['projects'];
	break;

	case 'project':
		$projects_tab = true;
		$require = 'project.inc';
		$title = $strProperties;

		if ($REQUEST_METHOD == 'POST')
		{
			if ($name != '')
			{
				$projects->update_project($project_id, $name, $comments);
			}else
			{
				$feedback = '<p class="Error">'.$error_missing_field.'</p>';
			}
		}
	break;

	case 'load_data';
		$project = $projects->get_project($project_id);
		$title = $pm_project_data." '".$project['name']."'";
		$projects_tab = true;
		$require = 'project_data.inc';
	break;

	case 'fees':
		if(isset($delete_fee))
		{
			$projects->delete_fee($delete_fee);
		}
		$title = $pm_fees;
		$fees_tab = true;
		$require = 'fees.inc';
	break;

	case 'fee':
		$fees_tab = true;
		if($GO_MODULES->write_permissions)
		{
			if ($REQUEST_METHOD == 'POST')
			{
				$value = str_replace(',','.',$value);

				if ($name == '' || $value == '')
				{
					$feedback = '<p class="Error">'.$error_missing_field.'</p>';
				}else
				{
					if (isset($fee_id))
					{
						if ($projects->update_fee($fee_id, $name, $value))
						{
							$feedback = '<p class="Success">'.$strSaveSuccess.'</p>';
						}else
						{
							$feedback = '<p class="Error">'.$strSaveError.'</p>';
						}
					}else
					{
						if($projects->add_fee($name, $value))
						{

							$feedback = '<p class="Success">'.$strSaveSuccess.'</p>';
						}else
						{
							$feedback = '<p class="Error">'.$strSaveError.'</p>';
						}
					}
				}
				$title = $pm_fees;
				$require='fees.inc';
			}else
			{
				$title = $pm_new_fee;
				$require='fee.inc';
			}
		}else
		{
			$title = $strAccessDenied;
			$require = $GO_CONFIG->root_path.'error_docs/403.inc';
		}
	break;

	case 'users':
		$users_tab = true;
		$title = $pm_users;
		$require = 'users.inc';
	break;

	case 'materials':
		if(isset($delete_material))
		{
			$projects->delete_material($delete_material);
		}
		$materials_tab = true;
		$title = $pm_materials;
		$require = 'materials.inc';
	break;

	case 'material':
		$materials_tab = true;
		if ($GO_MODULES->write_permissions)
		{
			if ($REQUEST_METHOD == 'POST')
			{
				$value = str_replace(',','.',$value);

				if ($name == '' || $value == '')
				{
					$feedback = '<p class="Error">'.$error_missing_field.'</p>';
				}else
				{
					if (isset($material_id))
					{
						if ($projects->update_material($material_id, $name, $value, $description))
						{
							$feedback = '<p class="Success">'.$strSaveSuccess.'</p>';
						}else
						{
							$feedback = '<p class="Error">'.$strSaveError.'</p>';
						}
					}else
					{
						if($projects->add_material($name, $value, $description))
						{

							$feedback = '<p class="Success">'.$strSaveSuccess.'</p>';
						}else
						{
							$feedback = '<p class="Error">'.$strSaveError.'</p>';
						}
					}
				}
				$title = $pm_materials;
				$require = 'materials.inc';
			}else
			{

				$title = $pm_new_material;
				$require = 'material.inc';
			}
		}else
		{
			$title = $strAccessDenied;
			$require = $GO_CONFIG->root_path.'error_docs/403.inc';
		}
	break;

	default:
		$book_tab = true;
		$require = 'book.inc';

		if (isset($delete_hours))
		{
			$projects->delete_hours($delete_hours);
		}
		$time = time();
		$today = date("j", $time);
		$this_year = date("Y", $time);
		$this_month = date("m", $time);

		$day = isset($day) ? $day : $today;
		$month = isset($month) ? $month : $this_month;
		$year = isset($year) ? $year : $this_year;

		$date_format = $ses_date_format;
		$date_format = str_replace(" H:i", "",$ses_date_format);
		$new_time = mktime(0,0,0,$month,$day,$year);
		$date = date($date_format, $new_time);
		$title = $pm_new_entry.' '.$full_days[date("w", $new_time)].' '.$date;
	break;
}
?>
<table border="0" cellpadding="10" cellspacing="0">
<tr>
	<td>
	<table border="0" cellpadding="2" cellspacing="0">
	<tr>
		<td valign="top">
		<table border="0" cellpadding="0" cellspacing="0" class="TableBorder" width="750">
		<tr>
			<td valign="top">
			<table border="0" cellpadding="1" cellspacing="1" width="100%">
			<tr>
				<td colspan="99" class="TableHead"><?php echo $title; ?></td>
			</tr>
			<tr>
				<td class="<?php if(isset($book_tab)) echo 'ActiveTab'; else echo 'Tab'; ?>" align="center" width="100"><a href="<?php echo $PHP_SELF; ?>?post_action=book" class="<?php if(isset($book_tab)) echo 'ActiveTab'; else echo 'Tab'; ?>"><?php echo $pm_enter_data; ?></a></td>
				<td class="<?php if(isset($users_tab)) echo 'ActiveTab'; else echo 'Tab'; ?>" align="center" width="100"><a href="<?php echo $PHP_SELF; ?>?post_action=users" class="<?php if(isset($users_tab)) echo 'ActiveTab'; else echo 'Tab'; ?>"><?php echo $pm_load; ?></a></td>
				<td class="<?php if(isset($projects_tab)) echo 'ActiveTab'; else echo 'Tab'; ?>" align="center" width="100"><a href="<?php echo $PHP_SELF; ?>?post_action=projects" class="<?php if(isset($projects_tab)) echo 'ActiveTab'; else echo 'Tab'; ?>"><?php echo $lang_modules['projects']; ?></a></td>
				<td class="<?php if(isset($fees_tab)) echo 'ActiveTab'; else echo 'Tab'; ?>" align="center" width="100"><a href="<?php echo $PHP_SELF; ?>?post_action=fees" class="<?php if(isset($fees_tab)) echo 'ActiveTab'; else echo 'Tab'; ?>"><?php echo $pm_fees; ?></a></td>
				<td class="<?php if(isset($materials_tab)) echo 'ActiveTab'; else echo 'Tab'; ?>" align="center" width="100"><a href="<?php echo $PHP_SELF; ?>?post_action=materials" class="<?php if(isset($materials_tab)) echo 'ActiveTab'; else echo 'Tab'; ?>"><?php echo $pm_materials; ?></a></td>
				<td class="Tab" width="450">&nbsp;</td>
			</tr>
			<tr>
				<td class="TableInside" height="300" valign="top" colspan="6">
				<table border="0" cellpadding="10">
				<tr>
					<td>
					<?php
					if (isset($feedback)) echo $feedback;
					require($require);
					?>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
<?php
require($GO_THEME->theme_path."footer.inc");
?>
