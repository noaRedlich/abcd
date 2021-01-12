<?php
include_once($DOCUMENT_ROOT . "/Group-Office.php");

$simple = 1;
$page_subtitle = $lang['selling_credit'];
include("include/common.php");
include("include/functions.php");
if (!loginCheck('User'))
    exit;
global $action,$id,$cur_page,$lang,$conn,$config;
include("$config[template_path]/admin_top.html");

if ($saction != "sendreport") {
    $saction = "go";
}

if (!$sDate) {
    $firstday = mktime(0,0,0,1,1,2000);
    $sDate = date("d/m/Y",strtotime("+0 day",$firstday));
}
if (!$eDate) {
    $eDate = "01/01/2030";
}

$asDate = explode("/",$sDate);
$aeDate = explode("/",$eDate);

$startdate = mktime(0,0,0,$asDate[1],$asDate[0],$asDate[2]);
$enddate = mktime(23,59,59,$aeDate[1],$aeDate[0],$aeDate[2]);
$startDay = date("Y/m/d",$startdate);
$endDay = date("Y/m/d",$enddate);

$cats = $conn->Execute("select * from listingsCategories where user_ID = $userID  and Status=1 order by binary CategoryName");
if ($cats === false) {
    log_error($sql);
}

$cq = "";
if ($category) {
    $cq = "inner join listingsDBElements e on e.listing_ID = l.ID and field_name = 'ProductGroup' and field_value = '$category' ";
}

$sql = "select l.ID,l.Title from listingsDB l $cq where active  in ('yes','yek')  and l.user_id = $userID  order by binary Title";
$products = $conn->Execute($sql);
if ($products === false) {
    log_error($sql);
}
?>
    <script>
        function PrintReport() {
            document.getElementById("Query").style.display = "none";
            window.print();
            document.getElementById("Query").style.display = "";
        }

        function showChn(cdate) {
            openReport1("../stock/rep_cheshbonit.php?coupon=<?=$coupon?>&couponsdate=" + cdate + "&couponedate=" + cdate)
        }

        function mov(e) {
            e.parentElement.style.backgroundColor = 'lightyellow';
            e.style.cursor = "hand";
        }

        function mou(e) {
            e.parentElement.style.backgroundColor = '';
            e.style.cursor = "normal";
        }

        function openReport1(url) {
            var ss = "";
            s = window.open(url + '&simple=1', '', 'top=' + (window.screenTop + 5) + ',left=' + (window.screenLeft + 20) + ',height=500,width=800,resizable=yes,scrollbars=yes,status=yes');
            s.focus();
        }
    </script>
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
    <body>
    <table cellpadding=5 border=0 width=100%>
        <tr>
            <form method=post name=F>
                <input type=hidden name=usr value=<?= $usr ?>>
                <input type=hidden name=rmodule value=<?= $rmodule ?>>
                <td nowrap>
                    <?
                    $reporttitle = $lang['details_by_days'];
                    ?>
                    <? if ($coupon) {
                        $reporttitle .= "<br>$lang[coupon]" . $coupon;
                    } ?>
                    <strong style='font-size:12pt'><?= $reporttitle ?></strong>
                </td>
                <td width=99% style='border:inset 1'>
                    <?= $lang["from"] ?> <input size=6 name=sDate id=sDate value="<?= $sDate ?>">
                    <img align=absmiddle style="cursor:hand" src='<?= $imgPath ?>calendar.gif' onclick='ShowCalendar("F.sDate")
'>
                    <?= $lang["to"] ?> <input size=6 name=eDate id=eDate value="<?= $eDate ?>">
                    <img align=absmiddle style="cursor:hand" src='<?= $imgPath ?>calendar.gif' onclick='ShowCalendar("F.eDate")
'>


                    <input type=submit value=" <?= $lang["show"] ?> " style=";cursor:hand;padding:0 0 0 10;background-image:url(<?= $imgPath ?>refresh.gif);background-repeat:no-repeat;background-position:left top">


                    <? if ($saction == "go" || $saction == "sendreport") {
                        require("sendreport.php");
                    } ?>
                    <input type=hidden name=saction value=go>
                    <input type=hidden name=reportbody value="">
                    <input type=hidden name=sendmode value="">

                </td>
            </form>
        </tr>
    </table>
