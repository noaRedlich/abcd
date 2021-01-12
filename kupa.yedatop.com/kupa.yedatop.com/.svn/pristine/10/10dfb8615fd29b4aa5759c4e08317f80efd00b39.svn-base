<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

if ($task == 'overwrite')
{
	$task = 'paste';
}
echo '<input type="hidden" value="'.$task.'" name="task" />';
echo '<input type="hidden" name="overwrite_destination_path" value="'.$overwrite_destination_path.'" />';
echo '<input type="hidden" name="overwrite_source_path" value="'.$overwrite_source_path.'" />';
echo '<input type="hidden" name="overwrite" value="false" />';
?>
<table border="0" cellspacing="0" cellpadding="4">
<tr>
	<td><img src="<?php echo $GO_THEME->image_url; ?>questionmark.gif" border="0" /></td><td><h2><?php echo $fbConfirmOverwrite; ?></h2></td>
</tr>
<tr>
	<td colspan="2">
	<?php echo $strOverwritePrefix."'".basename($overwrite_destination_path)."'".$strOverwriteSuffix; ?>
	</td>
</tr>
<tr>
	<td colspan="2" class="cmd">
	<br />
	<?php
	$button = new button($cmdOk,'javascript:overwrite_file(true);');
	echo '&nbsp;&nbsp;';
	$button = new button($cmdCancel,'javascript:overwrite_file(false);');
	?>
	</td>
</tr>
</table>

<script type="text/javascript" language="javascript">
function overwrite_file(overwrite)
{
	if (overwrite)
	{
		document.forms[0].overwrite.value = "true";
	}
	document.forms[0].submit();
}
</script>