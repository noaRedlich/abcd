<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

$fs_sort_field = isset($fs_sort_field) ? $fs_sort_field : 'basename';
$fs_sort_direction = isset($fs_sort_direction) ? $fs_sort_direction : 'ASC';

if ($fs_sort_direction == "DESC")
{
	$sort_arrow = '&nbsp;<img src="'.$GO_THEME->image_url.'buttons/arrow_down.gif" border="0" />';
	$new_sort_direction = "ASC";
}else
{
	$sort_arrow = '&nbsp;<img src="'.$GO_THEME->image_url.'buttons/arrow_up.gif" border="0" />';
	$new_sort_direction = "DESC";
}


$folders = $fs->get_folders_sorted($path, $fs_sort_field, $fs_sort_direction);
$files = $fs->get_files_sorted($path, $fs_sort_field, $fs_sort_direction);
$item_count = count($files) + count($folders);
echo '<h1>'.$fbPath.': '.$current_path.'</h1>';
if ($userquota!=""&& $usedspace > $userquota){
	echo "<span style='color:red'>";
}
echo $strTotalUsed.' '.format_size($usedspace) ;
if ($userquota!=""){
	echo " 	$strFrom ".format_size ($userquota)."";
}
if ($userquota!=""&& $usedspace > $userquota){
echo "</span>";
}
echo '<input type="hidden" name="task" />';

echo '<table border="0"><tr>';
$parent_path = dirname($path);
if ($parent_path && $path != $home_path)
{
	echo '<td align="center" width="60" nowrap>';
	echo '<a class="small" href="'.$PHP_SELF.'?path='.urlencode($parent_path).'"><img src="'.$GO_THEME->image_url.'buttons/uplvl_big.gif" border="0" height="32" width="32" /><br />'.$fbUpLevel.'</a></td>';
}
echo '<td align="center" width="60" nowrap>';
echo '<a class="small" href="'.$PHP_SELF.'?path='.$urlencoded_path.'"><img src="'.$GO_THEME->image_url.'buttons/refresh.gif" border="0" height="32" width="32" /><br />'.$fbRefresh.'</a></td>';
echo '<td align="center" width="60" nowrap>';
echo '<a class="small" href="javascript:properties()"><img src="'.$GO_THEME->image_url.'buttons/properties.gif" border="0" height="32" width="32" /><br />'.$fbProperties.'</a></td>';
echo '<td align="center" width="60" nowrap>';
echo '<a class="small" href="'.$PHP_SELF.'?task=new_folder&path='.$urlencoded_path.'"><img src="'.$GO_THEME->image_url.'buttons/new_folder.gif" border="0" height="32" width="32" /><br />'.$fbNewFolder.'</a></td>';
echo '<td align="center" width="60" nowrap>';
echo '<a class="small" href="'.$PHP_SELF.'?task=upload&path='.$urlencoded_path.'"><img src="'.$GO_THEME->image_url.'buttons/upload.gif" border="0" height="32" width="32" /><br />'.$fbUpload.'</a></td>';
echo '<td align="center" width="60" nowrap>';
echo '<a class="small" href="javascript:delete_items()"><img src="'.$GO_THEME->image_url.'buttons/delete_big.gif" border="0" height="32" width="32" /><br />'.$fbDelete.'</a></td>';
echo '<td align="center" width="60" nowrap>';
echo '<a class="small" href="javascript:cut_items()"><img src="'.$GO_THEME->image_url.'buttons/cut.gif" border="0" height="32" width="32" /><br />'.$fbCut.'</a></td>';
echo '<td align="center" width="60" nowrap>';
echo '<a class="small" href="javascript:copy_items()"><img src="'.$GO_THEME->image_url.'buttons/copy.gif" border="0" height="32" width="32" /><br />'.$fbCopy.'</a></td>';


if((count($cut_files) + count($cut_folders) + count($copy_files) + count($copy_folders)) > 0)
{
	echo '<td align="center" width="60" nowrap>';
	echo '<a class="small" href="javascript:paste_items()"><img src="'.$GO_THEME->image_url.'buttons/paste.gif" border="0" height="32" width="32" /><br />'.$fbPaste.'</a></td>';
}

