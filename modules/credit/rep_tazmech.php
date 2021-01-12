<style>
.z {color:gray}
.s {cursor:hand}
.b {font-weight:bold;cursor:hand}
.bt {font-weight:bold;}
th {background-color:silver}
</style>
<script>
function showChn(ids){
	ids1 = ids.replace(/-1/g,"").replace(/\,/g,"")
	if (ids1){
		openReport1("rep_cheshbonit.php?usr=<?=$usr?>&ids="+ids)
	}
}

function mov(e){
	if(e.className=="s"||e.className=="b ") e.style.backgroundColor='yellow';
}
function mou(e){
	e.style.backgroundColor='';
}

function openReport1(url){
	var ss="";
	s = window.open(url+'&simple=1','','top='+(window.screenTop+5)+',left='+(window.screenLeft+20)+',height=500,width=800,resizable=yes,scrollbars=yes,status=yes');
	s.focus();
}	

</script>
<?php

	$xlsfilename = "sales";

	$sugIska = array(
		1=>"רגיל",
		2=>"קרדיט",
		3=>"מיידי",
		4=>"מועדון",
		6=>"מיוחד",
		8=>"תשלומים",
		9=>"מועדון תשלומים"
	);
	
	$company = array(
		1=>"ישרא'",
		2=>"כאל",
		3=>"דיינרס",
		4=>"אמקס",
		5=>"JCB",
		6=>"לאומי"
	);
	
	$matbea = array(
		1 =>"שקל",
		2 =>"דולר",
		4 =>"הצמדה לדולר",
		8 =>"הצמדה מדד"
	);
	
	$ishur = array(
		0 =>'ללא אישור',
		1 =>'שב"א',
		2 =>'חברת אשראי',
		3 =>'ידני'	
	);
	
	$typeIska = array(
		1=>'רגיל',
		2=>'מאושרת',
		3=>'מאולצת',
		51=>'זיכוי רגיל',
		52=>'ביטול',
		53=>'זיכוי מאושר'
	);

	$simple=1;
	$page_subtitle = "מכירות ";
	include("include/common.php");
	if (!loginCheck('User'))exit;
	global $action, $id, $cur_page, $lang, $conn, $config;

	include("$config[template_path]/admin_top.html");
	
	$modes = array("isra"=>$lang["isracard"],"visa"=>$lang["visa"],"diners"=>$lang["diners"]);
	
	if ($_GET["sDate"] && $saction!="sendreport"){
		$saction="go";
	}
	
	if (!$sDate){
		$firstday = mktime(0,0,0,date("m"),1,date("Y"));
		$sDate = date("d/m/Y",strtotime("+0 day",$firstday));
		$eDate = date("d/m/Y",strtotime("-1 day",strtotime("+ 1 month",$firstday)));
	}
	
	$asDate = explode("/",$sDate);
	$aeDate = explode("/",$eDate);
	
	$startdate = mktime(23,59,59,$asDate[1],$asDate[0],$asDate[2]);
	$enddate = mktime(23,59,59,$aeDate[1],$aeDate[0],$aeDate[2]);
	
	$stocks = $conn->Execute("select * from listingsStocks where Status=1 and user_id = $userID order by binary StockName");
	if ($stocks === false){log_error($sql);}
	?>
	<script>
	function PrintReport(){
		document.getElementById("Query").style.display = "none";
		window.print();
		document.getElementById("Query").style.display = "";
	}
	</script>
	<body>
	<table cellpadding=5 border=0 width=100%>
	<tr>
	<form name=F method=post>
	<input type=hidden name=mode value=<?=$mode?>>
	<input type=hidden name=usr value=<?=$usr?>>
	<input type=hidden name=rmodule value=<?=$rmodule?>>
	<td nowrap>
	<strong style='font-size:12pt'>
	<?$reporttitle = $lang["report_tazrim_mechirot"]?>
	<?if ($usr){$reporttitle.="<br>".$username;}?>
	<?=$reporttitle?>
	</strong>
	</td><td width=99% style='border:inset 1'>

	<?=$lang["from"]?> <input size=6 name=sDate id=sDate value="<?=$sDate?>">
<img align=absmiddle style="cursor:hand" src='<?=$imgPath?>calendar.gif' onclick='ShowCalendar("F.sDate")
'>
 
	
	<?=$lang["to"]?> <input size=6 name=eDate id=eDate value="<?=$eDate?>">
