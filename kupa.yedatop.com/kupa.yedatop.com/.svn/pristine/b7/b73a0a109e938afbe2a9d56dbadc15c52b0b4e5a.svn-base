<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

if (isset($load_scheduler_id))
{
	$item = $scheduler->get_scheduler($load_scheduler_id);
	echo '<input type="hidden" name="load_scheduler_id" value="'.$load_scheduler_id.'" />';
}
?>
<table border="0" cellpadding="5" cellspacing="0">
<?php
if (isset($load_scheduler_id))
{
	echo "<tr><td colspan=\"2\"><a href=\"javascript:popup('scheduler_permissions.php?scheduler_id=".$load_scheduler_id."','450','380')\" class=\"normal\">".$strPermissions."</a><br /><br /></td></tr>";
}
if (isset($feedback))
{
	echo "<tr><td colspan=\"2\">".$feedback."</td></tr>\n";
}
?>
<tr>
	<td>
	<?php echo $strName; ?>:
	</td>
	<td>
	<input type="text" class="textbox" name="name" maxlength="100" size="50" value="<?php if (isset($item)) echo $item["name"]; ?>" />
	</td>
</tr>
<tr>
	<td colspan="2">
        <?php
        $button = new button($cmdOk,"javascript:save_scheduler()");
        ?>
	</td>
</tr>
</table>
</form>
<script type="text/javascript">
document.forms[0].name.focus();
function save_scheduler()
{
	document.forms[0].post_action.value = 'save_scheduler';
	document.forms[0].submit();
}
</script>

