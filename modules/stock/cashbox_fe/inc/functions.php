<?
//print_r($_REQUEST);
$noheader=1;
require("conn.inc");
$rootdir=$GOCONFIG->file_storage_path;
$stock=$_REQUEST[stock];
$rootdir.="$username/POS/$stock";
$target_image_dir="../../../officefiles/".$username."/_MlaitekPro";
/*if(isset($_REQUEST['credit'])){
	include "../../ipay.php";
	$data=json_decode($_REQUEST['credit']);
	print_r($data);

	//$ipay=new ipay();
	//$x=$ipay->login();
	//function transaction($cardNumber="36409611152639",$cardExpirationDate="1507",$amount="20",$cvv="442",$numberOfPayments="1"){
	//$x=$ipay->transaction($data->ashray_numcard,$data->ashray_tokef,$data->amount,$data->ashray_cvv,$data->ashray_tz,$data->ashray_tashlumim);
}*/
// "&ashray_numcard="+ashray_numcard+"&ashray_tokef="+ashray_tokef+"&ashray_cvv="+ashray_cvv+"&ashray_tz="+ashray_tz
if(isset($_REQUEST['client_points'])){
	$sql = "select * from clients_points where client_id = ".$_REQUEST['client_id']." or client_id = 999999 and from_sum < ".$_REQUEST['client_points']." and to_sum > ".$_REQUEST['client_points']." order by client_id , from_sum desc";
	//file_put_contents("point_client.log", $sql);
	$result= mysql_query($sql);
	if($result){
		$row = mysql_fetch_array($result);
		if($row['percent_nis']=="nis"){
			die($row['points']);
		}
		else{   
			die((float)$row['points']*(float)$_REQUEST['client_points']/100.0);
		}
	}
	
	die("0");
} 
if(isset($_REQUEST['using_client_points'])){
	//echo "feigy".$_REQUEST['prepaid_sum']."-".$_REQUEST['prepaid_num'];
	$id=$_REQUEST['client_num'];
$sql = "select client_point_value from vcx00.users where id = $userID";
//echo $sql;
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
$client_point_value = (float)$row['client_point_value'];
	//echo $id;
	$sql="select sum(points) as points from using_clients_points where client_id='$id'";
	$result=mysql_query($sql);
	//echo $sql;
	$num=mysql_num_rows($result);
	if($num==0){
		echo "";
		die();
	}
	$row=mysql_fetch_array($result);
	$points = (float)$row["points"];
	if((float)$_REQUEST['using_client_points']<(float)$row["points"]*$client_point_value){
		$points = (float)$_REQUEST['using_client_points'] / $client_point_value;
	} 
	echo $points*$client_point_value.";".$points;
	
	die();

}
if(isset($_REQUEST['print_save_doc'])){
	$stock=$_REQUEST['stock'];
	$cash_num=$_REQUEST['cash_num'];
	$journal=$_REQUEST['journal'];
	$journal_id=$_REQUEST['journal_id'];
	$json=json_decode($_REQUEST['json']);
	$type=$_REQUEST['type'];
	if($type=='hachlafa'){
		$sql= "INSERT INTO `documents_copy` ( `journal`, `cash_num`, `stock`,`type` ,`html`, `json31`, `json48`) VALUES ( '$journal_id', '$cash_num', '$stock', '$type','".str_replace("'","",$json->html)."', '".str_replace("'","",json_encode($json->json31))."', '".str_replace("'","",json_encode($json->json48))."');";

	}
	else{
		$sql= "INSERT INTO `documents_copy` ( `journal`, `cash_num`, `stock`,`type` ,`html`, `json31`, `json48`) VALUES ( '$journal', '$cash_num', '$stock', '$type','".str_replace("'","",$json->html)."', '".str_replace("'","",json_encode($json->json31))."', '".str_replace("'","",json_encode($json->json48))."');";

	}

	mysql_query($sql);
		//echo $sql;
	echo mysql_error();
	die();
}

