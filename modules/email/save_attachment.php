<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

require("../../Group-Office.php");

$GO_SECURITY->authenticate();
require($GO_LANGUAGE->get_language_file('filesystem'));

if (isset($filename) && $REQUEST_METHOD != 'POST')
{
	$filename = stripslashes($filename);
	$email_tmp_file = $GO_CONFIG->tmpdir.$filename;
	session_register('email_tmp_file');

	require($GO_CONFIG->class_path.'filetypes.class.inc');
	$filetypes = new filetypes();
	$extension = get_file_extension($filename);
	if (!$type = $filetypes->get_type($extension))
	{
		$filetypes->add_type($extesnion, $mime);
	}
}

if (!isset($filename))
{
	$filename = basename($email_tmp_file);
}else
{
	$filename = smartstrip($filename);
}

if (isset($task) && $task == 'GO_HANDLER')
{
	require($GO_CONFIG->class_path.'filesystem.class.inc');
	$fs = new filesystem();

	if (file_exists(smartstrip($path).'/'.$filename))
	{
		$feedback = '<p class="Error">'.$fbNameExists.'</p>';

	}elseif(!$fs->has_write_permission($GO_SECURITY->user_id, smartstrip($path)))
	{
		$feedback = '<p class="Error">'.$strAccessDenied.': '.smartstrip($path).'</p>';
	}else
	{
		$new_path = smartstrip($path).'/'.$filename;
		if ($fs->move($email_tmp_file, $new_path))
		{
			$old_umask = umask(000);
			chmod($new_path, $GO_CONFIG->create_mode);
			umask($old_umask);

			echo "<script type=\"text/javascript\" language=\"javascript\">\n";
			echo "window.close()\n";
			echo "</script>\n";
		}else
		{
			$feedback = '<p class="Error">'.$strSaveError.'</p>';
		}
	}
}

if (!file_exists($email_tmp_file) && !is_dir($email_tmp_file))
{
	require($GO_CONFIG->class_path."imap.class.inc");
	$mail = new imap();
	if ($mail->open($email_host, $email_type, $email_port, $email_username, $email_password, $mailbox))
	{
		$data = $mail->view_part($uid, $part, $transfer, $mime);
		$mail->close();
		$fp = fopen($email_tmp_file,"w");
		fputs ($fp, $data, strlen($data));
		fclose($fp);
	}else
	{
		die('Could not connect to mail server!');
	}
}
require_once('../../Group-Office.php');

$module = $GO_MODULES->get_module('email');
$GO_HANDLER = $PHP_SELF;
$GO_CONFIG->window_mode = 'popup';
$mode = 'save';
$module = $GO_MODULES->get_module('filesystem');
require($GO_CONFIG->root_path.$module['path'].'index.php');
?>
