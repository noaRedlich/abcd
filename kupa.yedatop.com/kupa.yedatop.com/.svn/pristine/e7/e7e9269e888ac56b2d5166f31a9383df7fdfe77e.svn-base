<input type="hidden" name="action" value="export" />
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
				<td colspan="99" class="TableHead"><?php echo $contacts_export; ?></td>
			</tr>
			<tr>
				<td class="TableInside" height="300" valign="top">
				<table border="0" cellpadding="10">
				<tr>
					<td>

					<table border="0" cellpadding="5" cellspacing="0" align="center">
					<tr>
							<td><?php echo $contacts_export_text; ?></td>
					</tr>
					<tr>
							<td>
							<?php
							$separator = isset($separator) ? $separator : ';';
							$quote = isset($quote) ? $quote : '&quot;';
							$crlf = isset($crlf) ? $crlf : '\r\n';
							?>
							<table border="0"  cellpadding="0" cellspacing="3">
							<tr>
								<td><?php echo $contacts_seperator_text; ?>:</td>
								<td><input class="textbox" type="text" size="3" name="separator" value="<?php echo $separator; ?>" /></td>
							</tr>
							<tr>
								<td><?php echo $contacts_quote; ?>:</td>
								<td><input class="textbox" type="text" size="3" name="quote" value="<?php echo $quote; ?>" /></td>
							</tr>
							<tr>
								<td><?php echo $contacts_clrf; ?>:</td>
								<td><input class="textbox" type="text" size="3" name="crlf" value="<?php echo $crlf; ?>" /></td>
							</tr>
							</table>
							</td>
					</tr>
					<tr>
							<td>
							<br />
							<?php $button = new button($cmdOk, 'javascript:document.forms[0].submit();');	?>
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
