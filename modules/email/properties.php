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
require($GO_CONFIG->class_path."imap.class.inc");
$mail = new imap;
if ($mail->open($email_host, $email_type,$email_port,$email_username,$email_password,$mailbox))
{
	$content = $mail->get_message($uid);
}
$page_title=$fbProperties;
require($GO_THEME->theme_path."simple_header.inc");
?>
<table cellpadding="10">
<tr>
	<td>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td height="50">
		<h1><?php echo $fbProperties; ?></h1>
		</td>
	</tr>
	<tr>
		<td>
		<?php
			echo text_to_html($content["header"]);
		?>
		</td>

	</table>
	</td>
</tr>
</table>

<?php
require($GO_THEME->theme_path."simple_footer.inc");
?>
