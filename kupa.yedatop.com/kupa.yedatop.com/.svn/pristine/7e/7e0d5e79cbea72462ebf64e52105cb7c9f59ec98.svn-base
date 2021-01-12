<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

if ($GO_MODULES->write_permissions)
{
	echo '<a href="'.$PHP_SELF.'?post_action=add_project" class="normal">'.$pm_new_project.'</a><br /><br />';
}
$count = $projects->get_projects();
echo '<table border="0" cellspacing="0" cellpadding="4">';
echo '<tr><td><h3>'.$strName.'&nbsp;&nbsp;&nbsp;</h3></td>';
echo '<td><h3>'.$strOwner.'</h3></td></tr>';
if ($count > 0)
{
	while ($projects->next_record())
	{
		if ($GO_SECURITY->has_permission($GO_SECURITY->user_id, $projects->f('acl_read')) || $GO_SECURITY->has_permission($GO_SECURITY->user_id, $projects->f('acl_write')))
		{
			echo '<tr><td><a href="'.$PHP_SELF.'?post_action=project&project_id='.$projects->f('id').'" class="normal">'.$projects->f('name').'</a>&nbsp;&nbsp;&nbsp;</td><td>'.show_profile($projects->f('user_id')).'&nbsp;&nbsp;&nbsp;</td>';
			echo "<td><a href='javascript:confirm_action(\"".$PHP_SELF."?post_action=projects&delete_project=".$projects->f("id")."\",\"".rawurlencode($strDeletePrefix."'".$projects->f("name")."'".$strDeleteSuffix)."\")' title=\"".$strDeleteItem." '".$projects->f("name")."'\"><img src=\"".$GO_THEME->image_url."buttons/delete.gif\" border=\"0\"></a></td>\n";
		}else
		{
			$count--;
		}
	}
}

echo '<tr><td colspan="2"><i>'.$count.' '.$pm_projects_count.'</i></td></tr></table>';
?>
