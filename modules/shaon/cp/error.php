<?
	require "lib/mod_xml.lib";
	require "lib/mod_xml_ct.lib";
	require "services/BD3LoadConfiguration.service";

	if($login == "")
	{
	 	$login = "unknown user";
	}
?>
<html>
<head>
<title>Remote Web Adminstration System v. 3.0 - error</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="style.css" type="text/css">
</head>

<body bgcolor="#CCCCCC" text="#000000">
<table width="100%" border="0" height="100%">
  <tr>
    <td>
      <table border="0" cellspacing="1" bgcolor="#999999" align="center" cellpadding="0" width="620" height="364">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="8" cellpadding="8" bgcolor="#FFFFFF">
              <tr>
                <td colspan="2" valign="top">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td valign="top" align="right" colspan="2"></td>
                    </tr>
                    <tr>
                      <td valign="top" class="larger"><font color="#666666"><b><br>
                        <br>
                        </b></font><b>Access is denied.</b> Login failed for user:
                        &quot;
                        <? echo $login ?>
                        &quot;</td>
                      <td valign="top" align="right" width="120" class="larger"><br>
                        <br>
                        Date:
                        <? echo date("d.m.y") ?>
                        <br>
                        Time:
                        <? echo date("H:i") ?>
                      </td>
                    </tr>
                  </table>
                  <table width="100%" border="0" height="50" cellpadding="0" cellspacing="0">
                    <tr>
                      <td colspan="2">
                        <hr noshade size="1" color=C0C0C0>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top" width="40" height="20"><a href="index.php"><font color="#669966">[<img src="<?=$imgPath?>l.gif" width="14" height="14" border="0">]</font></a></td>
                      <td><a href="index.php"><font color="#999999">Back to the
                        main page to enter your password again</font></a></td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <hr noshade size="1" color=C0C0C0>
                      </td>
                    </tr>
                  </table>
                  <table border="0" cellpadding="2" cellspacing="2" align="center">
                    <tr>
                      <td width="10" valign="top" class="larger"><font color="#333333">1.</font></td>
                      <td class="larger"><font color="#333333">It may be that
                        you made a mistake while entering your login or password.
                        (3)<br>
                        <br>
                        </font></td>
                    </tr>
                    <tr>
                      <td width="10" valign="top" class="larger"><font color="#333333">2.</font></td>
                      <td class="larger"><font color="#333333">May be our System
                        Administrator has denied access to your account.<br>
                        <br>
                        </font></td>
                    </tr>
                    <tr>
                      <td width="10" valign="top" class="larger"><font color="#333333">3.</font></td>
                      <td class="larger"><font color="#333333">If you are not
                        abble to login and you are sure that all information you
                        are typing is true, please contact with our <a href="mailto:<? echo $admin_mail ?>">system
                        administrator</a>.<br>
                        </font></td>
                    </tr>
                    <tr>
                      <td width="10" valign="top" class="larger">4.</td>
                      <td class="larger">If you think that this error was on software
                        level, please contact with our support
                        service</a>.</td>
                    </tr>
                  </table>
                  <table width="100%" border="0" height="50">
                    <tr>
                      <td colspan="2">
                        <hr noshade size="1" color=C0C0C0>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top" width="40"><a href="license.html" target="_blank"><font color="#669966">[<img src="<?=$imgPath?>p.gif" width="14" height="14" border="0">]</font></a></td>
                      <td height="22"><a href="license.html" target="_blank"><font color="#999999">Please,
                        read users agreement before using this system</font></a></td>
                    </tr>
                    <tr>
                      <td valign="top" width="40"><font color="#669966">[<img src="<?=$imgPath?>p.gif" width="14" height="14" border="0">]</font></td>
                      <td height="22"><font color="#999999">You
                        can contact with author of this system by mail</font></a></td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <hr noshade size="1" color=C0C0C0>
                      </td>
                    </tr>
                  </table>
                  <br>
                  <table width="100%" border="0">
                    <tr>
                      <td></td>
                      <td align="right"></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>