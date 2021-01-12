<?php
$page_title = "מכירה הקפה - קופות רושמות";

$firstday = mktime(0, 0, 0, date("m"), 1, date("Y"));
/*
  if (!$sDate){
  $sDate = date("d/m/Y",strtotime("+0 day",$firstday));
  }
  if (!$eDate){
  $eDate = date("d/m/Y",strtotime("-1 day",strtotime("+ 1 month",$firstday)));
  }
 */

include("include/common.php");
include("include/functions.php");
$xlsfilename = "Credit";

if (!loginCheck('User')
    )exit;
global $action, $id, $cur_page, $lang, $conn, $config;

$sql = "select WorkingHoursPerDay,WorkingHoursFriday from $TABLE_USERDATA where username='$username'";
$userdata = $conn->Execute($sql);
if ($userdata === false) {
    log_error($sql);
}
if ($userdata->EOF) {
    die("User record is not initialized, please contact administrator.");
}

if (!$sDate) {
    $sDate = date("d/m/Y", strtotime("-2 YEAR"));
}
if (!$eDate) {
    $eDate = date("d/m/Y");
}

include("$config[template_path]/admin_top.html");

echo "<table width=100% height=360 border=0><tr style='height:1%'>";
echo "<td colspan=2></td>";
// total report



$sDateSQL = substr($sDate, 6, 4) . "-" . substr($sDate, 3, 2) . "-" . substr($sDate, 0, 2);
$eDateSQL = substr($eDate, 6, 4) . "-" . substr($eDate, 3, 2) . "-" . substr($eDate, 0, 2);
$q = "";
if ($sDate && $eDate) {
    $q.=" and ifnull(tranErechDate,trandate) between '$sDateSQL' and '$eDateSQL' ";
} else {
    if ($sDate) {
        $q.=" and ifnull(tranErechDate,trandate) >= '$sDateSQL' ";
    }
    if ($eDate) {
        $q.=" and ifnull(tranErechDate,trandate) <= '$eDateSQL' ";
    }
}
$qq = "";
$qq1 = "";
if ($cname) {
    $qq.=" and SupplierName like binary '%" . addslashes($cname) . "%' ";
}
if ($cnum) {
    if ($cnum1) {
        $qq.= " and ClientNum between " . addslashes($cnum) . " and " . addslashes($cnum1) . " ";
        $qq1.= " and CouponNumber between " . addslashes($cnum) . " and " . addslashes($cnum1) . " ";
    } else {
        $qq.=" and ClientNum = '" . addslashes($cnum) . "' ";
        $qq1.=" and CouponNumber = '" . addslashes($cnum) . "' ";
    }
} elseif (!$cnum && $cnum1) {
    $qq.=" and ClientNum < '" . addslashes($cnum1) . "' ";
    $qq1.=" and CouponNumber < '" . addslashes($cnum1) . "' ";
}

if (!$sort
    )$sort = "Number";
switch ($sort) {
    case "Name": $sortorder = "binary SupplierName";
        break;
    case "Number": $sortorder = "ClientNum+0";
        break;
}

$sql = "
		select count(*) as cnt from listingsSuppliers c
		where
		c.user_id = $userID
                and ifnull(clientnum,'')<>''
		and c.status=1
		$qq
		";

$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$recordSet = $conn->Execute($sql);
if ($recordSet === false)
    log_error($sql);
$num_rows = $recordSet->fields["cnt"];

$qnewSupport = "";
if ($UserData->fields["EnableNewKupaInCredit"]) {
    $qnewSupport = " or (p.paymid=7 and p.custid = c.ClientNum) or (t.custid = c.ClientNum and t.IsDebtPayment=1) ";
}

$query = array();

if (!$clients || $clients == 3) {

    $query[] = "
			select 1 as client, 3 as sortorder, c.id,ClientNum, SupplierName as CreditorName, Phone as HomePhone, CellPhone,Address,
			round(sum((case when paymid=7 then hakafasum 
			when paymid = 1 then CashSum
			when paymid = 2 then ChequeSum
			when paymid = 5 or paymid = 8 then creditcardsum 
			else CouponSum end)*(case when isrefund=0 and IsDebtPayment=0 then 1 else 0 end)),2) as Hov,
    			round(sum((case when paymid=7 then hakafasum else 0 end)* (case when isrefund=1 or IsDebtPayment=1 then 1 else 0 end)),2) as Hakafa,
    			round(sum((case when paymid=7 then 0
			when paymid = 1 then CashSum
			when paymid = 2 then ChequeSum
			when paymid = 5 or paymid = 8 then creditcardsum			
			else CouponSum end)*(case when isrefund=1 or IsDebtPayment=1 then 1 else 0 end)),2) as Zikui
			from listingsSuppliers c,transactionpayments p,transactions t where
                        ifnull(c.ClientNum,'')<>'' and
			((c.ClientNum = p.CouponNumber and paymid=3) $qnewSupport) and
			p.trans_id = t.id and t.user_id = $userID $q
			and c.user_id = $userID 
			and c.status=1
			$qq
			group by c.id";
}

