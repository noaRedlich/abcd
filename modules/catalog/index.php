<?
 require("defaults.php");
 require("../catalog_admin/connect.php");

 session_start();
 require_once($GO_CONFIG->class_path.'users.class.inc');
 $users = new users();
 $userID = $GO_SECURITY->user_id;
 $user = $users->get_user($userID);
 $_SESSION["login"] = $user[username];
 
 $r=mysql_query("select * from $db_users where login='$_SESSION[login]'");
 $f=mysql_fetch_array($r);
 $pass=md5($f[password]);
 $date = date("Y-m-d");
 if (mysql_num_rows($r)==0){
 	mysql_query("insert into $db_users 
		(firmstate,login,pass,firmname_he,state,city,address_he,zip,phone,fax,cellular,manager_he,mail,www,prices,flag,date)
		select 'on',username,'$pass',company,work_state,work_city,work_address,work_zip,work_phone,fax,cellular,name,email,homepage,10000,'B','$date'  
		from users where id=$userID") or die(mysql_error());
 }


 
if(true)
  {

   session_register("login");
   session_register("pass");
   session_register("ident");
   session_register("auth");

  // $_SESSION['login']=$_POST['login'];
 //  $_SESSION['pass']=md5($_POST['pass']);
  // $_SESSION['ident']=time();

   $ip=$_SERVER["REMOTE_ADDR"];

   $_SESSION['auth'].=$ip;
  }


// CHANGED

 if (($_GET["REQ"] == "authorize") and ($_POST["changed"] == "true"))
  {

  $search = array ("'<script[^>]*?>.*?</script>'si","'<[\/\!]*?[^<>]*?>'si","'&#(\d+);'e");
  $replace = array ("","","chr(\\1)");

  $firmname = ereg_replace ($search, $replace, $_POST["firmname"]);
  $firmname=ereg_replace("'","&quot;",$firmname);
  $business = ereg_replace ($search, $replace, $_POST["business"]);
  $business=ereg_replace("'","&quot;",$business);

if ($def_country_allow == "YES") {

  $city = ereg_replace ($search, $replace, $_POST["city"]);
  $city=ereg_replace("'","&quot;",$city);

}

  $zip = ereg_replace ($search, $replace, $_POST["zip"]);
  $zip=ereg_replace("'","&quot;",$zip);

  $address = ereg_replace ($search, $replace, $_POST["address"]);
  $address=ereg_replace("'","&quot;",$address);
  $phone = ereg_replace ($search, $replace, $_POST["phone"]);
  $phone=ereg_replace("'","&quot;",$phone);
  $fax = ereg_replace ($search, $replace, $_POST["fax"]);
  $fax=ereg_replace("'","&quot;",$fax);
  $cellular = ereg_replace ($search, $replace, $_POST["cellular"]);
  $cellular=ereg_replace("'","&quot;",$cellular);
  $manager = ereg_replace ($search, $replace, $_POST["manager"]);
  $manager=ereg_replace("'","&quot;",$manager);
  $mail = ereg_replace ($search, $replace, $_POST["mail"]);
  $mail=ereg_replace("'","&quot;",$mail);
  $www = ereg_replace ($search, $replace, $_POST["www"]);
  $www=ereg_replace("'","&quot;",$www);

  $r=mysql_query("select * from $db_users where login='$_SESSION[login]'");
  $f=mysql_fetch_array($r);

    $ip=$_SERVER["REMOTE_ADDR"];
    //if ($_SESSION['auth'] <> $ip) {echo "Stolen session!";exit();}

  if (($f["login"] == $_SESSION["login"]) and (mysql_numrows($r) == '1')) {

  if ($_POST["www"] <> "") {if(ereg("http://",$_POST["www"])) {} else {$www="http://$_POST[www]";}}

  if ($_POST["curblock"] == ""){$err=1;echo "<font face=verdana size=2 color=red><b>Error: $def_reg_err_cat</b></font>";}

 $curr = explode("::", $_POST["curblock"]); 
 $curblock1=$curr[0];
 $curblock2=$curr[1];


	if ($_POST["curblock2"] == "")

		{
		 $curblock21="0";
		 $curblock22="0";
		}

	else

		{
		 $curr2 = explode("::", $_POST["curblock2"]); 
		 $curblock21=$curr2[0];
		 $curblock22=$curr2[1];
		}



	if ($_POST["curblock3"] == "")

		{
		 $curblock31="0";
		 $curblock32="0";
		}

	else

		{
		 $curr3 = explode("::", $_POST["curblock3"]); 
		 $curblock31=$curr3[0];
		 $curblock32=$curr3[1];
		}


 if ((($curblock1 == $curblock21) and ($curblock2 == $curblock22)) and ($curblock1 <> "0")) {$err=1;echo "<b>Error:</b> $def_reg_err_cat_duplicate<br><br><a href=javascript:history.back()>$def_backtoregistration</a></font>";}

 if ((($curblock1 == $curblock31) and ($curblock2 == $curblock32)) and ($curblock1 <> "0")) {$err=1;echo "<b>Error:</b> $def_reg_err_cat_duplicate<br><br><a href=javascript:history.back()>$def_backtoregistration</a></font>";}

 if ((($curblock21 == $curblock31) and ($curblock22 == $curblock32)) and ($curblock21 <> "0")) {$err=1;echo "<b>Error:</b> $def_reg_err_cat_duplicate<br><br><a href=javascript:history.back()>$def_backtoregistration</a></font>";}

  if (trim($firmname) == "") {$err=1;echo "<font face=tahoma size=2 color=red><b>&nbsp;$def_specify $def_company</b></font>";}
  if (trim($business) == "") {$err=1;echo "<font face=tahoma size=2 color=red><b>&nbsp;$def_specify $def_description</b></font>";}
  if ($phone == "") {$err=1;echo "<font face=tahoma size=2 color=red><b>&nbsp;$def_specify  $def_phone</b></font>";}
  if (($def_country_allow == "YES") and ($city == "")) {$err=1;echo "<font face=tahoma size=2 color=red><b>&nbsp;$def_specify  $def_city</b></font>";}
  //if (trim($manager_he.$manager_en.$manager_ru) == "") {$err=1;echo "<font face=tahoma size=2 color=red><b>&nbsp;$def_specify  $def_manager</b></font>";}
  if ($mail == "") {$err=1;echo "<font face=tahoma size=2 color=red><b>&nbsp;$def_specify $def_email</b></font>";}

  $r=mysql_query("SELECT selector FROM $db_users WHERE login!='$_SESSION[login]' and mail='$_POST[mail]'");
  $mails=mysql_numrows($r);
  mysql_free_result($r);

  if ($mails > 1) {$err=1;echo "<font face=tahoma size=2 color=red><b>&nbsp;$def_reg_mail_used</b></font>";}



  $ip=$_SERVER["REMOTE_ADDR"];



 $curr = explode("::", $_POST["curblock"]); 
 $curblock1=$curr[0];
 $curblock2=$curr[1];


	if ($_POST["curblock2"] == "")

		{
		 $curblock21="0";
		 $curblock22="0";
		}

	else

		{
		 $curr2 = explode("::", $_POST["curblock2"]); 
		 $curblock21=$curr2[0];
		 $curblock22=$curr2[1];
		}



	if ($_POST["curblock3"] == "")

		{
		 $curblock31="0";
		 $curblock32="0";
		}

	else

		{
		 $curr3 = explode("::", $_POST["curblock3"]); 
		 $curblock31=$curr3[0];
		 $curblock32=$curr3[1];
		}

  // If user have changed the category, we also change the main category in his price lines

if (!$err){
	  //if ($curr[0] <> $f[category1]) {mysql_query("UPDATE $db_offers SET firmcategory1='$curr[0]' where firmselector='$f[selector]'") or die ("mySQL error1!");}
	  //if ($curr2[0] <> $f[category2]) {mysql_query("UPDATE $db_offers SET firmcategory2='$curr2[0]' where firmselector='$f[selector]'") or die ("mySQL error2!");}
	  //if ($curr3[0] <> $f[category3]) {mysql_query("UPDATE $db_offers SET firmcategory3='$curr3[0]' where firmselector='$f[selector]'") or die ("mySQL error3!");}
	

	
	$date = date("Y-m-d");
	
	if ($def_country_allow == "YES") {$location=$_POST["country"]; $postedcity=$city;} else {$location=$_POST["city2"]; $postedcity="";}
	if ($def_states_allow == "YES") {$state=$_POST["state"];}
	$servicearea = implode($servicearea,",");
	  mysql_query("UPDATE $db_users SET category1=$curblock1, category2=$curblock21, category3=$curblock31, subcategory1=$curblock2, subcategory2=$curblock22, subcategory3=$curblock32, 
	  firmname_".$def_language."='$firmname', 
	  business_".$def_language."='$business',
	  keyword_".$def_language."='$keyword', 
	  location='$location', servicearea='$servicearea', state='$state', city='$postedcity', 
	  address_".$def_language."='$address',  
	  zip='$zip', phone='$phone', fax='$fax', cellular = '$cellular',
	  manager_".$def_language."='$manager', 
	  mail='$mail', www='$www',  date_update='$date', ip_update='$ip' where login='$_SESSION[login]'") or die (mysql_error());
}

}}

 $incomingline="$home";

 require("../catalog_admin/header.inc");

// ENTER
 if (  $REQ!="change") {

  $r=mysql_query("select * from $db_users where login='$_SESSION[login]'") or die ("mySQL error5!");
  $f=mysql_fetch_array($r);
    $ip=$_SERVER["REMOTE_ADDR"];
    //if ($_SESSION['auth'] <> $ip) {echo "Stolen session!";exit();}

  if (($f["login"] == $_SESSION["login"]) and (mysql_numrows($r) == '1')) {

  if ($f["firmstate"]=="off") {$error="$def_notreviewedyet";}

else {

if ($_POST["do"] == "uploaded") {

if ($_POST["mode"] == "logo") {$picdir="logo";} else {$picdir="banner";}

if ($HTTP_POST_FILES['img1']['tmp_name']) {

chmod ($HTTP_POST_FILES['img1']['tmp_name'], 0777) or $uploaded="<font color=red>$def_banner_error</font><br>";

$size=Getimagesize($HTTP_POST_FILES['img1']['tmp_name']);
$filesize=filesize($HTTP_POST_FILES['img1']['tmp_name']);


if ($_POST["mode"] == "logo") {

$max_width=$def_logo_width;
$max_height=$def_logo_height;
$max_size=$def_logo_size;

}

else {

$max_width=$def_banner_width;
$max_height=$def_banner_height;
$max_size=$def_banner_size;

}

if ((($size[0] <= $max_width) and ($size[1] <= $max_height) and ($filesize < $max_size) and ($size[2] <> 4)) and (($size[2] == 1) or ($size[2] == 2) or ($size[2] == 3) or ($size[2] == 6)))
{

if ($size[2]==1) $type="gif";
if ($size[2]==2) $type="jpg";
if ($size[2]==3) $type="png";
if ($size[2]==6) $type="bmp";

@unlink("../../catalog/$picdir/$f[selector].gif");
@unlink("../../catalog/$picdir/$f[selector].bmp");
@unlink("../../catalog/$picdir/$f[selector].jpg");
@unlink("../../catalog/$picdir/$f[selector].png");

copy($HTTP_POST_FILES['img1']['tmp_name'], "../../catalog/$picdir/$f[selector].$type") or $uploaded="<font color=red>$def_banner_error</font><br>";

chmod ("../../catalog/$picdir/$f[selector].$type", 0777) or $uploaded="<font color=red>$def_banner_error</font><br>";

$uploaded="<font color=green>$def_banner_ok $size[0]x$size[1], $type</font><br>";

}               

else {

if ($_POST["mode"] == "logo") $uploaded="<font color=red>$def_banner_error ($def_logo_width x $def_logo_height) @ $def_logo_size Bytes</font><br>";
else $uploaded="<font color=red>$def_banner_error ($def_banner_width x $def_banner_height) @ $def_banner_size Bytes</font><br>";

}


} else {

$uploaded="<font color=red>$def_specify_file</font><br>";

}
}


?>
        <?echo str_replace("1%","20%","$help_header");?>
        <?echo "$user_help_1";?>
        <?echo"$help_footer";?>
  </td>

 <td valign=top align=center width="60%" bgcolor=<? echo"$def_background";?>>
<? if ($def_banner_allowed == "YES") { ?>

 <table cellpadding=0 cellspacing=0 border=0 width="100%">

 <tr>
  <td valign=center align=center width=100%>
<? //require("banner.inc"); ?>
<?
 // If logo was uploaded by this $id we show it
 $logoshandle = opendir('../../catalog/logo'); 

	$count2=0;

	while (false !== ($file1 = readdir($logoshandle))) { 
	    if ($file1 != "." && $file1 != "..") { 
	        $logo1[$count2]="$file1";
	   $count2++;
		} 
	}
 closedir($logoshandle); 
	for ($rot=0; $rot<count($logo1); $rot++)
	{
	$relogo = explode(".", $logo1[$rot]); 
	
	if ($relogo[0] == $f["selector"]) 
	 {

	echo "<br><img src=../../catalog/logo/$relogo[0].$relogo[1]?".rand(0,10000)."><br><br>$uploaded";
	
	 }

	}
	?>
  </td>
 </tr>

</table>

<? } ?>


 <div align="left">

<?

  $date_day = date("d");
  $date_month = date("m");
  $date_year = date("Y");

  list($on_year, $on_month, $on_day) = split ('[/.-]', $f["date_upgrade"]);

  $first_date = mktime(0,0,0,$on_month,$on_day,$on_year); 
  $second_date = mktime(0,0,0,$date_month,$date_day,$date_year);

  if($second_date > $first_date){ $days = $second_date-$first_date; } 
   else{ $days = $first_date-$second_date; } 

  $current_result = $def_paypal_expiration-(($days)/(60*60*24)); 

  if ($f["prices"] > '0') {

$offer_button="<td valign=top><form action=edoffers.php method=post><input type=hidden name=foot value=2>&nbsp;<input type=submit value=\"$def_offers\" border=0>&nbsp;</form></td>";

}

echo "<br></center><table cellspacing=0 cellpadding=0 border=0><tr><td align=left valign=top><form action=index.php?REQ=change method=post>&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit value=\"$def_change\" border=0></form></td>$offer_button";

$inv_date=date("YmdHis");

if ((($def_paypal_allowed == "YES") and ($f["flag"] == "B")) or (($def_paypal_allowed == "YES") and ($current_result <= $def_paypal_expiration_warning))) {

echo "

<td valign=top><form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\">
<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">
<input type=\"hidden\" name=\"business\" value=$def_paypal_email>
<input type=\"hidden\" name=\"return\" value=$def_return_url>
<input type=\"hidden\" name=\"notify_url\" value=$def_return_url>
<input type=\"hidden\" name=\"cancel_return\" value=$def_cancel_return> 

<input type=\"hidden\" name=\"invoice\" value=\"$inv_date\">

<input type=\"hidden\" name=\"custom\" value=\"$f[login]\">

<input type=\"hidden\" name=\"item_name\" value=\"$def_paypal_item ($f[login])\">
<input type=\"hidden\" name=\"quantity\" value=\"1\">
<input type=\"hidden\" name=\"amount\" value=\"$def_paypal_amount\">
<input type=\"hidden\" name=\"no_shipping\" value=\"0\">
<input type=submit Value=\"$def_paypal_button\">
</form></td>

";
}

echo "<td valign=top></td></tr></table>";

echo "</div><table cellspacing=1 cellpadding=2 border=0 width=97%>";

  echo "<tr><td align=center height=21 valign=middle width=100% colspan=2 bgcolor=$def_form_header_color><font face=verdana size=$def_form_header_fontsize color=$def_form_header_font_color>$def_user</font></td></tr>";
  echo "<tr><td  valign=middle width=30% bgcolor=$def_form_back_color>$def_company:</td><td  valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;".$f["firmname_".$def_language]."</td></tr>";

if ($f["flag"] == "A") $listingtype="$def_premium";
if ($f["flag"] == "B") $listingtype="$def_standard";

  echo "<tr><td  valign=middle width=30% bgcolor=$def_form_back_color>$def_listing:</td><td valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$listingtype</td></tr>";

  $res=mysql_query("select catsubsel,catsel,subcategory_".$def_language." as subcategory from $db_subcategory where catsubsel='$f[subcategory1]'") or die("mySQL error6!");
  $fe=mysql_fetch_array($res);
  mysql_free_result($res);
  $showcategory1=$fe["subcategory"];

  $res=mysql_query("select selector,category_".$def_language." as category from $db_category where selector='$f[category1]'") or die("mySQL error7!");
  $fe=mysql_fetch_array($res);
  mysql_free_result($res);
  $showmaincategory1=$fe["category"];


  $res=mysql_query("select catsubsel,catsel,subcategory_".$def_language." as subcategory from $db_subcategory where catsubsel='$f[subcategory2]'") or die("mySQL error8!");
  $fe=mysql_fetch_array($res);
  mysql_free_result($res);
  $showcategory2=$fe["subcategory"];

  $res=mysql_query("select selector,category_".$def_language." as category from $db_category where selector='$f[category2]'") or die("mySQL error9!");
  $fe=mysql_fetch_array($res);
  mysql_free_result($res);
  $showmaincategory2=$fe["category"];


  $res=mysql_query("select catsubsel,catsel,subcategory_".$def_language." as subcategory from $db_subcategory where catsubsel='$f[subcategory3]'") or die("mySQL error10!");
  $fe=mysql_fetch_array($res);
  mysql_free_result($res);
  $showcategory3=$fe["subcategory"];

  $res=mysql_query("select selector,category_".$def_language." as category from $db_category where selector='$f[category3]'") or die("mySQL error11!");
  $fe=mysql_fetch_array($res);
  mysql_free_result($res);
  $showmaincategory3=$fe["category"];

 if ($f[category1] <> 0) echo "<tr><td align=right valign=middle width=30% bgcolor=$def_form_back_color>$def_category:</td><td  valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$showmaincategory1, $showcategory1</td></tr>";
 //if ($f[category2] <> 0) echo "<tr><td align=right valign=middle width=30% bgcolor=$def_form_back_color>$def_category 2:</td><td align=left valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$showmaincategory2, $showcategory2</td></tr>";
 //if ($f[category3] <> 0) echo "<tr><td align=right valign=middle width=30% bgcolor=$def_form_back_color>$def_category 3:</td><td align=left valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$showmaincategory3, $showcategory3</td></tr>";

  echo "<tr><td valign=middle width=30% bgcolor=$def_form_back_color>$def_description:</td><td valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;".$f["business_".$def_language]."</td></tr>";
  echo "<tr><td  valign=middle width=30% bgcolor=$def_form_back_color>$def_offers_keyword:</td><td valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;".$f["keyword_".$def_language]."</td></tr>";

  $re1=mysql_query("SELECT locationselector,location_he as location FROM $db_location WHERE locationselector='$f[location]'");
  $fe1=mysql_fetch_array($re1);

if ($def_country_allow == "YES")
{
  echo "<tr><td  valign=middle width=30% bgcolor=$def_form_back_color>$def_country:</td><td  valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$fe1[location]</td></tr>";
}

else

{
  echo "<tr><td  valign=middle width=30% bgcolor=$def_form_back_color>$def_city:</td><td  valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$fe1[location]</td></tr>";
}

if ($def_states_allow == "YES")
{
  $ree=mysql_query("SELECT * FROM $db_states WHERE stateselector='$f[state]'");
  $fee=mysql_fetch_array($ree);

  echo "<tr><td  valign=middle width=30% bgcolor=$def_form_back_color>$def_state:</td><td valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$fee[state]</td></tr>";
}

if ($def_country_allow == "YES") {
  echo "<tr><td  valign=middle width=30% bgcolor=$def_form_back_color>$def_city:</td><td valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$f[city]</td></tr>";
}
  echo "<tr><td  valign=middle width=30% bgcolor=$def_form_back_color>$def_address:</td><td valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;".$f["address_".$def_language]."</td></tr>";
  echo "<tr><td  valign=middle width=30% bgcolor=$def_form_back_color>$def_zip:</td><td valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$f[zip]</td></tr>";
  echo "<tr><td  valign=middle width=30% bgcolor=$def_form_back_color>$def_phone:</td><td  valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$f[phone]</td></tr>";
  echo "<tr><td  valign=middle width=30% bgcolor=$def_form_back_color>$def_fax:</td><td  valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$f[fax]</td></tr>";
  echo "<tr><td  valign=middle width=30% bgcolor=$def_form_back_color>$def_cellular:</td><td  valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$f[cellular]</td></tr>";
  echo "<tr><td  valign=middle width=30% bgcolor=$def_form_back_color>$def_manager:</td><td  valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;".$f["manager_".$def_language]."</td></tr>";
  echo "<tr><td  valign=middle width=30% bgcolor=$def_form_back_color>$def_email:</td><td valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;<a href=mailto:$f[mail]>$f[mail]</a></td></tr>";
  echo "<tr><td  valign=middle width=30% bgcolor=$def_form_back_color>$def_webpage:</td><td valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;<a href=$f[www] target=\"_blank\">$f[www]</a></td></tr>";

  echo "<tr><td align=center valign=middle height=21 width=97% colspan=2 bgcolor=$def_form_header_color><font face=verdana size=$def_form_header_fontsize color=$def_form_header_font_color>$def_offers</font></td></tr>";

  $res=mysql_query("select num from $db_offers where firmselector='$f[selector]'");
  @$results_amount=mysql_numrows($res);
  @mysql_free_result($res);
  $free_offers=$f["prices"]-$results_amount;

  $res=mysql_query("select num from $db_offers where firmselector='$f[selector]' and sale=1");
  @$sales_amount=mysql_numrows($res);
  @mysql_free_result($res);
  $free_sales=$f["sales"]-$sales_amount;
  
  
 if ($f["prices"] > "0")
  {
   echo "<tr><td align=right valign=middle width=30% bgcolor=$def_form_back_color>$def_offers:</td><td  valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$f[prices] $def_offers, $results_amount $def_used, $free_offers $def_free.</td></tr>";
  }
else
  {
   echo "<tr><td colspan=2  valign=middle width=97% bgcolor=$def_form_back_color><center>&nbsp;$def_offers_no</center></td></tr>";
  }
 if ($f["sales"] > "0")
  {
   echo "<tr><td align=right valign=middle width=30% bgcolor=$def_form_back_color>$def_sales:</td><td  valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$f[sales] $def_sales, $sales_amount $def_used, $free_sales $def_free.</td></tr>";
  }
else
  {
   echo "<tr><td colspan=2  valign=middle width=97% bgcolor=$def_form_back_color><center>&nbsp;$def_sales_no</center></td></tr>";
  }
  
  
@ $bannerctr=$f["banner_click"]*100/$f["banner_show"];
@ $bannerctr=round($bannerctr,2);

  echo "<tr><td align=center valign=middle height=21 width=97% colspan=2 bgcolor=$def_form_header_color><font face=verdana size=$def_form_header_fontsize color=$def_form_header_font_color>$def_statistics</font></td></tr>";
  // echo "<tr><td align=right valign=middle width=30% bgcolor=$def_form_back_color>$def_banner_exposures:</td><td align=left valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$f[banner_show]</td></tr>";
  // echo "<tr><td align=right valign=middle width=30% bgcolor=$def_form_back_color>$def_banner_clicks:</td><td align=left valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$f[banner_click]</td></tr>";
  // echo "<tr><td align=right valign=middle width=30% bgcolor=$def_form_back_color>$def_banner_CTR:</td><td align=left valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$bannerctr%</td></tr>";
  echo "<tr><td align=right valign=middle width=30% bgcolor=$def_form_back_color>$def_visitors:</td><td  valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$f[counter]</td></tr>";


if ($def_rating_allowed == "YES") {

unset($rating_listing);

if (($def_rating_allowed == "YES") and ($f[countrating] > 0) and ($f[votes] > 0)) {
$rating_listing="";
for ($rate=0;$rate<$f[countrating];$rate++) {
$rating_listing.="<img src=./img/star.gif border=0>";
}

$rating_listing.=" <font color=$def_cat_font_color_empty>($f[votes] $def_votes)</font>";

  echo "<tr><td align=right valign=middle width=30% bgcolor=$def_form_back_color>$def_rating:</td><td valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$rating_listing</td></tr>";
}}

//  echo "<tr><td align=right valign=middle width=30% bgcolor=$def_form_back_color>$def_offers_reviews:</td><td align=left valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$f[price_show]</td></tr>";

 $regfromip=gethostbyaddr($f[ip]);
  echo "<tr><td align=right valign=middle width=30% bgcolor=$def_form_back_color>$def_record_added:</td><td valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$f[date] $def_from $regfromip ($f[ip])</td></tr>";

if (!empty($f[date_update]))
{
$updatefromip=gethostbyaddr($f[ip_update]);
echo "<tr><td align=right valign=middle width=30% bgcolor=$def_form_back_color>$def_record_update:</td><td  valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$f[date_update] $def_from $updatefromip ($f[ip_update])</td></tr>";
}

if ($f[flag] == "A")
{
//echo "<tr><td align=right valign=middle width=30% bgcolor=$def_form_back_color>$def_upgrade:</td><td align=left valign=middle width=70% bgcolor=$def_form_back_color>&nbsp;$f[date_upgrade] ($current_result days left)</td></tr>";
}

if ($def_banner_allowed == "YES") {

//NO BANNERS
if (false && (($f[flag] == "A")) or ((($f[flag] == "B") and ($def_standard_banner_allow == "YES"))))

{

?>
<tr><td align=right valign=middle width=100% bgcolor=<? echo"$def_form_back_color";?> colspan=2>
<FORM ACTION="index.php?REQ=authorize" METHOD="POST" ENCTYPE="multipart/form-data">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" WIDTH="100%">
   <?if (false){?>
	<TR>
		<TD HEIGHT="21" BGCOLOR="<? echo "$def_form_header_color"; ?>" align=center valign=center>
			<FONT size=<? echo"$def_form_header_fontsize";?> color=<? echo "$def_form_header_font_color";?>><? echo"$def_banner_upload";?></FONT>
		</TD>
	</TR>
	<TR>
		<TD BGCOLOR="ffffff" align=center>


<?

$banhandle = opendir('../../catalog/banner'); 

$bancount=0;
if ($banhandle){
while (false !== ($banfile = readdir($banhandle))) { 
    if ($banfile != "." && $banfile != "..") { 
        $banbanner[$bancount]="$banfile";
   $bancount++;
    } 
}
closedir($banhandle); 
}
for ($aaa=0;$aaa<count($banbanner);$aaa++)
{
$banrbanner = explode(".", $banbanner[$aaa]); 

}

?>


           <? if ((isset($uploaded) and ($_POST["mode"] != "logo"))) {echo " <b>$uploaded</b> ";}?>
			<INPUT TYPE="file" NAME="img1" SIZE="25">&nbsp;<input type=hidden name=step value="back"><input type=hidden name=do value="uploaded"><INPUT TYPE="SUBMIT" NAME="Submit" VALUE="<? echo "$def_upload";?>">
		</TD>
	</TR>
	<?}?>	
	
</TABLE>
</td></tr>

</FORM>

<? }}

if ((($f[flag] == "A")) or ((($f[flag] == "B") and ($def_standard_logo_allow == "YES"))))

{
 ?>

<tr><td align=right valign=middle width=100% bgcolor=<? echo"$def_form_back_color";?> colspan=2>
<FORM ACTION="index.php?REQ=authorize" METHOD="POST" ENCTYPE="multipart/form-data">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" WIDTH="100%">
	<TR>
		<TD HEIGHT="21" COLSPAN="3" BGCOLOR="<? echo "$def_form_header_color"; ?>" align=center>
			<FONT FACE="verdana" size=<? echo"$def_form_header_fontsize";?> color=<? echo "$def_form_header_font_color";?>><? echo "$def_logo_upload";?></FONT>
		</TD>
	</TR>
	<TR>
		<TD BGCOLOR="ffffff" align=center>

<?

$handle = opendir('../../catalog/logo'); 

$count=0;
if ($handle){
while (false !== ($file = readdir($handle))) { 
    if ($file != "." && $file != "..") { 
        $logo[$count]="$file";
   $count++;
    } 
}
closedir($handle); 
}
for ($xx=0;$xx<count($logo);$xx++)
{
$rlogo = explode(".", $logo[$xx]); 

}
?>

                        <? if ((isset($uploaded) and ($_POST["mode"] == "logo"))) {echo "<b>$uploaded</b>";}?>
			<INPUT TYPE="file" NAME="img1" SIZE="25">&nbsp;<input type=hidden name=step value="back"><input type=hidden name=mode value="logo"><input type=hidden name=do value="uploaded"><INPUT TYPE="SUBMIT" NAME="Submit" VALUE="<? echo "$def_upload";?>">
		</TD>
	</TR>
</TABLE>
</td></tr>
</FORM>

<?
}
  echo "</table><br>";

  mysql_close();

  require("footer.inc");
  exit();
 }
}
 else {
  $error="$def_login_error";
      }
  }

if ($_GET["REQ"] == "change") 
  {

  session_start();

  $r=mysql_query("select * from $db_users where login='$_SESSION[login]'");
  $f=mysql_fetch_array($r);

    $ip=$_SERVER["REMOTE_ADDR"];
    //if ($_SESSION['auth'] <> $ip) {echo "Stolen session!";exit();}

  if (($f["login"] == $_SESSION["login"]) and (mysql_numrows($r) == '1')) {
  if ($f["firmstate"]=="off") {echo "<img src=./img/pointer.gif>&nbsp;$def_notreviewedyet<br><br>";}
  else {


echo str_replace("1%","20%","$help_header");
echo"$user_help_2";
echo"$help_footer";

?>
  </td>

 <td valign=top align=center width="60%" bgcolor=<? echo"$def_background";?>>

<? if ($def_banner_allowed == "YES") { ?>

 <table cellpadding=0 cellspacing=0 border=0 width="100%">

 <tr>
  <td valign=center align=center width=100%>
<? //require("banner.inc"); ?>
  </td>
 </tr>

</table>

 <? } ?>

<?

  echo "<div align=left><form action=index.php?REQ=authorize method=post><input type=hidden name=step value=\"back\">&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit border=0 value='$def_back'></form></div>"; 
  echo "<center><table cellpadding=1 cellspacing=1 border=0 width=97%><tr><td colspan=2 align=center bgColor=$def_form_header_color height=21><font face=verdana size=$def_form_header_fontsize color=$def_form_header_font_color>$def_edit</font></td></tr>";
  echo "<form action=index.php?REQ=authorize method=post>";
  echo "<tr><td bgColor=$def_form_back_color align=right>$def_company: </td><td><input type=text name=firmname maxlength=100 size=50 value='".$f["firmname_".$def_language]."'></td></tr>";
  echo "<tr><td bgColor=$def_form_back_color align=right>$def_description: </td><td><input type=text maxlength=500 name=business size=50 value='".$f["business_".$def_language]."'></td></tr>";
  echo "<tr><td bgColor=$def_form_back_color align=right>$def_offers_keyword: </td><td><input type=text maxlength=500 name=keyword size=50 value='".$f["keyword_".$def_language]."'></td></tr>";



 echo "<tr><td bgColor=$def_form_back_color align=right>$def_defaultcategory: <font color=red>*</font></td><td><SELECT NAME=curblock style=\"width:334;\">";
 $rr=mysql_query("select selector,category_".$def_language." as category from $db_category order by category");
 $results_amount=mysql_numrows($rr);

 echo "<OPTION VALUE=\"\">$def_sel_cat";

 for($i=0;$i<$results_amount;$i++)
  {
   $fr=mysql_fetch_array($rr);

    $ra=mysql_query("select catsubsel,catsel,subcategory_".$def_language." as subcategory from $db_subcategory where catsel=$fr[selector] order by subcategory");
    $results_amount2=mysql_numrows($ra);

    for($j=0;$j<$results_amount2;$j++)
    {
    $fa=mysql_fetch_array($ra);
    if (($f["category1"] == $fr["selector"]) and ($f["subcategory1"] == $fa["catsubsel"])) 
		echo "<OPTION VALUE=\"$fr[selector]::$fa[catsubsel]\" SELECTED>$fr[category] :: $fa[subcategory]\n";
    else 
		echo "<OPTION VALUE=\"$fr[selector]::$fa[catsubsel]\">$fr[category] :: $fa[subcategory]";

    }
    echo "<OPTION VALUE=\"\">--";
  }
  echo "</SELECT></td></tr>";



/*
 echo "<tr><td bgColor=$def_form_back_color align=right>$def_category 2: &nbsp;&nbsp;<SELECT NAME=curblock2 style=\"width:316;\">";
                                                                                                                                                                                                                                                                                             

 require("../catalog_admin/connect.php");
 $rr=mysql_query("select selector,category_en as category from $db_category order by category");
 $results_amount=mysql_numrows($rr);

 echo "<OPTION VALUE=\"\">$def_sel_cat";

 for($i=0;$i<$results_amount;$i++)
  {
   $fr=mysql_fetch_array($rr);

    $ra=mysql_query("select catsubsel,catsel,subcategory_en as subcategory from $db_subcategory where catsel=$fr[selector] order by subcategory");
    $results_amount2=mysql_numrows($ra);

    for($j=0;$j<$results_amount2;$j++)
    {
    $fa=mysql_fetch_array($ra);
    if (($f["category2"] == $fr["selector"]) and ($f["subcategory2"] == $fa["catsubsel"])) echo "<OPTION VALUE=\"$fr[selector]::$fa[catsubsel]\" SELECTED>$fr[category] :: $fa[subcategory]\n";
    else echo "<OPTION VALUE=\"$fr[selector]::$fa[catsubsel]\">$fr[category] :: $fa[subcategory]";

    }
    echo "<OPTION VALUE=\"\">--";
  }
  mysql_free_result($ra);
  echo "</SELECT></td></tr>";



 echo "<tr><td bgColor=$def_form_back_color align=right>$def_category 3: &nbsp;&nbsp;<SELECT NAME=curblock3 style=\"width:316;\">";
                                                                                                                                                                                                                                                                                             

 require("../catalog_admin/connect.php");
 $rr=mysql_query("select selector,category_en as category from $db_category order by category");
 $results_amount=mysql_numrows($rr);

 echo "<OPTION VALUE=\"\">$def_sel_cat";

 for($i=0;$i<$results_amount;$i++)
  {
   $fr=mysql_fetch_array($rr);

    $ra=mysql_query("select catsubsel,catsel,subcategory_en as subcategory from $db_subcategory where catsel=$fr[selector] order by subcategory");
    $results_amount2=mysql_numrows($ra);

    for($j=0;$j<$results_amount2;$j++)
    {
    $fa=mysql_fetch_array($ra);
    if (($f["category3"] == $fr["selector"]) and ($f["subcategory3"] == $fa["catsubsel"])) echo "<OPTION VALUE=\"$fr[selector]::$fa[catsubsel]\" SELECTED>$fr[category] :: $fa[subcategory]\n";
    else echo "<OPTION VALUE=\"$fr[selector]::$fa[catsubsel]\">$fr[category] :: $fa[subcategory]";

    }
    echo "<OPTION VALUE=\"\">--";
  }
  mysql_free_result($ra);
  echo "</SELECT></td></tr>";

*/

if ($def_country_allow == "YES") {

  echo "<tr><td align=right valign=middle bgcolor=$def_form_back_color>$def_country: <font color=red>*</font></td><td><SELECT NAME=\"country\" style=\"width:334;\">";

  $re=mysql_query("SELECT locationselector,location_".$def_language." as location FROM $db_location order by location");
  $results_amount=mysql_numrows($re);

  for($i=0;$i<$results_amount;$i++)
  {
    $fa=mysql_fetch_array($re);

    if ($f["location"] == $fa["locationselector"])
     {
       echo "<OPTION VALUE=\"$fa[locationselector]\" SELECTED>$fa[location]";
     }
  else
   {
    echo "<OPTION VALUE=\"$fa[locationselector]\">$fa[location]";
   }
  }

  mysql_free_result($re);
  echo "</SELECT></td></tr>";
}

else
{

  echo "<tr><td align=right valign=middle bgcolor=$def_form_back_color>$def_city: <font color=red>*</font></td><td><SELECT NAME=\"city2\" style=\"width:334;\">";

  $re=mysql_query("SELECT locationselector,location_".$def_language." as location FROM $db_location order by location");
  $results_amount=mysql_numrows($re);

  for($i=0;$i<$results_amount;$i++)
  {
    $fa=mysql_fetch_array($re);

    if ($f["location"] == $fa["locationselector"])
     {
       echo "<OPTION VALUE=\"$fa[locationselector]\" SELECTED>$fa[location]";
     }
  else
   {
    echo "<OPTION VALUE=\"$fa[locationselector]\">$fa[location]";
   }
  }

  echo "</SELECT></td></tr>";
  echo "<tr><td align=right valign=top bgcolor=$def_form_back_color>$def_servicearea: </td><td>
  <SELECT NAME=\"servicearea[]\" MULTIPLE SIZE=5 style=\"width:334;\">";

  $re=mysql_query("SELECT locationselector,location_".$def_language." as location FROM $db_location order by location");
  $results_amount=mysql_numrows($re);

  if (strpos(" ,".$f["servicearea"].",",",0,"))
  {
	 echo "<OPTION VALUE=\"0\" SELECTED>$def_all";
  }
  else
  {
	 echo "<OPTION VALUE=\"0\"  >$def_all";
  }
	  
  for($i=0;$i<$results_amount;$i++)
  {
    $fa=mysql_fetch_array($re);

    if (strpos(" ,".$f["servicearea"].",",",".$fa["locationselector"].","))
     {
       	echo "<OPTION VALUE=\"$fa[locationselector]\" SELECTED>$fa[location]";
     }
  	else
   	{
    	echo "<OPTION VALUE=\"$fa[locationselector]\"  >$fa[location]";
   	}
  }

  mysql_free_result($re);
  echo "</SELECT></td></tr>";  
}


if ($def_states_allow == "YES") {

  echo "<tr><td align=right valign=middle bgcolor=$def_form_back_color>$def_state: <font color=red>*</font><SELECT NAME=\"state\" style=\"width:316;\">";

  $re=mysql_query("select * from $db_states order by state");
  $results_amount=mysql_numrows($re);

  for($i=0;$i<$results_amount;$i++)
  {
    $fa=mysql_fetch_array($re);

    if ($f["state"] == $fa["stateselector"])
     {
       echo "<OPTION VALUE=\"$fa[stateselector]\" SELECTED>$fa[state]";
     }
  else
   {
    echo "<OPTION VALUE=\"$fa[stateselector]\">$fa[state]";
   }
  }

  mysql_free_result($re);
  echo "</SELECT></td></tr>";
}


if ($def_country_allow == "YES") {

  echo "<tr><td bgColor=$def_form_back_color align=right>$def_city: <font color=red>*</font></td><td><input type=text name=city size=50 maxlength=100 value='$f[city]'></td></tr>";

}

  echo "<tr><td bgColor=$def_form_back_color align=right>$def_address: &nbsp;&nbsp;</td><td><input type=text name=address size=50  maxlength=200 value='".$f["address_".$def_language]."'></td></tr>";
  echo "<tr><td bgColor=$def_form_back_color align=right>$def_zip: &nbsp;&nbsp;</td><td><input type=text name=zip size=50  maxlength=100 value='$f[zip]'></td></tr>";
  echo "<tr><td bgColor=$def_form_back_color align=right>$def_phone: <font color=red>*</font></td><td><input type=text name=phone  maxlength=100 size=50 value='$f[phone]'></td></tr>";
  echo "<tr><td bgColor=$def_form_back_color align=right>$def_fax: </td><td><input type=text name=fax  maxlength=100 size=50 value='$f[fax]'></td></tr>";
  echo "<tr><td bgColor=$def_form_back_color align=right>$def_cellular: </td><td><input type=text name=cellular  maxlength=100 size=50 value='$f[cellular]'></td></tr>";
  echo "<tr><td bgColor=$def_form_back_color align=right>$def_manager: </td><td><input type=text  maxlength=100 name=manager size=50 value='".$f["manager_".$def_language]."'></td></tr>";
?>
  <tr><td bgColor=<? echo"$def_form_back_color";?> align=right><? echo"$def_email";?>: <font color=red>*</font></td><td><input type=text name=mail size=50  maxlength=100 onBlur="checkemail(this.value)" value='
<?
  echo "$f[mail]'></td></tr>";
  echo "<tr><td bgColor=$def_form_back_color align=right>$def_webpage: &nbsp;&nbsp;</td><td><input type=text name=www size=50 value='$f[www]'  maxlength=100></td></tr>";
  echo "<input type=hidden name=changed value=\"true\">";
  echo "<tr><td colspan=2 align=center bgColor=$def_form_header_color><input type=hidden name=step value=\"back\"><input type=submit value=\"$def_offers_change\" border=0></td></tr>";
  echo "</form></table><br>";

  mysql_close();include("footer.inc");exit();}}}


session_start();


?>
        <?echo str_replace("1%","20%","$help_header");?>
        <?echo"$user_help_3";?>
        <?echo"$help_footer";?>
  </td>

 <td valign=top align=center width="60%" bgcolor=<? echo"$def_background";?>>

<? if ($def_banner_allowed == "YES") { ?>

 <table cellpadding=0 cellspacing=0 border=0 width="100%">
 <tr>
  <td valign=center align=center width=100%>
   <? //include("banner.inc"); ?>
  </td>
 </tr>
</table>

<? } ?>



<?
 include("footer.inc");
?>