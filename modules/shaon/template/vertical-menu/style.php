<?php
	// style defs
	// generic style by jon roig (jon@jonroig.com)

	$style = array();


	// THE STYLE FOR ALL THE FORMS
	$style['form_cellpadding'] = "2";
	$style['form_cellspacing'] = "1";
	$style['form_border'] = "0";


	// ADMIN STYLES
	$style['admin_table_width'] = "100%";
	$style['image_column_width'] = "120";

	$style['admin_listing_cellpadding'] = "3";
	$style['admin_listing_cellspacing'] = "3";
	$style['admin_listing_border'] = "0";


	// USER PAGE RENDERING STYLES
	$style['left_right_table_width'] = "530";
	$style['left_right_table_cellpadding'] = "0";
	$style['left_right_table_cellspacing'] = "0";
	$style['left_right_table_border'] = "0";

	$style['feature_table_width'] = "530";
	$style['feature_table_cellpadding'] = "0";
	$style['feature_table_cellspacing'] = "0";
	$style['feature_table_border'] = "0";

	// RENDER THE USERS PAGE ELEMENTS
	function getAllUsersData()
	{
		// grabs the main info for a given user
		global $conn, $lang;

		$user = make_db_extra_safe($user);
		$sql = "SELECT user_name, emailAddress, ID FROM UserDB where user_name != 'admin' order by user_name";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		// get main listings data
		?>
			<table border="0" cellspacing="<?php echo $style[form_cellspacing] ?>" cellpadding="<?php echo $style[form_cellpadding] ?>" width="<?php echo $style[admin_table_width] ?>" class="form_main" align="center">
		<?php
		while (!$recordSet->EOF)
		{
			$name = make_db_unsafe ($recordSet->fields[user_name]);
			$userID = make_db_unsafe ($recordSet->fields[ID]);
			$emailAddress = make_db_unsafe ($recordSet->fields[emailAddress]);
			?>
				<tr>
					<td>
						<?php
							renderUserImages($userID);
						?>
					<td>
					<td>
						<?php
							echo"<br><h3>$name</h3>";
							echo"<p><b>Email:</b> <a href=\"mailto:$emailAddress\">$emailAddress</a>";
							renderUserInfo($userID);
							echo"</p>";
						?>
					</td>
				</tr>
			<?php
			$recordSet->MoveNext();
		} // end while
		echo"</table>";
	} // function getAllUsersData

	// RENDER THE LISTINGS PAGE ELEMENTS
	function GetPrice($listingID)
	{
		// Gets Price of listing for Mortgage Calc
		global $conn, $config;
		$listingID = make_db_extra_safe($listingID);
		$name = make_db_extra_safe(price);
		$sql = "SELECT listingsDBElements.field_value, listingsFormElements.field_type, listingsFormElements.field_caption FROM listingsDBElements, listingsFormElements WHERE ((listingsDBElements.listing_id = $listingID) AND (listingsFormElements.field_name = listingsDBElements.field_name) AND (listingsDBElements.field_name = $name))";

		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_value = make_db_unsafe ($recordSet->fields[field_value]);
			$field_type = make_db_unsafe ($recordSet->fields[field_type]);
			$field_caption = make_db_unsafe ($recordSet->fields[field_caption]);
			if ($field_type == "price")
			{
				echo $field_value;
				//$money_amount = international_num_format($field_value);
				//echo "<br><b>$field_caption</b>: ".money_formats($money_amount);
			} // end elseif
			$recordSet->MoveNext();
		}
	}
	function renderListingsImages($listingID)
	{
		// shows the images connected to a given image

		global $conn, $lang, $config, $style;
		// grab the images
		$listingID = make_db_extra_safe($listingID);
		$sql = "SELECT ID, caption, file_name, thumb_file_name FROM listingsImages WHERE (listing_id = $listingID) ORDER BY rank";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		$num_images = $recordSet->RecordCount();
		if ($num_images > 0)
		{
			echo "<td width=\"$style[image_column_width]\" valign=\"top\" class=\"row_main\" align=\"center\">";

			echo "<b>$lang[images]</b><br><hr width=\"75%\">";
			while (!$recordSet->EOF)
			{
				$caption = make_db_unsafe ($recordSet->fields[caption]);
				$thumb_file_name = make_db_unsafe ($recordSet->fields[thumb_file_name]);
				$file_name = make_db_unsafe ($recordSet->fields[file_name]);
				$imageID = make_db_unsafe ($recordSet->fields[ID]);

				// gotta grab the image size
				$imagedata = GetImageSize("$config[listings_upload_path]/$thumb_file_name");
				$imagewidth = $imagedata[0];
				$imageheight = $imagedata[1];
				$shrinkage = $config[thumbnail_width]/$imagewidth;
				$displaywidth = $imagewidth * $shrinkage;
				$displayheight = $imageheight * $shrinkage;

				$bimagedata = GetImageSize("$config[listings_upload_path]/$file_name");
				$bimagewidth = $bimagedata[0]+40;
				$bimageheight = $bimagedata[1]+40;
				echo "<a href=\"javascript:void(open('$config[listings_view_images_path]/$file_name','','width=$bimagewidth,height=$bimageheight,top=100,left=100,scrollbars=yes,resizable=yes'))\"> ";

				echo "<img src=\"$config[listings_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\" alt=\"$thumb_file_name\"></a><br> ";
				echo "<b>$caption</b><br><br>";
				$recordSet->MoveNext();
			} // end while
			echo "</td>";
		} // end if ($num_images > 0)
	} // end function renderListingsImages


	function makeYahooMap($listingID, $address_field, $city_field, $state_field)
	{
		// renders a link to yahoo maps on the page

		global $conn, $config;
		$sql_listingID = make_db_extra_safe($listingID);
		$sql_address_field = make_db_safe($address_field);
		$sql_city_field = make_db_safe($city_field);
		$sql_state_field = make_db_safe($state_field);
		// get address
		$sql = "SELECT listingsDBElements.field_value, listingsFormElements.field_type, listingsFormElements.field_caption FROM listingsDBElements, listingsFormElements WHERE ((listingsDBElements.listing_id = $sql_listingID) AND (listingsFormElements.field_name = listingsDBElements.field_name) AND (listingsDBElements.field_name = $sql_address_field))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$yahoo_address = make_db_unsafe ($recordSet->fields[field_value]);
			$recordSet->MoveNext();
		} // end while
		// get city
		$sql = "SELECT listingsDBElements.field_value, listingsFormElements.field_type, listingsFormElements.field_caption FROM listingsDBElements, listingsFormElements WHERE ((listingsDBElements.listing_id = $sql_listingID) AND (listingsFormElements.field_name = listingsDBElements.field_name) AND (listingsDBElements.field_name = $sql_city_field))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$yahoo_city = make_db_unsafe ($recordSet->fields[field_value]);
			$recordSet->MoveNext();
		} // end while
		// get state
		$sql = "SELECT listingsDBElements.field_value, listingsFormElements.field_type, listingsFormElements.field_caption FROM listingsDBElements, listingsFormElements WHERE ((listingsDBElements.listing_id = $sql_listingID) AND (listingsFormElements.field_name = listingsDBElements.field_name) AND (listingsDBElements.field_name = $sql_state_field))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$yahoo_state = make_db_unsafe ($recordSet->fields[field_value]);
			$recordSet->MoveNext();
		} // end while
		$yahoo_string = "Pyt=Tmap&amp;addr=$yahoo_address&amp;csz=$yahoo_city,$yahoo_state&amp;Get+Map=Get+Map";
		echo "<a href=\"http://maps.yahoo.com/py/maps.py?$yahoo_string\" target=\"_map\"><b>View a map of the area</b></a>";
	} // end makeYahooMap




	function renderSingleListingItem($listingID, $name)
	{
		// renders a single item on the listings page
		// includes the caption

		global $conn, $config;
		$listingID = make_db_extra_safe($listingID);
		$name = make_db_extra_safe($name);
		$sql = "SELECT listingsDBElements.field_value, listingsFormElements.field_type, listingsFormElements.field_caption FROM listingsDBElements, listingsFormElements WHERE ((listingsDBElements.listing_id = $listingID) AND (listingsFormElements.field_name = listingsDBElements.field_name) AND (listingsDBElements.field_name = $name))";

		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_value = make_db_unsafe ($recordSet->fields[field_value]);
			$field_type = make_db_unsafe ($recordSet->fields[field_type]);
			$field_caption = make_db_unsafe ($recordSet->fields[field_caption]);
			if ($field_value != "")
			{

				if ($field_type == "select-multiple" OR $field_type == "option" OR $field_type == "checkbox")
				{
					// handle field types with multiple options
					echo "<b>$field_caption</b><br>";
					$feature_index_list = explode("||", $field_value);
					while (list($feature_list_Value, $feature_list_item) = each ($feature_index_list))
					{

						echo "$feature_list_item<br>";
					} // end while
				} // end if field type is a multiple type

				elseif ($field_type == "price")
				{
					$money_amount = international_num_format($field_value);
					echo "<br><b>$field_caption</b>: ".$field_value;//money_formats($money_amount);
				} // end elseif
				elseif ($field_type == "number")
				{
					echo "<br><b>$field_caption</b>: ".international_num_format($field_value);
				} // end elseif
				elseif ($field_type == "url")
				{
					echo "<br><b>$field_caption</b>: <a href=\"$field_value\" target=\"_new\">$field_value</a>";
				}
				elseif ($field_type == "email")
				{
					echo "<br><b>$field_caption</b>: <a href=\"mailto:$field_value\">$field_value</a>";
				}
				elseif ($field_type == "text" OR $field_type == "textarea")
				{
					if ($config[add_linefeeds] == "yes")
					{
						$field_value = nl2br($field_value); //replace returns with <br />
					} // end if
					echo "<br><b>$field_caption</b>: $field_value";
				}
				else
				{
					echo "<br><b>$field_caption</b>: $field_value";
				} // end else
			} // end if ($field_value != "")
			$recordSet->MoveNext();
		} // end while
	} // end renderSingleListingItem

	function renderSingleListingItemRaw($listingID, $name)
	{
		// renders a single item without any fancy formatting or anything.
		// useful if you need to plug a variable into something else...

		global $conn, $config;
		$listingID = make_db_extra_safe($listingID);
		$name = make_db_extra_safe($name);
		$sql = "SELECT listingsDBElements.field_value FROM listingsDBElements, listingsFormElements WHERE ((listingsDBElements.listing_id = $listingID) AND (listingsFormElements.field_name = listingsDBElements.field_name) AND (listingsDBElements.field_name = $name))";

		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_value = make_db_unsafe ($recordSet->fields[field_value]);
			echo $field_value;
		}
	} // end renderSingleListingItemRaw($listingID, $name)

	function renderSingleListingItemNoCaption($listingID, $name)
	{
		// renders a single item on the listings page
		// this time, without a caption, though...

		global $conn, $config;
		$listingID = make_db_extra_safe($listingID);
		$name = make_db_extra_safe($name);
		$sql = "SELECT listingsDBElements.field_value, listingsFormElements.field_type FROM listingsDBElements, listingsFormElements WHERE ((listingsDBElements.listing_id = $listingID) AND (listingsFormElements.field_name = listingsDBElements.field_name) AND (listingsDBElements.field_name = $name))";

		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_value = make_db_unsafe ($recordSet->fields[field_value]);
			$field_type = make_db_unsafe ($recordSet->fields[field_type]);
			if ($field_value != "")
			{

				if ($field_type == "select-multiple" OR $field_type == "option" OR $field_type == "checkbox")
				{
					// handle field types with multiple options
					$feature_index_list = explode("||", $field_value);
					while (list($feature_list_Value, $feature_list_item) = each ($feature_index_list))
					{

						echo "$feature_list_item<br>";
					} // end while
				} // end if field type is a multiple type

				elseif ($field_type == "price")
				{
					$Rentalsql = "SELECT field_value FROM listingsDBElements WHERE ((listingsDBElements.listing_id = $listingID) and (field_name = 'type'))";
					$RentalrecordSet = $conn->Execute($Rentalsql);
					if ($RentalrecordSet === false)
					{
						log_error($Rentalsql);
					}
					$prop_type = make_db_unsafe ($RentalrecordSet->fields[field_value]);
					if ($prop_type == 'Rental')
					{
						$money_amount = international_num_format($field_value);
						echo "<br><b>$field_caption $lang[per_week]</b>: ".$field_value;//money_formats($money_amount);
					}
					else
					{
					$money_amount = international_num_format($field_value);
					echo "<br><b>$field_caption</b>: ".money_formats($money_amount);
					}
				} // end elseif
				elseif ($field_type == "number")
				{
					echo "<br><b>$field_caption</b>: ".international_num_format($field_value);
				} // end elseif
				elseif ($field_type == "url")
				{
					echo "<a href=\"$field_value\" target=\"_new\">$field_value</a>";
				}
				elseif ($field_type == "email")
				{
					echo "<a href=\"mailto:$field_value\">$field_value</a>";
				}
				elseif ($field_type == "text" OR $field_type == "textarea")
				{
					if ($config[add_linefeeds] == "yes")
					{
						$field_value = nl2br($field_value); //replace returns with <br />
					} // end if
					echo $field_value;
				}
				else
				{
					echo "$field_value";
				} // end else
			} // end if ($field_value != "")
			$recordSet->MoveNext();
		} // end while
	} // end renderSingleListingItemNoCaption


	function renderTemplateArea($templateArea, $listingID)
	{
		// renders all the elements in a given template area on the listing pages
		global $conn, $config, $userID;
		$listingID = make_db_extra_safe($listingID);
		$templateArea = make_db_extra_safe($templateArea);
		$sql = "SELECT listingsDBElements.user_id, listingsDBElements.field_value, listingsFormElements.access_level, listingsFormElements.field_type, listingsFormElements.field_caption FROM listingsDBElements, listingsFormElements WHERE ((listingsDBElements.listing_id = $listingID) AND (listingsFormElements.field_name = listingsDBElements.field_name) AND (listingsFormElements.location = $templateArea)) ORDER BY listingsFormElements.rank ASC";
		//echo $sql;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_value = make_db_unsafe ($recordSet->fields[field_value]);
			$field_type = make_db_unsafe ($recordSet->fields[field_type]);
			$field_caption = make_db_unsafe ($recordSet->fields[field_caption]);
			$access_level = make_db_unsafe ($recordSet->fields[access_level]);
			$usr_id = make_db_unsafe ($recordSet->fields[user_id]);
			//echo $field_caption."-".$field_type."-".$field_value."<br>";
			if ($field_value != "" and ($access_level >= 1 or $usr_id == $userID))
			{
				if ($field_type == "select-multiple" OR $field_type == "option" OR $field_type == "checkbox")
				{
					// handle field types with multiple options
					echo "<br><b>$field_caption</b>";
					$feature_index_list = explode("||", $field_value);
					while (list($feature_list_Value, $feature_list_item) = each ($feature_index_list))
					{

						echo "<br>$feature_list_item";
					} // end while
				} // end if field type is a multiple type
				elseif ($field_type == "price")
				{
					$money_amount = international_num_format($field_value);
					echo "<br><b>$field_caption</b>: ".$field_value;//.money_formats($money_amount);
				} // end elseif
				elseif ($field_type == "number")
				{
					echo "<br><b>$field_caption</b>: ".international_num_format($field_value);
				} // end elseif
				elseif ($field_type == "url")
				{
					echo "<br><b>$field_caption</b>: <a href=\"$field_value\" target=\"_new\">$field_value</a>";
				}
				elseif ($field_type == "email")
				{
					echo "<br><b>$field_caption</b>: <a href=\"mailto:$field_value\">$field_value</a>";
				}
				elseif ($field_type == "text" OR $field_type == "textarea")
				{
					if ($config[add_linefeeds] == "yes")
					{
						$field_value = nl2br($field_value); //replace returns with <br />
					} // end if
					echo "<br><b>$field_caption</b>: $field_value";
				}
				else
				{
					echo "<br><b>$field_caption</b>: $field_value";
				} // end else
			} // end if ($field_value != "")
			$recordSet->MoveNext();
		} // end while
	} // end renderTemplateArea





	function renderTemplateAreaNoCaption($templateArea, $listingID)
	{
		// renders all the elements in a given template area on the listing pages
		// this time without the corresponding captions
		global $conn, $config;
		$listingID = make_db_extra_safe($listingID);
		$templateArea = make_db_extra_safe($templateArea);
		$sql = "SELECT listingsDBElements.field_value, listingsFormElements.field_type, listingsFormElements.field_caption FROM listingsDBElements, listingsFormElements WHERE ((listingsDBElements.listing_id = $listingID) AND (listingsFormElements.field_name = listingsDBElements.field_name) AND (listingsFormElements.location = $templateArea)) ORDER BY listingsFormElements.rank ASC";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_value = make_db_unsafe ($recordSet->fields[field_value]);
			$field_type = make_db_unsafe ($recordSet->fields[field_type]);
			$field_caption = make_db_unsafe ($recordSet->fields[field_caption]);
			if ($field_value != "")
			{
				if ($field_type == "select-multiple" OR $field_type == "option" OR $field_type == "checkbox")
				{
					// handle field types with multiple options
					$feature_index_list = explode("||", $field_value);
					while (list($feature_list_Value, $feature_list_item) = each ($feature_index_list))
					{

						echo "$feature_list_item<br>";
					} // end while
				} // end if field type is a multiple type
				elseif ($field_type == "price")
				{
					$money_amount = international_num_format($field_value);
					echo "<br><b>$field_caption</b>: ".money_formats($money_amount);
				} // end elseif
				elseif ($field_type == "number")
				{
					echo "<br><b>$field_caption</b>: ".international_num_format($field_value);
				} // end elseif
				elseif ($field_type == "url")
				{
					echo "<br><a href=\"$field_value\" target=\"_new\">$field_value</a>";
				}
				elseif ($field_type == "email")
				{
					echo "<br><a href=\"mailto:$field_value\">$field_value</a>";
				}
				elseif ($field_type == "text" OR $field_type == "textarea")
				{
					if ($config[add_linefeeds] == "yes")
					{
						$field_value = nl2br($field_value); //replace returns with <br />
					} // end if
					echo "<br>$field_value";
				}
				else
				{
					echo "<br>$field_value";
				} // end else

			} // end if ($field_value != "")

			$recordSet->MoveNext();
		} // end while
	} // end renderTemplateAreaNoCaption


	function getMainListingData($listingID)
	{
		// get the main data for a given listing
		global $conn, $lang;
		$listingID = make_db_extra_safe($listingID);
		$sql = "SELECT listingsDB.user_ID, listingsDB.Title, listingsDB.expiration, users.name FROM listingsDB, users WHERE ((listingsDB.ID = $listingID) AND (users.id = listingsDB.user_ID))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		// get main listings data
		while (!$recordSet->EOF)
		{
			$listing_user_ID = make_db_unsafe ($recordSet->fields[user_ID]);
			$listing_Title = make_db_unsafe ($recordSet->fields[Title]);
			$listing_expiration = make_db_unsafe ($recordSet->fields[Title]);
			$listing_user_name = make_db_unsafe ($recordSet->fields[name]);
			$recordSet->MoveNext();
		} // end while
		echo "<h3>$listing_Title</h3>";
		echo "<b>$lang[listed_by] ".show_profile($listing_user_ID)."</a></b>";
	} // function getMainListingData

	function getListingEmail($listingID)
	{
		// get the email address for the person who posted a listing
		global $conn, $lang;
		$listingID = make_db_extra_safe($listingID);
		$sql = "SELECT UserDB.email as emailAddress FROM listingsDB, users as UserDB WHERE ((listingsDB.ID = $listingID) AND (UserDB.id = listingsDB.user_ID))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		// return the email address
		while (!$recordSet->EOF)
		{
			$listing_emailAddress = make_db_unsafe ($recordSet->fields[emailAddress]);
			$recordSet->MoveNext();
		} // end while
		if ($listing_emailAddress){
			echo "<b>$lang[user_email]:</b> <a href=\"mailto:$listing_emailAddress\">$listing_emailAddress</a><br>";
		}
	} // function getMainListingData

	function hitcount($listingID)
	{
		// counts hits to a given listing
		global $conn, $lang;
		$listingID = make_db_extra_safe($listingID);
		$sql = "UPDATE listingsDB SET hitcount=hitcount+1 WHERE ID=$listingID";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		$sql = "SELECT hitcount FROM listingsDB WHERE ID=$listingID";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$hitcount = $recordSet->fields[hitcount];
			echo "$lang[this_listing_has_been_viewed] <b>$hitcount</b> $lang[times].";
			$recordSet->MoveNext();
		} // end while
	} // end function hitcount

	function renderUserPicOnListingsPage($listingID)
	{
		if ($listingID != "")
		{
			// grabs the information for a given user
			// and displays it on a listings page

			global $conn, $config, $lang;

			$listingID = make_db_extra_safe($listingID);
			$sql = "SELECT UserDB.ID, UserDB.user_name FROM listingsDB, UserDB WHERE ((listingsDB.ID = $listingID) AND (UserDB.ID = listingsDB.user_ID))";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}

			// get main listings data
			while (!$recordSet->EOF)
			{
				$listing_user_ID = make_db_unsafe ($recordSet->fields[ID]);
				$listing_user_name = make_db_unsafe ($recordSet->fields[user_name]);
				$recordSet->MoveNext();
			} // end while

			$user = $listing_user_ID;
			// grab the images
			$sql = "SELECT ID, caption, file_name, thumb_file_name FROM userImages WHERE (user_id = $user) ORDER BY rank";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$num_images = $recordSet->RecordCount();
			if ($num_images > 0)
			{
				echo "<table><td width=\"$style[image_column_width]\" valign=\"top\" class=\"row_main\" align=\"center\">";
					$caption = make_db_unsafe ($recordSet->fields[caption]);
					$thumb_file_name = make_db_unsafe ($recordSet->fields[thumb_file_name]);
					$file_name = make_db_unsafe ($recordSet->fields[file_name]);
					$imageID = make_db_unsafe ($recordSet->fields[ID]);

					// gotta grab the image size
					$imagedata = GetImageSize("$config[user_upload_path]/$thumb_file_name");
					$imagewidth = $imagedata[0];
					$imageheight = $imagedata[1];
					$shrinkage = $config[thumbnail_width]/$imagewidth;
					$displaywidth = $imagewidth * $shrinkage;
					$displayheight = $imageheight * $shrinkage;

					echo "<a href=\"viewimage.php?imageID=$imageID&type=userimage\"> ";
					echo "<img src=\"$config[user_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\"></a><br> ";
					echo "<b>$caption</b><br><br>";
				echo "</td></table>";
			} // end ($num_images > 0)
		}
	}

	function renderUserInfoOnListingsPage($listingID)
	{
		if ($listingID != "")
		{
			// grabs the information for a given user
			// and displays it on a listings page

			global $conn, $config, $lang;

			$listingID = make_db_extra_safe($listingID);
			$sql = "SELECT UserDB.id as ID, UserDB.name as user_name FROM listingsDB, users as UserDB WHERE ((listingsDB.ID = $listingID) AND (UserDB.id = listingsDB.user_ID))";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}

			// get main listings data
			while (!$recordSet->EOF)
			{
				$listing_user_ID = make_db_unsafe ($recordSet->fields[ID]);
				$listing_user_name = make_db_unsafe ($recordSet->fields[user_name]);
				$recordSet->MoveNext();
			} // end while
			//echo "<b>$lang[listed_by] <a href=\"userview.php?user=$listing_user_ID\">$listing_user_name</a></b>";
			echo "<b>$lang[listed_by] ".show_profile($listing_user_ID);

			if (false && $listing_user_ID != "")
			{
				$sql = "SELECT UserDBElements.field_value, userFormElements.field_type, userFormElements.field_caption FROM UserDBElements, userFormElements WHERE ((UserDBElements.user_id = $listing_user_ID) AND (UserDBElements.field_name = userFormElements.field_name)) ORDER BY userFormElements.rank ASC";
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false)
				{
					log_error($sql);
				}
				while (!$recordSet->EOF)
				{
					$field_value = make_db_unsafe ($recordSet->fields[field_value]);
					$field_type = make_db_unsafe ($recordSet->fields[field_type]);
					$field_caption = make_db_unsafe ($recordSet->fields[field_caption]);
					if ($field_value != "")
					{

						if ($field_type == "select-multiple" OR $field_type == "option" OR $field_type == "checkbox")
						{
							// handle field types with multiple options
							echo "<b>$field_caption</b><br>";
							$feature_index_list = explode("||", $field_value);
							while (list($feature_list_Value, $feature_list_item) = each ($feature_index_list))
							{
								echo "$feature_list_item<br>";
							} // end while
						} // end if field type is a multiple type

						elseif ($field_type == "price")
						{
							$money_amount = international_num_format($field_value);
							echo "<br><b>$field_caption</b>: ".money_formats($money_amount);
						} // end elseif
						elseif ($field_type == "number")
						{
							echo "<br><b>$field_caption</b>: ".international_num_format($field_value);
						} // end elseif
						elseif ($field_type == "url")
						{
						echo "<br><b>$field_caption</b>: <a href=\"$field_value\" target=\"_new\">$field_value</a>";
						}
						elseif ($field_type == "email")
						{
							echo "<br><b>$field_caption</b>: <a href=\"mailto:$field_value\">$field_value</a>";
						}
						else
						{
							if ($config[add_linefeeds] == "yes")
							{
								$field_value = nl2br($field_value); //replace returns with <br />
							} // end if
							echo "<br><b>$field_caption</b>: $field_value";
						} // end else

					} // end if ($field_value != "")

						$recordSet->MoveNext();
				} // end while
			} // end if ($listing_user_ID != "")
		} // end ($listingID != "")
	} // end renderUserInfo



	function renderFeaturedListingsVertical($num_of_listings)
	{
		echo "<table><tr>";
		// shows the images connected to a given image

		global $conn, $lang, $config, $style;
		// grab the images
		$listingID = make_db_extra_safe($listingID);
		$sql = "SELECT ID, Title FROM listingsDB WHERE (featured = 'yes')";
		$recordSet = $conn->SelectLimit($sql, $num_of_listings, 0 );
		if ($recordSet === false)
		{
			log_error($sql);
		}

		$returned_num_listings = $recordSet->RecordCount();
		if ($returned_num_listings > 0)
			{
			echo "<td width=\"$style[image_column_width]\" valign=\"top\" class=\"row_main\" align=\"center\">";
			echo "<b>$lang[featured_listings]</b><br><hr width=\"75%\">";
			while (!$recordSet->EOF)
				{
					$Title = make_db_unsafe ($recordSet->fields[Title]);
					$ID = make_db_unsafe ($recordSet->fields[ID]);

					$sql2 = "SELECT thumb_file_name FROM listingsImages WHERE (listing_id = $ID) ORDER BY rank";
					$recordSet2 = $conn->SelectLimit($sql2, 1, 0 );
					if ($recordSet2 === false)
					{
						log_error($sql);
					}
					while (!$recordSet2->EOF)
					{
						$thumb_file_name = make_db_unsafe ($recordSet2->fields[thumb_file_name]);

						// gotta grab the image size
						$imagedata = GetImageSize("$config[listings_upload_path]/$thumb_file_name");
						$imagewidth = $imagedata[0];
						$imageheight = $imagedata[1];
						$shrinkage = $config[thumbnail_width]/$imagewidth;
						$displaywidth = $imagewidth * $shrinkage;
						$displayheight = $imageheight * $shrinkage;

						echo "<a href=\"listingview.php?listingID=$ID\"> ";

						echo "<img src=\"$config[listings_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\" alt=\"$lang[click_to_learn_more]\"><br> ";
						echo "<b>$Title</b></a><br><br>";
						$recordSet2->MoveNext();
					} // end while
					$recordSet->MoveNext();
				} // end while
			echo "</td>";
		} // end if ($num_images > 0)
		echo "</tr></table>";
	} // end function renderFeaturedListingsVertical



	// RENDER THE USER PAGE ELEMENTS


	function renderUserImages($user)
	{
		// grabs the listings for a given user
		global $conn, $lang, $config, $style;
		$user = make_db_extra_safe($user);
		// grab the images
		$sql = "SELECT ID, caption, file_name, thumb_file_name FROM userImages WHERE (user_id = $user) ORDER BY rank";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		$num_images = $recordSet->RecordCount();
		if ($num_images > 0)
		{
			echo "<td width=\"$style[image_column_width]\" valign=\"top\" class=\"row_main\" align=\"center\">";
			echo "<b>$lang[images]</b><br><hr width=\"75%\">";
			while (!$recordSet->EOF)
			{
				$caption = make_db_unsafe ($recordSet->fields[caption]);
				$thumb_file_name = make_db_unsafe ($recordSet->fields[thumb_file_name]);
				$file_name = make_db_unsafe ($recordSet->fields[file_name]);
				$imageID = make_db_unsafe ($recordSet->fields[ID]);

				// gotta grab the image size
				$imagedata = GetImageSize("$config[user_upload_path]/$thumb_file_name");
				$imagewidth = $imagedata[0];
				$imageheight = $imagedata[1];
				$shrinkage = $config[thumbnail_width]/$imagewidth;
				$displaywidth = $imagewidth * $shrinkage;
				$displayheight = $imageheight * $shrinkage;

				echo "<a href=\"viewimage.php?imageID=$imageID&type=userimage\"> ";
				echo "<img src=\"$config[user_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\"></a><br> ";
				echo "<b>$caption</b><br><br>";
				$recordSet->MoveNext();
			} // end while
			echo "</td>";
		} // end ($num_images > 0)
	} // end function renderUserImages



	function renderUserInfo($user)
	{
		// grabs the information for a given user
		global $conn, $config;
		$user = make_db_extra_safe($user);

		$sql = "SELECT UserDBElements.field_value, userFormElements.field_type, userFormElements.field_caption FROM UserDBElements, userFormElements WHERE ((UserDBElements.user_id = $user) AND (UserDBElements.field_name = userFormElements.field_name)) ORDER BY userFormElements.rank ASC";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_value = make_db_unsafe ($recordSet->fields[field_value]);
			$field_type = make_db_unsafe ($recordSet->fields[field_type]);
			$field_caption = make_db_unsafe ($recordSet->fields[field_caption]);
			if ($field_value != "")
			{
				if ($field_type == "select-multiple" OR $field_type == "option" OR $field_type == "checkbox")
				{
					// handle field types with multiple options
					echo "<b>$field_caption</b><br>";
					$feature_index_list = explode("||", $field_value);
					while (list($feature_list_Value, $feature_list_item) = each ($feature_index_list))
					{
						echo "$feature_list_item<br>";
					} // end while
				} // end if field type is a multiple typ
				elseif ($field_type == "price")
				{
					$money_amount = international_num_format($field_value);
					echo "<br><b>$field_caption</b>: ".money_formats($money_amount);
				} // end elseif
				elseif ($field_type == "number")
				{
					echo "<br><b>$field_caption</b>: ".international_num_format($field_value);
				} // end elseif
				elseif ($field_type == "url")
				{
					echo "<br><b>$field_caption</b>: <a href=\"$field_value\" target=\"_new\">$field_value</a>";
				}
				elseif ($field_type == "email")
				{
					echo "<br><b>$field_caption</b>: <a href=\"mailto:$field_value\">$field_value</a>";
				}
				else
				{
					if ($config[add_linefeeds] == "yes")
					{
						$field_value = nl2br($field_value); //replace returns with <br />
					} // end if
					echo "<br><b>$field_caption</b>: $field_value";
				} // end else

			} // end if ($field_value != "")

			$recordSet->MoveNext();
		} // end while
	} // end renderUserInfo




	function getMainUserData($user)
	{
		// grabs the main info for a given user

		global $conn, $lang;

		$user = make_db_extra_safe($user);
		$sql = "SELECT name as user_name, email as emailAddress FROM users as UserDB WHERE (id = $user)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		// get main listings data
		while (!$recordSet->EOF)
		{
			$name = make_db_unsafe ($recordSet->fields[user_name]);
			$emailAddress = make_db_unsafe ($recordSet->fields[emailAddress]);
			$recordSet->MoveNext();
		} // end while
		echo "<h3>$name</h3>";
	} // function getMainListingData

	function getUserEmail($user)
	{
		// grabs the main info for a given user

		global $conn, $lang;

		$user = make_db_extra_safe($user);
		$sql = "SELECT emailAddress FROM UserDB WHERE (ID = $user)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		// get main listings data
		while (!$recordSet->EOF)
		{
			$emailAddress = make_db_unsafe ($recordSet->fields[emailAddress]);
			$recordSet->MoveNext();
		} // end while
		echo "<b>$lang[user_email]:</b> <a href=\"mailto:$emailAddress\">$emailAddress</a>";
	} // function getMainListingData

	function userHitcount($user)
	{
		// hit counter for user listings

		global $conn, $lang;
		$user = make_db_extra_safe($user);
		$sql = "UPDATE UserDB SET hitcount=hitcount+1 WHERE ID=$user";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		$sql = "SELECT hitcount FROM UserDB WHERE ID=$user";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$hitcount = $recordSet->fields[hitcount];
			echo "$lang[this_user_has_been_viewed] <b>$hitcount</b> $lang[times].";
			$recordSet->MoveNext();
		} // end while
	} // end function userHitcount


	function userListings($user)
	{
		// produces the rest of the listings for users

		global $conn, $lang;
		$user = make_db_extra_safe($user);
		echo "<b>Other listings from this user:</b><ul>";
		$sql = "SELECT ID, Title FROM listingsDB WHERE user_ID = $user";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$ID = $recordSet->fields[ID];
			$Title = make_db_unsafe ($recordSet->fields[Title]);
			echo "<li> <a href=\"listingview.php?listingID=$ID\">$Title</a></li>";
			$recordSet->MoveNext();
		}
		echo "</ul>";
	} // end function userListings





	// BROWSING PAGE ELEMENTS

	function browse_all_listings()
	{
		global $conn, $config,$lang;
		$sql = "SELECT listingsDB.Title FROM listingsDB WHERE active = 'yes'";
		if ($config[use_expiration] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		$num_listings = $recordSet->RecordCount();
		echo "<a href=\"listing_browse.php\">".$lang['menu_user_browse_listings']." ($num_listings)</a>";
	} // end function browse_all_listings

	function searchbox_select ($browse_caption, $browse_field_name)
	{
		// builds a multiple choice select box for any given item you want
		// to let users search by
		global $conn, $config ,$userID, $lang;
		echo "<tr><td align=\"right\"><b>$browse_caption</b></td>";
		echo "<td align=\"left\"><select style='width:200px' name=\"$browse_field_name"."[]\" size=\"5\" multiple>";
		$sql = "SELECT listingsDBElements.field_value, listingsDB.ID, count(field_value) AS num_type FROM listingsDBElements, listingsDB WHERE listingsDBElements.field_name = '$browse_field_name' AND listingsDB.active = 'yes' AND listingsDBElements.listing_id = listingsDB.ID ";
		if ($config[use_expiration] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY listingsDBElements.field_value ORDER BY listingsDBElements.field_value+0, listingsDBElements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields[field_value]);
			$num_type = $recordSet->fields[num_type];
			$field_disp = ($field_output=="")?$lang["not_selected"]:$field_output;
			echo "<option  value=\"$field_output\">$field_disp ($num_type)</option>";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr>";
	} // end function searchbox_select

	function searchbox_select_vert ($browse_caption, $browse_field_name)
	{
		// builds a multiple choice select box for any given item you want
		// to let users search by
		global $conn, $config,$userID, $lang;
		echo "<table><tr><td align=\"center\"><b>$browse_caption</b></td></tr>";
		echo "<tr><td align=\"center\"><select style='width:200px' name=\"$browse_field_name"."[]\" size=\"5\" multiple>";
		$sql = "SELECT listingsDBElements.field_value, listingsDB.ID, count(field_value) AS num_type FROM listingsDBElements, listingsDB WHERE listingsDBElements.field_name = '$browse_field_name' AND (listingsDB.active = 'yes' or listingsDB.user_ID = $userID)  AND listingsDBElements.listing_id = listingsDB.ID ";
		if ($config[use_expiration] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY listingsDBElements.field_value ORDER BY listingsDBElements.field_value+0, listingsDBElements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields[field_value]);
			$num_type = $recordSet->fields[num_type];
			$field_disp = ($field_output=="")?$lang["not_selected"]:$field_output;
			echo "<option value=\"$field_output\">$field_disp ($num_type)";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr></table>";
		} // end function searchbox_select_vert

	function searchbox_pulldown ($browse_caption, $browse_field_name,$field_elements = "")
	{
		// builds a pulldown menu for any given item you want
		// to let users search by
		global $conn, $config,$userID,$lang;
		echo "<tr><td align=\"right\"><b>$browse_caption</b></td>";
		echo "<td align=\"left\"><select style='width:200px' name=\"$browse_field_name\"><option></option>";
		if (strpos(" ".$field_elements,"{")=="1"){
				$s = str_replace(array("}","{"),"",$field_elements);
				$s = explode(".",$s);
				$tablename = $s[0];$fieldname=$s[1];
				$ssql = "select ID,$fieldname from $tablename where Status=1 and user_ID = $userID order by SortOrder,$fieldname";
				$recordS = $conn->Execute($ssql);
				while (!$recordS->EOF){
					echo "<option value=\"".$recordS->fields["ID"]."\">".$recordS->fields[$fieldname];
					$recordS->MoveNext();
				}
		}
		echo "</select></td></tr>";
	} // end function searchbox_pulldown

	function searchbox_pulldown_vert ($browse_caption, $browse_field_name)
	{
		// builds a pulldown menu for any given item you want
		// to let users search by
		global $conn, $config,$userID,$lang;
		echo "<table><tr><td align=\"center\"><b>$browse_caption</b></td></tr>";
		echo "<tr><td align=\"center\"><select style='width:200px' name=\"$browse_field_name\"><option></option>";
		$sql = "SELECT listingsDBElements.field_value, listingsDB.ID, count(field_value) AS num_type FROM listingsDBElements, listingsDB WHERE listingsDBElements.field_name = '$browse_field_name' AND (listingsDB.active = 'yes' or listingsDB.user_ID = $userID) AND listingsDBElements.listing_id = listingsDB.ID ";
		if ($config[use_expiration] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY listingsDBElements.field_value ORDER BY listingsDBElements.field_value+0, listingsDBElements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields[field_value]);
			$num_type = $recordSet->fields[num_type];
			$field_disp = ($field_output=="")?$lang["not_selected"]:$field_output;
			echo "<option value=\"$field_output\">$field_disp ($num_type)";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr>";
	} // end function searchbox_pulldown_vert


	function searchbox_checkbox ($browse_caption, $browse_field_name)
	{
		// builds a series of checkboxes for any given item you want
		// to let users search by
		global $conn, $config,$userID;
		echo "<tr><td align=\"right\"><b>$browse_caption</b></td>";
		echo "<td align=\"left\">";
		$sql = "SELECT listingsDBElements.field_value, listingsDB.ID, count(field_value) AS num_type FROM listingsDBElements, listingsDB WHERE listingsDBElements.field_name = '$browse_field_name' AND (listingsDB.active = 'yes' or listingsDB.user_ID = $userID) AND listingsDBElements.listing_id = listingsDB.ID ";
		if ($config[use_expiration] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY listingsDBElements.field_value ORDER BY listingsDBElements.field_value+0, listingsDBElements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields[field_value]);
			$num_type = $recordSet->fields[num_type];
			echo "<input type=\"checkbox\" name=\"$browse_field_name"."[]\" value=\"$field_output\">$field_output ($num_type)<br>";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr>";
	} // end function searchbox_checkbox

	function searchbox_checkbox_vert ($browse_caption, $browse_field_name)
	{
		// builds a series of checkboxes for any given item you want
		// to let users search by
		global $conn, $config,$userID;
		echo "<table><tr><td align=\"center\"><b>$browse_caption</b></td></tr>";
		echo "<tr><td align=\"center\">";
		$sql = "SELECT listingsDBElements.field_value, listingsDB.ID, count(field_value) AS num_type FROM listingsDBElements, listingsDB WHERE listingsDBElements.field_name = '$browse_field_name' AND (listingsDB.active = 'yes' or listingsDB.user_ID = $userID) AND listingsDBElements.listing_id = listingsDB.ID ";
		if ($config[use_expiration] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY listingsDBElements.field_value ORDER BY listingsDBElements.field_value+0, listingsDBElements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields[field_value]);
			$num_type = $recordSet->fields[num_type];
			echo "<input type=\"checkbox\" name=\"$browse_field_name"."[]\" value=\"$field_output\">$field_output ($num_type)<br>";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr></table>";
	} // end function searchbox_checkbox_vert

	function searchbox_option ($browse_caption, $browse_field_name)
	{
		// builds a pulldown menu for any given item you want
		// to let users search by
		global $conn, $config,$userID;
		echo "<tr><td align=\"right\"><b>$browse_caption</b></td>";
		echo "<td align=\"left\">";
		$sql = "SELECT listingsDBElements.field_value, listingsDB.ID, count(field_value) AS num_type FROM listingsDBElements, listingsDB WHERE listingsDBElements.field_name = '$browse_field_name' AND (listingsDB.active = 'yes' or listingsDB.user_ID = $userID) AND listingsDBElements.listing_id = listingsDB.ID ";
		if ($config[use_expiration] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY listingsDBElements.field_value ORDER BY listingsDBElements.field_value+0, listingsDBElements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields[field_value]);
			$num_type = $recordSet->fields[num_type];
			echo "<input type=\"radio\" name=\"$browse_field_name\" value=\"$field_output\">$field_output ($num_type)<br>";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr>";
	} // end function searchbox_option


	function searchbox_option_vert ($browse_caption, $browse_field_name)
	{
		// builds a pulldown menu for any given item you want
		// to let users search by
		global $conn, $config,$userID;
		echo "<table><tr><td align=\"center\"><b>$browse_caption</b></td></tr>";
		echo "<tr><td align=\"center\">";
		$sql = "SELECT listingsDBElements.field_value, listingsDB.ID, count(field_value) AS num_type FROM listingsDBElements, listingsDB WHERE listingsDBElements.field_name = '$browse_field_name' AND (listingsDB.active = 'yes' or listingsDB.user_ID = $userID) AND listingsDBElements.listing_id = listingsDB.ID ";
		if ($config[use_expiration] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY listingsDBElements.field_value ORDER BY listingsDBElements.field_value+0, listingsDBElements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields[field_value]);
			$num_type = $recordSet->fields[num_type];
			echo "<input type=\"radio\" name=\"$browse_field_name\" value=\"$field_output\">$field_output ($num_type)<br>";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr></table>";
	} // end function searchbox_option_vert

	
	function searchbox_text ($browse_caption, $browse_field_name)
	{
		// builds a min/max combo box
		// to let users search by
		global $conn, $config,$lang;
		echo "<tr><td align=\"right\"><b>$browse_caption</b></td>";
		echo "<td align=\"left\">";
			/*
			$sql = "SELECT search_step FROM listingsFormElements WHERE field_name = '$browse_field_name'";
			// Get max, min and step
			$step = $conn->getOne($sql);
			$max = $conn->GetOne("select min(field_value +0) from listingsDBElements where field_name = '$browse_field_name'");
			$min = round($conn->GetOne("select max(field_value +0) from listingsDBElements where field_name = '$browse_field_name'"), -3);
		//	echo "min $min max $max step $step";
			if ($min > $max) 
			{
				$temp = $min;
				$min = $max;
				$max = $temp;
			}
			//$max = $max + $step;
			echo  $lang[from]." <select name=\"{$browse_field_name}-min\">\n";
			$options = "<option></option>\n";
			for ($i = $min; $i <= $max; $i += $step)
			{
				$options .= "\t\t<option>$i</option>\n";
			}
			if ($i - $step < $max)
			{
				$i = $max;
				$options .= "\t\t<option>$i</option>\n";
			}
			echo $options . '</select>';
			echo $lang[to]." <select name=\"{$browse_field_name}-max\">$options</select>";
			*/
			echo  " <input size=20 style='width:200px' name=\"{$browse_field_name}\">\n";
			echo "\n\t</td>\n</tr>\n";
	} // end function
		
	function searchbox_minmax ($browse_caption, $browse_field_name)
	{
		// builds a min/max combo box
		// to let users search by
		global $conn, $config,$lang;
		echo "<tr><td align=\"right\"><b>$browse_caption</b></td>";
		echo "<td align=\"left\">";
			/*
			$sql = "SELECT search_step FROM listingsFormElements WHERE field_name = '$browse_field_name'";
			// Get max, min and step
			$step = $conn->getOne($sql);
			$max = $conn->GetOne("select min(field_value +0) from listingsDBElements where field_name = '$browse_field_name'");
			$min = round($conn->GetOne("select max(field_value +0) from listingsDBElements where field_name = '$browse_field_name'"), -3);
		//	echo "min $min max $max step $step";
			if ($min > $max) 
			{
				$temp = $min;
				$min = $max;
				$max = $temp;
			}
			//$max = $max + $step;
			echo  $lang[from]." <select name=\"{$browse_field_name}-min\">\n";
			$options = "<option></option>\n";
			for ($i = $min; $i <= $max; $i += $step)
			{
				$options .= "\t\t<option>$i</option>\n";
			}
			if ($i - $step < $max)
			{
				$i = $max;
				$options .= "\t\t<option>$i</option>\n";
			}
			echo $options . '</select>';
			echo $lang[to]." <select name=\"{$browse_field_name}-max\">$options</select>";
			*/
			echo  $lang[from]." <input size=5 name=\"{$browse_field_name}-min\">\n";
			echo $lang[to]." <input size=5 name=\"{$browse_field_name}-max\">";
			echo "\n\t</td>\n</tr>\n";
	} // end function

	function searchbox_daterange ($caption, $field)
	{
		global $conn, $config,$from;
		static $js_added;
		if (!$js_added)
		{
			// add date
			echo '<script src="date.js"></script>';
			$js_added = true;
		}
		echo "<tr><td align=\"right\"><b>$caption</b></td>\n\t<td align=\"left\">";
		echo $lang[from]." <input type=\"text\" name=\"{$field}-mindate\" onKeyUp=\"Javascript: dateMask(this,event);\"> <BR>to
			<input type=\"text\" name=\"{$field}-maxdate\" onKeyUp=\"Javascript: dateMask(this,event);\">";
		echo "\n\t</td>\n</tr>\n";
	}

	function searchbox_optionlist ($caption, $field)
	{
		global $conn;
		// start the row
		echo "<tr><td align=\"right\"><b>$caption</b></td>";
		echo "<td align=\"left\">";
		$r = $conn->getOne("select f.field_elements from listingsformelements f where field_name = '$field'");
		echo "<select style='width:200px' name=\"{$field}[]\" multiple size=6>";
		foreach (explode('||', $r) as $f) 
		{
			$f = htmlspecialchars($f);
			echo "<option>$f</option>";
		}
		echo "\n\t</td>\n</tr>\n";
	}

	function latestListings($num_of_listings)
	{
		// builds a list of X number of latest listings
		global $conn,$userID;
		echo "<ul>";
		$sql = "SELECT ID, Title FROM listingsDB WHERE (listingsDB.active = 'yes' or listingsDB.user_ID = $userID) ORDER BY creation_date DESC";
		$recordSet = $conn->SelectLimit($sql, $num_of_listings, 0);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$ID = make_db_unsafe ($recordSet->fields[ID]);
			$Title = make_db_unsafe ($recordSet->fields[Title]);
			echo "<li> <a href=\"listingview.php?listingID=$ID\">$Title</a>";
			$recordSet->MoveNext();
		} // end while
		echo "</ul>";
	} // end function latestListings
	
	function rental_minmax ($browse_caption, $browse_field_name)
	{
		// builds a min/max combo box
		// to let users search by
		global $conn, $config, $lang;
		echo "<tr><td align=\"right\"><b>$browse_caption $lang[per_week]</b></td>";
		echo "<td align=\"left\">";

			// Get max, min and step
			$step =$config[rental_step];
			$max = $config[max_rental_price];
			$min = $config[min_rental_price];
		//	echo "min $min max $max step $step";
			if ($min > $max)
			{
				$temp = $min;
				$min = $max;
				$max = $temp;
			}
			echo "from <select name=\"{$browse_field_name}-min\">\n";
			$options = "<option></option>\n";
			for ($i = $min; $i <= $max; $i += $step)
			{
				$options .= "\t\t<option>$i</option>\n";
			}
			echo $options . '</select>';
			echo " to <select name=\"{$browse_field_name}-max\">$options</select>";
			echo "\n\t</td>\n</tr>\n";
	} // end function
	function renderFeaturedListingsHorizontal($num_of_listings)
	{
		// shows the images connected to a given image

		global $conn, $lang, $config, $style;
		// grab the images
		$listingID = make_db_extra_safe($listingID);
		//$seed=srand((double) microtime()*1000000);
		$sql = "SELECT ID, Title FROM listingsDB WHERE (featured = 'yes') ORDER BY RAND()";
		$recordSet = $conn->SelectLimit($sql, $num_of_listings, 0 );
		if ($recordSet === false) log_error($sql);

		$returned_num_listings = $recordSet->RecordCount();
		if ($returned_num_listings > 0)
		{

			print "<table border='0' width=\"50%\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">";
			$numcols = 3;
			$count = 1;
			print "<tr>";

			while (!$recordSet->EOF)
			{
				$Title = make_db_unsafe ($recordSet->fields[Title]);
				$ID = make_db_unsafe ($recordSet->fields[ID]);
				// GRAB THE LISTINGS IMAGE
				$sql2 = "SELECT thumb_file_name FROM listingsImages WHERE listing_id = $ID ORDER BY rank";
				$recordSet2 = $conn->SelectLimit($sql2, 1, 0);
				if ($recordSet2 === false) log_error($sql2);
				$num_images = $recordSet2->RecordCount();
				if ($num_images == 0)
				{
					if ($config[show_no_photo] == "yes")
					{
						echo "<td class=\"search_row_$count\" align=\"left\"><img src=\"images/nophoto.gif\" border=\"1\"></td>";
					}
					else
					{
						echo "<td class=\"search_row_$count\">&nbsp;</td>";
					}
				}
				while (!$recordSet2->EOF)
				{
					$thumb_file_name = make_db_unsafe ($recordSet2->fields[thumb_file_name]);
					if ($thumb_file_name != "")
					{

						// GOTTA GRAB THE IMAGE SIZE
						$imagedata = GetImageSize("$config[listings_upload_path]/$thumb_file_name");
						$imagewidth = $imagedata[0];
						$imageheight = $imagedata[1];
						$shrinkage = $config[thumbnail_width]/$imagewidth;
						$displaywidth = $imagewidth * $shrinkage;
						$displayheight = $imageheight * $shrinkage;

						// $sql2 = "SELECT thumb_file_name FROM listingsImages WHERE (listing_id = $ID) ORDER BY rank";
						// $recordSet2 = $conn->SelectLimit($sql2, 1, 0 );
						// if ($recordSet2 === false) log_error($sql);
						// {
						// while (!$recordSet2->EOF)
						// {
						// $thumb_file_name = make_db_unsafe ($recordSet2->fields[thumb_file_name]);
						//
						// // gotta grab the image size
						// $imagedata = GetImageSize("$config[listings_upload_path]/$thumb_file_name");
						// $imagewidth = $imagedata[0];
						// $imageheight = $imagedata[1];
						// $shrinkage = $config[thumbnail_width]/$imagewidth;
						// $displaywidth = $imagewidth * $shrinkage;
						// $displayheight = $imageheight * $shrinkage;


						$recordSet2->MoveNext();
					} // end while
					$recordSet->MoveNext();
				} // end while
				if ($count % $numcols ==0)
				{
					print "<td align=\"center\"> <a href=\"listingview.php?listingID=$ID\"> ";
					print "<div align=\"center\">  <br><img src=\"$config[listings_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\" alt=\"$lang[click_to_learn_more]\" border=1><br> ";
					echo "&nbsp;&nbsp;<b>$Title</b></a></div></td></tr><tr>";
					}else{
					print "<td align=\"center\"> <a href=\"listingview.php?listingID=$ID\"> ";
					print "<div align=\"center\">  <br>
					<img src=\"$config[listings_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\" alt=\"$lang[click_to_learn_more]\" border=1><br> ";
					echo "&nbsp;&nbsp;<b>$Title</b></a></div></td>";
				} //end if
				$count++;
			} // end if ($count%)
			print "</tr></table>";
		} //end if
	} // end function renderFeaturedListingsVertical
?>