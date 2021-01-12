<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

require("../Group-Office.php");

//load filetypes management class
require($GO_CONFIG->class_path."filetypes.class.inc");
$filetypes = new filetypes;
$mime = isset($mime) ? $mime : '';
if(!$filetype = $filetypes->get_type($extension, true))
{
	$filetype = $filetypes->add_type($extension, $mime,'','',true);
}

header("Cache-Control: max-age=2592000\n");
header("Content-type: image/gif\n");
header("Content-Disposition: filename=".$filetype['extension'].".gif\n");
header("Content-Transfer-Encoding: binary\n");
echo $filetype['image'];
?>
