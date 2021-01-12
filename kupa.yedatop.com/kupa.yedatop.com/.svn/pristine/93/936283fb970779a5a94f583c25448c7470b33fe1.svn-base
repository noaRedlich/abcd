<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

$count = $projects->get_materials();
if ($GO_MODULES->write_permissions)
{
	echo '<a href="'.$PHP_SELF.'?post_action=material" class="normal">'.$pm_new_material.'</a><br /><br />';
}
echo '<table border="0" cellpadding="4" cellspacing="0">';
echo '<tr><td><h3>'.$strName.'&nbsp;&nbsp;&nbsp;</h3></td>';
echo '<td><h3>'.$pm_value.'</h3></td>';
echo '<td><h3>'.$strComments.'</h3></td><td></td></tr>';

$class = 'Table1';
while ($projects->next_record())
{
	echo '<tr class="'.$class.'">';

	if ($GO_MODULES->write_permissions)
	{
		echo '<td><a href="'.$PHP_SELF.'?post_action=material&material_id='.$projects->f('id').'" class="normal">'.$projects->f('name').'</a>&nbsp;&nbsp;&nbsp;</td><td>'.number_format($projects->f('value'), 2, $ses_decimal_seperator,$ses_thousands_seperator).'&nbsp;&nbsp;&nbsp;</td>';
		echo '<td>'.text_to_html($projects->f('description')).'</td>';
		echo "<td><a href='javascript:confirm_action(\"".$PHP_SELF."?post_action=materials&delete_material=".$projects->f("id")."\",\"".rawurlencode($strDeletePrefix.$projects->f("name").$strDeleteSuffix)."\")' title=\"".$strDeleteItem." '".$projects->f("name")."'\"><img src=\"".$GO_THEME->image_url."buttons/delete.gif\" border=\"0\"></a></td></tr>\n";
	}else
	{
		echo '<td>'.$projects->f('name').'&nbsp;&nbsp;&nbsp;</td><td>'.number_format($projects->f('value'), 2, $ses_decimal_seperator,$ses_thousands_seperator).'&nbsp;&nbsp;&nbsp;</td>';
		echo '<td>'.text_to_html($projects->f('description')).'</td>';
		echo '<td></td></tr>';
	}
	if ($class != 'Table2') $class='Table2'; else $class='Table1';

}
echo '<tr><td colspan="2"><i>'.$count.' '.$pm_materials.'</i></td></tr></table>';
?>