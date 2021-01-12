<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

require("../../Group-Office.php");
//load file management class
$GO_SECURITY->authenticate();
$GO_MODULES->authenticate('filesystem');

require($GO_CONFIG->class_path.'filetypes.class.inc');
require($GO_CONFIG->class_path.'filesystem.class.inc');
$fs = new filesystem();
$filetypes = new filetypes();
$path = smartstrip($path);
if ($fs->has_read_permission($GO_SECURITY->user_id, $path))
{
	$filename = basename($path);
	$extension = get_file_extension($filename);

	$type = $filetypes->get_type($extension);

	$browser = detect_browser();

	header('Content-Type: '.$type['mime']);
	header('Content-Length: '.filesize($path));
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
	readfile($path);
}else
{
	header('Location: '.$GO_CONFIG->host.'error_docs/401.php');
	exit();
}
?>