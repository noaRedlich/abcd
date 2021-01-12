<?php
$newdesign=true;
@include_once("../../Group-Office.php");
include( "../../classes/phpexcel/class.writeexcel_workbook.inc.php");
include( "../../classes/phpexcel/class.writeexcel_worksheet.inc.php");
include("../../classes/adodb/adodb.inc.php");

if (!$debug)
{
	ini_set("error_reporting","E_ERROR & E_CORE_ERROR & E_COMPILE_ERROR");
}
//always set hebrew
//$GO_LANGUAGE->set_language("Hebrew");
require($GO_LANGUAGE->get_language_file('common'));

//authenticate the user
//if $GO_SECURITY->authenticate(true); is used the user needs admin permissons
if (strpos(" ".$PHP_SELF,"template_editor")){
	
	$GO_SECURITY->authenticate(true);
}
else{
	if (!strpos(" ".$PHP_SELF,"gettrans") && !strpos(" ".$PHP_SELF,"synccatalog")){
		$GO_SECURITY->authenticate();
	}
}


//see if the user has access to this module
//for this to work there must be a module named 'example'
$GO_MODULES->authenticate('shaon');

//set the page title for the header file
$page_title = "Prepaid Card";
//require the header file. This will draw the logo's and the menu
// there is also the simple_header.inc file without logo's and menu

if (!$noheader){
	if ($simple)
		require($GO_THEME->theme_path."simple_header.inc");
	else
		require($GO_THEME->theme_path."header.inc");
}

$imgPath = $GO_THEME->image_url."shaon/";  

require_once($GO_CONFIG->class_path.'users.class.inc');
//create a users object:
$users = new users();
$userID = $GO_SECURITY->user_id;
$user = $users->get_user($userID);
$username = $user[username];

if ($_GET["usr"]||$_POST["usr"]){
	$GO_MODULES->authenticate('shaon_admin');
	$userID = ($_GET["usr"])?$_GET["usr"]:$_POST["usr"];
	$user = $users->get_user($userID);
	$username = $user[username];
}		

//redirect of main user
if ($user["stock_user"]){
	$user = $users->get_user($user["stock_user"]);
	$userID = $user["id"];
	$username = $user[username];
}

///////////////////////////////////////////////////
// common include file
// you will have to set your preferences below...
// please be careful with this file -- make a backup if you're at all worried
// about screwing stuff up.

$config = array();
global $config;


///////////////////////////////////////////////////
// SITE INFORMATION
// make sure this info is accurate
$config['version'] = '1.0.7'; //Do Not Modify
// Your site's web address (don't forget the http://) - leave off the trailing slash
$config['baseurl'] = $GO_CONFIG->protocol.$GO_CONFIG->hostname.'/modules/precard';
// The actual location of openlistings on the machine -- leave off the trailing slash
// Windows users need to use double slashes eg. c:\\www\\open-realty
$config['basepath'] = $GO_CONFIG->root_path.'modules/precard';
$config['admin_name'] = 'Andrew Shaikevich'; // Your name -- all email will come from this name

$config['admin_email'] = 'info@netcity.co.il'; // all email which is sent from the site will come from this address
$config['admin_faxmail'] = "rfq@netcity.co.il";


$config['site_title'] = 'Open-Realty '.$config['version']; // Site title
$config['company_name'] = 'Open-Realty Real Estate, Inc.'; // Company Name used on Legal Page.
$config['company_location'] = 'OPEN SOURCE SOLUTION TO REAL ESTATE LISTINGS'; // Company Location for header. E.g. "of Susquehanna County, Pennsylvania"
$config['company_logo'] = '/images/title.jpg'; //Location of company logo.

///////////////////////////////////////////
//EXPORT OPTIONS
///////////////////////////////////////////
$config['export'] = 1; //Export to Baraholka? (1/0)
$config['export_path']='http://localhost:8083/heb/import.php'; //path to remote script
$config['export_expiration']='15'; //remote ad expiration in days
$config['export_password']='Hs3b8'; //should be set to remote database password
$config['export_ad_type']='1'; //remote ad type: 0-free, 1-commercial, 2-VIP
//remote categories mapping
//only the listings within the array will be exported!!!
//mask is drisha~eska~category~type (note: from right to left in hebrew! ��� - is a mask (any value)
$config['export_categories'] = array(
		"���~���~���~����"=>72,
		"���~���~���~����"=>72,
		"���~���~�����~���"=>56,
		"���~���~������~���"=>57,
		"���~�����~������~���"=>24,
		"���~�����~������~���"=>25
		);

///////////////////////////////////////////////////
// DATABASE TYPE
// default is mysql -- make sure you edit this file
// to make sure DB settings are correct!
global $db_type;

$GOCONFIG = new GO_CONFIG();

$db_user = $GOCONFIG->stock_db_user;		
$db_password = $GOCONFIG->stock_db_pass;		
$db_database = $GOCONFIG->stock_db_name;	
$db_server = $GOCONFIG->db_host;		
$db_type = $GO_CONFIG->db_type;

///////////////////////////////////////////////////
// TEMPLATE DATA
//$config[template_path] = $config[basepath].'/template/generic'; // leave off the trailing slashes
//$config[template_url] = $config[baseurl].'/template/generic'; // leave off the trailing slashes
$config['template_path'] = $config['basepath'].'/template/vertical-menu'; // leave off the trailing slashes
$config['template_url'] = $config['baseurl'].'/template/vertical-menu'; // leave off the trailing slashes

include($config['template_path'].'/style.php'); // style definitions


///////////////////////////////////////////////////
//LANGUAGE FILE PATH -- USED FOR MULTI-LANGUAGE SUPPORT
include($config['basepath'].'/include/language/'.strtolower($GO_LANGUAGE->language).'.php');


