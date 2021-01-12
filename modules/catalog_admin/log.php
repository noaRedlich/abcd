<?

include("defaults.php");
session_start();

         $incomingline="$def_admin_header";

        // Showing header
         include ("header.inc");


        // Showing help section
         echo"$help_header";
         echo"$log_help";
         echo"$help_footer";

?>

  </td>
   <td valign=top align=center width="60%" bgcolor=<? echo"$def_background";?>>


 <br><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr>
 <td width="100%" align=left valign=top>

<?echo "<center><font face=tahoma size=2>$def_admin_log</font><br><br></center><br>";

include("connect.php");

$pages=50;

if ($_GET["REQ"] == "clear") {

$r=mysql_query("select * from $db_admin where login='$_SESSION[admin_login]'") or die (mysql_error());
$f=mysql_fetch_array($r);
if (true) 
	{
	 mysql_query("delete from $db_log") or die (mysql_error());
	 echo "<center><font face=verdana size=2>Recent log was successfully cleared</center></font><br></td></tr></table><br><br></td>";

	include("footer.inc");
	 exit;
	}
			}


if ($_GET["REQ"] == "auth"){

$r=mysql_query("select * from $db_admin where login='$_SESSION[admin_login]'") or die (mysql_error());

$f=mysql_fetch_array($r);

if (true) {

$r=mysql_query("select * from $db_log") or die (mysql_error());

$results_amount=mysql_numrows($r);

$npage=$_GET["page"]+1;
$ppage=$_GET["page"]-1;
$page1=$_GET["page"]*$pages;
$page2=$page1+$pages;

$r=mysql_query( "select * from $db_log ORDER BY log LIMIT $page1, $page2") or die(mysql_error());

$fetchcounter=$pages;

$f=$results_amount-$page1;

if ($f < $pages) $fetchcounter=$results_amount-$page1;

for($i=0; $i<$fetchcounter; $i++)
 { $f=mysql_fetch_array($r);

echo "<font>&nbsp;&nbsp;&nbsp;&nbsp;$f[log]</font><br>";
         }	

echo "<center><table><tr><br>";

$z=0;

if ($results_amount > $pages){
for($x=0; $x<$results_amount; $x=$x+$pages)
{
if ($z == $_GET[page]) {echo "<form><td valign=top align=center><input type=button value=\"",$x+1,"-",$x+$pages,"\" border=0 style=\"COLOR: black; FONT-SIZE: 11px; font: bold\"></form></td>";$z++;} else {echo "<td valign=top align=center><form action=log.php?REQ=auth&page=$z&pages=$pages method=post><input type=submit value=\"",$x+1,"-",$x+$pages,"\" border=0 style=\"COLOR: black; FONT-SIZE: 11px\"></form></td>";$z++;}
}
}

echo "<td valign=top align=center><form action=log.php?REQ=clear method=post>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit value=\"Clear\" border=0 style=\"COLOR: black; FONT-SIZE: 11px\"></form></td>";
echo "</table><br><br></td></tr></table></td>";

include("footer.inc");
exit();

}}
?>
