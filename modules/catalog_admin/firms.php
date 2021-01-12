<?

include("defaults.php");

 session_start();


         $incomingline="$def_admin_header";

        // Showing header
         include ("header.inc");


        // Showing help section
         echo"$help_header";
         echo"$firms_help";
         echo"$help_footer";

?>

  </td>
   <td valign=top align=center width="60%" bgcolor=<? echo"$def_background";?>>


 <br><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align=center valign=top>


<?
echo "<font face=tahoma size=2>$def_admin_newreg</font><br><br>";

$can="";

include("connect.php");

if ($_GET["REQ"] == "complete"){

if (true){
  if ($_POST["inbut"] == "$def_admin_newreg_approve") {

$date = date("Y-m-d");

  $search = array ("'<script[^>]*?>.*?</script>'si","'<[\/\!]*?[^<>]*?>'si","'&#(\d+);'e");
  $replace = array ("","","chr(\\1)");

  $firmname = ereg_replace ($search, $replace, $_POST["firmname"]);
  $firmname = addslashes($firmname);
  $business = ereg_replace ($search, $replace, $_POST["business"]);
  $business = addslashes($business);

if ($def_country_allow == "YES") {

  $city = ereg_replace ($search, $replace, $_POST["city"]);
  $city=addslashes($city);

}

  $zip = ereg_replace ($search, $replace, $_POST["zip"]);
  $zip=addslashes($zip);

  $address=ereg_replace ($search, $replace, $_POST["address"]);
  $address=addslashes($address);

  
  $phone = ereg_replace ($search, $replace, $_POST["phone"]);
  $phone=addslashes($phone);
  $fax = ereg_replace ($search, $replace, $_POST["fax"]);
  $fax=addslashes($fax);
  $manager = ereg_replace ($search, $replace, $_POST["manager"]);
  $manager =addslashes($manager);

  $mail = ereg_replace ($search, $replace, $_POST["mail"]);
  $mail=addslashes($mail);
  $www = ereg_replace ($search, $replace, $_POST["www"]);
  $www=addslashes($www);

if ($def_country_allow == "YES") {$postedcity=$city;} else {$postedcity="No city";}

if ($_POST[listing] == "A") $date_upgrade=$date; else $date_upgrade="";

mysql_query("UPDATE $db_users SET firmstate='on', flag='$_POST[listing]', 
comment='$_POST[comment]', 
firmname_".$def_admin_language."='$_POST[firmname]', 
business_".$def_admin_language."='$_POST[business]', 
city='$postedcity', 
address_".$def_admin_language."='$_POST[address]',
zip='$_POST[zip]', phone='$_POST[phone]', fax='$_POST[fax]',
login='$_POST[login]',pass='".md5($_POST[password])."',
manager_".$def_admin_language."='$_POST[manager]', 
mail='$_POST[mail]', www='$_POST[www]', date='$date', date_upgrade='$date_upgrade' where selector='$_POST[idin]'") or die (mysql_error());

$time_hour = date("H");
$time_min = date("i");
$time_sec = date("s");

$date_day = date("d");
$date_month = date("m");
$date_year = date("Y");

	$ip=$_SERVER["REMOTE_ADDR"];

$log="$time_hour:$time_min:$time_sec  $date_year/$date_month/$date_day - [$_SESSION[admin_login], $ip] - $def_admin_log_newadded $_POST[firmname]";

mysql_query("INSERT INTO $db_log (log) VALUES ('$log')") or die (mysql_error());

echo "&nbsp;$def_admin_log_newadded!</font><br>";


   $re=mysql_query("select * from $db_users where selector='$_POST[idin]'") or die (mysql_error());
   $fe=mysql_fetch_array($re);

$to = $fe["mail"];

$approved_message.= "\n Company: $fe[firmname]\n\n Date: $fe[date]\n\n $mail_footer\n\n";

// ENGLISH MAIL OUT

mail($to,$approved_subject,$approved_message,"FROM: ".$from);

$can="yes";
                       }
  else {

mysql_query("DELETE FROM $db_users where selector='$_POST[idin]'") or die (mysql_error());

$time_hour = date("H");
$time_min = date("i");
$time_sec = date("s");

$date_day = date("d");
$date_month = date("m");
$date_year = date("Y");

	$ip=$_SERVER["REMOTE_ADDR"];

$log="$time_hour:$time_min:$time_sec  $date_year/$date_month/$date_day - [$_SESSION[admin_login], $ip] - $def_admin_log_newremoved $_POST[firmname]";

mysql_query("INSERT INTO $db_log (log) VALUES ('$log')") or die (mysql_error());

echo "&nbsp;$def_admin_log_newremoved</font><br>";

$to = "$mail";

// ENGLISH MAIL OUT

mail($to,$rejected_subject,$rejected_message,"FROM: ".$from);

$can="yes";
       }                                      }
                          }
  if (($_GET["REQ"] == "auth") or ($can=="yes")) 
  {

if (true){
mysql_free_result($r);
   $r=mysql_query("select * from $db_users where firmstate='off'") or die (mysql_error());

    if (mysql_numrows($r) > 0)
     {
 $rowsc=mysql_numrows($r);
 for ($a=0;$a<$rowsc;$a++)
  {
$newfirms=mysql_numrows($r);
$f=mysql_fetch_array($r);
$f["address_".$def_admin_language]= str_replace("'","&#39;",stripslashes($f["address_".$def_admin_language]));
$f["firmname_".$def_admin_language]= str_replace("'","&#39;",stripslashes($f["firmname_".$def_admin_language]));
$f["business_".$def_admin_language]= str_replace("'","&#39;",stripslashes($f["business_".$def_admin_language]));
$f["manager_".$def_admin_language]= str_replace("'","&#39;",stripslashes($f["manager_".$def_admin_language]));

	echo "<br>",$a+1,"/$newfirms</font><br><br>";

	echo "<center><table cellpadding=2 cellspacing=0 border=0 width=\"80%\"><tr><td align=center bgColor=$def_form_header_color colspan=2 height=21><font face=verdana size=$def_form_header_fontsize color=$def_form_header_font_color>$def_admin_newreg</font></td></tr>";

	echo "<form action=firms.php?REQ=complete method=post>";

	$res=mysql_query("select  subcategory_".$def_admin_language." as subcategory  from $db_subcategory where catsubsel='$f[subcategory1]'") or die(mysql_error());
	$fe=mysql_fetch_array($res);
	mysql_free_result($res);
	$showcategory=$fe["subcategory"];
	$res=mysql_query("select category_".$def_admin_language." as category from $db_category where selector='$f[category1]'") or die(mysql_error());
	$fe=mysql_fetch_array($res);
	mysql_free_result($res);
	$showmaincategory=$fe["category"];
    echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_category: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color align=left>$showmaincategory<br>$showcategory</font></td></tr>";

/*
	$res=mysql_query("select  concat(subcategory_he,' / ',subcategory_ru,' / ',subcategory_en) as subcategory  from $db_subcategory where catsubsel='$f[subcategory2]'") or die(mysql_error());
	$fe=mysql_fetch_array($res);
	mysql_free_result($res);
	$showcategory=$fe["subcategory"];
	$res=mysql_query("select concat(category_he,' / ',category_ru,' / ',category_en) as category  from $db_category where selector='$f[category2]'") or die(mysql_error());
	$fe=mysql_fetch_array($res);
	mysql_free_result($res);
	$showmaincategory=$fe["category"];
        echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_category 2: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color align=left>$showmaincategory, $showcategory</font></td></tr>";


	$res=mysql_query("select  concat(subcategory_he,' / ',subcategory_ru,' / ',subcategory_en) as subcategory  from $db_subcategory where catsubsel='$f[subcategory3]'") or die(mysql_error());
	$fe=mysql_fetch_array($res);
	mysql_free_result($res);
	$showcategory=$fe["subcategory"];
	$res=mysql_query("select concat(category_he,' / ',category_ru,' / ',category_en) as category  from $db_category where selector='$f[category3]'") or die(mysql_error());
	$fe=mysql_fetch_array($res);
	mysql_free_result($res);
	$showmaincategory=$fe["category"];

    echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_category 3: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color align=left>$showmaincategory, $showcategory</font></td></tr>";
*/
  $ree=mysql_query("SELECT location_".$def_admin_langauage." as location FROM $db_location WHERE locationselector='$f[location]'");
  $fee=mysql_fetch_array($ree);


if ($def_country_allow == "YES") {
     echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_country: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color align=left>$fee[location]</font></td></tr>";
}
else {
     echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_city: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color align=left>$fee[location]</font></td></tr>";
}


if ($def_states_allow == "YES") {
  $ree=mysql_query("SELECT * FROM $db_states WHERE stateselector='$f[state]'");
  @$fee=mysql_fetch_array($ree);

     echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_state: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color align=left>$fee[state]</font></td></tr>";
}
     echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_premium: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color align=left><input type=radio name=listing value='A' CHECKED style=\"border:0;\"></td></tr>";
     echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_standard: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color align=left><input type=radio name=listing value='B' CHECKED style=\"border:0;\"></td></tr>";



     echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_title: <font color=red size=1>*</font></td><td bgColor=$def_form_back_color align=left><input type=text name=firmname maxlength=100 size=40 value='".$f["firmname_".$def_admin_language]."'></td></tr>";
     echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_descr: <font color=red size=1>*</font></td><td bgColor=$def_form_back_color align=left><input type=text maxlength=500 name=business size=40 value='".$f["business_".$def_admin_language]."'></td></tr>";

 if ($def_country_allow == "YES") {
 echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_city: <font color=red size=1>*</font></td><td bgColor=$def_form_back_color align=left><input type=text maxlength=100 name=city size=40 value='$f[city]'></td></tr>";
 }

	echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_address (heb): &nbsp;&nbsp;</td><td bgColor=$def_form_back_color align=left><input type=text name=address maxlength=200 size=40 value='".$f["address_".$def_admin_language]."'></td></tr>";
	echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_zip: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color align=left><input type=text name=zip maxlength=100 size=40 value='$f[zip]'></td></tr>";
	echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_phone: <font color=red size=1>*</font></td><td bgColor=$def_form_back_color align=left><input type=text name=phone  maxlength=100 size=40 value='$f[phone]'></td></tr>";
	echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_fax: <font color=red size=1>*</font></td><td bgColor=$def_form_back_color align=left><input type=text name=fax  maxlength=100 size=40 value='$f[fax]'></td></tr>";
	echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_manager: <font color=red size=1>*</font></td><td bgColor=$def_form_back_color align=left><input type=text  maxlength=100 name=manager size=40 value='".$f["manager_".$def_admin_language]."'></td></tr>";
	echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_mail: <font color=red size=1>*</font></td><td bgColor=$def_form_back_color align=left><input type=text name=mail size=40  maxlength=100 onBlur='checkemail(this.value)' value='";
	echo "$f[mail]'></td></tr>";
	echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_page: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color align=left><input type=text name=www size=40 value='$f[www]'  maxlength=100></td></tr>";
	echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_registered: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color align=left>$f[date]</font></td></tr>";
	echo "<tr><td bgColor=$def_form_back_color align=right>IP: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color align=left>$f[ip]</font></td></tr>";
	echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_login: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color align=left><input name=login size=40 value='".$f[login]."'></font></td></tr>";
	echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_password: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color align=left><input name=login size=40 value=''></font></td></tr>";
	echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_comments: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color align=left><input type=text name=comment size=40 value='$f[comment]'  maxlength=100></td></tr>";


	echo "<input type=hidden name=idin value='$f[selector]'></td><td bgColor=$def_form_back_color align=left>";
	echo "<tr><td align=middle bgColor=$def_form_header_color colspan=2>
	<input type=submit name=inbut value=\"$def_admin_newreg_approve\" >
	<input type=submit name=inbut value=\"$def_admin_newreg_remove\" border=0></td></tr>";
	echo "</form></table>";

         }	
    }
 
else {echo "<br><font face=tahoma size=2>$def_admin_nonewregs</font>";

}
	mysql_close();
	echo "</td></tr></table><br><br>";

?>

</td>
<?

 include("footer.inc");
   }
 }

?>


