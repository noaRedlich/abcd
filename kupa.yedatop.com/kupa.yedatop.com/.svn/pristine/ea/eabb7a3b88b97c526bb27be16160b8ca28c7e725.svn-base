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
$is_owner = $fs->is_owner($GO_SECURITY->user_id, $path);
?>
<input type="hidden" name="task" value="read_permissions" />
<input type="hidden" name="next_task" value="properties" />
<input type="hidden" name="share_all" />
<table border="0" cellpadding="0" cellspacing="0" class="TableBorder" width="400">
<tr>
	<td valign="top">
	<table border="0" cellpadding="1" cellspacing="1" width="100%">
	<tr>
		<td colspan="99" class="TableHead"><?php echo $strReadRights; ?></td>
	</tr>
	<?php
	if ($share)
	{
		echo '<tr><td class="Tab" align="center"><a href="'.$PHP_SELF.'?task=properties&path='.$urlencoded_path.'" class="Tab" />'.$fbGeneral.'</a></td>';
		echo '<td class="ActiveTab" align="center">'.$strReadRights.'</td>';
		echo '<td class="Tab" align="center"><a href="'.$PHP_SELF.'?task=write_permissions&path='.$urlencoded_path.'" class="Tab" />'.$strWriteRights.'</a></td></tr>';
	}
	?>
	<tr>
		<td class="TableInside" colspan="99" width="100%" height="300" valign="top">
		<table border="0" width="100%">
		<tr>
			<td>
			<br />
			<?php
				$acl_control_read_only = !$is_owner ;
				$acl_control_id="write";
				$acl_control_acl_id = $share["acl_read"];
				$acl_control_hide_self = $is_owner;
				require($GO_CONFIG->control_path."acl_control.inc");
			?>

			</td>
		</tr>
		<tr>
			<td class="cmd" colspan="2" align="center">
				<br />

				<?php
				if ($is_owner)
				{
					$button = new button($cmdApply, 'javascript:document.forms[0].submit()');
					echo '&nbsp;&nbsp;';
				}
				$return_path = is_dir($path) ? $path : $location;
				$button = new button($cmdBack, 'javascript:document.location=\''.$PHP_SELF.'?path='.urlencode($return_path).'\';');
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
