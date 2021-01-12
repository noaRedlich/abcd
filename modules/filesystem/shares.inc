<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/
?>

<table border="0" cellpadding="0" cellspacing="0" class="TableBorder" width="400">
<tr>
	<td valign="top">
	<table border="0" cellpadding="1" cellspacing="1" width="100%">
	<tr>
		<td colspan="99" class="TableHead"><?php echo $fbShares; ?></td>
	</tr>
	<tr>
		<td class="TableInside">
		<table border="0" cellpadding="4" cellspacing="0">
		<tr>
			<td colspan="2"><?php if(isset($feedback)) echo $feedback; ?>&nbsp;</td>
		</tr>
		<tr>
			<td>
			<table border="0" cellpadding="4" cellspacing="0">
			<?php
			if (isset($delete_share))
			{
				$fs->delete_share(smartstrip($delete_share));
			}
			$count = $fs->get_shares($GO_SECURITY->user_id);

			while($fs->next_record())
			{
				if (file_exists($fs->f('path')))
				{
					$display_path = '/'.str_replace($GO_CONFIG->file_storage_path,'',$fs->f('path'));
					echo '<tr><td><img width="16" height="16" border="0" src="'.$GO_THEME->image_url.'folder.gif" /></td>';
					echo '<td><a href="'.$PHP_SELF.'?task=properties&path='.urlencode($fs->f('path')).'">'.$display_path.'</a></td>';
					echo "<td><a href='javascript:confirm_action(\"".$PHP_SELF."?task=shares&path=".urlencode($path)."&delete_share=".urlencode($fs->f('path'))."\",\"".rawurlencode($strDeletePrefix."'".$display_path."'".$strDeleteSuffix)."\")' title=\"".$strDeleteItem." '".$display_path."'\"><img src=\"".$GO_THEME->image_url."buttons/delete.gif\" border=\"0\"></a></td></tr>\n";
				}else
				{
					$fs2 = new filesystem();
					$fs2->delete_share($fs->f('path'));
					$count--;
				}
			}
			$text = ($count == 1) ? $fbShare : $fbShares;
			echo '<tr><td colspan="2"><i>'.$count.' '.$text.'</td></tr>';
			?>
			</table>
			</td>
		</tr>
		<tr>
			<td colspan="2">
			<br />
			<?php
			$button = new button($cmdBack, "javascript:document.location='".$PHP_SELF."?path=".$urlencoded_path."';");
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