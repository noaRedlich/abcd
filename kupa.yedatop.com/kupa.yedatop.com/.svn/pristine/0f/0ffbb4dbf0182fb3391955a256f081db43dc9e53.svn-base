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

	
	
	//DELETING
	if ($del && $GO_MODULES->has_write_permission($userID,'realty')){
		$sql = "delete from listingsDBElements where listing_id = $del";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		$sql = "delete from listingsDB where ID = $del";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
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

	$sql_select = "create temporary table TEMP SELECT distinct
	l.ID, l.Title, l.user_id, users.name as Owner, l.creation_date ";
	$sql_from = "	FROM listingsDB l inner join  users  on users.id = l.user_ID ";
	$sql_where = " WHERE ";
	
	if ($config[use_expiration] == "yes")
	{
		$sql_where .= "(l.expiration > ".$conn->DBDate(time()).") AND ";
	}
	if (isset($userid) and $userid!='' and $userid!=-1){
		$sql_where .= "  l.user_ID = $userid AND ";
		$sql_where .= " (l.active = 'yes' or l.user_ID = '$userID')";
	}
	elseif($userid==-1){
		$sql_where .= "  l.user_ID <> $userID AND l.user_ID <> 0 AND";
		$sql_where .= " (l.active = 'yes')";
	}
	else{
		$sql_where .= " (l.active = 'yes' or l.user_ID = '$userID')";
	}
	
	
	reset ($HTTP_GET_VARS);$i=1;
	foreach ($_GET as $ElementIndexValue => $ElementContents) {
		if ($ElementIndexValue == "sortby")
		{
			$guidestring_with_sort = "$ElementIndexValue=$ElementContents";
		}
		elseif ($ElementIndexValue == "del" || $ElementIndexValue == "cur_page" || $ElementIndexValue == "userid" || $ElementIndexValue == "PHPSESSID")
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
	
	
	 $sql = $sql_select . $sql_from . $sql_where;
	 $conn->Execute($sql);
	 //echo $sql."<p>";

	// this is the main SQL that grabs the listings
		// basic sort by title..
		if ($sortby == ""){
			$sort_text = "";
			$order_text = "ORDER BY ID desc";
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
			// BEGIN NEW CODE
			elseif ($sortby == "price")
		{
			$sortby = make_db_extra_safe($sortby);
			$sql_from= " left outer join listingsDBElements Eprice on Eprice.listing_id = l.ID and Eprice.field_name='price' ";
			$order_text = "ORDER BY replace(replace(Eprice.field_value,'$',''),',','')+0 ASC";
		}
			// END NEW CODE
		else
		{
			$sortby = make_db_extra_safe($sortby);
			$sql_from= " left outer join listingsDBElements Esort on Esort.listing_id = l.ID and Esort.field_name=$sortby ";
			$order_text = "ORDER BY Esort.field_value ASC";
		}

		$guidestring_with_sort = $guidestring_with_sort.$guidestring;

		$sql_select  = "select l.ID, Owner,Title,creation_date from TEMP l ";
		$sql = $sql_select . $sql_from. $order_text;
		 
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
					<?if ($GO_MODULES->has_write_permission($userID,'realty')){?>
					<td><strong>x</strong></td>
					<?}?>
				</tr>
				<tr>
					<td colspan="<?php echo $num_columns+1 ?>">
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
					?>
					<?if ($GO_MODULES->has_write_permission($userID,'realty')){?>
					<td class="search_row_<?=$count?>"><a href="#" onclick='window.event.cancelBubble=true;if(confirm("Delete: please confirm.")){location="<?=$PHP_SELF."?cur_page=".$_GET['cur_page']."&sortby=".$_GET['sortby']?>&del=<?=$current_ID?>"};' title='Delete'>x</a></td>
					<?}?>
					<?
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