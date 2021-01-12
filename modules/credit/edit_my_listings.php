<?php
include("include/common.php");
if ($edit){
	$page_subtitle = $lang['update_item'];
}


	if (!loginCheck('User'))exit;
	global $action, $id, $cur_page, $lang, $conn, $config;
	include("$config[template_path]/admin_top.html");
	
	if ($delete != "")
	{
		$sql_delete = make_db_safe($delete);
		// delete a listing
		$sql = "DELETE FROM listingsDB WHERE ((ID = $sql_delete) AND (user_ID = $userID))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) log_error($sql);
		// delete all the elements associated with a listing
		$sql = "DELETE FROM listingsDBElements WHERE ((listing_id = $sql_delete) AND (user_id = $userID))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		// now get all the images associated with an listing
		$sql = "SELECT file_name, thumb_file_name FROM listingsImages WHERE ((listing_id = $sql_delete) AND (user_id = $userID))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		
		// so, you've got 'em... it's time to unlink those bad boys...
		while (!$recordSet->EOF)
		{
			$thumb_file_name = make_db_unsafe ($recordSet->fields[thumb_file_name]);
			$file_name = make_db_unsafe ($recordSet->fields[file_name]);
			// get rid of those darned things...
			if (!unlink("$config[listings_upload_path]/$file_name"))
			{
				die("$lang[alert_site_admin]");
			}
			if ($file_name != $thumb_file_name)
			{
				if (!unlink("$config[listings_upload_path]/$thumb_file_name"))
				{
					die("$lang[alert_site_admin]");
				}
			}
			$recordSet->MoveNext();
		}

		// for the grand finale, we're going to remove the db records of 'em as well...
		$sql = "DELETE FROM listingsImages WHERE ((listing_id = $sql_delete) AND (user_id = $userID))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) 
		{
			log_error($sql);
		}

		// ta da! we're done...
		//echo "<script for=document event=onload>alert(\"".$lang[admin_listings_editor_listing_number] . " ".$delete." ".$lang[has_been_deleted]."\")</script>";
		log_action ("$lang[log_deleted_listing] $delete");
	}

	if ($action == "update_listing")
	{
		global $HTTP_POST_VARS, $userID, $pass_the_form;

		// update the listing
		if ($StockMin){ $StockMin = $HTTP_POST_VARS["StockMin"] = intval($StockMin);}
		if ($StockMax){ $StockMax = $HTTP_POST_VARS["StockMax"] = intval($StockMax);}
		if ($title== "")
		{
			// if the title is blank
			echo "$lang[admin_new_listing_enter_a_title]<br>";
		} // end if

		elseif ( $StockMin < 0 || $StockMin > 999999){
			echo "<strong style=color:red>$lang[admin_new_listing_enter_a_stockmin]</strong><br>";
		}
		elseif ( $StockMax < 0 || $StockMax > 999999){
			echo "<strong style=color:red>$lang[admin_new_listing_enter_a_stockmax]</strong><br>";
		}
		elseif ( $Quantity < 0 || $Quantity > 999999){
			echo "<strong style=color:red>$lang[admin_new_listing_enter_a_quantity]</strong><br>";
		}
		else
		{

			$pass_the_form = validateForm(listingsFormElements);


			if ($pass_the_form == "Yes")
			{
			
				$sql = "select ID from listingsDBElements where field_name='BarCode' and field_value='$BarCode' and user_id=$userID and listing_ID <> $edit";
				$recordSet = $conn->Execute($sql);
				if (!$recordSet->EOF){
					echo "<p><b style=color:red>$lang[barcode_exists]</b></p>";
				}
				else{
			
					$sql_title = make_db_safe($title);
					$contact1 = make_db_safe($edit_contact1);
					$sql_active = make_db_safe($active);
					$sql_notes = make_db_safe($notes);
					$sql_edit = make_db_safe($edit);
					$sql_export = make_db_safe($export);
	
					$sql = "UPDATE listingsDB SET title = $sql_title, export = $sql_export, ";
					if ($active=="no"){
						$sql .= " lastupdated = 0, lastdeleted = unix_timestamp(), synccatalog = 0, ";
					}
					else{
						$sql .= " lastdeleted = 0, lastupdated=unix_timestamp(), synccatalog = 0, ";
					}
					if ($featureListings == "yes")
					{
						// if the user can feature properties
						$sql_featured = make_db_safe($featured);
						$sql .= "featured = $sql_featured, ";
					} // end if ($featureListings == "yes")
					if ($admin_privs == "yes")
					{
						// if the user can feature properties
						$sql_active = make_db_safe($edit_active);
						$sql .= "active = $sql_active, ";
					} // end if ($admin_privs == "yes")
					if (($admin_privs == "yes" OR $config[user_default_canChangeExpirations] == "yes")and $config[use_expiration] = "yes")
					{
						//$date_array = explode("-",$edit_expiration);
						//$exp_text = implode(",",$date_array);
						//$expiration_date  = mktime (0,0,0,$exp_text);
						$expiration_date = strtotime($edit_expiration);
						$sql .= "expiration = ".$conn->DBDate($expiration_date).",";
					}
	
	
					$sql .= "notes = $sql_notes, active='$active', last_modified = ".$conn->DBTimeStamp(time())." WHERE ((ID = $sql_edit) AND (user_ID = $userID))";
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) 
					{
						log_error($sql);
					}
					//check price
					$sql = "select field_value as SalePrice from listingsDBElements where listing_ID = $sql_edit and field_name = 'SalePrice'";
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) 	{log_error($sql);}
					if (floatval($SalePrice)!=floatval($recordSet->fields["SalePrice"])){
						$sql = "update  listingsDB set priceupdated = unix_timestamp() where ID = $sql_edit ";
						if ($conn->Execute($sql)===false){echo $conn->ErrorMsg()."<br>".$sql;};
					}
					
					//update the rest 
					$message = updateListingsData($edit, $userID);
				} //end barcode check
				
				

			} // end if $pass_the_form == "Yes"
		} // end else
	} // end if $action == "update listing"


	if ($edit != "")
	{
		$sql_edit = make_db_safe($edit);
		// first, grab the listings's main info
		$sql = "SELECT ID, export, title, notes, last_modified, featured, active, expiration FROM listingsDB WHERE ((ID = $sql_edit) AND (user_ID = '$userID'))";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		$num_records = $recordSet->RecordCount();
		if ($num_records == 0)
		{
			die("$lang[priv_failure]");
		}

		while (!$recordSet->EOF)
		{
			// collect up the main DB's various fields
			$listing_ID = make_db_unsafe ($recordSet->fields[ID]);
			$edit_title = make_db_unsafe ($recordSet->fields[title]);
			$edit_contact1 = make_db_unsafe ($recordSet->fields[contact1]);
			$edit_export = make_db_unsafe ($recordSet->fields[export]);
			$edit_active = make_db_unsafe ($recordSet->fields[active]);
			$edit_notes = make_db_unsafe ($recordSet->fields[notes]);
			$edit_featured = make_db_unsafe ($recordSet->fields[featured]);
			$edit_active = make_db_unsafe ($recordSet->fields[active]);
			$last_modified = $recordSet->UserTimeStamp($recordSet->fields[last_modified],'d/m/Y H:i:s');
			$expiration = $recordSet->UserTimeStamp($recordSet->fields[expiration],'Y-m-d');
			$formatted_expiration = $recordSet->UserTimeStamp($recordSet->fields[expiration],'D M j Y');

			$recordSet->MoveNext();
		} // end while
		//$date_mod = getDate($last_modified);



		//$last_modified = "$date_mod[weekday], $date_mod[month] $date_mod[mday] $date_mod[year] at $date_mod[hours]:$date_mod[minutes]" // display the date in a human readable format
		// now, display all that stuff
		?>

		<table width=100% height=480 cellpadding=0 cellspacing=0>
		<TR><TD align=center>
		<?include("productmenu.php")?>
		</td></td>
		<TR><TD align=center><b>
				<?
				if ($action="update_listing"){
					if ($pass_the_form == "No")
					{
						// if we're not going to pass it, tell that they forgot to fill in one of the fields
						echo "<p style=color:red>$lang[required_fields_not_filled]</p>";
					}
					if ($message == "success")
					{
						echo "<p style=color:green>$lang[log_updated_listing]</p>";
						log_action ("$lang[log_updated_listing] $edit");
					} // end if
					else
					{
						//echo "<p style=color:red>$lang[alert_site_admin]</p>";
					}
				}
				?>
		</TD></TR>
		<tr style='height:100%'><td>
		<div style='overflow-Y:scroll;width:100%;height:100%;border:inset 1'>
		<table border="<?php echo $style[form_border] ?>" cellspacing="<?php echo $style[form_cellspacing] ?>" cellpadding="<?php echo $style[form_cellpadding] ?>" width="100%" class="form_main">
			<tr>
				<td style='display:none' width="<?php echo $style[image_column_width] ?>" valign="top" align="center" class="row_main">
					<b><?php echo $lang[images] ?></b>
					<br>
					<hr width="75%">
					<a href="edit_listings_images.php?edit=<?php echo $edit ?>"><?php echo $lang[edit_images] ?></a><br><br>
					<?php
					$sql = "SELECT caption, file_name, thumb_file_name FROM listingsImages WHERE 1=0 and ((listing_id = '$edit') AND (user_id = '$userID'))";
					$recordSet = $conn->Execute($sql);
						if ($recordSet === false) log_error($sql);

					$num_images = $recordSet->RecordCount();

					while (!$recordSet->EOF)
						{
						$caption = make_db_unsafe ($recordSet->fields[caption]);
						$thumb_file_name = make_db_unsafe ($recordSet->fields[thumb_file_name]);
						$file_name = make_db_unsafe ($recordSet->fields[file_name]);

						// gotta grab the image size
						$imagedata = GetImageSize("$config[listings_upload_path]/$thumb_file_name");
						$imagewidth = $imagedata[0];
						$imageheight = $imagedata[1];
						$shrinkage = $config[thumbnail_width]/$imagewidth;
						$displaywidth = $imagewidth * $shrinkage;
						$displayheight = $imageheight * $shrinkage;

						echo "<a href=\"$config[listings_view_images_path]/$file_name\" target=\"_thumb\"> ";

						echo "<img src=\"$config[listings_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\"></a><br> ";
						echo "<b>$caption</b><br><br>";
						$recordSet->MoveNext();
						} // end while
					?>
				</td>
				<td class="row_main">

				<table border="<?php echo $style[form_border] ?>" cellspacing="<?php echo $style[form_cellspacing] ?>" cellpadding="<?php echo $style[form_cellpadding] ?>">
					<form name="update_listing" action="<?php echo "$PHP_SELF";?>" method="post">
					<input type="hidden" name="action" value="update_listing">
					<input type="hidden" name="simple" value="<?=$simple?>">
					<input type="hidden" name="edit" value="<?php echo $edit ?>">


					<tr>
						<td align="right" class="row_main"><b><?php echo $lang[admin_listings_editor_title] ?>: <span class="required">*</span></b></td>
						<td  class="row_main"> <input type="text" style='width:230px' name="title" maxlength=50 value="<?php echo $edit_title ?>"></td>
					</tr>
					<?if ($admin_privs == "yes")
						{
							?>
							<tr><td align="right" class="row_main"><b><?php echo $lang[admin_listings_active] ?>:</b></td><td >
							<select name="edit_active" size="1">
							<option value="<?php echo $edit_active ?>"><?php echo $edit_active ?>
							<option value="">-----
							<option value="yes"><?=$lang['yes']?>
							<option value="no"><?=$lang['no']?>
							</select></td></tr>
						<?}?>

		<?php
		echo '<script src="../date.js"></script>';
		if ($workmode == "A"){	
			$q = "WHERE f.field_name <> 'Quantity' ";
		}
		else{
			$q = "";
		}
		$sql = "SELECT f.field_name, db.field_value, f.field_type, f.field_caption, f.default_text, f.field_elements, f.required 
			FROM listingsFormElements f 
			LEFT JOIN listingsDBElements db on db.field_name like concat(f.field_name,'%') and db.listing_id = '$edit' and db.user_id = '$userID' ORDER BY rank";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) 
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_name = make_db_unsafe ($recordSet->fields[field_name]);
			$field_value = make_db_unsafe ($recordSet->fields[field_value]);
			$field_type = make_db_unsafe ($recordSet->fields[field_type]);
			$field_caption = make_db_unsafe($recordSet->fields[field_caption]);
			$default_text = make_db_unsafe($recordSet->fields[default_text]);
			$field_elements = make_db_unsafe($recordSet->fields[field_elements]);
			$required = make_db_unsafe($recordSet->fields[required]);
			// pass the data to the function
			$visible = !($field_name=="Quantity" && $workmode == "A");
			renderExistingFormElement($field_type, $field_name, $field_value, $field_caption, $default_text, $required, $field_elements,$visible);

			$recordSet->MoveNext();
		}


		?>
		<tr>
			<td align="right" class="row_main"><b><?php echo $lang[admin_listings_editor_active] ?>: <span class="required">*</span></b></td>
			<td  class="row_main"> 
			<select name=active>
			<option value=yes <?=($edit_active=="yes")?"selected":""?>><?=$lang['yes']?>
			<option value=no <?=($edit_active=="no")?"selected":""?>><?=$lang['no']?>
			</select>
			</td>
		</tr>
		<tr>
			<td align="right" nowrap class="row_main"><b><?php echo $lang[admin_listings_editor_export] ?>: <span class="required">*</span></b></td>
			<td  class="row_main"> 
			<select name=export>
			<option value=1 <?=($edit_export=="1")?"selected":""?>><?=$lang['yes']?>
			<option value=0 <?=($edit_export=="0")?"selected":""?>><?=$lang['no']?>
			</select>
			</td>
		</tr>
		</table>
		</td></tr>
	</table>
	</div>	
	</td></tr>
	<tr style='height:1%'><td>
		<table width=100%>
			<?echo "<tr><td colspan=\"2\" align=\"center\" class=\"row_main\"><input type=\"submit\" style=width:100px value=\"$lang[update_button]\">
			<input style='width:100px;color=green;font-weight:bold' type=button value='{$lang['purchase_stock']}' onclick='s=window.open(\"purchase.php?ids=$edit\",\"purchase\",\"top=\"+(window.screenTop+15)+\",left=\"+(window.screenLeft+15)+\",width=500,height=400\");s.focus();'>
			</td></tr>";?>
			<?echo "<tr><td colspan=\"2\" align=\"center\" class=\"row_main\">$lang[required_form_text]</td></tr>";?>
			<tr><td align="center" class="row_main"><b><?php echo $lang[last_modifed] ?>:</b><?php echo $last_modified ?></td></tr>
		</table>
	</td></tr></form>
	</table>
		<?php
	} // end if $edit != ""
	if ($edit == "")
	{?>
		<SCRIPT LANGUAGE="JScript">
		var delete_error = "<?=$lang["delete_error"]?>";
		var oPopup = window.createPopup();
		function openPopup(mode,param)
		{
		    var oPopBody = oPopup.document.body;
			s ="style='font-family:Arial;font-size:10pt;color:black;text-decoration:none;width:100%' onmouseout='this.style.backgroundColor=\"\";this.style.color=\"black\"' onmouseover='this.style.backgroundColor=000000;this.style.color=\"white\"' href=#  ";
		    if (mode==1){
				oPopBody.innerHTML = "<DIV dir=<?=$direction?> style='height:100%;background-color:buttonface;border:solid 1 black;padding:5px'><a href=#></a><a "+s+" onclick=parent.wopen1('cp/main.php?service=groups','tools')><?=$lang[admin_menu_add_a_category]?></a><br><a "+s+" onclick=parent.wopen1('cp/main.php?service=suppliers','tools')><?=$lang[admin_menu_add_a_supplier]?></a></DIV>"
		    }
			if (mode==2){
				oPopBody.innerHTML = "<DIV dir=<?=$direction?> style='height:100%;background-color:buttonface;border:solid 1 black;padding:5px'><a href=#></a><a "+s+" onclick=parent.wopen('add_listing.php?ProductGroup="+param+"','add')><?=$lang[admin_menu_add_a_listing]?></a></DIV>"
			}
			oPopup.show(window.event.clientX, window.event.clientY+5, 130, 70, document.body);
		}
		</SCRIPT>


		<script>
		function selectall(checked){
			var ids="-1";
			for (i=0;i<document.all.tags("INPUT").length;i++){
				if (document.all.tags("INPUT")(i).id!=null&&document.all.tags("INPUT")(i).id!=""){
					document.all.tags("INPUT")(i).checked = checked;
					ids+=","+document.all.tags("INPUT")(i).id;
				}
			}
			if (ids!=""){
				basket(ids,checked);
			}
		}
		</script>
		<style>


		   /* styles for the tree */
		   SPAN.TreeviewSpanArea A,SPAN.TreeviewSpanArea A:visited {
		        font-size: 10pt; 
		        font-family: arial,helvetica; 
		        text-decoration: none;
		        color: black
		   }
		   SPAN.TreeviewSpanArea A:hover {
		        color: 'blue';
		   }
		</style>	
		<script src="ua.js"></script>
		<script src="ftiens4.js"></script>
		<script>
		USETEXTLINKS = 1
		USEFRAMES = 0
		BUILDALL = 0
		USEICONS = 1
		WRAPTEXT = 0
		HIGHLIGHT = 1
		HIGHLIGHT_BG = "BLUE"
		HIGHLIGHT_COLOR = "WHITE"
		PERSERVESTATE = 1
		STARTALLOPEN = 1
		ICONPATH = "images/"

		
		<?

		$sql="select * from listingsCategories where user_ID = $userID and Status=1 order by SortOrder,CategoryName";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		?>
		
		foldersTree = gFld("<b title='<?=$lang[tree_tooltip]?>' oncontextmenu='openPopup(1);return false;'><?=$lang[my_business]?></b>", "edit_my_listings.php?stock=<?=$stock?>")
		  root = insFld(foldersTree, gFld("<span title='<?=$lang[tree_tooltip]?>' oncontextmenu='openPopup(1);return false;'><?=$lang[admin_stock]?></span>", "edit_my_listings.php?stock=<?=$stock?>"))
		  	<?
			while (!$recordSet->EOF)
			{
					$s = "edit_my_listings.php?stock=$stock&catid=".$recordSet->fields["ID"];
					echo 'insDoc(root, gLnk("S", " <span title=\''.$lang[tree_tooltip].'\' oncontextmenu=\'openPopup(2,'.$recordSet->fields["ID"].');return false;\'> '.trim($recordSet->fields["CategoryName"]).'</span>", "'.$s.'"));'."\n";
				$recordSet->moveNext();
			}
			?>
		  del = insFld(root, gFld("פריטים לא פעילים", "edit_my_listings.php?stock=<?=$stock?>&deleted=1"))
		  del.iconSrc = "images/sbasket.gif";
		  del.iconSrcClosed = "images/sbasket.gif";
			
		  aux1 = insFld(foldersTree, gFld("<?=$lang['reports']?>", ""))
		  aux1.iconSrc = "images/prints.gif";
		  aux1.iconSrcClosed = "images/prints.gif";
		  s = insDoc(aux1, gLnk("S","<?=$lang['report_tazrim']?>", "javascript:openReport(&quot;rep_tazrim.php?&quot;)"))
		  s.iconSrc = "images/prints.gif"; 
		  s1 = insDoc(aux1, gLnk("S","<?=$lang['report_mechirot']?>", "javascript:openReport(&quot;rep_mechirot.php?stock=<?=$stock?>&quot;)"))
		  s1.iconSrc = "images/prints.gif";
		  s1 = insDoc(aux1, gLnk("S","<?=$lang['report_stocks']?>", "javascript:openReport(&quot;rep_points.php?stock=<?=$stock?>&quot;)"))
		  s1.iconSrc = "images/prints.gif";
		  s1 = insDoc(aux1, gLnk("S","<?=$lang['report_cheshbonit']?>", "javascript:openReport(&quot;rep_cheshbonit.php?stock=<?=$stock?>&quot;)"))
		  s1.iconSrc = "images/prints.gif";
		  <?
		  if (!$_GET["search"] && $_COOKIE["savedsearch"]){
		  	$search = 1;
		  	$params = $_COOKIE["savedsearch"];
		  }
		  elseif ($_GET["search"]){
		  	$params = $_SERVER["QUERY_STRING"];
		  }
		  $params.="&stock=$stock";
		  if ($search){?>
		  auxS = insFld(foldersTree, gFld("<?=$lang['search_result']?>", "<?=$PHP_SELF ."?". $params ?>"))
		  auxS.iconSrc = "images/ssearch2.gif";
		  auxS.iconSrcClosed = "images/ssearch2.gif";
		  <?}?>
			
		  //aux111 = insFld(aux11, gFld("עסקים", "edit_my_listings.php?drisha=<?=urlencode("ביקוש")?>&eska=<?=urlencode("מכירה")?>&category__type=<?=urlencode("עסקים")?>"))
		</script>
	
	<?
		echo "<table width=100% height=400 border=0><tr style='height:1%'>";
		echo "<td colspan=2></td>";
		$s='';$q='';$gs='';$where="";
		if ($catid){
			$where .= " and e3.field_value = $catid";
		}
		if ($stock){
			$where .= " and lse.StockID = $stock and lse.Quantity <> 0";
		}
		if ($deleted){
			$where .= " and d.active = 'no'";
		}
		else{
			$where .= " and d.active = 'yes'";
		}
		$s = substr($s,0,strlen($s)-1);
		// show all a user's given listings

		// grab the number of listings from the db
		if (!$_GET["search"]){
		
			if ($sortby == ""){
				$sort_text = "";
				$order_text = "ORDER BY ID desc";
				$sql_from= "";
			}
			elseif ($sortby == "bar")
			{
				$sort_text = "";
				$order_text = "ORDER BY barcode ASC";
				$sql_from= "";
			}	
			elseif ($sortby == "listingname")
			{
				$sort_text = "";
				$order_text = "ORDER BY binary Title ASC";
				$sql_from= "";
			}
			elseif ($sortby == "agent")
			{
				$sort_text = "";
				$order_text = "ORDER BY binary Owner ASC";
				$sql_from= "";
			}
			elseif ($sortby == "date")
			{
				$sort_text = "";
				$order_text = "ORDER BY ID desc";
				$sql_from= "";
			}
			elseif ($sortby == "price")
			{
				$order_text = "ORDER BY ifnull(lse.SalePrice,e2.field_value)+0 ASC";
			}
			else
			{
				$order_text = "ORDER BY Esort.field_value ASC";
			}
		
			$sql = "
			SELECT d.ID, title, active,basket,
			e1.field_value as barcode,
			ifnull(lse.SalePrice,e2.field_value) as saleprice,
			e4.field_value as cost,
			ifnull(e5.field_value,0) as quantity,
			sum(Quantity) as totalquantity
			FROM listingsDB d 
			inner join listingsDBElements e1
			on d.ID=e1.listing_id and e1.field_name='BarCode'
			inner join listingsDBElements e2
			on d.ID=e2.listing_id and e2.field_name='SalePrice'
			inner join listingsDBElements e3
			on d.ID=e3.listing_id and e3.field_name='ProductGroup'
			inner join listingsDBElements e4
			on d.ID=e4.listing_id and e4.field_name='Cost'
			inner join listingsDBElements e5
			on d.ID=e5.listing_id and e5.field_name='Quantity'
			left outer join listingsStocksElements lse on lse.ListingID = d.ID
			where  d.user_ID = '$userID' $where 
			group by d.ID, title, e1.field_value, e2.field_value
			$order_text ";
		}
		else
		{
		
		/////////////////// SEARCH /////////////////////
			$sql = "drop table IF EXISTS temp";
			$recordSet = $conn->Execute($sql);
			
			if ($recordSet === false)
			{
				log_error($sql);
			}
		
			$sql_select = "create temporary table TEMP  
			SELECT l.ID, title, active,basket,
			es1.field_value as barcode,
			ifnull(lse.SalePrice,es2.field_value) as saleprice,
			es4.field_value as cost,
			ifnull(es5.field_value,0) as quantity,
			sum(Quantity) as totalquantity
			FROM listingsDB l 
			inner join listingsDBElements es1
			on l.ID=es1.listing_id and es1.field_name='BarCode'
			inner join listingsDBElements es2
			on l.ID=es2.listing_id and es2.field_name='SalePrice'
			inner join listingsDBElements es3
			on l.ID=es3.listing_id and es3.field_name='ProductGroup'
			inner join listingsDBElements es4
			on l.ID=es4.listing_id and es4.field_name='Cost'
			inner join listingsDBElements es5
			on l.ID=es5.listing_id and es5.field_name='Quantity'
			left outer join listingsStocksElements lse on lse.ListingID = l.ID
			";
			$sql_where = " WHERE  l.user_ID = $userID  ";
			
			if ($title){	
					$sql_where .= "  AND Title like '%$title%' ";
			}
			/*
			if ($deleted){
				$sql_where .= " and l.active = 'no'";
			}
			else{
				$sql_where .= " and l.active = 'yes'";
			}	
			*/		
			
			reset ($HTTP_GET_VARS);$i=1;
			foreach ($_GET as $ElementIndexValue => $ElementContents) {
				if ($ElementIndexValue == "sortby")
				{
					$guidestring_with_sort = "$ElementIndexValue=$ElementContents";
				}
				elseif ($ElementIndexValue == "stock" || $ElementIndexValue == "search" || $ElementIndexValue == "title" || $ElementIndexValue == "del" || $ElementIndexValue == "cur_page" || $ElementIndexValue == "userid" || $ElementIndexValue == "PHPSESSID")
				{
					// do nothing
				}
				elseif ($ElementIndexValue == "imagesOnly")
				{
					$guidestring .= "$ElementIndexValue=$ElementContents&amp;";
					$sql_from . " inner join listingsImages im on im.listing_id = l.ID ";
				}
				elseif (strpos($ElementIndexValue,"-min") && $ElementContents)
				{
					$n = substr($ElementIndexValue,0,strpos($ElementIndexValue,"-"));
					$sql_from .= " inner join listingsDBElements e$i on e$i.listing_id = l.ID and e$i.field_name = '$n' and ";
					$sql_from .= " e$i.field_value >= $ElementContents ";
					$i++;
				}
				elseif (strpos($ElementIndexValue,"-max") && $ElementContents)
				{
					$n = substr($ElementIndexValue,0,strpos($ElementIndexValue,"-"));
					$sql_from .= " inner join listingsDBElements e$i on e$i.listing_id = l.ID and e$i.field_name = '$n' and ";
					$sql_from .= " e$i.field_value <= $ElementContents ";
					$i++;
				}		
				elseif ($ElementContents)
				{
					$sql_from .= "inner join listingsDBElements e$i on e$i.listing_id = l.ID and e$i.field_name = '$ElementIndexValue' and ";
					if (is_array($ElementContents)){
						while (list($featureValue, $feature_item) = each ($ElementContents))
						{
							$guidestring .= "&amp;".($ElementIndexValue)."%5B%5D=".urlencode($feature_item)."&amp;";
						}
						$values = "'".implode("','",$ElementContents)."'";
						$sql_from .= "e$i.field_value in (".$values.") ";
					}
					else{
						$sql_from .= "e$i.field_value = '$ElementContents' ";
					}
					$i++;
				}
			}
			
			 $sql_group = "	group by l.ID, title, es1.field_value, es2.field_value ";
 	
			if ($stock){
				$sql_where .= " and lse.StockID = $stock and lse.Quantity <> 0";
			}
			
			 $sql = $sql_select . $sql_from . $sql_where . $sql_group;
			 if ($conn->Execute($sql)===false){
					echo $conn->ErrorMsg()."<br>".$sql;
			 };
			 //echo $sql."<p>";
		
			// this is the main SQL that grabs the listings
			// basic sort by title..
			if ($sortby == ""){
				$sort_text = "";
				$order_text = "ORDER BY ID desc";
				$sql_from= "";
			}
			elseif ($sortby == "bar")
			{
				$sort_text = "";
				$order_text = "ORDER BY barcode ASC";
				$sql_from= "";
			}	
			elseif ($sortby == "listingname")
			{
				$sort_text = "";
				$order_text = "ORDER BY binary Title ASC";
				$sql_from= "";
			}
			elseif ($sortby == "agent")
			{
				$sort_text = "";
				$order_text = "ORDER BY binary Owner ASC";
				$sql_from= "";
			}
			elseif ($sortby == "date")
			{
				$sort_text = "";
				$order_text = "ORDER BY ID desc";
				$sql_from= "";
			}
			elseif ($sortby == "price")
			{
				$sortby = make_db_extra_safe($sortby);
				//$sql_from= " left outer join listingsDBElements Eprice on Eprice.listing_id = l.ID and Eprice.field_name='price' ";
				$order_text = "ORDER BY saleprice+0 ASC";
			}
			else
			{
				$sortby = make_db_extra_safe($sortby);
				$sql_from= " left outer join listingsDBElements Esort on Esort.listing_id = l.ID and Esort.field_name=$sortby ";
				$order_text = "ORDER BY Esort.field_value ASC";
			}
	
			$guidestring_with_sort = $guidestring_with_sort.$guidestring;
	
			$sql_select  = "select * from TEMP l ";
			$sql = $sql_select . $sql_from. $order_text;
		/////////////////// END SEARCH /////////////////
		}
		//echo $sql;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) log_error($sql);
		$num_rows = $recordSet->RecordCount();

		// build the string to select a certain number of listings per page
		$limit_str = $cur_page * $config[listings_per_page];

		$recordSet = $conn->SelectLimit($sql, $config[listings_per_page], $limit_str );
		if ($recordSet === false) 
		{
			log_error($sql);
		}
		$count = 0;
		
		echo "<td><h3>$s</h3></td>";
		echo "</tr>";
		echo "<tr style='height:1%'><td colspan=3>
		<table width=100% border=0 cellpadding=0 cellspacing=0><tr>
		<td  width=1% nowrap>";
		if ($catid){
			echo "<a style='color:black;font-size:8pt' href=# onclick=parent.wopen('add_listing.php?ProductGroup=".$catid."','add')><img src=images/folder_add.png width=16 height=16 align=absmiddle hspace=3 border=0>".$lang[admin_menu_add_a_listing]."</a>";
		}
		else{
			echo "
		 			<a style='color:black;font-size:8pt' href=# onclick=parent.wopen1('cp/main.php?service=groups','tools')><img src=images/folder_add.png width=16 height=16 align=absmiddle hspace=3 border=0>".$lang[admin_menu_add_a_category]."</a>
					<a style='color:black;font-size:8pt' href=# onclick=parent.wopen1('cp/main.php?service=suppliers','tools')><img src=images/businessman_add.png width=16 height=16 align=absmiddle hspace=3 border=0>".$lang[admin_menu_add_a_supplier]."</a>";
		}
		echo "</td><td nowrap width=99% align=left>";
		$gs="stock=$stock&catid=$catid&deleted=$deleted&sortby=$sortby&title=".urlencode($title)."&BarCode=$BarCode&ProductGroup=$ProductGroup&MisparZar=$MisparZar&MisparSiduri=$MisparSiduri&MisparChalifi=$MisparChalifi&search=".$_GET["search"];
		next_prev($num_rows, $cur_page, $gs);
		echo "</td>
		</tr></table></td></tr>";
		echo "<tr><td width=220 dir=ltr valign=top>
		<table height=100% cellpadding=0 cellspacing=0 width=100%><tr>
		<td style='padding-bottom:5px'>";?>
			<?
				$sql = "select ID,StockName from listingsStocks s where user_ID = $userID and Status=1 order by SortOrder, binary StockName";
				$rs = $conn->Execute($sql);
				if ($rs === false)
				{
					log_error($sql);
				}
				else{
					if ($_GET["search"]){
						//$srch = "+\"".$_SERVER["QUERY_STRING"]+"\"";
						$clr="highlightObjLink(root);";
					}
					
				?>
					
				<select dir='<?=$direction?>' style='width:220;background-color:#ffffcc;' name=stock onchange='<?=$clr?>location="<?$PHP_SELF?>?deleted=<?=$deleted?>&catid=<?=$catid?>&stock="+this.value<?=$srch?>'>
				<option value=""><?=$lang['all_points']?>
				<?
				while (!$rs->EOF){
					echo "<option ".(($stock==$rs->fields[ID])?"selected":"")." value=".$rs->fields[ID].">".$rs->fields[StockName]." ";
					$rs->MoveNext();
				}
				?>
				</select>
				<?}?>
		<?echo" </td>
		</tr><tr style='height:100%'><td>";
		echo "<div style='border:solid 1 gray;overflow:auto;width:220px;height:100%'>
		<table style='display:none' border=0>
		<tr><td><font size=-2><a style=\"font-size:7pt;text-decoration:none;color:silver\" href=\"http://www.treemenu.net/\" target=_blank>JavaScript Tree Menu</a></font></td></tr></table>
		<span class=TreeviewSpanArea><script>initializeDocument();".(($_GET["search"])?"highlightObjLink(auxS);":"")."</script></span>
		</div></td></tr></table></td>";
		echo "<td valign=top width=99%><div style='border:solid 1 gray;overflow-Y:scroll;overflow-X:auto;width:100%;height:100%'>";

		$gs="stock=$stock&catid=$catid&deleted=$deleted&title=".urlencode($title)."&BarCode=$BarCode&ProductGroup=$ProductGroup&MisparZar=$MisparZar&MisparSiduri=$MisparSiduri&MisparChalifi=$MisparChalifi&search=$search";
		?>
		<table border="0" cellspacing="0" cellpadding="<?php echo $style[admin_listing_cellpadding] ?>" width="100%" class="form_main">
		<tr class=tableHead2 align=center>
		<td class="tableHead2" ><input type=checkbox onclick='selectall(this.checked)'></td>
	<td class="tableHead2" nowrap><a class=gridtitlelink href="<?=basename($PHP_SELF)."?sortby=bar&$gs"?>"><?=$lang['barcode']?></a></td>
		<td class="tableHead2" width=99%><a class=gridtitlelink href="<?=basename($PHP_SELF)."?sortby=listingname&$gs"?>"><?=$lang['admin_listings_editor_title']?></a></td>
		<td class="tableHead2" nowrap><a class=gridtitlelink href="<?=basename($PHP_SELF)."?sortby=price&$gs"?>"><?=$lang['admin_listings_editor_price']?></a></td>
		<td class="tableHead2" nowrap><?=$lang['admin_listings_editor_revach']?></td>
		<td class="tableHead2" nowrap><?=$lang['admin_listings_editor_alut']?></td>
		<td class="tableHead2" nowrap><?=$lang['admin_listings_editor_revachachuz']?></td>
		<td class="tableHead2" nowrap><?=$lang['admin_listings_editor_quantity']?></td>
		<td class="tableHead2" nowrap><?=$lang['admin_listings_editor_active']?></td>
		<td class="tableHead2" nowrap><?=$lang['admin_listings_editor_action']?></td>
		</tr>
		<?
		while (!$recordSet->EOF)
		{

			// alternate the colors
			if ($count == 0)
			{
				$count = $count +1;
			}
			else
			{
				$count = 0;
			}

			//strip slashes so input appears correctly
			$barcode = make_db_unsafe ($recordSet->fields[barcode]);
			$title = make_db_unsafe ($recordSet->fields[title]);
			$saleprice = make_db_unsafe ($recordSet->fields[saleprice]);
			$cost = make_db_unsafe ($recordSet->fields[cost]);
			$active = make_db_unsafe ($recordSet->fields[active]);
			if ($workmode=="A"){
				$totalquantity = make_db_unsafe ($recordSet->fields[totalquantity]);
				$url = "quant_listings.php";
			}
			else{
				$totalquantity = make_db_unsafe ($recordSet->fields[quantity]);
				$url = "index.php";
			}
			if (!$totalquantity)$totalquantity=0;
			$ID = $recordSet->fields[ID];
			$basket = $recordSet->fields["basket"];
			$revach = ""; 
			$revachachuz = 0;
			$revach = $saleprice-$cost;

			if ($saleprice){
				$revachachuz = ($saleprice-$cost)*100/($saleprice);
				$saleprice=number_format($saleprice,2);
				$revach = number_format($revach,2);
				$revachachuz = number_format($revachachuz,2);
				if ($revachachuz>=0){
					$revachachuz="<span style=color:green>$revachachuz%</span>";
				}
				else{
					$revachachuz="<span style=color:red>$revachachuz%</span>";
				}
			}
			else{	
				$revach = "";
				$revachachuz = "";
			}

			?>
			<?php
			$cost = number_format($cost,2);
			$checked = ($basket)?"checked":"";	
			echo "<tr id='tr$ID'>";
			echo "<td onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"'  valign=\"top\" class=\"row3_$count\" ><input type=checkbox $checked id='$ID' onclick='basket($ID,this.checked)'></td>";
			echo "<td nowrap onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" class=\"row3_$count\">$barcode</td>";
			echo "<td onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" class=\"row3_$count\"><a href=\"javascript:wopen('$PHP_SELF?edit=$ID','edit')\">$title</a>&nbsp;</td>";
			echo "<td onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" nowrap class=\"row3_$count\" align=right>&nbsp;".$saleprice."&nbsp;</td>";
			echo "<td onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" nowrap class=\"row3_$count\" align=right><span dir=ltr>&nbsp;".$revach."&nbsp;</td>";
			echo "<td onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" nowrap class=\"row3_$count\" align=right><span dir=ltr>&nbsp;".$cost."&nbsp;</td>";
			echo "<td onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" nowrap class=\"row3_$count\" align=right><span dir=ltr>&nbsp;".$revachachuz."&nbsp;</td>";
			echo "<td onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' valign=\"top\" class=\"row3_$count\" align=right><a href=\"javascript:wopen('$url?edit=$ID','edit')\"><span dir=ltr ".(($totalquantity<0)?"style=color:red":"").">$totalquantity</span></a></td>";
		
			echo "<td onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' align=center class=\"row3_$count\" valign=\"top\" width=1% nowrap>";
			if ($active != "yes")
			{
				echo "<font color=\"red\">$lang[no]</font></b>";
			}
			else {
				echo "$lang[yes]";
			}
			echo "</td>";
			echo "<td bgcolor=#E0DBDC onmouseover='tr$ID.className=\"mo\"' onmouseout='tr$ID.className=\"\"' class=\"row3_$count\" valign=middle width=1% nowrap>";
			//echo "<a href=\"javascript:wopen('move_listings.php?movefrom=$stock&edit=$ID','edit')\"><img src=images/move.gif align=absmiddle border=0 alt='$lang[admin_listings_editor_move_listing]'></a>&nbsp;";

            $sql = "select * from history where listing_id = $ID";
			$test = $conn->Execute($sql);
			if ($test === false) log_error($sql);
			if ($test->EOF)
				echo "<a href=\"$PHP_SELF?delete=$ID\" onClick=\"return confirmDelete()\"><img src=images/delete2.png align=absmiddle border=0 alt='$lang[admin_listings_editor_delete_listing]'></a>&nbsp;";
			else
				echo "<a href=\"#\" onClick=\"alert(delete_error)\"><img src=images/delete2.png width=16 height=16 align=absmiddle border=0 alt='$lang[admin_listings_editor_delete_listing]'></a>&nbsp;";
			
			echo" <a href=\"javascript:wopen('$PHP_SELF?edit=$ID','edit')\"><img src=images/pencil.png width=16 height=16 align=absmiddle border=0 alt='$lang[admin_listings_editor_modify_listing]'></a>
			</td></tr>\r\n\r\n";
			$recordSet->MoveNext();
		} // end while
		?>
		</table>
		</div></td></tr></table>
		<?
	} // end if $edit == ""
?>


	<P>
	</P>

<?php
	include("$config[template_path]/admin_bottom.html");
	$conn->Close(); // close the db connection
?>