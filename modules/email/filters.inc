<table border="0" cellpadding="0" cellspacing="8" class="normal">
<tr>
        <td>
<?php
if (isset($delete_filter))
{
	$email->delete_filter($delete_filter);
}

if (isset($move_up_id) && $move_dn_id != 0)
{
	$email->move_up($move_up_id, $move_dn_id, $move_up_pr, $move_dn_pr);
}

$count = $email->get_filters($email_id);

if ($count>0)
{
	echo '<table border="0" cellpadding="2" cellspacing="0"><tr>';
	echo '<td><h3>'.$ml_field.'</h3></td>';
	echo '<td><h3>'.$ml_contains.'</h3></td>';
	echo '<td><h3>'.$ml_folder.'</h3></td>';
	echo '<td>&nbsp;</td>';
	echo '<td>&nbsp;</td>';
	echo '<td>&nbsp;</td></tr>';

	$last_id  = 0;
	$last_pr = 0;

	while($email->next_record())
	{
		switch($email->f("field"))
		{
			case "sender":
				$field = "E-mail";
			break;

			case "subject":
				$field = $ml_subject;
			break;

			case "to";
				$field = $ml_to;
			break;

			default:
				$field = $email->f("field");
			break;
		}

		echo '<tr height="18"><td>'.$field.'&nbsp;&nbsp;</td>';
		echo '<td>'.$email->f("keyword").'&nbsp;&nbsp;</td>';
		echo '<td>'.$email->f("folder").'</td>';
		echo "<td>&nbsp;<a href=\"".$PHP_SELF."?task=filters&move_up_id=".$email->f("id")."&move_dn_id=".$last_id."&move_dn_pr=".$email->f("priority")."&move_up_pr=".$last_pr."\" title=\"".$ml_move_up."\"><img src=\"".$GO_THEME->image_url."buttons/up.gif\" border=\"0\"></a></td>";
		echo "<td>&nbsp;<a href='javascript:confirm_action(\"".$PHP_SELF."?task=filters&delete_filter=".$email->f("id")."\",\"".rawurlencode($ml_delete_filter)."\")' title=\"".$ml_delete_filter1."\"><img src=\"".$GO_THEME->image_url."buttons/delete.gif\" border=\"0\"></a></td>";
		echo '</tr>';
		$last_id = $email->f("id");
		$last_pr = $email->f("priority");
	}
	echo "</table>";
}else
{
	echo $ml_no_filters;
}

?>
        </td>
</tr>
</table>
