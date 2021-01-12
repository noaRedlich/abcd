<?

 include("defaults.php");
 session_start();

         $incomingline="$def_admin_header";

        // Showing header
         include ("header.inc");


        // Showing help section
         echo"$help_header";
         echo"$editor_help";
         echo"$help_footer";

?>

 </td>
 <td valign=top align=center width="60%" bgcolor=<? echo"$def_background";?>>

 <br><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr>
 <td width="100%" align=center valign=top>

<?

echo "<font face=tahoma size=2>$def_admin_priceeditor</font><br><br>";

$can="";

include("connect.php");

//********
if ($_POST["do"] == "uploaded") {
	if ($_POST["mode"] == "logo") {$picdir="logo";} else {$picdir="banner";}

	if ($HTTP_POST_FILES['img1']['tmp_name']) {
	
		$f["selector"]=$_POST["idin"];
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
			
			echo  "<font color=green>$def_banner_ok $size[0]x$size[1], $type. ID: $f[selector]</font><br>";
		
		}               
		
		else {
		
			if ($_POST["mode"] == "logo")
				echo "<font color=red>$def_banner_error ($def_logo_width x $def_logo_height) @ $def_logo_size Bytes</font><br>";
			else 
				echo "<font color=red>$def_banner_error ($def_banner_width x $def_banner_height) @ $def_banner_size Bytes</font><br>";
		
		}
		
	
	} 
	else 
	{
	
		echo  "<font color=red>$def_specify_file</font><br>";
	
	}
}

