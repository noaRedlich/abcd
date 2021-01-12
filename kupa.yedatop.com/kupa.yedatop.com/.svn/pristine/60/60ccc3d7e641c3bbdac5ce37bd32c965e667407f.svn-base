<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

require("../../Group-Office.php");
$GO_SECURITY->authenticate();
$GO_MODULES->authenticate('scheduler');
require($GO_LANGUAGE->get_language_file('scheduler'));
$page_title = $sc_participants;
require($GO_CONFIG->class_path."scheduler.class.inc");
$scheduler=new scheduler;

$event = $scheduler->get_event($event_id);

if ($REQUEST_METHOD == "POST")
{
	$scheduler->set_event_status($event_id, $status, $ses_email);

}

require($GO_THEME->theme_path."simple_header.inc");
$status = $scheduler->get_event_status($event_id, $ses_email);
echo '<form name="participants" action="'.$PHP_SELF.'" method="post">';
echo '<input type="hidden" name="status" />';
echo '<input type="hidden" name="event_id" value="'.$event_id.'" />';
echo '<table border="0" width="95%" align="center" cellpadding="0" cellspacing="0">';
echo '<tr><td align="right" colspan="4" height="20">';

switch ($status)
{
	case '0';
		echo '<a class="normal" href="javascript:set_status(1)">'.$sc_accept.'</a>&nbsp;|&nbsp;';
		echo '<a class="normal" href="javascript:set_status(2)">'.$sc_decline.'</a>';
	break;

	case '1';
		echo '<a class="normal" href="javascript:set_status(2)">'.$sc_decline.'</a>';
	break;

	case '2';
		echo '<a class="normal" href="javascript:set_status(1)">'.$sc_accept.'</a>';
	break;
}
echo '</td></tr>';
?>
<tr height="30">
	<td colspan="4">
	<h1><?php echo $page_title; ?></h1>
	</td>
</tr>
<tr>
	<td class="TableHead2"><?php echo $strName; ?></td>
	<td class="TableHead2"><?php echo $strEmail; ?></td>
	<td class="TableHead2"><?php echo $sc_status; ?></td>
</tr>
<?php
if ($scheduler->get_participants($event_id))
{
	while ($scheduler->next_record())
	{
		echo '<tr><td nowrap>'.show_profile_by_email($scheduler->f('email'), $scheduler->f('name')).'&nbsp;</td>';
		echo '<td nowrap>'.mail_to($scheduler->f('email')).'&nbsp;</td><td>';
		switch($scheduler->f('status'))
		{
			case '0':
				echo $sc_not_responded;
			break;

			case '1':
				echo $sc_accepted;
			break;

			case '2':
				echo $sc_declined;
			break;

		}
		echo '</td></tr>';
		echo '<tr><td colspan="4" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
	}
}else
{
	echo '<tr><td colspan="4">'.$sc_no_participants.'</td></tr>';
	echo '<tr><td colspan="4" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
}
echo '</table>';
echo '</form>';
?>
<script language="javascript" type="text/javascript">
function set_status(value)
{
	document.forms[0].status.value=value;
	document.forms[0].submit();
}
</script>
<?php
require($GO_THEME->theme_path."simple_footer.inc");
?>