<img align=absmiddle style="cursor:hand" src='<?=$imgPath?>calendar.gif' onclick='ShowCalendar("F.eDate")
'>
 
	
	<select name=stock>
	<option value=""><?=$lang["all_points"]?>
	<?while(!$stocks->EOF){?>
		<option value="<?=$stocks->fields["ID"]?>" <?=($stock==$stocks->fields["ID"])?"selected":""?>><?=$stocks->fields["StockName"]?>
		<?$stocks->MoveNext();
	}?>
	</select>
	
	<input type=submit value=" <?=$lang["show"]?> " style=";cursor:hand;padding:0 0 0 10;background:url(<?=$imgPath?>refresh.gif);background-repeat:no-repeat;background-position:left top" >
	<?if ($saction=="go" || $saction=="sendreport"){
		require("sendreport.php");
	}?>
	<input type=hidden name=saction value=go>
	<input type=hidden name=reportbody value="">
	<input type=hidden name=sendmode value="">

	</td>
	</form>
	</tr>
	</table>
	<?	
	if ($saction=="sendreport"){
		$rbody = strip_tags(stripslashes($reportbody),"<table><tr><td><th><b>");
		sendReport($reporttitle,$rbody,$sendmode);
		echo "<center><strong style='color:green'>".$lang["report_sent"]."</strong></center>";
		echo stripslashes($reportbody);
	}
	elseif ($saction=="go"){

	$worksheet->set_column('A:H', 12);
	$headings = array($lang["date"],$lang["cash"],$lang["cheque"],$lang["isracard"],$lang["visa"],$lang["diners"],"סה\"כ אשראי",$lang['total']);
	$worksheet->write_row('A'.($rrow++), $headings, $heading);
	;
	
	echo "<div ><table id=REPORTTABLE dir=$dir border=1 bgcolor=white width=100% bordercolor=black style='border-collapse:collapse' cellpadding=2>";
	echo "<tr><th rowspan=2>".$lang["date"]."</th>";
	echo "<th rowspan=2>&nbsp;".$lang["cash"]."&nbsp;</th>";
	echo "<th rowspan=2>&nbsp;".$lang["cheque"]."&nbsp;</th>";
	echo "<th colspan=4>".$lang["creditcard"]."</th>";
	echo "<th rowspan=2>&nbsp;".$lang['total']."&nbsp;</th>";
	echo "</tr>";
	echo "<tr>";
	/*

	<th>&nbsp;IsraCard&nbsp;</th>
	<th>&nbsp;Visa Kal&nbsp;</th>
	<th>&nbsp;Diners&nbsp;</th>
	<th>&nbsp;AMEX&nbsp;</th>
	<th>&nbsp;JCB&nbsp;</th>
	<th>&nbsp;Visa Leumi&nbsp;</th>
	<th>&nbsp;Subtotal&nbsp;</th>
	*/
	echo "
	
	<th>&nbsp;".$lang[isracard]."&nbsp;</th>
	<th>&nbsp;".$lang[visa]."&nbsp;</th>
	<th>&nbsp;".$lang[diners]."&nbsp;</th>
	<th>&nbsp;".$lang['total']."&nbsp;</th>
	";
	echo"</tr>";
	

	$day = $startdate;
	$total_cash =0;
	$total_cheque =0;
	$total_cc_isra =0;
	$total_cc_kal = 0;
	$total_cc_diners = 0;
	$total_cc_amex = 0;
	$total_cc_jcb = 0;
	$total_cc_leumi = 0;	
	$total = 0;
	
	
	while ($day <= $enddate){
		//get cash payments
		$sqldate = date("Y-m-d",$day);
		$operday = date("d/m/Y",$day);
		$cash = "";
		$cheque = "";
		$cc_isra = "";
		$cc_kal = "";
		$cc_diners = "";
		$cc_amex = "";
		$cc_jcb = "";
		$cc_leumi = "";	
		
		$CashTrans = "";
		$ChequeTrans = "";
		$VisaTrans = "";
		$IsraTrans = "";
		$DinersTrans = "";
		
		
		//Cash
		
		$q="";
		if ($stock){
			$q = " and t.stock_id = $stock";
		}
		
		$sql = "select t.ID,
		(case when IsRefund 
		then 
			((CashSum-tChange) * -1)
		else 
			(CashSum-tChange)
		end) 
		as CashSum 
		from transactionpayments p, transactions t where t.ID = p.trans_id $q and t.tranDate = '".$sqldate."' and CashSum <> 0 and PaymntCount>0 and t.user_id = $userID";
		$rs = $conn->Execute($sql);
		if ($rs === false){log_error($sql);}
		if (!$rs->EOF){	
			$cash = sum($rs,"CashSum",&$CashTrans);
		}

		//Cheque
		
		$sql = "select t.ID,(case when IsRefund then ChequeSum*-1 else ChequeSum end) as ChequeSum 
		from transactionpayments p, transactions t where t.ID = p.trans_id $q 
		and t.tranDate = '".$sqldate."' 
		and ChequeSum <> 0 and t.user_id = $userID";
		$rs = $conn->Execute($sql);
		if ($rs === false){log_error($sql);}
		if (!$rs->EOF){	
			$cheque = sum($rs,"ChequeSum",&$ChequeTrans);
		}
		
		//Isra
		
		$sql = "select t.ID,(case when IsRefund then CreditCardSum*-1 else CreditCardSum end) as CreditCardSum from transactionpayments p, transactions t where t.ID = p.trans_id $q and t.tranDate = '".$sqldate."' and CreditCardSum <> 0 and CompanyNum in (1,4,5) and  t.user_id = $userID";
		$rs = $conn->Execute($sql);
		if ($rs === false){log_error($sql);}
		if (!$rs->EOF){	
			$cc_isra = sum($rs,"CreditCardSum",&$IsraTrans);
		}	
		
		//Visa
		
		$sql = "select t.ID,(case when IsRefund then CreditCardSum*-1 else CreditCardSum end) as CreditCardSum from transactionpayments p, transactions t where t.ID = p.trans_id $q and t.tranDate = '".$sqldate."' and CreditCardSum <> 0 and CompanyNum in (2,6) and  t.user_id = $userID";
		$rs = $conn->Execute($sql);
		if ($rs === false){log_error($sql);}
		if (!$rs->EOF){	
			$cc_kal = sum($rs,"CreditCardSum",&$VisaTrans);
		}				
		
		//Diners
		
		$sql = "select t.ID,(case when IsRefund then CreditCardSum*-1 else CreditCardSum end) as CreditCardSum from transactionpayments p, transactions t where t.ID = p.trans_id $q and t.tranDate = '".$sqldate."' and CreditCardSum <> 0 and CompanyNum in (3) and  t.user_id = $userID";
		$rs = $conn->Execute($sql);
		if ($rs === false){log_error($sql);}
		if (!$rs->EOF){	
			$cc_diners = sum($rs,"CreditCardSum",&$DinersTrans);
		}				
		
		
		//Output
		
		//if ($IsraTrans) $IsraTrans = substr($IsraTrans,1);
		//if ($VisaTrans) $VisaTrans = substr($VisaTrans,1);
		//if ($DinersTrans) $DinersTrans = substr($DinersTrans,1);
		
		$cc = $cc_isra + $cc_kal + $cc_diners + $cc_amex + $cc_jcb + $cc_leumi;
		
		$CCTrans = (($IsraTrans)?$IsraTrans:-1) .",".
				   (($VisaTrans)?$VisaTrans:-1) .",".
				   (($DinersTrans)?$DinersTrans:-1);					
		
		$Total = $cash  + $cheque + $cc;

		$TotalTrans = (($CCTrans)?$CCTrans:-1) .",".
					  (($ChequeTrans)?$ChequeTrans:-1) .",".
					  (($CashTrans)?$CashTrans:-1);
		
		$total_cash +=$cash;
		$total_cheque +=$cheque;
		$total_cc_isra +=$cc_isra;
		$total_cc_kal += $cc_kal;
		$total_cc_diners += $cc_diners;
		$total_cc_amex += $cc_amex;
		$total_cc_jcb += $cc_jcb;
		$total_cc_leumi+= $cc_leumi;	
		$total += $Total;
		$total_cc += $cc;
		//results


		echo "
		<tr align=right>
			<td>$operday</td>";
			$ids="";
			echo"
			<td class=".((!$cash)?"z":"s")." onmouseover='mov(this)' onmouseout='mou(this)' onclick='showChn(\"$CashTrans\")'>&nbsp;".number_format($cash,2)."&nbsp;</td>
			<td class=".((!$cheque)?"z":"s")." onmouseover='mov(this)' onmouseout='mou(this)' onclick='showChn(\"$ChequeTrans\")'>&nbsp;".number_format($cheque,2)."&nbsp;</td>
			";
			echo "<td class='".((!$cc_isra)?"z":"s")."' onmouseover='mov(this)' onmouseout='mou(this)' onclick='showChn(\"$IsraTrans\")'>&nbsp;".number_format($cc_isra,2)."&nbsp;</td>";
			$ids=$IsraTrans;
			echo "<td class='".((!$cc_kal)?"z":"s")."' onmouseover='mov(this)' onmouseout='mou(this)' onclick='showChn(\"$VisaTrans\")'> &nbsp;".number_format($cc_kal,2)."&nbsp;</td>";
			$ids=$VisaTrans;
			echo "<td class='".((!$cc_diners)?"z":"s")."' onmouseover='mov(this)' onmouseout='mou(this)'  onclick='showChn(\"$DinersTrans\")'>&nbsp;".number_format($cc_diners,2)."&nbsp;</td>";
			$ids=$DinersTrans;
			/*
			<td class=".((!$cc_amex)?"z":"").">&nbsp;".number_format($cc_amex,2)."&nbsp;</td>
			<td class=".((!$cc_jcb)?"z":"").">&nbsp;".number_format($cc_jcb,2)."&nbsp;</td>
			<td class=".((!$cc_leumi)?"z":"").">&nbsp;".number_format($cc_leumi,2)."&nbsp;</td>
			*/
			if(!$mode){
				echo"
				<td class=".((!$cc)?"z":"s")."  onmouseover='mov(this)' onmouseout='mou(this)' onclick='showChn(\"$CCTrans\")' >&nbsp;".number_format($cc,2)."&nbsp;</td>
				<td class='b ".((!$Total)?"z":"")."' onmouseover='mov(this)' onmouseout='mou(this)' onclick='showChn(\"$TotalTrans\")' >&nbsp;".number_format($Total,2)."&nbsp;</td>
				";
			}
			echo "</tr>"; 
			
			$b=$body;
			$f=$numformat;
			$worksheet->write("A".$rrow,$operday,$b);
			$worksheet->write("B".$rrow,$cash,$b);
			$worksheet->write("C".$rrow,$cheque,$f);
			$worksheet->write("D".$rrow,$cc_isra,$f);
			$worksheet->write("E".$rrow,$cc_kal,$b);
			$worksheet->write("F".$rrow,$cc_diners,$f);
			$worksheet->write("G".$rrow,$cc,$f);
			$worksheet->write("H".$rrow,$Total,$f);			
			$rrow++;
			
			
		//$day += (60*60*24);
        $day= strtotime("+1 day",$day);
	}
	echo "
	<tr>
		<td class=mode><b>".$lang['total']."</b></td>";
		echo "<td class=bt>&nbsp;".number_format($total_cash,2)."&nbsp;</td>";
		echo "<td class=bt>&nbsp;".number_format($total_cheque,2)."&nbsp;</td>";
		echo "<td class='bt'>&nbsp;".number_format($total_cc_isra,2)."&nbsp;</td>";
		echo "<td class='bt'>&nbsp;".number_format($total_cc_kal,2)."&nbsp;</td>";
		echo "<td class='bt'>&nbsp;".number_format($total_cc_diners,2)."&nbsp;</td>";
		/*
		<td class=b>&nbsp;".number_format($total_cc_amex,2)."&nbsp;</td>
		<td class=b>&nbsp;".number_format($total_cc_jcb,2)."&nbsp;</td>
		<td class=b>&nbsp;".number_format($total_cc_leumi,2)."&nbsp;</td>
		*/
		echo"
			<td class=bt>&nbsp;".number_format($total_cc,2)."&nbsp;</td>
			<td class=bt>&nbsp;".number_format($total,2)."&nbsp;</td>
			";
	echo "</tr>";
	echo "</table>";
	
	$worksheet->write("A".($rrow),"סה\"כ",$numformatb);
	$worksheet->write("B".($rrow),$total_cash,$numformatb);
	$worksheet->write("C".($rrow),$total_cheque,$numformatb);	
	$worksheet->write("D".($rrow),$total_cc_isra,$numformatb);	
	$worksheet->write("E".($rrow),$total_cc_kal,$numformatb);	
	$worksheet->write("F".($rrow),$total_cc_diners,$numformatb);	
	$worksheet->write("G".($rrow),$total_cc,$numformatb);				
	$worksheet->write("H".($rrow),$total,$numformatb);		
		
	}
	
	$workbook->close();

	copy($fname,"../../tmp/".$xlsfilename."_".$userID.".xls");
	
	include("$config[template_path]/admin_bottom.html");
	$conn->Close(); // close the db connection
	
	
	function sum($rs,$field,&$ids){
		$s=0;
		$ids="";
		$rs->MoveFirst();
		while(!$rs->EOF){
			$s+=$rs->fields[$field];
			if (!strpos(" ".$ids,",".$rs->fields["ID"])){ 
				$ids.=",".$rs->fields["ID"];
			}
			$rs->MoveNext();
		}
		$ids = substr($ids,1);
		return $s;
	}
?>