<?

include("defaults.php");
session_start();


         $incomingline="$def_admin_header";

        // Showing header
         include ("header.inc");


        // Showing help section
         echo"$help_header";
         echo"$mailer_help";
         echo"$help_footer";

?>

  </td>
   <td valign=top align=center width="60%" bgcolor=<? echo"$def_background";?>>


 <br><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr>
 <td width="100%" align=left valign=top>

<?
echo "<center><font face=tahoma size=2>Mailer</font><br><br></center><br>";

include("connect.php");


if ($_GET["REQ"] == "send") {

$r=mysql_query("select * from $db_admin where login='$_SESSION[admin_login]'") or die (mysql_error());
$f=mysql_fetch_array($r);
if(true)	{

	$maildb = mysql_query("select manager, mail from $db_users") or die (mysql_error());

	for ($mailing=0;$mailing<mysql_numrows($maildb);$mailing++)
{
	$mailf=mysql_fetch_array($maildb);

$to="$mailf[manager] <$mailf[mail]>";

	$text=$_POST[text];
	$subject=$_POST[subject];


        mail($to,$subject,$text,"FROM: ".$from);

}

	 echo "<center><font face=verdana size=2>Done!</center></font><br></td></tr></table></td>";
         include ("footer.inc");

	 exit;

	}
			}


else {

$r=mysql_query("select * from $db_admin where login='$_SESSION[admin_login]'") or die (mysql_error());

$f=mysql_fetch_array($r);


if(true){

echo "<center><table width=80%><tr><td valign=top align=left><form action=mailer.php?REQ=send method=post><font face=verdana size=2>Subject:<bR><input type=text name=subject border=0 style=\"COLOR: black; FONT-SIZE: 11px\"><br>Text: <br><textarea name=text cols=50 rows=20 border=0></textarea><br><input type=submit value=\"Send\" border=0></form></td>";
echo "</table>";

?>
<br>

</td></tr></table></td>

<?

 include("footer.inc");

exit();

}}
?>
