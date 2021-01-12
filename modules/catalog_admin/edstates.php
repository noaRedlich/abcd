<?

include("defaults.php");
session_start();

         $incomingline="$def_admin_header";

        // Showing header
         include ("header.inc");


        // Showing help section
         echo"$help_header";
         echo"$states_help";
         echo"$help_footer";

?>

  </td>
   <td valign=top align=center width="60%" bgcolor=<? echo"$def_background";?>>



 <br><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr>
 <td width="100%" align=center valign=top>

<?

echo "<font face=tahoma size=2>$def_admin_edstate</font><br><br>";

include("connect.php");

if (true){mysql_free_result($r);

if ((!empty($_POST["submit"])) and (empty($_POST[disp]))) {echo "$def_empty";}

else 

{


if ($_POST["submit"] =="$def_admin_addstate"){

$r=mysql_query("select MAX(stateselector) AS maxselector from $db_states") or die (mysql_error());

$f=mysql_fetch_array($r);
$newselector=$f["maxselector"]+1;
mysql_free_result($r);

$r=mysql_query("insert into $db_states (stateselector, state) values ('$newselector', '$_POST[disp]')") or die (mysql_error());

$time_hour = date("H");
$time_min = date("i");
$time_sec = date("s");

$date_day = date("d");
$date_month = date("m");
$date_year = date("Y");

	$ip=$_SERVER["REMOTE_ADDR"];

$log="$time_hour:$time_min:$time_sec  $date_year/$date_month/$date_day - [$_SESSION[admin_login], $ip] - $def_admin_log_state_added  $_POST[disp]";

mysql_query("INSERT INTO $db_log (log) VALUES ('$log')") or die (mysql_error());

			}

/*
elseif ($_POST["submit"] =="$def_admin_delstate"){

$r=mysql_query("select * from $db_states where stateselector='$_POST[chosen]'") or die (mysql_error());
$f=mysql_fetch_array($r);

mysql_query("delete from $db_states where stateselector='$_POST[chosen]'") or die (mysql_error());

$time_hour = date("H");
$time_min = date("i");
$time_sec = date("s");

$date_day = date("d");
$date_month = date("m");
$date_year = date("Y");

	$ip=$_SERVER["REMOTE_ADDR"];

$log="$time_hour:$time_min:$time_sec  $date_year/$date_month/$date_day - [$_SESSION[admin_login], $ip] - $def_admin_log_state_deleted  $f[state]";

mysql_query("INSERT INTO $db_log (log) VALUES ('$log')") or die (mysql_error());

			}

*/
elseif ($_POST["submit"] =="$def_admin_renstate"){

$r=mysql_query("select * from $db_states where stateselector='$_POST[chosen]'") or die (mysql_error());
$f=mysql_fetch_array($r);

mysql_query("UPDATE $db_states SET state='$_POST[disp]' where stateselector='$_POST[chosen]'") or die (mysql_error());

$time_hour = date("H");
$time_min = date("i");
$time_sec = date("s");

$date_day = date("d");
$date_month = date("m");
$date_year = date("Y");

	$ip=$_SERVER["REMOTE_ADDR"];

$log="$time_hour:$time_min:$time_sec  $date_year/$date_month/$date_day - [$_SESSION[admin_login], $ip] - $def_admin_log_state_renamed  $f[state] -> $_POST[disp]";

mysql_query("INSERT INTO $db_log (log) VALUES ('$log')") or die (mysql_error());

}

}

$r=mysql_query("select * from $db_states ORDER BY stateselector") or die (mysql_error());
$results_amount=mysql_numrows($r);

 echo "<table width=70% border=0 cellpadding=5 cellspacing=0>";

echo "<form method=post action=\"edstates.php\">";

for ($x=0;$x<$results_amount;$x++){
$f=mysql_fetch_array($r);
echo "<tr><td width=100% align=left valign=top><input type=radio name=chosen value=$f[stateselector] style=\"border:0;\">$f[state] <font color=777777 size=1>(id $f[stateselector])</font></td></tr>\n";
}

echo "<tr><td width=100% align=left valign=top><br><br>";
echo "<br><br><input type=text name=disp>&nbsp;<input type=submit name=submit value=\"$def_admin_addstate\">&nbsp;<input type=submit name=submit value=\"$def_admin_renstate\"></td></tr>\n";

echo "</table></form><br><br></td></tr></table></td>";
}

 include("footer.inc");

?>
