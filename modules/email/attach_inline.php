<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

require("../../Group-Office.php");
$GO_SECURITY->authenticate();
$GO_MODULES->authenticate('filesystem');
require($GO_LANGUAGE->get_language_file('email'));
$path = smartstrip($path);
//load file management class
require($GO_CONFIG->class_path."filesystem.class.inc");
require($GO_CONFIG->class_path.'email.class.inc');
$email = new email();
$fs = new filesystem();;


if (!$fs->has_read_permission($GO_SECURITY->user_id, $path))
{
	header('Location: '.$GO_CONFIG->host.'error_docs/401.php');
	exit();
}

$attachments_size = 0;

if (isset($attach_array))
{
	for($i=1;$i<=sizeof($attach_array);$i++)
	{
		$attachments_size += $attach_array[$i]->file_size;
	}
}
$filesize = filesize($path);
$attachments_size += $filesize;

if ($attachments_size < $GO_CONFIG->max_attachment_size)
{
	$GO_URL = $GO_CONFIG->host.$GO_MODULES->path.'download.php?path='.urlencode($path);
	$filename = basename($path);
	$content_id = '<'.$filename.'@mail.groupoffice>';
	$email->register_attachment($path, $filename, $filesize,'',$content_id);

	//Content-ID's that need to be replaced with urls when message is send
	$replace_url[] = $GO_URL;
	$replace_id[] = $content_id;
	session_register('replace_url','replace_id');
?>

	<html>
	<body>
	<script type="text/javascript">
        		opener.iView.document.execCommand('insertimage', false, '<?php echo $GO_URL; ?>');
        		opener.document.forms[0].mail_body.value = opener.iView.document.body.innerHTML;
                opener.document.forms[0].sendaction.value = 'attach_online';
                opener.document.forms[0].submit();
                window.close();
	</script>
	</body>
	</html>
<?php
}else
{
?>
	<html>
	<body>
	<script type="text/javascript">
			alert("<?php echo $ml_file_too_big.format_size($GO_CONFIG->max_attachment_size)." (".number_format($GO_CONFIG->max_attachment_size, 0, $ses_decimal_seperator, $ses_thousands_seperator)." bytes)."; ?>");
			window.close();
	</script>
	</body>
	</html>
<?php
}
?>
