<table border="0" cellpadding="10">
<tr>
	<td>
	<table border="0" cellpadding="3" cellspacing="0">
	<?php
	if (isset($feedback))
	{
		echo '<tr><td colspan="5" height="25">'.$feedback.'</td></tr>';
	}

	echo '<tr><td><h3>'.$strName.'</td><td><h3>'.$sc_owner.'</h3></td><td><h3>'.$sc_subscribed.'</h3></td><td>&nbsp;</td><td>&nbsp;</td></tr>';
	$scheduler_count = $scheduler->get_schedulers();
	if ($scheduler_count > 0)
	{
		while ($scheduler->next_record())
		{
			$read_perm = $GO_SECURITY->has_permission($GO_SECURITY->user_id, $scheduler->f("acl_read"));
			$write_perm = $GO_SECURITY->has_permission($GO_SECURITY->user_id, $scheduler->f("acl_write"));
			if ($read_perm || $write_perm)
			{
				$scheduler1= new scheduler;
				if($scheduler1->is_subscribed($GO_SECURITY->user_id, $scheduler->f("id")))
					$checked = "checked";
				else
					$checked = "";

				echo '<tr><td><a href="javascript:change_scheduler('.$scheduler->f("id").')" class="normal">'.$scheduler->f("name").'</a>&nbsp;</td>';
				echo '<td>'.show_profile($scheduler->f("user_id")).'&nbsp;</td>';
				echo '<td align="center">&nbsp;<input type="checkbox" name="subscribed[]" value="'.$scheduler->f("id").'" '.$checked.' /></td>';
				if ($write_perm)
				{
					echo '<td>&nbsp;<a href="javascript:load_scheduler('.$scheduler->f("id").')" title="'.$strEdit.' \''.$scheduler->f("name").'\'"><img src="'.$GO_THEME->image_url.'buttons/edit.gif" border="0" /></a></td>';
					echo "<td>&nbsp;<a href='javascript:delete_scheduler(\"".$scheduler->f("id")."\",\"".rawurlencode($strDeletePrefix."'".$scheduler->f("name")."'".$strDeleteSuffix)."\")' title=\"".$strDeleteItem." '".$scheduler->f("name")."'\"><img src=\"".$GO_THEME->image_url."buttons/delete.gif\" border=\"0\"></a></td></tr>\n";
				}else
				{
					echo '<td>&nbsp;</td><td>&nbsp;</td>';
				}

			}else
			{
				$scheduler_count--;
			}
		}
	}
	echo '</table>';
	$button = new button($cmdSave, 'javascript:subscribe()');
	?>
	</td>
</tr>
</table>