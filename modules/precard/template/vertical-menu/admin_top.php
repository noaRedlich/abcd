<?php
global $config,$lang;

include("$config[template_path]/admin_top2.php");

?>
<link rel=stylesheet href="<?php echo $config[ template_url ] ?>/style.css?1" type="text/css">
<script language="JavaScript" src="/modules/stock/include/calendar/Calendar1-901.js"></script>
<LINK REL="stylesheet" TYPE="text/css" HREF="/modules/stock/include/calendar/Calendar.css">
<script type="text/javascript">
    <!-- //
    //Show Calendar dialog
    function ShowCalendar(id) {
        ShowCalendar1(id)
        //show_calendar(id,null,null,"DD/MM/YYYY","INLINE","InlineX="+(event.screenX-window.screenLeft)+";Title=Date;Width=180;InlineY="+(event.screenY-window.screenTop+10)+";Fix=No;WeekStart=0;Weekends=56;AllowWeekends=Yes;ShortNames=Yes");
    }
    function ShowCalendar1(id) {
        show_calendar(id, null, null, "DD/MM/YYYY", "POPUP", "InlineX=" + (event.screenX - window.screenLeft) + ";Title=Date;Width=135;Height=200;InlineY=" + (event.screenY - window.screenTop + 10) + ";Fix=No;WeekStart=0;Weekends=56;AllowWeekends=Yes;ShortNames=Yes");
    }
    function basket(id, addaction) {
        s = "basket.php?id=" + id + "&action=" + ((addaction) ? "add" : "del") + "&rnd=" + Math.random();
        refreshBasket(s);
    }
    function confirmDelete() {
        var agree = confirm("<?php echo $lang[are_you_sure_you_want_to_delete] ?>");
        if (agree)
            return true;
        else
            return false;
    }
    function confirmUserDelete() {
        var agree = confirm("<?php echo $lang[delete_user] ?>");
        if (agree)
            return true;
        else
            return false;
    }
    function OnLoad() {
        try {
            <?if ($action){?>
            opener.location = opener.location.href;
            <?}?>
        }
        catch (e) {
        }
    }
    function refreshBasket(url) {
        if (url + "" == "undefined") {
            url = "basket.php";
        }
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP")
        xmlhttp.open("GET", url, false)
        xmlhttp.send()
        s = xmlhttp.ResponseText.replace("items_in_basket", "<?=$lang['items_in_basket']?>");
        s = s.replace("basket_empty", "<?=$lang['basket_empty']?>");
        document.getElementById("basket").innerHTML = s;
    }
    function wopen(url, name) {
        s = window.open(url + '&simple=1', name, 'top=50,left=150,height=500,width=550,resizable=yes,scrollbars=yes');
        s.focus();
    }
    function wopen1(url, name) {
        s = window.open(url + '&simple=1', name, 'top=50,left=100,height=500,width=600,resizable=yes,scrollbars=yes');
        s.focus();
    }
    function openReport(url) {
        s = window.open(url + '&simple=1', '', 'top=50,left=100,height=500,width=800,resizable=yes,scrollbars=yes,status=yes');
        s.focus();
    }
    function openReports() {
        //var oPopBody = oPopup.document.body;
        //oPopBody.innerHTML = document.getElementById("reports").innerHTML;
        //oPopup.show(window.event.clientX, window.event.clientY+5, 130, 70, document.body);
        wopen("reports.php?", "reports");
    }
    function openImports() {
        //var oPopBody = oPopup.document.body;
        //oPopBody.innerHTML = document.getElementById("reports").innerHTML;
        //oPopup.show(window.event.clientX, window.event.clientY+5, 130, 70, document.body);
        wopen("imports.php?", "reports");
    }
    // -->