<?
if ($saction == "sendreport") {
    $rbody = strip_tags(stripslashes($reportbody),"<table><tr><td><th><b>");
    sendReport($reporttitle,$rbody,$sendmode);
    echo "<center><strong style='color:green'>" . $lang["report_sent"] . "</strong></center>";
    echo stripslashes($reportbody);
} elseif ($saction == "go") {


    echo "<div >
	<table id=REPORTTABLE dir=$dir border=1 bgcolor=white width=100% bordercolor=black style='border-collapse:collapse' cellpadding=2>";
    echo "<tr><td bgcolor=lightgreen colspan=20><strong>{$lang['payments_pos']}</strong></td></tr>";

    echo "
	<tr align=rigth>
	<th width=1%>{$lang['date']}</th>
	<th width=1%>{$lang['hiuv']}</th>
	<th width=1%>{$lang['refund1']}</th>
	<th width=1%>{$lang['hakafa']}</th>
	<th width=1%>{$lang['overall_balance']}</th>
	</tr>
	";

    $STA = array();
    $STS = array();


    $sDateSQL = substr($sDate,6,4) . "-" . substr($sDate,3,2) . "-" . substr($sDate,0,2);
    $eDateSQL = substr($eDate,6,4) . "-" . substr($eDate,3,2) . "-" . substr($eDate,0,2);

    $q = "";

    if ($sDate) {
        $q .= " and ifnull(tranErechDate,trandate) >= '$sDateSQL' ";
    }
    if ($eDate) {
        $q .= " and ifnull(tranErechDate,trandate) <= '$eDateSQL' ";
    }


    $sql = "select ifnull(tranErechDate,trandate) as trandate,
		round(sum((case 
		when paymid=7 then hakafasum 
        when paymid = 1 then CashSum
        when paymid = 2 then ChequeSum
        when paymid = 5 or paymid = 8 or paymid = 9  then creditcardsum
        else CouponSum end)*(case when isRefund=0 and IsDebtPayment=0 then 1 else 0 end)),2) as Hov,
        round(sum((case when paymid=7 then hakafasum else 0 end)* (case when isrefund=1 or IsDebtPayment=1 then 1 else 0 end)),2) as Hakafa,
	round(sum((case 
	when paymid=7 then 0
        when paymid = 1 then CashSum
        when paymid = 2 then ChequeSum
        when paymid = 5 or paymid = 8  then creditcardsum
        else CouponSum end)*(case when isRefund=1 or IsDebtPayment=1 then 1 else 0 end)),2) as Zikui
		from transactionpayments p, transactions t
		where p.trans_id = t.id and t.user_id = $userID 
		and ((PaymID = 3 and CouponNumber = '$coupon') or (paymid=7 and p.custid = '$coupon') or (t.custid = '$coupon' and t.custid<>0 and t.IsDebtPayment=1)) $q
		group by  ifnull(tranErechDate,trandate)
	";
    //echo "<!--SQL:".$sql."-->";
    $transData = $conn->Execute($sql);
    if ($transData === false) {
        log_error($sql);
    }

    while (!$transData->EOF) {
        $HOV1 += $transData->fields["Hov"];
        $ZIKUI1 += $transData->fields["Zikui"];
        $HAKAFA1 += $transData->fields["Hakafa"];
        echo "<tr>
			<td onmouseover='mov(this)' onmouseout='mou(this)' onclick='showChn(\"" . $transData->fields["trandate"] . "\")'>" . dateFromSQL($transData->fields["trandate"]) . "</td>
			<td onmouseover='mov(this)' onmouseout='mou(this)' onclick='showChn(\"" . $transData->fields["trandate"] . "\")'><span dir=ltr style='color:blue'>" . number_format($transData->fields["Hov"],2) . "</span></td>
			<td onmouseover='mov(this)' onmouseout='mou(this)' onclick='showChn(\"" . $transData->fields["trandate"] . "\")'><span dir=ltr style='color:blue'>" . number_format($transData->fields["Zikui"],2) . "</span></td>
			<td onmouseover='mov(this)' onmouseout='mou(this)' onclick='showChn(\"" . $transData->fields["trandate"] . "\")'><span dir=ltr style='color:blue'>" . number_format($transData->fields["Hakafa"],2) . "</span></td>
			<td onmouseover='mov(this)' onmouseout='mou(this)' onclick='showChn(\"" . $transData->fields["trandate"] . "\")'><span dir=ltr style='color:blue'>" . number_format($transData->fields["Hov"] - $transData->fields["Zikui"] - $transData->fields["Hakafa"],2) . "</span></td>
		</tr>";
        $transData->MoveNext();
    }

    echo "<tr>
	<td><strong>{$lang['total']}</strong></td>
	<td><span dir=ltr><strong>" . number_format($HOV1,2) . "</strong></span></td>
	<td><span dir=ltr><strong>" . number_format($ZIKUI1,2) . "</strong></span></td>
	<td><span dir=ltr><strong>" . number_format($HAKAFA1,2) . "</strong></span></td>
	<td><span dir=ltr><strong>" . number_format($HOV1 - $ZIKUI1 - $HAKAFA1,2) . "</strong></span></td>
	</tr>";


    echo "<tr><td bgcolor=lightgreen colspan=20><strong>{$lang['payments_pc']}</strong></td></tr>";
    echo "
	<tr align=rigth>
	<th width=1%>{$lang['date']}</th>
	<th width=1%>{$lang['hiuv']}</th>
	<th width=1%>{$lang['refund1']}</th>
	<th width=1%>{$lang['hakafa']}</th>
	<th width=1%>{$lang['overall_balance']}</th>
	</tr>
	";

    $STA = array();
    $STS = array();


    $sDateSQL = substr($sDate,6,4) . "-" . substr($sDate,3,2) . "-" . substr($sDate,0,2);
    $eDateSQL = substr($eDate,6,4) . "-" . substr($eDate,3,2) . "-" . substr($eDate,0,2);
    $q = "";
    if ($sDate) {
        $q .= " and day >= '$sDateSQL' ";
    }
    if ($eDate) {
        $q .= " and day <= '$eDateSQL' ";
    }


    $sql = "select day, c.id,
		round(sum(Amount*(case when hov=1 then 1 else 0 end)),2) as Hov,
                0 as Hakafa,
		round(sum(Amount*(case when hov=0 then 1 else 0 end)),2) as Zikui
		from creditorpayments p,listingsSuppliers c
		where c.user_id = $userID 
		and c.ClientNum = '$coupon'
		and p.creditor_id = c.id
		and p.status=1 $q
		group by day,c.id
	";
    //echo $sql;
    $transData = $conn->Execute($sql);
    if ($transData === false) {
        log_error($sql);
    }
    $HOV = 0;
    $ZIKUI = 0;
    $HAKAFA = 0;
    while (!$transData->EOF) {
        $HOV += $transData->fields["Hov"];
        $ZIKUI += $transData->fields["Zikui"];
        $HAKAFA += $transData->fields["Hakafa"];
        echo "<tr ";
        if ($HasWritePermissions) {
            echo " onclick='wopen(\"cp/main.php?service=creditorpayments&cid=" . $transData->fields["id"] . "&bd_event=search_data&search_key=Day&search_keys=" . $transData->fields["day"] . "\")' ";
        }
        echo ">";
        echo "
            <td " . (($HasWritePermissions) ? "onmouseover='mov(this)' onmouseout='mou(this)'" : "") . "  >" . dateFromSQL($transData->fields["day"]) . "</td>
			<td " . (($HasWritePermissions) ? "onmouseover='mov(this)' onmouseout='mou(this)'" : "") . "  ><span dir=ltr style='color:blue'>" . number_format($transData->fields["Hov"],2) . "</span></td>
			<td " . (($HasWritePermissions) ? "onmouseover='mov(this)' onmouseout='mou(this)'" : "") . "  ><span dir=ltr style='color:blue'>" . number_format($transData->fields["Zikui"],2) . "</span></td>
			<td " . (($HasWritePermissions) ? "onmouseover='mov(this)' onmouseout='mou(this)'" : "") . "  ><span dir=ltr style='color:blue'>" . number_format($transData->fields["Hakafa"],2) . "</span></td>
			<td " . (($HasWritePermissions) ? "onmouseover='mov(this)' onmouseout='mou(this)'" : "") . "  ><span dir=ltr style='color:blue'>" . number_format($transData->fields["Hov"] - $transData->fields["Zikui"] - $transData->fields["Hakafa"],2) . "</span></td>
		</tr>";
        $transData->MoveNext();
    }

    //http://localhost:8086/modules/credit/cp/main.php?cid=2&bd_event=search_data&search_key=Day&search_keys=2004-02-20

    echo "<tr>
	<td><strong>{$lang['total']}</strong></td>
	<td><span dir=ltr><strong>" . number_format($HOV,2) . "</strong></span></td>
	<td><span dir=ltr><strong>" . number_format($ZIKUI,2) . "</strong></span></td>
	<td><span dir=ltr><strong>" . number_format($HAKAFA,2) . "</strong></span></td>
	<td><span dir=ltr><strong>" . number_format($HOV - $ZIKUI - $HAKAFA,2) . "</strong></span></td>
	</tr>";

    echo "<tr></tr><tr style='background-color:lightgreen'>
	<td><strong>{$lang['total']}</strong></td>
	<td><span dir=ltr><strong>" . number_format($HOV1 + $HOV,2) . "</strong></span></td>
	<td><span dir=ltr><strong>" . number_format($ZIKUI1 + $ZIKUI,2) . "</strong></span></td>
	<td><span dir=ltr><strong>" . number_format($HAKAFA1 + $HAKAFA,2) . "</strong></span></td>
	<td><span dir=ltr><strong>" . number_format(($HOV1 + $HOV) - ($ZIKUI + $ZIKUI1) - ($HAKAFA + $HAKAFA1),2) . "</strong></span></td>
	</tr>";
    echo "";

    echo "</table>
	
	
	</div>";
}

include("$config[template_path]/admin_bottom.html");
$conn->Close(); // close the db connection
?>