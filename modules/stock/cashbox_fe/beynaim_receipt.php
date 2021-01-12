<!DOCTYPE html>
<html lang="he">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	 <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>


</head>
<?
$json=$_REQUEST[json];
//print_r($json);
$data=json_decode($json);
//print_r($data);
//print_r($data);
//$products=$data->products;
//$products=json_decode($products);

//$payments=$data->payments;
//$payments=json_decode($payments);

//print_r($products[0]);

?>
<body dir="rtl">
<table width="270px" style="display:table">
	<tr><th colspan="2">ידע טופ</th></tr>
	<tr><th colspan="2">רבי עקיבא 54</th></tr>
	<tr><th colspan="2">בני ברק</th></tr>
	<tr><th colspan="2">דוח X <? echo date("d/m/y H:i")." ".$_REQUEST[kupa_num]; ?></th></tr>
	<tr><td colspan="2"><hr></td></tr>
<tr><td style="min-width:180px;">מספר עסקאות:</td><td><?=$_REQUEST[cash_num]?></td></tr>	
<tr><td> פריטים שנמכרו:</td><td><?=$_REQUEST[numprod_hova]-$_REQUEST[numprod_zchut]?></td></tr>
<tr><td> פריטים שחויבו:</td><td><?=$_REQUEST[numprod_hova]?></td></tr>	
<tr><td> פריטים שזוכו:</td><td><?=$_REQUEST[numprod_zchut]?></td></tr>	
<tr><td>דמי מחזור:</td><td><?=$_REQUEST[start_cash]?></td></tr>	
<tr><td>סה"כ הוספת מזומן לקופה:</td><td><?=$_REQUEST[mezumanin]?></td></tr>	
<tr><td>סה"כ הוצאת מזומן מהקופה:</td><td><?=$_REQUEST[mezumanout]?></td></tr>
	<tr><td colspan="2"><hr></td></tr>
<tr><td>מזומן:</td><td><?=$data->cash?></td></tr>	
<tr><td>חיובים:</td><td><?=$data->cash1->hova?></td></tr>	
<tr><td>זיכויים:</td><td><?=$data->cash1->zicuy?></td></tr>
<tr><td colspan="2"><hr></td></tr>

<tr><td>המחאה:</td><td><?=$data->cheque?></td></tr>	
<tr><td>חיובים:</td><td><?=$data->cheque1->hova?></td></tr>	
<tr><td>זיכויים:</td><td><?=$data->cheque1->zicuy?></td></tr>
<tr><td colspan="2"><hr></td></tr>

<tr><td>אשראי:</td><td><?=$data->credit?></td></tr>	
<tr><td>חיובים:</td><td><?=$data->credit1->hova?></td></tr>	
<tr><td>זיכויים:</td><td><?=$data->credit1->zicuy?></td></tr>
<tr><td colspan="2"><hr></td></tr>

<tr><td>הקפה:</td><td><?=$data->akafa->general?></td></tr>	
<tr><td>מזומן:</td><td><?=$data->akafa->cash?></td></tr>	
<tr><td>אשראי:</td><td><?=$data->akafa->credit?></td></tr>
<tr><td>המחאה:</td><td><?=$data->akafa->cheque?></td></tr>	
<tr><td>כרטיס מתנה:</td><td><?=$data->akafa->prepaid?></td></tr>
<tr><td colspan="2"><hr></td></tr>

<tr><td>פריפייד:</td><td><?=$data->prepaid?></td></tr>	
<tr><td>מזומן:</td><td><?=$data->prepaid->cash?></td></tr>	
<tr><td>אשראי:</td><td><?=$data->prepaid->credit?></td></tr>
<tr><td>המחאה:</td><td><?=$data->prepaid->cheque?></td></tr>	
<tr><td>כרטיס מתנה:</td><td><?=$data->prepaid->prepaid?></td></tr>
<tr><td colspan="2"><hr></td></tr>

<tr><td>סה"כ תקבולים:</td><td><?=$data->all?></td></tr>	


		
</table>
<input type="button" id="print" value="הדפס" onclick="$('#print').hide();window.print();" />
</body>
</html>