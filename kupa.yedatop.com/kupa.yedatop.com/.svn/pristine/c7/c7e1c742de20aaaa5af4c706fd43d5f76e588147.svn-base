<?php
if (isset($account_id) && $REQUEST_METHOD != "POST")
{
	$account = $email->get_account($account_id);
	if ($account['user_id'] != $GO_SECURITY->user_id)
	{
		require($GO_CONFIG->root_path."error_docs/403.inc");
		require($GO_THEME->theme_path."footer.inc");
		exit();
	}
	$page_title=$ml_edit_account;
	$name = $account["name"];
	$mail_address = $account["email"];
	$signature = $account["signature"];
	$host = $account["host"];
	$type = $account["type"];
	$port = $account["port"];
	$user = $account["username"];
	$pass = $account["password"];
	$mbroot = $account["mbroot"];

	echo '<input type="hidden" value="'.$account_id.'" name="account_id" />';

}else
{
	$page_title=$ml_new_account;
	$name = isset($name) ? $name : $ses_name;
	$mail_address = isset($mail_address) ? $mail_address : $ses_email;
	$signature = isset($signature) ? $signature : '';
	$ma = explode("@",$mail_address);
	$host = isset($host) ? $host : "mail.".$ma[1];
	$type = isset($type) ? $type : 'pop3';
	$port = isset($port) ? $port : '110';
	$user = isset($user) ? $user : '';
	$pass = isset($pass) ? $pass : '';
	$mbroot = isset($mbroot) ? $mbroot : '';
}
?>

<script type="text/javascript">

function change_port()
{
	if (document.forms[0].type.value == "imap")
	{
		document.forms[0].port.value = "143";
	}else
	{
		document.forms[0].port.value = "110";
	}
}

</script>
<input type="hidden" value="account" name="task" />
<input type="hidden" value="" name="signature" />

<table border="0" cellpadding="10" cellspacing="0">
<tr>
	<td>
	<table border="0" cellpadding="0" cellspacing="5">
        <?php if (isset($feedback)) echo '<tr><td class="Error" colspan="2">'.$feedback.'</td></tr>'; ?>
        <tr>
        		<td><?php echo $strName; ?>:</td>
                <td><input name="name" type="text" class="textbox" maxlength="100" size="40" value="<?php echo $name; ?>" /></td>
        </tr>
        <tr>
                <td><?php echo $strEmail; ?>:</td>
                <td><input name="mail_address" type="text" class="textbox" maxlength="100" size="40" value="<?php echo $mail_address; ?>" /></td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr>
    		<td><?php echo $ml_type; ?>/<?php echo $ml_port; ?>:</td>
                <td>
                <?php
                $dropbox1 = new dropbox();
                $dropbox1->add_value('pop3','POP3');
                $dropbox1->add_value('imap','IMAP');
                $dropbox1->print_dropbox('type',$type,'onchange="javascript:change_port()"');
                ?>
                &nbsp;/&nbsp;<input name="port" type="text" class="textbox" maxlength="6" size="4" value="<?php echo $port; ?>" />
                 </td>
        </tr>
        <tr>
    		<td><?php echo $ml_servertype; ?>:</td>
                <td>
                <?php
                $dropbox2 = new dropbox();
                $dropbox2->add_value('',$strDefault);
		$dropbox2->add_value(' ','UW IMAP');
                $dropbox2->add_value('INBOX.','Cyrus');
                $dropbox2->print_dropbox('mbroot',$mbroot,'');
                ?>
		 </td>
        </tr>
        <tr>
        	<td><?php echo $ml_host; ?>:</td>
			<td><input name="host" type="text" class="textbox" maxlength="100" size="40" value="<?php echo $host; ?>" /></td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr>
        	<td><?php echo $strUsername; ?>:</td>
			<td>

			<input name="user" type="text" class="textbox" maxlength="100" size="40" value="<?php echo $user; ?>" />
			</td>
        </tr>
        <tr>
        	<td><?php echo $strPassword; ?>:</td>
			<td>
			<input name="pass" type="password" class="textbox" maxlength="100" size="40" value="<?php echo $pass; ?>" />
			</td>
        </tr>

	</table>
	<br />
	<?php $button = new button($cmdSave, 'javascript:document.forms[0].submit();'); ?>
	</td>
</tr>
</table>
