<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

require("../../Group-Office.php");
$GO_SECURITY->authenticate();
require($GO_LANGUAGE->get_language_file('scheduler'));
$post_action = isset($post_action) ? $post_action : 'read';
require($GO_CONFIG->class_path."scheduler.class.inc");
$schedulers = new scheduler();

$event = $schedulers->get_event($event_id);

if ($post_action == 'read')
{
	$title = $strReadRights;
	$read_tab = true;
	$acl_control_id="read";
	$acl_control_acl_id = $event["acl_read"];
	if ($event['user_id'] != $GO_SECURITY->user_id)
	{
		$acl_control_read_only = true;
	}
}else
{
	$title = $strWriteRights;
	$write_tab = true;
	$acl_control_id="write";
	$acl_control_acl_id = $event["acl_write"];
	if ($event['user_id'] == $GO_SECURITY->user_id)
	{
		$acl_control_select_self = true;
	}else
	{
		$acl_control_read_only = true;
	}
}

$page_title=$event['title'];

require($GO_THEME->theme_path."simple_header.inc");
?>
<form method="post" name="permissions" action="<?php echo $PHP_SELF; ?>">
<input type="hidden" name="close" value="false" />
<input type="hidden" name="event_id" value="<?php echo $event_id; ?>" />
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
				<td colspan="99" class="TableHead"><?php echo $event['title']; ?></td>
			</tr>
			<tr>
				<td class="<?php if(isset($read_tab)) echo 'ActiveTab'; else echo 'Tab'; ?>" align="center" width="100"><a href="<?php echo $PHP_SELF; ?>?post_action=read&event_id=<?php echo $event_id; ?>" class="<?php if(isset($read_tab)) echo 'ActiveTab'; else echo 'Tab'; ?>"><?php echo $strReadRights; ?></a></td>
				<td class="<?php if(isset($write_tab)) echo 'ActiveTab'; else echo 'Tab'; ?>" align="center" width="100"><a href="<?php echo $PHP_SELF; ?>?post_action=write&event_id=<?php echo $event_id; ?>" class="<?php if(isset($write_tab)) echo 'ActiveTab'; else echo 'Tab'; ?>"><?php echo $strWriteRights; ?></a></td>
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
					<td>
					<?php
					if ($event['user_id'] == $GO_SECURITY->user_id)
					{
						$button = new button($cmdSave, 'javascript:save_close()');
						echo '&nbsp;&nbsp;';
						$button = new button($cmdApply, 'javascript:document.forms[0].submit()');
						echo '&nbsp;&nbsp;';
					}
					$button = new button($cmdClose, 'javascript:window.close()');
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