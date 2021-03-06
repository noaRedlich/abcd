<?
if (false)//Server Offline
{
    echo '<html dir=\'rtl\'>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
    </head>
    <body>
        <br/>
        <br/>
        <center>
            <b style=\'color:red\'>
                ��� ����� ������� ���� �������  ���� ������
                <br>
                ���� �-3 ������
                ��� ����� 10:00 - 13:00
                <br>
                ��� �������. ����� ���� �� ����� ���. 072-2125588
            </b>
        </center>
        <br>';
    exit();
}
////////////////////////////////////////////////////////////////////
// Author: Merijn Schering <mschering@hilckmanngroep.com>
// Version: 1.0 Release date: 14 March 2003
////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////
/////////////////////          Server configuration           ///////////////////
/////////////////////////////////////////////////////////////////////////////////

    class GO_CONFIG
    {
        var $obfuscate = false;
        var $useradmin = "administrator";
        var $account_expiration_days = 30;
        var $user_disk_quota = 5000000;
        #FRAMEWORK VARIABLES
        var $db_charset = "utf8";
        #slash to use '/' for linux and '\\' for windows
        var $slash = "/";
        #default language
        var $language = "hebrew";
        #default theme
        var $theme = "blue";
        #Allow change of theme
        var $allow_themes = true;
        #Group-Office Version
        var $version = "1.05";
        #hostname whithout http://
        var $hostname = "localhost:8086";
        #protocol to put before hostname
        var $protocol = "http://";
        #title of group-office
        var $title = "YedaTop";
        var $admin_phone = "09-8871972";
        var $support_email = "support@netcity.co.il";
        #the person that gets emails at special events
        var $webmaster_email = "info@netcity.com";
       #the path to the root of group-office ends with slash
        var $root_path = "/home/mlaitech/office.yedatop.com/";
        #temporary files ends with slash
        var $tmpdir = "/home/mlaitech/office.yedatop.com/tmp/";
        #path to images
        var $image_url = "http://office.yedatop.com/themes/blue-grey/images/";
        #The maximum number of users
        var $max_users = 0;
        #database
        var $db_type = "mysql";
        var $db_host = "localhost";
        var $db_name = "vcx00";
        var $db_user = "vcx";
        var $db_pass = "HwvaDPcfu2udFcz5";

        var $stock_db_name = "vcx_weberp";
        var $stock_db_user = "vcx";
        var $stock_db_pass = "HwvaDPcfu2udFcz5";

        var $provider_db_name = "prf_smart_profit";
        var $provider_username = "smart_profit";
        var $provider_maindb_name = "prf_weberp";
        var $provider_officedb_name = "prf00";
        var $provider_db_user = "prf";
        var $provider_db_pass = "p0r4f4";

        var $provider_storage_path = "/home/vhosts/prf/officefiles/";
        #FILE BROWSER VARIABLES
        #the path to the location where the files of the file browser module are stored
        #this should NEVER be inside the document root of the webserver
        #this directory should be writable by apache. Also choose a partition that
        #has enough diskspace.
        var $mime_types_file = '/etc/mime.types';
        var $file_storage_path = "/home/mlaitech/office.yedatop.com/officefiles/";
        var $transactions_path = "/home/mlaitech/office.yedatop.com/transactions/";
        var $ftplog_path = "/home/mlaitech/mlaitech.info/ftplog/";
        #var $image_url;
        var $create_mode = 0755;
        #The maximum file size the filebrowser attempts to upload
        #note that the php.ini file must be set accordingly.(www.php.net)
        var $max_file_size = "100000000";
        #EMAIL VARIABLES
        #smtp server. leave empty when using local sendmail
        var $smtp_server = "";
        var $smtp_port = "";
        var $max_attachment_size = "10000000";
        ###############################################################################
        #######################        LINUX ONLY     #################################
        ###############################################################################
        #System account creation. This will allow users to create a system account to recieve email on
        #this machine.
        var $enable_system_accounts = false;
        var $enable_system_control = false;
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
        var $inmail_host = "";
        var $inmail_port = "";
        var $inmail_type = "";
        var $inmail_root = "";
        #the name of this host and optionally some connect options for IMAP of POP3
        #like '/notls' for redhat servers that need this somehow
        var $local_email_host = "";
        #Create Samba fileserver users
        var $enable_samba_accounts = false;
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
            global $DOCUMENT_ROOT;
            $this->hostname = $_SERVER["HTTP_HOST"];
            $this->protocol = $_SERVER["HTTPS"]?"https://":"http://";
            $this->host = $this->protocol.$this->hostname."/";
            $this->root_path = $DOCUMENT_ROOT;

            if (substr($this->root_path,strlen($this->root_path))!="/") $this->root_path.="/";
            if (!$this->image_url) $this->image_url = $this->host."themes/blue-grey/images/";
            //if($_GET['newskin']==1){
                $this->theme = 'blue';
                $this->image_url = $this->host."themes/blue/images/";
            //}
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
    /////////////////////       Group-Office initialisation        /////////
    /////////////////////////////////////////////////////////////////////////////////
    //load configuration
    $GO_CONFIG = new GO_CONFIG();
    //ini_set("error_reporting","E_ERROR & E_CORE_ERROR & E_COMPILE_ERROR");
    //database class library
    require_once($GO_CONFIG->root_path.'database/'.$GO_CONFIG->db_type.".class.inc");
    require($GO_CONFIG->class_path."modules.class.inc");
    require($GO_CONFIG->class_path."security.class.inc");
    require($GO_CONFIG->class_path."controls.class.inc");
    require_once($GO_CONFIG->class_path . 'database_pdo.class.inc');
    require_once($GO_CONFIG->class_path . 'translates.class.inc');
    //setting session save path is required for some server configuration
    session_save_path($GO_CONFIG->tmpdir);
    require($GO_CONFIG->root_path."functions.inc");
    //load language management class
    require($GO_CONFIG->class_path."language.class.inc");
    $GO_LANGUAGE = new GO_LANGUAGE();

    if (isset($SET_LANGUAGE)) $GO_LANGUAGE->set_language($SET_LANGUAGE);

    require($GO_LANGUAGE->get_language_file('common'));
    require($GO_CONFIG->class_path."theme.class.inc");
    $GO_THEME = new GO_THEME();
    if (isset($SET_THEME) && $GO_CONFIG->allow_themes) $GO_THEME->set_theme($SET_THEME);

    //load base classes
    $GO_MODULES = new GO_MODULES();
    $GO_SECURITY = new GO_SECURITY();
    if ($GO_SECURITY->accountIsDisabled())
    {
        echo "<center><br><br><b style='color:red'>Account is temporarily disabled. Try again in 15 minutes.<br>Contact administrator for any questions.</center>";
        die();
    }
#get translate from DB
$translate = new Translate();
$lang = $translate->getAllTranslatesForLang(($GO_LANGUAGE_NAME)?strtolower($GO_LANGUAGE_NAME):'hebrew');

function myprint($data, $exit=false){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    if ($exit) exit;
}

function _translate($name, $echo=false){
    global $lang;
    $output = (isset($lang[$name])) ? $lang[$name] : $name;
    if ($echo) {
        echo $output;
    } else {
        return $output;
    }
}
?>