if ($clients == 1 || $clients == 3) {

    $query[] = "
			select 0 as client, 2 as sortorder, c.id, ClientNum, concat(SupplierName,' (לא פעיל)') as CreditorName,
			Phone as HomePhone, CellPhone,Address,
			round(sum((case when paymid=7 then hakafasum 
			when paymid = 1 then CashSum
			when paymid = 2 then ChequeSum
			when paymid = 5 or paymid = 8 then creditcardsum
			else CouponSum end)*(case when isrefund=0 and IsDebtPayment=0 then 1 else 0 end)),2) as Hov,
    			round(sum((case when paymid=7 then hakafasum else 0 end)* (case when isrefund=1 or IsDebtPayment=1 then 1 else 0 end)),2) as Hakafa,
    			round(sum((case when paymid=7 then 0
			when paymid = 1 then CashSum
			when paymid = 2 then ChequeSum
			when paymid = 5 or paymid = 8 then creditcardsum
			else CouponSum end)*(case when isrefund=1 or IsDebtPayment=1 then 1 else 0 end)),2) as Zikui
			from transactionpayments p,transactions t,listingsSuppliers c
			where
                        ifnull(c.ClientNum,'')<>'' and
			p.trans_id = t.id and  t.user_id = $userID $q
			and ((c.ClientNum = p.CouponNumber and paymid=3) $qnewSupport) and c.status = 0
			and c.user_id = $userID 
			$qq1
			group by CouponNumber    
			";
}
if ($clients == 2 || $clients == 3) {

    $query[] = "
			select 0 as client, 1 as sortorder, 0 as id, CouponNumber as ClientNum, 'לא הוגדר' as CreditorName,
			null as HomePhone,null as CellPhone,null as address,
			round(sum((case when paymid=7 then hakafasum 
			when paymid = 1 then CashSum 
			when paymid = 2 then ChequeSum
			when paymid = 5 or paymid = 8 then creditcardsum
			else CouponSum end)*(case when isrefund=0 and IsDebtPayment=0 then 1 else 0 end)),2) as Hov,
			round(sum((case when paymid=7 then hakafasum else 0 end)* (case when isrefund=1 or IsDebtPayment=1 then 1 else 0 end)),2) as Hakafa,
    			round(sum((case when paymid=7 then 0
			when paymid = 1 then CashSum
			when paymid = 2 then ChequeSum
			when paymid = 5 or paymid = 8 then creditcardsum
			else CouponSum end)*(case when isrefund=1 or IsDebtPayment=1 then 1 else 0 end)),2) as Zikui
			from transactionpayments p
			inner join transactions t on p.trans_id = t.id and  t.user_id = $userID $q
			where t.user_id = $userID and
			CouponNumber <> 0 and 
			not exists (select id from listingsSuppliers where user_id = $userID and ClientNum = p.CouponNumber)
			$qq1
			group by CouponNumber 
			having (select count(id) from listingsSuppliers where user_id = $userID and ClientNum = p.CouponNumber)=0
			";
}

$sql = implode(" union all ", $query) . " order by sortorder,$sortorder";


//echo "SQL:".$sql."-->";
// build the string to select a certain number of listings per page
if ($showall)$config[listings_per_page] = 10000;
$limit_str = $cur_page * $config[listings_per_page];

$recordSet = $conn->SelectLimit($sql, $config[listings_per_page], $limit_str);
if ($recordSet === false) {
    log_error($sql);
}
$count = 0;

echo "<td><h3>$s</h3></td>";
echo "</tr>";
echo "<tr style='height:1%'><td colspan=2 bgcolor=buttonface>
		<table width=100% border=0 cellpadding=0 cellspacing=0><tr>
		<td  width=99% nowrap>";

if ($HasWritePermissions) {
    echo "<a style='color:black;font-size:8pt' href=# onclick=parent.wopen1('../stock/cppro/main.php?service=suppliers&bd_event=create_record&cid=1','tools',800,500)><img src=" . $imgPath . "businessman_add.png width=16 height=16 align=absmiddle hspace=3 border=0>לקוח חדש</a>";
}

