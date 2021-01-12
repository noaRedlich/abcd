<?php

class DataList
{
	// this class generates the data table ...
	// it assumes that the following functions are already defined:
		// delete()
		// update()
		// insert()
		// form()
		// >>> all the above functions change for every table
		// >>> the calling script should include the correct versions of them.
		// NavMenus()
		// sql()
	// the class also assumes that the following files already exist:
		// print.gif
		// delete.gif
		// update.gif
		// search.gif
		// cancel_search.gif
		// insert.gif
		// asc.gif
		// desc.gif
		// scroll_bar.gif
		// scroll_box.gif
		// scroll_next.gif
		// scroll_previous.gif
	// the class assumes the following CSS classes have been defined:
		// Error
		// TableTitle
		// TableHeader
		// TextBox
		// TableBody
		// TableBodyNumeric
		// TableBodySelectedNumeric
		// TableBodySelected
		// TableFooter
		// SelectedOption
	
	var $Query,

		$ColWidth,                      // array of field widths
		$DataHeight,

		$AllowSelection,
		$AllowDelete,
		$AllowInsert,
		$AllowUpdate,
		$AllowFilters,
		$AllowSorting,
		$AllowNavigation,
		$AllowPrinting,
		$HideTableView,
		$AllowCSV,
		$CSVSeparator,

		$QuickSearch,     // 0 to 3

		$RecordsPerPage,
		$ScriptFileName,
		$TableTitle,
		$PrimaryKey,

		$HTML;                          // generated html after calling Render()

	function DataList()     // Constructor function
	{
		$this->DataHeight = 150;
		
		$this->AllowSelection = 1;
		$this->AllowDelete = 1;
		$this->AllowInsert = 1;
		$this->AllowUpdate = 1;
		$this->AllowFilters = 1;
		$this->AllowNavigation = 1;
		$this->AllowPrinting = 1;
		$this->HideTableView = 0;
		$this->QuickSearch = 0;
		$this->AllowCSV = 0;
		$this->CSVSeparator = ",";
		
		$this->RecordsPerPage = 10;
		$this->HTML = "";
	}

