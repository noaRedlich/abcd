<?	

require("../../Group-Office.php");
	
	
	
	//authenticate the user
	//if $GO_SECURITY->authenticate(true); is used the user needs admin permissons
	$GO_SECURITY->authenticate(false);
	if (strpos($PHP_SELF,"/catalog_admin/")){
		$GO_MODULES->authenticate('catalog_admin');
		$_SESSION[admin]=1;
	}
	else{
		$GO_MODULES->authenticate('catalog');
	}

	//see if the user has access to this module
	//for this to work there must be a module named 'example'

	//set the page title for the header file
	$page_title = "Business catalog admin";
	//require the header file. This will draw the logo's and the menu
	// there is also the simple_header.inc file without logo's and menu
	if (!$noheader){
	if ($simple)
		require($GO_THEME->theme_path."simple_header.inc");
	else
	require($GO_THEME->theme_path."header.inc");
	}
	
	
// Default language
$def_admin_language="he"; // English


$lang =  $GO_LANGUAGE->language;	
// Default language
switch ($lang){
case "Hebrew":$def_admin_language=$def_language="he";break;
case "Russian":$def_admin_language=$def_language="ru";break;
case "English":$def_admin_language=$def_language="en";break;
}

// ADD COUNTRIES (PLEASE, SET IT UP ONLY ONCE, DURING INSTALLATION)
// Please, do not change it on the working system.

$def_country_allow = "NO";

// ADD STATES (PLEASE, SET IT UP ONLY ONCE, DURING INSTALLATION)
// Please, do not change it on the working system.

$def_states_allow = "NO";

// From whom should I send the message about registration
$from="info@netcity.co.il";


// Message subjects
$approved_subject="[+] Your registration was approved";
$rejected_subject="[-] Your registration was rejected";
$rejected_banner="[-] Your banner was removed";
$rejected_logo="[-] Your logo was removed";


// Message about registration approvement
$approved_message = "
 Good Day.

  Thanks for submitting your business!
 We look forward to working with you and helping you achieve success with our service.
 Since now your information is available for searching.

  You can edit your records any time, using your login and password.

";


// Message about rejected registration
$rejected_message= "
 Good day.

 Sorry, your registration was not approved.
 Possible reasons:
 - Your business is already in our database;
 - Your contact information is incorrect;
 - other reasons.

";


// Message about removed banner
$banner_message= "
 Good day.

 Sorry, but we've removed your banner from the system.
 Possible reasons:
 - your banner image is invalid;
 - it's not connected with your business;
 - other reasons.

";

// Message about removed banner
$logo_message= "
 Good day.

 Sorry, but we've removed your logo from the system.
 Possible reasons:
 - your logo image is invalid;
 - it's not connected with your business;
 - other reasons.

";

// Default stylesheet
$def_style="


";

// Default HELP&MENU frames background color
$def_help_background="#FFFFFF";

// Default central frame background color
$def_background="#FAFAF4";
 
// Default status line
$def_status_color="#9EAAB3";
$def_status_font_color="#FFFFFF";

// Default form header
// header color
$def_form_header_color="DEDEDE";
// header font color
$def_form_header_font_color="black";
// header font size
$def_form_header_fontsize="1";

// form background color
$def_form_back_color="#F5F3EA";


// LOGO SIZE
// Maximum Logo WIDTH
$def_logo_width="500";
// Maximum Logo HEIGHT
$def_logo_height="200";
// Maximum logo SIZE (bytes)
$def_logo_size="50000";
// Including admin language file
require ("lang/adm.$def_admin_language.php");

?>
