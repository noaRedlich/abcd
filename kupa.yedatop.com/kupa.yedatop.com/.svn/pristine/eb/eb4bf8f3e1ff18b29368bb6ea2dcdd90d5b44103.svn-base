<?php

	include ("edit_my_listings.php");
	exit;
	
	include("include/common.php");
	$s = loginCheck('User');
	if ($s){
	include("$config[template_path]/admin_top.html");
?>
<P>
This is the administrative area of the site!</p>
<P>
</P>

<?php
}
	include("$config[template_path]/admin_bottom.html");

	$conn->Close(); // close the db connection
?>
