<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/


require("../../Group-Office.php");
$GO_SECURITY->authenticate();
$GO_MODULES->authenticate('email');
require($GO_LANGUAGE->get_language_file('email'));
require($GO_CONFIG->class_path."imap.class.inc");

$mail = new imap;

$to = '';
$texts = '';
$images = '';

if ($mail->open($email_host, $email_type,$email_port,$email_username,$email_password,$mailbox))
{
	$mail_sort = isset($mail_sort) ? $mail_sort : '';
	$mail_reverse = isset($mail_reverse) ? $mail_reverse : '';
	$content = $mail->get_message($uid, $mail_sort, $mail_reverse);
	$subject = isset($content["subject"]) ? $content["subject"] : $ml_no_subject;

	$page_title = $subject;
	require($GO_THEME->theme_path."simple_header.inc");

	echo '<table border="0" cellpadding="10" width="100%"><tr><td>';
	require('message.inc');

}else
{
	require($GO_THEME->theme_path."simple_header.inc");
	echo '<table border="0" cellpadding="10" width="100%"><tr><td>';
	echo '<p class="Error">'.$ml_connect_failed.'</p>';
	echo '<p class="Error">'.imap_last_error().'</p>';
}
echo '</td></tr></table>';
echo "\n<script type=\"text/javascript\">\nwindow.print();\n</script>\n";
require($GO_THEME->theme_path."simple_footer.inc");
?>
