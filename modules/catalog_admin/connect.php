<?
ini_set("error_reporting","E_ERROR & E_CORE_ERROR & E_COMPILE_ERROR");

//---------------------------------------------------------------------------

  $db_host="localhost"; // Your database host server, eg. db.server.com
  $db_user="root";      // User who has full access to that database
  $db_pass="";      // User's password to access database
  $db_name="groupoffice";      // Database title

// Tables


  $db_users="dev_users_".$def_admin_language;
  $db_offers="dev_offers_".$def_admin_language;
  $db_rating="dev_rating";

  $db_category="dev_category";
  $db_subcategory="dev_subcategory";

  $db_location="dev_location";
  $db_states="dev_states";

  $db_admin="dev_admin";
  $db_log="dev_log";

//---------------------------------------------------------------------------
//Error_Reporting(E_ERRORS);

 if(!mysql_connect("$db_host","$db_user","$db_pass"))
	{
	echo "SQL_ERROR: Cannot connect to DB: $db_name<br>"; exit();
	}

  mysql_select_db("$db_name") or die("SQL_ERROR: Cannot select DB: $db_name");

  unset($db_host);
  unset($db_user);
  unset($db_pass);

?>