/*lc 22/03/2016 save exchange print document offline */
if(isset($_REQUEST['print_offline_exchange1'])){
	$json=json_decode($_REQUEST['print_offline_exchange1']);
	foreach ($json as $key=>$value) {
		     $stock=$value->stock;
			 $cash_num=$value->trannum;
			 $journal=$value->journal;
			 $journal_id=$value->journal_id;
			 $type=$value->type;
			 $json=json_decode($value->json);
			 $string_html=$json->html;
			 $string_json31=$json->json31;
			 $string_json48=$json->json48;
			 $sql="select * from  `documents_copy` where `cash_num`=$cash_num and `journal`=$journal_id and `stock`=$stock";
			 $result= mysql_query($sql);
			 // $num_row= mysql_num_rows($result);
			 // if($num_row==0){
				if(mysql_num_rows($result)==0 && $type=='hachlafa'){
				   $sql= "INSERT INTO `documents_copy` ( `journal`, `cash_num`, `stock`,`type` ,`html`, `json31`, `json48`)
				          VALUES ( 
				          '$journal_id', '$cash_num', '$stock', '$type','".str_replace("'","",$string_html)."', '".
				           str_replace("'","",$string_json31)."', '".str_replace("'","",$string_json48)."');";
					mysql_query($sql);
				}
			 // }
    }
	die();
}

/*lc 22/03/2016 save invoice-(cheshbonit) print document offline */
if(isset($_REQUEST['print_offline_invoice11'])){
	$json=json_decode($_REQUEST['print_offline_invoice11']);
	foreach ($json as $key=>$value) {
		     $stock=$value->stock;
			 $cash_num=$value->trannum;
			 $journal=$value->journal;
			 $journal_id=$value->journal_id;
			 $type=$value->type;
			 $json=json_decode($value->json);
			 $string_html=$json->html;
			 file_put_contents("funccc.log", $json->html);
			 $string_json31=$json->json31;
			 $string_json48=$json->json48;
			// file_put_contents("lea1.txt", " stock= ".$stock." cash_num= ".$cash_num." journal= ".$journal.
			 //                               " journal_id= ".$journal_id." type= ".$type." html= ".$string_html.
			 //                               " json31= ".$string_json31." json48= ".$string_json48);
			 $sql="SELECT  `ID` 
                  FROM  `vcx_weberp`.`listingsStocks`   
                  WHERE  `TerminalID` =".$stock;
			 //file_put_contents("lea_select_stock_id.txt",$sql);
			 $result= mysql_query($sql);
			 while($row = mysql_fetch_array($result)) {
                   $stock_id= $row['ID'];
			 }
			// file_put_contents("lea_num_stock.txt",$stock_id);
		     $sql="SELECT * FROM `transactions` WHERE `TranNum`=".$cash_num." and `package_id`=".$journal_id." and `stock_id`=".$stock_id;
		     //file_put_contents("lea_select_stock.txt",$sql);
			 echo 'sql select: ' . $sql;
		     $result= mysql_query($sql);
		     $empty_html= mysql_num_rows($result);
			 //file_put_contents("empty_html.txt",$empty_html);
		     if($empty_html==1){
				$sql="UPDATE `transactions` SET `html`='".str_replace("'","",$string_html)."' ,`json48`='".str_replace("'","",$string_json48)."' ,`json31`='".str_replace("'","",$string_json31).
				"' WHERE `TranNum`=".$cash_num." and `package_id`=".$journal_id." and `stock_id`=".$stock_id;
				// file_put_contents("lea_update_html.txt",$sql);
				 $result= mysql_query($sql);
				 mysql_num_rows($result);
				 echo 'sql update: ' . $sql;
			 }
    }
	die();
}

if(isset($_REQUEST['print_save'])){
	//file_put_contents('aa1print.txt', 'begin');
	$journal_id=$_REQUEST[journal_id];
	$stock=$_REQUEST[stock];
	$trannum=$_REQUEST[trannum]-1;
	$sql="select ID from vcx_weberp.listingsStocks where user_ID='$userID' and TerminalId='$stock'";
		$result=mysql_query($sql);
		$row=mysql_fetch_array($result);
		$stockid=$row[ID];
		$json=json_decode($_REQUEST['json']);
			$json->html=str_replace("מקור","העתק",$json->html);
		//print_r(json_decode(str_replace("'","",json_encode($json->json31))));

	$sql= "UPDATE `transactions` SET `json31`='".str_replace("'","",json_encode($json->json31))."',`json48`='".str_replace("'","",json_encode($json->json48))."',`html`='".str_replace("'","",$json->html)."' WHERE `package_id` =$journal_id
AND  `TranNum` =$trannum
AND  `stock_id` =$stockid";
	mysql_query($sql);
	//file_put_contents('aa1print1.txt', $sql);
	echo $sql;
	echo mysql_error();
	die();
}
if(isset($_REQUEST['print_save_a4'])){
	//file_put_contents('aa1print.txt', 'begin');
	$journal_id=$_REQUEST[journal_id];
	$stock=$_REQUEST[stock];
	$trannum=$_REQUEST[trannum]-1;
	$sql="select ID from vcx_weberp.listingsStocks where user_ID='$userID' and TerminalId='$stock'";
		$result=mysql_query($sql);
		$row=mysql_fetch_array($result);
		$stockid=$row[ID];
		$json=$_REQUEST['print_string'];
			$json=str_replace("מקור","העתק",$json);
		//print_r(json_decode(str_replace("'","",json_encode($json->json31))));

	$sql= "UPDATE `transactions` SET `json31`='".str_replace("'","",json_encode($json))."',`json48`='".str_replace("'","",json_encode($json))."',`html`='".str_replace("'","",$json)."' WHERE `package_id` =$journal_id
AND  `TranNum` =$trannum
AND  `stock_id` =$stockid";
	mysql_query($sql);
	//file_put_contents('aa1print1.txt', $sql);
	echo $sql;
	echo mysql_error();
	die();
}
if(isset($_REQUEST['actions2'])){
	mysql_query("CREATE TABLE IF NOT EXISTS `actions2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `desc` text CHARACTER SET hebrew NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=hebrew AUTO_INCREMENT=1 ;");
	mysql_query("INSERT INTO `actions2`(`desc`) VALUES ('".$_REQUEST['actions2']."')");
	echo mysql_error();
	die();
}
if(isset($_REQUEST['actions'])){
	mysql_query("
	CREATE TABLE IF NOT EXISTS `actions` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  `action` varchar(255) NOT NULL,
	  `type` varchar(255) NOT NULL,
	  `el_id` varchar(255) NOT NULL,
	  `el_class` varchar(255) NOT NULL,
	  `tran` VARCHAR( 255 ) NOT NULL,
	  `tash` VARCHAR( 255 ) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	mysql_query("INSERT INTO `actions`(`action`, `type`, `el_id`, `el_class`, `tran`, `tash`) 
	VALUES ('".$_REQUEST['action']."','".$_REQUEST['type']."','".$_REQUEST['idd']."','".$_REQUEST['clas']."','".$_REQUEST['tran']."','".$_REQUEST['tash']."')");
	echo mysql_error();
	die();
	//actions:1,time:time,action:action,type:type,idd:$(this).attr('id'),clas:$(this).attr('class')
}
if(isset($_REQUEST['check_shovar_num'])){
	$check_shovar_num=$_REQUEST['check_shovar_num'];
	$check_shovar_num=intval($check_shovar_num);

	$sql="select id,CouponSum from `transactionpayments` where CouponNumber='$check_shovar_num' and (voucher_used=0 or voucher_used is NULL) and CouponSum<0";
	//echo $sql;

	$result= mysql_query($sql);
	//file_put_contents("shovar.log", mysql_num_rows($result).$sql);
	if(mysql_num_rows($result) > 0){
		//file_put_contents("ss.log", mysql_num_rows($result));
		$row=mysql_fetch_array($result);
		$x=$row['CouponSum'];
		$x*=-1;
		echo $x;
		//file_put_contents("feigy.log", $row['CouponSum']);
		/*$sql="update `transactionpayments` set voucher_used=1 where CouponNumber='$check_shovar_num'";*/ /*sk  02/09 cancel the updating here */
		$result= mysql_query($sql);

	}
	else{
		echo 0;
	}

	die();
}
/*sk  03/09 update shovarzikuy to used*/
if(isset($_REQUEST['update_shovar_to_used'])){
	$shovar_num = json_decode($_REQUEST['update_shovar_to_used']);
	foreach ($shovar_num as $key => $value) {
		$sql="update `transactionpayments` set voucher_used=1 where CouponNumber='$value'";
		$result= mysql_query($sql);
		}
	die();
}
/*sk  06/09 get current date from the server*/
if(isset($_REQUEST['update_current_date'])){
	$cur_hour = date('H:i');
	/*lc 03/04/2016 get current time from the server for popup Time clock */
	$cur_time= date('H:i:s');
	$arr['hour']=$cur_hour;
	$arr['time']=$cur_time;
	echo json_encode($arr);
	die();
}
/*sk  27/01 get current date from the server*/
if(isset($_REQUEST['update_current_date111'])){
	$cur_date = date('d/m/Y');
	echo $cur_date;
	die();
}
/*sk  08/12/15 get current date from the server*/
if(isset($_REQUEST['update_current_time'])){
	$cur_time = date('H:i:s');
	//file_put_contents('$filename.txt', $cur_time);
	echo $cur_time;
	die();
}
/*sk  08/12/15 get current date from the server*/
if(isset($_REQUEST['update_current_time1'])){
	$cur_time1 = date('d/m/Y');
	echo $cur_time1;
	die();
}
if(isset($_REQUEST['ajaxandroid'])){
	//file_put_contents("credita.log", $_REQUEST['ajaxandroid']);
	die();
}
if(isset($_REQUEST['checkInternet'])){
	echo "1";
	die();
}
if(isset($_REQUEST['get_emv_details'])){
    switch ($userterminal2255) {
        case 'mdm':
            $terminalid11='50000297';
            $username11='mohamd';
            $password11='aGXUEawZ';
            break;

        case 'shenoy':
            $terminalid11='50000760';
            $username11='einav';
            $password11='QUrrs81c';
        break;
        case 'noch':
            $terminalid11='50000759';
            $username11='zion';
            $password11='02gTFy8S';
        break;
        case 'idit5':
            $terminalid11='50000130';
            $username11='idit';
            $password11='TkwIUg5U';
        break;
        default:
            $terminalid11='50000130';
            $username11='idit';
            $password11='TkwIUg5U';
        break;
    }
    /*$sql = "select * from vcx_weberp.listingsStocks where Status=1 and user_id = $userID and TerminalID=$stock order by binary StockName";

    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    $stock=$row['ID'];*/

        /*sk 15/12/2015  get ipay details from db table userdata per stock*/

    $sql = "select `IpayPassword`,`IpayUserName`,`IpayTerminalId`,`IpayCom` from vcx_weberp.listingsStocks where Status=1 and user_id = $userID and TerminalID=$stock";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    if($row){
        $ipay_password=$row['IpayPassword'];
        $ipay_username=$row['IpayUserName'];
        $ipay_terminal=$row['IpayTerminalId'];
        $ipay_com=$row['IpayCom'];
    }

    /*sk 15/12/2015  get ipay details from db table userdata*/
    if(!$ipay_password || !$ipay_username || !$ipay_terminal){
        $sql = "select `IpayPassword`,`IpayUserName`,`IpayTerminalId`,`IpayCom` from vcx_weberp.userdata  WHERE `office_user_id`=$userID ";

        $result_query=mysql_query($sql);
        $num=mysql_num_rows($result_query);

        if( $num > 0 ){
            $row=mysql_fetch_array($result_query);
            $ipay_terminal=$row['IpayTerminalId'];
            $ipay_username=$row['IpayUserName'];
            $ipay_password=$row['IpayPassword'];
             $ipay_com=$row['IpayCom'];
        }


    }
echo '{"IpayTerminalId":"'.$ipay_terminal.'","IpayUserName":"'.$ipay_username.'","IpayPassword":"'.$ipay_password.'","IpayCom":"'.$ipay_com.'"}';
   die();
}
if(isset($_REQUEST['track2']) && isset($_REQUEST['amount']) || isset($_REQUEST['ashray_numcard'])){
	/*echo "error"."התקשר";
	die();*/

	if(isset($_REQUEST['ApprovalNumber'])){
		$approvalNumber=$_REQUEST['ApprovalNumber'];
	}
	else{
		$approvalNumber="";
	}
	if(isset($_REQUEST['track2'])&&$_REQUEST['track2']!=""&&$_REQUEST['track2']!="undefined"){
		$track2=$_REQUEST['track2'];
		$cardNumber="";
		$ashray_tokef="";
		$ashray_cvv="";
		$ashray_tz="";
		$transactionCode="SIGNATURE_ONLY_TRANSACTION";
	}
	else{
		$track2="";
		$cardNumber=$_REQUEST['ashray_numcard'];
		$ashray_tokef=$_REQUEST['ashray_tokef'];
		$ashray_tokef =substr((string)$ashray_tokef,2,2).substr((string)$ashray_tokef,0,2);
		$ashray_cvv=$_REQUEST['ashray_cvv'];
		$ashray_tz=$_REQUEST['ashray_tz'];
		$transactionCode="PHONE_TRANSACTION";
	}
	$amount=$_REQUEST['amount'];
	$pay_num=$_REQUEST['pay_num'];
	$ashray_f_credit=$_REQUEST['ashray_f_credit'];
	$creditTerms="REGULAR_CREDIT";
	$transactionType="DEBIT";
    if($_REQUEST[ipay_emv]==1){
        $transactionType="CHARGE";

    }
	if($ashray_f_credit>0){
		$creditTerms="FIXED_INSTALMENT_CREDIT";

		$pay_num=$ashray_f_credit;
		$first="";
		$other="";
	}
	if($amount<0){
		$transactionType="CREDIT";
		$amount=abs($amount);
	}
	if($pay_num<1){
		$pay_num="";
		$first="";
		$other="";
	}
	if($pay_num > 1 && !$ashray_f_credit > 0){

		$creditTerms="PAYMENTS";
		$pay_first=$_REQUEST['pay_first'];
		if($pay_first<1){
			$first=$amount-round($amount/$pay_num,2)*($pay_num-1);
			$other=round($amount/$pay_num,2);
		}
		else{
			//852.15
			//250
			//2
			$first=$pay_first;
			$other=round(($amount-$first)/($pay_num-1),2);
			$first=$amount-$other*($pay_num-1);
		}
		$pay_num-=1;
	}


	/*echo $track2;
	$track2=explode("\n", $track2);

	print_r( $track2);
	die();*/
	//file_put_contents('klor.txt', $userID);
	switch ($userterminal2255) {
		case 'mdm':
			$terminalid11='50000297';
			$username11='mohamd';
			$password11='aGXUEawZ';
			break;
		case 'shenoy':
			$terminalid11='50000760';
			$username11='einav';
			$password11='QUrrs81c';
		break;
		case 'noch':
			$terminalid11='50000759';
			$username11='zion';
			$password11='02gTFy8S';
		break;
		case 'idit5':
			$terminalid11='50000130';
			$username11='idit';
			$password11='TkwIUg5U';
		break;
		default:
			$terminalid11='50000130';
			$username11='idit';
			$password11='TkwIUg5U';
		break;
	}
	/*$sql = "select * from vcx_weberp.listingsStocks where Status=1 and user_id = $userID and TerminalID=$stock order by binary StockName";

	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	$stock=$row['ID'];*/

		/*sk 15/12/2015  get ipay details from db table userdata per stock*/

	$sql = "select `IpayPassword`,`IpayUserName`,`IpayTerminalId` from vcx_weberp.listingsStocks where Status=1 and user_id = $userID and TerminalID=$stock";

	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	if($row){
		$ipay_password=$row['IpayPassword'];
		$ipay_username=$row['IpayUserName'];
		$ipay_terminal=$row['IpayTerminalId'];

	}

	/*sk 15/12/2015  get ipay details from db table userdata*/
	if(!$ipay_password || !$ipay_username || !$ipay_terminal){
		$sql = "select `IpayPassword`,`IpayUserName`,`IpayTerminalId` from vcx_weberp.userdata  WHERE `office_user_id`=$userID ";

		$result_query=mysql_query($sql);
		$num=mysql_num_rows($result_query);

		if( $num > 0 ){
			$row=mysql_fetch_array($result_query);
			$ipay_terminal=$row['IpayTerminalId'];
			$ipay_username=$row['IpayUserName'];
			$ipay_password=$row['IpayPassword'];
		}


	}

	/*if($userterminal2255=='mdm'){
		$terminalid11='50000297';
		$username11='mohamd';
		$password11='aGXUEawZ';
	}
	/*if($userterminal2255=='idit5'){
		$terminalid11='50000130';
		$username11='idit';
		$password11='TkwIUg5U';
	}
	if($userterminal2255=='shenoy'){
		$terminalid11='50000760';
		$username11='einav';
		$password11='QUrrs81c';
	}
	if($userterminal2255=='noch'){
		$terminalid11='50000759';
		$username11='zion';
		$password11='02gTFy8S';
	}*/
	/*else{
		$terminalid11='50000130';
		$username11='idit';
		$password11='TkwIUg5U';
	}*/
	if($_REQUEST[ipay_emv]!=1){
	$postData = '{"loginDetails":{"terminalId": "'.$ipay_terminal.'","username": "'.$ipay_username.'","password": "'.$ipay_password.'"}, 
	"transactionCommitting": { "track2": "'.$track2.'",
	"cardNumber":"'.$cardNumber.'",     
	"cardExpirationDate":"'.$ashray_tokef.'",    
	"cvv":"'.$ashray_cvv.'",
	"cardHolderId":"'.$ashray_tz.'",
	 "transactionType": "'.$transactionType.'",
	 "transactionCode": "SIGNATURE_ONLY_TRANSACTION", "amount": "'.$amount.'", "numberOfPayments": "'.$pay_num.'",
	  "firstPaymentAmount": "'.$first.'", "paymentAmount": "'.$other.'", "cardHolderName": "",  
	  "comments": "", "currency": "NIS_CURRENCY", "creditTerms": "'.$creditTerms.'", "customerFirstName": "Erez",
	"customerLastName": "Attia", "customerPhoneNumber": "", "customerEmail": "",
		"requestType": "QUERY_EXECUTION_IN_ACCORDANCE_WITH_THE_TERMINAL", "approvalNumber": "'.$approvalNumber.'", 
		"userData": "", "creditCardCompanyAdditionalData": "" } }';

	//file_put_contents('aaayyyyya222222ashray.txt', $postData);



	$ch = curl_init('https://credix.co.il/api/transactions/auth');


	curl_setopt_array($ch, array(
	    CURLOPT_POST => TRUE,
	    CURLOPT_RETURNTRANSFER => TRUE,
	    CURLOPT_HTTPHEADER => array(
	        'X-CREDIX-API-KEY: dgw6RM6y6gg5OnpBY2k7BM7B395mj7zs',
	        'Content-Type: application/json'
	    ),
	    CURLOPT_POSTFIELDS => $postData
	));
	}
else{
    $postData = '{"loginDetails":{"terminalId": "'.$ipay_terminal.'","username": "'.$ipay_username.'","password": "'.$ipay_password.'"}, 
    "transactionData": { "clientInputPan": "'.$track2.$cardNumber.'",         
    "cardExpirationDate":"'.$ashray_tokef.'",    
    "transactionType": "'.$transactionType.'",
    "transactionCode": "PHONE_TRANSACTION", 
    "amount": "'.$amount.'",
    "numberOfPayments": "'.$pay_num.'",
      "firstPaymentAmount": "'.$first.'",
       "paymentAmount": "'.$other.'",
       "indexPayment":"NONE",
    "currency": "ILS",
    "creditTerms": "'.$creditTerms.'",
    
    "mti":"REGULAR",
    "cardHolderId":"'.$ashray_tz.'",
    "cvv2":"'.$ashray_cvv.'",
    "requestType": "QUERY_EXECUTION_IN_ACCORDANCE_WITH_THE_TERMINAL",
    "authCodeManpik":"INVALID",
     "approvalNumber": "'.$approvalNumber.'" },
     "additionalData":{"cardHolderName":"",
     "customerFirstName":"",
     "customerLastName":"",
     "customerPhoneNumber":"",
     "customerEmail":"",
     "comments":""}}';

    //file_put_contents('aaayyyyya222222ashray.txt', $postData);



    $ch = curl_init('https://credix.co.il/api/transactions/emv/auth');


    curl_setopt_array($ch, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array(
            'X-CREDIX-API-KEY: dgw6RM6y6gg5OnpBY2k7BM7B395mj7zs',
            'Content-Type: application/json'
        ),
        CURLOPT_POSTFIELDS => $postData
    ));
}

	$response = curl_exec($ch);
	//file_put_contents('aa00.txt', 'fffff');
	//file_put_contents('aa014sarapostdata.txt',$postData);
	//file_put_contents('aa014sarapoonse.txt', $response);

	if($response === FALSE){

	    echo "error";
	}

	/*


	 * */


	//if($ipay_username=='einav' && $ipay_password=='QUrrs81c'){
		//file_put_contents("shenoy_log_".date("Ymdhis").".log", $postData."\n".$response);
		//file_put_contents("shenoy_log_po".date("Ymdhis").".log", $postData);
		//file_put_contents("shenoy_log_res".date("Ymdhis").".log",$response);
	//}


	 file_put_contents("../log_credit/".date("Ymdhis").".log", $postData."\n".$response);

	$responseData = json_decode($response, TRUE);
	//print_r($responseData);
	file_put_contents("000000000.log", $response);
	//file_put_contents("feigy2.log", $postData);
	//echo "<hr>";
	if( curl_getinfo($ch, CURLINFO_HTTP_CODE)==200){
		echo  $response;
	}else{
		echo "error";
        if($_REQUEST[ipay_emv]!=1){
		if($responseData['code']==1004){
			echo $responseData['code']." ".$responseData['message'].": ".$responseData['shvaErrorCode']." ";

		switch ($responseData['shvaErrorCode']) {
			case '001':echo "חסום.";break;
			case '003':echo "התקשר לחברת האשראי. ";break;
			case '002':echo "גנוב.";break;
			case '004':echo "סירוב.";break;
			case '005':echo "מזויף.";break;
			case '006':echo "ת.ז. או CVV שגויים. ";break;
			case '008':echo "תקלה בבניית מפתח גישה לקובץ חסומים.";break;
			case '009':echo "לא הצליח להתקשר, התקשר לחברת האשראי.";break;
			case '010':echo "תוכנית הופסקה עפ“י הוראת המפעיל (ESC) או COM PORT לא ניתן לפתיחה (WINDOWS).";break;
			case '015':echo "אין התאמה בין המספר שהוקלד לפס המגנטי.";break;
			case '016':echo "נתונים נוספים אינם או ישנם בניגוד להגדרות המסוף.";break;
			case '017':echo "לא הוקלדו 4 ספרות האחרונות.";break;
			case '019':echo "רשומה בקובץ INT_IN קצרה מ- 16 תווים.<br> נא להקליד ידנית!";break;
			case '020':echo "קובץ קלט (INT_IN) לא קיים.";break;
			case '021':echo "קובץ חסומים (NEG) לא קיים או לא מעודכן - בצע שידור או בקשה לאישור עבור כל עסקה.";break;
			case '022':echo "אחד מקבצי פרמטרים או ווקטורים לא קיים.";break;
			case '023':echo "קובץ תאריכים (DATA) לא קיים.";break;
			case '025':echo "הפרש בימים בקליטת חסומים גדול מדי -בצע שידור או בקשה לאישור עבור כל עסקה. ";break;
			case '024':echo "קובץ אתחול (START) לא קיים.";break;
			case '026':echo "הפרש דורות בקליטת חסומים גדול מידי – בצע שידור או בקשה לאישור עבור כל עסקה.";break;
			case '037':echo "שגיאה בתשלומים - סכום עסקה צריך להיות שווה תשלום ראשון + (תשלום קבוע כפול מס' ";break;
			case '036':echo "פג תוקף. ";break;
			case '035':echo "כרטיס לא רשאי לבצע עסקה עם סוג אשראי זה. ";break;
			case '034':echo "כרטיס לא רשאי לבצע במסוף זה או אין אישור לעסקה כזאת. ";break;
			case '033':echo "כרטיס לא תקין. ";break;
			case '032':echo "תנועות ישנות בצע שידור או בקשה לאישור עבור כל עסקה. ";break;
			case '031':echo "מסוף מעודכן כרב ספק והוקלד גם מס' מוטב. ";break;
			case '030':echo "מסוף שאינו מעודכן כרב ספק/רב מוטב והוקלד מס' ספק/מס' מוטב. ";break;
			case '029':echo "מספר מוטב לא הוכנס למסוף המוגדר לעבודה כרב מוטב. ";break;
			case '028':echo "מספר מסוף מרכזי לא הוכנס למסוף המוגדר לעבודה כרב ספק. ";break;
			case '027':echo "כאשר לא הוכנס פס מגנטי כולו הגדר עסקה כעסקה טלפונית או כעסקת חתימה בלבד.";break;
			case '040':echo "מסוף שמוגדר כרב מוטב הוקלד מס' ספק. ";break;
			case '039':echo "סיפרת בקורת לא תקינה. ";break;
			case '038':echo "לא ניתן לבצע עסקה מעל תקרה לכרטיס לאשראי חיוב מיידי. תשלומים)";break;
			case '041':echo "מעל תקרה, אך קובץ הקלט מכיל הוראה לא לבצע שאילתא (J1,J2,J3 )";break;
			case '042':echo "חסום בספק, אך קובץ הקלט מכיל הוראה לא לבצע שאילתא (J1,J2,J3 )";break;
			case '043':echo "אקראית, אך קובץ הקלט מכיל הוראה לא לבצע שאילתא (J1,J2,J3 )";break;
			case '044':echo "מסוף לא רשאי לבקש אישור ללא עסקה, אך קובץ הקלט מכיל (J5).";break;
			case '045':echo "מסוף לא רשאי לבקש אישור ביוזמתו, אך קובץ הקלט מכיל (J6).";break;
			case '046':echo "יש לבקש אישור, אך קובץ הקלט מכיל הוראה לא לבצע שאילתא (J1,J2,J3 )";break;
			case '047':echo "יש לבקש אישור בשל בעיה הקשורה לקכ“ח אך קובץ הקלט מכיל הוראה לא לבצע שאילתא.";break;
			case '051':echo "מספר רכב לא תקין.";break;
			case '052':echo "מד מרחק לא הוקלד.";break;
			case '053':echo "מסוף לא מוגדר כתחנת דלק. (הועבר כרטיס דלק או קוד עסקה לא מתאים)";break;
			case '057':echo "לא הוקלד מספר תעודת זהות.";break;
			case '058':echo "לא הוקלד CVV2.";break;
			case '059':echo "לא הוקלדו מספר תעודת הזהות וה- CVV2.";break;
			case '060':echo "צרוף ABS לא נמצא בהתחלת נתוני קלט בזיכרון.";break;
			case '061':echo "מספר כרטיס לא נמצא או נמצא פעמיים.";break;
			case '063':echo "קוד עסקה לא תקין. ";break;
			case '062':echo "סוג עסקה לא תקין.";break;
			case '071':echo "חובה להקליד מספר סודי. ";break;
			case '070':echo "לא מוגדר מכשיר להקשת מספר סודי. ";break;
			case '069':echo "אורך הפס המגנטי קצר מידי. ";break;
			case '068':echo "לא ניתן להצמיד לדולר או למדד לסוג אשראי שונה מתשלומים. ";break;
			case '067':echo "קיים מספר תשלומים לסוג אשראי שאינו דורש זה. ";break;
			case '066':echo "קיים תשלום ראשון ו/או תשלום קבוע לסוג אשראי שונה מתשלומים. ";break;
			case '065':echo "מטבע לא תקין. ";break;
			case '064':echo "סוג אשראי לא תקין.";break;
			case '072':echo "קכ“ח לא זמין – העבר בקורא מגנטי.";break;
			case '073':echo "הכרטיס נושא שבב ויש להעבירו דרך הקכ“ח.";break;
			case '074':echo "דחייה – כרטיס נעול.";break;
			case '075':echo "דחייה – פעולה עם קכ“ח לא הסתיימה בזמן הראוי.";break;
			case '076':echo "דחייה – נתונים אשר התקבלו מקכ“ח אינם מוגדרים במערכת.";break;
			case '080':echo "הוכנס “קוד מועדון“ לסוג אשראי לא מתאים. ";break;
			case '077':echo "הוקש מספר סודי שגוי";break;
			case '099':echo "לא מצליח לקרוא/ לכתוב/ לפתוח קובץ TRAN.";break;
			case '101':echo "אין אישור מחברת אשראי לעבודה.";break;
			case '106':echo "למסוף אין אישור לביצוע שאילתא לאשראי חיוב מיידי.";break;
			case '107':echo "סכום העסקה גדול מידי – חלק למספר העסקאות.";break;
			case '113':echo "למסוף אין אישור לעסקה טלפונית. ";break;
			case '112':echo "למסוף אין אישור לעסקה טלפון/ חתימה בלבד בתשלומים. ";break;
			case '111':echo "למסוף אין אישור לעסקה בתשלומים. ";break;
			case '110':echo "למסוף אין אישור לכרטיס חיוב מיידי. ";break;
			case '109':echo "למסוף אין אישור לכרטיס עם קוד השרות 108.";break;
			case '587':echo "למסוף אין אישור לבצע עסקאות מאולצות.";break;
			case '123':echo "למסוף אין אישור לעסקת כוכבים/נקודות/מיילים לסוג אשראי זה. ";break;
			case '122':echo "למסוף אין אישור להצמדה למדד לכרטיסי חו“ל. ";break;
			case '121':echo "למסוף אין אישור להצמדה למדד. ";break;
			case '120':echo "למסוף אין אישור להצמדה לדולר. ";break;
			case '119':echo "למסוף אין אישור לאשראי אמקס קרדיט. ";break;
			case '118':echo "למסוף אין אישור לאשראי ישראקרדיט. ";break;
			case '117':echo "למסוף אין אישור לעסקת כוכבים/נקודות/מיילים. ";break;
			case '116':echo "למסוף אין אישור לעסקת מועדון. ";break;
			case '115':echo "למסוף אין אישור לעסקה בדולרים. ";break;
			case '114':echo "למסוף אין אישור לעסקה “חתימה בלבד“.";break;
			case '124':echo "למסוף אין אישור לאשראי קרדיט בתשלומים לכרטיסי ישראכרט";break;
			case '131':echo "כרטיס לא רשאי לבצע עסקת כוכבים/נקודות/מיילים. ";break;
			case '130':echo "כרטיס לא רשאי לבצע עסקת מועדון. ";break;
			case '129':echo "למסוף אין אישור לבצע עסקת זכות מעל תקרה. ";break;
			case '128':echo "למסוף אין אישור לקבל כרטיסי ויזה אשר מתחילים ב - 3.";break;
			case '127':echo "למסוף אין אישור לעסקת חיוב מיידי פרט לכרטיסי חיוב מיידי. ";break;
			case '126':echo "למסוף אין אישור לקוד מועדון זה. ";break;
			case '125':echo "למסוף איו אישור לאשראי קרדיט בתשלומים לכרטיסי אמקס";break;
			case '133':echo "כרטיס לא תקף על פי רשימת כרטיסים תקפים של ישראכרט. ";break;
			case '132':echo "כרטיס לא רשאי לבצע עסקאות בדולרים (רגילות או טלפוניות).";break;
			case '134':echo "כרטיס לא תקין עפ“י הגדרת המערכת (VECTOR1 של ישראכרט)- מס' הספרות בכרטיס- שגוי.";break;
			case '135':echo "כרטיס לא רשאי לבצע עסקאות דולריות עפ“י הגדרת המערכת (VECTOR1 של ישראכרט).";break;
			case '136':echo "הכרטיס שייך לקבוצת כרטיסים אשר אינה רשאית לבצע עסקאות עפ“י הגדרת המערכת (VECTOR20 של ויזה).";break;
			case '139':echo "מספר תשלומים גדול מידי על פי רשימת כרטיסים תקפים של ישראכרט. ";break;
			case '138':echo "כרטיס לא רשאי לבצע עסקאות בתשלומים על פי רשימת כרטיסים תקפים של ישראכרט. ";break;
			case '137':echo "קידומת הכרטיס (7 ספרות) לא תקפה עפ“י הגדרת המערכת (VECTOR21 של דיינרס).";break;
			case '140':echo "כרטיסי ויזה ודיינרס לא רשאים לבצע עסקאות מועדון בתשלומים.";break;
			case '141':echo "סידרת כרטיסים לא תקפה עפ“י הגדרת המערכת. (VECTOR5 של ישראכרט).";break;
			case '142':echo "קוד שרות לא תקף עפ“י הגדרת המערכת (VECTOR6 של ישראכרט).";break;
			case '143':echo "קידומת הכרטיס (2 ספרות) לא תקפה עפ“י הגדרת המערכת. (VECTOR7 של ישראכרט).";break;
			case '144':echo "קוד שרות לא תקף עפ“י הגדרת המערכת. (VECTOR12 של ויזה).";break;
			case '145':echo "קוד שרות לא תקף עפ“י הגדרת המערכת. (VECTOR13 של ויזה).";break;
			case '147':echo "כרטיס לא רשאי לבצע עסקאות בתשלומים עפ“י וקטור 31 של לאומיקארד. ";break;
			case '146':echo "לכרטיס חיוב מיידי אסור לבצע עסקת זכות.";break;
			case '152':echo "קוד מועדון לא תקין. ";break;
			case '151':echo "אשראי לא מאושר לכרטיסי חו“ל. ";break;
			case '150':echo "אשראי לא מאושר לכרטיסי חיוב מיידי. ";break;
			case '149':echo "כרטיס אינו רשאי לבצע עסקאות טלפוניות עפ“י וקטור 31 של לאומיקארד. ";break;
			case '148':echo "כרטיס לא רשאי לבצע עסקאות טלפוניות וחתימה בלבד עפ“י ווקטור 31 של לאומיקארד.";break;
			case '153':echo "כרטיס לא רשאי לבצע עסקאות אשראי גמיש (עדיף +30/) עפ“י הגדרת המערכת. (VECTOR21 של דיינרס).";break;
			case '154':echo "כרטיס לא רשאי לבצע עסקאות חיוב מיידי עפ“י הגדרת המערכת. (VECTOR21 של דיינרס).";break;
			case '155':echo "סכום המינמלי לתשלום בעסקת קרדיט קטן מידי.";break;
			case '156':echo "מספר תשלומים לעסקת קרדיט לא תקין.";break;
			case '157':echo "תקרה 0 לסוג כרטיס זה בעסקה עם אשראי רגיל או קרדיט.";break;
			case '158':echo "תקרה 0 לסוג כרטיס זה בעסקה עם אשראי חיוב מיידי.";break;
			case '159':echo "תקרה 0 לסוג כרטיס זה בעסקת חיוב מיידי בדולרים.";break;
			case '160':echo "תקרה 0 לסוג כרטיס זה בעסקה טלפונית.";break;
			case '161':echo "תקרה 0 לסוג כרטיס זה בעסקת זכות.";break;
			case '162':echo "תקרה 0 לסוג כרטיס זה בעסקת תשלומים.";break;
			case '163':echo "כרטיס אמריקן אקספרס אשר הונפק בחו“ל לא רשאי לבצע עסקאות בתשלומים.";break;
			case '164':echo "כרטיסי JCB רשאי לבצע עסקאות רק באשראי רגיל.";break;
			case '168':echo "למסוף אין אישור לעסקה דולרית עם סוג אשראי זה. ";break;
			case '167':echo "לא ניתן לבצע עסקת כוכבים/נקודות/מיילים בדולרים. ";break;
			case '166':echo "כרטיס מועדון לא בתחום של המסוף. ";break;
			case '165':echo "סכום בכוכבים/נקודות/מיילים גדול מסכום העסקה.";break;
			case '171':echo "לא ניתן לבצע עסקה מאולצת לכרטיס/אשראי חיוב מיידי. ";break;
			case '170':echo "סכום הנחה בכוכבים/נקודות/מיילים גדול מהמותר. ";break;
			case '169':echo "לא ניתן לבצע עסקת זכות עם אשראי שונה מהרגיל";break;
			case '175':echo "למסוף אין אישור להצמדה לדולר לאשראי זה. ";break;
			case '174':echo "למסוף אין אישור להצמדה למדד לאשראי זה. ";break;
			case '173':echo "עסקה כפולה. ";break;
			case '172':echo "לא ניתן לבטל עסקה קודמת (עסקת זכות או מספר כרטיס אינו זהה).";break;
			case '176':echo "כרטיס אינו תקף עפ“י הגדרת ה מערכת (וקטור 1 של ישראכרט).";break;
			case '200':echo "שגיאה יישומית. ";break;
			case '180':echo "בכרטיס מועדון לא ניתן לבצע עסקה טלפונית. ";break;
			case '179':echo "אסור לבצע עסקת זכות בדולר בכרטיס תייר. ";break;
			case '178':echo "אסור לבצע עסקת זכות בכוכבים/נקודות/מיילים. ";break;
			case '177':echo "בתחנות דלק לא ניתן לבצע “שרות עצמי“ אלא “שרות עצמי בתחנות דלק“.";break;
			default:echo "שגיאה לא ידועה :) " . $responseData['shvaErrorCode'] ; break;
			}

		}
	else{
		echo $responseData['code']." ".$responseData['message'];
	}
}
else{

    if($responseData['code']==1004){
// 1004                          error returned                   4
//   echo $responseData['code']." ". $responseData['message']. ": ".$responseData['emvErrorResponse']['statusResult']." ".$responseData['data']['statusResultDes']; //here error changed
            echo $responseData['code']." ".$responseData['emvErrorResponse']['statusResult']." ".$responseData['data']['statusResultDes'];
//      switch ($responseData['shvaErrorCode']) {
      switch ($responseData['emvErrorResponse']['statusResult']) {
            case 1:echo "חסום.";break;
            case 3:echo "התקשר לחברת האשראי. ";break;
            case 2:echo "גנוב.";break;
            case 4:echo "סירוב.";break;
            case 5:echo "מזויף.";break;
            case 6:echo "ת.ז. או CVV שגויים. ";break;
            case 8:echo "תקלה בבניית מפתח גישה לקובץ חסומים.";break;
            case 9:echo "לא הצליח להתקשר, התקשר לחברת האשראי.";break;
            case 10:echo "תוכנית הופסקה עפ“י הוראת המפעיל (ESC) או COM PORT לא ניתן לפתיחה (WINDOWS).";break;
            case 15:echo "אין התאמה בין המספר שהוקלד לפס המגנטי.";break;
            case 16:echo "נתונים נוספים אינם או ישנם בניגוד להגדרות המסוף.";break;
            case 17:echo "לא הוקלדו 4 ספרות האחרונות.";break;
            case 19:echo "רשומה בקובץ INT_IN קצרה מ- 16 תווים.<br> נא להקליד ידנית!";break;
            case 20:echo "קובץ קלט (INT_IN) לא קיים.";break;
            case 21:echo "קובץ חסומים (NEG) לא קיים או לא מעודכן - בצע שידור או בקשה לאישור עבור כל עסקה.";break;
            case 22:echo "אחד מקבצי פרמטרים או ווקטורים לא קיים.";break;
            case 23:echo "קובץ תאריכים (DATA) לא קיים.";break;
            case 25:echo "הפרש בימים בקליטת חסומים גדול מדי -בצע שידור או בקשה לאישור עבור כל עסקה. ";break;
            case 24:echo "קובץ אתחול (START) לא קיים.";break;
            case 26:echo "הפרש דורות בקליטת חסומים גדול מידי – בצע שידור או בקשה לאישור עבור כל עסקה.";break;
            case 37:echo "שגיאה בתשלומים - סכום עסקה צריך להיות שווה תשלום ראשון + (תשלום קבוע כפול מס' ";break;
            case 36:echo "פג תוקף. ";break;
            case 35:echo "כרטיס לא רשאי לבצע עסקה עם סוג אשראי זה. ";break;
            case 34:echo "כרטיס לא רשאי לבצע במסוף זה או אין אישור לעסקה כזאת. ";break;
            case 33:echo "כרטיס לא תקין. ";break;
            case 32:echo "תנועות ישנות בצע שידור או בקשה לאישור עבור כל עסקה. ";break;
            case 31:echo "מסוף מעודכן כרב ספק והוקלד גם מס' מוטב. ";break;
            case 30:echo "מסוף שאינו מעודכן כרב ספק/רב מוטב והוקלד מס' ספק/מס' מוטב. ";break;
            case 29:echo "מספר מוטב לא הוכנס למסוף המוגדר לעבודה כרב מוטב. ";break;
            case 28:echo "מספר מסוף מרכזי לא הוכנס למסוף המוגדר לעבודה כרב ספק. ";break;
            case 27:echo "כאשר לא הוכנס פס מגנטי כולו הגדר עסקה כעסקה טלפונית או כעסקת חתימה בלבד.";break;
            case 40:echo "מסוף שמוגדר כרב מוטב הוקלד מס' ספק. ";break;
            case 39:echo "סיפרת בקורת לא תקינה. ";break;
            case 38:echo "לא ניתן לבצע עסקה מעל תקרה לכרטיס לאשראי חיוב מיידי. תשלומים)";break;
            case 41:echo "מעל תקרה, אך קובץ הקלט מכיל הוראה לא לבצע שאילתא (J1,J2,J3 )";break;
            case 42:echo "חסום בספק, אך קובץ הקלט מכיל הוראה לא לבצע שאילתא (J1,J2,J3 )";break;
            case 43:echo "אקראית, אך קובץ הקלט מכיל הוראה לא לבצע שאילתא (J1,J2,J3 )";break;
            case 44:echo "מסוף לא רשאי לבקש אישור ללא עסקה, אך קובץ הקלט מכיל (J5).";break;
            case 45:echo "מסוף לא רשאי לבקש אישור ביוזמתו, אך קובץ הקלט מכיל (J6).";break;
            case 46:echo "יש לבקש אישור, אך קובץ הקלט מכיל הוראה לא לבצע שאילתא (J1,J2,J3 )";break;
            case 47:echo "יש לבקש אישור בשל בעיה הקשורה לקכ“ח אך קובץ הקלט מכיל הוראה לא לבצע שאילתא.";break;
            case 51:echo "מספר רכב לא תקין.";break;
            case 52:echo "מד מרחק לא הוקלד.";break;
            case 53:echo "מסוף לא מוגדר כתחנת דלק. (הועבר כרטיס דלק או קוד עסקה לא מתאים)";break;
            case 57:echo "לא הוקלד מספר תעודת זהות.";break;
            case 58:echo "לא הוקלד CVV2.";break;
            case 59:echo "לא הוקלדו מספר תעודת הזהות וה- CVV2.";break;
            case 60:echo "צרוף ABS לא נמצא בהתחלת נתוני קלט בזיכרון.";break;
            case 61:echo "מספר כרטיס לא נמצא או נמצא פעמיים.";break;
            case 63:echo "קוד עסקה לא תקין. ";break;
            case 62:echo "סוג עסקה לא תקין.";break;
            case 71:echo "חובה להקליד מספר סודי. ";break;
            case 70:echo "לא מוגדר מכשיר להקשת מספר סודי. ";break;
            case 69:echo "אורך הפס המגנטי קצר מידי. ";break;
            case 68:echo "לא ניתן להצמיד לדולר או למדד לסוג אשראי שונה מתשלומים. ";break;
            case 67:echo "קיים מספר תשלומים לסוג אשראי שאינו דורש זה. ";break;
            case 66:echo "קיים תשלום ראשון ו/או תשלום קבוע לסוג אשראי שונה מתשלומים. ";break;
            case 65:echo "מטבע לא תקין. ";break;
            case 64:echo "סוג אשראי לא תקין.";break;
            case 72:echo "קכ“ח לא זמין – העבר בקורא מגנטי.";break;
            case 73:echo "הכרטיס נושא שבב ויש להעבירו דרך הקכ“ח.";break;
            case 74:echo "דחייה – כרטיס נעול.";break;
            case 75:echo "דחייה – פעולה עם קכ“ח לא הסתיימה בזמן הראוי.";break;
            case 76:echo "דחייה – נתונים אשר התקבלו מקכ“ח אינם מוגדרים במערכת.";break;
            case 80:echo "הוכנס “קוד מועדון“ לסוג אשראי לא מתאים. ";break;
            case 77:echo "הוקש מספר סודי שגוי";break;
            case 99:echo "לא מצליח לקרוא/ לכתוב/ לפתוח קובץ TRAN.";break;
            case 101:echo "אין אישור מחברת אשראי לעבודה.";break;
            case 106:echo "למסוף אין אישור לביצוע שאילתא לאשראי חיוב מיידי.";break;
            case 107:echo "סכום העסקה גדול מידי – חלק למספר העסקאות.";break;
            case 113:echo "למסוף אין אישור לעסקה טלפונית. ";break;
            case 112:echo "למסוף אין אישור לעסקה טלפון/ חתימה בלבד בתשלומים. ";break;
            case 111:echo "למסוף אין אישור לעסקה בתשלומים. ";break;
            case 110:echo "למסוף אין אישור לכרטיס חיוב מיידי. ";break;
            case 109:echo "למסוף אין אישור לכרטיס עם קוד השרות 108.";break;
            case 587:echo "למסוף אין אישור לבצע עסקאות מאולצות.";break;
            case 123:echo "למסוף אין אישור לעסקת כוכבים/נקודות/מיילים לסוג אשראי זה. ";break;
            case 122:echo "למסוף אין אישור להצמדה למדד לכרטיסי חו“ל. ";break;
            case 121:echo "למסוף אין אישור להצמדה למדד. ";break;
            case 120:echo "למסוף אין אישור להצמדה לדולר. ";break;
            case 119:echo "למסוף אין אישור לאשראי אמקס קרדיט. ";break;
            case 118:echo "למסוף אין אישור לאשראי ישראקרדיט. ";break;
            case 117:echo "למסוף אין אישור לעסקת כוכבים/נקודות/מיילים. ";break;
            case 116:echo "למסוף אין אישור לעסקת מועדון. ";break;
            case 115:echo "למסוף אין אישור לעסקה בדולרים. ";break;
            case 114:echo "למסוף אין אישור לעסקה “חתימה בלבד“.";break;
            case 124:echo "למסוף אין אישור לאשראי קרדיט בתשלומים לכרטיסי ישראכרט";break;
            case 131:echo "כרטיס לא רשאי לבצע עסקת כוכבים/נקודות/מיילים. ";break;
            case 130:echo "כרטיס לא רשאי לבצע עסקת מועדון. ";break;
            case 129:echo "למסוף אין אישור לבצע עסקת זכות מעל תקרה. ";break;
            case 128:echo "למסוף אין אישור לקבל כרטיסי ויזה אשר מתחילים ב - 3.";break;
            case 127:echo "למסוף אין אישור לעסקת חיוב מיידי פרט לכרטיסי חיוב מיידי. ";break;
            case 126:echo "למסוף אין אישור לקוד מועדון זה. ";break;
            case 125:echo "למסוף איו אישור לאשראי קרדיט בתשלומים לכרטיסי אמקס";break;
            case 133:echo "כרטיס לא תקף על פי רשימת כרטיסים תקפים של ישראכרט. ";break;
            case 132:echo "כרטיס לא רשאי לבצע עסקאות בדולרים (רגילות או טלפוניות).";break;
            case 134:echo "כרטיס לא תקין עפ“י הגדרת המערכת (VECTOR1 של ישראכרט)- מס' הספרות בכרטיס- שגוי.";break;
            case 135:echo "כרטיס לא רשאי לבצע עסקאות דולריות עפ“י הגדרת המערכת (VECTOR1 של ישראכרט).";break;
            case 136:echo "הכרטיס שייך לקבוצת כרטיסים אשר אינה רשאית לבצע עסקאות עפ“י הגדרת המערכת (VECTOR20 של ויזה).";break;
            case 139:echo "מספר תשלומים גדול מידי על פי רשימת כרטיסים תקפים של ישראכרט. ";break;
            case 138:echo "כרטיס לא רשאי לבצע עסקאות בתשלומים על פי רשימת כרטיסים תקפים של ישראכרט. ";break;
            case 137:echo "קידומת הכרטיס (7 ספרות) לא תקפה עפ“י הגדרת המערכת (VECTOR21 של דיינרס).";break;
            case 140:echo "כרטיסי ויזה ודיינרס לא רשאים לבצע עסקאות מועדון בתשלומים.";break;
            case 141:echo "סידרת כרטיסים לא תקפה עפ“י הגדרת המערכת. (VECTOR5 של ישראכרט).";break;
            case 142:echo "קוד שרות לא תקף עפ“י הגדרת המערכת (VECTOR6 של ישראכרט).";break;
            case 143:echo "קידומת הכרטיס (2 ספרות) לא תקפה עפ“י הגדרת המערכת. (VECTOR7 של ישראכרט).";break;
            case 144:echo "קוד שרות לא תקף עפ“י הגדרת המערכת. (VECTOR12 של ויזה).";break;
            case 145:echo "קוד שרות לא תקף עפ“י הגדרת המערכת. (VECTOR13 של ויזה).";break;
            case 147:echo "כרטיס לא רשאי לבצע עסקאות בתשלומים עפ“י וקטור 31 של לאומיקארד. ";break;
            case 146:echo "לכרטיס חיוב מיידי אסור לבצע עסקת זכות.";break;
            case 152:echo "קוד מועדון לא תקין. ";break;
            case 151:echo "אשראי לא מאושר לכרטיסי חו“ל. ";break;
            case 150:echo "אשראי לא מאושר לכרטיסי חיוב מיידי. ";break;
            case 149:echo "כרטיס אינו רשאי לבצע עסקאות טלפוניות עפ“י וקטור 31 של לאומיקארד. ";break;
            case 148:echo "כרטיס לא רשאי לבצע עסקאות טלפוניות וחתימה בלבד עפ“י ווקטור 31 של לאומיקארד.";break;
            case 153:echo "כרטיס לא רשאי לבצע עסקאות אשראי גמיש (עדיף +30/) עפ“י הגדרת המערכת. (VECTOR21 של דיינרס).";break;
            case 154:echo "כרטיס לא רשאי לבצע עסקאות חיוב מיידי עפ“י הגדרת המערכת. (VECTOR21 של דיינרס).";break;
            case 155:echo "סכום המינמלי לתשלום בעסקת קרדיט קטן מידי.";break;
            case 156:echo "מספר תשלומים לעסקת קרדיט לא תקין.";break;
            case 157:echo "תקרה 0 לסוג כרטיס זה בעסקה עם אשראי רגיל או קרדיט.";break;
            case 158:echo "תקרה 0 לסוג כרטיס זה בעסקה עם אשראי חיוב מיידי.";break;
            case 159:echo "תקרה 0 לסוג כרטיס זה בעסקת חיוב מיידי בדולרים.";break;
            case 160:echo "תקרה 0 לסוג כרטיס זה בעסקה טלפונית.";break;
            case 161:echo "תקרה 0 לסוג כרטיס זה בעסקת זכות.";break;
            case 162:echo "תקרה 0 לסוג כרטיס זה בעסקת תשלומים.";break;
            case 163:echo "כרטיס אמריקן אקספרס אשר הונפק בחו“ל לא רשאי לבצע עסקאות בתשלומים.";break;
            case 164:echo "כרטיסי JCB רשאי לבצע עסקאות רק באשראי רגיל.";break;
            case 168:echo "למסוף אין אישור לעסקה דולרית עם סוג אשראי זה. ";break;
            case 167:echo "לא ניתן לבצע עסקת כוכבים/נקודות/מיילים בדולרים. ";break;
            case 166:echo "כרטיס מועדון לא בתחום של המסוף. ";break;
            case 165:echo "סכום בכוכבים/נקודות/מיילים גדול מסכום העסקה.";break;
            case 171:echo "לא ניתן לבצע עסקה מאולצת לכרטיס/אשראי חיוב מיידי. ";break;
            case 170:echo "סכום הנחה בכוכבים/נקודות/מיילים גדול מהמותר. ";break;
            case 169:echo "לא ניתן לבצע עסקת זכות עם אשראי שונה מהרגיל";break;
            case 175:echo "למסוף אין אישור להצמדה לדולר לאשראי זה. ";break;
            case 174:echo "למסוף אין אישור להצמדה למדד לאשראי זה. ";break;
            case 173:echo "עסקה כפולה. ";break;
            case 172:echo "לא ניתן לבטל עסקה קודמת (עסקת זכות או מספר כרטיס אינו זהה).";break;
            case 176:echo "כרטיס אינו תקף עפ“י הגדרת ה מערכת (וקטור 1 של ישראכרט).";break;
            case 200:echo "שגיאה יישומית. ";break;
            case 180:echo "בכרטיס מועדון לא ניתן לבצע עסקה טלפונית. ";break;
            case 179:echo "אסור לבצע עסקת זכות בדולר בכרטיס תייר. ";break;
            case 178:echo "אסור לבצע עסקת זכות בכוכבים/נקודות/מיילים. ";break;
            case 177:echo "בתחנות דלק לא ניתן לבצע “שרות עצמי“ אלא “שרות עצמי בתחנות דלק“.";break;
            // added 10.05.2019
            case 000:echo "מאושר“.";break;
            case 001:echo "כרטיס חסום“.";break;
            case 002:echo "כרטיס גנוב החרם“.";break;
            case 003:echo " לחברת האשראי“.";break;
            case '1-800-86-86-86':echo "ישראכרט“.";break;
            case '1-700-700-110':echo "ויזה כ.א.ל";break;
            case '1-700-703-037':echo "ויזה לאומי";break;
            case 004:echo "עסקה לא אושרה";break;
            case 005:echo "כרטיס מזוייף החרם";break;
            case 006:echo " דחה עסקה cvc2/id שגוי"; break;
            case 007:echo " דחה עסקה CAVV/UCAF  שגוי"; break;
            case 008:echo " דחה עסקה AVS שגוי"; break;
            case 009:echo " דחיה נתק בתקשורת";break;
            case 010:echo " אישור חלקי";break;
            case 011:echo " דחה עסקה : חוסר בנקודות / כוכבים/מיילים/הטבה אחרת";break;
            case 012:echo " כרטיס לא מורשה במסוף";break;
            case 013:echo " דחה בקשה קוד יתרה שגוי";break;
            case 014:echo " דחיה כרטיס לא משוייך לרשת";break;
            case 015:echo " דחה עסקה: הכרטיס אינו בתוקף";break;
            case 016:echo " דחיה- אין הרשאה לסוג מטבע";break;
            case 017:echo " דחיה- אין הרשאה לסוג האשראי בעסקה";break;
            case 026:echo " דחה עסקה  ID- שגוי";break;
            case 041:echo "  ישנה חובת יציאה לבקשה לאישור לעסקה עם פארמטר J2";break;
            default:echo "שגיאה לא ידועה :) " . $responseData['shvaErrorCode'] ; break;
            }



        }
    else{
        echo $responseData['code']." ".$responseData['message'];
    }

	}
	echo "";
	}
	die();
}

