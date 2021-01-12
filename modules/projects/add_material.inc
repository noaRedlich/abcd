<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/
?>
<form method="post" name="add_fee" action="<?php echo $PHP_SELF; ?>">
<input type="hidden" name="post_action" value="<?php echo $post_action; ?>" />

<table border="0" cellpadding="0" cellspacing="3">

<tr>
	<td><?php echo $strName; ?>:</td>
	<td><input type="text" name="name" value="<?php if (isset($name)) echo $name; ?>" maxlength="50" size="40" class="textbox" /></td>
</tr>
<tr>
	<td valign="top"><?php echo $pm_value; ?>:</td>
	<td><input type="text" name="value" value="<?php if (isset($value)) echo $value; ?>" maxlength="10" size="6" class="textbox" /></td>
</tr>
<tr>
	<td valign="top"><?php echo $strComments; ?>:</td>
	<td><textarea name="description" class="textbox" cols="50" rows="4"><?php if (isset($description)) echo $description; ?></textarea></td>
</tr>
<tr>
	<td colspan="2"><br />
	<?php
	$button = new button($cmdSave, "javascript:document.forms[0].submit()");
	echo '&nbsp;&nbsp;';
	$button = new button($cmdCancel, "javascript:document.location='".$PHP_SELF."?post_action=materials';");
	?>
	</td>
</tr>

</table>
</form>