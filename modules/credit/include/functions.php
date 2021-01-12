<?
// dd/mm/yyyy -> YYYY-MM-DD
function dateToSQL($date){
	return substr($date,6,4) ."-".substr($date,3,2)."-".substr($date,0,2);
}

// YYYY-MM-DD -> dd/mm/yyyy
function dateFromSQL($date){
	return substr($date,8,2) ."/".substr($date,5,2)."/".substr($date,0,4);
}

function IsDate($date){
	return preg_match("/\d\d\/\d\d\/\d\d\d\d/",$date);
}
?>