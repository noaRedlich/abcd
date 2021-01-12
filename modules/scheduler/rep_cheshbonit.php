<style>
.z {color:gray}
.b {font-weight:bold}
th {background-color:silver}
</style>
<?php

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
	if (!$mode){
		include("include/common.php");
		if (!loginCheck('User'))exit;
		global $action, $id, $cur_page, $lang, $conn, $config;
		include("$config[template_path]/admin_top.html");
	}

	if (($_GET["sDate"] || $_GET["ids"] || $mode) && $saction != "sendreport"){
		$saction="go";
	}
	
	if ($ids){
		$sDate = date("d/m/Y");
		$eDate = $sDate;
	}
	
	if (!$sDate){
		$firstday = mktime(0,0,0,date("m"),1,date("Y"));
		$sDate = date("d/m/Y",strtotime("",$firstday));
		$eDate = date("d/m/Y",strtotime("-1 day",strtotime("+ 1 month",$firstday)));
	}
	
	$asDate = explode("/",$sDate);
	$aeDate = explode("/",$eDate);
	
	$startdate = mktime(23,59,59,$asDate[1],$asDate[0],$asDate[2]);
	$enddate = mktime(23,59,59,$aeDate[1],$aeDate[0],$aeDate[2]);
	$startDay = date("Y/m/d",$startdate);
	$endDay = date("Y/m/d",$enddate);
	
	
	?>
	
	<?if(!$mode){
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
		<?$reporttitle = $lang["report_cheshbonit"];?>
		<td nowrap>
		<strong style='font-size:12pt'><?=$reporttitle?></strong>
		</td><td width=99% style='border:inset 1'>
		
		<?if (!$ids){?>
		
		<?=$lang["from"]?> <input size=6 name=sDate value="<?=$sDate?>"> <?=$lang["to"]?> <input size=6 name=eDate value="<?=$eDate?>"> 
		
	
		<select name=stock>
		<option value=""><?=$lang["all_points"]?>
		<?while(!$stocks->EOF){?>
			<option value="<?=$stocks->fields["ID"]?>" <?=($stock==$stocks->fields["ID"])?"selected":""?>><?=$stocks->fields["StockName"]?>
			<?$stocks->MoveNext();
		}?>
		</select>
		
		<?=$language["search_by_number"]?> <input name=number size=10 maxlength=10 value="<?=$number?>">
		
		<input type=submit value=" <?=$lang["show"]?> ">
		<br>
		<?}?>
		
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
	<?}?>
	<?	
	if ($saction=="sendreport"){
		$rbody = strip_tags(stripslashes($reportbody),"<table><tr><td><th><b>");
		sendReport($reporttitle,$rbody,$sendmode);
		echo "<center><strong style='color:green'>".$lang["report_sent"]."</strong></center>";
		echo stripslashes($reportbody);
	}
	elseif ($saction=="go"){
	
	echo "<div ><table id=REPORTTABLE dir=$dir   width=100% border=0 cellpadding=2>";
	
	$day = $startdate;
	if ($number){
		$day=$enddate;
	}
	
	while ($day <= $enddate){

		$sqldate = date("Y-m-d",$day);
		$opdate = date("d/m/Y",$day);
		
		if ($number){
			$qq = " and journalNum * 10000 + tranNum = ".intval($number);
		}
		else{
			$qq = " and  trandate = '$sqldate' ";
			if ($stock){
				$qq.="and t.stock_id=$stock";
			}
		}
		
		if (!$ids){
			$sql = "select 
				t.*,p.journalNum from transactions t inner join transactionpackages p on p.id = t.package_id
				where 
					t.user_id = $userID
					$qq
				";
		}	
		else{
			$sql = "select 
				t.*,p.journalNum from transactions t inner join transactionpackages p on p.id = t.package_id
				where 
					t.id in ($ids)
				";
		}

		$transData = $conn->Execute($sql);
		if ($transData === false){log_error($sql);}

		while (!$transData->EOF){
			$transid = $transData->fields["ID"];
			$chn_num = str_pad( ($transData->fields["journalNum"])*10000 +  $transData->fields["TranNum"] , 8,"0",PAD_LEFT);
			echo "<tr>
			<td>
				<table width=100% border=1 bgcolor=white bordercolor=black style='border-collapse:collapse' cellpadding=2>
				<tr bgcolor=silver>
				<td width=50%>
					#".$chn_num." תאריך: ".$transData->fields["TranDate"].", שעה: ".(substr($transData->fields["TranTime"],0,5));
					if ($transData->fields["IsRefund"]){
						echo "(זיכוי)";
					}
				echo "</td>
				<td>
					סכום עסקה: ".number_format($transData->fields["TranAmount"],2);
				if ($transData->fields["TranDiscount"]||$transData->fields["TranCashDiscount"])	{
					if ($transData->fields["TranDiscount"]){
						$discount = $transData->fields["TranDiscount"];
						echo " הנחה:".number_format($transData->fields["TranDiscount"])."%";
						echo " סה\"כ:".number_format($transData->fields["TranAmount"] - ($transData->fields["TranAmount"]*$transData->fields["TranDiscount"]/100),2);
					}
					else{
						$discount = $transData->fields["TranCashDiscount"];
						echo " הנחה:".number_format($transData->fields["TranCashDiscount"],2)."";
						echo " סה\"כ:".number_format($transData->fields["TranAmount"] - $transData->fields["TranCashDiscount"],2);
					}
				}
				echo "</td></tr><tr><td colspan=2>";
				
				//components
				$components = $conn->Execute("select c.*,l.Title from transactioncomponents c left outer join listingsDB l on c.listing_id = l.id where c.trans_id  = $transid order by c.id");
				if ($components === false){log_error($sql);}
				if (!$components->EOF){
					echo "<table width=100% border=1 bordercolor=silver style='border-collapse:collapse'>
					<tr bgcolor=#efefef>
						<td >ברקוד</td>
						<td >שם מוצר</td>
						<td >כמות</td>
						<td >מחיר</td>
						<td >סכום</td>
						<td >הנחה</td>
						<td >סה\"כ</td>
					</tr>";
					while (!$components->EOF){
						echo "
							<tr>
								<td width=20% nowrap>".$components->fields["PluCode"]."</td>
								<td width=20% nowrap>".$components->fields["Title"]."</td>
								<td width=7% dir=ltr align=right>".$components->fields["StockAmount"]."</td><td width=8% dir=ltr align=right>".number_format($components->fields["CompAmount"]/$components->fields["StockAmount"],2)."</td>
								<td width=15%>".number_format($components->fields["CompAmount"],2)."</td>
								<td width=15%>";
								if ($components->fields["CompDiscount"]){
									echo "".number_format($components->fields["CompDiscount"],0)."%";
									$total = $components->fields["CompAmount"] - ($components->fields["CompAmount"]*$components->fields["CompDiscount"]/100);
								}
								elseif ($components->fields["CompCashDiscount"]){
									echo "".number_format($components->fields["CompCashDiscount"],2)."";
									$total = $components->fields["CompAmount"] - $components->fields["CompCashDiscount"];
								}
								else{
									echo "";
									$total = $components->fields["CompAmount"];
								}
								
								//add transaction discount
								if ($transData->fields["TranDiscount"]){
									echo "  ".$transData->fields["TranDiscount"]."% (עסקה)";
									$total = $total - ($total*$transData->fields["TranDiscount"]/100);
								}
								elseif ($transData->fields["TranCashDiscount"]){
									$TranDiscount = $transData->fields["TranCashDiscount"]*100/$transData->fields["TranAmount"];
									echo "  ".number_format($TranDiscount,2)."% (עסקה)";
									$total = $total - ($total*$TranDiscount/100);
								}
								
								echo "</td>
								<td width=15%>".number_format($total,2)."</td>
								";
							"</tr>
						";
						$components->MoveNext();
					}
					echo "</table><table><tr><td></td></tr></table>";
				}
				
				//payments
				$payments = $conn->Execute("select p.* from transactionpayments p where p.trans_id  = $transid order by id");
				if ($payments === false){log_error($sql);}
				if (!$payments->EOF){
					echo "<table width=100% border=1 bordercolor=silver style='border-collapse:collapse'>
					<tr bgcolor=#efefef>
						<td width=20%>סוג תשלום</td>
						<td width=15%>סכום</td>
						<td width=65%>פרטי תשלום</td>
					</tr>";
					while (!$payments->EOF){
						echo "<tr>";
						switch($payments->fields["PaymID"]){
							case 1:
								echo "<td valign=top>מזומן</td>";
								echo "<td valign=top>".number_format($payments->fields["CashSum"],2)."</td><td>&nbsp;</td>";
								break;
							case 2:
								echo "<td valign=top>המחאה</td>";
								echo "<td valign=top>".number_format($payments->fields["ChequeSum"],2)."</td>";
								echo "<td>צ'ק מס' ".$payments->fields["ChequeNumber"].
									", ".substr($payments->fields["PayDate"],0,10).
									", חשבון ".$payments->fields["BankNo"]." / ".$payments->fields["BankDeptNo"]." / ".$payments->fields["BankCntNo"].
									"</td>";
								break;
							case 3:
								echo "<td valign=top>שובר</td>";
								echo "<td valign=top>".number_format($payments->fields["CouponSum"],2)."</td>";
								echo "<td> מס' שובר ".$payments->fields["CouponNumber"]."</td>";
								break;
							case 4:
								echo "<td valign=top>מטבע זר</td>";
								echo "<td valign=top>".number_format($payments->fields["FrnCurrSum"],2)."</td>";
								echo "<td>: קוד מטבע";
									switch($payments->fields["CurrencyID"]){
										case "1": echo 'דולר ארה"ב';break;
										case "2": echo 'פרנק שוויצרי';break;
										case "5": echo 'לירה שטרלינג';break;
										case "6": echo 'ין יפני';break;
										case "8": echo 'דולר קנדי';break;
										case "9": echo 'כתר שוודי';break;
										case "10": echo 'כתר נורווגי';break;
										case "11": echo 'כתר דני';break;
										case "13": echo 'דולר אוסטרלי';break;
										case "14": echo ' ראנד דרא"פ';break;
										case "19": echo 'יורו';break;
										case "20": echo 'דינר ירדני';break;
										case "21": echo 'לירה לבנון';break;
										case "22": echo 'לירה מצרית';break;
										case "23": echo 'לירה טורקית';break;
										case "25": echo 'לירה קפריסית';break;
										case "26": echo 'דולר הונקונג';break;
										case "27": echo 'דולר סינגפור';break;
									}
								echo ", שער ". number_format($payments->fields["TCourse"],2);
								echo "</td>";
								break;
							case 5:
								echo "<td valign=top>כרטיס אשראי</td>";
								echo "<td valign=top>".number_format($payments->fields["CreditCardSum"],2)."</td>";
								echo "<td>";
								echo " מס' כרטיס: ". "*******".substr($payments->fields["CardNum"],strlen($payments->fields["CardNum"])-4);
								echo ". תוקף: ". substr($payments->fields["ExpDate"],0,strlen($payments->fields["ExpDate"])-2)."/".substr($payments->fields["ExpDate"],strlen($payments->fields["ExpDate"])-2);
								echo ". חברה: ". $company[$payments->fields["CompanyNum"]];
								echo ". תנאי עסקה: ". $sugIska[$payments->fields["CreditTerms"]];
								echo "<br>";
								echo "מטבע: ". $matbea[$payments->fields["Currency"]];
								echo ". אישור: ". $ishur[$payments->fields["AuthorizCode"]];
								echo ". סוג עסקה: ". $typeIska[$payments->fields["TranType"]];
								echo "<br>";
								if ($payments->fields["NumPayments"]){
									echo ". מס' תשלומים: ". $payments->fields["NumPayments"];
								}
								if ($payments->fields["FirstPayment"]){
									echo ". תשלום ראשון: ".number_format($payments->fields["FirstPayment"],2)."";
								}
								if ($payments->fields["OtherPayment"]){
									echo ". תשלומים אחרים: ".number_format($payments->fields["OtherPayment"],2)."";
								}

								echo "</td>";
								break;
						}
						echo "</tr>";
						
						$payments->MoveNext();
					}
				
					if ($transData->fields["tChange"]!=0){
						echo "<tr><td colspan=10> עודף: ".number_format($transData->fields["tChange"],2)." </td></tr>";
					}
					echo "</table>";
				}	


				echo "</td></tr>";

				
				echo "</table>
			</td>
			</tr>";
			$transData->MoveNext();
		}

		
		 $day += (60*60*24);
	}
	
	echo "</table></div>";
	
	}
	
	if (!$mode){
		include("$config[template_path]/admin_bottom.html");
		$conn->Close(); // close the db connection
	}
?>