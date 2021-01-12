<?
$simple = 1;
include_once($DOCUMENT_ROOT . "/Group-Office.php");
$page_subtitle = $lang['admin_menu_imports'];

include("include/common.php");
if (!loginCheck('User'))
    exit;
global $action,$id,$cur_page,$lang,$conn,$config;
include("$config[template_path]/admin_top.html");

?>
    <style>
        .z {
            color: gray
        }

        .b {
            font-weight: bold
        }

        th {
            background-color: silver
        }
    </style>
    <script>

        function openReport(url) {
            s = window.open(url + '&simple=1', '', 'top=50,left=100,height=500,width=800,resizable=yes,scrollbars=yes');
            s.focus();
        }

        function handleDates(auto) {
            document.F.sDate.disabled = document.F.eDate.disabled = auto
            document.all.CALFROM.disabled = document.all.CALTO.disabled = document.F.stock.disabled = auto;
            if (auto) document.F.stock.selectedIndex = 0;
        }

        function validate() {
            if (document.getElementById("DATES").style.display == "") {
                if (document.F.sDate.value == "" || isNaN(Date.parse(document.F.sDate.value))) {
                    alert("<?=$lang['invalid_value']?>");
                    document.F.sDate.focus();
                    return false;
                }
                if (document.F.eDate.value == "" || isNaN(Date.parse(document.F.eDate.value))) {
                    alert("<?=$lang['invalid_value']?>");
                    document.F.eDate.focus();
                    return false;
                }
            }
            return true;
        }

        function chMode(mode) {
            document.getElementById("ETYPE").style.display = (mode == "e") ? "" : "none";
            document.getElementById("ITYPE").style.display = (mode == "i") ? "" : "none";
            document.getElementById((mode == "e") ? "ETYPE" : "ITYPE").onchange();

            document.getElementById("FILE").style.display = (mode == "i") ? "" : "none";
            document.getElementById("DATES").style.display = (mode == "e") ? "" : "none";

            if (mode == "e") {
                document.getElementById("STOCK").style.display = (document.getElementById("ETYPE").value == "hm" || document.getElementById("ETYPE").value == "hmz" || document.getElementById("ETYPE").value == "sapt" || document.getElementById("ETYPE").value == "saps") ? "" : "none";
                document.getElementById("OPTIONS").style.display = "none";
            }
            else {
                document.getElementById("STOCK").style.display = "none";
                document.getElementById("OPTIONS").style.display = (document.getElementById("ITYPE").value == "e") ? "" : "none";

            }

        }

        function chTypeE(v) {
            document.getElementById("DATES").style.display = (v == "h" || v == "hm" || v == "hmz" || v == "sapt" || v == "saps") ? "" : "none";
            switch (v) {
                case "h":
                    document.getElementById("FRAME").src = "importworkers.php?rmodule=<?=$rmodule?>";
                    F.action = 'importworkers.php?simple=1&action=do&rmodule=<?=$rmodule?>';
                    document.getElementById("STOCK").style.display = "none";
                    break;
            }
        }

        function chTypeI(v) {
            switch (v) {
                case"e":
                    document.getElementById("OPTIONS").style.display = "none";
                    document.getElementById("FRAME").src = "importclients.php?rmodule=<?=$rmodule?>";
                    F.action = 'importclients.php?simple=1&action=do&rmodule=<?=$rmodule?>';
                    break;
            }
        }


    </script>

<?php

if (!$sDate) {
    $firstday = mktime(0,0,0,date("m"),1,date("Y"));
    $sDate = date("d/m/Y",strtotime("+0 day",$firstday));
    $eDate = date("d/m/Y",strtotime("-1 day",strtotime("+ 1 month",$firstday)));
}
?>
    <body>
    <table cellpadding=5 border=0 width=100%>
        <tr>
            <td colspan=2 bgcolor=#ffffcc align=center>
                <center><strong><?= $lang["admin_menu_imports"] ?></strong></center>
            </td>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <form name=F onsubmit='return validate();' method=post action='importworkers.php?simple=1&action=do&rmodule=<?= $rmodule ?>' encType=multipart/form-data target=log>
            <tr>
                <td width=120 bgcolor=#efefef width=1% nowrap><?= $lang['oper_type'] ?></td>
                <td>
                    <input disabled type=radio onclick='chMode("i")' name=mode value=i onclick='sswitch(1)'><?= $lang['import'] ?>
                    <input type=radio checked name=mode onclick='chMode("e")' value=e onclick='sswitch(2)'><?= $lang['export'] ?>
                </td>
            </tr>
            <tr>
                <td bgcolor=#efefef width=1% nowrap><?= $lang['type_of_software_foreign'] ?></td>
                <td>
                    <select id=ITYPE name=itype style='width:100%' onchange="chTypeI(this.value)">
                        <option value=e><?= $lang['file_excel_clients'] ?>
                    </select>

                    <select style='display:none' id=ETYPE name=etype style='width:100%' onchange="chTypeE(this.value)">
                        <option value=h><?= $lang['file_hours_of_entry_and_exit_csv'] ?>
                    </select>
                </td>
            </tr>
            <tr id=FILE>
                <td height=37 bgcolor=#efefef nowrap><?= $lang['file_name_import'] ?></td>
                <td><input type=file name=impfile style='width:100%'></td>
            </tr>
            <tr id=DATES style='display:none'>
                <td height=37 bgcolor=#efefef nowrap><?= $lang['dates_export'] ?></td>
                <td>
                    <?= $lang["from"] ?> <input size=7 name=sDate id=sDate value="<?= $sDate ?>" class="tcal">
                    <!-- img align=absmiddle style="cursor:hand" src='<?= $imgPath ?>calendar.gif' onclick='ShowCalendar("F.sDate")    ' -->
                    <?= $lang["to"] ?> <input size=7 name=eDate id=eDate value="<?= $eDate ?>" class="tcal">
                    <!-- img align=absmiddle style="cursor:hand" src='<?= $imgPath ?>calendar.gif' onclick='ShowCalendar("F.eDate")    ' -->
                </td>
            </tr>
            <tr id=OPTIONS style='display:none'>
                <td height=37 bgcolor=#efefef nowrap><?= $lang['options'] ?></td>
                <td>
                    <input type='checkbox' name=translit><?= $lang['make_russian_to_english'] ?>
                    <? if (false) { ?>
                        &nbsp;<input type='checkbox' name=forcepriceupdate><?= $lang['force_update_price'] ?>
                    <? } ?>
                </td>
            </tr>
            <tr id=STOCK style='display:none'>
                <td height=37 bgcolor=#efefef nowrap><?= $lang['stock_point'] ?></td>
                <td>
                    <?
                    //$stocks = GetStocks(true);
                    ?>
                    <select name=stock disabled style='width:100%'>
                        <? //FillStockList($stocks,$stock);?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan=2 align=center>
                    <hr>
                    <input type=submit class=button style='background-image:url(<?= $imgPath ?>ok.gif)' value="&nbsp;<?= $lang['submit'] ?>&nbsp;"></td>
            </tr>
            <tr>
                <td colspan=2>
                    <iframe id=FRAME name=log src='importworkers.php?simple=1&rmodule=<?= $rmodule ?>' style='width:100%;height:240'></iframe>
                </td>
            </tr>
        </form>
    </table>
    <script>
        function sswitch(mode) {
            document.F.file.disabled = (mode == 2)
            if (mode == 2) {
                document.F.file.value = "";
            }

        }

        chMode("e");

    </script>
<?
include("$config[template_path]/admin_bottom.html");
$conn->Close(); // close the db connection
?>