echo "</td>
		<td width=1%>
		";
$gs = "clients=$clients&showall=$showall&sort=$sort&sDate=$sDate&eDate=$eDate&cnum=$cnum&cnum1=$cnum1&cname=$cname&only=$only";
next_prev($num_rows, $cur_page, $gs);

echo "</td>
		</tr></table>
		
		</td>
		</tr>";
echo "<tr>";
echo "<td valign=top width=100% align=center>
		
		
		<div style='border:solid 1 gray;overflow-Y:scroll;overflow-X:auto;width:100%;height:100%'>";

$gs = "stock=$stock&catid=$catid&deleted=$deleted&title=" . urlencode($title) . "&BarCode=$BarCode&ProductGroup=$ProductGroup&MisparZar=$MisparZar&MisparSiduri=$MisparSiduri&MisparChalifi=$MisparChalifi&search=$search";

$fname = tempnam("../../tmp", $xlsfilename . ".xls");
$workbook = new writeexcel_workbook($fname);
$workbook->set_tempdir('../../tmp');
$worksheet = $workbook->addworksheet($xlsfilename);
$header = $workbook->addformat(array(
            bold => 1,
            color => 'blue',
            font => "Arial Hebrew",
            size => 18,
            align => 'left',
        ));
$heading = $workbook->addformat(array(
            bold => 1,
            color => 'blue',
            font => "Arial Hebrew",
            valign => 'top',
            align => 'center',
            border => 1
        ));
$body = $workbook->addformat(array(
            bold => 0,
            font => "Arial Hebrew",
            valign => 'top',
            border => 1,
            num_format => '#######################0',
        ));
$bodyred = $workbook->addformat(array(
            bold => 0,
            font => "Arial Hebrew",
            valign => 'top',
            color => "red",
            border => 1,
            num_format => '#######################0',
        ));
$numformat = $workbook->addformat(array(
            num_format => '0.00',
            bold => 0,
            font => "Arial Hebrew",
            valign => 'top',
            border => 1
        ));
$numformatb = $workbook->addformat(array(
            num_format => '0.00',
            bold => 1,
            font => "Arial Hebrew",
            valign => 'top',
            border => 1
        ));
$numformatred = $workbook->addformat(array(
            num_format => '############0.00',
            bold => 0,
            color => "red",
            font => "Arial Hebrew",
            valign => 'top',
            border => 1
        ));
$heading->set_text_wrap();
$body->set_text_wrap();
$worksheet->hide_gridlines(2);

$page_subtitle = "לקוחות";
$worksheet->write("A1", $page_subtitle, $header);
$rrow = 3;

$worksheet->set_column('A:G', 12);
$worksheet->set_column('B:C', 20);
$headings = array('מספר לקוח', 'שם לקוח', 'כתובת', 'טלפון', 'נייד', 'חיוב', 'זיכוי', 'הקפה', 'סה"כ יתרה');
$worksheet->write_row('A' . ($rrow++), $headings, $heading);
?>

<table border="0" id=REPORT_TAB cellspacing="0" cellpadding="<?php echo $style[admin_listing_cellpadding] ?>" width="100%" class="form_main">
    <tr class=tableHead2 align=center>
<? if ($HasWritePermissions) { ?>
            <td class="tableHead2" width=1% ><input type=checkbox id=CHECK value=-1 onclick=checkall(this.checked)></td>
<? } ?>
        <td class="tableHead2" width=1% nowrap><a style='color:white' href='?showall=<?= $showall ?>&sort=Number&only=<?= $only ?>&sDate=<?= $sDate ?>&eDate=<?= $eDate ?>&cnum=<?= $cnum ?>&cnum1=<?= $cnum1 ?>&cname=<?= $cname ?>'><u>מספר לקוח</u></td>
        <td class="tableHead2" nowrap><a style='color:white'  href='?showall=<?= $showall ?>&sort=Name&only=<?= $only ?>&sDate=<?= $sDate ?>&eDate=<?= $eDate ?>&cnum=<?= $cnum ?>&cnum1=<?= $cnum1 ?>&cname=<?= $cname ?>'><u>שם לקוח</u></td>
        <td class="tableHead2" width=1% nowrap >כתובת</td>
        <td class="tableHead2" width=1% nowrap >טלפון</td>
        <td class="tableHead2 " width=1% nowrap>נייד</td>
        <td class="tableHead2 money" width=1%  nowrap>חיוב</td>
        <td class="tableHead2 money" width=1%  nowrap>זיכוי</td>
        <td class="tableHead2 money" width=1%  nowrap>הקפה</td>
        <td class="tableHead2 money" width=1%  nowrap>סה"כ יתרה</td>
        <td class="tableHead2 none" width=1%  nowrap>פעולות</td>
    </tr>
