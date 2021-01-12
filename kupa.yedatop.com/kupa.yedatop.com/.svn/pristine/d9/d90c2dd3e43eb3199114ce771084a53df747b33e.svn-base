<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

$string = '';
if (isset($addresses))
{
	for ($i=0;$i<sizeof($addresses);$i++)
	{
		if ($i != 0)
		{
			$string .= ", ";
		}
		$string .= $addresses[$i];
	}
}

if ($string != "")
{
   	$string .= ", ";
}

if (isset($email))
{
	$string .= $email;
}else
{
	if (isset($contacts))
	{
		for($i=0;$i<sizeof($contacts);$i++)
		{
			if (isset($addresses))
			{
				if (!in_array($contacts[$i],$addresses))
				{
					if (isset($first))
						$string .= ", ";
					else
						$first = true;

					$string .= $contacts[$i];
				}
			}else
			{
				if (isset($first))
					$string .= ", ";
				else
					$first = true;

				$string .= $contacts[$i];

			}
		}
	}
}

session_unregister("GO_HANDLER");
?>
<html>
<body>
<script language="javascript" type="text/javascript">
        opener.document.forms[0].<?php echo $GO_FIELD; ?>.value = '<?php echo $string; ?>';
        window.close();
</script>
</body>
</html>