	function Render()
	{
	// get post and get variables
		global $lang,$HTTP_SERVER_VARS, $HTTP_GET_VARS, $HTTP_POST_VARS, $_SERVER;
		
		if($HTTP_SERVER_VARS["REQUEST_METHOD"] == "GET")
		{
			$SortField = $HTTP_GET_VARS["SortField"];
			$SortDirection = $HTTP_GET_VARS["SortDirection"];
			$FirstRecord = $HTTP_GET_VARS["FirstRecord"];
			$ScrollUp_y = $HTTP_GET_VARS["ScrollUp_y"];
			$ScrollDn_y = $HTTP_GET_VARS["ScrollDn_y"];
			$Previous_x = $HTTP_GET_VARS["Previous_x"];
			$Next_x = $HTTP_GET_VARS["Next_x"];
			$Filter_x = $HTTP_GET_VARS["Filter_x"];
			$NoFilter_x = $HTTP_GET_VARS["NoFilter_x"];
			$CancelFilter = $HTTP_GET_VARS["CancelFilter"];
			$ApplyFilter = $HTTP_GET_VARS["ApplyFilter"];
			$Search_x = $HTTP_GET_VARS["Search_x"];
			$SearchString = $HTTP_GET_VARS["SearchString"];
			$CSV_x = $HTTP_GET_VARS["CSV_x"];
			
			$FilterAnd1 = $HTTP_GET_VARS["FilterAnd1"];
			$FilterAnd2 = $HTTP_GET_VARS["FilterAnd2"];
			$FilterAnd3 = $HTTP_GET_VARS["FilterAnd3"];
			$FilterAnd4 = $HTTP_GET_VARS["FilterAnd4"];
			$FilterAnd5 = $HTTP_GET_VARS["FilterAnd5"];
			$FilterAnd6 = $HTTP_GET_VARS["FilterAnd6"];
			$FilterAnd7 = $HTTP_GET_VARS["FilterAnd7"];
			$FilterAnd8 = $HTTP_GET_VARS["FilterAnd8"];
			$FilterAnd9 = $HTTP_GET_VARS["FilterAnd9"];
			$FilterAnd10 = $HTTP_GET_VARS["FilterAnd10"];

			$FilterField1 = $HTTP_GET_VARS["FilterField1"];
			$FilterField2 = $HTTP_GET_VARS["FilterField2"];
			$FilterField3 = $HTTP_GET_VARS["FilterField3"];
			$FilterField4 = $HTTP_GET_VARS["FilterField4"];
			$FilterField5 = $HTTP_GET_VARS["FilterField5"];
			$FilterField6 = $HTTP_GET_VARS["FilterField6"];
			$FilterField7 = $HTTP_GET_VARS["FilterField7"];
			$FilterField8 = $HTTP_GET_VARS["FilterField8"];
			$FilterField9 = $HTTP_GET_VARS["FilterField9"];
			$FilterField10 = $HTTP_GET_VARS["FilterField10"];

			$FilterOperator1 = $HTTP_GET_VARS["FilterOperator1"];
			$FilterOperator2 = $HTTP_GET_VARS["FilterOperator2"];
			$FilterOperator3 = $HTTP_GET_VARS["FilterOperator3"];
			$FilterOperator4 = $HTTP_GET_VARS["FilterOperator4"];
			$FilterOperator5 = $HTTP_GET_VARS["FilterOperator5"];
			$FilterOperator6 = $HTTP_GET_VARS["FilterOperator6"];
			$FilterOperator7 = $HTTP_GET_VARS["FilterOperator7"];
			$FilterOperator8 = $HTTP_GET_VARS["FilterOperator8"];
			$FilterOperator9 = $HTTP_GET_VARS["FilterOperator9"];
			$FilterOperator10 = $HTTP_GET_VARS["FilterOperator10"];

			$FilterValue1 = $HTTP_GET_VARS["FilterValue1"];
			$FilterValue2 = $HTTP_GET_VARS["FilterValue2"];
			$FilterValue3 = $HTTP_GET_VARS["FilterValue3"];
			$FilterValue4 = $HTTP_GET_VARS["FilterValue4"];
			$FilterValue5 = $HTTP_GET_VARS["FilterValue5"];
			$FilterValue6 = $HTTP_GET_VARS["FilterValue6"];
			$FilterValue7 = $HTTP_GET_VARS["FilterValue7"];
			$FilterValue8 = $HTTP_GET_VARS["FilterValue8"];
			$FilterValue9 = $HTTP_GET_VARS["FilterValue9"];
			$FilterValue10 = $HTTP_GET_VARS["FilterValue10"];
			
			$Print_x = $HTTP_GET_VARS["Print_x"];
			$SelectedID = $HTTP_GET_VARS["SelectedID"];
			$insert_x = $HTTP_GET_VARS["insert_x"];
			$update_x = $HTTP_GET_VARS["update_x"];
			$delete_x = $HTTP_GET_VARS["delete_x"];
			$deselect_x = $HTTP_GET_VARS["deselect_x"];
		}
		else
		{
			$SortField = $HTTP_POST_VARS["SortField"];
			$SortDirection = $HTTP_POST_VARS["SortDirection"];
			$FirstRecord = $HTTP_POST_VARS["FirstRecord"];
			$ScrollUp_y = $HTTP_POST_VARS["ScrollUp_y"];
			$ScrollDn_y = $HTTP_POST_VARS["ScrollDn_y"];
			$Previous_x = $HTTP_POST_VARS["Previous_x"];
			$Next_x = $HTTP_POST_VARS["Next_x"];
			$Filter_x = $HTTP_POST_VARS["Filter_x"];
			$NoFilter_x = $HTTP_POST_VARS["NoFilter_x"];
			$CancelFilter = $HTTP_POST_VARS["CancelFilter"];
			$ApplyFilter = $HTTP_POST_VARS["ApplyFilter"];
			$Search_x = $HTTP_POST_VARS["Search_x"];
			$SearchString = $HTTP_POST_VARS["SearchString"];
			$CSV_x = $HTTP_POST_VARS["CSV_x"];
			
			$FilterAnd1 = $HTTP_POST_VARS["FilterAnd1"];
			$FilterAnd2 = $HTTP_POST_VARS["FilterAnd2"];
			$FilterAnd3 = $HTTP_POST_VARS["FilterAnd3"];
			$FilterAnd4 = $HTTP_POST_VARS["FilterAnd4"];
			$FilterAnd5 = $HTTP_POST_VARS["FilterAnd5"];
			$FilterAnd6 = $HTTP_POST_VARS["FilterAnd6"];
			$FilterAnd7 = $HTTP_POST_VARS["FilterAnd7"];
			$FilterAnd8 = $HTTP_POST_VARS["FilterAnd8"];
			$FilterAnd9 = $HTTP_POST_VARS["FilterAnd9"];
			$FilterAnd10 = $HTTP_POST_VARS["FilterAnd10"];

			$FilterField1 = $HTTP_POST_VARS["FilterField1"];
			$FilterField2 = $HTTP_POST_VARS["FilterField2"];
			$FilterField3 = $HTTP_POST_VARS["FilterField3"];
			$FilterField4 = $HTTP_POST_VARS["FilterField4"];
			$FilterField5 = $HTTP_POST_VARS["FilterField5"];
			$FilterField6 = $HTTP_POST_VARS["FilterField6"];
			$FilterField7 = $HTTP_POST_VARS["FilterField7"];
			$FilterField8 = $HTTP_POST_VARS["FilterField8"];
			$FilterField9 = $HTTP_POST_VARS["FilterField9"];
			$FilterField10 = $HTTP_POST_VARS["FilterField10"];

			$FilterOperator1 = $HTTP_POST_VARS["FilterOperator1"];
			$FilterOperator2 = $HTTP_POST_VARS["FilterOperator2"];
			$FilterOperator3 = $HTTP_POST_VARS["FilterOperator3"];
			$FilterOperator4 = $HTTP_POST_VARS["FilterOperator4"];
			$FilterOperator5 = $HTTP_POST_VARS["FilterOperator5"];
			$FilterOperator6 = $HTTP_POST_VARS["FilterOperator6"];
			$FilterOperator7 = $HTTP_POST_VARS["FilterOperator7"];
			$FilterOperator8 = $HTTP_POST_VARS["FilterOperator8"];
			$FilterOperator9 = $HTTP_POST_VARS["FilterOperator9"];
			$FilterOperator10 = $HTTP_POST_VARS["FilterOperator10"];

			$FilterValue1 = $HTTP_POST_VARS["FilterValue1"];
			$FilterValue2 = $HTTP_POST_VARS["FilterValue2"];
			$FilterValue3 = $HTTP_POST_VARS["FilterValue3"];
			$FilterValue4 = $HTTP_POST_VARS["FilterValue4"];
			$FilterValue5 = $HTTP_POST_VARS["FilterValue5"];
			$FilterValue6 = $HTTP_POST_VARS["FilterValue6"];
			$FilterValue7 = $HTTP_POST_VARS["FilterValue7"];
			$FilterValue8 = $HTTP_POST_VARS["FilterValue8"];
			$FilterValue9 = $HTTP_POST_VARS["FilterValue9"];
			$FilterValue10 = $HTTP_POST_VARS["FilterValue10"];
			
			$Print_x = $HTTP_POST_VARS["Print_x"];
			$SelectedID = $HTTP_POST_VARS["SelectedID"];
			$insert_x = $HTTP_POST_VARS["insert_x"];
			$update_x = $HTTP_POST_VARS["update_x"];
			$delete_x = $HTTP_POST_VARS["delete_x"];
			$deselect_x = $HTTP_POST_VARS["deselect_x"];
		}
		
	// insure authenticity of user inputs:
		if(!$this->AllowSelection)
		{
			$insert_x = "";
			$update_x = "";
			$delete_x = "";
			$deselect_x = "";
		}
		if(!$this->AllowDelete)
		{
			$delete_x = "";
		}
		if(!$this->AllowInsert)
		{
			$insert_x = "";
		}
		if(!$this->AllowUpdate)
		{
			$update_x = "";
		}
		if(!$this->AllowFilters)
		{
			$Filter_x = "";
		}
		if(!$this->AllowPrinting)
		{
			$Print_x = "";
		}
		if(!$this->QuickSearch)
		{
			$SeachString = "";
		}
		if(!$this->AllowCSV)
		{
			$CSV_x = "";
		}

	// retouch query to avoid case matching problems
		$this->Query = eregi_replace(" WHERE ", " where ", $this->Query);
		$this->Query = eregi_replace("SELECT ", "select ", $this->Query);
		$this->Query = eregi_replace(" FROM ", " from ", $this->Query);
		$this->Query = eregi_replace(" AS ", " as ", $this->Query);

		
		$this->HTML .= StyleSheet();
		include("./header.html");
		$this->HTML .= "<form method=post name=myform action='$this->ScriptFileName'>";


	// handle user commands ...
		if($deselect_x != "")
		{
			$SelectedID = "";
		}
		
		elseif($insert_x != "")
		{
			$SelectedID = insert();
			
			// redirect to a safe url to avoid refreshing and thus
			// insertion of duplicate records.

			$this->HTML .= "<META HTTP-EQUIV='Refresh' CONTENT='0;url=$this->ScriptFileName?SortField=$SortField&SortDirection=$SortDirection&FirstRecord=$FirstRecord&SelectedID=$SelectedID";
			for($i = 1; $i <= 10; $i++) // 3adad elflater elly ana 7atetha fi elbarnameg
			{
			        $FilterAnd = "FilterAnd$i";
			        $FilterField = "FilterField$i";
			        $FilterOperator = "FilterOperator$i";
			        $FilterValue = "FilterValue$i";
			        
			        if($$FilterField != "" && $$FilterOperator != "" && $$FilterValue != "")
			        {
			                $this->HTML .= "&$FilterAnd=${$FilterAnd}&$FilterField=${$FilterField}&$FilterOperator=${$FilterOperator}&$FilterValue=${$FilterValue}";
			        }
			}
			$this->HTML .= "'>";
		}
		
		elseif($delete_x != "")
		{
			$d = delete($SelectedID);
			if($d)
			        $this->HTML .= "<div class=Error>ERROR: $d</div>";
			else
			        $SelectedID = "";
		}
		
		elseif($update_x != "")
		{
			update($SelectedID);
		}
		
		elseif($Print_x != "")
		{
			// print code here ....
			$this->AllowNavigation = 0;
			$this->AllowSelection = 0;
		}
		elseif($Filter_x != "")
		{
			// filter page code here .....
			$this->HTML .= "<table border=0 align=center><tr><td colspan=4 class=TableTitle>Filters</td></tr>";
			$this->HTML .= "\n\t<tr><td class=TableHeader></td><td class=TableHeader>Filtered Field</td><td class=TableHeader>Comparison Operator</td><td class=TableHeader>Comparison Value</td></tr>";
			

			// get field names and captions
			$query_fields = str_replace("select ", "", $this->Query);
			$rest = strstr($this->Query, " from ");
			$query_fields = str_replace($rest, "", $query_fields);
			$field_names[] .= "";
			$field_captions[] .=  "";
			
			$is_first = 1;
			$fields_arr = explode(" as ", $query_fields);
			foreach($fields_arr as $field_as)
			{
				if($is_first)
				{
					$is_first = 0;
					$field_names[] = trim($field_as);
				}
				else
				{
					$exp1 = strstr($field_as, ",");
					if($exp1)
					{
						$exp2 = trim(substr($exp1,1));
						$exp3 = trim(str_replace("'", "", str_replace($exp1, "", $field_as)));
						$field_names[] = $exp2;
					}
					else
					{
						$exp3 = trim(str_replace("'", "", $field_as));
					}
					$field_captions[] = $exp3;
				}
			}

			for($i = 1; $i <= 10; $i++) // 3adad elflater elly ana 7atetha fi elbarnameg
			{
			        $FilterAnd = "FilterAnd$i";
			        $FilterField = "FilterField$i";
			        $FilterOperator = "FilterOperator$i";
			        $FilterValue = "FilterValue$i";
			        $fields = "";
			        $operators = "";
			        

			        // And, Or select
			        $this->HTML .= "\n\t<tr><td class=TableHeader style='text-align:right;'>&nbsp;Filter $i ";
			        $seland = new Combo;
			        $seland->ListItem = array("And", "Or");
			        $seland->ListData = array("and", "or");
			        $seland->SelectName = $FilterAnd;
			        $seland->SelectedData = $$FilterAnd;
			        $seland->Render();
			        $this->HTML .= $seland->HTML . "</td>";
			                                                                        

			        // Fields list
			        $selfields = new Combo;
			        $selfields->SelectName = $FilterField;
			        $selfields->SelectedData = $$FilterField;
			        $selfields->ListItem = $field_captions;
			        $selfields->ListData = $field_names;
			        $selfields->Render();
			        $this->HTML .= "\n\t\t<td>$selfields->HTML</td>";
			        

			        // Operators list
			        $selop = new Combo;
			        $selop->ListItem = array(" ", "Equal to", "Not equal to", "Greater than", "Greater than or equal to", "Less than", "Less than or equal to" , "Like" , "Not like");
			        $selop->ListData = array("", "<=>", "!=", ">", ">=", "<", "<=", "like", "not like");
			        $selop->SelectName = $FilterOperator;
			        $selop->SelectedData = $$FilterOperator;
			        $selop->Render();
			        $this->HTML .= "\n\t\t<td>$selop->HTML</td>";
			        

			        // Comparison expression
			        $this->HTML .= "\n\t\t<td><input size=25 type=text name=$FilterValue value=\"${$FilterValue}\" class=TextBox></td></tr>";
			}
			$this->HTML .= "\n\t<tr><td colspan=4 align=right><input type=image src=search.gif alt='Apply Filters'></td></tr>";
			$this->HTML .= "\n</table>";
			
			// hidden variables ....
			        $this->HTML .= "<input name=SortField value='$SortField' type=hidden>";               
			        // $this->HTML .= "<input name=SelectedID value='$SelectedID' type=hidden>";          
			        $this->HTML .= "<input name=SortDirection type=hidden value='$SortDirection'>";               
			        $this->HTML .= "<input name=FirstRecord type=hidden value='1'>";              
			return;
		}
		elseif($NoFilter_x != "")
		{
			// clear all filters ...
			for($i = 1; $i <= 10; $i++) // 3adad elflater elly ana 7atetha fi elbarnameg
			{
			        $FilterAnd = "FilterAnd$i";
			        $FilterField = "FilterField$i";
			        $FilterOperator = "FilterOperator$i";
			        $FilterValue = "FilterValue$i";
			        
			        $$FilterField = "";
			        $$FilterOperator = "";
			        $$FilterValue = "";
			}
			$SearchString = "";
		}
		
		if($SearchString != "")
		{
			if(!stristr($this->Query, " where "))
				$this->Query .= " where ";
			else
				$this->Query .= " and (";
			
			// get field names
			unset($field_names);
			$query_fields = str_replace("select ", "", $this->Query);
			$rest = strstr($this->Query, " from ");
			$query_fields = str_replace($rest, "", $query_fields);
			
			$is_first = 1;
			$query_modified_by_quicksearch = 0;
			$fields_arr = explode(" as ", $query_fields);
			foreach($fields_arr as $field_as)
			{
				if($is_first)
				{
					$is_first = 0;
					$exp1 = "any value";
					$exp2 = trim($field_as);
				}
				else
				{
					$exp1 = strstr($field_as, ",");
					if($exp1)
					{
						$exp2 = trim(substr($exp1,1));
					}
				}
				if($exp1)
				{
					$this->Query .= "$exp2 like '%" . ( get_magic_quotes_gpc()? $SearchString : addslashes($SearchString) ) . "%' or ";
					$query_modified_by_quicksearch = 1;
				}
			}

			if($query_modified_by_quicksearch)
			{
				$this->Query = substr($this->Query, 0, strlen($this->Query) - 3);

				if(stristr($this->Query, " and ("))
					$this->Query .= ") ";
			}
		}


	// set query filters
		if(true or $this->AllowFilters)
		{
			for($i = 1; $i <= 10; $i++) // 3adad elflater elly ana 7atetha fi elbarnameg
			{
			        $FilterAnd = "FilterAnd$i";
			        $FilterField = "FilterField$i";
			        $FilterOperator = "FilterOperator$i";
			        $FilterValue = "FilterValue$i";
			        if($$FilterField != "" && $$FilterOperator != "" && $$FilterValue != "")
			        {
			                if(!stristr($this->Query, " where "))
			                        $this->Query .= " where ";
	
			                $this->Query .= " f and (";
			                $this->Query .= " ${$FilterAnd} ${$FilterField} ${$FilterOperator} '${$FilterValue}' ]] ";
			        }
			}
		}
	// set query sort
		if(!stristr($this->Query, " order by ") && $SortField != "" && $this->AllowSorting)
			$this->Query .= " order by $SortField $SortDirection";

	// retouch query
		$this->Query = str_replace(" ]]  f and (", "", $this->Query);
		$this->Query = str_replace(" f and (", " and (", $this->Query);
		$this->Query = str_replace("' ]] ", "' ) ", $this->Query);
		$this->Query = str_replace(" ( and ", " ( ", $this->Query);
		$this->Query = str_replace(" ( or ", " ( ", $this->Query);
		$this->Query = str_replace(" where and ", " where ", $this->Query);
		$this->Query = str_replace(" where  and ", " where ", $this->Query);
		$this->Query = str_replace(" where or ", " where ", $this->Query);
		$this->Query = str_replace(" where  or ", " where ", $this->Query);
		if($this->PrimaryKey != "")
			$this->Query = str_replace(" from ", ", $this->PrimaryKey from ", $this->Query);

	// and finally, execute the query ...
		$result = sql($this->Query);
		$FieldCount = mysql_num_fields($result) - 1;
		$RecordCount = mysql_num_rows($result);

	// Output CSV on request
		if($CSV_x != "")
		{
			$this->HTML = "";
			// output CSV HTTP headers ...
			header("Cache-control: private");
			header("Content-type: application/force-download");
			
			if(strstr($_SERVER["HTTP_USER_AGENT"], "MSIE"))
				header("Content-Disposition: filename=data.csv" . "%20"); // For IE
			else
				header("Content-Disposition: attachment; filename=data.csv"); // For Other browsers
			
			// output CSV field names
			for($i = 0; $i < $FieldCount; $i++)
				$this->HTML .= mysql_field_name($result, $i) . $this->CSVSeparator;
			$this->HTML .= "\n\n";
			
			// output CSV data
			while($row = mysql_fetch_row($result))
			{
				for($i = 0; $i < $FieldCount; $i++)
					$this->HTML .= $row[$i] . $this->CSVSeparator;
				$this->HTML .= "\n\n";
			}
			$this->HTML = str_replace($this->CSVSeparator . "\n\n", "\n", $this->HTML);
			$this->HTML = substr($this->HTML, 0, strlen($this->HTML) - 1);
			
			// send output and quit script
			echo $this->HTML;
			exit;
		}
		$t = time(); // just a random number for any purpose ...

		$this->HTML .= "<table cellspacing=0 cellpadding=1 border=0><tr>\n";
		
	// display table title and Print and Filter icons
		$this->HTML .= "<td colspan=" . ($FieldCount + 1) . ">";
		$sum_width = 0;
		for($i = 0; $i < count($this->ColWidth); $i++)
			$sum_width += $this->ColWidth[$i];
		$this->HTML .= "<table width=" . $sum_width . " cellspacing=0 cellpadding=0 border=0><tr><td align=left><h3>$this->TableTitle</h3>";
		
	if(!$this->HideTableView)
	{
		// display quick search box
		if($this->QuickSearch > 0 && $this->QuickSearch < 4 && !$Print_x)
		{
			$this->HTML .= "<div class=TableBody style='text-align:" . ( ($this->QuickSearch == 1) ? "left" : (($this->QuickSearch == 2) ? "center" : "right")) . ";'>";
			$this->HTML .= "<b>" . $this->QuickSearchText . "</b> <input type=text name=SearchString value='" . $SearchString . "' size=15 class=TextBox>";
			$this->HTML .= "<input align=top border=0 name=Search type=image src=qsearch.gif alt='" . $this->QuickSearchText . "'>";
			$this->HTML .= "</div>";
		}
		// display Print icon
		if($this->AllowPrinting && !$Print_x)
			$this->HTML .= "<img src=print.gif name=Print alt='Printer Friendly View' onclick='openPrint()'>";
		
		// display CSV icon
		if($this->AllowCSV && !$Print_x)
			$this->HTML .= "<input type=image src=csv.gif name=CSV alt='Save data as CSV (comma-separated values)'>";
		
		// display Filter icons
		if($this->AllowFilters && !$Print_x)
			$this->HTML .= " <input type=image src=search.gif name=Filter alt='Edit Filters'> <input type=image src=cancel_search.gif name=NoFilter alt='Clear Filters'> ";
	}

		// display tables navigator menu
		if(!$Print_x) 
			$this->HTML .= NavMenus();
		
	if(!$this->HideTableView)
	{
		$this->HTML .= "</td><td align=right valign=bottom></td></tr></table></td></tr>\n<tr>";
		
	// display table headers                
		for($i = 0; $i < $FieldCount; $i++)
		{
			if($this->AllowSorting == 1)
			{
			        $sort1 = "<a href='$t' class=TableHeader onclick=\"SortDirection.value='asc'; SortField.value = $i + 1; myform.submit(); return false;\">";
			        $sort2 = "</a>";
			        if($i == $SortField - 1)
			        {
			                $SortDirection = ($SortDirection == "asc" ? "desc" : "asc");
			                $sort1 = "<a href='$t' class=TableHeader onclick=\"SortDirection.value='$SortDirection'; SortField.value = $i + 1; myform.submit(); return false;\"><img src=$SortDirection.gif border=0 width=11 height=11 hspace=3>";
			                $SortDirection = ($SortDirection == "asc" ? "desc" : "asc");
			        }
			}
			else
			{
			        $sort1 = "";
			        $sort2 = "";
			}
			$this->HTML .= "\t<td valign=top width='" . ($this->ColWidth[$i] ? $this->ColWidth[$i] : 100) . "' class=TableHeader><div class=TableHeader>$sort1" . mysql_field_name($result, $i) . "$sort2</div></td>\n";
		}
		
	// display vertical scroll bar ...
		if($RecordCount && $this->AllowNavigation)
		{
			if($FirstRecord > $RecordCount)
			        $FirstRecord -= $this->RecordsPerPage;
			        
			if($FirstRecord == "" || $FirstRecord < 1)
			        $FirstRecord = 1;
			
			$ScrollBox = $this->RecordsPerPage / $RecordCount * $this->DataHeight;
			if($ScrollBox > $this->DataHeight)
			        $ScrollBox = $this->DataHeight;

			if($ScrollUp_y != "")
			{
			        $FirstRecord = floor($ScrollUp_y / $ScrollBox) * $this->RecordsPerPage + 1;
			}
			elseif($ScrollDn_y != "")
			{
			        $OldScrollUp = floor(($FirstRecord - 1) / $RecordCount * $this->DataHeight);
			        $FirstRecord = floor(($ScrollDn_y + $ScrollBox + $OldScrollUp) / $ScrollBox) * $this->RecordsPerPage + 1;
			}
			elseif($Previous_x != "")
			{
			        $FirstRecord -= $this->RecordsPerPage;
			        if($FirstRecord <= 0)
			                $FirstRecord = 1;
			}
			elseif($Next_x != "")
			{
			        $FirstRecord += $this->RecordsPerPage;
			        if($FirstRecord > $RecordCount)
			                $FirstRecord = $RecordCount - ($RecordCount % $this->RecordsPerPage);
			        if($FirstRecord <= 0)
			                $FirstRecord = 1;
			}
			else
			{
			        // no scrolling action took place :)
			}
			
			$ScrollUp = floor(($FirstRecord - 1) / $RecordCount * $this->DataHeight);
	
			$ScrollBox = floor($ScrollBox);
			if($ScrollBox < 3) // set minimum scroll box height
			        $ScrollBox = 3;
			if( ($ScrollUp + $ScrollBox) > $this->DataHeight)
			        $ScrollUp = $this->DataHeight - $ScrollBox;
	
			$ScrollDn = $this->DataHeight - $ScrollBox - $ScrollUp;
	
			$this->HTML .= "\t<td rowspan=1000 valign=top width=15>";
			$this->HTML .= "<input type=image name=Previous src=scroll_previous.gif width=15 height=15>";
			if($ScrollUp >= 1)
			        $this->HTML .= "<input type=image name=ScrollUp height=$ScrollUp src=scroll_bar.gif width=15>";
			$this->HTML .= "<image src=scroll_box.gif height=$ScrollBox width=15>";
			if($ScrollDn >= 1)
			        $this->HTML .= "<input type=image name=ScrollDn height=$ScrollDn width=15 src=scroll_bar.gif>";
			$this->HTML .= "<input type=image name=Next src=scroll_next.gif width=15 height=15>";
			$this->HTML .= "</td>";
			
		}
		elseif($RecordCount)
		{
			$FirstRecord = 1;
			$this->RecordsPerPage = $RecordCount;
		}
	// end of scroll bar code
		
		$this->HTML .= "\n\t</tr>\n";
	}
		
	// render data
	// hidden variables ....
		$this->HTML .= "<input name=SortField value='$SortField' type=hidden>";               
		$this->HTML .= "<input name=SelectedID value='$SelectedID' type=hidden>";             
		$this->HTML .= "<input name=SortDirection type=hidden value='$SortDirection'>";               
		$this->HTML .= "<input name=FirstRecord type=hidden value='$FirstRecord'>";   
	if(!$this->HideTableView)
	{
		// filters ...
		for($i = 1; $i <= 10; $i++) // 3adad elflater elly ana 7atetha fi elbarnameg
		{
			$FilterAnd = "FilterAnd$i";
			$FilterField = "FilterField$i";
			$FilterOperator = "FilterOperator$i";
			$FilterValue = "FilterValue$i";
			if($$FilterField != "" && $$FilterOperator != "" && $$FilterValue != "")
			{
			        $this->HTML .= "<input name=$FilterAnd value='${$FilterAnd}' type=hidden>";
			        $this->HTML .= "<input name=$FilterField value='${$FilterField}' type=hidden>";
			        $this->HTML .= "<input name=$FilterOperator value='${$FilterOperator}' type=hidden>";
			        $this->HTML .= "<input name=$FilterValue value='${$FilterValue}' type=hidden>";
			}
		}
	
		$i = 0;
		if($RecordCount)
		{
			mysql_data_seek($result, $FirstRecord - 1);
			$i = $FirstRecord;
			while(($row = mysql_fetch_row($result)) && ($i < ($FirstRecord + $this->RecordsPerPage)))
			{
			        $this->HTML .= "\t<tr>";
			        for($j = 0; $j < $FieldCount; $j++)
			        {
			                if(is_numeric($row[$j]) && $SelectedID != $row[$FieldCount])
			                        $class = "TableBodyNumeric";
			                elseif(is_numeric($row[$j]) && $SelectedID == $row[$FieldCount])
			                        $class = "TableBodySelectedNumeric";
			                elseif(!is_numeric($row[$j]) && $SelectedID != $row[$FieldCount])
			                        $class = "TableBody";
			                else
			                        $class = "TableBodySelected";
			                
			                if($this->AllowSelection == 1)
			                {
			                        $sel1 = "<a href=$t class=$class onclick='SelectedID.value=\"" . $row[$FieldCount] . "\"; myform.submit(); return false;'>";
			                        $sel2 = "</a>";
			                }
			                else
			                {
			                        $sel1 = "";
			                        $sel2 = "";
			                }
			                
			                $this->HTML .= "<td valign=top class=$class><div class=$class>&nbsp;$sel1" . $row[$j] . "$sel2&nbsp;</div></td>";
			        }
			        $this->HTML .= "</tr>\n";
			        $i++;
			}
			$i--;
		}
		for($j = $i + 1; $j < ($FirstRecord + $this->RecordsPerPage); $j++)
			$this->HTML .= "\n\t<tr><td colspan=$FieldCount><div class=TableBody>&nbsp;</div></td></tr>";
	// end of data
	
		$this->HTML .= "\n\t<tr><td colspan=$FieldCount><div class=TableFooter>".$lang['there_is_currently']." $FirstRecord ".$lang['to']." $i ".$lang['of']." $RecordCount</div></td></tr>";
	}
		
		// display details form ...
		if($this->AllowSelection)
		{
			$this->HTML .= "\n\t<tr><td colspan=" . ($FieldCount + 1) . "> " . form($SelectedID, $this->AllowUpdate, (($this->HideTableView && $SelectedID) ? 0 : $this->AllowInsert), $this->AllowDelete) . " </td></tr>";
		}
		$this->HTML .= "</table>\n";

	
		$this->HTML .= "</form>";

		$this->HTML .= @implode ("", @file ("./footer.html"));
	// Das ist Alles!               
	}
}