//prepaid pay
if(isset($_REQUEST['prepaid_sum'])){
	//echo "feigy".$_REQUEST['prepaid_sum']."-".$_REQUEST['prepaid_num'];
	$id=$_REQUEST['prepaid_num'];

	//echo $id;
	$sql="select * from card_prepaid where id='$id'";
	$result=mysql_query($sql);
	//echo $sql;
	$num=mysql_num_rows($result);
	if($num==0){
		echo "";
		die();
	}
	$row=mysql_fetch_array($result);
	if($_REQUEST['prepaid_sum']>$row['BalanceCurrent']){
		//$sql="update card_prepaid set BalanceCurrent=0 where id=".$id;
		//mysql_query($sql);
		echo  $row['BalanceCurrent'].";".$row['BalanceCurrent'];
	}
	else{
		//$sql="update card_prepaid set BalanceCurrent=BalanceCurrent-".$_REQUEST['prepaid_sum']." where id=".$id;
		//mysql_query($sql);
		//echo $sql;
		//echo mysql_error();
		echo $_REQUEST['prepaid_sum'].";".$row['BalanceCurrent'];
	}
	die();
}
//prepaid load
if(isset($_REQUEST['prepaid_sum2'])){
	//echo "feigy".$_REQUEST['prepaid_sum']."-".$_REQUEST['prepaid_num'];
	$id=$_REQUEST['prepaid_num2'];
	$sum=$_REQUEST['prepaid_sum2'];
	/*sarakkkkkk*/
	$sql="SELECT `percent_nis`,`discount` FROM `loaded_card` WHERE  $sum >`from_sum` and $sum<=`to_sum` order by to_sum desc LIMIT 1";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);
	if($num==0){
		$sum_return=$sum;
		}
		else{
			$row=mysql_fetch_array($result);
			$additional=$row['discount'];
			$percent_nis=$row['percent_nis'];
			//file_put_contents('ssssssssssssssssss.log', $additional.'   '.$percent_nis.'   the sum: '.$sum);
			if($percent_nis=='percent'){
				$sum_return=(($sum*$additional)/100)+$sum;

			}
			else{

				$sum_return=$sum+$additional;
			}
		}
