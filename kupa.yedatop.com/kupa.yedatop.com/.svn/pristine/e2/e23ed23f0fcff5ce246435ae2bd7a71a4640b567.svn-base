<?php

	include("include/common.php");
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
		
		//export
		if($config[export]==1){
				//export to Baraholka;
				$users = new users();
				$user = $users->get_user($userID);
				$uname = $user[name];
				$uphone = $user[work_phone];
				$uemail = $user[email];
				$data="action=delete&";
				$data.="password=".$config[export_password]."&";
				$data.="id=".$delete."&";
				//echo $data;
				 $ch = curl_init ();
				 curl_setopt($ch, CURLOPT_URL,$config[export_path]);
				 curl_setopt($ch,CURLOPT_POST,1);
				 curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
				 curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
				 curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
				 $result=curl_exec ($ch); 
				  curl_close ($ch);
		} //end if - export

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
		echo "<p>$lang[admin_listings_editor_listing_number] '$delete' $lang[has_been_deleted]</p>";
		log_action ("$lang[log_deleted_listing] $delete");
	}

	if ($action == "update_listing")
	{
		// update the listing
		if ($title== "")
		{
			// if the title is blank
			echo "$lang[admin_new_listing_enter_a_title]<br>";
		} // end if

		else
		{

			global $HTTP_POST_VARS, $userID, $pass_the_form;
			$pass_the_form = validateForm(listingsFormElements);
			if ($pass_the_form == "No")
			{
				// if we're not going to pass it, tell that they forgot to fill in one of the fields
				echo "<p>$lang[required_fields_not_filled]</p>";
			}

			if ($pass_the_form == "Yes")
			{
				$sql_title = make_db_safe($title);
				$contact1 = make_db_safe($edit_contact1);
				$sql_active = make_db_safe($active);
				$sql_notes = make_db_safe($notes);
				$sql_edit = make_db_safe($edit);

				$sql = "UPDATE listingsDB SET title = $sql_title, contact1 = $contact1, export = '$export', ";
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
				//echo $sql;
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) 
				{
					log_error($sql);
				}
				$message = updateListingsData($edit, $userID);
				if ($message == "success")
				{
					echo "<p>$lang[admin_listings_editor_listing_number] $edit $lang[has_been_updated] </p>";
					log_action ("$lang[log_updated_listing] $edit");
				} // end if
				else
				{
					echo "<p>$lang[alert_site_admin]</p>";
				} // end else
				
				
				//search-mask
				$dest_category="";
				foreach ($config[export_categories] as $key => $value){
					$key = "^".str_replace("ייי","(.+)",$key)."$";
					if (ereg($key,$drisha."~".$eska."~".$category__type."~".$type)){
						$dest_category = $value;
						break;
					}
				}
				//export
				if($config[export]==1 and $dest_category!=""){
						//export to Baraholka;
						$users = new users();
						$user = $users->get_user($userID);
						$uname = $user[name];
						$uphone = $user[work_phone];
						$uemail = $user[email];
						$data.="action=edit&";
						$data.="password=".$config[export_password]."&";
						$data.="active=".(($active=="no" or !$export)?0:1)."&";
						$data.="id=".$edit."&";
					    $data.="title=".urlencode($title)."&";
						$data.="category=".$dest_category."&";
						$data.="description=".urlencode($description)."&";
						$data.="city=".urlencode($city)."&";
						$data.="type=".urlencode($config[export_ad_type])."&";
						$data.="expiration=".$config[export_expiration]."&";
						$data.="uname=".urlencode($uname)."&";
						$data.="uphone=".urlencode($uphone)."&";
						$data.="uemail=".urlencode($uemail)."&";
						//echo $data;
						 $ch = curl_init ();
						 curl_setopt($ch, CURLOPT_URL,$config[export_path]);
						 curl_setopt($ch,CURLOPT_POST,1);
						 curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
						 curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
						 curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
						 $result=curl_exec ($ch); 
						 //echo $result;
						 curl_close ($ch);
				} //end if - export
			} // end if $pass_the_form == "Yes"
		} // end else
	} // end if $action == "update listing"


	if ($edit != "")
	{
		$sql_edit = make_db_safe($edit);
		// first, grab the listings's main info
		$sql = "SELECT ID, title, contact1, export,notes, last_modified, featured, active, expiration FROM listingsDB WHERE ((ID = $sql_edit) AND (user_ID = '$userID'))";
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

		<table border="<?php echo $style[form_border] ?>" cellspacing="<?php echo $style[form_cellspacing] ?>" cellpadding="<?php echo $style[form_cellpadding] ?>" width="<?php echo $style[admin_table_width] ?>" class="form_main">
			<tr>
				<td colspan="2" class="row_main">
				<table width=100%><tr><td>
					<h3><?php echo "$lang[admin_listings_editor_modify_listing]"?> #<?=$edit?></h3>
				</td>
				<td nowrap width=1%>
				<input type=button onclick='location="<?php echo "$config[baseurl]"."/listingview.php?listingID=$listing_ID";?>";' value=<?php echo $lang[view] ?>>
				<input type=button value='<?=$lang[contract]?>' onclick='location="realtycontracts_view.php?FilterField1=listingid&FilterOperator1=<?=urlencode("<=>")?>&FilterValue1=<?=$listing_ID?>";'>
				</td></tr></table>
				</td>

			</tr>
			<tr>
				<td width="<?php echo $style[image_column_width] ?>" valign="top" align="center" class="row_main">
					<b><?php echo $lang[images] ?></b>
					<br>
					<hr width="75%">
					<a href="edit_listings_images.php?edit=<?php echo $edit ?>"><?php echo $lang[edit_images] ?></a><br><br>
					<?php
					$sql = "SELECT caption, file_name, thumb_file_name FROM listingsImages WHERE ((listing_id = '$edit') AND (user_id = '$userID'))";
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
					<input type="hidden" name="edit" value="<?php echo $edit ?>">


					<tr>
						<td align="right" class="row_main"><b><?php echo $lang[admin_listings_editor_title] ?>: <span class="required">*</span></b></td>
						<td  class="row_main"> <input type="text" name="title" value="<?php echo $edit_title ?>"></td>
					</tr>
					<tr>
						<td align="right" class="row_main"><b><?php echo $lang[admin_listings_editor_active] ?>: <span class="required">*</span></b></td>
						<td  class="row_main"> 
						<select name=active>
						<option value=yes <?=($edit_active=="yes")?"selected":""?>>Yes
						<option value=no <?=($edit_active=="no")?"selected":""?>>No
						</select>
						</td>
					</tr>
					<tr>
						<td align="right" class="row_main"><b><?php echo $lang[admin_listings_editor_export] ?>: <span class="required">*</span></b></td>
						<td  class="row_main"> 
						<select name=export>
						<option value=1 <?=($edit_export=="1")?"selected":""?>>Yes
						<option value=0 <?=($edit_export=="0")?"selected":""?>>No
						</select>
						</td>
					</tr>
					<?php
						if ($featureListings == "yes")
						{
						?>
						<tr><td align="right" class="row_main"><b><?php echo $lang[admin_listings_editor_featured]  ?>:</b></td><td >
						<select name="featured" size="1">
							<option value="<?php echo $edit_featured ?>"><?php echo $edit_featured ?>
							<option value="">-----
							<option value="yes">yes
							<option value="no">no
						</select>
						<?php
						} // end if ($featureListings == "yes")
						if ($admin_privs == "yes")
						{
							?>
							<tr><td align="right" class="row_main"><b><?php echo $lang[admin_listings_active] ?>:</b></td><td >
							<select name="edit_active" size="1">
							<option value="<?php echo $edit_active ?>"><?php echo $edit_active ?>
							<option value="">-----
							<option value="yes">yes
							<option value="no">no
							</select>
							<?php
						}
						if (($admin_privs == "yes" OR $config[user_default_canChangeExpirations] == "yes")and $config[use_expiration] = "yes")
						{
						?>
						</td></tr>
						<tr><td align="right" class="row_main"><b><?php echo $lang[expiration] ?>:</b></td><td class="row_main" ><input type="text" name="edit_expiration" value="<?php echo $expiration ?>">(Y-M-D)</td></tr>

						<?php
						} // end if ($admin_privs == "yes" and $config[use_expiration] = "yes")
						elseif ($config[use_expiration] = "yes")
						{
						?>
						<tr><td align="right" class="row_main"><b><?php echo $lang[expiration] ?>:</b></td><td class="row_main" > <?php echo $formatted_expiration ?></td></tr>

						<?php
						} // end if  ($config[use_expiration] = "yes")
						?>
					<tr><td align="right" class="row_main"><b><?php echo $lang[last_modifed] ?>:</b></td><td class="row_main" > <?php echo $last_modified ?></td></tr>
					
					<tr>
						<td align="right" class="row_main"><b><?php echo $lang[admin_listings_editor_contact1] ?>: </b></td>
						<td  class="row_main"> <input type="text" name="edit_contact1" value="<?=$edit_contact1?>">&nbsp;<a href='javascript:open_addressbook("edit_contact1", document.update_listing.edit_contact1.value)'><img src="<?php echo $GO_THEME->image_url; ?>buttons/addressbook.gif" width="16" height="16" border="0" /></a></td>
					</tr>
					<script>
					function open_addressbook(field, address_string)
					{
					popup('<?php echo $GO_CONFIG->host."contacts/select.php?returnfield=name&SET_HANDLER=".$GO_CONFIG->host.$GO_MODULES->path."add_contacts.php&SET_FIELD="; ?>'+field+'&address_string='+escape(address_string),'550','400');
					}
					</script>

					<tr><td align="right" class="row_main"><b><?php echo $lang[admin_listings_editor_notes] ?>:</b><br><div class="small">(<?php echo $lang[admin_listings_editor_notes_note] ?>)</div></td><td class="row_main" > <textarea name="notes" rows="6" cols="40"><?php echo $edit_notes ?></textarea></td></tr>

		<?php
		echo '<script src="../date.js"></script>';
		$sql = "SELECT f.field_name, db.field_value, f.field_type, f.field_caption, f.default_text, f.field_elements, f.required FROM listingsFormElements f left join listingsDBElements db on db.field_name like concat(f.field_name,'%') and db.listing_id = '$edit' and db.user_id = '$userID' where f.access_level <> 3 ORDER BY rank";
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
			renderExistingFormElement($field_type, $field_name, $field_value, $field_caption, $default_text, $required, $field_elements);

			$recordSet->MoveNext();
		}
		echo "<tr><td colspan=\"2\" align=\"center\" class=\"row_main\">$lang[required_form_text]</td></tr>";

		echo "<tr><td colspan=\"2\" align=\"center\" class=\"row_main\"><input type=\"submit\" value=\"$lang[update_button]\"></td></tr></table></form>";

		?>
		</td></tr></table>
		<?php
	} // end if $edit != ""
	if ($edit == "")
	{?>
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
		STARTALLOPEN = 0
		
		<?

		$sql="select field_elements from listingsFormElements where field_name = 'type'";
		$recordSet = $conn->Execute($sql);
		$el = $recordSet->fields[field_elements];
		$cats = explode("*","$el");
		$megurim = explode("||",$cats[0]);
		$asakim = explode("||",$cats[1]);
		$migrashim = explode("||",$cats[2]);
		?>
		
		foldersTree = gFld("<b>הכרטסות שלי</b>", "edit_my_listings.php")
		  aux1 = insFld(foldersTree, gFld("ביקוש", "edit_my_listings.php?drisha=<?=urlencode("ביקוש")?>"))
		  aux11 = insFld(aux1, gFld("מכירה", "edit_my_listings.php?drisha=<?=urlencode("ביקוש")?>&eska=<?=urlencode("מכירה")?>"))
		  aux111 = insFld(aux11, gFld("מגורים", "edit_my_listings.php?drisha=<?=urlencode("ביקוש")?>&eska=<?=urlencode("מכירה")?>&category__type=<?=urlencode("מגורים")?>"))
		  	<?
				while (list($indexValue, $list_item) = each ($megurim))
				{
					$s = 'edit_my_listings.php?drisha='.urlencode("ביקוש").'&eska='.urlencode("מכירה").'&category__type='.urlencode("מגורים").'&type='.urlencode($list_item);
					echo 'insDoc(aux111, gLnk("S", "'.trim($list_item).'", "'.$s.'"));'."\n";
				}reset($megurim);
			?>
		  aux111 = insFld(aux11, gFld("עסקים", "edit_my_listings.php?drisha=<?=urlencode("ביקוש")?>&eska=<?=urlencode("מכירה")?>&category__type=<?=urlencode("עסקים")?>"))
		  	<?
				while (list($indexValue, $list_item) = each ($asakim))
				{
					$s = 'edit_my_listings.php?drisha='.urlencode("ביקוש").'&eska='.urlencode("מכירה").'&category__type='.urlencode("עסקים").'&type='.urlencode($list_item);
					echo 'insDoc(aux111, gLnk("S", "'.trim($list_item).'", "'.$s.'"));'."\n";
				}reset($asakim);
			?>
		  aux111 = insFld(aux11, gFld("מגרשים", "edit_my_listings.php?drisha=<?=urlencode("ביקוש")?>&eska=<?=urlencode("מכירה")?>&category__type=<?=urlencode("מגרשים")?>"))
		  	<?
				while (list($indexValue, $list_item) = each ($migrashim))
				{
					$s = 'edit_my_listings.php?drisha='.urlencode("ביקוש").'&eska='.urlencode("מכירה").'&category__type='.urlencode("מגרשים").'&type='.urlencode($list_item);
					echo 'insDoc(aux111, gLnk("S", "'.trim($list_item).'", "'.$s.'"));'."\n";
				}reset($migrashim);
			?>
			
		  aux11 = insFld(aux1, gFld("השכרה", "edit_my_listings.php?drisha=<?=urlencode("ביקוש")?>&eska=<?=urlencode("השכרה")?>"))
		  aux111 = insFld(aux11, gFld("מגורים", "edit_my_listings.php?drisha=<?=urlencode("ביקוש")?>&eska=<?=urlencode("השכרה")?>&category__type=<?=urlencode("מגורים")?>"))
		  	<?
				while (list($indexValue, $list_item) = each ($megurim))
				{
					$s = 'edit_my_listings.php?drisha='.urlencode("ביקוש").'&eska='.urlencode("השכרה").'&category__type='.urlencode("מגורים").'&type='.urlencode($list_item);
					echo 'insDoc(aux111, gLnk("S", "'.trim($list_item).'", "'.$s.'"));'."\n";
				}reset($megurim);
			?>
		  aux111 = insFld(aux11, gFld("עסקים", "edit_my_listings.php?drisha=<?=urlencode("ביקוש")?>&eska=<?=urlencode("השכרה")?>&category__type=<?=urlencode("עסקים")?>"))
		  	<?
				while (list($indexValue, $list_item) = each ($asakim))
				{
					$s = 'edit_my_listings.php?drisha='.urlencode("ביקוש").'&eska='.urlencode("השכרה").'&category__type='.urlencode("עסקים").'&type='.urlencode($list_item);
					echo 'insDoc(aux111, gLnk("S", "'.trim($list_item).'", "'.$s.'"));'."\n";
				}reset($asakim);
			?>
		  aux111 = insFld(aux11, gFld("מגרשים", "edit_my_listings.php?drisha=<?=urlencode("ביקוש")?>&eska=<?=urlencode("השכרה")?>&category__type=<?=urlencode("מגרשים")?>"))
		  	<?
				while (list($indexValue, $list_item) = each ($migrashim))
				{
					$s = 'edit_my_listings.php?drisha='.urlencode("ביקוש").'&eska='.urlencode("השכרה").'&category__type='.urlencode("מגרשים").'&type='.urlencode($list_item);
					echo 'insDoc(aux111, gLnk("S", "'.trim($list_item).'", "'.$s.'"));'."\n";
				}reset($migrashim);
			?>		  
			
		  aux1 = insFld(foldersTree, gFld("הצעה", "edit_my_listings.php?drisha=<?=urlencode("הצעה")?>"))
		  aux11 = insFld(aux1, gFld("מכירה", "edit_my_listings.php?drisha=<?=urlencode("הצעה")?>&eska=<?=urlencode("מכירה")?>"))
		  aux111 = insFld(aux11, gFld("מגורים", "edit_my_listings.php?drisha=<?=urlencode("הצעה")?>&eska=<?=urlencode("מכירה")?>&category__type=<?=urlencode("מגורים")?>"))
		  	<?
				while (list($indexValue, $list_item) = each ($megurim))
				{
					$s = 'edit_my_listings.php?drisha='.urlencode("הצעה").'&eska='.urlencode("מכירה").'&category__type='.urlencode("מגורים").'&type='.urlencode($list_item);
					echo 'insDoc(aux111, gLnk("S", "'.trim($list_item).'", "'.$s.'"));'."\n";
				}reset($megurim);
			?>
		  aux111 = insFld(aux11, gFld("עסקים", "edit_my_listings.php?drisha=<?=urlencode("הצעה")?>&eska=<?=urlencode("מכירה")?>&category__type=<?=urlencode("עסקים")?>"))
		  	<?
				while (list($indexValue, $list_item) = each ($asakim))
				{
					$s = 'edit_my_listings.php?drisha='.urlencode("הצעה").'&eska='.urlencode("מכירה").'&category__type='.urlencode("עסקים").'&type='.urlencode($list_item);
					echo 'insDoc(aux111, gLnk("S", "'.trim($list_item).'", "'.$s.'"));'."\n";
				}reset($asakim);
			?>
		  aux111 = insFld(aux11, gFld("מגרשים", "edit_my_listings.php?drisha=<?=urlencode("הצעה")?>&eska=<?=urlencode("מכירה")?>&category__type=<?=urlencode("מגרשים")?>"))
		  	<?
				while (list($indexValue, $list_item) = each ($migrashim))
				{
					$s = 'edit_my_listings.php?drisha='.urlencode("הצעה").'&eska='.urlencode("מכירה").'&category__type='.urlencode("מגרשים").'&type='.urlencode($list_item);
					echo 'insDoc(aux111, gLnk("S", "'.trim($list_item).'", "'.$s.'"));'."\n";
				}reset($migrashim);
			?>
	      
		  aux11 = insFld(aux1, gFld("השכרה", "edit_my_listings.php?drisha=<?=urlencode("הצעה")?>&eska=<?=urlencode("השכרה")?>"))
		  aux111 = insFld(aux11, gFld("מגורים", "edit_my_listings.php?drisha=<?=urlencode("הצעה")?>&eska=<?=urlencode("השכרה")?>&category__type=<?=urlencode("מגורים")?>"))
		  	<?
				while (list($indexValue, $list_item) = each ($megurim))
				{
					$s = 'edit_my_listings.php?drisha='.urlencode("הצעה").'&eska='.urlencode("השכרה").'&category__type='.urlencode("מגורים").'&type='.urlencode($list_item);
					echo 'insDoc(aux111, gLnk("S", "'.trim($list_item).'", "'.$s.'"));'."\n";
				}reset($megurim);
			?>
		  aux111 = insFld(aux11, gFld("עסקים", "edit_my_listings.php?drisha=<?=urlencode("הצעה")?>&eska=<?=urlencode("השכרה")?>&category__type=<?=urlencode("עסקים")?>"))
		  	<?
				while (list($indexValue, $list_item) = each ($asakim))
				{
					$s = 'edit_my_listings.php?drisha='.urlencode("הצעה").'&eska='.urlencode("השכרה").'&category__type='.urlencode("עסקים").'&type='.urlencode($list_item);
					echo 'insDoc(aux111, gLnk("S", "'.trim($list_item).'", "'.$s.'"));'."\n";
				}reset($asakim);
			?>
		  aux111 = insFld(aux11, gFld("מגרשים", "edit_my_listings.php?drisha=<?=urlencode("הצעה")?>&eska=<?=urlencode("השכרה")?>&category__type=<?=urlencode("מגרשים")?>"))
		  	<?
				while (list($indexValue, $list_item) = each ($migrashim))
				{
					$s = 'edit_my_listings.php?drisha='.urlencode("הצעה").'&eska='.urlencode("השכרה").'&category__type='.urlencode("מגרשים").'&type='.urlencode($list_item);
					echo 'insDoc(aux111, gLnk("S", "'.trim($list_item).'", "'.$s.'"));'."\n";
				}reset($migrashim);
			?>
		</script>
	
	<?
		echo "<table width=100% height=400 border=0><tr style='height:1%'>";
		echo "<td><h3>$lang[listings_editor]</h3></td>";
		$s='';$q='';$gs='';
		if ($drisha){
			$s .= " $drisha /";
			$q .= "and e1.field_value = '".addslashes(trim($drisha))."' ";
			$gs .= '&drisha='.urlencode($drisha);
		}
		if ($eska){
			$s .= " $eska /";
			$q .= "and e2.field_value = '".addslashes(trim($eska))."' ";
			$gs .= '&eska='.urlencode($eska);
		}
		if ($category__type){
			$s .= " $category__type /";
			$q .= "and e3.field_value = '".addslashes(trim($category__type))."' ";
			$gs .= '&category__type='.urlencode($category__type);
		}
		if ($type){
			$s .= " $type /";
			$q .= "and e4.field_value = '".addslashes(trim($type))."' ";
			$gs .= '&type='.urlencode($type);
		}
		$s = substr($s,0,strlen($s)-1);
		// show all a user's given listings

		// grab the number of listings from the db
		$sql = "
		SELECT d.ID, title, contact1, notes, expiration, active, creation_date,
		e1.field_value as drisha,
		e2.field_value as eska,
		e3.field_value as category__type,
		e4.field_value as type,
		e5.field_value as city,
		e6.field_value as rooms,
		e7.field_value as price
		FROM listingsDB d  
		inner join listingsDBElements e1
		on d.ID=e1.listing_id and e1.field_name='drisha'
		inner join listingsDBElements e2
		on d.ID=e2.listing_id and e2.field_name='eska'
		inner join listingsDBElements e3
		on d.ID=e3.listing_id and e3.field_name='category__type'
		inner join listingsDBElements e4
		on d.ID=e4.listing_id and e4.field_name='type' 
		inner join listingsDBElements e5
		on d.ID=e5.listing_id and e5.field_name='city' 
		inner join listingsDBElements e6
		on d.ID=e6.listing_id and e6.field_name='rooms' 
		inner join listingsDBElements e7
		on d.ID=e7.listing_id and e7.field_name='price' 
		where d.user_ID = '$userID' $q order by d.ID desc";
		//echo $sql;
		//print_r($conn;
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
		echo "<tr style='height:1%'><td colspan=2 bgcolor=buttonface>";
		next_prev($num_rows, $cur_page, $gs);
		echo "</td></tr>";
		echo "<tr><td width=150 dir=ltr valign=top><div style='border:solid 1 gray;overflow:auto;width:150px;height:100%'>
		<table style='display:none' border=0><tr><td><font size=-2><a style=\"font-size:7pt;text-decoration:none;color:silver\" href=\"http://www.treemenu.net/\" target=_blank>JavaScript Tree Menu</a></font></td></tr></table>
		<span class=TreeviewSpanArea><script>initializeDocument()</script></span>
		</div></td>";
		echo "<td valign=top><div style='border:solid 1 gray;overflow-Y:scroll;overflow-X:auto;width:100%;height:100%'>";

		?>
		<table border="<?php echo $style[admin_listing_border] ?>" cellspacing="0" cellpadding="<?php echo $style[admin_listing_cellpadding] ?>" width="<?php echo $style[admin_table_width] ?>" class="form_main">
		<tr class=tableHead2>
		<td class="tableHead2" ><?=$lang['admin_listings_editor_title']?></td>
		<td class="tableHead2" ><?=$lang['admin_listings_editor_type']?></td>
		<td class="tableHead2" ><?=$lang['admin_listings_editor_city']?></td>
		<td class="tableHead2" ><?=$lang['admin_listings_editor_rooms']?></td>
		<td class="tableHead2" ><?=$lang['admin_listings_editor_price']?></td>
		<td class="tableHead2" ><?=$lang['admin_listings_editor_date']?></td>
		<td class="tableHead2" ><?=$lang['admin_listings_editor_active']?></td>
		<td class="tableHead2" ><?=$lang['admin_listings_editor_action']?></td>
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
			$title = make_db_unsafe ($recordSet->fields[title]);
			$contact1 = make_db_unsafe ($recordSet->fields[contact1]);
			$type = make_db_unsafe ($recordSet->fields[type]);
			$city = make_db_unsafe ($recordSet->fields[city]);
			$rooms = make_db_unsafe ($recordSet->fields[rooms]);
			$price = make_db_unsafe ($recordSet->fields[price]);
			$notes = make_db_unsafe ($recordSet->fields[notes]);
			$date = make_db_unsafe ($recordSet->UserTimeStamp($recordSet->fields[creation_date],'j/m/Y'));
			$active = make_db_unsafe ($recordSet->fields[active]);
			$formatted_expiration = $recordSet->UserTimeStamp($recordSet->fields[expiration],'j/M/Y');
			$ID = $recordSet->fields[ID];

			?>
			<?php
			echo "<tr><td  valign=\"top\" class=\"row3_$count\">
			<a href=\"$PHP_SELF?edit=$ID\">$ID. $title</a>";
			echo "</td><td class=\"row3_$count\">$type";
			/*if ($config[use_expiration] == "yes")
			{
				echo "<br><b>$lang[expiration]</b>: $formatted_expiration";
			}*/
			echo "</td>";
			echo "</td><td class=\"row3_$count\">$city&nbsp;</td>";
			echo "</td><td class=\"row3_$count\">$rooms&nbsp;</td>";
			echo "</td><td class=\"row3_$count\">$price&nbsp;</td>";
			echo "</td><td class=\"row3_$count\">$date</td>";
			
			echo "<td align=center class=\"row3_$count\" valign=middle width=1% nowrap>";
			if ($active != "yes")
			{
				echo "<font color=\"red\">$lang[no]</font></b>";
			}
			else {
				echo "$lang[yes]";
			}
			echo "</td>";
			echo "<td class=\"row3_$count\" valign=middle width=1% nowrap>
			<a href=\"$PHP_SELF?delete=$ID\" onClick=\"return confirmDelete()\"><img src=$GO_THEME->image_url/buttons/delete.gif align=absmiddle border=0 alt='$lang[admin_listings_editor_delete_listing]'></a>&nbsp;
			<a href=\"$PHP_SELF?edit=$ID\"><img src=$GO_THEME->image_url/buttons/edit.gif align=absmiddle border=0 alt='$lang[admin_listings_editor_modify_listing]'></a>
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