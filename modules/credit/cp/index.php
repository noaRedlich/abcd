<?
	require "lib/mod_xml.lib";
	require "lib/mod_xml_ct.lib";
	require "lib/system.lib";

	require "services/BD3LoadConfiguration.service";
	require "services/BD3LoadDBDevice.service";
	require "services/BD3ForwardRoutines.service";
	$sSystemSource = "services/BD3Login.service";
	$db = c();
   
        if (ereg("ERROR", $b, $matches))
        { echo $b; exit;}

	if($action != "login")
	{
	 	setcookie("bd3Auth");

		if($QUERY_STRING != "")
		{
			$QUERY_STRING = base64_decode($QUERY_STRING);
			parse_str($QUERY_STRING);
		}

		//	Handling new actions
		switch($action)
		{
			case change_password:
				//	Now we must to check bd3AuthFlag and compare it with
				//	'user_id' variable value

				if(!sysIsBD3AuthFlagTrue())
				{
					break;
				}
				$sSystemSource = "services/BD3ChangePassword.service";
				break;

			case validate_new_password:
				if(!sysIsBD3AuthFlagTrue())
				{
					break;
				}

				//	Checking input variables - pswd, new_pswd, new_pswd_1

				if($pswd == "" || $new_pswd == "" || $new_pswd != $new_pswd_1 || $pswd == $new_pswd)
				{
					$sError = "Invalid input data!";
				}

				//	Comparing 'pswd' with database's record for user with
				//	current 'user_id'

				$rUser = q("SELECT login FROM webDate_bd_users WHERE id='$user_id' AND pswd='".sysCrypt($pswd)."'");
				if(e($rUser) && $sError == "")
				{
					$sError = "You entered your wrong current password!";
				}

				if($sError != "")
				{
					$sSystemSource = "services/BD3ChangePassword.service";
					break;
				}

				//	Changing user's password and logging user in
				q("UPDATE webDate_bd_users SET pswd='".sysCrypt($new_pswd)."', status='0' WHERE id='$user_id'");
				$fUser = f($rUser);

				$login = $fUser[ login ];
				$pswd = $new_pswd;

				require "services/BD3LoginRoutines.service";
				break;
		}
	}
	else
	{
		session_start();
		$_SESSION["admin_charset"]=$HTTP_POST_VARS[lang];
		//echo "****".$_SESSION["admin_charset"];
		require "services/BD3LoginRoutines.service";
	}

?>
<html>
<head>
<title> Administrator Log In</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="style.css" type="text/css">
</head>

<body bgcolor="#CCCCCC" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" height="100%">
  <tr>
    <td>
      <table border="0" cellspacing="1" bgcolor="#999999" cellpadding="0" width="620" height="364" align="center">
        <tr>
          <td>
            <table border="0" cellspacing="8" cellpadding="8" bgcolor="#FFFFFF" width="620">
              <tr>
                <td align="center"> <div align=left></div> <br>
                  <hr noshade size="1" color=C0C0C0>
                  <br>
                  <br>
                  <?
			//	Include system source (this value defined by action's hanlder)
			@include $sSystemSource;
		?>
                </td>
              </tr>
              <tr>
                <td>
<table width="100%" border="0" height="50">
                    <tr>
                      <td colspan="2">
               
                      </td>
                    </tr>
                    
                    <tr>
                      <td colspan="2">
                     
                      </td>
                    </tr>
                  </table>
                  <br>
                
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

    </td>
  </tr>
</table>
<br>
</body>
</html>
<?

        d($db);
?>
