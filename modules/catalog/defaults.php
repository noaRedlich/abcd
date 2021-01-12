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
	
$lang =  $GO_LANGUAGE->language;	

// Default language
switch ($lang){
case "Hebrew":$def_admin_language=$def_language="he";break;
case "Russian":$def_admin_language=$def_language="ru";break;
case "English":$def_admin_language=$def_language="en";break;
}

// specify your full url to the script, don't use slash in the end!
// MAKE SURE IT POINTS TO THE DIRECTORY WHERE INDEX.PHP IS LOCATED!
$def_mainlocation="http://www.vcx.co.il/modules/catalog";

// admin's e-mail, will be used on all pages and in all outgoing messages
$def_adminmail="info@netcity.co.il";

// title for all pages
$def_title="NetCity Products and Services Catalog";

// ADD COUNTRIES (PLEASE, SET IT UP ONLY ONCE, DURING INSTALLATION)
// Please, do not change it on the working system.
$def_country_allow = "NO";

// ADD STATES (PLEASE, SET IT UP ONLY ONCE, DURING INSTALLATION)
// Please, do not change it on the working system.
$def_states_allow = "NO";

// PayPal

// use PayPal?
$def_paypal_allowed="NO"; // YES or NO (upper case, please)
$def_paypal_amount="0.01"; // USD amount to charge for registration
$def_paypal_item="Directory Listing"; //Item name
$def_paypal_email="andy@efox.org"; // Your PayPal merchant registered email

$def_paypal_expiration="365"; // When PREMIUM listing will expire and
                              // will become STANDARD (if not upgraded again),
                              // in days

$def_paypal_expiration_warning="5"; // Ask PREMIUM listing holder to upgrade
                                    // again and warn about expiration by
                                    // email in X days

/*
 Allow IPN in your PayPal acount!

  Just login to your account, goto "Profile", in "Selling Preferences"
 select "Instant Payment Notification Preferences", allow Instant
 Payment Notification and insert the full url to reg.php 
*/

// URL where to return PayPal IPN results
$def_return_url="http://www.vcx.co.il/reg.php";
// URL where to return, if submission was cancelled
$def_cancel_return="http://www.vcx.co.il/";

// Button title to pay for listing or upgrade
$def_paypal_button="Upgrade to Premium";


// Allow logo uploading for Standard listings
$def_standard_logo_allow="YES";  // YES or NO (upper case, please)

// Allow banner uploading for Standart listings
$def_standard_banner_allow="YES"; // YES or NO (upper case, please)

// Show map for Standart listings
$def_standard_map_allow="YES"; // YES or NO (upper case, please)

// Allow Send Message from a visitor for Standart listings
$def_standard_message_allow="YES"; // YES or NO (upper case, please)


// MAPS

// allow maps in listings
$def_map_allowed="NO"; // YES or NO (upper case, please)

// country to use with maps (do not change, if you use countries)
$def_map_country="USA";

// RATINGS
// allow RATINGS
$def_rating_allowed="NO"; // YES or NO (upper case, please)

// allow BANNERS uploading and rotating for all listing types
$def_banner_allowed="YES"; // YES or NO (upper case, please)

// allow PICTURES FOR PRICE LINES for all listing types
$def_price_pics_allowed="YES"; // YES or NO (upper case, please)


// LOGO SIZE
// Maximum Logo WIDTH
$def_logo_width="300";
// Maximum Logo HEIGHT
$def_logo_height="100";
// Maximum logo SIZE (bytes)
$def_logo_size="20000";


// BANNER SIZE
// Maximum Banner WIDTH
$def_banner_width="468";
// Maximum Banner HEIGHT
$def_banner_height="100";
// Maximum Banner SIZE (bytes)
$def_banner_size="25000";


// OFFERS LIST PICTURE WINDOWS SIZE
// width
$def_offers_window_width="500";
// height
$def_offers_window_height="350";


// OFFER PICTURE MAXIMUM SIZE
// Maximum WIDTH
$def_offer_pic_width="400";
// Maximum HEIGHT
$def_offer_pic_height="400";
// Maximum SIZE (bytes)
$def_offer_pic_size="50000";


// Default stylesheet
$def_style="

<link rel=\"stylesheet\" href=\"css.css\">

";

// Default HELP&MENU frames background color
$def_help_background="#FFFFFF";

// Default central frame background color
$def_background="#FAFAF4";

// Empty category font color
$def_cat_font_color_empty="#BBBBBB";

// Default status line
$def_status_color="#9EAAB3";
$def_status_font_color="#FFFFFF";

// Default form header
// header color
$def_form_header_color="#DADEDE";
// header font color
$def_form_header_font_color="black";
// header font size
$def_form_header_fontsize="1";

// form background color
$def_form_back_color="#F5F3EA";

// PREMIUM background color
$def_form_back_color_premium="#F2EAEE";


// Subject of the letter about continuing the membership, if expired.
$warn_subject = "Please, continue your Premium Membership.";

// Letter about continuing the membership, if expired.
$warn_letter = "Good day,

 You have $def_paypal_expiration_warning more days to continue your Premium
Subscription. Please, visit your user section and upgrade to Premium to get
another $def_paypal_expiration days of Premium Membership.

Thanks.
";

// Subject of the letter about discontinuing the membership, if expired.
$discontinued_subject = "Your Premium Membership is discontinued.";

// Letter about discontinuing the membership, if expired.
$discontinued_letter = "Good day,

 Your Premium Subscription was discontinued. Please, visit your user section
and upgrade to Premium to get another $def_paypal_expiration days of Premium
Membership.

Thanks.
";


// Including the language pack
require("../../catalog/lang/language.$def_language.php");

?>
