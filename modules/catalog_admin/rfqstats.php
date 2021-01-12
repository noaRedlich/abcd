<?
if (!$mode)$mode=0;
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

<center><font face=tahoma size=2>Orders history</font></center><br>
 <br><table width="100%" border="1" cellpadding="2" cellspacing="0">
<tr valign=top>
	<td nowrap>Date</td>
	<?if (!$mode){?>
	<td>Category</td>
	<?}?>
	<td>Name</td>
	<td>Contacts</td>
	<td>City</td>
	<td>Request</td>
	<td>Sent to</td>
</tr>
<?
include("connect.php");

$pages=50;

if ($_GET["DELETE"] != "") {

$r=mysql_query("select * from $db_admin where login='$_SESSION[admin_login]'") or die (mysql_error());
$f=mysql_fetch_array($r);
if (true) 
	{
	 mysql_query("delete from dev_order_send where order_id = ".$_GET["DELETE"]) or die (mysql_error());
	 mysql_query("delete from dev_order_log where id = ".$_GET["DELETE"]) or die (mysql_error());
	}
}


if (true){

$r=mysql_query("select * from $db_admin where login='$_SESSION[admin_login]'") or die (mysql_error());

$f=mysql_fetch_array($r);

if (true) {

$r=mysql_query("select id from dev_order_log where is_basket=$mode") or die (mysql_error());

$results_amount=mysql_numrows($r);

$npage=$_GET["page"]+1;
$ppage=$_GET["page"]-1;
$page1=$_GET["page"]*$pages;
$page2=$page1+$pages;

$r=mysql_query( "select *,dev_order_log.id as order_id, location_".$def_admin_language." as location, concat(category_".$def_admin_language.",' / ',subcategory_".$def_admin_language.") as category from dev_order_log left outer join dev_location on locationselector=location left outer join dev_subcategory s on catsubsel=category  left outer join dev_category c on c.selector = s.catsel where is_basket=$mode ORDER BY id desc LIMIT $page1, $page2") or die(mysql_error());

$fetchcounter=$pages;

$f=$results_amount-$page1;

if ($f < $pages) $fetchcounter=$results_amount-$page1;

for($i=0; $i<$fetchcounter; $i++)
 { $f=mysql_fetch_array($r);
 
 	if ($f[language]=="he" || $f[language]=="ru"){
		if (!strpos(" ".$f[customer],"&#")) $f[customer] = ConvertToUnicode($f[customer],$f[language]);
		if (!strpos(" ".$f[request],"&#")) $f[request] = ConvertToUnicode($f[request],$f[language]);
		if (!strpos(" ".$f[comment],"&#")) $f[comment] = ConvertToUnicode($f[comment],$f[language]);
	}
	
	if ($f[email])$f[contacts].="E-mail: ".$f[email]."<br>";
	if ($f[phone])$f[contacts].="Phone: ".$f[phone]."<br>";
	if ($f[fax])$f[contacts].="Fax: ".$f[fax]."<br>";
	
	echo "<tr valign=top>
	<td nowrap>".str_replace(" ","<br>",$f[timestamp])."&nbsp;
	<form onsubmit='return confirm(\"Delete: Are you sure?\")'><input type=hidden name=mode value=$mode><input type=hidden name=DELETE value=$f[id]><input type=submit value=Delete></form>
	</td>";
	if (!$mode){
	echo "<td>$f[category]&nbsp;</td>";
	}
	else
	{
		$f["request"]="";
		$of = mysql_query("select u.firmname_".$def_admin_language." as firmname, oo.price, oo.item,oo.quantity from dev_order_offers oo, $db_offers o, $db_users u where oo.offer_id = o.num and u.selector = o.firmselector and order_id = $f[order_id] order by firmselector") or die(mysql_error());
		while ($off = mysql_fetch_array($of)){
			$f["request"].=$off["item"]."<br>Price: ".$off["price"]."; Quantity: ".$off["quantity"]." <nobr>[".$off["firmname"]."]</nobr><hr noshade size=1>";
		}
	}
	
	echo"<td>$f[customer]&nbsp;</td>
	<td>$f[contacts]&nbsp;</td>
	<td>$f[location]&nbsp;</td>
	<td>$f[request]&nbsp;</td>
	<td>$f[sentto]&nbsp;</td>
	</tr>";
 }	

 echo "</table>";
echo "<center><table border=0><tr><br>";

$z=0;

if ($results_amount > $pages){
for($x=0; $x<$results_amount; $x=$x+$pages)
{
	if ($z == $_GET[page]) {
		echo "<form><td valign=top align=center><input type=button value=\"",$x+1,"-",$x+$pages,"\" border=0 style=\"COLOR: black; FONT-SIZE: 11px; font: bold\"></form></td>";$z++;} else {echo "<td valign=top align=center><form action=rfqstats.php?REQ=auth&mode=$mode&page=$z&pages=$pages method=post><input type=submit value=\"",$x+1,"-",$x+$pages,"\" border=0 style=\"COLOR: black; FONT-SIZE: 11px\"></form></td>";
		$z++;
	}
}
}

echo "<td valign=top align=center></td>";
echo "</table><br><br></td>";

include("footer.inc");
exit();

}}

function ConvertToUnicode ($source,$lang,$add_semicolon=true) 
{
	$source=urlencode($source);

	switch ($lang){
		case "he": $shift=1264; break;
		case "ru": $shift=848; break;
	}
	
	$decodedStr = '';
	$pos = 0;
	$len = strlen ($source);
	while ($pos < $len) 
	{
		$charAt = substr ($source, $pos, 1);
		if ($charAt == '%') 
		{
			$pos++;
			$charAt = substr ($source, $pos, 1);
			if ($charAt == 'u') 
			{
				// we got a unicode character
				$pos++;
				$unicodeHexVal = substr ($source, $pos, 4);
				$unicode = hexdec ($unicodeHexVal);
				$entity = "&#". $unicode . ';';
				$decodedStr .= utf8Encode ($entity);
				$pos += 4;
			}
			else 
			{
				// we have an escaped ascii character
				$hexVal = substr ($source, $pos, 2);
				$decodedStr .= "&#". (hexdec($hexVal)+$shift);
				if ($add_semicolon){
					$decodedStr .= ";";
				}
				$pos += 2;
			}
		}
		else 
		{
			$decodedStr .= $charAt;
			$pos++;
		}
	}
	
	$decodedStr	= str_replace("+"," ",$decodedStr);
	return $decodedStr;
}

?>
