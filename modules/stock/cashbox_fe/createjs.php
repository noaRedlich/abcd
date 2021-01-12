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
	$sql = "
			select *,CASE
           WHEN lse.SalePrice is NULL THEN ld.SalePrice          
           ELSE lse.SalePrice
        END as SalePrice,ld.ID as ID from  vcx_$username.listingsDB as ld LEFT OUTER JOIN vcx_$username.listingsStocksElements as lse on lse.ListingID=ld.ID and lse.StockID=$stockid limit 8000 ";
	
	//$sql="select * from  vcx_$username.listingsDB ";
	$product=sql2json($sql);
	//writeUTF8("d.log", "$product");
	
	echo "$product"; 
?>