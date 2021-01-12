<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

$edit_id = isset($edit_id) ? $edit_id : 0;

//delete accounts if requested
if(isset($groups))
{
	while($delete_group = array_shift($groups))
	{
		$contacts->delete_group($delete_group);
	}
}

if (isset($name) && $task == 'edit')
{
	$contacts->change_group_name($id, $name);
}

?>

<input type="hidden" name="id" value="<?php echo $edit_id; ?>" />
<input type="hidden" name="action" value="<?php echo $action; ?>" />
<input type="hidden" name="task" value="edit" />
<table border="0" cellpadding="10" cellspacing="0">
<tr>
	<td>
	<table border="0" cellpadding="4" cellspacing="0">
	<?php

	$count = $contacts->get_groups($GO_SECURITY->user_id);

	if ($count > 0)
	{
		echo '<tr><td colspan="3"><a class="normal" href="javascript:invert_selection()">'.$cmdInvertSelection.'</a></td></tr>';
        while ($contacts->next_record())
		{
			echo '<tr><td><input type="checkbox" name="groups[]" value="'.$contacts->f('id').'" />';
			if ($contacts->f("id") == $edit_id)
			{
				echo '<td><input type="text" size="25" class="textbox" name="name" value="'.$contacts->f("name").'" /></td>';
			}else
			{
				echo "<td>".$contacts->f("name")."</td>";
			}
			echo '<td><a href="'.$PHP_SELF.'?action=groups&edit_id='.$contacts->f("id").'" title="'.$strEdit.' \''.$contacts->f("name").'\'"><img src="'.$GO_THEME->image_url.'buttons/edit.gif" border="0" /></a></td>';
			echo '</tr>';

		}
	}else
	{
		echo "<tr><td>".$groups_no_groups."</td></tr>";
	}
	?>
	</table>
	</td>
</tr>
<tr>
	<td>
	<?php
    if ($edit_id != 0)
    {
		$button = new button($cmdSave, 'javascript:document.forms[0].submit()');
    	echo '&nbsp;&nbsp;';
    }
    ?>
	</td>
</tr>
</table>
<script type="text/javascript" language="javascript">
function invert_selection()
{
	for (var i=0;i<document.forms[0].elements.length;i++)
	{
		if(document.forms[0].elements[i].type == 'checkbox')
		{
			document.forms[0].elements[i].checked = !(document.forms[0].elements[i].checked);
		}
	}
}

function confirm_delete()
{
	var count = 0;

	for (var i=0;i<document.forms[0].elements.length;i++)
	{
		if(document.forms[0].elements[i].type == 'checkbox')
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
			if (confirm("<?php echo $strDeletePrefix.$contacts_this_group.$strDeleteSuffix; ?>"))
			{

				document.forms[0].submit();
			}
		break;

		default:
			if (confirm("<?php echo $strDeletePrefix.$strThis; ?> "+count+" <?php echo $contacts_groups .$strDeleteSuffix; ?>"))
			{

				document.forms[0].submit();
			}
		break;
	}
}
</script>