if($GO_CONFIG->window_mode == 'normal')
{
	echo '<td align="center" width="60" nowrap>';
	echo '<a class="small" href="javascript:mail_files()"><img src="'.$GO_THEME->image_url.'buttons/2.gif" border="0" height="32" width="32" /><br />e-mail</a></td>';

	echo '<td align="center" width="60" nowrap>';
	echo '<a class="small" href="'.$PHP_SELF.'?task=shares&path='.$urlencoded_path.'"><img src="'.$GO_THEME->image_url.'buttons/sharing.gif" border="0" height="32" width="32" /><br />'.$fbSharing.'</a></td>';

	echo '<td align="center" width="60" nowrap>';
	echo '<a class="small" href="'.$PHP_SELF.'?task=search&path='.$urlencoded_path.'"><img src="'.$GO_THEME->image_url.'buttons/3.gif" border="0" height="32" width="32" /><br />'.$fs_search.'</a></td>';
}

echo '</tr></table>';


if ($GO_CONFIG->window_mode == 'popup')
{
	echo '<table border="0">';
	if ($mode == 'save')
	{
		echo '<tr><td><h3>'.$fbFilename.':</h3></td><td><input type="text" class="textbox" name="filename" value="'.$filename.'" size="40" /></td></tr>';
	}
	echo '<tr><td><h3>'.$strLookIn.':</h3></td><td>';
	require_once($GO_CONFIG->class_path.'users.class.inc');
	$users = new users();

	$dropbox = new dropbox();
	$dropbox->add_value($home_path, 'home');

	$users->get_users($GO_SECURITY->user_id);
	while ($users->next_record())
	{
		$shares = array();
		$share_count = $fs->get_shares($users->f('id'));
		while ($fs->next_record())
		{
			if ($GO_SECURITY->has_permission($GO_SECURITY->user_id, $fs->f('acl_read')) || $GO_SECURITY->has_permission($GO_SECURITY->user_id, $fs->f('acl_write')))
			{
				if (strpos($path, $fs->f('path')) !== false)
				{
					$location = $fs->f('path');
				}
				$shares[] = $fs->f('path');
			}
		}
		$share_count = count($shares) ;
		if ($share_count > 0)
		{
			$dropbox->add_optgroup($users->f('username'));
			for ($i=0;$i<$share_count;$i++)
			{
				$dropbox->add_value($shares[$i], basename($shares[$i]));
			}
		}
	}
	$location = isset($location) ? $location : $home_path;
	$dropbox->print_dropbox('location',$location,'onchange="javascript:change_location(this)"');
	echo '</td></tr></table>';
}


echo '<table border="0" width="100%">';
echo '<tr>';

if ($GO_CONFIG->window_mode == 'normal')
{
	echo '<td dir=ltr valign="top">';
	require('treeview.inc');
	echo '</td>';
}

echo '<td valign="top">';

echo '<table border="0" cellspacing="0" cellpadding="0"><tr>';
echo '<td class="TableHead2" width="16"><input type="checkbox" onclick="javascript:invert_selection()" name="dummy" /></td>';
echo '<td class="TableHead2" width="16">&nbsp;</td>';

echo '<td class="TableHead2" width="100" nowrap><a class="TableHead2" href="'.$PHP_SELF.'?path='.$urlencoded_path.'&new_sort_field=basename&new_sort_direction='.$new_sort_direction.'">'.$strName.'</a>';
if ($fs_sort_field == 'basename')
{
	echo $sort_arrow;
}
echo '</td>';

echo '<td class="TableHead2" width="100" nowrap><a class="TableHead2" href="'.$PHP_SELF.'?path='.$urlencoded_path.'&new_sort_field=filemimefriendly&new_sort_direction='.$new_sort_direction.'">'.$strType.'</a>';
if ($fs_sort_field == 'filemimefriendly')
{
	echo $sort_arrow;
}
echo '</td>';

echo '<td class="TableHead2" width="100" nowrap align="right"><a class="TableHead2" href="'.$PHP_SELF.'?path='.$urlencoded_path.'&new_sort_field=filesize&new_sort_direction='.$new_sort_direction.'">'.$strSize.'</a>';
if ($fs_sort_field == 'filesize')
{
	echo $sort_arrow;
}
echo '</td>';

echo '<td class="TableHead2" width="100" nowrap align="right"><a class="TableHead2" href="'.$PHP_SELF.'?path='.$urlencoded_path.'&new_sort_field=filemtime&new_sort_direction='.$new_sort_direction.'">'.$strModified.'</a>';
if ($fs_sort_field == 'filemtime')
{
	echo $sort_arrow;
}
echo '</td>';
echo '</tr>';

