<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 24 April 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

require("../../Group-Office.php");

$GO_SECURITY->authenticate();
$GO_MODULES->authenticate('scheduler');
require($GO_LANGUAGE->get_language_file('scheduler'));

require($GO_CONFIG->class_path."scheduler.class.inc");
$scheduler = new scheduler();

$event = $scheduler->get_event($event_id);

require($GO_THEME->theme_path.'simple_header.inc');
?>
<form method="post" action="<?php echo $PHP_SELF; ?>">
<input type="hidden" name="event_id" value="<?php echo $event_id; ?>" />
<input type="hidden" name="scheduler_id" value="<?php echo $scheduler_id; ?>" />
<input type="hidden" name="task" value="scheduler" />

<table align="center" border="0" cellpadding="2">
<tr>
	<td>
	<table border="0" cellpadding="4">
	<tr>
		<td><img src="<?php echo $GO_THEME->image_url; ?>questionmark.gif" border="0" /></td><td align="center"><h2><?php echo $sc_delete_event; ?></h2></td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td><?php echo $sc_delete_pre." '".$event['title']."' ".$sc_delete_suf; ?></td>
</tr>
<tr>
	<td>
	<br />
	<?php
	$button = new button($sc_from_scheduler,"javascript:delete_event('unsubscribe_event')");
	echo '&nbsp;&nbsp;';
	$button = new button($sc_enitrely,"javascript:delete_event('delete_event')");
	echo '&nbsp;&nbsp;';
	$button = new button($cmdCancel,'javascript:window.close();');
	?>
	</td>
</tr>
</table>
</form>
<script type="text/javascript" language="javascript">
function delete_event(task)
{
	opener.document.forms[0].event_id.value = <?php echo $event_id; ?>;
	opener.document.forms[0].post_action.value=task;
	opener.document.forms[0].submit();
	window.close();
}
</script>
<?php
require($GO_THEME->theme_path.'simple_footer.inc');
?>

