<table width="450" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top">
            <table width="450" border="0" cellspacing="1" bgcolor="#333333" cellpadding="1">
              <tr>
                <td>
                  <table width="100%" border="0" cellspacing="3" cellpadding="3" bgcolor="#FFFFFF">
                    <tr>
                      <td colspan="2"><b>DISK INFORMATION:</b></td>
                    </tr>
                    <tr>
                      <td><u>Free on disk</u>:</td>
                      <td align="right"><? echo sprintf("%.3f", ((((diskfreespace("/"))/1024)/1024)))." Mb"; ?></td>
                    </tr>
                    <tr>
                      <td><u>Path to system</u>:</td>
                      <td align="right"><? echo ereg_replace('\\\\\\\\', '/', $HTTP_SERVER_VARS[ PATH_TRANSLATED ]); ?></td>
                    </tr>
                    <tr>
                      <td><u>URL</u>:</td>
                      <td align="right"><? echo $HTTP_HOST; ?></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">
            <table width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#333333">
              <tr>
                <td>
                  <table width="100%" border="0" cellspacing="3" cellpadding="3" bgcolor="#FFFFFF">
                    <tr>
                      <td colspan="2"><b>SYSTEM:</b></td>
                    </tr>
                    <tr>
                      <td><u>PHP Version</u>:</td>
                      <td align="right">PHP <? echo phpversion(); ?></td>
                    </tr>
                    <tr>
                      <td><u>Current DBMS</u>:</td>
                      <td align="right"><? echo $db_type ?></td>
                    </tr>
                    <tr>
                      <td><u>HTTP Server</u>:</td>
                      <td align="right"><? echo $HTTP_SERVER_VARS[ SERVER_SOFTWARE ] ?></td>
                    </tr>
                    <tr>
                      <td><u>Server Protocol</u>:</td>
                      <td align="right"><? echo $HTTP_SERVER_VARS[ SERVER_PROTOCOL ] ?></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">
            <table width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#333333">
              <tr>
                <td>
                  <table width="100%" border="0" cellspacing="3" cellpadding="3" bgcolor="#FFFFFF">
                    <tr>
                      <td colspan="2"><b>GLOBAL INFORMATION:</b></td>
                    </tr>
                    <tr>
                      <td><u>Logged in as user</u>:</td>
                      <td align="right"><? echo sysGetLoggedUserName(); ?></td>
                    </tr>
                    <tr>
                      <td><u>Logging Date/Time</u>:</td>
                      <td align="right"><? echo date("d.m.y H:i",$bd3AuthDT); ?></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
