<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

require("../../Group-Office.php");
$GO_SECURITY->authenticate();
$GO_MODULES->authenticate('projects');
require($GO_LANGUAGE->get_language_file('projects'));

$post_action = isset($post_action) ? $post_action : 'read';
require($GO_CONFIG->class_path."projects.class.inc");
$projects = new projects;

$project = $projects->get_project($project_id);

if ($project["user_id"] != $GO_SECURITY->user_id)
{
	$acl_control_read_only = true;
}

if ($post_action == 'read')
{
	$title = $strReadRights;
	$read_tab = true;
	$acl_control_id="read";
	$acl_control_acl_id = $project["acl_read"];
}else
{
	$title = $strWriteRights;
	$write_tab = true;
	$acl_control_id="write";
	$acl_control_acl_id = $project["acl_write"];
	$acl_control_select_self = true;
}

$page_title=$project['name'];

require($GO_THEME->theme_path."simple_header.inc");
?>
<form method="post" name="permissions" action="<?php echo $PHP_SELF; ?>">
<input type="hidden" name="close" value="false" />
<input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
<input type="hidden" name="post_action" value="<?php echo $post_action; ?>" />

<table border="0" cellpadding="10" cellspacing="0">
<tr>
	<td>
	<table border="0" cellpadding="2" cellspacing="0">
	<tr>
		<td valign="top">
		<table border="0" cellpadding="0" cellspacing="0" class="TableBorder" width="400">
		<tr>
			<td valign="top">
			<table border="0" cellpadding="1" cellspacing="1" width="100%">
			<tr>
				<td colspan="99" class="TableHead"><?php echo $projects->f('name'); ?></td>
			</tr>
			<tr>
				<td class="<?php if(isset($read_tab)) echo 'ActiveTab'; else echo 'Tab'; ?>" align="center" width="100"><a href="<?php echo $PHP_SELF; ?>?post_action=read&project_id=<?php echo $project_id; ?>" class="<?php if(isset($read_tab)) echo 'ActiveTab'; else echo 'Tab'; ?>"><?php echo $strReadRights; ?></a></td>
				<td class="<?php if(isset($write_tab)) echo 'ActiveTab'; else echo 'Tab'; ?>" align="center" width="100"><a href="<?php echo $PHP_SELF; ?>?post_action=write&project_id=<?php echo $project_id; ?>" class="<?php if(isset($write_tab)) echo 'ActiveTab'; else echo 'Tab'; ?>"><?php echo $strWriteRights; ?></a></td>
				<td class="Tab" width="200">&nbsp;</td>
			</tr>
			<tr>
				<td class="TableInside" height="300" valign="top" colspan="5">
				<table border="0" cellpadding="10">
				<tr>
					<td>
					<?php
					require($GO_CONFIG->control_path."acl_control.inc");
					?>
					</td>
				</tr>
				<tr>
					<td class="cmd">
					<?php
					if (!isset($acl_control_read_only))
					{
						$button = new button($cmdOk, "javascript:save_close();");
						echo '&nbsp;&nbsp;';
						$button = new button($cmdApply, "javascript:document.forms[0].submit();");
						echo '&nbsp;&nbsp;';
					}
					$button = new button($cmdClose, "javascript:window.close()");
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
</form>
<script type="text/javascript">
<!--
	function save_close()
	{
		document.forms[0].close.value = 'true';
		document.forms[0].submit();
	}
-->
</script>
<?php

if ($close == 'true')
{
	echo "<script type=\"text/javascript\">\n";
	echo "window.close()\n";
	echo "</script>\n";
}
require($GO_THEME->theme_path."simple_footer.inc");
?>