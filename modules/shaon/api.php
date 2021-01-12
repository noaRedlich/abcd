<?php
////vcx_weberp »listingsStocks
@include("../../Group-Office.php");
include( "../../classes/phpexcel/class.writeexcel_workbook.inc.php");
include( "../../classes/phpexcel/class.writeexcel_worksheet.inc.php");
include("../../classes/adodb/adodb.inc.php");
if (empty($_GET['user']))
{
    die('Bad User');
}
$dbname='vcx_'.htmlspecialchars($_GET['user']);
global $db_type;
$GOCONFIG = new GO_CONFIG();
$dbcnx = mysql_connect($GOCONFIG->db_host, $GOCONFIG->stock_db_user, $GOCONFIG->stock_db_pass);
if (!$dbcnx)
{
    die('Error mysql');
}
if (!mysql_select_db($dbname,$dbcnx) )
{
    die("Bad User");
}
if (empty($_GET['date']))
{
	die('Bad Date');
}
else
{
	$date=explode('-',$_GET['date']);
	$month=$date[1];
	$day= $date[0];
	$year=$date[2];
	if (!checkdate($month ,  $day ,  $year ))
		die('Bad Date');

}
if (empty($_GET['time']) or strtotime( $time )==false)
{
	 die('Bad Time');
}
else
{
	 $time=explode(':',$_GET['date']);
	 $minute=$time[1];
	 $hour=$time[0];
}
die('jobs');
?>
/*$db_user = $GOCONFIG->stock_db_user;
$db_password = $GOCONFIG->stock_db_pass;
$db_database = $dbname;
$db_server = $GOCONFIG->db_host;
$db_type = $GO_CONFIG->db_type;
$conn =  ADONewConnection($db_type);
var_dump($db_database);
$conn->Connect($db_server, $db_user, $db_password, $db_database);
global $action, $id, $cur_page, $lang, $conn, $config;
print_r($_GET);

var_dump($dbname);
$rslt = mysql_query("SHOW TABLES");
if (mysql_num_rows($rslt)!==1)
{
	while ($row = mysql_fetch_assoc($rslt)) {
    print_r($row);      }
    var_dump(mysql_num_rows($rslt));
    die('Bad User');

}
die('jobs');?>  */
