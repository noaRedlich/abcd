<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0  Release date: 14 March 2003									//
// Version: 1.02 Release date: 18 April 2003									//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

class filesystem extends db
{
	var $search_results = array();

	function filesystem()
	{
		$this->db();
	}

	function is_owner($user_id, $path)
	{
		global $GO_CONFIG;
		require_once($GO_CONFIG->class_path.'users.class.inc');
		$users = new users();
		$res = $users->get_stock_user($user_id);
		if (!$res) 
		{
			$res = $users->get_user($user_id);
		}
		
		if ($res)
		{
			$home_path = $GO_CONFIG->file_storage_path.$users->f('username');

			if (strpos($path, $home_path) === 0)
			{
				return true; 
			}
		}
		return false;
	}

	function get_shares($user_id)
	{
		//ORDER BY PATH important so higher order shares come first
		$sql = "SELECT * FROM fsShares WHERE user_id='$user_id' ORDER BY path ASC";
		$this->query($sql);
		return $this->num_rows();
	}

	function add_share($user_id, $path)
	{
		$path = addslashes($path);

		global $GO_SECURITY;
		$acl_read = $GO_SECURITY->get_new_acl('read: '.$path);
		$acl_write = $GO_SECURITY->get_new_acl('write: '.$path);
		if($acl_read && $acl_write)
		{
			$sql = "INSERT INTO fsShares (user_id, path, acl_read, acl_write) VALUES ('$user_id', '$path', '$acl_read', '$acl_write')";
			return $this->query($sql);
		}else
		{
			$GO_SECURITY->delete_acl($acl_read);
			$GO_SECURITY->delete_acl($acl_write);
		}

		return false;
	}

	function delete_share($path)
	{
		$path = addslashes($path);

		if ($share = $this->get_share($path))
		{
			global $GO_SECURITY;
			$GO_SECURITY->delete_acl($share['acl_read']);
			$GO_SECURITY->delete_acl($share['acl_write']);

			$sql = "DELETE FROM fsShares WHERE path='$path'";
			return $this->query($sql);
		}

		return false;
	}
	
	
	function update_share($old_path, $new_path)
	{
		$new_path = addslashes($new_path);
		$old_path = addslashes($old_path);

		$sql = "UPDATE fsShares SET path='$new_path' WHERE path='$old_path'";
		return $this->query($sql);
	}

	function get_share($path)
	{
		$path = addslashes($path);
		$sql = "SELECT * FROM fsShares WHERE path='$path'";
		$this->query($sql);
		if($this->next_record())
		{
			return $this->Record;
		}
		return false;
	}

	function find_share($path)
	{
		if ($share = $this->get_share($path))
		{
			return $share;
		}else
		{
			global $GO_CONFIG;
			$parent = dirname($path);
			if ($parent == $GO_CONFIG->file_storage_path || $parent == '' || $parent == $GO_CONFIG->slash)
			{
				return false;
			}else
			{
				return $this->find_share($parent);
			}
		}
	}

	function has_read_permission($user_id, $path)
	{
		if ($this->is_owner($user_id, $path))
		{
			return is_readable($path);
		}else
		{
			if ($share = $this->find_share($path))
			{
				global $GO_SECURITY;
				if($GO_SECURITY->has_permission($user_id, $share['acl_read']))
				{
					return is_readable($path);
				}
			}
			global $GO_CONFIG;
			if (strpos($path, $GO_CONFIG->tmpdir) === 0)
			{
				return is_readable($path);
			}
		}
		return $this->has_write_permission($user_id, $path);
	}

	function has_write_permission($user_id, $path)
	{
		if ($this->is_owner($user_id, $path))
		{
			return is_writable($path);
		}else
		{
			global $GO_SECURITY;
			if ($share = $this->find_share($path))
			{
				if($GO_SECURITY->has_permission($user_id, $share['acl_write']))
				{
					return is_writable($path);
				}
			}
			global $GO_CONFIG;
			if (strpos($path, $GO_CONFIG->tmpdir) === 0)
			{
				return is_writable($path);
			}
		}
		return false;
	}

	function size($path)
	{
		if (is_dir($path))
		{
			$size = 0;
			$children = $this->get_folders($path);
			while ($child = array_shift($children))
			{
				$size += $this->size($child['path']);
			}

			$files = $this->get_files($path);
			while ($file = array_shift($files))
			{
				$size += $file['size'];
			}
			return $size;
		}else
		{
			return filesize($path);
		}
	}

