<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

$image['mlastnode'] = '<img src="'.$GO_THEME->image_url.'treeview/mlastnode.gif" border="0" height="22" width="16" />';
$image['emptylastnode'] = '<img src="'.$GO_THEME->image_url.'treeview/emptylastnode.gif" border="0" height="22" width="16" />';
$image['plastnode'] = '<img src="'.$GO_THEME->image_url.'treeview/plastnode.gif" border="0" height="22" width="16" />';
$image['mnode'] = '<img src="'.$GO_THEME->image_url.'treeview/mnode.gif" border="0" height="22" width="16" />';
$image['emptynode'] = '<img src="'.$GO_THEME->image_url.'treeview/emptynode.gif" border="0" height="22" width="16" />';
$image['pnode'] = '<img src="'.$GO_THEME->image_url.'treeview/pnode.gif" border="0" height="22" width="16" />';
$image['vertline'] = '<img src="'.$GO_THEME->image_url.'treeview/vertline.gif" border="0" height="22" width="16" />';
$image['blank'] = '<img src="'.$GO_THEME->image_url.'treeview/blank.gif" border="0" height="22" width="16" />';
$image['group'] = '<img src="'.$GO_THEME->image_url.'treeview/workgroup.gif" border="0" height="22" width="24" />';
$image['user'] = '<img src="'.$GO_THEME->image_url.'treeview/workstation.gif" border="0" height="22" width="20" />';

$image['opened_folder'] = '<img src="'.$GO_THEME->image_url.'treeview/folderopen.gif" border="0" height="22" width="24" />';
$image['closed_folder'] = '<img src="'.$GO_THEME->image_url.'treeview/folderclosed.gif" border="0" height="22" width="24" />';

function print_filesystem($folders, $images='', $arguments='')
{
	global $image;
	global $path;
	global $PHP_SELF;
	global $expanded;
	global $GO_SECURITY;

	$fs = new filesystem();

	$count = count($folders);
	for ($i=0;$i<$count;$i++)
	{
		if ($fs->has_read_permission($GO_SECURITY->user_id, $folders[$i]['path']))
		{
			$subfolders = $fs->get_folders($folders[$i]['path']);
			$subfolders_count = count($subfolders);
			$open = in_array($folders[$i]['path'], $expanded);
		}else
		{
			$subfolders = array();
			$subfolders_count = 0;
			$open = false;
		}

		if ($subfolders_count > 0)
		{
			if ($i < ($count-1))
			{
				$new_image = $image['vertline'];
				$node = $open ? $image['mnode'] : $image['pnode'];

			}else
			{
				$new_image = $image['blank'];
				$node = $open ? $image['mlastnode'] : $image['plastnode'];
			}
		}else
		{
			if ($i < ($count-1))
			{
				$new_image = $image['vertline'];
				$node = $image['emptynode'];
			}else
			{
				$new_image = $image['blank'];
				$node = $image['emptylastnode'];
			}
		}

		if ($folders[$i]['path'] == $path)
		{
			$folder_image = $image['opened_folder'];
		}else
		{
			$folder_image = $image['closed_folder'];
		}

		$short_name = cut_string($folders[$i]['name'], 30);
		echo '<table border="0" cellpadding="0" cellspacing="0">';
		echo '<tr><td><a href="'.$PHP_SELF.'?path='.urlencode($path).'&expand_path='.urlencode($folders[$i]['path']).$arguments.'">'.$images.$node.$folder_image.'</a></td>';
		echo '<td nowrap><a href="'.$PHP_SELF.'?path='.urlencode($folders[$i]['path']).$arguments.'" title="'.$folders[$i]['name'].'">'.$short_name.'</a></td></tr></table>';

		if ($open)
		{
			print_filesystem($subfolders, $images.$new_image, $arguments);
		}
	}
	unset($fs);
}


if (!isset($expanded))
{
	$expanded[]=-1;
}

