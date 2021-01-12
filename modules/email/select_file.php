<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

require_once('../../Group-Office.php');
require($GO_LANGUAGE->get_language_file('filesystem'));
$module = $GO_MODULES->get_module('email');
$GO_HANDLER = $GO_CONFIG->host.$module['path'].'attach_online.php';
$GO_CONFIG->window_mode = 'popup';
$target_frame = '_self';
$module = $GO_MODULES->get_module('filesystem');
require($GO_CONFIG->root_path.$module['path'].'index.php');
?>