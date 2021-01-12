<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0  Release date: 14 March 2003									//
// 			1.01 Release date:  8 April 2003									//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

if (isset($filetype_reload))
{
	session_unregister("filetype_reload");
	echo "<script type=\"text/javascript\">\ndocument.location.reload();\n</script>";
	exit();
}
if ($REQUEST_METHOD == "POST")
{
	$filetypes->update_filetype($extension, $friendly);
}
$filetype = $filetypes->get_type($extension);

if ($REQUEST_METHOD == "POST")
{
 	if (isset($uploaded_file) && is_uploaded_file($uploaded_file))
	{
		if (eregi("gif", $uploaded_file_type))
		{
			$filetype = $filetypes->get_type($extension);
			$filetypes->update_filetype($extension, $filetype['friendly'], $uploaded_file);
			$filetype_reload = true;
			session_register('filetype_reload');
			echo "<script type=\"text/javascript\">\ndocument.location='".$PHP_SELF."?task=filetype&extension=".$extension."&first=".$first."';\n</script>";
			exit();
		}
	}
}
?>
<form method="post" action="<?php echo $PHP_SELF; ?>" enctype="multipart/form-data">
<input type="hidden" name="task" value="filetype" />
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $GO_CONFIG->max_file_size; ?>" />
<input type="hidden" name="extension" value="<?php echo $extension; ?>" />
<input type="hidden" name="first" value="<?php echo $first; ?>" />

<table border="0" cellpadding="10" cellspacing="0">
<tr>
	<td>
	<table border="0" cellpadding="2" cellspacing="0">
	<tr>
		<td valign="top">
		<table border="0" cellpadding="0" cellspacing="0" class="TableBorder" width="600">
		<tr>
			<td valign="top">
			<table border="0" cellpadding="1" cellspacing="1" width="100%">
			<tr>
				<td colspan="99" class="TableHead"><?php echo $ft_properties; ?></td>
			</tr>
			<tr>
				<td class="TableInside" height="300" valign="top">
				<table border="0" cellpadding="10">
				<tr>
					<td>
					<table  border="0" cellspacing="5" cellpadding="0">
					<tr>
						<td>
						<?php if (isset($feedback)) echo $feedback; ?>
						<table border="0" cellpadding="4" cellspacing="0">
						<tr>
							<td><?php echo $ft_extension; ?>:&nbsp;&nbsp;</td>
							<td><?php echo $extension; ?></td>

						</tr>
						<tr>
							<td><?php echo $ft_content; ?>:&nbsp;&nbsp;</td>
							<td><?php echo $filetype['mime']; ?></td>
						</tr>
						<tr>
							<td><?php echo $ft_friendly; ?>:&nbsp;&nbsp;</td>
							<td><input size="40" type="text" class="textbox" name="friendly" value="<?php echo $filetype['friendly']; ?>" /></td>
						</tr>
						<tr>
							<td><?php echo $ft_icon; ?>:&nbsp;&nbsp;</td>
							<td>
								<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td id="status" colspan="2" class="Success">
									<img src="icon.php?extension=<?php echo $extension; ?>" width="16" height="16" border="0"/>&nbsp;&nbsp;
									</td>
									<td><input class="textbox" type="file" name="uploaded_file" maxlength="100" /></td>
								</tr>
								</table>
							</td>
						</tr>
						</table>
						</td>
					</tr>

					<tr>
							<td colspan="2">
							<br />
							<?php
							$button = new button($cmdSave, 'javascript:upload();');
							echo '&nbsp;&nbsp;';
							$button = new button($cmdBack, 'javascript:document.location=\'index.php?&first='.$first.'\';');
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
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
</form>
<script type="text/javascript">
<!--
	function upload()
	{
		if (document.forms[0].uploaded_file.value != '')
		{
			var status = null;
			if (status = get_object("status"))
			{
				status.innerHTML = "<?php echo $ft_please_wait; ?>";
			}
		}
		document.forms[0].submit();
	}
-->
</script>