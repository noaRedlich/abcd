<?
$simple=1;
include("connect.php");
include("defaults.php");
$r = mysql_query("select * from dev_users_".$def_admin_language." order by selector") or die(mysql_error());
echo "<table style='border-collapse:collapse' width=100% border=1 ñellspacing=0 cellpadding=2>";
echo "<tr bgcolor=darkgray align=center>
<td><b>ID</td>
<td><b>Company name</td>
</tr>";
while ($f=mysql_fetch_array($r)){?>
<tr valign=top onmouseover='this.style.backgroundColor="silver"' onmouseout='this.style.backgroundColor="white"'>
<td style='cursor:hand' onclick='window.returnValue="<?=$f[selector]?>";window.close()'><?=$f[selector]?></td>
<td style='cursor:hand' onclick='window.returnValue="<?=$f[selector]?>";window.close()'><?=$f["firmname_".$def_admin_language]?>&nbsp;</td>
</tr>
<?}?>
</table>