$sum=$sum_return;

/*sk 20/12/15 */

	$sql="select * from card_prepaid where id='$id'";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);
	if($num==0){
		if(strlen($id)==16&&substr($id,0,3)==666&&substr($id,-3)==999){
			$sql="insert into card_prepaid (id) values('$id')";
			mysql_query($sql);
		}

	}
	$sql="update card_prepaid set BalanceCurrent=BalanceCurrent+$sum where id='".$id."'";
	echo $sql;
	$result=mysql_query($sql);
	echo mysql_error();
	$sql="INSERT INTO `card_prepaid_history`(`ID`, `Date`, `Charge`, `Paid`) VALUES ('".$id."',NOW(),".$sum.",".$sum.")";
	mysql_query($sql);
	die();
}
//prepaid check for check and load
if(isset($_REQUEST['prepaid_check'])){
	//file_put_contents("prepaid.log", $_REQUEST['prepaid_num']);
	$id=$_REQUEST['prepaid_num'];

	$sql="select * from card_prepaid where id='$id'";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);
	if($num==0){
		if(strlen($id)==16&&substr($id,0,3)==666&&substr($id,-3)==999){
			$sql="insert into card_prepaid (id) values('$id')";
			mysql_query($sql);
		}
		else{
			echo '-1';
		}

		die();
	}
	else{
		$row=mysql_fetch_array($result);
		echo  $row['BalanceCurrent'];
	}

	die();
}

