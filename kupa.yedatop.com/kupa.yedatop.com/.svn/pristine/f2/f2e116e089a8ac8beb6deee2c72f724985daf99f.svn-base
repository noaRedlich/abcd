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

require($GO_CONFIG->class_path."imap.class.inc");
$mail = new imap;
if ($mail->open($email_host, $email_type, $email_port, $email_username, $email_password, $mailbox))
{
	$file = $mail->view_part($uid, $part, $transfer, $mime);
	$mail->close();

	$filename = smartstrip($filename);
	$browser = detect_browser();

	header('Content-Type: '.$mime);
	header('Content-Length: '.strlen($file));
	header('Expires: '.gmdate('D, d M Y H:i:s') . ' GMT');
	if ($browser['name'] == 'MSIE')
	{
		header('Content-Disposition: inline; filename='.$filename);
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
	}else
	{
		header('Pragma: no-cache');
		header('Content-Disposition: attachment; filename='.$filename);
	}
	header('Content-Transfer-Encoding: binary');
	echo ($file);
}else
{
	echo $strDataError;
}
?>