elseif (($_GET["REQ"] == "complete") and ($_POST["inbut"]== "$def_admin_save")){

$r=mysql_query("select * from $db_admin where login='$_SESSION[admin_login]'") or die (mysql_error());

$f=mysql_fetch_array($r);




//********
if (true){
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
  $webfax = ereg_replace ($search, $replace, $_POST["webfax"]);
  $webfax=ereg_replace("'","&quot;",$webfax);
  $cellular = ereg_replace ($search, $replace, $_POST["cellular"]);
  $cellular=ereg_replace("'","&quot;",$cellular);
  $manager = ereg_replace ($search, $replace, $_POST["manager"]);
  $manager=ereg_replace("'","&quot;",$manager);
  $mail = ereg_replace ($search, $replace, $_POST["mail"]);
  $mail=ereg_replace("'","&quot;",$mail);
  $www = ereg_replace ($search, $replace, $_POST["www"]);
  $www=ereg_replace("'","&quot;",$www);
/*
if ($_POST["oldprices"] > $_POST["offers"]){

$r=mysql_query("SELECT * from $db_offers where firmselector='$_POST[idin]'") or die (mysql_error());

$countbanners=mysql_numrows($r);

for ($aaa=0;$aaa<$countbanners;$aaa++)

{

$f=mysql_fetch_array($r);

@unlink("../../catalog/offer/$f[num].gif");
@unlink("../../catalog/offer/$f[num].bmp");
@unlink("../../catalog/offer/$f[num].jpg");
@unlink("../../catalog/offer/$f[num].png");

}

$r=mysql_query("DELETE FROM $db_offers where firmselector='$_POST[idin]'") or die (mysql_error());

echo "<font face=verdana size=2>$def_admin_oldoffersdel</font><br><br>";

 }
 */

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

  // If user have changed the category, we also change the main category in the price lines

$reee=mysql_query("select * from $db_users where selector='$_POST[idin]'") or die (mysql_error());

$faaa=mysql_fetch_array($reee);

  if ($curr[0] <> $faaa[category1]) {mysql_query("UPDATE $db_offers SET firmcategory1='$curr[0]' where firmselector='$f[selector]'") or die (mysql_error());}
  if ($curr2[0] <> $faaa[category2]) {mysql_query("UPDATE $db_offers SET firmcategory2='$curr2[0]' where firmselector='$f[selector]'") or die (mysql_error());}
  if ($curr3[0] <> $faaa[category3]) {mysql_query("UPDATE $db_offers SET firmcategory3='$curr3[0]' where firmselector='$f[selector]'") or die (mysql_error());}

if ($def_country_allow == "YES") {$location=$_POST["location"]; $postedcity=$city;} else {$location=$_POST["location"]; $postedcity="No city";}
if ($def_states_allow == "YES") {$state=$_POST["state"];}

$date = "$faaa[date_upgrade]";
if (($faaa["flag"] == "B") and ($_POST["listing"] == "A")) { $date = date("Y-m-d"); }
if (($faaa["flag"] == "A") and ($_POST["listing"] == "B")) { $date = "0"; }

if (!$sendfax) $sendfax=0;
if (!$sendmail) $sendmail=0;
if ($servicearea){
	$servicearea = implode(",",$servicearea);
}
mysql_query("UPDATE $db_users SET flag='$_POST[listing]', category1=$curblock1, category2=$curblock21, category3=$curblock31, subcategory1=$curblock2, subcategory2=$curblock22, subcategory3=$curblock32, 
firmname_".$def_admin_language."='$firmname', 
business_".$def_admin_language."='$business', 
keyword_".$def_admin_language."='$keyword', 
location='$location', servicearea='$servicearea', state='$state', city='$postedcity', 
address_".$def_admin_language."='$address', 
manager_".$def_admin_language."='$manager', 
zip='$zip', phone='$phone', fax='$fax', sendfax='$sendfax',sendmail='$sendmail', 
webfax='$webfax', maxdayorders='$maxdayorders', cellular='$cellular',
mail='$mail', www='$www', 
prices='$_POST[offers]', 
sales='$_POST[sales]', 
comment='$_POST[comment]', date_upgrade='$date' where selector='$_POST[idin]'") or die (mysql_error());

$re=mysql_query("select * from $db_users where selector='$_POST[idin]'") or die (mysql_error());

$fa=mysql_fetch_array($re);

$time_hour = date("H");
$time_min = date("i");
$time_sec = date("s");

$date_day = date("d");
$date_month = date("m");
$date_year = date("Y");

	$ip=$_SERVER["REMOTE_ADDR"];

$log="$time_hour:$time_min:$time_sec  $date_year/$date_month/$date_day - [$_SESSION[admin_login], $ip] - \"$fa[firmname]\" $def_offers_changed $_POST[offers]";

mysql_query("INSERT INTO $db_log (log) VALUES ('$log')") or die (mysql_error());

echo "<font face=verdana size=2>&nbsp;$fa[firmname] ($_POST[idin]) $def_admin_refreshed!</font><br>";

$can="yes";
}
}





if (($_GET["REQ"] == "complete") and ($_POST["inbut"] == "$def_admin_compremove")){

$r=mysql_query("select * from $db_admin where login='$_SESSION[admin_login]'") or die (mysql_error());
$f=mysql_fetch_array($r);
if (true){

$ra=mysql_query("select * from $db_users where selector='$_POST[idin]'") or die (mysql_error());
$fa=mysql_fetch_array($ra);

$r=mysql_query("DELETE FROM $db_users where selector='$_POST[idin]'") or die (mysql_error());


$r=mysql_query("SELECT * from $db_offers where firmselector='$_POST[idin]'") or die (mysql_error());

$countbanners=mysql_numrows($r);

for ($aaa=0;$aaa<$countbanners;$aaa++)

{

$f=mysql_fetch_array($r);

@unlink("../../catalog/offer/$f[num].gif");
@unlink("../../catalog/offer/$f[num].bmp");
@unlink("../../catalog/offer/$f[num].jpg");
@unlink("../../catalog/offer/$f[num].png");

}

$r=mysql_query("DELETE FROM $db_offers where firmselector='$_POST[idin]'") or die (mysql_error());


@unlink("../../catalog/banner/$_POST[idin].gif");
@unlink("../../catalog/banner/$_POST[idin].bmp");
@unlink("../../catalog/banner/$_POST[idin].jpg");
@unlink("../../catalog/banner/$_POST[idin].png");

@unlink("../../catalog/logo/$_POST[idin].gif");
@unlink("../../catalog/logo/$_POST[idin].bmp");
@unlink("../../catalog/logo/$_POST[idin].jpg");
@unlink("../../catalog/logo/$_POST[idin].png");

echo "<font face=verdana size=2>$def_admin_companydelete \"$fa[firmname]\".</font><br><br>";
                        }

$time_hour = date("H");
$time_min = date("i");
$time_sec = date("s");

$date_day = date("d");
$date_month = date("m");
$date_year = date("Y");

	$ip=$_SERVER["REMOTE_ADDR"];

$log="$time_hour:$time_min:$time_sec  $date_year/$date_month/$date_day - [$_SESSION[admin_login], $ip] - $def_admin_companydelete \"$fa[firmname]\" ($fa[selector])";

mysql_query("INSERT INTO $db_log (log) VALUES ('$log')") or die (mysql_error());

$can="yes";
                   }






  if (($_GET["REQ"] == "auth") or ($can=="yes")) 
  {


$r=mysql_query("select * from $db_admin where login='$_SESSION[admin_login]'") or die (mysql_error());
$f=mysql_fetch_array($r);
if(true){


if (isset($_GET["ACT"])) {

if ($_GET["ACT"] == "banner")

{

@unlink("../../catalog/banner/$_GET[ID].gif");
@unlink("../../catalog/banner/$_GET[ID].bmp");
@unlink("../../catalog/banner/$_GET[ID].jpg");
@unlink("../../catalog/banner/$_GET[ID].png");

$r=mysql_query("SELECT firmname_he as firmname, selector, mail FROM $db_users where selector='$_GET[ID]'") or die (mysql_error());
$f=mysql_fetch_array($r);

$time_hour = date("H");
$time_min = date("i");
$time_sec = date("s");

$date_day = date("d");
$date_month = date("m");
$date_year = date("Y");

$ip=$_SERVER["REMOTE_ADDR"];

$to = $f["mail"];

mail($to,$rejected_banner,$banner_message,"FROM: ".$from);

echo "$def_admin_bannerdeleted \"$f[firmname]\" ($f[selector])<br>";

$log="$time_hour:$time_min:$time_sec  $date_year/$date_month/$date_day - [$_SESSION[admin_login], $ip] - $def_admin_bannerdeleted \"$f[firmname]\" ($f[selector])";

mysql_query("INSERT INTO $db_log (log) VALUES ('$log')") or die (mysql_error());


}

if ($_GET["ACT"] == "logo")

{

@unlink("../../catalog/logo/$_GET[ID].gif");
@unlink("../../catalog/logo/$_GET[ID].bmp");
@unlink("../../catalog/logo/$_GET[ID].jpg");
@unlink("../../catalog/logo/$_GET[ID].png");

$r=mysql_query("SELECT firmname_he as firmname, selector, mail FROM $db_users where selector='$_GET[ID]'") or die (mysql_error());
$f=mysql_fetch_array($r);

$time_hour = date("H");
$time_min = date("i");
$time_sec = date("s");

$date_day = date("d");
$date_month = date("m");
$date_year = date("Y");

$ip=$_SERVER["REMOTE_ADDR"];

$to = $f["mail"];

mail($to,$rejected_logo,$logo_message,"FROM: ".$from);

echo "$def_admin_logodeleted \"$f[firmname]\" ($f[selector])<br>";

$log="$time_hour:$time_min:$time_sec  $date_year/$date_month/$date_day - [$_SESSION[admin_login], $ip] - $def_admin_logodeleted \"$f[firmname]\" ($f[selector])";

mysql_query("INSERT INTO $db_log (log) VALUES ('$log')") or die (mysql_error());


}

}



echo "<br><center><table cellpadding=2 cellspacing=1 border=0 width=\"80%\">";
echo "<form name=s action=offers.php?REQ=auth method=post>";

echo "<tr><td  bgColor=$def_form_header_color  align=center colspan=2 height=21>SEARCH BY</td></tr>";
echo "<tr><td bgColor=$def_form_back_color align=right>Company name: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><input type=text name=idname value='$idname' maxlength=100 size=40></td></tr>";
echo "<tr><td bgColor=$def_form_back_color align=right>Company ID: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><input type=text name=idident value='$idident' maxlength=100 size=40>
<input type=button value=\"Lookup\" border=0 onclick='lookup()'></td></tr>";
echo "<tr><td bgColor=$def_form_back_color align=right>Company email: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><input type=text name=idmail value='$idmail' maxlength=100 size=40></td></tr>";
echo "<tr><td bgColor=$def_form_back_color align=right>Company URL: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><input type=text name=idweb value='$idweb' maxlength=100 size=40></td></tr>";

echo "<tr><td align=center bgColor=$def_form_header_color colspan=2>
<input type=submit name=inbut value=\"Search\" border=0>
<script>
function lookup(){
	id = window.showModalDialog('lookup.php','center:yes;dialogWidth:600px;dialogHeight:500px;resizable:yes;resize:yes;');
	if (id+''!='undefined'){
		document.s.idident.value = id;
	}
}
</script>
</td></form></tr></table><br><br>";


if ((isset($_POST["idident"])) or (isset($_POST["idname"])) or (isset($_POST["idmail"])) or (isset($_POST["idweb"])))

{

$query="select * from $db_users where ";
if ($_POST["idident"] <> "") $query.="selector='$_POST[idident]' or";

if ($_POST["idmail"] <> "") $query.=" mail='$_POST[idmail]' or ";

if ($_POST["idweb"] <> "") $query.=" www like '%$_POST[idweb]%' or ";

if ($_POST["idname"] <> "") $query.=" firmname_".$def_admin_language." LIKE '%$_POST[idname]%'  or ";

$query.=" selector = '-1'";
$r=mysql_query($query) or die (mysql_error());
 $f=mysql_fetch_array($r);
 $id=($f["selector"]);

if (mysql_numrows($r) == 0) {echo "<font face=verdana size=2>Sorry, nothing was found</font><br><br>";}

else {

	echo "<br><center><table cellpadding=2 cellspacing=1 border=0 width=\"90%\">";

	echo "<form action=offers.php?REQ=complete method=post>";


?>

 <tr><td colspan=2 align=center>ID: <?=$f[selector]?>. <a href='../catalog/edoffers.php?id=<?=$f[selector]?>'><u><?=$def_offers?></u></a></td></tr>
 <tr><td bgColor=<? echo "$def_form_back_color";?> align=right><? echo "$def_admin_premium:"; ?> &nbsp;&nbsp;</td><td bgcolor=<? echo "$def_form_back_color";?> ><font><input type=radio name=listing value='A' <? if ($f[flag] == 'A') echo "CHECKED";?> style="border:0;"></td></tr>

 <tr><td bgColor=<? echo "$def_form_back_color";?> align=right><? echo "$def_admin_standard:"; ?> &nbsp;&nbsp;</td><td bgcolor=<? echo "$def_form_back_color";?> ><font><input type=radio name=listing value='B' <? if ($f[flag] == 'B') echo "CHECKED";?> style="border:0;"></td></tr>

<?
  $f["firmname".$def_admin_language] = str_replace("'","&#39;",$f["firmname".$def_admin_language]) ;
  $f["business".$def_admin_language] = str_replace("'","&#39;",$f["business".$def_admin_language]);
  $f["address".$def_admin_language] = str_replace("'","&#39;",$f["address".$def_admin_language]);
  $f["keyword".$def_admin_language] = str_replace("'","&#39;",$f["keyword".$def_admin_language]);
  $f["manager".$def_admin_language] = str_replace("'","&#39;",$f["manager".$def_admin_language]); 
 

  echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_title: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><input type=text name=firmname maxlength=100 size=60 value='".$f["firmname_".$def_admin_language]."'></td></tr>";

  echo "<tr><td bgColor=$def_form_back_color align=right>Description: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><font><input type=text maxlength=500 name=business size=60 value='".$f["business_".$def_admin_language]."'></td></tr>";

  echo "<tr><td bgColor=$def_form_back_color align=right>Keywords: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><font><input type=text maxlength=500 name=keyword size=60 value='".$f["keyword_".$def_admin_language]."'></td></tr>";

  
 echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_category: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><font><SELECT NAME=curblock style=\"width: 376;\">";
                                                                                                                                                                                                                                                                                             
 $rr=mysql_query("select selector,category_".$def_admin_language." as category from $db_category order by category");
 $results_amount=mysql_numrows($rr);

 echo "<OPTION VALUE=\"\">$def_sel_cat";

 for($i=0;$i<$results_amount;$i++)
  {
   $fr=mysql_fetch_array($rr);

    $ra=mysql_query("select catsel, catsubsel,subcategory_".$def_admin_language." as subcategory from $db_subcategory where catsel=$fr[selector] order by subcategory");
    $results_amount2=mysql_numrows($ra);

    for($j=0;$j<$results_amount2;$j++)
    {
    $fa=mysql_fetch_array($ra);
    if (($f["category1"] == $fr["selector"]) and ($f["subcategory1"] == $fa["catsubsel"])) echo "<OPTION VALUE=\"$fr[selector]::$fa[catsubsel]\" SELECTED>$fr[category] :: $fa[subcategory]\n";
    else echo "<OPTION VALUE=\"$fr[selector]::$fa[catsubsel]\">$fr[category] :: $fa[subcategory]";

    }
    echo "<OPTION VALUE=\"\">--";
  }
  mysql_free_result($r);
  mysql_free_result($ra);
  echo "</SELECT></td></tr>";


/*


 echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_category 2: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><font><SELECT NAME=curblock2 style=\"width: 376;\">";
                                                                                                                                                                                                                                                                                             
 $rr=mysql_query("select selector,category_he as category from $db_category order by category");
 $results_amount=mysql_numrows($rr);

 echo "<OPTION VALUE=\"\">$def_sel_cat";

 for($i=0;$i<$results_amount;$i++)
  {
   $fr=mysql_fetch_array($rr);

    $ra=mysql_query("select catsel, catsubsel,subcategory_he as subcategory from $db_subcategory where catsel=$fr[selector] order by subcategory");
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




 echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_category 3: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><font><SELECT NAME=curblock3 style=\"width: 376;\">";
                                                                                                                                                                                                                                                                                             
 $rr=mysql_query("select selector,category_he as category from $db_category order by category") or die(mysql_query());
 $results_amount=mysql_numrows($rr);

 echo "<OPTION VALUE=\"\">$def_sel_cat";

 for($i=0;$i<$results_amount;$i++)
  {
   $fr=mysql_fetch_array($rr);

    $ra=mysql_query("select catsel, catsubsel,subcategory_he as subcategory from $db_subcategory where catsel=$fr[selector] order by subcategory") or die(mysql_query());
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

if ($def_country_allow == "YES") echo "<tr><td align=right valign=middle bgColor=$def_form_back_color>$def_admin_country: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><font><SELECT NAME=\"location\" style=\"width: 376;\">";
else echo "<tr><td align=right valign=middle bgColor=$def_form_back_color>$def_admin_city: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><font><SELECT NAME=\"location\" style=\"width: 376;\">";

  $re=mysql_query("select locationselector,location_".$def_admin_language." as location from $db_location order by location_he");
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

  echo "<tr><td align=right valign=middle bgColor=$def_form_back_color>$def_servicearea: </td>
  <td bgColor=$def_form_back_color ><SELECT NAME=\"servicearea[]\" MULTIPLE SIZE=5 style=\"width:376;\">";

  $re=mysql_query("SELECT locationselector,location_".$def_admin_language." as location FROM $db_location where location_".$def_admin_language." <>'' order by location");
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
    
if ($def_states_allow == "YES") {
  echo "<tr><td align=right valign=middle bgColor=$def_form_back_color>$def_admin_state: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><font><SELECT NAME=\"state\" style=\"width: 376;\">";

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

if ($def_country_allow == "YES") echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_city: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><font><input type=text name=city maxlength=100 size=60 value='$f[city]'></td></tr>";

  echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_address: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><font><input type=text name=address maxlength=200 size=60 value='".$f["address_".$def_admin_language]."'></td></tr>";

  echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_zip: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><font><input type=text name=zip maxlength=100 size=60 value='$f[zip]'></td></tr>";
  echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_phone: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><font><input type=text name=phone  maxlength=100 size=60 value='$f[phone]'></td></tr>";
  echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_fax: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><font><input type=text name=fax  maxlength=100 size=60 value='$f[fax]'></td></tr>";
  echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_webfax: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><font><input type=text name=webfax  maxlength=100 size=60 value='$f[webfax]'></td></tr>";
  echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_cellular: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><font><input type=text name=cellular  maxlength=100 size=60 value='$f[cellular]'></td></tr>";
  echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_manager: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><font><input type=text  maxlength=100 name=manager size=60 value='".$f["manager_".$def_admin_language]."'></td></tr>";
?>
  <tr><td bgColor=<? echo"$def_form_back_color";?> align=right><?echo"$def_admin_mail";?>: &nbsp;&nbsp;</td><td bgColor=<? echo"$def_form_back_color";?> ><input type=text name=mail size=60  maxlength=100 onBlur="checkemail(this.value)" value='
<?
  echo "$f[mail]'></td></tr>";
  echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_page: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><font><input type=text name=www size=60 value='$f[www]' maxlength=100></td></tr>";

  $res=mysql_query("select num from $db_offers where firmselector='$f[selector]'");
  $price_results_amount=mysql_numrows($res);
  mysql_free_result($res);
  $free_offers=$f["prices"]-$price_results_amount;

 if ($f["prices"] > "0")
  {
   echo "<tr><td align=right valign=middle bgColor=$def_form_back_color>$def_admin_offers: &nbsp;&nbsp;</td><td  valign=middle bgColor=$def_form_back_color>$price_results_amount used, $free_offers free.</td></tr>";
  }

	echo "<tr><td bgColor=$def_form_back_color align=right>$def_setoffers: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><input type=text name=offers maxlength=10 size=40 value='$f[prices]'></td></tr>";
	echo "<tr><td bgColor=$def_form_back_color align=right>$def_setsales: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><input type=text name=sales maxlength=10 size=40 value='$f[sales]'></td></tr>";
	echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_comments: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><input type=text name=comment  maxlength=100 size=40 value='$f[comment]'></td></tr>";
	echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_sendmail: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><input type=checkbox value=1 name=sendmail  ".(($f[sendmail])?"checked":"")."></td></tr>";
	echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_sendfax: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><input type=checkbox value=1 name=sendfax   ".(($f[sendfax])?"checked":"")."></td></tr>";
	echo "<tr><td bgColor=$def_form_back_color align=right>$def_maxdayorders: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><input type=text name=maxdayorders maxlength=10 size=40 value='$f[maxdayorders]'></td></tr>";

	echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_mail: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color ><a class=normal  href=mailto:$f[mail]><font face=verdana size=2>$f[mail]</a></td></tr>";

@$add_host=gethostbyaddr($f[ip]);
@$update_host=gethostbyaddr($f[ip_update]);

	echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_posted: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color >$f[date] / $f[ip] ($add_host)</td></tr>";

if (($f["date_update"] != "0") and (!empty($f["date_update"]))) echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_updated: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color >$f[date_update] / $f[ip_update] ($update_host)</td></tr>";
if (($f["date_upgrade"] != "0") and (!empty($f["date_upgrade"]))) echo "<tr><td bgColor=$def_form_back_color align=right>$def_admin_upgraded: &nbsp;&nbsp;</td><td bgColor=$def_form_back_color >$f[date_upgrade]</td></tr>";

  echo "<tr><td colspan=2 align=center valign=middle width=30% bgcolor=$def_form_back_color><font color=red>Please, REFRESH your browser window, to see the latest images!</font></td></tr>";



// BANNER SHOW

$banhandle = opendir('../../catalog/banner'); 

$bancount=0;

while (false !== ($banfile = readdir($banhandle))) { 
    if ($banfile != "." && $banfile != "..") { 
        $banbanner[$bancount]="$banfile";
   $bancount++;
    } 
}
closedir($banhandle); 
                                
for ($aaa=0;$aaa<count($banbanner);$aaa++)
{
$banrbanner = explode(".", $banbanner[$aaa]); 

if ($banrbanner[0] == $f["selector"]) 
 {

	echo "<tr><td bgColor=$def_form_back_color align=center colspan=2>BANNER<br><br><img src=../../catalog/banner/$banrbanner[0].$banrbanner[1] border=0><br><a class=normal  href=offers.php?REQ=auth&ACT=banner&ID=$banrbanner[0]><b>REMOVE BANNER</b></a></td></tr>";

 }
}


// LOGO SHOW 


// BANNER SHOW

$logohandle = opendir('../../catalog/logo'); 

$logocount=0;

while (false !== ($logofile = readdir($logohandle))) { 
    if ($logofile != "." && $logofile != "..") { 
        $logobanner[$logocount]="$logofile";
   		$logocount++;
    } 
}
closedir($logohandle); 
                                
for ($aaa=0;$aaa<count($logobanner);$aaa++)
{
$logo1banner = explode(".", $logobanner[$aaa]); 

if ($logo1banner[0] == $f["selector"]) 
 {
	echo "<tr><td bgColor=$def_form_back_color align=center colspan=2>LOGO<br><br><img src=../../catalog/logo/$logo1banner[0].$logo1banner[1] border=0><br><a class=normal  href=offers.php?REQ=auth&ACT=logo&ID=$logo1banner[0]><b>REMOVE LOGO</b></a></td></tr>";

 }
}


	echo "<tr><td align=center bgColor=$def_form_header_color colspan=2><input type=hidden name=oldprices value=$f[prices]><input type=hidden name=idin value='$f[selector]'><input type=submit name=inbut value=\"$def_admin_save\" border=0 >&nbsp;<input type=submit name=inbut value=\"$def_admin_compremove\" border=0></td></form></tr>";
?>
<tr><td align=right valign=middle width=100% bgcolor=<? echo"$def_form_back_color";?> colspan=2>
<FORM ACTION="offers.php?REQ=auth"  METHOD="POST" ENCTYPE="multipart/form-data">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" WIDTH="100%">
	<TR>
		<TD HEIGHT="21" COLSPAN="3" BGCOLOR="<? echo "$def_form_header_color"; ?>" align=center>
			<FONT FACE="verdana" size=<? echo"$def_form_header_fontsize";?> color=<? echo "$def_form_header_font_color";?>>Upload Logo</FONT>
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
			<INPUT TYPE="file" NAME="img1" SIZE="25">&nbsp;
			<input type=hidden name=idin value='<?=$f[selector]?>'>
			<input type=hidden name=step value="back">
			<input type=hidden name=mode value="logo">
			<input type=hidden name=do value="uploaded">
			<INPUT TYPE="SUBMIT" NAME="Submit" VALUE="Upload">
		</TD>
	</TR>
</TABLE>
</td></tr>
</FORM>
<?
	echo "</table><br><br>";


}         }	
?>

</td></tr></table></td>

<?


include("footer.inc");

exit();

}}

mysql_close();

?>

</body></html>