/************************ sk 07/02/2016 update offline enter and exit workers**************************/
if(isset($_POST['json_offline_worker'])){
	$json=json_decode($_REQUEST['json_offline_worker'],true);
	/*print_r($json);
	die();*/
	foreach ($json as $key => $value) {
		$string=$value['state'].'   '.$value['id_worker'].'<hr>';

	$id_worker=$value['id_worker'];

	$sql="select WorkerNum from workers where ID=$id_worker";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	$id_worker=$row['WorkerNum'];


	$stat=$value['state'];
	$stock='607000';
	$sql = "select * from vcx_weberp.listingsStocks where Status=1 and user_id = $userID and TerminalID=$stock order by binary StockName";
	//echo $sql;
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	$stock=$row['ID'];
	if($stat=="start"){
		$sql="select WorkerNum,HourSalary,WorkerName from vcx_$username.workers where (WorkerNum='$id_worker') and Status=1";
		//file_put_contents('query_worker.txt', $sql);
		//echo "3333".$sql;
		//echo $row[WorkerName];
		$result=mysql_query($sql);
		echo mysql_error();
		$row=mysql_fetch_array($result);
			if(date("w")==6){
				$sh=1;
			}
			else{
				$sh=0;
			}
		$sql="select max(ID) as id from attendance";
		$result=mysql_query($sql);
		$row2=mysql_fetch_array($result);
		$id=$row2[id]+1;
		//change format of date
		$dmy = $value[date_s];
        list($day, $month, $year) = explode("/", $dmy);
       $ymd = "$year-$month-$day";

		$sql_check_if_exisit="select * from  vcx_$username.attendance where `worker_num`= '$row[WorkerNum]'  and `day`='$ymd' and `entertime`='$value[time_s]' ";
		//file_put_contents('aachheck_if_exist.txt', $sql_check_if_exisit);
		$result111=mysql_query($sql_check_if_exisit);
		$flag_ex=0;
		$id1=0;
		if($result111){
			$row3=mysql_fetch_array($result111);
			$id1=$row3[id];
			if($id1){
				$flag_ex=1;
				}
			}
		//file_put_contents('aflag_offline.txt', $id1.'   --  '.$flag_ex);
		if($flag_ex==0){
			$sql="INSERT INTO vcx_$username.attendance ( id,`worker_id`, `worker_num`, `day`, `entertime`,`user_id`, `terminal_id`, `timestamp`, `sabbat`, `entertime_updated`, `hour_salary`) 
			VALUES ($id,'$id_worker','$row[WorkerNum]','$ymd','$value[time_s]','$userID',$stock,".time().",$sh,'".date("Y-m-d H:i:s")."','$row[HourSalary]') ";

			//file_put_contents('aaaofline_worf.txt', $sql);
			mysql_query($sql);
			echo mysql_error();
		}
	}
	else{
		$sql= "select WorkerName from vcx_$username.workers where (WorkerNum='$id_worker' or ID='$id_worker') and Status=1";
		//file_put_contents('aa3.txt', $sql);
		$result=mysql_query($sql);
		$row2=mysql_fetch_array($result);
		echo $row2[WorkerName];
		$sql="select * from vcx_$username.attendance where worker_num='$id_worker' and id=(select max(id) from attendance where worker_num='$id_worker')";
		//file_put_contents('aa2.txt', $sql);
		$result=mysql_query($sql);
		$row=mysql_fetch_array($result);
		if($row[day]==$value['date_s']){
			$two=0;
		}
		else{
			$two=1;
		}
		$t1 =time();
		$t2 = StrToTime ( $row[day]." ".$row[entertime] );
		$diff = $t1 - $t2;
		$hours = $diff / ( 60 * 60 );

		$salary=$row[hour_salary]*hours;
	 	$salary=number_format((float)$salary, 2, '.', '');

	 	//change format of date
		$dmy = $value[date_s];
        list($day, $month, $year) = explode("/", $dmy);
       $ymd = "$year-$month-$day";
		$sql_check_if_exisit2="select * from `attendance` where worker_num='$id_worker' and `exit_day`='$ymd' and `exittime`='$value[time_s]' ";
		$result222=mysql_query($sql_check_if_exisit2);
		$flag_ex2=0;
		$id2=0;
		if($result222){
			$row32=mysql_fetch_array($result222);
			$id2=$row32[id];
			if($id2){
				$flag_ex2=1;
				}
			}

		if($flag_ex2==0){

			/*sk 1/2/16 add exit day column*/
			$sql="UPDATE `attendance` SET `exittime`='$value[time_s]',`recordclosednormally`=1,`istwodates`=$two,
			`enterclosedabnormally`=1,`exitclosedabnormally`=1,`valid`=1,`exittime_updated`='".date("Y-m-d H:i:s")."',
			`total_salary`=$salary,`base_salary`=$salary,`lastupdated`=".time().",`exit_day`='$ymd' WHERE id=$row[id]";

			mysql_query($sql);
			echo mysql_error();
		}
	}

	}
	echo 11;
  die();
}
/*********************************************************************/