</script>
<? if (!$simple && !$rmodule){ ?>
<div id="reports" style='display:none'>
    <div dir=<?= $direction ?> style="padding:5px;font-family:arial;font-size:9pt;height:100%;width:100%;border:solid 1 black;background-color:#FFFFCC
    ">
    <a href=#></a>
    <a href=# style='width:100%;color:black' onclick='parent.openReport("rep_tazrim.php?")'><?= $lang["report_tazrim"] ?></a>
</div>
</div>
<table bgcolor=#E0DBDC border="0" cellspacing="2" cellpadding="2" width="100%">
    <form name=F method=get>
        <tr>
            <?php
            global $lang;
            if ($admin_privs == "yes" || $editForms == "yes") {
                // if the user has either admin or edit forms privs
                echo "$lang[admin_menu_regular_options] | ";
            } // end if
            ?>
            <td nowrap style='border:outset 2' align=center>
                <img src=<?= $imgPath ?>folder_gear.png hspace=3 align=absmiddle border=0><br>
                <a href="javascript:wopen1('cp/main.php?service=shaonuserdata','tools')">
                    <?php echo "$lang[admin_menu_tools]"; ?></a>
                <br>
                <a href="javascript:wopen1('imports.php?simple=1','imports')"><?= $lang['admin_menu_imports'] ?></a>
            </td>
            <? if ($GO_SECURITY->has_admin_permission($GO_SECURITY->user_id)) { ?>
            <? } ?>
            <?php
            // admin options
            global $editForms,$viewLogs,$admin_privs;
            if ($editForms == "yes") {
                // if the user can edit forms
                echo "<div align=\"right\" class=\"small\">$lang[admin_menu_form_editor_options] | <a href=\"user_form_editor.php\">$lang[admin_menu_edit_user_form]</a> | <a href=\"template_editor.php\">$lang[admin_menu_edit_listings_template]</a></div>";
            } // end if
            if ($admin_privs == "yes") {
                // if the user has admin privs
                echo "<div align=\"right\" class=\"small\">$lang[admin_menu_admin_options] | <a href=\"user_edit.php\">$lang[admin_menu_edit_users]</a> | <a href=\"listings_edit.php\">$lang[admin_menu_edit_listings]</a> | <a href=\"add_user.php\">$lang[user_editor_new]</a>";
            } // end if
            if ($admin_privs == "yes" AND $config[ moderate_listings ] == "yes") {
                // if the user has admin privs and moderation is turned on
                echo " | <a href=\"moderation_queue.php\">$lang[admin_listings_moderation_queue]</a>";
            }
            if ($viewLogs == "yes") {
                echo " | <a href=\"log_view.php\">$lang[log_view_activity_logs]</a>";
            } // end if
            ?>
            <td>&nbsp;</td>
            <td width=99% align=center>
                <fieldset>
                    <legend>תנאי חיפוש</legend>
                    <?= $lang["from"] ?> <input size=6 name=sDate id=sDate value="<?= $sDate ?>">
                    <img align=absmiddle style="cursor:hand" src='<?= $imgPath ?>calendar.gif' onclick='ShowCalendar1("F.sDate")'>
                    &nbsp;
                    <?= $lang["to"] ?> <input size=6 name=eDate id=eDate value="<?= $eDate ?>">
                    <img align=absmiddle style="cursor:hand" src='<?= $imgPath ?>calendar.gif' onclick='ShowCalendar1("F.eDate")'>
                    &nbsp;<?=$lang['in_point']?>
                    <select name=stock>
                        <option value=""><?= $lang["all_points"] ?>
                            <? while (!$stocks->EOF){ ?>
                        <option value="<?= $stocks->fields["ID"] ?>" <?= ($stock == $stocks->fields["ID"]) ? "selected" : "" ?>><?= $stocks->fields["StockName"] ?>
                            <? $stocks->MoveNext();
                            } ?>
                    </select>
                    <input style="cursor:hand;padding:0 0 0 0;background:url();background-repeat:no-repeat;background-position:left top" type=submit value='<?=$lang['submit']?>'>
                    <? if ($xlsfilename) { ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type=button value=" Excel " style="cursor:hand;padding:0 0 0 10;background:url(<?= $imgPath ?>excel.gif);background-repeat:no-repeat;background-position:left top"
                               onclick='location="../../tmp/<?= $xlsfilename ?>_<?= $userID ?>.xls?rnd=<?= rand() ?>"'>
                    <? } ?>
                </fieldset>
            </td>
            <td id=basket width=200>
                <!--iframe id=basket src=basket.php style='width:200px;height:40' frameborder=0></iframe-->
            </td>
        </tr>
    </form>
</table>
<p>
    <? } elseif ($simple) { ?>
        <style>
            body {
                background-color: buttonface
            }
        </style>
        <script>
            try {
                document.body.onload = OnLoad;
            }
            catch (e) {
            }
        </script>
    <? } ?>
<table width="100%" border="0" cellspacing="3" cellpadding="3">
    <tr>
        <td width="100%" valign="top">
            <!-- A Separate Layer for the Calendar -->
            <!-- Make sure to use the name Calendar for this layer -->
            <SCRIPT Language="Javascript" TYPE="text/javascript">
                Calendar.CreateCalendarLayer(10, 275, "");
            </SCRIPT>