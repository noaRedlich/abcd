<table width=655>
<td valign=top>

<table width="450" border="0" cellspacing="0" cellpadding="0">
<tr><td width=1% class=text bgcolor=666666 nowrap><b><font color=FFFFFF>&nbsp;&nbsp;PLUG-IN CONFIGURATOR&nbsp;&nbsp;</td><td width=99%></td></tr>
<tr><td height=2 bgcolor=666666 colspan=2><spacer type=block height=2></td></tr>
<tr><td height=4 bgcolor=A0A0A0 colspan=2><spacer type=block height=4></td></tr>
<tr><td colspan=2>

<table border=0 cellspacing=3 cellpadding=3 width=100%>
<form name=plugin_selector>
<tr><td class=text><b>Select plug-in:</td><td align=right>

<select name=selected_plugin style="width:150px" onChange="if(this.options[this.selectedIndex].value == '') this.selectedIndex=0; else window.location.href='main.php?plugin_name='+this.options[this.selectedIndex].value">
<?
			// 	Get current plug-in name and printing
			//	List of plug-ins to edit

			global $xsName;

			$sPlugList = plugGetPluginsList();

			while(list($k, $v) = each($sPlugList))
			{
				$pParser = new CModXMLParser("plug-ins/$v/options.xml");
				$pTranslator = new CModXMLCTTranslator($pParser->mxmlGetBTree());

				$xsName = "";
				@eval($pTranslator->sCode);

				echo "<option value='$v'";
				if($v == $current_plugin_name)
				{
					$sPlugInPath = "plug-ins/$current_plugin_name/options.xml";
					echo " selected";
				}

				if($xsName != "")
				{
					$v = $xsName;
				}

				echo ">$v\n";
			}
?>
</select>

</td></tr>
</form>
</table>
</td></tr>

</table>
	<?
			$pParser = new CModXMLParser($sPlugInPath);
			$pTranslator = new CModXMLCTTranslator($pParser->mxmlGetBTree(), 0);
			$pTranslator->mxtTranslateAsForm(0);


			if($action == save_configuration)
			{
				$pTranslator->mxtValidateFields(0);

				if(sizeof($pTranslator->sErrors))
				{
					echo '<table width="450" border="0" cellspacing="0" cellpadding="0">'.
							 '<tr><td class="small" bgcolor=FF0000 height=4 colspan=2><spacer type=block height=4 width=100%></td></tr>'.
							 '<tr><td class="small" width=440><b>ERRORS IN Parameters</td><td width=10 bgcolor=FF0000><spacer type=block height=100% width=10></td></tr>'.
							 '<tr><td class=text colspan=2><br>';

					$c = 0;
					while(list($i, $sError) = each($pTranslator->sErrors))
					{
						echo ($i + 1).". $sError\n"."<br>";
						$c++;
					}

					echo '<br>Total: '.$c.' error(s).<br>';
					echo '<br></td></tr>';
				}
				else
				{
					$pTranslator->mxtSaveAsXML($sPlugInPath);

					$pParser = new CModXMLParser($sPlugInPath);
					$pTranslator = new CModXMLCTTranslator($pParser->mxmlGetBTree(), 0);
					$pTranslator->mxtTranslateAsForm(0);
				}
			}
	?>

	<table width="450" border="0" cellspacing="0" cellpadding="0">
	<form action="main.php?action=save_configuration" method=post>
	<tr>
		<td bgcolor=A0A0A0 height=4 colspan=2><spacer type=block height=4 width=100%></td>
	</tr>
	<tr>
		<td class=text colspan=2 align=right><br>
			<input type=submit value="Save configuration">&nbsp;
			<input type=reset value="Cancel">&nbsp;
		</td>
	</tr>
		<?
			echo $pTranslator->sCode;
		?>

	<tr>
		<td bgcolor=FFFFFF height=6 colspan=2><spacer type=block height=6 width=100%></td>
	</tr>
	<tr>
		<td bgcolor=A0A0A0 height=4 colspan=2><spacer type=block height=4 width=100%></td>
	</tr>
	<tr>
		<td class=text colspan=2 align=right><br>
			<input type=submit value="Save configuration">&nbsp;
			<input type=reset value="Cancel">&nbsp;
			<br><br>
		</td>
	</tr>
  <tr>
		<td bgcolor=A0A0A0 height=4 colspan=2><spacer type=block height=4 width=100%></td>
	</tr>
	</form>
</table>
<table width="450" border="0" cellspacing="0" cellpadding="0">
<tr><td height=2 bgcolor=666666 colspan=2><spacer type=block height=2></td></tr>
</table>

</td>
<td valign=top>

<?
		sysActivateObject(PluginConfigurator);
?>

</td>
</table>
