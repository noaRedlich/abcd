<?php

// Data functions for table realtycontracts

// This script and data application were generated by AppGini 2.4 on 25/06/2003 at 19:54:53
// Download AppGini for free from http://www.bigprof.com/appgini/download/

function insert()
{
	global $HTTP_SERVER_VARS, $HTTP_GET_VARS, $HTTP_POST_VARS, $userID;
	
	if(get_magic_quotes_gpc())
	{
		$contract_number = $HTTP_POST_VARS["contract_number"];
		$customer = $HTTP_POST_VARS["customer"];
		$listingid = $HTTP_POST_VARS["listingid"];
		$term = $HTTP_POST_VARS["term"];
		$amount = $HTTP_POST_VARS["amount"];
		$currency = $HTTP_POST_VARS["currency"];
		$usd_rate = $HTTP_POST_VARS["usd_rate"];
	}
	else
	{
		$contract_number = addslashes($HTTP_POST_VARS["contract_number"]);
		$customer = addslashes($HTTP_POST_VARS["customer"]);
		$listingid = addslashes($HTTP_POST_VARS["listingid"]);
		$term = addslashes($HTTP_POST_VARS["term"]);
		$amount = addslashes($HTTP_POST_VARS["amount"]);
		$currency = addslashes($HTTP_POST_VARS["currency"]);
		$usd_rate = addslashes($HTTP_POST_VARS["usd_rate"]);
	}
	
	sql("insert into realtycontracts (user_id, contract_number, customer, listingid, term, amount, currency, usd_rate) values ('$userID'," . (($contract_number != "") ? "'$contract_number'" : "NULL") . ", " . (($customer != "") ? "'$customer'" : "NULL") . ", " . (($listingid != "") ? "'$listingid'" : "NULL") . ", " . (($term != "") ? "'$term'" : "NULL") . ", " . (($amount != "") ? "'$amount'" : "NULL") . ", " . (($currency != "") ? "'$currency'" : "NULL") . ", " . (($usd_rate != "") ? "'$usd_rate'" : "NULL") . ")");
	$s=mysql_insert_id();
	if ($contract_number==""){
		sql("update realtycontracts set contract_number = id where id = $s");
	}
	return $s;
}

function delete($selected_id)
{
	// insure referential integrity ...
	// child table: realtycontractpayments
	$res = sql("select id from realtycontracts where id='$selected_id'");
	$id = mysql_fetch_row($res);
	sql("delete from realtycontractpayments where contract_id='$id[0]'");
	sql("delete from realtycontracts where id='$selected_id'");
}

function update($selected_id)
{
	global $HTTP_SERVER_VARS, $HTTP_GET_VARS, $HTTP_POST_VARS;
	
	if(get_magic_quotes_gpc())
	{
		$contract_number = $HTTP_POST_VARS["contract_number"];
		$customer = $HTTP_POST_VARS["customer"];
		$listingid = $HTTP_POST_VARS["listingid"];
		$term = $HTTP_POST_VARS["term"];
		$amount = $HTTP_POST_VARS["amount"];
		$currency = $HTTP_POST_VARS["currency"];
		$usd_rate = $HTTP_POST_VARS["usd_rate"];
	}
	else
	{
		$contract_number = addslashes($HTTP_POST_VARS["contract_number"]);
		$customer = addslashes($HTTP_POST_VARS["customer"]);
		$listingid = addslashes($HTTP_POST_VARS["listingid"]);
		$term = addslashes($HTTP_POST_VARS["term"]);
		$amount = addslashes($HTTP_POST_VARS["amount"]);
		$currency = addslashes($HTTP_POST_VARS["currency"]);
		$usd_rate = addslashes($HTTP_POST_VARS["usd_rate"]);
	}

	sql("update realtycontracts set contract_number=" . (($contract_number != "") ? "'$contract_number'" : "NULL") . ", customer=" . (($customer != "") ? "'$customer'" : "NULL") . ", listingid=" . (($listingid != "") ? "'$listingid'" : "NULL") . ", term=" . (($term != "") ? "'$term'" : "NULL") . ", amount=" . (($amount != "") ? "'$amount'" : "NULL") . ", currency=" . (($currency != "") ? "'$currency'" : "NULL") . ", usd_rate=" . (($usd_rate != "") ? "'$usd_rate'" : "NULL") . " where id='$selected_id'");
}

