<?php

	global $action, $id, $lang, $conn, $config;
	$simple = true;
	include("include/common.php");
	if(!loginCheck('registered_user'))exit;
	//include("$config[template_path]/admin_top.html");

	$path = "administrator/realty templates";
	
	//create filesystem and filetypes object
	require_once($GO_CONFIG->class_path.'filesystem.class.inc');
	require_once($GO_CONFIG->class_path.'filetypes.class.inc');
	$fs = new filesystem($home_path);
	$filetypes = new filetypes;

	$home_path = $GO_CONFIG->file_storage_path.$ses_username;
	//$GO_FILESYSTEM_PATH = isset($GO_FILESYSTEM_PATH) ? smartstrip($GO_FILESYSTEM_PATH) : $home_path;
	$path = $home_path."/realty templates";
	
	
	//print report
	if ($template){

	    $fname = $GO_CONFIG->protocol."".$GO_CONFIG->hostname."/modules/realty/freport.php?template=$template&contract_id=$contract_id";
		//echo $fname;
		if($save){
			echo "<META HTTP-EQUIV=\"refresh\" content=\"1; URL=$fname\">";
		}
		if($preview){
			echo "<script>window.open('$fname&preview=1','preview','top=120,left=120,scrollbars=yes,width=700,height=500');</script>";
		}
		if ($tpl){
			echo "<script>window.open('$fname&preview=1&tpl=1','preview','top=120,left=120,scrollbars=yes,width=700,height=500');</script>";
		}
		if ($codes){
			echo "<script>window.open('$fname&preview=1&codes=1','preview','top=120,left=120,scrollbars=yes,width=700,height=500');</script>";
		}
	}
	//end print report
	
	$fs_sort_field = 'basename';
	$fs_sort_direction = 'ASC';
	//echo $home_path."=-";
	$files = $fs->get_files_sorted($path, $fs_sort_field, $fs_sort_direction);
	$item_count = count($files) + count($folders);
	
?>
<table width=100% bgcolor=buttonface cellpadding=5 cellspacing=0 height=100%>
	<tr style='height:1%'><td align=center class=TableHead2><?=$lang[select_template]?></td></tr>
	<tr><td valign=top>
<div style='background-color:white;border:solid 1 gray;overflow-Y:scroll;height:100%;width:100%'>
	<table width=100% cellpadding=5 cellspacing=0>
	<form method=post>
	<input type=hidden name=contract_id value="<?=$contract_id?>">
	<tr><td align=center style='border-bottom:solid 1'><?=$lang[your_templates]?></td></tr>
	<?while ($file = array_shift($files)){
		$filename = substr($file['name'],strrpos($file['name'],$GO_CONFIG->slash));
	?>
	<tr><td><b><input value="u*<?=$filename?>" type=radio <?if ($template=="u*$filename"){echo "checked";$checked=true;}?> name=template><?=substr($filename,0,strpos($filename,"."));?></td></tr>
	<?}?>
	<tr><td align=center style='border-bottom:solid 1'><br><?=$lang[common_templates]?></td></tr>
	<?
		$home_path = ($GO_CONFIG->file_storage_path).$GO_CONFIG->useradmin;
		$path = $home_path."/realty templates";
		$files = $fs->get_files_sorted($path, $fs_sort_field, $fs_sort_direction);
		while ($file = array_shift($files)){
		$filename = substr($file['name'],strrpos($file['name'],$GO_CONFIG->slash));
	?>
	<tr><td><b><input value="a*<?=$filename?>" type=radio <?if ($template=="a*$filename"){echo "checked";$checked=true;}?> name=template><?=substr($filename,0,strpos($filename,"."));?></td></tr>
	<?}?>
	</table>
	</td>
	</td></tr>
	<tr style='height:1%'><td>
	<center>
	<?=$lang[add_template_info]?>
	<hr noshade size=1>
	<input type=submit  name=save value=<?=$lang[save]?>>
	<input type=submit  name=preview value=<?=$lang[preview]?>>
	<input type=submit  name=tpl value="<?=$lang[view_template]?>">
	<input type=submit  name=codes value="<?=$lang[view_codes]?>">
	</center>
	
	</td></tr></table>
<?
	
		
	include("$config[template_path]/admin_bottom.html");
	$conn->Close(); // close the db connection
?>

