<?
$cashbox=true;
//print_r($_COOKIE);
//require("../include/common.php");
$simple=1;

require("inc/conn.inc");

$rootdir 	=$GOCONFIG->file_storage_path;
$stock=$_REQUEST[stock];
$rootdir.="$username/POS/$stock";
$target_image_dir="../../../officefiles/".$username."/_MlaitekPro";
//mysql_selectdb('vcx_weberp');
function sql2json($query) {
    $data_sql = mysql_query($query) or die("'';//" . $query.mysql_error());// If an error has occurred, 
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
		$value=str_replace('\\', "", $value);
				$value=str_replace('"', "", $value);
                $value = trim($value);
                //If it is an associative array we want it in the format of "key":"value"
                if($key == "items") $json_str .= "\"$key\":$value";
                elseif(count($data) > 1) $json_str .= "\"$key\":\"$value\"";
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
	else{
		$json_str .= "[]";
	}

    //Replace the '\n's - make it faster - but at the price of bad redability.
    $json_str = str_replace("\n","",$json_str); //Comment this out when you are debugging the script
//$json_str=str_replace("'","",$json_str);
    //Finally, output the data
    return $json_str;
}
$stock=$_REQUEST['stock'];
$stat2=$_REQUEST['stat2'];
if(1==1||isset($_REQUEST[stock])){
$sql="select StockName,ID from vcx_weberp.listingsStocks where TerminalID='$stock' and user_ID='$userID'";
//$dbname 	= $GOCONFIG->stock_db_name;
//echo $sql;
			$rs = mysql_query($sql);
			echo mysql_error();
			while ($row=mysql_fetch_array($rs)) {
				$stockname=$row[StockName];
				$stockid=$row[ID];			
				
			}
			$dbname="vcx_$username";
			
			$sql="select action,MAX(action_date) from vcx_$username.cashbox_actions where terminal_id=$stock";
			//echo $sql;
			$rs = mysql_query($sql);
			echo mysql_error();		
			if(mysql_num_rows($rs)<1){
				$stat="close";
			}	
			else{
				$row=mysql_fetch_array($rs);
				$stat=$row[action];				
			}
			
 function writeUTF8($filename,$data){
		$f=fopen($filename,"a"); 
        # Now UTF-8 - Add byte order mark 
        fwrite($f, pack("CCC",0xef,0xbb,0xbf)); 
        fwrite($f,$data); 
        fclose($f);
	}
if($stat2=='cat'){	
	/*lc 23/03/2016 change sql by stock_id (no by user_id only)*/
    $add_where="";
    $sql="select ID from vcx_weberp.listingsStocks where user_ID = $userID AND TerminalID = $stock";
	$result=mysql_query($sql);
	 while($row = mysql_fetch_array($result)) {
          $stock_id1= $row['ID'];
	 } 
	if($stock_id1){
		$add_where=" and ( stock_id=$stock_id1 or stock_id=0)";
	}
	$sql = "SELECT * FROM vcx_$username.listingsCategories where ID>0 and eStatus=1 $add_where";
    $category=sql2json($sql);
	echo "jsoncat:$category:jsoncat";	
}

if($stat2 == 'discount'){
    $sql = "select d.id,d.name,d.type,d.op1,d.op2, concat('[',group_concat(di.item_id),']') as items 
    from discount d, discount_item di where d.id = di.discount_id group by d.id,d.name,d.type,d.op1,d.op2";
    $discount=sql2json($sql);
    echo "jsondiscount:$discount:jsondiscount";   
}

if($stat2=='prod'){
	mysql_query("SET SQL_BIG_SELECTS=1;");
 /*
  * 
  * SELECT x . * , Title, BarCode, ProductGroup, ld.picture, ProductGroup AS department, t.sum_saled, 
CASE WHEN lse.SalePrice IS NULL 
THEN ld.SalePrice
ELSE lse.SalePrice
END AS SalePrice, ld.ID AS ID, Unit
FROM listingsDB AS ld
LEFT OUTER JOIN listingsStocksElements AS lse ON lse.ListingID = ld.ID
AND lse.StockID =788
LEFT OUTER JOIN (

SELECT listing_id, SUM( StockAmount ) AS sum_saled
FROM transactioncomponents
GROUP BY listing_id
) AS t ON ld.ID = t.listing_id
LEFT OUTER JOIN (

SELECT d . * , di.item_id
FROM discount d, discount_item di
WHERE d.id = di.discount_id
)x ON x.item_id = ld.ID
WHERE ld.active =  'yes'
GROUP BY ld.ID
ORDER BY t.sum_saled DESC 
LIMIT 0 , 30 */
 $sql="select ID from vcx_weberp.listingsStocks where user_ID = $userID AND TerminalID = $stock";
	$result=mysql_query($sql);
	 while($row = mysql_fetch_array($result)) {
          $stock_id1= $row['ID'];
	 } 
	if($stock_id1){
		$add_where=" and ( stock_id=$stock_id1 or stock_id=0)";
	}
	$sql_cat = "SELECT ID FROM vcx_$username.listingsCategories where ID>0 $add_where";
	$sql = "
	select ld.quick_item,Title,BarCode,ProductGroup,ld.picture,ProductGroup as department,t.sum_saled,CASE
           WHEN lse.SalePrice is NULL THEN ld.SalePrice          
           ELSE lse.SalePrice
        END as SalePrice,ld.ID as ID, Unit, 
        (select group_concat(distinct d.name SEPARATOR  ' , ') from discount_item di join discount d on di.discount_id = d.id 
        where di.item_id = ld.ID and curdate() between d.begin_date and d.end_date) as discount_desc
         from  vcx_$username.listingsDB as ld 
          LEFT outer JOIN vcx_$username.listingsStocksElements as lse on lse.ListingID=ld.ID and lse.StockID=$stockid 
          LEFT outer JOIN   
(SELECT listing_id ,sum(StockAmount) as sum_saled FROM vcx_$username.transactioncomponents group by listing_id) as t on ld.ID=t.listing_id 
where ld.active='yes'
and ProductGroup in ($sql_cat)
 group by ID order by t.sum_saled desc";
			/*select Title,BarCode,ProductGroup,CASE
           WHEN lse.SalePrice is NULL THEN ld.SalePrice          
           ELSE lse.SalePrice
        END as SalePrice,ld.ID as ID from  vcx_$username.listingsDB as ld LEFT OUTER JOIN vcx_$username.listingsStocksElements as lse on lse.ListingID=ld.ID and lse.StockID=$stockid";   */
	//echo $sql."<hr>"; 
	//$sql="select * from  vcx_$username.listingsDB ";
	if($username=="marinada"){
file_put_contents("feigyprod.txt", $sql);
	}
	$product=sql2json($sql);
	$product = trim(preg_replace('/\s\s+/', ' ', $product));
	$product=str_replace(array("\r", "\n"), '', $product);
	//writeUTF8("d.log", "$product");
		//echo $sql."<hr>";
	//$sql="select * from  vcx_$username.listingsDB ";
	$product=sql2json($sql);
	$product = trim(preg_replace('/\s\s+/', ' ', $product));
	$product=str_replace(array("\r", "\n"), '', $product);
	
	
	$rootdir 	=$GOCONFIG->file_storage_path;
	$userdir   = "../../../officefiles/".$username;
	$termdir   = "../../../officefiles/".$username."/POS/".$stock;
	foreach(array('user' => $userdir,'POS'=>$userdir."/POS",  'terminal' => $termdir,'terminal backup' => $termdir."/backup") as $key => $val){
		if (!file_exists($val)){
			$oldmask = umask(0);
			@mkdir($val,0777);
			umask($oldmask);
			//@mkdir($val,0777);	
			chmod($val,0777);
		} 
	}
	file_put_contents($termdir."/pludta", $product);
	//echo $termdir."/pludta";
	//echo "<hr>";
	if (file_exists($termdir."/prod".date("dmy").".js")){
		unlink($termdir."/prod".date("dmy").".js");
	}
	file_put_contents($termdir."/prod".date("dmy").".js", "var pjson=$product;");
	//echo $termdir."/prod.js";
	//	echo "<hr>";
	//echo "$product"; 
	echo $termdir."/prod".date("dmy").".js";
	/*$array=json_decode($product, true);
	if (is_array($array)):
	$a=0;
	foreach ($array as $key=>$value)
	{
	    $a++;  
	    if ($a<100)
	    {
	        $value['CategoryCode']=4;
	    }  
	    if ($a<350 and $a>100)
	    {
	        $value['CategoryCode']=5;
	    }
	    if ($a<700 and $a>350)
	    {
	        $value['CategoryCode']=6;
	    }
	    if ($a<1000 and $a>700)
	    {
	        $value['CategoryCode']=8;
	    }
	    if ($a<1050 and $a>1000)
	    {
	        $value['CategoryCode']=9;
	    }
	    if ($a<1150 and $a>1050)
	    {
	        $value['CategoryCode']=10;
	    }
	    if ($a<1300 and $a>1150)
	    {
	        $value['CategoryCode']=11;
	    }
	    if ($a<1900 and $a>1300)
	    {
	        $value['CategoryCode']=12;
	    }
	
	    $array5[]=array('StockID'=>$value['StockID'],'Name'=>$value['Name'],'SalePrice'=>$value['SalePrice'],'BarCode'=>$value['BarCode'],'CategoryCode'=>$value['CategoryCode'],'Unit'=>$value['Unit'],'Cost'=>$value['Cost']);
	    $array2[$value['BarCode']]=$key;
	    $array3[mb_strtolower($value['Name'])]=$key;
	}
	else:
	foreach ($array as $key=>$value)
	{
	    $array2[$value->BarCode]=$key;
	    $array3[mb_strtolower($value->Name)]=$key;
	} 
	endif;
	$array2=json_encode($array2);

	echo "jsonproduct_bard:$array2:jsonproduct_bard"; */
}
if($stat2=='prod2'){
	$limit=$_REQUEST['limit'];
	/*$sql = "
			select *,CASE
           WHEN lse.SalePrice is NULL THEN ld.SalePrice          
           ELSE lse.SalePrice
        END as SalePrice,ld.ID as ID from  vcx_$username.listingsDB as ld LEFT OUTER JOIN vcx_$username.listingsStocksElements as lse on lse.ListingID=ld.ID and lse.StockID=$stockid limit 8000 ";
	*/
	$sql="select *,ProductGroup as department from  vcx_$username.listingsDB where active='yes' limit $limit";
	echo $sql;
	$product=sql2json($sql);
	

	echo "jsonprod:$product:jsonprod"; 
}
if($stat2=='add_prod'){
	$sql="select ID,name from vcx_weberp.units  order by SortOrder";
	$unit=sql2json($sql);
	echo "jsonunit:$unit:jsonunit"; 
	$sql="select ID,SupplierName from listingsSuppliers where Status=1 and user_ID = '$userID'  and isSupplier=1  order by SortOrder,SupplierName";
	echo $sql;
	$Sapak=sql2json($sql);
	echo "jsonsapak:$Sapak:jsonsapak"; 
	$sql="select ID,CategoryName from listingsCategories where user_ID = $userID  order by CategoryName";
	$category=sql2json($sql);
	echo $sql;
	echo "jsoncategory:$category:jsoncategory"; 

	
}
if($stat2=='client'){
	mysql_query("SET SQL_BIG_SELECTS=1;");
	$sql=" select * from (select 1 as client, 3 as sortorder, c.id as id2,c.id as ID,c.CreditBalance as CreditBalance,ClientNum, SupplierName,SupplierName as value, Phone as HomePhone, CellPhone,Obligo,(Obligo) as itra1
			
			from listingsSuppliers c,transactionpayments p,transactions t where 
                     ifnull(c.ClientNum,'')<>'' and c.isClient=1 and
			((c.ClientNum = p.CouponNumber and paymid=3)  or (p.paymid=7 and p.custid = c.ClientNum) or (t.custid = c.ClientNum and t.IsDebtPayment=1) ) and
			p.trans_id = t.id and t.user_id = $userID  and ifnull(tranErechDate,trandate) between '2003-05-15' and '".date("Y-m-d")."' 
		and c.user_id = $userID 
			and c.status=1
			group by id) as a
			union select 1 as client, 4 as sortorder, s.id as id2,s.id as ID,s.CreditBalance as CreditBalance,s.ClientNum, s.SupplierName,s.SupplierName as value, s.Phone as HomePhone,s. CellPhone,s.Obligo,(s.Obligo) as itra1 from listingsSuppliers s where isClient=1
			group by id order by sortorder,ClientNum+0
	
		";
			
			//echo $sql;
    		$client=sql2json($sql);
    		$client=str_replace("$","",$client);
    		echo  "jsonclient:$client:jsonclient";
}
if($stat2=='clientgroup'){
	$sql="SELECT * FROM clientgroups  where Status=1  and user_ID = $userID";
	$clientgroup=sql2json($sql);
	echo  "jsonclientgroup:$clientgroup:jsonclientgroup";
}
if($stat2=='worker'){
	//$sql = "SELECT  `ID` ,  `SupplierName` ,  `Phone` ,  `Phone2` ,  `CellPhone` ,  `WorkerNum` ,  `Email`  from vcx_$username.listingsSuppliers where `status` = 1 and `isWorker` = 1 and `SupplierName` <> '' and `user_ID` = ".$userID." order by `SupplierName`";
   	//$sql = "SELECT  `ID` ,  WorkerName as SupplierName ,`WorkerNum`,0 as is_clock  from vcx_$username.workers where `status` = 1  and `user_ID` = ".$userID." order by `WorkerName`";

   		$sql="SELECT w.ID ,  CONCAT(WorkerName,' ',WorkerSurName) as SupplierName ,`WorkerNum`,
			CASE WHEN t.ID IS NULL THEN 0 ELSE 1 END as is_clock 
			from workers w  left join (SELECT id,worker_id
			FROM  `attendance` 
			WHERE  `day` = CURDATE( ) 
			AND exittime IS NULL
            group by worker_id) as t on w.WorkerNum =t.worker_id where `status` = 1 and `user_ID` = '$userID' order by `WorkerName`  ";
            /*sk 18/02/2016 */   
            // 
           // CASE WHEN t.ID IS not NULL or w.show_always=1 THEN  1 ELSE 0 END as is_clock
           //CASE WHEN t.ID IS NULL THEN 0 ELSE 1 END as is_clock 
        $sql="SELECT w.ID ,  CONCAT(WorkerName,' ',WorkerSurName) as SupplierName ,`WorkerNum`,
			 CASE WHEN t.ID IS not NULL or w.show_always=1 THEN  1 ELSE 0 END as is_clock 
			from workers w  left join (SELECT id,worker_num
			FROM  `attendance` 
			WHERE  `day` = CURDATE( ) 
			AND exittime IS NULL
            group by worker_num) as t on w.WorkerNum =t.worker_num where `status` = 1 and `user_ID` = '$userID' order by `WorkerName`  ";
		
    $worker=sql2json($sql);
	echo  "jsonworker:$worker:jsonworker";	
	
}
}
?>