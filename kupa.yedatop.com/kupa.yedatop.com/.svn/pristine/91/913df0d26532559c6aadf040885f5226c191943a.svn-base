<?



include("defaults.php");
include("connect.php");

		// Defining date & time to write to LOG
		$time_hour = date("H");
		$time_min = date("i");
		$time_sec = date("s");

		$date_day = date("d");
		$date_month = date("m");
		$date_year = date("Y");

	// Determining user's IP
	$ip=$_SERVER["REMOTE_ADDR"];

	$log="$time_hour:$time_min:$time_sec  $date_year/$date_month/$date_day - [$_SESSION[admin_login], $ip] - $def_admin_log_enter";

	// Interting a line to LOG
	//mysql_query("INSERT INTO $db_log (log) VALUES ('$log')") or die (mysql_error());


         $incomingline="$def_admin_header";

        // Showing header
         include ("header.inc");


        // Showing help section
         echo"$help_header";
         echo"$index_help";
         echo"$help_footer";

?>

  </td>
   <td valign=top align=center width="60%" bgcolor=<? echo"$def_background";?>>
	<br>

  <font face=tahoma size=2><b><? echo"$def_admin_header"; ?></b><br><br></font>

	<center>
	<br>

<?

	// Collecting statistics

	$rstat=mysql_query("select * from $db_users") or die (mysql_error());
	$firms_registered_all=mysql_numrows($rstat);
	mysql_free_result($rstat);

	$rstat=mysql_query("select * from $db_users where firmstate='on'") or die (mysql_error());
	$firms_registered=mysql_numrows($rstat);
	mysql_free_result($rstat);

	$rstat=mysql_query("select * from $db_users where firmstate='off'") or die (mysql_error());
	$firms_nonregistered=mysql_numrows($rstat);
	mysql_free_result($rstat);

	$rstat=mysql_query("select * from $db_offers") or die (mysql_error());
	$firms_prices_all=mysql_numrows($rstat);
	mysql_free_result($rstat);

	$rstat=mysql_query("select * from $db_offers where type='1'") or die (mysql_error());
	$count_offers_1=mysql_numrows($rstat);
	mysql_free_result($rstat);

	$rstat=mysql_query("select * from $db_offers where type='2'") or die (mysql_error());
	$count_offers_2=mysql_numrows($rstat);
	mysql_free_result($rstat);

	$rstat=mysql_query("select * from $db_offers where type='3'") or die (mysql_error());
	$count_offers_3=mysql_numrows($rstat);
	mysql_free_result($rstat);


	$rstat=mysql_query("select * from $db_category") or die (mysql_error());
	$firms_cat=mysql_numrows($rstat);
	mysql_free_result($rstat);

	$rstat=mysql_query("select * from $db_subcategory") or die (mysql_error());
	$firms_subcat=mysql_numrows($rstat);
	mysql_free_result($rstat);

	$rstat=mysql_query("select * from $db_location") or die (mysql_error());
	$firms_cities=mysql_numrows($rstat);
	mysql_free_result($rstat);

	$rstat=mysql_query("select * from $db_states") or die (mysql_error());
	$count_states=mysql_numrows($rstat);
	mysql_free_result($rstat);

	$banhandle = opendir('../../catalog/banner'); 

	$bancount=0;

	while (false !== ($banfile = readdir($banhandle))) { 
	    if ($banfile != "." && $banfile != "..") { 
		   $bancount++;
						    } 
							   }
	closedir($banhandle); 

	$logohandle = opendir('../../catalog/logo'); 

	$logocount=0;

	while (false !== ($logofile = readdir($logohandle))) { 
	    if ($logofile != "." && $logofile != "..") { 
		   $logocount++;
						    }  
							     }
	closedir($logohandle); 

	$logohandle2 = opendir('../../catalog/offer'); 

	$piccount=0;

	while (false !== ($logofile1 = readdir($logohandle2))) { 
	    if ($logofile1 != "." && $logofile1 != "..") { 
		   $piccount++;
						    } 
							     }
	closedir($logohandle2); 



?>

<table cellspacing=1 cellpadding=5 bgcolor=ffffff border=0 width=70%>
	<tr><td valign=center align=center bgColor=<? echo "$def_form_header_color"; ?> colspan=2>
	 <font face=verdana size=<? echo "$def_form_header_fontsize";?> color=<? echo "$def_form_header_font_color";?>><? echo "$def_admin_stat";?></font>
	</td></tr>
	<tr>

	 <td valign=top align=left  bgcolor=<? echo"$def_form_back_color";?> width=50%>
	  <br><font face=arial size=2>

<?     $result = mysql_query('SELECT VERSION() AS version');
       if ($result != FALSE && @mysql_num_rows($result) > 0) {
         $row   = mysql_fetch_array($result);
         $match = explode('.', $row['version']);
                    }
?>

<? echo "$def_admin_companies"; ?>: <b><? echo "$firms_registered_all"; ?></b><bR><br>
<? echo "$def_admin_approved_companies"; ?>: <b><? echo "$firms_registered"; ?></b><bR>
<? echo "$def_admin_notapproved_companies"; ?>: <b><? echo "$firms_nonregistered"; ?></b><bR>

<br>

<? echo "$def_admin_offers"; ?>: <b><? echo "$firms_prices_all"; ?></b> <br><br>

<? echo "$def_offer_1"; ?>: <b><? echo "$count_offers_1"; ?></b> <br>
<? echo "$def_offer_2"; ?>: <b><? echo "$count_offers_2"; ?></b> <br>
<? echo "$def_offer_3"; ?>: <b><? echo "$count_offers_3"; ?></b> <br>
<br>

</td>
<td valign=top align=left  bgcolor=<? echo"$def_form_back_color";?> width=50%>

<br><font face=arial size=2>

<? echo "$def_admin_categories"; ?>: <b><? echo "$firms_cat"; ?></b><br>
<? echo "$def_admin_subcategories"; ?>: <b><? echo "$firms_subcat"; ?></b><br><br>
<? echo "$def_admin_locations"; ?>: <b><? echo "$firms_cities"; ?></b><br>
<? if ($def_states_allow == "YES") { echo "$def_admin_states"; ?>: <b><? echo "$count_states"; ?></b><br> <? } ?>

<br>

<? echo "$def_admin_banners"; ?>: <b><? echo "$bancount"; ?></b><br>
<? echo "$def_admin_logos"; ?>: <b><? echo "$logocount"; ?></b><br>
<? echo "$def_admin_offer_pics"; ?>: <b><? echo "$piccount"; ?></b><br>

<br>

<? echo "mySQL"; ?>: <b><? echo "$row[version]"; ?></b><br>
<? echo "PHP"; ?>: <b><? echo phpversion(); ?></b><br>
<br><br>


</td></tr>
</table>
<br><br>
</td>

<?
include("footer.inc");

?>