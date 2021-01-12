<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/
?>
<input type="hidden" name="task" value="upload" />
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $GO_CONFIG->max_file_size; ?>" />

<table border="0" cellpadding="0" cellspacing="0" class="TableBorder" width="400">
<tr>
	<td valign="top">
	<table border="0" cellpadding="1" cellspacing="1" width="100%">
	<tr>
		<td colspan="99" class="TableHead"><?php echo $fbUpload; ?></td>
	</tr>
	<tr>
		<td class="TableInside">
		<table border="0" cellpadding="4" cellspacing="0">
		<?php if(isset($feedback)) echo '<tr><td>'.$feedback.'</td></tr>';
		?>
		<tr>
			<td>
			<?php echo '<h3>'.$fbPath.': '.$page_title.'</3><br /><br />'.$fbFiles; ?>
			:<br /><br />
			<input type="file" name="file[]" maxlength="100" class="textbox" /><br />
			<input type="file" name="file[]" maxlength="100" class="textbox" /><br />
			<input type="file" name="file[]" maxlength="100" class="textbox" /><br />
			<input type="file" name="file[]" maxlength="100" class="textbox" /><br />
			<input type="file" name="file[]" maxlength="100" class="textbox" />
			</td>
		</tr>
		<tr>
			<td>
			<?php
			echo $fbMaxUploadSize.": ".format_size($GO_CONFIG->max_file_size)." (".number_format($GO_CONFIG->max_file_size, 0, $ses_decimal_seperator, $ses_thousands_seperator)." bytes)";
			?>
			</td>
		</tr>
		<tr>
			<td id="status" class="Success">&nbsp;</td>
		</tr>
		<tr>
			<td>
				<br />
				<?php
				$button = new button($cmdOk, "javascript:upload()");
				echo '&nbsp;&nbsp;';
				$button = new button($cmdCancel, "javascript:document.location='".$PHP_SELF."?path=".$urlencoded_path."';");
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
<script type="text/javascript">
<!--
function upload()
{
	var status = null;
	if (status = get_object("status"))
	{
		status.innerHTML = "<?php echo $fbPleaseWait; ?>";
	}
	document.forms[0].submit();
}
-->
</script>