/*sk 17/12/2015 add addion in prepaid*/
if(isset($_REQUEST['calc_prepaid_amount'])){
	$sum=$_REQUEST['prepaid_sum1122'];
	$sql="SELECT `percent_nis`,`discount` FROM `loaded_card` WHERE  $sum >`from_sum` and $sum<=`to_sum` order by to_sum desc LIMIT 1";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);
	if($num==0){
		echo $sum;
		die();

		}
		else{
			$row=mysql_fetch_array($result);
			$additional=$row['discount'];
			$percent_nis=$row['percent_nis'];
			//file_put_contents('ssssssssssssssssss.log', $additional.'   '.$percent_nis.'   the sum: '.$sum);
			if($percent_nis=='percent'){
				$sum_return=(($sum*$additional)/100)+$sum;

			}
			else{

				$sum_return=$sum+$additional;
			}
		}
		echo $sum_return;
		die();

}
if(isset($_REQUEST['ashray_sum'])){
	include "../../ipay.php";
	$ipay=new ipay();
	$x=$ipay->transaction_track2($_REQUEST['ashray_num'],$_REQUEST['ashray_sum'],"1");
	echo $x;
}


function sql2json($query) {
    $data_sql = mysql_query($query) or die("'';//" . mysql_error());// If an error has occurred,
            //    make the error a js comment so that a javascript error will NOT be invoked
    $json_str = ""; //Init the JSON string.
    if($total = mysql_num_rows($data_sql)) { //See if there is anything in the query
        $json_str .= "[\n";
        $row_count = 0;
        while($data = mysql_fetch_assoc($data_sql)) {
            if(count($data) > 1) $json_str .= "{\n";

            $count = 0;
            foreach($data as $key => $value) {
            	$value=str_replace("\'", "", $value);
				$value=str_replace('\"', "", $value);
				$value=str_replace("'", "", $value);
				$value=str_replace('"', "", $value);
				$value=str_replace('\n', "", $value);
				$value=str_replace('#', "", $value);
				$value=str_replace('&', "", $value);
                //If it is an associative array we want it in the format of "key":"value"
                $value = str_replace('"', '\"', $value);
                    $value = str_replace('\\"', '\"', $value);
                if(count($data) > 1) $json_str .= "\"$key\":\"$value\"";
                else $json_str .= "\"$value\"";

                //Make sure that the last item don't have a ',' (comma)
                $count++;
                if($count < count($data)) $json_str .= ",\n";
            }
            $row_count++;
            if(count($data) > 1) $json_str .= "}\n";

            //Make sure that the last item don't have a ',' (comma)
            if($row_count < $total) $json_str .= ",\n";
        }

        $json_str .= "]\n";
    }

    //Replace the '\n's - make it faster - but at the price of bad redability.
    $json_str = str_replace("\n","",$json_str); //Comment this out when you are debugging the script

//$json_str=str_replace("'","",$json_str);
    //Finally, output the data
    return $json_str;
}
if(isset($_REQUEST['transactions'])){
	echo "'".$_REQUEST['transactions']."'";
	//"{"cash_num1":{"products":"[{\"BarCode\":\"7300000173\",\"Title\":\"NOF\",\"SalePrice\":\"219\",\"Amount\":1,\"Discount\":0,\"comment\":\"\",\"commentClass\":\"\",\"department\":\"8\",\"comptype\":\"2\",\"finalamount\":\"\",\"Discount2\":0,\"cdisctype\":0,\"$$hashKey\":\"IZR\"},{\"BarCode\":\"7300000098\",\"Title\":\"PINK\",\"SalePrice\":\"259\",\"Amount\":1,\"Discount\":0,\"comment\":\"\",\"commentClass\":\"\",\"department\":\"8\",\"comptype\":\"2\",\"finalamount\":\"\",\"Discount2\":0,\"cdisctype\":0,\"$$hashKey\":\"IZV\"},{\"BarCode\":\"7300000134\",\"Title\":\"SEL\",\"SalePrice\":\"279\",\"Amount\":1,\"Discount\":0,\"comment\":\"\",\"commentClass\":\"\",\"department\":\"8\",\"comptype\":\"2\",\"finalamount\":\"\",\"Discount2\":0,\"cdisctype\":0,\"$$hashKey\":\"IZZ\"},{\"BarCode\":\"7300000094\",\"Title\":\"TEAM\",\"SalePrice\":\"259\",\"Amount\":1,\"Discount\":0,\"comment\":\"\",\"commentClass\":\"\",\"department\":\"8\",\"comptype\":\"2\",\"finalamount\":\"\",\"Discount2\":0,\"cdisctype\":0}]","payments":"{\"cash\":[{\"type\":\"מזומן\",\"amount\":\"1016.00\",\"$$hashKey\":\"J07\"}],\"cheque\":[],\"credit\":[],\"akafa\":[],\"shovar\":[],\"prepaid\":[]}","paid":"1016.00","itra":"0.00","date":"02/08/2015","time":"9:27:9","pause":0,"cash_num":1,"original":"","SearchClient":[],"firstamount":1016,"finalamount":"1016.00","amountbeforevat":"833.12","round":0,"vat":"182.88","cashierid":"2541","change":"0.00","cash_kupanum":"000180001","$$hashKey":"J09"}}";
	//file_put_contents("feigy.log", $_REQUEST['transactions']);
	die();
}

