<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

$filename = basename($path);
$extension = get_file_extension($filename);
$type = $filetypes->get_type($extension);
$location = dirname($path);
$share = $fs->get_share($path);
$write_perms = $fs->has_write_permission($GO_SECURITY->user_id, $path);
?>
<input type="hidden" name="task" value="properties" />

<table border="0" cellpadding="0" cellspacing="0" class="TableBorder" width="400">
<tr>
	<td valign="top">
	<table border="0" cellpadding="1" cellspacing="1" width="100%">
	<tr>
		<td colspan="99" class="TableHead"><?php echo $fbProperties; ?></td>
	</tr>
	<?php
	if ($share)
	{
		echo '<tr><td class="ActiveTab" align="center">'.$fbGeneral.'</td>';
		echo '<td class="Tab" align="center"><a href="'.$PHP_SELF.'?task=read_permissions&path='.$urlencoded_path.'" class="Tab" />'.$strReadRights.'</a></td>';
		echo '<td class="Tab" align="center"><a href="'.$PHP_SELF.'?task=write_permissions&path='.$urlencoded_path.'" class="Tab" />'.$strWriteRights.'</a></td></tr>';
	}
	?>
	<tr>
		<td class="TableInside" colspan="99" width="100%" height="300" valign="top">
		<table border="0" cellpadding="4" cellspacing="0" align="center">
		<tr>
			<td colspan="2"><?php if(isset($feedback)) echo $feedback; ?>&nbsp;</td>
		</tr>
		<tr>
			<td>
			<?php echo $strName; ?>:
			</td>
			<td>
			<?php
			if ($write_perms)
			{
				echo '<input type="text" class="textbox" name="name" value="'.basename($path).'" maxlength="100" size="30" />';
			}else
			{
				echo basename($path);
			}
			?>
			</td>
		</tr>
		<tr>
			<td>
			<?php echo $fbLocation; ?>:
			</td>
			<td>
			<?php
			if (dirname($GO_CONFIG->file_storage_path).$GO_CONFIG->slash.basename($GO_CONFIG->file_storage_path) == $location)
			{
				echo $GO_CONFIG->slash;
			}else
			{
				echo str_replace($GO_CONFIG->file_storage_path,$GO_CONFIG->slash,$location);
			}
			?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><hr /></td>
		</tr>
		<tr>
			<td valign="top">
			<?php echo $strType; ?>:
			</td>
			<td>
			<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td valign="top">
				<?php
				if (is_dir($path))
				{
					echo '<img border="0" width="16" height="16" src="'.$GO_THEME->image_url.'folder.gif" />';
					echo '&nbsp;</td><td valign="top">';
					echo $fbFolder;
				}else
				{
					echo '<img border="0" width="16" height="16" src="'.$GO_CONFIG->host.'filetypes/icon.php?extension='.$extension.'" />';
					echo '&nbsp;</td><td valign="top">';
					echo $type['friendly'];
					if (($type['mime'] != $type['friendly']) && $type['mime'] != '')
					{
						echo '<br />('.$type['mime'].')';
					}
				}
				?>
				</td>
			</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td><?php echo $strSize; ?>:</td>
			<td><?php
			if (is_dir($path) && !isset($calc_size))
			{
				echo '<a href="'.$PHP_SELF.'?task=properties&path='.$urlencoded_path.'&calc_size=true" class="normal">'.$fs_calculate_folder_size.'</a>';
			}else
			{
				echo format_size($fs->size($path));
			}
			?></td>
		</tr>
		<tr>
			<td colspan="2"><hr /></td>
		</tr>
		<tr>
			<td>
			<?php echo $strCreated; ?>:
			</td>
			<td>
			<?php echo date($ses_date_format, filectime($path)); ?>
			</td>
		</tr>
		<tr>
			<td>
			<?php echo $strAccessed; ?>:
			</td>
			<td>
			<?php echo date($ses_date_format, fileatime($path)); ?>
			</td>
		</tr>
		<tr>
			<td>
			<?php echo $strModified; ?>:
			</td>
			<td>
			<?php echo date($ses_date_format, filemtime($path)); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><hr /></td>
		</tr>
		<?php
		$disabled = $fs->is_owner($GO_SECURITY->user_id, $path) ? '' : 'disabled' ;
		$check = $share ? 'checked' : '';
		echo '<tr><td colspan="2"><input type="checkbox" name="share_folder" value="'.$path.'" '.$check.' '.$disabled.' />'.$fs_activate_sharing.'</td></tr>';
		?>
		<tr>
			<td colspan="2" align="center">
				<br />
				<?php
				if ($write_perms)
				{
					$button = new button($cmdApply, 'javascript:document.forms[0].submit()');
					echo '&nbsp;&nbsp;';
				}
				$return_path = is_dir($path) ? $path : $location;
				$button = new button($cmdBack, 'javascript:document.location=\''.$PHP_SELF.'?path='.urlencode($return_path).'\';')
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

