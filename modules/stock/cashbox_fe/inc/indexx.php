<!DOCTYPE html>
<html lang="he" ng-app="cashbox">
  <head>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta charset="UTF-8">
  <?
 
  	function buildSelect($select_join,$fld_id,$fld_name,$select_name,$select_tag="",
						$selected_id=-1, $where="",$select_tag2="",$select_tag_option="",$def=-1,
						$onChange="",$caption="בחר"){
			
		if (!strstr($select_join,"select")) {
			$select_join = "select * from $select_join ";
		}


		if (strlen($onChange)>0){ //דוגמה: $onChange= index.php?id_city= 
			$tag3 = <<<EOT
onchange="document.location.href='$onChange' + this.value "
EOT;
		}

		$s = "$select_join $where ";
		//echo $s;
		$x = mysql_query($s);
		echo "<select $tag3 $select_tag $select_tag2 name='$select_name' id='$select_name'>";

		//if ($selected_id < 1){
		echo "<option $select_tag_option value=-1>$caption</option>";
		//}


		while ($r = mysql_fetch_array($x)){
			$id = $r[$fld_id];

			$names = explode(",",$fld_name);
			if($fld_name=='Mezahe,Teur'){
				$Tarich=$r[Tarich];
				$name = date('my',strtotime($Tarich))." -";
			}
			else{
				$name = "";
			}
			
            foreach ( $names as $key_value ){
            	$name = $name . " " . $r[$key_value];
            }

			$name = implode(' ', array_slice(explode(' ', $name), 0, 20));
			
			//$name = $r[$fld_name];

			$sel = "";
			if ($selected_id == $id || $def == $id)
				$sel=' selected="selected" ';
			echo "<option $select_tag_option $sel value='$id'>$name</option>";
		}
		echo "</select>";
	}
  	if(isset($_REQUEST[online])){
			$online=$_REQUEST[online];		
	}
	else{
		$online='on';
	}	
  	//include 'prodj2.php';
  	$cashbox=true;
	//print_r($_COOKIE);
	//require("../include/common.php");
	$simple=1;
	
	$flg=0;
	require("../include/common.php");
	
	include "inc/conn.inc";
	$rootdir 	=$GOCONFIG->file_storage_path;
	$stock=$_REQUEST['stock'];
	
	if(!isset($_REQUEST['stock'])){
		$query="select ID,StockName,TerminalID,
	 initBalance, 
	initBalanceOfficeUserId  as initBalanceUser, 
	initBalanceTimestamp as initBalanceDate,
	SortOrder from vcx_weberp.listingsStocks s where TerminalID!=999999 and user_ID = $userID and Status=1 
    and (id in (select stock_id from vcx_weberp.userstocks where userid = $userID2 and r=1) or $userID2=$userID)
    order by SortOrder, binary StockName";
	//echo $query;
    $result=mysql_query($query);
		if(mysql_num_rows($result)==1){
			$row=mysql_fetch_array($result);
			$stock=$row[TerminalID];
			
		}
		else{
			$stock=-1;
		}
	}
	$rootdir.="$username/POS/$stock";
	$target_image_dir="../../../officefiles/".$username."/_MlaitekPro";
	if($stock>1){
		function sql2json($query) {
    $data_sql = mysql_query($query) or die("'';//" . mysql_error().$query."<hr>");// If an error has occurred, 
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
                //If it is an associative array we want it in the format of "key":"value"
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
			$sql="select StockName,ID from vcx_weberp.listingsStocks where TerminalID=$stock and user_ID=$userID";
			//echo $sql;
			$dbname 	= $GOCONFIG->stock_db_name;

			$rs = $conn->Execute($sql);
			
			while (!$rs->EOF) {
				$stockname=$rs->fields[StockName];
				$stockid=$rs->fields[ID];			
				$rs->MoveNext(); 
			}
			$dbname="vcx_$username";
		$sql="select action,id from vcx_$username.cashbox_actions where terminal_id=$stock order by id desc limit 1";
			$rs = $conn->Execute($sql);
					
			if($rs->EOF){
				$stat="close_cashbox";
			}	
			else{
				$stat=$rs->fields[action];				
			}
			//echo $stat;
			
			

	}
//
?>

    <!--src="../../../officefiles/yedatop/POS/$stockname/prod.js" -->
    <script src="../../../officefiles/<?=$username?>/POS/<?=$stock?>/prod<?=date("dmy")?>.js"  type="text/javascript"></script>
    
    <link href="js/bootstrap/bootstrap.min.css" rel="stylesheet" media="screen"> 
 	<!--<script src="angucomplete-alt-master/angucomplete-alt.css"  rel="stylesheet" ></script> -->
    <script src="js/angular.min.js" type="text/javascript"></script>
    <script src="js/angular-ng-touch.js" type="text/javascript"></script>
<!--<script src="js/datepicker.js" type="text/javascript"></script>-->
    <script src="http://hamefits.com/proj/yitzhar/cashbox.js" type="text/javascript"></script>

    <!--<script src="js/cashbox5.js" type="text/javascript"></script>-->
    <script src="js/product.js" type="text/javascript"></script>
    <script src="js/client2.js" type="text/javascript"></script>
    <script src="js/worker.js" type="text/javascript"></script>
    <script src="js/payment8.js" type="text/javascript"></script>
    <script src="js/jquery.cookie.js" type="text/javascript"></script>
	<script src="js/anacha.js" type="text/javascript"></script>
	<script src="js/pause.js" type="text/javascript"></script>	
	<script src="js/achlafa.js" type="text/javascript"></script>
	<script src="js/keyboard.js" type="text/javascript"></script>
	<script src="js/debtpayment.js" type="text/javascript"></script>
	<script src="js/add_prod.js" type="text/javascript"></script>
	<script src="js/settings.js" type="text/javascript"></script>
	<script src="js/fastclick.js" type="text/javascript"></script>

    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet"> 
    <link href="css/style.css" rel="stylesheet" media="screen"> 
    <!--[if IE]>
		<link rel="stylesheet" type="text/css" href="css/ie.css" />
	<![endif]-->    
	
    
   <!-- <object style="display:none" id="SOME_ID" classid="clsid:SOME_CLASS_ID" codebase="./somePath.dll"></object>-->
   
    <!--<script src="angucomplete-alt-master/angucomplete-alt.js" type="text/javascript"></script>-->
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <!--<script src="js/jquery.cookie.js" type="text/javascript"></script>-->
    <!--<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">-->
     <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
     <script src="http://hamefits.com/proj/yitzhar/script.js" type="text/javascript"></script>
     <!--<script src="js/script.js" type="text/javascript"></script>-->
  </head>
  <body class="" onload="" ng-controller="CashboxController as cashc">
  
    

  	<span id="fastclick">
	<div class="disable_cash" style="position: absolute;width:100%;height:100%;z-index: 999;background: rgba(31, 30, 30, 0.7)"></div>
  	<script>  

  	function barcode(str){ 		
  		//alert(str);
  		var scope = angular.element(document.getElementById("product_main")).scope().search_barcode2(str);
  		
  	}
  	if(typeof android!='undefined'){
  		android.hideKeyboard(); 	
  	}
  	var link="../../../officefiles/<?=$username?>/POS/<?=$stock?>/prod<?=date("dmy")?>.js";	
		function invoice(){
			android.invoice_details();
		}  
		
  	</script>  
  	<?

  	if($stock<1){ 
  		
	?>
	<script>
		$(document).ready(function(){ 	
		
   alert(navigator.appVersion +navigator.appName );

			$('#select_kupa').change(function(){				
				if($('#select_kupa').val()!=-1){
					window.location.href="?stock="+$('#select_kupa').val();					
				}
			});
		});
	</script>
		<div style="position: absolute;width:100%;height: 100%;z-index: 1000;background: rgba(40, 38, 38, 0.9);">
			<div style="background-color: white;z-index: 2000;width: 400px;height: 208px;margin: 0 auto;margin-top: 100px;padding: 50px;">
				<h2 style="text-align: center;font-size: 170%;">בחר קופה:</h2>
				<select id="select_kupa" style="font-size: 120%;margin: 66px;">
					<option value="-1">בחר קופה</option>
		
<?
						$dbname 	= $GOCONFIG->db_name;
						$sql = "select TerminalID, StockName 
						from $TABLE_LISTINGSSTOCKS s
						where user_id = $userID AND TerminalID !=999999";
						/*$sql="SELECT TerminalID, StockName  FROM listingsStocks where StockName != ''  and (user_id = (select stock_user from vcx00.users where id = '$user_id') or user_id = '$user_id') ORDER BY StockName";*/
						//echo "<!--sql:$sql-->";
						$rs = $conn->Execute($sql);
						while (!$rs->EOF) {
							$stock=$rs->fields[StockName];
							$term=$rs->fields[TerminalID];
							echo "<option value='$term'>$stock</option>";
							$rs->MoveNext(); 
						}

?>					
				</select>
			</div>
		</div>
		
<?
	}
else{
?>
			<script>
			$(document).ready(function(){ 
				$(".openclose.cl").css({position: 'absolute'});
			});
			</script>
<?	
}

if(isset($_REQUEST[yitrat_kupa])){
	$sql="select max(ID) as JournalNum2 from vcx_$username.transactionpackages";
	$dbname 	= $GOCONFIG->stock_db_name;
	$flg=1;
				$rs = $conn->Execute($sql);
				
				if($rs->EOF){
					$journal=0;
				}
				else{
					$journal=$rs->fields[JournalNum2];	
				}
				$journal=$journal+1;
				$date1=date("Y-m-d h:i:s");
				$date2=time();
				$sql="INSERT INTO vcx_$username.transactionpackages( `stock_id`, `TerminalID`, `JournalNum`, `TransCount`, `JournalDate`, `DateTimeStamp`,`start_cash`)
				VALUES ('$stockid','$stock','$journal','0','$date1','$date2','$_REQUEST[yitrat_kupa]')";
				$rs = $conn->Execute($sql);	
				$sql="";		
				$trannum=1;			
				$sql="insert into vcx_$username.cashbox_actions (action,terminal_id) values ('open_cashbox',$stock)";
				mysql_query($sql);
				?>
				<script>
				function createCookie(name,value,days) {
				    if (days) {
				        var date = new Date();
				        date.setTime(date.getTime()+(days*24*60*60*1000));
				        var expires = "; expires="+date.toGMTString();
				    }
				    else var expires = "";
				    document.cookie = name+"="+value+expires+"; path=/";
				}
				createCookie("journal","<?=$journal?>",7);
				</script>
				<?
				//$journal=mysql_insert_id();
				echo "<!--journal:$journal-->";
				
				$stat="open_cashbox";	
				
?>

				<script>
					var journal=<?=$journal?>;
					var start_cash=<?=$_REQUEST[yitrat_kupa]?>;
					var online="<?=$online?>";
					
				</script>
				
<?				
					
	}
	elseif(isset($_REQUEST[machzor])){
		$sql="insert into vcx_$username.cashbox_actions (action,terminal_id) values ('close_cashbox',$stock)";
				mysql_query($sql);
				
				$stat="close_cashbox";
				$sql="select max(JournalNum) as JournalNum2,TransCount from vcx_$username.transactionpackages where TerminalID=$stock";
		
		$rs = $conn->Execute($sql);
		
		$journal=$rs->fields[JournalNum2];
		if(isset($_COOKIE[trannum])){
			$sql="update vcx_$username.transactionpackages set TransCount=$_COOKIE[trannum] where JournalNum=$journal and TerminalID=$stock";
			$trannum=$_COOKIE[trannum];
		}
		else{
			$trannum=$rs->fields[TransCount]+1;
		}
		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/modules/stock/cashbox2/bill/json'))
		{
		    $put=file_get_contents($_SERVER['DOCUMENT_ROOT'].'/modules/stock/cashbox2/bill/json');
		  //  $put=substr($put,1,-1);
		    $put='http://a.yeda-t.com/modules/stock/plujson.php?com=trans|yedatop|'.$stock.'|{"transactions":{"terminal":"'.$stock.'","transaction":'.$put.'}}';
		//echo	urlencode($put);
		$put = preg_replace('/\s+/', '', $put);
	       $answer= file_get_contents($put);
	       if(trim($answer) == "ok"){
		   //file_put_contents($_SERVER['DOCUMENT_ROOT'].'/modules/stock/cashbox2/bill/json', "[]");
		   }
			else{
				echo "errorrrrrrrrrrrrrrr $answer";
			}
	      
		    //$put=json_decode($put,true);
		}
	}
	else{
		$sql="select max(ID) as JournalNum2,TransCount,start_cash from vcx_$username.transactionpackages where TerminalID=$stock";
		
		$rs = $conn->Execute($sql);
		
		$journal=$rs->fields[JournalNum2];
		$start_cash=$rs->fields[start_cash]; 
		/*if(isset($_COOKIE[trannum])){
			$sql="update vcx_$username.transactionpackages set TransCount=$_COOKIE[trannum] where id=$journal and TerminalID=$stock";
			$trannum=$_COOKIE[trannum];
		}
		else{
			$trannum=$rs->fields[TransCount]+1;			
		}*/

?>

				<script>
					var journal=<?=$journal?>;
					var start_cash=<?=$start_cash?>;
					var online="<?=$online?>";
				</script>
				
<?				
	
	}
	if($stat=="open_cashbox" && $flg!=1){
				if($_COOKIE[journal]!=$journal){
					$stat="close_cashbox";
					
					?>
				<label style="z-index: 9999999999999999;position: absolute;z-index: 1000;font-size: 150%;color: white;top: 85px;right: 200px;">
				
הקופה פתוחה במחשב אחר על מנת לסגור את הקופה במחשב השני ולפתוח מחדש לחץ על פתיחת קופה				
</label>
					<?
				}
			}
	/*$sql = "
			select *,CASE
           WHEN lse.SalePrice is NULL THEN ld.SalePrice          
           ELSE lse.SalePrice
        END as SalePrice,ld.ID as ID from  vcx_$username.listingsDB as ld LEFT OUTER JOIN vcx_$username.listingsStocksElements as lse on lse.ListingID=ld.ID and lse.StockID=$stockid limit 8000 ";
	$productj=sql2json($sql);
	
	
	
	?>
	<script>
			var productj=<?=$productj?>;
	</script>
	<script>
	/*var productj="";
	$.ajax({
					url : 'get_data.php',
					type : 'POST',					
					data : {
						stat2 :  "prod",
						stock:5000011
					},
					error : function() {
						alert('k');
						return true;
					},
					success : function(data) {
						
						 var start = data.indexOf("jsonprod:");
					   	var end = data.indexOf(":jsonprod");
					   	var len="jsonprod".length+1;
					   	str=data.substr(start+len,end-start-len);
					   	productj=JSON.parse(str);
					   	alert(str);
					}
				});
			*/
	</script>
  	<script type="text/javascript">

	
  	function launchFullScreen(element) {
	  if(element.requestFullScreen) {
	    element.requestFullScreen();
	  } else if(element.mozRequestFullScreen) {
	    element.mozRequestFullScreen();
	  } else if(element.webkitRequestFullScreen) {
	    element.webkitRequestFullScreen();
	  }
	}
  	function full_screen(){		
  		if((window.fullScreen) ||
		   (window.innerWidth == screen.width && window.innerHeight == screen.height)) {
			// Launch fullscreen for browsers that support it!
				launchFullScreen(document.documentElement); // the whole page
				
				//launchFullScreen(document.getElementById("videoElement")); // any individual element
		} else {
		
		}
		
  	} 

</script>
<?
if($stat=="open_cashbox"){
				?>
				<script>
				$(document).ready(function(){
					$(".disable_cash").hide();	
					$(".clock").css("position","inherit");
				});
					
				</script>
<?	
			}
$sql="select * from vcx00.settings where user_id='$userID'";
$premission_list=sql2json($sql);
$result=mysql_query($sql);	

while ($row = mysql_fetch_array($result)) {
    $charge_worker=$row['charge_worker'];
}

$sql="select * from vcx00.settings_user where user_id='$userID'";
$details_list=sql2json($sql);
?>
				<script>
					var charge_worker="<?=$charge_worker?>";
					var premission_list= <?=$premission_list?>;
					var details_list= <?=$details_list?>;
				</script>
<div class="wrap display"></div>
	
	
	<anacha></anacha>	
	<settdetails></settdetails>
	<addcust></addcust>
	<beynaim></beynaim>
	<achlafa></achlafa>	
	<pause></pause>
	<clock></clock>
	<prepaid></prepaid>	
	<comment></comment>
	
<div class="popup pop_peulot popup_pay add_worker" ng-controller="PaymentController  as payment">
		<!--<img src="images/box_large.png" class="img_wrap"/>	-->		
		<div class="container_pop add_worker" ng-controller="WorkerController  as workers">
			<h2 class="relative">סה"כ לתשלום: <span style="font-size: 100%">₪</span><span class="span_sum zerofloat">{{amount}}</span></h2>
			<h5>הוסף עובד למכירה:</h5>
			
			<table class="workers_tb" style="width: 93%;">
				<tr>
					 <th>שם עובד</th>
					 <th>טלפון</th>
					 <th>נייד</th>
					 <th>אימייל</th>
					 <th>מס"ד</th>
					 <th></th>
				</tr>
				
				<tr class="worker_{{worker1.WorkerNum}}" ng-repeat="worker1 in workerslist" data-id="{{worker1.WorkerNum}}" ng-click="workers.choose_worker(this,worker1.WorkerNum)" >
					<td>{{worker1.SupplierName}}</td>
					<td>{{worker1.Phone}}</td>
					<td>{{worker1.CellPhone}}</td>
					<td>{{worker1.Email}}</td>
					<td>{{worker1.WorkerNum}}</td>
					<td><i  class='fa fa-check-circle display'></i></td>
				</tr>		
			</table>
			<input class="mybtn btngray rightbottom" type="button" value="ביטול" onclick="$( '.wrap' ).click();">
			<input class="mybtn btnblue leftbottom"  ng-click="workers.save_choose();openwrap2('.add_worker.container_pop','.type_pay.container_pop');start_paying()" type="button" value="בחירה">
		</div>
		
		<div class="container_pop type_pay display" >
			<h2 class="relative">סה"כ לתשלום: <span style="font-size: 100%">₪</span><span class="span_sum zerofloat">{{amount}}</span></h2>
			<h5>בחר אמצעי תשלום:</h5>
			<div class="type_half right" >
				 <!--localStorage.getItem('products');-->
				<div class="type_b">
					<input class="mybtn btnorange " type="button" value="מזומן" ng-click="openwrap2('.type_pay.container_pop','.mezuman.container_pop');payment.init_sum()">
					<input class="mybtn btnblue " type="button" value="המחאה"  ng-click="openwrap2('','.amchaa.container_pop');payment.init_sum()">
					<input class="mybtn btngreen " type="button" value="אשראי"  ng-click="openwrap2('','.ashray.container_pop');payment.init_sum()">
					<input class="mybtn btngreen " type="button" value="אשראי ידני"  ng-click="openwrap2('','.ashray_yadany.container_pop');payment.init_sum()">
				</div>
				<div class="type_s right type_b">
					<input class="mybtn btngray " type="button" value="שובר"   ng-click="openwrap2('','.shovar.container_pop');payment.init_sum()">
					<input class="mybtn btngray " type="button" value="כרטיס מתנה" ng-click="openwrap2('','.prifeyd.container_pop');payment.init_sum();prepaid_start();">
					<input class="mybtn btngray " type="button" value="שובר זיכוי"  ng-click="openwrap2('','.shovarzicuy.container_pop');payment.init_sum();start_shovarzivuy();">
					<input class="mybtn btnlightgray " type="button" value="יציאה" ng-click="payment.is_charge()">
				</div>
				<div class="type_s left type_b">
					<input class="mybtn btngray " type="button" value="הקפה"   ng-click="openwrap2('','.akafa.container_pop');payment.init_sum()">
					<input class="mybtn btngray " type="button" value="סילאריקס">
					<input class="mybtn btngray " type="button" value="אחר">
					<!--<input class="mybtn btnblue " type="button" value="אישור" class="end_btn"  ng-click="cashc.end_cash(0)" onclick="$('input[type=text]').val('');$('.text').val('');openwrap2('','.finish.container_pop')">-->
				</div>
			</div>
			
			<input type="hidden" name="helpsum"/>
			<div class="type_half left">
				
				<table class="workers_tb">
					<tr>
						 <th>סוג תשלום</th>
						 <th>סכום</th>
						 <th  ng-click="payment.add_type('.mezuman_sum','מזומן')"></th>
					</tr>	
					<tr  ng-repeat="pay in payments_type.cash">
						<td>{{pay.type}}</td>
						<td>{{pay.amount}}</td>
						<td><i class="fa fa-times-circle" ng-click="payment.remove_item('cash',$index)"></i></td>
					</tr>						
					<tr  ng-repeat="pay in payments_type.cheque" >
						<td>{{pay.type}}</td>
						<td>{{pay.amount}}</td>
						<td><i class="fa fa-times-circle" ng-click="payment.remove_item('cheque',$index)"></i></td>
					</tr>	
					<tr  ng-repeat="pay in payments_type.credit">
						<td>{{pay.type}}</td>
						<td>{{pay.amount}}</td>
						<td><i class="fa fa-times-circle" ng-click="payment.remove_item('credit',$index)"></i></td>
					</tr>
					<tr  ng-repeat="pay in payments_type.akafa">
						<td>{{pay.type}}</td>
						<td>{{pay.amount}}</td>
						<td><i class="fa fa-times-circle" ng-click="payment.remove_item('akafa',$index)"></i></td>
					</tr>
					<tr  ng-repeat="pay in payments_type.shovar">
						<td>{{pay.type}}</td>
						<td>{{pay.amount}}</td>
						<td><i class="fa fa-times-circle" ng-click="payment.remove_item('shovar',$index)"></i></td>
					</tr>
					<tr  ng-repeat="pay in payments_type.shovarzicuy">
						<td>{{pay.type}}</td>
						<td>{{pay.amount}}</td>
						<td><i class="fa fa-times-circle" ng-click="payment.remove_item('shovarzicuy',$index)"></i></td>
					</tr>
					<tr  ng-repeat="pay in payments_type.prepaid">
						<td>{{pay.type}}</td>
						<td>{{pay.amount}}</td>
						<td><i class="fa fa-times-circle" ng-click="payment.remove_item('prepaid',$index)"></i></td>
					</tr>
				</table>
			</div>
			<div class="leftbottom">
				<p class="pay_p"><span class="right">התקבל:</span><span class="left" style="width: 25px;font-size: 105%;"> ₪ </span><span class="left type_finall_sum zerofloat">{{paid}}</span></p>
				<p class="itra_p"><span class="right">יתרה לתשלום:</span><span class="left" style="width: 25px;font-size: 105%;"> ₪ </span><span class="left span_sum span_itra zerofloat">{{itra_calc()}}</span></p>
			</div>
		</div>
		<div class="container_pop mezuman display" >
			<h2 class="relative">תשלום במזומן</h2>
			<h5>לתשלום:<span style="font-size: 100%;color: #fa6f58;">₪</span><span class="span_sum zerofloat" style="color: #fa6f58;">{{itra}}</span></h5>
			<input type="text" value="{{itra}}" id="mezuman_itra" class="input_sum mezuman_sum zerofloat" onchange="if($(this).val()=='')$(this).val(0.00);" ng-blur="payment.calc2($('mezuman_sum').val())"/>
			<div class="shtar">
				<img src="images/money8.png"  ng-click="payment.calc1('200')">
				<img src="images/money2.png"  ng-click="payment.calc1('100')">
				<img src="images/money1.png"  ng-click="payment.calc1('50')">
				<img src="images/money7.png"  ng-click="payment.calc1('20')">				
			</div>
			<div class="matbea">
				<img src="images/money3.png" ng-click="payment.calc1('10')">
				<img src="images/money6.png" ng-click="payment.calc1('5')">
				<img src="images/money9.png" ng-click="payment.calc1('2')">
				<img src="images/money10.png" ng-click="payment.calc1('1')">
				<img src="images/money5.png" ng-click="payment.calc1('0.5')">
				<img src="images/money4.png" ng-click="payment.calc1('0.1')">
			</div>
			<input class="mybtn btnlightgray rightbottom" type="button" value="ביטול" onclick="openwrap2('','.type_pay.container_pop')">
			<input class="mybtn btnblue leftbottom" type="button" value="אישור"  ng-click="payment.check_end2('.mezuman');payment.add_type('.mezuman_sum','מזומן');cashc.checkend();" >
		</div>
		<div class="container_pop finish display">
			<h2 class="relative">סיום עסקה</h2>
			<p style="font-size: 224%;margin-top: 20px;margin-bottom: 10px;">העסקה בוצעה בהצלחה</p>	
			<p  style="font-size: 224%; " class="change">עודף: {{itraAbs}}</p>			
		</div>
		<div class="container_pop amchaa display">			
			<h2 class="relative" >תשלום בהמחאה</h2>
			<h5>לתשלום:<span style="font-size: 100%;color: #fa6f58;">₪</span><span class="span_sum zerofloat"  style="color: #fa6f58;">{{itra}}</span></h5>
			<form class="amchaa_frm" style="width: 64%;margin: 0 auto;">
				<input type="text" value="0.00" class="text amchaa_sum input_sum zerofloat" />
				<div style="width:30%;margin-right: 2%;float:right">
					<p>מספר המחאה:</p>
					<p>תאריך:</p>
					<p>מספר בנק:</p>
					<p>מספר סניף:</p>
					<p>מספר חשבון:</p>
				</div>
				
				<div style="width:60%;float:left">
					<input name="chequenumber" class="text required" />
					<input type="date" id=""  name="chequepaymentdate"  class="text required" />
					<input name="chequebanknumber"  class="text required" />
					<input name="chequebranch"  class="text required" />
					<input name="chequeaccountnumber" class="text required" />
				</div>	
			</form>

			<div class="allbottom">
				<input class="mybtn btnlightgray " type="button" value="ביטול" onclick="openwrap2('','.type_pay.container_pop')">
				<!--<input class="mybtn btngreen " type="button" value="בדיקת המחאה" onclick="openwrap2('','')">-->
				<input class="mybtn btnorange " type="button" value="מחולל התשלומים" onclick="$('input[name=mecholel_numtash]').val('2');get_val('.amchaa_sum','.amchaa_frm  .mecholelsum_text');calc_tash('1');openwrap2('','.mecholel_amchaa.container_pop');init_sum()">
				<input class="mybtn btnblue " type="button" value="אישור" ng-click="payment.check_end2('.mecholelsum_text');payment.add_type('.amchaa_sum','המחאה');cashc.checkend();" >
			</div>
		</div>
		<div class="container_pop ashray display"> 
			<h2 class="relative">תשלום באשראי</h2>
			<h5>לתשלום:<span style="font-size: 100%;color: #fa6f58;">₪</span><span class="span_sum zerofloat" style="color: #fa6f58;">{{itra}}</span></h5>
			<form class="amchaa_frm" style="width: 64%;margin: 0 auto;">
				<input type="text" value="0.00" class="text input_sum ashray_text zerofloat" />		
				<input type="text" style="  background: transparent; border: transparent;  outline: none;color:transparent;position: absolute" value="" class="text input_num ashray_text zerofloat" />			
			</form>			
			<p style="font-size: 224%;margin-top: 20px;margin-bottom: 10px;">העבר כרטיס אשראי</p>
			<input class="mybtn btnorange " type="button" value="הקלדה ידנית" style="width: 64%;"onclick="openwrap2('','.ashray_yadany.container_pop');init_sum()"> 
			<input class="mybtn btnlightgray rightbottom" type="button" value="ביטול" onclick="openwrap2('','.type_pay.container_pop')">
			<input class="mybtn btnblue leftbottom" type="button" value="אישור" ng-click="payment.check_end2('.ashray');payment.add_type('.ashray_text','אשראי');cashc.checkend();"   >
		</div>
		<div class="container_pop ashray_yadany display">
			<h2 class="relative">תשלום באשראי - ידני</h2>
			<h5>לתשלום:<span style="font-size: 100%;color: #fa6f58;">₪</span><span class="span_sum zerofloat"  style="color: #fa6f58;">{{itra}}</span></h5>
			<form class="amchaa_frm" style="width: 64%;margin: 0 auto;">
				<input type="text" value="0.00" class="input_sum text ashray_yadany_text zerofloat" />
				<div style="width:30%;margin-right: 2%;float:right">
					<p>מספר כרטיס:</p>
					<p>תוקף כרטיס:</p>
					<p>cvv:</p>
					<p>תשלומים:</p>
				</div>
				<div style="width:60%;float:left">
					<input class="text" name="ashray_numcard"/>
					<input  class="text" name="ashray_tokef"/>
					<input  class="text" name="ashray_cvv"/>
					<input  class="text" style="display: none" name="ashray_tashlumim"/>
				</div>	
			</form>
			
			<div class="allbottom">
				<input class="mybtn btnlightgray " type="button" value="ביטול" onclick="openwrap2('','.type_pay.container_pop')">
				<input class="mybtn btnorange " type="button" value="אופציות אשראי" onclick="openwrap2('','.option_ashray.container_pop');init_sum()">
				<input class="mybtn btnblue " type="button" value="אישור" ng-click="payment.check_end2('.ashray_yadany');get_val('.ashray_yadany_text','.ashray_text');payment.add_type('.ashray_text','אשראי ידני');cashc.checkend();">
			</div>
		</div>
		<div class="container_pop mecholel_amchaa display">
			<h2 class="relative">מחולל תשלומים</h2>
			<form class="amchaa_frm" style="width: 80%;">
				<div style="width:48%;margin-right: 2%;float:right">
					<p style="margin-top: 10px;">סכום סה"כ:</p>
					<p>מספר תשלומים:</p>
					<p>סכום תשלום ראשון:</p>
					<p>סכום תשלום שני ומעלה:</p>
					<p>תאריך פרעון שיק ראשון:</p>
					<p>מס' שיק  \ שובר ראשון:</p>
					<p>פרטי  בנק:</p>
					<p>מספר חשבון:</p>
				</div>
				<div style="width:50%;float:left">
					<input  class="text mecholelsum_text input_sum zerofloat" style="margin-top: 10px;" onchange="calc_tash('1')"/>
					<input  class="text"  name="mecholel_numtash" onchange="calc_tash('0')" required="required" value="2"/>
					<input  class="text"  name="mecholel_firstsum" onchange="calc_tash1()"/>
					<input  class="text"  name="mecholel_secsum"/>
					<input type="date"  class="text"  name="mecholel_datefirst"/>
					<input  class="text"  name="mecholel_numcheck"/>
					<input  class="text"  name="mecholel_bank" style="width: 48%;float:right"/>
					<input  class="text"  name="mecholel_snif" style="width: 48%;float:left"/>
					<input  class="text"  name="mecholel_bill"/>
				</div>	
			</form>
			
			<div class="allbottom">
				<input class="mybtn btnlightgray " type="button" value="ביטול" onclick="openwrap2('','.amchaa.container_pop')">
				<input class="mybtn btnorange " type="button" value="אופציות אשראי" style="visibility: hidden" onclick="init_sum()">
				<!--<input class="mybtn btngreen " type="button" value="בדיקת המחאה" onclick="openwrap2('','.mecholel.container_pop')">-->
				<input class="mybtn btnorange " type="button" value="חולל" ng-click="check_end2('.mecholelsum_text');payment.cholel();cashc.checkend();" >
			</div>
		</div>
		<div class="container_pop mecholel display">
			<h2 class="relative">מחולל תשלומים</h2>
			<form class="amchaa_frm" style="width: 80%;">
				<div style="width:48%;margin-right: 2%;float:right">
					<p style="margin-top: 10px;">סכום סה"כ:</p>
					<p>סכום תשלום ראשון:</p>
					<p>תאריך פרעון שיק ראשון:</p>
					<p>מס' שיק  \ שובר ראשון:</p>
					<p>מס' בנק:</p>
					<p>מספר סניף:</p>
					<p>מספר חשבון:</p>
				</div>
				<div style="width:50%;float:left">
					<input  class="text mecholelsum_text input_sum zerofloat" style="margin-top: 10px;"/>
					<input  class="text"  name="mecholel1_firstsum"/>
					<input type="date"  class="text"  name="mecholel1_datefirst"/>
					<input  class="text"  name="mecholel1_numcheck"/>
					<input  class="text"  name="mecholel1_bank"/>
					<input  class="text"  name="mecholel1_snif"/>
					<input  class="text"  name="mecholel1_bill"/>
				</div>	
			</form>
			
			<div class="allbottom">
				<input class="mybtn btnlightgray " type="button" value="ביטול" onclick="openwrap2('','.ashray.container_pop')">
				<input class="mybtn btnorange " type="button" value="אופציות אשראי" style="visibility: hidden">
				<!--<input class="mybtn btngreen " type="button" value="בדיקת המחאה" onclick="openwrap2('','.mecholel.container_pop')">-->
				<input class="mybtn btnorange " type="button" value="חולל" onclick="openwrap2('','.ashray_yadany.container_pop');get_val('.mecholel .mecholelsum_text','.ashray_yadany_text')">
			</div>
		</div>
		<div class="container_pop option_ashray display">
			<h2 class="relative">אופציות אשראי</h2>
			<h5>הוסף פרטים:</h5>
			<form class="amchaa_frm" style="width: 64%;margin: 0 auto;">
				<input type="text" value="" class="optionsashray_sum text input_sum" />
				<div style="width:38%;margin-right: 2%;float:right">
					<p>תעודת זהות:</p>
					<p style="line-height: 19px;">3 ספרות אחרונות בגב הכרטיס:</p>
					<p>סוג מטבע:</p>
					<p>מספר אישור:</p>
				</div>
				<div style="width:60%;float:left">
					<input  class="text" name="optionsashray_tz"/>
					<input  class="text" name="optionsashray_threenum"/>
					<select class="text" name="optionsashray_currency">
						<option>שקל</option>
						<option>שטר</option>
					</select>
					<input  class="text" name="optionsashray_numishur"/>
				</div>	
			</form>
			
			<input class="mybtn btnlightgray rightbottom" type="button" value="ביטול" onclick="openwrap2('','.ashray_yadany.container_pop')">
			<input class="mybtn btnblue leftbottom" type="button" value="אישור" onclick="openwrap2('','.ashray_yadany.container_pop');">
		</div>
		<div class="container_pop shovar display">
			<h2 class="relative">תשלום שובר</h2>
			<h5>לתשלום:<span style="font-size: 100%;color: #fa6f58;">₪</span><span class="span_sum zerofloat"  style="color: #fa6f58;">{{itra}}</span></h5>
			<form class="amchaa_frm" style="width: 64%;margin: 0 auto;">
				<input type="text" class="text shovar_sum input_sum zerofloat" value="0.00"  />
				<div style="width:30%;margin-right: 2%;float:right">
					<p>מספר שובר:</p>
				</div>
				<div style="width:60%;float:left">
					<input  class="text" name="shovar_num"/>
				</div>	
			</form>
			
			<div class="allbottom">
				<input class="mybtn btnlightgray " type="button" value="ביטול" onclick="openwrap2('','.type_pay.container_pop')">
				<!--<input class="mybtn btnorange " type="button" value="אופציות אשראי" onclick="openwrap2('','.option_ashray.container_pop')">
				<input class="mybtn btnorange " type="button" value="מחולל התשלומים" onclick="openwrap2('','.mecholel.container_pop')">-->
				<input class="mybtn btnblue " type="button" value="אישור" ng-click="check_end2('.shovar');payment.add_type('.shovar_sum','שובר');cashc.checkend();"  >
			</div>
		</div>
		
		<div class="container_pop shovarzicuy display">
			<h2 class="relative">תשלום שובר זיכוי</h2>
			<h5>לתשלום:<span style="font-size: 100%;color: #fa6f58;">₪</span><span class="span_sum zerofloat"  style="color: #fa6f58;">{{itra}}</span></h5>
			<form class="amchaa_frm" style="width: 64%;margin: 0 auto;">
				<input type="text" class="text shovarzicuy_sum input_sum zerofloat" value="0.00"  />
				<div style="width:30%;margin-right: 2%;float:right">
					<p>מספר שובר:</p>
				</div>
				<div style="width:60%;float:left">
					<input  class="text" name="shovarzicuy_num" />
				</div>	
			</form>
			
			<div class="allbottom">
				<input class="mybtn btnlightgray " type="button" value="ביטול" onclick="openwrap2('','.type_pay.container_pop')">
				<!--<input class="mybtn btnorange " type="button" value="אופציות אשראי" onclick="openwrap2('','.option_ashray.container_pop')">
				<input class="mybtn btnorange " type="button" value="מחולל התשלומים" onclick="openwrap2('','.mecholel.container_pop')">-->
				<input class="mybtn btnblue " type="button" value="אישור" ng-click="payment.add_type('.shovarzicuy_sum','שובר זיכוי');cashc.checkend();"  >
			</div>
		</div>
		
		<div class="container_pop prifeyd display">
			<h2 class="relative">תשלום פריפייד</h2>
			<h5>לתשלום:<span style="font-size: 100%;color: #fa6f58;">₪</span><span class="span_sum zerofloat"  style="color: #fa6f58;">{{itra}}</span></h5>
			<form class="amchaa_frm" style="width: 64%;margin: 0 auto;">
				<input type="text" value="0.00" class="text zerofloat prepaid_sum input_sum" ng-blur="prepaid_start();"/>					
			</form>
			<p style="font-size: 224%;margin-top: 20px;margin-bottom: 10px;">העבר כרטיס פריפייד</p>
			<input type="text" name="prepaid_num" class="zerofloat prepaid_num"  style="background: transparent;border: transparent;color: transparent;outline: transparent;" />
			<input class="mybtn btnlightgray rightbottom" type="button" value="ביטול" onclick="openwrap2('','.type_pay.container_pop')">
			<input class="mybtn btnblue leftbottom" ng-click="payment.add_type('.prepaid_sum','פריפייד');cashc.checkend();"   type="button" value="לתשלום">
		</div>
		<akafa></akafa>
		
	</div>
	<addprod></addprod>
	
	<div class="popup pop_peulot  pop_debtpayments" style="z-index: 9999;left:25%" ng-controller="DebtController">
		<!--<img src="images/box_small.png" class="img_wrap"/>	-->	
		<div class="container_pop debt" ng-controller="Client">
		<h2 class="relative">תשלום חוב</h2>
			<p style="font-size: 200%;margin-top: 20px;margin-bottom: 10px;">הכנס לקוח</p>
			<!--<div class="large-padded-row" style="  display: inline-block; width: 96%;" >
			      <div angucomplete-alt id="cust_search" placeholder="חיפוש לקוחות" pause="100" selected-object="selectedClient" local-data="clients" search-fields="SupplierName,sortorder,CellPhone" title-field="SupplierName,CellPhone"  minlength="1" input-class="form-control form-control-small  my_search_auto" match-class="highlight" clear-selected="true" >
			     </div> 
			</div>-->
			<input type="text" class="search_input my_search_auto cust_search_value c1" style="width: 75.5%;  margin-bottom: 20px;" placeholder="חיפוש לקוחות">
			<input type="button" ng-click="search_c();" class="mybtn btnorange " value="חפש">
				<table class="workers_tb akafa_client_tb cl_search" style="  margin-top: 20px;width: 93%;margin: 0 auto;display: inline-block;" >	
				<tr>
					<th>שם</th>
					<th>מספר</th>
					<!--<th>תאריך</th>-->
					<th>טלפון</th>
					<th>יתרה</th>
				</tr>											
				<tr  ng-repeat="cust in filterc=(search_cc  | limitTo:5) " ng-click="choose_client(cust)" style="cursor: pointer">
					<td>{{cust.SupplierName}}</td>
					<td>{{cust.ClientNum}}</td>
					<!--<td>25.06.2014</td>-->
					<td>{{cust.CellPhone}}</td>
					<td><i class="fa fa-info-circle" ng-class="{{curr_color()}}" style="margin-right: 18px;  color: #e65844;  color: #e65844;"></i></span><label class="lbl2 border yitrat_hov" style="padding-right: 5px;">{{itra1(cust.Obligo,cust.CreditBalance)}}  </label></td>
				<tr>
			</table>
			<p class="" style="font-size: 200%;margin-top: 20px;margin-bottom: 10px;">הכנס סכום</p>
			<input type="text" class="text debt_sum" style="width: 92%;background: white;border: 1px solid #cfd2d9;height: 37px;"/>
			<div class="newrow2 find_cust_container display"><label class="lbl1 name">שם: {{SearchClient.SupplierName}}</label><span>|</span></span><label class="lbl2 border num">מספר: {{SearchClient.ClientNum}}</label>
  				<i class="fa fa-calendar" style="color: black;margin-right: 26px;"></i>
  				<label class="lbl1">ביקור אחרון: 25.06.2014</label><i class="fa fa-info-circle" style="margin-right: 18px;  color: #e65844;  color: #e65844;"></i></span><label class="lbl2 border yitrat_hov" style="padding-right: 5px;">יתרה:{{itra1(SearchClient.Obligo,SearchClient.CreditBalance)}}  </label>
  			</div>
			<input class="mybtn leftbottom btnblue " type="button" value="אישור" ng-click="start_debt()"/>
			<input class="mybtn btnlightgray rightbottom" onclick="$( '.wrap' ).click();" type="button" value="ביטול">
			
		</div>
	</div>	
	<div class="popup pop_peulot  pop_chooseworker" style="z-index: 9999;left:25%" >
			<div class="container_pop add_worker" ng-controller="WorkerController  as workers">
			<h2 class="relative" style="  font-size: 150%;margin: 20px;"> עובדים:</h2>
	
			
			<table class="workers_tb" style="width: 98%;">
				<tr>
					 <th>שם עובד</th>
					 <th>טלפון</th>
					 <th>נייד</th>
					 <th>אימייל</th>
					 <th>מס"ד</th>
					 <th></th>
				</tr>
				
				<tr class="worker_{{worker1.WorkerNum}}" ng-repeat="worker1 in workerslist" data-id="{{worker1.WorkerNum}}" ng-click="workers.choose_worker(this,worker1.WorkerNum)" >
					<td>{{worker1.SupplierName}}</td>
					<td>{{worker1.Phone}}</td>
					<td>{{worker1.CellPhone}}</td>
					<td>{{worker1.Email}}</td>
					<td>{{worker1.WorkerNum}}</td>
					<td><i  class='fa fa-check-circle display'></i></td>
				</tr>		
			</table>
						<input class="mybtn btngray rightbottom" type="button" value="ביטול"  onclick="$( '.wrap' ).click();" >
			<input class="mybtn btnblue leftbottom"  ng-click="workers.save_choose()" type="button" value="בחירה">
		</div>
	</div>
  <div class="header" style="z-index: 99999">
	<!--<div class="log" style=" position: absolute;background: red;z-index: 99999999;">
		<table>
			<th style="  width: 100px;">זמן</th>
			<th  style="  width: 100px;">שורה</th>
			<th  style="  width: 100px;">פונקציה</th>
		</table>
	</div>	-->
  	<i class="fa fa-bars"></i>
	<!--<div class="inline full_s" onclick="full_screen(this)" style="width:40%;text-align: center;color: white;"><i class="fa fa-tablet" onclick="full_screen(this)"></i><!--<button onclick="invoice();">JavaScript interface</button></div>-->
	<i class="fa fa-tablet" style="margin-right: 17px;" onclick="full_screen(this)"></i>
	
	<div class="inline relative adding_p" style="width:50px;margin-right: 10px;  right: 3px;" onclick="openwrap('.pop_chooseworker','.pop_chooseworker,.pop_chooseworker .add_worker')">
		<i class="left fa fa-user"></i>
		<i class="left fa fa-refresh"></i>
	</div>
	 
	<span style="color:white;  display: inline-block;" class="{{premission_list['permission_worker_cach']}} worker_top" ng-controller="WorkerController as wrkk">{{SearchWorker.SupplierName}}</span>		
	<!--<i class="fa fa-usd" style="  margin-right: 18px;" onclick="openwrap3('.popup.pop_beynaim','.popup.pop_beynaim,.pop_beynaim .container_pop');$('.closebb').hide()" ng-click="alltash()"></i>-->

	<i class="fa fa-usd" style="  margin-right: 18px;" onclick="openwrap3('.popup.pop_beynaim','.popup.pop_beynaim,.pop_beynaim .container_pop');$('.closebb').hide()" ></i>
	<!--<a href="javascript:wopen('http://a.yeda-t.com/modules/stock/cashbox3/top.php?t=clock&fromcash=1&stock=<?=$stock?>','add',900)">-->
	<i class=" fa fa-clock-o clock" onclick="barcode('30032')"  style="   margin-right: 14px;  position: absolute;z-index: 999!important;"></i><!--</a>-->
	<i class="fa fa-credit-card prepaid" style="  margin-right: 20px;"></i>	
	<i class="fa fa-money" style="  margin-right: 25px;" onclick="openwrap('.pop_debtpayments','.pop_debtpayments,.pop_debtpayments .debt')"></i>
	<i class="fa fa-refresh refresh2" onclick="location.reload();" style="  margin-right:25px;"></i>
	<i class="fa fa-key" style="  margin-right:25px;"></i>
	
	<span style="color: white;font-size: 160%;margin-right: 20px;">
	 {{datet | date:'dd-MM-yyyy HH:mm:ss'}} 
	</span>
	<!--<button onclick="localStorage.clear();location.reload();">איפוס</button>
	<button onclick="invoice()">הדפס</button>-->
	<div class="stat inline">
		<label id="stock_name" data-id="<?=$stock?>"><?=$stockname?></label>
	</div>
	<div class="stat inline btn">		
		<div class="openclose op <?if($stat!="open_cashbox")echo "display";?>">
			<i class="fa fa-check"></i>
			<span class=" stt open"  onclick="openwrap('.popup.pop_beynaim','.popup.pop_beynaim,.pop_beynaim .container_pop');$('.closebb').show()">פתוח</span>
		</div>	
		<div class="openclose cl <?if($stat=="open_cashbox")echo "display";?>" style="z-index: 99999;" >
			<i class="fa fa-times "></i>
			<span class="stt close" style="z-index: 99999;margin-left: 30px;" >סגור</span>
		</div>	
	</div>
	
	<i class=" fa fa-cog" onclick="openwrap3('.pop_sett','.pop_sett,.pop_sett .details')"></i>
	<div class="logo_border">
		<img src="images/logoex.png">		
	</div>
	
  </div>
  
  <div class="leftmenu display">
  	<div class="leftmenu_inner" ng-click="menuclick('top.php?t=clients&stock=<?=$stock?>')" >
  		<i class=" fa fa-group"></i>
  		<p>לקוחות</p>
  	</div>
  	<div class="leftmenu_inner" ng-click="menuclick('top.php?t=reports&stock=<?=$stock?>')" >
  		<i class="fa fa-file-text-o"></i>
  		<p>דוחות</p>
  	</div>
  	<div class="leftmenu_inner"  ng-click="menuclick('top.php?t=documents&stock=<?=$stock?>')" >
  		<i class=" fa fa-file-o"></i>
  		<p>מסמכים</p>
  	</div>
  	<div class="leftmenu_inner"  ng-click="menuclick('top.php?t=mail&stock=<?=$stock?>')" >
  		<i class="fa fa-envelope-o"></i>
  		<p>מערכת דיוור</p>
  	</div>
  	<div class="leftmenu_inner"  ng-click="menuclick('top.php?t=prepaid&stock=<?=$stock?>')" >
  		<i class=" fa fa-money"></i>
  		<p>כרטיס מתנה</p>
  	</div>
  	<div class="leftmenu_inner"  ng-click="menuclick('top.php?t=check_check&stock=<?=$stock?>')" >
  		<i class="fa fa-check-square-o"></i>
  		<p>בדיקת המחאות</p>
  	</div>
  	<div class="leftmenu_inner"  ng-click="menuclick('top.php?t=check_credit&stock=<?=$stock?>')">
  		<i class="fa fa-check-square-o"></i>
  		<p>בדיקת אשראי</p>
  	</div>
  </div>

  <div class="mainarea" >
  	<div ng-controller="CashProdController  as cash_prod">
  		
  	<div class="right rightside" ng-controller="ProductController as prod" id="product_main" ng-enter="search_barcode()">
  		
  		<!-- form for barcode. not visible -->
<?
	if(isset($_REQUEST[online])&&$_REQUEST[online]=='off'){
?> 		
		<form>
  			<input type="text" class="search_input1" id="search_prod" onfocus="$(this).blur()"  
  				style="  background: transparent; border: transparent;  outline: none;color:transparent;position: absolute" />
  		</form>
<?
	}
	else{
?>
  		<form>
  			<input type="text" class="search_input1" id="search_prod"  onfocus="$(this).blur()" 
  				style="  background: transparent; border: transparent;  outline: none;color:transparent;position: absolute" />
  		</form>  		 		
  		

 <?
	}
 ?> 		
  <form class="search_form relative">
 <?
	if(isset($_REQUEST[online])&&$_REQUEST[online]=='off'){
?> 		
		<input type="text" class="search_input" id="search_prod2"     placeholder="חיפוש מוצר" />
<?
	}
	else{
?>
  		<input type="text" class="search_input" id="search_prod2"  placeholder="חיפוש מוצר"  />

 <?
	}
 ?> 
			
			<input type="button" ng-click="search_p();call_setTab(4)" class="search_p_global" value="חפש"/>
			<i class="fa fa-search-plus search_p_plus" ng-click="call_setTab(7)"></i>
			<i class="fa fa-search" ></i>
			<!--<a href="javascript:wopen('http://a.yeda-t.com/modules/stock/add_listing.php?&simple=1&fromcash=1','add')">	-->
				
  			<div class="relative submit_wrap"> 				
  				<input type="button"  value="" onclick="openwrap3('.pop_add_prod','.pop_add_prod ,.pop_add_prod .container_pop')"/>
  				<i class="fa fa-tag"></i>				
				<i class="fa fa-plus"></i>
  			</div>
  		</a>
  		</form>
  		<div >
  		<div class="rightcenter prod_area relative" ng-show="call_isSet(1)" > 			
  			<div class="prod_div">
  			<div class="prod_title">
				<p id="cat_name" style="margin-right: 15%;">מחלקות</p>				
				<span id="num_product" style="float: left;margin-top: 11px;margin-left: 8px;color: white;"></span>
			</div>			 		 
  			<div class="prod_container">
  				<div id="resizable" class="prod_row ui-widget-content" style="text-align: center;"> 
  					<i class="fa fa-spinner fa-spin spinner1" style="font-size: 100px;  color: rgb(85,193,231);margin-top: 7%;"></i> 						
  					<div class="prod"  ng-repeat="cat in prod.catJson" ng-click="prod.getCategory2(cat.ID,cat.Title)">
  					<div  class=" first main_cat categ" id="button_cats_{{cat.ID}}" >  						
  						<img ng-src="{{prod.image('<?=$target_image_dir?>',cat.picture)}}"></img>
						<p >{{cat.CategoryName|cut:true:15}}</p>
  					</div>
					</div>
    				</div>
  			</div>
  			</div>
  			<!--<img src="images/arrow_o.png" class="arrow_o"/>-->
  			
  		</div>
  		<div class="rightcenter prod_area relative" ng-show="call_isSet(3)">
  			<div class="prod_div">
  			<div class="prod_title">
  				<p ng-click="call_setTab(1);reset()" class="prod_btntop">למחלקות</p>				
				<p  class="prod_btntop" ng-click="prev()" ng-show="isprev()">הקודם</p>
				<p  class="prod_btntop" ng-click="next()" ng-show="isnext()">הבא</p>
				<span id="num_product" style="float: left;margin-top: 11px;margin-left: 8px;color: white;">מס' הפריטים: <span class="numpritim1">{{filtered.length}}</span></span>
			</div>			 		 
  			<div class="prod_container">
  				<div id="resizable" class="prod_row ui-widget-content" style="text-align: center;"> 
  						<i class="fa fa-spinner fa-spin spinner2" style="font-size: 100px;  color: rgb(85,193,231);margin-top: 7%;"></i> 	
  					<div class="prod" id="button_cats_{{cat2.ID}}" ng-click="cash_prod.add_cart(cat2)"  ng-repeat="cat2 in filtered=(prod.products | filter: { ProductGroup: prod.currgroup }  | slice:start:end)" >

  					<div  class=" first main_cat" >  						
  						
						<p style="margin-top: -4px;">{{cat2.Title|cut:true:45}}</p>
  					</div> 					 
					</div>
    				</div>
  			</div>
  			</div>
  			<!--<img src="images/arrow_o.png" class="arrow_o"/>-->
  			
  		</div>  
  		<div class="rightcenter prod_area relative " ng-show="call_isSet(7);">
  			<div class="prod_div">
  			<div class="prod_title">
  				<p ng-click="call_setTab(1);reset()" class="prod_btntop">למחלקות</p>
				<p  class="prod_btntop" ng-click="prev()" ng-show="isprev()">חיפוש מוצרים</p>
							</div>			 		 
  			<div class="prod_container">
  				<div id="resizable" class="prod_row ui-widget-content" > 
  					<div>
  						<input type="text" placeholder="מחיר" class="advanced_input" id="advanced_s_SalePrice"/>
  						<input type="text" placeholder="מחלקה" class="advanced_input" id="advanced_s_ProductGroup"/>
  						<input type="text" placeholder="קבוצה" class="advanced_input" id="advanced_s_ProductGroup"/>
  						<input type="text" placeholder="דגם" class="advanced_input" id="advanced_s_ProductGroup"/>
  						<input type="text" placeholder="ספק" class="advanced_input" id="advanced_s_ProductGroup"/>
  						<input type="text" placeholder="מספר זר" class="advanced_input" id="advanced_s_ProductGroup"/>
  						<input type="text" placeholder="מספר סידורי" class="advanced_input" id="advanced_s_ProductGroup"/>
  						<!--<select>
<?
						buildSelect("listingsDB","ProductGroup","ProductGroup","ProductGroup","", -1,"","","",1);
?>					
  						</select>-->
  					</div>
  					<div style="width:20%;float:left">					
  						<input type="button " ng-click="advanced_search_p();call_setTab(4)" class="search_p_global advanced_btn" value="חפש" id="advanced_ProductGroup">	
  					</div>
    				</div>
  			</div>
  			</div>
  			<!--<img src="images/arrow_o.png" class="arrow_o"/>-->
  			
  		</div>	
  		<div class="rightcenter prod_area relative tab8" ng-show="call_isSet(8);" ng-controller="Client">
  			<div class="prod_div">
  			<div class="prod_title">
				<p  class="prod_btntop" ng-click="prev()" ng-show="isprev()">חיפוש לקוחות</p>	
			</div>			 		 
  			<div class="prod_container">
  				<div id="resizable" class="prod_row ui-widget-content" > 
  					<div>
  						<input type="text" placeholder="ת.ז (ח.פ)" class="advanced_input" id="advanced_c_SalePrice"/>
  						<input type="text" placeholder="שם" class="advanced_input" id="advanced_c_SupplierName"/>
  						<input type="text" placeholder="קבוצה" class="advanced_input" id="advanced_c_ProductGroup"/>
  						<input type="text" placeholder="טלפון" class="advanced_input" id="advanced_c_CellPhone"/>
  						<input type="text" placeholder="כתובת" class="advanced_input" id="advanced_c_Address"/>
  						<input type="text" placeholder="מספר לקוח" class="advanced_input" id="advanced_c_ClientNum"/>

  					</div>
  					<div style="width:20%;float:left">					
  						<input type="button " ng-click="advanced_search_cust();call_setTab(9)" class="search_p_global advanced_btn" value="חפש" id="advanced_search_cust">	
  					</div>
    				</div>
  			</div>
  			</div>
  			<!--<img src="images/arrow_o.png" class="arrow_o"/>-->
  			
  		</div>	
  		  		<div class="rightcenter prod_area relative " ng-show="call_isSet(9);" ng-controller="Client">
		  			<div class="prod_div">
		  			<div class="prod_title">
						<p  class="prod_btntop" ng-click="prev()" ng-show="isprev()">הקודם</p>	
						<p  class="prod_btntop" ng-click="next()" ng-show="isnext()">הבא</p>
						<span id="num_product" style="float: left;margin-top: 11px;margin-left: 8px;color: white;">מס' הפריטים: <span class="numpritim2">{{filterc.length}}</span></span>
						
					</div>			 		 
		  			<div class="prod_container">
		  				<div id="resizable" class="prod_row ui-widget-content"> 
								<table class="workers_tb akafa_client_tb" style="  margin-top: 20px;" >	
										<tr>
											<th>שם</th>
											<th>מספר</th>
											<!--<th>תאריך</th>-->
											<th>טלפון</th>
											<th>יתרה</th>
										</tr>											
										<tr  ng-repeat="cust in filterc=(search_cc  | limitTo:5) " ng-click="choose_client(cust)" style="cursor: pointer">
											<td>{{cust.SupplierName}}</td>
											<td>{{cust.ClientNum}}</td>
											<!--<td>25.06.2014</td>-->
											<td>{{cust.CellPhone}}</td>
											<td><i class="fa fa-info-circle" style="margin-right: 18px;  color: #e65844;  color: #e65844;"></i></span><label class="lbl2 border yitrat_hov" style="padding-right: 5px;">{{itra1(cust.Obligo,cust.CreditBalance)}} </label></td>
										<tr>
								</table>							 
							</div>
		
		  			</div>
		  			</div>
		  			<!--<img src="images/arrow_o.png" class="arrow_o"/>-->
		  			
  		</div>		
<?
	if(isset($_REQUEST[online])&&$_REQUEST[online]=='off'){

?>
  		<div class="rightcenter prod_area relative " ng-show="call_isSet(4);">
  			<div class="prod_div">
  			<div class="prod_title">
  				<p ng-click="call_setTab(1);reset()" class="prod_btntop">למחלקות</p>	
				
				<p  class="prod_btntop" ng-click="prev()" ng-show="isprev()">הקודם</p>	
				<p  class="prod_btntop" ng-click="next()" ng-show="isnext()">הבא</p>
				<span id="num_product" style="float: left;margin-top: 11px;margin-left: 8px;color: white;">מס' הפריטים: <span class="numpritim2">{{filterprod.length}}</span></span>
				
			</div>			 		 
  			<div class="prod_container">
  				<div id="resizable" class="prod_row ui-widget-content"> 
  					
  					<div class="prod" id="button_cats_{{cat2.ID}}" ng-click="cash_prod.add_cart(cat2)" ng-repeat="cat2 in filterprod=(search_prd_off  | slice:start:end) " >

  					<div  class=" first main_cat" >  						
  					
								<p style="margin-top: -4px;">{{cat2.Title|cut:true:45}}</p>
  					</div> 					 
					</div>
    				</div>
  			</div>
  			</div>
  			<!--<img src="images/arrow_o.png" class="arrow_o"/>-->
  			
  		</div>

<?
	}
	else{
?>
		<div class="rightcenter prod_area relative " ng-show="call_isSet(4);">
  			<div class="prod_div">
  			<div class="prod_title">
  				<p ng-click="call_setTab(1);reset()" class="prod_btntop">למחלקות</p>	
				
				<p  class="prod_btntop" ng-click="prev()" ng-show="isprev()">הקודם</p>	
				<p  class="prod_btntop" ng-click="next()" ng-show="isnext()">הבא</p>
				<span id="num_product" style="float: left;margin-top: 11px;margin-left: 8px;color: white;">מס' הפריטים: <span class="numpritim2">{{search_prd.length}}</span></span>
				
			</div>			 		 
  			<div class="prod_container">
  				<div id="resizable" class="prod_row ui-widget-content"> 
  					
  					<div class="prod" id="button_cats_{{cat2.ID}}" ng-click="cash_prod.add_cart(cat2)"  ng-repeat="cat2 in filtered=(search_prd |  slice:start:end)" >

  					<div  class=" first main_cat" >  						
  						
							<p style="margin-top: -4px;">{{cat2.Title|cut:true:45}}</p>
  					</div> 					 
					</div>
    				</div>
  			</div>
  			</div>
  			<!--<img src="images/arrow_o.png" class="arrow_o"/>-->
  			<style>
  				 .first.main_cat p{
  				 	left:20%!important;
  				 	margin-right: 0;
  				 	top:20%!important;
  				 	width: 70%;
  				 }
  				 .categ.first.main_cat p{
  				 	left:5%!important;
  				 	margin-right:50%;
  				 	top:40%!important;
  				 	width:auto;
  				 	width: 100%;
  				 }
  				 ::-webkit-scrollbar { 
				    display: none; 
				}
  			</style>
  		</div>
  		<div class="rightcenter prod_area relative prod_search4" ng-show="call_isSet(44);">
  			<div class="prod_div">
  			<div class="prod_title">
  				<p ng-click="call_setTab(1);reset()" class="prod_btntop">למחלקות</p>	
				
				<p  class="prod_btntop" ng-click="prev()" ng-show="isprev()">הקודם</p>	
				<p  class="prod_btntop" ng-click="next()" ng-show="isnext()">הבא</p>
				<span id="num_product" style="float: left;margin-top: 11px;margin-left: 8px;color: white;">מס' הפריטים: <span class="numpritim2">{{filterprod2.length}}</span></span>
				
			</div>			 		 
  			<div class="prod_container">
  				<div id="resizable" class="prod_row ui-widget-content"> 
  					
  					<div class="prod" id="button_cats_{{cat2.ID}}" ng-click="cash_prod.add_cart(cat2)" ng-repeat="cat2 in filterprod2=(search_prd_barcode |slice:start:end) " >

  					<div  class=" first main_cat" >  						
  				
						<p style="margin-top: -4px">{{cat2.Title|cut:true:45}}</p>
  					</div> 					 
					</div>
    				</div>
  			</div>
  			</div>
  			<!--<img src="images/arrow_o.png" class="arrow_o"/>-->
  			
  		</div>
<?
	}
?>
  		
  		</div>
  		
		<div class="curr_btn" style="height: 40px;position: absolute;bottom: 19px;width: 39%;">
  				<input type="button" class="rightcenter_btn btngreen prod_btn" value="הוסף פריט כללי  / מזער פריסט"  ng-show="call_isSetMulti(1,3,4,44,7,8,9)"  ng-click="call_setTab(2)">
  				<input type="button" class="rightcenter_btn btnorange  calc_btn" value="מחלקות / מוצרים מהירים"  ng-show="call_isSetMultiCalc(2,5)"  ng-click="call_setTab(1)">
  			</div>
  		<div class="rightcenter calc_area"   ng-show="call_isSetMultiCalc(2,5)">
  			<div class="rightcenter calc_area" style="">
  			<div class="frm">
		<table style="width:100%" border="0" cellpadding="0" cellspacing="0">
		<tbody><tr>
		<td id="result_calc" class="input_result" style="height: 143.8px;">
		<input type="text" value="" name="Input" style="width:98%;padding-right:2%;color:#42494f;height:92%">
		<br>
		</td>
		</tr>
		<tr>
		<td>
		<table class="inner_calc" style="direction: ltr;" border="0" cellpadding="0" cellspacing="0" ng-controller="PaymentController  as payment">
			<tbody><tr>
				
				<td style="height: 143.8px;"><input type="button" name="one" value="  1  " onclick="$('input[name=Input]').val($('input[name=Input]').val()+'1')">  </td>
				<td style="height: 143.8px;"><input type="button" name="two" value="  2  " onclick="$('input[name=Input]').val($('input[name=Input]').val()+'2')">  </td>
				<td style="height: 143.8px;"><input type="button" name="three" value="  3  " onclick="$('input[name=Input]').val($('input[name=Input]').val()+'3')">  </td>
				<td style="height: 143.8px;"><input type="button" class="math" name="plus" value="  +  "onclick="$('input[name=Input]').val($('input[name=Input]').val()+'+')"></td>
				<td style="height: 143.8px;"><input type="button" class="math" name="clear" onclick="$('input[name=Input]').val('')" style="background:url('images/erase.png')no-repeat 51% 50% #e6e9ee!important"></td>				
			</tr>
			<tr>
			
		<td rowspan="1" style="height: 143.8px;"><input type="button" name="four" value="  4  " onclick="$('input[name=Input]').val($('input[name=Input]').val()+'4')"></td>
		<td rowspan="1" style="height: 143.8px;"><input type="button" name="five" value="  5  " onclick="$('input[name=Input]').val($('input[name=Input]').val()+'5')">  </td>
		<td rowspan="1" style="height: 143.8px;"><input type="button" name="six" value="  6  "onclick="$('input[name=Input]').val($('input[name=Input]').val()+'6')">  </td>
		<td rowspan="1" style="height: 143.8px;"><input type="button" class="math" name="minus" value="  -  " onclick="$('input[name=Input]').val($('input[name=Input]').val()+'-')"></td>
		<td rowspan="3" style="position: relative; height: 143.8px;"><div name="DoIt" class="result"  ng-click="cash_prod.add_new_cart();payment.calc3();"><i class="fa fa-check-circle"></i></div>
			<div name="DoIt" class="result2 display" onclick="Calc.Input.value = eval(Calc.Input.value);anacha_doit()"><i class="fa fa-check-circle"></i></div>
		</td>
			</tr>
			<tr>
		<td style="height: 143.8px;"><input type="button" name="seven" value="  7  " onclick="$('input[name=Input]').val($('input[name=Input]').val()+'7')"></td>
		<td style="height: 143.8px;"><input type="button" name="eight" value="  8  " onclick="$('input[name=Input]').val($('input[name=Input]').val()+'8')">  </td>
		<td style="height: 143.8px;"><input type="button" name="nine" value="  9  " onclick="$('input[name=Input]').val($('input[name=Input]').val()+'9')">  </td>
		<td style="height: 143.8px;"><input type="button" class="math" name="times" value="  x  " onclick="$('input[name=Input]').val($('input[name=Input]').val()+'*')"></td>
			</tr>
			<tr>
		<td style="height: 143.8px;"><input type="button" name="dot" value="  .  " onclick="$('input[name=Input]').val($('input[name=Input]').val()+'.')"></td>
		<td style="height: 143.8px;"><input type="button" name="zero" value="  0  " onclick="$('input[name=Input]').val($('input[name=Input]').val()+'0')">  </td>
		<td style="height: 143.8px;"><input type="button" name="zerozero" value="  00  " onclick="$('input[name=Input]').val($('input[name=Input]').val()+'00')"></td>
		<!-- <td><INPUT TYPE="button" NAME="DoIt"  VALUE="  =  " OnClick="Calc.Input.value = eval(Calc.Input.value)"></td> -->
		<td style="height: 143.8px;"><input type="button" class="math" name="div" value="  %  " onclick="$('input[name=Input]').val($('input[name=Input]').val()+'%')">
			
			<input type="hidden" name="flag_focused"/>
		</td>
			</tr>
		</tbody></table>
		</td>
		</tr>
		</tbody></table>
		</div>
  </div>
  		</div>
  	</div>

  	<div class="left leftside">
  		<div ng-controller='Client as clnt'>
		<div  class="relative search_form ">
			 <!--<div class="large-padded-row" style="  display: inline-block; width: 90%;">
			      <div angucomplete-alt id="cust_search" placeholder="חיפוש לקוחות" pause="100" selected-object="selectedClient" local-data="clients" search-fields="ClientNum,SupplierName,CellPhone,Address" title-field="ClientNum,SupplierName,CellPhone,Address"  minlength="1" input-class="form-control form-control-small  my_search_auto" match-class="highlight" clear-selected="true" >
			     </div> 
			     <i class="fa fa-search show"></i>
			</div>-->
			<input type="text" class="search_input my_search_auto cust_search_value c1"  placeholder="חיפוש לקוחות">
			<input type="button" ng-click="search_c();call_setTab(9)"  class="search_p_global" value="חפש">
			<i class="fa fa-search-plus search_p_plus " ng-click="call_setTab(8)"></i>
			<div class="relative submit_wrap" onclick="openwrap('','.pop_add_client,.pop_add_client .container_pop ');$('.client.mainarea2 input').val('')">
				<input type="button"  value="" class="search_cust_sbm" onclick=""/>
				<i class="fa fa-user"></i>
				<i class="fa fa-plus"></i>
			</div>
			
		</div>
  			<div class="newrow2 find_cust_container display"><label class="lbl1 name">שם: {{SearchClient.SupplierName}}</label><span>|</span></span><label class="lbl2 border num">מספר: {{SearchClient.ClientNum}}</label>
  				<span>|</span><i class="fa fa-calendar" style="color: black;padding-right: 12px;"></i>
  				<!--<label class="lbl1">25.06.2014</label>
  				<span>|</span>--><i class="fa fa-phone" style="color: black;padding-right: 12px;"></i>
  				<label class="lbl1">{{SearchClient.CellPhone}}</label>
  				<i class="fa fa-info-circle" style="margin-right: 18px;  color: #e65844;  color: #e65844;"></i></span><label class="lbl2 border yitrat_hov" style="padding-right: 5px;">יתרה: {{itra1(SearchClient.Obligo,SearchClient.CreditBalance)}}</label>
  			</div>
  		</div>
  		<div class="details_area" id="cat2" style="width: 100%;overflow-y: scroll;" >
  			<table style="width: 100%;font-size:150%;">
  				<tr class="details_title">
				    <th class="border_table"></th>
				 <!--   <th style="width:20%">בר קוד</th> -->
				    <th>שם פריט</th>
				    <th>מחיר</th>
				    <th  style="">כמות</th>
				    <th>הנחה</th>
				    <th class="border_table">סה"כ</th>
				    <th class="border_table"></th>
				  </tr>
				  <tr class="active tr_{{cash_prd.BarCode}}" ng-repeat="cash_prd in cash_prod.products">		    
				  	<td class="border_table text_center" ng-controller="CommentController as commentC" ng-click="start_comment_prod(cash_prd.BarCode);call_setTab(5)" onclick="openwrap('.pop_comment_prod','.comment_div_prod,.pop_comment_prod');"><i class="fa fa-info-circle {{cash_prd.commentClass}}"></i></td>		    
				  	<td><p style="height:40px;overflow: hidden;">{{::cash_prd.Title|cut:true:15}}</p></td>		    
				  	<td class="prod_SalePrice">{{::cash_prd.SalePrice}}</td>		    
				  	<td class="text_center"><i class="fa fa-plus-circle" ng-click="cash_prod.plus_count(cash_prd.SalePrice,cash_prd.BarCode,cash_prd.refund)"></i><p style="display: inline-block;width:60px;text-align:center;">{{cash_prd.Amount}}</p><i class="fa fa-minus-circle" ng-click="cash_prod.minus_count(cash_prd.SalePrice,cash_prd.BarCode,cash_prd.refund);"></i></td>
				  	<td  ng-click="cash_prod.start_anacha_prod(cash_prd,(cash_prd.SalePrice-cash_prd.Discount)*cash_prd.Amount)"><i class="fa fa-tag {{::isdiscount(cash_prd.Discount)}}" style="margin-left: 10px;" ></i>{{cash_prd.Discount}}</td>		    
				  	<td class="border_table padding_5 sum_p_l">{{::(cash_prd.SalePrice-cash_prd.Discount)*cash_prd.Amount|fix2}}</td>		    
				  	<td class="border_table text_center" ng-click="cash_prod.remove_cart(cash_prd.BarCode,cash_prd.SalePrice);"><i class="fa fa-times" ></i></td> 		  
				  </tr>
				  
				  
  			</table>
  			
  		</div>
  		
  		<img src="images/zigzag.png" class="bordr_wave"/>
  		
		<div class="abso">
  		<div class="sum_area">
  			<div class="right inline">
  				<div class="newrow1" ng-controller="CommentController as commentC" ng-click="start_comment()" onclick="openwrap('.pop_comment','.comment_div,.pop_comment');"><label class="block" style="  font-size: 120%;"><i class="fa fa-pencil {{commentClass}}" style="width:30px"></i>הוסף הערה</label></div>
  				<div class="newrow2" style="  font-size: 125%;"><label class="lbl1" style="  margin-right: 30px;">סה"כ פריטים: <span id="total_prod">{{countprod}}</span></label></div>
  				<!--<div class="newrow2"><label class="lbl1">שם: יהב כהן</label><span>|</span></span><label class="lbl2 border">מספר: 159</label>
				<label class="lbl3">ביקור אחרון: 25.6.2014</label>
				</div>
  				<div class="newrow3"><label>יתרת חוב: </label><label >  ₪</label><label id=before_tax style="font-size: 150%;"></label></div>-->
  			</div>
  			<div class="left inline">
  				<div class="newrow2"> 				
  					<div style="width: 100%;height: 26px;font-size: 130%;"><label class="right">סכום ביניים:</label><label class="left in_sum zerofloat">{{amount_out}}</label><label class="left">₪</label></div>
  					<div style="width: 100%;height: 26px;font-size: 130%;"><label class="right">18% מע"מ:</label><label class="left tax_sum zerofloat">{{amount_maam}}</label><label class="left">₪</label></div>
  					<div class="before_calc" style="width: 100%;height: 26px;font-size: 130%;display: none"><label class="right">לפני הנחה:</label><label class="left in_sum zerofloat">{{original_afterprod}}</label><label class="left">₪</label></div>  						
  				  				
  				<div class="newrow3"><label class="finall_price zerofloat">{{amount}}</label><span class="curr">₪</span></div>
  				
  			</div>
  			
  		</div>
  		<div style="clear: both;"></div>
  		<div class="peulot" style="font-size: 120%;" >
  			<button  value=""  ng-click="cashc.call_clean()" class="trash"><i class="fa fa-trash-o"></i></button>
  			<input type="button"  value="הנחה" class="anacha" ng-controller="AnachaController as anachaC" ng-click="start_anacha()" />
  			<input type="button" class="pause"  value="השהייה"/>
  			<input type="button" class="hachlafa"  value="פ. החלפה" ng-controller="AchlafaController"  ng-click="start_achlafa()"/>
  			<input type="button" ng-click="cashc.zicuy()" class="zicuy"  value="זיכוי" />
  			<input type="button"  value="לתשלום" class="pay" ng-controller="PaymentController  as payment"   ng-click="start_pay()"/>

  		</div>
		</div>
  	</div>
  </div>
  </div>
  </div>
  <div class="clear"></div>
  <alert></alert>
<style type="text/css" media="screen">
	.ui-widget-content.ng-scope{
		display: inline-block;
	} 
</style>
</span>
<div style="display: none">
<div id="receipt2"><div id="receipt" class="current" style="width: 80mm; font-size:20px;" align="center" ng-controller="CashProdController  as cash_prod">
			<table  style="display:table;width:80mm;">
			<tr><th colspan="2">ידע טופ</th></tr>
			<tr><th colspan="2">רבי עקיבא 54</th></tr>
			<tr><th colspan="2">בני ברק</th></tr>
			<tr><th colspan="2"><hr></th></tr>
			<tr><th colspan="2">חשבונית קבלה</th></tr>
			<tr><th colspan="2"><hr></th></tr>
			<tr><th><?=$stockname?></th><th>{{cash_kupanum}}-{{cash_num}}</th></tr>
		
			<tr><th colspan="2"><hr></th></tr>
			
	<tr><th width="50%">מחיר</th><th width="50%">תיאור מוצר</th></tr>
			
			  <tbody class="active tr_{{cash_prd.BarCode}}" ng-repeat="cash_prd in cash_prod.products" style="text-align: right;">	
			  	<tr><td colspan="2" style="font-weight: bold">{{cash_prd.BarCode}}-{{cash_prd.Title|cut:true:15}}</td></tr>	    
				  			 
				  	
				  	<tr><td style="text-align: left;" class="border_table padding_5 sum_p_l">{{(cash_prd.SalePrice-cash_prd.Discount)*cash_prd.Amount|fix2}}</td>	<td class="prod_SalePrice">{{(cash_prd.SalePrice)}} X {{cash_prd.Amount|fix2}}</tr>   
				      <tr  ng-if="{{isdiscount2(cash_prd.Discount)}}"><th colspan="2">הנחת שורה: {{cash_prd.Discount}}</th></tr>
				  				  		   
				  		    
				  	 		  
				  </tbody>
				  <tr><th colspan="2"><hr></th></tr>
				 <tr><th>סה"כ</th><th>{{amount}}</th></tr>	
				   <tr><th colspan="2"><hr></th></tr>
			
				 	<div ng-controller="PaymentController  as payment">
				 <tbody class="workers_tb" style="text-align: left;float: left;">					
					<tr  ng-repeat="pay in payments_type.cash">						
						<td>{{pay.amount}}</td>
						<td>{{pay.type}}:</td>						
					</tr>						
					<tr  ng-repeat="pay in payments_type.cheque" >						
						<td>{{pay.amount}}</td>
						<td>{{pay.type}}:</td>						
					</tr>	
					<tr  ng-repeat="pay in payments_type.credit">						
						<td>{{pay.amount}}</td>
						<td>{{pay.type}}:</td>						
					</tr>
					<tr  ng-repeat="pay in payments_type.akafa">						
						<td>{{pay.amount}}</td>
						<td>{{pay.type}}:</td>						
					</tr>
					<tr  ng-repeat="pay in payments_type.shovar">						
						<td>{{pay.amount}}</td>
						<td>{{pay.type}}:</td>						
					</tr>
					<tr  ng-repeat="pay in payments_type.shovarzicuy">						
						<td>{{pay.amount}}</td>
						<td>{{pay.type}}:</td>						
					</tr>
					<tr  ng-repeat="pay in payments_type.prepaid">						
						<td>{{pay.amount}}</td>
						<td>{{pay.type}}:</td>						
					</tr>
					</tbody>
				</table>
				</div><img src="http://a.yeda-t.com/modules/stock/cashbox_fe/inc/barcode.php?barcode={{cash_kupanum|fill_num}}"/>	</div>
			
	</div>
	
<div style="display: none">	
	<div id="report_x">
		<table style="display:table;style:80mm" dir="rtl">
	<tr><th colspan="2">ידע טופ</th></tr>
	<tr><th colspan="2">רבי עקיבא 54</th></tr>
	<tr><th colspan="2">בני ברק</th></tr>
	<tr><th colspan="2">דוח X    {{datet | date:'yyyy-MM-dd HH:mm:ss'}}  {{kupa_num}}</th></tr>
	<tr><td colspan="2"><hr></td></tr>
<tr><td style="min-width:180px;">מספר עסקאות:</td><td>{{}}</td></tr>	
<tr><td> פריטים שנמכרו:</td><td>{{numprod_hova-numprod_zchut}}</td></tr>
<tr><td> פריטים שחויבו:</td><td>{{numprod_hova}}</td></tr>	
<tr><td> פריטים שזוכו:</td><td>{{numprod_zchut}}</td></tr>	
<tr><td>דמי מחזור:</td><td>{{start_cash}}</td></tr>	
<tr><td>סה"כ הוספת מזומן לקופה:</td><td>{{mezumanin}}</td></tr>	
<tr><td>סה"כ הוצאת מזומן מהקופה:</td><td>{{mezumanout}}</td></tr>
	<tr><td colspan="2"><hr></td></tr>
<tr><td>מזומן:</td><td>{{finalltash['cash']}}</td></tr>	
<tr><td>חיובים:</td><td>{{finalltash['cash1']['hova']}}</td></tr>	
<tr><td>זיכויים:</td><td>{{finalltash['cash1']['zicuy']}}</td></tr>
<tr><td colspan="2"><hr></td></tr>

<tr><td>המחאה:</td><td>{{finalltash['cheque']}}</td></tr>	
<tr><td>חיובים:</td><td>{{finalltash['cheque1']['hova']}}</td></tr>	
<tr><td>זיכויים:</td><td>{{finalltash['cheque1']['zicuy']}}</td></tr>
<tr><td colspan="2"><hr></td></tr>

<tr><td>אשראי:</td><td>{{finalltash['credit']}}</td></tr>	
<tr><td>חיובים:</td><td>{{finalltash['credit1']['hova']}}</td></tr>	
<tr><td>זיכויים:</td><td>{{finalltash['credit1']['zicuy']}}</td></tr>
<tr><td colspan="2"><hr></td></tr>

<tr><td>הקפה:</td><td>{{finalltash['akafadebt']['general']}}</td></tr>	
<tr><td>מזומן:</td><td>{{finalltash['akafadebt']['cash']}}</td></tr>	
<tr><td>אשראי:</td><td>{{finalltash['akafadebt']['credit']}}</td></tr>
<tr><td>המחאה:</td><td>{{finalltash['akafadebt']['cheque']}}</td></tr>	
<tr><td>כרטיס מתנה:</td><td>{{finalltash['akafadebt']['prepaid']}}</td></tr>
<tr><td colspan="2"><hr></td></tr>

<tr><td>פריפייד:</td><td>{{finalltash['prepaid']}}</td></tr>	
<tr><td>מזומן:</td><td>{{}}</td></tr>	
<tr><td>אשראי:</td><td>{{}}</td></tr>
<tr><td>המחאה:</td><td>{{}}</td></tr>	
<tr><td>כרטיס מתנה:</td><td>{{}}</td></tr>
<tr><td colspan="2"><hr></td></tr>

<tr><td>סה"כ תקבולים:</td><td>{{}}</td></tr>	


		
</table>
	</div>
	<div id="zicuy1">
		<table width="270px" style="display:table" dir="rtl">
	<tr><th colspan="2">ידע טופ</th></tr>
	<tr><th colspan="2">רבי עקיבא 54</th></tr>
	<tr><th colspan="2">בני ברק</th></tr>
		<tr><td colspan="2"><hr></td></tr>
	<tr><th colspan="2">תעודת זיכוי מקור</th></tr>
		<tr><td colspan="2"><hr></td></tr>
	<tr><th>סה"כ</th><th>{{amount}}</th></tr>	
	<tr><th>חתימה</th><th>____________</th></tr>
	<tr><th></th><th></th></tr>
	</table>
	<img src="http://a.yeda-t.com/modules/stock/cashbox_fe/inc/barcode.php?barcode={{cash_kupanum|fill_num}}"/>
	</div>
	<div id="zicuy2">
		<table width="270px" style="display:table" dir="rtl">
	<tr><th colspan="2">ידע טופ</th></tr>
	<tr><th colspan="2">רבי עקיבא 54</th></tr>
	<tr><th colspan="2">בני ברק</th></tr>
		<tr><td colspan="2"><hr></td></tr>
	<tr><th colspan="2">תעודת זיכוי העתק</th></tr>
		<tr><td colspan="2"><hr></td></tr>
	<tr><th>סה"כ</th><th>{{amount}}</th></tr>
	<tr><th>חתימה</th><th>____________</th></tr>
	<tr><th></th><th></th></tr> 
	</table>
	<img src="http://a.yeda-t.com/modules/stock/cashbox_fe/inc/barcode.php?barcode={{cash_kupanum|fill_num}}"/>
	</div>
	<div id="achlafa" class="container_pop an" ng-controller="AchlafaController as achlafaC">
			<table id="achlafa_pritim" dir="rtl" style="width: 80mm;" ng-show="showprod">
				<tr><th colspan="3">ידע טופ</th></tr>
				<tr><th colspan="3">רבי עקיבא 54</th></tr>
				<tr><th colspan="3">בני ברק</th></tr>
				<tr><th colspan="3"><hr></th></tr>
				<tr><th colspan="3">פתק החלפה</th></tr>
				<tr><th colspan="3"><hr></th></tr>
	  			<tr class="details_title">
	  				<th>שם פריט</th>
	  				<th>ברקוד</th><th style="">כמות</th>
	  				
	  				<th class="border_table" style="width: 50px;"></th>
	  			</tr>
	  			<tr ng-repeat="cash_prd in prod_for_achlafa" data-id="{{cash_prd.BarCode}}">
	  				<td>{{cash_prd.Title}}</td>
	  				<td>{{cash_prd.BarCode}}</td>	  				
	  				<td>{{cash_prd.Amount}}</td>	  				
	  			</tr>	 
	
  			</table>
  			<img src="http://a.yeda-t.com/modules/stock/cashbox_fe/inc/barcode.php?barcode={{curr_achlafa}}"/>
  			
  	</div>
  </div>
    </div>
  	<keyboard></keyboard>
  	<script>  	
  		var curr_time = new Date($.now());	
	
  	</script>
  </body>
</html>