///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////


class DataCombo
{
	var $Query, // Only the first two fields of the query are used.
			        // The first field is treated as the primary key (data values),
			        // and the second field is the displayed data items.
		$Class,
		$Style,
		$SelectName,
		$FirstItem,     // if not empty, the first item in the combo with value of ""
		$SelectedData,  // a value compared to first field value of the query to select
			                    // an item from the combo.
		
		$ItemCount, // this is returned. It indicates the number of items in the combo.
		$HTML;      // this is returned. The combo html source after calling Render().
	
	function DataCombo() // Constructor function
	{
		$this->FirstItem = "";
		$this->HTML = "";
		$this->Class = "Option";
	}
	
	function Render()
	{
		$result = sql($this->Query);
		$this->ItemCount = mysql_num_rows($result);
		
		$this->HTML .= "<select name='$this->SelectName' class='$this->Class' style='$this->Style'>";
		if($this->FirstItem != "")
		{
			$this->HTML .= "\n\t<option value=''>$this->FirstItem</option>";
			$this->ItemCount ++;
		}
		
		while($row = mysql_fetch_row($result))
		{
			if($row[0] == $this->SelectedData)
			        $sel = "selected class=SelectedOption";
			else
			        $sel = "";
			
			$this->HTML .= "\n\t<option value='$row[0]' $sel>$row[1]</option>";
		}
		
		$this->HTML .= "\n</select>";
	}
}


///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////


class Combo
{
	// The Combo class renders a drop down combo
	// filled with elements in an array ListItem[]
	// and associates each element with data from
	// an array ListData[], and optionally selects 
	// one of the items.
	
	var $ListItem,          // array of items in the combo
		$ListData,              // array of items data values
		$Class,
		$Style,
		$SelectName,
		$SelectedData,
		
		$HTML;
		
	function Combo() // Constructor function
	{
		$this->Class = "Option";
		$this->HTML = "";
	}
	
	function Render()
	{
		$ArrayCount = count($this->ListItem);
		
		if($ArrayCount > count($this->ListData))
		{
			$this->HTML .= "Invalid Class Definition";
			return 0;
		}
		
		$this->HTML .= "<select name='$this->SelectName' class='$this->Class' style='$this->Style'>";
		for($i = 0; $i < $ArrayCount; $i++)
		{
			if($this->SelectedData == $this->ListData[$i])
			        $sel = "selected class=SelectedOption";
			else
			        $sel = "class=$this->Class";
			
			$this->HTML .= "\n\t<option value='" . $this->ListData[$i] . "' $sel>" . $this->ListItem[$i] . "</option>";
		}
		$this->HTML .= "</select>";
		
		return 1;
	}               
}
		
?>