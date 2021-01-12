<?

include("defaults.php");
session_start();

         $incomingline="$def_admin_header";

        // Showing header
         include ("header.inc");


        // Showing help section
         echo"$help_header";
         echo"$locations_help";
         echo"$help_footer";

?>

  </td>
   <td valign=top align=center width="60%" bgcolor=<? echo"$def_background";?>>



 <br><table width="100%" dir=ltr border="0" cellpadding="0" cellspacing="0"><tr>
 <td width="100%" align=center valign=top>

<?

echo "<font face=tahoma size=2>$def_admin_edlocation</font><br><br>";

include("connect.php");

if (true){

if ((!empty($_POST["submit"])) and (empty($_POST[disp]))) {echo "$def_empty";}

else 

{


if ($_POST["submit"] =="Add new"){

$r=mysql_query("select MAX(locationselector) AS maxselector from $db_location") or die (mysql_error());

$f=mysql_fetch_array($r);
$newselector=$f["maxselector"]+1;
mysql_free_result($r);

$r=mysql_query("insert into $db_location (locationselector, location_".$def_admin_language.",parent,sortorder) values ('$newselector', '$_POST[disp]','$_POST[parent]','$_POST[sortorder]')") or die (mysql_error());

$time_hour = date("H");
$time_min = date("i");
$time_sec = date("s");

$date_day = date("d");
$date_month = date("m");
$date_year = date("Y");

	$ip=$_SERVER["REMOTE_ADDR"];

$log="$time_hour:$time_min:$time_sec  $date_year/$date_month/$date_day - [$_SESSION[admin_login], $ip] - $def_admin_log_locationadded  $_POST[disp]";

mysql_query("INSERT INTO $db_log (log) VALUES ('$log')") or die (mysql_error());

			}


elseif ($_POST["submit"] =="Delete"){

$r=mysql_query("select * from $db_location where locationselector='$_POST[chosen]'") or die (mysql_error());
$f=mysql_fetch_array($r);

mysql_query("delete from $db_location where locationselector='$_POST[chosen]'") or die (mysql_error());

$time_hour = date("H");
$time_min = date("i");
$time_sec = date("s");

$date_day = date("d");
$date_month = date("m");
$date_year = date("Y");

	$ip=$_SERVER["REMOTE_ADDR"];

$log="$time_hour:$time_min:$time_sec  $date_year/$date_month/$date_day - [$_SESSION[admin_login], $ip] - $def_admin_log_locationdeleted  $f[location]";

mysql_query("INSERT INTO $db_log (log) VALUES ('$log')") or die (mysql_error());

			}


elseif ($_POST["submit"] =="Change"){

$r=mysql_query("select * from $db_location where locationselector='$_POST[chosen]'") or die (mysql_error());
$f=mysql_fetch_array($r);

$sql="UPDATE $db_location SET sortorder='$_POST[sortorder]', parent='$_POST[parent]',location_".$def_admin_language." = '".($_POST[disp])."' where locationselector='$_POST[chosen]'";
mysql_query($sql) or die (mysql_error());
//echo "UPDATE $db_location SET location_".$def_admin_language."='$_POST[disp]' where locationselector='$_POST[chosen]'";
$time_hour = date("H");
$time_min = date("i");
$time_sec = date("s");

$date_day = date("d");
$date_month = date("m");
$date_year = date("Y");

	$ip=$_SERVER["REMOTE_ADDR"];

$log="$time_hour:$time_min:$time_sec  $date_year/$date_month/$date_day - [$_SESSION[admin_login], $ip] - $def_admin_log_locationrenamed  $f[location] -> $_POST[disp]";

mysql_query("INSERT INTO $db_log (log) VALUES ('$log')") or die (mysql_error());

}


}

$sql="select l.* from $db_location l left outer join $db_location l1 on l1.locationselector=l.parent and l1.parent=0 ORDER BY (case when l.parent=0 then ifnull(l.sortorder,1)*10000+l.locationselector else ifnull(l1.sortorder,1)*10000+l.parent end),parent,  binary l.location_".$def_admin_language;
//echo $sql;
$r=mysql_query($sql) or die (mysql_error());
$results_amount=mysql_numrows($r);

 echo "<div style='width:95%;border:solid 1 black;overflow-Y:scroll;height:300'>
 <table width=100% border=0 cellpadding=2 cellspacing=0>";

echo "<form method=post name=ed action=\"edlocations.php\">";

for ($x=0;$x<$results_amount;$x++){
	$f=mysql_fetch_array($r);
	$name=$f["location_".$def_admin_language];
	echo "<tr><td width=100% align=left valign=top>";
	if ($f[parent])echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	echo "<input type=radio onclick=getCats(this) sortorder='$f[sortorder]' parent='$f[parent]' cats=\"".str_replace("\"","&quot;",$name)."\" name=chosen value=$f[locationselector] >".(($f[parent]==0)?"<b>":"")." $name ".(($f[parent]==0)?"</b>":"")."<font color=777777 size=1>(id $f[locationselector])</font></td></tr>\n";
}




echo "</table></div>";

echo "<br>
Name &nbsp;<input type=text name=disp>&nbsp;
Sort order <input size=2 type=text name=sortorder>&nbsp;
Country <select name=parent>
<option value=0>
";
$r=mysql_query("select * from $db_location where parent=0") or die (mysql_error());
while($f=mysql_fetch_array($r)){
	echo "<option value=".$f["locationselector"].">".$f["location_".$def_admin_language];
}

echo "
</select><hr>
<input type=submit name=submit value='Add new'>&nbsp;
<input type=submit name=submit value=Change>&nbsp;
<input type=submit name=submit value=Delete>&nbsp;

\n";

echo"</td></tr></form></table></td>";
}

 include("footer.inc");

?>
<script>
function getCats(el)
{
	document.ed.disp.value = el.cats;
	document.ed.parent.value=el.parent;
	document.ed.sortorder.value=el.sortorder;
}
</script>