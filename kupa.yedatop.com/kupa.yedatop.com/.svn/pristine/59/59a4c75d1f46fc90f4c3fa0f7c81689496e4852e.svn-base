<?

	// Connecting to mySQL database

 require("../catalog_admin/connect.php");

		// Reading all files from ./banner directory into $banners[] array

		$handle = opendir('../../catalog/banner'); 
		$count=0;
		if($handle){
		while (false !== ($file = readdir($handle)))    { 
		    if ($file != "." && $file != "..") 	
							{ 
		        $banners[$count]="$file";
			$count++;
    							} 
								}
		closedir($handle); 
		}
		$c_banners=count($banners);

		// Tests if there is 1 banner in the directory, if yes, show banner



		if ($c_banners == 1)
		{

			$rbannum = explode(".", $banners[0]); 
			$id_banner=$rbannum[0];
			$r_banner=mysql_query("select * from $db_users where selector='$id_banner'");
			$f_banner=mysql_fetch_array($r_banner);
			
			// Adds 1 to the mySQL number of banner shows for the definite ID

			$banner_show_banner=$f_banner["banner_show"];
			$banner_show_banner++;
			mysql_query("UPDATE $db_users SET banner_show=$banner_show_banner where selector='$id_banner'") or die ("mySQL error!");

			// Prints out a banner with a COMPLETE link (id, category, subcategory, type = banner)

			echo "<br><a href=../../catalog/index.php?REQ=view&id=",$id_banner,"&cat=$f_banner[category1]&subcat=$f_banner[subcategory1]&type=banner target=\"new\"><img src=../../catalog/banner/",$banners[0]," border=0 alt=\"$def_ads\"></a><br>$def_ads";

		}

		// Tests if there is more than 2 banners in the directory, if yes, shows a random banner

		if ($c_banners >= 2)

			{

			$rban = array_rand ($banners, 2);
			$rbannum = explode(".", $banners[$rban[0]]); 
			$id_banner=$rbannum[0];
			$r_banner=mysql_query("select * from $db_users where selector='$id_banner'");
			$f_banner=mysql_fetch_array($r_banner);
			
			// Adds 1 to the mySQL number of banner shows for the definite ID			

			$banner_show_banner=$f_banner["banner_show"];
			$banner_show_banner++;
			mysql_query("UPDATE $db_users SET banner_show=$banner_show_banner where selector='$id_banner'") or die ("mySQL error!");

			// Prints out a banner with a COMPLETE link (id, category, subcategory, type = banner)


			echo "<br><a href=index.php?REQ=view&id=",$id_banner,"&cat=$f_banner[category1]&subcat=$f_banner[subcategory1]&type=banner target=\"new\"><img src=./banner/",$banners[$rban[0]]," border=0 alt=\"$def_ads\"><br>$def_ads</a>";

			}

		// Now unset variables

		unset($id_banner);
		unset($f_banner);
		unset($c_banners);
		unset($banner_show_banner);
		@mysql_free_result($r_banner);

?>
