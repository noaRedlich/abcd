<?

 require("defaults.php");
 require("../catalog_admin/connect.php");

 session_start();
 require_once($GO_CONFIG->class_path.'users.class.inc');
 $users = new users();
 $userID = $GO_SECURITY->user_id;
 $user = $users->get_user($userID);
 $_SESSION["login"] = $user[username];
 if ($id && $_SESSION[admin]==1){
    $r=mysql_query("select * from $db_users where selector='$id'") or die (mysql_error());
 }
 else{
   $r=mysql_query("select * from $db_users where login='$_SESSION[login]'") or die (mysql_error());
 }

   // Selecting current login and pass from db
   $f=mysql_fetch_array($r);
   $defaultcategory = $f["subcategory1"];

    $ip=$_SERVER["REMOTE_ADDR"];
    //if ($_SESSION['auth'] <> $ip) {echo "Stolen session!";exit();}

  // if login and pass are OK, than continue..
if(true) {

     // if we changed the offer, start the procedure..
     if ($_POST["changed"] == "true")
      {

	// replacing all wicked symbols
	$search = array ("'<script[^>]*?>.*?</script>'si","'<[\/\!]*?[^<>]*?>'si","'&#(\d+);'e");
	$replace = array ("","","chr(\\1)");

	$item = ereg_replace ($search, $replace, $_POST["item"]);
        $item=ereg_replace("'","&quot;",$item);

	$message = ereg_replace ($search, $replace, $_POST["message"]);
        $message=ereg_replace("'","&quot;",$message);

	$quantity = ereg_replace ($search, $replace, $_POST["quantity"]);
        $quantity=ereg_replace("'","&quot;",$quantity);

	$packaging = ereg_replace ($search, $replace, $_POST["packaging"]);
        $packaging=ereg_replace("'","&quot;",$packaging);

	$price = ereg_replace ($search, $replace, $_POST["price"]);
        $price =ereg_replace("'","&quot;",$price);

	$oldprice = ereg_replace ($search, $replace, $_POST["oldprice"]);
        $oldprice =ereg_replace("'","&quot;",$oldprice);
		
	$offers=$f["prices"];
	$firmselec=$f["selector"];
	$type=$_POST["type"];

	// if there are no offers available we exit
 	if ($f["prices"] == '0')

           {
		echo "<br><br>&nbsp;$def_offer_closed";mysql_close();exit();
            }

 	if ((trim($item)=="") and ($_POST["but"] != "$def_offers_delete") and ($_POST["but"] != "$def_offers_edit_but") and ($_POST["do"] != "Upload"))
	{
		$empty="$def_offers_empty";
	}
 	if ($curblock1=="" and ($_POST["but"] != "$def_offers_delete") and ($_POST["but"] != "$def_offers_edit_but") and ($_POST["do"] != "Upload"))
	{
		$empty="$def_category_empty";
	}

  // if add button was pressed
  if ((empty($empty)) and ($_POST["but"] == "$def_offers_add"))

    {

	// selecting all offer lines of this company
	$r=mysql_query("SELECT * FROM $db_offers where firmselector='$firmselec' order by num desc");
	$results_amount=mysql_numrows($r);
    $sres=mysql_query("select num from $db_offers where firmselector='$firmselec' and sale=1");
    $sales_amount=mysql_num_rows($sres);


  // test if we haven't exceeded the number of defined offer lines

  
  if ($results_amount <> $offers)
   {
   	$sale=(!$sale)?0:1;
	$iserror = false;
	if ($sale){
		$mc=mysql_fetch_array(mysql_query("select count(*) cnt from $db_offers where firmselector = $firmselec and sale = 1")); 
		if (!($GO_SECURITY->has_admin_permission($GO_SECURITY->user_id))){
			if ($mc[cnt]>=$f[sales]){
				$over="$def_sales_limit $f[sales]";
				$iserror = true;
			}
		}
	}	
	if (!$iserror){
	    // if all is ok, we insert the line
		$curblock1 = explode("::",$curblock1);
		$curblock1 = $curblock1[1];
		$curblock2 = explode("::",$curblock2);
		$curblock2 = $curblock2[1];
		$curblock3 = explode("::",$curblock3);
		$curblock3 = $curblock3[1];
		$date = date("Y-m-d");
		mysql_query("INSERT INTO $db_offers (firmselector, firmcategory1,  firmcategory2,firmcategory3,
		item_".$def_language.",
		keyword_".$def_language.",
		date, type, sale,
		message_".$def_language.", 
		quantity, packaging, price,oldprice) VALUES ('$firmselec','$curblock1', '$curblock2', '$curblock3', 
		'$item', 
		'$keyword', 
		'$date', '$type', '$sale',
		'$message', 
		'$quantity', '$packaging', '$price', '$oldprice')") or die(mysql_error());
		$seek = mysql_insert_id();
	}
   }
	// or we write out "You have exceeded the number of defined offers"
	else  {$over="$def_offers_limit $offers";}

   }

  // if delete button was pressed
  if ((empty($empty)) and ($_POST["but"] == "$def_offers_delete"))

   {

    @unlink("../../catalog/offer/$seek.gif");
    @unlink("../../catalog/offer/$seek.bmp");
    @unlink("../../catalog/offer/$seek.jpg");
    @unlink("../../catalog/offer/$seek.png");
    // simply delete the record from the db with the given parameter
    mysql_query("DELETE FROM $db_offers WHERE num='$seek' and firmselector='$firmselec'") or die(mysql_error());

   }

  // if change button was pressed
  if ((empty($empty)) and ($_POST["but"] == "$def_offers_change"))

   {
	// changing the existing record
	$date = date("Y-m-d");
	$sale=(!$sale)?0:1;
	
	$iserror = false;
	if ($sale){
		$mc=mysql_fetch_array(mysql_query("select count(*) cnt from $db_offers where firmselector = $firmselec and sale = 1 and num <> '$seek'")); 
		if (!($GO_SECURITY->has_admin_permission($GO_SECURITY->user_id))){
			if ($mc[cnt]+1>$f[sales]){
				$over="$def_sales_limit $f[sales]";
				$iserror = true;
			}
		}
	}
	
	if (!$iserror){
		$curblock1 = explode("::",$curblock1);
		$curblock1 = $curblock1[1];
		$curblock2 = explode("::",$curblock2);
		$curblock2 = $curblock2[1];
		$curblock3 = explode("::",$curblock3);
		$curblock3 = $curblock3[1];
		mysql_query("UPDATE $db_offers SET 
		firmcategory1='$curblock1',
		firmcategory2='$curblock2',
		firmcategory3='$curblock3',
		item_".$def_language."='$item', 
		keyword_".$def_language."='$keyword', 
		model='$model',	date='$date', type=$type, 
		message_".$def_language."='$message',  
		sale=$sale,
		quantity='$quantity', packaging='$packaging', price='$price', oldprice='$oldprice' where num='$seek' and firmselector='$firmselec'") or die(mysql_error());
		$seek = $_POST["seek"];
	}
   }

  }

  
	//upload
	if ((empty($empty)) && ($_POST["but"] == "$def_offers_change" || $_POST["but"] == "$def_offers_add")
	&& $HTTP_POST_FILES[img1][name]){		
		chmod ($HTTP_POST_FILES['img1']['tmp_name'], 0777) or $upload="<font color=red>$def_offerpic_error</font><br>";
		$size=Getimagesize($HTTP_POST_FILES['img1']['tmp_name']);
		$filesize=filesize($HTTP_POST_FILES['img1']['tmp_name']);
	
		$max_width=$def_offer_pic_width;
		$max_height=$def_offer_pic_height;
		$max_size=$def_offer_pic_size;
	
		if ((($size[0] <= $max_width) and ($size[1] <= $max_height) and ($filesize < $max_size) and ($size[2] <> 4)) and (($size[2] == 1) or ($size[2] == 2) or ($size[2] == 3) or ($size[2] == 6)))
		{
			if ($size[2]==1) $type="gif";
			if ($size[2]==2) $type="jpg";
			if ($size[2]==3) $type="png";
			if ($size[2]==6) $type="bmp";
			@unlink("../../catalog/offer/$seek.gif");
			@unlink("../../catalog/offer/$seek.bmp");
			@unlink("../../catalog/offer/$seek.jpg");
			@unlink("../../catalog/offer/$seek.png");
			copy($HTTP_POST_FILES['img1']['tmp_name'], "../../catalog/offer/$seek.$type") or $upload="<font color=red>$def_offerpic_error</font><br>";
			chmod ("../../catalog/offer/$seek.$type", 0777) or $upload="<font color=red>$def_offers_pic_error</font><br>";
			$upload="<font color=green>$def_offers_pic_ok ($size[0]x$size[1], $type)</font><br>";
		}
		else
		{
			$upload="<font color=red>$def_offers_pic_error ($def_offer_pic_width x $def_offer_pic_height) @ $def_offer_pic_size Bytes</font><br>";
	    }
	}

 // Here we start output of the form of the default page...


 $firmsel=$f["selector"];

 // if the user have got to this stage and have no lines we kick him out
 if ($f["prices"] == '0'){echo "$def_nolines";mysql_close();exit();}

 // Selecting all records of this company
 $r=mysql_query("select *,subcategory_$lang as category from $db_offers left outer join $db_subcategory on firmcategory1 = catsubsel where firmselector='$firmsel' ORDER BY num desc");

 if ($_POST["but"] == "$def_offers_edit_but")

  {

   $re=mysql_query("select * from $db_offers where num='$seek' and firmselector='$firmsel'");
   $fe=mysql_fetch_array($re);

   $form_item=$fe["item_".$def_language];
   $form_keyword=$fe["keyword_".$def_language];
   $form_model=$fe[model];
   $form_type=$fe[type];
   $form_message=$fe["message_".$def_language];
   $form_quantity=$fe[quantity];
   $form_packaging=$fe[packaging];
   $form_price=$fe[price];
   $form_oldprice=$fe[oldprice];
   $form_sale = $fe[sale];

 
  }

 // Creating the status line
 $incomingline="$def_user | $def_offers_edit | $f[firmname]";

 // Showing header
 require("../catalog_admin/header.inc");

 // Showing help section
 echo str_replace("1%","20%","$help_header");
 echo"$edprice_help_1";
 echo"$help_footer";

?>

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

<div align=left>
<center><b><?=$f["firmname_".$def_language]?></b></center>
<table width=99%>
<tr>
<td width=5% nowrap>
<?
 // Go Back button

 if ($id && $_SESSION[admin]==1){
 	echo"<form action='../catalog_admin/offers.php?REQ=auth' method=post>&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit border=0 value='$def_back'>&nbsp;&nbsp;&nbsp;</form>"; 
 }
 else{
 	echo"<form action=index.php?REQ=authorize method=post><input type=hidden name=step value=\"back\">&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit border=0 value='$def_back'>&nbsp;&nbsp;&nbsp;</form>"; 
 }
?> 
</td>
<td>
<form name="exc_upload" method="post" action="import.php?id=<?=$id?>" target=_blank enctype="multipart/form-data">
<center>
<fieldset><legend>Import Excel file</legend>
  Choose a file <input type="file" size=30 name="excel_file">
  <input type="button" value="Import" onClick="javascript:if(document.exc_upload.excel_file.value.length==0) { alert('You must specify a file first'); return; }; submit();">
</fieldset>
</center>
</td>
<td align=center>
<a href=example.zip><u>Download sample<br>Excel file</a><br><br>
<a target=_blank href="/catalog/categories.php?language=<?=$current_language?>"><u>Category codes</a>
</td>
</form>
</tr>
</table>
<script>
function validate(){
	if (document.edit.item_he.value=="" && document.edit.item_en.value=="" && document.edit.item_ru.value=="" ){
		alert("<?=$def_offers_empty?>");
		return false;
	}
	if (document.edit.curblock1.value==""){
		alert("<?=$def_category_empty?>");
		document.edit.curblock1.focus();
		return false;
	}
	return true;
}
</script>
<?  
 if ($id && $_SESSION[admin]==1){
echo "<form name=edit onsubmit='return validate()' action=edoffers.php?id=$id method=post enctype=\"multipart/form-data\"><center>";
}
else{
echo "<form name=edit onsubmit='return validate()' action=edoffers.php method=post enctype=\"multipart/form-data\"><center>";
}
if (!empty($empty)) echo "<center><b style=color:red>$empty</b></center>";
echo "<table cellpadding=5 cellspacing=1 border=0 width=97%><tr><td align=center bgColor=$def_form_header_color height=21 colspan=3><font face=verdana size=$def_form_header_fontsize color=$def_form_header_font_color>\n$def_offers_edit</font></td></tr>

 <td bgColor=$def_form_back_color align=right width=80%>
 <table border=0 cellpadding=0 width=100%>
 <tr>
 <td > $def_offers_type: <font color=red>*</font></td>
 
 <td><SELECT NAME=type style=\"width:100%;\">

";

?>

<OPTION VALUE=1 <? if ($form_type == '1') echo "SELECTED"; ?>><? echo"$def_offer_1"; ?>
<OPTION VALUE=2 <? if ($form_type == '2') echo "SELECTED"; ?>><? echo"$def_offer_2"; ?>
<OPTION VALUE=3 <? if ($form_type == '3') echo "SELECTED"; ?>><? echo"$def_offer_3"; ?>

<?
                                                                                                                                                                                                                                                                                            

 echo "</SELECT></td></tr>
 <tr><td width=1% nowrap><a name=edit></a>$def_offers_item: </td><td><input type=text name=item value='$form_item' style='width:100%' size=45 maxlength=100></td>
 <tr><td width=1% nowrap>$def_offers_description: &nbsp;&nbsp;</td><td><textarea type=text name=message rows=2 style='width:100%' cols=35>$form_message</textarea></td>
 <tr><td width=1% nowrap>$def_offers_keyword: </td><td><input type=text name=keyword value='$form_keyword' style='width:100%' size=45 maxlength=255></td>
 <tr><td width=1% nowrap>$def_offers_model: </td><td><input type=text name=model value='$form_model' style='width:100%' size=45 maxlength=50></td>
 <tr><td width=1% nowrap>$def_offers_quantity: &nbsp;&nbsp;</td><td><input type=text name=quantity style='width:100%' value='$form_quantity' size=45 maxlength=100></td>
 <!--$def_offers_packaging: &nbsp;&nbsp;<input type=text name=packaging style='width:100%' value='$form_packaging' size=45 maxlength=100>-->";

 echo "<tr><td>$def_category 1: <font color=red>*</font></td><td><SELECT NAME=curblock1 style=\"width:100%;\">";
 $rr=mysql_query("select selector,category_".$def_language." as category from $db_category order by category");
 $results_amount1=mysql_numrows($rr);
 echo "<OPTION VALUE=\"\">$def_sel_cat";
 for($i=0;$i<$results_amount1;$i++)
  {
    $fr=mysql_fetch_array($rr);
    $ra=mysql_query("select catsubsel,catsel,subcategory_".$def_language." as subcategory from $db_subcategory where catsel=$fr[selector] order by subcategory");
    $results_amount2=mysql_numrows($ra);
    for($j=0;$j<$results_amount2;$j++)
    {
	    $fa=mysql_fetch_array($ra);
    	if (($fe["firmcategory1"] && $fe["firmcategory1"] == $fa["catsubsel"]) 
 		or  (!$fe["firmcategory1"] && $defaultcategory == $fa["catsubsel"]))
			echo "<OPTION VALUE=\"$fr[selector]::$fa[catsubsel]\" SELECTED>$fr[category] :: $fa[subcategory]\n";
	    else 
			echo "<OPTION VALUE=\"$fr[selector]::$fa[catsubsel]\">$fr[category] :: $fa[subcategory]";
	    }
    echo "<OPTION VALUE=\"\">--";
  }
  echo "</SELECT></td></tr>";

 echo "<tr><td>$def_category 2: </td><td><SELECT NAME=curblock2 style=\"width:100%;\">";
 $rr=mysql_query("select selector,category_".$def_language." as category from $db_category order by category");
 $results_amount1=mysql_numrows($rr);
 echo "<OPTION VALUE=\"\">$def_sel_cat";
 for($i=0;$i<$results_amount1;$i++)
  {
    $fr=mysql_fetch_array($rr);
    $ra=mysql_query("select catsubsel,catsel,subcategory_".$def_language." as subcategory from $db_subcategory where catsel=$fr[selector] order by subcategory");
    $results_amount2=mysql_numrows($ra);
    for($j=0;$j<$results_amount2;$j++)
    {
	    $fa=mysql_fetch_array($ra);
    	if ($fe["firmcategory2"] && $fe["firmcategory2"] == $fa["catsubsel"]) 
			echo "<OPTION VALUE=\"$fr[selector]::$fa[catsubsel]\" SELECTED>$fr[category] :: $fa[subcategory]\n";
	    else 
			echo "<OPTION VALUE=\"$fr[selector]::$fa[catsubsel]\">$fr[category] :: $fa[subcategory]";
	    }
    echo "<OPTION VALUE=\"\">--";
  }
  echo "</SELECT></td></tr>";

 echo "<tr><td>$def_category 3: </td><td><SELECT NAME=curblock3 style=\"width:100%;\">";
 $rr=mysql_query("select selector,category_".$def_language." as category from $db_category order by category");
 $results_amount1=mysql_numrows($rr);
 echo "<OPTION VALUE=\"\">$def_sel_cat";
 for($i=0;$i<$results_amount1;$i++)
  {
    $fr=mysql_fetch_array($rr);
    $ra=mysql_query("select catsubsel,catsel,subcategory_".$def_language." as subcategory from $db_subcategory where catsel=$fr[selector] order by subcategory");
    $results_amount2=mysql_numrows($ra);
    for($j=0;$j<$results_amount2;$j++)
    {
	    $fa=mysql_fetch_array($ra);
    	if ($fe["firmcategory3"] && $fe["firmcategory3"] == $fa["catsubsel"]) 
			echo "<OPTION VALUE=\"$fr[selector]::$fa[catsubsel]\" SELECTED>$fr[category] :: $fa[subcategory]\n";
	    else 
			echo "<OPTION VALUE=\"$fr[selector]::$fa[catsubsel]\">$fr[category] :: $fa[subcategory]";
	    }
    echo "<OPTION VALUE=\"\">--";
  }
  echo "</SELECT></td></tr>";
  
  echo "</table>";
echo "</td>";
echo "<td bgColor=$def_form_back_color align=center width=20%>
	$def_offers_price:<br><input type=text maxlength=10 name=price value='$form_price' size=7 value=\"0\" >
	<br><br>
	<fieldset>
	<input type=checkbox value=1 name=sale ".(($form_sale==1)?" checked":"")."> $def_issale<br>
	<br>
	$def_offers_oldprice:<br><input type=text maxlength=10 name=oldprice value='$form_oldprice' size=7 value=\"0\" >
	</fieldset>
	
</td></tr>";


if ($def_price_pics_allowed == "YES") {

if (isset($upload)) {echo "<center><b>$upload</b>";}

echo"
<tr><td colspan=3 align=center>
$def_offers_imageupload: 
<INPUT TYPE=\"file\" NAME=\"img1\" SIZE=\"25\">
<!--INPUT TYPE=\"submit\" NAME=\"do\" value=\"Upload\"-->";
echo "</td></tr>";
}

echo "<tr><td align=center bgColor=$def_form_header_color colspan=3>
<input type=\"submit\" name=but value=\"$def_offers_add\">&nbsp;
<INPUT type=\"submit\" name=but value=\"$def_offers_change\">
<INPUT type=hidden name=id value=$id>
<INPUT type=hidden name=changed value=true>
<INPUT type=hidden name=seek value=$seek>
<br>
";

echo "</center></td></tr>";
echo "</form>";

 echo "<table cellspacing=1 cellpadding=1 border=0 width=97%><tr><td width=100%>";
 @$results_amount=mysql_numrows($r);
 $sres=mysql_query("select num from $db_offers where firmselector='$f[selector]' and sale=1");
 $sales_amount=mysql_num_rows($sres);

 $pricelines=$f[prices];
 $salelines=$f[sales];



 // Writing out the message about the exceeding of the price lines
 if(isset($over))
  {
   echo"<center><font color=red>$over</font><br></center>";
  }
  
  
 
  
$free = $pricelines-$results_amount;
$free_sales=$salelines-$sales_amount;



echo "<center>$def_offers: <b>$pricelines</b>, $def_free: $free, $def_used: $results_amount.<br></center>";
echo "<center>$def_sales: <b>$salelines</b>, $def_free: $free_sales, $def_used: $sales_amount.<br><br></center>";

echo "<center>
<table cellpadding=3 cellspacing=1 border=0 width=97%><tr>
<td bgColor=$def_form_header_color align=middle valign=middle width=10%>#</td>
<td bgColor=$def_form_header_color align=middle valign=middle width=10%>$def_issale</td>
<td bgColor=$def_form_header_color align=middle valign=middle width=50%>$def_offers_item</td>
<td bgColor=$def_form_header_color align=middle valign=middle width=20%>
$def_offers_price
</td><td width=1% bgColor=$def_form_header_color>&nbsp;</td></tr>";

 // Output of the form with the existing price lines

$banhandle = opendir('../../catalog/offer'); 

$bancount=0;
if($banhandle){
while (false !== ($banfile = readdir($banhandle))) { 
    if ($banfile != "." && $banfile != "..") { 
        $banbanner[$bancount]="$banfile";
   $bancount++;
    } 
}
closedir($banhandle); 
}

 for($i=0; $i<$results_amount; $i++)
  {
 $f=mysql_fetch_array($r);

     if($i%2 == 0) {$color="$def_form_back_color";} else {$color="$def_background";}

$pictext="";
                            
for ($aaa=0;$aaa<count($banbanner);$aaa++)
{
$banrbanner = explode(".", $banbanner[$aaa]); 

if ($banrbanner[0] == $f[num]) $pictext="[<a class=normal href=\"javascript:popup('../../catalog/offers-pix.php?PIC=$f[num].$banrbanner[1]')\">picture</a>]";

}

$type_offer="";

if ($f["type"] == 1) $type_offer="$def_offer_1";
if ($f["type"] == 2) $type_offer="$def_offer_2";
if ($f["type"] == 3) $type_offer="$def_offer_3";
$category = $f["category"];
?>

<form name=fi<?=$f[num]?> method=post
onsubmit='return document.fi<?=$f[num]?>.f.value==""'>
<input type=hidden name=seek value='<?=$f[num]?>'>
<INPUT type=hidden name=changed value=true>
<?if ($seek==$f[num] && $_POST["but"] == "$def_offers_edit_but"){$color="yellow";}?>
<tr
style='background-color:<? echo $color;?>'
>
<td valign=top align=center ><?=($f[code])?></td>
<td valign=top align=center ><?=($f[sale])?"Yes":""?></td>

<?
if ($f['model']){
	$model="</u></b><span style='color:#996600'><nobr> ".$def_offers_model.": ".$f['model'].'</nobr></span>';
}
else{
	$model="";
}

 echo "<td align=left valign=top>
 <b>".$f["item_".$def_language]."</b><br>[$type_offer] $pictext [$category] $model<br>
 ".$f["message_".$def_language]." 

 <!--br>$def_offers_packaging: $f[packaging]--></td>";
 echo "<td align=left valign=top>$f[price]";
 if ($f[oldprice]){
	 echo " (<s>$f[oldprice]</s>)";
 }
 echo"<br>
 $def_offers_quantity: $f[quantity]
 </td>
 <td  valign=top>
 <input type=\"hidden\" name=f value=\"\">&nbsp;
 <input type=\"submit\" name=but value=\"$def_offers_edit_but\" onclick='document.fi".$f[num].".f.value=\"\";'>&nbsp;
 <INPUT type=\"submit\" name=but value=\"$def_offers_delete\" onclick='this.parentElement.parentElement.style.backgroundColor=\"yellow\";if (confirm(\"Delete item: are you sure?\")){document.fi".$f[num].".f.value=\"\";}else{document.fi".$f[num].".f.value=\"1\";} this.parentElement.parentElement.style.backgroundColor=\"$color\";'>

 </td>
 
 </tr></form>";
  }

echo "</table><br>";




echo "</table><br>";

//echo "</td></tr></table></td>";


?>
<SCRIPT LANGUAGE="JavaScript">

function popup(page) 

{

window.open(page,'page','width=<? echo "$def_offers_window_width"; ?>,height=<? echo "$def_offers_window_height"; ?>,directories=no,menubar=no,status=no,location=no,resizable=yes,scrollbars=yes,top=100,left=100');

}
</SCRIPT>
<?

// Closing DB connection
mysql_close();

// Including footer


 include("footer.inc");}

// If user started the script without parameters we just kick him out!
else { 
echo "ERROR: Sorry, this script can't be used separately.";
 include("footer.inc");
}



?>