while ($folder = array_shift($folders))
{
	$short_name = cut_string($folder['name'], 30);
	echo '<tr id="'.$folder['path'].'" class="Table1">';
	echo '<td><input onclick="javascript:item_click(this)" type="checkbox" name="folders[]" value="'.$folder['path'].'" /></td>';
	echo '<td><img width="16" height="16" border="0" src="'.$GO_THEME->image_url.'folder.gif" /></td>';
	if (isset($cut_folders) && in_array(addslashes($folder['path']), $cut_folders))
	{
		echo '<td nowrap><a href="'.$PHP_SELF.'?path='.urlencode($folder['path']).'"><font color="#7d7d7d">'.$folder['name'].'</font></a>&nbsp;&nbsp;</td>';
	}else
	{
		echo '<td nowrap><a href="'.$PHP_SELF.'?path='.urlencode($folder['path']).'" title="'.$folder['name'].'">'.$short_name.'</a>&nbsp;&nbsp;</td>';
	}
	echo '<td nowrap>'.$fbFolder.'&nbsp;&nbsp;</td>';
	echo '<td align="right">-&nbsp;&nbsp;</td>';
	echo '<td nowrap>'.date($ses_date_format, $folder['mtime']).'&nbsp;&nbsp;</td>';
	echo '</tr>';
	echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
}

$total_size = 0;

while ($file = array_shift($files))
{
	$extension = get_file_extension($file['name']);
	if (!isset($GO_FILTER_TYPES) || in_array($extension, $GO_FILTER_TYPES))
	{
		$total_size += $file['size'];
		$short_name = cut_string($file['name'], 30);

		echo '<tr id="'.$file['path'].'" class="Table1">';
		echo '<td><input onclick="javascript:item_click(this)" type="checkbox" name="files[]" value="'.$file['path'].'" /></td>';
		echo '<td><img width="16" height="16" border="0" src="'.$GO_CONFIG->host.'filetypes/icon.php?extension='.$extension.'" /></td>';
		if (isset($cut_files) && in_array(addslashes($file['path']), $cut_files))
		{
			echo '<td nowrap><a target="'.$target_frame.'" href="'.$GO_HANDLER.'?path='.urlencode($file['path']).'" title="'.$file['name'].'"><font color="#7d7d7d">'.$short_name.'</font></a>&nbsp;&nbsp;</td>';
		}else
		{
			echo '<td nowrap><a target="'.$target_frame.'" href="'.$GO_HANDLER.'?path='.urlencode($file['path']).'" title="'.$file['name'].'">'.$short_name.'</a>&nbsp;&nbsp;</td>';
		}
		echo '<td nowrap>'.$file['type'].'&nbsp;&nbsp;</td>';
		echo '<td nowrap align="right">'.format_size($file['size']).'&nbsp;&nbsp;</td>';
		echo '<td nowrap>'.date($ses_date_format, $file['mtime']).'&nbsp;&nbsp;</td>';
		echo '</tr>';
		echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
	}
}

echo '<tr><td colspan="99" class="small" height="18">&nbsp;'.$item_count.' '.$fbItems.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$fbFolderSize.': '.format_size($total_size).'</td></tr>';
echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
echo '</table>';

echo '</td></tr>';

if ($GO_CONFIG->window_mode=='popup')
{
	echo '<tr><td colspan="2" align="center"><br />';
	$button = new button($cmdOk,'javascript:submit_to_handler()');
	echo '&nbsp;&nbsp;';
	$button = new button($cmdCancel,'javascript:window.close()');
	echo '</td></tr>';
}
echo '</table>';
?>

<script type="text/javascript" language="javascript">
function submit_to_handler()
{
	<?php
	if ($GO_MULTI_SELECT == false)
	{
		?>
		var count = 0;
		var path = new String;

		for (var i=0;i<document.forms[0].elements.length;i++)
		{
			if(document.forms[0].elements[i].type == 'checkbox' && document.forms[0].elements[i].name != 'dummy')
			{
				if (document.forms[0].elements[i].checked == true)
				{
					document.forms[0].path.value = document.forms[0].elements[i].value;
					count++;
				}
			}
		}
		if (count > 1)
		{
			alert('<?php echo $fs_no_multi_select; ?>');
			return false;
		}
		<?php
	}
	?>
	document.forms[0].task.value = 'GO_HANDLER';
	document.forms[0].action = "<?php echo $GO_HANDLER; ?>";
	document.forms[0].submit();
}

