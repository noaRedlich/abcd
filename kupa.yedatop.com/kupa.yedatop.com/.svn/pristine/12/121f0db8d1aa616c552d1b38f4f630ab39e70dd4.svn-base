<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

require("../../Group-Office.php");
$GO_SECURITY->authenticate();
$GO_MODULES->authenticate('email');
require($GO_LANGUAGE->get_language_file('email'));
if ($REQUEST_METHOD == "POST")
{
	require($GO_CONFIG->class_path."users.class.inc");
	$users = new users;
	$profile = $users->get_user($GO_SECURITY->user_id);

	$body = $ml_displayed.$profile["name"]." <".$profile["email"].">\r\n\r\n";
	$body .= $ml_subject.": ".$subject."\r\n".$strDate.": ".$date;

	if (!sendmail($email, $profile["email"], $profile["name"], $ml_notify, $body))
	{
		$feedback = '<p class="Error">'.$ml_send_error.'</p>';
	}else
	{
		echo "<script type=\"text/javascript\">\nwindow.close();\n</script>";
		exit;
	}
}else
{
	$pos = strrpos($notification,'<');
	if ($pos)
	{
		$pos++;
		$endpos = strlen($notification)-$pos-1;
		$email = substr($notification, $pos, $endpos);
	}
}

$page_title=$ml_notify;
require($GO_THEME->theme_path."simple_header.inc");
?>
<form method="post" name="notify" action="<?php echo $PHP_SELF; ?>">
<input type="hidden" name="email" value="<?php echo $email; ?>" />
<input type="hidden" name="date" value="<?php echo $date; ?>" />
<input type="hidden" name="subject" value="<?php echo $subject; ?>" />

<table border="0" cellspacing="0" cellpadding="5" align="center">
<tr>
	<td><img src="<?php echo $GO_THEME->image_url; ?>questionmark.gif" border="0" /></td><td><h2><?php echo $ml_notify; ?></h2></td>
</tr>
</table>
<?php
if (isset($feedback))
{
	echo "<tr><td colspan=\"2\">".$feedback."</td></tr>\n";
}
?>
<table border="0" cellspacing="0" cellpadding="5" align="center">
<tr>
	<td colspan="2">
	<?php echo $ml_ask_notify; ?>
	</td>
</tr>
<tr>
	<td colspan="2" align="center">
	<?php
	$button = new button($cmdOk, "javascript:document.forms[0].submit()");
    echo '&nbsp;&nbsp;';
    $button = new button($cmdCancel, "javascript:window.close()");
	?>
	</td>
</tr>
</table>
</form>
<?php
require($GO_THEME->theme_path."simple_footer.inc");
?>
