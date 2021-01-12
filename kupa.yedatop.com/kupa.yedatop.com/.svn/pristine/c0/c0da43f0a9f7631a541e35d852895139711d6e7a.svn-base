<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

require("../Group-Office.php");
$GO_SECURITY->authenticate();
require($GO_LANGUAGE->get_language_file('groups'));
//load group management class
require($GO_CONFIG->class_path."groups.class.inc");
$groups = new groups;

//load contact management class
require($GO_CONFIG->class_path."users.class.inc");
$users = new users; 
$page_title = $groups_title;
require($GO_THEME->theme_path."header.inc");


//Add the tab names with thier associated documents
$table_docs[] = "groups.inc";
$table_tabs[] = $groups_tab;
$table_docs[] = "add_group.inc";
$table_tabs[] = $cmdAdd;

$table_title = $groups_title;
$table_width = "600";
$table_height = "300";
?>
<table border="0" cellspacing="0" cellpadding="15">
<tr>
	<td>
	<?php require($GO_CONFIG->control_path."html_table.inc"); ?>
	</td>
</tr>
</table>
<?php require($GO_THEME->theme_path."footer.inc"); ?>