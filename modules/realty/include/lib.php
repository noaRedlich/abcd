<?php
// This script and data application were generated by AppGini 2.4 on 25/06/2003 at 19:54:53
// Download AppGini for free from http://www.bigprof.com/appgini/download/

include("include/common.php");
if (!loginCheck('User'))exit;
include("$config[template_path]/admin_top.html");	
	
error_reporting(E_ALL ^ E_NOTICE);
include("./datalist.php");

function sql($statment)
{

		global $db_user,$db_password,$db_database,$db_server;
		
		$dbhost = $db_server;
		$dbuser = $db_user;
		$dbpass = $db_password;
		$dbname = $db_database;
		
		/****** Connect to MySQL ******/
		if(!mysql_connect($dbhost, $dbuser, $dbpass))
		{
			echo StyleSheet() . "\n\n<div class=Error>";
			echo "ERROR:" . mysql_error();
			echo "</div>";
			exit;
		}
		/****** Select DB ********/
		if(!mysql_select_db($dbname))
		{
			echo StyleSheet() . "\n\n<div class=Error>";
			echo "ERROR:" . mysql_error();
			echo ". If you haven't set up the database yet, you can do so by clicking <a href='setup/setup.php'>here</a>";
			echo "</div>";
			exit;
		}
	
	if(!$result = mysql_query($statment))
	{
			echo StyleSheet() . "\n\n<div class=Error>";
		echo "<br><b>ERROR:</b><br><br>
		     <!--strong>Query:</strong><br> $statment<br><br-->
		     " . mysql_error();
			if(stristr($statment, "select ")) echo ".<br>If you haven't set up the database yet, you can do so by clicking <a href='setup/setup.php'>here</a>";
			echo "</div>";
			echo "<a href=\"javascript:history.go(-1);\">&lt; Back</a>";
		exit;
	}
	return $result;
}

function NavMenus()
{
    return '';
	$t = time();
	$menu  = "<select name=nav_menu onChange='window.location=document.myform.nav_menu.options[document.myform.nav_menu.selectedIndex].value;'>";
	$menu .= "<option value='#' class=SelectedOption style='color:black;'>Select A Table</option>";
	$menu .= "<option value='realtycontracts_view.php?t=$t' class=SelectedOption>Contracts</option>";
	$menu .= "<option value='listingsDB_view.php?t=$t' class=SelectedOption>listingsDB</option>";
	$menu .= "<option value='realtycontractpayments_view.php?t=$t' class=SelectedOption>Payments</option>";
	$menu .= "</select>";
	return $menu;
}

function StyleSheet()
{
	$style = "
<style type=text/css>
<!--
.TableTitle {
        background-color: #003399;
        font-size: 12px;
		padding: 1px;
        font-weight: bold;
        color: #FFFFFF;
}

.TableHeader,a.TableHeader,a:visited.TableHeader {
        background-color: #003399;
        font-size: 12px;
		padding: 1px;
		text-align:center;
        font-weight: bold;
        color: #FFFFFF;
}
a.TableHeader,a:visited.TableHeader {
text-decoration:underline;
}

.TableHeader:hover {
color:red;
}

.TableBody {
font-family:arial;
font-size:10pt;
background-color:white;

}

.TableBody:hover {
color:red;
}

.TableBodyNumeric {
font-family:arial;
font-size:10pt;
background-color:white;
text-align:right;
}

td.TableBodyNumeric,td.TableBody,td.TableBodySelectedNumeric,td.TableBodySelected{
border-bottom:solid 1 gray
}

.TableBodyNumeric:hover {
color:red;
}

.TableFooter {
font-family:arial;
font-weight:bold;
font-size:10pt;
background-color:SILVER;
text-align:center;
}

.TableBodySelected {
font-family:arial;
font-size:10pt;
background-color:#efefef;
}

.TableBodySelectedNumeric {
font-family:arial;
font-size:10pt;
background-color:#efefef;
text-align:right;
}

.Error {
font-family:arial;
font-size:12px;
font-weight:bold;
background-color:white;
color:blue;
text-decoration:none;
margin-bottom:10pt;
}

.TextBox {
font-family:arial;
font-size:10pt;

}

.Option {
font-family:arial;
font-size:12px;
}

.SelectedOption {
font-family:arial;
font-size:12px;
color:blue;
}

-->
</style>\n\n";

	return $style;
}
?>