if(isset($_POST['id_worker'])){
	$id_worker=$_POST['id_worker'];
	//file_put_contents('$id_worker.txt', $id_worker);
	$sql="select WorkerNum from workers where ID=$id_worker";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	$id_worker=$row['WorkerNum'];
	//file_put_contents('$id_worker2.txt', $id_worker);

	$stat=$_POST['stat'];
	$stock=$_POST['stock'];
	$sql = "select * from vcx_weberp.listingsStocks where Status=1 and user_id = $userID and TerminalID=$stock order by binary StockName";
	//echo $sql;
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	$stock=$row['ID'];
	if($stat=="start"){
		$sql="select WorkerNum,HourSalary,WorkerName from vcx_$username.workers where (WorkerNum='$id_worker') and Status=1";
		//file_put_contents('query_worker.txt', $sql);
		//echo "3333".$sql;
		echo $row[WorkerName];
		$result=mysql_query($sql);
		echo mysql_error();
		$row=mysql_fetch_array($result);
			if(date("w")==6){
				$sh=1;
			}
			else{
				$sh=0;
			}
		$sql="select max(ID) as id from attendance";
		$result=mysql_query($sql);
		$row2=mysql_fetch_array($result);
		$id=$row2[id]+1;
		$sql="INSERT INTO vcx_$username.attendance ( id,`worker_id`, `worker_num`, `day`, `entertime`,`user_id`, `terminal_id`, `timestamp`, `sabbat`, `entertime_updated`, `hour_salary`) 
		VALUES ($id,'$id_worker','$row[WorkerNum]','".date("Y-m-d")."','".date("H:i")."','$userID',$stock,".time().",$sh,'".date("Y-m-d H:i:s")."','$row[HourSalary]') ";
		//echo "4444".$sql."\n";
		//file_put_contents('aaaaaworke.txt', $sql);
		mysql_query($sql);
		echo mysql_error();
	}
	else{
		$sql= "select WorkerName from vcx_$username.workers where (WorkerNum='$id_worker' or ID='$id_worker') and Status=1";
		//file_put_contents('aa3.txt', $sql);
		$result=mysql_query($sql);
		$row2=mysql_fetch_array($result);
		echo $row2[WorkerName];
		/* sk 28/01/2016*/
		//$sql="select * from vcx_$username.attendance where worker_id='$id_worker' and id=(select max(id) from attendance where worker_id='$id_worker')";
		$sql="select * from vcx_$username.attendance where worker_num='$id_worker' and id=(select max(id) from attendance where worker_num='$id_worker')";
		//file_put_contents('aa2.txt', $sql);
		$result=mysql_query($sql);
		$row=mysql_fetch_array($result);
		if($row[day]==date("Y-m-d")){
			$two=0;
		}
		else{
			$two=1;
		}
		$t1 =time();
		$t2 = StrToTime ( $row[day]." ".$row[entertime] );
		$diff = $t1 - $t2;
		$hours = $diff / ( 60 * 60 );

		$salary=$row[hour_salary]*$hours;
	 	$salary=number_format((float)$salary, 2, '.', '');
			/*sk 1/2/16 add exit day column*/
		$sql="UPDATE `attendance` SET `exittime`='".date("H:i")."',`recordclosednormally`=1,`istwodates`=$two,
		`enterclosedabnormally`=1,`exitclosedabnormally`=1,`valid`=1,`exittime_updated`='".date("Y-m-d H:i:s")."',
		`total_salary`=$salary,`base_salary`=$salary,`lastupdated`=".time().",`exit_day`='".date("Y-m-d")."' WHERE id=$row[id]";
		//echo $sql;
		//file_put_contents('aa1.txt', $sql);
		mysql_query($sql);

		echo mysql_error();
	}

	die();
}
elseif(isset($_REQUEST[advanced_search])){
	$where="";
	$where.=(isset($_REQUEST[barcode])&&$_REQUEST[barcode]!="")?"`BarCode` LIKE  '%".$_REQUEST[barcode]."' and ":"";
	$where.=(isset($_REQUEST[SalePrice])&&$_REQUEST[SalePrice]!="")?"SalePrice='".$_REQUEST[SalePrice]."' and ":"";
	$where.=(isset($_REQUEST[ProductGroup])&&$_REQUEST[ProductGroup]!=""&&$_REQUEST[ProductGroup]!="?"&&$_REQUEST[ProductGroup]!="-1")?"ProductGroup='".$_REQUEST[ProductGroup]."' and ":"";
	$where.=(isset($_REQUEST[MisparZar])&&$_REQUEST[MisparZar]!="")?"MisparZar='".$_REQUEST[MisparZar]."' and ":"";
	$where.=(isset($_REQUEST[Sapak])&&$_REQUEST[Sapak]!=""&&$_REQUEST[Sapak]!="?"&&$_REQUEST[Sapak]!="-1")?"Sapak='".$_REQUEST[Sapak]."' and ":"";
	$where.=(isset($_REQUEST[MisparSiduri])&&$_REQUEST[MisparSiduri]!="")?"MisparSiduri='".$_REQUEST[MisparSiduri]."' and ":"";
	$where.=(isset($_REQUEST[chalifinum])&&$_REQUEST[chalifinum]!="")?"chalifinum='".$_REQUEST[chalifinum]."' and ":"";
	$where=substr($where,0,-4);
	$sql="select *,ProductGroup as department from listingsDB where $where and active='yes'";
	//echo $sql;
	$product=sql2json($sql);
	echo $product;
	die();
}
//search
elseif(isset($_REQUEST[prod_search])){
	$prod=$_REQUEST[prod_search];
	$table=$_REQUEST[table];
	$sql="select * from $table where active='yes' and(
	REPLACE(`Title`,'א','') LIKE REPLACE('%$prod%','א','') or REPLACE(`Title`,'ה','') LIKE REPLACE('%$prod%','ה','') or REPLACE(`Title`,'ו','') LIKE REPLACE('%$prod%','ו','') or REPLACE(`Title`,'י','') LIKE REPLACE('%$prod%','י',''))";
	$product=sql2json($sql);
	echo $product;
	die();
}
//search client
elseif(isset($_REQUEST[search_c1])){
	$prod=$_REQUEST[search_c1];
	$table=$_REQUEST[table];
    if(count(explode("?",$prod)) > 1 && count(explode(";",$prod) > 1)){
                    $prod=explode(";",$prod);
        $prod = $prod[1];
                    $prod=explode("?",$prod);
                    $prod = $prod[0];
    }
	$sql="CREATE TABLE IF NOT EXISTS `using_clients_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,  
  `transction_id` int(11) NULL,
  `points` float NOT NULL,
  
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

   //$sql = "ALTER TABLE transactions ADD COLUMN IF NOT EXISTS points decimal(15,2) DEFAULT 0";
   mysql_query($sql);
	$sql="select *,(select sum(points) from using_clients_points where client_id = c.ClientNum) as points from vcx_$username.$table c where (`SupplierName` LIKE  '%$prod%' or `CellPhone` LIKE '%$prod%' or `ClientNum` LIKE '%$prod%'  or `Address` LIKE '%$prod%') and isClient=1";
/*	$sql="select * from $table where (
	REPLACE(`SupplierName`,'א','') LIKE REPLACE('%$prod%','א','') or REPLACE(`SupplierName`,'ה','') LIKE REPLACE('%$prod%','ה','') or REPLACE(`SupplierName`,'ו','') LIKE REPLACE('%$prod%','ו','') or REPLACE(`SupplierName`,'י','') LIKE REPLACE('%$prod%','י','')
	)  and isClient=1     order by SupplierName limit 200";
	*/
	//file_put_contents("search_c1", $sql);
	$product=sql2json($sql);
	echo $product;
	die();
}
elseif(isset($_REQUEST[prod_search_barcode])){
	$prod=$_REQUEST[prod_search_barcode];
	$sql="select *,ProductGroup as department from listingsDB where `BarCode`='$prod' and active='yes'";
	$product=sql2json($sql);
	echo $product;
	die();
}
elseif(isset($_REQUEST[advanced_search_c])){

	$where="";
	$where.=(isset($_REQUEST[CellPhone])&&$_REQUEST[CellPhone]!="")?"CellPhone='".$_REQUEST[CellPhone]."' and ":"";
	$where.=(isset($_REQUEST[Address])&&$_REQUEST[Address]!="")?"Address LIKE '%".$_REQUEST[Address]."%' and ":"";
	$where.=(isset($_REQUEST[ClientNum])&&$_REQUEST[ClientNum]!="")?"ClientNum='".$_REQUEST[ClientNum]."' and ":"";
	$where.=(isset($_REQUEST[SupplierName])&&$_REQUEST[SupplierName]!="")?"SupplierName LIKE '%".$_REQUEST[SupplierName]."%' and ":"";
	$where.=(isset($_REQUEST[BusinessNum])&&$_REQUEST[BusinessNum]!="")?"BusinessNum='".$_REQUEST[BusinessNum]."' and ":"";
	$where.=(isset($_REQUEST[GroupId])&&$_REQUEST[GroupId]!=""&&$_REQUEST[GroupId]!="?"&&$_REQUEST[GroupId]!="-1")?"GroupId='".$_REQUEST[GroupId]."' and ":"";


	$where=substr($where,0,-4);
	$sql="select * from listingsSuppliers where $where order by SupplierName";
	$client=sql2json($sql);
	echo $client;
	die();
}
//search worker
elseif(isset($_REQUEST[search_w])){
	$prod=$_REQUEST[search_w];
	$sql="SELECT ID , CONCAT(WorkerName,' ',WorkerSurName) as  WorkerName,`WorkerNum`			
			from workers  
			where `status` = 1 and (WorkerName like '%$prod%' or WorkerNum like '%$prod%') order by `WorkerName`";
	//echo $sql;
	$product=sql2json($sql);
	$product=str_replace(chr(13), "", $product);
	//file_put_contents('00w1sear.txt', $product);
	echo $product;
	die();
}
elseif(isset($_REQUEST[stock])){
	$stock=$_REQUEST[stock];
	$TransCount=$_REQUEST[count];
	$sql="insert into vcx_$username.cashbox_actions (action,terminal_id) values ('close_cashbox',$stock)";
	//echo $sql;
				mysql_query($sql);
				echo mysql_error();
				$stat="close_cashbox";
				$sql="select max(JournalNum) as JournalNum2,TransCount from vcx_$username.transactionpackages where TerminalID=$stock";

		$rs = mysql_query($sql);
		echo mysql_error();
		$row=mysql_fetch_array($rs);
		$journal=$row[JournalNum2];
		//if(isset($_COOKIE[trannum])){
			$sql="update vcx_$username.transactionpackages set TransCount=$TransCount where JournalNum=$journal and TerminalID=$stock";
	mysql_query($sql);
	echo mysql_error();
		//	$trannum=$_COOKIE[trannum];
		//}
		//else{
		//	$trannum=$rs->fields[TransCount]+1;
		//}
		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/modules/stock/cashbox2/bill/json'))
		{
		  /*  $put=file_get_contents($_SERVER['DOCUMENT_ROOT'].'/modules/stock/cashbox2/bill/json');
		  //  $put=substr($put,1,-1);
		    $put='http://a.yeda-t.com/modules/stock/plujson.php?com=trans|yedatop|'.$stock.'|{"transactions":{"terminal":"'.$stock.'","transaction":'.$put.'}}';
		//echo	urlencode($put);
		$put = preg_replace('/\s+/', '', $put);
	       $answer= file_get_contents($put);
	       if(trim($answer) == "ok"){

		   }
			else{
				echo "errorrrrrrrrrrrrrrr $answer";
			}

		    //$put=json_decode($put,true);*/
		}
	die();
}
elseif(isset($_REQUEST[cust])){

	require_once ($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'stock' . DIRECTORY_SEPARATOR . 'ClientLog' . DIRECTORY_SEPARATOR . 'ClientLog.php'); // client logging

	$sql="select Phone,CellPhone,BusinessNum from vcx_$username.listingsSuppliers where CellPhone=$_REQUEST[a_c_cellphone] or BusinessNum=$_REQUEST[a_c_tz] and BusinessNum not like''";//or ClientNum=$_REQUEST[a_c_num]"; // ClientNum checing - added 06.03.2019
	$result=mysql_query($sql);
	$result=mysql_num_rows($result);
	if($result>0){

		// client logging
			$logId = $_REQUEST[a_c_num];
						$a_c_num = $_REQUEST[a_c_num];
			
			$fileName = 'cashbox_fe->inc->functions.php';
			$record = array(
				'a_c_num' => $_REQUEST[a_c_num],
				'a_c_name' => $_REQUEST[a_c_name],
				'a_c_tz' => $_REQUEST[a_c_tz],
				'a_c_cellphone' => $_REQUEST[a_c_cellphone],
				'additional' => 'ERROR'
			);

			ClientLog::addLog($logId, $fileName, $record);

			$forJsonDebug = json_encode(array(
				'logId' => $logId,
				'fileName' => $fileName,
				'record' => $record
			));
			//echo PHP_EOL . 'forJsonDebug: ' . $forJsonDebug . PHP_EOL;

			/* echo PHP_EOL . "SQL: " . PHP_EOL . "INSERT_INTO vcx_$username.listingsSuppliers (`ClientNum`,`Address`,`SupplierName`,`BusinessNum`,`Phone`,`CellPhone`,`Email`,`GroupId`,`Discount`,`CreditBalance`,`Obligo`,  `user_ID`,isClient)
			values ('".$_REQUEST[a_c_num]."','".$_REQUEST[a_c_address]."','".$_REQUEST[a_c_name]."','".$_REQUEST[a_c_tz]."','".$_REQUEST[a_c_phone]."','".$_REQUEST[a_c_cellphone]."','".$_REQUEST[a_c_mail]."','".$_REQUEST[a_c_group]."','".$_REQUEST[a_c_discount]."','".$_REQUEST[a_c_hakafa]."','".$_REQUEST[a_c_obligo]."',$userID,1)";*/

		echo "0";

		die();
	}
$a_c_num = $_REQUEST[a_c_num];
$sql="select ClientNum from vcx_$username.listingsSuppliers where  ClientNum=$_REQUEST[a_c_num]"; // ClientNum checing - added 06.03.2019
	$result=mysql_query($sql);
	$result=mysql_num_rows($result);
	if($result>0){
$sql="select max(CONVERT(ClientNum,UNSIGNED INTEGER)) as max_client_num from vcx_$username.listingsSuppliers where isClient=1 AND ClientNum REGEXP '^[0-9]+$'";//or ClientNum=$_REQUEST[a_c_num]"; // ClientNum checing - added 06.03.2019
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
		if($row['max_client_num']>= $a_c_num){
			$a_c_num = $row['max_client_num']+1;
		}
		}
	$sql="INSERT INTO vcx_$username.listingsSuppliers (`ClientNum`,`AccountNum`,`Address`,`SupplierName`,`BusinessNum`,`Phone`,`CellPhone`,`Email`,`GroupId`,`Discount`,`CreditBalance`,`Obligo`,  `user_ID`,isClient)
	 values ('".$a_c_num."','".$a_c_num."','".$_REQUEST[a_c_address]."','".$_REQUEST[a_c_name]."','".$_REQUEST[a_c_tz]."','".$_REQUEST[a_c_phone]."','".$_REQUEST[a_c_cellphone]."','".$_REQUEST[a_c_mail]."','".$_REQUEST[a_c_group]."','".$_REQUEST[a_c_discount]."','".$_REQUEST[a_c_hakafa]."','".$_REQUEST[a_c_obligo]."',$userID,1)";
	 mysql_query($sql);
	 echo mysql_error();
echo $a_c_num;
	 $sqlReport = $sql; // client logging

	$currcust=$_REQUEST[pr_currnumcust]+1;
	$sql="update vcx00.settings set	pr_currnumcust='$currcust'	where user_id='$userID'";
	mysql_query($sql);

/*cust: "add",a_c_address:$("#a_c_address").val(),a_c_name:$("#a_c_name").val(),a_c_tz:$("#a_c_tz").val()
    		,a_c_birthdate:$("#a_c_birthdate").val(),a_c_phone:$("#a_c_phone").val(),a_c_cellphone:$("#a_c_cellphone").val(),a_c_mail:$("#a_c_mail").val()
    		,a_c_group:$("#a_c_group").val() ,a_c_discount:$("#a_c_discount").val() ,a_c_hakafa:$("#a_c_hakafa").val(),a_c_obligo:$("#a_c_obligo").val()  }*/

		/*client logging*/
			$logId = $a_c_num;
			$fileName = 'cashbox_fe->inc->functions.php';
			$record = array(
				'a_c_num' => $_REQUEST[a_c_num],
				'a_c_name' => $_REQUEST[a_c_name],
				'a_c_tz' => $_REQUEST[a_c_tz],
				'a_c_cellphone' => $_REQUEST[a_c_cellphone],
				'sql' => $sqlReport
			);

			ClientLog::addLog($logId, $fileName, $record);

	die();
}
elseif(isset($_REQUEST[prod_add])){
	/*sk 21/09/2015 add cost to product*/
	/*$sql="INSERT INTO vcx_$username.listingsDB (`user_ID`,`Title`,`BarCode`,`SalePrice`,`Unit`,`ProductGroup`,`Sapak`,`MisparZar`,`description`,`MisparSiduri`,`MisparChalifi`,`StockMin`,`StockMax`,`Mikum`,`active`)
			values ($userID,'".$_REQUEST[Title]."','".$_REQUEST[BarCode]."','".$_REQUEST[SalePrice]."','".$_REQUEST[Unit]."','".$_REQUEST[ProductGroup]."','".$_REQUEST[Sapak]."','".$_REQUEST[MisparZar]."','".$_REQUEST[description]."','".$_REQUEST[MisparSiduri]."','".$_REQUEST[MisparChalifi]."','".$_REQUEST[StockMin]."','".$_REQUEST[StockMax]."','".$_REQUEST[Mikum]."','yes')";
*/
/*sk 14/03/2016 not offer to create two products whith the same barcode*/
$barcode= trim($_REQUEST['BarCode']," ");
	$sql="select `ID` from  vcx_$username.listingsDB where `BarCode` like '".$barcode."'";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);
	if($num>0){
		die('1');
	}
$sql="INSERT INTO vcx_$username.listingsDB (`user_ID`,`Title`,`BarCode`,`SalePrice`,`Unit`,`ProductGroup`,`Sapak`,`MisparZar`,`description`,`MisparSiduri`,`MisparChalifi`,`StockMin`,`StockMax`,`Mikum`,`Cost`,`active`)
			values ($userID,'".$_REQUEST[Title]."','".$_REQUEST[BarCode]."','".$_REQUEST[SalePrice]."','".$_REQUEST[Unit]."','".$_REQUEST[ProductGroup]."','".$_REQUEST[Sapak]."','".$_REQUEST[MisparZar]."','".$_REQUEST[description]."','".$_REQUEST[MisparSiduri]."','".$_REQUEST[MisparChalifi]."','".$_REQUEST[StockMin]."','".$_REQUEST[StockMax]."','".$_REQUEST[Mikum]."','".$_REQUEST[Cost]."','yes')";
	mysql_query($sql);
	echo mysql_error();
	die();
}

if(isset($_REQUEST['save_premission'])){
	$data_sett=$_REQUEST['data_sett'];
	foreach ($data_sett as $key => $item) {
	  $data_sett[$key] = $item=='on'?1:0;
	}
	$sql="select id from vcx00.settings where user_id='$userID' ";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);
	if($num>0){
		$sql="update vcx00.settings set
		drawer_open='$data_sett[permission_open_drawer]',
		kupa_openclose='$data_sett[permission_openclose_kupa]',time='$data_sett[permission_hours]',
		alerts='$data_sett[permission_alerts]',promotional='$data_sett[permission_zicuy]',
		discount_prod='$data_sett[permission_discountprod]',discount_cash='$data_sett[permission_discount]',
		prefers='$data_sett[permission_prefer]',checkings='$data_sett[permission_checks]',		
		lang_mng='$data_sett[permission_langs]',charge_worker='$data_sett[permission_worker]',
		worker_cash='$data_sett[permission_worker_cach]',enter_mng='$data_sett[permission_backoffice]',force_cash='$data_sett[permission_force_cash]',details='$data_sett[permission_details]'
		where user_id='$userID'";
	}
	else{
		$sql="INSERT INTO vcx00.settings ( `user_id`,reports,drawer_open,kupa_openclose,time,alerts,promotional,discount_prod,discount_cash,prefers,checkings,inventory_mng,workers_mng,lang_mng,charge_worker,worker_cash,enter_mng,force_cash,details) 
		VALUES ('$userID','$data_sett[permission_open_drawer]','$data_sett[permission_openclose_kupa]','$data_sett[permission_hours]','$data_sett[permission_alerts]'
		,'$data_sett[permission_zicuy]','$data_sett[permission_discountprod]','$data_sett[permission_discount]','$data_sett[permission_prefer]','$data_sett[permission_checks]'
		,'$data_sett[permission_langs]','$data_sett[permission_worker]','$data_sett[permission_worker_cach]','$data_sett[permission_backoffice]','$data_sett[permission_force_cash]','$data_sett[permission_details]') ";
	}
