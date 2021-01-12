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
$path = smartstrip($path);
//load file management class
require($GO_CONFIG->class_path."filesystem.class.inc");
require($GO_CONFIG->class_path.'email.class.inc');
require($GO_CONFIG->class_path.'filetypes.class.inc');
$email = new email();
$fs = new filesystem();
$filetypes = new filetypes();


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


if (isset($files))
{
	for ($i=0;$i<count($files); $i++)
	{
		$attachments_size += filesize(smartstrip($files[$i]));
	}
	if ($attachments_size < $GO_CONFIG->max_attachment_size)
	{
		while ($file = smartstrip(array_shift($files)))
		{
			$filename = basename($file);
			$extension = get_file_extension($filename);
			if (!$type = $filetypes->get_type($extension))
			{
				$type = $filetypes->add_type($extension);
			}

			$email->register_attachment($file, $filename, filesize($file), $type['mime']);
		}
	}else
	{
		$task = 'too_big';
	}
}else
{
	if (isset($path) && !is_dir($path))
	{	$filesize = filesize($path);
		$attachments_size += $filesize;
		if ($attachments_size < $GO_CONFIG->max_attachment_size)
		{
			$filename = basename($path);
			$email->register_attachment($path, $filename, $filesize);
			$task = 'attached';
		}else
		{
			$task = 'too_big';
		}
	}
}

if ($task == 'too_big')
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
}else
{
	?>

	<html>
	<body>
	<script type="text/javascript">
			if(opener.document.forms[0].content_type.value == 'text/HTML')
			{
				opener.document.forms[0].mail_body.value = opener.iView.document.body.innerHTML;
			}
			opener.document.forms[0].sendaction.value = 'attach_online';
			opener.document.forms[0].submit();
			window.close();
	</script>
	</body>
	</html>
<?php

}
?>