function form($selected_id = "", $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1)
{
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.
	global $userID,$lang,$GO_THEME,$GO_CONFIG,$GO_MODULES;
	$code = "<br><table border=1  bordercolor=silver cellpadding=0 cellspacing=0><tr><td bgcolor=#efefef><div class=TableTitle >".$lang[contract]."</div></td></tr><tr><td><table>";
	$code .= "\n\t<tr><td colspan=2></td><td rowspan=70 valign=top>";
	if($AllowInsert)
		$code .= "<div><input vspace=1 type=image src=insert.gif name=insert alt='Add New Record'></div>";
	
	// combobox: listingid
	$combo_listingid = new DataCombo;
	$combo_listingid->Query = "select id, concat(ID,'. ',title) as title from listingsDB where user_ID = $userID order by ID";
	$combo_listingid->SelectName = "listingid";
	// combobox: currency
	$combo_currency = new Combo;
	$combo_currency->ListItem = explode(";;", "NIS;;USD");
	$combo_currency->ListData = explode(";;", "NIS;;USD");
	$combo_currency->SelectName = "currency";

	if($selected_id)
	{
		$res = sql("select * from realtycontracts where id='$selected_id'");
		$row = mysql_fetch_object($res);
		$combo_listingid->SelectedData = $row->listingid;
		$combo_currency->SelectedData = $row->currency;


		if($AllowUpdate)
			$code .= "<div><input type=image src=update.gif  vspace=1 name=update alt='Update Record'></div>";
		if($AllowDelete)
			$code .= "<div><input type=image  vspace=1 src=delete.gif name=delete alt='Delete Record' onclick='return confirm(\"Are you sure you want to delete this record permanently?\");'></div>";
		$code .= "<div><input type=image  vspace=1 src=cancel_search.gif name=deselect alt='Deselect Record'></div>";
		$code .= "<div><img  vspace=1 onclick='openPrint()' src=print.gif name=deselect alt='Print'></div>";
		$code .= "<BR><a href='realtycontractpayments_view.php?FilterField1=contract_id&FilterOperator1=".urlencode("<=>")."&FilterValue1=$selected_id'><img vspace=1 src=payments.gif border=0></a>";
		$code .= "<br><a href='edit_my_listings.php?edit=$row->listingid'><img src=listing.gif  vspace=1 border=0></a></div>";
		$code .= "
		<script>
		function openPrint(){
			window.open('print.php?contract_id=$selected_id','','scrolling=yes,width=400,height=400,left=100,top=100');
		}
		</script>
		";
	}
	$combo_listingid->Render();
	$combo_currency->Render();

	$code .= "</td></tr>";


	// Detail view form fields
	$code .= "\n\t<tr><td class=TableHeader valign=top><div class=TableHeader style='text-align:right;'>".$lang[number]."</div></td><td class=><input size= 20 type=text class=TextBox name=contract_number value='$row->contract_number'>&nbsp;&nbsp;</td></tr>";
	$code .= "\n\t<tr><td class=TableHeader valign=top><div class=TableHeader style='text-align:right;'>".$lang[customer]."</div></td><td class=><input size= 45 type=text class=TextBox name=customer value='$row->customer'>
	<a href='javascript:open_addressbook(\"customer\", document.myform.customer.value)'><img src='".$GO_THEME->image_url."buttons/addressbook.gif' width=16 height=16 border=0 /></a>
		</td></tr>
		<script>
		function open_addressbook(field, address_string)
		{
		popup('". $GO_CONFIG->host."contacts/select.php?returnfield=name&SET_HANDLER=".$GO_CONFIG->host.$GO_MODULES->path."add_contacts.php&SET_FIELD= '+field+'&address_string='+escape(address_string),'550','400');
		}
		</script>		
	
	</td></tr>";
	$code .= "\n\t<tr><td class=TableHeader valign=top><div class=TableHeader style='text-align:right;'>".$lang[listing]."</div></td><td class=>$combo_listingid->HTML&nbsp;&nbsp;</td></tr>";
	$code .= "\n\t<tr><td class=TableHeader valign=top><div class=TableHeader style='text-align:right;'>".$lang[term]."</div></td><td class=><input size=10 type=text class=TextBox name=term value='$row->term'>&nbsp;&nbsp;</td></tr>";
	$code .= "\n\t<tr><td class=TableHeader valign=top><div class=TableHeader style='text-align:right;'>".$lang[amount]."</div></td><td class=><input size=10 type=text class=TextBox name=amount value='$row->amount'>&nbsp;&nbsp;</td></tr>";
	$code .= "\n\t<tr><td class=TableHeader valign=top><div class=TableHeader style='text-align:right;'>".$lang[currency]."</div></td><td class=>$combo_currency->HTML&nbsp;&nbsp;</td></tr>";
	$code .= "\n\t<tr><td class=TableHeader valign=top><div class=TableHeader style='text-align:right;'>".$lang[usd_rate]."</div></td><td class=><input size=10 type=text class=TextBox name=usd_rate value='$row->usd_rate'>&nbsp;&nbsp;</td></tr>";
	//$code .= "\n\t<tr><td class=TableHeader valign=top><div class=TableHeader style='text-align:right;'>Code</div></td><td class=TableBody>$row->id&nbsp;&nbsp;</td></tr>";

	$code .= "</table></td></tr></table></center>";

	return $code;
}
?>