<?php
/*
This scripts replaces strings in all Group-Office files.
Enter old keywords in $string[n]['old'] and the new in $string[n]['new'].
BE CAREFULL WITH THIS SCRIPT!
*/

require("../../Group-Office.php");
$GO_SECURITY->authenticate();

$string[0]['old'] = '$module_scheduler';
$string[0]['new'] = '$lang_modules[\'scheduler\']';
$string[1]['old'] = '$module_filesystem';
$string[1]['new'] = '$lang_modules[\'filesystem\']';
$string[2]['old'] = '$module_email';
$string[2]['new'] = '$lang_modules[\'email\']';
$string[3]['old'] = '$module_projects';
$string[3]['new'] = '$lang_modules[\'projects\']';

$count = count($string);
require($GO_CONFIG->class_path."filesystem.class.inc");
$filesystem = new filesystem;

function replace_files($path)
{
	global $count, $string;
	$dir=opendir($path);
	while ($file=readdir($dir))
	{
		if (is_dir($path.$file))
		{
			if ($file != "." && $file != "..")
			{
				replace_files($path.$file.'/');
			}
		}else
		{
			if ($file != 'replace.php')
			{
				$fp = fopen($path.$file, 'r');
				$data = fread($fp, filesize($path.$file));
				fclose($fp);

				for ($i=0;$i<$count;$i++)
				{
					$data = str_replace($string[$i]['old'],$string[$i]['new'], $data);
				}

				$fp = fopen($path.$file, 'w+');
				fwrite($fp, $data);
				fclose($fp);
				echo $path.$file."<br />";
			}

		}
	}
	closedir($dir);
}
replace_files($GO_CONFIG->root_path);
?>