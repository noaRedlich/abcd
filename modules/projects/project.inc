<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

if ($project = $projects->get_project($project_id))
{
?>
<a href="<?php echo $PHP_SELF; ?>?post_action=load_data&project_id=<?php echo $project_id; ?>" class="normal"><?php echo $pm_show_data; ?></a>
&nbsp;|&nbsp;
<a href="javascript:popup('permissions.php?project_id=<?php echo $project_id; ?>','450','380')" class="normal"><?php echo $strPermissions; ?></a>
<br /><br />
<form method="post" action="<?php echo $PHP_SELF; ?>" name="project">
<input type="hidden" name="post_action" value="project" />
<input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
<table border="0" cellspacing="0" cellpadding="4">
<tr>
	<td><?php echo $strName; ?>:</td>
	<td>
	<?php
	if ($project['user_id'] == $GO_SECURITY->user_id)
	{
		echo '<input type="text" class="textbox" size="40" name="name" value="'.$project['name'].'" maxlength="50" />';
	}else
	{
		echo $project['name'];
	}
	?>
	</td>
<tr>
<tr>
	<td><?php echo $strOwner; ?>:</td>
	<td>
	<?php
	echo show_profile($project['user_id']);
	?>
	</td>
</tr>
<tr>
	<td><?php echo $pm_created_at; ?>:</td>
	<td>
	<?php
	echo date($ses_date_format, $project['creation_time']);
	?>
	</td>
<tr>
<tr>
	<td valign="top"><?php echo $strComments; ?>:</td>
	<td>
	<?php
	if ($project['user_id'] == $GO_SECURITY->user_id)
	{
		echo '<textarea name="comments" cols="50" rows="4" class="textbox">'.$project['comments'].'</textarea>';
	}else
	{
		echo text_to_html($project['comments']);
	}
	?>
	</td>
</tr>
<tr>
	<td colspan="2">
	<?php
	if ($project['user_id'] == $GO_SECURITY->user_id)
	{
		$button = new button($cmdSave, "javascript:document.forms[0].submit()");
		echo '&nbsp;&nbsp;';
	}
	$button = new button($cmdBack, "javascript:document.location='".$PHP_SELF."?post_action=projects';");
	?>
	</td>
</tr>
</table>
</form>
<?php
}else
{
	echo $strDataError;
}
?>