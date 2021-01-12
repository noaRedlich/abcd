<?php
require("../../Group-Office.php");
$GO_SECURITY->authenticate();

require_once($GO_CONFIG->root_path.'database/'.$GO_CONFIG->db_type.".class.inc");
$db = new db();
$db->query("ALTER TABLE `pmProjects` DROP `contact_id`");
$db->query("ALTER TABLE `emAccounts` ADD `mbroot` VARCHAR( 30 ) NOT NULL");
echo 'If you see no errors then the update is successfull!';
?>