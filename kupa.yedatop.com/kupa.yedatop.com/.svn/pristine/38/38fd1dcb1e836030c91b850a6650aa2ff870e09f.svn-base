<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

$time = time();
$current_year = date("Y", $time);
$current_month = date("m", $time);
$current_day = date("j", $time);

$start_year = isset($start_year) ? $start_year : $current_year;
$start_month = isset($start_month) ? $start_month : $current_month;
$start_day = isset($start_day) ? $start_day : $current_day;

$end_year = isset($end_year) ? $end_year : $current_year;
$end_month = isset($end_month) ? $end_month : $current_month;
$end_day = isset($end_day) ? $end_day : $current_day;

for ($i=1;$i<32;$i++)
{
	$num_days[] = $i;
}

for ($i=1;$i<13;$i++)
{
	$num_months[] = $i;
}

$years[0] = $current_year;
$years[1] = $current_year+1;
$years[2] = $current_year+2;
$years[3] = $current_year+3;
$years[4] = $current_year+4;
$years[5] = $current_year+5;
$years[5] = $current_year+6;
?>
<form method="post" action ="<?php echo $PHP_SELF; ?>" name="add_project">
<input type="hidden" name="post_action" value="add_project">

<table border="0" cellpadding="0" cellspacing="3">

<tr>
	<td><?php echo $strName; ?>:</td>
	<td><input type="text" name="name" value="<?php if (isset($name)) echo $name; ?>" maxlength="50" size="40" class="textbox" /></td>
</tr>
<tr>
	<td valign="top"><?php echo $strComments; ?>:</td>
	<td><textarea name="comments" cols="50" rows="4" class="textbox"><?php  if (isset($comments)) echo $comments; ?></textarea></td>
</tr>
<tr>
	<td colspan="2"><br />
	<?php
	$button = new button($cmdSave, "javascript:document.forms[0].submit()");
	echo '&nbsp;&nbsp;';
	$button = new button($cmdCancel, "javascript:document.location='".$PHP_SELF."?post_action=projects';");
	?>
	</td>
</tr>

</table>

</form>