echo $sql;
		mysql_query($sql);
		echo mysql_error();
	die();
}

//---------------------lc 24/07/2016 add--------------------start-------------------------------
if(isset($_REQUEST['save_details1'])){

    $a=$_FILES["up_logo"]["name"];
	$target = "../../../../officefiles/$username/_MlaitekPro/$a";
		if(!is_dir( "../../../../officefiles/$username/_MlaitekPro")){
			mkdir("../../../../officefiles/$username/_MlaitekPro",0777);
		}
	move_uploaded_file( $_FILES['up_logo']['tmp_name'], $target);
	$sql="update vcx00.settings_user set logo='$a'			
		  where user_id='$userID'";
	 mysql_query($sql);
	 echo ' the logo has been upload good';
	 sleep(5);
	 // header('Location: https://office1.yedatop.com/modules/stock/cashbox_fe/');
	 	 header('Location: https://kupa.yedatop.com/modules/stock/cashbox_fe/');
	 
	 die();
}
//---------------------lc 24/07/2016 -------------------------end--------------------------


if(isset($_REQUEST['save_details'])){

	$data_sett=$_REQUEST['data_sett'];
	$sql="select id from vcx00.settings_user where user_id='$userID'";

	$result=mysql_query($sql);
	$num=mysql_num_rows($result);
	/*sk 04/04/16 update details per stock*/
	/*if($num>0){
		$sql="update vcx00.settings_user set
		name='$data_sett[name]',tz='$data_sett[tz]',tel='$data_sett[tel]',fax='$data_sett[fax]',address='$data_sett[address]',mikud='$data_sett[mikud]',email='$data_sett[mail]',web='$data_sett[web]',logo='$data_sett[logo]'	,comments='$data_sett[comments]'
		where user_id='$userID'";
	}
	else{
		$sql="INSERT INTO vcx00.settings_user ( `user_id`,name,tz,tel,fax,address,mikud,email,web,logo,comments)
		VALUES ('$userID','$data_sett[name]','$data_sett[tz]','$data_sett[tel]','$data_sett[fax]','$data_sett[address]','$data_sett[mikud]','$data_sett[mail]','$data_sett[web]','$data_sett[logo]','$data_sett[comments]') ";
	}*/
	$data_sett[comments] = str_replace("'", "\'", $data_sett[comments]);
    $data_sett[name] = str_replace("'", "\'", $data_sett[name]);
	if($num>0){
		$sql="update vcx00.settings_user set 
		print_tel     =$data_sett[print_tel],
print_fax     =$data_sett[print_fax],
print_mail    =$data_sett[print_mail],
print_web     =$data_sett[print_web],
print_address =$data_sett[print_address],
print_mikud   =$data_sett[print_mikud],        
        print_logo   =$data_sett[print_logo],  
		name='$data_sett[name]',web='$data_sett[web]'	,comments='$data_sett[comments]',barkod_len=$data_sett[barkod_len],desc_len=$data_sett[desc_len],total_len=$data_sett[total_len]				
		where user_id='$userID'";
	}
	else{
		$sql="INSERT INTO vcx00.settings_user (print_tel    ,print_fax    ,print_mail   ,print_web    ,print_address,print_mikud ,print_logo , `user_id`,name,tz,web,logo,comments,barkod_len,desc_len,total_len) 
		VALUES ($data_sett[print_tel],$data_sett[print_fax],$data_sett[print_mail],$data_sett[print_web],$data_sett[print_address],$data_sett[print_mikud],$data_sett[print_logo],
		   '$userID','$data_sett[name]','$data_sett[web]','$data_sett[logo]','$data_sett[comments]','$data_sett[barkod_len]','$data_sett[desc_len]','$data_sett[total_len]') ";
	}

	mysql_query($sql);
		echo mysql_error();
		file_put_contents("feigy_setting.log", $sql);


	$sql="SELECT  `ID` FROM  `vcx_weberp`.`listingsStocks` WHERE  `TerminalID` =".$_REQUEST['stock11'];
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);

	if($num>0){
		$sql= "update  `vcx_weberp`.`listingsStocks` set Phone='$data_sett[tel]',Fax='$data_sett[fax]',Mikud='$data_sett[mikud]',Email='$data_sett[mail]',Address='$data_sett[address]' where `TerminalID` =".$_REQUEST['stock11'];
	}



	mysql_query($sql);
	echo mysql_error();
	die();
}







if(isset($_REQUEST['save_details'])){

	$data_sett=$_REQUEST['data_sett'];
	$sql="select id from vcx00.settings_user where user_id='$userID'";

	$result=mysql_query($sql);
	$num=mysql_num_rows($result);
	if($num>0){
		$sql="update vcx00.settings_user set
		name='$data_sett[name]',tz='$data_sett[tz]',tel='$data_sett[tel]',fax='$data_sett[fax]',address='$data_sett[address]',mikud='$data_sett[mikud]',email='$data_sett[mail]',web='$data_sett[web]',
		comments='$data_sett[comments]',barkod_len=".$data_sett[barkod_len]."	,desc_len=".$data_sett[desc_len]."	,total_len=".$data_sett[total_len]."			
		where user_id='$userID'";
	}
	else{
		$sql="INSERT INTO vcx00.settings_user ( `user_id`,name,tz,tel,fax,address,mikud,email,web,logo,comments,barkod_len,desc_len,total_len) 
		VALUES ('$userID','$data_sett[name]','$data_sett[tz]','$data_sett[tel]','$data_sett[fax]','$data_sett[address]','$data_sett[mikud]','$data_sett[mail]','$data_sett[web]','$data_sett[logo]',
		'$data_sett[comments]','$data_sett[barkod_len]','$data_sett[desc_len]','$data_sett[total_len]') ";
	}
	echo $sql;
		mysql_query($sql);
		echo mysql_error();
	die();
}
if(isset($_REQUEST['save_prefers'])){
	$data_sett=$_REQUEST['data_sett'];
	//$data_sett['pr_online'] = $data_sett['pr_online']=='on'?1:0;


		$sql="select id,pr_pass from vcx00.settings where user_id='$userID'";
		$result=mysql_query($sql);
		$num=mysql_num_rows($result);
		$row = mysql_fetch_array($result);
		if(($data_sett['pr_pass']==undefined||$data_sett['pr_pass']=='')&&($data_sett['pr_pass2']!=undefined||$data_sett['pr_pass2']!='')){

		}
		else{
			if(($data_sett['pr_pass']!=$data_sett['pr_pass2'])){
				//echo "נא הקש סיסמא תואמת";
				echo '11';
				die();
			}
			else if($data_sett['pr_currpass']!=$row['pr_pass']){
				//echo $row['pr_pass'];
				//echo "סיסמא נוכחית לא נכונה";
				echo '22';
				die();
			}
		}

		if($num>0){
			$data_sett[pr_viewprod]=$data_sett[pr_viewprod]=='on'?1:0;
			$data_sett[pr_weight_no_vat]=$data_sett[pr_weight_no_vat]=='on'?1:0;
			$data_sett[pr_save_mizdamen]=$data_sett[pr_save_mizdamen]=='on'?1:0;
            $data_sett[pr_viewpayment_mezuman]=$data_sett[pr_viewpayment_mezuman]=='on'?1:0;
			$data_sett[pr_print_temp]=$data_sett[pr_print_temp]=='on'?1:0;
            $data_sett[pr_viewpayment_amchaa]=$data_sett[pr_viewpayment_amchaa]=='on'?1:0;
            $data_sett[pr_viewpayment_credit]=$data_sett[pr_viewpayment_credit]=='on'?1:0;
            $data_sett[pr_viewpayment_shovarzicuy]=$data_sett[pr_viewpayment_shovarzicuy]=='on'?1:0;
            $data_sett[pr_viewpayment_akafa]=$data_sett[pr_viewpayment_akafa]=='on'?1:0;
            $data_sett[pr_viewpayment_prepaid]=$data_sett[pr_viewpayment_prepaid]=='on'?1:0;
            $data_sett[pr_viewpayment_shovar]=$data_sett[pr_viewpayment_shovar]=='on'?1:0;
            $data_sett[pr_show_checkcheque_button]=$data_sett[pr_show_checkcheque_button]=='on'?1:0;
            $data_sett[pr_global_item]=$data_sett[pr_global_item]=='on'?1:0;
            $data_sett[pr_tip]=$data_sett[pr_tip]=='on'?1:0;
			$data_sett[pr_online]=$data_sett[pr_online]=='on'?1:0;
			$pr_themes=str_replace("theme", "", $data_sett[pr_themes]);
			/*$sql="update vcx00.settings set
			pr_lang='$data_sett[pr_lang]',pr_currency='$data_sett[pr_currency]',
			pr_theme='$pr_themes',pr_online='$data_sett[pr_online]',
			pr_slika='$data_sett[pr_slika]',pr_masof='$data_sett[pr_masof]',
			pr_numcust='$data_sett[pr_numcust]',pr_currnumcust='$data_sett[pr_currnumcust]',pr_group='$data_sett[pr_group]',
			pr_viewprod='$data_sett[pr_viewprod]',pr_pass='$data_sett[pr_pass]'
			where user_id='$userID'";*/
			/*sk 07/01/16 shao payments buttomn in main page*/
			$sql="update vcx00.settings set
			pr_lang='$data_sett[pr_lang]',pr_currency='$data_sett[pr_currency]',
			pr_theme='$pr_themes',pr_online='$data_sett[pr_online]',
			pr_slika='$data_sett[pr_slika]',pr_masof='$data_sett[pr_masof]',
			pr_numcust='$data_sett[pr_numcust]',pr_currnumcust='$data_sett[pr_currnumcust]',pr_group='$data_sett[pr_group]',
			pr_viewprod='$data_sett[pr_viewprod]',		
			pr_save_mizdamen='$data_sett[pr_save_mizdamen]',
			pr_viewpayment_mezuman='$data_sett[pr_viewpayment_mezuman]',
			pr_viewpayment_amchaa='$data_sett[pr_viewpayment_amchaa]',
			pr_viewpayment_credit='$data_sett[pr_viewpayment_credit]',
			pr_viewpayment_shovarzicuy='$data_sett[pr_viewpayment_shovarzicuy]',
			pr_viewpayment_akafa='$data_sett[pr_viewpayment_akafa]',
			pr_viewpayment_prepaid='$data_sett[pr_viewpayment_prepaid]',
			pr_viewpayment_shovar='$data_sett[pr_viewpayment_shovar]',
			global_item ='$data_sett[pr_global_item]',
			tip ='$data_sett[pr_tip]',
			pr_pass='$data_sett[pr_pass]',show_cash_button='$data_sett[check_cash_button]',
			show_credit_button='$data_sett[check_credit_button]',show_cheque_button='$data_sett[check_cheque_button]',
			show_shovar_button='$data_sett[check_shovar_button]'
			,show_shovarzicuy_button='$data_sett[check_shovarzicuy_button]',show_prepaid_button='$data_sett[check_prepaid_button]'
			,show_akafa_button='$data_sett[check_akafa_button]'		
			,pr_weight_no_vat='$data_sett[pr_weight_no_vat]'		
			where user_id='$userID'";

		}
		mysql_query($sql);
		die();

}
if(isset($_REQUEST['pass_manager'])){
		$pass_manager=$_REQUEST['pass_manager'];
		$sql="select id,pr_pass from vcx00.settings where user_id='$userID'";
		$result=mysql_query($sql);
		$num=mysql_num_rows($result);
		$row = mysql_fetch_array($result);

		if($pass_manager!=$row['pr_pass']){
				echo 0;
				die();
		}
		echo 1;
		die();

}

if(isset($_REQUEST['settings_start'])){
		$sql="select id from vcx00.settings_user where user_id='$userID'";
		$result=mysql_query($sql);
		$num=mysql_num_rows($result);
		if($num<=0){
			$sql="select * from vcx00.users where id='$userID'";
			$x = mysql_query($sql);
			if($x){
				$row=mysql_fetch_array($x);
				$sql="INSERT INTO vcx00.settings_user ( `user_id`,name,tz,email) 
				VALUES ('$userID','$row[name]','$row[businessnum]','$row[email]') ";
				mysql_query($sql);
				echo mysql_error();
			}
		}

		$sql="select id from vcx00.settings where user_id='$userID'";
		$result=mysql_query($sql);
		$num=mysql_num_rows($result);
		if($num<=0){
			$sql="INSERT INTO vcx00.settings (`user_id`) VALUES ('$userID') ";
			mysql_query($sql);
			echo mysql_error();
		}
		die();

}
/*sk 15/02/16 */
if(isset($_REQUEST['checked_value'])){

		$checked_value=$_REQUEST['checked_value'];

		$checked=$_REQUEST['checked'];

		if($checked=='true'){
            $sql="select count(*) as cd from `vcx_".$username."`.`title_definition`";
            $result=mysql_query($sql);
			if($result){
				$row=mysql_fetch_array($result);
				$count=$row['cd'];

			}
			if($count==8){
				die('1');
			}
			$sql= "INSERT INTO `vcx_".$username."`.`title_definition` (`title_id`) VALUES ('".$checked_value."')";
			mysql_query($sql);
			die('0');
		}
		else if($checked=='false'){

			$sql="delete from `vcx_".$username."`.`title_definition` where `title_id`=".$checked_value;

			mysql_query($sql);
			die('0');
		}
	}
?>