<?
$HOV = $ZIKUI = 0;

while (!$recordSet->EOF) {


    $id = $recordSet->fields["id"];
    $isClient = $recordSet->fields["client"];
    $ID = $recordSet->fields["id"];
    $creditor_num = $recordSet->fields["ClientNum"];
    $name = $recordSet->fields["CreditorName"];
    $hov = $recordSet->fields["Hov"];
    $address = $recordSet->fields["Address"];
    $zikui = $recordSet->fields["Zikui"];
    $hakafa = $recordSet->fields["Hakafa"];

    $q = "";
    if ($sDate) {
        $q.=" and day >= '$sDateSQL' ";
    }
    if ($eDate) {
        $q.=" and day <= '$eDateSQL' ";
    }

    $sql = "select
			round(sum(amount * (case when hov=0 then 1 else 0 end) ),2) as zikui,
			round(sum(amount * (case when hov=1 then 1 else 0 end) ),2) as hov
			from creditorpayments c
			where creditor_id = $id $q and status=1";
    $zik = $conn->Execute($sql);
    if ($zik === false) {
        log_error($sql);
    }
    $hov1 = $zik->fields["hov"];
    $zikui1 = $zik->fields["zikui"];
    $hov = $hov + $hov1;
    $zikui = $zikui + $zikui1;

    $total = $hov - $zikui - $hakafa;

    if ($only && $total <= 0) {
        $recordSet->MoveNext();
        continue;
    }

    $HOV+=$hov;
    $ZIKUI+=$zikui;
    $HAKAFA+=$hakafa;

    // alternate the colors
    if ($count == 0) {
        $count = $count + 1;
    } else {
        $count = 0;
    }



    echo "<tr style='cursor:hand' onclick='s=openReport(\"rep_credit.php?coupon=" . $creditor_num . "&sDate=$sDate&eDate=$eDate\");try{s.focus()}catch(e){}'  id='tr$ID'>";
    if ($HasWritePermissions) {
        echo "<td align=center onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" class=\"row3_$count\">";
        if ($isClient) {
            echo "<input type=checkbox id=CHECK name=CHECK value='$ID' onclick='window.event.cancelBubble=true'>";
        } else {
            echo "&nbsp;";
        }
        echo "</td>";
    }
    echo "<td onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" class=\"row3_$count\">";
    if ($HasWritePermissions && $isClient) {
        echo "<a onclick='event.cancelBubble=true;' href=\"javascript:wopen('../stock/cppro/main.php?service=suppliers&bd_event=edit_record&record_id=$id&cid=$id','edit',800,500)\">";
    }
    echo "$creditor_num</a>&nbsp;</td>";
    echo "<td onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" class=\"row3_$count\">";
    if ($HasWritePermissions && $ID) {
        echo "<a onclick='event.cancelBubble=true;' href=\"javascript:wopen('../stock/cppro/main.php?service=suppliers&bd_event=edit_record&record_id=$id&cid=$id','edit',800,500)\">";
    }
    if (!$isClient) {
        echo "<span style='color:red'>";
    }
    echo "$name</a>&nbsp;</td>";
    echo "<td nowrap onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" class=\"row3_$count\">" . $recordSet->fields["Address"] . "&nbsp;</td>";
    echo "<td nowrap onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" class=\"row3_$count\">" . $recordSet->fields["HomePhone"] . "&nbsp;</td>";
    echo "<td nowrap onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" class=\"row3_$count\">" . $recordSet->fields["CellPhone"] . "&nbsp;</td>";

    echo "<td nowrap onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" class=\"row3_$count money\"><span dir=ltr>" . number_format($hov, 2) . "</span>&nbsp;</td>";
    echo "<td nowrap onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" class=\"row3_$count money \"><span dir=ltr>" . number_format($zikui, 2) . "</span>&nbsp;</td>";
    echo "<td nowrap onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" class=\"row3_$count money \"><span dir=ltr>" . number_format($hakafa, 2) . "</span>&nbsp;</td>";
    echo "<td nowrap onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" class=\"row3_$count money \"><span dir=ltr>" . number_format($hov - $zikui - $hakafa, 2) . "</span>&nbsp;</td>";

    echo "<td onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" nowrap class=\"row3_$count none\" >";
    echo"<a onclick='event.cancelBubble=true;s=openReport(\"rep_credit.php?coupon=" . $creditor_num . "&sDate=$sDate&eDate=$eDate\");try{s.focus()}catch(e){};event.cancelBubble=true;' ><img  style='cursor:hand' src='" . $imgPath . "table_view.gif' width=16 height=16 alt='פרוט לפי ימים' border=0></a>";
    echo "&nbsp;<a onclick='event.cancelBubble=true;s=openReport(\"../stock/rep_cheshbonit.php?coupon=" . $creditor_num . "&couponsdate=" . dateToSQL($sDate) . "&couponedate=" . dateToSQL($eDate) . "\");try{s.focus()}catch(e){};event.cancelBubble=true;' ><img  style='cursor:hand' src='" . $imgPath . "note_view.gif' width=16 height=16 alt='פרוט חשבוניות' border=0></a>";
    if ($HasWritePermissions && $isClient) {
        echo "&nbsp;
				<a onclick='event.cancelBubble=true' href=\"javascript:wopen('cp/main.php?service=creditorpayments&cid=$id','edit');\"><img  style='cursor:hand' src='" . $imgPath . "money_envelope.gif' width=16 height=16 alt='תשלומים' border=0></a>";
    }
    echo "</td>";

    $b = $body;
    $f = $numformat;
    $worksheet->write("A" . $rrow, " " . $creditor_num, $b);
    $worksheet->write("B" . $rrow, $name, $b);
    $worksheet->write("C" . $rrow, " " . $recordSet->fields["Address"], $f);
    $worksheet->write("D" . $rrow, " " . $recordSet->fields["HomePhone"], $f);
    $worksheet->write("E" . $rrow, " " . $recordSet->fields["CellPhone"], $f);
    $worksheet->write("F" . $rrow, $hov, $f);
    $worksheet->write("G" . $rrow, $zikui, $f);
    $worksheet->write("H" . $rrow, $hakafa, $f);
    $worksheet->write("I" . $rrow, $hov - $zikui - $hakafa, $f);
    $rrow++;

    echo "</tr>\r\n\r\n";
    $recordSet->MoveNext();
} // end while

