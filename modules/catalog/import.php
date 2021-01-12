<?php

	require "excelparser.php";
	$simple=1;
	require "defaults.php";
	require "../catalog_admin/connect.php";
	 session_start(); 
	 require_once($GO_CONFIG->class_path.'users.class.inc');
	 $users = new users();
	 $userID = $GO_SECURITY->user_id;
	 $user = $users->get_user($userID);
	 $_SESSION["login"] = $user[username];
 	 if ($id && $_SESSION[admin]==1){
 	    $r=mysql_query("select * from $db_users where selector='$id'") or die (mysql_error());
	 }
	 else{
	   $r=mysql_query("select * from $db_users where login='$_SESSION[login]'") or die (mysql_error());
	 }

   // Selecting current login and pass from db
   $f=mysql_fetch_array($r);



	function print_error( $msg )
	{
		print <<<END
		<tr>
			<td colspan=7><font color=red><b>Error: </b></font>$msg</td>
			<td><font color=red><b>Rejected</b></font></td>
		</tr>

END;
	}
	
		function error( $msg )
	{
		print <<<END
		<table>
		<tr>
			<td><font color=red><b>Error: </b></font>$msg</td>
		</tr>
		</table>

END;
	}

	function uc2html($str) {
		$ret = '';
		for( $i=0; $i<strlen($str)/2; $i++ ) {
			$charcode = ord($str[$i*2])+256*ord($str[$i*2+1]);
			$ret .= '&#'.$charcode;
		}
		return $ret;
	}

	function get( $exc, $data )
	{
		switch( $data['type'] )
		{
			// string
		case 0:
			$ind = $data['data'];
			if( $exc->sst[unicode][$ind] )
				return uc2html($exc->sst['data'][$ind]);
			else
				return $exc->sst['data'][$ind];

			// integer
		case 1:
			return (integer) $data['data'];

			// float
		case 2:
			return (float) $data['data'];
        
		case 3:
			return gmdate("m-d-Y",$exc->xls2tstamp($data[data]));

		default:
			return '';
		}
	}

	function fatal($msg = '') {
		echo '[Fatal error]';
		if( strlen($msg) > 0 )
			echo ": $msg";
		echo "<br>\nScript terminated<br>\n";
		if( $f_opened) @fclose($fh);
		exit();
	};

	$err_corr = "Unsupported format or file corrupted";

	$excel_file_size;
	$excel_file = $_FILES['excel_file'];
	if($excel_file)
		$excel_file = $_FILES['excel_file']['tmp_name'];

	if($excel_file == '') fatal("No file uploaded");

	$fh = @fopen ($excel_file,'rb');
	if( !$fh ) fatal("No file uploaded");
	if( filesize($excel_file)==0 ) fatal("No file uploaded");

	$fc = fread( $fh, filesize($excel_file) );
	@fclose($fh);
	if( strlen($fc) < filesize($excel_file) )
		fatal("Cannot read file");

	$exc = new ExcelFileParser;
	if( $exc->ParseFromFile($excel_file)>0 ) fatal($err_corr);
	//$res = $exc->ParseFromString($fc);
	switch ($res) {
		case 0: break;
		case 1: fatal("Can't open file");
		case 2: fatal("File too small to be an Excel file");
		case 3: fatal("Error reading file header");
		case 4: fatal("Error reading file");
		case 5: fatal("This is not an Excel file or file stored in Excel < 5.0");
		case 6: fatal("File corrupted");
		case 7: fatal("No Excel data found in file");
		case 8: fatal("Unsupported file version");

		default:
			fatal("Unknown error");
	}

	if( count($exc->worksheet['name']) < 1 ) fatal("No worksheets in Excel file.");

	//
	// Process only three first worksheets
	$codes = "";
	
	for ($worksheet=0;$worksheet<count($exc->worksheet['name']);$worksheet++){

	if( $exc->worksheet['unicode'][$worksheet] )
	{
		$wname = uc2html($exc->worksheet['name'][$worksheet]);
	}
	else
	{
		$wname = $exc->worksheet['name'][$worksheet];
	}

	if ($wname!="Russian" && $wname!="Hebrew" && $wname!="English"){
		continue;
	}
	
	print "<b>Worksheet: \"";
	print $wname."\"</b><br>";

	//
	// Obtain worksheet data

	$ws = $exc->worksheet['data'][$worksheet];

	/****** DEBUG STUFF*
		print '<pre>';
		print_r($exc->worksheet);
		print '</pre>';
	*/

	//
	// Process

	if( is_array($ws) &&
	    isset($ws['max_row']) &&
	    isset($ws['max_col']) )
	{

		//
		// Validate number of rows and cols

		if( $ws['max_col'] < 2 ) {
			error("Invalid format.<br>Number of columns is less then 2.");
			continue;
		}
		if( $ws['max_row'] == 0 ){
    		error("Invalid format.<br>No rows defined in document.");
			continue;
		}

		//
		// Iterate rows

		$data = $ws['cell'];

		$items = array();

		print "<b>Receiving data:</b><br>";
		print "<table border=1>\n";

		foreach( $data as $i => $row )
		{
			////////////////////////////////////////////////////////////////////////////
			// $i now contains row index.
			// $row - row data
			//
			// Note: You should use foreach or language construction
			// 		like this to iterate rows, because if excel file contains
			// 		only 2 rows with indexes 0 and 100, then $data will be equal to
			// 		array( 0 => data1, 100 => data2 ).
			////////////////////////////////////////////////////////////////////////////

			/****** DEBUG STUFF
				print '<pre>';
				print_r($row);
				print '</pre>';
			*/

			// this counter is for information only.
			// so adjust it to be 1 - based.
			$i++;

			//
			// Check the row has valid format

			if( !is_array( $row ) )
			{
				print_error("Row $i is of invalid format.");
				continue;
			}

			if( count( $row ) < 2 )
			{
				print_error("Row $i has less then 2 columns.");
				continue;
			}

			$valid = true;

			for( $col = 0; $col < 2; $col++ )
				if( !is_array( $row[$col] ) )
				{
					print_error("Column $col in row $i is of invalid format.");
					$valid = false;
					break;
				}
			if( !$valid ) continue;

			//
			// Fetch data

			$code = get( $exc, $row[0] );
			$name = get( $exc, $row[1] );
			$spec = get( $exc, $row[2] );
			$price = get( $exc, $row[3] );
            $quantity = get( $exc, $row[4] );
            $type = get( $exc, $row[5] );
            $category = get( $exc, $row[6] );

			//
			// Validate data

			if(trim($code)=="" || !is_numeric( $code ) )
			{
				print_error("Row $i is of invalid format - code field is empty or non-numeric.");
				continue;
			}

			if(trim($name)=="")
			{
				print_error("Row $i is of invalid format - name field is empty.");
				continue;
			}

			print <<<END
			<tr>
				<td>$code&nbsp;</td>
				<td>$name&nbsp;</td>
				<td>$spec&nbsp;</td>
				<td>$price&nbsp;</td>
				<td>$quantity&nbsp;</td>
				<td>$type&nbsp;</td>
				<td>$category&nbsp;</td>
				<td><font color=green><b>Accepted</b></font></td>
			</tr>

END;

			//
			// Store data

			$cur = count( $items );
			$items[ $cur ]['code'] = $code;
			$items[ $cur ]['name'] = $name;
			$items[ $cur ]['spec'] = $spec;
			$items[ $cur ]['price'] = $price;
			$items[ $cur ]['quantity'] = $quantity;
			$items[ $cur ]['type'] = $type;
			$items[ $cur ]['category'] = $category;
		}

		print "</table>\n";
	}

	//
	// Write $items array to the MySQL table.

	print "<b>Insert data into the database.... </b>";

	if( count( $items ) == 0 ){
		error('No data to import into the MySQL table.');
		continue;
	}
	else
	{
		//

		//
		// Prepare query

		switch ($wname){
			case "Hebrew":  $lang = "he"; break;
			case "English": $lang = "en"; break;
			case "Russian": $lang = "ru"; break;
		}
		
		foreach( $items as $item )
		{
			$code = trim(addslashes( $item['code'] ));
			$name = addslashes( $item['name'] );
			$spec = addslashes( $item['spec'] );
			$quantity = addslashes( $item['quantity'] );
			$price = addslashes( $item['price'] );
			$type = addslashes( $item['type'] );
			$category = addslashes( $item['category'] );
		
			if (!strpos(",".$codes,"'$code',")){
				$codes .= "'$code',";
			}

			$q = mysql_query("select num from dev_offers WHERE code = '$code' and firmselector = $f[selector]");
			$date = date("Y-m-d");
			if (mysql_num_rows($q)>0){
			
				$query = "UPDATE dev_offers SET 
				date = '$date',
				item_$lang = '$name', ";
				if ($price){
					$query .= "price = '$price', ";
				}
				if ($quantity){
					$query .= "quantity = '$quantity', ";
				}
				if ($type){
					$query .= "type = '$type', ";
				}
				if ($category){
					$query .= "firmcategory1 = '$category', ";
				}
				$query .= "message_$lang = '$spec' ";
				$query .= "WHERE code = '$code' and pricelist=1 and firmselector = $f[selector]";
				mysql_query( $query) or die(mysql_error());
			
			}
			else
			{
			
				if (!$category) $category = $f[category1];
				if (!$type) $type=1;
				$query  = "INSERT INTO dev_offers (pricelist,date,firmselector, firmcategory1, code, item_$lang, message_$lang, price, quantity, type ) VALUES (";
				$value  = "1,'$date','$f[selector]','$category',";
				$value .= sprintf("\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\")",$code, $name, $spec, $price,$quantity,$type);
				$query .= $value;
	     		// Execute SQL query
				$result = mysql_query( $query) or die(mysql_error());

			}
			
		}


		//echo $query."<BR>";
		$num = count($items);

		print "<b style=color:green>$num row(s) successfully processed.</b><br><br>";

		//
		// Close connection

	}
	
	}//loop worksheets
	
	$codes = substr($codes,0, strlen($codes)-1);
	if ($codes){
		$query = "delete from dev_offers where pricelist=1 and firmselector = $f[selector] and code not in ($codes)";
//		echo $query;
		mysql_query($query);
		$num = mysql_affected_rows();
		if ($num){
			print "<b style=color:green>$num row(s) deleted from the catalog.</b><br><br>";
		}
	}
	
?>


</body>
</html>