	function move($source_path, $destination_path)
	{
		//do not move into own path
		$source_dir_count = count(explode('/',$source_path));
		$destination_dir_count = count(explode('/',$destination_path));

		if ((strpos($destination_path, $source_path) === 0) && ($destination_dir_count > $source_dir_count))
		{
			return false;
		}elseif($source_path == $destination_path)
		{
			return true;
		}else
		{
			if (is_dir($source_path))
			{
				if (!file_exists($destination_path))
				{
					if (!mkdir($destination_path))
					{
						return false;
					}
				}
				$files = $this->get_files($source_path);
				while ($file = array_shift($files))
				{
					if(!$this->move($file['path'], $destination_path.'/'.$file['name']))
					{
						return false;
					}
				}
				$children = $this->get_folders($source_path);
				while ($child = array_shift($children))
				{
					if (!$this->move($child['path'], $destination_path.'/'.$child['name']))
					{
						return false;
					}
				}
				return rmdir($source_path);
			}else
			{
				#rename fails when moving accross partitions
				#return rename($source_path, $destination_path);
				if (copy($source_path, $destination_path))
				{
					return unlink($source_path);
				}
			}
		}
	}

	function copy($source_path, $destination_path)
	{
		//do not copy into own path
		if (strpos($destination_path, $source_path) === 0)
		{
			return false;
		}else
		{
			if (is_dir($source_path))
			{
				if (!file_exists($destination_path))
				{
					if (!mkdir($destination_path))
					{
						return false;
					}
				}
				$files = $this->get_files($source_path);
				while ($file = array_shift($files))
				{
					if(!$this->copy($file['path'], $destination_path.'/'.$file['name']))
					{
						return false;
					}
				}
				$children = $this->get_folders($source_path);
				while ($child = array_shift($children))
				{
					if (!$this->copy($child['path'], $destination_path.'/'.$child['name']))
					{
						return false;
					}
				}
				return true;
			}else
			{
				return copy($source_path, $destination_path);
			}
		}
	}

	function delete($path)
	{
		global $GO_SECURITY;
		if (is_dir($path))
		{
			$children = $this->get_folders($path);
			while ($child = array_shift($children))
			{
				if (!$this->delete($child['path']))
				{
					return false;
				}
			}

			$files = $this->get_files($path);
			while ($file = array_shift($files))
			{
				if (!$this->delete($file['path']))
				{
					return false;
				}
			}
			if ($this->has_write_permission($GO_SECURITY->user_id, $path))
			{
				if ($this->get_share($path))
				{
					$this->delete_share($path);
				}
				return @rmdir($path);
			}else
			{
				return false;
			}
		}else
		{
			if ($this->has_write_permission($GO_SECURITY->user_id, $path))
			{
				return @unlink($path);
			}else
			{
				return false;
			}
		}
	}

	function get_parent_path($path)
	{
		$last_folder_pos = strrpos($path, '/');
		if (is_integer($last_folder_pos))
		{
			if ($last_folder_pos === 0)
			{
				return '/';
			}else
			{
				return substr($path, 0, $last_folder_pos);
			}
		}else
		{
			return false;
		}
	}

	//faster then get_folders_sorted
	function get_folders($path)
	{
		global $GO_CONFIG;
		$slash  = $GO_CONFIG->slash;
		if (substr($path, -1) != $slash) $path .= $slash;
		$folders = array();
		if($dir = opendir($path))
		{
			while($item=readdir($dir))
			{
				$folder_path = $path.$item;
				if (is_dir($folder_path) && $item != "." && $item != "..")
				{
					$folder['path'] = $folder_path;
					$folder['name'] = basename($folder_path);
					$folder['mtime'] = filemtime($folder_path);
					$folder['size'] = filesize($folder_path);
					$folder['type'] = filetype($folder_path);
					$folders[] = $folder;
				}
			}
			closedir($dir);
		}
		return $folders;
	}

	#returns all subfolders of a folder sorted based on the result of a function
	#passed that is performed on the pathname. (For example filesize();)
	function get_folders_sorted($path,$sort_field='basename',$sort_direction='ASC')
	{
		global $DOCUMENT_ROOT;
		global $HTTP_HOST;

		$folders = array();
		$slash = stristr(PHP_OS, 'Win') ? '\\' : '/';
		if (substr($path, -1) != $slash) $path .= $slash;
		if(strstr($HTTP_HOST, $path))
		{
			$url = str_replace($DOCUMENT_ROOT, 'http://'.$HTTP_HOST, $path);
			if ($slash == '\\')
			{
				$url = str_replace('\\','/',$url);
			}
		}
		if (function_exists($sort_field) && is_dir($path))
		{
			$sorted_list = array();

			if(@$dir = opendir($path))
			{
				while($item=readdir($dir))
				{
					$folder_path = $path.$item;

					if (is_dir($folder_path) && $item != "." && $item != "..")
					{
						$key_id = 0;
						$first_key = strtolower($sort_field($folder_path));
						$key = $first_key;
						while (array_key_exists($key, $sorted_list))
						{
							$key = $first_key.'_'.$key_id;
							$key_id++;
						}
						$sorted_list[$key] = $folder_path;
					}
				}
				closedir($dir);

				if ($sort_direction == 'ASC')
				{
					ksort($sorted_list);
				}else
				{
					krsort($sorted_list);
				}

				while ($item=array_shift($sorted_list))
				{
					$folder = array();
					$folder['path'] = $item;
					$folder['name'] = basename($item);
					$folder['mtime'] = filemtime($item);
					$folder['size'] = filesize($item);
					$folder['type'] = filetype($item);
					if(isset($url))
					{
						$folder['url'] = $url.$folder['name'];
					}
					$folders[] = $folder;
				}
			}
		}
		return $folders;
	}

