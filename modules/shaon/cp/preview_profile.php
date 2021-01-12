<?
	header("Pragma: no-cache");

	require "lib/system.lib";
	require "lib/plug-ins.lib";
	require "lib/mod_xml.lib";
	require "lib/mod_xml_ct.lib";

	require "services/BD3LoadConfiguration.service";
	require "services/BD3LoadDBDevice.service";

	require "services/BD3Auth.service";

	$db = c();

    $profile = f(q("SELECT id, member_id FROM dt_profile WHERE id='$id'"));

	if($profile == "")
	{
		echo "<h3>Error: Nothing to preview</h3>";
		d($db);
		exit;
	}

    $fPhoto = f(q("SELECT filename_1, filename_2, filename_3 FROM dt_photos WHERE member_id='$profile[member_id]'"));

    if($fPhoto[ filename_1 ] != "")
    {
        $fPhoto[ filename_1 ] = "../../photos/".$fPhoto[ filename_1 ];
    }

    if($fPhoto[ filename_2 ] != "")
    {
        $fPhoto[ filename_2 ] = "../../photos/".$fPhoto[ filename_2 ];
    }
	
	if($fPhoto[ filename_3 ] != "")
    {
        $fPhoto[ filename_3 ] = "../../photos/".$fPhoto[ filename_3 ];
    }
?>
<html>
<head>
<title>Remote Web Adminstration System v. 3.0 - preview profile</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="4" topmargin="4" marginwidth="4" marginheight="4">

<span class=big>Preview profile</span><hr noshade size=1><br><br>
<?
	include "../code/preview_profile.php";
    include "../theme/preview_profile_wp.php";
?>
<br>
<font class=small>
<hr noshade size=1>
<b>E-mail address: </b><? echo $profile[ email ] ?>
<br>
<div align=right>
<a href=#top>Go to top</a>
</div>
<br>
</font>
</body>
</html>
<?
	d($db);
?>
