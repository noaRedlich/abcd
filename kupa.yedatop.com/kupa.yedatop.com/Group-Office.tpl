<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

/////////////////////////////////////////////////////////////////////////////////
/////////////////////          Server configuration           ///////////////////
/////////////////////////////////////////////////////////////////////////////////

class GO_CONFIG
{
	#FRAMEWORK VARIABLES

	#slash to use '/' for linux and '\\' for windows
	var $slash = "%slash%";

	#default language
	var $language = "%language%";

	#default theme
   	var $theme = "%theme%";

	#Allow change of theme
	var $allow_themes = %allow_themes%;

	#Group-Office Version
	var $version = "1.05";

	#hostname whithout http://
	var $hostname = "%hostname%";

	#protocol to put before hostname
	var $protocol = "%protocol%";

	#title of group-office
	var $title = "%title%";

	#the person that gets emails at special events
	var $webmaster_email = "%webmaster_email%";

	#the path to the root of group-office ends with slash
	var $root_path = "%root_path%";

	#temporary files ends with slash
	var $tmpdir = "%tmpdir%";

	#The maximum number of users
	var $max_users = 0;

	#database
	var $db_type = "%db_type%";
	var $db_host = "%db_host%";
	var $db_name = "%db_name%";
	var $db_user = "%db_user%";
	var $db_pass = "%db_pass%";

	#FILE BROWSER VARIABLES

	#the path to the location where the files of the file browser module are stored
	#this should NEVER be inside the document root of the webserver
	#this directory should be writable by apache. Also choose a partition that
	#has enough diskspace.

	var $mime_types_file = '/etc/mime.types';
	var $file_storage_path = "%file_storage_path%";

	var $create_mode = %create_mode%;

	#The maximum file size the filebrowser attempts to upload
	#note that the php.ini file must be set accordingly.(www.php.net)

	var $max_file_size = "%max_file_size%";

	#EMAIL VARIABLES

	#smtp server. leave empty when using local sendmail
	var $smtp_server = "%smtp_server%";
	var $smtp_port = "%smtp_port%";
	var $max_attachment_size = "%max_attachment_size%";

	###############################################################################
	#######################        LINUX ONLY     #################################
	###############################################################################

	#System account creation. This will allow users to create a system account to recieve email on
	#this machine.

	var $enable_system_accounts = %enable_system_accounts%;
	var $enable_system_control = %enable_system_control%;
	#################################only set below when system accounts or control is enabled###################
	#Location of sudo /etc/sudoers must be configured correctly. Apache must have access to useradd and chpasswd
	var $sudo = '/usr/bin/sudo';
	var $du = '/usr/bin/du';
	#location of reboot
	var $reboot = '/sbin/reboot';
	#location of poweroff
	var $poweroff = '/sbin/poweroff';
	#location of useradd
	var $useradd = '/usr/sbin/useradd';
	var $userdel = '/usr/sbin/userdel';
	#location of chpasswd
	var $chpasswd = '/usr/sbin/chpasswd';
	#the shell for the users. set to /bin/false for no shell access
	var $shell = '/bin/false';
	#location to scripts for samba user management
	var $auto_smbadduser = '/usr/sbin/auto_smbadduser.exp';
	var $auto_smbpasswd = '/usr/sbin/auto_smbpasswd.exp';
	var $smbdeluser = '/usr/bin/smbpasswd -x';

	#The E-mail addresses hostname. E-mail addresses will be username@inmail_host
	var $inmail_host = "%inmail_host%";
	var $inmail_port = "%inmail_port%";
	var $inmail_type = "%inmail_type%";
	var $inmail_root = "%inmail_root%";
	#the name of this host and optionally some connect options for IMAP of POP3
	#like '/notls' for redhat servers that need this somehow
	var $local_email_host = "%local_email_host%";

	#Create Samba fileserver users
	var $enable_samba_accounts = %enable_samba_accounts%;
	#################################only set above when system accounts are enabled###################

	/////////////////////////////////////////////////////////////////////////////////
	/////////////////////      Do not change underneath this      ///////////////////
	/////////////////////////////////////////////////////////////////////////////////

	#relative to root_path no slash at end
	var $module_path = 'modules';
	var $class_path = 'classes';
	var $control_path = 'controls';
	var $control_url = 'controls';
	var $theme_path = 'themes';
	var $language_path = 'language';

   	var $host;
	var $default_filetype_icon = 'lib/icons/default.gif';
	var $window_mode = 'normal';

	function GO_CONFIG()
	{
		#URL to host
		$this->host = $this->protocol.$this->hostname."/";

		#path to classes
		$this->class_path = $this->root_path.$this->class_path.$this->slash;

		#path to controls
		$this->control_path = $this->root_path.$this->control_path.$this->slash;

		#url to controls
		$this->control_url = $this->host.$this->control_url.$this->slash;
		
		#path to modules
		$this->module_path = $this->root_path.$this->module_path.$this->slash;

		#filetype icon
		$this->default_filetype_icon = $this->root_path.$this->default_filetype_icon;
	}

	//gets the date formats
	function get_date_formats()
	{
		$formats[] = "d-m-Y H:i";
		$formats[] = "m-d-Y H:i";
		//$formats[] = "D j M Y H:i";
		return $formats;
	}

	function get_date_formats_friendly()
	{
		$f_formats[] = "dd-mm-yyyy hh:mm";
		$f_formats[] = "mm-dd-yyyy hh:mm";
		//$f_formats[] = "Fri 1 jan H:i";
		return $f_formats;
	}


}

/////////////////////////////////////////////////////////////////////////////////
/////////////////////       Group-Office initialisation        //////////////////
/////////////////////////////////////////////////////////////////////////////////


//load configuration
$GO_CONFIG = new GO_CONFIG();

//database class library
require_once($GO_CONFIG->root_path.'database/'.$GO_CONFIG->db_type.".class.inc");
require($GO_CONFIG->class_path."modules.class.inc");
require($GO_CONFIG->class_path."security.class.inc");
require($GO_CONFIG->class_path."controls.class.inc");

//setting session save path is required for some server configuration
session_save_path($GO_CONFIG->tmpdir);

require($GO_CONFIG->root_path."functions.inc");

//load language management class
require($GO_CONFIG->class_path."language.class.inc");
$GO_LANGUAGE = new GO_LANGUAGE();
if (isset($SET_LANGUAGE))
{
	$GO_LANGUAGE->set_language($SET_LANGUAGE);
}

require($GO_LANGUAGE->get_language_file('common'));

require($GO_CONFIG->class_path."theme.class.inc");
$GO_THEME = new GO_THEME();
if (isset($SET_THEME) && $GO_CONFIG->allow_themes)
{
	$GO_THEME->set_theme($SET_THEME);
}

//load base classes
$GO_MODULES = new GO_MODULES();
$GO_SECURITY = new GO_SECURITY();

?>
