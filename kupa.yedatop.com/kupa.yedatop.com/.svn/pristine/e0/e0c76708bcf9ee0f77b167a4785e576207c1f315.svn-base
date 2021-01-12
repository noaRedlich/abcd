<?php

	global $action, $id, $lang, $conn, $config;
	$noheader = true;
	
	require("../../Group-Office.php");
	$GO_SECURITY->authenticate();
	$userID = $GO_SECURITY->user_id;
	
	include('include/db.php');
	include('include/adodb/adodb.inc.php');
	$conn =  ADONewConnection($db_type);
	$conn->PConnect($db_server, $db_user, $db_password, $db_database);	
	
	if (!$preview){
		header("Content-type: application/word"); 
		header("Content-Disposition: attachment; filename=report_$contract_id.doc"); 
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
	}
	
	$template = split("\*",$template);
	$mode = $template[0];
	$template = $template[1];
	
	$home_path = $GO_CONFIG->file_storage_path.(($mode=="m")?$ses_username:$GO_CONFIG->useradmin);
	$path = $home_path."/realty templates";
	
	
	//print report
	if ($template){
		$filename = $path."/".$template;
		$fp = fopen($filename,"r");
		$content = fread($fp,500000);
		$content = processTemplate($content,$contract_id);
		echo $content;
	}
	//end print report
	

	$conn->Close(); // close the db connection
?>


<?
//process template
function processTemplate($content,$contract_id){
global $userID,$conn,$tpl,$codes,$lang;
$data = array();
$sql ="
SELECT 
	cc1.name as owner_name,
	cc1.home_phone as owner_home_phone,
	cc1.work_phone as owner_work_phone,
	c.contract_number,c.customer,c.term,c.amount,c.currency,c.usd_rate,
	d.ID, d.Title, d.contact1, d.notes, d.last_modified, d.featured, d.active, d.expiration,
	f.field_name, db.field_value, f.field_type, f.field_caption, 
	f.default_text, f.field_elements, f.required 
	FROM 
	listingsFormElements f 
	left outer join listingsDBElements db on db.field_name = f.field_name 
	inner join listingsDB d on d.ID = db.listing_id 
	inner join realtycontracts c on c.listingid = d.ID and c.id = '$contract_id' 
	AND c.user_id = '$userID' 
	left outer join contacts cc1 on cc1.name = d.contact1 ORDER BY rank 
	";
//	echo $sql;
	$recordSet = $conn->Execute($sql);
	for($i=0;$i<count($recordSet->fields);$i++){
		$f = $recordSet->FetchField($i);
		$fname=$f->name;
		if (!strpos(" ".$fname,"field_") && !strpos(" ".$fname,"default_text")){
			$data[$fname]=$recordSet->fields[$fname];
		}
	}
	while (!$recordSet->EOF)
	{
		$n = $recordSet->fields["field_name"];
		$data[$n]=$recordSet->fields["field_value"];
		$recordSet->MoveNext();
	}
	
	if ($codes){
			$content= "
			<html dir=rtl><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1255\"></head>
			<table border=1><tr bgcolor=silver><td>Code</td><td>Value</td></tr>";
	}
	
	foreach ($data as $key => $value) { 
		$value = str_replace("||",", ",$value);
		if ($codes){
			$content.= "<tr><td>\{$key}</td><td>$value&nbsp;</td></tr>";
		}
		else{
		if ($tpl!=1){

			$content = eregi_replace("{".$key."}","<b>$value</b>",$content);
		}
		}
	} 
	
	if ($codes){
			$content.= "</table>";
	}
	
	return $content;
}
?>