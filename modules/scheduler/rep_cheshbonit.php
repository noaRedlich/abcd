<style>
.z {color:gray}
.b {font-weight:bold}
th {background-color:silver}
</style>
<?php

	$sugIska = array(
		1=>"����",
		2=>"�����",
		3=>"�����",
		4=>"������",
		6=>"�����",
		8=>"�������",
		9=>"������ �������"
	);
	
	$company = array(
		1=>"����'",
		2=>"���",
		3=>"������",
		4=>"����",
		5=>"JCB",
		6=>"�����"
	);
	
	$matbea = array(
		1 =>"���",
		2 =>"����",
		4 =>"����� �����",
		8 =>"����� ���"
	);
	
	$ishur = array(
		0 =>'��� �����',
		1 =>'��"�',
		2 =>'���� �����',
		3 =>'����'	
	);
	
	$typeIska = array(
		1=>'����',
		2=>'������',
		3=>'������',
		51=>'����� ����',
		52=>'�����',
		53=>'����� �����'
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
					#".$chn_num." �����: ".$transData->fields["TranDate"].", ���: ".(substr($transData->fields["TranTime"],0,5));
					if ($transData->fields["IsRefund"]){
						echo "(�����)";
					}
				echo "</td>
				<td>
					���� ����: ".number_format($transData->fields["TranAmount"],2);
				if ($transData->fields["TranDiscount"]||$transData->fields["TranCashDiscount"])	{
					if ($transData->fields["TranDiscount"]){
						$discount = $transData->fields["TranDiscount"];
						echo " ����:".number_format($transData->fields["TranDiscount"])."%";
						echo " ��\"�:".number_format($transData->fields["TranAmount"] - ($transData->fields["TranAmount"]*$transData->fields["TranDiscount"]/100),2);
					}
					else{
						$discount = $transData->fields["TranCashDiscount"];
						echo " ����:".number_format($transData->fields["TranCashDiscount"],2)."";
						echo " ��\"�:".number_format($transData->fields["TranAmount"] - $transData->fields["TranCashDiscount"],2);
					}
				}
				echo "</td></tr><tr><td colspan=2>";
				
				//components
				$components = $conn->Execute("select c.*,l.Title from transactioncomponents c left outer join listingsDB l on c.listing_id = l.id where c.trans_id  = $transid order by c.id");
				if ($components === false){log_error($sql);}
				if (!$components->EOF){
					echo "<table width=100% border=1 bordercolor=silver style='border-collapse:collapse'>
					<tr bgcolor=#efefef>
						<td >�����</td>
						<td >�� ����</td>
						<td >����</td>
						<td >����</td>
						<td >����</td>
						<td >����</td>
						<td >��\"�</td>
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
									echo "  ".$transData->fields["TranDiscount"]."% (����)";
									$total = $total - ($total*$transData->fields["TranDiscount"]/100);
								}
								elseif ($transData->fields["TranCashDiscount"]){
									$TranDiscount = $transData->fields["TranCashDiscount"]*100/$transData->fields["TranAmount"];
									echo "  ".number_format($TranDiscount,2)."% (����)";
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
						<td width=20%>��� �����</td>
						<td width=15%>����</td>
						<td width=65%>���� �����</td>
					</tr>";
					while (!$payments->EOF){
						echo "<tr>";
						switch($payments->fields["PaymID"]){
							case 1:
								echo "<td valign=top>�����</td>";
								echo "<td valign=top>".number_format($payments->fields["CashSum"],2)."</td><td>&nbsp;</td>";
								break;
							case 2:
								echo "<td valign=top>�����</td>";
								echo "<td valign=top>".number_format($payments->fields["ChequeSum"],2)."</td>";
								echo "<td>�'� ��' ".$payments->fields["ChequeNumber"].
									", ".substr($payments->fields["PayDate"],0,10).
									", ����� ".$payments->fields["BankNo"]." / ".$payments->fields["BankDeptNo"]." / ".$payments->fields["BankCntNo"].
									"</td>";
								break;
							case 3:
								echo "<td valign=top>����</td>";
								echo "<td valign=top>".number_format($payments->fields["CouponSum"],2)."</td>";
								echo "<td> ��' ���� ".$payments->fields["CouponNumber"]."</td>";
								break;
							case 4:
								echo "<td valign=top>���� ��</td>";
								echo "<td valign=top>".number_format($payments->fields["FrnCurrSum"],2)."</td>";
								echo "<td>: ��� ����";
									switch($payments->fields["CurrencyID"]){
										case "1": echo '���� ���"�';break;
										case "2": echo '���� �������';break;
										case "5": echo '���� �������';break;
										case "6": echo '�� ����';break;
										case "8": echo '���� ����';break;
										case "9": echo '��� �����';break;
										case "10": echo '��� �������';break;
										case "11": echo '��� ���';break;
										case "13": echo '���� �������';break;
										case "14": echo ' ���� ���"�';break;
										case "19": echo '����';break;
										case "20": echo '���� �����';break;
										case "21": echo '���� �����';break;
										case "22": echo '���� �����';break;
										case "23": echo '���� ������';break;
										case "25": echo '���� �������';break;
										case "26": echo '���� �������';break;
										case "27": echo '���� �������';break;
									}
								echo ", ��� ". number_format($payments->fields["TCourse"],2);
								echo "</td>";
								break;
							case 5:
								echo "<td valign=top>����� �����</td>";
								echo "<td valign=top>".number_format($payments->fields["CreditCardSum"],2)."</td>";
								echo "<td>";
								echo " ��' �����: ". "*******".substr($payments->fields["CardNum"],strlen($payments->fields["CardNum"])-4);
								echo ". ����: ". substr($payments->fields["ExpDate"],0,strlen($payments->fields["ExpDate"])-2)."/".substr($payments->fields["ExpDate"],strlen($payments->fields["ExpDate"])-2);
								echo ". ����: ". $company[$payments->fields["CompanyNum"]];
								echo ". ���� ����: ". $sugIska[$payments->fields["CreditTerms"]];
								echo "<br>";
								echo "����: ". $matbea[$payments->fields["Currency"]];
								echo ". �����: ". $ishur[$payments->fields["AuthorizCode"]];
								echo ". ��� ����: ". $typeIska[$payments->fields["TranType"]];
								echo "<br>";
								if ($payments->fields["NumPayments"]){
									echo ". ��' �������: ". $payments->fields["NumPayments"];
								}
								if ($payments->fields["FirstPayment"]){
									echo ". ����� �����: ".number_format($payments->fields["FirstPayment"],2)."";
								}
								if ($payments->fields["OtherPayment"]){
									echo ". ������� �����: ".number_format($payments->fields["OtherPayment"],2)."";
								}

								echo "</td>";
								break;
						}
						echo "</tr>";
						
						$payments->MoveNext();
					}
				
					if ($transData->fields["tChange"]!=0){
						echo "<tr><td colspan=10> ����: ".number_format($transData->fields["tChange"],2)." </td></tr>";
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