$worksheet->write("E" . ($rrow), "סה\"כ", $numformatb);
$worksheet->write("F" . ($rrow), $HOV, $numformatb);
$worksheet->write("G" . ($rrow), $ZIKUI, $numformatb);
$worksheet->write("H" . ($rrow), $HAKAFA, $numformatb);
$worksheet->write("I" . ($rrow), $HOV - $ZIKUI - $HAKAFA, $numformatb);

$workbook->close();
copy($fname, "../../tmp/" . $xlsfilename . "_" . $userID . ".xls");
unlink($fname);
?>

    <tr class=money>
        <td class="row3_1" colspan=<?= $HasWritePermissions ? 6 : 5 ?>><strong>סה"כ</strong></td>
        <td class="row3_1" money nowrap><b dir=ltr><?= number_format($HOV, 2) ?></b></td>
        <td class="row3_1" money nowrap><b dir=ltr><?= number_format($ZIKUI, 2) ?></b></td>
        <td class="row3_1" money nowrap><b dir=ltr><?= number_format($HAKAFA, 2) ?></b></td>
        <td class="row3_1" money nowrap><b dir=ltr><?= number_format($HOV - $ZIKUI - $HAKAFA, 2) ?></b></td>
        <td class="row3_1 none" >&nbsp;</td>
    </tr>
</table>
</div>

</td></tr></table>

<P>
</P>

<script>
    function checkall(v){
        for(i=0;i<document.all("CHECK").length;i++){
            if (document.all("CHECK")[i].name=="CHECK")
            {
                document.all("CHECK")[i].checked = v;
            }
        }
    }

    function zeroHov(){
        var checked="-1";
        for(i=0;i<document.all("CHECK").length;i++){
            if (document.all("CHECK")[i].name=="CHECK" && document.all("CHECK")[i].value != -1)
            {
                if (document.all("CHECK")[i].checked){
                    checked+=","+document.all("CHECK")[i].value;
                }
            }
        }
        if (checked=="-1"){
            alert("אין לקוחות מסומנים");
        }
        else{
            s = showModalDialog("zero.php?ids="+checked,"","dialogWidth:430px;dialogHeight:180px;center:yes;status:no;help:no;resizable:no");
            if (s!=-1){
                document.location.reload();
            }
        }

    }
</script>
<?php
    include("$config[template_path]/admin_bottom.html");
    $conn->Close(); // close the db connection
?>