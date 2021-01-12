<?php


	include("include/common.php");
	if (!loginCheck('User'))exit;
	global $action, $id, $cur_page, $conn, $lang, $config, $HTTP_GET_VARS;
	include("$config[template_path]/admin_top.html");
	$debug_GET = True;
	$guidestring = "";
	$guidestring_with_sort = "";

	// Save GET
	foreach ($_GET as $k => $v)
	{
		if ($v && $k != 'cur_page' && $k != 'PHPSESSID' && $k != 'sortby')
		{
			$guidestring .= '&amp;' . urlencode("$k") . '=' . urlencode("$v");
		}
	}

	// START BY SETTING UP THE TABLE OF ALL POSSIBLE LISTINGS
	// while this may seem crazy at first, it actually is reasonably efficient, especially
	// considering the limitations of mysql and the lack of subqueries.
	// basically, it works by the process of elimination...

	$sql = "drop table IF EXISTS temp";
	$recordSet = $conn->Execute($sql);
	
	if ($recordSet === false)
	{
		log_error($sql);
	}

	$sql = "CREATE TEMPORARY TABLE temp SELECT listingsDB.ID, listingsDB.Title, users.name as Owner, listingsDB.creation_date, listingsDBElements.field_name, listingsDBElements.field_value FROM listingsDB, listingsDBElements, users WHERE (users.id = listingsDB.user_ID and listingsDBElements.listing_id = listingsDB.ID) AND ";
	
	if ($config[use_expiration] == "yes")
	{
		$sql .= "(listingsDB.expiration > ".$conn->DBDate(time()).") AND ";
	}
	if (isset($userid) and $userid!='' and $userid!=-1){
		$sql .= "  listingsDB.user_ID = $userid AND ";
		$sql .= " (listingsDB.active = 'yes' or listingsDB.user_ID = '$userID')";
	}
	elseif($userid==-1){
		$sql .= "  listingsDB.user_ID <> $userID AND listingsDB.user_ID <> 0 AND";
		$sql .= " (listingsDB.active = 'yes')";
	}
	else{
		$sql .= " (listingsDB.active = 'yes' or listingsDB.user_ID = '$userID')";
	}
	

	//echo $sql;
	$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		
		
	reset ($HTTP_GET_VARS);
	foreach ($_GET as $ElementIndexValue => $ElementContents) {
		if ($ElementIndexValue == "sortby")
		{
			$guidestring_with_sort = "$ElementIndexValue=$ElementContents";
		}
		elseif ($ElementIndexValue == "cur_page")
		{
			// do nothing
		}
		elseif ($ElementIndexValue == "userid")
		{
			// do nothing
		}
		elseif ($ElementIndexValue == "PHPSESSID")
		{
			// do nothing
		}
		elseif ($ElementIndexValue == "imagesOnly")
		{
			$guidestring .= "$ElementIndexValue=$ElementContents&amp;";
			if ($ElementContents == "yes")
			{
				$whilecount = 0;
				$delete_string = "DELETE FROM temp WHERE (1=1)";
				// the 1=1 is a dumb sql trick to deal with the code below ... it works, but you can ignore it
				$sql = "SELECT temp.ID, COUNT(listingsImages.file_name) AS imageCount FROM listingsImages, temp WHERE (listingsImages.listing_id = temp.ID) GROUP BY listingsImages.listing_id";
				$recordSet = $conn->Execute($sql);
					if ($recordSet === false)
					{
						log_error($sql);
					}
				while (!$recordSet->EOF)
				{
					$whilecount = $whilecount + 1;
					$listingID = $recordSet->fields[ID];
					$imageCount = $recordSet->fields[imageCount];
					$delete_string .= " AND ";
					$delete_string .= "(ID <> $listingID)";
					$recordSet->MoveNext();
				} // end while
				$recordSet = $conn->Execute($delete_string);
					if ($recordSet === false)
					{
						log_error($delete_string);
					}
			}

		} // end elseif ($ElementIndexValue == "imagesOnly")
		elseif (is_array($ElementContents))
		{
			$sql_ElementIndexValue = make_db_safe($ElementIndexValue);

			// Arrays can happen for two reasons:  1. multi options like zip code
			// 2. multi options like home features.  Check the db to see which
			// type of field this is and process accordingly
			if ($conn->getOne("select search_type from listingsFormElements where field_name = $sql_ElementIndexValue") == 'optionlist')
			{

	/*
					echo "deleting for $e";
					$res = $conn->Execute("select id temp where field_name = $sql_ElementIndexValue and field_value not like '%$e%'");
					while (!$res->EOF) {
						$conn->Execute("delete from temp where id = " . $res->fields['id']);
						$res->moveNext();
					}
				}
	*/
				// Delete all records that don't have any field name by this name
				$sql = "select count(t2.field_name) as cnt, t1.id as id from temp t1 left join listingsDBelements t2 on t1.id = t2.listing_id and t1.field_name = $sql_ElementIndexValue group by t1.id";
				$res = $conn->Execute($sql);
				while (!$res->EOF)
				{
					// Check for no field
					if ($res->fields['cnt'] == 0)
					{
						$conn->execute('delete from temp where id = ' . $res->fields['id']);
					}
					else
					{
						// for each value, delete those records that don't match it
						$value = $conn->getOne("select field_value from temp where id = " . $res->fields['id'] . " and field_name = $sql_ElementIndexValue");
						foreach ($ElementContents as $e)
						{
							if (!strstr($value, $e)) 
							{
								$conn->execute('delete from temp where id = ' . $res->fields['id']);
							}
						}
					}
					$res->moveNext();
				}
			}
			else
			{
				// first, we need to see if there's anything that'll meet the criteria
				$whilecountTwo = 0;
				$select_statement = "SELECT ID FROM temp WHERE ( (field_name=$sql_ElementIndexValue) AND ";
				while (list($featureValue, $feature_item) = each ($ElementContents))
				{
					$guidestring .= "&amp;".($ElementIndexValue)."%5B%5D=".urlencode($feature_item)."&amp;";
					//$guidestring .= urlencode($featureValue)."%5B%5D=".urlencode($feature_item)."&";
					$whilecountTwo = $whilecountTwo + 1;
					if ($whilecountTwo > 1)
					{
						$select_statement .= " OR ";
					}
					$sql_feature_item = make_db_safe($feature_item);
					$select_statement .= "(field_value = $sql_feature_item)";
				}
				$select_statement .= ")";
				$recordSet = $conn->Execute($select_statement);
				if ($recordSet === false)
				{
					log_error($select_statement);
				}
				$save_array = array();
				while (!$recordSet->EOF)
				{
					$save_ID = $recordSet->fields[ID];
					$save_array[] = "$save_ID";
					$recordSet->MoveNext();
				} // end while
				$num_to_delete = $recordSet->RecordCount();
				// now, delete everything that we don't want...
				if ($num_to_delete > 0)
				{
					$delete_string = "DELETE FROM temp WHERE ";
					while (list($IndexValue,$ElementContents) = each($save_array))
					{
						if ($IndexValue > 0)
						{
							$delete_string .= " AND ";
						}
						$sql_ElementContents = make_db_safe($ElementContents);
						$delete_string .= "(ID <> $sql_ElementContents)";
					} // end while

					$recordSet = $conn->Execute($delete_string);
						if ($recordSet === false)
						{
							log_error($delete_string);
						}
						
				} // ($num_to_delete > 0)
				// if there's nothing that matches, delete all the other possibilities...
				elseif ($num_to_delete == 0)
				{
					$delete_string = "DELETE FROM temp";
					$recordSet = $conn->Execute($delete_string);
						if ($recordSet === false)
						{
							log_error($delete_string);
						}
				} // end elseif ($num_to_delete = 0)
			} // end optionlist check
		} // end elseif (is_array($ElementContents))
		else
		{
				// Don't process empty searches
				if (!$ElementContents) continue;

				$val = $ElementContents;
			$ElementContents = make_db_safe($ElementContents);
				// Check for min/max values
				$l3 = substr($ElementIndexValue, strlen($ElementIndexValue) - 3);
				if ($l3 == 'min' OR $l3 == 'max')
				{
					$col = strtok($ElementIndexValue, '-');
					// Because mysql 3.x doesn't have cast(), we must retrieve all records then filter - yuck
					$sql = "select id, field_value as v from temp where field_name = '$col'";
					$rs = $conn->Execute($sql);
					$del_id = array();
					while (!$rs->EOF) {
						if ($l3 == 'min' AND $val!='')
						{
							if (!is_numeric($rs->fields['v']) || $rs->fields['v'] < $val)
							{	
								$del_id[] = $rs->fields['id'];
							}
						}
						if ($l3 == 'max' AND $val!='')
						{
							if (!is_numeric($rs->fields['v']) || $rs->fields['v'] > $val)
							{
								$del_id[] = $rs->fields['id'];
							}
						}
						$rs->MoveNext();
					}
					
					$sql = 'delete from temp where id in (' . implode(',', $del_id) . ')';
					if (sizeof($del_id))
					{
						$conn->execute($sql);
					}
					
					continue;
				}

			// Check for min/max dates
			$l7 = substr($ElementIndexValue, strlen($ElementIndexValue) - 7);
			if ($l7 == 'mindate' OR $l7 == 'maxdate')
			{
				if (($time = strtotime($val)) > 1)
				{
					$col = strtok($ElementIndexValue, '-');
					// Because mysql 3.x doesn't have cast(), we must retrieve all records then filter - yuck
					$sql = "select id, field_value as v from temp where field_name = '$col'";
					$rs = $conn->Execute($sql);
					$del_id = array();
					while (!$rs->EOF)
					{
						$db_time = strtotime($rs->fields['v']);
						if ($l7 == 'mindate' AND $val)
						{
							if ($db_time < $time)
							{
								$del_id[] = $rs->fields['id'];
							}
						}
						if ($l7 == 'maxdate' AND $val)
						{
							if ($db_time > $time)
							{
								$del_id[] = $rs->fields['id'];
							}
						}
						if ($db_time < 1 or !$val)
						{
							$del_id[] = $rs->fields['id'];
						}
						$rs->MoveNext();
					}
					$sql = 'delete from temp where id in (' . implode(',', $del_id) . ')';
					if (sizeof($del_id))
					{
						$conn->execute($sql);
					}
					
					continue;
			}
		}

		if (!$ElementContents) continue;
			$ElementIndexValue = make_db_safe($ElementIndexValue);
			$select_statement = "SELECT ID FROM temp WHERE ( (field_name = $ElementIndexValue) AND (field_value = $ElementContents) )";
			$recordSet = $conn->Execute($select_statement);
				if ($recordSet === false)
				{
					log_error($select_statement);
				}
			$save_array = array();
			while (!$recordSet->EOF)
			{
				$save_ID = $recordSet->fields[ID];
				$save_array[] = "$save_ID";
				$recordSet->MoveNext();
			} // end while
			$num_to_delete = $recordSet->RecordCount();
			if ($num_to_delete > 0)
			{

				$delete_string = "DELETE FROM temp WHERE ";
				while (list($IndexValue,$ElementContents) = each($save_array))
				{
					if ($IndexValue > 0)
					{
						$delete_string .= " AND ";
					}
					$delete_string .= "(ID <> $ElementContents)";
				}
				$recordSet = $conn->Execute($delete_string);
					if ($recordSet === false)
					{
						log_error($delete_string);
					}
			} // end ($num_to_delete > 0)
			elseif ($num_to_delete == 0)
			{
				$delete_string = "DELETE FROM temp";
				$recordSet = $conn->Execute($delete_string);
					if ($recordSet === false)
					{
						log_error($delete_string);
					}
			} // end elseif ($num_to_delete = 0)

		} // end else
	} // end while


		// this is the main SQL that grabs the listings
		// basic sort by title..
		if ($sortby == ""){
			$sort_text = "";
			$order_text = "ORDER BY id desc";
		}
		
		elseif ($sortby == "listingname")
		{
			$sort_text = "";
			$order_text = "ORDER BY binary Title ASC";
		}
		elseif ($sortby == "agent")
		{
			$sort_text = "";
			$order_text = "ORDER BY binary owner ASC";
		}
		elseif ($sortby == "date")
		{
			$sort_text = "";
			$order_text = "ORDER BY id desc";
		}
			// BEGIN NEW CODE
			elseif ($sortby == "price")
		{
			$sortby = make_db_extra_safe($sortby);
			$sort_text = "WHERE (field_name = $sortby)";
			$order_text = "ORDER BY field_value +0 ASC";
		}
			// END NEW CODE
		else
		{
			$sortby = make_db_extra_safe($sortby);
			$sort_text = "WHERE (field_name = $sortby)";
			$order_text = "ORDER BY field_value ASC";
		}

		$guidestring_with_sort = $guidestring_with_sort.$guidestring;

		$sql = "SELECT * from temp $sort_text GROUP BY ID $order_text";
		$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}

		$num_rows = $recordSet->RecordCount();
		if ($num_rows > 0)
		{
			echo "<a href='listingsearch.php'><img src='images/undo.gif' border=0 align=absmiddle hspace=3 width=23 height=22><b>".$lang[backtosearch]."</b></a>";
			next_prev($num_rows, $cur_page, $guidestring_with_sort); // put in the next/previous stuff
			// build the string to select a certain number of listings per page
			$limit_str = $cur_page * $config[listings_per_page];
			$resultRecordSet = $conn->SelectLimit($sql, $config[listings_per_page], $limit_str );
				if ($resultRecordSet === false)
				{
					log_error($sql);
				}

			?>

			<table border="<?php echo $style[form_border] ?>" cellspacing="0" cellpadding="<?php echo $style[form_cellpadding] ?>" width="<?php echo $style[admin_table_width] ?>" class="form_main" align="center">
				<tr>
					<td><b><a href="<?php echo $php_self ?>?sortby=listingname&<? echo $guidestring ?>"><? echo $lang[admin_listings_editor_title] ?></a></b></td>
					<td><b><? echo $lang[image] ?></b></td>
					<td><b><a href="<?php echo $php_self ?>?sortby=agent&<? echo $guidestring ?>"><? echo $lang[admin_listings_editor_agent] ?></a></b></td>
					<td><b><a href="<?php echo $php_self ?>?sortby=date&<? echo $guidestring ?>"><? echo $lang[admin_listings_editor_date] ?></a></b></td>

					<?php
					// grab browsable fields
					$sql = "SELECT field_caption, field_name FROM listingsFormElements WHERE (display_on_browse = 'Yes') AND (field_type <> 'textarea') ORDER BY rank";
					$recordSet = $conn->Execute($sql);
					$num_columns = $recordSet->RecordCount();
					while (!$recordSet->EOF)
					{
						$field_caption = make_db_unsafe ($recordSet->fields[field_caption]);
						$field_name = make_db_unsafe ($recordSet->fields[field_name]);
						echo "<td align=\"center\"><b><a href=\"$PHP_SELF?sortby=$field_name&amp;$guidestring\">$field_caption</a></b></td>";
						$recordSet->MoveNext();
					} // end while
					$num_columns = $num_columns + 4; // add one for the image an one for the agent and one for date
					?>
				</tr>
				<tr>
					<td colspan="<?php echo $num_columns ?>">
						<hr>
					</td>
				</tr>

				<?php
				$count = 0;
				while (!$resultRecordSet->EOF)
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

					$Title = make_db_unsafe ($resultRecordSet->fields[Title]);
					$Agent = make_db_unsafe ($resultRecordSet->fields[Owner]);
					$Date = make_db_unsafe ($resultRecordSet->fields[creation_date]);
					$Date = explode("-",$Date);
					$Date = $Date[2]."/".$Date[1]."/".$Date[0];
					$current_ID = $resultRecordSet->fields[ID];
					
					$backurl = urlencode($PHP_SELF."?".$_SERVER["QUERY_STRING"]);
					echo "<tr title='$descr' style='cursor:hand' onmouseover='this.className=\"Selected\"' onmouseout='this.className=\"\"' onclick='location=(\"listingview.php?listingID=$current_ID&backurl=$backurl\")'>";
					echo "<td class=\"search_row_$count\"><b>$Title&nbsp;</b></td>";

					// grab the listing's image
					$sql2 = "SELECT thumb_file_name FROM listingsImages WHERE listing_id = $current_ID ORDER BY rank";
					$recordSet2 = $conn->SelectLimit($sql2, 1, 0);
					if ($recordSet2 === false)
					{
						log_error($sql2);
					}
					$num_images = $recordSet2->RecordCount();
					if ($num_images == 0)
					{
						if ($config[show_no_photo] == "yes")
						{
							//echo "<td class=\"search_row_$count\" align=\"center\"><img src=\"images/nophoto.gif\" border=\"1\" alt=\"no photo\"></td>";
							echo "<td class=\"search_row_$count\" align=\"center\"><a href=\"listingview.php?listingID=$current_ID\"><img src=\"images/nophoto.gif\" border=\"1\" alt=\"no photo\"></a></td>";
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
							// gotta grab the image size
							$imagedata = GetImageSize("$config[listings_upload_path]/$thumb_file_name");
							$imagewidth = $imagedata[0];
							$imageheight = $imagedata[1];
							
							$shrinkage = ($imagewidth)?($config[thumbnail_width]/$imagewidth):0;
							$displaywidth = $imagewidth * $shrinkage;
							$displayheight = $imageheight * $shrinkage;
							echo "<td class=\"search_row_$count\" align=\"center\">";
							//echo "<img src=\"$config[listings_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\" alt=\"$thumb_file_name\">";
							echo "<img src='images/photo.jpg' border=0>";
							echo "</td>";
						} // end if ($thumb_file_name != "")
						$recordSet2->MoveNext();
					} // end while

					//show agent
					echo "<td  class=\"search_row_$count\">$Agent</b></td>";
					echo "<td  class=\"search_row_$count\">$Date</b></td>";
					
					// grab the rest of the listing's data
					$sql2 = "SELECT listingsDBElements.field_value, listingsDBElements.user_id, listingsFormElements.access_level, listingsFormElements.field_type FROM listingsFormElements left outer join listingsDBElements  on listingsDBElements.field_name = listingsFormElements.field_name and listingsDBElements.listing_id = $current_ID WHERE ((listingsFormElements.display_on_browse = 'Yes') AND (listingsFormElements.field_type <> 'textarea') ) ORDER BY listingsFormElements.rank";
					//echo $sql2;
					$recordSet2 = $conn->Execute($sql2);
						if ($recordSet2 === false)
						{
							log_error($sql2);
						}
					while (!$recordSet2->EOF)
					{
						$field_value = make_db_unsafe ($recordSet2->fields[field_value]);
						$field_type = make_db_unsafe ($recordSet2->fields[field_type]);
						$access_level = make_db_unsafe ($recordSet2->fields[access_level]);
						$usr_id = make_db_unsafe ($recordSet2->fields[user_id]);
						
						echo "<td align=\"center\" class=\"search_row_$count\">";
						if ($access_level != 0 or $usr_id == $userID){

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
							//$field_value = ereg_replace('[^0-9]', '', $field_value);
							//echo "$config[money_sign]".number_format($field_value, 2, '.', ',');
							//$money_amount = international_num_format($field_value);
							//echo money_formats($money_amount);
							echo $field_value;
						} // end elseif
						elseif ($field_type == "number")
						{
							if ($field_value!=""){echo international_num_format($field_value);}
						} // end elseif
						elseif ($field_type == "url")
						{
							echo "<a href=\"$field_value\" target=\"_new\">$field_value</a>";
						}
						elseif ($field_type == "email")
						{
							echo "<a href=\"mailto:$field_value\">$field_value</a>";
						}
						else
						{
							echo "$field_value";
						} // end else

						}
						else{
						echo "---";
						}
						echo "&nbsp;</td>";
						$recordSet2->MoveNext();
					} // end while


					echo "</tr>";
					// deal with text areas, like descriptions
					$sql2 = "SELECT listingsDBElements.field_value, listingsFormElements.field_type FROM listingsDBElements, listingsFormElements WHERE ((listingsDBElements.listing_id = $current_ID) AND (listingsFormElements.display_on_browse = 'Yes') AND (listingsFormElements.field_type = 'textarea') AND (listingsDBElements.field_name = listingsFormElements.field_name)) ORDER BY listingsFormElements.rank";
					$recordSet2 = $conn->Execute($sql2);
						if ($recordSet2 === false)
						{
							log_error($sql2);
						}
					while (!$recordSet2->EOF)
					{
						$field_value = make_db_unsafe ($recordSet2->fields[field_value]);
						$field_caption = make_db_unsafe ($recordSet2->fields[field_caption]);
						echo "<tr><td class=\"search_row_$count\">$field_value</td></tr>";
						$recordSet2->MoveNext();
					} // end while


					$resultRecordSet->MoveNext();
				} // end while


				?>


		</table>

		<?php
	} // end if ($num_rows > 0)
	else
	{
		echo "<p>$lang[search_no_results]</p>";
		echo "<a href='listingsearch.php'><img src='images/undo.gif' border=0 align=absmiddle hspace=3 width=23 height=22><b>".$lang[backtosearch]."</b></a>";
	}


	include("$config[template_path]/admin_bottom.html");
	$conn->Close(); // close the db connection
?>