//expand or collaps the expand id which is passed when a user clicks a node
if (isset($expand_path))
{
	$expand_path = smartstrip($expand_path);
	$key = array_search($expand_path, $expanded);
	if (!$key)
	{
		$expanded[] = $expand_path;
	}else
	{
		unset($expanded[$key]);
	}
}else
{
	//always expand folder clicks
	if (isset($path))
	{
		$key = array_search($path, $expanded);
		if (!$key)
		{
			$expanded[] = $path;
		}

	}
}

//stay in search mode if search is clicked
if ($task == 'search')
{
	$arguments = '&task=search';
}else
{
	$arguments = '';
}

//store array of opened folders in session
session_register("expanded");

echo '<table border="0" cellpadding="0" cellspacing="0">';
echo '<tr><td><a href="'.$PHP_SELF.'?path='.urlencode($home_path).$arguments.'">'.$image['user'].'</a></td>';
echo '<td><a href="'.$PHP_SELF.'?path='.urlencode($home_path).$arguments.'">home</a></td></tr></table>';

$folder = array();
$folder['path'] = $home_path;
$folder['name'] = basename($folder['path']);
print_filesystem(array($folder),'',$arguments);

echo '<table border="0" cellpadding="0" cellspacing="0">';
echo '<tr><td>'.$image['group'].'</td>';
echo '<td>'.$fbShares.'</td></tr></table>';

require_once($GO_CONFIG->class_path.'users.class.inc');
$users = new users();

$users->get_user($GO_SECURITY->user_id);
$users_array = array();

while ($users->next_record())
{
	if ($users->f('id') != $GO_SECURITY->user_id && 
	$GO_SECURITY->has_permission($GO_SECURITY->user_id, $users->f('acl_id')) &&
	$fs->get_shares($users->f('id'))
	)
	{
		$user['id'] = $users->f('id');
		$user['username'] = $users->f('username');

		$users_array[] = $user;
	}
}
$counter = $count = count($users_array);
for ($i=0;$i<$count;$i++)
{
	$open = in_array($users_array[$i]['id'], $expanded);
	$sign = $open ? 'm' : 'p';
	$share_count = $fs->get_shares($users_array[$i]['id']);

	$shared_folders = array();
	$had_permission = false;
	while ($fs->next_record())
	{
		if (file_exists($fs->f('path')) && is_dir($fs->f('path')))
		{
			if (!isset($last_folder) || !$had_permission || strpos($fs->f('path'), $last_folder) === false)
			{
				if ($GO_SECURITY->has_permission($GO_SECURITY->user_id, $fs->f('acl_read')) || $GO_SECURITY->has_permission($GO_SECURITY->user_id, $fs->f('acl_write')))
				{
					$folder['path'] = $fs->f('path');
					$folder['name'] = basename($folder['path']);
					$folder['share_path'] = $folder['path'];
					$shared_folders[] = $folder;
					$had_permission = true;
				}else
				{
					$had_permission = false;
					$share_count--;
				}
			}else
			{
				$share_count--;
			}
		}else
		{
			$fs2 = new filesystem();
			$fs2->delete_share($fs->f('path'));
			$share_count--;
		}
		$last_folder = $fs->f('path');

	}
	if ($counter > 1)
	{	$leftside = $image['vertline'];
		if ($share_count > 0)
		{
			$node = $image[$sign.'node'];
		}else
		{
			$node = $image['emptynode'];
		}
	}else
	{
		$leftside = $image['blank'];
		if ($share_count > 0)
		{
			$node = $image[$sign.'lastnode'];
		}else
		{
			$node = $image['emptylastnode'];
		}
	}
	if ($share_count){
		echo '<table border="0" cellpadding="0" cellspacing="0">';
		echo '<tr><td><a href="'.$PHP_SELF.'?path='.urlencode($path).'&expand_path='.urlencode($users_array[$i]['id']).$arguments.'">'.$node.$image['user'].'</a></td>';
		echo '<td><a href="'.$PHP_SELF.'?path='.urlencode($path).'&expand_path='.urlencode($users_array[$i]['id']).$arguments.'">'.$users_array[$i]['username'].'</a></td></tr></table>';
	}
	if($open)
	{
		print_filesystem($shared_folders, $leftside,$arguments);
	}

	$counter--;
}
?>