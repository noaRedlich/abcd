<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

require("../../Group-Office.php");

require($GO_CONFIG->class_path."scheduler.class.inc");
$scheduler = new scheduler;
require($GO_LANGUAGE->get_language_file('scheduler'));
require($GO_THEME->theme_path.'header.inc');
echo '<table border="0" cellpadding="10" cellspacing="0"><tr><td><h1>'.$sc_decline_title.'</h1>';
if ($event_id > 0 && $email != '')
{
	if ($scheduler->set_event_status($event_id,'2', $email))
	{
		echo $sc_decline_confirm;
	}
}else
{
	echo $sc_bad_event;
}

echo '</td></tr></table>';
require($GO_THEME->theme_path.'footer.inc');
?>
