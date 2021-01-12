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

//load user management class
require($GO_CONFIG->class_path."groups.class.inc");
$groups = new groups;



$count = $groups->get_users_in_group($group_id);
$page_title= $count.' '.$groups_users_in1.' '.$name;
require($GO_THEME->theme_path."simple_header.inc");
echo '<table border="0" cellpadding="10"><tr><td>';
echo '<table border="0" cellpadding="0" cellspacing="1">';
echo '<tr><td><h1>'.$page_title.'</h1></td></tr>';

if ($count>0)
{
        while ($groups->next_record())
        {
		echo "<tr height=\"18\">\n";
		echo "<td>".show_profile($groups->f("id"),$groups->f("name"))."&nbsp;</a></td>\n";
		echo "</tr>\n";
	}
}else
{
        echo "<tr><td colspan=\"99\">".$groups_no_users."</td></tr>";
}
echo '<tr><td><br />';
$button = new button($cmdClose, 'javascript:window.close()');
echo '</td></tr>';
echo "</table></td></tr></table>";
require($GO_THEME->theme_path."simple_footer.inc");
?>