///////////////////////////////////////////////////
// DISPLAY SETTINGS
$config['listings_per_page'] = 15; //number of listings to show on one page:
$config['add_linefeeds'] = 'yes'; // convert returns to line feeds? yes or no
$config['strip_html'] = 'yes'; // Should HTML be stripped out of listings? yes or no
$config['allowed_html_tags'] ='<a><b><i><u><br>'; // which html tags can a person input?
$config['money_sign'] = '$'; // default is dollars, but it could be "&#163;" for pounds or "&#128;" for euros
$config['show_no_photo'] = 'no'; // if a listing doesn't have a photo, should it use the /images/nophoto.gif instead?
$config['number_format_style'] = '1'; // support for international numbering format. See the documentation for details

function money_formats ($number)
{
	global $config;
	// formats prices correctly
	// defaults to $123, but other folks in other lands do it differently
	// uncomment the correct one
	$output = $config['money_sign'].$number; // usa, uk - $123,345
	// $output = "$number $config['money_sign']"; // germany, spain -- 123.456,78 �
	// $output = "$config['money_sign'] $number"; // honduras -- � 123,456.78
	return $output;
}

///////////////////////////////////////////////////
// UPLOAD SETTINGS
$config['max_listings_uploads'] = 7; // max # of pics for a given listing
$config['max_listings_upload_size'] = '100000'; // (in bytes)
$config['max_listings_upload_width'] = 700; // max width (in pixels)
$config['listings_upload_path'] = $config['basepath'].'/images/listing_photos'; // leave off the trailing slash
$config['listings_view_images_path'] = $config['baseurl'].'/images/listing_photos';

$config['max_user_uploads'] = 5; // max # of pics for a given user
$config['max_user_upload_size'] = '100000';
$config['max_user_upload_width'] = 700; // max width (in pixels)
$config['user_upload_path'] = $config['basepath'].'/images/user_photos'; // leave off the trailing slash
$config['user_view_images_path'] = $config['baseurl'].'/images/user_photos';

$config['allowed_upload_types'] = array('image/pjpeg','image/jpeg','image/gif', 'image/x-png'); // allowed file types
$config['allowed_upload_extensions'] = array('jpg','gif','png'); //possible allowed file extensions
$config['make_thumbnail'] = 'yes'; // use an external thumbnailing tool to resize images
$config['thumbnail_width'] = '100'; // max width (in pixels) of thumbnails
// This line is for GD Lib Image Support
$config['path_to_thumbnailer'] = $config['basepath'].'/include/thumbnail_gd.php'; // path to the thumnailing tool

// These two lines are for ImageMagick Support
$config['path_to_thumbnailer'] = $config['basepath'].'/include/thumbnail_imagemagick.php'; // path to the thumnailing tool
//$config['path_to_imagemagick'] = '/usr/X11R6/bin/convert'; // path to the convert tool, OPTIONAL! (Fill this only if you use ImageMagick)



///////////////////////////////////////////////////
// LISTING EXPIRATION SETTINGS
$config['use_expiration'] = 'no'; // should the system use expiration? yes or no
$config['days_until_listings_expire'] = '365'; // listings should be active for this number of days


///////////////////////////////////////////////////
// USER SETTINGS
$config['allow_user_signup'] = 'yes'; // can users sign up through the web site? yes or no
$config['user_default_admin'] = 'no'; // are new users admins by default?  yes or no
// I recommend that you leave the following entries marked "no"... unless there's a particular
// reason you want new users to be able to do administrative tasks by default...
$config['user_default_feature'] = 'no'; // can new users feature listings by default? yes or no
$config['user_default_moderate'] = 'no'; // can new users moderate listings by default? yes or no
$config['user_default_logview'] = 'no'; // can new users view logs by default? yes or no
$config['user_default_editForms'] = 'no'; // can new users edit forms by default? yes or no
$config['user_default_canChangeExpirations'] = 'yes'; // can users change the expiration dates of their listings? yes or no

///////////////////////////////////////////////////
// RENTAL SEARCH SETTINGS
$config['rental_step'] = '20';
$config['max_rental_price'] = '100';
$config['min_rental_price'] = '800';

///////////////////////////////////////////////////
// MODERATION SETTINGS
$config['moderate_listings'] = 'no'; // should listings require moderator approval to be "live?" yes or no
$config['email_notification_of_new_listings'] = 'yes'; // should the site admin receive an email notification if someone adds a listing? yes or no

///////////////////////////////////////////////////
// MISCELLENEOUS SETTINGS
// you shouldn't have to mess with these things unless you rename a folder, etc...
// main file location
include($config['basepath'].'/include/main.php');

$TABLE_USERDATA = $db_database.".userdata";
$TABLE_LISTINGSSTOCKS = $db_database.".listingsStocks";
$TABLE_NEWS = $db_database.".news";
$TABLE_NEWS_USERS = $db_database.".news_users";	

// this is the setup for the ADODB library
$conn =  ADONewConnection($db_type);
$conn->Connect($db_server, $db_user, $db_password, $db_database);
$UserData = $conn->Execute("select * from $TABLE_USERDATA where username = '$username'");

$usermail = $UserData->fields["email"];
$userfax = $UserData->fields["faxmail"];

if ($UserData->fields["sql_database"])
{ 
	$conn = ADONewConnection($db_type); 
	$db_database = $UserData->fields["sql_database"];
	//$db_user = $UserData->fields["sql_user"];
	//$db_password = $UserData->fields["sql_password"];
	$conn->Connect($db_server, $db_user, $db_password, $UserData->fields["sql_database"],true); 
	if ($conn->ErrorMsg())
	{
		echo $conn->ErrorMsg();
	}    
}  
?>