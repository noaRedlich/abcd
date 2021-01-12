<?

include("defaults.php");
session_start();

         $incomingline="$def_admin_header";

        // Showing header
         include ("header.inc");


        // Showing help section
         echo"$help_header";
         echo"$cat_help";
         echo"$help_footer";

?>

  </td>
   <td valign=top align=center width="60%" bgcolor=<? echo"$def_background";?>>


 <br><table width="100%" dir=<?=$direction?> border="0" cellpadding="0" cellspacing="0"><tr>
 <td width="100%" align=center valign=top>


<?

echo "<font face=tahoma size=2>$def_admin_edcat</font><br><br>";

include("connect.php");
if (true)
 {



if ((!empty($_POST["submit"])) and ($_POST["submit"] !="$def_admin_delcat" && empty($_POST[disp_he]))) {echo "$def_empty";}

else 

{

if ($_POST["submit"] =="$def_admin_addcat")
  {

$r=mysql_query("select MAX(selector) AS maxselector from $db_category") or die (mysql_error());

$f=mysql_fetch_array($r);
$newselector=$f["maxselector"]+1;
mysql_free_result($r);

$r=mysql_query("insert into $db_category (selector, category_he,category_ru,category_en) values ('$newselector', '$_POST[disp_he]', '$_POST[disp_ru]', '$_POST[disp_en]')") or die (mysql_error());

$time_hour = date("H");
$time_min = date("i");
$time_sec = date("s");

$date_day = date("d");
$date_month = date("m");
$date_year = date("Y");

	$ip=$_SERVER["REMOTE_ADDR"];

$log="$time_hour:$time_min:$time_sec  $date_year/$date_month/$date_day - [$_SESSION[admin_login], $ip] - $def_admin_log_newcatadded $_POST[disp]";

mysql_query("INSERT INTO $db_log (log) VALUES ('$log')") or die (mysql_error());

  }

elseif ($_POST["submit"] == "$def_admin_addsubcat")

   {

$r=mysql_query("select MAX(catsubsel) AS maxsubsel from $db_subcategory") or die (mysql_error());

$oldcat = explode("::", $_POST["chosen"]); 
$f=mysql_fetch_array($r);
$newsubselector=$f["maxsubsel"]+1;
mysql_free_result($r);

$r=mysql_query("insert into $db_subcategory (catsel, catsubsel, subcategory_en,subcategory_ru,subcategory_he) values ('$oldcat[0]', '$newsubselector', '$_POST[disp_en]', '$_POST[disp_ru]', '$_POST[disp_he]')") or die (mysql_error());

$time_hour = date("H");
$time_min = date("i");
$time_sec = date("s");

$date_day = date("d");
$date_month = date("m");
$date_year = date("Y");

	$ip=$_SERVER["REMOTE_ADDR"];

$log="$time_hour:$time_min:$time_sec  $date_year/$date_month/$date_day - [$_SESSION[admin_login], $ip] - $def_admin_log_newsubcatadded $_POST[disp]";

mysql_query("INSERT INTO $db_log (log) VALUES ('$log')") or die (mysql_error());

   }


elseif ($_POST["submit"] == "$def_admin_catren")

    {

$cat = explode("::", $_POST["chosen"]); 
$cat0=$cat[0];
$cat1=$cat[1];

if ($cat1 == "")
     {

$r=mysql_query("SELECT * from $db_category where selector='$cat0'") or die (mysql_error());
$f=mysql_fetch_array($r);

mysql_query("UPDATE $db_category SET category_en='$_POST[disp_en]',category_ru='$_POST[disp_ru]',category_he='$_POST[disp_he]' where selector='$cat0'") or die (mysql_error());

$time_hour = date("H");
$time_min = date("i");
$time_sec = date("s");

$date_day = date("d");
$date_month = date("m");
$date_year = date("Y");

	$ip=$_SERVER["REMOTE_ADDR"];

$log="$time_hour:$time_min:$time_sec  $date_year/$date_month/$date_day - [$_SESSION[admin_login], $ip] - $def_admin_log_catrenamed  $f[category] -> $_POST[disp]";

mysql_query("INSERT INTO $db_log (log) VALUES ('$log')") or die (mysql_error());

      }

else  

      {

$r=mysql_query("SELECT * from $db_subcategory where catsel='$cat0' and catsubsel='$cat1'") or die (mysql_error());
$f=mysql_fetch_array($r);

mysql_query("UPDATE $db_subcategory SET subcategory_en='$_POST[disp_en]',subcategory_ru='$_POST[disp_ru]',subcategory_he='$_POST[disp_he]' where catsel='$cat0' and catsubsel='$cat1'") or die (mysql_error());

$time_hour = date("H");
$time_min = date("i");
$time_sec = date("s");

$date_day = date("d");
$date_month = date("m");
$date_year = date("Y");

	$ip=$_SERVER["REMOTE_ADDR"];

$log="$time_hour:$time_min:$time_sec  $date_year/$date_month/$date_day - [$_SESSION[admin_login], $ip] - $def_admin_log_subcatrenamed  $f[subcategory] -> $_POST[disp]";

mysql_query("INSERT INTO $db_log (log) VALUES ('$log')") or die (mysql_error());

     }

     }

elseif ($_POST["submit"] == "$def_admin_delcat")

      {

$ip=$_SERVER["REMOTE_ADDR"];

$cat = explode("::", $_POST["chosen"]); 
$cat0=$cat[0];
$cat1=$cat[1];

if ($cat1 == "")

       {

$r=mysql_query("SELECT * from $db_category where selector='$cat0'") or die (mysql_error());
$f=mysql_fetch_array($r);


mysql_query("delete from $db_category where selector='$cat0'") or die (mysql_error());
$time_hour = date("H");
$time_min = date("i");
$time_sec = date("s");

$date_day = date("d");
$date_month = date("m");
$date_year = date("Y");

	$ip=$_SERVER["REMOTE_ADDR"];

$log="$time_hour:$time_min:$time_sec  $date_year/$date_month/$date_day - [$_SESSION[admin_login], $ip] - $def_admin_log_catdeleted  $f[category]";

mysql_query("INSERT INTO $db_log (log) VALUES ('$log')") or die (mysql_error());

      }

else    {


$r=mysql_query("SELECT * from $db_subcategory where catsel='$cat0' and catsubsel='$cat1'") or die (mysql_error());
$f=mysql_fetch_array($r);

mysql_query("delete from $db_subcategory where catsel='$cat0' and catsubsel='$cat1'") or die (mysql_error());

$time_hour = date("H");
$time_min = date("i");
$time_sec = date("s");

$date_day = date("d");
$date_month = date("m");
$date_year = date("Y");

	$ip=$_SERVER["REMOTE_ADDR"];

$log="$time_hour:$time_min:$time_sec  $date_year/$date_month/$date_day - [$_SESSION[admin_login], $ip] - $def_admin_log_subcatdeleted  $f[subcategory]";

mysql_query("INSERT INTO $db_log (log) VALUES ('$log')") or die (mysql_error());

       }

    }


}


$r=mysql_query("select * from $db_category") or die (mysql_error());
$results_amount=mysql_numrows($r);

echo "<div style='overflow:auto;width:95%;padding:5px;height:260;border:solid 1 black'><table width=100% border=0 cellpadding=0 cellspacing=0>";

echo "<form method=post name=ed action=\"edcat.php\">";

for ($x=0;$x<$results_amount;$x++){
$f=mysql_fetch_array($r);
$name=$f[category_he]. " / ".$f[category_en]." / ".$f[category_ru];
echo "<tr><td width=100% valign=top><br><font color=777777 size=1>(id $f[selector])</font> <input type=radio onclick='getCats(this);' cats=\"$name\" name=chosen value=$f[selector] style=\"border:0;\"><font size=2>$name <br></td></tr>\n";


$re=mysql_query("select * from $db_subcategory where catsel=$f[selector]") or die (mysql_error());
$results_amount2=mysql_numrows($re);

for ($x1=0;$x1<$results_amount2;$x1++){

$fe=mysql_fetch_array($re);
$name=$fe[subcategory_he]. " / ".$fe[subcategory_en]." / ".$fe[subcategory_ru];
echo "<tr><td width=100%  valign=top>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color=bebebe size=1>(id $fe[catsubsel])</font> <input type=radio onclick='getCats(this);' cats=\"$name\"  name=chosen value=$f[selector]::$fe[catsubsel]  style=\"border:0;\">$name <br></td></tr>\n";
}
}


echo "</table></div>";

echo "<br>
Hebrew&nbsp;  <input type=text size=60 name=disp_he><br>
English&nbsp;  <input type=text  size=60 name=disp_en><br>
Russian <input type=text  size=60 name=disp_ru>
<hr>
&nbsp;<input type=submit name=submit value=\"$def_admin_addcat\">
&nbsp;<input type=submit name=submit value=\"$def_admin_delcat\">
&nbsp;<input type=submit name=submit value=\"$def_admin_addsubcat\">
&nbsp;<input type=submit name=submit value=$def_admin_catren>\n
</td></tr></form></table></td>";

}

 include("footer.inc");

?>
<script>
function getCats(el)
{
	cats = el.cats.split(" / ");
	document.ed.disp_he.value = cats[0];
	document.ed.disp_en.value = cats[1];
	document.ed.disp_ru.value = cats[2];
}
</script>
