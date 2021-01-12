<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

require("../Group-Office.php");
$GO_SECURITY->authenticate(true);
require($GO_LANGUAGE->get_language_file('filetypes'));
require($GO_CONFIG->class_path."filetypes.class.inc");
$filetypes = new filetypes;

$page_title= $ft_title;
require($GO_THEME->theme_path."header.inc");

$task = isset($task) ? $task : '';
switch ($task)
{
	case 'filetype':
		require("filetype.inc");
	break;

	default:
		require("filetypes.inc");
	break;
}
require($GO_THEME->theme_path."footer.inc");
?>

