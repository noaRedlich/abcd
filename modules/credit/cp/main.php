<?
header("Pragma: no-cache");
$simple = 1;
require("../../../Group-Office.php");
$page_subtitle = $lang['admin_menu_tools'];

//always set hebrew
//$GO_LANGUAGE->set_language("Hebrew");
require($GO_LANGUAGE->get_language_file('common'));
$imgPath = $GO_THEME->image_url . "credit/";


//authenticate the user
//if $GO_SECURITY->authenticate(true); is used the user needs admin permissons
$GO_SECURITY->authenticate();

//see if the user has access to this module
//for this to work there must be a module named 'example'
$GO_MODULES->authenticate('credit');

$officedb = $GO_CONFIG->db_name;
require_once($GO_CONFIG->class_path . 'users.class.inc');
//create a users object:
$users = new users();
$userID = $GO_SECURITY->user_id;
$user = $users->get_user($userID);
$userName = $user[ username ];

//set the page title for the header file
$page_title = $lang['selling_credit'];
//require the header file. This will draw the logo's and the menu
// there is also the simple_header.inc file without logo's and menu

require "lib/system.lib";
require "lib/plug-ins.lib";
require "lib/mod_xml.lib";
require "lib/mod_xml_ct.lib";
require "lib/mail.lib";

require "services/BD3LoadConfiguration.service";
$main_db = $db_name = $GO_CONFIG->stock_db_name;
require "services/BD3LoadDBDevice.service";

require "services/BD3Auth.service";
require "services/BD3ProcessEvents.service";
require "services/BD3PushServiceName.service";
require "services/BD3PushPluginName.service";

if (!$noheader) {
    if ($simple)
        require($GO_THEME->theme_path . "simple_header.inc");
    else
        require($GO_THEME->theme_path . "header.inc");
}

session_start();

$db = c();

$language = "rus";

if (ereg("ERROR",$b,$matches)) {
    echo $b;
    exit;
}
?>
    <html>
    <?
    session_start();
    $admin_charset = $_SESSION["admin_charset"];
    ?>
    <? if ($admin_charset == "heb") $charset = "utf-8" ?>
    <? if ($admin_charset == "rus") $charset = "utf-8" ?>
    <head>
        <title>administration area</title>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= $charset ?>">
        <link rel="stylesheet" href="style.css" type="text/css">
    </head>

    <body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <table width="100%" border="0" cellspacing="4" cellpadding="4">
        <tr>
            <td valign="top">
                <table width="100%" border="0" style='display:none' cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top"><br>
                        </td>
                        <td align="right" valign="top">
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td colspan="5" class="larger" align="right"><font color="#666666">Logged in as user: <b>
                                                <? echo sysGetLoggedUserName(); ?>
                                            </b></font></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="larger" align="right"><font color="#666666">You have been logged in on:
                                            <? echo date("d.m.y H:i",$bd3AuthDT); ?>
                                        </font></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="larger" align="right">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="larger" align="right">&nbsp;</td>
                                </tr>
                            </table>
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="26" class="larger" align="right" height="22">&nbsp;</td>
                                    <td width="30" valign="top"><a href="main.php?<? echo strtotime(date("d M Y H:i:s")); ?>"><font color="#669966">[<img src="<?= $imgPath ?>p.gif" width="14" height="14" border="0">]</font></a></td>
                                    <td width="85"><a href="main.php?<? echo strtotime(date("d M Y H:i:s")); ?>"><font color="#999999">Refresh page </font></a></td>
                                    <td width="30" valign="top"><a href="main.php?action=logout"><font color="#669966">[<img src="<?= $imgPath ?>p.gif" width="14" height="14" border="0">]</font></a></td>
                                    <td width="120"><a href="main.php?action=logout"><font color="#999999">Log out from system</font></a></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <hr noshade size="1" color=C0C0C0>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
    <table width="100%" height=98% border="0" cellspacing="4" cellpadding="4">
        <tr>
            <? if (!$cid) { ?>
                <td width="20%" valign="top">
                    <?
                    if (false && $GO_SECURITY->has_admin_permission($GO_SECURITY->user_id)) {
                        @include "services/BD3AdminstratorMenu.service";
                        @include "services/BD3Tools.service";
                    }
                    ?>
                    <? @include "services/BD3UsersMenu.service"; ?>
                </td>
            <? } ?>
            <td valign="top" style='border:inset 1'
            '>
            <?

            if ($current_service == "default" || ($current_plugin == "" && $current_service == "")) {
                @include "modules/kernel/default";
            }

            if ($current_service != "" && $current_service != "default") {
                $muserdata = q("select sql_database,credit_user from userdata u,$officedb.users uu where uu.id = u.office_user_id and u.username = '$userName'");
                $muserdata = f($muserdata);
                if ($muserdata["credit_user"]) {
                    $muserdata = q("select sql_database from userdata where office_user_id = '" . $muserdata["credit_user"] . "'");
                    $muserdata = f($muserdata);
                }

                if ($muserdata["sql_database"]) {
                    $db_name = $muserdata["sql_database"];
                }
                include "services/BD3ModuleRoutines.service";
                include "services/BD3ModuleAnalyser.service";
            } else {
                if ($current_plugin != "") {
                    include "services/BD3PluginAnalyser.service";
                }
            }
            ?>
            </td>
        </tr>
    </table>
    <table style='display:none' width="100%" border="0" cellspacing="4" cellpadding="4">
        <tr>
            <td valign="top" height="106">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="right" valign="top" colspan="2">
                            <hr noshade size="1" color=C0C0C0>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <table width="295" border="0" cellpadding="0" cellspacing="0">

                                <tr>
                                    <td valign="top" width="31">&nbsp;</td>
                                    <td>
                                        <hr noshade size="1" color=F0F0F0 width="211" align="left">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" width="31" height="22">&nbsp;</td>

                                </tr>
                            </table>
                        </td>
                        <td align="right" valign="top">
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td height="22" width="30" valign="top"><a href="#top"><font color="#669966">[<img src="<?= $imgPath ?>u.gif" border="0" width="13" height="14">]</font></a></td>
                                    <td width="80"><a href="#top"><font color="#999999">Go to the top </font></a></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>

                    </tr>
                </table>
            </td>
        </tr>
    </table>
    </body>
    </html>
<?
d($db);
?>