function item_click(check_box)
{
	var item = get_object(check_box.value);
	if (check_box.checked)
	{
		item.className = 'Table2';
	}else
	{
		item.className = 'Table1';
	}
}

function invert_selection()
{
	for (var i=0;i<document.forms[0].elements.length;i++)
	{
		if(document.forms[0].elements[i].type == 'checkbox' && document.forms[0].elements[i].name != 'dummy')
		{
			document.forms[0].elements[i].checked = !(document.forms[0].elements[i].checked);
			item_click(document.forms[0].elements[i]);
		}
	}
}

function cut_items()
{
	var count = 0;
	for (var i=0;i<document.forms[0].elements.length;i++)
	{
		if(document.forms[0].elements[i].type == 'checkbox' && document.forms[0].elements[i].name != 'dummy')
		{
			if (document.forms[0].elements[i].checked == true)
			{
				count++;
			}
		}
	}

	if (count > 0)
	{
		document.forms[0].task.value = 'cut';
		document.forms[0].submit();
	}else
	{
		alert("<?php echo $fbNoSelect; ?>");
	}
}

function copy_items()
{
	var count = 0;
	for (var i=0;i<document.forms[0].elements.length;i++)
	{
		if(document.forms[0].elements[i].type == 'checkbox' && document.forms[0].elements[i].name != 'dummy')
		{
			if (document.forms[0].elements[i].checked == true)
			{
				count++;
			}
		}
	}

	if (count > 0)
	{
		document.forms[0].task.value = 'copy';
		document.forms[0].submit();
	}else
	{
		alert("<?php echo $fbNoSelect; ?>");
	}
}

function paste_items()
{
	document.forms[0].task.value = 'paste';
	document.forms[0].submit();
}


function mail_files()
{
	var count = 0;
	for (var i=0;i<document.forms[0].elements.length;i++)
	{
		if(document.forms[0].elements[i].name == 'files[]')
		{
			if (document.forms[0].elements[i].checked == true)
			{
				count++;
			}
		}
	}

	if(count > 0)
	{
		document.forms[0].task.value = 'mail_files';
		document.forms[0].submit();
	}else
	{
		alert("<?php echo $fbNoSelect; ?>");
	}
}

function delete_items()
{
	var count = 0;
	var filename = new String;
	var path = new String;

	for (var i=0;i<document.forms[0].elements.length;i++)
	{
		if(document.forms[0].elements[i].type == 'checkbox' && document.forms[0].elements[i].name != 'dummy')
		{
			if (document.forms[0].elements[i].checked == true)
			{
				count++;
				path = document.forms[0].elements[i].value;
				filename = path.substring(path.lastIndexOf('/')+1, path.length);
			}
		}
	}
	switch (count)
	{
		case 0:
			alert("<?php echo $fbNoSelect; ?>");
		break;

		case 1:
			if (confirm("<?php echo $strDeletePrefix; ?>'"+filename+"'<?php echo $strDeleteSuffix; ?>"))
			{
				document.forms[0].task.value = 'delete';
				document.forms[0].submit();
			}
		break;

		default:
			if (confirm("<?php echo $strDeletePrefix.$strThis; ?> "+count+" <?php echo $strItems.$strDeleteSuffix; ?>"))
			{
				document.forms[0].task.value = 'delete';
				document.forms[0].submit();
			}
		break;
	}
}

function change_location(dropbox)
{
	document.forms[0].share_path.value = dropbox.value;
	document.forms[0].path.value = dropbox.value;
	document.forms[0].submit();
}

function properties()
{
	var count = 0;
	var path = new String;

	for (var i=0;i<document.forms[0].elements.length;i++)
	{
		if(document.forms[0].elements[i].type == 'checkbox' && document.forms[0].elements[i].name != 'dummy')
		{
			if (document.forms[0].elements[i].checked == true)
			{
				count++;
				path = document.forms[0].elements[i].value;
			}
		}
	}
	switch (count)
	{
		case 0:
			document.forms[0].task.value = "properties";
			document.forms[0].submit();
		break;

		case 1:
			document.forms[0].task.value = "properties";
			document.forms[0].path.value = path;
			document.forms[0].submit();

		break;

		default:
			alert("<?php echo $fs_no_multi_select; ?>");
		break;
	}
}
</script>
