<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/
?>
<input type="hidden" name="task" value="new_folder" />
<table border="0" cellpadding="0" cellspacing="0" class="TableBorder" width="400">
<tr>
	<td valign="top">
	<table border="0" cellpadding="1" cellspacing="1" width="100%">
	<tr>
		<td colspan="99" class="TableHead"><?php echo $fbNewFolder; ?></td>
	</tr>
	<tr>
		<td class="TableInside">
		<table border="0" cellpadding="4" cellspacing="0">
		<tr>
			<td colspan="2"><?php if(isset($feedback)) echo $feedback; ?>&nbsp;</td>
		</tr>
		<tr>
			<td>
			<?php echo $strName; ?>:
			</td>
			<td>
			<input type="text" class="textbox" name="name" value="<?php echo $fbNewFolder; ?>" maxlength="100" size="30" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<br />
				<?php
				$button = new button($cmdOk, "javascript:document.forms[0].submit()");
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