	//faster then get_files_sorted
	function get_files($path)
	{
		$slash = stristr(PHP_OS, 'Win') ? '\\' : '/';
		if (substr($path, -1) != $slash) $path .= $slash;
		$files = array();
		if($dir = @opendir($path))
		{
			while($item=readdir($dir))
			{
				$file_path = $path.$item;
				if (!is_dir($file_path))
				{
					$file['path'] = $file_path;
					$file['name'] = basename($file_path);
					$file['size'] = filesize($file_path);
					$file['mtime'] = filemtime($file_path);
					$file['type'] = filemimefriendly($file_path);

					$files[] = $file;
				}
			}
			closedir($dir);
		}
		return $files;
	}

	#returns all subfolders of a folder sorted based on the result of a function
	#passed that is performed on the pathname. (For example filesize();)
	function get_files_sorted($path,$sort_field='basename',$sort_direction='ASC')
	{
		global $DOCUMENT_ROOT;
		global $HTTP_HOST, $GO_CONFIG;

		$files = array();

		$slash = $GO_CONFIG->slash;
		if (substr($path, -1) != $slash) $path .= $slash;

		if(strstr($HTTP_HOST, $path))
		{
			$url = str_replace($DOCUMENT_ROOT, 'http://'.$HTTP_HOST, $path);
			if ($slash == '\\')
			{
				$url = str_replace('\\','/',$url);
			}
		}
		if (function_exists($sort_field) && is_dir($path))
		{
			$sorted_list = array();

			if($dir = @opendir($path))
			{
				while($item=readdir($dir))
				{
					$file = $path.$item;
					if (!is_dir($file))
					{
						$key_id = 0;
						$first_key = strtolower($sort_field($file));
						$key = $first_key;
						while (array_key_exists($key, $sorted_list))
						{
							$key = $first_key.'_'.$key_id;
							$key_id++;
						}
						$sorted_list[$key] = $file;
					}
				}
				closedir($dir);

				if ($sort_direction == 'ASC')
				{
					 ksort($sorted_list);
				}else
				{
					krsort($sorted_list);
				}

				while ($item=array_shift($sorted_list))
				{
					$file = array();
					$file['path'] = $item;
					$file['name'] = basename($item);
					$file['mtime'] = filemtime($item);
					$file['size'] = filesize($item);
					$file['type'] = filemimefriendly($item);

					if(isset($url))
					{
						$file['url'] = $url.$file['name'];
					}
					$files[] = $file;
				}
			}

		}
		return $files;
	}

	function search($path, $keyword, $modified_later_then=0, $modified_earlier_then=0)
	{
		global $GO_SECURITY;

		if ($modified_earlier_then == 0)
		{
			$modified_earlier_then = time();
		}

		if($this->has_read_permission($GO_SECURITY->user_id, $path))
		{
			$folders = $this->get_folders($path);
			while ($folder = array_shift($folders))
			{
				$this->search($folder['path'], $keyword, $modified_later_then, $modified_earlier_then);
			}
		}
		$folder['path'] = $path;
		$folder['name'] = basename($path);
		$folder['mtime'] = filemtime($path);
		$folder['size'] = filesize($path);
		$folder['type'] = filetype($path);

		if (stristr(basename($path), $keyword) && $modified_later_then < $folder['mtime'] && $modified_earlier_then > $folder['mtime'])
		{
			$this->search_results[] = $folder;
		}

		$files = $this->get_files($path);
		while ($file = array_shift($files))
		{
			if (stristr($file['name'], $keyword) && $modified_later_then < $file['mtime'] && $modified_earlier_then > $file['mtime'])
			{
				$this->search_results[] = $file;
			}
		}

		return $this->search_results;
	}

	function delete_user($user_id)
	{
		$fs = new filesystem();
		$this->get_shares($user_id);
		while($this->next_record())
		{
			$fs->delete_share($this->f('path'));
		}
	}
}
?>